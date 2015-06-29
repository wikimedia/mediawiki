<?php

require_once __DIR__ . '/Maintenance.php';

class PopulateContentModel extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Populate the various content_* fields';
		$this->setBatchSize( 10 );
	}

	public function execute() {
		$dbw = wfGetDB( DB_MASTER );
		$this->populatePage( $dbw );
		$this->populateRevisionOrArchive( $dbw, 'revision' );
		$this->populateRevisionOrArchive( $dbw, 'archive' );
	}

	protected function populatePage( DatabaseBase $dbw ) {
		$toSave = array();
		$lastId = 0;
		do {
			$rows = $dbw->select(
				'page',
				array( 'page_namespace', 'page_title', 'page_id' ),
				array( 'page_content_model' => null, 'page_id > ' . $dbw->addQuotes( $lastId ) ),
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
			$this->output( "done.\n" );
		}
	}

	protected function populateRevisionOrArchive( DatabaseBase $dbw, $table ) {
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
			} else { // revision
				$selectTables = array( 'revision', 'page' );
				$fields = array( 'page_title', 'page_namespace' );
				$join_conds = array( 'page' => array( 'INNER JOIN', 'rev_page=page_id' ) );
			}
			$rows = $dbw->select(
				$selectTables,
				array_merge( $fields, array( $model_row, $format_row, $key ) ),
				// @todo support populating format if model is already set
				array( $model_row => null, "$key > " . $dbw->addQuotes( $lastId ) ),
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
					var_dump($row);
					throw $e;
				}
				$defaultModel = $handler->getModelID();
				$defaultFormat = $handler->getDefaultFormat();
				$dbModel = $row->{$model_row};
				$dbFormat = $row->{$format_row};
				if ( $dbModel === null && $dbFormat === null ) {
					// Set the defaults
					$toSave[$defaultModel][] = $row->{$key};
				} else { // $dbModel === null, $dbFormat set.
					if ( $dbFormat === $defaultFormat ) {
						$toSave[$defaultModel][] = $row->{$key};
					} else { // non-default format, just update now
						$dbw->update(
							$table,
							array( $model_row => $defaultModel ),
							array( $key => $row->{$key} ),
							__METHOD__
						);
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
