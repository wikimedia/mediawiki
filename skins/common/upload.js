function licenseSelectorCheck() {
	var selector = document.getElementById("wpLicense");
	if (selector.selectedIndex > 0 &&
		selector.options[selector.selectedIndex].value == "" ) {
		// Browser is broken, doesn't respect disabled attribute on <option>
		selector.selectedIndex = 0;
	}
}

function licenseSelectorFixup() {
	// for MSIE/Mac; non-breaking spaces cause the <option> not to render
	// but, for some reason, setting the text to itself works
	var selector = document.getElementById("wpLicense");
	var ua = navigator.userAgent;
	var isMacIe = (ua.indexOf("MSIE") != -1) && (ua.indexOf("Mac") != -1);
	if (isMacIe) {
		for (var i = 0; i < selector.options.length; i++) {
			selector.options[i].text = selector.options[i].text;
		}
	}
}

addOnloadHook(licenseSelectorFixup);
