<?php
/**
 * Copyright © 2005, Ævar Arnfjörð Bjarmason
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\Linker;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of categories with the most pages in them
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialMostLinkedCategories extends QueryPage {

	private ILanguageConverter $languageConverter;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Mostlinkedcategories' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$dbr = $this->getDatabaseProvider()->getReplicaDatabase();
		return [
			'tables' => [ 'category' ],
			'fields' => [ 'title' => 'cat_title',
				'namespace' => NS_CATEGORY,
				'value' => 'cat_pages' ],
			'conds' => [ $dbr->expr( 'cat_pages', '>', 0 ) ],
		];
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return true;
	}

	/**
	 * Fetch user page links and cache their existence
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$nt = Title::makeTitleSafe( NS_CATEGORY, $result->title );
		if ( !$nt ) {
			return Html::element(
				'span',
				[ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription(
					$this->getContext(),
					NS_CATEGORY,
					$result->title )
			);
		}

		$text = $this->languageConverter->convertHtml( $nt->getText() );

		$plink = $this->getLinkRenderer()->makeLink( $nt, new HtmlArmor( $text ) );
		$nlinks = $this->msg( 'nmembers' )->numParams( $result->value )->escaped();

		return $this->getLanguage()->specialList( $plink, $nlinks );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'highuse';
	}
}
/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialMostLinkedCategories::class, 'SpecialMostLinkedCategories' );
