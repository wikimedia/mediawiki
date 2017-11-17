<?php

namespace MediaWiki\Storage;

use Title;

/**
 * FIXME: document!
 * FIXME: should probably be in a different namespace!
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PageContentComposer {

	/**
	 * Create a WikiPage object of the appropriate class for the given title.
	 *
	 * MCR migration note: this replaces Wikisome use cases of Page::factory().
	 *
	 * @param Title $title
	 *
	 * @throws MWException
	 * @return WikiPage|WikiCategoryPage|WikiFilePage
	 */
	public function newContentComposer( Title $title ) {
		// FIXME: we may need a single slot vs a fully composed version!

		$ns = $title->getNamespace();

		if ( $ns == NS_MEDIA ) {
			throw new MWException( "NS_MEDIA is a virtual namespace; use NS_FILE." );
		} elseif ( $ns < 0 ) {
			throw new MWException( "Invalid or virtual namespace $ns given." );
		}

		$page = null;
		if ( !Hooks::run( 'WikiPageFactory', [ $title, &$page ] ) ) {
			return $page;
		}

		// FIXME: these are PageContentComposers!
		switch ( $ns ) {
			case NS_FILE:
				$page = new FilePageComposer( $title );
				break;
			case NS_CATEGORY:
				$page = new CategoryPageComposer( $title );
				break;
			default:
				$page = new WikiPageComposer( $title );
		}

		return $page;
	}

}
