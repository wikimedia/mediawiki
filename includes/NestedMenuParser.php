<?php
/**
 * A more advanced parser for parsing messages like MediaWiki:Sidebar into
 * pretty, modern, nested navigation menus.
 *
 * This has been forked from Oasis' NavigationService.
 * The class name was changed, "magic word" handling was removed from
 * parseMessage() and some (related) unused functions were also removed.
 *
 * @file
 * @since 1.23
 * @author Inez Korczyński
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 * @see https://github.com/Wikia/app/blob/release-136.020/includes/wikia/services/NavigationService.class.php
 * @see https://github.com/Wikia/app/commit/5b132a9dfff4f87c544791295749e44e4b724b92
 * @see Skin::buildSidebar(), Skin::addToSidebar(), Skin::addToSidebarPlain()
 */
class NestedMenuParser {

	/**
	 * Is the message we're supposed to parse in the wiki's content language
	 * (true) or not?
	 * @var bool $forContent
	 */
	private $forContent = false;

	/**
	 * Internal version number used to create the memcached keys in parseMessage()
	 */
	const version = '0.01';

	/**
	 * Parses a system message by exploding along newlines.
	 *
	 * @param string $messageName Name of the MediaWiki message to parse
	 * @param array $maxChildrenAtLevel Allowed number of items on each menu level
	 * @param int $duration Cache duration for memcached calls
	 * @param bool $forContent Is the message we're supposed to parse in the
	 *                          wiki's content language (true) or not?
	 * @return Array
	 */
	public function parseMessage( $messageName, array $maxChildrenAtLevel = array(), $duration, $forContent = false ) {
		wfProfileIn( __METHOD__ );

		global $wgLang, $wgContLang, $wgMemc;

		$this->forContent = $forContent;

		$useCache = $wgLang->getCode() == $wgContLang->getCode();

		if ( $useCache || $this->forContent ) {
			$cacheKey = wfMemcKey( $messageName, self::version );
			$nodes = $wgMemc->get( $cacheKey );
		}

		if ( empty( $nodes ) ) {
			if ( $this->forContent ) {
				$lines = explode( "\n", wfMessage( $messageName )->inContentLanguage()->text() );
			} else {
				$lines = explode( "\n", wfMessage( $messageName )->text() );
			}
			$nodes = $this->parseLines( $lines, $maxChildrenAtLevel );

			if ( $useCache || $this->forContent ) {
				$wgMemc->set( $cacheKey, $nodes, $duration );
			}
		}

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	/**
	 * Function used by parseMessage() above.
	 *
	 * @param array $lines Newline-separated lines from the supplied MW: msg
	 * @param array $maxChildrenAtLevel Allowed number of items on each menu level
	 * @return Array
	 */
	private function parseLines( array $lines, array $maxChildrenAtLevel = array() ) {
		wfProfileIn( __METHOD__ );

		$nodes = array();
		$lastDepth = 0;
		$i = 0;
		$lastSkip = null;

		foreach ( $lines as $line ) {
			// we are interested only in lines that are not empty and start
			// with asterisk, so discard empty lines or lines not matching
			// that condition
			if ( trim( $line ) === '' || $line[0] !== '*' ) {
				continue;
			}

			$depth = strrpos( $line, '*' ) + 1;

			if ( $lastSkip !== null && $depth >= $lastSkip ) {
				continue;
			} else {
				$lastSkip = null;
			}

			if ( $depth == $lastDepth + 1 ) {
				$parentIndex = $i;
			} elseif ( $depth == $lastDepth ) {
				$parentIndex = $nodes[$i]['parentIndex'];
			} else {
				for ( $x = $i; $x >= 0; $x-- ) {
					if ( $x == 0 ) {
						$parentIndex = 0;
						break;
					}
					if ( $nodes[$x]['depth'] <= $depth - 1 ) {
						$parentIndex = $x;
						break;
					}
				}
			}

			if (
				isset( $maxChildrenAtLevel[$depth - 1] ) &&
				isset( $nodes[$parentIndex]['children'] ) &&
				count( $nodes[$parentIndex]['children'] ) >= $maxChildrenAtLevel[$depth - 1]
			)
			{
				$lastSkip = $depth;
				continue;
			}

			$node = $this->parseOneLine( $line );
			$node['parentIndex'] = $parentIndex;
			$node['depth'] = $depth;

			$nodes[$node['parentIndex']]['children'][] = $i + 1;
			$nodes[$i + 1] = $node;
			$lastDepth = $node['depth'];
			$i++;
		}

		wfProfileOut( __METHOD__ );
		return $nodes;
	}

	/**
	 * Parse one line of a supplied message, figuring out the link target and
	 * what text to display.
	 *
	 * @param string $line Line to parse
	 * @return Array containing original, text and href keys
	 */
	private function parseOneLine( $line ) {
		wfProfileIn( __METHOD__ );

		// trim spaces and asterisks from line and then split it to maximum two chunks
		$lineArr = explode( '|', trim( $line, '* ' ), 2 );

		// trim [ and ] from line to have just http://en.wikipedia.org instead of [http://en.wikipedia.org] for external links
		$lineArr[0] = trim( $lineArr[0], '[]' );

		if ( count( $lineArr ) == 2 && $lineArr[1] != '' ) {
			$msgObj = wfMessage( $lineArr[0] );
			$link = ( $msgObj->isDisabled() ? $lineArr[0] : trim( $msgObj->inContentLanguage()->text() ) );
			$link = trim( wfMessage( $lineArr[0] )->inContentLanguage()->text() );
			$desc = trim( $lineArr[1] );
		} else {
			$link = $desc = trim( $lineArr[0] );
		}

		$text = $this->forContent ? wfMessage( $desc )->inContentLanguage() : wfMessage( $desc );
		if ( $text->isDisabled() ) {
			$text = $desc;
		}

		if ( preg_match( '/^(?:' . wfUrlProtocols() . ')/', $link ) ) {
			$href = $link;
		} elseif ( empty( $link ) ) {
			$href = '#';
		} elseif ( $link[0] == '#' ) {
			$href = '#';
		} else {
			$title = Title::newFromText( $link );
			if ( $title === null ) {
				$href = $title->fixSpecialName()->getLocalURL();
			} else {
				$href = '#';
			}
		}

		wfProfileOut( __METHOD__ );
		return array(
			'original' => $lineArr[0],
			'text' => $text,
			'href' => $href
		);
	}
}