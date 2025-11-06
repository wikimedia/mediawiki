<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Implements Special:Ancientpages
 *
 * @ingroup SpecialPage
 */
class SpecialAncientPages extends QueryPage {

	private NamespaceInfo $namespaceInfo;
	private ILanguageConverter $languageConverter;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'Ancientpages' );
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
		$tables = [ 'page', 'revision' ];
		$conds = [
			'page_namespace' => $this->namespaceInfo->getContentNamespaces(),
			'page_is_redirect' => 0
		];
		$joinConds = [
			'revision' => [
				'JOIN', [
					'page_latest = rev_id'
				]
			],
		];

		// Allow extensions to modify the query
		$this->getHookRunner()->onAncientPagesQuery( $tables, $conds, $joinConds );

		return [
			'tables' => $tables,
			'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'rev_timestamp'
			],
			'conds' => $conds,
			'join_conds' => $joinConds
		];
	}

	/** @inheritDoc */
	public function usesTimestamps() {
		return true;
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param \stdClass $result Result row
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$d = $this->getLanguage()->userTimeAndDate( $result->value, $this->getUser() );
		$title = Title::makeTitle( $result->namespace, $result->title );
		$linkRenderer = $this->getLinkRenderer();

		$link = $linkRenderer->makeKnownLink(
			$title,
			new HtmlArmor( $this->languageConverter->convertHtml( $title->getPrefixedText() ) )
		);

		return $this->getLanguage()->specialList( $link, htmlspecialchars( $d ) );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialAncientPages::class, 'SpecialAncientPages' );
