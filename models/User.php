<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id идентификатор пользователя
 * @property string $first_name Имя
 * @property string $last_name Фамилия
 * @property string $country Страна
 * @property string $city Город
 * @property UserSessions $userSessions
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'country', 'city'], 'required'],
            [['first_name', 'last_name', 'country', 'city'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'идентификатор пользователя',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'country' => 'Страна',
            'city' => 'Город',
        ];
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    //Почему таблица называется UserSessions, если там хранится токен только? Можно было занести токен в таблицу User
    public function getUserSessions(): ActiveQuery
    {
        return $this->hasOne(UserSessions::class, ['user_id' => 'id']);
    }


    /**
     * Проверка подписи
     * Сортировка входящих параметров по ключу (используйте ksort)
     * Сформируйте строку $str из полученных параметров исключив из нее sig
     * К концу полученной строки добавьте секретный ключ
     * С полученной строкой выполните mb_strtolower( md5($str), 'UTF-8' ) и сравните результат с пришедшем sig
     * Если mb_strtolower( md5($str), 'UTF-8' ) === sig, зарегистрируйте пользователя, в противном случаи выдайте ошибку
     * @param array $data
     * @return bool
     */
    public static function verifySig(array $data): bool
    {
        $appSig = Yii::$app->params['secret_key'];
        $sig = $data['sig'] ?? '';
        unset($data['sig']);

        $str = ksort($data);
        $verifyString = mb_strtolower(md5($str . $appSig), 'UTF-8');

        return $verifyString === $sig;
    }
}
