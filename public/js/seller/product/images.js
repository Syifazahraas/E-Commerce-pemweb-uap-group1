    const imagesInput = document.getElementById('images');
    const imagesLabel = document.getElementById('imagesLabel');
    const imagesPreview = document.getElementById('imagesPreview');
    const uploadBtn = document.getElementById('uploadBtn');
    const currentImageCount = Number("{{ $product->productImages->count() }}");
    const maxImages = 10;

    imagesInput.addEventListener('change', handleImagesChange);

    function handleImagesChange() {
        const files = imagesInput.files;
        imagesPreview.innerHTML = '';

        if (files.length > 0) {
            // Check if total would exceed limit
            if (currentImageCount + files.length > maxImages) {
                alert(`Total gambar tidak boleh lebih dari ${maxImages}. Saat ini: ${currentImageCount}, Upload: ${files.length}`);
                resetForm();
                return;
            }

            imagesLabel.style.display = 'none';
            uploadBtn.disabled = false;

            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-item';

                    if (currentImageCount === 0 && index === 0) {
                        div.innerHTML = `
                            <span class="image-preview-badge">
                                <i class="fas fa-star"></i> Thumbnail
                            </span>
                            <img src="${event.target.result}" alt="Preview ${index + 1}">
                            <div class="image-preview-actions">
                                <button type="button" onclick="removeImage(${index})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        `;
                    } else {
                        div.innerHTML = `
                            <img src="${event.target.result}" alt="Preview ${index + 1}">
                            <div class="image-preview-actions">
                                <button type="button" onclick="removeImage(${index})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        `;
                    }

                    imagesPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        } else {
            resetForm();
        }
    }

    function removeImage(index) {
        const dataTransfer = new DataTransfer();
        const files = imagesInput.files;

        Array.from(files).forEach((file, i) => {
            if (i !== index) {
                dataTransfer.items.add(file);
            }
        });

        imagesInput.files = dataTransfer.files;
        handleImagesChange();
    }

    function resetForm() {
        imagesInput.value = '';
        imagesPreview.innerHTML = '';
        imagesLabel.style.display = 'block';
        uploadBtn.disabled = true;
    }

    // Drag and drop
    imagesLabel.addEventListener('dragover', (e) => {
        e.preventDefault();
        imagesLabel.classList.add('dragover');
    });

    imagesLabel.addEventListener('dragleave', () => {
        imagesLabel.classList.remove('dragover');
    });

    imagesLabel.addEventListener('drop', (e) => {
        e.preventDefault();
        imagesLabel.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            imagesInput.files = files;
            handleImagesChange();
        }
    });
