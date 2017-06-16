<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OfferStatusLog;

/**
 * OfferStatusLogSearch represents the model behind the search form about `backend\models\OfferStatusLog`.
 */
class OfferStatusLogSearch extends OfferStatusLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'offer_id', 'followup_by_id',  'status_id', 'assigned_to', 'created_by', 'updated_by'], 'integer'],
            [['customer_contact', 'contact_date', 'topics', 'next_steps', 'next_followup_date', 'comments', 'created', 'updated', 'customer_id'], 'safe'],
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
        $query = OfferStatusLog::find();

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

        // grid filtering conditions
  /*      $query->andFilterWhere([
            'contact_date' => $this->contact_date,
            'next_followup_date' => $this->next_followup_date,
            'status_id' => $this->status_id,
            'created_by' => $this->created_by,
            'created' => $this->created,
            'updated_by' => $this->updated_by,
            'updated' => $this->updated,
        ]);
*/
        $query->andFilterWhere(['like', 'customer_contact', $this->customer_contact])
            ->andFilterWhere(['like', 'topics', $this->topics])
            ->andFilterWhere(['like', 'next_steps', $this->next_steps])
            ->andFilterWhere(['like', 'offer.offer_no', $this->offer_id])             
            ->andFilterWhere(['like', 'comments', $this->comments])            
            ->andFilterWhere(['like', 'customer.name', $this->customer_id])
            ->andFilterWhere(['offer.status_id' => $this->status_id])
            ->andFilterWhere(['user_followup_by_id.id' => $this->followup_by_id])
            ->andFilterWhere(['user_assigned_to.id' => $this->assigned_to]);  ;

        return $dataProvider;
    }
}
