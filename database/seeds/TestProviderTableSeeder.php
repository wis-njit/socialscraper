<?php

use Illuminate\Database\Seeder;
use App\Provider;

class TestProviderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $providers = ['facebook', 'twitter', 'instagram']; //'google' supported but removed as SNS is not used

        foreach ($providers as $provider) {
            Provider::firstOrCreate(['name' => $provider,]);
        }
    }

}
