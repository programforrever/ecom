<!-- Solución para asegurar que el thumbnail se guarda correctamente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cuando se hace clic en cualquier uploader AIZ, mostrar mensaje de debug
    const uploaders = document.querySelectorAll('[data-toggle="aizuploader"]');
    uploaders.forEach(uploader => {
        const label = uploader.parentElement.parentElement.querySelector('label');
        const labelText = label ? label.textContent : '';
        
        uploader.addEventListener('click', function() {
            console.log('✓ Uploader click detectado para:', labelText);
            const hiddenInput = uploader.querySelector('.selected-files');
            console.log('✓ Campo hidden encontrado:', hiddenInput);
            console.log('✓ Valor actual:', hiddenInput.value);
        });
    });

    // Monitorear cambios en campos hidden
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                console.log('✓ Campo hidden actualizado:', mutation.target.name,'=', mutation.target.value);
            }
        });
    });

    const selectedFiles = document.querySelectorAll('.selected-files');
    selectedFiles.forEach(input => {
        observer.observe(input, {
            attributes: true,
            attributeFilter: ['value']
        });
    });
});

// Log cuando se envía el formulario
const form = document.querySelector('form[id*="choice_form"]');
if (form) {
    const originalSubmit = form.submit;
    form.addEventListener('submit', function(e) {
        const thumbnailInput = form.querySelector('input[name="thumbnail_img"]');
        const thumbnailHoverInput = form.querySelector('input[name="thumbnail_hover_img"]');
        
        console.log('=== FORM SUBMIT ===');
        console.log('thumbnail_img valor:', thumbnailInput ? thumbnailInput.value : 'NO ENCONTRADO');
        console.log('thumbnail_hover_img valor:', thumbnailHoverInput ? thumbnailHoverInput.value : 'NO ENCONTRADO');
        
        if (thumbnailInput && !thumbnailInput.value) {
            console.warn('⚠️ ADVERTENCIA: thumbnail_img está vacío!');
        }
    });
}
</script>
