// Wikipedia JavaScript support functions

// for enhanced RecentChanges
function toggleVisibility( _levelId, _otherId, _linkId) {
	var thisLevel = document.getElementById( _levelId );
	var otherLevel = document.getElementById( _otherId );
	var linkLevel = document.getElementById( _linkId );
	if ( thisLevel.style.display == 'none' ) {
		thisLevel.style.display = 'block';
		otherLevel.style.display = 'none';
		linkLevel.style.display = 'inline';
	} else {
		thisLevel.style.display = 'none';
		otherLevel.style.display = 'inline';
		linkLevel.style.display = 'none';
		}
	}

// Timezone stuff
// tz in format [+-]HHMM
function checkTimezone( tz, msg ) {
	var localclock = new Date();
	// returns negative offset from GMT in minutes
	var tzRaw = localclock.getTimezoneOffset();
	var tzHour = Math.floor( Math.abs(tzRaw) / 60);
	var tzMin = Math.abs(tzRaw) % 60;
	var tzString = ((tzRaw >= 0) ? "-" : "+") + ((tzHour < 10) ? "0" : "") + tzHour + ((tzMin < 10) ? "0" : "") + tzMin;
	if( tz != tzString ) {
		var junk = msg.split( '$1' );
		document.write( junk[0] + "UTC" + tzString + junk[1] );
	}
}

// in [-][H]H format...
// won't yet work with non-even tzs
function fetchTimezone() {
	// FIXME: work around Safari bug
	var localclock = new Date();
	// returns negative offset from GMT in minutes
	var tzRaw = localclock.getTimezoneOffset();
	var tzHour = Math.floor( Math.abs(tzRaw) / 60);
	var tzString = ((tzRaw >= 0) ? "-" : "") + ((tzHour < 10) ? "" : "0") + tzHour;
	return tzString;
}

function guessTimezone(box) {
	document.preferences.wpHourDiff.value = fetchTimezone();
}

function showTocToggle(show,hide) {
	if(document.getElementById) {
		document.writeln('<small>[<a href="javascript:toggleToc()" class="internal">' +
		'<span id="showlink" style="display:none;">' + show + '</span>' +
		'<span id="hidelink">' + hide + '</span>'
		+ '</a>]</small>');
	}
}

function toggleToc() {
	var toc = document.getElementById('tocinside');
	var showlink=document.getElementById('showlink');
	var hidelink=document.getElementById('hidelink');
	if(toc.style.display == 'none') {
		toc.style.display = tocWas;
		hidelink.style.display='';
		showlink.style.display='none';

	} else {
		tocWas = toc.style.display;
		toc.style.display = 'none';
		hidelink.style.display='none';
		showlink.style.display='';

	}
}

// this function generates the actual toolbar buttons with localized text
// we use it to avoid creating the toolbar where javascript is not enabled
function addButton(imageFile, speedTip, tagOpen, tagClose, sampleText) {


	speedTip=escapeQuotes(speedTip);
	tagOpen=escapeQuotes(tagOpen);
	tagClose=escapeQuotes(tagClose);
	sampleText=escapeQuotes(sampleText);
	document.write("<a href=\"#\" onclick=\"javascript:insertTags");
	document.write("('"+tagOpen+"','"+tagClose+"','"+sampleText+"');\">");
	document.write("<img width=\"23\" height=\"22\" src=\""+imageFile+"\" border=\"0\" ALT=\""+speedTip+"\" TITLE=\""+speedTip+"\">");
	document.write("</a>");
	return;
}

function addInfobox(infoText) {

	// if no support for changing selection, add a small copy & paste field
	var clientPC = navigator.userAgent.toLowerCase(); // Get client info
	var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1)
		&& (clientPC.indexOf('khtml')==-1));
 	if(!document.selection && !is_nav) {
	 	document.write("<form name='infoform' id='infoform'>"+
			"<input size=80 id='infobox' name='infobox' value='"+
			infoText+"' READONLY></form>");
 	}

}

function escapeQuotes(text) {

	text=text.replace(/'/g,"\\'");
	text=text.replace(/\n/g,"\\n");
	return text;
}

// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
// copied and adapted from phpBB
function insertTags(tagOpen, tagClose, sampleText) {

	var txtarea = document.editform.wpTextbox1;
	// IE
	if(document.selection) {
		var theSelection = document.selection.createRange().text;
		if(!theSelection) { theSelection=sampleText;}
		txtarea.focus();
		document.selection.createRange().text = tagOpen + theSelection + tagClose;
	// Mozilla
	} else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
 		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		var myText = (txtarea.value).substring(startPos, endPos);
		if(!myText) { myText=sampleText;}
		txtarea.value = txtarea.value.substring(0, startPos) + tagOpen + myText + tagClose + txtarea.value.substring(endPos, txtarea.value.length);
		txtarea.focus();
		var cPos=startPos+(tagOpen.length+myText.length+tagClose.length);
		txtarea.selectionStart=cPos;
		txtarea.selectionEnd=cPos;
	// All others
	} else {
		// Append at the end: Some people find that annoying
		//txtarea.value += tagOpen + sampleText + tagClose;
		//txtarea.focus();
		tagOpen=tagOpen.replace(/\n/g,"");
		tagClose=tagClose.replace(/\n/g,"");
		document.infoform.infobox.value=tagOpen+sampleText+tagClose;
		txtarea.focus();
	}
	// reposition cursor if possible
	if (txtarea.createTextRange) txtarea.caretPos = document.selection.createRange().duplicate();
}
