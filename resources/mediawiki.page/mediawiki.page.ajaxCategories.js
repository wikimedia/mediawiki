/**
 * mediaWiki.page.ajaxCategories
 *
 * @author Michael Dale, 2009
 * @author Leo Koppelkamm, 2011
 * @since 1.18
 *
 * Relies on: mw.config (wgFormattedNamespaces, wgNamespaceIds, wgCaseSensitiveNamespaces, wgUserGroups), 
 *   mw.util.wikiGetlink, mw.user.getId
 */
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
	catUrl = function( cat ) {
		return mw.util.wikiGetlink( new mw.Title( cat, catNsId ) );
	};

	/**
	 * Helper function for $.fn.suggestion
	 * 
	 * @param string Query string.
	 */
	fetchSuggestions = function( query ) {
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
		_request = request;
	};
	
	/**
	* Replace <nowiki> and comments with unique keys
	*/
	replaceNowikis = function( text, id, array ) {
		var matches = text.match( /(<nowiki\>[\s\S]*?<\/nowiki>|<\!--[\s\S]*?--\>)/g );
		for ( var i = 0; matches && i < matches.length; i++ ) {
			array[i] = matches[i];
			text = text.replace( matches[i], id + i + '-' );
		}
		return text;
	};
	
	/**
	* Restore <nowiki> and comments  from unique keys
	*/
	restoreNowikis = function( text, id, array ) {
		for ( var i = 0; i < array.length; i++ ) {
			text = text.replace( id + i + '-', array[i] );
		}
		return text;
	};
	
	/**
	 * Makes regex string caseinsensitive.
	 * Useful when 'i' flag can't be used.
	 * Return stuff like [Ff][Oo][Oo]
	 * @param string Regex string.
	 * @return string Processed regex string
	 */
	makeCaseInsensitive = function( string ) {
		if ( $.inArray( 14, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) + 1 ) {
			return string;
		}
		var newString = '';
		for ( var i=0; i < string.length; i++ ) {
			newString += '[' + string.charAt( i ).toUpperCase() + string.charAt( i ).toLowerCase() + ']';
		}
		return newString;
	};
	
	/**
	 * Build a regex that matches legal invocations 
	 * of the passed category.
	 * @param string category.
	 * @param boolean Match one following linebreak as well?
	 * @return Regex
	 */
	buildRegex = function( category, matchLineBreak ) {
		var categoryNSFragment = '';
		$.each( mw.config.get( 'wgNamespaceIds' ), function( name, id ) {
			if ( id == 14 ) {
				// The parser accepts stuff like cATegORy, 
				// we need to do the same
				// ( Well unless we have wgCaseSensitiveNamespaces, but that's being checked for )
				categoryNSFragment += '|' + makeCaseInsensitive ( $.escapeRE( name ) );
			}
		} );
		categoryNSFragment = categoryNSFragment.substr( 1 ); // Remove leading pipe

		// Build the regex
		var titleFragment = $.escapeRE( category ).replace( /( |_)/g, '[ _]' );
		
		firstChar = titleFragment.charAt( 0 );
		firstChar = '[' + firstChar.toUpperCase() + firstChar.toLowerCase() + ']';
		titleFragment = firstChar + titleFragment.substr( 1 );
		var categoryRegex = '\\[\\[(' + categoryNSFragment + '):' + '[ _]*' +titleFragment + '(\\|[^\\]]*)?\\]\\]';
		if ( matchLineBreak ) {
			categoryRegex += '[ \\t\\r]*\\n?';
		}
		return new RegExp( categoryRegex, 'g' );
	};
	

