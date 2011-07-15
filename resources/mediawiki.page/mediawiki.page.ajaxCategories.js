// TODO
//
// * The edit summary should contain the added/removed category name too. 
//     Something like: "Category:Foo added. Reason"
//     Requirement: Be able to get msg with lang option.
// * Handle uneditable cats. Needs serverside changes!
// * Handle normal redirects
// * Enter to submit

( function( $ ) {

	/* Local scope */

	var	catNsId = mw.config.get( 'wgNamespaceIds' ).category,

	clean = function( s ) {
		if ( s !== undefined ) {
			return s.replace( /[\x00-\x1f\x23\x3c\x3e\x5b\x5d\x7b\x7c\x7d\x7f\s]+/g, '' );
		}
	},

	/**
	 * Build URL for passed Category
	 * 
	 * @param string category name.
	 * @return string Valid URL
	 */
	catLink = function( cat ) {
		return mw.util.wikiGetlink( new mw.Title( cat, catNsId ) );
	};

mw.ajaxCategories = function( options ) {
	//Save scope in shortcut
	var aC = this;

	// TODO grab these out of option object.

	var catLinkWrapper = '<li/>';
	var $container = $( '.catlinks' );
	var $containerNormal = $( '#mw-normal-catlinks' );

	var categoryLinkSelector = '#mw-normal-catlinks li a:not(.icon)';
	var _request;

	var _saveAllButton;
	var _cancelAllButton;
	var _multiEdit = mw.util.inArray( mw.config.get( 'wgUserGroups' ) );

	/**
	 * Helper function for $.fn.suggestion
	 * 
	 * @param string Query string.
	 */
	_fetchSuggestions = function( query ) {
		var _this = this;
		// ignore bad characters, they will be stripped out
		var catName = clean( $( this ).val() );
		var request = $.ajax( {
			url: mw.util.wikiScript( 'api' ),
			data: {
				'action': 'query',
				'list': 'allpages',
				'apnamespace': catNsId,
				'apprefix': catName,
				'format': 'json'
			},
			dataType: 'json',
			success: function( data ) {
				// Process data.query.allpages into an array of titles
				var pages = data.query.allpages;
				var titleArr = [];

				$.each( pages, function( i, page ) {
					var title = page.title.split( ':', 2 )[1];
					titleArr.push( title );
				} );

				$( _this ).suggestions( 'suggestions', titleArr );
			}
		} );
		//TODO
		_request = request;
	};

	/**
	 * Insert a newly added category into the DOM
	 * 
	 * @param string category name.
	 * @return jQuery object
	 */
	_createCatDOM = function( cat ) {
		// User can implicitely state a sort key.
		// Remove before display
		cat = cat.replace(/\|.*/, '');

		// strip out bad characters
		cat = clean ( cat );

		if ( $.isEmpty( cat ) || aC.containsCat( cat ) ) { 
			return; 
		}

		var $catLinkWrapper = $( catLinkWrapper );
		var $anchor = $( '<a/>' ).append( cat );
		$catLinkWrapper.append( $anchor );
		$anchor.attr( { target: "_blank", href: catLink( cat ) } );

		_createCatButtons( $anchor.get(0) );

		return $anchor;
	};

	_makeSuggestionBox = function( prefill, callback, buttonVal ) {
		// Create add category prompt
		var promptContainer = $( '<div class="mw-addcategory-prompt"/>' );
		var promptTextbox = $( '<input type="text" size="45" class="mw-addcategory-input"/>' );
		if ( prefill !== '' ) {
			promptTextbox.val( prefill );
		}
		var addButton = $( '<input type="button" class="mw-addcategory-button"/>' );
		addButton.val( buttonVal );

		addButton.click( callback );

		promptTextbox.suggestions( {
			'fetch':_fetchSuggestions,
			'cancel': function() {
				var req = _request;
				// XMLHttpRequest.abort is unimplemented in IE6, also returns nonstandard value of "unknown" for typeof
				if ( req && ( typeof req.abort !== 'unknown' ) && ( typeof req.abort !== 'undefined' ) && req.abort ) {
					req.abort();
				}
			}
		} );

		promptTextbox.suggestions();

		promptContainer.append( promptTextbox );
		promptContainer.append( addButton );

		return promptContainer;
	};

	/**
	 * Parse the DOM $container and build a list of
	 * present categories
	 * 
	 * @return array Array of all categories
	 */
	_getCats = function() {
		return $container.find( categoryLinkSelector ).map( function() { return $.trim( $( this ).text() ); } );
	};

	/**
	 * Check whether a passed category is present in the DOM
	 * 
	 * @return boolean True for exists
	 */
	this.containsCat = function( cat ) {
		return _getCats().filter( function() { return $.ucFirst(this) == $.ucFirst(cat); } ).length !== 0;
	};

	/**
	 * This gets called by all action buttons
	 * Displays a dialog to confirm the action
	 * Afterwords do the actual edit
	 *
	 * @param function fn text-modifying function 
	 * @param string actionSummary Changes done
	 * @param function fn doneFn callback after everything is done
	 * @return boolean True for exists
	 */
	_confirmEdit = function( fn, actionSummary, doneFn, $link, action ) {
		// Check whether to use multiEdit mode
		if ( _multiEdit && action != 'all' ) {
			// Stash away
			$link.data('stashIndex', _stash.fns.length );
			_stash.summaries.push( actionSummary );
			_stash.fns.push( fn );


			//TODO add Cancel button
			_saveAllButton.show();
			//_cancelAllButton.show();

			// This only does visual changes
			doneFn( true );
			return;
		}
		// Produce a confirmation dialog
		var dialog = $( '<div/>' );

		dialog.addClass( 'mw-ajax-confirm-dialog' );
		dialog.attr( 'title', mw.msg( 'ajax-confirm-title' ) );

		// Intro text.
		var confirmIntro = $( '<p/>' );
		confirmIntro.text( mw.msg( 'ajax-confirm-prompt' ) );
		dialog.append( confirmIntro );

		// Summary of the action to be taken
		var summaryHolder = $( '<p/>' );
		var summaryLabel = $( '<strong/>' );
		summaryLabel.text( mw.msg( 'ajax-confirm-actionsummary' ) + " " );
		summaryHolder.text( actionSummary );
		summaryHolder.prepend( summaryLabel );
		dialog.append( summaryHolder );

		// Reason textbox.
		var reasonBox = $( '<input type="text" size="45" />' );
		reasonBox.addClass( 'mw-ajax-confirm-reason' );
		dialog.append( reasonBox );

		// Submit button
		var submitButton = $( '<input type="button"/>' );
		submitButton.val( mw.msg( 'ajax-confirm-save' ) );

		var submitFunction = function() {
			_addProgressIndicator( dialog );
			_doEdit(
				mw.config.get( 'wgPageName' ),
				fn,
				reasonBox.val(),
				function() {
					doneFn();
					dialog.dialog( 'close' );
					_removeProgressIndicator( dialog );
				}
			);
		};

		var buttons = {};
		buttons[mw.msg( 'ajax-confirm-save' )] = submitFunction;
		var dialogOptions = {
			'AutoOpen' : true,
			'buttons' : buttons,
			'width' : 450
		};

		$( '#catlinks' ).prepend( dialog );
		dialog.dialog( dialogOptions );
	};

	/**
	 * When multiEdit mode is enabled,
	 * this is called when the user clicks "save all"
	 * Combines the summaries and edit functions
	 */
	_handleStashedCategories = function() {
		// Save fns
		fns = _stash.fns;

		//TODO Add spaces in msg
		var summary = _stash.summaries.join();
		if ( summary == '' ) {
			_saveAllButton.hide();
			return;
		}
		var combinedFn = function( oldtext ) {
			// Run the text through all action functions
			newtext = oldtext;
			for ( var i = 0; i < fns.length; i++ ) {
				if ( $.isfunction( fns[i] ) ) {
					newtext = fns[i]( newtext );
				}
			}
			return newtext;
		};
		var doneFn = _resetToActual;

		_confirmEdit( combinedFn, summary, doneFn, '', 'all' );
	};

	_resetToActual = function() {
		//Remove saveAllButton
		_saveAllButton.hide();
		_cancelAllButton.hide();

		// Clean stash
		_stash.fns = [];
		_stash.summaries = [];

		// TODO
		$container.find('.mw-removed-category').parent().remove();
		// Any link with $link.css('text-decoration', 'line-through');
		// needs to be removed

	};

	_doEdit = function( page, fn, summary, doneFn ) {
		// Get an edit token for the page.
		var getTokenVars = {
			'action':'query',
			'prop':'info|revisions',
			'intoken':'edit',
			'titles':page,
			'rvprop':'content|timestamp',
			'format':'json'
		};

		$.get( mw.util.wikiScript( 'api' ), getTokenVars,
			function( reply ) {
				var infos = reply.query.pages;
				$.each(
					infos,
					function( pageid, data ) {
						var token = data.edittoken;
						var timestamp = data.revisions[0].timestamp;
						var oldText = data.revisions[0]['*'];

						var newText = fn( oldText );

						if ( newText === false ) return;

						var postEditVars = {
							'action':'edit',
							'title':page,
							'text':newText,
							'summary':summary,
							'token':token,
							'basetimestamp':timestamp,
							'format':'json'
						};

						$.post( mw.util.wikiScript( 'api' ), postEditVars, doneFn, 'json' );
					}
				);
			}
		, 'json' );
	};

	/**
	 * Append spinner wheel to element
	 * @param DOMObject element.
	 */
	_addProgressIndicator = function( elem ) {
		var indicator = $( '<div/>' );

		indicator.addClass( 'mw-ajax-loader' );

		elem.append( indicator );
	};

	/**
	 * Find and remove spinner wheel from inside element
	 * @param DOMObject parent element.
	 */
	_removeProgressIndicator = function( elem ) {
		elem.find( '.mw-ajax-loader' ).remove();
	};

	/**
	 * Makes regex string caseinsensitive.
	 * Useful when 'i' flag can't be used.
	 * Return stuff like [Ff][Oo][Oo]
	 * @param string Regex string.
	 * @return string Processed regex string
	 */
	_makeCaseInsensitive = function( string ) {
		var newString = '';
		for (var i=0; i < string.length; i++) {
			newString += '[' + string[i].toUpperCase() + string[i].toLowerCase() + ']';
		}
		return newString;
	};
	_buildRegex = function( category ) {
		// Build a regex that matches legal invocations of that category.
		var categoryNSFragment = '';
		$.each( mw.config.get( 'wgNamespaceIds' ), function( name, id ) {
			if ( id == 14 ) {
				// The parser accepts stuff like cATegORy, 
				// we need to do the same
				categoryNSFragment += '|' + _makeCaseInsensitive ( $.escapeRE(name) );
			}
		} );
		categoryNSFragment = categoryNSFragment.substr( 1 ); // Remove leading |

		// Build the regex
		var titleFragment = $.escapeRE(category);

		firstChar = category.charAt( 0 );
		firstChar = '[' + firstChar.toUpperCase() + firstChar.toLowerCase() + ']';
		titleFragment = firstChar + category.substr( 1 );
		var categoryRegex = '\\[\\[(' + categoryNSFragment + '):' + titleFragment + '(\\|[^\\]]*)?\\]\\]';

		return new RegExp( categoryRegex, 'g' );
	};

	_handleEditLink = function( e ) {
		e.preventDefault();
		var $this = $( this );
		var $link = $this.parent().find( 'a:not(.icon)' );
		var category = $link.text();

		var $input = _makeSuggestionBox( category, 
						_handleCategoryEdit, 
						_multiEdit ? mw.msg( 'ajax-confirm-ok' ) : mw.msg( 'ajax-confirm-save' ) 
					);
		$link.after( $input ).hide();
		$link.data('editButton').hide();
		$link.data('deleteButton').unbind('click').click( function() {
			$input.remove();
			$link.show();
			$link.data('editButton').show();
			$( this ).unbind('click').click( _handleDeleteLink );
		});
	};

	_handleAddLink = function( e ) {
		e.preventDefault();

		$container.find( '#mw-normal-catlinks>.mw-addcategory-prompt' ).toggle();
	};

	_handleDeleteLink = function( e ) {
		var $this = $( this );
		var $link = $this.parent().find( 'a:not(.icon)' );
		var category = $link.text();

		if ( $link.hasClass('mw-added-category') ) {
			// We're just cancelling the addition
			_removeStashItem ( $link );

			$link.parent().remove();
			return;
		}

		var categoryRegex = _buildRegex( category );

		var summary = mw.msg( 'ajax-remove-category-summary', category );

		_confirmEdit(
			function( oldText ) {
				newText = _runHooks ( oldText, 'beforeDelete' );
				//TODO Cleanup whitespace safely?
				var newText = newText.replace( categoryRegex, '' );

				if ( newText == oldText ) {
					var error = mw.msg( 'ajax-remove-category-error' );
					_showError( error );
					_removeProgressIndicator( $( '.mw-ajax-confirm-dialog' ) );
					$( '.mw-ajax-confirm-dialog' ).dialog( 'close' );
					return false;
				}

				return newText;
			},
			summary, 
			function( unsaved ) {
				if ( unsaved ) {
					//TODO Make revertable
					$link.addClass('mw-removed-category');
				} else {
					$this.parent().remove();
				}
			},
			$link,
			'delete'
		);
	};

	_handleCategoryAdd = function( e ) {
		var $this = $( this );

		// Grab category text
		var category = $this.parent().find( '.mw-addcategory-input' ).val();
		category = $.ucFirst( category );

		var $link = _createCatDOM( category );

		if ( aC.containsCat(category) ) {
			_showError( mw.msg( 'ajax-category-already-present', category ) );
			return;
		}
		var appendText = "\n[[" + new mw.Title( category,  catNsId ) + "]]\n";
		var summary = mw.msg( 'ajax-add-category-summary', category );

		_confirmEdit(
			function( oldText ) {
				newText = _runHooks ( oldText, 'beforeAdd' );
				return newText + appendText;
			},
			summary,
			function( unsaved ) {
				$container.find( '#mw-normal-catlinks>.mw-addcategory-prompt' ).toggle();
				$container.find( '#mw-normal-catlinks ul' ).append( $link.parent() );
				if ( unsaved ) {
					$link.addClass( 'mw-added-category' );
				}
			},
			$link,
			'add'
		);
	};

	_handleCategoryEdit = function( e ) {
		var $this = $( this );

		// Grab category text
		var categoryNew = $this.parent().find( '.mw-addcategory-input' ).val();
		categoryNew = $.ucFirst( categoryNew );

		var $link = $this.parent().parent().find( 'a:not(.icon)' );
		if ( $link.hasClass('mw-removed-category') ) {
			_removeStashItem ( $link );
			$link.removeClass( 'mw-removed-category' );
		}
		if ( $link.data( 'origCat' ) ) {
			var category = $link.data( 'origCat' );
			_removeStashItem ( $link );
		} else {
			var category = $link.text();
		}


		// User didn't change anything.
		if ( category == categoryNew ) {
			$link.data('deleteButton').click();
			return;
		}
		categoryRegex = _buildRegex( category );

		var summary = mw.msg( 'ajax-edit-category-summary', category, categoryNew );

		_confirmEdit(
			function( oldText ) {
				newText = _runHooks ( oldText, 'beforeChange' );

				var matches = newText.match( categoryRegex );

				//Old cat wasn't found, likely to be transcluded
				if ( !$.isArray( matches ) ) {
					var error = mw.msg( 'ajax-edit-category-error' );
					_showError( error );
					_removeProgressIndicator( $( '.mw-ajax-confirm-dialog' ) );
					$( '.mw-ajax-confirm-dialog' ).dialog( 'close' );
					return false;
				}
				var sortkey = matches[0].replace( categoryRegex, '$2' );
				var newCategoryString = "[[" + new mw.Titel( categoryNew, catNsId ) + sortkey + ']]';

				if (matches.length > 1) {
					// The category is duplicated.
					// Remove all but one match
					for (var i = 1; i < matches.length; i++) {
						oldText = oldText.replace( matches[i], ''); 
					}
				}
				var newText = oldText.replace( categoryRegex, newCategoryString );

				return newText;
			},
			summary, 
			function( unsaved ) {
				// Remove input box & button
				$link.data('deleteButton').click();

				// Update link text and href
				$link.show().text( categoryNew ).attr( 'href', catLink( categoryNew ) );
				if ( unsaved ) {
					$link.data( 'origCat', category ).addClass( 'mw-changed-category' );
				}
			},
			$link,
			'edit'
		);
	};

	/**
	 * Open a dismissable error dialog 
	 *
	 * @param string str The error description
	 */
	_showError = function( str ) {
		var dialog = $( '<div/>' );
		dialog.text( str );

		mw.util.$content.append( dialog );

		var buttons = { };
		buttons[mw.msg( 'ajax-error-dismiss' )] = function( e ) {
			dialog.dialog( 'close' );
		};
		var dialogOptions = {
			'buttons' : buttons,
			'AutoOpen' : true,
			'title' : mw.msg( 'ajax-error-title' )
		};

		dialog.dialog( dialogOptions );
	};

	/**
	 * Manufacture iconed button, with or without text 
	 *
	 * @param string icon The icon class.
	 * @param string title Title attribute.
	 * @param string className (optional) Additional classes to be added to the button.
	 * @param string text (optional) Text of button.
	 *
	 * @return jQueryObject The button
	 */
	_createButton = function( icon, title, className, text ){
		var $button = $( '<a>' ).addClass( className || '' )
			.attr('title', title);

		if ( text ) {
			var $icon = $( '<a>' ).addClass( 'icon ' + icon );
			$button.addClass( 'icon-parent' ).append( $icon ).append( text );
		} else {
			$button.addClass( 'icon ' + icon );
		}
		return $button;
	};

	/**
	 * Append edit and remove buttons to a given category link 
	 *
	 * @param DOMElement element Anchor element, to which the buttons should be appended.
	 */
	_createCatButtons = function( element ) {
		// Create remove & edit buttons
		var deleteButton = _createButton('icon-close', mw.msg( 'ajax-remove-category' ) );
		var editButton = _createButton('icon-edit', mw.msg( 'ajax-edit-category' ) );

		//Not yet used
		var saveButton = _createButton('icon-tick', mw.msg( 'ajax-confirm-save' ) ).hide();

		deleteButton.click( _handleDeleteLink );
		editButton.click( _handleEditLink );

		$( element ).after( deleteButton ).after( editButton );

		//Save references to all links and buttons
		$( element ).data({
			saveButton 	: saveButton,
			deleteButton: deleteButton,
			editButton 	: editButton
		});
	};
	this.setup = function() {
		// Could be set by gadgets like HotCat etc.
		if ( mw.config.get('disableAJAXCategories') ) {
			return;
		}
		// Only do it for articles.
		if ( !mw.config.get( 'wgIsArticle' ) ) return;

		// Unhide hidden category holders.
		$('#mw-hidden-catlinks').show();

		var $li = $('<li>', {
			'class' : 'mw-ajax-addcategory-holder'
		});
		// $containerNormal.find('ul').append( $li)
		// Create [Add Category] link
		var addLink = _createButton('icon-add', 
									mw.msg( 'ajax-add-category' ), 
									'mw-ajax-addcategory', 
									mw.msg( 'ajax-add-category' )
								   );
		addLink.click( _handleAddLink );
		$containerNormal.append( addLink );

		// Create add category prompt
		var promptContainer = _makeSuggestionBox( '', _handleCategoryAdd, mw.msg( 'ajax-add-category-submit' ) );
		promptContainer.hide();

		// Create edit & delete link for each category.
		$( '#catlinks li a' ).each( function() {
			_createCatButtons( this );
		});

		$containerNormal.append( promptContainer );

		//TODO Make more clickable
		_saveAllButton = _createButton( 'icon-tick', 
										mw.msg( 'ajax-confirm-save-all' ), 
										'', 
										mw.msg( 'ajax-confirm-save-all' ) 
										);
		_cancelAllButton = _createButton( 'icon-tick', 
										mw.msg( 'ajax-confirm-save-all' ), 
										'', 
										mw.msg( 'ajax-confirm-save-all' ) 
										);
		_saveAllButton.click( _handleStashedCategories ).hide();
		_cancelAllButton.hide();
		//TODO wrap in div display:inline-block
		$containerNormal.append( _saveAllButton ).append( _cancelAllButton );
	};

	_stash = {
		summaries : [],
		fns : []
	};
	_removeStashItem = function( $link ) {
		var i = $link.data( 'stashIndex' );
		delete _stash.fns[i];
		delete _stash.summaries[i];
	}
	_hooks = {
		beforeAdd : [],
		beforeChange : [],
		beforeDelete : []
	};
	_runHooks = function( oldtext, type ) {
		// No hooks registered
		if ( !_hooks[type] ) {
			return oldtext;
		} else {
			for (var i = 0; i < _hooks[type].length; i++) {
				oldtext = _hooks[type][i]( oldtext );
			}
			return oldtext;
		}
	};
	/**
	 * Add hooks
	 * Currently available: beforeAdd, beforeChange, beforeDelete
	 *
	 * @param string type Type of hook to add
	 * @param function fn Hook function. This function is the old text passed 
	 *					  and it needs to return the modified text
	 */
	this.addHook = function( type, fn ) {
		if ( !_hooks[type] ) return;
		else hooks[type].push( fn );
	};
};

} )( jQuery );