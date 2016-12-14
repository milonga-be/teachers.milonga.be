<?php
$this->title = 'Update location';
echo '<h1>'. $this->title .'</h1>';

echo $this->render('_form' , ['venue' => $venue ] );
?>