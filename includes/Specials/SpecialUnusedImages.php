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

	private readonly int $fileMigrationStage;

	public function __construct( IConnectionProvider $dbProvider ) {
		parent::__construct( 'Unusedimages' );
		$this->setDatabaseProvider( $dbProvider );
		$this->fileMigrationStage = $this->getConfig()->get( MainConfigNames::FileSchemaMigrationStage );
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
			$imageTables = [ 'image' ];
			$nameField = 'img_name';
			$timestampField = 'img_timestamp';
			$fileConds = [];
			$fileJoins = [];
		} else {
			$imageTables = [ 'file', 'filerevision' ];
			$nameField = 'file_name';
			$timestampField = 'fr_timestamp';
			$fileConds = [ 'file_deleted' => 0 ];
			$fileJoins = [ 'filerevision' => [ 'JOIN', 'file_latest = fr_id' ] ];
		}

		$retval = [
			'tables' => [ ...$imageTables, 'linktarget', 'imagelinks' ],
			'fields' => [
				'namespace' => NS_FILE,
				'title' => $nameField,
				'value' => $timestampField,
			],
			'conds' => [
				'il_target_id' => null,
				...$fileConds
			],
			'join_conds' => [
				...$fileJoins,
				'linktarget' => [ 'LEFT JOIN', [ 'lt_title = ' . $nameField, 'lt_namespace' => NS_FILE ] ],
				'imagelinks' => [ 'LEFT JOIN', 'il_target_id = lt_id' ]
			],
		];

		if ( $this->getConfig()->get( MainConfigNames::CountCategorizedImagesAsUsed ) ) {
			// Order is significant
			$retval['tables'] = [ ...$imageTables, 'page', 'categorylinks', 'linktarget', 'imagelinks' ];
			$retval['conds']['page_namespace'] = NS_FILE;
			$retval['conds']['cl_from'] = null;
			$retval['join_conds']['page'] = [ 'JOIN', $nameField . ' = page_title' ];
			$retval['join_conds']['categorylinks'] = [ 'LEFT JOIN', 'cl_from = page_id' ];
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
