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
		/** TS_MW */
		public readonly string $timestamp,
		public readonly ?int $edits,
	) {
	}

	public function toJsonArray(): array {
		return [
			'pstContent' => $this->pstContent,
			'output' => $this->output,
			'timestamp' => $this->timestamp,
			'edits' => $this->edits,
		];
	}

	public static function newFromJsonArray( array $json ): PageEditStashContents {
		return new self(
			pstContent: $json['pstContent'],
			output: $json['output'],
			timestamp: $json['timestamp'],
			edits: $json['edits'] ?? null,
		);
	}
}
