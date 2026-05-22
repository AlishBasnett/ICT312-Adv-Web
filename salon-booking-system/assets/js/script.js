document.addEventListener('DOMContentLoaded', function () {
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('#siteNav');

    if (navToggle && nav) {
        navToggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    document.querySelectorAll('.js-confirm').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const message = form.dataset.confirm || 'Are you sure?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('.js-validate').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(function (field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('field-error');
                } else {
                    field.classList.remove('field-error');
                }
            });

            if (!isValid) {
                event.preventDefault();
                window.alert('Please complete all required fields.');
            }
        });
    });
});
