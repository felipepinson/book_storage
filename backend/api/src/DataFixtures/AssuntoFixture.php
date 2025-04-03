<?php

namespace App\DataFixtures;

use App\Entity\Assunto;
use App\Entity\Autor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livro;

class AssuntoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->assuntosMock() as $info) {
            $assunto = new Assunto();
            $assunto->setDescricao($info['descricao']);

            $manager->persist($assunto);
        }

        $manager->flush();
    }

    public function assuntosMock()
    {
        return [
            ['descricao' => 'Aventura'],
            ['descricao' => 'Fic o Cient fico'],
            ['descricao' => 'Romance'],
            ['descricao' => 'Terror'],
            ['descricao' => 'Suspense'],
            ['descricao' => 'Fantasia'],
            ['descricao' => 'A o'],
            ['descricao' => 'Humor'],
            ['descricao' => 'Infantil'],
            ['descricao' => 'Jovem Adulto'],
        ];
    }
}
