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

	static protected $changes;

	static protected $changesList;

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
	}

	public function testBeginRecentChangesList() {
		$changesList = new EnhancedChangesList( $this->getContext() );

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
	 * @dataProvider firstRecentChangesLineProvider
	 */
	public function testFirstRecentChangesLine( $changes, $matchers ) {
		$changesList = new EnhancedChangesList( $this->getContext() );
		$changesList->beginRecentChangesList();

		foreach( $changes as $change ) {
			$line = $changesList->recentChangesLine( $change );

			foreach( $matchers as $matcher ) {
				$this->assertTag( $matcher, $line );
			}

			break;
		}
	}

	public function firstRecentChangesLineProvider() {
		return array(
			array(
				$this->getRecentChanges(),
				array(
					array(
						'tag' => 'h4',
						'content' => '3 November 2013'
					)
				)
			)
		);
	}

	/**
	 * @dataProvider emptyRecentChangesLinesProvider
	 */
	public function testEmptyRecentChangesLines( $changes, $lineNumbers ) {
		$changesList = new EnhancedChangesList( $this->getContext() );
		$changesList->beginRecentChangesList();

		foreach( $changes as $i => $change ) {
			$line = $changesList->recentChangesLine( $change );

			if ( in_array( $i, $lineNumbers ) ) {
				$this->assertEquals( '', $line, "change line $i is empty" );
			}
		}
	}

	public function emptyRecentChangesLinesProvider() {
		return array(
			array(
				$this->getRecentChanges(),
				range( 1, 3 )
			)
		);
	}

	/**
	 * @dataProvider outputChangesBlockProvider
	 */
	public function testOutputChangesBlock( $changes, $position, $matchers, $patterns ) {
		$changesList = new EnhancedChangesList( $this->getContext() );
		$changesList->beginRecentChangesList();

		foreach( $changes as $i => $change ) {
			$line = $changesList->recentChangesLine( $change );

			if ( $i === $position ) {
				foreach( $matchers as $matcher ) {
					$this->assertTag( $matcher[0], $line, $matcher[1] );
				}

				foreach( $patterns as $pattern ) {
					preg_match_all( $pattern[0], $line, $matches );
					$this->assertEquals( $pattern[1], count( $matches[0] ), $pattern[2] );
				}
			}
		}
	}

	public function outputChangesBlockProvider() {
		return array(
			array(
				$this->getRecentChanges(),
				4,
				$this->getMatchers(),
				array_merge(
					$this->getSpanPatterns(),
					$this->getBytesChangedPatterns(),
					$this->getLinkPatterns(),
					$this->getOtherPatterns()
				)
			)
		);
	}

	private function getSpanPatterns() {
		return array(
			array(
				'/<span class="comment">\(content was &quot;hello&quot;\)<\/span>/',
				1,
				'comment span'
			),
			array(
				'/<span class="mw-collapsible-toggle mw-collapsible-arrow mw-enhancedchanges-arrow '
					. 'mw-enhancedchanges-arrow-space"><\/span>/',
				1,
				'arrow space span'
			),
			array(
				'/<span class="mw-title">/',
				2,
				'title span'
			),
			array(
				'/<span class="mw-changeslist-separator">\. \.<\/span>/',
				9,
				'change separators'
			),
			array(
				'/<span class="changedby">/',
				1,
				'changed by span'
			),
			array(
				'/<span class="mw-enhanced-rc-time">/',
				2,
				'enhanced rc time span'
			),
			array(
				'/<span class="mw-usertoollinks">/',
				4,
				'user tool links span'
			)
		);
	}

	private function getBytesChangedPatterns() {
		return array(
			array(
				'/<span dir="ltr" class="mw-plusminus-pos" title="210 bytes after change">/',
				1,
				'210 bytes added span'
			),
			array(
				'/<span dir="ltr" class="mw-plusminus-neg" title="210 bytes after change">/',
				1,
				'210 bytes added span'
			),
			array(
				'/<span dir="ltr" class="mw-plusminus-neg" title="188 bytes after change">/',
				1,
				'188 bytes after span'
			),
			array(
				'/<span dir="ltr" class="mw-plusminus-pos" title="212 bytes after change">/',
				1,

				'212 bytes after span'
			)
		);
	}

	private function getLinkPatterns() {
		return array_merge(
			$this->getDeletionLogPattern(),
			$this->getTitleLinkPatterns(),
			$this->getUserLinkPatterns(),
			$this->getDiffLinkPatterns()
		);
	}

	private function getDeletionLogPattern() {
		return array(
			array(
				'/<a href="\/wiki\/Special:Log\/delete" title="Special:Log\/delete">Deletion log<\/a>/',
				1,
				'Deletion log link'
			)
		);
	}

	private function getTitleLinkPatterns() {
		return array(
			array(
				'/<a href="\/wiki\/Xyz" title="Xyz" class="mw-changeslist-title">Xyz<\/a>/',
				1,
				'Xyz title link'
			),
			array(
				'/<a href="\/wiki\/Abc" title="Abc" class="mw-changeslist-title">Abc<\/a>/',
				1,
				'Abc title link'
			)
		);
	}

	private function getUserLinkPatterns() {
		return array(
			array(
				'/<a href="\/index.php\?title=User:Mary&amp;action=edit&amp;redlink=1" class='
					. '"new mw-userlink" title="User:Mary \(page does not exist\)">Mary<\/a>/',
				3,
				'Mary user page link',
			),
			array(
				'/<a href="\/index.php\?title=User_talk:Mary&amp;action=edit&amp;redlink=1" '
					. 'class="new" title="User talk:Mary \(page does not exist\)">Talk<\/a>/',
				2,
				'Mary user talk link'
			),
			array(
				'/<a href="\/wiki\/Special:Contributions\/Mary" '
					. 'title="Special:Contributions\/Mary">contribs<\/a>/',
				2,
				'Mary contribs link'
			),
			array(
				'/<a href="\/wiki\/Special:Contributions\/127.0.0.2" title='
					. '"Special:Contributions\/127.0.0.2" class="mw-userlink">127.0.0.2<\/a>/',
				2,
				'127.0.0.2 contribs link'
			),
			array(
				'/<a href="\/index.php\?title=User_talk:127.0.0.2&amp;action=edit&amp;redlink=1" '
					. 'class="new" title="User talk:127.0.0.2 \(page does not exist\)">Talk<\/a>/',
				1,
				'127.0.0.2 talk link'
			)
		);
	}

	private function getDiffLinkPatterns() {
		return array(
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;diff=193&amp;oldid=190" '
					. 'title="Xyz">2 changes<\/a>/',
				1,
				'Grouped changes diff link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;diff=0&amp;oldid=193"'
					. ' tabindex="1">cur<\/a>/',
				1,
				'Diff #1 cur link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;diff=193&amp;oldid=191"'
					. ' title="Xyz">prev<\/a>/',
				1,
				'Diff #1 prev link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;oldid=191" title="Xyz">'
					. '21:21<\/a>/',
				1,
				'Diff timestamp link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;diff=0&amp;oldid=191" '
					. 'tabindex="3">cur<\/a>/',
				1,
				'Diff #2 cur link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;diff=191&amp;oldid=190" '
					. 'title="Xyz">prev<\/a>/',
				1,
				'Diff #2 prev link'
			),
			array(
				'/<a href="\/index.php\?title=Abc&amp;curid=35&amp;diff=192&amp;oldid=169" '
					. 'tabindex="2">diff<\/a>/',
				1,
				'Abc diff link'
			),
			array(
				'/<a href="\/index.php\?title=Abc&amp;curid=35&amp;action=history" '
					. 'title="Abc">hist<\/a>/',
				1,
				'Abc hist link'
			)
		);
	}

	private function getOtherPatterns() {
		$patterns = array(
			array( '/&#160;&#160;&#160;&#160;&#160;/', 5, 'change flags' ),
			array( '/<h4>31 October 2013<\/h4>/', 1, 'date h4 element' ),
			array( '/deleted page/', 1, 'delete page txt' )
		);

		foreach( array( '22:26', '21:15', '21:16' ) as $time ) {
			$patterns[] = array(
				'/&#160;&#160;&#160;&#160;&#160;' . $time . '&#160;/',
				1,
				"timestamp $time"
			);
		}

		return $patterns;
	}

	private function getMatchers() {
		$matchers = array();

		$matchers[] = array(
			array(
				'tag' => 'div',
				'child' => array(
					'tag' => 'table',
					'attributes' => array(
						'class' => implode( ' ', array(
							'mw-collapsible',
							'mw-collapsed',
							'mw-enhanced-rc',
							'mw-changeslist-ns0-Xyz',
							'mw-changeslist-line-not-watched'
						) )
					),
					'child' => array(
						'tag' => 'tr'
					)
				)
			),
			'test outer html'
		);

		$matchers[] = array(
			array(
				'tag' => 'td',
				'attributes' => array( 'class' => 'mw-enhanced-rc-nested' ),
				'child' => array(
					'tag' => 'span',
					'attributes' => array( 'class' => 'mw-enhanced-rc-time' ),
					'child' => array(
						'tag' => 'a',
						'content' => '21:15',
						'attributes' => array( 'title' => 'Xyz' )
					)
				)
			),
			'mw-enhanced-rc-nested collapsed html'
		);

		$matchers[] = array(
			array(
				'tag' => 'table',
				'attributes' => array(
					'class' => implode( ' ', array(
						'mw-enhanced-rc',
						'mw-changeslist-ns0-Abc',
						'mw-changeslist-line-not-watched'
					) )
				),
				'child' => array(
					'tag' => 'tr'
				)
			),
			'mw-enhanced Abc single change entry'
		);

		return $matchers;
	}

	/**
	 * @dataProvider endRecentChangesProvider
	 */
	public function testEndRecentChangesList( $changes ) {
		$changesList = new EnhancedChangesList( $this->getContext() );
		$changesList->beginRecentChangesList();

		foreach( $changes as $change ) {
			$changesList->recentChangesLine( $change, false );
		}

		$end = $changesList->endRecentChangesList();

		foreach( $this->getEndMatchers() as $matcher ) {
			$this->assertTag( $matcher[0], $end, $matcher[1] );
		}

		$patterns = $this->getEndLinkPatterns();

		foreach( $patterns as $pattern ) {
			preg_match_all( $pattern[0], $end, $matches );
			$this->assertEquals( $pattern[1], count( $matches[0] ), $pattern[2] );
		}
	}

	public function endRecentChangesProvider() {
		return array(
			array( $this->getRecentChanges() )
		);
	}

	private function getEndMatchers() {
		$matchers = array();

		$matchers[] = array(
			array(
				'tag' => 'div',
				'child' => array(
					'tag' => 'table',
					'attributes' => array(
						'class' => implode( ' ', array(
							'mw-enhanced-rc',
							'mw-changeslist-ns0-Xyz',
							'mw-changeslist-line-not-watched'
						) )
					),
					'child' => array(
						'tag' => 'tr',
						'child' => array(
							'tag' => 'td',
							'attributes' => array( 'class' => 'mw-enhanced-rc' )
						)
					)
				)
			),
			'end line div and table html'
		);

		return $matchers;
	}

	private function getEndLinkPatterns() {
		return array(
			array(
				'/<a href="\/wiki\/Xyz" title="Xyz" class="mw-changeslist-title">Xyz<\/a>/',
				1,
				'Xyz title link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;diff=190&amp;oldid=189" '
					. 'tabindex="4">diff<\/a>/',
				1,
				'Diff link'
			),
			array(
				'/<a href="\/index.php\?title=Xyz&amp;curid=5&amp;action=history" '
					. 'title="Xyz">hist<\/a>/',
				1,
				'History link'
			),
			array(
				'/<a href="\/index.php\?title=User:Mary&amp;action=edit&amp;redlink=1" class='
					. '"new mw-userlink" title="User:Mary \(page does not exist\)">Mary<\/a>/',
				1,
				'Mary user page link'
			),
			array(
				'/<a href="\/index.php\?title=User_talk:Mary&amp;action=edit&amp;redlink=1" '
					. 'class="new" title="User talk:Mary \(page does not exist\)">Talk<\/a>/',
				1,
				'Mary user talk page link'
			),
			array(
				'/<a href="\/wiki\/Special:Contributions\/Mary" '
					. 'title="Special:Contributions\/Mary">contribs<\/a>/',
				1,
				'Mary contribs link'
			)
		);
	}

	protected function getBaseChange() {
		$user = User::newFromName( 'Mary' );

		if ( ! $user->getId() ) {
			$user->addToDatabase();
		}

		return array(
			'rc_user' => $user->getId(),
			'rc_user_text' => $user->getName(),
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
	}

	public function getRecentChanges() {
		if ( !isset( self::$changes ) ) {
			self::$changes = $this->buildRecentChanges();
		}

		return self::$changes;
	}

	private function buildRecentChanges() {
		$base = $this->getBaseChange();

		$rows = array();

		$rows[] = array_merge(
			$base,
			array(
				'rc_id' => 548,
				'rc_timestamp' => '20131103222615',
				'rc_cur_time' => '20131103222615',
				'rc_title' => 'Zzz',
				'rc_comment' => 'content was "hello"',
				'rc_cur_id' => 0,
				'rc_this_oldid' => 0,
				'rc_last_oldid' => 0,
				'rc_old_len' => null,
				'rc_new_len' => null,
				'rc_type' => 3,
				'rc_logid' => 25,
				'rc_log_type' => 'delete',
				'rc_log_action' => 'delete'
			)
		);

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

	private function getContext() {
		$title = Title::newFromText( 'RecentChanges', NS_SPECIAL );

		$context = new RequestContext();
		$context->setTitle( $title );

		$user = User::newFromId( 0 );
		$context->setUser( $user );

		return $context;
	}

}
