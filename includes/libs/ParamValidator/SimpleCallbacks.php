<?php

namespace Wikimedia\ParamValidator;

use Wikimedia\ParamValidator\Util\UploadedFile;

/**
 * Simple Callbacks implementation for $_GET/$_POST/$_FILES data
 *
 * Options array keys used by this class:
 *  - 'useHighLimits': (bool) Return value from useHighLimits()
 *
 * @since 1.34
 * @unstable
 */
class SimpleCallbacks implements Callbacks {

	/** @var (string|string[])[] $_GET/$_POST data */
	private $params;

	/** @var (array|UploadedFile)[] $_FILES data or UploadedFile instances */
	private $files;

	/** @var array Any recorded conditions */
	private $conditions = [];

	/**
	 * @param (string|string[])[] $params Data from $_POST + $_GET
	 * @param array[] $files Data from $_FILES
	 */
	public function __construct( array $params, array $files = [] ) {
		$this->params = $params;
		$this->files = $files;
	}

	public function hasParam( $name, array $options ) {
		return isset( $this->params[$name] );
	}

	public function getValue( $name, $default, array $options ) {
		return $this->params[$name] ?? $default;
	}

	public function hasUpload( $name, array $options ) {
		return isset( $this->files[$name] );
	}

	public function getUploadedFile( $name, array $options ) {
		$file = $this->files[$name] ?? null;
		if ( $file && !$file instanceof UploadedFile ) {
			$file = new UploadedFile( $file );
			$this->files[$name] = $file;
		}
		return $file;
	}

	public function recordCondition( ValidationException $condition, array $options ) {
		$this->conditions[] = $condition;
	}

	/**
	 * Fetch any recorded conditions
	 * @return array[]
	 */
	public function getRecordedConditions() {
		return $this->conditions;
	}

	/**
	 * Clear any recorded conditions
	 */
	public function clearRecordedConditions() {
		$this->conditions = [];
	}

	public function useHighLimits( array $options ) {
		return !empty( $options['useHighLimits'] );
	}

}
