<?php
$this->title = 'New class';
echo '<h1>'. $this->title .'</h1>';

echo $this->render('_form' , ['lesson' => $lesson ] );
?>