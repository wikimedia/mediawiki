<?php
/**
 * PostgreSQL-specific updater.
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
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use FixInconsistentRedirects;
use FixWrongPasswordPrefixes;
use MediaWiki\Maintenance\FixAutoblockLogTitles;
use MigrateExternallinks;
use MigrateRevisionActorTemp;
use MigrateRevisionCommentTemp;
use PopulateUserIsTemp;
use UpdateRestrictions;
use Wikimedia\Rdbms\DatabasePostgres;

/**
 * Class for handling updates to Postgres databases.
 *
 * @ingroup Installer
 * @since 1.17
 */
class PostgresUpdater extends DatabaseUpdater {

	/**
	 * @var DatabasePostgres
	 */
	protected $db;

	/**
	 * @return array
	 */
	protected function getCoreUpdateList() {
		return [
			// 1.39
			[ 'addTable', 'user_autocreate_serial', 'patch-user_autocreate_serial.sql' ],
			[ 'runMaintenance', MigrateRevisionActorTemp::class ],
			[ 'dropTable', 'revision_actor_temp' ],
			[ 'runMaintenance', UpdateRestrictions::class ],
			[ 'dropPgField', 'page', 'page_restrictions' ],
			[ 'migrateTemplatelinks' ],
			[ 'changeNullableField', 'templatelinks', 'tl_target_id', 'NOT NULL', true ],
			[ 'changePrimaryKey', 'templatelinks', [ 'tl_from', 'tl_target_id' ] ],
			[ 'dropField', 'templatelinks', 'tl_title', 'patch-templatelinks-drop-tl_title.sql' ],

			// 1.40
			[ 'addField', 'externallinks', 'el_to_path', 'patch-externallinks-el_to_path.sql' ],

			// 1.41
			[ 'addField', 'user', 'user_is_temp', 'patch-user-user_is_temp.sql' ],
			[ 'runMaintenance', MigrateRevisionCommentTemp::class ],
			[ 'dropTable', 'revision_comment_temp' ],
			[ 'runMaintenance', MigrateExternallinks::class ],
			[ 'modifyField', 'externallinks', 'el_to', 'patch-externallinks-el_to_default.sql' ],
			[ 'addField', 'pagelinks', 'pl_target_id', 'patch-pagelinks-target_id.sql' ],
			[ 'dropField', 'externallinks', 'el_to', 'patch-externallinks-drop-el_to.sql' ],
			[ 'runMaintenance', FixInconsistentRedirects::class ],
			[ 'modifyField', 'image', 'img_size', 'patch-image-img_size_to_bigint.sql' ],
			[ 'modifyField', 'filearchive', 'fa_size', 'patch-filearchive-fa_size_to_bigint.sql' ],
			[ 'modifyField', 'oldimage', 'oi_size', 'patch-oldimage-oi_size_to_bigint.sql' ],
			[ 'modifyField', 'uploadstash', 'us_size', 'patch-uploadstash-us_size_to_bigint.sql' ],

			// 1.42
			[ 'addField', 'user_autocreate_serial', 'uas_year', 'patch-user_autocreate_serial-uas_year.sql' ],
			[ 'addTable', 'block_target', 'patch-block_target.sql' ],
			[ 'dropIndex', 'categorylinks', 'cl_collation_ext', 'patch-drop-cl_collation_ext.sql' ],
			[ 'runMaintenance', PopulateUserIsTemp::class ],
			// Attempt a rename from `site_groups` to `site_group` prior to dropping the `sites` indexes,
			// due to a typo in the original index-renaming database update from MW 1.36. (T374042)
			[ 'renameIndex', 'sites', 'sites_group', 'site_group' ],
			[ 'dropIndex', 'sites', 'site_type', 'patch-sites-drop_indexes.sql' ],
			// Re-attempt to drop most of the dropped `sites`-table indexes:
			// If a Postgres wiki previously ran `update.php` on MW 1.42 or above, the script would have errored after
			// reaching the "DROP INDEX site_group;" line of `patch-sites-drop_indexes.sql` (due to a previous
			// index-renaming typo from MW 1.36).
			// However, as the `site_type` index itself _would_ have been successfully dropped, the `.sql` file listed
			// in the `dropIndex` line above will not be re-applied on any future runs of `update.php`.
			// Therefore, to ensure that the remaining indexes are definitely dropped on these wikis, we need to
			// separately attempt to drop them again. (T374042; T374042#11017896)
			[ 'dropIndex', 'sites', 'site_group', 'patch-sites-drop_site_group_index.sql' ],
			[ 'dropIndex', 'sites', 'site_source', 'patch-sites-drop_site_source_index.sql' ],
			[ 'dropIndex', 'sites', 'site_language', 'patch-sites-drop_site_language_index.sql' ],
			[ 'dropIndex', 'sites', 'site_protocol', 'patch-sites-drop_site_protocol_index.sql' ],
			[ 'dropIndex', 'sites', 'site_domain', 'patch-sites-drop_site_domain_index.sql' ],
			[ 'dropIndex', 'sites', 'site_forward', 'patch-sites-drop_site_forward_index.sql' ],
			[ 'dropIndex', 'iwlinks', 'iwl_prefix_from_title', 'patch-iwlinks-drop-iwl_prefix_from_title.sql' ],

			// 1.43
			[ 'migratePagelinks' ],
			[ 'dropDefault', 'revision', 'rev_actor' ],
			[ 'dropDefault', 'revision', 'rev_comment_id' ],
			[ 'changeField', 'revision', 'rev_id', 'BIGINT', '' ],
			[ 'changeField', 'revision', 'rev_parent_id', 'BIGINT', '' ],
			[ 'changeField', 'recentchanges', 'rc_id', 'BIGINT', '' ],
			[ 'changeField', 'change_tag', 'ct_rc_id', 'BIGINT', '' ],
			[ 'runMaintenance', \MigrateBlocks::class ],
			[ 'dropTable', 'ipblocks' ],
			[ 'dropField', 'pagelinks', 'pl_title', 'patch-pagelinks-drop-pl_title.sql' ],
			[ 'addPostDatabaseUpdateMaintenance', FixAutoblockLogTitles::class ],
			[ 'migrateSearchindex' ],

			// 1.44
			[ 'addTable', 'file', 'patch-file.sql' ],
			[ 'addField', 'categorylinks', 'cl_target_id', 'patch-categorylinks-target_id.sql' ],
			[ 'addTable', 'collation', 'patch-collation.sql' ],
			[ 'dropTable', 'module_deps' ],

			// 1.45
			[ 'addTable', 'existencelinks', 'patch-existencelinks.sql' ],
			[ 'runMaintenance', FixWrongPasswordPrefixes::class ],
			[ 'addIndex', 'categorylinks', 'cl_timestamp_id', 'patch-categorylinks-cl_timestamp_id.sql' ],
			[ 'migrateCategorylinks' ],
			[ 'normalizeCollation' ],
			[ 'addIndex', 'categorylinks', 'cl_pkey', 'patch-categorylinks-pk.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_source_name_timestamp',
				'patch-recentchanges-rc_source_name_timestamp.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_name_source_patrolled_timestamp',
				'patch-recentchanges-rc_name_source_patrolled_timestamp.sql' ],
			[ 'dropField', 'recentchanges', 'rc_new', 'patch-recentchanges-drop-rc_new.sql' ],
		];
	}

