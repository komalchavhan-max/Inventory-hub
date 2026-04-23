<?php

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use App\Models\Equipment;

class DataTableService
{
    private const STATUS_MAP = [
        'Available' => 'tint-success',
        'Assigned'  => 'tint-warning',
        'In-Repair' => 'tint-danger',
        'Archived'  => 'tint-slate',
        'Pending'   => 'tint-warning',
        'Approved'  => 'tint-info',
        'Completed' => 'tint-success',
        'Fulfilled' => 'tint-success',
        'Rejected'  => 'tint-danger',
    ];

    private const CONDITION_MAP = [
        'New'  => 'tint-info',
        'Good' => 'tint-success',
        'Fair' => 'tint-warning',
        'Poor' => 'tint-danger',
    ];

    private const PRIORITY_MAP = [
        'Urgent' => 'tint-danger',
        'High'   => 'tint-warning',
        'Normal' => 'tint-info',
        'Low'    => 'tint-slate',
    ];

    private const URGENCY_MAP = [
        'Critical' => 'tint-danger',
        'High'     => 'tint-warning',
        'Medium'   => 'tint-info',
        'Low'      => 'tint-success',
    ];

    private static function pill(string $label, string $tint): string{
        return '<span class="badge-pill '.$tint.'">'.$label.'</span>';
    }

    private static function statusPill(string $status): string{
        $label = $status === 'In-Repair' ? 'In Repair' : $status;
        return self::pill($label, self::STATUS_MAP[$status] ?? 'tint-slate');
    }

    private static function emptyCell(): string{
        return '<span class="text-muted">—</span>';
    }

    private static function messageButton($message): string{
        if (!$message) {
            return self::emptyCell();
        }
        return '<button type="button" class="action-btn message view-message-btn" title="View message" aria-label="View message" data-message="'.e($message).'"><i class="bi bi-chat-dots"></i></button>';
    }

    private static function rejectButton($row, string $equipmentField = 'equipment'): string{
        $equipmentName = $row->{$equipmentField}->name ?? 'N/A';
        return '<button type="button" class="action-btn reject reject-btn" title="Reject" aria-label="Reject"
                data-id="'.$row->id.'"
                data-employee="'.e($row->user->name ?? 'N/A').'"
                data-equipment="'.e($equipmentName).'"><i class="bi bi-x-lg"></i></button>';
    }

    private static function approveButton(string $routeName, $id): string{
        return '<form action="'.route($routeName, $id).'" method="POST" class="d-inline-flex">
                    '.csrf_field().'
                    <button type="submit" class="action-btn approve" title="Approve" aria-label="Approve"><i class="bi bi-check-lg"></i></button>
                </form>';
    }

    private static function singleActionForm(string $routeName, $id, string $class, string $icon, string $label): string{
        return '<form action="'.route($routeName, $id).'" method="POST" class="d-inline-flex">
                    '.csrf_field().'
                    <button type="submit" class="action-btn '.$class.'" title="'.$label.'" aria-label="'.$label.'"><i class="bi '.$icon.'"></i></button>
                </form>';
    }

    private static function deleteForm(string $routeName, $id, string $confirm, string $actionClass = 'archive', string $icon = 'bi-archive', string $label = 'Archive'): string{
        return '<form action="'.route($routeName, $id).'" method="POST" class="d-inline-flex" onsubmit="return confirm(\''.$confirm.'\');">
                    '.csrf_field().'
                    '.method_field('DELETE').'
                    <button type="submit" class="action-btn '.$actionClass.'" title="'.$label.'" aria-label="'.$label.'"><i class="bi '.$icon.'"></i></button>
                </form>';
    }

    public static function equipmentData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('name', 'like', "%{$search}%")
                              ->orWhere('serial_number', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('category_name', fn($row) => $row->category->name ?? 'Uncategorized')
            ->addColumn('assigned_to_name', fn($row) => $row->assignedUser->name ?? 'Not Assigned')
            ->addColumn('action', function ($row) {
                $view = '<a href="'.route('admin.equipment.show', $row->id).'" class="action-btn view" title="View" aria-label="View"><i class="bi bi-eye"></i></a>';

                if ($row->status === 'Archived') {
                    return '<div class="action-group">'.$view
                        .self::singleActionForm('admin.equipment.restore', $row->id, 'restore', 'bi-arrow-counterclockwise', 'Restore')
                        .'</div>';
                }

                $edit = '<a href="'.route('admin.equipment.edit', $row->id).'" class="action-btn edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>';
                return '<div class="action-group">'.$view.$edit
                    .self::deleteForm('admin.equipment.destroy', $row->id, 'Archive this equipment?')
                    .'</div>';
            })
            ->editColumn('status', fn($row) => self::statusPill($row->status))
            ->editColumn('condition', fn($row) => self::pill($row->condition, self::CONDITION_MAP[$row->condition] ?? 'tint-slate'))
            ->rawColumns(['action', 'status', 'condition'])
            ->make(true);
    }

