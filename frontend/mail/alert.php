<p>Dear tango organizers,</p>

<p>These are the events listed on the milonga.be agenda,<br/>
Please check that all the information is correct before we send our newsletter on Friday.</p>

<p>
How to add information to the milonga.be calendar:<br/>
* Encode the event in our tool at http://teachers.milonga.be/backend/ <br>
* Just respect these minimal instructions: a correct title, always a full street address, and at least an email address where you can be contacted. 
</p>

<p>If an event doesn't take place excpetionnaly (beware with the holidays !) please remove it</p>

<p>Thanks for your collaboration,</p>
<p>
--<br>
Boris Verdeyen Pazmiño<br>
milonga@milonga.be<br>
</p>
<hr>
<h4>MILONGAS &amp; WORKSHOPS : </h4>

<?php 
// $i = 0;
foreach ($events as $event) { ?>
	<?php if( !isset($previous_event) || (new Datetime($previous_event['start']['dateTime']))->format('Ymd') != (new Datetime($event['start']['dateTime']))->format('Ymd') ){ ?>
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
	$previous_event = $event;
}
?>