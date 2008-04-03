<?php
/** Kannada (ಕನ್ನಡ)
 *
 * @addtogroup Language
 *
 * @author Mana
 * @author Shushruth
 * @author HPN
 * @author Nike
 * @author Hari Prasad Nadig <hpnadig@gmail.com> http://en.wikipedia.org/wiki/User:Hpnadig
 * @author Ashwath Mattur <ashwatham@gmail.com> http://en.wikipedia.org/wiki/User:Ashwatham
 * @author Siebrand
 * @author לערי ריינהארט
 * @author SPQRobin
 */

$namespaceNames = array(
	NS_MEDIA            => 'ಮೀಡಿಯ',
	NS_SPECIAL          => 'ವಿಶೇಷ',
	NS_MAIN             => '',
	NS_TALK             => 'ಚರ್ಚೆಪುಟ',
	NS_USER             => 'ಸದಸ್ಯ',
	NS_USER_TALK        => 'ಸದಸ್ಯರ_ಚರ್ಚೆಪುಟ',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_ಚರ್ಚೆ',
	NS_IMAGE            => 'ಚಿತ್ರ',
	NS_IMAGE_TALK       => 'ಚಿತ್ರ_ಚರ್ಚೆಪುಟ',
	NS_MEDIAWIKI        => 'ಮೀಡಿಯವಿಕಿ',
	NS_MEDIAWIKI_TALK   => 'ಮೀಡೀಯವಿಕಿ_ಚರ್ಚೆ',
	NS_TEMPLATE         => 'ಟೆಂಪ್ಲೇಟು',
	NS_TEMPLATE_TALK    => 'ಟೆಂಪ್ಲೇಟು_ಚರ್ಚೆ',
	NS_HELP             => 'ಸಹಾಯ',
	NS_HELP_TALK        => 'ಸಹಾಯ_ಚರ್ಚೆ',
	NS_CATEGORY         => 'ವರ್ಗ',
	NS_CATEGORY_TALK    => 'ವರ್ಗ_ಚರ್ಚೆ'
);

$digitTransformTable = array(
	'0' => '೦', # &#x0ce6;
	'1' => '೧', # &#x0ce7;
	'2' => '೨', # &#x0ce8;
	'3' => '೩', # &#x0ce9;
	'4' => '೪', # &#x0cea;
	'5' => '೫', # &#x0ceb;
	'6' => '೬', # &#x0cec;
	'7' => '೭', # &#x0ced;
	'8' => '೮', # &#x0cee;
	'9' => '೯', # &#x0cef;
);

$messages = array(
# User preference toggles
'tog-underline'            => 'ಲಿಂಕುಗಳ ಕೆಳಗೆ ಗೆರೆ ತೋರಿಸಿ',
'tog-hideminor'            => 'ಚಿಕ್ಕಪುಟ್ಟ ಬದಲಾವಣೆಗಳನ್ನು ಅಡಗಿಸಿ',
'tog-extendwatchlist'      => 'ಸಂಬಂಧಿತ ಎಲ್ಲಾ ಬದಲಾವಣೆಗಳನ್ನು ತೋರುವಂತೆ ಪಟ್ಟಿಯನ್ನು ವಿಸ್ತರಿಸಿ',
'tog-rememberpassword'     => 'ಈ ಗಣಕಯಂತ್ರದಲ್ಲಿ ನನ್ನ ಲಾಗಿನ್ ನೆನಪಿನಲ್ಲಿಟ್ಟುಕೊ',
'tog-watchcreations'       => 'ನಾನು ಪ್ರಾರಂಭಿಸುವ ಲೇಖನಗಳನ್ನು ನನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗೆ ಸೇರಿಸು',
'tog-watchdefault'         => 'ನಾನು ಸಂಪಾದಿಸುವ ಪುಟಗಳನ್ನು ವೀಕ್ಷಣಾಪಟ್ಟಿಗೆ ಸೇರಿಸು',
'tog-watchmoves'           => 'ನಾನು ಸ್ಥಳಾಂತರಿಸುವ ಪುಟಗಳನ್ನು ನನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿಗೆ ಸೇರಿಸು',
'tog-watchdeletion'        => 'ನಾನು ಅಳಿಸುವ ಪುಟಗಳನ್ನು ನನ್ನ ವೀಕ್ಷಣಾ ಪಟ್ಟಿಗೆ ಸೇರಿಸು',
'tog-previewonfirst'       => 'ಮೊದಲ ಬದಲಾವಣೆಯ ನಂತರ ಮುನ್ನೋಟವನ್ನು ತೋರಿಸು',
'tog-enotifwatchlistpages' => 'ನನ್ನ ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಲ್ಲಿರುವ ಯಾವುದಾದರೂ ಪುಟವು ಬದಲಾದಾಗ ನನಗೆ ಇ-ಅಂಚೆ ಕಳುಹಿಸು.',
'tog-enotifusertalkpages'  => 'ನನ್ನ ಚರ್ಚೆ ಪುಟ ಬದಲಾದರೆ ನನಗೆ ಇ-ಅಂಚೆ ಕಳುಹಿಸು',
'tog-enotifminoredits'     => 'ಚಿಕ್ಕ-ಪುಟ್ಟ ಬದಲಾವಣೆಗಳಾದಾಗಲೂ ಇ-ಅಂಚೆ ಕಳುಹಿಸು',
'tog-shownumberswatching'  => 'ಪುಟವನ್ನು ವೀಕ್ಷಿಸುತ್ತಿರುವ ಸದಸ್ಯರ ಸಂಖ್ಯೆಯನ್ನು ತೋರಿಸು',
'tog-watchlisthideown'     => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಲ್ಲಿ ನನ್ನ ಸಂಪಾದನೆಗಳನ್ನು ತೋರಿಸಬೇಡ',
'tog-watchlisthidebots'    => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ ಬಾಟ್ ಸಂಪಾದನೆಗಳನ್ನು ಅಡಗಿಸು',
'tog-watchlisthideminor'   => 'ಚಿಕ್ಕ ಬದಲಾವಣೆಗಳನ್ನು ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಿಂದ ಅಡಗಿಸು',
'tog-ccmeonemails'         => 'ಇತರರಿಗೆ ನಾನು ಕಳುಹಿಸುವ ಇ-ಅಂಚೆಯ ಪ್ರತಿಯನ್ನು ನನಗೂ ಕಳುಹಿಸು',
'tog-diffonly'             => 'ವ್ಯತ್ಯಾಸಗಳ ಕೆಳಗಿರುವ ಪುಟದ ವಿವರಗಳನ್ನು ತೋರಿಸಬೇಡ',
'tog-showhiddencats'       => 'ಅಡಗಿಸಲ್ಪಟ್ಟ ವರ್ಗಗಳನ್ನು ತೋರಿಸು',

'underline-always' => 'ಯಾವಾಗಲೂ',
'underline-never'  => 'ಎಂದಿಗೂ ಇಲ್ಲ',

'skinpreview' => '(ಮುನ್ನೋಟ)',

# Dates
'sunday'        => 'ಭಾನುವಾರ',
'monday'        => 'ಸೋಮವಾರ',
'tuesday'       => 'ಮಂಗಳವಾರ',
'wednesday'     => 'ಬುಧವಾರ',
'thursday'      => 'ಗುರುವಾರ',
'friday'        => 'ಶುಕ್ರವಾರ',
'saturday'      => 'ಶನಿವಾರ',
'sun'           => 'ಭಾನು',
'mon'           => 'ಸೋಮ',
'tue'           => 'ಮಂಗಳ',
'wed'           => 'ಬುಧ',
'thu'           => 'ಗುರು',
'fri'           => 'ಶುಕ್ರ',
'sat'           => 'ಶನಿ',
'january'       => 'ಜನವರಿ',
'february'      => 'ಫೆಬ್ರುವರಿ',
'march'         => 'ಮಾರ್ಚ್',
'april'         => 'ಏಪ್ರಿಲ್',
'may_long'      => 'ಮೇ',
'june'          => 'ಜೂನ್',
'july'          => 'ಜುಲೈ',
'august'        => 'ಆಗಸ್ಟ್',
'september'     => 'ಸೆಪ್ಟೆಂಬರ್',
'october'       => 'ಅಕ್ಟೋಬರ್',
'november'      => 'ನವೆಂಬರ್',
'december'      => 'ಡಿಸೆಂಬರ್',
'january-gen'   => 'ಜನವರಿ',
'february-gen'  => 'ಫ್ರೆಬ್ರುವರಿ',
'march-gen'     => 'ಮಾರ್ಚ್',
'april-gen'     => 'ಏಪ್ರಿಲ್',
'may-gen'       => 'ಮೇ',
'june-gen'      => 'ಜೂನ್',
'july-gen'      => 'ಜುಲೈ',
'august-gen'    => 'ಆಗಸ್ಟ್',
'september-gen' => 'ಸೆಪ್ಟಂಬರ್',
'october-gen'   => 'ಅಕ್ಟೋಬರ್',
'november-gen'  => 'ನವೆಂಬರ್',
'december-gen'  => 'ಡಿಸೆಂಬರ್',
'jan'           => 'ಜನವರಿ',
'feb'           => 'ಫೆಬ್ರುವರಿ',
'mar'           => 'ಮಾರ್ಚ್',
'apr'           => 'ಏಪ್ರಿಲ್',
'may'           => 'ಮೇ',
'jun'           => 'ಜೂನ್',
'jul'           => 'ಜುಲೈ',
'aug'           => 'ಆಗಸ್ಟ್',
'sep'           => 'ಸೆಪ್ಟಂಬರ್',
'oct'           => 'ಅಕ್ಟೋಬರ್',
'nov'           => 'ನವೆಂಬರ್',
'dec'           => 'ಡಿಸೆಂಬರ್',

# Categories related messages
'categories'                     => '{{PLURAL:$1|ವರ್ಗ|ವರ್ಗಗಳು}}',
'categoriespagetext'             => 'ವಿಕಿಯಲ್ಲಿ ಈ ಕೆಳಗಿನ ವರ್ಗಗಳಿವೆ',
'pagecategories'                 => '{{PLURAL:$1|ವರ್ಗ|ವರ್ಗಗಳು}}',
'category_header'                => '"$1" ವರ್ಗದಲ್ಲಿರುವ ಲೇಖನಗಳು',
'subcategories'                  => 'ಉಪವರ್ಗಗಳು',
'category-media-header'          => '"$1" ವರ್ಗದಲ್ಲಿರುವ ಚಿತ್ರ/ಶಬ್ದ ಫೈಲುಗಳು',
'category-empty'                 => "''ಈ ವರ್ಗದಲ್ಲಿ ಸದ್ಯದಲ್ಲಿ ಯಾವುದೇ ಪುಟಗಳಾಗಲಿ ಅಥವ ಚಿತ್ರಗಳಾಗಲಿ ಇಲ್ಲ.''",
'hidden-categories'              => '{{PLURAL:$1|ಅಡಗಿಸಲ್ಪಟ್ಟ ವರ್ಗ|ಅಡಗಿಸಲ್ಪಟ್ಟ ವರ್ಗಗಳು}}',
'hidden-category-category'       => 'ಅಡಗಿಸಲ್ಪಟ್ಟಿರುವ ವರ್ಗಗಳು', # Name of the category where hidden categories will be listed
'category-subcat-count-limited'  => 'ಈ ವರ್ಗದಲ್ಲಿ ಕೆಳಗೆ ತೋರಿಸಿರುವ {{PLURAL:$1|ಉಪವರ್ಗ|$1 ಉಪವರ್ಗಗಳು}} ಇವೆ.',
'category-article-count-limited' => 'ಪ್ರಸಕ್ತ ವರ್ಗದಲ್ಲಿ ಈ ಕೆಳಗಿನ {{PLURAL:$1|ಪುಟ ಇದೆ|$1 ಪುಟಗಳು ಇವೆ}}.',
'category-file-count-limited'    => 'ಪ್ರಸಕ್ತ ವರ್ಗದಲ್ಲಿ ಈ ಕೆಳಗಿನ {{PLURAL:$1|ಫೈಲು ಇದೆ|$1 ಫೈಲುಗಳು ಇವೆ}}.',
'listingcontinuesabbrev'         => 'ಮುಂದು.',

'mainpagetext'      => 'ವಿಕಿ ತಂತ್ರಾಂಶವನ್ನು ಯಶಸ್ವಿಯಾಗಿ ಅನುಸ್ಥಾಪಿಸಲಾಯಿತು.',
'mainpagedocfooter' => 'ವಿಕಿ ತಂತ್ರಾಂಶವನ್ನು ಬಳಸುವ ಬಗ್ಗೆ ಮಾಹಿತಿಗೆ [http://meta.wikimedia.org/wiki/Help:Contents ಬಳಕೆದಾರರಿಗೆ ನಿರ್ದೇಶನ ಪುಟ] ನೋಡಿ.

== ಪ್ರಾರಂಭಿಸುವುದು ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ ಮೀಡಿಯವಿಕಿ FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]',

'about'          => 'ನಮ್ಮ ಬಗ್ಗೆ',
'article'        => 'ಲೇಖನ ಪುಟ',
'newwindow'      => '(ಹೊಸ ಕಿಟಕಿಯನ್ನು ತೆರೆಯುತ್ತದೆ)',
'cancel'         => 'ವಜಾ ಮಾಡಿ',
'qbfind'         => 'ಹುಡುಕು',
'qbedit'         => 'ಸಂಪಾದಿಸು',
'qbpageoptions'  => 'ಈ ಪುಟ',
'qbmyoptions'    => 'ನನ್ನ ಪುಟಗಳು',
'qbspecialpages' => 'ವಿಶೇಷ ಪುಟಗಳು',
'moredotdotdot'  => 'ಇನ್ನಷ್ಟು...',
'mypage'         => 'ನನ್ನ ಪುಟ',
'mytalk'         => 'ನನ್ನ ಚರ್ಚೆ',
'anontalk'       => 'ಈ ಐ.ಪಿ ಗೆ ಮಾತನಾಡಿ',
'navigation'     => 'ಸಂಚರಣೆ',
'and'            => 'ಮತ್ತು',

'errorpagetitle'    => 'ದೋಷ',
'returnto'          => '$1 ಗೆ ಹಿಂತಿರುಗಿ.',
'tagline'           => '{{SITENAME}} ಇಂದ',
'help'              => 'ಸಹಾಯ',
'search'            => 'ಹುಡುಕು',
'searchbutton'      => 'ಹುಡುಕು',
'go'                => 'ಹೋಗು',
'searcharticle'     => 'ಹೋಗು',
'history'           => 'ಪುಟದ ಚರಿತ್ರೆ',
'history_short'     => 'ಇತಿಹಾಸ',
'updatedmarker'     => 'ನನ್ನ ಕೊನೆಯ ವೀಕ್ಷಣೆಯ ನಂತರ ಬದಲಾಗಿರುವವು',
'info_short'        => 'ಮಾಹಿತಿ',
'printableversion'  => 'ಪ್ರಿಂಟ್ ಆವೃತ್ತಿ',
'permalink'         => 'ಸ್ಥಿರ ಸಂಪರ್ಕ',
'edit'              => 'ಸಂಪಾದಿಸಿ (edit this page)',
'create'            => 'ಸೃಷ್ಟಿಸು',
'editthispage'      => 'ಈ ಪುಟವನ್ನು ಬದಲಾಯಿಸಿ',
'create-this-page'  => 'ಈ ಪುಟವನ್ನು ಸೃಷ್ಟಿಸು',
'delete'            => 'ಅಳಿಸಿ',
'deletethispage'    => 'ಈ ಪುಟವನ್ನು ಅಳಿಸಿ',
'protect'           => 'ಸಂರಕ್ಷಿಸು',
'protect_change'    => 'ಸಂರಕ್ಷಣೆಯನ್ನು ಬದಲಾಯಿಸಿ',
'protectthispage'   => 'ಈ ಪುಟವನ್ನು ಸಂರಕ್ಷಿಸಿ',
'unprotect'         => 'ಸಂರಕ್ಷಣೆ ತೆಗೆ',
'unprotectthispage' => 'ಈ ಪುಟದ ಸಂರಕ್ಷಣೆಯನ್ನು ತಗೆಯಿರಿ',
'newpage'           => 'ಹೊಸ ಪುಟ',
'talkpage'          => 'ಈ ಪುಟದ ಬಗ್ಗೆ ಚರ್ಚೆ ಮಾಡಿ',
'talkpagelinktext'  => 'ಚರ್ಚೆ',
'specialpage'       => 'ವಿಶೇಷ ಪುಟ',
'personaltools'     => 'ವೈಯಕ್ತಿಕ ಉಪಕರಣಗಳು',
'postcomment'       => 'ನಿಮ್ಮ ಮಾತನ್ನು ಲಗತ್ತಿಸಿ',
'articlepage'       => 'ಲೇಖನ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'talk'              => 'ಚರ್ಚೆ',
'views'             => 'ನೋಟಗಳು',
'toolbox'           => 'ಉಪಕರಣ',
'userpage'          => 'ಸದಸ್ಯರ ಪುಟ ತೋರು',
'imagepage'         => 'ಚಿತ್ರದ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'templatepage'      => 'ಟೆಂಪ್ಲೇಟು ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'viewhelppage'      => 'ಸಹಾಯ ಪುಟ ತೋರು',
'categorypage'      => 'ವರ್ಗ ಪುಟ ತೋರು',
'viewtalkpage'      => 'ಚರ್ಚೆಯನ್ನು ವೀಕ್ಷಿಸಿ',
'otherlanguages'    => 'ಇತರ ಭಾಷೆಗಳು',
'redirectedfrom'    => '($1 ಇಂದ ಪುನರ್ನಿರ್ದೇಶಿತ)',
'redirectpagesub'   => 'ಪುನರ್ನಿರ್ದೇಶನ ಪುಟ',
'lastmodifiedat'    => 'ಈ ಪುಟವನ್ನು ಕೊನೆಯಾಗಿ $2, $1 ರಂದು ಬದಲಾಯಿಸಲಾಗಿತ್ತು.', # $1 date, $2 time
'viewcount'         => 'ಈ ಪುಟವನ್ನು {{PLURAL:$1|೧ ಬಾರಿ|$1 ಬಾರಿ}} ವೀಕ್ಷಿಸಲಾಗಿದೆ.',
'protectedpage'     => 'ಸಂರಕ್ಷಿತ ಪುಟ',
'jumpto'            => 'ಇಲ್ಲಿಗೆ ಹೋಗು:',
'jumptonavigation'  => 'ಸಂಚರಣೆ',
'jumptosearch'      => 'ಹುಡುಕು',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'ಕನ್ನಡ {{SITENAME}} ಬಗ್ಗೆ',
'aboutpage'         => 'Project:ನಮ್ಮ ಬಗ್ಗೆ',
'bugreports'        => 'ದೋಷ ವರದಿಗಳು',
'bugreportspage'    => 'Project:ದೋಷ ವರದಿಗಳು',
'copyright'         => 'ಇದು ಈ ಕಾಪಿರೈಟ್‌ನಲ್ಲಿ ಲಭ್ಯವಿದೆ $1.',
'copyrightpagename' => '{{SITENAME}} ಕಾಪಿರೈಟ್',
'copyrightpage'     => '{{ns:project}}:ಕೃತಿಸ್ವಾಮ್ಯತೆಗಳು',
'currentevents'     => 'ಪ್ರಚಲಿತ',
'currentevents-url' => 'Project:ಪ್ರಚಲಿತ',
'disclaimers'       => 'ಅಬಾಧ್ಯತೆಗಳು',
'disclaimerpage'    => 'Project:ಸಾಮಾನ್ಯ ಅಬಾಧ್ಯತೆಗಳು',
'edithelp'          => 'ಸಂಪಾದನೆಗೆ ಸಹಾಯ',
'edithelppage'      => 'Help:ಸಂಪಾದನೆ',
'helppage'          => 'Help:ಪರಿವಿಡಿ',
'mainpage'          => 'ಮುಖ್ಯ ಪುಟ',
'portal'            => 'ಸಮುದಾಯ ಪುಟ',
'portal-url'        => 'Project:ಸಮುದಾಯ ಪುಟ',
'privacy'           => 'ಖಾಸಗಿ ಮಾಹಿತಿಯ ಬಗ್ಗೆ ನಿಲುವು',
'privacypage'       => 'Project:ಖಾಸಗಿಮಾಹಿತಿ ನಿಯಮ',
'sitesupport'       => 'ದೇಣಿಗೆ',
'sitesupport-url'   => 'Project:ದೇಣಿಗೆ',

