@extends('admin.layouts.app')
@section('title', 'Notification')

@section('content')

    <div class="notifications">
        <h4 class="fw-bold mb-3">Notifications</h4>

        {{-- Start Notification --}}
        @foreach($notifications as $notification)
            <div class="card notify-card bg-transparent border-0 border-bottom rounded-0 notification_row_{{$notification['id']}}">
                <div class="card-body">
                    <div class="d-md-flex justify-content-between align-items-center align-items-center">
                        <div class="mb-3 mb-md-0">
                            <h6 class="fw-600">{{ $notification['user']->first_name . ' ' . $notification['user']->last_name }}</h6>
                            @if($adminNotifications)
                                @if($notification->type == 'project')
                                    <p class="mb-0">
                                        @if(!empty($notification['project_id']) && $notification->is_requested_del == 1)
                                            Want to delete the <span class="fw-600">{{ $notification->job_title }}</span> Project need approval.
                                        @elseif(!empty($notification->project_id) && $notification->is_requested_del == 0)
                                            Want to edit the <span class="fw-600">{{ $notification->job_title }}</span> Project need approval.
                                        @elseif(empty($notification['project_id']))
                                            Want to add the <span class="fw-600">{{ $notification->job_title }}</span> Project need approval.
                                        @endif
                                    </p>
                                    <p class="mb-0">
                                        {{ $notification['updated_at'] ? ($notification['updated_at']->isToday() ? $notification['updated_at']->format('h:i A') : $notification['updated_at']->format('M d, Y h:i A')) : ($notification['created_at']->isToday() ? $notification['created_at']->format('h:i A') : $notification['created_at']->format('M d, Y h:i A')) }}
                                    </p>
                                @elseif($notification->type == 'earning')
                                    <p class="mb-0">
                                        @if(!empty($notification->earning_id) && $notification->is_requested_del == 1)
                                            Want to delete the $ {{ number_format($notification->earning , 2) }} earning for {{ $notification['month'] }} against <span class="fw-600">{{ $notification['project']->job_title }}</span> Project need approval.
                                        @elseif(!empty($notification->earning_id) && $notification->is_requested_del == 0)
                                            Want to edit the ${{ number_format($notification->earning , 2) }} earning for {{ $notification['month'] }} against <span class="fw-600">{{ $notification['project']->job_title }}</span> Project need approval.
                                        @elseif(empty($notification->earning_id) && $notification->is_requested_del == 0)
                                            Want to add the ${{ number_format($notification->earning , 2) }} earning for {{ $notification['month'] }} against <span class="fw-600">{{ $notification['project']->job_title }}</span> Project need approval.
                                        @endif
                                    </p>
                                    <p class="mb-0">
                                        {{ $notification['updated_at'] ? ($notification['updated_at']->isToday() ? $notification['updated_at']->format('h:i A') : $notification['updated_at']->format('M d, Y h:i A')) : ($notification['created_at']->isToday() ? $notification['created_at']->format('h:i A') : $notification['created_at']->format('M d, Y h:i A')) }}
                                    </p>
                                @elseif($notification->type == 'commission')
                                    <p class="mb-0">
                                        Want to add the commission for <span class="fw-600">{{ $notification['project']->job_title }}</span> Project need approval.
                                    </p>
                                    <p class="mb-0">
                                        {{ $notification['updated_at'] ? ($notification['updated_at']->isToday() ? $notification['updated_at']->format('h:i A') : $notification['updated_at']->format('M d, Y h:i A')) : ($notification['created_at']->isToday() ? $notification['created_at']->format('h:i A') : $notification['created_at']->format('M d, Y h:i A')) }}
                                    </p>
                                @elseif($notification->type == 'stopEarning')
                                    <p class="mb-0">
                                        Want to stop earning for {{ $notification['earner']->first_name . ' ' . $notification['earner']->last_name }} from {{ $notification['month'] }} against <span class="fw-600">{{ $notification['project']->job_title }}</span> Project need approval.
                                    </p>
                                    <p class="mb-0">
                                        {{ $notification['updated_at'] ? ($notification['updated_at']->isToday() ? $notification['updated_at']->format('h:i A') : $notification['updated_at']->format('M d, Y h:i A')) : ($notification['created_at']->isToday() ? $notification['created_at']->format('h:i A') : $notification['created_at']->format('M d, Y h:i A')) }}
                                    </p>
                                @elseif($notification->type == 'completed')
                                    <p class="mb-0">
                                        Want to complete <span class="fw-600">{{ $notification['project']->job_title }}</span> Project. </p>
                                    <p class="mb-0">
                                        {{ $notification['updated_at'] ? ($notification['updated_at']->isToday() ? $notification['updated_at']->format('h:i A') : $notification['updated_at']->format('M d, Y h:i A')) : ($notification['created_at']->isToday() ? $notification['created_at']->format('h:i A') : $notification['created_at']->format('M d, Y h:i A')) }}
                                    </p>
                                @endif

                            @else
                                <p class="mb-0">
                                    {!! $notification['message'] !!}
                                </p>
                            @endif
                            <p class="mb-0">
                                {{ $notification['updated_at'] ? ($notification['updated_at']->isToday() ? $notification['updated_at']->format('h:i A') : $notification['updated_at']->format('M d, Y h:i A')) : ($notification['created_at']->isToday() ? $notification['created_at']->format('h:i A') : $notification['created_at']->format('M d, Y h:i A')) }}
                            </p>
                        </div>
                        @if($adminNotifications)
                            <div class="d-flex align-items-center">
                                <div class="btn-group-footer z-index-99">
{{--                                    <button type="submit" class="btn btn-soft-primary mb-1 min-w-120 btn-lg-8 rounded-2 fs-14"--}}
{{--                                            data-bs-toggle="modal" data-bs-target="#viewProjectDetailModal"--}}
{{--                                            id="create_role_btn">Project Detail--}}
{{--                                    </button>--}}
                                    @if($notification->type == 'project')
                                        <a class="btn btn-soft-primary min-w-120 btn-lg-8 rounded-2 mx-2 fs-14" href="javascript:void(0)" onclick="detail_project('{{ $notification['id'] }}')">Project Detail</a>
                                    @elseif($notification->type == 'commission')
                                        <a class="btn btn-soft-primary min-w-120 btn-lg-8 rounded-2 mx-2 fs-14" href="javascript:void(0)" onclick="detail_commission('{{ $notification['id'] }}')">Commission Detail</a>
                                    @endif
                                        <a class="btn btn-light min-w-120 btn-lg-8 rounded-2 mx-2 fs-14"
                                           href="javascript:void(0)"
                                           onclick="approve_project('{{ base64_encode($notification['id']) }}', '1', '{{ $notification->type }}')">Cancel</a>
                                        <a class="btn btn-secondary min-w-120 btn-lg-8 rounded-2 fs-14"
                                           href="javascript:void(0)"
                                           onclick="approve_project('{{ base64_encode($notification['id']) }}', '2', '{{ $notification->type }}')">Approve</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        {{-- End Notification --}}

    </div>

    <h5 class="text-center py-4 no_records" style="display: @if(isset($notifications) && count($notifications) <= 0) block @else none @endif">No Record Found</h5>

    <div class="modal fade" id="viewProjectDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-primary fw-600" id="createProjectModalLabel">Project Detail</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Client Name</p>
                                    <h5 class="fw-600 mb-0 fs-16 client-name">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Project Name</p>
                                    <h5 class="fw-600 mb-0 fs-16 project-name">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Start Date</p>
                                    <h5 class="fw-600 mb-0 fs-16 start-date">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">End Date</p>
                                    <h5 class="fw-600 mb-0 fs-16 end-date">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Hourly Rate</p>
                                    <h5 class="fw-600 mb-0 fs-16 hourly-rate">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Sales Person</p>
                                    <h5 class="fw-600 mb-0 fs-16 sales-person">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Type</p>
                                    <h5 class="fw-600 mb-0 fs-16 project-type">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Platform</p>
                                    <h5 class="fw-600 mb-0 fs-16 platform">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Employee</p>
                                    <h5 class="fw-600 mb-0 fs-16 employee">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission%Employee</p>
                                    <h5 class="fw-600 mb-0 fs-16 project-commission-employee">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission%Manager</p>
                                    <h5 class="fw-600 mb-0 fs-16 project-commission-manager">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission%HOD</p>
                                    <h5 class="fw-600 mb-0 fs-16 project-commission-hod">---</h5>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewCommissionDetailModal" data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h6 class="modal-title text-primary fw-600" id="createProjectModalLabel">Commission Detail</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="d-flex align-items-center">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Project Name</p>
                                    <h5 class="fw-600 mb-0 fs-16 commission-project-name">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Requested By</p>
                                    <h5 class="fw-600 mb-0 fs-16 requested-by">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission%Employee</p>
                                    <h5 class="fw-600 mb-0 fs-16 commission-employee">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission%Manager</p>
                                    <h5 class="fw-600 mb-0 fs-16 commission-manager">---</h5>
                                </div>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <div>
                                    <p class="fs-16 text-light-400 mb-1 fw-normal">Commission%HOD</p>
                                    <h5 class="fw-600 mb-0 fs-16 commission-hod">---</h5>
                                </div>
                            </div>

                        </div>
                    </div>

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

