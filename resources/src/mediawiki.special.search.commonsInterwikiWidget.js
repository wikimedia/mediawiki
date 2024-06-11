( function () {

	var api = new mw.Api(),
		pageUrl = new mw.Uri();

	function itemTemplate( results ) {

		return results.map( ( result ) => {
			var imageThumbnailSrc = result.thumbnail ? result.thumbnail.source : '';

			return $( '<div>' ).addClass( 'iw-result__mini-gallery' ).append(
				$( '<a>' )
					.addClass( 'iw-result__mini-gallery__image' )
					.attr( {
						style: 'background-image: url(' + imageThumbnailSrc + ')',
						href: new mw.Title( result.title ).getUrl()
					} ).append(
						$( '<span>' ).addClass( 'iw-result__mini-gallery__caption' )
							.text( result.title )
					)
			);
		} );

	}

	function itemWrapperTemplate( pageQuery, itemTemplateOutput ) {

		return $( '<li>' ).addClass( 'iw-resultset iw-resultset--image' ).attr( 'data-iw-resultset-pos', 0 ).append(
			$( '<div>' ).addClass( 'iw-result__header' ).append(
				$( '<strong>' ).text( mw.msg( 'searchprofile-images' ) )
			),
			$( '<div>' ).addClass( 'iw-result__content' ).append(
				itemTemplateOutput
			),
			$( '<div>' ).addClass( 'iw-result__footer' ).append(
				$( '<a>' )
					.attr( 'href', new mw.Title( 'Special:Search' ).getUrl( {
						search: pageQuery,
						fulltext: 1,
						profile: 'images'
					} ) )
					.text( mw.msg( 'search-interwiki-more-results' ) )
			)
		);

	}

	api.get( {
		action: 'query',
		generator: 'search',
		gsrsearch: pageUrl.query.search,
		gsrnamespace: mw.config.get( 'wgNamespaceIds' ).file,
		gsrlimit: 3,
		prop: 'pageimages',
		pilimit: 3,
		piprop: 'thumbnail',
		pithumbsize: 300,
		formatversion: 2
	} ).done( ( resp ) => {
		var results = resp.query && resp.query.pages || false,
			multimediaWidgetTemplate;

		if ( !results ) {
			return;
		}

		results.sort( ( a, b ) => a.index - b.index );

		multimediaWidgetTemplate = itemWrapperTemplate( pageUrl.query.search, itemTemplate( results ) );
		/* we really only need to wait for document ready for DOM manipulation */
		$( () => {
			$( '.iw-results' ).append( multimediaWidgetTemplate );
		} );
	} );

}() );
