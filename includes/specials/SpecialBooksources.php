<?php
/**
 * Implements Special:Booksources
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
 * Special page outputs information on sourcing a book with a particular ISBN
 * The parser creates links to this page when dealing with ISBNs in wikitext
 *
 * @author Rob Church <robchur@gmail.com>
 * @ingroup SpecialPage
 */
class SpecialBookSources extends SpecialPage {
	/**
	 * ISBN passed to the page, if any
	 */
	private $isbn = '';

	public function __construct() {
		parent::__construct( 'Booksources' );
	}

	/**
	 * Show the special page
	 *
	 * @param string $isbn ISBN passed as a subpage parameter
	 */

	public function execute($isbn) {
		$hiddenFields = [
            'title' => $this->getPageTitle()->getPrefixedDBkey(),
        ];
        $this->setHeaders();
        $formDescriptor = array(
            'simpletextfield' => array(
                    'label' => 'ISBN',
                    'class' => 'HTMLTextField',
                    'size' => 20,
            )
        );

        $htmlForm = HTMLForm::factory( 'ooui',$formDescriptor, $this->getContext() );
        $htmlForm->setSubmitText( 'Search' );
        $htmlForm->setWrapperLegendMsg( 'booksources-search-legend' );
        $htmlForm->addHiddenFields( $hiddenFields );
        $htmlForm->setAction( wfScript() );
        $htmlForm->setMethod( 'get' );
        $htmlForm->prepareForm()->displayForm( false );
	}

	/**
	 * Return whether a given ISBN (10 or 13) is valid.
	 *
	 * @param string $isbn ISBN passed for check
	 * @return bool
	 */
	public static function isValidISBN( $isbn ) {
		$isbn = self::cleanIsbn( $isbn );
		$sum = 0;
		if ( strlen( $isbn ) == 13 ) {
			for ( $i = 0; $i < 12; $i++ ) {
				if ( $isbn[$i] === 'X' ) {
					return false;
				} elseif ( $i % 2 == 0 ) {
					$sum += $isbn[$i];
				} else {
					$sum += 3 * $isbn[$i];
				}
			}

			$check = ( 10 - ( $sum % 10 ) ) % 10;
			if ( (string)$check === $isbn[12] ) {
				return true;
			}
		} elseif ( strlen( $isbn ) == 10 ) {
			for ( $i = 0; $i < 9; $i++ ) {
				if ( $isbn[$i] === 'X' ) {
					return false;
				}
				$sum += $isbn[$i] * ( $i + 1 );
			}

			$check = $sum % 11;
			if ( $check == 10 ) {
				$check = "X";
			}
			if ( (string)$check === $isbn[9] ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Trim ISBN and remove characters which aren't required
	 *
	 * @param string $isbn Unclean ISBN
	 * @return string
	 */
	private static function cleanIsbn( $isbn ) {
		return trim( preg_replace( '![^0-9X]!', '', $isbn ) );
	}

	/**
	 * Generate a form to allow users to enter an ISBN
	 *
	 * @return string
	 */

	/**
	 * Determine where to get the list of book sources from,
	 * format and output them
	 *
	 * @throws MWException
	 * @return bool
	 */
	private function showList() {
		global $wgContLang;

		# Hook to allow extensions to insert additional HTML,
		# e.g. for API-interacting plugins and so on
		Hooks::run( 'BookInformation', [ $this->isbn, $this->getOutput() ] );

		# Check for a local page such as Project:Book_sources and use that if available
		$page = $this->msg( 'booksources' )->inContentLanguage()->text();
		$title = Title::makeTitleSafe( NS_PROJECT, $page ); # Show list in content language
		if ( is_object( $title ) && $title->exists() ) {
			$rev = Revision::newFromTitle( $title, false, Revision::READ_NORMAL );
			$content = $rev->getContent();

			if ( $content instanceof TextContent ) {
				// XXX: in the future, this could be stored as structured data, defining a list of book sources

				$text = $content->getNativeData();
				$this->getOutput()->addWikiText( str_replace( 'MAGICNUMBER', $this->isbn, $text ) );

				return true;
			} else {
				throw new MWException( "Unexpected content type for book sources: " . $content->getModel() );
			}
		}

		# Fall back to the defaults given in the language file
		$this->getOutput()->addWikiMsg( 'booksources-text' );
		$this->getOutput()->addHTML( '<ul>' );
		$items = $wgContLang->getBookstoreList();
		foreach ( $items as $label => $url ) {
			$this->getOutput()->addHTML( $this->makeListItem( $label, $url ) );
		}
		$this->getOutput()->addHTML( '</ul>' );

		return true;
	}

	/**
	 * Format a book source list item
	 *
	 * @param string $label Book source label
	 * @param string $url Book source URL
	 * @return string
	 */
	private function makeListItem( $label, $url ) {
		$url = str_replace( '$1', $this->isbn, $url );

		return Html::rawElement( 'li', [],
			Html::element( 'a', [ 'href' => $url, 'class' => 'external' ], $label )
		);
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
