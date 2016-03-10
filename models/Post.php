<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\validators\ImageValidator;
use yii\helpers\HtmlPurifier;
use app\models\comment\Coment;
use app\models\Rating;

/**
 * This is the model class for table "Post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $post_name
 * @property string $post_text
 * @property string $post_short_text
 * @property string $post_img
 * @property integer $post_status
 * @property string $created_at
 * @property string $updated_at
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    public $hidden_img = null;
    public $count_comment = 0;
    public $rates = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_name', 'post_text', 'post_short_text'], 'required'],
            ['post_name',  'filter', 'filter' => 'trim'],
            [['post_name', 'post_text'], 'string'],
            ['post_name', 'match', 'pattern' => '/^[а-яёА-ЯЁa-zA-Z0-9\s]{4,255}$/iu', 'message' => 'Допустимые символы: Кириллица и Английские буквы,цифры,пробел.Мин. кол-во символов 4, макс кол-во символов 254'],
            [['post_name', 'post_short_text'], 'string', 'max' => 255],
            ['post_img', 'image', 'extensions' => 'png, jpg, jpeg',
                'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                'minWidth' => 400, 'maxWidth' => 810,
                'minHeight' => 180, 'maxHeight' => 410,
                'maxSize' => 150*1024,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_name' => 'Назавание: ',
            'post_short_text' => 'Краткое Содержание',
            'post_text' => 'Содержание',
            'post_img' => 'Выберите Рисунок',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at','updated_at'],
                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
            ],
                'value' => function(){ return date('Y-m-d');},
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Save post
     * @return bool
     */
    public function savePost()
    {
        $this->user_id = Yii::$app->user->id;
        $this->post_status = self::STATUS_ACTIVE;
        $this->post_img = UploadedFile::getInstance($this, 'post_img');
        $this->post_text = HtmlPurifier::process($this->post_text);
        $this->post_short_text = HtmlPurifier::process($this->post_short_text);
        if($this->save())
        {
           if($this->post_img)
            {
                $img = $this->id . '.' . $this->post_img->extension;
                $this->post_img->saveAs('image/upload/post/' . $img);
                $this->post_img = $img;
                $this->update();
            }

            return true;
        }
       return false;
    }

    /**
     * Update post
     * @return bool
     * @throws \Exception
     */
    public function updatePost()
    {
        $this->post_img = UploadedFile::getInstance($this, 'post_img');
        if (!$this->post_img)
        {
            $this->post_img = (Yii::$app->request->post()['Post']['hidden_img'] != null) ? $this->oldAttributes['post_img'] : null;
        }
        else
        {
            $img = $this->id . '.' . $this->post_img->extension;
            $this->post_img->saveAs('image/upload/post/' . $img);
            $this->post_img = $img;
        }
        $this->post_text = HtmlPurifier::process($this->post_text);
        $this->post_short_text = HtmlPurifier::process($this->post_short_text);
        return $this->update() ? true : false;
    }

    /**
     *  get count comment for post
     * @return mixed
     */
    public function getCommentsCount()
    {
    return Coment::find()->where(['entity' => $this->id])->count();
    }

    /**
     * get rating for post
     * @return float
     */
    public function getRatingRates()
    {
        $count = Rating::find()->where(['post_id' => $this->id])->count();
        $sum = Rating::find()->where(['post_id' => $this->id])->sum('rates');

        return $sum ? $sum/$count : 0;
    }
}
