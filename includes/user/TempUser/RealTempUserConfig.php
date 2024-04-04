<?php

namespace MediaWiki\User\TempUser;

use BadMethodCallException;
use InvalidArgumentException;
use MediaWiki\Permissions\Authority;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Rdbms\AndExpressionGroup;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Rdbms\OrExpressionGroup;

/**
 * The real TempUserConfig including internal methods used by TempUserCreator.
 *
 * @since 1.39
 */
class RealTempUserConfig implements TempUserConfig {
	/** @var bool */
	private $enabled = false;

	/** @var array */
	private $serialProviderConfig = [];

	/** @var array */
	private $serialMappingConfig = [];

	/** @var string[] */
	private $autoCreateActions;

	/** @var Pattern|null */
	private $genPattern;

	/** @var Pattern[]|null */
	private $matchPatterns;

	/** @var Pattern|null */
	private $reservedPattern;

	/** @var int|null */
	private $expireAfterDays;

	/** @var int|null */
	private $notifyBeforeExpirationDays;

	/**
	 * @param array $config See the documentation of $wgAutoCreateTempUser.
	 *   - enabled: bool
	 *   - actions: array
	 *   - genPattern: string
	 *   - matchPattern: string|string[], optional
	 *   - reservedPattern: string, optional
	 *   - serialProvider: array
	 *   - serialMapping: array
	 *   - expireAfterDays: int, optional
	 *   - notifyBeforeExpirationDays: int, optional
	 */
	public function __construct( $config ) {
		if ( $config['enabled'] ?? false ) {
			$this->enabled = true;
			$this->autoCreateActions = $config['actions'];
			$this->genPattern = new Pattern( 'genPattern', $config['genPattern'] );
			if ( isset( $config['matchPattern'] ) ) {
				$matchPatterns = $config['matchPattern'];
				if ( !is_array( $config['matchPattern'] ) ) {
					$matchPatterns = [ $matchPatterns ];
				}
				foreach ( $matchPatterns as &$pattern ) {
					$pattern = new Pattern( 'matchPattern', $pattern );
				}
				$this->matchPatterns = $matchPatterns;
			} else {
				$this->matchPatterns = [ $this->genPattern ];
			}
			$this->serialProviderConfig = $config['serialProvider'];
			$this->serialMappingConfig = $config['serialMapping'];
			$this->expireAfterDays = $config['expireAfterDays'] ?? null;
			$this->notifyBeforeExpirationDays = $config['notifyBeforeExpirationDays'] ?? null;
		}
		if ( isset( $config['reservedPattern'] ) ) {
			$this->reservedPattern = new Pattern( 'reservedPattern', $config['reservedPattern'] );
		}
	}

	public function isEnabled() {
		return $this->enabled;
	}

	public function isAutoCreateAction( string $action ) {
		if ( $action === 'create' ) {
			$action = 'edit';
		}
		return $this->isEnabled()
			&& in_array( $action, $this->autoCreateActions, true );
	}

	public function shouldAutoCreate( Authority $authority, string $action ) {
		return $this->isAutoCreateAction( $action )
			&& !$authority->isRegistered()
			&& $authority->isAllowed( 'createaccount' );
	}

	public function isTempName( string $name ) {
		if ( !$this->isEnabled() ) {
			return false;
		}
		foreach ( $this->matchPatterns as $pattern ) {
			if ( $pattern->isMatch( $name ) ) {
				return true;
			}
		}
		return false;
	}

	public function isReservedName( string $name ) {
		return $this->isTempName( $name ) || ( $this->reservedPattern && $this->reservedPattern->isMatch( $name ) );
	}

	public function getPlaceholderName(): string {
		$year = null;
		if ( $this->serialProviderConfig['useYear'] ?? false ) {
			$year = MWTimestamp::getInstance()->format( 'Y' );
		}
		if ( $this->isEnabled() ) {
			return $this->genPattern->generate( '*', $year );
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	public function getMatchPattern(): Pattern {
		wfDeprecated( __METHOD__, '1.42' );
		if ( $this->isEnabled() ) {
			// This method is deprecated to allow time for callers to update.
			// This method only returns one Pattern, so just return the first one.
			return $this->getMatchPatterns()[0];
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	public function getMatchPatterns(): array {
		if ( $this->isEnabled() ) {
			return $this->matchPatterns;
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	public function getMatchCondition( IReadableDatabase $db, string $field, string $op ): IExpression {
		if ( $this->isEnabled() ) {
			$exprs = [];
			foreach ( $this->getMatchPatterns() as $pattern ) {
				$exprs[] = $db->expr( $field, $op, $pattern->toLikeValue( $db ) );
			}
			if ( count( $exprs ) === 1 ) {
				return $exprs[0];
			}
			if ( $op === IExpression::LIKE ) {
				return new OrExpressionGroup( ...$exprs );
			} elseif ( $op === IExpression::NOT_LIKE ) {
				return new AndExpressionGroup( ...$exprs );
			} else {
				throw new InvalidArgumentException( "Invalid operator $op" );
			}
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	public function getExpireAfterDays(): ?int {
		return $this->expireAfterDays;
	}

	public function getNotifyBeforeExpirationDays(): ?int {
		return $this->notifyBeforeExpirationDays;
	}

	/**
	 * @internal For TempUserCreator only
	 * @return Pattern
	 */
	public function getGeneratorPattern(): Pattern {
		if ( $this->isEnabled() ) {
			return $this->genPattern;
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	/**
	 * @internal For TempUserCreator only
	 * @return array
	 */
	public function getSerialProviderConfig(): array {
		return $this->serialProviderConfig;
	}

	/**
	 * @internal For TempUserCreator only
	 * @return array
	 */
	public function getSerialMappingConfig(): array {
		return $this->serialMappingConfig;
	}
}
