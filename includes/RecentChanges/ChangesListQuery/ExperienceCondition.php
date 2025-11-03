<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserFactory;
use stdClass;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * A filter condition module for user experience levels
 *
 * @since 1.45
 */
class ExperienceCondition extends ChangesListConditionBase {
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::LearnerMemberSince,
		MainConfigNames::ExperiencedUserMemberSince,
		MainConfigNames::LearnerEdits,
		MainConfigNames::ExperiencedUserEdits,
	];

	private const LEVELS = [ 'unregistered', 'registered', 'newcomer', 'learner', 'experienced' ];

	private NamedConditionHelper $namedConditionHelper;

	public function __construct(
		private ServiceOptions $config,
		private TempUserConfig $tempUserConfig,
		private UserFactory $userFactory
	) {
		$this->namedConditionHelper = new NamedConditionHelper( $this->tempUserConfig );
	}

	/** @inheritDoc */
	public function validateValue( $value ) {
		if ( !in_array( $value, self::LEVELS ) ) {
			throw new InvalidArgumentException( "must be one of : " .
				implode( ', ', self::LEVELS )
			);
		}
		return $value;
	}

	/**
	 * @param mixed $value
	 * @return never
	 */
	public function exclude( $value ): void {
		throw new LogicException( 'unimplemented' );
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		if ( $value === 'registered' || $value === 'unregistered' ) {
			$rowValue = $this->namedConditionHelper->isNamed( $row )
				? 'registered' : 'unregistered';
			return $value === $rowValue;
		} else {
			return $this->getExperienceFromRow( $row ) === $value;
		}
	}

	private function getExperienceFromRow( stdClass $row ): string {
		// This should match User::getExperienceLevel(), except that we treat
		// temporary users as unregistered
		if ( !$this->namedConditionHelper->isNamed( $row ) ) {
			return 'unregistered';
		} else {
			// TODO: this depends on a UserArray batch query done in
			//   ChangesListSpecialPage to efficient. The batch query should be
			//   owned by this module, or we should do a join. But a join is
			//   inefficient due to T403798.
			$user = $this->userFactory->newFromAnyId( $row->rc_user, $row->rc_user_text );
			return $user->getExperienceLevel();
		}
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->rcUserFields();
	}

	/** @inheritDoc */
	public function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		$selected = array_fill_keys( $this->required, true );

		$isUnregistered = $this->namedConditionHelper->getExpression( $dbr, false );
		$isRegistered = $this->namedConditionHelper->getExpression( $dbr, true );
		$aboveNewcomer = $this->getExperienceExpr( 'learner', $dbr );
		$notAboveNewcomer = $this->getExperienceExpr( 'learner', $dbr, true );
		$aboveLearner = $this->getExperienceExpr( 'experienced', $dbr );
		$notAboveLearner = $this->getExperienceExpr( 'experienced', $dbr, true );

		// We need to select some range of user experience levels, from the following table:
		// | Unregistered |     --------- Registered ---------     |
		// |              |  Newcomers  |  Learners  | Experienced |
		// |<------------>|<----------->|<---------->|<----------->|
		// We just need to define a condition for each of the columns, figure out which are selected,
		// and then OR them together.
		$columnConds = [
			'unregistered' => $isUnregistered,
			'registered' => $isRegistered,
			'newcomer' => $dbr->andExpr( [ $isRegistered, $notAboveNewcomer ] ),
			'learner' => $dbr->andExpr( [ $isRegistered, $aboveNewcomer, $notAboveLearner ] ),
			'experienced' => $dbr->andExpr( [ $isRegistered, $aboveLearner ] ),
		];

		// There are some cases where we can easily optimize away some queries:
		// | Unregistered |     --------- Registered ---------     |
		// |              |  Newcomers  |  Learners  | Experienced |
		// |              |<-------------------------------------->| (1)
		// |<----------------------------------------------------->| (2)

		// (1) Selecting all of "Newcomers", "Learners" and "Experienced users" is the same as "Registered".
		if (
			isset( $selected['registered'] ) ||
			( isset( $selected['newcomer'] ) && isset( $selected['learner'] ) && isset( $selected['experienced'] ) )
		) {
			unset( $selected['newcomer'], $selected['learner'], $selected['experienced'] );
			$selected['registered'] = true;
		}
		// (2) Selecting "Unregistered" and "Registered" covers all users.
		if ( isset( $selected['registered'] ) && isset( $selected['unregistered'] ) ) {
			unset( $selected['registered'], $selected['unregistered'] );
		}

		// Combine the conditions for the selected columns.
		if ( !$selected ) {
			return;
		}
		$selectedColumnConds = array_values( array_intersect_key( $columnConds, $selected ) );
		$query->where( $dbr->orExpr( $selectedColumnConds ) );

		// Add necessary tables to the queries.
		$query->joinForConds( 'actor' )->straight();
		if ( isset( $selected['newcomer'] ) || isset( $selected['learner'] ) || isset( $selected['experienced'] ) ) {
			$query->joinForConds( 'user' )->weakLeft();
		}
	}

	/**
	 * @param string $level
	 * @param IReadableDatabase $dbr
	 * @param bool $asNotCondition
	 * @return IExpression
	 */
	private function getExperienceExpr( $level, IReadableDatabase $dbr, $asNotCondition = false ): IExpression {
		$configSince = $this->getRegistrationThreshold( $level );
		$now = ConvertibleTimestamp::time();
		$secondsPerDay = 86400;
		$timeCutoff = $now - $configSince * $secondsPerDay;

		$editCutoff = $this->getEditThreshold( $level );

		if ( $asNotCondition ) {
			return $dbr->expr( 'user_editcount', '<', $editCutoff )
				->or( 'user_registration', '>', $dbr->timestamp( $timeCutoff ) );
		}
		return $dbr->expr( 'user_editcount', '>=', $editCutoff )->andExpr(
			// Users who don't have user_registration set are very old, so we assume they're above any cutoff
			$dbr->expr( 'user_registration', '=', null )
				->or( 'user_registration', '<=', $dbr->timestamp( $timeCutoff ) )
		);
	}

	/**
	 * @param string $level
	 * @return int days
	 */
	private function getRegistrationThreshold( $level ) {
		return match ( $level ) {
			'learner' => $this->config->get( MainConfigNames::LearnerMemberSince ),
			'experienced' => $this->config->get( MainConfigNames::ExperiencedUserMemberSince ),
		};
	}

	/**
	 * @param string $level
	 * @return int
	 */
	private function getEditThreshold( $level ) {
		return match ( $level ) {
			'learner' => $this->config->get( MainConfigNames::LearnerEdits ),
			'experienced' => $this->config->get( MainConfigNames::ExperiencedUserEdits ),
		};
	}
}
