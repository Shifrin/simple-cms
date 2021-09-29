<?php

namespace common\models;

use One;
use common\app\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%user_profile}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $bio_info
 * @property integer $gender
 * @property string $website
 * @property string $linkedin
 * @property string $facebook
 * @property string $twitter
 * @property string $github
 * @property string $user_image
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property User $user
 * @property Attachment $file
 */
class UserProfile extends ActiveRecord {
    
    const GENDER_MALE = 10;
    const GENDER_FEMALE = 20;
    const SCENARIO_FILE_UPLOAD = 'file_upload';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className()
            ],
            [
                'class' => BlameableBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'unique'],
            [['user_id', 'gender', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['first_name', 'last_name', 'bio_info'], 'filter', 'filter' => 'trim'],
            [['first_name', 'last_name', 'bio_info'], 'string'],
            [['gender'], 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
            [['website', 'linkedin', 'facebook', 'twitter', 'github', 'user_image'],
                'url', 'skipOnEmpty' => true],
            [['first_name', 'last_name', 'bio_info', 'gender', 'website', 'linkedin',
                'facebook', 'twitter', 'github', 'user_image'], 'default', 'value' => new Expression('NULL')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => One::t('app', 'ID'),
            'user_id' => One::t('app', 'User ID'),
            'first_name' => One::t('app', 'First Name'),
            'last_name' => One::t('app', 'Last Name'),
            'bio_info' => One::t('app', 'Bio Info'),
            'gender' => One::t('app', 'Gender'),
            'website' => One::t('app', 'Website'),
            'linkedin' => One::t('app', 'Linkedin'),
            'facebook' => One::t('app', 'Facebook'),
            'twitter' => One::t('app', 'Twitter'),
            'github' => One::t('app', 'Github'),
            'user_image' => One::t('app', 'Profile Picture'),
            'created_at' => One::t('app', 'Created At'),
            'created_by' => One::t('app', 'Created By'),
            'updated_at' => One::t('app', 'Updated At'),
            'updated_by' => One::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Get user full name
     * @return string
     */
    public function getName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get user gender
     * @return mixed
     */
    public function getGender()
    {
        $list = self::genderList();
        return $list[$this->gender];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFile()
    {
        return $this->hasOne(Attachment::className(), ['model_id' => 'id'])
            ->where(['model' => substr(strrchr(get_class($this), '\\'), 1)]);
    }

    /**
     * Get profile picture
     * @param bool $srcOnly Defaults to false,
     * @return null|string
     */
//    public function getPicture($srcOnly = false)
//    {
//        if ($this->file) {
//            $fileSrc = $this->file->getFileUrl();
//        } else {
//            $fileName = $this->gender == self::GENDER_FEMALE ? 'user-female.png' : 'user-male.png';
//            $fileSrc = One::app()->urlManager->createUrl(["/images/{$fileName}"]);
//        }
//
//        if ($srcOnly) {
//            return $fileSrc;
//        }
//
//        return Html::img($fileSrc, [
//            'class' => 'img-responsive img-circle', 'alt' => One::t('app', 'Profile Picture')
//        ]);
//    }

    /**
     * Get profile picture.
     *
     * @param array $options Html Options
     * @return null|string
     */
    public function getPicture($options = [])
    {
        $image = $this->user_image;

        if ($image !== null || !empty($image)) {
            $explode = StringHelper::explode($this->user_image, '/');
            $file = One::getAlias('@webroot') . '/uploads/source/users/' . end($explode);

            if (file_exists($file)) {
                $imageInfo = getimagesize(Html::encode($file));
                $options = array_merge($options, [
                    'width' => $imageInfo[0],
                    'height' => $imageInfo[1]
                ]);

                return Html::img($image, array_merge(['alt' => $this->getName()], $options));
            }
        }

        $fileName = $this->gender == self::GENDER_FEMALE ? 'user-female.png' : 'user-male.png';

        return Html::img(Url::to(["images/{$fileName}"]), array_merge([
            'class' => 'img-responsive img-circle',
            'alt' => $this->getName()
        ], $options));
    }

    /**
     * Delete user image.
     *
     * @return bool
     */
    public function deleteUserImage()
    {
        if ($this->user_image !== null || !empty($this->user_image)) {
            $explode = StringHelper::explode($this->user_image, '/');
            $fileDirectory = One::getAlias('@frontendWebroot') . '/uploads/source/users/' . end($explode);

            if (file_exists($fileDirectory)) {
                unlink($fileDirectory);
                $this->updateAttributes(['user_image' => null]);
                return true;
            }
        }

        return false;
    }

    /**
     * Helper function
     * This will help to generate dropdown for gender list
     * @return array
     */
    public static function genderList()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female'
        ];
    }
}
