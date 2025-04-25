<?php
/**
 * Build file cache for content pages
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

use MediaWiki\Actions\Action;
use MediaWiki\Context\RequestContext;
use MediaWiki\Debug\MWDebug;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;
use MediaWiki\Page\Article;
use MediaWiki\Settings\SettingsBuilder;
use MediaWiki\Title\Title;
use Wikimedia\AtEase\AtEase;
use Wikimedia\Rdbms\SelectQueryBuilder;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script that builds the file cache.
 *
 * @ingroup Maintenance
 */
class RebuildFileCache extends Maintenance {
	/** @var bool */
	private $enabled = true;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Build the file cache' );
		$this->addOption( 'start', 'Page_id to start from', false, true );
		$this->addOption( 'end', 'Page_id to end on', false, true );
		$this->addOption( 'overwrite', 'Refresh page cache' );
		$this->addOption( 'all', 'Build the file cache for pages in all namespaces, not just content pages' );
		$this->setBatchSize( 100 );
	}

	public function finalSetup( SettingsBuilder $settingsBuilder ) {
		$this->enabled = $settingsBuilder->getConfig()->get( MainConfigNames::UseFileCache );
		// Script will handle capturing output and saving it itself
		$settingsBuilder->putConfigValue( MainConfigNames::UseFileCache, false );

		// Avoid DB writes (like enotif/counters)
		$this->getServiceContainer()->getReadOnlyMode()
			->setReason( 'Building cache' );

		// Ensure no debug-specific logic ends up in the cache (must be after Setup.php)
		MWDebug::deinit();

		parent::finalSetup( $settingsBuilder );
	}

	public function execute() {
		if ( !$this->enabled ) {
			$this->fatalError( "Nothing to do -- \$wgUseFileCache is disabled." );
		}

		$start = $this->getOption( 'start', "0" );
		if ( !ctype_digit( $start ) ) {
			$this->fatalError( "Invalid value for start parameter." );
		}
		$start = intval( $start );

		$end = $this->getOption( 'end', "0" );
		if ( !ctype_digit( $end ) ) {
			$this->fatalError( "Invalid value for end parameter." );
		}
		$end = intval( $end );

		$this->output( "Building page file cache from page_id {$start}!\n" );

		$dbr = $this->getReplicaDB();
		$batchSize = $this->getBatchSize();
		$overwrite = $this->hasOption( 'overwrite' );
		$start = ( $start > 0 )
			? $start
			: $dbr->newSelectQueryBuilder()
				->select( 'MIN(page_id)' )
				->from( 'page' )
				->caller( __METHOD__ )->fetchField();
		$end = ( $end > 0 )
			? $end
			: $dbr->newSelectQueryBuilder()
				->select( 'MAX(page_id)' )
				->from( 'page' )
				->caller( __METHOD__ )->fetchField();
		if ( !$start ) {
			$this->fatalError( "Nothing to do." );
		}

		$where = [];
		if ( !$this->getOption( 'all' ) ) {
			// If 'all' isn't passed as an option, just fall back to previous behaviour
			// of using content namespaces
			$where['page_namespace'] =
				$this->getServiceContainer()->getNamespaceInfo()->getContentNamespaces();
		}

		// Mock request (hack, no real client)
		$_SERVER['HTTP_ACCEPT_ENCODING'] = 'bgzip';

		# Do remaining chunk
		$end += $batchSize - 1;
		$blockStart = $start;
		$blockEnd = $start + $batchSize - 1;

		$dbw = $this->getPrimaryDB();
		// Go through each page and save the output
		while ( $blockEnd <= $end ) {
			// Get the pages
			$res = $dbr->newSelectQueryBuilder()
				->select( [ 'page_namespace', 'page_title', 'page_id' ] )
				->from( 'page' )
				->useIndex( 'PRIMARY' )
				->where( $where )
				->andWhere( [
					$dbr->expr( 'page_id', '>=', (int)$blockStart ),
					$dbr->expr( 'page_id', '<=', (int)$blockEnd ),
				] )
				->orderBy( 'page_id', SelectQueryBuilder::SORT_ASC )
				->caller( __METHOD__ )->fetchResultSet();

			$this->beginTransaction( $dbw, __METHOD__ ); // for any changes
			foreach ( $res as $row ) {
				$rebuilt = false;

				$title = Title::makeTitleSafe( $row->page_namespace, $row->page_title );
				if ( $title === null ) {
					$this->output( "Page {$row->page_id} has bad title\n" );
					continue; // broken title?
				}

				$context = new RequestContext();
				$context->setTitle( $title );
				$article = Article::newFromTitle( $title, $context );
				$context->setWikiPage( $article->getPage() );

				// Some extensions like FlaggedRevs while error out if this is unset
				RequestContext::getMain()->setTitle( $title );

				// If the article is cacheable, then load it
				if ( $article->isFileCacheable( HTMLFileCache::MODE_REBUILD ) ) {
					$viewCache = new HTMLFileCache( $title, 'view' );
					$historyCache = new HTMLFileCache( $title, 'history' );
					if ( $viewCache->isCacheGood() && $historyCache->isCacheGood() ) {
						if ( $overwrite ) {
							$rebuilt = true;
						} else {
							$this->output( "Page '$title' (id {$row->page_id}) already cached\n" );
							continue; // done already!
						}
					}

					AtEase::suppressWarnings(); // header notices

					// 1. Cache ?action=view
					// Be sure to reset the mocked request time (T24852)
					$_SERVER['REQUEST_TIME_FLOAT'] = microtime( true );
					ob_start();
					$article->view();
					$context->getOutput()->output();
					$context->getOutput()->clearHTML();
					$viewHtml = ob_get_clean();
					$viewCache->saveToFileCache( $viewHtml );

					// 2. Cache ?action=history
					// Be sure to reset the mocked request time (T24852)
					$_SERVER['REQUEST_TIME_FLOAT'] = microtime( true );
					ob_start();
					Action::factory( 'history', $article, $context )->show();
					$context->getOutput()->output();
					$context->getOutput()->clearHTML();
					$historyHtml = ob_get_clean();
					$historyCache->saveToFileCache( $historyHtml );

					AtEase::restoreWarnings();

					if ( $rebuilt ) {
						$this->output( "Re-cached page '$title' (id {$row->page_id})..." );
					} else {
						$this->output( "Cached page '$title' (id {$row->page_id})..." );
					}
					$this->output( "[view: " . strlen( $viewHtml ) . " bytes; " .
						"history: " . strlen( $historyHtml ) . " bytes]\n" );
				} else {
					$this->output( "Page '$title' (id {$row->page_id}) not cacheable\n" );
				}
			}
			$this->commitTransaction( $dbw, __METHOD__ ); // commit any changes

			$blockStart += $batchSize;
			$blockEnd += $batchSize;
		}
		$this->output( "Done!\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = RebuildFileCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