'badaccess-group0' => 'ನೀವು ಕೋರಿರುವ ಕ್ರಿಯೆಯನ್ನು ನಿರ್ವಹಿಲು ನಿಮಗೆ ಅನುಮತಿ ಇಲ್ಲ.',

'versionrequired'     => 'ಮೀಡಿಯವಿಕಿಯ $1 ನೇ ಅವೃತ್ತಿ ಬೇಕಾಗುತ್ತದೆ',
'versionrequiredtext' => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಲು ಮೀಡಿಯವಿಕಿಯ $1 ನೇ ಆವೃತ್ತಿ ಬೇಕಾಗಿದೆ. [[Special:Version|ಆವೃತ್ತಿ]] ಪುಟವನ್ನು ನೋಡಿ.',

'ok'                      => 'ಸರಿ',
'retrievedfrom'           => '"$1" ಇಂದ ಪಡೆಯಲ್ಪಟ್ಟಿದೆ',
'youhavenewmessages'      => 'ನಿಮಗೆ $1 ಇವೆ ($2).',
'newmessageslink'         => 'ಹೊಸ ಸಂದೇಶಗಳು',
'newmessagesdifflink'     => 'ಕೊನೆಯ ಬದಲಾವಣೆ',
'youhavenewmessagesmulti' => '$1 ಅಲ್ಲಿ ನಿಮಗೆ ಹೊಸ ಸಂದೇಶಗಳಿವೆ',
'editsection'             => 'ಬದಲಾಯಿಸಿ',
'editold'                 => 'ಬದಲಾಯಿಸಿ',
'editsectionhint'         => '$1 ವಿಭಾಗ ಸಂಪಾದಿಸಿ',
'toc'                     => 'ಪರಿವಿಡಿ',
'showtoc'                 => 'ತೋರಿಸು',
'hidetoc'                 => 'ಅಡಗಿಸು',
'restorelink'             => '{{PLURAL:$1|೧ ಅಳಿಸಲ್ಪಟ್ಟ ಸಂಪಾದನೆ|$1 ಅಳಿಸಲ್ಪಟ್ಟ ಸಂಪಾದನೆಗಳು}}',
'feedlinks'               => 'ಫೀಡ್:',
'site-rss-feed'           => '$1 RSS ಫೀಡು',
'site-atom-feed'          => '$1 Atom ಫೀಡು',
'page-rss-feed'           => '"$1" RSS ಫೀಡು',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ಲೇಖನ',
'nstab-user'      => 'ಸದಸ್ಯರ ಪುಟ',
'nstab-special'   => 'ವಿಶೇಷ',
'nstab-project'   => 'ಬಗ್ಗೆ',
'nstab-image'     => 'ಚಿತ್ರ',
'nstab-mediawiki' => 'ಸಂದೇಶ',
'nstab-template'  => 'ಟೆಂಪ್ಲೇಟು',
'nstab-help'      => 'ಸಹಾಯ',
'nstab-category'  => 'ವರ್ಗ',

# Main script and global functions
'nosuchspecialpage' => 'ಆ ಹೆಸರಿನ ವಿಶೇಷ ಪುಟ ಇಲ್ಲ',
'nospecialpagetext' => "<big>'''ನೀವು ಅಸ್ಥಿತ್ವದಲ್ಲಿರದ ವಿಶೇಷ ಪುಟವನ್ನು ಕೋರಿರುವಿರಿ.'''</big>

ಅಸ್ಥಿತ್ವದಲ್ಲಿರುವ ವಿಶೇಷ ಪುಟಗಳ ಪಟ್ಟಿ [[Special:Specialpages]] ಅಲ್ಲಿದೆ.",

# General errors
'error'               => 'ದೋಷ',
'databaseerror'       => 'ಡೇಟಬೇಸ್ ದೋಷ',
'noconnect'           => 'ಕ್ಷಮಿಸಿ! ಸದ್ಯಕ್ಕೆ ವಿಕಿಯು ತಾಂತ್ರಿಕ ತೊಂದರೆಗಳನ್ನು ಅನುಭವಿಸುತ್ತಿದೆ ಮತ್ತು ಡೇಟಾಬೇಸ್ ಸರ್ವರ್ ಅನ್ನು ಸಂಪರ್ಕಿಸಲು ಸಾಧ್ಯವಾಗುತ್ತಿಲ್ಲ. <br />
$1',
'laggedslavemode'     => 'ಎಚ್ಚರ: ಪುಟದಲ್ಲಿ ಇತ್ತೀಚಿನ ಬದಲಾವಣೆಗಳು ಕಾಣದಿರಬಹುದು.',
'internalerror'       => 'ಆಂತರಿಕ ದೋಷ',
'internalerror_info'  => 'ಆಂತರಿಕ ದೋಷ: $1',
'filecopyerror'       => '"$1" ಫೈಲ್ ಅನ್ನು "$2" ಗೆ ನಕಲಿಸಲಾಗಲಿಲ್ಲ.',
'filerenameerror'     => '"$1" ಫೈಲನ್ನು "$2" ಎಂದು ಮರುನಾಮಕರಣ ಮಾಡಲು ಆಗಲಿಲ್ಲ.',
'filedeleteerror'     => '"$1" ಫೈಲ್ ಅನ್ನು ಅಳಿಸಲಾಗಲಿಲ್ಲ.',
'filenotfound'        => '"$1" ಫೈಲನ್ನು ಹುಡುಕಲಾಗಲಿಲ್ಲ.',
'formerror'           => 'ದೋಷ: ಅರ್ಜಿ ಕಳುಹಿಸಲಾಗಲಿಲ್ಲ',
'badarticleerror'     => 'ಈ ಪುಟದ ಮೇಲೆ ನೀವು ಪ್ರಯತ್ನಿಸಿದ ಕಾರ್ಯವನ್ನು ನಡೆಸಲಾಗದು.',
'cannotdelete'        => 'ಈ ಪುಟ ಅಥವಾ ಚಿತ್ರವನ್ನು ಅಳಿಸಲಾಗಲಿಲ್ಲ. (ಬೇರೊಬ್ಬ ಸದಸ್ಯರಿಂದ ಆಗಲೇ ಅಳಿಸಲ್ಪಟ್ಟಿರಬಹುದು.)',
'badtitle'            => 'ಸರಿಯಿಲ್ಲದ ಹೆಸರು',
'badtitletext'        => 'ನೀವು ಕೋರಿದ ಪುಟದ ಶೀರ್ಷಿಕೆ ಸಿಂಧುವಲ್ಲದ್ದು ಅಥವ ಖಾಲಿ ಅಥವ ಸರಿಯಾದ ಕೊಂಡಿಯಲ್ಲದ ಅಂತರ-ಭಾಷೆ/ಅಂತರ-ವಿಕಿ ಸಂಪರ್ಕವಾಗಿದೆ. 
ಅದರಲ್ಲಿ ಒಂದು ಅಥವ ಹೆಚ್ಚು ಶೀರ್ಷಿಕೆಯಲ್ಲಿ ಬಳಸಲು ನಿಷಿದ್ಧವಾಗಿರುವ ಅಕ್ಷರಗಳು ಇರಬಹುದು.',
'viewsource'          => 'ಆಕರ ವೀಕ್ಷಿಸು',
'viewsourcefor'       => '$1 ಪುಟಕ್ಕೆ',
'protectedpagetext'   => 'ಈ ಪುಟವನ್ನು ಸಂಪಾದನೆ ಮಾಡಲಾಗದಂತೆ ಸಂರಕ್ಷಿಸಲಾಗಿದೆ.',
'viewsourcetext'      => 'ಈ ಪುಟದ ಮೂಲವನ್ನು ನೀವು ವೀಕ್ಷಿಸಬಹುದು ಮತ್ತು ನಕಲು ಮಾಡಬಹುದು:',
'ns-specialprotected' => 'ವಿಶೇಷ ಪುಟಗಳನ್ನು ಸಂಪಾದಿಸಲು ಆಗುವುದಿಲ್ಲ.',
'titleprotected'      => "ಈ ಹೆಸರಿನ ಪುಟವನ್ನು ಸೃಷ್ಟಿಸಲಾಗದಂತೆ [[User:$1|$1]] ಅವರು ಸಂರಕ್ಷಿಸಿದ್ದಾರೆ.
ಸಂರಕ್ಷಣೆಗೆ ನೀಡಿರುವ ಕಾರಣ: ''$2''.",

# Login and logout pages
'logouttitle'                => 'ಸದಸ್ಯ ಲಾಗೌಟ್',
'logouttext'                 => '<strong>ನೀವು ಈಗ ಲಾಗ್ ಔಟ್ ಆಗಿರುವಿರಿ.</strong>

ನೀವು {{SITENAME}} ಅನ್ನು ಅನಾಮಧೇಯವಾಗಿ ಉಪಯೋಗಿಸಬಹುದು, ಅಥವ ಮತ್ತೆ ಇದೇ ಹೆಸರಿನಲ್ಲಿ ಅಥವ ಬೇರೆ ಹೆಸರಿನಲ್ಲಿ ಲಾಗ್ ಇನ್ ಆಗಬಹುದು.
ಗಮನಿಸಿ: ನಿಮ್ಮ ಬ್ರೌಸರ್‍ನ cache ಅನ್ನು ಅಳಿಸುವವರೆಗೂ ಕೆಲವು ಪುಟಗಳು ನೀವಿನ್ನೂ ಲಾಗ್ ಇನ್ ಆಗಿರುವಂತೆ ಪ್ರದರ್ಶಿತವಾಗಬಹುದು.',
'welcomecreation'            => '== ಸುಸ್ವಾಗತ, $1! ==

ನಿಮ್ಮ ಅಕೌಂಟನ್ನು ಸೃಷ್ಟಿಸಲಾಗಿದೆ. ನಿಮ್ಮ {{SITENAME}} ಇಚ್ಛೆಗಳನ್ನು ಬದಲಾಯಿಸುವುದನ್ನು ಮರೆಯಬೇಡಿ.',
'yourname'                   => 'ನಿಮ್ಮ ಬಳಕೆಯ ಹೆಸರು',
'yourpassword'               => 'ನಿಮ್ಮ ಪ್ರವೇಶಪದ',
'yourpasswordagain'          => 'ಪ್ರವೇಶ ಪದ ಮತ್ತೊಮ್ಮೆ ಟೈಪ್ ಮಾಡಿ',
'remembermypassword'         => 'ಈ ಗಣಕಯಂತ್ರದಲ್ಲಿ ನನ್ನ ಪ್ರವೇಶ ಪದವನ್ನು ನೆನಪಿನಲ್ಲಿಟ್ಟುಕೊ',
'loginproblem'               => '<b>ನಿಮ್ಮ ಲಾಗಿನ್ ನಲ್ಲಿ ತೊ೦ದರೆಯಾಯಿತು.</b><br />ಮತ್ತೆ ಪ್ರಯತ್ನಿಸಿ!',
'login'                      => 'ಲಾಗ್ ಇನ್',
'loginprompt'                => '{{SITENAME}} ತಾಣಕ್ಕೆ ಲಾಗ್ ಇನ್ ಆಗಲು ನಿಮ್ಮ ಗಣಕಯಂತ್ರದಲ್ಲಿ ಕುಕೀ (cookie) ಸೌಲಭ್ಯವಿರಬೇಕು.',
'userlogin'                  => 'ಲಾಗ್ ಇನ್ - log in',
'logout'                     => 'ಲಾಗ್ ಔಟ್',
'userlogout'                 => 'ಲಾಗ್ ಔಟ್ - log out',
'notloggedin'                => 'ಲಾಗಿನ್ ಆಗಿಲ್ಲ',
'nologin'                    => 'ಲಾಗ್ ಇನ್ ಇಲ್ಲವೇ? $1.',
'nologinlink'                => 'ಖಾತೆಯನ್ನು ಸೃಷ್ಟಿಸಿ',
'createaccount'              => 'ಹೊಸ ಖಾತೆ ತೆರೆಯಿರಿ',
'gotaccount'                 => 'ಈಗಾಗಲೇ ಖಾತೆಯಿದೆಯೇ? $1.',
'gotaccountlink'             => 'ಲಾಗ್ ಇನ್',
'createaccountmail'          => 'ಇ-ಅಂಚೆಯ ಮೂಲಕ',
'badretype'                  => 'ನೀವು ಕೊಟ್ಟ ಪ್ರವೇಶಪದಗಳು ಬೇರೆಬೇರೆಯಾಗಿವೆ.',
'userexists'                 => 'ನೀವು ನೀಡಿದ ಸದಸ್ಯರ ಹೆಸರು ಆಗಲೆ ಬಳಕೆಯಲ್ಲಿದೆ. ದಯವಿಟ್ಟು ಬೇರೊಂದು ಹೆಸರನ್ನು ಆಯ್ಕೆ ಮಾಡಿ.',
'youremail'                  => 'ಇ-ಅಂಚೆ:',
'username'                   => 'ಸದಸ್ಯತ್ವದ ಹೆಸರು:',
'yourrealname'               => 'ನಿಜ ಹೆಸರು:',
'yourlanguage'               => 'ಭಾಷೆ:',
'yournick'                   => 'ಅಡ್ಡಹೆಸರು:',
'badsiglength'               => 'ಅಡ್ಡಹೆಸರು ತುಂಬಾ ಉದ್ದವಾಗಿದೆ; ಅದು $1 ಅಕ್ಷರಗಳಿಗಿಂತ ಕಡಿಮೆ ಇರಬೇಕು.',
'email'                      => 'ಇ-ಅಂಚೆ',
'prefs-help-realname'        => 'ನಿಜ ಹೆಸರು ನೀಡುವುದು ಐಚ್ಛಿಕ. ನೀವು ಅದನ್ನು ನೀಡಿದಲ್ಲಿ ನಿಮ್ಮ ಕಾಣಿಕೆಗಳಿಗೆ ನಿಮಗೆ ಮನ್ನಣೆ ನೀಡಲಾಗುವುದು.',
'loginerror'                 => 'ಲಾಗಿನ್ ದೋಷ',
'prefs-help-email-required'  => 'ಇ-ಅಂಚೆ ವಿಳಾಸ ಬೇಕಾಗಿದೆ.',
'loginsuccesstitle'          => 'ಲಾಗಿನ್ ಯಶಸ್ವಿ',
'loginsuccess'               => 'ನೀವು ಈಗ "$1" ಆಗಿ ವಿಕಿಪೀಡಿಯಕ್ಕೆ ಲಾಗಿನ್ ಆಗಿದ್ದೀರಿ.',
'nosuchuser'                 => '"$1" ಹೆಸರಿನ ಯಾವ ಸದಸ್ಯರೂ ಇಲ್ಲ.
ಕಾಗುಣಿತವನ್ನು ಪರೀಕ್ಷಿಸಿ, ಅಥವಾ ಕೆಳಗಿನ ಫಾರ್ಮ್ ಅನ್ನು ಉಪಯೋಗಿಸಿ ಹೊಸ ಸದಸ್ಯತ್ವವನ್ನು ಸೃಷ್ಟಿಸಿ.',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" ಹೆಸರಿನ ಸದಸ್ಯರು ಯಾರೂ ಇಲ್ಲ.
ಹೆಸರಲ್ಲಿ ಕಾಗುಣಿತ ತಪ್ಪಿದೆಯೆ ಎಂದು ಪರೀಕ್ಷಿಸಿ.',
'nouserspecified'            => 'ನೀವು ಒಂದು ಸದಸ್ಯತ್ವದ ಹೆಸರನ್ನು ಸೂಚಿಸಬೇಕು.',
'wrongpassword'              => 'ತಪ್ಪು ಪ್ರವೇಶ ಪದ ನೀಡಿರುವಿರಿ. ಮತ್ತೊಮ್ಮೆ ಪ್ರಯತ್ನಿಸಿ.',
'wrongpasswordempty'         => 'ಖಾಲಿ ಪ್ರವೇಶ ಪದವನ್ನು ನೀಡಿರುವಿರಿ. ಮತ್ತೊಮ್ಮೆ ಪ್ರಯತ್ನಿಸಿ.',
'passwordtooshort'           => 'ನಿಮ್ಮ ಪ್ರವೇಶಪದ ಸಿಂಧುವಲ್ಲ ಅಥವ ತುಂಬ ಚಿಕ್ಕದಾಗಿದೆ.
ಅದು ಕನಿಷ್ಟ $1 ಅಕ್ಷರಗಳನ್ನು ಹೊಂದಿರಬೇಕು ಮತ್ತು ನಿಮ್ಮ ಬಳಕೆಯ ಹೆಸರಿಂದ ಭಿನ್ನವಾಗಿರಬೇಕು.',
'mailmypassword'             => 'ಹೊಸ ಪ್ರವೇಶ ಪದವನ್ನು ಇ-ಅಂಚೆ ಮೂಲಕ ಕಳುಹಿಸಿ',
'passwordremindertitle'      => '{{SITENAME}}ಗೆ ಹೊಸ ತಾತ್ಕಾಲಿಕ ಪ್ರವೇಶ ಪದ',
'passwordremindertext'       => '{{SITENAME}} ($4) ಸೈಟಿಗೆ ಹೊಸ ಪ್ರವೇಶಪದವನ್ನು $1 ಐ.ಪಿ. ವಿಳಾಸದಿಂದ ಕೋರಲಾಗಿದೆ.
ಸದಸ್ಯ "$2" ಅವರ ಹೊಸ ಪ್ರವೇಶಪದ ಈಗ "$3".
ನೀವು ಲಾಗ್ ಇನ್ ಆಗಿ ತಮ್ಮ ಪ್ರವೇಶಪದವನ್ನು ಬದಲಾಯಿಸಿ. 

