<?php

/**
 * @group API
 * @group Database
 */
class ApiQueryRevisionsTest extends MediaWikiLangTestCase {

	private $mTested, $mDatabaseMock, $mApiBaseMock, $mPageSetMock;

	function setUp() {
		global $wgContLang, $wgAuth, $wgMemc, $wgRequest, $wgUser, $wgAPIMaxUncachedDiffs;
		parent::setUp();

		$wgMemc = new EmptyBagOStuff();
		$wgContLang = Language::factory( 'en' );
		$wgAuth = new StubObject( 'wgAuth', 'AuthPlugin' );
		$wgRequest = new FauxRequest( array() );
		$wgAPIMaxUncachedDiffs = 100000000; // Workaround for ugly static counters

		$this->mPageSetMock = new ApiPageSetMock();
		$this->mDatabaseMock = new DatabaseMock();
		$this->mApiBaseMock = new ApiBaseMock( $this->mDatabaseMock, $this->mPageSetMock );

		$this->mTested = new ApiQueryRevisionsTestable( $this->mApiBaseMock, 'revisions' );
		$this->mTested->profileIn();
	}

	private function getSampleRevision( $id, $deleted = 0 ) {
		$row = new RevisionMock();
		$row->rev_id = $id;
		$row->rev_page = 1;
		$row->rev_text_id = $id;
		$row->rev_timestamp = 20120406155647 + $id;
		$row->rev_comment = 'Test summary ' . $id;
		$row->rev_user_text = 'Beau';
		$row->rev_user = 1;
		$row->rev_minor_edit = 0;
		$row->rev_deleted = $deleted;
		$row->rev_len = 12;
		$row->rev_parent_id = $id - 1;
		$row->rev_sha1 = 'sha1-' . $id;
		$row->page_namespace = 0;
		$row->page_title = 'Test_page';
		$row->page_id = $row->rev_page;
		$row->page_latest = 1;
		$row->text = 'Test content ' . $id;
		return $row;
	}

	private function getSampleRequest( $custom = array() ) {
		$request = array(
			'action' => 'query',
			'titles' => 'Test_page',
			'prop' => 'revisions',
			'rvlimit' => '10',
		);
		return array_merge( $request, $custom );
	}

	private function getSampleResponse( $custom = array() ) {
		$response = array(
			'query' => array(
				'pages' => array(
					1 => array(
						'revisions' => $custom,
					),
				),
			),
		);
		return $response;
	}

	private function executeQuery( $request, $user = null ) {
		// Empty session
		$session = array();
		// Create a new context
		$context = new DerivativeContext( new RequestContext() );
		$context->setRequest( new FauxRequest( $request, true, $session ) );
		if ( $user !== null ) {
			$context->setUser( $user );
		}
		$this->mTested->setContext( $context );
		$this->mTested->execute();
		return $this->mTested->getResult()->getData();
	}

	/**
	 * This test checks if revision data is properly protected.
	 *
	 * @param testedRequest A request to be sent to the API.
	 * @param testedResponse An expected response from the specified input.
	 * @param deleted A value of the revision deleted flag.
	 * @param userCan A boolean value indicating whether the user can
	 * access the requested data.
	 *
	 * @dataProvider dataFieldVisiblity
	 */
	function testFieldVisiblity( $testedRequest, $testedResponse, $deleted, $userCan ) {
		$request = $this->getSampleRequest( $testedRequest );
		$user = null;

		// Tested revision
		$revision1 = $this->getSampleRevision( 1, $deleted );
		$revision1->setUserPermission( $user, $userCan ? $deleted : 0 );
		$this->mTested->setRevision( $revision1->rev_id, $revision1 );

		// Revision always visible - used for diff tests
		$revision2 = $this->getSampleRevision( 2 );
		$revision2->setUserPermission( $user, 0 );
		$this->mTested->setRevision( $revision2->rev_id, $revision2 );

		// Revision always hidden - used for diff tests
		$revision3 = $this->getSampleRevision( 3, Revision::DELETED_TEXT );
		$revision3->setUserPermission( $user, $userCan ? Revision::DELETED_TEXT : 0 );
		$this->mTested->setRevision( $revision3->rev_id, $revision3 );

		$this->mPageSetMock->setGoodTitles( array( $revision1->getTitle() ) );
		$this->mTested->setSelectResults( array( $revision1 ) );

		// Perform the test
		$response = $this->executeQuery( $request, $user );

		// Check
		$expectedResponse = $this->getSampleResponse( $testedResponse );
		$this->assertEquals( $expectedResponse, $response );
	}

