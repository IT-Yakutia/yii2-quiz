<?php

use yii\db\Migration;

/**
 * Class m191122_022530_quiz_init
 */
class m191122_022530_quiz_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{quiz}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'photo' => $this->string(),
            'sort' => $this->integer(),
            'type' => $this->tinyInteger(1)->unsigned()->defaultValue(0)->comment('0 - стандарт | 1 - рейтинг'),

            'slug' => $this->string()->notNull(),
            'user_id' => $this->integer(),
            'meta_description' => $this->string(),
            'meta_keywords' => $this->string(),

            'is_publish' => $this->boolean(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('quiz-user-fkey','quiz','user_id','user','id','SET NULL','SET NULL');
        $this->createIndex('quiz-user-idx','quiz','user_id');

        $this->createTable('{{quiz_question}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type' => $this->integer(),
            'sort' => $this->integer(),

            'quiz_id' => $this->integer()->notNull(),

            'is_publish' => $this->boolean(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('quiz_question-quiz-fkey','quiz_question','quiz_id','quiz','id','CASCADE','CASCADE');
        $this->createIndex('quiz_question-quiz-idx','quiz_question','quiz_id');

        $this->createTable('{{quiz_result}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'photo' => $this->string(),
            'description' => $this->text(),

            'quiz_id' => $this->integer()->notNull(),
            'min_limit' => $this->tinyInteger()->defaultValue(0)->unsigned()->comment('рейтинговый квиз'),
            'max_limit' => $this->tinyInteger()->defaultValue(0)->unsigned()->comment('рейтинговый квиз'),

            'is_publish' => $this->boolean(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('quiz_result-quiz-fkey','quiz_result','quiz_id','quiz','id','CASCADE','CASCADE');
        $this->createIndex('quiz_result-quiz-idx','quiz_result','quiz_id');

        $this->createTable('{{quiz_option}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'type' => $this->integer(),
            'sort' => $this->integer(),
            'src' => $this->string(),
            'correct_answer' => $this->boolean()->defaultValue(false)->comment('рейтинговый квиз'),

            'quiz_question_id' => $this->integer()->notNull(),
            'quiz_result_id' => $this->integer()->notNull(),

            'is_publish' => $this->boolean(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('quiz_option-quiz_question-fkey','quiz_option','quiz_question_id','quiz_question','id','CASCADE','CASCADE');
        $this->createIndex('quiz_option-quiz_question-idx','quiz_option','quiz_question_id');

        $this->addForeignKey('quiz_option-quiz_result-fkey','quiz_option','quiz_result_id','quiz_result','id','CASCADE','CASCADE');
        $this->createIndex('quiz_option-quiz_result-idx','quiz_option','quiz_result_id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('quiz_option-quiz_result-fkey','quiz_option');
        $this->dropIndex('quiz_option-quiz_result-idx','quiz_option');

        $this->dropForeignKey('quiz_option-quiz_question-fkey','quiz_option');
        $this->dropIndex('quiz_option-quiz_question-idx','quiz_option');

        $this->dropTable('{{quiz_option}}');

        $this->dropForeignKey('quiz_result-quiz-fkey','quiz_result');
        $this->dropIndex('quiz_result-quiz-idx','quiz_result');

        $this->dropTable('{{quiz_result}}');

        $this->dropForeignKey('quiz_question-quiz-fkey','quiz_question');
        $this->dropIndex('quiz_question-quiz-idx','quiz_question');

        $this->dropTable('{{quiz_question}}');

        $this->dropForeignKey('quiz-user-fkey','quiz');
        $this->dropIndex('quiz-user-idx','quiz');

        $this->dropTable('{{quiz}}');
    }
}