ನೀವು ಈ ಕೋರಿಕೆಯನ್ನು ಮಾಡಿಲ್ಲದಿದ್ದರೆ, ಅಥವ ನೀವು ನಿಮ್ಮ ಹಳೆಯ ಪ್ರವೇಶಪದವನ್ನು ನೆನಪಿಸಿಕೊಂಡರೆ ಈ ಮಾಹಿತಿಗೆ ಗಮನ ನೀಡದೆ ನಿಮ್ಮ ಹಳೆಯ ಪ್ರವೇಶಪದವನ್ನು ಉಪಯೋಗಿಸಲು ಮುಂದುವರೆಸಿರಿ.',
'noemail'                    => 'ಸದಸ್ಯ "$1" ಅವರ ಹೆಸರಿನಲ್ಲಿ ಯಾವ ಇ-ಅಂಚೆ ವಿಳಾಸವೂ ದಾಖಲಾಗಿಲ್ಲ.',
'passwordsent'               => '"$1" ಅವರ ಹೆಸರಿನಲ್ಲಿ ನೋಂದಾಯಿತವಾದ ಇ-ಅಂಚೆ ವಿಳಾಸಕ್ಕೆ ಹೊಸ ಪ್ರವೇಶಪದವನ್ನು ಕಳುಹಿಸಲಾಗಿದೆ.
ಅದನ್ನು ಪಡೆದ ಮೇಲೆ ಮತ್ತೆ ಲಾಗಿನ್ ಆಗಿ.',
'eauthentsent'               => 'ನೀವು ನೊಂದಾಯಿಸಿದ ಇ-ಅಂಚೆ ವಿಳಾಸಕ್ಕೆ ಧೃಡೀಕರಣ ಅಂಚೆಯನ್ನು ಕಳುಹಿಸಲಾಗಿದೆ. 
ಈ ವಿಳಾಸಕ್ಕೆ ಮುಂದೆ ಯಾವುದೇ ಇ-ಅಂಚೆ ಕಳುಹಿಸಲ್ಪಡುವ ಮುನ್ನ ನೀವು ಈ ಕಳುಹಿಸಿರುವ ಅಂಚೆಯಲ್ಲಿನ ನಿರ್ದೇಶನಗಳನ್ನು ಪಾಲಿಸಿ, ಈ ವಿಳಾಸವು ನಿಮ್ಮದೇ ಎಂದು ಧೃಡೀಕರಿಸಬೇಕು.',
'acct_creation_throttle_hit' => 'ಕ್ಷಮಿಸಿ, ನೀವಾಗಲೇ $1 ಖಾತೆಗಳನ್ನು ತೆರೆದಿದ್ದೀರಿ. ಇನ್ನು ಖಾತೆಗಳನ್ನು ತೆರೆಯಲಾಗುವುದಿಲ್ಲ.',
'emailconfirmlink'           => 'ನಿಮ್ಮ ಇ-ಅಂಚೆ ವಿಳಾಸವನ್ನು ಧೃಡೀಕರಿಸಿ',
'accountcreated'             => 'ಖಾತೆಯನ್ನು ಸೃಷ್ಟಿಸಲಾಯಿತು',
'accountcreatedtext'         => '$1 ಅವರ ಬಳಕೆದಾರ ಖಾತೆ ಸೃಷ್ಟಿಸಲ್ಪಟ್ಟಿದೆ.',
'createaccount-title'        => '{{SITENAME}} ತಾಣಕ್ಕೆ ಬಳಕೆದಾರ ಖಾತೆ ಸೃಷ್ಟಿ ಮಾಡುವಿಕೆ',
'loginlanguagelabel'         => 'ಭಾಷೆ: $1',

# Password reset dialog
'resetpass_success'   => 'ನಿಮ್ಮ ಪ್ರವೇಶ ಪದವನ್ನು ಯಶಸ್ವಿಯಾಗಿ ಬದಲಾಯಿಸಲಾಗಿದೆ. ಈಗ ನಿಮ್ಮನ್ನು ಲಾಗ್ ಇನ್ ಮಾಡಲಾಗುತ್ತಿದೆ...',
'resetpass_forbidden' => '{{SITENAME}} ಅಲ್ಲಿ ಪ್ರವೇಶಪದಗಳನ್ನು ಬದಲಾಯಿಸುವಂತಿಲ್ಲ.',

# Edit page toolbar
'bold_sample'     => 'ದಪ್ಪಗಿನ ಅಚ್ಚು',
'bold_tip'        => 'ದಪ್ಪಗಿನ ಅಚ್ಚು',
'italic_sample'   => 'ಓರೆ ಅಕ್ಷರಗಳು',
'italic_tip'      => 'ಓರೆ ಅಕ್ಷರಗಳು',
'link_sample'     => 'ಸಂಪರ್ಕದ ಹೆಸರು',
'link_tip'        => 'ಆಂತರಿಕ ಸಂಪರ್ಕ',
'extlink_sample'  => 'http://www.example.com ಸಂಪರ್ಕ ಶೀರ್ಷಿಕೆ',
'extlink_tip'     => 'ಬಾಹ್ಯ ಸಂಪರ್ಕ (http:// ಇಂದ ಶುರು ಮಾಡಿ)',
'headline_sample' => 'ಶಿರೋಲೇಖ',
'headline_tip'    => '೨ನೇ ಮಟ್ಟದ ತಲೆಬರಹ',
'math_sample'     => 'ಇಲ್ಲಿ ಸೂತ್ರವನ್ನು ಅಳವಡಿಸಿ',
'math_tip'        => 'ಗಣಿತ ಸೂತ್ರ (LaTeX)',
'nowiki_sample'   => 'ಈ ಜಾಗದಲ್ಲಿ ಬರೆಯಲ್ಪಟ್ಟದ್ದು ವಿಕೀಕರಣ ಆಗುವುದಿಲ್ಲ',
'nowiki_tip'      => 'ವಿಕಿ ರಚನಕ್ರಮವನ್ನು ಅಲಕ್ಷಿಸು',
'image_tip'       => 'ಅಳವಡಿಸಲ್ಪಟ್ಟ ಫೈಲು',
'media_tip'       => 'ಫೈಲಿಗೆ ಕೊಂಡಿ',
'sig_tip'         => 'ಸಮಯಮುದ್ರೆಯೊಂದಿಗೆ ನಿಮ್ಮ ಸಹಿ',
'hr_tip'          => 'ಅಡ್ಡ ಗೆರೆ (ಆದಷ್ಟು ಕಡಿಮೆ ಉಪಯೋಗಿಸಿ)',

# Edit pages
'summary'                  => 'ಸಾರಾಂಶ',
'subject'                  => 'ವಿಷಯ/ತಲೆಬರಹ',
'minoredit'                => 'ಇದು ಚುಟುಕಾದ ಬದಲಾವಣೆ',
'watchthis'                => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'savearticle'              => 'ಪುಟವನ್ನು ಉಳಿಸಿ',
'preview'                  => 'ಮುನ್ನೋಟ',
'showpreview'              => 'ಮುನ್ನೋಟ',
'showdiff'                 => 'ಬದಲಾವಣೆಗಳನ್ನು ತೋರಿಸಿ',
'anoneditwarning'          => "'''ಎಚ್ಚರ:''' ನೀವು ಲಾಗ್ ಇನ್ ಆಗಿಲ್ಲ. ನಿಮ್ಮ ಐಪಿ ವಿಳಾಸವು ಪುಟದ ಸಂಪಾದನೆಗಳ ಇತಿಹಾಸದಲ್ಲಿ ದಾಖಲಾಗುತ್ತದೆ.",
'missingsummary'           => "'''ಗಮನಿಸಿ:''' ನಿಮ್ಮ ಸಂಪಾದನೆಯ ಸಾರಾಂಶವನ್ನು ನೀವು ನೀಡಿಲ್ಲ. ಮತ್ತೊಮ್ಮೆ \"ಉಳಿಸು\" ಗುಂಡಿಯನ್ನು ಒತ್ತಿದರೆ, ಸಾರಾಂಶವಿಲ್ಲದೆಯೇ ನಿಮ್ಮ ಸಂಪಾದನೆಯನ್ನು ಉಳಿಸಲಾಗುವುದು.",
'summary-preview'          => 'ತಾತ್ಪರ್ಯ ಮುನ್ನೋಟ',
'blockedtitle'             => 'ಈ ಸದಸ್ಯರನ್ನು ತಡೆ ಹಿಡಿಯಲಾಗಿದೆ.',
'blockedtext'              => "<big>'''ನಿಮ್ಮ ಸದಸ್ಯತ್ವವನ್ನು ಅಥವ IP ವಿಳಾಸವನ್ನು ನಿರ್ಭಂಧಿಸಲಾಗಿದೆ.'''</big>

\$1 ಅವರು ಈ ನಿರ್ಭಂಧನೆಯನ್ನು ಒಡ್ಡಿರುವರು. ಇದಕ್ಕ ಅವರು ನೀಡಿರುವ ಕಾರಣ: ''\$2''.

* ನಿರ್ಭಂಧನೆಯ ಪ್ರಾರಂಭ: \$8
* ನಿರ್ಭಂಧನೆ ಮುಗಿಯುವುದು: \$6
* ನಿರ್ಭಂಧನೆ ಹೇರಲ್ಪಟ್ಟವರು: \$7

ನೀವು \$1 ಅಥವ ಇತರ [[{{MediaWiki:Grouppage-sysop}}|ನಿರ್ವಾಹಕರನ್ನು]] ಈ ನಿರ್ಭಂಧನೆಯನ್ನು ಚರ್ಚಿಸಲು ಸಂಪರ್ಕಿಸಬಹುದು.
ನೀವು 'ಸದಸ್ಯರಿಗೆ ಇ-ಅಂಚೆ ಕಳುಹಿಸಿ' ಸೌಲಭ್ಯವನ್ನು ಉಪಯೋಗಿಸಲು ನಿಮ್ಮ \"[[Special:Preferences|ನನ್ನ ಆಯ್ಕೆಗಳು]]\" ಪುಟದಲ್ಲಿ ನಿಮ್ಮ ಇ-ಅಂಚೆ ವಿಳಾಸವನ್ನು ನೀಡಿರಬೇಕು ಮತ್ತು ಈಗ ಆ ಪುಟವನ್ನು ನೀವು ಉಪಯೋಗಿಸಲು ನಿರ್ಭಂಧಿಸಲಾಗಿಲ್ಲ.
ನಿಮ್ಮ ಪ್ರಸಕ್ತ IP ವಿಳಾಸವು \$3, ಮತ್ತು ಈ ನಿರ್ಭಂಧನೆಯ ಕ್ರಮಸಂಖ್ಯೆ (ID) #\$5. ದಯವಿಟ್ಟು ನಿಮ್ಮ ಸಂಪರ್ಕಗಳಲ್ಲಿ ಈ ಸಂಖ್ಯೆಗಳನ್ನು ಸೇರಿಸಿ.",
'blockednoreason'          => 'ಯಾವ ಕಾರಣವೂ ನೀಡಲಾಗಿಲ್ಲ',
'whitelistedittitle'       => 'ಸಂಪಾದನೆ ಮಾಡಲು ಲಾಗ್ ಇನ್ ಆಗಿರಬೇಕು',
'whitelistreadtitle'       => 'ಓದಲು ಲಾಗ್ ಇನ್ ಆಗಿರಬೇಕು',
'whitelistreadtext'        => 'ಪುಟಗಳನ್ನು ಓದಲು ನೀವು [[Special:Userlogin|ಲಾಗ್ ಇನ್]] ಆಗಬೇಕು.',
'loginreqtitle'            => 'ಲಾಗಿನ್ ಆಗಬೇಕು',
'accmailtitle'             => 'ಪ್ರವೇಶ ಪದ ಕಳುಹಿಸಲಾಯಿತು.',
'accmailtext'              => "'$1'ನ ಪ್ರವೇಶ ಪದ $2 ಗೆ ಕಳುಹಿಸಲಾಗಿದೆ",
'newarticle'               => '(ಹೊಸತು)',
'newarticletext'           => "ಇನ್ನೂ ಅಸ್ಥಿತ್ವದಲ್ಲಿ ಇರದ ಪುಟದ ಲಿಂಕ್ ಅನ್ನು ನೀವು ಒತ್ತಿರುವಿರಿ.
ಈ ಪುಟವನ್ನು ಸೃಷ್ಟಿಸಲು ಕೆಳಗಿನ ಚೌಕದಲ್ಲಿ ಬರೆಯಲು ಆರಂಭಿಸಿರಿ.
(ಹೆಚ್ಚು ಮಾಹಿತಿಗೆ [[{{MediaWiki:Helppage}}|ಸಹಾಯ ಪುಟ]] ನೋಡಿ).
ಈ ಪುಟಕ್ಕೆ ನೀವು ತಪ್ಪಾಗಿ ಬಂದಿದ್ದಲ್ಲಿ ನಿಮ್ಮ ಬ್ರೌಸರ್‍ನ '''back''' ಬಟನ್ ಅನ್ನು ಒತ್ತಿ.",
'noarticletext'            => 'ಈ ಪುಟದಲ್ಲಿ ಸದ್ಯಕ್ಕೆ ಏನೂ ಇಲ್ಲ, ನೀವು ಇತರ ಪುಟಗಳಲ್ಲಿ [[Special:Search/{{PAGENAME}}|ಈ ಹೆಸರನ್ನು ಹುಡುಕಬಹುದು]] ಅಥವ [{{fullurl:{{FULLPAGENAME}}|action=edit}} ಈ ಪುಟವನ್ನು ಸಂಪಾದಿಸಬಹುದು].',
'note'                     => '<strong>ಸೂಚನೆ:</strong>',
'previewnote'              => '<strong>ಇದು ಕೇವಲ ಮುನ್ನೋಟ; ಪುಟವನ್ನು ಇನ್ನೂ ಉಳಿಸಲಾಗಿಲ್ಲ ಎಂಬುದನ್ನು ಮರೆಯದಿರಿ!</strong>',
'editing'                  => "'$1' ಲೇಖನ ಬದಲಾಯಿಸಲಾಗುತ್ತಿದೆ",
'editingsection'           => '$1 (ವಿಭಾಗ) ಅನ್ನು ಸಂಪಾದಿಸುತ್ತಿರುವಿರಿ',
'explainconflict'          => "ನೀವು ಈ ಪುಟವನ್ನು ಸಂಪಾದಿಸಲು ಪ್ರಾರಂಭ ಮಾಡಿದ ಮೇಲೆ ಬೇರೊಬ್ಬರು ಯಾರೊ ಪುಟವನ್ನು ಬದಲಾಯಿಸಿದ್ದಾರೆ.
ಮೇಲಿನ ಬರವಣಗೆ ಚೌಕದಲ್ಲಿ ಪುಟದ ಪ್ರಸಕ್ತ ಸ್ವರೂಪ ತೋರಿಸಲಾಗಿದೆ.
ನೀವು ಮಾಡಿದ ಸಂಪಾದನೆಗಳನ್ನು ಕೆಳಗಿನ ಬರವಣಗೆ ಚೌಕದಲ್ಲಿ ತೋರಿಸಲಾಗಿದೆ.
ನಿಮ್ಮ ಸಂಪಾದನೆಗಳನ್ನು ಪ್ರಸಕ್ತ ಸ್ವರೂಪದ ಲೇಖನದಲ್ಲಿ ನೀವು ಸೇರಿಸಬೇಕಾಗುತ್ತದೆ.
ನೀವು ಪುಟವನ್ನು ಉಳಿಸಿದಾಗ '''ಮೇಲಿನ ಚೌಕದಲ್ಲಿ ನೀವು ಮಾಡುವ ಬದಲಾವಣೆಗಳನ್ನು ಮಾತ್ರ''' ಉಳಿಸಲಾಗುತ್ತದೆ.",
'storedversion'            => 'ಈಗಾಗಲೇ ಉಳಿಸಲಾಗಿರುವ ಆವೃತ್ತಿ',
'editingold'               => '<strong>ಎಚ್ಚರಿಕೆ: ಈ ಪುಟದ ಹಳೆಯ ಆವೃತ್ತಿಯನ್ನು ಬದಲಾಯಿಸುತ್ತಿದ್ದೀರಿ. ಈ ಬದಲಾವಣೆಗಳನ್ನು ಉಳಿಸಿದಲ್ಲಿ, ನಂತರದ ಆವೃತ್ತಿಗಳೆಲ್ಲವೂ ಕಳೆದುಹೋಗುತ್ತವೆ.</strong>',
'yourdiff'                 => 'ವ್ಯತ್ಯಾಸಗಳು',
'copyrightwarning'         => 'ದಯವಿಟ್ಟು ಗಮನಿಸಿ: {{SITENAME}} ಸೈಟಿನಲ್ಲಿ ನಿಮ್ಮ ಎಲ್ಲಾ ಕಾಣಿಕೆಗಳನ್ನೂ $2 ಅಡಿಯಲ್ಲಿ ಬಿಡುಗಡೆ ಮಾಡಲಾಗುತ್ತದೆ (ಮಾಹಿತಿಗೆ $1 ನೋಡಿ). ನಿಮ್ಮ ಸಂಪಾದನೆಗಳನ್ನು ಬೇರೆಯವರು ನಿರ್ಧಾಕ್ಷಿಣ್ಯವಾಗಿ ಬದಲಾಯಿಸಿ ಬೇರೆ ಕಡೆಗಳಲ್ಲಿ ಹಂಚಬಹುದು. ಇದಕ್ಕೆ ನಿಮ್ಮ ಒಪ್ಪಿಗೆ ಇದ್ದರೆ ಮಾತ್ರ ಇಲ್ಲಿ ಸಂಪಾದನೆ ಮಾಡಿ.<br />
ಅಲ್ಲದೆ ನಿಮ್ಮ ಸಂಪಾದನೆಗಳನ್ನು ಸ್ವತಃ ರಚಿಸಿದ್ದು, ಅಥವ ಕೃತಿಸ್ವಾಮ್ಯತೆಯಿಂದ ಮುಕ್ತವಾಗಿರುವ ಕಡೆಯಿಂದ ಪಡೆದಿದ್ದು ಎಂದು ಪ್ರಮಾಣಿಸುತ್ತಿರುವಿರಿ.
<strong>ಕೃತಿಸ್ವಾಮ್ಯತೆಯ ಅಡಿಯಲ್ಲಿರುವ ರಚನೆಗಳನ್ನು ಅನುಮತಿ ಇಲ್ಲದೆ ಇಲ್ಲಿಗೆ ಹಾಕಬೇಡಿ!</strong>',
'longpagewarning'          => '<strong>ಎಚ್ಚರ: ಈ ಪುಟ $1 ಕಿಲೋಬೈಟ್‍ಗಳಷ್ಟು ಉದ್ದ ಇದೆ; ಕೆಲವು ಬ್ರೌಸರ್‍ಗಳಲ್ಲಿ ೩೨ ಕಿಲೋಬೈಟ್‍ಗಳಿಗಿಂತ ಉದ್ದದ ಪುಟಗಳನ್ನು ಸಂಪಾದನೆ ಮಾಡುವುದು ಕಷ್ಟ. ಪುಟವನ್ನು ಆದಷ್ಟು ವಿಭಾಗಗಳಾಗಿ ವಿಂಗಡಿಸಲು ಪ್ರಯತ್ನಿಸಿ.</strong>',
'protectedpagewarning'     => '<strong>ಎಚ್ಚರಿಕೆ: ಈ ಪುಟವನ್ನು ಸಂರಕ್ಷಿಸಲಾಗಿದೆ. ಇದನ್ನು ಕೇವಲ ನಿರ್ವಾಹಕರು ಬದಲಾಯಿಸಬಹುದು.</strong>',
'semiprotectedpagewarning' => "'''ಗಮನಿಸಿ:''' ಈ ಪುಟವನ್ನು ಕೇವಲ ನೊಂದಯಿತ ಸದಸ್ಯರು ಸಂಪಾದನೆ ಮಾಡಬರುವಂತೆ ಸಂರಕ್ಷಿಸಲಾಗಿದೆ.",
'templatesused'            => 'ಈ ಪುಟದಲ್ಲಿ ಉಪಯೋಗಿಸಲಾಗಿರುವ ಟೆಂಪ್ಲೇಟುಗಳು:',
'templatesusedpreview'     => 'ಈ ಮುನ್ನೋಟದಲ್ಲಿ ಉಪಯೋಗಿಸಲ್ಪಟ್ಟಿರುವ ಟೆಂಪ್ಲೇಟುಗಳು:',
'templatesusedsection'     => 'ಈ ವಿಭಾಗದಲ್ಲಿ ಉಪಯೋಗಿಸಲ್ಪಟ್ಟಿರುವ ಟೆಂಪ್ಲೇಟುಗಳು:',
'template-protected'       => '(ಸಂರಕ್ಷಿತ)',
'template-semiprotected'   => '(ಅರೆ-ಸಂರಕ್ಷಿತ)',
'nocreatetitle'            => 'ಪುಟವನ್ನು ಸೃಷ್ಟಿಸುವುದನ್ನು ನಿಯಮಿಸಲಾಗಿದೆ',
'nocreatetext'             => '{{SITENAME}} ಅಲ್ಲಿ ಹೊಸ ಪುಟಗಳನ್ನು ಸೃಷ್ಟಿಸಲು ಕೆಲವು ನಿಬಂಧನೆಗಳಿವೆ.
ನೀವು ಹಿಂದಿರುಗಿ ಆಗಲೇ ಅಸ್ಥಿತ್ವದಲ್ಲಿರುವ ಪುಟವೊಂದನ್ನು ಸಂಪಾದಿಸಿ, ಅಥವ [[Special:Userlogin|ಲಾಗ್ ಇನ್ ಆಗಿ ಅಥವ ಹೊಸ ಸದಸ್ಯರಾಗಿ]].',
'nocreate-loggedin'        => '{{SITENAME}} ಅಲ್ಲಿ ಹೊಸ ಪುಟಗಳನ್ನು ಸೃಷ್ಟಿಸಲು ನಿಮಗೆ ಅನುಮತಿ ಇಲ್ಲ.',
'recreate-deleted-warn'    => "'''ಎಚ್ಚರಿಕೆ: ಹಿಂದೆ ಅಳಿಸಲಾದ ಪುಟವನ್ನು ನೀವು ಮತ್ತೆ ಸೃಷ್ಟಿಸುತ್ತಿರುವಿರಿ.'''