	/** @inheritDoc */
	protected function getInitialUpdateKeys() {
		return [
			'filearchive-fa_major_mime-patch-fa_major_mime-chemical.sql',
			'image-img_major_mime-patch-img_major_mime-chemical.sql',
			'oldimage-oi_major_mime-patch-oi_major_mime-chemical.sql',
			'user_groups-ug_group-patch-ug_group-length-increase-255.sql',
			'user_former_groups-ufg_group-patch-ufg_group-length-increase-255.sql',
			'user_properties-up_property-patch-up_property.sql',
		];
	}

	/**
	 * @param string $table
	 * @return array[]|null
	 */
	protected function describeTable( $table ) {
		$q = <<<END
SELECT attname, attnum FROM pg_namespace, pg_class, pg_attribute
	WHERE pg_class.relnamespace = pg_namespace.oid
		AND attrelid=pg_class.oid AND attnum > 0
		AND relname=%s AND nspname=%s
END;
		$res = $this->db->query(
			sprintf( $q,
				$this->db->addQuotes( $table ),
				$this->db->addQuotes( $this->db->getCoreSchema() )
			),
			__METHOD__
		);
		if ( !$res ) {
			return null;
		}

		$cols = [];
		foreach ( $res as $r ) {
			$cols[] = [
				"name" => $r[0],
				"ord" => $r[1],
			];
		}

		return $cols;
	}

