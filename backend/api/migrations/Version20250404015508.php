<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404015508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE VIEW vw_relatorio_livros_autores_assuntos AS
            SELECT
                a.codAu AS autor_id,
                a.nome AS nome_autor,
                l.codl AS livro_id,
                l.titulo,
                l.editora,
                l.edicao,
                l.ano_publicacao,
                GROUP_CONCAT(DISTINCT ass.descricao ORDER BY ass.descricao SEPARATOR ', ') AS assuntos,
                l.preco
            FROM
                livro l
                LEFT JOIN livro_autor la ON l.codl = la.livro_codl
                LEFT JOIN autor a ON la.autor_codAu = a.codAu
                LEFT JOIN livro_assunto las ON l.codl = las.livro_codl
                LEFT JOIN assunto ass ON las.assunto_codAs = ass.codAs
            GROUP BY a.codAu, l.codl
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            $this->addSql('DROP VIEW IF EXISTS vw_relatorio_livros_autores_assuntos');
        SQL);
    }
}
