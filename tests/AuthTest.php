<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    protected $name = 'testname';
    protected $email = 'testname@localhost.com';
    protected $password = 'qweasd123';

    /**
     * A test to login success via api.
     *
     * @return void
     */
    public function testLoginSuccess()
    {
        $user = factory(User::class)->create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password)
        ]);

        // Success logged in
        $this->post('/auth/login', ['email' => $this->email, 'password' => $this->password]);
        $resp = $this->getContentObject();

        $this->seeJsonContains(['status' => 'ok', 'error' => false]);
        $this->assertEquals($resp->user->email, $this->email);
        $this->assertEquals($resp->user->name, $this->name);

        // Test to access api
        $this->be($user);
        $this->get('/raids', ["X-AUTH-TOKEN" => $user->x_auth_token]);

        $this->seeJsonContains([]);
        $this->dontSeeJson(['error' => true, 'message' => 'unauthorized']);
    }

    /**
     * A test to failed login via api.
     *
     * @return void
     */
    public function testLoginFailed()
    {
        // Failed logged in
        $this->post('/auth/login', ['email' => $this->email, 'password' => $this->password]);
        $this->seeJsonContains(['status' => 'failed', 'error' => true]);
    }

    /**
     * A test to logout via api.
     *
     * @return void
     */
    public function testLogout()
    {
        // Success logout
        $this->loginAsDummyUser();
        $this->get('/auth/logout');
        $this->seeJsonContains(['logout' => true]);
    }
}
