( function () {
	'use strict';

	$( () => {
		$( '#mw-version-sbom-download' ).on( 'click', function ( e ) {
			const link = this;
			e.preventDefault();
			new mw.Api().get( {
				action: 'query',
				meta: 'siteinfo',
				siprop: 'sbom',
				formatversion: 2
			} ).then( ( data ) => {
				const url = URL.createObjectURL( new Blob(
					[ JSON.stringify( data.query.sbom, null, '\t' ) ],
					{ type: 'application/vnd.cyclonedx+json' }
				) );
				const a = document.createElement( 'a' );
				a.href = url;
				a.download = link.getAttribute( 'download' );
				a.click();
				URL.revokeObjectURL( url );
			} );
		} );
	} );
}() );
