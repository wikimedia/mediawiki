<?php

namespace Wikimedia\Rdbms;

/**
 * Interface SchemaBuilder that gets a definition and produces SQL based on RDBMS
 *
 * @experimental
 * @unstable
 */
interface SchemaBuilder {

	/**
	 * An example of $schema value:
	 * [
	 * 'name' => 'actor',
	 * 'columns' => [
	 * 	[ 'actor_id', 'bigint', [ 'Unsigned' => true, 'Notnull' => true ] ],
	 * 	[ 'actor_user', 'integer', [ 'Unsigned' => true ] ],
	 * 	[ 'actor_name', 'string', [ 'Length' => 255, 'Notnull' => true ] ],
	 * ],
	 * 'indexes' => [
	 * 	[ 'actor_user', [ 'actor_user' ], 'unique' => true ],
	 * 	[ 'actor_name', [ 'actor_name' ], 'unique' => true ]
	 *  ],
	 * 'pk' => [ 'actor_id' ]
	 * ],
	 * @param array $schema
	 * @return void
	 */
	public function addTable( array $schema );

	/**
	 * @return string[] SQLs to run
	 */
	public function getSql();
}
