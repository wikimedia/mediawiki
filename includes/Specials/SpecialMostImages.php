<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\SpecialPage\ImageQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most used images.
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialMostImages extends ImageQueryPage {

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Mostimages' );
		$this->setDatabaseProvider( $dbProvider );
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
			'tables' => [ 'imagelinks', 'linktarget' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'lt_title',
				'value' => 'COUNT(*)'
			],
			'join_conds' => [ 'linktarget' => [ 'JOIN', 'il_target_id = lt_id' ] ],
			'options' => [
				'GROUP BY' => 'lt_title',
				'HAVING' => 'COUNT(*) > 1'
			]
		];
	}

	/** @inheritDoc */
	protected function getCellHtml( $row ) {
		return $this->msg( 'nimagelinks' )->numParams( $row->value )->escaped() . '<br />';
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'highuse';
	}

	/** @inheritDoc */
	protected function getRecacheDB() {
		return $this->getDatabaseProvider()->getReplicaDatabase(
			ImageLinksTable::VIRTUAL_DOMAIN,
			'vslow'
		);
	}
}

// @codeCoverageIgnoreStart
/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialMostImages::class, 'MostimagesPage' );
// @codeCoverageIgnoreEnd
