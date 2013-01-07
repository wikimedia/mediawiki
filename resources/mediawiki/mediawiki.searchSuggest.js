/**
 * Add search suggestions to the search form.
 */
( function ( mw, $ ) {
	$( document ).ready( function ( $ ) {
		var map, resultRenderCache, searchboxesSelectors,
			// Region where the suggestions box will appear directly below
			// (using the same width). Can be a container element or the input
			// itself, depending on what suits best in the environment.
			// For Vector the suggestion box should align with the simpleSearch
			// container's borders, in other skins it should align with the input
			// element (not the search form, as that would leave the buttons
			// vertically between the input and the suggestions).
			$searchRegion = $( '#simpleSearch, #searchInput' ).first(),
			$searchInput = $( '#searchInput' );

		// Compatibility map
		map = {
			browsers: {
				// Left-to-right languages
				ltr: {
					// SimpleSearch is broken in Opera < 9.6
					opera: [['>=', 9.6]],
					docomo: false,
					blackberry: false,
					ipod: false,
					iphone: false
				},
				// Right-to-left languages
				rtl: {
					opera: [['>=', 9.6]],
					docomo: false,
					blackberry: false,
					ipod: false,
					iphone: false
				}
			}
		};

		if ( !$.client.test( map ) ) {
			return;
		}

		// Compute form data for search suggestions functionality.
		function computeResultRenderCache( context ) {
			var $form, formAction, baseHref, linkParams;

			// Compute common parameters for links' hrefs
			$form = context.config.$region.closest( 'form' );

			formAction = $form.attr( 'action' );
			baseHref = formAction + ( formAction.match(/\?/) ? '&' : '?' );

			linkParams = {};
			$.each( $form.serializeArray(), function ( idx, obj ) {
				linkParams[ obj.name ] = obj.value;
			} );

			return {
				textParam: context.data.$textbox.attr( 'name' ),
				linkParams: linkParams,
				baseHref: baseHref
			};
		}

		// The function used to render the suggestions.
		function renderFunction( text, context ) {
			if ( !resultRenderCache ) {
				resultRenderCache = computeResultRenderCache( context );
			}

			// linkParams object is modified and reused
			resultRenderCache.linkParams[ resultRenderCache.textParam ] = text;

			// this is the container <div>, jQueryfied
			this
				.append(
					// the <span> is needed for $.autoEllipsis to work
					$( '<span>' )
						.css( 'whiteSpace', 'nowrap' )
						.text( text )
				)
				.wrap(
					$( '<a>' )
						.attr( 'href', resultRenderCache.baseHref + $.param( resultRenderCache.linkParams ) )
						.addClass( 'mw-searchSuggest-link' )
				);
		}

		function specialRenderFunction( query, context ) {
			var $el = this;

			if ( !resultRenderCache ) {
				resultRenderCache = computeResultRenderCache( context );
			}

			// linkParams object is modified and reused
			resultRenderCache.linkParams[ resultRenderCache.textParam ] = query;

			if ( $el.children().length === 0 ) {
				$el
					.append(
						$( '<div>' )
							.addClass( 'special-label' )
							.text( mw.msg( 'searchsuggest-containing' ) ),
						$( '<div>' )
							.addClass( 'special-query' )
							.text( query )
							.autoEllipsis()
					)
					.show();
			} else {
				$el.find( '.special-query' )
					.text( query )
					.autoEllipsis();
			}

			if ( $el.parent().hasClass( 'mw-searchSuggest-link' ) ) {
				$el.parent().attr( 'href', resultRenderCache.baseHref + $.param( resultRenderCache.linkParams ) + '&fulltext=1' );
			} else {
				$el.wrap(
					$( '<a>' )
						.attr( 'href', resultRenderCache.baseHref + $.param( resultRenderCache.linkParams ) + '&fulltext=1' )
						.addClass( 'mw-searchSuggest-link' )
				);
			}
		}

		// General suggestions functionality for all search boxes
		searchboxesSelectors = [
			// Primary searchbox on every page in standard skins
			'#searchInput',
			// Secondary searchbox in legacy skins (LegacyTemplate::searchForm uses id "searchInput + unique id")
			'#searchInput2',
			// Special:Search
			'#powerSearchText',
			'#searchText',
			// Generic selector for skins with multiple searchboxes (used by CologneBlue)
			'.mw-searchInput'
		];
		$( searchboxesSelectors.join(', ') )
			.suggestions( {
				fetch: function ( query ) {
					var $el, jqXhr;

					if ( query.length !== 0 ) {
						$el = $(this);
						jqXhr = $.ajax( {
							url: mw.util.wikiScript( 'api' ),
							data: {
								format: 'json',
								action: 'opensearch',
								search: query,
								namespace: 0,
								suggest: ''
							},
							dataType: 'json',
							success: function ( data ) {
								if ( $.isArray( data ) && data.length ) {
									$el.suggestions( 'suggestions', data[1] );
								}
							}
						});
						$el.data( 'request', jqXhr );
					}
				},
				cancel: function () {
					var jqXhr = $(this).data( 'request' );
					// If the delay setting has caused the fetch to have not even happened
					// yet, the jqXHR object will have never been set.
					if ( jqXhr && $.isFunction( jqXhr.abort ) ) {
						jqXhr.abort();
						$(this).removeData( 'request' );
					}
				},
				result: {
					render: renderFunction,
					select: function ( $input ) {
						$input.closest( 'form' ).submit();
					}
				},
				delay: 120,
				highlightInput: true
			} )
			.bind( 'paste cut drop', function () {
				// make sure paste and cut events from the mouse and drag&drop events
				// trigger the keypress handler and cause the suggestions to update
				$( this ).trigger( 'keypress' );
			} );

		// Ensure that the thing is actually present!
		if ( $searchRegion.length === 0 ) {
			// Don't try to set anything up if simpleSearch is disabled sitewide.
			// The loader code loads us if the option is present, even if we're
			// not actually enabled (anymore).
			return;
		}

		// Placeholder text for search box
		$searchInput
			.attr( 'placeholder', mw.msg( 'searchsuggest-search' ) )
			.placeholder();

		// Special suggestions functionality for skin-provided search box
		$searchInput.suggestions( {
			result: {
				render: renderFunction,
				select: function ( $input ) {
					$input.closest( 'form' ).submit();
				}
			},
			special: {
				render: specialRenderFunction,
				select: function ( $input ) {
					$input.closest( 'form' ).append(
						$( '<input type="hidden" name="fulltext" value="1"/>' )
					);
					$input.closest( 'form' ).submit();
				}
			},
			$region: $searchRegion
		} );

		// In most skins (at least Monobook and Vector), the font-size is messed up in <body>.
		// (they use 2 elements to get a sane font-height). So, instead of making exceptions for
		// each skin or adding more stylesheets, just copy it from the active element so auto-fit.
		$searchInput
			.data( 'suggestions-context' )
			.data.$container
				.css( 'fontSize', $searchInput.css( 'fontSize' ) );

	} );

}( mediaWiki, jQuery ) );
