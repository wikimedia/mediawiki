<?php
/**
 * @defgroup Wikimedia Wikimedia
 */

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
 * @file
 * @ingroup Maintenance
 * @ingroup Wikimedia
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class AddWiki extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Add a new wiki to the family. Wikimedia specific!";
		$this->addArg( 'language', 'Language code of new site, e.g. en' );
		$this->addArg( 'site', 'Type of site, e.g. wikipedia' );
		$this->addArg( 'dbname', 'Name of database to create, e.g. enwiki' );
		$this->addArg( 'domain', 'Domain name of the wiki, e.g. en.wikipedia.org' );
	}

	public function getDbType() {
		return Maintenance::DB_ADMIN;
	}

	public function execute() {
		global $IP, $wgDefaultExternalStore, $wgNoDBParam, $wgPasswordSender;

		$wgNoDBParam = true;
		$lang = $this->getArg( 0 );
		$site = $this->getArg( 1 );
		$dbName = $this->getArg( 2 );
		$domain = $this->getArg( 3 );
		$languageNames = Language::getLanguageNames();

		if ( !isset( $languageNames[$lang] ) ) {
			$this->error( "Language $lang not found in \$wgLanguageNames", true );
		}
		$name = $languageNames[$lang];

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
		$dbw->sourceFile( "$IP/extensions/ProofreadPage/ProofreadPage.sql" );
		$dbw->sourceFile( "$IP/extensions/UsabilityInitiative/ClickTracking/ClickTrackingEvents.sql" );
		$dbw->sourceFile( "$IP/extensions/UsabilityInitiative/ClickTracking/ClickTracking.sql" );
		$dbw->sourceFile( "$IP/extensions/UsabilityInitiative/UserDailyContribs/UserDailyContribs.sql" );
		$dbw->sourceFile( "$IP/extensions/UsabilityInitiative/OptIn/OptIn.sql" );

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
				$extdb->commit();
			}
		}

		global $wgTitle, $wgArticle;
		$wgTitle = Title::newFromText( wfMsgWeirdKey( "mainpage/$lang" ) );
		$this->output( "Writing main page to " . $wgTitle->getPrefixedDBkey() . "\n" );
		$wgArticle = new Article( $wgTitle );
		$ucsite = ucfirst( $site );

		$wgArticle->doEdit( $this->getFirstArticle( $ucsite, $name ), '', EDIT_NEW | EDIT_DEFER_UPDATES | EDIT_AUTOSUMMARY,
			false, null, false, false, '', true );

		$this->output( "Adding to dblists\n" );

		# Add to dblist
		$file = fopen( "$common/all.dblist", "a" );
		fwrite( $file, "$dbName\n" );
		fclose( $file );

		# Update the sublists
		shell_exec( "cd $common && ./refresh-dblist" );

		# print "Constructing interwiki SQL\n";
		# Rebuild interwiki tables
		# passthru( '/home/wikipedia/conf/interwiki/update' );
		
		$time = wfTimestamp( TS_RFC2822 );
		$escDbName = wfEscapeShellArg( $dbname );
		$escTime = wfEscapeShellArg( $time );
		$escUcsite = wfEscapeShellArg( $ucsite );
		$escName = wfEscapeShellArg( $name );
		$escLang = wfEscapeShellArg( $lang );
		$escDomain = wfEscapeShellArg( $domain );
		shell_exec( "echo notifyNewProjects $escDbName $escTime $escUcsite $escName $escLang $escDomain | at now + 15 minutes" );
		
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

