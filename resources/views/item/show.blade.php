<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('product.view_product'): {{ $item->name }}</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6"><strong>@lang('product.item_code'):</strong> {{ $item->item_code ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.item_type'):</strong> {{ $item->item_type ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.category'):</strong> {{ optional($item->category)->name ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.brand'):</strong> {{ optional($item->brand)->name ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.unit'):</strong> {{ optional($item->unit)->short_name ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.min_stock'):</strong> {{ $item->min_stock ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.max_stock'):</strong> {{ $item->max_stock ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.reorder_point'):</strong> {{ $item->reorder_point ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.default_purchase_price'):</strong> {{ $item->default_purchase_price ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.default_sales_price'):</strong> {{ $item->default_sales_price ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.currency'):</strong> {{ $item->currency ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.dimensions'):</strong> {{ $item->dimensions ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.color'):</strong> {{ $item->color ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('product.model'):</strong> {{ $item->model ?? '-' }}</div>
                <div class="col-sm-6"><strong>@lang('lang_v1.status'):</strong> {{ $item->is_active ? __('lang_v1.active') : __('lang_v1.inactive') }}</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>
    </div>
</div>
