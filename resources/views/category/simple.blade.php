@extends('layouts.app')
@section('title', 'Categories')

@section('content')
    <div class="container">
        <h1>Categories</h1>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="category_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Short Code</th>
                            <th>Parent</th>
                            <th>Description</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            // Load data directly
            $.ajax({
                url: "{{ url('simple-categories') }}",
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    var tbody = $('#category_table tbody');
                    tbody.empty();
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(category) {
                            var row = '<tr>' +
                                '<td>' + category.id + '</td>' +
                                '<td>' + category.name + '</td>' +
                                '<td>' + (category.short_code || '-') + '</td>' +
                                '<td>' + category.parent_category + '</td>' +
                                '<td>' + (category.description || '-') + '</td>' +
                                '<td>' + category.created_at + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="6">No categories found</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                    $('#category_table tbody').html('<tr><td colspan="6">Error loading data</td></tr>');
                }
            });
        });
    </script>
@endsection
