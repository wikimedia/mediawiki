/**
 * Takes a srcset string and creates a map of the values.
 *
 * @ignore
 * @param {string} srcset
 * @return {Object} shuffled srcset
 */
const makeSrcSetMap = ( srcset ) => {
	const srcsetMap = {};
	srcset.split( ',' )
		.map( ( a ) => a.trim().split( ' ' ) )
		.forEach(
			( combo ) => {
				if ( combo.length === 2 && combo[ 1 ].endsWith( 'x' ) ) {
					srcsetMap[ combo[ 1 ].replace( 'x', '' ) ] = combo[ 0 ];
				}
			}
		);
	return srcsetMap;
};

/**
 * Takes a srcset map and shuffles it so that 2x becomes 1x etc..
 * Drops existing 1x and 1.5x values.
 *
 * @ignore
 * @param {Object<string, string>} srcsetMap
 * @return {string} shuffled srcset
 */
const shuffleSrcSet = ( srcsetMap ) => {
	// create new srcset
	const newSrcset = Object.keys( srcsetMap ).filter( ( key ) => parseInt( key, 10 ) - 1 > 0.5 ).map( ( key ) => `${ srcsetMap[ key ] } ${ parseInt( key, 10 ) - 1 }x` ).join( ', ' );
	return newSrcset;
};

/**
 * For users requiring a larger thumbnail, we reach into srcset to promote the larger values
 * to avoid blurry thumbnails. This function shuffles the srcset values down by 1x, so 2x becomes 1x etc.. and drops
 * existing 1x and 1.5x values.
 * If the srcset is in an unexpected format, or no srcset values are above 1.5x, this function will do nothing leaving
 * the image as is.
 *
 * @ignore
 * @param {Element} img
 * @return {void}
 */
const updateThumbnailToPreferredSize = ( img ) => {
	const srcsetMap = makeSrcSetMap( img.srcset );
	if ( Object.keys( srcsetMap ).length > 0 ) {
		const newSrcset = shuffleSrcSet( srcsetMap );
		if ( newSrcset ) {
			img.srcset = newSrcset;
			const upgradedSrc = srcsetMap[ '1x' ];
			if ( upgradedSrc && img.currentSrc !== upgradedSrc ) {
				img.src = upgradedSrc;
			}
		}
	}
};

/**
 * Checks users preferred thumbnail size and upgrades from small to large
 * to sharpen images.
 *
 * @ignore
 * @param {jQuery} $element
 */
function updateThumbnailsToPreferredSize( $element ) {
	if ( !$element.closest( '[data-mw-parsoid-version]' ).length ) {
		// Don't run on legacy parser-rendered content (yet), as legacy parser already achieves this via HTML changes
		// this can be removed once we've verified that users are happy with the Parsoid solution and we
		// remove the legacy parser option.
		return;
	}
	if ( document.documentElement.classList.contains( 'skin-theme-clientpref-thumb-large' ) ) {
		const observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					updateThumbnailToPreferredSize( entry.target );
					observer.unobserve( entry.target );
				}
			} );
		} );
		$element.find( 'img[srcset]' ).each( ( _, img ) => {
			observer.observe( img );
		} );
	}
}

module.exports = {
	test: {
		shuffleSrcSet,
		makeSrcSetMap,
		updateThumbnailToPreferredSize
	},
	updateThumbnailsToPreferredSize
};
