<?php

namespace common\models;

use common\app\ActiveRecord;
use One;

/**
 * This is the model class for table "{{%auction_bid}}".
 *
 * @property integer $id
 * @property integer $auction_id
 * @property string $amount
 * @property integer $created_by
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Auction $auction
 */
class AuctionBid extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auction_bid}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auction_id', 'amount'], 'required'],
            [['auction_id'], 'integer'],
            [
                ['amount'],
                'number',
                'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'
            ],
//            [
//                ['auction_id'],
//                'exist',
//                'skipOnError' => true,
//                'targetClass' => Auction::className(),
//                'targetAttribute' => ['auction_id' => 'id']
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
            'auction_id' => One::t('app', 'Auction ID'),
            'amount' => One::t('app', 'Amount'),
            'created_by' => One::t('app', 'Created By'),
            'created_at' => One::t('app', 'Created At'),
            'updated_at' => One::t('app', 'Updated At'),
            'updated_by' => One::t('app', 'Updated By'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuction()
    {
        return $this->hasOne(Auction::className(), ['id' => 'auction_id']);
    }

    /**
     * @inheritdoc
     * @return AuctionBidQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuctionBidQuery(get_called_class());
    }
}
