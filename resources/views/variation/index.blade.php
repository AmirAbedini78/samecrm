@extends('layouts.app')
@section('title', __('product.variations'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('product.variations')
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('lang_v1.manage_product_variations')</small>
        </h1>
        <!-- <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol> -->
    </section>

    <!-- Main content -->
    <section class="content">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('lang_v1.all_variations')])
            @slot('tool')
                <div class="box-tools">
                    <a class="tw-dw-btn tw-bg-gradient-to-r tw-from-indigo-600 tw-to-blue-500 tw-font-bold tw-text-white tw-border-none tw-rounded-full btn-modal"
                    data-href="{{action([\App\Http\Controllers\VariationTemplateController::class, 'create'])}}" 
                    data-container=".variation_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg> @lang('messages.add')
                    </a>
                </div>
            @endslot
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="variation_table">
                    <thead>
                        <tr>
                            <th>@lang('product.variations')</th>
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th>@lang('messages.action')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcomponent

        <div class="modal fade variation_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
        </div>

    </section>
    <!-- /.content -->

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Destroy existing DataTable if it exists to avoid reinit errors
            if ($.fn.DataTable.isDataTable('#variation_table')) {
                $('#variation_table').DataTable().clear().destroy();
                $('#variation_table').empty();
            }
            
            // Rebuild table structure
            $('#variation_table').html('<thead><tr><th>@lang("product.variations")</th><th>Product Name</th><th>SKU</th><th>@lang("messages.action")</th></tr></thead><tbody></tbody>');
            
            variation_table = $('#variation_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ action([\App\Http\Controllers\VariationTemplateController::class, 'index']) }}",
                    dataSrc: function(json) {
                        try {
                            var rows = json && json.data ? json.data : [];
                            if (!rows.length) return [];
                            // If rows are array-shaped, normalize them to objects
                            if (Array.isArray(rows[0])) {
                                return rows.map(function(r){
                                    return {
                                        name: r[0] || '',
                                        product_name: r[1] || '',
                                        sub_sku: r[2] || '',
                                        action: r[3] || ''
                                    };
                                });
                            }
                            return rows;
                        } catch (e) { console.log('dataSrc normalize error:', e); return []; }
                    },
                    error: function(xhr, error, thrown) {
                        console.log('AJAX Error:', error);
                        console.log('Response:', xhr.responseText);
                        console.log('Status:', xhr.status);
                    }
                },
                columns: [
                    {
                        data: null,
                        name: 'name',
                        defaultContent: '',
                        render: function(data, type, row) {
                            // row may be object or array
                            if (row && !Array.isArray(row)) return row.name || '';
                            return (Array.isArray(row) ? (row[0] || '') : '');
                        }
                    },
                    {
                        data: null,
                        name: 'product_name',
                        defaultContent: '',
                        render: function(data, type, row) {
                            if (row && !Array.isArray(row)) return row.product_name || '';
                            return (Array.isArray(row) ? (row[1] || '') : '');
                        }
                    },
                    {
                        data: null,
                        name: 'sub_sku',
                        defaultContent: '',
                        render: function(data, type, row) {
                            if (row && !Array.isArray(row)) return row.sub_sku || '';
                            return (Array.isArray(row) ? (row[2] || '') : '');
                        }
                    },
                    {
                        data: null,
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        defaultContent: '',
                        render: function(data, type, row) {
                            if (row && !Array.isArray(row)) return row.action || '';
                            return (Array.isArray(row) ? (row[3] || '') : '');
                        }
                    }
                ],
                language: {
                    processing: "Loading...",
                    emptyTable: "No variations found"
                }
            });
        });
    </script>
@endsection
