<?php
/** Gujarati (ગુજરાતી)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$namespaceNames = [
	NS_MEDIA            => 'દ્રશ્ય-શ્રાવ્ય_(મિડિયા)',
	NS_SPECIAL          => 'વિશેષ',
	NS_TALK             => 'ચર્ચા',
	NS_USER             => 'સભ્ય',
	NS_USER_TALK        => 'સભ્યની_ચર્ચા',
	NS_PROJECT_TALK     => '$1_ચર્ચા',
	NS_FILE             => 'ચિત્ર',
	NS_FILE_TALK        => 'ચિત્રની_ચર્ચા',
	NS_MEDIAWIKI        => 'મીડિયાવિકિ',
	NS_MEDIAWIKI_TALK   => 'મીડિયાવિકિ_ચર્ચા',
	NS_TEMPLATE         => 'ઢાંચો',
	NS_TEMPLATE_TALK    => 'ઢાંચાની_ચર્ચા',
	NS_HELP             => 'મદદ',
	NS_HELP_TALK        => 'મદદની_ચર્ચા',
	NS_CATEGORY         => 'શ્રેણી',
	NS_CATEGORY_TALK    => 'શ્રેણીની_ચર્ચા',
];

$specialPageAliases = [
	'Allmessages'               => [ 'બધાંસંદેશા' ],
	'Allpages'                  => [ 'બધાંપાનાં' ],
	'Ancientpages'              => [ 'પ્રાચીનપાનાં' ],
	'Blankpage'                 => [ 'કોરૂં_પાનું' ],
	'Block'                     => [ 'પ્રતિબંધ', 'IP_પર_પ્રતિબંધ', 'સભ્યપર_પ્રતિબંધ' ],
	'Booksources'               => [ 'પુસ્તકસ્રોત' ],
	'BrokenRedirects'           => [ 'ખંડિત_પુનઃમાર્ગદર્શન' ],
	'Categories'                => [ 'શ્રેણીઓ' ],
	'ChangePassword'            => [ 'ગુપ્તસંજ્ઞા_બદલો', 'ગુપ્તસંજ્ઞા_પુન:_સ્થાપન' ],
	'Confirmemail'              => [ 'ઇમેઇલખાતરીકરો' ],
	'Contributions'             => [ 'પ્રદાન' ],
	'CreateAccount'             => [ 'ખાતું_ખોલો' ],
	'Deadendpages'              => [ 'મૃતાંતપાનાં' ],
	'DeletedContributions'      => [ 'હટાવેલાં_યોગદાન' ],
	'DoubleRedirects'           => [ 'દ્વિ_પુનઃમાર્ગદર્શન' ],
	'Emailuser'                 => [ 'સભ્યનેઇમેલ' ],
	'ExpandTemplates'           => [ 'શ્રેણીવિસ્તારો' ],
	'Export'                    => [ 'નિકાસ' ],
	'Fewestrevisions'           => [ 'લઘુત્તમ_પુનરાવર્તન' ],
	'FileDuplicateSearch'       => [ 'ફાઇલપ્રતિકૃતિ_શોધ' ],
	'Filepath'                  => [ 'ફાઇલપથ' ],
	'Import'                    => [ 'આયાત' ],
	'Invalidateemail'           => [ 'અમાન્ય_ઇ-મેઇલ' ],
	'LinkSearch'                => [ 'કડી_શોધ' ],
	'Listadmins'                => [ 'યાદીપ્રબંધક' ],
	'Listbots'                  => [ 'યાદીબૉટ' ],
	'Listfiles'                 => [ 'યાદીફાઇલ', 'ફાઇલયાદી', 'ચિત્રયાદી' ],
	'Listgrouprights'           => [ 'યાદીસમુહઅધિકારો', 'સભ્યસમુહઅધિકારો' ],
	'Listredirects'             => [ 'પુનઃમાર્ગદર્શનયાદી' ],
	'Listusers'                 => [ 'યાદીસભ્યો', 'સભ્યયાદી' ],
	'Log'                       => [ 'લૉગ', 'લૉગ્સ' ],
	'Lonelypages'               => [ 'એકાકીપાનાં', 'અનાથપાનાં' ],
	'Longpages'                 => [ 'લાંબાપાના' ],
	'MergeHistory'              => [ 'વિલિનિકરણ_ઈતિહાસ' ],
	'Mostcategories'            => [ 'મોટાભાગની_શ્રેણીઓ' ],
	'Mostimages'                => [ 'સૌથી_વધુજોડાયેલી_ફાઇલો', 'મહત્તમ_ફાઇલો', 'મહત્તમ_ચિત્રો' ],
	'Mostlinked'                => [ 'સૌથીવધુ_જોડાયેલાં_પાનાં', 'સૌથીવધુ_જોડાયેલ' ],
	'Mostlinkedcategories'      => [ 'સૌથીવધુજોડાયેલી_શ્રેણી', 'સૌથીવધુવપરાયેલી_શ્રેણીઓ' ],
	'Mostlinkedtemplates'       => [ 'સૌથીવધુ_જોડાયેલાં_ઢાંચા', 'સૌથી_વધુવપરાયેલાં_ઢાંચા' ],
	'Mostrevisions'             => [ 'મહત્તમ_પુનરાવર્તન' ],
	'Movepage'                  => [ 'પાનુંખસેડો' ],
	'Mycontributions'           => [ 'મારૂપ્રદાન' ],
	'Mypage'                    => [ 'મારૂપાનું' ],
	'Mytalk'                    => [ 'મારીચર્ચા' ],
	'Newimages'                 => [ 'નવીફાઇલો', 'નવાંચિત્રો' ],
	'Newpages'                  => [ 'નવાપાનાં' ],
	'Preferences'               => [ 'પસંદ' ],
	'Prefixindex'               => [ 'ઉપસર્ગ' ],
	'Protectedpages'            => [ 'સંરક્ષિતપાનાં' ],
	'Protectedtitles'           => [ 'સંરક્ષિત_શિર્ષકો' ],
	'Randompage'                => [ 'યાદચ્છ', 'કોઈ_પણ_એક' ],
	'Randomredirect'            => [ 'યાદચ્છ_પુનઃમાર્ગદર્શન' ],
	'Recentchanges'             => [ 'તાજાફેરફારો' ],
	'Recentchangeslinked'       => [ 'તાજેતરનાં_ફેરફારો', 'સંલગ્ન_ફેરફારો' ],
	'Revisiondelete'            => [ 'રદકરેલું_સુધારો' ],
	'Search'                    => [ 'શોધ' ],
	'Shortpages'                => [ 'ટુંકાપાનાં' ],
	'Specialpages'              => [ 'ખાસપાનાં' ],
	'Statistics'                => [ 'આંકડાકીયમાહિતી' ],
	'Tags'                      => [ 'ટેગ' ],
	'Uncategorizedcategories'   => [ 'અવર્ગિકૃત_શ્રેણીઓ' ],
	'Uncategorizedimages'       => [ 'અવર્ગિકૃત_ફાઇલો', 'અવર્ગિકૃત_ચિત્રો' ],
	'Uncategorizedpages'        => [ 'અવર્ગિકૃત_પાનાં' ],
	'Uncategorizedtemplates'    => [ 'અવર્ગિકૃત_ઢાંચા' ],
	'Undelete'                  => [ 'પુનઃપ્રાપ્ત' ],
	'Unusedcategories'          => [ 'વણવપરાયેલી_શ્રેણીઓ' ],
	'Unusedimages'              => [ 'વણવપરાયેલ_ફાઇલો', 'વણવપરાયેલ_ચિત્રો' ],
	'Unusedtemplates'           => [ 'વણવપરાયેલાં_ઢાંચા' ],
	'Unwatchedpages'            => [ 'વણજોયેલા_પાનાં' ],
	'Upload'                    => [ 'ચડાવો' ],
	'Userlogin'                 => [ 'સભ્યપ્રવેશ' ],
	'Userlogout'                => [ 'સભ્યનિવેશ' ],
	'Userrights'                => [ 'સભ્યાધિકાર' ],
	'Version'                   => [ 'સંસ્કરણ' ],
	'Wantedcategories'          => [ 'જોઇતી_શ્રેણીઓ' ],
	'Wantedfiles'               => [ 'જોઇતી_ફાઇલો' ],
	'Wantedpages'               => [ 'જોઇતા_પાનાં', 'ત્રુટક_કડી' ],
	'Wantedtemplates'           => [ 'જોઇતા_ઢાંચા' ],
	'Watchlist'                 => [ 'ધ્યાનસૂચિ' ],
	'Whatlinkshere'             => [ 'અહિં_શું_જોડાય_છે?' ],
	'Withoutinterwiki'          => [ 'આંતરવિકિવિહીન' ],
];

$digitTransformTable = [
	'0' => '૦', # &#x0ae6;
	'1' => '૧', # &#x0ae7;
	'2' => '૨', # &#x0ae8;
	'3' => '૩', # &#x0ae9;
	'4' => '૪', # &#x0aea;
	'5' => '૫', # &#x0aeb;
	'6' => '૬', # &#x0aec;
	'7' => '૭', # &#x0aed;
	'8' => '૮', # &#x0aee;
	'9' => '૯', # &#x0aef;
];

$digitGroupingPattern = "##,##,###";

$linkTrail = "/^([\x{0A80}-\x{0AFF}]+)(.*)$/sDu";
