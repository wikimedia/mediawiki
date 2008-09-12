<?php
/** Sinhala (සිංහල)
 *
 * @ingroup Language
 * @file
 *
 * @author Asiri wiki
 * @author Chandana
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

'about'          => 'ගැන',
'article'        => 'අන්තර්ගත පිටුව',
'cancel'         => 'අවලංගු කරන්න',
'qbfind'         => 'සොයන්න',
'qbbrowse'       => 'පිරික්සන්න',
'qbedit'         => 'සකසන්න',
'qbpageoptions'  => 'මේ පිටුව',
'qbmyoptions'    => 'මගේ පිටු',
'qbspecialpages' => 'විශේෂ පිටු',
'moredotdotdot'  => 'තවත්...',
'mypage'         => 'මගේ පිටුව',
'mytalk'         => 'මගේ සාකච්ඡා',
'navigation'     => 'හසුරවන්න',
'and'            => 'සහ',

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

'ok'                  => 'හරි',
'newmessagesdifflink' => 'අවසාන වෙනස',
'toc'                 => 'පටුන',
'showtoc'             => 'පෙන්වන්න',
'hidetoc'             => 'සඟවන්න',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ලිපිය',
'nstab-user'      => 'පරිශීලක පිටුව',
'nstab-special'   => 'විශේෂ',
'nstab-project'   => 'ව්‍යාපෘති පිටුව',
'nstab-image'     => 'ගොනුව',
'nstab-mediawiki' => 'Message',
'nstab-help'      => 'උදව් පිටුව',
'nstab-category'  => 'ප්‍රවර්ගය',

# General errors
'badtitle' => 'නුසුදුසු මාතෘකාවක්',

# Login and logout pages
'welcomecreation'            => "== ආයුබෝවන්, $1! ==

<!--Your account has been created. Don't forget to change your {{SITENAME}} preferences.-->
ඔබ‍ගේ ගිණුම තැනී ඇත.ඔබ‍ගේ {{SITENAME}} අභිරුචි වෙනස් කර ගන්න අමතක කරන්න එපා.",
'yourname'                   => 'පරිශීලක නාමය:',
'yourpassword'               => 'මුරපදය:',
'yourpasswordagain'          => 'මුරපදය නැවත ලියන්න:',
'yourdomainname'             => 'ඔබගේ වසම:',
'userlogin'                  => 'Log in / ගිණුමක් තනන්න',
'logout'                     => 'පිටවන්න',
'nologinlink'                => 'ගිණුමක් තනන්න',
'createaccount'              => 'ගිණුමක් තනන්න',
'gotaccount'                 => 'දැනටමත් ගිණුමක් තිබේද?$1.',
'createaccountmail'          => 'විද්‍යුත් තැපෑල මගින්',
'youremail'                  => 'විද්‍යුත් තැපෑල:',
'username'                   => 'පරිශීලක නාමය:',
'yourrealname'               => 'සැබෑ නාමය:',
'yourlanguage'               => 'භාෂාව:',
'yournick'                   => 'විකල්ප නාමය:',
'email'                      => 'විද්‍යුත් තැපෑල',
'prefs-help-email-required'  => 'විද්‍යුත් තැපෑල් ලිපිනය අවශ්‍යයි.',
'mailmypassword'             => 'මුරපදය විද්‍යුත් තැපෑල‍ට යවන්න',
'acct_creation_throttle_hit' => 'ඔබ දැන‍ටමත් ගිණුම $1 තනා ඇත.ඔබට තවත් ගිණුම් තැනිය නොහැක.',
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
'summary'           => 'සාරාංශය',
'minoredit'         => 'මෙය සුළු සංස්කරණයකි',
'savearticle'       => 'පිටුව සුරකින්න',
'preview'           => 'පෙරදසුන',
'showpreview'       => 'පෙරදසුන පෙන්වන්න',
'showdiff'          => 'වෙනස්වීම් පෙන්වන්න',
'yourtext'          => 'ඔබගේ පෙළ',
'copyrightwarning'  => "Please note that all contributions to {{SITENAME}} are considered to be released under the $2 (see $1 for details). If you don't want your writing to be edited mercilessly and redistributed at will, then don't submit it here.<br />
එසේ ම මෙය ඔබ විසින් ම ලියූ බවට හෝ පොදු විෂයපථයකින්, ඊ‍ට ස‍මාන නිදහස් මූලාශ්‍රයකින් උපුටා ගත් බව‍ට හෝ අපහ‍‍ට සහතික විය යුතු ය. (තොරතුරු සඳහා $1 බලන්න).
<strong>හිමිකම් ඇවුරුණු දේ අනවසරයෙන් ප්‍රකාෂ කිරිමෙන් වලකින්න!</strong>",
'copyrightwarning2' => "Please note that all contributions to {{SITENAME}} may be edited, altered, or removed by other contributors. If you don't want your writing to be edited mercilessly, then don't submit it here.<br />
එසේ ම මෙය ඔබ විසින් ම ලියූ බවට හෝ පොදු විෂයපථයකින්, ඊ‍ට ස‍මාන නිදහස් මූලාශ්‍රයකින් උපුටා ගත් බව‍ට හෝ අපහ‍‍ට සහතික විය යුතු ය. (තොරතුරු සඳහා $1 බලන්න).
<strong>හිමිකම් ඇවුරුණු දේ අනවසරයෙන් ප්‍රකාෂ කිරිමෙන් වලකින්න!</strong>",

# Revision deletion
'rev-delundel' => 'පෙන්වන්න/සඟවන්න',

# Search results
'viewprevnext' => '($1) ($2) ($3) බලන්න',
'powersearch'  => 'ගැඹුරින් සොයන්න',

# Preferences page
'mypreferences'  => 'මගේ අභිරුචි',
'changepassword' => 'මුරපදය වෙනස් කරන්න',
'skin-preview'   => 'පෙරදසුන',
'datetime'       => 'දිනය සහ වේලාව',
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
'hide'            => 'සඟවන්න',
'show'            => 'පෙන්වන්න',

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
'newpages' => 'අලුත් පිටු',
'move'     => 'ගෙනයන්න',

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
'namespace' => 'නාමඅවකාශය:',

# Contributions
'contributions' => 'මේ පරිශීලකයාගේ දායකත්වය',
'mycontris'     => 'මගේ දායකත්වය',

# What links here
'whatlinkshere' => 'සබැඳි පිටු',

# Block/unblock
'ipaddress' => 'IP යොමුව:',

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
'specialpages' => 'විශේෂ පිටු',

);
