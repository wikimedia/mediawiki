<?php
/** Oriya (ଓଡ଼ିଆ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Jayantanth
 * @author Jose77
 * @author Odisha1
 * @author Psubhashish
 * @author Sambiwiki
 * @author Shijualex
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

$digitTransformTable = array(
	'0' => '୦', # &#x0b66;
	'1' => '୧', # &#x0b67;
	'2' => '୨', # &#x0b68;
	'3' => '୩', # &#x0b69;
	'4' => '୪', # &#x0b6a;
	'5' => '୫', # &#x0b6b;
	'6' => '୬', # &#x0b6c;
	'7' => '୭', # &#x0b6d;
	'8' => '୮', # &#x0b6e;
	'9' => '୯', # &#x0b6f;
);

/** namespace translations from translatewiki.net 
 * @author Shijualex
 * @author Psubhashish
 */
$namespaceNames = array(
	NS_MEDIA            => 'ମାଧ୍ୟମ',
	NS_SPECIAL          => 'ବିଶେଷ',
	NS_TALK             => 'ଆଲୋଚନା',
	NS_USER             => 'ବ୍ୟବହାରକାରି',
	NS_USER_TALK        => 'ବ୍ୟବହାରକାରିଁକ_ଆଲୋଚନା',
	NS_PROJECT_TALK     => 'ଉଇକିପିଡ଼ିଆ_ଆଲୋଚନା',
	NS_FILE             => 'ଫାଇଲ',
	NS_FILE_TALK        => 'ଫାଇଲ_ଆଲୋଚନା',
	NS_MEDIAWIKI        => 'ମିଡ଼ିଆଉଇକି',
	NS_MEDIAWIKI_TALK   => 'ମିଡ଼ିଆଉଇକି_ଆଲୋଚନା',
	NS_TEMPLATE         => 'ଟେଁପଲେଟ',
	NS_TEMPLATE_TALK    => 'ଟେଁପଲେଟ_ଆଲୋଚନା',
	NS_HELP             => 'ସାହାଯ୍ୟ',
	NS_HELP_TALK        => 'ସାହାଯ୍ୟ_ଆଲୋଚନା',
	NS_CATEGORY         => 'ବିଭାଗ',
	NS_CATEGORY_TALK    => 'ବିଭାଗିୟ_ଆଲୋଚନା',
);

$messages = array(
'underline-always' => 'ସବୁବେଳେ',
'underline-never'  => 'କେବେନୁହେଁ',

# Font style option in Special:Preferences
'editfont-sansserif' => 'ସାନସ-ସେରିଫ ଫଣ୍ଟ',
'editfont-serif'     => 'ସେରିଫ ଫଣ୍ଟ',

# Dates
'sunday'        => 'ରବିବାର',
'monday'        => 'ସୋମବାର',
'tuesday'       => 'ମଙ୍ଗଳବାର',
'wednesday'     => 'ବୁଧବାର',
'thursday'      => 'ଗୁରୁବାର',
'friday'        => 'ଶୁକ୍ରବାର',
'saturday'      => 'ଶନିବାର',
'sun'           => 'ରବିବାର',
'mon'           => 'ସୋମବାର',
'tue'           => 'ମଙ୍ଗଳବାର',
'wed'           => 'ବୁଧବାର',
'thu'           => 'ଗୁରୁବାର',
'fri'           => 'ଶୁକ୍ରବାର',
'sat'           => 'ଶନିବାର',
'january'       => 'ଜାନୁଆରି',
'february'      => 'ଫେବୁଆରି',
'march'         => 'ମାର୍ଚ',
'april'         => 'ଏପ୍ରିଲ',
'may_long'      => 'ମେ',
'june'          => 'ଜୁନ',
'july'          => 'ଜୁଲାଇ',
'august'        => 'ଅଗଷ୍ଟ',
'september'     => 'ସେପଟେମ୍ବର',
'october'       => 'ଅକଟୋବର',
'november'      => 'ନଭେମ୍ବର',
'december'      => 'ଡିସେମ୍ବର',
'january-gen'   => 'ଜାନୁଆରି',
'february-gen'  => 'ଫେବ୍ରୁଆରି',
'march-gen'     => 'ମାର୍ଚ',
'april-gen'     => 'ଏପ୍ରିଲ',
'may-gen'       => 'ମେ',
'june-gen'      => 'ଜୁନ',
'july-gen'      => 'ଜୁଲାଇ',
'august-gen'    => 'ଅଗଷ୍ଟ',
'september-gen' => 'ସେପ୍ଟେଁବର',
'october-gen'   => 'ଅକଟୋବର',
'november-gen'  => 'ନଭେଁବର',
'december-gen'  => 'ଡିସେଁବର',
'jan'           => 'ଜାନୁଆରି',
'feb'           => 'ଫେବୃଆରି',
'mar'           => 'ମାର୍ଚ',
'apr'           => 'ଏପ୍ରିଲ',
'may'           => 'ମେ',
'jun'           => 'ଜୁନ',
'jul'           => 'ଜୁଲାଇ',
'aug'           => 'ଅଗଷ୍ଟ',
'sep'           => 'ସେପଟେଁବର',
'oct'           => 'ଅକଟୋବର',
'nov'           => 'ନଭେଁବର',
'dec'           => 'ଡିସେଁବର',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Category|ବିଭାଗ}}',
'category_header'          => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'subcategories'            => 'ସାନ ଶ୍ରେଣୀସବୁ',
'category-media-header'    => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'category-empty'           => "''ଏହି ଶ୍ରେଣୀ ଭିତରେ କିଛି ଫରଦ ବା ମାଧ୍ୟମ ନାହିଁ ।''",
'hidden-categories'        => '{{PLURAL:$1|Hidden category|ଲୁଚିଥିବା ବିଭାଗ}}',
'hidden-category-category' => 'ଲୁଚିରହିଥିବା ଶ୍ରେଣୀ',
'category-subcat-count'    => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀଟିରେ କେବଳ ତଳେଥିବା ସାନ ଶ୍ରେଣୀଗୁଡିକ ଅଛନ୍ତି । |ଏହି ଶ୍ରେଣୀଟିରେ ସର୍ବମୋଟ $2 ରୁ ତଳେଥିବା ଏହି {{PLURAL:$1|subcategory|$1 ଶ୍ରେଣୀଗୁଡିକ}} ଅଛନ୍ତି  । }}',
'listingcontinuesabbrev'   => 'ଆହୁରି ଅଛି..',

