<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class DataTableService
{
    public static function equipmentData($query){
        return DataTables::of($query)
            ->filter(function ($query) {
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('category_name', function($row){
                return $row->category->name ?? 'Uncategorized';
            })
            ->addColumn('assigned_to_name', function($row){
                return $row->assignedUser->name ?? 'Not Assigned';
            })
            ->addColumn('action', function($row){
                if ($row->status == 'Archived'){
                    return '<form action="'.route('admin.equipment.restore', $row->id).'" method="POST" style="display:inline">
                                '.csrf_field().'
                                <button type="submit" class="btn btn-sm btn-success">Restore</button>
                            </form>';
                }
                return '
                    <a href="'.route('admin.equipment.show', $row->id).'" class="btn btn-sm btn-info">View</a>
                    <a href="'.route('admin.equipment.edit', $row->id).'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.route('admin.equipment.destroy', $row->id).'" method="POST" style="display:inline">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Archive this equipment?\')">Archive</button>
                    </form>
                ';
            })
            ->editColumn('status', function($row){
                $badges = ['Available' => 'success', 'Assigned' => 'warning', 'In-Repair' => 'danger'];
                $color = $badges[$row->status] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->status.'</span>';
            })
            ->editColumn('condition', function($row){
                $colors = ['New' => 'primary', 'Good' => 'success', 'Fair' => 'warning', 'Poor' => 'danger'];
                $color = $colors[$row->condition] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->condition.'</span>';
            })
            ->rawColumns(['action', 'status', 'condition'])
            ->make(true);
    }

    public static function categoriesData($query){           // Process Categories DataTable
        return DataTables::of($query)
            ->addColumn('icon_display', function($row){
                if ($row->icon) {
                    return '<i class="'.$row->icon.'"></i> ' . $row->name;
                }
                return $row->name;
            })
            ->addColumn('equipment_count', function($row){
                return '<span class="badge bg-primary">'.$row->equipment_count.'</span>';
            })
            ->addColumn('action', function($row){
                return '
                    <a href="'.route('admin.categories.edit', $row->id).'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.route('admin.categories.destroy', $row->id).'" method="POST" style="display:inline">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Delete this category?\')">Delete</button>
                    </form>
                ';
            })
            ->editColumn('description', function($row){
                return \Str::limit($row->description, 80);
            })
            ->rawColumns(['icon_display', 'equipment_count', 'action'])
            ->make(true);
    }

    public static function equipmentRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($query) {
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                        ->orWhere('priority', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('request_reason', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('equipment', function ($equip) use ($search) {
                            $equip->where('name', 'like', "%{$search}%")
                                    ->orWhere('serial_number', 'like', "%{$search}%");
                        });
                    });
                }
            })
            ->addColumn('employee_name', function($row){
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('equipment_name', function($row){
                return $row->equipment->name ?? 'N/A';
            })
            ->addColumn('admin_message_display', function($row){
                if ($row->admin_message) {
                    return '<button type="button" class="btn btn-sm btn-info view-message-btn" data-message="'.e($row->admin_message).'">
                                <i class="bi bi-chat-dots"></i> View Message
                            </button>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function($row){
                if ($row->status == 'Pending') {
                    return '
                        <form action="'.route('admin.requests.equipment.approve', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                data-id="'.$row->id.'" 
                                data-employee="'.e($row->user->name ?? 'N/A').'" 
                                data-equipment="'.e($row->equipment->name ?? 'N/A').'">
                            Reject
                        </button>
                    ';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('priority', function($row){
                $colors = ['Urgent' => 'danger', 'Normal' => 'warning', 'Low' => 'info'];
                $color = $colors[$row->priority] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->priority.'</span>';
            })
            ->editColumn('status', function($row){
                $colors = ['Pending' => 'warning', 'Approved' => 'info', 'Rejected' => 'danger', 'Fulfilled' => 'success'];
                $color = $colors[$row->status] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->status.'</span>';
            })
            ->editColumn('request_date', function($row){
                // Format the date to d-m-Y H:i (e.g., 22-04-2026 10:35)
                return $row->request_date ? date('d-m-Y H:i', strtotime($row->request_date)) : '-';
            })
            ->rawColumns(['action', 'priority', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function exchangeRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($query) {
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                        ->orWhere('exchange_reason', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('request_date', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('oldEquipment', function ($equip) use ($search) {
                            $equip->where('name', 'like', "%{$search}%")
                                    ->orWhere('serial_number', 'like', "%{$search}%");
                        })
                        ->orWhereHas('requestedEquipment', function ($equip) use ($search) {
                            $equip->where('name', 'like', "%{$search}%")
                                    ->orWhere('serial_number', 'like', "%{$search}%");
                        });
                        
                        if (strtolower($search) == 'yes') {
                            $q->orWhere('has_damage', 1);
                        } elseif (strtolower($search) == 'no') {
                            $q->orWhere('has_damage', 0);
                        }
                    });
                }
            })
            ->addColumn('employee_name', function($row){
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('old_equipment_name', function($row){
                return $row->oldEquipment->name ?? 'N/A';
            })
            ->addColumn('requested_equipment_name', function($row){
                return $row->requestedEquipment->name ?? 'N/A';
            })
            ->addColumn('admin_message_display', function($row){
                if ($row->admin_message) {
                    return '<button type="button" class="btn btn-sm btn-info view-message-btn" data-message="'.e($row->admin_message).'">
                                <i class="bi bi-chat-dots"></i> View Message
                            </button>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function($row){
                if ($row->status == 'Pending'){
                    return '
                        <form action="'.route('admin.requests.exchange.approve', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                data-id="'.$row->id.'" 
                                data-employee="'.e($row->user->name ?? 'N/A').'" 
                                data-equipment="'.e($row->requestedEquipment->name ?? 'N/A').'">
                            Reject
                        </button>
                    ';
                }
                if ($row->status == 'Approved'){
                    return '
                        <form action="'.route('admin.requests.exchange.process', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-primary">Process</button>
                        </form>
                    ';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('has_damage', function($row){
                return $row->has_damage ? '<span class="badge bg-danger">Yes</span>' : '<span class="badge bg-success">No</span>';
            })
            ->editColumn('status', function($row){
                $colors = ['Pending' => 'warning', 'Approved' => 'info', 'Completed' => 'success', 'Rejected' => 'danger'];
                $color = $colors[$row->status] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->status.'</span>';
            })
            ->editColumn('request_date', function($row){
                return $row->request_date ? date('d-m-Y', strtotime($row->request_date)) : '-';
            })
            ->rawColumns(['action', 'has_damage', 'status', 'admin_message_display'])
            ->make(true);
    }

   public static function repairRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($query) {     
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->orWhere('id', 'like', "%{$search}%");

                        $q->orWhere('issue_description', 'like', "%{$search}%");

                        $q->orWhere('status', 'like', "%{$search}%");

                        $q->orWhere('urgency', 'like', "%{$search}%");

                        $q->orWhere('location', 'like', "%{$search}%");

                        $q->orWhere('request_date', 'like', "%{$search}%");

                        $q->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });

                        $q->orWhereHas('equipment', function ($equip) use ($search) {
                            $equip->where('name', 'like', "%{$search}%")
                                ->orWhere('serial_number', 'like', "%{$search}%");
                        });
                    });
                }
            })
            ->addColumn('employee_name', function($row){
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('equipment_name', function($row){
                return $row->equipment->name ?? 'N/A';
            })
            ->addColumn('admin_message_display', function($row){
                if ($row->admin_message) {
                    return '<button type="button" class="btn btn-sm btn-info view-message-btn" data-message="'.e($row->admin_message).'">
                                <i class="bi bi-chat-dots"></i> View Message
                            </button>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function($row){
                if ($row->status == 'Pending') {
                    return '
                        <form action="'.route('admin.requests.repair.approve', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                data-id="'.$row->id.'" 
                                data-employee="'.e($row->user->name ?? 'N/A').'" 
                                data-equipment="'.e($row->equipment->name ?? 'N/A').'">
                            Reject
                        </button>
                    ';
                }
                if ($row->status == 'Approved') {
                    return '
                        <form action="'.route('admin.requests.repair.complete', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-primary">Complete</button>
                        </form>
                    ';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('urgency', function($row){
                $colors = ['Critical' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'success'];
                $color = $colors[$row->urgency] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->urgency.'</span>';
            })
            ->editColumn('status', function($row){
                $colors = ['Pending' => 'warning', 'Approved' => 'info', 'Completed' => 'success', 'Rejected' => 'danger'];
                $color = $colors[$row->status] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->status.'</span>';
            })
            ->editColumn('request_date', function($row){
                return $row->request_date ? date('d-m-Y', strtotime($row->request_date)) : '-';
            })
            ->rawColumns(['action', 'urgency', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function returnRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($query) {
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                        ->orWhere('return_reason', 'like', "%{$search}%")
                        ->orWhere('equipment_condition', 'like', "%{$search}%")
                        ->orWhere('missing_parts', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('return_date', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        })
                        ->orWhereHas('equipment', function ($equip) use ($search) {
                            $equip->where('name', 'like', "%{$search}%")
                                    ->orWhere('serial_number', 'like', "%{$search}%");
                        });
                    });
                }
            })
            ->addColumn('employee_name', function($row){
                return $row->user->name ?? 'N/A';
            })
            ->addColumn('equipment_name', function($row){
                return $row->equipment->name ?? 'N/A';
            })
            ->addColumn('admin_message_display', function($row){
                if ($row->admin_message){
                    return '<button type="button" class="btn btn-sm btn-info view-message-btn" data-message="'.e($row->admin_message).'">
                                <i class="bi bi-chat-dots"></i> View Message
                            </button>';
                }
                return '<span class="text-muted">-</span>';
            })
            ->addColumn('action', function($row){
                if ($row->status == 'Pending'){
                    return '
                        <form action="'.route('admin.requests.return.approve', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                data-id="'.$row->id.'" 
                                data-employee="'.e($row->user->name ?? 'N/A').'" 
                                data-equipment="'.e($row->equipment->name ?? 'N/A').'">
                            Reject
                        </button>
                    ';
                }
                if ($row->status == 'Approved'){
                    return '
                        <form action="'.route('admin.requests.return.complete', $row->id).'" method="POST" style="display:inline-block">
                            '.csrf_field().'
                            <button type="submit" class="btn btn-sm btn-primary">Complete</button>
                        </form>
                    ';
                }
                return '<span class="text-muted">-</span>';
            })
            ->editColumn('status', function($row){
                $colors = ['Pending' => 'warning', 'Approved' => 'info', 'Completed' => 'success', 'Rejected' => 'danger'];
                $color = $colors[$row->status] ?? 'secondary';
                return '<span class="badge bg-'.$color.'">'.$row->status.'</span>';
            })
            ->editColumn('return_date', function($row){
                return $row->return_date ? date('d-m-Y', strtotime($row->return_date)) : '-';
            })
            ->rawColumns(['action', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function maintenanceLogsData($query){
        return DataTables::of($query)
            ->filter(function ($query) {
                if ($search = request()->get('search')['value']) {
                    $query->where(function ($q) use ($search) {
                        $q->where('id', 'like', "%{$search}%")
                        ->orWhere('issue_description', 'like', "%{$search}%")
                        ->orWhere('technician_name', 'like', "%{$search}%")
                        ->orWhere('cost', 'like', "%{$search}%")
                        ->orWhere('repair_date', 'like', "%{$search}%")
                        ->orWhereHas('equipment', function ($equip) use ($search) {
                            $equip->where('name', 'like', "%{$search}%")
                                    ->orWhere('serial_number', 'like', "%{$search}%");
                        });
                    });
                }
            })
            ->addColumn('equipment_name', function($row){
                return $row->equipment->name ?? 'N/A';
            })
            ->addColumn('action', function($row){
                return '<a href="'.route('admin.maintenance-logs.show', $row->id).'" class="btn btn-sm btn-info">View</a>';
            })
            ->editColumn('cost', function($row){
                return '$'.number_format($row->cost, 2);
            })
            ->editColumn('repair_date', function($row){
                return $row->repair_date ? date('d-m-Y', strtotime($row->repair_date)) : '-';
            })
            ->editColumn('created_at', function($row){
                return $row->created_at ? date('d-m-Y', strtotime($row->created_at)) : '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}