	/**
	 * @param string $idx
	 * @return array|null
	 */
	protected function describeIndex( $idx ) {
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
				$this->db->addQuotes( $this->db->getCoreSchema() ),
				$this->db->addQuotes( $idx )
			),
			__METHOD__
		);
		if ( !$res ) {
			return null;
		}
		$r = $res->fetchRow();
		if ( !$r ) {
			return null;
		}

		$indkey = $r[0];
		$relid = intval( $r[1] );
		$indkeys = explode( ' ', $indkey );

		$colnames = [];
		foreach ( $indkeys as $rid ) {
			$query = <<<END
SELECT attname FROM pg_class, pg_attribute
	WHERE attrelid=$relid
		AND attnum=%d
		AND attrelid=pg_class.oid
END;
			$r2 = $this->db->query( sprintf( $query, $rid ), __METHOD__ );
			if ( !$r2 ) {
				return null;
			}
			$row2 = $r2->fetchRow();
			if ( !$row2 ) {
				return null;
			}
			$colnames[] = $row2[0];
		}

		return $colnames;
	}

	/**
	 * @param string $fkey
	 * @return string|null
	 */
	protected function fkeyDeltype( $fkey ) {
		$q = <<<END
SELECT confdeltype FROM pg_constraint, pg_namespace
	WHERE connamespace=pg_namespace.oid
		AND nspname=%s
		AND conname=%s;
END;
		$r = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $this->db->getCoreSchema() ),
				$this->db->addQuotes( $fkey )
			),
			__METHOD__
		);
		$row = $r->fetchRow();
		return $row ? $row[0] : null;
	}

	/**
	 * @param string $table
	 * @param string $rule
	 * @return string|null
	 */
	protected function ruleDef( $table, $rule ) {
		$q = <<<END
SELECT definition FROM pg_rules
	WHERE schemaname = %s
		AND tablename = %s
		AND rulename = %s
END;
		$r = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $this->db->getCoreSchema() ),
				$this->db->addQuotes( $table ),
				$this->db->addQuotes( $rule )
			),
			__METHOD__
		);
		$row = $r->fetchRow();
		if ( !$row ) {
			return null;
		}
		$d = $row[0];

		return $d;
	}

	/**
	 * @param string $table
	 * @param string $pkey
	 * @param string $ns
	 */
	protected function addSequence( $table, $pkey, $ns ) {
		if ( !$this->db->sequenceExists( $ns ) ) {
			$this->output( "Creating sequence $ns\n" );
			if ( $pkey !== false ) {
				$table = $this->db->addIdentifierQuotes( $table );
				$this->db->query( "CREATE SEQUENCE $ns OWNED BY $table.$pkey", __METHOD__ );
				$this->setDefault( $table, $pkey, '"nextval"(\'"' . $ns . '"\'::"regclass")' );
			} else {
				$this->db->query( "CREATE SEQUENCE $ns", __METHOD__ );
			}
		}
	}

	/**
	 * @param string $table
	 * @param string $ns
	 */
	protected function dropSequence( $table, $ns ) {
		if ( $this->db->sequenceExists( $ns ) ) {
			$this->output( "Dropping sequence $ns\n" );
			$this->db->query( "DROP SEQUENCE $ns CASCADE", __METHOD__ );
		}
	}

	/**
	 * @param string $old
	 * @param string $new
	 */
	protected function renameSequence( $old, $new ) {
		if ( $this->db->sequenceExists( $new ) ) {
			$this->output( "...sequence $new already exists.\n" );

			return;
		}
		if ( $this->db->sequenceExists( $old ) ) {
			$this->output( "Renaming sequence $old to $new\n" );
			$this->db->query( "ALTER SEQUENCE $old RENAME TO $new", __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $pkey
	 * @param string $seq
	 */
	protected function setSequenceOwner( $table, $pkey, $seq ) {
		if ( $this->db->sequenceExists( $seq ) ) {
			$this->output( "Setting sequence $seq owner to $table.$pkey\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER SEQUENCE $seq OWNED BY $table.$pkey", __METHOD__ );
		}
	}

	/**
	 * @param string $old
	 * @param string $new
	 * @param string|false $patch
	 */
	protected function renameTable( $old, $new, $patch = false ) {
		if ( $this->db->tableExists( $old, __METHOD__ ) ) {
			$this->output( "Renaming table $old to $new\n" );
			$old = $this->db->addIdentifierQuotes( $old );
			$new = $this->db->addIdentifierQuotes( $new );
			$this->db->query( "ALTER TABLE $old RENAME TO $new", __METHOD__ );
			if ( $patch !== false ) {
				$this->applyPatch( $patch );
			}
		}
	}

	/**
	 * @param string $table
	 * @param string $old
	 * @param string $new
	 * @param bool $skipBothIndexExistWarning
	 * @param string|false $a
	 * @param bool $b
	 * @return bool
	 */
	protected function renameIndex(
		$table, $old, $new, $skipBothIndexExistWarning = false, $a = false, $b = false
	) {
		// First requirement: the table must exist
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...skipping: '$table' table doesn't exist yet.\n" );

			return true;
		}

		// Second requirement: the new index must be missing
		if ( $this->db->indexExists( $table, $new, __METHOD__ ) ) {
			$this->output( "...index $new already set on $table table.\n" );
			if ( !$skipBothIndexExistWarning
				&& $this->db->indexExists( $table, $old, __METHOD__ )
			) {
				$this->output( "...WARNING: $old still exists, despite it has been " .
					"renamed into $new (which also exists).\n" .
					"            $old should be manually removed if not needed anymore.\n" );
			}

			return true;
		}

		// Third requirement: the old index must exist
		if ( !$this->db->indexExists( $table, $old, __METHOD__ ) ) {
			$this->output( "...skipping: index $old doesn't exist.\n" );

			return true;
		}

		$this->db->query( "ALTER INDEX $old RENAME TO $new", __METHOD__ );
		return true;
	}

	/**
	 * @param string $table
	 * @param string $field
	 */
	protected function dropPgField( $table, $field ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "...$table table does not contain $field field.\n" );
		} else {
			$this->output( "Dropping column '$table.$field'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table DROP COLUMN $field", __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @param string $type
	 */
	protected function addPgField( $table, $field, $type ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi !== null ) {
			$this->output( "...column '$table.$field' already exists\n" );
		} else {
			$this->output( "Adding column '$table.$field'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table ADD $field $type", __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @param string $newtype
	 * @param string $default
	 */
	protected function changeField( $table, $field, $newtype, $default ) {
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping default change.\n" );
			return;
		}
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === strtolower( $newtype ) ) {
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		} else {
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( $default !== '' ) {
				$res = [];
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef, __METHOD__ );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$this->db->query( $sql, __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @param string $newtype
	 * @param string $default
	 */
	protected function changeFieldPurgeTable( $table, $field, $newtype, $default ) {
		# # For a cache table, empty it if the field needs to be changed, because the old contents
		# # may be corrupted.  If the column is already the desired type, refrain from purging.
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === $newtype ) {
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		} else {
			$this->output( "Purging data from cache table '$table'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "DELETE from $table", __METHOD__ );
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = [];
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef, __METHOD__ );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$this->db->query( $sql, __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @param string $default
	 */
	protected function setDefault( $table, $field, $default ) {
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping default change.\n" );
			return;
		}
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== $default ) {
			$this->output( "Changing '$table.$field' default value\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table ALTER $field SET DEFAULT "
				. $this->db->addQuotes( $default ), __METHOD__ );
		}
	}

	/**
	 * Drop a default value from a field
	 * @since 1.32
	 * @param string $table
	 * @param string $field
	 */
	protected function dropDefault( $table, $field ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== false ) {
			$this->output( "Removing '$table.$field' default value\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table ALTER $field DROP DEFAULT", __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @param string $null
	 * @param bool $update
	 */
	protected function changeNullableField( $table, $field, $null, $update = false ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			return;
		}
		if ( $fi->isNullable() ) {
			# # It's NULL - does it need to be NOT NULL?
			if ( $null === 'NOT NULL' ) {
				$this->output( "Changing '$table.$field' to not allow NULLs\n" );
				$table = $this->db->addIdentifierQuotes( $table );
				if ( $update ) {
					$this->db->query( "UPDATE $table SET $field = DEFAULT WHERE $field IS NULL", __METHOD__ );
				}
				$this->db->query( "ALTER TABLE $table ALTER $field SET NOT NULL", __METHOD__ );
			} else {
				$this->output( "...column '$table.$field' is already set as NULL\n" );
			}
		} else {
			# # It's NOT NULL - does it need to be NULL?
			if ( $null === 'NULL' ) {
				$this->output( "Changing '$table.$field' to allow NULLs\n" );
				$table = $this->db->addIdentifierQuotes( $table );
				$this->db->query( "ALTER TABLE $table ALTER $field DROP NOT NULL", __METHOD__ );
			} else {
				$this->output( "...column '$table.$field' is already set as NOT NULL\n" );
			}
		}
	}

	/**
	 * @param string $table
	 * @param string $index
	 * @param string $type
	 * @param bool $unique
	 */
	protected function addPgIndex( $table, $index, $type, $unique = false ) {
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping index.\n" );
		} elseif ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "...index '$index' on table '$table' already exists\n" );
		} else {
			$this->output( "Creating index '$index' on table '$table' $type\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$unique = $unique ? 'UNIQUE' : '';
			$this->db->query( "CREATE $unique INDEX $index ON $table $type", __METHOD__ );
		}
	}

	/**
	 * @param string $table
	 * @param string $index
	 * @param string $type
	 */
	protected function addPgExtIndex( $table, $index, $type ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "...index '$index' on table '$table' already exists\n" );
		} elseif ( preg_match( '/^\(/', $type ) ) {
			$this->output( "Creating index '$index' on table '$table'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "CREATE INDEX $index ON $table $type", __METHOD__ );
		} else {
			$this->applyPatch( $type, true, "Creating index '$index' on table '$table'" );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 */
	protected function dropFkey( $table, $field ) {
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping constraint change.\n" );
			return;
		}
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! " .
				"Please report this.\n" );
			return;
		}

		if ( $this->dropConstraint( $table, $field, 'foreignkey', $fi->conname() ) ) {
			$this->output( "Dropping foreign key constraint on '$table.$field'\n" );
		} else {
			$this->output( "...foreign key constraint on '$table.$field' already does not exist\n" );
		}
	}

	/**
	 * @param string $table
	 * @param string $field
	 * @param string $clause
	 */
	protected function changeFkeyDeferrable( $table, $field, $clause ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! " .
				"Please report this.\n" );

			return;
		}
		if ( $fi->is_deferred() && $fi->is_deferrable() ) {
			return;
		}
		$this->output( "Altering column '$table.$field' to be DEFERRABLE INITIALLY DEFERRED\n" );

		$conname = $fi->conname();
		$conclause = "CONSTRAINT \"$conname\"";

		if ( !$this->dropConstraint( $table, $field, 'foreignkey', $conname ) ) {
			$this->output( "Column '$table.$field' does not have a foreign key " .
				"constraint, will be added\n" );
			$conclause = "";
		}

		$command =
			"ALTER TABLE $table ADD $conclause " .
			"FOREIGN KEY ($field) REFERENCES $clause DEFERRABLE INITIALLY DEFERRED";
		$this->db->query( $command, __METHOD__ );
	}

	/**
	 * @param string $table
	 * @param string $index
	 */
	protected function dropPgIndex( $table, $index ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "Dropping obsolete index '$index'\n" );
			$this->db->query( "DROP INDEX \"" . $index . "\"", __METHOD__ );
		}
	}

	/**
	 * @param string $index
	 * @param array $should_be
	 * @param string $good_def
	 */
	protected function checkIndex( $index, $should_be, $good_def ) {
		$pu = $this->db->indexAttributes( $index );
		if ( $pu && $pu != $should_be ) {
			$this->output( "Dropping obsolete version of index '$index'\n" );
			$this->db->query( "DROP INDEX \"" . $index . "\"", __METHOD__ );
			$pu = [];
		} else {
			$this->output( "...no need to drop index '$index'\n" );
		}

		if ( !$pu ) {
			$this->output( "Creating index '$index'\n" );
			$this->db->query( $good_def, __METHOD__ );
		} else {
			$this->output( "...index '$index' exists\n" );
		}
	}

	/**
	 * @param string $table
	 * @param array $shouldBe
	 * @param string|null $constraintName
	 */
	protected function changePrimaryKey( $table, $shouldBe, $constraintName = null ) {
		// https://wiki.postgresql.org/wiki/Retrieve_primary_key_columns
		$result = $this->db->query(
			"SELECT a.attname as column " .
				"FROM pg_index i " .
				"JOIN pg_attribute a ON a.attrelid = i.indrelid " .
				"AND a.attnum = ANY(i.indkey) " .
				"WHERE i.indrelid = '\"$table\"'::regclass " .
				"AND i.indisprimary",
			__METHOD__
		);
		$currentColumns = [];
		foreach ( $result as $row ) {
			$currentColumns[] = $row->column;
		}

		if ( $currentColumns == $shouldBe ) {
			$this->output( "...no need to change primary key of '$table'\n" );
			return;
		}

		$this->dropConstraint( $table, '', 'primary', $constraintName );

		$table = $this->db->addIdentifierQuotes( $table );
		$this->db->query(
			"ALTER TABLE $table" .
			" ADD PRIMARY KEY (" . implode( ',', $shouldBe ) . ');',
			__METHOD__
		);
	}

	/**
	 * Drop generic constraint. If the constraint was created with a custom name,
	 * then the name must be queried and supplied as $conname, otherwise standard
	 * system suffixes and format would be assumed.
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $type
	 * @param string|null $conname
	 * @return bool
	 */
	protected function dropConstraint( $table, $field, $type, $conname = null ) {
		if ( $conname === null ) {
			if ( $type == 'primary' ) {
				$conname = "{$table}_pkey";
			} else {
				$map = [ 'unique' => 'key', 'check' => 'check', 'foreignkey' => 'fkey' ];
				$conname = "{$table}_{$field}_{$map[$type]}";
			}
		}

		if ( $this->db->constraintExists( $table, $conname ) ) {
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query(
				"ALTER TABLE $table DROP CONSTRAINT $conname;",
				__METHOD__
			);

			return true;
		}

		return false;
	}

	/**
	 * Replaces unique index with primary key,modifies si_title length
	 *
	 * @since 1.43
	 * @return void
	 */
	protected function migrateSearchindex() {
		$updateKey = 'searchindex-pk-titlelength';
		if ( !$this->tableExists( 'searchindex' ) ) {
			return;
		}

		$primaryIndexExists = $this->db->indexExists( 'searchindex', 'searchindex_pkey' );
		if ( $this->updateRowExists( $updateKey ) || ( $primaryIndexExists ) ) {
			$this->output( "...searchindex table has already been migrated.\n" );
			if ( !$this->updateRowExists( $updateKey ) ) {
				$this->insertUpdateRow( $updateKey );
			}
			return;
		}

		$apply = $this->applyPatch( 'patch-searchindex-pk-titlelength.sql', false, '...migrating searchindex table' );

		if ( $apply ) {
			$this->insertUpdateRow( $updateKey );
		}
	}
}
