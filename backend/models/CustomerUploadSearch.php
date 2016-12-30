<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomerUpload;

/**
 * CustomerUploadSearch represents the model behind the search form about `backend\models\CustomerUpload`.
 */
class CustomerUploadSearch extends CustomerUpload
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'file_size'], 'integer'],
            [['customer_id', 'created_by', 'changed_by', 'file_path', 'file_name', 'file_extension', 'file_type', 'title', 'description', 'valid_from', 'valid_to', 'created', 'changed'], 'safe'],
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
        $query = CustomerUpload::find();

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
        $query->joinWith('customer');
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
         //   'customer_id' => $this->customer_id,
            'file_size' => $this->file_size,
            'valid_from' => $this->valid_from,
            'valid_to' => $this->valid_to,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'changed' => $this->changed,
            'changed_by' => $this->changed_by,
        ]);

        $query->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'customer.name', $this->customer_id])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension])
            ->andFilterWhere(['like', 'file_type', $this->file_type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
