<?php
namespace backend\models;

use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;

class UserSearch extends User{

	var $schoolname;

	public function rules(){
		return [
			[['username', 'schoolname'], 'safe']
		];
	}

	/**
	 * Returns an ActiveProvider for the search
	 * @param array $params The parameters for the search
	 * @return ActiveProvider
	 */
	public function search($params){
		$query = self::find()->joinWith('school');
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'		=> [				
				'defaultOrder'	=> ['username' => SORT_ASC,]
			]
        ]);

        $dataProvider->sort->attributes['schoolname'] = [
            'asc' => ['school.name' => SORT_ASC],
            'desc' => ['school.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if(!empty($this->username)){
            $query->andWhere(['LIKE', 'username', $this->username]);
        }
        if(!empty($this->schoolname)){
            $query->andWhere(['LIKE', 'school.name', $this->schoolname]);
        }

        return $dataProvider;
	}

}