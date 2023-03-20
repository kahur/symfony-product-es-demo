<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230319133943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Product tables migration';
    }

    public function up(Schema $schema): void
    {
        $productTableSQL = '
                CREATE TABLE IF NOT EXISTS `product` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `title` VARCHAR(255) NOT NULL,
                  `short_desc` VARCHAR(255) NOT NULL,
                  `created_at` DATETIME NULL DEFAULT NOW(),
                  `updated_at` DATETIME NULL DEFAULT NOW(),
                  PRIMARY KEY (`id`)
                )
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8mb4
                COLLATE = utf8mb4_unicode_ci';

        $this->addSql($productTableSQL);

        $productDetailTableSQL = '
                CREATE TABLE IF NOT EXISTS `product_detail` (
                  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
                  `product_id` INT(11) NOT NULL,
                  `detail_name` VARCHAR(255) NOT NULL,
                  `detail_value` LONGTEXT NOT NULL,
                  `created_at` DATETIME NULL DEFAULT NOW(),
                  `updated_at` DATETIME NULL DEFAULT NOW(),
                  PRIMARY KEY (`id`),
                  INDEX `fk_product_detail_product_idx` (`product_id` ASC) VISIBLE,
                  CONSTRAINT `fk_product_detail_product`
                    FOREIGN KEY (`product_id`)
                    REFERENCES `product` (`id`)
                    ON DELETE CASCADE
                    ON UPDATE NO ACTION
                )
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8mb4
                COLLATE = utf8mb4_unicode_ci';

        $this->addSql($productDetailTableSQL);;

    }

    public function down(Schema $schema): void
    {
       $productDetailSQL = 'DROP TABLE IF EXISTS `product_detail`';
       $this->addSql($productDetailSQL);

       $productSQL = 'DROP TABLE IF EXISTS `product`';
       $this->addSql($productSQL);
    }
}
