    document.getElementById('name').addEventListener('keyup', function () {
        let slug = this.value.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
        document.getElementById('slug').value = slug;
    });
