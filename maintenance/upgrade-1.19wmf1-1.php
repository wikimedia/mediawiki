<?php

require_once( dirname( __FILE__ ) . '/Maintenance.php' );


class SchemaMigration extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Run Schema Migrations for branch against all wikis";
		$this->addOption( 'secondary', 'Run on secondary / non-prod slaves', false, false );
	}

	function doAllSchemaChanges() {
		global $wgLBFactoryConf, $wgConf;

		if ( $this->getOption( 'secondary' ) ) { 
			require( dirname( __FILE__ ) . '/../../wmf-config/db-secondary.php' );
		}

		$sectionLoads = $wgLBFactoryConf['sectionLoads'];
		$sectionsByDB = $wgLBFactoryConf['sectionsByDB'];

		$rootPass = trim( wfShellExec( '/usr/local/bin/mysql_root_pass' ) );

		// Compile wiki lists
		$wikisBySection = array();
		foreach ( $wgConf->getLocalDatabases() as $wiki ) {
			if ( isset( $sectionsByDB[$wiki] ) ) {
				$wikisBySection[$sectionsByDB[$wiki]][] = $wiki;
			} else {
				$wikisBySection['DEFAULT'][] = $wiki;
			}
		}

		// Do the upgrades
		foreach ( $sectionLoads as $section => $loads ) {
			$master = true;
			foreach ( $loads as $server => $load ) {
				if ( $master ) {
					echo "Skipping $section master $server\n";
					$master = false;
					continue;
				}

				$db = new DatabaseMysql(
					$server,
					'root',
					$rootPass,
					false, /* dbName */
					0, /* flags, no transactions */
					'' /* prefix */
				);

				foreach ( $wikisBySection[$section] as $wiki ) {
					$db->selectDB( $wiki );
					$this->upgradeWiki( $db );
					if ( !$this->getOption( 'secondary' ) ) { 
						while ( $db->getLag() > 10 ) {
							echo "Waiting for $server to catch up to master.\n";
							sleep( 20 );
						}
					}
				}
			}
		}

		echo "All done (except masters).\n";
	}

	function upgradeWiki( $db ) {
		$wiki = $db->getDBname();
		$server = $db->getServer();
		$missing = "";

		$upgradeLogRow = $db->selectRow( 'updatelog',
			'ul_key',
			array( 'ul_key' => '1.19wmf1-1' ),
			__FUNCTION__ );
		if ( $upgradeLogRow ) {
			echo $db->getDBname() . ": already done\n";
			return;
		}

		echo "$server $wiki 1.19wmf1-1";

		if ( ! $db->indexExists( 'page', 'page_redirect_namespace_len' ) ) {
			echo " page_redirect_namespace_len index";
			$this->sourceUpgradeFile( $db, dirname( __FILE__ ) . '/archives/patch-page_redirect_namespace_len.sql' );
			if ( ! $db->indexExists( 'page', 'page_redirect_namespace_len' ) ) {
				$missing .= " page_redirect_namespace_len";
			}
		}

		if ( ! $db->fieldExists( 'archive', 'ar_sha1' ) ) {
			echo " ar_sha1";
			$this->sourceUpgradeFile( $db, dirname( __FILE__ ) . '/archives/patch-ar_sha1.sql' );
			if ( ! $db->fieldExists( 'archive', 'ar_sha1' ) ) {
				$missing .= " ar_sha1";
			}
		}

		if ( $db->fieldExists( 'article_feedback', 'aa_page_id' ) &&
			! $db->indexExists( 'article_feedback', 'aa_page_user_token' ) ) {
			echo " aa_page_user_token index";
			$this->sourceUpgradeFile( $db, dirname( __FILE__ ) . '/archives/patch-af_page_user_token-index.sql' );
			if (! $db->indexExists( 'article_feedback', 'aa_page_user_token' ) ) {
				$missing .= " aa_page_user_token";
			}
		}

		if ( $wiki == "enwiki" ) {
			echo " skipping rev_sha1";
		} else {
			if ( ! $db->fieldExists( 'revision', 'rev_sha1' ) ) {
				echo " rev_sha1";
				$this->sourceUpgradeFile( $db, dirname( __FILE__ ) . '/archives/patch-rev_sha1.sql' );
				if ( ! $db->fieldExists( 'revision', 'rev_sha1' ) ) {
					$missing .= " rev_sha1";
				}
			}
		}

		if ( $missing ) {
			echo " WARN: missing $missing\n";
		} else {
			$db->insert( 'updatelog', 
				array( 'ul_key' => '1.19wmf1-1' ),
				__FUNCTION__ );
			echo " ok\n";
		}
	}

	function sourceUpgradeFile( $db, $file ) {
		if ( !file_exists( $file ) ) {
			echo "File missing: $file\n";
			exit( 1 );
		}
		$db->sourceFile( $file );
	}
	
	function execute() { 
		$this->doAllSchemaChanges();
	}
}

$maintClass = "SchemaMigration";
require_once( RUN_MAINTENANCE_IF_MAIN );

