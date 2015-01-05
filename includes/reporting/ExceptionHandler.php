<?php

/**
 * Interface for objects that can handle exceptions.
 *
 * @since 1.24
 *
 * @licence GNU GPL v2+
 * @author Daniel Kinzler
 */
interface ExceptionHandler {

	/**
	 * Handle the given exception. Typical ways to handle an exception are to
	 * re-throw it, ignore it or log it.
	 *
	 * @param Exception $exception
	 * @param string $errorCode
	 * @param string $explanation
	 */
	public function handleException( Exception $exception, $errorCode, $explanation );

}
