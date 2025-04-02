<?php

namespace App\DataFixtures;

use App\Entity\Assunto;
use App\Entity\Autor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livro;

class LivroFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->livrosMock() as $infoLivro) {
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

            $manager->persist($assunto);
            $manager->persist($autor);

            $manager->persist($livro);
        }

        $manager->flush();
    }

    public function livrosMock()
    {
        return [
            [
                'titulo' => 'Livro 1',
                'editora' => 'Editora A',
                'edicao' => 1,
                'anoPublicacao' => '2001',
                'autor' => ['nome' => 'Autor 1'],
                'assunto' => ['descricao' => 'Assunto 1'],
            ],
            [
                'titulo' => 'Livro 2',
                'editora' => 'Editora B',
                'edicao' => 1,
                'anoPublicacao' => '2002',
                'autor' => ['nome' => 'Autor 2'],
                'assunto' => ['descricao' => 'Assunto 2'],
            ],
            [
                'titulo' => 'Livro 3',
                'editora' => 'Editora C',
                'edicao' => 1,
                'anoPublicacao' => '2003',
                'autor' => ['nome' => 'Autor 3'],
                'assunto' => ['descricao' => 'Assunto 3'],
            ],
            [
                'titulo' => 'Livro 4',
                'editora' => 'Editora D',
                'edicao' => 1,
                'anoPublicacao' => '2004',
                'autor' => ['nome' => 'Autor 4'],
                'assunto' => ['descricao' => 'Assunto 4'],
            ],
            [
                'titulo' => 'Livro 5',
                'editora' => 'Editora E',
                'edicao' => 1,
                'anoPublicacao' => '2005',
                'autor' => ['nome' => 'Autor 5'],
                'assunto' => ['descricao' => 'Assunto 5'],
            ],
            [
                'titulo' => 'Livro 6',
                'editora' => 'Editora F',
                'edicao' => 1,
                'anoPublicacao' => '2006',
                'autor' => ['nome' => 'Autor 6'],
                'assunto' => ['descricao' => 'Assunto 6'],
            ],
            [
                'titulo' => 'Livro 7',
                'editora' => 'Editora G',
                'edicao' => 1,
                'anoPublicacao' => '2007',
                'autor' => ['nome' => 'Autor 7'],
                'assunto' => ['descricao' => 'Assunto 7'],
            ],
            [
                'titulo' => 'Livro 8',
                'editora' => 'Editora H',
                'edicao' => 1,
                'anoPublicacao' => '2008',
                'autor' => ['nome' => 'Autor 8'],
                'assunto' => ['descricao' => 'Assunto 8'],
            ],
            [
                'titulo' => 'Livro 9',
                'editora' => 'Editora I',
                'edicao' => 1,
                'anoPublicacao' => '2009',
                'autor' => ['nome' => 'Autor 9'],
                'assunto' => ['descricao' => 'Assunto 9'],
            ],
            [
                'titulo' => 'Livro 10',
                'editora' => 'Editora J',
                'edicao' => 1,
                'anoPublicacao' => '2010',
                'autor' => ['nome' => 'A. A. Row'],
                'assunto' => ['descricao' => 'Assunto 10'],
            ],
        ];
    }
}
