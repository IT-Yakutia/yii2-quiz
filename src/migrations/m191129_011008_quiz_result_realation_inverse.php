<?php

use yii\db\Migration;

/**
 * Class m191129_011008_quiz_result_realation_inverse
 */
class m191129_011008_quiz_result_realation_inverse extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->dropForeignKey('quiz_option-quiz_result-fkey','quiz_option');
        $this->dropIndex('quiz_option-quiz_result-idx','quiz_option');

        $this->dropColumn('{{quiz_option}}', 'quiz_result_id');

        $this->createTable('{{quiz_result_select}}', [
            'id' => $this->primaryKey(),
            'quiz_option_id' => $this->integer()->notNull(),
            'quiz_result_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('quiz_result_select-quiz_result-fkey','quiz_result_select','quiz_result_id','quiz_result','id','CASCADE','CASCADE');
        $this->createIndex('quiz_result_select-quiz_result-idx','quiz_result_select','quiz_result_id');

        $this->addForeignKey('quiz_result_select-quiz_option-fkey','quiz_result_select','quiz_option_id','quiz_option','id','CASCADE','CASCADE');
        $this->createIndex('quiz_result_select-quiz_option-idx','quiz_result_select','quiz_option_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('quiz_result_select-quiz_result-fkey','quiz_result_select');
        $this->dropIndex('quiz_result_select-quiz_result-idx','quiz_result_select');

        $this->dropForeignKey('quiz_result_select-quiz_option-fkey','quiz_result_select');
        $this->dropIndex('quiz_result_select-quiz_option-idx','quiz_result_select');

        $this->dropTable('{{quiz_result_select}}');

        $this->addColumn('{{quiz_option}}', 'quiz_result_id', $this->integer());

        $this->addForeignKey('quiz_option-quiz_result-fkey','quiz_option','quiz_result_id','quiz_result','id','SET NULL','SET NULL');
        $this->createIndex('quiz_option-quiz_result-idx','quiz_option','quiz_result_id');
        
    }
}
