<?php

/**
 * Dummy implementation of SearchIndexFieldDefinition for testing purposes.
 *
 * @since 1.28
 */
class DummySearchIndexFieldDefinition extends SearchIndexFieldDefinition {

	/**
	 * @param SearchEngine $engine
	 *
	 * @return array
	 */
	public function getMapping( SearchEngine $engine ) {
		$mapping = [
			'name' => $this->name,
			'type' => $this->type,
			'flags' => $this->flags,
			'subfields' => []
		];

		foreach ( $this->subfields as $subfield ) {
			$mapping['subfields'][] = $subfield->getMapping( $engine );
		}

		return $mapping;
	}

}
