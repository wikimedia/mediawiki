<?php
/**
 * Helper class for the index.php entry point.
 *
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiEntryPoint;
use MediaWiki\MediaWikiServices;

/**
 * Backwards compatibility shim for use by extensions that created a MediaWiki object just in order to call
 * doPostOutputShutdown().
 *
 * @deprecated since 1.42, use MediaWikiEntryPoint instead
 */
class MediaWiki extends MediaWikiEntryPoint {

	public function __construct(
		?IContextSource $context = null,
		?EntryPointEnvironment $environment = null
	) {
		$context ??= RequestContext::getMain();
		$environment ??= new EntryPointEnvironment();

		parent::__construct( $context, $environment, MediaWikiServices::getInstance() );
	}

	protected function execute(): never {
		throw new LogicException(
			'The backwards-compat MediaWiki class does not implement the execute() method'
		);
	}

	/**
	 * Overwritten to make public, for backwards compatibility
	 *
	 * @deprecated since 1.42, extensions should have no need to call this.
	 *             Subclasses of MediaWikiEntryPoint in core should generally
	 *             call postOutputShutdown() instead.
	 */
	public function restInPeace() {
		parent::restInPeace();
	}

	/**
	 * Overwritten to make public, for backwards compatibility.
	 *
	 * @deprecated since 1.42, extensions should have no need to call this.
	 */
	public function doPostOutputShutdown() {
		parent::doPostOutputShutdown();
	}

	/**
	 * This function commits all DB and session changes as needed *before* the
	 * client can receive a response (in case DB commit fails) and thus also before
	 * the response can trigger a subsequent related request by the client.
	 *
	 * @param IContextSource $context
	 *
	 * @since 1.27
	 * @deprecated since 1.42, extensions should have no need to call this.
	 *             Subclasses of MediaWikiEntryPoint in core should generally
	 *             call prepareForOutput() instead.
	 */
	public static function preOutputCommit( IContextSource $context ) {
		$entryPoint = new static( $context );
		$entryPoint->prepareForOutput();
	}

}
