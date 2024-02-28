<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Test extends Seeder
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

    // Create a new user
    $saname = 'Super Admin';
    $saemail = 'superadmin@jerquin-bayudo.com';
    $sapassword = 'test123';

    $user1 = User::create([
        'name' => $saname,
        'email' => $saemail,
        'password' => Hash::make($sapassword),
    ]);
        
    $aname = 'Jerquin Bayudo';
    $aemail = 'admin@jerquin-bayudo.com';
    $apassword = 'test123';

    $user2 = User::create([
        'name' => $aname,
        'email' => $aemail,
        'password' => Hash::make($apassword),
    ]);

    // Assign permissions to the user
    $user1->givePermissionTo([
        UserPermission::SUPER_ADMIN,
        UserPermission::ADMIN,
        UserPermission::STAFF,
    ]);
    $user2->givePermissionTo([
        UserPermission::ADMIN,
    ]);
    }
}
