<div class="d-flex justify-content-between bg-white px-4 border-bottom align-items-center h-76">
    <div class="d-flex align-items-center">
        <i class="fas fa-align-left me-1 me-md-3 fs-4 light-icon " id="menu-toggle"></i>
        <div class="input-group search-input">
            <span class="input-group-text bg-transparent border-0 pe-2 d-none d-md-block" id="basic-addon1">
                <img src="{{asset('assets/admin/search-icon.svg')}}" width="18" alt="">
            </span>
            <form id="header_search_form" action="{{ route('projects') }}" method="get">
                <input type="text"
                       class="form-control border-0 ms-2 ms-lg-1 mt-1 ps-0 bg-transparent rounded-0 outline-none"
                       placeholder="Search Project" id="header_search" name="header_search">
            </form>
        </div>

    </div>

    <div class="d-flex align-items-center">

        <div class="">
            <a href="{{route('notifications')}}" class="text-decoration-none position-relative">
                <img src="{{asset('assets/icons/bell.svg')}}" width="30" alt="">
                <span
                    class="position-absolute top-0  translate-middle bg-primary border border-white @if($notificationsExist == 1) rounded-circle notify-dot @endif">
                <span class="visually-hidden">New alerts</span>
              </span>
            </a>

        </div>

        <div class="d-flex header-profile align-items-center border-start ps-2 ps-md-3 ms-3 h-76">
            <a href="{{route('profile')}}" class="text-decoration-none text-dark">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <img
                            src="{{ asset(auth()->user()->profile_photo_path ? 'storage/'.auth()->user()->profile_photo_path : 'assets/admin/profile.svg')}}"
                            class="rounded-circle" width="35" height="35" style="object-fit: cover" alt="...">
                    </div>
                    <div class="flex-grow-1 ms-2 d-none d-md-block">
                        <h6 class="mb-0 fw-bold">{{ $userName }}</h6>
                        <p class="mb-0">{{ $userRole }}</p>
                    </div>
                </div>
            </a>
            <div class="dropdown header-profile-dropdown">
                <a class="btn border-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-chevron-down fs-18" aria-hidden="true"></i>
                </a>

                <ul class="dropdown-menu" style="border-color: #00000010 !important;">
                    <li><a class="dropdown-item d-md-none"
                           href="{{route('profile')}}">{{auth()->user()->first_name ?? ''}} {{auth()->user()->last_name ?? ''}}</a>
                    </li>
                    <li><a class="dropdown-item d-none d-md-block" href="{{route('profile')}}">Profile</a></li>
                    <li><a class="dropdown-item" id="logoutBtn" href="#!">Logout</a></li>
                </ul>
            </div>

        </div>

    </div>
</div>


<form method="POST" action="{{ route('logout') }}" class="inline" style="display: none">
    @csrf

    <button type="submit" class="btn btn-lg underline fs-12" id="logoutSiteBtn" style="display: none">
        {{ __('Log Out') }}
    </button>
</form>

@push('scripts')

    <script>

        $('#header_search').keypress(function (e) {
            if (e.keyCode == 13) {
                $('#header_search_form').submit();
            }
        });

    </script>

    <script>
        $(document).ready(function () {
            $('#logoutBtn').click(function () {
                $('#logoutSiteBtn').click();
            });
        });
    </script>

@endpush
