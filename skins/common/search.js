// JS specific to Special:Search

// change the search link to what user entered
window.mwSearchHeaderClick = function( obj ) {
	var searchbox = document.getElementById( 'searchText' );
	if( searchbox === null ) {
		searchbox = document.getElementById( 'powerSearchText' );
	}
	if( searchbox === null ) {
		return; // should always have either normal or advanced search
	}

	var searchterm = searchbox.value;
	var parts = obj.getAttribute( 'href', 2).split( 'search=' );
	var lastpart = '';
	var prefix = 'search=';
	if( parts.length > 1 && parts[1].indexOf('&') >= 0 ) {
		lastpart = parts[1].substring( parts[1].indexOf('&') );
	} else {
		prefix = '&search=';
	}
	obj.href = parts[0] + prefix + encodeURIComponent( searchterm ) + lastpart;
};

window.mwToggleSearchCheckboxes = function( btn ) {
	if( !document.getElementById ) {
		return;
	}

	var nsInputs = document.getElementById( 'powersearch' ).getElementsByTagName( 'input' );
	var isChecked = false;

	for ( var i = 0; i < nsInputs.length; i++ ) {
		var pattern = /^ns/;
		if ( ( nsInputs[i].type == 'checkbox' ) && ( pattern.test( nsInputs[i].name ) ) ) {
			switch ( btn ) {
				case 'none':
					if ( nsInputs[i].checked ) {
						nsInputs[i].checked = false;
					}
					break;
				case 'all':
					if ( !nsInputs[i].checked ) {
						nsInputs[i].checked = true;
					}
					break;
			}
		}
	}
};
