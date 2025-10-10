<?php

namespace App\Http\Controllers;

use App\Warehouse;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    /**
     * Display a listing of warehouses (Sepidar style).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('business_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $warehouses = Warehouse::where('warehouses.business_id', $business_id)
                ->with(['keeper'])
                ->select([
                    'warehouses.id',
                    'warehouses.name',
                    'warehouses.warehouse_code',
                    'warehouses.warehouse_type',
                    'warehouses.keeper_id',
                    'warehouses.capacity',
                    'warehouses.capacity_unit',
                    'warehouses.storage_address',
                    'warehouses.description',
                    'warehouses.is_active',
                    // Aggregates (Sepidar-style)
                    'warehouses.beginning_period_quantity',
                    'warehouses.beginning_period_amount',
                    'warehouses.input_quantity',
                    'warehouses.input_amount',
                    'warehouses.output_quantity',
                    'warehouses.output_amount',
                    'warehouses.net_quantity',
                    'warehouses.stock_amount'
                ]);

            return Datatables::of($warehouses)
                ->addColumn('warehouse_type_text', function ($row) {
                    $types = [
                        'central' => __('business.central'),
                        'branch' => __('business.branch'),
                        'consignment' => __('business.consignment'),
                        'quarantine' => __('business.quarantine'),
                        'waste' => __('business.waste'),
                        'temporary' => __('business.temporary')
                    ];
                    return $types[$row->warehouse_type] ?? $row->warehouse_type;
                })
                ->addColumn('keeper_name', function ($row) {
                    return $row->keeper ? $row->keeper->first_name . ' ' . $row->keeper->last_name : '-';
                })
                ->addColumn('capacity_display', function ($row) {
                    return $row->capacity ? $row->capacity . ' ' . $row->capacity_unit : '-';
                })
                ->addColumn('begin_qty', function($row){ return number_format($row->beginning_period_quantity, 2); })
                ->addColumn('begin_amt', function($row){ return number_format($row->beginning_period_amount, 2); })
                ->addColumn('in_qty', function($row){ return number_format($row->input_quantity, 2); })
                ->addColumn('in_amt', function($row){ return number_format($row->input_amount, 2); })
                ->addColumn('out_qty', function($row){ return number_format($row->output_quantity, 2); })
                ->addColumn('out_amt', function($row){ return number_format($row->output_amount, 2); })
                ->addColumn('net_qty', function($row){ return number_format($row->net_quantity, 2); })
                ->addColumn('stock_amt', function($row){ return number_format($row->stock_amount, 2); })
                ->addColumn('status', function ($row) {
                    return $row->is_active ? 
                        '<span class="label label-success">' . __('lang_v1.active') . '</span>' : 
                        '<span class="label label-danger">' . __('lang_v1.inactive') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">'
                        . '<button type="button" data-href="' . action([\App\Http\Controllers\WarehouseController::class, 'show'], [$row->id]) . '" class="btn btn-xs btn-info btn-modal" data-container=".warehouse_modal">'
                        . '<i class="fa fa-eye"></i> ' . __('messages.view') . '</button>'
                        . ' <button type="button" data-href="' . action([\App\Http\Controllers\BusinessLocationController::class, 'edit'], [$row->id]) . '" class="btn btn-xs btn-primary btn-modal" data-container=".location_edit_modal">'
                        . '<i class="fa fa-edit"></i> ' . __('messages.edit') . '</button>'
                        . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('warehouse.index');
    }

    /**
     * Show the form for creating a new warehouse.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('business_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        $users = User::pluck('first_name', 'id');
        
        return view('warehouse.create', compact('users'));
    }

    /**
     * Store a newly created warehouse.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('business_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            $input = $request->only([
                'name', 'warehouse_code', 'warehouse_type', 'keeper_id', 
                'capacity', 'capacity_unit', 'storage_address', 'description',
                'inventory_account_id', 'issue_account_id'
            ]);

            $input['business_id'] = $business_id;
            $input['is_active'] = 1;

            $warehouse = Warehouse::create($input);

            $output = [
                'success' => true,
                'msg' => __('business.business_location_added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = [
                'success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Display the specified warehouse.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! auth()->user()->can('business_settings.access')) {
            abort(403, 'Unauthorized action.');
        }

        $warehouse = Warehouse::with(['keeper'])->findOrFail($id);
        
        return view('warehouse.show', compact('warehouse'));
    }
}
