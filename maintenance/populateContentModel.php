<?php

require_once __DIR__ . '/Maintenance.php';

/**
 * Usage:
 *  populateContentModel.php --ns=1 --table=page
 */
class PopulateContentModel extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Populate the various content_* fields';
		$this->addOption( 'ns', 'Namespace to run in, or "all" for all namespaces', true, true );
		$this->addOption( 'table', 'Table to run in', true, true );
		$this->setBatchSize( 100 );
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		$ns = $this->getOption( 'ns' );
		if ( !ctype_digit( $ns ) && $ns !== 'all' ) {
			$this->error( 'Invalid namespace', 1 );
		}
		$ns = $ns === 'all' ? 'all' : (int)$ns;
		$table = $this->getOption( 'table' );
		switch ( $table ) {
			case 'revision':
			case 'archive':
				$this->populateRevisionOrArchive( $dbw, $table, $ns );
				break;
			case 'page':
				$this->populatePage( $dbw, $ns );
				break;
			default:
				$this->error( "Invalid table name: $table", 1 );
		}
	}

	protected function populatePage( DatabaseBase $dbw, $ns ) {
		$toSave = array();
		$lastId = 0;
		$nsCondition = $ns === 'all' ? array() : array( 'page_namespace' => $ns );
		do {
			$rows = $dbw->select(
				'page',
				array( 'page_namespace', 'page_title', 'page_id' ),
				array(
					'page_content_model' => null,
					'page_id > ' . $dbw->addQuotes( $lastId ),
				) + $nsCondition,
				__METHOD__,
				array( 'LIMIT' => $this->mBatchSize, 'ORDER BY' => 'page_id ASC' )
			);
			$this->output( "Fetched {$rows->numRows()} rows.\n" );
			foreach ( $rows as $row ) {
				$title = Title::newFromRow( $row );
				$model = ContentHandler::getDefaultModelFor( $title );
				$toSave[$model][] = $row->page_id;
				$count = count( $toSave[$model] );
				if ( $count > $this->mBatchSize ) {
					$this->output( "Setting $count rows to $model..." );
					$dbw->update(
						'page',
						array( 'page_content_model' => $model ),
						array( 'page_id' => $toSave[$model] ),
						__METHOD__
					);
					wfWaitForSlaves();
					$this->output( "done.\n" );
					unset( $toSave[$model] );
				}
				$lastId = $row->page_id;
			}
		} while ( $rows->numRows() >= $this->mBatchSize );
		foreach ( $toSave as $model => $pages ) {
			$count = count( $pages );
			$this->output( "Setting $count rows to $model..." );
			$dbw->update(
				'page',
				array( 'page_content_model' => $model ),
				array( 'page_id' => $pages ),
				__METHOD__
			);
			wfWaitForSlaves();
			$this->output( "done.\n" );
		}
	}

	protected function populateRevisionOrArchive( DatabaseBase $dbw, $table, $ns ) {
		$prefix = $table === 'archive' ? 'ar' : 'rev';
		$model_row = "{$prefix}_content_model";
		$format_row = "{$prefix}_content_format";
		$key = "{$prefix}_id";
		$toSave = array();
		$lastId = 0;
		do {
			if ( $table === 'archive' ) {
				$selectTables = 'archive';
				$fields = array( 'ar_namespace', 'ar_title' );
				$join_conds = array();
				$where = $ns === 'all' ? array() : array( 'ar_namespace' => $ns );
			} else { // revision
				$selectTables = array( 'revision', 'page' );
				$fields = array( 'page_title', 'page_namespace' );
				$join_conds = array( 'page' => array( 'INNER JOIN', 'rev_page=page_id' ) );
				$where = $ns === 'all' ? array() : array( 'page_namespace' => $ns );
			}
			$rows = $dbw->select(
				$selectTables,
				array_merge( $fields, array( $model_row, $format_row, $key ) ),
				// @todo support populating format if model is already set
				array(
					$model_row => null,
					"$key > " . $dbw->addQuotes( $lastId ),
				) + $where,
				__METHOD__,
				array( 'LIMIT' => $this->mBatchSize, 'ORDER BY' => "$key ASC" ),
				$join_conds
			);
			$this->output( "Fetched {$rows->numRows()} rows.\n" );
			foreach ( $rows as $row ) {
				if ( $table === 'archive' ) {
					$title = Title::makeTitle( $row->ar_namespace, $row->ar_title );
				} else {
					$title = Title::newFromRow( $row );
				}
				$lastId = $row->{$key};
				try {
					$handler = ContentHandler::getForTitle( $title );
				} catch ( MWException $e ) {
					$this->error( "Invalid content model for $title" );
					continue;
				}
				$defaultModel = $handler->getModelID();
				$defaultFormat = $handler->getDefaultFormat();
				$dbModel = $row->{$model_row};
				$dbFormat = $row->{$format_row};
				$id = $row->{$key};
				if ( $dbModel === null && $dbFormat === null ) {
					// Set the defaults
					$toSave[$defaultModel][] = $row->{$key};
				} else { // $dbModel === null, $dbFormat set.
					if ( $dbFormat === $defaultFormat ) {
						$toSave[$defaultModel][] = $row->{$key};
					} else { // non-default format, just update now
						$this->output( "Updating format for revision $id of $title... ");
						$dbw->update(
							$table,
							array( $model_row => $defaultModel ),
							array( $key => $id ),
							__METHOD__
						);
						wfWaitForSlaves();
						$this->output( "done.\n" );
						continue;
					}
				}

				$count = count( $toSave[$defaultModel] );
				if ( $count > $this->mBatchSize ) {
					$this->output( "Setting $count rows to $defaultModel..." );
					$dbw->update(
						$table,
						array( $model_row => $defaultModel, $format_row => $defaultFormat ),
						array( $key => $toSave[$defaultModel] ),
						__METHOD__
					);
					wfWaitForSlaves();
					$this->output( "done.\n" );
					unset( $toSave[$defaultModel] );
				}
			}
		} while ( $rows->numRows() >= $this->mBatchSize );
		foreach ( $toSave as $model => $ids ) {
			$count = count( $ids );
			$format = ContentHandler::getForModelID( $model )->getDefaultFormat();
			$this->output( "Setting $count rows to $model..." );
			$dbw->update(
				$table,
				array( $model_row => $model, $format_row => $format ),
				array( $key => $toSave[$model] ),
				__METHOD__
			);
			$this->output( "done.\n" );
		}
	}
}

$maintClass = 'PopulateContentModel';
require_once RUN_MAINTENANCE_IF_MAIN;
