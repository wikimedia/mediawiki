/**
 * Live preview script for MediaWiki
 */
(function( $ ) {
	window.doLivePreview = function( e ) {
		var previewShowing = false;

		e.preventDefault();

		$( mw ).trigger( 'LivePreviewPrepare' );

		var $wikiPreview = $( '#wikiPreview' );

		$( '#mw-content-text' ).css( 'position', 'relative' );

		if ( $wikiPreview.is( ':visible' ) ) {
			previewShowing = true;
		}

		// show #wikiPreview if it's hidden (if it is hidden, it's also empty, so nothing changes in the rendering)
		// to be able to scroll to it
		$wikiPreview.show();

		// Jump to where the preview will appear
		$wikiPreview[0].scrollIntoView();

		var postData = $( '#editform' ).formToArray();
		postData.push( { 'name' : 'wpPreview', 'value' : '1' } );

		// Hide active diff, used templates, old preview if shown
		var copyElements = ['#wikiPreview', '.templatesUsed', '.hiddencats',
							'#catlinks'];
		var copySelector = copyElements.join(',');

		$.each( copyElements, function( k, v ) {
			$( v ).fadeTo( 'fast', 0.4 );
		} );

		// Display a loading graphic
		var loadSpinner = $( '<div class="mw-ajax-loader"/>' );
		// Move away from header (default is -16px)
		loadSpinner.css( 'top', '0' );

		// If the preview is already showing, overlay the spinner on top of it.
		if ( previewShowing ) {
			loadSpinner
				.css( 'position', 'absolute' )
				.css( 'z-index', '3' )
				.css( 'left', '50%' )
				.css( 'margin-left', '-16px' );
		}
		$wikiPreview.before( loadSpinner );

		var page = $( '<div/>' );
		var target = $( '#editform' ).attr( 'action' );

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
					var newClasses = page.find( copyElements[i] ).prop('class');
					$(copyElements[i]).prop( 'class', newClasses );
				}

				$.each( copyElements, function( k, v ) {
					// Don't belligerently show elements that are supposed to be hidden
					$( v ).fadeTo( 'fast', 1, function() {
						$( this ).css( 'display', '' );
					} );
				} );

				loadSpinner.remove();

				$( mw ).trigger( 'LivePreviewDone', [copyElements] );
			} );
	};

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
}) ( jQuery );
