<?php
/**
 * Link functions provided by MediaWiki core; experimental
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Parser
 */

/**
 * Various core link functions, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreLinkFunctions {
	/**
	 * @param $parser Parser_LinkHooks
	 * @return bool
	 */
	static function register( $parser ) {
		$parser->setLinkHook( NS_CATEGORY, array( __CLASS__, 'categoryLinkHook' ) );
		return true;
	}

	/**
	 * @param $parser Parser
	 * @param $holders LinkHolderArray
	 * @param $markers LinkMarkerReplacer
	 * @param Title $title
	 * @param $titleText
	 * @param null $displayText
	 * @param bool $leadingColon
	 * @return bool
	 */
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
		return $holders->makeHolder( $title, isset($displayText) ? $displayText : $titleText, array(), '', '' );
	}

	/**
	 * @param  $parser Parser
	 * @param  $holders LinkHolderArray
	 * @param  $markers LinkMarkerReplacer
	 * @param Title $title
	 * @param  $titleText
	 * @param null $sortText
	 * @param bool $leadingColon
	 * @return bool|string
	 */
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
