<?php
/** Divehi (ދިވެހިބަސް)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Glacious
 * @author MF-Warburg
 * @author לערי ריינהארט
 */

$rtl = true;

$namespaceNames = array(
	NS_SPECIAL          => 'ހާއްޞަ',
	NS_TALK             => 'ޚިޔާލު',
	NS_USER             => 'މެމްބަރު',
	NS_FILE             => 'ފައިލް',
	NS_FILE_TALK        => 'ފައިލް_ޚިޔާލު',
	NS_MEDIAWIKI        => 'މީޑިއާވިކީ',
	NS_MEDIAWIKI_TALK   => 'މީޑިޔާވިކި_ޚިޔާލު',
	NS_TEMPLATE         => 'ފަންވަތް',
	NS_TEMPLATE_TALK    => 'ފަންވަތް_ޚިޔާލު',
	NS_HELP             => 'އެހީ',
	NS_HELP_TALK        => 'އެހީ_ޚިޔާލު',
	NS_CATEGORY         => 'ޤިސްމު',
	NS_CATEGORY_TALK    => 'ޤިސްމު_ޚިޔާލު',
);

$specialPageAliases = array(
	'Userlogin'                 => array( 'ވަދެވަޑައިގަންނަވާ' ),
	'Userlogout'                => array( 'ބޭރަށް ވަޑައިގަންނަވާ' ),
	'CreateAccount'             => array( 'މެމްބަރުކަން ހާސިލްކުރައްވާ' ),
	'Preferences'               => array( 'ތަރުޖީހުތައް' ),
	'Recentchanges'             => array( 'އެންމެ ފަހުގެ ބަދަލްތައް' ),
	'Upload'                    => array( 'ފޮނުއްވާ' ),
	'Listfiles'                 => array( 'ފައިލް ލިސްޓް' ),
	'Newimages'                 => array( 'އާ ފައިލް' ),
	'Randompage'                => array( 'ކޮންމެވެސް ސަފްޙާއެއް' ),
	'Uncategorizedtemplates'    => array( 'ޤިސްމުކުރެވިފައި ނުވާ ފަންވަތް' ),
	'Unusedcategories'          => array( 'ބޭނުން ނުކުރާ ޤިސްމުތައް' ),
	'Unusedimages'              => array( 'ބޭނުން ނުކުރާ ފައިލް' ),
	'Shortpages'                => array( 'ކުރު ސަފްޙާތައް' ),
	'Longpages'                 => array( 'ދިގު ސަފްޙާތައް' ),
	'Newpages'                  => array( 'އާ ސަފްހާތައް' ),
	'Protectedpages'            => array( 'ދިފާޢުކުރެވިފައިވާ ސަފްޙާތައް' ),
	'Allpages'                  => array( 'ހުރިހާ ސަފްޙާއެއް' ),
	'Ipblocklist'               => array( 'ބްލޮކް ކުރެވިފައިވާ ލިސްޓް' ),
	'Specialpages'              => array( 'ޙާއްސަ ސަފްޙާތައް' ),
	'Contributions'             => array( 'ޙިއްސާ' ),
	'Emailuser'                 => array( 'މެމްބަރަށް އީ-މެއިލް ފޮނުވާ' ),
);

