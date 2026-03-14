<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Http\Resources\PosProductCollection;
use App\Models\Cart;
use App\Utility\FontUtility;
use App\Utility\PosUtility;
use Session;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Hash;

class PosController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:pos_manager'])->only('admin_index');
        $this->middleware(['permission:pos_configuration'])->only('pos_activation');
    }

    public function index()
    {
        $customers = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc')->get();
        return view('backend.pos.index', compact('customers'));
    }

    public function search(Request $request)
    {
        $products = PosUtility::product_search($request->only('category', 'brand', 'keyword'));

        $stocks = new PosProductCollection($products);
        $stocks->appends(['keyword' =>  $request->keyword, 'category' => $request->category, 'brand' => $request->brand]);
        return $stocks;
    }

    // Add product To cart
    public function addToCart(Request $request)
    {   
        $stockId    = $request->stock_id;
        $userID     = Session::get('pos.user_id');
        $temUserId  = Session::get('pos.temp_user_id');
        
        \Log::info('PosController::addToCart START', [
            'stock_id' => $stockId,
            'user_id' => $userID,
            'temp_user_id' => $temUserId,
            'session_keys' => Session::all()
        ]);
        
        if (!$temUserId && !$userID) {
            $temUserId = bin2hex(random_bytes(10));
            Session::put('pos.temp_user_id', $temUserId);
            \Log::info('Created new temp_user_id: ' . $temUserId);
        }
        
        $response = PosUtility::addToCart($stockId, $userID, $temUserId);
        
        \Log::info('addToCart response', [
            'success' => $response['success'],
            'message' => $response['message']
        ]);
        
        // Ensure session is available for view rendering
        Session::put('pos.user_id', $userID);
        Session::put('pos.temp_user_id', $temUserId);
        Session::save();
        
        \Log::info('Session before render', [
            'pos.user_id' => Session::get('pos.user_id'),
            'pos.temp_user_id' => Session::get('pos.temp_user_id')
        ]);
        
        $cartView = view('backend.pos.cart')->render();
        
        \Log::info('Cart view rendered', [
            'view_length' => strlen($cartView)
        ]);
        
        return array(
            'success' => $response['success'],
            'message' => $response['message'],
            'view' => $cartView
        );
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $cart = Cart::find($request->cartId);
        $response = PosUtility::updateCartItemQuantity($cart, $request->only(['cartId', 'quantity']));

        return array('success' => $response['success'], 'message' => $response['message'], 'view' => view('backend.pos.cart')->render());
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        Cart::where('id', $request->id)->delete();
        return view('backend.pos.cart');
    }

    //Shipping Address for admin
    public function getShippingAddress(Request $request)
    {
        Session::forget('pos.shipping_info');
        $user_id = $request->id;
        return ($user_id == '') ? view('backend.pos.guest_shipping_address') : view('backend.pos.shipping_address', compact('user_id'));
    }

    public function set_shipping_address(Request $request)
    {
        $data = PosUtility::get_shipping_address($request);

        $shipping_info = $data;
        $request->session()->put('pos.shipping_info', $shipping_info);
    }

    // Update user Cart data when user is changed 
    public function updateSessionUserCartData(Request $request)
    {
        PosUtility::updateCartOnUserChange($request->only(['userId']));
        return view('backend.pos.cart');
    }

    //set Discount
    public function setDiscount(Request $request)
    {
        if ($request->discount >= 0) {
            Session::put('pos.discount', $request->discount);
        }
        return view('backend.pos.cart');
    }

    //set Shipping Cost
    public function setShipping(Request $request)
    {
        if ($request->shipping != null) {
            Session::put('pos.shipping', $request->shipping);
        }
        return view('backend.pos.cart');
    }

    //order summary
    public function get_order_summary(Request $request)
    {
        try {
            \Log::info('get_order_summary called');
            $carts = get_pos_user_cart();
            $deliveryType = $request->get('delivery_type', 'shipping');
            \Log::info('get_order_summary - carts found: ' . count($carts));
            \Log::info('get_order_summary - delivery_type: ' . $deliveryType);
            
            foreach ($carts as $cart) {
                \Log::info('Cart item:', [
                    'id' => $cart->id,
                    'product_id' => $cart->product_id,
                    'product_exists' => $cart->product ? true : false
                ]);
            }
            
            return view('backend.pos.order_summary', compact('deliveryType'));
        } catch (\Exception $e) {
            \Log::error('get_order_summary error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    //order place
    public function order_store(Request $request)
    {
        try {
            \Log::info('order_store - Request received', $request->all());
            
            $request->merge([
                'temp_user_id' => Session::get('pos.temp_user_id'), 
                'shippingInfo' => Session::get('pos.shipping_info'), 
                'shippingCost' => Session::get('pos.shipping', 0), 
                'discount' => Session::get('pos.discount'),
                'delivery_type' => $request->get('delivery_type', 'shipping')
            ]);
            
            \Log::info('order_store - After merge', $request->all());
            
            $response = PosUtility::orderStore($request->except(['_token']));
            
            \Log::info('order_store - Response:', $response);

            if ($response['success']) {
                Session::forget('pos.shipping_info');
                Session::forget('pos.shipping');
                Session::forget('pos.discount');
                Session::forget('pos.user_id');
                Session::forget('pos.temp_user_id');
            }

            return $response;
        } catch (\Exception $e) {
            \Log::error('order_store - Exception: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => 0,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function configuration()
    {
        return view('backend.pos.pos_activation');
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);

        $print_width = get_setting('print_width');
        if ($print_width == null) {
            flash(translate('Thermal printer size is not given in POS configuration'))->warning();
            return back();
        }

        $pdf_style_data = FontUtility::get_font_family();

        $html = view('backend.pos.thermal_invoice', compact('order', 'pdf_style_data'));

        $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => [$print_width, 1000]]);
        $mpdf->WriteHTML($html);
        // $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->page   = 0;
        $mpdf->state  = 0;
        unset($mpdf->pages[0]);
        // The $p needs to be passed by reference
        $p = 'P';
        // dd($mpdf->y);
        $mpdf->_setPageSize(array($print_width, $mpdf->y), $p);

        $mpdf->addPage();
        $mpdf->WriteHTML($html);

        $mpdf->Output('order-' . $order->code . '.pdf', 'I');
    }

    // Get POS sales list
    public function getSalesList(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $page = $request->get('page', 1);
            $customerSearch = trim($request->get('customer', ''));
            $dateSearch = trim($request->get('date', ''));
            
            // Get recent POS orders with filters
            $query = Order::where('order_from', 'pos')
                ->with('user', 'orderDetails.product')
                ->orderBy('created_at', 'desc');
            
            // Filter by customer name
            if (!empty($customerSearch)) {
                $query->where(function($q) use ($customerSearch) {
                    // Search in users table (registered customers)
                    $q->whereHas('user', function($user) use ($customerSearch) {
                        $user->where('name', 'like', '%' . $customerSearch . '%');
                    });
                    // If search term looks like walk-in customer, include those too
                    if (stripos('walk-in customer', $customerSearch) !== false || 
                        stripos('walk in', $customerSearch) !== false ||
                        stripos('cliente', $customerSearch) !== false) {
                        $q->orWhereNull('user_id');
                    }
                });
            }
            
            // Filter by date
            if (!empty($dateSearch)) {
                $query->whereDate('created_at', $dateSearch);
            }
            
            $orders = $query->paginate($limit, ['*'], 'page', $page);
            
            return view('backend.pos.sales_list', compact('orders'));
        } catch (\Exception $e) {
            \Log::error('getSalesList error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register a new customer from POS interface
     */
    public function registerCustomer(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:20',
                'password' => 'required|string|min:6|confirmed',
            ]);

            // Create new user
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? '';
            $user->password = Hash::make($validated['password']);
            $user->user_type = 'customer';
            $user->email_verified_at = now(); // Auto-verify email for POS registration
            $user->save();

            return response()->json([
                'success' => true,
                'user_id' => $user->id,
                'name' => $user->name,
                'message' => translate('Customer registered successfully')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('registerCustomer error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}
