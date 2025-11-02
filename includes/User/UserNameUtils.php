<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\User;

use InvalidArgumentException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Language\Language;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\TitleParser;
use MediaWiki\User\TempUser\TempUserConfig;
use Psr\Log\LoggerInterface;
use Wikimedia\IPUtils;
use Wikimedia\Message\ITextFormatter;
use Wikimedia\Message\MessageValue;

/**
 * UserNameUtils service
 *
 * @since 1.35
 * @ingroup User
 * @author DannyS712
 */
class UserNameUtils implements UserRigorOptions {

	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::MaxNameChars,
		MainConfigNames::ReservedUsernames,
		MainConfigNames::InvalidUsernameCharacters
	];

	/**
	 * For use by isIP() and isLikeIPv4DashRange()
	 */
	private const IPV4_ADDRESS = '\d{1,3}\.\d{1,3}\.\d{1,3}\.(?:xxx|\d{1,3})';

	// RIGOR_* constants are inherited from UserRigorOptions

	// phpcs:ignore MediaWiki.Commenting.PropertyDocumentation.WrongStyle
	private ServiceOptions $options;
	private Language $contentLang;
	private LoggerInterface $logger;
	private TitleParser $titleParser;
	private ITextFormatter $textFormatter;

	/**
	 * @var string[]|false Cache for isUsable()
	 */
	private $reservedUsernames = false;

	private HookRunner $hookRunner;
	private TempUserConfig $tempUserConfig;

	/**
	 * @param ServiceOptions $options
	 * @param Language $contentLang
	 * @param LoggerInterface $logger
	 * @param TitleParser $titleParser
	 * @param ITextFormatter $textFormatter the text formatter for the current content language
	 * @param HookContainer $hookContainer
	 * @param TempUserConfig $tempUserConfig
	 */
	public function __construct(
		ServiceOptions $options,
		Language $contentLang,
		LoggerInterface $logger,
		TitleParser $titleParser,
		ITextFormatter $textFormatter,
		HookContainer $hookContainer,
		TempUserConfig $tempUserConfig
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->contentLang = $contentLang;
		$this->logger = $logger;
		$this->titleParser = $titleParser;
		$this->textFormatter = $textFormatter;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->tempUserConfig = $tempUserConfig;
	}

	/**
	 * Is the input a valid username?
	 *
	 * Checks if the input is a valid username, we don't want an empty string,
	 * an IP address, any type of IP range, anything that contains slashes
	 * (would mess up subpages), is longer than the maximum allowed username
	 * size or begins with a lowercase letter.
	 *
	 * @param string $name Name to match
	 * @return bool
	 */
	public function isValid( string $name ): bool {
		if ( $name === ''
			|| $this->isIP( $name )
			|| $this->isValidIPRange( $name )
			|| $this->isLikeIPv4DashRange( $name )
			|| str_contains( $name, '/' )
			|| strlen( $name ) > $this->options->get( MainConfigNames::MaxNameChars )
			|| $name !== $this->contentLang->ucfirst( $name )
		) {
			return false;
		}

		// Ensure that the name can't be misresolved as a different title,
		// such as with extra namespace keys at the start.
		try {
			$title = $this->titleParser->parseTitle( $name );
		} catch ( MalformedTitleException ) {
			$title = null;
		}

		if ( $title === null
			|| $title->getNamespace()
			|| $name !== $title->getText()
		) {
			return false;
		}

		// Check an additional list of troublemaker characters.
		// Should these be merged into the title char list?
		$unicodeList = '/[' .
			'\x{0080}-\x{009f}' . # iso-8859-1 control chars
			'\x{00a0}' . # non-breaking space
			'\x{2000}-\x{200f}' . # various whitespace
			'\x{2028}-\x{202f}' . # breaks and control chars
			'\x{3000}' . # ideographic space
			'\x{e000}-\x{f8ff}' . # private use
			']/u';
		if ( preg_match( $unicodeList, $name ) ) {
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
	public function isUsable( string $name ): bool {
		// Must be a valid username, obviously ;)
		if ( !$this->isValid( $name ) ) {
			return false;
		}

		if ( !$this->reservedUsernames ) {
			$reservedUsernames = $this->options->get( MainConfigNames::ReservedUsernames );
			$this->hookRunner->onUserGetReservedNames( $reservedUsernames );
			foreach ( $reservedUsernames as &$reserved ) {
				if ( str_starts_with( $reserved, 'msg:' ) ) {
					$reserved = $this->textFormatter->format(
						MessageValue::new( substr( $reserved, 4 ) )
					);
				}
			}
			$this->reservedUsernames = $reservedUsernames;
		}

		// Certain names may be reserved for batch processes.
		if ( in_array( $name, $this->reservedUsernames, true ) ) {
			return false;
		}

		// Treat this name as not usable if it is reserved by the temp user system and either:
		// * Temporary account creation is disabled
		// * The name is not a temporary account
		// This is necessary to ensure that CentralAuth auto-creation will be denied (T342475).
		if (
			$this->isTempReserved( $name ) &&
			( !$this->tempUserConfig->isEnabled() || !$this->isTemp( $name ) )
		) {
			return false;
		}

		return true;
	}

	/**
	 * Usernames which fail to pass this function will be blocked
	 * from new account registrations, but may be used internally
	 * either by batch processes or by user accounts which have
	 * already been created.
	 *
	 * Additional preventions may be added here rather than in
	 * isValid() to avoid disrupting existing accounts.
	 *
	 * @param string $name String to match
	 * @return bool
	 */
	public function isCreatable( string $name ): bool {
		// Ensure that the username isn't longer than 235 bytes, so that
		// (at least for the builtin skins) user javascript and css files
		// will work. (T25080)
		if ( strlen( $name ) > 235 ) {
			$this->logger->debug(
				__METHOD__ . ": '$name' uncreatable due to length"
			);
			return false;
		}

		$invalid = $this->options->get( MainConfigNames::InvalidUsernameCharacters );
		// Preg yells if you try to give it an empty string
		if ( $invalid !== '' &&
			preg_match( '/[' . preg_quote( $invalid, '/' ) . ']/', $name )
		) {
			$this->logger->debug(
				__METHOD__ . ": '$name' uncreatable due to wgInvalidUsernameCharacters"
			);
			return false;
		}

		if ( $this->isTempReserved( $name ) ) {
			$this->logger->debug(
				__METHOD__ . ": '$name' uncreatable due to TempUserConfig"
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
	 * @return string|false
	 */
	public function getCanonical( string $name, string $validate = self::RIGOR_VALID ) {
		// Force usernames to capital
		$name = $this->contentLang->ucfirst( $name );

		// Reject names containing '#'; these will be cleaned up
		// with title normalisation, but then it's too late to
		// check elsewhere
		if ( str_contains( $name, '#' ) ) {
			return false;
		}

		// No need to proceed if no validation is requested, just
		// clean up underscores and user namespace prefix (see T283915).
		if ( $validate === self::RIGOR_NONE ) {
			// This is only needed here because if validation is
			// not self::RIGOR_NONE, it would be done at title parsing stage.
			$nsPrefix = $this->contentLang->getNsText( NS_USER ) . ':';
			if ( str_starts_with( $name, $nsPrefix ) ) {
				$name = str_replace( $nsPrefix, '', $name );
			}
			$name = strtr( $name, '_', ' ' );
			return $name;
		}

		// Clean up name according to title rules,
		// but only when validation is requested (T14654)
		try {
			$title = $this->titleParser->parseTitle( $name, NS_USER );
		} catch ( MalformedTitleException ) {
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
				return $this->isValid( $name ) ? $name : false;
			case self::RIGOR_USABLE:
				return $this->isUsable( $name ) ? $name : false;
			case self::RIGOR_CREATABLE:
				return $this->isCreatable( $name ) ? $name : false;
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
	 * This does //not// match IP ranges. See also T239527.
	 *
	 * @param string $name Name to check
	 * @return bool
	 */
	public function isIP( string $name ): bool {
		$anyIPv4 = '/^' . self::IPV4_ADDRESS . '$/';
		$validIP = IPUtils::isValid( $name );
		return $validIP || preg_match( $anyIPv4, $name );
	}

	/**
	 * Wrapper for IPUtils::isValidRange
	 *
	 * @param string $range Range to check
	 * @return bool
	 */
	public function isValidIPRange( string $range ): bool {
		return IPUtils::isValidRange( $range );
	}

	/**
	 * Validates IPv4 and IPv4-like ranges in the form of 1.2.3.4-5.6.7.8,
	 * (which we'd like to avoid as a username/title pattern).
	 *
	 * @since 1.42
	 * @param string $range IPv4 dash range to check
	 * @return bool
	 */
	public function isLikeIPv4DashRange( string $range ): bool {
		return preg_match(
			'/^' . self::IPV4_ADDRESS . '-' . self::IPV4_ADDRESS . '$/',
			$range
		);
	}

	/**
	 * Does the username indicate a temporary user?
	 *
	 * @since 1.39
	 * @param string $name
	 * @return bool
	 */
	public function isTemp( string $name ) {
		return $this->tempUserConfig->isTempName( $name );
	}

	/**
	 * Is the username uncreatable due to it being reserved by the temp username
	 * system? Note that unlike isTemp(), this does not imply that a user having
	 * this name is an actual temp account. This should only be used to deny
	 * account creation.
	 *
	 * @since 1.41
	 * @param string $name
	 * @return bool
	 */
	public function isTempReserved( string $name ) {
		return $this->tempUserConfig->isReservedName( $name );
	}

	/**
	 * Get a placeholder name for a temporary user before serial acquisition
	 *
	 * This method throws if temporary users are not enabled, and you can't check whether they are or
	 * not from this class, so you have to check from the TempUserConfig class first, and then you
	 * might as well use TempUserConfig::getPlaceholderName() directly.
	 *
	 * @since 1.39
	 * @deprecated since 1.45 Use TempUserConfig::getPlaceholderName() instead
	 * @return string
	 */
	public function getTempPlaceholder() {
		wfDeprecated( __METHOD__, '1.45' );
		return $this->tempUserConfig->getPlaceholderName();
	}
}
