<?php
/**
 * @ingroup Database
 * @file
 * This is the Postgres database abstraction layer.
 *
 */
class PostgresField {
	private $name, $tablename, $type, $nullable, $max_length, $deferred, $deferrable, $conname;

	static function fromText($db, $table, $field) {
	global $wgDBmwschema;

		$q = <<<SQL
SELECT 
 attnotnull, attlen, COALESCE(conname, '') AS conname,
 COALESCE(condeferred, 'f') AS deferred,
 COALESCE(condeferrable, 'f') AS deferrable,
 CASE WHEN typname = 'int2' THEN 'smallint'
  WHEN typname = 'int4' THEN 'integer'
  WHEN typname = 'int8' THEN 'bigint'
  WHEN typname = 'bpchar' THEN 'char'
 ELSE typname END AS typname
FROM pg_class c
JOIN pg_namespace n ON (n.oid = c.relnamespace)
JOIN pg_attribute a ON (a.attrelid = c.oid)
JOIN pg_type t ON (t.oid = a.atttypid)
LEFT JOIN pg_constraint o ON (o.conrelid = c.oid AND a.attnum = ANY(o.conkey) AND o.contype = 'f')
WHERE relkind = 'r'
AND nspname=%s
AND relname=%s
AND attname=%s;
SQL;

		$table = $db->tableName( $table );
		$res = $db->query(sprintf($q,
				$db->addQuotes($wgDBmwschema),
				$db->addQuotes($table),
				$db->addQuotes($field)));
		$row = $db->fetchObject($res);
		if (!$row)
			return null;
		$n = new PostgresField;
		$n->type = $row->typname;
		$n->nullable = ($row->attnotnull == 'f');
		$n->name = $field;
		$n->tablename = $table;
		$n->max_length = $row->attlen;
		$n->deferrable = ($row->deferrable == 't');
		$n->deferred = ($row->deferred == 't');
		$n->conname = $row->conname;
		return $n;
	}

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tablename;
	}

	function type() {
		return $this->type;
	}

	function nullable() {
		return $this->nullable;
	}

	function maxLength() {
		return $this->max_length;
	}

	function is_deferrable() {
		return $this->deferrable;
	}

	function is_deferred() {
		return $this->deferred;
	}

	function conname() {
		return $this->conname;
	}

}

/**
 * @ingroup Database
 */
class DatabasePostgres extends DatabaseBase {
	var $mInsertId = null;
	var $mLastResult = null;
	var $numeric_version = null;
	var $mAffectedRows = null;

	function DatabasePostgres($server = false, $user = false, $password = false, $dbName = false,
		$failFunction = false, $flags = 0 )
	{

		$this->mFailFunction = $failFunction;
		$this->mFlags = $flags;
		$this->open( $server, $user, $password, $dbName);

	}

	function getType() {
		return 'postgres';
	}

	function cascadingDeletes() {
		return true;
	}
	function cleanupTriggers() {
		return true;
	}
	function strictIPs() {
		return true;
	}
	function realTimestamps() {
		return true;
	}
	function implicitGroupby() {
		return false;
	}
	function implicitOrderby() {
		return false;
	}
	function searchableIPs() {
		return true;
	}
	function functionalIndexes() {
		return true;
	}

	function hasConstraint( $name ) {
		global $wgDBmwschema;
		$SQL = "SELECT 1 FROM pg_catalog.pg_constraint c, pg_catalog.pg_namespace n WHERE c.connamespace = n.oid AND conname = '" . pg_escape_string( $name ) . "' AND n.nspname = '" . pg_escape_string($wgDBmwschema) ."'";
		return $this->numRows($res = $this->doQuery($SQL));
	}

	static function newFromParams( $server, $user, $password, $dbName, $failFunction = false, $flags = 0)
	{
		return new DatabasePostgres( $server, $user, $password, $dbName, $failFunction, $flags );
	}

	/**
	 * Usually aborts on failure
	 * If the failFunction is set to a non-zero integer, returns success
	 */
	function open( $server, $user, $password, $dbName ) {
		# Test for Postgres support, to avoid suppressed fatal error
		if ( !function_exists( 'pg_connect' ) ) {
			throw new DBConnectionError( $this, "Postgres functions missing, have you compiled PHP with the --with-pgsql option?\n (Note: if you recently installed PHP, you may need to restart your webserver and database)\n" );
		}

		global $wgDBport;

		if (!strlen($user)) { ## e.g. the class is being loaded
			return;
		}
		$this->close();
		$this->mServer = $server;
		$this->mPort = $port = $wgDBport;
		$this->mUser = $user;
		$this->mPassword = $password;
		$this->mDBname = $dbName;

		$connectVars = array(
			'dbname' => $dbName,
			'user' => $user,
			'password' => $password );
		if ($server!=false && $server!="") {
			$connectVars['host'] = $server;
		}
		if ($port!=false && $port!="") {
			$connectVars['port'] = $port;
		}
		$connectString = $this->makeConnectionString( $connectVars, PGSQL_CONNECT_FORCE_NEW );

		$this->installErrorHandler();
		$this->mConn = pg_connect( $connectString );
		$phpError = $this->restoreErrorHandler();

		if ( !$this->mConn ) {
			wfDebug( "DB connection error\n" );
			wfDebug( "Server: $server, Database: $dbName, User: $user, Password: " . substr( $password, 0, 3 ) . "...\n" );
			wfDebug( $this->lastError()."\n" );
			if ( !$this->mFailFunction ) {
				throw new DBConnectionError( $this, $phpError );
			} else {
				return false;
			}
		}

		$this->mOpened = true;

		global $wgCommandLineMode;
		## If called from the command-line (e.g. importDump), only show errors
		if ($wgCommandLineMode) {
			$this->doQuery( "SET client_min_messages = 'ERROR'" );
		}

		$this->doQuery( "SET client_encoding='UTF8'" );

		global $wgDBmwschema, $wgDBts2schema;
		if (isset( $wgDBmwschema ) && isset( $wgDBts2schema )
			&& $wgDBmwschema !== 'mediawiki'
			&& preg_match( '/^\w+$/', $wgDBmwschema )
			&& preg_match( '/^\w+$/', $wgDBts2schema )
		) {
			$safeschema = $this->quote_ident($wgDBmwschema);
			$safeschema2 = $this->quote_ident($wgDBts2schema);
			$this->doQuery( "SET search_path = $safeschema, $wgDBts2schema, public" );
		}

		return $this->mConn;
	}

