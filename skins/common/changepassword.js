
function onNameChange() {
	if ( wgUserName != document.getElementById('wpName').value ) {
		document.getElementById('wpPassword').disabled = true;
		document.getElementById('wpComment').disabled = false;
	} else {
		document.getElementById('wpPassword').disabled = false;
		document.getElementById('wpComment').disabled = true;
	}
}

function onNameChangeHook() {
	document.getElementById( 'wpName' ).onblur = onNameChange;
}

addOnloadHook( onNameChangeHook );
