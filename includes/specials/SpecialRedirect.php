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

namespace MediaWiki\Specials;

use MediaWiki\Exception\PermissionsError;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;

/**
 * Redirect dispatcher for user IDs, thumbnails, and various permalinks.
 *
 * - user: the user page for a given numeric user ID.
 * - file: the file thumbnail URL for a given filename.
 * - revision: permalink for any revision.
 * - page: permalink for page by numeric page ID.
 * - logid: permalink for any log entry.
 *
 * @ingroup SpecialPage
 * @since 1.22
 */
class SpecialRedirect extends FormSpecialPage {

	/**
	 * The type of the redirect (user/file/revision)
	 *
	 * Example value: `'user'`
	 *
	 * @var string|null
	 */
	protected $mType;

	/**
	 * The identifier/value for the redirect (which id, which file)
	 *
	 * Example value: `'42'`
	 *
	 * @var string|null
	 */
	protected $mValue;

	private RepoGroup $repoGroup;
	private UserFactory $userFactory;

	public function __construct(
		RepoGroup $repoGroup,
		UserFactory $userFactory
	) {
		parent::__construct( 'Redirect' );
		$this->mType = null;
		$this->mValue = null;

		$this->repoGroup = $repoGroup;
		$this->userFactory = $userFactory;
	}

	/**
	 * Set $mType and $mValue based on parsed value of $subpage.
	 * @param string|null $subpage
	 */
	public function setParameter( $subpage ) {
		// parse $subpage to pull out the parts
		$parts = $subpage !== null ? explode( '/', $subpage, 2 ) : [];
		$this->mType = $parts[0] ?? null;
		$this->mValue = $parts[1] ?? null;
	}

	/**
	 * Handle Special:Redirect/user/xxxx (by redirecting to User:YYYY)
	 *
	 * @return Status A good status contains the url to redirect to
	 */
	public function dispatchUser() {
		if ( !ctype_digit( $this->mValue ) ) {
			return Status::newFatal( 'redirect-not-numeric' );
		}
		$user = $this->userFactory->newFromId( (int)$this->mValue );
		$user->load(); // Make sure the id is validated by loading the user
		if ( $user->isAnon() ) {
			return Status::newFatal( 'redirect-not-exists' );
		}
		if ( $user->isHidden() && !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			throw new PermissionsError( null, [ 'badaccess-group0' ] );
		}

		return Status::newGood( [
			$user->getUserPage()->getFullURL( '', false, PROTO_CURRENT ), 302
		] );
	}

	/**
	 * Handle Special:Redirect/file/xxxx
	 *
	 * @return Status A good status contains the url to redirect to
	 */
	public function dispatchFile() {
		try {
			$title = Title::newFromTextThrow( $this->mValue, NS_FILE );
			if ( $title && !$title->inNamespace( NS_FILE ) ) {
				// If the given value contains a namespace enforce file namespace
				$title = Title::newFromTextThrow( Title::makeName( NS_FILE, $this->mValue ) );
			}
		} catch ( MalformedTitleException $e ) {
			return Status::newFatal( $e->getMessageObject() );
		}
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable False positive
		$file = $this->repoGroup->findFile( $title );

		if ( !$file || !$file->exists() ) {
			return Status::newFatal( 'redirect-not-exists' );
		}
		// Default behavior: Use the direct link to the file.
		$url = $file->getUrl();
		$request = $this->getRequest();
		$width = $request->getInt( 'width', -1 );
		$height = $request->getInt( 'height', -1 );

		// If a width is requested...
		if ( $width != -1 ) {
			$mto = $file->transform( [ 'width' => $width, 'height' => $height ] );
			// ... and we can
			if ( $mto && !$mto->isError() ) {
				// ... change the URL to point to a thumbnail.
				// Note: This url is more temporary as can change
				// if file is reuploaded and has different aspect ratio.
				$url = [ $mto->getUrl(), $height === -1 ? 301 : 302 ];
			}
		}

		return Status::newGood( $url );
	}

	/**
	 * Handle Special:Redirect/revision/xxx
	 * (by redirecting to index.php?oldid=xxx)
	 *
	 * @return Status A good status contains the url to redirect to
	 */
	public function dispatchRevision() {
		$oldid = $this->mValue;
		if ( !ctype_digit( $oldid ) ) {
			return Status::newFatal( 'redirect-not-numeric' );
		}
		$oldid = (int)$oldid;
		if ( $oldid === 0 ) {
			return Status::newFatal( 'redirect-not-exists' );
		}

		return Status::newGood( wfAppendQuery( wfScript( 'index' ), [
			'oldid' => $oldid
		] ) );
	}

