<?php
/**
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Deferred\LinksUpdate\ImageLinksTable;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\ImageQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of the most used images.
 *
 * @ingroup SpecialPage
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */
class SpecialMostImages extends ImageQueryPage {

	private int $imageLinksMigrationStage;

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Mostimages' );
		$this->setDatabaseProvider( $dbProvider );
		$this->imageLinksMigrationStage = $this->getConfig()->get( MainConfigNames::ImageLinksSchemaMigrationStage );
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
		if ( $this->imageLinksMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$linksTables = [ 'imagelinks' ];
			$titleField = 'il_to';
			$joinConds = [];
		} else {
			$linksTables = [ 'imagelinks', 'linktarget' ];
			$titleField = 'lt_title';
			$joinConds = [ 'linktarget' => [ 'JOIN', 'il_target_id = lt_id' ] ];
		}

		return [
			'tables' => $linksTables,
			'fields' => [
				'namespace' => NS_FILE,
				'title' => $titleField,
				'value' => 'COUNT(*)'
			],
			'join_conds' => $joinConds,
			'options' => [
				'GROUP BY' => $titleField,
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
