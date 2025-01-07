<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $data = AuditLog::query();

        if ($request->has('user_id') && !empty($request->user_id)) {
            $data->whereIn('user_id', $request->user_id);
        }

        if ($request->has('model') && !empty($request->model)) {
            $data->whereIn('model_type', $request->model);
        }

        if ($request->has('date_range') && !empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $startDate = $dates[0];
                $endDate = $dates[1];
                $data->whereBetween('performed_at', [$startDate, $endDate]);
            }
        }

        if ($request->has('display') && $request->display == 'on') {
            return DataTables::of($data)
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('action', function ($row) {
                    return $row->action;
                })
                ->addColumn('model_type', function ($row) {
                    return class_basename($row->model_type);
                })
                ->addColumn('model_id', function ($row) {
                    return $row->model_id;
                })
                ->addColumn('user_name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('changes', fn($row) => $this->formatChanges(json_decode($row->changes, true)))
                ->addColumn('actions', function ($row) {

                    return '
                    <a href="#" title="Edit Models" data-url="' . route('brands.edit', [$row->id]) . '" data-size="small" data-ajax-popup="true"
                        data-title="' . __('Edit Model') . '" data-bs-toggle="tooltip">
                        <i class="fas fa-edit text-info font-18"></i>
                    </a>
                    &nbsp;&nbsp;
                    <a href="#" title="Delete" onclick="handleAction(' . $row->id . ', \'delete\')" data-bs-toggle="tooltip">
                        <i class="fa fa-trash text-danger font-18"></i>
                    </a>';

                })
                ->rawColumns(['actions', 'changes']) 
                ->toJson();
        } else {
            $auditLogs = $query->get();

            return response()->json($auditLogs);
        }
    }
    private function formatChanges($changes)
    {
        $formattedChanges = '';
        foreach ($changes as $field => $change) {
            $formattedChanges .= "{$field}: {$change}<br>";
        }
        return $formattedChanges ?  $formattedChanges : 'N/A' ;
    }
}
