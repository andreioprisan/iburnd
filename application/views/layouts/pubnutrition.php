
<?php
if (isset($foods))
{
	
if (count($foods) != 0) {
	foreach ($foods as $food) { 

		$s_s_text = "";
		$s_s_val = "";
		if ($food->s_s == 0 || $food->s_s == null || $food->s_s == "1") 
			$s_s_val .= (double)1; 
		else 
			$s_s_val .= (double)$food->s_s; 
	
		$s_s_text .= $s_s_val;

		$s_s_text2 = "";
		if ($food->s == null) 
			$s_s_text2 .= "serving";
		else 
			$s_s_text2 .= $food->s; 
	
		$s_s_text .= " ".$s_s_text2;
		?>
		<script>
		showNutrPlank('<?= $food->nid ?>','<?= $food->fid ?>', '1', '<?= $s_s_text2 ?>');
		</script>
			<h1><?= $food->n; ?></h1>
		
		<table width="100%">
		<tr>
			<td width="35%" style="vertical-align:text-top;">
				<img src="/assets/img/food.jpg" style="display:inline; position: relative; ">
				
			</td>
			<td width="40%" style="vertical-align:text-top;  top: -190px; position: relative;">
				<h3><span class="label label-info" style="font-size: 16px;">Servings</span> <?= $food->s_s; ?></h3>
				<br>
				<h3><span class="label label-info" style="font-size: 16px;">Serving Size</span> <?php if ($food->s != null) { echo $food->s ; } else { echo "1 serving"; }; ?></h3>
				<br>
				<h3><span class="label label-important" style="font-size: 16px;">Calories</span> <?= $food->n_kcal; ?></h3>
				<br>
				<h3><span class="label" style="font-size: 16px;">Date</span> <?= $food->n_date; ?></h3>
			</td>
			<td width="25%"  style="vertical-align:text-top;  top: -200px; position: relative;">
				<div id="nd_<?= $food->nid ?>" class="nutritionaldetails" style="display:none"></div>
			</td>
		</tr>
		</table>
	<?php } ?>
<?php } else { ?>
Count not find this iburnd story. The member either marked it as private or deleted the story.
<?php } ?>

<?php } ?>

<!--
<a href="#" class="btn btn-danger" rel="dpopover" data-content="<table border=1><tr><td>asdf</td><td>asdf</td></tr></table>And here's some amazing content. It's very engaging. right?" data-original-title="A Title">hover for popover</a>
-->