    public static function categoriesData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('name', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('icon_display', function ($row) {
                return $row->icon ? '<i class="'.$row->icon.' me-1"></i>'.$row->name : $row->name;
            })
            ->addColumn('equipment_count', function ($row) {
                $count = \App\Models\Equipment::where('category_id', $row->id)->count();
                return '<span class="badge bg-primary">' . $count . '</span>';
            })
            ->addColumn('action', function ($row) {
                $view = '<a href="'.route('admin.categories.show', $row->id).'" class="action-btn view" title="View" aria-label="View"><i class="bi bi-eye"></i></a>';
                $edit = '<a href="'.route('admin.categories.edit', $row->id).'" class="action-btn edit" title="Edit" aria-label="Edit"><i class="bi bi-pencil"></i></a>';
                $delete = '<form action="'.route('admin.categories.destroy', $row->id).'" method="POST" class="d-inline-flex" onsubmit="return confirm(\'Delete this category?\');">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="action-btn delete" title="Delete" aria-label="Delete"><i class="bi bi-trash"></i></button>
                            </form>';
                return '<div class="action-group">'.$view.$edit.$delete.'</div>';
            })
            ->editColumn('description', fn($row) => \Str::limit($row->description, 80))
            ->rawColumns(['icon_display', 'equipment_count', 'action'])
            ->make(true);
    }

