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


/* Temporary hack for Mozilla bug; revert to quirks mode handling of <hr> */
if(navigator.userAgent &&
   navigator.userAgent.indexOf('Gecko') != -1 &&
   navigator.userAgent.indexOf('KHTML') == -1) {
	document.writeln(
	'<style type="text/css">\n' +
	'hr {\n' +
	'  display: inline;\n' +
	'  -moz-box-sizing: border-box;\n' +
	'  margin: 0 0.1% 0 0.1%;\n' +
	'  font-size: -moz-initial !important;\n' +
	'}\n' +
	'hr:before {\n' +
	'  white-space: pre;\n' +
	'  content: "\\A";\n' +
	'}\n' +
	'hr:after {\n' +
	'  white-space: pre;\n' +
	'  content: "\\A";\n' +
	'}\n' +
	'</style>');
}

