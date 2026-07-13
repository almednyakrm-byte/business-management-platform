<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\العملاءController;
use App\Repository\العملاءRepository;
use App\Entity\العملاء;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testالعملاء extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(العملاءRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new العملاءController($this->repository, $this->router);
    }

    public function testGetAll(): void
    {
        $expectedResponse = new Response(json_encode([new العملاء()]));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new العملاء()]);

        $response = $this->controller->getAll();

        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne(): void
    {
        $expectedResponse = new Response(json_encode(new العملاء()));

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(new العملاء());

        $response = $this->controller->getOne(1);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testCreate(): void
    {
        $request = new Request([], [], ['_method' => 'POST'], json_encode(['name' => 'test']));
        $expectedResponse = new Response(json_encode(new العملاء()));

        $this->repository->expects($this->once())
            ->method('create')
            ->with(new العملاء('test'))
            ->willReturn(new العملاء());

        $response = $this->controller->create($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate(): void
    {
        $request = new Request([], [], ['_method' => 'PUT'], json_encode(['name' => 'test']));
        $expectedResponse = new Response(json_encode(new العملاء()));

        $this->repository->expects($this->once())
            ->method('update')
            ->with(new العملاء('test'))
            ->willReturn(new العملاء());

        $response = $this->controller->update(1, $request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete(): void
    {
        $expectedResponse = new Response('', Response::HTTP_NO_CONTENT);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(null);

        $response = $this->controller->delete(1);

        $this->assertEquals($expectedResponse, $response);
    }
}



// App\Controller\العملاءController.php

namespace App\Controller;

use App\Repository\العملاءRepository;
use App\Entity\العملاء;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class العملاءController
{
    private $repository;
    private $router;

    public function __construct(العملاءRepository $repository, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    public function getAll(): Response
    {
        return new Response(json_encode($this->repository->findAll()));
    }

    public function getOne(int $id): Response
    {
        return new Response(json_encode($this->repository->findOneById($id)));
    }

    public function create(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->repository->create(new العملاء($data['name']));
        return new Response(json_encode(new العملاء()));
    }

    public function update(int $id, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->repository->update(new العملاء($data['name']));
        return new Response(json_encode(new العملاء()));
    }

    public function delete(int $id): Response
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\العملاءRepository.php

namespace App\Repository;

use App\Entity\العملاء;

interface العملاءRepository
{
    public function findAll(): array;
    public function findOneById(int $id): ?العملاء;
    public function create(العملاء $entity): void;
    public function update(العملاء $entity): void;
    public function delete(int $id): void;
}



// App\Entity\العملاء.php

namespace App\Entity;

class العملاء
{
    private $id;
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}