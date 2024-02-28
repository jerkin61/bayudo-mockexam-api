<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Jerquin\Database\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Jerquin\Enums\Permission as UserPermission;
use Illuminate\Support\Facades\Validator;

class PermissionAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // Create permissions if they don't exist
    Permission::firstOrCreate(['name' => UserPermission::SUPER_ADMIN]);
    Permission::firstOrCreate(['name' => UserPermission::ADMIN]);
    Permission::firstOrCreate(['name' => UserPermission::STAFF]);
    Permission::firstOrCreate(['name' => UserPermission::USER]);

    // Create a new user
    $saname = 'Super Admin';
    $saemail = 'superadmin@fligno.com';
    $sapassword = 'test123';

    $user1 = User::create([
        'name' => $saname,
        'email' => $saemail,
        'password' => Hash::make($sapassword),
    ]);
    
        
    $aname = 'Jerquin Bayudo';
    $aemail = 'admin@fligno.com';
    $apassword = 'test123';

    $user2 = User::create([
        'name' => $aname,
        'email' => $aemail,
        'password' => Hash::make($apassword),
    ]);
    $bname = 'Jerquin Bayudo';
    $bemail = 'user@fligno.com';
    $bpassword = 'test123';

    $user3 = User::create([
        'name' => $bname,
        'email' => $bemail,
        'password' => Hash::make($bpassword),
    ]);

    // Assign permissions to the user
    $user1->givePermissionTo([
        UserPermission::SUPER_ADMIN,
        UserPermission::ADMIN,
        UserPermission::STAFF,
        UserPermission::USER,
    ]);
    $user2->givePermissionTo([
        UserPermission::ADMIN,
        UserPermission::USER,
    ]);
    $user3->givePermissionTo([
        UserPermission::USER,
    ]);
    }
}
