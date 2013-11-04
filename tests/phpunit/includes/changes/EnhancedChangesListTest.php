<?php

/**
 * @covers EnhancedChangesList
 *
 * @todo test more combinations of changes, options, etc.
 *
 * @group Database
 *
 * @licence GNU GPL v2+
 * @author Katie Filbert < aude.wiki@gmail.com >
 */
class EnhancedChangesListTest extends MediaWikiTestCase {

	protected $context;

	protected $user;

	public function setUp() {
		parent::setUp();

		$flags = array(
			'newpage' => array( 'letter' => 'newpageletter',
				'title' => 'recentchanges-label-newpage' ),
			'minor' => array( 'letter' => 'minoreditletter',
				'title' => 'recentchanges-label-minor', 'class' => 'minoredit' ),
			'bot' => array( 'letter' => 'boteditletter',
				'title' => 'recentchanges-label-bot', 'class' => 'botedit' ),
			'unpatrolled' => array( 'letter' => 'unpatrolledletter',
				'title' => 'recentchanges-label-unpatrolled' )
		);

		$this->setMwGlobals( array(
			'wgLanguageCode' => 'en',
			'wgScriptPath' => '',
			'wgScript' => '/index.php',
			'wgArticlePath' => '/wiki/$1',
			'wgRecentChangesFlags' => $flags
		) );

		$title = Title::newFromText( 'RecentChanges', NS_SPECIAL );

		$this->context = new RequestContext();
		$this->context->setTitle( $title );

		$this->user = User::newFromName( 'Mary' );

		if ( $this->user->getId() === 0 ) {
			$this->user->addToDatabase();
		}

		$user2 = User::newFromId( 0 );
		$this->context->setUser( $user2 );
	}

	public function testBeginRecentChangesList() {
		$changesList = new EnhancedChangesList( $this->context );
		$this->assertTrue( $changesList instanceof EnhancedChangesList );

		$changesList->beginRecentChangesList();

		$out = $changesList->getContext()->getOutput();

		$expectedStyles = array(
			'mediawiki.special.changeslist',
			'mediawiki.special.changeslist.enhanced',
		);

		$this->assertEquals( $expectedStyles, $out->getModuleStyles() );

		$expectedModules = array(
			'jquery.makeCollapsible',
			'mediawiki.icon'
		);

		$this->assertEquals( $expectedModules, $out->getModules() );
	}

	/**
	 * @dataProvider recentChangesLineProvider
	 */
	public function testRecentChangesLine( $expected ) {
		$changesList = new EnhancedChangesList( $this->context );
		$changesList->beginRecentChangesList();
		$changes = $this->getRecentChanges();

		$line = $changesList->recentChangesLine( $changes[0], false );
		$this->assertEquals( $expected[0], $line );

		$line = $changesList->recentChangesLine( $changes[1], false );
		$this->assertEquals( $expected[1], $line );

		$line = $changesList->recentChangesLine( $changes[2], false );
		$this->assertEquals( $expected[2], $line );

		$line = $changesList->recentChangesLine( $changes[3], false );
		$this->assertEquals( $expected[3], $line );
	}

	public function recentChangesLineProvider() {
		$expected1 = "<h4>3 November 2013</h4>\n";
		$expected4 = file_get_contents( __DIR__ . '/../../data/changes/enhancedchanges.html' );

		return array(
			array(
				array( $expected1, '', '', $expected4 )
			)
		);
	}

	/**
	 * @dataProvider endRecentChangesListProvider
	 */
	public function testEndRecentChangesList( $expected ) {
		$changesList = new EnhancedChangesList( $this->context );
		$changesList->beginRecentChangesList();
		$changes = $this->getRecentChanges();

		foreach( $changes as $change ) {
			$changesList->recentChangesLine( $change, false );
		}

		$end = $changesList->endRecentChangesList();
		$this->assertEquals( $expected, $end );
	}

	public function endRecentChangesListProvider() {
		return array(
			array( trim( file_get_contents( __DIR__ . '/../../data/changes/enhancedchangesend.html' ) ) )
		);
	}

	protected function getRecentChanges() {
		$base = array(
			'rc_user' => $this->user->getId(),
			'rc_user_text' => $this->user->getName(),
			'rc_namespace' => 0,
			'rc_title' => 'Xyz',
			'rc_comment' => '',
			'rc_minor' => 0,
			'rc_bot' => 0,
			'rc_cur_id' => 5,
			'rc_type' => 0,
			'rc_patrolled' => 1,
			'rc_ip' => '127.0.0.1',
			'rc_deleted' => 0,
			'rc_logid' => 0,
			'rc_log_type' => null,
			'rc_log_action' => '',
			'rc_params' => '',
			'rc_source' => 'mw.edit'
		);

		$rows = array();
		$rows[] = array_merge(
			$base,
			array(
				'rc_id' => 547,
				'rc_timestamp' => '20131103211549',
				'rc_cur_time' => '20131103211549',
				'rc_this_oldid' => 193,
				'rc_last_oldid' => 191,
				'rc_old_len' => 207,
				'rc_new_len' => 210,
			)
		);

		$rows[] = array_merge(
			$base,
			array(
				'rc_id' => 546,
				'rc_timestamp' => '20131103211649',
				'rc_cur_time' => '20131103211649',
				'rc_title' => 'Abc',
				'rc_cur_id' => 35,
				'rc_this_oldid' => 192,
				'rc_last_oldid' => 169,
				'rc_old_len' => 210,
				'rc_new_len' => 212,
			)
		);

		$rows[] = array_merge(
			$base,
			array(
				'rc_id' => 545,
				'rc_timestamp' => '20131103212153',
				'rc_cur_time' => '20131103212153',
				'rc_user' => 0,
				'rc_user_text' => '127.0.0.2',
				'rc_this_oldid' => 191,
				'rc_last_oldid' => 190,
				'rc_ip' => '127.0.0.2',
				'rc_old_len' => 212,
				'rc_new_len' => 188,
			)
		);

		$rows[] = array_merge(
			$base,
			array(
				'rc_id' => 544,
				'rc_timestamp' => '20131031212339',
				'rc_cur_time' => '20131031212339',
				'rc_this_oldid' => 190,
				'rc_last_oldid' => 189,
				'rc_old_len' => 2,
				'rc_new_len' => 3,
			)
		);

		$changes = array();
		$counter = 0;

		foreach( $rows as $row ) {
			$change = new RecentChange();
			$change->setAttribs( $row );
			$change->counter = $counter;
			$changes[] = $change;

			$counter++;
		}

		return $changes;
	}

}
