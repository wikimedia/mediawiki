<?php
declare( strict_types = 1 );
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Storage;

use MediaWiki\Content\Content;
use MediaWiki\Parser\ParserOutput;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;

/**
 * A simple structured value for stash contents.
 */
class PageEditStashContents implements JsonCodecable {
	use JsonCodecableTrait;

	public function __construct(
		public readonly Content $pstContent,
		public readonly ParserOutput $output,
		/** A hash of the ParserOptions used to produce $output. (T439926) */
		public readonly ?string $optionsHash,
		/** TS::MW */
		public readonly string $timestamp,
		public readonly ?int $edits,
	) {
	}

	public function toJsonArray(): array {
		return [
			'pstContent' => $this->pstContent,
			'output' => $this->output,
			'optionsHash' => $this->optionsHash,
			'timestamp' => $this->timestamp,
			'edits' => $this->edits,
		];
	}

	public static function newFromJsonArray( array $json ): PageEditStashContents {
		return new self(
			pstContent: $json['pstContent'],
			output: $json['output'],
			// Absent in v3 stash entries written before MW 1.47
			optionsHash: $json['optionsHash'] ?? null,
			timestamp: $json['timestamp'],
			edits: $json['edits'] ?? null,
		);
	}
}
