<?php
/** Sinhala (සිංහල)
 *
 * @ingroup Language
 * @file
 *
 * @author Asiri wiki
 * @author Chandana
 * @author නන්දිමිතුරු
 */

$namespaceNames = array(
	NS_MEDIA          => 'මාධ්‍යය',
	NS_SPECIAL        => 'විශේෂ',
	NS_TALK           => 'සාකච්ඡාව',
	NS_USER           => 'පරිශීලක',
	NS_USER_TALK      => 'පරිශීලක_සාකච්ඡාව',
	# NS_PROJECT set by \$wgMetaNamespace
	NS_PROJECT_TALK   => '$1_සාකච්ඡාව',
	NS_IMAGE          => 'රූපය',
	NS_IMAGE_TALK     => 'රූපය_සාකච්ඡාව',
	NS_MEDIAWIKI      => 'විකිමාධ්‍ය',
	NS_MEDIAWIKI_TALK => 'විකිමාධ්‍ය_සාකච්ඡාව',
	NS_TEMPLATE       => 'සැකිල්ල',
	NS_TEMPLATE_TALK  => 'සැකිල_සාකච්ඡාව',
	NS_HELP           => 'උදවු',
	NS_HELP_TALK      => 'උදව_සාකච්ඡාව',
	NS_CATEGORY       => 'ප්‍රවර්ගය',
	NS_CATEGORY_TALK  => 'ප්‍රවර්ග_සාකච්ඡාව',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'ආපසු_හරවා_යවනවා' ),
	'BrokenRedirects'           => array( 'වැරදුනු_යොමුකිරිමක්' ),
	'Userlogin'                 => array( 'ඇතුලුවිම' ),
	'Userlogout'                => array( 'ඉවත්විම' ),
	'CreateAccount'             => array( 'සාමාජිකත්වය_ලැබිමට' ),
	'Preferences'               => array( 'මනාපය' ),
	'Watchlist'                 => array( 'මුරකරනවා' ),
	'Recentchanges'             => array( 'නව_වෙනස්විමි' ),
	'Upload'                    => array( 'ගොනුවක්_ඇතූලත්_කිරිම' ),
	'Imagelist'                 => array( 'පින්තූර_ලැයිස්තුව' ),
	'Newimages'                 => array( 'අලුත්_පින්තූර' ),
	'Listusers'                 => array( 'සාමාජික_ලැයිස්තුව' ),
	'Statistics'                => array( 'සංඛ්‍යා_ලේඛනය' ),
	'Randompage'                => array( 'අහඹු_ලෙස', 'අහඹු_පිටුව' ),
	'Lonelypages'               => array( 'හුදකලා_පිටුව' ),
	'Uncategorizedpages'        => array( 'වර්ගනොකල_පිටුව' ),
	'Uncategorizedcategories'   => array( 'වර්ගනොකල_කොටස්' ),
	'Uncategorizedimages'       => array( 'වර්ගනොකල_පින්තූර' ),
	'Uncategorizedtemplates'    => array( 'වර්ගනොකල_අචිචු' ),
	'Unusedcategories'          => array( 'හාවිතා_නොවන_කොටස්' ),
	'Unusedimages'              => array( 'හාවිතා_නොවන_පින්තූර' ),
	'Wantedpages'               => array( 'අවශය_පිටු' ),
	'Wantedcategories'          => array( 'අවශය_කොටස' ),
	'Mostlinked'                => array( 'ජනපිය_සම්බන්ධකය' ),
	'Mostlinkedcategories'      => array( 'වැඬ්පුර_භව්තාවු_කොටස' ),
	'Mostlinkedtemplates'       => array( 'වැඬ්පුර_භව්තාවු_අච්චු' ),
	'Mostcategories'            => array( 'ජනපිය_කොටස' ),
	'Mostimages'                => array( 'අතිශය_පින්තූර' ),
	'Mostrevisions'             => array( 'අතිශය_පරිශෝධනය' ),
	'Shortpages'                => array( 'කෙට_පිටුව' ),
	'Longpages'                 => array( 'දිග_පිටුව' ),
	'Newpages'                  => array( 'නව_පිටුව' ),
	'Ancientpages'              => array( 'අතීත_පිටුව' ),
	'Deadendpages'              => array( 'අඩු_කරන_පිටුව' ),
	'Protectedpages'            => array( 'ආරක්ෂිත_පිටුව' ),
	'Protectedtitles'           => array( 'ආරක්ෂිත__හිමිකම' ),
	'Allpages'                  => array( 'සියල_පිටුව' ),
	'Prefixindex'               => array( 'උපසර්ගය' ),
	'Specialpages'              => array( 'විශෝෂ_පිටුව' ),
	'Contributions'             => array( 'දායකත්වය' ),
	'Emailuser'                 => array( 'පරිශීලකට_ඉ-ලිපිය_යැවිම' ),
	'Confirmemail'              => array( 'ඉ-ලිපිය_තහවුරු_කරනවා' ),
	'Recentchangeslinked'       => array( 'නුතන_වෙනස්_වීම' ),
	'Movepage'                  => array( 'පිටුව_ගෙන_යනවා' ),
	'Blockme'                   => array( 'මමම_අවහිර_කරනවා' ),
	'Booksources'               => array( 'පුස්තක' ),
	'Categories'                => array( 'වර්ගකරිම' ),
	'Export'                    => array( 'අපනයනය' ),
	'Version'                   => array( 'අනුවාදය' ),
	'Allmessages'               => array( 'සියලු_පණිිවිඩ' ),
	'Log'                       => array( 'කඳ' ),
	'Import'                    => array( 'ආයාත' ),
	'Randomredirect'            => array( 'අහඹු_ලෙස_යොමුකිරිම' ),
	'Mypage'                    => array( 'මගේ__පිටුව' ),
	'Mytalk'                    => array( 'මගේ__කතාබහ' ),
	'Mycontributions'           => array( 'මගේ_දායකත්වය' ),
	'Popularpages'              => array( 'ජනප්‍රිය_පිටුව' ),
	'Search'                    => array( 'සෙවුම' ),
	'Resetpass'                 => array( 'මුර_පදය_යළි_පිහිටුවනවා' ),
	'Withoutinterwiki'          => array( 'පටන_අන්තර්_විකි' ),
	'MergeHistory'              => array( 'ඉතිහාසය_සංයුක්ත_කිරිම' ),
	'Filepath'                  => array( 'ගොනු_පථය' ),
);