'about'         => 'ବାବଦରେ',
'article'       => 'ସୂଚୀ ଫରଦ',
'newwindow'     => 'ନୂଆ ଉଇଁଡୋରେ ଖୋଲିବ',
'cancel'        => 'ନାକଚ କରିଦିଅ',
'moredotdotdot' => 'ଅଧିକ...',
'mypage'        => 'ମୋ ଫରଦ',
'mytalk'        => 'ମୋ ଆଲୋଚନା',
'anontalk'      => 'ଏହି ଆଇ.ପି. ଠିକଣା ଉପରେ ଆଲୋଚନା',
'navigation'    => 'ଦିଗବାରେଣି',
'and'           => '&#32;ଓ',

# Cologne Blue skin
'qbfind'         => 'ଖୋଜିବା',
'qbbrowse'       => 'ଖୋଜିବା',
'qbedit'         => 'ବଦଳାଇବା',
'qbpageoptions'  => 'ଏଇଟା ଫର୍ଦ',
'qbpageinfo'     => 'ଭିତର ଚିଜ',
'qbmyoptions'    => 'ମୋର ଫର୍ଦ',
'qbspecialpages' => 'ବିଶେଷ ଫରଦ',

# Vector skin
'vector-action-addsection'       => 'ଲେଖା ମିଶାଇବା',
'vector-action-delete'           => 'ଲିଭେଇବେ',
'vector-action-move'             => 'ଘୁଞ୍ଚାଇବେ',
'vector-action-protect'          => 'କିଳିବେ',
'vector-action-undelete'         => 'ଲିଭାଇବେ ନାହିଁ',
'vector-action-unprotect'        => 'କିଳିବେ ନାହିଁ',
'vector-simplesearch-preference' => 'ଆହୁରି ଅଧିକ ଖୋଜା ମତାମତ ଗୁଡ଼ିକ ସଚଳ କରିବେ (କେବଳ ଭେକ୍ଟର ସ୍କିନ)',
'vector-view-create'             => 'ତିଆରି',
'vector-view-edit'               => 'ବଦଳାଇବେ',
'vector-view-history'            => 'ଇତିହାସ ଦେଖ',
'vector-view-view'               => 'ପଢ଼ିବେ',
'vector-view-viewsource'         => 'ଉତ୍ସ ଦେଖ',
'namespaces'                     => 'ନେମସ୍ପେସ',

'errorpagetitle'   => 'ଭୁଲ',
'tagline'          => '{{SITENAME}} ରୁ',
'help'             => 'ସାହାଯ୍ୟ',
'search'           => 'ଖୋଜିବେ',
'searchbutton'     => 'ଖୋଜିବେ',
'go'               => 'ଯିବା',
'searcharticle'    => 'ଯିବା',
'history'          => 'ଫାଇଲ ଇତିହାସ',
'history_short'    => 'ଇତିହାସ',
'info_short'       => 'ଖବର',
'printableversion' => 'ଛପାହୋଇପାରିବା ପୃଷ୍ଠା',
'permalink'        => 'ସବୁଦିନିଆ ଲିଙ୍କ',
'print'            => 'ପ୍ରିଣ୍ଟ କରିବା',
'view'             => 'ଦେଖଣା',
'edit'             => 'ବଦଳାଇବେ',
'create'           => 'ତିଆରି କରିବେ',
'editthispage'     => 'ଏହି ଫରଦଟିକୁ ବଦଳାଇବା',
'create-this-page' => 'ଏହି ଫରଦଟି ତିଆରି କରିବା',
'delete'           => 'ଲିଭେଇବେ',
'deletethispage'   => 'ଏହି ଫରଦଟି ଲିଭାଇବା',
'protect'          => 'କିଳିବେ',
'protect_change'   => 'ବଦଳାଇବା',
'newpage'          => 'ନୂଆ ଫରଦ',
'talkpagelinktext' => 'କଥାଭାଷା',
'specialpage'      => 'ବିଶେଷ ଫରଦ',
'personaltools'    => 'ନିଜର ଟୁଲ',
'talk'             => 'ଆଲୋଚନା',
'views'            => 'ଦେଖା',
'toolbox'          => 'ଜନ୍ତ୍ର ପେଡ଼ି',
'otherlanguages'   => 'ଅଲଗା ଭାଷା',
'redirectedfrom'   => '($1 ରୁ ଲେଉଟି ଆସିଛି)',
'lastmodifiedat'   => 'ଏହି ଫରଦଟି $1 ତାରିଖ $2 ବେଳେ ବଦଳାଯାଇଥିଲା ।',
'jumpto'           => 'ଡେଇଁଯିବେ',
'jumptonavigation' => 'ଦିଗବାରେଣିକୁ',
'jumptosearch'     => 'ଖୋଜିବେ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ବାବଦରେ',
'aboutpage'            => 'Project:ବାବଦରେ',
'copyright'            => '$1 ରେ ସର୍ବସ୍ଵତ୍ଵ ସଂରକ୍ଷିତ',
'copyrightpage'        => '{{ns:project}}:କପିରାଇଟ',
'disclaimers'          => 'ଆମେ ଦାୟୀ ନୋହୁଁ',
'disclaimerpage'       => 'Project:ଆମେ ଦାୟୀ ନୋହୁଁ',
'edithelp'             => 'ଲେଖା ସାହାଜ୍ୟ',
'edithelppage'         => 'Help:ବଦଳାଇବା',
'mainpage'             => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'mainpage-description' => 'ମୂଳ ଫରଦ',
'portal'               => 'କମୁନିଟି ପୋଟାଲ',
'privacy'              => 'ଗୁମର ନୀତି',
'privacypage'          => 'Project:ଗୁମର ନୀତି',

'badaccess' => 'ଅନୁମତି ମିଳିବାରେ ଅସୁବିଧା',

'retrievedfrom'           => '"$1" ରୁ ଅଣାଯାଇଅଛି',
'youhavenewmessages'      => 'ଆପଣଙ୍କର $1 ($2).',
'newmessageslink'         => 'ନୂଆ ମେସେଜ',
'youhavenewmessagesmulti' => '$1 ତାରିଖରେ ନୂଆ ଚିଠିଟିଏ ଆସିଛି',
'editsection'             => 'ବଦଳାଇବେ',
'editold'                 => 'ବଦଳାଇବା',
'editlink'                => 'ବଦଳାଇବେ',
'viewsourcelink'          => 'ଉତ୍ସ ଦେଖ',
'editsectionhint'         => '$1 ଭାଗଟିକୁ ବଦଳାଇବେ',
'toc'                     => 'ଭିତର ଚିଜ',
'showtoc'                 => 'ଦେଖାଅ',
'hidetoc'                 => 'ଲୁଚାଅ',
'site-rss-feed'           => '$1 ଆରେସେସ ଫିଡ଼',
'site-atom-feed'          => '$1 ଆଟମ ଫିଡ଼',
'page-rss-feed'           => '$1 ଟି ଆରେସେସ ଫିଡ଼',
'page-atom-feed'          => '$1 ଟି ଆଟମ ଫିଡ଼',
'red-link-title'          => ' $1 (ଫରଦଟି ନାହିଁ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ପୃଷ୍ଠା',
'nstab-user'      => ' ଇଉଜର ଫରଦ',
'nstab-media'     => 'ମେଡିଆ ପରଦ',
'nstab-special'   => 'ବିଶେଷ ଫରଦ',
'nstab-project'   => 'ପ୍ରକଳ୍ପ ଫରଦ',
'nstab-image'     => 'ଫାଇଲ',
'nstab-mediawiki' => 'ଖବର',
'nstab-template'  => 'ଟେଁପଲେଟ',
'nstab-category'  => 'ବିଭାଗ',

