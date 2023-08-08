@extends('admin.layouts.app')
@section('title', 'Roles')

@section('content')

    <div class="roles">
        <h4 class="fw-bold mb-3">Roles</h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Roles & Member</h6>
                    <a href="{{route('role.create')}}" class="btn btn-soft-primary rounded-pill px-3">Create Role</a>
                </div>

                <div class="table-responsive ">
                    <table class="table h-table">
                        <thead class="th-light">
                        <tr class="py-3">
                            <th scope="col">Role</th>
                            <th scope="col">Member</th>
                            <th scope="col">Permissions</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @if(isset($roles) && count($roles) > 0)
                            @foreach($roles as $role)
                                <tr>
                                    <td>{{$role->name}}</td>
                                    <td>{{ $role->users_count }}</td>
                                    <td>{{ $role->rolePermissions() }}</td>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center">

                                            <a href="{{route('role.edit',$role->id)}}" class="me-2 text-decoration-none"
                                            >
                                                <img src="{{asset('assets/icons/edit-icon.svg')}}" class="img-fluid"
                                                     alt="">
                                            </a>

                                            <a href="#!" class="text-decoration-none"
                                               {{--                                               data-bs-toggle="modal" data-bs-target="#deleteRoleModal"--}}
                                               onclick="deleteRecord('{{$role->id}}')"
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
                    @if(isset($roles) && count($roles) <= 0)
                        <h5 class="text-center py-4">No Record Found</h5>
                    @endif

                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-7 align-self-center">

                @if ($roles->hasMorePages())
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            @if ($roles->onFirstPage())
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
                                    <a class="page-link" href="{{ $roles->url(1) }}" aria-label="First">
                                        <span aria-hidden="true">&laquo;&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $roles->previousPageUrl() }}" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                            @endif

                            @if ($roles->currentPage() > 1)
                                <li class="page-item"><a class="page-link" href="{{ $roles->url($roles->currentPage() - 1) }}">{{ $roles->currentPage() - 1 }}</a></li>
                            @endif

                            <li class="page-item active">
                                <a class="page-link" href="#">{{ $roles->currentPage() }}</a>
                            </li>

                            @if ($roles->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $roles->nextPageUrl() }}">{{ $roles->currentPage() + 1 }}</a></li>
                            @endif

                            @if ($roles->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $roles->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="{{ $roles->url($roles->lastPage()) }}" aria-label="Last">
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

    <!--  Success  Modal -->
    @include('admin.modals.success-modal')

    <!--  delete  Modal -->

    @include('admin.modals.delete-modal')

    @push('scripts')
        <script>
            function deleteRecord(id) {

                $('#inputDeleteID').val(id);
                $('#deleteModal').modal('show');

                $('#deleteForm').on('submit', function () {

                    $('#deleteBtn').addClass('disabled');

                    $.ajax({
                        'url': '{{ route('role.delete') }}',
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
                                    window.location = '{{ route('roles') }}';
                                }, 2500);
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
