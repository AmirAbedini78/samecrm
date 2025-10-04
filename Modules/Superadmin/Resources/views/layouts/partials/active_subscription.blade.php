@if(!empty($__subscription) && env('APP_ENV') != 'demo')
@php
    $package_details = is_string($__subscription->package_details) ? json_decode($__subscription->package_details, true) : $__subscription->package_details;
    $package_details = $package_details ?: [];
@endphp
<i class="fas fa-info-circle pull-left cursor-pointer" style= "color:white" aria-hidden="true" data-toggle="popover" data-html="true" title="@lang('superadmin::lang.active_package_description')" data-placement="right" data-trigger="hover" data-content="
    <table class='table table-condensed'>
     <tr class='text-center'> 
        <td colspan='2'>
            {{ $__subscription->package->name ?? 'Ultimate Package' }}
        </td>
     </tr>
     <tr class='text-center'>
        <td colspan='2'>
            {{ @format_date($__subscription->start_date) }} - {{@format_date($__subscription->end_date) }}
        </td>
     </tr>
     <tr> 
        <td colspan='2'>
            <i class='fa fa-check text-success'></i>
            @if(($package_details['location_count'] ?? 999) == 0)
                @lang('superadmin::lang.unlimited')
            @else
                {{ $package_details['location_count'] ?? 999 }}
            @endif

            @lang('business.business_locations')
        </td>
     </tr>
     <tr>
        <td colspan='2'>
            <i class='fa fa-check text-success'></i>
            @if(($package_details['user_count'] ?? 999) == 0)
                @lang('superadmin::lang.unlimited')
            @else
                {{ $package_details['user_count'] ?? 999 }}
            @endif

            @lang('superadmin::lang.users')
        </td>
     <tr>
     <tr>
        <td colspan='2'>
            <i class='fa fa-check text-success'></i>
            @if(($package_details['product_count'] ?? 999) == 0)
                @lang('superadmin::lang.unlimited')
            @else
                {{ $package_details['product_count'] ?? 999 }}
            @endif

            @lang('superadmin::lang.products')
        </td>
     </tr>
     <tr>
        <td colspan='2'>
            <i class='fa fa-check text-success'></i>
            @if(($package_details['invoice_count'] ?? 999) == 0)
                @lang('superadmin::lang.unlimited')
            @else
                {{ $package_details['invoice_count'] ?? 999 }}
            @endif

            @lang('superadmin::lang.invoices')
        </td>
     </tr>
     
    </table>                     
">
</i>
@endif