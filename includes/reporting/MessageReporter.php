<?php

/**
 * Interface for objects that can report messages.
 *
 * @since 1.24
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
interface MessageReporter {

	/**
	 * Report the provided message.
	 *
	 * @param string $message
	 */
	public function reportMessage( $message );
}
