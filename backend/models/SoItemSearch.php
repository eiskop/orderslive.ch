<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SoItem;

/**
 * SoItemSearch represents the model behind the search form about `backend\models\SoItem`.
 */
class SoItemSearch extends SoItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'so_id', 'created_by', 'changed_by'], 'integer'],
            [['so_item_no', 'created', 'changed'], 'safe'],
            [['qty', 'value'], 'number'],
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
        $query = SoItem::find();

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
            'so_id' => $this->so_id,
            'qty' => $this->qty,
            'value' => $this->value,
            'created_by' => $this->created_by,
            'created' => $this->created,
            'changed_by' => $this->changed_by,
            'changed' => $this->changed,
        ]);

        $query->andFilterWhere(['like', 'so_item_no', $this->so_item_no]);

        return $dataProvider;
    }
}
