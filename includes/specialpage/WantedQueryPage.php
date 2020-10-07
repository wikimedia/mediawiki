<?php
/**
 * Class definition for a wanted query page.
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
 * @ingroup SpecialPage
 */

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Class definition for a wanted query page like
 * WantedPages, WantedTemplates, etc
 * @stable to extend
 * @ingroup SpecialPage
 */
abstract class WantedQueryPage extends QueryPage {
	public function isExpensive() {
		return true;
	}

	public function isSyndicated() {
		return false;
	}

	/**
	 * Cache page existence for performance
	 * @stable to override
	 * @param IDatabase $db
	 * @param IResultWrapper $res
	 */
	protected function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * Should formatResult() always check page existence, even if
	 * the results are fresh?  This is a (hopefully temporary)
	 * kluge for Special:WantedFiles, which may contain false
	 * positives for files that exist e.g. in a shared repo (bug
	 * 6220).
	 * @stable to override
	 * @return bool
	 */
	protected function forceExistenceCheck() {
		return false;
	}

	/**
	 * Format an individual result
	 *
	 * @stable to override
	 *
	 * @param Skin $skin Skin to use for UI elements
	 * @param object $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$linkRenderer = $this->getLinkRenderer();
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( $title instanceof Title ) {
			if ( $this->isCached() || $this->forceExistenceCheck() ) {
				$pageLink = $this->existenceCheck( $title )
					? '<del>' . $linkRenderer->makeLink( $title ) . '</del>'
					: $linkRenderer->makeLink( $title );
			} else {
				$pageLink = $linkRenderer->makeBrokenLink( $title );
			}
			return $this->getLanguage()->specialList( $pageLink, $this->makeWlhLink( $title, $result ) );
		} else {
			return $this->msg( 'wantedpages-badtitle', $result->title )->escaped();
		}
	}

	/**
	 * Does the Title currently exists
	 *
	 * This method allows a subclass to override this check
	 * (For example, wantedfiles, would want to check if the file exists
	 * not just that a page in the file namespace exists).
	 *
	 * This will only control if the link is crossed out. Whether or not the link
	 * is blue vs red is controlled by if the title exists.
	 *
	 * @note This will only be run if the page is cached (ie $wgMiserMode = true)
	 *   unless forceExistenceCheck() is true.
	 * @since 1.24
	 * @stable to override
	 *
	 * @param Title $title
	 * @return bool
	 */
	protected function existenceCheck( Title $title ) {
		return $title->isKnown();
	}

	/**
	 * Make a "what links here" link for a given title
	 *
	 * @param Title $title Title to make the link for
	 * @param object $result Result row
	 * @return string
	 */
	protected function makeWlhLink( $title, $result ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() );
		$label = $this->msg( 'nlinks' )->numParams( $result->value )->text();
		return $this->getLinkRenderer()->makeLink( $wlh, $label );
	}

	/**
	 * Order by title for pages with the same number of links to them
	 *
	 * @stable to override
	 * @return array
	 * @since 1.29
	 */
	protected function getOrderFields() {
		return [ 'value DESC', 'namespace', 'title' ];
	}

	/**
	 * Do not order descending for all order fields.  We will use DESC only on one field, see
	 * getOrderFields above. This overwrites sortDescending from QueryPage::getOrderFields().
	 * Do NOT change this to true unless you remove the phrase DESC in getOrderFiels above.
	 * If you do a database error will be thrown due to double adding DESC to query!
	 *
	 * @stable to override
	 * @return bool
	 * @since 1.29
	 */
	protected function sortDescending() {
		return false;
	}

	/**
	 * Also use the order fields returned by getOrderFields when fetching from the cache.
	 * @stable to override
	 * @return array
	 * @since 1.29
	 */
	protected function getCacheOrderFields() {
		return $this->getOrderFields();
	}

}
