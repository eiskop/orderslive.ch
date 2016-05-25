<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DevelopmentLog;

/**
 * DevelopmentLogSearch represents the model behind the search form about `backend\models\DevelopmentLog`.
 */
class DevelopmentLogSearch extends DevelopmentLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'developer_id', 'approved_by_id', 'created_by', 'changed_by'], 'integer'],
            [['task_name', 'task_description', 'estimated_start_time', 'estimated_completion_time', 'approved_date', 'created', 'changed', 'completion_perc'], 'safe'],
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
        $query = DevelopmentLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'priority' => SORT_ASC,
                    'task_name' => SORT_ASC, 
                ]
            ],
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
            'priority' => $this->priority,
            'completion_perc' => $this->completion_perc,
            'developer_id' => $this->developer_id,
            'estimated_start_time' => $this->estimated_start_time,
            'estimated_completion_time' => $this->estimated_completion_time,
            'approved_by_id' => $this->approved_by_id,
            'approved_date' => $this->approved_date,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'changed' => $this->changed,
            'changed_by' => $this->changed_by,
        ]);

        $query->andFilterWhere(['like', 'task_name', $this->task_name])
            ->andFilterWhere(['like', 'task_description', $this->task_description]);

        return $dataProvider;
    }
}
