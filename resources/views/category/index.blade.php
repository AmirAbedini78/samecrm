@extends('layouts.app')
@section('title', __('product.categories'))

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('product.categories')
            <small class="tw-text-sm md:tw-text-base tw-text-gray-700 tw-font-semibold">@lang('lang_v1.manage_categories')</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                @component('components.widget', ['class' => 'box-primary', 'title' => __('product.categories')])
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-right">
                                <a class="btn btn-primary btn-big" href="{{ action([\App\Http\Controllers\CategoryController::class, 'create']) }}">
                                    <i class="fa fa-plus"></i> @lang('messages.add')
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped ajax_view" id="category_table">
                            <thead>
                                <tr>
                                    <th>@lang('messages.action')</th>
                                    <th>@lang('product.category')</th>
                                    <th>@lang('lang_v1.short_code')</th>
                                    <th>@lang('lang_v1.parent_category')</th>
                                    <th>@lang('lang_v1.description')</th>
                                    <th>@lang('lang_v1.created_at')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endcomponent
            </div>
        </div>
    </section>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Simple DataTable without serverSide
            category_table = $('#category_table').DataTable({
                processing: true,
                serverSide: false,
                ajax: {
                    url: "{{ url('simple-categories') }}",
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.log('AJAX Error:', error);
                        console.log('Response:', xhr.responseText);
                    }
                },
                columnDefs: [{
                    "targets": [0],
                    "orderable": false,
                    "searchable": false
                }],
                columns: [
                    { data: 'action', name: 'action' },
                    { data: 'name', name: 'name' },
                    { data: 'short_code', name: 'short_code' },
                    { data: 'parent_category', name: 'parent_category' },
                    { data: 'description', name: 'description' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });

            $(document).on('click', '.delete-category', function(e) {
                e.preventDefault();
                var href = $(this).attr('href');
                swal({
                    title: LANG.sure,
                    text: LANG.confirm_delete_category,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: "DELETE",
                            url: href,
                            dataType: "json",
                            success: function(result) {
                                if (result.success == true) {
                                    toastr.success(result.msg);
                                    category_table.ajax.reload();
                                } else {
                                    toastr.error(result.msg);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
