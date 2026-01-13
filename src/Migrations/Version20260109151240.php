<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260109151240 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_address ADD billing_recipient_type VARCHAR(255) DEFAULT NULL, ADD tax_code VARCHAR(255) DEFAULT NULL, ADD vat_number VARCHAR(255) DEFAULT NULL, ADD sdi_code VARCHAR(255) DEFAULT NULL, ADD pec_address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_address DROP billing_recipient_type, DROP tax_code, DROP vat_number, DROP sdi_code, DROP pec_address');
    }
}
