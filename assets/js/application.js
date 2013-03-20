function getClubName(id){
	var clubname = "";
	if (id != "") {
		$.post("/manager/clubs/getclubname", { "id" : id },  
		  function(result) {
			var j = $.parseJSON(result);
			clubname = j.result;
			return clubname;
		});
	}
}

function saveModifyOfficerRel(id, officer_id){
	console.log(id);
	var a = $(id).parent().parent().parent();
	idnum = a.attr('id');
	console.log(idnum);
//	idnum = a.attr('id','99');
	
	var position = $('#of_rel_position_'+idnum+'').val();
	var ay = $('#of_rel_ay_'+idnum+'').val();
	var retired = $('#of_rel_retired_'+idnum+'').val();
	var club_id = $('#of_rel_club_'+idnum+'').val();
	if (retired == "1")
	{
		var retired_txt = "Retired";
	} else {
		var retired_txt = "Active";
	}
	
	if (idnum == "X")
	{
		
	}
	
	$('.of_rel_view.position.'+idnum+'').html(position);
	$('.of_rel_view.ay.'+idnum+'').html(ay);
	$('.of_rel_view.retired.'+idnum+'').html(retired_txt);
	var clubname = "";
	$.post("/manager/clubs/getclubname", { "id" : club_id },  
	  function(result) {
		var j = $.parseJSON(result);
		clubname = j.result;
		$('.of_rel_view.club.'+idnum+'').html(clubname);
	});
	
	if (idnum == "X")
	{
		
	}
	
	var dataString = 'position='+ encodeURIComponent(position);
	dataString += '&ay=' + encodeURIComponent(ay);  
	dataString += '&retired='+ encodeURIComponent(retired);  
	dataString += '&id='+ encodeURIComponent(idnum);
	dataString += '&club_id='+ encodeURIComponent(club_id);
	dataString += '&officer_id='+ encodeURIComponent(officer_id);
	
	var doaction = "reledit";
	if (idnum == "X")
	{
		doaction = "reladd";
	}
	
	$.ajax({  
	  type: "POST",  
	  url: "/manager/officers/"+doaction,  
	  data: dataString,  
	  success: function(result) {
		if (result != "0") {
			toggleModifyOfficerRel(null, idnum);
		}
	  }  
	});
}

function deleteOfficerRel(id){
	var dataString = 'id='+ encodeURIComponent(id);
	
	$.ajax({  
	  type: "POST",  
	  url: "/manager/officers/reldel",  
	  data: dataString,  
	  success: function(result) {
		if (result == "1") {
			$('#'+id+'').fadeOut();
		}
	  }  
	});
}

function addOfficerRel(){
	var i = "X";
	var r = $("table tr:last").clone();
	idrem = r.attr('id');
	r.attr('id',"X");
	
	r.find("div").each(function() {
		$(this).removeClass(idrem);
		$(this).addClass("X");
	  });
	
	r.find("tr").each(function() {
		$(this).removeClass(idrem);
		$(this).addClass("X");
	  });

	r.find("select").each(function() {
		$(this).removeClass(idrem);
		$(this).addClass("X");
	  });

	r.appendTo("table");
}

function toggleModifyOfficerRel(id, idnum){
	if (id != null)
	{
		var a = $(id).parent().parent().parent();
		idnum = a.attr('id');
	}

//	idnum = a.attr('id','99');

	$('.of_rel_view.'+idnum+'').toggle();
	$('.of_rel_edit.'+idnum+'').toggle();
	
	if ($('a#of_rel_'+idnum+'.btn.primary').html() != "Cancel")
	{
		$('a#of_rel_'+idnum+'.btn.primary').html('Cancel');
	} else {
		$('a#of_rel_'+idnum+'.btn.primary').html('Modify');
	}
	
}

function check_fieldset(fieldname, triggererror) {
	var fieldtype = "input";
	
	if (fieldname == "clubmission") {
		fieldtype = "textarea";
	} else if (fieldname == "advisor_id" || fieldname == "clubcategory" ||  fieldname == "clubschool" ||  fieldname == "year" ||  fieldname == "school") {
		fieldtype = "select";
	} else {
		fieldtype = "input";
	}
	
	var fieldval = $(""+fieldtype+"#"+fieldname+"").val();
	
	if (triggererror == true) {
		if (fieldval == "") {
			$(""+fieldtype+"#"+fieldname+"").parent().parent().parent().addClass("error");
			$("span#"+fieldname+"_errortxt").show();
			var error = 1;
		} else {
			$("span#"+fieldname+"_errortxt").hide();
			$(""+fieldtype+"#"+fieldname+"").parent().parent().parent().removeClass("error");
			var error = 0;
		}
	} else {
		var error = 0;		
	} 
	
	return [error, fieldval];
	
}

