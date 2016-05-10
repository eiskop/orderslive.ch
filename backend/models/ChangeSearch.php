<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Change;

/**
 * ChangeSearch represents the model behind the search form about `backend\models\Change`.
 */
class ChangeSearch extends Change
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'change_time', 'change_type', 'change_reason', 'created_by', 'updated_by'], 'integer'],
            [['change_object', 'change_object_id', 'comment', 'created', 'updated'], 'safe'],
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
        $query = Change::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'change_time' => $this->change_time,
            'change_type' => $this->change_type,
            'change_reason' => $this->change_reason,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'updated' => $this->updated,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'change_object', $this->change_object])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
