<?php

namespace App\Model;

use App\Entity\Assunto;
use App\Entity\Livro;
use App\Entity\Autor;
use Doctrine\ORM\EntityManagerInterface;

class LivroModel
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function adicionarLivroComAutor(array $infoLivro): Livro
    {
        // Criando um novo livro
        $livro = new Livro();
        $livro->setTitulo($infoLivro['titulo']);
        $livro->setEditora($infoLivro['editora']);
        $livro->setEdicao($infoLivro['edicao']);
        $livro->setAnoPublicacao($infoLivro['anoPublicacao']);

        $autor = new Autor();
        $autor->setNome($infoLivro['autor']['nome']);
        $livro->addAutor($autor);

        $assunto = new Assunto();
        $assunto->setDescricao($infoLivro['assunto']['descricao']);
        $livro->addAssunto($assunto);

        $this->entityManager->persist($assunto);
        $this->entityManager->persist($autor);

        $this->entityManager->persist($livro);
        $this->entityManager->flush();

        return $livro;
    }

    public function buscarLivros(array $filtros = []): array
    {
        return $this->entityManager->getRepository(Livro::class)->findBy($filtros);
    }

    public function atualizarLivro(int $id, array $infoLivro): Livro
    {
        $livro = $this->entityManager->getRepository(Livro::class)->find($id);

        if (!$livro) {
            throw new \Exception('Livro nao encontrado', 404);
        }

        $livro->setTitulo($infoLivro['titulo']);
        $livro->setEditora($infoLivro['editora']);
        $livro->setEdicao($infoLivro['edicao']);
        $livro->setAnoPublicacao($infoLivro['anoPublicacao']);

        $autor = $livro->getAutores()->first();
        $autor->setNome($infoLivro['autor']['nome']);

        $assunto = $livro->getAssuntos()->first();
        $assunto->setDescricao($infoLivro['assunto']['descricao']);

        $this->entityManager->flush();

        return $livro;
    }
}
