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
		},
		isCatNsSensitive = $.inArray( 14, mw.config.get( 'wgCaseSensitiveNamespaces' ) ) !== -1;

	/**
	 * @return {String}
	 */
	function clean( s ) {
		if ( typeof s === 'string' ) {
			return s.replace( /[\x00-\x1f\x23\x3c\x3e\x5b\x5d\x7b\x7c\x7d\x7f\s]+/g, '' );
		}
		return '';
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
				var	pages = data.query.allpages,
					titleArr = $.map( pages, function( page ) {
						return new mw.Title( page.title ).getMainText();
					} );

				$el.suggestions( 'suggestions', titleArr );
			}
		} );
		$el.data( 'suggestions-request', request );
	}

	/**
	 * Replace <nowiki> and comments with unique keys in the page text.
	 *
	 * @param text {String}
	 * @param id {String} Unique key for this nowiki replacement layer call.
	 * @param keys {Array} Array where fragments will be stored in.
	 * @return {String}
	 */
	function replaceNowikis( text, id, keys ) {
		var matches = text.match( /(<nowiki\>[\s\S]*?<\/nowiki>|<\!--[\s\S]*?--\>)/g );
		for ( var i = 0; matches && i < matches.length; i++ ) {
			keys[i] = matches[i];
			text = text.replace( matches[i], '' + id + '-' + i );
		}
		return text;
	}

	/**
	 * Restore <nowiki> and comments from unique keys in the page text.
	 *
	 * @param text {String}
	 * @param id {String} Unique key of the layer to be restored, as passed to replaceNowikis().
	 * @param keys {Array} Array where fragements should be fetched from.
	 * @return {String}
	 */
	function restoreNowikis( text, id, keys ) {
		for ( var i = 0; i < keys.length; i++ ) {
			text = text.replace( '' + id + '-' + i, keys[i] );
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
				name = $.escapeRE( name );
				return !isCatNsSensitive ? makeCaseInsensitive( name ) : name;
			}
			// Otherwise don't include in categoryNSFragment
			return null;
		} ).join( '|' );

		firstChar = '[' + firstChar.toUpperCase() + firstChar.toLowerCase() + ']';
		titleFragment = firstChar + titleFragment.substr( 1 );
		categoryRegex = '\\[\\[(' + categoryNSFragment + ')' + '[ _]*' + ':' + '[ _]*' + titleFragment + '[ _]*' + '(\\|[^\\]]*)?\\]\\]';
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
	 * @param text {String} (optional) Text label of button.
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
			$button.addClass( 'icon-parent' ).append( $icon ).append( mw.html.escape( text ) );
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
	var	ajaxcat = this;

	// Elements tied to this instance
	this.saveAllButton = null;
	this.cancelAllButton = null;
	this.addContainer = null;

	this.request = null;

	// Stash and hooks
	this.stash = {
		dialogDescriptions: [],
		editSummaries: [],
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
		ajaxcat.resolveRedirects( categoryText, function( resolvedCatTitle ) {
			ajaxcat.handleCategoryAdd( $link, resolvedCatTitle, '', false );
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
			$input = ajaxcat.makeSuggestionBox( category,
				ajaxcat.handleEditLink,
				ajaxcat.options.multiEdit ? mw.msg( 'ajax-confirm-ok' ) : mw.msg( 'ajax-confirm-save' )
			);

		$link.after( $input ).hide();

		$input.find( '.mw-addcategory-input' ).focus();

		// Get the editButton associated with this category link,
		// and hide it.
		$link.data( 'editButton' ).hide();

		// Get the deleteButton associated with this category link,
		$link.data( 'deleteButton' )
			// (re)set click handler
			.unbind( 'click' )
			.click( function() {
				// When the delete button is clicked:
				// - Remove the suggestion box
				// - Show the link and it's edit button
				// - (re)set the click handler again
				$input.remove();
				$link.show().data( 'editButton' ).show();
				$( this )
					.unbind( 'click' )
					.click( ajaxcat.handleDeleteLink )
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
		var	input, category, categoryOld,
			sortkey = '', // Wikitext for between '[[Category:Foo' and ']]'.
			$el = $( this ),
			$link = $el.parent().parent().find( 'a:not(.icon)' );

		// Grab category text
		input = $el.parent().find( '.mw-addcategory-input' ).val();

		// Strip sortkey
		var arr = input.split( '|', 2 );
		if ( arr.length > 1 ) {
			category = arr[0];
			sortkey = arr[1];
		}

		// Grab text
		var isAdded = $link.hasClass( 'mw-added-category' );
		ajaxcat.resetCatLink( $link );
		categoryOld = $link.text();

		// If something changed and the new cat is already on the page, delete it.
		if ( categoryOld !== category && ajaxcat.containsCat( category ) ) {
			$link.data( 'deleteButton' ).click();
			return;
		}

		// Resolve redirects
		ajaxcat.resolveRedirects( category, function( resolvedCatTitle ) {
			ajaxcat.handleCategoryEdit( $link, categoryOld, resolvedCatTitle, sortkey, isAdded );
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
			ajaxcat.resetCatLink( $link, $link.hasClass( 'mw-added-category' ) );
			return;
		} else if ( $link.is( '.mw-removed-category' ) ) {
			// It's already removed...
			return;
		}
		ajaxcat.handleCategoryDelete( $link, category );
	};

	/**
	 * When multiEdit mode is enabled,
	 * this is called when the user clicks "save all"
	 * Combines the dialogDescriptions and edit functions.
	 *
	 * @context Element
	 * @return ?
	 */
	this.handleStashedCategories = function() {

		// Remove "holes" in array
		var dialogDescriptions = $.grep( ajaxcat.stash.dialogDescriptions, function( n, i ) {
			return n;
		} );

		if ( dialogDescriptions.length < 1 ) {
			// Nothing to do here.
			ajaxcat.saveAllButton.hide();
			ajaxcat.cancelAllButton.hide();
			return;
		} else {
			dialogDescriptions = dialogDescriptions.join( '<br/>' );
		}

		// Remove "holes" in array
		var summaryShort = $.grep( ajaxcat.stash.editSummaries, function( n,i ) {
			return n;
		} );
		summaryShort = summaryShort.join( ', ' );

		var	fns = ajaxcat.stash.fns;

		ajaxcat.doConfirmEdit( {
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
			dialogDescription: dialogDescriptions,
			editSummary: summaryShort,
			doneFn: function() {
				ajaxcat.resetAll( true );
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
			ajaxcat = this,
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
		$( '#catlinks' ).find( 'li a' ).each( function() {
			ajaxcat.createCatButtons( $( this ) );
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
			ajaxcat.resetAll( false );
		} ).hide();
		options.$containerNormal.append( this.saveAllButton ).append( this.cancelAllButton );
		options.$container.append( this.addContainer );
	},

	/**
	 * Insert a newly added category into the DOM.
	 *
	 * @param catTitle {mw.Title} Category title for which a link should be created.
	 * @return {jQuery}
	 */
	createCatLink: function( catTitle ) {
		var	catName = catTitle.getMainText(),
			$catLinkWrapper = $( this.options.catLinkWrapper ),
			$anchor = $( '<a>' )
				.text( catName )
				.attr( {
					target: '_blank',
					href: catTitle.getUrl()
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
			ajaxcat = this;

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
	 * Execute or queue a category addition.
	 *
	 * @param $link {jQuery} Anchor tag of category link inside #catlinks.
	 * @param catTitle {mw.Title} Instance of mw.Title of the category to be added.
	 * @param catSortkey {String} sort key (optional)
	 * @param noAppend
	 * @return {mw.ajaxCategories}
	 */
	handleCategoryAdd: function( $link, catTitle, catSortkey, noAppend ) {
		var	ajaxcat = this,
			// Suffix is wikitext between '[[Category:Foo' and ']]'.
			suffix = catSortkey ? '|' + catSortkey : '',
			catName = catTitle.getMainText(),
			catFull = catTitle.toText();

		if ( this.containsCat( catName ) ) {
			this.showError( mw.msg( 'ajax-category-already-present', catName ) );
			return this;
		}

		if ( !$link.length ) {
			$link = this.createCatLink( catTitle );
		}

		// Mark red if missing
		$link[(catTitle.exists() === false ? 'addClass' : 'removeClass')]( 'new' );

		this.doConfirmEdit( {
			modFn: function( oldText ) {
				var newText = ajaxcat.runHooks( oldText, 'beforeAdd', catName );
				newText = newText + "\n[[" + catFull + suffix + "]]\n";
				return ajaxcat.runHooks( newText, 'afterAdd', catName );
			},
			dialogDescription: mw.message( 'ajax-add-category-summary', catName ).escaped(),
			editSummary: '+[[' + catFull + ']]',
			doneFn: function( unsaved ) {
				if ( !noAppend ) {
					ajaxcat.options.$container
						.find( '#mw-normal-catlinks > .mw-addcategory-prompt' ).children( 'input' ).hide();
					ajaxcat.options.$container
						.find( '#mw-normal-catlinks ul' ).append( $link.parent() );
				} else {
					// Remove input box & button
					$link.data( 'deleteButton' ).click();

					// Update link text and href
					$link.show().text( catName ).attr( 'href', catTitle.getUrl() );
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
	 * Execute or queue a category edit.
	 *
	 * @param $link {jQuery} Anchor tag of category link in #catlinks.
	 * @param oldCatName {String} Name of category before edit
	 * @param catTitle {mw.Title} Instance of mw.Title for new category
	 * @param catSortkey {String} Sort key of new category link (optional)
	 * @param isAdded {Boolean} True if this is a new link, false if it changed an existing one
	 */
	handleCategoryEdit: function( $link, oldCatName, catTitle, catSortkey, isAdded ) {
		var	ajaxcat = this,
			catName = catTitle.getMainText();

		// Category add needs to be handled differently
		if ( isAdded ) {
			// Pass sortkey back
			this.handleCategoryAdd( $link, catTitle, catSortkey, true );
			return;
		}

		// User didn't change anything, trigger delete
		// @todo Document why it's deleted.
		if ( oldCatName === catName ) {
			$link.data( 'deleteButton' ).click();
			return;
		}

		// Mark red if missing
		$link[(catTitle.exists() === false ? 'addClass' : 'removeClass')]( 'new' );

		var	categoryRegex = buildRegex( oldCatName ),
			editSummary = '[[' + new mw.Title( oldCatName, catNsId ).toText() + ']] -> [[' + catTitle.toText() + ']]';

		ajaxcat.doConfirmEdit({
			modFn: function( oldText ) {
				var	newText = ajaxcat.runHooks( oldText, 'beforeChange', oldCatName, catName ),
					matches = newText.match( categoryRegex );

				// Old cat wasn't found, likely to be transcluded
				if ( !$.isArray( matches ) ) {
					ajaxcat.showError( mw.msg( 'ajax-edit-category-error' ) );
					return false;
				}

				var	suffix = catSortkey ? '|' + catSortkey : matches[0].replace( categoryRegex, '$2' ),
					newCategoryWikitext = '[[' + catTitle + suffix + ']]';

				if ( matches.length > 1 ) {
					// The category is duplicated. Remove all but one match
					for ( var i = 1; i < matches.length; i++ ) {
						oldText = oldText.replace( matches[i], '' );
					}
				}
				newText = oldText.replace( categoryRegex, newCategoryWikitext );

				return ajaxcat.runHooks( newText, 'afterChange', oldCatName, catName );
			},
			dialogDescription: mw.message( 'ajax-edit-category-summary', oldCatName, catName ).escaped(),
			editSummary: editSummary,
			doneFn: function( unsaved ) {
				// Remove input box & button
				$link.data( 'deleteButton' ).click();

				// Update link text and href
				$link.show().text( catName ).attr( 'href', catTitle.getUrl() );
				if ( unsaved ) {
					$link.data( 'origCat', oldCatName ).addClass( 'mw-changed-category' );
				}
			},
			$link: $link,
			action: 'edit'
		});
	},

	/**
	 * Checks the API whether the category in question is a redirect.
	 * Also returns existance info (to color link red/blue)
	 * @param category {String} Name of category to resolve
	 * @param callback {Function} Called with 1 argument (mw.Title object)
	 */
	resolveRedirects: function( category, callback ) {
		if ( !this.options.resolveRedirects ) {
			callback( category, true );
			return;
		}
		var	catTitle = new mw.Title( category, catNsId ),
			queryVars = {
				action:'query',
				titles: catTitle.toString(),
				redirects: 1,
				format: 'json'
			};

		$.getJSON( mw.util.wikiScript( 'api' ), queryVars, function( json ) {
			var	redirect = json.query.redirects,
				exists = !json.query.pages[-1];

			// If it's a redirect 'exists' is for the target, not the origin
			if ( redirect ) {
				// Register existance of redirect origin as well,
				// a non-existent page can't be a redirect.
				mw.Title.exist.set( catTitle.toString(), true );

				// Override title with the redirect target
				catTitle = new mw.Title( redirect[0].to ).getMainText();
			}

			// Register existence
			mw.Title.exist.set( catTitle.toString(), exists );

			callback( catTitle );
		} );
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
			ajaxcat = this;

		deleteButton.click( this.handleDeleteLink );
		editButton.click( ajaxcat.createEditInterface );

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
	 * @param newCat {String} Category name to be checked for.
	 * @return {Boolean}
	 */
	containsCat: function( newCat ) {
		newCat = $.ucFirst( newCat );
		var match = false;
		$.each( this.getCats(), function(i, cat) {
			if ( $.ucFirst( cat ) === newCat ) {
				match = true;
				// Stop once we have a match
				return false;
			}
		} );
		return match;
	},

	/**
	 * Execute or queue a category delete.
	 *
	 * @param $link {jQuery}
	 * @param category
	 * @return ?
	 */
	handleCategoryDelete: function( $link, category ) {
		var	categoryRegex = buildRegex( category, true ),
			ajaxcat = this;

		this.doConfirmEdit({
			modFn: function( oldText ) {
				var newText = ajaxcat.runHooks( oldText, 'beforeDelete', category );
				newText = newText.replace( categoryRegex, '' );

				if ( newText === oldText ) {
					ajaxcat.showError( mw.msg( 'ajax-remove-category-error' ) );
					return false;
				}

				return ajaxcat.runHooks( newText, 'afterDelete', category );
			},
			dialogDescription: mw.message( 'ajax-remove-category-summary', category ).escaped(),
			editSummary: '-[[' + new mw.Title( category, catNsId ) + ']]',
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
			$link.parent().remove();
			return;
		}
		if ( data.origCat && !dontRestoreText ) {
			var catTitle = new mw.Title( data.origCat, catNsId );
			$link.text( catTitle.getMainText() );
			$link.attr( 'href', catTitle.getUrl() );
		}

		$link.removeData();

		// Re-add data
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
		}, ajaxcat = this;

		$.post(
			mw.util.wikiScript( 'api' ),
			getTokenVars,
			function( json ) {
				if ( 'error' in json ) {
					ajaxcat.showError( mw.msg( 'ajax-api-error', json.error.code, json.error.info ) );
					return;
				} else if ( json.query && json.query.pages ) {
					var infos = json.query.pages;
				} else {
					ajaxcat.showError( mw.msg( 'ajax-api-unknown-error' ) );
					return;
				}

				$.each( infos, function( pageid, data ) {
					var	token = data.edittoken,
						timestamp = data.revisions[0].timestamp,
						oldText = data.revisions[0]['*'],
						nowikiKey = mw.user.generateId(), // Unique ID for nowiki replacement
						nowikiFragments = []; // Nowiki fragments will be stored here during the changes

					// Replace all nowiki parts with unique keys..
					oldText = replaceNowikis( oldText, nowikiKey, nowikiFragments );

					// ..then apply the changes to the page text..
					var newText = fn( oldText );
					if ( newText === false ) {
						return;
					}

					// ..and restore the nowiki parts back.
					newText = restoreNowikis( newText, nowikiKey, nowikiFragments );

					var postEditVars = {
						action: 'edit',
						title: page,
						text: newText,
						summary: summary,
						token: token,
						basetimestamp: timestamp,
						format: 'json'
					};

					$.post(
						mw.util.wikiScript( 'api' ),
						postEditVars,
						doneFn,
						'json'
					)
					.error( function( xhr, text, error ) {
						ajaxcat.showError( mw.msg( 'ajax-api-error', text, error ) );
					});
				} );
			},
			'json'
		).error( function( xhr, text, error ) {
			ajaxcat.showError( mw.msg( 'ajax-api-error', text, error ) );
		} );
	},

	/**
	 * This gets called by all action buttons
	 * Displays a dialog to confirm the action
	 * Afterwards do the actual edit.
	 *
	 * @param props {Object}:
	 * - modFn {Function} text-modifying function
	 * - dialogDescription {String} Changes done (HTML for in the dialog, escape before hand if needed)
	 * - editSummary {String} Changes done (text for the edit summary)
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
			ajaxcat = this;

		// Check whether to use multiEdit mode:
		if ( this.options.multiEdit && props.action !== 'all' ) {

			// Stash away
			props.$link
				.data( 'stashIndex', this.stash.fns.length )
				.data( 'summary', props.dialogDescription );

			this.stash.dialogDescriptions.push( props.dialogDescription );
			this.stash.editSummaries.push( props.editSummary );
			this.stash.fns.push( props.modFn );

			this.saveAllButton.show();
			this.cancelAllButton.show();

			// Clear input field after action
			ajaxcat.addContainer.find( '.mw-addcategory-input' ).val( '' );

			// This only does visual changes, fire done and return.
			props.doneFn( true );
			return this;
		}

		// Summary of the action to be taken
		summaryHolder = $( '<p>' )
			.html( '<strong>' + mw.message( 'ajax-category-question' ).escaped() + '</strong><br/>' + props.dialogDescription );

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
			ajaxcat.addProgressIndicator( dialog );
			ajaxcat.doEdit(
				mw.config.get( 'wgPageName' ),
				props.modFn,
				props.editSummary + ': ' + reasonBox.val(),
				function() {
					props.doneFn();

					// Clear input field after successful edit
					ajaxcat.addContainer.find( '.mw-addcategory-input' ).val( '' );

					dialog.dialog( 'close' );
					ajaxcat.removeProgressIndicator( dialog );
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
			delete this.stash.dialogDescriptions[i];
		} catch(e) {}

		if ( $.isEmpty( this.stash.fns ) ) {
			this.stash.fns = [];
			this.stash.dialogDescriptions = [];
			this.stash.editSummaries = [];
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
			ajaxcat = this;

		if ( del ) {
			$del = $links.filter( '.mw-removed-category' ).parent();
		}

		$links.each( function() {
			ajaxcat.resetCatLink( $( this ), false, del );
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
