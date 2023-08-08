<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{--            <x-authentication-card-logo />--}}
        </x-slot>


        <div class="container vh-100">
            <div class="row vh-100 d-flex justify-content-center align-items-center">
                <div class="col-md-8 col-lg-5 mx-auto  login-form">

                    <div class="text-center mb-5">
                        <a href="{{route('root')}}"><img src="{{asset('assets/admin/logo.svg')}}" class="img-fluid mb-3" alt=""></a>
                        <h3 class="fw-bold">Forgot Your Password?</h3>
                    </div>


                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    <x-validation-errors class="mb-2 text-danger"/>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf


                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" :value="old('email')"
                                   class="form-control" required id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email</label>
                        </div>

{{--                        <div class="block">--}}
{{--                            <x-label for="email" value="{{ __('Email') }}"/>--}}
{{--                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"--}}
{{--                                     :value="old('email')" required autofocus autocomplete="username"/>--}}
{{--                        </div>--}}

                        <div>
                            <button class="btn btn-lg fs-14 py-3 btn-secondary w-100" type="submit">
                                {{ __('Email Password Reset Link') }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>


    </x-authentication-card>
</x-guest-layout>
