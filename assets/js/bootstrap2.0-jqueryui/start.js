var mets = 0;
var kcal = 0;
var kcal_goal = 0;

var weight_lb = 175;
var height_in = 71;
var age = 24;
var user_activity_level = 1;
var gender = 1;

var bmi = 0;

var justadd_type;
var mainsearchtype = "nutrition";
var servingsize_curarid = 0;
var servingsize_curar;
var servingsize_curar_w = 0;
var thisitem;

var fid;
var sid;
var sid_s;

var date;

var a = "";
var awayfromhoversearch = true;
var itemstotall = 0;

function rotateServingType()
{
	if (servingsize_curarid + 1 == servingsize_curar.length)
		servingsize_curarid = 0;
	else 
		servingsize_curarid++;
	
	if (servingsize_curar[servingsize_curarid].sname)
		$('.food_serving_default ').html(servingsize_curar[servingsize_curarid].sname);
	else 
		$('.food_serving_default ').html('');
		
	if (servingsize_curar[servingsize_curarid].sw)
		servingsize_curar_w = servingsize_curar[servingsize_curarid].sw;
	
	sid = servingsize_curar[servingsize_curarid].sid;
	
	// bind correct serving id to item
	$('.justadd_'+justadd_type+' #s_id').val(sid);
	
	// update calories on serving click
	servings2cal($('#justadd_servingscount').val());
	
	// update text padding on serving change
	showPaddedFoodText(servingsize_curar[servingsize_curarid].sname.length);
}

function mainsearchtypeupdate(type)
{
	mainsearchtype = type;
}

function showItemCals(cals)
{
	$('#justadd_cals').html(cals + " Cals");
	$('#justadd_cals').fadeIn();
	
}

function showPaddedFoodText(sslentmp)
{
	var coeff = 2.85;
	if (sslentmp > 4 && sslentmp <= 8)
		coeff = 1.54;
	else if (sslentmp > 8 && sslentmp <= 10)
		coeff = 1.40;
	else if (sslentmp > 10 && sslentmp <= 13)
		coeff = 1.22;
	else if (sslentmp > 13 && sslentmp <= 15)
		coeff = 0.99;
	else if (sslentmp > 15 && sslentmp <= 18)
		coeff = 0.86;
	else if (sslentmp > 18)
		coeff = 0.68;

	// set food name offset
	var sslentmp_total = sslentmp * coeff;
	$('.justadd_nutrition #text').css('padding-left', sslentmp_total + "%" );					
}

function servings2cal(servings)
{
	var totalnewcals = thisitem.kcal;
	
	if (!servingsize_curar)
	{
		servingsize_curar = [ {  sid: 42, sname: 'serving' } ];
	}
	
	if (servings == 0 || servings == "" || servings == null || servings == "1")
		servings = 1;
	
	if (servingsize_curar_w == 0)
		totalnewcals = thisitem.kcal * servings;
	else {
		totalnewcals = servingsize_curar[servingsize_curarid].sw / 100 * thisitem.kcal * servings;
	}
	
	kcal = totalnewcals;
	sid = servingsize_curar[servingsize_curarid].sid;
	
	sid_s = servings;
	$('.justadd_'+justadd_type+' #s_s').val(sid_s);
	$('.justadd_'+justadd_type+' #n_kcal').val(kcal);
	
	showItemCals(Math.round(totalnewcals));

//	$('.justadd_'+justadd_type+' #pa_kcal').val(cals);
}

function mets2cal(mets, min)
{
	var cals = Math.round((weight_lb / 2.2) * ( min / 60 ) * mets);
	
	showItemCals(cals);
	
	$('.justadd_'+justadd_type+' #pa_kcal').val(cals);
	
}

