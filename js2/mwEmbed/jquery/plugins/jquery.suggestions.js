/**
 * This plugin provides a generic way to add suggestions to a text box
 * Usage:
 *
 * Set options
 *     $('#textbox').suggestions({ option1: value1, option2: value2 });
 *     $('#textbox').suggestions( option, value );
 * Get option:
 *     value = $('#textbox').suggestions( option );
 * Initialize:
 *     $('#textbox').suggestions();
 * 
 * Available options:
 * animationDuration: How long (in ms) the animated growing of the results box
 *     should take (default: 200)
 * cancelPending(): Function called when any pending asynchronous suggestions
 *     fetches should be canceled (optional). Executed in the context of the
 *     textbox
 * delay: Number of ms to wait for the user to stop typing (default: 120)
 * fetch(query): Callback that should fetch suggestions and set the suggestions
 *     property (required). Executed in the context of the textbox
 * maxGrowFactor: Maximum width of the suggestions box as a factor of the width
 *     of the textbox (default: 2)
 * maxRows: Maximum number of suggestion rows to show
 * submitOnClick: If true, submit the form when a suggestion is clicked
 *     (default: false)
 * suggestions: Array of suggestions to display (default: [])
 * 
 */
(function($) {
$.fn.suggestions = function( param, param2 ) {
	/**
	 * Handle special keypresses (arrow keys and escape)
	 * @param key Key code
	 */
	function processKey( key ) {
		switch ( key ) {
			case 40:
				// Arrow down
				if ( conf._data.div.is( ':visible' ) ) {
					highlightResult( 'next', true );
				} else {
					// Load suggestions right now
					updateSuggestions( false );
				}
			break;
			case 38:
				// Arrow up
				if ( conf._data.div.is( ':visible' ) ) {
					highlightResult( 'prev', true );
				}
			break;
			case 27:
				// Escape
				conf._data.div.hide();
				restoreText();
				cancelPendingSuggestions();
			break;
			default:
				updateSuggestions( true );
		}
	}
	
	/**
	 * Restore the text the user originally typed in the textbox,
	 * before it was overwritten by highlightResult(). This restores the
	 * value the currently displayed suggestions are based on, rather than
	 * the value just before highlightResult() overwrote it; the former
	 * is arguably slightly more sensible.
	 */
	function restoreText() {
		conf._data.textbox.val( conf._data.prevText );
	}
	
	/**
	 * Ask the user-specified callback for new suggestions. Any previous
	 * delayed call to this function still pending will be canceled.
	 * If the value in the textbox hasn't changed since the last time
	 * suggestions were fetched, this function does nothing.
	 * @param delayed If true, delay this by the user-specified delay
	 */
	function updateSuggestions( delayed ) {
		// Cancel previous call
		if ( conf._data.timerID != null )
			clearTimeout( conf._data.timerID );
		if ( delayed )
			setTimeout( doUpdateSuggestions, conf.delay );
		else
			doUpdateSuggestions();
	}
	
	/**
	 * Delayed part of updateSuggestions()
	 * Don't call this, use updateSuggestions( false ) instead
	 */
	function doUpdateSuggestions() {
		if ( conf._data.textbox.val() == conf._data.prevText )
			// Value in textbox didn't change
			return;
		
		conf._data.prevText = conf._data.textbox.val();
		conf.fetch.call ( conf._data.textbox,
			conf._data.textbox.val() );
	}
	
	/**
	 * Called when the user changes the suggestions post-init.
	 * Typically happens asynchronously from conf.fetch()
	 */
	function suggestionsChanged() {
		conf._data.div.show();
		updateSuggestionsTable();
		fitContainer();
		trimResultText();
	}
	
	/**
	 * Cancel any delayed updateSuggestions() call and inform the user so
	 * they can cancel their result fetching if they use AJAX or something 
	 */
	function cancelPendingSuggestions() {
		if ( conf._data.timerID != null )
			clearTimeout( conf._data.timerID );
		conf.cancelPending.call( this );
	}
	
	/**
	 * Rebuild the suggestions table
	 */
	function updateSuggestionsTable() {
		// If there are no suggestions, hide the div
		if ( conf.suggestions.length == 0 ) {
			conf._data.div.hide();
			return;
		}
		
		var table = conf._data.div.children( 'table' );
		table.empty();
		for ( var i = 0; i < conf.suggestions.length; i++ ) {
			var td = $( '<td />' ) // FIXME: why use a span?
				.append( $( '<span />' ).text( conf.suggestions[i] ) );
				//.addClass( 'os-suggest-result' ); //FIXME: use descendant selector
			$( '<tr />' )
				.addClass( 'os-suggest-result' ) // FIXME: use descendant selector
				.attr( 'rel', i )
				.data( 'text', conf.suggestions[i] )
				.append( td )
				.appendTo( table );
		}
	}
	
	/**
	 * Make the container fit into the screen
	 */
	function fitContainer() {
		if ( conf._data.div.is( ':hidden' ) )
			return;
		
		// FIXME: Mysterious -20 from mwsuggest.js,
		// presumably to make room for a scrollbar
		var availableHeight = $( 'body' ).height() - (
			Math.round( conf._data.div.offset().top ) -
			$( document ).scrollTop() ) - 20;
		var rowHeight = conf._data.div.find( 'tr' ).outerHeight();
		var numRows = Math.floor( availableHeight / rowHeight );
		
		// Show at least 2 rows if there are multiple results
		if ( numRows < 2 && conf.suggestions.length >= 2 )
			numRows = 2;
		if ( numRows > conf.maxRows )
			numRows = conf.maxRows;
		
		var tableHeight = conf._data.div.find( 'table' ).outerHeight();
		if ( numRows * rowHeight < tableHeight ) {
			// The container is too small
			conf._data.div.height( numRows * rowHeight );
			conf._data.visibleResults = numRows;
		} else {
			// The container is possibly too large
			conf._data.div.height( tableHeight );
			conf._data.visibleResults = conf.suggestions.length;
		}
	}
	
	/**
	 * If there are results wider than the container, try to grow the
	 * container or trim them to end with "..."
	 */
	function trimResultText() {
		if ( conf._data.div.is( ':hidden' ) )
			return;
		
		// Try to grow the container so all results fit
		// Can't use each() here because the inner function can read
		// but not write maxWidth for some crazy reason
		var maxWidth = 0;
		var spans = conf._data.div.find( 'span' ).get();
		for ( var i = 0; i < spans.length; i++ )
			if ( $(spans[i]).outerWidth() > maxWidth )
				maxWidth = $(spans[i]).outerWidth();
		
		// FIXME: Some mysterious fixing going on here
		// FIXME: Left out Opera fix for now
		// FIXME: This doesn't check that the container won't run off the screen
		// FIXME: This should try growing to the left instead if no space on the right
		var fix = 0;
		if ( conf._data.visibleResults < conf.suggestions.length )
			fix = 20;
		//else
		//	fix = operaWidthFix();
		if ( fix < 4 )
			// FIXME: Make 4px configurable?
			fix = 4; // Always pad at least 4px
		maxWidth += fix;
		
		var textBoxWidth = conf._data.textbox.outerWidth();
		var factor = maxWidth / textBoxWidth;
		if ( factor > conf.maxGrowFactor ) 
			factor = conf.maxGrowFactor;
		if ( factor < 1 )
			// Don't shrink the container to be smaller
			// than the textbox
			factor = 1;
		var newWidth = Math.round( textBoxWidth * factor );
		if ( newWidth != conf._data.div.outerWidth() )
			conf._data.div.animate( { width: newWidth },
				conf.animationDuration );
		// FIXME: mwsuggest.js has this inside the if != block
		// but I don't think that's right
		newWidth -= fix;
		
		// If necessary, trim and add ...
		conf._data.div.find( 'tr' ).each( function() {
			var span = $(this).find( 'span' );
			if ( span.outerWidth() > newWidth ) {
				var span = $(this).find( 'span' );
				span.text( span.text() + '...' );
				
				// While it's still too wide and the last
				// iteration shrunk it, remove the character
				// before '...'
				while ( span.outerWidth() > newWidth && span.text().length > 3 ) {
					span.text( span.text().substring( 0,
						span.text().length - 4 ) + '...' );
				}
				$(this).attr( 'title', $(this).data( 'text' ) );
			}
		});
	}
	
	/**
	 * Get a jQuery object for the currently highlighted row
	 */
	function getHighlightedRow() {
		return conf._data.div.find( '.os-suggest-result-hl' );
	}
	
	/**
	 * Highlight a result in the results table
	 * @param result <tr> to highlight: jQuery object, or 'prev' or 'next'
	 * @param updateTextbox If true, put the suggestion in the textbox
	 */
	function highlightResult( result, updateTextbox ) {
		// TODO: Use our own class here
		var selected = getHighlightedRow();
		if ( !result.get || selected.get( 0 ) != result.get( 0 ) ) {
			if ( result == 'prev' ) {
				result = selected.prev();
			} else if ( result == 'next' ) {
				if ( selected.size() == 0 )
					// No item selected, go to the first one
					result = conf._data.div.find( 'tr:first' );
				else {
					result = selected.next();
					if ( result.size() == 0 )
						// We were at the last item, stay there
						result = selected;
				}
			}
			
			selected.removeClass( 'os-suggest-result-hl' );
			result.addClass( 'os-suggest-result-hl' );
		}
		
		if ( updateTextbox ) {
			if ( result.size() == 0 )
				restoreText();
			else
				conf._data.textbox.val( result.data( 'text' ) );
		}
		
		if ( result.size() > 0 && conf._data.visibleResults < conf.suggestions.length ) {
			// Not all suggestions are visible
			// Scroll if needed
			
			// height of a result row
			var rowHeight = result.outerHeight();
			// index of first visible element
			var first = conf._data.div.scrollTop() / rowHeight;  
			// index of last visible element
			var last = first + conf._data.visibleResults - 1;
			// index of element to scroll to
			var to = result.attr( 'rel' );
			
			if ( to < first )
				// Need to scroll up
				conf._data.div.scrollTop( to * rowHeight );
			else if ( result.attr( 'rel' ) > last )
				// Need to scroll down
				conf._data.div.scrollTop( ( to - conf._data.visibleResults + 1 ) * rowHeight );
		}
	}
	
	/**
	 * Initialize the widget
	 */
	function init() {
		if ( typeof conf != 'object' || typeof conf._data != 'undefined' )
			// Configuration not set or init already done
			return;
		
		// Set defaults
		if ( typeof conf.animationDuration == 'undefined' )
			conf.animationDuration = 200;
		if ( typeof conf.cancelPending != 'function' )
			conf.cancelPending = function() {};
		if ( typeof conf.delay == 'undefined' )
			conf.delay = 250;
		if ( typeof conf.maxGrowFactor == 'undefined' )
			conf.maxGrowFactor = 2;
		if ( typeof conf.maxRows == 'undefined' )
			conf.maxRows = 7;
		if ( typeof conf.submitOnClick == 'undefined' )
			conf.submitOnClick = false;
		if ( typeof conf.suggestions != 'object' )
			conf.suggestions = [];
		
		conf._data = {};
		conf._data.textbox = $(this);
		conf._data.timerID = null; // ID of running timer
		conf._data.prevText = null; // Text in textbox when suggestions were last fetched
		conf._data.visibleResults = 0; // Number of results visible without scrolling
		conf._data.mouseDownOn = $( [] ); // Suggestion the last mousedown event occured on
	
		// Create container div for suggestions
		conf._data.div = $( '<div />' )
			.addClass( 'os-suggest' ) //TODO: use own CSS
			.css( {
				top: Math.round( $(this).offset().top ) + this.offsetHeight,
				left: Math.round( $(this).offset().left ),
				width: $(this).outerWidth()
			})
			.hide()
			.appendTo( $( 'body' ) );
		
		// Create results table
		$( '<table />' )
			.addClass( 'os-suggest-results' ) // TODO: use descendant selector
			.width( $(this).outerWidth() ) // TODO: see if we need Opera width fix 
			.appendTo( conf._data.div );
		
		$(this)
			// Stop browser autocomplete from interfering
			.attr( 'autocomplete', 'off')
			.keydown( function( e ) {
				// Store key pressed to handle later
				conf._data.keypressed = (e.keyCode == undefined) ? e.which : e.keyCode;
				conf._data.keypressed_count = 0;
			})
			.keypress( function() {
				conf._data.keypressed_count++;
				processKey( conf._data.keypressed );
			})
			.keyup( function() {
				// Some browsers won't throw keypress() for
				// arrow keys. If we got a keydown and a keyup
				// without a keypress in between, solve that
				if (conf._data.keypressed_count == 0 )
					processKey( conf._data.keypressed );
			})
			.blur( function() {
				// When losing focus because of a mousedown
				// on a suggestion, don't hide the suggestions 
				if ( conf._data.mouseDownOn.size() > 0 )
					return;
				conf._data.div.hide();
				cancelPendingSuggestions();
			});
		
		conf._data.div
			.mouseover( function( e ) {
				var tr = $( e.target ).closest( '.os-suggest tr' );
				highlightResult( tr, false );
			})
			// Can't use click() because the container div is hidden
			// when the textbox loses focus. Instead, listen for a
			// mousedown followed by a mouseup on the same <tr>
			.mousedown( function( e ) {
				var tr = $( e.target ).closest( '.os-suggest tr' );
				conf._data.mouseDownOn = tr;
			})
			.mouseup( function( e ) {
				var tr = $( e.target ).closest( '.os-suggest tr' );
				var other = conf._data.mouseDownOn;
				conf._data.mouseDownOn = $( [] );
				if ( tr.get( 0 ) != other.get( 0 ) )
					return;
				 
				highlightResult( tr, true );
				conf._data.div.hide();
				conf._data.textbox.focus();
				if ( conf.submitOnClick )
					conf._data.textbox.closest( 'form' )
						.submit();
			});
	}
	
	function getProperty( prop ) {
		return ( param[0] == '_' ? undefined : conf[param] );
	}
	
	function setProperty( prop, value ) {
		if ( typeof conf == 'undefined' ) {
			$(this).data( 'suggestionsConfiguration', {} );
			conf = $(this).data( 'suggestionsConfiguration' );
		}
		if ( prop[0] != '_' )
			conf[prop] = value;
		if ( prop == 'suggestions' && conf._data )
			// Setting suggestions post-init
			suggestionsChanged();
	}
	
	
	// Body of suggestions() starts here
	var conf = $(this).data( 'suggestionsConfiguration' );
	if ( typeof param == 'object' )
		return this.each( function() {
			// Bulk-set properties
			for ( key in param ) {
				// Make sure that this in setProperty()
				// is set right
				setProperty.call( this, key, param[key] );
			}
		});
	else if ( typeof param == 'string' ) {
		if ( typeof param2 != 'undefined' )
			return this.each( function() {
				setProperty( param, param2 );
			});
		else
			return getProperty( param );
	} else if ( typeof param != 'undefined' )
		// Incorrect usage, ignore
		return this;
	
	// No parameters given, initialize
	return this.each( init );
};})(jQuery);