$messages = array(
# User preference toggles
'tog-underline'       => 'පුරුක යටින් ඉරි අඳිනවා',
'tog-highlightbroken' => ' කැඩුණු සන්ධිය ආකෘතිය <a href="" වර්ගය="අලුත">මේ සමාන ලෙස </a> (විකල්ප: මේ සමාන ලෙස<a href="" වර්ගය="අභ්‍යනතර">?</a>).',
'tog-justify'         => 'ඡේදය පේළි ගසන්න',
'tog-hideminor'       => 'අලුත් වෙනසහි සුළු සංස්කරණය හැංගිම',
'tog-editsection'     => '[සංස්කරණය] බැඳියාවන් මගින් ඡේද සංස්කරණයට ඉඩ සැලසීම',

# Dates
'sunday'        => 'ඉරිදා',
'monday'        => 'සඳුදා',
'tuesday'       => 'අඟහරුවාදා',
'wednesday'     => 'බදාදා',
'thursday'      => 'බ්‍රහස්පතින්දා',
'friday'        => 'සිකුරාදා',
'saturday'      => 'සෙනසුරාදා',
'sun'           => 'ඉරිදා',
'mon'           => 'සඳු',
'tue'           => 'අඟ',
'wed'           => 'බදා',
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
'mar'           => 'මාර්',
'apr'           => 'අප්‍රේ',
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
'hidden-category-category'       => 'සැඟවුනු ප්‍රවර්ග', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|මෙම ප්‍රවර්ගය සතු වන්නේ පහත දැක්වෙන උපප්‍රවර්ගය පමණි.|මෙම ප්‍රවර්ගය සතු මුළු $2 උපප්‍රවර්ග ගණන අතර, පහත දැක්වෙන {{PLURAL:$1|උපප්‍රවර්ගය|උපප්‍රවර්ග $1 }} වේ.}}',
'category-subcat-count-limited'  => 'මෙම ප්‍රවර්ගයට පහත දැක්වෙන {{PLURAL:$1|උපප්‍රවර්ගය| උපප්‍රවර්ග $1 ගණන}} අඩංගු වේ.',
'category-article-count'         => '{{PLURAL:$2|මෙම ප්‍රවර්ගය සතු වන්නේ මෙහි පහත දැක්වෙන පිටුව පමණි.|සමස්ත $2 පිටු ගණන අතුරින්, {{PLURAL:$1|පිටුව|පිටු $1 ගණනක්}} මෙම ප්‍රවර්ගය සතුවේ.}}',
'category-article-count-limited' => 'මෙහි පහත දෑක්වෙන {{PLURAL:$1|පිටුව|පිටු $1 ගණන}} අයත් වනුයේ වත්මන් ප්‍රවර්ගයටය.',
'category-file-count'            => '{{PLURAL:$2|මෙම ප්‍රවර්ගයට අයත් වන්නේ පහත දැක්වෙන ගොනුව පමණි.|සමස්ත $2 ගොනු ගණන අතුරින්, මෙහි පහත දැක්වෙන {{PLURAL:$1|ගොනුව|ගොනු $1 ගණන}} මෙම ප්‍රවර්ගය සතු වේ.}}',
'category-file-count-limited'    => 'මෙහි පහත දැක්වෙන {{PLURAL:$1|ගොනුව|ගොනු $1 ගණන}} අයත් වන්නේ වත්මන් ප්‍රවර්ගයටය.',
'listingcontinuesabbrev'         => 'ඉතිරිය.',

'mainpagetext'      => "<big>'''මාධ්‍යවිකි සාර්ථක ලෙස ස්ථාපනය කරන ලදි.'''</big>",
'mainpagedocfooter' => 'විකි මෘදුකාංග භාවිතා කිරීම පිළිබඳ තොරතුරු සඳහා  [http://meta.wikimedia.org/wiki/Help:Contents පරිශීලකයන් සඳහා නියමුව] හදාරන්න.