ಈ ಪುಟವನ್ನು ಸಂಪಾದಿಸಲು ಸಮರ್ಪಕ ಕಾರಣವಿದೆಯೆ ಎಂದು ದಯವಿಟ್ಟು ಆಲೋಚಿಸಿ.
ಪುಟದ ಅಳಿಸುವಿಕೆ ದಿನಚರಿಯನ್ನು ಈ ಕೆಳಗೆ ನೀಡಲಾಗಿದೆ:",

# Account creation failure
'cantcreateaccounttitle' => 'ಖಾತೆಯನ್ನು ಸೃಷ್ಟಿಸಲಾಗುತ್ತಿಲ್ಲ',

# History pages
'viewpagelogs'        => 'ಈ ಪುಟಗಳ ದಾಖಲೆಗಳನ್ನು ವೀಕ್ಷಿಸಿ',
'nohistory'           => 'ಈ ಪುಟಕ್ಕೆ ಬದಲಾವಣೆಗಳ ಇತಿಹಾಸ ಇಲ್ಲ.',
'currentrev'          => 'ಈಗಿನ ತಿದ್ದುಪಡಿ',
'revisionasof'        => '$1 ದಿನದ ಆವೃತ್ತಿ',
'revision-info'       => '$2 ಅವರು $1 ಅಂದು ಸಂಪಾದನೆ ಮಾಡಿದ ನಂತರದ ಆವೃತ್ತಿ',
'previousrevision'    => '←ಹಿಂದಿನ ಪರಿಷ್ಕರಣೆ',
'nextrevision'        => 'ಮುಂದಿನ ಪರಿಷ್ಕರಣೆ',
'currentrevisionlink' => 'ಈಗಿನ ಪರಿಷ್ಕರಣೆ',
'cur'                 => 'ಸದ್ಯದ',
'next'                => 'ಮುಂದಿನದು',
'last'                => 'ಕೊನೆಯ',
'page_first'          => 'ಮೊದಲ',
'page_last'           => 'ಕೊನೆಯ',
'histlegend'          => 'ವ್ಯತ್ಯಾಸಗಳ ಆಯ್ಕೆ: ಹೋಲಿಕೆ ಮಾಡಬೇಕು ಎಂದಿರುವ ಎರಡು ಆವೃತ್ತಿಗಳ ಪಕ್ಕದಲ್ಲಿ ಇರುವ ಗುಂಡಿಗಳನ್ನು ಗುರುತು ಮಾಡಿ. ನಂತರ enter ಅನ್ನು ಒತ್ತಿ ಅಥವ ಪಟ್ಟಿಯ ಅಂತ್ಯದಲ್ಲಿರುವ ಗುಂಡಿಯನ್ನು ಒತ್ತಿ.<br />
ಆಖ್ಯಾನ: (ಈಗಿನ) = ಪ್ರಸಕ್ತ ಆವೃತ್ತಿಯೊಂದಿಗೆ ವ್ಯತ್ಯಾಸಗಳು,
(ಕೊನೆಯ) = ಹಿಂದಿನ ಆವೃತ್ತಿಯೊಂದಿಗೆ ವ್ಯತ್ಯಾಸಗಳು, ಚು = ಚುಟುಕಾದ ಬದಲಾವಣೆ.',
'deletedrev'          => '[ಅಳಿಸಲಾಗಿದೆ]',
'histfirst'           => 'ಅತ್ಯಂತ ಮುಂಚಿನ',
'histlast'            => 'ಅತ್ಯಂತ ಇತ್ತೀಚಿನ',
'historyempty'        => '(ಖಾಲಿ)',

# Revision feed
'history-feed-title'          => 'ಬದಲಾವಣೆಗಳ ಇತಿಹಾಸ',
'history-feed-description'    => 'ವಿಕಿಯ ಈ ಪುಟದ ಬದಲಾವಣೆಗಳ ಇತಿಹಾಸ',
'history-feed-item-nocomment' => '$1 $2 ಅಲ್ಲಿ', # user at time
'history-feed-empty'          => 'ನೀವು ಕೋರಿರುವ ಪುಟ ಅಸ್ಥಿತ್ವದಲ್ಲಿ ಇಲ್ಲ.
ಅದು ವಿಕಿಯಿಂದ ಅಳಿಸಲ್ಪಟ್ಟಿರಬಹುದು ಅಥವ ಪುನರ್ನಾಮಕಾರಣಗೊಂಡಿರಬಹುದು.
ಸಂಬಂಧಿತ ಹೊಸ ಪುಟಗಳನ್ನು [[Special:Search|ಹುಡುಕಲು ಪ್ರಯತ್ನಿಸಿ]].',

# Revision deletion
'rev-deleted-user' => '(ಬಳಕೆದಾರ ಹೆಸರು ತಗೆಯಲ್ಪಟ್ಟಿದೆ)',
'rev-delundel'     => 'ತೋರಿಸು/ಅಡಗಿಸು',
'pagehist'         => 'ಪುಟದ ಇತಿಹಾಸ',
'deletedhist'      => 'ಅಳಿಸಲ್ಪಟ್ಟ ಇತಿಹಾಸ',

# History merging
'mergehistory'             => 'ಪುಟ ಇತಿಹಾಸಗಳನ್ನು ವಿಲೀನಗೊಳಿಸು',
'mergehistory-go'          => 'ವಿಲೀನಗೊಳಿಸಬಹುದಾದ ಸಂಪಾದನೆಗಳನ್ನು ತೋರಿಸು',
'mergehistory-submit'      => 'ಆವೃತ್ತಿಗಳನ್ನು ವಿಲೀನಗೊಳಿಸು',
'mergehistory-autocomment' => '[[:$1]] ಅನ್ನು [[:$2]] ಪುಟದೊಳಗೆ ವಿಲೀನಗೊಳಿಸಲಾಯಿತು',

# Merge log
'mergelog' => 'ಸೇರ್ಪಡೆಯ ದಾಖಲೆ',

# Diffs
'history-title'           => '"$1" ಪುಟದ ಬದಲಾವಣೆಗಳ ಇತಿಹಾಸ',
'difference'              => '(ಆವೃತ್ತಿಗಳ ನಡುವಿನ ವ್ಯತ್ಯಾಸ)',
'lineno'                  => '$1 ನೇ ಸಾಲು:',
'compareselectedversions' => 'ಆಯ್ಕೆ ಮಾಡಿದ ಆವೃತ್ತಿಗಳನ್ನು ಹೊಂದಾಣಿಕೆ ಮಾಡಿ ನೋಡಿ',
'editundo'                => 'ಹಿಂದಿನಂತೆ',
'diff-multi'              => '(ಮಧ್ಯದಲ್ಲಿ ಆಗಿರುವ {{PLURAL:$1|೧ ಬದಲಾವಣೆಯನ್ನು|$1 ಬದಲಾವಣೆಗಳನ್ನು}} ತೋರಿಸಲಾಗಿಲ್ಲ.)',

# Search results
'searchresults'         => 'ಶೋಧನೆಯ ಫಲಿತಾಂಶಗಳು',
'searchsubtitle'        => "'''[[:$1]]''' ಅನ್ನು ಹುಡುಕಿದಿರಿ",
'searchsubtitleinvalid' => "'''$1''' ಅನ್ನು ಹುಡುಕಿದಿರಿ",
'noexactmatch'          => "'''\"\$1\" ಹೆಸರಿನ ಯಾವ ಪುಟವೂ ಇಲ್ಲ.''' ನೀವು ಅದನ್ನು [[:\$1|ಸೃಷ್ಟಿಸಬಹುದು]].",
'noexactmatch-nocreate' => "'''\"\$1\" ಹೆಸರಿನ ಯಾವ ಪುಟವೂ ಇಲ್ಲ.'''",
'prevn'                 => 'ಹಿಂದಿನ $1',
'nextn'                 => 'ಮುಂದಿನ $1',
'viewprevnext'          => 'ವೀಕ್ಷಿಸು ($1) ($2) ($3)',
'searchall'             => 'ಎಲ್ಲಾ',
'powersearch'           => 'ಹುಡುಕಿ',

# Preferences page
'preferences'          => 'ಇಚ್ಛೆಗಳು',
'mypreferences'        => 'ನನ್ನ ಆಯ್ಕೆಗಳು',
'prefs-edits'          => 'ಸಂಪಾದನೆಗಳ ಸಂಖ್ಯೆ:',
'prefsnologin'         => 'ಲಾಗಿನ್ ಆಗಿಲ್ಲ',
'changepassword'       => 'ಪ್ರವೇಶ ಪದ ಬದಲಾಯಿಸಿ',
'dateformat'           => 'ದಿನಾಂಕದ ಫಾರ್ಮ್ಯಾಟ್',
'datetime'             => 'ದಿನ ಮತ್ತು ಸಮಯ',
'prefs-rc'             => 'ಇತ್ತೀಚಿನ ಬದಲಾವಣೆಗಳು',
'prefs-watchlist'      => 'ವೀಕ್ಷಣಾಪಟ್ಟಿ',
'prefs-watchlist-days' => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ ತೋರಿಸಲಾಗುವ ದಿನಗಳು:',
'prefs-misc'           => 'ಇತರೆ',
'saveprefs'            => 'ಉಳಿಸಿ',
'oldpassword'          => 'ಹಳೆಯ ಪ್ರವೇಶ ಪದ',
'newpassword'          => 'ಹೊಸ ಪ್ರವೇಶ ಪದ',
'retypenew'            => 'ಹೊಸ ಪ್ರವೇಶಪದವನ್ನು ಮತ್ತೆ ಟೈಪಿಸು:',
'searchresultshead'    => 'ಹುಡುಕು',
'recentchangescount'   => 'ಇತ್ತೀಚೆಗಿನ ಬದಲಾವಣೆಗಳಲ್ಲಿರುವ ವಿಷಯಗಳ ಸಂಖ್ಯೆ',
'savedprefs'           => 'ನಿಮ್ಮ ಇಚ್ಛೆಗಳನ್ನು ಉಳಿಸಲಾಯಿತು.',
'timezonelegend'       => 'ಟೈಮ್ ಝೋನ್',
'localtime'            => 'ಸ್ಥಳೀಯ ಸಮಯ',
'allowemail'           => 'ಬೇರೆ ಸದಸ್ಯರಿಂದ ಈ-ಮೈಲ್‍ಗಳನ್ನು ಸ್ವೀಕರಿಸು',
'default'              => 'ಮೂಲಸ್ಥಿತಿ',
'files'                => 'ಫೈಲುಗಳು',

# User rights
'userrights-user-editname' => 'ಬಳಕೆದಾರ ಹೆಸರನ್ನು ಸೂಚಿಸಿ:',
'editinguser'              => "'''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]]) ಅವರ ಸದಸ್ಯತ್ವದ ಹಕ್ಕುಗಳನ್ನು ಬದಲಾಗಿಸಲಾಗುತ್ತಿದೆ.",
'userrights-reason'        => 'ಬದಲಾವಣೆಗೆ ಕಾರಣ:',

# Groups
'group'     => 'ಗುಂಪು:',
'group-bot' => 'ಬಾಟ್‍ಗಳು',
'group-all' => '(ಎಲ್ಲವೂ)',

'group-bot-member' => 'ಬಾಟ್',

'grouppage-bot'   => '{{ns:project}}:ಬಾಟ್‍ಗಳು',
'grouppage-sysop' => '{{ns:project}}:ನಿರ್ವಾಹಕರು',

# User rights log
'rightslog' => 'ಸದಸ್ಯರ ಹಕ್ಕುಗಳ ದಾಖಲೆಗಳು',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ಬದಲಾವಣೆ|ಬದಲಾವಣೆಗಳು}}',
'recentchanges'                     => 'ಇತ್ತೀಚೆಗಿನ ಬದಲಾವಣೆಗಳು',
'recentchangestext'                 => 'ವಿಕಿಗೆ ಮಾಡಲ್ಪಟ್ಟ ಇತ್ತೀಚಿನ ಬದಲಾವಣೆಗಳನ್ನು ಈ ಪುಟದಲ್ಲಿ ನೀವು ಕಾಣಬಹುದು.',
'recentchanges-feed-description'    => 'ವಿಕಿಯಲ್ಲಿ ಆಗುವ ಹೊಸ ಬದಲಾವಣೆಗಳ ಮೇಲೆ ನಿಗ ಇಡಲು ಉಪಯೋಗವಾಗುವ ಫೀಡು.',
'rcnote'                            => 'ಕೊನೆಯ <strong>$2</strong> ದಿನಗಳಲ್ಲಿ ಮಾಡಿದ <strong>$1</strong> ಬದಲಾವಣೆಗಳು ಕೆಳಗಿನಂತಿವೆ.',
'rcnotefrom'                        => "'''$2''' ಇಂದ ಆಗಿರುವ ಬದಲಾವಣೆಗಳು ಕೆಳಗಿವೆ (ಕೊನೆಯ '''$1'''ರವರೆಗೆ ತೋರಿಸಲಾಗಿದೆ).",
'rclistfrom'                        => '$1 ಇಂದ ಪ್ರಾರಂಭಿಸಿ ಮಾಡಲಾದ ಬದಲಾವಣೆಗಳನ್ನು ನೋಡಿ',
'rcshowhideminor'                   => 'ಚಿಕ್ಕಪುಟ್ಟ ಬದಲಾವಣೆಗಳನ್ನು $1',
'rcshowhidebots'                    => 'ಬಾಟ್‍ಗಳನ್ನು $1',
'rcshowhideliu'                     => 'ಲಾಗ್-ಇನ್ ಆಗಿರುವ ಸದಸ್ಯರು $1',
'rcshowhideanons'                   => 'ಅನಾಮಧೇಯ ಸದಸ್ಯರು $1',
'rcshowhidepatr'                    => '$1 ಪರೀಕ್ಷಿತ ಸಂಪಾದನೆಗಳು',
'rcshowhidemine'                    => 'ನನ್ನ ಸಂಪಾದನೆಗಳನ್ನು $1',
'rclinks'                           => 'ಕೊನೆಯ $2 ದಿನಗಳಲ್ಲಿ ಮಾಡಿದ $1 ಕೊನೆಯ ಬದಲಾವಣೆಗಳನ್ನು ನೋಡಿ <br />$3',
'diff'                              => 'ವ್ಯತ್ಯಾಸ',
'hist'                              => 'ಇತಿಹಾಸ',
'hide'                              => 'ಅಡಗಿಸು',
'show'                              => 'ತೋರಿಸು',
'minoreditletter'                   => 'ಚು',
'newpageletter'                     => 'ಹೊ',
'boteditletter'                     => 'ಬಾ',
'number_of_watching_users_pageview' => '[$1 ವೀಕ್ಷಿಸುತ್ತಿರುವ {{PLURAL:$1|ಸದಸ್ಯ|ಸದಸ್ಯರು}}]',

