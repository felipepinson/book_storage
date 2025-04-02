<?php

namespace App\Controller;

use App\Model\AssuntoModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AssuntoController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
    }

    public function buscarAssuntos(Request $request, AssuntoModel $model): JsonResponse
    {
        try {
            $assuntos = $model->buscarAssuntos($request->query->all());

            $json = $this->serializer->serialize($assuntos, 'json', ['groups' => 'assunto']);

            return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cadastrar(Request $request, AssuntoModel $model): JsonResponse
    {
        try {
            $assunto = $model->adicionarAssunto($request->toArray());
            $json = $this->serializer->serialize($assunto, 'json', ['groups' => 'assunto']);
            return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Este assunto já existe no sistema.'], JsonResponse::HTTP_CONFLICT);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function atualizar(Request $request, AssuntoModel $model, int $id): JsonResponse
    {
        try {
            $assunto = $model->atualizarAssunto($id, $request->toArray());
            $json = $this->serializer->serialize($assunto, 'json', ['groups' => 'assunto']);
            return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Este assunto já existe no sistema.'], JsonResponse::HTTP_CONFLICT);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function remover(AssuntoModel $model, int $id): JsonResponse
    {
        try {
            $model->removerAssunto($id);

            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => 'Este assunto n o existe no sistema.'], JsonResponse::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}