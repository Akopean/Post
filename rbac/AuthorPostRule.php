<?php

namespace app\rbac;


use yii\rbac\Rule;

/**
 * User group rule class.
 */
class AuthorPostRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'author';

    /**
     * @inheritdoc
     */
    public function execute($userId, $item, $params)
    {
        return isset($params) ? $params['user_id']== $userId : false;
    }
}