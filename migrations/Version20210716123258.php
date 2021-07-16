<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210716123258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_type (pokemon_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B077296A2FE71C3E (pokemon_id), INDEX IDX_B077296AC54C8C93 (type_id), PRIMARY KEY(pokemon_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_attack (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, attack_id INT NOT NULL, level INT NOT NULL, INDEX IDX_2B29516F2FE71C3E (pokemon_id), INDEX IDX_2B29516FF5315759 (attack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296A2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_attack ADD CONSTRAINT FK_2B29516F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_attack ADD CONSTRAINT FK_2B29516FF5315759 FOREIGN KEY (attack_id) REFERENCES attack (id)');
        $this->addSql('ALTER TABLE attack ADD type_id INT NOT NULL');
        $this->addSql('ALTER TABLE attack ADD CONSTRAINT FK_47C02D3BC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_47C02D3BC54C8C93 ON attack (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pokemon_type');
        $this->addSql('DROP TABLE pokemon_attack');
        $this->addSql('ALTER TABLE attack DROP FOREIGN KEY FK_47C02D3BC54C8C93');
        $this->addSql('DROP INDEX IDX_47C02D3BC54C8C93 ON attack');
        $this->addSql('ALTER TABLE attack DROP type_id');
    }
}
