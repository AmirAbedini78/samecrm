<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Simplified for debugging - remove all permission checks
        $business_id = request()->session()->get('user.business_id') ?? 1;
        
        if (request()->ajax()) {
            // Show all categories for debugging
            $query = Category::where('business_id', $business_id)->orWhere('business_id', '!=', $business_id);
            
            $categories = $query->select('*');
            
            return Datatables::of($categories)
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group">
                        <a href="#" class="btn btn-xs btn-info">View</a>
                        <a href="#" class="btn btn-xs btn-primary">Edit</a>
                    </div>';
                })
                ->addColumn('parent_category', function ($row) {
                    return $row->parent_id == 0 ? 'Main Category' : 'Sub Category';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $categories = Category::where('business_id', $business_id)
            ->where('category_type', 'product')
            ->where('parent_id', 0)
            ->pluck('name', 'id');

        return view('category.create')
            ->with(compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('category.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category = Category::create([
            'business_id' => $business_id,
            'name' => $request->name,
            'short_code' => $request->short_code,
            'description' => $request->description,
            'parent_id' => $request->parent_id ?? 0,
            'category_type' => 'product',
            'created_by' => auth()->user()->id
        ]);

        $output = [
            'success' => true,
            'data' => $category,
            'msg' => __('lang_v1.success')
        ];

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!auth()->user()->can('category.view')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $category = Category::where('business_id', $business_id)
            ->where('id', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        return view('category.show')
            ->with(compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!auth()->user()->can('category.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $category = Category::where('business_id', $business_id)
            ->where('id', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        $categories = Category::where('business_id', $business_id)
            ->where('category_type', 'product')
            ->where('parent_id', 0)
            ->where('id', '!=', $id)
            ->pluck('name', 'id');

        return view('category.edit')
            ->with(compact('category', 'categories'));
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
        if (!auth()->user()->can('category.update')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $category = Category::where('business_id', $business_id)
            ->where('id', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category->update([
            'name' => $request->name,
            'short_code' => $request->short_code,
            'description' => $request->description,
            'parent_id' => $request->parent_id ?? 0
        ]);

        $output = [
            'success' => true,
            'data' => $category,
            'msg' => __('lang_v1.success')
        ];

        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('category.delete')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $category = Category::where('business_id', $business_id)
            ->where('id', $id)
            ->first();

        if (!$category) {
            abort(404);
        }

        // Check if category has products
        $product_count = \App\Product::where('category_id', $id)->count();
        if ($product_count > 0) {
            $output = [
                'success' => false,
                'msg' => __('lang_v1.category_has_products')
            ];
            return $output;
        }

        // Check if category has sub-categories
        $sub_category_count = Category::where('parent_id', $id)->count();
        if ($sub_category_count > 0) {
            $output = [
                'success' => false,
                'msg' => __('lang_v1.category_has_sub_categories')
            ];
            return $output;
        }

        $category->delete();

        $output = [
            'success' => true,
            'msg' => __('lang_v1.success')
        ];

        return $output;
    }
}