function clearAlertTag()
{
	$('#justadd_alert').hide(); 
	$('.justadd_workout').hide(); 
	$('#justadd_time').val(''); 
	$('#justadd_time').attr("disabled", false);

	$('#datepicker_workout').val(''); 
	$('#datepicker_workout').attr("disabled", false);
	$('#datepicker_nutrition').val(''); 
	$('#datepicker_nutrition').attr("disabled", false);


	$('#justadd_cals').hide();
	$('.justadd_nutrition').hide();
	
	$('#food_servings').hide();
	$('#workout_mins').hide();
	$('#food_serving_size').hide();
	
	// reset workout data button text
	$('#justadd_btn').button('reset');
	// reset workout data items
	if (justadd_type == "workout")
	{
		$('.justadd_'+justadd_type+' #pa_id').val(' ');
		$('.justadd_'+justadd_type+' #pa_mets').val(' ');
		$('.justadd_'+justadd_type+' #pa_kcal').val(' ');
	} else {
		$('.justadd_'+justadd_type+' #nid').val(' ');
		$('.justadd_'+justadd_type+' #f_id').val(' ');
		$('.justadd_'+justadd_type+' #n_kcal').val(' ');
		$('.justadd_'+justadd_type+' #s_id').val(' ');
		$('.justadd_'+justadd_type+' #s_s').val(' ');
		
	}
	$('#datepicker_workout').val(' ');
	$('#justadd_time').val(' ');
	$('#w_id').val(' ');
	
	servingsize_curar_w = 0;
	servingsize_curarid = 0;
	sid_s = null;
	sid = null;
	fid = null;
	
	$('#tags').val('');
	$('#justadd_btn').button('reset');
	
	postSaveShowDeleteButtonUndo();
}

function calcbmi()
{
	bmi = ( weight_lb / ( height_in * height_in ) ) * 703;
}

function updateKcalGoal()
{
	if (gender == 1)
	{
		kcal_goal = 10 * (weight_lb * 0.45359237) + 6.25 * 2.54 * height_in - 5 * age + 5;
	} else {
		kcal_goal = 10 * (weight_lb * 0.45359237) + 6.25 * 2.54 * height_in - 5 * age - 161;
	}
}

function nikeplus_firsttime()
{
	$('#nikeplus_loginchecking').fadeOut();
	
	$('#nikeplus_loginchecking').removeClass("btn-danger btn-success");
	$('#nikeplus_loginchecking').html("Checking");
	$('#nikeplus_loginchecking').fadeIn();
	
	$.ajax({
		url: "/nike/jsonchecklogin",
		dataType: "json",
		data: {
			username: $('#nikeplus_username').val(),
			password: $('#nikeplus_password').val()
		},
		success: function( data ) {
			$('#nikeplus_loginchecking').fadeOut();
			if (data.result == "1")
			{
				$('#nikeplus_loginchecking').removeClass("btn-danger");
				$('#nikeplus_loginchecking').addClass("btn-success");
				$('#nikeplus_loginchecking').html("Success!");
				$('#nikeplus_loginchecking').fadeIn();
				
				$('#nikeplus_step2').fadeIn();
				
				nikeplus_firsttime_dosync();
				
			} else {
				$('#nikeplus_loginchecking').removeClass("btn-success");
				$('#nikeplus_loginchecking').addClass("btn-danger");
				$('#nikeplus_loginchecking').html("Failed! Try Again");
				$('#nikeplus_loginchecking').fadeIn();
			}
		}
	});
}

function nikeplus_firsttime_dosync()
{
	$.ajax({
		url: "/nike/jsondologin",
		dataType: "json",
		data: {
			username: $('#nikeplus_username').val(),
			password: $('#nikeplus_password').val()
		},
		success: function( data ) {
			window.location.reload();
		}
	});
}


function updateCalTracker(date)
{
	$.ajax({
		url: "/stats/cal",
		dataType: "json",
		data: {
			date: date,
		},
		success: function( data ) {
			if (data.result != "false")
			{
				var barproc = data.stats.t / kcal_goal;
				if (barproc <= 0)
					barproc = 0;
					
				$('#caloriebar').css("width", barproc * 100 +'%');
				$('#caloriebar_big').css("width", barproc * 100 +'%');
				
				$('#dashboard_nutrition_cal').html(""+Math.round(data.stats.n));
				$('#dashboard_workout_cal').html(""+Math.round(data.stats.w));
				$('#dashboard_net_cal').html(""+Math.round(data.stats.t));
				if (data.stats.w_c > 1)
					var plural_1 = "s";
				else 
					var plural_1 = "";

				if (data.stats.n_c > 1)
					var plural_2 = "s";
				else 
					var plural_2 = "";
				
				$('#dashboard_workout_cal_c').html(data.stats.w_c + " workout" + plural_1);
				$('#dashboard_nutrition_cal_c').html(data.stats.n_c + " food" + plural_2);
				
			} else {
				
			}
		}
	});
	
	showhistory();
	
}

