<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\المبيعاتController;
use App\Repository\المبيعاتRepository;
use App\Entity\المبيعات;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testالمبيعات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(المبيعاتRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new المبيعاتController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = [
            ['id' => 1, 'name' => 'Sale 1'],
            ['id' => 2, 'name' => 'Sale 2'],
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'Sale 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testCreate(): void
    {
        $sale = new المبيعات();
        $sale->setName('Sale 1');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($sale);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->create($sale);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $sale = new المبيعات();
        $sale->setId(1);
        $sale->setName('Sale 1');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($sale);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->update(1, $sale);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->repository->find(1));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->controller->delete(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `المبيعاتController` class and the `المبيعاتRepository` class are already defined in your application. The `المبيعات` entity is also assumed to be defined. The `EntityManagerInterface` is used to mock the entity manager. The `Request` object is not used in this test, as the controller methods are mocked to return the expected responses.