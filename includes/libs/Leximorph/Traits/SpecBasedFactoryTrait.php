<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Leximorph\Traits;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use UnexpectedValueException;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * SpecBasedFactoryTrait
 *
 * Trait for instantiating Leximorph objects from a spec map.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
trait SpecBasedFactoryTrait {

	/**
	 * The default language code.
	 */
	protected string $langCode;

	/**
	 * The logger instance.
	 */
	protected ?LoggerInterface $logger = null;

	/**
	 * Cache of created singleton objects.
	 *
	 * @var array<string, object>
	 */
	protected array $singletons = [];

	/**
	 * Return the spec map (keyed by class name).
	 *
	 * @return array<class-string, array<string, mixed>>
	 */
	abstract protected function getSpecMap(): array;

	/**
	 * Return the constructor arguments for the given spec.
	 *
	 * Each subclass can decide how to build arguments based on flags like "langDependent", "needsLogger", etc.
	 *
	 * @param array<string,mixed> $spec
	 * @param LoggerInterface $logger
	 *
	 * @return array<int,mixed>
	 */
	abstract protected function getSpecArgs( array $spec, LoggerInterface $logger ): array;

	/**
	 * Create and cache an object instance.
	 *
	 * @template T of object
	 *
	 * @param class-string<T> $class The class name.
	 *
	 * @return T An instance of the specified class.
	 *
	 * @since 1.45
	 */
	protected function createFromSpec( string $class ) {
		if ( isset( $this->singletons[$class] ) ) {
			/** @var T $cached */
			$cached = $this->singletons[$class];

			return $cached;
		}

		$specs = $this->getSpecMap();
		if ( !isset( $specs[$class] ) ) {
			throw new UnexpectedValueException( "Class not registered: $class" );
		}

		$spec = $specs[$class];
		$logger = $this->logger ?? new NullLogger();

		$args = $this->getSpecArgs( $spec, $logger );
		if ( !empty( $spec['args'] ) && is_array( $spec['args'] ) ) {
			$args = array_merge( $args, $spec['args'] );
		}

		$objectSpec = [
			'class' => $class,
			'args' => $args,
		];

		/** @phan-suppress-next-line PhanTypeInvalidCallableArrayKey */
		$instance = ObjectFactory::getObjectFromSpec( $objectSpec );

		if ( !$instance instanceof $class ) {
			throw new UnexpectedValueException(
				"Expected instance of $class, got " . get_class( $instance )
			);
		}

		$this->singletons[$class] = $instance;

		return $instance;
	}
}
