<?php

declare(strict_types=1);

namespace App\Tests;

use App\Controller\التسويقController;
use App\Repository\التسويقRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Testالتسويق extends TestCase
{
    private ObjectProphecy $repository;
    private ObjectProphecy $request;
    private ObjectProphecy $response;
    private التسويقController $controller;

    protected function setUp(): void
    {
        $this->repository = $this->prophesize(التسويقRepository::class);
        $this->request = $this->prophesize(ServerRequestInterface::class);
        $this->response = $this->prophesize(ResponseInterface::class);
        $this->controller = new التسويقController($this->repository->reveal());
    }

    public function testGetAll(): void
    {
        $this->request->getMethod()->willReturn('GET');
        $this->repository->findAll()->willReturn([
            ['id' => 1, 'name' => 'Test'],
            ['id' => 2, 'name' => 'Test2'],
        ]);

        $response = $this->controller->handle($this->request->reveal(), $this->response->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetById(): void
    {
        $this->request->getMethod()->willReturn('GET');
        $this->request->getAttribute('id')->willReturn(1);
        $this->repository->find(1)->willReturn(['id' => 1, 'name' => 'Test']);

        $response = $this->controller->handle($this->request->reveal(), $this->response->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $this->request->getMethod()->willReturn('POST');
        $this->request->getParsedBody()->willReturn(['name' => 'Test']);
        $this->repository->create(['name' => 'Test'])->willReturn(['id' => 1, 'name' => 'Test']);

        $response = $this->controller->handle($this->request->reveal(), $this->response->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $this->request->getMethod()->willReturn('PUT');
        $this->request->getAttribute('id')->willReturn(1);
        $this->request->getParsedBody()->willReturn(['name' => 'Test2']);
        $this->repository->update(1, ['name' => 'Test2'])->willReturn(['id' => 1, 'name' => 'Test2']);

        $response = $this->controller->handle($this->request->reveal(), $this->response->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->request->getMethod()->willReturn('DELETE');
        $this->request->getAttribute('id')->willReturn(1);
        $this->repository->delete(1)->willReturn(true);

        $response = $this->controller->handle($this->request->reveal(), $this->response->reveal());

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}