	function makeConnectionString( $vars ) {
		$s = '';
		foreach ( $vars as $name => $value ) {
			$s .= "$name='" . str_replace( "'", "\\'", $value ) . "' ";
		}
		return $s;
	}


	function initial_setup($password, $dbName) {
		// If this is the initial connection, setup the schema stuff and possibly create the user
		global $wgDBname, $wgDBuser, $wgDBpassword, $wgDBsuperuser, $wgDBmwschema, $wgDBts2schema;

		print "<li>Checking the version of Postgres...";
		$version = $this->getServerVersion();
		$PGMINVER = '8.1';
		if ($version < $PGMINVER) {
			print "<b>FAILED</b>. Required version is $PGMINVER. You have " . htmlspecialchars( $version ) . "</li>\n";
			dieout("</ul>");
		}
		print "version " . htmlspecialchars( $this->numeric_version ) . " is OK.</li>\n";

		$safeuser = $this->quote_ident($wgDBuser);
		// Are we connecting as a superuser for the first time?
		if ($wgDBsuperuser) {
			// Are we really a superuser? Check out our rights
			$SQL = "SELECT
                      CASE WHEN usesuper IS TRUE THEN
                      CASE WHEN usecreatedb IS TRUE THEN 3 ELSE 1 END
                      ELSE CASE WHEN usecreatedb IS TRUE THEN 2 ELSE 0 END
                    END AS rights
                    FROM pg_catalog.pg_user WHERE usename = " . $this->addQuotes($wgDBsuperuser);
			$rows = $this->numRows($res = $this->doQuery($SQL));
			if (!$rows) {
				print "<li>ERROR: Could not read permissions for user \"" . htmlspecialchars( $wgDBsuperuser ) . "\"</li>\n";
				dieout('</ul>');
			}
			$perms = pg_fetch_result($res, 0, 0);

			$SQL = "SELECT 1 FROM pg_catalog.pg_user WHERE usename = " . $this->addQuotes($wgDBuser);
			$rows = $this->numRows($this->doQuery($SQL));
			if ($rows) {
				print "<li>User \"" . htmlspecialchars( $wgDBuser ) . "\" already exists, skipping account creation.</li>";
			}
			else {
				if ($perms != 1 and $perms != 3) {
					print "<li>ERROR: the user \"" . htmlspecialchars( $wgDBsuperuser ) . "\" cannot create other users. ";
					print 'Please use a different Postgres user.</li>';
					dieout('</ul>');
				}
				print "<li>Creating user <b>" . htmlspecialchars( $wgDBuser ) . "</b>...";
				$safepass = $this->addQuotes($wgDBpassword);
				$SQL = "CREATE USER $safeuser NOCREATEDB PASSWORD $safepass";
				$this->doQuery($SQL);
				print "OK</li>\n";
			}
			// User now exists, check out the database
			if ($dbName != $wgDBname) {
				$SQL = "SELECT 1 FROM pg_catalog.pg_database WHERE datname = " . $this->addQuotes($wgDBname);
				$rows = $this->numRows($this->doQuery($SQL));
				if ($rows) {
					print "<li>Database \"" . htmlspecialchars( $wgDBname ) . "\" already exists, skipping database creation.</li>";
				}
				else {
					if ($perms < 1) {
						print "<li>ERROR: the user \"" . htmlspecialchars( $wgDBsuperuser ) . "\" cannot create databases. ";
						print 'Please use a different Postgres user.</li>';
						dieout('</ul>');
					}
					print "<li>Creating database <b>" . htmlspecialchars( $wgDBname ) . "</b>...";
					$safename = $this->quote_ident($wgDBname);
					$SQL = "CREATE DATABASE $safename OWNER $safeuser ";
					$this->doQuery($SQL);
					print "OK</li>\n";
					// Hopefully tsearch2 and plpgsql are in template1...
				}

				// Reconnect to check out tsearch2 rights for this user
				print "<li>Connecting to \"" . htmlspecialchars( $wgDBname ) . "\" as superuser \"" .
					htmlspecialchars( $wgDBsuperuser ) . "\" to check rights...";

				$connectVars = array();
				if ($this->mServer!=false && $this->mServer!="") {
					$connectVars['host'] = $this->mServer;
				}
				if ($this->mPort!=false && $this->mPort!="") {
					$connectVars['port'] = $this->mPort;
				}
				$connectVars['dbname'] = $wgDBname;
				$connectVars['user'] = $wgDBsuperuser;
				$connectVars['password'] = $password;

				@$this->mConn = pg_connect( $this->makeConnectionString( $connectVars ) );
				if ( !$this->mConn ) {
					print "<b>FAILED TO CONNECT!</b></li>";
					dieout("</ul>");
				}
				print "OK</li>\n";
			}

			if ($this->numeric_version < 8.3) {
				// Tsearch2 checks
				print "<li>Checking that tsearch2 is installed in the database \"" . 
					htmlspecialchars( $wgDBname ) . "\"...";
				if (! $this->tableExists("pg_ts_cfg", $wgDBts2schema)) {
					print "<b>FAILED</b>. tsearch2 must be installed in the database \"" . 
						htmlspecialchars( $wgDBname ) . "\".";
					print "Please see <a href='http://www.devx.com/opensource/Article/21674/0/page/2'>this article</a>";
					print " for instructions or ask on #postgresql on irc.freenode.net</li>\n";
					dieout("</ul>");
				}
				print "OK</li>\n";
				print "<li>Ensuring that user \"" . htmlspecialchars( $wgDBuser ) . 
					"\" has select rights on the tsearch2 tables...";
				foreach (array('cfg','cfgmap','dict','parser') as $table) {
					$SQL = "GRANT SELECT ON pg_ts_$table TO $safeuser";
					$this->doQuery($SQL);
				}
				print "OK</li>\n";
			}

			// Setup the schema for this user if needed
			$result = $this->schemaExists($wgDBmwschema);
			$safeschema = $this->quote_ident($wgDBmwschema);
			if (!$result) {
				print "<li>Creating schema <b>" . htmlspecialchars( $wgDBmwschema ) . "</b> ...";
				$result = $this->doQuery("CREATE SCHEMA $safeschema AUTHORIZATION $safeuser");
				if (!$result) {
					print "<b>FAILED</b>.</li>\n";
					dieout("</ul>");
				}
				print "OK</li>\n";
			}
			else {
				print "<li>Schema already exists, explicitly granting rights...\n";
				$safeschema2 = $this->addQuotes($wgDBmwschema);
				$SQL = "SELECT 'GRANT ALL ON '||pg_catalog.quote_ident(relname)||' TO $safeuser;'\n".
					"FROM pg_catalog.pg_class p, pg_catalog.pg_namespace n\n".
					"WHERE relnamespace = n.oid AND n.nspname = $safeschema2\n".
					"AND p.relkind IN ('r','S','v')\n";
				$SQL .= "UNION\n";
				$SQL .= "SELECT 'GRANT ALL ON FUNCTION '||pg_catalog.quote_ident(proname)||'('||\n".
					"pg_catalog.oidvectortypes(p.proargtypes)||') TO $safeuser;'\n".
					"FROM pg_catalog.pg_proc p, pg_catalog.pg_namespace n\n".
					"WHERE p.pronamespace = n.oid AND n.nspname = $safeschema2";
				$res = $this->doQuery($SQL);
				if (!$res) {
					print "<b>FAILED</b>. Could not set rights for the user.</li>\n";
					dieout("</ul>");
				}
				$this->doQuery("SET search_path = $safeschema");
				$rows = $this->numRows($res);
				while ($rows) {
					$rows--;
					$this->doQuery(pg_fetch_result($res, $rows, 0));
				}
				print "OK</li>";
			}

			// Install plpgsql if needed
			$this->setup_plpgsql();

			$wgDBsuperuser = '';
			return true; // Reconnect as regular user

		} // end superuser

		if (!defined('POSTGRES_SEARCHPATH')) {

			if ($this->numeric_version < 8.3) {
				// Do we have the basic tsearch2 table?
				print "<li>Checking for tsearch2 in the schema \"" . htmlspecialchars( $wgDBts2schema ) . "\"...";
				if (! $this->tableExists("pg_ts_dict", $wgDBts2schema)) {
					print "<b>FAILED</b>. Make sure tsearch2 is installed. See <a href=";
					print "'http://www.devx.com/opensource/Article/21674/0/page/2'>this article</a>";
					print " for instructions.</li>\n";
					dieout("</ul>");
				}
				print "OK</li>\n";

				// Does this user have the rights to the tsearch2 tables?
				$ctype = pg_fetch_result($this->doQuery("SHOW lc_ctype"),0,0);
				print "<li>Checking tsearch2 permissions...";
				// Let's check all four, just to be safe
				error_reporting( 0 );
				$ts2tables = array('cfg','cfgmap','dict','parser');
				$safetsschema = $this->quote_ident($wgDBts2schema);
				foreach ( $ts2tables AS $tname ) {
					$SQL = "SELECT count(*) FROM $safetsschema.pg_ts_$tname";
					$res = $this->doQuery($SQL);
					if (!$res) {
						print "<b>FAILED</b> to access " . htmlspecialchars( "pg_ts_$tname" ) . 
							". Make sure that the user \"". htmlspecialchars( $wgDBuser ) . 
							"\" has SELECT access to all four tsearch2 tables</li>\n";
						dieout("</ul>");
					}
				}
				$SQL = "SELECT ts_name FROM $safetsschema.pg_ts_cfg WHERE locale = " . $this->addQuotes( $ctype ) ;
				$SQL .= " ORDER BY CASE WHEN ts_name <> 'default' THEN 1 ELSE 0 END";
				$res = $this->doQuery($SQL);
				error_reporting( E_ALL );
				if (!$res) {
					print "<b>FAILED</b>. Could not determine the tsearch2 locale information</li>\n";
					dieout("</ul>");
				}
				print "OK</li>";

				// Will the current locale work? Can we force it to?
				print "<li>Verifying tsearch2 locale with " . htmlspecialchars( $ctype ) . "...";
				$rows = $this->numRows($res);
				$resetlocale = 0;
				if (!$rows) {
					print "<b>not found</b></li>\n";
					print "<li>Attempting to set default tsearch2 locale to \"" . htmlspecialchars( $ctype ) . "\"...";
					$resetlocale = 1;
				}
				else {
					$tsname = pg_fetch_result($res, 0, 0);
					if ($tsname != 'default') {
						print "<b>not set to default (" . htmlspecialchars( $tsname ) . ")</b>";
						print "<li>Attempting to change tsearch2 default locale to \"" . 
							htmlspecialchars( $ctype ) . "\"...";
						$resetlocale = 1;
					}
				}
				if ($resetlocale) {
					$SQL = "UPDATE $safetsschema.pg_ts_cfg SET locale = " . $this->addQuotes( $ctype ) . " WHERE ts_name = 'default'";
					$res = $this->doQuery($SQL);
					if (!$res) {
						print "<b>FAILED</b>. ";
						print "Please make sure that the locale in pg_ts_cfg for \"default\" is set to \"" . 
							htmlspecialchars( $ctype ) . "\"</li>\n";
						dieout("</ul>");
					}
					print "OK</li>";
				}

				// Final test: try out a simple tsearch2 query
				$SQL = "SELECT $safetsschema.to_tsvector('default','MediaWiki tsearch2 testing')";
				$res = $this->doQuery($SQL);
				if (!$res) {
					print "<b>FAILED</b>. Specifically, \"" . htmlspecialchars( $SQL ) . "\" did not work.</li>";
					dieout("</ul>");
				}
				print "OK</li>";
			}

			// Install plpgsql if needed
			$this->setup_plpgsql();

			// Does the schema already exist? Who owns it?
			$result = $this->schemaExists($wgDBmwschema);
			if (!$result) {
				print "<li>Creating schema <b>" . htmlspecialchars( $wgDBmwschema ) . "</b> ...";
				error_reporting( 0 );
				$safeschema = $this->quote_ident($wgDBmwschema);
				$result = $this->doQuery("CREATE SCHEMA $safeschema");
				error_reporting( E_ALL );
				if (!$result) {
					print "<b>FAILED</b>. The user \"" . htmlspecialchars( $wgDBuser ) . 
						"\" must be able to access the schema. ".
						"You can try making them the owner of the database, or try creating the schema with a ".
						"different user, and then grant access to the \"" . 
						htmlspecialchars( $wgDBuser ) . "\" user.</li>\n";
					dieout("</ul>");
				}
				print "OK</li>\n";
			}
			else if ($result != $wgDBuser) {
				print "<li>Schema \"" . htmlspecialchars( $wgDBmwschema ) . "\" exists but is not owned by \"" . 
					htmlspecialchars( $wgDBuser ) . "\". Not ideal.</li>\n";
			}
			else {
				print "<li>Schema \"" . htmlspecialchars( $wgDBmwschema ) . "\" exists and is owned by \"" . 
					htmlspecialchars( $wgDBuser ) . "\". Excellent.</li>\n";
			}

			// Always return GMT time to accomodate the existing integer-based timestamp assumption
			print "<li>Setting the timezone to GMT for user \"" . htmlspecialchars( $wgDBuser ) . "\" ...";
			$SQL = "ALTER USER $safeuser SET timezone = 'GMT'";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<b>FAILED</b>.</li>\n";
				dieout("</ul>");
			}
			print "OK</li>\n";
			// Set for the rest of this session
			$SQL = "SET timezone = 'GMT'";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<li>Failed to set timezone</li>\n";
				dieout("</ul>");
			}

