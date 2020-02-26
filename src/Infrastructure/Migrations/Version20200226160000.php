<?php

declare(strict_types=1);

namespace App\Infrastructure\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200226160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE products (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_lines (product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', price VARCHAR(255) NOT NULL, count INT NOT NULL, INDEX IDX_CC9FF86B4584665A (product_id), INDEX IDX_CC9FF86B8D9F6D38 (order_id), PRIMARY KEY(product_id, order_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', status VARCHAR(255) NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_lines ADD CONSTRAINT FK_CC9FF86B4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE order_lines ADD CONSTRAINT FK_CC9FF86B8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_lines DROP FOREIGN KEY FK_CC9FF86B4584665A');
        $this->addSql('ALTER TABLE order_lines DROP FOREIGN KEY FK_CC9FF86B8D9F6D38');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE order_lines');
        $this->addSql('DROP TABLE orders');
    }
}
