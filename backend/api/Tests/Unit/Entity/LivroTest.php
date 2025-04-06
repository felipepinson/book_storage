<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Livro;
use PHPUnit\Framework\TestCase;

class LivroTest extends TestCase
{
    public function testLivroSettersAndGetters(): void
    {
        $livro = new Livro();
        $livro->setTitulo("O Senhor dos Anéis");
        $livro->setEditora("HarperCollins");
        $livro->setEdicao(1);
        $livro->setAnoPublicacao("1954");

        $this->assertEquals("O Senhor dos Anéis", $livro->getTitulo());
        $this->assertEquals("HarperCollins", $livro->getEditora());
        $this->assertEquals(1, $livro->getEdicao());
        $this->assertEquals("1954", $livro->getAnoPublicacao());
    }
}