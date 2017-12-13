<?php

/**
 * @ingroup Dump
 */
class ExportProgressFilter extends DumpFilter {
	/**
	 * @var BackupDumper
	 */
	private $progress;

	function __construct( &$sink, &$progress ) {
		parent::__construct( $sink );
		$this->progress = $progress;
	}

	function writeClosePage( $string ) {
		parent::writeClosePage( $string );
		$this->progress->reportPage();
	}

	function writeRevision( $rev, $string ) {
		parent::writeRevision( $rev, $string );
		$this->progress->revCount();
	}
}
