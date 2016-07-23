<?php
/*
 * Copyright 2016 CampaignChain, Inc. <info@campaignchain.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160621000022 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE campaignchain_operation_social_media_schedule (id INT AUTO_INCREMENT NOT NULL, operation_id INT DEFAULT NULL, message LONGTEXT NOT NULL, createdDate DATETIME NOT NULL, modifiedDate DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7589D1F944AC3583 (operation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaignchain_operation_social_media_schedule_location (schedule_id INT NOT NULL, location_id INT NOT NULL, INDEX IDX_C6DDFCBBA40BC2D5 (schedule_id), INDEX IDX_C6DDFCBB64D218E (location_id), PRIMARY KEY(schedule_id, location_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE campaignchain_operation_social_media_schedule ADD CONSTRAINT FK_7589D1F944AC3583 FOREIGN KEY (operation_id) REFERENCES campaignchain_operation (id)');
        $this->addSql('ALTER TABLE campaignchain_operation_social_media_schedule_location ADD CONSTRAINT FK_C6DDFCBBA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES campaignchain_operation_social_media_schedule (id)');
        $this->addSql('ALTER TABLE campaignchain_operation_social_media_schedule_location ADD CONSTRAINT FK_C6DDFCBB64D218E FOREIGN KEY (location_id) REFERENCES campaignchain_location (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE campaignchain_operation_social_media_schedule_location DROP FOREIGN KEY FK_C6DDFCBBA40BC2D5');
        $this->addSql('DROP TABLE campaignchain_operation_social_media_schedule');
        $this->addSql('DROP TABLE campaignchain_operation_social_media_schedule_location');
    }
}
