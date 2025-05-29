@if(count($notifications) > 0)
@foreach($notifications as $notification)
<a href="javascript:void(0);"
    class="dropdown-item notification-item fw-bold"
    data-id="{{ $notification->id }}"
    data-url="{{ route('notifications.read', $notification->id) }}">
    <h6 class="fw-normal mb-0">{{ $notification->title }}</h6>
    <small>{{ $notification->message }}</small>
    <small class="text-muted d-block">{{ $notification->created_at->diffForHumans() }}</small>
</a>
@if(!$loop->last)
<hr class="dropdown-divider">
@endif
@endforeach
@else
<a href="#" class="dropdown-item">
    <h6 class="fw-normal mb-0">No unread notifications</h6>
</a>
@endif

<hr class="dropdown-divider">
<a href="{{ route('notifications.all') }}" class="dropdown-item text-center">See all notifications</a>