# Recent changes linked
'recentchangeslinked'          => 'ಸಂಬಂಧಪಟ್ಟ ಬದಲಾವಣೆಗಳು',
'recentchangeslinked-title'    => '$1 ಪುಟಕ್ಕೆ ಸಂಬಂಧಿಸಿದ ಬದಲಾವಣೆಗಳು',
'recentchangeslinked-noresult' => 'ಸೂಚಿತ ಕಾಲದಲ್ಲಿ ಸಂಪರ್ಕ ಹೊಂದಿರುವ ಪುಟಗಳಲ್ಲಿ ಯಾವ ಬದಲಾವಣೆಗಳೂ ಇಲ್ಲ.',
'recentchangeslinked-summary'  => "ಈ ವಿಶೇಷ ಪುಟ ಸಂಪರ್ಕ ಹೊಂದಿರುವ ಪುಟಗಳ ಕೊನೆ ಬದಲಾವಣೆಗಳನ್ನು ಪಟ್ಟಿ ಮಾಡುತ್ತದೆ. 
ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ ಇರುವ ಪುಟಗಳು '''ದಪ್ಪ ಅಕ್ಷರ'''ಗಳಲ್ಲಿ ಇವೆ.",

# Upload
'upload'             => 'ಫೈಲ್ ಅಪ್ಲೋಡ್',
'uploadbtn'          => 'ಫೈಲನ್ನು ಅಪ್ಲೋಡ್ ಮಾಡಿ',
'uploadnologin'      => 'ಲಾಗಿನ್ ಆಗಿಲ್ಲ',
'uploaderror'        => 'ಅಪ್ಲೋಡ್ ದೋಷ',
'uploadlog'          => 'ಅಪ್ಲೋಡ್ ದಾಖಲೆಗಳು',
'uploadlogpage'      => 'ಅಪ್ಲೋಡ್ ದಾಖಲೆ',
'filename'           => 'ಕಡತದ ಹೆಸರು',
'filedesc'           => 'ಸಾರಾಂಶ',
'filestatus'         => 'ಕೃತಿಸ್ವಾಮ್ಯತೆಯ ಸ್ಥಿತಿ:',
'filesource'         => 'ಆಕರ:',
'uploadedfiles'      => 'ಅಪ್ಲೋಡ್ ಆಗಿರುವ ಫೈಲುಗಳು',
'ignorewarning'      => 'ಎಚ್ಚರಿಕೆಯನ್ನು ಕಡೆಗಣಿಸಿ ಫೈಲನ್ನು ಉಳಿಸಿ',
'ignorewarnings'     => 'ಎಲ್ಲಾ ಎಚ್ಚರಗಳನ್ನೂ ಕಡೆಗಣಿಸು',
'badfilename'        => 'ಚಿತ್ರದ ಹೆಸರನ್ನು $1 ಗೆ ಬದಲಾಯಿಸಲಾಗಿದೆ.',
'fileexists'         => 'ಈ ಹೆಸರಿನ ಫೈಲ್ ಆಗಲೇ ಅಸ್ತಿತ್ವದಲ್ಲಿದೆ. ಈ ಹೆಸರನ್ನು ಬದಲಾಯಿಸಲು ಇಚ್ಛೆಯಿಲ್ಲದಿದ್ದರೆ, ದಯವಿಟ್ಟು $1 ಅನ್ನು ಪರೀಕ್ಷಿಸಿ.',
'savefile'           => 'ಕಡತವನ್ನು ಉಳಿಸಿ',
'uploadedimage'      => '"[[$1]]" ಅಪ್ಲೋಡ್ ಆಯಿತು',
'overwroteimage'     => '"[[$1]]" ಫೈಲಿನ ಹೊಸ ಆವೃತ್ತಿಯನ್ನು ಅಪ್ಲೋಡ್ ಮಾಡಲಾಯಿತು',
'uploaddisabledtext' => '{{SITENAME}} ತಾಣದಲ್ಲಿ ಫೈಲುಗಳ ಅಪ್ಲೋಡ್ ಮಾಡುವಿಕೆ ತಡೆಗಟ್ಟಲ್ಪಟ್ಟಿದೆ.',
'watchthisupload'    => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',

'upload-file-error' => 'ಆಂತರಿಕ ದೋಷ',

# Special:Imagelist
'imagelist'      => 'ಚಿತ್ರಗಳ ಪಟ್ಟಿ',
'imagelist_date' => 'ದಿನಾಂಕ',
'imagelist_name' => 'ಹೆಸರು',
'imagelist_user' => 'ಸದಸ್ಯ',
'imagelist_size' => 'ಗಾತ್ರ',

# Image description page
'filehist'                  => 'ಕಡತದ ಇತಿಹಾಸ',
'filehist-help'             => 'ದಿನ/ಕಾಲ ಒತ್ತಿದರೆ ಆ ಸಮಯದಲ್ಲಿ ಈ ಕಡತದ ವಸ್ತುಸ್ಥಿತಿ ತೋರುತ್ತದೆ.',
'filehist-deleteall'        => 'ಎಲ್ಲವನ್ನೂ ಅಳಿಸು',
'filehist-deleteone'        => 'ಇದನ್ನು ಅಳಿಸು',
'filehist-current'          => 'ಪ್ರಸಕ್ತ',
'filehist-datetime'         => 'ದಿನ/ಕಾಲ',
'filehist-user'             => 'ಸದಸ್ಯ',
'filehist-dimensions'       => 'ಆಯಾಮಗಳು',
'filehist-filesize'         => 'ಫೈಲಿನ ಗಾತ್ರ',
'filehist-comment'          => 'ವಕ್ಕಣೆ',
'imagelinks'                => 'ಕೊಂಡಿಗಳು',
'linkstoimage'              => 'ಈ ಕೆಳಗಿನ ಪುಟಗಳು ಈ ಚಿತ್ರಕ್ಕೆ ಸಂಪರ್ಕ ಹೊಂದಿವೆ:',
'nolinkstoimage'            => 'ಈ ಫೈಲಿಗೆ ಯಾವ ಪುಟವೂ ಸಂಪರ್ಕ ಹೊಂದಿಲ್ಲ.',
'sharedupload'              => 'ಈ ಫೈಲಿನ ಮೂಲವನ್ನು ಹಲವರು ಹಂಚಿಕೊಂಡಿರುವರು ಮತ್ತು ಅನೇಕ ಯೋಜನೆಗಳಲ್ಲಿ ಇದು ಉಪಯೋಗದಲ್ಲಿರಬಹುದು.',
'noimage'                   => 'ಈ ಹೆಸರಿನ ಫೈಲು ಯಾವುದೂ ಇಲ್ಲ. ನಿಮಗೆ ಆದರೆ ಅದನ್ನು $1.',
'noimage-linktext'          => 'ಅಪ್ಲೋಡ್ ಮಾಡಿ',
'uploadnewversion-linktext' => 'ಈ ಫೈಲಿನ ಹೊಸ ಆವೃತ್ತಿಯನ್ನು ಅಪ್ಲೋಡ್ ಮಾಡಿ',

# File deletion
'filedelete'                  => '$1 ಅನ್ನು ಅಳಿಸು',
'filedelete-legend'           => 'ಫೈಲನ್ನು ಅಳಿಸು',
'filedelete-intro'            => "'''[[Media:$1|$1]]''' ಅನ್ನು ಅಳಿಸುತ್ತಿರುವಿರಿ.",
'filedelete-comment'          => 'ಅಳಿಸುವಿಕೆಗೆ ಕಾರಣ:',
'filedelete-submit'           => 'ಅಳಿಸು',
'filedelete-success'          => "'''$1''' ಅಳಿಸಲಾಗಿದೆ.",
'filedelete-nofile'           => "'''$1''' {{SITENAME}} ಅಲ್ಲಿ ಅಸ್ಥಿತ್ವದಲ್ಲಿಲ್ಲ.",
'filedelete-reason-otherlist' => 'ಇತರ ಕಾರಣ',
'filedelete-reason-dropdown'  => '*ಸಾಮಾನ್ಯ ಅಳಿಸುವಿಕೆ ಕಾರಣಗಳು
** ಕೃತಿಸ್ವಾಮ್ಯತೆ ಉಲ್ಲಂಘನೆ
** ದ್ವಿಪ್ರತಿಗಳಿರುವ ಫೈಲು',
'filedelete-edit-reasonlist'  => 'ಅಳಿಸುವಿಕೆಯ ಕಾರಣಗಳನ್ನು ಸಂಪಾದಿಸು',

# MIME search
'mimesearch' => 'MIME ಹುಡುಕಾಟ',

# Unwatched pages
'unwatchedpages' => 'ಯಾರೂ ವೀಕ್ಷಿಸುತ್ತಿರದ ಪುಟಗಳು',

# List redirects
'listredirects' => 'ರೀಡೈರೆಕ್ಟ್ ಪುಟಗಳ ಪಟ್ಟಿ',

# Unused templates
'unusedtemplates'     => 'ಉಪಯೋಗದಲ್ಲಿರದ ಟೆಂಪ್ಲೇಟುಗಳು',
'unusedtemplatestext' => 'ಯಾವ ಪುಟದಲ್ಲೂ ಉಪಯೋಗದಲ್ಲಿ ಇರದ ಟೆಂಪ್ಲೇಟುಗಳನ್ನು ಇಲ್ಲಿ ಪಟ್ಟಿ ಮಾಡಲಾಗಿದೆ. ಇವನ್ನು ಅಳಿಸುವ ಮುನ್ನ ಟೆಂಪ್ಲೇಟುಗಳಿಗೆ ಇತರ ಲಿಂಕುಗಳಿದೆಯೆ ಎಂದು ಪರೀಕ್ಷಿಸಲು ಮರೆಯದಿರಿ.',
'unusedtemplateswlh'  => 'ಇತರ ಕೊಂಡಿಗಳು',

# Random page
'randompage' => 'ಯಾದೃಚ್ಛಿಕ ಪುಟ',

# Random redirect
'randomredirect' => 'ಯದೃಚ್ಛಿಕ ಪುನರ್ನಿರ್ದೇಶಿತ ಪುಟ',

# Statistics
'statistics'             => 'ಅಂಕಿ ಅಂಶಗಳು',
'sitestats'              => 'ತಾಣದ ಅಂಕಿಅಂಶಗಳು',
'userstats'              => 'ಸದಸ್ಯರ ಅಂಕಿ ಅಂಶ',
'sitestatstext'          => "ಒಟ್ಟು '''\$1''' ಪುಟಗಳು ಡೇಟಾಬೇಸ್‌ನಲ್ಲಿವೆ.
ಈ ಸಂಖ್ಯೆ \"ಚರ್ಚೆ\" ಪುಟಗಳನ್ನು, ವಿಕಿಪೀಡಿಯಾದ ಬಗೆಗಿನ ಪುಟಗಳನ್ನು, ಹಾಗೂ ಪುಟ್ಟ \"ಚುಟುಕು\" ಪುಟಗಳನ್ನೂ, ರೆಡೈರೆಕ್ಟ್ ಮಾಡಿದ ಪುಟಗಳನ್ನು ಹಾಗೂ ಬೇರೆಲ್ಲೂ ಸೇರಿಸಲಾಗದ ಕೆಲವು ಇತರೆ ಪುಟಗಳನ್ನು ಒಳಗೊಂಡಿದೆ.

ಇವುಗಳನ್ನು ಬಿಟ್ಟು, ಒಟ್ಟು '''\$2''' ಬಹುಶಃ ನಿಜವಾದ ಲೇಖನಗಳಿಂದ ಕೂಡಿದ ಪುಟಗಳಿವೆ.",
'userstatstext'          => "ಒಟ್ಟು '''$1''' ನೊಂದಾಯಿಸಿದ ಸದಸ್ಯರಿದ್ದಾರೆ. ಇವರಲ್ಲಿ '''$2''' ಮಂದಿ ನಿರ್ವಾಹಕರಿದ್ದಾರೆ ($3 ನೋಡಿ).",
'statistics-mostpopular' => 'ಅತ್ಯಂತ ಹೆಚ್ಚು ವೀಕ್ಷಿತ ಪುಟಗಳು',

'disambiguations' => 'ದ್ವಂದ್ವನಿವಾರಣಾ ಪುಟಗಳು',

'doubleredirects' => 'ಮರುಕಳಿಸಿದ ಪುನರ್ನಿರ್ದೇಶನಗಳು',

'brokenredirects'        => 'ಮುರಿದ ರಿಡೈರೆಕ್ಟ್‌ಗಳು',
'brokenredirectstext'    => 'ಕೆಳಗಿನ ರಿಡೈರೆಕ್ಟುಗಳು ವಿಕಿಯಲ್ಲಿ ಇಲ್ಲದ ಪುಟಗಳಿಗೆ ಸಂಪರ್ಕ ಹೊಂದಿವೆ:',
'brokenredirects-edit'   => '(ಸಂಪಾದಿಸಿ)',
'brokenredirects-delete' => '(ಅಳಿಸಿ)',

'withoutinterwiki'        => 'ಬೇರೆ ಭಾಷೆಗಳಿಗೆ ಸಂಪರ್ಕ ಹೊಂದಿರದ ಪುಟಗಳು',
'withoutinterwiki-submit' => 'ತೋರಿಸು',

'fewestrevisions' => 'ಅತ್ಯಂತ ಕಡಿಮೆ ಬದಲಾವಣೆಗಳನ್ನು ಹೊಂದಿರುವ ಪುಟಗಳು',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|ಬೈಟ್|ಬೈಟ್‍ಗಳು}}',
'ncategories'             => '$1 {{PLURAL:$1|ವರ್ಗ|ವರ್ಗಗಳು}}',
'nlinks'                  => '$1 {{PLURAL:$1|ಸಂಪರ್ಕ|ಸಂಪರ್ಕಗಳು}}',
'nmembers'                => '$1 {{PLURAL:$1|ಸದಸ್ಯ|ಸದಸ್ಯರು}}',
'nrevisions'              => '$1 {{PLURAL:$1|ಬದಲಾವಣೆ|ಬದಲಾವಣೆಗಳು}}',
'nviews'                  => '$1 {{PLURAL:$1|ನೋಟ|ನೋಟಗಳು}}',
'lonelypages'             => 'ಒಬ್ಬಂಟಿ ಪುಟಗಳು',
'uncategorizedpages'      => 'ವರ್ಗ ಗೊತ್ತು ಮಾಡದ ಪುಟಗಳು',
'uncategorizedcategories' => 'ಅವರ್ಗೀಕೃತ ವರ್ಗಗಳು',
'uncategorizedimages'     => 'ಅವರ್ಗೀಕೃತ ಫೈಲುಗಳು',
'uncategorizedtemplates'  => 'ಅವರ್ಗೀಕೃತ ಟೆಂಪ್ಲೇಟುಗಳು',
'unusedcategories'        => 'ಬಳಕೆಯಲ್ಲಿರದ ವರ್ಗಗಳು',
'unusedimages'            => 'ಉಪಯೋಗಿಸದ ಚಿತ್ರಗಳು',
'popularpages'            => 'ಜನಪ್ರಿಯ ಪುಟಗಳು',
'wantedcategories'        => 'ಬೇಕಾಗಿರುವ ವರ್ಗಗಳು',
'wantedpages'             => 'ಬೇಕಾಗಿರುವ ಪುಟಗಳು',
'mostlinked'              => 'ಅತ್ಯಂತ ಹೆಚ್ಚು ಸಂಪರ್ಕಗಳನ್ನು ಹೊಂದಿರುವ ಪುಟಗಳು',
'mostlinkedcategories'    => 'ಅತ್ಯಂತ ಹೆಚ್ಚು ಸಂಪರ್ಕಗಳನ್ನು ಹೊಂದಿರುವ ವರ್ಗಗಳು',
'mostlinkedtemplates'     => 'ಅತ್ಯಂತ ಹೆಚ್ಚು ಸಂಪರ್ಕಗಳನ್ನು ಹೊಂದಿರುವ ಟೆಂಪ್ಲೇಟುಗಳು',
'mostcategories'          => 'ಅತ್ಯಂತ ಹೆಚ್ಚು ವರ್ಗಗಳನ್ನು ಹೊಂದಿರುವ ಪುಟಗಳು',
'mostimages'              => 'ಅತಿ ಹೆಚ್ಚು ಸಂಪರ್ಕಗಳನ್ನು ಹೊಂದಿರುವ ಫೈಲುಗಳು',
'mostrevisions'           => 'ಅತ್ಯಂತ ಹೆಚ್ಚು ಬದಲಾವಣೆಗಳಾಗಿವು ಪುಟಗಳು',
'prefixindex'             => 'ಪೂರ್ವನಾಮಗಳ ಸೂಚಿಕೆ',
'shortpages'              => 'ಪುಟ್ಟ ಪುಟಗಳು',
'longpages'               => 'ಉದ್ದನೆಯ ಪುಟಗಳು',
'deadendpages'            => 'ಕೊನೆಯಂಚಿನ ಪುಟಗಳು',
'protectedpages'          => 'ಸಂರಕ್ಷಿತ ಪುಟಗಳು',
'protectedtitles'         => 'ಸಂರಕ್ಷಿತ ಶೀರ್ಷಿಕೆಗಳು',
'protectedtitlestext'     => 'ಈ ಕೆಳಗಿನ ಶೀರ್ಷಿಕೆಗಳನ್ನು ಸೃಷ್ಟಿಸಲಾಗದಂತೆ ಸಂರಕ್ಷಿಸಲಾಗಿದೆ',
'listusers'               => 'ಸದಸ್ಯರ ಪಟ್ಟಿ',
'specialpages'            => 'ವಿಶೇಷ ಪುಟಗಳು',
'spheading'               => 'ಎಲ್ಲಾ ಸದಸ್ಯರಿಗೂ ಇರುವ ವಿಶೇಷ ಪುಟಗಳು',
'newpages'                => 'ಹೊಸ ಪುಟಗಳು',
'newpages-username'       => 'ಬಳಕೆದಾರ ಹೆಸರು:',
'ancientpages'            => 'ಹಳೆಯ ಪುಟಗಳು',
'move'                    => 'ಸ್ಥಳಾಂತರಿಸಿ',
'movethispage'            => 'ಈ ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',

# Book sources
'booksources'    => 'ಪುಸ್ತಕಗಳ ಮೂಲ',
'booksources-go' => 'ಹೋಗು',

# Special:Log
'specialloguserlabel'  => 'ಸದಸ್ಯ:',
'speciallogtitlelabel' => 'ಶೀರ್ಷಿಕೆ:',
'log'                  => 'ದಾಖಲೆಗಳು',
'all-logs-page'        => 'ಎಲ್ಲಾ ದಾಖಲೆಗಳು',
'log-search-legend'    => 'ದಾಖಲೆಗಳಿಗೆ ಹುಡುಕು',
'log-search-submit'    => 'ಹೋಗು',
'logempty'             => 'ದಾಖಲೆಗಳಲ್ಲಿ ಇದಕ್ಕೆ ಹೋಲುವ ಯಾವ ವಸ್ತುವೂ ಇಲ್ಲ.',

