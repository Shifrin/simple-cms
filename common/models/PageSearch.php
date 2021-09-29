<?php

namespace common\models;

use One;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\StringHelper;

/**
 * PageSearch represents the model behind the search form about `common\models\Page`.
 */
class PageSearch extends Page
{
    public $string_search;
    public $date_attribute;
    public $date_search_from;
    public $date_search_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'publish_at', 'created_by', 'created_at', 'updated_at', 'updated_by'], 'integer'],
            [['title', 'slug', 'content', 'string_search', 'date_attribute',
                'date_search_from', 'date_search_to'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        $query = Page::find()->where('status!=' . self::STATUS_TRASH);

        // Only for trashed items
        if (One::app()->request->get('tab') == 'trash') {
            $query = Page::find()->trash();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $formatter = One::app()->formatter;

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'publish_at' => $this->publish_at,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'content', $this->content]);

        if (!empty($this->date_attribute) && !empty($this->date_search_from)) {
            $this->date_search_from = $formatter->asTimestamp($this->date_search_from);
            $query->andFilterWhere(['>=', $this->date_attribute, $this->date_search_from]);
        }

        if (!empty($this->date_attribute) && !empty($this->date_search_to)) {
            $this->date_search_to = $formatter->asTimestamp($this->date_search_to);
            $query->andFilterWhere(['<=', $this->date_attribute, $this->date_search_to]);
        }

        if (!empty($this->string_search) && strpos($this->string_search, ':') !== false) {
            $string = StringHelper::explode($this->string_search, ':');

            switch ($string[0]) {
                case 'title':
                    $query->andFilterWhere(['like', 'title', $string[1]]);
                    break;
                case 'slug':
                    $query->andFilterWhere(['like', 'slug', $string[1]]);
                    break;
                case 'content':
                    $query->andFilterWhere(['like', 'content', $string[1]]);
                    break;
            }
        } else {
            $query->andFilterWhere([
                'OR',
                ['like', 'title', $this->string_search],
                ['like', 'slug', $this->string_search],
                ['like', 'content', $this->string_search],
            ]);
        }

        return $dataProvider;
    }

    public function dateAttributes()
    {
        return [
            'publish_at' => $this->getAttributeLabel('publish_at'),
            'created_at' => $this->getAttributeLabel('created_at'),
            'updated_at' => $this->getAttributeLabel('updated_at'),
        ];
    }
}
