/**
 * IE fixes javascript loaded by wikibits.js for IE <= 6.0
 */
/*global isMSIE55:true, doneIETransform:true, doneIEAlphaFix:true */
/*global hookit:true, fixalpha:true */
( function ( mw, $ ) {

var expandedURLs, hasClass;

// Also returns true for IE6, 7, 8, 9 and 10. createPopup is removed in IE11.
// Good thing this is only loaded for IE <= 6 by wikibits.
// Might as well set it to true.
isMSIE55 = ( window.showModalDialog && window.clipboardData && window.createPopup );
doneIETransform = false;
doneIEAlphaFix = false;

hookit = function () {
	if ( !doneIETransform && document.getElementById && document.getElementById( 'bodyContent' ) ) {
		doneIETransform = true;
		fixalpha();
	}
};

if ( document.attachEvent ) {
	document.attachEvent( 'onreadystatechange', hookit );
}

// png alpha transparency fixes
fixalpha = function ( logoId ) {
	// bg
	if ( isMSIE55 && !doneIEAlphaFix ) {
		var bg, imageUrl, linkFix, logoa, logospan, plogo;
		plogo = document.getElementById( logoId || 'p-logo' );
		if ( !plogo ) {
			return;
		}

		logoa = plogo.getElementsByTagName('a')[0];
		if ( !logoa ) {
			return;
		}

		bg = logoa.currentStyle.backgroundImage;
		imageUrl = bg.substring( 5, bg.length - 2 );

		doneIEAlphaFix = true;

		if ( imageUrl.substr( imageUrl.length - 4 ).toLowerCase() === '.png' ) {
			logospan = logoa.appendChild( document.createElement( 'span' ) );

			logoa.style.backgroundImage = 'none';
			logospan.style.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=' + imageUrl + ')';
			logospan.style.height = '100%';
			logospan.style.position = 'absolute';
			logospan.style.width = logoa.currentStyle.width;
			logospan.style.cursor = 'hand';
			// Center image with hack for IE5.5
			if ( document.documentElement.dir === 'rtl' ) {
				logospan.style.right = '50%';
				logospan.style.setExpression( 'marginRight', '"-" + (this.offsetWidth / 2) + "px"' );
			} else {
				logospan.style.left = '50%';
				logospan.style.setExpression( 'marginLeft', '"-" + (this.offsetWidth / 2) + "px"' );
			}
			logospan.style.top = '50%';
			logospan.style.setExpression( 'marginTop', '"-" + (this.offsetHeight / 2) + "px"' );

			linkFix = logoa.appendChild( logoa.cloneNode() );
			linkFix.style.position = 'absolute';
			linkFix.style.height = '100%';
			linkFix.style.width = '100%';
		}
	}
};

if ( isMSIE55 ) {
	// ondomready
	$( fixalpha );
}

// Expand links for printing
hasClass = function ( classText, classWanted ) {
	var i = 0, classArr = classText.split(/\s/);
	for ( i = 0; i < classArr.length; i++ ) {
		if ( classArr[i].toLowerCase() === classWanted.toLowerCase() ) {
			return true;
		}
	}
	return false;
};

window.onbeforeprint = function () {
	var allLinks, contentEl, expandedLink, expandedText, i;

	expandedURLs = [];
	contentEl = document.getElementById( 'content' );

	if ( contentEl ) {
		allLinks = contentEl.getElementsByTagName( 'a' );

		for ( i = 0; i < allLinks.length; i++ ) {
			if ( hasClass( allLinks[i].className, 'external' ) && !hasClass( allLinks[i].className, 'free' ) ) {
				expandedLink = document.createElement( 'span' );
				expandedText = document.createTextNode( ' (' + allLinks[i].href + ')' );
				expandedLink.appendChild( expandedText );
				allLinks[i].parentNode.insertBefore( expandedLink, allLinks[i].nextSibling );
				expandedURLs[i] = expandedLink;
			}
		}
	}
};

window.onafterprint = function () {
	for ( var i = 0; i < expandedURLs.length; i++ ) {
		if ( expandedURLs[i] ) {
			expandedURLs[i].removeNode( true );
		}
	}
};

}( mediaWiki, jQuery ) );