# Special:Allpages
'allpages'       => 'ಎಲ್ಲ ಪುಟಗಳು',
'alphaindexline' => '$1 ಇಂದ $2',
'nextpage'       => 'ಮುಂದಿನ ಪುಟ ($1)',
'prevpage'       => 'ಹಿಂದಿನ ಪುಟ ($1)',
'allpagesfrom'   => 'ಇದರಿಂದ ಪ್ರಾರಂಭವಾಗುವ ಪುಟಗಳನ್ನು ತೋರಿಸು:',
'allarticles'    => 'ಎಲ್ಲ ಲೇಖನಗಳು',
'allpagesprev'   => 'ಹಿಂದಕ್ಕೆ',
'allpagesnext'   => 'ಮುಂದಕ್ಕೆ',
'allpagessubmit' => 'ಹೋಗು',
'allpagesprefix' => 'ಈ ಪೂರ್ವಪದವನ್ನು ಹೊಂದಿರುವ ಪುಟಗಳನ್ನು ತೋರಿಸು:',

# Special:Listusers
'listusersfrom'      => 'ಇದರಿಂದ ಪ್ರಾರಂಭವಾಗುವ ಬಳಕೆದಾರರನ್ನು ತೋರಿಸು:',
'listusers-submit'   => 'ತೋರು',
'listusers-noresult' => 'ಯಾವ ಬಳಕೆದಾರರೂ ಸಿಗಲಿಲ್ಲ.',

# E-mail user
'emailuser'       => 'ಈ ಸದಸ್ಯರಿಗೆ ಇ-ಅಂಚೆ ಕಳಿಸಿ',
'emailpage'       => 'ಸದಸ್ಯರಿಗೆ ವಿ-ಅ೦ಚೆ ಕಳಿಸಿ',
'defemailsubject' => 'ವಿಕಿಪೀಡಿಯ ವಿ-ಅ೦ಚೆ',
'noemailtitle'    => 'ಯಾವುದೇ ಇ-ಅಂಚೆ ವಿಳಾಸ ಇಲ್ಲ',
'noemailtext'     => 'ಈ ಸದಸ್ಯ ಯಾವುದೇ ಇ-ಅಂಚೆ ವಿಳಾಸ ನೀಡಿಲ್ಲ, ಅಥವ ಬೇರೆ ಸದಸ್ಯರಿಂದ ಇ-ಅಂಚೆ ಪಡೆಯಲು ಒಪ್ಪಿಕೊಂಡಿಲ್ಲ.',
'emailfrom'       => 'ಇಂದ',
'emailto'         => 'ಗೆ',
'emailsubject'    => 'ವಿಷಯ',
'emailmessage'    => 'ಸಂದೇಶ',
'emailsend'       => 'ಕಳುಹಿಸಿ',
'emailccme'       => 'ನನ್ನ ಸಂದೇಶದ ಪ್ರತಿಯೊಂದನ್ನು ನನಗೆ ಇ-ಅಂಚೆ ಮೂಲಕ ಕಳಿಸು.',
'emailsent'       => 'ಇ-ಅಂಚೆ ಕಳುಹಿಸಲಾಯಿತು',
'emailsenttext'   => 'ನಿಮ್ಮ ಇ-ಅಂಚೆ ಕಳುಹಿಸಲಾಗಿದೆ.',

# Watchlist
'watchlist'            => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿ',
'mywatchlist'          => 'ನನ್ನ ವೀಕ್ಷಣಾಪಟ್ಟಿ',
'watchlistfor'         => "('''$1''' ಅವರ)",
'nowatchlist'          => 'ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ ಯಾವುದೇ ಪುಟಗಳಿಲ್ಲ',
'watchnologin'         => 'ಲಾಗಿನ್ ಆಗಿಲ್ಲ',
'addedwatch'           => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿಗೆ ಸೇರಿಸಲಾಯಿತು',
'addedwatchtext'       => '"<nowiki>$1</nowiki>" ಪುಟವನ್ನು ನಿಮ್ಮ [[Special:Watchlist|ವೀಕ್ಷಣಾಪಟ್ಟಿಗೆ]] ಸೇರಿಸಲಾಗಿದೆ. ಈ ಪುಟದ ಮತ್ತು ಇದರ ಚರ್ಚಾ ಪುಟದ ಮುಂದಿನ ಬದಲಾವಣೆಗಳು ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಲ್ಲಿ ಕಾಣಸಿಗುತ್ತವೆ, ಮತ್ತು [[Special:Recentchanges|ಇತ್ತೀಚೆಗಿನ ಬದಲಾವಣೆಗಳ]] ಪಟ್ಟಿಯಲ್ಲಿ ಈ ಪುಟಗಳನ್ನು ದಪ್ಪಕ್ಷರಗಳಲ್ಲಿ ಕಾಣಿಸಲಾಗುವುದು.

<p>ಈ ಪುಟವನ್ನು ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಿಂದ ತೆಗೆಯಬಯಸಿದಲ್ಲಿ, ಮೇಲ್ಪಟ್ಟಿಯಲ್ಲಿ ಕಾಣಿಸಿರುವ "ವೀಕ್ಷಣಾ ಪುಟದಿಂದ ತೆಗೆ" ಅನ್ನು ಕ್ಲಿಕ್ಕಿಸಿ.',
'removedwatch'         => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಯಿಂದ ತೆಗೆಯಲಾಗಿದೆ',
'removedwatchtext'     => '"[[:$1]]" ಪುಟವನ್ನು ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಿಂದ ತೆಗೆಯಲಾಗಿದೆ.',
'watch'                => 'ವೀಕ್ಷಿಸಿ',
'watchthispage'        => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'unwatch'              => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಿಂದ ತೆಗೆ',
'unwatchthispage'      => 'ವೀಕ್ಷಣೆ ನಿಲ್ಲಿಸು',
'notvisiblerev'        => 'ಆವೃತ್ತಿಯನ್ನು ಅಳಿಸಲಾಗಿದೆ',
'watchlist-details'    => 'ಚರ್ಚೆ ಪುಟಗಳನ್ನು ಹೊರತುಪಡಿಸಿ {{PLURAL:$1|$1 ಪುಟ|$1 ಪುಟಗಳು}} ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿದೆ.',
'watchlistcontains'    => 'ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ $1 {{PLURAL:$1|ಪುಟ|ಪುಟಗಳು}} ಇವೆ.',
'wlshowlast'           => 'ಕೊನೆಯ $1 ಗಂಟೆ $2 ದಿನಗಳು $3 ಅನ್ನು ತೋರಿಸು',
'watchlist-show-bots'  => 'ಬಾಟ್ ಸಂಪಾದನೆಗಳನ್ನು ತೋರಿಸು',
'watchlist-hide-bots'  => 'ಬಾಟ್ ಸಂಪಾದನೆಗಳನ್ನು ಅಡಗಿಸು',
'watchlist-show-own'   => 'ನನ್ನ ಸಂಪಾದನೆಗಳನ್ನು ತೋರಿಸು',
'watchlist-hide-own'   => 'ನನ್ನ ಸಂಪಾದನೆಗಳನ್ನು ಅಡಗಿಸು',
'watchlist-show-minor' => 'ಚಿಕ್ಕಪುಟ್ಟ ಬದಲಾವಣೆಗಳನ್ನು ತೋರಿಸು',
'watchlist-hide-minor' => 'ಚಿಕ್ಕಪುಟ್ಟ ಬದಲಾವಣೆಗಳನ್ನು ಅಡಗಿಸು',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ವೀಕ್ಷಣೆಗೆ ಸೇರಿಸಲಾಗುತ್ತಿದೆ...',
'unwatching' => 'ವೀಕ್ಷಣೆಯಿಂದ ತೆಗೆಯಲಾಗುತ್ತಿದೆ...',

'enotif_reset'       => 'ಭೇಟಿಯಿತ್ತ ಎಲ್ಲಾ ಪುಟಗಳನ್ನು ಗುರುತು ಮಾಡಿ',
'enotif_newpagetext' => 'ಇದೊಂದು ಹೊಸ ಪುಟ.',
'changed'            => 'ಬದಲಾಯಿಸಲಾಗಿದೆ',
'created'            => 'ಸೃಷ್ಟಿಸಲ್ಪಟ್ಟಿದೆ',
'enotif_lastvisited' => 'ನಿಮ್ಮ ಕಳೆದ ಭೇಟಿಯ ನಂತರದ ಎಲ್ಲಾ ಬದಲಾವಣೆಗಳಿಗೆ $1 ನೋಡಿ.',
'enotif_anon_editor' => 'ಅನಾಮಧೇಯ ಸದಸ್ಯ $1',

# Delete/protect/revert
'deletepage'                  => 'ಪುಟವನ್ನು ಅಳಿಸಿ',
'confirm'                     => 'ಧೃಡಪಡಿಸು',
'exblank'                     => 'ಪುಟ ಖಾಲಿ ಇತ್ತು',
'delete-legend'               => 'ಅಳಿಸು',
'historywarning'              => 'ಎಚ್ಚರಿಕೆ: ನೀವು ಅಳಿಸಲು ಹೊರಟಿರುವ ಪುಟಕ್ಕೆ ಸಂಪಾದನೆಯ ಇತಿಹಾಸವಿದೆ:',
'confirmdeletetext'           => 'ಒಂದು ಪುಟವನ್ನು ಮತ್ತು ಅದರ ಸಂಪೂರ್ಣ ಇತಿಹಾಸವನ್ನು ನೀವು ಶಾಶ್ವತವಾಗಿ ಅಳಿಸಿಹಾಕುತ್ತಿರುವಿರಿ.
ಇದನ್ನು ನೀವು ಮಾಡಬಯಸುವಿರಿ, ಇದರ ಪರಿಣಾಮಗಳನ್ನು ಬಲ್ಲಿರಿ, ಮತ್ತು [[{{MediaWiki:Policy-url}}|ಕಾರ್ಯನೀತಿಗಳ]] ಅನುಸಾರ ಇದನ್ನು ಮಾಡುತ್ತಿದ್ದೀರಿ ಎಂದು ದೃಢಪಡಿಸಿ.',
'actioncomplete'              => 'ಕಾರ್ಯ ಸಂಪೂರ್ಣ',
'deletedtext'                 => '"<nowiki>$1</nowiki>" ಅನ್ನು ಅಳಿಸಲಾಯಿತು.
ಇತ್ತೀಚೆಗಿನ ಅಳಿಸುವಿಕೆಗಳ ಪಟ್ಟಿಗಾಗಿ $2 ಅನ್ನು ನೋಡಿ.',
'deletedarticle'              => '"$1" ಅಳಿಸಲಾಯಿತು',
'dellogpage'                  => 'ಅಳಿಸುವಿಕೆ ದಾಖಲೆ',
'deletionlog'                 => 'ಅಳಿಸುವಿಕೆ ದಿನಚರಿ',
'deletecomment'               => 'ಅಳಿಸುವುದರ ಕಾರಣ',
'deleteotherreason'           => 'ಇತರ/ಹೆಚ್ಚುವರಿ ಕಾರಣ:',
'deletereasonotherlist'       => 'ಇತರ ಕಾರಣ',
'deletereason-dropdown'       => '*ಸಾಮಾನ್ಯ ಅಳಿಸುವಿಕೆಯ ಕಾರಣಗಳು
** ಸಂಪಾದಕರ ಕೋರಿಕೆ
** ಕೃತಿಸ್ವಾಮ್ಯತೆಯ ಉಲ್ಲಂಘನೆ
** Vandalism',
'delete-edit-reasonlist'      => 'ಅಳಿಸುವಿಕೆ ಕಾರಣಗಳನ್ನು ಸಂಪಾದಿಸು',
'rollback_short'              => 'ತೊಡೆದುಹಾಕು',
'rollbacklink'                => 'ತೊಡೆದುಹಾಕು',
'protectlogpage'              => 'ಸಂರಕ್ಷಣೆ ದಿನಚರಿ',
'protectedarticle'            => '"[[$1]]" ಸಂರಕ್ಷಿಸಲಾಗಿದೆ.',
'modifiedarticleprotection'   => '"[[$1]]" ಪುಟದ ಸಂರಕ್ಷಣೆ ಮಟ್ಟವನ್ನು ಬದಲಾಯಿಸಲಾಯಿತು',
'unprotectedarticle'          => '"[[$1]]" ಪುಟದ ಸಂರಕ್ಷಣೆ ತೆಗೆಯಲಾಯಿತು',
'protect-title'               => '"$1" ಪುಟದ ಸಂರಕ್ಷಣೆ ಮಟ್ಟವನ್ನು ಬದಲಾಯಿಸು',
'protect-legend'              => 'ಸಂರಕ್ಷಣೆ ಧೃಡಪಡಿಸಿ',
'protectcomment'              => 'ಸಂರಕ್ಷಿಸಲು ಕಾರಣ:',
'protectexpiry'               => 'ಮುಕ್ತಾಯ:',
'protect_expiry_invalid'      => 'ಮುಕ್ತಾಯದ ಕಾಲ ಸಿಂಧುವಲ್ಲ.',
'protect_expiry_old'          => 'ಮುಕ್ತಾಯದ ಕಾಲ ಭೂತಕಾಲದಲ್ಲಿ ಇದೆ.',
'protect-unchain'             => 'ಸ್ಥಳಾತಂರಿಸುವ ಅನುಮತಿಗಳನ್ನು ತೆರೆ',
'protect-text'                => 'ನೀವು ಇಲ್ಲಿ <strong><nowiki>$1</nowiki></strong> ಪುಟದ ಸಂರಕ್ಷಣೆ ಮಟ್ಟವನ್ನು ವೀಕ್ಷಿಸಬಹುದು ಮತ್ತು ಬದಲಾಯಿಸಬಹುದು.',
'protect-locked-access'       => 'ನಿಮ್ಮ ಖಾತೆಗೆ ಪುಟ ಸಂರಕ್ಷಣ ಮಟ್ಟಗಳನ್ನು ಬದಲಾಯಿಸುವ ಅನುಮತಿ ಇಲ್ಲ. 
ಈ ಪುಟದ ಪ್ರಸಕ್ತ ವಸ್ತುಸ್ಥಿತಿ ಹೀಗಿದೆ: <strong>$1</strong>:',
'protect-cascadeon'           => 'ಈ ಕೆಳಗಿನ ತಡಸಲು ಸಂರಕ್ಷಣೆ (cascading protection) ಹೊಂದಿರುವ {{PLURAL:$1|ಪುಟದಲ್ಲಿ|ಪುಟಗಳಲ್ಲಿ}} ಸೇರ್ಪಡೆ ಆಗಿರುವುದರಿಂದ ಈ ಪುಟವೂ ಸಂರಕ್ಷಿತವಾಗಿದೆ.
ನೀವು ಈ ಪುಟದ ಸಂರಕ್ಷಣೆ ಮಟ್ಟವನ್ನು ಬದಲಾಯಿಸಬಹುದು, ಆದರೆ ಅದು ತಡಸಲು ಸಂರಕ್ಷಣೆಯನ್ನು ಬದಲಾಯಿಸುವುದಿಲ್ಲ.',
'protect-default'             => '(ಸಾಮಾನ್ಯ ಸ್ಥಿತಿ)',
'protect-fallback'            => '"$1" ಅನುಮತಿ ಬೇಕಾಗಿದೆ',
'protect-level-autoconfirmed' => 'ನೋಂದಾವಣೆ ಆಗಿಲ್ಲದ ಸದಸ್ಯರನ್ನು ತಡೆಹಿಡಿ',
'protect-level-sysop'         => 'ನಿರ್ವಾಹಕರು ಮಾತ್ರ',
'protect-summary-cascade'     => 'ತಡಸಲು (cascading)',
'protect-expiring'            => 'ಮುಕ್ತಾಯ $1 (UTC)',
'protect-cascade'             => 'ಈ ಪುಟದಲ್ಲಿ ಸೇರಿಸಲಾಗಿರುವ ಪುಟಗಳನ್ನು ಸಂರಕ್ಷಿಸು (ತಡಸಲು ಸಂರಕ್ಷಣೆ - cascading protection)',
'protect-cantedit'            => 'ನೀವು ಈ ಪುಟದ ಸಂರಕ್ಷಣೆ ಮಟ್ಟವನ್ನು ಬದಲಾಯಿಸುವ ಅನುಮತಿಯನ್ನು ಹೊಂದಿಲ್ಲ.',
'restriction-type'            => 'ಅನುಮತಿ:',
'restriction-level'           => 'ನಿರ್ಬಂಧನೆಯ ಮಟ್ಟ:',

# Restrictions (nouns)
'restriction-edit'   => 'ಸಂಪಾದನೆ',
'restriction-move'   => 'ಸ್ಥಳಾಂತರ',
'restriction-create' => 'ಸೃಷ್ಟಿ',

# Restriction levels
'restriction-level-sysop'         => 'ಪೂರ್ಣವಾಗಿ ಸಂರಕ್ಷಿತ',
'restriction-level-autoconfirmed' => 'ಅರೆ ಸಂರಕ್ಷಿತ',
'restriction-level-all'           => 'ಯಾವುದೇ ಮಟ್ಟ',

# Undelete
'undelete'               => 'ಅಳಿಸಲ್ಪಟ್ಟ ಪುಟಗಳನ್ನು ವೀಕ್ಷಿಸು',
'viewdeletedpage'        => 'ಅಳಿಸಲ್ಪಟ್ಟ ಪುಟಗಳನ್ನು ವೀಕ್ಷಿಸು',
'undeletebtn'            => 'ಹಿಂದೆ ಇದ್ದಂತೆ ಮಾಡು',
'undelete-search-box'    => 'ಅಳಿಸಲ್ಪಟ್ಟ ಪುಟಗಳನ್ನು ಹುಡುಕು',
'undelete-search-submit' => 'ಹುಡುಕು',

# Namespace form on various pages
'namespace'      => 'ಹೆಸರಿನ ಬಗೆ:',
'invert'         => 'ಆಯ್ಕೆಯನ್ನು ತಿರುಗಿಸು',
'blanknamespace' => '(ಮುಖ್ಯ)',

# Contributions
'contributions' => 'ಸದಸ್ಯರ ಕಾಣಿಕೆಗಳು',
'mycontris'     => 'ನನ್ನ ಕಾಣಿಕೆಗಳು',
'contribsub2'   => '$1 ($2) ಗೆ',
'uctop'         => ' (ಮೇಲಕ್ಕೆ)',
'month'         => 'ಈ ತಿಂಗಳಿಂದ (ಮತ್ತು ಮುಂಚಿನ):',
'year'          => 'ಈ ವರ್ಷದಿಂದ (ಮತ್ತು ಮುಂಚಿನ):',

