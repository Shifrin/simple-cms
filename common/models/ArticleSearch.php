<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NewsSearch represents the model behind the search form about `common\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['id', 'status', 'publish_at', 'created_by', 'created_at', 'updated_at', 'updated_by'],
                'integer'
            ],
            [
                ['type', 'title', 'content', 'summary', 'category'],
                'safe'
            ],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Article::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'title',
                    'slug',
                    'content',
                    'summary',
                    'status',
                    'author_id',
                    'publish_at',
                    'created_by',
                    'created_at',
                    'updated_at',
                    'updated_by',
                ]
            ]
        ]);

        $dataProvider->sort->attributes['category'] = [
            'asc' => ['{{%category}}.id' => SORT_ASC],
            'desc' => ['{{%category}}.id' => SORT_DESC],
        ];

//        $query->joinWith('categories');
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

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

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'summary', $this->summary]);

        $query->andFilterWhere(['like', 'category', $this->categories]);

        return $dataProvider;
    }
}
