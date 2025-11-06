<?php
/**
 * Copyright © 2008, Danny B.
 * Based on SpecialWantedcategories.php by Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * makeWlhLink() taken from SpecialMostlinkedtemplates by Rob Church <robchur@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\Deferred\LinksUpdate\TemplateLinksTable;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\SpecialPage\WantedQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most wanted templates
 *
 * @ingroup SpecialPage
 * @author Danny B.
 */
class SpecialWantedTemplates extends WantedQueryPage {

	private LinksMigration $linksMigration;

	public function __construct(
		IConnectionProvider $dbProvider,
		LinkBatchFactory $linkBatchFactory,
		LinksMigration $linksMigration
	) {
		parent::__construct( 'Wantedtemplates' );
		$this->setDatabaseProvider( $dbProvider );
		$this->setLinkBatchFactory( $linkBatchFactory );
		$this->linksMigration = $linksMigration;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		$queryInfo = $this->linksMigration->getQueryInfo( 'templatelinks' );
		[ $ns, $title ] = $this->linksMigration->getTitleFields( 'templatelinks' );
		return [
			'tables' => array_merge( $queryInfo['tables'], [ 'page' ] ),
			'fields' => [
				'namespace' => $ns,
				'title' => $title,
				'value' => 'COUNT(*)'
			],
			'conds' => [
				'page_title' => null,
				$ns => NS_TEMPLATE
			],
			'options' => [ 'GROUP BY' => [ $ns, $title ] ],
			'join_conds' => array_merge(
				[ 'page' => [ 'LEFT JOIN',
					[ "page_namespace = $ns", "page_title = $title" ] ] ],
				$queryInfo['joins']
			)
		];
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			TemplateLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialWantedTemplates::class, 'SpecialWantedTemplates' );
