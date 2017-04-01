<?php

namespace Tests\Unit;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserServiceTest extends TestCase
{

    use DatabaseTransactions;
    use DatabaseMigrations;

    private $userService;

    protected function setUp()
    {
        parent::setUp();
        $this->userService = new UserService();
        $this->seed("TestDatabaseSeeder");
        //\DB::connection()->enableQueryLog();
    }

    public function testGetAllProviderAccounts(){

        //Not in DB seeding
        $out = $this->userService->getAllProviderAccounts('-1');
        //print_r($out[0]->name);
        self::assertTrue($out[0]->name == 'google');
        self::assertTrue($out[0]->active == '0');

        //Contained in DB seeding
        $out = $this->userService->getAllProviderAccounts('1');
        //print_r($out[0]->name);
        self::assertTrue($out[0]->name == 'google');
        self::assertTrue($out[0]->active == '1');
        //print_r($out);
        /*print_r(
            \DB::getQueryLog()
        );*/
    }

    public function testDisassociateProviderAccount(){
        self::assertTrue($this->userService->disassociateProviderAccount('google', 1) == 1);
        self::assertTrue($this->userService->disassociateProviderAccount('foo', -1) == 0);
    }
}
