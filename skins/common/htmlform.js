// Find select-or-other fields.
addOnloadHook( function() {
	var fields = getElementsByClassName( document, 'select', 'mw-htmlform-select-or-other' );

	for( var i = 0; i < fields.length; i++ ) {
		var select = fields[i];

		addHandler( select, 'change', htmlforms.selectOrOtherSelectChanged );

		// Use a fake 'e' to update it.
		htmlforms.selectOrOtherSelectChanged( { 'target': select } );
	}
} );

window.htmlforms = {
	'selectOrOtherSelectChanged' : function( e ) {
		var select;
		if ( !e ) {
			e = window.event;
		}
		if ( e.target ) {
			select = e.target;
		} else if ( e.srcElement ) {
			select = e.srcElement;
		}
		if ( select.nodeType == 3 ) { // defeat Safari bug
			select = select.parentNode;
		}

		var id = select.id;
		var textbox = document.getElementById( id + '-other' );

		textbox.disabled = ( select.value != 'other' );
	}
};

