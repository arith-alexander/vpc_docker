<?php

use Phinx\Migration\AbstractMigration;

class KuziInitialize extends AbstractMigration
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
    public function change()
    {
        $table1 = $this->table('kuzi_pattern');
        $table1->addColumn('name', 'string')
            ->addColumn('max', 'integer')
            ->addColumn('rest', 'integer')
            ->create();
        $table2 = $this->table('kuzi_history');
        $table2->addColumn('kuzi_pattern_id', 'integer')
            ->addColumn('create_date', 'datetime')
            ->create();
    }
}
