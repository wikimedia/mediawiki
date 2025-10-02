<?php

/**
 * Registry of flags used with ParserOutput::{getLinkList,appendLink}()
 * within MediaWiki core.
 *
 * @license GPL-2.0-or-later
 * @since 1.43
 *
 * @file
 * @ingroup Parser
 */

namespace MediaWiki\Parser;

/**
 * Registry of flags used with ParserOutput::{getLinkList,appendLink}()
 * within MediaWiki core.
 *
 * All link types used should be defined in this class.
 *
 * @package MediaWiki\Parser
 */
class ParserOutputLinkTypes {

	/**
	 * Category links
	 * @see ParserOutput::addCategory
	 * @see ParserOutput::getCategoryMap
	 * @see ParserOutput::getCategoryNames
	 */
	public const CATEGORY = 'category';

	/**
	 * Interwiki links
	 * @see ParserOutput::addInterwikiLink
	 * @see ParserOutput::getInterwikiLinks
	 */
	public const INTERWIKI = 'interwiki';

	/**
	 * Language links
	 * @see ParserOutput::addLanguageLink
	 * @see ParserOutput::getLanguageLinks
	 */
	public const LANGUAGE = 'language';

	/**
	 * @var string Local links
	 * @see ParserOutput::addLink
	 * @see ParserOutput::getLinks
	 */
	public const LOCAL = 'local';

	/**
	 * Links to media
	 * @see ParserOutput::addImage
	 * @see ParserOutput::getImages
	 * @see ParserOutput::getFileSearchOptions
	 */
	public const MEDIA = 'media';

	/**
	 * Links to special pages
	 * @see ParserOutput::addLink
	 * @see ParserOutput::getLinksSpecial
	 */
	public const SPECIAL = 'special';

	/**
	 * Links to templates
	 * @see ParserOutput::addTemplate
	 * @see ParserOutput::getTemplates
	 * @see ParserOutput::getTemplateIds
	 */
	public const TEMPLATE = 'template';

	/**
	 * #ifexist references
	 * @see ParserOutput::addExistenceDependency
	 */
	public const EXISTENCE = 'existence';

	public static function cases(): array {
		return [
			self::CATEGORY,
			self::INTERWIKI,
			self::LANGUAGE,
			self::LOCAL,
			self::MEDIA,
			self::SPECIAL,
			self::TEMPLATE,
		];
	}
}
