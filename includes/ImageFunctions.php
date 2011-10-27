<?php
/**
 * Global functions related to images
 *
 * @file
 */

/**
 * Determine if an image exists on the 'bad image list'.
 *
 * The format of MediaWiki:Bad_image_list is as follows:
 *    * Only list items (lines starting with "*") are considered
 *    * The first link on a line must be a link to a bad image
 *    * Any subsequent links on the same line are considered to be exceptions,
 *      i.e. articles where the image may occur inline.
 *
 * @param $name string the image name to check
 * @param $contextTitle Title|bool the page on which the image occurs, if known
 * @param $blacklist string wikitext of a file blacklist
 * @return bool
 */
function wfIsBadImage( $name, $contextTitle = false, $blacklist = null ) {
	static $badImageCache = null; // based on bad_image_list msg
	wfProfileIn( __METHOD__ );

	# Handle redirects
	$redirectTitle = RepoGroup::singleton()->checkRedirect( Title::makeTitle( NS_FILE, $name ) );
	if( $redirectTitle ) {
		$name = $redirectTitle->getDbKey();
	}

	# Run the extension hook
	$bad = false;
	if( !wfRunHooks( 'BadImage', array( $name, &$bad ) ) ) {
		wfProfileOut( __METHOD__ );
		return $bad;
	}
 
	$cacheable = ( $blacklist === null );
	if( $cacheable && $badImageCache !== null ) {
		$badImages = $badImageCache;
	} else { // cache miss
		if ( $blacklist === null ) {
			$blacklist = wfMsgForContentNoTrans( 'bad_image_list' ); // site list
		}
		# Build the list now
		$badImages = array();
		$lines = explode( "\n", $blacklist );
		foreach( $lines as $line ) {
			# List items only
			if ( substr( $line, 0, 1 ) !== '*' ) {
				continue;
			}

			# Find all links
			$m = array();
			if ( !preg_match_all( '/\[\[:?(.*?)\]\]/', $line, $m ) ) {
				continue;
			}

			$exceptions = array();
			$imageDBkey = false;
			foreach ( $m[1] as $i => $titleText ) {
				$title = Title::newFromText( $titleText );
				if ( !is_null( $title ) ) {
					if ( $i == 0 ) {
						$imageDBkey = $title->getDBkey();
					} else {
						$exceptions[$title->getPrefixedDBkey()] = true;
					}
				}
			}

			if ( $imageDBkey !== false ) {
				$badImages[$imageDBkey] = $exceptions;
			}
		}
		if ( $cacheable ) {
			$badImageCache = $badImages;
		}
	}

	$contextKey = $contextTitle ? $contextTitle->getPrefixedDBkey() : false;
	$bad = isset( $badImages[$name] ) && !isset( $badImages[$name][$contextKey] );
	wfProfileOut( __METHOD__ );
	return $bad;
}
