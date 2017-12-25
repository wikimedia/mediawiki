<?php

/**
 * @group JobQueue
 * @group medium
 * @group Database
 */
class RefreshLinksPartitionTest extends MediaWikiTestCase {
	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		parent::__construct( $name, $data, $dataName );

		$this->tablesUsed[] = 'page';
		$this->tablesUsed[] = 'revision';
		$this->tablesUsed[] = 'pagelinks';
	}

	/**
	 * @dataProvider provider_backlinks
	 * @covers BacklinkJobUtils::partitionBacklinkJob
	 */
	public function testRefreshLinks( $ns, $dbKey, $pages ) {
		$title = Title::makeTitle( $ns, $dbKey );

		foreach ( $pages as $page ) {
			list( $bns, $bdbkey ) = $page;
			$bpage = WikiPage::factory( Title::makeTitle( $bns, $bdbkey ) );
			$content = ContentHandler::makeContent( "[[{$title->getPrefixedText()}]]", $bpage->getTitle() );
			$bpage->doEditContent( $content, "test" );
		}

		$title->getBacklinkCache()->clear();
		$this->assertEquals(
			20,
			$title->getBacklinkCache()->getNumLinks( 'pagelinks' ),
			'Correct number of backlinks'
		);

		$job = new RefreshLinksJob( $title, [ 'recursive' => true, 'table' => 'pagelinks' ]
			+ Job::newRootJobParams( "refreshlinks:pagelinks:{$title->getPrefixedText()}" ) );
		$extraParams = $job->getRootJobParams();
		$jobs = BacklinkJobUtils::partitionBacklinkJob( $job, 9, 1, [ 'params' => $extraParams ] );

		$this->assertEquals( 10, count( $jobs ), 'Correct number of sub-jobs' );
		$this->assertEquals( $pages[0], current( $jobs[0]->params['pages'] ),
			'First job is leaf job with proper title' );
		$this->assertEquals( $pages[8], current( $jobs[8]->params['pages'] ),
			'Last leaf job is leaf job with proper title' );
		$this->assertEquals( true, isset( $jobs[9]->params['recursive'] ),
			'Last job is recursive sub-job' );
		$this->assertEquals( true, $jobs[9]->params['recursive'],
			'Last job is recursive sub-job' );
		$this->assertEquals( true, is_array( $jobs[9]->params['range'] ),
			'Last job is recursive sub-job' );
		$this->assertEquals( $title->getPrefixedText(), $jobs[0]->getTitle()->getPrefixedText(),
			'Base job title retainend in leaf job' );
		$this->assertEquals( $title->getPrefixedText(), $jobs[9]->getTitle()->getPrefixedText(),
			'Base job title retainend recursive sub-job' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs[0]->params['rootJobSignature'],
			'Leaf job has root params' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs[9]->params['rootJobSignature'],
			'Recursive sub-job has root params' );

		$jobs2 = BacklinkJobUtils::partitionBacklinkJob(
			$jobs[9],
			9,
			1,
			[ 'params' => $extraParams ]
		);

		$this->assertEquals( 10, count( $jobs2 ), 'Correct number of sub-jobs' );
		$this->assertEquals( $pages[9], current( $jobs2[0]->params['pages'] ),
			'First job is leaf job with proper title' );
		$this->assertEquals( $pages[17], current( $jobs2[8]->params['pages'] ),
			'Last leaf job is leaf job with proper title' );
		$this->assertEquals( true, isset( $jobs2[9]->params['recursive'] ),
			'Last job is recursive sub-job' );
		$this->assertEquals( true, $jobs2[9]->params['recursive'],
			'Last job is recursive sub-job' );
		$this->assertEquals( true, is_array( $jobs2[9]->params['range'] ),
			'Last job is recursive sub-job' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs2[0]->params['rootJobSignature'],
			'Leaf job has root params' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs2[9]->params['rootJobSignature'],
			'Recursive sub-job has root params' );

		$jobs3 = BacklinkJobUtils::partitionBacklinkJob(
			$jobs2[9],
			9,
			1,
			[ 'params' => $extraParams ]
		);

		$this->assertEquals( 2, count( $jobs3 ), 'Correct number of sub-jobs' );
		$this->assertEquals( $pages[18], current( $jobs3[0]->params['pages'] ),
			'First job is leaf job with proper title' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs3[0]->params['rootJobSignature'],
			'Leaf job has root params' );
		$this->assertEquals( $pages[19], current( $jobs3[1]->params['pages'] ),
			'Last job is leaf job with proper title' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs3[1]->params['rootJobSignature'],
			'Last leaf job has root params' );
	}

	public static function provider_backlinks() {
		$pages = [];
		for ( $i = 0; $i < 20; ++$i ) {
			$pages[] = [ 0, "Page-$i" ];
		}
		return [
			[ 10, 'Bang', $pages ]
		];
	}
}