	/**
	 * Handle Special:Redirect/page/xxx (by redirecting to index.php?curid=xxx)
	 *
	 * @return Status A good status contains the url to redirect to
	 */
	public function dispatchPage() {
		$curid = $this->mValue;
		if ( !ctype_digit( $curid ) ) {
			return Status::newFatal( 'redirect-not-numeric' );
		}
		$curid = (int)$curid;
		if ( $curid === 0 ) {
			return Status::newFatal( 'redirect-not-exists' );
		}

		return Status::newGood( wfAppendQuery( wfScript( 'index' ), [
			'curid' => $curid
		] ) );
	}

	/**
	 * Handle Special:Redirect/logid/xxx
	 * (by redirecting to index.php?title=Special:Log&logid=xxx)
	 *
	 * @since 1.27
	 * @return Status A good status contains the url to redirect to
	 */
	public function dispatchLog() {
		$logid = $this->mValue;
		if ( !ctype_digit( $logid ) ) {
			return Status::newFatal( 'redirect-not-numeric' );
		}
		$logid = (int)$logid;
		if ( $logid === 0 ) {
			return Status::newFatal( 'redirect-not-exists' );
		}
		$query = [ 'title' => 'Special:Log', 'logid' => $logid ];
		return Status::newGood( wfAppendQuery( wfScript( 'index' ), $query ) );
	}

	/**
	 * Use appropriate dispatch* method to obtain a redirection URL,
	 * and either: redirect, set a 404 error code and error message,
	 * or do nothing (if $mValue wasn't set) allowing the form to be
	 * displayed.
	 *
	 * @return Status|bool True if a redirect was successfully handled.
	 */
	private function dispatch() {
		// the various namespaces supported by Special:Redirect
		switch ( $this->mType ) {
			case 'user':
				$status = $this->dispatchUser();
				break;
			case 'file':
				$status = $this->dispatchFile();
				break;
			case 'revision':
				$status = $this->dispatchRevision();
				break;
			case 'page':
				$status = $this->dispatchPage();
				break;
			case 'logid':
				$status = $this->dispatchLog();
				break;
			default:
				$status = null;
				break;
		}
		if ( $status && $status->isGood() ) {
			// These urls can sometimes be linked from prominent places,
			// so varnish cache.
			$value = $status->getValue();
			if ( is_array( $value ) ) {
				[ $url, $code ] = $value;
			} else {
				$url = $value;
				$code = 301;
			}
			if ( $code === 301 ) {
				$this->getOutput()->setCdnMaxage( 60 * 60 );
			} else {
				$this->getOutput()->setCdnMaxage( 10 );
			}
			$this->getOutput()->redirect( $url, $code );

			return true;
		}
		if ( $this->mValue !== null ) {
			$this->getOutput()->setStatusCode( 404 );

			// @phan-suppress-next-line PhanTypeMismatchReturnNullable Null of $status seems unreachable
			return $status;
		}

		return false;
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [
			'type' => [
				'type' => 'select',
				'label-message' => 'redirect-lookup',
				'options-messages' => [
					'redirect-user' => 'user',
					'redirect-page' => 'page',
					'redirect-revision' => 'revision',
					'redirect-file' => 'file',
					'redirect-logid' => 'logid',
				],
				'default' => $this->mType,
			],
			'value' => [
				'type' => 'text',
				'label-message' => 'redirect-value',
				'default' => $this->mValue,
				'required' => true,
			],
		];
	}

	/** @inheritDoc */
	public function onSubmit( array $data ) {
		if ( !empty( $data['type'] ) && !empty( $data['value'] ) ) {
			$this->setParameter( $data['type'] . '/' . $data['value'] );
		}

		/* if this returns false, will show the form */
		return $this->dispatch();
	}

	public function onSuccess() {
		/* do nothing, we redirect in $this->dispatch if successful. */
	}

	protected function alterForm( HTMLForm $form ) {
		// tweak label on submit button
		$form->setSubmitTextMsg( 'redirect-submit' );
	}

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	protected function getSubpagesForPrefixSearch() {
		return [
			'file',
			'page',
			'revision',
			'user',
			'logid',
		];
	}

	/**
	 * @return bool
	 */
	public function requiresPost() {
		return false;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'redirects';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRedirect::class, 'SpecialRedirect' );
