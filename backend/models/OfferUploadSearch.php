<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OfferUpload;

/**
 * OfferUploadSearch represents the model behind the search form about `backend\models\OfferUpload`.
 */
class OfferUploadSearch extends OfferUpload
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'offer_id', 'file_size', 'created_by', 'changed_by'], 'integer'],
            [['file_path', 'file_name', 'file_extension', 'file_type', 'title', 'description', 'created', 'changed'], 'safe'],
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
        $query = OfferUpload::find();

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
            'file_size' => $this->file_size,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'changed' => $this->changed,
            'changed_by' => $this->changed_by,
        ]);

        $query->andFilterWhere(['like', 'file_path', $this->file_path])
            ->andFilterWhere(['like', 'file_name', $this->file_name])
            ->andFilterWhere(['like', 'file_extension', $this->file_extension])
            ->andFilterWhere(['like', 'file_type', $this->file_type])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
