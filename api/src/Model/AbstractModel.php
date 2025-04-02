<?php

namespace App\Model;

use App\Entity\AbstractEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractModel
{
    protected $em;
    protected $validator;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ){
        $this->em = $em;
        $this->validator = $validator;
    }

    public function persist(object $obj): void
    {
        $this->em->persist($obj);
    }

    public function flush(): void
    {
        $this->em->flush();
    }

    public function persistAndFlush(object $obj): void
    {
        $this->persist($obj);
        $this->flush();
    }

    public function validatorErrors(AbstractEntity $obj): ?array
    {
        $erros = $this->validator->validate($obj);

        if (count($erros) > 0) {
            $errosFormatados = [];

            foreach ($erros as $erro) {
                $errosFormatados[$erro->getPropertyPath()][] = $erro->getMessage();
            }

            return $errosFormatados;
        }

        return null;
    }
}
