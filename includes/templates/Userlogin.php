<?php
/**
 * Html form for user login.
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
 * @ingroup Templates
 */

class UserloginTemplate extends QuickTemplate {

	/**
	 * Get a Message object with its context set.
	 *
	 * @param string $name Message name
	 * @param mixed $params... Message parameters
	 * @return Message
	 */
	public function msg( $name/*, $params */ ) {
		$args = func_get_args();
		return call_user_func_array( [ $this->getSkin(), 'msg' ], $args );
	}

	function execute() {
		echo new OOUI\LabelWidget( [
			'label' => new OOUI\HtmlSnippet( $this->msg( 'loginprompt' )->parse() ),
			'id' => 'userloginprompt',
		] );

		if ( $this->haveData( 'languages' ) ) {
			echo new OOUI\LabelWidget( [
				'label' => new OOUI\HtmlSnippet( $this->data['languages'] ),
				'id' => 'languagelinks',
			] );
		}

		$form = new OOUI\FormLayout( [
			'id' => 'userloginForm',
			'method' => 'post',
			'action' => $this->data['action'],
		] );
		$items = [];

		if ( $this->data['loggedin'] ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\LabelWidget( [
					'label' => new OOUI\HtmlSnippet(
						$this->msg( 'userlogin-loggedin' )->params( $this->data['loggedinuser'] )->parse()
					),
					'classes' => [ 'warningbox' ],
				]
			) );
		}

		// Extensions such as ConfirmEdit add form HTML here…
		// We shouldn't be stuffing random HTML into FieldsetLayouts, which is why this is so weird.
		$items[] = new OOUI\FieldLayout(
			new OOUI\Widget( [
				'content' => new OOUI\HtmlSnippet(
					'<section class="mw-form-header">' . $this->data['header'] . '</section>'
				),
			] )
		);