mw.ajaxCategories = function( options ) {
	//Save scope in shortcut
	var that = this, _request, _saveAllButton, _cancelAllButton, _addContainer, defaults;
	
	defaults = { 
		catLinkWrapper   : '<li/>',
		$container       : $( '.catlinks' ),
		$containerNormal : $( '#mw-normal-catlinks' ),
		categoryLinkSelector : 'li a:not(.icon)',
		multiEdit        : $.inArray( 'user', mw.config.get( 'wgUserGroups' ) ) + 1,
		resolveRedirects : true
	};
	// merge defaults and options, without modifying defaults */
	options = $.extend( {}, defaults, options );

	/**
	 * Insert a newly added category into the DOM
	 * 
	 * @param string category name.
	 * @return jQuery object
	 */
	this.createCatLink = function( cat ) {
		// User can implicitely state a sort key.
		// Remove before display
		cat = cat.replace(/\|.*/, '' );

		// strip out bad characters
		cat = clean ( cat );

		if ( $.isEmpty( cat ) || that.containsCat( cat ) ) { 
			return; 
		}

		var $catLinkWrapper = $( options.catLinkWrapper );
		var $anchor = $( '<a/>' ).append( cat );
		$catLinkWrapper.append( $anchor );
		$anchor.attr( { target: "_blank", href: catUrl( cat ) } );

		_createCatButtons( $anchor );

		return $anchor;
	};

	/**
	 * Takes a category link element
	 * and strips all data from it.
	 * 
	 * @param jQuery object
	 */
	this.resetCatLink = function( $link, del, dontRestoreText ) {
		$link.removeClass( 'mw-removed-category mw-added-category mw-changed-category' );
		var data = $link.data();

		if ( typeof data.stashIndex == "number" ) {
			_removeStashItem( data.stashIndex );			
		}
		if ( del ) {
			$link.parent.remove();
			return;
		}
		if ( data.origCat && !dontRestoreText ) {
			$link.text( data.origCat );
			$link.attr( 'href', catUrl( data.origCat ) );
		}

		$link.removeData();

		//Readd static.
		$link.data({
			saveButton 	: data.saveButton,
			deleteButton: data.deleteButton,
			editButton 	: data.editButton
		});
	};

	/**
	 * Reset all data from the category links and the stash.
	 * @param Boolean del Delete any category links with .mw-removed-category
	 */
	this.resetAll = function( del ) {
		var $links = options.$container.find( options.categoryLinkSelector ), $del = $();
		if ( del ) {
			$del = $links.filter( '.mw-removed-category' ).parent();
		}

		$links.each( function() {
			that.resetCatLink( $( this ), false, del );
		});
		
		$del.remove();

		if ( !options.$container.find( '#mw-hidden-catlinks li' ).length ) {
			options.$container.find( '#mw-hidden-catlinks' ).remove();
		}
	};
	
	/**
	 * Create a suggestion box for use in edit/add dialogs
	 * @param str prefill Prefill input
	 * @param function callback on submit
	 * @param str buttonVal Button text
	 */
	this._makeSuggestionBox = function( prefill, callback, buttonVal ) {
		// Create add category prompt
		var promptContainer = $( '<div class="mw-addcategory-prompt"/>' );
		var promptTextbox = $( '<input type="text" size="30" class="mw-addcategory-input"/>' );
		if ( prefill !== '' ) {
			promptTextbox.val( prefill );
		}
		var addButton = $( '<input type="button" class="mw-addcategory-button"/>' );
		addButton.val( buttonVal );

		addButton.click( callback );
		promptTextbox.keyup( function( e ) {
    		if ( e.keyCode == 13 ) addButton.click();
		});
		promptTextbox.suggestions( {
			'fetch': fetchSuggestions,
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
	this.getCats = function() {
		return options.$container.find( options.categoryLinkSelector ).map( function() { return $.trim( $( this ).text() ); } );
	};

	/**
	 * Check whether a passed category is present in the DOM
	 * 
	 * @return boolean True for exists
	 */
	this.containsCat = function( cat ) {
		return that.getCats().filter( function() { return $.ucFirst( this ) == $.ucFirst( cat ); } ).length !== 0;
	};

	/**
	 * This gets called by all action buttons
	 * Displays a dialog to confirm the action
	 * Afterwards do the actual edit
	 *
	 * @param function fn text-modifying function 
	 * @param string actionSummary Changes done
	 * @param string shortSummary Changes, short version
	 * @param function fn doneFn callback after everything is done
	 * @return boolean True for exists
	 */
	this._confirmEdit = function( fn, actionSummary, shortSummary, doneFn, $link, action ) {
		// Check whether to use multiEdit mode
		if ( options.multiEdit && action != 'all' ) {
			// Stash away
			$link.data( 'stashIndex', _stash.fns.length );
			$link.data( 'summary', actionSummary );
			_stash.summaries.push( actionSummary );
			_stash.shortSum.push( shortSummary );
			_stash.fns.push( fn );

			_saveAllButton.show();
			_cancelAllButton.show();

			// This only does visual changes
			doneFn( true );
			return;
		}
		// Produce a confirmation dialog
		var dialog = $( '<div/>' );

		dialog.addClass( 'mw-ajax-confirm-dialog' );
		dialog.attr( 'title', mw.msg( 'ajax-confirm-title' ) );

		// Summary of the action to be taken
		var summaryHolder = $( '<p/>' );
		summaryHolder.html( mw.msg( 'ajax-category-question', actionSummary ) );
		dialog.append( summaryHolder );

		// Reason textbox.
		var reasonBox = $( '<input type="text" size="45" />' );
		reasonBox.addClass( 'mw-ajax-confirm-reason' );
		dialog.append( reasonBox );

		// Submit button
		var submitButton = $( '<input type="button"/>' );
		submitButton.val( mw.msg( 'ajax-confirm-save' ) );

		var submitFunction = function() {
			that._addProgressIndicator( dialog );
			that._doEdit(
				mw.config.get( 'wgPageName' ),
				fn,
				shortSummary + ': ' + reasonBox.val(),
				function() {
					doneFn();
					dialog.dialog( 'close' );
					that._removeProgressIndicator( dialog );
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

		// Close on enter
		dialog.keyup( function( e ) {
    		if ( e.keyCode == 13 ) submitFunction();
		});
	};

	/**
	 * When multiEdit mode is enabled,
	 * this is called when the user clicks "save all"
	 * Combines the summaries and edit functions
	 */
	this._handleStashedCategories = function() {
		var summary = '', fns = _stash.fns;

		// Remove "holes" in array
		summary = $.grep( _stash.summaries, function( n, i ) {
			return ( n );
		});
		if ( summary.length < 1 ) {
			// Nothing to do here.
			_saveAllButton.hide();
			_cancelAllButton.hide();
			return;
		} else if ( summary.length == 1 ) {
			summary = summary.pop();
		} else {
			var lastSummary = summary.pop();
			summary = summary.join( ', ');
			summary += mw.msg( 'ajax-category-and' ) + lastSummary;
			summary = summary.substring( 0, summary.length - 2 );
		}
		// Remove "holes" in array
		summaryShort = $.grep( _stash.shortSum, function( n,i ) {
			return ( n );
		});
		summaryShort = summaryShort.join( ', ' );

		var combinedFn = function( oldtext ) {
			// Run the text through all action functions
			newtext = oldtext;
			for ( var i = 0; i < fns.length; i++ ) {
				if ( $.isFunction( fns[i] ) ) {
					newtext = fns[i]( newtext );
					if ( newtext === false ) {
						return false;
					}
				}
			}
			return newtext;
		};
		var doneFn = function() { that.resetAll( true ); };

		that._confirmEdit( combinedFn, summary, shortSummary, doneFn, '', 'all' );
	};

	/**
	 * Do the actual edit.
	 * Gets token & text from api, runs it through fn
	 * and saves it with summary.
	 * @param str page Pagename
	 * @param function fn edit function
	 * @param str summary
	 * @param str doneFn Callback after all is done
	 */
	this._doEdit = function( page, fn, summary, doneFn ) {
		// Get an edit token for the page.
		var getTokenVars = {
			'action':'query',
			'prop':'info|revisions',
			'intoken':'edit',
			'titles':page,
			'rvprop':'content|timestamp',
			'format':'json'
		};

		$.post( mw.util.wikiScript( 'api' ), getTokenVars,
			function( reply ) {
				var infos = reply.query.pages;
				$.each(
					infos,
					function( pageid, data ) {
						var token = data.edittoken;
						var timestamp = data.revisions[0].timestamp;
						var oldText = data.revisions[0]['*'];

						// Replace all nowiki and comments with unique keys
						var key = mw.user.generateId();
						var nowiki = [];
						oldText = replaceNowikis( oldText, key, nowiki );
						
						// Then do the changes
						var newText = fn( oldText );
						if ( newText === false ) return;
						
						// And restore them back
						newText = restoreNowikis( newText, key, nowiki );

						var postEditVars = {
							'action':'edit',
							'title':page,
							'text':newText,
							'summary':summary,
							'token':token,
							'basetimestamp':timestamp,
							'format':'json'
						};

						$.post( mw.util.wikiScript( 'api' ), postEditVars, doneFn, 'json' )
						 .error( function( xhr, text, error ) {
							_showError( mw.msg( 'ajax-api-error', text, error ) );
						});
					}
				);
			}
		, 'json' ).error( function( xhr, text, error ) {
			_showError( mw.msg( 'ajax-api-error', text, error ) );
		});
	};
	/**
	 * Append spinner wheel to element
	 * @param DOMObject element.
	 */
	this._addProgressIndicator = function( elem ) {
		elem.append( $( '<div/>' ).addClass( 'mw-ajax-loader' ) );
	};

	/**
	 * Find and remove spinner wheel from inside element
	 * @param DOMObject parent element.
	 */
	this._removeProgressIndicator = function( elem ) {
		elem.find( '.mw-ajax-loader' ).remove();
	};
	
	/**
	 * Checks the API whether the category in question is a redirect.
	 * Also returns existance info ( to color link red/blue )
	 * @param string category.
	 * @param function callback
	 */
	this._resolveRedirects = function( category, callback ) {
		if ( !options.resolveRedirects ) {
			callback( category );
			return;
		}
		var queryVars = {
			'action':'query',
			'titles': new mw.Title( category,  catNsId ).toString(),
			'redirects':'',
			'format' : 'json'
		};

		$.get( mw.util.wikiScript( 'api' ), queryVars,
			function( reply ) {
				var redirect = reply.query.redirects;
				if ( redirect ) {
					category = new mw.Title( redirect[0].to )._name;
				}
				callback( category, !reply.query.pages[-1] );
			}
		, 'json' );
	};
	
	/**
	 * Handle add category submit. Not to be called directly
	 */
	this._handleAddLink = function( e ) {
		var $this = $( this ), $link = $();

		// Grab category text
		var category = $this.parent().find( '.mw-addcategory-input' ).val();
		category = $.ucFirst( category );

		// Resolve redirects
		that._resolveRedirects( category, function( resolvedCat, exists ) {
			that.handleCategoryAdd( $link, resolvedCat, false, exists );
		} );
	};
	/**
	 * Execute or queue an category add
	 */
	this.handleCategoryAdd = function( $link, category, noAppend, exists ) {
		if ( !$link.length ) {
			$link = that.createCatLink( category );
		}
		// Mark red if missing
		$link.toggleClass( 'new', exists === false );
		
		// Handle sortkey
		var arr = category.split( '|' ), sortkey = '';
		
		if ( arr.length > 1 ) {
			category = arr.shift();
			sortkey = '|' + arr.join( '|' );
			if ( sortkey == '|' ) sortkey = '';
		}
		
		//Replace underscores 
		category = category.replace(/_/g, ' ' );
		
		if ( that.containsCat( category ) ) {
			_showError( mw.msg( 'ajax-category-already-present', category ) );
			return;
		}
		var catFull = new mw.Title( category,  catNsId ).toString().replace(/_/g, ' ' );
		var appendText = "\n[[" + catFull + sortkey + "]]\n";
		var summary = mw.msg( 'ajax-add-category-summary', category );
		var shortSummary = '+[[' + catFull + ']]';
		that._confirmEdit(
			function( oldText ) {
				newText = _runHooks ( oldText, 'beforeAdd', category );
				newText = newText + appendText;
				return _runHooks ( newText, 'afterAdd', category );
			},
			summary,
			shortSummary,
			function( unsaved ) {
				if ( !noAppend ) {
					options.$container.find( '#mw-normal-catlinks>.mw-addcategory-prompt' ).children( 'input' ).hide();
					options.$container.find( '#mw-normal-catlinks ul' ).append( $link.parent() );
				} else {
					// Remove input box & button
					$link.data( 'deleteButton' ).click();

					// Update link text and href
					$link.show().text( category ).attr( 'href', catUrl( category ) );
				}
				if ( unsaved ) {
					$link.addClass( 'mw-added-category' );
				}
				$( '.mw-ajax-addcategory' ).click();
			},
			$link,
			'add'
		);
	};
	this._createEditInterface = function( e ) {
		var $this = $( this ),
			$link = $this.data( 'link' ),
			category = $link.text();
		var $input = that._makeSuggestionBox( category, 
						that._handleEditLink, 
						options.multiEdit ? mw.msg( 'ajax-confirm-ok' ) : mw.msg( 'ajax-confirm-save' ) 
			);
		$link.after( $input ).hide();
		$input.find( '.mw-addcategory-input' ).focus();
		$link.data( 'editButton' ).hide();
		$link.data( 'deleteButton' ).unbind( 'click' ).click( function() {
			$input.remove();
			$link.show();
			$link.data( 'editButton' ).show();
			$( this ).unbind( 'click' ).click( that._handleDeleteLink )
				.attr( 'title', mw.msg( 'ajax-remove-category' ));
		}).attr( 'title', mw.msg( 'ajax-cancel' ));	
	};
	
	/**
	 * Handle edit category submit. Not to be called directly
	 */
	this._handleEditLink = function( e ) {
		var $this = $( this ),
			$link = $this.parent().parent().find( 'a:not(.icon)' ),
			categoryNew, sortkey = '';
		
		// Grab category text
		categoryNew = $this.parent().find( '.mw-addcategory-input' ).val();
		categoryNew = $.ucFirst( categoryNew.replace(/_/g, ' ' ) );
		
		// Strip sortkey
		var arr = categoryNew.split( '|' );
		if ( arr.length > 1 ) {
			categoryNew = arr.shift();
			sortkey = '|' + arr.join( '|' );
		}

		// Grab text
		var added = $link.hasClass( 'mw-added-category' );
		that.resetCatLink ( $link );
		var category = $link.text();

		// Check for dupes ( exluding itself )
		if ( category != categoryNew && that.containsCat( categoryNew ) ) {
			$link.data( 'deleteButton' ).click();
			return;
		}

		// Resolve redirects
		that._resolveRedirects( categoryNew, function( resolvedCat, exists ) {
			that.handleCategoryEdit( $link, category, resolvedCat, sortkey, exists, added );
		});
	};
	/**
	 * Execute or queue an category edit
	 */
	this.handleCategoryEdit = function( $link, category, categoryNew, sortkeyNew, exists, added ) {
		// Category add needs to be handled differently
		if ( added ) {
			// Pass sortkey back
			that.handleCategoryAdd( $link, categoryNew + sortkeyNew, true );
			return;
		}
		// User didn't change anything.
		if ( category == categoryNew + sortkeyNew ) {
			$link.data( 'deleteButton' ).click();
			return;
		}
		// Mark red if missing
		$link.toggleClass( 'new', exists === false );
	
		categoryRegex = buildRegex( category );
		
		var summary = mw.msg( 'ajax-edit-category-summary', category, categoryNew );
		var shortSummary = '[[' + new mw.Title( category,  catNsId ) + ']] -> [[' + new mw.Title( categoryNew,  catNsId ) + ']]';
		that._confirmEdit(
			function( oldText ) {
				newText = _runHooks ( oldText, 'beforeChange', category, categoryNew );

				var matches = newText.match( categoryRegex );

				//Old cat wasn't found, likely to be transcluded
				if ( !$.isArray( matches ) ) {
					_showError( mw.msg( 'ajax-edit-category-error' ) );
					return false;
				}
				var sortkey = sortkeyNew || matches[0].replace( categoryRegex, '$2' );
				var newCategoryString = "[[" + new mw.Title( categoryNew, catNsId ) + sortkey + ']]';

				if ( matches.length > 1 ) {
					// The category is duplicated.
					// Remove all but one match
					for ( var i = 1; i < matches.length; i++ ) {
						oldText = oldText.replace( matches[i], '' ); 
					}
				}
				var newText = oldText.replace( categoryRegex, newCategoryString );

				return _runHooks ( newText, 'afterChange', category, categoryNew );
			},
			summary, 
			shortSummary,
			function( unsaved ) {
				// Remove input box & button
				$link.data( 'deleteButton' ).click();

				// Update link text and href
				$link.show().text( categoryNew ).attr( 'href', catUrl( categoryNew ) );
				if ( unsaved ) {
					$link.data( 'origCat', category ).addClass( 'mw-changed-category' );
				}
			},
			$link,
			'edit'
		);
	};
	
	/**
	 * Handle delete category submit. Not to be called directly
	 */
	this._handleDeleteLink = function() {
		var $this = $( this ),
			$link = $this.parent().find( 'a:not(.icon)' ),
			category = $link.text();

		if ( $link.is( '.mw-added-category, .mw-changed-category' ) ) {
			// We're just cancelling the addition or edit
			that.resetCatLink ( $link, $link.hasClass( 'mw-added-category' ) );
			return;
		} else if ( $link.is( '.mw-removed-category' ) ) {
			// It's already removed...
			return;
		}
		that.handleCategoryDelete( $link, category );
	};
	
	/**
	 * Execute or queue an category delete
	 */
	this.handleCategoryDelete = function( $link, category ) {
		var categoryRegex = buildRegex( category, true );

		var summary = mw.msg( 'ajax-remove-category-summary', category );
		var shortSummary = '-[[' + new mw.Title( category,  catNsId ) + ']]';

		that._confirmEdit(
			function( oldText ) {
				newText = _runHooks ( oldText, 'beforeDelete', category );
				var newText = newText.replace( categoryRegex, '' );

				if ( newText == oldText ) {
					_showError( mw.msg( 'ajax-remove-category-error' ) );
					return false;
				}

				return _runHooks ( newText, 'afterDelete', category );
			},
			summary,
			shortSummary,
			function( unsaved ) {
				if ( unsaved ) {
					$link.addClass( 'mw-removed-category' );
				} else {
					$link.parent().remove();
				}
			},
			$link,
			'delete'
		);
	};
	

	/**
	 * Open a dismissable error dialog 
	 *
	 * @param string str The error description
	 */
	_showError = function( str ) {
		var oldDialog = $( '.mw-ajax-confirm-dialog' );
		that._removeProgressIndicator( oldDialog );
		oldDialog.dialog( 'close' );
		
		var dialog = $( '<div/>' );
		dialog.text( str );

		mw.util.$content.append( dialog );

		var buttons = { };
		buttons[mw.msg( 'ajax-confirm-ok' )] = function( e ) {
			dialog.dialog( 'close' );
		};
		var dialogOptions = {
			'buttons' : buttons,
			'AutoOpen' : true,
			'title' : mw.msg( 'ajax-error-title' )
		};

		dialog.dialog( dialogOptions );

		// Close on enter
		dialog.keyup( function( e ) {
    		if ( e.keyCode == 13 ) dialog.dialog( 'close' );
		});
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
		// We're adding a zero width space for IE7, it's got problems with empty nodes apparently
		var $button = $( '<a>' ).addClass( className || '' )
			.attr( 'title', title ).html( '&#8203;' );

		if ( text ) {
			var $icon = $( '<span>' ).addClass( 'icon ' + icon ).html( '&#8203;' );
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
	_createCatButtons = function( $element ) {
		// Create remove & edit buttons
		var deleteButton = _createButton( 'icon-close', mw.msg( 'ajax-remove-category' ) );
		var editButton = _createButton( 'icon-edit', mw.msg( 'ajax-edit-category' ) );

		//Not yet used
		var saveButton = _createButton( 'icon-tick', mw.msg( 'ajax-confirm-save' ) ).hide();

		deleteButton.click( that._handleDeleteLink );
		editButton.click( that._createEditInterface );

		$element.after( deleteButton ).after( editButton );

		//Save references to all links and buttons
		$element.data({
			saveButton 	: saveButton,
			deleteButton: deleteButton,
			editButton 	: editButton
		});
		editButton.data({
			link 	: $element
		});
	};

	/**
	 * Create the UI 
	 */
	this.setup = function() {
		// Could be set by gadgets like HotCat etc.
		if ( mw.config.get( 'disableAJAXCategories' ) ) {
			return false;
		}
		// Only do it for articles.
		if ( !mw.config.get( 'wgIsArticle' ) ) return;

		// Create [Add Category] link
		var addLink = _createButton( 'icon-add', 
									mw.msg( 'ajax-add-category' ), 
									'mw-ajax-addcategory', 
									mw.msg( 'ajax-add-category' )
								   );
		addLink.click( function() {
			$( this ).nextAll().toggle().filter( '.mw-addcategory-input' ).focus();
		});
		

		// Create add category prompt
		_addContainer = that._makeSuggestionBox( '', that._handleAddLink, mw.msg( 'ajax-add-category-submit' ) );
		_addContainer.children().hide();

		_addContainer.prepend( addLink );

		// Create edit & delete link for each category.
		$( '#catlinks li a' ).each( function() {
			_createCatButtons( $( this ) );
		});

		options.$containerNormal.append( _addContainer );

		//TODO Make more clickable
		_saveAllButton = _createButton( 'icon-tick', 
										mw.msg( 'ajax-confirm-save-all' ), 
										'', 
										mw.msg( 'ajax-confirm-save-all' ) 
										);
		_cancelAllButton = _createButton( 'icon-close', 
										mw.msg( 'ajax-cancel-all' ), 
										'', 
										mw.msg( 'ajax-cancel-all' ) 
										);
		_saveAllButton.click( that._handleStashedCategories ).hide();
		_cancelAllButton.click( function() { that.resetAll( false ); } ).hide();
		options.$containerNormal.append( _saveAllButton ).append( _cancelAllButton );
		options.$container.append( _addContainer );
	};

	_stash = {
		summaries : [],
		shortSum : [],
		fns : []
	};
	_removeStashItem = function( i ) {
		if ( typeof i != "number" ) {
			i = i.data( 'stashIndex' );
		}
		delete _stash.fns[i];
		delete _stash.summaries[i];
		if ( $.isEmpty( _stash.fns ) ) {
			_stash.fns = [];
			_stash.summaries = [];
			_stash.shortSum = [];
			_saveAllButton.hide();
			_cancelAllButton.hide();
		}
	};
	_hooks = {
		beforeAdd : [],
		beforeChange : [],
		beforeDelete : [],
		afterAdd : [],
		afterChange : [],
		afterDelete : []
	};
	_runHooks = function( oldtext, type, category, categoryNew ) {
		// No hooks registered
		if ( !_hooks[type] ) {	
			return oldtext;
		} else {
			for ( var i = 0; i < _hooks[type].length; i++ ) {
				oldtext = _hooks[type][i]( oldtext, category, categoryNew );
				if ( oldtext === false ) {
					_showError( mw.msg( 'ajax-category-hook-error', category ) );
					return;
				}
			}
			return oldtext;
		}
	};
	/**
	 * Add hooks
	 * Currently available: beforeAdd, beforeChange, beforeDelete,
	 *						afterAdd, afterChange, afterDelete
	 * If the hook function returns false, all changes are aborted.
	 *
	 * @param string type Type of hook to add
	 * @param function fn Hook function. The following vars are passed to it:
	 *									1. oldtext: The wikitext before the hook
	 *									2. category: The deleted, added, or changed category
	 *									3. (only for beforeChange/afterChange): newcategory
	 */
	this.addHook = function( type, fn ) {
		if ( !_hooks[type] || !$.isFunction( fn ) ) {
			return;
		}
		else {
			hooks[type].push( fn );
		}
	};
};

} )( jQuery );