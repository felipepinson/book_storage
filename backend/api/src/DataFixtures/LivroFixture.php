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
            $livro->setPreco($infoLivro['preco']);

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
                'titulo' => 'Dom Quixote',
                'editora' => 'Martins Fontes',
                'edicao' => 1,
                'anoPublicacao' => '1605',
                'preco' => 19.90,
                'autor' => [
                    'nome' => 'Miguel de Machado'
                ],
                'assunto' => [
                    'descricao' => 'Drama'
                ]
            ],
            [
                'titulo' => 'O Senhor dos An eis',
                'editora' => 'HarperCollins Brasil',
                'edicao' => 1,
                'anoPublicacao' => '1954',
                'preco' => 39.90,
                'autor' => [
                    'nome' => 'J.R.R. Tolkien II'
                ],
                'assunto' => [
                    'descricao' => 'Mundo Mistico'
                ]
            ],
            [
                'titulo' => '1984',
                'editora' => 'Companhia das Letras',
                'edicao' => 1,
                'anoPublicacao' => '1949',
                'preco' => 29.90,
                'autor' => [
                    'nome' => 'George Orwell II'
                ],
                'assunto' => [
                    'descricao' => 'Artes'
                ]
            ],
            [
                'titulo' => 'O Processo',
                'editora' => 'Globo',
                'edicao' => 1,
                'anoPublicacao' => '1925',
                'preco' => 19.90,
                'autor' => [
                    'nome' => 'Franz Kafka'
                ],
                'assunto' => [
                    'descricao' => 'Policial'
                ]
            ],
            [
                'titulo' => 'A Revolu o dos Bichos',
                'editora' => 'Companhia das Letras',
                'edicao' => 1,
                'anoPublicacao' => '1945',
                'preco' => 29.90,
                'autor' => [
                    'nome' => 'George Orwell'
                ],
                'assunto' => [
                    'descricao' => 'Mundo Animal'
                ]
            ],
            [
                'titulo' => 'O Aprendiz de Feiticeiro',
                'editora' => 'HarperCollins Brasil',
                'edicao' => 1,
                'anoPublicacao' => '1950',
                'preco' => 29.90,
                'autor' => [
                    'nome' => 'C.S. Lucas'
                ],
                'assunto' => [
                    'descricao' => 'Bruxaria'
                ]
            ],
            [
                'titulo' => 'O Hobbit',
                'editora' => 'HarperCollins Brasil',
                'edicao' => 1,
                'anoPublicacao' => '1937',
                'preco' => 29.90,
                'autor' => [
                    'nome' => 'J.R.R. Andrew'
                ],
                'assunto' => [
                    'descricao' => 'Mistico'
                ]
            ],
            [
                'titulo' => 'O Nome do Vento',
                'editora' => 'Globo',
                'edicao' => 1,
                'anoPublicacao' => '2002',
                'preco' => 29.90,
                'autor' => [
                    'nome' => 'Patrick Rothfuss'
                ],
                'assunto' => [
                    'descricao' => 'Abracadabra'
                ]
            ],
            [
                'titulo' => 'O Conto da Aia',
                'editora' => 'Globo',
                'edicao' => 1,
                'anoPublicacao' => '1986',
                'preco' => 29.90,
                'autor' => [
                    'nome' => 'Margaret Atwood'
                ],
                'assunto' => [
                    'descricao' => 'Ficão Cient.'
                ]
            ],
            [
                'titulo' => 'A Guerra dos Mundos',
                'editora' => 'Companhia das Letras',
                'edicao' => 1,
                'preco' => 29.90,
                'anoPublicacao' => '1898',
                'autor' => [
                    'nome' => 'H.G. Wells II'
                ],
                'assunto' => [
                    'descricao' => 'Batalhas'
                ]
            ]
        ];
    }
}
