<?php

class SpecialPasswordPolicies extends SpecialPage {
	function __construct() {
		parent::__construct( 'PasswordPolicies' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		$out->addHTML(
			Xml::openElement( 'table', [ 'class' => 'wikitable mw-passwordpolicies-table' ] ) .
				'<tr>' .
				Xml::element( 'th', null, $this->msg( 'passwordpolicies-group' )->text() ) .
				Xml::element( 'th', null, $this->msg( 'passwordpolicies-policies' )->text() ) .
				'</tr>'
		);

		$config = $this->getConfig();
		$policies = $config->get( 'PasswordPolicy' );

		// TODO: Copied from Special:Listgrouprights, refactor
		$groupPermissions = $config->get( 'GroupPermissions' );
		$revokePermissions = $config->get( 'RevokePermissions' );
		$addGroups = $config->get( 'AddGroups' );
		$removeGroups = $config->get( 'RemoveGroups' );
		$groupsAddToSelf = $config->get( 'GroupsAddToSelf' );
		$groupsRemoveFromSelf = $config->get( 'GroupsRemoveFromSelf' );
		$allGroups = array_unique( array_merge(
			array_keys( $groupPermissions ),
			array_keys( $revokePermissions ),
			array_keys( $addGroups ),
			array_keys( $removeGroups ),
			array_keys( $groupsAddToSelf ),
			array_keys( $groupsRemoveFromSelf )
		) );
		asort( $allGroups );

		$linkRenderer = $this->getLinkRenderer();

		foreach ( $allGroups as $group ) {
			$permissions = isset( $groupPermissions[$group] )
				? $groupPermissions[$group]
				: [];
			$groupname = ( $group == '*' ) // Replace * with a more descriptive groupname
				? 'all'
				: $group;

			$msg = $this->msg( 'group-' . $groupname );
			$groupnameLocalized = !$msg->isBlank() ? $msg->text() : $groupname;

			$msg = $this->msg( 'grouppage-' . $groupname )->inContentLanguage();
			$grouppageLocalized = !$msg->isBlank() ?
				$msg->text() :
				MWNamespace::getCanonicalName( NS_PROJECT ) . ':' . $groupname;
			$grouppageLocalizedTitle = Title::newFromText( $grouppageLocalized );

			if ( $group == '*' || !$grouppageLocalizedTitle ) {
				// Do not make a link for the generic * group or group with invalid group page
				$grouppage = htmlspecialchars( $groupnameLocalized );
			} else {
				$grouppage = $linkRenderer->makeLink(
					$grouppageLocalizedTitle,
					$groupnameLocalized
				);
			}

			if ( $group === 'user' ) {
				// Link to Special:listusers for implicit group 'user'
				$grouplink = '<br />' . $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text()
				);
			} elseif ( !in_array( $group, $config->get( 'ImplicitGroups' ) ) ) {
				$grouplink = '<br />' . $linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'Listusers' ),
					$this->msg( 'listgrouprights-members' )->text(),
					[],
					[ 'group' => $group ]
				);
			} else {
				// No link to Special:listusers for other implicit groups as they are unlistable
				$grouplink = '';
			}

			$id = $group == '*' ? false : Sanitizer::escapeIdForAttribute( $group );

			$out->addHTML( Html::rawElement( 'tr', [ 'id' => $id ], "
				<td>$grouppage$grouplink</td>
					<td>" . $this->formatPolicies( $policies, $group ) . '</td>
				'
			) );

		}

		$out->addHTML( Xml::closeElement( 'table' ) );
	}

	private function formatPolicies( $policies, $group ) {
		$groupPolicies = UserPasswordPolicy::getPoliciesForGroups(
			$policies['policies'],
			[ $group ],
			$policies['policies']['default']
		);

		$ret = [];
		foreach ( $groupPolicies as $gp => $val ) {
			if ( $val === false ) {
				// Policy isn't enabled, so no need to dispaly it
				continue;
			} elseif ( $val === true ) {
				$msg = $this->msg( 'passwordpolicies-policy-' . strtolower( $gp ) );
			} else {
				$msg = $this->msg( 'passwordpolicies-policy-' . strtolower( $gp ) )->numParams( $val );
                        }
			$ret[] = $this->msg(
				'passwordpolicies-policy-display',
				$msg,
				'<span class="mw-passwordpolicies-policy-name">' . $gp . '</span>'
			)->parse();
		}
		if ( !count( $ret ) ) {
			return '';
		} else {
			return '<ul><li>' . implode( "</li>\n<li>", $ret ) . '</li></ul>';
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
