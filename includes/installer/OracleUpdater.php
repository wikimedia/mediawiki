<?php
/**
 * Oracle-specific updater.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for handling updates to Oracle databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class OracleUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return array(
			// 1.16
			array( 'doNamespaceDefaults' ),
			array( 'doFKRenameDeferr' ),
			array( 'doFunctions17' ),
			array( 'doSchemaUpgrade17' ),
		);
	}

	/**
	 * MySQL uses datatype defaults for NULL inserted into NOT NULL fields
	 * In namespace case that results into insert of 0 which is default namespace
	 * Oracle inserts NULL, so namespace fields should have a default value
	 */
	protected function doNamespaceDefaults() {
		$this->output( "Altering namespace fields with default value ... " );
		$meta = $this->db->fieldInfo( 'page', 'page_namespace' );
		if ( $meta->defaultValue() != null ) {
			$this->output( "defaults seem to present on namespace fields\n" );
			return;
		}

		$this->applyPatch( 'patch_namespace_defaults.sql', false );
		$this->output( "ok\n" );
	}

	/**
	 * Uniform FK names + deferrable state
	 */
	protected function doFKRenameDeferr() {
		$this->output( "Altering foreign keys ... " );
		$meta = $this->db->query( 'SELECT COUNT(*) cnt FROM user_constraints WHERE constraint_type = \'R\' AND deferrable = \'DEFERRABLE\'' );
		$row = $meta->fetchRow();
		if ( $row && $row['cnt'] > 0 ) {
			$this->output( "at least one FK is deferrable, considering up to date\n" );
			return;
		}

		$this->applyPatch( 'patch_fk_rename_deferred.sql', false );
		$this->output( "ok\n" );
	}

	/**
	 * Recreate functions to 17 schema layout
	 */
	protected function doFunctions17() {
		$this->output( "Recreating functions ... " );
		$this->applyPatch( 'patch_create_17_functions.sql', false );
		$this->output( "ok\n" );
	}

	/**
	 * Schema upgrade 16->17
	 * there are no incremental patches prior to this
	 */
	protected function doSchemaUpgrade17() {
		$this->output( "Updating schema to 17 ... " );
		// check if iwlinks table exists which was added in 1.17
		if ( $this->db->tableExists( trim( $this->db->tableName( 'iwlinks' ) ) ) ) {
			$this->output( "schema seem to be up to date.\n" );
			return;
		}
		$this->applyPatch( 'patch_16_17_schema_changes.sql', false );
		$this->output( "ok\n" );
	}

	/**
	 * Overload: after this action field info table has to be rebuilt
	 */
	public function doUpdates( $what = array( 'core', 'extensions', 'purge' ) ) {
		parent::doUpdates( $what );

		$this->db->query( 'BEGIN fill_wiki_info; END;' );
	}

}
