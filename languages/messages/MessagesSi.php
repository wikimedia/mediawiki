<?php
/** Sinhala (සිංහල)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'රූපය' => NS_FILE,
	'රූපය_සාකච්ඡාව' => NS_FILE_TALK,
	'විකිමාධ්‍ය' => NS_MEDIAWIKI,
	'විකිමාධ්‍ය_සාකච්ඡාව' => NS_MEDIAWIKI_TALK,
	'උදව_සාකච්ඡාව' => NS_HELP_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ක්‍රියාකාරී_පරිශීලකයන්' ),
	'Allmessages'               => array( 'සියළු_පණිවුඩ' ),
	'Allpages'                  => array( 'සියළු_පිටු' ),
	'Ancientpages'              => array( 'පුරාතන_පිටු' ),
	'Badtitle'                  => array( 'නුසුසුදු_මාතෘකාව' ),
	'Blankpage'                 => array( 'හිස්_පිටුව' ),
	'Block'                     => array( 'වාරණය_කරන්න', 'IP_වාරණය_කරන්න', 'පරිශීලක_වාරණය_කරන්න' ),
	'Booksources'               => array( 'ග්‍රන්ථ_මූලාශ්‍ර' ),
	'BrokenRedirects'           => array( 'භින්න_යළි-යොමුකිරීම්' ),
	'Categories'                => array( 'ප්‍රවර්ග' ),
	'ChangePassword'            => array( 'මුරපදය_වෙනස්_කරන්න', 'මුරපදය_ප්‍රතිස්ථාපනය_කරන්න' ),
	'Confirmemail'              => array( 'විද්‍යුත්-තැපෑල_තහවුරු_කරන්න' ),
	'Contributions'             => array( 'දායකත්ව' ),
	'CreateAccount'             => array( 'ගිණුම_තැනීමට' ),
	'Deadendpages'              => array( 'අග_ඇවුරුණු_පිටුව' ),
	'DeletedContributions'      => array( 'මකාදැමුණු_දායකත්වයන්' ),
	'DoubleRedirects'           => array( 'ද්විත්ව_යළි-යොමුකිරීම්' ),
	'Emailuser'                 => array( 'පරිශීලකට_විද්‍යුත්-තැපැලක්_යැවිම' ),
	'Export'                    => array( 'නිර්යාතකරන්න' ),
	'Fewestrevisions'           => array( 'අතිස්වල්ප_සංශෝධන' ),
	'FileDuplicateSearch'       => array( 'ගොනු_අනුපිටපත්_ගවේෂණය' ),
	'Filepath'                  => array( 'ගොනු_පෙත' ),
	'Import'                    => array( 'ආයාත_කරන්න' ),
	'Invalidateemail'           => array( 'විද්‍යුත්_තැපෑල_අනීතික_කරන්න' ),
	'BlockList'                 => array( 'වාරණ_ලැයිස්තුව', 'වාරණ_ලයිස්තුගතකරන්න_', 'IP_වාරණ_ලැයිස්තුව' ),
	'LinkSearch'                => array( 'සබැඳි_ගවේෂණය' ),
	'Listadmins'                => array( 'පරිපාලකයන්_ලැයිස්තුගත_කරන්න' ),
	'Listbots'                  => array( 'රොබෝවන්_ලැයිස්තුගත_කරන්න' ),
	'Listfiles'                 => array( 'රූප_ලැයිස්තුව' ),
	'Listgrouprights'           => array( 'කණ්ඩායම්_හිමිකම්_ලැයිස්තුගතකරන්න' ),
	'Listredirects'             => array( 'යළි-යොමුකිරීම්_ලැයිස්තුගතකරන්න' ),
	'Listusers'                 => array( 'පරිශීලකයන්_ලැයිස්තු_ගත_කරන්න', 'පරිශීලක_ලැයිස්තුව' ),
	'Lockdb'                    => array( 'දත්ත_සංචිතය_අවුරන්න' ),
	'Log'                       => array( 'ලඝු_සටහන', 'ලඝු_සටහන්' ),
	'Lonelypages'               => array( 'හුදකලා_පිටු' ),
	'Longpages'                 => array( 'දිගු_පිටු' ),
	'MergeHistory'              => array( 'ඒකාබද්ධ_ඉතිහාසය' ),
	'MIMEsearch'                => array( 'MIME_ගවේෂණය' ),
	'Mostcategories'            => array( 'බොහෝ_ප්‍රවර්ග' ),
	'Mostimages'                => array( 'බෙහෙවින්_සබැඳි_ගොනු', 'බොහෝ_ගොනු', 'බොහෝ_රූප' ),
	'Mostlinked'                => array( 'බෙහෙවින්_සබැඳි_පිටු', 'බෙහෙවින්_සබැඳි' ),
	'Mostlinkedcategories'      => array( 'බෙහෙවින්_සබැඳි_ප්‍රවර්ග', 'බෙහෙවින්_භාවිත_ප්‍රවර්ග' ),
	'Mostlinkedtemplates'       => array( 'බෙහෙවින්_සබැඳි_සැකිලි', 'බෙහෙවින්_භාවිත_සැකිලි' ),
	'Mostrevisions'             => array( 'බොහෝ_සංශෝධන' ),
	'Movepage'                  => array( 'පිටුව_ගෙන_යන්න' ),
	'Mycontributions'           => array( 'මගේ_දායකත්වය' ),
	'Mypage'                    => array( 'මගේ_පිටුව' ),
	'Mytalk'                    => array( 'මගේ_සාකච්ඡාව' ),
	'Newimages'                 => array( 'නව_ගොනු', 'නව_රූප' ),
	'Newpages'                  => array( 'නව_පිටුව' ),
	'Popularpages'              => array( 'ජනප්‍රිය_පිටු' ),
	'Preferences'               => array( 'අභිරුචියන්' ),
	'Prefixindex'               => array( 'උපසර්ග_සූචිය' ),
	'Protectedpages'            => array( 'ආරක්‍ෂිත_පිටුව' ),
	'Protectedtitles'           => array( 'ආරක්‍ෂිත_ශීර්ෂයන්' ),
	'Randompage'                => array( 'අහඹු', 'අහඹු_පිටුව' ),
	'Randomredirect'            => array( 'අහඹු_යළි-යොමුකිරිම' ),
	'Recentchanges'             => array( 'මෑත_වෙනස්වීම්' ),
	'Recentchangeslinked'       => array( 'සබැඳුනු_මෑත_වෙනස්කිරීම්', 'මෑත_වෙනස්කිරීම්' ),
	'Revisiondelete'            => array( 'සංශෝධන_මකාදමන්න' ),
	'Search'                    => array( 'ගවේෂණය' ),
	'Shortpages'                => array( 'කෙටි_පිටු' ),
	'Specialpages'              => array( 'විශේෂ_පිටු' ),
	'Statistics'                => array( 'සංඛ්‍යාන_දත්ත' ),
	'Tags'                      => array( 'ටැග' ),
	'Unblock'                   => array( 'වාරණය_ඉවත්කල' ),
	'Uncategorizedcategories'   => array( 'ප්‍රවර්ගීකරනය_නොකල_ප්‍රවර්ග' ),
	'Uncategorizedimages'       => array( 'ප්‍රවර්ගීකරනය_නොකල_රූප' ),
	'Uncategorizedpages'        => array( 'ප්‍රවර්ගීකරනය_නොකල_පිටු' ),
	'Uncategorizedtemplates'    => array( 'ප්‍රවර්ගීකරනය_නොකල_සැකිලි' ),
	'Undelete'                  => array( 'මකාදැමීම_අවලංගු_කරන්න' ),
	'Unlockdb'                  => array( 'දත්ත_සංචිතය_ඇවුරුම_අවලංගු_කරන්න' ),
	'Unusedcategories'          => array( 'හාවිතා_නොවන_ප්‍රවර්ග' ),
	'Unusedimages'              => array( 'හාවිතා_නොවන_රූප' ),
	'Unusedtemplates'           => array( 'භාවිත_නොකල_සැකිලි' ),
	'Unwatchedpages'            => array( 'මුර_නොකල_පිටු' ),
	'Upload'                    => array( 'උඩුගත_කිරීම' ),
	'Userlogin'                 => array( 'පරිශීලක_ප්‍රවිෂ්ටය' ),
	'Userlogout'                => array( 'පරිශීලක_නිෂ්ක්‍රමණය' ),
	'Userrights'                => array( 'පරිශීලක_හිමිකම්' ),
	'Version'                   => array( 'සංශෝධනය' ),
	'Wantedcategories'          => array( 'අවශ්‍ය_ප්‍රවර්ග' ),
	'Wantedfiles'               => array( 'අවශ්‍ය_ගොනු' ),
	'Wantedpages'               => array( 'අවශ්‍ය_පිටු' ),
	'Wantedtemplates'           => array( 'අවශ්‍ය_සැකිලි' ),
	'Watchlist'                 => array( 'මුර_ලැයිස්තුව' ),
	'Whatlinkshere'             => array( 'මෙහි_කුමක්_සබැඳී_ඇතිද' ),
	'Withoutinterwiki'          => array( 'අන්තර්_විකි_නොමැතිව' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#යළියොමුව', '#REDIRECT' ),
	'currentmonth'              => array( '1', 'වත්මන්මාසය', 'වත්මන්මාසය2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'වත්මන්මාසය1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'වත්මන්මාසනාමය', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'වත්මන්මාසනාමයපොදු', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'වත්මන්මාසයකෙටි', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'වත්මන්දිනය', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'වත්මන්දිනය2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'වත්මන්දිනනාමය', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'වත්මන්වසර', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'වත්මන්වේලාව', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'වත්මන්පැය', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'දේශීයමාසය', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'දේශීයමාසනාමය', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'දේශීයමාසනාමයපොදු', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'දේශීයමාසයකෙටි', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'දේශීයදිනය', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'දේශීයදිනය2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'දේශීයදිනනාමය', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'දේශීයවසර', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'දේශීයවේලාව', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'දේශීයපැය', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'පිටුසංඛ්‍යාව', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ලිපිසංඛ්‍යාව', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ගොනුසංඛ්‍යාව', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'පරිශීලකයන්සංඛ්‍යාව', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'සංස්කරණසංඛ්‍යාව', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'පිටුනාමය', 'PAGENAME' ),
	'namespace'                 => array( '1', 'නාමඅවකාශය', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'නාමඅවකාශයන්', 'NAMESPACEE' ),
	'msg'                       => array( '0', 'පණිවුඩ:', 'MSG:' ),
	'img_right'                 => array( '1', 'දකුණ', 'right' ),
	'img_left'                  => array( '1', 'වම', 'left' ),
	'img_none'                  => array( '1', 'නොමැත', 'none' ),
	'img_width'                 => array( '1', '$1පික්', '$1px' ),
	'img_center'                => array( '1', 'මධ්‍යය', 'center', 'centre' ),
	'img_border'                => array( '1', 'දාරය', 'border' ),
	'img_sub'                   => array( '1', 'උප', 'sub' ),
	'img_middle'                => array( '1', 'මැද', 'middle' ),
	'special'                   => array( '0', 'විශේෂ', 'special' ),
);

