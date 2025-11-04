<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Json
 */

namespace MediaWiki\Json;

/** @deprecated since 1.45; use JsonCodecableTrait in new code. */
trait JsonDeserializableTrait {

	public function jsonSerialize(): array {
		return $this->annotateJsonForDeserialization(
			$this->toJsonArray()
		);
	}

	/**
	 * Annotate the $json array with class metadata.
	 *
	 * @param array $json
	 * @return array
	 */
	private function annotateJsonForDeserialization( array $json ): array {
		$json[JsonConstants::TYPE_ANNOTATION] = get_class( $this );
		return $json;
	}

	/**
	 * Prepare this object for JSON serialization.
	 * The returned array will be passed to self::newFromJsonArray
	 * upon JSON deserialization.
	 * @return array
	 */
	abstract protected function toJsonArray(): array;
}

/** @deprecated class alias since 1.43 */
class_alias( JsonDeserializableTrait::class, 'MediaWiki\\Json\\JsonUnserializableTrait' );
