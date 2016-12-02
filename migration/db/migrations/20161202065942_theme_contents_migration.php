<?php

use Phinx\Migration\AbstractMigration;

class ThemeContentsMigration extends AbstractMigration
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
                'name'  => "願望"
            ],
            [
                'id'    => 2,
                'name'  => "交際"
            ],
            [
                'id'    => 3,
                'name'  => "病気"
            ]
        ];
        $this->insert('theme', $rows);

        $rows = [
            [
                'id' => 1,
                'content' => '一花咲かせる、時代来たり。',
                'theme_id' => '1', //願望
                'pattern_id' => '1' //超吉
            ],
            [
                'id' => 2,
                'content' => '万事うまく行く。思いわずらうな。',
                'theme_id' => '1', //願望
                'pattern_id' => '2' //大吉
            ],
            [
                'id' => 3,
                'content' => 'わがままをせねば、諸事叶う。',
                'theme_id' => '1', //願望
                'pattern_id' => '3' //中吉
            ],
            [
                'id' => 4,
                'content' => '他人の助けにて叶う。',
                'theme_id' => '1', //願望
                'pattern_id' => '4' //吉
            ],
            [
                'id' => 5,
                'content' => '行く手を阻むものはなし。直進せよ。',
                'theme_id' => '2', //交際
                'pattern_id' => '1' //超吉
            ],
            [
                'id' => 6,
                'content' => '心配無用。すべてよいほうに進む。',
                'theme_id' => '2', //交際
                'pattern_id' => '2' //大吉
            ],
            [
                'id' => 7,
                'content' => 'ああ言えばこう言う。',
                'theme_id' => '2', //交際
                'pattern_id' => '3' //中吉
            ],
            [
                'id' => 8,
                'content' => '渡る世間に鬼はなし。敵意は厳禁。',
                'theme_id' => '2', //交際
                'pattern_id' => '4' //吉
            ],
            [
                'id' => 9,
                'content' => '勢いを増す時。向かうところ敵なし。',
                'theme_id' => '3', //病気
                'pattern_id' => '1' //超吉
            ],
            [
                'id' => 10,
                'content' => '大人しくしていれば、すぐによくなる。',
                'theme_id' => '3', //病気
                'pattern_id' => '2' //大吉
            ],
            [
                'id' => 11,
                'content' => 'さして問題なし。気のせいなり。',
                'theme_id' => '3', //病気
                'pattern_id' => '3' //中吉
            ],
            [
                'id' => 12,
                'content' => '食に問題あり。見直せ。',
                'theme_id' => '3', //病気
                'pattern_id' => '4' //吉
            ]
        ];
        $this->insert('fortune', $rows);
    }

    public function down()
    {
        $this->execute('DELETE FROM theme');
        $this->execute('DELETE FROM fortune');
    }
}
