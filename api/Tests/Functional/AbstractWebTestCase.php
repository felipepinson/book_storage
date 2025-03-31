<?php

namespace App\Tests\Functional;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = $this->createClient();

        $this->em = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function sendRequest(string $method, string $url, array $data = [], array $headers = []): void
    {
        $this->client->request(
            $method,
            $url,
            [],
            [],
            $headers,
            json_encode($data),
        );
    }

    protected function getJsonResponse(): array|\stdClass
    {
        return json_decode((string) $this->client->getResponse()->getContent());
    }

    protected static function getDataFromFile(string $fileName)
    {
        return json_decode(file_get_contents(__DIR__ . '/../data/' . $fileName . '.json'), true);
    }

    protected static function getDtoFromFile(string $fileName, string $dtoFqcn): object
    {
        $data = self::getDataFromFile($fileName);

        return new $dtoFqcn(...$data);
    }

    protected function getFileResponse(): string
    {
        return $this->client->getResponse()->getContent();
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
        ];
    }

    protected function save(AbstractEntity $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    protected function remove(AbstractEntity $entity): void
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    protected function clear(): void
    {
        $this->em->clear();
    }
}
