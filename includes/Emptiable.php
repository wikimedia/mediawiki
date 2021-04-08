<?php
namespace MediaWiki;

/**
 * An interface to check for emptiness of an object
 */
interface Emptiable {

	/**
	 * Check if object is empty
	 * @return bool Is it empty
	 */
	public function isEmpty();

}
