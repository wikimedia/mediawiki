<?php

namespace MediaWiki\JobQueue;

use Closure;
use InvalidArgumentException;
use MediaWiki\Page\PageReference;
use MediaWiki\Title\Title;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @since 1.40
 */
class JobFactory {

	private ObjectFactory $objectFactory;

	/** @var array<array|callable|string> Object specs, see ObjectFactory */
	private array $jobObjectSpecs;

	/**
	 * @param ObjectFactory $objectFactory
	 * @param array<array|callable|string> $jobObjectSpecs Object specs, see ObjectFactory
	 */
	public function __construct( ObjectFactory $objectFactory, array $jobObjectSpecs ) {
		$this->objectFactory = $objectFactory;
		$this->jobObjectSpecs = $jobObjectSpecs;
	}

	/**
	 * Create the appropriate object to handle a specific job.
	 *
	 * @note For backwards compatibility with Job::factory,
	 * this method also supports an alternative signature:
	 * @code
	 *   newJob(
	 *     string $command,
	 *     PageReference $page,
	 *     array $params
	 *   )
	 * @endcode
	 *
	 * @param string $command Job command
	 * @param array $params Job parameters
	 *
	 * @return Job
	 * @throws InvalidArgumentException
	 */
	public function newJob( string $command, $params = [] ): Job {
		if ( !isset( $this->jobObjectSpecs[ $command ] ) ) {
			throw new InvalidArgumentException( "Invalid job command '{$command}'" );
		}

		$spec = $this->jobObjectSpecs[ $command ];
		$needsTitle = $this->needsTitle( $command, $spec );

		// TODO: revisit support for old method signature
		if ( $params instanceof PageReference ) {
			// Backwards compatibility for old signature ($command, $title, $params)
			$title = Title::newFromPageReference( $params );
			$params = func_num_args() >= 3 ? func_get_arg( 2 ) : [];
		} elseif ( isset( $params['namespace'] ) && isset( $params['title'] ) ) {
			// Handle job classes that take title as constructor parameter.
			// If a newer classes like GenericParameterJob uses these parameters,
			// then this happens in Job::__construct instead.
			$title = Title::makeTitle(
				$params['namespace'],
				$params['title']
			);
		} else {
			// Default title for job classes not implementing GenericParameterJob.
			// This must be a valid title because it not directly passed to
			// our Job constructor, but rather its subclasses which may expect
			// to be able to use it.
			$title = Title::makeTitle(
				NS_SPECIAL,
				'Blankpage'
			);
		}

		if ( $needsTitle ) {
			$args = [ $title, $params ];
		} else {
			$args = [ $params ];
		}

		/** @var Job $job */
		$job = $this->objectFactory->createObject(
			$spec,
			[
				'allowClassName' => true,
				'allowCallable' => true,
				'extraArgs' => $args,
				'assertClass' => Job::class
			]
		);

		// TODO: create a setter, marked @internal
		$job->command = $command;
		return $job;
	}

	/**
	 * Determines whether the job class needs a Title to be passed
	 * as the first parameter to the constructor.
	 *
	 * @param string $command
	 * @param string|array|Closure $spec
	 *
	 * @return bool
	 */
	private function needsTitle( string $command, $spec ): bool {
		if ( is_callable( $spec ) ) {
			$needsTitle = true;
		} elseif ( is_array( $spec ) ) {
			if ( isset( $spec['needsPage'] ) ) {
				$needsTitle = $spec['needsPage'];
			} elseif ( isset( $spec['class'] ) ) {
				$needsTitle = !is_subclass_of( $spec['class'],
					GenericParameterJob::class );
			} elseif ( isset( $spec['factory'] ) ) {
				$needsTitle = true;
			} else {
				throw new InvalidArgumentException(
					"Invalid job specification for '{$command}': " .
					"must contain the 'class' or 'factory' key."
				);
			}
		} elseif ( is_string( $spec ) ) {
			$needsTitle = !is_subclass_of( $spec,
				GenericParameterJob::class );
		} else {
			throw new InvalidArgumentException(
				"Invalid job specification for '{$command}': " .
				"must be a callable, an object spec array, or a class name"
			);
		}

		return $needsTitle;
	}
}
