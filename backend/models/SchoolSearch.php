<?php
namespace backend\models;

use Yii;
use common\models\School;
use yii\data\ActiveDataProvider;

class SchoolSearch extends School{

	public function rules(){
		return [
			[['name'], 'safe']
		];
	}

	/**
	 * Returns an ActiveProvider for the search
	 * @param array $params The parameters for the search
	 * @return ActiveProvider
	 */
	public function search($params){
		$query = School::find();
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'		=> [				
				'defaultOrder'	=> ['name' => SORT_ASC,]
			]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if(!empty($this->name)){
            $query->andWhere(['LIKE', 'name', $this->name]);
        }

        return $dataProvider;
	}

}