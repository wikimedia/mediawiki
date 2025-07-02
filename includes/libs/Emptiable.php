<?php
namespace Wikimedia;

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

/** @deprecated class alias since 1.41 */
class_alias( Emptiable::class, 'MediaWiki\\Emptiable' );
/** @deprecated class alias since 1.45 */
class_alias( Emptiable::class, 'MediaWiki\\Libs\\Emptiable' );
