<?php
/**
 * Take page text out of an XML dump file and preprocess it to obj.
 * It may be useful for getting preprocessor statistics or filling the 
 * preprocessor cache.
 *
 * Copyright (C) 2011 Platonides - http://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Maintenance
 */
 
require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class PreprocessDump extends Maintenance {

	private $count = 0;
	private $startTime;

	/* Variables for dressing up as a parser */
	public $mTitle = 'PreprocessDump';
	public $mPPNodeCount = 0;

	public function getStripList() {
		global $wgParser;
		return $wgParser->getStripList();
	}
		
	public function __construct() {
		parent::__construct();
		$this->saveFailed = false;
		$this->mDescription = "Run a file or dump with a preprocessor";
		$this->addOption( 'file',  'File with text to run.', false, true );
		$this->addOption( 'dump',  'XML dump to execute all revisions.', false, true );
		$this->addOption( 'from',  'Article from XML dump to start from.', false, true );
		$this->addOption( 'cache', 'Use and populate the preprocessor cache.', false, false );
		$this->addOption( 'preprocessor', 'Preprocessor to use.', false, false );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function execute() {
		global $wgParser, $wgParserConf, $wgPreprocessorCacheThreshold;
		
		if (! ( $this->hasOption( 'file' ) ^ $this->hasOption( 'dump' ) ) ) {
			$this->error("You must provide a file or dump", true);
		}

		if ( !$this->hasOption( 'cache' ) ) {
			$wgPreprocessorCacheThreshold = false;
			$this->saveFailed = $this->getOption('save-failed');
		}
		
		if ( $this->hasOption( 'preprocessor' ) ) {
			$name = $this->getOption( 'preprocessor' );
		} elseif ( isset( $wgParserConf['preprocessorClass'] ) ) {
			$name = $wgParserConf['preprocessorClass'];
		} else {
			$name = 'Preprocessor_DOM';
		}

		$wgParser->firstCallInit();
		$this->mPreprocessor = new $name( $this );
		
		if ( $this->hasOption( 'file' ) ) {
			$revision = new WikiRevision;
			
			$revision->setText( file_get_contents( $this->getOption( 'file' ) ) );
			$revision->setTitle( Title::newFromText( rawurldecode( basename( $this->getOption('file'), '.txt' ) ) ) );
			$this->handleRevision( $revision );
			return;
		}
		
		$this->startTime = wfTime();

		if ( $this->getOption('dump') == '-' ) {
			$source = new ImportStreamSource( $this->getStdin() );
		} else {
			$this->error("Sorry, I don't support dump filenames yet. Use - and provide it on stdin on the meantime.", true);
		}
		$importer = new WikiImporter( $source );

		$importer->setRevisionCallback(
			array( &$this, 'handleRevision' ) );
		
		$this->from = $this->getOption( 'from', null );
		$this->count = 0;
		$importer->doImport();
			
		$delta = wfTime() - $this->startTime;
		$this->error( "{$this->count} revisions preprocessed in " . round($delta, 2) . " seconds " );
		if ($delta > 0)
			$this->error( round($this->count / $delta, 2) . " pages/sec" );
		
		# Perform the memory_get_peak_usage() when all the other data has been output so there's no damage if it dies.
		# It is only available since 5.2.0 (since 5.2.1 if you haven't compiled with --enable-memory-limit)
		$this->error( "Memory peak usage of " . memory_get_peak_usage() . " bytes\n" );
	}
	
	/**
	 * Callback function for each revision, preprocessToObj()
	 * @param $rev Revision
	 */
	public function handleRevision( $rev ) {
		$title = $rev->getTitle();
		if ( !$title ) {
			$this->error( "Got bogus revision with null title!" );
			return;
		}
		
		$this->count++;
		if ( isset( $this->from ) ) {
			if ( $this->from != $title )
				return;
			$this->output( "Skipped " . ($this->count - 1) . " pages\n" );
			
			$this->count = 1;
			$this->from = null;
		}
		try {
			$this->mPreprocessor->preprocessToObj( $rev->getText(), 0 );
		}
		catch(Exception $e) {
			$this->error("Caught exception " . $e->getMessage() . " in " . $title->	getPrefixedText() );
		}
	}
}

$maintClass = "PreprocessDump";
require_once( RUN_MAINTENANCE_IF_MAIN );

