<?php

namespace App\Controller;

use App\Model\AutorModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AutorController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function buscarAutores(Request $request, AutorModel $model): JsonResponse
    {
        try {
            $filtros = $request->query->all();
            $autores = $model->buscarAutores($filtros);

            $json = $this->serializer->serialize($autores, 'json', ['groups' => 'autor']);

            return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cadastrar(Request $request, AutorModel $model): JsonResponse
    {
        try {
            $autor = $model->criarAutor($request->toArray());

            $json = $this->serializer->serialize($autor, 'json', ['groups' => 'autor']);

            return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);

        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Esse autor já existe no sistema.'], JsonResponse::HTTP_CONFLICT);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function atualizar(Request $request, AutorModel $model, int $id): JsonResponse
    {
        try {
            $autor = $model->atualizarAutor($id, $request->toArray());

            $json = $this->serializer->serialize($autor, 'json', ['groups' => 'autor']);

            return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);

        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function remover(AutorModel $model, int $id): JsonResponse
    {
        try {
            $model->removerAutor($id);

            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);

        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



}