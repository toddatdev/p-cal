@extends('admin.layouts.app')
@section('title', 'Project Detail')

@section('content')

    <div class="projects-details">
      <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="fw-bold">
              <!--
            <a href="{{route('projects')}}" class="text-secondary text-decoration-none me-2"><i
                    class="fa fa-arrow-left"></i></a>
                    -->
              {{$project->job_title}} </h4>
          @if(empty($loggedInUser->role_id) && $project->status == 0 || strtolower($loggedInUser['role']->name) == 'hod' && $project->status == 0 || strtolower($loggedInUser['role']->name) == 'manager' && $project->status == 0)
              <a href="javascript:void(0)"
                 data-bs-toggle="modal"
                 class="btn btn-soft-primary mb-2 mb-lg-0 rounded-pill px-3"
                 onclick="completeProject('{{ $project['id'] }}')"
              >Complete Project</a>
          @endif
      </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Client Name</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->client_name}}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Start Date</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->start_date}}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">End Date</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->end_date}}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Hourly Rate</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->hourly_rate}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Sales Person</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->sale->name}}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Type</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->type->name}}</h5>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Platform</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{$project->platform->name}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-lg-3">
                        <div class="row">
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod')
                                <div class="col-md-4 mb-2">
                                    <div>
                                        <p class="fs-16 text-light-400 mb-1 fw-normal">Commission HOD %</p>
                                        <h5 class="fw-600 mb-0 fs-16 commission_percentage_hod">{{$project['commission']->commission_percentage_hod}}
                                            %</h5>
                                    </div>
                                </div>
                            @endif
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                <div class="col-md-4 mb-2">
                                    <div>
                                        <p class="fs-16 text-light-400 mb-1 fw-normal">Commission Manager %</p>
                                        <h5 class="fw-600 mb-0 fs-16 commission_percentage_manager">{{$project['commission']->commission_percentage_manager}}
                                            %</h5>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission Employee %</p>
                                    <h5 class="fw-600 mb-0 fs-16 commission_percentage_employee">{{$project['commission']->commission_percentage_employee}}%</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-lg-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Total Earning</p>
                                    <h5 class="fw-600 mb-0 fs-16">{{ $totalEarning }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-md-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-3 mb-md-0">Monthly Earning</h6>
                    <div>
                        @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                            <a href="javascript:void(0)" id="edit_commission"
                               class="btn btn-outline-primary mb-2 fs-14 mb-lg-0 rounded-pill px-3"
                            >Edit Commission</a>

                        <a href="javascript:void(0)"
                           onclick="stopEarningModalBtn()"
                           class="btn btn-outline-primary mb-2 fs-14 mb-lg-0 rounded-pill px-3 mx-1 mx-md-3"
                        >Stop Earning</a>

                            <a href="javascript:void(0)"
                               data-bs-toggle="modal"
                               class="btn btn-soft-primary mb-2 mb-lg-0 rounded-pill px-3"
                               onclick="createProjectEarning()"
                            >Add Earning</a>
                        @endif
                    </div>
                </div>

                <!--  Add Earning Modal -->
                <div class="modal fade" id="addEarningModal" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h6 class="modal-title text-primary fw-600" id="createProjectEarningModalLabel">Create
                                    Project Earning</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <form id="createEarningForm" onsubmit="return false;">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="hidden" id="earning_id" name="earning_id">

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">
                                                    Earnings in Dollar<span
                                                        class="text-danger ">*</span>
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="earning"
                                                       id="earning"
                                                       placeholder="Earning"
                                                >

                                            </fieldset>
                                            <div id="validate-earning" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Month
                                                    <span
                                                        class="text-danger ">*</span></legend>
                                                <select
                                                    name="month"
                                                    id="month"
                                                    class="form-control form-control-lg month border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                                                </select>

                                            </fieldset>
                                            <div id="validate-month" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Year <span
                                                        class="text-danger ">*</span></legend>
                                                <select
                                                    name="year"
                                                    id="year"
                                                    class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light"
                                                >
                                                </select>

                                            </fieldset>
                                            <div id="validate-year" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">
                                                    Conversion<span
                                                        class="text-danger ">*</span>
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="exg_rate"
                                                       id="exg_rate"
                                                       placeholder="Dollar Rate"
                                                >

                                            </fieldset>
                                            <div id="validate-exg_rate" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-3 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Currency
                                                    <span
                                                        class="text-danger ">*</span></legend>
                                                <select
                                                    name="currency"
                                                    id="currency"
                                                    class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                                                    <option value="pkr">PKR</option>
                                                    <option value="inr">INR</option>
                                                    <option value="rng">RNG</option>
                                                    <option value="tka">TKA</option>
                                                </select>

                                            </fieldset>
                                            <div id="validate-currency" class="invalid-feedback"></div>

                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14"
                                            data-bs-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit"
                                            id="saveEarningBtn"
                                            class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14 ">
                                        Save
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!--  Stop Earning Modal -->
                <div class="modal fade" id="stopEarningModal" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h6 class="modal-title text-primary fw-600" id="createProjectEarningModalLabel">Stop
                                    Earning</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close" onclick="unselectProjectUsers()"></button>
                            </div>
                            <form id="stop_earning_form" onsubmit="return false;">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Month
                                                    <span
                                                        class="text-danger ">*</span></legend>
                                                <select
                                                    name="month"
                                                    id="stop_earning_month"
                                                    class="form-control form-control-lg month border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                                                @foreach($months as $month)
                                                    <option value="{{ $month }}">{{ $month }}</option>
                                                @endforeach
                                                </select>

                                            </fieldset>
                                            <div id="validate-stop-earning-month" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">

                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Employees
                                                    <span
                                                        class="text-danger ">*</span></legend>
                                                <div class="dropdown w-100" id="select_employee_dropdown_section">
                                                    <a class="btn bg-transparent w-100 border-0
                                                    dropdown-toggle-select-multiple text-start py-9" href="#"
                                                       role="button" id="dropdownMenuLink" data-bs-toggle="dropdown"
                                                       aria-expanded="false" data-bs-auto-close="false">
                                                        Select Employee
                                                    </a>

                                                    <ul class="dropdown-menu selectStopEarningUser w-100 p-3 border-0 shadow"
                                                        aria-labelledby="dropdownMenuLink" id="stopEarningYear">

                                                    </ul>
                                                </div>

                                            </fieldset>
                                            <div id="validate-project-user" class="invalid-feedback"></div>

                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14"
                                            data-bs-dismiss="modal" onclick="unselectProjectUsers()">Cancel
                                    </button>
                                    <button type="submit"
                                            id="stop_earning_btn"
                                            class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14 ">
                                        Continue
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!--  Edit Commission Modal -->
                <div class="modal fade" id="editCommissionModal" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h6 class="modal-title text-primary fw-600" id="createProjectEarningModalLabel">Edit Commission</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <form id="editCommissionForm" onsubmit="return false;">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">
                                                    Commission%Employee
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="commission_percentage_employee"
                                                       id="commission_percentage_employee"
                                                       placeholder="0"
                                                >

                                            </fieldset>
                                            <div id="validate-commission_percentage_employee"
                                                 class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">
                                                    Commission%Manager
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="commission_percentage_manager"
                                                       id="commission_percentage_manager"
                                                       placeholder="0"
                                                >

                                            </fieldset>
                                            <div id="validate-commission_percentage_manager"
                                                 class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">
                                                    Commission%HOD
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="commission_percentage_hod"
                                                       id="commission_percentage_hod"
                                                       placeholder="0"
                                                >
                                            </fieldset>
                                            <div id="validate-commission_percentage_hod" class="invalid-feedback"></div>
                                            <p class="mt-2 text-light-400">If no Commission, Add Zero</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14"
                                            data-bs-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit"
                                            id="saveCommissionBtn"
                                            class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14 ">
                                        Save
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="table-responsive ">
                    <table class="table h-table projects-table ">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">Month</th>
                            <th scope="col">Year</th>
                            <th scope="col">Total Earning</th>
                            <th scope="col">Employee's Commission</th>
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                <th scope="col">Manager's Commission</th>
                            @endif
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod')
                                <th scope="col">Hod's Commission</th>
                            @endif
                            <th scope="col">Dollar Rate</th>
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($earnings) && count($earnings) > 0)
                            @foreach($earnings as $earning)
                                <tr>
                                    <td>{{$earning->month}}</td>
                                    <td>{{ $earning->year }}</td>
                                    <td>${{ number_format($earning->earning , 2) }}</td>
                                    <td>{{ '$'.number_format($earning->employee_commission , 2).' / '.number_format($earning->employee_commission_by_exg_rate, 2).' '.strtoupper($earning->currency) }}</td>
                                    @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                        <td>{{ '$'.number_format($earning->manager_commission , 2).' / '.number_format($earning->manager_commission_by_exg_rate, 2).' '.strtoupper($earning->currency) }}</td>
                                    @endif
                                    @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod')
                                        <td>{{ '$'.number_format($earning->hod_commission , 2).' / '.number_format($earning->hod_commission_by_exg_rate, 2).' '.strtoupper($earning->currency) }}</td>
                                    @endif
                                    <td>{{ $earning->exg_rate .' '. strtoupper($earning->currency) }}</td>
                                    <td>
                                        @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                            <div class="d-flex justify-content-start align-items-center">
                                                <a href="javascript:void(0)" class="me-2 text-decoration-none"
                                                   onclick="editProjectEarning('{{ $earning['id'] }}')"
                                                >
                                                    <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid"
                                                         alt="">
                                                </a>
                                                <a href="javascript:void(0)" class="text-decoration-none"
                                                   onclick="deleteEarning('{{ $earning['id'] }}')"
                                                >
                                                    <img src="{{asset('assets/icons/delete-icon.svg')}}"
                                                         class="img-fluid"
                                                         alt="">
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="fw-bold">Total</td>
                                <td></td>
                                <td class="fw-bold">{{ $totalEarning }}</td>
                                <td class="fw-bold">{{ $employeeCommissionTotal }}</td>
                                @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                    <td class="fw-bold">{{ $managerCommissionTotal }}</td>
                                @endif
                                @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod')
                                    <td class="fw-bold">{{ $hodCommissionTotal }}</td>
                                @endif
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if(isset($earnings) && count($earnings) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif
                </div>

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

            let active_user = '{{ checkAuthUserRole() }}';


        </script>

        <script>

            function stopEarningModalBtn() {

                let months = [];
                months = {!! json_encode($months) !!};
                if (months.length > 0) {

                    $('#stopEarningModal').modal('show');
                }
                else {

                    $('#showErrorResponseMsg').text('We\'re sorry, but there are no selectable months available for this project. The end date falls before the current date. Please adjust the project end date accordingly.');
                    $('#errorModal').modal('show');
                }

            }

        </script>

        <script>

            $(document).mouseup(function (e) {

                var container = $('#select_employee_dropdown_section');

                if (!container.is(e.target) && container.has(e.target).length === 0) {

                    $('#stopEarningYear').removeClass('show');
                }
            })

        </script>

        <script>

            function completeProject(project_id){

                $.ajax({
                    url: '{{ route('project.complete_project') }}',
                    type: 'POST',
                    data: {
                        project_id: project_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {

                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');

                            setTimeout(function () {
                                window.location = '{{route('project.details',base64_encode($project->id))}}';
                            }, 1500);
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

            $(function () {
                $('#stop_earning_month').change();
            });

            $('#stop_earning_month').on('change', function () {

                let month = $('#stop_earning_month').val();
                let project_id = '{{ $project['id'] }}';

                $.ajax({
                    url: '{{ route('projects.project_details.users_list_for_stop_earning') }}',
                    type: 'POST',
                    data: {
                        month: month,
                        project_id: project_id,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {

                        let project_users = response.projectUsers;

                        $('#stopEarningYear').empty();
                        $.each(project_users, function(index, value) {

                            $('#stopEarningYear').append(
                                `
                                    <div class="form-check">
                                        <input class="form-check-input cb-lg selectUser" name="project_users[]"
                                           type="checkbox" value="`+value.user.id+`" id="flexCheckDefault">
                                            <label class="form-check-label mt-1 ms-2" for="flexCheckDefault">
                                                `+value.user.first_name+` `+value.user.last_name+`
                                            </label>
                                    </div>
                                `
                            )
                        });
                    }
                })
            })

            $('#stop_earning_form').on('submit', function () {

                if (active_user === '1')
                    $('#deleteBtn').text('Yes');
                else
                    $('#deleteBtn').text('Send For Approval');

                $('#stopEarningModal').modal('hide');
                $('#delete_heading').text('You want to stop the earning?');
                $('#deleteModal').modal('show');
                $('#deleteCancelBtn').attr('onclick', 'unselectProjectUsers()');
                $('#deleteBtn').attr('onclick', 'submitStopEarning()');
            })

            function unselectProjectUsers() {

                $('input[name="project_users[]"]').prop('checked', false);
            }

            function submitStopEarning() {

                $('#validate-project-user').removeClass('d-block');
                $('#validate-stop-earning-month').removeClass('d-block');

                let project_id = '{{ $project['id'] }}';
                let month = $('#stop_earning_month').val();
                let project_users = $("input[name='project_users[]']:checked").map(function () {
                    return $(this).val();
                }).get();
                let formError = false;


                if (project_users.length === 0) {
                    $('#validate-project-user').addClass('d-block').text('Select at least one user.');
                    formError = true;
                }

                if (!month) {
                    $('#validate-stop-earning-month').addClass('d-block').text('Month field is required.');
                    formError = true;
                }

                if (formError) {
                    return false;
                }

                $('#stop_earning_btn').addClass('disabled');

                $.ajax({
                    url: '{{ route('projects.project_details.stop_earning') }}',
                    type: 'POST',
                    data: {
                        project_id: project_id,
                        month: month,
                        project_users: project_users,
                        active_user: active_user,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.success) {

                            $('#deleteModal').modal('hide');

                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');

                            setTimeout(function () {

                                $('#successModal').modal('hide');
                            }, 2000);

                            $('#stop_earning_btn').removeClass('disabled');
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

            $('#edit_commission').on('click', function () {

                $('#'+this.id).addClass('disabled');

                let project_id = '{{ $project['id'] }}';

                $.ajax({
                    url: '{{ route('projects.project_details.get_commission') }}',
                    type: 'POST',
                    data: {
                        project_id: project_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {

                        if (response.success) {

                            let commission = response.commission;
                            $('#commission_percentage_employee').val(commission.commission_percentage_employee);
                            $('#commission_percentage_manager').val(commission.commission_percentage_manager);
                            $('#commission_percentage_hod').val(commission.commission_percentage_hod);
                        }
                        else {

                            $('#showErrorResponseMsg').text(response.message);
                            $('#errorModal').modal('show');
                        }

                    }
                })

                $('#'+this.id).removeClass('disabled');

                if (active_user === '1')
                    $('#saveCommissionBtn').text('Save');
                else
                    $('#saveCommissionBtn').text('Send For Approval');

                $('#editCommissionModal').modal('show');
            });

            $('#editCommissionForm').on('submit', function () {

                let project_id = '{{ $project['id'] }}';
                let commission_employee = $('#commission_percentage_employee').val();
                let commission_manager = $('#commission_percentage_manager').val();
                let commission_hod = $('#commission_percentage_hod').val();

                $('#saveCommissionBtn').addClass('disabled');

                $.ajax({
                    url: '{{ route('projects.project_details.edit_commission') }}',
                    type: 'POST',
                    data: {

                        active_user: active_user,
                        project_id: project_id,
                        commission_percentage_employee: commission_employee,
                        commission_percentage_manager: commission_manager,
                        commission_percentage_hod: commission_hod,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {

                        if (response.success) {

                            $('#editCommissionModal').modal('hide');

                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');

                            setTimeout(function () {
                                if (active_user === '1') {

                                    $('.commission_percentage_employee').html(commission_employee+'%');
                                    $('.commission_percentage_manager').html(commission_manager+'%');
                                    $('.commission_percentage_hod').html(commission_hod+'%');
                                }

                                $('#successModal').modal('hide');
                            }, 2000);
                        } else {

                            if (response.errors) {

                                if (response.errors.commission_percentage_employee) {

                                    $('#commission_percentage_employee').addClass('is-invalid');
                                    $('#validate-commission_percentage_employee').addClass('d-block').text(response.errors.commission_percentage_employee);
                                }

                                if (response.errors.commission_percentage_manager) {

                                    $('#commission_percentage_manager').addClass('is-invalid');
                                    $('#validate-commission_percentage_manager').addClass('d-block').text(response.errors.commission_percentage_manager);
                                }

                                if (response.errors.commission_percentage_hod) {

                                    $('#commission_percentage_hod').addClass('is-invalid');
                                    $('#validate-commission_percentage_hod').addClass('d-block').text(response.errors.commission_percentage_hod);
                                }
                            } else {

                                $('#showErrorResponseMsg').text(response.message);
                                $('#errorModal').modal('show');
                            }

                        }

                        $('#saveCommissionBtn').removeClass('disabled');
                    }
                })
            });

        </script>

        <script>

            let start_date = new Date('{{ $project->start_date }}');
            let start_date_month_name = start_date.toLocaleString('default', {month: 'long'});
            let start_date_year = start_date.getFullYear();
            let end_date = new Date('{{ $project->end_date }}');
            let end_date_month_name = end_date.toLocaleString('default', {month: 'long'});
            let end_date_year = end_date.getFullYear();
        </script>

        {{--Year--}}
        <script>

            $(document).ready(function () {

                // Get the current year
                var currentYear = new Date().getFullYear();

                // Set the range of years
                var startYear = start_date_year;
                var endYear = end_date_year;

                // Get the year dropdown element
                var yearDropdown = document.getElementById('year');

                // Generate the options for the dropdown
                for (var year = startYear; year <= endYear; year++) {
                    var option = document.createElement('option');
                    option.value = year;
                    option.text = year;
                    // Set the default selected option
                    if (year === currentYear) {
                        option.setAttribute('selected', 'selected');
                    }

                    yearDropdown.appendChild(option);
                }

                getMonthsList();
            });


        </script>

        {{--Month--}}

        <script>
            let hasMonthsInAddEarning = false;

            function getMonthsList() {
                var currentMonth = new Date().getMonth();
                var months_earning = [];

                var dropdownyear = $('#year').val();

                <?php if(count($earningMonths) > 0){ ?>
                let monthyear = null;
                    <?php foreach($earningMonths as $key => $val){ ?>
                    monthyear = '{{ $val['year'] }}';
                if(monthyear === dropdownyear){
                    months_earning.push('<?php echo $val['month']; ?>');
                }
                <?php } ?>
                <?php } ?>

                var months = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ];

                var monthNames = months.filter(function(n) {
                    return !this.has(n);
                }, new Set(months_earning));

                var yearDropdown = $('#year').val();

                var startIndex = 0;
                var endIndex = months.length;

                if (yearDropdown == start_date_year) {
                    var startMonthIndex = months.indexOf(start_date_month_name);
                    if (startMonthIndex !== -1) {
                        startIndex = startMonthIndex;
                    }
                }

                if (yearDropdown == end_date_year) {
                    var endMonthIndex = months.indexOf(end_date_month_name);
                    if (endMonthIndex !== -1) {
                        endIndex = endMonthIndex + 1;
                    }
                }

                monthNames = months.slice(startIndex, endIndex);

                // Remove months from monthNames that are in the $earningMonths variable for the selected year
                if (months_earning.length > 0) {
                    monthNames = monthNames.filter(function(month) {
                        return !months_earning.includes(month);
                    });
                }

                $('#month').empty();

                var monthDropdown = document.getElementById('month');

                for (var i = 0; i < monthNames.length; i++) {
                    var option = document.createElement('option');
                    option.value = monthNames[i];
                    option.text = monthNames[i];

                    if (i === currentMonth) {
                        option.setAttribute('selected', 'selected');
                    }

                    monthDropdown.appendChild(option);
                }

                // Remove selected year if no months to show
                if (monthNames.length === 0) {
                    $('#year option:selected').remove();

                    // Check if there are any remaining years
                    if ($('#year option').length === 0) {
                        hasMonthsInAddEarning = true;
                    } else {
                        // Auto-select next year if available
                        var nextYearIndex = $('#year option').index($('#year option:selected')) + 1;
                        if (nextYearIndex < $('#year option').length) {
                            $('#year').prop('selectedIndex', nextYearIndex);
                        }

                        // Trigger change event to update the month dropdown
                        $('#year').trigger('change');
                    }
                }
            }

            // Trigger getMonthsList on year dropdown change
            $('#year').on('change', getMonthsList);

        </script>

        <script>

            function createProjectEarning() {
                $('#createProjectEarningModalLabel').text('Create Earning');

                if (active_user === '1')
                    $('#saveEarningBtn').text('Save');
                else
                    $('#saveEarningBtn').text('Send For Approval');

                if (!hasMonthsInAddEarning) {

                    $('#addEarningModal').modal('show');
                }
                else {

                    $('#showErrorResponseMsg').text('We\'re sorry, but there are no selectable months available for this project. You have already added earnings for all the months.');
                    $('#errorModal').modal('show');
                }

                removeEarningFormValidations();
                $('#createEarningForm')[0].reset();
            }

        </script>

        <script>

            function removeEarningFormValidations() {
                $('#earning').removeClass('is-invalid');
                $('#month').removeClass('is-invalid');
                $('#year').removeClass('is-invalid');
                $('#exg_rate').removeClass('is-invalid');
                $('#currency').removeClass('is-invalid');

                $('#validate-earning').removeClass('d-block');
                $('#validate-month').removeClass('d-block');
                $('#validate-year').removeClass('d-block');
                $('#validate-exg_rate').removeClass('d-block');
                $('#validate-currency').removeClass('d-block');
            }

            function showControllerReturnedValidations(errors) {

                if (errors.earning) {
                    $('#earning').addClass('is-invalid');
                    $('#validate-earning').addClass('d-block').text(errors.earning);
                }
                if (errors.month) {
                    $('#month').addClass('is-invalid');
                    $('#validate-month').addClass('d-block').text(errors.month);
                }
                if (errors.year) {
                    $('#year').addClass('is-invalid');
                    $('#validate-year').addClass('d-block').text(errors.year);
                }
                if (errors.exg_rate) {
                    $('#exg_rate').addClass('is-invalid');
                    $('#validate-exg_rate').addClass('d-block').text(errors.exg_rate);
                }
            }


        </script>

        <script>

            function editProjectEarning(id) {

                $('#createProjectEarningModalLabel').text('Edit Earning');

                if (active_user === '1')
                    $('#saveEarningBtn').text('Update');
                else
                    $('#saveEarningBtn').text('Send For Approval');

                removeEarningFormValidations();
                $('#createEarningForm')[0].reset();

                $.ajax({
                    'url': '{{ route('project.earning.edit') }}',
                    'type': 'GET',
                    'data': {
                        earning_id: id
                    },
                    success: function (response) {

                        if (response.success) {

                            let earning = response.earning;

                            $('#earning_id').val(earning.id);
                            $('#earning').val(earning.earning);
                            $('#month').val(earning.month);
                            $('#year').val(earning.year);
                            $('#exg_rate').val(earning.exg_rate);
                            $('#currency').val(earning.currency);
                            $('#addEarningModal').modal('show');

                        } else {
                            $('#showErrorResponseMsg').text(response.message);
                            $('#errorModal').modal('show');
                        }
                    }
                })
            }

        </script>


        <script>
            $('#createEarningForm').on('submit', function () {

                removeEarningFormValidations();

                let earning_id = $('#earning_id').val();
                let earning = $('#earning').val();
                let month = $('#month').val();
                let year = $('#year').val();
                let exg_rate = $('#exg_rate').val();
                let currency = $('#currency').val();


                let formError = false;

                if (!earning) {

                    $('#earning').addClass('is-invalid');
                    $('#validate-earning').addClass('d-block').text('Earning field is required.');
                    formError = true;
                }
                if (!month) {

                    $('#month').addClass('is-invalid');
                    $('#validate-month').addClass('d-block').text('Please select month.');
                    formError = true;
                }
                if (!year) {

                    $('#year').addClass('is-invalid');
                    $('#validate-year').addClass('d-block').text('Please select year.');
                    formError = true;
                }
                if (!exg_rate) {

                    $('#exg_rate').addClass('is-invalid');
                    $('#validate-exg_rate').addClass('d-block').text('Dollar rate field is required.');
                    formError = true;
                }
                if (!currency) {

                    $('#currency').addClass('is-invalid');
                    $('#validate-currency').addClass('d-block').text('Dollar rate field is required.');
                    formError = true;
                }

                if (formError) {

                    return false;
                }

                $('#saveEarningBtn').addClass('disabled');

                $.ajax({
                    'url': '{{ route('project.earning.store') }}',
                    'type': 'POST',
                    data: {
                        earning_id: earning_id,
                        project_id: {{$project->id}},
                        earning: earning,
                        month: month,
                        year: year,
                        exg_rate: exg_rate,
                        currency: currency,
                        active_user: active_user,
                        _token: '{{ csrf_token() }}'
                    },

                    success: function (response) {
                        if (response.success) {

                            $('#addEarningModal').modal('hide');

                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');
                            setTimeout(function () {

                                if (active_user === '1') {

                                    window.location = '{{route('project.details',base64_encode($project->id))}}';
                                }
                                else {

                                    $('#successModal').modal('hide');
                                    $('#saveEarningBtn').removeClass('disabled');
                                }


                            }, 2000);
                        } else {

                            if (response.errors) {

                                showControllerReturnedValidations(response.errors)
                            } else {

                                $('#showErrorResponseMsg').text(response.message);
                                $('#errorModal').modal('show');
                            }

                            $('#saveEarningBtn').removeClass('disabled');
                        }
                    }
                })
            })
        </script>

        <script>

            function deleteEarning(id) {

                $('#inputDeleteID').val(id);
                $('#delete_heading').text('You want to delete this project Earning?');
                $('#deleteModal').modal('show');
                $('#deleteBtn').text('Send For Approval');

                $('#deleteForm').on('submit', function () {

                    $('#deleteBtn').addClass('disabled');

                    $.ajax({
                        'url': '{{ route('project.earning.destroy') }}',
                        'type': 'POST',
                        data: {
                            earning_id: id,
                            active_user: active_user,
                            _token: '{{ csrf_token() }}'
                        },

                        success: function (response) {
                            if (response.success) {

                                $('#deleteModal').modal('hide');
                                $('#showResponseMsg').text(response.message);
                                $('#successModal').modal('show');

                                setTimeout(function () {

                                    if (active_user === '1')
                                        window.location = '{{route('project.details',base64_encode($project->id))}}';
                                    else
                                        $('#successModal').modal('hide');
                                }, 1500);
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
