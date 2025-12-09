    const logoInput = document.getElementById('logo');
    const logoLabel = document.getElementById('logoLabel');
    const logoPreview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewImg');

    // Handle file selection
    logoInput.addEventListener('change', handleLogoChange);

    function handleLogoChange() {
        const file = logoInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                logoPreview.style.display = 'block';
                logoLabel.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    }

    function changeLogoImage() {
        logoInput.click();
    }

    function removeLogoImage() {
        logoInput.value = '';
        logoPreview.style.display = 'none';
        logoLabel.style.display = 'block';
    }

    // Drag and drop
    logoLabel.addEventListener('dragover', (e) => {
        e.preventDefault();
        logoLabel.classList.add('dragover');
    });

    logoLabel.addEventListener('dragleave', () => {
        logoLabel.classList.remove('dragover');
    });

    logoLabel.addEventListener('drop', (e) => {
        e.preventDefault();
        logoLabel.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            logoInput.files = files;
            handleLogoChange();
        }
    });



