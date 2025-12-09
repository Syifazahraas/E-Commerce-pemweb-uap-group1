    // Format number input with thousand separator
    const amountInput = document.getElementById('withdrawalAmount');

    amountInput.addEventListener('blur', function() {
        const value = this.value.replace(/[^0-9]/g, '');
        if (value) {
            this.value = value;
        }
    });

    // Prevent negative values
    amountInput.addEventListener('input', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });

    // Form validation
    document.getElementById('withdrawalForm').addEventListener('submit', function(e) {
        const amount = parseInt(amountInput.value);
        const maxBalance = parseInt(document.getElementById('withdrawalForm').dataset.maxBalance, 10);

        if (amount < 10000) {
            e.preventDefault();
            alert('Jumlah penarikan minimal Rp 10.000');
            return false;
        }

        if (amount > maxBalance) {
            e.preventDefault();
            alert('Jumlah penarikan melebihi saldo tersedia');
            return false;
        }

        return confirm('Apakah Anda yakin ingin mengajukan penarikan sebesar Rp ' + amount.toLocaleString('id-ID') + '?');
    });
