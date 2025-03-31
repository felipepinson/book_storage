<?php

namespace App\DataFixtures;

use App\Entity\Assunto;
use App\Entity\Autor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livro;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LivrosFixture extends Fixture
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
                'titulo' => 'Harry Potter e a Pedra Filosofal',
                'editora' => 'Rocco',
                'edicao' => 1,
                'anoPublicacao' => '1997',
                'autor' => [
                    'nome' => 'J.K. Rowling',
                ],
                'assunto' => [
                    'descricao' => 'Fantasia'
                ]
            ],
            [
                'titulo' => 'O Hobbit',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1937',
                'autor' => [
                    'nome' => 'J.R.R. Tolkien',
                ],
                'assunto' => [
                    'descricao' => 'Comedia e drama'
                ]
            ],
            [
                'titulo' => 'A Cidade e as Estrelas',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1956',
                'autor' => [
                    'nome' => 'Arthur C. Clarke',
                ],
                'assunto' => [
                    'descricao' => 'Fic o Cientifica'
                ]
            ],
            [
                'titulo' => 'O Senhor dos An eis',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1954',
                'autor' => [
                    'nome' => 'J.R.R. Tolkien',
                ],
                'assunto' => [
                    'descricao' => 'Fantasia'
                ]
            ],
            [
                'titulo' => 'O Homem que Calculava',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1938',
                'autor' => [
                    'nome' => 'Malba Tahan',
                ],
                'assunto' => [
                    'descricao' => 'Matem tica'
                ]
            ],
            [
                'titulo' => 'O Nome do Vento',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '2007',
                'autor' => [
                    'nome' => 'Patrick Rothfuss',
                ],
                'assunto' => [
                    'descricao' => 'Fantasia'
                ]
            ],
            [
                'titulo' => 'O Cavaleiro da Rosa',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1954',
                'autor' => [
                    'nome' => 'J.R.R. Tolkien',
                ],
                'assunto' => [
                    'descricao' => 'Fantasia'
                ]
            ],
            [
                'titulo' => 'O Senhor dos An eis',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1954',
                'autor' => [
                    'nome' => 'J.R.R. Tolkien',
                ],
                'assunto' => [
                    'descricao' => 'Fantasia'
                ]
            ],
            [
                'titulo' => 'O Hobbit',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1937',
                'autor' => [
                    'nome' => 'J.R.R. Tolkien',
                ],
                'assunto' => [
                    'descricao' => 'Comedia e drama'
                ]
            ],
            [
                'titulo' => 'A Cidade e as Estrelas',
                'editora' => 'HarperCollins',
                'edicao' => 2,
                'anoPublicacao' => '1956',
                'autor' => [
                    'nome' => 'Arthur C. Clarke',
                ],
                'assunto' => [
                    'descricao' => 'Fic o Cientifica'
                ]
            ],
        ];
    }
}
