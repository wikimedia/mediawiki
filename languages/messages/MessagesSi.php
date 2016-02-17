<?php
/** Sinhala (සිංහල)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Asiri wiki
 * @author Budhajeewa
 * @author Calcey
 * @author Chandana
 * @author Jiro Ono
 * @author Kaganer
 * @author Meno25
 * @author Pasanbhathiya2
 * @author Romaine
 * @author Sahan.ssw
 * @author Singhalawap
 * @author Thushara
 * @author චතුනි අලහප්පෙරුම
 * @author තඹරු විජේසේකර
 * @author දසනැබළයෝ
 * @author නන්දිමිතුරු
 * @author පසිඳු කාවින්ද
 * @author බිඟුවා
 * @author රොමානිස් සැමුවෙල්
 * @author ශ්වෙත
 * @author සුරනිමල
 */

$namespaceNames = [
	NS_MEDIA            => 'මාධ්‍යය',
	NS_SPECIAL          => 'විශේෂ',
	NS_TALK             => 'සාකච්ඡාව',
	NS_USER             => 'පරිශීලක',
	NS_USER_TALK        => 'පරිශීලක_සාකච්ඡාව',
	NS_PROJECT_TALK     => '$1_සාකච්ඡාව',
	NS_FILE             => 'ගොනුව',
	NS_FILE_TALK        => 'ගොනුව_සාකච්ඡාව',
	NS_MEDIAWIKI        => 'මාධ්‍යවිකි',
	NS_MEDIAWIKI_TALK   => 'මාධ්‍යවිකි_සාකච්ඡාව',
	NS_TEMPLATE         => 'සැකිල්ල',
	NS_TEMPLATE_TALK    => 'සැකිලි_සාකච්ඡාව',
	NS_HELP             => 'උදවු',
	NS_HELP_TALK        => 'උදවු_සාකච්ඡාව',
	NS_CATEGORY         => 'ප්‍රවර්ගය',
	NS_CATEGORY_TALK    => 'ප්‍රවර්ග_සාකච්ඡාව',
];

