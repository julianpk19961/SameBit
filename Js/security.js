/**
 * security.js - Manejo de seguridad para peticiones AJAX
 * 
 * Este archivo proporciona funciones para:
 * - Obtener y gestionar tokens CSRF
 * - Agregar tokens CSRF a todas las peticiones AJAX
 * - Manejar errores de autenticación
 */

// Configurar interceptores AJAX globales para CSRF
$(document).ajaxSend(function(event, xhr, settings) {
    // Solo agregar token CSRF a peticiones POST
    if (settings.type === 'POST') {
        // Obtener token CSRF actual
        var csrfToken = getCsrfToken();
        
        if (csrfToken) {
            // Agregar token a los datos de la petición
            if (settings.data) {
                settings.data += '&csrf_token=' + encodeURIComponent(csrfToken);
            } else {
                settings.data = 'csrf_token=' + encodeURIComponent(csrfToken);
            }
        }
    }
});

// Manejar respuestas AJAX (especialmente errores de autenticación)
$(document).ajaxComplete(function(event, xhr, settings) {
    // Verificar si la respuesta indica problema de autenticación
    if (xhr.status === 401 || xhr.status === 403) {
        if (Swal.isVisible()) return;
        // Sesión expirada o no autenticado
        Swal.fire({
            icon: 'error',
            title: 'Sesión Expirada',
            text: 'Su sesión ha expirado. Por favor inicie sesión nuevamente.',
            timer: 3000,
            showConfirmButton: false
        }).then(function() {
            // Redirigir a login
            window.location.href = '/pages/login.php';
        });
    }
    
    // Verificar si la respuesta es JSON y contiene error de CSRF
    try {
        var response = JSON.parse(xhr.responseText);
        if (response.error && response.error.includes('CSRF')) {
            Swal.fire({
                icon: 'error',
                title: 'Error de Seguridad',
                text: 'Token de seguridad inválido. Por favor recargue la página.',
                timer: 3000,
                showConfirmButton: false
            });
        }
    } catch (e) {
        // No es JSON, ignorar
    }
});

/**
 * Obtener token CSRF actual de un meta tag o variable global
 * El token se genera en el servidor y se incluye en la página
 */
function getCsrfToken() {
    // Intentar obtener de meta tag
    var metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag) {
        return metaTag.getAttribute('content');
    }
    
    // Intentar obtener de variable global (seteada por PHP)
    if (typeof window.CSRF_TOKEN !== 'undefined') {
        return window.CSRF_TOKEN;
    }
    
    // Intentar obtener de localStorage (no recomendado para CSRF, pero como fallback)
    return localStorage.getItem('csrf_token') || '';
}

/**
 * Actualizar token CSRF después de una petición exitosa
 * El servidor debería devolver un nuevo token en cada respuesta
 */
function updateCsrfToken(newToken) {
    if (newToken) {
        // Actualizar meta tag
        var metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            metaTag.setAttribute('content', newToken);
        }
        
        // Actualizar variable global
        window.CSRF_TOKEN = newToken;
        
        // Actualizar localStorage (fallback)
        localStorage.setItem('csrf_token', newToken);
    }
}

/**
 * Verificar si el usuario está autenticado
 */
function isAuthenticated() {
    var user = localStorage.getItem('user');
    return user !== null && user !== 'null';
}

/**
 * Verificar si el usuario es administrador
 */
function isAdmin() {
    if (!isAuthenticated()) {
        return false;
    }
    
    try {
        var user = JSON.parse(localStorage.getItem('user'));
        return user.privilegeSet === 'root' || user.privilegeSet === 'admin' || user.privilegeSet === 'administrador';
    } catch (e) {
        return false;
    }
}

/**
 * Redirigir a login si no está autenticado
 */
function requireAuth() {
    if (!isAuthenticated()) {
        window.location.href = '/pages/login.php';
        return false;
    }
    return true;
}

/**
 * Redirigir si no es administrador
 */
function requireAdmin() {
    if (!requireAuth()) {
        return false;
    }
    
    if (!isAdmin()) {
        Swal.fire({
            icon: 'error',
            title: 'Acceso Denegado',
            text: 'No tiene permisos para acceder a esta sección',
            timer: 3000
        });
        return false;
    }
    return true;
}

/**
 * Cerrar sesión de forma segura
 */
function logout() {
    // Limpiar localStorage
    localStorage.removeItem('user');
    localStorage.removeItem('csrf_token');
    
    // Redirigir a logout del servidor
    window.location.href = '/config/logout.php';
}

/**
 * Función helper para peticiones AJAX seguras
 * Agrega automáticamente token CSRF y maneja errores
 */
function secureAjax(options) {
    // Configuración por defecto
    var defaults = {
        beforeSend: function(xhr) {
            // Verificar autenticación
            if (!isAuthenticated() && options.requireAuth !== false) {
                requireAuth();
                return false;
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 401) {
                Swal.fire({
                    icon: 'error',
                    title: 'Sesión Expirada',
                    text: 'Por favor inicie sesión nuevamente',
                    timer: 3000
                }).then(function() {
                    window.location.href = '/pages/login.php';
                });
            } else if (xhr.status === 403) {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso Denegado',
                    text: 'No tiene permisos para realizar esta acción',
                    timer: 3000
                });
            } else {
                // Error genérico
                console.error('AJAX Error:', status, error);
            }
            
            // Llamar a error handler original si existe
            if (options.originalError) {
                options.originalError(xhr, status, error);
            }
        }
    };
    
    // Guardar error handler original
    if (options.error) {
        defaults.originalError = options.error;
    }
    
    // Fusionar opciones
    var finalOptions = $.extend({}, defaults, options);
    
    // Realizar petición AJAX
    return $.ajax(finalOptions);
}

// Exportar funciones para uso global
window.Security = {
    getCsrfToken: getCsrfToken,
    updateCsrfToken: updateCsrfToken,
    isAuthenticated: isAuthenticated,
    isAdmin: isAdmin,
    requireAuth: requireAuth,
    requireAdmin: requireAdmin,
    logout: logout,
    secureAjax: secureAjax
};

// Inicializar cuando el documento esté listo
$(document).ready(function() {
    // Verificar autenticación en cada página (excepto login)
    var currentPage = window.location.pathname;
    if (!currentPage.includes('login.php') && !isAuthenticated()) {
        // No redirigir inmediatamente, dejar que cada página maneje su autenticación
        console.warn('Usuario no autenticado');
    }
});