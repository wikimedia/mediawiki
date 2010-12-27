<?php
/**
 * PostgreSQL-specific updater.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for handling updates to Postgres databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */

class PostgresUpdater extends DatabaseUpdater {

	/**
	 * @todo FIXME: Postgres should use sequential updates like Mysql, Sqlite
	 * and everybody else. It never got refactored like it should've.
	 */
	protected function getCoreUpdateList() {
		return array(
			# beginning
			array( 'checkPgUser' ),

			# new sequences
			array( 'addSequence', 'logging_log_id_seq'          ),
			array( 'addSequence', 'page_restrictions_pr_id_seq' ),

			# renamed sequences
			array( 'renameSequence', 'ipblocks_ipb_id_val', 'ipblocks_ipb_id_seq'         ),
			array( 'renameSequence', 'rev_rev_id_val',      'revision_rev_id_seq'         ),
			array( 'renameSequence', 'text_old_id_val',     'text_old_id_seq'             ),
			array( 'renameSequence', 'category_id_seq',     'category_cat_id_seq'         ),
			array( 'renameSequence', 'rc_rc_id_seq',        'recentchanges_rc_id_seq'     ),
			array( 'renameSequence', 'log_log_id_seq',      'logging_log_id_seq'          ),
			array( 'renameSequence', 'pr_id_val',           'page_restrictions_pr_id_seq' ),

			# new tables
			array( 'addTable', 'category',          'patch-category.sql' ),
			array( 'addTable', 'page',              'patch-page.sql' ),
			array( 'addTable', 'querycachetwo',     'patch-querycachetwo.sql' ),
			array( 'addTable', 'page_props',        'patch-page_props.sql' ),
			array( 'addTable', 'page_restrictions', 'patch-page_restrictions.sql' ),
			array( 'addTable', 'profiling',         'patch-profiling.sql' ),
			array( 'addTable', 'protected_titles',  'patch-protected_titles.sql' ),
			array( 'addTable', 'redirect',          'patch-redirect.sql' ),
			array( 'addTable', 'updatelog',         'patch-updatelog.sql' ),
			array( 'addTable', 'change_tag',        'patch-change_tag.sql' ),
			array( 'addTable', 'tag_summary',       'patch-change_tag.sql' ),
			array( 'addTable', 'valid_tag',         'patch-change_tag.sql' ),
			array( 'addTable', 'user_properties',   'patch-user_properties.sql' ),
			array( 'addTable', 'log_search',        'patch-log_search.sql' ),
			array( 'addTable', 'l10n_cache',        'patch-l10n_cache.sql' ),
			array( 'addTable', 'iwlinks',           'patch-iwlinks.sql' ),
			array( 'addTable', 'msg_resource',      'patch-msg_resource.sql' ),
			array( 'addTable', 'msg_resource_links','patch-msg_resource_links.sql' ),
			array( 'addTable', 'module_deps',       'patch-module_deps.sql' ),

			# rename tables
			array( 'renameTable', 'text',       'pagecontent' ),
			array( 'renameTable', 'user',       'mwuser' ),

			# Needed before new field
			array( 'convertArchive2' ),

			# new fields
			array( 'addPgField', 'archive',       'ar_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'archive',       'ar_len',               'INTEGER' ),
			array( 'addPgField', 'archive',       'ar_page_id',           'INTEGER' ),
			array( 'addPgField', 'archive',       'ar_parent_id',         'INTEGER' ),
			array( 'addPgField', 'categorylinks', 'cl_sortkey_prefix',    "TEXT NOT NULL DEFAULT ''"),
			array( 'addPgField', 'categorylinks', 'cl_collation',         "TEXT NOT NULL DEFAULT 0"),
			array( 'addPgField', 'categorylinks', 'cl_type',              "TEXT NOT NULL DEFAULT 'page'"),
			array( 'addPgField', 'image',         'img_sha1',             "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'ipblocks',      'ipb_allow_usertalk',   'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_anon_only',        'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_by_text',          "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'ipblocks',      'ipb_block_email',      'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_create_account',   'SMALLINT NOT NULL DEFAULT 1' ),
			array( 'addPgField', 'ipblocks',      'ipb_deleted',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_enable_autoblock', 'SMALLINT NOT NULL DEFAULT 1' ),
			array( 'addPgField', 'filearchive',   'fa_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'logging',       'log_deleted',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'logging',       'log_id',               "INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('logging_log_id_seq')" ),
			array( 'addPgField', 'logging',       'log_params',           'TEXT' ),
			array( 'addPgField', 'mwuser',        'user_editcount',       'INTEGER' ),
			array( 'addPgField', 'mwuser',        'user_hidden',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'mwuser',        'user_newpass_time',    'TIMESTAMPTZ' ),
			array( 'addPgField', 'oldimage',      'oi_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'oldimage',      'oi_major_mime',        "TEXT NOT NULL DEFAULT 'unknown'" ),
			array( 'addPgField', 'oldimage',      'oi_media_type',        'TEXT' ),
			array( 'addPgField', 'oldimage',      'oi_metadata',          "BYTEA NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'oldimage',      'oi_minor_mime',        "TEXT NOT NULL DEFAULT 'unknown'" ),
			array( 'addPgField', 'oldimage',      'oi_sha1',              "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'page_restrictions', 'pr_id',            "INTEGER NOT NULL UNIQUE DEFAULT nextval('page_restrictions_pr_id_seq')" ),
			array( 'addPgField', 'profiling',     'pf_memory',            'NUMERIC(18,10) NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'recentchanges', 'rc_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'recentchanges', 'rc_log_action',        'TEXT' ),
			array( 'addPgField', 'recentchanges', 'rc_log_type',          'TEXT' ),
			array( 'addPgField', 'recentchanges', 'rc_logid',             'INTEGER NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'recentchanges', 'rc_new_len',           'INTEGER' ),
			array( 'addPgField', 'recentchanges', 'rc_old_len',           'INTEGER' ),
			array( 'addPgField', 'recentchanges', 'rc_params',            'TEXT' ),
			array( 'addPgField', 'redirect',      'rd_interwiki',         'TEXT NULL' ),
			array( 'addPgField', 'redirect',      'rd_fragment',          'TEXT NULL' ),
			array( 'addPgField', 'revision',      'rev_deleted',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'revision',      'rev_len',              'INTEGER' ),
			array( 'addPgField', 'revision',      'rev_parent_id',        'INTEGER DEFAULT NULL' ),
			array( 'addPgField', 'site_stats',    'ss_active_users',      "INTEGER DEFAULT '-1'" ),
			array( 'addPgField', 'user_newtalk',  'user_last_timestamp',  'TIMESTAMPTZ' ),
			array( 'addPgField', 'logging',       'log_user_text',        "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'logging',       'log_page',             'INTEGER' ),
			array( 'addPgField', 'interwiki',     'iw_api',               "TEXT NOT NULL DEFAULT ''"),
			array( 'addPgField', 'interwiki',     'iw_wikiid',            "TEXT NOT NULL DEFAULT ''"),

			# type changes
			array( 'changeField', 'archive',       'ar_deleted',      'smallint', '' ),
			array( 'changeField', 'archive',       'ar_minor_edit',   'smallint', 'ar_minor_edit::smallint DEFAULT 0' ),
			array( 'changeField', 'filearchive',   'fa_deleted',      'smallint', '' ),
			array( 'changeField', 'filearchive',   'fa_height',       'integer',  '' ),
			array( 'changeField', 'filearchive',   'fa_metadata',     'bytea',    "decode(fa_metadata,'escape')" ),
			array( 'changeField', 'filearchive',   'fa_size',         'integer',  '' ),
			array( 'changeField', 'filearchive',   'fa_width',        'integer',  '' ),
			array( 'changeField', 'filearchive',   'fa_storage_group', 'text',     '' ),
			array( 'changeField', 'filearchive',   'fa_storage_key',  'text',     '' ),
			array( 'changeField', 'image',         'img_metadata',    'bytea',    "decode(img_metadata,'escape')" ),
			array( 'changeField', 'image',         'img_size',        'integer',  '' ),
			array( 'changeField', 'image',         'img_width',       'integer',  '' ),
			array( 'changeField', 'image',         'img_height',      'integer',  '' ),
			array( 'changeField', 'interwiki',     'iw_local',        'smallint', 'iw_local::smallint DEFAULT 0' ),
			array( 'changeField', 'interwiki',     'iw_trans',        'smallint', 'iw_trans::smallint DEFAULT 0' ),
			array( 'changeField', 'ipblocks',      'ipb_auto',        'smallint', 'ipb_auto::smallint DEFAULT 0' ),
			array( 'changeField', 'ipblocks',      'ipb_anon_only',   'smallint', "CASE WHEN ipb_anon_only=' ' THEN 0 ELSE ipb_anon_only::smallint END DEFAULT 0" ),
			array( 'changeField', 'ipblocks',      'ipb_create_account', 'smallint', "CASE WHEN ipb_create_account=' ' THEN 0 ELSE ipb_create_account::smallint END DEFAULT 1" ),
			array( 'changeField', 'ipblocks',      'ipb_enable_autoblock', 'smallint', "CASE WHEN ipb_enable_autoblock=' ' THEN 0 ELSE ipb_enable_autoblock::smallint END DEFAULT 1" ),
			array( 'changeField', 'ipblocks',      'ipb_block_email', 'smallint', "CASE WHEN ipb_block_email=' ' THEN 0 ELSE ipb_block_email::smallint END DEFAULT 0" ),
			array( 'changeField', 'ipblocks',      'ipb_address',     'text',     'ipb_address::text' ),
			array( 'changeField', 'ipblocks',      'ipb_deleted',     'smallint', 'ipb_deleted::smallint DEFAULT 0' ),
			array( 'changeField', 'math',          'math_inputhash',  'bytea',    "decode(math_inputhash,'escape')" ),
			array( 'changeField', 'math',          'math_outputhash', 'bytea',    "decode(math_outputhash,'escape')" ),
			array( 'changeField', 'mwuser',        'user_token',      'text',     '' ),
			array( 'changeField', 'mwuser',        'user_email_token', 'text',     '' ),
			array( 'changeField', 'objectcache',   'keyname',         'text',     '' ),
			array( 'changeField', 'oldimage',      'oi_height',       'integer',  '' ),
			array( 'changeField', 'oldimage',      'oi_metadata',     'bytea',    "decode(img_metadata,'escape')" ),
			array( 'changeField', 'oldimage',      'oi_size',         'integer',  '' ),
			array( 'changeField', 'oldimage',      'oi_width',        'integer',  '' ),
			array( 'changeField', 'page',          'page_is_redirect', 'smallint', 'page_is_redirect::smallint DEFAULT 0' ),
			array( 'changeField', 'page',          'page_is_new',     'smallint', 'page_is_new::smallint DEFAULT 0' ),
			array( 'changeField', 'querycache',    'qc_value',        'integer',  '' ),
			array( 'changeField', 'querycachetwo', 'qcc_value',       'integer',  '' ),
			array( 'changeField', 'recentchanges', 'rc_bot',          'smallint', 'rc_bot::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_deleted',      'smallint', '' ),
			array( 'changeField', 'recentchanges', 'rc_minor',        'smallint', 'rc_minor::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_new',          'smallint', 'rc_new::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_type',         'smallint', 'rc_type::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_patrolled',    'smallint', 'rc_patrolled::smallint DEFAULT 0' ),
			array( 'changeField', 'revision',      'rev_deleted',     'smallint', 'rev_deleted::smallint DEFAULT 0' ),
			array( 'changeField', 'revision',      'rev_minor_edit',  'smallint', 'rev_minor_edit::smallint DEFAULT 0' ),
			array( 'changeField', 'templatelinks', 'tl_namespace',    'smallint', 'tl_namespace::smallint' ),
			array( 'changeField', 'user_newtalk',  'user_ip',         'text',     'host(user_ip)' ),

			# null changes
			array( 'changeNullableField', 'oldimage', 'oi_bits',       'NULL' ),
			array( 'changeNullableField', 'oldimage', 'oi_timestamp',  'NULL' ),
			array( 'changeNullableField', 'oldimage', 'oi_major_mime', 'NULL' ),
			array( 'changeNullableField', 'oldimage', 'oi_minor_mime', 'NULL' ),

			array( 'checkOiDeleted' ),

			# New indexes
			array( 'addPgIndex', 'archive',       'archive_user_text',      '(ar_user_text)' ),
			array( 'addPgIndex', 'image',         'img_sha1',               '(img_sha1)' ),
			array( 'addPgIndex', 'oldimage',      'oi_sha1',                '(oi_sha1)' ),
			array( 'addPgIndex', 'page',          'page_mediawiki_title',   '(page_title) WHERE page_namespace = 8' ),
			array( 'addPgIndex', 'pagelinks',     'pagelinks_title',        '(pl_title)' ),
			array( 'addPgIndex', 'revision',      'rev_text_id_idx',        '(rev_text_id)' ),
			array( 'addPgIndex', 'recentchanges', 'rc_timestamp_bot',       '(rc_timestamp) WHERE rc_bot = 0' ),
			array( 'addPgIndex', 'templatelinks', 'templatelinks_from',     '(tl_from)' ),
			array( 'addPgIndex', 'watchlist',     'wl_user',                '(wl_user)' ),
			array( 'addPgIndex', 'logging',       'logging_user_type_time', '(log_user, log_type, log_timestamp)' ),
			array( 'addPgIndex', 'logging',       'logging_page_id_time',   '(log_page,log_timestamp)' ),
			array( 'addPgIndex', 'iwlinks',       'iwl_prefix_title_from',  '(iwl_prefix, iwl_title, iwl_from)' ),

			array( 'checkOiNameConstraint' ),
			array( 'checkPageDeletedTrigger' ),
			array( 'checkRcCurIdNullable' ),
			array( 'checkPagelinkUniqueIndex' ),
			array( 'checkRevUserFkey' ),
			array( 'checkIpbAdress' ),
			array( 'checkIwlPrefix' ),

			# All FK columns should be deferred
			array( 'changeFkeyDeferrable', 'archive',          'ar_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'categorylinks',    'cl_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'externallinks',    'el_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'filearchive',      'fa_deleted_user', 'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'filearchive',      'fa_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'image',            'img_user',        'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'imagelinks',       'il_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'ipblocks',         'ipb_by',          'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'ipblocks',         'ipb_user',        'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'langlinks',        'll_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'logging',          'log_user',        'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'oldimage',         'oi_name',         'image(img_name) ON DELETE CASCADE ON UPDATE CASCADE' ),
			array( 'changeFkeyDeferrable', 'oldimage',         'oi_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'pagelinks',        'pl_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'page_props',       'pp_page',         'page (page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'page_restrictions', 'pr_page',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'protected_titles', 'pt_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'recentchanges',    'rc_cur_id',       'page(page_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'recentchanges',    'rc_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'redirect',         'rd_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'revision',         'rev_page',        'page (page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'revision',         'rev_user',        'mwuser(user_id) ON DELETE RESTRICT' ),
			array( 'changeFkeyDeferrable', 'templatelinks',    'tl_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'trackbacks',       'tb_page',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'user_groups',      'ug_user',         'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'user_newtalk',     'user_id',         'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'user_properties',  'up_user',         'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'watchlist',        'wl_user',         'mwuser(user_id) ON DELETE CASCADE' ),

			# end
			array( 'tsearchFixes' ),
		);
	}

	protected function getOldGlobalUpdates() {
		global $wgExtNewTables, $wgExtPGNewFields, $wgExtPGAlteredFields, $wgExtNewIndexes;

		$updates = array();

		# Add missing extension tables
		foreach ( $wgExtNewTables as $tableRecord ) {
			$updates[] = array(
				'addTable', $tableRecord[0], $tableRecord[1], true
			);
		}

		# Add missing extension fields
		foreach ( $wgExtPGNewFields as $fieldRecord ) {
			$updates[] = array(
					'addPgField', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2]
				);
		}

		# Change altered columns
		foreach ( $wgExtPGAlteredFields as $fieldRecord ) {
			$updates[] = array(
					'changeField', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2]
				);
		}

		# Add missing extension indexes
		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$updates[] = array(
					'addPgExtIndex', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2]
				);
		}

		return $updates;
	}

	protected function describeTable( $table ) {
		global $wgDBmwschema;
		$q = <<<END
SELECT attname, attnum FROM pg_namespace, pg_class, pg_attribute
	WHERE pg_class.relnamespace = pg_namespace.oid
	  AND attrelid=pg_class.oid AND attnum > 0
	  AND relname=%s AND nspname=%s
END;
		$res = $this->db->query( sprintf( $q,
				$this->db->addQuotes( $table ),
				$this->db->addQuotes( $wgDBmwschema ) ) );
		if ( !$res ) {
			return null;
		}

		$cols = array();
		foreach ( $res as $r ) {
			$cols[] = array(
					"name" => $r[0],
					"ord" => $r[1],
				);
		}
		return $cols;
	}

	function describeIndex( $idx ) {
		global $wgDBmwschema;

		// first fetch the key (which is a list of columns ords) and
		// the table the index applies to (an oid)
		$q = <<<END
SELECT indkey, indrelid FROM pg_namespace, pg_class, pg_index
	WHERE nspname=%s
	  AND pg_class.relnamespace = pg_namespace.oid
	  AND relname=%s
	  AND indexrelid=pg_class.oid
END;
		$res = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $wgDBmwschema ),
				$this->db->addQuotes( $idx )
			)
		);
		if ( !$res ) {
			return null;
		}
		if ( !( $r = $this->db->fetchRow( $res ) ) ) {
			return null;
		}

		$indkey = $r[0];
		$relid = intval( $r[1] );
		$indkeys = explode( ' ', $indkey );

		$colnames = array();
		foreach ( $indkeys as $rid ) {
			$query = <<<END
SELECT attname FROM pg_class, pg_attribute
	WHERE attrelid=$relid
	  AND attnum=%d
	  AND attrelid=pg_class.oid
END;
			$r2 = $this->db->query( sprintf( $query, $rid ) );
			if ( !$r2 ) {
				return null;
			}
			if ( !( $row2 = $this->db->fetchRow( $r2 ) ) ) {
				return null;
			}
			$colnames[] = $row2[0];
		}

		return $colnames;
	}

	function fkeyDeltype( $fkey ) {
		global $wgDBmwschema;
		$q = <<<END
SELECT confdeltype FROM pg_constraint, pg_namespace
	WHERE connamespace=pg_namespace.oid
	  AND nspname=%s
	  AND conname=%s;
END;
		$r = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $wgDBmwschema ),
				$this->db->addQuotes( $fkey )
			)
		);
		if ( !( $row = $this->db->fetchRow( $r ) ) ) {
			return null;
		}
		return $row[0];
	}

