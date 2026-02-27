@extends('backend.layouts.app')

@section('content')
<style>
:root {
    --bg: #e8edf2;
    --shadow-light: #ffffff;
    --shadow-dark: #c5cdd8;
    --text-primary: #3a4a5c;
    --text-secondary: #6b7a8d;
    --accent: #e07b3f;
    --accent-light: #f5a06a;
    --accent-hover: #cf6a2e;
}

.social-page {
    background: var(--bg);
    min-height: 100vh;
    padding: 30px;
    font-family: 'Segoe UI', sans-serif;
}

.social-title {
    color: var(--text-primary);
    font-weight: 700;
    font-size: 1.4rem;
    letter-spacing: 0.5px;
    margin-bottom: 28px;
    text-shadow: 1px 1px 0 var(--shadow-light);
}

.social-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    align-items: stretch;
}

.social-card {
    background: var(--bg);
    border-radius: 22px;
    padding: 22px 20px 20px;
    box-shadow: 8px 8px 18px var(--shadow-dark), -8px -8px 18px var(--shadow-light);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.social-card form {
    display: flex;
    flex-direction: column;
    flex: 1;
}
.fields-wrap {
    display: flex;
    flex-direction: column;
    gap: 14px;
    flex: 1;
}

.card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 8px;
}
.card-head-title {
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.6px;
    line-height: 1.3;
    padding-bottom: 10px;
    position: relative;
}
.card-head-title::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 34px; height: 3px;
    background: var(--accent);
    border-radius: 2px;
    box-shadow: 0 0 8px var(--accent-light);
}

.help-btn {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: var(--bg);
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 900;
    color: var(--accent);
    flex-shrink: 0;
    box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
    transition: all 0.15s ease;
    display: flex; align-items: center; justify-content: center;
}
.help-btn:hover  { box-shadow: 2px 2px 6px var(--shadow-dark), -2px -2px 6px var(--shadow-light); color: var(--accent-hover); }
.help-btn:active { box-shadow: inset 3px 3px 7px var(--shadow-dark), inset -3px -3px 7px var(--shadow-light); }

.card-image-wrap {
    width: 100%;
    aspect-ratio: 16/9;
    border-radius: 14px;
    background: var(--bg);
    box-shadow: inset 5px 5px 12px var(--shadow-dark), inset -5px -5px 12px var(--shadow-light);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
}
.card-image-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.card-image-placeholder svg { opacity: 0.4; }
.card-image-placeholder span {
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--text-secondary);
    opacity: 0.5;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.neuro-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.neuro-field label {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.neuro-input {
    width: 100%;
    background: var(--bg);
    border: none;
    border-radius: 12px;
    padding: 10px 14px;
    color: var(--text-primary);
    font-size: 0.88rem;
    box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light);
    outline: none;
    transition: box-shadow 0.2s ease;
    box-sizing: border-box;
}
.neuro-input:focus {
    box-shadow: inset 5px 5px 12px var(--shadow-dark), inset -5px -5px 12px var(--shadow-light), 0 0 0 2px var(--accent-light);
}
.neuro-input[readonly] {
    color: var(--text-secondary);
    cursor: default;
    font-size: 0.78rem;
}

/* Campo invisible para igualar alturas en Google/Facebook/Twitter */
.neuro-field-phantom {
    display: flex;
    flex-direction: column;
    gap: 6px;
    visibility: hidden;
    pointer-events: none;
    aria-hidden: true;
}
.neuro-field-phantom label {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.neuro-save-btn {
    background: var(--bg);
    border: none;
    border-radius: 12px;
    padding: 11px 0;
    width: 100%;
    color: var(--accent);
    font-weight: 800;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    cursor: pointer;
    box-shadow: 5px 5px 12px var(--shadow-dark), -5px -5px 12px var(--shadow-light);
    transition: all 0.15s ease;
    margin-top: 14px;
    flex-shrink: 0;
}
.neuro-save-btn:hover  { box-shadow: 3px 3px 8px var(--shadow-dark), -3px -3px 8px var(--shadow-light); color: var(--accent-hover); }
.neuro-save-btn:active { box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light); }

