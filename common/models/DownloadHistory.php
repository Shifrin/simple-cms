<?php

namespace common\models;

use One;
use common\app\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%download_history}}".
 *
 * @property integer $id
 * @property integer $image_id
 * @property integer $user_id
 * @property integer $downloaded_at
 *
 * @property Image $image
 * @property User $user
 */
class DownloadHistory extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%download_history}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image_id', 'user_id'], 'required'],
            [['image_id', 'user_id', 'downloaded_at'], 'integer'],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(),
                'targetAttribute' => ['image_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(),
                'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => One::t('app', 'ID'),
            'image_id' => One::t('app', 'Image ID'),
            'user_id' => One::t('app', 'User ID'),
            'downloaded_at' => One::t('app', 'Downloaded At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Find by image id
     *
     * @param $image_id
     * @return ActiveQuery.
     */
    public static function findByImage($image_id)
    {
        return self::find()->where(['image_id' => $image_id]);
    }
}
