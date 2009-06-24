<?php

$wgCommandLineMode = true;
$self = 'Search.t';
define( 'MEDIAWIKI', true );
require 't/Test.php';
require 'includes/Defines.php';
require 'includes/ProfilerStub.php';
require 'LocalSettings.php';
require 'AdminSettings.php';
require 'includes/Setup.php';

function buildTestDatabase( $tables ) {
	global $wgDBprefix, $wgDBserver, $wgDBadminuser, $wgDBadminpassword, $wgDBname, $wgDBtype;
	$oldPrefix = $wgDBprefix;
	$wgDBprefix = 'parsertest';

	$db = wfGetDB ( DB_SLAVE );

	if( $db->isOpen() ) {
		if ( !( stristr( $db->getSoftwareLink(), 'MySQL') && version_compare( $db->getServerVersion(), '4.1', '<' ) ) ) {
			# Database that supports CREATE TABLE ... LIKE
			foreach ($tables as $tbl) {
				$newTableName = $db->tableName( $tbl );
				$tableName = $oldPrefix . $tbl;
				$db->query("CREATE TEMPORARY TABLE $newTableName (LIKE $tableName)");
			}
		} else {
			# Hack for MySQL versions < 4.1, which don't support
			# "CREATE TABLE ... LIKE". Note that
			# "CREATE TEMPORARY TABLE ... SELECT * FROM ... LIMIT 0"
			# would not create the indexes we need....
			foreach ($tables as $tbl) {
				$res = $db->query("SHOW CREATE TABLE $tbl");
				$row = $db->fetchRow($res);
				$create = $row[1];
				$create_tmp = preg_replace('/CREATE TABLE `(.*?)`/', 'CREATE TEMPORARY TABLE `'
					. $wgDBprefix . '\\1`', $create);
				if ($create === $create_tmp) {
					# Couldn't do replacement
					wfDie( "could not create temporary table $tbl" );
				}
				$db->query($create_tmp);
			}

		}
		return $db;
	} else {
		// Something amiss
		return null;
	}
}

class SearchEngineTest {
	var $db, $search;

	function __construct( SearchEngine $search ){
		$this->search = $search;
		$this->db = $this->search->db;
	}

	function insertSearchData() {
		$this->db->safeQuery( <<<END
		INSERT INTO ! (page_id,page_namespace,page_title,page_latest)
		VALUES (1, 0, 'Main_Page', 1),
			   (2, 1, 'Main_Page', 2),
			   (3, 0, 'Smithee', 3),
			   (4, 1, 'Smithee', 4),
			   (5, 0, 'Unrelated_page', 5),
			   (6, 0, 'Another_page', 6),
			   (7, 4, 'Help', 7),
			   (8, 0, 'Thppt', 8),
			   (9, 0, 'Alan_Smithee', 9),
			   (10, 0, 'Pages', 10)
END
			, $this->db->tableName( 'page' ) );
		$this->db->safeQuery( <<<END
		INSERT INTO ! (rev_id,rev_page)
		VALUES (1, 1),
		       (2, 2),
		       (3, 3),
		       (4, 4),
		       (5, 5),
		       (6, 6),
		       (7, 7),
		       (8, 8),
		       (9, 9),
		       (10, 10)
END
			, $this->db->tableName( 'revision' ) );
		$this->db->safeQuery( <<<END
		INSERT INTO ! (old_id,old_text)
		VALUES (1, 'This is a main page'),
			   (2, 'This is a talk page to the main page, see [[smithee]]'),
			   (3, 'A smithee is one who smiths. See also [[Alan Smithee]]'),
			   (4, 'This article sucks.'),
			   (5, 'Nothing in this page is about the S word.'),
			   (6, 'This page also is unrelated.'),
			   (7, 'Help me!'),
			   (8, 'Blah blah'),
			   (9, 'yum'),
			   (10,'are food')
END
			, $this->db->tableName( 'text' ) );
		$this->db->safeQuery( <<<END
		INSERT INTO ! (si_page,si_title,si_text)
		VALUES (1, 'main page', 'this is a main page'),
			   (2, 'main page', 'this is a talk page to the main page, see smithee'),
			   (3, 'smithee', 'a smithee is one who smiths see also alan smithee'),
			   (4, 'smithee', 'this article sucks'),
			   (5, 'unrelated page', 'nothing in this page is about the s word'),
			   (6, 'another page', 'this page also is unrelated'),
			   (7, 'help', 'help me'),
			   (8, 'thppt', 'blah blah'),
			   (9, 'alan smithee', 'yum'),
			   (10, 'pages', 'are food')
END
			, $this->db->tableName( 'searchindex' ) );
	}

	function fetchIds( $results ) {
		$matches = array();
		while( $row = $results->next() ) {
			$matches[] = $row->getTitle()->getPrefixedText();
		}
		$results->free();
		# Search is not guaranteed to return results in a certain order;
		# sort them numerically so we will compare simply that we received
		# the expected matches.
		sort( $matches );
		return $matches;
	}

	function run(){
		if( is_null( $this->db ) ){
			fail( "Can't find a database to test with." );
			return;
		}
		
		$this->insertSearchData();
		plan( 4 );
		
		$exp = array( 'Smithee' );
		$got = $this->fetchIds( $this->search->searchText( 'smithee' ) );
		is( $got, $exp, "Plain search" );

		$exp = array( 'Alan Smithee', 'Smithee' );
		$got = $this->fetchIds( $this->search->searchTitle( 'smithee' ) );
		is( $got, $exp, "Title search" );

		$this->search->setNamespaces( array( 0, 1, 4 ) );

		$exp = array( 'Smithee', 'Talk:Main Page', );
		$got = $this->fetchIds( $this->search->searchText( 'smithee' ) );
		is( $got, $exp, "Power search" );

		$exp = array( 'Alan Smithee', 'Smithee', 'Talk:Smithee', );
		$got = $this->fetchIds( $this->search->searchTitle( 'smithee' ) );
		is( $got, $exp, "Title power search" );
	}
}
