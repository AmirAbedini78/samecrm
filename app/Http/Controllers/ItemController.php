<?php

namespace App\Http\Controllers;

use App\Product;
use App\BusinessLocation;
use App\Category;
use App\Brand;
use App\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    /**
     * Display a listing of items (Sepidar style).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('product.view')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $items = Product::where('business_id', $business_id)
                ->with(['category', 'brand', 'unit'])
                ->select([
                    'products.id',
                    'products.name',
                    'products.item_code',
                    'products.item_type',
                    'products.category_id',
                    'products.brand_id',
                    'products.unit_id',
                    'products.min_stock',
                    'products.max_stock',
                    'products.reorder_point',
                    'products.default_purchase_price',
                    'products.default_sales_price',
                    'products.currency',
                    'products.dimensions',
                    'products.color',
                    'products.model',
                    'products.serial_required',
                    'products.expiry_required',
                    'products.is_active',
                ]);

            return Datatables::of($items)
                ->addColumn('item_type_text', function ($row) {
                    $types = [
                        'raw_material' => __('product.raw_material'),
                        'purchased_goods' => __('product.purchased_goods'),
                        'sale_goods' => __('product.sale_goods'),
                        'semi_finished' => __('product.semi_finished'),
                        'service' => __('product.service'),
                        'asset' => __('product.asset'),
                        'waste' => __('product.waste')
                    ];
                    return $types[$row->item_type] ?? $row->item_type;
                })
                ->addColumn('category_name', function ($row) {
                    return $row->category ? $row->category->name : '-';
                })
                ->addColumn('brand_name', function ($row) {
                    return $row->brand ? $row->brand->name : '-';
                })
                ->addColumn('unit_name', function ($row) {
                    return $row->unit ? $row->unit->short_name : '-';
                })
                ->addColumn('stock_status', function ($row) {
                    if ($row->min_stock > 0) {
                        return '<span class="label label-info">' . __('product.min_stock') . ': ' . $row->min_stock . '</span>';
                    }
                    return '<span class="label label-success">' . __('lang_v1.available') . '</span>';
                })
                ->addColumn('price_display', function ($row) {
                    $purchase = $row->default_purchase_price ? number_format($row->default_purchase_price, 2) : '-';
                    $sales = $row->default_sales_price ? number_format($row->default_sales_price, 2) : '-';
                    return 'خرید: ' . $purchase . '<br>فروش: ' . $sales;
                })
                ->addColumn('technical_specs', function ($row) {
                    $specs = [];
                    if ($row->dimensions) $specs[] = 'ابعاد: ' . $row->dimensions;
                    if ($row->color) $specs[] = 'رنگ: ' . $row->color;
                    if ($row->model) $specs[] = 'مدل: ' . $row->model;
                    return implode('<br>', $specs) ?: '-';
                })
                ->addColumn('controls', function ($row) {
                    $controls = [];
                    if ($row->serial_required) $controls[] = '<span class="label label-primary">سریال</span>';
                    if ($row->expiry_required) $controls[] = '<span class="label label-warning">انقضا</span>';
                    return implode(' ', $controls) ?: '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_active ? 
                        '<span class="label label-success">' . __('lang_v1.active') . '</span>' : 
                        '<span class="label label-danger">' . __('lang_v1.inactive') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">'
                        . '<button type="button" data-href="' . action([\App\Http\Controllers\ItemController::class, 'show'], [$row->id]) . '" class="btn btn-xs btn-info btn-modal" data-container=".item_modal">'
                        . '<i class="fa fa-eye"></i> ' . __('messages.view') . '</button>'
                        . ' <a href="' . action([\App\Http\Controllers\ProductController::class, 'edit'], [$row->id]) . '" class="btn btn-xs btn-primary">'
                        . '<i class="fa fa-edit"></i> ' . __('messages.edit') . '</a>'
                        . '</div>';
                })
                // Sepidar item report fields with safe defaults
                ->addColumn('date', function($row){ return $row->date ?? '-'; })
                ->addColumn('document_number', function($row){ return $row->document_number ?? '-'; })
                ->addColumn('document_type', function($row){ return $row->document_type ?? '-'; })
                ->addColumn('base_document_number', function($row){ return $row->base_document_number ?? '-'; })
                ->addColumn('tracking_number', function($row){ return $row->tracking_number ?? '-'; })
                ->addColumn('quantity', function($row){ return isset($row->quantity) ? number_format($row->quantity, 2) : '-'; })
                ->addColumn('warehouse_stock', function($row){ return isset($row->warehouse_stock) ? number_format($row->warehouse_stock, 2) : '-'; })
                ->addColumn('unit_price', function($row){ return isset($row->unit_price) ? number_format($row->unit_price, 2) : '-'; })
                ->addColumn('final_unit_price', function($row){ return isset($row->final_unit_price) ? number_format($row->final_unit_price, 2) : '-'; })
                ->addColumn('final_amount', function($row){ return isset($row->final_amount) ? number_format($row->final_amount, 2) : '-'; })
                ->addColumn('duties', function($row){ return isset($row->duties) ? number_format($row->duties, 2) : '-'; })
                ->addColumn('shipping_cost', function($row){ return isset($row->shipping_cost) ? number_format($row->shipping_cost, 2) : '-'; })
                ->addColumn('shipping_tax', function($row){ return isset($row->shipping_tax) ? number_format($row->shipping_tax, 2) : '-'; })
                ->addColumn('net_amount', function($row){ return isset($row->net_amount) ? number_format($row->net_amount, 2) : '-'; })
                ->addColumn('exchange_rate', function($row){ return isset($row->exchange_rate) ? number_format($row->exchange_rate, 6) : '-'; })
                ->addColumn('currency_amount', function($row){ return isset($row->currency_amount) ? number_format($row->currency_amount, 2) : '-'; })
                ->addColumn('currency_tax', function($row){ return isset($row->currency_tax) ? number_format($row->currency_tax, 2) : '-'; })
                ->addColumn('currency_duties', function($row){ return isset($row->currency_duties) ? number_format($row->currency_duties, 2) : '-'; })
                ->addColumn('agreed_return_price', function($row){ return isset($row->agreed_return_price) ? number_format($row->agreed_return_price, 2) : '-'; })
                ->addColumn('agreed_return_amount', function($row){ return isset($row->agreed_return_amount) ? number_format($row->agreed_return_amount, 2) : '-'; })
                ->addColumn('net_agreed_return', function($row){ return isset($row->net_agreed_return) ? number_format($row->net_agreed_return, 2) : '-'; })
                ->addColumn('month', function($row){ return $row->month ?? '-'; })
                ->addColumn('description', function($row){ return $row->description ?? '-'; })
                ->rawColumns(['stock_status', 'price_display', 'technical_specs', 'controls', 'status', 'action'])
                ->make(true);
            } catch (\Throwable $e) {
                \Log::error('Items AJAX error: '.$e->getMessage().' File:'.$e->getFile().' Line:'.$e->getLine());
                $draw = (int) request()->input('draw', 1);
                $fallback = Product::select('id','name','sku as item_code')
                    ->where('business_id', request()->session()->get('user.business_id'))
                    ->take(50)->get()->map(function($p){
                        return [
                            'name' => $p->name,
                            'item_code' => $p->item_code,
                            'item_type_text' => '-',
                            'category_name' => '-',
                            'brand_name' => '-',
                            'unit_name' => '-',
                            'stock_status' => '-',
                            'price_display' => '-',
                            'technical_specs' => '-',
                            'controls' => '-',
                            'status' => '-',
                            'date' => '-',
                            'document_number' => '-',
                            'document_type' => '-',
                            'base_document_number' => '-',
                            'tracking_number' => '-',
                            'quantity' => '-',
                            'warehouse_stock' => '-',
                            'unit_price' => '-',
                            'final_unit_price' => '-',
                            'final_amount' => '-',
                            'duties' => '-',
                            'shipping_cost' => '-',
                            'shipping_tax' => '-',
                            'net_amount' => '-',
                            'exchange_rate' => '-',
                            'currency_amount' => '-',
                            'currency_tax' => '-',
                            'currency_duties' => '-',
                            'agreed_return_price' => '-',
                            'agreed_return_amount' => '-',
                            'net_agreed_return' => '-',
                            'month' => '-',
                            'description' => '-',
                            'action' => ''
                        ];
                    });
                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => $fallback->count(),
                    'recordsFiltered' => $fallback->count(),
                    'data' => $fallback,
                ]);
            }
        }

        return view('item.index');
    }

    /**
     * Show the form for creating a new item.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        
        $categories = Category::forDropdown($business_id);
        $brands = Brand::forDropdown($business_id);
        $units = Unit::forDropdown($business_id);
        
        return view('item.create', compact('categories', 'brands', 'units'));
    }

    /**
     * Store a newly created item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('product.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $business_id = $request->session()->get('user.business_id');

            $input = $request->only([
                'name', 'item_code', 'item_type', 'category_id', 'brand_id', 'unit_id',
                'min_stock', 'max_stock', 'reorder_point', 'default_purchase_price', 
                'default_sales_price', 'currency', 'dimensions', 'color', 'model',
                'serial_required', 'expiry_required', 'is_active'
            ]);

            $input['business_id'] = $business_id;
            $input['type'] = 'single';
            $input['enable_stock'] = 1;
            $input['created_by'] = auth()->user()->id;
            $input['sku'] = $input['item_code'] ?? 'ITEM-' . time();
            $input['barcode_type'] = 'C128';

            // Convert checkboxes to boolean
            $input['serial_required'] = $request->has('serial_required') ? 1 : 0;
            $input['expiry_required'] = $request->has('expiry_required') ? 1 : 0;
            $input['is_active'] = $request->has('is_active') ? 1 : 0;

            $item = Product::create($input);

            $output = [
                'success' => true,
                'msg' => __('product.product_added_success'),
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
     * Display the specified item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! auth()->user()->can('product.view')) {
            abort(403, 'Unauthorized action.');
        }

        $item = Product::with(['category', 'brand', 'unit'])->findOrFail($id);
        
        return view('item.show', compact('item'));
    }
}
