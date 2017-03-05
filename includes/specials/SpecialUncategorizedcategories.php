<?php
/**
 * Implements Special:Uncategorizedcategories
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
 * A special page that lists uncategorized categories
 *
 * @ingroup SpecialPage
 */
class UncategorizedCategoriesPage extends UncategorizedPagesPage {
	/**
	 * Holds a list of categories, which shouldn't be listed on this special page,
	 * even if it is uncategorized.
	 * @var array
	 */
	private $exceptionList = null;

	function __construct( $name = 'Uncategorizedcategories' ) {
		parent::__construct( $name );
		$this->requestedNamespace = NS_CATEGORY;
	}

	/**
	 * Returns an array of category titles (usually without the namespace), which
	 * shouldn't be listed on this page, even if they're uncategorized.
	 *
	 * @return array
	 */
	private function getExceptionList() {
		if ( $this->exceptionList === null ) {
			$exList = $this->msg( 'uncategorized-categories-exceptionlist' )
				->inContentLanguage()->plain();
			$proposedTitles = explode( "\n", $exList );
			foreach ( $proposedTitles as $count => $titleStr ) {
				if ( strpos( $titleStr, '*' ) !== 0 ) {
					continue;
				}
				$titleStr = preg_replace( "/^\\*\\s*/", '', $titleStr );
				$title = Title::newFromText( $titleStr, NS_CATEGORY );
				if ( $title && $title->getNamespace() !== NS_CATEGORY ) {
					$title = Title::makeTitleSafe( NS_CATEGORY, $titleStr );
				}
				if ( $title ) {
					$this->exceptionList[] = $title->getDBKey();
				}
			}
		}
		return $this->exceptionList;
	}

	public function getQueryInfo() {
		$dbr = wfGetDB( DB_SLAVE );
		$query = parent::getQueryInfo();
		$exceptionList = $this->getExceptionList();
		if ( $exceptionList ) {
			$query['conds'][] = 'page_title not in ( ' . $dbr->makeList( $exceptionList ) . ' )';
		}

		return $query;
	}

	/**
	 * Formats the result
	 * @param Skin $skin The current skin
	 * @param object $result The query result
	 * @return string The category link
	 */
	function formatResult( $skin, $result ) {
		$title = Title::makeTitle( NS_CATEGORY, $result->title );
		$text = $title->getText();

		return $this->getLinkRenderer()->makeKnownLink( $title, $text );
	}
}
