<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Mail;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserFactory;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;

/**
 * Factory for EmailUser objects.
 *
 * Obtain via ServiceWiring.
 *
 * @since 1.41
 * @ingroup Mail
 */
class EmailUserFactory {

	/**
	 * @internal For use by ServiceWiring only.
	 */
	public function __construct(
		private readonly ServiceOptions $options,
		private readonly HookContainer $hookContainer,
		private readonly UserOptionsLookup $userOptionsLookup,
		private readonly CentralIdLookup $centralIdLookup,
		private readonly UserFactory $userFactory,
		private readonly IEmailer $emailer,
		private readonly IMessageFormatterFactory $messageFormatterFactory,
		private readonly ITextFormatter $contLangMsgFormatter,
	) {
		$options->assertRequiredOptions( EmailUser::CONSTRUCTOR_OPTIONS );
	}

	public function newEmailUser( Authority $sender ): EmailUser {
		return new EmailUser(
			$this->options,
			$this->hookContainer,
			$this->userOptionsLookup,
			$this->centralIdLookup,
			$this->userFactory,
			$this->emailer,
			$this->messageFormatterFactory,
			$this->contLangMsgFormatter,
			$sender
		);
	}

	/**
	 * @internal Temporary BC method for SpecialEmailUser
	 */
	public function newEmailUserBC( Authority $sender, ?Config $config = null ): EmailUser {
		$options = $config ? new ServiceOptions( EmailUser::CONSTRUCTOR_OPTIONS, $config ) : $this->options;
		return new EmailUser(
			$options,
			$this->hookContainer,
			$this->userOptionsLookup,
			$this->centralIdLookup,
			$this->userFactory,
			$this->emailer,
			$this->messageFormatterFactory,
			$this->contLangMsgFormatter,
			$sender
		);
	}
}
