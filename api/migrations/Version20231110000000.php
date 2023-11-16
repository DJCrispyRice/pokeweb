<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '1.0.0 - Database first version';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE attacks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attacks (id INT NOT NULL, type_id INT NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, power INT DEFAULT NULL, pp INT NOT NULL, is_physical BOOLEAN NOT NULL, accuracy INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA42E602C54C8C93 ON attacks (type_id)');
        $this->addSql('CREATE TABLE pokemons (id INT NOT NULL, name VARCHAR(255) NOT NULL, hp INT NOT NULL, attack INT NOT NULL, defense INT NOT NULL, speed INT NOT NULL, special INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon_types (pokemon_id INT NOT NULL, type_id INT NOT NULL, PRIMARY KEY(pokemon_id, type_id))');
        $this->addSql('CREATE INDEX IDX_B6D930642FE71C3E ON pokemon_types (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_B6D93064C54C8C93 ON pokemon_types (type_id)');
        $this->addSql('CREATE TABLE pokemon_attacks (pokemon_id INT NOT NULL, attack_id INT NOT NULL, PRIMARY KEY(pokemon_id, attack_id))');
        $this->addSql('CREATE INDEX IDX_C6289A932FE71C3E ON pokemon_attacks (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_C6289A93F5315759 ON pokemon_attacks (attack_id)');
        $this->addSql('CREATE TABLE types (id INT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE type_strengths (type_source INT NOT NULL, type_target INT NOT NULL, PRIMARY KEY(type_source, type_target))');
        $this->addSql('CREATE INDEX IDX_27166D1C7C5D144B ON type_strengths (type_source)');
        $this->addSql('CREATE INDEX IDX_27166D1C65B844C4 ON type_strengths (type_target)');
        $this->addSql('CREATE TABLE type_weakness (type_source INT NOT NULL, type_target INT NOT NULL, PRIMARY KEY(type_source, type_target))');
        $this->addSql('CREATE INDEX IDX_A2771AB57C5D144B ON type_weakness (type_source)');
        $this->addSql('CREATE INDEX IDX_A2771AB565B844C4 ON type_weakness (type_target)');
        $this->addSql('CREATE TABLE type_uselessness (type_source INT NOT NULL, type_target INT NOT NULL, PRIMARY KEY(type_source, type_target))');
        $this->addSql('CREATE INDEX IDX_9194D2327C5D144B ON type_uselessness (type_source)');
        $this->addSql('CREATE INDEX IDX_9194D23265B844C4 ON type_uselessness (type_target)');
        $this->addSql('ALTER TABLE attacks ADD CONSTRAINT FK_AA42E602C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_types ADD CONSTRAINT FK_B6D930642FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemons (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_types ADD CONSTRAINT FK_B6D93064C54C8C93 FOREIGN KEY (type_id) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_attacks ADD CONSTRAINT FK_C6289A932FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemons (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_attacks ADD CONSTRAINT FK_C6289A93F5315759 FOREIGN KEY (attack_id) REFERENCES attacks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_strengths ADD CONSTRAINT FK_27166D1C7C5D144B FOREIGN KEY (type_source) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_strengths ADD CONSTRAINT FK_27166D1C65B844C4 FOREIGN KEY (type_target) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_weakness ADD CONSTRAINT FK_A2771AB57C5D144B FOREIGN KEY (type_source) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_weakness ADD CONSTRAINT FK_A2771AB565B844C4 FOREIGN KEY (type_target) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_uselessness ADD CONSTRAINT FK_9194D2327C5D144B FOREIGN KEY (type_source) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type_uselessness ADD CONSTRAINT FK_9194D23265B844C4 FOREIGN KEY (type_target) REFERENCES types (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE attacks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE types_id_seq CASCADE');
        $this->addSql('ALTER TABLE attacks DROP CONSTRAINT FK_AA42E602C54C8C93');
        $this->addSql('ALTER TABLE pokemon_types DROP CONSTRAINT FK_B6D930642FE71C3E');
        $this->addSql('ALTER TABLE pokemon_types DROP CONSTRAINT FK_B6D93064C54C8C93');
        $this->addSql('ALTER TABLE pokemon_attacks DROP CONSTRAINT FK_C6289A932FE71C3E');
        $this->addSql('ALTER TABLE pokemon_attacks DROP CONSTRAINT FK_C6289A93F5315759');
        $this->addSql('ALTER TABLE type_strengths DROP CONSTRAINT FK_27166D1C7C5D144B');
        $this->addSql('ALTER TABLE type_strengths DROP CONSTRAINT FK_27166D1C65B844C4');
        $this->addSql('ALTER TABLE type_weakness DROP CONSTRAINT FK_A2771AB57C5D144B');
        $this->addSql('ALTER TABLE type_weakness DROP CONSTRAINT FK_A2771AB565B844C4');
        $this->addSql('ALTER TABLE type_uselessness DROP CONSTRAINT FK_9194D2327C5D144B');
        $this->addSql('ALTER TABLE type_uselessness DROP CONSTRAINT FK_9194D23265B844C4');
        $this->addSql('DROP TABLE attacks');
        $this->addSql('DROP TABLE pokemons');
        $this->addSql('DROP TABLE pokemon_types');
        $this->addSql('DROP TABLE pokemon_attacks');
        $this->addSql('DROP TABLE types');
        $this->addSql('DROP TABLE type_strengths');
        $this->addSql('DROP TABLE type_weakness');
        $this->addSql('DROP TABLE type_uselessness');
    }
}
