/**
 * Takes a srcset string and creates a map of the values.
 *
 * @ignore
 * @param {string} srcset
 * @return {Object} shuffled srcset mapping strings e.g. '2' to src values.
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
 * Takes a srcset map and shuffles it so that 3x becomes 2x etc..
 * Drops numbers < 1
 *
 * @ignore
 * @param {Object<string, string>} srcsetMap
 * @return {string} shuffled srcset
 */
const shuffleSrcSet = ( srcsetMap ) => Object.keys( srcsetMap )
	.filter( ( key ) => parseInt( key, 10 ) - 1 > 1 )
	.map( ( key ) => `${ srcsetMap[ key ] } ${ parseInt( key, 10 ) - 1 }x` ).join( ', ' );

/**
 * For users requiring a larger thumbnail, we reach into srcset to promote the larger values
 * to avoid blurry thumbnails. This function updates the src and srcset attribute of the image
 * to support a higher resolution image (for example using 2x as the src image).
 * If the srcset is in an unexpected format, or no srcset values are above 1.5x, this function will do nothing leaving
 * the srcset as is.
 *
 * @ignore
 * @param {Element} img
 * @return {void}
 */
const updateThumbnailToPreferredSize = ( img ) => {
	const srcsetMap = makeSrcSetMap( img.srcset );
	if ( Object.keys( srcsetMap ).length > 0 ) {
		const newSrcset = shuffleSrcSet( srcsetMap );
		const upgradedSrc = srcsetMap[ '2' ];
		if ( upgradedSrc ) {
			img.src = upgradedSrc;
		}
		if ( newSrcset ) {
			img.srcset = newSrcset;
			if ( upgradedSrc && img.src !== upgradedSrc ) {
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
	if ( document.documentElement.classList.contains( 'skin-theme-clientpref-thumb-large' ) ) {
		const observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					updateThumbnailToPreferredSize( entry.target );
					observer.unobserve( entry.target );
				}
			} );
		} );
		$element.find( '.mw-default-size img[srcset]' ).each( ( _, img ) => {
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
