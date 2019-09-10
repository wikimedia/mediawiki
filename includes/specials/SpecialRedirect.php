<?php
/**
 * Implements Special:Redirect
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
 * @file
 * @ingroup SpecialPage
 */

use MediaWiki\MediaWikiServices;

/**
 * A special page that redirects to: the user for a numeric user id,
 * the file for a given filename, or the page for a given revision id.
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
	 * @var string $mType
	 */
	protected $mType;

	/**
	 * The identifier/value for the redirect (which id, which file)
	 *
	 * Example value: `'42'`
	 *
	 * @var string $mValue
	 */
	protected $mValue;

	function __construct() {
		parent::__construct( 'Redirect' );
		$this->mType = null;
		$this->mValue = null;
	}

	/**
	 * Set $mType and $mValue based on parsed value of $subpage.
	 * @param string $subpage
	 */
	function setParameter( $subpage ) {
		// parse $subpage to pull out the parts
		$parts = explode( '/', $subpage, 2 );
		$this->mType = $parts[0];
		$this->mValue = $parts[1] ?? null;
	}

	/**
	 * Handle Special:Redirect/user/xxxx (by redirecting to User:YYYY)
	 *
	 * @return Status A good status contains the url to redirect to
	 */
	function dispatchUser() {
		if ( !ctype_digit( $this->mValue ) ) {
			// Message: redirect-not-numeric
			return Status::newFatal( $this->getMessagePrefix() . '-not-numeric' );
		}
		$user = User::newFromId( (int)$this->mValue );
		$username = $user->getName(); // load User as side-effect
		if ( $user->isAnon() ) {
			// Message: redirect-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
		}
		if ( $user->isHidden() && !MediaWikiServices::getInstance()->getPermissionManager()
			->userHasRight( $this->getUser(), 'hideuser' )
		) {
			throw new PermissionsError( null, [ 'badaccess-group0' ] );
		}
		$userpage = Title::makeTitle( NS_USER, $username );

		return Status::newGood( [
			$userpage->getFullURL( '', false, PROTO_CURRENT ), 302
		] );
	}

	/**
	 * Handle Special:Redirect/file/xxxx
	 *
	 * @return Status A good status contains the url to redirect to
	 */
	function dispatchFile() {
		try {
			$title = Title::newFromTextThrow( $this->mValue, NS_FILE );
			if ( $title && !$title->inNamespace( NS_FILE ) ) {
				// If the given value contains a namespace enforce file namespace
				$title = Title::newFromTextThrow( Title::makeName( NS_FILE, $this->mValue ) );
			}
		} catch ( MalformedTitleException $e ) {
			return Status::newFatal( $e->getMessageObject() );
		}
		$file = MediaWikiServices::getInstance()->getRepoGroup()->findFile( $title );

		if ( !$file || !$file->exists() ) {
			// Message: redirect-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
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
	function dispatchRevision() {
		$oldid = $this->mValue;
		if ( !ctype_digit( $oldid ) ) {
			// Message: redirect-not-numeric
			return Status::newFatal( $this->getMessagePrefix() . '-not-numeric' );
		}
		$oldid = (int)$oldid;
		if ( $oldid === 0 ) {
			// Message: redirect-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
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
	function dispatchPage() {
		$curid = $this->mValue;
		if ( !ctype_digit( $curid ) ) {
			// Message: redirect-not-numeric
			return Status::newFatal( $this->getMessagePrefix() . '-not-numeric' );
		}
		$curid = (int)$curid;
		if ( $curid === 0 ) {
			// Message: redirect-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
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
	function dispatchLog() {
		$logid = $this->mValue;
		if ( !ctype_digit( $logid ) ) {
			// Message: redirect-not-numeric
			return Status::newFatal( $this->getMessagePrefix() . '-not-numeric' );
		}
		$logid = (int)$logid;
		if ( $logid === 0 ) {
			// Message: redirect-not-exists
			return Status::newFatal( $this->getMessagePrefix() . '-not-exists' );
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
	function dispatch() {
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
				list( $url, $code ) = $value;
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
		if ( !is_null( $this->mValue ) ) {
			$this->getOutput()->setStatusCode( 404 );

			return $status;
		}

		return false;
	}

	protected function getFormFields() {
		$mp = $this->getMessagePrefix();
		$ns = [
			// subpage => message
			// Messages: redirect-user, redirect-page, redirect-revision,
			// redirect-file, redirect-logid
			'user' => $mp . '-user',
			'page' => $mp . '-page',
			'revision' => $mp . '-revision',
			'file' => $mp . '-file',
			'logid' => $mp . '-logid',
		];
		$a = [];
		$a['type'] = [
			'type' => 'select',
			'label-message' => $mp . '-lookup', // Message: redirect-lookup
			'options' => [],
			'default' => current( array_keys( $ns ) ),
		];
		foreach ( $ns as $n => $m ) {
			$m = $this->msg( $m )->text();
			$a['type']['options'][$m] = $n;
		}
		$a['value'] = [
			'type' => 'text',
			'label-message' => $mp . '-value' // Message: redirect-value
		];
		// set the defaults according to the parsed subpage path
		if ( !empty( $this->mType ) ) {
			$a['type']['default'] = $this->mType;
		}
		if ( !empty( $this->mValue ) ) {
			$a['value']['default'] = $this->mValue;
		}

		return $a;
	}

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
		/* display summary at top of page */
		$this->outputHeader();
		// tweak label on submit button
		// Message: redirect-submit
		$form->setSubmitTextMsg( $this->getMessagePrefix() . '-submit' );
		/* submit form every time */
		$form->setMethod( 'get' );
	}

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
	public function requiresWrite() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function requiresUnblock() {
		return false;
	}

	protected function getGroupName() {
		return 'redirects';
	}
}
