<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\ImageQueryPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of unused images
 *
 * @ingroup SpecialPage
 */
class SpecialUnusedImages extends ImageQueryPage {

	private int $fileMigrationStage;
	private int $imageLinksMigrationStage;

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Unusedimages' );
		$this->setDatabaseProvider( $dbProvider );
		$this->fileMigrationStage = $this->getConfig()->get( MainConfigNames::FileSchemaMigrationStage );
		$this->imageLinksMigrationStage = $this->getConfig()->get( MainConfigNames::ImageLinksSchemaMigrationStage );
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
		if ( $this->fileMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$tables = [ 'image' ];
			$nameField = 'img_name';
			$timestampField = 'img_timestamp';
			$fileConds = [];
			$fileJoins = [];
		} else {
			$tables = [ 'file', 'filerevision' ];
			$nameField = 'file_name';
			$timestampField = 'fr_timestamp';
			$fileConds = [ 'file_deleted' => 0 ];
			$fileJoins = [ 'filerevision' => [ 'JOIN', 'file_latest = fr_id' ] ];
		}

		if ( $this->imageLinksMigrationStage & SCHEMA_COMPAT_READ_OLD ) {
			$linksTables = [ 'imagelinks' ];
			$linksConds = [ 'il_to' => null ];
			$linksJoins = [
				'imagelinks' => [ 'LEFT JOIN', 'il_to = ' . $nameField ]
			];
		} else {
			$linksTables = [ 'linktarget', 'imagelinks' ];
			$linksConds = [ 'il_target_id' => null ];
			$linksJoins = [
				'linktarget' => [ 'LEFT JOIN', [ 'lt_title = ' . $nameField, 'lt_namespace' => NS_FILE ] ],
				'imagelinks' => [ 'LEFT JOIN', 'il_target_id = lt_id' ]
			];
		}

		$retval = [
			'tables' => array_merge( $tables, $linksTables ),
			'fields' => [
				'namespace' => NS_FILE,
				'title' => $nameField,
				'value' => $timestampField,
			],
			'conds' => array_merge( $linksConds, $fileConds ),
			'join_conds' => array_merge( $fileJoins, $linksJoins ),
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
