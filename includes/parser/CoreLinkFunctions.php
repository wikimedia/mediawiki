<?php
/**
 * Link functions provided by MediaWiki core; experimental
 *
 * @file
 */

/**
 * Various core link functions, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreLinkFunctions {
	static function register( $parser ) {
		$parser->setLinkHook( NS_CATEGORY, array( __CLASS__, 'categoryLinkHook' ) );
		return true;
	}

	static function defaultLinkHook( $parser, $holders, $markers,
			Title $title, $titleText, &$displayText = null, &$leadingColon = false ) {
		if( isset($displayText) && $markers->findMarker( $displayText ) ) {
			# There are links inside of the displayText
			# For backwards compatibility the deepest links are dominant so this
			# link should not be handled
			$displayText = $markers->expand($displayText);
			# Return false so that this link is reverted back to WikiText
			return false;
		}
		return $holders->makeHolder( $title, isset($displayText) ? $displayText : $titleText, '', '', '' );
	}

	static function categoryLinkHook( $parser, $holders, $markers,
			Title $title, $titleText, &$sortText = null, &$leadingColon = false ) {
		global $wgContLang;
		# When a category link starts with a : treat it as a normal link
		if( $leadingColon ) return true;
		if( isset($sortText) && $markers->findMarker( $sortText ) ) {
			# There are links inside of the sortText
			# For backwards compatibility the deepest links are dominant so this
			# link should not be handled
			$sortText = $markers->expand($sortText);
			# Return false so that this link is reverted back to WikiText
			return false;
		}
		if( !isset($sortText) ) $sortText = $parser->getDefaultSort();
		$sortText = Sanitizer::decodeCharReferences( $sortText );
		$sortText = str_replace( "\n", '', $sortText );
		$sortText = $wgContLang->convertCategoryKey( $sortText );
		$parser->mOutput->addCategory( $title->getDBkey(), $sortText );
		return '';
	}

}
