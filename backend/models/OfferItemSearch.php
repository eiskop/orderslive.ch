<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OfferItem;

/**
 * OfferItemSearch represents the model behind the search form about `backend\models\OfferItem`.
 */
class OfferItemSearch extends OfferItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'offer_id', 'offer_item_type_id', 'created_by', 'changed_by'], 'integer'],
            [['qty', 'value', 'project_discount_perc', 'value_net'], 'number'],
            [['created', 'changed'], 'safe'],
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
        $query = OfferItem::find();

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
            'offer_id' => $this->offer_id,
            'offer_item_type_id' => $this->offer_item_type_id,
            'qty' => $this->qty,
            'value' => $this->value,
            'value_net' => $this->value_net,
            'project_discount_perc' => $this->project_discount_perc,
            'created_by' => $this->created_by,
            'created' => $this->created,
            'changed_by' => $this->changed_by,
            'changed' => $this->changed,
        ]);

        return $dataProvider;
    }
}