* If you would like to help translating the interface to this language, please do not translate here, but go to [[translatewiki:|translatewiki]], a special wiki for translating the interface. That way everyone can use it on every wiki using the [[mw:|same software]].

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
[[ab:]]
[[ace:]]
[[af:]]
[[ak:]]
[[als:]]
[[am:]]
[[an:]]
[[ang:]]
[[ar:]]
[[arc:]]
[[arz:]]
[[as:]]
[[ast:]]
[[av:]]
[[ay:]]
[[az:]]
[[ba:]]
[[bar:]]
[[bat-smg:]]
[[bcl:]]
[[be:]]
[[be-x-old:]]
[[bg:]]
[[bh:]]
[[bi:]]
[[bm:]]
[[bn:]]
[[bo:]]
[[bpy:]]
[[br:]]
[[bs:]]
[[bug:]]
[[bxr:]]
[[ca:]]
[[cbk-zam:]]
[[cdo:]]
[[ce:]]
[[ceb:]]
[[ch:]]
[[cho:]]
[[chr:]]
[[chy:]]
[[ckb:]]
[[co:]]
[[cr:]]
[[crh:]]
[[cs:]]
[[csb:]]
[[cu:]]
[[cv:]]
[[cy:]]
[[da:]]
[[de:]]
[[diq:]]
[[dk:]]
[[dsb:]]
[[dv:]]
[[dz:]]
[[ee:]]
[[el:]]
[[eml:]]
[[en:]]
[[eo:]]
[[es:]]
[[et:]]
[[eu:]]
[[ext:]]
[[fa:]]
[[ff:]]
[[fi:]]
[[fiu-vro:]]
[[fj:]]
[[fo:]]
[[fr:]]
[[frp:]]
[[fur:]]
[[fy:]]
[[ga:]]
[[gan:]]
[[gd:]]
[[gl:]]
[[glk:]]
[[gn:]]
[[got:]]
[[gu:]]
[[gv:]]
[[ha:]]
[[hak:]]
[[haw:]]
[[he:]]
[[hi:]]
[[hif:]]
[[ho:]]
[[hr:]]
[[hsb:]]
[[ht:]]
[[hu:]]
[[hy:]]
[[hz:]]
[[ia:]]
[[id:]]
[[ie:]]
[[ig:]]
[[ii:]]
[[ik:]]
[[ilo:]]
[[io:]]
[[is:]]
[[it:]]
[[iu:]]
[[ja:]]
[[jbo:]]
[[jv:]]
[[ka:]]
[[kaa:]]
[[kab:]]
[[kg:]]
[[ki:]]
[[kj:]]
[[kk:]]
[[kl:]]
[[km:]]
[[kn:]]
[[ko:]]
[[kr:]]
[[ks:]]
[[ksh:]]
[[ku:]]
[[kv:]]
[[kw:]]
[[ky:]]
[[la:]]
[[lad:]]
[[lb:]]
[[lbe:]]
[[lg:]]
[[li:]]
[[lij:]]
[[lmo:]]
[[ln:]]
[[lo:]]
[[lt:]]
[[lv:]]
[[map-bms:]]
[[mdf:]]
[[mg:]]
[[mh:]]
[[mhr:]]
[[mi:]]
[[mk:]]
[[ml:]]
[[mn:]]
[[mo:]]
[[mr:]]
[[ms:]]
[[mt:]]
[[mus:]]
[[mwl:]]
[[my:]]
[[myv:]]
[[mzn:]]
[[na:]]
[[nan:]]
[[nap:]]
[[nds:]]
[[nds-nl:]]
[[ne:]]
[[new:]]
[[ng:]]
[[nl:]]
[[nn:]]
[[no:]]
[[nov:]]
[[nrm:]]
[[nv:]]
[[ny:]]
[[oc:]]
[[om:]]
[[or:]]
[[os:]]
[[pa:]]
[[pag:]]
[[pam:]]
[[pap:]]
[[pdc:]]
[[pi:]]
[[pih:]]
[[pl:]]
[[pms:]]
[[pnt:]]
[[pnb:]]
[[ps:]]
[[pt:]]
[[qu:]]
[[rm:]]
[[rmy:]]
[[rn:]]
[[ro:]]
[[roa-rup:]]
[[roa-tara:]]
[[ru:]]
[[rw:]]
[[sa:]]
[[sah:]]
[[sc:]]
[[scn:]]
[[sco:]]
[[sd:]]
[[se:]]
[[sg:]]
[[sh:]]
[[si:]]
[[simple:]]
[[sk:]]
[[sl:]]
[[sm:]]
[[sn:]]
[[so:]]
[[sq:]]
[[sr:]]
[[srn:]]
[[ss:]]
[[st:]]
[[stq:]]
[[su:]]
[[sv:]]
[[sw:]]
[[szl:]]
[[ta:]]
[[te:]]
[[tet:]]
[[tg:]]
[[th:]]
[[ti:]]
[[tk:]]
[[tl:]]
[[tn:]]
[[to:]]
[[tpi:]]
[[tr:]]
[[ts:]]
[[tt:]]
[[tum:]]
[[tw:]]
[[ty:]]
[[udm:]]
[[ug:]]
[[uk:]]
[[ur:]]
[[uz:]]
[[ve:]]
[[vec:]]
[[vi:]]
[[vls:]]
[[vo:]]
[[wa:]]
[[war:]]
[[wo:]]
[[wuu:]]
[[xal:]]
[[xh:]]
[[yi:]]
[[yo:]]
[[za:]]
[[zea:]]
[[zh:]]
[[zh-classical:]]
[[zh-min-nan:]]
[[zh-yue:]]
[[zu:]]

EOT;
	}
}

$maintClass = "AddWiki";
require_once( DO_MAINTENANCE );
