<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110202110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '1.0.0 - First version of the model';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE attack_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE attack (id INT NOT NULL, type_id INT NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, power INT DEFAULT NULL, pp INT NOT NULL, is_physical BOOLEAN NOT NULL, accuracy INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47C02D3BC54C8C93 ON attack (type_id)');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, name VARCHAR(255) NOT NULL, attack INT NOT NULL, defense INT NOT NULL, speed INT NOT NULL, special INT NOT NULL, accuracy INT DEFAULT 100 NOT NULL, evasion INT DEFAULT 100 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon_type (pokemon_id INT NOT NULL, type_id INT NOT NULL, PRIMARY KEY(pokemon_id, type_id))');
        $this->addSql('CREATE INDEX IDX_B077296A2FE71C3E ON pokemon_type (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_B077296AC54C8C93 ON pokemon_type (type_id)');
        $this->addSql('CREATE TABLE pokemon_attack (pokemon_id INT NOT NULL, attack_id INT NOT NULL, PRIMARY KEY(pokemon_id, attack_id))');
        $this->addSql('CREATE INDEX IDX_2B29516F2FE71C3E ON pokemon_attack (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_2B29516FF5315759 ON pokemon_attack (attack_id)');
        $this->addSql('CREATE TABLE type (id INT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE types_strength (type_source INT NOT NULL, type_target INT NOT NULL, PRIMARY KEY(type_source, type_target))');
        $this->addSql('CREATE INDEX IDX_19DE06F67C5D144B ON types_strength (type_source)');
        $this->addSql('CREATE INDEX IDX_19DE06F665B844C4 ON types_strength (type_target)');
        $this->addSql('CREATE TABLE types_weakness (type_source INT NOT NULL, type_target INT NOT NULL, PRIMARY KEY(type_source, type_target))');
        $this->addSql('CREATE INDEX IDX_A0EFDA677C5D144B ON types_weakness (type_source)');
        $this->addSql('CREATE INDEX IDX_A0EFDA6765B844C4 ON types_weakness (type_target)');
        $this->addSql('CREATE TABLE types_uselessness (type_source INT NOT NULL, type_target INT NOT NULL, PRIMARY KEY(type_source, type_target))');
        $this->addSql('CREATE INDEX IDX_2AB7FF567C5D144B ON types_uselessness (type_source)');
        $this->addSql('CREATE INDEX IDX_2AB7FF5665B844C4 ON types_uselessness (type_target)');
        $this->addSql('ALTER TABLE attack ADD CONSTRAINT FK_47C02D3BC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296A2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_attack ADD CONSTRAINT FK_2B29516F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_attack ADD CONSTRAINT FK_2B29516FF5315759 FOREIGN KEY (attack_id) REFERENCES attack (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE types_strength ADD CONSTRAINT FK_19DE06F67C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE types_strength ADD CONSTRAINT FK_19DE06F665B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE types_weakness ADD CONSTRAINT FK_A0EFDA677C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE types_weakness ADD CONSTRAINT FK_A0EFDA6765B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE types_uselessness ADD CONSTRAINT FK_2AB7FF567C5D144B FOREIGN KEY (type_source) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE types_uselessness ADD CONSTRAINT FK_2AB7FF5665B844C4 FOREIGN KEY (type_target) REFERENCES type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE attack_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_id_seq CASCADE');
        $this->addSql('ALTER TABLE attack DROP CONSTRAINT FK_47C02D3BC54C8C93');
        $this->addSql('ALTER TABLE pokemon_type DROP CONSTRAINT FK_B077296A2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP CONSTRAINT FK_B077296AC54C8C93');
        $this->addSql('ALTER TABLE pokemon_attack DROP CONSTRAINT FK_2B29516F2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_attack DROP CONSTRAINT FK_2B29516FF5315759');
        $this->addSql('ALTER TABLE types_strength DROP CONSTRAINT FK_19DE06F67C5D144B');
        $this->addSql('ALTER TABLE types_strength DROP CONSTRAINT FK_19DE06F665B844C4');
        $this->addSql('ALTER TABLE types_weakness DROP CONSTRAINT FK_A0EFDA677C5D144B');
        $this->addSql('ALTER TABLE types_weakness DROP CONSTRAINT FK_A0EFDA6765B844C4');
        $this->addSql('ALTER TABLE types_uselessness DROP CONSTRAINT FK_2AB7FF567C5D144B');
        $this->addSql('ALTER TABLE types_uselessness DROP CONSTRAINT FK_2AB7FF5665B844C4');
        $this->addSql('DROP TABLE attack');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_type');
        $this->addSql('DROP TABLE pokemon_attack');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE types_strength');
        $this->addSql('DROP TABLE types_weakness');
        $this->addSql('DROP TABLE types_uselessness');
    }
}
