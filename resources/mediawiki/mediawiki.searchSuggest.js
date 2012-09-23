/**
 * Add search suggestions to the search form.
 */
( function ( mw, $ ) {
	$( document ).ready( function ( $ ) {
		var map,
			$searchform = $( '#simpleSearch, #searchInput' ).first(),
			$searchInput = $( '#searchInput' );

		// Ensure that the thing is actually present!
		if ( $searchform.length === 0 ) {
			// Don't try to set anything up if simpleSearch is disabled sitewide.
			// The loader code loads us if the option is present, even if we're
			// not actually enabled (anymore).
			return;
		}

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

		// Placeholder text for search box
		$searchform.filter( '#searchInput' )
			.attr( 'placeholder', mw.msg( 'searchsuggest-search' ) )
			.placeholder();

		// General suggestions functionality for all search boxes
		$( '#searchInput, #searchInput2, #powerSearchText, #searchText' )
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
					// If the delay setting has caused the fetch to have not even happend yet,
					// the jqXHR object will have never been set.
					if ( jqXhr && $.isFunction ( jqXhr.abort ) ) {
						jqXhr.abort();
						$(this).removeData( 'request' );
					}
				},
				result: {
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

		// Special suggestions functionality for skin-provided search box
		$searchInput.suggestions( {
			result: {
				select: function ( $input ) {
					$input.closest( 'form' ).submit();
				}
			},
			special: {
				render: function ( query ) {
					var $el = this;
					if ( $el.children().length === 0 ) {
						$el
							.append(
								$( '<div>' )
									.addClass( 'special-label' )
									.text( mw.msg( 'searchsuggest-containing' ) )
							)
							.append(
								$( '<div>' )
									.addClass( 'special-query' )
									.text( query )
									.autoEllipsis()
							)
							.show();
					} else {
						$el.find( '.special-query' )
							.empty()
							.text( query )
							.autoEllipsis();
					}
				},
				select: function ( $input ) {
					$input.closest( 'form' ).append(
						$( '<input>', {
							type: 'hidden',
							name: 'fulltext',
							val: '1'
						})
					);
					$input.closest( 'form' ).submit();
				}
			},
			$region: $searchform
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