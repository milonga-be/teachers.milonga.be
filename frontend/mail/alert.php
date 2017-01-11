<p>Dear tango organizers,</p>

<p>These are the events listed on the milonga.be agenda, and among them there is one you created.<br/>
Please check that all the information is correct before we send our newsletter tomorrow at noon.</p>

<p>
How to add information to the milonga.be calendar:<br/>
* Put the event into the Google Calendar and it will show up minutes later on http://www.milonga.be. <br>
* Just respect these minimal instructions: a correct event title (with MILONGA: or WORKSHOP: ), always a full street address, and at least an email address where you can be contacted. 
</p>

<p>Thanks for your collaboration,</p>
<p>
--<br>
Boris Verdeyen Pazmiño<br>
milonga@milonga.be<br>
</p>
<hr>
<h4>MILONGAS: </h4>

<?php 
$i = 0;
$events = $milongas;
foreach ($events as $event) { ?>
	<?php if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ ?>
	<h4><?= (new Datetime($event['start']['dateTime']))->format('l, F j')?></h4>
	<?php } ?>
	<p>
		<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?>
		 - 
		<?= $event['summary'] ?>
		<?php if( isset($event['location']) ){ ?>
		<br><?= $event['location']?>
		<?php } ?>
	</p>
<?php
	$i++;
}
?>
<hr>
<h3>WORKSHOPS: </h3>
<?php 
$i = 0;
$events = $workshops;
foreach ($events as $event) { ?>
	<?php if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ ?>
	<h4><?= (new Datetime($event['start']['dateTime']))->format('l, F j')?></h4>
	<?php } ?>
	<p>
		
		<?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?>
		 - 
		<?= $event['summary'] ?>
		<?php if( isset($event['location']) ){ ?>
		<br><?= $event['location']?>
		<?php } ?>
	</p>
<?php
	$i++;
}
?>
<hr>