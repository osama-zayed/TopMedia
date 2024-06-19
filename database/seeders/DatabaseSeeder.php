<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'osama',
            'email' => 'osama@gmail.com',
            'password' => bcrypt('123123123'),
        ]);

        // صلاحيات المنتجات
        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);

        // صلاحيات الطلبات
        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'cancel orders']);
        Permission::create(['name' => 'ship orders']);

        // صلاحيات العملاء
        Permission::create(['name' => 'view customers']);
        Permission::create(['name' => 'create customers']);
        Permission::create(['name' => 'edit customers']);
        Permission::create(['name' => 'delete customers']);

        // صلاحيات التوصيل
        Permission::create(['name' => 'view deliveries']);
        Permission::create(['name' => 'create deliveries']);
        Permission::create(['name' => 'edit deliveries']);
        Permission::create(['name' => 'cancel deliveries']);

        // صلاحيات التقارير
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'generate reports']);

        // صلاحيات الإعدادات
        Permission::create(['name' => 'view settings']);
        Permission::create(['name' => 'update settings']);

        // صلاحيات المستخدمين
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'assign roles']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view orders', 'create orders', 'edit orders', 'cancel orders', 'ship orders',
            'view customers', 'create customers', 'edit customers', 'delete customers',
            'view deliveries', 'create deliveries', 'edit deliveries', 'cancel deliveries',
            'view reports', 'generate reports',
            'view settings', 'update settings',
            'view users', 'create users', 'edit users', 'delete users', 'assign roles'
        ]);

        $customerRole = Role::create(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view products', 'create orders', 'view orders'
        ]);
        $role = Role::where('name', 'admin')->first();
        $user->syncRoles([$role->id]);


        Setting::create([
            'key' => 'store_name',
            'value' => 'Top media',
        ]);
        Setting::create([
            'key' => 'description',
            'value' => 'متجر متخصص في بيع الالكترونيات',
        ]);

        Setting::create([
            'key' => 'logo',
            'value' => 'asset/images/logo.png',
        ]);
        Setting::create([
            'key' => 'phone_number',
            'value' => '+967775561590',
        ]);
        Setting::create([
            'key' => 'Whatsapp_number',
            'value' => '+967775561590',
        ]);
        Setting::create([
            'key' => 'facebook_account',
            'value' => 'https://www.facebook.com/osama.abdullah.12720?mibextid=ZbWKwL',
        ]);
        Setting::create([
            'key' => 'instagram_account',
            'value' => 'https://www.instagram.com/o.a.s55/?utm_source=qrs',
        ]);
       

        // \App\Models\User::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\Product::factory(100)->create();
    }
}