	/**
	 * Generates input data for testFieldVisiblity test.
	 */
	function dataFieldVisiblity() {
		$tests = array();

		$userData['normalRequest'] = array(
			'rvprop' => 'user',
		);
		$userData['unhideRequest'] = array(
			'rvprop' => 'user',
			'rvunhide' => '',
		);
		$userData['visibleResponse'] = array(
			0 => array(
				'user' => 'Beau',
			),
		);
		$userData['hiddenResponse'] = array(
			0 => array(
				'userhidden' => '',
			),
		);
		$userData['unhiddenResponse'] = array(
			0 => array(
				'userhidden' => '',
				'user' => 'Beau',
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $userData, Revision::DELETED_USER ) );

		$userIdData['normalRequest'] = array(
			'rvprop' => 'userid',
		);
		$userIdData['unhideRequest'] = array(
			'rvprop' => 'userid',
			'rvunhide' => '',
		);
		$userIdData['visibleResponse'] = array(
			0 => array(
				'userid' => 1,
			),
		);
		$userIdData['hiddenResponse'] = array(
			0 => array(
				'userhidden' => '',
			),
		);
		$userIdData['unhiddenResponse'] = array(
			0 => array(
				'userhidden' => '',
				'userid' => 1,
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $userIdData, Revision::DELETED_USER ) );

		$commentData['normalRequest'] = array(
			'rvprop' => 'comment',
		);
		$commentData['unhideRequest'] = array(
			'rvprop' => 'comment',
			'rvunhide' => '',
		);
		$commentData['visibleResponse'] = array(
			0 => array(
				'comment' => 'Test summary 1',
			),
		);
		$commentData['hiddenResponse'] = array(
			0 => array(
				'commenthidden' => '',
			),
		);
		$commentData['unhiddenResponse'] = array(
			0 => array(
				'commenthidden' => '',
				'comment' => 'Test summary 1',
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $commentData, Revision::DELETED_COMMENT ) );

		$parsedCommentData['normalRequest'] = array(
			'rvprop' => 'parsedcomment',
		);
		$parsedCommentData['unhideRequest'] = array(
			'rvprop' => 'parsedcomment',
			'rvunhide' => '',
		);
		$parsedCommentData['visibleResponse'] = array(
			0 => array(
				'parsedcomment' => 'Test summary 1',
			),
		);
		$parsedCommentData['hiddenResponse'] = array(
			0 => array(
				'commenthidden' => '',
			),
		);
		$parsedCommentData['unhiddenResponse'] = array(
			0 => array(
				'commenthidden' => '',
				'parsedcomment' => 'Test summary 1',
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $parsedCommentData, Revision::DELETED_COMMENT ) );

		$textData['normalRequest'] = array(
			'rvprop' => 'content',
		);
		$textData['unhideRequest'] = array(
			'rvprop' => 'content',
			'rvunhide' => '',
		);
		$textData['visibleResponse'] = array(
			0 => array(
				'*' => 'Test content 1',
			),
		);
		$textData['hiddenResponse'] = array(
			0 => array(
				'texthidden' => '',
			),
		);
		$textData['unhiddenResponse'] = array(
			0 => array(
				'texthidden' => '',
				'*' => 'Test content 1',
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $textData, Revision::DELETED_TEXT ) );

		$diffToTextData['normalRequest'] = array(
			'rvprop' => '',
			'rvdifftotext' => 'Test content 2',
		);
		$diffToTextData['unhideRequest'] = array(
			'rvprop' => '',
			'rvdifftotext' => 'Test content 2',
			'rvunhide' => '',
		);
		$diffToTextData['visibleResponse'] = array(
			0 => array(
				'diff' => array(
					'*' => 'DIFF(Test content 1, Test content 2)',
				),
			),
		);
		$diffToTextData['hiddenResponse'] = array(
			0 => array(
				'texthidden' => '',
			),
		);
		$diffToTextData['unhiddenResponse'] = array(
			0 => array(
				'texthidden' => '',
				'diff' => array(
					'*' => 'DIFF(Test content 1, Test content 2)',
				),
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $diffToTextData, Revision::DELETED_TEXT ) );

		// Diff to revision always visible (2)
		$diffTo2Data['normalRequest'] = array(
			'rvprop' => '',
			'rvdiffto' => '2',
		);
		$diffTo2Data['unhideRequest'] = array(
			'rvprop' => '',
			'rvdiffto' => '2',
			'rvunhide' => '',
		);
		$diffTo2Data['visibleResponse'] = array(
			0 => array(
				'diff' => array(
					'*' => 'DIFF(1, 2, 0, false, true)',
					'from' => '1',
					'to' => '2',
				),
			),
		);
		$diffTo2Data['hiddenResponse'] = array(
			0 => array(
				'texthidden' => '',
			),
		);
		$diffTo2Data['unhiddenResponse'] = array(
			0 => array(
				'texthidden' => '',
				'diff' => array(
					'*' => 'DIFF(1, 2, 0, false, true)',
					'from' => '1',
					'to' => '2',
				),
			),
		);
		$tests = array_merge( $tests, $this->dataFieldVisiblityTests( $diffTo2Data, Revision::DELETED_TEXT ) );

		// Diff to revision always hidden (3)
		$diffTo3Data['normalRequest'] = array(
			'rvprop' => '',
			'rvdiffto' => '3',
		);
		$diffTo3Data['unhideRequest'] = array(
			'rvprop' => '',
			'rvdiffto' => '3',
			'rvunhide' => '',
		);
		$diffTo3Data['hiddenResponse'] = array(
			0 => array(
				'diff' => array(
					'from' => '1',
					'to' => '3',
					'totexthidden' => '',
				),
			),
		);
		$diffTo3Data['unhiddenResponse'] = array(
			0 => array(
				'diff' => array(
					'*' => 'DIFF(1, 3, 0, false, true)',
					'from' => '1',
					'to' => '3',
					'totexthidden' => '',
				),
			),
		);
		$tests[] = array(
			$diffTo3Data['normalRequest'],
			$diffTo3Data['hiddenResponse'],
			0, // "From" revision visible
			false, // No permissions to view deleted
		);
		$tests[] = array(
			$diffTo3Data['normalRequest'],
			$diffTo3Data['hiddenResponse'],
			0, // "From" revision visible
			true, // Has permissions to view deleted
		);
		$tests[] = array(
			$diffTo3Data['unhideRequest'],
			$diffTo3Data['hiddenResponse'],
			0, // "From" revision visible
			false, // No permissions to view deleted
		);
		$tests[] = array(
			$diffTo3Data['unhideRequest'],
			$diffTo3Data['unhiddenResponse'],
			0, // "From" revision visible
			true, // Has permissions to view deleted
		);

		return $tests;
	}

