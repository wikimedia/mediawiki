/**
 * Pre-load the Wiki logo
 *
 * $wgLogo is rendered as the background-image of #p-logo rather than an <img>
 * tag. However, background-images are only loaded after all <img> tags in the
 * page HTML have been loaded. This causes the logo to render late, which is
 * annoying because it is visually prominent and above-the-fold. We work around
 * this by creating an Image node and setting its src to the logo URL, which
 * elevates its priority to that of an <img>.
 */
/*global devicePixelRatio */
( function ( mw ) {
	var logos = mw.config.get( 'wgLogoHD', {} ),
		bestLogo = mw.config.get( 'wgLogo' ),
		bestRatio = 0, ratio, logo;

	if ( window.devicePixelRatio > 1 ) {
		for ( ratio in logos ) {
			logo = logos[ratio];
			ratio = parseFloat( ratio );
			if ( ratio > bestRatio && ratio <= devicePixelRatio ) {
				bestLogo = logo;
			}
		}
	}

	new Image().src = bestLogo;
} ( mediaWiki ) );
