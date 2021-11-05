<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211104123146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shop_carts (uuid VARCHAR(36) NOT NULL, amount DOUBLE PRECISION NOT NULL, currency_code VARCHAR(255) NOT NULL, PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_carts_products (shop_cart_uuid VARCHAR(36) NOT NULL, product_uuid VARCHAR(36) NOT NULL, quantity INT NOT NULL, amount DOUBLE PRECISION NOT NULL, currency_code VARCHAR(255) NOT NULL, INDEX IDX_8244DAFD272DDE14 (shop_cart_uuid), INDEX IDX_8244DAFD5C977207 (product_uuid), PRIMARY KEY(shop_cart_uuid, product_uuid)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_carts_products ADD CONSTRAINT FK_8244DAFD272DDE14 FOREIGN KEY (shop_cart_uuid) REFERENCES shop_carts (uuid)');
        $this->addSql('ALTER TABLE shop_carts_products ADD CONSTRAINT FK_8244DAFD5C977207 FOREIGN KEY (product_uuid) REFERENCES products (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shop_carts_products DROP FOREIGN KEY FK_8244DAFD272DDE14');
        $this->addSql('DROP TABLE shop_carts');
        $this->addSql('DROP TABLE shop_carts_products');
    }
}
