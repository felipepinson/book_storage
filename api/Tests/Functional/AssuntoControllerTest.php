<?php

namespace App\Tests\Functional;

use App\Entity\Assunto;
use Symfony\Component\HttpFoundation\Response;

class AssuntoControllerTest extends AbstractWebTestCase
{
    public function testBuscarAssuntos()
    {
        $this->sendRequest('GET', '/v1/assuntos');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCadastrarNovoAssunto()
    {
        $infoAssunto = [
            'descricao' => 'drama',
        ];

        $this->sendRequest('POST', '/v1/assuntos', $infoAssunto);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseContent = $this->getJsonResponse();

        $assuntoSalvo = $this->em->getRepository(Assunto::class)->findOneById($responseContent->id);

        $this->assertEquals($assuntoSalvo->getDescricao(), 'drama');
    }

    public function testAlterarAssunto()
    {
        $livro = $this->em->getRepository(Assunto::class)->findOneBy(['descricao' => 'Humor']);
        $codlAss = $livro->getId();

        $infoAssunto = [
            'descricao' => 'altera para fantasia',
        ];

        $this->sendRequest('PUT', "/v1/assuntos/{$codlAss}", $infoAssunto);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $assuntoRemovido = $this->em->getRepository(Assunto::class)->findOneById($codlAss);

        $this->assertEquals($assuntoRemovido->getDescricao(), 'altera para fantasia');
    }

    public function testRemoverAssunto()
    {
        $livro = $this->em->getRepository(Assunto::class)->findOneBy(['descricao' => 'Humor']);
        $codlAss = $livro->getId();

        $this->sendRequest('DELETE', "/v1/assuntos/{$codlAss}");
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $assuntoRemovido = $this->em->getRepository(Assunto::class)->findOneById($codlAss);

        $this->assertNull($assuntoRemovido);
    }
}
