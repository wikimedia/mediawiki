<?php
use MediaWiki\Tests\Maintenance\MaintenanceBaseTestCase;

/**
 * @covers \TitleCleanup
 * @group Database
 */
class CleanupTitlesTest extends MaintenanceBaseTestCase {

	public function addDBDataOnce() {
		// Add some existing pages to test normalization clashes
		$this->insertPage( 'User talk:195.175.37.8' );
		$this->insertPage( 'User talk:195.175.37.10' );
		$this->insertPage( 'Project talk:Existing' );

		// Create an interwiki link to test titles with interwiki prefixes
		$this->getDb()->newInsertQueryBuilder()
			->insertInto( 'interwiki' )
			->ignore()
			->row( [
				'iw_prefix' => 'custom',
				'iw_url' => 'https://local.example/w/index.php?title=$1',
				// Specify all fields to avoid errors on Postgres
				'iw_api' => '',
				'iw_wikiid' => '',
				'iw_local' => 0,
				'iw_trans' => 0
			] )
		->caller( __METHOD__ )
		->execute();
	}

	protected function getMaintenanceClass() {
		return TitleCleanup::class;
	}

	public static function provideInvalidTitles() {
		# Valid title
		yield [ 0, 'Foo', 0, 'Foo', null ];
		# Projectspace title encoded as mainspace
		yield [ 0, 'Project:Foo', 4, 'Foo', null ];
		# Interwiki title encoded as mainspace
		yield [ 0, 'custom:Foo', 0, 'Broken/custom:Foo', null ];
		# Unknown namespace
		yield [ 9999, 'Foo', 0, 'Broken/NS9999:Foo', null ];
		# Illegal characters
		yield [ 0, '<', 0, 'Broken/\x3c', null ];
		# Illegal characters and unknown namespace
		yield [ 9999, '<', 0, 'Broken/NS9999:\x3c', null ];
		# IP normalization that clashes (this IP is added above)
		yield [ 3, '195.175.037.8', 0, 'Broken/User_talk:195.175.37.8', null ];
		# IP normalization (valid)
		yield [ 3, '195.175.037.9', 3, '195.175.37.9', null ];
		# Page in main namespace whose title needs additional normalization after resolving
		# the namespace prefix, and then clashes with another title when fully normalized
		yield [ 0, 'User talk:195.175.037.10', 0, 'Broken/User_talk:195.175.37.10', null ];
		# Non-ascii characters (and otherwise invalid)
		# The point of this is to make sure it escapes the invalid < character without also
		# escaping the non-ASCII characters in the other parts of the title
		yield [ 0, '<Википедия', 0, 'Broken/\x3cВикипедия', null ];
		# Non-ascii charaters (and otherwise invalid in a way that removing characters not in Title::legalChars()
		# doesn't cure)
		# This output is unideal, and just a failsafe to avoid "Broken/id:" titles
		yield [ 1, '%25Википедия', 0, 'Broken/Talk:\x2525\xd0\x92\xd0\xb8\xd0\xba\xd0\xb8\xd0\xbf\xd0\xb5\xd0\xb4\xd0\xb8\xd1\x8f', null ];
		# Special namespace
		yield [ 0, "Special:Foo", 0, "Broken/Special:Foo", null ];
		# Media namespace
		yield [ 0, "Media:Foo", 0, "Broken/Media:Foo", null ];
		# With prefix
		yield [ 0, '<', 0, 'Prefix/\x3c', 'Prefix' ];
		# Incorrectly encoded talk page of namespace
		yield [ 1, 'Project:Foo', 5, 'Foo', null ];
		# Of special page
		yield [ 1, 'Special:Foo', 0, 'Broken/Talk:Special:Foo', null ];
		# Of interwiki
		yield [ 1, 'custom:Foo', 0, 'Broken/Talk:custom:Foo', null ];
		# Of page that already exists
		yield [ 1, 'Project:Existing', 0, 'Broken/Talk:Project:Existing', null ];
	}

	/**
	 * @dataProvider provideInvalidTitles
	 */
	public function testCleanup( $namespace, $title, $expectedNamespace, $expectedTitle, $prefix ) {
		$pageId = $this->insertPage( 'Mangleme' )['id'];
		$dbw = $this->getDB();
		$dbw->newUpdateQueryBuilder()
			->update( 'page' )
			->set( [
					'page_namespace' => $namespace,
					'page_title' => $title,
			] )
			->where( [
				'page_id' => $pageId
			] )
			->caller( __METHOD__ )
			->execute();
		if ( $prefix ) {
			$this->maintenance->loadWithArgv( [ "--prefix", $prefix ] );
		}
		$this->maintenance->execute();
		$newRow = $dbw->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->where( [
				'page_id' => $pageId
			] )->fetchRow();
		$this->assertEquals( $expectedNamespace, $newRow->page_namespace );
		$this->assertEquals( $expectedTitle, $newRow->page_title );
	}

	public function testBrokenIdFailsafe() {
		$this->insertPage( 'Talk:ABC' );
		$this->insertPage( 'Broken/Talk:ABC' );
		$pageId = $this->insertPage( 'Mangleme' )['id'];
		$dbw = $this->getDB();
		$dbw->newUpdateQueryBuilder()
			->update( "page" )
			->set( [
					'page_namespace' => 0,
					'page_title' => 'Talk:ABC',
			] )
			->where( [
				'page_id' => $pageId
			] )
			->caller( __METHOD__ )
			->execute();
		$this->maintenance->execute();
		$newRow = $dbw->newSelectQueryBuilder()
			->select( [ 'page_namespace', 'page_title' ] )
			->from( 'page' )
			->where( [
				'page_id' => $pageId
			] )->fetchRow();
		$this->assertSame( 0, (int)$newRow->page_namespace );
		$this->assertEquals( 'Broken/id:_' . $pageId, $newRow->page_title );
	}

}
