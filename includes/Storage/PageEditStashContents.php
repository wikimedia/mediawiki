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
		public Content $pstContent,
		public ParserOutput $output,
		public string $timestamp,
		public ?int $edits,
	) {
	}

	public function toJsonArray(): array {
		// Use PHP serialization of $pstContent
		// @TODO T398656 use JSON serialization of $pstContent instead.
		return [
			'pstContent' => serialize( $this->pstContent ),
			// forward-compatibility T398656
			'pstContentUsesPHPSerialization' => true,
			'output' => $this->output,
			'timestamp' => $this->timestamp,
			'edits' => $this->edits,
		];
	}

	public static function newFromJsonArray( array $json ): PageEditStashContents {
		return new self(
			pstContent: ( $json['pstContentUsesPHPSerialization'] ?? false ) ?
				unserialize( $json['pstContent'] ) : $json['pstContent'],
			output: $json['output'],
			timestamp: $json['timestamp'],
			edits: $json['edits'] ?? null,
		);
	}
}
