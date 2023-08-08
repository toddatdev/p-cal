@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

    <div class="">
        <h4 class="fw-bold mb-4">Edit Profile</h4>

        <div class="card mb-4"
{{--             style="height: 75vh"--}}
        >
            <form id="profile-form" onsubmit="return false;">
                <div class="card-body">
                    <h6 class="fw-bold mb-4">Personal Profile</h6>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img id="display_profile_image"
                                 src="{{ asset($profile['profile_photo_path'] ? 'storage/'.$profile['profile_photo_path'] : 'assets/admin/profile.svg') }}"
                                 class="rounded-circle" width="60" height="60" alt="..." style="object-fit: cover">
                            <input type="file" id="profile_image" name="profile_image" hidden/>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <input type="file" style="display: none">
                            <a href="javascript:void(0)" class="me-3 text-decoration-none text-dark"
                               onclick="changeProfile()">Change Profile</a>
                            <a href="javascript:void(0)" onclick="removeProfile()"
                               class="text-dark text-decoration-none ">Remove</a>
                        </div>
                    </div>
                    <div id="validate-profile-image" class="invalid-feedback d-block"></div>
                    <div class="row mt-4">
                        <div class="col-lg-6 mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">First Name <span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="text"
                                       class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                       name="first_name"
                                       id="first_name"
                                       placeholder="Enter First Name"
                                       value="@isset($profile){{ $profile['first_name'] }}@endisset"
                                >
                            </fieldset>
                            <div id="validate-first-name" class="invalid-feedback"></div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Last Name <span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="text"
                                       class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                       name="last_name"
                                       id="last_name"
                                       placeholder="Enter Last Name"
                                       value="@isset($profile){{ $profile['last_name'] }}@endisset"
                                >
                            </fieldset>
                            <div id="validate-last-name" class="invalid-feedback"></div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Email Address<span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="text"
                                       class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                       name="email"
                                       id="email"
                                       placeholder="Email"
                                       value="@isset($profile){{ $profile['email'] }}@endisset"
                                >
                            </fieldset>
                            <div id="validate-email" class="invalid-feedback"></div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Phone No <span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="text"
                                       class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                       name="phone"
                                       id="phone"
                                       placeholder="Enter Phone No"
                                       value="@isset($profile){{ $profile['phone'] }}@endisset"
                                >
                            </fieldset>
                            <div id="validate-phone" class="invalid-feedback"></div>
                        </div>
                        @if(checkAuthUserRole() != 1)
                        <div class="col-lg-6 mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Job Title <span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="text"
                                       class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                       name="job_title"
                                       id="job_title"
                                       placeholder="Enter Job Title"
                                       value="@isset($profile){{ $profile['job_title'] }}@endisset"
                                >
                            </fieldset>
                            <div id="validate-job-title" class="invalid-feedback"></div>
                        </div>
                        @endif
                        <div class="col-lg-6 mb-3">
                            <fieldset class="input-group border rounded-1 ps-1">
                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Role <span
                                        class="text-danger ">*</span>
                                </legend>
                                <input type="text"
                                       class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                       name="role"
                                       id="role"
                                       placeholder="Enter Role"
                                       value="@isset($profile['role']){{ $profile['role'] }}@else Superadmin @endisset"
                                       readonly
                                >
                            </fieldset>
                            <div id="validate-role" class="invalid-feedback"></div>
                        </div>
                        @if($profile->role_id === null)
                            <div class="col-lg-6 mb-3">
                                <fieldset class="input-group border rounded-1 ps-1">
                                    <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Change Password <span
                                            class="text-danger ">*</span>
                                    </legend>
                                    <input type="password"
                                           class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                           name="change_password"
                                           id="change_password"
                                           placeholder="Enter Password"
                                    >
                                </fieldset>
                                <div id="validate-change-password" class="invalid-feedback"></div>
                            </div>
                        @endif
                        <div class="col-lg-6"></div>
                    </div>
                </div>
                <div class="d-flex justify-content-end pb-3 pe-3 ">
                    <div class="btn-group-footer z-index-99">
                        <a href="{{route('dashboard')}}"
                           class="btn btn-light min-w-120 btn-lg rounded-2 fs-14 ">Cancel</a>
                        <button type="submit" class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14"
                                id="create-profile-btn">Save
                        </button>
                    </div>
                </div>
            </form>


        </div>


    </div>

    <!--  delete  Modal -->
    @include('admin.modals.delete-modal')

    <!--  Success  Modal -->
    @include('admin.modals.success-modal')

    <!--  error Modal -->
    @include('admin.modals.error-modal')

    @push('scripts')

        <script>

            let active_user = '{{ checkAuthUserRole() }}';

        </script>

        <script>
            let remove_profile_picture = null;

            function changeProfile() {

                $('#profile_image').trigger('click');
            }

            $('#profile_image').on('change', function () {

                var input = $(this)[0];

                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#display_profile_image').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            });

            function removeProfile() {

                remove_profile_picture = 1;
                $('#display_profile_image').attr('src', 'assets/admin/profile.svg');
            }
        </script>

        <script>

            function removeProfileValidations() {

                $('#first_name').removeClass('is-invalid');
                $('#last_name').removeClass('is-invalid');
                $('#email').removeClass('is-invalid');
                $('#phone').removeClass('is-invalid');
                $('#job_title').removeClass('is-invalid');

                $('#validate-first-name').removeClass('d-block');
                $('#validate-last-name').removeClass('d-block');
                $('#validate-email').removeClass('d-block');
                $('#validate-phone').removeClass('d-block');
                $('#validate-job-title').removeClass('d-block');
                $('#validate-role').removeClass('d-block');
                $('#validate-profile-image').removeClass('d-block');
            }

            function showControllerReturnedProfileValidations(errors) {

                if (errors.first_name) {

                    $('#first_name').addClass('is-invalid');
                    $('#validate-first-name').addClass('d-block').text(errors.first_name);
                }
                if (errors.last_name) {

                    $('#last_name').addClass('is-invalid');
                    $('#validate-last-name').addClass('d-block').text(errors.last_name);
                }
                if (errors.email) {

                    $('#email').addClass('is-invalid');
                    $('#validate-email').addClass('d-block').text(errors.email);
                }
                if (errors.phone) {

                    $('#phone').addClass('is-invalid');
                    $('#validate-phone').addClass('d-block').text(errors.phone);
                }
                if (errors.job_title) {

                    $('#job_title').addClass('is-invalid');
                    $('#validate-job-title').addClass('d-block').text(errors.job_title);
                }
                if (errors.role) {

                    $('#role').addClass('is-invalid');
                    $('#validate-role').addClass('d-block').text(errors.role);
                }
                if (errors.profile_image) {

                    $('#validate-profile-image').addClass('d-block').text(errors.profile_image);
                }
            }

        </script>

        <script>

            $('#profile-form').on('submit', function () {

                removeProfileValidations();

                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let email = $('#email').val();
                let phone = $('#phone').val();
                let profile_image = $('#profile_image')[0].files[0];
                let profileError = false;

                if (!first_name) {

                    $('#first_name').addClass('is-invalid');
                    $('#validate-first-name').addClass('d-block').text('First name field is required.');
                    profileError = true;
                }
                if (!last_name) {

                    $('#last_name').addClass('is-invalid');
                    $('#validate-last-name').addClass('d-block').text('Last name field is required.');
                    profileError = true;
                }
                if (!email) {

                    $('#email').addClass('is-invalid');
                    $('#validate-email').addClass('d-block').text('Email field is required.');
                    profileError = true;
                }
                if (!phone) {

                    $('#phone').addClass('is-invalid');
                    $('#validate-phone').addClass('d-block').text('Phone field is required.');
                    profileError = true;
                }
                if (active_user !== '1') {

                    if (!$('#job_title').val()) {

                        $('#job_title').addClass('is-invalid');
                        $('#validate-job-title').addClass('d-block').text('Job title field is required.');
                        profileError = true;
                    }
                }

                if (profileError) {

                    return false;
                }

                $('#create-profile-btn').addClass('disabled');

                let formData = new FormData();
                formData.append('user_id', '{{ $profile->id }}');
                formData.append('first_name', first_name);
                formData.append('last_name', last_name);
                formData.append('email', email);
                formData.append('phone', phone);
                if (active_user === '1') {

                    formData.append('change_password', $('#change_password').val());
                }
                else {

                    formData.append('job_title', $('#job_title').val());
                }
                if (profile_image != undefined)
                    formData.append('profile_image', profile_image);
                formData.append('remove_profile', remove_profile_picture);

                $.ajax({
                    'url': '{{ route('profile.store') }}',
                    'type': 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {

                        if (response.success) {

                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');

                            setTimeout(function () {
                                window.location = '{{ route('profile') }}';
                            }, 2000);
                        } else {

                            if (response.errors) {

                                showControllerReturnedProfileValidations(response.errors)
                            } else {

                                $('#showErrorResponseMsg').text(response.message);
                                $('#errorModal').modal('show');
                            }

                            $('#create-profile-btn').removeClass('disabled');
                        }
                    }
                })
            });

        </script>

    @endpush

@endsection
