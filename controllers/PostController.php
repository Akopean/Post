<?php

namespace app\controllers;

use app\models\comment\Coment;
use Yii;
use app\models\Post;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\web\ForbiddenHttpException;
use app\models\Rating;

/**
 * Class PostController
 * @package app\controllers
 */
class PostController extends Controller
{

    /**
     * Lists all Post models.
     * @return string
     */
    public function actionIndex()
    {
        $query = Post::find()->where(['post_status' => Post::STATUS_ACTIVE])->orderBy('created_at DESC');
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count()]);
            $pages->pageSize = 5;

            $model = $query->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

                foreach ($model as $post) {
                    $post->count_comment = $post->CommentsCount;
                }
        return $this->render('index', [
            'model' => $model,
            'pages' => $pages,
        ]);

    }

    /**
     * Displays a single Post model.
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $query = Post::find()->where(['post_status' => Post::STATUS_ACTIVE, 'id' => $id])->one();
        if($query)
        {
            $query->rates = $query->RatingRates;
            return $this->render('view', [
                'model' => $query,
            ]);
        }
        else
        {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $model = new Post();
        if (\Yii::$app->user->can('createPost'))
        {
            if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->savePost()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
            {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            throw new ForbiddenHttpException('Доступ Закрыт');
        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Post::find()
            ->where(['post_status' => Post::STATUS_ACTIVE, 'id' => $id])
            ->orderBy('id')
            ->one();
        if (\Yii::$app->user->can('updatePost') || \Yii::$app->user->can('updateOwnPost',['user_id'=>$model->user_id])) {

            if ($model->load(Yii::$app->request->post()) && $model->updatePost())
            {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else
            {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        else
        {
            throw new ForbiddenHttpException('Доступ Закрыт');
        }
    }

    /**
     *Deletes an existing Post model and Rating(post_id = id)
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->user->can('deletePost'))
        {
            if($this->findModel($id))
            {
                Rating::deleteAll(['post_id' => $id]);
                Coment::deleteAll(['entity' => $id]);
                $this->findModel($id)->delete();
                return $this->redirect(['index']);
            }
            else
            {
                throw new NotFoundHttpException('Запрашиваемая страница не существует.');
            }
        }
        else
        {
            throw new ForbiddenHttpException('Доступ Закрыт');
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null)
        {
            return $model;
        }
        else
        {
            throw new NotFoundHttpException('Запрашиваемая страница не существует.');
        }
    }

    /**
     * Update User rating
     * @return mixed|null
     * @throws NotFoundHttpException
     */
    public function actionUpdateRating()
    {
        $model = new Rating();
        if (Yii::$app->request->post() &&
            Yii::$app->request->isAjax &&
            Yii::$app->request->post('rating') != null &&
            Yii::$app->request->post('post_id') != null &&
            POST::findOne(Yii::$app->request->post('post_id')))
        {
            if ($model->updateRating(Yii::$app->request->post('rating'),Yii::$app->request->post('post_id')))
            {
                return Post::findOne(Yii::$app->request->post('post_id'))->RatingRates;
            }
            else
            {
                return null;
            }
        }
       throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }
}
