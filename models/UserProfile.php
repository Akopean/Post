<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "user_profile".
 *
 * @property integer $user_id
 * @property string $avatar
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property string $birthday
 * @property integer $gender
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    public $hidden_img = null;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gender'], 'integer'],
            [['first_name', 'second_name', 'middle_name', 'birthday'], 'filter', 'filter' => 'trim'],
            [['first_name', 'second_name', 'middle_name'], 'match', 'pattern' => '/^[а-яёА-ЯЁ]{2,32}$/iu', 'message' => 'Допустимые символы: Кириллица.Мин. кол-во символов 2, Макс. кол-во символов 32'],

            [['avatar'], 'string'],
            [['first_name', 'second_name', 'middle_name', 'birthday'], 'string', 'max' => 32],
            ['avatar', 'image', 'extensions' => 'png, jpg, jpeg',
                'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                'minWidth' => 190, 'maxWidth' => 220,
                'minHeight' => 490, 'maxHeight' => 510,
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
            'user_id' => 'User ID',
            'avatar' => 'Аватар',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'birthday' => 'Дата Рождения',
            'gender' => 'Пол',
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
     * update profile for user
     * @param $id
     * @return bool
     */
    public function updateProfile($id)
    {
        $this->avatar = UploadedFile::getInstance($this, 'avatar');
        if (!$this->avatar)
        {
            $this->avatar = (Yii::$app->request->post()['UserProfile']['hidden_img'] != null) ? $this->oldAttributes['avatar'] : null;
        }
        else
        {
            $img = Yii::$app->user->id . '.' . $this->avatar->extension;
            $this->avatar->saveAs('image/upload/user/avatar/' . $img);
            $this->avatar = $img;
        }
        $this->user_id = ( $id ? $id : Yii::$app->user->id);

        return $this->save() ? true : false;
    }
}