	/**
	 * A helper method for dataFieldVisiblity.
	 * @param data An array of sample requests and responses.
	 * @param flag A value of the revision deleted flag.
	 */
	function dataFieldVisiblityTests( $data, $flag ) {
		$tests = array();

		// Test field visibility to everyone
		$tests[] = array(
			$data['normalRequest'],
			$data['visibleResponse'],
			0, // Visible
			false, // No permissions to view deleted
		);
		$tests[] = array(
			$data['normalRequest'],
			$data['hiddenResponse'],
			$flag, // Hidden
			false, // No permissions to view deleted
		);
		// Test field visibility to a privileged user
		$tests[] = array(
			$data['normalRequest'],
			$data['visibleResponse'],
			0, // Visible
			true, // Has permissions to view deleted
		);
		$tests[] = array(
			$data['normalRequest'],
			$data['hiddenResponse'],
			$flag, // Hidden
			true, // Has permissions to view deleted
		);
		// Test field visibility with unhiding to everyone
		$tests[] = array(
			$data['unhideRequest'],
			$data['visibleResponse'],
			0, // Visible
			false, // No permissions to view deleted
		);
		$tests[] = array(
			$data['unhideRequest'],
			$data['hiddenResponse'],
			$flag, // Hidden
			false, // No permissions to view deleted
		);
		// Test field visibility with unhiding to a privileged user
		$tests[] = array(
			$data['unhideRequest'],
			$data['visibleResponse'],
			0, // Visible
			true, // Has permissions to view deleted
		);
		$tests[] = array(
			$data['unhideRequest'],
			$data['unhiddenResponse'],
			$flag, // Hidden
			true, // Has permissions to view deleted
		);

		return $tests;
	}

}

