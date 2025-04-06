<?php

namespace App\Tests\Functional;

use App\Entity\Livro;
use Symfony\Component\HttpFoundation\Response;

class LivroControllerTest extends AbstractWebTestCase
{
    public function testGetAllLivrosComAutores(): void
    {
        $this->sendRequest('GET', '/v1/livros');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertNotEmpty($this->getResponse());
        $this->assertSame(10, count($this->getJsonResponse()));
    }

    public function testCadastrarNovoLivroComAutoresEAssunto(): void
    {
        $novoLivro = [
            'titulo' => 'Harry Potter',
            'editora' => 'Rocco',
            'edicao' => 1,
            'anoPublicacao' => '1997',
            'preco' => 20.00,
            'autores' => [
                [
                    'nome' => 'J.K. Rowling',
                ],
                [
                    'nome' => 'J.K. Rowling 2',
                ]
            ],
            'assuntos' => [
                [
                    'descricao' => 'Fantasia',
                ],
            ],
        ];

        $this->sendRequest('POST', '/v1/livros', $novoLivro);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $responseContent = $this->getJsonResponse();

        $livroSalvo = $this->em->getRepository(Livro::class)->findOneById($responseContent->id);

        $this->assertEquals($livroSalvo->getTitulo(), 'Harry Potter');
        $this->assertEquals($livroSalvo->getEditora(), 'Rocco');
        $this->assertEquals($livroSalvo->getEdicao(), 1);
        $this->assertEquals($livroSalvo->getAnoPublicacao(), '1997');
        $this->assertCount(2, $livroSalvo->getAutores()->toArray());
        $this->assertEquals($livroSalvo->getAutores()->first()->getNome(), 'J.K. Rowling');
        $this->assertEquals($livroSalvo->getAssuntos()->first()->getDescricao(), 'Fantasia');
    }

    public function testAlterarLivro(): void
    {
        $dados = [
            'titulo' => 'Harry Potter teste',
            'editora' => 'Rocco 2',
            'edicao' => 2,
            'preco' => 20.00,
            'anoPublicacao' => '1998',
        ];

        $livro = $this->em->getRepository(Livro::class)->findOneBy(['titulo' => 'Dom Quixote']);
        $codl = $livro->getId();

        $this->assertSame('Dom Quixote', $livro->getTitulo());
        $this->assertSame('Martins Fontes', $livro->getEditora());
        $this->assertSame(1, $livro->getEdicao());
        $this->assertSame('1605', $livro->getAnoPublicacao());

        $this->sendRequest('PUT',  sprintf('/v1/livros/%s', $codl), $dados);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $livroAtualizado = $this->em->getRepository(Livro::class)->findOneById($codl);

        $this->assertSame($dados['titulo'], $livroAtualizado->getTitulo());
        $this->assertSame($dados['editora'], $livroAtualizado->getEditora());
        $this->assertSame($dados['edicao'], $livroAtualizado->getEdicao());
        $this->assertSame($dados['anoPublicacao'], $livroAtualizado->getAnoPublicacao());
        $this->assertSame($dados['preco'], $livroAtualizado->getPreco());
    }

    public function testAlterarAutoresLivro(): void
    {
        $dados = [
            'autores' => [
                ['nome' => 'C.S. Lewis']
            ],
        ];

        $livro = $this->em->getRepository(Livro::class)->findOneBy(['titulo' => 'Dom Quixote']);
        $codl = $livro->getId();

        $this->assertSame('Dom Quixote', $livro->getTitulo());
        $this->assertSame('Martins Fontes', $livro->getEditora());
        $this->assertSame(1, $livro->getEdicao());
        $this->assertSame('1605', $livro->getAnoPublicacao());

        $this->sendRequest('PUT',  sprintf('/v1/livros/%s', $codl), $dados);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $livroAtualizado = $this->em->getRepository(Livro::class)->findOneById($codl);

        $this->assertEquals($livroAtualizado->getAutores()->first()->getNome(), 'C.S. Lewis');
    }

    public function testAlterarAssuntosLivro(): void
    {
        $dados = [
            'assuntos' => [
                ['descricao' => 'Fantasia'],
                ['descricao' => 'Infantil'],
            ],
        ];

        $livro = $this->em->getRepository(Livro::class)->findOneBy(['titulo' => 'Dom Quixote']);
        $codl = $livro->getId();

        $this->sendRequest('PUT',  sprintf('/v1/livros/%s', $codl), $dados);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $assuntoAtualizado = $this->em->getRepository(Livro::class)->findOneById($codl);

        $this->assertEquals($assuntoAtualizado->getAssuntos()->first()->getDescricao(), 'Fantasia');
        $this->assertEquals($assuntoAtualizado->getAssuntos()->next()->getDescricao(), 'Infantil');
    }

    public function testRemoverLivro(): void
    {
        $livro = $this->em->getRepository(Livro::class)->findOneBy(['titulo' => 'Dom Quixote']);
        $codl = $livro->getId();

        $this->sendRequest('DELETE',  sprintf('/v1/livros/%s', $codl));

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $livroRemovido = $this->em->getRepository(Livro::class)->findOneById($codl);

        $this->assertNull($livroRemovido);
    }
}
