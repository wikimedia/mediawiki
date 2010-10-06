window.historyRadios = function(parent) {
	var inputs = parent.getElementsByTagName('input');
	var radios = [];
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].name == "diff" || inputs[i].name == "oldid") {
			radios[radios.length] = inputs[i];
		}
	}
	return radios;
}

// check selection and tweak visibility/class onclick
window.diffcheck = function() {
	var dli = false; // the li where the diff radio is checked
	var oli = false; // the li where the oldid radio is checked
	var hf = document.getElementById('pagehistory');
	if (!hf) {
		return true;
	}
	var lis = hf.getElementsByTagName('li');
	for (var i=0;i<lis.length;i++) {
		var inputs = historyRadios(lis[i]);
		if (inputs[1] && inputs[0]) {
			if (inputs[1].checked || inputs[0].checked) { // this row has a checked radio button
				if (inputs[1].checked && inputs[0].checked && inputs[0].value == inputs[1].value) {
					return false;
				}
				if (oli) { // it's the second checked radio
					if (inputs[1].checked) {
						if ( (typeof oli.className) != 'undefined') {
							oli.classNameOriginal = oli.className.replace( 'selected', '' );
						} else {
							oli.classNameOriginal = '';
						}
						
						oli.className = "selected "+oli.classNameOriginal;
						return false;
					}
				} else if (inputs[0].checked) {
					return false;
				}
				if (inputs[0].checked) {
					dli = lis[i];
				}
				if (!oli) {
					inputs[0].style.visibility = 'hidden';
				}
				if (dli) {
					inputs[1].style.visibility = 'hidden';
				}
				if ( (typeof lis[i].className) != 'undefined') {
					lis[i].classNameOriginal = lis[i].className.replace( 'selected', '' );
				} else {
					lis[i].classNameOriginal = '';
				}
						
				lis[i].className = "selected "+lis[i].classNameOriginal;
				oli = lis[i];
			}  else { // no radio is checked in this row
				if (!oli) {
					inputs[0].style.visibility = 'hidden';
				} else {
					inputs[0].style.visibility = 'visible';
				}
				if (dli) {
					inputs[1].style.visibility = 'hidden';
				} else {
					inputs[1].style.visibility = 'visible';
				}
				if ( typeof lis[i].classNameOriginal != 'undefined' ) {
					lis[i].className = lis[i].classNameOriginal;
				}
			}
		}
	}
	return true;
}
