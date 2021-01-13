<?php
/**
 * Take page text out of an XML dump file and preprocess it to obj.
 * It may be useful for getting preprocessor statistics or filling the
 * preprocessor cache.
 *
 * Copyright © 2011 Platonides - https://www.mediawiki.org/
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

use MediaWiki\MediaWikiServices;

require_once __DIR__ . '/dumpIterator.php';

/**
 * Maintenance script that takes page text out of an XML dump file and
 * preprocesses it to obj.
 *
 * @ingroup Maintenance
 */
class PreprocessDump extends DumpIterator {
	/** @var Preprocessor|null */
	private $preprocessor;

	/** @var int Bit field of Preprocessor::DOM_* constants */
	private $ptoFlags;

	public function __construct() {
		parent::__construct();
		$this->addOption( 'cache', 'Use and populate the preprocessor cache.', false, false );
		$this->addOption( 'preprocessor', 'This option is ignored', false, false );
	}

	public function getDbType() {
		return Maintenance::DB_NONE;
	}

	public function checkOptions() {
		$parser = MediaWikiServices::getInstance()->getParser();
		$parser->firstCallInit(); // make sure strip list is loaded
		$this->preprocessor = $parser->getPreprocessor();
		$this->ptoFlags = $this->hasOption( 'cache' ) ? 0 : Preprocessor::DOM_UNCACHED;
	}

	/**
	 * Callback function for each revision, preprocessToObj()
	 * @param WikiRevision $rev
	 */
	public function processRevision( WikiRevision $rev ) {
		$content = $rev->getContent();
		if ( $content->getModel() !== CONTENT_MODEL_WIKITEXT ) {
			return;
		}
		/** @var WikitextContent $content */
		'@phan-var WikitextContent $content';

		try {
			$this->preprocessor->preprocessToObj( strval( $content->getText() ), $this->ptoFlags );
		} catch ( Exception $e ) {
			$this->error( "Caught exception " . $e->getMessage() . " in "
				. $rev->getTitle()->getPrefixedText() );
		}
	}
}

$maintClass = PreprocessDump::class;
require_once RUN_MAINTENANCE_IF_MAIN;
