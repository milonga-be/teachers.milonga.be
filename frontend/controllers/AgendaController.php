<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class AgendaController extends Controller{

	public function actionList(){

		$google_calendar_id = Yii::$app->params['google-calendar-id'];
		$google_api_key = Yii::$app->params['google-api-key'];

		$json_array = $this->getFromGoogleApi( 'https://www.googleapis.com/calendar/v3/calendars/' . $google_calendar_id . '/events?key=' . $google_api_key . '&orderBy=startTime&singleEvents=true&timeMin=' . urlencode( date('c') ) );

		// var_dump($json_array);

		return $this->render('list',['events' => $json_array['items']]);

	}


	private function getFromGoogleApi( $url ){

		$content = file_get_contents( $url );
		if( $content ){
			return json_decode($content, true);
		}else{
			return false;
		}

	}
}