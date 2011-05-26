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
 
require_once( dirname( __FILE__ ) . '/dumpIterator.php' );

class PreprocessDump extends DumpIterator {

	/* Variables for dressing up as a parser */
	public $mTitle = 'PreprocessDump';
	public $mPPNodeCount = 0;

	public function getStripList() {
		global $wgParser;
		return $wgParser->getStripList();
	}
		
	public function __construct() {
		parent::__construct();
		$this->addOption( 'cache', 'Use and populate the preprocessor cache.', false, false );
		$this->addOption( 'preprocessor', 'Preprocessor to use.', false, false );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function checkOptions() {
		global $wgParser, $wgParserConf, $wgPreprocessorCacheThreshold;

		if ( !$this->hasOption( 'cache' ) ) {
			$wgPreprocessorCacheThreshold = false;
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
	}
	
	/**
	 * Callback function for each revision, preprocessToObj()
	 * @param $rev Revision
	 */
	public function processRevision( $rev ) {
		try {
			$this->mPreprocessor->preprocessToObj( $rev->getText(), 0 );
		}
		catch(Exception $e) {
			$this->error("Caught exception " . $e->getMessage() . " in " . $rev->getTitle()->getPrefixedText() );
		}
	}
}

$maintClass = "PreprocessDump";
require( RUN_MAINTENANCE_IF_MAIN );

