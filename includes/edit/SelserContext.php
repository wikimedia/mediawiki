<?php

namespace MediaWiki\Edit;

use MediaWiki\Content\Content;
use MediaWiki\MediaWikiServices;
use UnexpectedValueException;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\SelserData;

/**
 * Value object representing contextual information needed by Parsoid for selective serialization ("selser") of
 * modified HTML.
 *
 * @see SelserData
 *
 * @since 1.40
 */
class SelserContext implements JsonCodecable {
	use JsonCodecableTrait;

	public function __construct(
		private PageBundle $pageBundle,
		private int $revId,
		private ?Content $content = null
	) {
		if ( !$revId && !$content ) {
			throw new UnexpectedValueException(
				'If $revId is 0, $content must be given. ' .
				'If we can\'t load the content from a revision, we have to stash it.'
			);
		}
	}

	/**
	 * @return PageBundle
	 */
	public function getPageBundle(): PageBundle {
		return $this->pageBundle;
	}

	/**
	 * @return int
	 */
	public function getRevisionID(): int {
		return $this->revId;
	}

	/**
	 * @return Content|null
	 */
	public function getContent(): ?Content {
		return $this->content;
	}

	public function toJsonArray(): array {
		return [
			'revId' => $this->revId,
			'pb' => $this->pageBundle,
			// After I544625136088164561b9169a63aed7450cce82f5 this can be:
			// 'c' => $this->content,
			'content' => $this->content ? [
				'model' => $this->content->getModel(),
				'data' => $this->content->serialize(),
			] : null,
		];
	}

	public static function jsonClassHintFor( string $keyName ): ?string {
		if ( $keyName === 'pb' ) {
			return PageBundle::class;
		}
		return null;
	}

	public static function newFromJsonArray( array $json ): self {
		$revId = (int)$json['revId'];
		$pb = $json['pb'];
		if ( is_array( $pb ) ) {
			// Backward compatibility with old serialization format
			$pb = new PageBundle(
				$pb['html'],
				$pb['parsoid'] ?? null,
				$pb['mw'] ?? null,
				$pb['version'] ?? null,
				$pb['headers'] ?? null,
				$pb['contentmodel'] ?? null
			);
		}
		$content = $json['c'] ?? $json['content'] ?? null;
		if ( is_array( $content ) ) {
			// Backward compatibility with old serialization format
			$contentHandler = MediaWikiServices::getInstance()
				->getContentHandlerFactory()
				->getContentHandler( $content['model'] );
			$content = $contentHandler->unserializeContent( $content['data'] );
		}
		return new self( $pb, $revId, $content );
	}
}
