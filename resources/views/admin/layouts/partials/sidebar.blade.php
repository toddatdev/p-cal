<div class="text-center h-76 border-bottom d-flex align-items-center justify-content-center">
    <a href="{{ route('dashboard') }}"><img src="{{asset('assets/admin/logo.svg')}}" class="img-fluid w-75" alt=""></a>
</div>
<div class="sidebar-nav">
    <ul class="navbar-nav flex-fill w-100 mb-2">
        <li class="nav-item dropdown mb-3">
            <a href="{{route('dashboard')}}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'sidebar-active-link' : '' }}">
                <span class="ml-3 item-text">Dashboard</span>
            </a>

        </li>
        @can('roles')
            <li class="nav-item dropdown mb-3">
                <a href="{{route('roles')}}" class="nav-link
                    {{ request()->routeIs('roles') ? 'sidebar-active-link' : '' }} {{ request()->routeIs('create-role') ? 'sidebar-active-link' : '' }}">
                    <span class="ml-3 item-text ">Roles</span>
                </a>
            </li>
        @endcan

        @can('employees')
            <li class="nav-item dropdown mb-3">
                <a href="{{route('employees')}}" class="nav-link {{ request()->routeIs('employees') ? 'sidebar-active-link' : '' }}">
                    <span class="ml-3 item-text ">Employees</span>
                </a>
            </li>
        @endcan

        @can('commissions')
            <li class="nav-item dropdown mb-3">
                <a href="#" class="nav-link {{ request()->routeIs('commissions') ? 'sidebar-active-link' : '' }}">
                    <span class="ml-3 item-text ">Commissions</span>
                </a>
            </li>
        @endcan

        @can('projects')
            <li class="nav-item dropdown mb-3">
                <a href="{{route('projects')}}" class="nav-link {{ request()->routeIs('projects') ? 'sidebar-active-link' : '' }} {{ request()->routeIs('project.details') ? 'sidebar-active-link' : '' }}">
                    <span class="ml-3 item-text ">Projects</span>
                </a>
            </li>
        @endcan

        @can('admin settings')
            <li class="nav-item dropdown mb-3">
                <a href="{{route('settings')}}" class="nav-link {{ request()->routeIs('settings') ? 'sidebar-active-link' : '' }}">
                    <span class="ml-3 item-text ">Admin Settings</span>
                </a>
            </li>
        @endcan


        <li class="nav-item dropdown mb-3">
            <a href="javascript:void(0)" class="nav-link" id="logoutBtnLink">
                <span class="ml-3 item-text">Logout</span>
            </a>
        </li>
    </ul>

</div>


<form method="POST" action="{{ route('logout') }}" class="inline" style="display: none">
    @csrf

    <button type="submit" class="btn btn-lg underline fs-12" id="logoutSite" style="display: none">
        {{ __('Log Out') }}
    </button>
</form>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#logoutBtnLink').click(function() {
                $('#logoutSite').click();
            });
        });
    </script>
@endpush()
