@extends('admin.layouts.app')
@section('title', 'Admin Settings')

@section('content')

    <div class="roles">
        <h4 class="fw-bold mb-3">Admin Settings</h4>
        {{-- Sales Person--}}

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h6 class="fw-bold mb-0 text-capitalize">Sales Person</h6>
                    <a href="#!"
                       class="btn btn-soft-primary rounded-pill px-3"
                       onclick="getAdminSettingType('sales_person')"
                    >Create Sales Person</a>
                </div>
                <div class="table-responsive ">
                    <table class="table h-table">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">Name</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($salesPersons) && count($salesPersons) > 0)
                            @foreach($salesPersons as $sp)
                                <tr>
                                    <td>{{$sp->name}}</td>
                                    <td>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <a href="#!" class="me-2 text-decoration-none"
                                               onclick="editRecord('{{$sp->id}}','{{$sp->name}}','{{$sp->setting_type}}')"
                                            >
                                                <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>
                                            <a href="#!" class="text-decoration-none"
                                               onclick="deleteRecord('{{$sp->id}}')"
                                            >
                                                <img src="{{asset('assets/icons/delete-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(isset($salesPersons) && count($salesPersons) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif
                </div>
            </div>
        </div>


        {{--Platform--}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h6 class="fw-bold mb-0 text-capitalize">Platform</h6>
                    <a href="#!"
                       onclick="getAdminSettingType('platform')"
                       class="btn btn-soft-primary rounded-pill px-3"
                    >Create Platform</a>
                </div>

                <div class="table-responsive ">
                    <table class="table h-table">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">Name</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($platforms) && count($platforms) > 0)
                            @foreach($platforms as $p)
                                <tr>
                                    <td>{{$p->name}}</td>
                                    <td>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <a href="#!" class="me-2 text-decoration-none"
                                               onclick="editRecord('{{$p->id}}','{{$p->name}}','{{$p->setting_type}}')"
                                            >
                                                <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>
                                            <a href="#!" class="text-decoration-none"
                                               onclick="deleteRecord('{{$p->id}}')"
                                            >
                                                <img src="{{asset('assets/icons/delete-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(isset($platforms) && count($platforms) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif
                </div>
            </div>
        </div>

        {{--Type--}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h6 class="fw-bold mb-0 text-capitalize">Type</h6>
                    <a href="#!"
                       onclick="getAdminSettingType('type')"
                       class="btn btn-soft-primary rounded-pill px-3"
                    >Create Type</a>
                </div>

                <div class="table-responsive ">
                    <table class="table h-table">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">Name</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($types) && count($types) > 0)
                            @foreach($types as $t)
                                <tr>
                                    <td>{{$t->name}}</td>
                                    <td>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <a href="#!" class="me-2 text-decoration-none"
                                               onclick="editRecord('{{$t->id}}','{{$t->name}}','{{$t->setting_type}}')"
                                            >
                                                <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>
                                            <a href="#!" class="text-decoration-none"
                                               onclick="deleteRecord('{{$t->id}}')"
                                            >
                                                <img src="{{asset('assets/icons/delete-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    @if(isset($types) && count($types) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif
                </div>
            </div>
        </div>

    </div>



    <!--  Create or Update Modal -->
    <div class="modal fade" id="createAdminSettingsModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-primary fw-600" id="staticBackdropLabel">Create <span
                            id="legendHeaderName"></span></h6>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
                </div>
                <form id="createAdminSettings" onsubmit="return false;">
                    @csrf
                    <input type="hidden" class="form-control" readonly name="settings_id" id="settings_id">
                    <input type="hidden" class="form-control" readonly name="settings_type" id="settings_type">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <fieldset class="input-group border rounded-1 ps-1">
                                    <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend"><span
                                            id="legendName"></span><span
                                            class="text-danger ">*</span></legend>
                                    <input type="text"
                                           class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                                           name="name"
                                           id="name"
                                           placeholder="Name"
                                    >
                                </fieldset>
                                <div id="validateSettingName" class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14"
                                data-bs-dismiss="modal">Cancel
                        </button>
                        <button type="submit" class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14 "
                                id="saveSettingBtn">Save
                        </button>
                    </div>
                </form>
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
            function getAdminSettingType(adminType) {
                $('#settings_type').val(adminType);

                if (adminType === 'sales_person') {
                    $('#legendName').text('Sales Persons');
                    $('#legendHeaderName').text('Sales Persons');
                } else if (adminType === 'platform') {
                    $('#legendName').text('Platform');
                    $('#legendHeaderName').text('Platform');
                } else {
                    $('#legendName').text('type');
                    $('#legendHeaderName').text('type');
                }

                $('#createAdminSettingsModal').modal('show');

                // Submit Form

                saveOrUpdateSettings();

            }

            function editRecord(id, name, adminType) {
                if (adminType === 'sales_person') {
                    $('#legendName').text('Sales Persons');
                    $('#legendHeaderName').text('Sales Persons');
                } else if (adminType === 'platform') {
                    $('#legendName').text('Platform');
                    $('#legendHeaderName').text('Platform');
                } else {
                    $('#legendName').text('type');
                    $('#legendHeaderName').text('type');
                }

                $('#settings_id').val(id);
                $('#name').val(name);
                $('#settings_type').val(adminType);
                $('#createAdminSettingsModal').modal('show');

                // Submit Form
                saveOrUpdateSettings();
            }

            function saveOrUpdateSettings() {
                $('#createAdminSettings').on('submit', function () {

                    $('#name').removeClass('is-invalid');
                    $('#validateSettingName').removeClass('d-block');


                    let id = $('#settings_id').val();
                    let name = $('#name').val();
                    let checkSettingType = $('#settings_type').val();


                    let formError = false;

                    if (!name) {

                        $('#name').addClass('is-invalid');
                        $('#validateSettingName').addClass('d-block').text('Name field is required.');
                        formError = true;
                    }
                    if (formError) {
                        return false;
                    }
                    $('#saveSettingBtn').addClass('disabled');


                    $.ajax({
                        'url': '{{ route('setting.store') }}',
                        'type': 'POST',
                        data: {
                            id: id,
                            setting_type: checkSettingType,
                            name: name,
                            _token: '{{ csrf_token() }}'
                        },

                        success: function (response) {
                            if (response.success) {

                                $('#deleteRoleModal').modal('hide');
                                $('#showResponseMsg').text(response.message);
                                $('#createAdminSettingsModal').modal('hide');
                                $('#successModal').modal('show');

                                setTimeout(function () {
                                    window.location = '{{ route('settings') }}';
                                }, 1000);
                            } else {

                                if (response.errors) {
                                    $('#showMessage').text(response.message).addClass('alert-danger');
                                }

                                $('#deleteBtn').removeClass('disabled');
                            }
                        }
                    })
                })
            }


            // Delete Settings
            function deleteRecord(id) {

                $('#inputDeleteID').val(id);
                $('#deleteModal').modal('show');

                $('#deleteForm').on('submit', function () {

                    $('#deleteBtn').addClass('disabled');

                    $.ajax({
                        'url': '{{ route('settings.delete') }}',
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
                                    window.location = '{{ route('settings') }}';
                                }, 1000);
                            } else {

                                if (response.errors) {
                                    $('#showMessage').text(response.message).addClass('alert-danger');
                                }

                                $('#deleteBtn').removeClass('disabled');
                            }
                        }
                    })
                })
            }
        </script>
    @endpush

@endsection
