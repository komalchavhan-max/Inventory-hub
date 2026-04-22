<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{  
    public function fetch(){
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($notification){
                $message = $notification->message;
                $icon = '';
                if ($notification->status == 'Approved'){
                    $icon = '✅ ';
                } elseif ($notification->status == 'Rejected'){
                    $icon = '❌ ';
                } elseif ($notification->status == 'Completed'){
                    $icon = '✔️ ';
                }
                
                return [
                    'id' => $notification->id,
                    'message' => $icon . $message,
                    'full_message' => $notification->message,
                    'status' => $notification->status,
                    'is_read' => (bool)$notification->is_read,
                    'type' => $notification->type,
                    'created_at' => $notification->created_at ? $notification->created_at->diffForHumans() : 'Just now',
                ];
            });
            
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
            
        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }
    
    public function markAsRead(Request $request){
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $request->id)
            ->first();
            
        if ($notification){
            $notification->is_read = true;
            $notification->save();
        }
        
        return response()->json(['success' => true]);
    }
    
    public function markAllRead(){
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json(['success' => true]);
    }
    
    public function index(){
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        if (auth()->user()->isAdmin()){    // Check if user is admin or employee
            return view('notifications.index', compact('notifications'));
        }
        
        return view('employee.notifications.index', compact('notifications'));
    }
}