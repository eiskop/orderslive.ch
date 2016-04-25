<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomerGroup;

/**
 * CustomerGroupSearch represents the model behind the search form about `backend\models\CustomerGroup`.
 */
class CustomerGroupSearch extends CustomerGroup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'zip_code', 'created', 'created_by', 'updated_by'], 'integer'],
            [['name', 'contact', 'street', 'city', 'province', 'fax_no', 'tel_no', 'updated'], 'safe'],
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
        $query = CustomerGroup::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'zip_code' => $this->zip_code,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'updated' => $this->updated,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'fax_no', $this->fax_no])
            ->andFilterWhere(['like', 'tel_no', $this->tel_no]);

        return $dataProvider;
    }
}