# General errors
'error'           => 'ଭୁଲ',
'missing-article' => 'ଡାଟାବେସଟି ଆପଣ ଲେଖିଥିବା "$1" $2 ଶବଦଟି ପାଇଲା ନାହିଁ । .

ଯଦି ଆପଣ ଖୋଜିଥିବା ଫରଦଟି କେହି ଉଡ଼ାଇ ଦେଇଥାଏ ତେବେ ଏମିତି ହୋଇପାରେ ।

ଯଦି ସେମିତି ହୋଇନଥାଏ ତେବେ ଆପଣ ଏହି ସଫଟଉଏରରେ କିଛି ଅସୁବିଧା ଖୋଜି ପାଇଛଁତି ।
କେହି ଜଣେ ଟିକେ [[Special:ListUsers/sysop|ପରିଛା]] ଙ୍କୁ ଏହି ଇଉଆରେଲ (url) ସହ ଚିଠିଟିଏ ପଠାଇ ଦିଅଁତୁ ।',
'viewsource'      => 'ଉତ୍ସ ଦେଖ',

# Login and logout pages
'yourname'                => 'ଇଉଜର ନାଆଁ',
'yourpassword'            => 'ପାସଉଆଡ଼',
'login'                   => 'ଲଗଇନ',
'nav-login-createaccount' => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'loginprompt'             => "{{SITENAME}}ରେ ଲଗ ଇନ କରିବାପାଇଁ ଆପଣଙ୍କୁ '''କୁକି''' ସଚଳ କରିବାକୁ ପଡ଼ିବ ।",
'userlogin'               => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'userloginnocreate'       => 'ଲଗଇନ',
'logout'                  => 'ଲଗଆଉଟ',
'userlogout'              => 'ଲଗ ଆଉଟ',
'nologin'                 => 'ଖାତାଟିଏ ନାହିଁ? $1।',
'nologinlink'             => 'ନୁଆ ଖାତାଟିଏ ଖୋଲିବା',
'createaccount'           => 'ନୁଆ ଖାତା ଖୋଲିବା',
'gotaccountlink'          => 'ଲଗଇନ',
'createaccountreason'     => 'କାରଣ:',
'loginlanguagelabel'      => 'ଭାଷା: $1',

# Change password dialog
'oldpassword' => 'ପୂର୍ବ ପାସଉଆଡ଼:',
'newpassword' => 'ନୁଆ ପାସଉଆଡ଼:',

# Edit page toolbar
'bold_sample'     => 'ବୋଲ୍ଡ ଲେଖା',
'bold_tip'        => 'ବୋଲ୍ଡ ଲେଖା',
'italic_sample'   => 'ତେରେଛା ଲେଖା',
'italic_tip'      => 'ତେରେଛା ଲେଖା',
'link_sample'     => 'ଲିଁକ ଟାଇଟଲ',
'link_tip'        => 'ଭିତର ଲିଁକ',
'extlink_sample'  => 'http://www.example.com ଲିଁକ ଟାଇଟଲ',
'extlink_tip'     => 'ବାହାର ଲିଁକ (http:// ଆଗରେ ଲଗାଇବାକୁ ମନେରଖିଥିବେ)',
'headline_sample' => 'ହେଡଲାଇନ ଲେଖା',
'headline_tip'    => '୨କ ଆକାରର ମୂଳଧାଡ଼ି',
'nowiki_sample'   => 'ଫରମାଟ ହୋଇ ନଥିବା ଲେଖା ଏଠାରେ ପୁରାଅ',
'nowiki_tip'      => 'ଉଇକି ଫରମାଟିଁଗକୁ ଛାଡିଦିଅ',
'image_tip'       => 'ଏମବେଡ ହୋଇ ଥିବା ଫାଇଲ',
'media_tip'       => 'ଫାଇଲର ଲିଁକ',
'sig_tip'         => 'ଲେଖାର ବେଳ ସହ ଆପଣଁକ ହସ୍ତାକ୍ଷର',
'hr_tip'          => 'ସାମାଁତରାଳ ରେଖା (ବେଳେବେଳେ ବ୍ୟ୍ଅବହାର କର)',

# Edit pages
'summary'                => 'ସାରକଥା:',
'subject'                => 'ବିଷୟ/ମୂଳ ଲେଖା',
'minoredit'              => 'ଏହା ଖୁବ ଛୋଟ ବଦଳଟିଏ',
'watchthis'              => 'ଏହି ଫରଦଟିକୁ ଦେଖ',
'savearticle'            => 'ସାଇତି ରଖ',
'preview'                => 'ସାଇତିବା ଆଗରୁ ଦେଖଣା',
'showpreview'            => 'ଦେଖଣା',
'showdiff'               => 'ବଦଳଗୁଡିକୁ ଦେଖାଅ',
'anoneditwarning'        => "'''ଜାଣିରଖଁତୁ:''' ଆପଣ ଲଗଇନ କରିନାହାଁତି ।
ଏହି ଫରଦର '''ଇତିହାସ''' ଫରଦରେ ଆପଣଁକ ଆଇପି ଠିକଣାତି ସାଇତା ହୋଇଯିବ ।",
'loginreqlink'           => 'ଲଗଇନ',
'newarticle'             => '(ନୁଆ)',
'noarticletext'          => 'ଏହି ଫରଦଟିରେ କିଛି ବି ଲେଖା ନାହିଁ ।
ଆପଣ [[Special:Search/{{PAGENAME}}|ଏହି ଲେଖାଟିର ନାଆଁ]] ବାକି ଫରଦମାନଙ୍କରେ ଖୋଜି ପାରନ୍ତି,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|ଫରଦ={{FULLPAGENAMEE}}}} ରେ ଯୋଡ଼ାଯାଇଥିବା ବାକି ଫରଦସବୁକୁ ଖୋଜି ପାରନ୍ତି],
କିମ୍ବା [{{fullurl:{{FULLPAGENAME}}|action=edit}} ଏହି ଫରଦଟିକୁ ବଦଳାଇ ପାରନ୍ତି]</span> ।',
'editing'                => '$1 କୁ ବଦଳାଉଛି',
'editingsection'         => '$1 (ଭାଗ)କୁ ବଦଳାଇବେ',
'templatesused'          => 'ଏହି ଫରଦରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଟେମ୍ପଲେଟ|ଟେମ୍ପଲେଟସବୁ}}:',
'template-protected'     => '(କିଳାଯାଇଥିବା)',
'template-semiprotected' => '(ଅଧା କିଳାଯାଇଥିବା)',