function submit_clubedit() {
	var haserrors = 0;
	var clubid = $("input#clubid").val();
	var clubidcompare = $("input#clubidcompare").val();
	var actiontype = $("input#actiontype").val();
	
	var clubname = check_fieldset("clubname", true);
	haserrors = haserrors + clubname[0];
	var clubname_val = clubname[1];
	
	var clubalias = check_fieldset("clubalias", true);
	haserrors = haserrors + clubalias[0];
	var clubalias_val = clubalias[1];

	var cluburl = check_fieldset("cluburl", true);
	haserrors += cluburl[0];
	var cluburl_val = cluburl[1];

	var clubcategory = check_fieldset("clubcategory", true);
	haserrors += clubcategory[0];
	var clubcategory_val = clubcategory[1];

	var clubschool = check_fieldset("clubschool", true);
	haserrors += clubschool[0];
	var clubschool_val = clubschool[1];

	var clubaddress = check_fieldset("clubaddress", true);
	haserrors += clubaddress[0];
	var clubaddress_val = clubaddress[1];

	var clubrep = check_fieldset("clubrep", true);
	haserrors += clubrep[0];
	var clubrep_val = clubrep[1];

	var clubmission = check_fieldset("clubmission", true);
	haserrors += clubmission[0];
	var clubmission_val = clubmission[1];

	var advisor_id = check_fieldset("advisor_id", true);
	haserrors += advisor_id[0];
	var advisor_id_val = advisor_id[1];

	if (haserrors == 0)
	{
		var dataString = 'clubname='+ encodeURIComponent(clubname_val) + '&clubalias=' + encodeURIComponent(clubalias_val) + '&cluburl=' + encodeURIComponent(cluburl_val);  
		dataString += '&clubcategory='+ encodeURIComponent(clubcategory_val) + '&clubaddress=' + encodeURIComponent(clubaddress_val) + '&clubrep=' + encodeURIComponent(clubrep_val);  
		dataString += '&clubmission='+ encodeURIComponent(clubmission_val) + '&advisor_id=' + encodeURIComponent(advisor_id_val);
		dataString += '&clubid=' + encodeURIComponent(clubid);  
		dataString += '&clubidcompare=' + encodeURIComponent(clubidcompare);  
		dataString += '&actiontype='+ encodeURIComponent(actiontype);  
		dataString += '&clubschool='+ encodeURIComponent(clubschool_val);  
		
		$.ajax({  
		  type: "POST",  
		  url: "/manager/clubs/"+actiontype+"",  
		  data: dataString,  
		  success: function(result) {
			if (result == "1") {
				if (actiontype == "edit") {
					window.location.reload();
				} else { 
					eval( window.location = ""+window.location.origin+"/manager/view/clubs" );
				}	
			}
		  }  
		});
	}
}

function submit_clubdelete() {
	var clubid = $("input#clubid").val();
	var clubidcompare = $("input#clubidcompare").val();

	var dataString = '&clubid=' + encodeURIComponent(clubid);  
	dataString += '&clubidcompare=' + encodeURIComponent(clubidcompare);  
		
	$.ajax({  
	  type: "POST",  
	  url: "/manager/clubs/delete",  
	  data: dataString,  
	  success: function(result) {
		if (result == "1") {
			eval( window.location = ""+window.location.origin+"/manager/view/clubs" );
		}
	  }  
	});
	
}


function submit_officeredit() {
	var haserrors = 0;
	var netidh = $("input#netidh").val();
	var netidcompare = $("input#netidcompare").val();
	var actiontype = $("input#actiontype").val();

	var netid = check_fieldset("netid", true);
	haserrors = haserrors + netid[0];
	var netid_val = netid[1];
	
	var prefix = check_fieldset("prefix", false);
	var prefix_val = prefix[1];
	
	var firstname = check_fieldset("firstname", true);
	haserrors = haserrors + firstname[0];
	var firstname_val = firstname[1];

	var middlename = check_fieldset("middlename", false);
	var middlename_val = middlename[1];

	var lastname = check_fieldset("lastname", true);
	haserrors += lastname[0];
	var lastname_val = lastname[1];

	var suffix = check_fieldset("suffix", false);
	var suffix_val = suffix[1];

	var email = check_fieldset("email", true);
	haserrors += email[0];
	var email_val = email[1];

	var year = check_fieldset("year", false);
	var year_val = year[1];

	var school = check_fieldset("school", false);
	var school_val = school[1];

	if (haserrors == 0)
	{
		var dataString = '&netidh=' + encodeURIComponent(netidh);  
		dataString += '&actiontype=' + encodeURIComponent(actiontype);  
		dataString += '&netidcompare=' + encodeURIComponent(netidcompare);  
		dataString += '&netid=' + encodeURIComponent(netid_val);  
		dataString += '&prefix=' + encodeURIComponent(prefix_val);  
		dataString += '&firstname=' + encodeURIComponent(firstname_val);  
		dataString += '&middlename=' + encodeURIComponent(middlename_val);  
		dataString += '&lastname=' + encodeURIComponent(lastname_val);  
		dataString += '&suffix=' + encodeURIComponent(suffix_val);  
		dataString += '&email=' + encodeURIComponent(email_val);  
		dataString += '&year='+ encodeURIComponent(year_val);  
		dataString += '&school='+ encodeURIComponent(school_val);  
		
		$.ajax({  
		  type: "POST",  
		  url: "/manager/officers/"+actiontype+"",  
		  data: dataString,  
		  success: function(result) {
			if (result == "1") {
				if (actiontype == "edit") {
					window.location.reload();
				} else { 
					eval( window.location = ""+window.location.origin+"/manager/view/officers" );
				}	
			}
		  }  
		});
	}
}

