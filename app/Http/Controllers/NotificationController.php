<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of all notifications for the authenticated user.
     */
    public function index()
    {
        $notifications = [];

        if (Auth::guard('admin')->check()) {
            $notifications = Auth::guard('admin')->user()->allNotifications()->paginate(10);
        } elseif (Auth::guard('club')->check()) {
            $notifications = Auth::guard('club')->user()->allNotifications()->paginate(10);
        }

        return view('dashboard.notifications.index', compact('notifications'));
    }
    /**
     * Mark a notification as read and redirect to appropriate page.
     */    public function markAsRead($id)
    {
        $notification = Notification::with('notifiable')->findOrFail($id);

        $authorized = false;

        if (
            Auth::guard('admin')->check() &&
            $notification->notifiable_type === 'App\\Models\\Admin' &&
            $notification->notifiable_id === Auth::guard('admin')->id()
        ) {
            $authorized = true;
        } elseif (
            Auth::guard('club')->check() &&
            $notification->notifiable_type === 'App\\Models\\Club' &&
            $notification->notifiable_id === Auth::guard('club')->id()
        ) {
            $authorized = true;
        }

        if (!$authorized) {
            abort(403, 'Unauthorized action.');
        }

        if (!$notification->read_at) {
            $notification->read_at = now();
            $notification->save();
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }
        switch ($notification->type) {
            case 'club_update':
                if (Auth::guard('club')->check()) {
                    return redirect()->route('club.profile');
                }
                break;

            case 'admin_notification':
                if (Auth::guard('admin')->check() && isset($notification->data['club_id'])) {
                    return redirect()->route('clubs.show', $notification->data['club_id']);
                }
                break;

            default:
                return back();
        }

        return back();
    }

    /**
     * Mark all notifications as read for the authenticated user.
     */
    public function markAllAsRead()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->user()->unreadNotifications()->update(['read_at' => now()]);
        } elseif (Auth::guard('club')->check()) {
            Auth::guard('club')->user()->unreadNotifications()->update(['read_at' => now()]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
    /**
     * Check for new notifications via AJAX call and return JSON response.
     */
    public function checkNewNotifications(Request $request)
    {
        $unreadNotifications = [];
        $count = 0;

        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $unreadNotifications = $user->unreadNotifications()
                ->where('created_at', '>=', now()->subMinutes(5))
                ->get();
            $count = $user->unreadNotifications()->count();
        } elseif (Auth::guard('club')->check()) {
            $user = Auth::guard('club')->user();
            $unreadNotifications = $user->unreadNotifications()
                ->where('created_at', '>=', now()->subMinutes(5))
                ->get();
            $count = $user->unreadNotifications()->count();
        }

        return response()->json([
            'hasNew' => $unreadNotifications->count() > 0,
            'count' => $count,
            'notifications' => $unreadNotifications
        ]);
    }

    /**
     * Get HTML content for the notification dropdown via AJAX.
     */
    public function getDropdownContent(Request $request)
    {
        $notifications = [];

        if (Auth::guard('admin')->check()) {
            $notifications = Auth::guard('admin')->user()->unreadNotifications()->limit(5)->get();
        } elseif (Auth::guard('club')->check()) {
            $notifications = Auth::guard('club')->user()->unreadNotifications()->limit(5)->get();
        }

        $html = view('dashboard.notifications.dropdown', compact('notifications'))->render();

        return response()->json([
            'html' => $html
        ]);
    }
}
