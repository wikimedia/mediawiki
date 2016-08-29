<?php

namespace MediaWiki\MessagePoster;

use MWException;
use ReflectionClass;
use Title;

/**
 * Registry for IMessagePoster objects. This provides a pluggable to way to script the action
 * of adding a message to someone's talk page.  The IMessagePoster does all permission
 * checks.
 */
class MessagePosterRegistry {
	/**
	 * @var array Mapping of content model to ReflectionClass
	 */
	protected $contentModelToClass = [];

	// Note: This registration scheme is currently not compatible with LQT, since that doesn't
	// have its own content model, just islqttalkpage. LQT pages will be passed to the wikitext
	// IMessagePoster.
	/**
	 * Register an IMessagePoster implementation for a given content model.
	 *
	 * @param string $contentModel Content model of pages this IMessagePoster can post to
	 * @param string $className Name of a class implementing IMessagePoster
	 */
	public function register ( $contentModel, $className ) {
		if ( isset( $this->contentModelToClass[$contentModel] ) ) {
			throw new MWException( 'Content model "' . $contentModel . '" is already registered' );
		}

		$this->contentModelToClass[$contentModel] = new ReflectionClass( $className );
	}

	/**
	 * Unregister a given content model.
	 * This is exposed for testing and should not normally be used.
	 *
	 * @param string $contentModel Content model to unregister
	 */
	public function unregister( $contentModel ) {
		unset( $this->contentModelToClass[$contentModel] );
	}

	/**
	 * Create an IMessagePoster for a given title.
	 *
	 * It works by determining the content model and constructing the appropriate
	 * IMessagePoster object for the given title.
	 *
	 * @param Title $title Title that will be posted to
	 * @return MediaWiki\IMessagePoster\IMessagePoster
	 * @throws MWException If the content model is not registered
	 */
	public function getMessagePoster( $title ) {
		$contentModel = $title->getContentModel( Title::GAID_FOR_UPDATE );
		return $this->createForContentModel(
			$contentModel,
			$title
		);
	}

	/**
	 * Creates an IMessagePoster instance, given a title and content model
	 *
	 * @param string $contentModel Content model of title
	 * @param Title $title Title being posted to
	 * @return IMessagePoster
	 * @throws MWException If the content model is not registered
	 */
	protected function createForContentModel( $contentModel, $title ) {
		if( !isset( $this->contentModelToClass[$contentModel] ) ) {
			$prefixedDbText = $title->getPrefixedDBkey();

			throw new MWException( '"' . $prefixedDbText . '" has the content model "' . $contentModel . '", which is not registered' );
		}

		return $this->contentModelToClass[$contentModel]->newInstance( $title );
	}
}
