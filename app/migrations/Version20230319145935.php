<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230319145935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Product gallery migration';
    }

    public function up(Schema $schema): void
    {
        $fileTableSQL = '
            CREATE TABLE IF NOT EXISTS `file` (
              `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
              `name` VARCHAR(255) NOT NULL,
              `type` VARCHAR(255) NOT NULL,
              `path` VARCHAR(255) NOT NULL,
              `meta_data` JSON NULL,
              `created_at` DATETIME NULL DEFAULT NOW(),
              PRIMARY KEY (`id`),
              INDEX `type_index` (`type` ASC) VISIBLE)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci';
        $this->addSql($fileTableSQL);

        $productGalleryTableSQL = '
            CREATE TABLE IF NOT EXISTS `product_gallery` (
              `product_id` INT(11) NOT NULL,
              `file_id` BIGINT(20) NOT NULL,
              INDEX `fk_product_gallery_product1_idx` (`product_id` ASC) VISIBLE,
              CONSTRAINT `fk_product_gallery_product1`
                FOREIGN KEY (`product_id`)
                REFERENCES `product` (`id`)
                ON DELETE CASCADE
                ON UPDATE NO ACTION,
              CONSTRAINT `fk_product_gallery_file1`
                FOREIGN KEY (`file_id`)
                REFERENCES `file` (`id`)
                ON DELETE CASCADE
                ON UPDATE NO ACTION)
            ENGINE = InnoDB
            DEFAULT CHARACTER SET = utf8mb4
            COLLATE = utf8mb4_unicode_ci';
        $this->addSql($productGalleryTableSQL);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
