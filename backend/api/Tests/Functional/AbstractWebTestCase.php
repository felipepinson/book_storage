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

    protected function getResponse(): string
    {
        return $this->client->getResponse()->getContent();
    }

    protected function getJsonResponse(): array|\stdClass
    {
        return json_decode((string) $this->getResponse());
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
