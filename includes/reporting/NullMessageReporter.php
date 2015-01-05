<?php

/**
 * Null implementation of the MessageReporter interface that
 * does nothing with messages it receives.
 *
 * @since 1.24
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class NullMessageReporter implements MessageReporter {

	/**
	 * @see MessageReporter::reportMessage
	 *
	 * @param string $message
	 */
	public function reportMessage( $message ) {
		// no-op
	}
}
