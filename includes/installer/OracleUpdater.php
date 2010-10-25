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
		);
	}


	/**
	 * MySQL uses datatype defaults for NULL inserted into NOT NULL fields
	 * In namespace case that results into insert of 0 which is default namespace
	 * Oracle inserts NULL, so namespace fields should have a default value
	 */
	protected function doNamespaceDefaults() {
		$meta = $this->db->fieldInfo( 'page', 'page_namespace' );
		if ( $meta->defaultValue() != null ) {
			$this->output( "... defaults seem to present on namespace fields\n" );
			return;
		}

		$this->output( "Altering namespace fields with default value ..." );
		$this->applyPatch( 'patch_namespace_defaults.sql', false );
		$this->output( "ok\n" );
	}

	/**
	 * Overload: after this action field info table has to be rebuilt
	 */
	public function doUpdates( $purge = true ) {
		parent::doUpdates();
		
		$this->db->doQuery( 'BEGIN fill_wiki_info; END;' );
	}

}
