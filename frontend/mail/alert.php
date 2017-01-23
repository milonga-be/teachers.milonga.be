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
<h4>MILONGAS &amp; WORKSHOPS : </h4>

<?php 
$i = 0;
foreach ($events as $event) { ?>
	<?php if( !isset($events[$i-1]) || (new Datetime($events[$i-1]['start']['dateTime']))->format('Ymd') != (new Datetime($events[$i]['start']['dateTime']))->format('Ymd') ){ ?>
	<h4 style="color:#F66062;"><?= (new Datetime($event['start']['dateTime']))->format('l, F j')?></h4>
	<?php } ?>
	<p>
		<?php if(isset($event['category'])){ ?>
			<h6 style="margin-bottom:0px"><?= strtoupper($event['category'])?></h6>
		<?php } ?>
		<strong><?= $event['summary'] ?></strong><br>
		<small><?= (new Datetime($event['start']['dateTime']))->format('H:i') ?> - <?= (new Datetime($event['end']['dateTime']))->format('H:i')?></small>
		<?php if( isset($event['location']) ){ ?>
		<br><small><?= $event['location']?></small>
		<?php } ?>
	</p>
<?php
	$i++;
}
?>