		if ( $this->data['message'] ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\LabelWidget( [
					'label' => new OOUI\HtmlSnippet(
						// This really sucks
						( $this->data['messagetype'] == 'error'
							? '<strong>' . $this->msg( 'loginerror' )->escaped() . '</strong><br />'
							: '' ) .
						$this->data['message']
					),
					'classes' => [ $this->data['messagetype'] . 'box' ],
				] )
			);
		}

		if ( $this->data['formheader'] ) {
			// Extensions such as MobileFrontend add form HTML here…
			$items[] = new OOUI\FieldLayout(
				new OOUI\Widget( [
					'content' => new OOUI\HtmlSnippet(
						'<div class="mw-form-formheader">' . $this->data['formheader'] . '</div>'
					),
				] )
			);
		}

		if ( $this->data['secureLoginUrl'] ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\ButtonWidget( [
					'label' => $this->msg( 'userlogin-signwithsecure' )->text(),
					'icon' => 'lock',
					'href' => $this->data['secureLoginUrl'],
					'framed' => false,
					'classes' => [ 'mw-ui-flush-right' ],
				] )
			);
		}

		// The actual form fields at last

		$items[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'wpName',
				'id' => 'wpName1',
				'value' => $this->data['name'],
				'placeholder' => $this->msg( 'userlogin-yourname-ph' )->text(),
				'classes' => [ 'loginText' ],
				'tabIndex' => 1,
				// Set focus to this field if it's blank.
				'autofocus' => !$this->data['name'],
				// Currently not supported in PHP, but that might change; see T74585.
				'validate' => 'non-empty',
			] ),
			[
				'label' => $this->msg( 'userlogin-yourname' )->text(),
				'align' => 'top',
			]
		);

		$items[] = new OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'name' => 'wpPassword',
				'id' => 'wpPassword1',
				'type' => 'password',
				'placeholder' => $this->msg( 'userlogin-yourpassword-ph' )->text(),
				'classes' => [ 'loginPassword' ],
				'tabIndex' => 2,
				// Set focus to this field if username is filled in.
				'autofocus' => (bool)$this->data['name'],
			] ),
			[
				'label' => $this->msg( 'userlogin-yourpassword' )->text(),
				'align' => 'top',
			]
		);

		if ( isset( $this->data['usedomain'] ) && $this->data['usedomain'] ) {
			$options = array();
			foreach ( $this->data['domainnames'] as $dom ) {
				$options[] = array( 'data' => $dom );
			}

			$items[] = new OOUI\FieldLayout(
				new OOUI\DropdownInputWidget( [
					'name' => 'wpDomain',
					'options' => $options,
					'value' => $this->data['domain'],
					'tabIndex' => 3,
				] ),
				[
					'label' => $this->msg( 'yourdomainname' )->text(),
					'align' => 'top',
				]
			);
		}

		// More extension-added HTML…
		if ( $this->haveData( 'extrafields' ) ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\Widget( [
					'content' => new OOUI\HtmlSnippet( $this->data['extrafields'] ),
				] )
			);
		}

		if ( $this->data['canremember'] ) {
			global $wgCookieExpiration;
			$expirationDays = ceil( $wgCookieExpiration / ( 3600 * 24 ) );
			$items[] = new OOUI\FieldLayout(
				new OOUI\CheckboxInputWidget( [
					'name' => 'wpRemember',
					'id' => 'wpRemember',
					'value' => '1',
					'selected' => (bool)$this->data['remember'],
					'tabIndex' => 4,
				] ),
				[
					'label' => $this->msg( 'userlogin-remembermypassword' )->numParams( $expirationDays )->text(),
					'align' => 'inline',
				]
			);
		}

		$items[] = new OOUI\FieldLayout(
			new OOUI\ButtonInputWidget( [
				'label' => $this->msg( 'pt-login-button' )->text(),
				'name' => 'wpLoginAttempt',
				'id' => 'wpLoginAttempt',
				'value' => '1',
				'type' => 'submit',
				'value' => '1',
				'selected' => (bool)$this->data['remember'],
				'tabIndex' => 6,
				'flags' => [ 'primary', 'constructive' ],
			] )
		);

		// Bottom stuff

		$items[] = new OOUI\FieldLayout(
			new OOUI\ButtonWidget( [
				'label' => $this->msg( 'userlogin-helplink2' )->text(),
				'href' => Skin::makeInternalOrExternalUrl(
					$this->msg( 'helplogin-url' )->inContentLanguage()->text()
				),
				'framed' => false,
				'id' => 'mw-userlogin-help',
				'classes' => [ 'mw-form-related-link-container' ],
			] )
		);

		if ( $this->data['useemail'] && $this->data['canreset'] && $this->data['resetlink'] === true ) {
			$items[] = new OOUI\FieldLayout(
				new OOUI\ButtonWidget( [
					'label' => $this->msg( 'userlogin-resetpassword-link' )->text(),
					'href' => SpecialPage::getTitleFor( 'PasswordReset' )->getLinkURL(),
					'framed' => false,
					'classes' => [ 'mw-form-related-link-container' ],
				] )
			);
		}

		if ( $this->haveData( 'createOrLoginHref' ) ) {
			if ( $this->data['loggedin'] ) {
				$items[] = new OOUI\FieldLayout(
					new OOUI\ButtonWidget( [
						'label' => $this->msg( 'userlogin-createanother' )->text(),
						'href' => $this->data['createOrLoginHref'],
						'framed' => false,
						'id' => 'mw-createaccount-join',
						'classes' => [ 'mw-form-related-link-container' ],
						'tabIndex' => 7,
					] )
				);
			} else {
				$items[] = new OOUI\FieldLayout(
					new OOUI\ButtonWidget( [
						'label' => $this->msg( 'userlogin-joinproject' )->text(),
						'href' => $this->data['createOrLoginHref'],
						'flags' => [ 'primary', 'progressive' ],
						'id' => 'mw-createaccount-join',
						'classes' => [ 'mw-form-related-link-container' ],
						'tabIndex' => 7,
					] ),
					[
						'label' => $this->msg( 'userlogin-noaccount' )->text(),
						'align' => 'top',
						'id' => 'mw-createaccount-cta',
					]
				);
			}
		}

		$form->appendContent(
			new OOUI\FieldsetLayout( [
				'items' => $items,
			] )
		);

		// Hidden fields
		$fields = '';
		if ( $this->haveData( 'uselang' ) ) {
			$fields .= Html::hidden( 'uselang', $this->data['uselang'] );
		}
		if ( $this->haveData( 'token' ) ) {
			$fields .= Html::hidden( 'wpLoginToken', $this->data['token'] );
		}
		if ( $this->data['cansecurelogin'] ) {
			$fields .= Html::hidden( 'wpForceHttps', $this->data['stickhttps'] );
		}
		if ( $this->data['cansecurelogin'] && $this->haveData( 'fromhttp' ) ) {
			$fields .= Html::hidden( 'wpFromhttp', $this->data['fromhttp'] );
		}
		$form->appendContent( new OOUI\HtmlSnippet( $fields ) );

		echo $form;
	}
}
