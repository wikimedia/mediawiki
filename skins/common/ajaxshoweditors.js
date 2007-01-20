var sajax_debug_mode = false;
var canRefresh = null;
var ShowEditorsCounting = false;
var wgAjaxShowEditors = {} ;

// The loader. Look at bottom for the sajax hook registration
wgAjaxShowEditors.onLoad = function() {
	var elEditors = document.getElementById( 'ajax-se' );
	// wgAjaxShowEditors.refresh();
	elEditors.onclick = function() { wgAjaxShowEditors.refresh(); } ;

	var elTextArea = document.getElementById( 'wpTextbox1' );
	elTextArea.onkeypress = function() { wgAjaxShowEditors.refresh(); } ;

	wgAjaxShowEditors.allowRefresh();
}


// Ask for new data & update UI
wgAjaxShowEditors.refresh = function() {
	if( !canRefresh ) { return; }

	// Disable new requests for 5 seconds
	canRefresh = false;
	setTimeout( 'wgAjaxShowEditors.allowRefresh()', 5000 );

	// Load the editors list element, it will get rewrote
	var elEditorsList = document.getElementById( 'ajax-se-editors' );

	if( wgUserName == null ) {
		wgUserName = '';
	}

	// Do the ajax call to the server
	sajax_do_call( "wfAjaxShowEditors", [ wgArticleId, wgUserName ], elEditorsList );
	if(!ShowEditorsCounting) {
		wgAjaxShowEditors.countup();
	}
}

wgAjaxShowEditors.countup = function() {
	ShowEditorsCounting = true;

	var elEditorsList = document.getElementById( 'ajax-se-editors' );
	for(var i=0;i<elEditorsList.childNodes.length;i++) {
		var item = elEditorsList.childNodes[i];
		if (item.nodeName == 'SPAN') {
			var value = parseInt( item.innerHTML );
			value++;
			item.innerHTML = value ;
		}
	}
	setTimeout( "wgAjaxShowEditors.countup()", 1000 );
}

// callback to allow refresh
wgAjaxShowEditors.allowRefresh = function() {
	canRefresh = true;
}

// Register our initialization function.
hookEvent( "load", wgAjaxShowEditors.onLoad);