function setTDate()
{
	var mydate= new Date();
	var theyear=mydate.getFullYear()-2000;
	var themonth=mydate.getMonth()+1;
	var thetoday=mydate.getDate();
	
	if (themonth < 10)
		themonth = "0" + themonth;

	if (thetoday < 10)
		thetoday = "0" + thetoday;
	
	date = themonth+"/"+thetoday+"/"+theyear;
}

function toggleJustDeleteSubmit()
{
	if (justadd_type == "workout")
	{

		$.ajax({
			url: "/workouts/delete",
			dataType: "json",
			data: {
				w_id: $('#w_id').val()
			},
			success: function( data ) {
				updateCalTracker(date);

				if (data.result != "false")
				{
					clearAlertTag();
				} else {
					
				}
			}
		});

	} else {
			$.ajax({
				url: "/nutritions/delete",
				dataType: "json",
				data: {
					nid: $('#nid').val()
				},
				success: function( data ) {
					updateCalTracker(date);
					
					if (data.result != "false")
					{
						clearAlertTag()
					} else {
						
					}
				}
			});
			
	}
	
	if ($('#nid').val() != null)
	{
		var nrid = "n_" + $('#nid').val();
		$('#'+nrid).remove();
		
	}	
	
	updateCalTracker(date);
}

function toggleJustAddSubmit()
{
	$('#justadd_time').attr("disabled", true);
	$('#datepicker_workout').attr("disabled", true);
	
	if (justadd_type == "workout")
	{
		
		$.ajax({
			url: "/workouts/save",
			dataType: "json",
			data: {
				pa_id: $('.justadd_'+justadd_type+' #pa_id').val(),
				pa_mets: $('.justadd_'+justadd_type+' #pa_mets').val(),
				pa_kcal: $('.justadd_'+justadd_type+' #pa_kcal').val(),
				pa_date: $('#datepicker_workout').val(),
				pa_mins: $('#justadd_time').val(),
				w_id: $('#w_id').val()
			},
			success: function( data ) {
				updateCalTracker(date);
				
				if (data.result != "false")
				{
					$('#w_id').val(data.w_id);
				
					$('#justadd_time').attr("disabled", false);
					$('#datepicker_workout').attr("disabled", false);
				} else {
				
				}
				
				if (data.story)
				{
					var storyid = data.story;
				
					$.ajax({
						url: "/workouts/facebookpost",
						dataType: "json",
						data: {
							story_id: storyid
						},
						success: function( data2 ) {
						
						}
					});
				
				}
				$('#justadd_btn').button('reset');
				
				postSaveShowDeleteButton();
			}
		});
	
	} else {
		$.ajax({
			url: "/nutritions/save",
			dataType: "json",
			data: {
				fid: $('.justadd_'+justadd_type+' #f_id').val(),
				sid: $('.justadd_'+justadd_type+' #s_id').val(),
				n_kcal: $('.justadd_'+justadd_type+' #n_kcal').val(),
				s_s: $('.justadd_'+justadd_type+' #s_s').val(),
				n_date: $('#datepicker_workout').val(),
				nid: $('#nid').val()
			},
			success: function( data ) {
				updateCalTracker(date);
				
				if (data.result != "false")
				{
					$('#nid').val(data.nid);
					
					$('#justadd_time').attr("disabled", false);
					$('#datepicker_workout').attr("disabled", false);
				} else {
					
				}
				
				$('#justadd_btn').button('reset');
				
				postSaveShowDeleteButton();
				
				if (data.story)
				{
					var storyid = data.story;
				
					$.ajax({
						url: "/nutritions/facebookpost",
						dataType: "json",
						data: {
							story_id: storyid
						},
						success: function( data2 ) {
						
						}
					});
				
				}
					
			}
		});
	
	}
	
	updateCalTracker(date);
}

// after save, show delete button
function postSaveShowDeleteButton()
{
	$('#justadd_btn').addClass('btn-success');
	$('#justadd_btn_del').fadeIn();
}

function postSaveShowDeleteButtonUndo()
{
	$('#justadd_btn').removeClass('btn-success');
	$('#justadd_btn_del').hide();
}

function showNutrPlank(nid, fid, s_val, s_txt)
{
	$.ajax({
		type: "GET",
		url: "/stats/nutr_info_plank",
		dataFormat: "",
		data: {
			'fid': fid,
			'servings': s_val,
			'serving_text': s_txt,
		},
		success: function(result) {
			
			$('#nd_'+nid).html(result);
			$('#nd_'+nid).fadeIn();
		}
	});
	
	
}

