<?php

namespace App\Tests\Functional;

use App\Entity\Livro;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class LivroControllerTest extends WebTestCase
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

    public function testGetAllLivrosComAutores(): void
    {
        $this->client ->request('GET', '/v1/livros');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = json_decode($this->client ->getResponse()->getContent(), true);

        $this->assertNotEmpty($responseContent);
        $this->assertSame(10, count($responseContent));
    }

    public function testCadastrarNovoLivroComAutorEAssunto(): void
    {
        $novoLivro = json_encode([
            'titulo' => 'Harry Potter',
            'editora' => 'Rocco',
            'edicao' => 1,
            'anoPublicacao' => '1997',
            'autor' => [
                'nome' => 'J.K. Rowling',
            ],
            'assunto' => [
                'descricao' => 'Fantasia'
            ],
        ]);

        $this->client ->request('POST', '/v1/livros', [],[],[], $novoLivro);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseContent = json_decode($this->client ->getResponse()->getContent(), true);

        $livroSalvo = $this->em->getRepository(Livro::class)->findOneById($responseContent['id']);

        $this->assertStringContainsString($livroSalvo->getTitulo(), 'Harry Potter');
        $this->assertStringContainsString($livroSalvo->getEditora(), 'Rocco');
        $this->assertStringContainsString($livroSalvo->getEdicao(), 1);
        $this->assertStringContainsString($livroSalvo->getAnoPublicacao(), '1997');
        $this->assertStringContainsString($livroSalvo->getAutores()->first()->getNome(), 'J.K. Rowling');
        $this->assertStringContainsString($livroSalvo->getAssuntos()->first()->getDescricao(), 'Fantasia');
    }
}
