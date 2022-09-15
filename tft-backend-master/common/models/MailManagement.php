<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mail_management".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $body
 * @property int $reply_of
 * @property string $email_type
 * @property int $created_at
 */
class MailManagement extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mail_management';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'email', 'subject', 'body', 'reply_of', 'email_type', 'created_at'], 'required'],
            [['user_id', 'reply_of', 'created_at'], 'integer'],
            [['body', 'email_type'], 'string'],
            [['name', 'subject'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
            'email' => 'Email',
            'subject' => 'Subject',
            'body' => 'Body',
            'reply_of' => 'Reply Of',
            'email_type' => 'Email Type',
            'created_at' => 'Created At',
        ];
    }
}
