<?php

namespace App\Controller;

use App\Model\LivroModel;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LivroController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function getAll(Request $request,LivroModel $model): JsonResponse
    {
        $filtros = $request->query->all();

        // Recupera todos os livros
        $livros = $model->buscarLivros($filtros);

        // Serializa os dados dos livros usando o grupo "livro:list"
        $json = $this->serializer->serialize($livros, 'json', ['groups' => 'livro:list']);

        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);  // True para informar que o conteúdo é JSON
    }

    public function cadastrar(Request $resquest, LivroModel $model): JsonResponse
    {
        $livro = $model->adicionarLivroComAutor($resquest->toArray());

        $json = $this->serializer->serialize($livro, 'json', ['groups' => 'livro:list']);

        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);  // True para informar que o conteúdo é JSON
    }
}