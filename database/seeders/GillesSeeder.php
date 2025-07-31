<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\User;
use App\Models\UserProject;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class GillesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'saisie', 'guard_name' => 'web']);

        $user = User::create([
            'firstname' => 'ASSOGBA',
            'lastname' => 'Gilles',
            'email' => 'gilles@gouv.bj',
            'password' => Hash::make('Mtfp@2025'),
            'birthdate' => '1980-01-01',
            'birthplace' => 'Cityville',
            'address' => '123 Admin Street, Cityville',
            'phone'=>'0166092669',
            'structure_id'=> 10,
        ]);



        UserSetting::create([
            'user_id' => $user->id,
            'use_2FA' => false,
            'accept_notification' => false,
            'notification_list' => null,
            'mode_2FA' => 'SMS',
        ]);

        $user->assignRole($role);

    }
}
