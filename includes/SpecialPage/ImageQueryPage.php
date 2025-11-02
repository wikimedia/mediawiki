<?php
/**
 * Variant of QueryPage which uses a gallery to output results.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use ImageGalleryBase;
use MediaWiki\Output\OutputPage;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Variant of QueryPage which uses a gallery to output results, thus
 * suited for reports generating images
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
abstract class ImageQueryPage extends QueryPage {
	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @stable to override
	 *
	 * @param OutputPage $out OutputPage to print to
	 * @param Skin $skin User skin to use [unused]
	 * @param IReadableDatabase $dbr (read) connection to use
	 * @param IResultWrapper $res Result pointer
	 * @param int $num Number of available result rows
	 * @param int $offset Paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		if ( $num > 0 ) {
			$gallery = ImageGalleryBase::factory( false, $this->getContext() );

			// $res might contain the whole 1,000 rows, so we read up to
			// $num [should update this to use a Pager]
			$i = 0;
			foreach ( $res as $row ) {
				$i++;
				$namespace = $row->namespace ?? NS_FILE;
				$title = Title::makeTitleSafe( $namespace, $row->title );
				if ( $title instanceof Title && $title->inNamespace( NS_FILE ) ) {
					$gallery->add( $title, $this->getCellHtml( $row ), '', '', [], ImageGalleryBase::LOADING_LAZY );
				}
				if ( $i === $num ) {
					break;
				}
			}

			$out->addHTML( $gallery->toHTML() );
		}
	}

	/**
	 * @stable to override
	 *
	 * @param Skin $skin
	 * @param stdClass $result
	 *
	 * @return bool|string
	 */
	protected function formatResult( $skin, $result ) {
		return false;
	}

	/**
	 * Get additional HTML to be shown in a results' cell
	 *
	 * @stable to override
	 *
	 * @param stdClass $row Result row
	 * @return string
	 */
	protected function getCellHtml( $row ) {
		return '';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( ImageQueryPage::class, 'ImageQueryPage' );
