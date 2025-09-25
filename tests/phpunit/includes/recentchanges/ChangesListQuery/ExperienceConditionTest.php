<?php

namespace MediaWiki\Tests\RecentChanges\ChangesListQuery;

use MediaWiki\DAO\WikiAwareEntity;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\PageIdentityValue;
use MediaWiki\User\User;

/**
 * @group Database
 * @covers \MediaWiki\RecentChanges\ChangesListQuery\ExperienceCondition
 */
class ExperienceConditionTest extends \MediaWikiIntegrationTestCase {

	private function createUsers( array $specs, int $now ) {
		$dbw = $this->getDb();
		$rcf = $this->getServiceContainer()->getRecentChangeFactory();
		$title = new PageIdentityValue( 1, 0, 'Test', WikiAwareEntity::LOCAL );
		foreach ( $specs as $name => $spec ) {
			$user = User::createNew(
				$name,
				[
					'editcount' => $spec['edits'],
					'registration' => $dbw->timestamp( $this->daysAgo( $spec['days'], $now ) ),
					'email' => 'ut',
				]
			);
			$rc = $rcf->createEditRecentChange(
				$now,
				$title,
				false,
				$user,
				'',
				0,
				false
			);
			$rcf->insertRecentChange( $rc );
		}
	}

	private function daysAgo( int $days, int $now ): int {
		$secondsPerDay = 86400;
		return $now - $days * $secondsPerDay;
	}

	private function fetchUsers( array $filters, int $now ): array {
		$query = $this->getServiceContainer()->getChangesListQueryFactory()->newQuery();
		foreach ( $filters as $filter ) {
			$query->applyAction( 'require', 'experience', $filter );
		}
		$query->fields( 'actor_name' );

		$res = $query->fetchResult()->getRows();
		foreach ( $res as $row ) {
			$usernames[] = $row->actor_name;
		}

		return $usernames;
	}

	public function testFilterUserExpLevel() {
		$now = time();
		$this->overrideConfigValues( [
			MainConfigNames::LearnerEdits => 10,
			MainConfigNames::LearnerMemberSince => 4,
			MainConfigNames::ExperiencedUserEdits => 500,
			MainConfigNames::ExperiencedUserMemberSince => 30,
		] );

		$this->createUsers( [
			'Newcomer1' => [ 'edits' => 2, 'days' => 2 ],
			'Newcomer2' => [ 'edits' => 12, 'days' => 3 ],
			'Newcomer3' => [ 'edits' => 8, 'days' => 5 ],
			'Learner1' => [ 'edits' => 15, 'days' => 10 ],
			'Learner2' => [ 'edits' => 450, 'days' => 20 ],
			'Learner3' => [ 'edits' => 460, 'days' => 33 ],
			'Learner4' => [ 'edits' => 525, 'days' => 28 ],
			'Experienced1' => [ 'edits' => 538, 'days' => 33 ],
		], $now );

		// newcomers only
		$this->assertArrayEquals(
			[ 'Newcomer1', 'Newcomer2', 'Newcomer3' ],
			$this->fetchUsers( [ 'newcomer' ], $now )
		);

		// newcomers and learner
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
			],
			$this->fetchUsers( [ 'newcomer', 'learner' ], $now )
		);

		// newcomers and more learner
		$this->assertArrayEquals(
			[
				'Newcomer1', 'Newcomer2', 'Newcomer3',
				'Experienced1',
			],
			$this->fetchUsers( [ 'newcomer', 'experienced' ], $now )
		);

		// learner only
		$this->assertArrayEquals(
			[ 'Learner1', 'Learner2', 'Learner3', 'Learner4' ],
			$this->fetchUsers( [ 'learner' ], $now )
		);

		// more experienced only
		$this->assertArrayEquals(
			[ 'Experienced1' ],
			$this->fetchUsers( [ 'experienced' ], $now )
		);

		// learner and more experienced
		$this->assertArrayEquals(
			[
				'Learner1', 'Learner2', 'Learner3', 'Learner4',
				'Experienced1',
			],
			$this->fetchUsers( [ 'learner', 'experienced' ], $now )
		);
	}

}
