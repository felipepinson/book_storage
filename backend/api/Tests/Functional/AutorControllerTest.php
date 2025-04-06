<?php

namespace App\Tests\Functional;

use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Component\HttpFoundation\Response;

class AutorControllerTest extends AbstractWebTestCase
{
    public function testGetAllLivrosComAutores(): void
    {
        $this->sendRequest('GET', '/v1/autores');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertNotEmpty($this->getResponse());
        $this->assertSame(20, count($this->getJsonResponse()));
    }

    public function testCadastrarNovoAutor(): void
    {
        $novoAutor = [
            'nome' => 'Novo Autor',
        ];

        $this->sendRequest('POST', '/v1/autores', $novoAutor);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseContent = $this->getJsonResponse();

        $autorSalvo = $this->em->getRepository(Autor::class)->findOneById($responseContent->id);

        $this->assertEquals($autorSalvo->getNome(), 'Novo Autor');
    }

    public function testAlterarAutor(): void
    {
        $dados = [
            'nome' => 'Autor Alterado'
        ];

        $autor = $this->em->getRepository(Autor::class)->findOneBy(['nome' => 'J.K. Rowling']);
        $codA = $autor->getId();

        $this->sendRequest('PUT', sprintf('/v1/autores/%s', $codA), $dados);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $autorAtualizado = $this->em->getRepository(Autor::class)->findOneById($codA);

        $this->assertEquals($autorAtualizado->getNome(), 'Autor Alterado');
    }

    public function testRemoverAutor(): void
    {
        $autor = $this->em->getRepository(Autor::class)->findOneBy(['nome' => 'J.K. Rowling']);
        $codA = $autor->getId();

        $this->sendRequest('DELETE', sprintf('/v1/autores/%s', $codA));

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $autorRemovido = $this->em->getRepository(Autor::class)->findOneById($codA);

        $this->assertNull($autorRemovido);
    }
}
