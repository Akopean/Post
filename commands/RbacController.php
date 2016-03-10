<?php
namespace app\commands;

use app\rbac\AuthorPostRule;
use app\rbac\OwnerUserProfileRule;
use Yii;
use yii\console\Controller;
use \rmrevin\yii\module\Comments\Permission;
use \rmrevin\yii\module\Comments\rbac\ItsMyComment;

/**
 * RBAC console controller.
 */
class RbacController extends Controller
{
    /**
     * Initial RBAC action
     * @param integer $id admin ID
     */
    public function actionInit($id = null)
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll(); //удаляем старые данные

        //
        $rule = new AuthorPostRule();
        $auth->add($rule);
        $ItsMyCommentRule = new ItsMyComment();
        $auth->add($ItsMyCommentRule);
        $user_profile = new OwnerUserProfileRule();
        $auth->add($user_profile);

        // добавляем разрешение "comments.create"
        $own_user_profile_update = $auth->createPermission('updateOwnUserProfile');
        $own_user_profile_update->description = 'Can update own user profile';
        $own_user_profile_update->ruleName = $user_profile->name;
        $auth->add($own_user_profile_update);

        // добавляем разрешение "comments.create"
        $user_profile_update = $auth->createPermission('updateUserProfile');
        $user_profile_update->description = 'Can update user profile';
        $auth->add($user_profile_update);

        // добавляем разрешение "comments.create"
        $comments_create = $auth->createPermission(Permission::CREATE);
        $comments_create->description = 'Can create own comments';
        $auth->add($comments_create);

        // добавляем разрешение "comments.update"
        $comments_update = $auth->createPermission(Permission::UPDATE);
        $comments_update->description = 'Can update own comments';
        $auth->add($comments_update);

        // добавляем разрешение "comments.update.own"
        $comments_update_own = $auth->createPermission(Permission::UPDATE_OWN);
        $comments_update_own->description = 'Can update own comments';
        $comments_update_own->ruleName = $ItsMyCommentRule->name;
        $auth->add($comments_update_own);

        $auth->addChild($comments_update_own, $comments_update);

        // добавляем разрешение "comments.delete"
        $comments_delete = $auth->createPermission(Permission::DELETE);
        $comments_delete->description = 'Can delete all comments';
        $auth->add($comments_delete);

        // добавляем разрешение "comments.delete"
        $comments_delete_own = $auth->createPermission(Permission::DELETE_OWN);
        $comments_delete_own->description = 'Can delete all comments';
        $comments_delete_own->ruleName = $ItsMyCommentRule->name;
        $auth->add($comments_delete_own);

        $auth->addChild($comments_delete_own, $comments_delete);



        // добавляем разрешение "createPost"
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a post';
        $auth->add($createPost);

        // добавляем разрешение "updatePost"
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update post';
        $auth->add($updatePost);

        // добавляем разрешение "updateOwnPost"
        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $updateOwnPost->ruleName = $rule->name;
        $auth->add($updateOwnPost);

        // добавляем разрешение "deletePost"
        $deletePost = $auth->createPermission('deletePost');
        $deletePost->description = 'Delete post';
        $auth->add($deletePost);

        $auth->addChild($updateOwnPost, $updatePost);

        // добавляем роль "user" и даём роли разрешение
        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createPost);
        $auth->addChild($user, $updateOwnPost);
        $auth->addChild($user, $own_user_profile_update);
        $auth->addChild($user, $comments_create);
        $auth->addChild($user, $comments_update_own);
        $auth->addChild($user, $comments_delete_own);

        // добавляем роль "admin" и даём роли разрешение
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);
        $auth->addChild($admin, $deletePost);
        $auth->addChild($admin, $user_profile_update);
        $auth->addChild($admin, $comments_create);
        $auth->addChild($admin, $comments_update);
        $auth->addChild($admin, $comments_delete);
        $auth->addChild($admin, $user);

        // Установить  Администратора по значению id [Пользователя]
        if ($id !== null) {
            $auth->assign($admin, $id);
        }
    }
}
