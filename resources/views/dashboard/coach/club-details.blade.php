@extends('layouts.dashboard')

@section('title', 'Coach - My Club Details')

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded h-100 p-4">
                <h5 class="mb-4">Club Information</h5>

                <div class="row">
                    <div class="col-md-3">
                        <div class="card bg-dark mb-3">
                            <div class="card-body text-center">
                                @if($club->logo)
                                <img src="{{ asset('storage/' . $club->logo) }}" alt="{{ $club->name }}" class="img-fluid rounded mb-4" style="max-height: 150px;">
                                @else
                                <img src="{{ asset('img/default-avatar.png') }}" alt="{{ $club->name }}" class="img-fluid rounded mb-4" style="max-height: 150px;">
                                @endif
                                <h4 class="text-white">{{ $club->name }}</h4>
                                <p class="text-light"><i class="fa fa-envelope me-2"></i> {{ $club->email }}</p>
                                <p class="text-light"><i class="fa fa-phone me-2"></i> {{ $club->phone ?? 'No phone number' }}</p>
                                <div class="mt-3">
                                    <span class="badge {{ $club->status == 'active' ? 'bg-success' : 'bg-danger' }} p-2">
                                        <i class="fa fa-circle me-1"></i> {{ ucfirst($club->status ?? 'Unknown') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="row g-3">
                            <div class="col-sm-6 col-md-4">
                                <div class="bg-dark rounded d-flex align-items-center justify-content-between p-4">
                                    <i class="fa fa-users fa-3x text-primary"></i>
                                    <div class="ms-3 text-end">
                                        <p class="mb-2 text-white">Total Coaches</p>
                                        <h6 class="mb-0 text-white">{{ $totalClubCoaches }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="bg-dark rounded d-flex align-items-center justify-content-between p-4">
                                    <i class="fa fa-user-check fa-3x text-primary"></i>
                                    <div class="ms-3 text-end">
                                        <p class="mb-2 text-white">Members</p>
                                        <h6 class="mb-0 text-white">{{ $club->users ? $club->users->count() : 0 }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="bg-dark rounded d-flex align-items-center justify-content-between p-4">
                                    <i class="fa fa-calendar-alt fa-3x text-primary"></i>
                                    <div class="ms-3 text-end">
                                        <p class="mb-2 text-white">Established</p>
                                        <h6 class="mb-0 text-white">{{ $club->established_date ? date('Y', strtotime($club->established_date)) : 'N/A' }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="bg-dark rounded p-4 h-100">
                                    <h6 class="mb-3 text-white"><i class="fa fa-map-marker-alt me-2"></i> Location</h6>
                                    <table class="table table-borderless text-white">
                                        <tr>
                                            <td><strong>Address:</strong></td>
                                            <td>{{ $club->address ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>City:</strong></td>
                                            <td>{{ $club->city ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Country:</strong></td>
                                            <td>{{ $club->country ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Website:</strong></td>
                                            <td>
                                                @if($club->website)
                                                <a href="{{ $club->website }}" target="_blank" class="text-info">{{ $club->website }}</a>
                                                @else
                                                Not specified
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <div class="bg-dark rounded p-4 h-100">
                                    <h6 class="mb-3 text-white"><i class="fa fa-clock me-2"></i> Working Hours</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span><strong>Opening Time:</strong></span>
                                        <span>{{ $club->open_time ? date('h:i A', strtotime($club->open_time)) : 'Not set' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span><strong>Closing Time:</strong></span>
                                        <span>{{ $club->close_time ? date('h:i A', strtotime($club->close_time)) : 'Not set' }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Working Days:</strong>
                                        @if(isset($club->working_days) && is_array($club->working_days))
                                        <div class="d-flex flex-wrap mt-2">
                                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                            <span class="badge {{ in_array($day, $club->working_days) ? 'bg-primary' : 'bg-secondary' }} me-2 mb-2 p-2">
                                                {{ ucfirst($day) }}
                                            </span>
                                            @endforeach
                                        </div>
                                        @else
                                        <p>No working days specified</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card bg-dark">
                            <div class="card-header">
                                <h6 class="text-white mb-0">Club Facilities</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex flex-wrap">
                                    @if($club->has_parking)
                                    <div class="me-4 mb-3 text-center">
                                        <div class="bg-primary p-3 rounded-circle mb-2">
                                            <i class="fa fa-car text-white fa-2x"></i>
                                        </div>
                                        <p class="mb-0 text-white">Parking</p>
                                    </div>
                                    @endif
                                    @if($club->has_wifi)
                                    <div class="me-4 mb-3 text-center">
                                        <div class="bg-primary p-3 rounded-circle mb-2">
                                            <i class="fa fa-wifi text-white fa-2x"></i>
                                        </div>
                                        <p class="mb-0 text-white">WiFi</p>
                                    </div>
                                    @endif
                                    @if($club->has_showers)
                                    <div class="me-4 mb-3 text-center">
                                        <div class="bg-primary p-3 rounded-circle mb-2">
                                            <i class="fa fa-shower text-white fa-2x"></i>
                                        </div>
                                        <p class="mb-0 text-white">Showers</p>
                                    </div>
                                    @endif
                                    @if($club->has_lockers)
                                    <div class="me-4 mb-3 text-center">
                                        <div class="bg-primary p-3 rounded-circle mb-2">
                                            <i class="fa fa-lock text-white fa-2x"></i>
                                        </div>
                                        <p class="mb-0 text-white">Lockers</p>
                                    </div>
                                    @endif
                                    @if($club->has_pool)
                                    <div class="me-4 mb-3 text-center">
                                        <div class="bg-primary p-3 rounded-circle mb-2">
                                            <i class="fa fa-swimming-pool text-white fa-2x"></i>
                                        </div>
                                        <p class="mb-0 text-white">Pool</p>
                                    </div>
                                    @endif
                                    @if($club->has_sauna)
                                    <div class="me-4 mb-3 text-center">
                                        <div class="bg-primary p-3 rounded-circle mb-2">
                                            <i class="fa fa-hot-tub text-white fa-2x"></i>
                                        </div>
                                        <p class="mb-0 text-white">Sauna</p>
                                    </div>
                                    @endif
                                </div>

                                @if(empty($club->has_parking) && empty($club->has_wifi) && empty($club->has_showers) &&
                                empty($club->has_lockers) && empty($club->has_pool) && empty($club->has_sauna))
                                <p class="text-muted">No facilities information available</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if($club->description)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card bg-dark">
                            <div class="card-header">
                                <h6 class="text-white mb-0">About The Club</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-white">{{ $club->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection