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
 */

namespace MediaWiki\Mail;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Permissions\Authority;
use MediaWiki\User\CentralId\CentralIdLookup;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserOptionsLookup;
use Wikimedia\Message\IMessageFormatterFactory;
use Wikimedia\Message\ITextFormatter;

/**
 * Factory for EmailUser objects.
 *
 * @since 1.41
 */
class EmailUserFactory {
	/** @var ServiceOptions */
	private ServiceOptions $options;
	/** @var HookContainer */
	private HookContainer $hookContainer;
	/** @var UserOptionsLookup */
	private UserOptionsLookup $userOptionsLookup;
	/** @var CentralIdLookup */
	private CentralIdLookup $centralIdLookup;
	/** @var UserFactory */
	private UserFactory $userFactory;
	/** @var IEmailer */
	private IEmailer $emailer;
	/** @var IMessageFormatterFactory */
	private IMessageFormatterFactory $messageFormatterFactory;
	/** @var ITextFormatter */
	private ITextFormatter $contLangMsgFormatter;

	/**
	 * @param ServiceOptions $options
	 * @param HookContainer $hookContainer
	 * @param UserOptionsLookup $userOptionsLookup
	 * @param CentralIdLookup $centralIdLookup
	 * @param UserFactory $userFactory
	 * @param IEmailer $emailer
	 * @param IMessageFormatterFactory $messageFormatterFactory
	 * @param ITextFormatter $contLangMsgFormatter
	 */
	public function __construct(
		ServiceOptions $options,
		HookContainer $hookContainer,
		UserOptionsLookup $userOptionsLookup,
		CentralIdLookup $centralIdLookup,
		UserFactory $userFactory,
		IEmailer $emailer,
		IMessageFormatterFactory $messageFormatterFactory,
		ITextFormatter $contLangMsgFormatter
	) {
		$options->assertRequiredOptions( EmailUser::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->hookContainer = $hookContainer;
		$this->userOptionsLookup = $userOptionsLookup;
		$this->centralIdLookup = $centralIdLookup;
		$this->userFactory = $userFactory;
		$this->emailer = $emailer;
		$this->messageFormatterFactory = $messageFormatterFactory;
		$this->contLangMsgFormatter = $contLangMsgFormatter;
	}

	/**
	 * @param Authority $sender
	 * @return EmailUser
	 */
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
	 * @param Authority $sender
	 * @param Config|null $config
	 * @return EmailUser
	 */
	public function newEmailUserBC( Authority $sender, Config $config = null ): EmailUser {
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
