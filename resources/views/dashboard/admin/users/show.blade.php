@extends('layouts.dashboard')
@section('title', 'Admin - User Details')
@section('content')
<div class="container-fluid px-4 mt-4">
    <div class="row">
        <div class="col-xl-12">            
            <div class="card mb-4 bg-dark text-white border-secondary">
                <div class="card-header bg-secondary text-white d-flex align-items-center">
                    <i class="fas fa-user me-2"></i>
                    User Information
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-dark text-white">
                        <tbody>
                            <tr>
                                <th width="30%">User ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th width="30%">Full Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Club</th>
                                <td>{{ $user->club ? $user->club->name : 'Not assigned to any club' }}</td>
                            </tr>
                            <tr>
                                <th>Coach</th>
                                <td>{{ $user->coach ? $user->coach->name : 'Not assigned to any coach' }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td>{{ $user->phone_number ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>{{ ucfirst($user->gender ?? 'Not provided') }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') : 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Height</th>
                                <td>{{ $user->height_cm ? $user->height_cm . ' cm' : 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Weight</th>
                                <td>{{ $user->weight_kg ? $user->weight_kg . ' kg' : 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Goal</th>
                                <td>{{ ucfirst($user->goal ?? 'Not provided') }}</td>
                            </tr>
                            <tr>
                                <th>Fitness Level</th>
                                <td>{{ ucfirst($user->fitness_level ?? 'Not provided') }}</td>
                            </tr>
                            <tr>
                                <th>Registered</th>
                                <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-3 d-flex gap-2">
                        <form action="{{ route('admin.users.delete', $user->getEncodedId()) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user? This action can be undone by restoring from trash.')">
                                <i class="fas fa-trash"></i> Delete User
                            </button>
                        </form>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
