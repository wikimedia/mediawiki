<?php
/**
 * @license GPL-2.0-or-later
 */

namespace MediaWiki\Tests\Mocks\Content;

use MediaWiki\Content\Content;
use MediaWiki\Exception\MWContentSerializationException;

/**
 * A dummy content handler that will throw on an attempt to serialize content.
 */
class DummySerializeErrorContentHandler extends DummyContentHandlerForTesting {

	/** @inheritDoc */
	public function __construct( $dataModel ) {
		parent::__construct( $dataModel, [ "testing-serialize-error" ] );
	}

	/**
	 * @see ContentHandler::unserializeContent
	 *
	 * @param string $blob
	 * @param string|null $format
	 *
	 * @return Content
	 */
	public function unserializeContent( $blob, $format = null ) {
		throw new MWContentSerializationException( 'Could not unserialize content' );
	}

	/**
	 * @see ContentHandler::supportsDirectEditing
	 *
	 * @return bool
	 *
	 * @todo Should this be in the parent class?
	 */
	public function supportsDirectApiEditing() {
		return true;
	}

}
