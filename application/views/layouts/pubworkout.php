<?php
if (count($workouts) != 0) {
	foreach ($workouts as $workout) { ?>
		<h1><?= $workout->pa_txt; ?></h1>
		<table width="100%">
		<tr>
			<td width="35%" style="vertical-align:text-top;">
				<img src="/assets/img/weight.jpg" style="display:inline; position: relative; ">
			</td>
			<td width="65%" style="vertical-align:text-top; padding-left:50px; top: -190px; position: relative;">
				<h3><span class="label label-info" style="font-size: 16px;">Time</span> <?= $workout->pa_mins; ?> minutes</h3>
				<br>
				<h3><span class="label label-info" style="font-size: 16px;">Mets</span> <?= $workout->pa_mets; ?></h3>
				<br>
				<h3><span class="label label-important" style="font-size: 16px;">Calories</span> <?= $workout->pa_kcal; ?></h3>
				<br>
				<h3><span class="label" style="font-size: 16px;">Date</span> <?= $workout->pa_date; ?></h3>
			</td>
		</tr>
		</table>
	<?php } ?>
<?php } else { ?>
Count not find this iburnd story. The member either marked it as private or deleted the story.
<?php } ?>
