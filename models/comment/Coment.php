<?php

namespace app\models\comment;

use rmrevin\yii\module\Comments\models\Comment;
use rmrevin\yii\module\Comments;


class Coment extends Comment
{
    /**
     * @return bool
     */
    public function canUpdate()
    {
        $User = \Yii::$app->getUser();

        return Comments\Module::instance()->useRbac === true ? \Yii::$app->getUser()->can(Comments\Permission::UPDATE) || \Yii::$app->getUser()->can(Comments\Permission::UPDATE_OWN, ['Comment' => $this])
            : ($User->isGuest ? false : $this->created_by === $User->id);
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        $User = \Yii::$app->getUser();

        return Comments\Module::instance()->useRbac === true
            ? \Yii::$app->getUser()->can(Comments\Permission::DELETE) || \Yii::$app->getUser()->can(Comments\Permission::DELETE_OWN, ['Comment' => $this])
            : ($User->isGuest ? false : $this->created_by === $User->id);
    }
}