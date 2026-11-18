// Small, accessible enhancement for the administrator login form.
// Authentication and form submission remain handled by Laravel.
document.addEventListener('click', (event) => {
    const button = event.target.closest('[data-password-toggle]');
    if (!button) return;

    const field = document.getElementById(button.dataset.passwordToggle);
    if (!field || field.tagName !== 'INPUT' || !['password', 'text'].includes(field.type)) return;

    const reveal = field.type === 'password';
    field.type = reveal ? 'text' : 'password';
    button.textContent = reveal ? 'Hide' : 'Show';
    button.setAttribute('aria-pressed', String(reveal));
    button.setAttribute('aria-label', reveal ? 'Hide password' : 'Show password');
});
