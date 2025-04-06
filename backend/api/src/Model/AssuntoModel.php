<?php

namespace App\Model;

use App\Entity\Assunto;
use Symfony\Component\HttpFoundation\Response;

class AssuntoModel extends AbstractModel
{
    public function buscarAssuntos(array $filtros = []): array
    {
        return $this->em->getRepository(Assunto::class)->findBy($filtros);
    }

    public function adicionarAssunto(array $infoAssunto): Assunto
    {
        $assunto = new Assunto();
        $assunto->setDescricao($infoAssunto['descricao']);

        $this->persistAndFlush($assunto);

        return $assunto;
    }

    public function atualizarAssunto(int $id, array $infoAssunto): Assunto
    {
        $assunto = $this->em->getRepository(Assunto::class)->find($id);

        if (!$assunto) {
            throw new \Doctrine\ORM\EntityNotFoundException('Assunto não encontrado', Response::HTTP_NOT_FOUND);
        }

        $assunto->setDescricao($infoAssunto['descricao']);

        $this->flush();

        return $assunto;
    }

    public function removerAssunto(int $id): void
    {
        $assunto = $this->em->getRepository(Assunto::class)->find($id);

        if (!$assunto) {
            throw new \Doctrine\ORM\EntityNotFoundException('Assunto não encontrado', Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($assunto);

        $this->flush();
    }
}