function doAutocompleteClick(type, item)
{
	clearAlertTag();

	thisitem = item;
	var newitem = "";
	var id = item.id;
	var text = decodeURIComponent(item.label);
	
	$('#food_servings').hide();
	$('#workout_mins').hide();
	$('#food_serving_size').hide();
	
	if (type == "workout")
	{
		mets = item.mets;
		$('#workout_mins').show();
	} else {
		// show food serving input and text
		$('#food_serving_size').show();
		$('#food_servings').show();
		
		fid = item.fid;
		
		if (item.servingsize == null)
		{
			// if no serving size data, hide the entry in the line
			$('#food_serving_size').hide();
			$('#food_servings').hide();
			
			$('.justadd_nutrition #text').css('padding-left',  "1%" );
			
			// set serving size count
			// todo
		} else {
			servingsize_curar = item.servingsize;
			if (servingsize_curar[servingsize_curarid].sname)
				$('.food_serving_default ').html(servingsize_curar[servingsize_curarid].sname);
			else 
				$('.food_serving_default ').html('');
				
			if (servingsize_curar[servingsize_curarid].sw)
				servingsize_curar_w = servingsize_curar[servingsize_curarid].sw;
			
			sid = servingsize_curar[servingsize_curarid].sid;
			
			// set the correct spacing for the serving size field and text offset
			// based on serving size tet description length
			
			var sslentmp = servingsize_curar[servingsize_curarid].sname.length;
			
			showPaddedFoodText(sslentmp);
			
		}
		
		// update calorie counter for this food
		kcal = item.kcal;
		if (servingsize_curar_w != 0)
			kcal = servingsize_curar_w/100 * kcal;

		showItemCals(Math.round(kcal));

		
	}
	
	justadd_type = type;
	
	$('.justadd_'+type+' #text').html(text);
	$('.justadd_'+type+' #pa_id').val(id);
	$('.justadd_'+type+' #pa_mets').val(mets);

	$('.justadd_'+type+' #f_id').val(fid);
	$('.justadd_'+type+' #n_kcal').val(kcal);
	$('.justadd_'+type+' #s_id').val(sid);
	$('.justadd_'+type+' #s_s').val(sid_s);
	
	// load elements if editing a previous record
	// nutrition
	if (item.sid)
		$('.justadd_nutrition #s_id').val(item.sid);
	if (item.s_s)
		$('#justadd_servingscount').val(item.s_s); 
	if (item.n_date)
		$('#datepicker_workout').val(item.n_date);
	else 
		$('#datepicker_workout').val(date);
	if (item.nid) {
		$('#nid').val(item.nid);
		postSaveShowDeleteButton();
	}
	
	//workout
	if (item.wid)
		$('#w_id').val(item.wid);
	if (item.pa_id)
		$('#pa_id').val(item.pa_id);
	if (item.pa_mets)
		$('#pa_mets').val(item.pa_mets);
	if (item.pa_kcal)
		$('#pa_kcal').val(item.pa_kcal);
	
	servings2cal($('#justadd_servingscount').val());

	// now actually show the built alert item
	$('#justadd_alerts').show();
	$('#justadd_alert').fadeIn();
	$('.justadd_'+type+'').fadeIn();
	
}

function showbadges()
{
	var badges_txt = '';
	var badges_count = 0;
	
	$.ajax({
		type: "GET",
		url: "/stats/badges",
		dataFormat: "json",
		data: {},
		success: function(result) {
			$.each(result['badges'], function(i) {
				var item = result['badges'][i];
				badges_txt += '<div class="badges-tile"><strong><img src="'+item['path']+'"></strong><small><center>'+item['desc_short']+'</center></small></div> ';
				badges_count++;
			});
			
			$('#badges-tab').html(badges_txt);
			$('#dashboard_badges_num').html(badges_count);
		}
	});
	
}

function showhistory()
{
	$.ajax({
		type: "GET",
		url: "/story/html",
		dataFormat: "",
		data: {},
		success: function(result) {
			if (result != null && result != "")
				$('#history').html(result);
		}
	});
	
}

