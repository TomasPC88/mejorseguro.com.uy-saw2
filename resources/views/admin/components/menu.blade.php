<p class="menu-label">
    General
</p>
<ul class="menu-list">
    <li><a href="{{ url('admin/') }}" class="{{ w2_isactive($name, 'dashboard') }} wo-section-click">
            Dashboard
        </a>
    @can('listar-administradores')
        <li><a href="{{ url('admin/administradores') }}"
               class="{{ w2_isactive($name, 'administradores') }} wo-section-click">
                Administradores
            </a>
        </li>
    @endcan
    @can('listar-roles')
        <li><a href="{{ url('admin/roles') }}" class="{{ w2_isactive($name, 'roles') }} wo-section-click">
                Roles
            </a></li>
    @endcan
    @can('listar-portadas')
        <li><a href="{{ url('admin/portadas') }}" class="{{ w2_isactive($name, 'portadas') }} wo-section-click">
                Portadas
            </a></li>
    @endcan
    @can('listar-newsletters')
        <li><a href="{{ url('admin/newsletters') }}"
               class="{{ w2_isactive($name, 'newsletters') }} wo-section-click">
                Newsletters
            </a></li>
    @endcan
     @can('editar-configuracion')
        <li><a href="{{ url('admin/configuracion') }}"
               class="{{ w2_isactive($name, 'configuracion') }} wo-section-click">
                Configuración
            </a></li>
    @endcan

</ul>

