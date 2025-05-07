<?php

/**
 * Registry of flags used with ParserOutput::{getLinkList,appendLink}()
 * within MediaWiki core.
 *
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
