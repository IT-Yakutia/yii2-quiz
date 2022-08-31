<?php

use yii\db\Migration;

/**
 * Class m220829_111153_add_users_results_for_poll
 */
class m220829_111153_add_users_results_for_poll extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{quiz_user_answer}}', [
            'id' => $this->primaryKey(),
            'answers' => $this->text()->notNull(),
            'browser_agent' => $this->string()->notNull(),
            'ip' => $this->string()->notNull(),

            'quiz_id' => $this->integer()->notNull(),
            'user_id' => $this->integer(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('quiz_user_answer-quiz-fkey','quiz_user_answer','quiz_id','quiz','id','CASCADE','CASCADE');
        $this->createIndex('quiz_user_answer-quiz-idx','quiz_user_answer','quiz_id');

        $this->addForeignKey('quiz_user_answer-user-fkey','quiz_user_answer','user_id','user','id','SET NULL','SET NULL');
        $this->createIndex('quiz_user_answer-user-idx','quiz_user_answer','user_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('quiz_user_answer-quiz-fkey','quiz_user_answer');
        $this->dropIndex('quiz_user_answer-quiz-idx','quiz_user_answer');

        $this->dropForeignKey('quiz_user_answer-user-fkey','quiz_user_answer');
        $this->dropIndex('quiz_user_answer-user-idx','quiz_user_answer');

        $this->dropTable('{{quiz_user_answer}}');
    }
}
