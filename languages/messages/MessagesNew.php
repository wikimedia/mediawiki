<?php
/** Newari (नेपाल भाषा)
 *
 * @ingroup Language
 * @file
 *
 * @author Eukesh
 * @author SPQRobin
 * @author Siebrand
 * @author Jon Harald Søby
 */

$namespaceNames = array(
	NS_MEDIA            => 'माध्यम',
	NS_SPECIAL          => 'विशेष',
	NS_MAIN             => '',
	NS_TALK             => 'खँलाबँला',
	NS_USER             => 'छ्येलेमि',
	NS_USER_TALK        => 'छ्येलेमि_खँलाबँला',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_खँलाबँला',
	NS_IMAGE            => 'किपा',
	NS_IMAGE_TALK       => 'किपा_खँलाबँला',
	NS_MEDIAWIKI        => 'मिडियाविकि',
	NS_MEDIAWIKI_TALK   => 'मिडियाविकि_खँलाबँला',
	NS_HELP             => 'ग्वाहालि',
	NS_HELP_TALK        => 'ग्वाहालि_खँलाबँला',
	NS_CATEGORY         => 'पुचः',
	NS_CATEGORY_TALK    => 'पुचः_खँलाबँला'
);

$digitTransformTable = array(
	'0' => '०', # &#x0966;
	'1' => '१', # &#x0967;
	'2' => '२', # &#x0968;
	'3' => '३', # &#x0969;
	'4' => '४', # &#x096a;
	'5' => '५', # &#x096b;
	'6' => '६', # &#x096c;
	'7' => '७', # &#x096d;
	'8' => '८', # &#x096e;
	'9' => '९', # &#x096f;
);

