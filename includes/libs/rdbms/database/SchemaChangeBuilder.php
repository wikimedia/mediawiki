<?php

namespace Wikimedia\Rdbms;

/**
 * Interface SchemaChangeBuilder that gets a definition and produces ALTER TABLE SQL based on RDBMS
 *
 * @experimental
 * @unstable
 */
interface SchemaChangeBuilder {

	/**
	 * An example of $schema value:
	 * [
	 * 'comment' => 'Adding foo field',
	 * 'before' => <Before snapshot of the abstract schema>
	 * 'after' => <After snapshot of the abstract schema>
	 * ],
	 * @param array $schemaChangeSpec
	 * @return string[]
	 */
	public function getSchemaChangeSql( array $schemaChangeSpec ): array;
}
