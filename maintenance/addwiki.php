<?php
/**
 * Add a new wiki
 * Wikimedia specific!
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
 * @defgroup Wikimedia Wikimedia
 * @ingroup Maintenance
 * @ingroup Wikimedia
 */

require_once( dirname(__FILE__) . '/Maintenance.php' );

class AddWiki extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Add a new wiki to the family. Wikimedia specific!";
		$this->addArg( 'language', 'Language code of new site' );
		$this->addArg( 'site', 'Type of site' );
		$this->addArg( 'dbname', 'Name of database to create' );
	}

	protected function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $IP, $wgLanguageNames, $wgDefaultExternalStore, $wgNoDBParam;

		$wgNoDBParam = true;
		$lang = $this->getArg(0);
		$site = $this->getArg(1);
		$dbName = $this->getArg(2);

		if ( !isset( $wgLanguageNames[$lang] ) ) {
			$this->error( "Language $lang not found in \$wgLanguageNames", true );
		}
		$name = $wgLanguageNames[$lang];

		$dbw = wfGetDB( DB_MASTER );
		$common = "/home/wikipedia/common";

		$this->output( "Creating database $dbName for $lang.$site ($name)\n" );

		# Set up the database
		$dbw->query( "SET table_type=Innodb" );
		$dbw->query( "CREATE DATABASE $dbName" );
		$dbw->selectDB( $dbName );

		$this->output( "Initialising tables\n" );
		$dbw->sourceFile( $this->getDir() . '/tables.sql' );
		$dbw->sourceFile( "$IP/extensions/OAI/update_table.sql" );
		$dbw->sourceFile( "$IP/extensions/AntiSpoof/sql/patch-antispoof.mysql.sql" );
		$dbw->sourceFile( "$IP/extensions/CheckUser/cu_changes.sql" );
		$dbw->sourceFile( "$IP/extensions/CheckUser/cu_log.sql" );
		$dbw->sourceFile( "$IP/extensions/TitleKey/titlekey.sql" );
		$dbw->sourceFile( "$IP/extensions/Oversight/hidden.sql" );
		$dbw->sourceFile( "$IP/extensions/GlobalBlocking/localdb_patches/setup-global_block_whitelist.sql" );
		$dbw->sourceFile( "$IP/extensions/AbuseFilter/abusefilter.tables.sql" );
		$dbw->sourceFile( "$IP/extensions/UsabilityInitiative/PrefStats/PrefStats.sql" );

		$dbw->query( "INSERT INTO site_stats(ss_row_id) VALUES (1)" );

		# Initialise external storage
		if ( is_array( $wgDefaultExternalStore ) ) {
			$stores = $wgDefaultExternalStore;
		} elseif ( $stores ) {
			$stores = array( $wgDefaultExternalStore );
		} else {
			$stores = array();
		}
		if ( count( $stores ) ) {
			global $wgDBuser, $wgDBpassword, $wgExternalServers;
			foreach ( $stores as $storeURL ) {
				$m = array();
				if ( !preg_match( '!^DB://(.*)$!', $storeURL, $m ) ) {
					continue;
				}

				$cluster = $m[1];
				$this->output( "Initialising external storage $cluster...\n" );

				# Hack
				$wgExternalServers[$cluster][0]['user'] = $wgDBuser;
				$wgExternalServers[$cluster][0]['password'] = $wgDBpassword;

				$store = new ExternalStoreDB;
				$extdb = $store->getMaster( $cluster );
				$extdb->query( "SET table_type=InnoDB" );
				$extdb->query( "CREATE DATABASE $dbName" );
				$extdb->selectDB( $dbName );

				# Hack x2
				$blobsTable = $store->getTable( $extdb );
				$sedCmd = "sed s/blobs\\\\\\>/$blobsTable/ " . $this->getDir() . "/storage/blobs.sql";
				$blobsFile = popen( $sedCmd, 'r' );
				$extdb->sourceStream( $blobsFile );
				pclose( $blobsFile );
				$extdb->immediateCommit();
			}
		}

		global $wgTitle, $wgArticle;
		$wgTitle = Title::newFromText( wfMsgWeirdKey( "mainpage/$lang" ) );
		$this->output( "Writing main page to " . $wgTitle->getPrefixedDBkey() . "\n" );
		$wgArticle = new Article( $wgTitle );
		$ucsite = ucfirst( $site );

		$wgArticle->insertNewArticle( $this->getFirstArticle( $ucsite, $name ), '', false, false );

		$this->output( "Adding to dblists\n" );

		# Add to dblist
		$file = fopen( "$common/all.dblist", "a" );
		fwrite( $file, "$dbName\n" );
		fclose( $file );

		# Update the sublists
		shell_exec("cd $common && ./refresh-dblist");

		#print "Constructing interwiki SQL\n";
		# Rebuild interwiki tables
		#passthru( '/home/wikipedia/conf/interwiki/update' );

		$this->output( "Script ended. You still have to:
	* Add any required settings in InitialiseSettings.php
	* Run sync-common-all
	* Run /home/wikipedia/conf/interwiki/update
	" );
	}
	
	private function getFirstArticle( $ucsite, $name ) {
		return <<<EOT
	==This subdomain is reserved for the creation of a [[wikimedia:Our projects|$ucsite]] in '''[[w:en:{$name}|{$name}]]''' language==

	* Please '''do not start editing''' this new site. This site has a test project on the [[incubator:|Wikimedia Incubator]] (or on the [[betawikiversity:|BetaWikiversity]] or on the [[oldwikisource:|Old Wikisource]]) and it will be imported to here.

	* If you would like to help translating the interface to this language, please do not translate here, but go to [[betawiki:|Betawiki]], a special wiki for translating the interface. That way everyone can use it on every wiki using the [[mw:|same software]].

	* For information about how to edit and for other general help, see [[m:Help:Contents|Help on Wikimedia's Meta-Wiki]] or [[mw:Help:Contents|Help on MediaWiki.org]].

	== Sister projects ==
	<span class="plainlinks">
	[http://www.wikipedia.org Wikipedia] |
	[http://www.wiktionary.org Wiktonary] |
	[http://www.wikibooks.org Wikibooks] |
	[http://www.wikinews.org Wikinews] |
	[http://www.wikiquote.org Wikiquote] |
	[http://www.wikisource.org Wikisource]
	[http://www.wikiversity.org Wikiversity]
	</span>

	See Wikimedia's [[m:|Meta-Wiki]] for the coordination of these projects.

	[[aa:]]
	[[af:]]
	[[als:]]
	[[ar:]]
	[[de:]]
	[[en:]]
	[[as:]]
	[[ast:]]
	[[ay:]]
	[[az:]]
	[[bcl:]]
	[[be:]]
	[[bg:]]
	[[bn:]]
	[[bo:]]
	[[bs:]]
	[[cs:]]
	[[co:]]
	[[cs:]]
	[[cy:]]
	[[da:]]
	[[el:]]
	[[eo:]]
	[[es:]]
	[[et:]]
	[[eu:]]
	[[fa:]]
	[[fi:]]
	[[fr:]]
	[[fy:]]
	[[ga:]]
	[[gl:]]
	[[gn:]]
	[[gu:]]
	[[he:]]
	[[hi:]]
	[[hr:]]
	[[hsb:]]
	[[hy:]]
	[[ia:]]
	[[id:]]
	[[is:]]
	[[it:]]
	[[ja:]]
	[[ka:]]
	[[kk:]]
	[[km:]]
	[[kn:]]
	[[ko:]]
	[[ks:]]
	[[ku:]]
	[[ky:]]
	[[la:]]
	[[ln:]]
	[[lo:]]
	[[lt:]]
	[[lv:]]
	[[hu:]]
	[[mi:]]
	[[mk:]]
	[[ml:]]
	[[mn:]]
	[[mr:]]
	[[ms:]]
	[[mt:]]
	[[my:]]
	[[na:]]
	[[nah:]]
	[[nds:]]
	[[ne:]]
	[[nl:]]
	[[no:]]
	[[oc:]]
	[[om:]]
	[[pa:]]
	[[pl:]]
	[[ps:]]
	[[pt:]]
	[[qu:]]
	[[ro:]]
	[[ru:]]
	[[sa:]]
	[[si:]]
	[[sk:]]
	[[sl:]]
	[[sq:]]
	[[sr:]]
	[[sv:]]
	[[sw:]]
	[[ta:]]
	[[te:]]
	[[tg:]]
	[[th:]]
	[[tk:]]
	[[tl:]]
	[[tr:]]
	[[tt:]]
	[[ug:]]
	[[uk:]]
	[[ur:]]
	[[uz:]]
	[[vi:]]
	[[vo:]]
	[[xh:]]
	[[yo:]]
	[[za:]]
	[[zh:]]
	[[zu:]]

EOT;
	}
}

$maintClass = "AddWiki";
require_once( DO_MAINTENANCE );
