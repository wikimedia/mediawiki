<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiUsageException;
use MediaWiki\Context\RequestContext;
use MediaWiki\MainConfigNames;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Tests\User\TempUser\TempUserTestTrait;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use RevisionDeleter;

/**
 * @group API
 * @group Database
 * @group medium
 * @covers \MediaWiki\Api\ApiComparePages
 */
class ApiComparePagesTest extends ApiTestCase {

	use TempUserTestTrait;

	/** @var array */
	protected static $repl = [];

	protected function addPage( $page, $text, $model = CONTENT_MODEL_WIKITEXT ) {
		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromLinkTarget( new TitleValue( NS_MAIN, 'ApiComparePagesTest ' . $page ) );
		$content = $this->getServiceContainer()->getContentHandlerFactory()
			->getContentHandler( $model )
			->unserializeContent( $text );
		$performer = static::getTestSysop()->getAuthority();
		$status = $this->editPage(
			$page,
			$content,
			'Test for ApiComparePagesTest: ' . $text,
			NS_MAIN,
			$performer
		);
		if ( !$status->isOK() ) {
			$this->fail( 'Failed to create ' . $page->getTitle()->getPrefixedText() . ': ' . $status->getWikiText( false, false, 'en' ) );
		}
		return $status->getNewRevision()->getId();
	}