/* ══ MODAL ══ */
.help-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(58,74,92,0.35);
    backdrop-filter: blur(4px);
    z-index: 99999;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.help-modal-overlay.open { display: flex; }

.help-modal {
    background: var(--bg);
    border-radius: 22px;
    padding: 30px 28px;
    max-width: 500px;
    width: 100%;
    box-shadow: 12px 12px 28px var(--shadow-dark), -12px -12px 28px var(--shadow-light);
    position: relative;
    animation: modalIn 0.25s ease;
    max-height: 90vh;
    overflow-y: auto;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.92) translateY(12px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-close-btn {
    position: absolute;
    top: 16px; right: 16px;
    width: 30px; height: 30px;
    border-radius: 50%;
    background: var(--bg);
    border: none;
    cursor: pointer;
    font-size: 0.85rem;
    color: var(--text-secondary);
    box-shadow: 3px 3px 8px var(--shadow-dark), -3px -3px 8px var(--shadow-light);
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s ease;
}
.modal-close-btn:hover { color: var(--accent); }
.modal-provider-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    background: var(--bg);
    box-shadow: 5px 5px 12px var(--shadow-dark), -5px -5px 12px var(--shadow-light);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 14px;
    font-size: 1.5rem;
}
.modal-title {
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 6px;
}
.modal-subtitle {
    font-size: 0.82rem;
    color: var(--text-secondary);
    margin-bottom: 20px;
    line-height: 1.55;
}
.modal-steps { display: flex; flex-direction: column; gap: 14px; }
.modal-step  { display: flex; gap: 12px; align-items: flex-start; }
.step-num {
    width: 26px; height: 26px;
    border-radius: 50%;
    background: var(--bg);
    box-shadow: 3px 3px 7px var(--shadow-dark), -3px -3px 7px var(--shadow-light);
    color: var(--accent);
    font-size: 0.72rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-top: 2px;
}
.step-text { font-size: 0.83rem; color: var(--text-primary); line-height: 1.55; }
.step-text a { color: var(--accent); font-weight: 700; text-decoration: none; }
.step-text a:hover { text-decoration: underline; }
.step-text code {
    background: var(--bg);
    border-radius: 6px;
    padding: 1px 6px;
    font-size: 0.78rem;
    box-shadow: inset 2px 2px 5px var(--shadow-dark), inset -2px -2px 5px var(--shadow-light);
    color: var(--accent);
    font-weight: 700;
}

@media (max-width: 1100px) { .social-grid { grid-template-columns: repeat(2,1fr); } }
@media (max-width: 620px)  { .social-grid { grid-template-columns: 1fr; } .social-page { padding: 16px; } }

