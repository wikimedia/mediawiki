( function () {
	var api = new mw.Api();

	/**
	 * Show the edit summary.
	 *
	 * @private
	 * @param {jQuery} $formNode
	 * @param {Object} response
	 */
	function showEditSummary( $formNode, response ) {
		var $summaryPreview = $formNode.find( '.mw-summary-preview' ).empty();
		var parse = response.parse;

		if ( !parse || !parse.parsedsummary ) {
			return;
		}

		$summaryPreview.append(
			mw.message( 'summary-preview' ).parse(),
			' ',
			$( '<span>' ).addClass( 'comment' ).html( parenthesesWrap( parse.parsedsummary ) )
		);
	}

	/**
	 * Wrap a string in parentheses.
	 *
	 * @private
	 * @param {string} str
	 * @return {string}
	 */
	function parenthesesWrap( str ) {
		if ( str === '' ) {
			return str;
		}
		// There is no equivalent to rawParams
		return mw.message( 'parentheses' ).escaped()
			// .replace() use $ as start of a pattern.
			// $$ is the pattern for '$'.
			// The inner .replace() duplicates any $ and
			// the outer .replace() simplifies the $$.
			.replace( '$1', str.replace( /\$/g, '$$$$' ) );
	}

	/**
	 * Show status indicators.
	 *
	 * @private
	 * @param {Array} indicators
	 */
	function showIndicators( indicators ) {
		// eslint-disable-next-line no-jquery/no-map-util
		indicators = $.map( indicators, function ( indicator, name ) {
			return $( '<div>' )
				.addClass( 'mw-indicator' )
				.attr( 'id', mw.util.escapeIdForAttribute( 'mw-indicator-' + name ) )
				.html( indicator )
				.get( 0 );
		} );
		if ( indicators.length ) {
			mw.hook( 'wikipage.indicators' ).fire( $( indicators ) );
		}

		// Add whitespace between the <div>s because
		// they get displayed with display: inline-block
		var newList = [];
		indicators.forEach( function ( indicator ) {
			newList.push( indicator, document.createTextNode( '\n' ) );
		} );

		$( '.mw-indicators' ).empty().append( newList );
	}

	/**
	 * Show the templates used.
	 *
	 * The formatting here repeats what is done in includes/TemplatesOnThisPageFormatter.php
	 *
	 * @private
	 * @param {Array} templates List of template titles.
	 */
	function showTemplates( templates ) {
		// The .templatesUsed div can be empty, if no templates are in use.
		// In that case, we have to create the required structure.
		var $parent = $( '.templatesUsed' );

		// Find or add the explanation text (the toggler for collapsing).
		var $explanation = $parent.find( '.mw-templatesUsedExplanation p' );
		if ( $explanation.length === 0 ) {
			$explanation = $( '<p>' );
			$parent.append( $( '<div>' )
				.addClass( 'mw-templatesUsedExplanation' )
				.append( $explanation ) );
		}

		// Find or add the list. The makeCollapsible() method is called on this
		// in resources/src/mediawiki.action/mediawiki.action.edit.collapsibleFooter.js
		var $list = $parent.find( 'ul' );
		if ( $list.length === 0 ) {
			$list = $( '<ul>' ).addClass( [ 'mw-editfooter-list', 'mw-collapsible', 'mw-made-collapsible' ] );
			$parent.append( $list );
		}

		if ( templates.length === 0 ) {
			$explanation.msg( 'templatesusedpreview', 0 );
			$list.empty();
			return;
		}

		// Fetch info about all templates, batched because API is limited to 50 at a time.
		$parent.addClass( 'mw-preview-loading-elements-loading' );
		var batchSize = 50;
		var requests = [];
		for ( var batch = 0; batch < templates.length; batch += batchSize ) {
			// Build a list of template names for this batch.
			var titles = templates
				.slice( batch, batch + batchSize )
				.map( function ( template ) {
					return template.title;
				} );
			requests.push( api.post( {
				action: 'query',
				format: 'json',
				formatversion: 2,
				titles: titles,
				prop: 'info',
				// @todo Do we need inlinkcontext here?
				inprop: 'linkclasses|protection',
				intestactions: 'edit'
			} ) );
		}
		$.when.apply( null, requests ).done( function () {
			var templatesAllInfo = [];
			// For the first batch, empty the list in preparation for either adding new items or not needing to.
			for ( var r = 0; r < arguments.length; r++ ) {
				// Response is either the whole argument, or the 0th element of it.
				var response = arguments[ r ][ 0 ] || arguments[ r ];
				var templatesInfo = ( response.query && response.query.pages ) || [];
				templatesInfo.forEach( function ( ti ) {
					templatesAllInfo.push( {
						title: mw.Title.newFromText( ti.title ),
						apiData: ti
					} );
				} );
			}
			// Sort alphabetically.
			templatesAllInfo.sort( function ( t1, t2 ) {
				// Compare titles with the same rules of Title::compare() in PHP.
				if ( t1.title.getNamespaceId() !== t2.title.getNamespaceId() ) {
					return t1.title.getNamespaceId() - t2.title.getNamespaceId();
				} else {
					return t1.title.getMain() === t2.title.getMain() ?
						0 :
						t1.title.getMain() < t2.title.getMain() ? -1 : 1;
				}
			} );

			// Add new template list, and update the list header.
			var $listNew = $( '<ul>' );
			addItemToTemplateListPromise( $listNew, templatesAllInfo, 0 )
				.then( function () {
					$list.html( $listNew.html() );
				} );
			$explanation.msg( 'templatesusedpreview', templatesAllInfo.length );
		} ).always( function () {
			$parent.removeClass( 'mw-preview-loading-elements-loading' );
		} );
	}

	/**
	 * Recursive function to add a template link to the list of templates in use.
	 * This is useful because addItemToTemplateList() might need to make extra API requests to fetch
	 * messages, but we don't want to send parallel requests for these (because they're often the
	 * for the same messages).
	 *
	 * @private
	 * @param {jQuery} $list The `<ul>` to add the item to.
	 * @param {Object} templatesInfo All templates' info, sorted by namespace and title.
	 * @param {number} templateIndex The current item in templatesInfo (0-indexed).
	 * @return {jQuery.Promise}
	 */
	function addItemToTemplateListPromise( $list, templatesInfo, templateIndex ) {
		return addItemToTemplateList( $list, templatesInfo[ templateIndex ] ).then( function () {
			if ( templatesInfo[ templateIndex + 1 ] !== undefined ) {
				return addItemToTemplateListPromise( $list, templatesInfo, templateIndex + 1 );
			}
		} );
	}

	/**
	 * Create list item with relevant links for the given template, and add it to the $list.
	 *
	 * @private
	 * @param {jQuery} $list The `<ul>` to add the item to.
	 * @param {Object} template Template info with which to construct the `<li>`.
	 * @return {jQuery.Promise}
	 */
	function addItemToTemplateList( $list, template ) {
		var canEdit = template.apiData.actions.edit !== undefined;
		var linkClasses = template.apiData.linkclasses || [];
		if ( template.apiData.missing !== undefined && template.apiData.known === undefined ) {
			linkClasses.push( 'new' );
		}
		var $baseLink = $( '<a>' )
			// Additional CSS classes (e.g. link colors) used for links to this template.
			// The following classes might be used here:
			// * new
			// * mw-redirect
			// * any added by the GetLinkColours hook
			.addClass( linkClasses );
		var $link = $baseLink.clone()
			.attr( 'href', template.title.getUrl() )
			.text( template.title.getPrefixedText() );
		var $editLink = $baseLink.clone()
			.attr( 'href', template.title.getUrl( { action: 'edit' } ) )
			.append( mw.msg( canEdit ? 'editlink' : 'viewsourcelink' ) );
		var wordSep = mw.message( 'word-separator' ).escaped();
		return getRestrictionsText( template.apiData.protection || [] )
			.then( function ( restrictionsList ) {
				// restrictionsList is a comma-separated parentheses-wrapped localized list of restriction level names.
				var editLinkParens = parenthesesWrap( $editLink[ 0 ].outerHTML );
				var $li = $( '<li>' ).append( $link, wordSep, editLinkParens, wordSep, restrictionsList );
				$list.append( $li );
			} );
	}

	/**
	 * Get a localized string listing the restriction levels for a template.
	 *
	 * This should match the logic from TemplatesOnThisPageFormatter::getRestrictionsText().
	 *
	 * @private
	 * @param {Array} restrictions Set of protection info objects from the inprop=protection API.
	 * @return {jQuery.Promise}
	 */
	function getRestrictionsText( restrictions ) {
		var msg = '';
		if ( !restrictions ) {
			return $.Deferred().resolve( msg );
		}

		// Record other restriction levels, in case it's protected for others.
		var restrictionLevels = [];
		restrictions.forEach( function ( r ) {
			if ( r.type !== 'edit' ) {
				return;
			}
			if ( r.level === 'sysop' ) {
				msg = mw.msg( 'template-protected' );
			} else if ( r.level === 'autoconfirmed' ) {
				msg = mw.msg( 'template-semiprotected' );
			} else {
				restrictionLevels.push( r.level );
			}
		} );

		// If sysop or autoconfirmed, use that.
		if ( msg !== '' ) {
			return $.Deferred().resolve( msg );
		}

		// Otherwise, if the edit restriction isn't one of the backwards-compatible ones,
		// use the (possibly custom) restriction-level-* messages.
		var msgs = [];
		restrictionLevels.forEach( function ( level ) {
			msgs.push( 'restriction-level-' + level );
		} );
		if ( msgs.length === 0 ) {
			return $.Deferred().resolve( '' );
		}

		// Custom restriction levels don't have their messages loaded, so we have to do that.
		return api.loadMessagesIfMissing( msgs ).then( function () {
			var localizedMessages = msgs.map( function ( m ) {
				// Messages that can be used here include:
				// * restriction-level-sysop
				// * restriction-level-autoconfirmed
				return mw.message( m ).parse();
			} );
			// There's no commaList in JS, so just join with commas (doesn't handle the last item).
			return parenthesesWrap( localizedMessages.join( mw.msg( 'comma-separator' ) ) );
		} );
	}

	/**
	 * Show the language links (Vector-specific).
	 * TODO: Doesn't work in vector-2022 (maybe it doesn't need to?)
	 *
	 * @private
	 * @param {Array} langLinks
	 */
	function showLanguageLinks( langLinks ) {
		var newList = langLinks.map( function ( langLink ) {
			var bcp47 = mw.language.bcp47( langLink.lang );
			// eslint-disable-next-line mediawiki/class-doc
			return $( '<li>' )
				.addClass( 'interlanguage-link interwiki-' + langLink.lang )
				.append( $( '<a>' )
					.attr( {
						href: langLink.url,
						title: langLink.title + ' - ' + langLink.langname,
						lang: bcp47,
						hreflang: bcp47
					} )
					.text( langLink.autonym )
				);
		} );
		var $list = $( '#p-lang ul' ),
			$parent = $list.parent();
		$list.detach().empty().append( newList ).prependTo( $parent );
	}

	/**
	 * Update the various bits of the page based on the response.
	 *
	 * @private
	 * @param {Object} config
	 * @param {Object} response
	 */
	function handleParseResponse( config, response ) {
		var $content;

		// Js config variables and modules.
		if ( response.parse.jsconfigvars ) {
			mw.config.set( response.parse.jsconfigvars );
		}
		if ( response.parse.modules ) {
			mw.loader.load( response.parse.modules.concat(
				response.parse.modulestyles
			) );
		}

		// Indicators.
		showIndicators( response.parse.indicators );

		// Display title.
		if ( response.parse.displaytitle ) {
			$( '#firstHeadingTitle' ).html( response.parse.displaytitle );
		}

		// Categories.
		if ( response.parse.categorieshtml ) {
			$content = $( $.parseHTML( response.parse.categorieshtml ) );
			mw.hook( 'wikipage.categories' ).fire( $content );
			$( '.catlinks[data-mw="interface"]' ).replaceWith( $content );
		}

		// Table of contents.
		if ( response.parse.sections ) {
			/**
			 * Fired when dynamic changes have been made to the table of contents.
			 *
			 * @event ~'wikipage.tableOfContents'
			 * @memberof Hooks
			 * @param {Object[]} sections Metadata about each section, as returned by
			 *   [API:Parse]{@link https://www.mediawiki.org/wiki/Special:MyLanguage/API:Parsing_wikitext}.
			 */
			mw.hook( 'wikipage.tableOfContents' ).fire(
				response.parse.hidetoc ? [] : response.parse.sections
			);
		}

		// Templates.
		if ( response.parse.templates ) {
			showTemplates( response.parse.templates );
		}

		// Limit report.
		if ( response.parse.limitreporthtml ) {
			$( '.limitreport' ).html( response.parse.limitreporthtml )
				.find( '.mw-collapsible' ).makeCollapsible();
		}

		// Language links.
		if ( response.parse.langlinks && mw.config.get( 'skin' ) === 'vector' ) {
			showLanguageLinks( response.parse.langlinks );
		}

		if ( !response.parse.text ) {
			return;
		}

		// Remove preview note, if present (added by Live Preview, etc.).
		config.$previewNode.find( '.previewnote' ).remove();
		// Remove any previous preview
		config.$previewNode.children( '.mw-parser-output' ).remove();

		$content = $( $.parseHTML( response.parse.text ) );

		config.$previewNode.append( $content ).show();

		mw.hook( 'wikipage.content' ).fire( $content );
	}

	/**
	 * Get the unresolved promise of the preview request.
	 *
	 * @private
	 * @param {Object} config
	 * @param {string|number} section
	 * @return {jQuery.Promise}
	 */
	function getParseRequest( config, section ) {
		var params = {
			formatversion: 2,
			action: 'parse',
			title: config.title,
			summary: config.summary,
			prop: ''
		};

		if ( !config.showDiff ) {
			$.extend( params, {
				prop: 'text|indicators|displaytitle|modules|jsconfigvars|categorieshtml|sections|templates|langlinks|limitreporthtml|parsewarningshtml',
				text: config.$textareaNode.textSelection( 'getContents' ),
				pst: true,
				preview: true,
				sectionpreview: section !== '',
				disableeditsection: true,
				useskin: mw.config.get( 'skin' ),
				uselang: mw.config.get( 'wgUserLanguage' )
			} );
			if ( mw.config.get( 'wgUserVariant' ) ) {
				params.variant = mw.config.get( 'wgUserVariant' );
			}
		}
		if ( section === 'new' ) {
			params.section = 'new';
			params.sectiontitle = params.summary;
			delete params.summary;
		}

		return api.post( params, { headers: { 'Promise-Non-Write-API-Action': 'true' } } );
	}

	/**
	 * Get the unresolved promise of the diff view request.
	 *
	 * @private
	 * @param {Object} config
	 * @param {Object[]|null} response
	 */
	function handleDiffResponse( config, response ) {
		var $table = config.$diffNode.find( 'table.diff' );

		if ( response && response[ 0 ].compare.bodies.main ) {
			var diff = response[ 0 ].compare.bodies;

			$table.find( 'tbody' ).html( diff.main );
			mw.hook( 'wikipage.diff' ).fire( $table );
		} else {
			// The diff is empty.
			var $tableCell = $( '<td>' )
				.attr( 'colspan', 4 )
				.addClass( 'diff-notice' )
				.append(
					$( '<div>' )
						.addClass( 'mw-diff-empty' )
						.text( mw.msg( 'diff-empty' ) )
				);
			$table.find( 'tbody' )
				.empty()
				.append(
					$( '<tr>' ).append( $tableCell )
				);
		}
		config.$diffNode.show();
	}

	/**
	 * Get the selectors of elements that should be grayed out while the preview is being generated.
	 *
	 * @memberof module:mediawiki.page.preview
	 * @return {string[]}
	 * @stable
	 */
	function getLoadingSelectors() {
		return [
			// Main
			'.mw-indicators',
			'#firstHeading',
			'#wikiPreview',
			'#wikiDiff',
			'#catlinks',
			'#p-lang',
			// Editing-related
			'.templatesUsed',
			'.limitreport',
			'.mw-summary-preview',
			'.hiddencats'
		];
	}

	/**
	 * Fetch and display a preview of the current editing area.
	 *
	 * @memberof module:mediawiki.page.preview
	 * @param {Object} config Configuration options.
	 * @param {jQuery} [config.$previewNode=$( '#wikiPreview' )] Where the preview should be displayed.
	 * @param {jQuery} [config.$diffNode=$( '#wikiDiff' )] Where diffs should be displayed (if showDiff is set).
	 * @param {jQuery} [config.$formNode=$( '#editform' )] The form node.
	 * @param {jQuery} [config.$textareaNode=$( '#wpTextbox1' )] The edit form's textarea.
	 * @param {jQuery} [config.$spinnerNode=$( '.mw-spinner-preview' )] The loading indicator. This will
	 *   be shown/hidden accordingly while waiting for the XMLHttpRequest to complete.
	 *   Ignored if no $spinnerNode is given.
	 * @param {string} [config.summary=null] The edit summary. If no value is given, the summary is
	 *   fetched from `$( '#wpSummaryWidget' )`.
	 * @param {boolean} [config.showDiff=false] Shows a diff in the preview area instead of the content.
	 * @param {string} [config.title=mw.config.get( 'wgPageName' )] The title of the page being previewed
	 * @param {string[]} [config.loadingSelectors=getLoadingSelectors()] An array of query selectors
	 *   (i.e. '#catlinks') that should be grayed out while the preview is being generated.
	 * @return {jQuery.Promise|undefined} jQuery.Promise or `undefined` if no `$textareaNode` was provided in the config.
	 * @fires Hooks~'wikipage.categories'
	 * @fires Hooks~'wikipage.content'
	 * @fires Hooks~'wikipage.diff'
	 * @fires Hooks~'wikipage.indicators'
	 * @fires Hooks~'wikipage.tableOfContents'
	 * @stable
	 */
	function doPreview( config ) {
		config = $.extend( {
			$previewNode: $( '#wikiPreview' ),
			$diffNode: $( '#wikiDiff' ),
			$formNode: $( '#editform' ),
			$textareaNode: $( '#wpTextbox1' ),
			$spinnerNode: $( '.mw-spinner-preview' ),
			summary: null,
			showDiff: false,
			title: mw.config.get( 'wgPageName' ),
			loadingSelectors: getLoadingSelectors()
		}, config );

		var section = config.$formNode.find( '[name="wpSection"]' ).val();

		if ( !config.$textareaNode || config.$textareaNode.length === 0 ) {
			return;
		}

		// Fetch edit summary, if not already given.
		if ( !config.summary ) {
			var $summaryWidget = $( '#wpSummaryWidget' );
			if ( $summaryWidget.length ) {
				config.summary = OO.ui.infuse( $summaryWidget ).getValue();
			}
		}

		// Show the spinner if it exists.
		if ( config.$spinnerNode && config.$spinnerNode.length ) {
			config.$spinnerNode.show();
		}

		// Gray out the 'copy elements' while we wait for a response.
		var $loadingElements = $( config.loadingSelectors.join( ',' ) );
		$loadingElements.addClass( [ 'mw-preview-loading-elements', 'mw-preview-loading-elements-loading' ] );

		// Acquire a temporary user username before previewing or diffing, so that signatures and
		// user-related magic words display the temp user instead of IP user in the preview. (T331397)
		var tempUserNamePromise = mw.user.acquireTempUserName();

		var parseRequest, diffRequest;

		parseRequest = tempUserNamePromise.then( function () {
			return getParseRequest( config, section );
		} );

		if ( config.showDiff ) {
			config.$previewNode.hide();
			// Hide the table of contents, in case it was previously shown after previewing.
			mw.hook( 'wikipage.tableOfContents' ).fire( [] );

			var contents = config.$textareaNode.textSelection( 'getContents' ),
				sectionTitle = config.summary;

			if ( section === 'new' ) {
				// T293930: Hack to show live diff for new section creation.

				// We concatenate the section heading with the edit box text and pass it to
				// the diff API as the full input text. This is roughly what the server-side
				// does when difference is requested for section edit.
				// The heading is always prepended, we do not bother with editing old rev
				// at this point (`?action=edit&oldid=xxx&section=new`) -- which will require
				// mid-text insertion of the section -- because creation of new section is only
				// possible on latest revision.

				// The section heading text is unconditionally wrapped in <h2> heading and
				// ends with double newlines, except when it's empty. This is for parity with the
				// server-side rendering of the same case.
				sectionTitle = sectionTitle === '' ? '' : '== ' + sectionTitle + ' ==\n\n';

				// Prepend section heading to section text.
				contents = sectionTitle + contents;
			}

			// The compare API returns an error if the title doesn't exist and fromtext is not
			// specified. So we have to account for the possibility that the page was created or
			// deleted after the user started editing. Luckily the parse API returns pageid so we
			// can wait for that.
			// TODO: Show "Warning: This page was deleted after you started editing!"?
			diffRequest = parseRequest.then( function ( parseResponse ) {
				var diffPar = {
					action: 'compare',
					fromtitle: config.title,
					totitle: config.title,
					toslots: 'main',
					// Remove trailing whitespace for consistency with EditPage diffs.
					// TODO trimEnd() when we can use that.
					'totext-main': contents.replace( /\s+$/, '' ),
					'tocontentmodel-main': mw.config.get( 'wgPageContentModel' ),
					topst: true,
					slots: 'main',
					uselang: mw.config.get( 'wgUserLanguage' )
				};
				if ( mw.config.get( 'wgUserVariant' ) ) {
					diffPar.variant = mw.config.get( 'wgUserVariant' );
				}
				if ( section ) {
					diffPar[ 'tosection-main' ] = section;
				}
				if ( parseResponse.parse.pageid === 0 ) {
					diffPar.fromslots = 'main';
					diffPar[ 'fromcontentmodel-main' ] = mw.config.get( 'wgPageContentModel' );
					diffPar[ 'fromtext-main' ] = '';
				}
				return api.post( diffPar );
			} );

		} else if ( config.$diffNode ) {
			config.$diffNode.hide();
		}

		return $.when( parseRequest, diffRequest )
			.done( function ( parseResponse, diffResponse ) {
				showEditSummary( config.$formNode, parseResponse[ 0 ] );

				if ( config.showDiff ) {
					handleDiffResponse( config, diffResponse );
				} else {
					handleParseResponse( config, parseResponse[ 0 ] );
				}

				mw.hook( 'wikipage.editform' ).fire( config.$formNode );
			} )
			.always( function () {
				if ( config.$spinnerNode && config.$spinnerNode.length ) {
					config.$spinnerNode.hide();
				}
				$loadingElements.removeClass( 'mw-preview-loading-elements-loading' );
			} );
	}

	/**
	 * Fetch and display a preview of the current editing area.
	 *
	 * @example
	 * var preview = require( 'mediawiki.page.preview' );
	 * preview.doPreview();
	 *
	 * @exports mediawiki.page.preview
	 */
	module.exports = {
		doPreview: doPreview,
		getLoadingSelectors: getLoadingSelectors
	};

}() );
