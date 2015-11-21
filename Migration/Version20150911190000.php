<?php
/*
* This file is part of EC-CUBE
*
* Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
* http://www.lockon.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150911190000 extends AbstractMigration
{

    public function up(Schema $schema)
    {
        $this->createInstagramApiTable($schema);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('plg_instagram_api');
        $schema->dropSequence('plg_instagram_api_instagram_api_id_seq');

        $this->connection->delete('dtb_block', array('file_name' => 'instagram_api'));

    }

    public function postUp(Schema $schema)
{

    $app = new \Eccube\Application();
    $app->boot();

    $statement = $this->connection->prepare('SELECT block_id FROM dtb_block ORDER BY block_id desc');
    $statement->execute();
    $blockId = $statement->fetchColumn();

    $blockIdNumber = $blockId + 1;
    $deviceTypeId = '10';
    $blockName = 'Instagram';
    $fileName = 'instagram_api';
    $datetime = date('Y-m-d H:i:s');
    $logicFlg = '1';
    $deletableFlg = '1';
    $insert = "INSERT INTO dtb_block(
                        block_id, device_type_id, block_name, file_name, create_date, update_date, logic_flg, deletable_flg)
                VALUES ('$blockIdNumber', '$deviceTypeId', '$blockName', '$fileName', '$datetime', '$datetime', '$logicFlg', '$deletableFlg'
                        );";
    $this->connection->executeUpdate($insert);

}


    protected function createInstagramApiTable(Schema $schema)
    {
        $table = $schema->createTable("plg_instagram_api");
        $table->addColumn('instagram_api_id', 'integer', array(
          'autoincrement' => true,
        ));
        $table->addColumn('api_token', 'text', array(
          'notnull' => false,
        ));
        $table->addColumn('api_user', 'text', array(
          'notnull' => false,
        ));

        $table->setPrimaryKey(array('instagram_api_id'));
    }
}
