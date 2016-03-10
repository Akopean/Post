<?php

namespace app\models;

use Yii;
use app\models\Post;

/**
 * This is the model class for table "rating".
 *
 * @property integer $id
 * @property integer $post_id
 * @property integer $rates
 * @property string $ip
 *
 */
class Rating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rates','post_id', 'ip'], 'required'],
            ['post_id', 'integer'],
            ['rates', 'double', 'min'=> 0, 'max' => 5],
            ['rates','match', 'pattern' => '/^([0-5]\.?[0-9]?)$/'],
            ['ip', 'ip'],
            ['rates', 'default', 'value' => 0, 'on' => 'default'],
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @param $rates rating rates
     * @param $id  post $id
     * @return bool
     */

    public function updateRating($rates,$id)
    {
        $this->rates = $rates;
        $this->ip = Yii::$app->request->userIP;
        $this->post_id = $id;
        return $this->save() ? true : false;
    }
}