# History pages
'viewpagelogs'           => 'ଏହି ଫରଦ ପାଇଁ ଲଗଗୁଡ଼ିକୁ ଦେଖନ୍ତୁ ।',
'currentrev-asof'        => '$1 ହୋଇଥିବା ରିଭିଜନ',
'revisionasof'           => '$1 ଉପରେ କରାଯାଇଥିବା ବଦଳ',
'previousrevision'       => 'ପୁରୁଣା ସଁକଳନ',
'cur'                    => 'ଦାନକର',
'next'                   => 'ପରେ',
'last'                   => 'ଆଗ',
'page_first'             => 'ପ୍ରଥମ',
'history-fieldset-title' => 'ଇତିହାସ ଖୋଜିବା',
'histfirst'              => 'ସବୁଠୁ ପୁରୁଣା',
'histlast'               => 'ନଗଦ',
'historyempty'           => '(ଖାଲି)',

# Revision deletion
'rev-delundel'   => 'ଦେଖାଇବା/ଲୁଚାଇବା',
'revdelete-log'  => 'କାରଣ:',
'revdel-restore' => 'ଦେଖଣାକୁ ବଦଳାଇବା',

# History merging
'mergehistory-reason' => 'କାରଣ:',

# Merge log
'revertmerge' => 'ମିଶାଇବା ନାହିଁ',

# Diffs
'history-title'           => '"$1" ପାଇଁ ସଁକଳନ ଇତିହାସ',
'difference'              => '(ରିଭିଜନ ଭିତରେ ଥିବା ତଫାତ)',
'lineno'                  => '$1 କ ଧାଡ଼ି:',
'compareselectedversions' => 'ବଛାହୋଇଥିବା ସଁକଳନ ଗୁଡ଼ିକୁ ତଉଲ',
'editundo'                => 'ପଛକୁ ଫେରିବା',

# Search results
'searchresults'             => 'ଖୋଜିବାରୁ ମିଳିଲା',
'searchresults-title'       => '"$1" ପାଇଁ ଖୋଜିବାରୁ ମିଳିଲା',
'searchresulttext'          => '{{SITENAME}} ରେ ଖୋଜିବା ବାବଦରେ ଅଧିକ ଜାଣିବା ପାଇଁ,  [[{{MediaWiki:Helppage}}|{{int:help}}]] ଦେଖନ୍ତୁ',
'searchsubtitle'            => 'ଆପଣ  \'\'\'[[:$1]]\'\'\' ପାଇଁ ([[Special:Prefixindex/$1|"$1" ରେ ଆରଭ ହୋଇଥିବା ସବୁ ଫରଦ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" କୁ ଯୋଡ଼ାଥିବା ସବୁତକ ପୃଷ୍ଠା]])',
'notitlematches'            => 'ଫରଦର ଟାଇଟଲ ମିଶୁନାହିଁ',
'notextmatches'             => 'ଫରଦର ଲେଖାସବୁ ମିଶୁନାହିଁ',
'prevn'                     => '{{PLURAL:$1|$1}}ର ଆଗରୁ',
'nextn'                     => '{{PLURAL:$1|$1}} ପର',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3) ଟି ଦେଖ',
'search-result-size'        => '$1 ({{PLURAL:$2|1 ଶବ୍ଦ|$2 ଶବ୍ଦ}})',
'search-redirect'           => '($1 କୁ ଆଗକୁ ବଢେଇନିଅ )',
'search-section'            => '(ଭାଗ $1)',
'search-suggest'            => 'ଆପଣ $1 ଭାବି ଖୋଜିଥିଲେ କି',
'search-interwiki-caption'  => 'ସାଙ୍ଗରେ ଚାଲିଥିବା ବାକି ପ୍ରକଳ୍ପସବୁ',
'search-interwiki-default'  => '$1 ଫଳାଫଳ:',
'search-interwiki-more'     => '(ଅଧିକ)',
'search-mwsuggest-enabled'  => 'ମତାମତ ସହ',
'search-mwsuggest-disabled' => 'ମତାମତ ନାହିଁ',
'searchall'                 => 'ସବୁ',
'nonefound'                 => "'''ଦେଖଁତୁ''': ଆପଣ ଖାଲି କିଛି ନେମସ୍ପେସକୁ ଆପେ ଆପେ ଖୋଜିପାରିବେ ।
ସବୁ ପ୍ରକାରର ଲେଖା (ଆଲୋଚନା ଫରଦ, ଟେଁପଲେଟ ହେରିକା) ଖୋଜିବା ପାଇଁ ନିଜ ପ୍ରଶ୍ନ ଆଗରେ ''all:'' ଯୋଡ଼ି ଖୋଜନ୍ତୁ, ନହେଲେ ଦରକାରି ନେମସ୍ପେସକୁ ପ୍ରିଫିକ୍ସ କରି ବ୍ୟବହାର କରନ୍ତୁ ।",
'powersearch'               => 'ଗହିର ଖୋଜା',
'powersearch-legend'        => 'ଗହିର ଖୋଜା',
'powersearch-ns'            => 'ନେମସ୍ପେସରେ ଖୋଜ',
'powersearch-redir'         => 'ପଛକୁ ଲେଉଟାଯାଇଥିବା ଲେଖାଗୁଡ଼ିକର ତାଲିକା',
'powersearch-field'         => 'ଖୋଜ',
'powersearch-toggleall'     => 'ସବୁ',
'powersearch-togglenone'    => 'କିଛି ନାହିଁ',

# Quickbar
'qbsettings-none' => 'କିଛି ନାହିଁ',

