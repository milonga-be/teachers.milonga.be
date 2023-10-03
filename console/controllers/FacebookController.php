<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\School;
use common\models\User;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\Page;
use FacebookAds\Object\PagePost;


/**
 * Schools controller
 */
class FacebookController extends Controller{

    /**
     * Post Weekly Milongas
     */
    public function actionPostMilongas(){
        $app_id = "1275475786660941";
        $app_secret = "8697af3193f942d6a795d3da6f89d648";
        $access_token = "EAASICdkuZCE0BAHFyeTNcSo3s1t7ZAEwQci5ZAt3iPOBUSZAHizLEDZBq3tfvUkcbwsUUZBnEPQBLVLiOhsicU14Um3c51oLch61Gwu0dIFbDbvqZBypDLa5Da7YPEOx1y6XhyZAi2ZC25HAszIzMB3fhAengQ5KqNYnlSFz83VCRQyWZBnbcBbK6DKuedB706bUY1PxKX6mRWRAZDZD";
        $id = "272158899527489";

        $api = Api::init($app_id, $app_secret, $access_token);
        $api->setLogger(new CurlLogger());

        $fields = array(
        );
        $params = array(
          'message' => 'This is a test value',
          // 'picture' => 'https://teachers.milonga.be/frontend/web/img/cadre%20winter-letstango.png',
          // 'link' => 'https://www.milonga.be'
          // 'published' => 'false',
        );
        echo json_encode((new Page($id))->createFeed(
          $fields,
          $params
        )->exportAllData(), JSON_PRETTY_PRINT);
    }
}