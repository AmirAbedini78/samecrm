@extends('layouts.app')
@section('title', __('business.warehouses') . ' - سپیدار')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('business.warehouses') - سپیدار</h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary'])
        @slot('tool')
            <div class="box-tools">
                <a class="btn btn-primary btn-modal" 
                   data-href="{{ action([\App\Http\Controllers\WarehouseController::class, 'create']) }}" 
                   data-container=".warehouse_modal">
                    <i class="fa fa-plus"></i> @lang('business.add_warehouse')
                </a>
            </div>
        @endslot

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="warehouse_table">
                <thead>
                    <tr>
                        <th>@lang('business.name')</th>
                        <th>@lang('business.warehouse_code')</th>
                        <th>@lang('business.warehouse_type')</th>
                        <th>@lang('business.warehouse_keeper')</th>
                        <th>@lang('business.capacity')</th>
                        <th>@lang('business.storage_address')</th>
                        <th>اول دوره-مقدار</th>
                        <th>اول دوره-مبلغ</th>
                        <th>ورودی-مقدار</th>
                        <th>ورودی-مبلغ</th>
                        <th>خروجی-مقدار</th>
                        <th>خروجی-مبلغ</th>
                        <th>خالص-مقدار</th>
                        <th>موجودی-مبلغ</th>
                        <th>@lang('lang_v1.status')</th>
                        <th>@lang('messages.action')</th>
                    </tr>
                </thead>
            </table>
        </div>
    @endcomponent

    <div class="modal fade warehouse_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->
@endsection

@section('javascript')
<script type="text/javascript">
    $(document).ready(function() {
        warehouse_table = $('#warehouse_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ action([\App\Http\Controllers\WarehouseController::class, 'index']) }}",
                error: function(xhr, error, thrown) {
                    console.log('AJAX Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'warehouse_code', name: 'warehouse_code' },
                { data: 'warehouse_type_text', name: 'warehouse_type' },
                { data: 'keeper_name', name: 'keeper_name' },
                { data: 'capacity_display', name: 'capacity_display' },
                { data: 'storage_address', name: 'storage_address' },
                { data: 'begin_qty', name: 'begin_qty' },
                { data: 'begin_amt', name: 'begin_amt' },
                { data: 'in_qty', name: 'in_qty' },
                { data: 'in_amt', name: 'in_amt' },
                { data: 'out_qty', name: 'out_qty' },
                { data: 'out_amt', name: 'out_amt' },
                { data: 'net_qty', name: 'net_qty' },
                { data: 'stock_amt', name: 'stock_amt' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                processing: "Loading...",
                emptyTable: "No warehouses found"
            }
        });
    });
</script>
@endsection