# Preferences page
'mypreferences'            => 'ମୋ ପସଁଦସବୁ',
'skin-preview'             => 'ଦେଖଣା',
'searchresultshead'        => 'ଖୋଜିବା',
'timezoneregion-africa'    => 'ଆଫ୍ରିକା',
'timezoneregion-america'   => 'ଆମେରିକା',
'timezoneregion-asia'      => 'ଏସିଆ',
'timezoneregion-australia' => 'ଅଷ୍ଟ୍ରେଲିଆ',
'timezoneregion-europe'    => 'ଇଉରୋପ',
'prefs-namespaces'         => 'ନେମସ୍ପେସ',
'prefs-files'              => 'ଫାଇଲ',
'yourlanguage'             => 'ଭାଷା:',

# User rights
'userrights-reason' => 'କାରଣ:',

# Groups
'group-sysop' => 'ପରିଛାଗଣ',

'grouppage-sysop' => '{{ns:project}}:ପରିଛା (ଆଡମିନ)',

# Recent changes
'recentchanges'                   => 'ନଗଦ ବଦଳ',
'recentchanges-legend'            => 'ଏବେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'recentchanges-feed-description'  => 'ଏହି ଫିଡ଼ଟିର ନଗଦ ବଦଳ ଦେଖାଁତୁ ।',
'recentchanges-label-newpage'     => 'ଏହି ବଦଳ ନୂଆ ଫରଦଟିଏ ତିଆରିକଲା',
'recentchanges-label-minor'       => 'ଏହା ଗୋଟିଏ ଛୋଟ ବଦଳ',
'recentchanges-label-bot'         => "ଏହି ବଦଳଟି ଜଣେ '''ବଟ'''ଙ୍କ ଦେଇ କରାଯାଇଥିଲା",
'recentchanges-label-unpatrolled' => 'ଏହି ବଦଳଟିକୁ ଏ ଯାଏଁ ପରଖା ଯାଇନାହିଁ',
'rclistfrom'                      => '$1ରୁ ଆରମ୍ଭ କରି ନୂଆ ବଦଳଗୁଡ଼ିକ ଦେଖାଅ',
'rcshowhideminor'                 => '$1 ଟି ଛୋଟମୋଟ ବଦଳ',
'rcshowhidebots'                  => '$1 ଜଣ ବଟ',
'rcshowhideliu'                   => '$1 ଜଣ ନାଆଁ ଲେଖାଇଥିବା ଇଉଜର',
'rcshowhideanons'                 => '$1 ଜଣ ଅଜଣା ଇଉଜର',
'rcshowhidemine'                  => '$1 ମୁଁ କରିଥିବା ବଦଳ',
'rclinks'                         => 'ଗଲା $2 ଦିନର $1 ବଦଳଗୁଡ଼ିକୁ ଦେଖାଅ<br />$3',
'diff'                            => 'ଅଦଳ ବଦଳ',
'hist'                            => 'ଇତିହାସ',
'hide'                            => 'ଲୁଚାଅ',
'show'                            => 'ଦେଖାଅ',
'minoreditletter'                 => 'ଟିକେ',
'newpageletter'                   => 'ନୂଆ',
'boteditletter'                   => 'ବଟ',
'rc-enhanced-expand'              => 'ପୁରା ଦେଖାଅ (ଜାଭାସ୍କ୍ରିପଟ ଦରକାର)',
'rc-enhanced-hide'                => 'ବେଶି କଥାସବୁ ଲୁଚାଇଦିଅ',

# Recent changes linked
'recentchangeslinked'          => 'ଏଇମାତ୍ର ବଦଳାଯାଇଥିବା ପୃଷ୍ଠାର ଲିଙ୍କ',
'recentchangeslinked-feed'     => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-toolbox'  => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-title'    => '"$1" ସାଁଗରେ ଜୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-noresult' => 'ଯୋଡ଼ାଯାଇଥିବା ଫରଦ ସବୁରେ ଏଇ ସମୟ ଭିତରେ କିଛି ବଦଳାଯାଇନାହିଁ ।',
'recentchangeslinked-summary'  => "ଏଇଟି ଅଳ୍ପସମୟ ଆଗରୁ ନିର୍ଦିଷ୍ଟ ଫରଦରୁ ଲିଂକ ହୋଇଥିବା ଆଉ ବଦଳାଯାଇଥିବା (ଅବା ଗୋଟିଏ ନିର୍ଦିଷ୍ଟ ବିଭାଗର) ଫରଦସବୁର ତାଲିକା ।  [[Special:Watchlist|ମୋର ଦେଖାତାଲିକା]]ର ଫରଦ ସବୁ '''ବୋଲଡ'''।",
'recentchangeslinked-page'     => 'ଫରଦର ନାଆଁ',
'recentchangeslinked-to'       => 'ଦିଆଯାଇଥିବା ଫରଦରେ ଯୋଡ଼ା ବାକି ଫରଦମାନଙ୍କର ବଦଳ ସବୁ ଦେଖାନ୍ତୁ ।',

# Upload
'upload'            => 'ଫାଇଲ ଅପଲୋଡ଼ କର',
'filedesc'          => 'ସାରକଥା',
'fileuploadsummary' => 'ସାରକଥା:',

# Special:ListFiles
'imgfile'        => 'ଫାଇଲ',
'listfiles_name' => 'ନାମ',

# File description page
'file-anchor-link'    => 'ଫାଇଲ',
'filehist'            => 'ଫାଇଲ ଇତିହାସ',
'filehist-help'       => 'ଏହା ଫାଇଲଟି ସେତେବେଳେ ଯେମିତି ଦିଶୁଥିଲା ତାହା ଦେଖିବା ପାଇଁ ତାରିଖ/ବେଳା ଉପରେ କ୍ଲିକ କରନ୍ତୁ',
'filehist-current'    => 'ଏବେକାର',
'filehist-datetime'   => 'ତାରିଖ/ବେଳ',
'filehist-thumb'      => 'ନଖ ଦେଖଣା',
'filehist-thumbtext'  => '$1 ପରିକା ସଁକଳନର ନଖଦେଖଣା',
'filehist-user'       => 'ଇଉଜର',
'filehist-dimensions' => 'ଆକାର',
'filehist-comment'    => 'ମତାମତ',
'imagelinks'          => 'ଫାଇଲର ଲିଁକସବୁ',
'linkstoimage'        => 'ଏହି ସବୁ{{PLURAL:$1|ଫରଦ|$1 ଫରଦମାନେ}} ଏହି ଫାଇଲଟିକୁ ଯୋଡ଼ିଥାନ୍ତି:',
'sharedupload'        => 'ଏହି ଫାଇଲଟି $1 ରୁ ଆଉ ବାକି ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର କରାଯାଇପାରିବ .',

# File reversion
'filerevert-comment' => 'କାରଣ:',

# File deletion
'filedelete-comment' => 'କାରଣ:',

# Random page
'randompage' => 'ଯାହିତାହି ଫରଦଟିଏ',

