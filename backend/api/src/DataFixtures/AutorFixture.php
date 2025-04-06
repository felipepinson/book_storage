<?php

namespace App\DataFixtures;

use App\Entity\Autor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AutorFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->autoresMock() as $info) {
            $autor = new Autor();
            $autor->setNome($info['nome']);

            $manager->persist($autor);
        }

        $manager->flush();
    }

    public function autoresMock()
    {
        return [
            ['nome' => 'J.K. Rowling'],
            ['nome' => 'J.R.R. Tolkien'],
            ['nome' => 'C.S. Lewis'],
            ['nome' => 'George R.R. Martin'],
            ['nome' => 'Jorge Luis Borges'],
            ['nome' => 'Gabriel Garcia Marquez'],
            ['nome' => 'Umberto Eco'],
            ['nome' => 'Isaac Asimov'],
            ['nome' => 'Ray Bradbury'],
            ['nome' => 'Philip K. Dick'],
        ];
    }
}
