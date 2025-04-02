<?php

namespace App\Model;

use App\Entity\Assunto;
use App\Entity\Livro;
use App\Entity\Autor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LivroModel extends AbstractModel
{
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        private readonly AutorModel $autorModel,
        private readonly AssuntoModel $assuntoModel
    ) {
        parent::__construct($em, $validator);
    }

    public function buscarLivros(array $filtros = []): array
    {
        return $this->em->getRepository(Livro::class)->findBy($filtros);
    }

    public function adicionarLivro(array $infoLivro): Livro
    {
        $livro = new Livro();

        $livro->setTitulo($infoLivro['titulo']);
        $livro->setEditora($infoLivro['editora']);
        $livro->setEdicao($infoLivro['edicao']);
        $livro->setAnoPublicacao($infoLivro['anoPublicacao']);

        foreach ($infoLivro['autores'] as $autor) {
            $autoExistente = current($this->autorModel->buscarAutores(['nome' => $autor['nome']]));
            if (!empty($autoExistente)) {
                $livro->addAutor($autoExistente);

                continue;
            }

            $novoAutor = new Autor();
            $novoAutor->setNome($autor['nome']);
            $livro->addAutor($novoAutor);

            $this->persist($novoAutor);
        }

        foreach ($infoLivro['assuntos'] as $assunto) {
            $assuntoExistente = current($this->assuntoModel->buscarAssuntos(['descricao' => $assunto['descricao']]));
            if (!empty($assuntoExistente)) {
                $livro->addAssunto($assuntoExistente);

                continue;
            }

            $novoAssunto = new Assunto();
            $novoAssunto->setDescricao($assunto['descricao']);
            $livro->addAssunto($novoAssunto);

            $this->persist($novoAssunto);
        }

        $this->persistAndFlush($livro);

        return $livro;
    }

    public function atualizarLivro(int $id, array $infoLivro): Livro
    {
        $livro = $this->em->getRepository(Livro::class)->find($id);

        if (!$livro) {
            throw new \Doctrine\ORM\EntityNotFoundException('Livro não encontrado', Response::HTTP_NOT_FOUND);
        }

        $metadata = $this->em->getClassMetadata(Livro::class);

        $propiedadesLivros = $metadata->getFieldNames();
        foreach ($infoLivro as $propName => $value) {
            if (in_array($propName, $propiedadesLivros)) {
                $livro->{'set' . ucfirst($propName)}($value);
            }
        }

        if (in_array('autores', array_keys($infoLivro))) {
            $livro->getAutores()->clear();

            foreach ($infoLivro['autores'] as $autorData) {
                $autor = current($this->autorModel->buscarAutores($autorData));
                if (!$autor) {
                    throw new \Doctrine\ORM\EntityNotFoundException(
                        sprintf('Autor %s não encontrado', $autorData['nome']
                    ), Response::HTTP_NOT_FOUND);
                }

                $livro->addAutor($autor);
            }
        }

        if (in_array('assuntos', array_keys($infoLivro))) {
            $livro->getAssuntos()->clear();

            foreach ($infoLivro['assuntos'] as $assuntoData) {
                $assunto = current($this->assuntoModel->buscarAssuntos($assuntoData));
                if (!$assunto) {
                    throw new \Doctrine\ORM\EntityNotFoundException(
                        sprintf('Assunto %s não encontrado', $assuntoData['descricao']
                    ), Response::HTTP_NOT_FOUND);
                }

                $livro->addAssunto($assunto);
            }
        } 

        $this->persistAndFlush($livro);

        return $livro;
    }

    public function removerLivro(int $id): void
    {
        $livro = $this->em->getRepository(Livro::class)->find($id);

        if (!$livro) {
            throw new \Doctrine\ORM\EntityNotFoundException('Livro ja foi removido ou não existe', Response::HTTP_NOT_FOUND);
        }

        $this->em->remove($livro);
        $this->em->flush();
    }
}