@endsection

@push('scripts')

    <script>

        function approve_project(notification_id, approval_status, notification_type) {
            if (notification_type === 'project')
                notification_type = 1;
            else if (notification_type === 'earning')
                notification_type = 2;
            else if (notification_type === 'commission')
                notification_type = 3;
            else if (notification_type === 'stopEarning')
                notification_type = 4;
            else if (notification_type === 'completed')
                notification_type = 5;

            $.ajax({
                url: '{{ route('notifications.approve') }}',
                type: 'POST',
                data: {
                    notification_id: notification_id,
                    approval_status: approval_status,
                    notification_type: notification_type,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {

                    if (response.success) {

                        $('#showResponseMsg').text(response.message);
                        $('#successModal').modal('show');
                        $('.notification_row_'+atob(notification_id)).remove();

                        if (response.remainingNotifications === 0) {

                            $('.no_records').show();
                        }

                        setTimeout(function () {
                            $('#successModal').modal('hide');
                        }, 1500);
                    }
                    else {

                        $('#showErrorResponseMsg').text(response.message);
                        $('#errorModal').modal('show');
                    }
                }
            })
        }

        function detail_project(project_id) {

            $.ajax({
                url: '{{ route('notifications.project-detail') }}',
                type: 'GET',
                data: {
                    project_id: project_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {

                    if (response.success) {

                        $('.client-name').text(response.data.client_name);
                        $('.project-name').text(response.data.job_title);
                        $('.start-date').text(response.data.start_date);
                        $('.end-date').text(response.data.end_date);
                        $('.hourly-rate').text(response.data.hourly_rate);
                        $('.sales-person').text(response.data.sales_person);
                        $('.project-type').text(response.data.type);
                        $('.platform').text(response.data.platform);
                        $('.employee').text(response.data.employee);
                        $('.project-commission-hod').text(response.data.commission_hod);
                        $('.project-commission-manager').text(response.data.commission_manager);
                        $('.project-commission-employee').text(response.data.commission_employee);

                        $('#viewProjectDetailModal').modal('show');


                        // setTimeout(function () {
                        //     $('#viewProjectDetailModal').modal('hide');
                        // }, 1500);
                    }
                    else {

                        $('#showErrorResponseMsg').text(response.message);
                        $('#errorModal').modal('show');
                    }
                }
            })
        }

        function detail_commission(commission_id) {

            $.ajax({
                url: '{{ route('notifications.commission-detail') }}',
                type: 'GET',
                data: {
                    commission_id: commission_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {

                    if (response.success) {

                        console.log(response.data);
                        $('.commission-project-name').text(response.data.project);
                        $('.requested-by').text(response.data.requested_by);
                        $('.commission-employee').text(response.data.commission_percentage_employee);
                        $('.commission-hod').text(response.data.commission_percentage_hod);
                        $('.commission-manager').text(response.data.commission_percentage_manager);
                        $('#viewCommissionDetailModal').modal('show');

                    }
                    else {

                        $('#showErrorResponseMsg').text(response.message);
                        $('#errorModal').modal('show');
                    }
                }
            })
        }

    </script>

@endpush
