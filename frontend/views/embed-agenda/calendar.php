<?php
use yii\web\View;
use common\components\Htmlizer;

echo $this->render('/agenda/calendar', [ 'events_by_date' => $events_by_date , 'start' => $start, 'month_first_day' => $month_first_day , 'weeks' => $weeks, 'selected_day' => $selected_day ]);