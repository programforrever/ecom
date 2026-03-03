# Flash Deal Setup Guide

## Overview
The "Ofertas del Día" (Today's Deals) card has been successfully implemented in the frontend. It displays active flash deals with products directly on the homepage.

## Current Status
✅ **Working** - The offers card is displaying correctly on the homepage with products, prices, and countdown timer.

## How It Works

### Backend Components
1. **Helper Functions** (`app/Http/Helpers.php`):
   - `get_featured_flash_deal()` - Retrieves the active featured flash deal
   - `get_flash_deal_products($flash_deal_id)` - Gets products in a specific flash deal

2. **Models**:
   - `App\Models\FlashDeal` - Main flash deal model
   - `App\Models\FlashDealProduct` - Links products to flash deals
   - `App\Models\Product` - Product model

3. **Database Tables**:
   - `flash_deals` - Stores flash deal information
   - `flash_deal_products` - Junction table linking products to deals

### Frontend Components
1. **Integration Point**: `/resources/views/frontend/nuevotema/index.blade.php`
   - Lines 1159-1166: Includes the todays_deals_card partial

2. **Partial**: `/resources/views/frontend/nuevotema/partials/todays_deals_card.blade.php`
   - Displays flash deal title, products, countdown timer, and navigation

### Features Included
- 📦 Product carousel with navigation buttons
- 💰 Price display with discount percentage badges
- ⏱️ Countdown timer (Days, Hours, Minutes, Seconds)
- ⭐ Product ratings based on database rating field
- 🎨 Styled with custom colors:
  - Product names: #0961b3 (blue)
  - Prices: #a90000 (red)
  - Stars: #ffc107 (gold)
  - Discount badge: #e74c3c (red background)

## Creating Flash Deals

### Option 1: Using Admin Panel
1. Navigate to Admin Dashboard
2. Go to Marketing → Flash Deals
3. Click "Add New Flash Deal"
4. Fill in:
   - Title
   - Date Range (Start/End dates)
   - Background Color
   - Text Color
   - Products to include (with individual discounts)
5. Check "Featured" to display on homepage
6. Save

### Option 2: Using Artisan Command
```bash
php artisan setup:flash-deal
```
This command creates a test flash deal with 3 sample products (only for testing)

### Database Requirements for Flash Deal

To display on homepage, a flash deal must:
- Have `status = 1` (Active)
- Have `featured = 1` (Featured)
- Have `start_date ≤ current time` (Already started)
- Have `end_date ≥ current time` (Not expired)
- Have at least one product in `flash_deal_products` table

## Test Data
A test flash deal has been created with:
- **ID**: 2
- **Title**: Ofertas del Día
- **Duration**: 7 days from creation
- **Products**: 3 sample clothing items
- **Discount**: 10% per product

To remove test data and create your own:
```sql
DELETE FROM flash_deal_products WHERE flash_deal_id = 2;
DELETE FROM flash_deals WHERE id = 2;
```

## Customization

### Colors
Edit `/resources/views/frontend/nuevotema/partials/todays_deals_card.blade.php`:
- Line 177-179: Discount badge colors
- Line 152: Countdown block styling
- Line 130-135: Product info styling

### Layout
- Slider transitions: Line 42-44
- Number of products visible: Modify `.take(3)` in PHP section (line 9)
- Navigation button styling: Lines 91-116

### Translations
- Spanish card title: Line 228 ("🔥 Ofertas del Día")
- Empty state message: Line 291 ("No hay ofertas disponibles")
- Edit in FlashDealTranslation model for multi-language support

## Troubleshooting

### No offers showing?
1. Verify a flash deal exists: Admin → Marketing → Flash Deals
2. Check that it has `Featured` checkbox enabled
3. Confirm dates are within current date range
4. Ensure products are assigned to the flash deal
5. Clear views cache: `php artisan view:clear`

### Products not displaying?
1. Check that flash deal has products: `SELECT * FROM flash_deal_products WHERE flash_deal_id = 2;`
2. Verify products exist and are published: `SELECT * FROM products WHERE id IN (...)`
3. Check for JavaScript errors in browser console
4. Verify discount type is set correctly in product record

### Images not loading?
1. Check thumbnail image path in products table
2. Use `get_image()` helper (already implemented)
3. Verify external links are accessible

## File Structure
```
resources/views/
└── frontend/
    └── nuevotema/
        ├── index.blade.php (integration point)
        └── partials/
            ├── todays_deals_card.blade.php (main component)
            └── product_box_1.blade.php (product cards - uses same styling)
```

## Current Implementation
- ✅ Flash deal retrieval with date filtering
- ✅ Product carousel with arrow navigation
- ✅ Countdown timer with real-time updates
- ✅ Product pricing with discounts
- ✅ Dynamic star ratings
- ✅ Responsive design for mobile
- ✅ Brand colors applied (#0961b3, #a90000)

## Version
- Component: v1.0
- Theme: nuevotema
- Database: Fully compatible with current schema