$namespaceAliases = [
	'රූපය' => NS_FILE,
	'රූපය_සාකච්ඡාව' => NS_FILE_TALK,
	'විකිමාධ්‍ය' => NS_MEDIAWIKI,
	'විකිමාධ්‍ය_සාකච්ඡාව' => NS_MEDIAWIKI_TALK,
	'උදව_සාකච්ඡාව' => NS_HELP_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'ක්‍රියාකාරී_පරිශීලකයන්' ],
	'Allmessages'               => [ 'සියළු_පණිවුඩ' ],
	'Allpages'                  => [ 'සියළු_පිටු' ],
	'Ancientpages'              => [ 'පුරාතන_පිටු' ],
	'Badtitle'                  => [ 'නුසුසුදු_මාතෘකාව' ],
	'Blankpage'                 => [ 'හිස්_පිටුව' ],
	'Block'                     => [ 'වාරණය_කරන්න', 'IP_වාරණය_කරන්න', 'පරිශීලක_වාරණය_කරන්න' ],
	'Booksources'               => [ 'ග්‍රන්ථ_මූලාශ්‍ර' ],
	'BrokenRedirects'           => [ 'භින්න_යළි-යොමුකිරීම්' ],
	'Categories'                => [ 'ප්‍රවර්ග' ],
	'ChangePassword'            => [ 'මුරපදය_වෙනස්_කරන්න', 'මුරපදය_ප්‍රතිස්ථාපනය_කරන්න' ],
	'Confirmemail'              => [ 'විද්‍යුත්-තැපෑල_තහවුරු_කරන්න' ],
	'Contributions'             => [ 'දායකත්ව' ],
	'CreateAccount'             => [ 'ගිණුම_තැනීමට' ],
	'Deadendpages'              => [ 'අග_ඇවුරුණු_පිටුව' ],
	'DeletedContributions'      => [ 'මකාදැමුණු_දායකත්වයන්' ],
	'DoubleRedirects'           => [ 'ද්විත්ව_යළි-යොමුකිරීම්' ],
	'Emailuser'                 => [ 'පරිශීලකට_විද්‍යුත්-තැපැලක්_යැවිම' ],
	'Export'                    => [ 'නිර්යාතකරන්න' ],
	'Fewestrevisions'           => [ 'අතිස්වල්ප_සංශෝධන' ],
	'FileDuplicateSearch'       => [ 'ගොනු_අනුපිටපත්_ගවේෂණය' ],
	'Filepath'                  => [ 'ගොනු_පෙත' ],
	'Import'                    => [ 'ආයාත_කරන්න' ],
	'Invalidateemail'           => [ 'විද්‍යුත්_තැපෑල_අනීතික_කරන්න' ],
	'BlockList'                 => [ 'වාරණ_ලැයිස්තුව', 'වාරණ_ලයිස්තුගතකරන්න_', 'IP_වාරණ_ලැයිස්තුව' ],
	'LinkSearch'                => [ 'සබැඳි_ගවේෂණය' ],
	'Listadmins'                => [ 'පරිපාලකයන්_ලැයිස්තුගත_කරන්න' ],
	'Listbots'                  => [ 'රොබෝවන්_ලැයිස්තුගත_කරන්න' ],
	'Listfiles'                 => [ 'රූප_ලැයිස්තුව' ],
	'Listgrouprights'           => [ 'කණ්ඩායම්_හිමිකම්_ලැයිස්තුගතකරන්න' ],
	'Listredirects'             => [ 'යළි-යොමුකිරීම්_ලැයිස්තුගතකරන්න' ],
	'Listusers'                 => [ 'පරිශීලකයන්_ලැයිස්තු_ගත_කරන්න', 'පරිශීලක_ලැයිස්තුව' ],
	'Lockdb'                    => [ 'දත්ත_සංචිතය_අවුරන්න' ],
	'Log'                       => [ 'ලඝු_සටහන', 'ලඝු_සටහන්' ],
	'Lonelypages'               => [ 'හුදකලා_පිටු' ],
	'Longpages'                 => [ 'දිගු_පිටු' ],
	'MergeHistory'              => [ 'ඒකාබද්ධ_ඉතිහාසය' ],
	'MIMEsearch'                => [ 'MIME_ගවේෂණය' ],
	'Mostcategories'            => [ 'බොහෝ_ප්‍රවර්ග' ],
	'Mostimages'                => [ 'බෙහෙවින්_සබැඳි_ගොනු', 'බොහෝ_ගොනු', 'බොහෝ_රූප' ],
	'Mostlinked'                => [ 'බෙහෙවින්_සබැඳි_පිටු', 'බෙහෙවින්_සබැඳි' ],
	'Mostlinkedcategories'      => [ 'බෙහෙවින්_සබැඳි_ප්‍රවර්ග', 'බෙහෙවින්_භාවිත_ප්‍රවර්ග' ],
	'Mostlinkedtemplates'       => [ 'බෙහෙවින්_සබැඳි_සැකිලි', 'බෙහෙවින්_භාවිත_සැකිලි' ],
	'Mostrevisions'             => [ 'බොහෝ_සංශෝධන' ],
	'Movepage'                  => [ 'පිටුව_ගෙන_යන්න' ],
	'Mycontributions'           => [ 'මගේ_දායකත්වය' ],
	'Mypage'                    => [ 'මගේ_පිටුව' ],
	'Mytalk'                    => [ 'මගේ_සාකච්ඡාව' ],
	'Newimages'                 => [ 'නව_ගොනු', 'නව_රූප' ],
	'Newpages'                  => [ 'නව_පිටුව' ],
	'Preferences'               => [ 'අභිරුචියන්' ],
	'Prefixindex'               => [ 'උපසර්ග_සූචිය' ],
	'Protectedpages'            => [ 'ආරක්‍ෂිත_පිටුව' ],
	'Protectedtitles'           => [ 'ආරක්‍ෂිත_ශීර්ෂයන්' ],
	'Randompage'                => [ 'අහඹු', 'අහඹු_පිටුව' ],
	'Randomredirect'            => [ 'අහඹු_යළි-යොමුකිරිම' ],
	'Recentchanges'             => [ 'මෑත_වෙනස්වීම්' ],
	'Recentchangeslinked'       => [ 'සබැඳුනු_මෑත_වෙනස්කිරීම්', 'මෑත_වෙනස්කිරීම්' ],
	'Revisiondelete'            => [ 'සංශෝධන_මකාදමන්න' ],
	'Search'                    => [ 'ගවේෂණය' ],
	'Shortpages'                => [ 'කෙටි_පිටු' ],
	'Specialpages'              => [ 'විශේෂ_පිටු' ],
	'Statistics'                => [ 'සංඛ්‍යාන_දත්ත' ],
	'Tags'                      => [ 'ටැග' ],
	'Unblock'                   => [ 'වාරණය_ඉවත්කල' ],
	'Uncategorizedcategories'   => [ 'ප්‍රවර්ගීකරනය_නොකල_ප්‍රවර්ග' ],
	'Uncategorizedimages'       => [ 'ප්‍රවර්ගීකරනය_නොකල_රූප' ],
	'Uncategorizedpages'        => [ 'ප්‍රවර්ගීකරනය_නොකල_පිටු' ],
	'Uncategorizedtemplates'    => [ 'ප්‍රවර්ගීකරනය_නොකල_සැකිලි' ],
	'Undelete'                  => [ 'මකාදැමීම_අවලංගු_කරන්න' ],
	'Unlockdb'                  => [ 'දත්ත_සංචිතය_ඇවුරුම_අවලංගු_කරන්න' ],
	'Unusedcategories'          => [ 'හාවිතා_නොවන_ප්‍රවර්ග' ],
	'Unusedimages'              => [ 'හාවිතා_නොවන_රූප' ],
	'Unusedtemplates'           => [ 'භාවිත_නොකල_සැකිලි' ],
	'Unwatchedpages'            => [ 'මුර_නොකල_පිටු' ],
	'Upload'                    => [ 'උඩුගත_කිරීම' ],
	'Userlogin'                 => [ 'පරිශීලක_ප්‍රවිෂ්ටය' ],
	'Userlogout'                => [ 'පරිශීලක_නිෂ්ක්‍රමණය' ],
	'Userrights'                => [ 'පරිශීලක_හිමිකම්' ],
	'Version'                   => [ 'සංශෝධනය' ],
	'Wantedcategories'          => [ 'අවශ්‍ය_ප්‍රවර්ග' ],
	'Wantedfiles'               => [ 'අවශ්‍ය_ගොනු' ],
	'Wantedpages'               => [ 'අවශ්‍ය_පිටු' ],
	'Wantedtemplates'           => [ 'අවශ්‍ය_සැකිලි' ],
	'Watchlist'                 => [ 'මුර_ලැයිස්තුව' ],
	'Whatlinkshere'             => [ 'මෙහි_කුමක්_සබැඳී_ඇතිද' ],
	'Withoutinterwiki'          => [ 'අන්තර්_විකි_නොමැතිව' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#යළියොමුව', '#REDIRECT' ],
	'currentmonth'              => [ '1', 'වත්මන්මාසය', 'වත්මන්මාසය2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'වත්මන්මාසය1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'වත්මන්මාසනාමය', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'වත්මන්මාසනාමයපොදු', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'වත්මන්මාසයකෙටි', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'වත්මන්දිනය', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'වත්මන්දිනය2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'වත්මන්දිනනාමය', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'වත්මන්වසර', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'වත්මන්වේලාව', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'වත්මන්පැය', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'දේශීයමාසය', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'දේශීයමාසනාමය', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'දේශීයමාසනාමයපොදු', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'දේශීයමාසයකෙටි', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'දේශීයදිනය', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'දේශීයදිනය2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'දේශීයදිනනාමය', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'දේශීයවසර', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'දේශීයවේලාව', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'දේශීයපැය', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'පිටුසංඛ්‍යාව', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ලිපිසංඛ්‍යාව', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'ගොනුසංඛ්‍යාව', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'පරිශීලකයන්සංඛ්‍යාව', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'සංස්කරණසංඛ්‍යාව', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'පිටුනාමය', 'PAGENAME' ],
	'namespace'                 => [ '1', 'නාමඅවකාශය', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'නාමඅවකාශයන්', 'NAMESPACEE' ],
	'msg'                       => [ '0', 'පණිවුඩ:', 'MSG:' ],
	'img_right'                 => [ '1', 'දකුණ', 'right' ],
	'img_left'                  => [ '1', 'වම', 'left' ],
	'img_none'                  => [ '1', 'නොමැත', 'none' ],
	'img_width'                 => [ '1', '$1පික්', '$1px' ],
	'img_center'                => [ '1', 'මධ්‍යය', 'center', 'centre' ],
	'img_border'                => [ '1', 'දාරය', 'border' ],
	'img_sub'                   => [ '1', 'උප', 'sub' ],
	'img_middle'                => [ '1', 'මැද', 'middle' ],
	'special'                   => [ '0', 'විශේෂ', 'special' ],
];

