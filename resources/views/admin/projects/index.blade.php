@extends('admin.layouts.app')
@section('title', 'Projects')
@section('content')

    <div class="roles">
        <h4 class="fw-bold mb-3">Projects</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Projects & Commissions</h6>
                    <div class="d-flex text-end">
                        <div class="dropdown me-2">
                            <button type="button" class="btn btn-white border dropdown-toggle" id="dropdownMenu"
                                    data-bs-toggle="dropdown" aria-expanded="false" data-offset="10,20">
                                Properties: {{ $requestStatus ? $requestStatus : 'All' }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu">
                                <a href="{{route('projects')}}"
                                   class="dropdown-item"
                                >All</a>
                                <a href="{{route('projects', ['status' => 'completed'])}}"
                                   class="dropdown-item"
                                >Completed</a>
                                <a href="{{route('projects', ['status' => 'in-progress'])}}"
                                   class="dropdown-item"
                                >In Progress</a>
                            </div>
                        </div>
                        <div>
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                <a href="#!"
                                   data-bs-toggle="modal" data-bs-target="#createProjectModal"
                                   class="btn btn-soft-primary rounded-pill px-3" onclick="createProject()"
                                >Create Project</a>
                            @endif
                        </div>
                    </div>
                </div>

                <!--  Project Create Modal -->
                <div class="modal fade" id="createProjectModal" data-bs-backdrop="static" data-bs-keyboard="false"
                     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h6 class="modal-title text-primary fw-600" id="createProjectModalLabel">Create Project</h6>
                                {{--                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                            </div>

                            <form id="createProjectForm" onsubmit="return false;">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">

                                        <input type="hidden" name="project_id" id="project_id"/>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Client
                                                    Name
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="client_name"
                                                       id="client_name"
                                                       placeholder="Client Name"
                                                >

                                            </fieldset>
                                            <div id="validate-client_name" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Project Name
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="job_title"
                                                       id="job_title"
                                                       placeholder="Project Name"
                                                >

                                            </fieldset>
                                            <div id="validate-job_title" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Start Date
                                                </legend>
                                                <input type="date"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="start_date"
                                                       id="start_date"
                                                       placeholder="Start Date"
                                                >

                                            </fieldset>
                                            <div id="validate-start_date" class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">End Date
                                                </legend>
                                                <input type="date"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="end_date"
                                                       id="end_date"
                                                       placeholder="Start Date"
                                                >

                                            </fieldset>
                                            <div id="validate-end_date" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Hourly
                                                    Rate
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="hourly_rate"
                                                       id="hourly_rate"
                                                       placeholder="0"
                                                >

                                            </fieldset>
                                            <div id="validate-hourly_rate" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Sales
                                                    Person
                                                </legend>
                                                <select tabindex="2" name="sales_person" id="sales_person"
                                                        class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                                                    <option value="">Select Sales Person</option>
                                                    @if(isset($salesPersons) && count($salesPersons) > 0)
                                                        @foreach($salesPersons as $sp)
                                                            <option value="{{$sp->id}}">{{$sp->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </fieldset>
                                            <div id="validate-sales_person" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Type
                                                </legend>
                                                <select tabindex="2"
                                                        name="type"
                                                        id="type"
                                                        class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                                                    <option value="">Select Type</option>
                                                    @if(isset($types) && count($types) > 0)
                                                        @foreach($types as $t)
                                                            <option value="{{$t->id}}">{{$t->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </fieldset>
                                            <div id="validate-type" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Platform
                                                </legend>
                                                <select tabindex="2"
                                                        name="platform"
                                                        id="platform"
                                                        class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                                                    <option value="">Select Platform</option>
                                                    @if(isset($platforms) && count($platforms) > 0)
                                                        @foreach($platforms as $p)
                                                            <option value="{{$p->id}}">{{$p->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </fieldset>
                                            <div id="validate-platform" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3" id="employee_dropdown">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Employee
                                                </legend>
                                                <select tabindex="2"
                                                        name="user_id[]"
                                                        id="user_id"
                                                        class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light" data-validate-id="user" onchange="parentEmployeeDropDown(this.value, 'employee_dropdown')">
                                                    <option value="">Select Employee</option>
                                                    @if(isset($users) && count($users) > 0)
                                                        @foreach($users as $user)
                                                            <option
                                                                value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>

                                            </fieldset>
                                            <div id="validate-user" class="invalid-feedback"></div>

                                        </div>

                                        <div class="col-lg-6 mb-3" id="employee_dropdown_end">
                                            <fieldset class="input-group border rounded-1 ps-1">
                                                <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">
                                                    Commission % Employee
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
                                                    Commission % Manager
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
                                                    Commission % HOD
                                                </legend>
                                                <input type="text"
                                                       class="form-control form-control-lg border-0 bg-transparent fs-14 outline-none ph-light"
                                                       name="commission_percentage_hod"
                                                       id="commission_percentage_hod"
                                                       placeholder="0"
                                                >

                                            </fieldset>
                                            <div id="validate-commission_percentage_hod" class="invalid-feedback"></div>

                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14"
                                            data-bs-dismiss="modal">Cancel
                                    </button>
                                    <button type="submit"
                                            class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14 "
                                            id="saveProjectBtn"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>


                <div class="table-responsive ">
                    <table class="table h-table projects-table">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">Serial No</th>
                            <th scope="col">Client Name</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Employee</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Total Earning</th>
                            <th scope="col">Hourly Rate</th>
                            <th scope="col">Sales Person</th>
                            <th scope="col">Type</th>
                            <th scope="col">Platform</th>
                            <th scope="col">Commission Employee %</th>
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                <th scope="col">Commission Manager %</th>
                            @endif
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod')
                                <th scope="col">Commission Hod %</th>
                            @endif
                            @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                <th scope="col">Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>

                        @if(isset($projects) && count($projects) > 0)
                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->serialNumber }}.</td>
                                    <td>{{$project->client_name}}</td>
                                    <td>
                                        <a
                                             href="{{route('project.details',base64_encode($project->id))}}"
                                            class="text-secondary">{{$project->job_title}}</a>
                                    </td>
                                    <td>{{$project->projectEmployee()->first_name}} {{$project->projectEmployee()->last_name}}</td>
                                    <td>{{$project->start_date}}</td>
                                    <td>{{$project->end_date}}</td>
                                    <td>{{$project->total_earning }}</td>
                                    <td>{{$project->hourly_rate}}</td>
                                    <td>{{$project->sale->name}}</td>
                                    <td>{{$project->type->name}}</td>
                                    <td>{{$project->platform->name}}</td>

                                    <td class="text-center">{{$project['commission']->commission_percentage_employee}}%</td>
                                    @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                        <td class="text-center">{{$project['commission']->commission_percentage_manager}}%</td>
                                    @endif
                                    @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod')
                                        <td class="text-center">{{$project['commission']->commission_percentage_hod}}%</td>
                                    @endif
                                    @if(empty($loggedInUser->role_id) || strtolower($loggedInUser['role']->name) == 'hod' || strtolower($loggedInUser['role']->name) == 'manager')
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center">
                                                <a href="javascript:void(0)"
                                                   onclick="editProject('{{ $project['id'] }}')"
                                                   class="me-2 text-decoration-none">
                                                    <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid"
                                                         alt="">
                                                </a>
                                                <a href="javascript:void(0)"
                                                   onclick="deleteProject('{{ $project['id'] }}')"
                                                   class="text-decoration-none">
                                                    <img src="{{asset('assets/icons/delete-icon.svg')}}"
                                                         class="img-fluid" alt="">
                                                </a>
                                            </div>
                                        </td>
                                    @endif
                                </tr>

                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(isset($projects) && count($projects) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-7 align-self-center">

                @if ($projects->hasMorePages())
{{--               <div class="d-flex">--}}
{{--                   {!! $projects->links() !!}--}}
{{--               </div>--}}
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($projects->onFirstPage())
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
                                <a class="page-link" href="{{ $projects->url(1) }}" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $projects->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        @if ($projects->currentPage() > 1)
                            <li class="page-item"><a class="page-link" href="{{ $projects->url($projects->currentPage() - 1) }}">{{ $projects->currentPage() - 1 }}</a></li>
                        @endif

                        <li class="page-item active">
                            <a class="page-link" href="#">{{ $projects->currentPage() }}</a>
                        </li>

                        @if ($projects->hasMorePages())
                            <li class="page-item"><a class="page-link" href="{{ $projects->nextPageUrl() }}">{{ $projects->currentPage() + 1 }}</a></li>
                        @endif

                        @if ($projects->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $projects->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $projects->url($projects->lastPage()) }}" aria-label="Last">
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
            <!--
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-md-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-500 w-100 mb-3 mb-lg-0">Commission this Month:</h5>
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <p class="mb-0">In USD's</p>
                                    <h3 class="mb-0 fw-600">$150</h3>
                                </div>
                                <div>
                                    <p class="mb-0">In PKR</p>
                                    <h3 class="mb-0 fw-600">28,750</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
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

            var project_id = '';
            var active_user = '{{ $activeUser }}';

            function parentEmployeeDropDown(user_id, current_employee_dropdown_div_id) {

                if (!user_id)
                    return false;

                $.ajax({
                    url: '{{ route('projects.parent_users') }}',
                    type: 'GET',
                    data: {
                        user_id: user_id,
                        project_id: project_id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {

                        if (response.success) {

                            $('#'+current_employee_dropdown_div_id).nextUntil('#employee_dropdown_end').remove();
                            $('#'+current_employee_dropdown_div_id).after(
                                `<div class="col-lg-6 mb-3" id="${response.parentRoleName}_dropdown">
                                    <fieldset class="input-group border rounded-1 ps-1">
                                        <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">${response.parentRoleName}</legend>
                                            <select tabindex="2"
                                                    name="user_id[]"
                                                    id="${String(response.parentRoleName).toLowerCase()}"
                                                    class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light" data-validate-id="${String(response.parentRoleName).toLowerCase()}" onchange="parentEmployeeDropDown(this.value, '${response.parentRoleName}_dropdown')">
                                                <option value="">Select ${response.parentRoleName}</option>
                                                    ${response.parentRoleUsersList.map(function (value) {
                                                        return `<option value="${value.id}" ${value.id === response.currentParentUser ? 'selected' : ''}>${value.first_name} ${value.last_name}</option>`;
                                                    }).join('')}
                                            </select>
                                    </fieldset>
                                    <div id="validate-${String(response.parentRoleName).toLowerCase()}" class="invalid-feedback"></div>
                                </div>`
                            )

                            if (response.parentUserParent) {
                                parentEmployeeDropDown(response.parentUserParent.id, response.parentRoleName+'_dropdown')
                            }
                        }
                    }
                })
            }

        </script>

        <script>

            function createProject() {

                $('#createProjectModalLabel').text('Create Project');
                project_id = null;

                if (active_user === '1')
                    $('#saveProjectBtn').text('Save');
                else
                    $('#saveProjectBtn').text('Send For Approval');

                removeProjectFormValidations();
                $('#createProjectForm')[0].reset();
            }

        </script>

        <script>

            function editProject(id) {


                $('#createProjectModalLabel').text('Edit Project');

                if (active_user === '1')
                    $('#saveProjectBtn').text('Update');
                else
                    $('#saveProjectBtn').text('Send For Approval');

                removeProjectFormValidations();
                $('#createProjectForm')[0].reset();

                $.ajax({
                    'url': '{{ route('project.edit') }}',
                    'type': 'GET',
                    'data': {
                        project_id: id
                    },
                    success: function (response) {

                        if (response.success) {

                            let project = response.project;

                            $('#project_id').val(project.id);
                            $('#client_name').val(project.client_name);
                            $('#job_title').val(project.job_title);
                            $('#start_date').val(project.start_date);
                            $('#end_date').val(project.end_date);
                            $('#hourly_rate').val(project.hourly_rate);
                            $('#sales_person').val(project.sales_person_id);
                            $('#type').val(project.type_id);
                            $('#platform').val(project.platform_id);
                            $('#user_id').val(project.user_id);
                            project_id = project.id;
                            parentEmployeeDropDown(project.user_id, 'employee_dropdown')
                            $('#commission_percentage_employee').val(project.commission.commission_percentage_employee);
                            $('#commission_percentage_manager').val(project.commission.commission_percentage_manager);
                            $('#commission_percentage_hod').val(project.commission.commission_percentage_hod);

                            $('#createProjectModal').modal('show');
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

            function removeProjectFormValidations() {

                $('#client_name').removeClass('is-invalid');
                $('#job_title').removeClass('is-invalid');
                $('#start_date').removeClass('is-invalid');
                $('#end_date').removeClass('is-invalid');
                $('#hourly_rate').removeClass('is-invalid');
                $('#sales_person').removeClass('is-invalid');
                $('#type').removeClass('is-invalid');
                $('#platform').removeClass('is-invalid');
                /*
                $('#commission_percentage_employee').removeClass('is-invalid');
                $('#commission_percentage_manager').removeClass('is-invalid');
                $('#commission_percentage_hod').removeClass('is-invalid');
                 */

                $('#validate-client_name').removeClass('d-block');
                $('#validate-job_title').removeClass('d-block');
                $('#validate-start_date').removeClass('d-block');
                $('#validate-end_date').removeClass('d-block');
                $('#validate-hourly_rate').removeClass('d-block');
                $('#validate-sales_person').removeClass('d-block');
                $('#validate-type').removeClass('d-block');
                $('#validate-platform').removeClass('d-block');
                $('#validate-user_id').removeClass('d-block');
                /*
                $('#validate-commission_percentage_employee').removeClass('d-block');
                $('#validate-commission_percentage_manager').removeClass('d-block');
                $('#validate-commission_percentage_hod').removeClass('d-block');
                 */

                var user_ids_boxes = $('select[name="user_id[]"]');
                user_ids_boxes.each(function(index) {

                    let selectedValues = $(this).val();
                    let validateID = $(this).data('validate-id');
                    $('#'+validateID).removeClass('is-invalid');
                    $('#validate-'+validateID).removeClass('d-block');

                });

            }

            function showControllerReturnedValidations(errors) {

                if (errors.client_name) {

                    $('#client_name').addClass('is-invalid');
                    $('#validate-client_name').addClass('d-block').text(errors.client_name);
                }
                if (errors.job_title) {

                    $('#job_title').addClass('is-invalid');
                    $('#validate-job_title').addClass('d-block').text(errors.job_title);
                }
                if (errors.start_date) {

                    $('#start_date').addClass('is-invalid');
                    $('#validate-start_date').addClass('d-block').text(errors.start_date);
                }
                if (errors.end_date) {

                    $('#end_date').addClass('is-invalid');
                    $('#validate-end_date').addClass('d-block').text(errors.end_date);
                }
                if (errors.hourly_rate) {

                    $('#hourly_rate').addClass('is-invalid');
                    $('#validate-hourly_rate').addClass('d-block').text(errors.hourly_rate);
                }
                if (errors.sales_person) {

                    $('#sales_person').addClass('is-invalid');
                    $('#validate-sales_person').addClass('d-block').text(errors.sales_person);
                }
                if (errors.type) {

                    $('#type').addClass('is-invalid');
                    $('#validate-type').addClass('d-block').text(errors.type);
                }
                if (errors.platform) {

                    $('#platform').addClass('is-invalid');
                    $('#validate-platform').addClass('d-block').text(errors.platform);
                }
                if (errors.user_id) {

                    $('#user_id').addClass('is-invalid');
                    $('#validate-user_id').addClass('d-block').text(errors.user_id);
                }
                if (errors.commission_percentage_employee) {

                    $('#commission_percentage_employee').addClass('is-invalid');
                    $('#validate-commission_percentage_employee').addClass('d-block').text(errors.commission_percentage_employee);
                }

                if (errors.commission_percentage_manager) {

                    $('#commission_percentage_manager').addClass('is-invalid');
                    $('#validate-commission_percentage_manager').addClass('d-block').text(errors.commission_percentage_manager);
                }

                if (errors.commission_percentage_hod) {

                    $('#commission_percentage_hod').addClass('is-invalid');
                    $('#validate-commission_percentage_hod').addClass('d-block').text(errors.commission_percentage_hod);
                }
            }

        </script>

        <script>
            $('#createProjectForm').on('submit', function () {

                removeProjectFormValidations();

                let project_id = $('#project_id').val();
                let clientName = $('#client_name').val();
                let jobTitle = $('#job_title').val();
                let startDate = $('#start_date').val();
                let endDate = $('#end_date').val();
                let hourlyRate = $('#hourly_rate').val();
                let salesPerson = $('#sales_person').val();
                let type = $('#type').val();
                let platform = $('#platform').val();
                var user_ids = [];
                var user_ids_boxes = $('select[name="user_id[]"]');
                user_ids_boxes.each(function() {
                    user_ids = user_ids.concat($(this).val());
                });
                let commissionPercentageEmployee = $('#commission_percentage_employee').val();
                let commissionPercentageManager = $('#commission_percentage_manager').val();
                let commissionPercentageHod = $('#commission_percentage_hod').val();

                let formError = false;

                if (!clientName) {
                    $('#client_name').addClass('is-invalid');
                    $('#validate-client_name').addClass('d-block').text('Client name field is required.');
                    formError = true;
                }
                if (!jobTitle) {
                    $('#job_title').addClass('is-invalid');
                    $('#validate-job_title').addClass('d-block').text('Job title field is required.');
                    formError = true;
                }
                if (!startDate) {
                    $('#start_date').addClass('is-invalid');
                    $('#validate-start_date').addClass('d-block').text('Start Date field is required.');
                    formError = true;
                }
                if (!endDate) {
                    $('#end_date').addClass('is-invalid');
                    $('#validate-end_date').addClass('d-block').text('End Date field is required.');
                    formError = true;
                }
                if (!hourlyRate) {
                    $('#hourly_rate').addClass('is-invalid');
                    $('#validate-hourly_rate').addClass('d-block').text('Hourly rate field is required.');
                    formError = true;
                }

                if (!salesPerson) {
                    $('#sales_person').addClass('is-invalid');
                    $('#validate-sales_person').addClass('d-block').text('Please select sales person.');
                    formError = true;
                }
                if (!type) {
                    $('#type').addClass('is-invalid');
                    $('#validate-type').addClass('d-block').text('Please select type.');
                    formError = true;
                }
                if (!platform) {
                    $('#platform').addClass('is-invalid');
                    $('#validate-platform').addClass('d-block').text('Please select  platform.');
                    formError = true;
                }
                user_ids_boxes.each(function(index) {
                    let selectedValues = $(this).val();
                    if (!selectedValues || selectedValues.length === 0) {

                        let validateID = $(this).data('validate-id')
                        $('#'+validateID).addClass('is-invalid');
                        $('#validate-'+validateID).addClass('d-block').text('Please select '+validateID);
                        formError = true;
                    }
                });
                /*
                if (!commissionPercentageEmployee) {
                    $('#commission_percentage_employee').addClass('is-invalid');
                    $('#validate-commission_percentage_employee').addClass('d-block').text('Commission percentage employee field is required.');
                    formError = true;
                }

                if (!commissionPercentageManager) {
                    $('#commission_percentage_manager').addClass('is-invalid');
                    $('#validate-commission_percentage_manager').addClass('d-block').text('Commission percentage manager field is required.');
                    formError = true;
                }

                if (!commissionPercentageHod) {
                    $('#commission_percentage_hod').addClass('is-invalid');
                    $('#validate-commission_percentage_hod').addClass('d-block').text('Commission percentage hod field is required.');
                    formError = true;
                }
                 */

                if (formError) {

                    return false;
                }

                $('#saveProjectBtn').addClass('disabled');

                $.ajax({
                    'url': '{{ route('projects.store') }}',
                    'type': 'POST',
                    data: {
                        project_id: project_id,
                        client_name: clientName,
                        job_title: jobTitle,
                        start_date: startDate,
                        end_date: endDate,
                        hourly_rate: hourlyRate,
                        sales_person: salesPerson,
                        type: type,
                        platform: platform,
                        user_ids: user_ids,
                        commission_percentage_employee: commissionPercentageEmployee,
                        commission_percentage_manager: commissionPercentageManager,
                        commission_percentage_hod: commissionPercentageHod,
                        active_user: active_user,

                        _token: '{{ csrf_token() }}'
                    },

                    success: function (response) {
                        if (response.success) {

                            $('#createProjectModal').modal('hide');
                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');

                            setTimeout(function () {
                                window.location = '{{ route('projects') }}';
                            }, 2500);
                        }
                        else {

                            if (response.errors) {

                                showControllerReturnedValidations(response.errors)
                            }
                            else {

                                $('#showErrorResponseMsg').text(response.message);
                                $('#errorModal').modal('show');
                            }

                            $('#saveProjectBtn').removeClass('disabled');
                        }
                    }
                })
            })
        </script>

        <script>

            function deleteProject(id) {

                $('#inputDeleteID').val(id);
                $('#delete_heading').text('You want to delete this project?');

                if (active_user == 1)
                    $('#deleteBtn').text('Delete');
                else
                    $('#deleteBtn').text('Send For Approval');

                $('#deleteModal').modal('show');

                $('#deleteForm').on('submit', function () {

                    $('#deleteBtn').addClass('disabled');

                    $.ajax({
                        'url': '{{ route('project.destroy') }}',
                        'type': 'POST',
                        data: {
                            project_id: id,
                            active_user: active_user,
                            _token: '{{ csrf_token() }}'
                        },

                        success: function (response) {
                            if (response.success) {

                                $('#deleteModal').modal('hide');
                                $('#showResponseMsg').text(response.message);
                                $('#successModal').modal('show');

                                setTimeout(function () {
                                    window.location = '{{ route('projects') }}';
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
