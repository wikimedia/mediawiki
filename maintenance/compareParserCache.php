<?php
/**
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

require_once __DIR__ . '/Maintenance.php';

/**
 * @ingroup Maintenance
 */
class CompareParserCache extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Parse random pages and compare output to cache.' );
		$this->addOption( 'namespace', 'Page namespace number', true, true );
		$this->addOption( 'maxpages', 'Number of pages to try', true, true );
	}

	public function execute() {
		$pages = $this->getOption( 'maxpages' );

		$dbr = $this->getDB( DB_REPLICA );

		$totalsec = 0.0;
		$scanned = 0;
		$withcache = 0;
		$withdiff = 0;
		while ( $pages-- > 0 ) {
			$row = $dbr->selectRow( 'page', '*',
				[
					'page_namespace' => $this->getOption( 'namespace' ),
					'page_is_redirect' => 0,
					'page_random >= ' . wfRandom()
				],
				__METHOD__,
				[
					'ORDER BY' => 'page_random',
				]
			);

			if ( !$row ) {
				continue;
			}
			++$scanned;

			$title = Title::newFromRow( $row );
			$page = WikiPage::factory( $title );
			$revision = $page->getRevision();
			$content = $revision->getContent( Revision::RAW );

			$parserOptions = $page->makeParserOptions( 'canonical' );

			$parserOutputOld = ParserCache::singleton()->get( $page, $parserOptions );

			if ( $parserOutputOld ) {
				$t1 = microtime( true );
				$parserOutputNew = $content->getParserOutput(
					$title, $revision->getId(), $parserOptions, false );
				$sec = microtime( true ) - $t1;
				$totalsec += $sec;

				$this->output( "Parsed '{$title->getPrefixedText()}' in $sec seconds.\n" );

				$this->output( "Found cache entry found for '{$title->getPrefixedText()}'..." );
				$oldHtml = trim( preg_replace( '#<!-- .+-->#Us', '', $parserOutputOld->getText() ) );
				$newHtml = trim( preg_replace( '#<!-- .+-->#Us', '', $parserOutputNew->getText() ) );
				$diff = wfDiff( $oldHtml, $newHtml );
				if ( strlen( $diff ) ) {
					$this->output( "differences found:\n\n$diff\n\n" );
					++$withdiff;
				} else {
					$this->output( "No differences found.\n" );
				}
				++$withcache;
			} else {
				$this->output( "No parser cache entry found for '{$title->getPrefixedText()}'.\n" );
			}
		}

		$ave = $totalsec ? $totalsec / $scanned : 0;
		$this->output( "Checked $scanned pages; $withcache had prior cache entries.\n" );
		$this->output( "Pages with differences found: $withdiff\n" );
		$this->output( "Average parse time: $ave sec\n" );
	}
}

$maintClass = "CompareParserCache";
require_once RUN_MAINTENANCE_IF_MAIN;
