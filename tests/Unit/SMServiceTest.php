<?php

namespace Tests\Unit;

use App\ProviderUserProfile;
use app\User;
use config\constants\SocialProvidersEnum;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\SMService;

class SMServiceTest extends TestCase
{

    use DatabaseTransactions;

    private $dbUser;
    private $snsAccount;
    private $smService;

    protected function setUp()
    {
        parent::setUp();
        $this->dbUser = $this->initDBUser();
        $this->snsAccount = $this->initSNSAccount();
        $this->smService = new SMService();
    }

    private function initDBUser(){
        $snsProfile = new ProviderUserProfile();
        $snsProfile->provider_user_id = '7-8329295';

        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@admin.com';
        $user->snsProfile = $snsProfile;
        return $user;
    }

    private function initSNSAccount(){

        $snsAccount = new User();
        $snsAccount->name = 'admin';
        $snsAccount->email = 'admin@admin.com';
        $snsAccount->id = '5-8329295';
        return $snsAccount;
    }
    /**
     *Test email lookup of user returned from SNS
     */
    public function testFindOrCreateUser()
    {
        $authUser = $this->smService->findOrCreateUser($this->snsAccount, SocialProvidersEnum::GOOGLE);
        $this->assertEquals($this->dbUser->email, $authUser->email, 'Email address did not match');

    }

    /**
     * Test lookup by SNS provider id's
     */
    public function testFindOrCreateUserByProviderId(){

        $this->dbUser->email = "";
        $authUser = $this->smService->findOrCreateUser($this->snsAccount, SocialProvidersEnum::GOOGLE);

        $hasProviderId = $authUser->snsProfile->contains('provider_user_id', $this->dbUser->snsProfile->provider_user_id);
        //print_r($hasProviderId);
        $this->assertTrue($hasProviderId);
    }
}