# Statistics
'statistics-pages' => 'ଫରଦ',

'brokenredirects-edit' => 'ବଦଳାଇବେ',

# Miscellaneous special pages
'nbytes'               => '$1 {{PLURAL:$1|byte|ବାଇଟ}}',
'nmembers'             => '$1 {{PLURAL:$1|member|ସଭ୍ୟ}}',
'wantedcategories'     => 'ଦରକାରି ବିଭାଗ',
'wantedpages'          => 'ଦରକାରି ଫରଦ',
'wantedpages-badtitle' => '$1 ଉତ୍ତରସବୁରେ ଥିବା ଭୁଲ ଟାଇଟଲ',
'wantedfiles'          => 'ଦରକାରି ଫାଇଲ',
'wantedtemplates'      => 'ଦରକାରି ଟେଁପଲେଟ',
'mostlinked'           => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଫରଦ',
'mostlinkedcategories' => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ବିଭାଗ',
'mostlinkedtemplates'  => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଟେଁପଲେଟ',
'mostcategories'       => 'ବେଶିବିଭାଗ ଥିବା ଫରଦ',
'mostimages'           => 'ଫାଇଲରେ ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଥିବା',
'prefixindex'          => 'ଆଗରୁ କିଛି ଯୋଡ଼ା ସହ ଥିବା ସବୁ ଫରଦସବୁ',
'move'                 => 'ଘୁଞ୍ଚାଇବେ',
'pager-newer-n'        => '{{PLURAL:$1|ନୂଆ 1|ନୂଆ $1}}',
'pager-older-n'        => '{{PLURAL:$1|ପୁରୁଣା 1|ପୁରୁଣା $1}}',

# Book sources
'booksources'    => 'ବହିର ମୁଳ ସ୍ରୋତ',
'booksources-go' => 'ଯିବା',

# Special:Log
'log' => 'ଲଗ',

# Special:AllPages
'allpages'       => 'ସବୁ ଫରଦ',
'alphaindexline' => '$1 ରୁ $2',
'allpagessubmit' => 'ଯିବା',

# Special:LinkSearch
'linksearch-ok' => 'ଖୋଜିବା',

# E-mail user
'emailuser'           => 'ଏହି ଉଇଜରଁକୁ ଇମେଲ କର',
'emailusernamesubmit' => 'ଦାଖଲକରିବା',
'emailmessage'        => 'ଖବର:',

# Watchlist
'watchlist'     => 'ଦେଖାତାଲିକା',
'mywatchlist'   => 'ମୋର ଦେଖାତାଲିକା',
'watch'         => 'ଦେଖ',
'watchthispage' => 'ଏହି ଫରଦଟିକୁ ଦେଖ',
'unwatch'       => 'ଦେଖନାହିଁ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ଦେଖୁଛି...',
'unwatching' => 'ଦେଖୁନାହିଁ...',

# Delete
'actioncomplete' => 'କାମଟି ପୁରା ହେଲା',
'deletedarticle' => '"[[$1]]" ଟି ଉଡ଼ିଗଲା',
'dellogpage'     => 'ଲିଭାଇବା ଲଗ',
'deletecomment'  => 'କାରଣ:',

# Rollback
'rollbacklink' => 'ପଛକୁ ଫେର',

# Protect
'protectlogpage'              => 'କିଳିବା ଲଗ',
'protectcomment'              => 'କାରଣ:',
'protectexpiry'               => 'ଅଚଳ ହେବ:',
'protect_expiry_invalid'      => 'ଅଚଳ ହେବାର ବେଳଟି ଭୁଲ.',
'protect_expiry_old'          => 'ଅଚଳ ହେବାର ବେଳ ଅତୀତରେ ଥିଲା.',
'protect-default'             => 'ସବୁ ଇଉଜରଁକୁ ଅନୁମତି ଦିଅ',
'protect-fallback'            => '"$1" ବାଲା ଅନୁମତି ଦରକାର',
'protect-level-autoconfirmed' => 'ନୁଆ ଓ ନାଆଁ ଲେଖାଇ ନ ଥିବା ଇଉଜରମାନକୁ ଅଟକାଁତୁ',
'protect-level-sysop'         => 'କେବଳ ପରିଛାମାନଁକ ପାଇଁ',
'protect-summary-cascade'     => 'କାସକେଡ଼ ହୋଇଥିବା',
'protect-expiring'            => '$1 (ଇଉଟିସି)ରେ ଅଚଳ ହୋଇଯିବ',
'protect-cascade'             => 'ଏହି ଫରଦସହ ଜୋଡ଼ା ଫରଦସବୁକୁ କିଳିଦିଅ (କାସକେଡ଼କରା ସୁରକ୍ଷା)',
'protect-cantedit'            => 'ଆପଣ ଏହି ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ, କାହିଁକି ନା ଏହାକୁ ବଦଳାଇବା ପାଇଁ ଆପଣଁକୁ ଅନୁମତି ନାହିଁ .',
'restriction-type'            => 'ଅନୁମତି',
'restriction-level'           => 'ପ୍ରତିରକ୍ଷା ସ୍ତର',

# Restrictions (nouns)
'restriction-edit'   => 'ବଦଳାଇବେ',
'restriction-create' => 'ତିଆରି',

# Undelete
'undeletelink'           => 'ଦେଖିବା/ଆଉଥରେ ଫେରାଇଆଣିବା',
'undeleteviewlink'       => 'ଦେଖଣା',
'undeletecomment'        => 'କାରଣ:',
'undelete-search-submit' => 'ଖୋଜିବା',

# Namespace form on various pages
'namespace'      => 'ନେମସ୍ପେସ',
'invert'         => 'ବଛାଯାଇଥିବା ଲେଖାକୁ ଓଲଟେଇପକାଅ',
'blanknamespace' => '(ମୂଳ)',

# Contributions
'contributions' => 'ଇଉଜରଙ୍କ ଦାନ',
'mycontris'     => 'ମୋ ଅବଦାନ',
'contribsub2'   => '$1 ($2) ପାଇଁ',
'month'         => 'ମାସରୁ (ଓ ତା ଆଗରୁ)',
'year'          => 'ବରସରୁ (ଓ ତା ଆଗରୁ)',

'sp-contributions-talk'   => 'କଥାଭାଷା',
'sp-contributions-submit' => 'ଖୋଜିବା',

