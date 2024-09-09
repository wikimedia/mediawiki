( function () {

	function itemTemplate( results ) {

		return results.map( ( result ) => {
			const imageThumbnailSrc = result.thumbnail ? result.thumbnail.source : '';

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

	const api = new mw.Api();
	const pageUrl = new URL( location.href );

	api.get( {
		action: 'query',
		generator: 'search',
		gsrsearch: pageUrl.searchParams.get( 'search' ),
		gsrnamespace: mw.config.get( 'wgNamespaceIds' ).file,
		gsrlimit: 3,
		prop: 'pageimages',
		pilimit: 3,
		piprop: 'thumbnail',
		pithumbsize: 300,
		formatversion: 2
	} ).done( ( resp ) => {
		const results = resp.query && resp.query.pages || false;

		if ( !results ) {
			return;
		}

		results.sort( ( a, b ) => a.index - b.index );

		const multimediaWidgetTemplate = itemWrapperTemplate(
			pageUrl.searchParams.get( 'search' ),
			itemTemplate( results )
		);
		/* we really only need to wait for document ready for DOM manipulation */
		$( () => {
			$( '.iw-results' ).append( multimediaWidgetTemplate );
		} );
	} );

}() );
