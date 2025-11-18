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
        this.setupRealTimeValidation();
    }

    setupRoleSelection() {
        this.roleOptions.forEach(option => {
            option.addEventListener('click', () => {
                this.selectRole(option);
            });
        });
    }

    selectRole(option) {
        this.roleOptions.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');
        this.selectedRole = option.getAttribute('data-role');
        
        // Actualizar interfaz según el rol seleccionado
        this.actualizarInterfazPorRol();
    }

    actualizarInterfazPorRol() {
        const welcomeSection = document.querySelector('.welcome-section');
        const features = document.querySelectorAll('.feature-item');
        
        // Resetear todos los features
        features.forEach(feature => feature.style.display = 'flex');
        
        switch(this.selectedRole) {
            case 'administrador':
                this.mostrarMensajeRol('Acceso completo al sistema');
                break;
            case 'docente':
                this.mostrarMensajeRol('Panel de gestión docente');
                features[2].style.display = 'none'; // Ocultar feature no relevante
                break;
            case 'estudiante':
                this.mostrarMensajeRol('Seguimiento de prácticas');
                features[1].style.display = 'none'; // Ocultar feature no relevante
                break;
        }
    }

    mostrarMensajeRol(mensaje) {
        let mensajeRol = document.querySelector('.rol-message');
        if (!mensajeRol) {
            mensajeRol = document.createElement('div');
            mensajeRol.className = 'rol-message';
            const sectionHeader = document.querySelector('.section-header');
            sectionHeader.appendChild(mensajeRol);
        }
        mensajeRol.innerHTML = `<small style="color: #0dcaf0; font-style: italic;">${mensaje}</small>`;
    }

    setupRealTimeValidation() {
        const usuarioInput = document.getElementById('usuario');
        const passwordInput = document.getElementById('password');
        
        usuarioInput.addEventListener('blur', () => this.validarUsuario(usuarioInput.value));
        passwordInput.addEventListener('blur', () => this.validarPassword(passwordInput.value));
    }

    validarUsuario(usuario) {
        if (usuario.length < 3) {
            this.mostrarErrorCampo('usuario', 'El usuario debe tener al menos 3 caracteres');
            return false;
        }
        this.limpiarErrorCampo('usuario');
        return true;
    }

    validarPassword(password) {
        if (password.length < 4) {
            this.mostrarErrorCampo('password', 'La contraseña debe tener al menos 4 caracteres');
            return false;
        }
        this.limpiarErrorCampo('password');
        return true;
    }

    mostrarErrorCampo(campo, mensaje) {
        const input = document.getElementById(campo);
        const wrapper = input.parentElement;
        
        // Remover error anterior
        this.limpiarErrorCampo(campo);
        
        // Agregar estilo de error
        input.style.borderColor = '#dc2626';
        input.style.background = 'rgba(220, 38, 38, 0.1)';
        
        // Agregar mensaje de error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-campo';
        errorDiv.style.color = '#fecaca';
        errorDiv.style.fontSize = '12px';
        errorDiv.style.marginTop = '5px';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${mensaje}`;
        
        wrapper.appendChild(errorDiv);
    }

    limpiarErrorCampo(campo) {
        const input = document.getElementById(campo);
        const wrapper = input.parentElement;
        const errorDiv = wrapper.querySelector('.error-campo');
        
        if (errorDiv) {
            errorDiv.remove();
        }
        
        input.style.borderColor = '';
        input.style.background = '';
    }

    setupPasswordToggle() {
        const toggleBtn = document.getElementById('togglePassword');
        if (!toggleBtn) return;

        toggleBtn.addEventListener('click', () => {
            const passwordInput = document.getElementById('password');
            const icon = toggleBtn.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }

    setupRememberMe() {
        const checkbox = document.getElementById('rememberCheckbox');
        if (!checkbox) return;

        checkbox.addEventListener('click', () => {
            checkbox.classList.toggle('checked');
            // Guardar preferencia en localStorage
            localStorage.setItem('rememberLogin', checkbox.classList.contains('checked'));
        });

        // Cargar preferencia guardada
        if (localStorage.getItem('rememberLogin') === 'true') {
            checkbox.classList.add('checked');
        }
    }

    setupAutoRoleDetection() {
        const usernameInput = document.getElementById('usuario');
        if (!usernameInput) return;

        usernameInput.addEventListener('input', () => {
            const username = usernameInput.value.toLowerCase();
            this.detectRoleFromUsername(username);
        });

        // Cargar usuario recordado si existe
        const savedUser = localStorage.getItem('savedUsername');
        if (savedUser) {
            usernameInput.value = savedUser;
            this.detectRoleFromUsername(savedUser.toLowerCase());
        }
    }

    detectRoleFromUsername(username) {
        this.roleOptions.forEach(opt => opt.classList.remove('selected'));
        
        if (username.includes('admin')) {
            this.selectRole(document.querySelector('[data-role="administrador"]'));
        } else if (username.includes('doc') || username.includes('prof')) {
            this.selectRole(document.querySelector('[data-role="docente"]'));
        } else if (username.includes('est') || username.includes('alum')) {
            this.selectRole(document.querySelector('[data-role="estudiante"]'));
        }
    }

    setupFormSubmission() {
        const form = document.querySelector('form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            if (!this.validarFormulario()) {
                e.preventDefault();
                return;
            }
            this.handleFormSubmit(e);
        });
    }

    validarFormulario() {
        const usuario = document.getElementById('usuario').value;
        const password = document.getElementById('password').value;
        
        const usuarioValido = this.validarUsuario(usuario);
        const passwordValido = this.validarPassword(password);
        
        if (!this.selectedRole) {
            this.mostrarErrorGlobal('Por favor, selecciona tu rol');
            return false;
        }
        
        this.limpiarErrorGlobal();
        return usuarioValido && passwordValido;
    }

    mostrarErrorGlobal(mensaje) {
        let errorDiv = document.querySelector('.error-global');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-global';
            const form = document.querySelector('form');
            form.insertBefore(errorDiv, form.firstChild);
        }
        
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${mensaje}`;
        errorDiv.style.display = 'block';
    }

    limpiarErrorGlobal() {
        const errorDiv = document.querySelector('.error-global');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
    }

    handleFormSubmit(e) {
        const btn = document.querySelector('.btn-signin');
        const originalHTML = btn.innerHTML;
        
        // Guardar usuario si está marcado "recordar"
        if (document.getElementById('rememberCheckbox').classList.contains('checked')) {
            const usuario = document.getElementById('usuario').value;
            localStorage.setItem('savedUsername', usuario);
        } else {
            localStorage.removeItem('savedUsername');
        }
        
        // Mostrar estado de carga
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> VERIFICANDO CREDENCIALES...';
        btn.disabled = true;
        
        // El formulario se enviará normalmente después de la validación
    }

    autoFocusUsername() {
        const usernameInput = document.getElementById('usuario');
        if (usernameInput) {
            setTimeout(() => usernameInput.focus(), 500);
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new LoginManager();
});