# What links here
'whatlinkshere'            => 'ଏଠି କଣ କଣ ଲିଙ୍କ ଅଛି',
'whatlinkshere-title'      => '"$1" କୁ ଫରଦ ଲିଁକ',
'whatlinkshere-page'       => 'ଫରଦ',
'linkshere'                => "ଏହି ଫରଦ ସବୁ  '''[[:$1]]''' ସହ ଯୋଡା ଯାଇଅଛି:",
'isredirect'               => 'ଅଲଗା ଫରଦକୁ ଯୋଡା ଫରଦ',
'isimage'                  => 'ଚିତ୍ର ଲିଁକ',
'whatlinkshere-prev'       => '{{PLURAL:$1|ଆଗ|ଆଗ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ପର|ପର $1}}',
'whatlinkshere-links'      => '← ଲିଁକ',
'whatlinkshere-hideredirs' => '$1 କୁ ଲେଉଟାଣି',
'whatlinkshere-hidetrans'  => '$1 ରେଫେରେଁସ ସହ ଭିତରେ ପୁରାଇବା',
'whatlinkshere-hidelinks'  => '$1 ଟି ଲିଁକ',
'whatlinkshere-filters'    => 'ଫିଲଟରସବୁ',

# Block/unblock
'ipbreason'          => 'କାରଣ:',
'blocklist-reason'   => 'କାରଣ',
'ipblocklist-submit' => 'ଖୋଜିବା',
'blocklink'          => 'ଅଟକେଇ ଦିଅ',
'unblocklink'        => 'ଛାଡ଼ିବା',
'change-blocklink'   => 'ଓଗଳାକୁ ବଦଳାଇବା',
'contribslink'       => 'ଅବଦାନ',
'blocklogpage'       => 'ଲଗଟିକୁ ଅଟକାଇଦିଅ',

# Move page
'movelogpage' => 'ଲଗଟିକୁ ଘୁଞ୍ଚାଅ',
'movereason'  => 'କାରଣ:',
'revertmove'  => 'ପଛକୁ ଫେରାଇନିଅ',

# Export
'export' => 'ଫରଦସବୁ ରପ୍ତାନି କର',

# Thumbnails
'thumbnail-more' => 'ବଡ଼କର',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ଆପଣଁକ ଇଉଜର ଫରଦ',
'tooltip-pt-mytalk'               => 'ଆପଣଁକ ଆଲୋଚନା ଫରଦ',
'tooltip-pt-preferences'          => 'ମୋ ପସଁଦସବୁ',
'tooltip-pt-watchlist'            => 'ବଦଳ ପାଇଁ ଆପଣ ଦେଖାଶୁଣା କରୁଥିବା ଫରଦଗୁଡ଼ିକର ତାଲିକା',
'tooltip-pt-mycontris'            => 'ଆପଣଁକ ଅବଦାନ',
'tooltip-pt-login'                => 'ଆପଣଙ୍କୁ ଲଗିନ କରିବାକୁ କୁହାଯାଉଅଛି ସିନା, ବାଧ୍ୟ କରାଯାଉନାହିଁ',
'tooltip-pt-logout'               => 'ଲଗ ଆଉଟ',
'tooltip-ca-talk'                 => 'ଏହି ପୃଷ୍ଠାଟି ଉପରେ ଆଲୋଚନା',
'tooltip-ca-edit'                 => 'ଆପଣ ଏହି ପୃଷ୍ଠାଟିରେ ଅଦଳ ବଦଳ କରିପାରିବେ, ତେବେ ସାଇତିବା ଆଗରୁ ଦେଖଣା ଦେଖଁତୁ ।',
'tooltip-ca-addsection'           => 'ନୂଆ ନିର୍ଘଁଟଟିଏ ଆଁରଭ କରିବା',
'tooltip-ca-viewsource'           => 'ଏଇ ଫରଦଟି କିଳାଯାଇଛି ।
ଆପଣ ଏହାର ମୂଳ ଦେଖିପାରିବେ',
'tooltip-ca-history'              => 'ଏହି ଫରଦର ପୁରୁଣା ସଂସ୍କରଣ',
'tooltip-ca-move'                 => 'ଏଇ ଫରଦଟି ଘୁଁଚାଅ',
'tooltip-ca-watch'                => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ଫରଦଟି ମିଶାନ୍ତୁ',
'tooltip-ca-unwatch'              => 'ନିଜ ଦେଖଣାତାଲିକାରୁ ଏହି ଫରଦଟି ବାହାର କରିଦିଅଁତୁ',
'tooltip-search'                  => '{{SITENAME}} ରେ ଖୋଜିବା',
'tooltip-search-go'               => 'ଏହି ଅବିକଳ ନାଆଁଟି ଥିଲେ ସେହି ପୃଷ୍ଠାକୁ ଯିବା',
'tooltip-search-fulltext'         => 'ଏହି ଲେଖାଟି ପାଇଁ ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବା',
'tooltip-p-logo'                  => 'ପ୍ରଧାନ ପ୍ରୁଷ୍ଟ୍ଆ',
'tooltip-n-mainpage'              => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-mainpage-description'  => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'tooltip-n-portal'                => 'ଏହି ପ୍ରକଳ୍ପଟିରେ ଖୋଜା ଖୋଜି ପାଇଁ ଆପଣ କେମିତି ସାହାଯ୍ୟ କରିପାରିବେ',
'tooltip-n-currentevents'         => 'ନଗଦ କାମର ପଛପଟେ ଚାଲିଥିବା କାମର ତଥ୍ୟ',
'tooltip-n-recentchanges'         => 'ବିକିରେ ଏହିମାତ୍ର କରାଯାଇଥିବା ଅଦଳ ବଦଳ',
'tooltip-n-randompage'            => 'ଯାହିତାହି ପୃଷ୍ଠାଟିଏ ଖୋଲ',
'tooltip-n-help'                  => 'ଖୋଜି ପାଇବା ଭଳି ଜାଗା',
'tooltip-t-whatlinkshere'         => 'ଏଠାରେ ଯୋଡ଼ାଯାଇଥିବା ଫରଦସବୁର ତାଲିକା',
'tooltip-t-recentchangeslinked'   => 'ଏହି ଫରଦ ସାଗେ ଯୋଡ଼ା ଫରଦଗୁଡ଼ିକରେ ଏଇଲାଗେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'tooltip-feed-rss'                => 'ଏହି ଫରଦଟି ପାଇଁ ଆରଏସଏସ ଫିଡ',
'tooltip-feed-atom'               => 'ଏହି ଫରଦଟି ପାଇଁ ଆଟମ ଫିଡ',
'tooltip-t-contributions'         => 'ଏହି ଇଉଜରଙ୍କର ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ଦାନ ଦେଖାଇବା',
'tooltip-t-upload'                => 'ଫାଇଲ ଅପଲୋଡ଼ କର',
'tooltip-t-specialpages'          => 'ନିଆରା ପୃଷ୍ଠା ତାଲିକା',
'tooltip-t-print'                 => 'ଏହି ପୃଷ୍ଠାର ଛପାହୋଇପାରିବା ସଙ୍କଳନ',
'tooltip-t-permalink'             => 'ସଁଶୋଧିତ ଏହି ଫରଦଟିର ସ୍ଥାୟି ଲିଁକ',
'tooltip-ca-nstab-main'           => 'ସୂଚୀ ପୃଷ୍ଠାଟି ଦେଖିବା',
'tooltip-ca-nstab-user'           => 'ଫାଇଲ ଫରଦଗୁଡ଼ିକ ଦେଖଁତୁ',
'tooltip-ca-nstab-special'        => 'ଏଇଟି ଗୋଟିଏ ବିଶେଷ ଫରଦ, ଆପଣ ଏହାକୁ ବଦଳାଇପାରିବେ ନାହିଁ',
'tooltip-ca-nstab-project'        => 'ପ୍ରକଳ୍ପ ଫରଦଟି ଦେଖିବା',
'tooltip-ca-nstab-image'          => 'ଫାଇଲ ଫରଦଗୁଡ଼ିକ ଦେଖଁତୁ',
'tooltip-ca-nstab-category'       => 'ବିଭାଗ ଫରଦଟିକୁ ଖୋଲ',
'tooltip-minoredit'               => 'ଏହାକୁ ଛୋଟ ବଦଳ ଭାବେ ଗଣ',
'tooltip-save'                    => 'ବଦଳଗୁଡ଼ିକ ସାଇତିବେ',
'tooltip-preview'                 => 'ଆପଣନ୍କ ବଦଳ ଦେଖିନିଅନ୍ତୁ, ସାଇତିବା ଆଗରୁ ଏହା ବ୍ୟ୍ଅବହାର କରନ୍ତୁ!',
'tooltip-diff'                    => 'ଏହି ଲେଖାରେ ଆପଣ କରିଥିବା ବଦଳଗୁଡିକୁ ଦେଖନ୍ତୁ ।',
'tooltip-compareselectedversions' => 'ଏହି ଫରଦର ଦୁଇଟି ବଛାଯାଇଥିବା ସଁକଳନକୁ ତଉଲିବା',
'tooltip-watch'                   => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ଫରଦଟି ମିଶାନ୍ତୁ',
'tooltip-rollback'                => '"ଫେରିବା" ଏହି ଫରଦରେ ଶେଷ ଦାତାଙ୍କ ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ବଦଳକୁ  ଏକାଥରକରେ ପଛକୁ ଫେରାଇଦେବ',
'tooltip-undo'                    => '"କରନାହିଁ" ଆଗରୁ କରାଯାଇଥିବା ବଦଳଟିକୁ ପଛକୁ ଲେଉଟାଇଦିଏ ଆଉ ବଦଳ ଫରମଟିକୁ ଦେଖଣା ଭାବରେ ଖୋଲେ । ଏହା ଆପଣଙ୍କୁ ସାରକଥାରେ ଗୋଟିଏ କାରଣ ଲେଖିବାକୁ ଅନୁମତି ଦିଏ ।',

