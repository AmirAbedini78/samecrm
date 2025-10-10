<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        {!! Form::open(['url' => action([\App\Http\Controllers\WarehouseController::class, 'store']), 'method' => 'post', 'id' => 'warehouse_add_form' ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('business.add_warehouse') - سپیدار</h4>
        </div>

        <div class="modal-body">
            <!-- Basic Information Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-info-circle"></i> اطلاعات پایه انبار</h4>
                    <hr>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('name', __('business.name') . ':*') !!}
                        {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __('business.name')]); !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('warehouse_code', __('business.warehouse_code') . ':*') !!}
                        {!! Form::text('warehouse_code', null, ['class' => 'form-control', 'required', 'placeholder' => __('business.warehouse_code')]); !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('warehouse_type', __('business.warehouse_type') . ':*') !!}
                        {!! Form::select('warehouse_type', [
                            'central' => __('business.central'),
                            'branch' => __('business.branch'),
                            'consignment' => __('business.consignment'),
                            'quarantine' => __('business.quarantine'),
                            'waste' => __('business.waste'),
                            'temporary' => __('business.temporary')
                        ], 'central', ['class' => 'form-control', 'required']); !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('keeper_id', __('business.warehouse_keeper') . ':') !!}
                        {!! Form::select('keeper_id', $users, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.please_select')]); !!}
                    </div>
                </div>
            </div>

            <!-- Capacity Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-cube"></i> ظرفیت انبار</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('capacity', __('business.capacity') . ':') !!}
                        {!! Form::number('capacity', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => __('business.capacity')]); !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('capacity_unit', __('business.capacity_unit') . ':') !!}
                        {!! Form::select('capacity_unit', [
                            'm3' => 'm³ - متر مکعب',
                            'm2' => 'm² - متر مربع',
                            'kg' => 'kg - کیلوگرم',
                            'ton' => 'ton - تن',
                            'piece' => 'piece - عدد'
                        ], 'm3', ['class' => 'form-control']); !!}
                    </div>
                </div>
            </div>

            <!-- Accounting Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-calculator"></i> حساب‌های حسابداری</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('inventory_account_id', __('business.inventory_account_id') . ':') !!}
                        {!! Form::text('inventory_account_id', null, ['class' => 'form-control', 'placeholder' => __('business.inventory_account_id')]); !!}
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('issue_account_id', __('business.issue_account_id') . ':') !!}
                        {!! Form::text('issue_account_id', null, ['class' => 'form-control', 'placeholder' => __('business.issue_account_id')]); !!}
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="row">
                <div class="col-sm-12">
                    <h4><i class="fa fa-map-marker"></i> آدرس و توضیحات</h4>
                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('storage_address', __('business.storage_address') . ':') !!}
                        {!! Form::textarea('storage_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('business.storage_address')]); !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::label('description', __('business.description') . ':') !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('business.description')]); !!}
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
