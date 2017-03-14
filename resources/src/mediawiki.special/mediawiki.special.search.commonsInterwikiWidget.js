( function ( mw, $ ) {

	var api = new mw.Api(),
		pageUrl = new mw.Uri(),
		imagesText = new mw.Message( mw.messages, 'searchprofile-images' ),
		moreResultsText = new mw.Message( mw.messages, 'search-interwiki-more-results' );

	function itemTemplate( results ) {

		var resultOutput = '', i, result, imageCaption, imageThumbnailSrc;

		for ( i = 0; i < results.length; i++ ) {
			result = results[ i ],
			imageCaption = mw.html.element( 'span', { 'class': 'iw-result__mini-gallery__caption' }, result.title );
			imageThumbnailSrc = ( result.thumbnail ) ? result.thumbnail.source : '';
			resultOutput += '<div class="iw-result__mini-gallery">' +
						/* escaping response content */
						mw.html.element( 'a', {
							href: '/wiki/' + result.title,
							'class': 'iw-result__mini-gallery__image',
							style: 'background-image: url(' + imageThumbnailSrc + ');'
						}, new mw.html.Raw( imageCaption ) ) +
					'</div>';
		}

		return resultOutput;
	}

	function itemWrapperTemplate( pageQuery, itemTemplateOutput ) {

		return '<li class="iw-resultset iw-resultset--image" data-iw-resultset-pos="0">' +
				'<div class="iw-result__header">' +
					'<span class="iw-result__icon iw-result__icon--image"></span>' +
					'<strong>' + imagesText.escaped() + '</strong>' +
				'</div>' +
				'<div class="iw-result__content">' +
				/* template output has been sanitized by mw.html.element */
				itemTemplateOutput +
				'</div>' +
				'<div class="iw-result__footer">' +
					'<a href="/w/index.php?title=Special:Search&search=' + encodeURIComponent( pageQuery ) + '&fulltext=1&profile=images">' +
						moreResultsText.escaped() +
					'</a>' +
				'</div>' +
			'</li>';

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
	} )
	.done( function ( resp ) {
		var results = ( resp.query && resp.query.pages ) ? resp.query.pages : false,
			multimediaWidgetTemplate;

		if ( !results ) {
			return;
		}

		results.sort( function( a, b ) {
			return a.index - b.index;
		} );

		multimediaWidgetTemplate = itemWrapperTemplate( pageUrl.query.search, itemTemplate( results ) );
		/* we really only need to wait for document ready for DOM manipulation */
		$( function () {
			$( '.iw-results' ).prepend( multimediaWidgetTemplate );
		} );
	} );

}( mediaWiki, jQuery ) );
