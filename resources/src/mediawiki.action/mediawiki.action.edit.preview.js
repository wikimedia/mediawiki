/*!
 * Live edit preview.
 */
( function ( mw, $ ) {

	/**
	 * @ignore
	 * @param {jQuery.Event} e
	 */
	function doLivePreview( e ) {
		var isDiff, api, request, postData, copySelectors,
			$wikiPreview, $wikiDiff, $editform, $copyElements, $spinner;

		e.preventDefault();

		isDiff = ( e.target.name === 'wpDiff' );
		$wikiPreview = $( '#wikiPreview' );
		$wikiDiff = $( '#wikiDiff' );
		$editform = $( '#editform' );

		// Show #wikiPreview if it's hidden to be able to scroll to it
		// (if it is hidden, it's also empty, so nothing changes in the rendering)
		$wikiPreview.show();

		// Jump to where the preview will appear
		$wikiPreview[0].scrollIntoView();

		copySelectors = [
			// Main
			'#firstHeading',
			'#wikiPreview',
			'#wikiDiff',
			'#catlinks',
			'.hiddencats',
			'#p-lang',
			// Editing-related
			'.templatesUsed',
			'.limitreport',
			'.mw-summary-preview'
		];
		$copyElements = $( copySelectors.join( ',' ) );

		// Not shown during normal preview, to be removed if present
		$( '.mw-newarticletext' ).remove();

		$spinner = $.createSpinner( {
			size: 'large',
			type: 'block'
		} );
		$wikiPreview.before( $spinner );
		$spinner.css( {
			marginTop: $spinner.height()
		} );

		// Can't use fadeTo because it calls show(), and we might want to keep some elements hidden
		// (e.g. empty #catlinks)
		$copyElements.animate( { opacity: 0.4 }, 'fast' );

		api = new mw.Api();
		postData = {
			action: 'parse',
			uselang: mw.config.get( 'wgUserLanguage' ),
			title: mw.config.get( 'wgPageName' ),
			text: $editform.find( '#wpTextbox1' ).val()
		};

		if ( isDiff ) {
			$wikiPreview.hide();

			// First PST the input, then diff it
			$.extend( postData, {
				onlypst: ''
			} );
			request = api.post( postData ).then( function ( response1 ) {
				var section, postData;
				postData = {
					action: 'query',
					indexpageids: '',
					prop: 'revisions',
					titles: mw.config.get( 'wgPageName' ),
					rvdifftotext: response1.parse.text['*'],
					rvprop: ''
				};
				section = $editform.find( '[name="wpSection"]' ).val();
				if ( section !== '' ) {
					postData.rvsection = section;
				}
				return api.post( postData ).then( function ( result2 ) {
					var diffHtml;
					try {
						diffHtml = result2.query.pages[result2.query.pageids[0]]
							.revisions[0].diff['*'];
					} catch ( e ) {
						// "result.blah is undefined" error, ignore
						mw.log.error( e );
					}
					$wikiDiff.find( 'table.diff tbody' ).html( diffHtml );
					$wikiDiff.show();
				} );
			} );
		} else {
			$wikiDiff.hide();
			$.extend( postData, {
				pst: '',
				disablepp: '',
				preview: '',
				prop: 'text|displaytitle|modules|categorieshtml|templates|langlinks',
				summary: $editform.find( '#wpSummary' ).val()
			} );
			if ( $( '[name="wpSection"]' ).val() !== '' ) {
				postData.sectionpreview = '';
			}
			request = api.post( postData );

			request.then( function ( response ) {
				var li, newList, $next, $parent, $list;
				if ( response.parse.modules ) {
					mw.loader.load( response.parse.modules.concat(
						response.parse.modulescripts,
						response.parse.modulestyles,
						response.parse.modulemessages ) );
				}
				if ( response.parse.displaytitle ) {
					$( '#firstHeading' ).replaceWith( response.parse.displaytitle );
				}
				if ( response.parse.categorieshtml ) {
					$( '#catlinks' ).replaceWith( response.parse.categorieshtml['*'] );
				}
				if ( response.parse.parsedsummary ) {
					$editform.find( '.mw-summary-preview' ).html( response.parse.parsedsummary['*'] );
				}
				if ( response.parse.templates ) {
					newList = [];
					$.each( response.parse.templates, function ( i, template ) {
						li = $( '<li>' )
							.append( $('<a>')
								.attr( {
									'href': mw.util.getUrl( template['*'] ),
									'class': ( template.exists !== undefined ? '' : 'new' )
								} )
								.text( template['*'] )
							);
						newList.push( li );
					} );

					$editform.find( '.mw-editfooter-list' ).detach().empty().append( newList ).appendTo( '.templatesUsed' );
				}
				if ( response.parse.langlinks ) {
					newList = [];
					$.each( response.parse.langlinks, function ( i, langlink ) {
						li = $( '<li>' )
							.addClass( 'interlanguage-link interwiki-' + langlink.lang )
							.append( $( '<a>' )
								.attr( {
									'href': langlink.url,
									'title': langlink['*'] + ' - ' + langlink.langname,
									'lang': langlink.lang,
									'hreflang': langlink.lang
								} )
								.text( langlink.autonym )
							);
						newList.push( li );
					} );
					$list = $( '#p-lang ul' );
					$parent = $list.parent();
					$list.detach().empty().append( newList ).prependTo( $parent );
				}

				if ( response.parse.text['*'] ) {
					$next = $wikiPreview.next();
					// If there is no next node, use parent instead.
					// Only query parent if needed, false otherwise.
					$parent = !$next.length && $wikiPreview.parent();

					$wikiPreview
						.detach()
						.empty()
						.html( response.parse.text['*'] );

					// Reattach
					if ( $parent ) {
						$parent.append( $wikiPreview );
					} else {
						$next.before( $wikiPreview );
					}
					$wikiPreview.show();

					mw.hook( 'wikipage.content' ).fire( $wikiPreview );
				}
			} );
		}
		request.always( function () {
			$spinner.remove();
			$copyElements.animate( {
				opacity: 1
			}, 'fast' );
		} );
	}

	$( function () {
		// Do not enable on user .js/.css pages, as there's no sane way of "previewing"
		// the scripts or styles without reloading the page.
		if ( $( '#mw-userjsyoucanpreview' ).length || $( '#mw-usercssyoucanpreview' ).length ) {
			return;
		}

		// The following elements can change in a preview but are not output
		// by the server when they're empty until the preview response.
		// TODO: Make the server output these always (in a hidden state), so we don't
		// have to fish and (hopefully) put them in the right place (since skins
		// can change where they are output).

		if ( !document.getElementById( 'p-lang' ) && document.getElementById( 'p-tb' ) ) {
			$( '#p-tb' ).after(
				$( '<div>' ).attr( 'id', 'p-lang' )
			);
		}

		if ( !$( '.mw-summary-preview' ).length ) {
			$( '.editCheckboxes' ).before(
				$( '<div>' ).addClass( 'mw-summary-preview' )
			);
		}

		if ( !document.getElementById( 'wikiDiff' ) && document.getElementById( 'wikiPreview' ) ) {
			$( '#wikiPreview' ).after(
				$( '<div>' )
					.attr( 'id', 'wikiDiff' )
					.html( '<table class="diff"><col class="diff-marker"/><col class="diff-content"/>' +
                                '<col class="diff-marker"/><col class="diff-content"/><tbody/></table>' )
			);
		}

		// This should be moved down to '#editform', but is kept on the body for now
		// because the LiquidThreads extension is re-using this module with only half
		// the EditPage (doesn't include #editform presumably, bug 55463).
		$( document.body ).on( 'click', '#wpPreview, #wpDiff', doLivePreview );
	} );

}( mediaWiki, jQuery ) );
