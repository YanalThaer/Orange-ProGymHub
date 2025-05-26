@extends('layouts.dashboard')

@section('title')
    ProGymHub | Notifications
@endsection

@section('content')
<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12">
            <div class="bg-secondary rounded h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="mb-0">All Notifications</h6>
                    @if($notifications->count() > 0)
                    <a href="{{ route('notifications.markAllAsRead') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-check-double me-2"></i>Mark All as Read
                    </a>
                    @endif
                </div>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Message</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                                <tr>
                                    <td>{{ $notification->title }}</td>
                                    <td>{{ $notification->message }}</td>
                                    <td>{{ $notification->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if($notification->read_at)
                                            <span class="badge bg-success">Read</span>
                                        @else
                                            <span class="badge bg-danger">Unread</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('notifications.read', $notification->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No notifications found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
