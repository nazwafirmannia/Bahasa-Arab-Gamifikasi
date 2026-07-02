<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @property-read \App\Models\User $user
 */
class NotificationController extends Controller
{
    /**
     * Tampilkan halaman notifikasi
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $filter = $request->get('filter', 'all'); // all | unread
        
        // ✅ PHPDoc membantu IntelliSense mengenali relasi
        /** @var \Illuminate\Database\Eloquent\Relations\HasMany $query */
        $query = $user->notifications();
        
        if ($filter === 'unread') {
            $query->whereNull('read_at');
        }
        
        $notifications = $query->paginate(20);
        
        // ✅ Auto-mark as read saat buka halaman
        $user->unreadNotifications()->update(['read_at' => now()]);
        
        return view('user.notifications.index', compact('notifications', 'filter'));
    }
    
    /**
     * Mark single notification as read (AJAX)
     */
    public function markAsRead($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $notification = Notification::where('id_notification', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail();
            
        $notification->markAsRead();
        
        return response()->json([
            'success' => true, 
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }
    
    /**
     * Mark all as read (AJAX)
     */
    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json(['success' => true, 'unread_count' => 0]);
    }
    
    /**
     * Delete notification
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        Notification::where('id_notification', $id)
            ->where('id_user', $user->id_user)
            ->firstOrFail()
            ->delete();
            
        return back()->with('success', 'Notifikasi dihapus.');
    }
}