<?php
if (isset($date))
{
$date_split = explode("/", $date);
$prev  = mktime(0, 0, 0, $date_split['0'], $date_split['1']-1, $date_split['2']);
$next  = mktime(0, 0, 0, $date_split['0'], $date_split['1']+1, $date_split['2']);
$prev_date = date("m/d/y", $prev);
$next_date = date("m/d/y", $next);
?>
&nbsp;&nbsp;&nbsp;&nbsp;<h3>Nutritional Log for <a href="<?= "/nutritions/log?date=".$prev_date ?>">&lt;</a> <?= $date ;?> <a href="<?= "/nutritions/log?date=".$next_date ?>">&gt;</a></h3><br>

<?php
}

if (count($foods) != 0) {
?>
<table class="table table-striped" style="width: 100%">
	<thead>
		<tr>
			<th width="5%">for date</th>
			<th width="2%">#</th>
			<th width="25%">serving</th>
			<th width="65%">item description</th>
			<th width="6%" style="text-align:right">Calories</th>
<!--			<th width="5%">added</th>
-->
		</tr>
	</thead>
	<tbody>
		<?php 
		$total_kcal = 0;
		foreach ($foods as $food) { ?>
			<?php 	
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
		<tr id="n_<?= $food->nid ?>" onclick="thisitem = 	{
					type: 'food',
					fid: <?= $food->fid ?>,
					label: '<?= rawurlencode(strtolower($food->n)); ?>',
					servingsize: <?php 
						if ($food->sid != NULL) 
						{ 
							echo '[ {  sid: '.(int)$food->sid.', sname: \''.$food->s.'\' } ]';
							if ($food->s_s == '0.00')
								$food->s_s = '1.00';
						} else { 
							echo '[ {  sid: 42, sname: \'serving\' } ]'; 
							$food->s_s = '1.00';
							$food->sid = '42';
						} 
						?>,
					s_s: <?= $food->s_s ?>,
					sid: <?= $food->sid ?>,
					nid: <?= $food->nid ?>,
					n_date: '<?= $food->n_date ?>',
					kcal: <?= $food->n_kcal/$food->s_s ?>
				}; doAutocompleteClick( 'nutrition', thisitem); $('.nutritionaldetails').hide(); $('#nd_<?= $food->nid ?>').fadeToggle(); showNutrPlank('<?= $food->nid ?>','<?= $food->fid ?>', '<?= $s_s_val ?>', '<?= $s_s_text2 ?>')">
			<td><?= $food->n_date ?></td>
			<td><?= $s_s_val; ?> 
			</td>
			<td><?= $s_s_text2; ?>
				</td>
			<td><?= strtolower($food->n) ?><br>
				<div id="nd_<?= $food->nid ?>" class="nutritionaldetails" style="display:none">
				<?php //$a = $stats_m->nutr_info_plank($fid, $s_s_val, $s_s_text2); ?>
				</div>
			</td>
			<td style="text-align:right"><?= $food->n_kcal ?></td>
<!--			<td><?php 
			$a = explode(" ", $food->n_datetime);
			$b = explode(":", $a[1]);
			if ($b[0] <= 9)
				echo "morning";
			else if ($b[0] <= 17)
				echo "afternoon";
			else if ($b[0] <= 24)
				echo "evening";?></td>
-->
		</tr>
		<?php 
		$total_kcal += (float)$food->n_kcal;
		} ?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td  style="text-align:right"><b><?= number_format($total_kcal,2); ?></b></td>
		</tr>
	</tbody>
</table>
<?php } else { ?>
No foods recorded for this day.
<?php } ?>

<!--
<a href="#" class="btn btn-danger" rel="dpopover" data-content="<table border=1><tr><td>asdf</td><td>asdf</td></tr></table>And here's some amazing content. It's very engaging. right?" data-original-title="A Title">hover for popover</a>
-->
