<?php
/**
 * Implements Special:Redirect
 *
 * @section LICENSE
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
	 * @var string $mType
	 * @example 'user'
	 */
	protected $mType;

	/**
	 * The identifier/value for the redirect (which id, which file)
	 *
	 * @var string $mValue
	 * @example '42'
	 */
	protected $mValue;

	function __construct() {
		parent::__construct( 'Redirect' );
		$this->mType = null;
		$this->mValue = null;
	}

	/**
	 * Set $mType and $mValue based on parsed value of $subpage.
	 */
	function setParameter( $subpage ) {
		// parse $subpage to pull out the parts
		$parts = explode( '/', $subpage, 2 );
		$this->mType = count( $parts ) > 0 ? $parts[0] : null;
		$this->mValue = count( $parts ) > 1 ? $parts[1] : null;
	}

	/**
	 * Handle Special:Redirect/user/xxxx (by redirecting to User:YYYY)
	 *
	 * @return string|null url to redirect to, or null if $mValue is invalid.
	 */
	function dispatchUser() {
		if ( !ctype_digit( $this->mValue ) ) {
			return null;
		}
		$user = User::newFromId( (int)$this->mValue );
		$username = $user->getName(); // load User as side-effect
		if ( $user->isAnon() ) {
			return null;
		}
		$userpage = Title::makeTitle( NS_USER, $username );
		return $userpage->getFullURL( '', false, PROTO_CURRENT );
	}

	/**
	 * Handle Special:Redirect/file/xxxx
	 *
	 * @return string|null url to redirect to, or null if $mValue is not found.
	 */
	function dispatchFile() {
		$title = Title::makeTitleSafe( NS_FILE, $this->mValue );

		if ( ! $title instanceof Title ) {
			return null;
		}
		$file = wfFindFile( $title );

		if ( !$file || !$file->exists() ) {
			return null;
		}
		// Default behavior: Use the direct link to the file.
		$url = $file->getURL();
		$request = $this->getRequest();
		$width = $request->getInt( 'width', -1 );
		$height = $request->getInt( 'height', -1 );

		// If a width is requested...
		if ( $width != -1 ) {
			$mto = $file->transform( array( 'width' => $width, 'height' => $height ) );
			// ... and we can
			if ( $mto && !$mto->isError() ) {
				// ... change the URL to point to a thumbnail.
				$url = $mto->getURL();
			}
		}
		return $url;
	}

	/**
	 * Handle Special:Redirect/revision/xxx
	 * (by redirecting to index.php?oldid=xxx)
	 *
	 * @return string|null url to redirect to, or null if $mValue is invalid.
	 */
	function dispatchRevision() {
		$oldid = $this->mValue;
		if ( !ctype_digit( $oldid ) ) {
			return null;
		}
		$oldid = (int)$oldid;
		if ( $oldid === 0 ) {
			return null;
		}
		return wfAppendQuery( wfScript( 'index' ), array(
			'oldid' => $oldid
		) );
	}

	/**
	 * Use appropriate dispatch* method to obtain a redirection URL,
	 * and either: redirect, set a 404 error code and error message,
	 * or do nothing (if $mValue wasn't set) allowing the form to be
	 * displayed.
	 *
	 * @return bool true if a redirect was successfully handled.
	 */
	function dispatch() {
		// the various namespaces supported by Special:Redirect
		switch ( $this->mType ) {
		case 'user':
			$url = $this->dispatchUser();
			break;
		case 'file':
			$url = $this->dispatchFile();
			break;
		case 'revision':
			$url = $this->dispatchRevision();
			break;
		default:
			$this->getOutput()->setStatusCode( 404 );
			$url = null;
			break;
		}
		if ( $url ) {
			$this->getOutput()->redirect( $url );
			return true;
		}
		if ( !is_null( $this->mValue ) ) {
			$this->getOutput()->setStatusCode( 404 );
			// Message: redirect-not-exists
			$msg = $this->getMessagePrefix() . '-not-exists';
			return Status::newFatal( $msg );
		}
		return false;
	}

	protected function getFormFields() {
		$mp = $this->getMessagePrefix();
		$ns = array(
			// subpage => message
			// Messages: redirect-user, redirect-revision, redirect-file
			'user' => $mp . '-user',
			'revision' => $mp . '-revision',
			'file' => $mp . '-file',
		);
		$a = array();
		$a['type'] = array(
			'type' => 'select',
			'label-message' => $mp . '-lookup', // Message: redirect-lookup
			'options' => array(),
			'default' => current( array_keys( $ns ) ),
		);
		foreach ( $ns as $n => $m ) {
			$m = $this->msg( $m )->text();
			$a['type']['options'][$m] = $n;
		}
		$a['value'] = array(
			'type' => 'text',
			'label-message' => $mp . '-value' // Message: redirect-value
		);
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

	protected function getGroupName() {
		return 'redirects';
	}
}
