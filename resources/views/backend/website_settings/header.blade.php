@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Website Header') }}</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8 mx-auto">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">{{ translate('Header Setting') }}</h6>
			</div>
			<div class="card-body">
				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
					@csrf
					<!-- Header Logo -->
					<div class="form-group row">
	                    <label class="col-md-3 col-from-label">{{ translate('Header Logo') }}</label>
						<div class="col-md-8">
		                    <div class=" input-group " data-toggle="aizuploader" data-type="image">
		                        <div class="input-group-prepend">
		                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
		                        </div>
		                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
								<input type="hidden" name="types[]" value="header_logo">
		                        <input type="hidden" name="header_logo" class="selected-files" value="{{ get_setting('header_logo') }}">
		                    </div>
		                    <div class="file-preview"></div>
						</div>
	                </div>
					<!-- Show Language Switcher -->
                    <div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Show Language Switcher?')}}</label>
						<div class="col-md-8">
							<label class="aiz-switch aiz-switch-success mb-0">
								<input type="hidden" name="types[]" value="show_language_switcher">
								<input type="checkbox" name="show_language_switcher" @if( get_setting('show_language_switcher') == 'on') checked @endif>
								<span></span>
							</label>
						</div>
					</div>
					<!-- Show Currency Switcher -->
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">{{translate('Show Currency Switcher?')}}</label>
						<div class="col-md-8">
							<label class="aiz-switch aiz-switch-success mb-0">
								<input type="hidden" name="types[]" value="show_currency_switcher">
								<input type="checkbox" name="show_currency_switcher" @if( get_setting('show_currency_switcher') == 'on') checked @endif>
								<span></span>
							</label>
						</div>
					</div>
					<!-- Enable stikcy header -->
	                <div class="form-group row">
						<label class="col-md-3 col-from-label">{{translate('Enable stikcy header?')}}</label>
						<div class="col-md-8">
							<label class="aiz-switch aiz-switch-success mb-0">
								<input type="hidden" name="types[]" value="header_stikcy">
								<input type="checkbox" name="header_stikcy" @if( get_setting('header_stikcy') == 'on') checked @endif>
								<span></span>
							</label>
						</div>
					</div>
					<div class="border-top pt-3">
						<!-- Enable Marquee Banner -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Enable Marquee Banner?')}}</label>
							<div class="col-md-8">
								<label class="aiz-switch aiz-switch-success mb-0">
									<input type="hidden" name="types[]" value="enable_marquee_banner">
									<input type="checkbox" id="enable_marquee_banner" name="enable_marquee_banner" @if( get_setting('enable_marquee_banner') == 'on') checked @endif>
									<span></span>
								</label>
							</div>
						</div>

						<!-- Marquee Text -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Text')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_text">
									<textarea class="form-control" id="marquee_text_field" placeholder="{{ translate('Enter the marquee text') }}" name="marquee_text" rows="3">{{ get_setting('marquee_text') }}</textarea>
									<small class="form-text text-muted">{{ translate('Usa la sintaxis [icon:nombre] para agregar iconos. Ejemplo: [icon:star] Oferta') }}</small>
								</div>

								<!-- Preview de iconos en tiempo real -->
								<div class="mt-3 p-3" style="background: #f8f9fa; border-radius: 4px; border: 1px solid #ddd;">
									<label><strong>{{ translate('Previsualización') }}</strong></label>
									<div id="marquee_preview" style="font-size: 14px; padding: 10px; background: #e74c3c; color: white; border-radius: 4px; min-height: 40px; display: flex; align-items: center;">
										{{ get_setting('marquee_text') ? get_setting('marquee_text') : 'El texto aparecerá aquí...' }}
									</div>
								</div>
								
								<!-- Icon Selector -->
								<div class="mt-3">
									<label class="d-block mb-2"><strong>{{ translate('Popular Icons - Click to insert') }}</strong></label>
									<div class="icon-selector-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(45px, 1fr)); gap: 8px;">
									<!-- Fire Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="fire" title="Fire" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-fire" style="color: #FF5722;"></i>
									</button>
									<!-- Star Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="star" title="Star" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-star" style="color: #FFD700;"></i>
									</button>
									<!-- Gift Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="gift" title="Gift" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-gift" style="color: #E91E63;"></i>
									</button>
									<!-- Truck Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="truck" title="Truck" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-truck" style="color: #2196F3;"></i>
									</button>
									<!-- Heart Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="heart" title="Heart" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-heart" style="color: #FF1744;"></i>
									</button>
									<!-- Bell Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="bell" title="Bell" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-bell" style="color: #FF9800;"></i>
									</button>
									<!-- Info Circle Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="info-circle" title="Info Circle" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-info-circle" style="color: #00BCD4;"></i>
									</button>
									<!-- Discount/Tag Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="tag" title="Tag" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-tag" style="color: #673AB7;"></i>
									</button>
									<!-- Phone Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="phone" title="Phone" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-phone" style="color: #4CAF50;"></i>
									</button>
									<!-- Clock Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="clock" title="Clock" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-clock" style="color: #9C27B0;"></i>
									</button>
									<!-- Check Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="check-circle" title="Check" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-check-circle" style="color: #8BC34A;"></i>
									</button>
									<!-- Rocket Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="rocket" title="Rocket" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-rocket" style="color: #F44336;"></i>
									</button>
									<!-- Smile Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="smile" title="Smile" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-smile" style="color: #FBC02D;"></i>
									</button>
									<!-- Thumbs Up Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="thumbs-up" title="Thumbs Up" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-thumbs-up" style="color: #03A9F4;"></i>
									</button>
									<!-- Exclamation Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="exclamation-circle" title="Exclamation" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-exclamation-circle" style="color: #FF5252;"></i>
									</button>
									<!-- Zap/Lightning Icon -->
									<button type="button" class="btn btn-sm btn-light icon-btn" data-icon="bolt" title="Lightning" style="border: 1px solid #ddd; padding: 10px; font-size: 18px; cursor: pointer; border-radius: 4px;">
										<i class="la la-bolt" style="color: #FFCA28;"></i>
									</button>
									</div>
								</div>

								<script>
									document.addEventListener('DOMContentLoaded', function() {
										const textarea = document.getElementById('marquee_text_field');
										const preview = document.getElementById('marquee_preview');

										function updatePreview() {
											let text = textarea.value;
											// Mapa de colores para cada icono
											const iconColors = {
												'fire': '#FF5722',
												'star': '#FFD700',
												'gift': '#E91E63',
												'truck': '#2196F3',
												'heart': '#FF1744',
												'bell': '#FF9800',
												'info-circle': '#00BCD4',
												'tag': '#673AB7',
												'phone': '#4CAF50',
												'clock': '#9C27B0',
												'check-circle': '#8BC34A',
												'rocket': '#F44336',
												'smile': '#FBC02D',
												'thumbs-up': '#03A9F4',
												'exclamation-circle': '#FF5252',
												'bolt': '#FFCA28'
											};
											
											// Reemplazar iconos con etiquetas de Line Awesome coloreadas
											text = text.replace(/\[icon:([^\]]+)\]/g, function(match, iconName) {
												const color = iconColors[iconName] || '#333';
												return '<i class="la la-' + iconName + '" style="margin: 0 8px; color: ' + color + ';"></i>';
											});
											preview.innerHTML = text || 'El texto aparecerá aquí...';
										}

										// Actualizar previsualización en tiempo real
										textarea.addEventListener('input', updatePreview);
										
										// Inicializar previsualización
										updatePreview();

										// Insertar iconos al hacer clic
										document.querySelectorAll('.icon-btn').forEach(button => {
											button.addEventListener('click', function(e) {
												e.preventDefault();
												const icon = this.getAttribute('data-icon');
												const cursorPos = textarea.selectionStart;
												const text = textarea.value;
												const newText = text.slice(0, cursorPos) + ' [icon:' + icon + '] ' + text.slice(cursorPos);
												textarea.value = newText;
												textarea.focus();
												textarea.setSelectionRange(cursorPos + 10 + icon.length, cursorPos + 10 + icon.length);
												updatePreview();
											});
										});
									});
								</script>
							</div>
						</div>

						<!-- Marquee Speed -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Speed')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_speed">
									<input type="number" class="form-control" placeholder="5" name="marquee_speed" value="{{ get_setting('marquee_speed', '5') }}" min="0.1" max="500" step="0.1">
									<small class="form-text text-muted">{{ translate('Sin restricciones. Valores menores = MAS LENTO. Ejemplo: 0.5=muy lento, 2=lento, 5=normal, 10=rápido, 20=muy rápido') }}</small>
								</div>
							</div>
						</div>

						<!-- Marquee Font Size -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Font Size')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_font_size">
									<input type="number" class="form-control" placeholder="14" name="marquee_font_size" value="{{ get_setting('marquee_font_size', '14') }}" min="10" max="32">
									<small class="form-text text-muted">{{ translate('In pixels') }}</small>
								</div>
							</div>
						</div>

						<!-- Marquee Font Weight -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Font Weight')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_font_weight">
									<select class="form-control" name="marquee_font_weight">
										<option value="normal" @if(get_setting('marquee_font_weight') == 'normal') selected @endif>{{ translate('Normal') }}</option>
										<option value="500" @if(get_setting('marquee_font_weight') == '500') selected @endif>{{ translate('Medium') }}</option>
										<option value="600" @if(get_setting('marquee_font_weight') == '600') selected @endif>{{ translate('Semi Bold') }}</option>
										<option value="700" @if(get_setting('marquee_font_weight') == '700') selected @endif>{{ translate('Bold') }}</option>
										<option value="800" @if(get_setting('marquee_font_weight') == '800') selected @endif>{{ translate('Extra Bold') }}</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Marquee Text Color -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Text Color')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_text_color">
									<input type="color" class="form-control" style="height: 45px;" name="marquee_text_color" value="{{ get_setting('marquee_text_color', '#ffffff') }}">
								</div>
							</div>
						</div>

						<!-- Marquee Background Color -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Background Color')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_bg_color">
									<input type="color" class="form-control" style="height: 45px;" name="marquee_bg_color" value="{{ get_setting('marquee_bg_color', '#e74c3c') }}">
								</div>
							</div>
						</div>

						<!-- Marquee Animation Type -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Animation Type')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_animation">
									<select class="form-control" name="marquee_animation">
										<option value="scroll" @if(get_setting('marquee_animation') == 'scroll') selected @endif>{{ translate('Scroll') }}</option>
										<option value="slide" @if(get_setting('marquee_animation') == 'slide') selected @endif>{{ translate('Slide') }}</option>
										<option value="bounce" @if(get_setting('marquee_animation') == 'bounce') selected @endif>{{ translate('Bounce') }}</option>
										<option value="fade" @if(get_setting('marquee_animation') == 'fade') selected @endif>{{ translate('Fade') }}</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Marquee Padding -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marquee Padding (Y)')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marquee_padding">
									<input type="number" class="form-control" placeholder="12" name="marquee_padding" value="{{ get_setting('marquee_padding', '12') }}" min="5" max="30">
									<small class="form-text text-muted">{{ translate('Vertical padding in pixels') }}</small>
								</div>
							</div>
						</div>
					</div>

					<div class="border-top pt-3">
						<!-- Topbar Banner Large (Legacy) -->
						<div class="form-group row">
		                    <label class="col-md-3 col-from-label">{{ translate('Topbar Banner Large (Legacy)') }}</label>
							<div class="col-md-8">
			                    <div class=" input-group " data-toggle="aizuploader" data-type="image">
			                        <div class="input-group-prepend">
			                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
			                        </div>
			                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="topbar_banner">
			                        <input type="hidden" name="topbar_banner" class="selected-files" value="{{ get_setting('topbar_banner') }}">
			                    </div>
			                    <div class="file-preview"></div>
                                <small>{{ translate('Will be shown in large device (only if marquee is disabled)') }}</small>
							</div>
		                </div>
						<!-- Topbar Banner Medium -->
						<div class="form-group row">
		                    <label class="col-md-3 col-from-label">{{ translate('Topbar Banner Medium (Legacy)') }}</label>
							<div class="col-md-8">
			                    <div class=" input-group " data-toggle="aizuploader" data-type="image">
			                        <div class="input-group-prepend">
			                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
			                        </div>
			                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="topbar_banner_medium">
			                        <input type="hidden" name="topbar_banner_medium" class="selected-files" value="{{ get_setting('topbar_banner_medium') }}">
			                    </div>
			                    <div class="file-preview"></div>
                                <small>{{ translate('Will be shown in medium device (only if marquee is disabled)') }}</small>
							</div>
		                </div>
						<!-- Topbar Banner Small -->
						<div class="form-group row">
		                    <label class="col-md-3 col-from-label">{{ translate('Topbar Banner Small (Legacy)') }}</label>
							<div class="col-md-8">
			                    <div class=" input-group " data-toggle="aizuploader" data-type="image">
			                        <div class="input-group-prepend">
			                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
			                        </div>
			                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="topbar_banner_small">
			                        <input type="hidden" name="topbar_banner_small" class="selected-files" value="{{ get_setting('topbar_banner_small') }}">
			                    </div>
			                    <div class="file-preview"></div>
                                <small>{{ translate('Will be shown in small device (only if marquee is disabled)') }}</small>
							</div>
		                </div>
						<!-- Topbar Banner Link -->
		                <div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Topbar Banner Link')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="topbar_banner_link">
									<input type="text" class="form-control" placeholder="{{ translate('Link with') }} http:// {{ translate('or') }} https://" name="topbar_banner_link" value="{{ get_setting('topbar_banner_link') }}">
								</div>
							</div>
						</div>
					</div>
                    <div class="border-top pt-3">
						<!-- Help line number -->
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Help line number')}}</label>
							<div class="col-md-8">
								<div class="form-group">
									<input type="hidden" name="types[]" value="helpline_number">
									<input type="text" class="form-control" placeholder="{{ translate('Help line number') }}" name="helpline_number" value="{{ get_setting('helpline_number') }}">
								</div>
							</div>
						</div>
                    </div>
					<div class="border-top pt-3">
						<!-- Header Nav Menu Text Color -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label mb-md-0">{{translate('Header Nav Menu Text Color')}}</label>
							<div class="col-md-8 d-flex">
								<input type="hidden" name="types[]" value="header_nav_menu_text">
								<div class="radio mar-btm mr-3 d-flex align-items-center">
									<input id="header_nav_menu_text_light" class="magic-radio" type="radio" name="header_nav_menu_text" value="light" @if((get_setting('header_nav_menu_text') == 'light') ||  (get_setting('header_nav_menu_text') == null)) checked @endif>
									<label for="header_nav_menu_text_light" class="mb-0 ml-2">{{translate('Light')}}</label>
								</div>
								<div class="radio mar-btm mr-3 d-flex align-items-center">
									<input id="header_nav_menu_text_dark" class="magic-radio" type="radio" name="header_nav_menu_text" value="dark" @if(get_setting('header_nav_menu_text') == 'dark') checked @endif>
									<label for="header_nav_menu_text_dark" class="mb-0 ml-2">{{translate('Dark')}}</label>
								</div>
							</div>
						</div>
						<!-- Header Nav Menus -->
						<label class="">{{translate('Header Nav Menu')}}</label>
						<div class="header-nav-menu">
							<input type="hidden" name="types[]" value="header_menu_labels">
							<input type="hidden" name="types[]" value="header_menu_links">
							@if (get_setting('header_menu_labels') != null)
								@foreach (json_decode( get_setting('header_menu_labels'), true) as $key => $value)
									<div class="row gutters-5">
										<div class="col-4">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{translate('Label')}}" name="header_menu_labels[]" value="{{ $value }}">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<input type="text" class="form-control" placeholder="{{ translate('Link with') }} http:// {{ translate('or') }} https://" name="header_menu_links[]" value="{{ json_decode(App\Models\BusinessSetting::where('type', 'header_menu_links')->first()->value, true)[$key] }}">
											</div>
										</div>
										<div class="col-auto">
											<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
												<i class="las la-times"></i>
											</button>
										</div>
									</div>
								@endforeach
							@endif
						</div>
						<button
							type="button"
							class="btn btn-soft-secondary btn-sm"
							data-toggle="add-more"
							data-content='<div class="row gutters-5">
								<div class="col-4">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="{{translate('Label')}}" name="header_menu_labels[]">
									</div>
								</div>
								<div class="col">
									<div class="form-group">
										<input type="text" class="form-control" placeholder="{{ translate('Link with') }} http:// {{ translate('or') }} https://" name="header_menu_links[]">
									</div>
								</div>
								<div class="col-auto">
									<button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
										<i class="las la-times"></i>
									</button>
								</div>
							</div>'
							data-target=".header-nav-menu">
							{{ translate('Add New') }}
						</button>
					</div>
					<!-- Update Button -->
					<div class="mt-4 text-right">
						<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
