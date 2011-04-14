window.historyRadios = function( parent ) {
	var inputs = parent.getElementsByTagName( 'input' );
	var radios = [],
		i = 0;
	for ( i = 0; i < inputs.length; i++ ) {
		if ( inputs[i].name == 'diff' || inputs[i].name == 'oldid' ) {
			radios[radios.length] = inputs[i];
		}
	}
	return radios;
};

// check selection and tweak visibility/class onclick
window.diffcheck = function() {
	var	dli = false, // the li where the diff radio is checked
		oli = false, // the li where the oldid radio is checked
		i = 0;
	var hf = document.getElementById( 'pagehistory' );
	if ( !hf ) {
		return true;
	}
	var lis = hf.getElementsByTagName( 'li' );
	for ( i = 0; i < lis.length; i++ ) {
		var inputs = historyRadios( lis[i] );
		if ( inputs[1] && inputs[0] ) {
			if ( inputs[1].checked || inputs[0].checked ) { // this row has a checked radio button
				if ( inputs[1].checked && inputs[0].checked && inputs[0].value == inputs[1].value ) {
					return false;
				}
				if ( oli ) { // it's the second checked radio
					if ( inputs[1].checked ) {
						if ( typeof oli.className != 'undefined' ) {
							oli.classNameOriginal = oli.className.replace( 'selected', '' );
						} else {
							oli.classNameOriginal = '';
						}

						oli.className = 'selected ' + oli.classNameOriginal;
						return false;
					}
				} else if ( inputs[0].checked ) {
					return false;
				}
				if ( inputs[0].checked ) {
					dli = lis[i];
				}
				if ( !oli ) {
					inputs[0].style.visibility = 'hidden';
				}
				if ( dli ) {
					inputs[1].style.visibility = 'hidden';
				}
				if ( (typeof lis[i].className) != 'undefined') {
					lis[i].classNameOriginal = lis[i].className.replace( 'selected', '' );
				} else {
					lis[i].classNameOriginal = '';
				}

				lis[i].className = 'selected ' + lis[i].classNameOriginal;
				oli = lis[i];
			} else { // no radio is checked in this row
				if ( !oli ) {
					inputs[0].style.visibility = 'hidden';
				} else {
					inputs[0].style.visibility = 'visible';
				}
				if ( dli ) {
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
};

//update the compare link as you select radio buttons
window.updateCompare = function () {
	var hf = compareLink.$form.get(0);
	var oldInd = -1;
	var i = 0;
	while(oldInd == -1 & i<hf.oldid.length) {
		if(hf.oldid[i].checked){
			oldInd=i;
		}
		i++;
	}
	var diffInd=-1;
	var j=0;
	while(diffInd==-1 & j<hf.diff.length) {
		if(hf.diff[j].checked){
			diffInd=j;
		}
		j++;
	}
	var wikiLinkURL = wgServer + wgScript + "?title=" + encodeURIComponent(hf.title.value)
		+ "&diff=" + hf.diff[diffInd].value + "&oldid=" + hf.oldid[oldInd].value;
	compareLink.wikiTop.attr("href", wikiLinkURL);
	compareLink.wikiEnd.attr("href", wikiLinkURL);

	if(compareLink.htmlDiffs)
	{
		var htmlLinkURL = wgServer + wgScript + "?title=" + encodeURIComponent(hf.title.value)
			+ "&htmldiff=" + compareLink.htmlDiffButtonTxt
			+ "&diff=" + hf.diff[diffInd].value + "&oldid=" + hf.oldid[oldInd].value;
		compareLink.htmlTop.attr("href", htmlLinkURL);
		compareLink.htmlEnd.attr("href", htmlLinkURL);
	}
};

//change the button to a link where possible
window.fixCompare = function () {
	window.compareLink = {};
	var doneHtml = false;
	var doneWiki = false;
	compareLink.htmlDiffs = false;
	
	compareLink.$form = $("#mw-history-compare");
	var hf = compareLink.$form.get(0);
	
	var $buttons = $('input.historysubmit');
	
	if (!compareLink.$form.length || !$buttons.length) return;

	$buttons.each(function() {
		if(this.name == "htmldiff") {
			if (doneHtml) return true;
			doneHtml = true;
			var url = wgServer + wgScript + "?title=" + encodeURIComponent(hf.title.value)
				+ "&htmldiff=" + this.value + "&diff=" + hf.diff[0].value + "&oldid=" + hf.oldid[1].value;
			compareLink.htmlDiffs = true;
		} else {
			if (doneWiki) return true;
			doneWiki = true;
			var url = wgServer + wgScript + "?title=" + encodeURIComponent(hf.title.value)
				+ "&diff=" + hf.diff[0].value + "&oldid=" + hf.oldid[1].value;
		}
		var $linkTop = $("<a href='" + url + "' class='historycomparelink ui-button'>" + this.value + "</a>").button();
		compareLink.$form.before($linkTop);
		$linkEnd = $linkTop.clone();
		compareLink.$form.append($linkEnd);
		
		if(this.name == "htmldiff") {
			compareLink.htmlTop = $linkTop;
			compareLink.htmlEnd = $linkEnd;
		} else {
			compareLink.wikiTop = $linkTop;
			compareLink.wikiEnd = $linkEnd;
		}
	});
	$buttons.hide();

	$("#pagehistory").change(function() {window.updateCompare()});
};

