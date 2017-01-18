( function ( mw, $ ) {

var searchTerm = '',
	api =  new mw.Api( {
		ajax: {
			url: 'https://commons.wikimedia.org/w/api.php',
			dataType: 'jsonp',
			headers: { 'Api-User-Agent': 'Example/1.0' }
		}
	} );

function itemTemplate( results ) {

	var resultOutput = '',
						i,
						result;

	for ( i = 0; i < results.length; i++ ) {
		result = results[ i ];
		resultOutput += '<div class="iw-result__mini-gallery">' +
						'<a href="/w/' + result.title + '" style="background-image: url(' + result.thumbnail.source + ');" class="iw-result__mini-gallery__image"></a>' +
						'</div>';
	}

	return resultOutput;
}

function itemWrapperTemplate( resultTemplateOutput ) {

	var query = mw.Uri().query.search;

	return '<li class="iw-result iw-result--image">' +
			'<div class="iw-result__header">' +
				'<span class="iw-result__icon iw-result__icon--image"></span>' +
				'<strong>Images</strong> <em>from commons</em>' +
			'</div>' +
			'<div class="iw-result__content">' +
			resultTemplateOutput +
			'</div>' +
			'<div class="iw-result__footer">'+
				'<a href="https://commons.wikimedia.org/w/index.php?title=Special:Search&search=' + query + '&fulltext=1" class="extiw" title="wikt:Special:Search">more results</a></div>' +
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
			formatversion: 2
		} )
		.done( function ( resp ) {
			var multimediaWidgetTemplate =  itemWrapperTemplate( itemTemplate( resp.query.pages )  );
			$( document ).ready( function () {
				$( '.iw-results' ).prepend( multimediaWidgetTemplate );
			} );
		} );

}( mediaWiki, jQuery ) );