    public static function equipmentRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('id', 'like', "%{$search}%")
                              ->orWhere('priority', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%")
                              ->orWhere('request_reason', 'like', "%{$search}%")
                              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                              ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', "%{$search}%")->orWhere('serial_number', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('employee_name', fn($row) => $row->user->name ?? 'N/A')
            ->addColumn('equipment_name', fn($row) => $row->equipment->name ?? 'N/A')
            ->addColumn('admin_message_display', fn($row) => self::messageButton($row->admin_message))
            ->addColumn('action', function ($row) {
                if ($row->status !== 'Pending') {
                    return self::emptyCell();
                }
                return '<div class="action-group">'
                    .self::approveButton('admin.requests.equipment.approve', $row->id)
                    .self::rejectButton($row, 'equipment')
                    .'</div>';
            })
            ->editColumn('priority', fn($row) => self::pill($row->priority, self::PRIORITY_MAP[$row->priority] ?? 'tint-slate'))
            ->editColumn('status', fn($row) => self::statusPill($row->status))
            ->editColumn('request_date', fn($row) => $row->request_date ? date('d-m-Y H:i', strtotime($row->request_date)) : '—')
            ->rawColumns(['action', 'priority', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function exchangeRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('id', 'like', "%{$search}%")
                              ->orWhere('exchange_reason', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%")
                              ->orWhere('request_date', 'like', "%{$search}%")
                              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                              ->orWhereHas('oldEquipment', fn($e) => $e->where('name', 'like', "%{$search}%")->orWhere('serial_number', 'like', "%{$search}%"))
                              ->orWhereHas('requestedEquipment', fn($e) => $e->where('name', 'like', "%{$search}%")->orWhere('serial_number', 'like', "%{$search}%"));

                        if (strtolower($search) === 'yes') $inner->orWhere('has_damage', 1);
                        elseif (strtolower($search) === 'no') $inner->orWhere('has_damage', 0);
                    });
                }
            })
            ->addColumn('employee_name', fn($row) => $row->user->name ?? 'N/A')
            ->addColumn('old_equipment_name', fn($row) => $row->oldEquipment->name ?? 'N/A')
            ->addColumn('requested_equipment_name', fn($row) => $row->requestedEquipment->name ?? 'N/A')
            ->addColumn('admin_message_display', fn($row) => self::messageButton($row->admin_message))
            ->addColumn('action', function ($row) {
                if ($row->status === 'Pending') {
                    return '<div class="action-group">'
                        .self::approveButton('admin.requests.exchange.approve', $row->id)
                        .self::rejectButton($row, 'requestedEquipment')
                        .'</div>';
                }
                if ($row->status === 'Approved') {
                    return '<div class="action-group">'
                        .self::singleActionForm('admin.requests.exchange.process', $row->id, 'process', 'bi-arrow-right-circle', 'Process')
                        .'</div>';
                }
                return self::emptyCell();
            })
            ->editColumn('has_damage', fn($row) => $row->has_damage
                ? self::pill('Yes', 'tint-danger')
                : self::pill('No', 'tint-success'))
            ->editColumn('status', fn($row) => self::statusPill($row->status))
            ->editColumn('request_date', fn($row) => $row->request_date ? date('d-m-Y', strtotime($row->request_date)) : '—')
            ->rawColumns(['action', 'has_damage', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function repairRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('id', 'like', "%{$search}%")
                              ->orWhere('issue_description', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%")
                              ->orWhere('urgency', 'like', "%{$search}%")
                              ->orWhere('location', 'like', "%{$search}%")
                              ->orWhere('request_date', 'like', "%{$search}%")
                              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                              ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', "%{$search}%")->orWhere('serial_number', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('employee_name', fn($row) => $row->user->name ?? 'N/A')
            ->addColumn('equipment_name', fn($row) => $row->equipment->name ?? 'N/A')
            ->addColumn('admin_message_display', fn($row) => self::messageButton($row->admin_message))
            ->addColumn('action', function ($row) {
                if ($row->status === 'Pending') {
                    return '<div class="action-group">'
                        .self::approveButton('admin.requests.repair.approve', $row->id)
                        .self::rejectButton($row, 'equipment')
                        .'</div>';
                }
                if ($row->status === 'Approved') {
                    return '<div class="action-group">'
                        .self::singleActionForm('admin.requests.repair.complete', $row->id, 'complete', 'bi-check2-circle', 'Complete')
                        .'</div>';
                }
                return self::emptyCell();
            })
            ->editColumn('urgency', fn($row) => self::pill($row->urgency, self::URGENCY_MAP[$row->urgency] ?? 'tint-slate'))
            ->editColumn('status', fn($row) => self::statusPill($row->status))
            ->editColumn('request_date', fn($row) => $row->request_date ? date('d-m-Y', strtotime($row->request_date)) : '—')
            ->rawColumns(['action', 'urgency', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function returnRequestsData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('id', 'like', "%{$search}%")
                              ->orWhere('return_reason', 'like', "%{$search}%")
                              ->orWhere('equipment_condition', 'like', "%{$search}%")
                              ->orWhere('missing_parts', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%")
                              ->orWhere('return_date', 'like', "%{$search}%")
                              ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                              ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', "%{$search}%")->orWhere('serial_number', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('employee_name', fn($row) => $row->user->name ?? 'N/A')
            ->addColumn('equipment_name', fn($row) => $row->equipment->name ?? 'N/A')
            ->addColumn('admin_message_display', fn($row) => self::messageButton($row->admin_message))
            ->addColumn('action', function ($row) {
                if ($row->status === 'Pending') {
                    return '<div class="action-group">'
                        .self::approveButton('admin.requests.return.approve', $row->id)
                        .self::rejectButton($row, 'equipment')
                        .'</div>';
                }
                if ($row->status === 'Approved') {
                    return '<div class="action-group">'
                        .self::singleActionForm('admin.requests.return.complete', $row->id, 'complete', 'bi-check2-circle', 'Complete')
                        .'</div>';
                }
                return self::emptyCell();
            })
            ->editColumn('status', fn($row) => self::statusPill($row->status))
            ->editColumn('return_date', fn($row) => $row->return_date ? date('d-m-Y', strtotime($row->return_date)) : '—')
            ->rawColumns(['action', 'status', 'admin_message_display'])
            ->make(true);
    }

    public static function maintenanceLogsData($query){
        return DataTables::of($query)
            ->filter(function ($q) {
                if ($search = request()->get('search')['value'] ?? null) {
                    $q->where(function ($inner) use ($search) {
                        $inner->where('id', 'like', "%{$search}%")
                              ->orWhere('issue_description', 'like', "%{$search}%")
                              ->orWhere('technician_name', 'like', "%{$search}%")
                              ->orWhere('cost', 'like', "%{$search}%")
                              ->orWhere('repair_date', 'like', "%{$search}%")
                              ->orWhereHas('equipment', fn($e) => $e->where('name', 'like', "%{$search}%")->orWhere('serial_number', 'like', "%{$search}%"));
                    });
                }
            })
            ->addColumn('equipment_name', fn($row) => $row->equipment->name ?? 'N/A')
            ->addColumn('action', function ($row) {
                return '<div class="action-group">
                            <a href="'.route('admin.maintenance-logs.show', $row->id).'" class="action-btn view" title="View" aria-label="View"><i class="bi bi-eye"></i></a>
                        </div>';
            })
            ->editColumn('cost', fn($row) => '$'.number_format($row->cost, 2))
            ->editColumn('repair_date', fn($row) => $row->repair_date ? date('d-m-Y', strtotime($row->repair_date)) : '—')
            ->editColumn('created_at', fn($row) => $row->created_at ? date('d-m-Y', strtotime($row->created_at)) : '—')
            ->rawColumns(['action'])
            ->make(true);
    }
}
