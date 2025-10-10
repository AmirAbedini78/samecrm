<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'capacity' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    /**
     * Get the business that owns the warehouse.
     */
    public function business()
    {
        return $this->belongsTo(\App\Business::class);
    }

    /**
     * Get the warehouse keeper (user) associated with the warehouse.
     */
    public function keeper()
    {
        return $this->belongsTo(\App\User::class, 'keeper_id');
    }

    /**
     * Get the products in this warehouse.
     */
    public function products()
    {
        return $this->belongsToMany(\App\Product::class, 'warehouse_products', 'warehouse_id', 'product_id')
                    ->withPivot('quantity', 'min_stock', 'max_stock')
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active warehouses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Return list of warehouses for a business
     *
     * @param  int  $business_id
     * @param  bool  $show_all = false
     * @return array
     */
    public static function forDropdown($business_id, $show_all = false)
    {
        $query = Warehouse::where('business_id', $business_id)->Active();

        $result = $query->select('name', 'id', 'warehouse_code')->get();

        $warehouses = $result->pluck('name', 'id');

        if ($show_all) {
            $warehouses->prepend(__('report.all_warehouses'), '');
        }

        return $warehouses;
    }
}
