<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\ImageQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of unused images
 *
 * @ingroup SpecialPage
 */
class SpecialUnusedImages extends ImageQueryPage {
	private int $migrationStage;

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Unusedimages' );
		$this->setDatabaseProvider( $dbProvider );
		$this->migrationStage = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::FileSchemaMigrationStage
		);
	}

	/** @inheritDoc */
	public function isExpensive() {
		return true;
	}

	/** @inheritDoc */
	protected function sortDescending() {
		return false;
	}

	/** @inheritDoc */
	public function isSyndicated() {
		return false;
	}

	/** @inheritDoc */
	public function getQueryInfo() {
		if ( $this->migrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$tables = [ 'image' ];
			$nameField = 'img_name';
			$timestampField = 'img_timestamp';
			$extraConds = [];
			$extraJoins = [];
		} else {
			$tables = [ 'file', 'filerevision' ];
			$nameField = 'file_name';
			$timestampField = 'fr_timestamp';
			$extraConds = [ 'file_deleted' => 0 ];
			$extraJoins = [ 'filerevision' => [ 'JOIN', 'file_latest = fr_id' ] ];
		}

		$retval = [
			'tables' => array_merge( $tables, [ 'imagelinks' ] ),
			'fields' => [
				'namespace' => NS_FILE,
				'title' => $nameField,
				'value' => $timestampField,
			],
			'conds' => array_merge( [ 'il_to' => null ], $extraConds ),
			'join_conds' => array_merge(
				[ 'imagelinks' => [ 'LEFT JOIN', 'il_to = ' . $nameField ] ],
				$extraJoins
			),
		];

		if ( $this->getConfig()->get( MainConfigNames::CountCategorizedImagesAsUsed ) ) {
			// Order is significant
			$retval['tables'] = [ 'image', 'page', 'categorylinks',
				'imagelinks' ];
			$retval['conds']['page_namespace'] = NS_FILE;
			$retval['conds']['cl_from'] = null;
			$retval['conds'][] = $nameField . ' = page_title';
			$retval['join_conds']['categorylinks'] = [
				'LEFT JOIN', 'cl_from = page_id' ];
			$retval['join_conds']['imagelinks'] = [
				'LEFT JOIN', 'il_to = page_title' ];
		}

		return $retval;
	}

	/** @inheritDoc */
	public function usesTimestamps() {
		return true;
	}

	/** @inheritDoc */
	protected function getPageHeader() {
		if ( $this->getConfig()->get( MainConfigNames::CountCategorizedImagesAsUsed ) ) {
			return $this->msg(
				'unusedimagestext-categorizedimgisused'
			)->parseAsBlock();
		}
		return $this->msg( 'unusedimagestext' )->parseAsBlock();
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'maintenance';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialUnusedImages::class, 'SpecialUnusedImages' );
