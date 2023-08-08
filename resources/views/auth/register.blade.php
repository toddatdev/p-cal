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
                        <h3 class="fw-bold">Register to Your Account</h3>
                    </div>

                    <x-validation-errors class="mb-4"/>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-floating mb-3">
                            <input id="first_name" type="text" name="first_name" :value="old('first_name')"
                                   class="form-control" required id="floatingInput" placeholder="First Name">
                            <label for="floatingInput">First Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="last_name" type="text" name="last_name" :value="old('last_name')"
                                   class="form-control" required id="floatingInput" placeholder="Last Name">
                            <label for="floatingInput">Last Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="username" type="text" name="username" :value="old('username')"
                                   class="form-control" required id="floatingInput" placeholder="User Name">
                            <label for="floatingInput">User Name</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="email" name="email" id="email" :value="old('email')"
                                   class="form-control" required id="floatingInput" placeholder="Enter your email">
                            <label for="floatingInput">Email address</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="phone" type="text" name="phone" :value="old('phone')"
                                   class="form-control" required id="floatingInput" placeholder="Phone number">
                            <label for="floatingInput">Phone</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="dob" type="date" name="dob" :value="old('dob')"
                                   class="form-control" required id="floatingInput">
                            <label for="floatingInput">Date of birth</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="address" type="text" name="address" :value="old('address')"
                                   class="form-control" required id="floatingInput" placeholder="Addresss">
                            <label for="floatingInput">Address</label>
                        </div>


                        <div class="form-floating mb-3">
                            <input id="password" type="password" name="password" :value="old('password')"
                                   class="form-control" required id="floatingInput" placeholder="Enter your password">
                            <label for="floatingInput">Password</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input id="password_confirmation" type="password" name="password_confirmation" :value="old('password_confirmation')"
                                   class="form-control" required id="floatingInput" placeholder="Enter your Confirm password">
                            <label for="floatingInput">Confirm Password</label>
                        </div>


{{--                        <div>--}}
{{--                            <x-label for="name" value="{{ __('Name') }}"/>--}}
{{--                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"--}}
{{--                                     required autofocus autocomplete="name"/>--}}
{{--                        </div>--}}

{{--                        <div class="mt-4">--}}
{{--                            <x-label for="email" value="{{ __('Email') }}"/>--}}
{{--                            <x-input id="email" class="block mt-1 w-full" type="email" name="email"--}}
{{--                                     :value="old('email')" required autocomplete="username"/>--}}
{{--                        </div>--}}

{{--                        <div class="mt-4">--}}
{{--                            <x-label for="password" value="{{ __('Password') }}"/>--}}
{{--                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required--}}
{{--                                     autocomplete="new-password"/>--}}
{{--                        </div>--}}

{{--                        <div class="mt-4">--}}
{{--                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}"/>--}}
{{--                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password"--}}
{{--                                     name="password_confirmation" required autocomplete="new-password"/>--}}
{{--                        </div>--}}

                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="mt-4">
                                <x-label for="terms">
                                    <div class="flex items-center">
                                        <x-checkbox name="terms" id="terms" required/>

                                        <div class="ml-2">
                                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                        </div>
                                    </div>
                                </x-label>
                            </div>
                        @endif


                        <div class="text-end mb-4">
                            <a class="text-primary"
                               href="{{ route('login') }}">
                                Already registered?
                            </a>
                        </div>

                        <div>
                            <button class="btn btn-lg fs-14 py-3 btn-secondary w-100" type="submit">
                               Register
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </x-authentication-card>


</x-guest-layout>
