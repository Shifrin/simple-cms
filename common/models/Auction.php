<?php

namespace common\models;

use common\app\ActiveRecord;
use One;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%auction}}".
 *
 * @property integer $id
 * @property integer $collection_id
 * @property string $start_price
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Post $collection
 * @property AuctionBid[] $bids
 */
class Auction extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_price', 'start_time', 'end_time'], 'required'],
            [['collection_id'], 'integer'],
            [['collection_id'], 'unique'],
            [
                ['start_price'],
                'number',
                'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'
            ],
            [['start_time'], 'date', 'type' => 'datetime',
                'format' => 'MM/dd/yyyy HH:mm a', 'timestampAttribute' => 'start_time'],
            [['end_time'], 'date', 'type' => 'datetime',
                'format' => 'MM/dd/yyyy HH:mm a', 'timestampAttribute' => 'end_time'],
//            [['end_time'], 'compare', 'compareAttribute' => 'start_time', 'operator' => '>']
//            [
//                ['collection_id'],
//                'exist',
//                'skipOnError' => true,
//                'targetClass' => Post::className(),
//                'targetAttribute' => ['collection_id' => 'id']
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => One::t('app', 'ID'),
            'collection_id' => One::t('app', 'Collection ID'),
            'start_price' => One::t('app', 'Start Price'),
            'start_time' => One::t('app', 'Start Time'),
            'end_time' => One::t('app', 'End Time'),
            'created_by' => One::t('app', 'Created By'),
            'created_at' => One::t('app', 'Created At'),
            'updated_at' => One::t('app', 'Updated At'),
            'updated_by' => One::t('app', 'Updated By'),
        ];
    }

    /*
     * @inheritdoc
     */
    public function behaviors()
    {
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
     * @return \yii\db\ActiveQuery
     */
    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['id' => 'collection_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBids()
    {
        return $this->hasMany(AuctionBid::className(), ['auction_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return AuctionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuctionQuery(get_called_class());
    }

    /**
     * Get formatted date and time.
     *
     * @return mixed
     */
    public function getStartTime()
    {
        return One::app()->formatter->asDatetime($this->start_time);
    }

    /**
     * Get formatted date and time.
     *
     * @return mixed
     */
    public function getEndTime()
    {
        return One::app()->formatter->asDatetime($this->end_time);
    }

}
