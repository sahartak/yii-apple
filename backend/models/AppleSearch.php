<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Apple;

/**
 * AppleSearch represents the model behind the search form of `common\models\Apple`.
 */
class AppleSearch extends Apple
{
    public $status;

    /**
     * AppleSearch Constructor
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct(array $config = [])
    {
        parent::__construct('', $config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'on_tree'], 'integer'],
            [['created_at', 'fall_at'], 'safe'],
            [['color', 'status'], 'string'],
            [['size'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Apple::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
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
            'on_tree' => $this->on_tree,
            'size' => $this->size,
        ]);
        if($this->created_at){
            [$dateFrom, $dateTo] = static::getDatesRange($this->created_at);
            $query->andWhere(['between','created_at', $dateFrom, $dateTo]);
        }
        if($this->fall_at){
            [$dateFrom, $dateTo] = static::getDatesRange($this->fall_at);
            $query->andWhere(['between','fall_at', $dateFrom, $dateTo]);
        }

        switch ($this->status) {
            case self::STATUS_ON_TREE:
                $query->andFilterWhere(['on_tree' => true]);
            break;
            case self::STATUS_FALLEN:
                $query->andFilterWhere(['on_tree' => false]);
                break;
            case self::STATUS_SPOILED:
                $query->andWhere(['<','fall_at', time() - self::SPOIL_TIME]);
            break;
        }

        $query->andFilterWhere(['LIKE', 'color', $this->color]);

        return $dataProvider;
    }

    public static function getDatesRange($date){
        $dateFrom = strtotime($date);
        $dateTo = strtotime($date.' 23:59:59');
        return [$dateFrom, $dateTo];
    }
}
