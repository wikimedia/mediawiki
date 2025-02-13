<?php
namespace MediaWiki\Tests;

/**
 * Trait for asserting callback invocation.
 */
trait ExpectCallbackTrait {

	/** @var array<string,int> */
	private array $expectedCallbackInvocations = [];

	/** @var array<string,int> */
	private array $actualCallbackInvocations = [];

	/**
	 * Expect the given domain event to be triggered
	 *
	 * @param string $eventType The event type to expect
	 * @param int $expectedCount The expected number of invocations.
	 *        Setting this to 0 fails the test if the event is triggered.
	 *        Expected counts are added up across consecutive calls to
	 *        expectDomainEvent() for the same event type.
	 * @param ?callable $listener A listener callback for performing
	 *        assertions on the event object.
	 */
	private function expectDomainEvent(
		string $eventType,
		int $expectedCount = 1,
		?callable $listener = null
	) {
		$this->registerListener(
			$eventType,
			$this->makeExpectedCallback(
				"$eventType listener",
				$expectedCount,
				$listener
			)
		);
	}

	/**
	 * Expect the given hook to be triggered
	 *
	 * @param string $hookName The name of the hook to expect
	 * @param int $expectedCount The expected number of invocations.
	 *        Setting this to 0 fails the test if the hook is triggered.
	 *        Expected counts are added up across consecutive calls to
	 *        expectHook() for the same event hook.
	 * @param ?callable $handler A handler callback for performing
	 *        assertions on the hook parameters.
	 */
	private function expectHook(
		string $hookName,
		int $expectedCount = 1,
		?callable $handler = null
	) {
		$this->setTemporaryHook(
			$hookName,
			$this->makeExpectedCallback(
				"$hookName handler",
				$expectedCount,
				$handler
			)
		);
	}

	/**
	 * Returns a callback that is expected to be called the given number of
	 * times.
	 *
	 * @param string $name The name of the callback, to be used in failure
	 *        messages.
	 * @param int $expectedCount The expected number of invocations.
	 *        Setting this to 0 fails the test if the callback is invoked.
	 *        Expected counts are added up across consecutive calls to
	 *        makeExpectedCallback() with the same name.
	 * @param ?callable $assertions A callback for performing assertions.
	 *        Parameters and return value are looped through.
	 */
	private function makeExpectedCallback(
		string $name,
		int $expectedCount = 1,
		?callable $assertions = null
	): callable {
		$this->expectedCallbackInvocations[ $name ] =
			( $this->expectedCallbackInvocations[ $name ] ?? 0 )
			+ $expectedCount;

		return function ( &...$args ) use ( $name, $assertions ) {
			$this->actualCallbackInvocations[ $name ] =
				( $this->actualCallbackInvocations[ $name ] ?? 0 ) + 1;

			if ( $assertions ) {
				return $assertions( ...$args );
			}

			return null;
		};
	}

	/**
	 * @postCondition
	 */
	public function expectedCallbackInvocationsPostConditions(): void {
		$this->runDeferredUpdates();

		foreach ( $this->expectedCallbackInvocations as $name => $count ) {
			$this->assertSame(
				$count,
				$this->actualCallbackInvocations[ $name ] ?? 0,
				"Expected number of $name invocations"
			);
		}
	}

}
