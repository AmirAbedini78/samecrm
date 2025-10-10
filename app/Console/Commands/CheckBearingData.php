<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;
use App\Category;
// use App\Brand;
use App\BusinessLocation;
use App\Variation;

class CheckBearingData extends Command
{
    protected $signature = 'check:bearing-data';
    protected $description = 'Check bearing company data';

    public function handle()
    {
        $this->info('Checking bearing company data...');
        
        $this->info('Products count: ' . Product::count());
        $this->info('Categories count: ' . Category::count());
        $this->info('Brands count: ' . \DB::table('brands')->count());
        $this->info('Warehouses count: ' . BusinessLocation::count());
        $this->info('Variations count: ' . Variation::count());
        
        // Show some sample products
        $products = Product::with(['category'])->take(5)->get();
        $this->info("\nSample Products:");
        foreach ($products as $product) {
            $this->info("- {$product->name} ({$product->item_code}) - {$product->category->name}");
        }
        
        return 0;
    }
}
