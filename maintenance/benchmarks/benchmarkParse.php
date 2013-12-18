<?php

require( dirname(__FILE__).'/../Maintenance.php' );

class BenchmarkParse extends Maintenance {
	var $templateTimestamp;

	function __construct() {
		parent::__construct();
		$this->addArg( 'title', 'The name of the page to parse' );
		$this->addOption( 'cold', 'Don\'t repeat the parse operation to warm the cache' );
		$this->addOption( 'page-time', 
			'Use the version of the page which was current at the given time',
			false, true );
		$this->addOption( 'tpl-time',
			'Use templates which were current at the given time',
			false, true );
	}

	function execute() {
		global $wgParser;

		if ( $this->hasOption( 'tpl-time' ) ) {
			$this->templateTimestamp = wfTimestamp( TS_MW, strtotime( $this->getOption( 'tpl-time' ) ) );
			Hooks::register( 'BeforeParserFetchTemplateAndtitle', array( $this, 'onFetchTemplate' ) );
		}
		$title = Title::newFromText( $this->getArg() );
		if ( !$title ) {
			$this->error( "Invalid title" );
			exit( 1 );
		}
		if ( $this->hasOption( 'page-time' ) ) {
			$pageTimestamp = wfTimestamp( TS_MW, strtotime( $this->getOption( 'page-time' ) ) );
			$id = $this->getRevIdForTime( $title, $pageTimestamp );
			if ( !$id ) {
				$this->error( "The page did not exist at that time" );
				exit( 1 );
			}

			$revision = Revision::newFromId( $id );
		} else {
			$revision = Revision::newFromTitle( $title );
		}
		if ( !$this->hasOption( 'cold' ) ) {
			$this->runParser( $revision );
		}
		$startUsage = getrusage();
		$startTime = microtime( true );
		$this->runParser( $revision );
		$endUsage = getrusage();
		$endTime = microtime( true );

		printf( "CPU time = %.3f s, wall clock time = %.3f s\n",
			$endUsage['ru_utime.tv_sec'] + $endUsage['ru_utime.tv_usec'] * 1e-6
			- $startUsage['ru_utime.tv_sec'] - $startUsage['ru_utime.tv_usec'] * 1e-6,
			$endTime - $startTime );
	}

	function getRevIdForTime( $title, $timestamp ) {
		$dbr = wfGetDB( DB_SLAVE );
		$id = $dbr->selectField(
			array( 'revision', 'page' ),
			'rev_id', 
			array(
				'page_namespace' => $title->getNamespace(),
				'page_title' => $title->getDBkey(),
				'rev_page=page_id',
				'rev_timestamp < ' . $dbr->addQuotes( $timestamp )
			),
			__METHOD__,
			array( 'ORDER BY' => 'rev_timestamp DESC', 'LIMIT' => 1 ) );
		return $id;
	}

	function runParser( $revision ) {
		$content = $revision->getContent();
		$content->getParserOutput( $revision->getTitle(), $revision->getId() );
	}

	function onFetchTemplate( $parser, $title, &$skip, &$id ) {
		$pdbk = $title->getPrefixedDBkey();
		if ( !isset( $this->idCache[$pdbk] ) ) {
			$dbr = wfGetDB(DB_SLAVE);
			$proposedId = $this->getRevIdForTime( $title, $this->templateTimestamp );
			$this->idCache[$pdbk] = $proposedId;
		}
		if ( $this->idCache[$pdbk] !== false ) {
			$id = $this->idCache[$pdbk];
		}
		return true;
	}
}

$maintClass = 'BenchmarkParse';
require( RUN_MAINTENANCE_IF_MAIN );
