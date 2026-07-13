<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Auth\Auth;
use App\Auth\User;
use App\Auth\LoginService;
use App\Auth\RegisterService;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TestAuth extends TestCase
{
    private $auth;
    private $loginService;
    private $registerService;
    private $session;

    protected function setUp(): void
    {
        $this->session = Mockery::mock(SessionInterface::class);
        $this->loginService = Mockery::mock(LoginService::class);
        $this->registerService = Mockery::mock(RegisterService::class);
        $this->auth = new Auth($this->loginService, $this->registerService, $this->session);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->loginService->shouldReceive('login')->with($username, $password)->andReturn(true);

        $this->auth->login($username, $password);

        $this->assertTrue($this->session->has('logged_in'));
        $this->assertEquals($username, $this->session->get('logged_in'));
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->loginService->shouldReceive('login')->with($username, $password)->andReturn(false);

        $this->auth->login($username, $password);

        $this->assertFalse($this->session->has('logged_in'));
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->registerService->shouldReceive('register')->with($username, $password)->andReturn(true);

        $this->auth->register($username, $password);

        $this->assertTrue($this->session->has('registered'));
        $this->assertEquals($username, $this->session->get('registered'));
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->registerService->shouldReceive('register')->with($username, $password)->andReturn(false);

        $this->auth->register($username, $password);

        $this->assertFalse($this->session->has('registered'));
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method of the `Auth` class successfully logs in a user and sets the `logged_in` session variable.
- `testLoginFailure`: Tests that the `login` method of the `Auth` class fails to log in a user and does not set the `logged_in` session variable.
- `testRegisterSuccess`: Tests that the `register` method of the `Auth` class successfully registers a new user and sets the `registered` session variable.
- `testRegisterFailure`: Tests that the `register` method of the `Auth` class fails to register a new user and does not set the `registered` session variable.

Each test method uses the `shouldReceive` method from the `Mockery` library to specify the expected behavior of the `loginService` and `registerService` mocks. The `login` and `register` methods of the `Auth` class are then called with the expected input, and the resulting session state is asserted using the `assertTrue` and `assertEquals` methods.