class ApiBaseMock extends ApiBase {

	private $mResult, $mDatabase, $mPageSet;

	public function __construct( $database, $pageSet ) {
		parent::__construct( $this, '', '' );
		$this->mDatabase = $database;
		$this->mPageSet = $pageSet;
		$this->mResult = new ApiResult( $this );
	}

	public function getResult() {
		return $this->mResult;
	}

	public function execute() {
	}

	public function getVersion() {
	}

	public function getDB() {
		return $this->mDatabase;
	}

	public function getPageSet() {
		return $this->mPageSet;
	}

	public function canApiHighLimits() {
		return true;
	}

	public function isInternalMode() {
		return true;
	}

	public function __call( $name, $args ) {
		return 1;
	}
}

class ApiPageSetMock {

	private $mRevisions;
	private $mGoodTitles;

	public function setRevisions( $revisions ) {
		$this->mRevisions = $revisions;
	}

	public function setGoodTitles ( $titles ) {
		$this->mGoodTitles = $titles;
	}

	public function getGoodTitleCount() {
		return count( $this->mGoodTitles );
	}

	public function getGoodTitles() {
		return $this->mGoodTitles;
	}

	public function getRevisionCount() {
		return count( $this->mRevisions );
	}

	public function getRevisionIDs() {
		return $this->mRevisions;
	}

	public function __call( $name, $args ) {
		return 1; // FIXME: this will be removed
	}
}

class DatabaseMock {

	public function __call( $name, $args ) {
		return 1; // FIXME: this will be removed
	}

	function timestampOrNull( $ts = null ) {
		if ( is_null( $ts ) ) {
			return null;
		}
		else {
			return $ts;
		}
	}

	function addQuotes( $s ) {
		if ( $s === null ) {
			return 'NULL';
		}
		else {
			return "'" . $s . "'";
		}
	}
}

class UserMock extends User {

	private $mUserRights;

	public function __construct( $rights = array() ) {
		parent::__construct();

		$this->mUserRights = $rights;
	}

	public function isAllowed( $action = '' ) {
		return array_key_exists( $action, $this->mUserRights );
	}

	public function getId() {
		return 1;
	}

}

class TitleMock extends Title {

	public function userCan( $action, $user = null, $doExpensiveQueries = true ) {
		return true;
	}

	public function getPreviousRevisionID( $revId, $flags = 0 ) {
		return $revId - 1;
	}

	public function getNextRevisionID( $revId, $flags = 0 ) {
		return $revId + 1;
	}

	public function __call( $name, $args ) {
		return false; // FIXME: this will be removed
	}

}

class RevisionMock {

	private $mUserPermissions = array();

	public function setUserPermission( $user, $permissions = 0 ) {
		$id = $user == null ? 0 : $user->getId();
		$this->mUserPermissions[$id] = $permissions;
	}

