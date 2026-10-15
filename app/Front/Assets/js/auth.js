document.addEventListener('DOMContentLoaded', () => {
	if (window.lucide) {
		lucide.createIcons();
	}

	document.querySelectorAll('[data-password-toggle]').forEach((button) => {
		const passwordInput = document.getElementById(button.dataset.passwordToggle);
		if (!passwordInput) return;

		button.addEventListener('click', () => {
			const isVisible = passwordInput.type === 'text';
			passwordInput.type = isVisible ? 'password' : 'text';
			button.setAttribute('aria-label', isVisible ? 'Mostrar senha' : 'Ocultar senha');
			button.setAttribute('aria-pressed', String(!isVisible));

			const passwordIcon = document.createElement('i');
			passwordIcon.setAttribute('data-lucide', isVisible ? 'eye' : 'eye-off');
			passwordIcon.className = 'h-4 w-4';
			passwordIcon.setAttribute('aria-hidden', 'true');
			button.replaceChildren(passwordIcon);

			if (window.lucide) {
				lucide.createIcons();
			}
		});
	});
});
