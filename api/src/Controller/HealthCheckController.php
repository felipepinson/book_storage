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
        $load   = sys_getloadavg();
        $memory = [
            "usage"      => memory_get_usage(),
            "peak_usage" => memory_get_peak_usage()
        ];

        $response = [
            "message"  => "Everything's fine here :) Thanks for checking!",
            "memory"   => $memory,
            "load_avg" => $load
        ];

        $logger->debug('Health check OK');

        return $this->json($response);
    }
}