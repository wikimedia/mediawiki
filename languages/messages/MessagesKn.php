<?php
/**
 * Language file for Kannada.
 * Mosty done by:
 *   Hari Prasad Nadig <hpnadig@gmail.com>
 *     http://en.wikipedia.org/wiki/User:Hpnadig
 *   Ashwath Mattur <ashwatham@gmail.com>
 *     http://en.wikipedia.org/wiki/User:Ashwatham
 *
 * Also see the Kannada Localisation Initiative at:
 *      http://kannada.sourceforge.net/
 *
 * @addtogroup Language
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
# Dates
'jan' => 'ಜನವರಿ',
'feb' => 'ಫೆಬ್ರುವರಿ',
'mar' => 'ಮಾರ್ಚ್',
'apr' => 'ಎಪ್ರಿಲ್',
'may' => 'ಮೇ',
'jun' => 'ಜೂನ್',
'jul' => 'ಜುಲೈ',
'aug' => 'ಆಗಸ್ಟ್',
'sep' => 'ಸೆಪ್ಟೆಂಬರ್',
'oct' => 'ಅಕ್ಟೋಬರ್',
'nov' => 'ನವೆಂಬರ್',
'dec' => 'ಡಿಸೆಂಬರ್',

# Bits of text used by many pages
'categories'      => 'ವರ್ಗಗಳು',
'pagecategories'  => 'ವರ್ಗಗಳು',
'category_header' => '"$1" ವರ್ಗದಲ್ಲಿರುವ ಲೇಖನಗಳು',
'subcategories'   => 'ಉಪವಿಭಾಗಗಳು',

'mainpagetext' => 'ವಿಕಿ ತ೦ತ್ರಾ೦ಶವನ್ನು ಯಶಸ್ವಿಯಾಗಿ ಅನುಸ್ಥಾಪಿಸಲಾಯಿತು.',

'about'         => 'ನಮ್ಮ ಬಗ್ಗೆ',
'article'       => 'ಲೇಖನ ಪುಟ',
'newwindow'     => '(ಹೊಸ ಕಿಟಕಿಯನ್ನು ತೆರೆಯುತ್ತದೆ)',
'cancel'        => 'ವಜಾ ಮಾಡಿ',
'moredotdotdot' => 'ಇನ್ನಷ್ಟು...',
'mypage'        => 'ನನ್ನ ಪುಟ',
'mytalk'        => 'ನನ್ನ ಚರ್ಚೆ',
'anontalk'      => 'ಈ ಐ.ಪಿ ಗೆ ಮಾತನಾಡಿ',
'navigation'    => 'ಸಂಚರಣೆ',

'errorpagetitle' => 'ದೋಷ',
'returnto'       => '$1 ಗೆ ಹಿಂತಿರುಗಿ.',
'help'           => 'ಸಹಾಯ',
'search'         => 'ಹುಡುಕು',
'searchbutton'   => 'ಹುಡುಕು',
'go'             => 'ಹೋಗು',
'searcharticle'  => 'ಹೋಗು',
'history'        => 'ಪುಟದ ಚರಿತ್ರೆ',
'history_short'  => 'ಇತಿಹಾಸ',
'info_short'     => 'ಮಾಹಿತಿ',
'edit'           => 'ಸಂಪಾದಿಸಿ',
'editthispage'   => 'ಈ ಪುಟವನ್ನು ಬದಲಾಯಿಸಿ',
'delete'         => 'ಅಳಿಸಿ',
'deletethispage' => 'ಈ ಪುಟವನ್ನು ಅಳಿಸಿ',
'protect'        => 'ಸಂರಕ್ಷಿಸು',
'unprotect'      => 'ಸಂರಕ್ಷಣೆ ತೆಗೆ',
'newpage'        => 'ಹೊಸ ಪುಟ',
'talkpage'       => 'ಈ ಪುಟದ ಬಗ್ಗೆ ಚರ್ಚೆ ಮಾಡಿ',
'specialpage'    => 'ವಿಶೇಷ ಪುಟ',
'postcomment'    => 'ನಿಮ್ಮ ಮಾತನ್ನು ಲಗತ್ತಿಸಿ',
'articlepage'    => 'ಲೇಖನ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'toolbox'        => 'ಉಪಕರಣ',
'imagepage'      => 'ಚಿತ್ರದ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'otherlanguages' => 'ಇತರ ಭಾಷೆಗಳು',
'lastmodifiedat' => 'ಈ ಪುಟವನ್ನು ಕೊನೆಯಾಗಿ $2, $1 ರಂದು ಬದಲಾಯಿಸಲಾಗಿತ್ತು.', # $1 date, $2 time
'protectedpage'  => 'ಸ೦ರಕ್ಷಿತ ಪುಟ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutpage'         => 'ವಿಕಿಪೀಡಿಯಾ:ನಮ್ಮ ಬಗ್ಗೆ',
'copyright'         => 'ಇದು ಈ ಕಾಪಿರೈಟ್‌ನಲ್ಲಿ ಲಭ್ಯವಿದೆ $1.',
'copyrightpagename' => '{{SITENAME}} ಕಾಪಿರೈಟ್',
'copyrightpage'     => 'ವಿಕಿಪೀಡಿಯ: ಕೃತಿಸ್ವಾಮ್ಯತೆಗಳು',
'currentevents'     => 'ಪ್ರಚಲಿತ',
'edithelp'          => 'ಸಂಪಾದನೆಗೆ ಸಹಾಯ',
'edithelppage'      => 'ಸಹಾಯ:ಸ೦ಪಾದನೆ',
'helppage'          => 'ಸಹಾಯ:ಪರಿವಿಡಿ',
'portal'            => 'ಸಮುದಾಯ ಪುಟ',
'sitesupport'       => 'ದೇಣಿಗೆ',

'ok'              => 'ಸರಿ',
'newmessageslink' => 'ಹೊಸ ಸಂದೇಶಗಳು',
'editsection'     => 'ಬದಲಾಯಿಸಿ',
'editold'         => 'ಬದಲಾಯಿಸಿ',
'toc'             => 'ಪರಿವಿಡಿ',
'showtoc'         => 'ತೋರಿಸು',
'hidetoc'         => 'ಅಡಗಿಸು',
'feedlinks'       => 'ಫೀಡ್:',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main'      => 'ಲೇಖನ',
'nstab-user'      => 'ಸದಸ್ಯರ ಪುಟ',
'nstab-special'   => 'ವಿಶೇಷ',
'nstab-project'   => 'ಬಗ್ಗೆ',
'nstab-image'     => 'ಚಿತ್ರ',
'nstab-mediawiki' => 'ಸಂದೇಶ',
'nstab-help'      => 'ಸಹಾಯ',
'nstab-category'  => 'ವರ್ಗ',

# General errors
'error'           => 'ದೋಷ',
'databaseerror'   => 'ಡೇಟಬೇಸ್ ದೋಷ',
'internalerror'   => 'ಆ೦ತರಿಕ ದೋಷ',
'filecopyerror'   => '"$1" ಫೈಲ್ ಅನ್ನು "$2" ಗೆ ನಕಲಿಸಲಾಗಲಿಲ್ಲ.',
'filedeleteerror' => '"$1" ಫೈಲ್ ಅನ್ನು ಅಳಿಸಲಾಗಲಿಲ್ಲ.',
'filenotfound'    => '"$1" ಫೈಲನ್ನು ಹುಡುಕಲಾಗಲಿಲ್ಲ.',
'formerror'       => 'ದೋಷ: ಅರ್ಜಿ ಕಳುಹಿಸಲಾಗಲಿಲ್ಲ',
'badarticleerror' => 'ಈ ಪುಟದ ಮೇಲೆ ನೀವು ಪ್ರಯತ್ನಿಸಿದ ಕಾರ್ಯವನ್ನು ನಡೆಸಲಾಗದು.',
'cannotdelete'    => 'ಈ ಪುಟ ಅಥವಾ ಚಿತ್ರವನ್ನು ಅಳಿಸಲಾಗಲಿಲ್ಲ. (ಬೇರೊಬ್ಬ ಸದಸ್ಯರಿ೦ದ ಆಗಲೇ ಅಳಿಸಲ್ಪಟ್ಟಿರಬಹುದು.)',
'badtitle'        => 'ಸರಿಯಿಲ್ಲದ ಹೆಸರು',
'viewsource'      => 'ಆಕರ ವೀಕ್ಷಿಸು',

# Login and logout pages
'logouttitle'                => 'ಸದಸ್ಯ ಲಾಗೌಟ್',
'yourname'                   => 'ನಿಮ್ಮ ಬಳಕೆಯ ಹೆಸರು',
'yourpassword'               => 'ನಿಮ್ಮ ಪ್ರವೇಶಪದ',
'loginproblem'               => '<b>ನಿಮ್ಮ ಲಾಗಿನ್ ನಲ್ಲಿ ತೊ೦ದರೆಯಾಯಿತು.</b><br />ಮತ್ತೆ ಪ್ರಯತ್ನಿಸಿ!',
'login'                      => 'ಲಾಗ್ ಇನ್',
'userlogin'                  => 'ಲಾಗ್ ಇನ್',
'logout'                     => 'ಲಾಗ್ ಔಟ್',
'userlogout'                 => 'ಲಾಗ್ ಔಟ್',
'notloggedin'                => 'ಲಾಗಿನ್ ಆಗಿಲ್ಲ',
'createaccount'              => 'ಹೊಸ ಖಾತೆ ತೆರೆಯಿರಿ',
'createaccountmail'          => 'ಇ-ಅಂಚೆಯ ಮೂಲಕ',
'badretype'                  => 'ನೀವು ಕೊಟ್ಟ ಪ್ರವೇಶಪದಗಳು ಬೇರೆಬೇರೆಯಾಗಿವೆ.',
'loginerror'                 => 'ಲಾಗಿನ್ ದೋಷ',
'loginsuccesstitle'          => 'ಲಾಗಿನ್ ಯಶಸ್ವಿ',
'loginsuccess'               => 'ನೀವು ಈಗ "$1" ಆಗಿ ವಿಕಿಪೀಡಿಯಕ್ಕೆ ಲಾಗಿನ್ ಆಗಿದ್ದೀರಿ.',
'nosuchuser'                 => '"$1" ಹೆಸರಿನ ಯಾವ ಸದಸ್ಯರೂ ಇಲ್ಲ.
ಕಾಗುಣಿತವನ್ನು ಪರೀಕ್ಷಿಸಿ, ಅಥವಾ ಕೆಳಗಿನ ಫಾರ್ಮ್ ಅನ್ನು ಉಪಯೋಗಿಸಿ ಹೊಸ ಸದಸ್ಯತ್ವವನ್ನು ಸೃಷ್ಟಿಸಿ.',
'mailmypassword'             => 'ಹೊಸ ಪ್ರವೇಶ ಪದವನ್ನು ಇ-ಅಂಚೆ ಮೂಲಕ ಕಳುಹಿಸಿ',
'acct_creation_throttle_hit' => 'ಕ್ಷಮಿಸಿ, ನೀವಾಗಲೇ $1 ಖಾತೆಗಳನ್ನು ತೆರೆದಿದ್ದೀರಿ. ಇನ್ನು ಖಾತೆಗಳನ್ನು ತೆರೆಯಲಾಗುವುದಿಲ್ಲ.',

# Edit page toolbar
'bold_sample'     => 'ದಪ್ಪಗಿನ ಅಚ್ಚು',
'bold_tip'        => 'ದಪ್ಪಗಿನ ಅಚ್ಚು',
'link_sample'     => 'ಸಂಪರ್ಕದ ಹೆಸರು',
'link_tip'        => 'ಆಂತರಿಕ ಸಂಪರ್ಕ',
'headline_sample' => 'ಶಿರೋಲೇಖ',

# Edit pages
'summary'       => 'ಸಾರಾಂಶ',
'minoredit'     => 'ಇದು ಚುಟುಕಾದ ಬದಲಾವಣೆ',
'watchthis'     => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'savearticle'   => 'ಪುಟವನ್ನು ಉಳಿಸಿ',
'preview'       => 'ಮುನ್ನೋಟ',
'showpreview'   => 'ಮುನ್ನೋಟ',
'blockedtitle'  => 'ಈ ಸದಸ್ಯರನ್ನು ತಡೆ ಹಿಡಿಯಲಾಗಿದೆ.',
'loginreqtitle' => 'ಲಾಗಿನ್ ಆಗಬೇಕು',
'accmailtitle'  => 'ಪ್ರವೇಶ ಪದ ಕಳುಹಿಸಲಾಯಿತು.',
'accmailtext'   => "'$1'ನ ಪ್ರವೇಶ ಪದ $2 ಗೆ ಕಳುಹಿಸಲಾಗಿದೆ",
'newarticle'    => '(ಹೊಸತು)',
'noarticletext' => '(ಈ ಪುಟದಲ್ಲಿ ಸದ್ಯಕ್ಕೆ ಏನೂ ಇಲ್ಲ)',
'note'          => '<strong>ಸೂಚನೆ:</strong>',
'previewnote'   => 'ಇದು ಕೇವಲ ಮುನ್ನೋಟ, ಪುಟವನ್ನು ಇನ್ನೂ ಉಳಿಸಲಾಗಿಲ್ಲ ಎ೦ಬುದನ್ನು ಮರೆಯದಿರಿ!',
'editing'       => "'$1' ಲೇಖನ ಬದಲಾಯಿಸಲಾಗುತ್ತಿದೆ",
'editinguser'   => "'$1' ಲೇಖನ ಬದಲಾಯಿಸಲಾಗುತ್ತಿದೆ",
'storedversion' => 'ಈಗಾಗಲೇ ಉಳಿಸಲಾಗಿರುವ ಆವೃತ್ತಿ',
'editingold'    => '<strong>ಎಚ್ಚರಿಕೆ: ಈ ಪುಟದ ಹಳೆಯ ಆವೃತ್ತಿಯನ್ನು ಬದಲಾಯಿಸುತ್ತಿದ್ದೀರಿ. ಈ ಬದಲಾವಣೆಗಳನ್ನು ಉಳಿಸಿದಲ್ಲಿ, ನ೦ತರದ ಆವೃತ್ತಿಗಳೆಲ್ಲವೂ ಕಳೆದುಹೋಗುತ್ತವೆ.</strong>',

# History pages
'currentrev' => 'ಈಗಿನ ತಿದ್ದುಪಡಿ',
'cur'        => 'ಸದ್ಯದ',
'next'       => 'ಮುಂದಿನದು',
'last'       => 'ಕೊನೆಯ',

# Diffs
'difference'              => '(ಆವೃತ್ತಿಗಳ ನಡುವಿನ ವ್ಯತ್ಯಾಸ)',
'lineno'                  => '$1 ನೇ ಸಾಲು:',
'editcurrent'             => 'ಈ ಪುಟದ ಪ್ರಸಕ್ತ ಆವೃತ್ತಿಯನ್ನು ಸ೦ಪಾದಿಸಿ',
'compareselectedversions' => 'ಆಯ್ಕೆ ಮಾಡಿದ ಆವೃತ್ತಿಗಳನ್ನು ಹೊಂದಾಣಿಕೆ ಮಾಡಿ ನೋಡಿ',

# Search results
'searchresults' => 'ಶೋಧನೆಯ ಫಲಿತಾಂಶಗಳು',
'nextn'         => 'ಮು೦ದಿನ $1',
'powersearch'   => 'ಹುಡುಕಿ',

# Preferences page
'changepassword'     => 'ಪ್ರವೇಶ ಪದ ಬದಲಾಯಿಸಿ',
'dateformat'         => 'ದಿನಾಂಕದ ಫಾರ್ಮ್ಯಾಟ್',
'oldpassword'        => 'ಹಳೆಯ ಪ್ರವೇಶ ಪದ',
'newpassword'        => 'ಹೊಸ ಪ್ರವೇಶ ಪದ',
'recentchangescount' => 'ಇತ್ತೀಚೆಗಿನ ಬದಲಾವಣೆಗಳಲ್ಲಿರುವ ವಿಷಯಗಳ ಸಂಖ್ಯೆ',
'timezonelegend'     => 'ಟೈಮ್ ಝೋನ್',

# Recent changes
'recentchanges'     => 'ಇತ್ತೀಚೆಗಿನ ಬದಲಾವಣೆಗಳು',
'recentchangestext' => 'ವಿಕಿಗೆ ಮಾಡಲ್ಪಟ್ಟ ಇತ್ತೀಚಿನ ಬದಲಾವಣೆಗಳನ್ನು ಈ ಪುಟದಲ್ಲಿ ನೀವು ಕಾಣಬಹುದು.',
'rcnote'            => 'ಕೊನೆಯ <strong>$2</strong> ದಿನಗಳಲ್ಲಿ ಮಾಡಿದ <strong>$1</strong> ಬದಲಾವಣೆಗಳು ಕೆಳಗಿನಂತಿವೆ.',
'rclistfrom'        => '$1 ಇಂದ ಪ್ರಾರಂಭಿಸಿ ಮಾಡಲಾದ ಬದಲಾವಣೆಗಳನ್ನು ನೋಡಿ',
'rclinks'           => 'ಕೊನೆಯ $2 ದಿನಗಳಲ್ಲಿ ಮಾಡಿದ $1 ಕೊನೆಯ ಬದಲಾವಣೆಗಳನ್ನು ನೋಡಿ <br />$3',
'diff'              => 'ವ್ಯತ್ಯಾಸ',
'hist'              => 'ಇತಿಹಾಸ',
'hide'              => 'ಅಡಗಿಸು',
'show'              => 'ತೋರಿಸು',
'newpageletter'     => 'ಹೊ',

# Recent changes linked
'recentchangeslinked' => 'ಸಂಬಂಧಪಟ್ಟ ಬದಲಾವಣೆಗಳು',

# Upload
'upload'       => 'ಫೈಲ್ ಅಪ್ಲೋಡ್',
'filename'     => 'ಕಡತದ ಹೆಸರು:',
'filedesc'     => 'ಸಾರಾಂಶ',
'filesource'   => 'ಆಕರ:',
'minlength'    => 'ಚಿತ್ರಗಳ ಹೆಸರಿನಲ್ಲಿ ಕನಿಷ್ಟ ಮೂರು ಅಕ್ಷರಗಳಿರಬೇಕು.',
'badfilename'  => 'ಚಿತ್ರದ ಹೆಸರನ್ನು $1 ಗೆ ಬದಲಾಯಿಸಲಾಗಿದೆ.',
'fileexists'   => 'ಈ ಹೆಸರಿನ ಫೈಲ್ ಆಗಲೇ ಅಸ್ತಿತ್ವದಲ್ಲಿದೆ. ಈ ಹೆಸರನ್ನು ಬದಲಾಯಿಸಲು ಇಚ್ಛೆಯಿಲ್ಲದಿದ್ದರೆ, ದಯವಿಟ್ಟು $1 ಅನ್ನು ಪರೀಕ್ಷಿಸಿ.',
'fileuploaded' => 'ಫೈಲ್ "$1" ಯಶಸ್ವಿಯಾಗಿ ಅಪ್‍ಲೋಡ್ ಆಯಿತು. ದಯವಿಟ್ಟು ಕೆಳಗಿನ ಸ೦ಪರ್ಕವನ್ನು ಕ್ಲಿಕ್ಕಿಸಿ ಈ ಫೈಲ್ ನ ಬಗ್ಗೆ ಮಾಹಿತಿಯನ್ನು ತು೦ಬಿಸಿ. ಉದಾಹರಣೆಗೆ: ಈ ಫೈಲ್ ಎಲ್ಲಿ೦ದ ಬ೦ದಿತು, ಯಾವಾಗ ಯಾರಿ೦ದ ಸೃಷ್ಟಿಯಾದದ್ದು, ಮತ್ತು ನಿಮಗೆ ತಿಳಿದಿರುವ ಇತರ ಯಾವುದೇ ಮಾಹಿತಿ.

$2',
'savefile'     => 'ಕಡತವನ್ನು ಉಳಿಸಿ',

# Image list
'imagelist'    => 'ಚಿತ್ರಗಳ ಪಟ್ಟಿ',
'getimagelist' => 'ಚಿತ್ರಗಳ ಪಟ್ಟಿಯನ್ನು ಪಡೆಯಲಾಗುತ್ತಿದೆ',
'ilsubmit'     => 'ಹುಡುಕು',
'byname'       => 'ಹೆಸರಿಗನುಗುಣವಾಗಿ',
'bydate'       => 'ದಿನಾಂಕಕ್ಕನುಗುಣವಾಗಿ',
'bysize'       => 'ಗಾತ್ರಕ್ಕನುಗುಣವಾಗಿ',
'deleteimg'    => 'ಅಳಿಸಿ',
'linkstoimage' => 'ಈ ಕೆಳಗಿನ ಪುಟಗಳು ಈ ಚಿತ್ರಕ್ಕೆ ಸಂಪರ್ಕ ಹೊಂದಿವೆ:',

# Statistics
'statistics'    => 'ಅಂಕಿ ಅಂಶಗಳು',
'sitestats'     => 'ತಾಣದ ಅಂಕಿಅಂಶಗಳು',
'userstats'     => 'ಸದಸ್ಯರ ಅಂಕಿ ಅಂಶ',
'sitestatstext' => "ಒಟ್ಟು '''\$1''' ಪುಟಗಳು ಡೇಟಾಬೇಸ್‌ನಲ್ಲಿವೆ.
ಈ ಸಂಖ್ಯೆ \"ಚರ್ಚೆ\" ಪುಟಗಳನ್ನು, ವಿಕಿಪೀಡಿಯಾದ ಬಗೆಗಿನ ಪುಟಗಳನ್ನು, ಹಾಗೂ ಪುಟ್ಟ \"ಚುಟುಕು\" ಪುಟಗಳನ್ನೂ, ರೆಡೈರೆಕ್ಟ್ ಮಾಡಿದ ಪುಟಗಳನ್ನು ಹಾಗೂ ಬೇರೆಲ್ಲೂ ಸೇರಿಸಲಾಗದ ಕೆಲವು ಇತರೆ ಪುಟಗಳನ್ನು ಒಳಗೊಂಡಿದೆ.

ಇವುಗಳನ್ನು ಬಿಟ್ಟು, ಒಟ್ಟು '''\$2''' ಬಹುಶಃ ನಿಜವಾದ ಲೇಖನಗಳಿಂದ ಕೂಡಿದ ಪುಟಗಳಿವೆ.",
'userstatstext' => "ಒಟ್ಟು '''$1''' ನೊಂದಾಯಿಸಿದ ಸದಸ್ಯರಿದ್ದಾರೆ. ಇವರಲ್ಲಿ '''$2''' ಮಂದಿ ನಿರ್ವಾಹಕರಿದ್ದಾರೆ ($3 ನೋಡಿ).",

'brokenredirects' => 'ಮುರಿದ ರಿಡೈರೆಕ್ಟ್‌ಗಳು',

# Miscellaneous special pages
'nlinks'             => '$1 ಸ೦ಪರ್ಕಗಳು',
'lonelypages'        => 'ಒಬ್ಬಂಟಿ ಪುಟಗಳು',
'uncategorizedpages' => 'ವರ್ಗ ಗೊತ್ತು ಮಾಡದ ಪುಟಗಳು',
'unusedimages'       => 'ಉಪಯೋಗಿಸದ ಚಿತ್ರಗಳು',
'popularpages'       => 'ಜನಪ್ರಿಯ ಪುಟಗಳು',
'wantedpages'        => 'ಬೇಕಾಗಿರುವ ಪುಟಗಳು',
'randompage'         => 'ಯಾದೃಚ್ಛಿಕ ಪುಟ',
'shortpages'         => 'ಪುಟ್ಟ ಪುಟಗಳು',
'longpages'          => 'ಉದ್ದನೆಯ ಪುಟಗಳು',
'deadendpages'       => 'ಕೊನೆಯಂಚಿನ ಪುಟಗಳು',
'specialpages'       => 'ವಿಶೇಷ ಪುಟಗಳು',
'spheading'          => 'ಎಲ್ಲಾ ಸದಸ್ಯರಿಗೂ ಇರುವ ವಿಶೇಷ ಪುಟಗಳು',
'newpages'           => 'ಹೊಸ ಪುಟಗಳು',
'ancientpages'       => 'ಹಳೆಯ ಪುಟಗಳು',
'move'               => 'ಸ್ಥಳಾಂತರಿಸಿ',
'movethispage'       => 'ಈ ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',

# Book sources
'booksources' => 'ಪುಸ್ತಕಗಳ ಮೂಲ',

'categoriespagetext' => 'ವಿಕಿಯಲ್ಲಿ ಈ ಕೆಳಗಿನ ವರ್ಗಗಳಿವೆ',
'alphaindexline'     => '$1 ಇಂದ $2',
'version'            => 'ಆವೃತ್ತಿ',

# Special:Allpages
'nextpage' => 'ಮು೦ದಿನ ಪುಟ ($1)',

# E-mail user
'emailuser'       => 'ಈ ಸದಸ್ಯರಿಗೆ ವಿ-ಅ೦ಚೆ ಕಳಿಸಿ',
'emailpage'       => 'ಸದಸ್ಯರಿಗೆ ವಿ-ಅ೦ಚೆ ಕಳಿಸಿ',
'defemailsubject' => 'ವಿಕಿಪೀಡಿಯ ವಿ-ಅ೦ಚೆ',
'emailfrom'       => 'ಇಂದ',
'emailto'         => 'ಗೆ',
'emailsubject'    => 'ವಿಷಯ',
'emailmessage'    => 'ಸಂದೇಶ',
'emailsend'       => 'ಕಳುಹಿಸಿ',
'emailsent'       => 'ಇ-ಅಂಚೆ ಕಳುಹಿಸಲಾಯಿತು',
'emailsenttext'   => 'ನಿಮ್ಮ ವಿ-ಅ೦ಚೆ ಸ೦ದೇಶವನ್ನು ಕಳುಹಿಸಲಾಯಿತು.',

# Watchlist
'addedwatch'     => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿಗೆ ಸೇರಿಸಲಾಯಿತು',
'addedwatchtext' => '"$1" ಪುಟವನ್ನು ನಿಮ್ಮ [[Special:Watchlist|ವೀಕ್ಷಣಾಪಟ್ಟಿಗೆ]] ಸೇರಿಸಲಾಗಿದೆ. ಈ ಪುಟದ ಮತ್ತು ಇದರ ಚರ್ಚಾ ಪುಟದ ಮು೦ದಿನ ಬದಲಾವಣೆಗಳು ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಲ್ಲಿ ಕಾಣಸಿಗುತ್ತವೆ, ಮತ್ತು [[Special:Recentchanges|ಇತ್ತೀಚೆಗಿನ ಬದಲಾವಣೆಗಳ]] ಪಟ್ಟಿಯಲ್ಲಿ ಈ ಪುಟಗಳನ್ನು ದಪ್ಪಕ್ಷರಗಳಲ್ಲಿ ಕಾಣಿಸಲಾಗುವುದು.

<p>ಈ ಪುಟವನ್ನು ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಿ೦ದ ತೆಗೆಯಬಯಸಿದಲ್ಲಿ, ಮೇಲ್ಪಟ್ಟಿಯಲ್ಲಿ ಕಾಣಿಸಿರುವ "ವೀಕ್ಷಣಾ ಪುಟದಿ೦ದ ತೆಗೆ" ಅನ್ನು ಕ್ಲಿಕ್ಕಿಸಿ.',
'watch'          => 'ವೀಕ್ಷಿಸಿ',
'watchthispage'  => 'ಈ ಪುಟವನ್ನು ವೀಕ್ಷಿಸಿ',
'unwatch'        => 'ವೀಕ್ಷಣಾ ಪಟ್ಟಿಯಿಂದ ತೆಗೆ',

# Delete/protect/revert
'deletepage'        => 'ಪುಟವನ್ನು ಅಳಿಸಿ',
'confirm'           => 'ಧೃಡಪಡಿಸು',
'exblank'           => 'ಪುಟ ಖಾಲಿ ಇತ್ತು',
'confirmdelete'     => 'ಅಳಿಸುವಿಕೆ ಧೃಡಪಡಿಸು',
'deletesub'         => '("$1" ಅನ್ನು ಅಳಿಸಲಾಗುತ್ತಿದೆ)',
'confirmdeletetext' => 'ಪುಟ ಅಥವಾ ಚಿತ್ರ ಮತ್ತು ಅದರ ಸ೦ಪೂರ್ಣ ಇತಿಹಾಸವನ್ನು ನೀವು ಶಾಶ್ವತವಾಗಿ ಅಳಿಸಿಹಾಕುತ್ತಿದ್ದೀರಿ. ಇದನ್ನು ನೀವು ಮಾಡಬಯಸುವಿರಿ, ಇದರ ಪರಿಣಾಮಗಳನ್ನು ಬಲ್ಲಿರಿ, ಮತ್ತು [[{{ns:project}}:Policy]] ನ ಅನುಸಾರ ಇದನ್ನು ಮಾಡುತ್ತಿದ್ದೀರಿ ಎ೦ದು ದೃಢಪಡಿಸಿ.',
'actioncomplete'    => 'ಕಾರ್ಯ ಸಂಪೂರ್ಣ',
'deletedtext'       => '"$1" ಅನ್ನು ಅಳಿಸಲಾಯಿತು.
ಇತ್ತೀಚೆಗಿನ ಅಳಿಸುವಿಕೆಗಳ ಪಟ್ಟಿಗಾಗಿ $2 ಅನ್ನು ನೋಡಿ.',
'deletedarticle'    => '"$1" ಅಳಿಸಲಾಯಿತು',
'deletionlog'       => 'ಅಳಿಸುವಿಕೆ ದಿನಚರಿ',
'deletecomment'     => 'ಅಳಿಸುವುದರ ಕಾರಣ',
'confirmprotect'    => 'ಸಂರಕ್ಷಣೆ ಧೃಡಪಡಿಸಿ',
'protectcomment'    => 'ಸ೦ರಕ್ಷಿಸಲು ಕಾರಣ',

# Contributions
'contributions' => 'ಸದಸ್ಯರ ಕಾಣಿಕೆಗಳು',
'mycontris'     => 'ನನ್ನ ಕಾಣಿಕೆಗಳು',
'contribsub2'   => '$1 ($2) ಗೆ',
'uctop'         => ' (ಮೇಲಕ್ಕೆ)',

# What links here
'whatlinkshere' => 'ಇಲ್ಲಿಗೆ ಯಾವ ಸಂಪರ್ಕ ಕೂಡುತ್ತದೆ',
'linklistsub'   => '(ಸ೦ಪರ್ಕಗಳ ಪಟ್ಟಿ)',

# Block/unblock
'blockip'           => 'ಈ ಸದಸ್ಯನನ್ನು ತಡೆ ಹಿಡಿಯಿರಿ',
'ipbreason'         => 'ಕಾರಣ',
'ipbsubmit'         => 'ಈ ಸದಸ್ಯರನ್ನು ತಡೆಹಿಡಿಯಿರಿ',
'blockipsuccesssub' => 'ತಡೆಹಿಡಿಯುವಿಕೆ ಯಶಸ್ವಿಯಾಯಿತು.',
'ipblocklist'       => 'ಬ್ಲಾಕ್ ಮಾಡಲಾದ ಐಪಿ ವಿಳಾಸಗಳ ಹಾಗೂ ಬಳಕೆಯ ಹೆಸರುಗಳ ಪಟ್ಟಿ',
'blocklink'         => 'ತಡೆ ಹಿಡಿಯಿರಿ',
'contribslink'      => 'ಕಾಣಿಕೆಗಳು',
'blocklogpage'      => 'ತಡೆಹಿಡಿದ ಸದಸ್ಯರ ದಿನಚರಿ',
'blocklogentry'     => '"$1" ಅನ್ನು $2 ರ ಸಮಯದವರೆಗೆ ತಡೆಹಿಡಿಯಲಾಗಿದೆ',

# Move page
'movepage'        => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',
'movearticle'     => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',
'movenologintext' => 'ಪುಟವನ್ನು ಸ್ಥಳಾ೦ತರಿಸಲು ನೀವು ನೋ೦ದಾಯಿತ ಸದಸ್ಯರಾಗಿದ್ದು <a href="/wiki/Special:Userlogin">ಲಾಗಿನ್</a> ಆಗಿರಬೇಕು.',
'movepagebtn'     => 'ಪುಟವನ್ನು ಸ್ಥಳಾಂತರಿಸಿ',
'pagemovedsub'    => 'ಸ್ಥಳಾ೦ತರಿಸುವಿಕೆ ಯಶಸ್ವಿಯಾಯಿತು',
'1movedto2'       => '$1 - $2 ಕ್ಕೆ ಸ್ಥಳಾಂತರಿಸಲಾಗಿದೆ',
'1movedto2_redir' => '$1 - $2 ಕ್ಕೆ ರಿಡೈರೆಕ್ಟ್ ಮೂಲಕ ಸ್ಥಳಾಂತರಿಸಲಾಗಿದೆ',

# Export
'export' => 'ಪುಟಗಳನ್ನು ರಫ್ತು ಮಾಡಿ',

# Special:Import
'import'        => 'ಪುಟಗಳನ್ನು ಅಮದು ಮಾಡಿ',
'importfailed'  => 'ಆಮದು ಯಶಸ್ವಿಯಾಗಲಿಲ್ಲ: $1',
'importnotext'  => 'ಖಾಲಿ ಅಥವಾ ಯಾವುದೇ ಶಬ್ಧಗಳಿಲ್ಲ',
'importsuccess' => 'ಆಮದು ಯಶಸ್ವಿಯಾಯಿತು!',

# Attribution
'anonymous'     => 'ವಿಕಿಪೀಡಿಯಾದ ಅನಾಮಿಕ ಸದಸ್ಯರು',
'and'           => 'ಮತ್ತು',
'othercontribs' => '$1 ರ ಕೆಲಸವನ್ನು ಆಧರಿಸಿ.',
'creditspage'   => 'ಪುಟದ ಗೌರವಗಳು',

# Spam protection
'subcategorycount'     => 'ಒಟ್ಟು $1 ಉಪವಿಭಾಗಗಳು ಈ ವರ್ಗದಡಿ ಇವೆ.',
'categoryarticlecount' => 'ಈ ವರ್ಗದಲ್ಲಿ $1 ಲೇಖನಗಳಿವೆ.',

);


