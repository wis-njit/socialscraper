<?php

use App\ProviderUserProfile;
use Illuminate\Database\Seeder;
use App\Provider;
use App\User;

class ProviderUserProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminDummyUser = User::firstOrCreate(['email'=> 'admin@admin.com']);

        if ($adminDummyUser) {

            $providers = Provider::All();//['google','facebook','twitter','instagram'];

            foreach ($providers as $provider) {
                ProviderUserProfile::create([
                    'user_id'=> $adminDummyUser->id,
                    'provider_type_id' => $provider->id,
                    'provider_user_id' => $provider->id . '-8329295',
                    'email' => $provider->id .'someotheremail@example.com',
                    'name' => $provider->id .'SNS Crazyman',
                ]);

            }
        }
    }
}