/* ══ Botón flotante scroll-to-bottom (solo móvil) ══ */
.scroll-bottom-fab {
    display: none;
}
@media (max-width: 1100px) {
    .scroll-bottom-fab {
        display: flex;
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: var(--accent);
        border: none;
        cursor: pointer;
        z-index: 9000;
        align-items: center;
        justify-content: center;
        box-shadow: 6px 6px 14px var(--shadow-dark), -6px -6px 14px var(--shadow-light);
        transition: box-shadow 0.2s ease, transform 0.2s ease, opacity 0.3s ease;
        animation: fabIn 0.4s ease;
    }
    .scroll-bottom-fab:hover {
        box-shadow: 4px 4px 10px var(--shadow-dark), -4px -4px 10px var(--shadow-light);
        transform: translateY(-2px);
    }
    .scroll-bottom-fab:active {
        box-shadow: inset 4px 4px 10px var(--shadow-dark), inset -4px -4px 10px var(--shadow-light);
        transform: translateY(0);
    }
    .scroll-bottom-fab.hidden {
        opacity: 0;
        pointer-events: none;
        transform: translateY(16px);
    }
    /* Flecha animada */
    .scroll-bottom-fab svg {
        animation: bounceArrow 1.6s ease-in-out infinite;
    }
}
@keyframes fabIn {
    from { opacity: 0; transform: scale(0.7) translateY(20px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
@keyframes bounceArrow {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(4px); }
}
</style>

{{-- ══ MODALES EN ESPAÑOL ══ --}}

<div class="help-modal-overlay" id="modal-google">
    <div class="help-modal">
        <button class="modal-close-btn" onclick="closeModal('modal-google')">✕</button>
        <div class="modal-provider-icon">🔵</div>
        <div class="modal-title">¿Cómo obtener las credenciales de Google?</div>
        <div class="modal-subtitle">Sigue estos pasos para crear tu Client ID y Client Secret en Google Cloud Console.</div>
        <div class="modal-steps">
            <div class="modal-step"><span class="step-num">1</span><span class="step-text">Ingresa a <a href="https://console.cloud.google.com/" target="_blank">console.cloud.google.com</a> e inicia sesión con tu cuenta de Google.</span></div>
            <div class="modal-step"><span class="step-num">2</span><span class="step-text">Crea un nuevo proyecto o selecciona uno existente desde la barra de navegación superior.</span></div>
            <div class="modal-step"><span class="step-num">3</span><span class="step-text">En el menú izquierdo ve a <code>APIs y servicios → Credenciales</code>.</span></div>
            <div class="modal-step"><span class="step-num">4</span><span class="step-text">Haz clic en <code>+ Crear credenciales</code> y elige <code>ID de cliente OAuth</code>.</span></div>
            <div class="modal-step"><span class="step-num">5</span><span class="step-text">Selecciona tipo <code>Aplicación web</code>. En "URI de redireccionamiento autorizados" añade: <code>{{ url('/auth/google/callback') }}</code></span></div>
            <div class="modal-step"><span class="step-num">6</span><span class="step-text">Haz clic en <strong>Crear</strong>. Copia el <code>Client ID</code> y el <code>Client Secret</code> y pégalos en los campos correspondientes.</span></div>
        </div>
    </div>
</div>

<div class="help-modal-overlay" id="modal-facebook">
    <div class="help-modal">
        <button class="modal-close-btn" onclick="closeModal('modal-facebook')">✕</button>
        <div class="modal-provider-icon">🔷</div>
        <div class="modal-title">¿Cómo obtener las credenciales de Facebook?</div>
        <div class="modal-subtitle">Sigue estos pasos para crear tu App ID y App Secret en Meta for Developers.</div>
        <div class="modal-steps">
            <div class="modal-step"><span class="step-num">1</span><span class="step-text">Ingresa a <a href="https://developers.facebook.com/" target="_blank">developers.facebook.com</a> e inicia sesión.</span></div>
            <div class="modal-step"><span class="step-num">2</span><span class="step-text">Haz clic en <code>Mis Apps → Crear App</code>. Elige <code>Consumidor</code> como tipo de aplicación.</span></div>
            <div class="modal-step"><span class="step-num">3</span><span class="step-text">En el panel de tu app ve a <code>Configuración → Básica</code>. Allí encontrarás tu <code>App ID</code> y tu <code>App Secret</code>.</span></div>
            <div class="modal-step"><span class="step-num">4</span><span class="step-text">En el menú izquierdo añade el producto <code>Facebook Login</code> y configura la URL de redirección: <code>{{ url('/auth/facebook/callback') }}</code></span></div>
            <div class="modal-step"><span class="step-num">5</span><span class="step-text">Copia el <code>App ID</code> y el <code>App Secret</code> y pégalos en los campos correspondientes.</span></div>
        </div>
    </div>
</div>

<div class="help-modal-overlay" id="modal-twitter">
    <div class="help-modal">
        <button class="modal-close-btn" onclick="closeModal('modal-twitter')">✕</button>
        <div class="modal-provider-icon">🐦</div>
        <div class="modal-title">¿Cómo obtener las credenciales de Twitter / X?</div>
        <div class="modal-subtitle">Sigue estos pasos para obtener tu Client ID y Client Secret desde el Portal para Desarrolladores de X.</div>
        <div class="modal-steps">
            <div class="modal-step"><span class="step-num">1</span><span class="step-text">Ingresa a <a href="https://developer.twitter.com/en/portal/dashboard" target="_blank">developer.twitter.com</a> e inicia sesión.</span></div>
            <div class="modal-step"><span class="step-num">2</span><span class="step-text">Crea un nuevo Proyecto y una App, o utiliza una existente.</span></div>
            <div class="modal-step"><span class="step-num">3</span><span class="step-text">Dentro de tu App ve a <code>Keys and Tokens</code> y activa <code>OAuth 2.0</code>.</span></div>
            <div class="modal-step"><span class="step-num">4</span><span class="step-text">Añade la URL de callback: <code>{{ url('/auth/twitter/callback') }}</code></span></div>
            <div class="modal-step"><span class="step-num">5</span><span class="step-text">Copia tu <code>Client ID</code> y <code>Client Secret</code> y pégalos en los campos correspondientes.</span></div>
        </div>
    </div>
</div>

<div class="help-modal-overlay" id="modal-apple">
    <div class="help-modal">
        <button class="modal-close-btn" onclick="closeModal('modal-apple')">✕</button>
        <div class="modal-provider-icon">🍎</div>
        <div class="modal-title">¿Cómo obtener las credenciales de Apple?</div>
        <div class="modal-subtitle">Sigue estos pasos para configurar "Iniciar sesión con Apple" en tu cuenta de Apple Developer.</div>
        <div class="modal-steps">
            <div class="modal-step"><span class="step-num">1</span><span class="step-text">Ingresa a <a href="https://developer.apple.com/account/" target="_blank">developer.apple.com</a> e inicia sesión.</span></div>
            <div class="modal-step"><span class="step-num">2</span><span class="step-text">Ve a <code>Certificates, IDs & Profiles → Identifiers</code>. Crea un Services ID con <code>Sign In with Apple</code> habilitado.</span></div>
            <div class="modal-step"><span class="step-num">3</span><span class="step-text">El <code>Client ID</code> es el identificador de tu Services ID (ej. <code>com.tuapp.signin</code>).</span></div>
            <div class="modal-step"><span class="step-num">4</span><span class="step-text">Ve a <code>Keys</code> y crea una nueva clave con <code>Sign In with Apple</code>. Descarga el archivo <code>.p8</code> — ese es tu Client Secret.</span></div>
            <div class="modal-step"><span class="step-num">5</span><span class="step-text">La URL de Callback ya está configurada automáticamente: <code>{{ url('/apple-callback') }}</code></span></div>
            <div class="modal-step"><span class="step-num">6</span><span class="step-text">Pega el <code>Client ID</code> y el contenido del archivo <code>.p8</code> como <code>Client Secret</code> en los campos.</span></div>
        </div>
    </div>
</div>

{{-- ══ PÁGINA PRINCIPAL ══ --}}
<div class="social-page">
    <h1 class="social-title">{{ translate('Credenciales de inicio de sesión social') }}</h1>

    <div class="social-grid">

        {{-- GOOGLE --}}
        <div class="social-card">
            <div class="card-head">
                <div class="card-head-title">{{ translate('Inicio de sesión con Google') }}</div>
                <button class="help-btn" onclick="openModal('modal-google')" title="¿Cómo obtener las credenciales?">?</button>
            </div>
            <div class="card-image-wrap">
                <div class="card-image-placeholder">
                    <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span>Google Cloud Console</span>
                </div>
            </div>
            <form action="{{ route('env_key_update.update') }}" method="POST">
                @csrf
                <div class="fields-wrap">
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="GOOGLE_CLIENT_ID">
                        <label>{{ translate('Client ID') }}</label>
                        <input type="text" class="neuro-input" name="GOOGLE_CLIENT_ID" value="{{ env('GOOGLE_CLIENT_ID') }}" placeholder="{{ translate('Pega tu Google Client ID') }}" required>
                    </div>
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="GOOGLE_CLIENT_SECRET">
                        <label>{{ translate('Client Secret') }}</label>
                        <input type="text" class="neuro-input" name="GOOGLE_CLIENT_SECRET" value="{{ env('GOOGLE_CLIENT_SECRET') }}" placeholder="{{ translate('Pega tu Google Client Secret') }}" required>
                    </div>
                    {{-- Campo fantasma para igualar con Apple (3 campos) --}}
                    <div class="neuro-field-phantom">
                        <label>&nbsp;</label>
                        <input type="text" class="neuro-input" tabindex="-1">
                    </div>
                </div>
                <button type="submit" class="neuro-save-btn">{{ translate('Guardar') }}</button>
            </form>
        </div>

        {{-- FACEBOOK --}}
        <div class="social-card">
            <div class="card-head">
                <div class="card-head-title">{{ translate('Inicio de sesión con Facebook') }}</div>
                <button class="help-btn" onclick="openModal('modal-facebook')" title="¿Cómo obtener las credenciales?">?</button>
            </div>
            <div class="card-image-wrap">
                <div class="card-image-placeholder">
                    <svg width="38" height="38" viewBox="0 0 24 24" fill="#1877F2">
                        <path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.514c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                    </svg>
                    <span>Meta for Developers</span>
                </div>
            </div>
            <form action="{{ route('env_key_update.update') }}" method="POST">
                @csrf
                <div class="fields-wrap">
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="FACEBOOK_CLIENT_ID">
                        <label>{{ translate('App ID') }}</label>
                        <input type="text" class="neuro-input" name="FACEBOOK_CLIENT_ID" value="{{ env('FACEBOOK_CLIENT_ID') }}" placeholder="{{ translate('Pega tu Facebook App ID') }}" required>
                    </div>
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="FACEBOOK_CLIENT_SECRET">
                        <label>{{ translate('App Secret') }}</label>
                        <input type="text" class="neuro-input" name="FACEBOOK_CLIENT_SECRET" value="{{ env('FACEBOOK_CLIENT_SECRET') }}" placeholder="{{ translate('Pega tu Facebook App Secret') }}" required>
                    </div>
                    <div class="neuro-field-phantom">
                        <label>&nbsp;</label>
                        <input type="text" class="neuro-input" tabindex="-1">
                    </div>
                </div>
                <button type="submit" class="neuro-save-btn">{{ translate('Guardar') }}</button>
            </form>
        </div>

        {{-- TWITTER / X --}}
        <div class="social-card">
            <div class="card-head">
                <div class="card-head-title">{{ translate('Inicio de sesión con Twitter / X') }}</div>
                <button class="help-btn" onclick="openModal('modal-twitter')" title="¿Cómo obtener las credenciales?">?</button>
            </div>
            <div class="card-image-wrap">
                <div class="card-image-placeholder">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="#050407">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.91-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                    <span>X Developer Portal</span>
                </div>
            </div>
            <form action="{{ route('env_key_update.update') }}" method="POST">
                @csrf
                <div class="fields-wrap">
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="TWITTER_CLIENT_ID">
                        <label>{{ translate('Client ID') }}</label>
                        <input type="text" class="neuro-input" name="TWITTER_CLIENT_ID" value="{{ env('TWITTER_CLIENT_ID') }}" placeholder="{{ translate('Pega tu Twitter Client ID') }}" required>
                    </div>
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="TWITTER_CLIENT_SECRET">
                        <label>{{ translate('Client Secret') }}</label>
                        <input type="text" class="neuro-input" name="TWITTER_CLIENT_SECRET" value="{{ env('TWITTER_CLIENT_SECRET') }}" placeholder="{{ translate('Pega tu Twitter Client Secret') }}" required>
                    </div>
                    <div class="neuro-field-phantom">
                        <label>&nbsp;</label>
                        <input type="text" class="neuro-input" tabindex="-1">
                    </div>
                </div>
                <button type="submit" class="neuro-save-btn">{{ translate('Guardar') }}</button>
            </form>
        </div>

        {{-- APPLE --}}
        <div class="social-card">
            <div class="card-head">
                <div class="card-head-title">{{ translate('Inicio de sesión con Apple') }}</div>
                <button class="help-btn" onclick="openModal('modal-apple')" title="¿Cómo obtener las credenciales?">?</button>
            </div>
            <div class="card-image-wrap">
                <div class="card-image-placeholder">
                    <svg width="34" height="38" viewBox="0 0 814 1000" fill="#3a4a5c">
                        <path d="M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76 0-103.7 40.8-165.9 40.8s-105-37.5-155.5-127.4C46 790.8 0 663.4 0 541.8c0-207.4 135.4-316.8 268.3-316.8 71 0 130.7 46.6 174.9 46.6 42.3 0 109.4-49.4 188.3-49.4 30.3 0 108.3 2.6 164.9 100.1zm-234.5-181.6c31.1-36.9 53.1-88.1 53.1-139.3 0-7.1-.6-14.3-1.9-20.1-50.6 1.9-110.8 33.7-147.1 75.8-28.5 32.4-55.1 83.6-55.1 135.5 0 7.8 1.3 15.6 1.9 18.1 3.2.6 8.4 1.3 13.6 1.3 45.4 0 102.5-30.4 135.5-71.3z"/>
                    </svg>
                    <span>Apple Developer</span>
                </div>
            </div>
            <form action="{{ route('env_key_update.update') }}" method="POST">
                @csrf
                <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_LOGIN">
                <input type="hidden" name="SIGN_IN_WITH_APPLE_LOGIN" value="{{ url('/users/login') }}">
                <div class="fields-wrap">
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_REDIRECT">
                        <label>{{ translate('URL de Callback') }}</label>
                        <input type="text" class="neuro-input" name="SIGN_IN_WITH_APPLE_REDIRECT" value="{{ url('/apple-callback') }}" readonly>
                    </div>
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_CLIENT_ID">
                        <label>{{ translate('Client ID') }}</label>
                        <input type="text" class="neuro-input" name="SIGN_IN_WITH_APPLE_CLIENT_ID" value="{{ env('SIGN_IN_WITH_APPLE_CLIENT_ID') }}" placeholder="{{ translate('Pega tu Apple Client ID') }}" required>
                    </div>
                    <div class="neuro-field">
                        <input type="hidden" name="types[]" value="SIGN_IN_WITH_APPLE_CLIENT_SECRET">
                        <label>{{ translate('Client Secret') }}</label>
                        <input type="text" class="neuro-input" name="SIGN_IN_WITH_APPLE_CLIENT_SECRET" value="{{ env('SIGN_IN_WITH_APPLE_CLIENT_SECRET') }}" placeholder="{{ translate('Pega tu Apple Client Secret') }}" required>
                    </div>
                </div>
                <button type="submit" class="neuro-save-btn">{{ translate('Guardar') }}</button>
            </form>
        </div>

    </div>
</div>

{{-- ══ Botón flotante ( móvil) ══ --}}
<!-- boton flotante-->
<button class="scroll-bottom-fab" id="scrollFab" title="Ir al final" aria-label="Bajar al final">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f5f5f5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 12 15 18 9"/>
    </svg>
</button>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}
document.querySelectorAll('.help-modal-overlay').forEach(function(o) {
    o.addEventListener('click', function(e) {
        if (e.target === o) { o.classList.remove('open'); document.body.style.overflow = ''; }
    });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.help-modal-overlay.open').forEach(function(o) {
            o.classList.remove('open'); document.body.style.overflow = '';
        });
    }
});

// ── Botón flotante scroll-to-bottom ──
var fab = document.getElementById('scrollFab');
if (fab) {
    // Al hacer clic baja suavemente hasta el final
    fab.addEventListener('click', function () {
        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
    });

    // Se oculta automáticamente cuando ya estás al final
    window.addEventListener('scroll', function () {
        var distanceToBottom = document.body.scrollHeight - window.scrollY - window.innerHeight;
        if (distanceToBottom < 60) {
            fab.classList.add('hidden');
        } else {
            fab.classList.remove('hidden');
        }
    });
}
</script>
@endsection
