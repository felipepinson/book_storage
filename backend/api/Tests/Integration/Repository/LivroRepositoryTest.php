<?php

namespace App\Tests\Integration\Repository;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LivroRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testSalvarLivro(): void
    {
        // 1. Criar o Autor
        $autor = new Autor();
        $autor->setNome("J.K. Rowling");

        // 1. Criar um Assunto
        $assunto = new Assunto();
        $assunto->setDescricao("Comedia e drama");


        $livro = new Livro();
        $livro->setTitulo("O Hobbit");
        $livro->setEditora("HarperCollins");
        $livro->setEdicao(2);
        $livro->setAnoPublicacao("1937");

        // 3. Associar o Autor ao Livro
        $livro->addAutor($autor);
        $livro->addAssunto($assunto); // Associação ManyToMany

        $this->entityManager->persist($assunto);
        $this->entityManager->persist($autor);

        $this->entityManager->persist($livro);
        $this->entityManager->flush();

        $livroSalvo = $this->entityManager->getRepository(Livro::class)->findOneBy(['titulo' => 'O Hobbit']);

        $autores = $livroSalvo->getAutores();

        $this->assertNotNull($livroSalvo);
        $this->assertEquals("O Hobbit", $livroSalvo->getTitulo());
        //$this->assertEquals("J.K. Rowling", $livroSalvo->getAutores()->first()->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }
}