'sp-contributions-newbies'     => 'ಹೊಸ ಖಾತೆಗಳ ಕಾಣಿಕೆಗಳನ್ನು ಮಾತ್ರ ತೋರಿಸು',
'sp-contributions-newbies-sub' => 'ಹೊಸ ಖಾತೆಗಳಿಗೆ',
'sp-contributions-blocklog'    => 'ತಡೆಹಿಡಿಯುವಿಕೆ ದಾಖಲೆ',
'sp-contributions-search'      => 'ಸಂಪಾದನೆಗಳನ್ನು ಹುಡುಕು',
'sp-contributions-submit'      => 'ಹುಡುಕು',

# What links here
'whatlinkshere'       => 'ಇಲ್ಲಿಗೆ ಯಾವ ಸಂಪರ್ಕ ಕೂಡುತ್ತದೆ',
'whatlinkshere-title' => '"$1" ಪುಟಕ್ಕೆ ಸಂಪರ್ಕ ಹೊಂದಿರುವ ಪುಟಗಳು',
'linklistsub'         => '(ಸಂಪರ್ಕಗಳ ಪಟ್ಟಿ)',
'linkshere'           => "'''[[:$1]]'''ಗೆ ಈ ಪುಟಗಳು ಸಂಪರ್ಕ ಹೊಂದಿವೆ:",
'nolinkshere'         => "'''[[:$1]]''' ಗೆ ಯಾವ ಪುಟಗಳೂ ಸಂಪರ್ಕ ಹೊಂದಿಲ್ಲ.",
'isredirect'          => 'ಪುನರ್ನಿರ್ದೇಶನ ಪುಟ',
'istemplate'          => 'ಸೇರ್ಪಡೆ',
'whatlinkshere-prev'  => '{{PLURAL:$1|ಹಿಂದಿನ|ಹಿಂದಿನ $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ಮುಂದಿನ|ಮುಂದಿನ $1}}',
'whatlinkshere-links' => '← ಕೊಂಡಿಗಳು',

# Block/unblock
'blockip'            => 'ಈ ಸದಸ್ಯನನ್ನು ತಡೆ ಹಿಡಿಯಿರಿ',
'ipbreason'          => 'ಕಾರಣ',
'ipbreasonotherlist' => 'ಇತರ ಕಾರಣ',
'ipbemailban'        => 'ಬಳಕೆದಾರನು ಇ-ಅಂಚೆ ಕಳುಹಿಸುವುದನ್ನು ತಡೆಗಟ್ಟು',
'ipbsubmit'          => 'ಈ ಸದಸ್ಯರನ್ನು ತಡೆಹಿಡಿಯಿರಿ',
'ipboptions'         => '೨ ಗಂಟೆಗಳು:2 hours,೧ ದಿನ:1 day,೩ ದಿನಗಳು:3 days,೧ ವಾರ:1 week,೨ ವಾರಗಳು:2 weeks,೧ ತಿಂಗಳು:1 month,೩ ತಿಂಗಳುಗಳು:3 months,೬ ತಿಂಗಳುಗಳು:6 months,೧ ವರ್ಷ:1 year,ಅನಿರ್ಧಿಷ್ಟ:infinite', # display1:time1,display2:time2,...
'ipbotheroption'     => 'ಇತರ',
'blockipsuccesssub'  => 'ತಡೆಹಿಡಿಯುವಿಕೆ ಯಶಸ್ವಿಯಾಯಿತು.',
'blockipsuccesstext' => '"$1" ಐಪಿ ಸಂಖ್ಯೆಯನ್ನು ತಡೆ ಹಿಡಿಯಲಾಗಿದೆ. <br /> ತಡೆಗಳನ್ನು ಪರಿಶೀಲಿಸಲು [[Special:Ipblocklist|ತಡೆ ಹಿಡಿಯಲಾಗಿರುವ ಐಪಿ ಸಂಖ್ಯೆಗಳ ಪಟ್ಟಿ]] ನೋಡಿ.',
'ipblocklist'        => 'ಬ್ಲಾಕ್ ಮಾಡಲಾದ ಐಪಿ ವಿಳಾಸಗಳ ಹಾಗೂ ಬಳಕೆಯ ಹೆಸರುಗಳ ಪಟ್ಟಿ',
'ipblocklist-submit' => 'ಹುಡುಕು',
'infiniteblock'      => 'ಅನಂತ',
'blocklink'          => 'ತಡೆ ಹಿಡಿಯಿರಿ',
'unblocklink'        => 'ತಡೆಯನ್ನು ತಗೆ',
'contribslink'       => 'ಕಾಣಿಕೆಗಳು',
'blocklogpage'       => 'ತಡೆಹಿಡಿದ ಸದಸ್ಯರ ದಿನಚರಿ',
'blocklogentry'      => '"$1" ಅನ್ನು $2 ರ ಸಮಯದವರೆಗೆ ತಡೆಹಿಡಿಯಲಾಗಿದೆ',

# Move page
'move-page-legend' => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',
'movepagetext'     => "ಈ ಕೆಳಗಿನ ಫಾರ್ಮನ್ನು ಉಪಯೋಗಿಸಿದಲ್ಲಿ ಪುಟವನ್ನು ಪುನರ್ನಾಮಕರಣ ಮಾಡಲಾಗುವುದು ಮತ್ತು ಅದರ ಸಂಪಾದನೆಯ ಇತಿಹಾಸವನ್ನು ಹೊಸ ಹೆಸರಿಗೆ ವರ್ಗಾಯಿಸಲಾಗುವುದು.
ಹಳೆ ಶೀರ್ಷಿಕೆ ಹೊಸ ಶೀರ್ಷಿಕೆಗೆ ಪುನರ್ನಿರ್ದೇಶಿಸಲಾಗುವುದು.
ಹಳೆ ಶೀರ್ಷಿಕೆಗೆ ಇರುವ ಸಂಪರ್ಕಗಳನ್ನು ಬದಲಾಯಿಸಲಾಗುವುದಿಲ್ಲ;
ದಯವಿಟ್ಟು ಹಳೆ ಶೀರ್ಷಿಕೆಗೆ ಪುನರ್ನಿರ್ದೇಶಿತವಾಗಿದ್ದ ಪುಟಗಳನ್ನು ಕೈಯಾರೆ ಹೊಸ ಶೀರ್ಷಿಕೆಗೆ ಬದಲಾಯಿಸಿ.
ಇತರ ಸಂಪರ್ಕಗಳು ಯಾವೂ ಮುರಿದಿಲ್ಲವೆಂದು ಖಚಿತಪಡಿಸುವುದು ನಿಮ್ಮ ಜವಾಬ್ದಾರಿ.

ಗಮನಿಸಿ: ನೀವು ಸೂಚಿಸಿರುವ ಹೊಸ ಶೀರ್ಷಿಕೆಯಡಿಯಲ್ಲಿ ಆಗಲೇ ಪುಟವೊಂದು ಇದ್ದಲ್ಲಿ ಈ ಸ್ಥಳಾಂತರವನ್ನು ಮಾಡಲು ಸಾಮಾನ್ಯವಾಗಿ ''ಆಗುವುದಿಲ್ಲ'' - ಕೇವಲ ಆ ಪುಟ ಖಾಲಿ ಇದ್ದರೆ ಮತ್ತು ಯಾವುದೂ ಸಂಪಾದನೆ ಇತಿಹಾಸವು ಇಲ್ಲದಿದ್ದರೆ ಮಾತ್ರ ಸಾಧ್ಯ.

'''ಎಚ್ಚರಿಕೆ!'''
ಅನೇಕ ಸಂಪರ್ಕಗಳಿರುವ ಪುಟವನ್ನು ನೀವು ಸ್ಥಳಾಂತರಿಸುತ್ತಿರುವುದೇ ಆದರೆ ಇದೊಂದು ದೊಡ್ಡ ಹಾಗು ಅನಿರೀಕ್ಷಿತ ಬದಲಾವಣೆಯಾಗಬಹುದು;
ದಯವಿಟ್ಟು ನೀವು ಮಾಡುತ್ತಿರುವ ಕ್ರಮದ ಪರಿಣಾಮಗಳನ್ನು ಅರಿತಿರುವಿರೆಂದು ಖಾತ್ರಿ ಪಡಿಸಿಕೊಂಡ ನಂತರ ಮುನ್ನಡೆಯಿರಿ.",
'movepagetalktext' => "ಜೊತೆಗಿನ ಚರ್ಚೆ ಪುಟವೂ ಸ್ಥಳಾಂತರಿಸಲಾಗುವುದು. ಈ ಸ್ಥಳಾಂತರ '''ಆಗದಿರುವ''' ಪ್ರಸಂಗಗಳು:
*ಸ್ಥಳಾಂತರಿಕೆಯ ಹೆಸರಿನಲ್ಲಿ ಆಗಲೇ ಖಾಲಿಯಲ್ಲದ ಒಂದು ಪುಟವು ಇದ್ದಲ್ಲಿ, ಅಥವ
*ಕೆಳಗಿನ ಚೌಕದಲ್ಲಿರುವ tick mark ಅನ್ನು ನೀವು ತಗೆದಲ್ಲಿ. 