$messages = array(
# User preference toggles
'tog-justify'          => 'अनुच्छेद धंकादिसँ',
'tog-hideminor'        => 'न्हुगु हिलेज्याय् चिधंगु सम्पादन सुचुकादिसँ',
'tog-rememberpassword' => 'जिगु लग इन थ्व कम्प्युतरय् लुमंकादिसँ',
'tog-watchcreations'   => 'जिं देकागु / न्ह्यथनागु पौ जिगु दृष्टिधलः(watchlist)य् तयादिसँ',

'underline-always' => 'न्ह्याबिलें',

# Dates
'sunday'   => 'आइतबाः',
'january'  => 'ज्यानुवरी',
'february' => 'फेब्रुवरी',

# Categories related messages
'category-empty' => "''थ्व पुचले आःईले पौ वा मिदिया मदु।''",

'about'         => 'विषयक',
'qbfind'        => 'मालादिसँ',
'moredotdotdot' => 'अप्व॰॰॰',
'mypage'        => 'जिगु पौ',
'mytalk'        => 'जिगु खं',
'navigation'    => 'परिवहन',
'and'           => 'व',

'search'           => 'मालादिसं',
'searchbutton'     => 'मालादिसँ',
'go'               => 'झासँ',
'searcharticle'    => 'झासँ',
'history'          => 'पौया इतिहास',
'history_short'    => 'इतिहास',
'info_short'       => 'जानकारी',
'printableversion' => 'ध्वायेज्युगु संस्करण',
'print'            => 'ध्वानादिसँ',
'edit'             => 'सम्पादन',
'editthispage'     => 'थ्व पौ सम्पादन यानादिसं',
'newpage'          => 'न्हुगु पौ',
'talkpagelinktext' => 'खँल्हाबँल्हा',
'specialpage'      => 'विषेश पौ',
'personaltools'    => 'निजी ज्याब्व',
'talk'             => 'खँलाबँला',
'toolbox'          => 'ज्याब्व सन्दुक',
'projectpage'      => 'ज्याखँ पौ क्येनादिसँ',
'otherlanguages'   => 'मेमेगु भाषाय्',
'jumptosearch'     => 'मालादिसँ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'edithelppage'         => 'Help:सम्पादन',
'mainpage'             => 'मू पौ',
'mainpage-description' => 'मू पौ',
'policy-url'           => 'Project:नीति',
'portal'               => 'सामाजिक दबू',
'portal-url'           => 'Project:सामाजिक दबू',
'privacy'              => 'दुबिस्ता नियम',

'ok'                      => 'ज्यु',
'newmessageslink'         => 'न्हुगु सन्देश',
'newmessagesdifflink'     => 'न्हापाया हिलेज्या',
'youhavenewmessagesmulti' => '$1य् छित न्हुगु सन्देश वगु दु',
'editsection'             => 'सम्पादन',
'editold'                 => 'सम्पादन',
'editsectionhint'         => 'खण्ड सम्पादन: $1',
'showtoc'                 => 'क्यनादिसँ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-mediawiki' => 'सन्देश',
'nstab-category'  => 'पुचः',

# General errors
'laggedslavemode' => 'चेतावनी: पतिइ न्हुगु अपदेत मदेफु ।',
'readonly'        => 'देताबेस संरक्षित',
'internalerror'   => 'इन्तरनल इरर',
'viewsource'      => 'स्रोत स्वयादिसँ',

# Login and logout pages
'loginpagetitle'    => 'छ्य्‌लामि दुहां झासँ',
'yourname'          => 'छ्य्‌लामि नां:',
'yourpassword'      => 'दुथखँग्वः (पासवर्द):',
'yourpasswordagain' => 'दुथखँग्वः हानं तियादिसँ:',
'yourdomainname'    => 'छिगु दोमेन:',
'login'             => 'दुहां वनेगु',
'logout'            => 'पिने झासँ',
'userlogout'        => 'पिने झासँ',
'nologinlink'       => 'खाता न्ह्यथनादिसँ',
'createaccount'     => 'खाता चायेकादिसँ',
'youremail'         => 'इ-मेल:',
'username'          => 'छ्य्‌लामि नां:',
'yourrealname'      => 'वास्तविक नां:',
'yourlanguage'      => 'भाषा:',
'accountcreated'    => 'खाता न्ह्येथन',

# Edit pages
'savearticle'   => 'पौ मुंकादिसं',
'preview'       => 'स्वयादिसं',
'newarticle'    => '(न्हु)',
'note'          => '<strong>होस यानादिसँ:</strong>',
'previewnote'   => '<strong>थ्व पूर्वालोकन जक्क ख। छिं यानादिगु सम्पादन स्वथंगु मदुनि!</strong>',
'editing'       => '$1 सम्पादन जुयाच्वँगु दु',
'editconflict'  => 'सम्पादन द्वंगु दु: $1',
'yourtext'      => 'छिगु आखः',
'storedversion' => 'स्वथनातगु संस्करण',

# History pages
'revisionasof'     => '$1 तक्कया संस्करण',
'previousrevision' => '←पुलांगु संस्करण',

# Search results
'powersearch' => 'मालादिसँ',

# Preferences page
'mypreferences'  => 'जिगु प्राथमिकता',
'changepassword' => 'पासवर्द हिलादिसँ',
'math'           => 'गणित',
'datetime'       => 'दिं व ई',
'prefs-personal' => 'छ्य्‌लामि प्रोफाइल',
'saveprefs'      => 'स्वथनादिसँ',

# User rights
'userrights-user-editname' => 'छपू छ्य्‌लामि नां तयादिसँ:',

# Groups
'group-bot' => 'बोत',

# Recent changes
'recentchanges' => 'न्हुगु हिलेज्या',
'show'          => 'क्यनादिसँ',

# Upload
'upload' => 'फाइल अपलोड',

# Random page
'randompage' => 'छगु च्वसुइ येंकादिसं',

# Statistics
'statistics' => 'तथ्याङ्क',

'withoutinterwiki-submit' => 'क्यनादिसँ',

# Miscellaneous special pages
'newpages-username' => 'छ्येलेमि नां:',

# Special:Allpages
'allpages'    => 'सकल पौत',
'nextpage'    => 'मेगु पौ ($1)',
'allarticles' => 'सकल च्वसुत',

# Special:Categories
'categories' => 'पुचःत',

# Restrictions (nouns)
'restriction-edit' => 'सम्पादन',

# Namespace form on various pages
'namespace'      => 'नेमस्पेस:',
'blanknamespace' => '(मू)',

# Contributions
'mycontris' => 'जिगु योगदान',

# What links here
'whatlinkshere' => 'थन छु स्वाई',

# Attribution
'others' => 'मेमेगु',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'सकल',

# Auto-summaries
'autosumm-new' => 'न्हुगु पौ: $1',

# Special:SpecialPages
'specialpages' => 'विषेश पौत:',

);