$messages = array(
# User preference toggles
'tog-hideminor'    => 'ކުޑަ ކުޑަ އުނި އިތުރުތައް އެންމެފަހުގެ ބަދަލުތަކުގެ ލިސްޓުން ފޮރުއްވަވާ',
'tog-ccmeonemails' => 'އަޅުގަނޑު އެހެން މެމްބަރުންނަށް ފޮނުވާ އީމެއިލްގެ ނަކަލެއް އަޅުގަނޑަށް ފޮނުވާ',

'underline-always' => 'އަބަދުވެސް',
'underline-never'  => 'ހަމަހިލާ ނޫން',

# Dates
'sunday'        => 'އާދީއްތަ',
'monday'        => 'ހޯމަ',
'tuesday'       => 'އަންގާރަ',
'wednesday'     => 'ބުދަ',
'thursday'      => 'ބުރާސްފަތި',
'friday'        => 'ހުކުރު',
'saturday'      => 'ހޮނިހިރު',
'sun'           => 'އާދީއްތަ',
'mon'           => 'ހޯމަ',
'tue'           => 'އަންގާރަ',
'wed'           => 'ބުދަ',
'thu'           => 'ބުރާސްފަތި',
'fri'           => 'ހުކުރު',
'sat'           => 'ހޮނިހިރު',
'january'       => 'ޖެނުއަރީ',
'february'      => 'ފެބްރުއަރީ',
'march'         => 'މާރޗް',
'april'         => 'އެޕްރީލް',
'may_long'      => 'މެއި',
'june'          => 'ޖޫން',
'july'          => 'ޖުލައި',
'august'        => 'އޮގަސްޓު',
'september'     => 'ސެޕްޓެމްބަރު',
'october'       => 'އޮކްޓޫބަރު',
'november'      => 'ނޮވެމްބަރު',
'december'      => 'ޑިސެމްބަރު',
'january-gen'   => 'ޖެނުއަރީ',
'february-gen'  => 'ފެބްރުއަރީ',
'march-gen'     => 'މާޗް',
'april-gen'     => 'އޭޕްރިލް',
'may-gen'       => 'މޭއި',
'june-gen'      => 'ޖޫން',
'july-gen'      => 'ޖުލައި',
'august-gen'    => 'އޯގަސްޓް',
'september-gen' => 'ސެޕްޓެމްބަރ',
'october-gen'   => 'އޮކްޓޯބަރ',
'november-gen'  => 'ނޮވެމްބަރ',
'december-gen'  => 'ޑިސެމްބަރ',
'jan'           => 'ޖެނުއަރީ',
'feb'           => 'ފެބްރުއަރީ',
'mar'           => 'މާޗް',
'apr'           => 'އޭޕްރިލް',
'may'           => 'މެއި',
'jun'           => 'ޖޫން',
'jul'           => 'ޖުލައި',
'aug'           => 'އޯގަސްޓް',
'sep'           => 'ސެޕްޓެމްބަރ',
'oct'           => 'އޮކްޓޯބަރ',
'nov'           => 'ނޮވެމްބަރ',
'dec'           => 'ޑިސެމްބަރ',

# Categories related messages
'category_header' => 'ގިސްމު "$1" ގައިވާ މަޒުމޫނުތައް',
'subcategories'   => 'ކުދިގިސްމުތައް',
'category-empty'  => "''މި ގިސްމުގައި އެއްވެއސް ސަފްހާ އެއް އަދި އެއްވެސް ފައިލެއް ނުހިމެނެއެވެ.''",

'about'      => 'ތާރަފު',
'article'    => 'ފިހުރިސްތު ޞަފްޙާ',
'newwindow'  => '(އާ ވިންޑޯ އެއް ހުޅުވޭނެއެވެ)',
'cancel'     => 'މަންސޫހު',
'mytalk'     => 'މަގޭ ވާހަކަ',
'navigation' => 'ސަމުގާ',
'and'        => '&#32;އަދި',

# Cologne Blue skin
'qbfind'        => 'ހޯއްދަވާ',
'qbedit'        => 'އުނިއިތުރުގެންނަވާ',
'qbpageoptions' => 'މި ޞަފްޙާ',
'qbmyoptions'   => 'މަގޭ ސަފްހާ ތައް',

'errorpagetitle'    => 'ކުށް',
'returnto'          => 'އަނބުރާ $1 އަށް ވަޑައިގަންނަވާ!',
'tagline'           => 'ވިކިޕީޑިއާ އިން',
'help'              => 'އެހީ',
'search'            => 'ހޯއްދަވާ',
'go'                => 'ދުރުވޭ',
'history'           => 'ޞަފްޙާގެ ތާރީހު',
'history_short'     => 'ތާރީހު',
'info_short'        => 'މައުލޫމާތު',
'printableversion'  => 'ޗާޕަށްފަހި ނުސްހާ',
'permalink'         => 'ދާއިމީ ފާލަން',
'print'             => 'ޗާޕުކުރައްވާ',
'edit'              => 'އުނިއިތުރު ގެންނަވާ',
'editthispage'      => 'މި ޞަފްޙާއަށް އުނިއިތުރު ގެންނަވާ',
'delete'            => 'ފޮހެލައްވާ',
'deletethispage'    => 'މި ޞަފްޙާ ފޮހެލައްވާ',
'protect'           => 'ދިފާއުކުރައްވާ',
'protect_change'    => 'ބަދަލު ގެންނަވާ',
'protectthispage'   => 'މި ޞަފްޙާ ދިފާއުކުރައްވާ',
'unprotect'         => 'ދިފާއުކުރުން ހުއްޓަވާލައްވާ',
'unprotectthispage' => 'މި ޞަފްޙާ ދިފާއުކުރުން ހުއްޓަވާލައްވާ',
'newpage'           => 'އާ ސަފްޙާ',
'talkpage'          => 'މި ސަފްޙާއާ މެދު ބަހުސްކުރައްވާ',
'talkpagelinktext'  => 'ޚިޔާލު ސަފްޙާ',
'specialpage'       => 'ހާއްސަ ޞަފްޙާ',
'articlepage'       => 'ފިހުރިސްތު ޞަފްޙާ ބައްލަވާ',
'talk'              => 'ބަހުސް',
'views'             => 'ހިޔާލުފުޅުތައް',
'toolbox'           => 'އަތްމަތީފޮށި',
'userpage'          => 'މެންބަރުގެ ޞަފްޙާ ބައްލަވާ',
'viewtalkpage'      => 'ބަހުސް ބައްލަވާ',
'otherlanguages'    => 'އެހެން ބަސްބަހުން',
'redirectedfrom'    => '(މިސްރާބުކުރެވުނީ $1 އިން)',
'redirectpagesub'   => 'ޞަފްޙާގެ މިސްރާބު އައުކުރައްވާ',
'lastmodifiedat'    => 'މި ޞަފްހާ އަށް އެންމެ ފަހުން ބަދަލެއް ގެނެވިފައިވަނީ $1، $2 ގައެވެ.',
'protectedpage'     => 'ދިފާއުކުރެވިފައިވާ ޞަފްޙާ',
'jumptosearch'      => 'ހޯއްދަވާ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'ވިކިޕީޑިއާ ގެ ތާރަފު',
'aboutpage'            => 'Project:ތާރަފު',
'copyright'            => 'ހުރިހާ މާއްދާއެއް $1 ގެ ދަށުން ލިބެން އެބަހުއްޓެވެ.',
'copyrightpagename'    => 'ވިކިޕީޑިއާ:ނަކަލުކުރުމުގެ ހައްގު',
'copyrightpage'        => '{{ns:project}}:ނަކަލުކުރުމުގެހައްގު',
'currentevents'        => 'ހިނގަމުންދާ ހާދިސާތައް',
'disclaimers'          => 'އިއުލާނުތައް',
'edithelp'             => 'ބަދަލުތައް ގެނައުމަށް އެހީ އެއް',
'edithelppage'         => 'އެހީ: އުނިއިތުރު ގެންނެވުން',
'helppage'             => 'Help:ފިހުރިސްތު',
'mainpage'             => 'މައި ޞަފްޙާ',
'mainpage-description' => 'މައި ޞަފްޙާ',
'portal'               => 'އާންމު ހޮޅުއަށި',
'privacy'              => 'އަމިއްލަވަންތަ ސިޔާސަތު',

'badaccess' => 'ހުއްދައިގެ ކުށެއް',

'ok'              => 'ރަނގަޅު',
'editsection'     => 'އުނިއިތުރު ގެންނަވާ',
'editsectionhint' => 'މަޒްމޫނުގެ $1 ބަޔަށް އުނިއިތުރު ގެންނަނީ',
'toc'             => 'ފިހުރިސްތު',
'showtoc'         => 'ދައްކަވާ',
'hidetoc'         => 'ފޮރުއްވާ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'މަޒުމޫނު',
'nstab-user'      => 'މެންބަރު ޞަފްޙާ',
'nstab-special'   => 'ހާއްސަ',
'nstab-mediawiki' => 'މެސެޖު',
'nstab-template'  => 'ފަންވަތް',
'nstab-category'  => 'ގިސްމު',

# General errors
'error'         => 'ކުށް',
'databaseerror' => 'ކޮށާރުގެ އޮޅުމެއް',
'cannotdelete'  => 'ޞަފްޙާ ނުވަތަ ފައިލު ފޮހެއެއް ނުލެވުނު (ފަހަރެއްގައި މީގެ ކުރީން އެހެން ބޭފުޅަކު ފޮހެލެއްވީ ކަމަށް ވެދާނެ)',
'badtitle'      => 'ނުރަނގަޅު ސުރުހީއެއް',
'viewsource'    => 'މަސްދަރު ބައްލަވާ',

# Login and logout pages
'welcomecreation'            => '== މަރުހަބާ، $1 ! ==
ތިޔަބޭފުޅާގެ މެންބަރުޝިޕް ހެދިއްޖެއެވެ. ތިޔަ ބޭފުޅާގެ ވިކިޕީޑިއާގެ ތަރުޖީހުތައް ބަދަލުކުރައްވަން ހަނދުމަފުޅު ބަހައްޓަވާށެވެ.',
'yourname'                   => 'މެންބަރުގެނަން',
'yourpassword'               => 'ސިއްރުބަސް',
'yourpasswordagain'          => 'ސިއްރުބަސް އަލުންލިޔުއްވާ',
'login'                      => 'ވަދެވަޑައިގަންނަވާ',
'userlogin'                  => 'ވަންނަވާ / މެންބަރަކަށް ވެ ވަޑައިގަންނަވާ',
'userlogout'                 => 'ބޭރަށް ވަޑައިގަންނަވާ',
'nologin'                    => 'މެމްބަރުކަން ހާސިލް ކުރައްވާފައި ނުވޭތޯ؟ $1',
'nologinlink'                => 'މެމްބަރުކަން ހާސިލް ކުރައްވާ!',
'createaccount'              => 'މެންބަރަކަށް ވެ ވަޑައިގަންނަވާ',
'badretype'                  => 'ތިޔަ ލިޔުއްވި ދެ ސިއްރުބަސް އެއް ސިއްރު ބަހާއި އަނެއް ސިއްރު ބަހާއި ދިމަލެއް ނުވޭ. އަދި ސިއްރުބަސް ރަނގަޅަށް ޖައްސަވާށެވެ!',
'userexists'                 => 'ތިޔަ ލިޔުއްވި ނަން މިހާރުވެސް ދަނީ ބޭނުން ކުރެވެމުން، އައު ނަމެއް އިހުތިޔާރު ކުރައްވާ!',
'wrongpassword'              => 'ތިޔަ ލިޔުއްވި ސިއްރުބަސް އަދި ރަނގަޅެއް ނޫނެވެ! އަދި މަސައްކަތް ކޮށްލައްވާށެވެ!',
'wrongpasswordempty'         => 'ތިޔަ ލިޔުއްވި ސިއްރުބަހެއް ނޭނގުނު. އަލުން މަސައްކަތް ކޮށްލައްވާ!',
'mailmypassword'             => 'ސިއްރުބަސް އީމޭލުކުރައްވާ',
'acct_creation_throttle_hit' => 'މާފު ކުރައްވާ، އެހެނީ ތިޔަބޭފުޅާ ވަނީ ކުރީއްސުރެ $1 މެންބަރުކަން ހޯއްދަވާފައި އެހެންވީމާ އިތުރަށް ލިބިވަޑައެއް ނުގަންނަވާނެ.',

# Password reset dialog
'oldpassword' => 'ކުރީގެ ސިއްރުބަސް:',

# Edit page toolbar
'bold_sample'     => 'ބޯ އިބާރާތް',
'bold_tip'        => 'ބޯ އިބާރާތް',
'italic_sample'   => 'ކަތި އިބާރާތް',
'italic_tip'      => 'ކަތި އިބާރާތް',
'headline_sample' => 'ސުރުހީގެ އިބާރާތް',

# Edit pages
'summary'                  => 'ހުލާސާ:',
'subject'                  => 'މާއްދާ/ސުރުހީ:',
'minoredit'                => 'މިއީ ކުޑަކުޑަ އުނިއިތުރެކެވެ',
'watchthis'                => 'މި ޞަފްޙާއަށް ނަޒަރުބަހައްޓަވާ',
'savearticle'              => 'ޞަފްޙާ ރައްކާކުރައްވާ',
'preview'                  => 'ނަމޫނާ',
'showpreview'              => 'ނަމޫނާ',
'showdiff'                 => 'ބަދަލުތައް ދައްކަވާ',
'anoneditwarning'          => "'''ސަމާލުކަމަށް:''' ތިޔަ ބޭފުޅާވަނީ ވިކިޕީޑިޔާގެ މެމްބަރެއްގެ ގޮތުގައި ވަދެ ވަޑައި ނުގަނެ. އެހެންކަމުން ތިޔަ '''އައި.ޕީ''' އެޑްރެސް މި ސަފްހާގެ ތާރީހުގައި ރެކޯޑު ކުރެވޭނެއެވެ.",
'blockedtitle'             => 'މެންބަރާމެދު ވަނީ ފިޔަވަޅުއެޅިފައި',
'blockedtext'              => "ތިޔަ މެމްބަރު، ނުވަތަ ތިޔަ ބޭފުޅާގެ އައި.ޕީ އެޑްރެސް ވަނީ ބްލޮކް ކުރެވިފައެވ

މި އައި.ޕީ އެޑްރެސް ބްލޮކް ކުރީ $1 އެވެ.

ބްލޮކް ކުރުމުގެ ސަބަބަކަށް ދެވިފައިވަނީ ''$2''.

މި ބްލޮކްގެ މުއްދަތު ހަމަވާނީ: $6<br />
ބްލޮކް ކުރެވިފައިވަނީ: $7

މި ބްލޮކް އާއި މެދު ޚިޔާލު ފާޅުކުރައްވާނަމަ  $1 އާއި ނުވަތަ އެހެން [[{{MediaWiki:Grouppage-sysop}}|އެޑްމިނިސްޓްރޭޓަރަކާއި]] ވާހަކަ ދައްކަވާށެވެ.
އެހެންނަމަވެސް ތިބޭފުޅާގެ [[Special:Preferences|ތަރުޖީހު]] ގައި ރަނގަޅު އީމޭލް އެޑްރެހެއް ކަނޑައަޅުއްވާފައި ނުވާ ނަމަ ތިބޭފުޅާ އަކަށް 'މެމްބަރަށް އީމޭލް ފޮނުއްވަވާ' ގެ ޚިދުމަތެއް ބޭނުން ނުކުރެއްވޭނެއެވެ.

ތިބޭފުޅޭގެ އައި.ޕީ އެޑް ރެސް އަކީ  $3 އެވެ.
އަދި ބްލޮކް އައި.ޑީ އަކީ #$5 އެވެ.",
'blockedoriginalsource'    => "'''$1''' މަޒުމޫނުގެ މަސްދަރު ތިރީގައި މި ވަނީ އެވެ:",
'accmailtitle'             => 'ސިއްރުބަސް ފޮނުވިއްޖެ.',
'accmailtext'              => '"$1" އަށްޓަކައިވާ ސިއްރު ބަސް $2 އަށް ވަނީ ފޮނުވިފައި',
'newarticle'               => '(އައު)',
'editing'                  => '$1 އަށް އުނިއިތުރު ގެންނަނީ',
'editingsection'           => '$1 ގެ (ބަޔަކަށް) އުނި އިތުރު ގެންނަނީ',
'editconflict'             => 'އުނިއިތުރުގެންނެވުމުގައި އަރާރުން: $1',
'yourtext'                 => 'ތިޔބޭފުޅާގެ ލިޔުއްވުން',
'yourdiff'                 => 'ތަފާތުތައް',
'semiprotectedpagewarning' => "'''ސަމާލުކަމަށް:''' މި ޞަފްހާވަނީ ދިފާއު ކުރެވިފައެވެ. އެހެންކަމުން މިސަފްޙާ އަށް އުނި އިތުރު ގެނެވޭނީ ހަމައެކަނި މެމްބަރުކަން ހާސިލް ކުރައްވާފައިވާ ބޭފުޅުންނަށެވެ!",
'templatesused'            => 'މި ޞަފްޙާ ގައި ބޭނުން ކުރެވިފައިވާ ފަންވަތްތައް:',

# History pages
'next' => 'ކުރިޔަށް',

# Diffs
'difference'              => '(އިސްލާހުތަކުގައި ހުރި ފަރަގު)',
'compareselectedversions' => 'އިހުތިޔާރު ކުރެވިފައިވާ ނުސްހާތައް އަޅައިކިޔުއްވާ',

# Search results
'viewprevnext'   => 'ބައްލަވާ($1) ($2) ($3).',
'searchhelp-url' => 'Help:ފިހުރިސްތު',

# Preferences page
'preferences'    => 'ތަރުޖީހުތައް',
'mypreferences'  => 'މަގޭ ތަރުޖީހުތައް',
'changepassword' => 'ސިއްރުބަސް ބަދަލުކުރައްވާ',
'columns'        => 'ކޮލަންތައް:',
'prefs-files'    => 'ފައިލުތައް',
'youremail'      => '٭ އީމޭލު',
'username'       => 'މެންބަރުނަން:',
'yourrealname'   => '* އަސްލު ނަން',
'yourlanguage'   => 'ބަސް:',
'yournick'       => 'ލަގަބު/ކުއްނިއްޔާ:',
'email'          => 'އީމޭލު',

# User rights
'userrights'               => 'މެންބަރުގެ ހައްގުތަކުގެ އިންތިޒާމް',
'userrights-user-editname' => 'މެންބަރުނަން ލިޔުއްވާ:',

# Recent changes
'recentchanges'     => 'ފަހު ބަދަލުތައް',
'recentchangestext' => 'މި ވިކިޕީޑިޔާ އަށް ގެނެވިފައިވާ އެންމެ ފަހުގެ ބަދަލުތައް މި ޞަފްހާ އިން ބައްލަވާ!',
'diff'              => 'ފަރަގު',
'hide'              => 'ފޮރުވާ',
'show'              => 'ދައްކަވާ',

# Recent changes linked
'recentchangeslinked' => 'ގުޅުންހުރި ބަދަލުތައް',

# Upload
'upload'            => 'ފައިލު ފޮނުވާ',
'uploadbtn'         => 'ފައިލު ފޮނުވާ',
'filedesc'          => 'ހުލާސާ',
'fileuploadsummary' => 'ހުލާސާ:',
'filestatus'        => 'ނަކަލުކުރުމުގެހައްގުގެ ހާލަތު:',
'filesource'        => 'މަސްދަރު:',
'uploadedfiles'     => 'ފޮނުވިފައިވާ ފައިލުތައް',
'uploadwarning'     => 'ފައިލުފޮނުއްވުމުގެ ކުރިން ދެވޭ އިންޒާރު',

# Special:ListFiles
'listfiles' => 'ފައިލުތަކުގެ ފިހުރިސްތު',

# File description page
'imagelinks' => 'ފާލަންތައް',

# Random page
'randompage' => 'ކޮންމެވެސް ޞަފްޙާއެއް',

# Statistics
'statistics'              => 'ތަފާސްހިސާބުތައް',
'statistics-header-users' => 'މެންބަރުގެ ތަފާސްހިސާބު',

'brokenredirects' => 'އޮޅިފައިވާ މިސްރާބުތައް',

# Miscellaneous special pages
'unusedcategories' => 'ބޭނުންނުކުރެވޭ ގިސްމުތައް',
'unusedimages'     => 'ބޭނުންނުކުރެވޭ ފައިލުތައް',
'wantedcategories' => 'ބޭނުންފުޅުވާ ގިސްމުތައް',
'wantedpages'      => 'ބޭނުންފުޅުވާ ޞަފްޙާތައް',
'shortpages'       => 'ކުރު ޞަފްޙާތައް',
'ancientpages'     => 'ބޯދާ ޞަފްޙާތައް',
'move'             => 'ނަން/ތަން ބަދަލުކުރައްވާ',
'movethispage'     => 'މި ޞަފްހާގެ ނަންބަދަލުކުރައްވާ',

# Book sources
'booksources' => 'ފޮތްތަކުގެ މަސްދަރުތައް',

# Special:AllPages
'allpages'       => 'ހުރިހާ ޞަފްޙާތައް',
'allarticles'    => 'ހުރިހާ މަޒުމޫނުތައް',
'allinnamespace' => 'ހުރިހާ ޞަފްޙާތައް (ނުތުގު $1 ގައިވާ)',
'allpagesprev'   => 'ކުރީގެ',
'allpagesnext'   => 'ކުރިޔަށް އޮތް',
'allpagessubmit' => 'ދުރުވޭ',

# Special:Categories
'categories'         => 'ގިސްމުތައް',
'categoriespagetext' => 'ތިރީގައި މިވާ ގިސްމުތައް ވިކީ ގައި މައުޖޫދުވެގެން ވެއެވެ.',

# E-mail user
'emailuser' => 'މި މެންބަރަށް އީމޭލު ފޮނުއްވާ',

# Watchlist
'watchlist'         => 'މަގޭ ނަޒަރު',
'addedwatch'        => 'ނަޒަރުގެ ފިހުރިސްތަށް ލެވިއްޖެ',
'addedwatchtext'    => "މި ޞަފްޙާ \"<nowiki>\$1</nowiki>\" ތިޔަބޭފުޅާގެ [[Special:Watchlist|ހާއްސަ ނަޒަރު]] ފިހުރިސްތަށް ލެވިއްޖެއެވެ. ދެން ކުރިމަގުގައި މި ޞަފްޙާ އަދި މިއާ ގުޅޭ ބަހުސް ގެ ޞަފްޙާ އަށް ގެނެވޭ އުނިއިތުރު ތަކުގެ މައުލޫމާތު މިޞަފްހާއަށް ލެވޭނެއެވެ. އަދި އެ ޞަފްޙާތަކުގެ ޝަނާހަތު ފަސޭހަ ކުރުމަށްޓަކައި [[Special:ފަހު ބަދަލުތައް|ފަހު ބަދަލުތަކުގެ ފިހުރިސްތު]] ގައި އެބައިތައް '''ބޯ''' (bold) އަކުރުން ލިޔެވޭނެއެވެ. <p> ކޮންމެ އިރަކުވެސް ތިޔަބޭފުޅާ ހާއްސަ ނަޒަރުގެ ފިހުރިސްތުން މި ޞަފްޙާ އުނިކުރައްވަން ބޭނުންފުޅިއްޔާ މަތީގައި ދެވިފައިވާ \"ހާއްސަ ނަޒަރުން އުނިކުރޭ\" ގައި ކޮއްޓަވާ ލައްވާށެވެ.",
'watch'             => 'ނަޒަރުބަހައްޓަވާ',
'watchthispage'     => 'މި ޞަފްޙާއަށް ނަޒަރުބަހައްޓަވާ',
'unwatch'           => 'ހާއްސަ ނަޒަރުން އުނިކުރޭ',
'watchlistcontains' => 'ތިޔަބޭފުޅާގެ ހާއްސަ ނަޒަރު ފިހުރިސްތުގައި ވަނީ $1 ޞަފްޙާއެވެ.',
'wlnote'            => 'ތިރީގައި މިވަނީ އެއީ ފާއިތުވި <b>$2</b> ގަޑި އިރުގެ ތެރޭގައިގެނެވިފައިވާ ފަހު $1 ބަދަލެވެ.',

'changed' => 'ބަދަލުކުރެވިއްޖެ',

# Delete
'deletepage'        => 'ޞަފްޙާ ފޮހެލައްވާ',
'confirm'           => 'ޔަގީން',
'confirmdeletetext' => 'ތިޔަބޭފުޅާ ތިޔަ އުޅުއްވަނީ ޞަފްޙާއެއް ނުވަތަ ތަޞްވީރެއް އެއާ ގުޅިފައިވާ ހުރިހާ ތާރީހަކާއެކު ކޮށާރުން ފޮހެލައްވާށެވެ. މިކަން މިގޮތަށް ކުރައްވަން ބޭނުންފުޅުކަން ޔަގީން ކުރައްވާށެވެ. އަދި މިކަމުން ނުކުމެދާނެ ނަތީޖާއެއް ވެސް ތިޔަ ބޭފުޅާއަށް ރަނގަޅަށް އެނގިވަޑައިގަންނަވަންވާނެއެވެ. އަދި ތިޔަކަން ތިކުރެއްވެނީ [[{{MediaWiki:Policy-url}}|ވިކިޕީޑިއާ ގެ ސިޔާސަތާ]] އެއްގޮތަށްތޯ ވެސް ބައްލަވައި ޔަގީން ކުރައްވާށެވެ!',
'actioncomplete'    => 'އަމަލު ފުރިހަމަވެއްޖެ',
'deletecomment'     => 'ފޮހެލައްވާ ސަބަބު',

# Rollback
'cantrollback' => 'އުނިއިތުރު އިއާދައެއް ނުކުރެވޭނެ؛ އެހެނީ އެންމެ ފަހު އުނިއިތުރުގައި ހިއްސާވި ފަރާތަކީ މިޞަފްޙާގެ ހަމައެކަނި މުއައްލިފެވެ.',

# Protect
'unprotectedarticle' => '"[[$1]]" ދިފާއުކުރުން ހުއްޓާލެވިއްޖެ',
'prot_1movedto2'     => '[[$1]] އަށް ނަގުލުކުރެވިފައި [[$2]]',

# Restrictions (nouns)
'restriction-edit' => 'އުނިއިތުރު ގެންނަވާ',

# Undelete
'undelete'        => 'ފޮހެލެވިފައިވާ ޞަފްޙާތައް ބައްލަވާ',
'viewdeletedpage' => 'ފޮހެލެވިފައިވާ ޞަފްޙާތައް ބައްލަވާ',
'undeletebtn'     => 'އިއާދަ ކުރޭ!',

# Namespace form on various pages
'blanknamespace' => '(މައި)',

# Contributions
'contributions' => 'މެންބަރު ގެ ހިއްސާ',
'mycontris'     => 'މަގޭ ހިއްސާ',

'sp-contributions-userrights' => 'މެންބަރުގެ ހައްގުތަކުގެ އިންތިޒާމް',

# What links here
'whatlinkshere' => 'މިއާ ގުޅެނީ ކޮންއެއްޗެއް',

# Block/unblock
'blockip'           => 'މެންބަރާ މެދު ފިޔަވަޅުއަޅުއްވާ',
'ipbreason'         => 'ސަބަބު',
'ipbsubmit'         => 'މި މެމްބަރާއި މެދު ފިޔަވަޅު އަޅުއްވާ!',
'badipaddress'      => 'ނުރަނގަޅު އައި.ޕީ އެޑްރެހެއް',
'expiringblock'     => 'މުއްދަތު ހަމަވާނީ $1',
'blocklink'         => 'ފިޔަވަޅުއަޅުއްވާ',
'contribslink'      => 'ޙިއްޞާ',
'proxyblocksuccess' => 'ފުރިހަމަވެއްޖެ.',

# Developer tools
'lockdb'  => 'ކޮށާރު ބަންދުކުރައްވާ',
'lockbtn' => 'ކޮށާރު ބަންދުކުރައްވާ',

# Move page
'move-page-legend'        => 'ޞަފްޙާގެ ނަން ބަދަލުކުރައްވާ',
'movearticle'             => 'ޞަފްޙާގެ ނަން/ތަން ބަދަލުކުރައްވާ',
'newtitle'                => 'އައު ނަމަކަށް',
'move-watch'              => 'މި ޞަފްހާ އަށް ނަޒަރުބަހައްޓަވާ!',
'movepagebtn'             => 'ޞަފްޙާގެނަން ބަދަލުކުރައްވާ',
'articleexists'           => 'ތިޔަ ސުރުހީގައި ކުރީއްސުރެ ވެސް ޞަފްޙައެއް ވޭ، ނުވަތަ ތިޔަ އިހުތިޔާރުކުރެއްވި ނަން ރަނގަޅެއް ނޫން، ވީމާ އެހެން ނަމެއް އިހުތިޔާރުކުރެއްވުން އެދެވިގެންވެއެވެ.',
'movedto'                 => 'އަށް ބަދަލުކުރެވިފައި',
'1movedto2'               => '[[$1]] އަށް ނަގުލުކުރެވިފައި [[$2]]',
'1movedto2_redir'         => '$2 އަށް ނަގުލުކުރެވިއްޖެ $1 މިސްރާބުކުރައްވާ',
'movelogpagetext'         => 'ތިރީގައި މިވަނީ ނަން/ތަން ބަދަލުކުރެވިފައިވާ ޞަފްޙާތަކުގެ ފިހުރިސްތެކެވެ.',
'movereason'              => 'ސަބަބު',
'delete_and_move'         => 'ފޮހެލައްވާފައި އެހެންނަމަކަށްބަދަލުކުރައްވާ',
'delete_and_move_confirm' => 'އާދެ، މި ޞަފްޙާ ފޮހެލައްވާ',
'delete_and_move_reason'  => 'އެހެންނަމަކަށް ބަދަލުކުރުމަށްޓަކައި ފޮހެލެވިއްޖެ',

# Namespace 8 related
'allmessages'        => 'ނިޒާމުގެ މެސެޖުތައް',
'allmessagesname'    => 'ނަން',
'allmessagesdefault' => 'ކުރީގެ މަތަން',
'allmessagescurrent' => 'މިހާރުގެ މަތަން',

# Tooltip help for the actions
'tooltip-pt-logout'  => 'ބޭރަށްވަޑައިގަންނަވާ',
'tooltip-n-mainpage' => 'މައި ސަފްޙާއަށް ވަަޑައިގަންނަވާ',

# Special:NewFiles
'showhidebots' => '($1 ސެއްޓި)',
'ilsubmit'     => 'ހޯއްދަވާ',

# EXIF tags
'exif-imagedescription' => 'ތަސްވީރުގެ ސުރުހީ',
'exif-artist'           => 'މުސައްނިފު',
'exif-filesource'       => 'ފައިލުގެ މަސްދަރު',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-n' => 'ތަސްވީރުގެ ސުރުހީ',

# 'all' in various places, this might be different for inflected languages
'imagelistall'  => 'ހުރިހާ',
'watchlistall2' => 'ހުރިހާ',
'namespacesall' => 'ހުރިހާ',

# E-mail address confirmation
'confirmemail' => 'އީމޭލު އެޑްރެސް ޔަގީންކުރައްވާ',

# action=purge
'confirm_purge_button' => 'ރަނގަޅު',

# Special:Version
'version' => 'ނުސްހާ ނަމްބަރު',

# Special:SpecialPages
'specialpages' => 'ހާއްސަ ޞަފްޙާތައް',

);
