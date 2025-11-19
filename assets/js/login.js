// assets/js/login.js

class LoginManager {
    constructor() {
        this.roleOptions = document.querySelectorAll('.role-option');
        this.selectedRole = '';
        this.init();
    }

    init() {
        this.setupRoleSelection();
        this.setupPasswordToggle();
        this.setupRememberMe();
        this.setupFormSubmission();
        this.setupAutoRoleDetection();
        this.autoFocusUsername();
    }

    setupRoleSelection() {
        this.roleOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.selectRole(option);
            });
            
            // Hacer los roles seleccionables con teclado (accesibilidad)
            option.setAttribute('tabindex', '0');
            option.setAttribute('role', 'button');
            option.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.selectRole(option);
                }
            });
        });
    }

    selectRole(option) {
    // Remover selección anterior
    this.roleOptions.forEach(opt => {
        opt.classList.remove('selected');
        opt.setAttribute('aria-selected', 'false');
    });
    
    // Agregar selección actual
    option.classList.add('selected');
    option.setAttribute('aria-selected', 'true');
    this.selectedRole = option.getAttribute('data-role');
    
    // ACTUALIZAR INPUT HIDDEN - ESTO ES CRÍTICO
    const rolInput = document.getElementById('rolInput');
    if (rolInput) {
        rolInput.value = this.selectedRole;
    }
    
    console.log('Rol seleccionado:', this.selectedRole);
}

    setupPasswordToggle() {
        const toggleBtn = document.getElementById('togglePassword');
        if (!toggleBtn) return;

        toggleBtn.addEventListener('click', () => {
            this.togglePasswordVisibility();
        });
        
        // También hacerlo accesible con teclado
        toggleBtn.setAttribute('tabindex', '0');
        toggleBtn.setAttribute('role', 'button');
        toggleBtn.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.togglePasswordVisibility();
            }
        });
    }

    togglePasswordVisibility() {
        const passwordInput = document.getElementById('passwordInput');
        const icon = document.querySelector('#togglePassword i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
            toggleBtn.setAttribute('aria-label', 'Ocultar contraseña');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
            toggleBtn.setAttribute('aria-label', 'Mostrar contraseña');
        }
    }

    setupRememberMe() {
        const visualCheckbox = document.getElementById('rememberCheckboxVisual');
        const realCheckbox = document.getElementById('rememberCheckboxInput');
        
        if (!visualCheckbox || !realCheckbox) return;

        // Click en el checkbox visual
        visualCheckbox.addEventListener('click', () => {
            realCheckbox.checked = !realCheckbox.checked;
            visualCheckbox.classList.toggle('checked', realCheckbox.checked);
        });

        // También manejar el checkbox real para accesibilidad
        realCheckbox.addEventListener('change', () => {
            visualCheckbox.classList.toggle('checked', realCheckbox.checked);
        });
        
        // Hacerlo accesible con teclado
        visualCheckbox.setAttribute('tabindex', '0');
        visualCheckbox.setAttribute('role', 'checkbox');
        visualCheckbox.setAttribute('aria-labelledby', 'rememberLabel');
        visualCheckbox.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                realCheckbox.checked = !realCheckbox.checked;
                visualCheckbox.classList.toggle('checked', realCheckbox.checked);
            }
        });
    }

    setupAutoRoleDetection() {
        const usernameInput = document.getElementById('usuarioInput');
        if (!usernameInput) return;

        usernameInput.addEventListener('input', () => {
            const username = usernameInput.value.toLowerCase();
            this.detectRoleFromUsername(username);
        });
    }

    detectRoleFromUsername(username) {
        this.roleOptions.forEach(opt => {
            opt.classList.remove('selected');
            opt.setAttribute('aria-selected', 'false');
        });
        
        let targetRole = null;
        
        if (username.includes('admin')) {
            targetRole = document.querySelector('[data-role="administrador"]');
        } else if (username.includes('doc') || username.includes('prof')) {
            targetRole = document.querySelector('[data-role="docente"]');
        } else if (username.includes('est') || username.includes('alum')) {
            targetRole = document.querySelector('[data-role="estudiante"]');
        }
        
        if (targetRole) {
            this.selectRole(targetRole);
        }
    }

   setupFormSubmission() {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', (e) => {
        if (!this.validateForm()) {
            e.preventDefault();
            return;
        }
        this.handleFormSubmit(e);
    });
}

validateForm() {
    const username = document.getElementById('usuarioInput').value.trim();
    const password = document.getElementById('passwordInput').value;
    const rolInput = document.getElementById('rolInput');
    
    // Validaciones básicas
    if (!username) {
        this.showError('Por favor ingrese su usuario');
        document.getElementById('usuarioInput').focus();
        return false;
    }
    
    if (!password) {
        this.showError('Por favor ingrese su contraseña');
        document.getElementById('passwordInput').focus();
        return false;
    }
    
    if (!rolInput.value) {
        this.showError('Debe seleccionar un tipo de usuario');
        document.getElementById('roleError').style.display = 'block';
        return false;
    }
    
    this.hideError();
    document.getElementById('roleError').style.display = 'none';
    return true;
}

    handleFormSubmit(e) {
        const btn = document.querySelector('.btn-signin');
        const originalHTML = btn.innerHTML;
        
        // Mostrar estado de carga
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> VERIFICANDO...';
        btn.disabled = true;

        // Prevenir múltiples envíos
    e.preventDefault();
        
        // Simular tiempo de procesamiento
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            e.target.submit();
        }, 500);
    }

    setupPageUnload() {
    window.addEventListener('beforeunload', () => {
        // Limpiar token si el usuario cierra la página sin hacer login
        if (!document.querySelector('form').submitted) {
            // Podrías hacer una llamada AJAX para limpiar el token
            // o confiar en que el token expirará en 30 minutos
        }
    });
}

    autoFocusUsername() {
        const usernameInput = document.getElementById('usuarioInput');
        if (usernameInput) {
            usernameInput.focus();
        }
    }

    showError(message) {
        let errorDiv = document.querySelector('.error-message');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.setAttribute('role', 'alert');
            errorDiv.setAttribute('aria-live', 'assertive');
            const form = document.querySelector('form');
            form.insertBefore(errorDiv, form.firstChild);
        }
        
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        errorDiv.style.display = 'block';
    }

    hideError() {
        const errorDiv = document.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new LoginManager();
});