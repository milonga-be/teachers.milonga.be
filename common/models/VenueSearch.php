<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Venue;

/**
 * VenueSearch represents the model behind the search form about `common\models\Venue`.
 */
class VenueSearch extends Venue
{

    var $school_protected = TRUE;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'address','postalcode'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params )
    {
        $query = Venue::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'		=> [				
				'defaultOrder'	=> ['name' => SORT_ASC]
			]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if( $this->school_protected ){
            $user = Yii::$app->user->identity;
            $school = $user->school;
            $query->andFilterWhere([
                'school_id' => $school->id,
            ]);
        }
        

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'address', $this->address]);
        $query->andFilterWhere(['like', 'postalcode', $this->postalcode]);
      
        return $dataProvider;
    }
}
