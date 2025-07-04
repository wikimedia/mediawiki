<?php

namespace MediaWiki\User\TempUser;

use BadMethodCallException;
use InvalidArgumentException;
use MediaWiki\Permissions\Authority;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * The real TempUserConfig including internal methods used by TempUserCreator.
 *
 * @since 1.39
 */
class RealTempUserConfig implements TempUserConfig {
	/** @var bool */
	private $known = false;

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
	 *   - known: bool
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
		$this->enabled = $config['enabled'] ?? false;
		$this->known = $this->enabled || ( $config['known'] ?? false );

		// Configuration related to creating new temporary accounts for some actions
		if ( $this->enabled ) {
			$this->autoCreateActions = $config['actions'];
			$this->serialProviderConfig = $config['serialProvider'];
			$this->serialMappingConfig = $config['serialMapping'];
		}

		// Configuration related to managing and identifying existing temporary accounts,
		// regardless of whether new temp accounts are being actively created via the
		// 'enabled' config flag.
		if ( $this->known || $this->enabled ) {
			$this->genPattern = new Pattern( 'genPattern', $config['genPattern'] );
			$this->expireAfterDays = $config['expireAfterDays'] ?? null;
			$this->notifyBeforeExpirationDays = $config['notifyBeforeExpirationDays'] ?? null;
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
		}

		// Configuration that is set regardless of whether the feature is enabled or known.
		if ( isset( $config['reservedPattern'] ) ) {
			$this->reservedPattern = new Pattern( 'reservedPattern', $config['reservedPattern'] );
		}
	}

	/** @inheritDoc */
	public function isEnabled() {
		return $this->enabled;
	}

	/** @inheritDoc */
	public function isKnown() {
		return $this->known;
	}

	/** @inheritDoc */
	public function isAutoCreateAction( string $action ) {
		if ( $action === 'create' ) {
			$action = 'edit';
		}
		return $this->isEnabled()
			&& in_array( $action, $this->autoCreateActions, true );
	}

	/** @inheritDoc */
	public function shouldAutoCreate( Authority $authority, string $action ) {
		return $this->isAutoCreateAction( $action )
			&& !$authority->isRegistered()
			&& $authority->isAllowed( 'createaccount' );
	}

	/** @inheritDoc */
	public function isTempName( string $name ) {
		if ( !$this->isKnown() ) {
			return false;
		}
		foreach ( $this->matchPatterns as $pattern ) {
			if ( $pattern->isMatch( $name ) ) {
				return true;
			}
		}
		return false;
	}

	/** @inheritDoc */
	public function isReservedName( string $name ) {
		return $this->isTempName( $name ) || ( $this->reservedPattern && $this->reservedPattern->isMatch( $name ) );
	}

	/** @inheritDoc */
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

	/**
	 * @deprecated since 1.42.
	 */
	public function getMatchPattern(): Pattern {
		wfDeprecated( __METHOD__, '1.42' );
		if ( $this->isKnown() ) {
			// This method is deprecated to allow time for callers to update.
			// This method only returns one Pattern, so just return the first one.
			return $this->getMatchPatterns()[0];
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	/** @inheritDoc */
	public function getMatchPatterns(): array {
		if ( $this->isKnown() ) {
			return $this->matchPatterns;
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	/** @inheritDoc */
	public function getMatchCondition( IReadableDatabase $db, string $field, string $op ): IExpression {
		if ( $this->isKnown() ) {
			$exprs = [];
			foreach ( $this->getMatchPatterns() as $pattern ) {
				$exprs[] = $db->expr( $field, $op, $pattern->toLikeValue( $db ) );
			}
			if ( count( $exprs ) === 1 ) {
				return $exprs[0];
			}
			if ( $op === IExpression::LIKE ) {
				return $db->orExpr( $exprs );
			} elseif ( $op === IExpression::NOT_LIKE ) {
				return $db->andExpr( $exprs );
			} else {
				throw new InvalidArgumentException( "Invalid operator $op" );
			}
		} else {
			throw new BadMethodCallException( __METHOD__ . ' is disabled' );
		}
	}

	/** @inheritDoc */
	public function getExpireAfterDays(): ?int {
		return $this->expireAfterDays;
	}

	/** @inheritDoc */
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
