/**
 * Javascript for the login form.
 */

$(function() {
	var username = $("#username"), password = $("#password"), allFields = $([])
			.add(name).add(password), tips = $(".validateTips");

	$("#login_form").dialog({
		autoOpen : false,
		height : 450,
		width : 350,
		modal : true,
		buttons : {
			"Login" : function() {
				var bValid = true;
				allFields.removeClass("ui-state-error");

				bValid = bValid && checkLength(username, "username", 3, 16, tips);
				bValid = bValid && checkLength(password, "password", 3, 16, tips);
				if (bValid) {
					$("#submit_login").submit();
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

	$("#login_button").click(function() {
		$("#login_form").dialog("open");
	});

	

});