# Browsing diffs
'previousdiff' => '← ପୁରୁଣା ବଦଳ',

# Media information
'file-info-size' => '$1 × $2 ପିକସେଲ, ଫାଇଲ ଆକାର: $3, ଏମ.ଆଇ.ଏମ.ଇର ପ୍ରକାର: $4',
'file-nohires'   => '<small>ବଡ଼ ରେଜୋଲୁସନ ନାହିଁ ।</small>',
'show-big-image' => 'ପୁରା ବଡ଼ ଆକାରରେ',

# Special:NewFiles
'ilsubmit' => 'ଖୋଜିବା',

# Bad image list
'bad_image_list' => 'ଗଢ଼ଣଟି ଏମିତି ହେବ:

କେବଳ (ଯେଉଁ ଧାଡ଼ିଗୁଡ଼ିକ * ରୁ ଆରଭ ହୋଇଥାଏ) ସେହି ସବୁକୁ ହିସାବକୁ ନିଆଯିବ ।
ଗୋଟିଏ ଧାଡ଼ିର ପ୍ରଥମ ଲିଁକଟି ଗୋଟିଏ ଖରାପ ଫାଇଲର ଲିଁକ ହୋଇଥିବା ଦରକାର ।
ପ୍ରଥମ ଲିକ ପରର ସବୁ ଲିକକୁ ସ୍ଵତଁତ୍ର ବୋଲି ଧରାଯିବ । ମାନେ, ସେଇସବୁ ଫରଦରେ ଯେଉଁଠି ଫାଇଲଟି ଧାଡି ଭିତରେ ରହିଥିବ ।',

# Metadata
'metadata'          => 'ମେଟାଡାଟା',
'metadata-help'     => 'ଏହି ଫରଦଟିରେ ଗୁଡ଼ାଏ ଅଧିକ କଥା ଅଛି, ବୋଧହୁଏ ଡିଜିଟାଲ କାମେରା କିମ୍ବା ସ୍କାନରରେ ନିଆଯାଇଛି । ଯଦି ଫାଇଲଟି ତାର ମୂଳ ଭାଗଠୁ ବଦଳାଜାଇଥାଏ ତେବେ କିଛି ଅଁଶ ଠିକ ଭାବେ ଦେଖାଯାଇ ନପାରେ ।',
'metadata-expand'   => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',
'metadata-collapse' => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',

# External editor support
'edit-externally'      => 'ଏକ ବାହାର ଆପ୍ଲିକେସନ ବ୍ୟବହାର କରି ଏହି ଫାଇଲଟିକୁ ବଦଳାଇବା',
'edit-externally-help' => '(ଆହୁରି ବି [http://www.mediawiki.org/wiki/Manual:External_editors ସଜାଡିବା ନିର୍ଦେଶ] ଦେଖନ୍ତୁ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ସବୁ',
'namespacesall' => 'ସବୁ',
'monthsall'     => 'ସବୁ',
'limitall'      => 'ସବୁ',

# Special:FilePath
'filepath-page' => 'ଫାଇଲ:',

# Special:FileDuplicateSearch
'fileduplicatesearch-submit' => 'ଖୋଜିବା',

# Special:SpecialPages
'specialpages'             => 'ନିଆରା ପୃଷ୍ଠା',
'specialpages-group-login' => 'ଲଗିନ / ଖାତା ଖୋଲି',

# Special:Tags
'tags-edit' => 'ବଦଳାଇବେ',

# HTML forms
'htmlform-submit' => 'ଦାଖଲକରିବା',

);
