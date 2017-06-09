<?php

use yii\db\Migration;

/**
 * Handles the creation of table `frontend_user`.
 */
class m170227_033904_create_frontend_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('frontend_user', [
            "id" => $this->integer()->unsigned()->increments(),
            "uuid" => $this->string(45)->primaryKey()->common("用户 id ，主键"),
            'password' => $this->string(45)->notNull()->common("用户密码"),
            'email' => $this->string(45)->notNull()->common("用户邮箱"),
            'status' => $this->smallInteger()->common('用户状态'),
            'phone' => $this->bigint()->unique()->common('用户 电话， 也是用户名'),
            'create_time' => $this->integer()->common('创建用户的时间'),
            'update_time' => $this->integer()->common("更新用户的时间"),
            'access_token' => $this->string(45)->common("更改或忘记密码是  需要验证的字段"),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('frontend_user');
    }
}
