/*
 * Main javascript file. 
 */

/**
 * Update the tips of a form.
 * 
 * @param t
 *            tips container
 * @param tips
 *            the tips to display.
 */
function updateTips(t, tips) {
	tips.text(t).addClass("ui-state-highlight");
	setTimeout(function() {
		tips.removeClass("ui-state-highlight", 1500);
	}, 500);
}

/**
 * Count the words in a chunk of text. Update the tips with a message if error.
 * 
 * @param target
 *            the container of the text
 * @param name
 *            label of container
 * @param min
 *            minimum length of text
 * @param max
 *            maximum length of text
 * @returns {Boolean} true if the length of the text is between min and max,
 *          false otherwise
 */
function checkWordsCount(target, name, min, max, tips) {
	var text = target.val().trim();
	var highlight = false;
	if (target.val().trim() == "") {
		highlight = true;
	} else {
		var count = text.split(' ').length;
		if (count < min || count > max) {
			highlight = true;
		}
	}
	if (highlight) {
		target.addClass("ui-state-error");
		updateTips("Length of " + name + " must be between " + min + " and "
				+ max + ".", tips);
		return false;
	} else {
		return true;
	}
}

/**
 * Check the length of characters of some text.
 * 
 * @param o
 * @param n
 * @param min
 * @param max
 * @returns {Boolean}
 */
function checkLength(o, n, min, max, tips) {
	if (o.val().length > max || o.val().length < min) {
		o.addClass("ui-state-error");
		updateTips("Length of " + n + " must be between " + min + " and " + max
				+ ".", tips);
		return false;
	} else {
		return true;
	}
}

/**
 * Check if a file container has a file selected. Update tips otherwise.
 * 
 * @param o
 *            the container
 * @returns {Boolean}
 */
function checkImage(o, tips) {
	if (o.val() == "") {
		o.addClass("ui-state-error");
		updateTips("You must select a cover image for the article.", tips);
		return false;
	} else {
		return true;
	}
}

/**
 * Check if a multi select has a value. Used for the writers in the write and
 * edit forms.
 * 
 * @param o
 * @returns {Boolean}
 */
function checkWriters(o, tips) {
	if (o.val() == null) {
		o.addClass("ui-state-error");
		updateTips("You must select at least one writer for the article.", tips);
		return false;
	} else {
		return true;
	}
}

/**
 * Print an error message on the screen using jQuery. Fades out after some time.
 * 
 * @param message
 *            the text to display
 */
function alert_error_message(message) {
	$('#messages')
			.append(
					'<div id="error-widget" style="display:none">\
		<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">\
			<p>\
				<span class="ui-icon ui-icon-alert"\
					style="float: left; margin-right: .3em;"></span> <strong>Alert:</strong>\
				'
							+ message + '.\
			</p>\
		</div>\
	</div>');
	$('#error-widget').show().delay(3000).fadeOut();
}

/**
 * Print a success/info message on the screen using jQuery. Fades out after some
 * time.
 * 
 * @param message
 *            the text of the message
 */
function alert_success_message(message) {
	$('#messages')
			.append(
					'<div id="error-widget" style="display:none">\
		<div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">\
			<p>\
				<span class="ui-icon ui-icon-info"\
					style="float: left; margin-right: .3em;"></span> <strong>Alert:</strong>\
				'
							+ message + '.\
			</p>\
		</div>\
	</div>');
	$('#error-widget').show().delay(3000).fadeOut();
}