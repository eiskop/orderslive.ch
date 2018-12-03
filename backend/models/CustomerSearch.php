<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Customer;

/**
 * CustomerSearch represents the model behind the search form about `backend\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_group_id', 'created', 'created_by', 'updated_by', 'ifas_account'], 'integer'],
            [['name', 'customer_priority_id', 'contact', 'street', 'zip_code', 'city', 'province', 'fax_no', 'tel_no', 'updated'], 'safe'],
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
        $query = Customer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['name'=>SORT_ASC]]
        ]);
        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('customerDiscount');
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_group_id' => $this->customer_group_id,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'updated' => $this->updated,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'customer_priority_id', $this->customer_priority_id])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'province', $this->province])
            ->andFilterWhere(['like', 'fax_no', $this->fax_no])
            ->andFilterWhere(['like', 'ifas_account', $this->ifas_account])
            ->andFilterWhere(['like', 'tel_no', $this->tel_no]);

        return $dataProvider;
    }
}
