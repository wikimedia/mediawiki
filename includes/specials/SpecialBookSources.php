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

use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;

/**
 * Special page outputs information on sourcing a book with a particular ISBN
 * The parser creates links to this page when dealing with ISBNs in wikitext
 *
 * @author Rob Church <robchur@gmail.com>
 * @ingroup SpecialPage
 */
class SpecialBookSources extends SpecialPage {
	public function __construct() {
		parent::__construct( 'Booksources' );
	}

	/**
	 * @param string|null $isbn ISBN passed as a subpage parameter
	 */
	public function execute( $isbn ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$this->outputHeader();

		// User provided ISBN
		$isbn = $isbn ?: $this->getRequest()->getText( 'isbn' );
		$isbn = trim( $isbn );

		$this->buildForm( $isbn );

		if ( $isbn !== '' ) {
			if ( !self::isValidISBN( $isbn ) ) {
				$out->wrapWikiMsg(
					"<div class=\"error\">\n$1\n</div>",
					'booksources-invalid-isbn'
				);
			}

			$this->showList( $isbn );
		}
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
	 * @param string $isbn
	 */
	private function buildForm( $isbn ) {
		$formDescriptor = [
			'isbn' => [
				'type' => 'text',
				'name' => 'isbn',
				'label-message' => 'booksources-isbn',
				'default' => $isbn,
				'autofocus' => true,
				'required' => true,
			],
		];

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() );
		HTMLForm::factory( 'ooui', $formDescriptor, $context )
			->setWrapperLegendMsg( 'booksources-search-legend' )
			->setSubmitTextMsg( 'booksources-search' )
			->setMethod( 'get' )
			->prepareForm()
			->displayForm( false );
	}

	/**
	 * Determine where to get the list of book sources from,
	 * format and output them
	 *
	 * @param string $isbn
	 * @throws MWException
	 * @return bool
	 */
	private function showList( $isbn ) {
		$out = $this->getOutput();

		$isbn = self::cleanIsbn( $isbn );
		# Hook to allow extensions to insert additional HTML,
		# e.g. for API-interacting plugins and so on
		$this->getHookRunner()->onBookInformation( $isbn, $out );

		# Check for a local page such as Project:Book_sources and use that if available
		$page = $this->msg( 'booksources' )->inContentLanguage()->text();
		$title = Title::makeTitleSafe( NS_PROJECT, $page ); # Show list in content language
		if ( is_object( $title ) && $title->exists() ) {
			$rev = MediaWikiServices::getInstance()
				->getRevisionLookup()
				->getRevisionByTitle( $title );
			$content = $rev->getContent( SlotRecord::MAIN );

			if ( $content instanceof TextContent ) {
				// XXX: in the future, this could be stored as structured data, defining a list of book sources

				$text = $content->getText();
				$out->addWikiTextAsInterface( str_replace( 'MAGICNUMBER', $isbn, $text ) );

				return true;
			} else {
				throw new MWException( "Unexpected content type for book sources: " . $content->getModel() );
			}
		}

		# Fall back to the defaults given in the language file
		$out->addWikiMsg( 'booksources-text' );
		$out->addHTML( '<ul>' );
		$items = MediaWikiServices::getInstance()->getContentLanguage()->getBookstoreList();
		foreach ( $items as $label => $url ) {
			$out->addHTML( $this->makeListItem( $isbn, $label, $url ) );
		}
		$out->addHTML( '</ul>' );

		return true;
	}

	/**
	 * Format a book source list item
	 *
	 * @param string $isbn
	 * @param string $label Book source label
	 * @param string $url Book source URL
	 * @return string
	 */
	private function makeListItem( $isbn, $label, $url ) {
		$url = str_replace( '$1', $isbn, $url );

		return Html::rawElement( 'li', [],
			Html::element( 'a', [ 'href' => $url, 'class' => 'external' ], $label )
		);
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
