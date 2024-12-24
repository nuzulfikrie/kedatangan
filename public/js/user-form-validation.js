function validateUserForm(formId) {
    const form = document.getElementById(formId);
    const nameInput = form.querySelector('#name');
    const emailInput = form.querySelector('#email');
    const passwordInput = form.querySelector('#password');
    const passwordConfirmInput = form.querySelector('#password_confirmation');
    const roleSelect = form.querySelector('#role');

    // Clear previous error messages
    function clearErrors() {
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
            el.classList.add('border-gray-300');
        });
    }

    // Add error message
    function showError(element, message) {
        element.classList.remove('border-gray-300');
        element.classList.add('border-red-500');
        const errorDiv = document.createElement('p');
        errorDiv.className = 'mt-1 text-sm text-red-600 error-message';
        errorDiv.textContent = message;
        element.parentNode.appendChild(errorDiv);
    }

    // Validate email format
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        clearErrors();

        // Name validation
        if (!nameInput.value.trim()) {
            showError(nameInput, 'Name is required');
            hasErrors = true;
        } else if (nameInput.value.length > 255) {
            showError(nameInput, 'Name cannot exceed 255 characters');
            hasErrors = true;
        }

        // Email validation
        if (!emailInput.value.trim()) {
            showError(emailInput, 'Email is required');
            hasErrors = true;
        } else if (!isValidEmail(emailInput.value)) {
            showError(emailInput, 'Please enter a valid email address');
            hasErrors = true;
        }

        // Password validation (for create form)
        if (formId === 'createUserForm' || passwordInput.value) {
            if (formId === 'createUserForm' && !passwordInput.value) {
                showError(passwordInput, 'Password is required');
                hasErrors = true;
            } else if (passwordInput.value && passwordInput.value.length < 8) {
                showError(passwordInput, 'Password must be at least 8 characters');
                hasErrors = true;
            } else if (passwordInput.value !== passwordConfirmInput.value) {
                showError(passwordConfirmInput, 'Passwords do not match');
                hasErrors = true;
            }
        }

        // Role validation
        if (!roleSelect.value) {
            showError(roleSelect, 'Please select a role');
            hasErrors = true;
        }

        if (hasErrors) {
            e.preventDefault();
            // Scroll to first error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Real-time validation
    const inputs = [nameInput, emailInput, passwordInput, passwordConfirmInput];
    inputs.forEach(input => {
        if (!input) return;
        
        input.addEventListener('blur', function() {
            clearErrors();
            
            if (this.id === 'name') {
                if (!this.value.trim()) {
                    showError(this, 'Name is required');
                } else if (this.value.length > 255) {
                    showError(this, 'Name cannot exceed 255 characters');
                }
            }
            
            if (this.id === 'email') {
                if (!this.value.trim()) {
                    showError(this, 'Email is required');
                } else if (!isValidEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                }
            }
            
            if (this.id === 'password' && this.value) {
                if (this.value.length < 8) {
                    showError(this, 'Password must be at least 8 characters');
                }
            }
            
            if (this.id === 'password_confirmation' && this.value) {
                if (this.value !== passwordInput.value) {
                    showError(this, 'Passwords do not match');
                }
            }
        });
    });
} 