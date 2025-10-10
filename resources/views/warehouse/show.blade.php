<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('business.warehouses'): {{ $warehouse->name }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6"><strong>@lang('business.warehouse_code'):</strong> {{ $warehouse->warehouse_code ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('business.warehouse_type'):</strong> {{ $warehouse->warehouse_type ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('business.warehouse_keeper'):</strong> {{ optional($warehouse->keeper)->first_name }} {{ optional($warehouse->keeper)->last_name }}</div>
                <div class="col-sm-6"><strong>@lang('business.capacity'):</strong> {{ $warehouse->capacity ? $warehouse->capacity . ' ' . $warehouse->capacity_unit : '-' }}</div>
                <div class="col-sm-12"><strong>@lang('business.storage_address'):</strong> {{ $warehouse->storage_address ?? '-' }}</div>
                <div class="col-sm-12"><strong>@lang('business.description'):</strong> {{ $warehouse->description ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('lang_v1.status'):</strong> {{ $warehouse->is_active ? __('lang_v1.active') : __('lang_v1.inactive') }}</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>
