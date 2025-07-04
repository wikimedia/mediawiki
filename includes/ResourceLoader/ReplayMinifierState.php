<?php

namespace MediaWiki\ResourceLoader;

use LogicException;
use Wikimedia\Minify\MinifierState;

/**
 * Fake minifier that buffers additions to later replay to multiple another minifiers.
 *
 * This exists for ResourceLoader::getOneModuleResponse() to pass module content
 * through both IdentityMinifierState and JavaScriptMinifierState/JavaScriptMapperState
 * without building the same module twice.
 *
 * @internal
 * @phan-file-suppress PhanPluginNeverReturnMethod
 */
class ReplayMinifierState extends MinifierState {

	protected array $calls = [];

	/**
	 * Replay all supported method calls from this minifier on another minifier.
	 */
	public function replayOn( MinifierState $otherMinifier ): void {
		foreach ( $this->calls as [ $method, $args ] ) {
			$otherMinifier->$method( ...$args );
		}
	}

	/** @inheritDoc */
	public function outputFile( string $file ) {
		$this->calls[] = [ __FUNCTION__, func_get_args() ];
		return $this;
	}

	/** @inheritDoc */
	public function sourceRoot( string $url ) {
		throw new LogicException( "Not implemented" );
	}

	/** @inheritDoc */
	public function addSourceFile( string $url, string $source, bool $bundle = false ) {
		$this->calls[] = [ __FUNCTION__, func_get_args() ];
		return $this;
	}

	/** @inheritDoc */
	public function setErrorHandler( $onError ) {
		throw new LogicException( "Not implemented" );
	}

	/** @inheritDoc */
	protected function minify( string $source ): string {
		throw new LogicException( "Not implemented" );
	}

	/** @inheritDoc */
	public function addOutput( string $output ) {
		$this->calls[] = [ __FUNCTION__, func_get_args() ];
		return $this;
	}

	/** @inheritDoc */
	public function ensureNewline() {
		$this->calls[] = [ __FUNCTION__, func_get_args() ];
		return $this;
	}

	/** @inheritDoc */
	public function getMinifiedOutput() {
		throw new LogicException( "Not implemented" );
	}

}
