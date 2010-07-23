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
 * @author Calcey
 * @author Chandana
 * @author Jiro Ono
 * @author Meno25
 * @author Romaine
 * @author Singhalawap
 * @author චතුනි අලහප්පෙරුම
 * @author තඹරු විජේසේකර
 * @author දසනැබළයෝ
 * @author නන්දිමිතුරු
 * @author බිඟුවා
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
	'DoubleRedirects'           => array( 'ද්විත්ව යළි-යොමුකිරීම්' ),
	'BrokenRedirects'           => array( 'භින්න යළි-යොමුකිරීම්' ),
	'Disambiguations'           => array( 'වක්‍රෝත්තිහරණයන්' ),
	'Userlogin'                 => array( 'පරිශීලක ප්‍රවිෂ්ටය' ),
	'Userlogout'                => array( 'පරිශීලක නිෂ්ක්‍රමණය' ),
	'CreateAccount'             => array( 'ගිණුම තැනීමට' ),
	'Preferences'               => array( 'අභිරුචියන්' ),
	'Watchlist'                 => array( 'මුරලැයිස්තුව' ),
	'Recentchanges'             => array( 'මෑත වෙනස්වීම්' ),
	'Upload'                    => array( 'උඩුගත කිරීම' ),
	'Listfiles'                 => array( 'රූප ලැයිස්තුව' ),
	'Newimages'                 => array( 'අලුත් රූප' ),
	'Listusers'                 => array( 'පරිශීලකයන් ලැයිස්තු ගත කරන්න', 'පරිශීලක ලැයිස්තුව' ),
	'Listgrouprights'           => array( 'කණ්ඩායම් හිමිකම් ලැයිස්තුගතකරන්න' ),
	'Statistics'                => array( 'සංඛ්‍යාන දත්ත' ),
	'Randompage'                => array( 'අහඹු ලෙස', 'අහඹු පිටුව' ),
	'Lonelypages'               => array( 'හුදකලා පිටුව' ),
	'Uncategorizedpages'        => array( 'ප්‍රවර්ගීකරනය නොකල පිටු' ),
	'Uncategorizedcategories'   => array( 'ප්‍රවර්ගීකරනය නොකල ප්‍රවර්ග' ),
	'Uncategorizedimages'       => array( 'ප්‍රවර්ගීකරනය නොකල රූප' ),
	'Uncategorizedtemplates'    => array( 'ප්‍රවර්ගීකරනය නොකල සැකිලි' ),
	'Unusedcategories'          => array( 'හාවිතා නොවන ප්‍රවර්ග' ),
	'Unusedimages'              => array( 'හාවිතා නොවන රූප' ),
	'Wantedpages'               => array( 'අවශ්‍ය පිටු' ),
	'Wantedcategories'          => array( 'අවශ්‍ය ප්‍රවර්ග' ),
	'Wantedfiles'               => array( 'අවශ්‍ය ගොනු' ),
	'Wantedtemplates'           => array( 'අවශ්‍ය සැකිලි' ),
	'Mostlinked'                => array( 'බොහෝ ලෙසින් සබැඳි' ),
	'Mostlinkedcategories'      => array( 'බොහෝ ලෙසින් සබැඳි ප්‍රවර්ග', 'බෙහෙවින් භාවිතවූ ප්‍රවර්ග' ),
	'Mostlinkedtemplates'       => array( 'බොහෝ ලෙසින් සබැඳි සැකිලි', 'බෙහෙවින් භාවිතවූ සැකිලි' ),
	'Mostimages'                => array( 'බොහෝ රූප' ),
	'Mostcategories'            => array( 'බොහෝ ප්‍රවර්ග' ),
	'Mostrevisions'             => array( 'බොහෝ සංශෝධන' ),
	'Fewestrevisions'           => array( 'අතිස්වල්ප සංශෝධන' ),
	'Shortpages'                => array( 'කොට පිටුව' ),
	'Longpages'                 => array( 'දිගු පිටුව' ),
	'Newpages'                  => array( 'නව පිටුව' ),
	'Ancientpages'              => array( 'පුරාතන පිටුව' ),
	'Deadendpages'              => array( 'අග ඇවුරුණු පිටුව' ),
	'Protectedpages'            => array( 'ආරක්ෂිත පිටුව' ),
	'Protectedtitles'           => array( 'ආරක්ෂිත ශීර්ෂයන්' ),
	'Allpages'                  => array( 'සියළු පිටු' ),
	'Prefixindex'               => array( 'උපසර්ග සූචිය' ),
	'Ipblocklist'               => array( 'අන්තර්ජාල ලිපින වාරණ ලැයිස්තුව' ),
	'Specialpages'              => array( 'විශේෂ පිටු' ),
	'Contributions'             => array( 'දායකත්ව' ),
	'Emailuser'                 => array( 'පරිශීලකට විද්‍යුත්-තැපැලක් යැවිම' ),
	'Confirmemail'              => array( 'විද්‍යුත්-තැපෑල තහවුරු කරන්න' ),
	'Whatlinkshere'             => array( 'මෙහි කුමක් සබැඳී ඇතිද' ),
	'Recentchangeslinked'       => array( 'සබැඳුනු මෑත වෙනස්වීම්', 'මෑතවෙනස් වීම්' ),
	'Movepage'                  => array( 'පිටුව ගෙන යනවා' ),
	'Blockme'                   => array( 'මා වාරණය කරන්න' ),
	'Booksources'               => array( 'ග්‍රන්ථ මූලාශ්‍ර' ),
	'Categories'                => array( 'ප්‍රවර්ගයන්' ),
	'Export'                    => array( 'නිර්යාතකරන්න' ),
	'Version'                   => array( 'අනුවාදය' ),
	'Allmessages'               => array( 'සියළු පණිවුඩ' ),
	'Log'                       => array( 'ලඝු සටහන', 'ලඝු සටහන්' ),
	'Blockip'                   => array( 'අන්තර්ජාල ලිපිනය වාරණය කරන්න' ),
	'Undelete'                  => array( 'මකාදැමීම අවලංගු කරන්න' ),
	'Import'                    => array( 'ආයාත කරන්න' ),
	'Lockdb'                    => array( 'දත්ත ගබඩාව අවුරන්න' ),
	'Unlockdb'                  => array( 'දත්ත ගබඩාව ඇවුරුම අවලංගු කරන්න' ),
	'Userrights'                => array( 'පරිශීලක හිමිකම්' ),
	'MIMEsearch'                => array( 'MIME ගවේෂණය' ),
	'FileDuplicateSearch'       => array( 'ගොනු අනුපිටපත් ගවේෂණය' ),
	'Unwatchedpages'            => array( 'මුර නොකල පිටු' ),
	'Listredirects'             => array( 'යළි-යොමුකිරීම් ලැයිස්තුගතකරන්න' ),
	'Revisiondelete'            => array( 'සංශෝධන මකාදමන්න' ),
	'Unusedtemplates'           => array( 'භාවිත නොකල සැකිලි' ),
	'Randomredirect'            => array( 'අහඹු යළි-යොමුකිරිම' ),
	'Mypage'                    => array( 'මගේ පිටුව' ),
	'Mytalk'                    => array( 'මගේ සාකච්ඡාව' ),
	'Mycontributions'           => array( 'මගේ දායකත්වය' ),
	'Listadmins'                => array( 'පරිපාලකයන් ලැයිස්තුගත කරන්න' ),
	'Listbots'                  => array( 'රොබෝවරුන් ලැයිස්තුගතකරන්න' ),
	'Popularpages'              => array( 'ජනප්‍රිය පිටු' ),
	'Search'                    => array( 'ගවේෂණය' ),
	'Resetpass'                 => array( 'මුර පදය ප්‍රතිෂ්ඨාපනය කරන්න' ),
	'Withoutinterwiki'          => array( 'අන්තර් විකි නොමැතිව' ),
	'MergeHistory'              => array( 'ඒකාබද්ධ ඉතිහාසය' ),
	'Filepath'                  => array( 'ගොනු පෙත' ),
	'Invalidateemail'           => array( 'විද්්‍යුත් තැපෑල අනීතික කරන්න' ),
	'Blankpage'                 => array( 'හිස් පිටුව' ),
	'LinkSearch'                => array( 'සබැඳි ගවේෂණය' ),
	'DeletedContributions'      => array( 'මකාදැමුණු දායකත්වයන්' ),
	'Tags'                      => array( 'ටැග' ),
	'Activeusers'               => array( 'ක්‍රියාකාරීපරිශීලකයන්' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#යළියොමුව', '#REDIRECT' ),
	'currentmonth'          => array( '1', 'වත්මන්මාසය', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'වත්මන්මාසනාමය', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'වත්මන්මාසයකෙටි', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'වත්මන්දිනය', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'වත්මන්දිනය2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'වත්මන්දිනනාමය', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'වත්මන්වසර', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'වත්මන්වේලාව', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'වත්මන්පැය', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'දේශීයමාසය', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'දේශීයමාසනාමය', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'දේශීයමාසයකෙටි', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'දේශීයදිනය', 'LOCALDAY' ),
	'localday2'             => array( '1', 'දේශීයදිනය2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'දේශීයදිනනාමය', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'දේශීයවසර', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'දේශීයවේලාව', 'LOCALTIME' ),
	'localhour'             => array( '1', 'දේශීයපැය', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'පිටුසංඛ්‍යාව', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ලිපිසංඛ්‍යාව', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ගොනුසංඛ්‍යාව', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'පරිශීලකයන්සංඛ්‍යාව', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'සංස්කරණසංඛ්‍යාව', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'පිටුනාමය', 'PAGENAME' ),
	'img_right'             => array( '1', 'දකුණ', 'right' ),
	'img_left'              => array( '1', 'වම', 'left' ),
	'img_none'              => array( '1', 'නොමැත', 'none' ),
	'img_width'             => array( '1', '$1පික්', '$1px' ),
	'img_center'            => array( '1', 'මධ්‍යය', 'center', 'centre' ),
	'special'               => array( '0', 'විශේෂ', 'special' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'සබැඳි යටීර කිරීම:',
'tog-highlightbroken'         => 'භින්න සබැඳියන් ආකෘතිකරණය මේ අයුරින් කරන්න <a href="" class="new"> </a> (විකල්ප: මේ අයුරින් කරන්න<a href="" class="internal">?</a>).',
'tog-justify'                 => 'ඡේදයන් පේළි ගසන්න',
'tog-hideminor'               => 'මෑත වෙනස්වීම්හි සුළු සංස්කරණ සඟවන්න',
'tog-hidepatrolled'           => 'විමසුමට ලක්කෙරුණු සංස්කරණයන්, මෑත වෙනස්වීම් හී නොපෙන්වන්න',
'tog-newpageshidepatrolled'   => 'විමසුමට ලක්කෙරුණු පිටු, අළුත් පිටු ලැයිස්තුවෙහි නොපෙන්වන්න',
'tog-extendwatchlist'         => 'මෑත වෙනස්වීම් පමණක් නොව, අදාළ සියළු වෙනස්වීම් දක්වා පෙන්වන අයුරින් මුර-ලැයිස්තුව පුළුල් කරන්න',
'tog-usenewrc'                => 'ආවර්ධිත මෑත වෙනස්වීම් භාවිතා කරන්න (ජාවාස්ක්‍රිප්ට් ඇවැසිය)',
'tog-numberheadings'          => 'ශීර්ෂ-නාම ස්වයංක්‍රීයව අංකනය කරන්න',
'tog-showtoolbar'             => 'සංස්කරණ මෙවලම්තීරුව පෙන්වන්න (ජාවාස්ක්‍රිප්ට්)',
'tog-editondblclick'          => 'ද්විත්ව-ක්ලික් කිරීම මගින් පිටු සංස්කරණය අරඹන්න (ජාවාස්ක්‍රිප්ට්)',
'tog-editsection'             => '[සංස්කරණය] සබැඳියාවන් මගින් ඡේද සංස්කරණය සක්‍රීය කරන්න',
'tog-editsectiononrightclick' => 'ඡේද ශීර්ෂ මත දකුණු-ක්ලික් කිරීමෙන් ඡේද සංස්කරණය සක්‍රීය කරන්න (ජාවාස්ක්‍රිප්ට්)',
'tog-showtoc'                 => 'පටුන පෙන්වන්න ( තුනකට වඩා වැඩියෙන් ශීර්ෂ-නාම අඩංගු පිටු සඳහා)',
'tog-rememberpassword'        => 'මාගේ ප්‍රවිෂ්ටය පිළිබඳ විස්තර මෙම පරිගණක මතකයෙහි (උපරිම ලෙස{{PLURAL:$1|දිනයක්|දින $1 ක්}}) තබාගන්න',
'tog-watchcreations'          => 'මම තනන පිටු මගේ මුර-ලැයිස්තුවට එක් කරන්න',
'tog-watchdefault'            => 'මම සංස්කරණය කරන පිටු මගේ මුර-ලැයිස්තුවට එක් කරන්න',
'tog-watchmoves'              => 'මම ගෙනයන පිටු මගේ මුර-ලැයිස්තුවට එක් කරන්න',
'tog-watchdeletion'           => 'මම මකාදමන පිටු මගේ මුර-ලැයිස්තුවට එක් කරන්න',
'tog-previewontop'            => 'සංස්කරණ කොටුවට පෙරාතුව පෙර-දසුන පෙන්වන්න',
'tog-previewonfirst'          => 'පළමු සංස්කරණයෙහිදී පෙර-දසුන පෙන්වන්න',
'tog-nocache'                 => 'පිටු සඳහා පූර්වාපේක්‍ෂී සංචිතකරණය (කෑෂ්) කිරීම අක්‍රීය කරන්න',
'tog-enotifwatchlistpages'    => 'මගේ මුර-ලැයිස්තුවේ පිටුවක් වෙනස් වූ විට මා හට විද්‍යුත්-තැපෑලක් එවන්න',
'tog-enotifusertalkpages'     => 'මගේ පරිශීලක සාකච්ඡා පිටුව වෙනස් වූ විට මා හට විද්‍යුත්-තැපෑලක් එවන්න',
'tog-enotifminoredits'        => 'පිටුවල  සුළු-සංස්කරණ වලදී පවා මා හට විද්‍යුත්-තැපෑලක් එවන්න',
'tog-enotifrevealaddr'        => 'දැනුම්දීමේ විද්‍යුත්-තැපෑලෙහිදී මාගේ විද්‍යුත්-තැපැල් ලිපිනය හෙළි කරන්න',
'tog-shownumberswatching'     => 'මුර කරනු ලබන පරිශීලකයන් සංඛ්‍යාව පෙන්වන්න',
'tog-oldsig'                  => 'පවතින අත්සනෙහි පූර්ව දර්ශනය:',
'tog-fancysig'                => 'අත්සන විකිපෙළ (ස්වයංක්‍රීය සබැඳියක් විරහිතව) ලෙසින් සලකන්න',
'tog-externaleditor'          => 'පෙරනිමියෙන් බාහිර සංස්කාරකයක් භාවිත කරන්න (ප්‍රවීණයන් සඳහා පමණි, ඔබගේ පරිගණකයට විශේෂ  පරිස්ථිතීන් යෙදවිය යුතුවේ)',
'tog-externaldiff'            => 'පෙරනිමියෙන් බාහිර වෙනස භාවිතා කරන්න (ප්‍රවීණයන් සඳහා පමණයි, ඔබගේ පරිගණකයෙහි විශේෂ පරිස්ථිතීන් අවශ්‍යයයි)',
'tog-showjumplinks'           => '"වෙත පනින්න"  යන ප්‍රවේශතා සබැඳියන් සක්‍රීය කරන්න',
'tog-uselivepreview'          => 'තත්කාල පෙර-දසුන භාවිතා කරන්න (ජාවාස්ක්‍රිප්ට්) (පරීක්ෂණාත්මක)',
'tog-forceeditsummary'        => 'හිස් සංස්කරණ සාරාංශයකට මා ඇතුළු වන විට මාහට ඉඟි කරන්න',
'tog-watchlisthideown'        => 'මුර-ලැයිස්තුවෙන් මාගේ සංස්කරණ සඟවන්න',
'tog-watchlisthidebots'       => 'මුර-ලැයිස්තුවෙන් රොබෝ සංස්කරණ සඟවන්න',
'tog-watchlisthideminor'      => 'මුර-ලැයිස්තුවෙන් සුළු සංස්කරණ සඟවන්න',
'tog-watchlisthideliu'        => 'ප්‍රවිෂ්ට වී ඇති පරිශීලකයන් විසින් සිදුකර ඇති සංස්කරණ මුර-ලැයිස්තුවෙන් සඟවන්න',
'tog-watchlisthideanons'      => 'නිර්නාමික පරිශීලකයන් විසින් සිදුකොට ඇති සංස්කරණ මුර-ලැයිස්තුවෙන් සඟවන්න',
'tog-watchlisthidepatrolled'  => 'විමසුමට ලක්කෙරුණු සංස්කරණයන්, මෑත වෙනස්වීම් හී නොපෙන්වන්න',
'tog-nolangconversion'        => 'විචල්‍යයන් පෙරැළීම අක්‍රීය කරන්න',
'tog-ccmeonemails'            => 'මා විසින් අනෙකුත් පරිශීලකයන් හට යවන විද්‍යුත්-තැපෑලයන්හි පිටපත් මාහට එවන්න',
'tog-diffonly'                => '“වෙනස් ”පදයන්ට පහළින්, පිටුවල අන්තර්ගතය   නොපෙන්වන්න',
'tog-showhiddencats'          => 'සැඟවුනු ප්‍රවර්ග පෙන්වන්න',
'tog-noconvertlink'           => 'සබැඳියන්ගේ ශීර්ෂ පෙරැළීම අක්‍රීය කරන්න',
'tog-norollbackdiff'          => 'පුනරාවර්තනයක් කිරීමෙන් පසු වෙනස්වීම් අත්හරින්න',

'underline-always'  => 'සැමවිටම කරන්න',
'underline-never'   => 'කිසිවිටෙක නොකරන්න',
'underline-default' => 'බ්‍රවුසරයෙහි පෙරනිමිය අනුවය',

# Font style option in Special:Preferences
'editfont-style'     => 'අකුරු විලාසයන් සංස්කරණ පෙදෙස:',
'editfont-default'   => 'පෙර නිමි බ්‍රව්සරය',
'editfont-monospace' => 'ඒක අවකාශිත ෆොන්ට්',
'editfont-sansserif' => 'Sans-serif අකුරු',
'editfont-serif'     => 'Serif අකුරු',

# Dates
'sunday'        => 'ඉරිදා',
'monday'        => 'සඳුදා',
'tuesday'       => 'අඟහරුවාදා',
'wednesday'     => 'බදාදා',
'thursday'      => 'බ්‍රහස්පතින්දා',
'friday'        => 'සිකුරාදා',
'saturday'      => 'සෙනසුරාදා',
'sun'           => 'ඉරිදා',
'mon'           => 'සඳුදා',
'tue'           => 'අඟ',
'wed'           => 'බදාදා',
'thu'           => 'බ්‍රහස්',
'fri'           => 'සිකු',
'sat'           => 'සෙන',
'january'       => 'ජනවාරි',
'february'      => 'පෙබරවාරි',
'march'         => 'මාර්තු',
'april'         => 'අප්‍රේල්',
'may_long'      => 'මැයි',
'june'          => 'ජූනි',
'july'          => 'ජූලි',
'august'        => 'අගෝස්තු',
'september'     => 'සැප්තැම්බර්',
'october'       => 'ඔක්තෝබර්',
'november'      => 'නොවැම්බර්',
'december'      => 'දෙසැම්බර්',
'january-gen'   => 'ජනවාරි',
'february-gen'  => 'පෙබරවාරි',
'march-gen'     => 'මාර්තු',
'april-gen'     => 'අප්‍රේල්',
'may-gen'       => 'මැයි',
'june-gen'      => 'ජූනි',
'july-gen'      => 'ජූලි',
'august-gen'    => 'අගෝස්තු',
'september-gen' => 'සැප්තැම්බර්',
'october-gen'   => 'ඔක්තෝබර්',
'november-gen'  => 'නොවැම්බර්',
'december-gen'  => 'දෙසැම්බර්',
'jan'           => 'ජන',
'feb'           => 'පෙබ',
'mar'           => 'මාර්තු',
'apr'           => 'අප්‍රේල්',
'may'           => 'මැයි',
'jun'           => 'ජූනි',
'jul'           => 'ජූලි',
'aug'           => 'අගෝ',
'sep'           => 'සැප්',
'oct'           => 'ඔක්',
'nov'           => 'නොවැ',
'dec'           => 'දෙසැ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|ප්‍රවර්ගය|ප්‍රවර්ග}}',
'category_header'                => '"$1" ප්‍රවර්ගයට අයත් පිටු',
'subcategories'                  => 'උපප්‍රවර්ග',
'category-media-header'          => '"$1" ප්‍රවර්ගයට අයත් මාධ්‍ය',
'category-empty'                 => "''දැනට මෙම ප්‍රවර්ගය පිටු හෝ මාධ්‍ය හෝ නොදරයි.''",
'hidden-categories'              => '{{PLURAL:$1|සැඟවුනු ප්‍රවර්ගය|සැඟවුනු ප්‍රවර්ග}}',
'hidden-category-category'       => 'සැඟවුනු ප්‍රවර්ග',
'category-subcat-count'          => '{{PLURAL:$2|මෙම ප්‍රවර්ගය සතු වන්නේ පහත දැක්වෙන උපප්‍රවර්ගය පමණි.| මෙම ප්‍රවර්ගය සතු  උපප්‍රවර්ග $2  ක් අතර, පහත දැක්වෙන {{PLURAL:$1|උපප්‍රවර්ගය|උපප්‍රවර්ග $1 }} වේ.}}',
'category-subcat-count-limited'  => 'මෙම ප්‍රවර්ගයට පහත දැක්වෙන {{PLURAL:$1| එක් උපප්‍රවර්ගයක්| උපප්‍රවර්ග $1 ක්}} අයත් වේ.',
'category-article-count'         => '{{PLURAL:$2|මෙම ප්‍රවර්ගය සතු වන්නේ මෙහි පහත දැක්වෙන පිටුව පමණි.| මෙම ප්‍රවර්ගය සතු සමස්ත  පිටු $2 අතර, පහත දැක්වෙන {{PLURAL:$1|පිටුවද වේ.|පිටු $1 ද වෙති.}}}}',
'category-article-count-limited' => 'මෙහි පහත දැක්වෙන  {{PLURAL:$1|පිටුව|පිටු $1 }} අයත් වනුයේ වත්මන් ප්‍රවර්ගයටය.',
'category-file-count'            => '{{PLURAL:$2|මෙම ප්‍රවර්ගයට අයත් වන්නේ පහත දැක්වෙන ගොනුව පමණි.| මෙම ප්‍රවර්ගය සතු සමස්ත ගොනු  $2 අතර, මෙහි පහත දැක්වෙන {{PLURAL:$1|ගොනුවද වේ.|ගොනු $1 ද වෙති.}}}}',
'category-file-count-limited'    => 'මෙහි පහත දැක්වෙන {{PLURAL:$1|ගොනුව|ගොනු $1 }} අයත් වන්නේ වත්මන් ප්‍රවර්ගයටය.',
'listingcontinuesabbrev'         => 'ඉතිරිය.',
'index-category'                 => 'සූචිගත පිටු',
'noindex-category'               => 'සූචිගත නොකළ පිටු',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',
'mainpagetext'      => "'''මාධ්‍යවිකි සාර්ථක ලෙස ස්ථාපනය කරන ලදි.'''",
'mainpagedocfooter' => 'විකි මෘදුකාංග භාවිතා කිරීම පිළිබඳ තොරතුරු සඳහා  [http://meta.wikimedia.org/wiki/Help:Contents පරිශීලකයන් සඳහා නියමුව] හදාරන්න.

== ඇරඹුම ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings වින්‍යාස පරිස්ථිතීන් ලැයිස්තුව]
* [http://www.mediawiki.org/wiki/Manual:FAQ මාධ්‍යවිකි නිතර-අසන-පැන]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce මාධ්‍යවිකි නිකුතුව තැපැල් ලැයිස්තුව]',

'about'         => 'පිළිබඳ',
'article'       => 'පටුන',
'newwindow'     => '(නව කවුළුවක විවෘතවේ)',
'cancel'        => 'අත් හරින්න',
'moredotdotdot' => 'තවත්...',
'mypage'        => 'මගේ පිටුව',
'mytalk'        => 'මගේ සාකච්ඡා',
'anontalk'      => 'මෙම අන්තර්ජාල ලිපිනය සඳහා සාකච්ඡාව',
'navigation'    => 'සංචලනය',
'and'           => '&#32;සහ',

# Cologne Blue skin
'qbfind'         => 'සොයන්න',
'qbbrowse'       => 'පිරික්සන්න',
'qbedit'         => 'සංස්කරණය',
'qbpageoptions'  => 'මෙම පිටුව',
'qbpageinfo'     => 'සන්දර්භය',
'qbmyoptions'    => 'මගේ පිටු',
'qbspecialpages' => 'විශේෂ පිටු',
'faq'            => 'නිතර-අසන-පැන',
'faqpage'        => 'Project:නිතර-අසන-පැන',

# Vector skin
'vector-action-addsection'   => 'මාතෘකාව එක්කරන්න',
'vector-action-delete'       => 'මකාදමන්න',
'vector-action-move'         => 'ගෙනයන්න',
'vector-action-protect'      => 'ආරක්ෂා කරන්න',
'vector-action-undelete'     => 'මකාදැමීම අවලංගු කරන්න',
'vector-action-unprotect'    => 'ආරක්ෂා‍ නොකරන්න',
'vector-namespace-category'  => 'ප්‍රවර්ගය',
'vector-namespace-help'      => 'උදවු පිටුව',
'vector-namespace-image'     => 'ගොනුව',
'vector-namespace-main'      => 'පිටුව',
'vector-namespace-media'     => 'මාධ්‍ය පිටුව',
'vector-namespace-mediawiki' => 'පණිවුඩය',
'vector-namespace-project'   => 'ව්‍යාපෘති පිටුව',
'vector-namespace-special'   => 'විශේෂ පිටුව',
'vector-namespace-talk'      => 'සංවාදය',
'vector-namespace-template'  => 'සැකිල්ල',
'vector-namespace-user'      => 'පරිශීලක පිටුව',
'vector-view-create'         => 'තනන්න',
'vector-view-edit'           => 'සංස්කරණය කරන්න',
'vector-view-history'        => 'ඉතිහාසය නරඹන්න',
'vector-view-view'           => 'කියවන්න',
'vector-view-viewsource'     => 'මූලාශ්‍රය නරඹන්න',
'actions'                    => 'කාර්යයන්',
'namespaces'                 => 'නාමඅවකාශයන්',
'variants'                   => 'ප්‍රභේද්‍යයන්',

'errorpagetitle'    => 'දෝෂය',
'returnto'          => '$1 ට නැවත යන්න.',
'tagline'           => '{{SITENAME}} වෙතින්',
'help'              => 'උදවු',
'search'            => 'ගවේෂණය',
'searchbutton'      => 'සොයන්න',
'go'                => 'යන්න',
'searcharticle'     => 'යන්න',
'history'           => 'පිටුවේ ඉතිහාසය',
'history_short'     => 'ඉතිහාසය',
'updatedmarker'     => 'මාගේ අවසාන පිවිසුමෙන් පසුව යාවත්කාලීන කර ඇත',
'info_short'        => 'තොරතුරු',
'printableversion'  => 'මුද්‍රණයකලහැකි සංස්කරණය',
'permalink'         => 'ස්ථාවර සබැඳුම',
'print'             => 'මුද්‍රණය කරන්න',
'edit'              => 'සංස්කරණය කරන්න',
'create'            => 'තනන්න',
'editthispage'      => 'මෙම පිටුව සංස්කරණය කරන්න',
'create-this-page'  => 'මෙම පිටුව තනන්න',
'delete'            => 'මකන්න',
'deletethispage'    => 'මෙම පිටුව මකන්න',
'undelete_short'    => '{{PLURAL:$1|එක් සංස්කරණයක|සංස්කරණ $1 ක}} මකා දැමීම ප්‍රතිලෝම කරන්න',
'protect'           => 'ආරක්‍ෂණය කරන්න',
'protect_change'    => 'වෙනස් කරන්න',
'protectthispage'   => 'මෙම පිටුව ආරක්‍ෂණය කරන්න',
'unprotect'         => 'ආරක්‍ෂණය කිරීමෙන් ඉවත් වන්න',
'unprotectthispage' => 'මෙම පිටුව ආරක්‍ෂණය කිරීමෙන් ඉවත් වන්න',
'newpage'           => 'නව පිටුව',
'talkpage'          => 'මෙම පිටුව පිළිබඳ සංවාදයකට එළඹෙන්න',
'talkpagelinktext'  => 'සාකච්ඡාව',
'specialpage'       => 'විශේෂ පිටුව',
'personaltools'     => 'පුද්ගලික මෙවලම්',
'postcomment'       => 'නව ඡේදයක්',
'articlepage'       => 'අන්තර්ගත පිටුව නරඹන්න',
'talk'              => 'සංවාදය',
'views'             => 'නැරඹුම්',
'toolbox'           => 'මෙවලම් ගොන්න',
'userpage'          => 'පරිශීලක පිටුව නරඹන්න',
'projectpage'       => 'ව්‍යාපෘති පිටුව නරඹන්න',
'imagepage'         => 'ගොනු පිටුව නරඹන්න',
'mediawikipage'     => 'පණිවුඩ පිටුව නරඹන්න',
'templatepage'      => 'සැකිලි පිටුව නරඹන්න',
'viewhelppage'      => 'උදවු පිටුව නරඹන්න',
'categorypage'      => 'ප්‍රවර්ග පිටුව නරඹන්න',
'viewtalkpage'      => 'සංවාදය නරඹන්න',
'otherlanguages'    => 'වෙනත් භාෂා වලින්',
'redirectedfrom'    => '($1 වෙතින් යළි-යොමු කරන ලදි)',
'redirectpagesub'   => 'පිටුව යළි-යොමු කරන්න',
'lastmodifiedat'    => 'මෙම පිටුව අවසන් වරට වෙනස් කරන ලද්දේ $1 දිනදී, $2 වේලාවෙහිදීය.',
'viewcount'         => 'මෙම පිටුවට  {{PLURAL:$1|එක් වරක්|$1 වරක්}} පිවිස ඇත.',
'protectedpage'     => 'ආරක්ෂිත පිටුව',
'jumpto'            => 'වෙත පනින්න:',
'jumptonavigation'  => 'සංචලනය',
'jumptosearch'      => 'ගවේෂණය',
'view-pool-error'   => 'සමාවන්න, සේවාදායකයන් මෙම අවස්ථාවෙහිදී අධිභාරණයට ලක් වී ඇත.
පමණට වඩා පරිශීලක පිරිසක් මෙම පිටුව නැරඹීමට යත්න දරති.
මෙම පිටුවට පිවිසීමට නැවත උත්සාහ දැරීමට පෙර මදක් පමාවන්න.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} පිළිබඳ
<!--{{SITENAME}}About-->',
'aboutpage'            => 'Project:පිළිබඳ',
'copyright'            => ' $1 යටතේ අන්තර්ගතය දැක ගත හැක.',
'copyrightpage'        => '{{ns:project}}:කර්තෘ-හිමිකම්',
'currentevents'        => 'කාලීන සිදුවීම්',
'currentevents-url'    => 'Project:කාලීන සිදුවීම්',
'disclaimers'          => 'වියාචනයන්',
'disclaimerpage'       => 'Project:පොදු වියාචන',
'edithelp'             => 'සංස්කරණ උදවු',
'edithelppage'         => 'Help:සංස්කරණ',
'helppage'             => 'Help:පටුන',
'mainpage'             => 'මුල් පිටුව',
'mainpage-description' => 'මුල් පිටුව',
'policy-url'           => 'Project:ප්‍රතිපත්තිය',
'portal'               => 'ප්‍රජා ද්වාරය',
'portal-url'           => 'Project:ප්‍රජා ද්වාරය',
'privacy'              => 'පුද්ගලිකත්ව ප්‍රතිපත්තිය',
'privacypage'          => 'Project:පුද්ගලිකත්ව ප්‍රතිපත්තිය',

'badaccess'        => 'අවසරදීමේ දෝෂයකි',
'badaccess-group0' => 'ඔබ විසින් අයැදුම් කර සිටි කාර්යය ක්‍රියාත්මක කිරීමට ඔබ හට ඉඩ ලබා දෙනු නොලැබේ.',
'badaccess-groups' => 'ඔබ අයැදුම් කර සිටි කාර්යය, ඉදිරි {{PLURAL:$2| කාණ්ඩයට| කාණ්ඩ සමූහය අතුරින් එකකට}} අයත් පරිශීලකයන්ගේ පරිහරණයට සීමා කර ඇත: $1.',

'versionrequired'     => 'මාධ්‍යවිකි $1 අනුවාදය අවශ්‍ය වේ',
'versionrequiredtext' => 'මෙම පිටුව භාවිතා කිරීමට, මාධ්‍යවිකි හි $1 අනුවාදය අවශ්‍ය වේ.
[[Special:Version|අනුවාද පිටුව]] බලන්න.',

'ok'                      => 'හරි',
'retrievedfrom'           => '"$1" වෙතින් නැවත ලබාගන්නා ලදි',
'youhavenewmessages'      => 'ඔබ හට ඇති $1 ($2)',
'newmessageslink'         => 'නව පණිවුඩ',
'newmessagesdifflink'     => 'අවසාන වෙනස',
'youhavenewmessagesmulti' => 'ඔබ හට $1 හි නව පණිවුඩ ඇත',
'editsection'             => 'සංස්කරණය',
'editsection-brackets'    => '[$1]',
'editold'                 => 'සංස්කරණය',
'viewsourceold'           => 'මූලාශ්‍රය නරඹන්න',
'editlink'                => 'සංස්කරණය',
'viewsourcelink'          => 'මූලාශ්‍රය නරඹන්න',
'editsectionhint'         => 'ඡේද සංස්කරණය: $1',
'toc'                     => 'පටුන',
'showtoc'                 => 'පෙන්වන්න',
'hidetoc'                 => 'සඟවන්න',
'thisisdeleted'           => 'අවශ්‍යතාවය $1 නැරඹීමද නැතහොත් ප්‍රතිෂ්ඨාපනයද?',
'viewdeleted'             => '$1 නැරඹීම අවශ්‍යයද?',
'restorelink'             => 'මකා දමනු ලැබූ {{PLURAL:$1| එක් සංස්කරණයක්| සංස්කරණ $1  ක්}}',
'feedlinks'               => 'පෝෂකය:',
'feed-invalid'            => 'දායකත්ව පෝෂකයෙහි ශෛලිය අනීතිකය.',
'feed-unavailable'        => 'සමග්‍රහ පෝෂකයන් නොමැත',
'site-rss-feed'           => '$1 RSS පෝෂකය',
'site-atom-feed'          => '$1 Atom පෝෂකය',
'page-rss-feed'           => '"$1" RSS පෝෂකය',
'page-atom-feed'          => '"$1" Atom පෝෂකය',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (පිටුව නොපවතියි)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'පිටුව',
'nstab-user'      => 'පරිශීලක පිටුව',
'nstab-media'     => 'මාධ්‍ය පිටුව',
'nstab-special'   => 'විශේෂ පිටුව',
'nstab-project'   => 'ව්‍යාපෘති පිටුව',
'nstab-image'     => 'ගොනුව',
'nstab-mediawiki' => 'පණිවුඩය',
'nstab-template'  => 'සැකිල්ල',
'nstab-help'      => 'උදවු පිටුව',
'nstab-category'  => 'ප්‍රවර්ගය',

# Main script and global functions
'nosuchaction'      => 'මෙම නමැති කාර්යයක් නොමැත',
'nosuchactiontext'  => 'URL (කලාප ලිපිනය) විසින් හුවා දක්වා ඇති කෘත්‍යය අනීතිකයි.
ඔබ සමහරවිට URL (කලාප ලිපිනය) අකුරු වරදවා සටහන් කර ඇත, නැතහොත් සාවද්‍යය සබැඳුම ඔස්සේ පැමිණ ඇත. 
මෙය සමහරවිට {{SITENAME}} විසින් භාවිතා කරන මෘදුකාංගයන්හි දෝෂයක් පිළිඹිබු කරන්නක්ද විය හැක.',
'nosuchspecialpage' => 'මෙම නමැති විශේෂ පිටුවක් නොමැත',
'nospecialpagetext' => '<strong>ඔබ අයැද ඇත්තේ අනීතික විශේෂ පිටුවකි.</strong>

වලංගු විශේෂ පිටු දැක්වෙන ලැයිස්තුවක් [[Special:SpecialPages|{{int:specialpages}}]]හිදී ඔබහට සම්භ වනු ඇත.',

# General errors
'error'                => 'දෝෂය',
'databaseerror'        => 'දත්ත-ගබඩා දෝෂය',
'dberrortext'          => 'දත්ත-ගබඩා විමසුම් කාරක-රීති දෝෂයක් සිදුවී ඇත.
මෙය මෘදුකාංගයේ දෝෂයක් හඟවන්නක් විය හැක.
අවසන් වරට උත්සාහ කල දත්ත-ගබඩා විමසුම:
"<tt>$2</tt>" ශ්‍රිතය අනුසාරයෙන්
<blockquote><tt>$1</tt></blockquote> විය.
දත්ත-ගබඩාව විසින් වාර්තා කල දෝෂය "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'දත්ත-ගබඩා විමසුමෙහි කාරක-රීති දෝෂයක් හට ගෙන ඇත.
අවසන් වරට උත්සාහ කල දත්ත-ගබඩා විමසුම:
"$2" ශ්‍රිතය අනුසාරයෙන්,
"$1" විය
දත්ත-ගබඩාව විසින් වාර්තා කල දෝෂය "$3: $4"',
'laggedslavemode'      => "'''අවවාදයයි:''' මෑත යාවත්කාලීන කිරීම් මෙම පිටුවෙහි අඩංගු නොවීමට ඉඩ ඇත.",
'readonly'             => 'දත්තගබඩාව අවුරා ඇත',
'enterlockreason'      => 'අවුරා දැමීමට හේතුවක් සපයන අතරතුර, ඇවිරීම මුදාහැරීමට බලාපොරොත්තු වන කාලසීමාව නිමානය කර දක්වන්න',
'readonlytext'         => 'බොහෝ විට චර්යානුගත නඩත්තු කටයුතු හේතුවෙන්, දත්ත-ගබඩාව වෙත නව ප්‍රවේශනයන් හා එය අරභයා අනෙකුත් වෙනස් කිරීම්,  දැනට අහුරා ඇති අතර, ඉහත කටයුතු නිම වීමෙන් අනතුරුව තත්ත්වය සාමාන්‍ය කෙරෙනු ඇත.

එය ඇවුරූ පරිපාලක වරයා මෙම පැහැදිලි කිරීම ලබා දුනි: $1',
'missing-article'      => 'සොයාගත යුතුව තිබූ,   "$1" $2 නමැති, පිටුවක පෙළ සොයාගැනුමට දත්ත සංචිතය අසමත් විය.

මකා දැමූ පිටුවක ඉතිහාසය සබැඳියන් හෝ යල් පැන ගිය වෙනස හෝ ඔස්සේ පැමිණීම, මෙවැන්නක් සාමාන්‍යයෙන් ඇති කරයි.

හේතුව මෙය නොවේ නම්, ඔබ විසින් මෘදුකාංගයෙහි දෝෂයක්(bug) සොයාගෙන ඇත.
URL  සඳහන් කරමින්, මෙම කරුණ [[Special:ListUsers/sysop|පරිපාලකවරයෙකුට]] වාර්තාකරන්න.',
'missingarticle-rev'   => '(සංශෝධන#: $1)',
'missingarticle-diff'  => '(වෙනස: $1, $2)',
'readonly_lag'         => 'ගෝල දත්ත-ගබඩා සර්වරයන්හි ක්‍රියාශීලිත්වය  ගුරු සර්වර මට්ටමට පත් වන තෙක් දත්ත-ගබඩාව ස්වයංක්‍රීය ලෙස ඇවුරුමකට ලක්ව ඇත',
'internalerror'        => 'අභ්‍යන්තර දෝෂය',
'internalerror_info'   => 'අභ්‍යන්තර දෝෂය: $1',
'fileappenderrorread'  => 'එක්කිරීමේදී "$1" නියවීමට නොහැකි විය.',
'fileappenderror'      => '"$2" වෙත "$1" යා කල නොහැක.',
'filecopyerror'        => '"$1" ගොනුව "$2" වෙත පිටපත් කිරීමට නොහැකි විය.',
'filerenameerror'      => '"$1" ගොනුව "$2" බවට යළි-නම්-කිරීම සිදු කල නොහැකි විය.',
'filedeleteerror'      => '"$1" ගොනුව මකා-දැමිය නොහැකි විය.',
'directorycreateerror' => '"$1" නාමාවලිය තැනීම කල නොහැකි විය.',
'filenotfound'         => '"$1" ගොනුව සොයා ගත නොහැකි විය.',
'fileexistserror'      => '"$1" ගොනුව වෙත ලිවීම කල නොහැකි විය: ගොනුව පවතියි',
'unexpected'           => 'අනපේක්‍ෂිත අගය: "$1"="$2".',
'formerror'            => 'දෝෂය: ආකෘති-පත්‍රය ඉදිරිපත් කල නොහැකි විය',
'badarticleerror'      => 'මෙම පිටුව විෂයයෙහි මෙම කාර්යය ඉටු නල නොහැකි විය.',
'cannotdelete'         => '"$1" පිටුව හෝ ගොනුව හෝ මකා දැමිය නොහැකි විය.
අනෙකෙකු විසින් දැනටමත් ‍මකා දැමීම සිදු කර ඇතිවා විය හැක.',
'badtitle'             => 'නුසුදුසු ශීර්ෂයක්',
'badtitletext'         => 'අයැද ඇති පිටු ශීර්ෂය අනීතික, හිස් හෝ වැරදි ලෙස සබැඳි අන්තර්-භාෂා/අන්තර්-විකී ශීර්ෂයකි.
ශීර්ෂයන්හි භාවිතා කල නොහැකි අක්ෂර එකක් හෝ කිහිපයක් හෝ එහි අඩංගු වී ඇතිවා විය හැක.',
'perfcached'           => 'පහත දැක්වෙන දත්ත පූර්වාපේක්‍ෂිතව සංචිත කෙරී ඇති (කෑෂ් කෙරී ඇති) බැවින් ඒවා යවත්කාලීන නොවීමට ඉඩ ඇත.',
'perfcachedts'         => 'පහත දත්ත පූර්වාපේක්‍ෂීව සංචිත කෙරී ඇති (කෑෂ් කෙරී ඇති) අතර, අවසන් වරට යාවත්කාලීන කර ඇත්තේ  $1 දීය.',
'querypage-no-updates' => 'මෙම පිටුව සඳහා යාවත්කාල කිරීම් දැනට අක්‍රීය කොට ඇත.
දැනට මෙහිදී දත්ත පුනස්ථාපනය සිදු නොවේ.',
'wrong_wfQuery_params' => ' wfQuery() සඳහා සාවද්‍ය පරාමිතිකයන්<br />
ශ්‍රිතය: $1<br />
විමසුම: $2',
'viewsource'           => 'මූලාශ්‍රය නරඹන්න',
'viewsourcefor'        => '$1 සඳහා',
'actionthrottled'      => 'ක්‍රියාව අවකරණය කරන ලදි',
'actionthrottledtext'  => 'අයාචිත තැපෑල  වැලකීමේ ක්‍රියාමාර්ගයක් ලෙස, ඔබ විසින්, කෙටි කාල සීමාවක් තුල, පමණට වැඩි වාර ගණනක් මෙම ක්‍රියාව සිදු කිරීම, සීමා කර ඇති අතර, ඔබ මෙම සීමාව ඉක්මවා ඇත.
විනාඩි කිහිපයකින් පසුව නැවත උත්සාහ කරන්න.',
'protectedpagetext'    => 'සංස්කරණයන් වලක්වනු වස් මෙම පිටුව අවුරා ඇත.',
'viewsourcetext'       => 'මෙම පිටුවෙහි මූලාශ්‍රය නැරඹීමට හා පිටපත් කිරීමට ඔබ හට හැකිය:',
'protectedinterface'   => 'මෙම පිටුව විසින්, මෘදුකාංගය සඳහා අතුරුමුව පෙළ සපයන අතර එබැවින් අපයෙදුම වැලැක්වීම සඳහා එය අවුරා ඇත.',
'editinginterface'     => "'''අවවාදයයි:''' මෘදුකාංගයට අතුරුමුව පෙළ සැපයීමට භාවිතා වන පිටුවක් ඔබ විසින් සංස්කරණය කරනු ලබයි.
මෙම පිටුවට සිදු කරන වෙනස්වීම් විසින් අනෙකුත් පරිශීලකයන්ගේ පරිශීලක අතුරුමුවෙහි පෙනුමට බලපෑම් එල්ල කෙරෙනු ඇත.
පරිවර්තන සඳහා, මාධ්‍යවිකි ප්‍රාදේශීයකරන ව්‍යාපෘතිය, [http://translatewiki.net/wiki/Main_Page?setlang=si translatewiki.net], භාවිතා කිරීම සලකා බැලීමට කාරුණික වන්න.",
'sqlhidden'            => '(එස්කිවුඑල් විපරම සඟවා ඇත)',
'cascadeprotected'     => '"තීරු දර්ශන" විකල්පය සක්‍රීයනය කොට එමගින් ආරක්‍ෂණය කල පහත දැක්වෙන {{PLURAL:$1|පිටුව|පිටු}} අඩංගු කර ඇති බැවින්, මෙම පිටුව සංස්කරණය කිරීමෙන් වලකා ඇත:
$2',
'namespaceprotected'   => "'''$1''' නාමඅවකාශයෙහි පිටු සංස්කරණය කිරීමට ඔබහට අවසර නොමැත.",
'customcssjsprotected' => 'තවත් පරිශීලකයෙකුගේ පෞද්ගලික පරිස්ථිතිය අඩංගු වන බැවින්, මෙම පිටුව සංස්කරණය කිරීමට ඔබ හට අවසර නොමැත.',
'ns-specialprotected'  => 'විශේෂ පිටු සංස්කරණය කිරීම සිදු කල නොහැක.',
'titleprotected'       => "මෙම ශීර්ෂ-නාමය තැනීම  [[User:$1|$1]] විසින් වාරණය කොට ඇත.
මේ සඳහා  ''$2''  හේතුව දක්වා ඇත.",

# Virus scanner
'virus-badscanner'     => "අයෝග්‍ය වික්‍යාසයකි: අඥාත වයිරස සුපිරික්සකයකි: ''$1''",
'virus-scanfailed'     => 'පරිලෝකනය අසාර්ථක විය (කේතය $1)',
'virus-unknownscanner' => 'අඥාත ප්‍රතිවයිරසයක්:',

# Login and logout pages
'logouttext'                 => "'''ඔබ දැන් නිෂ්ක්‍රමණය වී ඇත.'''

ඔබට නිර්නාමිකව {{SITENAME}} කටයුතු කරගෙන යාහැක, නැතහොත් පෙර පරිශීලක ලෙස හෝ වෙනත් පරිශීලකයෙකු ලෙස [[Special:UserLogin|නැවත ප්‍රවිෂ්ට විය හැක]].
ඔබගේ බ්‍රවුසරයෙහි පූර්වාපේක්‍ෂී සංචිතය (කෑෂය) නිෂ්කාශනය කරන තෙක්, සමහරක් පිටු විසින් ඔබ තවදුරටත් ප්‍රවිෂ්ට වී ඇති බවක් දිගටම පෙන්නුම් කිරීමට ඉඩ ඇත.",
'welcomecreation'            => '== ආයුබෝවන්, $1! ==

ඔබ‍ගේ ගිණුම තැනී ඇත.
ඔබ‍ගේ [[Special:Preferences|{{SITENAME}} අභිරුචි ]] වෙනස් කිරීම අමතක නොකරන්න.',
'yourname'                   => 'පරිශීලක නාමය:',
'yourpassword'               => 'මුරපදය:',
'yourpasswordagain'          => 'මුරපදය යළි ඇතුළු කරන්න:',
'remembermypassword'         => 'මාගේ ප්‍රවිෂ්ටය පිළිබඳ විස්තර මෙම පරිගණක මතකයෙහි (උපරිම ලෙස{{PLURAL:$1|දිනයක්|දින $1 ක්}}) තබාගන්න',
'yourdomainname'             => 'ඔබගේ වසම:',
'externaldberror'            => 'එක්කෝ සත්‍යාවත් දත්ත-ගබඩා දෝෂයක් පැවතුනි නැතිනම් ඔබගේ බාහිර ගිණුම යාවත්කාලීන කිරීමට ඔබ හට අවසර දී නොමැත.',
'login'                      => 'ප්‍රවිෂ්ටය',
'nav-login-createaccount'    => 'ප්‍රවිෂ්ටවන්න / නව ගිණුමක් අරඹන්න',
'loginprompt'                => '{{SITENAME}} වෙත ප්‍රවිෂ්ට වීම සඳහා ඔබ විසින් කුකීස් සක්‍රීය කොට තිබිය යුතුය.',
'userlogin'                  => 'ප්‍රවිෂ්ටවන්න / නව ගිණුමක් අරඹන්න',
'userloginnocreate'          => 'ප්‍රවිෂ්ට වන්න',
'logout'                     => 'නිෂ්ක්‍රමණය',
'userlogout'                 => 'නිෂ්ක්‍රමණය',
'notloggedin'                => 'ප්‍රවිෂ්ට වී නොමැත',
'nologin'                    => "ඔබ හට ගිණුමක් නොමැතිද? '''$1'''.",
'nologinlink'                => 'ගිණුමක් තනන්න',
'createaccount'              => 'ගිණුම තනන්න',
'gotaccount'                 => "දැනටමත් ගිණුමක් තිබේද? '''$1'''.",
'gotaccountlink'             => 'ප්‍රවිෂ්ට වන්න',
'createaccountmail'          => 'විද්‍යුත් තැපෑල මගින්',
'badretype'                  => 'ඔබ ඇතුළු කල මුරපදය නොගැලපේ.',
'userexists'                 => 'ඔබ ඇතුළු කල පරිශීලක නාමය දැනටමත් භාවිතයෙහි ඇත.
කරුණාකර වෙනත් නමක් තෝරා ගන්න.',
'loginerror'                 => 'ප්‍රවිෂ්ට වීමේ දෝෂයකි',
'createaccounterror'         => 'ගිණුම නිර්මාණය කළ නොහැකි විය:$1',
'nocookiesnew'               => 'පරිශීලක ගිණුම තනා ඇති නමුදු, ඔබ ප්‍රවිෂ්ට වී නොමැත.
පරිශීලකයන් ප්‍රවිෂ්ට කර ගැනීම සඳහා, {{SITENAME}} විසින් කුකී භාවිතා කරයි.
ඔබ විසින් කුකී අක්‍රීය කර ඇත.
කරුණාකර ඒවා සක්‍රීය කොට, ඔබගේ නව පරිශීලක-නාමය හා මුර-පදය ඇසුරෙන් ප්‍රවිෂ්ට වන්න.',
'nocookieslogin'             => 'පරිශීලකයන් ප්‍රවිෂ්ට කර ගැනීම සඳහා, {{SITENAME}} විසින් කුකී භාවිතා කරනු ලැබේ.
ඔබ විසින් කුකී අක්‍රීය නොට ඇත.
කරුණාකර, ඒවා සක්‍රීය කොට, නැවත උත්සාහ ‍කරන්න.',
'noname'                     => 'වලංගු පරිශීලක-නාමයක් සඳහන් කිරීමට ඔබ අසමත් වී ඇත.',
'loginsuccesstitle'          => 'ප්‍රවිෂ්ට වීම සාර්ථකයි',
'loginsuccess'               => "'''ඔබ දැන්, \"\$1\" ලෙස, {{SITENAME}} යට ප්‍රවිෂ්ට විමට සමත් වී ඇත.'''",
'nosuchuser'                 => '"$1" යන නමැති පරිශීලකයෙකු නොමැත.
පරිශීලක නාමයන්හි මහාප්‍රාණ ආදිය සැලකේ.
ඔබගේ අක්ෂර-වින්‍යාසය පිරික්සා බැලීම හෝ, [[Special:UserLogin/signup|නව ගිණුමක් තැනීම]] හෝ සිදුකරන්න.',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" නමින් පරිශීලකයෙකු නොමැත.
අක්‍ෂර-වින්‍යාසය පිරික්සා බලන්න.',
'nouserspecified'            => 'ඔබ විසින් පරිශීලක-නාමයක් සඳහන් කල යුතු වේ.',
'login-userblocked'          => 'මෙම පරිශීලකයා වාරණය කොට ඇත. ප්‍රවිෂ්ට වීමට ඉඩ දෙනු නොලැබේ.',
'wrongpassword'              => 'සාවද්‍ය මුර-පදයක් ඇතුළත් කෙරිණි.
නැවත උත්සාහකරන්න.',
'wrongpasswordempty'         => 'හිස් මුර-පදයක් ඇතුළත් කෙරිණි.
නැවත උත්සාහ කරන්න.',
'passwordtooshort'           => 'මුරපදය අඩුම වශයෙන් {{PLURAL:$1|එක් අක්ෂරයක්|අක්ෂර $1 ක්}} සහිත විය යුතුය.',
'password-name-match'        => 'ඔබගේ මුරපදය, ඔබගේ පරිශීලක නාමයෙන් වෙනස් එකක් විය යුතුය.',
'mailmypassword'             => 'නව මුරපදය විද්‍යුත් තැපෑල‍ට යවන්න',
'passwordremindertitle'      => '{{SITENAME}} සඳහා නව තාවකාලික මුර-පදය',
'passwordremindertext'       => 'යම් අයෙකු  ($1 අන්තර්ජාල ලිපිනය තුලින් සමහර විට ඔබ) විසින්  {{SITENAME}} ($4)සඳහා නව මුර-පදයක් ඉල්ලා සිට ඇත. පරිශීලක "$2"  වෙනුවෙන් තාවකාලික  මුර-පදයක් තනා "$3" බවට නියම කර ඇත. මෙය ඔබගේ අභිලාශය වූයේ නම් ඔබ විසින් ළහිළහියේ ප්‍රවිෂ්ට වී, නව මුර-පදයක් තෝරා ගත යුතුව ඇත.ඔබගේ තාවකාලික මුරපදය  {{PLURAL:$5|එක් දිනකින්|දින $5 කින්}}කල් ඉකුත්වනු ඇත.

වෙන යම් අයෙකු විසින් මෙම ආයාචනය සිදු කර ඇත්නම් හෝ ඔබ හට ඔබගේ මුර-පදය නැවත සිහිවුනි නම් හා එබැවින් එය වෙනස් කිරීම තවදුරටත් ඔබගේ අභිලාෂය නොවේ නම්  මෙම පණිවුඩය නොසලකාහරිමින් ඔබගේ පැරැණි මුර-පදය දිගටම භාවිතා කරන්න.',
'noemail'                    => 'පරිශීලක  "$1" සඳහා විද්‍යුත්-තැපැල් ලිපිනයක් සටහන් වී නොමැත.',
'noemailcreate'              => 'ඔබ වලංගු ඊ-මේල් ලිපිනයක් සැපයිය යුතුය',
'passwordsent'               => ' "$1" වෙනුවෙන් ලේඛනගත කර ඇති විද්‍යුත් තැපැල් ලිපිනයට නව මුර පදයක් යවා ඇත.
ඔබට එය ලැබුනු පසු නැවත ප්‍රවිෂ්ට වන්න.',
'blocked-mailpassword'       => 'ඔබගේ අන්තර්ජාල ලිපිනය භාවිතා කරමින් සංස්කරණයෙහි යෙදීම වාරණය කොට ඇති අතර, අපයෙදුම වළකනු වස්,  මුර-පදය පුනරුත්ථාන  කෘත්‍යය භාවිත කිරීමට අවසරයද අහිමි කොට ඇත.',
'eauthentsent'               => 'නම් කර ඇති විද්‍යුත්-ලිපි ලිපිනය වෙත, තහවුරු කිරීම් විද්‍යුත්-ලිපියක් යවා ඇත.
ගිණුම වෙත වෙනත් විද්‍යුත්-ලිපියක්  යැවීමට පෙර, ගිණුම සත්‍ය වශයෙන්ම ඔබගේම බව තහවුරු කරනු වස්, විද්‍යුත්-ලිපියෙහි අඩංගු උපදෙස්  පිළිපදින්න.',
'throttled-mailpassword'     => 'අවසන් {{PLURAL:$1| පැය|පැය $1 }} අතරතුරදී, දැනටමත් එක් මුර-පද සිහිගැන්වීමක් යවා ඇත .
අපයෙදුම වළක්වනු වස්, {{PLURAL:$1|එක් පැයක| පැය $1 ක}}ට වරක් එක් මුර-පද සිහිගැන්වීමක් පමණක් යවනු ලැබේ.',
'mailerror'                  => 'තැපෑල යැවීමේදී වූ දෝෂය: $1',
'acct_creation_throttle_hit' => 'ඔබගේ අන්තර්ජාල ලිපිනය භාවිතා කල මෙම විකියට අමුත්තන් විසින් {{PLURAL:$1|එක් ගිණුමක්|ගිණුම් $1 ක්}} පසුගිය දිනය තුලදී තනා ඇති අතර, එය මෙම කාල පරිච්ඡේදය තුලදී ඉඩ දෙනු ලබන උපරිමය වෙයි.
මේ හේතුවෙන්, මෙම අන්තර්ජාල ලිපිනය භාවිතා කරන අමුත්තන් විසින් මෙම අවස්ථාවෙහිදී තවත් ගිණුම් තැනීම සිදු කල නොහැකිව ඇත.',
'emailauthenticated'         => '$2 දින $3 වේලාවෙහිදී ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය සත්‍යවත් කරන ලදි.',
'emailnotauthenticated'      => 'ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය තවමත් සත්‍යවත් කර නොමැත.
පහත හැකියාවන් කිසිවක් ඉටුකරනු වස් විද්‍යුත්-තැපෑල  යවනු නොලැබේ.',
'noemailprefs'               => 'පහත හැකියාවන් ඉටුකිරීමට ඉඩ සලසනු වස් විද්‍යුත්-තැපැල් ලිපිනයක් හුවා දක්වන්න.',
'emailconfirmlink'           => 'ඔබගේ විද්‍යුත් තැපැල් ලිපිනය තහවුරු කරන්න',
'invalidemailaddress'        => 'විද්‍යුත්-තැපැල් ලිපිනයෙහි  ආකෘතිය අනීතික බවක් ‍ පිළිබිඹු කරන බැවින් එය පිළිගත නොහැක.
මනා-ආකෘතියකින් සුසැදි ලිපිනයක් ඇතුළත් කිරීමට හෝ එම ක්ෂේත්‍රය සිස් කිරීම‍ට හෝ කාරුණික වන්න.',
'accountcreated'             => 'ගිණුම තනන ලදි',
'accountcreatedtext'         => ' $1 සඳහා පරිශීලක ගිණුම තනන ලදි.',
'createaccount-title'        => '{{SITENAME}} සඳහා ගිණුම තැනීම',
'createaccount-text'         => 'කිසියම් අයෙකු, "$2" නමින් හා, "$3" යන මුර-පදය යොදමින්,  ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය සඳහා {{SITENAME}} ($4) හි ගිණුමක් තනා ඇත.
ඔබ දැන් ප්‍රවිෂ්ට වී, ඔබගේ මුර-පදය වෙනස් කල යුතුව ඇත.

මෙම ගිණුම තැනී ඇත්තේ ප්‍රමාද දෝෂයකින් නම්, මෙම පණිවුඩය නොසලකා හැරිය හැක.',
'usernamehasherror'          => 'පරිශීලක නාමයේ පූරක අනුලකුණු අඩංගු විය නොහැකිය',
'login-throttled'            => 'ඔබ විසින් මෑතදී  පමණට වඩා වාර ගණනක් ප්‍රවිෂ්ට වීමට උත්සාහ දරා ඇත.
යළි උත්සාහ කිරීමට පෙර කරුණාකර මදක් පොරොත්තු වන්න.',
'loginlanguagelabel'         => 'භාෂාව: $1',
'suspicious-userlogout'      => 'නිෂ්ක්‍රමණය සඳහා ඔබගේ අයැදුම නිෂ්ප්‍රභා කෙරුනේ එය යොමු කොට ඇත්තේ භින්න(කැඩුනු) බ්‍රවුසරයකින් හෝ නිවේෂණය කෙරෙමින් පවතින ප්‍රොක්සියක් වෙතින් යැයි බැලූ බැල්මට පෙනෙන බැවිනි.',

# Password reset dialog
'resetpass'                 => 'මුර-පදය වෙනස් කරන්න',
'resetpass_announce'        => 'විද්‍යුත්-තැපෑලෙන් එවනු ලැබූ තාවකාලික කේතයක් උපයෝගී කර ගනිමින් ඔබ ප්‍රවිෂ්ට වී ඇත.
ප්‍රවිෂ්ට වීම නිසි ලෙස නිමවනු වස් ඔබ සැකසූ නව මුර-පදයක් මෙහි බහාලිය යුතු වේ:',
'resetpass_text'            => '<!-- මෙතැනට පෙළ එක් කරන්න -->',
'resetpass_header'          => 'ගිණුම් මුර-පදය වෙනස් කරන්න',
'oldpassword'               => 'පැරැණි මුර-පදය:',
'newpassword'               => 'නව මුර-පදය:',
'retypenew'                 => 'නව මුර-පදය නැවත ඇතුළු කරන්න:',
'resetpass_submit'          => 'මුර-පදය පූරණය කොට ඉන් පසු ප්‍රවිෂ්ට වන්න',
'resetpass_success'         => 'ඔබගේ මුර-පදය සාර්ථක ලෙස වෙනස් කරන ලදි! දැන් ඔබව ප්‍රවිෂ්ට කරගනිමින්...',
'resetpass_forbidden'       => 'මුර-පදයන් වෙනස් කිරීම  සිදු කල නොහැක',
'resetpass-no-info'         => 'මෙම පිටුව සෘජු ලෙස පරිශීලනය කෙරුමට ඔබ පළමු ප්‍රවිෂ්ට විය යුතුය.',
'resetpass-submit-loggedin' => 'මුර-පදය වෙනස්කරන්න',
'resetpass-submit-cancel'   => 'අත් හරින්න',
'resetpass-wrong-oldpass'   => 'තාවකාලික හෝ වත්මන් මුර-පදය අනීතිකයි. 
ඔබ දැනටමත් සාර්ථක ලෙස ඔබගේ මුර-පදය වෙනස් කොට හෝ නව තාවකාලික මුර-පදයක් ඉල්ලා සිට හෝ ඇතිවා විය හැක.',
'resetpass-temp-password'   => 'තාවකාලික මුර-පදය:',

# Edit page toolbar
'bold_sample'     => 'තදකුරු පෙළ',
'bold_tip'        => 'තදකුරු පෙළ',
'italic_sample'   => 'ඇලකුරු පෙළ',
'italic_tip'      => 'ඇලකුරු පෙළ',
'link_sample'     => 'සබැඳි ශීර්ෂය',
'link_tip'        => 'අභ්‍යන්තර සබැඳිය',
'extlink_sample'  => 'http://www.example.com සබැඳියෙහි ශීර්ෂය',
'extlink_tip'     => 'බාහිර සබැඳිය ( http:// උපසර්ගය සිහි තබාගන්න)',
'headline_sample' => 'සිරස්තල  පෙළ',
'headline_tip'    => '2වන මට්ටමෙහි සිරස්තලය',
'math_sample'     => 'සූත්‍රය මෙහි රුවන්න',
'math_tip'        => 'ගණිත සූත්‍රය (LaTeX)',
'nowiki_sample'   => 'ආකෘතිකරණය-නොකල පෙළ මෙහි රුවන්න',
'nowiki_tip'      => 'විකි ආකෘතිකරණය නොසලකාහරින්න',
'image_sample'    => 'නිදසුන.jpg',
'image_tip'       => 'නිවේශිත(embedded) ගොනුව',
'media_sample'    => 'නිදසුන.ogg',
'media_tip'       => 'ගොනු සබැඳිය',
'sig_tip'         => 'වේලා-මුද්‍රාව හා සමග ඔබගේ විද්‍යුත් අත්සන',
'hr_tip'          => 'තිරස් පේළිය (අවම වශයෙන් භාවිතා කරන්න)',

# Edit pages
'summary'                          => 'සාරාංශය:',
'subject'                          => 'විෂයය/සිරස් තලය:',
'minoredit'                        => 'මෙය සුළු සංස්කරණයකි',
'watchthis'                        => 'මෙම පිටුව මුර කරන්න',
'savearticle'                      => 'පිටුව සුරකින්න',
'preview'                          => 'පෙරදසුන',
'showpreview'                      => 'පෙරදසුන පෙන්වන්න',
'showlivepreview'                  => 'තත්කාල පෙර-දසුන',
'showdiff'                         => 'වෙනස්වීම් පෙන්වන්න',
'anoneditwarning'                  => "'''අවවාදයයි:''' ඔබ පරිශීලකයෙකු වශයෙන් පද්ධතියට ප්‍රවිෂ්ටවී නැත.
එබැව්න් මෙම පිටුවෙහි සංස්කරණ ඉතිහාසයෙහි, ඔබගේ IP ලිපිනය සටහන් කරගනු ඇත.",
'anonpreviewwarning'               => 'අවවාදයයි: ඔබ පරිශීලකයෙකු වශයෙන් පද්ධතියට ප්‍රවිෂ්ට වී නොමැත. එමනිසා මෙම පිටුවෙහි සංස්කරණ ඉතිහාසයෙහි, ඔබගේ අන්තර්ජාල ලිපිනය සටහන් කරගැනීමට සිදුවනු ඇත.',
'missingsummary'                   => "'''සිහිගැන්වීමයි:''' ඔබ විසින් සංස්කරණ සාරාංශයක් සපයා නොමැත.
ඔබ නැවතත් සුරැකීම ක්ලික් කලහොත්, ඔබගේ සංස්කරණය එවැන්නක් විරහිතවම සුරැකෙනු ඇත.",
'missingcommenttext'               => 'කරුණාකර පහතින් පරිකථනයක් ඇතුළු කරන්න.',
'missingcommentheader'             => "'''සිහිගැන්වීමයි:''' මෙම පරිකථනය සඳහා ඔබ විසින් විෂයයක්/සිරස්තලයක් සපයා නොමැත.
ඔබ නැවතත් සුරැකීම ක්ලික් කලහොත්, ඔබගේ සංස්කරණය එවැන්නක් විරහිතවම සුරැකෙනු ඇත.",
'summary-preview'                  => 'සාරාංශ පෙර-දසුන:',
'subject-preview'                  => 'විෂයය/සිරස්තලය හි පෙර-දසුන:',
'blockedtitle'                     => 'පරිශීලකයා වාරණය කර ඇත',
'blockedtext'                      => "ඔබගේ පරිශීලක නාමය හෝ IP ලිපිනය වාරණය කොට ඇත.'''

මෙම වාරණය සිදුකොට ඇත්තේ  $1 විසිනි.
මේ සඳහා දී ඇති හේතුව ''$2'' වේ.

* වාරණයෙහි ඇරඹුම: $8
*වාරණයයෙහි අවසානය: $6
* අදහස් කරන ලද  වාරණ-ලාභී: $7

වාරණය පිළිබඳ සංවාදයකට එළඹීමෙනු වස්, $1 හෝ  වෙනත් [[{{MediaWiki:Grouppage-sysop}}|පරිපාලකයෙකු]] හෝ සම්බන්ධ කරගැනීමට ඔබ හට හැකිය.
ඔබගේ  [[Special:Preferences|ගිණුම් අභිරුචි]] වල, වලංගු විද්‍යුත්-තැපැල් ලිපිනයක් නිරූපනය කොට  ඇති නම් හා ඔබ විසින් එය භාවිත කිරීම වාරණය කොට නොමැති නම් මිස,  'මෙම පරිශීලකයාට විද්‍යුත්-තැපෑලක් යවන්න' යන අංගය ඔබ විසින් භාවිතා කල නොහැකිය.
ඔබගේ වත්මන් අන්තර්ජාල ලිපිනය  $3 වන අතර, වාරණ අනන්‍යතාවය #$5 වේ.
ඔබ විසින් සිදු කරන ඕනෑම විමසුමකදී ඉහත සියළු විස්තර අඩංගු කරන්න.",
'autoblockedtext'                  => "$1 විසින් වාරණයට ලක්වූ වෙනත් පරිශීලකයෙකු විසින් භාවිත කල බැවින්  ඔබගේ අන්තර්ජාල ලිපිනය ස්වයංක්‍රීය ලෙස වාරණයට ලක්ව ඇත.
මේ සඳහා දී ඇති හේතුව පහත වේ:

:''$2''

* වාරණයෙහි ඇරඹුම: $8
* වාරණයයෙහි අවසානය: $6
* අදහස් කරන ලද  වාරණ-ලාභී: $7

වාරණය පිළිබඳ සංවාදයකට එළඹීමෙනු වස්, $1 හෝ  වෙනත් [[{{MediaWiki:Grouppage-sysop}}|පරිපාලකයෙකු]] හෝ සම්බන්ධ කරගැනීමට ඔබ හට හැකිය.

ඔබගේ  [[Special:Preferences|ගිණුම් අභිරුචි]] වල, වලංගු විද්‍යුත්-තැපැල් ලිපිනයක් නිරූපනය කොට  ඇති නම් හා ඔබ විසින් එය භාවිත කිරීම වාරණය කොට නොමැති නම් මිස,  'මෙම පරිශීලකයාට විද්‍යුත්-තැපෑලක් යවන්න' යන අංගය ඔබ විසින් භාවිතා කල නොහැකිය.

ඔබගේ වත්මන් අන්තර්ජාල ලිපිනය  $3 වන අතර, වාරණ අනන්‍යතාවය #$5 වේ.
ඔබ විසින් සිදු කරන ඕනෑම විමසුමකදී ඉහත සියළු විස්තර අඩංගු කරන්න.",
'blockednoreason'                  => 'කිසිදු හේතුවක් දක්වා නොමැත',
'blockedoriginalsource'            => " '''$1'''  හි මූලාශ්‍රය පහත දැක්වේ:",
'blockededitsource'                => " '''$1''' විෂයයයෙහි  සිදු කල  '''ඔබගේ සංස්කරණ'' යන්හී පෙළ පහත දැක්වේ:",
'whitelistedittitle'               => 'සංස්කරණය කිරීමට ප්‍රවිෂ්ටවී සිටිය යුතුය',
'whitelistedittext'                => 'සංස්කරණය කිරීමට පෙරාතුව ඔබ  $1 විය යුතුය.',
'confirmedittext'                  => 'පිටු සංස්කරණයට පෙර ඔබ‍ විසින් ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය තහවුරු කල යුතු වේ.
ඔබගේ [[Special:Preferences|පරිශීලක අභිරුචීන්]] තුලින් ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය සකසා ඉක්බිතිව තහවුරු කරන්න.',
'nosuchsectiontitle'               => 'කොටසක් සොයා ගත නොහැක',
'nosuchsectiontext'                => 'ඔබ උත්සාහ කළේ නොපවතින කොටසක් සංස්කරණය කිරීමටයි.
එම කොටස ඔබ පිටුව නරඹමින් සිටින අතරතුර මකා දමනු ලැබ හෝ දලනය කිරීමට ලක් කර හෝ තිබිය හැක.',
'loginreqtitle'                    => 'ප්‍රවිෂ්ට වී සිටීම අවශ්‍යයි',
'loginreqlink'                     => 'ප්‍රවිෂ්ටය',
'loginreqpagetext'                 => 'අනෙකුත් පිටු නරඹනු වස් ඔබ  $1 විය යුතුය.',
'accmailtitle'                     => 'මුර-පදය යවන ලදි',
'accmailtext'                      => "[[User talk:$1|$1]] සඳහා අහඹු ලෙස ජනනය කරන ලද මුරපදයක් $2 වෙත යවා ඇත.

මෙම නව ගිණුම සඳහා මුරපදය, ප්‍රවිෂ්ට වීමෙන් අනතුරුව, ''[[Special:ChangePassword|මුර පදය වෙනස් කරන්න]]''  පිටුව තුලදී වෙනස් කල හැක.",
'newarticle'                       => '(නව)',
'newarticletext'                   => "බැඳියක් ඔස්සේ පැමිණ ඔබ අවතීර්ණ වී ඇත්තේ දැනට නොපවතින ලිපියකටයි.
මෙම ලිපිය තැනීමට එනම් නිමැවීමට අවශ්‍ය නම්, පහත ඇති කොටුව තුල අකුරු ලිවීම අරඹන්න (වැඩිමනත් තොරතුරු සඳහා [[{{MediaWiki:Helppage}}|උදවු පිටුව]] බලන්න).
ඔබ මෙහි අවතීර්ණ වී ඇත්තේ කිසියම් අත්වැරැද්දකින් බව හැ‍‍ඟෙන්නේ නම්, ඔබගේ සැරිසරයෙහි (බ්‍රවුසරයෙහි) '''පසුපසට''' බොත්තම ක්ලික් කරන්න.",
'anontalkpagetext'                 => "----''මෙම සංවාද පිටුව අයත් වන්නේ තවමත් ගිණුමක් තනා නැති හෝ එසේ කොට එනමුදු එය භාවිතා නොකරන හෝ නිර්නාමික පරිශීලකයෙකුටය.
එබැවින්, ඔහු/ඇය හැඳින්වීමට සංඛ්‍යාත්මක IP ලිපිනය භාවිතා කිරීමට අප හට සිදුවේ.
පරිශීලකයන් කිහිප දෙනෙකු විසින් මෙවැනි IP ලිපිනයක් හවුලේ පරිහරණය කරනවා විය හැක.
ඔබ නිර්නාමික පරිශීලකයෙකු නම් හා ඔබ පිළිබඳ අනනුකූල පරිකථනයන් සිදුවෙන බවක් ඔබට හැ‍ඟේ නම්, අනෙකුත් නිර්නාමික පරිශීලකයන් හා සමග  මෙවැනි සංකූලතා ඇතිවීම වලක්වනු වස්,  කරුණාකර  [[Special:UserLogin/signup|ගිණුමක් තැනීමට]] හෝ [[Special:UserLogin|ප්‍රවිෂ්ට වීමට]]  කාරුණික වන්න.''",
'noarticletext'                    => 'දැනට මෙම පිටුවෙහි කිසිදු පෙළක් නොමැත.
අනෙකුත් පිටුවල  [[Special:Search/{{PAGENAME}}|මෙම පිටු ශීර්ෂය සඳහා ගවේශනය කිරීම]] හෝ,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} අදාළ ලඝු-සටහන් සඳහා ගවේෂණය කිරීම],
හෝ [{{fullurl:{{FULLPAGENAME}}|action=edit}} මෙම පිටුව සංස්කරණය කිරීම] හෝ ඔබ විසින් සිදු කල හැක</span>.',
'noarticletext-nopermission'       => 'දැනට මෙම පිටුවෙහි කිසිදු පෙළක් නොමැත.
අනෙකුත් පිටුවල [[Special:Search/{{PAGENAME}}|මෙම පිටු ශීර්ෂය සඳහා ගවේශනය කිරීම]] හෝ, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}අදාළ ලඝු-සටහන් සඳහා ගවේෂණය කිරීම]</span>, හෝ මෙම පිටුව සංස්කරණය කිරීම හෝ ඔබට කල හැක.',
'userpage-userdoesnotexist'        => '"$1" යන පරිශීලක ගිණුම ලේඛනගත කොට නොමැත.
ඔබ හට මෙම පිටුව තැනීමට/සංස්කරණය කිරීමට ඇවැසිද යන බව විමසා බලන්න.',
'userpage-userdoesnotexist-view'   => '"$1" පරිශීලක ගිණුම ලියාපදිංචි කර නොමැත.',
'blocked-notice-logextract'        => 'මෙම පරිශීලකයා දැනට අවහිර කරනු ලැබ ඇත.
ආශ්‍රය තකා නවතම අවහිර කිරීම් ලඝු-සටහන පහත සැපයේ:',
'clearyourcache'                   => "'''සටහන - සුරැකීමෙන් පසුව, වෙනස්වීම් දැකීම සඳහා, බ්‍රවුසරයෙහි පූර්වාක්ෂේපිත සංචිතය (කෑෂය)  මගහැරීමට ඔබ හට සිදුවනවා ඇත.'''
'''Mozilla / Firefox / Safari:''' ''යළිපූරණය'' ක්ලික් කරමින් ''ෂිෆ්ට්'' ඔබන්න, නැතහොත් ‘‘Ctrl-F5'' හෝ ''Ctrl-R’’ ඔබන්න (මැකිංටොෂ් සඳහා ''Command-R'');
'''Konqueror: ''' ''යළිපූරණය'' ක්ලික් කරන්න නැතහොත් ''F5'' ඔබන්න;
'''Opera:''' ''Tools → Preferences'' හි කැෂය නිෂ්කාශනය කරන්න;
'''Internet Explorer:''' ''Refresh,'' ක්ලික් කරමින් ''Ctrl'' ඔබන්න නැතහොත් ‘‘Ctrl-F5'' ඔබන්න.",
'usercssyoucanpreview'             => "'''හෝඩුවාව:'''සුරැකුමට පෙර, ඔබගේ නව  CSS පරික්ෂා කරනු වස්, \"{{int:පෙර-දසුන පෙන්වන්න}}\" බොත්තම භාවිතා කරන්න.",
'userjsyoucanpreview'              => "'''හෝඩුවාව:'''සුරැකුමට පෙර, ඔබගේ නව  ජාවා ස්ක්‍රිප්ට් පරික්ෂා කරනු වස්, \"{{int:පෙර-දසුන පෙන්වන්න}}\" බොත්තම භාවිතා කරන්න.",
'usercsspreview'                   => "'''ඔබගේ පරිශීලක CSS මත පෙර-දසුනක් පමණක් ඔබ විසින් සිදුකෙරෙන බව ධාරණය කරන්න.'''
'''එය තවමත් සුරැකීමට ලක් කොට නොමැත!'''",
'userjspreview'                    => "'''ඔබ සිදුකරන්නේ ඔබගේ පරිශීලක ජාවාස්ක්‍රිප්ට් පරික්ෂා කිරීම/පෙර-දසුන පමණක් බව ධාරණය කරන්න.'''
'''එය තවමත් සුරැකීමට ලක් කොට නොමැත!'''",
'userinvalidcssjstitle'            => "'''අවවාදයයි:''' ඡවියක් නොමැත \"\$1\".
රීති ප්‍රකාරව .css හා .js පිටු විසින් ඉංග්‍රීසි කුඩා-අකුරු ශීර්ෂ භාවිතා කෙරෙන බව සිහි තබා ගන්න, නිදසුන. {{ns:user}}:Foo/monobook.css මිස {{ns:user}}:Foo/Monobook.css නොවන බව.",
'updated'                          => '(යාවත්කාලීන)',
'note'                             => "'''සටහන:'''",
'previewnote'                      => "'''මෙය පෙරදසුනක් පමණකි;
වෙනස්වීම් සුරැකීම තවමත් සිදුකොට නොමැත!'''",
'previewconflict'                  => 'ඔබ විසින් සුරැකීම තෝරාගත්තොත්,  ඉහළ පෙළ සංස්කරණ සරියෙහි,  පෙළ දර්ශනය විය හැකි අයුර මෙම පෙර-දසුනෙන් ආවර්ජනය වේ.',
'session_fail_preview'             => "'''කණගාටුයි! සැසි දත්ත හානියක් හේතුවෙන් අප විසින් ඔබගේ  සංස්කරණය ක්‍රියායයනය කිරීමට නොහැකි වී ඇත.
කරුණාකර නැවත උත්සාහ කරන්න.
එයද ප්‍රතිඵල විරහිත නම්, [[Special:UserLogout|නිෂ්ක්‍රමණය වීම]] හා නැවත ප්‍රවිෂ්ට වීම අත්හදා බලන්න.'''",
'session_fail_preview_html'        => "'''කණගාටුයි! සැසි දත්ත හානියක් හේතුවෙන්, අප විසින් ඔබගේ සංස්කරණය ක්‍රියායනය කිරීම සිදු කල නොහැකි විය.'''

''{{SITENAME}} විසින් නොනිමි HTML සක්‍රීය කොට ඇති බැවින්, ජාවාස්ක්‍රිප්ට් ප්‍රහාරයන්ගෙන් වැලකීමේ පූර්වොපායයක් ලෙස, පෙර-දසුන සඟවා ඇත.''

'''මෙය නීත්‍යානුකූල සංස්කරණ උත්සාහයයක් නම්,  නැවත උත්සාහ කරන්න.
එසේ කල තන්හීද අසාර්ථක නම්, [[Special:UserLogout|නිෂ්ක්‍රමණය වී]] නැවත ප්‍රවිෂ්ට වීම අත්හදා බලන්න.'''",
'token_suffix_mismatch'            => "''' ඔබගේ සේවාලාභියා විසින් සංස්කරණ ටෝකනයෙහි විරාම අක්ෂර  කලවම් කිරීම නිසා ඔබගේ සංස්කරණය නිෂ්ප්‍රභා කර ඇත.
සංස්කරණය නිෂ්ප්‍රභා කරන ලද්දේ පිටු පෙළ දූෂණය වීම වැලැක්වීමටය.
දෝෂ-සපිරි වෙබ්-පාදක නිර්නාමික නියුතු සේවාවක් ඔබ විසින් භාවිත කිරීම නිසා මෙය සමහරවිට සිදුවිය හැක.'''",
'editing'                          => '$1 සංස්කරණය කරමින් පවතියි',
'editingsection'                   => '$1 (ඡේදය) සංස්කරණය කරමින් පවතියි',
'editingcomment'                   => '$1 සංස්කරණය කරමින් පවතියි (නව ඡේදයක්)',
'editconflict'                     => 'සංස්කරණ ගැටුම: $1',
'explainconflict'                  => "ඔබ මෙම පිටුව සංස්කරණය කිරීමට ඇරඹි පසුව යම් අයෙකු එය වෙනස් කොට ඇත.
ඉහළ පෙළ සරියෙහි අඩංගු වනුයේ පිටු පෙළ වත්මන පවතින අයුරිනි.
පහළ පෙළ සරියෙහි ඔබගේ වෙනස්වීම් පෙන්වා ඇත.
වත්මන පවතින පෙළට ඔබගේ වෙනස්වීම් ඒකාබද්ධ කිරීම ඔබ විසින් කල යුතුව ඇත.
ඔබ විසින්  \"පිටුව සුරකින්න\" යන්න එබූ විට සුරැකෙන්නේ ඉහළ පෙළ සරියෙහි පෙළ '''පමණි'''.",
'yourtext'                         => 'ඔබගේ පෙළ',
'storedversion'                    => 'ගබඩාකල අනුවාදය',
'nonunicodebrowser'                => "'''අවවාදයයි: ඔබගේ බ්‍රවුසරය යුනිකේත  අනුකූල නොවේ.
මෙම දුෂ්කරතාවය මගහැර පිටු සංස්කරණය  සුරක්ෂිතව සිදුකිරීමට ඔබට ඉඩ සලසන වක් මගක් ඇත: ASCII-නොවන අක්ෂර  සංස්කරණ කොටුවෙහි ෂඩ්දශක කේතයන් ලෙස පෙන්නුම් කෙරේ.'''",
'editingold'                       => "'''අවවාදයයි: ඔබ සංස්කරණය කරනුයේ මෙම පිටුවෙහි යල්-පැනගිය සංශෝධනයකි.
ඔබ එය සුරැකුවහොත්, මෙම සංශෝධනයට පසුව සිදු කෙරී ඇති වෙනස්වීම් කිසිවක් තිබේ නම් ඒවා නැතිවනු ඇත.'''",
'yourdiff'                         => 'වෙනස්කම්',
'copyrightwarning'                 => "{{SITENAME}} සඳහා ඔබ විසින් දායක වන කෘතීන් පල කොට මුදා හැරීමෙහිදී,  $2 ට යටත් වන බව කරුණාවෙන් සලකන්න (වැඩි විස්තර සඳහා $1 බලන්න). ඔබගේ ලියැවිලි, අනෙකුන් විසින් හිත්පිත් නොමැති අයුරින් සංස්කරණය කිරීම හා ඔවුන් රිසි පරිදි  නැවත බෙදාහැරීම,  ඔබ හට දරා ගැනීමට නොහැකි නම්, ඔබගේ කෘති මෙහි පල කිරීමෙන් වලකින්න.<br />
එසේ ම මෙය ඔබ විසින් ම ලියූ බවට හෝ පොදු විෂයපථයකින්, ඊ‍ට ස‍මාන නිදහස් මූලාශ්‍රයකින් උපුටා ගත් බව‍ට හෝ අපහ‍‍ට සහතික විය යුතු ය. (තොරතුරු සඳහා $1 බලන්න).
'''හිමිකම් ඇවුරුණු දේ අනවසරයෙන් ප්‍රකාශ කිරිමෙන් වලකින්න !'''",
'copyrightwarning2'                => "{{SITENAME}} වෙත දායක වෙමින් ඔබ විසින් යොමු කෙරෙන කෘති, එවැනිම දායකත්වයක් සපයන වෙනයම් ඕනෑම අයෙකුන් විසින්, සංස්කරණය කිරීම, වෙනස් කිරීම, හෝ ඉවත් කිරීම සිදුවිය හැක්කක් බව කරුණාවෙන් සලකන්න.ඔබගේ ලියැවිලි, හිත්පිත් නැතිවා සේ පෙනෙන ඉතා රළු අයුරින් සංස්කරණයට ලක් කිරීම නොකල යුතු යැයි ඔබ හඟින්නේ නම්, ඔබගේ කෘති මෙහි පල කිරීමෙන් වලකින්න.<br />
එසේ ම මෙය ඔබ විසින් ම ලියූ බවට හෝ පොදු විෂයපථයකින්, ඊ‍ට ස‍මාන නිදහස් මූලාශ්‍රයකින් උපුටා ගත් බව‍ට හෝ අපහ‍‍ට සහතික විය යුතු ය. (තොරතුරු සඳහා $1 බලන්න).
''' හිමිකම් ඇවුරුණු දේ අනවසරයෙන් ප්‍රකාශ කිරිමෙන් වලකින්න!'''",
'longpagewarning'                  => "'''අවවාදයයි: මෙම පිටුව කිලෝ බයිට්  $1 ගණනක් දිගුය;
 32කි.බ. පමණට කිට්ටු හෝ ඊට වඩා දිගු පිටු සංස්කරණය කිරීම සමහරක් බ්‍රවුසර වලට දුෂ්කර විය හැක.
මෙම  ‍පිටුව කුඩා කොටස් වලට බෙදීම පිළිබඳව කරුණාකර අවධානය යොමු කරන්න.'''",
'longpageerror'                    => "'''දෝෂය: ඔබ විසින් ඉදිරිපත් කර ඇති පෙළ, කිලෝබයිට් $1 ක් දිගු වන අතර, උපරිමය වන කිලෝබයිට් $2 ට වඩා දිගය.
එය සුරැකිය නොහැක.'''",
'readonlywarning'                  => "'''අවවාදයයි: දත්ත-ගබඩාව නඩත්තු කටයුතු සඳහා අවුරා ඇති අතර, එබැවින් ඔබගේ සංස්කරණයන් මේ දැන්මම සුරැකීමට ඔබ හට නොහැකි වනු ඇත.
ඔබ තුටු නම්, කපා-පසුව-ඇලවීමක් මගින් පෙළ වෙනත් පෙළ ගොනුවකට නංවා ඉනික්බිතිව පසුව සුරැකීම සිදුකිරීමට කරුණු සැලසිය හැක.'''

එය ඇවුරූ පරිපාලක විසින් ඒ සඳහා දී ඇති පැහැදිලි කිරීම මෙසේය: $1",
'protectedpagewarning'             => "\"'අවවාදයයි: පරිපාලක වරප්‍රසාද හිමි අය විසින් පමණක් සංස්කරණය කල හැකි වන පරිදි මෙම පිටුව අවුරා ඇත.'''
ආසන්නතම ලඝු සටහන යොමුවන් සඳහා පහතින් සපයනු ලබයි.",
'semiprotectedpagewarning'         => "'''සටහන:''' ලේඛනගත පරිශීලකයන්ට පමණක් සංස්කරණය කල හැකි පරිදි මෙම පිටුව අවුරා ඇත.
ආසන්නතම ලඝු සටහන යොමුවන් සඳහා පහතින් සපයනු ලැබේ.",
'cascadeprotectedwarning'          => "'''අවවාදයයි:''' මෙහි පහත දැක්වෙන තීරු දර්ශන-ආරක්‍ෂිත {{PLURAL:$1|පිටුවක|පිටු වල}} එය අඩංගු කොට ඇති  බැවින්,  පරිපාලක වරප්‍රසාද සතු පරිශීලකයන් හට පමණක් මෙම පිටුව සංස්කරණය කල හැකි වන පරිදි එය අවුරා ඇත:",
'titleprotectedwarning'            => "'''අවවාදයයි: එය තැනීම සඳහා [[Special:ListGroupRights|විශේෂිත හිමිකම්]]  අවශ්‍ය වන පරිදී මෙම පිටුව අවුරා ඇත.'''
ආසන්නතම ලඝු සටහන යොමුවන් සඳහා පහතින් සපයනු ලැබේ.",
'templatesused'                    => 'මෙම පිටුවෙහි භාවිත {{PLURAL:$1|සැකිල්ල|සැකිලි}}:',
'templatesusedpreview'             => 'මෙම පෙර-දසුනෙහි භාවිත {{PLURAL:$1|සැකිල්ල|සැකිලි}}',
'templatesusedsection'             => ' මෙම කොටසෙහි භාවිතා කර ඇති  {{PLURAL:$1|සැකිල්ල|සැකිලි }}:',
'template-protected'               => '(රක්ෂිත)',
'template-semiprotected'           => '(අර්ධ-රක්ෂිත)',
'hiddencategories'                 => 'මෙම පිටුව, {{PLURAL:$1| එක් සැඟවුණු ප්‍රවර්ගයක| සැඟවුණු ප්‍රවර්ගයන් $1 ක}} අවයවයක් වේ:',
'edittools'                        => '<!-- මෙම පෙළ සංස්කරණ හා උඩුගත ආකෘතින්ට පහළින් පෙන්නුම් කෙරේ. -->',
'nocreatetitle'                    => 'පිටු තැනීම සීමා කර ඇත',
'nocreatetext'                     => 'නව පිටු තැනීමේ හැකියාව {{SITENAME}} විසින් සීමාකර ඇත.
ඔබ හට පෙරළා ගොස්,  දැනට පවතින පිටුවක් සංස්කරණය කිරීම හෝ,  [[Special:UserLogin|ගිණුමකට ප්‍රවිෂ්ට වීම හෝ  නව ගිණුමක් තැනීම හෝ]] සිදුකල හැක.',
'nocreate-loggedin'                => '{{SITENAME}} හි නව පිටු තැනීමට අවසරයක් ඔබ හට ප්‍රදානය කොට නොමැත.',
'sectioneditnotsupported-title'    => 'කොටසක් සංස්කරණය කිරීම සඳහා සහාය නොදක්වයි',
'sectioneditnotsupported-text'     => 'මෙම පිටුවේදී කොටසක් සංස්කරණය කිරීම සඳහා සහාය නොදක්වයි',
'permissionserrors'                => 'අවසරයන් පිළිබඳ දෝෂයන් පවතී',
'permissionserrorstext'            => 'පහත දැක්වෙන {{PLURAL:$1|හේතුව|හේතූන්}} නිසා, ඔබ හට එය සිදුකිරීමට අවසර ලබා දීමට නොහැක:',
'permissionserrorstext-withaction' => 'පහත {{PLURAL:$1|හේතුව|හේතු}} නිසා, ඔබ හට $2 සඳහා අවසර නොමැත:',
'recreate-moveddeleted-warn'       => "'''අවවාදයයි: පෙරදී මකාදැමුණු පිටුවක් ඔබ විසින් යළි-තනමින් පවතියි.'''

මෙම පිටුව සංස්කරණය තවදුරටත් සිදු කරලීම සුදුසු දැයි එබ විසින් සලකා බැලිය යුතුව ඇත.
මෙම පිටුව සඳහා මකාදැමීම් හා ගෙන යෑම් ලඝු-සටහන් ඔබගේ පහසුව තකා මෙහි දක්වා ඇත:",
'moveddeleted-notice'              => 'මෙම පිටුව මකාදමා ඇත.
පිටුව සඳහා මකාදැමීම් හා ගෙන යෑම් ලඝු-සටහන් ඔබගේ පහසුව තකා මෙහි පහත දක්වා ඇත.',
'log-fulllog'                      => 'මුළු ලඝු-සටහන නරඹන්න',
'edit-hook-aborted'                => 'හසුර මගින් සංස්කරණය රෝධනය කෙරිණි.
එය කිසිදු පැහැදිලි කිරීමක් නොදුනි.',
'edit-gone-missing'                => 'පිටුව යාවත්කාල කිරීම සිදුකල නොහැකි විය.
එය මකා දමා ඇති බවක් පෙනේ.',
'edit-conflict'                    => 'සංස්කරණ ගැටුම.',
'edit-no-change'                   => 'පෙළට කිසිදු වෙනසක් සිදු නොකල  බැවින් ඔබගේ සංස්කරණය නොසලකාහරින ලදි.',
'edit-already-exists'              => 'නව පිටුවක් තැනිය නොහැකි විය.
එය දැනටමත් පවතියි.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'අවවාදයයි: මෙම පිටුවෙහි අධිවැය ව්‍යාකරණ විග්‍රහ ශ්‍රිත කැඳවුම් පමණට වඩා ඇත.

එහි තිබිය යුතු වූයේ  {{PLURAL:$2|එක් කැඳවුමකට |කැඳවුම් $2 ට }} අඩුවෙන් වුවද, මෙහි දැනට  {{PLURAL:$1|එක් කැඳවුමක්|කැඳවුම්  $1 ක්}} අඩංගුව ඇත.',
'expensive-parserfunction-category'       => 'අධිවැය ව්‍යාකරණ විග්‍රහ ශ්‍රිත කැඳවුම් පමණට වඩා ඇති පිටු',
'post-expand-template-inclusion-warning'  => 'අවවාදයයි: සැකිලි අඩංගු කිරීමේ ප්‍රමාණය අවසර ලබා දී ඇති පමණට වඩා විශාලයි.
සමහරක් සැකිලි අඩංගු නොකරනු ඇත.',
'post-expand-template-inclusion-category' => 'මෙම පිටු තුල, සැකිලි අඩංගු කිරීමේ ප්‍රමාණය, අවසර දී ඇති සීමා ඉක්මවා ගොස් ඇත',
'post-expand-template-argument-warning'   => 'අවවාදයයි: ව්‍යාප්ති ප්‍රමාණය ඇවැසි තරමට වඩා විශාල ලෙස දක්වා ඇති සැකිලි විචල්‍යයන් අඩුම වශයෙන් එකක් හෝ  මෙම පිටුව තුල අන්තර්ගතය.
එම විචල්‍යයන් නොසලකා හැර ඇත.',
'post-expand-template-argument-category'  => 'මෙම පිටුවල, සැකිලි විචල්‍යයන් හරියාකාර දැක්වීම පැහැර හැරීම පිළිබඳ ගැටළු පවතී',
'parser-template-loop-warning'            => 'සැකිලි ලූපය අනාවරණය කෙරිණි: [[$1]]',
'parser-template-recursion-depth-warning' => 'සැකිලි ආවර්තනික අධිකත්ව සීමාව ඉක්මවිණි ($1)',
'language-converter-depth-warning'        => 'භාෂා පරිවර්තක අධිකත්ව සීමාව ඉක්මවා ඇත ($1)',

# "Undo" feature
'undo-success' => 'සංස්කරණය අහෝසි කල හැක.
පහත දක්වා ඇති සැසැඳුම පරික්ෂා කර බලා ඔබගේ අභිලාෂය මෙයමැයි සත්‍යාපනය කොට ගෙන, සංස්කරණය අහෝසි කිරීම නිමවනු වස් පහත දැක්වෙන වෙනස්වීම් සුරකින්න.',
'undo-failure' => 'පරස්පර අතරමැඳි සංස්කරණයන් පැවතීම හේතුවෙන් මෙම සංස්කරණය අහෝසි කල නොහැක.',
'undo-norev'   => 'එය නොපැවතීම නිසාවෙන් හෝ එය මකා දමා ඇති නිසාවෙන් මෙම සංස්කරණය අහෝසි කිරීම කල නොහැකි විය.',
'undo-summary' => ' [[Special:Contributions/$2|$2]] මගින් සිදුකල  $1 සංශෝධනය අහෝසි කරන්න ([[User talk:$2|සාකච්ඡා]])',

# Account creation failure
'cantcreateaccounttitle' => 'ගිණුම තැනිය නොහැක',
'cantcreateaccount-text' => "මෙම අන්තර්ජාල ලිපිනය ('''$1''') මගින් ගිණුම් තැනීම [[User:$3|$3]] විසින් වාරණය කොට ඇත.

$3 විසින් සපයා ඇති හේතුව ''$2'' වේ",

# History pages
'viewpagelogs'           => 'මෙම පිටුව පිලිබඳ සටහන් නරඹන්න',
'nohistory'              => 'මෙම පිටුව සඳහා සංස්කරණ ඉතිහාසයක් නොමැත.',
'currentrev'             => 'වත්මන් සංශෝධනය',
'currentrev-asof'        => '$1 වන විට වත්මන් සංශෝධනය',
'revisionasof'           => '$1 තෙක් සංශෝධනය',
'revision-info'          => '$1 වන විට  $2 විසින් සිදු කර ඇති සංශෝධන',
'previousrevision'       => '← පැරණි සංශෝධනය',
'nextrevision'           => 'නව සංශෝධනය →',
'currentrevisionlink'    => 'වත්මන් සංශෝධනය',
'cur'                    => 'වත්මන්',
'next'                   => 'මීලඟ',
'last'                   => 'පෙර',
'page_first'             => 'පළමු',
'page_last'              => 'අවසන්',
'histlegend'             => 'වෙනස තේරීම: සැසඳිය යුතු අනුවාදයන්හි  රේඩියෝ බොක්ස් සලකුණු කොට ඉන්පසු එන්ටර් බොත්තම එබීම හෝ පහළින්ම ඇති බොත්තම එබීම කරන්න.<br />
ආඛ්‍යායිකාව: (වත්මන්) = වත්මන් අනුවාදය හා සමග වෙනස,
(අවසන්) = පෙර අනුවාදය හා සමග වෙනස, සුළු = සුළු සංස්කරණය.',
'history-fieldset-title' => 'ඉතිහාසය පිරික්සන්න',
'history-show-deleted'   => 'මකනු ලැබූ ඒවා පමණයි',
'histfirst'              => 'පැරණිතම',
'histlast'               => 'නවීනතම',
'historysize'            => '({{PLURAL:$1|බයිට්1 |බයිට් $1 ගණනක්}})',
'historyempty'           => '(හිස්)',

# Revision feed
'history-feed-title'          => 'සංශෝධන ඉතිහාසය',
'history-feed-description'    => 'විකියෙහි මෙම පිටුව සඳහා ඇති සංශෝධන ඉතිහාසය',
'history-feed-item-nocomment' => '$1 විසින්  $2 හිදී',
'history-feed-empty'          => 'අයැදුනු පිටුව නොපවතියි.
එය විකියෙන් මකා දමා හෝ නම-වෙනස් කොට ඇතිවා විය හැකිය.
අදාල නව පිටු සඳහා  [[Special:Search|විකිය තුල ගවේෂණය]] අත්හදා බලන්න.',

# Revision deletion
'rev-deleted-comment'         => '(පරිකථනය ඉවත් කරන ලදි)',
'rev-deleted-user'            => '(පරිශීලක-නාමය ඉවත් කරන ලදි)',
'rev-deleted-event'           => '(ලඝු-සටහන් තැබීමේ  ක්‍රියාව අත්හිටුවන ලදි)',
'rev-deleted-user-contribs'   => '[පරිශීපක නාමය හෝ ලිපිනය ඉවත් කළා - දායකත්ව මඟින් සඟවන ලද සංස්කරණය]',
'rev-deleted-text-permission' => "මෙම පිටු සංශෝධනය '''මකා දමා ඇත'''.
වැඩි විස්තර බොහෝ විට [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} මකාදැමීම් ලඝු-සටහන] වෙත තිබීමට ඉඩ ඇත.",
'rev-deleted-text-unhide'     => "මෙම පිටු සංශෝධනය '''මකාදමා ඇත'''.
විස්තර බොහෝ විට [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} මකාදැමීම් ලඝු-සටහන] හි තිබීමට ඉඩ ඇත.
පරිපාලකවරයෙකු වශයෙන්, මේ පිළිබඳ කටයුතු සිදු කිරීමට ඇවැසි නම්,  ඔබට [$1 මෙම සංශෝධනය නැරඹිම] තවමත් සිදුකල හැක.",
'rev-suppressed-text-unhide'  => 'මෙම පිටුව සංශෝධනය කිරීම "මකා දමා ඇත".විස්තර [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}යටපත් කිරීම් ලඝු-සටහනේ]තිබීමට ඉඩ ඇත.ඔබ ඉදිරියට යෑමට කැමතිනම් පරිපාලකයෙකු වශයෙන් තවමත් ඔබට [$1 මෙම සංශෝධනය නැරඹීමට] හැකිය.',
'rev-deleted-text-view'       => "මෙම පිටු සංශෝධනය '''මකා දමා ඇත'''.
පරිපාලකයෙකු වශයෙන් එය ඔබහට නැරඹිය හැක; වැඩි විස්තර බොහෝ විට [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} මකාදැමීම් ලඝු-සටහන] වෙත තිබීමට ඉඩ ඇත.",
'rev-suppressed-text-view'    => 'මෙම පිටුව සංශෝධනය "\'වළක්වා ඇත"\'.ඔබට පරිපාලකයෙකු වශයෙන් තවමත් එය නැරඹීමට හැක;විස්තර [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}වැළැක්වීම් ලඝු-සටහනේ].ඇත.',
'rev-deleted-no-diff'         => "මෙම වෙනස ඔබ හට නැරඹිය නොහැකි වන්නේ එක් සංශෝධනයක් '''මකා දමා ඇති''' බැවිනි.
විස්තර බොහෝවිට [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} මකාදැමීම් ලඝු-සටහන] හි තිබීමට ඉඩ ඇත.",
'rev-suppressed-no-diff'      => 'සංශෝධනයන්වලින් එකක් "\'මකා දමනු ලැබ"\' ඇති බැවින් ඔබට මෙම diff නැරඹිය නොහැක.',
'rev-deleted-unhide-diff'     => "මෙම වෙනස හි එක් සංශෝධනයක් '''මකාදමා ඇත'''.
විස්තර බොහෝවිට [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} යටපත්කිරීම් ලඝු-සටහන]හි තිබීමට ඉඩ ඇත.
පරිපාලකවරයෙකු වශයෙන්, මේ පිළිබඳ කටයුතු සිදු කිරීමට ඇවැසි නම්,  ඔබට [$1 මෙම වෙනස නැරඹීම] තවමත් සිදුකල හැක.",
'rev-suppressed-unhide-diff'  => 'මෙම වෙනස හි එක් සංශෝධනයක් "\'මකාදමා ඇත"\'. විස්තර බොහෝවිට [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}}යටපත්කිරීම් ලඝු-සටහනහි]තිබීමට ඉඩ ඇත. පරිපාලකවරයෙකු වශයෙන්, මේ පිළිබඳ කටයුතු සිදු කිරීමට ඇවැසි නම්, ඔබට [$1 මෙම වෙනස නැරඹීම] තවමත් සිදුකල හැක.',
'rev-deleted-diff-view'       => 'මෙම වෙනසෙහි එක් සංශෝධනයක් "\'මකා දමා ඇත"\'.පරිපාලකයෙකු වශයෙන් ඔබට මෙම වෙනස නැරඹිය හැකිය;විස්තර [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} මකාදැමීම් ලඝු-සටහනේ] තිබීමට ඉඩ ඇත.',
'rev-suppressed-diff-view'    => "මෙම වෙනසෙහි එක්  සංශෝධනයක්  '''මකා දමා ඇත'''.
පරිපාලකයෙකු වශයෙන් ඔබට එම වෙනස  නැරඹිය හැක; වැඩි විස්තර බොහෝ විට [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} යටපත්කිරීම් ලඝු-සටහන] වෙත තිබීමට ඉඩ ඇත.",
'rev-delundel'                => 'පෙන්වන්න/සඟවන්න',
'rev-showdeleted'             => 'පෙන්වන්න',
'revisiondelete'              => 'සංශෝධන මකා දමන්න/මකා දැමීම ප්‍රතිලෝම කරන්න',
'revdelete-nooldid-title'     => 'ඉලක්කගත සංශෝධනය අනීතිකයි',
'revdelete-nooldid-text'      => 'මෙම කාර්යය ඉටු කිරීම සඳහා එක්කෝ ඔබ විසින් ඉලක්කගත සංශෝධනය(න්) නොදක්වයි,
සඳහන් කල සංශෝධනය නොපවතියි, නැතහොත්, වත්මන් සංශෝධනය සැඟවීමට ඔබ යත්න දරයි.',
'revdelete-nologtype-title'   => 'ලඝු-සටහන් වර්ගයක් දක්වා නොමැත',
'revdelete-nologtype-text'    => 'මෙම ක්‍රියාව සිදු කල හැකි වන පරිදී  ලඝු-සටහන් වර්ගයක් දැක්විය යුතු වුවද ඔබ එසේ කර නොමැත.',
'revdelete-nologid-title'     => 'අනීතික ලඝු-සටහන් නිවේශිතය',
'revdelete-nologid-text'      => 'මෙම කෘත්‍යය සිදු කල හැකි වන පරිදී ඔබ විසින් එක්කෝ ඉලක්කගත ලඝු-සටහන් තැබිය යුතු සිදුවීමක් දක්වා නොමැත නැතිනම් දක්වා ඇති නිවේශිතය නොපවතියි.',
'revdelete-no-file'           => 'නිවේශිත ගොනුව නොපවතියි.',
'revdelete-show-file-confirm' => '$2 දින $3 වේලාවේදී මකාදැමුනු "<nowiki>$1</nowiki>" ගොනුවෙහි සංශෝධනයක් නැරඹීමට ඔබ හට සහතික වශයෙන් ඇවැසිද?',
'revdelete-show-file-submit'  => 'ඔව්',
'revdelete-selected'          => "'''[[:$1]] හි {{PLURAL:$2|තෝරාගත් සංශෝධනය|තෝරාගත් සංශෝධනයන්}} :'''",
'logdelete-selected'          => "'''{{PLURAL:$1|තෝරාගත් ලඝු-සිදුවීම|තෝරාගත් ලඝු-සිදුවීම්}}:'''",
'revdelete-text'              => "'''මකාදැමුණු සංශෝධනයන් හා සිද්ධීන් තවදුරටත් පිටු විත්ති හා ලඝු-සටහන් හි දර්ශනය වුවද, ප්‍රජාව ට ප්‍රවිෂ්ඨ විය හැක්කේ ඒවායේ අන්තර්ගතයෙන් කොටසකටය.'''
අමතර සීමා පණවා නොමැති නම්, සැඟවුනු අන්තර්ගතයට එළඹී, යම් අතුරුමුහුණතක් ඔස්සේ,   එය මකාදැමුම යළි අවලංගු කිරීමට, {{SITENAME}} හි අනෙකුත් පරිපාලකයන්හට තවමත් අවතාශ ඇත්තේය.",
'revdelete-confirm'           => 'කරුණාකර ඔබ මෙය කිරීමට අදහස් කරන බවත්,එහි ප්‍රතිඵලය අවබෝධ කර ගන්නා බවත්,මෙය සිදු කරනුයේ [[{{MediaWiki:Policy-url}}| ප්‍රතිපත්තියට]] අනුකූලව බවත් තහවුරු කරන්න.',
'revdelete-suppress-text'     => "යටපත්කිරීම පහත අවස්ථාවන්හිදී  '''පමණක්''' භාවිතා කල යුතුය:
* නුසුදුසු පෞද්ගලික තොරතුරු 
*: ''නිවසෙහි ලිපින හා දුරකතන අංක ආදිය.''",
'revdelete-legend'            => 'දෘශ්‍ය අවහිරකිරීම් සකසන්න',
'revdelete-hide-text'         => 'සංශෝධන පෙළ සඟවන්න',
'revdelete-hide-image'        => 'ගොනු අන්තර්ගතය සඟවන්න',
'revdelete-hide-name'         => 'ක්‍රියාව හා ඉලක්කය සඟවන්න',
'revdelete-hide-comment'      => 'සංස්කරණ පරිකථනය සඟවන්න',
'revdelete-hide-user'         => 'සංස්කාරකගේ පරිශීලක නාමය/IP ලිපිනය සඟවන්න',
'revdelete-hide-restricted'   => 'අනෙකුන් මෙන්ම පරිපාලකවරුන් ගෙන්ද මෙම දත්ත යටපත්කරන්න',
'revdelete-radio-same'        => '(වෙනස් නොකරන්න)',
'revdelete-radio-set'         => 'ඔව්',
'revdelete-radio-unset'       => 'නැත',
'revdelete-suppress'          => 'අනෙකුන්ගෙන් මෙන්ම පරිපාලකයන්ගෙන්ද දත්ත යටපත් කරන්න',
'revdelete-unsuppress'        => 'ප්‍රතිෂ්ඨාපනය කරන ලද සංශෝධනයන් විෂයයෙහි පැනවුනු පරිසීමා ඉවත්කරන්න',
'revdelete-log'               => 'හේතුව:',
'revdelete-submit'            => 'තෝරාගත් {{PLURAL:$1|සංශෝධනය|සංශෝධනයන්}}ට යොදන්න',
'revdelete-logentry'          => ' [[$1]] හි සංශෝධන සංජානනය වෙනස්කරන ලදි',
'logdelete-logentry'          => '[[$1]] හි සිදුවීම් සංජානනය වෙනස්කරන ලදි',
'revdelete-success'           => "'''සංශෝධන සංජානනය සාර්ථකව යාවත්කාලීන කරන ලදි.'''",
'revdelete-failure'           => "'''සංශෝධන දෘශ්‍යතාවය යාවත්කාලීන කළ නොහැකි විය:'''
$1",
'logdelete-success'           => "'''ලඝු-සටහන් සංජානනය  සාර්ථකව පරිස්ථාපනය කෙරිණි.'''",
'logdelete-failure'           => "'''ලඝු-සටහන් දෘශ්‍යතාවය නියම කිරීම කල නොහැකි විය:'''
$1",
'revdel-restore'              => 'සංජානනය වෙනස් කරන්න',
'revdel-restore-deleted'      => 'සංශෝධන මකා දමන ලදී',
'revdel-restore-visible'      => 'දෘශ්‍යමාන සංශෝධන',
'pagehist'                    => 'පිටු ඉතිහාසය',
'deletedhist'                 => 'මකාදැමූ ඉතිහාසය',
'revdelete-content'           => 'අන්තර්ගතය',
'revdelete-summary'           => 'සංස්කරණ සාරාංශය',
'revdelete-uname'             => 'පරිශීලක-නාමය',
'revdelete-restricted'        => 'පරිපාලකයන් විෂයයෙහි ව්‍යවහාරිත පරිසීමාවන්',
'revdelete-unrestricted'      => 'පරිපාලකයන්ගේ පරිසීමාවන් ඉවත් කරන ලදි',
'revdelete-hid'               => '$1 සඟවන ලදි',
'revdelete-unhid'             => '$1 අනාවරණය කරන ලදි',
'revdelete-log-message'       => '{{PLURAL:$2|එක් සංශෝධනයක්|සංශෝධන $2  ක්}} විෂයයෙහි $1',
'logdelete-log-message'       => '{{PLURAL:$2|එක් සිදුවීමක්|සිදුවීම් $2 ක්}} විෂයයෙහි $1',
'revdelete-hide-current'      => '$1දින, $2 වේලාවෙහි, අයිතමය සැඟවීමෙහිදී දෝෂයක් ඇති වී ඇත: මෙය මෑත සංශෝධනය වෙයි.
එය සැඟවිය නොහැක.',
'revdelete-show-no-access'    => '$1 දින, $2 වේලාවෙහි, අයිතමය ප්‍රදර්ශනය කිරීමෙහිදී දෝෂයක් ඇතිවී ඇත: මෙම අයිතමය "පරිසීමිත" ලෙසින් සලකුණු කර ඇත.
ඔබට ඒ සඳහා ප්‍රවේශයන් නොමැත.',
'revdelete-modify-no-access'  => '$1 දින, $2 වේලාවෙහි, අයිතමය වෙනස්කිරීමෙහිදී දෝෂයක් ඇතිවී ඇත: මෙම අයිතමය "පරිසීමිත" ලෙසින් සලකුණු කර ඇත.
ඔබට ඒ සඳහා ප්‍රවේශයන් නොමැත.',
'revdelete-modify-missing'    => 'අනන්‍යාංක $1 දරණ අයිතමය වෙනස් කිරීමෙහිදී දෝෂයක් ඇතිවී ඇත: එය දත්ත ගබඩාවෙන් අස්ථානගතවී ඇත!',
'revdelete-no-change'         => "'''අවවාදයයි:''' $1 දින, $2 වේලාවෙහි, අයිතමය දැනටමත් දෘශ්‍යතා පරිස්ථිතීන් ඉල්ලා සිට ඇත.",
'revdelete-concurrent-change' => '$1 දින, $2 වේලාවෙහි, අයිතමය වෙනස් කිරීමෙහිදී දෝෂයක් ඇතිවී ඇත: එය වෙනස්කිරීමට ඔබ උත්සාහ ගන්නා අතරතුරදී තවත් අයෙකු විසින් එහි ස්තිතිය වෙනස් කර ඇති බවක් පෙනෙන්නට ඇත.
කරුණාකර ලඝු-සටහන් පරික්ෂාකර බලන්න.',
'revdelete-only-restricted'   => '$2 දිනැති අයිතමය සැඟවීමේ දෝෂය , $1:අනෙකුත් සැඟවීම් විකල්පයන් අතුරින් එකක් තෝරාගන්නේ නැතිව, පරිපාලකයන්ගේ දර්ශනයෙන් අයිතමයන් සැඟවීම  ඔබහට සිදුකල නොහැක.',
'revdelete-reason-dropdown'   => '*මකා දැමීමේ පොදු හේතු
**කතු හිමිකම් උල්ලංඝනය
**නුසුදුසු පුද්ගලික කොරතුරු
**අපහාසාත්මක විය හැකි තොරතුරු',
'revdelete-otherreason'       => 'වෙනත්/අමතර හේතු:',
'revdelete-reasonotherlist'   => 'වෙනත් හේතු',
'revdelete-edit-reasonlist'   => 'මකා දැමීමට හේතූන් සංස්කරණය කරන්න',
'revdelete-offender'          => 'සංශෝධන කතෘ:',

# Suppression log
'suppressionlog'     => 'යටපත්කිරීම් පිළිබඳ ලඝු-සටහන',
'suppressionlogtext' => 'පරිපාලකයන්ගෙන් සැඟවුනු අන්තර්ගතය සම්බන්ධ මකාදැමීම් හා වාරණ ලැයිස්තුවක් මෙහි පහත දැක්වේ.
දැනට ක්‍රියාත්මක වන තහනම් හා වාරණයන් ලැයිස්තුවක් සඳහා [[Special:IPBlockList|අන්තර්ජාල වාරණ ලැයිස්තුව]] බලන්න.',

# Revision move
'moverevlogentry'              => '{{PLURAL:$3|එක් සංශෝධනයක්|සංශෝධන $3 ක්}} $1 සිට $2 දක්වා ගෙන යන ලදි',
'revisionmove'                 => '"$1" සිට සංශෝධන ගෙන යන්න',
'revmove-explain'              => 'පහත සංශෝධන $1 සිට නිශ්චය ලෙස දැක්වුනු ඉලක්ක පිටු වෙත ගෙන යනු ලැබේ. ඉලක්කය නොපවතියි නම්, එය තනනු ලැබේ. නැතහොත්, මෙම සංශෝධනයන්,  පිටු ඉතිහාසය හා සමගින් එඒකාබද්ධ කරනු ලැබේ.',
'revmove-legend'               => 'ඉලක්ක පිටුව සහ සාරාංශය පිහිටුවන්න',
'revmove-submit'               => 'තෝරාගත් පිටුවට සංශෝධන ගෙන යන්න',
'revisionmoveselectedversions' => 'තෝරාගත් සංශෝධන ගෙන යන්න',
'revmove-reasonfield'          => 'හේතුව:',
'revmove-titlefield'           => 'ඉලක්ක පිටුව:',
'revmove-badparam-title'       => 'නුසුදුසු පරමිතීන්',
'revmove-badparam'             => 'ඔබගේ ඉල්ලුම අනීතික හෝ අප්‍රමාණ පරාමිතිකයන් අඩංගුව ඇත. කරුණාකර  "back" ඔබා නැවත උත්සාහ කර බලන්න.',
'revmove-norevisions-title'    => 'අනීතික ඉලක්ක සංශෝධනය',
'revmove-norevisions'          => 'මෙම කාර්යය සිදුකිරීමට ඔබ එක්කෝ ඉලක්ක සංශෝධන එකක් හෝ වැඩි ගණනක් හෝ දක්වා නැත නැත්නම් සඳහන් කරන පද සංශෝධනය නොපවතියි.',
'revmove-nullmove-title'       => 'නුසුදුසු ශීර්ෂයක්',
'revmove-nullmove'             => 'ප්‍රභව හා ඉලක්ක පිටු එක හා සමානයි. කරුණාකර "back" බොත්තම ඔබා "$1" ට වෙනස් පිටු නාමයක් ඇතුලත් කරන්න.',
'revmove-success-existing'     => '{{PLURAL:$1| [[$2]] වෙතින් එක් සංශෝධනයක්|[[$2]] වෙතින් සංශෝධන $1 ක්}} දැනට පවතින [[$3]] පිටුව වෙත ගෙන යන ලදි.',
'revmove-success-created'      => '{{PLURAL:$1| [[$2]] වෙතින් එක් සංශෝධනයක්| [[$2]] ‍වෙතින් සංශෝධන $1 ක්}} අළුතින් නිමැවුනු [[$3]] පිටුව වෙත ගෙන යන ලදි.',

# History merging
'mergehistory'                     => 'පිටු ඉතිහාසයන් ඒකාබද්ධ කරන්න',
'mergehistory-header'              => 'එක් මූල පිටුවක ඉතිහාසයේ සංශෝධන වෙනත් නවමු පිටුවක ඉතිහාසයේ සංශෝධන හා ඒකාබද්ධ කිරීමට මෙම පිටුව ඔබට ඉඩ සලසයි.
මෙම වෙනස සිදු කලද ‍පිටුවේ ඓතිහාසික අඛණ්ඩතාවය පවත්වා ගෙන යන බවට සහතික කරන්න.',
'mergehistory-box'                 => 'පිටු දෙකෙහි සංශෝධන ඒකාබද්ධ කරන්න:',
'mergehistory-from'                => 'මූල පිටුව:',
'mergehistory-into'                => 'අන්ත පිටුව:',
'mergehistory-list'                => 'ඒකාබද්ධ කලහැකි සංස්කරණ ඉතිහාසය',
'mergehistory-merge'               => '[[:$1]] හි පහත දැක්වෙන සංශෝධන  [[:$2]] හා සමග ඒකාබද්ධ කල හැක.
යම් වේලාවකදී හා ඊට පෙර සිදු කල සංශෝධන පමණක් ඒකාබද්ධ කිරීමට රේඩියෝ බොත්තම් තීරුව භාවිතා කරන්න.
සංචාලන සබැඳියන් භාවිතය හේතුවෙන් මෙම තීරුව ප්‍රත්‍යාරම්භයකට ලක් කරවන බව සටහන් කරන්න.',
'mergehistory-go'                  => 'ඒකාබද්ධ කල හැකි සංස්කරණ පෙන්වන්න',
'mergehistory-submit'              => 'සංශෝධන ඒකාබද්ධ කරන්න',
'mergehistory-empty'               => 'සංශෝධනයන් කිසිවක් ඒකාබද්ධ කල නොහැක.',
'mergehistory-success'             => ' [[:$1]] හි  {{PLURAL:$3|සංශෝධනයක්|සංශෝධන  $3 ක්}}සාර්ථක ලෙස   [[:$2]] හා සමග ඒකාබද්ධ කරන ලදි.',
'mergehistory-fail'                => 'ඉතිහාස ඒකාබද්ධය සිදු කල නොහැක, පිටු හා වේලා පරාමිතීන් නැවත පිරික්සා බලන්න.',
'mergehistory-no-source'           => 'මූල පිටුව $1 කොපවතී.',
'mergehistory-no-destination'      => 'අන්ත පිටුව $1 නොපවතී.',
'mergehistory-invalid-source'      => 'මූල පිටුව නීතික ශීර්ෂයක් සහිත විය යුතුය.',
'mergehistory-invalid-destination' => 'අන්ත  පිටුව නීතික ශීර්ෂයක් සහිත විය යුතුය.',
'mergehistory-autocomment'         => '[[:$2]] හා සමග [[:$1]] ඒකාබද්ධ කරන ලදි',
'mergehistory-comment'             => ' [[:$2]]: $3 හා සමග [[:$1]] ඒකාබද්ධ කරන ලදි',
'mergehistory-same-destination'    => 'මූල හා අන්ත පිටු දෙකම එකක් විය නොහැක',
'mergehistory-reason'              => 'හේතුව:',

# Merge log
'mergelog'           => 'එකාබද්ධ කිරීම් ලඝු-සටහන',
'pagemerge-logentry' => '[[$2]] හා සමග [[$1]] ඒකාබද්ධ කරන ලදි ($3 දක්වා සංශෝධනයන්)',
'revertmerge'        => 'ඒකාබද්ධය අහෝසි කරන්න',
'mergelogpagetext'   => 'එක් පිටු ඉතිහාසයක් තවකක් හා සමග ඉතා මෑතදී සිදුවූ ඒකාබද්ධ වීම් දැක්වෙන ලැයිස්තුවක් පහත වේ.',

# Diffs
'history-title'            => '"$1"හි සංශෝධන ඉතිහාසය',
'difference'               => '(අනුවාද අතර වෙනස්කම්)',
'lineno'                   => 'පේළිය $1:',
'compareselectedversions'  => 'තෝරාගත් සංශෝධන සසඳන්න',
'showhideselectedversions' => 'තෝරාගත් සංශෝධන පෙන්වන්න/සඟවන්න',
'editundo'                 => 'අහෝසි කරන්න',
'diff-multi'               => '({{PLURAL:$1|එක් අතරමැදි සංශෝධනයක්| අතරමැදි සංශෝධන $1 ක්}} පෙන්නුම් කර නොමැත.)',

# Search results
'searchresults'                    => 'ගවේෂණ ප්‍රතිඵල',
'searchresults-title'              => '"$1" සඳහා ගවේෂණ ප්‍රතිඵල',
'searchresulttext'                 => '{{SITENAME}} ගවේෂණය පිළිබඳ වැඩි විස්තර සඳහා , [[{{MediaWiki:Helppage}}|{{int:help}}]] බලන්න.',
'searchsubtitle'                   => 'ඔබගේ ගවේෂණය වූයේ  \'\'\'[[:$1]]\'\'\'  සඳහාය ([[Special:Prefixindex/$1| "$1" යෙන් ඇරඹෙන සියළු පිටු]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1| "$1" වෙත සබැ‍ඳෙන සියළු පිටු]])',
'searchsubtitleinvalid'            => "ඔබගේ ගවේෂණය වූයේ  '''$1''' සඳහාය",
'toomanymatches'                   => 'පමණට වඩා ගැලපුම් ගණනක් ලැබුණි, කරුණාකර වෙනස් විමසුමක් සිදුකර බලන්න',
'titlematches'                     => 'පිටු ශීර්ෂය ගැළපෙයි',
'notitlematches'                   => 'පිටු ශීර්ෂ කිසිවක් නොගැළපෙති',
'textmatches'                      => 'පිටු පෙළ ගැළපෙයි',
'notextmatches'                    => 'පිටු පෙළ කිසිවක් නොගැළපෙයි',
'prevn'                            => 'පූර්ව  {{PLURAL:$1|$1}}',
'nextn'                            => 'මීලඟ  {{PLURAL:$1|$1}}',
'prevn-title'                      => 'පූර්ව  {{PLURAL:$1|ප්‍රතිඵලය|ප්‍රතිඵලයන් $1}}',
'nextn-title'                      => 'මීලඟ  {{PLURAL:$1|ප්‍රතිඵලය|ප්‍රතිඵල $1}}',
'shown-title'                      => 'එක් පිටුවක {{PLURAL:$1|ප්‍රතිඵලයක්|ප්‍රතිඵල $1 ක්}} බැගින් පෙන්වන්න',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) නරඹන්න',
'searchmenu-legend'                => 'ගවේෂණ තෝරාගැනීම්',
'searchmenu-exists'                => "''' මෙම විකියෙහි  \"[[:\$1]]\" ලෙස නම් කර ඇති පිටුවක් ඇත'''",
'searchmenu-new'                   => "'''මෙම විකියෙහි \"[[:\$1]]\" පිටුව තනන්න!'''",
'searchhelp-url'                   => 'Help:පටුන',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|මෙම උපසර්ගය භාවිත කරමින් පිටු පිරික්සන්න]]',
'searchprofile-articles'           => 'අන්තර්ගත පිටු',
'searchprofile-project'            => 'උදවු හා ව්‍යාපෘති පිටු',
'searchprofile-images'             => 'බහුමාධ්‍ය',
'searchprofile-everything'         => 'සියල්ල',
'searchprofile-advanced'           => 'ප්‍රගත',
'searchprofile-articles-tooltip'   => '$1 හි ගවේෂණය කරන්න',
'searchprofile-project-tooltip'    => '$1 හි ගවේෂණය කරන්න',
'searchprofile-images-tooltip'     => 'ගොනු සඳහා ගවේෂණය කරන්න',
'searchprofile-everything-tooltip' => 'සියළු අන්තර්ගතය ගවේෂණය කරන්න(සාකච්ඡා පිටුද ඇතුළුව)',
'searchprofile-advanced-tooltip'   => 'අභිරුචි නාමඅවකාශයන්හි ගවේෂණය කරන්න',
'search-result-size'               => '$1 ({{PLURAL:$2|වචන1 ක් |වචන $2 ක්}})',
'search-result-category-size'      => '{{PLURAL:$1|එක් සාමාජීකයෙන්|සාමාජීකයන් $1 ක්}} ({{PLURAL:$2|එක් උප-ප්‍රවර්ගයක්|උප-ප්‍රවර්ග $2 ක්}}, {{PLURAL:$3|එක් ගොනුවක්|ගොනු $3 ක්}})',
'search-result-score'              => 'අදාළතාව: $1%',
'search-redirect'                  => '($1 යළි-යොමු කරන්න)',
'search-section'                   => '($1 ඡේදය)',
'search-suggest'                   => 'ඔබ අදහස් කළේ මෙයද: $1',
'search-interwiki-caption'         => 'සොයුරු ව්‍යාපෘති',
'search-interwiki-default'         => '$1 වෙතින් ප්‍රතිඵල:',
'search-interwiki-more'            => '(තවත්)',
'search-mwsuggest-enabled'         => 'ඇඟවිලි සමගින්',
'search-mwsuggest-disabled'        => 'ඇඟවිලි නොමැත',
'search-relatedarticle'            => 'සහසම්බන්ධිත',
'mwsuggest-disable'                => 'AJAX ඇඟවිලි අක්‍රීය කරන්න',
'searcheverything-enable'          => 'සියළු නාමඅවකාශයන්හි ගවේෂණය කරන්න',
'searchrelated'                    => 'සම්බන්ධිත',
'searchall'                        => 'සියල්ල',
'showingresults'                   => "#'''$2''' ගෙන් ආරම්භ කොට, {{PLURAL:$1|ප්‍රතිඵල '''1'''  ක් |ප්‍රතිඵල '''$1''' ක්}} දක්වා පහත පෙන්වා ඇත.",
'showingresultsnum'                => "#'''$2''' ගෙන් ආරම්භ කොට, {{PLURAL:$3|ප්‍රතිඵල '''1'''  ක් |ප්‍රතිඵල '''$3''' ක්}} පහත පෙන්වා ඇත.",
'showingresultsheader'             => "'''$4''' සඳහා {{PLURAL:$5| '''$3''' අතුරින් '''$1''' ප්‍රතිඵලය| '''$3''' අතුරින් '''$1 - $2''' ප්‍රතිඵලයන් }}",
'nonefound'                        => "'''සටහන''': පෙරනිමියෙන් ගවේෂණය වන්නේ සමහරක් නාමඅවකාශ පමණි.
ඔබ‍ගේ විමසුමට ''all:'' උපසර්ගය යෙදීම මගින් සියළු අන්තර්ගතය ගවේෂණයට ඉඩ සැලසීම අත්හදා බලන්න (සාකච්ඡා පිටු, සැකිලි, ආදියද ඇතුළුව), නැතහොත්, උපසර්ගය ලෙස අපේක්‍ෂිත නාමඅවකාශය භාවිතා කරන්න.",
'search-nonefound'                 => 'විමසුම හා ගැලපෙන ප්‍රතිථල කිසිවක් නොමැත.',
'powersearch'                      => 'වැඩිමනත් ගවේෂණය කරන්න',
'powersearch-legend'               => 'වැඩිමනත් ගවේෂණය',
'powersearch-ns'                   => 'නාමඅවකාශයන්හි ගවේෂණය කරන්න:',
'powersearch-redir'                => 'යළි-යොමුවීම් ලැයිස්තුගත කරන්න',
'powersearch-field'                => 'සඳහා ගවේෂණය',
'powersearch-togglelabel'          => 'පිරික්සන්න:',
'powersearch-toggleall'            => 'සියල්ල',
'powersearch-togglenone'           => 'කිසිවක් නොමැත',
'search-external'                  => 'බාහිර ගවේෂණය',
'searchdisabled'                   => '{{SITENAME}} ගවේෂණය අක්‍රීය කොට ඇත.
මේ අතරතුර ඔබ හට ගූගල් ඔස්සේ ගවේෂණය කල හැක.
{{SITENAME}} අන්තර්ගතය පිළිබඳ ඔවුන්ගේ සූචි යල් පැන ගොස් ඇතිවා විය හැකි බව සටහන් කර ගන්න.',

# Quickbar
'qbsettings'               => 'යුහුතීරුව',
'qbsettings-none'          => 'කිසිවක් නොමැත',
'qbsettings-fixedleft'     => 'ස්ථාවර වම',
'qbsettings-fixedright'    => 'ස්ථාවර දකුණ',
'qbsettings-floatingleft'  => 'ප්ලාවක වම',
'qbsettings-floatingright' => 'ප්ලාවක දකුණ',

# Preferences page
'preferences'                   => 'අභීරුචි',
'mypreferences'                 => 'මගේ අභිරුචි',
'prefs-edits'                   => 'සංස්කරණයන් සංඛ්‍යාව:',
'prefsnologin'                  => 'ප්‍රවිෂ්ට වී නොමැත',
'prefsnologintext'              => 'පරිශීලක අභිරුචි සැකසීමට නම්, ඔබ  <span class="plainlinks">[{{fullurl:Special:Userlogin|returnto=$1}} ප්‍රවිෂ්ටවී]</span> සිටිය යුතුය.',
'changepassword'                => 'මුරපදය වෙනස් කරන්න',
'prefs-skin'                    => 'ඡවිය',
'skin-preview'                  => 'පෙරදසුන',
'prefs-math'                    => 'ගණිත',
'datedefault'                   => 'අභිරුචියක් නොමැත',
'prefs-datetime'                => 'දිනය සහ වේලාව',
'prefs-personal'                => 'පරිශීලක පැතිකඩ',
'prefs-rc'                      => 'මෑත වෙනස්කිරීම්',
'prefs-watchlist'               => 'මුර-ලැයිස්තුව',
'prefs-watchlist-days'          => 'මුර-ලැයිස්තුවෙහි පෙන්විය යුතු දිනයන්:',
'prefs-watchlist-days-max'      => '(උපරිමයෙන් දින7 ක්)',
'prefs-watchlist-edits'         => 'ආවර්ධිත මුර-ලැයිස්තුවෙහි පෙන්විය යුතු උපරිම වෙනස්වීම් සංඛ්‍යාව:',
'prefs-watchlist-edits-max'     => '(උපරිම සංඛ්‍යාව: 1000)',
'prefs-watchlist-token'         => 'මුරලැයිස්තු ටෝකනය:',
'prefs-misc'                    => 'විවිධ',
'prefs-resetpass'               => 'මුර-පදය වෙනස් කරන්න',
'prefs-email'                   => 'විද්‍යුත්-ලිපි තෝරාගැනීම්',
'prefs-rendering'               => 'පෙනුම',
'saveprefs'                     => 'සුරැකුම',
'resetprefs'                    => 'නොසුරැකූ වෙනස්වීම් නිෂ්කාශනය කරන්න',
'restoreprefs'                  => 'පෙරනිමි පරිස්ථිතීන් සියල්ල යළි-පිහිටුවන්න',
'prefs-editing'                 => 'සංස්කරණය කරමින්',
'prefs-edit-boxsize'            => 'සංස්කරණ ‍කවුළුවෙහි ප්‍රමාණය.',
'rows'                          => 'පේළි:',
'columns'                       => 'තීරු:',
'searchresultshead'             => 'ගවේෂණය',
'resultsperpage'                => 'පිටුවකට හිට් ගණන:',
'contextlines'                  => 'හිට් එකකට පේළි ගණන:',
'contextchars'                  => 'එක් පේළියකට සන්දර්භය:',
'stub-threshold'                => '<a href="#" class="stub">කොට සබැඳි</a> ආකෘතිකරණය සඳහා සීමකය (බයිට්):',
'recentchangesdays'             => 'මෑත වෙනස්වීම්හි පෙන්විය යුතු දිනයන්:',
'recentchangesdays-max'         => '(උපරිමයෙන් {{PLURAL:$1|එක් දිනක්|දින $1 ක්}})',
'recentchangescount'            => 'පෙරනිමියෙන් පෙන්විය යුතු සංස්කරණ ගණන:',
'prefs-help-recentchangescount' => 'මෑත වෙනස්වීම්, පිටු ඉතිහාසයන්, සහ ලඝු-සටහන් මෙයට ඇතුලත් වෙති.',
'prefs-help-watchlist-token'    => 'මෙම පාටීරය වෙත රහස් කේතයක් ඇතුළු කිරීමෙන් ඔබගේ මුරලැයිස්තුව වෙත RSS පෝෂකයක් ජනනය වනු ඇත.
මෙම පාටීරයෙහි කේතය දන්නා ඕනෑම අයෙකුට ඔබගේ මුරලැයිස්තුව කියවිය හැකි වන අතර, එබැවින්ම විරල එකක් තෝරාගන්න.
ඔබ හට භාවිතා කල හැකි අහඹු ලෙස-ජනනය වූ අගයක් මෙන්න: $1',
'savedprefs'                    => 'ඔබගේ අභිරුචි සුරැකීම සිදු කර ඇත.',
'timezonelegend'                => 'වේලා කලාපය:',
'localtime'                     => 'ප්‍රාදේශීය වේලාව:',
'timezoneuseserverdefault'      => 'සේවාදායක පෙරනිමිය භාවිතා කරන්න',
'timezoneuseoffset'             => 'වෙනත් (හිලව්ව නියමාකාරයෙන් දක්වන්න)',
'timezoneoffset'                => 'හිලව්ව¹:',
'servertime'                    => 'සර්වරයේ වේලාව:',
'guesstimezone'                 => 'බ්‍රවුසරයෙන් පුරවන්න',
'timezoneregion-africa'         => 'අප්‍රිකාව',
'timezoneregion-america'        => 'ඇමරිකාව',
'timezoneregion-antarctica'     => 'ඇන්ටාටිකාව',
'timezoneregion-arctic'         => 'අත්ලාන්තික්',
'timezoneregion-asia'           => 'ආසියාව',
'timezoneregion-atlantic'       => 'අත්ලාන්තික් සාගරය',
'timezoneregion-australia'      => 'ඔස්ට්‍රේලියාව',
'timezoneregion-europe'         => 'යුරෝපය',
'timezoneregion-indian'         => 'ඉන්දියානු සාගරය',
'timezoneregion-pacific'        => 'පැසිපික් සාගරය',
'allowemail'                    => 'අනෙකුත් පරිශීලකයන්ගෙන් විද්‍යුත්-තැපෑල ලැබීම සක්‍රීය කරන්න',
'prefs-searchoptions'           => 'ගවේෂණ විකල්පයන්',
'prefs-namespaces'              => 'නාමඅවකාශ',
'defaultns'                     => 'පෙරනිමියෙන් මෙම නාමඅවකාශයන්හි ගවේෂණය කරන්න:',
'default'                       => 'පෙරනිමි',
'prefs-files'                   => 'ගොනු',
'prefs-custom-css'              => 'අභිරුචි CSS',
'prefs-custom-js'               => ' අභිරුචි JS',
'prefs-common-css-js'           => 'සියළු සිවි සඳහා හවුලේ භාවිත  CSS/ජාවා ස්ක්‍රිප්ට්:',
'prefs-reset-intro'             => 'ඔබගේ අභිප්‍රේතයන්, අඩවි පෙරනිමි වෙතට යළි-පිහිටුවීම සඳහා, ඔබ හට මෙම පිටුව භාවිතා කල හැක.
මෙය අහෝසි කල නොහැක.',
'prefs-emailconfirm-label'      => 'විද්‍යුත්-ලිපිනය තහවුරුකිරීම:',
'prefs-textboxsize'             => 'සංස්කරණ කවුළුවෙහි ප්‍රමාණය',
'youremail'                     => 'විද්‍යුත් තැපෑල:',
'username'                      => 'පරිශීලක නාමය:',
'uid'                           => 'පරිශීලක අනන්‍යාංකය:',
'prefs-memberingroups'          => 'ඉදිරියේ දැක්වෙන {{PLURAL:$1|කණ්ඩායමෙහි|කණ්ඩායම් වල}} සාමාජිකයෙකි:',
'prefs-registration'            => 'ලියාපදිංචිවූ වේලාව:',
'yourrealname'                  => 'සැබෑ නාමය:',
'yourlanguage'                  => 'භාෂාව:',
'yourvariant'                   => 'විචල්‍යය:',
'yournick'                      => 'නව අත්සන:',
'prefs-help-signature'          => 'කතා පිටුව මත සටහන් "<nowiki>~~~~</nowiki>" මඟින් අත්සන් තැබිය යුතු අතර එය ඔබේ අත්සන හා කාල මුද්‍රාව බවට පරිවර්තනය වනු ඇත.',
'badsig'                        => 'නොනිමි අත්සන අනීතිකයි.
HTML ටැගයන් පිරික්සන්න.',
'badsiglength'                  => 'ඔබගේ විද්‍යුත් අත්සන පමණට වඩා දිගු වැඩිය.
එය  {{PLURAL:$1|එක් අක්ෂරයකට|අක්ෂරයන් $1 කට}} වඩා කෙටි විය යුතුය.',
'yourgender'                    => 'ස්ත්‍රී/පුරුෂ භාවය:',
'gender-unknown'                => 'හෙළි නොකරයි',
'gender-male'                   => 'පුරුෂ',
'gender-female'                 => 'ස්ත්‍රී',
'prefs-help-gender'             => 'වෛකල්පික: මෘදුකාංග විසින් නිවැරැදි-ලිංගභේද යොමුකිරීම් සඳහා භාවිතා කෙරෙයි.
මෙම තොරතුරු සමස්ත ප්‍රජාව සඳහා වෙයි.',
'email'                         => 'විද්‍යුත් තැපෑල',
'prefs-help-realname'           => 'සැබෑ නාමය හෙළි කිරීම වෛකල්පිකයි.
ඔබ විසින් එය හෙළි කල හොත්, ඔබගේ කෘතීන් සඳහා ඔබහට කතෘ-බුහුමන් පිරිනැමීමට එය භාවිතා කරනු ඇත.',
'prefs-help-email'              => 'විද්‍යුත්-තැපෑල ලිපිනය සැපයීම වෛකල්පිකයි, එනමුදු ඔබගේ මුර-පදය ඔබහට අමතක වූ විටෙක නව මුර-පදයක් ඔබහට විද්‍යුත්-තැපැල්ගත කිරීමට එය ප්‍රයෝජනවත් විය හැක.
අනෙක් අතට, ඔබගේ පරිශීලක පිටුව හෝ පරිශීලක_සාකච්ඡා පිටුව හෝ තුලින් අනෙකුන් හට ඔබ හා සම්බන්ධ වීමට ඉඩ සැලසීමෙන්,  ඔබගේ අනන්‍යතාවය හෙළි නොකර සිටීමට ඔබහට හැකිය.',
'prefs-help-email-required'     => 'විද්‍යුත්-ලිපිනය අවශ්‍යයි.',
'prefs-info'                    => 'මූලික තොරතුරු',
'prefs-i18n'                    => 'ජාත්‍යන්තරකරණය',
'prefs-signature'               => 'අත්සන',
'prefs-dateformat'              => 'දින ආකෘතිය',
'prefs-timeoffset'              => 'වේලා හිලව්ව',
'prefs-advancedediting'         => 'ප්‍රගත විකල්පයන්',
'prefs-advancedrc'              => 'ප්‍රගත විකල්පයන්',
'prefs-advancedrendering'       => 'ප්‍රගත විකල්පයන්',
'prefs-advancedsearchoptions'   => 'ප්‍රගත විකල්පයන්',
'prefs-advancedwatchlist'       => 'ප්‍රගත විකල්පයන්',
'prefs-displayrc'               => 'දර්ශන විකල්පයන්',
'prefs-diffs'                   => 'වෙනස',

# User rights
'userrights'                     => 'පරිශීලක හිමිකම් කළමනාකරණය',
'userrights-lookup-user'         => 'පරිශීලක කණ්ඩායම් කළමනාකරණය කරන්න',
'userrights-user-editname'       => 'පරිශීලක-නාමයක් ආදායනය කරන්න:',
'editusergroup'                  => 'පරිශීලක කණ්ඩායම් සංස්කරණය කරන්න',
'editinguser'                    => "පරිශීලක  '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]]) ගේ පරිශීලක හිමිකම් වෙනස්කිරීම",
'userrights-editusergroup'       => 'පරිශීලක කණ්ඩායම් සංස්කරණය කරන්න',
'saveusergroups'                 => 'පරිශීලක කණ්ඩායම් සුරකින්න',
'userrights-groupsmember'        => 'ඉදිරි කාණ්ඩයන්හි සාමාජිකයෙකි:',
'userrights-groupsmember-auto'   => 'මෙහි ව්‍යංග්‍ය සාමාජීක:',
'userrights-groups-help'         => 'මෙම පරිශීලකයා අයත් වන කණ්ඩායම් ඔබ හට වෙනස් කල හැක:
* කතිර යෙදූ කොටුවකින් ගම්‍ය වන්නේ පරිශීලකයා එම කණ්ඩායමට අයත් බවය.
* කතිර නෙයෙදූ කොටුවකින් ගම්‍ය වන්නේ පරිශීලකයා මෙම කණ්ඩායමට අයත් නොවන බවය.
* * යන්නක් අඟවනුයේ ඔබ විසින් එක් කල පසු කණ්ඩායම ඉවත් කල නොහැකි බවද එය ප්‍රතිලෝම වශයෙන්ද සත්‍ය වන බවත්ය.',
'userrights-reason'              => 'හේතුව:',
'userrights-no-interwiki'        => 'අනෙකුත් විකියන්හි පරිශීලක හිමිකම් සංස්කරණය කිරීමට ඔබහට අවසර නොමැත.',
'userrights-nodatabase'          => '$1 දත්ත-ගබඩාව නොපවතියි හෝ ස්ථානීක නොවෙයි.',
'userrights-nologin'             => 'පරිශීලක හිමිකම් ප්‍රදානය කරනු වස්, ඔබ පරිපාලක ගිණුමකින්  [[Special:UserLogin|පුවිෂ්ට විය]] යුතුය.',
'userrights-notallowed'          => 'පරිශීලක හිමිකම් ප්‍රදානය කිරීමට ඔබගේ ගිණුමට අවසර නොමැත.',
'userrights-changeable-col'      => 'ඔබට වෙනස් කල හැකි කණ්ඩායම්',
'userrights-unchangeable-col'    => 'ඔබට වෙනස් කල නොහැකි කණ්ඩායම්',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'කණ්ඩායම:',
'group-user'          => 'පරිශීලකයෝ',
'group-autoconfirmed' => 'ස්වයං-චිරස්ථිත පරිශීලකයෝ',
'group-bot'           => 'රොබෝවරු',
'group-sysop'         => 'පරිපාලකවරු',
'group-bureaucrat'    => 'නිලබලධාරියෝ',
'group-suppress'      => 'ප්‍රමාද දෝෂයන්',
'group-all'           => '(සියල්ල)',

'group-user-member'          => 'පරිශීලක',
'group-autoconfirmed-member' => 'ස්වයං-චිරස්ථිත පරිශීලකයා',
'group-bot-member'           => 'රොබෝවරයා',
'group-sysop-member'         => 'පරිපාලකවරයා',
'group-bureaucrat-member'    => 'නිලබලධාරියා',
'group-suppress-member'      => 'ප්‍රමාද දෝෂය',

'grouppage-user'          => '{{ns:project}}:පරිශිලකයෝ',
'grouppage-autoconfirmed' => '{{ns:project}}:ස්වයං-චිරස්ථිත පරිශීලකයෝ',
'grouppage-bot'           => '{{ns:project}}:රොබෝවරු',
'grouppage-sysop'         => '{{ns:project}}:පරිපාලකවරු',
'grouppage-bureaucrat'    => '{{ns:project}}:නිලබලධාරියෝ',
'grouppage-suppress'      => '{{ns:project}}:ප්‍රමාද දෝෂය',

# Rights
'right-read'                  => 'පිටු කියවන්න',
'right-edit'                  => 'පිටු සංස්කරණය කරන්න',
'right-createpage'            => '(සංවාද පිටු නොවන) පිටු තනන්න',
'right-createtalk'            => 'සංවාද පිටු තනන්න',
'right-createaccount'         => 'නව පරිශීලක ගිණුම් තනන්න',
'right-minoredit'             => 'සංස්කරණ සුළු ලෙස සළකුණු කරන්න',
'right-move'                  => 'පිටු ගෙන යන්න',
'right-move-subpages'         => 'පිටු ඒවායේ උපපිටු ද සමග ගෙනයන්න',
'right-move-rootuserpages'    => 'මූල පරිශීලක පිටු ගෙනයන්න',
'right-movefile'              => 'ගොනු රැගෙන යන්න',
'right-suppressredirect'      => 'පිටුවක් ගෙනයන විට පැරණි නම වෙතින් යළි-යොමුවක් නොතනන්න',
'right-upload'                => 'ගොනු උඩුගත කරන්න',
'right-reupload'              => 'පවතින ගොනු අධිලිවීමකට ලක්කරන්න (overwrite)',
'right-reupload-own'          => 'යමෙකු විසින්ම උඩුගත කෙරුනු පවතින ගොනුවක් අධිලිවීමකට ලක්කරන්න',
'right-reupload-shared'       => 'හවුල් මාධ්‍ය සුරක්‍ෂිතාගාරයෙහි ගොනු සීමිත අබිබැවීමකට ලක් කරන්න',
'right-upload_by_url'         => 'URL ලිපිනයකින් (කලාප ලිපිනයකින්) ගොනුවක් උඩුගත කරන්න',
'right-purge'                 => 'තහවුරුකිරීමකින් තොරව, පිටුවක් සඳහා අඩවි පූර්වාපේක්‍ෂි සංචිතය (කෑෂය) විමෝචනය කරන්න',
'right-autoconfirmed'         => 'අර්ධ-ආරක්‍ෂිත පිටු සංස්කරණය කරන්න',
'right-bot'                   => 'ස්වයංක්‍රීය ක්‍රියාවලියක් ලෙස සැළකෙන්න',
'right-nominornewtalk'        => 'සංවාද පිටුවලට සිදුකෙරෙන සුළු සංස්කරණ හේතුවෙන් නව පණිවුඩයන් ඉඟිය පූරනය නොකරන්න',
'right-apihighlimits'         => 'API විමසුම් වලදී ඉහළ සීමාවන් භාවිතා කරන්න',
'right-writeapi'              => 'ලිවීම්  API භාවිතය',
'right-delete'                => 'පිටු මකා දමන්න',
'right-bigdelete'             => 'විශාල ඉතිහාස ඇති පිටු මකා දමන්න',
'right-deleterevision'        => 'පිටුවල විශේෂිත සංශෝධනයන් මකා දැමීම හා මක දැමීම ප්‍රතිලෝම කිරීම සිදු කරන්න',
'right-deletedhistory'        => 'ඒවායෙහි ආශ්‍රිත පෙළ රහිතව, මකාදැමුනු ඉතිහාස සංලේඛයන් නරඹන්න',
'right-deletedtext'           => 'මකා දැමූ සංශෝධන අතරතුර මකා දැමූ පෙළ හා වෙනස්වීම් පෙන්වන්න',
'right-browsearchive'         => 'මකාදැමූ පිටු ගවේෂණය කරන්න',
'right-undelete'              => 'පිටුවක් මකාදැමීම ප්‍රතිලෝම කරන්න',
'right-suppressrevision'      => 'පරිපාලකවරුන් වෙතින් සඟවා ඇති සංශෝධනයන් විමර්ශනය කොට ප්‍රතිෂ්ඨාපනය කරන්න',
'right-suppressionlog'        => 'පෞද්ගලික ලඝු-සටහන් නරඹන්න',
'right-block'                 => 'අනෙකුත් පරිශීලකයන් සංස්කරණය කිරීමෙන් වාරණය කරන්න',
'right-blockemail'            => 'පරිශීලකයාගේ විද්‍යුත්-තැපැල් යැවීමේ හැකියාව වාරණය කරන්න',
'right-hideuser'              => 'පරිශීලක නාමයක් වාරණය කරමින්, එය ප්‍රජාව වෙතින් සඟවන්න',
'right-ipblock-exempt'        => 'අන්තර්ජාල ලිපින වාරණයන්, ස්වයංක්‍රීය-වාරණයන් හා පරාස වාරණයන් මඟ හරින්න',
'right-proxyunbannable'       => 'ප්‍රතියුක්තයන්ගේ ස්වයංක්‍රීයව වාරණයන් මඟහරින්න',
'right-unblockself'           => 'ඔවුන් විසින්ම වාරණයෙන් මුදවීම',
'right-protect'               => 'ආරක්ෂණ මට්ටම් වෙනස් කරමින් ආරක්ෂිත පිටු සංස්කරණය කරන්න',
'right-editprotected'         => 'ආරක්ෂිත පිටු සංස්කරණය කරන්න (තීරු-දර්ශන ආරක්ෂණය විරහිත)',
'right-editinterface'         => 'පරිශීලක අතුරු-මුව සංස්කරණය කරන්න',
'right-editusercssjs'         => 'අනෙකුත් පරිශීලකයන්ගේ  CSS හා JS ගොනු සංස්කරණය කරන්න',
'right-editusercss'           => 'අනෙකුත් පරිශීලකයන්ගේ  CSS ගොනු සංස්කරණය කරන්න',
'right-edituserjs'            => 'අනෙකුත් පරිශීලකයන්ගේ  JS ගොනු සංස්කරණය කරන්න',
'right-rollback'              => 'සුවිශේෂ පිටුවක් අවසන් වරට සංස්කරණය කල පරිශීලකයෙකුගේ සංස්කරණයන් විගසින් පුනරාවර්තනය කරන්න',
'right-markbotedits'          => 'පුනරාවර්තනය-කෙරුනු සංස්කරණයන් රොබෝ සංස්කරණයන් ලෙස සලකුණු කරන්න',
'right-noratelimit'           => '‍සීඝ්‍රතා සීමාවන්ගෙක් බලපෑම් ඇතිනොවන්න',
'right-import'                => 'අනෙකුත් විකියන්ගෙන් පිටු ආයාත කරන්න',
'right-importupload'          => 'ගොනු උඩුගත කිරීමකින් පිටු ආයාත කරන්න',
'right-patrol'                => 'අනෙකුන්ගේ සංස්කරණ, පරික්ෂා කර බැලූ ලෙස, සලකුණු කරන්න',
'right-autopatrol'            => 'අයෙකුගේ ස්වීය සංස්කරණයන්, ස්වයංක්‍රීය ලෙස, පරික්‍ෂාකර බැලූ ලෙස සලකුණු කරන්න',
'right-patrolmarks'           => 'මෑත වෙනස්වීම් පරික්ෂාකරබැලීම් ලකුණුකිරීම් නරඹන්න',
'right-unwatchedpages'        => 'මුර-නොකෙරෙන පිටු ලැයිස්තුවක් නරඹන්න',
'right-trackback'             => 'පසුහැඹීමක් ඉදිරිපත් කරන්න',
'right-mergehistory'          => 'පිටුවල ඉතිහාසයන් ඒකාබද්ධ කරන්න',
'right-userrights'            => 'පරිශීලක හිමිකම් සියල්ල සංස්කරණය කරන්න',
'right-userrights-interwiki'  => 'අනෙකුත් විකියන්හි පරිශීලකයන්ගේ හිමිකම් සංස්කරණය කරන්න',
'right-siteadmin'             => 'දත්ත-ගබඩාව අවුරන්න හා ඇවුරුම ඉවත් කරන්න',
'right-reset-passwords'       => 'අනෙක් පරිශීලකගේ මුරපදය ප්‍රතිෂ්ඨාපනය කරන්න',
'right-override-export-depth' => '5වන මට්ටම දක්වා සබැඳි පිටු ද සහිතව පිටු නිර්යාත කරන්න',
'right-sendemail'             => 'අනෙක් පරිශීලකයන්ට ඊ-ලිපි යවන්න',
'right-revisionmove'          => 'සංශෝධන ඒකාබද්ධ කරන්න',

# User rights log
'rightslog'      => 'පරිශීලක හිමිකම් සටහන',
'rightslogtext'  => 'මෙය පරිශීලකයන්ගේ හිමිකම් වෙනස්වීම් පිළිබඳ ලඝු-සටහනකි.',
'rightslogentry' => '$1 සඳහා කණ්ඩායම් සාමාජිකත්වය $2 සිට $3 දක්වා වෙනස්කෙරිණි',
'rightsnone'     => '(කිසිවක් නොමැත)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'මෙම පිටුව කියවන්න',
'action-edit'                 => 'මෙම පිටුව සංස්කරණය කරන්න',
'action-createpage'           => 'පිටු තනන්න',
'action-createtalk'           => 'සංවාද පිටු තනන්න',
'action-createaccount'        => 'මෙම පරිශීලක ගිණුම තනන්න',
'action-minoredit'            => 'මෙම සංස්කරණය සුළු ලෙස සලකුණු කරන්න',
'action-move'                 => 'මෙම පිටුව ගෙනයන්න',
'action-move-subpages'        => 'මෙම පිටුව හා එහි උප පිටු ගෙන යන්න',
'action-move-rootuserpages'   => 'මූල පරිශීලක පිටු ගෙනයන්න',
'action-movefile'             => 'මෙම ගොනුව ගෙනයන්න',
'action-upload'               => 'මෙම ගොනුව උඩුගත කරන්න',
'action-reupload'             => 'දැනට පවතින මෙම ගොනුව අධිලිවීමකට ලක්කරන්න',
'action-reupload-shared'      => 'හවුල් සුරක්‍ෂිතාගාරයක ඇති මෙම ගොනුව අභිබැවීමකට ලක් කරන්න',
'action-upload_by_url'        => 'URL ලිපිනයක් (කලාප ලිපිනයක්) වෙතින් මෙම ගොනුව උඩුගත කරන්න',
'action-writeapi'             => 'ලිවීම් API භාවිතා කරන්න',
'action-delete'               => 'මෙම පිටුව මකන්න',
'action-deleterevision'       => 'මෙම සංශෝධනය මකන්න',
'action-deletedhistory'       => 'මෙම පිටුවේ මකාදැමුනු ඉතිහාසය නරඹන්න',
'action-browsearchive'        => 'මකාදැමුනු පිටු ගවේශනය කරන්න',
'action-undelete'             => 'මෙම පිටුව මකාදැමීම ප්‍රතිලෝම කරන්න',
'action-suppressrevision'     => 'මෙම සැඟවුනු සංශෝධනය විමර්ශනය කොට ප්‍රතිෂ්ඨාපනය කරන්න',
'action-suppressionlog'       => 'මෙම පෞද්ගලික ලඝු-සටහන නරඹන්න',
'action-block'                => 'මෙම පරිශීලකයා සංස්කරණය කිරීමෙන් වාරණය කරන්න',
'action-protect'              => 'මෙම පිටුවේ රැකවරණ මට්ටම් වෙනස් කරන්න',
'action-import'               => 'වෙනත් විකියක් වෙතින් මෙම පිටුව ආයාත කරන්න',
'action-importupload'         => 'ගොනු උඩුගත කිරීමක් වෙතින් මෙම පිටුව ආයාත කරන්න',
'action-patrol'               => 'අනෙකුන්ගේ සංස්කරණ, පරික්‍ෂාකර බැලූ ලෙස සලකුණු කරගන්න',
'action-autopatrol'           => 'ඔබගේ සංස්කරණය, පරික්‍ෂාකර බැලූ ලෙස සලකුණු කරවාගන්න',
'action-unwatchedpages'       => 'මුර-නොකෙරෙන පිටු ලැයිස්තුව නරඹන්න',
'action-trackback'            => 'පසුහැඹීමක් ඉදිරිපත් කරන්න',
'action-mergehistory'         => 'මෙම පිටුවේ ඉතිහාසය ඒකාබද්ධ කරන්න',
'action-userrights'           => 'සියළු පරිශීලක හිමිකම් සංස්කරණය කරන්න',
'action-userrights-interwiki' => 'අනෙකුත් විකියන්ගේ පරිශීලකයන්ගේ පරිශීලක හිමිකම් සංස්කරණය කරන්න',
'action-siteadmin'            => 'දත්ත-ගබඩාව අවුරන්න හෝ ඇවුරුම ඉවත් කරන්න',
'action-revisionmove'         => 'සංශෝධන ගෙන යන්න',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|වෙනස්වීම|වෙනස්වීම්}}',
'recentchanges'                     => 'මෑත වෙනස්කිරීම්',
'recentchanges-legend'              => 'මෑත වෙනස්කිරීම් සැකසුම් තෝරාගැනීම',
'recentchangestext'                 => 'මෙම පිටුවේ විකියට සිදුකල ඉතා මෑත වෙනස්වීම් පසුහඹන්න.',
'recentchanges-feed-description'    => 'මෙම පෝෂකයෙහි විකියට බොහෝ මෑතදී සිදුකල වෙනස්වීම් හෙළිකරන්න.',
'recentchanges-label-legend'        => 'පුරාවෘත්තය : $1',
'recentchanges-legend-newpage'      => '$1 -  නව පිටුව',
'recentchanges-label-newpage'       => 'මෙම සංස්කරණය නව පිටුවක් නිර්මාණය කරන ලදී',
'recentchanges-legend-minor'        => '$1- සුළු සංස්කරණය',
'recentchanges-label-minor'         => 'මෙය සුළු සංස්කරණයකි',
'recentchanges-legend-bot'          => '$1 - bot සංස්කරණය',
'recentchanges-label-bot'           => 'මෙම සංස්කරණය bot එකක් මඟින් සිදු කරන ලදී',
'recentchanges-legend-unpatrolled'  => '$1 - විමර්ශනය නොකළ සංස්කරණ',
'recentchanges-label-unpatrolled'   => 'මෙම සංස්කරණය තවම විමර්ශනය කර නොමැත',
'rcnote'                            => "$4 දින, $5 වන තෙක් සැලකිල්ලට ගත් කල, අවසන් {{PLURAL:$2|දිනදී|දින '''$2''' තුලදී}} සිදුවී ඇති, {{PLURAL:$1| '''1''' ක් වෙනස|අවසන් වෙනස්වීම් '''$1'''  }} මෙහි පහත දැක්වේ.",
'rcnotefrom'                        => "'''$2''' න් පසු සිදුවී ඇති වෙනස්කම් මෙහි පහත දැක්වේ ('''$1''' ක ප්‍රමාණයක උපරිමයක් පෙන්වා ඇත).",
'rclistfrom'                        => '$1 සිට බලපැවැත්වෙන මෑත වෙනස්වීම් පෙන්වන්න',
'rcshowhideminor'                   => 'සුළු සංස්කරණ $1',
'rcshowhidebots'                    => 'රොබෝ $1',
'rcshowhideliu'                     => 'ප්‍රවිෂ්ට වූ පරිශීලකයන් $1',
'rcshowhideanons'                   => 'නිර්නාමික පරිශීලකයන් $1',
'rcshowhidepatr'                    => 'පරික්‍ෂා කර බැලූ සංස්කරණයන් $1',
'rcshowhidemine'                    => 'මගේ සංස්කරණයන් $1',
'rclinks'                           => 'අවසන් දින $2 තුලදී සිදුවී ඇති අවසන් වෙනස්වීම් $1 පෙන්නුම් කරන්න<br />$3',
'diff'                              => 'වෙනස',
'hist'                              => 'ඉතිහාසය',
'hide'                              => 'සඟවන්න',
'show'                              => 'පෙන්වන්න',
'minoreditletter'                   => 'සුළු',
'newpageletter'                     => 'නව',
'boteditletter'                     => 'රොබෝ',
'number_of_watching_users_pageview' => '[ {{PLURAL:$1| එක් පරිශීලකයෙක් මුර-කරයි|පරිශීලකවරුන් $1 ක් මුර-කරති}} ]',
'rc_categories'                     => 'ප්‍රවර්ගයන්ට සීමා කරන්න ("|" මගින් වෙන් කරන්න)',
'rc_categories_any'                 => 'ඕනෑම',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ නව ඡේදය',
'rc-enhanced-expand'                => 'විස්තර පෙන්වන්න (ජාවාස්ක්‍රිප්ට් අවශ්‍යයි)',
'rc-enhanced-hide'                  => 'විස්තර සඟවන්න',

# Recent changes linked
'recentchangeslinked'          => 'සහසම්බන්ධිත වෙනස්වීම්',
'recentchangeslinked-feed'     => 'සහසම්බන්ධිත වෙනස්වීම්',
'recentchangeslinked-toolbox'  => 'සහසම්බන්ධිත වෙනස්වීම්',
'recentchangeslinked-title'    => '"$1" ට සහසම්බන්ධිත වෙනස්වීම්',
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'සලකා බැලූ කාලසීමාවෙහිදී, සබැඳි පිටු වල කිසිදු වෙනසක් සිදුවී නොමැත.',
'recentchangeslinked-summary'  => "විශේෂී ලෙස නිරූපිත පිටුවකට (හෝ විශේෂි ලෙස නිරූපිත ප්‍රවර්ගයක සාමාජීකයන්ට) සබැඳි පිටුවල  මෑතදී සිදුවූ වෙනස්වීම් දැක්වෙන ලැයිස්තුවක් මෙහි දැක්වේ.
[[Special:Watchlist|ඔබගේ  මුර-ලැයිස්තුවෙහි]] පිටු  '''තදකුරු''' වලින් දක්වා ඇත.",
'recentchangeslinked-page'     => 'පිටු නාමය:',
'recentchangeslinked-to'       => 'ඒ වෙනුවට දී ඇති පිටුවට සබැඳෙන පිටුවල වෙනස්වීම්  පෙන්වන්න',

# Upload
'upload'                      => 'ගොනුවක් උඩුගත කිරීම',
'uploadbtn'                   => 'ගොනුව උඩුගත කරන්න',
'reuploaddesc'                => 'උඩුගත කිරීම අත්හැරදමා උඩුගත කිරීම් ආකෘති පත්‍රය වෙත යන්න',
'upload-tryagain'             => 'වෙනස් කරන ලද ගොනු විස්තරය ඉදිරිපත් කරන්න',
'uploadnologin'               => 'ප්‍රවිෂ්ට වී නොමැත',
'uploadnologintext'           => 'ගොනු උඩුගත කිරීමට පෙර ඔබ  [[Special:UserLogin|ප්‍රවිෂ්ට වී]] සිටිය යුතුය.',
'upload_directory_missing'    => 'උඩුගත ඩිරෙක්ටරිය ($1) සොයාගත නොහැකි අතර එය වෙබ්-සේවාදායකය විමින් තැනිය නොහැකි විය.',
'upload_directory_read_only'  => 'වෙබ්-සේවාදායකය විසින් උඩුගත ඩිරෙක්ටරිය ($1) වෙත ලිවීමට නොහැකි විය.',
'uploaderror'                 => 'උඩුගත කිරීම් දෝෂයක්',
'upload-recreate-warning'     => "'''අවවාදයයි: මෙම නම තිබූ ගොනුවක් මකාදුමුමට හෝ ගෙනයෑමට හෝ ලක්ව ඇත.'''

ඔබගේ පහසුව තකා මෙම පිටුවවෙහි මකාදැමුමෙහි හා ගෙනයෑමෙහි ලඝු සටහන මෙහි දැක්වේ:",
'uploadtext'                  => "ගොනු උඩුගත කිරීම සඳහා පහත ආකෘති පත්‍රය භාවිතා කරන්න.
පෙරදී උඩුගතකෙරුණු ගොනු නැරඹුම හෝ ගවේෂණය සඳහා  [[Special:FileList|උඩුගතකෙරුණු ගොනු ලැයිස්තුව]] වෙත යන්න, (යළි)උඩුගතකෙරුම්ද  [[Special:Log/upload|උඩුගතකෙරුම් ලඝු-සටහන]] තුල සටහන් කර ඇති අතර, මකාදැමුම්  [[Special:Log/delete|මකාදැමුම් ලඝු-සටහන]] හි ඇත.

ගොනුවක් පිටුවක බහාලීම සඳහා, පහත ආකාරයේ සබැඳියක් භාවිතා කරන්න:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' ගොනුවෙහි පරිපූර්ණ අනුවාදය භාවිතා කිරීමට
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|alt text]]</nowiki></tt>''' 'විකල්ප පෙළ' යන්න විස්තරය ලෙසින් තැබෙමින් වම් මායිමෙහි කොටුවක පික්සල 200 පළල ප්‍රවාචිතයක් භාවිතා කිරීමට
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' ගොනුව ප්‍රදර්ශනය නොකෙරෙමින්  ගොනුවට සෘජු ලෙස සබැඳීමට",
'upload-permitted'            => 'අනුදත් ගොනු වර්ගයන්: $1.',
'upload-preferred'            => 'අභිප්‍රේත ගොනු වර්ගයන්: $1.',
'upload-prohibited'           => 'තහනම් ගොනු වර්ගයන්: $1.',
'uploadlog'                   => 'උඩුගත කිරීම් ලඝු-සටහන',
'uploadlogpage'               => 'උඩුගත කිරීම් සටහන',
'uploadlogpagetext'           => 'ඉතා මෑතදී සිදුකල ගොනු උඩුගතකිරීම් ලැයිස්තුවක් පහත දැක්වේ.
වැඩිමනත් දෘශ්‍ය සමාලෝචනය සඳහා [[Special:NewFiles|නව ගොනු ගැලරිය]] බලන්න.',
'filename'                    => 'ගොනු-නම',
'filedesc'                    => 'සාරාංශය',
'fileuploadsummary'           => 'සාරාංශය:',
'filereuploadsummary'         => 'ගොනු වෙනස්වීම්:',
'filestatus'                  => 'හිමිකම් තත්ත්වය:',
'filesource'                  => 'මූලාශ්‍රය:',
'uploadedfiles'               => 'උඩුගත කෙරුනු ගොනු',
'ignorewarning'               => 'අවවාදය නොසලකා කෙසේ හෝ ගොනුව සුරකින්න',
'ignorewarnings'              => 'සියළු අවවාද නොසලකා හරින්න',
'minlength1'                  => 'ගොනු නාමයන් අඩුම වශයෙන් එක් අකුරකින් සමන්විත විය යුතුය.',
'illegalfilename'             => 'ශීර්ෂයන්හි භාවිත කිරීමට ඉඩ නොදෙන යම් අක්ෂරයන් "$1" ගොනු නාමයෙහි අඩංගුය.
කරුණාකර ගොනුව යළිනම් කොට එය නැවත උඩුගත කිරීමට උත්සාහ කරන්න.',
'badfilename'                 => 'ගොනු නම "$1" බවට වෙනස් කර ඇත.',
'filetype-mime-mismatch'      => 'ගොනු දිගුව MIME වර්ගය හා නොගැළපේ.',
'filetype-badmime'            => '"$1" MIME වර්ගයෙහි ගොනු උඩුගත කිරීමට ඉඩ දෙනු නොලැබේ.',
'filetype-bad-ie-mime'        => 'මෙම ගොනුව උඩුගත කල නොහැකි වන්නේ ඉන්ටනෙට් එක්ස්ප්ලෝරර් විසින් එය,  ප්‍රතික්‍ෂේප කෙරෙන හා භව්‍ය ලෙසින් අනතුරුදායක ගොනු මාදිලියක් වන, "$1" ලෙසින් හඳුනාගැනෙන බැවිනි.',
'filetype-unwanted-type'      => "'''\".\$1\"''' යනු අනවශ්‍ය ගොනු වර්ගයකි.
රුචිකර {{PLURAL:\$3|ගොනු වර්ගය|ගොනු වර්ගයන්}} වන්නේ  \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' යනු අවසරලත් ගොනු වර්ගයක් නොවේ.
අවසරලත්  {{PLURAL:\$3|ගොනු වර්ගය|ගොනු වර්ගයන්}} වන්නේ  \$2.",
'filetype-missing'            => 'ගොනුවට  (".jpg" වැනි) ප්‍රසර්ජනයක් නොමැත.',
'empty-file'                  => 'ඔබ ඉදිරිපත්කල ගොනුව හිස් එකකි.',
'file-too-large'              => 'ඔබ විසින් යොමන ලද ගොනුව පමණට වඩා විශෘලය.',
'filename-tooshort'           => 'ගොනුනාමය කෙටි වැඩිය.',
'filetype-banned'             => 'මෙම ගොනු වර්ගය ප්‍රතිෂේධිත (තහනම් කල) ය.',
'verification-error'          => 'මෙම ගොනුව සත්‍යායනය කිරීමෙන් සමත් වූ බවට පිළිගත නොහැක.',
'hookaborted'                 => 'දිගු ආකුශයක් (කොක්කක්) විසින් ඔබ උත්සාහ කල වෙනස්කිරීම රෝධනය කරන ලදි.',
'illegal-filename'            => 'පිටු-නාමයට ඉඩ දෙනු නොලැබේ.',
'overwrite'                   => 'දැනට බවතින ගොනුවක් උපරිලේඛනය (උඩින් ලිවීඹ) කෙරුමට ඉඩදෙනු නොලැබේ.',
'unknown-error'               => 'අඥාත (නොදන්නා) දෝෂයක් හට ගැනුනි.',
'tmp-create-error'            => 'තාවකාලික ගොනුවක් තැනීම කල නොහැකි විය.',
'tmp-write-error'             => 'තාවකාලික ගොනුව ලිවීමේදී දෝෂයක් හට ගැනුනි.',
'large-file'                  => 'ගොනුවල විශාලත්වය  $1 ට වඩා වැඩි නොවීම නිර්දේශ කරනු ලැබේ;
මෙම ගොනුව  $2 ක් විශාලය.',
'largefileserver'             => 'සේවාදායකයේ හැඩගස්වීම ප්‍රකාර ඉඩ ලබා දෙන ප්‍රමාණයට වඩා මෙම ගොනුව විශාලය.',
'emptyfile'                   => 'ඔබ විසින් උඩුගත කරන ලද ගොනුව හිස් බවක් පෙනේ.
මෙය සමහරවිට ගොනු  නාමයේ මුද්‍රණ දෝෂයක් නිසා විය හැක.
ඔබට නිසැකවම මෙම ගොනුව උඩුගත කිරීමට අවශ්‍යයද යන්න පරික්‍ෂා කර බලන්න.',
'fileexists'                  => "මෙම නම සහිත ගොනුවක් දැනටමත් පවතියි, මෙය වෙනස් කල යුතු බවට ඔබට නිසැක නොවේ නම්, කරුණාකර '''<tt>[[:$1]]</tt>''' පරික්ෂා කර බලන්න .
[[$1|thumb]]",
'filepageexists'              => "මෙම ගොනුව සඳහා විස්තර පිටුව දැනටමත් '''<tt>[[:$1]]</tt>''' හි තනා ඇති නමුත්, මෙම නම ඇති කිසිදු ගොනුවක් දැනට නොපවතියි.
ඔබ විසින් ඇතුලත් කෙරෙන සාරාංශය විස්තර පිටුවෙහි දිස් නොවනු ඇත.
සාරාංශය එහි  දිස්කෙරුමට,  ඔබ විසින් එය හස්තීය ලෙස සංස්කරණය කෙරුම සිදුකල යුතු වේ.
[[$1|thumb]]",
'fileexists-extension'        => "එක්වැනි නමක් ඇති ගොනුවක් පවතී: [[$2|thumb]]
* උඩුගත කෙරෙන ගොනුවේ නම: '''<tt>[[:$1]]</tt>'''
* පවතින ගොනුවේ නම: '''<tt>[[:$2]]</tt>'''
කරුණාකර වෙනත් නමක් තෝරාගන්න.",
'fileexists-thumbnail-yes'    => "ගොනුව, කුඩා ප්‍රමාණයේ රූපයක් බව පෙනී යයි ''(සිඟිති-රූපය)''. [[$1|thumb]]
කරුණාකර '''<tt>[[:$1]]</tt>''' ගොනුව පරික්‍ෂා කර බලන්න.
පරික්‍ෂා කර බැලූ ගොනුවෙහි අඩංගු වන්නේ මුලික ප්‍රමාණයෙහි රූපයම නම් අමතර සිඟිති-රූපයක් උඩුගත කිරීම අවශ්‍ය නොවේ.",
'file-thumbnail-no'           => "ගොනු නම '''<tt>$1</tt>''' යන්නෙන් ආරම්භ වේ.
එය කුඩාකල ප්‍රමාණයෙහි රූපයක් බව පෙනී යයි  ''(සිඟිති-රූපය)''.
පූර්ණ විසර්ජනය සහිත මෙම රූපය ඔබ සතු වෙයි නම් මෙය උඩුගත කරන්න, නැතහොත් ගොනු නාමය වෙනස් කරන්න.",
'fileexists-forbidden'        => 'මෙම නම ඇති ගොනුවක් දැනටමත් පවතින අතර, එය උඩින් ලීවීම සිදුකල නොහැක.
කෙසේ හෝ ඔබගේ ගොනුව උඩුගත කිරීමට ඔබට ඇවැසි නම්, කරුණාකර නැවත ගොස් නව නමක් භාවිතා කරන්න. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'මෙම නම සහිත ගොනුවක් හවුල් ගොනු සුරක්‍ෂිතාගාරයෙහි දැනටමත් පවතියි.
ඔබ හ‍ට මෙම ගොනුව උඩුගත කිරීම කෙසේ හෝ සිදුකිරීමට ඇවැසි නම්, කරුණාකර පෙරළා ගොස් අළුත් නමක් භාවිතා කරන්න. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'මෙම ගොනුව පහත  {{PLURAL:$1|ගොනුවෙහි|ගොනු වල}} අනුපිටපතකි:',
'file-deleted-duplicate'      => 'මෙම ([[$1]]) ගොනුවට සර්වසාම්‍ය ගොනුවක් පෙරදී මකාදමා ඇත.
එය යළි-උඩුගත කිරීම සඳහා කටයුතු කිරීමට පෙර එම ගොනුවෙහි මකාදැමීම් ඉතිහාසය ඔබ විසින් පරීක්ෂා කර බැලිය යුතුව ඇත.',
'successfulupload'            => 'සාර්ථක උඩුගත කිරීමකි',
'uploadwarning'               => 'උඩුගත කිරීම් අවවාදයකි',
'uploadwarning-text'          => 'කරුණාකර පහත ගොනු විස්තරය වෙනස් කර නැවත උත්සාහ කරන්න.',
'savefile'                    => 'ගොනුව සුරකින්න',
'uploadedimage'               => '"[[$1]]" උඩුගත කරන ලදි',
'overwroteimage'              => ' "[[$1]]" හි නව අනුවාදයක් උඩුගත කරන ලදි',
'uploaddisabled'              => 'උඩුගත කිරීම් අක්‍රීය කර ඇත',
'copyuploaddisabled'          => 'URL මගින් උඩුගත කිරීම අක්‍රීය කොට ඇත.',
'uploadfromurl-queued'        => 'ඔබගේ උඩුගත කිරීම පෙළ ගස්වා ඇත.',
'uploaddisabledtext'          => ' {{SITENAME}} හි ගොනු උඩුගත කිරීම් අක්‍රීය කර ඇත.',
'php-uploaddisabledtext'      => 'PHP හි ගොනු උඩුගතකිරීම් අක්‍රීය කොට ඇත.
කරුණාකර  ගොනු_උඩුගතකිරීම් පරිස්ථිතිය පරික්ෂා කර බලන්න.',
'uploadscripted'              => 'වෙබ් බ්‍රවුසරයක් මගින් සාවද්‍ය ලෙස අර්ථ පැහැදිය හැකි HTML හෝ ලේඛන ක්‍රම තේතයක් මෙම ගොනුවේ අඩංගු වේ.',
'uploadvirus'                 => 'මෙම ගොනුවෙහි වයිරසයක් අඩංගුය! විස්තර: $1',
'upload-source'               => 'මූලාශ්‍ර ගොනුව',
'sourcefilename'              => 'මූල ගොනුනාමය:',
'sourceurl'                   => 'මූලාශ්‍ර URL:',
'destfilename'                => 'අන්ත ගොනුනාමය:',
'upload-maxfilesize'          => 'උපරිම ගොනු විශාලත්වය: $1',
'upload-description'          => 'ගොනු විස්තරය',
'upload-options'              => 'උඩුගත කිරීම් විකල්ප',
'watchthisupload'             => 'මෙම ගොනුව මුර-කරන්න',
'filewasdeleted'              => 'මෙම නම ඇති ගොනුව මින් පෙර උඩුගත කොට අනතුරුව මකාදමා ඇත.
එය නැවත උඩුගතකිරීමට උත්සාහ කිරීමට පෙර ඔබ විසින්  $1 පරික්‍ෂා කර බැලිය යුතුය.',
'upload-wasdeleted'           => "'''අවවාදයයි: මින්පෙර මකාදැමුණු ගොනුවක් ඔබ විසින් උඩුගත කෙරෙමින් පවතියි.'''

මෙම ගොනුව උඩුගත කිරීම සිදුකරගෙනයාම  යෝග්‍යද යන බව ඔබ විසින් සලකා බැලිය යුතුය.
ඔබගේ පහසුව සඳහා මකාදැමුම් ලඝු-සටහන මෙහි දක්වා ඇත:",
'filename-bad-prefix'         => "ඔබ උඩුගත කරන ගොනුවේ නම, සාමාන්‍යයෙන් ස්වයංක්‍රීය ලෙස ඩිජිටල් කැමරා විසින් අනුගත කෙරෙන  අව්‍යාක්‍යාත්මක නමක් වන '''\"\$1\"''' යන්නෙන් ආරම්භ වෙයි,.
වඩාත් ව්‍යාකාත්මක නමක් ඔබගේ ගොනුව සඳහා තෝරාගැනුමට කාරුණික වන්න.",
'filename-prefix-blacklist'   => '#<!-- මෙම පේළිය මෙලෙසම පැවතීමට ඉඩදෙන්න --> <pre>
# වාග් රීතිය පහත පරිදිය:
#   * "#" අක්ෂරයෙහි සිට පේළි අග දක්වා සියල්ල පරිකථනයක් වේ
#   * හිස්-නොවන සෑම පේළියක්ම ඩිජිටල් කැමරාවලින් ස්වයංක්‍රීයව අනුයුක්ත කෙරෙන සාමාන්‍ය ගොනු නාමයන්හට උපසර්ගයක් වේ
CIMG # කැසියෝ
DSC_ # නිකොන්
DSCF # ෆූජි
DSCN # නිකොන්
DUW # සමහරක් ජංගම දුරකථන
IMG # සාමාන්‍ය
JD # ජෙනොප්ටික්
MGP # පෙන්ටැක්ස්
PICT # විවිධ.
 #</pre> <!-- මෙම පේළිය මෙලෙසම පැවතීමට ඉඩදෙන්න -->',
'upload-successful-msg'       => 'ඔබගේ උඩුගත කිරීම මෙහි ඇත: $1',
'upload-failure-subj'         => 'උඩුගත කිරීමේ ගැටළුව',
'upload-failure-msg'          => 'ඔබගේ උඩුගතකිරීම හා බැඳි ගැටළුවක් විය:

$1',

'upload-proto-error'        => 'සදොස් මූලලේඛය',
'upload-proto-error-text'   => 'දුරස්ථ උඩුගත කිරීම් සඳහා,  කලාප ලිපිනයන් (URLලයන්)  <code>http://</code> හෝ <code>ftp://</code> යන්නෙන් ආරම්භ විය යුතුයි.',
'upload-file-error'         => 'අභ්‍යන්තර දෝෂය',
'upload-file-error-text'    => 'සේවාදායකයෙහි තාවකාලික ගොනුවක් තැනීමට උත්සාහ දැරීමෙහිදී අභ්‍යන්තර දෝෂයක් හට ගැනිණි.
කරුණාකර [[Special:ListUsers/sysop|පරිපාලකවරයෙක්]] වෙත යොමුවන්න.',
'upload-misc-error'         => 'අඥාත උඩුගත කිරීම් දෝෂය',
'upload-misc-error-text'    => 'උඩුගත කිරීමේදී අඥාත දෝෂයක් සිදුවිය.
කලාප ලිපිනයෙහි  (URL) නීතික බව හා ප්‍රවේශ්‍ය බව සත්‍යාපනය කොට නැවත උත්සාහ කරන්න.
ගැටළුව තවදුරටත් පවතියි නම්, [[Special:ListUsers/sysop|පරිපාලකවරයෙකු]] අමතන්න.',
'upload-too-many-redirects' => 'අන්තර්ජාල ලිපිනයෙහි පමණට වඩා යළි-යොමුවීම් අඩංගු වෙයි',
'upload-unknown-size'       => 'නොදන්නා තරම',
'upload-http-error'         => 'HTTP දෝෂයක් හට ගැනිණි: $1',

# img_auth script messages
'img-auth-accessdenied' => 'ප්‍රවේශය තහනම් කර ඇත',
'img-auth-nopathinfo'   => 'PATH_INFO වැරදී ඇත.
මෙම තොරතුරු යැවීම සඳහා ඔබගේ සේවා දායකය පිහිටුවා නැත.
එය CGI-පාදක වූවක් විය හැකි අතර img_auth සඳහා සහය නොදක්වයි.
http://www.mediawiki.org/wiki/Manual:Image_Authorization බලන්න.',
'img-auth-notindir'     => 'ඉල්ලුම් කළ පෙත වින්‍යසගත උඩුගත කිරීම් නාමාවලියේ නැත.',
'img-auth-badtitle'     => '"$1" මඟින් වලංගු මාතෘකාවක් ගොඩනැගිය නොහැකිය.',
'img-auth-nologinnWL'   => 'ඔබ ඇතුල් වී නොමැති අතර "$1" සුදු ලැයිස්තුවේ නොමැත.',
'img-auth-nofile'       => '"$1" ගොනුව නොපවතී.',
'img-auth-isdir'        => 'ඔබ "$1" නාමාවලියට පිවිසීමට උත්සාහ කරයි.
අවසර ලබා දෙන්නේ ගොනු ප්‍රවේශය සඳහා පමණි.',
'img-auth-streaming'    => '"$1" ප්‍රවාහය වෙමින් පවතී.',
'img-auth-public'       => 'img_auth.php හි කාර්යය වන්නේ පෞද්ගලික විකියක් මඟින්  ගොනු ප්‍රතිදානය කිරිීමයි.
මෙම විකිය වින්‍යාසගත කොට ඇත්තේ පොදු විකියක් ලෙසය.
ප්‍රශස්ත ආරක්ෂාව සඳහා , img_auth.php අක්‍රීය කර ඇත.',
'img-auth-noread'       => '"$1"  කියවීම සඳහා පරිශීලකයාට ප්‍රවේශවීම් නොමැත.',

# HTTP errors
'http-invalid-url'      => 'අනීතික අන්තර්ජාල ලිපිනය: $1',
'http-invalid-scheme'   => '"$1" පර්ක්‍රමය සහිත අන්තර්ජාල ලිපිනයන් වෙත අනුබල දෙනු නොලැබේ',
'http-request-error'    => 'අඥාත දෝෂයක් හේතුවෙන් HTTP අයැදුම අසාර්ථක විය.',
'http-read-error'       => 'HTTP කියැවුම් දෝෂය.',
'http-timed-out'        => 'HTTP අයැදුම සඳහා වූ කාලය ඉක්මව ඇත.',
'http-curl-error'       => 'අන්තර්ජාල ලිපිනය පමුණුවාගෙන ඒමේ දෝෂය : $1',
'http-host-unreachable' => 'අන්තර්ජාල ලිපිනය වෙත සේන්දු විය නොහැකි විය',
'http-bad-status'       => 'HTTP ආයාචනයෙහිදී ගැටළුවක් පැන නැගුනි: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'කලාප ලිපිනය ( URL) වෙත සේන්දුවිය නොහැකි විය',
'upload-curl-error6-text'  => 'සපයා ඇති කලාප ලිපිනය (URL) වෙත සේන්දු විය නොහැකි විය.
කලාප ලිපිනය (URL) නිරවද්‍ය බවද අඩවිය ක්‍රියාත්මක බවද කරුණාකර පරික්ෂා කර බලන්න.',
'upload-curl-error28'      => 'උඩුගත කිරීමේ කාලය ඉක්මවා ගොස් ඇත',
'upload-curl-error28-text' => 'අඩවිය විසින් ප්‍රතිචාර දැක්වීමට ගත් කාලය අධිකය.
අඩවිය ක්‍රියාත්මක දැයි පරික්‍ෂාකර බලා, මඳ වේලාවක් රැඳී සිට නැවත උත්සාහ කරන්න.
කාර්ය බහුලත්වය අඩු වේලාවක උත්සාහ කිරීමට ඔබ හට සිදුවිය හැක.',

'license'            => 'බලය ලබා දීම:',
'license-header'     => 'අවසර දීම',
'nolicense'          => 'කිසිවක් තෝරාගෙන නැති',
'license-nopreview'  => '(පෙර-දසුන  නැරඹිය නොහැක)',
'upload_source_url'  => ' (නීතික, ප්‍රජාව විසින් ප්‍රවේශ්‍ය කලාප ලිපිනය (URL) කි)',
'upload_source_file' => ' (ඔබගේ පරිගණකයේ ගොනුවකි)',

# Special:ListFiles
'listfiles-summary'     => 'මෙම විශේෂ පිටුවෙහි දැක්වෙන්නේ සියළු උඩුගත කල ගොනුය.
අවසානයට උඩුගත කල ගොනු පෙරනිමියෙන් ලැයිස්තුවෙහි ඉහළින්ම දැක්වේ.
පේළි ශීර්ෂකය ක්ලික් කිරීමෙන් සුබෙදුම් පටිපාටිය වෙනස් කල හැක.',
'listfiles_search_for'  => 'මාධ්‍ය නාමය සඳහා ගවේෂණය කරන්න:',
'imgfile'               => 'ගොනුව',
'listfiles'             => 'ගොනු ලැයිස්තුව',
'listfiles_date'        => 'දිනය',
'listfiles_name'        => 'නම',
'listfiles_user'        => 'පරිශීලක',
'listfiles_size'        => 'විශාලත්වය',
'listfiles_description' => 'විස්තරය',
'listfiles_count'       => 'අනුවාදයන්',

# File description page
'file-anchor-link'          => 'ගොනුව',
'filehist'                  => 'ගොනු ඉතිහාසය',
'filehist-help'             => 'එම අවස්ථාවෙහිදී  ගොනුව පැවැති ආකාරය නැරඹීම ඔබ හට රිසි නම්  දිනය/වේලාව මත ක්ලික් කරන්න.',
'filehist-deleteall'        => 'සියල්ල මකන්න',
'filehist-deleteone'        => 'මකන්න',
'filehist-revert'           => 'ප්‍රතිවර්තනය',
'filehist-current'          => 'වත්මන්',
'filehist-datetime'         => 'දිනය/කාලය',
'filehist-thumb'            => 'සිඟිති-රූපය',
'filehist-thumbtext'        => ' $1වන විට අනුවාදය සඳහා සිඟිති-රූපය',
'filehist-nothumb'          => 'සිඟිති-රූපයක් නොමැත',
'filehist-user'             => 'පරිශීලක',
'filehist-dimensions'       => 'මාන',
'filehist-filesize'         => 'ගොනුවේ විශාලත්වය',
'filehist-comment'          => 'පරිකථනය',
'filehist-missing'          => 'ගොනුව සොයාගත නොහැක',
'imagelinks'                => 'ගොනු සබැඳියන්',
'linkstoimage'              => 'මෙම ගොනුවට  {{PLURAL:$1|ලිපිය බැ‍ඳෙයි|ලිපි $1 ක් බැඳෙති}}:',
'linkstoimage-more'         => 'මෙම ගොනුවට {{PLURAL:$1|පිටුවකට |පිටු  $1 කට}} වඩා වැඩි ගණනක් සබැ‍ඳේ.
මෙම පිටුවට පමණක් අදාළ වන {{PLURAL:$1|පළමු පිටු සබැඳිය|පළමු පිටු සබැඳියන් $1 }} මෙහි පහත ලැයිස්තුවෙහි දැක්වේ.
 [[Special:WhatLinksHere/$2|සම්පූර්ණ ලැයිස්තුව]]ක්ද තිබේ.',
'nolinkstoimage'            => 'මෙම ගොනුවට සබැඳෙන පිටු කිසිවක් නොමැත.',
'morelinkstoimage'          => 'මෙම ගොනුව සඳහා [[Special:WhatLinksHere/$1|තවත් සබැඳි]] තිබේදැයි නරඹන්න.',
'redirectstofile'           => 'පහත {{PLURAL:$1|ගොනුව මෙම ගොනුව කරා යළි-යොමුවේ|ගොනු $1 මෙම ගොනුව කරා යළි-යොමුවෙති}} :',
'duplicatesoffile'          => 'පහත {{PLURAL:$1|ගොනුව |ගොනු $1 }} මෙම ගොනුවේ {{PLURAL:$1|අනුපිටපත |අනුපිටපත් }} වේ ([[Special:FileDuplicateSearch/$2|වැඩි විස්තර සඳහා]]):',
'sharedupload'              => 'මෙම ගොනුව $1 වෙතින් වන අතර අනෙකුත් ව්‍යාපෘතින් සඳහාද භාවිතා කල හැකි වෙයි.',
'sharedupload-desc-there'   => 'මෙම ගොනුව  $1 වෙතින් වන අතර අනෙකුත් ව්‍යාපෘතීන් විසින්ද භාවිතා කල හැක.
වැඩි විස්තර සඳහා කරුණාකර [$2 ගොනු විස්තර පිටුව] බලන්න.',
'sharedupload-desc-here'    => 'මෙම ගොනුව  $1 වෙතින් වන අතර අනෙකුත් ව්‍යාපෘතීන් විසින්ද භාවිතා කල හැක.
එහි [$2 ගොනු විස්තර පිටුව] තුල අඩංගු විස්තර මෙහි පහත දැක්වෙයි.',
'filepage-nofile'           => 'මෙම නම සහිත ගොනුවක් නොපවතියි.',
'filepage-nofile-link'      => 'මෙම නම සහිත ගොනුවක් නොපවතින නමුදු, ඔබ විසින් [$1 එය උඩුගතතිරීම] සිදුකල හැක.',
'uploadnewversion-linktext' => 'මෙම ගොනුවෙහි නව අනුවාදයක් උඩුගත කරන්න',
'shared-repo-from'          => '$1 වෙතින්',
'shared-repo'               => 'හවුල් සුරක්ෂිතාගාරයකි',

# File reversion
'filerevert'                => '$1 ප්‍රතිවර්තනය කරන්න',
'filerevert-backlink'       => '← $1',
'filerevert-legend'         => 'ගොනුව ප්‍රතිවර්තනය කරන්න',
'filerevert-intro'          => "ඔබ විසින්  '''[[Media:$1|$1]]''' ප්‍රතිවර්තනය කරමින් පවතින්නේ  [ $2 දින, $3 වේලාවේ පැවැති $4 අනුවාදයටයි ].",
'filerevert-comment'        => 'හේතුව:',
'filerevert-defaultcomment' => '$2 දින, $1 වේලාවෙහි වූ අනුවාදය වෙත ප්‍රතිවර්තනය කෙරිණි',
'filerevert-submit'         => 'ප්‍රතිවර්තනය',
'filerevert-success'        => "'''[[Media:$1|$1]]''',   [$3 දින, $2 වේලාවෙහි වූ $4 අනුවාදය ] වෙත ප්‍රතිවර්තනය කෙරිණි .",
'filerevert-badversion'     => 'සපයා ඇති වේලාමුද්‍රාව හා සමග මෙම ගොනුව සැලකූ කල, පූර්ව ස්ථානීය අනුවාද නොමැති බව පෙනේ.',

# File deletion
'filedelete'                  => '$1 මකන්න',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'ගොනුව මකන්න',
'filedelete-intro'            => "ඔබ විසින්  '''[[Media:$1|$1]]'''ගොනුව, එහි සමස්ත ඉතිහාසය සමගින් මකා දැමීමට ආසන්නයේ පවතියි.",
'filedelete-intro-old'        => "ඔබ විසින් මකා දමමින් පවතින්නේ [$4 $3, $2] වන විට '''[[Media:$1|$1]]''' හි අනුවාදයයි.",
'filedelete-comment'          => 'හේතුව:',
'filedelete-submit'           => 'මකා දමන්න',
'filedelete-success'          => "'''$1''' මකා දමන ලදි.",
'filedelete-success-old'      => "$3, $2  වන විට '''[[Media:$1|$1]]'''  හි අනුවාදය මකා දමා ඇත.",
'filedelete-nofile'           => "'''$1''' නොපවතියි.",
'filedelete-nofile-old'       => "There is no archived version of '''$1''' with the නියමකරඇති  attributes.",
'filedelete-otherreason'      => 'අනෙකුත්/අමතර හේතුව:',
'filedelete-reason-otherlist' => 'අනෙකුත් හේතුව',
'filedelete-reason-dropdown'  => '*සාමාන්‍ය මකාදැමීම් හේතූන්
** හිමිකම් උල්ලංඝනය
** අනුපිටපත් කල ගොනුව',
'filedelete-edit-reasonlist'  => 'මකා දැමීමට හේතූන් සංස්කරණය කරන්න',
'filedelete-maintenance'      => 'නඩත්තුව අතරතුර ගොනු මැකීම හා ප්‍රතිසංස්කරණය තාවකාලිකව අක්‍රීය වේ.',

# MIME search
'mimesearch'         => 'MIME ගවේෂණය',
'mimesearch-summary' => 'ගොනු, එහි MIME-වර්ගය අනුව පෙරහනය කිරීමට මෙම පිටුව අවකාශ සලසයි.
ප්‍රදානය: අන්කර්ගතවර්ගය/උපවර්ගය, නිද. <tt>රූපය/jpeg</tt>.',
'mimetype'           => 'MIME වර්ගය:',
'download'           => 'භාගත කිරීම',

# Unwatched pages
'unwatchedpages' => 'මුර-නොකෙරෙන පිටු',

# List redirects
'listredirects' => 'යළි-යොමුවීම් ලැයිස්තුව',

# Unused templates
'unusedtemplates'     => 'භාවිතා නොවූ සැකිලි',
'unusedtemplatestext' => 'වෙනත් පිටුවක අඩංගු කොට නොමැති, සැකිලි නාමඅවකාශයෙහි සියළු පිටු මෙම පිටුවෙහි ලැයිස්තුගත කොට ඇත.
ඒවා මකාදැමීමට පෙර, සැකිලි සඳහා වෙනත් සබැඳි තිබේදැයි පරික්ෂා කර බැලීමට සුපරික්‍ෂාකාරී වන්න.',
'unusedtemplateswlh'  => 'අනෙකුත් සබැඳියන්',

# Random page
'randompage'         => 'අහඹු පිටුව',
'randompage-nopages' => 'පහත {{PLURAL:$2|නාමඅවකාශය|නාමඅවකාශ}}:$1 හි කිසිදු පිටුවක් නොමැත.',

# Random redirect
'randomredirect'         => 'අහුඹු යළි-යොමුකිරීම',
'randomredirect-nopages' => '"$1" නාම-අවකාශයෙහි යළි-යොමුවීම් නොමැත.',

# Statistics
'statistics'                   => 'සංඛ්‍යාන දත්ත',
'statistics-header-pages'      => 'පිටුවල සංඛ්‍යාන දත්ත',
'statistics-header-edits'      => 'සංස්කරණ වල සංඛ්‍යාන දත්ත',
'statistics-header-views'      => 'නැරඹුම් වල සංඛ්‍යාන දත්ත',
'statistics-header-users'      => 'පරිශීලකයන් පිළිබඳ සංඛ්‍යාන දත්ත',
'statistics-header-hooks'      => 'අනෙක් සංඛ්‍යා ලේඛන',
'statistics-articles'          => 'අන්තර්ගත  පිටු',
'statistics-pages'             => 'පිටු',
'statistics-pages-desc'        => 'සාකච්ඡා පිටු, යළි-යොමුවීම් ආදිය ඇතුළු විකියෙහි සියළු පිටු.',
'statistics-files'             => 'උඩුගතකරන ලද ගොනු',
'statistics-edits'             => '{{SITENAME}} පිහිටුවීමෙන් අනතුරුව සිදුවූ පිටු සංස්කරණයන්',
'statistics-edits-average'     => 'එක් පිටුවකට සංස්කරණයන්හි  මධ්‍යක අගය',
'statistics-views-total'       => 'නැරඹුම් එකතුව',
'statistics-views-peredit'     => 'එක් සංස්කරණයකට නැරඹුම් ගණන',
'statistics-users'             => 'ලේඛනගත  [[Special:ListUsers|පරිශීලකයෝ]]',
'statistics-users-active'      => 'ක්‍රියාශීලි පරිශීලකයන්',
'statistics-users-active-desc' => 'පසුගිය {{PLURAL:$1|දිනය|දින $1}} තුලදී කිසියම් ක්‍රියාවක් සිදු කල පරිශීලකයන්',
'statistics-mostpopular'       => 'බෙහෙවින් නරඹනු ලබන පිටු',

'disambiguations'      => 'වක්‍රෝත්තිහරණ පිටු',
'disambiguationspage'  => 'Template:වක්‍රෝත්තිහරණ',
'disambiguations-text' => "ඉදිරි පිටු '''වක්‍රෝත්තිහරණ පිටුව'''කට සබැ‍ඳේ.
ඒවා ඒ වෙනුවට අනුරූප මාතෘකාවට සබැඳිය යුතුය.<br />
යම් පිටුවක් වක්‍රෝත්තිහරණ පිටුවක් ලෙස සලකනුයේ එය [[MediaWiki:Disambiguationspage]] වෙතින් සබැඳුනු සැකිල්ලක් භාවිතා කරන්නේ නම්ය",

'doubleredirects'            => 'ද්විත්ව යළි-යොමුකිරීම්',
'doubleredirectstext'        => 'අනෙකුත් යළි-යොමුවීම් පිටුවලට යළි-යොමුවන පිටුවල ලැයිස්තුවක් මෙම පිටුවේ දැක්වේ.
එක් එක් පේළියක අඩංගු වන්නේ පළමු හා දෙවන යළි-යොමුවීම් වලට සබැඳි හා ඒ සමග පළමු යළි-යොමුව එල්ල වන්නාවූ, සාමාන්‍යයෙන් "සත්‍ය" ඉලක්ක පිටුව වන, දෙවන යළි-යොමුවේ ඉලක්කයයි.<del>කපා හැරි</del> නිවේශිතයන් පිලිබඳ ගැටළු විසඳා ඇත.',
'double-redirect-fixed-move' => '[[$1]] ගෙන ගොස් ඇත, එය දැන් [[$2]] වෙතට යළි-යොමුවකි',
'double-redirect-fixer'      => 'යළි-යොමුවීම් උපස්ථායක',

'brokenredirects'        => 'භින්න යළි-යොමුවීම්',
'brokenredirectstext'    => 'මෙහි පහත දැක්වෙන යළි-යොමුවීම් නොපවතින පිටු වලට සබැඳේ:',
'brokenredirects-edit'   => 'සංස්කරණය',
'brokenredirects-delete' => 'මකා දැමීම',

'withoutinterwiki'         => 'භාෂා සබැඳි විරහිත පිටු',
'withoutinterwiki-summary' => 'මෙහි පහත දැක්වෙන පිටු අනෙකුත් භාෂා අනුවාදයන් වෙත නොබැඳේ.',
'withoutinterwiki-legend'  => 'උපසර්ගය',
'withoutinterwiki-submit'  => 'පෙන්වන්න',

'fewestrevisions' => 'ස්වල්පතම සංශෝධන සහිත පිටු',

# Miscellaneous special pages
'nbytes'                  => '{{PLURAL:$1|එක් බයිටයකි|බයිට් $1 කි}}',
'ncategories'             => '{{PLURAL:$1|එක් ප්‍රවර්ගයකි|ප්‍රවර්ගයන් $1 කි}}',
'nlinks'                  => '{{PLURAL:$1|එක් සබැඳියකි|සබැඳියන් $1 කි}}',
'nmembers'                => '{{PLURAL:$1|එක් සාමාජිකයෙකි|සාමාජීකයන් $1 කි}}',
'nrevisions'              => '{{PLURAL:$1|එක් සංශෝධනයකි|සංශෝධන $1 කි }}',
'nviews'                  => ' {{PLURAL:$1|නැරඹුම් එකකි|නැරඹුම් $1 කි}}',
'specialpage-empty'       => 'මෙම වාර්තාවට ප්‍රතිඵල කිසිවක් නොමැත.',
'lonelypages'             => 'හුදෙකලා පිටු',
'lonelypagestext'         => 'පහත පිටු,  {{SITENAME}} හි අනෙකුත් පිටුවලින් සබැඳි නොමැත.',
'uncategorizedpages'      => 'ප්‍රවර්ගීකරණය නොවූ පිටු',
'uncategorizedcategories' => 'ප්‍රවර්ගීකරණය නොවූ ප්‍රවර්ග',
'uncategorizedimages'     => 'ප්‍රවර්ගීකරණය නොවූ ගොනු',
'uncategorizedtemplates'  => 'ප්‍රවර්ගීකරණය නොවූ සැකිලි',
'unusedcategories'        => 'භාවිතා නොවූ ප්‍රවර්ග',
'unusedimages'            => 'භාවිතා නොවූ ගොනු',
'popularpages'            => 'ජනප්‍රිය පිටු',
'wantedcategories'        => 'අවශ්‍ය ප්‍රවර්ග',
'wantedpages'             => 'අවශ්‍ය පිටු',
'wantedpages-badtitle'    => 'ප්‍රතිඵල ගොන්නේ අනීතික ශීර්ෂය: $1',
'wantedfiles'             => 'අවශ්‍ය ගොනු',
'wantedtemplates'         => 'අවශ්‍ය සැකිලි',
'mostlinked'              => 'පිටු වලට බෙහෙවින්ම සබැඳි',
'mostlinkedcategories'    => 'ප්‍රවර්ගයන්ට බෙහෙවින්ම සබැඳි',
'mostlinkedtemplates'     => 'සැකිලි වලට බෙහෙවින්ම සබැඳි',
'mostcategories'          => 'ප්‍රවර්ගයන් බොහෝමයක් සහිත පිටු',
'mostimages'              => 'ගොනු වලට බෙහෙවින්ම සබැඳි',
'mostrevisions'           => 'වඩාත්ම සංශෝධන සහිත පිටු',
'prefixindex'             => 'උපසර්ගය සහිත සියළු පිටු',
'shortpages'              => 'කෙටි පිටු',
'longpages'               => 'දිගු පිටු',
'deadendpages'            => 'ආවෘත-අගැති පිටු',
'deadendpagestext'        => 'පහත පිටු, {{SITENAME}} හි අනෙකුත් පිටු වෙත සබැඳී නොමැත.',
'protectedpages'          => 'ආරක්ෂිත පිටු',
'protectedpages-indef'    => 'අනිශ්චිත ආරක්ෂණයන් පමණයි',
'protectedpages-cascade'  => 'තීරු-දර්ශන ආරක්ෂණයන් පමණයි',
'protectedpagestext'      => 'ඉදිරියේ දැක්වෙන පිටු ගෙනයාම හෝ සංස්කරණය කිරීම හෝ වාරණය කොට ඇත',
'protectedpagesempty'     => 'මෙම පරාමිතීන් හා සමග සැලකූ කල,  කිසිදු පිටුවක් දැනට ආරක්ෂිත වී නොමැත.',
'protectedtitles'         => 'ආරක්‍ෂිත ශීර්ෂයන්',
'protectedtitlestext'     => 'පහත දැක්වෙන ශීර්ෂයන් තැනිය නොහැකි වන පරිදි ආරක්‍ෂණය කොට ඇත',
'protectedtitlesempty'    => 'මෙම පරාමිතීන් හා සමග සැලකූ කල, කිසිදු ශීර්ෂයක් දැනට ආරක්ෂිත වී නොමැත.',
'listusers'               => 'පරිශීලක ලැයිස්තුව',
'listusers-editsonly'     => 'සංස්කරණයන් සිදුකර ඇති පරිශීලකයන් පමණක් පෙන්වන්න',
'listusers-creationsort'  => 'තැනූ දින අනුව සුබෙදන්න',
'usereditcount'           => ' {{PLURAL:$1|සංස්කරණ එකකි|සංස්කරණ $1 කි}}',
'usercreated'             => '$1 දින $2 වේලාවේදී තනන ලදි',
'newpages'                => 'අළුත් පිටු',
'newpages-username'       => 'පරිශීලක-නාමය:',
'ancientpages'            => 'පුරාණතම පිටු',
'move'                    => 'ගෙනයන්න',
'movethispage'            => 'මෙම පිටුව ගෙන යන්න',
'unusedimagestext'        => 'පහත ගොනු පවතින නමුත් ඒවා කිසිදු පිටුවකට කාවද්දා නොමැත.
සෘජු කලාප ලිපින (URL) සමගින් අනෙකුත් වෙබ් අඩවි ගොනුවකට සබැඳිය හැකි බවද, එබැවින්ම සක්‍රීය භාවිතයෙහි පැවතුනද මෙහිදී ලැයිස්තුගතකිරීමට ලක් විය හැකි බවද  කාරුණිකව සටහන් කර ගන්න.',
'unusedcategoriestext'    => 'වෙනයම් කිසිම පිටුවක් හෝ ප්‍රවර්ගයක් හෝ එහි ප්‍රයෝජනය නොගත්තද, පහත ප්‍රවර්ග පිටු පවතියි.',
'notargettitle'           => 'ඉලක්කයක් නොමැත',
'notargettext'            => 'මෙම ශ්‍රිතය ක්‍රියාත්මකකිරීමට  යොදවනු වස් අන්ත පිටුවක් ඔබ විසින් හුවා දක්වා නොමැත.',
'nopagetitle'             => 'එවැනි ඉලක්කගත පිටුවක් නොමැත',
'nopagetext'              => 'ඔබ විසින් සඳහන් කර ඇති ඉලක්කගත පිටුව නොපවතියි.',
'pager-newer-n'           => '{{PLURAL:$1|නවීන 1|නවීන $1}}',
'pager-older-n'           => '{{PLURAL:$1|පැරණි 1|පැරණි $1}}',
'suppress'                => 'ප්‍රමාද දෝෂය',

# Book sources
'booksources'               => 'ග්‍රන්ථ මූලාශ්‍ර',
'booksources-search-legend' => 'ග්‍රන්ථ මූලාශ්‍ර සඳහා ගවේෂණය කරන්න',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'යන්න',
'booksources-text'          => 'පහත දැක්වෙන්නේ අළුත් හා පරණ පොත් විකුණන අනෙකුත් අඩවි වලට සබැඳි ලැයිස්තුවක් වන අතර,  ඔබ විසින් සොයන පොත් පිළිබඳ වැඩිමනත් විස්තර ඒවා‍යේ අඩංගු වීමට ඉඩ ඇත:',
'booksources-invalid-isbn'  => 'සපයන ලද ISBN අංකය නීතික බවක් නොපෙන්වයි; මුල් මුලාශ්‍රය වෙතින් පිටපත් කිරීමේදී සිදුවූ දෝෂ සඳහා පරික්ෂා කරන්න.',

# Special:Log
'specialloguserlabel'  => 'පරිශීලකයා:',
'speciallogtitlelabel' => 'ශීර්ෂය:',
'log'                  => 'සටහන්',
'all-logs-page'        => 'සියළු පොදු ලඝු-සටහන්',
'alllogstext'          => '{{SITENAME}} හි සියළු සුගම ලඝු-සටහන් හි සංයුක්ත සංදර්ශකය.
ලඝු-සටහන් වර්ගය, පරිශීලක නාමය හෝ  බලපෑම එල්ල වූ පිටුව තෝරාගැනුමෙන් ඔබහට නැරඹුමෙහි පුළුල අඩු කර ගත හැක.',
'logempty'             => 'ලඝු-සටහනෙහි ගැලපෙන අයිතමයන් කිසිවක් නොමැත.',
'log-title-wildcard'   => 'මෙම පෙළෙන් ඇරඹෙන ශීර්ෂ සඳහා ගවේෂණය කරන්න',

# Special:AllPages
'allpages'          => 'සියළු පිටු',
'alphaindexline'    => '$1 සි‍ට $2 වෙත',
'nextpage'          => 'ඊළඟ පිටුව ($1)',
'prevpage'          => 'පූර්ව පිටුව ($1)',
'allpagesfrom'      => 'මෙහිදී ඇරඹෙන පිටු පෙන්වන්න:',
'allpagesto'        => 'මෙහිදී කෙළවර වන පිටු පෙන්වන්න:',
'allarticles'       => 'සියළු පිටු',
'allinnamespace'    => 'සියළු පිටු ($1 නාමඅවකාශය)',
'allnotinnamespace' => 'සියළු පිටු ($1 නාමඅවකාශයෙහි නොමැති)',
'allpagesprev'      => 'පූර්ව',
'allpagesnext'      => 'ඊලඟ',
'allpagessubmit'    => 'යන්න',
'allpagesprefix'    => 'මෙම උපසර්ගය සහිත පිටු පෙන්වන්න:',
'allpagesbadtitle'  => 'සපයා ඇති පිටු ශීර්ෂය අනීතික විය නැතහොත් එහි අන්තර්-භාෂා හෝ අන්තර් විකී උපසර්ගයක් අඩංගු විය.
ශීර්ෂයන්හි අඩංගු විය නොහැකි අක්ෂර එකක් හෝ කිහිපයක් හෝ එහි අඩංගු වී තිබිය හැක.',
'allpages-bad-ns'   => '{{SITENAME}} හි  "$1" නාමඅවකාශය නොමැත.',

# Special:Categories
'categories'                    => 'ප්‍රවර්ග',
'categoriespagetext'            => 'පහත {{PLURAL:$1|ප්‍රවර්ගයෙහි අන්තර්ගතය |ප්‍රවර්ගයන්හි අන්තර්ගතයන්}} වනුයේ පිටු හෝ මාධ්‍යයන්ය.
[[Special:UnusedCategories|භාවිතනොවූ  ප්‍රවර්ගයන්]] මෙහි පෙන්වා දක්වා නොමැත.
 [[Special:WantedCategories|අවශ්‍ය ප්‍රවර්ගයන්]]ද බලන්න.',
'categoriesfrom'                => 'මෙහිදී ඇරඹෙන ප්‍රවර්ග පෙන්වන්න:',
'special-categories-sort-count' => 'ගණණය පරිදි  සුබෙදුම',
'special-categories-sort-abc'   => 'අකාරාදියේ පිළිවෙලට සුබෙදන්න',

# Special:DeletedContributions
'deletedcontributions'             => 'මකාදැමූ පරිශීලක දායකත්වයන්',
'deletedcontributions-title'       => 'මකාදැමූ පරිශීලක දායකත්වයන්',
'sp-deletedcontributions-contribs' => 'දායකත්වයන්',

# Special:LinkSearch
'linksearch'       => 'බාහිර සබැඳි',
'linksearch-pat'   => 'ගවේෂණ රටාව:',
'linksearch-ns'    => 'නාම-අවකාශය:',
'linksearch-ok'    => 'ගවේෂණය',
'linksearch-text'  => '"*.wikipedia.org" වැනි ආදේශක භාවිතා කල හැක.<br />
පිටුවහල් වෙන මූලලේඛයන්: <tt>$1</tt>',
'linksearch-line'  => '$2 වෙතින් $1 සබැඳිණි',
'linksearch-error' => 'ආදේශක  පෙනීසිටිය හැක්කේ සත්කාරකනාමය ආරම්භයෙහි පමණයි.',

# Special:ListUsers
'listusersfrom'      => '‍මෙම අකුරෙන් පටන්ගෙන පරිශීලකයන් ප්‍රදර්ශනය කරන්න:',
'listusers-submit'   => 'පෙන්වන්න',
'listusers-noresult' => 'පරිශීලකයෙකු සොයාගත නොහැකි විය.',
'listusers-blocked'  => '(වාරණය කොට)',

# Special:ActiveUsers
'activeusers'            => 'සක්‍රීය පරිශීලකයන් ලැයිස්තුව',
'activeusers-intro'      => 'මෙය පසුගිය $1 {{PLURAL:$1|දිනය|දින}}තුළ යම් ක්‍රියාකාරකමක් කළ පරිශීලකයන්ගේ ලැයිස්තුවකි.',
'activeusers-count'      => '{{PLURAL:$1|එක් සංස්කරණයක්|සංස්කරණ $1 ක්}} අවසන් {{PLURAL:$3|දිනය|දින $3}} තුළ',
'activeusers-from'       => 'මෙයින් ඇරඹෙන පරිශීලකයන් පෙන්වන්න:',
'activeusers-hidebots'   => ' bots සඟවන්න',
'activeusers-hidesysops' => 'පරිපාලකයින් සඟවන්න',
'activeusers-noresult'   => 'කිසිදු පරිශීලකයෙකු හමුනොවිණි.',

# Special:Log/newusers
'newuserlogpage'              => 'පරිශීලකයන් තැනීමේ සටහන',
'newuserlogpagetext'          => 'මෙය පරිශිලකයන් තැනීම පිළිබඳ ලඝු-සටහනකි.',
'newuserlog-byemail'          => 'විද්‍යුත්-තැපෑලෙන් මුර-පදය යවන ලදි',
'newuserlog-create-entry'     => 'නව පරිශීලක',
'newuserlog-create2-entry'    => '$1 නව ගිණුම තනන ලදි',
'newuserlog-autocreate-entry' => 'ගිණුම ස්වයංක්‍රීයව තනන ලදි',

# Special:ListGroupRights
'listgrouprights'                      => 'පරිශීලක කාණ්ඩ හිමිකම්',
'listgrouprights-summary'              => 'මෙම විකියේ අර්ථදක්වා ඇති පරිශීලක කාණ්ඩ ලැයිස්තුවක් ඔවුනට අදාළ ප්‍රවේශ හිමිකම්ද සමගින් මෙහි පහත ලැයිස්තුගත කොට ඇත.
පුද්ගලික හිමිකම් පිළිබඳ  [[{{MediaWiki:Listgrouprights-helppage}}|වැඩිමනත් තොරතුරු]] පැවතිය හැක.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">හිමිකම ප්‍රදානය කෙරිණි </span>
* <span class="listgrouprights-revoked">හිමිකම අහෝසි කෙරිණි </span>',
'listgrouprights-group'                => 'කාණ්ඩය',
'listgrouprights-rights'               => 'හිමිකම්',
'listgrouprights-helppage'             => 'Help:කාණ්ඩ හිමිකම්',
'listgrouprights-members'              => '(පරිශිලකයන් ලැයිස්තුව)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|කණ්ඩායම|කණ්ඩායම්}} එක් කල හැක: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|කණ්ඩායම|කණ්ඩායම්}} ඉවත් කල හැක: $1',
'listgrouprights-addgroup-all'         => 'සියළු කණ්ඩායම් එක් කල හැක',
'listgrouprights-removegroup-all'      => 'සියළු කණ්ඩායම් ‍ඉවත් කල හැක',
'listgrouprights-addgroup-self'        => '{{PLURAL:$2|කාණ්ඩය|කාණඩයන්}} ස්වීය ගිණුමට එක්කරන්න: $1',
'listgrouprights-removegroup-self'     => '{{PLURAL:$2|කාණ්ඩය|කාණ්ඩයන්}} ස්වීය ගිණුමෙන් ඉවත් කරන්න: $1',
'listgrouprights-addgroup-self-all'    => 'සි‍යළු කාණ්ඩයන් ස්වීය ගිණුමට එක්කරන්න',
'listgrouprights-removegroup-self-all' => 'සියළු කාණ්ඩයන් ස්වීය ගිණුමෙන් ඉවත් කරන්න',

# E-mail user
'mailnologin'          => 'යායුතු ලිපිනය නොමැත',
'mailnologintext'      => 'අනෙකුත් පරිශීලකයන්හට  විද්‍යුත්-තැපැල් යැවුමට පෙරාතුව, ඔබ [[Special:UserLogin|ප්‍රවිෂ්ට වී]], ඔබගේ  [[Special:Preferences|අභිරුචියන්හි]]  නීතික විද්‍යුත්-තැපැල් ලිපිනයක් සඳහන් කර තිබිය යුතුය.',
'emailuser'            => 'මෙම පරිශීලකයාහට විද්‍යුත්-තැපෑලක් යවන්න',
'emailpage'            => ' පරිශීලකට විද්‍යුත්-තැපැලක් යවන්න',
'emailpagetext'        => 'මෙම පරිශීලකයා හට විද්‍යුත්-තැපෑල් පණිවුඩයක් යැවීම සඳහා මෙම ආකෘති පත්‍රය භාවිතා කිරීමට ඔබ හට හැක.
ලබන්නා විසින් සෘජු ලෙස ඔබ හට පිළිතුරු එවනු හැකි වන පරිදි, ඔබ විසින් [[Special:Preferences|ඔබගේ පරිශීලක අභිරුචියන්]] හි ඇතුළත් කල විද්‍යුත්-තැපැල් ලිපිනය,  විද්‍යුත්-තැපෑලෙහි "වෙතින්" ලිපිනයෙහි පෙන්නුම් කරනු ඇත.',
'usermailererror'      => 'තැපැල් ආරම්මණය පෙරළා දැක්වූ දෝෂය:',
'defemailsubject'      => '{{SITENAME}} විද්‍යුත්-තැපෑල',
'usermaildisabled'     => 'ඔබගේ විද්‍යුත්-තැපෑල අක්‍රීය කොට ඇත',
'usermaildisabledtext' => 'මෙම විකියෙහි අනෙකුත් පරිශීලකයන් හට විද්‍යුත්-ගැපැල් යැවීමට ඔබ හට නොහැක',
'noemailtitle'         => 'විද්‍යුත්-තැපැල් ලිපිනයක් නොමැත',
'noemailtext'          => 'මෙම පරිශීලකයා නීතික විද්‍යුත්-තැපැල් ලිපිනයක් සඳහන් කර නැත.',
'nowikiemailtitle'     => 'විද්‍යුත්-තැපෑලයන් කිසිවක් සඳහා අවසර නොමැත',
'nowikiemailtext'      => 'අනෙකුත් පරිශීලකයන්ගෙන් විද්‍යුත්-තැපැල් ලැබ නොගැනුම මෙම පරිශිලකයා විසින් තෝරාගෙන ඇත.',
'email-legend'         => 'වෙනත් {{SITENAME}} පරිශීලකයෙකුට විද්‍යුත්-තැපෑලක් යවන්න',
'emailfrom'            => 'වෙතින්:',
'emailto'              => 'වෙතට:',
'emailsubject'         => 'විෂයය:',
'emailmessage'         => 'පණිවුඩය:',
'emailsend'            => 'යවන්න',
'emailccme'            => 'මගේ පණිවුඩයෙහි පිටපතක් මා වෙත විද්‍යුත්-තැපැල් කරන්න.',
'emailccsubject'       => '$1: $2 වෙත ඔබගේ පණිවුඩය පිටපත් කරන්න',
'emailsent'            => 'විද්‍යුත්-තැපෑල යවන ලදි',
'emailsenttext'        => 'ඔබගේ  විද්‍යුත්-තැපැල්  පණිවුඩය යවා ඇත.',
'emailuserfooter'      => '{{SITENAME}} හි  " පරිශීලකට විද්‍යුත්-තැපැලක් යවන්න" ශ්‍රිතය අනුසාරයෙන් $1 විසින්  $2  වෙත  විද්‍යුත්-තැපෑලක් යවන ලදි.',

# User Messenger
'usermessage-summary' => 'පද්ධති පණිවුඩයක් තබා යමි.',
'usermessage-editor'  => 'පද්ධති පණිවුඩ කරු',

# Watchlist
'watchlist'            => 'මගේ මුර-ලැයිස්තුව',
'mywatchlist'          => 'මගේ මුර ලැයිස්තුව',
'watchlistfor'         => "('''$1''' සඳහා)",
'nowatchlist'          => 'ඔබගේ මුර-ලැයිස්තුවේ කිසිදු අයිතමයක් නොමැත.',
'watchlistanontext'    => 'ඔබගේ මුර-ලැයිස්තුවෙහි අයිතම නැරඹීමට හෝ සංස්කරණය කිරීමට හෝ කරුණාකර $1 සපුරන්න.',
'watchnologin'         => 'ප්‍රවිෂ්ට වී නොමැත',
'watchnologintext'     => 'ඔබගේ මුරලැයිස්තුව විකරණය කිරීමට පෙරාතුව ඔබ [[Special:UserLogin|ප්‍රවිෂ්ට වී]] සිටිය යුතුය.',
'addedwatch'           => 'මුර-ලැයිස්තුවට එක් කරන ලදි',
'addedwatchtext'       => "\"[[:\$1]]\" පිටුව ඔබගේ [[Special:Watchlist|මුර-ලැයිස්තුවට]] එක් කොට ඇත.
මෙම පිටුවට සහ එයට අදාළ සාකච්ඡා පිටුවට ඉදිරියෙහිදී සිදු කෙරෙන වෙනස් කම් මෙහි ලේඛනගත වන අතර, ප්‍රභේදනය කර ගැනීමෙහි පහසුව තකා,  [[Special:RecentChanges|මෑත වෙනස්වීම් ලැයිස්තුව]]  තුල මෙම පිටුව  '''තදකුරු''' වලින් දක්වනු ඇත.",
'removedwatch'         => 'මුර-ලැයිස්තුවෙන් ඉවත් කරන ලදි',
'removedwatchtext'     => 'මෙම "[[:$1]]"  පිටුව  [[Special:Watchlist|ඔබගේ  මුර-ලැයිස්තුවෙන්]] ඉවත් කරන ලදි.',
'watch'                => 'මුර කරන්න',
'watchthispage'        => 'මෙම පිටුව මුර කරන්න',
'unwatch'              => 'මුර නොකරන්න',
'unwatchthispage'      => 'මුර-කිරීම නවතින්න',
'notanarticle'         => 'අන්තර්ගත පිටුවක් නොවේ',
'notvisiblerev'        => 'සංශෝධනය මකාදමා ඇත',
'watchnochange'        => 'ඔබ විසින් මුරකෙරෙන කිසිදු අයිතමයක් දක්වා ඇති කාල සීමාවෙහිදී  සංස්කරණයට භාජනය වී නොමැත.',
'watchlist-details'    => 'සාකච්ඡා පිටු නොගිණුනු කල, ඔබගේ මුර-ලැයිස්තුවෙහි {{PLURAL:$1|එක් පිටුවක්|පිටු $1 ක්}} ඇත.',
'wlheader-enotif'      => '* විද්‍යුත්-තැපැල් දැනුම්දීම සක්‍රීය කෙරිණි.',
'wlheader-showupdated' => "* ඔබ විසින් ඒවාට අවසන් වරට පිවිසුනු පසුව වෙනස්කෙරුනු පිටු  '''තදකුරු''' වලින් පෙන්වා ඇත",
'watchmethod-recent'   => 'මුර-කෙරෙන පිටු සඳහා මෑත සංස්කරණයන් පරික්‍ෂා කරමින්',
'watchmethod-list'     => 'මෑත සංස්කරණයන් සඳහා මුර-කෙරෙන පිටු පරික්‍ෂා කරමින්',
'watchlistcontains'    => 'ඔබගේ මුර-ලැයිස්තුවෙහි  {{PLURAL:$1|එක් පිටුවක්|පිටු $1 ක්}} අඩංගුය.',
'iteminvalidname'      => "'$1' අයිතමය පිළිබඳ ගැටළුවක් ඇත, අනීතික නමකි...",
'wlnote'               => "පහත දැක්වෙන්නේ, අවසන් {{PLURAL:$2|පැය|පැය '''$2''' }} තුලදී සිදු කෙරී ඇති {{PLURAL:$1|අවසන් වෙනස්වීම්යි |අවසන් වෙනස්වීම්  '''$1''' යි}}.",
'wlshowlast'           => 'අවසන් පැය  $1 දින  $2  $3 පෙන්වන්න',
'watchlist-options'    => 'මුර-ලැයිස්තු විකල්ප',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'මුර කරමින්...',
'unwatching' => 'මුර නොකරමින්...',

'enotif_mailer'                => '{{SITENAME}}හි නිවේදන යවන්නා',
'enotif_reset'                 => 'පිවිසුනු සියළු පිටු සලකුණු කරන්න',
'enotif_newpagetext'           => 'මෙය නව පිටුවකි.',
'enotif_impersonal_salutation' => '{{SITENAME}} පරිශීලක',
'changed'                      => 'වෙනස්කරන ලදි',
'created'                      => 'තනන ලදි',
'enotif_subject'               => '{{SITENAME}}හි  $PAGETITLE යන පිටුව  $PAGEEDITOR විසින්  $CHANGEDORCREATED කෙරිණි',
'enotif_lastvisited'           => 'ඔබගේ අවසාන පිවිසුමට පසු සිදුවූ සියළු වෙනස්වීම් නැරඹුමට $1 බලන්න.',
'enotif_lastdiff'              => 'මෙම වෙනස නැරඹීම සඳහා $1 බලන්න.',
'enotif_anon_editor'           => 'නිර්නාමික පරිශීලක $1',
'enotif_body'                  => 'සාදර  $WATCHINGUSERNAME,


{{SITENAME}} හි  $PAGETITLE පිටුව,  $PAGEEDITDATE  දිනදී  $PAGEEDITOR විසින් $CHANGEDORCREATED කර ඇති අතර, වත්මන් අනුවාදය සඳහා $PAGETITLE_URL බලන්න.

$NEWPAGE

සංස්කාරකගේ  සාරාංශය: $PAGESUMMARY $PAGEMINOREDIT

සංස්කාරක හා සම්බන්ධවීමට:
තැපෑල: $PAGEEDITOR_EMAIL
විකි: $PAGEEDITOR_WIKI

ඔබ විසින් මෙම පිටුව වෙත පිවිසුනොත් මිස ඉදිරියෙහිදී සිදුවිය හැකි වෙනස්වීම් අභිමුඛයෙහි වෙනත් කිසිම දෑනුම්දීම් සිදුනොවනු ඇත.
ඔබගේ මුර ලැයිස්තුවෙහි ඔබගේ සියළු මුරකෙරුණු පිටු සඳහා දැනුම්දීමේ සලකුණු ප්‍රත්‍යාරම්භ කෙරුමද ඔබ හට සිදුකල හැක.

             ඔබගේ හිතවත් {{SITENAME}} හි දැනුම්දීමේ පද්ධතිය

--
ඔබගේ මුරලැයිස්තු පරිස්ථිතීන් වෙනස්කිරීම සඳහා 
{{fullurl:{{#special:Watchlist}}/edit}} වෙත පිවිසෙන්න

ඔබගේ මුරලැයිස්තුවෙන් පිටුව මකා දැමීම සඳහා වූ 
$UNWATCHURL වෙත පිවිසෙන්න

ප්‍රතිපෝෂණය හා වැඩිමනත්  සහාය:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'පිටුව මකා දමන්න',
'confirm'                => 'තහවුරු කරන්න',
'excontent'              => "අන්තර්ගතය වූයේ: '$1'",
'excontentauthor'        => "අන්තර්ගතය වූයේ: '$1' (හා එකම දායකයා වූයේ  '[[Special:Contributions/$2|$2]]' පමණි)",
'exbeforeblank'          => "හිස්කිරීමට පෙරාතුව පැවැති අන්තර්ගතය වූයේ: '$1'",
'exblank'                => 'පිටුව හිස්ව පැවතිණි',
'delete-confirm'         => '"$1" මකා දමන්න',
'delete-backlink'        => '← $1',
'delete-legend'          => 'මකන්න',
'historywarning'         => '"\'අවවාදයයි"\': ඔබ විසින් මකා දැමීමට සූදානම් වන පිටුවට $1 {{PLURAL:$1|සංශෝධනය|සංශෝධන}}: සමඟ ඉතිහාසයක් ඇත:',
'confirmdeletetext'      => 'එහි සමස්ත ඉතිහාසය හා සමගින් පිටුවක් මකා දැමීමට ඔබ සැරසෙයි.
ඔබගේ අභිමතාර්ථය මෙයමදැයි අවලෝකනය කරමින්, මෙහි ප්‍රතිවිපාක මුළුමනින් ඔබ විසින් අවබෝධ කරගෙන ඇති බවට සෑහීමට පත් වෙමින් හා, ඔබ මෙය සිදුකරන්නේ  [[{{MediaWiki:Policy-url}}|ප්‍රතිපත්තියට]] අනුකූලවදැයි විමසා බලන්න.',
'actioncomplete'         => 'ක්‍රියාව සමාප්තයි',
'actionfailed'           => 'කාර්යය අසාර්ථක විය',
'deletedtext'            => '"<nowiki>$1</nowiki>" මකා දමා ඇත.
මෑත මකාදැමීම් පිළිබඳ වාර්තාවක් සඳහා $2 බලන්න.',
'deletedarticle'         => '"[[$1]]" මකා දමන ලදි',
'suppressedarticle'      => '"[[$1]]" යටපත් කෙරිණි',
'dellogpage'             => 'මකා-දැමීම පිලිබඳ සටහන',
'dellogpagetext'         => 'පහත දැක්වෙන්නේ ඉතා මෑතදී සිදු කර ඇති මකාදැමීම් ලැයිස්තුවකි.',
'deletionlog'            => 'මකා-දැමුම් ලඝු-සටහන',
'reverted'               => 'පැරණි සංශෝධනය වෙත ප්‍රතිවර්තනය කෙරිණි',
'deletecomment'          => 'හේතුව:',
'deleteotherreason'      => 'අනෙකුත්/අමතර හේතුව:',
'deletereasonotherlist'  => 'අනෙකුත් හේතුව',
'deletereason-dropdown'  => '*සාමාන්‍ය මකාදැමීම් හේතූන්
** කතෘගේ ඉල්ලීම
** හිමිකම් උල්ලංඝනය
** වන්ධල්‍යය',
'delete-edit-reasonlist' => 'සංස්කරණ මකා දැමීම් හේතු',
'delete-toobig'          => '{{PLURAL:$1|එක් සංශෝධනයකට|සංශෝධන $1 කට}} වඩා වැඩි, විශාල සංස්කරණ ඉතිහාසයක් මෙම පිටුව සතු වෙයි.
අනවධානය නිසා  {{SITENAME}}හි සිදුවිය හැකි අක්‍රමවත්වීම් වලකනු වස්, මෙවැනි පිටු මකාදැමීම පිළිබඳ සීමා තහංචි පනවා ඇත.',
'delete-warning-toobig'  => 'මෙම පිටුවට, {{PLURAL:$1|එක් සංශෝධනයකට|සංශෝධන $1 කට}} වඩා වැඩි විශාල සංස්කරණ ඉතිහාසයක් ඇත.
මෙය මකාදැමීම  {{SITENAME}} හි දත්ත-ගබඩා ක්‍රියාකාරකම් වලට අවහිරතා පැන නැංවීමට හේතු විය හැක;
පරිස්සමින් ඉදිරි කටයුතු කරන්න.',

# Rollback
'rollback'          => 'සංස්කරණයන් පුනරාවර්තනය කරන්න',
'rollback_short'    => 'පුනරාවර්තනය',
'rollbacklink'      => 'පුනරාවර්තනය',
'rollbackfailed'    => 'පුනරාවර්තනය අසාර්ථකයි',
'cantrollback'      => 'සංස්කරණය ප්‍රතිවර්තනය කල නොහැක;
අවසන් දායකයා මෙම පිටුවේ එකම කතෘවරයාද වෙයි.',
'alreadyrolled'     => '[[User:$2|$2]] ([[User talk:$2|සාකච්ඡාව]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) විසින් සිදුකල  [[:$1]] හි අවසාන සංශෝධනය  පුනරාවර්තනය කල නොහැක;
වෙනත් අයෙකු අතින් පිටුව දැනටමත් සංස්කරණය වී හෝ පුනරාවර්තනය වී ඇත.

පිටුවට අවසන් සංස්කරණය සිදුකොට ඇත්තේ [[User:$3|$3]] ([[User talk:$3|සාකච්ඡාව]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) විසිනි.',
'editcomment'       => "සංස්කරණ සාරාංශය වූයේ: \"''\$1''\".",
'revertpage'        => '[[Special:Contributions/$2|$2]] ([[User talk:$2|සාකච්ඡාව]]) ගේ සංස්කරණයන්  [[User:$1|$1]] ගේ අවසන් අනුවාදය වෙත ප්‍රතිවර්තනය කෙරිණි',
'revertpage-nouser' => '(පරිශිලක නම ඉවත් කිරීමෙන්) සංස්කරණයන්  [[User:$1|$1]] මඟින් කළ අවසන් සංශෝධනයට ප්‍රතිවර්තනය කෙරිණි',
'rollback-success'  => ' $1 විසින් සිදුකල සංස්කරණයන් ප්‍රතිවර්තනය කරන ලදි;
$2 ගේ අවසන් අනුවාදය වෙතට යළි වෙනස් කරන ලදි .',

# Edit tokens
'sessionfailure-title' => 'සැසි ඇණ හිටීම',
'sessionfailure'       => 'ඔබගේ ප්‍රවිෂ්ට වීමේ සැසියෙහි කිසියම් ගැටළුකාරී තත්ත්වයක් පැන නැගී ඇත;
සැසි පරිග්‍රහණයට එරෙහි ආරක්ෂිත පියවරක් ලෙස මෙම ක්‍රියාව අත්හැරදමා ඇත.
"පසුපසට" බොත්තම ක්ලික් කර ඔබ පැමිණි පිටුව යළිපූරණය නොට නැවත උත්සාහ කරන්න.',

# Protect
'protectlogpage'              => 'ආරක්ෂණය කිරීම් දැක්වෙන ලඝු-සටහන',
'protectlogtext'              => 'පිටු ඇවුරුම් හා ඇවුරුම් අවලංගු කිරීම් ලැයිස්තුවක් පහත දැක්වේ.
දැනට ක්‍රියාත්මක වන පිටු ආරක්ෂණයන් ලැයිස්තුවක් සඳහා [[Special:ProtectedPages|ආරක්ෂිත පිටු ලැයිස්තුව]] බලන්න.',
'protectedarticle'            => '"[[$1]]" ආරක්‍ෂණය කරන ලදි',
'modifiedarticleprotection'   => ' "[[$1]]" සඳහා ආරක්‍ෂණ මට්ටම වෙනස් කෙරිණි',
'unprotectedarticle'          => '"[[$1]]" හි ආරක්‍ෂණය අවලංගු කෙරිණි',
'movedarticleprotection'      => '"[[$2]]" සිට "[[$1]]" දක්වා ආරක්ෂණ පරිස්ථිතීන් ගෙනයන ලදි',
'protect-title'               => ' "$1" සඳහා ආරක්‍ෂණ මට්ටම වෙනස් කරන්න',
'prot_1movedto2'              => '[[$2]] දක්වා [[$1]] ගෙනයන ලදි',
'protect-backlink'            => '← $1',
'protect-legend'              => 'ආරක්‍ෂණය තහවුරු කරන්න',
'protectcomment'              => 'හේතුව:',
'protectexpiry'               => 'ඉකුත් වීම:',
'protect_expiry_invalid'      => 'අනීතික ඉකුත් වීමේ කාලයකි.',
'protect_expiry_old'          => 'ඉකුත් වීමේ කාලය දැනටමත් ඉක්ම ගොස් ඇත.',
'protect-unchain-permissions' => 'තවදුරටත් ඇති ආරක්ෂක විකල්ප අගුළු අරින්න',
'protect-text'                => "'''<nowiki>$1</nowiki>''' පිටුව සඳහා ආරක්ෂණ මට්ටම නැරඹීම හා වෙනස් කිරීම මෙහිදී ඔබ විසින් සිදු කල හැක.",
'protect-locked-blocked'      => "වාරණයට ලක්ව සිටියදී ඔබ හට ආරක්ෂණ මට්ටම් වෙනස්කල නොහැක.
'''$1''' පිටුව සඳහා වත්මන් පරිස්ථිතීන් මෙලෙස වේ:",
'protect-locked-dblock'       => "සක්‍රීය දත්ත-ගබඩාව ඇවුරුමක් හේතුවෙන්, ආරක්ෂණ මට්ටම් වෙනස් කිරීම සිදු කල නොහැකි තත්ත්වයක් උද්ගත වී ඇත.
පිටුව සඳහා වත්මන් පරිස්ථිතීන් මෙසේය '''$1''':",
'protect-locked-access'       => "පිටුවෙහි ආරක්ෂණ මට්ටම් වෙනස් කිරීම සඳහා ඔබගේ ගිණුමට අවසර නැත.
පිටුවෙහි වත්මන් සැකසුම් මෙහි දැක්වේ '''$1''':",
'protect-cascadeon'           => 'තීරු දර්ශන ආරක්ෂණය (cascading protection) බල ගන්වා ඇති පහත  {{PLURAL:$1|පිටුව|පිටු}} අන්තර්ගත වීම හේතුවෙන් මෙම පිටුව දැනට ආරක්ෂණයට ලක්ව ඇත.
පිටුවෙහි ආරක්ෂණ මට්ටම ඔබ විසින් වෙනස් කල හැකි නමුදු, එම ක්‍රියාව තීරු දර්ශන ආරක්ෂණය කෙරෙහි බලපෑම් ඇති නොකරනු ඇත.',
'protect-default'             => 'සියළු පරිශිලකයන්ට ඉඩ සලසන්න',
'protect-fallback'            => '"$1" අවසරය අවශ්‍ය වේ',
'protect-level-autoconfirmed' => 'නව සහ ලියාපදිංචි වී නොමැති පරිශීලකයන් වාරණය කරන්න',
'protect-level-sysop'         => 'පරිපාලකවරුන්ට පමණයි',
'protect-summary-cascade'     => 'තීරු දර්ශනය',
'protect-expiring'            => 'ඉකුත් වේ  $1 (යූටීසි)',
'protect-expiry-indefinite'   => 'අනිශ්චිත',
'protect-cascade'             => 'මෙම පිටුවෙහි ඇතුළත් කර ඇති පිටු ආරක්ෂණය කරන්න (තීරු දර්ශන ආරක්ෂණය)',
'protect-cantedit'            => 'ඔබ හට එය සංස්කරණය කිරීමට අවසර නොමැති බැවින්, ඔබ හට මෙම පිටුවෙහි ආරක්ෂණ මට්ටම වෙනස් කල නොහැක.',
'protect-othertime'           => 'අනෙකුත් වේලාව:',
'protect-othertime-op'        => 'අනෙකුත් වේලාව',
'protect-existing-expiry'     => 'සංස්කරණ කල්ඉකුත්වීම් කාලය time: $3, $2',
'protect-otherreason'         => 'අනෙකුත්/අමතර හේතුව:',
'protect-otherreason-op'      => 'අනෙකුත් හේතුව',
'protect-dropdown'            => '*සාමන්‍ය රක්ෂණ හේතූන්
** අත්‍යන්ත වන්ධ්‍යලය
** අත්‍යන්ත අයාචිත-තැපෑල
** නිෂ්ඵලදායී සංස්කරණ පොරකෑම්
** අධික අතුරුමාරු සහිත පිටුව',
'protect-edit-reasonlist'     => 'සංස්කරණ ආරක්ෂණ හේතූන්',
'protect-expiry-options'      => 'පැය 1:1 hour,දින 1:1 day, සති 1:1 week, සති 2:2 weeks, මාස 1:1 month, මාස 3:3 months, මාස 6:6 months, වසර 1:1 year, අනන්තය:infinite',
'restriction-type'            => 'අවසරය:',
'restriction-level'           => 'සීමාකිරීම් මට්ටම:',
'minimum-size'                => 'අවම විශාලත්වය',
'maximum-size'                => 'උපරිම විශාලත්වය:',
'pagesize'                    => '(බයිට්)',

# Restrictions (nouns)
'restriction-edit'   => 'සංස්කරණය කරන්න',
'restriction-move'   => 'ගෙන යන්න',
'restriction-create' => 'තනන්න',
'restriction-upload' => 'උඩුගත කරන්න',

# Restriction levels
'restriction-level-sysop'         => 'පූර්ණ ලෙස  ආරක්‍ෂණය කෙරිණි',
'restriction-level-autoconfirmed' => 'අර්ධ ලෙස ආරක්‍ෂණය කෙරිණි',
'restriction-level-all'           => 'ඕනෑම මට්ටමක්',

# Undelete
'undelete'                     => 'මකා දැමූ පිටු නරඹන්න',
'undeletepage'                 => 'මකා දැමූ පිටු නරඹා ඒවා ප්‍රතිෂ්ඨාපනය කරන්න',
'undeletepagetitle'            => "'''මෙහි පහත සමන්විත වන්නේ [[:$1|$1]] හි මකාදැමුණු සංශෝධනයන් ගෙනි '''.",
'viewdeletedpage'              => 'මකා දැමූ පිටු නරඹන්න',
'undeletepagetext'             => 'පහත {{PLURAL:$1|පිටුව මකාදැමුවද එය |පිටු $1 මකාදැමුවද ඒවා}}සංරක්‍ෂිතාගාරයෙහි තවමත් පවතින බැවින් ප්‍රතිෂ්ඨාපනය කල හැක.
සංරක්‍ෂිතාගාරය කලින් කලට  සුද්ධ පවිත්‍ර කරනු ලැබිය හැක.',
'undelete-fieldset-title'      => 'සංශෝධනයන් ප්‍රතිෂ්ඨාපනය කරන්න',
'undeleteextrahelp'            => "පිටුවෙහි සමස්ත ඉතිහාසය ප්‍රතිෂ්ඨාපනය  කරනු වස්, සියළු පිරික්සුම්කොටු නොතෝරා, '''''ප්‍රතිෂ්ඨාපනය''''' ක්ලික් කරන්න.
යම් සුවිශේෂ ප්‍රතිෂ්ඨාපනයක් සිදුකිරීමට,  ප්‍රතිෂ්ඨාපනය කිරීමට රිසි සංශෝධනයන්ට අනුරූප කොටු තෝරාගෙන, '''''ප්‍රතිෂ්ඨාපනය''''' ක්ලික් කරන්න.
'''''ප්‍රත්‍යාරම්භය''''' ක්ලික් කිරීමෙන් පරිකථන ක්ෂේත්‍රය හා සියළු පිරික්සුම්කොටු නිෂ්කාශනය වේ.",
'undeleterevisions'            => ' {{PLURAL:$1|සංශෝධනයක්|සංශෝධන $1 ක්}} සංරක්‍ෂිතාගාරයට යවන ලදි',
'undeletehistory'              => 'ඔබ පිටුව ප්‍රතිෂ්ඨාපනය කලහොත්, සියළු සංශෝධනයන් ඉතිහාසයට ප්‍රතිෂ්ඨාපනය වනු ඇත.
මකාදැමීමෙන් අනතුරුව පළමු නමම සහිත නව පිටුවක් තැනුවේ නම්, ප්‍රතිෂ්ඨාපිත සංශෝධනයන් පූර්ව ඉතිහාසයෙහි බහාලේ .',
'undeleterevdel'               => 'උඩු පිටුව හෝ ගොනු සංශෝධනය හෝ භාගික වශයෙන් මකාදැමීම එහි ප්‍රතිඵලයක් වන්නේ නම් මකාදැමීම අවලංගු කිරීම සිදුනොකරනු ඇත.
‍එවැනි අවස්ථාවලදී, නවීනතම මකාදැමුණු සංශෝධනය නොතෝරාගැනුම හෝ නොසැඟවීම හෝ ඔබ විසින් සිදුකල යුතුය.',
'undeletehistorynoadmin'       => 'මෙම පිටුව මකාදමා ඇත.
මකා දැමුමට පෙර මෙම පිටුව සංස්කරණය කල පරිශීලකයන් පිළිබඳ විස්තරද සහිතව, මකාදැමුමට හේතුව පහත සාරාංශයෙහි දැක්වේ.
මෙම මකාදැමුණු සංශෝධනයන්ගේ තථ්‍ය පෙළ පරිහරණය කල හැක්කේ පරිපාලකවරුන්ට පමණයි.',
'undelete-revision'            => '($4 දී, $5 වන විට) $3 විසින් මකා දමා තිබූ $1හි සංශෝධනය :',
'undeleterevision-missing'     => 'අනීතික හෝ සොයාගතනොහැකි සංශෝධනය.
එක්කෝ ඔබගේ සබැඳිය සදොස්ය, නැතහොත් සංශෝධනය ප්‍රතිෂ්ඨාපනයට හෝ සංරක්ෂිතයෙන් ඉවත් කිරීමට හෝ  ලක්ව තිබේ.',
'undelete-nodiff'              => 'පූර්ව සංශෝධන කිසිවක් සොයා ගත නොහැකි විය.',
'undeletebtn'                  => 'ප්‍රතිෂ්ඨාපනය',
'undeletelink'                 => 'නරඹන්න/ප්‍රතිෂ්ඨාපනය කරන්න',
'undeleteviewlink'             => 'නරඹන්න',
'undeletereset'                => 'ප්‍රත්‍යාරම්භ කරන්න',
'undeleteinvert'               => 'තෝරාගැනුම කණපිට පෙරලන්න',
'undeletecomment'              => 'හේතුව:',
'undeletedarticle'             => '"[[$1]]"  ප්‍රතිෂ්ඨාපනය කරන ලදි',
'undeletedrevisions'           => '{{PLURAL:$1|සංශෝධනයක්|සංශෝධන $1 ක්}} ප්‍රතිෂ්ඨාපනය කරන ලදි',
'undeletedrevisions-files'     => '{{PLURAL:$1|එක් සංශෝධනයක්| සංශෝධන $1 ක්}} සහ {{PLURAL:$2|එක් ගොනුවක්|ගොනු $2 ක්}} ප්‍රතිෂ්ඨාපනය කෙරිණි',
'undeletedfiles'               => '{{PLURAL:$1|එක් ගොනුවක්|ගොනු $1 ක්}} ප්‍රතිෂ්ඨාපනය කෙරිණි',
'cannotundelete'               => 'මකාදැමීම ප්‍රතිලෝම කිරීම අසාර්ථක විය;
මෙම පිටුවේ මකාදැමීම ප්‍රතිලෝම කිරීම යමෙකු මීට කලින්  කර ඇතුවා විය හැක.',
'undeletedpage'                => "'''$1 ප්‍රතිෂ්ඨාපනය කෙරී ඇත'''

මෑතදී සිදුවූ මකාදැමීම් හා ප්‍රතිෂ්ඨාපනයන් හි වාර්තාවක් උදෙසා [[Special:Log/delete|මකාදැමීම් ලඝු-සටහන]] පරිශීලනය කරන්න.",
'undelete-header'              => 'මෑතදී මකාදැමුණු පිටු සඳහා  [[Special:Log/delete|මකාදැමුම්  ලඝු-සටහන]] බලන්න.',
'undelete-search-box'          => 'මකා දැමූ පිටු ගවේෂණය කරන්න',
'undelete-search-prefix'       => 'මෙයින් ඇරඹෙන පිටු පෙන්වන්න:',
'undelete-search-submit'       => 'ගවේෂණය',
'undelete-no-results'          => 'මකාදැමීම් සංරක්ෂිතයෙහි ගැලපෙන පිටු කිසිවක් හමු නොවිණි.',
'undelete-filename-mismatch'   => ' $1 වේලාමුද්‍රාව සමගැති ගොනු සංශෝධනයේ මකාදැමීම ප්‍රතිලෝම කල නොහැක: ගොනුනාමය නොගැළපේ',
'undelete-bad-store-key'       => ' $1 වේලාමුද්‍රාව සමගැති ගොනු සංශෝධනයේ මකාදැමීම ප්‍රතිලෝම කල නොහැක: මකාදැමීමට පෙර ගොනුව අස්ථානගතවී තිබුණි.',
'undelete-cleanup-error'       => 'භාවිතා නොකල සංරක්ෂිත ගොනුව "$1" මකාදැමීමෙහිදී දෝෂ ඇතිවිය.',
'undelete-missing-filearchive' => '$1 ගොනු සංරක්ෂණ අනන්‍යාංකය දත්ත-ගබඩාවෙහි නොමැති නිසා  ප්‍රතිෂ්ඨාපනය කල නොහැකි විය.
එහි මකාදැමුම දැනටමත් අවලංගු කර ඇතුවා විය හැක.',
'undelete-error-short'         => 'මෙම ගොනුව මකාදැමීම අවලංගු කිරීමේදී දෝෂයක් ඇති විය: $1',
'undelete-error-long'          => 'මෙම ගොනුව මකාදැමීම අවලංගු කිරීමේදී දෝෂ හමු විය:

$1',
'undelete-show-file-confirm'   => '"<nowiki>$1</nowiki>" ගොනුවෙහි  $2 දිනදී $3 වේලාවෙහිදී මකාදැමුණු සංශෝධනය නැරඹීම ඔබ විසින් සිදු කල යුතු බව ඔබ හට සහතිකද?',
'undelete-show-file-submit'    => 'ඔව්',

# Namespace form on various pages
'namespace'      => 'නාමඅවකාශය:',
'invert'         => 'තෝරාගැනුම ප්‍රතිලෝම කරන්න',
'blanknamespace' => '(ප්‍රධාන)',

# Contributions
'contributions'       => 'මේ පරිශීලකයාගේ දායකත්ව',
'contributions-title' => ' $1 සඳහා පරිශීලක දායකත්වයන්',
'mycontris'           => 'මගේ දායකත්ව',
'contribsub2'         => '$1 සඳහා ($2)',
'nocontribs'          => 'මෙම උපමානයන් හා ගැලපෙන වෙනස්වීම් හමුනොවිණි.',
'uctop'               => '(පෙරටු)',
'month'               => 'මෙම මස (හා ඉන් පෙර) සිට:',
'year'                => 'මෙම වසර (හා ඉන් පෙරාතුව) සිට:',

'sp-contributions-newbies'             => 'නව ගිණුම් වලට පමණක් අදාල දායකත්ව පෙන්වන්න',
'sp-contributions-newbies-sub'         => 'නව ගිණුම් වලට අදාල',
'sp-contributions-newbies-title'       => 'නව ගිණුම් වලට අදාල පරිශීලක දායකත්ව',
'sp-contributions-blocklog'            => 'වාරණ සටහන',
'sp-contributions-deleted'             => 'මකාදැමූ පරිශීලක දායකත්වයන්',
'sp-contributions-logs'                => 'ලඝු-සටහන්',
'sp-contributions-talk'                => 'සාකච්ඡාව',
'sp-contributions-userrights'          => 'පරිශීලක හිමිකම් කළමනාකරණය',
'sp-contributions-blocked-notice'      => 'මෙම පරිශීලකයා දැනට අවහිර කරනු ලැබ තිබේ.
නවතම අවහිර කිරීම් ලඝු සටහන පහත යොමුවෙන් සපයනු ලැබේ:',
'sp-contributions-blocked-notice-anon' => 'මෙම අන්තර්ජාල ලිපිනය දැනට වාරණය කොට ඇත.
ඔබගේ උපමානයන් සඳහා නවතම වාරණ ලඝු නිවේශිතය මෙහි පහත දක්වා ඇත:',
'sp-contributions-search'              => 'දායකත්ව පිළිබඳ ගවේෂණය කරන්න',
'sp-contributions-username'            => 'පරිශීලක නාමය හෝ IP ලිපිනය:',
'sp-contributions-submit'              => 'ගවේෂණය කරන්න',

# What links here
'whatlinkshere'            => 'මෙයට සබැ‍ඳෙන පිටු',
'whatlinkshere-title'      => '"$1" වෙත සබැ‍ඳෙන පිටු',
'whatlinkshere-page'       => 'පිටුව:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "ඉදිරියෙහි දැක්වෙන පිටු, '''[[:$1]]''' වෙත සබැඳෙයි:",
'nolinkshere'              => "'''[[:$1]]''' වෙත කිසිදු පිටුවක් සබැඳී නොමැත.",
'nolinkshere-ns'           => "තෝරාගෙන ඇති නාම-අවකාශය තුලදී, කිසිදු පිටුවක්, '''[[:$1]]''' වෙත නොබැඳෙයි.",
'isredirect'               => 'පිටුව යළි-යොමුකරන්න',
'istemplate'               => 'අන්තහ්කරණය',
'isimage'                  => 'ප්‍රතිමූර්ති-සබැඳිය',
'whatlinkshere-prev'       => '{{PLURAL:$1|පූර්ව|පූර්ව $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ඉදිරි|ඉදිරි $1}}',
'whatlinkshere-links'      => '← සබැඳි',
'whatlinkshere-hideredirs' => '$1 යළි-යොමුකරයි',
'whatlinkshere-hidetrans'  => '$1 අන්තඃගතයන්',
'whatlinkshere-hidelinks'  => 'සබැඳියන් $1',
'whatlinkshere-hideimages' => ' රූප සබැඳියන් $1',
'whatlinkshere-filters'    => 'පෙරහන්',

# Block/unblock
'blockip'                         => 'පරිශීලකයා වාරණය කරන්න',
'blockip-title'                   => 'පරිශීලකයා වාරණය කරන්න',
'blockip-legend'                  => 'වාරණයකල පරිශීලක',
'blockiptext'                     => 'විශේෂිත අන්තර්ජාල ලිපිනයකින් හෝ ප්‍රතිශීලක නාමයකින් ලිවීම් ප්‍රවේශය වාරණය කෙරුමට පහත ආකෘති පත්‍රය භාවිතා කරන්න.
වන්ධල්‍යය වැලැක්වීමේ හුදු  අභිලාෂයෙන් හා, [[{{MediaWiki:Policy-url}}|ප්‍රතිපත්ති]] ප්‍රකාරව මෙය සිදුකල යුත්තේය.
විශේෂිත  හේතුවක් මෙහි පහත ඇතුලත් කරන්න (නිදසුනක් ලෙස, වන්ධල්‍ය්‍යට ලක්වුනු විශේෂිත පිටු හඳුන්වමින්).',
'ipaddress'                       => 'අන්තර්ජාල ලිපිනය:',
'ipadressorusername'              => 'අන්තර්ජාල ලිපිනය හෝ පරිශීලක නාමය:',
'ipbexpiry'                       => 'කල් ඉකුත්වීම:',
'ipbreason'                       => 'හේතුව:',
'ipbreasonotherlist'              => 'අනෙකුත් හේතුව',
'ipbreason-dropdown'              => '*සාමාන්‍ය වාරණ හේතූන්
** සාවද්‍ය තොරතුරු බහාලීම
** පිටුවලින් අන්තර්ගතය ඉවත්කිරීම
** බාහිර අඩවි වෙත අයාචිත-තැපැල් සබැඳියන්
** විප්‍රලාප /පල්හෑලි පිටු තුලට බහාලීම 
** තැතිගන්වනසුළු  හැසිරීම/හිරිහැරකිරීම
** බහුගණ ගිනුම් අපයෙදුම
** නොපිළිගතහැකි පරිශීලකනාමය',
'ipbanononly'                     => 'නිර්නාමික පරිශීලකයන් පමණක් වාරණය කරන්න',
'ipbcreateaccount'                => 'ගිණුම් තැනීම වලක්වන්න',
'ipbemailban'                     => 'පරිශීලක විසින් විද්‍යුත්-තැපැල් යැවීම වලක්වන්න',
'ipbenableautoblock'              => 'මෙම පරිශීලකයා විසින් භාවිතා කරන අන්තර්ජාල ලිපිනයද, මෙයින් පසුව සංස්කරණය සඳහා ඔවුන් භාවිතා කිරීමට ඉඩ ඇති අන්තර්ජාල ලිපිනයන්ද ස්වයංක්‍රීය ලෙස වාරණය කරන්න',
'ipbsubmit'                       => 'මෙම පරිශීලක වාරණය කරන්න',
'ipbother'                        => 'අනෙකුත් වේලාව:',
'ipboptions'                      => 'පැය 2:2 hours,දින 1:1 day,දින 3:3 days,සති 1:1 week,සති 2:2 weeks,මාස 1:1 month,මාස 3:3 months,මාස 6:6 months,වසර 1:1 year,අනන්තය:infinite',
'ipbotheroption'                  => 'අනෙකුත්',
'ipbotherreason'                  => 'අනෙකුත්/අමතර හේතුව:',
'ipbhidename'                     => 'පරිශීලක-නාමය සංස්තරණයන් ගෙන් හා ලැයිස්තු වලින් සඟවන්න',
'ipbwatchuser'                    => 'මෙම පරිශීලකයාගේ පරිශීලක හා සාකච්ඡා පිටු මුර-කරන්න',
'ipballowusertalk'                => 'වාරණය පැවතියදී ස්වීය සාකච්ඡා පිටුව සංස්කරණය කිරීමට මෙම පරිශීලකයාට ඉඩදෙන්න',
'ipb-change-block'                => 'මෙම පරිස්ථිතීන් සහිතව පරිශීලකයා යළි-වාරණය කරන්න',
'badipaddress'                    => 'අනීතික අන්තර්ජාල ලිපිනයකි',
'blockipsuccesssub'               => 'වාරණය සාර්ථක විය',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] වාරණයට ලක් කර ඇත.<br />
වාරණයන් සමාලෝචනය සඳහා  [[Special:IPBlockList|අන්තර්ජාල වාරණ ලැයිස්තුව]] බලන්න.',
'ipb-edit-dropdown'               => 'සංස්කරණ වාරණ හේතූන්',
'ipb-unblock-addr'                => '$1වාරණය අත්හිටුවන්න',
'ipb-unblock'                     => 'පරිශීලක නාමයක හෝ අන්තර්ජාල ලිපිනයක වාරණය අත්හිටුවන්න',
'ipb-blocklist-addr'              => '$1 සඳහා පවතින වාරණයන් නරඹන්න',
'ipb-blocklist'                   => 'පවතින වාරණයන් නරඹන්න',
'ipb-blocklist-contribs'          => '$1 සඳහා දායකත්වයන්',
'unblockip'                       => 'පරිශීලකයාගේ වාරණය අත්හිටුවන්න',
'unblockiptext'                   => 'පෙරදී වාරණයට ලක්වූ අන්තර්ජාල ලිපිනය හෝ පරිශීලක නාමය හෝ වෙත ලිවීමේ බලය ප්‍රතිෂ්ඨාපනය කෙරුමට පහත ආකෘතිය භාවිත කරන්න.',
'ipusubmit'                       => 'මෙම වාරණය කිරීම අත්හිටුවන්න',
'unblocked'                       => '[[User:$1|$1]]  වාරණය අත්හිටුවා ඇත',
'unblocked-id'                    => '$1 වාරණය ඉවත් කරගන්නා ලදි',
'ipblocklist'                     => 'වාරණයට ලක්වූ අන්තර්ජාල ලිපිනයන් හා පරිශීලක නාම',
'ipblocklist-legend'              => 'වාරණය කෙරුනු පරිශීලකයා සොයන්න',
'ipblocklist-username'            => 'පරිශීලක නාමය හෝ අන්තර්ජාල ලිපිනය:',
'ipblocklist-sh-userblocks'       => 'ගිණුම් වාරණයන් $1',
'ipblocklist-sh-tempblocks'       => 'තාවකාලික වාරණයන් $1',
'ipblocklist-sh-addressblocks'    => 'ඒක අන්තර්ජාල-ලිපින වාරණයන් $1',
'ipblocklist-submit'              => 'ගවේෂණය',
'ipblocklist-localblock'          => 'පෙදෙසි අවහිරය',
'ipblocklist-otherblocks'         => 'අනෙක් {{PLURAL:$1|අවහිර කිරීම|අවහිර කිරීම්}}',
'blocklistline'                   => '$1 දී, $2 විසින් $3 ($4) වාරණය කෙරිණි',
'infiniteblock'                   => 'අනන්තය',
'expiringblock'                   => '$1 දිනදී $2 වේලාවේදී  කල් ඉකුත්වේ',
'anononlyblock'                   => 'නිර්නාමිකයන් පමණි',
'noautoblockblock'                => 'ස්වයංක්‍රීය වාරණය අක්‍රීය කෙරිණි',
'createaccountblock'              => 'ගිණුම් තැනීම වාරණය කෙරිණි',
'emailblock'                      => 'විද්‍යුත්-තැපෑල වාරණය කෙරිණි',
'blocklist-nousertalk'            => 'ස්වීය සාකච්ඡා පිටුව සංස්කරණය කල නොහැක',
'ipblocklist-empty'               => 'වාරණ-ලැයිස්තුව හිස්ය.',
'ipblocklist-no-results'          => 'අයැදුනු අන්තර්ජාල ලිපිනය හෝ පරිශීලක නාමය හෝ වාරණයකට ලක්ව නොමැත.',
'blocklink'                       => 'වාරණය',
'unblocklink'                     => 'වාරණයෙන් ඉවත්වන්න',
'change-blocklink'                => 'වාරකය වෙනස් කරන්න',
'contribslink'                    => 'දායකත්ව',
'autoblocker'                     => 'ඔබගේ අන්තර්ජාල ලිපිනය "[[User:$1|$1]]" විසින් මෑතකදී භාවිතා කර ඇති බැවින් ඔබ ස්වයංක්‍රීය-වාරණයකට ලක් කර ඇත.
$1 ගේ වාරණයට හේතුව මෙය වේ: "$2"',
'blocklogpage'                    => 'වාරණ ලඝු සටහන',
'blocklog-showlog'                => 'මෙම පරිශීලකයා මීට පෙර අවහිර කරනු ලැබ ඇත.
අවහිරි කිරීම් ලඝු සටහන යොමුව සඳහා පහතින් සපයනු ලැබේ:',
'blocklog-showsuppresslog'        => 'මෙම පරිශීලකයා මීට පෙර අවහිර කොට සඟවනු ලැබ ඇත.
යොමුව සඳහා යටපත් කිරීම් ලඝු සටහන පහතින් සපයනු ලැබේ:',
'blocklogentry'                   => '$2 $3 වෙතින් දැක්වෙන ඉකුත් වීමේ කාලයකට යටත් කොට [[$1]] වාරණයට ලක් කර ඇත',
'reblock-logentry'                => '$2 $3 කල්ඉකුත්වීමේ වේලාවට යටත්ව [[$1]] සඳහා වාරණ පරිස්ථිතීන් වෙනස්කරන ලදි',
'blocklogtext'                    => 'පරිශීලකයන් වාරණය කිරීමේ හා වාරණයන් අත්හිටුවීමේ කාර්යයන් දැක්වෙන ලඝු සටහන මෙහි දැක්වේ.
ස්වයංක්‍රීයව වාරණය කල අන්තර්ජාල ලිපිනයන් ලැයිස්තුගත කොට නොමැත.
වර්තමානයෙහි ක්‍රියාත්මක වන තහනම් හා වාරණ සඳහා [[Special:IPBlockList|අන්තර්ජාල ලිපිනයන් වාරණ ලැයිස්තුව]] බලන්න.',
'unblocklogentry'                 => '$1 හි වාරණය අත්හිටුවන ලදි',
'block-log-flags-anononly'        => 'නිර්නාමික පරිශීලකයන් පමණි',
'block-log-flags-nocreate'        => 'ගිණුම් තැනීම අක්‍රීය කොට ඇත',
'block-log-flags-noautoblock'     => 'ස්වයංක්‍රීය වාරණය අක්‍රීය කොට ඇත',
'block-log-flags-noemail'         => 'විද්‍යුත්-තැපෑල වාරණය කොට ඇත',
'block-log-flags-nousertalk'      => 'ස්වීය සාකච්ඡා පිටුව සංස්කරණය කල නොහැක',
'block-log-flags-angry-autoblock' => 'ආවර්ධිත ස්වයංක්‍රීය වාරණය සක්‍රීය කරන ලදි',
'block-log-flags-hiddenname'      => 'පරිශීලක-නාමය  සඟවා ඇත',
'range_block_disabled'            => 'පරාස වාරණයන් සිදුකිරීමට පරිපාලක වරුන්ට ඇති හැකියාව අක්‍රීය කරන ලදි.',
'ipb_expiry_invalid'              => 'ඉකුත්වීමේ කාලය අනීතිකය.',
'ipb_expiry_temp'                 => 'සැඟවුනු පරිශීලක-නාම වාරණයන් ස්ථීර ඒවා විය යුතුය.',
'ipb_hide_invalid'                => 'මෙම ගිණුම යටපත්කිරීම කල නොහැකියි; පමණට වඩා සංස්කරණ සිදු කර ඇතිවා විය හැක.',
'ipb_already_blocked'             => '"$1" දැනටමත් වාරණයට ලක් කර ඇත',
'ipb-needreblock'                 => '== දැනටමත් වාරණය කොට ඇත ==
$1 දැනටමත් වාරණය කොට ඇත. පරිස්ථිතීන් වෙනස්කිරීම ඔබ හට ඇවැසිද?',
'ipb-otherblocks-header'          => 'අනෙකුත් {{PLURAL:$1|වාරණය|වාරණයන්}}',
'ipb_cant_unblock'                => 'දෝෂය: වාරණ අනන්‍යනාංකය $1 සොයා ගත නොහැකි විය.
මෙය දැනටමත් වාරණ අත්හිටුවීමකට භාජනය වී ඇතිවා විය හැක.',
'ipb_blocked_as_range'            => 'දෝෂය: $1 අන්තර්ජාල ලිපිනය සෘජුව වාරණය කොට නොමැති අතර එහි වාරණ‍ය අත්හිටුවිය නොහැක.
එනමුදු, එය, $2 පරාසයෙහි කොටසක් ලෙස වාරණයට ලක් කොට ඇති අතර, එහි වාරණය අත්හිටුවිය හැක.',
'ip_range_invalid'                => 'අනීතික අන්තර්ජාල ලිපින පරාසයකි.',
'ip_range_toolarge'               => '/$1 ට වඩා විශාල පරාස කොටස්වලට ඉඩ ලබා නොදේ.',
'blockme'                         => 'මා වාරණය කරන්න',
'proxyblocker'                    => 'ප්‍රතියුක්ත (ප්‍රොක්සි) වාරණකරු',
'proxyblocker-disabled'           => 'මෙම කෘත්‍යය අක්‍රීය කොට ඇත.',
'proxyblockreason'                => 'ඔබගේ අන්තර්ජාල ලිපිනය විවෘත ප්‍රතියුක්තයක් (ප්‍රොක්සි) බැවින් එය වාරණය කොට ඇත.
ඔබගේ අන්තර්ජාල සේවා ප්‍රතිපාදකයා හෝ තාක්ෂණික අනුග්‍රාහකයා හෝ අමතා මෙම බරපතළ ආරක්ෂණ ගැටළුව ඔවුනට නිරාවරණය කරන්න.',
'proxyblocksuccess'               => 'සිදුකලා.',
'sorbs'                           => 'DNSBL',
'sorbsreason'                     => 'ඔබගේ අන්තර්ජාල ලිපිනය, {{SITENAME}} විසින් භාවිත වන DNSBL හි විවෘත නියුතුවක් (ප්‍රොක්සියක්) ලෙස ලැයිස්තුගත කොට ඇත.',
'sorbs_create_account_reason'     => 'ඔබගේ අන්තර්ජාල ලිපිනය, {{SITENAME}} විසින් භාවිත වන DNSBL හි විවෘත නියුතුවක් (ප්‍රොක්සියක්) ලෙස ලැයිස්තුගත කොට ඇත.
ඔබ හට ගිණුමක් තැනිය නොහැක',
'cant-block-while-blocked'        => 'ඔබ වාරණයට ලක්ව සිටින අතරතුර අනෙක් පරිශීලකයන් වාරණය කිරීමට ඔබ හට නොහැක.',
'cant-see-hidden-user'            => 'අවහිර කිරීමට උත්සාහ කරන පරිශීලකයා දැනටමත් අවහිර කර සඟවා ඇත.පරිශීලක සැඟවුම් අයිතිය ඔබ සතු නොවන බැවින් ,ඔබට පරිශීලක අවහිරය නැරඹීමට හෝ සංස්කරණය කිරීමට නොහැකිය.',
'ipbblocked'                      => 'ඔබද වාරණය කොට ඇති බැවින් අනෙකුත් පරිශීලකයන් වාරණය කිරීම හෝ වාරණයෙන් මුදවීම ඔබ හට කල නොහැක',
'ipbnounblockself'                => 'ඔබ විසින්ම ඔබගේ වාරණයෙන් බැහැර වීමට ඉඩදෙනු නොලැබේ',

# Developer tools
'lockdb'              => 'දත්ත-ගබඩාව අවුරන්න',
'unlockdb'            => 'දත්ත-ගබඩාවට පැනවුනු ඇවුරුම ඉවත් කරන්න',
'lockdbtext'          => 'පිටු සංස්කරණය, ඔවුන්ගේ අභිරුචි වෙනස් කිරීම, ඔවුන්ගේ මුර-ලැයිස්තු වෙනස් කිරීම, හා දත්ත-ගබඩාව වෙනස් කිරීම ඔස්සේ සිදු කල යුතුවූ වෙනත් දේවල් සිදු කිරීමට සියළු පරිශීලකයන් හට ඇති හැකියාව, දත්ත-ගබඩාව ඇවුරුම මගින් අත්හිටුවීමකට ලක් වේ.
‍ඔබගේ අභිමතාර්ථය මෙයමැයිද, ඔබගේ නඩත්තු කටයුතු අවසන් වූ විට දත්ත-ගබඩාව ඇවුරුම ඉවත් කරන බවද සනාථ කරන්න.',
'unlockdbtext'        => 'පිටු සංස්කරණය, ඔවුන්ගේ අභිරුචි වෙනස් කිරීම, ඔවුන්ගේ මුර-ලැයිස්තු වෙනස් කිරීම, හා දත්ත-ගබඩාව වෙනස් කිරීම ඔස්සේ සිදු කල යුතුවූ වෙනත් දේවල් සිදු කිරීමට සියළු පරිශීලකයන් හට ඇති හැකියාව, දත්ත-ගබඩාව ඇවුරුම අත්හිටුවීම මගින් ප්‍රතිෂ්ඨාපනයට ලක් වේ.
‍ඔබගේ අභිමතාර්ථය මෙයමැයි සනාථ කරන්න.',
'lockconfirm'         => 'ඔව්, මා හට ඇත්ත වශයෙන් ඇවැසි වන්නේ දත්ත-ගබඩාව ඇවුරුමයි.',
'unlockconfirm'       => 'ඔව්, මා හට ඇත්ත වශයෙන්ම ඇවැසි වන්නේ දත්ත-ගබඩාව ඇවුරුම ඉවත් කෙරුමයි.',
'lockbtn'             => 'දත්ත-ගබඩාව අවුරන්න',
'unlockbtn'           => 'දත්ත-ගබඩාව ඇවුරුම ඉවත් කරන්න',
'locknoconfirm'       => 'තහවුරුකිරීමේ කොටුව ඔබ විසින් තෝරාගෙන නොමැත.',
'lockdbsuccesssub'    => 'දත්ත-ගබඩාව ඇවුරුම සාර්ථක විය',
'unlockdbsuccesssub'  => 'දත්ත-ගබඩාව ඇවුරුම ඉවත් කරන ලදි',
'lockdbsuccesstext'   => 'දත්ත-ගබඩාව අවුරා ඇත.<br />
ඔබගේ නඩත්තු කටයුතු අවසන් වූ විට [[Special:UnlockDB|ඇවුරුම ඉවත් කෙරුමට]]  සිහි තබා ගන්න.',
'unlockdbsuccesstext' => 'දත්ත-ගබඩාව ඇවුරුම ඉවත් කර ඇත.',
'lockfilenotwritable' => 'දත්ත-ගබඩා ඇවුරුම් ගොනුව, ලිවිය-හැකි ගොනුවක් නොවේ.
දත්ත-ගබඩාව ඇවුරුම හෝ ඇවුරුම අත්හිටුවීම හෝ කල හැකි වනු වස්, මෙය වෙබ් සේවාදායකය මගින් ලිවිය-හැක්කක් විය යුතුය.',
'databasenotlocked'   => 'දත්ත-ගබඩාව අවුරා නොමැත.',

# Move page
'move-page'                    => ' $1 ගෙනයන්න',
'move-page-backlink'           => '← $1',
'move-page-legend'             => 'පිටුව ගෙනයන්න',
'movepagetext'                 => "පහත ආකෘතිය භාවිතා කිරීමෙන්, එහි සියළු ඉතිහාසය නව නාමයට අනුයුක්ත කරමින්,  පිටුවක නම-වෙනස් කිරීම සිදුවේ.
නව නාමය වෙත යළි-යොමු වන්නාවූ පිටුවක් බවට පැරැණි නාමය පත් වෙයි.
ආදිමය නාමය වෙත ස්වයංක්‍රීයව එල්ල වන යළි-යොමු වීම් යාවත්කාලීන කිරීම් ඔබ විසින් සිදු කල හැක.
එසේ සිදු කිරීමට ඔබ නොරිසි නම්, [[Special:DoubleRedirects|ද්විත්ව]] හෝ [[Special:BrokenRedirects|භින්න යළි-යොමු වීම්]] පරික්ෂා කර බැලීමට යුහුසුළු වන්න.
නියමිත යොමු කරා සබැඳියන්  දිගටම එල්ල වන බව සහතික කිරීම ඔබගේ වගකීමකි.

නව නාමය සහිත පිටුවක් දැනටමත් තිබේ නම්, එය හිස් නම් හෝ යළි-යොමුවක් හා එහි පූර්ව සංස්කරණ ඉතිහාසයක් නොමැති නම් මිස, පිටුව ගෙනයෑම සිදු ''නොකරන''' බව සලකන්න.
මෙහි අරුත වන්නේ, ඔබ විසින් අත්වැරැද්දක් සිදුවුනි නම්, නම වෙනස් කල යම් පිටුවක නම ‍වෙනස් කිරීමට පැවැති පිටුවට ආපසු නම වෙනස් කල හැකි බවත්, එනමුදු දැනට පවතින පිටුවක් අධිලිවීමකට ලක් කිරීම සිදු කල නොහැකි බවත්ය.

'''අවවාදයයි!'''
මෙම වෙනස ජනප්‍රිය පිටුවකට විෂයෙහි සිදුවන උග්‍ර හා අනපේක්‍ෂිත වෙනස්කමක් විය හැක;
බිඳක් නැවැතී  මෙහි ප්‍රතිවිපාක පිළිබඳ පරිලෝකනය කිරීමට යුහුසුළු වන්න.",
'movepagetalktext'             => "එය සමග ආශ්‍රිත සාකච්ඡා පිටුව ස්වයංක්‍රීය ලෙස ගෙනයාම වළක්වන '''වැළැහීම්:'''
*නව පිටු නාමය යටතේ, හිස්-නොවන සාකච්ඡා පිටුවක් දැනටමත් පැවැතීම, හෝ
*පහත කොටුව ඔබ විසින් නොතේරූ නිසාවෙන්.

මෙවන් අවස්ථා වලදී, අවශ්‍යතාවය පැන නගී නම්, හස්තීය ලෙස ගෙන යාම හෝ ඒකාබද්ධ කිරීම හෝ සිදු කිරීමට ඔබ හට සිදුවේ.",
'movearticle'                  => 'පිටුව ගෙනයන්න:',
'moveuserpage-warning'         => "'''අවවාදයයි:''' ඔබ යත්න දරමින් සිටිනුයේ පරිශීලක පිටුවක් ගෙන යෑමටයි. පිටුව ගෙන යෑම පමණක් සිදුවන බවද පරිශීලකයා යළි-නම්කෙරුම සිදු ''නොවන'' බවද කරුණාවෙන් සිහි තබා ගන්න.",
'movenologin'                  => 'ප්‍රවිෂ්ටවී නොමැත',
'movenologintext'              => 'පිටුවක් ගෙනයෑමට පෙර, ඔබ ලේඛනගත පරිශීලකයෙකු වී [[Special:UserLogin|ප්‍රවිෂ්ට වී]] සිටිය යුතුය.',
'movenotallowed'               => 'පිටු ගෙනයෑමට ඔබ හට අවසර නොමැත.',
'movenotallowedfile'           => 'ගොනු ගෙන යෑමට අවසර ඔබ සතුව නොමැත.',
'cant-move-user-page'          => 'පරිශීලක පිටු ගෙනයෑමට  (උපපිටු වලින් හැරෙන්නට) ඔබ හට අවසර නොමැත.',
'cant-move-to-user-page'       => 'පිටුවක් පරිශීලක පිටුවක් වෙතට ගෙනයෑමට  (පරිශීලක උපපිටුවක් වෙත හැරෙන්නට) ඔබ හට අවසර නොමැත.',
'newtitle'                     => 'නව පිටු නාමය වෙත:',
'move-watch'                   => 'මෙම පිටුව මුර කරන්න',
'movepagebtn'                  => 'පිටුව ගෙන යන්න',
'pagemovedsub'                 => 'ගෙනයාම සාර්ථකයි',
'movepage-moved'               => '\'\'\'"$1" යන පිටුව  "$2"\'\'\' වෙත ගෙන යන ලදි',
'movepage-moved-redirect'      => 'යළි-යොමුවක් නිමැවිණි.',
'movepage-moved-noredirect'    => 'යළි-යොමුවක් නිමැවීම යටපත් කෙරිණි.',
'articleexists'                => 'එක්කෝ මෙම නම ඇති පිටුවක් දැනටමත් පවතී, නැත්නම් ඔබ විසින් තෝරා ගෙන ඇති පිටුව වලංගු එකක් නොවේ.
වෙන යම් නමක් තෝරාගන්න.',
'cantmove-titleprotected'      => 'මෙම පරිස්ථානයට පිටුවක් ගෙනයෑමට ඔබ හට නොහැකි වන්නේ, තැනීමක් සිදුනොකෙරෙන අයුරින් නව ශිර්ෂය රක්ෂණය කර ඇති නිසාය',
'talkexists'                   => "'''මෙම පිටුව සාර්ථක ලෙස ගෙන ගිය නමුදු, සාකච්ඡා පිටුව එසේ ගෙන යාම කල නොහැකි වූයේ නව පිටු නාමයට අදාලව සාකච්ඡා පිටුවක් දැනටමත් පවතින බැවිනි.
කරුණාකර ඒවා හස්තීය ලෙස ඒකාබද්ධ කරන්න.'''",
'movedto'                      => 'වෙත ගෙන යන ලදි',
'movetalk'                     => 'ආශ්‍රිත සාකච්ඡා පිටුව ගෙන යන්න',
'move-subpages'                => 'උපපිටු ($1 දක්වා) ගෙනයන්න',
'move-talk-subpages'           => 'සාකච්ඡා පිටුවෙහි උපපිටු ($1 දක්වා) ගෙනයන්න',
'movepage-page-exists'         => '$1 පිටුව දැනටමත් පවතින අතර, එය ස්වයංක්‍රීයව අධිලිවීමකට භාජනය කල නොහැක.',
'movepage-page-moved'          => ' $1 පිටුව $2 වෙත ගෙනයන ලදි.',
'movepage-page-unmoved'        => ' $1 පිටුව  $2 වෙත ගෙනයෑම සිදුකල නොහැකි විය.',
'movepage-max-pages'           => '{{PLURAL:$1|එක් පිටුවක|පිටු $1 ක}}  උපරිමයකට යටත්ව ගෙනයෑම සිදුකර ඇති අතර ස්වයංක්‍රීය ලෙස ගෙනයෑම තවදුරටත් සිදු නොවනු ඇත.',
'1movedto2'                    => '[[$1]] යන්න [[$2]] වෙත ගෙන යන ලදි',
'1movedto2_redir'              => 'යළි-යොමුකිරීමක් මගින් [[$2]] වෙත  [[$1]] ගෙන යන ලදි',
'move-redirect-suppressed'     => 'යළි-යොමුකිරීම් යටපත් කෙරිණි',
'movelogpage'                  => 'ගෙනයෑම් ලඝු-සටහන',
'movelogpagetext'              => 'පහත දැක්වෙන්නේ ගෙනගිය පිටු ලැයිස්තුවකි.',
'movesubpage'                  => '{{PLURAL:$1|උපපිටුව|උපපිටු}}',
'movesubpagetext'              => 'මෙම පිටුවට, පහත පෙන්වා ඇති  {{PLURAL:$1|උපපිටුව|උපපිටු $1}} ඇත.',
'movenosubpage'                => 'මෙම පිටුව සතුව උපපිටු නොමැත.',
'movereason'                   => 'හේතුව:',
'revertmove'                   => 'ප්‍රතිවර්තනය',
'delete_and_move'              => 'මකාදමා ගෙන යන්න',
'delete_and_move_text'         => '==මකාදැමීම අවශ්‍යව ඇත==
අන්ත පිටුව "[[:$1]]" දැනටමත් පවතියි.
එය මකාදමා ගෙනයාම සඳහා පෙත එළි කිරීමට ඔබ හට ඇවැසිද?',
'delete_and_move_confirm'      => 'ඔව්, පිටුව මකා දමන්න',
'delete_and_move_reason'       => 'ගෙන යෑම සඳහා ඉඩ සලසනු වස් මකාදමන ලදි',
'selfmove'                     => 'මූල හා අන්ත ශීර්ෂ දෙකම එකමය;
පිටුවක් එය වෙතම ගෙන යා නොහැක.',
'immobile-source-namespace'    => '"$1" නාමඅවකාශයෙහි පිටු ගෙනයාම සිදුකල නොහැක',
'immobile-target-namespace'    => '"$1" නාමඅවකාශය වෙත පිටු ගෙනයාම සිදුකල නොහැක',
'immobile-target-namespace-iw' => 'අන්තර්විකී සබැඳිය, පිටු ගෙනයෑම සඳහා නීතික එල්ලයක් නොවේ.',
'immobile-source-page'         => 'මෙම පිටුව ගෙනයාහැක්කක් නොවේ.',
'immobile-target-page'         => 'එම අන්ත ශීර්ෂයට ගෙන යෑම කල නොහැක.',
'imagenocrossnamespace'        => 'ගොනුවක්, ගොනුවක්-නොවන නාමඅවකාශයකට ගෙනයෑම කල නොහැක',
'imagetypemismatch'            => 'නව ගොනු ප්‍රසර්ජනය එහි වර්ගය හා නොගැලපේ',
'imageinvalidfilename'         => 'ඉලක්කගත ගොනු නාමය අනීතිකයි',
'fix-double-redirects'         => 'මුල් ශීර්ෂයට එල්ලවන කිසියම් යළි-යොමුවීම් උඩුගත කරන්න',
'move-leave-redirect'          => 'යළි-යොමුවක් හැර දමන්න',
'protectedpagemovewarning'     => "'''අවවාදයයි:''' පරිපාලක වරප්‍රසාද සතු පරිශීලකයන්ට පමණක් ගෙන යෑ හැකි පරිදී මෙම පිටුව අවුරා ඇත.
ආසන්නතම ලඝු සටහන යොමුවන් සඳහා පහතින් සපයනු ලැබේ:",
'semiprotectedpagemovewarning' => "'''සටහන:''' ලේඛනගත පරිශීලකයන්ට පමණක් ගෙන යෑ හැකි පරිදි මෙම පිටුව අවුරා ඇත.
ආසන්නතම ලඝු සටහන යොමුවන් සඳහා පහතින් සපයනු ලැබේ:",
'move-over-sharedrepo'         => '== ගොනුව පවතී ==
[[:$1]] පවතින්නේ හවුල් ගබඩාවකය.මෙම මාතෘකාවට ගොනුවක් ගෙනයාම හවුල් ගොනුව යටපත් කරනු ඇත.',
'file-exists-sharedrepo'       => 'තෝරා ගත් ගොනු නාමය දැනටම  හවුල් ගබඩාවක භාවිතා වේ.
කරුණාකර වෙනත් නමක් තෝරන්න.',

# Export
'export'            => 'පිටු නිර්යාත කරන්න',
'exporttext'        => 'යම් XML යක වෙළා ඇති කිසියම් සුවිශේෂී පිටුවක හෝ පිටු සමූහයක හෝ පෙළ හා සංස්කරණ ඉතිහාසය නිර්යාත කිරීමට ඔබට හැක.
[[Special:Import|පිටුව ආයාත කරන්න]] හා සමගින් මාධ්‍යවිකි භාවිතයෙන් වෙනත් විකියකට මෙය ආයාත කල හැක.

පිටු නිර්යාත කිරීම සඳහා, පහත පෙළ කොටුවේ, එක් පේළියකට එක් ශීර්ෂයක් වන පරිදී ශීර්ෂයන් ඇතුළු කොට, ඔබට ඇවැසි වන්නේ,  පිටු ඉතිහාස පේළි හා සමගින් සියළු පැරැණි අනුවාදයන් මෙන්ම වත්මන් අනුවාදයද  නැතහොත් අවසන් සංස්කරණය පිළිබඳ තොරතුරු සමග වත්මන් අනුවාදයද යන වග තෝරාගත යුතුය.

අපරෝක්ත අවස්ථාවෙහිදී ඔබහට සබැඳියක්ද භාවිතා කල හැක, නිද. "[[{{MediaWiki:Mainpage}}]]" පිටුව සඳහා [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'සම්පූර්ණ ඉතිහාසය නොව වත්මන් සංශෝධනය පමණක් අඩංගු කරන්න',
'exportnohistory'   => "----
'''සටහන:''' කාර්යසාධන හේතූන් නිසාවෙන් මෙම ආකෘති පත්‍රය භාවිතයෙන් පිටුවල සම්පූර්ණ ඉතිහාසය නිර්යාත කිරීම අක්‍රීය කොට ඇත.",
'export-submit'     => 'නිර්යාත',
'export-addcattext' => 'ප්‍රවර්ගයෙන් පිටු එනතු කරන්න:',
'export-addcat'     => 'එක් කරන්න',
'export-addnstext'  => 'නාමඅවකාශය වෙතින් පිටු එකතු කරන්න:',
'export-addns'      => 'එකතු කරන්න',
'export-download'   => 'ගොනුවක් ලෙස සුරකින්න',
'export-templates'  => 'සැකිලි ඇතුළත් කරන්න',
'export-pagelinks'  => 'මෙම මට්ටම දක්වා සබැඳි පිටු අන්තර්ගත කරන්න:',

# Namespace 8 related
'allmessages'                   => 'පද්ධති පණිවුඩ',
'allmessagesname'               => 'නම',
'allmessagesdefault'            => 'පෙරනිමි පෙළ',
'allmessagescurrent'            => 'වත්මන් පෙළ',
'allmessagestext'               => 'මේ මාධ්‍යවිකි නාමඅවකාශයෙහිදී  හමුවන පද්ධති පණිවුඩයන් ලැයිස්තුවකි.
වර්ගීය මාධ්‍යවිකි ප්‍රාදේශීයකරණයට දායක වීමට ඔබ රිසි නම් කරුණාකර [http://www.mediawiki.org/wiki/Localisation මාධ්‍යවිකි ප්‍රාදේශීයකරණය]  සහ [http://translatewiki.net බීටාවිකි] වෙත පිවිසෙන්න.',
'allmessagesnotsupportedDB'     => "'''\$wgUseDatabaseMessages''' අක්‍රීය කොට ඇති බැවින්, මෙම පිටුව භාවිතා කල නොහැක.",
'allmessages-filter-legend'     => 'පෙරහන',
'allmessages-filter'            => 'පාරිභෝගීකරණ තත්ත්වය අනුව පෙරීම:',
'allmessages-filter-unmodified' => 'වෙනසකට නතු නොකල',
'allmessages-filter-all'        => 'සියල්ල',
'allmessages-filter-modified'   => 'වෙනසකට නතු කල',
'allmessages-prefix'            => 'උපසර්ගය පරිදී පෙරීම:',
'allmessages-language'          => 'භාෂාව:',
'allmessages-filter-submit'     => 'යන්න',

# Thumbnails
'thumbnail-more'           => 'විශාලනය කිරීම',
'filemissing'              => 'ගොනුව දක්නට නොමැත',
'thumbnail_error'          => 'සිඟිති-රූපයක් තැනීමෙහිදී ඇතිවූ දෝෂය: $1',
'djvu_page_error'          => 'සීමාව ඉක්මවා ගිය DjVu පිටුව',
'djvu_no_xml'              => 'XML හෝ  DjVu හෝ ගොනුව අත්කරගැනුමට නොහැකි විය',
'thumbnail_invalid_params' => 'සිඟිති-රූපයේ පරාමිතික අනීතිකයි',
'thumbnail_dest_directory' => 'අන්ත ඩිරෙක්ටරිය තැනීම කල නොහැක',
'thumbnail_image-type'     => 'රූප වර්ගය සඳහා අනුග්‍රහය සපයනු නොලැබේ',
'thumbnail_gd-library'     => 'GD පුස්තකාල වින්‍යාසය අසම්පූර්ණයි: අඩුපාඩු ශ්‍රිතය $1',
'thumbnail_image-missing'  => 'ගොනුව සොයාගත නොහැකි බවක් පෙන්නුම් කරයි: $1',

# Special:Import
'import'                     => 'පිටු ආයාත කරන්න',
'importinterwiki'            => 'අන්තර්විකී ආයාතය',
'import-interwiki-text'      => 'ආයාත කිරීම සඳහා විකියක් හා පිටු ශීර්ෂයක් තෝරාගන්න.
සංශෝධන දිනයන් හා සංස්කාරකවරුන්ගේ නම් සංරක්‍ෂණය කෙරෙනු ඇත.
සියළු අන්තර්විකි ආ‍යාත ක්‍රියාවන් [[Special:Log/import|ආයාත ලඝු-සටහනෙහි]] සටහන් වනු ඇත.',
'import-interwiki-source'    => 'මූලාශ්‍ර විකිය/පිටුව:',
'import-interwiki-history'   => 'මෙම පිටුව සඳහා සියළු ඉතිහාස අනුවාදයන් පිටපත් කරන්න',
'import-interwiki-templates' => 'සියළු සැකිලි අන්තර්ගත කරන්න',
'import-interwiki-submit'    => 'ආයාත කරන්න',
'import-interwiki-namespace' => 'ගමනාන්ත නාමඅවකාශය:',
'import-upload-filename'     => 'ගොනු-නාමය:',
'import-comment'             => 'පරිකථනය:',
'importtext'                 => '[[Special:Export|නිර්යාත උපයුක්තය]] භාවිතා කරමින් ගොනුව මූල විකියෙන් නිර්යාත කිරීමට කාරුණික වන්න.
ඔය ඔබගේ පරිගණකයෙහි සුරැක මෙහි උඩුගත කරන්න.',
'importstart'                => 'පිටු ආයාත කරමින්...',
'import-revision-count'      => ' {{PLURAL:$1|සංශෝධනය|සංශෝධන $1 ක්}}',
'importnopages'              => 'ආයාත කිරීමට කිසිදු පිටුවක් නොමැත.',
'imported-log-entries'       => '{{PLURAL:$1|එක් ලඝු සටහනක්|ලඝු සටහන් $1 ක්}} ආනයනය කෙරිණි.',
'importfailed'               => 'ආයාත කිරීම  අසාර්ථකයි: <nowiki>$1</nowiki>',
'importunknownsource'        => 'අඥාත ආයාත මූලාශ්‍ර වර්ගය',
'importcantopen'             => 'ආයාත ගොනුව විවෘත කිරීමට නොහැකි විය',
'importbadinterwiki'         => 'අයෝග්‍ය අන්තර්විකි සබැඳියක්',
'importnotext'               => 'හිස් හෝ පෙළක් නොමැති',
'importsuccess'              => 'ආයාත කිරීම අවසානයි!',
'importhistoryconflict'      => 'පරස්පර ඉතිහාස සංශෝධන පවතියි (මෙම පිටුව මින් පෙර ආයාත කෙරෙන්නට ඇත)',
'importnosources'            => 'අන්තර්විකි ආයාත මූලයන් කිසිවක් අර්ථදක්වා නොමැති අතර සෘජු ඉතිහාස උඩුගතකිරීම් අක්‍රීය කොට ඇත.',
'importnofile'               => 'ආයාත ගොනු කිසිවක් උඩුගත නොකෙරිණි.',
'importuploaderrorsize'      => 'ආයාත ගොනුව උඩුගත කෙරුම අසාර්ථක විය.
අනුදෙන උඩුගත විශාලත්වයට වඩා ගොනුව විශාලය.',
'importuploaderrorpartial'   => 'ආයාත ගොනුව උඩුගත කෙරුම අසාර්ථක විය.
ගොනුව උඩුගත කෙරුම භාගික වශයෙන් පමණක් සිදුවී ඇත.',
'importuploaderrortemp'      => 'ආයාත ගොනුව උඩුගත කෙරුම අසාර්ථක විය.
තාවකාලික ගොනුවක් සොයාගත නොහැකි විය.',
'import-parse-failure'       => 'XML ආයාත ව්‍යාකරණ විග්‍රහ අසමර්ථය',
'import-noarticle'           => 'ආයාත කිරීමට පිටු නොමැත!',
'import-nonewrevisions'      => 'සියළු සංශෝධනයන් පෙරදී ආයාත කරන ලදි.',
'xml-error-string'           => '$2 පේළියෙහි, $3 තීරුවෙහි $1 ($4 බයිට්): $5',
'import-upload'              => 'XML දත්ත උඩුගත කරන්න',
'import-token-mismatch'      => 'සැසි දත්ත හානියකි.
කරුණාකර නැවත උත්සාහ කරන්න.',
'import-invalid-interwiki'   => 'සඳහන් කර ඇති විකියෙන් ආයාත කිරීම සිදු කල නොහැක.',

# Import log
'importlogpage'                    => 'ලඝු-සටහන් ආයාත කරන්න',
'importlogpagetext'                => 'අනෙකුත් විකියන්ගෙන් සංස්කරණ ඉතිහාසයන් ඇති පිටු වල පරිපාලනමය ආයාත කිරීම්.',
'import-logentry-upload'           => 'ගොනු උඩුගත කිරීමක් මගින් [[$1]] ආයාත කෙරිණි',
'import-logentry-upload-detail'    => ' {{PLURAL:$1|සංශෝධනය|සංශෝධන $1 ක්}}',
'import-logentry-interwiki'        => '$1 අන්තර්විකීකරණය කරන ලදි',
'import-logentry-interwiki-detail' => '$2 වෙතින් {{PLURAL:$1|එක් සංශෝධනයක්|සංශෝධන $1 ක්}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ඔබගේ පරිශීලක පිටුව',
'tooltip-pt-anonuserpage'         => 'සංස්කරණයට ඔබ භාවිතා කරමින් පවතින අන්තර්ජාල ලිපිනය සඳහා පරිශීලක පිටුව',
'tooltip-pt-mytalk'               => 'ඔබගේ සාකච්ඡා පිටුව',
'tooltip-pt-anontalk'             => 'මෙම අන්තර්ජාල ලිපිනයෙන් කර ඇති සංස්කරණයන් පිළිබඳ සංවාදය',
'tooltip-pt-preferences'          => 'මගේ අභිරුචි',
'tooltip-pt-watchlist'            => 'වෙනස්වීම් සිදුවී තිබේදැයි යන්න පිලිබඳව ඔබගේ විමසුමට ලක්ව ඇති පිටු ලැයිස්තුව',
'tooltip-pt-mycontris'            => 'ඔබගේ දායකත්වයන් ලැයිස්තුව‍',
'tooltip-pt-login'                => 'එය අවශ්‍ය‍යෙන් කල යුත්තක් ‍නොවුනද, ප්‍රවිෂ්ට වීම සඳහා ඔබ ධෛර්යමත් කරනු ලැබේ.',
'tooltip-pt-anonlogin'            => 'එය අවශ්‍ය‍යෙන් කල යුත්තක් ‍නොවුනද, ප්‍රවිෂ්ට වීම සඳහා ඔබ ධෛර්යමත් කරනු ලැබේ.',
'tooltip-pt-logout'               => 'නිෂ්ක්‍රමණය',
'tooltip-ca-talk'                 => 'අන්තර්ගත පිටුව පිළිබඳ සංවාදය',
'tooltip-ca-edit'                 => 'ඔබ‍ට මෙම පිටුව සංස්කරණය කල හැක.
සුරැකීමට පෙර කරුණාකර පෙරදසුන බොත්තම භාවිතා කරන්න.',
'tooltip-ca-addsection'           => 'නව ඡේදයක් අරඹන්න',
'tooltip-ca-viewsource'           => 'මෙම පිටුව ආරක්‍ෂණය කොට ඇත.
ඔබට එහි මූලාශ්‍රය නැරඹිය හැක.',
'tooltip-ca-history'              => 'මෙම පිටුවේ පැරණි අනුවාදයන්.',
'tooltip-ca-protect'              => 'මෙම පිටුව ආරක්‍ෂණය කරන්න',
'tooltip-ca-unprotect'            => 'මෙම පිටුව නොමසුරකින්න',
'tooltip-ca-delete'               => 'මේ පිටුව මකන්න',
'tooltip-ca-undelete'             => 'මෙම පිටුව මකා දැමීමට පෙර එයට සිදුකල සංස්කරණයන් නැවත ප්‍රතිෂ්ඨාපනය කරන්න',
'tooltip-ca-move'                 => 'මෙම පිටුව ගෙන යන්න',
'tooltip-ca-watch'                => 'මෙම පිටුව ඔබගේ මුර-ලැයිස්තුවට එක් කරන්න',
'tooltip-ca-unwatch'              => 'මෙම පිටුව ඔබගේ මුර-ලැයිස්තුවෙන් ඉවත් කරන්න',
'tooltip-search'                  => '{{SITENAME}} ගවේෂණය',
'tooltip-search-go'               => 'මෙම නාමයට තථ්‍ය ලෙස ගැලපෙන පිටුවක් ඇත්නම් එය වෙත යන්න',
'tooltip-search-fulltext'         => 'මෙම පෙළ අඩංගු පිටු ගවේෂණය කරන්න',
'tooltip-p-logo'                  => 'මුල් පිටුව',
'tooltip-n-mainpage'              => 'මුල් පිටුව‍ට පිවිසෙන්න',
'tooltip-n-mainpage-description'  => 'ප්‍රධාන පිටුවට ගොඩ වදින්න',
'tooltip-n-portal'                => 'ව්‍යාපෘතියට අදාළව, ඔබට සිදුකල හැකි දෑ, අවශ්‍ය දෑ සොයා ගත හැකි අයුරු, යනාදී වැදගත් තොරතුරු',
'tooltip-n-currentevents'         => 'කාලීන සිදුවීම් පිළිබඳ පසුබිම් තොරතුරු සොයා දැනගන්න',
'tooltip-n-recentchanges'         => 'විකියෙහි මෑත වෙනස්වීම් දැක්වෙන ලැයිස්තුවක්.',
'tooltip-n-randompage'            => 'අහුඹු පිටුවක් ප්‍රවේශනය කරන්න (බා ගන්න)',
'tooltip-n-help'                  => 'තොරතුරු නිරාවරණය කර ගත හැකි තැන.',
'tooltip-t-whatlinkshere'         => 'මෙය හා සබැ‍ඳෙන සියළු විකි පිටු ලැයිස්තුව',
'tooltip-t-recentchangeslinked'   => 'මෙම පිටුව හා සබැඳි පිටුවල මෑත වෙනස්වීම්',
'tooltip-feed-rss'                => 'මෙම පිටුව සඳහා RSS පෝෂකය',
'tooltip-feed-atom'               => 'මෙම පිටුව සඳහා Atom පෝෂකය',
'tooltip-t-contributions'         => 'මෙම පරිශීලකයාගේ දායකත්ව ලැයිස්තුව නරඹන්න',
'tooltip-t-emailuser'             => 'මෙම පරිශීලකයාට විද්‍යුත්-තැපෑලක් යවන්න',
'tooltip-t-upload'                => 'ගොනු උඩුගත කරන්න',
'tooltip-t-specialpages'          => 'සියලු විශේෂ පිටු ලැයිස්තුව',
'tooltip-t-print'                 => 'මෙම පිටුවෙහි මුද්‍රණය කල හැකි අනුවාදය',
'tooltip-t-permalink'             => 'පිටුවෙහි මෙම අනුවාදයට, ස්ථාවර බැඳිය',
'tooltip-ca-nstab-main'           => 'අන්තර්ගත පිටුව නරඹන්න',
'tooltip-ca-nstab-user'           => 'පරිශීලක පිටුව නරඹන්න',
'tooltip-ca-nstab-media'          => 'මාධ්‍ය පිටුව නරඹන්න',
'tooltip-ca-nstab-special'        => 'මෙය විශේෂ පිටුවකි, එයම සංස්කරණය කිරීමට ඔබට නොහැක',
'tooltip-ca-nstab-project'        => 'ව්‍යාපෘති පිටුව නරඹන්න',
'tooltip-ca-nstab-image'          => 'ගොනු පිටුව නරඹන්න',
'tooltip-ca-nstab-mediawiki'      => 'පද්ධති පණිවුඩය නරඹන්න',
'tooltip-ca-nstab-template'       => 'සැකිල්ල නරඹන්න',
'tooltip-ca-nstab-help'           => 'උදවු පිටුව නරඹන්න',
'tooltip-ca-nstab-category'       => 'ප්‍රවර්ග පිටුව නරඹන්න',
'tooltip-minoredit'               => 'මෙය සුළු සංස්කරණයක් ලෙස සනිටුහන් කරගන්න',
'tooltip-save'                    => 'වෙනස්කිරීම් සුරකින්න',
'tooltip-preview'                 => 'ඔබ විසින් කල  වෙනස් වීම් පෙර-දසුන් කර, ඉන් අනතුරුව සුරැකීම සිදුකිරීමට කාරුණික වන්න!',
'tooltip-diff'                    => 'පෙළෙහි ඔබ සිදුකල වෙනස්වීම් මොනවාදැයි හුවා දක්වන්න.',
'tooltip-compareselectedversions' => 'මෙම පිටුවෙහි, තෝරාගෙන ඇති අනුවාද දෙක අතර වෙනස්කම් බලන්න.',
'tooltip-watch'                   => 'මෙම පිටුව ඔබගේ මුර-ලැයිස්තුවට එක් කරන්න',
'tooltip-recreate'                => 'පිටුව මකාදමා ඇති වුවද, එය යළි-නිර්මාණය කරන්න',
'tooltip-upload'                  => 'උඩුගත කිරීම අරඹන්න',
'tooltip-rollback'                => '"පුනරාවර්තනය" එක් වරක් ක්ලික් කිරීමෙහි ප්‍රතිඵලය වනුයේ, සංස්කරණය(න්) ප්‍රතිවර්තනය වී, අවසන් දායකයා විසින් සැදූ මෙම පිටුව වෙත පිටුව ගෙන ඒමයි.',
'tooltip-undo'                    => '"අහෝසි" මගින් සිදුකෙරෙනුයේ මෙම සංස්කරණය ප්‍රතිවර්තනය කොට, සංස්කරණ-ආකෘතිය, පෙරදසුන් මාදිලියෙහි විවෘත කිරීමයි.
සාරාංශයෙහි, මේ පිළිබඳව හේතුවක් පල කිරීමට, ඔබට ඉඩ සැලසේ.',
'tooltip-preferences-save'        => 'අභිරුචීන් සුරකින්න',
'tooltip-summary'                 => 'කෙටි සාරාංශයක් ඇතුළත් කරන්න',

# Stylesheets
'common.css'      => '/* මෙහි CSS  බහාලීම සියළු ඡවියයන් භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'standard.css'    => '/* මෙහි CSS  බහාලීම සම්මත ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'nostalgia.css'   => '/* මෙහි CSS  බහාලීම පිළිසැමරුම් ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'cologneblue.css' => '/* මෙහි CSS  බහාලීම සිහිල්-සුවඳ-පැන් ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'monobook.css'    => '/* මෙහි CSS  බහාලීම ඒකායන ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'myskin.css'      => '/* මෙහි CSS  බහාලීම මගේ-ඡවිය ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'chick.css'       => '/* මෙහි CSS  බහාලීම හැඩකාරී ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'simple.css'      => '/* මෙහි CSS  බහාලීම සරල ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'modern.css'      => '/* මෙහි CSS  බහාලීම නූතන ඡවිය භාවිතා කරන පරිශීලකයන් හට බලපෑම් සිදු කල හැක */',
'print.css'       => '/* මෙහි CSS  බහාලීම මුද්‍රණ ප්‍රතිදානයට බලපෑම් සිදු කල හැක */',
'handheld.css'    => '/* මෙහි බහාලන CSS විසින් $wgHandheldStyle හි වින්‍යාසකෙරෙන ජවිය මත පදනම් වූ අතේ ගෙන යා හැකි උපකරණ වලට බලපෑම් කල හැක*/',

# Scripts
'common.js'      => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් සෑම පිටු ප්‍රවේශනයකදීම සියළු පරිශීලකයන්හට ප්‍රවේශනය කෙරේ. */',
'standard.js'    => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් සම්මත ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'nostalgia.js'   => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් පිළි සැමරුම් ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'cologneblue.js' => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් සිහිල්-සුවඳ-පැන් ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'monobook.js'    => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් ඒකායන ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'myskin.js'      => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් මගේ-ඡවිය ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'chick.js'       => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් හැඩකාරී ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'simple.js'      => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් සරල ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',
'modern.js'      => '/* මෙහි ඕනෑම ජාවාස්ක්‍රිප්ට් එකක් නූතන ඡවිය භාවිතා කරන පරිශීලකයන්හට ප්‍රවේශනය කෙරේ */',

# Metadata
'nodublincore'      => 'ඩබ්ලින් කොර් RDF පාරදත්ත මෙම සේවාදායකයෙහි අක්‍රීය කොට ඇත.',
'nocreativecommons' => 'ක්‍රියේටිව් කොමන්ස් RDF පාරදත්ත මෙම සේවාදායකයෙහි අක්‍රීය කොට ඇත.',
'notacceptable'     => 'ඔබගේ සේවාලාභියාට කියැවිය හැකි ආකෘතියකින් දත්ත සැපැයීමට විකි සේවාදායකයට නොහැක.',

# Attribution
'anonymous'        => '{{SITENAME}} හි නිර්නාමික {{PLURAL:$1|පරිශීලකයා|පරිශීලකයෝ}}',
'siteuser'         => '{{SITENAME}} පරිශීලක $1',
'anonuser'         => '{{SITENAME}} නිර්නාමික පරිශීලක $1',
'lastmodifiedatby' => 'මෙම පිටුව අවසන් වරට විකරණය කරන ලද්දේ  $3 විසින්  $1 දින  $2 වේලාවේදීය .',
'othercontribs'    => '$1ගේ කෘතිය මත පදනම් විය.',
'others'           => 'අනෙකුන්',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|පරිශීලකයා|පරිශීලකයෝ}} $1',
'anonusers'        => '{{SITENAME}} නිර්නාමික {{PLURAL:$2|පරිශිලකයා|පරිශීලකයින්}} $1',
'creditspage'      => 'පිටුවෙහි කතෘ-බුහුමන්',
'nocredits'        => 'මෙම පිටුව සඳහා ස්තුතිපූර්වක තොරතුරු කිසිවක් නැත.',

# Spam protection
'spamprotectiontitle' => 'අයාචිත-තැපෑලෙන් රැකවරණය සපයන පෙරහන',
'spamprotectiontext'  => 'ඔබ හට සුරැකීමට අවශ්‍ය පිටුව අයාචිත-තැපැල් පෙරහන විසින් වාරණය කෙරිණි.
මෙය බොහෝදුරට අපලේඛිත බාහිර අඩවියක් වෙත වූ සබැඳියක් හේතුවෙන් සිදුවන්නට ඇත.',
'spamprotectionmatch' => 'ඔබගේ අයාචිත-තැපෑල  පෙරහන පූරනය කර ඇත්තේ ඉදිරියේ දැක්වෙන පෙළය: $1',
'spambot_username'    => 'මාධ්‍යවිකි අයාචිත-තැපෑල ශෝධනය',
'spam_reverting'      => ' $1 හට සබැඳියන් නොමැති අවසන් අනුවාදය වෙත ප්‍රතිවර්තනය වෙමින්',
'spam_blanking'       => 'සියළු සංශෝධනයන්හි  $1 වෙතවූ සබැඳියන් අඩංගු විය, හිස්කරමින්',

# Info page
'infosubtitle'   => 'පිටුව සඳහා විස්තර',
'numedits'       => 'සංස්කරණ ගණන (පිටුව): $1',
'numtalkedits'   => 'සංස්කරණ ගණන  (සංවාද පිටුව): $1',
'numwatchers'    => 'මුරකරන්නන් ගණන: $1',
'numauthors'     => 'ප්‍රභින්න කතෘවරුන් ගණන (පිටුව): $1',
'numtalkauthors' => 'ප්‍රභින්න කතෘවරුන් ගණන (සංවාද පිටුව): $1',

# Math options
'mw_math_png'    => 'සැමවිට PNG ලෙසට විදැහන්න',
'mw_math_simple' => 'ඉතා සරල නම් HTML එසේ නොමැති නම් PNG',
'mw_math_html'   => 'හැකි නම් HTML එසේ නොමැති නම් PNG',
'mw_math_source' => 'TeX  ලෙසින් පැවැතීමට හරින්න(පෙළ බ්‍රවුසරයන් සඳහා)',
'mw_math_modern' => 'නවීන බ්‍රවුසරයන් සඳහා නිර්දේශ කෙරේ',
'mw_math_mathml' => 'හැකි නම් MathML (පරීක්ෂණාත්මක)',

# Math errors
'math_failure'          => 'ව්‍යාකරණ විග්‍රහය අසමත් විය',
'math_unknown_error'    => 'අඥාත දෝෂය',
'math_unknown_function' => 'අඥාත ශ්‍රිතය',
'math_lexing_error'     => 'රීතිමය දෝෂයකි',
'math_syntax_error'     => 'කාරක-රීති දෝෂය',
'math_image_error'      => 'PNG අන්වර්තනය අසාර්ථකවිය;
latex, dvips, gs, හා convert හී නිදොස්  ස්ථාපනය සිදුවී ඇතිදැයි පිරික්සන්න',
'math_bad_tmpdir'       => 'ගණිත තාවකාලික ඩිරෙක්ටරිය තැනීමට හෝ එයට ලිවීමට නොහැක',
'math_bad_output'       => 'ගණිත ප්‍රතිදාන ඩිරෙක්ටරිය තැනීමට හෝ එයට ලිවීමට නොහැක',
'math_notexvc'          => 'texvc අභිවාහකය දක්නට නොමැත;
වින්‍යාස කෙරුමට කරුණාකර math/README බලන්න.',

# Patrolling
'markaspatrolleddiff'                 => 'පරික්ෂාකර බැලූ ලෙස සලකුණු කරන්න',
'markaspatrolledtext'                 => 'මෙම පිටුව පරික්‍ෂාකර බැලූ ලෙස සලකුණු කරන්න',
'markedaspatrolled'                   => 'පරික්‍ෂෘකර බැලූ ලෙස සලකුණු කරන්න',
'markedaspatrolledtext'               => '[[:$1]]  හි තෝරාගත් සංශෝධනය පරික්‍ෂාකර බැලූ ලෙස හා විමසූ ලෙස සලකුණු කර ඇත.',
'rcpatroldisabled'                    => 'මෑත වෙනස්වීම් පරික්ෂාකිරීමේ අංගය අක්‍රීය කොට ඇත',
'rcpatroldisabledtext'                => 'මෑත වෙනස්වීම් පරික්ෂාකිරීමේ අංගය දැනට අක්‍රීය කොට ඇත.',
'markedaspatrollederror'              => 'පරික්‍ෂාකර බැලූ ලෙස සලකුණු කල නොහැක',
'markedaspatrollederrortext'          => 'පරික්‍ෂාකර බැලූ ලෙස සලකුණු කිරීම සඳහා ඔබ විසින් සංශෝධනයක් හුවා දැක්විය යුතුය.',
'markedaspatrollederror-noautopatrol' => 'ඔබගේ ස්වීය වෙනස්වීම් පරික්‍ෂාකර බැලූ ලෙස සලකුණු කිරීමට ඔබ හට ඉඩ දෙනු නොලැබේ.',

# Patrol log
'patrol-log-page'      => 'පරික්ෂාකිරීම් ලඝු-සටහන',
'patrol-log-header'    => 'මෙය පරික්‍ෂාකර බැලූ සංශෝධනයන්ගේ ලඝු-සටහනකි.',
'patrol-log-line'      => '$2 හි $1 පරික්ෂා කර බැලූ බව $3 හි ලකුණු කෙරිණි',
'patrol-log-auto'      => '(ස්වයංක්‍රීය)',
'patrol-log-diff'      => 'r$1',
'log-show-hide-patrol' => 'පරික්‍ෂාකිරීම් ලඝු-සටහන් $1',

# Image deletion
'deletedrevision'                 => 'පැරැණි සංශෝධනය $1 මකාදමන ලදි',
'filedeleteerror-short'           => 'ගොනුව මකාදැමීමේ දෝෂය: $1',
'filedeleteerror-long'            => 'ගොනුව මකාදැමීමේදී දෝෂයන් හමුවුණි:

$1',
'filedelete-missing'              => 'එය නොපවතින නිසාවෙන්  "$1" ගොනුව මකාදැමිය නොහැක.',
'filedelete-old-unregistered'     => 'නිරූපිත  "$1" ගොනු සංශෝධනය දත්ත-ගබඩාවෙහි නොමැත.',
'filedelete-current-unregistered' => 'නිරූපිත "$1" ගොනුව දත්ත-ගබඩාවෙහි නොමැත.',
'filedelete-archive-read-only'    => 'වෙබ්සේවාදායකය විසින්  "$1"සංරක්ෂික විරෙක්ටරියට ලිවීම සිදුකල නොහැක.',

# Browsing diffs
'previousdiff' => '← පැරැණි සංස්කරණය',
'nextdiff'     => 'නවීන සංස්කරණය →',

# Media information
'mediawarning'         => "'''අවවාදයයි''': අනිෂ්ට කේතයන් මෙම ගොනුවෙහි අඩංගු විය හැක.
එය ක්‍රියාත්මක කිරීමෙන්, ඔබගේ පද්ධතියට හානිවිය හැක.",
'imagemaxsize'         => "රූප ප්‍රමාණ සීමාව:<br />''(ගොනු විස්තර පිටු සඳහා)''",
'thumbsize'            => 'සිඟිති-රූපයේ විශාලත්වය:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|පිටුව|පිටු}}',
'file-info'            => '(ගොනු විශාලත්වය: $1, MIME වර්ගය: $2)',
'file-info-size'       => '($1 × $2 පික්සල, ගොනු විශාලත්වය: $3, MIME ශෛලිය: $4)',
'file-nohires'         => '<small>උච්චතර විසර්ජනය දක්වා එළඹිය නොහැක.</small>',
'svg-long-desc'        => '(SVG ගොනුව, නාමමාත්‍රිකව $1 × $2 පික්සල්, ගොනු විශාලත්වය: $3)',
'show-big-image'       => 'සම්පූර්ණ විසර්ජනය (Full resolution)',
'show-big-image-thumb' => '<small>පෙර නැරඹුමෙහි  විශාලත්වය: $1 × $2 පික්සල</small>',
'file-info-gif-looped' => 'වලිත',
'file-info-gif-frames' => '$1 {{PLURAL:$1රාමුව|රාමු}}',
'file-info-png-frames' => '↓ $1 {{PLURAL:$1|රාමුව|රාමු}}',

# Special:NewFiles
'newimages'             => 'නව ගොනු ගැලරිය',
'imagelisttext'         => "පහත දැක්වෙන්නේ  $2 අනුව සුබෙදුනු {{PLURAL:$1|ගොනුවක|ගොනු '''$1''' ක}} ලැයිස්තුවකි.",
'newimages-summary'     => 'මෙම විශේෂ පිටුව, අවසානයට උඩුගත කෙරුණු ගොනු පෙන්වයි.',
'newimages-legend'      => 'පෙරහන',
'newimages-label'       => 'ගොනු නාමය (හෝ එයින් කොටසක්):',
'showhidebots'          => '(රොබෝ $1 දෙනෙක්)',
'noimages'              => 'පෙනෙන්නට කිසිවක් නොමැත.',
'ilsubmit'              => 'ගවේෂණය',
'bydate'                => 'දිනය ප්‍රකාර',
'sp-newimages-showfrom' => ' $2, $1 සිට බලපැවැත්වෙන නව ගොනු පෙන්වන්න',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 'තත්',
'minutes-abbrev' => 'විනා',
'hours-abbrev'   => 'පැය',

# Bad image list
'bad_image_list' => 'ආකෘතිය පහත පෙන්වා ඇති පරිදි වේ:

ලැයිස්තු අයිතම පමණක් (* යන්නෙන් ආරම්භ වන්නාවූ පේළි) සළකා බලනු ලැබේ.
පේළියක පළමු සබැඳිය සදොස් ගොනුවකට යොමු වන සබැඳියක් විය යුතුය.
එම පේළියෙහිම ඉනික්බිති හමුවන ඕනෑම සබැඳියක් සලකනු ලබන්නේ ව්‍යහිවාරයක් ලෙසටය, එනම්, ගොනු එක පේළියට පැවතිය හැකි පිටු.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn'   => 'cn',
'variantname-zh-tw'   => 'tw',
'variantname-zh-hk'   => 'hk',
'variantname-zh-mo'   => 'mo',
'variantname-zh-sg'   => 'sg',
'variantname-zh-my'   => 'my',
'variantname-zh'      => 'zh',

# Variants for Serbian language
'variantname-sr-ec' => 'sr-ec',
'variantname-sr-el' => 'sr-el',
'variantname-sr'    => 'sr',

# Variants for Kazakh language
'variantname-kk-kz'   => 'kk-kz',
'variantname-kk-tr'   => 'kk-tr',
'variantname-kk-cn'   => 'kk-cn',
'variantname-kk-cyrl' => 'kk-cyrl',
'variantname-kk-latn' => 'kk-latn',
'variantname-kk-arab' => 'kk-arab',
'variantname-kk'      => 'kk',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',
'variantname-ku-latn' => 'ku-Latn',
'variantname-ku'      => 'ku',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'tg-Cyrl',
'variantname-tg-latn' => 'tg-Latn',
'variantname-tg'      => 'tg',

# Metadata
'metadata'          => 'පාරදත්ත (Metadata)',
'metadata-help'     => 'සමහරවිට ඩිජිටල් කැමරාවක් හෝ ස්කෑනරයක් හෝ භාවිතයෙන්, නිමැවා හෝ සංඛ්‍යාංකකරණය (ඩිජිටල්කරණය) කොට එක් කල , අමතර තොරතුරු මෙම ගොනුවේ අඩංගුය.
ගොනුව මුලින්ම පැවැති තත්ත්වයෙහි සිට විකරණය කොට තිබේ නම්, සමහරක් තොරතුරු විකරිත ගොනුව පූර්ණ වශයෙන් පිළිඹිමු නොකරනු ඇත.',
'metadata-expand'   => 'විස්තීරණය කරන ලද විස්තර පෙන්වන්න',
'metadata-collapse' => 'විස්තීරණය කරන ලද විස්තර සඟවන්න',
'metadata-fields'   => 'පාරදත්ත වගුව බිඳවැටෙන විට, මෙම පණිවුඩයෙහි ලැයිස්තු ගත කොට ඇති  EXIF පාරදත්ත ක්ෂේත්‍රයන් රූප පිටු ප්‍රදර්ශනයෙහි ඇතුළත් කෙරෙයි.
අනෙක්වා ‍‍ පෙරනිමියෙන් සඟවනු ලැබේ.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'පළල',
'exif-imagelength'                 => 'උස',
'exif-bitspersample'               => 'එක් සංරචකයකට බිට් ගණන',
'exif-compression'                 => 'හැකිලීම් පටිපාටිය',
'exif-photometricinterpretation'   => 'පික්සල සංයුතිය',
'exif-orientation'                 => 'දිශානුයෝජනය',
'exif-samplesperpixel'             => 'සංරචක සංඛ්‍යාව',
'exif-planarconfiguration'         => 'දත්ත වින්‍යාසය',
'exif-ycbcrsubsampling'            => 'Y හා C අතර උපනියැදීම් අනුපාතය',
'exif-ycbcrpositioning'            => 'Y හා C පිහිටුම්',
'exif-xresolution'                 => 'තිරස් විසර්ජනය',
'exif-yresolution'                 => 'සිරස් විසර්ජනය',
'exif-resolutionunit'              => 'X හා Y විසර්ජනයන්හි ඒකක',
'exif-stripoffsets'                => 'රූප දත්ත පරිස්ථානය',
'exif-rowsperstrip'                => 'එක් තීරයකට පේළි ගණන',
'exif-stripbytecounts'             => 'එක් සම්පීඩිත පේළියකට බයිට් ගණන',
'exif-jpeginterchangeformat'       => 'JPEG SOI වෙත හිලව්ව',
'exif-jpeginterchangeformatlength' => 'JPEG දත්ත බයිට් ගණන',
'exif-transferfunction'            => 'සමර්පණ ශ්‍රිතය',
'exif-whitepoint'                  => 'ශ්වේත ලක්ෂ්‍යය වර්ණවත්භාවය',
'exif-primarychromaticities'       => 'ප්‍රාථමික වර්ණයන්ගේ වර්ණවත්භාවයන්',
'exif-ycbcrcoefficients'           => 'වර්ණ අවකාශ පරිණාමණ න්‍යාස සංගුණක',
'exif-referenceblackwhite'         => 'කළු හා සුදු සමුද්දේශ අගයන් යුගලයක්',
'exif-datetime'                    => 'ගොනුව  වෙනස්කල  දින හා වේලාව',
'exif-imagedescription'            => 'රූප ශීර්ෂය',
'exif-make'                        => 'කැමරා නිෂ්පාදක',
'exif-model'                       => 'කැමරා මාදිලිය',
'exif-software'                    => 'භාවිතාකල මෘදුකාංග',
'exif-artist'                      => 'කතෘ',
'exif-copyright'                   => 'හිමිකම් දරන්නා',
'exif-exifversion'                 => 'Exif අනුවාදය',
'exif-flashpixversion'             => 'අනුග්‍රාහක Flashpix අනුවාදය',
'exif-colorspace'                  => 'වර්ණ අවකාශය',
'exif-componentsconfiguration'     => 'එක් එක් සංරචකයේ අර්ථය',
'exif-compressedbitsperpixel'      => 'රූප සම්පීඩන මාදිලිය',
'exif-pixelydimension'             => 'නීතිකරූප පළල',
'exif-pixelxdimension'             => 'නීතික රූප උස',
'exif-makernote'                   => 'නිෂ්පාදකගේ සටහන්',
'exif-usercomment'                 => 'පරිශීලක පරිකථනයන්',
'exif-relatedsoundfile'            => 'සහසම්බන්ධිත ශ්‍රව්‍ය ගොනුව',
'exif-datetimeoriginal'            => 'දත්ත ජනන දිනය හා වේලාව',
'exif-datetimedigitized'           => 'ඩිජිටල්කරන දිනය හා වේලාව',
'exif-subsectime'                  => 'දිනයවේලාව තත්පරනොටසින්',
'exif-subsectimeoriginal'          => 'දිනටවේලාවමූල්‍ය තත්පරකොටසින්',
'exif-subsectimedigitized'         => 'දිනයවේලාවඩිජිටල්කල තත්පරකොටසින්',
'exif-exposuretime'                => 'නිරාවරණ කාලය',
'exif-exposuretime-format'         => 'තත්පර $1 ($2)',
'exif-fnumber'                     => 'F අංකය',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'නිරාවරණ වැඩසටහන',
'exif-spectralsensitivity'         => 'වර්ණාවලී සංවේදිතාවය',
'exif-isospeedratings'             => 'ISO වේග ඇගැයුම',
'exif-oecf'                        => 'ප්‍රකාශවිද්‍යුත් අනුවර්තන සාධකය',
'exif-shutterspeedvalue'           => 'ෂටර වේගය',
'exif-aperturevalue'               => 'විවරය',
'exif-brightnessvalue'             => 'දීප්තතාවය',
'exif-exposurebiasvalue'           => 'නිරාවරණ නැඹුරුව',
'exif-maxaperturevalue'            => 'උපරිම භූමි විවරය',
'exif-subjectdistance'             => 'වස්තු දුර',
'exif-meteringmode'                => 'මැනුම් මාදිලිය',
'exif-lightsource'                 => 'ආලෝක ප්‍රභවය',
'exif-flash'                       => 'සැණෙළිය',
'exif-focallength'                 => 'කාච නාභීය දිග',
'exif-focallength-format'          => '$1 මි.මී.',
'exif-subjectarea'                 => 'විෂයය සරිය',
'exif-flashenergy'                 => 'සැණෙළි ශක්තිය',
'exif-spatialfrequencyresponse'    => 'අවකාශීය සංඛ්‍යාත ප්‍රතිචාරය',
'exif-focalplanexresolution'       => 'නාභීය තලය X විසර්ජනය',
'exif-focalplaneyresolution'       => 'නාභීය තලය Y විසර්ජනය',
'exif-focalplaneresolutionunit'    => 'නාභීය තලය විසර්ජනය ඒකකය',
'exif-subjectlocation'             => 'වස්තු පරිස්ථානය',
'exif-exposureindex'               => 'නිරාවරණ දර්ශකය',
'exif-sensingmethod'               => 'සංවේදන ක්‍රමය',
'exif-filesource'                  => 'ගොනු මූලය',
'exif-scenetype'                   => 'දර්ශන වර්ගය',
'exif-cfapattern'                  => 'CFA රටාව',
'exif-customrendered'              => 'උපයෝග්‍ය රූප සැකැසුම',
'exif-exposuremode'                => 'නිරාවරණ මාදිලිය',
'exif-whitebalance'                => 'ශ්වේත තුලනය',
'exif-digitalzoomratio'            => 'ඩිජිටල් සූම් අනුපාතය',
'exif-focallengthin35mmfilm'       => ' 35 මි.මී.  සේයාපටලයන්හි නාභීය දුර',
'exif-scenecapturetype'            => 'දර්ශන ග්‍රහණ මාදිලිය',
'exif-gaincontrol'                 => 'දර්ශන පාලනය',
'exif-contrast'                    => 'අසමතාව',
'exif-saturation'                  => 'සන්තෘප්තිය',
'exif-sharpness'                   => 'තියුණුබව',
'exif-devicesettingdescription'    => 'උපකරණ පරිස්ථිති විස්තරය',
'exif-subjectdistancerange'        => 'වස්තු දුර පරාසය',
'exif-imageuniqueid'               => 'අනන්‍ය රූප අනන‍්‍යාංකය',
'exif-gpsversionid'                => 'GPS ටැග අනුවාදය',
'exif-gpslatituderef'              => 'උතුරු හෝ දකුණු අක්ෂාංශය',
'exif-gpslatitude'                 => 'අක්ෂාංශය',
'exif-gpslongituderef'             => 'බටහිර හෝ නැගෙනහිර දේශාංශය',
'exif-gpslongitude'                => 'දේශාංශය',
'exif-gpsaltituderef'              => 'උන්නතාංශ සමුද්දේශය',
'exif-gpsaltitude'                 => 'උන්නතාංශය',
'exif-gpstimestamp'                => 'GPS වේලාව (පරමාණු ඔරලෝසුව)',
'exif-gpssatellites'               => 'මිනුම් සඳහා භාවිතා වන චන්ද්‍රිකා',
'exif-gpsstatus'                   => 'රිසීවරයෙහි තරාතිරම',
'exif-gpsmeasuremode'              => 'මැනුම් අකාරය',
'exif-gpsdop'                      => 'මැනුම් නිරවද්‍යතාවය',
'exif-gpsspeedref'                 => 'වේග ඒකකය',
'exif-gpsspeed'                    => 'GPS රිසීවරයෙහි වේගය',
'exif-gpstrackref'                 => 'චලිත දිශාව සඳහා සමුද්දේශය',
'exif-gpstrack'                    => 'චලිත දිශාව',
'exif-gpsimgdirectionref'          => 'රූපයේ දිශාව සඳහා සමුද්දේශය',
'exif-gpsimgdirection'             => 'රූපයේ දිශාව',
'exif-gpsmapdatum'                 => 'භූමිතික මැනුම් දත්ත භාවිත කෙරිණි',
'exif-gpsdestlatituderef'          => 'අන්තයෙහි අක්ෂාංශය සඳහා සමුද්දේශය',
'exif-gpsdestlatitude'             => 'අන්තයෙහි අක්ෂාංශය',
'exif-gpsdestlongituderef'         => 'අන්තයෙහි දේශාංශය සඳහා සමුද්දේශය',
'exif-gpsdestlongitude'            => 'අන්තයෙහි දේශාංශය',
'exif-gpsdestbearingref'           => 'අන්තයෙහි දිශානතිය සඳහා සමුද්දේශය',
'exif-gpsdestbearing'              => 'අන්තයෙහි දිශානතිය',
'exif-gpsdestdistanceref'          => 'අන්තයට දුර සඳහා සමුද්දේශය',
'exif-gpsdestdistance'             => 'අන්තයට දුර',
'exif-gpsprocessingmethod'         => 'GPS සැකසුම් ක්‍රමයෙහි නම',
'exif-gpsareainformation'          => 'GPS සරියෙහි නම',
'exif-gpsdatestamp'                => 'GPS දිනය',
'exif-gpsdifferential'             => 'GPS ආන්තරීක ශෝධනය',

# EXIF attributes
'exif-compression-1' => 'අසංක්ෂිප්ත',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',
'exif-photometricinterpretation-6' => 'YCbCr',

'exif-unknowndate' => 'අඥාත දිනයකි',

'exif-orientation-1' => 'සාමාන්‍ය',
'exif-orientation-2' => 'තිරස වටා පෙරලා',
'exif-orientation-3' => '180° භ්‍රමණය කොට',
'exif-orientation-4' => 'සිරස වටා පෙරලා',
'exif-orientation-5' => '90° වාමාවර්තය භ්‍රමණය නොට සිරස වටා පෙරලා',
'exif-orientation-6' => '90° දක්ෂිණාවර්තව භ්‍රමණය කොට',
'exif-orientation-7' => '90° දක්ෂිණාවර්තව භ්‍රමණය කොට සිරස වටා පෙරලා',
'exif-orientation-8' => '90° වාමාවර්තව භ්‍රමණය කොට',

'exif-planarconfiguration-1' => 'කුට්ටි ආකෘතිකරණය',
'exif-planarconfiguration-2' => 'තලීය ආකෘතිකරණය',

'exif-xyresolution-i' => '$1 dpi',
'exif-xyresolution-c' => '$1 dpc',

'exif-colorspace-1'      => 'sRGB',
'exif-colorspace-ffff.h' => 'FFFF.H',

'exif-componentsconfiguration-0' => 'නොපවතියි',
'exif-componentsconfiguration-1' => 'Y',
'exif-componentsconfiguration-2' => 'Cb',
'exif-componentsconfiguration-3' => 'Cr',
'exif-componentsconfiguration-4' => 'R',
'exif-componentsconfiguration-5' => 'G',
'exif-componentsconfiguration-6' => 'B',

'exif-exposureprogram-0' => 'අර්ථදක්වා නැත',
'exif-exposureprogram-1' => 'හස්තීය',
'exif-exposureprogram-2' => 'සාමාන්‍ය ක්‍රමලේඛය',
'exif-exposureprogram-3' => 'විවර ප්‍රමුඛතාවය',
'exif-exposureprogram-4' => 'ෂටර ප්‍රමුඛතාවය',
'exif-exposureprogram-5' => 'නිර්මාණාත්මක වැඩසටහන (ක්ෂේත්‍ර ගැඹුර‍ට නැඹුරුතාවයක් දක්වන)',
'exif-exposureprogram-6' => 'කිරිය වැඩසටහන (සීඝ්‍ර ෂටර වේගයට නැඹුරුතාවයක් දක්වන)',
'exif-exposureprogram-7' => 'ආල්ඛ්‍ය තලීය මාදිලිය (පසුතලය නාභිගත නොවන සමීප ඡායාරූප සඳහා)',
'exif-exposureprogram-8' => 'භූතල තලීය මාදිලිය (පසුතලය නාභිගත වන භූතල ඡායාරූප සඳහා)',

'exif-subjectdistance-value' => 'මීටර $1',

'exif-meteringmode-0'   => 'අඥාත',
'exif-meteringmode-1'   => 'සාමාන්‍යය',
'exif-meteringmode-2'   => 'මැදි-බරු-සාමාන්‍යය',
'exif-meteringmode-3'   => 'අවධාරිතය',
'exif-meteringmode-4'   => 'බහුඅවධාරිතය',
'exif-meteringmode-5'   => 'රටාව',
'exif-meteringmode-6'   => 'භාගික',
'exif-meteringmode-255' => 'අනෙකුත්',

'exif-lightsource-0'   => 'අඥාත',
'exif-lightsource-1'   => 'දිවා එළිය',
'exif-lightsource-2'   => 'ප්‍රතිදීප්ත',
'exif-lightsource-3'   => 'ටංස්ටන් (තාපදීප්ත ආලෝකය)',
'exif-lightsource-4'   => 'සැණෙළිය',
'exif-lightsource-9'   => 'කදිම කාලගුණය',
'exif-lightsource-10'  => 'වළාකුළු පිරි කාලගුණය',
'exif-lightsource-11'  => 'සෙවණ',
'exif-lightsource-12'  => 'දිවාඑළි ප්‍රතිදීප්ත (D 5700 – 7100K)',
'exif-lightsource-13'  => 'දිවා ශ්වේත ප්‍රතිදීප්ත (N 4600 – 5400K)',
'exif-lightsource-14'  => 'සිහිල් ශ්වේත ප්‍රතිදීප්ත (W 3900 – 4500K)',
'exif-lightsource-15'  => 'ශ්වේත ප්‍රතිදීප්ත (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'සම්මත ආලෝකය A',
'exif-lightsource-18'  => 'සම්මත ආලෝකය B',
'exif-lightsource-19'  => 'සම්මත ආලෝකය C',
'exif-lightsource-20'  => 'D55',
'exif-lightsource-21'  => 'D65',
'exif-lightsource-22'  => 'D75',
'exif-lightsource-23'  => 'D50',
'exif-lightsource-24'  => 'ISO මැදිරි ටංස්ටන්',
'exif-lightsource-255' => 'අනෙකුත් ආලෝක ප්‍රභවය',

# Flash modes
'exif-flash-fired-0'    => 'සැණෙළිය නොදැල්වුණි',
'exif-flash-fired-1'    => 'සැණෙළිය දැල්වුණි',
'exif-flash-return-0'   => 'ස්පන්දාලෝක ප්‍රත්‍යාගමන අනාවරණ කෘත්‍යය නැත',
'exif-flash-return-2'   => 'ස්පන්දාලෝක ප්‍රත්‍යාගමන ආලෝකය අනාවරණය නොවිණි',
'exif-flash-return-3'   => 'ස්පන්දාලෝක ප්‍රත්‍යාගමන ආලෝකය අනාවරණය විණි',
'exif-flash-mode-1'     => 'සැණෙළිය දැල්වීම අනිවාර්යයි',
'exif-flash-mode-2'     => 'සැණෙලිය අනිවාර්යයෙන් යටපත්කිරීම',
'exif-flash-mode-3'     => 'ස්වයංක්‍රීය පන්නය',
'exif-flash-function-1' => 'සැණෙළි ක්‍රියාවලියක් නැත',
'exif-flash-redeye-1'   => 'රකුසැස් ලඝුකරණ මාදිලිය',

'exif-focalplaneresolutionunit-2' => 'අඟල්',

'exif-sensingmethod-1' => 'අනිශ්චිත',
'exif-sensingmethod-2' => 'එක්-චිප වර්ණ සරි සංවේදකය',
'exif-sensingmethod-3' => 'ද්වි-චිප වර්ණ සරි සංවේදකය',
'exif-sensingmethod-4' => 'තුන්-චිප වර්ණ සරි සංවේදකය',
'exif-sensingmethod-5' => 'වර්ණ අනුක්‍රමික සරි සංවේදකය',
'exif-sensingmethod-7' => 'ත්‍රිරේඛීය සංවේදකය',
'exif-sensingmethod-8' => 'වර්ණ අනුක්‍රමික රේඛීය සංවේදකය',

'exif-filesource-3' => 'DSC (ආන්තර පරිලෝකන වර්ණමිතිය)',

'exif-scenetype-1' => 'සෘජු ලෙස ඡායරූපගතකල රූපයන්',

'exif-customrendered-0' => 'සාමාන්‍ය ක්‍රියාවලිය',
'exif-customrendered-1' => 'උපයෝජ්‍ය ක්‍රියාවලිය',

'exif-exposuremode-0' => 'ස්වයාක්‍රීය නිරාවරණය',
'exif-exposuremode-1' => 'හස්තීය නිරාවරණය',
'exif-exposuremode-2' => 'ස්වයං සමුච්චය',

'exif-whitebalance-0' => 'ස්වයංක්‍රීය ශ්වේත තුලනය',
'exif-whitebalance-1' => 'හස්තීය  ශ්වේත තුලනය',

'exif-scenecapturetype-0' => 'සම්මත',
'exif-scenecapturetype-1' => 'භූතල තලීය',
'exif-scenecapturetype-2' => 'ආල්ඛ්‍ය තලීය',
'exif-scenecapturetype-3' => 'රාත්‍රී දර්ශනය',

'exif-gaincontrol-0' => 'නොමැත',
'exif-gaincontrol-1' => 'අඩු වර්ධනය ඉහළ දැමුමක්',
'exif-gaincontrol-2' => 'වැඩි වර්ධනය ඉහළ දැමුමක්',
'exif-gaincontrol-3' => 'අඩු වර්ධනය පහළ දැමුමක්',
'exif-gaincontrol-4' => 'වැඩි වර්ධනය ඉහළ දැමුමක්',

'exif-contrast-0' => 'සාමාන්‍ය',
'exif-contrast-1' => 'සුමුදු',
'exif-contrast-2' => 'දැඩි',

'exif-saturation-0' => 'සාමාන්‍ය',
'exif-saturation-1' => 'අඩු සන්තෘප්තිය',
'exif-saturation-2' => 'වැඩි සන්තෘප්තිය',

'exif-sharpness-0' => 'සාමාන්‍ය',
'exif-sharpness-1' => 'සුමුදු',
'exif-sharpness-2' => 'දැඩි',

'exif-subjectdistancerange-0' => 'අඥාත',
'exif-subjectdistancerange-1' => 'සාර්ව',
'exif-subjectdistancerange-2' => 'සමීප නැරඹුම',
'exif-subjectdistancerange-3' => 'දුරස්තර නැරඹුම',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'උතුරු අක්ෂාංශය',
'exif-gpslatitude-s' => 'දකුණු අක්ෂාංශය',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'නැගෙනහිර දේශාංශය',
'exif-gpslongitude-w' => 'බටහිර දේශාංශය',

'exif-gpsstatus-a' => 'මිනුම සිදුවෙමින් පවතියි',
'exif-gpsstatus-v' => 'මිනුම් අන්කර්ක්‍රියාත්මකභාවය',

'exif-gpsmeasuremode-2' => 'ද්වීමාන මිනුම',
'exif-gpsmeasuremode-3' => 'ත්‍රිමාන මිනුම',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'පැයට කිලෝමීටර',
'exif-gpsspeed-m' => 'පැයට සැතපුම්',
'exif-gpsspeed-n' => 'නාවික සැතපුම්',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'සත්‍ය දිශාව',
'exif-gpsdirection-m' => 'චුම්භක දිශාව',

# External editor support
'edit-externally'      => 'බාහිර  උපයෝගයක් භාවිතා කරමින් මෙම ගොනුව සංස්කරණය කරන්න',
'edit-externally-help' => '(වැඩිදුර තොරතුරු සඳහා [http://www.mediawiki.org/wiki/Manual:External_editors පිහිටුවීම් උපදෙස්] බලන්න.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'සියල්ල',
'imagelistall'     => 'සියල්ල',
'watchlistall2'    => 'සියල්ල',
'namespacesall'    => 'සියල්ල',
'monthsall'        => 'සියළු',
'limitall'         => 'සියල්ලම',

# E-mail address confirmation
'confirmemail'              => 'විද්‍යුත්-තැපැල් ලිපිනය තහවුරු කරන්න',
'confirmemail_noemail'      => 'ඔබගේ  [[Special:Preferences|පරිශීලක අභිරුචියන්]] හි නීතික විද්‍යුත්-තැපැල් ලිපිනයක් ඔබ විසින් පිහිටුවා නොමැත.',
'confirmemail_text'         => 'විද්‍යුත්-තැපැල් අංගයන් භාවිතා කිරීමට පෙර  ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය නීතිකරණය කල යුතු බවට {{SITENAME}} අවධාරණය කරයි.
ඔබගේ ලිපිනයට තහවුරුකරණ තැපෑලක් යවනු  සඳහා පහත බොත්තම සක්‍රියනය කරන්න.
මෙම තැපෑල කේතයක් අඩංගු සබැඳියක් සහිත වනු ඇත;
ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය නීතික බව තහවුරු කරනු වස් මෙම සබැඳිය ඔබගේ බ්‍රවුසරයෙහි  ප්‍රවේශනය කරන්න.',
'confirmemail_pending'      => 'තහවුරු කිරීමේ කේතයක් දැනටමත් ඔබට විද්‍යුත්-තැපැල් කොට ඇත;
ඔබ විසින් ගිණුම තැනුවේ මෑතකදී නම්, නව තේකයක් ඉල්ලා සිටීමට පෙර එය ඔබ වෙත ලඟා වීම සඳහා විනාඩි කිහිපයක් රැඳී සිටින්න.',
'confirmemail_send'         => 'තහවුරුකිරීමේ කේතයක් තැපැල් කරන්න',
'confirmemail_sent'         => 'තහවුරුකිරීමේ විද්‍යුත්-තැපෑලක් යැවිණි.',
'confirmemail_oncreate'     => 'ඔබගේ විද්‍යුත්-තැපැල් ලිපිනයට තහවුරුකිරීමේ කේතයක් යවන ලදි.
ප්‍රවිෂ්ට වීමට මෙම කේතය අනවශ්‍ය නමුදු, විකියෙහි විද්‍යුත්-තැපැල් සම්බන්ධ අංගයන් කිසිවක් හෝ සක්‍රීය කිරීමට පෙර මෙය ඉදිරිපත් කල යුතු වේ.',
'confirmemail_sendfailed'   => 'ඔබගේ තහවුරුකිරීමේ තැපෑල යැවීමට {{SITENAME}} හට නොහැකි විය.
අනීතික අක්ෂර තිබේදැයි ඔබගේ විද්‍යුත් තැපැල් ලිපිනය පරික්ෂා කර බලන්න.

තැපැල්කරු ආපසු දැනුම්දුන්නේ: $1',
'confirmemail_invalid'      => 'තහවුරුකරණ කේතය අනීතිකයි.
කේතය කල් ඉකුත්වූ එකක් විය හැක.',
'confirmemail_needlogin'    => 'ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය තහවුරුකිරීමට ඔබ  $1 වී සිටිය යුතුය.',
'confirmemail_success'      => 'ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය තහවුරුකොට ඇත.
ඔබහට දැන් [[Special:UserLogin|පුවිෂ්ට වී]] විකිය භුක්තිවිඳිය හැක.',
'confirmemail_loggedin'     => 'ඔබගේ විද්‍යුත්-තැපැල් ලිපිනය දැන් තහවුරුකොට ඇත.',
'confirmemail_error'        => 'ඔබගේ තහවුරුකිරීම සුරැකීමට උත්සාහ කිරීමේදී යම් ගැටළුවක් පැනනැගුණි.',
'confirmemail_subject'      => '{{SITENAME}} විද්‍යුත්-තැපැල් ලිපිනය තහවුරුකිරීම',
'confirmemail_body'         => 'යම් අයෙකු, අන්තර්ජාල ලිපිනය $1 මගින්, සමහර විට ඔබ,
{{SITENAME}} හි  "$2" නමැති ගිණුමක් මෙම විද්‍යුත්-තැපැල් ලිපිනය සහිතව ලියාපදිංචි කර ඇත .

මෙම ගිණුම ඇත්ත වශයෙන්ම ඔබගේ බව සහතික කිරීමට හා  {{SITENAME}} හි, 
විද්‍යුත්-තැපැල් විශේෂාංග සක්‍රීයනය කරනු වස්, ඔබගේ බ්‍රවුසරයෙහි පහත සබැඳිය විවෘත කරන්න:

$3

මෙම ගිණුම, ඔබ විසින් ලියාපදිංචි  *නොකළේ*  නම්, මෙම සබැඳිය ඔස්සේ සපැමිණ
විද්‍යුත්-තැපැල් ලිපින සහතික කිරීම අත්හරින්න:

$5

මෙම සහතික කිරීමේ කේතයෙහි වලංගු බව   $4 වන විට ඉකුත් වේ.',
'confirmemail_body_changed' => 'යම් අයෙකු, අන්තර්ජාල ලිපිනය $1 මගින්, සමහර විට ඔබ,
{{SITENAME}} හි  "$2" නමැති ගිණුමෙහි විද්‍යුත්-තැපැල් ලිපිනය වෙනස් කර ඇත .

මෙම ගිණුම ඇත්ත වශයෙන්ම ඔබගේ බව සහතික කිරීමට හා  {{SITENAME}} හි, 
විද්‍යුත්-තැපැල් විශේෂාංග සක්‍රීයනය කරනු වස්, ඔබගේ බ්‍රවුසරයෙහි පහත සබැඳිය විවෘත කරන්න:

$3

මෙම ගිණුම, ඔබ හට අයක්  *නොවේ*  නම්, මෙම සබැඳිය ඔස්සේ සපැමිණ
විද්‍යුත්-තැපැල් ලිපින සහතික කිරීම අත්හරින්න:

$5

මෙම සහතික කිරීමේ කේතයෙහි වලංගු බව  $4 වන විට ඉකුත් වේ',
'confirmemail_invalidated'  => 'විද්‍යුත්-ගැපැල් ලිපිනය තහවුරුකිරීම අවලංගු කෙරිණි',
'invalidateemail'           => 'විද්‍යුත්-තැපැල් තහවුරුකිරීම අවලංගු කරන්න',

# Scary transclusion
'scarytranscludedisabled' => '[අන්තර්විකී  අන්තඃගතකිරීම් අක්‍රීය කොට ඇත]',
'scarytranscludefailed'   => '[$1 සඳහා සැකිලි අත්කරගැනුම අසාර්ථක විය]',
'scarytranscludetoolong'  => '[URL දිගු වැඩිය]',

# Trackbacks
'trackbackbox'      => 'මෙම පිටුව සඳහා පසුහැඹීම්:<br />
$1',
'trackbackremove'   => '([$1 මකාදමන්න])',
'trackbacklink'     => 'පසුහැඹීම',
'trackbackdeleteok' => 'පසුහැඹීම සාර්ථක ලෙස මකාදමන ලදි.',

# Delete conflict
'deletedwhileediting' => "'''අවවාදයයි''': ඔබ විසින් මෙම පිටුව සංස්කරණය ඇරැඹි පසුව එය මකා දමන ලදි!",
'confirmrecreate'     => "ඔබ විසින් මේ පිටුව සංස්කරණය කිරීම ඇරඹූ පසු, පරිශීලක [[User:$1|$1]] ([[User talk:$1|සාකච්ඡාව]]) විසින් එය මකාදමා පහත හේතුව සපයන ලදි:
: ''$2''
ඔබ එට සත්‍යවශයෙන්ම මෙම පිටුව යළිතැනීමට අවශ්‍ය බව තහවුරුකරන්න.",
'recreate'            => 'යළිතැනීම',

'unit-pixel' => 'පික්සල',

# action=purge
'confirm_purge_button' => 'හරි',
'confirm-purge-top'    => 'මෙම පිටුවෙහි පූර්වාපේක්‍ෂී සංචිතය (කෑෂය) හිස් කල යුතුද?',
'confirm-purge-bottom' => 'පිටුවක් විමෝචනය කිරීම විසින් පූර්වාපේක්‍ෂිත සංචිතය (කෑෂය) හිස් කොට ඉතාමත් මෑත අනුවාදය පෙන්නුම් කිරීමට බල කරයි.',

# Multipage image navigation
'imgmultipageprev' => '← පෙර පිටුව',
'imgmultipagenext' => 'ඊළඟ පිටුව →',
'imgmultigo'       => 'යන්න!',
'imgmultigoto'     => ' $1 පිටුවට යන්න',

# Table pager
'ascending_abbrev'         => 'ආරෝහණ',
'descending_abbrev'        => 'අවරෝහණ',
'table_pager_next'         => 'ඊළඟ පිටුව',
'table_pager_prev'         => 'පෙර පිටුව',
'table_pager_first'        => 'පළමු පිටුව',
'table_pager_last'         => 'අවසාන පිටුව',
'table_pager_limit'        => 'එක් පිටුවක් වෙනුවෙන් අයිතම $1 පෙන්වන්න',
'table_pager_limit_label'  => 'එක් පිටුවකට අයිතම:',
'table_pager_limit_submit' => 'යන්න',
'table_pager_empty'        => 'ප්‍රතිඵල නොමැත',

# Auto-summaries
'autosumm-blank'   => 'පිටුව හිස් කෙරිණි',
'autosumm-replace' => "පිටුව වෙනුවට  '$1' ප්‍රතිස්ථාපනය කරමින්",
'autoredircomment' => ' [[$1]] වෙතට යළි-යොමුකරමින්',
'autosumm-new'     => "'$1' යොදමින් නව පිටුවක් තනන ලදි",

# Size units
'size-bytes'     => '$1 බ',
'size-kilobytes' => '$1 කි.බ.',
'size-megabytes' => '$1 මෙ.බ.',
'size-gigabytes' => '$1 ගි.බ.',

# Live preview
'livepreview-loading' => 'බා ගැනෙමින්…',
'livepreview-ready'   => 'බා ගැනෙමින්… සූදානම්!',
'livepreview-failed'  => 'තත්කාල පෙර-දසුන අසමත් විය! සාමාන්‍ය පෙර-දසුන  වෑයම් කරන්න.',
'livepreview-error'   => 'මෙය හා සම්බන්ධ වීම අසාර්ථක විය: $1 "$2".
සාමාන්‍ය පෙර-දසුන අත්හදා බලන්න.',

# Friendlier slave lag warnings
'lag-warn-normal' => '{{PLURAL:$1|තත්පරයකට|තත්පර $1 කට}} වඩා නැවුම් වෙනස්වීම්, ලැයිස්තුවෙහි පෙන්නුම් නොවීමට ඉඩ ඇත.',
'lag-warn-high'   => 'දත්ත-ගබඩා සේවාදායකයෙහි අධික විලම්බය නිසා, වෙනස්වීමට පසු ගතවූයේ  {{PLURAL:$1|එක් තත්පරයක්|තත්පර $1 ක්}} පමණක් නම්, ලැයිස්තුවෙහි අන්තර්ගතවී නොතිබිය හැක.',

# Watchlist editor
'watchlistedit-numitems'       => 'සාකච්ඡා පිටු ගණනය නොකල විට, ඔබගේ මුර-ලැයිස්තුවෙහි  {{PLURAL:$1|ශීර්ෂ එකක්|ශීර්ෂ $1 ක්}} අඩංගු වේ.',
'watchlistedit-noitems'        => 'ඔබගේ මුර-ලැයිස්තුවේ ශීර්ෂ කිසිවක් නොමැත.',
'watchlistedit-normal-title'   => 'මුර-ලැයිස්තුව සංස්කරණය කරන්න',
'watchlistedit-normal-legend'  => 'මුර-ලැයිස්තුවෙන් ශීර්ෂයන් ඉවත් කරන්න',
'watchlistedit-normal-explain' => 'ඔබගේ මුර-ලැයිස්තුවෙහි සිරස්තලයන් පහත දක්වා ඇත.
සිරස්තලයක් ඉවත් කිරීමට, එය‍ට යාබද කොටුව තෝරාගෙන, සිරස්තල ඉවත්කරන්න යන්න මත ක්ලික් කරන්න.
[[Special:Watchlist/raw|නොනිමි ලැයිස්තුව සංස්කරණය කිරීම]] වුවද ඔබ විසින් සිදු කල හැක.',
'watchlistedit-normal-submit'  => 'ශීර්ෂයන් ඉවත් කරන්න',
'watchlistedit-normal-done'    => 'ඔබගේ මුර-ලැයිස්තුවෙන් {{PLURAL:$1|එක් ශීර්ෂයක්|ශීර්ෂයන් $1 ක්}} ඉවත් කරන ලදි:',
'watchlistedit-raw-title'      => 'නොනිමි මුර-ලැයිස්තුව සංස්කරණය කරන්න',
'watchlistedit-raw-legend'     => 'නොනිමි මුර-ලැයිස්තුව සංස්කරණය කරන්න',
'watchlistedit-raw-explain'    => 'ඔබගේ මුර-ලැයිස්තුවෙහි ශීර්ෂයන් මෙහි පහත දැක්වෙන අතර, එක් පේළියකට එක් ශීර්ෂයක් වන ලෙස, එම ලැයිස්තුවට එක් කිරීමෙන් හා ලැයිස්තුවෙන් ඉවත් කිරීමෙන් එය සංස්කරණය කල හැක.
මෙය නිමවූ විට, මුර-ලැයිස්තුව යාවත්කාලකිරීම යන්න මත ක්ලික් කරන්න.
 [[Special:Watchlist/edit|සම්මත සංස්කාරකය භාවිතා කිරීමද]] ඔබ විසින් සිදු කල හැක.',
'watchlistedit-raw-titles'     => 'ශීර්ෂයන්:',
'watchlistedit-raw-submit'     => 'මුර-ලැයිස්තුව යාවත්කාලීන කරන්න',
'watchlistedit-raw-done'       => 'ඔබගේ මුර-ලැයිස්තුව යාවත්කාලීන කරන ලදි.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|ශීර්ෂ 1 ක් |ශීර්ෂ  $1 ක් }} එක් කරන ලදි:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|ශීර්ෂ 1 ක්|ශීර්ෂ  $1 ක්}} ඉවත් කරන ලදි:',

# Watchlist editing tools
'watchlisttools-view' => 'අදාල වෙනස්වීම් නරඹන්න',
'watchlisttools-edit' => 'මුර-ලැයිස්තුව නැරඹීම හා සංස්කරණය සිදු කරන්න',
'watchlisttools-raw'  => 'නොනිමි මුර-ලැයිස්තුව සංස්කරණය කරන්න',

# Iranian month names
'iranian-calendar-m1'  => 'ෆාර්වාදීන්',
'iranian-calendar-m2'  => 'ඕඩිබෙහෙෂ්ට්',
'iranian-calendar-m3'  => 'කෝඩාඩ්',
'iranian-calendar-m4'  => 'ටීර්',
'iranian-calendar-m5'  => 'මෝඩාඩ්',
'iranian-calendar-m6'  => 'ෂාරිවා',
'iranian-calendar-m7'  => 'මෙහ්ර්',
'iranian-calendar-m8'  => 'අබාන්',
'iranian-calendar-m9'  => 'අෂාර්',
'iranian-calendar-m10' => 'ඩෙයි',
'iranian-calendar-m11' => 'බාහ්මාන්',
'iranian-calendar-m12' => 'එස්ෆාන්ඩ්',

# Hijri month names
'hijri-calendar-m1'  => 'මුහාරම්',
'hijri-calendar-m2'  => 'සෆාර්',
'hijri-calendar-m3'  => 'රාබි අල් අවාල්',
'hijri-calendar-m4'  => 'රාබි අල් තානි',
'hijri-calendar-m5'  => 'ජුමාඩා අල් අවාල්',
'hijri-calendar-m6'  => 'ජුමාඩා අල් තානි',
'hijri-calendar-m7'  => 'රජාබ්',
'hijri-calendar-m8'  => 'ෂාබාන්',
'hijri-calendar-m9'  => 'රාමාදාන්',
'hijri-calendar-m10' => 'ෂාව්වාල්',
'hijri-calendar-m11' => 'ඩූ අල් කිඩාහ්',
'hijri-calendar-m12' => 'ඩූ අල් හිජාහ්',

# Hebrew month names
'hebrew-calendar-m1'      => 'තිස්රෙයි',
'hebrew-calendar-m2'      => 'චෙෂ්වාන්',
'hebrew-calendar-m3'      => 'කිස්ලෙව්',
'hebrew-calendar-m4'      => 'ටෙවෙට්',
'hebrew-calendar-m5'      => 'ෂිවාට්',
'hebrew-calendar-m6'      => 'අඩාර්',
'hebrew-calendar-m6a'     => 'අඩාර්  I',
'hebrew-calendar-m6b'     => 'අඩාර්  II',
'hebrew-calendar-m7'      => 'නිසාන්',
'hebrew-calendar-m8'      => 'ඉයාර්',
'hebrew-calendar-m9'      => 'සිවාන්',
'hebrew-calendar-m10'     => 'ටාමුස්',
'hebrew-calendar-m11'     => 'අව්',
'hebrew-calendar-m12'     => 'එලුල්',
'hebrew-calendar-m1-gen'  => 'තිස්රෙයි',
'hebrew-calendar-m2-gen'  => 'චෙෂ්වාන්',
'hebrew-calendar-m3-gen'  => 'කිස්ලෙව්',
'hebrew-calendar-m4-gen'  => 'ටෙවෙට්',
'hebrew-calendar-m5-gen'  => 'ෂිවාට්',
'hebrew-calendar-m6-gen'  => 'අඩාර්',
'hebrew-calendar-m6a-gen' => 'අඩාර්  I',
'hebrew-calendar-m6b-gen' => 'අඩාර්  II',
'hebrew-calendar-m7-gen'  => 'නිසාන්',
'hebrew-calendar-m8-gen'  => 'ඉයාර්',
'hebrew-calendar-m9-gen'  => 'සිවාන්',
'hebrew-calendar-m10-gen' => 'ටාමුස්',
'hebrew-calendar-m11-gen' => 'අව්',
'hebrew-calendar-m12-gen' => 'එලුල්',

# Signatures
'timezone-utc' => 'යූටීසී',

# Core parser functions
'unknown_extension_tag' => 'අඥාත ප්‍රසර්ජන ටැගය "$1"',
'duplicate-defaultsort' => 'අවවාදයයි: "$2" පෙරනිමි සුබෙදුම් යතුර විසින් ‍පූර්ව පෙරනිමි සුබෙදුම් යතුර  වූ  "$1" අතික්‍රමණය කරයි.',

# Special:Version
'version'                          => 'අනුවාදය',
'version-extensions'               => 'ස්ථාපිත ප්‍රසර්ජනයන්',
'version-specialpages'             => 'විශේෂ පිටු',
'version-parserhooks'              => 'ව්‍යාකරණ විග්‍රහක හසුරු',
'version-variables'                => 'විචල්‍යයන්',
'version-other'                    => 'වෙනත්',
'version-mediahandlers'            => 'මාධ්‍ය හසුරුවනය',
'version-hooks'                    => 'හසුරු',
'version-extension-functions'      => 'ප්‍රසර්ජන ශ්‍රිත',
'version-parser-extensiontags'     => 'ව්‍යාකරණ  විග්‍රහක ප්‍රසර්ජන ටැගයන්',
'version-parser-function-hooks'    => 'වයාකරණ විග්‍රහක ශ්‍රිත හසුරු',
'version-skin-extension-functions' => 'ඡවි ප්‍රසර්ජන ශ්‍රීත',
'version-hook-name'                => 'හසුරු නම',
'version-hook-subscribedby'        => 'දායකවී ඇත්තේ',
'version-version'                  => '(අනුවාදය $1)',
'version-license'                  => 'බලපත්‍රය',
'version-software'                 => 'ස්ථාපිත මෘදුකාංග',
'version-software-product'         => 'නිෂ්පාදනය',
'version-software-version'         => 'අනුවාදය',

# Special:FilePath
'filepath'         => 'ගොනු පෙත',
'filepath-page'    => 'ගොනුව:',
'filepath-submit'  => 'යන්න',
'filepath-summary' => 'මෙම විශේෂ පිටුව,  ගොනුවකට අදාල  සම්පූර්ණ පෙත හුවා දක්වයි.
රූප, පූර්ණ විසර්ජනයෙන් දැක්වෙන අතර, අනෙකුත් ගොනු වර්ග ඒවායේ ආශ්‍රිත ක්‍රමලේඛයන් අනුසාරයෙන් සෘජුව ආරම්භ කෙරේ.

"{{ns:file}}:" උපසර්ගය විරහිතව ගොනු නාමය ඇතුලත් කරන්න.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'අනුපිටපත් ගොනු සඳහා ගවේෂණය කරන්න',
'fileduplicatesearch-summary'  => 'එහි පූරක අගය පාදක කර ගෙන අනුපිටපත් ගොනු සඳහා ගවේෂණය කරන්න.

"{{ns:file}}:" උපසර්ගය රහිතව ගොනු නාමය ඇතුළත් කරන්න.',
'fileduplicatesearch-legend'   => 'අනුපිටපතක් සඳහා ගවේෂණය කරන්න',
'fileduplicatesearch-filename' => 'ගොනු-නාමය:',
'fileduplicatesearch-submit'   => 'ගවේෂණය',
'fileduplicatesearch-info'     => '$1 × $2 පික්සල<br />ගොනු විශාලත්වය: $3<br />MIME ශෛලිය: $4',
'fileduplicatesearch-result-1' => '"$1" ගොනුවට සර්වසම අනුපිටපතක් නොමැත.',
'fileduplicatesearch-result-n' => '"$1" ගොනුවට {{PLURAL:$2|සර්වසම අනුපිටපතක්|සර්වසම අනුපිටපත් $2 ක්}} ඇත.',

# Special:SpecialPages
'specialpages'                   => 'විශේෂ පිටු',
'specialpages-note'              => '----
* සාමාන්‍ය විශේෂ පිටු.
* <strong class="mw-specialpagerestricted">සීමිත විශේෂ පිටු.</strong>',
'specialpages-group-maintenance' => 'නඩත්තු වාර්තා',
'specialpages-group-other'       => 'අනෙකුත් විශේෂ පිටු',
'specialpages-group-login'       => 'ප්‍රවිෂ්ට වන්න / ගිණුමක් තනන්න',
'specialpages-group-changes'     => 'මෑත වෙනස්වීම් හා ලඝු-සටහන්',
'specialpages-group-media'       => 'මාධ්‍ය වාර්තා හා උඩුගත කිරීම්',
'specialpages-group-users'       => 'පරිශීලකයන් හා හිමිකම්',
'specialpages-group-highuse'     => 'බෙහෙවින් භාවිත වන පිටු',
'specialpages-group-pages'       => 'පිටු ලැයිස්තු',
'specialpages-group-pagetools'   => 'පිටු මෙවලම්',
'specialpages-group-wiki'        => 'විකි දත්ත හා මෙවලම්',
'specialpages-group-redirects'   => 'විශේෂ පිටු යළි-යොමුකිරීම',
'specialpages-group-spam'        => 'අයාචිත-තැපෑල මෙවලම්',

# Special:BlankPage
'blankpage'              => 'හිස් පිටුව',
'intentionallyblankpage' => 'මෙම පිටුව අභිප්‍රේතව හිස්ව තබන ලදි',

# External image whitelist
'external_image_whitelist' => '#මෙම පේළිය මෙම අයුරින්ම තිබීමට ඉඩ හරින්න <pre>
#ක්‍රමවත් ප්‍රකාශ ඛණ්ඩයන් (// අතරතුර පවතින කොටස පමණක්) පහත තබන්න
#බාහිර (සෘජු-බැඳි) රූපයන්හි අන්තර්ජාල ලිපිනයන් හා සමගින් මේවා ගලපනු ඇත 
#ගැලපෙන ඒවා රූප වශයෙන් ප්‍රදර්ශනය කෙරෙනු ඇත, නැත‍ෙහාත් රූපයට සබැඳියක් පමණක් පෙන්වනු ඇත 
# # වෙතින් ඇරඹෙන පේළි පරිකථන ලෙසන් සැලකේ 
#‍මෙය අකුරු-ප්‍රමාණ-පිළිබඳ-සංවේදී නොවේ

# regex කොටස් සියල්ල මෙම පේළියට ඉහලින් බහාලන්න. මෙම පේළිය මෙම අයුරින්ම තිබීමට ඉඩ හරින්න </pre>',

# Special:Tags
'tags'                    => 'නීතික ලෙසින් වෙනස් කල හැකි ටැගයන්',
'tag-filter'              => '[[Special:Tags|ටැග]] පෙරහන:',
'tag-filter-submit'       => 'පෙරහන',
'tags-title'              => 'ටැගයන්',
'tags-intro'              => 'මෘදුකාංගය විසින් සංස්කරණයක් සිදුකල හැකි ටැගයන් මෙම පිටුවෙහි ලැයිස්තුගත කොට ඒවායේ තේරුම් දක්වා ඇත.',
'tags-tag'                => ' ටැග් නම',
'tags-display-header'     => 'වෙනස්කම් ලැයිස්තුන්හී පෙනුම',
'tags-description-header' => 'තේරුමෙහි පූර්ණ විස්තරය',
'tags-hitcount-header'    => 'ටැගගත කෙරුණු වෙනස්කම්',
'tags-edit'               => 'සංස්කරණය',
'tags-hitcount'           => '{{PLURAL:$1|වෙනස්කම|වෙනස්කම් $1 }}',

# Special:ComparePages
'comparepages'     => 'පිටු සසඳන්න',
'compare-selector' => 'පිටුවේ සංශෝධන සසඳන්න',
'compare-page1'    => 'පිටුව 1',
'compare-page2'    => 'පිටුව 2',
'compare-rev1'     => '1වන සංශෝධනය',
'compare-rev2'     => '2වන සංශෝධනය',
'compare-submit'   => 'සසඳන්න',

# Database error messages
'dberr-header'      => 'මෙම විකියෙහි ගැටළුවක් පවතියි',
'dberr-problems'    => 'සමාවන්න! මෙම අඩවිය තාක්ෂණික ගැටළු අත්දකියි.',
'dberr-again'       => 'විනාඩි කිහිපයක් කල්ගතකර යළි-බාගැනුම උත්සාහ කරන්න.',
'dberr-info'        => '(දත්තගබඩා සේවාදායකය හා සම්බන්ධ වීම‍ට නොහැක: $1)',
'dberr-usegoogle'   => 'මේ අතරතුර ගූගල් ඔස්සේ ගවේෂණය කිරීමට ඔබ විසින් යත්න දැරිය හැක.',
'dberr-outofdate'   => 'අපගේ අන්තර්ගතයෙහි සූචියන් යල් පැන ගොස් තිබිය හැකි බව සටහන් කර ගන්න.',
'dberr-cachederror' => 'මෙය ඉල්ලා ඇති පිටුවෙහි පූර්වාපේක්ෂිත සංචිත පිටුවක් වන අතර එය යාවත්කාලින නොවිය හැකි බව සලකන්න.',

# HTML forms
'htmlform-invalid-input'       => 'ඔබගේ සමහරක් ප්‍රදානයන් විෂයයෙහි ගැ‍ටළු ඇත',
'htmlform-select-badoption'    => 'ඔබ විසින් හුවාදක්වා ඇති අගය නීතික විකල්පයක් නොවේ.',
'htmlform-int-invalid'         => 'ඔබ විසින් හුවාදක්වා ඇති අගය පූර්ණාංකයක් නොවේ.',
'htmlform-float-invalid'       => 'ඔබ ඉදිරිපත් කල අගය සංඛ්‍යාවක් නොවේ.',
'htmlform-int-toolow'          => 'ඔබ විසින් හුවාදක්වා ඇති අගය, අවම අගය වන $1 ට වඩා අඩුය',
'htmlform-int-toohigh'         => 'ඔබ විසින් හුවාදක්වා ඇති අගය, උපරිම අගය වන $1 ට වඩා වැඩිය',
'htmlform-required'            => 'මෙම අගය ඇවැසිය',
'htmlform-submit'              => 'යොමුකරන්න',
'htmlform-reset'               => 'වෙනස්කිරීම් අහෝසිකරන්න',
'htmlform-selectorother-other' => 'වෙනත්',

);
