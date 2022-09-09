<div class="navbar-brand">
    <button id="menu-btn" type="button" class="navbar-item">
        <img src="{{ w2img('sitios_logo/iso/sitios-iso_c_64.png') }}" alt="Sitios Logo">
    </button>
    <a href="{{ route("admin.$name.index")}}" class="navbar-item nav-title">{{ $title }}</a>
</div>


<div class="navbar-end navbar-menu">
    <div class="navbar-item">
        <div class="field">
            <div class="control">
                <input id="wo2-search" name="search" class="input" type="text" placeholder="Buscar..." />
            </div>
        </div>
    </div>
    <a href="{{ route('admin.logout') }}" class="navbar-item">
        <span class="icon is-medium">
            <i class="fa fa-sign-out-alt fa-2x"></i>
        </span>
    </a>
</div>
