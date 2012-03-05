/**
 * Live preview script for MediaWiki
 */

window.doLivePreview = function( e ) {
	e.preventDefault();

	$( mw ).trigger( 'LivePreviewPrepare' );
	
	var postData = $('#editform').formToArray();
	postData.push( { 'name' : 'wpPreview', 'value' : '1' } );
	
	// Hide active diff, used templates, old preview if shown
	var copyElements = ['#wikiPreview', '.templatesUsed', '.hiddencats',
						'#catlinks'];
	var copySelector = copyElements.join(',');

	$.each( copyElements, function(k,v) { $(v).fadeOut('fast'); } );
	
	// Display a loading graphic
	var loadSpinner = $('<div class="mw-ajax-loader"/>');
	$('#wikiPreview').before( loadSpinner );
	
	var page = $('<div/>');
	var target = $('#editform').attr('action');
	
	if ( !target ) {
		target = window.location.href;
	}
	
	page.load( target + ' ' + copySelector, postData,
		function() {
			
			for( var i=0; i<copyElements.length; ++i) {
				// For all the specified elements, find the elements in the loaded page
				//  and the real page, empty the element in the real page, and fill it
				//  with the content of the loaded page
				var copyContent = page.find( copyElements[i] ).contents();
				$(copyElements[i]).empty().append( copyContent );
				var newClasses = page.find( copyElements[i] ).attr('class');
				$(copyElements[i]).attr( 'class', newClasses );
			}
			
			$.each( copyElements, function(k,v) {
				// Don't belligerently show elements that are supposed to be hidden
				$(v).fadeIn( 'fast', function() { $(this).css('display', ''); } );
			} );
			
			loadSpinner.remove();

			$( mw ).trigger( 'LivePreviewDone', [copyElements] );
		} );
}

// Shamelessly stolen from the jQuery form plugin, which is licensed under the GPL.
// http://jquery.malsup.com/form/#download
$.fn.formToArray = function() {
	var a = [];
	if (this.length == 0) return a;

	var form = this[0];
	var els = form.elements;
	if (!els) return a;
	for(var i=0, max=els.length; i < max; i++) {
		var el = els[i];
		var n = el.name;
		if (!n) continue;

		var v = $.fieldValue(el, true);
		if (v && v.constructor == Array) {
			for(var j=0, jmax=v.length; j < jmax; j++)
				a.push({name: n, value: v[j]});
		}
		else if (v !== null && typeof v != 'undefined')
			a.push({name: n, value: v});
	}

	if (form.clk) {
		// input type=='image' are not found in elements array! handle it here
		var $input = $(form.clk), input = $input[0], n = input.name;
		if (n && !input.disabled && input.type == 'image') {
			a.push({name: n, value: $input.val()});
			a.push({name: n+'.x', value: form.clk_x}, {name: n+'.y', value: form.clk_y});
		}
	}
	return a;
};

/**
 * Returns the value of the field element.
 */
$.fieldValue = function(el, successful) {
	var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
	if (typeof successful == 'undefined') successful = true;

	if (successful && (!n || el.disabled || t == 'reset' || t == 'button' ||
		(t == 'checkbox' || t == 'radio') && !el.checked ||
		(t == 'submit' || t == 'image') && el.form && el.form.clk != el ||
		tag == 'select' && el.selectedIndex == -1))
			return null;

	if (tag == 'select') {
		var index = el.selectedIndex;
		if (index < 0) return null;
		var a = [], ops = el.options;
		var one = (t == 'select-one');
		var max = (one ? index+1 : ops.length);
		for(var i=(one ? index : 0); i < max; i++) {
			var op = ops[i];
			if (op.selected) {
				var v = op.value;
				if (!v) // extra pain for IE...
					v = (op.attributes && op.attributes['value'] &&
						!(op.attributes['value'].specified))
							? op.text : op.value;
				if (one) return v;
				a.push(v);
			}
		}
		return a;
	}
	return el.value;
};

$(document).ready( function() {
	$('#wpPreview').click( doLivePreview );
} );
