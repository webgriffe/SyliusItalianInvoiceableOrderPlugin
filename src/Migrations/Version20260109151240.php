<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @psalm-api
 */
final class Version20260109151240 extends AbstractMigration
{
    #[\Override]
    public function up(Schema $schema): void
    {
        $table = $schema->getTable('sylius_address');

        if (!$table->hasColumn('billing_recipient_type')) {
            $this->addSql('ALTER TABLE sylius_address ADD billing_recipient_type VARCHAR(255) DEFAULT NULL');
        }

        if (!$table->hasColumn('tax_code')) {
            $this->addSql('ALTER TABLE sylius_address ADD tax_code VARCHAR(255) DEFAULT NULL');
        }

        if (!$table->hasColumn('vat_number')) {
            $this->addSql('ALTER TABLE sylius_address ADD vat_number VARCHAR(255) DEFAULT NULL');
        }

        if (!$table->hasColumn('sdi_code')) {
            $this->addSql('ALTER TABLE sylius_address ADD sdi_code VARCHAR(255) DEFAULT NULL');
        }

        if (!$table->hasColumn('pec_address')) {
            $this->addSql('ALTER TABLE sylius_address ADD pec_address VARCHAR(255) DEFAULT NULL');
        }
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        $table = $schema->getTable('sylius_address');

        if ($table->hasColumn('pec_address')) {
            $this->addSql('ALTER TABLE sylius_address DROP pec_address');
        }

        if ($table->hasColumn('sdi_code')) {
            $this->addSql('ALTER TABLE sylius_address DROP sdi_code');
        }

        if ($table->hasColumn('vat_number')) {
            $this->addSql('ALTER TABLE sylius_address DROP vat_number');
        }

        if ($table->hasColumn('tax_code')) {
            $this->addSql('ALTER TABLE sylius_address DROP tax_code');
        }

        if ($table->hasColumn('billing_recipient_type')) {
            $this->addSql('ALTER TABLE sylius_address DROP billing_recipient_type');
        }
    }
}
