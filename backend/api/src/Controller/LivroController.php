<?php

namespace App\Controller;

use App\Model\LivroModel;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use Doctrine\DBAL\Exception as DBALException;
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

    public function buscarLivros(Request $request,LivroModel $model): JsonResponse
    {
        try {
            $filtros = $request->query->all();

            $livros = $model->buscarLivros($filtros);

            $json = $this->serializer->serialize($livros, 'json', ['groups' => 'livro']);

            return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        } catch (NoResultException $e) {
            return new JsonResponse(['error' => 'Nenhum livro encontrado.'], JsonResponse::HTTP_NOT_FOUND);

        } catch (DBALException $e) {
            return new JsonResponse(['error' => 'Erro ao acessar o banco de dados.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops... Verifiquei os parametros e tente novamente.'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cadastrar(Request $resquest, LivroModel $model): JsonResponse
    {
        try {
            $livro = $model->adicionarLivro($resquest->toArray());

            $json = $this->serializer->serialize($livro, 'json', ['groups' => 'livro']);

            return new JsonResponse($json, JsonResponse::HTTP_CREATED, [], true);

        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);

        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Esse livro já existe no sistema.'], JsonResponse::HTTP_CONFLICT);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function atualizar(Request $request, LivroModel $model, int $id): JsonResponse
    {
        try {
            $livro = $model->atualizarLivro($id, $request->toArray());

            $json = $this->serializer->serialize($livro, 'json', ['groups' => 'livro']);

            return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);

        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Esse livro já existe no sistema.'], JsonResponse::HTTP_CONFLICT);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);

        } catch (DBALException $e) {
            if (strpos($e->getMessage(), 'Unique violation') !== false) {
                return new JsonResponse(['error' => 'Esse livro já foi cadastrado no sistema para esse autor'], JsonResponse::HTTP_CONFLICT);
            }

            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function remover(LivroModel $model, int $id): JsonResponse
    {
        try {
            $model->removerLivro($id);

            return new JsonResponse(['message' => 'Livro removido com sucesso!'], JsonResponse::HTTP_NO_CONTENT);

        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ops.. Temos um Erro interno no servidor. Aguarde alguns instantes e tente novamente'], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}