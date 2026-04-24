<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

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
        return view('notifications.index');
    }

    public function getNotificationsData(){
        $notifications = Notification::where('user_id', Auth::id())
            ->select('notifications.*');
        
        return DataTables::of($notifications)
            ->addColumn('action', function($row) {
                if (!$row->is_read) {
                    return '<button class="btn btn-sm btn-outline-primary mark-read-btn" data-id="'.$row->id.'">Mark read</button>';
                }
                return '<span class="text-muted">Read</span>';
            })
            ->editColumn('status', function($row) {
                $colors = ['Approved' => 'success', 'Rejected' => 'danger', 'Info' => 'info', 'Completed' => 'success'];
                $color = $colors[$row->status] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->status.'</span>';
            })
            ->editColumn('created_at', function($row) {
                return $row->created_at->diffForHumans();
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}