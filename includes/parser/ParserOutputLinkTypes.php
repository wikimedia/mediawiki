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
enum ParserOutputLinkTypes: string {

	/**
	 * Category links
	 * @see ParserOutput::addCategory
	 * @see ParserOutput::getCategoryMap
	 * @see ParserOutput::getCategoryNames
	 */
	case CATEGORY = 'category';

	/**
	 * Interwiki links
	 * @see ParserOutput::addInterwikiLink
	 */
	case INTERWIKI = 'interwiki';

	/**
	 * Language links
	 * @see ParserOutput::addLanguageLink
	 * @see ParserOutput::getLanguageLinks
	 */
	case LANGUAGE = 'language';

	/**
	 * @var string Local links
	 * @see ParserOutput::addLink
	 * @see ParserOutput::getLinks
	 */
	case LOCAL = 'local';

	/**
	 * Links to media
	 * @see ParserOutput::addImage
	 * @see ParserOutput::getImages
	 * @see ParserOutput::getFileSearchOptions
	 */
	case MEDIA = 'media';

	/**
	 * Links to special pages
	 * @see ParserOutput::addLink
	 * @see ParserOutput::getLinksSpecial
	 */
	case SPECIAL = 'special';

	/**
	 * Links to templates
	 * @see ParserOutput::addTemplate
	 * @see ParserOutput::getTemplates
	 * @see ParserOutput::getTemplateIds
	 */
	case TEMPLATE = 'template';

	/**
	 * #ifexist references
	 * @see ParserOutput::addExistenceDependency
	 */
	case EXISTENCE = 'existence';

	/**
	 * Return the ParserOutputLinkTypes, as an array of string flag values.
	 * @return list<string>
	 */
	public static function values(): array {
		return array_column( self::cases(), 'value' );
	}
}
