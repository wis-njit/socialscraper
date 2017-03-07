<?php

namespace Tests\Unit;

use App\Services\SMService;
use Config\Constants\SocialProvidersEnum;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Socialite\Two\User;
use Tests\TestCase;

//TODO use Mockery
class SMServiceTest extends TestCase
{

    use DatabaseTransactions;
    use DatabaseMigrations;

    private $smService;

    protected function setUp()
    {
        parent::setUp();
        $this->smService = new SMService();
        $this->seed("TestDatabaseSeeder");
        //DB::connection()->enableQueryLog();
    }

    //Mimics basic info some SNS' return
    private function getTestSnsAccount1(){

        $snsAccount = new User();
        $snsAccount->name = 'testuser';
        $snsAccount->email = 'testuser@example.com';
        $snsAccount->id = 'fooid';
        return $snsAccount;
    }

    private function getTestSnsGoogleEmail(){

        $snsAccount = new User();
        $snsAccount->name = 'testuser1';
        $snsAccount->email = 'testuser1-google-email@example.com';
        $snsAccount->id = 'testuser1-google-1234';
        return $snsAccount;
    }
    /**
     *Test successful email lookup of user returned from SNS
     */
    public function testFindOrCreateUserByPrimaryAccountEmail()
    {
        $authUser = $this->smService->findOrCreateUser($this->getTestSnsAccount1(), SocialProvidersEnum::GOOGLE);
        $this->assertEquals('testuser@example.com', $authUser->email, 'Email address did not match');
    }

    /**
     * Test lookup by SNS provider id
     */
    public function testFindOrCreateUserByProviderId(){

        $authUser = $this->smService->findOrCreateUser($this->getTestSnsGoogleEmail(), SocialProvidersEnum::GOOGLE);
        $hasProviderId = $authUser->snsProfile->contains('provider_user_id', 'testuser1-google-1234');
        $this->assertTrue($hasProviderId, 'Provider id was not listed');
    }

    /**
     * Test lookup by the SNS provider email
     */
   /* public function testFindOrCreateUserByProviderProfileEmail(){

        $authUser = $this->smService->findOrCreateUser($this->getTestSnsGoogleEmail(), SocialProvidersEnum::GOOGLE);
        $hasProviderEmail = $authUser->snsProfile->contains('email', 'testuser1-google-email@example.com');
        $this->assertTrue($hasProviderEmail, "Provider's listed email was not among those returned.");
    }*/

    /**NEGATIVE TESTS**/

    /**
     * Test lookup by SNS provider id does not match one we know is in the db
     */
    public function testFindOrCreateUserByProviderId_Negative(){

        $authUser = $this->smService->findOrCreateUser($this->getTestSnsAccount1(), SocialProvidersEnum::GOOGLE);
        $hasProviderId = $authUser->snsProfile->contains('provider_user_id', 'testuser1-google-1234');
        $this->assertFalse($hasProviderId);
    }

       /**
     * Ensure lookup by provider email does not return unrelated results
     */
    /*public function testFindOrCreateUserByProviderProfileEmail_Negative(){

        $authUser = $this->smService->findOrCreateUser($this->getTestSnsGoogleEmail(), SocialProvidersEnum::GOOGLE);
        $hasProviderEmail = $authUser->snsProfile->contains('email', 'testuser2-google-email@example.com');
        $this->assertFalse($hasProviderEmail);
    }*/

    /**
     * Ensure lookup by provider email does not return results when incorrect
     */
    public function testFindOrCreateUserByProviderProfileEmailMismatchedProviderType_Negative(){

        $authUser = $this->smService->findOrCreateUser($this->getTestSnsGoogleEmail(), SocialProvidersEnum::TWITTER);
        $hasProviderEmail = $authUser->snsProfile->contains('email', 'testuser1-google-email@example.com');
        $this->assertFalse($hasProviderEmail);

    }
}
