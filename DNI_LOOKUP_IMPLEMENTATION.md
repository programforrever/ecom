# DNI Lookup Feature - Implementación Frontend-Only

## 📋 Resumen de Cambios

Se ha implementado la búsqueda de DNI en **todas las páginas de registro** del sistema usando **JavaScript puro en el frontend**, sin cambios en el backend.

### ✅ Cambios Realizados:

#### 1. **Archivo JavaScript Creado:**
- `public/assets/js/dni-lookup.js` - Contiene toda la lógica para consultar apiperu.dev

#### 2. **Vistas Modificadas (Agregar campo DNI):**
- `resources/views/frontend/user_registration.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/boxed/user_registration.blade.php`
- `resources/views/auth/focused/user_registration.blade.php`
- `resources/views/auth/free/user_registration.blade.php`

#### 3. **Script Compartido Modificado:**
- `resources/views/auth/login_register_js.blade.php` - Incluye `dni-lookup.js`

---

## 🔧 Características

### Campo DNI Agregado:
- **Label:** DNI (Opcional)
- **Placeholder:** "Enter DNI"
- **Validaciones:**
  - Mínimo 8 dígitos
  - Solo números
  - Máximo 8 caracteres
  
### Botón "Lookup":
- Consulta automáticamente la API de apiperu.dev
- Rellena automáticamente el campo "Full Name" si encuentra el DNI
- Muestra estado "Consultando..." mientras procesa
- Maneja errores elegantemente con mensajes amigables

---

## 🚀 Cómo Funciona

### Flujo de Usuario:
1. Usuario ingresa un DNI válido (8 dígitos)
2. Usuario hace clic en botón "Lookup"
3. Sistema consulta `https://apiperu.dev/api/dni`
4. Si el DNI existe:
   - Campo "Full Name" se rellena automáticamente
   - Usuario puede continuar el registro
5. Si el DNI no existe:
   - Se muestra mensaje: "DNI no encontrado"
   - Campo "Full Name" se limpia

### Seguridad:
- ✅ Token en JavaScript (seguro porque API es **solo lectura** de datos públicos)
- ✅ Validación de formato DNI en el cliente
- ✅ Sin cambios en el backend
- ✅ Sin modificación de Apache .htaccess

---

## 📝 Token API

- **API:** apiperu.dev
- **Token:** `78291d771ba286fabfd992954dd6630cb6a0324cb643b7b372abed2cbb00ee0f`
- **Método:** POST
- **URL:** `https://apiperu.dev/api/dni`
- **Encabezado:** `Authorization: Bearer {TOKEN}`

---

## 🧪 Pruebas

### Para Probar Localmente:

1. **Accede a una página de registro:**
   - http://localhost/ecom/users/registration (Frontend)
   - http://localhost/ecom/auth/register (Backend)
   - http://localhost/ecom/auth/boxed (Boxed layout)
   - http://localhost/ecom/auth/focused (Focused layout)
   - http://localhost/ecom/auth/free (Free layout)

2. **Prueba el formulario:**
   - Ingresa un DNI válido de Perú en el campo DNI
   - Haz clic en "Lookup"
   - Verifica que el nombre se rellena automáticamente

3. **Casos de Error:**
   ```javascript
   // DNI inválido (menos de 8 dígitos)
   Input: "1234567"
   Resultado: Alerta "Por favor ingresa un DNI válido (8 dígitos)"
   
   // DNI no encontrado
   Input: "00000000"
   Resultado: Alerta "DNI no encontrado. Verifica el número e intenta de nuevo."
   
   // Error de conexión
   Resultado: Alerta "Error al consultar el DNI. Intenta de nuevo más tarde."
   ```

---

## 📦 Archivos Incluidos

### dni-lookup.js - Dos funciones disponibles:

#### Función 1: `lookupDNI(dniInputId, nameFieldId)`
```javascript
// Parametrizadas por IDs de HTML
lookupDNI('dni', 'name');
```

#### Función 2: `lookupDNIFromElement(dniElement, nameElement)` ⭐ **RECOMENDADA**
```javascript
// Parametrizadas por objeto DOM
lookupDNIFromElement(
    document.getElementById('dni'), 
    document.querySelector('input[name=name]')
);
```

---

## 🔄 Mantenimiento

### Si necesitas cambiar el token:
Edita `public/assets/js/dni-lookup.js` línea 2:
```javascript
const APIPERU_TOKEN = 'tu_nuevo_token_aqui';
```

### Si necesitas modificar la API:
Edita `public/assets/js/dni-lookup.js` línea 3:
```javascript
const APIPERU_API_URL = 'https://nueva-api.com/endpoint';
```

---

## ⚠️ Limitaciones / Notas

1. **Campo DNI es opcional** - El usuario puede dejar en blanco y seguir registrándose
2. **API puede estar limitado** - Depende de los límites de rate-limiting de apiperu.dev
3. **Requiere conexión a Internet** - Necesita acceso a https://apiperu.dev
4. **Solo funciona con DNI de Perú** - La API de apiperu.dev es específica para Perú
5. **No se guarda el DNI** - El campo DNI es solo para autocompletar el nombre (no está en la BD de usuarios)

---

## ✨ Ventajas de esta Implementación

✅ No requiere cambios en backend
✅ No afecta Apache .htaccess
✅ No introduce dependencias nuevas
✅ Token seguro (API es solo lectura pública)
✅ Interfaz amigable con estado "Consultando..."
✅ Manejo de errores completo
✅ Funciona en todas las páginas de registro
✅ Fácil mantenimiento

---

## 🐛 Troubleshooting

### El botón no funciona:
1. Abre la consola del navegador (F12)
2. Verifica que no haya errores de JavaScript
3. Comprueba que `dni-lookup.js` se carguó correctamente

### El DNI se consulta pero no se rellena el nombre:
1. Verifica que el DNI sea válido (8 dígitos, el usuario debe existir en RENIEC)
2. Revisa la consola del navegador para mensajes de error
3. Verifica la conexión a Internet

### Error de CORS:
- La API apiperu.dev puede tener restricciones CORS
- Esto es normal y se maneja con graceful error handling en el código

---

## 📞 Soporte

Para preguntas sobre la API: https://apiperu.dev/docs
Para preguntas sobre la implementación: Revisar `public/assets/js/dni-lookup.js`