	public function getTitle() {
		return new TitleMock();
	}

	public function getId() {
		return $this->rev_id;
	}

	public function isDeleted( $field ) {
		return( $this->rev_deleted&$field ) == $field;
	}

	public function userCan( $field, User$user = null ) {
		$id = $user == null ? 0 : $user->getId();
		if ( !array_key_exists( $id, $this->mUserPermissions ) ) {
			return false;
		}
		return( ( $this->mUserPermissions[$id]&$field ) == $field );
	}

	private function checkArgs( $audience, $user ) {
		if ( $audience !== Revision::FOR_THIS_USER ) {
			throw new ErrorException( "Unexpected invocation, expected Revision::FOR_THIS_USER" );
		}
		if ( !$this->userCan( 0, $user ) ) {
			throw new ErrorException( "Unexpected invocation, the user has no permissions" );
		}
	}

	public function getUserText( $audience = Revision::FOR_PUBLIC, User$user = null ) {
		$this->checkArgs( $audience, $user );
		return $this->rev_user_text;
	}

	public function getUser( $audience = Revision::FOR_PUBLIC, User$user = null ) {
		$this->checkArgs( $audience, $user );
		return $this->rev_user;
	}

	public function getComment( $audience = Revision::FOR_PUBLIC, User$user = null ) {
		$this->checkArgs( $audience, $user );
		return $this->rev_comment;
	}

	public function getText( $audience = Revision::FOR_PUBLIC, User$user = null ) {
		$this->checkArgs( $audience, $user );
		return $this->text;
	}

	public function __call( $name, $args ) {
		return false; // FIXME: this will be removed
	}
}

class DifferenceEngineMock {

	private $mResult = 'UNKNOWN';
	private $mOld = null;
	private $mNew = null;
	private $mRcid = null;
	private $mRefreshCache = null;
	private $mUnhide = null;

	function __construct( $context = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false )
	{
		if ( $old || $new || $rcid || $refreshCache || $unhide ) {
			$this->mResult = 'DIFF(' . $old . ', ' . $new . ', ' . $rcid . ', ' . ( $refreshCache ? 'true':'false' ) . ', ' . ( $unhide ? 'true':'false' ) . ')';
		}
		$this->mOld = $old;
		$this->mNew = $new;
		$this->mRcid = $rcid;
		$this->mRefreshCache = $refreshCache;
		$this->mUnhide = $unhide;
	}

	function setText( $oldText, $newText ) {
		$this->mResult = 'DIFF(' . $oldText . ", " . $newText . ")";
	}

	function getDiffBody() {
		return $this->mResult;
	}

	function wasCacheHit() {
		return true; // always
	}

	function getOldid() {
		return $this->mOld;
	}

	function getNewid() {
		return $this->mNew;
	}

	public function __call( $name, $args ) {
		return false; // FIXME: this will be removed
	}

}

class ApiQueryRevisionsTestable extends ApiQueryRevisions {
	private $mSelectResults = array();
	private $mRevisions = array();

	public function setSelectResults( $array = array() ) {
		$this->mSelectResults = $array;
	}

	public function setRevision( $id, $revision ) {
		$this->mRevisions[$id] = $revision;
	}

	protected function select( $method, $extraQuery = array() ) {
		// Return mock objects
		return $this->mSelectResults;
	}

	protected function createRevisionObject( $row ) {
		// Pass anything through
		return $row;
	}

	protected function createRevisionObjectFromId( $id ) {
		// Return prepared objects
		if ( array_key_exists( $id, $this->mRevisions ) ) {
			return $this->mRevisions[$id];
		}
		return null;
	}

	protected function createDifferenceEngine( $context = null, $old = 0,
		$new = 0, $rcid = 0, $refreshCache = false, $unhide = false ) {
		// Return mock object
		return new DifferenceEngineMock( $context, $old, $new, $rcid, $refreshCache, $unhide );
	}
}
