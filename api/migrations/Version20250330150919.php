<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250330150919 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE assunto (codAs INT AUTO_INCREMENT NOT NULL, descricao VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_B9F0BE022B85EB (descricao), PRIMARY KEY(codAs)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE autor (codAu INT AUTO_INCREMENT NOT NULL, nome VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_31075EBA54BD530C (nome), PRIMARY KEY(codAu)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE livro (codl INT AUTO_INCREMENT NOT NULL, titulo VARCHAR(40) NOT NULL, editora VARCHAR(40) NOT NULL, edicao INT NOT NULL, ano_publicacao VARCHAR(4) NOT NULL, PRIMARY KEY(codl)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE livro_autor (livro_codl INT NOT NULL, autor_codAu INT NOT NULL, INDEX Livro_Autor_FKIndex1 (livro_codl), INDEX Livro_Autor_FKIndex2 (autor_codAu), PRIMARY KEY(livro_codl, autor_codAu)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE livro_assunto (livro_codl INT NOT NULL, assunto_codAs INT NOT NULL, INDEX Livro_Assunto_FKIndex1 (livro_codl), INDEX Livro_Assunto_FKIndex2 (assunto_codAs), PRIMARY KEY(livro_codl, assunto_codAs)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_autor ADD CONSTRAINT Livro_Autor_FKIndex1 FOREIGN KEY (livro_codl) REFERENCES livro (codl)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_autor ADD CONSTRAINT Livro_Autor_FKIndex2 FOREIGN KEY (autor_codAu) REFERENCES autor (codAu)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_assunto ADD CONSTRAINT Livro_Assunto_FKIndex1 FOREIGN KEY (livro_codl) REFERENCES livro (codl)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_assunto ADD CONSTRAINT Livro_Assunto_FKIndex2 FOREIGN KEY (assunto_codAs) REFERENCES assunto (codAs)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_autor DROP FOREIGN KEY Livro_Autor_FKIndex1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_autor DROP FOREIGN KEY Livro_Autor_FKIndex2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_assunto DROP FOREIGN KEY Livro_Assunto_FKIndex1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livro_assunto DROP FOREIGN KEY Livro_Assunto_FKIndex2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE assunto
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE autor
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE livro
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE livro_autor
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE livro_assunto
        SQL);
    }
}
