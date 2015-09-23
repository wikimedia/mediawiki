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

/**
 * Class definition for a wanted query page like
 * WantedPages, WantedTemplates, etc
 * @ingroup SpecialPage
 */
abstract class WantedQueryPage extends QueryPage {
	function isExpensive() {
		return true;
	}

	function isSyndicated() {
		return false;
	}

	/**
	 * Cache page existence for performance
	 * @param IDatabase $db
	 * @param ResultWrapper $res
	 */
	function preprocessResults( $db, $res ) {
		if ( !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch;
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		// Back to start for display
		$res->seek( 0 );
	}

	/**
	 * Should formatResult() always check page existence, even if
	 * the results are fresh?  This is a (hopefully temporary)
	 * kluge for Special:WantedFiles, which may contain false
	 * positives for files that exist e.g. in a shared repo (bug
	 * 6220).
	 * @return bool
	 */
	function forceExistenceCheck() {
		return false;
	}

	/**
	 * Format an individual result
	 *
	 * @param Skin $skin Skin to use for UI elements
	 * @param object $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( $title instanceof Title ) {
			if ( $this->isCached() || $this->forceExistenceCheck() ) {
				$pageLink = $this->existenceCheck( $title )
					? '<del>' . Linker::link( $title ) . '</del>'
					: Linker::link( $title );
			} else {
				$pageLink = Linker::link(
					$title,
					null,
					array(),
					array(),
					array( 'broken' )
				);
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
	private function makeWlhLink( $title, $result ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedText() );
		$label = $this->msg( 'nlinks' )->numParams( $result->value )->escaped();
		return Linker::link( $wlh, $label );
	}
}
