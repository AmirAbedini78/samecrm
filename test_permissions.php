<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== USERS IN SYSTEM ===\n";
$users = User::select('id', 'username', 'first_name', 'last_name', 'user_type')->get();
foreach ($users as $user) {
    echo "ID: {$user->id} - Username: {$user->username} - Name: {$user->first_name} {$user->last_name} - Type: {$user->user_type}\n";
}

echo "\n=== ROLES IN SYSTEM ===\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "Role: {$role->name} - Business ID: {$role->business_id}\n";
}

echo "\n=== PERMISSIONS IN SYSTEM ===\n";
$permissions = Permission::all();
foreach ($permissions as $permission) {
    echo "Permission: {$permission->name}\n";
}

echo "\n=== TESTING USER PERMISSIONS ===\n";
foreach ($users as $user) {
    echo "\n--- User: {$user->username} ---\n";
    echo "Roles: " . $user->getRoleNames()->implode(', ') . "\n";
    echo "Permissions: " . $user->getAllPermissions()->pluck('name')->implode(', ') . "\n";
    
    $test_permissions = ['product.view', 'product.create', 'sell.view', 'stock.view', 'user.view'];
    foreach ($test_permissions as $perm) {
        $has = $user->can($perm) ? 'YES' : 'NO';
        echo "Can {$perm}: {$has}\n";
    }
}
