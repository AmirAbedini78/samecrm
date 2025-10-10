<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Business;
use App\BusinessLocation;
use App\Category;
// use App\Brand;
use App\Unit;
use App\Product;
use App\Variation;
use App\VariationTemplate;
use App\VariationValueTemplate;
use App\ProductVariation;
use App\VariationLocationDetails;
use App\User;
use App\Warehouse;
use Carbon\Carbon;

class BearingCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating bearing company data...');

        // Get business
        $business = Business::first();
        if (!$business) {
            $this->command->error('No business found!');
            return;
        }

        // Create Categories
        $this->createCategories($business->id);
        
        // Create Brands
        $this->createBrands($business->id);
        
        // Create Units
        $this->createUnits($business->id);
        
        // Create Business Locations (Warehouses)
        $this->createWarehouses($business->id);
        
        // Create Products
        $this->createProducts($business->id);

        // Seed warehouse stock pivot using warehouses table
        $this->seedWarehouseStock($business->id);
        
        $this->command->info('✅ Bearing company data created successfully!');
    }

    private function createCategories($business_id)
    {
        // Get first user ID
        $user_id = \App\User::first()->id ?? 1;
        
        $categories = [
            [
                'name' => 'Ball Bearings',
                'description' => 'Ball bearings for various applications',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'Roller Bearings',
                'description' => 'Roller bearings for heavy-duty applications',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'Thrust Bearings',
                'description' => 'Thrust bearings for axial loads',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'Spherical Bearings',
                'description' => 'Spherical bearings for misalignment',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'Bearing Accessories',
                'description' => 'Accessories and maintenance items',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✓ Categories created');
    }

    private function createBrands($business_id)
    {
        // Get first user ID
        $user_id = \App\User::first()->id ?? 1;
        
        $brands = [
            [
                'name' => 'SKF',
                'description' => 'Swedish bearing manufacturer',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'FAG',
                'description' => 'German bearing manufacturer',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'NSK',
                'description' => 'Japanese bearing manufacturer',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'Timken',
                'description' => 'American bearing manufacturer',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'NTN',
                'description' => 'Japanese bearing manufacturer',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert($brand);
        }

        $this->command->info('✓ Brands created');
    }

    private function createUnits($business_id)
    {
        // Get first user ID
        $user_id = \App\User::first()->id ?? 1;
        
        $units = [
            [
                'actual_name' => 'Piece',
                'short_name' => 'PCS',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'actual_name' => 'Set',
                'short_name' => 'SET',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'actual_name' => 'Box',
                'short_name' => 'BOX',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'actual_name' => 'Kilogram',
                'short_name' => 'KG',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('✓ Units created');
    }

    private function createWarehouses($business_id)
    {
        // Get required IDs
        $invoice_scheme_id = \DB::table('invoice_schemes')->first()->id ?? 1;
        $invoice_layout_id = \DB::table('invoice_layouts')->first()->id ?? 1;
        
        $warehouses = [
            [
                'name' => 'Main Warehouse - Tehran',
                'location_id' => 'WH001',
                'warehouse_code' => 'WH001',
                'warehouse_type' => 'central',
                'landmark' => 'Tehran, Iran',
                'city' => 'Tehran',
                'state' => 'Tehran',
                'country' => 'Iran',
                'zip_code' => '12345',
                'mobile' => '+98-21-12345678',
                'email' => 'warehouse@bearingco.com',
                'capacity' => 10000,
                'capacity_unit' => 'm3',
                'storage_address' => 'Building A, Floor 1-3, Tehran Industrial Zone',
                'description' => 'Main central warehouse for all bearing products',
                'is_active' => true,
                'business_id' => $business_id,
                'invoice_scheme_id' => $invoice_scheme_id,
                'invoice_layout_id' => $invoice_layout_id,
            ],
            [
                'name' => 'Oil Industry Warehouse - Abadan',
                'location_id' => 'WH002',
                'warehouse_code' => 'WH002',
                'warehouse_type' => 'branch',
                'landmark' => 'Abadan, Iran',
                'city' => 'Abadan',
                'state' => 'Khuzestan',
                'country' => 'Iran',
                'zip_code' => '12346',
                'mobile' => '+98-61-87654321',
                'email' => 'abadan@bearingco.com',
                'capacity' => 5000,
                'capacity_unit' => 'm3',
                'storage_address' => 'Near Abadan Refinery, Industrial Zone',
                'description' => 'Specialized warehouse for oil industry bearings',
                'is_active' => true,
                'business_id' => $business_id,
                'invoice_scheme_id' => $invoice_scheme_id,
                'invoice_layout_id' => $invoice_layout_id,
            ],
            [
                'name' => 'Quality Control Warehouse',
                'location_id' => 'WH003',
                'warehouse_code' => 'WH003',
                'warehouse_type' => 'quarantine',
                'landmark' => 'Tehran, Iran',
                'city' => 'Tehran',
                'state' => 'Tehran',
                'country' => 'Iran',
                'zip_code' => '12347',
                'mobile' => '+98-21-87654321',
                'email' => 'qc@bearingco.com',
                'capacity' => 1000,
                'capacity_unit' => 'm3',
                'storage_address' => 'Quality Control Building, Tehran',
                'description' => 'Warehouse for quality control and testing',
                'is_active' => true,
                'business_id' => $business_id,
                'invoice_scheme_id' => $invoice_scheme_id,
                'invoice_layout_id' => $invoice_layout_id,
            ],
        ];

        foreach ($warehouses as $wh) {
            // Create BusinessLocation for compatibility (only valid columns)
            BusinessLocation::create([
                'name' => $wh['name'],
                'location_id' => $wh['location_id'],
                'landmark' => $wh['landmark'] ?? null,
                'city' => $wh['city'] ?? null,
                'state' => $wh['state'] ?? null,
                'country' => $wh['country'] ?? null,
                'zip_code' => $wh['zip_code'] ?? null,
                'mobile' => $wh['mobile'] ?? null,
                'email' => $wh['email'] ?? null,
                'is_active' => $wh['is_active'] ?? true,
                'business_id' => $business_id,
                'invoice_scheme_id' => $invoice_scheme_id,
                'invoice_layout_id' => $invoice_layout_id,
            ]);

            // Create Warehouse record (separate table) for Inventory module (full details)
            Warehouse::create([
                'business_id' => $business_id,
                'name' => $wh['name'],
                'warehouse_code' => $wh['warehouse_code'],
                'warehouse_type' => $wh['warehouse_type'],
                'capacity' => $wh['capacity'],
                'capacity_unit' => $wh['capacity_unit'],
                'storage_address' => $wh['storage_address'],
                'description' => $wh['description'],
                'is_active' => true,
            ]);
        }

        $this->command->info('✓ Warehouses created');
    }

    private function createProducts($business_id)
    {
        // Get first user ID
        $user_id = \App\User::first()->id ?? 1;
        
        // Get actual IDs
        $category_ids = \App\Category::where('business_id', $business_id)->pluck('id')->toArray();
        $brand_ids = \DB::table('brands')->where('business_id', $business_id)->pluck('id')->toArray();
        $unit_ids = \App\Unit::where('business_id', $business_id)->pluck('id')->toArray();
        
        $products = [
            // Ball Bearings
            [
                'name' => 'SKF Deep Groove Ball Bearing 6205',
                'sku' => 'SKF-6205',
                'item_code' => 'BB-6205-001',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => $unit_ids[0] ?? 1, // Piece
                'brand_id' => $brand_ids[0] ?? 1, // SKF
                'category_id' => $category_ids[0] ?? 1, // Ball Bearings
                'alert_quantity' => 10,
                'min_stock' => 5,
                'max_stock' => 100,
                'reorder_point' => 15,
                'weight' => 0.15,
                'dimensions' => '25x52x15 mm',
                'color' => 'Silver',
                'model' => '6205-2RS',
                'default_rack' => 'A-01',
                'default_row' => 'R-01',
                'default_shelf' => 'S-01',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 25.50,
                'default_sales_price' => 35.00,
                'profit_percentage' => 37.25,
                'discount_limit' => 10.00,
                'currency' => 'USD',
                'serial_required' => true,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Deep groove ball bearing for general purpose applications',
                // Sepidar inventory item fields
                'row_number' => 1,
                'date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'document_number' => 'INV-1001',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9001',
                'tracking_number' => 'TRK-6205-001',
                'quantity' => 50,
                'warehouse_stock' => 50,
                'unit_price' => 25.50,
                'final_unit_price' => 28.05,
                'final_amount' => 1402.50,
                'duties' => 20.00,
                'shipping_cost' => 35.00,
                'shipping_tax' => 3.50,
                'net_amount' => 1379.00,
                'exchange_rate' => 1.000000,
                'currency_amount' => 1379.00,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
                'created_by' => $user_id,
            ],
            [
                'name' => 'FAG Angular Contact Ball Bearing 7205B',
                'sku' => 'FAG-7205B',
                'item_code' => 'BB-7205B-002',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 1,
                'brand_id' => 2, // FAG
                'category_id' => 1,
                'alert_quantity' => 8,
                'min_stock' => 4,
                'max_stock' => 80,
                'reorder_point' => 12,
                'weight' => 0.18,
                'dimensions' => '25x52x15 mm',
                'color' => 'Silver',
                'model' => '7205B-TVP',
                'default_rack' => 'A-02',
                'default_row' => 'R-01',
                'default_shelf' => 'S-02',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 45.00,
                'default_sales_price' => 62.00,
                'profit_percentage' => 37.78,
                'discount_limit' => 15.00,
                'currency' => 'USD',
                'serial_required' => true,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Angular contact ball bearing for high precision applications',
                'row_number' => 2,
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'document_number' => 'INV-1002',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9002',
                'tracking_number' => 'TRK-7205B-002',
                'quantity' => 40,
                'warehouse_stock' => 40,
                'unit_price' => 45.00,
                'final_unit_price' => 49.50,
                'final_amount' => 1980.00,
                'duties' => 25.00,
                'shipping_cost' => 40.00,
                'shipping_tax' => 4.00,
                'net_amount' => 1951.00,
                'exchange_rate' => 1.000000,
                'currency_amount' => 1951.00,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
            // Roller Bearings
            [
                'name' => 'NSK Cylindrical Roller Bearing NU205',
                'sku' => 'NSK-NU205',
                'item_code' => 'RB-NU205-003',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 1,
                'brand_id' => 3, // NSK
                'category_id' => 2, // Roller Bearings
                'alert_quantity' => 6,
                'min_stock' => 3,
                'max_stock' => 60,
                'reorder_point' => 10,
                'weight' => 0.25,
                'dimensions' => '25x52x15 mm',
                'color' => 'Silver',
                'model' => 'NU205E',
                'default_rack' => 'B-01',
                'default_row' => 'R-02',
                'default_shelf' => 'S-01',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 35.00,
                'default_sales_price' => 48.00,
                'profit_percentage' => 37.14,
                'discount_limit' => 12.00,
                'currency' => 'USD',
                'serial_required' => true,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Cylindrical roller bearing for heavy radial loads',
                'row_number' => 3,
                'date' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'document_number' => 'INV-1003',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9003',
                'tracking_number' => 'TRK-NU205-003',
                'quantity' => 30,
                'warehouse_stock' => 30,
                'unit_price' => 35.00,
                'final_unit_price' => 38.50,
                'final_amount' => 1155.00,
                'duties' => 15.00,
                'shipping_cost' => 30.00,
                'shipping_tax' => 3.00,
                'net_amount' => 1107.00,
                'exchange_rate' => 1.000000,
                'currency_amount' => 1107.00,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
            [
                'name' => 'Timken Tapered Roller Bearing 30205',
                'sku' => 'TIM-30205',
                'item_code' => 'RB-30205-004',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 1,
                'brand_id' => 4, // Timken
                'category_id' => 2,
                'alert_quantity' => 5,
                'min_stock' => 2,
                'max_stock' => 50,
                'reorder_point' => 8,
                'weight' => 0.30,
                'dimensions' => '25x52x16.25 mm',
                'color' => 'Silver',
                'model' => '30205J',
                'default_rack' => 'B-02',
                'default_row' => 'R-02',
                'default_shelf' => 'S-02',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 55.00,
                'default_sales_price' => 75.00,
                'profit_percentage' => 36.36,
                'discount_limit' => 15.00,
                'currency' => 'USD',
                'serial_required' => true,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Tapered roller bearing for combined loads',
                'row_number' => 4,
                'date' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'document_number' => 'INV-1004',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9004',
                'tracking_number' => 'TRK-30205-004',
                'quantity' => 25,
                'warehouse_stock' => 25,
                'unit_price' => 55.00,
                'final_unit_price' => 60.50,
                'final_amount' => 1512.50,
                'duties' => 12.00,
                'shipping_cost' => 25.00,
                'shipping_tax' => 2.50,
                'net_amount' => 1473.00,
                'exchange_rate' => 1.000000,
                'currency_amount' => 1473.00,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
            // Thrust Bearings
            [
                'name' => 'SKF Thrust Ball Bearing 51105',
                'sku' => 'SKF-51105',
                'item_code' => 'TB-51105-005',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 1,
                'brand_id' => 1, // SKF
                'category_id' => 3, // Thrust Bearings
                'alert_quantity' => 4,
                'min_stock' => 2,
                'max_stock' => 40,
                'reorder_point' => 6,
                'weight' => 0.12,
                'dimensions' => '25x42x11 mm',
                'color' => 'Silver',
                'model' => '51105',
                'default_rack' => 'C-01',
                'default_row' => 'R-03',
                'default_shelf' => 'S-01',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 28.00,
                'default_sales_price' => 38.00,
                'profit_percentage' => 35.71,
                'discount_limit' => 10.00,
                'currency' => 'USD',
                'serial_required' => true,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Thrust ball bearing for axial loads',
                'row_number' => 5,
                'date' => Carbon::now()->subDays(6)->format('Y-m-d'),
                'document_number' => 'INV-1005',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9005',
                'tracking_number' => 'TRK-51105-005',
                'quantity' => 20,
                'warehouse_stock' => 20,
                'unit_price' => 28.00,
                'final_unit_price' => 30.80,
                'final_amount' => 616.00,
                'duties' => 10.00,
                'shipping_cost' => 18.00,
                'shipping_tax' => 1.80,
                'net_amount' => 586.20,
                'exchange_rate' => 1.000000,
                'currency_amount' => 586.20,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
            // Spherical Bearings
            [
                'name' => 'FAG Spherical Roller Bearing 22205',
                'sku' => 'FAG-22205',
                'item_code' => 'SB-22205-006',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 1,
                'brand_id' => 2, // FAG
                'category_id' => 4, // Spherical Bearings
                'alert_quantity' => 3,
                'min_stock' => 1,
                'max_stock' => 30,
                'reorder_point' => 5,
                'weight' => 0.35,
                'dimensions' => '25x52x18 mm',
                'color' => 'Silver',
                'model' => '22205E',
                'default_rack' => 'D-01',
                'default_row' => 'R-04',
                'default_shelf' => 'S-01',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 65.00,
                'default_sales_price' => 88.00,
                'profit_percentage' => 35.38,
                'discount_limit' => 15.00,
                'currency' => 'USD',
                'serial_required' => true,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Spherical roller bearing for misalignment compensation',
                'row_number' => 6,
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'document_number' => 'INV-1006',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9006',
                'tracking_number' => 'TRK-22205-006',
                'quantity' => 15,
                'warehouse_stock' => 15,
                'unit_price' => 65.00,
                'final_unit_price' => 71.50,
                'final_amount' => 1072.50,
                'duties' => 15.00,
                'shipping_cost' => 20.00,
                'shipping_tax' => 2.00,
                'net_amount' => 1035.50,
                'exchange_rate' => 1.000000,
                'currency_amount' => 1035.50,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
            // Bearing Accessories
            [
                'name' => 'Bearing Grease - High Temperature',
                'sku' => 'GREASE-HT',
                'item_code' => 'ACC-GREASE-007',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 4, // Kilogram
                'brand_id' => 1, // SKF
                'category_id' => 5, // Bearing Accessories
                'alert_quantity' => 20,
                'min_stock' => 10,
                'max_stock' => 200,
                'reorder_point' => 30,
                'weight' => 1.0,
                'dimensions' => '1kg container',
                'color' => 'Blue',
                'model' => 'LGHP2',
                'default_rack' => 'E-01',
                'default_row' => 'R-05',
                'default_shelf' => 'S-01',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 15.00,
                'default_sales_price' => 22.00,
                'profit_percentage' => 46.67,
                'discount_limit' => 20.00,
                'currency' => 'USD',
                'serial_required' => false,
                'expiry_required' => true,
                'is_active' => true,
                'product_description' => 'High temperature bearing grease for oil industry applications',
                'row_number' => 7,
                'date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'document_number' => 'INV-1007',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9007',
                'tracking_number' => 'TRK-GREASE-007',
                'quantity' => 60,
                'warehouse_stock' => 60,
                'unit_price' => 15.00,
                'final_unit_price' => 16.50,
                'final_amount' => 990.00,
                'duties' => 8.00,
                'shipping_cost' => 12.00,
                'shipping_tax' => 1.20,
                'net_amount' => 968.80,
                'exchange_rate' => 1.000000,
                'currency_amount' => 968.80,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
            [
                'name' => 'Bearing Puller Set - 3 Pieces',
                'sku' => 'PULLER-SET',
                'item_code' => 'ACC-PULLER-008',
                'item_type' => 'sale_goods',
                'barcode_type' => 'C128',
                'unit_id' => 2, // Set
                'brand_id' => 1, // SKF
                'category_id' => 5,
                'alert_quantity' => 2,
                'min_stock' => 1,
                'max_stock' => 20,
                'reorder_point' => 3,
                'weight' => 2.5,
                'dimensions' => 'Various sizes',
                'color' => 'Black',
                'model' => 'PULLER-3P',
                'default_rack' => 'E-02',
                'default_row' => 'R-05',
                'default_shelf' => 'S-02',
                'inventory_account_id' => 'INV-001',
                'purchase_account_id' => 'PUR-001',
                'sales_account_id' => 'SAL-001',
                'default_purchase_price' => 85.00,
                'default_sales_price' => 120.00,
                'profit_percentage' => 41.18,
                'discount_limit' => 25.00,
                'currency' => 'USD',
                'serial_required' => false,
                'expiry_required' => false,
                'is_active' => true,
                'product_description' => 'Professional bearing puller set for maintenance',
                'row_number' => 8,
                'date' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'document_number' => 'INV-1008',
                'document_type' => 'purchase',
                'base_document_number' => 'PO-9008',
                'tracking_number' => 'TRK-PULLER-008',
                'quantity' => 10,
                'warehouse_stock' => 10,
                'unit_price' => 85.00,
                'final_unit_price' => 93.50,
                'final_amount' => 935.00,
                'duties' => 10.00,
                'shipping_cost' => 14.00,
                'shipping_tax' => 1.40,
                'net_amount' => 909.60,
                'exchange_rate' => 1.000000,
                'currency_amount' => 909.60,
                'currency_tax' => 0.00,
                'currency_duties' => 0.00,
                'agreed_return_price' => 0.00,
                'agreed_return_amount' => 0.00,
                'net_agreed_return' => 0.00,
                'month' => Carbon::now()->format('Y-m'),
                'description' => 'Initial purchase entry',
                'business_id' => $business_id,
            ],
        ];

        foreach ($products as $index => $productData) {
            $productData['created_by'] = $user_id;
            $productData['unit_id'] = $unit_ids[$index % max(1, count($unit_ids))] ?? 1;
            $productData['brand_id'] = $brand_ids[$index % max(1, count($brand_ids))] ?? 1;
            $productData['category_id'] = $category_ids[$index % max(1, count($category_ids))] ?? 1;
            $product = Product::create($productData);
            
            // Create product variation first
            $product_variation = \App\ProductVariation::create([
                'product_id' => $product->id,
                'variation_template_id' => null,
                'name' => $product->name,
                'is_dummy' => 0,
            ]);

            // Create variation for each product
            $variation = Variation::create([
                'name' => $product->name,
                'product_id' => $product->id,
                'product_variation_id' => $product_variation->id,
                'sub_sku' => $product->sku,
                'default_purchase_price' => $product->default_purchase_price,
                'dpp_inc_tax' => $product->default_purchase_price * 1.1, // 10% tax
                'profit_percent' => $product->profit_percentage,
                'default_sell_price' => $product->default_sales_price,
                'sell_price_inc_tax' => $product->default_sales_price * 1.1, // 10% tax
            ]);

            // Create variation location details for each BusinessLocation
            $locations = BusinessLocation::where('business_id', $business_id)->get();
            foreach ($locations as $loc) {
                VariationLocationDetails::create([
                    'product_id' => $product->id,
                    'product_variation_id' => $product_variation->id,
                    'variation_id' => $variation->id,
                    'location_id' => $loc->id,
                    'qty_available' => rand(10, 100),
                ]);
            }
        }

        $this->command->info('✓ Products created with variations and stock');
    }

    private function seedWarehouseStock($business_id)
    {
        $warehouses = Warehouse::where('business_id', $business_id)->get();
        $products = Product::where('business_id', $business_id)->get();

        foreach ($warehouses as $wh) {
            foreach ($products as $product) {
                DB::table('warehouse_products')->updateOrInsert(
                    [
                        'warehouse_id' => $wh->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'quantity' => rand(10, 200),
                        'min_stock' => $product->min_stock ?? 0,
                        'max_stock' => $product->max_stock ?? 0,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            // Update warehouse aggregated fields (Sepidar-style)
            $begin_qty = rand(100, 300);
            $begin_amt = $begin_qty * rand(20, 80);
            $in_qty = rand(200, 500);
            $in_amt = $in_qty * rand(20, 80);
            $out_qty = rand(150, 450);
            $out_amt = $out_qty * rand(20, 80);
            $net_qty = $begin_qty + $in_qty - $out_qty;
            $stock_amt = $net_qty * rand(20, 80);

            $wh->update([
                'beginning_period_quantity' => $begin_qty,
                'beginning_period_amount' => $begin_amt,
                'input_quantity' => $in_qty,
                'input_amount' => $in_amt,
                'output_quantity' => $out_qty,
                'output_amount' => $out_amt,
                'net_quantity' => $net_qty,
                'stock_amount' => $stock_amt,
            ]);
        }

        $this->command->info('✓ Warehouse stock seeded (pivot)');
    }
}
