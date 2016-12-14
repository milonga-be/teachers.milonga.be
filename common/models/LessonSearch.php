<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Lesson;

/**
 * LessonsSearch represents the model behind the search form about `common\models\Lesson`.
 */
class LessonSearch extends Lesson
{

    var $school_protected = TRUE;
    var $postal_codes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['day', 'venue_id','teachers','level'], 'safe'],
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
        $query = Lesson::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'		=> [				
				'defaultOrder'	=> ['day' => SORT_ASC]
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
        

        $query->andFilterWhere(['=', 'venue_id', $this->venue_id]);
        $query->andFilterWhere(['like', 'teachers', $this->teachers]);
        $query->andFilterWhere(['=', 'level', $this->level]);
        $query->andFilterWhere(['=', 'day', $this->day]);
      
        return $dataProvider;
    }
}
