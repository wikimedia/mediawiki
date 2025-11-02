<?php
/**
 * Variant of QueryPage which formats the result as a simple link to the page.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\Html\Html;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Linker\Linker;
use MediaWiki\MediaWikiServices;
use MediaWiki\Skin\Skin;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Variant of QueryPage which formats the result as a simple link to the page
 *
 * @stable to extend
 * @ingroup SpecialPage
 */
abstract class PageQueryPage extends QueryPage {

	/** @var ILanguageConverter|null */
	private $languageConverter = null;

	/**
	 * Run a LinkBatch to pre-cache LinkCache information,
	 * like page existence and information for stub color and redirect hints.
	 * This should be done for live data and cached data.
	 *
	 * @stable to override
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @since 1.36
	 * @param ILanguageConverter $languageConverter
	 */
	final protected function setLanguageConverter( ILanguageConverter $languageConverter ) {
		$this->languageConverter = $languageConverter;
	}

	/**
	 * @note Call self::setLanguageConverter in your constructor when overriding
	 *
	 * @since 1.36
	 * @return ILanguageConverter
	 */
	final protected function getLanguageConverter(): ILanguageConverter {
		if ( $this->languageConverter === null ) {
			// Fallback if not provided
			// TODO Change to wfWarn in a future release
			$this->languageConverter = MediaWikiServices::getInstance()->getLanguageConverterFactory()
				->getLanguageConverter( $this->getContentLanguage() );
		}
		return $this->languageConverter;
	}

	/**
	 * Format the result as a simple link to the page
	 *
	 * @stable to override
	 *
	 * @param Skin $skin
	 * @param stdClass $row Result row
	 * @return string
	 */
	public function formatResult( $skin, $row ) {
		$title = Title::makeTitleSafe( $row->namespace, $row->title );
		if ( $title instanceof Title ) {

			$text = $this->getLanguageConverter()->convertHtml( $title->getPrefixedText() );
			return $this->getLinkRenderer()->makeLink( $title, new HtmlArmor( $text ) );
		} else {
			return Html::element( 'span', [ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription( $this->getContext(), $row->namespace, $row->title ) );
		}
	}
}

/** @deprecated class alias since 1.41 */
class_alias( PageQueryPage::class, 'PageQueryPage' );
