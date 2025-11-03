<?php

namespace MediaWiki\RecentChanges\ChangesListQuery;

use MediaWiki\User\TempUser\TempUserConfig;
use stdClass;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A filter module which checks if the change actor is registered and "named",
 * i.e. not temporary.
 *
 * @since 1.45
 */
class NamedCondition extends ChangesListConditionBase {
	private NamedConditionHelper $namedConditionHelper;

	public function __construct(
		private TempUserConfig $tempUserConfig
	) {
		$this->namedConditionHelper = new NamedConditionHelper( $this->tempUserConfig );
	}

	/** @inheritDoc */
	public function evaluate( stdClass $row, $value ): bool {
		return $this->namedConditionHelper->isNamed( $row );
	}

	/**
	 * @param null $value
	 * @return null
	 */
	public function validateValue( $value ) {
		if ( $value !== null ) {
			throw new \InvalidArgumentException(
				'boolean filter "named" does not take a value' );
		}
		return $value;
	}

	/** @inheritDoc */
	protected function prepareCapture( IReadableDatabase $dbr, QueryBackend $query ) {
		$query->joinForFields( 'actor' )->straight();
		$query->rcUserFields();
	}

	/** @inheritDoc */
	protected function prepareConds( IReadableDatabase $dbr, QueryBackend $query ) {
		[ $required, $excluded ] = $this->getUniqueValues();
		if ( $required === [] ) {
			$query->forceEmptySet();
		} elseif ( $required || $excluded ) {
			// TODO: consider straight join
			$query->joinForConds( 'actor' )->reorderable();
			$query->where( $this->namedConditionHelper->getExpression( $dbr, (bool)$required ) );
		}
	}
}
