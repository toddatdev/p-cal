@extends('admin.layouts.app')
@section('title', 'Employees')

@section('content')

    <div class="employees">
        <h4 class="fw-bold mb-3">Employees</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Employees</h6>
                    <a href="#!"
                       data-bs-toggle="modal" data-bs-target="#createEmployeeModal"
                       class="btn btn-soft-primary rounded-pill px-3" onclick="resetCreateEmployeeModal()"
                    >Create Employee</a>
                </div>

                <!--  Employee Create Modal -->
                <div class="modal fade" id="createEmployeeModal" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h6 class="modal-title text-primary fw-600" id="create-employee-form-text">Create
                                    Employee</h6>
                                {{--                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                            </div>
                            <form id="employee-form" onsubmit="return false;">

                                <input type="hidden" name="employee_id" id="employee_id">

                                <div class="modal-body" x-data="{ selectedRole: '' }">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">First Name
                                                    <span class="text-danger ">*</span>
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="first_name"
                                                       placeholder="First Name"
                                                       id="first_name"
                                                >
                                            </fieldset>
                                            <div id="validate-first-name" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Last Name
                                                    <span class="text-danger ">*</span>
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="last_name"
                                                       placeholder="Last Name"
                                                       id="last_name"
                                                >
                                            </fieldset>
                                            <div id="validate-last-name" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Email <span
                                                        class="text-danger ">*</span>
                                                </legend>
                                                <input type="email"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="email"
                                                       placeholder="Email"
                                                       id="email"
                                                >
                                            </fieldset>
                                            <div id="validate-email" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Phone #
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="phone"
                                                       placeholder="Phone #"
                                                       id="phone"
                                                >
                                            </fieldset>
                                            <div id="validate-phone" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Date of birth #
                                                </legend>
                                                <input type="date"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="phone"
                                                       placeholder="Phone #"
                                                       id="date_of_birth"
                                                       max="{{ date('Y-m-d') }}"
                                                >
                                            </fieldset>
                                            <div id="validate-date-of-birth" class="invalid-feedback"></div>
                                        </div>


                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Job Title
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="job_title"
                                                       placeholder="Job Title"
                                                       id="job_title"
                                                >
                                            </fieldset>
                                            <div id="validate-job-title" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Role <span
                                                        class="text-danger ">*</span></legend>
                                                <select tabindex="2" name="role_id"
                                                        class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light"
                                                        id="role_id"
                                                        onchange="getParentRoleUsers(this.value)"
                                                >
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </fieldset>
                                            <div id="validate-role-id" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3" id="parent_user_select_box" style="display: none">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend" id="parent_user_label">HOD <span
                                                        class="text-danger ">*</span></legend>
                                                <select tabindex="2" name="role_id" id="parent_user"
                                                        class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">

                                                </select>
                                            </fieldset>
                                            <div id="validate-parent-user" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3" x-data="{showPassword: false}">

                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Create Password</legend>
                                                <input
                                                    x-bind:type="showPassword ? 'text' : 'password'"
                                                    class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                    name="password"
                                                    id="password"
                                                    placeholder="********"
                                                    aria-label=""
                                                >
                                                <a id="changePassTarget-2" class="input-group-append input-group-text border-0 bg-transparent text-decoration-none"
                                                   style="outline-color: transparent"
                                                   href="javascript:;"
                                                   @click.prevent="showPassword  = !showPassword"
                                                >

                                                    <i id="changePassIcon" class="fa fa-eye text-dark"
                                                       :class="{'fa fa-eye': showPassword, 'fa fa-eye-slash': !showPassword}"></i>
                                                </a>
                                            </fieldset>
                                            <div id="validate-password" class="invalid-feedback"></div>
                                        </div>


                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14"
                                            data-bs-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit" class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14" id="employee-form-btn">
                                        Save
                                    </button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>


                <div class="table-responsive" id="employees-list">
                    <table class="table h-table">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">First Name</th>
                            <th scope="col">Last Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone #</th>
                            <th scope="col">Job Title</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee['first_name'] }}</td>
                                <td>{{ $employee['last_name'] }}</td>
                                <td>{{ $employee['email'] }}</td>
                                <td>{{ $employee['phone'] }}</td>
                                <td>{{ $employee['job_title'] }}</td>
                                <td>{{ $employee['role']['name'] }}</td>
                                <td>
                                    <div class="d-flex justify-content-start align-items-center">
                                        <a href="javascript:void(0)" onclick="getEmployeeInfo('{{ $employee['id'] }}')" class="me-2 text-decoration-none">
                                            <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid" alt="">
                                        </a>
                                        <a href="javascript:void(0)" onclick="deleteRecord('{{ $employee['id'] }}')" class="text-decoration-none">
                                            <img src="{{asset('assets/icons/delete-icon.svg')}}" class="img-fluid" alt="">
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    @if(isset($employees) && count($employees) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-7 align-self-center">

                @if ($employees->hasMorePages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            @if ($employees->onFirstPage())
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="First">
                                        <span aria-hidden="true">&laquo;&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $employees->url(1) }}" aria-label="First">
                                        <span aria-hidden="true">&laquo;&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $employees->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif

                            @if ($employees->currentPage() > 1)
                                <li class="page-item"><a class="page-link" href="{{ $employees->url($employees->currentPage() - 1) }}">{{ $employees->currentPage() - 1 }}</a></li>
                            @endif

                            <li class="page-item active">
                                <a class="page-link" href="#">{{ $employees->currentPage() }}</a>
                            </li>

                            @if ($employees->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $employees->nextPageUrl() }}">{{ $employees->currentPage() + 1 }}</a></li>
                            @endif

                            @if ($employees->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $employees->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $employees->url($employees->lastPage()) }}" aria-label="Last">
                                        <span aria-hidden="true">&raquo;&raquo;</span>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Last">
                                        <span aria-hidden="true">&raquo;&raquo;</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </nav>

                @endif
            </div>
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

            function getEmployeeInfo(id) {

                $('#create-employee-form-text').text('Update Employee');

                let employeeID = id;

                $.ajax({
                    'url': '{{ route('employees.get_info') }}',
                    'type': 'GET',
                    data: {
                        'id': employeeID
                    },
                    success: function (response) {

                        if (response.success) {

                            let employee = response.employee;

                            $('#employee_id').val(employee.id);
                            $('#first_name').val(employee.first_name);
                            $('#last_name').val(employee.last_name);
                            $('#email').val(employee.email);
                            $('#phone').val(employee.phone);
                            $('#date_of_birth').val(employee.dob);
                            $('#job_title').val(employee.job_title);
                            $('#role_id').val(employee.role_id);

                            if (response.parentUsers.length > 0) {

                                $('#parent_user_select_box').show();
                                $('#parent_user_label').text(response.parentRoleName);

                                $('#parent_user').empty();
                                $('#parent_user').append($("<option></option>").attr("value", "").text("Please select "+response.parentRoleName));
                                $.each(response.parentUsers, function(key, value) {
                                    $('#parent_user').append($("<option></option>").attr("value", value.id).text(value.first_name+' '+value.last_name));
                                });
                                $('#parent_user').val(employee.parent_user_id);
                            }

                            $('#password').val(employee.password);

                            $('#createEmployeeModal').modal('show');
                        }
                        else {

                            $('#showErrorResponseMsg').text(response.message);
                            $('#errorModal').modal('show');
                        }


                    }
                })
            }

        </script>

        <script>

            function getParentRoleUsers(id) {

                $.ajax({
                    'url': '{{ route('employees.get_parent_role_users') }}',
                    'type': 'GET',
                    data: {
                        employee_role_id: id
                    },
                    success: function (response) {

                        if (response.users.parent_role_users.length > 0) {

                            $('#parent_user_label').text(response.parentRoleName);

                            $('#parent_user').empty();

                            $('#parent_user').append($("<option></option>").attr("value", "").text("Please select "+response.parentRoleName));

                            $.each(response.users.parent_role_users, function(key, value) {

                                $('#parent_user').append($("<option></option>").attr("value", value.id).text(value.first_name+' '+value.last_name));
                            });

                            $('#parent_user_select_box').show();
                        }
                        else {

                            $('#parent_user_select_box').hide();
                        }

                    }
                });
            }

        </script>

        <script>

            function removeValidations() {

                $('#first_name').removeClass('is-invalid');
                $('#validate-first-name').removeClass('d-block');
                $('#last_name').removeClass('is-invalid');
                $('#validate-last-name').removeClass('d-block');
                $('#email').removeClass('is-invalid');
                $('#validate-email').removeClass('d-block');
                $('#phone').removeClass('is-invalid');
                $('#validate-phone').removeClass('d-block');
                $('#date_of_birth').removeClass('is-invalid');
                $('#validate-date-of-birth').removeClass('d-block');
                $('#job_title').removeClass('is-invalid');
                $('#validate-job-title').removeClass('d-block');
                $('#role_id').removeClass('is-invalid');
                $('#validate-role-id').removeClass('d-block');
                $('#parent_user').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');
                $('#validate-password').removeClass('d-block');
            }

        </script>

        <script>
            function validateEmail(email) {

                var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                return emailRegex.test(email);
            }
        </script>

        <script>

            $('#employee-form').on('submit', function () {

                removeValidations();

                let employee_id = $('#employee_id').val();
                let first_name = $('#first_name').val();
                let last_name = $('#last_name').val();
                let email = $('#email').val();
                let phone = $('#phone').val();
                let date_of_birth = $('#date_of_birth').val();
                let job_title = $('#job_title').val();
                let role_id = $('#role_id').val();
                let parent_user = $('#parent_user').val();
                let password = $('#password').val();
                let formError = false;

                if(!first_name) {

                    $('#first_name').addClass('is-invalid');
                    $('#validate-first-name').addClass('d-block').text('First name field is required.');
                    formError = true;
                }
                if (!last_name) {

                    $('#last_name').addClass('is-invalid');
                    $('#validate-last-name').addClass('d-block').text('Last name field is required.');
                    formError = true;
                }
                if (!email) {

                    $('#email').addClass('is-invalid');
                    $('#validate-email').addClass('d-block').text('Email field is required.');
                    formError = true;
                }
                else if (!validateEmail(email)) {

                    $('#email').addClass('is-invalid');
                    $('#validate-email').addClass('d-block').text('Please provide a valid Email.');
                    formError = true;
                }
                if (!phone) {

                    $('#phone').addClass('is-invalid');
                    $('#validate-phone').addClass('d-block').text('Phone field is required.');
                    formError = true;
                }
                if (!date_of_birth) {

                    $('#date_of_birth').addClass('is-invalid');
                    $('#validate-date-of-birth').addClass('d-block').text('Date of birth field is required.');
                    formError = true;
                }
                if (!job_title) {

                    $('#job_title').addClass('is-invalid');
                    $('#validate-job-title').addClass('d-block').text('Job title field is required.');
                    formError = true;
                }
                if (!role_id) {

                    $('#role_id').addClass('is-invalid');
                    $('#validate-role-id').addClass('d-block').text('Role field is required.');
                    formError = true;
                }
                if ($('#parent_user_select_box').is(':visible')) {

                    if (!parent_user) {

                        $('#parent_user').addClass('is-invalid');
                        $('#validate-parent-user').addClass('d-block').text('Parent user field is required.');
                        formError = true;
                    }
                }

                if (!employee_id) {

                    if (!password) {

                        $('#password').addClass('is-invalid');
                        $('#validate-password').addClass('d-block').text('Password field is required.');
                        formError = true;
                    }
                }

                if (formError) {

                    return false;
                }

                $('#employee-form-btn').addClass('disabled');

                $.ajax({
                    'url': '{{ route('employees.store') }}',
                    'type': 'POST',
                    data: {
                        employee_id: employee_id,
                        first_name: first_name,
                        last_name: last_name,
                        email: email,
                        phone: phone,
                        date_of_birth: date_of_birth,
                        job_title: job_title,
                        role_id: role_id,
                        parent_user: parent_user,
                        password: password,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {

                        if (response.success) {

                            $('#createEmployeeModal').modal('hide');
                            console.log(response.message);

                            $('#successModal').modal('show');
                            $('#showResponseMsg').text(response.message);

                            setTimeout(function () {
                                window.location = '{{ route('employees') }}';
                            }, 2500);
                        }
                        else {

                            if (response.errors) {

                                validationResponseOfEmployee(response.errors);
                            }
                            else {

                                $('#showErrorResponseMsg').text(response.message);
                                $('#errorModal').modal('show');
                            }

                            $('#employee-form-btn').removeClass('disabled');
                        }
                    }
                })
            });
        </script>

        <script>

            function validationResponseOfEmployee (errors) {

                if(errors.first_name) {

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
                if (errors.date_of_birth) {

                    $('#date_of_birth').addClass('is-invalid');
                    $('#validate-date-of-birth').addClass('d-block').text(errors.date_of_birth);
                }
                if (errors.job_title) {

                    $('#job_title').addClass('is-invalid');
                    $('#validate-job-title').addClass('d-block').text(errors.job_title);
                }
                if (errors.role_id) {

                    $('#role_id').addClass('is-invalid');
                    $('#validate-role-id').addClass('d-block').text(errors.role_id);
                }
                if (errors.password) {

                    $('#password').addClass('is-invalid');
                    $('#validate-password').addClass('d-block').text(errors.password);
                }
            }

        </script>

        <script>

            function resetCreateEmployeeModal() {

                $('#create-employee-form-text').text('Create Employee');
                $('#employee_id').val();
                $('#employee-form')[0].reset();
                removeValidations();
                $('#parent_user_select_box').hide();
            }

        </script>

        <script>

            function deleteRecord(id) {

                $('#inputDeleteID').val(id);
                $('#delete_heading').text('You want to delete this employee?');
                $('#deleteModal').modal('show');

                $('#deleteForm').on('submit', function () {

                    $('#deleteBtn').addClass('disabled');

                    $.ajax({
                        'url': '{{ route('employees.destroy') }}',
                        'type': 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },

                        success: function (response) {
                            if (response.success) {

                                $('#deleteModal').modal('hide');
                                $('#showResponseMsg').text(response.message);
                                $('#successModal').modal('show');

                                setTimeout(function () {
                                    window.location = '{{ route('employees') }}';
                                }, 1000);
                            } else {

                                $('#showErrorResponseMsg').text(response.message);
                                $('#errorModal').modal('show');

                                $('#deleteBtn').removeClass('disabled');
                            }
                        }
                    })
                })
            }

        </script>

    @endpush

@endsection
