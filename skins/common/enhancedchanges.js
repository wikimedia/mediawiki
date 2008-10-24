/* 
  JavaScript file for enhanced recentchanges
 */
 
/*
  * onload hook to add the CSS to hide parts that should be collapsed
  *
  * We do this with JS so everything will be expanded by default
  * if JS is disabled
 */
addOnloadHook(function () {
	var css = ".mw-rc-jshidden { display:none; }";
	appendCSS(css);
});

/*
 * Switch an RC line between hidden/shown
 * @param int idNumber : the id number of the RC group
*/ 
function toggleVisibility(idNumber) {
	var openarrow = document.getElementById("mw-rc-openarrow-"+idNumber);
	var closearrow = document.getElementById("mw-rc-closearrow-"+idNumber);
	var subentries = document.getElementById("mw-rc-subentries-"+idNumber);
	if (openarrow.style.display == 'inline') {
		openarrow.style.display = 'none';
		closearrow.style.display = 'inline';
		subentries.style.display = 'block';
	} else {
		openarrow.style.display = 'inline';
		closearrow.style.display = 'none';
		subentries.style.display = 'none';
	}
}
