<?php

return [

    'title' => 'sistema de gestion escolar',
    'title_prefix' => '',
    'title_postfix' => '',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'google_fonts' => [
        'allowed' => true,
    ],

    'logo' => '<b>Admin</b>LTE',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    'menu' => [
        [
            'text' => 'configuracion',
            'url' => 'admin/configuracion',
            'icon' => 'fas fa-fw fa-cog',
            'classes' => 'bg-blue text-white',
        ],
        [
            'text' => 'Gestiones',
            'url' => 'admin/gestiones',
            'icon' => 'fas fa-fw fa-tasks',
            'classes' => 'bg-blue text-white',
        ],
        [
            'text' => 'Periodos',
            'url' => 'admin/periodos',
            'icon' => 'fas fa-fw fa-calendar-alt',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Niveles',
            'url' => 'admin/niveles',
            'icon' => 'fas fa-fw fa-layer-group',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Grados',
            'url' => 'admin/grados',
            'icon' => 'fas fa-fw fa-list-alt',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Paralelos',
            'url' => 'admin/paralelos',
            'icon' => 'fas fa-fw fa-clone',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Turnos',
            'url' => 'admin/turnos',
            'icon' => 'fas fa-fw fa-clock',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Materias',
            'url' => 'admin/materias',
            'icon' => 'fas fa-fw fa-book',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Roles',
            'url' => 'admin/roles',
            'icon' => 'fas fa-fw fa-user-check',
            'classes' => 'bg-blue text-white',

        ],
        [
            'text' => 'Personal',
            'icon' => 'fas fa-fw fa-users-cog',
            'classes' => 'bg-blue text-white',
            'submenu'=> [
                [
                    'text' => 'Administrativo',
                    'url' => 'admin/personal/administrativo',
                    'classes' => 'bg-white text-black'
                ],
                [
                    'text' => 'Docente',
                    'url' => 'admin/personal/docente',
                    'classes' => 'bg-white text-black'
                ],
            ],

        ],
        [
            'text' => 'Asignaciones',
            'icon' => 'fas fa-fw fa-chalkboard-teacher',
            'classes' => 'bg-blue text-white',
            'url' => 'admin/asignaciones'  // CORREGIDO: era 'admin/asignciones'
        ],
        [
            'text' => 'Padres de Familia',
            'icon' => 'fas fa-fw fa-house-user',
            'classes' => 'bg-blue text-white',
            'url' => 'admin/ppffs'
        ],
        [
            'text' => 'Estudiantes',
            'icon' => 'fas fa-fw fa-user-graduate',
            'classes' => 'bg-blue text-white',
            'url' => 'admin/estudiantes'
        ],
        [
            'text' => 'Matriculacion',
            'icon' => 'fas fa-fw fa-clipboard-list',
            'classes' => 'bg-blue text-white',
            'url' => 'admin/matriculaciones'
        ],
        
    ],

    'livewire' => false,

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization (CONFIGURACIÓN CORREGIDA)
    |--------------------------------------------------------------------------
    */
    'plugins' => [
        // DataTables con exportación a Excel, PDF, etc.
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js',
                ],
            ],
        ],

        // SweetAlert2
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
                ],
            ],
        ],

        // Select2 para mejores selectores
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css',
                ],
            ],
        ],
    ],
];