<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;

/**
 * A click providing the current time in nanoseconds, backed by {@link hrtime}.
 *
 * @since 1.43
 * @internal
 */
class Clock {
	/**
	 * Timestamp to return in place of the current time, or `null` to use the current time.
	 * @var int|null
	 */
	private static ?int $mockTime = null;

	/**
	 * The reference UNIX timestamp in nanoseconds which hrtime() offsets should be added to
	 * to derive an absolute timestamp.
	 * @var int|null
	 */
	private ?int $referenceTime = null;

	public function __construct() {
		Assert::precondition(
			PHP_INT_SIZE >= 8,
			'The Clock class requires 64-bit integers to support nanosecond timing'
		);
	}

	/**
	 * Get the current time, represented as the number of nanoseconds since the UNIX epoch.
	 */
	public function getCurrentNanoTime(): int {
		$this->referenceTime ??= (int)( 1e9 * microtime( true ) ) - hrtime( true );
		return self::$mockTime ?? ( $this->referenceTime + hrtime( true ) );
	}

	/**
	 * Set a mock time to override the timestamp returned by {@link Clock::getCurrentNanoTime()}.
	 * Useful for testing.
	 *
	 * @param int|null $epochNanos The override timestamp, or `null` to return to using the current time.
	 * @return void
	 */
	public static function setMockTime( ?int $epochNanos ): void {
		Assert::precondition( defined( 'MW_PHPUNIT_TEST' ), 'This method should only be used in tests' );
		self::$mockTime = $epochNanos;
	}
}
