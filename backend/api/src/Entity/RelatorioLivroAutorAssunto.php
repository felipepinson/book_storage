<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'vw_relatorio_livros_autores_assuntos')]

class RelatorioLivroAutorAssunto
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', name: "livro_id")]
    private string $livroId;

    #[ORM\Column(type: 'integer', name: "autor_id")]
    private int $autorId;

    #[ORM\Column(type: 'string', name: "nome_autor")]
    private string $nomeAutor;

    #[ORM\Column(type: 'string')]
    private string $titulo;

    #[ORM\Column(type: 'string')]
    private string $editora;

    #[ORM\Column(type: 'string')]
    private string $edicao;

    #[ORM\Column(type: 'string', name: "ano_publicacao")]
    private string $anoPublicacao;

    #[ORM\Column(type: 'string')]
    private string $assuntos;

    public function getAutorId(): int { return $this->autorId; }
    public function getNomeAutor(): string { return $this->nomeAutor; }
    public function getLivroId(): string { return $this->livroId; }
    public function getTitulo(): string { return $this->titulo; }
    public function getEditora(): string { return $this->editora; }
    public function getEdicao(): string { return $this->edicao; }
    public function getAnoPublicacao(): string { return $this->anoPublicacao; }
    public function getAssuntos(): string { return $this->assuntos; }
}
