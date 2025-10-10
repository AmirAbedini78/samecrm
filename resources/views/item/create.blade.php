<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action([\App\Http\Controllers\ItemController::class, 'store']), 'method' => 'post', 'id' => 'item_add_form' ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('product.add_item') - سپیدار</h4>
        </div>

        <div class="modal-body">
            <!-- Basic Information Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-info-circle"></i> اطلاعات پایه کالا</h4>
                    <hr>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name', __('product.name') . ':*') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('product.name')]); !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('item_code', __('product.item_code') . ':*') !!}
                        {!! Form::text('item_code', null, ['class' => 'form-control', 'required', 'placeholder' => __('product.item_code')]); !!}
                    </div>
                </div>
            </div>

            <div class="row">
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

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('category_id', __('product.category') . ':*') !!}
                        {!! Form::select('category_id', $categories, null, ['class' => 'form-control select2', 'required', 'placeholder' => __('lang_v1.please_select')]); !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('brand_id', __('product.brand') . ':') !!}
                        {!! Form::select('brand_id', $brands, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.please_select')]); !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                        {!! Form::select('unit_id', $units, null, ['class' => 'form-control select2', 'required', 'placeholder' => __('lang_v1.please_select')]); !!}
                    </div>
                </div>
            </div>

            <!-- Stock Management Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-cubes"></i> مدیریت موجودی</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('min_stock', __('product.min_stock') . ':') !!}
                        {!! Form::number('min_stock', 0, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => __('product.min_stock')]); !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('max_stock', __('product.max_stock') . ':') !!}
                        {!! Form::number('max_stock', 0, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => __('product.max_stock')]); !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('reorder_point', __('product.reorder_point') . ':') !!}
                        {!! Form::number('reorder_point', 0, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => __('product.reorder_point')]); !!}
                    </div>
                </div>
            </div>

            <!-- Pricing Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-money"></i> قیمت‌گذاری</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('default_purchase_price', __('product.default_purchase_price') . ':') !!}
                        {!! Form::number('default_purchase_price', 0, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => __('product.default_purchase_price')]); !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('default_sales_price', __('product.default_sales_price') . ':') !!}
                        {!! Form::number('default_sales_price', 0, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => __('product.default_sales_price')]); !!}
                    </div>
                </div>

                <div class="col-sm-4">
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
                    <h4><i class="fa fa-cog"></i> مشخصات فنی</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('dimensions', __('product.dimensions') . ':') !!}
                        {!! Form::text('dimensions', null, ['class' => 'form-control', 'placeholder' => __('product.dimensions')]); !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('color', __('product.color') . ':') !!}
                        {!! Form::text('color', null, ['class' => 'form-control', 'placeholder' => __('product.color')]); !!}
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="form-group">
                        {!! Form::label('model', __('product.model') . ':') !!}
                        {!! Form::text('model', null, ['class' => 'form-control', 'placeholder' => __('product.model')]); !!}
                    </div>
                </div>
            </div>

            <!-- Control Settings Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-check-square"></i> تنظیمات کنترل</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            {!! Form::checkbox('serial_required', 1, false, ['class' => 'input-icheck']); !!} 
                            <strong>@lang('product.serial_required')</strong>
                        </label>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            {!! Form::checkbox('expiry_required', 1, false, ['class' => 'input-icheck']); !!} 
                            <strong>@lang('product.expiry_required')</strong>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>
                            {!! Form::checkbox('is_active', 1, true, ['class' => 'input-icheck']); !!} 
                            <strong>@lang('product.is_active')</strong>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
