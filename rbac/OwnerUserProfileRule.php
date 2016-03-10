<?php

namespace app\rbac;


use yii\rbac\Rule;

/**
 * User group rule class.
 */
class OwnerUserProfileRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'userprofile';

    /**
     * @inheritdoc
     */
    public function execute($userId, $item, $params)
    {
        return isset($params) ? $params['user_id']== $userId : false;
    }
}