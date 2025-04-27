<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250427191947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE category_entity_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE product_entity_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE category_entity (id INT NOT NULL, code VARCHAR(10) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_entity (id INT NOT NULL, name TEXT NOT NULL, price NUMERIC(10, 2) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_entity_category_entity (product_entity_id INT NOT NULL, category_entity_id INT NOT NULL, PRIMARY KEY(product_entity_id, category_entity_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_30D33891EF85CBD0 ON product_entity_category_entity (product_entity_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_30D338914645AF6D ON product_entity_category_entity (category_entity_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_entity_category_entity ADD CONSTRAINT FK_30D33891EF85CBD0 FOREIGN KEY (product_entity_id) REFERENCES product_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_entity_category_entity ADD CONSTRAINT FK_30D338914645AF6D FOREIGN KEY (category_entity_id) REFERENCES category_entity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE category_entity_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE product_entity_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_entity_category_entity DROP CONSTRAINT FK_30D33891EF85CBD0
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_entity_category_entity DROP CONSTRAINT FK_30D338914645AF6D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category_entity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_entity
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_entity_category_entity
        SQL);
    }
}
