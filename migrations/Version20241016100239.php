<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016100239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usucurso (id INT AUTO_INCREMENT NOT NULL, id_curso_id INT NOT NULL, id_usuario_id INT NOT NULL, nota INT DEFAULT NULL, INDEX IDX_BE4A1A8D710A68A (id_curso_id), INDEX IDX_BE4A1A87EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usucurso ADD CONSTRAINT FK_BE4A1A8D710A68A FOREIGN KEY (id_curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE usucurso ADD CONSTRAINT FK_BE4A1A87EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE usuario_curso DROP FOREIGN KEY FK_D7E52AF287CB4A1F');
        $this->addSql('ALTER TABLE usuario_curso DROP FOREIGN KEY FK_D7E52AF2DB38439E');
        $this->addSql('DROP TABLE usuario_curso');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_curso (usuario_id INT NOT NULL, curso_id INT NOT NULL, INDEX IDX_D7E52AF2DB38439E (usuario_id), INDEX IDX_D7E52AF287CB4A1F (curso_id), PRIMARY KEY(usuario_id, curso_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE usuario_curso ADD CONSTRAINT FK_D7E52AF287CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_curso ADD CONSTRAINT FK_D7E52AF2DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usucurso DROP FOREIGN KEY FK_BE4A1A8D710A68A');
        $this->addSql('ALTER TABLE usucurso DROP FOREIGN KEY FK_BE4A1A87EB2C349');
        $this->addSql('DROP TABLE usucurso');
    }
}
