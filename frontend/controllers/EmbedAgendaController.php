<?php
namespace frontend\controllers;

use frontend\controllers\AgendaController;

class EmbedAgendaController extends AgendaController{

	public $layout = 'embed';
	var $embedded = true;

}