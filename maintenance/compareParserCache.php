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

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Title\Title;
use Wikimedia\Diff\Diff;
use Wikimedia\Diff\UnifiedDiffFormatter;

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

		$dbr = $this->getReplicaDB();

		$totalsec = 0.0;
		$scanned = 0;
		$withcache = 0;
		$withdiff = 0;
		$services = $this->getServiceContainer();
		$parserCache = $services->getParserCache();
		$renderer = $services->getRevisionRenderer();
		$wikiPageFactory = $services->getWikiPageFactory();
		while ( $pages-- > 0 ) {
			$row = $dbr->newSelectQueryBuilder()
				// @todo Title::selectFields() or Title::getQueryInfo() or something
				->select( [
					'page_namespace',
					'page_title',
					'page_id',
					'page_len',
					'page_is_redirect',
					'page_latest',
				] )
				->from( 'page' )
				->where( [
					'page_namespace' => $this->getOption( 'namespace' ),
					'page_is_redirect' => 0,
					$dbr->expr( 'page_random', '>=', wfRandom() ),
				] )
				->orderBy( 'page_random' )
				->caller( __METHOD__ )->fetchRow();

			if ( !$row ) {
				continue;
			}
			++$scanned;

			$title = Title::newFromRow( $row );
			$page = $wikiPageFactory->newFromTitle( $title );
			$revision = $page->getRevisionRecord();
			$parserOptions = $page->makeParserOptions( 'canonical' );
			$parserOutputOld = $parserCache->get( $page, $parserOptions );

			if ( $parserOutputOld ) {
				$t1 = microtime( true );
				$parserOutputNew = $renderer->getRenderedRevision( $revision, $parserOptions )
					->getRevisionParserOutput();

				$sec = microtime( true ) - $t1;
				$totalsec += $sec;

				$this->output( "Parsed '{$title->getPrefixedText()}' in $sec seconds.\n" );

				$this->output( "Found cache entry found for '{$title->getPrefixedText()}'..." );

				$oldHtml = trim( preg_replace( '#<!-- .+-->#Us', '',
					$parserOutputOld->getRawText() ) );
				$newHtml = trim( preg_replace( '#<!-- .+-->#Us', '',
					$parserOutputNew->getRawText() ) );
				$diffs = new Diff( explode( "\n", $oldHtml ), explode( "\n", $newHtml ) );
				$formatter = new UnifiedDiffFormatter();
				$unifiedDiff = $formatter->format( $diffs );

				if ( strlen( $unifiedDiff ) ) {
					$this->output( "differences found:\n\n$unifiedDiff\n\n" );
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

// @codeCoverageIgnoreStart
$maintClass = CompareParserCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
