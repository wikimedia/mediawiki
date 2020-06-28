<?php

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
 * @author DannyS712
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use Language;
use MalformedTitleException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use Psr\Log\LoggerInterface;
use TitleParser;
use Wikimedia\IPUtils;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * UserNameUtils service
 *
 * @since 1.35
 */
class UserNameUtils {

	public const CONSTRUCTOR_OPTIONS = [
		'MaxNameChars',
		'ReservedUsernames',
		'InvalidUsernameCharacters'
	];

	public const RIGOR_CREATABLE = 'creatable';
	public const RIGOR_USABLE = 'usable';
	public const RIGOR_VALID = 'valid';
	public const RIGOR_NONE = 'none';

	/**
	 * @var ServiceOptions
	 */
	private $options;

	/**
	 * @var Language
	 */
	private $contentLang;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var TitleParser
	 */
	private $titleParser;

	/**
	 * @var ITextFormatter
	 */
	private $textFormatter;

	/**
	 * @var string[]|false Cache for isUsable()
	 */
	private $reservedUsernames = false;

	/**
	 * @var HookRunner
	 */
	private $hookRunner;

	/**
	 * @param ServiceOptions $options
	 * @param Language $contentLang
	 * @param LoggerInterface $logger
	 * @param TitleParser $titleParser
	 * @param ITextFormatter $textFormatter the text formatter for the current content language
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		ServiceOptions $options,
		Language $contentLang,
		LoggerInterface $logger,
		TitleParser $titleParser,
		ITextFormatter $textFormatter,
		HookContainer $hookContainer
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->contentLang = $contentLang;
		$this->logger = $logger;
		$this->titleParser = $titleParser;
		$this->textFormatter = $textFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Is the input a valid username?
	 *
	 * Checks if the input is a valid username, we don't want an empty string,
	 * an IP address, anything that contains slashes (would mess up subpages),
	 * is longer than the maximum allowed username size or doesn't begin with
	 * a capital letter.
	 *
	 * @param string $name Name to match
	 * @return bool
	 */
	public function isValid( string $name ) : bool {
		if ( $name === ''
			|| $this->isIP( $name )
			|| strpos( $name, '/' ) !== false
			|| strlen( $name ) > $this->options->get( 'MaxNameChars' )
			|| $name !== $this->contentLang->ucfirst( $name )
		) {
			return false;
		}

		// Ensure that the name can't be misresolved as a different title,
		// such as with extra namespace keys at the start.
		try {
			$title = $this->titleParser->parseTitle( $name );
		} catch ( MalformedTitleException $_ ) {
			$title = null;
		}

		if ( $title === null
			|| $title->getNamespace()
			|| strcmp( $name, $title->getText() )
		) {
			return false;
		}

		// Check an additional blacklist of troublemaker characters.
		// Should these be merged into the title char list?
		$unicodeBlacklist = '/[' .
			'\x{0080}-\x{009f}' . # iso-8859-1 control chars
			'\x{00a0}' . # non-breaking space
			'\x{2000}-\x{200f}' . # various whitespace
			'\x{2028}-\x{202f}' . # breaks and control chars
			'\x{3000}' . # ideographic space
			'\x{e000}-\x{f8ff}' . # private use
			']/u';
		if ( preg_match( $unicodeBlacklist, $name ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Usernames which fail to pass this function will be blocked
	 * from user login and new account registrations, but may be used
	 * internally by batch processes.
	 *
	 * If an account already exists in this form, login will be blocked
	 * by a failure to pass this function.
	 *
	 * @param string $name Name to match
	 * @return bool
	 */
	public function isUsable( string $name ) : bool {
		// Must be a valid username, obviously ;)
		if ( !$this->isValid( $name ) ) {
			return false;
		}

		if ( !$this->reservedUsernames ) {
			$reservedUsernames = $this->options->get( 'ReservedUsernames' );
			$this->hookRunner->onUserGetReservedNames( $reservedUsernames );
			$this->reservedUsernames = $reservedUsernames;
		}

		// Certain names may be reserved for batch processes.
		foreach ( $this->reservedUsernames as $reserved ) {
			if ( substr( $reserved, 0, 4 ) === 'msg:' ) {
				$reserved = $this->textFormatter->format(
					MessageValue::new( substr( $reserved, 4 ) )
				);
			}
			if ( $reserved === $name ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Usernames which fail to pass this function will be blocked
	 * from new account registrations, but may be used internally
	 * either by batch processes or by user accounts which have
	 * already been created.
	 *
	 * Additional blacklisting may be added here rather than in
	 * isValidUserName() to avoid disrupting existing accounts.
	 *
	 * @param string $name String to match
	 * @return bool
	 */
	public function isCreatable( string $name ) : bool {
		// Ensure that the username isn't longer than 235 bytes, so that
		// (at least for the builtin skins) user javascript and css files
		// will work. (T25080)
		if ( strlen( $name ) > 235 ) {
			$this->logger->debug(
				__METHOD__ . ": '$name' uncreatable due to length"
			);
			return false;
		}

		$invalid = $this->options->get( 'InvalidUsernameCharacters' );
		// Preg yells if you try to give it an empty string
		if ( $invalid !== '' &&
			preg_match( '/[' . preg_quote( $invalid, '/' ) . ']/', $name )
		) {
			$this->logger->debug(
				__METHOD__ . ": '$name' uncreatable due to wgInvalidUsernameCharacters"
			);
			return false;
		}

		return $this->isUsable( $name );
	}

	/**
	 * Given unvalidated user input, return a canonical username, or false if
	 * the username is invalid.
	 * @param string $name User input
	 * @param string $validate Type of validation to use
	 *   Use of public constants RIGOR_* is preferred
	 *   - RIGOR_NONE        No validation
	 *   - RIGOR_VALID       Valid for batch processes
	 *   - RIGOR_USABLE      Valid for batch processes and login
	 *   - RIGOR_CREATABLE   Valid for batch processes, login and account creation
	 *
	 * @throws InvalidArgumentException
	 * @return bool|string
	 */
	public function getCanonical( string $name, string $validate = self::RIGOR_VALID ) {
		// Force usernames to capital
		$name = $this->contentLang->ucfirst( $name );

		// Reject names containing '#'; these will be cleaned up
		// with title normalisation, but then it's too late to
		// check elsewhere
		if ( strpos( $name, '#' ) !== false ) {
			return false;
		}

		// No need to proceed if no validation is requested, just
		// clean up underscores and return
		if ( $validate === self::RIGOR_NONE ) {
			$name = strtr( $name, '_', ' ' );
			return $name;
		}

		// Clean up name according to title rules,
		// but only when validation is requested (T14654)
		try {
			$title = $this->titleParser->parseTitle( $name, NS_USER );
		} catch ( MalformedTitleException $_ ) {
			$title = null;
		}

		// Check for invalid titles
		if ( $title === null
			|| $title->getNamespace() !== NS_USER
			|| $title->isExternal()
		) {
			return false;
		}

		$name = $title->getText();

		// RIGOR_NONE handled above
		switch ( $validate ) {
			case self::RIGOR_VALID:
				if ( !$this->isValid( $name ) ) {
					return false;
				}
				return $name;
			case self::RIGOR_USABLE:
				if ( !$this->isUsable( $name ) ) {
					return false;
				}
				return $name;
			case self::RIGOR_CREATABLE:
				if ( !$this->isCreatable( $name ) ) {
					return false;
				}
				return $name;
			default:
				throw new InvalidArgumentException(
					"Invalid parameter value for validation ($validate) in " .
					__METHOD__
				);
		}
	}

	/**
	 * Does the string match an anonymous IP address?
	 *
	 * This function exists for username validation, in order to reject
	 * usernames which are similar in form to IP addresses. Strings such
	 * as 300.300.300.300 will return true because it looks like an IP
	 * address, despite not being strictly valid.
	 *
	 * We match "\d{1,3}\.\d{1,3}\.\d{1,3}\.xxx" as an anonymous IP
	 * address because the usemod software would "cloak" anonymous IP
	 * addresses like this, if we allowed accounts like this to be created
	 * new users could get the old edits of these anonymous users.
	 *
	 * Unlike User::isIP, this does //not// match IPv6 ranges (T239527)
	 *
	 * @param string $name Name to check
	 * @return bool
	 */
	public function isIP( string $name ) : bool {
		$anyIPv4 = '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})$/';
		$validIP = IPUtils::isValid( $name );
		return $validIP || preg_match( $anyIPv4, $name );
	}

	/**
	 * Wrapper for IPUtils::isValidRange
	 *
	 * @param string $range Range to check
	 * @return bool
	 */
	public function isValidIPRange( string $range ) : bool {
		return IPUtils::isValidRange( $range );
	}

}
