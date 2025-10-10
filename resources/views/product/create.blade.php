@extends('layouts.app')
@section('title', __('product.add_new_product') . ' - سپیدار')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('product.add_new_product') - سپیدار</h1>
</section>

<!-- Main content -->
<section class="content">
    @php
    $form_class = empty($duplicate_product) ? 'create' : '';
    $is_image_required = !empty($common_settings['is_product_image_required']);
    @endphp
    {!! Form::open(['url' => action([\App\Http\Controllers\ProductController::class, 'store']), 'method' => 'post',
    'id' => 'product_add_form','class' => 'product_form ' . $form_class, 'files' => true ]) !!}
    @component('components.widget', ['class' => 'box-primary'])
    
    <!-- Basic Information Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-info-circle"></i> اطلاعات پایه کالا - سپیدار</h4>
            <hr>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('name', __('product.product_name') . ':*') !!}
                {!! Form::text('name', !empty($duplicate_product->name) ? $duplicate_product->name : null, ['class' => 'form-control', 'required',
                'placeholder' => __('product.product_name')]); !!}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('item_code', __('product.item_code') . ':*') !!} @show_tooltip(__('tooltip.item_code'))
                {!! Form::text('item_code', null, ['class' => 'form-control', 'required',
                'placeholder' => __('product.item_code')]); !!}
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('category_id', __('product.category') . ':*') !!}
                {!! Form::select('category_id', $categories, !empty($duplicate_product->category_id) ? $duplicate_product->category_id : null, ['class' => 'form-control select2', 'required', 'placeholder' => __('lang_v1.please_select')]); !!}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('item_type', __('product.item_type') . ':*') !!}
                {!! Form::select('item_type', [
                    'raw_material' => __('product.raw_material'),
                    'purchased_goods' => __('product.purchased_goods'),
                    'sale_goods' => __('product.sale_goods'),
                    'semi_finished' => __('product.semi_finished'),
                    'service' => __('product.service'),
                    'asset' => __('product.asset'),
                    'waste' => __('product.waste')
                ], 'sale_goods', ['class' => 'form-control', 'required']); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                <div class="input-group">
                    {!! Form::select('unit_id', $units, !empty($duplicate_product->unit_id) ? $duplicate_product->unit_id : session('business.default_unit'), ['class' => 'form-control select2', 'required']); !!}
                    <span class="input-group-btn">
                        <button type="button" @if(!auth()->user()->can('unit.create')) disabled @endif class="btn btn-default bg-white btn-flat btn-modal" data-href="{{action([\App\Http\Controllers\UnitController::class, 'create'], ['quick_add' => true])}}" title="@lang('unit.add_unit')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('brand_id', __('product.brand') . ':') !!}
                {!! Form::select('brand_id', $brands, !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id : null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.please_select')]); !!}
            </div>
        </div>
    </div>

    <!-- Stock Management Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-warehouse"></i> مدیریت موجودی - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('min_stock', __('product.min_stock') . ':') !!}
                {!! Form::number('min_stock', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0',
                'placeholder' => __('product.min_stock')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('max_stock', __('product.max_stock') . ':') !!}
                {!! Form::number('max_stock', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0',
                'placeholder' => __('product.max_stock')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('reorder_point', __('product.reorder_point') . ':') !!}
                {!! Form::number('reorder_point', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0',
                'placeholder' => __('product.reorder_point')]); !!}
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-money"></i> قیمت‌گذاری - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_purchase_price', __('product.default_purchase_price') . ':') !!}
                {!! Form::number('default_purchase_price', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0',
                'placeholder' => __('product.default_purchase_price')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_sales_price', __('product.default_sales_price') . ':') !!}
                {!! Form::number('default_sales_price', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0',
                'placeholder' => __('product.default_sales_price')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('profit_percentage', __('product.profit_percentage') . ':') !!}
                {!! Form::number('profit_percentage', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'max' => '100',
                'placeholder' => __('product.profit_percentage')]); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('discount_limit', __('product.discount_limit') . ':') !!}
                {!! Form::number('discount_limit', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'max' => '100',
                'placeholder' => __('product.discount_limit')]); !!}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('currency', __('product.currency') . ':') !!}
                {!! Form::select('currency', [
                    'USD' => 'USD - دلار آمریکا',
                    'EUR' => 'EUR - یورو',
                    'IRR' => 'IRR - ریال ایران',
                    'GBP' => 'GBP - پوند انگلیس'
                ], 'USD', ['class' => 'form-control']); !!}
            </div>
        </div>
    </div>

    <!-- Technical Specifications Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-cogs"></i> مشخصات فنی - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('dimensions', __('product.dimensions') . ':') !!}
                {!! Form::text('dimensions', null, ['class' => 'form-control',
                'placeholder' => __('product.dimensions')]); !!}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('color', __('product.color') . ':') !!}
                {!! Form::text('color', null, ['class' => 'form-control',
                'placeholder' => __('product.color')]); !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('model', __('product.model') . ':') !!}
                {!! Form::text('model', null, ['class' => 'form-control',
                'placeholder' => __('product.model')]); !!}
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('weight', __('product.weight') . ':') !!}
                {!! Form::number('weight', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0',
                'placeholder' => __('product.weight')]); !!}
            </div>
        </div>
    </div>

    <!-- Warehouse Location Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-map-marker"></i> موقعیت انبار - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_rack', __('product.default_rack') . ':') !!}
                {!! Form::text('default_rack', null, ['class' => 'form-control',
                'placeholder' => __('product.default_rack')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_row', __('product.default_row') . ':') !!}
                {!! Form::text('default_row', null, ['class' => 'form-control',
                'placeholder' => __('product.default_row')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_shelf', __('product.default_shelf') . ':') !!}
                {!! Form::text('default_shelf', null, ['class' => 'form-control',
                'placeholder' => __('product.default_shelf')]); !!}
            </div>
        </div>
    </div>

    <!-- Accounting Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-calculator"></i> حساب‌های حسابداری - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('inventory_account_id', __('product.inventory_account_id') . ':') !!}
                {!! Form::text('inventory_account_id', null, ['class' => 'form-control',
                'placeholder' => __('product.inventory_account_id')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('purchase_account_id', __('product.purchase_account_id') . ':') !!}
                {!! Form::text('purchase_account_id', null, ['class' => 'form-control',
                'placeholder' => __('product.purchase_account_id')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('sales_account_id', __('product.sales_account_id') . ':') !!}
                {!! Form::text('sales_account_id', null, ['class' => 'form-control',
                'placeholder' => __('product.sales_account_id')]); !!}
            </div>
        </div>
    </div>

    <!-- Control Settings Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-sliders"></i> تنظیمات کنترل - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('serial_required', 1, false, ['class' => 'input-icheck']); !!}
                        {{ __('product.serial_required') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('expiry_required', 1, false, ['class' => 'input-icheck']); !!}
                        {{ __('product.expiry_required') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('enable_stock', 1, true, ['class' => 'input-icheck']); !!}
                        {{ __('product.enable_stock') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Section -->
    <div class="row">
        <div class="col-sm-12">
            <h4><i class="fa fa-toggle-on"></i> وضعیت - سپیدار</h4>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('is_active', 1, true, ['class' => 'input-icheck']); !!}
                        {{ __('product.is_active') }}
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('not_for_selling', 1, false, ['class' => 'input-icheck']); !!}
                        {{ __('product.not_for_selling') }}
                    </label>
                </div>
            </div>
        </div>
    </div>

        <div class="clearfix"></div>
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                <div class="input-group">
                    {!! Form::select('unit_id', $units, !empty($duplicate_product->unit_id) ? $duplicate_product->unit_id : session('business.default_unit'), ['class' => 'form-control select2', 'required']); !!}
                    <span class="input-group-btn">
                        <button type="button" @if(!auth()->user()->can('unit.create')) disabled @endif class="btn btn-default bg-white btn-flat btn-modal" data-href="{{action([\App\Http\Controllers\UnitController::class, 'create'], ['quick_add' => true])}}" title="@lang('unit.add_unit')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-sm-4 @if(!session('business.enable_sub_units')) hide @endif">
            <div class="form-group">
                {!! Form::label('sub_unit_ids', __('lang_v1.related_sub_units') . ':') !!} @show_tooltip(__('lang_v1.sub_units_tooltip'))

                {!! Form::select('sub_unit_ids[]', [], !empty($duplicate_product->sub_unit_ids) ? $duplicate_product->sub_unit_ids : null, ['class' => 'form-control select2', 'multiple', 'id' => 'sub_unit_ids']); !!}
            </div>
        </div>
        @if(!empty($common_settings['enable_secondary_unit']))
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('secondary_unit_id', __('lang_v1.secondary_unit') . ':') !!} @show_tooltip(__('lang_v1.secondary_unit_help'))
                {!! Form::select('secondary_unit_id', $units, !empty($duplicate_product->secondary_unit_id) ? $duplicate_product->secondary_unit_id : null, ['class' => 'form-control select2']); !!}
            </div>
        </div>
        @endif

        <div class="col-sm-4 @if(!session('business.enable_brand')) hide @endif">
            <div class="form-group">
                {!! Form::label('brand_id', __('product.brand') . ':') !!}
                <div class="input-group">
                    {!! Form::select('brand_id', $brands, !empty($duplicate_product->brand_id) ? $duplicate_product->brand_id : null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
                    <span class="input-group-btn">
                        <button type="button" @if(!auth()->user()->can('brand.create')) disabled @endif class="btn btn-default bg-white btn-flat btn-modal" data-href="{{action([\App\Http\Controllers\BrandController::class, 'create'], ['quick_add' => true])}}" title="@lang('brand.add_brand')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-sm-4 @if(!session('business.enable_category')) hide @endif">
            <div class="form-group">
                {!! Form::label('category_id', __('product.category') . ':') !!}
                {!! Form::select('category_id', $categories, !empty($duplicate_product->category_id) ? $duplicate_product->category_id : null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
            </div>
        </div>

        <div class="col-sm-4 @if(!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
            <div class="form-group">
                {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                {!! Form::select('sub_category_id', $sub_categories, !empty($duplicate_product->sub_category_id) ? $duplicate_product->sub_category_id : null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
            </div>
        </div>

        @php
        $default_location = null;
        if(count($business_locations) == 1){
        $default_location = array_key_first($business_locations->toArray());
        }
        @endphp
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('product_locations', __('business.business_locations') . ':') !!} @show_tooltip(__('lang_v1.product_location_help'))
                {!! Form::select('product_locations[]', $business_locations, $default_location, ['class' => 'form-control select2', 'multiple', 'id' => 'product_locations']); !!}
            </div>
        </div>


        <div class="clearfix"></div>

        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label>
                    {!! Form::checkbox('enable_stock', 1, !empty($duplicate_product) ? $duplicate_product->enable_stock : true, ['class' => 'input-icheck', 'id' => 'enable_stock']); !!} <strong>@lang('product.manage_stock')</strong>
                </label>@show_tooltip(__('tooltip.enable_stock')) <p class="help-block"><i>@lang('product.enable_stock_help')</i></p>
            </div>
        </div>
        <div class="col-sm-4 @if(!empty($duplicate_product) && $duplicate_product->enable_stock == 0) hide @endif" id="alert_quantity_div">
            <div class="form-group">
                {!! Form::label('alert_quantity', __('product.alert_quantity') . ':') !!} @show_tooltip(__('tooltip.alert_quantity'))
                {!! Form::text('alert_quantity', !empty($duplicate_product->alert_quantity) ? @format_quantity($duplicate_product->alert_quantity) : null , ['class' => 'form-control input_number',
                'placeholder' => __('product.alert_quantity'), 'min' => '0']); !!}
            </div>
        </div>
        @if(!empty($common_settings['enable_product_warranty']))
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('warranty_id', __('lang_v1.warranty') . ':') !!}
                {!! Form::select('warranty_id', $warranties, null, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
            </div>
        </div>
        @endif
        <!-- include module fields -->
        @if(!empty($pos_module_data))
        @foreach($pos_module_data as $key => $value)
        @if(!empty($value['view_path']))
        @includeIf($value['view_path'], ['view_data' => $value['view_data']])
        @endif
        @endforeach
        @endif
        <div class="clearfix"></div>
        <div class="col-sm-8 mb-5">
            <div class="form-group">
                <div class="row">
                    <div class="col-sm-8 product-description-label">
                        {!! Form::label('product_description', __('lang_v1.product_description') . ':') !!}
                    </div> 
                </div>
                {!! Form::textarea('product_description', !empty($duplicate_product->product_description) ? $duplicate_product->product_description : null, ['class' => 'form-control']); !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
               
                <div class="row">
                    <div class="col-sm-6 image-label">
                    {!! Form::label('image', __('lang_v1.product_image') . ':') !!}
                    </div> 
                </div>
                {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*',
                'required' => $is_image_required, 'class' => 'upload-element']); !!}
                <small>
                    <p class="help-block">@lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)]) <br> @lang('lang_v1.aspect_ratio_should_be_1_1')</p>
                </small>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            {!! Form::label('product_brochure', __('lang_v1.product_brochure') . ':') !!}
            {!! Form::file('product_brochure', ['id' => 'product_brochure', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
            <small>
                <p class="help-block">
                    @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                    @includeIf('components.document_help_text')
                </p>
            </small>
        </div>
    </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary'])
    <div class="row">
        @if(session('business.enable_product_expiry'))

        @if(session('business.expiry_type') == 'add_expiry')
        @php
        $expiry_period = 12;
        $hide = true;
        @endphp
        @else
        @php
        $expiry_period = null;
        $hide = false;
        @endphp
        @endif
        <div class="col-sm-4 @if($hide) hide @endif">
            <div class="form-group">
                <div class="multi-input">
                    {!! Form::label('expiry_period', __('product.expires_in') . ':') !!}<br>
                    {!! Form::text('expiry_period', !empty($duplicate_product->expiry_period) ? @num_format($duplicate_product->expiry_period) : $expiry_period, ['class' => 'form-control pull-left input_number',
                    'placeholder' => __('product.expiry_period'), 'style' => 'width:60%;']); !!}
                    {!! Form::select('expiry_period_type', ['months'=>__('product.months'), 'days'=>__('product.days'), '' =>__('product.not_applicable') ], !empty($duplicate_product->expiry_period_type) ? $duplicate_product->expiry_period_type : 'months', ['class' => 'form-control select2 pull-left', 'style' => 'width:40%;', 'id' => 'expiry_period_type']); !!}
                </div>
            </div>
        </div>
        @endif

        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label>
                    {!! Form::checkbox('enable_sr_no', 1, !(empty($duplicate_product)) ? $duplicate_product->enable_sr_no : false, ['class' => 'input-icheck']); !!} <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
                </label> @show_tooltip(__('lang_v1.tooltip_sr_no'))
            </div>
        </div>
        
        <!-- Sepidar Stock Management Fields -->
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('min_stock', __('product.min_stock') . ':') !!} @show_tooltip(__('tooltip.min_stock'))
                {!! Form::text('min_stock', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.min_stock')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('max_stock', __('product.max_stock') . ':') !!} @show_tooltip(__('tooltip.max_stock'))
                {!! Form::text('max_stock', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.max_stock')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('reorder_point', __('product.reorder_point') . ':') !!} @show_tooltip(__('tooltip.reorder_point'))
                {!! Form::text('reorder_point', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.reorder_point')]); !!}
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label>
                    {!! Form::checkbox('not_for_selling', 1, !(empty($duplicate_product)) ? $duplicate_product->not_for_selling : false, ['class' => 'input-icheck']); !!} <strong>@lang('lang_v1.not_for_selling')</strong>
                </label> @show_tooltip(__('lang_v1.tooltip_not_for_selling'))
            </div>
        </div>

        <div class="clearfix"></div>

        <!-- Rack, Row & position number -->
        @if(session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
        <div class="col-md-12">
            <h4>@lang('lang_v1.rack_details'):
                @show_tooltip(__('lang_v1.tooltip_rack_details'))
            </h4>
        </div>
        @foreach($business_locations as $id => $location)
        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('rack_' . $id, $location . ':') !!}

                @if(session('business.enable_racks'))
                {!! Form::text('product_racks[' . $id . '][rack]', !empty($rack_details[$id]['rack']) ? $rack_details[$id]['rack'] : null, ['class' => 'form-control', 'id' => 'rack_' . $id,
                'placeholder' => __('lang_v1.rack')]); !!}
                @endif

                @if(session('business.enable_row'))
                {!! Form::text('product_racks[' . $id . '][row]', !empty($rack_details[$id]['row']) ? $rack_details[$id]['row'] : null, ['class' => 'form-control', 'placeholder' => __('lang_v1.row')]); !!}
                @endif

                @if(session('business.enable_position'))
                {!! Form::text('product_racks[' . $id . '][position]', !empty($rack_details[$id]['position']) ? $rack_details[$id]['position'] : null, ['class' => 'form-control', 'placeholder' => __('lang_v1.position')]); !!}
                @endif
            </div>
        </div>
        @endforeach
        @endif

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('weight', __('lang_v1.weight') . ':') !!}
                {!! Form::text('weight', !empty($duplicate_product->weight) ? $duplicate_product->weight : null, ['class' => 'form-control', 'placeholder' => __('lang_v1.weight')]); !!}
            </div>
        </div>
        
        <!-- Sepidar Pricing Fields -->
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_purchase_price', __('product.default_purchase_price') . ':') !!}
                {!! Form::text('default_purchase_price', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.default_purchase_price')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('default_sales_price', __('product.default_sales_price') . ':') !!}
                {!! Form::text('default_sales_price', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.default_sales_price')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('profit_percentage', __('product.profit_percentage') . ':') !!}
                {!! Form::text('profit_percentage', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.profit_percentage')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('discount_limit', __('product.discount_limit') . ':') !!}
                {!! Form::text('discount_limit', null, ['class' => 'form-control input_number',
                'placeholder' => __('product.discount_limit')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('currency', __('product.currency') . ':') !!}
                {!! Form::select('currency', [
                    'USD' => 'USD',
                    'EUR' => 'EUR',
                    'IRR' => 'IRR',
                    'AED' => 'AED'
                ], 'USD', ['class' => 'form-control select2']); !!}
            </div>
        </div>
        
        <!-- Sepidar Technical Specifications -->
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('dimensions', __('product.dimensions') . ':') !!}
                {!! Form::text('dimensions', null, ['class' => 'form-control',
                'placeholder' => __('product.dimensions')]); !!}
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('color', __('product.color') . ':') !!}
                {!! Form::text('color', null, ['class' => 'form-control',
                'placeholder' => __('product.color')]); !!}
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('model', __('product.model') . ':') !!}
                {!! Form::text('model', null, ['class' => 'form-control',
                'placeholder' => __('product.model')]); !!}
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('default_rack', __('product.default_rack') . ':') !!}
                {!! Form::text('default_rack', null, ['class' => 'form-control',
                'placeholder' => __('product.default_rack')]); !!}
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('default_row', __('product.default_row') . ':') !!}
                {!! Form::text('default_row', null, ['class' => 'form-control',
                'placeholder' => __('product.default_row')]); !!}
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('default_shelf', __('product.default_shelf') . ':') !!}
                {!! Form::text('default_shelf', null, ['class' => 'form-control',
                'placeholder' => __('product.default_shelf')]); !!}
            </div>
        </div>
        
        <!-- Sepidar Account Fields -->
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('inventory_account_id', __('product.inventory_account_id') . ':') !!}
                {!! Form::text('inventory_account_id', null, ['class' => 'form-control',
                'placeholder' => __('product.inventory_account_id')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('purchase_account_id', __('product.purchase_account_id') . ':') !!}
                {!! Form::text('purchase_account_id', null, ['class' => 'form-control',
                'placeholder' => __('product.purchase_account_id')]); !!}
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('sales_account_id', __('product.sales_account_id') . ':') !!}
                {!! Form::text('sales_account_id', null, ['class' => 'form-control',
                'placeholder' => __('product.sales_account_id')]); !!}
            </div>
        </div>
        
        <!-- Sepidar Status Fields -->
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label>
                    {!! Form::checkbox('serial_required', 1, false, ['class' => 'input-icheck']); !!} <strong>@lang('product.serial_required')</strong>
                </label>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label>
                    {!! Form::checkbox('expiry_required', 1, false, ['class' => 'input-icheck']); !!} <strong>@lang('product.expiry_required')</strong>
                </label>
            </div>
        </div>
        
        <div class="col-sm-4">
            <div class="form-group">
                <br>
                <label>
                    {!! Form::checkbox('is_active', 1, true, ['class' => 'input-icheck']); !!} <strong>@lang('product.is_active')</strong>
                </label>
            </div>
        </div>
        @php
        $custom_labels = json_decode(session('business.custom_labels'), true);
        $product_custom_fields = !empty($custom_labels['product']) ? $custom_labels['product'] : [];
        $product_cf_details = !empty($custom_labels['product_cf_details']) ? $custom_labels['product_cf_details'] : [];

        @endphp
        <!--custom fields-->
        <div class="clearfix"></div>

        @foreach($product_custom_fields as $index => $cf)
            @if(!empty($cf))
                @php
                    $db_field_name = 'product_custom_field' . $loop->iteration;
                    $cf_type = !empty($product_cf_details[$loop->iteration]['type']) ? $product_cf_details[$loop->iteration]['type'] : 'text';
                    $dropdown = !empty($product_cf_details[$loop->iteration]['dropdown_options']) ? explode(PHP_EOL, $product_cf_details[$loop->iteration]['dropdown_options']) : [];
                @endphp

                <div class="col-sm-3">
                    <div class="form-group">
                        {!! Form::label($db_field_name, $cf . ':') !!}

                        @if(in_array($cf_type, ['text', 'date']))
                        
                            <input type="{{$cf_type}}" name="{{$db_field_name}}" id="{{$db_field_name}}" value="{{!empty($duplicate_product->$db_field_name) ? $duplicate_product->$db_field_name : null}}" class="form-control" placeholder="{{$cf}}">

                        @elseif($cf_type == 'dropdown')
                            <!-- {!! Form::select($db_field_name, $dropdown, !empty($duplicate_product->$db_field_name) ? $duplicate_product->$db_field_name : null, ['placeholder' => $cf, 'class' => 'form-control select2']); !!} -->
                            <select name="{{ $db_field_name }}" id="{{ $db_field_name }}" class="form-control select2">
                                <option value="">{{ $cf }}</option>
                                @foreach($dropdown as $option)
                                    <option value="{{ $option }}" @if(!empty($duplicate_product->$db_field_name) && $option == $duplicate_product->$db_field_name) selected @endif>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            @endif
        @endforeach

        <div class="col-sm-3">
            <div class="form-group">
                {!! Form::label('preparation_time_in_minutes', __('lang_v1.preparation_time_in_minutes') . ':') !!}
                {!! Form::number('preparation_time_in_minutes', !empty($duplicate_product->preparation_time_in_minutes) ? $duplicate_product->preparation_time_in_minutes : null, ['class' => 'form-control', 'placeholder' => __('lang_v1.preparation_time_in_minutes')]); !!}
            </div>
        </div>
        <!--custom fields-->
        <div class="clearfix"></div>
        @include('layouts.partials.module_form_part')
    </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary'])
    <div class="row">

        <div class="col-sm-4 @if(!session('business.enable_price_tax')) hide @endif">
            <div class="form-group">
                {!! Form::label('tax', __('product.applicable_tax') . ':') !!}
                {!! Form::select('tax', $taxes, !empty($duplicate_product->tax) ? $duplicate_product->tax : null, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'], $tax_attributes); !!}
            </div>
        </div>

        <div class="col-sm-4 @if(!session('business.enable_price_tax')) hide @endif">
            <div class="form-group">
                {!! Form::label('tax_type', __('product.selling_price_tax_type') . ':*') !!}
                {!! Form::select('tax_type', ['inclusive' => __('product.inclusive'), 'exclusive' => __('product.exclusive')], !empty($duplicate_product->tax_type) ? $duplicate_product->tax_type : 'exclusive',
                ['class' => 'form-control select2', 'required']); !!}
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="col-sm-4">
            <div class="form-group">
                {!! Form::label('type', __('product.product_type') . ':*') !!} @show_tooltip(__('tooltip.product_type'))
                {!! Form::select('type', $product_types, !empty($duplicate_product->type) ? $duplicate_product->type : null, ['class' => 'form-control select2',
                'required', 'data-action' => !empty($duplicate_product) ? 'duplicate' : 'add', 'data-product_id' => !empty($duplicate_product) ? $duplicate_product->id : '0']); !!}
            </div>
        </div>

        <div class="form-group col-sm-12" id="product_form_part">
            @include('product.partials.single_product_form_part', ['profit_percent' => $default_profit_percent])
        </div>

        <input type="hidden" id="variation_counter" value="1">
        <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">

    </div>
    @endcomponent
    <div class="row">
        <div class="col-sm-12">
            <input type="hidden" name="submit_type" id="submit_type">
            <div class="text-center">
                <div class="btn-group">
                    @if($selling_price_group_count)
                    <button type="submit" value="submit_n_add_selling_prices" class="tw-dw-btn tw-dw-btn-warning tw-dw-btn-lg tw-text-white submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                    @endif

                    @can('product.opening_stock')
                    <button id="opening_stock_button" @if(!empty($duplicate_product) && $duplicate_product->enable_stock == 0) disabled @endif type="submit" value="submit_n_add_opening_stock" class="tw-dw-btn tw-dw-btn-lg tw-text-white bg-purple submit_product_form">@lang('lang_v1.save_n_add_opening_stock')</button>
                    @endcan

                    <button type="submit" value="save_n_add_another" class="tw-dw-btn tw-dw-btn-lg bg-maroon submit_product_form">@lang('lang_v1.save_n_add_another')</button>

                    <button type="submit" value="submit" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white submit_product_form">@lang('messages.save')</button>
                </div>

            </div>
        </div>
    </div>
    {!! Form::close() !!}

</section>
<!-- /.content -->

@endsection

@section('javascript')

<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        __page_leave_confirmation('#product_add_form');
        onScan.attachTo(document, {
            suffixKeyCodes: [13], // enter-key expected at the end of a scan
            reactToPaste: true, // Compatibility to built-in scanners in paste-mode (as opposed to keyboard-mode)
            onScan: function(sCode, iQty) {
                $('input#sku').val(sCode);
            },
            onScanError: function(oDebug) {
                console.log(oDebug);
            },
            minLength: 2,
            ignoreIfFocusOn: ['input', '.form-control']
            // onKeyDetect: function(iKeyCode){ // output all potentially relevant key events - great for debugging!
            //     console.log('Pressed: ' + iKeyCode);
            // }
        });
    });
</script>
@endsection