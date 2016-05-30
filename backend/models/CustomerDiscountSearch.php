<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CustomerDiscount;

/**
 * CustomerDiscountSearch represents the model behind the search form about `backend\models\CustomerDiscount`.
 */
class CustomerDiscountSearch extends CustomerDiscount
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'offer_item_type_id', 'created_by', 'updated_by', 'approved_by', 'active'], 'integer'],
            [['base_discount_perc'], 'double'],
            [['valid_from', 'created', 'updated', 'approved'], 'safe'],
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
        $query = CustomerDiscount::find();

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
            'customer_id' => $this->customer_id,
            'offer_item_type_id' => $this->offer_item_type_id,
            'base_discount_perc' => $this->base_discount_perc,
            'valid_from' => $this->valid_from,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'updated' => $this->updated,
            'updated_by' => $this->updated_by,
            'approved' => $this->approved,
            'approved_by' => $this->approved_by,
            'active' => $this->active,
        ]);

        return $dataProvider;
    }
}
