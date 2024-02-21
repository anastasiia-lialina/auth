<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_sessions".
 *
 * @property int $user_id Идентификатор пользователя
 * @property string $access_token Токен
 */
class UserSessions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_sessions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['access_token'], 'required'],
            [['access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Идентификатор пользователя',
            'access_token' => 'Токен',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserSessionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSessionsQuery(get_called_class());
    }
}
