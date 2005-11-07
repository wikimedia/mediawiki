<?php
/** Yiddish (ייִדיש)
  *
  * @bug 3810
  *
  * @package MediaWiki
  * @subpackage Language
  */

require_once 'LanguageUtf8.php';

$wgNamespaceNamesYi = array(
	NS_MEDIA => 'מעדיע',
	NS_SPECIAL => 'באַזונדער',
	NS_MAIN => '',
	NS_TALK => 'רעדן',
	NS_USER => 'באַניצער',
	NS_USER_TALK => 'באַניצער_רעדן',
	NS_PROJECT => $wgMetaNamespace,
	NS_PROJECT_TALK => $wgMetaNamespace . '_רעדן',
	NS_IMAGE => 'בילד',
	NS_IMAGE_TALK => 'בילד_רעדן',
	NS_MEDIAWIKI => 'מעדיעװיקי',
	NS_MEDIAWIKI_TALK => 'מעדיעװיקי_רעדן',
	NS_TEMPLATE => 'מוסטער',
	NS_TEMPLATE_TALK => 'מוסטער_רעדן',
	NS_HELP => 'הילף',
	NS_HELP_TALK => 'הילף_רעדן',
	NS_CATEGORY => 'קאַטעגאָריע',
	NS_CATEGORY_TALK=> 'קאַטעגאָריע_רעדן'
);

class LanguageYi extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesYi;
		return $wgNamespaceNamesYi;
	}

	function getDefaultUserOptions() {
		$opt = parent::getDefaultUserOptions();
		$opt['quickbar'] = 2; # Right-to-left
		return $opt;
	}

	# For right-to-left language support
	function isRTL() {
		return true;
	}
}

?>
