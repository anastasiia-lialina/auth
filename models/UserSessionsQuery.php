<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[UserSessions]].
 *
 * @see UserSessions
 */
class UserSessionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserSessions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserSessions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
