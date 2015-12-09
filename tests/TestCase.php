<?php

use App\User;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Get last content from response.
     *
     * @return object
     */
    protected function getContentObject()
    {
        return json_decode($this->response->getContent());
    }

    /**
     * Get last content from response with collect.
     *
     * @return \Collect
     */
    protected function getContentCollect()
    {
        return collect($this->getContentObject());
    }

    /**
     * Login as dummy user.
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    protected function loginAsDummyUser($name = '', $email = '', $password = '')
    {
        $user = factory(User::class)->create();

        if ($name && $email && $password)
        {
            $input = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password)
            ];

            $user = factory(User::class)->create($input);
        }

        $this->be($user);

        return $user;
    }

    /**
     * Generate headers for user
     *
     * @return array
     */
    protected function generateHeaders($user)
    {
        return ["X-AUTH-TOKEN" => $user->x_auth_token];
    }

}
