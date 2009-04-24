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

var htmlforms = {
	'selectOrOtherSelectChanged' : function(e) {
		if (!e) e = window.event;
		var select = e.target;
		var id = select.id;
		var textbox = document.getElementById( id+'-other' );
		
		if (select.value == 'other') {
			textbox.disabled = false;
		} else {
			textbox.disabled = true;
		}
	},
}

