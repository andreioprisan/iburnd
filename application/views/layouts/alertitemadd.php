<div id="justadd_alerts" style="display:none">
	<div class="alert alert-danger justadd_workout" name="justadd_id" style="display:none">
		<input type="hidden" id="w_id" value="">
		<input type="hidden" id="pa_id" value="">
		<input type="hidden" id="pa_mets" value="">
		<input type="hidden" id="pa_kcal" value="">
		<strong>
			<div id="justadd_type" style="display:inline">
				<span class="label label-info" style="font-size: 18px; top: 1px; position: relative; left: -4px;">Workout</span> 
			</div>
		</strong>
		<div id="text" style="display:inline; font-weight:bold; font-size: 15px; padding-left: 9.5%;"></div>
		<a class="close" href="#" onclick="clearAlertTag(); ">×</a>
	</div>

	<div class="alert alert-info justadd_nutrition" name="justadd_id" style="display:none">
		<input type="hidden" id="nid" value="">
		<input type="hidden" id="f_id" value="">
		<input type="hidden" id="n_kcal" value="">
		<input type="hidden" id="s_id" value="">
		<input type="hidden" id="s_s" value="">
		<strong>
			<div id="justadd_type" style="display:inline">
				<span class="label label-important" style="font-size: 18px; top: 1px; position: relative; left: -4px;">Food</span> 
			</div>
		</strong>
		<div id="text" style="display:inline; font-weight:bold; font-size: 15px; padding-left: 10%;"></div>
		<a class="close" href="#" onclick="clearAlertTag(); ">×</a>
		
	</div>

	<div id="justadd_alert" style="display:inline; position:relative; top:-51px; left: -2.6%; float:right; width:88%">
		<div class="input-prepend" id="food_servings" style="display:inline; position:absolute; left: -3.9%; display:inline">
			<input type="text" id="justadd_servingscount" class="" style="width:28px; font-size:14px; left: 1.5%; position: absolute; text-shadow: 0 1px 0 white; background-color: white; border: 1px solid #CCC; -webkit-border-radius: 3px 0 0 3px; -moz-border-radius: 3px 0 0 3px; border-radius: 3px 0 0 3px; display: inline; " placeholder="1" onchange="servings2cal(this.value);">
		</div>

		<div id="food_serving_size" class="btn-group" style="display:inline; position:absolute; left: -0.5%; display:none">
			<a class="btn food_serving_default" href="#" id="" onclick="rotateServingType();">serving size</a>
		</div>
		
		
		<div id="workout_mins" class="input-prepend" style="display:inline; position:absolute; left: 1.5%; display:none">
			<input type="text" id="justadd_time" class="" style="width:28px; font-size:14px; left: 1.5%; position: absolute; text-shadow: 0 1px 0 white; background-color: white; border: 1px solid #CCC; -webkit-border-radius: 3px 0 0 3px; -moz-border-radius: 3px 0 0 3px; border-radius: 3px 0 0 3px; " placeholder="?" onchange="mets2cal(mets, this.value);">
			<span class="add-on" style="position: relative; left: 117%;">min</span>
		</div>
	
		<div class="label" style="display:inline; left: 72%; width: 95px; position: absolute; font-size: 16px; top:3px; display:none;" id="justadd_cals">Default</div>
		
		<div class="input-prepend" style="display:inline; position:absolute; left: 85%">
			<span class="add-on" style="right: 10%; position: absolute; font-size: 20px;"><i class="icon-calendar"></i></span>
			<input type="text" id="datepicker_workout" class="datepicker" style="width:61px; font-size:14px; position: absolute; text-shadow: 0 1px 0 white; background-color: white; border: 1px solid #CCC; -webkit-border-radius: 3px 0 0 3px; -moz-border-radius: 3px 0 0 3px; border-radius: 3px 0 0 3px;" placeholder="on date">
		</div>
		<a class="btn" id='justadd_btn' style="right: 4.5%; position: absolute;" onclick="toggleJustAddSubmit();"><i class="icon-ok" style="font-size: 22px;top: -3px;position: relative;left: -3px;"></i></a>
		<a class="btn btn-danger" id='justadd_btn_del' style="right: 0.5%; position: absolute; display: none;" onclick="toggleJustDeleteSubmit();"><i class="icon-trash" style="font-size: 22px;top: -1px;position: relative;left: -0px;"></i></a>
		
	</div>
</div>
