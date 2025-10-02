<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason, 2006 Rob Church
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\LinksUpdate\PageLinksTable;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Skin\Skin;
use MediaWiki\SpecialPage\QueryPage;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * List of pages ordered by the number of pages linking to them.
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @author Rob Church <robchur@gmail.com>
 */
class SpecialMostLinked extends QueryPage {

	private LinksMigration $linksMigration;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Mostlinked' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->linksMigration = $linksMigration;
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
		[ $ns, $title ] = $this->linksMigration->getTitleFields( 'pagelinks' );
		$queryInfo = $this->linksMigration->getQueryInfo( 'pagelinks' );
		return [
			'tables' => $queryInfo['tables'],
			'fields' => [
				'namespace' => $ns,
				'title' => $title,
				'value' => 'COUNT(*)'
			],
			'options' => [
				'HAVING' => 'COUNT(*) > 1',
				'GROUP BY' => [ $ns, $title ],
			],
			'join_conds' => $queryInfo['joins'],
		];
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			PageLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
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
	 * Make a link to "what links here" for the specified title
	 *
	 * @param Title $title Title being queried
	 * @param string $caption Text to display on the link
	 * @return string
	 */
	private function makeWlhLink( $title, $caption ) {
		$wlh = SpecialPage::getTitleFor( 'Whatlinkshere', $title->getPrefixedDBkey() );

		$linkRenderer = $this->getLinkRenderer();
		return $linkRenderer->makeKnownLink( $wlh, $caption );
	}

	/**
	 * Make links to the page corresponding to the item,
	 * and the "what links here" page for it
	 *
	 * @param Skin $skin Skin to be used
	 * @param stdClass $result Result row
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
					$result->title )
			);
		}

		$linkRenderer = $this->getLinkRenderer();
		$link = $linkRenderer->makeLink( $title );
		$wlh = $this->makeWlhLink(
			$title,
			$this->msg( 'nlinks' )->numParams( $result->value )->text()
		);

		return $this->getLanguage()->specialList( $link, $wlh );
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
class_alias( SpecialMostLinked::class, 'SpecialMostLinked' );
