<?php
/**
 * Utilidades SQL para gestión de permisos
 * Use estos queries para operaciones comunes
 */

/**
 * Obtener permisos de un usuario para un módulo específico
 * 
 * SELECT p.name, p.slug, pp.can_access
 * FROM profile_permissions pp
 * INNER JOIN module_permissions mp ON pp.module_permission_id = mp.id
 * INNER JOIN modules m ON mp.module_id = m.id
 * INNER JOIN permissions p ON mp.permission_id = p.id
 * INNER JOIN users u ON u.profile_id = pp.profile_id
 * WHERE u.id = ? AND m.slug = ?
 * ORDER BY p.name;
 * 
 * Parámetros: [user_id, module_slug]
 */

/**
 * Obtener todos los usuarios con su perfil
 * 
 * SELECT u.id, u.username, u.first_name, u.last_name, p.name as profile_name, p.slug as profile_slug
 * FROM users u
 * INNER JOIN profiles p ON u.profile_id = p.id
 * WHERE u.active = 1
 * ORDER BY u.first_name, u.last_name;
 */

/**
 * Cambiar perfil de usuario
 * 
 * UPDATE users
 * SET profile_id = ?, updated_at = NOW()
 * WHERE id = ? AND id != ? -- Prevenir cambiar el último admin
 * 
 * Parámetros: [new_profile_id, user_id, admin_user_id]
 */

/**
 * Crear un nuevo perfil basado en otro
 * 
 * INSERT INTO profiles (name, slug, description, active)
 * VALUES (?, ?, ?, 1);
 * 
 * -- Luego copiar permisos:
 * INSERT INTO profile_permissions (profile_id, module_permission_id, can_access)
 * SELECT ?, module_permission_id, can_access
 * FROM profile_permissions
 * WHERE profile_id = ?;
 * 
 * Parámetros: [name, slug, description, new_profile_id, source_profile_id]
 */

/**
 * Obtener resumen de permisos por perfil
 * 
 * SELECT 
 *     p.name as profile_name,
 *     m.name as module_name,
 *     COUNT(CASE WHEN pp.can_access = 1 THEN 1 END) as granted_count,
 *     COUNT(*) as total_count
 * FROM profiles p
 * LEFT JOIN profile_permissions pp ON p.id = pp.profile_id
 * LEFT JOIN module_permissions mp ON pp.module_permission_id = mp.id
 * LEFT JOIN modules m ON mp.module_id = m.id
 * WHERE p.active = 1
 * GROUP BY p.id, m.id
 * ORDER BY p.name, m.name;
 */

/**
 * Obtener auditoría de cambios (si se registra)
 * 
 * SELECT * FROM audit_log
 * WHERE action = 'permission_updated'
 * ORDER BY created_at DESC
 * LIMIT 100;
 */

/**
 * Desactivar un perfil (sin eliminar datos)
 * 
 * UPDATE profiles
 * SET active = 0
 * WHERE id = ? AND slug != 'admin';
 * 
 * Parámetro: [profile_id]
 */

/**
 * Resetear permisos de un perfil a todos en false
 * 
 * UPDATE profile_permissions
 * SET can_access = 0
 * WHERE profile_id = (
 *     SELECT id FROM profiles WHERE slug = ?
 * );
 * 
 * Parámetro: [profile_slug]
 */

/**
 * Otorgar permiso específico a un perfil
 * 
 * INSERT INTO profile_permissions (profile_id, module_permission_id, can_access)
 * SELECT 
 *     ?,
 *     mp.id,
 *     1
 * FROM module_permissions mp
 * INNER JOIN modules m ON mp.module_id = m.id
 * INNER JOIN permissions p ON mp.permission_id = p.id
 * WHERE m.slug = ? AND p.slug = ?
 * ON DUPLICATE KEY UPDATE can_access = 1;
 * 
 * Parámetros: [profile_id, module_slug, permission_slug]
 */

/**
 * Revocar permiso específico de un perfil
 * 
 * UPDATE profile_permissions
 * SET can_access = 0
 * WHERE profile_id = ? AND module_permission_id = (
 *     SELECT mp.id
 *     FROM module_permissions mp
 *     INNER JOIN modules m ON mp.module_id = m.id
 *     INNER JOIN permissions p ON mp.permission_id = p.id
 *     WHERE m.slug = ? AND p.slug = ?
 * );
 * 
 * Parámetros: [profile_id, module_slug, permission_slug]
 */

/**
 * Contar cuántos usuarios tienen cada perfil
 * 
 * SELECT p.name, p.slug, COUNT(u.id) as user_count
 * FROM profiles p
 * LEFT JOIN users u ON p.id = u.profile_id AND u.active = 1
 * WHERE p.active = 1
 * GROUP BY p.id
 * ORDER BY user_count DESC;
 */

?>
