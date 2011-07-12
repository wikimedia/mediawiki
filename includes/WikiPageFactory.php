<?php
/**
 * Factory class for classes representing a MediaWiki article and history.
 */
class WikiPageFactory {
	/**
	 * Create an WikiPage object of the appropriate class for the given title.
	 *
	 * @param $title Title
	 * @return WikiPage object
	 */
	public static function newFromTitle( Title $title ) {
		switch( $title->getNamespace() ) {
			case NS_MEDIA:
				throw new MWException( "NS_MEDIA is a virtual namespace" );
			case NS_FILE:
				$page = new WikiFilePage( $title );
				break;
			case NS_CATEGORY:
				$page = new WikiCategoryPage( $title );
				break;
			default:
				$page = new WikiPage( $title );
		}

		return $page;
	}
}
