<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\So;
use backend\models\SoStatus;


/**
 * SoSearch represents the model behind the search form about `backend\models\So`.
 */
class SoSearch extends So
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'days_to_process', 'qty', 'product_type'], 'integer'],
            [['product_group_id', 'customer_order_no', 'confirmation_no', 'surface', 'status_id', 'order_received', 'customer_priority_id', 'created', 'customer_id', 'updated', 'created_by', 'updated_by', 'assigned_to', 'offer_no', 'product_type'], 'safe'],
            [['value'], 'number'],
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
        $query = So::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('productGroup');
        $query->joinWith('customer');
        $query->joinWith('updatedBy');
        $query->joinWith('soStatus');

  /*      $query->andFilterWhere([
            'so.id' => $this->id,
           // 'product_group_id' => $this->product_group_id,
            //'customer_id' => $this->customer_id,
            'value' => $this->value,
            'qty' => $this->qty, 
            'order_received' => $this->order_received,
            'days_to_process' => $this->days_to_process,
            //'created_by' => $this->created_by,
            'so.created' => $this->created,
            'so.updated_by' => $this->updated_by,
            'so.updated' => $this->updated,
            'so.assigned_to' => $this->assigned_to,
        ]);
*/
        $query->andFilterWhere(['like', 'so.customer_order_no', $this->customer_order_no])
            ->andFilterWhere(['like', 'so.confirmation_no', $this->confirmation_no])
            ->andFilterWhere(['like', 'so.surface', $this->surface])
            ->andFilterWhere(['=', 'so.product_type', $this->product_type])
            ->andFilterWhere(['so.status_id' => $this->status_id])
            ->andFilterWhere(['like', 'so.customer_priority_id', $this->customer_priority_id])
            ->andFilterWhere(['like', 'so.product_group_id', $this->product_group_id])
            ->andFilterWhere(['like', 'customer.name', $this->customer_id])
            ->andFilterWhere(['like', 'so.created_by', $this->created_by])
            ->andFilterWhere(['like', 'so.assigned_to', $this->assigned_to]);

        return $dataProvider;
    }
}
