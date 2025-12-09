
    const logoInput = document.getElementById('logo');
    const logoLabel = document.getElementById('logoLabel');
    const logoPreview = document.getElementById('logoPreview');
    const previewImg = document.getElementById('previewImg');

    // Handle file selection
    logoInput.addEventListener('change', handleLogoChange);

    function handleLogoChange() {
        const file = logoInput.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                logoInput.value = '';
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Tipe file tidak valid! Hanya JPG, JPEG, dan PNG yang diperbolehkan.');
                logoInput.value = '';
                return;
            }

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
