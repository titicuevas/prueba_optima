document.addEventListener('DOMContentLoaded', function () {
    const anioSelect = document.querySelector('select[name="anio"]');
    if (anioSelect) {
        anioSelect.addEventListener('change', function() {
            this.form.submit();
        });
    }
}); 