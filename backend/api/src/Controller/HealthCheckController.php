<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * HealthCheckController
 */
class HealthCheckController extends AbstractController
{
    public function health(LoggerInterface $logger): JsonResponse
    {
        $response = [
            "message"  => "Tudo ok :) Obrigado por confirmar!",
        ];

        $logger->debug('Health check OK');

        return $this->json($response);
    }
}