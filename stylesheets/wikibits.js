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

var tocShow, tocHide;
function showTocToggle(show,hide) {
	if(document.getElementById) {
		document.writeln('<small>[<a href="javascript:toggleToc()" id="toctoggle">' +
			hide + '/' + show + '</a>]</small>');
		tocShow = show;
		tocHide = hide;
	}
}

function toggleToc() {
	var toc = document.getElementById('tocinside');
	var tog = document.getElementById('toctoggle');
	if(toc.style.display == 'none') {
		toc.style.display = tocWas;
		// tog.innerHtml = tocHide;
	} else {
		tocWas = toc.style.display;
		toc.style.display = 'none';
		// tog.innerHtml = tocShow;
	}
}

