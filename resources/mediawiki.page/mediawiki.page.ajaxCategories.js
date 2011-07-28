/**
 * mediaWiki.page.ajaxCategories
 *
 * @author Michael Dale, 2009
 * @author Leo Koppelkamm, 2011
 * @author Timo Tijhof, 2011
 * @since 1.19
 *
 * Relies on: mw.config (wgFormattedNamespaces, wgNamespaceIds,
 * wgCaseSensitiveNamespaces, wgUserGroups), mw.util.wikiGetlink, mw.user.getId
 */
( function( $ ) {

	/* Local scope */

	var	catNsId = mw.config.get( 'wgNamespaceIds' ).category,
		defaultOptions = {
			catLinkWrapper: '<li>',
			$container: $( '.catlinks' ),
			$containerNormal: $( '#mw-normal-catlinks' ),
			categoryLinkSelector: 'li a:not(.icon)',
			multiEdit: $.inArray( 'user', mw.config.get( 'wgUserGroups' ) ) !== -1,
			resolveRedirects: true
		};

	function clean( s ) {
		if ( s !== undefined ) {
			return s.replace( /[\x00-\x1f\x23\x3c\x3e\x5b\x5d\x7b\x7c\x7d\x7f\s]+/g, '' );
		}
	}

	/**
	 * Build URL for passed Category
	 *
	 * @param cat {String} Category name.
	 * @return {String} Valid URL
	 */
	function catUrl( cat ) {
		return mw.util.wikiGetlink( new mw.Title( cat, catNsId ) );
	}

	/**
	 * Helper function for $.fn.suggestions
	 *
	 * @context {jQuery}
	 * @param value {String} Textbox value.
	 */
	function fetchSuggestions( value ) {
		var	request,
			$el = this,
			catName = clean( value );

		request = $.ajax( {
			url: mw.util.wikiScript( 'api' ),
			data: {
				action: 'query',
				list: 'allpages',
				apnamespace: catNsId,
				apprefix: catName,
				format: 'json'
			},
			dataType: 'json',
			success: function( data ) {
				// Process data.query.allpages into an array of titles
				var	title,
					pages = data.query.allpages,
					titleArr = [];

				$.each( pages, function( i, page ) {
					title = page.title.split( ':', 2 )[1];
					titleArr.push( title );
				} );

				$el.suggestions( 'suggestions', titleArr );
			}
		} );
		$el.data( 'suggestions-request', request );
	}

	/**
	 * Replace <nowiki> and comments with unique keys
	 *
	 * @param text {String}
	 * @param id
	 * @param keys {Array}
	 * @return {String}
	 */
	function replaceNowikis( text, id, keys ) {
		var matches = text.match( /(<nowiki\>[\s\S]*?<\/nowiki>|<\!--[\s\S]*?--\>)/g );
		for ( var i = 0; matches && i < matches.length; i++ ) {
			keys[i] = matches[i];
			text = text.replace( matches[i], id + i + '-' );
		}
		return text;
	}

	/**
	 * Restore <nowiki> and comments from unique keys
	 * @param text {String}
	 * @param id
	 * @param keys {Array}
	 * @return {String}
	 */
	function restoreNowikis( text, id, keys ) {
		for ( var i = 0; i < keys.length; i++ ) {
			text = text.replace( id + i + '-', keys[i] );
		}
		return text;
	}

	/**
	 * Makes regex string caseinsensitive.
	 * Useful when 'i' flag can't be used.
	 * Return stuff like [Ff][Oo][Oo]
	 *
	 * @param string {String} Regex string
	 * @return {String} Processed regex string
	 */
	function makeCaseInsensitive( string ) {
		var newString = '';
		if ( $.inArray( 14, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) !== -1 ) {
			return string;
		}
		for ( var i = 0; i < string.length; i++ ) {
			newString += '[' + string.charAt( i ).toUpperCase() + string.charAt( i ).toLowerCase() + ']';
		}
		return newString;
	}

	/**
	 * Build a regex that matches legal invocations of the passed category.
	 * @param category {String}
	 * @param matchLineBreak {Boolean} Match one following linebreak as well?
	 * @return {RegExp}
	 */
	function buildRegex( category, matchLineBreak ) {
		var	categoryRegex, categoryNSFragment,
			titleFragment = $.escapeRE( category ).replace( /( |_)/g, '[ _]' ),
			firstChar = titleFragment.charAt( 0 );

		// Filter out all names for category namespace
		categoryNSFragment = $.map( mw.config.get( 'wgNamespaceIds' ), function( id, name ) {
			if ( id === catNsId ) {
				return makeCaseInsensitive( $.escapeRE( name ) );
			}
		} ).join( '|' );

		firstChar = '[' + firstChar.toUpperCase() + firstChar.toLowerCase() + ']';
		titleFragment = firstChar + titleFragment.substr( 1 );
		categoryRegex = '\\[\\[(' + categoryNSFragment + '):' + '[ _]*' + titleFragment + '(\\|[^\\]]*)?\\]\\]';
		if ( matchLineBreak ) {
			categoryRegex += '[ \\t\\r]*\\n?';
		}
		return new RegExp( categoryRegex, 'g' );
	}

	/**
	 * Manufacture iconed button, with or without text.
	 *
	 * @param icon {String} The icon class.
	 * @param title {String} Title attribute.
	 * @param className {String} (optional) Additional classes to be added to the button.
	 * @param text {String} (optional) Text of button.
	 * @return {jQuery} The button.
	 */
	function createButton( icon, title, className, text ){
		// We're adding a zero width space for IE7, it's got problems with empty nodes apparently
		var $button = $( '<a>' )
			.addClass( className || '' )
			.attr( 'title', title )
			.html( '&#8203;' );

		if ( text ) {
			var $icon = $( '<span>' ).addClass( 'icon ' + icon ).html( '&#8203;' );
			$button.addClass( 'icon-parent' ).append( $icon ).append( text );
		} else {
			$button.addClass( 'icon ' + icon );
		}
		return $button;
	}

/**
 * @constructor
 * @param
 */
mw.ajaxCategories = function( options ) {

	this.options = options = $.extend( defaultOptions, options );

	// Save scope in shortcut
	var	that = this;

	// Elements tied to this instance
	this.saveAllButton = null;
	this.cancelAllButton = null;
	this.addContainer = null;

	this.request = null;

	// Stash and hooks
	this.stash = {
		summaries: [],
		shortSum: [],
		fns: []
	};
	this.hooks = {
		beforeAdd: [],
		beforeChange: [],
		beforeDelete: [],
		afterAdd: [],
		afterChange: [],
		afterDelete: []
	};

	/* Event handlers */

	/**
	 * Handle add category submit. Not to be called directly.
	 *
	 * @context Element
	 * @param e {jQuery Event}
	 */
	this.handleAddLink = function( e ) {
		var	$el = $( this ),
			$link = $([]),
			categoryText = $.ucFirst( $el.parent().find( '.mw-addcategory-input' ).val() || '' );

		// Resolve redirects
		that.resolveRedirects( categoryText, function( resolvedCat, exists ) {
			that.handleCategoryAdd( $link, resolvedCat, false, exists );
		} );
	};

	/**
	 * @context Element
	 * @param e {jQuery Event}
	 */
	this.createEditInterface = function( e ) {
		var $el = $( this ),
			$link = $el.data( 'link' ),
			category = $link.text(),
			$input = that.makeSuggestionBox( category,
				that.handleEditLink,
				that.options.multiEdit ? mw.msg( 'ajax-confirm-ok' ) : mw.msg( 'ajax-confirm-save' )
			);

		$link.after( $input ).hide();

		$input.find( '.mw-addcategory-input' ).focus();

		$link.data( 'editButton' ).hide();

		$link.data( 'deleteButton' )
			.unbind( 'click' )
			.click( function() {
				$input.remove();
				$link.show().data( 'editButton' ).show();
				$( this )
					.unbind( 'click' )
					.click( that.handleDeleteLink )
					.attr( 'title', mw.msg( 'ajax-remove-category' ) );
			})
			.attr( 'title', mw.msg( 'ajax-cancel' ) );
	};

	/**
	 * Handle edit category submit. Not to be called directly.
	 *
	 * @context Element
	 * @param e {jQuery Event}
	 */
	this.handleEditLink = function( e ) {
		var categoryNew,
			$el = $( this ),
			$link = $el.parent().parent().find( 'a:not(.icon)' ),
			sortkey = '';

		// Grab category text
		categoryNew = $el.parent().find( '.mw-addcategory-input' ).val();
		categoryNew = $.ucFirst( categoryNew.replace( /_/g, ' ' ) );

		// Strip sortkey
		var arr = categoryNew.split( '|' );
		if ( arr.length > 1 ) {
			categoryNew = arr.shift();
			sortkey = '|' + arr.join( '|' );
		}

		// Grab text
		var added = $link.hasClass( 'mw-added-category' );
		that.resetCatLink( $link );
		var category = $link.text();

		// Check for dupes ( exluding itself )
		if ( category !== categoryNew && that.containsCat( categoryNew ) ) {
			$link.data( 'deleteButton' ).click();
			return;
		}

		// Resolve redirects
		that.resolveRedirects( categoryNew, function( resolvedCat, exists ) {
			that.handleCategoryEdit( $link, category, resolvedCat, sortkey, exists, added );
		});
	};

	/**
	 * Handle delete category submit. Not to be called directly.
	 *
	 * @context Element
	 * @param e {jQuery Event}
	 */
	this.handleDeleteLink = function( e ) {
		var	$el = $( this ),
			$link = $el.parent().find( 'a:not(.icon)' ),
			category = $link.text();

		if ( $link.is( '.mw-added-category, .mw-changed-category' ) ) {
			// We're just cancelling the addition or edit
			that.resetCatLink( $link, $link.hasClass( 'mw-added-category' ) );
			return;
		} else if ( $link.is( '.mw-removed-category' ) ) {
			// It's already removed...
			return;
		}
		that.handleCategoryDelete( $link, category );
	};

	/**
	 * When multiEdit mode is enabled,
	 * this is called when the user clicks "save all"
	 * Combines the summaries and edit functions.
	 *
	 * @context Element
	 * @return ?
	 */
	this.handleStashedCategories = function() {

		// Remove "holes" in array
		var summary = $.grep( that.stash.summaries, function( n, i ) {
			return n;
		} );

		if ( summary.length < 1 ) {
			// Nothing to do here.
			that.saveAllButton.hide();
			that.cancelAllButton.hide();
			return;
		} else {
			summary = summary.join( '<br/>' );
		}

		// Remove "holes" in array
		var summaryShort = $.grep( that.stash.shortSum, function( n,i ) {
			return n;
		} );
		summaryShort = summaryShort.join( ', ' );

		var	fns = that.stash.fns;

		that.doConfirmEdit( {
			modFn: function( oldtext ) {
				// Run the text through all action functions
				var newtext = oldtext;
				for ( var i = 0; i < fns.length; i++ ) {
					if ( $.isFunction( fns[i] ) ) {
						newtext = fns[i]( newtext );
						if ( newtext === false ) {
							return false;
						}
					}
				}
				return newtext;
			},
			actionSummary: summary,
			shortSummary: summaryShort,
			doneFn: function() {
				that.resetAll( true );
			},
			$link: null,
			action: 'all'
		} );
	};
};

/* Public methods */

mw.ajaxCategories.prototype = {
	/**
	 * Create the UI
	 */
	setup: function() {
		// Could be set by gadgets like HotCat etc.
		if ( mw.config.get( 'disableAJAXCategories' ) ) {
			return false;
		}
		// Only do it for articles.
		if ( !mw.config.get( 'wgIsArticle' ) ) {
			return;
		}
		var options = this.options,
			that = this,
			// Create [Add Category] link
			$addLink = createButton( 'icon-add',
				mw.msg( 'ajax-add-category' ),
				'mw-ajax-addcategory',
				mw.msg( 'ajax-add-category' )
			).click( function() {
				$( this ).nextAll().toggle().filter( '.mw-addcategory-input' ).focus();
			});

		// Create add category prompt
		this.addContainer = this.makeSuggestionBox( '', this.handleAddLink, mw.msg( 'ajax-add-category-submit' ) );
		this.addContainer.children().hide();
		this.addContainer.prepend( $addLink );

		// Create edit & delete link for each category.
		$( '#catlinks li a' ).each( function() {
			that.createCatButtons( $( this ) );
		});

		options.$containerNormal.append( this.addContainer );

		// @todo Make more clickable
		this.saveAllButton = createButton( 'icon-tick',
			mw.msg( 'ajax-confirm-save-all' ),
			'',
			mw.msg( 'ajax-confirm-save-all' )
		);
		this.cancelAllButton = createButton( 'icon-close',
			mw.msg( 'ajax-cancel-all' ),
			'',
			mw.msg( 'ajax-cancel-all' )
		);
		this.saveAllButton.click( this.handleStashedCategories ).hide();
		this.cancelAllButton.click( function() {
			that.resetAll( false );
		} ).hide();
		options.$containerNormal.append( this.saveAllButton ).append( this.cancelAllButton );
		options.$container.append( this.addContainer );
	},

	/**
	 * Insert a newly added category into the DOM.
	 *
	 * @param cat {String} Category name.
	 * @return {jQuery}
	 */
	createCatLink: function( cat ) {
		// User can implicitely state a sort key.
		// Remove before display.
		// strip out bad characters
		cat = clean( cat.replace( /\|.*/, '' ) );

		if ( $.isEmpty( cat ) || this.containsCat( cat ) ) {
			return;
		}

		var	$catLinkWrapper = $( this.options.catLinkWrapper ),
			$anchor = $( '<a>' )
				.append( cat )
				.attr( {
					target: '_blank',
					href: catUrl( cat )
				} );

		$catLinkWrapper.append( $anchor );

		this.createCatButtons( $anchor );

		return $anchor;
	},

	/**
	 * Create a suggestion box for use in edit/add dialogs
	 * @param prefill {String} Prefill input
	 * @param callback {Function} Called on submit
	 * @param buttonVal {String} Button text
	 */
	makeSuggestionBox: function( prefill, callback, buttonVal ) {
		// Create add category prompt
		var	$promptContainer = $( '<div class="mw-addcategory-prompt"></div>' ),
			$promptTextbox = $( '<input type="text" size="30" class="mw-addcategory-input"></input>' ),
			$addButton = $( '<input type="button" class="mw-addcategory-button"></input>' ),
			that = this;

		if ( prefill !== '' ) {
			$promptTextbox.val( prefill );
		}

		$addButton
			.val( buttonVal )
			.click( callback );

		$promptTextbox
			.keyup( function( e ) {
				if ( e.keyCode === 13 ) {
					$addButton.click();
				}
			} )
			.suggestions( {
				fetch: fetchSuggestions,
				cancel: function() {
					var req = this.data( 'suggestions-request' );
					// XMLHttpRequest.abort is unimplemented in IE6, also returns nonstandard value of 'unknown' for typeof
					if ( req && typeof req.abort !== 'unknown' && typeof req.abort !== 'undefined' && req.abort ) {
						req.abort();
					}
				}
			} )
			.suggestions();

		$promptContainer
			.append( $promptTextbox )
			.append( $addButton );

		return $promptContainer;
	},

	/**
	 * Execute or queue an category add.
	 * @param $link {jQuery}
	 * @param category
	 * @param noAppend
	 * @param exists
	 * @return {mw.ajaxCategories}
	 */
	handleCategoryAdd: function( $link, category, noAppend, exists ) {
		// Handle sortkey
		var	arr = category.split( '|' ),
			sortkey = '',
			that = this;

		if ( arr.length > 1 ) {
			category = arr.shift();
			sortkey = '|' + arr.join( '|' );
			if ( sortkey === '|' ) {
				sortkey = '';
			}
		}

		if ( !$link.length ) {
			$link = this.createCatLink( category );
		}

		if ( this.containsCat( category ) ) {
			this.showError( mw.msg( 'ajax-category-already-present', category ) );
			return this;
		}

		// Sometimes createCatLink returns undefined/null, previously caused an exception
		// in the following lines, catching now.. @todo
		if ( !$link ) {
			this.showError( 'Unexpected error occurred. $link undefined.' );
			return this;
		}

		// Mark red if missing
		$link.toggleClass( 'new', exists !== true );

		// Replace underscores
		category = category.replace( /_/g, ' ' );
		var catFull = new mw.Title( category, catNsId ).toString().replace( /_/g, ' ' );

		this.doConfirmEdit( {
			modFn: function( oldText ) {
				var newText = that.runHooks( oldText, 'beforeAdd', category );
				newText = newText + "\n[[" + catFull + sortkey + "]]\n";
				return that.runHooks( newText, 'afterAdd', category );
			},
			actionSummary: mw.msg( 'ajax-add-category-summary', category ),
			shortSummary: '+[[' + catFull + ']]',
			doneFn: function( unsaved ) {
				if ( !noAppend ) {
					that.options.$container
						.find( '#mw-normal-catlinks > .mw-addcategory-prompt' ).children( 'input' ).hide();
					that.options.$container
						.find( '#mw-normal-catlinks ul' ).append( $link.parent() );
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
			$link: $link,
			action: 'add'
		} );
		return this;
	},

	/**
	 * Execute or queue an category edit.
	 * @param $link {jQuery}
	 * @param category
	 * @param categoryNew
	 * @param sortkeyNew
	 * @param exists {Boolean}
	 * @param added {Boolean}
	 */
	handleCategoryEdit: function( $link, category, categoryNew, sortkeyNew, exists, added ) {
		var that = this;

		// Category add needs to be handled differently
		if ( added ) {
			// Pass sortkey back
			that.handleCategoryAdd( $link, categoryNew + sortkeyNew, true );
			return;
		}

		// User didn't change anything.
		if ( category === categoryNew + sortkeyNew ) {
			$link.data( 'deleteButton' ).click();
			return;
		}

		// Mark red if missing
		$link[(exists === false ? 'addClass' : 'removeClass')]( 'new' );

		var	categoryRegex = buildRegex( category ),
			shortSummary = '[[' + new mw.Title( category, catNsId ) + ']] -> [[' + new mw.Title( categoryNew, catNsId ) + ']]';
		that.doConfirmEdit({
			modFn: function( oldText ) {
				var	sortkey, newCategoryString,
					newText = that.runHooks( oldText, 'beforeChange', category, categoryNew ),
					matches = newText.match( categoryRegex );

				//Old cat wasn't found, likely to be transcluded
				if ( !$.isArray( matches ) ) {
					that.showError( mw.msg( 'ajax-edit-category-error' ) );
					return false;
				}
				sortkey = sortkeyNew || matches[0].replace( categoryRegex, '$2' );
				newCategoryString = '[[' + new mw.Title( categoryNew, catNsId ) + sortkey + ']]';

				if ( matches.length > 1 ) {
					// The category is duplicated.
					// Remove all but one match
					for ( var i = 1; i < matches.length; i++ ) {
						oldText = oldText.replace( matches[i], '' );
					}
				}
				newText = oldText.replace( categoryRegex, newCategoryString );

				return that.runHooks( newText, 'afterChange', category, categoryNew );
			},
			actionSummary: mw.msg( 'ajax-edit-category-summary', category, categoryNew ),
			shortSummary: shortSummary,
			doneFn: function( unsaved ) {
				// Remove input box & button
				$link.data( 'deleteButton' ).click();

				// Update link text and href
				$link.show().text( categoryNew ).attr( 'href', catUrl( categoryNew ) );
				if ( unsaved ) {
					$link.data( 'origCat', category ).addClass( 'mw-changed-category' );
				}
			},
			$link: $link,
			action: 'edit'
		});
	},

	/**
	 * Checks the API whether the category in question is a redirect.
	 * Also returns existance info (to color link red/blue)
	 * @param string category.
	 * @param function callback
	 */
	resolveRedirects: function( category, callback ) {
		if ( !this.options.resolveRedirects ) {
			callback( category, true );
			return;
		}
		var queryVars = {
			action:'query',
			titles: new mw.Title( category, catNsId ).toString(),
			redirects: '',
			format: 'json'
		};

		$.get( mw.util.wikiScript( 'api' ), queryVars,
			function( reply ) {
				var redirect = reply.query.redirects;
				if ( redirect ) {
					category = new mw.Title( redirect[0].to )._name;
				}
				callback( category, !reply.query.pages[-1] );
			}, 'json'
		);
	},

	/**
	 * Append edit and remove buttons to a given category link
	 *
	 * @param DOMElement element Anchor element, to which the buttons should be appended.
	 * @return {mw.ajaxCategories}
	 */
	createCatButtons: function( $element ) {
		var	deleteButton = createButton( 'icon-close', mw.msg( 'ajax-remove-category' ) ),
			editButton = createButton( 'icon-edit', mw.msg( 'ajax-edit-category' ) ),
			saveButton = createButton( 'icon-tick', mw.msg( 'ajax-confirm-save' ) ).hide(),
			that = this;

		deleteButton.click( this.handleDeleteLink );
		editButton.click( that.createEditInterface );

		$element.after( deleteButton ).after( editButton );

		// Save references to all links and buttons
		$element.data( {
			deleteButton: deleteButton,
			editButton: editButton,
			saveButton: saveButton
		} );
		editButton.data( {
			link: $element
		} );
		return this;
	},

	/**
	 * Append spinner wheel to element.
	 * @param $el {jQuery}
	 * @return {mw.ajaxCategories}
	 */
	addProgressIndicator: function( $el ) {
		$el.append( $( '<div>' ).addClass( 'mw-ajax-loader' ) );
		return this;
	},

	/**
	 * Find and remove spinner wheel from inside element.
	 * @param $el {jQuery}
	 * @return {mw.ajaxCategories}
	 */
	removeProgressIndicator: function( $el ) {
		$el.find( '.mw-ajax-loader' ).remove();
		return this;
	},

	/**
	 * Parse the DOM $container and build a list of
	 * present categories.
	 *
	 * @return {Array} All categories.
	 */
	getCats: function() {
		var cats = this.options.$container
				.find( this.options.categoryLinkSelector )
				.map( function() {
					return $.trim( $( this ).text() );
				} );
		return cats;
	},

	/**
	 * Check whether a passed category is present in the DOM.
	 *
	 * @return {Boolean}
	 */
	containsCat: function( cat ) {
		cat = $.ucFirst( cat );
		return this.getCats().filter( function() {
			return $.ucFirst( this ) === cat;
		} ).length !== 0;
	},

	/**
	 * Execute or queue an category delete.
	 *
	 * @param $link {jQuery}
	 * @param category
	 * @return ?
	 */
	handleCategoryDelete: function( $link, category ) {
		var	categoryRegex = buildRegex( category, true ),
			that = this;

		that.doConfirmEdit({
			modFn: function( oldText ) {
				var newText = that.runHooks( oldText, 'beforeDelete', category );
				newText = newText.replace( categoryRegex, '' );

				if ( newText === oldText ) {
					that.showError( mw.msg( 'ajax-remove-category-error' ) );
					return false;
				}

				return that.runHooks( newText, 'afterDelete', category );
			},
			actionSummary: mw.msg( 'ajax-remove-category-summary', category ),
			shortSummary: '-[[' + new mw.Title( category, catNsId ) + ']]',
			doneFn: function( unsaved ) {
				if ( unsaved ) {
					$link.addClass( 'mw-removed-category' );
				} else {
					$link.parent().remove();
				}
			},
			$link: $link,
			action: 'delete'
		});
	},

	/**
	 * Takes a category link element
	 * and strips all data from it.
	 *
	 * @param $link {jQuery}
	 * @param del {Boolean}
	 * @param dontRestoreText {Boolean}
	 * @return ?
	 */
	resetCatLink: function( $link, del, dontRestoreText ) {
		$link.removeClass( 'mw-removed-category mw-added-category mw-changed-category' );
		var data = $link.data();

		if ( typeof data.stashIndex === 'number' ) {
			this.removeStashItem( data.stashIndex );
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

		// Readd static.
		$link.data( {
			saveButton: data.saveButton,
			deleteButton: data.deleteButton,
			editButton: data.editButton
		} );
	},

	/**
	 * Do the actual edit.
	 * Gets token & text from api, runs it through fn
	 * and saves it with summary.
	 * @param page {String} Pagename
	 * @param fn {Function} edit function
	 * @param summary {String}
	 * @param doneFn {String} Callback after all is done
	 */
	doEdit: function( page, fn, summary, doneFn ) {
		// Get an edit token for the page.
		var getTokenVars = {
			action: 'query',
			prop: 'info|revisions',
			intoken: 'edit',
			titles: page,
			rvprop: 'content|timestamp',
			format: 'json'
		}, that = this;

		$.post(
			mw.util.wikiScript( 'api' ),
			getTokenVars,
			function( reply ) {
				var infos = reply.query.pages;
				$.each( infos, function( pageid, data ) {
					var token = data.edittoken;
					var timestamp = data.revisions[0].timestamp;
					var oldText = data.revisions[0]['*'];

					// Replace all nowiki and comments with unique keys
					var key = mw.user.generateId();
					var nowiki = [];
					oldText = replaceNowikis( oldText, key, nowiki );

					// Then do the changes
					var newText = fn( oldText );
					if ( newText === false ) {
						return;
					}

					// And restore them back
					newText = restoreNowikis( newText, key, nowiki );

					var postEditVars = {
						action: 'edit',
						title: page,
						text: newText,
						summary: summary,
						token: token,
						basetimestamp: timestamp,
						format: 'json'
					};

					$.post( mw.util.wikiScript( 'api' ), postEditVars, doneFn, 'json' )
					 .error( function( xhr, text, error ) {
						that.showError( mw.msg( 'ajax-api-error', text, error ) );
					});
				} );
			},
			'json'
		).error( function( xhr, text, error ) {
			that.showError( mw.msg( 'ajax-api-error', text, error ) );
		} );
	},

	/**
	 * This gets called by all action buttons
	 * Displays a dialog to confirm the action
	 * Afterwards do the actual edit.
	 *
	 * @param props {Object}:
	 * - modFn {Function} text-modifying function
	 * - actionSummary {String} Changes done
	 * - shortSummary {String} Changes, short version
	 * - doneFn {Function} callback after everything is done
	 * - $link {jQuery}
	 * - action
	 * @return {mw.ajaxCategories}
	 */
	doConfirmEdit: function( props ) {
		var summaryHolder, reasonBox, dialog, submitFunction,
			buttons = {},
			dialogOptions = {
				AutoOpen: true,
				buttons: buttons,
				width: 450
			},
			that = this;

		// Check whether to use multiEdit mode:
		if ( this.options.multiEdit && props.action !== 'all' ) {

			// Stash away
			props.$link
				.data( 'stashIndex', this.stash.fns.length )
				.data( 'summary', props.actionSummary );

			this.stash.summaries.push( props.actionSummary );
			this.stash.shortSum.push( props.shortSummary );
			this.stash.fns.push( props.modFn );

			this.saveAllButton.show();
			this.cancelAllButton.show();

			// Clear input field after action
			that.addContainer.find( '.mw-addcategory-input' ).val( '' );

			// This only does visual changes, fire done and return.
			props.doneFn( true );
			return this;
		}

		// Summary of the action to be taken
		summaryHolder = $( '<p>' )
			.html( '<strong>' + mw.msg( 'ajax-category-question' ) + '</strong><br/>' + props.actionSummary );

		// Reason textbox.
		reasonBox = $( '<input type="text" size="45"></input>' )
			.addClass( 'mw-ajax-confirm-reason' );

		// Produce a confirmation dialog
		dialog = $( '<div>' )
			.addClass( 'mw-ajax-confirm-dialog' )
			.attr( 'title', mw.msg( 'ajax-confirm-title' ) )
			.append( summaryHolder )
			.append( reasonBox );

		// Submit button
		submitFunction = function() {
			that.addProgressIndicator( dialog );
			that.doEdit(
				mw.config.get( 'wgPageName' ),
				props.modFn,
				props.shortSummary + ': ' + reasonBox.val(),
				function() {
					props.doneFn();

					// Clear input field after successful edit
					that.addContainer.find( '.mw-addcategory-input' ).val( '' );

					dialog.dialog( 'close' );
					that.removeProgressIndicator( dialog );
				}
			);
		};

		buttons[mw.msg( 'ajax-confirm-save' )] = submitFunction;

		dialog.dialog( dialogOptions ).keyup( function( e ) {
			// Close on enter
			if ( e.keyCode === 13 ) {
				submitFunction();
			}
		} );

		return this;
	},

	/**
	 * @param index {Number|jQuery} Stash index or jQuery object of stash item.
	 * @return {mw.ajaxCategories}
	 */
	removeStashItem: function( i ) {
		if ( typeof i !== 'number' ) {
			i = i.data( 'stashIndex' );
		}

		try {
			delete this.stash.fns[i];
			delete this.stash.summaries[i];
		} catch(e) {}

		if ( $.isEmpty( this.stash.fns ) ) {
			this.stash.fns = [];
			this.stash.summaries = [];
			this.stash.shortSum = [];
			this.saveAllButton.hide();
			this.cancelAllButton.hide();
		}
		return this;
	},

	/**
	 * Reset all data from the category links and the stash.
	 *
	 * @param del {Boolean} Delete any category links with .mw-removed-category
	 * @return {mw.ajaxCategories}
	 */
	resetAll: function( del ) {
		var	$links = this.options.$container.find( this.options.categoryLinkSelector ),
			$del = $([]),
			that = this;

		if ( del ) {
			$del = $links.filter( '.mw-removed-category' ).parent();
		}

		$links.each( function() {
			that.resetCatLink( $( this ), false, del );
		} );

		$del.remove();

		this.options.$container.find( '#mw-hidden-catlinks' ).remove();

		return this;
	},

	/**
	 * Add hooks
	 * Currently available: beforeAdd, beforeChange, beforeDelete,
	 *						afterAdd, afterChange, afterDelete
	 * If the hook function returns false, all changes are aborted.
	 *
	 * @param string type Type of hook to add
	 * @param function fn Hook function. The following vars are passed to it:
	 *	1. oldtext: The wikitext before the hook
	 *	2. category: The deleted, added, or changed category
	 *	3. (only for beforeChange/afterChange): newcategory
	 */
	addHook: function( type, fn ) {
		if ( !this.hooks[type] || !$.isFunction( fn ) ) {
			return;
		}
		else {
			this.hooks[type].push( fn );
		}
	},


	/**
	 * Open a dismissable error dialog
	 *
	 * @param string str The error description
	 */
	showError: function( str ) {
		var	oldDialog = $( '.mw-ajax-confirm-dialog' ),
			buttons = {},
			dialogOptions = {
				buttons: buttons,
				AutoOpen: true,
				title: mw.msg( 'ajax-error-title' )
			};

		this.removeProgressIndicator( oldDialog );
		oldDialog.dialog( 'close' );

		var dialog = $( '<div>' ).text( str );

		mw.util.$content.append( dialog );

		buttons[mw.msg( 'ajax-confirm-ok' )] = function( e ) {
			dialog.dialog( 'close' );
		};

		dialog.dialog( dialogOptions ).keyup( function( e ) {
			if ( e.keyCode === 13 ) {
				dialog.dialog( 'close' );
			}
		} );
	},

	/**
	 * @param oldtext
	 * @param type
	 * @param category
	 * @param categoryNew
	 * @return oldtext
	 */
	runHooks: function( oldtext, type, category, categoryNew ) {
		// No hooks registered
		if ( !this.hooks[type] ) {
			return oldtext;
		} else {
			for ( var i = 0; i < this.hooks[type].length; i++ ) {
				oldtext = this.hooks[type][i]( oldtext, category, categoryNew );
				if ( oldtext === false ) {
					this.showError( mw.msg( 'ajax-category-hook-error', category ) );
					return;
				}
			}
			return oldtext;
		}
	}
};

} )( jQuery );
