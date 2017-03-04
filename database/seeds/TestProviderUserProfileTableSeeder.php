<?php

use App\ProviderUserProfile;
use Illuminate\Database\Seeder;
use App\Provider;
use App\User;

class TestProviderUserProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $u1 = User::firstOrCreate(['email' => 'testuser1@example.com', 'name' => 'testuser1', 'password' => '1234']);
        $u2 = User::firstOrCreate(['email' => 'testuser2@example.com', 'name' => 'testuser2', 'password' => '1234']);
        $userArray = [$u1, $u2];

        foreach ($userArray as $userObj) {
            foreach (Provider::All() as $provider) {
                ProviderUserProfile::firstOrCreate([
                    'user_id' => $userObj->id,
                    'provider_type_id' => $provider->id,
                    'provider_user_id' => $userObj->name . '-' . $provider->name . '-1234', //testuser1-google-1234
                    'email' => $userObj->name . '-' . $provider->name . '-email@example.com', //testuser1-google-email@example.com
                    'name' => $userObj->name . '-' . $provider->name . '-name', //testuser1-google-name
                ]);

            }
        }
    }
}
