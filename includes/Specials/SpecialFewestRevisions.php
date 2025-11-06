<?php
/**
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
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List articles with the fewest revisions.
 *
 * @ingroup SpecialPage
 * @author Martin Drashkov
 */
class SpecialFewestRevisions extends QueryPage {

	private NamespaceInfo $namespaceInfo;
	private ILanguageConverter $languageConverter;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Fewestrevisions' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		return [
			'tables' => [ 'revision', 'page' ],
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'COUNT(*)',
			],
			'conds' => [
				'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
				'page_id = rev_page',
				'page_is_redirect' => 0,
			],
			'options' => [
				'GROUP BY' => [ 'page_namespace', 'page_title' ]
			]
		];
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result Database row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$nt = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$nt ) {
			return Html::element(
				'span',
				[ 'class' => 'mw-invalidtitle' ],
				Linker::getInvalidTitleDescription(
					$this->getContext(),
					$result->namespace,
					$result->title
				)
			);
		}
		$linkRenderer = $this->getLinkRenderer();

		$text = $this->languageConverter->convertHtml( $nt->getPrefixedText() );
		$plink = $linkRenderer->makeLink( $nt, new HtmlArmor( $text ) );

		$nl = $this->msg( 'nrevisions' )->numParams( $result->value )->text();
		$redirect = isset( $result->redirect ) && $result->redirect ?
			' - ' . $this->msg( 'isredirect' )->escaped() : '';
		$nlink = $linkRenderer->makeKnownLink(
			$nt,
			$nl,
			[],
			[ 'action' => 'history' ]
		) . $redirect;

		return $this->getLanguage()->specialList( $plink, $nlink );
	}

	/**
	 * Cache page existence for performance
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	protected function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialFewestRevisions::class, 'SpecialFewestRevisions' );