function submit_officerdelete() {
	var netidh = $("input#netidh").val();
	var netidcompare = $("input#netidcompare").val();

	var dataString = '&netidh=' + encodeURIComponent(netidh);  
	dataString += '&netidcompare=' + encodeURIComponent(netidcompare);  
		
	$.ajax({  
	  type: "POST",  
	  url: "/manager/officers/delete",  
	  data: dataString,  
	  success: function(result) {
		if (result == "1") {
			eval( window.location = ""+window.location.origin+"/manager/view/officers" );
		}
	  }  
	});
	
}


$(function(){

  // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
  // IT'S ALL JUST JUNK FOR OUR DOCS!
  // ++++++++++++++++++++++++++++++++++++++++++


  // Hide the Mobile Safari address bar once loaded
  // ==============================================

  // Set a timeout...
  // setTimeout(function(){
  //   // Hide the address bar!
  //   window.scrollTo(0, 1);
  // }, 0);


  // table sort example
  // ==================

  // make code pretty
  window.prettyPrint && prettyPrint()

  // table sort example
  if ($.fn.tableSorter) {
    $("#sortTableExample").tablesorter( { sortList: [[ 1, 0 ]] } )
  }

  // add on logic
  $('.add-on :checkbox').on('click', function () {
    var $this = $(this)
      , method = $this.attr('checked') ? 'addClass' : 'removeClass'
    $(this).parents('.add-on')[method]('active')
  })

  // Disable certain links in docs
  // Please do not carry these styles over to your projects
  // it's merely here to prevent button clicks form taking you
  // away from your spot on page!!

  $('[href^=#]').click(function (e) {
    e.preventDefault()
  })

  // Copy code blocks in docs
  $(".copy-code").on('focus', function () {
    var el = this
    setTimeout(function () { $(el).select() }, 0)
  })

  if ($.fn.tooltip) {

    // position static twipsies for components page
    if ($(".twipsies a").length) {
      $(window).on('load resize', function () {
        $(".twipsies a").each(function () {
          $(this)
            .tooltip({
              placement: $(this).attr('title')
            , trigger: 'manual'
            })
            .tooltip('show')
          })
      })
    }

    // add tipsies to grid for scaffolding
    if ($('#grid-system').length) {

      $('#grid-system').tooltip({
          selector: '.show-grid > div'
        , title: function () { return $(this).width() + 'px' }
      })

    }
  }

  // javascript build logic

  var inputs = $("#javascript input")

  // toggle all plugin checkboxes
  $('#selectAll').on('click', function (e) {
    e.preventDefault()
    inputs.attr('checked', !inputs.is(':checked'))
  })

  // handle build button dropdown
  var buildTypes = $('#javascriptBuilder .dropdown-menu li').on('click', function () {
    buildTypes.removeClass('active')
    $(this).addClass('active')
  })

  // request built javascript
  $('#javascriptBuild').on('click', function () {

    var names = $("#javascript input:checked")
      .map(function () { return this.value })
      .toArray()

    if (names[names.length - 1] == 'bootstrap-transition.js') {
      names.unshift(names.pop())
    }

    $.ajax({
      type: 'POST'
    , dataType: 'jsonpi'
    , params: {
        branch: '2.0-wip'
      , dir: 'js'
      , filenames: names
      , compress: buildTypes.first().hasClass('active')
      }
    , url: "http://bootstrap.herokuapp.com"
    })
  })

})


// Modified from the original jsonpi https://github.com/benvinegar/jquery-jsonpi
// by the talented Ben Vinegar
!function($) {
    $.ajaxTransport('jsonpi', function(opts, originalOptions, jqXHR) {
        var url = opts.url;

        return {
            send: function(_, completeCallback) {
                var name = 'jQuery_iframe_' + jQuery.now(),
                    iframe, form;

                iframe = $('<iframe>')
                    .attr('name', name)
                    .appendTo('head');

                form = $('<form>')
                    .attr('method', opts.type) // GET or POST
                    .attr('action', url)
                    .attr('target', name);

                $.each(opts.params, function(k, v) {
                    $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', k)
                        .attr('value', v)
                        .appendTo(form);
                });

                form.appendTo('body').submit();
            }
       };
    });
}(jQuery);