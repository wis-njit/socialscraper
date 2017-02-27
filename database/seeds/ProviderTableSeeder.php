<?php

use Illuminate\Database\Seeder;
use App\Provider;

class ProviderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Provider::count() == 0) {

            $providers = ['google','facebook','twitter','instagram'];

            foreach ($providers as $provider) {
                Provider::create(['name' => $provider,]);
            }
        }
    }
}
