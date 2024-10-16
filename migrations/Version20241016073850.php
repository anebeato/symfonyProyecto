<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016073850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add nota field to usuario_curso table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE curso (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, admin TINYINT(1) NOT NULL, foto VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario_curso (usuario_id INT NOT NULL, curso_id INT NOT NULL, nota FLOAT DEFAULT NULL, INDEX IDX_D7E52AF2DB38439E (usuario_id), INDEX IDX_D7E52AF287CB4A1F (curso_id), PRIMARY KEY(usuario_id, curso_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usuario_curso ADD CONSTRAINT FK_D7E52AF2DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_curso ADD CONSTRAINT FK_D7E52AF287CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_curso DROP FOREIGN KEY FK_D7E52AF2DB38439E');
        $this->addSql('ALTER TABLE usuario_curso DROP FOREIGN KEY FK_D7E52AF287CB4A1F');
        $this->addSql('DROP TABLE curso');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('DROP TABLE usuario_curso');
    }
}
