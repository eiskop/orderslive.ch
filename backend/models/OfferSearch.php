<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Offer;
use backend\models\User;
use backend\models\OfferStatus;


/**
 * OfferSearch represents the model behind the search form about `backend\models\Offer`.
 */
class OfferSearch extends Offer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'qty', 'prio1', 'days_to_process', 'deadline'], 'integer'],
            [['offer_no', 'offer_wir_id', 'customer_contact', 'customer_order_no', 'confirmation_no', 'offer_received', 'customer_priority_id', 'comments', 'created', 'updated', 'processed_by_id', 'followup_by_id', 'product_group_id', 'customer_id', 'carpenter','status_id','created_by', 'updated_by', 'assigned_to'], 'safe'],
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
        $query = Offer::find();

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
        $query->joinWith('assignedTo');
        $query->joinWith('followupBy');
        $query->joinWith('status');
//        $query->joinWith('offerStatus');
        // grid filtering conditions
        $query->andFilterWhere([
            'offer.id' => $this->id,
            //'offer.processed_by_id' => $this->processed_by_id,
            //'offer.followup_by_id' => $this->followup_by_id,
            //'offer.product_group_id' => $this->product_group_id,
            //'offer.customer_id' => $this->customer_id,
            //'offer.carpenter' => $this->carpenter,
            'offer.qty' => $this->qty,
            //'offer.prio1' => $this->prio1,
            'offer.value' => $this->value,
            'offer_received' => $this->offer_received,
            //'offer.days_to_process' => $this->days_to_process,
            //'offer.deadline' => $this->deadline,
            //'offer.created_by' => $this->created_by,
            //'offer.created' => $this->created,
            //'offer.updated_by' => $this->updated_by,
            //'offer.updated' => $this->updated,
        ]);

        $query->andFilterWhere(['offer.offer_no'=>$this->offer_no])
            ->andFilterWhere(['like', 'offer.customer_order_no', $this->customer_order_no])
            ->andFilterWhere(['like', 'customer.name', $this->customer_id])
            ->andFilterWhere(['like', 'LOWER(carpenter)', strtolower($this->carpenter)])
            ->andFilterWhere(['offer.customer_priority_id' => $this->customer_priority_id])
            ->andFilterWhere(['offer.status_id' => $this->status_id])
            ->andFilterWhere(['user_followup_by_id.id' => $this->followup_by_id])
            ->andFilterWhere(['user_assigned_to.id' => $this->assigned_to]);  

/*       $query->andFilterWhere(['like', 'customer_order_no', $this->customer_order_no])
            ->andFilterWhere(['like', 'confirmation_no', $this->confirmation_no])
            ->andFilterWhere(['like', 'surface', $this->surface])
            ->andFilterWhere(['like', 'so_status.name', $this->status_id])
            ->andFilterWhere(['like', 'so.customer_priority_id', $this->customer_priority_id])
            ->andFilterWhere(['like', 'product_group.name', $this->product_group_id])
            ->andFilterWhere(['like', 'customer.name', $this->customer_id])
            ->andFilterWhere(['like', 'user.username', $this->created_by]);            
*/
        return $dataProvider;
    }
}
