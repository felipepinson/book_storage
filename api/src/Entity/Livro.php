<?php

namespace App\Entity;

use App\Repository\LivroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LivroRepository::class)]
class Livro extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "codl")]
    #[Groups(["livro"])]
    private ?int $id = null;

    #[ORM\Column(length: 40)]
    #[Groups(["livro"])]
    private ?string $titulo = null;

    #[ORM\Column(length: 40)]
    #[Groups(["livro"])]
    private ?string $editora = null;

    #[ORM\Column]
    #[Groups(["livro"])]
    private ?int $edicao = null;

    #[ORM\Column(length: 4)]
    #[Groups(["livro"])]
    private ?string $anoPublicacao = null;

    /**
     * @var Collection<int, Autor>
     */
    #[ORM\ManyToMany(targetEntity: Autor::class, inversedBy: 'livros', fetch: 'EAGER')]
    #[ORM\JoinTable(name: "livro_autor")]
    #[ORM\JoinColumn(name: "livro_codl", referencedColumnName: "codl")]
    #[ORM\InverseJoinColumn(name: "autor_codAu", referencedColumnName: "codAu")]
    #[Groups(["livro"])]
    private Collection $autores;

    /**
     * @var Collection<int, Assunto>
     */
    #[ORM\ManyToMany(targetEntity: Assunto::class, inversedBy: 'livros', fetch: 'EAGER')]
    #[ORM\JoinTable(name: "livro_assunto")]
    #[ORM\JoinColumn(name: "livro_codl", referencedColumnName: "codl")]
    #[ORM\InverseJoinColumn(name: "assunto_codAs", referencedColumnName: "codAs")]
    #[Groups(["livro"])]
    private Collection $assuntos;

    public function __construct()
    {
        $this->autores = new ArrayCollection();
        $this->assuntos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getEditora(): ?string
    {
        return $this->editora;
    }

    public function setEditora(string $editora): static
    {
        $this->editora = $editora;

        return $this;
    }

    public function getEdicao(): ?int
    {
        return $this->edicao;
    }

    public function setEdicao(int $edicao): static
    {
        $this->edicao = $edicao;

        return $this;
    }

    public function getAnoPublicacao(): ?string
    {
        return $this->anoPublicacao;
    }

    public function setAnoPublicacao(string $anoPublicacao): static
    {
        $this->anoPublicacao = $anoPublicacao;

        return $this;
    }

    /**
     * @return Collection<int, Autor>
     */
    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function setAutores(array $autores): static
    {
        foreach ($autores as $autor) {
            $this->addAutor($autor);
        }

        return $this;
    }

    public function addAutor(Autor $novoAutor): static
    {
        foreach ($this->autores as $autor) {
            if (!empty($autor) && $autor->getNome() === $novoAutor->getNome()) {
                return $this;
            }
        }

        $this->autores->add($novoAutor);

        return $this;
    }

    public function removeAutor(Autor $autore): static
    {
        $this->autores->removeElement($autore);

        return $this;
    }

    /**
     * @return Collection<int, Assunto>
     */
    public function getAssuntos(): Collection
    {
        return $this->assuntos;
    }

    public function setAssuntos(array $assuntos): static
    {
        foreach ($assuntos as $assunto) {
            $this->addAssunto($assunto);
        }

        return $this;
    }

    public function addAssunto(Assunto $novoAssunto): static
    {
        foreach ($this->assuntos as $assunto) {
            if (!empty($assunto) && $assunto->getDescricao() === $novoAssunto->getDescricao()) {
                return $this;
            }
        }

        $this->assuntos->add($novoAssunto);

        return $this;
    }

    public function removeAssunto(Assunto $assunto): static
    {
        $this->assuntos->removeElement($assunto);

        return $this;
    }
}
