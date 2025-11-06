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
			'tables' => [ 'imagelinks' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => 'il_to',
				'value' => 'COUNT(*)'
			],
			'options' => [
				'GROUP BY' => 'il_to',
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

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.40
 */
class_alias( SpecialMostImages::class, 'MostimagesPage' );
