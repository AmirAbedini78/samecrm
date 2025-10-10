@extends('layouts.app')
@section('title', __('product.items') . ' - سپیدار')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('product.items') - سپیدار</h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary'])
        @slot('tool')
            <div class="box-tools">
                <a class="btn btn-primary btn-modal" 
                   data-href="{{ action([\App\Http\Controllers\ItemController::class, 'create']) }}" 
                   data-container=".item_modal">
                    <i class="fa fa-plus"></i> @lang('product.add_item')
                </a>
            </div>
        @endslot

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="item_table">
                <thead>
                    <tr>
                        <th>@lang('product.name')</th>
                        <th>@lang('product.item_code')</th>
                        <th>@lang('product.item_type')</th>
                        <th>@lang('product.category')</th>
                        <th>@lang('product.brand')</th>
                        <th>@lang('product.unit')</th>
                        <th>@lang('product.stock_status')</th>
                        <th>@lang('product.price')</th>
                        <th>تاریخ</th>
                        <th>شماره</th>
                        <th>نوع</th>
                        <th>سند مبنا</th>
                        <th>ردیابی</th>
                        <th>مقدار</th>
                        <th>موجودی انبار</th>
                        <th>فی</th>
                        <th>فی تمام شده</th>
                        <th>مبلغ تمام شده</th>
                        <th>عوارض</th>
                        <th>هزینه حمل</th>
                        <th>مالیات حمل</th>
                        <th>مبلغ خالص</th>
                        <th>نرخ ارز</th>
                        <th>مبلغ ارز</th>
                        <th>مالیات ارز</th>
                        <th>عوارض ارز</th>
                        <th>فی مرجوعی</th>
                        <th>مبلغ مرجوعی</th>
                        <th>خالص مرجوعی</th>
                        <th>ماه</th>
                        <th>توضیحات</th>
                        <th>@lang('product.technical_specs')</th>
                        <th>@lang('product.controls')</th>
                        <th>@lang('lang_v1.status')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endcomponent

    <div class="modal fade item_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        item_table = $('#item_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ action([\App\Http\Controllers\ItemController::class, 'index']) }}",
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}';
                },
                error: function(xhr, error, thrown) {
                    console.log('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'item_code', name: 'item_code' },
                { data: 'item_type_text', name: 'item_type' },
                { data: 'category_name', name: 'category_name' },
                { data: 'brand_name', name: 'brand_name' },
                { data: 'unit_name', name: 'unit_name' },
                { data: 'stock_status', name: 'stock_status' },
                { data: 'price_display', name: 'price_display' },
                { data: 'technical_specs', name: 'technical_specs' },
                { data: 'controls', name: 'controls' },
                { data: 'status', name: 'status' },
                { data: 'date', name: 'date' },
                { data: 'document_number', name: 'document_number' },
                { data: 'document_type', name: 'document_type' },
                { data: 'base_document_number', name: 'base_document_number' },
                { data: 'tracking_number', name: 'tracking_number' },
                { data: 'quantity', name: 'quantity' },
                { data: 'warehouse_stock', name: 'warehouse_stock' },
                { data: 'unit_price', name: 'unit_price' },
                { data: 'final_unit_price', name: 'final_unit_price' },
                { data: 'final_amount', name: 'final_amount' },
                { data: 'duties', name: 'duties' },
                { data: 'shipping_cost', name: 'shipping_cost' },
                { data: 'shipping_tax', name: 'shipping_tax' },
                { data: 'net_amount', name: 'net_amount' },
                { data: 'exchange_rate', name: 'exchange_rate' },
                { data: 'currency_amount', name: 'currency_amount' },
                { data: 'currency_tax', name: 'currency_tax' },
                { data: 'currency_duties', name: 'currency_duties' },
                { data: 'agreed_return_price', name: 'agreed_return_price' },
                { data: 'agreed_return_amount', name: 'agreed_return_amount' },
                { data: 'net_agreed_return', name: 'net_agreed_return' },
                { data: 'month', name: 'month' },
                { data: 'description', name: 'description' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                processing: "Loading...",
                emptyTable: "No items found"
            }
        });
    });
</script>
@endsection
