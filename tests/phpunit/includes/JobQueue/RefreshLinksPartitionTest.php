<?php
namespace MediaWiki\Tests\JobQueue;

use MediaWiki\JobQueue\Job;
use MediaWiki\JobQueue\Jobs\RefreshLinksJob;
use MediaWiki\JobQueue\Utils\BacklinkJobUtils;
use MediaWiki\Title\Title;
use MediaWikiIntegrationTestCase;

/**
 * @group JobQueue
 * @group medium
 * @group Database
 */
class RefreshLinksPartitionTest extends MediaWikiIntegrationTestCase {

	/**
	 * @dataProvider provideBacklinks
	 * @covers \MediaWiki\JobQueue\Utils\BacklinkJobUtils
	 */
	public function testRefreshLinks( $ns, $dbKey, $pages ) {
		$title = Title::makeTitle( $ns, $dbKey );

		$user = $this->getTestSysop()->getAuthority();
		foreach ( $pages as [ $bns, $bdbkey ] ) {
			$this->editPage(
				Title::makeTitle( $bns, $bdbkey ),
				"[[{$title->getPrefixedText()}]]",
				'test',
				NS_MAIN,
				$user
			);
		}

		$backlinkCache = $this->getServiceContainer()->getBacklinkCacheFactory()
			->getBacklinkCache( $title );
		$this->assertEquals(
			20,
			$backlinkCache->getNumLinks( 'pagelinks' ),
			'Correct number of backlinks'
		);

		$job = new RefreshLinksJob( $title, [ 'recursive' => true, 'table' => 'pagelinks' ]
			+ Job::newRootJobParams( 'refreshlinks:pagelinks:' . $title->getPrefixedText() ) );
		$extraParams = $job->getRootJobParams();
		$jobs = BacklinkJobUtils::partitionBacklinkJob( $job, 9, 1, [ 'params' => $extraParams ] );

		$this->assertCount( 10, $jobs, 'Correct number of sub-jobs' );
		$this->assertEquals( $pages[0], current( $jobs[0]->params['pages'] ),
			'First job is leaf job with proper title' );
		$this->assertEquals( $pages[8], current( $jobs[8]->params['pages'] ),
			'Last leaf job is leaf job with proper title' );
		$this->assertTrue( isset( $jobs[9]->params['recursive'] ),
			'Last job is recursive sub-job' );
		$this->assertTrue( $jobs[9]->params['recursive'],
			'Last job is recursive sub-job' );
		$this->assertIsArray( $jobs[9]->params['range'],
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

		$this->assertCount( 10, $jobs2, 'Correct number of sub-jobs' );
		$this->assertEquals( $pages[9], current( $jobs2[0]->params['pages'] ),
			'First job is leaf job with proper title' );
		$this->assertEquals( $pages[17], current( $jobs2[8]->params['pages'] ),
			'Last leaf job is leaf job with proper title' );
		$this->assertTrue( isset( $jobs2[9]->params['recursive'] ),
			'Last job is recursive sub-job' );
		$this->assertTrue( $jobs2[9]->params['recursive'],
			'Last job is recursive sub-job' );
		$this->assertIsArray( $jobs2[9]->params['range'],
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

		$this->assertCount( 2, $jobs3, 'Correct number of sub-jobs' );
		$this->assertEquals( $pages[18], current( $jobs3[0]->params['pages'] ),
			'First job is leaf job with proper title' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs3[0]->params['rootJobSignature'],
			'Leaf job has root params' );
		$this->assertEquals( $pages[19], current( $jobs3[1]->params['pages'] ),
			'Last job is leaf job with proper title' );
		$this->assertEquals( $extraParams['rootJobSignature'], $jobs3[1]->params['rootJobSignature'],
			'Last leaf job has root params' );
	}

	public static function provideBacklinks() {
		$pages = [];
		for ( $i = 0; $i < 20; ++$i ) {
			$pages[] = [ 0, "Page-$i" ];
		}
		return [
			[ 10, 'Bang', $pages ]
		];
	}
}