function welcomebox_show(showid)
{
	console.log(showid);
	$('.featurebox').hide(); 
	$('#'+showid).show(); 

	$('#feat-overview li a').each(function(index) 
	{ 
		$(this).removeClass('selected'); 
	}); 

	$('#'+showid+"_menu").addClass('selected');
	
	if (showid == "welcome2iburnd") {
		$('.tri').css('left','5.3%');
	} else if 	(showid == "socialsharing") {
		$('.tri').css('left','22.5%');
	} else if 	(showid == "nutrition") {
		$('.tri').css('left','38%');
	} else if 	(showid == "workouts") {
		$('.tri').css('left','52%');
	} else if 	(showid == "rewards") {
		$('.tri').css('left','69%');
	} else if 	(showid == "sync") {
		$('.tri').css('left','87.9%');
	}

}

function doAutocompleteLogShow(type, id)
{
	var type_cat = "";
	if (type == 'nutrition')
	{
		type_cat = "food";
	} else if (type == 'workout')
	{
		
	}
	
	/*
	thisitem = 	{
		type: type_cat,
		fid: id,
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
	}; doAutocompleteClick( 'nutrition', thisitem); $('.nutritionaldetails').hide(); $('#nd_<?= $food->nid ?>').fadeToggle();
	*/
	
}

$(function () {
	setTDate();
	updateKcalGoal();
	updateCalTracker(date);
	
	showbadges();
	
	$('#stuck')
	    .mouseover(function(event) {
//	      $(event.target).height("50px");
			$('#stuck').height("50px");
		
	    })
	    .mouseout(function(event) {
			$('#stuck').height("50px");
	    })
	    .click(function(event) {
//	      $(event.target).toggleClass('outline-element-clicked');
	    });
	

	
	$("a[rel=dpopover]").popover();

	$( "#datepicker_workout" ).datepicker({ 
		dateFormat: 'mm/dd/y' 
	});

	$( "#datepicker_nutrition" ).datepicker({ 
		dateFormat: 'mm/dd/y' 
	});
	
	
	$('.btn-group').button();
	$('#mainlookuptype').button();
	//default to food
	$('#mainlookuptype #2').button('toggle');

    // Autocomplete

    $("#tags").autocomplete({
        source: function( request, response ) {
			$.ajax({
//				url: "/search/nutrition",
				url: "/search/"+mainsearchtype,
				dataType: "json",
				data: {
					q: request.term,
					type: "cal"
				//	type: "full"
				},
				success: function( data ) {
					itemstotall = data.items.length;
					var hnewsearch = 40+25*data.items.length;
					$('#stuck').height(""+hnewsearch+"px");
					$('#stuck').show(); 
					
					//$('.ui-autocomplete').show();
					
					a = data;
					if (mainsearchtype == "workout")
					{
						response( $.map( data.items, function( item ) {
							return {
								type: "workout",
								id: item.id,
								label: item.text,
								mets: item.mets
							}
						}));
					} else {
						response( $.map( data.items, function( item ) {
							if (item.nutrients == null)
								var kcal_tmp = 0;
							else 
								var kcal_tmp = item.nutrients[208];
								
							return {
								type: "food",
								fid: item.fid,
								label: item.name,
								servingsize: item.servingsize,
								kcal: kcal_tmp
							}
						}));
						
					} 
				}
			});
		},
		minLength: 3,
		open: function(e, ui) {
			$('#stuck').show();
			$('.ui-autocomplete').css('z-index','100000000000');
			$('.ui-autocomplete').css('display','block');
			$(".ui-autocomplete").show();
			
			$('.ui-autocomplete.ui-menu.ui-widget.ui-widget-content.ui-corner-all li')
			    .mouseover(function(event) {
					$('.ui-autocomplete').css('z-index','100000000000');
					$('.ui-autocomplete').css('display','block');
					
					$('#stuck-menuitem').removeClass('close');
					$('#stuck-menuitem').addClass('open');
					
					if (itemstotall != 0)
					{
						var hnewsearch = 40+25*itemstotall;
						$('#stuck').height(""+hnewsearch+"px");
						$('#stuck').show();
					}
			    })
			    .mouseout(function(event) {
					$('#stuck-menuitem').removeClass('open');
					itemstotall = 0;
				
					//$('.ui-autocomplete').css('display','none');
					//$('#stuck').height("50px");
					//$('#stuck').css('display','none');
					//$('#stuck').hide();
			    })
			    .click(function(event) {
					clearAlertTag(); 
					$('#tags').val('');
					$('#stuck').height("50px");
					$('#stuck-menuitem').removeClass('open');
			    });
			
		},
		'z-index': 1000000000000000,
		select: function( event, ui ) {
			ui.item.label = encodeURIComponent(ui.item.label);
			doAutocompleteClick( mainsearchtype, ui.item );
		}
    });

});