	public function addDBDataOnce() {
		$this->disableAutoCreateTempUser();
		$user = static::getTestSysop()->getUser();
		self::$repl['creator'] = $user->getName();
		self::$repl['creatorid'] = $user->getId();

		self::$repl['revA1'] = $this->addPage( 'A', 'A 1' );
		self::$repl['revA2'] = $this->addPage( 'A', 'A 2' );
		self::$repl['revA3'] = $this->addPage( 'A', 'A 3' );
		self::$repl['revA4'] = $this->addPage( 'A', 'A 4' );
		self::$repl['pageA'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest A' )->getArticleID();

		self::$repl['revB1'] = $this->addPage( 'B', 'B 1' );
		self::$repl['revB2'] = $this->addPage( 'B', 'B 2' );
		self::$repl['revB3'] = $this->addPage( 'B', 'B 3' );
		self::$repl['revB4'] = $this->addPage( 'B', 'B 4' );
		self::$repl['pageB'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest B' )->getArticleID();
		$updateTimestamps = [
			self::$repl['revB1'] => '20010101011101',
			self::$repl['revB2'] => '20020202022202',
			self::$repl['revB3'] => '20030303033303',
			self::$repl['revB4'] => '20040404044404',
		];
		foreach ( $updateTimestamps as $id => $ts ) {
			$this->getDb()->newUpdateQueryBuilder()
				->update( 'revision' )
				->set( [ 'rev_timestamp' => $this->getDb()->timestamp( $ts ) ] )
				->where( [ 'rev_id' => $id ] )
				->caller( __METHOD__ )
				->execute();
		}

		self::$repl['revC1'] = $this->addPage( 'C', 'C 1' );
		self::$repl['revC2'] = $this->addPage( 'C', 'C 2' );
		self::$repl['revC3'] = $this->addPage( 'C', 'C 3' );
		self::$repl['pageC'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest C' )->getArticleID();

		$id = $this->addPage( 'D', 'D 1' );
		self::$repl['pageD'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest D' )->getArticleID();
		$this->getDb()->newDeleteQueryBuilder()
			->deleteFrom( 'revision' )
			->where( [ 'rev_id' => $id ] )
			->caller( __METHOD__ )
			->execute();

		self::$repl['revE1'] = $this->addPage( 'E', 'E 1' );
		self::$repl['revE2'] = $this->addPage( 'E', 'E 2' );
		self::$repl['revE3'] = $this->addPage( 'E', 'E 3' );
		self::$repl['revE4'] = $this->addPage( 'E', 'E 4' );
		self::$repl['pageE'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest E' )->getArticleID();
		$this->getDb()->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [ 'page_latest' => 0 ] )
			->where( [ 'page_id' => self::$repl['pageE'] ] )
			->caller( __METHOD__ )
			->execute();

		self::$repl['revF1'] = $this->addPage( 'F', "== Section 1 ==\nF 1.1\n\n== Section 2 ==\nF 1.2" );
		self::$repl['pageF'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest F' )->getArticleID();

		self::$repl['revG1'] = $this->addPage( 'G', "== Section 1 ==\nG 1.1", CONTENT_MODEL_TEXT );
		self::$repl['pageG'] = Title::makeTitle( NS_MAIN, 'ApiComparePagesTest G' )->getArticleID();

		$page = $this->getServiceContainer()->getWikiPageFactory()
			->newFromTitle( Title::makeTitle( NS_MAIN, 'ApiComparePagesTest C' ) );
		$this->deletePage( $page, 'Test for ApiComparePagesTest', $user );

		RevisionDeleter::createList(
			'revision',
			RequestContext::getMain(),
			Title::makeTitle( NS_MAIN, 'ApiComparePagesTest B' ),
			[ self::$repl['revB2'] ]
		)->setVisibility( [
			'value' => [
				RevisionRecord::DELETED_TEXT => 1,
				RevisionRecord::DELETED_USER => 1,
				RevisionRecord::DELETED_COMMENT => 1,
			],
			'comment' => 'Test for ApiComparePages',
		] );

		RevisionDeleter::createList(
			'revision',
			RequestContext::getMain(),
			Title::makeTitle( NS_MAIN, 'ApiComparePagesTest B' ),
			[ self::$repl['revB3'] ]
		)->setVisibility( [
			'value' => [
				RevisionRecord::DELETED_USER => 1,
				RevisionRecord::DELETED_COMMENT => 1,
				RevisionRecord::DELETED_RESTRICTED => 1,
			],
			'comment' => 'Test for ApiComparePages',
		] );
	}

	protected function doReplacements( &$value ) {
		if ( is_string( $value ) ) {
			if ( preg_match( '/^{{REPL:(.+?)}}$/', $value, $m ) ) {
				$value = self::$repl[$m[1]];
			} else {
				$value = preg_replace_callback( '/{{REPL:(.+?)}}/', static function ( $m ) {
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
		$this->overrideConfigValue( MainConfigNames::DiffEngine, 'php' );

		$this->doReplacements( $params );

		$params += [
			'action' => 'compare',
			'errorformat' => 'none',
		];

		$performer = $sysop
			? static::getTestSysop()->getAuthority()
			: static::getTestUser()->getAuthority();
		if ( $exceptionCode ) {
			try {
				$this->doApiRequest( $params, null, false, $performer );
				$this->fail( 'Expected exception not thrown' );
			} catch ( ApiUsageException $ex ) {
				$this->assertApiErrorCode( $exceptionCode, $ex,
					"Exception with code $exceptionCode" );
			}
		} else {
			$apiResult = $this->doApiRequest( $params, null, false, $performer );
			$apiResult = $apiResult[0];
			$this->doReplacements( $expect );
			$this->assertEquals( $expect, $apiResult );
		}
	}

	private static function makeDeprecationWarnings( ...$params ) {
		$warn = [];
		foreach ( $params as $p ) {
			$warn[] = [
				'code' => 'deprecation',
				'data' => [ 'feature' => "action=compare&{$p}" ],
				'module' => 'compare',
			];
			if ( count( $warn ) === 1 ) {
				$warn[] = [
					'code' => 'deprecation-help',
					'module' => 'main',
				];
			}
		}

		return $warn;
	}

	public static function provideDiff() {
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
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">A </del>4</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">B </ins>4</div></td></tr>' . "\n",
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
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">A </del>4</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">B </ins>4</div></td></tr>' . "\n",
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
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div>A <del class="diffchange diffchange-inline">2</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div>A <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
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
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">A </del>2</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">C </ins>2</div></td></tr>' . "\n",
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
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">A </del>2</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">B </ins>2</div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Basic diff, text' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'fromcontentmodel-main' => 'wikitext',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel-main' => 'wikitext',
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">{{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text 2' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel-main' => 'wikitext',
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">{{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, guessed model' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'toslots' => 'main',
					'totext-main' => 'To text',
				],
				[
					'warnings' => [ [ 'code' => 'compare-nocontentmodel', 'module' => 'compare' ] ],
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text</div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with title and PST' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'totitle' => 'Test',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">Test</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with page ID and PST' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'toid' => '{{REPL:pageB}}',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest B</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with revision and PST' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'torev' => '{{REPL:revB2}}',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest B</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, text with deleted revision and PST' => [
				[
					'fromslots' => 'main',
					'fromtext-main' => 'From text',
					'torev' => '{{REPL:revC2}}',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest C</ins></div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Basic diff, test with sections' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'fromslots' => 'main',
					'fromtext-main' => "== Section 2 ==\nFrom text?",
					'fromsection-main' => 2,
					'totitle' => 'ApiComparePagesTest F',
					'toslots' => 'main',
					'totext-main' => "== Section 1 ==\nTo text?",
					'tosection-main' => 1,
				],
				[
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker"></td><td class="diff-context diff-side-deleted"><div>== Section 1 ==</div></td><td class="diff-marker"></td><td class="diff-context diff-side-added"><div>== Section 1 ==</div></td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">F 1.1</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To text?</ins></div></td></tr>' . "\n"
							. '<tr><td class="diff-marker"></td><td class="diff-context diff-side-deleted"><br></td><td class="diff-marker"></td><td class="diff-context diff-side-added"><br></td></tr>' . "\n"
							. '<tr><td class="diff-marker"></td><td class="diff-context diff-side-deleted"><div>== Section 2 ==</div></td><td class="diff-marker"></td><td class="diff-context diff-side-added"><div>== Section 2 ==</div></td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From text?</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">F 1.2</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Diff with all props' => [
				[
					'fromrev' => '{{REPL:revB1}}',
					'torev' => '{{REPL:revB3}}',
					'totitle' => 'ApiComparePagesTest B',
					'prop' => 'diff|diffsize|rel|ids|title|user|comment|parsedcomment|size|timestamp'
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
						'fromtimestamp' => '2001-01-01T01:11:01Z',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB3}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'tosize' => 3,
						'touserhidden' => true,
						'tocommenthidden' => true,
						'tosuppressed' => true,
						'totimestamp' => '2003-03-03T03:33:03Z',
						'next' => '{{REPL:revB4}}',
						'diffsize' => 454,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div>B <del class="diffchange diffchange-inline">1</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div>B <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Diff with all props as sysop' => [
				[
					'fromrev' => '{{REPL:revB2}}',
					'torev' => '{{REPL:revB3}}',
					'totitle' => 'ApiComparePagesTest B',
					'prop' => 'diff|diffsize|rel|ids|title|user|comment|parsedcomment|size|timestamp'
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
						'fromtimestamp' => '2002-02-02T02:22:02Z',
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB3}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
						'tosize' => 3,
						'touserhidden' => true,
						'tocommenthidden' => true,
						'tosuppressed' => true,
						'totimestamp' => '2003-03-03T03:33:03Z',
						'prev' => '{{REPL:revB1}}',
						'next' => '{{REPL:revB4}}',
						'diffsize' => 454,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div>B <del class="diffchange diffchange-inline">2</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div>B <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Text diff with all props' => [
				[
					'fromrev' => '{{REPL:revB1}}',
					'toslots' => 'main',
					'totext-main' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel-main' => 'wikitext',
					'prop' => 'diff|diffsize|rel|ids|title|user|comment|parsedcomment|size|timestamp'
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
						'fromtimestamp' => '2001-01-01T01:11:01Z',
						'diffsize' => 477,
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">B 1</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To text {{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
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
			'Relative diff, no prev' => [
				[
					'fromrev' => '{{REPL:revA1}}',
					'torelative' => 'prev',
					'prop' => 'ids|rel|diff|title|user|comment',
				],
				[
					'warnings' => [
						[
							'code' => 'compare-no-prev',
							'module' => 'compare',
						],
					],
					'compare' => [
						'toid' => '{{REPL:pageA}}',
						'torevid' => '{{REPL:revA1}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest A',
						'touser' => '{{REPL:creator}}',
						'touserid' => '{{REPL:creatorid}}',
						'tocomment' => 'Test for ApiComparePagesTest: A 1',
						'toparsedcomment' => 'Test for ApiComparePagesTest: A 1',
						'next' => '{{REPL:revA2}}',
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div> </div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">A 1</ins></div></td></tr>' . "\n",
					],
				],
			],
			'Relative diff, no next' => [
				[
					'fromrev' => '{{REPL:revA4}}',
					'torelative' => 'next',
					'prop' => 'ids|rel|diff|title|user|comment',
				],
				[
					'warnings' => [
						[
							'code' => 'compare-no-next',
							'module' => 'compare',
						],
					],
					'compare' => [
						'fromid' => '{{REPL:pageA}}',
						'fromrevid' => '{{REPL:revA4}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest A',
						'fromuser' => '{{REPL:creator}}',
						'fromuserid' => '{{REPL:creatorid}}',
						'fromcomment' => 'Test for ApiComparePagesTest: A 4',
						'fromparsedcomment' => 'Test for ApiComparePagesTest: A 4',
						'prev' => '{{REPL:revA3}}',
						'body' => '',
					],
				],
			],
			'Diff for specific slots' => [
				// @todo Use a page with multiple slots here
				[
					'fromrev' => '{{REPL:revA1}}',
					'torev' => '{{REPL:revA3}}',
					'prop' => 'diff',
					'slots' => 'main',
				],
				[
					'compare' => [
						'bodies' => [
							'main' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
								. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
								. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div>A <del class="diffchange diffchange-inline">1</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div>A <ins class="diffchange diffchange-inline">3</ins></div></td></tr>' . "\n",
						],
					],
				],
			],
			// @todo Add a test for diffing with a deleted slot. Deleting 'main' doesn't work.

			'Basic diff, deprecated text' => [
				[
					'fromtext' => 'From text',
					'fromcontentmodel' => 'wikitext',
					'totext' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel' => 'wikitext',
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromtext', 'fromcontentmodel', 'totext', 'tocontentmodel' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">{{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deprecated text 2' => [
				[
					'fromtext' => 'From text',
					'totext' => 'To text {{subst:PAGENAME}}',
					'tocontentmodel' => 'wikitext',
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromtext', 'totext', 'tocontentmodel' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">{{subst:PAGENAME}}</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deprecated text, guessed model' => [
				[
					'fromtext' => 'From text',
					'totext' => 'To text',
				],
				[
					'warnings' => array_merge( self::makeDeprecationWarnings( 'fromtext', 'totext' ), [
						[ 'code' => 'compare-nocontentmodel', 'module' => 'compare' ],
					] ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text</div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deprecated text with title and PST' => [
				[
					'fromtext' => 'From text',
					'totitle' => 'Test',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromtext', 'totext' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">Test</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deprecated text with page ID and PST' => [
				[
					'fromtext' => 'From text',
					'toid' => '{{REPL:pageB}}',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromtext', 'totext' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest B</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deprecated text with revision and PST' => [
				[
					'fromtext' => 'From text',
					'torev' => '{{REPL:revB2}}',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromtext', 'totext' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest B</ins></div></td></tr>' . "\n",
					]
				],
			],
			'Basic diff, deprecated text with deleted revision and PST' => [
				[
					'fromtext' => 'From text',
					'torev' => '{{REPL:revC2}}',
					'totext' => 'To text {{subst:PAGENAME}}',
					'topst' => true,
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromtext', 'totext' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">From </del>text</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To </ins>text <ins class="diffchange diffchange-inline">ApiComparePagesTest C</ins></div></td></tr>' . "\n",
					]
				],
				false, true
			],
			'Basic diff, test with deprecated sections' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'fromsection' => 1,
					'totext' => "== Section 1 ==\nTo text\n\n== Section 2 ==\nTo text?",
					'tosection' => 2,
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromsection', 'totext', 'tosection' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div>== Section <del class="diffchange diffchange-inline">1 </del>==</div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div>== Section <ins class="diffchange diffchange-inline">2 </ins>==</div></td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div><del class="diffchange diffchange-inline">F 1.1</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div><ins class="diffchange diffchange-inline">To text?</ins></div></td></tr>' . "\n",
						'fromid' => '{{REPL:pageF}}',
						'fromrevid' => '{{REPL:revF1}}',
						'fromns' => '0',
						'fromtitle' => 'ApiComparePagesTest F',
					]
				],
			],
			'Basic diff, test with deprecated sections and revdel, non-sysop' => [
				[
					'fromrev' => '{{REPL:revB2}}',
					'fromsection' => 0,
					'torev' => '{{REPL:revB4}}',
					'tosection' => 0,
				],
				[],
				'missingcontent'
			],
			'Basic diff, test with deprecated sections and revdel, sysop' => [
				[
					'fromrev' => '{{REPL:revB2}}',
					'fromsection' => 0,
					'torev' => '{{REPL:revB4}}',
					'tosection' => 0,
				],
				[
					'warnings' => self::makeDeprecationWarnings( 'fromsection', 'tosection' ),
					'compare' => [
						'body' => '<tr><td colspan="2" class="diff-lineno" id="mw-diff-left-l1">Line 1:</td>' . "\n"
							. '<td colspan="2" class="diff-lineno">Line 1:</td></tr>' . "\n"
							. '<tr><td class="diff-marker" data-marker="−"></td><td class="diff-deletedline diff-side-deleted"><div>B <del class="diffchange diffchange-inline">2</del></div></td><td class="diff-marker" data-marker="+"></td><td class="diff-addedline diff-side-added"><div>B <ins class="diffchange diffchange-inline">4</ins></div></td></tr>' . "\n",
						'fromid' => '{{REPL:pageB}}',
						'fromrevid' => '{{REPL:revB2}}',
						'fromns' => 0,
						'fromtitle' => 'ApiComparePagesTest B',
						'fromtexthidden' => true,
						'fromuserhidden' => true,
						'fromcommenthidden' => true,
						'toid' => '{{REPL:pageB}}',
						'torevid' => '{{REPL:revB4}}',
						'tons' => 0,
						'totitle' => 'ApiComparePagesTest B',
					]
				],
				false, true,
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
			'Error, deleted revision ID and torelative=prev' => [
				[
					'fromrev' => '{{REPL:revC2}}',
					'torelative' => 'prev',
				],
				[],
				'compare-relative-to-deleted', true
			],
			'Error, deleted revision ID and torelative=next' => [
				[
					'fromrev' => '{{REPL:revC2}}',
					'torelative' => 'next',
				],
				[],
				'compare-relative-to-deleted', true
			],
			'Deleted revision ID and torelative=cur' => [
				[
					'fromrev' => '{{REPL:revC2}}',
					'torelative' => 'cur',
				],
				[],
				'nosuchrevid', true
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
			'Error, section diff with no revision' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'toslots' => 'main',
					'totext-main' => "== Section 1 ==\nTo text?",
					'tosection-main' => 1,
				],
				[],
				'compare-notorevision',
			],
			'Error, section diff with revdeleted revision' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'torev' => '{{REPL:revB2}}',
					'toslots' => 'main',
					'totext-main' => "== Section 1 ==\nTo text?",
					'tosection-main' => 1,
				],
				[],
				'missingcontent',
			],
			'Error, section diff with a content model not supporting sections' => [
				[
					'fromtitle' => 'ApiComparePagesTest G',
					'torev' => '{{REPL:revG1}}',
					'toslots' => 'main',
					'totext-main' => "== Section 1 ==\nTo text?",
					'tosection-main' => 1,
				],
				[],
				'sectionsnotsupported',
			],
			'Error, section diff with bad content model' => [
				[
					'fromtitle' => 'ApiComparePagesTest F',
					'torev' => '{{REPL:revF1}}',
					'toslots' => 'main',
					'totext-main' => "== Section 1 ==\nTo text?",
					'tosection-main' => 1,
					'tocontentmodel-main' => CONTENT_MODEL_TEXT,
				],
				[],
				'sectionreplacefailed',
			],
			'Error, deleting the main slot' => [
				[
					'fromtitle' => 'ApiComparePagesTest A',
					'totitle' => 'ApiComparePagesTest A',
					'toslots' => 'main',
				],
				[],
				'compare-maintextrequired',
			],
			// @todo Add a test for using 'tosection-foo' without 'totext-foo' (can't do it with main)
		];
		// phpcs:enable
	}
}
