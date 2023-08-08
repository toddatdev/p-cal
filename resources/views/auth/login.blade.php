<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{--            <x-authentication-card-logo />--}}
        </x-slot>
        <div class="container vh-100">
            <div class="row vh-100 d-flex justify-content-center align-items-center">
                <div class="col-md-8 col-lg-5 mx-auto  login-form">

                    <div class="text-center mb-5">
                        <a href="{{route('root')}}"><img src="{{asset('assets/admin/logo.svg')}}" class="img-fluid mb-3"
                                                         alt=""></a>
                        <h3 class="fw-bold">Login to Your Account</h3>
                    </div>

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Email <span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="email"
                                       :value="old('email')"
                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                       name="email"
                                       placeholder="Email"
                                       id="email"
                                >
                            </fieldset>
                        </div>
                        <div class="mb-3" x-data="{showPassword: false}">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Password</legend>
                                <input
                                    x-bind:type="showPassword ? 'text' : 'password'"
                                    class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                    name="password"
                                    required
                                    id="password"
                                    placeholder="********"
                                    aria-label=""
                                >
                                <a id="changePassTarget-2"
                                   class="input-group-append input-group-text border-0 bg-transparent text-decoration-none"
                                   style="outline-color: transparent"
                                   href="javascript:;"
                                   @click.prevent="showPassword  = !showPassword"
                                >

                                    <i id="changePassIcon" class="fa fa-eye text-dark"
                                       :class="{'fa fa-eye': showPassword, 'fa fa-eye-slash': !showPassword}"></i>
                                </a>
                            </fieldset>
                            <x-validation-errors class="mb-0 text-primary"/>
                            @if(session('error'))
                                <p id="session_alert">{{ session('error') }}</p>
                            @endif

                        </div>
                        <div>
                            <button class="btn btn-lg fs-14 py-3 btn-secondary w-100" type="submit">
                                {{ __('Login') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