ಈ ಪ್ರಸಂಗಗಳಲ್ಲಿ ನೀವು ಸ್ವತಃ ಚರ್ಚೆ ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಬೇಕು ಅಥವ ಒಂದುಗೂಡಿಸಬೇಕು.",
'movearticle'      => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',
'movenologin'      => 'ಲಾಗಿನ್ ಆಗಿಲ್ಲ',
'movenologintext'  => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಲು ನೀವು ನೋಂದಾಯಿತ ಸದಸ್ಯರಾಗಿದ್ದು [[Special:Userlogin|ಲಾಗಿನ್]] ಆಗಿರಬೇಕು.',
'newtitle'         => 'ಈ ಹೊಸ ಶೀರ್ಷಿಕೆಗೆ:',
'move-watch'       => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸು',
'movepagebtn'      => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',
'pagemovedsub'     => 'ಸ್ಥಳಾಂತರಿಸುವಿಕೆ ಯಶಸ್ವಿಯಾಯಿತು',
'movepage-moved'   => '<big>\'\'\'"$1" ಪುಟವನ್ನು "$2" ಹೆಸರಿಗೆ ಸ್ಥಳಾಂತರಿಸಲಾಯಿತು\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'ಆ ಹೆಸರಿನಲ್ಲಿ ಒಂದು ಪುಟ ಆಗಲೇ ಅಸ್ಥಿತ್ವದಲ್ಲಿದೆ ಅಥವ ನೀವು ಆಯ್ಕೆ ಮಾಡಿರುವ ಹೆಸರು ಇತರ ಕಾರಣಗಳಿಗೆ ಸ್ವೀಕಾರಾರ್ಹವಾಗಿಲ್ಲ.
ದಯವಿಟ್ಟು ಬೇರೆ ಹೆಸರನ್ನು ಆಯ್ಕೆ ಮಾಡಿ.',
'talkexists'       => "'''ಪುಟವು ಯಶಸ್ವಿಯಾಗಿ ಸ್ಥಳಾಂತರಿಸಲ್ಪಟ್ಟಿತು, ಆದರೆ ಹೊಸದಾಗಿ ಸ್ಥಳಾಂತರಿಸಲ್ಪಟ್ಟ ಜಾಗದಲ್ಲಿ ಆಗಲೇ ಒಂದು ಚರ್ಚೆ ಪುಟ ಇರುವುದರಿಂದ ಈ ಪುಟದ ಚರ್ಚೆ ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಲಾಗಲಿಲ್ಲ.
ದಯವಿಟ್ಟು ಕೈಯಾರೆ ಈ ಎರಡು ಪುಟಗಳನ್ನು ಒಂದಾಗಿಸಿ.'''",
'movedto'          => 'ಸ್ಥಳಾಂತರಿಸಲ್ಪಟ್ಟ ನೆಲೆ',
'movetalk'         => 'ಜೊತೆಗಿನ ಚರ್ಚೆ ಪುಟವನ್ನೂ ಸ್ಥಳಾಂತರಿಸು',
'talkpagemoved'    => 'ಜೊತೆಗಿನ ಚರ್ಚೆ ಪುಟವನ್ನೂ ಸ್ಥಳಾಂತರಿಸಲಾಯಿತು.',
'talkpagenotmoved' => 'ಜೊತೆಗಿನ ಚರ್ಚೆ ಪುಟವನ್ನು ಸ್ಥಳಾಂತರ <strong>ಮಾಡಲಾಗಲಿಲ್ಲ</strong>.',
'1movedto2'        => '[[$1]] - [[$2]] ಪುಟಕ್ಕೆ ಸ್ಥಳಾಂತರಿಸಲಾಗಿದೆ',
'1movedto2_redir'  => '[[$1]] - [[$2]] ಪುಟ ರಿಡೈರೆಕ್ಟ್ ಮೂಲಕ ಸ್ಥಳಾಂತರಿಸಲಾಗಿದೆ',
'movelogpage'      => 'ಸ್ಥಳಾಂತರಿಕೆ ದಾಖಲೆ',
'movereason'       => 'ಕಾರಣ',
'revertmove'       => 'ಹಿಂದಿನಂತಾಗಿಸು',

# Export
'export'          => 'ಪುಟಗಳನ್ನು ರಫ್ತು ಮಾಡಿ',
'export-addcat'   => 'ಸೇರಿಸು',
'export-download' => 'ಫೈಲಾಗಿ ಉಳಿಸು',

# Namespace 8 related
'allmessages'         => 'ಸಂಪರ್ಕ ಸಾಧನದ ಎಲ್ಲ ಸಂದೇಶಗಳು',
'allmessagesname'     => 'ಹೆಸರು',
'allmessagesmodified' => 'ಬದಲಾವಣೆ ಮಾಡಿದ್ದನ್ನು ಮಾತ್ರ ತೋರಿಸು',

# Thumbnails
'thumbnail-more'  => 'ದೊಡ್ಡದಾಗಿಸು',
'thumbnail_error' => 'ಮುನ್ನೋಟ ಚಿತ್ರವನ್ನು ಸೃಷ್ಟಿಸುವಲ್ಲಿ ದೋಷ: $1',

# Special:Import
'import'             => 'ಪುಟಗಳನ್ನು ಅಮದು ಮಾಡಿ',
'importfailed'       => 'ಆಮದು ಯಶಸ್ವಿಯಾಗಲಿಲ್ಲ: $1',
'importbadinterwiki' => 'ಇಂಟರ್‍ವಿಕಿ ಲಿಂಕ್ ಸರಿಯಾಗಿಲ್ಲ',
'importnotext'       => 'ಖಾಲಿ ಅಥವಾ ಯಾವುದೇ ಶಬ್ಧಗಳಿಲ್ಲ',
'importsuccess'      => 'ಆಮದು ಯಶಸ್ವಿಯಾಯಿತು!',

# Import log
'importlogpage' => 'ಆಮದುಗಳ ದಾಖಲೆ',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ನನ್ನ ಸದಸ್ಯ ಪುಟ',
'tooltip-pt-mytalk'               => 'ನನ್ನ ಚರ್ಚೆ ಪುಟ',
'tooltip-pt-preferences'          => 'ನನ್ನ ಆಯ್ಕೆಗಳು',
'tooltip-pt-watchlist'            => 'ನೀವು ಬದಲಾವಣೆಗಳ ಮೇಲೆ ನಿಗಾ ವಹಿಸುತ್ತಿರುವ ಪುಟಗಳ ಪಟ್ಟಿ',
'tooltip-pt-mycontris'            => 'ನನ್ನ ಕಾಣಿಕೆಗಳ ಪಟ್ಟಿ',
'tooltip-pt-login'                => 'ನೀವು ಲಾಗ್ ಇನ್ ಆಗಬೇಕೆಂದು ಕೋರುತ್ತೇವೆ, ಆದರೆ ಅದು ಖಡ್ಡಾಯ ಏನು ಅಲ್ಲ.',
'tooltip-pt-logout'               => 'ಲಾಗ್ ಔಟ್',
'tooltip-ca-talk'                 => 'ಮಾಹಿತಿ ಪುಟದ ಬಗ್ಗೆ ಚರ್ಚೆ',
'tooltip-ca-edit'                 => 'ಈ ಪುಟವನ್ನು ನೀವು ಸಂಪಾದಿಸಬಹುದು. ಉಳಿಸುವ ಮುನ್ನ ಮುನ್ನೋಟವನ್ನು ಉಪಯೋಗಿಸಿ.',
'tooltip-ca-addsection'           => 'ಈ ಚರ್ಚೆಗೆ ನಿಮ್ಮ ಅಭಿಪ್ರಾಯವನ್ನು ಸೇರಿಸಿ.',
'tooltip-ca-viewsource'           => 'ಈ ಪುಟ ಸಂರಕ್ಷಿತವಾಗಿದೆ. ಅದರ ಮೂಲವನ್ನು ನೀವು ವೀಕ್ಷಿಸಬಹುದು.',
'tooltip-ca-protect'              => 'ಈ ಪುಟವನ್ನು ಸಂರಕ್ಷಿಸು',
'tooltip-ca-delete'               => 'ಈ ಪುಟವನ್ನು ಅಳಿಸು',
'tooltip-ca-move'                 => 'ಈ ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸು',
'tooltip-ca-watch'                => 'ಈ ಪುಟವನ್ನು ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಗೆ ಸೇರಿಸಿ',
'tooltip-ca-unwatch'              => 'ಈ ಪುಟವನ್ನು ನಿಮ್ಮ ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಿಂದ ತೆಗೆ',
'tooltip-search'                  => '{{SITENAME}} ಅನ್ನು ಹುಡುಕಿ',
'tooltip-p-logo'                  => 'ಮುಖ್ಯ ಪುಟ',
'tooltip-n-mainpage'              => 'ಮುಖ್ಯ ಪುಟ ನೋಡಿ',
'tooltip-n-portal'                => 'ಯೋಜನೆಯ ಬಗ್ಗೆ, ನೀವು ಏನು ಮಾಡಬಹುದು, ಎಲ್ಲಿ ಇದರ ಬಗ್ಗೆ ತಿಳಿದುಕೊಳ್ಳಬಹುದು',
'tooltip-n-currentevents'         => 'ಪ್ರಸಕ್ತ ಆಗುಹೋಗುಗಳ ಬಗ್ಗೆ ಹಿನ್ನಲೆ ಮಾಹಿತಿ ಪಡೆಯಿರಿ',
'tooltip-n-recentchanges'         => 'ವಿಕಿಯಲ್ಲಿನ ಇತ್ತೀಚಿನ ಬದಲಾವಣೆಗಳ ಪಟ್ಟಿ.',
'tooltip-n-randompage'            => 'ಯದೃಚ್ಛಿಕ ಪುಟವೊಂದನ್ನು ತೋರಿಸು',
'tooltip-n-help'                  => 'ಇದರ ಬಗ್ಗೆ ತಿಳಿದುಕೊಳ್ಳಲು ಜಾಗ.',
'tooltip-n-sitesupport'           => 'ನಮ್ಮನ್ನು ಬೆಂಬಲಿಸಿ',
'tooltip-t-whatlinkshere'         => 'ಇಲ್ಲಿಗೆ ಸಂಪರ್ಕ ಹೊಂದಿರುವ ಎಲ್ಲಾ ವಿಕಿ ಪುಟಗಳ ಪಟ್ಟಿ',
'tooltip-t-recentchangeslinked'   => 'ಈ ಪುಟದಿಂದ ಸಂಪರ್ಕ ಹೊಂದಿರುವ ಪುಟಗಳಲ್ಲಿನ ಇತ್ತೀಚಿನ ಬದಲಾವಣೆಗಳು',
'tooltip-feed-rss'                => 'ಈ ಪುಟಕ್ಕೆ RSS ಫೀಡು',
'tooltip-feed-atom'               => 'ಈ ಪುಟಕ್ಕೆ Atom ಫೀಡು',
'tooltip-t-contributions'         => 'ಈ ಸದಸ್ಯರ ಕಾಣಿಕೆಗಳ ಪಟ್ಟಿಯನ್ನು ತೋರಿಸು',
'tooltip-t-emailuser'             => 'ಈ ಸದಸ್ಯರಿಗೆ ಇ-ಅಂಚೆಯನ್ನು ಕಳುಹಿಸು',
'tooltip-t-upload'                => 'ಫೈಲನ್ನು ಅಪ್ಲೋಡ್ ಮಾಡಿ',
'tooltip-t-specialpages'          => 'ಎಲ್ಲಾ ವಿಶೇಷ ಪುಟಗಳ ಪಟ್ಟಿ',
'tooltip-ca-nstab-user'           => 'ಸದಸ್ಯರ ಪುಟವನ್ನು ವೀಕ್ಷಿಸು',
'tooltip-ca-nstab-project'        => 'ಯೋಜನೆಯ ಪುಟವನ್ನು ನೋಡಿ',
'tooltip-ca-nstab-image'          => 'ಕಡತದ ಪುಟ ವೀಕ್ಷಿಸಿ',
'tooltip-ca-nstab-template'       => 'ಟೆಂಪ್ಲೇಟನ್ನು ವೀಕ್ಷಿಸು',
'tooltip-ca-nstab-help'           => 'ಸಹಾಯ ಪುಟವನ್ನು ವೀಕ್ಷಿಸು',
'tooltip-ca-nstab-category'       => 'ವರ್ಗದ ಪುಟವನ್ನು ನೋಡಿ',
'tooltip-minoredit'               => 'ಇದನ್ನು ಚುಟುಕಾದ ಬದಲಾವಣೆಯೆಂದು ಗುರುತಿಸು',
'tooltip-save'                    => 'ನಿಮ್ಮ ಬದಲಾವಣೆಗಳನ್ನು ಉಳಿಸಿ',
'tooltip-preview'                 => 'ನೀವು ಮಾಡಿದ ಬದಲಾವಣೆಗಳ ಮುನ್ನೋಟ - ದಯವಿಟ್ಟು ಪುಟ ಉಳಿಸುವ ಮುನ್ನ ಇದನ್ನೊಮ್ಮೆ ನೋಡಿ!',
'tooltip-diff'                    => 'ನೀವು ಮಾಡಿದ ಬದಲಾವಣೆಗಳನ್ನು ತೋರುತ್ತದೆ.',
'tooltip-compareselectedversions' => 'ಆರಿಸಿದ ಎರಡು ಆವೃತ್ತಿಗಳ ಮಧ್ಯದ ವ್ಯತ್ಯಾಸಗಳನ್ನು ನೋಡು.',
'tooltip-watch'                   => 'ಈ ಪುಟವನ್ನು ನಿಮ್ಮ ವೀಕ್ಷಣಾ ಪಟ್ಟಿಗೆ ಸೇರಿಸು',
'tooltip-upload'                  => 'ಅಪ್ಲೋಡ್ ಅನ್ನು ಪ್ರಾರಂಭಿಸು',

# Attribution
'anonymous'     => '{{SITENAME}} : ಅನಾಮಧೇಯ ಬಳಕೆದಾರ(ರು)',
'othercontribs' => '$1 ರ ಕೆಲಸವನ್ನು ಆಧರಿಸಿ.',
'siteusers'     => '{{SITENAME}} ಸದಸ್ಯ(ರು) $1',
'creditspage'   => 'ಪುಟದ ಗೌರವಗಳು',

# Info page
'infosubtitle' => 'ಪುಟದ ಬಗ್ಗೆ ಮಾಹಿತಿ',
'numedits'     => 'ಸಂಪಾದನೆಗಳ ಸಂಖ್ಯೆ (ಪುಟ): $1',
'numtalkedits' => 'ಸಂಪಾದನೆಗಳ ಸಂಖ್ಯೆ (ಚರ್ಚೆ ಪುಟ): $1',
'numwatchers'  => 'ವೀಕ್ಷಿಸುತ್ತಿರುವವರ ಸಂಖ್ಯೆ: $1',

# Image deletion
'deletedrevision'       => 'ಹಳೆ ಆವೃತ್ತಿ $1 ಅನ್ನು ಅಳಿಸಲಾಗಿದೆ',
'filedeleteerror-short' => 'ಈ ಫೈಲನ್ನು ಅಳಿಸುವುದರಲ್ಲಿ ದೋಷ: $1',
'filedelete-missing'    => '"$1" ಫೈಲು ಅಸ್ಥಿತ್ವದಲ್ಲಿ ಇಲ್ಲದಿರುವುದರಿಂದ ಅದನ್ನು ಅಳಿಸಲು ಬರುವುದಿಲ್ಲ.',

# Browsing diffs
'previousdiff' => '← ಹಿಂದಿನ ವ್ಯತ್ಯಾಸ',
'nextdiff'     => 'ಮುಂದಿನ ವ್ಯತ್ಯಾಸ',

# Media information
'file-info-size'       => '($1 × $2 ಚಿತ್ರಬಿಂದು, ಫೈಲಿನ ಗಾತ್ರ: $3, MIME ಪ್ರಕಾರ: $4)',
'file-nohires'         => '<small>ಇದಕ್ಕಿಂತ ಹೆಚ್ಚಿನ ವಿವರವಾದ ನೋಟ ಇಲ್ಲ.</small>',
'svg-long-desc'        => '(SVG ಫೈಲು, ಸುಮಾರಾಗಿ $1 × $2 ಚಿತ್ರಬಿಂದುಗಳು, ಫೈಲಿನ ಗಾತ್ರ: $3)',
'show-big-image'       => 'ಅತಿ ಹೆಚ್ಚು ವಿವರವಾದ ನೋಟ',
'show-big-image-thumb' => '<small>ಈ ಮುನ್ನೋಟದ ಅಳತೆ: $1 × $2 ಚಿತ್ರಬಿಂದುಗಳು</small>',

# Special:Newimages
'newimages' => 'ಹೊಸ ಫೈಲುಗಳ ಪ್ರದರ್ಶನ',
'noimages'  => 'ನೋಡಲು ಏನೂ ಇಲ್ಲ.',
'ilsubmit'  => 'ಹುಡುಕು',
'bydate'    => 'ದಿನಾಂಕಕ್ಕನುಗುಣವಾಗಿ',

# Bad image list
'bad_image_list' => 'ವ್ಯವಸ್ಥೆಯ ಆಕಾರ ಈ ರೀತಿ:

ಪಟ್ಟಿಯಲ್ಲಿರುವ ದಾಖಲೆಗಳನ್ನು (* ಇಂದ ಪ್ರಾರಂಭವಾಗುವ ಸಾಲುಗಳು) ಮಾತ್ರ ಪರಿಗಣಿಸಲಾಗುತ್ತದೆ.
ಪ್ರತಿ ಸಾಲಿನ ಮೊದಲ ಕೊಂಡಿಯು ಒಂದು ದೋಷಯುಕ್ತ ಫೈಲಿಗೆ ಕೊಂಡಿಯಾಗಿರಬೇಕು.
ಅದೇ ಸಾಲಿನ ಮುಂದಿನ ಎಲ್ಲಾ ಕೊಂಡಿಗಳನ್ನು ಪರಿಗಣನೆಯಿಂದ ವಿನಾಯತಿ ಮಾಡಲಾಗುತ್ತದೆ, ಅಂದರೆ ಎಲ್ಲಿ ಪುಟಗಳ ಒಳಗೆ ಫೈಲು ಇರುತ್ತದೆಯೊ ಅಲ್ಲಿ.',

# Metadata
'metadata'          => 'ಮೇಲ್ದರ್ಜೆ ಮಾಹಿತಿ',
'metadata-help'     => 'ಈ ಫೈಲಿನಲ್ಲಿ ಹೆಚ್ಚಿನ ಮಾಹಿತಿ ಇದೆ. ಪ್ರಾಯಶಃ ಫೈಲನ್ನು ಸೃಷ್ಟಿಸಲು ಉಪಯೋಗಿಸಲಾದ ಡಿಜಿಟಲ್ ಕ್ಯಾಮೆರದಿಂದ ಅಥವ ಸ್ಕ್ಯಾನರ್ ಇಂದ ಈ ಮಾಹಿತಿ ಸೇರಿಸಲ್ಪಟ್ಟಿದೆ.
ಮೂಲಪ್ರತಿಯಿಂದ ಈ ಫೈಲು ಮಾರ್ಪಾಟಾಗಿದ್ದಲ್ಲಿ, ಈ ಮಾಹಿತಿ ಮಾರ್ಪಟ್ಟ ಫೈಲಿನ ವಿವರಗಳಿಗೆ ಸರಿಯಾಗಿ ಹೊಂದದೆ ಇರಬಹುದು.',
'metadata-expand'   => 'ವಿಸ್ತಾರವಾದ ವಿವರಗಳನ್ನು ತೋರಿಸು',
'metadata-collapse' => 'ವಿಸ್ತಾರವಾದ ವಿವರಗಳನ್ನು ಅಡಗಿಸು',
'metadata-fields'   => 'ಈ ಸಂದೇಶದಲ್ಲಿ ಪಟ್ಟಿ ಮಾಡಲಾಗಿರುವ EXIF ಮೇಲ್ದರ್ಜೆ ಮಾಹಿತಿ ಚಿತ್ರ ಪುಟದಲ್ಲಿ ಸೇರಿಸಲಾಗುತ್ತದೆ. ಪುಟದಲ್ಲಿ ಮೇಲ್ದರ್ಜೆ ಮಾಹಿತಿ ಪಟ್ಟಿಯನ್ನು ತೆರೆದಾಗ ಇವು ಕಾಣುತ್ತದೆ.
ಉಳಿದವುಗಳು ಮೂಲಸ್ಥಿತಿಯಲ್ಲಿ ಅಗೋಚರವಾಗಿರುತ್ತವೆ.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'       => 'ಅಗಲ',
'exif-imagelength'      => 'ಎತ್ತರ',
'exif-imagedescription' => 'ಚಿತ್ರದ ಶೀರ್ಷಿಕೆ',
'exif-copyright'        => 'ಕೃತಿಸ್ವಾಮ್ಯತೆಯನ್ನು ಹೊಂದಿರುವವರು',

# External editor support
'edit-externally'      => 'ಬಾಹ್ಯ ತಂತ್ರಾಂಶವನ್ನು ಉಪಯೋಗಿಸಿ ಇದನ್ನು ಸಂಪಾದಿಸಿ',
'edit-externally-help' => 'ಹೆಚ್ಚಿನ ಮಾಹಿತಿಗೆ [http://meta.wikimedia.org/wiki/Help:External_editors ಸ್ಥಾಪನೆಯ ನಿರ್ದೇಶಗಳನ್ನು] ನೋಡಿ.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ಎಲ್ಲಾ',
'imagelistall'     => 'ಎಲ್ಲಾ',
'watchlistall2'    => 'ಎಲ್ಲಾ',
'namespacesall'    => 'ಎಲ್ಲಾ',
'monthsall'        => 'ಎಲ್ಲಾ',

# E-mail address confirmation
'confirmemail'          => 'ಇ-ಅಂಚೆ ವಿಳಾಸವನ್ನು ಖಾತ್ರಿ ಮಾಡಿ',
'confirmemail_success'  => 'ನಿಮ್ಮ ಇ-ಅಂಚೆ ವಿಳಾಸ ಖಾತ್ರಿಗೊಂಡಿದೆ. ಈಗ ನೀವು ಲಾಗಿನ್ ಆಗಿ ವಿಕಿಯನ್ನು ಉಪಯೋಗಿಸಬಹುದು.',
'confirmemail_loggedin' => 'ನಿಮ್ಮ ಇ-ಅಂಚೆ ವಿಳಾಸ ಈಗ ಖಾತ್ರಿಗೊಂಡಿದೆ.',

# Trackbacks
'trackbackremove' => ' ([$1 ಅಳಿಸು])',

# Delete conflict
'deletedwhileediting' => 'ಸೂಚನೆ: ನೀವು ಸಂಪಾದನೆ ಪ್ರಾರಂಭಿಸಿದ ನಂತರ ಈ ಪುಟವನ್ನು ಅಳಿಸಲಾಗಿದೆ!',
'recreate'            => 'ಪುನಃ ಸೃಷ್ಟಿಸು',

# HTML dump
'redirectingto' => '[[$1]] ಪುಟಕ್ಕೆ ಪುನರ್ನಿರ್ದೇಶಿತವಾಗುತ್ತಿದೆ...',

# Multipage image navigation
'imgmultipageprev' => '← ಹಿಂದಿನ ಪುಟ',
'imgmultipagenext' => 'ಮುಂದಿನ ಪುಟ →',

# Table pager
'table_pager_next'  => 'ಮುಂದಿನ ಪುಟ',
'table_pager_prev'  => 'ಹಿಂದಿನ ಪುಟ',
'table_pager_first' => 'ಮೊದಲ ಪುಟ',
'table_pager_last'  => 'ಕೊನೆಯ ಪುಟ',

# Auto-summaries
'autosumm-blank' => 'ಪುಟದಲ್ಲಿರುವ ಎಲ್ಲಾ ಮಾಹಿತಿಯನ್ನೂ ತಗೆಯುತ್ತಿರುವೆ',
'autosumm-new'   => 'ಹೊಸ ಪುಟ: $1',

# Watchlist editor
'watchlistedit-numitems'       => 'ಚರ್ಚೆ ಪುಟಗಳನ್ನು ಹೊರತುಪಡಿಸಿ, ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ {{PLURAL:$1|೧ ಶೀರ್ಷಿಕೆ ಇದೆ|$1 ಶೀರ್ಷಿಕೆಗಳು ಇವೆ}}.',
'watchlistedit-noitems'        => 'ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ ಯಾವುದೂ ಪುಟಗಳಿಲ್ಲ.',
'watchlistedit-normal-title'   => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಯನ್ನು ಸಂಪಾದಿಸು',
'watchlistedit-normal-legend'  => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಯಿಂದ ಶೀರ್ಷಿಕೆಗಳನ್ನು ತೆಗೆ',
'watchlistedit-normal-explain' => 'ನಿಮ್ಮ ವೀಕ್ಷಣಾಪಟ್ಟಿಯಲ್ಲಿ ಇರುವ ಶೀರ್ಷಿಕೆಗಳನ್ನು ಕೆಳಗೆ ತೋರಿಸಲಾಗಿದೆ.
ಯಾವುದೇ ಶೀರ್ಷಿಕೆಯನ್ನು ತಗೆಯಲು, ಅದರ ಪಕ್ಕದಲ್ಲಿರುವ ಚೌಕವನ್ನು ಗುರುತು ಮಾಡಿ ಮತ್ತು "ಶೀರ್ಷಿಕೆಗಳನ್ನು ತೆಗೆ" ಗುಂಡಿಯನ್ನು ಒತ್ತಿ.
ನೀವು ವೀಕ್ಷಣಾಪಟ್ಟಿಯನ್ನು [[Special:Watchlist/raw|ನೇರವಾಗಿ ಸಂಪಾದಿಸಬಹುದು]] ಕೂಡ.',
'watchlistedit-normal-submit'  => 'ಶೀರ್ಷಿಕೆಗಳನ್ನು ತೆಗೆ',
'watchlistedit-raw-title'      => 'ಮೂಲ ವೀಕ್ಷಣಾಪಟ್ಟಿಯನ್ನು ಸಂಪಾದಿಸು',
'watchlistedit-raw-legend'     => 'ಮೂಲ ವೀಕ್ಷಣಾಪಟ್ಟಿಯನ್ನು ಸಂಪಾದಿಸು',
'watchlistedit-raw-titles'     => 'ಶೀರ್ಷಿಕೆಗಳು:',

# Watchlist editing tools
'watchlisttools-view' => 'ಸೂಚಿಸಲ್ಪಟ್ಟ ಬದಲಾವಣೆಗಳನ್ನು ವೀಕ್ಷಿಸಿ',
'watchlisttools-edit' => 'ವೀಕ್ಷಣಾಪಟ್ಟಿಯನ್ನು ನೋಡು ಮತ್ತು ಸಂಪಾದಿಸು',
'watchlisttools-raw'  => 'ಮೂಲ ವೀಕ್ಷಣಾಪಟ್ಟಿಯನ್ನು ಸಂಪಾದಿಸು',

# Special:Version
'version'                  => 'ಆವೃತ್ತಿ', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'ವಿಶೇಷ ಪುಟಗಳು',
'version-other'            => 'ಇತರ',
'version-version'          => 'ಆವೃತ್ತಿ',
'version-software'         => 'ಸಂಸ್ಥಾಪಿಸಲಾಗಿರುವ ತಂತ್ರಾಂಶ',
'version-software-version' => 'ಆವೃತ್ತಿ',

# Special:Filepath
'filepath'      => 'ಫೈಲಿನ ಮಾರ್ಗ',
'filepath-page' => 'ಫೈಲು:',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'ದ್ವಿಪ್ರತಿ ಫೈಲುಗಳಿಗೆ ಹುಡುಕು',
'fileduplicatesearch-legend'   => 'ದ್ವಿಪ್ರತಿಯನ್ನು ಹುಡುಕು',
'fileduplicatesearch-filename' => 'ಫೈಲಿನ ಹೆಸರು:',
'fileduplicatesearch-submit'   => 'ಹುಡುಕು',
'fileduplicatesearch-result-n' => '"$1" ಫೈಲು {{PLURAL:$2|೧ ದ್ವಿಪ್ರತಿಯನ್ನು|$2 ದ್ವಿಪ್ರತಿಗಳನ್ನು}} ಹೊಂದಿದೆ.',

);
