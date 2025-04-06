<?php

namespace App\Model;

use App\Entity\Autor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class AutorModel extends AbstractModel
{
    public function buscarAutores(array $filtros = []): array
    {
        return $this->em->getRepository(Autor::class)->findBy($filtros);
    }

    public function criarAutor(array $dados): Autor
    {
        $autor = new Autor();
        $autor->setNome($dados['nome']);

        $this->persistAndFlush($autor);

        return $autor;
    }

    public function atualizarAutor(int $id, array $dados): Autor
    {
        $autor = $this->em->getRepository(Autor::class)->find($id);

        if (!$autor) {
            throw new \Doctrine\ORM\EntityNotFoundException('Auto nao encontrado', Response::HTTP_NOT_FOUND);
        }

        $autor->setNome($dados['nome']);

        $this->flush();

        return $autor;
    }

    public function removerAutor(int $id): void
    {
        $autor = $this->em->getRepository(Autor::class)->find($id);

        if (!$autor) {
            throw new \Doctrine\ORM\EntityNotFoundException('Auto nao encontrado', Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($autor);
        $this->flush();
    }
}
