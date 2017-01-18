( function ( mw, $ ) {

var searchTerm = '',
	api =  new mw.Api( { /* because ForeignApi doesn't allow requests from local */
		ajax: {
			url: 'https://commons.wikimedia.org/w/api.php',
			headers: { 'Api-User-Agent': 'interwiki-Search-Results-Widget' }
		}
	} );

function itemTemplate( results ) {

	var resultOutput = '',
						i,
						result;

	for ( i = 0; i < results.length; i++ ) {
		result = results[ i ];
		resultOutput += '<div class="iw-result__mini-gallery">' +
							/* escaping response content */
							mw.html.element( 'a', { href: '/w/' + result.title, class: 'iw-result__mini-gallery__image', style: 'background-image: url(' + result.thumbnail.source + ');' } ) +
						'</div>';
	}

	return resultOutput;
}

function itemWrapperTemplate( itemTemplateOutput ) {

	var query = mw.Uri().query.search;

	return '<li class="iw-result iw-result--image">' +
			'<div class="iw-result__header">' +
				'<span class="iw-result__icon iw-result__icon--image"></span>' +
				'<strong>Images</strong> <em>from commons</em>' +
			'</div>' +
			'<div class="iw-result__content">' +
			/* template output has been sanitized by mw.html.element */
			itemTemplateOutput +
			'</div>' +
			'<div class="iw-result__footer">'+
				'<a href="https://commons.wikimedia.org/w/index.php?title=Special:Search&search=' + encodeURIComponent( query ) + '&fulltext=1" class="extiw" title="wikt:Special:Search">more results</a></div>' +
		'</li>';

}

api.get( {	action: 'query',
			generator: 'search',
			gsrsearch: mw.Uri().query.search,
			gsrnamespace: mw.config.get( 'wgNamespaceIds' ).file,
			gsrlimit: 3,
			prop: 'pageimages',
			pilimit: 3,
			piprop: 'thumbnail',
			pithumbsize: 300,
			formatversion: 2,
			origin: '*' /* enables CORS in mw api T62835 */
		} )
		.done( function ( resp ) {
			var multimediaWidgetTemplate =  itemWrapperTemplate( itemTemplate( resp.query.pages )  );
			/* we really only need to wait for document ready for DOM manipulation */
			$( document ).ready( function () {
				$( '.iw-results' ).prepend( multimediaWidgetTemplate );
			} );
		} );

}( mediaWiki, jQuery ) );
