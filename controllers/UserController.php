<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\LoginForm;
use app\models\UserProfile;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'profile' => UserProfile::findOne($id)
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'user profile' page.
     * @return mixed
     */
    public function actionSignUp()
    {
        if(Yii::$app->user->isGuest) {
            $model = new User();
            if ($model->load(Yii::$app->request->post()) && $model->signUp()) {
                return $this->redirect(['profile', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            throw new ForbiddenHttpException('Вы уже зарегестрированы!!!');
        }
    }

    /**
     * Добавляем или изменяем Профиль Пользователя
     * @return string|\yii\web\Response
     */
    public function actionProfile($id = null)
    {
      $id = (is_int((int)$id) && (int)$id > 0 && User::findOne(['id' => $id])) ? $id : Yii::$app->user->id;

        if(!Yii::$app->user->isGuest && (\Yii::$app->user->can('updateUserProfile') || \Yii::$app->user->can('updateOwnUserProfile',['user_id'=> $id]))) {
            $model = ($model = UserProfile::findOne($id)) ? $model : new UserProfile();
            if ($model->load(Yii::$app->request->post()) && $model->validate())
            {
                if ($model->updateProfile((int)$id))
                {
                    Yii::$app->session->setFlash('success', 'Профиль изменен');
                }
                else
                {
                    Yii::$app->session->setFlash('error', 'Профиль не изменен');
                    Yii::error('Ошибка записи. Профиль не изменен');
                    return $this->refresh();
                }
            }

            return $this->render(
                'profile',
                [
                    'model' => $model
                ]
            );
        }
        else
        {
            return $this->redirect(['login']);
        }
    }


    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