	function ruleDef( $table, $rule ) {
		global $wgDBmwschema;
	$q = <<<END
SELECT definition FROM pg_rules
	WHERE schemaname = %s
	  AND tablename = %s
	  AND rulename = %s
END;
		$r = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $wgDBmwschema ),
				$this->db->addQuotes( $table ),
				$this->db->addQuotes( $rule )
			)
		);
		$row = $this->db->fetchRow( $r );
		if ( !$row ) {
			return null;
		}
		$d = $row[0];
		return $d;
	}

	protected function addSequence( $ns ) {
		if ( !$this->db->sequenceExists( $ns ) ) {
			$this->output( "Creating sequence $ns\n" );
			$this->db->query( "CREATE SEQUENCE $ns" );
		}
	}

	protected function renameSequence( $old, $new ) {
		if ( $this->db->sequenceExists( $old ) ) {
			$this->output( "Renaming sequence $old to $new\n" );
			$this->db->query( "ALTER SEQUENCE $old RENAME TO $new" );
		}
	}

	protected function renameTable( $old, $new ) {
		if ( $this->db->tableExists( $old ) ) {
			$this->output( "Renaming table $old to $new\n" );
			$this->db->query( "ALTER TABLE \"$old\" RENAME TO $new" );
		}
	}

	protected function addPgField( $table, $field, $type ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( !is_null( $fi ) ) {
			$this->output( "... column \"$table.$field\" already exists\n" );
			return;
		} else {
			$this->output( "Adding column \"$table.$field\"\n" );
			$this->db->query( "ALTER TABLE $table ADD $field $type" );
		}
	}

	protected function changeField( $table, $field, $newtype, $default ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "... error: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === $newtype )
			$this->output( "... column \"$table.$field\" is already of type \"$newtype\"\n" );
		else {
			$this->output( "Changing column type of \"$table.$field\" from \"{$fi->type()}\" to \"$newtype\"\n" );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = array();
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$sql .= ";\nCOMMIT;\n";
			$this->db->query( $sql );
		}
	}

	protected function changeNullableField( $table, $field, $null ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "... error: expected column $table.$field to exist\n" );
			exit( 1 );
		}
		if ( $fi->isNullable() ) {
			# # It's NULL - does it need to be NOT NULL?
			if ( 'NOT NULL' === $null ) {
				$this->output( "Changing \"$table.$field\" to not allow NULLs\n" );
				$this->db->query( "ALTER TABLE $table ALTER $field SET NOT NULL" );
			} else {
				$this->output( "... column \"$table.$field\" is already set as NULL\n" );
			}
		} else {
			# # It's NOT NULL - does it need to be NULL?
			if ( 'NULL' === $null ) {
				$this->output( "Changing \"$table.$field\" to allow NULLs\n" );
				$this->db->query( "ALTER TABLE $table ALTER $field DROP NOT NULL" );
			}
			else {
				$this->output( "... column \"$table.$field\" is already set as NOT NULL\n" );
			}
		}
	}

	public function addPgIndex( $table, $index, $type ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			$this->output( "... index \"$index\" on table \"$table\" already exists\n" );
		} else {
			$this->output( "Creating index \"$index\" on table \"$table\" $type\n" );
			$this->db->query( "CREATE INDEX $index ON $table $type" );
		}
	}

	public function addPgExtIndex( $table, $index, $type ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			$this->output( "... index \"$index\" on table \"$table\" already exists\n" );
		} else {
			$this->output( "Creating index \"$index\" on table \"$table\"\n" );
			if ( preg_match( '/^\(/', $type ) ) {
				$this->db->query( "CREATE INDEX $index ON $table $type" );
			} else {
				$this->applyPatch( $type, true );
			}
		}
	}

	protected function changeFkeyDeferrable( $table, $field, $clause ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "WARNING! Column \"$table.$field\" does not exist but it should! Please report this.\n" );
			return;
		}
		if ( $fi->is_deferred() && $fi->is_deferrable() ) {
			return;
		}
		$this->output( "Altering column \"$table.$field\" to be DEFERRABLE INITIALLY DEFERRED\n" );
		$conname = $fi->conname();
		$command = "ALTER TABLE $table DROP CONSTRAINT $conname";
		$this->db->query( $command );
		$command = "ALTER TABLE $table ADD CONSTRAINT $conname FOREIGN KEY ($field) REFERENCES $clause DEFERRABLE INITIALLY DEFERRED";
		$this->db->query( $command );
	}

	/**
	 * Verify that this user is configured correctly
	 */
	protected function checkPgUser() {
		global $wgDBmwschema, $wgDBts2schema, $wgDBuser;

		$config = $this->db->selectField( 
			'pg_catalog.pg_user', "array_to_string(useconfig,'*')",
			array( 'usename' => $wgDBuser ), __METHOD__ );

		$conf = array();
		foreach ( explode( '*', $config ) as $c ) {
			list( $x, $y ) = explode( '=', $c );
			$conf[$x] = $y;
		}

		if ( !array_key_exists( 'search_path', $conf ) ) {
			$search_path = '';
		} else {
			$search_path = $conf['search_path'];
		}

		if ( strpos( $search_path, $wgDBmwschema ) === false ) {
			$this->output( "Adding in schema \"$wgDBmwschema\" to search_path for user \"$wgDBuser\"\n" );
			$search_path = "$wgDBmwschema, $search_path";
		}
		if ( strpos( $search_path, $wgDBts2schema ) === false ) {
			$this->output( "Adding in schema \"$wgDBts2schema\" to search_path for user \"$wgDBuser\"\n" );
			$search_path = "$search_path, $wgDBts2schema";
		}
		$search_path = str_replace( ', ,', ',', $search_path );
		if ( array_key_exists( 'search_path', $conf ) === false || $search_path != $conf['search_path'] ) {
			$this->db->query( "ALTER USER $wgDBuser SET search_path = $search_path" );
			$this->db->query( "SET search_path = $search_path" );
		} else {
			$path = $conf['search_path'];
			$this->output( "... search_path for user \"$wgDBuser\" looks correct ($path)\n" );
		}

		$goodconf = array(
			'client_min_messages' => 'error',
			'DateStyle'           => 'ISO, YMD',
			'TimeZone'            => 'GMT'
		);

		foreach ( $goodconf as $key => $value ) {
			if ( !array_key_exists( $key, $conf ) or $conf[$key] !== $value ) {
				$this->output( "Setting $key to '$value' for user \"$wgDBuser\"\n" );
				$this->db->query( "ALTER USER $wgDBuser SET $key = '$value'" );
				$this->db->query( "SET $key = '$value'" );
			} else {
				$this->output( "... default value of \"$key\" is correctly set to \"$value\" for user \"$wgDBuser\"\n" );
			}
		}
	}

	protected function convertArchive2() {
		if ( $this->db->tableExists( "archive2" ) ) {
			$this->output( "Converting \"archive2\" back to normal archive table\n" );
			if ( $this->db->ruleExists( 'archive', 'archive_insert' ) ) {
				$this->output( "Dropping rule \"archive_insert\"\n" );
				$this->db->query( 'DROP RULE archive_insert ON archive' );
			}
			if ( $this->db->ruleExists( 'archive', 'archive_delete' ) ) {
				$this->output( "Dropping rule \"archive_delete\"\n" );
				$this->db->query( 'DROP RULE archive_delete ON archive' );
			}
			$this->applyPatch( 'patch-remove-archive2.sql' );
		} else {
			$this->output( "... obsolete table \"archive2\" does not exist\n" );
		}
	}

	protected function checkOiDeleted() {
		if ( $this->db->fieldInfo( 'oldimage', 'oi_deleted' )->type() !== 'smallint' ) {
			$this->output( "Changing \"oldimage.oi_deleted\" to type \"smallint\"\n" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted DROP DEFAULT" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted TYPE SMALLINT USING (oi_deleted::smallint)" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted SET DEFAULT 0" );
		} else {
			$this->output( "... column \"oldimage.oi_deleted\" is already of type \"smallint\"\n" );
		}
	}

	protected function checkOiNameConstraint() {
		if ( $this->db->hasConstraint( "oldimage_oi_name_fkey_cascaded" ) ) {
			$this->output( "... table \"oldimage\" has correct cascading delete/update foreign key to image\n" );
		} else {
			if ( $this->db->hasConstraint( "oldimage_oi_name_fkey" ) ) {
				$this->db->query( "ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey" );
			}
			if ( $this->db->hasConstraint( "oldimage_oi_name_fkey_cascade" ) ) {
				$this->db->query( "ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey_cascade" );
			}
			$this->output( "Making foreign key on table \"oldimage\" (to image) a cascade delete/update\n" );
			$this->db->query( "ALTER TABLE oldimage ADD CONSTRAINT oldimage_oi_name_fkey_cascaded " .
				"FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE ON UPDATE CASCADE" );
		}
	}

	protected function checkPageDeletedTrigger() {
		if ( !$this->db->triggerExists( 'page', 'page_deleted' ) ) {
			$this->output( "Adding function and trigger \"page_deleted\" to table \"page\"\n" );
			$this->applyPatch( 'patch-page_deleted.sql' );
		} else {
			$this->output( "... table \"page\" has \"page_deleted\" trigger\n" );
		}
	}

	protected function checkRcCurIdNullable(){
		$fi = $this->db->fieldInfo( 'recentchanges', 'rc_cur_id' );
		if ( !$fi->isNullable() ) {
			$this->output( "Removing NOT NULL constraint from \"recentchanges.rc_cur_id\"\n" );
			$this->applyPatch( 'patch-rc_cur_id-not-null.sql' );
		} else {
			$this->output( "... column \"recentchanges.rc_cur_id\" has a NOT NULL constraint\n" );
		}
	}

	protected function checkPagelinkUniqueIndex() {
		$pu = $this->describeIndex( 'pagelink_unique' );
		if ( !is_null( $pu ) && ( $pu[0] != 'pl_from' || $pu[1] != 'pl_namespace' || $pu[2] != 'pl_title' ) ) {
			$this->output( "Dropping obsolete version of index \"pagelink_unique index\"\n" );
			$this->db->query( 'DROP INDEX pagelink_unique' );
			$pu = null;
		} else {
			$this->output( "... obsolete version of index \"pagelink_unique index\" does not exist\n" );
		}

		if ( is_null( $pu ) ) {
			$this->output( "Creating index \"pagelink_unique index\"\n" );
			$this->db->query( 'CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title)' );
		} else {
			$this->output( "... index \"pagelink_unique_index\" already exists\n" );
		}
	}

	protected function checkRevUserFkey() {
		if ( $this->fkeyDeltype( 'revision_rev_user_fkey' ) == 'r' ) {
			$this->output( "... constraint \"revision_rev_user_fkey\" is ON DELETE RESTRICT\n" );
		} else {
			$this->output( "Changing constraint \"revision_rev_user_fkey\" to ON DELETE RESTRICT\n" );
			$this->applyPatch( 'patch-revision_rev_user_fkey.sql' );
		}
	}

	protected function checkIpbAdress() {
		if ( $this->db->indexExists( 'ipblocks', 'ipb_address' ) ) {
			$this->output( "Removing deprecated index 'ipb_address'...\n" );
			$this->db->query( 'DROP INDEX ipb_address' );
		}
		if ( $this->db->indexExists( 'ipblocks', 'ipb_address_unique' ) ) {
			$this->output( "... have ipb_address_unique\n" );
		} else {
			$this->output( "Adding ipb_address_unique index\n" );
			$this->applyPatch( 'patch-ipb_address_unique.sql' );
		}
	}

	protected function checkIwlPrefix() {
		if ( $this->db->indexExists( 'iwlinks', 'iwl_prefix' ) ) {
			$this->output( "Replacing index 'iwl_prefix' with 'iwl_prefix_from_title'...\n" );
			$this->applyPatch( 'patch-rename-iwl_prefix.sql' );
		}
	}

	protected function tsearchFixes() {
		# Tweak the page_title tsearch2 trigger to filter out slashes
		# This is create or replace, so harmless to call if not needed
		$this->applyPatch( 'patch-ts2pagetitle.sql' );

		# If the server is 8.3 or higher, rewrite the tsearch2 triggers
		# in case they have the old 'default' versions
		# Gather version numbers in case we need them
		if ( $this->db->getServerVersion() >= 8.3 ) {
			$this->applyPatch( 'patch-tsearch2funcs.sql' );
		}
	}
}
