$( function () {
	$( '#minidiff' ).find( '.mw-diff-inline-moved' ).each( function ( i ) {
		var $moveMarker,
			$to = $( this ),
			ref = $( this ).data( 'move-ref' ),
			$from = $( '.mw-diff-inline-added, .mw-diff-inline-deleted' )
				.filter( '[data-line="' + ref + '"]' ),
			foffset = $from.offset(),
			toffset = $to.offset(),
			offset = (i+1)*5,
			w = 20, l = foffset.left - w - offset;

		while ( l < -w ) {
			l += w;
		}
		$moveMarker = $( '<div class="mw-diff-move-marker">' ).css( {
			width: w,
			height: toffset.top - foffset.top,
			position: 'absolute',
			top: foffset.top + 8,
			left: l,
			bottom: toffset.top
		} ).appendTo( 'body' );

		if ( $from.hasClass( 'mw-diff-inline-added' ) ) {
			$moveMarker.addClass( 'mw-diff-move-marker-to-from' );
		} else {
			$moveMarker.addClass( 'mw-diff-move-marker-from-to' );
		}
	} );
} );