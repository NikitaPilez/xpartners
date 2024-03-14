<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ClientControllerTest extends WebTestCase
{
    private array $headers;
    private string $path;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->headers = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTH-TOKEN' => 12345678,
        ];
        $this->path = 'http://localhost:8000/api/client/';
    }

    public function testCreateClient(): void
    {
        $client = static::createClient();

        $username = 'Test';
        $phone = '+375297777777';
        $email = 'test@gmail.com';

        $body = json_encode([
            'username' => $username,
            'phone' => $phone,
            'email' => $email,
        ]);

        $client->request(method: 'POST', uri: $this->path, server: $this->headers, content: $body);

        $responseContent = $client->getResponse()->getContent();
        $arrayContent = json_decode($responseContent, true);

        $this->assertEquals($username, $arrayContent['username']);
        $this->assertEquals($phone, $arrayContent['phone']);
        $this->assertEquals($email, $arrayContent['email']);
        $this->assertArrayHasKey('id', $arrayContent);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testUpdateClient(): void
    {
        $client = static::createClient();

        $clientRepository = static::getContainer()->get(ClientRepository::class);

        /** @var Client $clientEntity */
        $clientEntity = $clientRepository->findOneBy([]);

        $phone = $clientEntity->getPhone() . '123';

        $body = json_encode([
            'phone' => $phone,
        ]);

        $client->request(method: 'POST', uri: $this->path . $clientEntity->getId() . '/edit', server: $this->headers, content: $body);

        $responseContent = $client->getResponse()->getContent();
        $arrayContent = json_decode($responseContent, true);

        $this->assertEquals($phone, $arrayContent['phone']);
        $this->assertArrayHasKey('id', $arrayContent);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testUpdateByIdNonExist(): void
    {
        $client = static::createClient();

        $body = json_encode([
            'phone' => '123',
        ]);

        $client->request(method: 'POST', uri: $this->path . rand(1000, 10000) . '/edit', server: $this->headers, content: $body);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testGetById(): void
    {
        $client = static::createClient();

        $clientRepository = static::getContainer()->get(ClientRepository::class);

        /** @var Client $clientEntity */
        $clientEntity = $clientRepository->findOneBy([]);

        $client->request(method: 'GET', uri: $this->path . $clientEntity->getId(), server: $this->headers);

        $responseContent = $client->getResponse()->getContent();
        $arrayContent = json_decode($responseContent, true);

        $this->assertEquals($clientEntity->getId(), $arrayContent['id']);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testGetByIdNonExist(): void
    {
        $client = static::createClient();

        $client->request(method: 'GET', uri: $this->path . rand(1000, 10000), server: $this->headers);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request(method: 'GET', uri: $this->path, server: $this->headers);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testWithoutAuth()
    {
        $client = static::createClient();
        $client->request(method: 'GET', uri: $this->path);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = static::createClient();

        $clientRepository = static::getContainer()->get(ClientRepository::class);

        /** @var Client $clientEntity */
        $clientEntity = $clientRepository->findOneBy([]);

        $client->request(method: 'DELETE', uri: $this->path . $clientEntity->getId(), server: $this->headers);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