== ඇරඹුම ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings වින්‍යාස සැකසුම් ලැයිස්තුව]
* [http://www.mediawiki.org/wiki/Manual:FAQ මාධ්‍යවිකි නිතර-අසන-පැන]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce මාධ්‍යවිකි නිකුතුව තැපැල් ලැයිස්තුව]',

'about'          => 'පිළිබඳ',
'article'        => 'පටුන',
'newwindow'      => '(නව කවුළුවක විවෘත වේ)',
'cancel'         => 'අවලංගු කරන්න',
'qbfind'         => 'සොයන්න',
'qbbrowse'       => 'පිරික්සන්න',
'qbedit'         => 'සංස්කරණය',
'qbpageoptions'  => 'මෙම පිටුව',
'qbpageinfo'     => 'සන්දර්භය',
'qbmyoptions'    => 'මගේ පිටු',
'qbspecialpages' => 'විශේෂ පිටු',
'moredotdotdot'  => 'තවත්...',
'mypage'         => 'මගේ පිටුව',
'mytalk'         => 'මගේ සාකච්ඡා',
'anontalk'       => 'මෙම අන්තර්ජාල ලිපිනය සඳහා සාකච්ඡාව',
'navigation'     => 'හසුරවන්න',
'and'            => 'සහ',

# Metadata in edit box
'metadata_help' => 'පාරදත්ත:',

'help'             => 'උදව්',
'search'           => 'සොයන්න',
'searchbutton'     => 'සොයන්න',
'go'               => 'යන්න',
'searcharticle'    => 'යන්න',
'history'          => 'පිටුවේ ඉතිහාසය',
'history_short'    => 'ඉතිහාසය',
'info_short'       => 'තොරතුරු',
'printableversion' => 'මුද්‍රණ ආකෘතිය',
'permalink'        => 'ස්ථාවර සබැඳුම',
'edit'             => 'සංස්කරණය කරන්න',
'create'           => 'සකසන්න',
'delete'           => 'මකන්න',
'deletethispage'   => 'මෙම පිටුව මකන්න',
'protect'          => 'සුරකින්න',
'protectthispage'  => 'මෙම පිටුව සුරකින්න',
'newpage'          => 'නව පිටුව',
'talkpagelinktext' => 'සාකච්ඡාව',
'specialpage'      => 'විශේෂ පිටුව',
'personaltools'    => 'පුද්ගලික මෙවලම්',
'talk'             => 'සාකච්ඡා පිටුව',
'toolbox'          => 'මෙවලම්',
'otherlanguages'   => 'වෙනත් භාෂා වලින්',
'protectedpage'    => 'ආරක්ෂිත පිටුව',
'jumptosearch'     => 'සොයන්න',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ගැන
<!--{{SITENAME}}About-->',
'currentevents'        => 'කාලීන සිදුවීම්',
'edithelp'             => 'සංස්කරණ උදව්',
'edithelppage'         => 'Help:සංස්කරණ',
'helppage'             => 'Help:පටුන',
'mainpage'             => 'මුල් පිටුව',
'mainpage-description' => 'මුල් පිටුව',
'portal'               => 'ප්‍රජා ද්වාරය',
'portal-url'           => 'Project:ප්‍රජා ද්වාරය',
'privacy'              => 'රහස්‍යභාවය ප්‍රතිපත්තිය',
'privacypage'          => 'Project: රහස්‍යභාවය ප්‍රතිපත්තිය',

'badaccess'        => 'අවසර දෝෂය',
'badaccess-group0' => 'ඔබ විසින් අයැදුම් කර සිටි කාර්යය ක්‍රියාත්මක කිරීමට ඔබ හට ඉඩ ලබා දෙනු නොලැබේ.',
'badaccess-groups' => 'ඔබ අයැදුම් කර සිටි කාර්යය, {{PLURAL:$2|ඉදිරි කාණ්ඩයට|ඉදිරි කාණ්ඩ සමූහය අතුරින් එකකට}}: $1,  අයත් පරිශීලකයන්ගේ පරිහරණයට සීමා කර ඇත.',

'versionrequired'     => 'මාධ්‍යවිකි $1 අනුවාදය අවශ්‍ය වේ',
'versionrequiredtext' => 'මෙම පිටුව භාවිතා කිරීමට, මාධ්‍යවිකි හි $1 අනුවාදය අවශ්‍ය වේ.
[[Special:Version|අනුවාද පිටුව]] බලන්න.',

'ok'                      => 'හරි',
'retrievedfrom'           => '"$1" වෙතින් නැවත ලබාගන්නා ලදි',
'youhavenewmessages'      => 'ඔබ හට $1 ($2) ඇත.',
'newmessageslink'         => 'නව පණිවුඩ',
'newmessagesdifflink'     => 'අවසාන වෙනස',
'youhavenewmessagesmulti' => 'ඔබ හට $1 හි නව පණිවුඩ ඇත',
'editsection'             => 'සංස්කරණය',
'editold'                 => 'සංස්කරණය',
'viewsourceold'           => 'මූලාශ්‍රය නරඹන්න',
'editsectionhint'         => 'ඡේද සංස්කරණය: $1',
'toc'                     => 'පටුන',
'showtoc'                 => 'පෙන්වන්න',
'hidetoc'                 => 'සඟවන්න',
'thisisdeleted'           => 'අවශ්‍යතාවය $1 නැරඹීමද නැතහොත් ප්‍රතිෂ්ඨාපනයද?',
'viewdeleted'             => '$1 නැරඹීම අවශ්‍යයද?',
'restorelink'             => '{{PLURAL:$1|මකා දමනු ලැබූ එක් සංස්කරණයක්|$1 මකා දමනු ලැබූ සංස්කරණ ගණනක්}}',
'red-link-title'          => '$1 (තවමත් ලියා නොමැත)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ලිපිය',
'nstab-user'      => 'පරිශීලක පිටුව',
'nstab-media'     => 'මාධ්‍ය පිටුව',
'nstab-special'   => 'විශේෂ',
'nstab-project'   => 'ව්‍යාපෘති පිටුව',
'nstab-image'     => 'ගොනුව',
'nstab-mediawiki' => 'පණිවුඩය',
'nstab-template'  => 'සැකිල්ල',
'nstab-help'      => 'උදවු පිටුව',
'nstab-category'  => 'ප්‍රවර්ගය',

# Main script and global functions
'nosuchaction'      => 'මෙම නමැති කාර්යයක් නොමැත',
'nosuchspecialpage' => 'මෙම නමැති විශේෂ පිටුවක් නොමැත',
'nospecialpagetext' => "<big>'''ඔබ අයැද ඇත්තේ අවලංගු විශේෂ පිටුවකි.'''</big>

වලංගු විශේෂ පිටු දැක්වෙන ලැයිස්තුවක් [[Special:SpecialPages|{{int:specialpages}}]]හිදී ඔබහට සම්භ වනු ඇත.",

# General errors
'error'                => 'දෝෂය',
'databaseerror'        => 'පරිගණක දත්ත-ගබඩා දෝෂය',
'nodb'                 => 'පරිගණක දත්ත-ගබඩාව $1 තෝරාගත නොහැකි විය',
'laggedslavemode'      => 'අවවාදයයි: මෙම පිටුවෙහි මෑතදී සිදු කල  යාවත්නාල කිරීම් අඩංගු නොවිය හැක.',
'missingarticle-rev'   => '(පරිශෝධනය#: $1)',
'missingarticle-diff'  => '(වෙනස: $1, $2)',
'internalerror'        => 'අභ්‍යන්තර දෝෂය',
'internalerror_info'   => 'අභ්‍යන්තර දෝෂය: $1',
'filecopyerror'        => '"$1" ගොනුව "$2" වෙත පිටපත් කිරීමට නොහැකි විය.',
'filerenameerror'      => '"$1" ගොනුව "$2" බවට යළි-නම්-කිරීම සිදු කල නොහැකි විය.',
'filedeleteerror'      => '"$1" ගොනුව මකා-දැමිය නොහැකි විය.',
'filenotfound'         => '"$1" ගොනුව සොයා ගත නොහැකි විය.',
'unexpected'           => 'අනපේක්‍ෂිත අගය: "$1"="$2".',
'badarticleerror'      => 'මෙම පිටුව විෂයයෙහි මෙම කාර්යය ඉටු නල නොහැකි විය.',
'cannotdelete'         => 'නිරූපිත පිටුව හෝ ගොනුව හෝ මකා දැමිය නොහැකි විය.
අනෙකෙකු විසින් දැනටමත් ‍මකා දැමීම සිදු කර ඇතිවා විය හැක.',
'badtitle'             => 'නුසුදුසු මාතෘකාවක්',
'viewsource'           => 'මූලාශ්‍රය නරඹන්න',
'viewsourcetext'       => 'මෙම පිටුවෙහි මූලාශ්‍රය නැරඹීමට හා පිටපත් කිරීමට ඔබ හට හැකිය:',
'namespaceprotected'   => "'''$1''' නාමඅවකාශයෙහි පිටු සංස්කරණය කිරීමට ඔබහට අවසර නොමැත.",
'customcssjsprotected' => 'තවත් පරිශීලකයෙකුගේ පෞද්ගලික පරිස්ථිතිය අඩංගු වන බැවින්, මෙම පිටුව සංස්කරණය කිරීමට ඔබ හට අවසර නොමැත.',
'ns-specialprotected'  => 'විශේෂ පිටු සංස්කරණය කිරීම සිදු කල නොහැක.',

# Login and logout pages
'welcomecreation'            => '== ආයුබෝවන්, $1! ==

ඔබ‍ගේ ගිණුම තැනී ඇත.
ඔබ‍ගේ [[Special:Preferences|{{SITENAME}} අභිරුචි ]] වෙනස් කර ගන්න අමතක කරන්න එපා.',
'loginpagetitle'             => 'පරිශීලක ප්‍රවිෂ්ටය',
'yourname'                   => 'පරිශීලක නාමය:',
'yourpassword'               => 'මුරපදය:',
'yourpasswordagain'          => 'මුරපදය නැවත ලියන්න:',
'remembermypassword'         => 'මාගේ ප්‍රවිෂ්ටය පිළිබඳ විස්තර මෙම පරිගණක මතකයෙහි රඳවා තබා ගන්න',
'yourdomainname'             => 'ඔබගේ වසම:',
'loginproblem'               => '<b>ඔබගේ ප්‍රවිෂ්ටය පිළිබඳ ගැටළුවක් පැන නැගී ඇත.</b><br />නැවත උත්සාහ කරන්න!',
'login'                      => 'ප්‍රවිෂ්ටය',
'nav-login-createaccount'    => 'ප්‍රවිෂ්ට වන්න / ගිණුමක් තනන්න',
'userlogin'                  => 'ප්‍රවිෂ්ට වන්න / ගිණුමක් තනන්න',
'logout'                     => 'පිටවන්න',
'notloggedin'                => 'ප්‍රවිෂ්ට වී නොමැත',
'nologin'                    => 'ඔබ හට ගිණුමක් නොමැතිද? $1.',
'nologinlink'                => 'ගිණුමක් තනන්න',
'createaccount'              => 'ගිණුමක් තනන්න',
'gotaccount'                 => 'දැනටමත් ගිණුමක් තිබේද?$1.',
'gotaccountlink'             => 'ප්‍රවිෂ්ට වන්න',
'createaccountmail'          => 'විද්‍යුත් තැපෑල මගින්',
'badretype'                  => 'ඔබ ඇතුළු කල මුරපද නොගැලපෙති.',
'userexists'                 => 'ඔබ ඇතුළු කල පරිශීලක නාමය දැනටමත් භාවිතයෙහි ඇත.
කරුණාකර වෙනස් නමක් තෝරා ගන්න.',
'youremail'                  => 'විද්‍යුත් තැපෑල:',
'username'                   => 'පරිශීලක නාමය:',
'uid'                        => 'පරිශීලක අනන්‍යතාව:',
'yourrealname'               => 'සැබෑ නාමය:',
'yourlanguage'               => 'භාෂාව:',
'yournick'                   => 'විකල්ප නාමය:',
'email'                      => 'විද්‍යුත් තැපෑල',
'prefs-help-email-required'  => 'විද්‍යුත් තැපෑල් ලිපිනය අවශ්‍යයි.',
'mailmypassword'             => 'මුරපදය විද්‍යුත් තැපෑල‍ට යවන්න',
'acct_creation_throttle_hit' => 'කණගාටුයි, ඔබ දැනටමත් {{PLURAL:$1|එක් ගිණුමක් |ගිණුම් $1 ක්}} තනා ඇත.
ඔබට තවත් ගිණුම් තැනිය නොහැක.',
'emailconfirmlink'           => 'ඔබගේ විද්‍යුත් තැපැල් ලිපිනය තහවුරු කරන්න',
'loginlanguagelabel'         => 'භාෂාව: $1',

# Password reset dialog
'resetpass_header' => 'මුරපදය යළි පිහිටුවන්න',

# Edit page toolbar
'bold_sample'   => 'තදකුරු',
'bold_tip'      => 'තදකුරු',
'italic_sample' => 'ඇලකුරු',
'italic_tip'    => 'ඇලකුරු',
'link_tip'      => 'අභ්‍යන්තර සබැඳිය',
'math_tip'      => 'ගණිත සුත්‍ර(LaTeX)',
'media_tip'     => 'ගොනු සබැඳිය',

# Edit pages
'summary'                => 'සාරාංශය',
'minoredit'              => 'මෙය සුළු සංස්කරණයකි',
'savearticle'            => 'පිටුව සුරකින්න',
'preview'                => 'පෙරදසුන',
'showpreview'            => 'පෙරදසුන පෙන්වන්න',
'showdiff'               => 'වෙනස්වීම් පෙන්වන්න',
'anoneditwarning'        => "'''අවවාදයයි:''' ඔබ පරිශීලකයෙකු වශයෙන් පද්ධතියට ප්‍රවිෂ්ට වී නොමැත.
එමනිසා මෙම පිටුවෙහි සංස්කරණ ඉතිහාසයෙහි, ඔබගේ අන්තර්ජාල ලිපිනය සටහන් කරගැනීමට සිදුවනු ඇත.",
'newarticletext'         => "බැඳියක් ඔස්සේ පැමිණ ඔබ අවතීර්ණ වී ඇත්තේ දැනට නොපවතින ලිපියකටයි.
මෙම ලිපිය තැනීමට එනම් නිමැවීමට අවශ්‍ය නම්, පහත ඇති කොටුව තුල අකුරු ලිවීම අරඹන්න (වැඩිමනත් තොරතුරු සඳහා [[{{MediaWiki:Helppage}}|උදවු පිටුව]] බලන්න).
ඔබ මෙහි අවතීර්ණ වී ඇත්තේ කිසියම් අත්වැරැද්දකින් බව හැ‍‍ඟෙන්නේ නම්, ඔබගේ සැරිසරයෙහි (බ්‍රවුසරයෙහි) '''පසුපසට''' බොත්තම ක්ලික් කරන්න.",
'previewnote'            => '<strong>මෙය පෙරදසුනක් පමණකි;
වෙනස්කම් සුරැකීම තවමත් සිදුකොට නොමැත!</strong>',
'editing'                => '$1 සංස්කරණය කරමින් පවතියි',
'editingsection'         => '$1 (ඡේදය) සංස්කරණය කරමින් පවතියි',
'editingcomment'         => '$1 (පරිකථනය) සංස්කරණය කරමින් පවතියි',
'yourtext'               => 'ඔබගේ පෙළ',
'copyrightwarning'       => "Please note that all contributions to {{SITENAME}} are considered to be released under the $2 (see $1 for details). If you don't want your writing to be edited mercilessly and redistributed at will, then don't submit it here.<br />
එසේ ම මෙය ඔබ විසින් ම ලියූ බවට හෝ පොදු විෂයපථයකින්, ඊ‍ට ස‍මාන නිදහස් මූලාශ්‍රයකින් උපුටා ගත් බව‍ට හෝ අපහ‍‍ට සහතික විය යුතු ය. (තොරතුරු සඳහා $1 බලන්න).
<strong>හිමිකම් ඇවුරුණු දේ අනවසරයෙන් ප්‍රකාෂ කිරිමෙන් වලකින්න!</strong>",
'copyrightwarning2'      => "Please note that all contributions to {{SITENAME}} may be edited, altered, or removed by other contributors. If you don't want your writing to be edited mercilessly, then don't submit it here.<br />
එසේ ම මෙය ඔබ විසින් ම ලියූ බවට හෝ පොදු විෂයපථයකින්, ඊ‍ට ස‍මාන නිදහස් මූලාශ්‍රයකින් උපුටා ගත් බව‍ට හෝ අපහ‍‍ට සහතික විය යුතු ය. (තොරතුරු සඳහා $1 බලන්න).
<strong>හිමිකම් ඇවුරුණු දේ අනවසරයෙන් ප්‍රකාෂ කිරිමෙන් වලකින්න!</strong>",
'template-protected'     => '(රක්ෂිත)',
'template-semiprotected' => '(අර්ධ-රක්ෂිත)',
'hiddencategories'       => 'මෙම පිටුව, {{PLURAL:$1| එක් සැඟවුණු ප්‍රවර්ගයක| සැඟවුණු ප්‍රවර්ගයන් $1 ගණනක}} අවයවයක් වේ:',
'edittools'              => '<!-- Text here will be shown below edit and upload forms. -->
<div class="plainlinks" style="margin-top:1px;border-width:1px;border-style:solid;border-color:#aaaaaa;padding:2px;">
<div id="specialchars" class="plainlinks" style="margin-top:1px; border-width:1px; border-style:solid; border-color:#aaaaaa; border-left-width:0; border-right-width:0; padding:2px;">
<p class="specialbasic" id="සිංහල">
<charinsert>ං ඃ</charinsert> ·
<charinsert>අ ආ ඇ ඈ ඉ ඊ උ ඌ ඍ ඎ ඏ ඐ  </charinsert> ·
<charinsert>එ ඒ ඓ ඔ ඕ ඖ ක ඛ ග ඝ </charinsert> ·
<charinsert>ඞ ඟ ච ඡ ජ ඣ ඤ ඥ ඦ </charinsert> ·
<charinsert>ට ඨ ඩ ඪ ණ ඬ ත ථ ද ධ </charinsert> ·
<charinsert>න ඳ ප ඵ බ භ ම ඹ ය ර </charinsert> ·
<charinsert>ල ව ශ ෂ ස හ ළ ෆ</charinsert> ·
<charinsert>්්  ා  ැ  ෑ    ි   ී  ු    ූ  ෘ </charinsert> ·
<charinsert>ෙ ේ  ෛ  ො  ෝ  ෞ  ෟ  ෲ ෳ </charinsert> ·
<charinsert>෴ </charinsert> ·
<charinsert> ‌ </charinsert> (ZWNJ) ·
<charinsert> ‍ </charinsert> (ZWJ) ·

</p>
<p class="specialbasic" id="Wiki">
<charinsert> {{}} {{{}}}   |   []   [[]] <nowiki><div class=text></nowiki>+<nowiki></div></nowiki>  [[]]   &nbsp; <nowiki><poem></nowiki>+<nowiki></poem></nowiki> [[ද්වාරය:+]] [[ප්‍රවර්ගය:+]] [[රූපය:+]] #REDIRECT  <ref>+</ref> <nowiki><references /></nowiki> </charinsert> ·
<charinsert>  <nowiki><s>+</s></nowiki>   <nowiki><sup>+</sup></nowiki>   <nowiki><sub>+</sub></nowiki>   <nowiki><code></code></nowiki>  <nowiki><blockquote>+</blockquote></nowiki> {{DEFAULTSORT:}} </charinsert> ·
<charinsert> <nowiki><math></nowiki>+<nowiki></math></nowiki> <nowiki><nowiki></nowiki>+<nowiki></nowiki></nowiki> <nowiki><noinclude></nowiki>+<nowiki></noinclude></nowiki> <nowiki><includeonly></nowiki>+<nowiki></includeonly></nowiki> <!-- -->  <span class="plainlinks"></span> </charinsert> ·
<charinsert> ~~~ ~~~~ </charinsert>
</p>
</div>
</div>
<div id="edittoolsinfo"></div>',
'nocreatetitle'          => 'පිටු තැනීම සීමා කර ඇත',
'nocreatetext'           => 'නව පිටු තැනීමේ හැකියාව {{SITENAME}} විසින් සීමාකර ඇත.
ඔබ හට පෙරළා ගොස්,  දැනට පවතින පිටුවක් සංස්කරණය කිරීම හෝ,  [[Special:UserLogin|ගිණුමකට ප්‍රවිෂ්ට වීම හෝ  නව ගිණුමක් තැනීම හෝ]] සිදුකල හැක.',
'nocreate-loggedin'      => '{{SITENAME}} හි නව පිටු තැනීමට අවසරයක් ඔබ හට ප්‍රදානය කොට නොමැත.',
'permissionserrors'      => 'අවසරයන් පිළිබඳ දෝෂයන් පවතී',
'permissionserrorstext'  => 'පහත දැක්වෙන {{PLURAL:$1|හේතුව|හේතූන්}} නිසා, ඔබ හට එය සිදුකිරීමට අවසර ලබා දීමට නොහැක:',
'recreate-deleted-warn'  => "'''අවවාදයයි: පෙරදී මකා දැමූ ගොනුවක් ඔබ විසින් යලි-තනමින් පවතියි.'''

මෙම පිටුව සංස්කරණය කිරීම තවදුරටත් සිදුකරගෙන යාම සුදුසුද යන වග ඔබ විසින් සලකා බැලිය යුතුය.
මෙම පිටුවට අදාල මකා දැමීම් පිළිබඳ විස්තර දැක්වෙන මකා-දැමීම්-ලඝු-සටහන ඔබගේ පහසුව තකා මෙහි දක්වා ඇත:",
'deleted-notice'         => 'මෙම පිටුව මකා දමා ඇත.
මෙම පිටුවට අදාල වන මකා දැමීම් සටහන් කර ඇති  මකා-දැමීම්-ලඝු-සටහන, ඔබගේ සැඳහුම සඳහා, මෙහි පහත දක්වා ඇත.',
'deletelog-fulllog'      => 'සම්පූර්ණ ලඝු-සටහන නරඹන්න',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'අවවාදයයි: සැකිලි අඩංගු කිරීමේ ප්‍රමාණය අවසර ලබා දී ඇති පමණට වඩා විශාලයි.
සමහරක් සැකිලි අඩංගු නොකරනු ඇත.',
'post-expand-template-inclusion-category' => 'මෙම පිටු තුල, සැකිලි අඩංගු කිරීමේ පුමාණය, අවසර දී ඇති සීමා ඉක්මවා ගොස් ඇත',
'post-expand-template-argument-warning'   => 'අවවාදයයි: ව්‍යාප්ති ප්‍රමාණය ඇවැසි තරමට වඩා විශාල ලෙස දක්වා ඇති සැකිලි විචල්‍යයන් අඩුම වශයෙන් එකක් හෝ  මෙම පිටුව තුල අන්තර්ගතය.
එම විචල්‍යයන් නොසලකා හැර ඇත.',
'post-expand-template-argument-category'  => 'මෙම පිටුවල, සැකිලි විචල්‍යයන් හරියාකාර දැක්වීම පැහැර හැරීම පිළිබඳ ගැටළු පවතී',

# Account creation failure
'cantcreateaccounttitle' => 'ගිණුම තැනිය නොහැක',
'cantcreateaccount-text' => "මෙම අන්තර්ජාල ලිපිනය ('''$1''') මගින් ගිණුම් තැනීම [[User:$3|$3]] විසින් වාරණය කොට ඇත.

$3 විසින් සපයා ඇති හේතුව ''$2'' වේ",

# History pages
'viewpagelogs' => 'මෙම පිටුව සඳහා ලඝු-සටහන් නරඹන්න',
'nohistory'    => 'මෙම පිටුව සඳහා සංස්කරණ ඉතිහාසයක් නොමැත.',
'revnotfound'  => 'සංශෝධනය සොයා ගත නොහැකි විය',
'currentrev'   => 'වත්මන් සංශෝධනය',
'cur'          => 'වත්මන්',
'last'         => 'අවසන්',
'historyempty' => '(හිස්)',

# Revision feed
'history-feed-title' => 'සංශෝධන ඉතිහාසය',

# Revision deletion
'rev-delundel' => 'පෙන්වන්න/සඟවන්න',

# Diffs
'history-title' => '"$1" හි සංශෝධන ඉතිහාසය',

# Search results
'viewprevnext' => '($1) ($2) ($3) බලන්න',
'powersearch'  => 'ගැඹුරින් සොයන්න',

# Preferences page
'mypreferences'  => 'මගේ අභිරුචි',
'changepassword' => 'මුරපදය වෙනස් කරන්න',
'skin-preview'   => 'පෙරදසුන',
'datetime'       => 'දිනය සහ වේලාව',
'prefs-personal' => 'පරිශීලක පැතිකඩ',
'prefs-rc'       => 'නව වෙනස්වීම්',
'prefs-misc'     => 'විවිධ',
'saveprefs'      => 'Save',
'resetprefs'     => 'යළි පිහිටුවන්න',
'files'          => 'ගොනු',

# Recent changes
'recentchanges'   => 'නව වෙනස්වීම්',
'rcshowhideminor' => 'සුළු සංස්කරණ $1',
'rcshowhideanons' => 'නිර්නාමික පරිශීලකයෝ $1',
'diff'            => 'වෙනස',
'hist'            => 'විත්ති',
'hide'            => 'සඟවන්න',
'show'            => 'පෙන්වන්න',
'newpageletter'   => 'නව',

# Recent changes linked
'recentchangeslinked' => 'සබැඳි වෙනස්වීම්',

# Upload
'upload' => 'ගොනුවක් උඩුගත කිරීම',

# Image description page
'filehist'          => 'ගොනු ඉතිහාසය',
'filehist-datetime' => 'දිනය/කාලය',
'filehist-user'     => 'පරිශීලක',

# Random page
'randompage' => 'අහඹු පිටුව',

# Miscellaneous special pages
'nbytes'      => '$1 {{PLURAL:$1|බයිටය|බයිට්}}',
'ncategories' => '$1 {{PLURAL:$1|ප්‍රවර්ගය|ප්‍රවර්ගයන්}}',
'newpages'    => 'අලුත් පිටු',
'move'        => 'ගෙනයන්න',

# Book sources
'booksources-go' => 'යන්න',

# Special:Log
'log' => 'Logs',

# Special:AllPages
'allpages'       => 'සියලු පිටු',
'alphaindexline' => '$1 සි‍ට $2',
'allarticles'    => 'සියලු පිටු',
'allpagessubmit' => 'යන්න',

# Special:ListUsers
'listusers-submit' => 'පෙන්වන්න',

# Watchlist
'mywatchlist' => 'මගේ මුර ලැයිස්තුව',
'watch'       => 'මුර කරන්න',
'unwatch'     => 'මුර නොකරන්න',

# Undelete
'undelete-search-submit' => 'සොයන්න',

# Namespace form on various pages
'namespace'      => 'නාමඅවකාශය:',
'blanknamespace' => '(ප්‍රධාන)',

# Contributions
'contributions' => 'මේ පරිශීලකයාගේ දායකත්වය',
'mycontris'     => 'මගේ දායකත්වය',

# What links here
'whatlinkshere' => 'සබැඳි පිටු',

# Block/unblock
'ipaddress'                       => 'IP යොමුව:',
'contribslink'                    => 'දායකත්ව',
'autoblocker'                     => 'ඔබගේ අන්තර්ජාල ලිපිනය "[[පරිශීලක:$1|$1]]" විසින් මෑතකදී භාවිතා කර ඇති බැවින් ඔබ ස්වයංක්‍රීය-වාරණයකට ලක් කර ඇත.
$1 ගේ වාරණයට හේතුව මෙය වේ: "$2"',
'blocklogpage'                    => 'වාරණ ලඝු සටහන',
'blocklog-fulllog'                => 'පූර්ණ වාරණ ලඝු-සටහන',
'blocklogentry'                   => '$2 $3 වෙතින් දැක්වෙන ඉකුත් වීමේ කාලයකට යටත් කොට [[$1]] වාරණයට ලක් කර ඇත',
'blocklogtext'                    => 'පරිශීලකයන් වාරණය කිරීමේ හා වාරණයන් අත්හිටුවීමේ කාර්යයන් දැක්වෙන ලඝු සටහන මෙහි දැක්වේ.
ස්වයංක්‍රීයව වාරණය කල අන්තර්ජාල ලිපිනයන් ලැයිස්තුගත කොට නොමැත.
වර්තමානයෙහි ක්‍රියාත්මක වන තහනම් හා වාරණ සඳහා [[Special:IPBlockList|අන්තර්ජාල ලිපිනයන් වාරණ ලැයිස්තුව]] බලන්න.',
'unblocklogentry'                 => '$1 හි වාරණය අත්හිටුවන ලදි',
'block-log-flags-anononly'        => 'නිර්නාමික පරිශීලකයන් පමණි',
'block-log-flags-nocreate'        => 'ගිණුම් තැනීම අක්‍රීය කොට ඇත',
'block-log-flags-noautoblock'     => 'ස්වයංක්‍රීය වාරණය අක්‍රීය කොට ඇත',
'block-log-flags-noemail'         => 'වි-තැපෑල වාරණය කොට ඇත',
'block-log-flags-angry-autoblock' => 'ප්‍රබලකල (ඉවැඩි) ස්වයංක්‍රීය වාරණය සක්‍රීය කරන ලදි',
'range_block_disabled'            => 'පරාස වාරණයන් සිදුකිරීමට පරිපාලක වරුන්ට ඇති හැකියාව අක්‍රීය කරන ලදි.',
'ipb_expiry_invalid'              => 'ඉකුත්වීමේ කාලය අවලංගුය.',
'ipb_expiry_temp'                 => 'සැඟවුනු පරිශීලක-නාම වාරණයන් ස්ථීර විය යුතුය.',
'ipb_already_blocked'             => '"$1" දැනටමත් වාරණයට ලක් කර ඇත',
'ipb_cant_unblock'                => 'දෝෂය: වාරණ අනන්‍යතාවය $1 සොයා ගත නොහැකි විය.
මෙය දැනටමත් වාරණ අත්හිටුවීමකට භාජනය වී ඇතිවා විය හැක.',
'ipb_blocked_as_range'            => 'දෝෂය: $1 අන්තර්ජාල ලිපිනය සෘජුව වාරණය කොට නොමැති අතර එහි වාරණ‍ය අත්හිටුවිය නොහැක.
එනමුදු, එය, $2 පරාසයෙහි කොටසක් ලෙස වාරණයට ලක් කොට ඇති අතර, එහි වාරණය අත්හිටුවිය හැක.',
'ip_range_invalid'                => 'අවලංගු අන්තර්ජාල ලිපින පරාසයකි.',
'blockme'                         => 'මා වාරණය කරන්න',
'proxyblocker'                    => 'ප්‍රතියුක්ත (ප්‍රොක්සි) වාරණකරු',
'proxyblocker-disabled'           => 'මෙම කෘත්‍යය අක්‍රීය කොට ඇත.',
'proxyblockreason'                => 'ඔබගේ අන්තර්ජාල ලිපිනය විවෘත ප්‍රතියුක්තයක් (ප්‍රොක්සි) බැවින් එය වාරණය කොට ඇත.
ඔබගේ අන්තර්ජාල සේවා ප්‍රතිපාදකයා හෝ තාක්ෂණික අනුග්‍රාහකයා හෝ අමතා මෙම බරපතළ ආරක්ෂණ ගැටළුව ඔවුනට නිරාවරණය කරන්න.',
'proxyblocksuccess'               => 'සිදුකලා.',

# Namespace 8 related
'allmessagesname' => 'නම',

# Tooltip help for the actions
'tooltip-pt-preferences'   => 'මගේ අභිරුචි',
'tooltip-ca-edit'          => 'ඔබ‍ට මෙම පිටුව සංස්කරණය කල හැක. කරුණාකර සුරැකීමට පෙර පෙරදසුන බොත්තම භාවිතා කරන්න.',
'tooltip-ca-delete'        => 'මේ පිටුව මකන්න',
'tooltip-search'           => 'සොයන්න {{SITENAME}}',
'tooltip-n-mainpage'       => 'මුල් පිටුව‍ට යන්න',
'tooltip-t-specialpages'   => 'සියලු විශේෂ පිටු ලැයිස්තුව',
'tooltip-ca-nstab-project' => 'ව්‍යාපෘති පිටුව පෙන්වන්න',
'tooltip-save'             => 'ඔබගේ වෙනස් කිරීම් සුරකින්න',

# Special:NewImages
'ilsubmit' => 'සොයන්න',

# Metadata
'metadata' => 'පාරදත්ත',

# EXIF tags
'exif-imagewidth'   => 'පළල',
'exif-imagelength'  => 'උස',
'exif-artist'       => 'කතෘ',
'exif-gpslatitude'  => 'අක්ෂාංශය',
'exif-gpslongitude' => 'දේශාංශය',

'exif-subjectdistance-value' => 'මීටර $1',

'exif-focalplaneresolutionunit-2' => 'අඟල්',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'සියල්ල',

# action=purge
'confirm_purge_button' => 'හරි',

# Multipage image navigation
'imgmultipageprev' => '← පෙර පිටුව',
'imgmultipagenext' => 'ඊළඟ පිටුව →',
'imgmultigo'       => 'යන්න!',

# Table pager
'table_pager_next'         => 'ඊළඟ පිටුව',
'table_pager_prev'         => 'පෙර පිටුව',
'table_pager_first'        => 'පළමු පිටුව',
'table_pager_last'         => 'අවසාන පිටුව',
'table_pager_limit_submit' => 'යන්න',

# Special:Version
'version-specialpages' => 'විශේෂ පිටු',
'version-other'        => 'වෙනත්',

# Special:SpecialPages
'specialpages'             => 'විශේෂ පිටු',
'specialpages-group-pages' => 'පිටු ලැයිස්තුව',

);
