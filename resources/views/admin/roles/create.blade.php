@extends('admin.layouts.app')
@section('title', 'Create Role')

@section('content')

    <div class="roles position-relative">

        <div class="alert " id="showMessage" role="alert" style="display: none">
        </div>

        <form id="create_role" onsubmit="return false;">
            @csrf

            <input type="hidden" id="role_id" name="role_id" value="{{ isset($role) ? $role['id'] : null }}">

            <div class="row mb-3">
                <div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    <fieldset class="input-group border rounded-1 ps-1">
                        <legend class="float-none w-auto mb-0 px-2 ms-1 fs-15">Role Name <span
                                class="text-danger ">*</span>
                        </legend>
                        <input type="text"
                               class="form-control form-control-lg bg-transparent border-0 fs-14 outline-none ph-light"
                               name="name"
                               @if(isset($role))
                               value="{{$role->name}}"
                               @endif
                               id="name"
                               placeholder="Enter Role"
                        >
                    </fieldset>
                    <div id="validate-name" class="invalid-feedback">

                    </div>

                </div>
                <div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    <fieldset class="input-group border rounded-1 ps-1">
                        <legend class="float-none w-auto mb-0 px-2 ms-1 fs-14 legend">Parent Role</legend>
                        <select tabindex="2" name="parent_id" id="parent_id"
                                class="form-control form-control-lg border-0 bg-transparent select-dropdown-arrow fs-14 outline-none ph-light">
                            <option value="">Select Parent Role</option>


                            @if(isset($roles) && count($roles) > 0)
                                @foreach($roles as $r)
                                    <option value="{{$r->id}}"
                                    @if(isset($role))
                                        {{$r->id === $role->parent_id ? 'selected' : ''}}
                                        @endif

                                    >{{$r->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </fieldset>

                </div>
            </div>

            <div class="card mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h6 class="fw-bold mb-0">Permissions</h6>
                        {{--                    <a href="#!" class="btn btn-soft-primary rounded-pill px-3">Create Role</a>--}}
                        <div>
                            <div id="validate-permission" class="invalid-feedback">
                                Please select at least one permission.
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table h-table">
                            <thead class="th-light">
                            <tr class="py-3">
                                <th scope="col">Permissions</th>
                                <th scope="col" class="text-end">Assigned</th>
                            </tr>
                            </thead>
                            <tbody>


                            @if(isset($permissions) && count($permissions) > 0)
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{{$permission->name}}</td>
                                        <td class="">
                                            <div class="form-check d-flex justify-content-end ">

                                                <input class="form-check-input cb-lg"
                                                       name="permission_ids[]"
                                                       type="checkbox" value="{{$permission->id}}"
                                                       id="permission_ids"

                                                @if(isset($role))
                                                    {{ in_array($permission->id, json_decode($role->permission_ids)) ? 'checked' : '' }}
                                                    @endif

                                                >

                                                {{-- label class="form-check-label" for="flexCheckDefault"></label>--}}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="position-fixed bottom-0 pb-3 pt-2  z-index-99 d-flex justify-content-end right-25 ">
                <div class="btn-group-footer z-index-99">
                    <a href="{{route('roles')}}" class="btn btn-light min-w-120 btn-lg rounded-2 fs-14 ">Cancel</a>
                    <button type="submit" class="btn btn-secondary min-w-120 btn-lg rounded-2 ms-2 fs-14"
                            id="create_role_btn">Create Role
                    </button>
                </div>
            </div>
        </form>
    </div>


    <!--  Success  Modal -->
    @include('admin.modals.success-modal')

    <!--  Delete Modal -->
    @include('admin.modals.error-modal')

    @push('scripts')

        <script>
            $('#create_role').on('submit', function () {

                $('#name').removeClass('is-invalid');
                $('#validate-name').removeClass('d-block');
                $("input[name='permission_ids[]']").removeClass('border-danger');
                $('#validate-permission').removeClass('d-block');

                let role_id = $('#role_id').val();
                let name = $('#name').val();
                let parent_id = $('#parent_id').val();
                let permission_ids = $("input[name='permission_ids[]']:checked").map(function () {
                    return $(this).val();
                }).get();

                let formError = false;

                if (!name) {

                    $('#name').addClass('is-invalid');
                    $('#validate-name').addClass('d-block').text('Role name field is required.');
                    formError = true;
                }
                if (permission_ids.length == 0) {

                    $('#validate-permission').addClass('d-block');
                    $("input[name='permission_ids[]']").addClass('border-danger');
                    formError = true;
                }

                if (formError) {

                    return false;
                }

                $('#create_role_btn').addClass('disabled');

                $.ajax({
                    'url': '{{ route('role.store') }}',
                    'type': 'POST',
                    data: {
                        role_id: role_id,
                        name: name,
                        parent_id: parent_id,
                        permission_ids: permission_ids,
                        _token: '{{ csrf_token() }}'
                    },

                    success: function (response) {

                        if (response.success) {

                            $('#showResponseMsg').text(response.message);
                            $('#successModal').modal('show');

                            setTimeout(function () {
                                window.location = '{{ route('roles') }}';
                            }, 2500);
                        } else {

                            if (response.errors) {

                                $('#name').addClass('is-invalid');
                                $('#validate-name').addClass('d-block').text(response.errors.name);
                            }
                            else {

                                $('#showResponseMsg').text(response.message);
                                $('#errorModal').modal('show');
                            }
                            $('#create_role_btn').removeClass('disabled');
                        }
                    }
                })
            })
        </script>
    @endpush

@endsection
