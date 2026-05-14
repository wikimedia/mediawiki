<?php

namespace MediaWiki\ShadowPage;

use MediaWiki\Content\ContentHandlerFactory;
use MediaWiki\Language\Language;
use MediaWiki\Language\MessageCache;
use MediaWiki\Page\PageReference;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SlotRoleRegistry;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * The provider for NS_MEDIAWIKI
 *
 * @since 1.47
 */
class MessageProvider extends BaseShadowPageProvider {
	public function __construct(
		private MessageCache $cache,
		private Language $contLang,
		private SlotRoleRegistry $slotRoleRegistry,
		private ContentHandlerFactory $contentHandlerFactory,
	) {
	}

	public function get( PageReference $title ): ?ShadowPage {
		[ $name, $lang ] = $this->cache->figureMessage(
			$this->contLang->lcfirst( $title->getDBkey() )
		);

		$message = wfMessage( $name )->inLanguage( $lang )->useDatabase( false );
		if ( !$message->exists() ) {
			return null;
		}
		$text = $message->plain();
		$model = $this->slotRoleRegistry
			->getRoleHandler( SlotRecord::MAIN )
			->getDefaultModel( $title );
		$content = $this->contentHandlerFactory->getContentHandler( $model )
			->unserializeContent( $text );

		return new MessagePage( $this->getParseHelper(), $content, $title );
	}

	public function existsForLink( PageReference|LinkTarget $link ): bool {
		[ $name, $lang ] = $this->cache->figureMessage(
			$this->contLang->lcfirst( $link->getDBkey() )
		);

		return wfMessage( $name )->inLanguage( $lang )->useDatabase( false )->exists();
	}

}
