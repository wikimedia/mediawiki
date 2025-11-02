<?php
/**
 * Copyright © 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Maintenance\BackupDumper;

/**
 * @ingroup Dump
 */
class ExportProgressFilter extends DumpFilter {
	/**
	 * @var BackupDumper
	 */
	private $progress;

	/**
	 * @param DumpOutput &$sink
	 * @param BackupDumper &$progress
	 */
	public function __construct( &$sink, &$progress ) {
		parent::__construct( $sink );
		$this->progress = $progress;
	}

	/** @inheritDoc */
	public function writeClosePage( $string ) {
		parent::writeClosePage( $string );
		$this->progress->reportPage();
	}

	/** @inheritDoc */
	public function writeRevision( $rev, $string ) {
		parent::writeRevision( $rev, $string );
		$this->progress->revCount();
	}
}
