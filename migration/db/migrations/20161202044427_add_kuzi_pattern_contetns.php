<?php

use Phinx\Migration\AbstractMigration;

class AddKuziPatternContetns extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $rows = [
            [
              'id'    => 1,
              'name'  => '超吉',
              'max'  => 1
            ],
            [
              'id'    => 2,
              'name'  => '大吉',
              'max'  => 9
            ],
            [
              'id'    => 3,
              'name'  => '中吉',
              'max'  => 20
            ],
            [
              'id'    => 4,
              'name'  => '吉',
              'max'  => 20
            ]
        ];
        $this->insert('kuzi_pattern', $rows);
    } 
    public function down()
    {
        $this->execute('DELETE FROM kuzi_pattern');
    }
}