			print "<li>Setting the datestyle to ISO, YMD for user \"" . htmlspecialchars( $wgDBuser ) . "\" ...";
			$SQL = "ALTER USER $safeuser SET datestyle = 'ISO, YMD'";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<b>FAILED</b>.</li>\n";
				dieout("</ul>");
			}
			print "OK</li>\n";
			// Set for the rest of this session
			$SQL = "SET datestyle = 'ISO, YMD'";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<li>Failed to set datestyle</li>\n";
				dieout("</ul>");
			}

			// Fix up the search paths if needed
			print "<li>Setting the search path for user \"" . htmlspecialchars( $wgDBuser ) . "\" ...";
			$path = $this->quote_ident($wgDBmwschema);
			if ($wgDBts2schema !== $wgDBmwschema)
				$path .= ", ". $this->quote_ident($wgDBts2schema);
			if ($wgDBmwschema !== 'public' and $wgDBts2schema !== 'public')
				$path .= ", public";
			$SQL = "ALTER USER $safeuser SET search_path = $path";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<b>FAILED</b>.</li>\n";
				dieout("</ul>");
			}
			print "OK</li>\n";
			// Set for the rest of this session
			$SQL = "SET search_path = $path";
			$result = pg_query($this->mConn, $SQL);
			if (!$result) {
				print "<li>Failed to set search_path</li>\n";
				dieout("</ul>");
			}
			define( "POSTGRES_SEARCHPATH", $path );
		}
	}


	function setup_plpgsql() {
		print "<li>Checking for Pl/Pgsql ...";
		$SQL = "SELECT 1 FROM pg_catalog.pg_language WHERE lanname = 'plpgsql'";
		$rows = $this->numRows($this->doQuery($SQL));
		if ($rows < 1) {
			// plpgsql is not installed, but if we have a pg_pltemplate table, we should be able to create it
			print "not installed. Attempting to install Pl/Pgsql ...";
			$SQL = "SELECT 1 FROM pg_catalog.pg_class c JOIN pg_catalog.pg_namespace n ON (n.oid = c.relnamespace) ".
				"WHERE relname = 'pg_pltemplate' AND nspname='pg_catalog'";
			$rows = $this->numRows($this->doQuery($SQL));
            global $wgDBname;
			if ($rows >= 1) {
			$olde = error_reporting(0);
				error_reporting($olde - E_WARNING);
				$result = $this->doQuery("CREATE LANGUAGE plpgsql");
				error_reporting($olde);
				if (!$result) {
					print "<b>FAILED</b>. You need to install the language plpgsql in the database <tt>" . 
						htmlspecialchars( $wgDBname ) . "</tt></li>";
					dieout("</ul>");
				}
			}
			else {
				print "<b>FAILED</b>. You need to install the language plpgsql in the database <tt>" . 
					htmlspecialchars( $wgDBname ) . "</tt></li>";
				dieout("</ul>");
			}
		}
		print "OK</li>\n";
	}


	/**
	 * Closes a database connection, if it is open
	 * Returns success, true if already closed
	 */
	function close() {
		$this->mOpened = false;
		if ( $this->mConn ) {
			return pg_close( $this->mConn );
		} else {
			return true;
		}
	}

	function doQuery( $sql ) {
		if (function_exists('mb_convert_encoding')) {
			$sql = mb_convert_encoding($sql,'UTF-8');
		}
		$this->mLastResult = pg_query( $this->mConn, $sql);
		$this->mAffectedRows = null; // use pg_affected_rows(mLastResult)
		return $this->mLastResult;
	}

	function queryIgnore( $sql, $fname = '' ) {
		return $this->query( $sql, $fname, true );
	}

	function freeResult( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		if ( !@pg_free_result( $res ) ) {
			throw new DBUnexpectedError($this,  "Unable to free Postgres result\n" );
		}
	}

	function fetchObject( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@$row = pg_fetch_object( $res );
		# FIXME: HACK HACK HACK HACK debug

		# TODO:
		# hashar : not sure if the following test really trigger if the object
		#          fetching failed.
		if( pg_last_error($this->mConn) ) {
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $row;
	}

	function fetchRow( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@$row = pg_fetch_array( $res );
		if( pg_last_error($this->mConn) ) {
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $row;
	}

	function numRows( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		@$n = pg_num_rows( $res );
		if( pg_last_error($this->mConn) ) {
			throw new DBUnexpectedError($this,  'SQL error: ' . htmlspecialchars( pg_last_error($this->mConn) ) );
		}
		return $n;
	}
	function numFields( $res ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_num_fields( $res );
	}
	function fieldName( $res, $n ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_field_name( $res, $n );
	}

	/**
	 * This must be called after nextSequenceVal
	 */
	function insertId() {
		return $this->mInsertId;
	}

	function dataSeek( $res, $row ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_result_seek( $res, $row );
	}

	function lastError() {
		if ( $this->mConn ) {
			return pg_last_error();
		}
		else {
			return "No database connection";
		}
	}
	function lastErrno() {
		return pg_last_error() ? 1 : 0;
	}

	function affectedRows() {
		if ( !is_null( $this->mAffectedRows ) ) {
			// Forced result for simulated queries
			return $this->mAffectedRows;
		}
		if( empty( $this->mLastResult ) )
			return 0;
		return pg_affected_rows( $this->mLastResult );
	}

	/**
	 * Estimate rows in dataset
	 * Returns estimated count, based on EXPLAIN output
	 * This is not necessarily an accurate estimate, so use sparingly
	 * Returns -1 if count cannot be found
	 * Takes same arguments as Database::select()
	 */

	function estimateRowCount( $table, $vars='*', $conds='', $fname = 'DatabasePostgres::estimateRowCount', $options = array() ) {
		$options['EXPLAIN'] = true;
		$res = $this->select( $table, $vars, $conds, $fname, $options );
		$rows = -1;
		if ( $res ) {
			$row = $this->fetchRow( $res );
			$count = array();
			if( preg_match( '/rows=(\d+)/', $row[0], $count ) ) {
				$rows = $count[1];
			}
			$this->freeResult($res);
		}
		return $rows;
	}


	/**
	 * Returns information about an index
	 * If errors are explicitly ignored, returns NULL on failure
	 */
	function indexInfo( $table, $index, $fname = 'DatabasePostgres::indexInfo' ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='$table'";
		$res = $this->query( $sql, $fname );
		if ( !$res ) {
			return null;
		}
		while ( $row = $this->fetchObject( $res ) ) {
			if ( $row->indexname == $this->indexName( $index ) ) {
				return $row;
			}
		}
		return false;
	}

	function indexUnique ($table, $index, $fname = 'DatabasePostgres::indexUnique' ) {
		$sql = "SELECT indexname FROM pg_indexes WHERE tablename='{$table}'".
			" AND indexdef LIKE 'CREATE UNIQUE%(" . 
			$this->strencode( $this->indexName( $index ) ) .
			")'";
		$res = $this->query( $sql, $fname );
		if ( !$res )
			return null;
		while ($row = $this->fetchObject( $res ))
			return true;
		return false;

	}

	/**
	 * INSERT wrapper, inserts an array into a table
	 *
	 * $args may be a single associative array, or an array of these with numeric keys,
	 * for multi-row insert (Postgres version 8.2 and above only).
	 *
	 * @param $table   String: Name of the table to insert to.
	 * @param $args    Array: Items to insert into the table.
	 * @param $fname   String: Name of the function, for profiling
	 * @param $options String or Array. Valid options: IGNORE
	 *
	 * @return bool Success of insert operation. IGNORE always returns true.
	 */
	function insert( $table, $args, $fname = 'DatabasePostgres::insert', $options = array() ) {
		global $wgDBversion;

		if ( !count( $args ) ) {
			return true;
		}

		$table = $this->tableName( $table );
		if (! isset( $wgDBversion ) ) {
			$wgDBversion = $this->getServerVersion();
		}

		if ( !is_array( $options ) )
			$options = array( $options );

		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$multi = true;
			$keys = array_keys( $args[0] );
		}
		else {
			$multi = false;
			$keys = array_keys( $args );
		}

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$ignore = in_array( 'IGNORE', $options ) ? 'mw' : '';

		// If we are not in a transaction, we need to be for savepoint trickery
		$didbegin = 0;
		if ( $ignore ) {
			if (! $this->mTrxLevel) {
				$this->begin();
				$didbegin = 1;
			}
			$olde = error_reporting( 0 );
			// For future use, we may want to track the number of actual inserts
			// Right now, insert (all writes) simply return true/false
			$numrowsinserted = 0;
		}

		$sql = "INSERT INTO $table (" . implode( ',', $keys ) . ') VALUES ';

		if ( $multi ) {
			if ( $wgDBversion >= 8.2 && !$ignore ) {
				$first = true;
				foreach ( $args as $row ) {
					if ( $first ) {
						$first = false;
					} else {
						$sql .= ',';
					}
					$sql .= '(' . $this->makeList( $row ) . ')';
				}
				$res = (bool)$this->query( $sql, $fname, $ignore );
			}
			else {
				$res = true;
				$origsql = $sql;
				foreach ( $args as $row ) {
					$tempsql = $origsql;
					$tempsql .= '(' . $this->makeList( $row ) . ')';

					if ( $ignore ) {
						pg_query($this->mConn, "SAVEPOINT $ignore");
					}

					$tempres = (bool)$this->query( $tempsql, $fname, $ignore );

					if ( $ignore ) {
						$bar = pg_last_error();
						if ($bar != false) {
							pg_query( $this->mConn, "ROLLBACK TO $ignore" );
						}
						else {
							pg_query( $this->mConn, "RELEASE $ignore" );
							$numrowsinserted++;
						}
					}

					// If any of them fail, we fail overall for this function call
					// Note that this will be ignored if IGNORE is set
					if (! $tempres)
						$res = false;
				}
			}
		}
		else {
			// Not multi, just a lone insert
			if ( $ignore ) {
				pg_query($this->mConn, "SAVEPOINT $ignore");
			}

			$sql .= '(' . $this->makeList( $args ) . ')';
			$res = (bool)$this->query( $sql, $fname, $ignore );
			if ( $ignore ) {
				$bar = pg_last_error();
				if ($bar != false) {
					pg_query( $this->mConn, "ROLLBACK TO $ignore" );
				}
				else {
					pg_query( $this->mConn, "RELEASE $ignore" );
					$numrowsinserted++;
				}
			}
		}
		if ( $ignore ) {
			$olde = error_reporting( $olde );
			if ($didbegin) {
				$this->commit();
			}

			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;

			// IGNORE always returns true
			return true;
		}


		return $res;

	}

	/**
	 * INSERT SELECT wrapper
	 * $varMap must be an associative array of the form array( 'dest1' => 'source1', ...)
	 * Source items may be literals rather then field names, but strings should be quoted with Database::addQuotes()
	 * $conds may be "*" to copy the whole table
	 * srcTable may be an array of tables.
	 * @todo FIXME: implement this a little better (seperate select/insert)?
	*/
	function insertSelect( $destTable, $srcTable, $varMap, $conds, $fname = 'DatabasePostgres::insertSelect',
		$insertOptions = array(), $selectOptions = array() )
	{
		$destTable = $this->tableName( $destTable );

		// If IGNORE is set, we use savepoints to emulate mysql's behavior
		$ignore = in_array( 'IGNORE', $insertOptions ) ? 'mw' : '';

		if( is_array( $insertOptions ) ) {
			$insertOptions = implode( ' ', $insertOptions );
		}
		if( !is_array( $selectOptions ) ) {
			$selectOptions = array( $selectOptions );
		}
		list( $startOpts, $useIndex, $tailOpts ) = $this->makeSelectOptions( $selectOptions );
		if( is_array( $srcTable ) ) {
			$srcTable = implode( ',', array_map( array( &$this, 'tableName' ), $srcTable ) );
		} else {
			$srcTable = $this->tableName( $srcTable );
		}

		// If we are not in a transaction, we need to be for savepoint trickery
		$didbegin = 0;
		if ( $ignore ) {
			if( !$this->mTrxLevel ) {
				$this->begin();
				$didbegin = 1;
			}
			$olde = error_reporting( 0 );
			$numrowsinserted = 0;
			pg_query( $this->mConn, "SAVEPOINT $ignore");
		}

		$sql = "INSERT INTO $destTable (" . implode( ',', array_keys( $varMap ) ) . ')' .
				" SELECT $startOpts " . implode( ',', $varMap ) .
				" FROM $srcTable $useIndex";

		if ( $conds != '*') {
			$sql .= ' WHERE ' . $this->makeList( $conds, LIST_AND );
		}

		$sql .= " $tailOpts";

		$res = (bool)$this->query( $sql, $fname, $ignore );
		if( $ignore ) {
			$bar = pg_last_error();
			if( $bar != false ) {
				pg_query( $this->mConn, "ROLLBACK TO $ignore" );
			} else {
				pg_query( $this->mConn, "RELEASE $ignore" );
				$numrowsinserted++;
			}
			$olde = error_reporting( $olde );
			if( $didbegin ) {
				$this->commit();
			}

			// Set the affected row count for the whole operation
			$this->mAffectedRows = $numrowsinserted;

			// IGNORE always returns true
			return true;
		}

		return $res;
	}
	
	function tableName( $name ) {
		# Replace reserved words with better ones
		switch( $name ) {
			case 'user':
				return 'mwuser';
			case 'text':
				return 'pagecontent';
			default:
				return $name;
		}
	}

	/**
	 * Return the next in a sequence, save the value for retrieval via insertId()
	 */
	function nextSequenceValue( $seqName ) {
		$safeseq = preg_replace( "/'/", "''", $seqName );
		$res = $this->query( "SELECT nextval('$safeseq')" );
		$row = $this->fetchRow( $res );
		$this->mInsertId = $row[0];
		$this->freeResult( $res );
		return $this->mInsertId;
	}

	/**
	 * Return the current value of a sequence. Assumes it has been nextval'ed in this session.
	 */
	function currentSequenceValue( $seqName ) {
		$safeseq = preg_replace( "/'/", "''", $seqName );
		$res = $this->query( "SELECT currval('$safeseq')" );
		$row = $this->fetchRow( $res );
		$currval = $row[0];
		$this->freeResult( $res );
		return $currval;
	}

	# REPLACE query wrapper
	# Postgres simulates this with a DELETE followed by INSERT
	# $row is the row to insert, an associative array
	# $uniqueIndexes is an array of indexes. Each element may be either a
	# field name or an array of field names
	#
	# It may be more efficient to leave off unique indexes which are unlikely to collide.
	# However if you do this, you run the risk of encountering errors which wouldn't have
	# occurred in MySQL
	function replace( $table, $uniqueIndexes, $rows, $fname = 'DatabasePostgres::replace' ) {
		$table = $this->tableName( $table );

		if (count($rows)==0) {
			return;
		}

		# Single row case
		if ( !is_array( reset( $rows ) ) ) {
			$rows = array( $rows );
		}

		foreach( $rows as $row ) {
			# Delete rows which collide
			if ( $uniqueIndexes ) {
				$sql = "DELETE FROM $table WHERE ";
				$first = true;
				foreach ( $uniqueIndexes as $index ) {
					if ( $first ) {
						$first = false;
						$sql .= "(";
					} else {
						$sql .= ') OR (';
					}
					if ( is_array( $index ) ) {
						$first2 = true;
						foreach ( $index as $col ) {
							if ( $first2 ) {
								$first2 = false;
							} else {
								$sql .= ' AND ';
							}
							$sql .= $col.'=' . $this->addQuotes( $row[$col] );
						}
					} else {
						$sql .= $index.'=' . $this->addQuotes( $row[$index] );
					}
				}
				$sql .= ')';
				$this->query( $sql, $fname );
			}

			# Now insert the row
			$sql = "INSERT INTO $table (" . $this->makeList( array_keys( $row ), LIST_NAMES ) .') VALUES (' .
				$this->makeList( $row, LIST_COMMA ) . ')';
			$this->query( $sql, $fname );
		}
	}

	# DELETE where the condition is a join
	function deleteJoin( $delTable, $joinTable, $delVar, $joinVar, $conds, $fname = 'DatabasePostgres::deleteJoin' ) {
		if ( !$conds ) {
			throw new DBUnexpectedError($this,  'Database::deleteJoin() called with empty $conds' );
		}

		$delTable = $this->tableName( $delTable );
		$joinTable = $this->tableName( $joinTable );
		$sql = "DELETE FROM $delTable WHERE $delVar IN (SELECT $joinVar FROM $joinTable ";
		if ( $conds != '*' ) {
			$sql .= 'WHERE ' . $this->makeList( $conds, LIST_AND );
		}
		$sql .= ')';

		$this->query( $sql, $fname );
	}

	# Returns the size of a text field, or -1 for "unlimited"
	function textFieldSize( $table, $field ) {
		$table = $this->tableName( $table );
		$sql = "SELECT t.typname as ftype,a.atttypmod as size
			FROM pg_class c, pg_attribute a, pg_type t
			WHERE relname='$table' AND a.attrelid=c.oid AND
				a.atttypid=t.oid and a.attname='$field'";
		$res =$this->query($sql);
		$row=$this->fetchObject($res);
		if ($row->ftype=="varchar") {
			$size=$row->size-4;
		} else {
			$size=$row->size;
		}
		$this->freeResult( $res );
		return $size;
	}

	function limitResult($sql, $limit, $offset=false) {
		return "$sql LIMIT $limit ".(is_numeric($offset)?" OFFSET {$offset} ":"");
	}

	function wasDeadlock() {
		return $this->lastErrno() == '40P01';
	}

	function duplicateTableStructure( $oldName, $newName, $temporary = false, $fname = 'DatabasePostgres::duplicateTableStructure' ) {
		return $this->query( 'CREATE ' . ( $temporary ? 'TEMPORARY ' : '' ) . " TABLE $newName (LIKE $oldName INCLUDING DEFAULTS)", $fname );
	}

	function timestamp( $ts=0 ) {
		return wfTimestamp(TS_POSTGRES,$ts);
	}

	/**
	 * Return aggregated value function call
	 */
	function aggregateValue ($valuedata,$valuename='value') {
		return $valuedata;
	}


	function reportQueryError( $error, $errno, $sql, $fname, $tempIgnore = false ) {
		// Ignore errors during error handling to avoid infinite recursion
		$ignore = $this->ignoreErrors( true );
		$this->mErrorCount++;

		if ($ignore || $tempIgnore) {
			wfDebug("SQL ERROR (ignored): $error\n");
			$this->ignoreErrors( $ignore );
		}
		else {
			$message = "A database error has occurred\n" .
				"Query: $sql\n" .
				"Function: $fname\n" .
				"Error: $errno $error\n";
			throw new DBUnexpectedError($this, $message);
		}
	}

	/**
	 * @return string wikitext of a link to the server software's web site
	 */
	function getSoftwareLink() {
		return "[http://www.postgresql.org/ PostgreSQL]";
	}

	/**
	 * @return string Version information from the database
	 */
	function getServerVersion() {
		$versionInfo = pg_version( $this->mConn );
		if ( version_compare( $versionInfo['client'], '7.4.0', 'lt' ) ) {
			// Old client, abort install
			$this->numeric_version = '7.3 or earlier';
		} elseif ( isset( $versionInfo['server'] ) ) {
			// Normal client
			$this->numeric_version = $versionInfo['server'];
		} else {
			// Bug 16937: broken pgsql extension from PHP<5.3
			$this->numeric_version = pg_parameter_status( $this->mConn, 'server_version' );
		}
		return $this->numeric_version;
	}

	/**
	 * Query whether a given relation exists (in the given schema, or the
	 * default mw one if not given)
	 */
	function relationExists( $table, $types, $schema = false ) {
		global $wgDBmwschema;
		if ( !is_array( $types ) )
			$types = array( $types );
		if ( !$schema )
			$schema = $wgDBmwschema;
		$etable = $this->addQuotes( $table );
		$eschema = $this->addQuotes( $schema );
		$SQL = "SELECT 1 FROM pg_catalog.pg_class c, pg_catalog.pg_namespace n "
			. "WHERE c.relnamespace = n.oid AND c.relname = $etable AND n.nspname = $eschema "
			. "AND c.relkind IN ('" . implode("','", $types) . "')";
		$res = $this->query( $SQL );
		$count = $res ? $res->numRows() : 0;
		if ($res)
			$this->freeResult( $res );
		return $count ? true : false;
	}

	/*
	 * For backward compatibility, this function checks both tables and
	 * views.
	 */
	function tableExists( $table, $schema = false ) {
		return $this->relationExists( $table, array( 'r', 'v' ), $schema );
	}

	function sequenceExists( $sequence, $schema = false ) {
		return $this->relationExists( $sequence, 'S', $schema );
	}

	function triggerExists( $table, $trigger ) {
		global $wgDBmwschema;

		$q = <<<SQL
	SELECT 1 FROM pg_class, pg_namespace, pg_trigger
		WHERE relnamespace=pg_namespace.oid AND relkind='r'
		      AND tgrelid=pg_class.oid
		      AND nspname=%s AND relname=%s AND tgname=%s
SQL;
		$res = $this->query(sprintf($q,
				$this->addQuotes($wgDBmwschema),
				$this->addQuotes($table),
				$this->addQuotes($trigger)));
		if (!$res)
			return null;
		$rows = $res->numRows();
		$this->freeResult( $res );
		return $rows;
	}

	function ruleExists( $table, $rule ) {
		global $wgDBmwschema;
		$exists = $this->selectField("pg_rules", "rulename",
				array(	"rulename" => $rule,
					"tablename" => $table,
					"schemaname" => $wgDBmwschema ) );
		return $exists === $rule;
	}

	function constraintExists( $table, $constraint ) {
		global $wgDBmwschema;
		$SQL = sprintf("SELECT 1 FROM information_schema.table_constraints ".
			   "WHERE constraint_schema = %s AND table_name = %s AND constraint_name = %s",
			$this->addQuotes($wgDBmwschema),
			$this->addQuotes($table),
			$this->addQuotes($constraint));
		$res = $this->query($SQL);
		if (!$res)
			return null;
		$rows = $res->numRows();
		$this->freeResult($res);
		return $rows;
	}

	/**
	 * Query whether a given schema exists. Returns the name of the owner
	 */
	function schemaExists( $schema ) {
		$eschema = preg_replace("/'/", "''", $schema);
		$SQL = "SELECT rolname FROM pg_catalog.pg_namespace n, pg_catalog.pg_roles r "
				."WHERE n.nspowner=r.oid AND n.nspname = '$eschema'";
		$res = $this->query( $SQL );
		if ( $res && $res->numRows() ) {
			$row = $res->fetchObject();
			$owner = $row->rolname;
		} else {
			$owner = false;
		}
		if ($res)
			$this->freeResult($res);
		return $owner;
	}

	function fieldInfo( $table, $field ) {
		return PostgresField::fromText($this, $table, $field);
	}
	
	/**
	 * pg_field_type() wrapper
	 */
	function fieldType( $res, $index ) {
		if ( $res instanceof ResultWrapper ) {
			$res = $res->result;
		}
		return pg_field_type( $res, $index );
	}

	function begin( $fname = 'DatabasePostgres::begin' ) {
		$this->query( 'BEGIN', $fname );
		$this->mTrxLevel = 1;
	}

	function commit( $fname = 'DatabasePostgres::commit' ) {
		$this->query( 'COMMIT', $fname );
		$this->mTrxLevel = 0;
	}

	/* Not even sure why this is used in the main codebase... */
	function limitResultForUpdate( $sql, $num ) {
		return $sql;
	}

	function setup_database() {
		global $wgVersion, $wgDBmwschema, $wgDBts2schema, $wgDBport, $wgDBuser;

		// Make sure that we can write to the correct schema
		// If not, Postgres will happily and silently go to the next search_path item
		$ctest = "mediawiki_test_table";
		$safeschema = $this->quote_ident($wgDBmwschema);
		if ($this->tableExists($ctest, $wgDBmwschema)) {
			$this->doQuery("DROP TABLE $safeschema.$ctest");
		}
		$SQL = "CREATE TABLE $safeschema.$ctest(a int)";
		$olde = error_reporting( 0 );
		$res = $this->doQuery($SQL);
		error_reporting( $olde );
		if (!$res) {
			print "<b>FAILED</b>. Make sure that the user \"" . htmlspecialchars( $wgDBuser ) . 
				"\" can write to the schema \"" . htmlspecialchars( $wgDBmwschema ) . "\"</li>\n";
			dieout(""); # Will close the main list <ul> and finish the page.
		}
		$this->doQuery("DROP TABLE $safeschema.$ctest");

		$res = $this->sourceFile( "../maintenance/postgres/tables.sql" );
		if ($res === true) {
			print " done.</li>\n";
		} else {
			print " <b>FAILED</b></li>\n";
			dieout( htmlspecialchars( $res ) );
		}

		## Update version information
		$mwv = $this->addQuotes($wgVersion);
		$pgv = $this->addQuotes($this->getServerVersion());
		$pgu = $this->addQuotes($this->mUser);
		$mws = $this->addQuotes($wgDBmwschema);
		$tss = $this->addQuotes($wgDBts2schema);
		$pgp = $this->addQuotes($wgDBport);
		$dbn = $this->addQuotes($this->mDBname);
		$ctype = $this->addQuotes( pg_fetch_result($this->doQuery("SHOW lc_ctype"),0,0) );

		echo "<li>Populating interwiki table... ";
		## Avoid the non-standard "REPLACE INTO" syntax
		$f = fopen( "../maintenance/interwiki.sql", 'r' );
		if ( !$f ) {
			print "<b>FAILED</b></li>";
			dieout( "Could not find the interwiki.sql file" );
		}
		## We simply assume it is already empty as we have just created it
		$SQL = "INSERT INTO interwiki(iw_prefix,iw_url,iw_local) VALUES ";
		while ( ! feof( $f ) ) {
			$line = fgets($f,1024);
			$matches = array();
			if (!preg_match('/^\s*(\(.+?),(\d)\)/', $line, $matches)) {
				continue;
			}
			$this->query("$SQL $matches[1],$matches[2])");
		}
		print " successfully populated.</li>\n";

		$this->doQuery("COMMIT");
	}

	function encodeBlob( $b ) {
		return new Blob ( pg_escape_bytea( $b ) ) ;
	}

	function decodeBlob( $b ) {
		if ($b instanceof Blob) {
			$b = $b->fetch();
		}
		return pg_unescape_bytea( $b );
	}

	function strencode( $s ) { ## Should not be called by us
		return pg_escape_string( $s );
	}

	function addQuotes( $s ) {
		if ( is_null( $s ) ) {
			return 'NULL';
		} else if ( is_bool( $s ) ) {
			return intval( $s );
		} else if ($s instanceof Blob) {
			return "'".$s->fetch($s)."'";
		}
		return "'" . pg_escape_string($s) . "'";
	}

	function quote_ident( $s ) {
		return '"' . preg_replace( '/"/', '""', $s) . '"';
	}

	/**
	 * Postgres specific version of replaceVars.
	 * Calls the parent version in Database.php
	 *
	 * @private
	 *
	 * @param $ins String: SQL string, read from a stream (usually tables.sql)
	 *
	 * @return string SQL string
	 */
	protected function replaceVars( $ins ) {

		$ins = parent::replaceVars( $ins );

		if ($this->numeric_version >= 8.3) {
			// Thanks for not providing backwards-compatibility, 8.3
			$ins = preg_replace( "/to_tsvector\s*\(\s*'default'\s*,/", 'to_tsvector(', $ins );
		}

		if ($this->numeric_version <= 8.1) { // Our minimum version
			$ins = str_replace( 'USING gin', 'USING gist', $ins );
		}

		return $ins;
	}

	/**
	 * Various select options
	 *
	 * @private
	 *
	 * @param $options Array: an associative array of options to be turned into
	 *              an SQL query, valid keys are listed in the function.
	 * @return array
	 */
	function makeSelectOptions( $options ) {
		$preLimitTail = $postLimitTail = '';
		$startOpts = $useIndex = '';

		$noKeyOptions = array();
		foreach ( $options as $key => $option ) {
			if ( is_numeric( $key ) ) {
				$noKeyOptions[$option] = true;
			}
		}

		if ( isset( $options['GROUP BY'] ) ) $preLimitTail .= " GROUP BY " . $options['GROUP BY'];
		if ( isset( $options['HAVING'] ) ) $preLimitTail .= " HAVING {$options['HAVING']}";
		if ( isset( $options['ORDER BY'] ) ) $preLimitTail .= " ORDER BY " . $options['ORDER BY'];

		//if (isset($options['LIMIT'])) {
		//	$tailOpts .= $this->limitResult('', $options['LIMIT'],
		//		isset($options['OFFSET']) ? $options['OFFSET']
		//		: false);
		//}

		if ( isset( $noKeyOptions['FOR UPDATE'] ) ) $postLimitTail .= ' FOR UPDATE';
		if ( isset( $noKeyOptions['LOCK IN SHARE MODE'] ) ) $postLimitTail .= ' LOCK IN SHARE MODE';
		if ( isset( $noKeyOptions['DISTINCT'] ) || isset( $noKeyOptions['DISTINCTROW'] ) ) $startOpts .= 'DISTINCT';

		return array( $startOpts, $useIndex, $preLimitTail, $postLimitTail );
	}

	function setFakeMaster( $enabled = true ) {}

	function getDBname() {
		return $this->mDBname;
	}

	function getServer() {
		return $this->mServer;
	}

	function buildConcat( $stringList ) {
		return implode( ' || ', $stringList );
	}

	public function getSearchEngine() {
		return "SearchPostgres";
	}
} // end DatabasePostgres class
