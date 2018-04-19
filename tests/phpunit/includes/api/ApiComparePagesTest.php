<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiComparePages
 */
class ApiComparePagesTest extends ApiTestCase {

	protected static $repl = [];

	protected function setUp() {
		parent::setUp();

		// Set $wgExternalDiffEngine to something bogus to try to force use of
		// the PHP engine rather than wikidiff2.
		$this->setMwGlobals( [
			'wgExternalDiffEngine' => '/dev/null',
		] );
	}

	protected function addPage( $page, $text, $model = CONTENT_MODEL_WIKITEXT ) {
		$title = Title::newFromText( 'ApiComparePagesTest ' . $page );
		$content = ContentHandler::makeContent( $text, $title, $model );

		$page = WikiPage::factory( $title );
		$user = static::getTestSysop()->getUser();
		$status = $page->doEditContent(
			$content, 'Test for ApiComparePagesTest: ' . $text, 0, false, $user
		);
		if ( !$status->isOK() ) {
			$this->fail( "Failed to create $title: " . $status->getWikiText( false, false, 'en' ) );
		}
		return $status->value['revision']->getId();
	}

	public function addDBDataOnce() {
		$user = static::getTestSysop()->getUser();
		self::$repl['creator'] = $user->getName();
		self::$repl['creatorid'] = $user->getId();

		self::$repl['revA1'] = $this->addPage( 'A', 'A 1' );
		self::$repl['revA2'] = $this->addPage( 'A', 'A 2' );
		self::$repl['revA3'] = $this->addPage( 'A', 'A 3' );
		self::$repl['revA4'] = $this->addPage( 'A', 'A 4' );
		self::$repl['pageA'] = Title::newFromText( 'ApiComparePagesTest A' )->getArticleId();

		self::$repl['revB1'] = $this->addPage( 'B', 'B 1' );
		self::$repl['revB2'] = $this->addPage( 'B', 'B 2' );
		self::$repl['revB3'] = $this->addPage( 'B', 'B 3' );
		self::$repl['revB4'] = $this->addPage( 'B', 'B 4' );
		self::$repl['pageB'] = Title::newFromText( 'ApiComparePagesTest B' )->getArticleId();

		self::$repl['revC1'] = $this->addPage( 'C', 'C 1' );
		self::$repl['revC2'] = $this->addPage( 'C', 'C 2' );
		self::$repl['revC3'] = $this->addPage( 'C', 'C 3' );
		self::$repl['pageC'] = Title::newFromText( 'ApiComparePagesTest C' )->getArticleId();

		$id = $this->addPage( 'D', 'D 1' );
		self::$repl['pageD'] = Title::newFromText( 'ApiComparePagesTest D' )->getArticleId();
		wfGetDB( DB_MASTER )->delete( 'revision', [ 'rev_id' => $id ] );

		self::$repl['revE1'] = $this->addPage( 'E', 'E 1' );
		self::$repl['revE2'] = $this->addPage( 'E', 'E 2' );
		self::$repl['revE3'] = $this->addPage( 'E', 'E 3' );
		self::$repl['revE4'] = $this->addPage( 'E', 'E 4' );
		self::$repl['pageE'] = Title::newFromText( 'ApiComparePagesTest E' )->getArticleId();
		wfGetDB( DB_MASTER )->update(
			'page', [ 'page_latest' => 0 ], [ 'page_id' => self::$repl['pageE'] ]
		);

		self::$repl['revF1'] = $this->addPage( 'F', "== Section 1 ==\nF 1.1\n\n== Section 2 ==\nF 1.2" );
		self::$repl['pageF'] = Title::newFromText( 'ApiComparePagesTest F' )->getArticleId();

		WikiPage::factory( Title::newFromText( 'ApiComparePagesTest C' ) )
			->doDeleteArticleReal( 'Test for ApiComparePagesTest' );

		RevisionDeleter::createList(
			'revision',
			RequestContext::getMain(),
			Title::newFromText( 'ApiComparePagesTest B' ),
			[ self::$repl['revB2'] ]
		)->setVisibility( [
			'value' => [
				Revision::DELETED_TEXT => 1,
				Revision::DELETED_USER => 1,
				Revision::DELETED_COMMENT => 1,
			],
			'comment' => 'Test for ApiComparePages',
		] );

		RevisionDeleter::createList(
			'revision',
			RequestContext::getMain(),
			Title::newFromText( 'ApiComparePagesTest B' ),
			[ self::$repl['revB3'] ]
		)->setVisibility( [
			'value' => [
				Revision::DELETED_USER => 1,
				Revision::DELETED_COMMENT => 1,
				Revision::DELETED_RESTRICTED => 1,
			],
			'comment' => 'Test for ApiComparePages',
		] );

		Title::clearCaches(); // Otherwise it has the wrong latest revision for some reason
	}

