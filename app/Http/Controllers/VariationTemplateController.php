<?php

namespace App\Http\Controllers;

use App\ProductVariation;
use App\Variation;
use App\VariationTemplate;
use App\VariationValueTemplate;
use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VariationTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            // Comprehensive fix: Use Datatables::of() with proper column mapping
            $business_id = request()->session()->get('user.business_id') ?? 1;

            $variations = Variation::join('products', 'variations.product_id', '=', 'products.id')
                ->where('products.business_id', $business_id)
                ->select([
                    'variations.id',
                    'variations.name',
                    'products.name as product_name',
                    'variations.sub_sku'
                ]);

            return Datatables::of($variations)
                ->addColumn('name', function ($row) {
                    return $row->name ?: '';
                })
                ->addColumn('product_name', function ($row) {
                    return $row->product_name ?: '';
                })
                ->addColumn('sub_sku', function ($row) {
                    return $row->sub_sku ?: '';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">
                        <a href="#" class="btn btn-xs btn-info">View</a>
                        <a href="#" class="btn btn-xs btn-primary">Edit</a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->removeColumn('id')
                ->removeColumn('product_id')
                ->make(true);
        }

        return view('variation.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('variation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->only(['name']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $variation = VariationTemplate::create($input);

            //craete variation values
            if (! empty($request->input('variation_values'))) {
                $values = $request->input('variation_values');
                $data = [];
                foreach ($values as $value) {
                    if (! empty($value)) {
                        $data[] = ['name' => $value];
                    }
                }
                $variation->values()->createMany($data);
            }

            $output = ['success' => true,
                'data' => $variation,
                'msg' => 'Variation added succesfully',
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => 'Something went wrong, please try again',
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VariationTemplate  $variationTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(VariationTemplate $variationTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $variation = VariationTemplate::where('business_id', $business_id)
                            ->with(['values'])->find($id);

            return view('variation.edit')
                ->with(compact('variation'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (request()->ajax()) {
            try {
                $input = $request->only(['name']);
                $business_id = $request->session()->get('user.business_id');

                $variation = VariationTemplate::where('business_id', $business_id)->findOrFail($id);

                if ($variation->name != $input['name']) {
                    $variation->name = $input['name'];
                    $variation->save();

                    ProductVariation::where('variation_template_id', $variation->id)
                                ->update(['name' => $variation->name]);
                }

                //update variation
                $data = [];
                if (! empty($request->input('edit_variation_values'))) {
                    $values = $request->input('edit_variation_values');
                    foreach ($values as $key => $value) {
                        if (! empty($value)) {
                            $variation_val = VariationValueTemplate::find($key);

                            if ($variation_val->name != $value) {
                                $variation_val->name = $value;
                                $data[] = $variation_val;
                                Variation::where('variation_value_id', $key)
                                    ->update(['name' => $value]);
                            }
                        }
                    }
                    $variation->values()->saveMany($data);
                }
                if (! empty($request->input('variation_values'))) {
                    $values = $request->input('variation_values');
                    foreach ($values as $value) {
                        if (! empty($value)) {
                            $data[] = new VariationValueTemplate(['name' => $value]);
                        }
                    }
                }
                $variation->values()->saveMany($data);

                $output = ['success' => true,
                    'msg' => 'Variation updated succesfully',
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => 'Something went wrong, please try again',
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            try {
                $business_id = request()->session()->get('user.business_id');

                $variation = VariationTemplate::where('business_id', $business_id)->findOrFail($id);
                $variation->delete();

                $output = ['success' => true,
                    'msg' => 'Category deleted succesfully',
                ];
            } catch (\Eexception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => 'Something went wrong, please try again',
                ];
            }

            return $output;
        }
    }
}
