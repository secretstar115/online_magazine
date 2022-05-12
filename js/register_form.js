/*
 * JS for the edit form. 
 */

$(function() {
	var username = $("#username"), password = $("#password"), allFields = $([])
			.add(name).add(password), tips = $(".validateTips");

	var register_d = $("#register_form").dialog({
		autoOpen : false,
		height : 450,
		width : 350,
		modal : true,
		buttons : {
			"Create" : function() {
				var bValid = true;
				allFields.removeClass("ui-state-error");

				bValid = bValid && checkLength(username, "username", 3, 16, tips);
				bValid = bValid && checkLength(password, "password", 3, 16, tips);
				if (bValid) {
					dialog2.dialog('open');
				}
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		},
		close : function() {
			allFields.val("").removeClass("ui-state-error");
		}
	});

	$("#register").click(function() {
		$("#register_form").dialog("open");
	});

	var dialog2 = $("#dialog").dialog({
		autoOpen : false,
		modal : true,
		stack : true,
		title : 'Submit Confirmation',
		close : function() {
		},
		buttons : {
			"Create account" : function() {
				$("#submit_account").submit();
				register_d.dialog("close");
				$(this).dialog("close");
			},
			Cancel : function() {
				$(this).dialog("close");
			}
		}
	});

});