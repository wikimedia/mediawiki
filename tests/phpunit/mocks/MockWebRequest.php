<?php

/**
 * A mock WebRequest.
 *
 * If the code under test accesses the response via the request (see
 * WebRequest#response), then you might be able to use this mock to simplify
 * your tests.
 */
class MockWebRequest extends WebRequest
{
	/**
	 * @var WebResponse
	 */
	protected $response;

	public function __construct( WebResponse $response ) {
		parent::__construct();

		$this->response = $response;
	}

	public function response() {
		return $this->response;
	}
}
