<?php
/**
 * Fix revisions for pages with changed content model.
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Usage:
 *  fixContentModel.php [--ns 0,10,122]
 * Run when default content model is changed for some namespace. See also T128466, T104033.
 */
class FixContentModel extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Fix revisions for pages after change of default content model' );
		$this->addOption( 'ns', 'Namespace(s) to fix, comma-separated' );

		$this->setBatchSize( 100 );
	}

	public function execute() {
		if ( !$this->getConfig()->get( 'ContentHandlerUseDB' ) ) {
			$this->output( "\$wgContentHandlerUseDB is not enabled, nothing to do.\n" );
			return true;
		}

		$dbr = $this->getDB( DB_SLAVE );
		$namespacesOption = $this->getOption( 'ns' );
		if ( !$namespacesOption ) {
			$namespaces = [ NS_MAIN ];
		} else {
			$namespaces = explode( $namespacesOption, ',' );
		}

		foreach ( $namespaces as $ns ) {
			$lastPage = 0;
			$title = Title::makeTitle( $ns, "Test" );
			$defaultModel = ContentHandler::getDefaultModelFor( $title );
			do {
				$rows =
					$dbr->select( [ 'page', 'revision' ], [
							'page_id',
							'page_latest',
							'page_title',
							'page_namespace',
							'page_content_model'
						], [
							'page_namespace' => $ns,
							'page_id > ' . $dbr->addQuotes( $lastPage ),
							'page_content_model != ' . $dbr->addQuotes( $defaultModel ),
							'page.page_latest = revision.rev_id',
							'revision.rev_content_model IS NULL'
						], __METHOD__, [ 'ORDER BY' => 'page_id', 'LIMIT' => $this->mBatchSize ],
						[ '' ] );
				foreach ( $rows as $row ) {
					$this->handleRow( $row );
				}
			} while ( $rows->numRows() >= $this->mBatchSize );
		}

		return true;
	}

	protected function handleRow( stdClass $row ) {
		$title = Title::makeTitle( $row->page_namespace, $row->page_title );
		$this->output( "Processing {$title} ({$row->page_id})...\n" );
		$dbw = $this->getDB( DB_MASTER );
		$this->output(
		"Setting revision content model on {$row->page_latest} to {$row->page_content_model}..." );
		$dbw->update( 'revision', [ 'rev_content_model' => $row->page_content_model ],
			[ 'rev_id' => $row->page_latest ], __METHOD__ );
		wfWaitForSlaves();
		$this->output( "done.\n" );
	}
}

$maintClass = FixContentModel::class;
require_once RUN_MAINTENANCE_IF_MAIN;
