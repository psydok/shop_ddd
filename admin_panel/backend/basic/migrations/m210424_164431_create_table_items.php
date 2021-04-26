<?php

use yii\db\Migration;

/**
 * Class m210424_164431_create_table_items
 */
class m210424_164431_create_table_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('item', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'name' => $this->string()->notNull()->unique(),
            'price' => $this->double()->notNull(),
            'img_link' => $this->string()
        ]);
        $this->addForeignKey(
            'fk-item-category_id',
            'item',
            'category_id',
            'category',
            'id',
            'CASCADE');
        $this->insert('item', [
            'category_id' => 3,
            'name' => 'Green',
            'price' => 70.0
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-item-category_id',
            'item'
        );
        $this->dropTable('item');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210424_164431_create_table_items cannot be reverted.\n";

        return false;
    }
    */
}
