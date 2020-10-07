<?php
/**
 * Exception thrown when an unregistered content model is requested. This error
 * can be triggered by user input, so a separate exception class is provided so
 * callers can substitute a context-specific, internationalised error message.
 *
 * @newable
 * @ingroup Content
 * @since 1.27
 */
class MWUnknownContentModelException extends MWException {
	/** @var string The name of the unknown content model */
	private $modelId;

	/**
	 * @stable to call
	 * @param string $modelId
	 */
	public function __construct( $modelId ) {
		parent::__construct( "The content model '$modelId' is not registered on this wiki.\n" .
			'See https://www.mediawiki.org/wiki/Content_handlers to find out which extensions ' .
			'handle this content model.' );
		$this->modelId = $modelId;
	}

	/** @return string */
	public function getModelId() {
		return $this->modelId;
	}
}
