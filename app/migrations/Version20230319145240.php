<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230319145240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Product category migration';
    }

    public function up(Schema $schema): void
    {
        $categoryTableSQL = '
            CREATE TABLE IF NOT EXISTS `category` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `parent_id` INT(11) NULL,
              `name` VARCHAR(255) NOT NULL,
              `created_at` DATETIME NULL DEFAULT NOW(),
              `updated_at` DATETIME NULL DEFAULT NOW(),
              PRIMARY KEY (`id`),
              INDEX `fk_category_category1_idx` (`parent_id` ASC) VISIBLE,
              CONSTRAINT `fk_category_category1`
                FOREIGN KEY (`parent_id`)
                REFERENCES `category` (`id`)
                ON DELETE CASCADE
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci';
        $this->addSql($categoryTableSQL);

        $productCategoryTableSQL = '
          CREATE TABLE IF NOT EXISTS `product_category` (
              `product_id` INT(11) NOT NULL,
              `category_id` INT(11) NOT NULL,
              INDEX `fk_product_category_product1_idx` (`product_id` ASC) VISIBLE,
              INDEX `fk_product_category_category1_idx` (`category_id` ASC) VISIBLE,
              CONSTRAINT `fk_product_category_product1`
                FOREIGN KEY (`product_id`)
                REFERENCES `product` (`id`)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
              CONSTRAINT `fk_product_category_category1`
                FOREIGN KEY (`category_id`)
                REFERENCES `category` (`id`)
                ON DELETE CASCADE
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci';
        $this->addSql($productCategoryTableSQL);

    }

    public function down(Schema $schema): void
    {
        $productCategorySQL = 'DROP TABLE IF EXISTS `product_category`';
        $this->addSql($productCategorySQL);

        $categorySQL = 'DROP TABLE IF EXISTS `category`';
        $this->addSql($categorySQL);

    }
}