	protected function doReplacements( &$value ) {
		if ( is_string( $value ) ) {
			if ( preg_match( '/^{{REPL:(.+?)}}$/', $value, $m ) ) {
				$value = self::$repl[$m[1]];
			} else {
				$value = preg_replace_callback( '/{{REPL:(.+?)}}/', function ( $m ) {
					return self::$repl[$m[1]] ?? $m[0];
				}, $value );
			}
		} elseif ( is_array( $value ) || is_object( $value ) ) {
			foreach ( $value as &$v ) {
				$this->doReplacements( $v );
			}
			unset( $v );
		}
	}

	/**
	 * @dataProvider provideDiff
	 */
	public function testDiff( $params, $expect, $exceptionCode = false, $sysop = false ) {
		$this->doReplacements( $params );

		$params += [
			'action' => 'compare',
		];

		$user = $sysop
			? static::getTestSysop()->getUser()
			: static::getTestUser()->getUser();
		if ( $exceptionCode ) {
			try {
				$this->doApiRequest( $params, null, false, $user );
				$this->fail( 'Expected exception not thrown' );
			} catch ( ApiUsageException $ex ) {
				$this->assertTrue( $this->apiExceptionHasCode( $ex, $exceptionCode ),
					"Exception with code $exceptionCode" );
			}
		} else {
			$apiResult = $this->doApiRequest( $params, null, false, $user );
			$apiResult = $apiResult[0];
			$this->doReplacements( $expect );
			$this->assertEquals( $expect, $apiResult );
		}
	}

