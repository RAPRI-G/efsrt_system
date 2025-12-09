<?php
// helpers/RolesHelper.php

class RolesHelper
{
    // Constantes de tipos según tu BD
    const TIPO_ADMINISTRADOR = 2;
    const TIPO_DOCENTE = 1;
    const TIPO_ESTUDIANTE = 3;

    // Roles como strings (los que ya tienes en UsuarioModel)
    const ROL_ADMINISTRADOR = 'administrador';
    const ROL_DOCENTE = 'docente';
    const ROL_ESTUDIANTE = 'estudiante';

    // Mapeo de tipo a rol
    public static function getRolFromTipo($tipo)
    {
        $map = [
            self::TIPO_ADMINISTRADOR => self::ROL_ADMINISTRADOR,
            self::TIPO_DOCENTE => self::ROL_DOCENTE,
            self::TIPO_ESTUDIANTE => self::ROL_ESTUDIANTE
        ];
        return $map[$tipo] ?? 'usuario';
    }

    // Permisos por rol (qué controladores puede acceder cada rol)
    private static $permisosPorRol = [
        'administrador' => [
            'Inicio',
            'Usuario',
            'Empresa',
            'Estudiante',
            'Modulos',
            'Practica',
            'Asistencia',
            'Reportes',
            'Buscar'
        ],
        'docente' => [
            'Inicio',
            'Estudiante',
            'Asistencia',
            'Reportes',
            'Buscar'
        ],
        'estudiante' => [
            'DashboardEstudiante',
            'AsistenciaEstudiante'

        ]
    ];

    // Permisos por módulo para el menú
    private static $menuPermisos = [
        'inicio' => ['administrador', 'docente'],
        'dashboard_estudiante' => ['estudiante'],
        'usuarios' => ['administrador'],
        'empresas' => ['administrador'],
        'estudiantes' => ['administrador', 'docente'],
        'modulos' => ['administrador'],
        'practicas' => ['administrador',],
        'asistencias' => ['administrador', 'docente'],
        'asistencia_estudiante' => ['estudiante'],
        'reportes' => ['administrador', 'docente'],
        'informacion' => ['administrador', 'docente', 'estudiante']
    ];

    /**
     * Verifica si un rol tiene acceso a un controlador
     */
    public static function puedeAccederControlador($rol, $controlador)
    {
        return isset(self::$permisosPorRol[$rol]) &&
            in_array($controlador, self::$permisosPorRol[$rol]);
    }

    /**
     * Verifica si un rol puede ver un ítem del menú
     */
    public static function puedeVerMenu($rol, $moduloMenu)
    {
        return isset(self::$menuPermisos[$moduloMenu]) &&
            in_array($rol, self::$menuPermisos[$moduloMenu]);
    }

    /**
     * Obtiene todos los controladores permitidos para un rol
     */
    public static function getControladoresPermitidos($rol)
    {
        return self::$permisosPorRol[$rol] ?? [];
    }

    /**
     * Obtiene todos los módulos de menú permitidos para un rol
     */
    public static function getMenusPermitidos($rol)
    {
        $menus = [];
        foreach (self::$menuPermisos as $modulo => $roles) {
            if (in_array($rol, $roles)) {
                $menus[] = $modulo;
            }
        }
        return $menus;
    }
}
