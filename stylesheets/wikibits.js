// Wikipedia JavaScript support functions

// if this is true, the toolbar will no longer overwrite the infobox when you move the mouse over individual items
var noOverwrite=false;
var alertText;
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var is_gecko = ((clientPC.indexOf('gecko')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('khtml') == -1));
var is_safari = ((clientPC.indexOf('AppleWebKit')!=-1) && (clientPC.indexOf('spoofer')==-1));
if (clientPC.indexOf('opera')!=-1) {
    var is_opera = true;
    var is_opera_preseven = (window.opera && !document.childNodes);
    var is_opera_seven = (window.opera && document.childNodes);
}

// document.write special stylesheet links
function addcss ( stylepath ) {
    if (is_opera_preseven) {
        document.write('<link rel="stylesheet" type="text/css" href="'+stylepath+'Opera6Fixes.css">');
    } else if (is_opera_seven) {
        document.write('<link rel="stylesheet" type="text/css" href="'+stylepath+'Opera7Fixes.css">');
    }
    return;
}

// Un-trap us from framesets
if( window.top != window ) window.top.location = window.location;

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

// in [-]HH:MM format...
// won't yet work with non-even tzs
function fetchTimezone() {
	// FIXME: work around Safari bug
	var localclock = new Date();
	// returns negative offset from GMT in minutes
	var tzRaw = localclock.getTimezoneOffset();
	var tzHour = Math.floor( Math.abs(tzRaw) / 60);
	var tzMin = Math.abs(tzRaw) % 60;
	var tzString = ((tzRaw >= 0) ? "-" : "") + ((tzHour < 10) ? "0" : "") + tzHour +
		":" + ((tzMin < 10) ? "0" : "") + tzMin;
	return tzString;
}

function guessTimezone(box) {
	document.preferences.wpHourDiff.value = fetchTimezone();
}

function showTocToggle(show,hide) {
	if(document.getElementById) {
		document.writeln('<span class=\'toctoggle\'>[<a href="javascript:toggleToc()" class="internal">' +
		'<span id="showlink" style="display:none;">' + show + '</span>' +
		'<span id="hidelink">' + hide + '</span>'
		+ '</a>]</span>');
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
	var mouseOver="";

	// we can't change the selection, so we show example texts
	// when moving the mouse instead, until the first button is clicked
	if(!document.selection && !is_gecko) {
		// filter backslashes so it can be shown in the infobox
		var re=new RegExp("\\\\n","g");
		tagOpen=tagOpen.replace(re,"");
		tagClose=tagClose.replace(re,"");
		mouseOver = "onMouseover=\"if(!noOverwrite){document.infoform.infobox.value='"+tagOpen+sampleText+tagClose+"'};\"";
	}

	document.write("<a href=\"javascript:insertTags");
	document.write("('"+tagOpen+"','"+tagClose+"','"+sampleText+"');\">");

        document.write("<img width=\"23\" height=\"22\" src=\""+imageFile+"\" border=\"0\" ALT=\""+speedTip+"\" TITLE=\""+speedTip+"\""+mouseOver+">");
	document.write("</a>");
	return;
}

function addInfobox(infoText,text_alert) {
	alertText=text_alert;
	var clientPC = navigator.userAgent.toLowerCase(); // Get client info

	var re=new RegExp("\\\\n","g");
	alertText=alertText.replace(re,"\n");

	// if no support for changing selection, add a small copy & paste field
	// document.selection is an IE-only property. The full toolbar works in IE and
	// Gecko-based browsers.
	if(!document.selection && !is_gecko) {
 		infoText=escapeQuotesHTML(infoText);
	 	document.write("<form name='infoform' id='infoform'>"+
			"<input size=80 id='infobox' name='infobox' value=\""+
			infoText+"\" READONLY></form>");
 	}

}

function escapeQuotes(text) {
	var re=new RegExp("'","g");
	text=text.replace(re,"\\'");
	re=new RegExp('"',"g");
	text=text.replace(re,'&quot;');
	re=new RegExp("\\n","g");
	text=text.replace(re,"\\n");
	return text;
}

function escapeQuotesHTML(text) {
	var re=new RegExp('"',"g");
	text=text.replace(re,"&quot;");
	return text;
}

// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
// copied and adapted from phpBB
function insertTags(tagOpen, tagClose, sampleText) {

	var txtarea = document.editform.wpTextbox1;
	// IE
	if(document.selection  && !is_gecko) {
		var theSelection = document.selection.createRange().text;
		if(!theSelection) { theSelection=sampleText;}
		txtarea.focus();
		if(theSelection.charAt(theSelection.length - 1) == " "){// exclude ending space char, if any
			theSelection = theSelection.substring(0, theSelection.length - 1);
			document.selection.createRange().text = tagOpen + theSelection + tagClose + " ";
		} else {
			document.selection.createRange().text = tagOpen + theSelection + tagClose;
		}

	// Mozilla
	} else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
 		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		var scrollTop=txtarea.scrollTop;
		var myText = (txtarea.value).substring(startPos, endPos);
		if(!myText) { myText=sampleText;}
		if(myText.charAt(myText.length - 1) == " "){ // exclude ending space char, if any
			subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
		} else {
			subst = tagOpen + myText + tagClose;
		}
		txtarea.value = txtarea.value.substring(0, startPos) + subst +
		  txtarea.value.substring(endPos, txtarea.value.length);
		txtarea.focus();

		var cPos=startPos+(tagOpen.length+myText.length+tagClose.length);
		txtarea.selectionStart=cPos;
		txtarea.selectionEnd=cPos;
		txtarea.scrollTop=scrollTop;

	// All others
	} else {
		var copy_alertText=alertText;
		var re1=new RegExp("\\$1","g");
		var re2=new RegExp("\\$2","g");
		copy_alertText=copy_alertText.replace(re1,sampleText);
		copy_alertText=copy_alertText.replace(re2,tagOpen+sampleText+tagClose);
		var text;
		if (sampleText) {
			text=prompt(copy_alertText);
		} else {
			text="";
		}
		if(!text) { text=sampleText;}
		text=tagOpen+text+tagClose;
		document.infoform.infobox.value=text;
		// in Safari this causes scrolling
		if(!is_safari) {
			txtarea.focus();
		}
		noOverwrite=true;
	}
	// reposition cursor if possible
	if (txtarea.createTextRange) txtarea.caretPos = document.selection.createRange().duplicate();
}
