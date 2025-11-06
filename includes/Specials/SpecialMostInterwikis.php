<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of pages that have the highest interwiki count.
 *
 * @ingroup SpecialPage
 */
class SpecialMostInterwikis extends QueryPage {

	private NamespaceInfo $namespaceInfo;

	public function __construct(
		NamespaceInfo $namespaceInfo,
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory
	) {
		parent::__construct( 'Mostinterwikis' );
		$this->namespaceInfo = $namespaceInfo;
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
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
			'tables' => [
				'langlinks',
				'page'
			], 'fields' => [
				'namespace' => 'page_namespace',
				'title' => 'page_title',
				'value' => 'COUNT(*)'
			], 'conds' => [
				'page_namespace' => $this->namespaceInfo->getContentNamespaces()
			], 'options' => [
				'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => [
					'page_namespace',
					'page_title'
				]
			], 'join_conds' => [
				'page' => [
					'LEFT JOIN',
					'page_id = ll_from'
				]
			]
		];
	}

	/**
	 * Pre-fill the link cache
	 *
	 * @param IReadableDatabase $db
	 * @param IResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		$this->executeLBFromResultWrapper( $res );
	}

	/**
	 * @param Skin $skin
	 * @param stdClass $result
	 * @return string
	 */
	public function formatResult( $skin, $result ) {
		$title = Title::makeTitleSafe( $result->namespace, $result->title );
		if ( !$title ) {
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
		if ( $this->isCached() ) {
			$link = $linkRenderer->makeLink( $title );
		} else {
			$link = $linkRenderer->makeKnownLink( $title );
		}

		$count = $this->msg( 'ninterwikis' )->numParams( $result->value )->escaped();

		return $this->getLanguage()->specialList( $link, $count );
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
class_alias( SpecialMostInterwikis::class, 'SpecialMostInterwikis' );