	public static function provideDiff() {
		// phpcs:disable Generic.Files.LineLength.TooLong
		return [
			'Basic diff, titles' => [
				[
					'fromtitle' => 'ApiComparePagesTest A',
					'totitle' => 'ApiComparePagesTest B',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA4}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest A',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB4}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">A </del>4</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">B </ins>4</div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, page IDs' => [
				[
					'fromid' => '{{REPL:pageA}}',
					'toid' => '{{REPL:pageB}}',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA4}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest A',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB4}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">A </del>4</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">B </ins>4</div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, revision IDs' => [
				[
					'fromrev' => '{{REPL:revA2}}',
					'torev' => '{{REPL:revA3}}',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA2}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest A',
						'toid' => '{{REPL:pageA}}',
						'torevid' => '{{REPL:revA3}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest A',
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div>A <del class="diffchange diffchange-inline">2</del></div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div>A <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deleted revision ID as sysop' => [
				[
					'fromrev' => '{{REPL:revA2}}',
					'torev' => '{{REPL:revC2}}',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA2}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest A',
						'toid' => 0,
						'torevid' => '{{REPL:revC2}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest C',
						'toarchive' => true,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">A </del>2</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">C </ins>2</div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Basic diff, revdel as sysop' => [
				[
					'fromrev' => '{{REPL:revA2}}',
					'torev' => '{{REPL:revB2}}',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA2}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest A',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB2}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'totexthidden' => true,
						'touserhidden' => true,
						'tocommenthidden' => true,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">A </del>2</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">B </ins>2</div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Basic diff, text' => [
				[
					'fromtext' => 'From text',
					'fromcontentmodel' => 'wikitext',
					'totext' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel' => 'wikitext',
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">{{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text 2' => [
				[
					'fromtext' => 'From text',
					'totext' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel' => 'wikitext',
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">{{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, guessed model' => [
				[
					'fromtext' => 'From text',
					'totext' => 'To text',
				],
				[
					'warnings' => [
						'compare' => [
							'warnings' => 'No content model could be determined, assuming wikitext.',
						],
					],
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text</div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with title and PST' => [
				[
					'fromtext' => 'From text',
					'totitle' => 'Test',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">Test</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with page ID and PST' => [
				[
					'fromtext' => 'From text',
					'toid' => '{{REPL:pageB}}',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest B</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with revision and PST' => [
				[
					'fromtext' => 'From text',
					'torev' => '{{REPL:revB2}}',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest B</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with deleted revision and PST' => [
				[
					'fromtext' => 'From text',
					'torev' => '{{REPL:revC2}}',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest C</ins></div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Basic diff, test with sections' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'fromsection' => 1,
					'totext' => "== Section 1 ==\nTo text\n\n== Section 2 ==\nTo text?",
					'tosection' => 2,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div>== Section <del class="diffchange diffchange-inline">1 </del>==</div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div>== Section <ins class="diffchange diffchange-inline">2 </ins>==</div></td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div><del class="diffchange diffchange-inline">F 1.1</del></div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div><ins class="diffchange diffchange-inline">To text?</ins></div></td></tr>' . "\n",
						'fromid' => '{{REPL:pageF}}',
						'fromrevid' => '{{REPL:revF1}}',
						'fromns' => '0',
						'fromtitle' => 'ApiComparePagesTest F',
					]
				],
			],
			'Diff with all props' => [
				[
					'fromrev' => '{{REPL:revB1}}',
					'torev' => '{{REPL:revB3}}',
					'totitle' => 'ApiComparePagesTest B',
					'prop' => 'diff|diffsize|rel|ids|title|user|comment|parsedcomment|size'
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageB}}',
						'fromrevid' => '{{REPL:revB1}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest B',
						'fromsize' => 3,
						'fromuser' => '{{REPL:creator}}',
						'fromuserid' => '{{REPL:creatorid}}',
						'fromcomment' => 'Test for ApiComparePagesTest: B 1',
						'fromparsedcomment' => 'Test for ApiComparePagesTest: B 1',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB3}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'tosize' => 3,
						'touserhidden' => true,
						'tocommenthidden' => true,
						'tosuppressed' => true,
						'next' => '{{REPL:revB4}}',
						'diffsize' => 391,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div>B <del class="diffchange diffchange-inline">1</del></div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div>B <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Diff with all props as sysop' => [
				[
					'fromrev' => '{{REPL:revB2}}',
					'torev' => '{{REPL:revB3}}',
					'totitle' => 'ApiComparePagesTest B',
					'prop' => 'diff|diffsize|rel|ids|title|user|comment|parsedcomment|size'
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageB}}',
						'fromrevid' => '{{REPL:revB2}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest B',
						'fromsize' => 3,
						'fromtexthidden' => true,
						'fromuserhidden' => true,
						'fromuser' => '{{REPL:creator}}',
						'fromuserid' => '{{REPL:creatorid}}',
						'fromcommenthidden' => true,
						'fromcomment' => 'Test for ApiComparePagesTest: B 2',
						'fromparsedcomment' => 'Test for ApiComparePagesTest: B 2',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB3}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'tosize' => 3,
						'touserhidden' => true,
						'tocommenthidden' => true,
						'tosuppressed' => true,
						'prev' => '{{REPL:revB1}}',
						'next' => '{{REPL:revB4}}',
						'diffsize' => 391,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1" >Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class=\'diff-marker\'>−</td><td class=\'diff-deletedline\'><div>B <del class="diffchange diffchange-inline">2</del></div></td><td class=\'diff-marker\'>+</td><td class=\'diff-addedline\'><div>B <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Relative diff, cur' => [
				[
					'fromrev' => '{{REPL:revA2}}',
					'torelative' => 'cur',
					'prop' => 'ids',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA2}}',
						'toid' => '{{REPL:pageA}}',
						'torevid' => '{{REPL:revA4}}',
					]
				],
			],
			'Relative diff, next' => [
				[
					'fromrev' => '{{REPL:revE2}}',
					'torelative' => 'next',
					'prop' => 'ids|rel',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageE}}',
						'fromrevid' => '{{REPL:revE2}}',
						'toid' => '{{REPL:pageE}}',
						'torevid' => '{{REPL:revE3}}',
						'prev' => '{{REPL:revE1}}',
						'next' => '{{REPL:revE4}}',
					]
				],
			],
			'Relative diff, prev' => [
				[
					'fromrev' => '{{REPL:revE3}}',
					'torelative' => 'prev',
					'prop' => 'ids|rel',
				],
				[
					'compare' => [
						'fromid' => '{{REPL:pageE}}',
						'fromrevid' => '{{REPL:revE2}}',
						'toid' => '{{REPL:pageE}}',
						'torevid' => '{{REPL:revE3}}',
						'prev' => '{{REPL:revE1}}',
						'next' => '{{REPL:revE4}}',
					]
				],
			],

			'Error, missing title' => [
				[
					'fromtitle' => 'ApiComparePagesTest X',
					'totitle' => 'ApiComparePagesTest B',
				],
				[],
				'missingtitle',
			],
			'Error, invalid title' => [
				[
					'fromtitle' => '<bad>',
					'totitle' => 'ApiComparePagesTest B',
				],
				[],
				'invalidtitle',
			],
			'Error, missing page ID' => [
				[
					'fromid' => 8817900,
					'totitle' => 'ApiComparePagesTest B',
				],
				[],
				'nosuchpageid',
			],
			'Error, page with missing revision' => [
				[
					'fromtitle' => 'ApiComparePagesTest D',
					'totitle' => 'ApiComparePagesTest B',
				],
				[],
				'nosuchrevid',
			],
			'Error, page with no revision' => [
				[
					'fromtitle' => 'ApiComparePagesTest E',
					'totitle' => 'ApiComparePagesTest B',
				],
				[],
				'nosuchrevid',
			],
			'Error, bad rev ID' => [
				[
					'fromrev' => 8817900,
					'totitle' => 'ApiComparePagesTest B',
				],
				[],
				'nosuchrevid',
			],
			'Error, deleted revision ID, non-sysop' => [
				[
					'fromrev' => '{{REPL:revA2}}',
					'torev' => '{{REPL:revC2}}',
				],
				[],
				'nosuchrevid',
			],
			'Error, revision-deleted content' => [
				[
					'fromrev' => '{{REPL:revA2}}',
					'torev' => '{{REPL:revB2}}',
				],
				[],
				'missingcontent',
			],
			'Error, text with no title and PST' => [
				[
					'fromtext' => 'From text',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[],
				'compare-no-title',
			],
			'Error, test with invalid from section ID' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'fromsection' => 5,
					'totext' => "== Section 1 ==\nTo text\n\n== Section 2 ==\nTo text?",
					'tosection' => 2,
				],
				[],
				'nosuchfromsection',
			],
			'Error, test with invalid to section ID' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'fromsection' => 1,
					'totext' => "== Section 1 ==\nTo text\n\n== Section 2 ==\nTo text?",
					'tosection' => 5,
				],
				[],
				'nosuchtosection',
			],
			'Error, Relative diff, no from revision' => [
				[
					'fromtext' => 'Foo',
					'torelative' => 'cur',
					'prop' => 'ids',
				],
				[],
				'compare-relative-to-nothing'
			],
			'Error, Relative diff, cur with no current revision' => [
				[
					'fromrev' => '{{REPL:revE2}}',
					'torelative' => 'cur',
					'prop' => 'ids',
				],
				[],
				'nosuchrevid'
			],
			'Error, Relative diff, next revdeleted' => [
				[
					'fromrev' => '{{REPL:revB1}}',
					'torelative' => 'next',
					'prop' => 'ids',
				],
				[],
				'missingcontent'
			],
			'Error, Relative diff, prev revdeleted' => [
				[
					'fromrev' => '{{REPL:revB3}}',
					'torelative' => 'prev',
					'prop' => 'ids',
				],
				[],
				'missingcontent'
			],
		];
		// phpcs:enable
	}
}
