<?php
declare( strict_types = 1 );
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
		// Use PHP serialization of $pstContent
		return [
			'pstContent' => $this->pstContent,
			'output' => $this->output,
			'timestamp' => $this->timestamp,
			'edits' => $this->edits,
		];
	}

	public static function newFromJsonArray( array $json ): PageEditStashContents {
		return new self(
			// Backward compatibility: can remove this check
			// once stash expires from the last version which doesn't
			// include I1250afa5dc0a46020bf4e485bde37d7386d823e8
			// (Note that released versions of MW will never have this flag
			// set: MW 1.44 used PHP serialization and MW 1.45 will use full
			// JSON serialization.)
			pstContent: ( $json['pstContentUsesPHPSerialization'] ?? false ) ?
				unserialize( $json['pstContent'] ) : $json['pstContent'],
			output: $json['output'],
			timestamp: $json['timestamp'],
			edits: $json['edits'] ?? null,
		);
	}
}
