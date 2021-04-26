<?php

use yii\db\Migration;

/**
 * Class m210424_164402_create_table_categories
 */
class m210424_164402_create_table_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()
        ]);
        $this->insert('category', [
            'name' => 'Cookie'
        ]);
        $this->insert('category', [
            'name' => 'Tea'
        ]);
        $this->insert('category', [
            'name' => 'Coffee'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210424_164402_create_table_categories cannot be reverted.\n";

        return false;
    }
    */
}
