<?php

namespace App\Controller;

use App\Entity\RelatorioLivroAutorAssunto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class RelatorioController extends AbstractController
{
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(RelatorioLivroAutorAssunto::class);
        $dados = $repo->findby([], ['nomeAutor' => 'ASC', 'titulo' => 'ASC']);

        return $this->json($dados);
    }
}
