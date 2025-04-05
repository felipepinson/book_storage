<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250404173106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Restringir cadastro de livro para autor, garantindo que não sejam cadastrados com dupliciades';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TRIGGER validar_livro_autor_unico
            BEFORE INSERT ON livro_autor
            FOR EACH ROW
            BEGIN
                DECLARE livro_existente INT;
                SELECT COUNT(*)
                INTO livro_existente
                FROM livro l
                JOIN livro_autor la ON l.codl = la.livro_codl
                WHERE l.titulo = (SELECT titulo FROM livro WHERE codl = NEW.livro_codl)
                AND l.editora = (SELECT editora FROM livro WHERE codl = NEW.livro_codl)
                AND l.edicao = (SELECT edicao FROM livro WHERE codl = NEW.livro_codl)
                AND l.ano_publicacao = (SELECT ano_publicacao FROM livro WHERE codl = NEW.livro_codl)
                AND la.autor_codAu = NEW.autor_codAu;

                IF livro_existente > 0 THEN
                    SIGNAL SQLSTATE '23505'
                    SET MESSAGE_TEXT = 'Este livro já foi cadastrado para esse autor!';
                END IF;
            END;
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TRIGGER IF EXISTS validar_livro_autor_unico;");
    }
}
