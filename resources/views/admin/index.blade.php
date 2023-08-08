@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')

    <div class="">
        <h4 class="fw-bold mb-3">Dashboard</h4>
        <div class="row admin-dashboard">
            @if(empty($activeUser['role_id']))
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-roles card-h-50 d-flex justify-content-center align-items-center">
                       <div>
                           <h1 class="fw-bold">{{ $roleCount }}</h1>
                           <p class="fs-16 mb-0">Roles</p>
                       </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-employees card-h-50 d-flex justify-content-center align-items-center">
                       <div>
                           <h1 class="fw-bold">{{ $employeeCount }}</h1>
                           <p class="fs-16 mb-0">Employees</p>
                       </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-commissions card-h-50 d-flex justify-content-center align-items-center">
                       <div>
                           <h1 class="fw-bold">70%</h1>
                           <p class="fs-16 mb-0">Commissions</p>
                       </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-projects card-h-50 d-flex justify-content-center align-items-center">
                       <div>
                           <h1 class="fw-bold">{{ $projectCount }}</h1>
                           <p class="fs-16 mb-0">Projects</p>
                       </div>
                    </div>
                </div>
            @else
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-roles card-h-50 d-flex justify-content-center align-items-center">
                        <div>
                            <h1 class="fw-bold">{{ '$'.number_format($myEarning, 2) }}</h1>
                            <p class="fs-16 mb-0 text-center">My Earning</p>
                        </div>
                    </div>
                </div>
                @if(strtolower($activeUser->role->name) == 'hod' || strtolower($activeUser->role->name) == 'manager')
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card-employees card-h-50 d-flex justify-content-center align-items-center">
                            <div>
                                <h1 class="fw-bold">{{ '$'.number_format($remainingEarning , 2) }}</h1>
                                <p class="fs-16 mb-0 text-center">Employees Commission</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card-commissions card-h-50 d-flex justify-content-center align-items-center">
                        <div>
                            <h1 class="fw-bold">{{ '$'.number_format($totalEarning , 2) }}</h1>
                            <p class="fs-16 mb-0 text-center">Total Earning</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
