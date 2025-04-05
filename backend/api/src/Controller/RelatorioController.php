<?php

namespace App\Controller;

use App\Entity\RelatorioLivroAutorAssunto;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RelatorioController extends AbstractController
{
    public function index(EntityManagerInterface $em): Response
    {
        $repo = $em->getRepository(RelatorioLivroAutorAssunto::class);
        $dados = $repo->findby([], ['nomeAutor' => 'ASC']);

        $agrupadoPorAutor = [];

        foreach ($dados as $item) {
            $autorId = $item->getAutorId();
            $autorNome = $item->getNomeAutor();
            if (!isset($agrupadoPorAutor[$autorId])) {
                $agrupadoPorAutor[$autorId] = [
                    'nome' => $autorNome,
                    'livros' => [],
                ];
            }

            $agrupadoPorAutor[$autorId]['livros'][] = $item;
        }

        // 1. Configurar o Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // 2. Renderizar o HTML com Twig
        $html = $this->renderView('relatorio/livro-por-autor.twig', [
            'titulo' => 'Relatório de Livros',
            'data' => date('d/m/Y H:i'),
            'lista' => $agrupadoPorAutor
        ]);

        $dompdf->set_option('isHtml5ParserEnabled', true);
        $dompdf->set_option('isPhpEnabled', true);
        // 3. Carregar HTML no Dompdf
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 4. Retornar como resposta
        return new Response(
            $dompdf->output(),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="relatorio.pdf"',
            ]
        );
    }
}
