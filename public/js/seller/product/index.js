    function confirmDelete(productId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        const form = document.getElementById('deleteForm');
        form.action = `/seller/products/${productId}`;
        modal.show();
    }
