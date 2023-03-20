<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320172938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'ADD primary keys to file & product';
    }

    public function up(Schema $schema): void
    {
        $productCategorySQL = 'ALTER TABLE `product_category`
                        ADD PRIMARY KEY (`product_id`,`category_id`)';
        $this->addSql($productCategorySQL);

        $productCategorySQL = 'ALTER TABLE `product_gallery`
                        ADD PRIMARY KEY (`product_id`,`file_id`)';
        $this->addSql($productCategorySQL);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
