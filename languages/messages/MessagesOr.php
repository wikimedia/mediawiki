<?php
/** Oriya (ଓଡ଼ିଆ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ansumang
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
	NS_USER             => 'ବ୍ୟବାହାରକାରୀ',
	NS_USER_TALK        => 'ବ୍ୟବାହାରକାରୀଙ୍କ_ଆଲୋଚନା',
	NS_PROJECT_TALK     => 'ଉଇକିପିଡ଼ିଆ_ଆଲୋଚନା',
	NS_FILE             => 'ଫାଇଲ',
	NS_FILE_TALK        => 'ଫାଇଲ_ଆଲୋଚନା',
	NS_MEDIAWIKI        => 'ମିଡ଼ିଆଉଇକି',
	NS_MEDIAWIKI_TALK   => 'ମିଡ଼ିଆଉଇକି_ଆଲୋଚନା',
	NS_TEMPLATE         => 'ଟେମ୍ପଲେଟ',
	NS_TEMPLATE_TALK    => 'ଟେମ୍ପଲେଟ_ଆଲୋଚନା',
	NS_HELP             => 'ସାହାଯ୍ୟ',
	NS_HELP_TALK        => 'ସାହାଯ୍ୟ_ଆଲୋଚନା',
	NS_CATEGORY         => 'ଶ୍ରେଣୀ',
	NS_CATEGORY_TALK    => 'ଶ୍ରେଣୀ_ଆଲୋଚନା',
);

$namespaceAliases = array(
	'ବ୍ୟବହାରକାରି'          => NS_USER,
	'ବ୍ୟବହାରକାରିଁକ_ଆଲୋଚନା' => NS_USER_TALK,
	'ଟେଁପଲେଟ'             => NS_TEMPLATE,
	'ଟେଁପଲେଟ_ଆଲୋଚନା'     => NS_TEMPLATE_TALK,
	'ବିଭାଗ'                => NS_CATEGORY,
	'ବିଭାଗିୟ_ଆଲୋଚନା'      => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'ସଚଳ_ଇଉଜର' ),
	'Allmessages'               => array( 'ସବୁ_ମେସେଜ' ),
	'Allpages'                  => array( 'ସବୁ_ପୃଷ୍ଠା' ),
	'Ancientpages'              => array( 'ପୁରୁଣା_' ),
	'Blankpage'                 => array( 'ଖାଲି_ପୃଷ୍ଠା' ),
	'Block'                     => array( 'ଅଟକାଇଦେବେ', 'ଆଇପି_ଅଟକାଇଦେବେ', 'ଇଉଜରକୁ_ଅଟକାଇଦେବେ' ),
	'Blockme'                   => array( 'ମୋତେ_ଅଟକାଇଦିଅନ୍ତୁ' ),
	'Booksources'               => array( 'ଲେଖା_ନିଆଯାଇଥିବା_ବହି' ),
	'BrokenRedirects'           => array( 'ଭଙ୍ଗା_ଲେଉଟାଣି' ),
	'Categories'                => array( 'ଶ୍ରେଣୀ' ),
	'ChangeEmail'               => array( 'ଇମେଲ_ବଦଳାଇବେ' ),
	'ChangePassword'            => array( 'ପାସବାର୍ଡ଼_ବଦଳାଇବେ' ),
	'ComparePages'              => array( 'ପୃଷ୍ଠାକୁ_ତଉଲ' ),
	'Confirmemail'              => array( 'ଇମେଲ_ଥୟ_କରିବେ' ),
	'Contributions'             => array( 'ଅବଦାନ' ),
	'CreateAccount'             => array( 'ଖାତା_ଖୋଲିବେ' ),
	'Deadendpages'              => array( 'ଆଗକୁ_ରାହା_ନଥିବା_ପୃଷ୍ଠା' ),
	'DeletedContributions'      => array( 'ହଟାଇ_ଦିଆଯାଇଥିବା_ଅବଦାନ' ),
	'Disambiguations'           => array( 'ଆବୁରୁଜାବୁରୁ_କଥା' ),
	'DoubleRedirects'           => array( 'ଦୁଇଥର_ଲେଉଟାଣି' ),
	'Emailuser'                 => array( 'ସଭ୍ୟଙ୍କୁ_ମେଲ_କରନ୍ତୁ' ),
	'Export'                    => array( 'ରପ୍ତାନି_କରିବା' ),
	'Fewestrevisions'           => array( 'ସବୁଠୁ_କମ_ସଙ୍କଳନ' ),
	'FileDuplicateSearch'       => array( 'ନକଲି_ଫାଇଲ_ଖୋଜା' ),
	'Filepath'                  => array( 'ଫାଇଲରାସ୍ତା' ),
	'Import'                    => array( 'ଆମଦାନି' ),
	'Invalidateemail'           => array( 'କାମକରୁନଥିବା_ଇମେଲ' ),
	'BlockList'                 => array( 'ତାଲିକାକୁ__ଅଟକାଇଦେବା' ),
	'LinkSearch'                => array( 'ଲିଙ୍କ_ଖୋଜା' ),
	'Listadmins'                => array( 'ପରିଛା_ତାଲିକା' ),
	'Listbots'                  => array( 'ବଟ_ମାନଙ୍କ_ତାଲିକା' ),
	'Listfiles'                 => array( 'ଫାଇଲ_ତାଲିକା' ),
	'Listgrouprights'           => array( 'ଗୋଠ_ନିୟମ_ତାଲିକା' ),
	'Listredirects'             => array( 'ଲେଉଟାଣି_ତାଲିକା' ),
	'Listusers'                 => array( 'ଇଉଜର_ତାଲିକା' ),
	'Lockdb'                    => array( 'ଡାଟାବେସ‌_କିଳିଦେବା' ),
	'Log'                       => array( 'ଲଗ' ),
	'Lonelypages'               => array( 'ଏକୁଟିଆ_ପୃଷ୍ଠା' ),
	'Longpages'                 => array( 'ଲମ୍ବା_ପୃଷ୍ଠା' ),
	'MergeHistory'              => array( 'ଇତିହାସକୁ_ମିଶାଇଦେବା' ),
	'MIMEsearch'                => array( 'MIME_ଖୋଜା' ),
	'Mostcategories'            => array( 'ଅଧିକ_ଶ୍ରେଣୀ' ),
	'Mostimages'                => array( 'ଅଧିକ_ଯୋଡ଼ା_ଫାଇଲ' ),
	'Mostlinked'                => array( 'ଅଧିକ_ଯୋଡ଼ା_ପୃଷ୍ଠା' ),
	'Mostlinkedcategories'      => array( 'ଅଧିକ_ଯୋଡ଼ା_ଶ୍ରେଣୀ' ),
	'Mostlinkedtemplates'       => array( 'ଅଧିକ_ଯୋଡ଼ା_ଟେମ୍ପଲେଟ' ),
	'Mostrevisions'             => array( 'ବେଶି_ସଙ୍କଳନ' ),
	'Movepage'                  => array( 'ପୃଷ୍ଠାକୁ_ଘୁଞ୍ଚାଇବା' ),
	'Mycontributions'           => array( 'ମୋ_ଅବଦାନ' ),
	'Mypage'                    => array( 'ମୋ_ପୃଷ୍ଠା' ),
	'Mytalk'                    => array( 'ମୋ_ଆଲୋଚନା' ),
	'Myuploads'                 => array( 'ମୋ_ଅପଲୋଡ' ),
	'Newimages'                 => array( 'ନୂଆ_ଫାଇଲ' ),
	'Newpages'                  => array( 'ନୂଆ_ପୃଷ୍ଠା' ),
	'PermanentLink'             => array( 'ଚିରକାଳର_ଲିଙ୍କ' ),
	'Popularpages'              => array( 'ଜଣାଶୁଣା_ପୃଷ୍ଠା' ),
	'Preferences'               => array( 'ପସନ୍ଦସବୁ' ),
	'Prefixindex'               => array( 'ଆଗରେ_ଯୋଡ଼ାହେବା_ଇଣ୍ଡେକ୍ସ' ),
	'Protectedpages'            => array( 'କିଳାଯାଇଥିବା_ପୃଷ୍ଠା' ),
	'Protectedtitles'           => array( 'କିଳାଯାଇଥିବା_ନାଆଁ' ),
	'Randompage'                => array( 'ଇଆଡୁ_ଇଆଡୁ' ),
	'Randomredirect'            => array( 'ଜାହିତାହି_ଫେରଣା' ),
	'Recentchanges'             => array( 'ନଗଦ_ବଦଳ' ),
	'Recentchangeslinked'       => array( 'ଜୋଡ଼ାଥିବା_ନଗଦ_ବଦଳ' ),
	'Revisiondelete'            => array( 'ସଙ୍କଳନଲିଭାଇଦିଅ' ),
	'RevisionMove'              => array( 'ସଙ୍କଳନ' ),
	'Search'                    => array( 'ଖୋଜନ୍ତୁ' ),
	'Shortpages'                => array( 'ଛୋଟ_ପୃଷ୍ଠା' ),
	'Specialpages'              => array( 'ବିଶେଷ_ପୃଷ୍ଠା' ),
	'Statistics'                => array( 'ଗଣନା' ),
	'Tags'                      => array( 'ଟାଗ' ),
	'Unblock'                   => array( 'ଫିଟାଇଦିଅ' ),
	'Uncategorizedcategories'   => array( 'ଅସଜଡ଼ା_ଶ୍ରେଣୀ' ),
	'Uncategorizedimages'       => array( 'ସଜଡ଼ା_ଶ୍ରେଣୀର_ଫାଇଲ' ),
	'Uncategorizedpages'        => array( 'ଅସଜଡ଼ା_ଫାଇଲସବୁ' ),
	'Uncategorizedtemplates'    => array( 'ଅସଜଡ଼ା_ଟେମ୍ପଲେଟ' ),
	'Undelete'                  => array( 'ଲିଭାଅ_ନାହିଁ' ),
	'Unlockdb'                  => array( 'DBଖୋଲିବା' ),
	'Unusedcategories'          => array( 'ବ୍ୟବହାର_ହୋଇନଥିବା_ଶ୍ରେଣୀ' ),
	'Unusedimages'              => array( 'ବ୍ୟବହାର_ହୋଇନଥିବା_ଫାଇଲ' ),
	'Unusedtemplates'           => array( 'ବ୍ୟବହାର_ହୋଇନଥିବା_ଟେମ୍ପଲେଟ' ),
	'Unwatchedpages'            => array( 'ଦେଖାଯାଇନଥିବା_ପୃଷ୍ଠାସବୁ' ),
	'Upload'                    => array( 'ଅପଲୋଡ଼' ),
	'UploadStash'               => array( 'ଷ୍ଟାସ_ଅପଲୋଡ଼_କରନ୍ତୁ' ),
	'Userlogin'                 => array( 'ଇଉଜର_ଲଗ_ଇନ' ),
	'Userlogout'                => array( 'ଇଉଜର_ଲଗ_ଆଉଟ' ),
	'Userrights'                => array( 'ଇଉଜର_ଅଧିକାର' ),
	'Version'                   => array( 'ଭରସନ' ),
	'Wantedcategories'          => array( 'ଦରକାରି_ଶ୍ରେଣୀ' ),
	'Wantedfiles'               => array( 'ଦରକାରି_ଫାଇଲ' ),
	'Wantedpages'               => array( 'ଦରକାରି_ପୃଷ୍ଠା' ),
	'Wantedtemplates'           => array( 'ଦରକାରି_ଟେମ୍ପଲେଟ' ),
	'Watchlist'                 => array( 'ଦେଖଣା_ତାଲିକା' ),
	'Whatlinkshere'             => array( 'ଏଠାରେ_କଣ_ଲିଁକ_ଅଛି' ),
	'Withoutinterwiki'          => array( 'ଇଣ୍ଟରଉଇକି_ବିନା' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#ଲେଉଟାଣି', '#REDIRECT' ),
	'noeditsection'         => array( '0', '_ବଦଳା_ନହେବାଶ୍ରେଣୀ_', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'ଏବେକାର_ମାସ', 'ଏବେର_ମାସ୨', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'ଏବେର_ମାସ', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'ଏବେକାର_ମାସ_ନାଆଁ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'ଏବେକାର_ମାସ_ନାଆଁ_ସାଧାରଣ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'ଏବେକାର_ମାସ_ନାଆଁ_ସଂକ୍ଷିପ୍ତ', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'ଏବେକାର_ଦିନ', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'ଏବେକାର_ଦିନ୨', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'ଏବେକାର_ଦିନ_ନାଆଁ', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ଏବେକାର_ବର୍ଷ', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'ଏବେକାର_ସମୟ', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ଏବେକାର_ଘଣ୍ଟା', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'ଏବେର_ମାସ୧', 'ସ୍ଥାନୀୟ_ମାସ୨', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'ଏବେକାର_ମାସ୧', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'ମାସ୧ର_ନାଆଁ', 'LOCALMONTHNAME' ),
	'localmonthnamegen'     => array( '1', 'ସ୍ଥାନୀୟ_ମାସ୧_ନାଆଁ_ସାଧାରଣ', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'      => array( '1', 'ସ୍ଥାନୀୟ_ମାସର୧_ନାଆଁ_ସଂକ୍ଷିପ୍ତ', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'Local_ଦିନ', 'LOCALDAY' ),
	'localday2'             => array( '1', 'ସ୍ଥାନୀୟ_ଦିନ୨', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'ଦିନ', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ସ୍ଥାନୀୟ_ବର୍ଷ', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'ସ୍ଥାନୀୟ_ସମୟ', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ସ୍ଥାନୀୟ_ଘଣ୍ଟା', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'ପୃଷ୍ଠା_ସଂଖ୍ୟା', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ଲେଖା_ସଂଖ୍ୟା', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ଫାଇଲ_ସଂଖ୍ୟା', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'ବ୍ୟବାହାରକାରୀ_ସଂଖ୍ୟା', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'ସଚଳ_ବ୍ୟବାହାରକାରୀଙ୍କ_ସଂଖ୍ୟା', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'ବଦଳ_ସଂଖ୍ୟା', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'କେତେଥର_ଦେଖାଯାଇଛି', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'ପୃଷ୍ଠା_ନାଆଁ', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'ପୃଷ୍ଠା_ନାମକାରଣକାରୀ', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'ନେମସ୍ପେସ', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ନେମସ୍ପେସକାରୀ', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'ଟକସ୍ପେସ', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'ଟକସ୍ପେସକାରୀ', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'ବିଷୟସ୍ପେସ', 'ଲେଖାସ୍ପେସ', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'msg'                   => array( '0', 'ମେସେଜ:', 'MSG:' ),
	'img_manualthumb'       => array( '1', 'ନଖଦେଖଣା=$1', 'ଦେଖଣା=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'ଡାହାଣ', 'right' ),
	'img_left'              => array( '1', 'ବାଆଁ', 'left' ),
	'img_none'              => array( '1', 'କିଛି_ନୁହେଁ', 'none' ),
	'img_width'             => array( '1', '$1_ପିକସେଲ', '$1px' ),
	'img_center'            => array( '1', 'କେନ୍ଦ୍ର', 'center', 'centre' ),
	'img_framed'            => array( '1', 'ଫ୍ରେମକରା', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'ଫ୍ରେମନଥିବା', 'frameless' ),
	'img_border'            => array( '1', 'ବର୍ଡର', 'border' ),
	'img_baseline'          => array( '1', 'ବେସଲାଇନ', 'baseline' ),
	'img_top'               => array( '1', 'ଉପର', 'top' ),
	'img_text_top'          => array( '1', 'ଲେଖା-ଉପର', 'text-top' ),
	'img_middle'            => array( '1', 'ମଝି', 'middle' ),
	'img_bottom'            => array( '1', 'ତଳ', 'bottom' ),
	'img_text_bottom'       => array( '1', 'ଲେଖା-ତଳ', 'text-bottom' ),
	'img_link'              => array( '1', 'ଲିଙ୍କ=$1', 'link=$1' ),
	'articlepath'           => array( '0', 'ଲେଖାର_ପଥ', 'ARTICLEPATH' ),
	'server'                => array( '0', 'ସର୍ଭର', 'SERVER' ),
	'grammar'               => array( '0', 'ବ୍ୟାକରଣ', 'GRAMMAR:' ),
	'gender'                => array( '0', 'ଲିଙ୍ଗ', 'GENDER:' ),
	'plural'                => array( '0', 'ବହୁବଚନ:', 'PLURAL:' ),
	'raw'                   => array( '0', 'କଞ୍ଚା', 'RAW:' ),
	'displaytitle'          => array( '1', 'ଦେଖଣାନାଆଁ', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '_ନୂଆବିଭାଗଲିଙ୍କ_', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '_ନୂଆ_ବିଭାଗ_ନକରିବା_ଲିଙ୍କ_', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'ନଗଦ_ରିଭିଜନ', 'CURRENTVERSION' ),
	'numberofadmins'        => array( '1', 'ପରିଛାମାନଙ୍କତାଲିକା', 'NUMBEROFADMINS' ),
	'padleft'               => array( '0', 'ବାଆଁପ୍ୟାଡ଼', 'PADLEFT' ),
	'padright'              => array( '0', 'ଡାହାଣପ୍ୟାଡ଼', 'PADRIGHT' ),
	'special'               => array( '0', 'ବିଶେଷ', 'special' ),
	'filepath'              => array( '0', 'ଫାଇଲରାହା:', 'FILEPATH:' ),
	'tag'                   => array( '0', 'ଟାଗ', 'tag' ),
	'hiddencat'             => array( '1', '_ଲୁଚିଥିବାବିଭାଗ_', '__HIDDENCAT__' ),
	'pagesize'              => array( '1', 'ଫରଦଆକାର', 'PAGESIZE' ),
	'protectionlevel'       => array( '1', 'ପ୍ରତିରକ୍ଷାସ୍ତର', 'PROTECTIONLEVEL' ),
	'formatdate'            => array( '0', 'ତାରିଖରପ୍ରକାର', 'formatdate', 'dateformat' ),
	'url_path'              => array( '0', 'ବାଟ', 'PATH' ),
	'url_wiki'              => array( '0', 'ଉଇକି', 'WIKI' ),
	'url_query'             => array( '0', 'ପ୍ରଶ୍ନ', 'QUERY' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'ତଳେ ଥିବା ଲିଙ୍କ:',
'tog-highlightbroken'         => '<a href="" class="new"> ଭଳି</a> (ବିକଳ୍ପ: <a href="" class="internal">?</a> ଭଳି) ଭଙ୍ଗା ଲିଙ୍କସବୁକୁ ସଜାଡ଼ିବେ',
'tog-justify'                 => 'ପାରାଗ୍ରାଫଗୁଡ଼ିକର ବାଆଁ ଡାହାଣ ସମଭାବେ ସଜାଡ଼ିବେ',
'tog-hideminor'               => 'ଛୋଟ ଛୋଟ ନଗଦ ବଦଳ ସବୁକୁ ଲୁଚାଇବେ',
'tog-hidepatrolled'           => 'ନଗଦ ବଦଳରେ ଥିବା ଜଗାହୋଇଥିବା ବଦଳ ସବୁକୁ ଲୁଚାଇବେ',
'tog-newpageshidepatrolled'   => 'ନୂଆ ପୃଷ୍ଠାତାଲିକାରୁ ଜଗାହୋଇଥିବା ବଦଳସବୁକୁ ଲୁଚାଇବେ',
'tog-extendwatchlist'         => 'କେବଳ ନଗଦ ନୁହେଁ, ସବୁଯାକ ବଦଳକୁ ଦେଖାଇବା ନିମନ୍ତେ ଦେଖଣାତାଲିକାକୁ ବଢ଼ାଇବେ',
'tog-usenewrc'                => 'ଉନ୍ନତ ନଗଦ ବଦଳ ବ୍ୟବହାର କରନ୍ତୁ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-numberheadings'          => 'ଆପେଆପେ-ସଂଖ୍ୟାର ନାମଗୁଡ଼ିକ',
'tog-showtoolbar'             => 'ସମ୍ପାଦନା ଟୁଲବାର ଦେଖାଇବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ସଚଳ କରିବେ)',
'tog-editondblclick'          => 'ଦୁଇଥର କ୍ଲିକରେ ପୃଷ୍ଠା ବଦଳାଇବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-editsection'             => '[ବଦଳାଇବେ] ଲିଙ୍କରେ ବିଭାଗର ସମ୍ପାଦନାକୁ ସଚଳ କରିବେ',
'tog-editsectiononrightclick' => 'ବିଭାଗ ନାମରେ ଡାହାଣ କ୍ଲିକ କରି ବିଭାଗ ସମ୍ପାଦନାକୁ ସଚଳ କରିବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-showtoc'                 => 'ସୂଚୀପତ୍ର ଦେଖାଇବେ (୩ରୁ ଅଧିକ ମୁଖ୍ୟ ନାମ ଥିଲେ)',
'tog-rememberpassword'        => 'ଏହି ବ୍ରାଉଜରରେ (ସବୁଠୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|day|ଦିନ}}) ପାଇଁ ମୋ ଲଗଇନ ମନେ ରଖିଥିବେ',
'tog-watchcreations'          => 'ମୋ ତିଆରି ପୃଷ୍ଠାସବୁକୁ ମୋର ଦେଖଣାତାଲିକା ଭିତରେ ରଖିବେ',
'tog-watchdefault'            => 'ମୋ ଦେଇ ସମ୍ପାଦିତ ପୃଷ୍ଠାସବୁକୁ ମୋର ଦେଖଣାତାଲିକା ଭିତରେ ରଖିବେ',
'tog-watchmoves'              => 'ମୋ ଦେଇ ଘୁଞ୍ଚାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ମୋର ଦେଖଣାତାଲିକା ଭିତରେ ରଖିବେ',
'tog-watchdeletion'           => 'ମୋ ଦେଇ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ମୋର ଦେଖଣାତାଲିକା ଭିତରେ ରଖିବେ',
'tog-minordefault'            => 'ସବୁଯାକ ସମ୍ପାଦନାକୁ ଛାଏଁ ଟିକେ ବଦଳ ଭାବରେ ସୂଚିତ କରିବେ',
'tog-previewontop'            => 'ଏଡ଼ିଟ ବାକ୍ସ ଆଗରୁ ଦେଖଣା ଦେଖାଇବେ',
'tog-previewonfirst'          => 'ପ୍ରଥମ ବଦଳର ଦେଖଣା ଦେଖାଇବେ',
'tog-nocache'                 => 'ବ୍ରାଉଜର ପୃଷ୍ଠା ସଂରକ୍ଷଣକୁ ଅଚଳ କରିବେ',
'tog-enotifwatchlistpages'    => 'ମୋ ଦେଖଣାତାଲିକାରେ ଥିବା ପୃଷ୍ଠାରେ କିଛି ବଦଳ ହେଲେ ମୋତେ ଇ-ମେଲ କରିବେ',
'tog-enotifusertalkpages'     => 'ମୋର ଆଲୋଚନା ପୃଷ୍ଠାରେ କିଛି ବଦଳ ହେଲେ ମୋତେ ଇ-ମେଲ କରିବେ',
'tog-enotifminoredits'        => 'ପୃଷ୍ଠାରେ ଛୋଟ ଛୋଟ ବଦଳ ହେଲେ ବି ମୋତେ ଇ-ମେଲ କରିବେ',
'tog-enotifrevealaddr'        => 'ସୂଚନା ଇ-ମେଲ ରେ ମୋର ଇ-ମେଲ ଠିକଣା ଦେଖାଇବେ',
'tog-shownumberswatching'     => 'ଦେଖୁଥିବା ବ୍ୟବାହାରକାରୀଙ୍କ ସଂଖ୍ୟା ଦେଖାଇବେ',
'tog-oldsig'                  => 'ଏବେ ଥିବା ନାମ:',
'tog-fancysig'                => 'ଦସ୍ତଖତକୁ ଉଇକିଟେକ୍ସଟ ଭାବରେ ଗଣିବେ (ଆପେଆପେ ଥିବା ଲିଙ୍କ ବିନା)',
'tog-externaleditor'          => 'ବାହାର ସମ୍ପାଦକଟି ଆପଣାଛାଏଁ ବ୍ୟବହାର କରିବେ (କେବଳ ପଟୁ ସଭ୍ୟଙ୍କ ପାଇଁ, ଏଥି ନିମନ୍ତେ ଆପଣଙ୍କ କମ୍ପୁଟରରେ ବିଶେଷ ସଜାଣି ଲୋଡ଼ା । [http://www.mediawiki.org/wiki/Manual:External_editors ଅଧିକ ସୂଚନା])',
'tog-externaldiff'            => 'ବାହାର ବାଛିବା (external diff) ଆପଣାଛାଏଁ ବ୍ୟବହାର କରିବେ (କେବଳ ପଟୁ ସଭ୍ୟଙ୍କ ପାଇଁ, ଏଥି ନିମନ୍ତେ ଆପଣଙ୍କ କମ୍ପୁଟରରେ ବିଶେଷ ସଜାଣି ଲୋଡ଼ା । [http://www.mediawiki.org/wiki/Manual:External_editors ଅଧିକ ସୂଚନା])',
'tog-showjumplinks'           => '"ଡେଇଁଯିବେ" ଲିଙ୍କସବୁକୁ ସଚଳ କରିବେ',
'tog-uselivepreview'          => 'ସାଥେ ସାଥେ ଚାଲିଥିବା ଦେଖଣା ବ୍ୟବହାର କରିବେ (ଜାଭାସ୍କ୍ରିପ୍ଟ ଲୋଡ଼ା)',
'tog-forceeditsummary'        => 'ଖାଲି ସମ୍ପାଦନା ସାରକଥାକୁ ଯିବା ବେଳେ ମୋତେ ଜଣାଇବେ',
'tog-watchlisthideown'        => 'ମୋ ଦେଖଣା ତାଲିକାରେ ମୋ ନିଜର ସମ୍ପାଦନାଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthidebots'       => 'ଦେଖଣା ତାଲିକାରେ ବଟ ଦେଇ ବଦଳ ଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthideminor'      => 'ଦେଖଣା ତାଲିକାରେ ଛୋଟ ଛୋଟ ବଦଳ ଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthideliu'        => 'ଲଗ ଇନ କରିଥିବା ସଭ୍ୟମାନଙ୍କ ଦେଇ କରାହୋଇଥିବା ବଦଳଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthideanons'      => 'ଅଜଣା ସଭ୍ୟମାନଙ୍କ ଦେଇ କରାହୋଇଥିବା ବଦଳଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-watchlisthidepatrolled'  => 'ମୋ ଦେଖଣା ତାଲିକାରୁ ଜଗାଯାଇଥିବା ସମ୍ପାଦନାଗୁଡ଼ିକ ଲୁଚାଇବେ',
'tog-ccmeonemails'            => 'ମୁଁ ଯେଉଁ ଇ-ମେଲ ସବୁ ଅନ୍ୟମାନଙ୍କୁ ପଠାଉଛି ସେସବୁର ନକଲ ମୋତେ ପଠାଇବେ ।',
'tog-diffonly'                => 'ତୁଳନା ତଳେ ପୃଷ୍ଠାର ଭିତର ଭାଗ ଦେଖାନ୍ତୁ ନାହିଁ',
'tog-showhiddencats'          => 'ଲୁଚାଯାଇଥିବା ଶ୍ରେଣୀଗୁଡ଼ିକ ଦେଖାଇବେ',
'tog-norollbackdiff'          => 'ରୋଲବ୍ୟାକ କଲାପରେ ତୁଳନା ଦେଖାନ୍ତୁ ନାହିଁ',

'underline-always'  => 'ସବୁବେଳେ',
'underline-never'   => 'କେବେନୁହେଁ',
'underline-default' => 'ବ୍ରାଉଜରରେ ଅଗରୁ ଥିବା ସୁବିଧା',

# Font style option in Special:Preferences
'editfont-style'     => 'ଫଣ୍ଟ ଶୈଳୀକୁ ବଦଳାଇବେ:',
'editfont-default'   => 'ବ୍ରାଉଜରରେ ଅଗରୁ ଥିବା ସୁବିଧା',
'editfont-monospace' => 'ମନୋସ୍ପେସ ଥିବା ଫଣ୍ଟ',
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
'february'      => 'ଫେବ୍ରୁଆରି',
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
'pagecategories'                 => '{{PLURAL:$1|Category|ଶ୍ରେଣୀସବୁ}}',
'category_header'                => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'subcategories'                  => 'ସାନ ଶ୍ରେଣୀସବୁ',
'category-media-header'          => '"$1" ବିଭାଗରେ ଥିବା ଫରଦଗୁଡ଼ିକ',
'category-empty'                 => "''ଏହି ଶ୍ରେଣୀ ଭିତରେ କିଛି ପୃଷ୍ଠା ବା ମାଧ୍ୟମ ନାହିଁ ।''",
'hidden-categories'              => '{{PLURAL:$1|Hidden category|ଲୁଚିଥିବା ଶ୍ରେଣୀ}}',
'hidden-category-category'       => 'ଲୁଚିରହିଥିବା ଶ୍ରେଣୀ',
'category-subcat-count'          => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀଟିରେ କେବଳ ତଳେଥିବା ସାନ ଶ୍ରେଣୀଗୁଡିକ ଅଛନ୍ତି । |ଏହି ଶ୍ରେଣୀଟିରେ ସର୍ବମୋଟ $2 ରୁ ତଳେଥିବା ଏହି {{PLURAL:$1|subcategory|$1 ଶ୍ରେଣୀଗୁଡିକ}} ଅଛନ୍ତି  । }}',
'category-subcat-count-limited'  => 'ଏହି ଶ୍ରେଣୀ ଅଧୀନରେ ତଳଲିଖିତ {{PLURAL:$1|ସାନ ଶ୍ରେଣୀ|$1 ସାନ ଶ୍ରେଣୀସମୂହ}} ରହିଅଛନ୍ତି ।',
'category-article-count'         => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀରେ ତଳେଥିବା ପୃଷ୍ଠାସବୁ ଅଛି ।|ମୋଟ $2 ରୁ ଏହି ଶ୍ରେଣୀ ଭିତରେ {{PLURAL:$1|ଟି ପୃଷ୍ଠା|$1ଟି ପୃଷ୍ଠା}} ଅଛି ।}}',
'category-article-count-limited' => 'ତଲଲିଖିତ {{PLURAL:$1|ପୃଷ୍ଠାଟି|$1ଟି ପୃଷ୍ଠା}} ଏହି ଶ୍ରେଣୀରେ ରହିଅଛି ।',
'category-file-count'            => '{{PLURAL:$2|ଏହି ଶ୍ରେଣୀରେ କେବଳ ତଳେଥିବା ଫାଇଲ ଗୋଟି ଅଛି । | ମୋଟ $2 ରୁ ଏହି ଶ୍ରେଣୀ ଭିତରେ {{PLURAL:$1|ଟି ପୃଷ୍ଠା|$1ଟି ଫାଇଲ}} ଅଛି ।}}',
'category-file-count-limited'    => 'ତଲଲିଖିତ {{PLURAL:$1|ଫାଇଲଟି|$1ଟି ଫାଇଲ}} ଏହି ଶ୍ରେଣୀରେ ରହିଅଛି ।',
'listingcontinuesabbrev'         => 'ଆହୁରି ଅଛି..',
'index-category'                 => 'ସୂଚୀଥିବା ପୃଷ୍ଠାସବୁ',
'noindex-category'               => 'ସୂଚୀହୀନ ପୃଷ୍ଠାସବୁ',
'broken-file-category'           => 'ଭଙ୍ଗା ଫାଇଲ ଲିଙ୍କ ଥିବା ପୃଷ୍ଠାସମୂହ',

'about'         => 'ବାବଦରେ',
'article'       => 'ସୂଚୀପତ୍ର',
'newwindow'     => '(ଏହା ନୂଆ ଉଇଣ୍ଡୋରେ ଖୋଲିବ)',
'cancel'        => 'ନାକଚ',
'moredotdotdot' => 'ଅଧିକ...',
'mypage'        => 'ମୋ ପୃଷ୍ଠା',
'mytalk'        => 'ମୋ ଆଲୋଚନା',
'anontalk'      => 'ଏହି ଆଇ.ପି. ଠିକଣା ଉପରେ ଆଲୋଚନା',
'navigation'    => 'ଦିଗବାରେଣି',
'and'           => '&#32;ଓ',

# Cologne Blue skin
'qbfind'         => 'ଖୋଜିବା',
'qbbrowse'       => 'ଖୋଜିବା',
'qbedit'         => 'ବଦଳାଇବେ',
'qbpageoptions'  => 'ଏହି ଫର୍ଦଟି',
'qbpageinfo'     => 'ଭିତର ଚିଜ',
'qbmyoptions'    => 'ମୋର ଫର୍ଦ',
'qbspecialpages' => 'ନିଆରା ପୃଷ୍ଠା',
'faq'            => 'ବାରମ୍ବାର ପଚରାଯାଉଥିବା ପ୍ରଶ୍ନ',
'faqpage'        => 'Project:ବାରମ୍ବାର ପଚାରାଯାଉଥିବା ପ୍ରଶ୍ନ',

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
'vector-view-history'            => 'ଇତିହାସ ଦେଖିବେ',
'vector-view-view'               => 'ପଢ଼ିବେ',
'vector-view-viewsource'         => 'ଉତ୍ସ ଦେଖିବେ',
'actions'                        => 'କାମ',
'namespaces'                     => 'ନେମସ୍ପେସ',
'variants'                       => 'ନିଆରା',

'errorpagetitle'    => 'ଭୁଲ',
'returnto'          => '$1କୁ ଫେରିଯାନ୍ତୁ ।',
'tagline'           => '{{SITENAME}} ରୁ',
'help'              => 'ସହଯୋଗ',
'search'            => 'ଖୋଜିବେ',
'searchbutton'      => 'ଖୋଜିବେ',
'go'                => 'ଯିବା',
'searcharticle'     => 'ଯିବା',
'history'           => 'ଫାଇଲ ଇତିହାସ',
'history_short'     => 'ଇତିହାସ',
'updatedmarker'     => 'ମୋ ଶେଷ ଆସିବା ପରେ ଅପଡେଟ କରାଯାଇଅଛି',
'printableversion'  => 'ଛପାହୋଇପାରିବା ପୃଷ୍ଠା',
'permalink'         => 'ସବୁଦିନିଆ ଲିଙ୍କ',
'print'             => 'ପ୍ରିଣ୍ଟ କରିବେ',
'view'              => 'ଦେଖଣା',
'edit'              => 'ବଦଳାଇବେ',
'create'            => 'ତିଆରି କରିବେ',
'editthispage'      => 'ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇବା',
'create-this-page'  => 'ଏହି ପୃଷ୍ଠାଟି ତିଆରିବେ',
'delete'            => 'ଲିଭେଇବେ',
'deletethispage'    => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'undelete_short'    => '{{PLURAL:$1|ଗୋଟିଏ ବଦଳ|$1ଟି ବଦଳ}} ଯାହା ଲିଭାସରିଛି ତାହାକୁ ପଛକୁ ଫେରାଇଦେବା',
'viewdeleted_short' => '{{PLURAL:$1|ଗୋଟିଏ ଲିଭାଯାଇଥିବା ବଦଳ|$1ଟି ଲିଭାଯାଇଥିବା ବଦଳ}} ଦେଖାଇବେ',
'protect'           => 'କିଳିବେ',
'protect_change'    => 'ବଦଳାଇବା',
'protectthispage'   => 'ଏହି ପୃଷ୍ଠାଟିକୁ କିଳିବେ',
'unprotect'         => 'ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇବେ',
'unprotectthispage' => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ସୁରକ୍ଷାର ପ୍ରକାର ବଦଲାଇବେ',
'newpage'           => 'ନୂଆ ପୃଷ୍ଠା',
'talkpage'          => 'ପୃଷ୍ଠାକୁ ଆଲୋଚନା କରନ୍ତୁ',
'talkpagelinktext'  => 'କଥାଭାଷା',
'specialpage'       => 'ନିଆରା ପୃଷ୍ଠା',
'personaltools'     => 'ନିଜର ଟୁଲ',
'postcomment'       => 'ନୂଆ ଭାଗ',
'articlepage'       => 'ସୂଚୀ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'talk'              => 'ଆଲୋଚନା',
'views'             => 'ଦେଖା',
'toolbox'           => 'ଉପକରଣ',
'userpage'          => 'ବ୍ୟବହାରକାରୀଙ୍କ ପୃଷ୍ଠା ଦେଖନ୍ତୁ',
'projectpage'       => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠାଟି ଦେଖାଇବା',
'imagepage'         => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'mediawikipage'     => 'ମେସେଜ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'templatepage'      => 'ଛାଞ୍ଚ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'viewhelppage'      => 'ସହାୟ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'categorypage'      => 'ଶ୍ରେଣୀ ପୃଷ୍ଠାଟିକୁ ଦେଖାଇବେ',
'viewtalkpage'      => 'ଆଲୋଚନାଗୁଡ଼ିକୁ ଦେଖନ୍ତୁ',
'otherlanguages'    => 'ଅଲଗା ଭାଷା',
'redirectedfrom'    => '($1 ରୁ ଲେଉଟି ଆସିଛି)',
'redirectpagesub'   => 'ଆଉଥରେ ଫେରିବା ପୃଷ୍ଠା',
'lastmodifiedat'    => 'ଏହି ପୃଷ୍ଠାଟି $1 ତାରିଖ $2 ବେଳେ ବଦଳାଯାଇଥିଲା ।',
'viewcount'         => 'ଏହି ପୃଷ୍ଠାଟି {{PLURAL:$1|ଥରେ|$1 ଥର}} ଖୋଲାଯାଇଛି ।',
'protectedpage'     => 'କିଳାଯାଇଥିବା ପୃଷ୍ଠା',
'jumpto'            => 'ଡେଇଁଯିବେ',
'jumptonavigation'  => 'ଦିଗବାରେଣିକୁ',
'jumptosearch'      => 'ଖୋଜିବେ',
'view-pool-error'   => 'କ୍ଷମା କରିବେ, ସର୍ଭରସବୁ ଏବେ ମନ୍ଦ ହୋଇଯାଇଅଛନ୍ତି ।
ଅନେକ ସଭ୍ୟ ଏହି ଏକା ପୃଷ୍ଠାଟି ଦେଖିବାକୁ ଚେଷ୍ଟାକରୁଅଛନ୍ତି ।
ଏହି ପୃଷ୍ଠାକୁ ଆଉଥରେ ଖୋଲିବା ଆଗରୁ ଦୟାକରି କିଛି କ୍ଷଣ ଅପେକ୍ଷା କରନ୍ତୁ ।
$1',
'pool-timeout'      => 'କଞ୍ଚି ଖୋଲାଯିବା ପାଇଁ ଅପେକ୍ଷା କରୁକରୁ ସମୟ ସରିଗଲା',
'pool-queuefull'    => 'ପୁଲ ଧାଡ଼ିଟି ଭରିଯାଇଅଛି',
'pool-errorunknown' => 'ଅଜଣା ଅସୁବିଧା',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} ବାବଦରେ',
'aboutpage'            => 'Project:ବାବଦରେ',
'copyright'            => '$1 ରେ ସର୍ବସ୍ଵତ୍ଵ ସଂରକ୍ଷିତ',
'copyrightpage'        => '{{ns:project}}:କପିରାଇଟ',
'currentevents'        => 'ଏବେକାର ଘଟଣା',
'currentevents-url'    => 'Project:ଏବେକାର ଘଟଣା',
'disclaimers'          => 'ଆମେ ଦାୟୀ ନୋହୁଁ',
'disclaimerpage'       => 'Project:ଆମେ ଦାୟୀ ନୋହୁଁ',
'edithelp'             => 'ଲେଖା ସାହାଯ୍ୟ',
'edithelppage'         => 'Help:ବଦଳାଇବା',
'helppage'             => 'Help:ଭିତର ଚିଜ',
'mainpage'             => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'mainpage-description' => 'ପ୍ରଧାନ ପୃଷ୍ଠା',
'policy-url'           => 'Project:ନୀତି',
'portal'               => 'ସଙ୍ଘ ଆଲୋଚନା ସଭା',
'portal-url'           => 'Project:ସଙ୍ଘ ଆଲୋଚନା ସଭା',
'privacy'              => 'ଗୁମର ନୀତି',
'privacypage'          => 'Project:ଗୁମର ନୀତି',

'badaccess'        => 'ଅନୁମତି ମିଳିବାରେ ଅସୁବିଧା',
'badaccess-group0' => 'ଆପଣ ଅନୁରୋଷ କରିଥିବା ପୃଷ୍ଠାଟିରେ କିଛି କାମ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଅନୁମତି ମିଳିନାହିଁ',
'badaccess-groups' => 'ଆପଣ ଅନୁରୋଧ କରିଥିବା କାମଟି କେବଳ {{PLURAL:$2|ଗୋଠ|ଗୋଠମାନଙ୍କ ଭିତରୁ ଗୋଟିଏ ଗୋଠ}}: $1 ର ସଭ୍ୟମାନଙ୍କ ଭିତରେ ସୀମିତ ।',

'versionrequired'     => 'ମିଡ଼ିଆଉଇକି ର $1 ତମ ସଙ୍କଳନଟି ଲୋଡ଼ା',
'versionrequiredtext' => 'ଏହି ପୃଷ୍ଠାଟି ବ୍ୟବହାର କରିବା ନିମନ୍ତେ ମିଡ଼ିଆଉଇକିର $1 ତମ ସଙ୍କଳନ ଲୋଡ଼ା ।
[[Special:Version|ସଙ୍କଳନ ପୃଷ୍ଠାଟି]] ଦେଖନ୍ତୁ ।',

'ok'                      => 'ଠିକ ଅଛି',
'retrievedfrom'           => '"$1" ରୁ ଅଣାଯାଇଅଛି',
'youhavenewmessages'      => 'ଆପଣଙ୍କର $1 ($2).',
'newmessageslink'         => 'ନୂଆ ମେସେଜ',
'newmessagesdifflink'     => 'ଶେଷ ବଦଳ',
'youhavenewmessagesmulti' => '$1 ତାରିଖରେ ନୂଆ ଚିଠିଟିଏ ଆସିଛି',
'editsection'             => 'ବଦଳାଇବେ',
'editold'                 => 'ବଦଳାଇବେ',
'viewsourceold'           => 'ଉତ୍ସ ଦେଖିବେ',
'editlink'                => 'ବଦଳାଇବେ',
'viewsourcelink'          => 'ଉତ୍ସ ଦେଖିବେ',
'editsectionhint'         => '$1 ଭାଗଟିକୁ ବଦଳାଇବେ',
'toc'                     => 'ଭିତର ଚିଜ',
'showtoc'                 => 'ଦେଖାଇବେ',
'hidetoc'                 => 'ଲୁଚାଇବେ',
'collapsible-collapse'    => 'ଚାପିଦେବେ',
'collapsible-expand'      => 'ବଢ଼ାଇବେ',
'thisisdeleted'           => '$1 କୁ ଦେଖିବେ ଅବା ପୁନସ୍ଥାପନ କରିବେ?',
'viewdeleted'             => 'ଦେଖିବା $1?',
'restorelink'             => '{{PLURAL:$1|ଗୋଟିଏ ଲିଭାଯାଇଥିବା ବଦଳ|$1ଟି ଲିଭାଯାଇଥିବା ବଦଳ}}',
'feedlinks'               => 'ଫିଡ଼:',
'feed-invalid'            => 'ଅଚଳ ସବସ୍କ୍ରିପସନ ଫିଡ଼ ପ୍ରକାର ।',
'feed-unavailable'        => 'ସିଣ୍ଡିକେସନ ଫିଡ଼ସବୁ ମିଳୁନାହିଁ',
'site-rss-feed'           => '$1 ଆରେସେସ ଫିଡ଼',
'site-atom-feed'          => '$1 ଆଟମ ଫିଡ଼',
'page-rss-feed'           => '$1 ଟି ଆରେସେସ ଫିଡ଼',
'page-atom-feed'          => '$1 ଟି ଆଟମ ଫିଡ଼',
'red-link-title'          => ' $1 (ପୃଷ୍ଠାଟି ନାହିଁ)',
'sort-descending'         => 'ବଡ଼ରୁ ସାନ କ୍ରମେ ସଜାନ୍ତୁ',
'sort-ascending'          => 'ସାନରୁ ବଡ଼ କ୍ରମେ ସଜାନ୍ତୁ',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ପୃଷ୍ଠା',
'nstab-user'      => 'ବ୍ୟବାହରକାରୀଙ୍କର ପୃଷ୍ଠା',
'nstab-media'     => 'ମେଡିଆ ପରଦ',
'nstab-special'   => 'ନିଆରା ପୃଷ୍ଠା',
'nstab-project'   => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠା',
'nstab-image'     => 'ଫାଇଲ',
'nstab-mediawiki' => 'ଖବର',
'nstab-template'  => 'ଛାଞ୍ଚ',
'nstab-help'      => 'ସାହାଯ୍ୟ ପୃଷ୍ଠା',
'nstab-category'  => 'ଶ୍ରେଣୀ',

# Main script and global functions
'nosuchaction'      => 'ସେହିଭଳି କିଛି କାମ ନାହିଁ',
'nosuchactiontext'  => 'URL ଟିରେ ଦିଆଯାଇଥିବା କାମଟି ଅଚଳ ଅଟେ ।
ଆପଣ ବୋଧ ହୁଏ URL ଟି ଭୁଲ ତାଇପ କରିଥିବେ, ଅଥବା ଲିଙ୍କଟି ଭୁଲ ଥିବ ।
ଏହା ମଧ୍ୟ {{SITENAME}}ରେ ବ୍ୟବହାର କରାଯାଇଥିବା ସଫ୍ଟବେରରେ ଥିବା କିଛି ଭୁଲକୁ ସୂଚାଇପାରେ ।',
'nosuchspecialpage' => 'ସେହି ଭଳି କିଛି ବି ବିଶେଷ ପୃଷ୍ଠା ନାହିଁ',
'nospecialpagetext' => '<strong>ଆପଣ ଅଚଳ ବିଶେଷ ପୃଷ୍ଠାଟିଏ ପାଇଁ ଆବେଦନ କରିଅଛନ୍ତି ।</strong>

[[Special:SpecialPages|{{int:specialpages}}]]ରେ ଅନେକ ଗୁଡ଼ିଏ ସଚଳ ସଚଳ ବିଶେଷ ପୃଷ୍ଠା ମିଳିପାରିବ ।',

# General errors
'error'                => 'ଭୁଲ',
'databaseerror'        => 'ଡାଟାବେସରେ ଭୁଲ',
'dberrortext'          => 'ଡାଟାବେସ ପ୍ରଶ୍ନ ଖୋଜା ଭୁଲ ଟିଏ ହୋଇଅଛି ।
ଏହା ଏହି ସଫ୍ଟବେରରେ ଭୁଲଟିଏକୁ ମଧ୍ୟ ସୂଚାଇପାରେ ।
ଶେଷ ଥର ଖୋଜାଯାଇଥିବା ଡାଟାବେସ ପ୍ରଶ୍ନ ଖୋଜାଟି ଥିଲା:
"<tt>$2</tt>" କାମ ଭିତରୁ
<blockquote><tt>$1</tt></blockquote> ।
ଡାଟାବେସ ଫେରନ୍ତା ଭୁଲ "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'ଡାଟାବେସ ପ୍ରଶ୍ନ ଖୋଜା ଭୁଲଟିଏ ହୋଇଅଛି ।
ଶେଷ ଖୋଜା ଡାଟାବେସ ପ୍ରଶ୍ନଟି ଥିଲା:
"$1"
ଯାହା "$2" ଭିତରୁ ନିଆଯାଇଥିଲା ।
ଡାଟାବେସ ଫେରନ୍ତା ଭୁଲ "$3: $4"',
'laggedslavemode'      => "'''ଜାଣିରଖନ୍ତୁ:''' ପୃଷ୍ଠାଟିରେ ବୋଧ ହୁଏ ନଗଦ ବଦଳ ନ ଥାଇପାରେ ।",
'readonly'             => 'ଡାଟାବେସଟିରେ କଞ୍ଚି ପଡ଼ିଅଛି',
'enterlockreason'      => 'କେତେ ଦିନ ଭିତରେ ଏହା ଖୋଲାଯିବ ତାହାର ଅଟକଳ ସହିତ କଞ୍ଚି ପଡ଼ିବାର କାରଣ ଦିଅନ୍ତୁ',
'readonlytext'         => 'ଏହି ଡାଟାବେସଟିରେ ଅଧୁନା ନୂଆ ପ୍ରସଙ୍ଗ ଯୋଗ ଓ ବାକି ବଦଳ ପାଇଁ ତାଲା ପଡ଼ିଅଛି, ଏହା ଡାଟାବେସର ନିତିଦିନିଆ ରକ୍ଷଣାବେକ୍ଷଣା ନିମନ୍ତେ ହୋଇଥାଇପାରେ, ଯାହା ପରେ ଏହ ପୁଣି ସାଧାରଣ ଭାବେ କାମ କରିବ ।

ଏଥିରେ ତାଲା ପକାଇଥିବା ପରିଛାଙ୍କ ତାଲା ପକାଇବାର କାରଣ: $1',
'missing-article'      => 'ଡାଟାବେସଟି ଆପଣ ଖୋଜିଥିବା "$1" $2 ଶବ୍ଦଟି ପାଇଲା ନାହିଁ । .

ଯଦି ଆପଣ ଖୋଜିଥିବା ପୃଷ୍ଠାଟି କେହି ଉଡ଼ାଇ ଦେଇଥାଏ ତେବେ ଏମିତି ହୋଇପାରେ ।

ଯଦି ସେମିତି ହୋଇନଥାଏ ତେବେ ଆପଣ ଏହି ସଫଟବେରରେ କିଛି ଅସୁବିଧା ଖୋଜି ପାଇଛନ୍ତି ।
କେହି ଜଣେ ଟିକେ [[Special:ListUsers/sysop|ପରିଛା]] ଙ୍କୁ ଏହି ଇଉଆରେଲ (url) ସହ ଚିଠିଟିଏ ପଠାଇ ଦିଅନ୍ତୁ ।',
'missingarticle-rev'   => '(ସଙ୍କଳନ#: $1)',
'missingarticle-diff'  => '(ପ୍ରଭେଦ: $1, $2)',
'readonly_lag'         => 'ଏହି ଡାଟାବେସଟିରେ ଆପେ ଆପେ ତାଲା ପଡ଼ିଯାଇଅଛି, ଇତିମଧ୍ୟରେ ସାନ ଡାଟାବେସଟି ମୁଖ୍ୟ ଡାଟାବେସ ସହିତ ଯୋଗାଯୋଗ କରୁଅଛି',
'internalerror'        => 'ଭିତରର ଭୁଲ',
'internalerror_info'   => 'ଭିତରର ଭୁଲ : $1',
'fileappenderrorread'  => 'ଯୋଡ଼ିବା ବେଳେ "$1"କୁ ପଢ଼ିପାରିଲୁଁ ନାହିଁ ।',
'fileappenderror'      => '"$1" ସହ "$2" କୁ ଯୋଡ଼ିପାରିଲୁଁ ନାହିଁ ।',
'filecopyerror'        => '"$1" ରୁ "$2" କୁ ନକଲ କରିପାରିଲୁଁ ନାହିଁ ।',
'filerenameerror'      => '"$1" ରୁ "$2" କୁ ନାମ ବଦଳ କରିପାରିଲୁଁ ନାହିଁ ।',
'filedeleteerror'      => '"$1" ଫାଇଲଟି ଲିଭାଇ ପାରିଲୁଁ ନାହିଁ ।',
'directorycreateerror' => '"$1" ସୂଚିଟି ତିଆରି କରିପାରିଲୁଁ ନାହିଁ ।',
'filenotfound'         => '"$1" ଫାଇଲଟି ପାଇଲୁ ନାହିଁ ।',
'fileexistserror'      => '"$1" ଫାଇଲଟି ଲେଖିପାରିଲୁଁ ନାହିଁ: ଏହା ଆଗରୁ ଅଛି',
'unexpected'           => 'ଅଜଣା ନାମ ମିଳିଲା: "$1"="$2" ।',
'formerror'            => 'ଭୁଲ: ଫର୍ମଟି ପଠାଇ ପାରିଲୁଁ ନାହିଁ',
'badarticleerror'      => 'ଏହି ପୃଷ୍ଠାରେ ଏହି କାମଟି ହୋଇପାରିବ ନାହିଁ ।',
'cannotdelete'         => '"$1" ପୃଷ୍ଠା ବା ଫାଇଲଟି ଲିଭାଯାଇପାରିବ ନାହିଁ । ଏହା ଆଗରୁ କାହା ଦେଇ ବୋଧେ ଲିଭାଇ ଦିଆଯାଇଛି ।',
'badtitle'             => 'ଖରାପ ନାଆଁ',
'badtitletext'         => 'ଆପଣ ଅନୁରୋଧ କରିଥିବା ପୃଷ୍ଠାଟି ଭୁଲ, ଖାଲି ଅଛି ବା ବାକି ଭାଷା ସାଙ୍ଗରେ ଭୁଲରେ ଯୋଡା ଯାଇଛି ବା ଭୁଲ ଇଣ୍ଟର ଉଇକି ନାଆଁ ଦିଅଯାଇଛି ।
ଏଥିରେ ଥିବା ଗୋଟିଏ ବା ଦୁଇଟି ଅକ୍ଷର ନାଆଁ ଭାବରେ ବ୍ୟବହାର କରାଯାଇ ପାରିବ ନାହିଁ ।',
'perfcached'           => 'ତଳଲିଖିତ ତଥ୍ୟଟି ଆଗରୁ ରହିଥିବା ତଥ୍ୟ, ତେଣୁ ନଗଦ ହୋଇନପାରେ ।',
'perfcachedts'         => 'ତଳଲିଖିତ ତଥ୍ୟ ଆଗରୁ ଥିବା ତଥ୍ୟ ଓ  $1ରେ ଶେଷଥର ଅପଡେଟ ହୋଇଥିଲା ।',
'querypage-no-updates' => 'ଏହି ପୃଷ୍ଠାଟି ପାଇଁ ଅପଡେଟସବୁ ଏବେ ଅଚଳ କରାଯାଇଅଛି ।
ଏଠାରେ ଥିବା ତଥ୍ୟ ସବୁ ଏବେ ସତେଜ ହୋଇପାରିବ ନାହିଁ ।',
'wrong_wfQuery_params' => 'wfQuery() ପାଇଁ ଭୁଲ ପାରାମିଟର<br />
କାମ: $1<br />
ଖୋଜା ପ୍ରଶ୍ନ: $2',
'viewsource'           => 'ଉତ୍ସ ଦେଖିବେ',
'viewsourcefor'        => '$1 ନିମନ୍ତେ',
'actionthrottled'      => 'କାମଟି ବନ୍ଦ କରିଦିଆଗଲା',
'actionthrottledtext'  => 'ସ୍ପାମକୁ ବନ୍ଦ କରିବା ନିମନ୍ତେ ଏକ ଅଳ୍ପ ସମୟ ବିରତି ଭିତରେ ଆପଣଙ୍କୁ ଏହି କାମଟୀ ବାରମ୍ବାର କରିବାକୁ ଅନୁମତି ଦିଆଯାଉନାହିଁ ଓ ଆପଣ ସୀମା ପାର କରିଯାଇଛନ୍ତି ।
ଦୟାକରି କିଛି ସମୟ ପରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'protectedpagetext'    => 'ଏହି ପୃଷ୍ଠାଟି ସମ୍ପାଦନା କରିବାରୁ କିଳାଯାଇଛି ।',
'viewsourcetext'       => 'ଆପଣ ଏହି ପୃଷ୍ଠାର ଲେଖା ଦେଖିପାରିବେ ଓ ନକଲ କରିପାରିବେ:',
'protectedinterface'   => 'ଏହି ପୃଷ୍ଠାଟି ସଫ୍ଟବେର ନିମନ୍ତେ ଇଣ୍ଟରଫେସ ଲେଖା ଯୋଗାଇଥାଏ ଓ ଏହା ଅବ୍ୟବହାରକୁ ରୋକିବା ନିମନ୍ତେ କିଳାଯାଇଅଛି ।',
'editinginterface'     => "'''ଚେତାବନୀ:''' ଆପଣ ସଫ୍ଟବେରର ଇଣ୍ଟରଫେସ ଲେଖା ଯୋଗାଇବା ନିମନ୍ତେ ବ୍ୟବହାର କରାଯାଉଥିବା ଏକ ପୃଷ୍ଠାର ସମ୍ପାଦନା କରୁଅଛନ୍ତି ।
ଏହି ପୃଷ୍ଠାର କିଛି ବି ବଦଳ ବାକି ସଭ୍ୟମାନଙ୍କ ଇଣ୍ଟରଫେସର ଦେଖଣାକୁ ପ୍ରଭାବିତ କରିବ ।
ଅନୁବାଦ ନିମନ୍ତେ, ଦୟାକରି ମିଡ଼ିଆଉଇକିର ସ୍ଥାନୀୟକରଣ ପ୍ରକଳ୍ପ [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net] ବ୍ୟବହାର କରନ୍ତୁ ।",
'sqlhidden'            => '(SQL ପ୍ରଶ୍ନ ଲୁଚାଯାଇଅଛି)',
'cascadeprotected'     => 'ଏହି ପୃଷ୍ଠା ସମ୍ପାଦନା କରିବାରୁ କିଳାଯାଇଅଛି, କାରଣ ଏଥିରେ ତଳଲିଖିତ {{PLURAL:$1|ପୃଷ୍ଠାଟିଏ ଅଛି|ଟି ପୃଷ୍ଠା ଅଛି}} ଯାହା "କ୍ୟାସକେଡ଼ କରା" ସୁବିଧା ଦେଇ କିଳାଯାଇଅଛି ।:
$2',
'namespaceprotected'   => "ଆପଣଙ୍କୁ ଏହି '''$1''' ନେମସ୍ପେସ ଥିବା ପୃଷ୍ଠାରେ ସମ୍ପାଦନା କରିବା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ ।",
'customcssprotected'   => 'ଆପଣଙ୍କୁ ଏହି CSS ପୃଷ୍ଠାର ସମ୍ପାଦନା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ, କାରଣ ଏଥିରେ ଆଉଜଣେ ସଭ୍ୟଙ୍କର ବ୍ୟକ୍ତିଗତ ସଜାଣି ରହିଅଛି ।',
'customjsprotected'    => 'ଆପଣଙ୍କୁ ଏହି ଜାଭାସ୍କ୍ରିପ୍ଟ ପୃଷ୍ଠାର ସମ୍ପାଦନା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ, କାରଣ ଏଥିରେ ଆଉଜଣେ ସଭ୍ୟଙ୍କର ବ୍ୟକ୍ତିଗତ ସଜାଣି ରହିଅଛି ।',
'ns-specialprotected'  => 'ବିଶେଷ ପୃଷ୍ଠାସବୁକୁ ବଦଳାଯାଇପାରିବ ନାହିଁ ।',
'titleprotected'       => 'ଏହି ନାମଟି [[User:$1|$1]]ଙ୍କ ଦେଇ ନୂଆ ତିଆରିହେବାରୁ କିଳାଯାଇଅଛି ।
ଏହାର କାରଣ ହେଲା "\'\'$2\'\'" ।',

# Virus scanner
'virus-badscanner'     => "ମନ୍ଦ ସଂରଚନା: ଅଜଣା ଭାଇରସ ସ୍କାନର: ''$1''",
'virus-scanfailed'     => 'ସ୍କାନ କରିବା ବିଫଳ ହେଲା (କୋଡ଼ $1)',
'virus-unknownscanner' => 'ଅଜଣା ଆଣ୍ଟିଭାଇରସ:',

# Login and logout pages
'logouttext'                 => "'''ଆପଣ ଲଗାଆଉଟ କରିଦେଲେ'''

ଆପଣ ଅଜଣା ଭାବରେ {{SITENAME}}କୁ ଯାଇପାରିବେ, କିମ୍ବା [[Special:UserLogin|ଆଉଥରେ]] ଆଗର ଇଉଜର ନାଆଁରେ/ଅଲଗା ନାଆଁରେ ଲଗଇନ କରିପାରିବେ ।
ଜାଣିରଖନ୍ତୁ, କିଛି ପୃଷ୍ଠା ଲଗାଆଉଟ କଲାପରେ ବି ଆଗପରି ଦେଖାଯାଇପାରେ, ଆପଣ ବ୍ରାଉଜର କାସକୁ ହଟାଇଲା ଯାଏଁ ଏହା ଏମିତି ରହିବ ।",
'welcomecreation'            => '== $1!, ଆପଣଙ୍କ ଖାତାଟି ତିଆରି ହୋଇଗଲା==
ତେବେ, ନିଜର [[Special:Preferences|{{SITENAME}} ପସନ୍ଦସବୁକୁ]] ବଦଳାଇବାକୁ ଭୁଲିବେ ନାହିଁ ।',
'yourname'                   => 'ବ୍ୟବାହରକାରୀଙ୍କର ନାଆଁ:',
'yourpassword'               => 'ପାସବାର୍ଡ଼',
'yourpasswordagain'          => 'ପାସବାର୍ଡ଼ ଆଉଥରେ:',
'remembermypassword'         => 'ଏହି ବ୍ରାଉଜରରେ (ସବୁଠୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|day|ଦିନ}}) ପାଇଁ ମୋ ଲଗଇନ ମନେ ରଖିଥିବେ',
'securelogin-stick-https'    => 'ଲଗ ଇନ କଲାପରେ HTTPS ସହ ଯୋଡ଼ି ହୋଇ ରହନ୍ତୁ',
'yourdomainname'             => 'ଆପଣଙ୍କ ଡୋମେନ:',
'externaldberror'            => 'ବୋଧ ହୁଏ ଚିହ୍ନଟ ଡାଟାବେସ ଭୁଲଟିଏ ହୋଇଥିଲା ବା ଆପଣଙ୍କୁ ନିଜର ବାହାର ଖାତା ଅପଡେଟ କରିବା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ ।',
'login'                      => 'ଲଗଇନ',
'nav-login-createaccount'    => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'loginprompt'                => "{{SITENAME}}ରେ ଲଗ ଇନ କରିବାପାଇଁ ଆପଣଙ୍କୁ '''କୁକି''' ସଚଳ କରିବାକୁ ପଡ଼ିବ ।",
'userlogin'                  => 'ଲଗିନ / ଖାତା ଖୋଲିବା',
'userloginnocreate'          => 'ଲଗଇନ',
'logout'                     => 'ଲଗଆଉଟ',
'userlogout'                 => 'ଲଗ ଆଉଟ',
'notloggedin'                => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'nologin'                    => 'ଖାତାଟିଏ ନାହିଁ? $1।',
'nologinlink'                => 'ନୁଆ ଖାତାଟିଏ ଖୋଲିବା',
'createaccount'              => 'ନୁଆ ଖାତା ଖୋଲିବା',
'gotaccount'                 => 'ଆଗରୁ ଖାତାଟିଏ ଅଛି କି? $1.',
'gotaccountlink'             => 'ଲଗଇନ',
'userlogin-resetlink'        => 'ଲଗଇନ ତଥ୍ୟ ସବୁ ଭୁଲିଗେଲେକି?',
'createaccountmail'          => 'ଇ-ମେଲ ରୁ',
'createaccountreason'        => 'କାରଣ:',
'badretype'                  => 'ଆପଣ ଦେଇଥିବା ପାସବାର୍ଡ଼ଟି ମେଳଖାଉନାହିଁ ।',
'userexists'                 => 'ଆପଣ ଦେଇଥିବା ଇଉଜର ନାମ ଆଗରୁ ଅଛି ।
ଦୟାକରି ଅଲଗା ନାମଟିଏ ବାଛନ୍ତୁ ।',
'loginerror'                 => 'ଲଗ‌‌ଇନ ଭୁଲ',
'createaccounterror'         => '$1 ନାମରେ ଖାତାଟିଏ ଖୋଲାଯାଇପାରିଲା ନାହିଁ',
'nocookiesnew'               => 'ଇଉଜର ନାମଟି ତିଆରି କରିଦିଆଗଲା, ହେଲେ ଆପଣ ଲଗ ଇନ କରିନାହାନ୍ତି ।
{{SITENAME}} ସଭ୍ୟମାନଙ୍କୁ ଲଗ ଇନ କରିବା ନିମନ୍ତେ କୁକି ବ୍ୟବହାର କରିଥାଏ । ଆପଣଙ୍କ କୁକି ଅଚଳ କରାଯାଇଅଛି ।
ଦୟାକରି ତାହାକୁ ସଚଳ କରନ୍ତୁ ଓ ତାହା ପରେ ଆପଣଙ୍କ ନୂଆ ଇଉଜର ନାମ ଓ ପାସବାର୍ଡ଼ ସହିତ ଲଗ ଇନ କରନ୍ତୁ ।',
'nocookieslogin'             => '{{SITENAME}} ସଭ୍ୟ ମାନଙ୍କୁ ଲଗ ଇନ କରାଇବା ପାଇଁ କୁକି ବ୍ୟବହାର କରିଥାଏ ।
ଆପଣଙ୍କର କୁକି ଅଚଳ ହୋଇଅଛି ।
ଦୟାକରି ତାହାକୁ ସଚଳ କରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'nocookiesfornew'            => 'ଯେହେତୁ ଆମ୍ଭେ ଏହାର ମୂଳ ସ୍ରୋତ ଜାଣିପାରିଲୁଁ ନାହିଁ ଏହି ଇଉଜର ଖାତାଟି ତିଆରି କରାଗଲା ନାହିଁ ।
ଥୟ କରନ୍ତୁ କି ଆପଣ କୁକି ସଚଳ କରିଅଛନ୍ତି, ପୃଷ୍ଠାଟିକୁ ଆଉଥରେ ଲୋଡ଼ କରି ଚେଷ୍ଟା କରନ୍ତୁ ।',
'noname'                     => 'ଆପଣ ଗୋଟିଏ ବୈଧ ଇଉଜର ନାମ ଦେଇନାହାନ୍ତି ।',
'loginsuccesstitle'          => 'ଠିକଭାବେ ଲଗଇନ ହେଲା',
'loginsuccess'               => "'''ଆପଣ {{SITENAME}}ରେ \"\$1\" ଭାବରେ ଲଗଇନ କରିଛନ୍ତି ।'''",
'nosuchuser'                 => '"$1" ନାମରେ କେହି ଜଣେ ବି ସଭ୍ୟ ନାହାନ୍ତି ।
ଇଉଜର ନାମ ଇଂରାଜୀ ଛୋଟ ଓ ବଡ଼ ଅକ୍ଷର ପ୍ରତି ସମ୍ବେଦନଶୀଳ ।
ଆପଣ ନିଜର ବନାନ ପରଖି ନିଅନ୍ତୁ, ଅଥବା [[Special:UserLogin/signup|ନୂଆ ଖାତାଟିଏ ତିଆରି କରନ୍ତୁ]] ।',
'nosuchusershort'            => '"$1" ନାମରେ କେହି ଜଣେ ବି ସଭ୍ୟ ନାହାନ୍ତି ।
ଆପଣ ବନାନ ପରଖି ନିଅନ୍ତୁ ।',
'nouserspecified'            => 'ଆପଣଙ୍କୁ ଇଉଜର ନାମଟିଏ ଦେବାକୁ ପଡ଼ିବ ।',
'login-userblocked'          => 'ଏହି ସଭ୍ୟଙ୍କୁ ଅଟକାଯାଇଛି । ଲଗ ଇନ କରିବାକୁ ଅନୁମତି ନାହିଁ ।',
'wrongpassword'              => 'ଦିଆଯାଇଥିବା ପାସବାର୍ଡ଼ଟି ଭୁଲ ଅଟେ  ।
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'wrongpasswordempty'         => 'ଦିଆଯାଇଥିବା ପାସବାର୍ଡ଼ଟି ଖାଲି ଛଡ଼ାଯାଇଛି ।</br>
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'passwordtooshort'           => 'ପାସବାର୍ଡ଼ଟି ଅତି କମରେ {{PLURAL:$1|ଗୋଟିଏ ଅକ୍ଷର|$1ଟି ଅକ୍ଷର}}ର ହୋଇଥିବା ଲୋଡ଼ା ।',
'password-name-match'        => 'ଆପଣଙ୍କ ପାସବାର୍ଡ଼ଟି ଆପଣଙ୍କ ଇଉଜର ନାମ ଠାରୁ ଅଲଗା ହେବା ଉଚିତ ।',
'password-login-forbidden'   => 'ଏହି ଇଉଜର ନାମ ଓ ପାସବାର୍ଡ଼ର ବ୍ୟବହାରକୁ ବାରଣ କରାଯାଇଅଛି ।',
'mailmypassword'             => 'ପାସବାର୍ଡ଼ଟିକୁ ଇମେଲ କରି ପଠାଇବେ',
'passwordremindertitle'      => '{{SITENAME}} ପାଇଁ ନୂଆ ଅଳ୍ପ କାଳର ପାସବାର୍ଡ଼',
'passwordremindertext'       => 'କେହିଜଣେ (ବୋଧେ ଆପଣ, $1 IP ଠିକଣାରୁ) 
ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ପାଇଁ {{SITENAME}} ($4) ରେ ଆବେଦନ କରିଅଛନ୍ତି । "$2"ଙ୍କ ପାଇଁ ଏକ ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼
ତିଆରି କରିଦିଆଗଲା ଓ ତାହାକୁ "$3" ପାଇଁ ଖଞ୍ଜି ଦିଆଗଲା । ଯଦି ଏହା ଆପଣଙ୍କର
ଇଛା ତେବେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରି ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ଏବେ ଦେବାକୁ ପଡ଼ିବ ।
Your temporary password will expire in {{PLURAL:$5|one day|$5 days}}.

If someone else made this request, or if you have remembered your password,
and you no longer wish to change it, you may ignore this message and
continue using your old password.',
'noemail'                    => 'ସଭ୍ୟ "$1"ଙ୍କ ପାଇଁ କିଛି ବି ଇ-ମେଲ ଆଇ.ଡି. ସାଇତାଯାଇନାହିଁ  ।',
'noemailcreate'              => 'ଆପଣଙ୍କୁ ଏକ ସଚଳ ଇ-ମେଲ ଠିକଣା ଦେବାକୁ ପଡ଼ିବ',
'passwordsent'               => '"$1" ପାଇଁ ଥୟ କରାଯାଇଥିବା ଇ-ମେଲକୁ ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ପଠାଇଦିଆଗଲା ।
ତାହା ମିଳିଲା ପରେ ଆଉଥରେ ଲଗ ଇନ କରନ୍ତୁ ।',
'blocked-mailpassword'       => 'ଆପଣଙ୍କ IP ଠିକଣାଟି ସମ୍ପାଦନାରେ ଭାଗ ନେବାରୁ ଅଟକାଯାଇଛି, ତେଣୁ ପାସବାର୍ଡ଼ ଫେରନ୍ତା କାମ ବ୍ୟବହାର କରି ଅବ୍ୟବହାରକୁ ରୋକିବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'eauthentsent'               => 'ଆପଣଙ୍କ ବଛା ଇ-ମେଲ ଠିକଣାକୁ ଏକ ଥୟ କରିବା ଇ-ମେଲଟିଏ ପଠାଇଦିଆଗଲା ।
ଖାତାଟି ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ନିମନ୍ତେ ଆଉ କେଉଁ ଇ-ମେଲ ଆପଣଙ୍କ ଖାତାକୁ ପଠାହେବା ଆଗରୁ ଆପଣଙ୍କୁ ସେହି ଇ-ମେଲରେ ଥିବା ସୂଚନା ଅନୁସରଣ କରିବାକୁ ପଡ଼ିବ ।',
'throttled-mailpassword'     => 'ଗତ {{PLURAL:$1|ଏକ ଘଣ୍ଟାରେ|$1 ଘଣ୍ଟାରେ}} ଆପଣଙ୍କୁ ଏକ ପାସବାର୍ଡ଼ ମନେକରିବା ସୂଚନାଟିଏ ପଠାଯାଇଛି ।
ଅବ୍ୟବହାରକୁ ରୋକିବା ନିମନ୍ତେ, {{PLURAL:$1|ଏକ ଘଣ୍ଟାରେ|$1 ଘଣ୍ଟାରେ}} କେବଳ ଗୋଟିଏ ପାସବାର୍ଡ଼ ହିଁ ପଠାହେବ ।',
'mailerror'                  => 'ମେଲ ପଠାଇବାରେ ଭୁଲ : $1',
'acct_creation_throttle_hit' => 'ଏହି ଉଇକିର ଦେଖଣାହାରୀ ମାନେ ଆପଣଙ୍କ IP ଠିକଣା ବ୍ୟବହାର କରି ବିଗତ ଦିନରେ {{PLURAL:$1|ଖାତାଟିଏ|$1 ଗୋଟି ଖାତା}} ତିଆରି କରିଛନ୍ତି ଯାହା ସେହି ସମୟସୀମା ଭିତରେ ସବୁଠାରୁ ଅଧିକ ଥିଲା ।
ତେଣୁ, ଏହି IP ଠିକଣାର ଦେଖଣାହାରୀ ଗଣ ଏବେ ଆଉ ଅଧିକ ଖାତା ଖୋଲିପାରିବେ ନାହିଁ ।',
'emailauthenticated'         => '$2 ତାରିଖ $3 ଘଟିକା ବେଳେ ଆପଣଙ୍କ ଇ-ମେଲ ଠିକଣାଟି ଅନୁମୋଦିତ ହେଲା ।',
'emailnotauthenticated'      => 'ଆପନଙ୍କ ଇ-ମେଲ ଠିକଣାଟି ଅନୁମୋଦିତ୍ ହୋଇନାହିଁ ।
ଏହି ସବୁ ସୁବିଧାକୁ ନେଇ କିଛି ବି ଇ-ମେଲ ଆପଣଙ୍କୁ ପଠାଯିବ ନାହିଁ ।',
'noemailprefs'               => 'ଆପଣଙ୍କ ପସନ୍ଦ ଭିତରେ ଏକ ଇ-ମେଲ ଠିକଣା ଦିଅନ୍ତୁ ଯାହା ଏହି ସବୁ ସୁବିଧାକୁ ସଚଳ କରାଇବ ।',
'emailconfirmlink'           => 'ଆପଣଙ୍କ ଇମେଲ ଆଇ.ଡି.ଟି ଠିକ ବୋଲି ଥୟ କରନ୍ତୁ',
'invalidemailaddress'        => 'ଏହି ଇ-ମେଲ ଠିକଣାଟି ସଠିକ ସଜାଣିରେ ନଥିବାରୁ ଏହାକୁ ଗ୍ରହଣ କରାଯାଇପାରିବ ନାହିଁ ।
ଦୟାକରି ଏକ ସଚଳ ଓ ଠିକ ସଜାଣିରେ ଥିବା ଇ-ମେଲ ଠିକଣା ଦିଅନ୍ତୁ ।',
'accountcreated'             => 'ଖାତାଟି ଖୋଲାହୋଇଗଲା',
'accountcreatedtext'         => '$1 ପାଇଁ ନୂଆ ଖାତାଟିଏ ତିଆରି ହୋଇଗଲା ।',
'createaccount-title'        => '{{SITENAME}} ପାଇଁ ଖାତା ଖୋଲା',
'createaccount-text'         => 'କେହି ଜଣେ ଆପଣଙ୍କ ଇ-ମେଲ ଠିକଣାରେ {{SITENAME}} ($4) ରେ "$2" ନାମରେ, "$3" ପାସବାର୍ଡ଼ରେ ଖାତାଟିଏ ତିଆରି କରିଅଛି ।
ଆପଣ ଏବେ ଲଗ ଇନ କରି ନିଜର ପାସବାର୍ଡ଼ଟିକୁ ବଦଳାଇଦିଅନ୍ତୁ ।

ଯଦି ଭୁଲରେ ଏହି ଖାତାଟି ତିଆରି କରାଯାଇଥାଏ ତେବେ ଏହି ସୂଚନାଟିକୁ ଅଣଦେଖା କରିବେ ।',
'usernamehasherror'          => 'ଇଉଜର ନାମରେ ହାସ ଅକ୍ଷର (hash characters) ରହି ପାରିବନାହିଁ',
'login-throttled'            => 'ଆପଣ ବହୁ ଥର ଲଗ ଇନ କରିବାର ଉଦ୍ୟମ କରିଅଛନ୍ତି ।
ଦୟାକରି ଆଉଥରେ ଚେଷ୍ଟା କରିବା ଆଗରୁ କିଛି କାଳ ଅପେକ୍ଷ କରନ୍ତୁ ।',
'login-abort-generic'        => 'ଆପଣଙ୍କ ଲଗ ଇନ ଅସଫଳ ହେଲା - ନାକଚ କରିଦିଆଗଲା',
'loginlanguagelabel'         => 'ଭାଷା: $1',
'suspicious-userlogout'      => 'ଲଗ ଆଉଟ କରିବା ନିମନ୍ତେ ଆପଣ କରିଥିବା ଆବେଦନ ନାକଚ କରିଦିଆଗଲା କାରଣ ଲାଗୁଅଛି ଯେ ଏହା ଏକ ଅସ୍ଥିର ବ୍ରାଉଜରରୁ ପଠାଯାଇଅଛି ଅବା ପ୍ରକ୍ସି ଧରାଯାଇଅଛି ।',

# E-mail sending
'php-mail-error-unknown' => 'PHP ର ମେଲ() କାମରେ ଅଜଣା ଅସୁବିଧା ।',
'user-mail-no-addy'      => 'ଏକ ଇ-ମେଲ ଠିକଣା ବିନା ଇ-ମେଲ ପଠାଇବାକୁ ଚେଷ୍ଟା କଲୁଁ ।',

# Change password dialog
'resetpass'                 => 'ପାସବାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'resetpass_announce'        => 'ଆପଣ ଏକ ଅସ୍ଥାୟୀ ଇ-ମେଲରେ ଯାଇଥିବା କୋଡ଼ ସହାୟତାରେ ଲଗ ଇନ କରିଅଛନ୍ତି ।
ଲଗ ଇନ ଶେଷ କରିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଏହିଠାରେ ନୂଆ ପାସବାର୍ଡ଼ଟିଏ ଦେବାକୁ ପଡ଼ିବ:',
'resetpass_header'          => 'ଖାତାର ପାସବାର୍ଡ଼ଟିକୁ ବଦଳାଇ ଦିଅନ୍ତୁ',
'oldpassword'               => 'ପୁରୁଣା ପାସଉଆଡ଼:',
'newpassword'               => 'ନୂଆ ପାସବାର୍ଡ଼:',
'retypenew'                 => 'ପାସବାର୍ଡ଼ ଆଉଥରେ ଦିଅନ୍ତୁ:',
'resetpass_submit'          => 'ପାସବାର୍ଡ଼ଟି ଦେଇ ଲଗ ଇନ କରନ୍ତୁ',
'resetpass_success'         => 'ଆପଣଙ୍କ ପାସବାର୍ଡ଼ଟି ବଦଳାଇ ଦିଆଗଲା !
ଏବେ ଲଗ ଇନ କରୁଅଛୁଁ...',
'resetpass_forbidden'       => 'ପାସବାର୍ଡ଼ମାନ ବଦଳା ଯାଇପାରିବ ନାହିଁ',
'resetpass-no-info'         => 'ଏହି ପୃଷ୍ଠାଟିକୁ ସିଧା ଖୋଲିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରିବାକୁ ପଡ଼ିବ ।',
'resetpass-submit-loggedin' => 'ପାସବାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'resetpass-submit-cancel'   => 'ନାକଚ',
'resetpass-wrong-oldpass'   => 'ଅସ୍ଥାୟୀ ବା ଏବେକାର ପାସବାର୍ଡ଼ଟି ଭୁଲ ଅଟେ ।
ଆପଣ ବୋଧ ହୁଏ ଆଗରୁ ସଫଳ ଭାବରେ ନିଜର ପାସବାର୍ଡ଼ଟି ବଦଳାଇଦେଇଛନ୍ତି ବା ନୂଆ ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼ଟିଏ ପାଇଁ ଆବେଦନ କରିଅଛନ୍ତି ।',
'resetpass-temp-password'   => 'ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼:',

# Special:PasswordReset
'passwordreset'              => 'ପାସୱିର୍ଡ ପୁନଃ ସ୍ଥାପନ କରନ୍ତୁ',
'passwordreset-text'         => 'ନିଜ ଖାତାର ସବିଶେଷ ବିବରଣୀ ଏକ ଇ-ମେଲରେ ପାଇବା ପାଇଁ ଏହି ଆବେଦନ ପତ୍ରଟି ପୂରଣ କରନ୍ତୁ ।',
'passwordreset-legend'       => 'ପାସୱିର୍ଡ ପୁନଃ ସ୍ଥାପନ କରନ୍ତୁ',
'passwordreset-disabled'     => 'ପାସବାର୍ଡ଼କୁ ପୁରାପୁରି ମୂଳକୁ ଫେରାଇବା ଏହି ଉଇକିରେ ଅଚଳ କରାଯାଇଅଛି ।',
'passwordreset-pretext'      => '{{PLURAL:$1||ତଳେ ଥିବା ତଥ୍ୟସମୂହରୁ କୌଣସି ଗୋଟିଏ ଦିଅନ୍ତୁ}}',
'passwordreset-username'     => 'ବ୍ୟବାହରକାରୀଙ୍କର ନାଆଁ:',
'passwordreset-domain'       => 'ଡୋମେନ:',
'passwordreset-email'        => 'ଇ-ମେଲ ଠିକଣା:',
'passwordreset-emailtitle'   => '{{SITENAME}} ର ଖାତା ସବିଶେଷ',
'passwordreset-emailelement' => 'ଇଉଜର ନାମ: $1
ଅସ୍ଥାୟୀ ପାସବାର୍ଡ଼: $2',
'passwordreset-emailsent'    => 'ଏକ ମନେପକାଇବା ଇ-ମେଲ ପଠାଇଦିଆଯାଇଅଛି ।',

# Special:ChangeEmail
'changeemail'          => 'ଇ-ମେଲ ଠିକଣା ବଦଳାଇବେ',
'changeemail-header'   => 'ଖାତା ଇ-ମେଲ ଠିକଣା ବଦଳାଇବେ',
'changeemail-text'     => 'ଆପଣା ଇ-ମେଲ ଠିକଣା ବଦଳାଇବା ନିମନ୍ତେ ଏହି ଆବେଦନ ପତ୍ରଟି ପୂରଣ କରନ୍ତୁ । ଆପଣଙ୍କୁ ଏହି ବଦଳ ଥୟ କରିବା ପାଇଁ ନିଜର ପାସବାର୍ଡ଼ ଦେବାକୁ ପଡ଼ିବ ।',
'changeemail-no-info'  => 'ଏହି ପୃଷ୍ଠାଟିକୁ ସିଧା ଖୋଲିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ଲଗ ଇନ କରିବାକୁ ପଡ଼ିବ ।',
'changeemail-oldemail' => 'ଏବେକାର ଇ-ମେଲ ଠିକଣା:',
'changeemail-newemail' => 'ନୂଆ ଇ-ମେଲ ଠିକଣା:',
'changeemail-none'     => '(କିଛି ନାହିଁ)',
'changeemail-submit'   => 'ଇ-ମେଲ ପରିର୍ବତ୍ତନ କରନ୍ତୁ',
'changeemail-cancel'   => 'ନାକଚ',

# Edit page toolbar
'bold_sample'     => 'ମୋଟା ଲେଖା',
'bold_tip'        => 'ମୋଟା ଲେଖା',
'italic_sample'   => 'ତେରେଛା ଲେଖା',
'italic_tip'      => 'ତେରେଛା ଲେଖା',
'link_sample'     => 'ଲିଙ୍କ ଶିରୋନାମା',
'link_tip'        => 'ଭିତର ଲିଙ୍କ',
'extlink_sample'  => 'http://www.example.com ଲିଙ୍କ ଶିରୋନାମା',
'extlink_tip'     => 'ବାହାର ଲିଙ୍କ (http:// ଆଗରେ ଲଗାଇବାକୁ ମନେରଖିଥିବେ)',
'headline_sample' => 'ଶିରୋନାମା ଲେଖା',
'headline_tip'    => '୨କ ଆକାରର ମୂଳଧାଡ଼ି',
'nowiki_sample'   => 'ଅସଜଡ଼ା ଲେଖା ଏଠାରେ ଭରିବେ',
'nowiki_tip'      => 'ଉଇକି ସଜାଣି ବିନା',
'image_tip'       => 'ଏମବେଡ଼ ହୋଇ ଥିବା ଫାଇଲ',
'media_tip'       => 'ଫାଇଲର ଲିଙ୍କ',
'sig_tip'         => 'ଲେଖାର ବେଳ ସହ ଆପଣଁକ ହସ୍ତାକ୍ଷର',
'hr_tip'          => 'ସମାନ୍ତରାଳ ରେଖା (ବେଳେବେଳେ ବ୍ୟବହାର କରିବେ)',

# Edit pages
'summary'                          => 'ସାରକଥା:',
'subject'                          => 'ବିଷୟ/ମୂଳ ଲେଖା',
'minoredit'                        => 'ଏହା ଖୁବ ଛୋଟ ବଦଳଟିଏ',
'watchthis'                        => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'savearticle'                      => 'ସାଇତିବେ',
'preview'                          => 'ସାଇତିବା ଆଗରୁ ଦେଖଣା',
'showpreview'                      => 'ଦେଖଣା',
'showlivepreview'                  => 'ଜୀବନ୍ତ ଦେଖଣା',
'showdiff'                         => 'ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'anoneditwarning'                  => "'''ଜାଣିରଖନ୍ତୁ:''' ଆପଣ ଲଗଇନ କରିନାହାନ୍ତି ।
ଏହି ଫରଦର '''ଇତିହାସ''' ପୃଷ୍ଠାରେ ଆପଣଙ୍କ ଆଇପି ଠିକଣାଟି ସାଇତା ହୋଇଯିବ ।",
'anonpreviewwarning'               => "''ଆପଣ ଲଗ ଇନ କରି ନାହାନ୍ତି । ଆପଣ ଯେଉଁ ବଦଳସବୁ କରିବେ ଆପଣଙ୍କର IP ଠିକଣା ଏହି ପୃଷ୍ଠାର ଇତିହାସରେ ସାଇତା ହୋଇଯିବ ।''",
'missingsummary'                   => "'''ଚେତାବନୀ:''' ଆପଣ ଏକ ସମ୍ପାଦନା ସାରକଥା ଦେଇନାହାନ୍ତି ।
ଯଦି ଆପଣ \"{{int:savearticle}}\"ରେ ଆଉଥରେ କ୍ଲିକ କରନ୍ତି, ତେବେ ଆପଣଙ୍କ ବଦଳ ସାରକଥା ବିନା ସାଇତା ହୋଇଯିବ ।",
'missingcommenttext'               => 'ଦୟାକରି ତଳେ ଏକ ମତାମତ ଦିଅନ୍ତୁ ।',
'missingcommentheader'             => "'''ଚେତାବନୀ:''' ଆପଣ ଏହି ମତଟି ନିମନ୍ତେ ଏକ ଶିରୋନାମା/ମୁଖ୍ୟ ନାମ ଦେଇନାହାନ୍ତି ।
ଯଦି ଆପଣ \"{{int:savearticle}}\"ରେ ଆଉଥରେ କ୍ଲିକ କରନ୍ତି, ତେବେ ଆପଣଙ୍କ ବଦଳ ସାରକଥା ବିନା ସାଇତା ହୋଇଯିବ ।",
'summary-preview'                  => 'ସାରକଥା ଦେଖଣା:',
'subject-preview'                  => 'ବିଷୟ/ଶିରୋନାମା ଦେଖଣା:',
'blockedtitle'                     => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଯାଇଅଛି',
'blockednoreason'                  => 'କିଛି କାରଣ ଦିଆଯାଇ ନାହିଁ',
'blockedoriginalsource'            => "'''$1'''ର ମୂଳ ସ୍ରୋତ ତଳେ ଦିଆଯାଇଅଛି:",
'blockededitsource'                => "'''ଆପଣଙ୍କ ସମ୍ପାଦନା''' ରୁ '''$1'''ର ଲେଖା ତଳେ ଦିଆଯାଇଅଛି:",
'whitelistedittitle'               => 'ସମ୍ପାଦନା କରିବା ନିମନ୍ତେ ଲଗ ଇନ ଦରକାର',
'whitelistedittext'                => 'ପୃଷ୍ଠା ସମ୍ପାଦନ ପାଇଁ ଆପଣଙ୍କୁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'confirmedittext'                  => 'ସମ୍ପାଦନା କରିବା ଆଗରୁ ଆପଣଙ୍କୁ ନିଜର ଇ-ମେଲ ଠିକଣାଟିକୁ ଥୟ କରିବାକୁ ପଡ଼ିବ ।
ଆପଣା [[Special:Preferences|ସଭ୍ୟ ପସନ୍ଦ]] ଭିତରୁ ବାଛି ନିଜ ଇ-ମେଲ ଠିକଣାଟିକୁ ଥୟ କରନ୍ତୁ ।',
'nosuchsectiontitle'               => 'ବିଭାଗ ମିଳିଲା ନାହିଁ',
'nosuchsectiontext'                => 'ଆପଣ ସମ୍ପାଦନା କରିବାକୁ ଚେଷ୍ଟା କରୁଥିବା ବିଭାଗଟି ଏଯାଏଁ ତିଆରି କରାଯାଇ ନାହିଁ ।
ଆପଣ ଏହି ପୃଷ୍ଠାଟି ଦେଖିବା ବେଳେ ତାହାକୁ ଘୁଞ୍ଚାଇ ବା ଲିଭାଇ ଦିଆଯାଇ ଥାଇପାରେ ।',
'loginreqtitle'                    => 'ଲଗ ଇନ ଲୋଡ଼ା',
'loginreqlink'                     => 'ଲଗଇନ',
'loginreqpagetext'                 => 'ବାକି ପୃଷ୍ଠାମାନ ଦେଖିବା ନିମନ୍ତେ ଆପଣଙ୍କୁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'accmailtitle'                     => 'ପାସବାର୍ଡ଼ଟି ପଠାଇ ଦିଆଗଲା ।',
'newarticle'                       => '(ନୁଆ)',
'newarticletext'                   => "ଆପଣ ଖୋଲିଥିବା ଲିଙ୍କଟିରେ ଏଯାଏଁ କିଛିବି ପୃଷ୍ଠା ନାହିଁ ।
ଏହି ପୃଷ୍ଠାଟିକୁ ତିଆରି କରିବା ପାଇଁ ତଳ ବାକ୍ସରେ ଟାଇପ କରନ୍ତୁ (ଅଧିକ ଜାଣିବା ପାଇଁ [[{{MediaWiki:Helppage}}|ସାହାଯ୍ୟ ପୃଷ୍ଠା]] ଦେଖନ୍ତୁ) ।
ଯଦି ଆପଣ ଏଠାକୁ ଭୁଲରେ ଆସିଯାଇଥାନ୍ତି ତେବେ ଆପଣଙ୍କ ବ୍ରାଉଜରର '''Back''' ପତିଟି ଦବାନ୍ତୁ ।",
'noarticletext'                    => 'ଏହି ପୃଷ୍ଠାଟିରେ କିଛି ବି ଲେଖା ନାହିଁ ।
ଆପଣ [[Special:Search/{{PAGENAME}}|ଏହି ଲେଖାଟିର ନାଆଁ]] ବାକି ପୃଷ୍ଠାମାନଙ୍କରେ ଖୋଜି ପାରନ୍ତି,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}ରେ ଯୋଡ଼ାଯାଇଥିବା ବାକି ପୃଷ୍ଠାସବୁକୁ ଖୋଜି ପାରନ୍ତି],
କିମ୍ବା [{{fullurl:{{FULLPAGENAME}}|action=edit}} ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରନ୍ତି]</span> ।',
'noarticletext-nopermission'       => 'ଏହି ପୃଷ୍ଠାଟିରେ କିଛି ବି ଲେଖା ନାହିଁ ।
ଆପଣ [[Special:Search/{{PAGENAME}}|ଏହି ଲେଖାଟିର ନାଆଁ]] ବାକି ପୃଷ୍ଠାମାନଙ୍କରେ ଖୋଜି ପାରନ୍ତି,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}}ରେ ଯୋଡ଼ାଯାଇଥିବା ବାକି ପୃଷ୍ଠାସବୁକୁ ଖୋଜି ପାରନ୍ତି],
କିମ୍ବା [{{fullurl:{{FULLPAGENAME}}|action=edit}} ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରନ୍ତି]</span> ।',
'userpage-userdoesnotexist'        => 'ଇଉଜର ଖାତା "$1" ଟି ତିଆରି କରାଯାଇନାହିଁ ।
ଆପଣ ଏହି ପୃଷ୍ଠାଟିକୁ ତିଆରି କରିବାକୁ ଚାହାନ୍ତି କି ନାହିଁ ଦୟାକରି ପରଖି ନିଅନ୍ତୁ ।',
'userpage-userdoesnotexist-view'   => 'ଇଉଜର ନାମ "$1"ଟି ତିଆରି କରାଯାଇ ନାହିଁ ।',
'blocked-notice-logextract'        => 'ଏହି ସଭ୍ୟଙ୍କୁ ଏବେ ପାଇଁ ଅଟକାଯାଇଅଛି ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଲଗ ଇତିହାସ ତଳେ ଦିଆଗଲା ।',
'clearyourcache'                   => "'''ଜାଣିରଖନ୍ତୁ:''' ସାଇତିବା ପରେ ବଦଲଗୁଡ଼ିକ ଦେଖିବା ନିମନ୍ତେ ଆପଣଙ୍କ ବ୍ରାଉଜରର ଅସ୍ଥାୟୀ ସ୍ମୃତି (cache) କୁ ସଫା କରିଦିଅନ୍ତୁ ।
* '''ଫାଇଆରଫକ୍ସ / ସଫାରି:''Reload''ରେ କ୍ଲିକ କଲାବେଳେ ''' ''Shift'' ସୁଇଚଟିକୁ ଚାପି ଧରନ୍ତୁ, କିମ୍ବା ''Ctrl-F5'' ଅଥବା ''Ctrl-R'' (ଏକ Macରେ ''Command-R'') ଦବାନ୍ତୁ
* '''ଗୁଗୁଲ କ୍ରୋମ:''' ''Ctrl-Shift-R'' ଦବାନ୍ତୁ (ଏକ Macରେ ''Command-Shift-R'')
* '''ଇଣ୍ଟରନେଟ ଏକ୍ସପ୍ଲୋରର:''' ''Refresh''ରେ କ୍ଲିକ କରିଲାବେଳେ ''Ctrl'' ଦବାଇ ରଖନ୍ତୁ, କିମ୍ବା ''Ctrl-F5'' ଦବାନ୍ତୁ
* '''କଁକରର:''' ''Reload'' ରେ କ୍ଲିକ କରନ୍ତୁ ଅଥବା ''F5'' ଦବାନ୍ତୁ
* '''ଅପେରା:''' ''Tools → Preferences'' ରୁ ଅସ୍ଥାୟୀ ସ୍ମୃତି (cache) ସଫା କରିଦିଅନ୍ତୁ",
'usercssyoucanpreview'             => "'''ଜାଣିବା କଥା:''' ଆପଣା ନୂଆ CSS ସାଇତିବା ଆଗରୁ \"{{int:showpreview}}\" ବ୍ୟବହାର କରି ପରଖି ନିଅନ୍ତୁ ।",
'userjsyoucanpreview'              => "'''ଜାଣିବା କଥା:''' ଆପଣା ନୂଆ ଜାଭାସ୍କ୍ରିପ୍ଟ (JavaScript) ସାଇତିବା ଆଗରୁ \"{{int:showpreview}}\" ବ୍ୟବହାର କରି ପରଖି ନିଅନ୍ତୁ ।",
'usercsspreview'                   => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ନିଜର ସଭ୍ୟ CSS ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!'''",
'userjspreview'                    => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ନିଜର ସଭ୍ୟ ଜାଭାସ୍କ୍ରିପ୍ଟ (JavaScript) ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!'''",
'sitecsspreview'                   => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ଏହି CSS ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତାଯାଇନାହିଁ!'''",
'sitejspreview'                    => "'''ଜାଣି ରଖନ୍ତୁ ଯେ ଆପଣ କେବଳ ଏହି ଜାଭାସ୍କ୍ରିପ୍ଟ (JavaScript) ଦେଖୁଅଛନ୍ତି ।'''
'''ଏହା ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!'''",
'updated'                          => '(ସତେଜ କରିଦିଆଗଲା)',
'note'                             => "'''ଟୀକା:'''",
'previewnote'                      => "'''ଜାଣିରଖନ୍ତୁ ଯେ, ଏହା କେବଳ ଏକ ଦେଖଣା ।'''
ଆପଣ କରିଥିବା ବଦଳସବୁ ଏଯାଏଁ ସାଇତା ଯାଇନାହିଁ!",
'previewconflict'                  => 'ଉପରେ ଦିଶୁଥିବା ଏହି ଦେଖଣାକୁ ସାଇତିଲେ ଏହା ଏକାପରି ଦେଖାଯିବ ।',
'editing'                          => '$1 କୁ ବଦଳାଉଛି',
'editingsection'                   => '$1 (ଭାଗ)କୁ ବଦଳାଇବେ',
'editingcomment'                   => '$1 (ନୂଆ ଭାଗ)କୁ ବଦଳାଉଛୁ',
'editconflict'                     => 'ବଦଳାଇବା ଦ୍ଵନ୍ଦ: $1',
'explainconflict'                  => "ଆପଣ ବଦଳାଇବା ଆରମ୍ଭ କରିବା ଭିତରେ କେହିଜଣେ ଏହି ପୃଷ୍ଠାକୁ ବଦଳାଇଛନ୍ତି ।</br>
ଉପର ଲେଖା ଜାଗାଟି ଏହା ଯେମିତି ଅଛି ସେମିତି ଥିବା ଲେଖାଟି ଦେଖାଉଛି ।</br>
ତଳ ଜାଗାଟିରେ ଆପଣ କରିଥିବା ବଦଳ ଦେଖାଉଛି ।</br>
ଏବେ ଥିବା ଲେଖାରେ ଆପଣଙ୍କୁ ନିଜ ବଦଳକୁ ମିଶାଇବାକୁ ହେବ ।</br>
ଯଦି ଆପଣ \"{{int:savearticle}}\" ଦବାନ୍ତି ତେବେ '''କେବଳ''' ଉପର ଲେଖାଟି ସାଇତା ହୋଇଯିବ ।",
'yourtext'                         => 'ଆପଣଙ୍କ ଲେଖା',
'storedversion'                    => 'ସାଇତା ସଙ୍କଳନ',
'editingold'                       => "'''ଚେତାବନୀ: ଆପଣ ଏହି ପୃଷ୍ଠାର ଏକ ଅଚଳ ପୁରାତନ ସଙ୍କଳନକୁ ବଦଳାଉଛନ୍ତି ।'''
ଯଦି ଆପଣ ଏହାକୁ ସାଇତିବେ, ନୂଆ ସଙ୍କଳନ ଯାଏଁ କରାଯାଇଥିବା ସବୁ ବଦଳ ନଷ୍ଟ ହୋଇଯିବ ।",
'yourdiff'                         => 'ତଫାତ',
'longpageerror'                    => "'''ଭୁଲ: ଆପଣ ଦେଇଥିବା ଲେଖାଟି $1 କିଲୋବାଇଟ ଲମ୍ବା, ଯାହାକି ସବୁଠାରୁ ଅଧିକ $2 ଠାରୁ ବି ଅଧିକ ।'''
ଏହା ସାଇତାଯାଇପାରିବ ନାହିଁ ।",
'semiprotectedpagewarning'         => "'''ଜାଣିରଖନ୍ତୁ:''' ଏହି ପୃଷ୍ଠାଟିକୁ କିଳାଯାଇଅଛି ଯାହା ଫଳରେ କେବଳ ନାମ ଲେଖାଇଥିବା ସଭ୍ୟ ମାନେ ଏହାକୁ ବଦଳାଇପାରିବେ ।
ଆପଣଙ୍କ ଜାଣିବା ନିମନ୍ତେ ନଗଦ ଲଗ ଇତିହାସ ତଳେ ଦିଆଗଲା:",
'titleprotectedwarning'            => "'''ଚେତାବନୀ: ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଅଛି ଯାହାକୁ ତିଆରିବା ପାଇଁ [[Special:ListGroupRights|ବିଶେଷ କ୍ଷମତା]] ଥିବା ବ୍ୟବାହାରକାରୀ ଲୋଡ଼ା ।'''
ଆପଣଙ୍କ ସୁବିଧା ପାଇଁ ତଳେ ନଗଦ ଲଗ ପ୍ରବେଶ ଦିଆଗଲା:",
'templatesused'                    => 'ଏହି ପୃଷ୍ଠାରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'templatesusedpreview'             => 'ଏହି ଦେଖଣାରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'templatesusedsection'             => 'ଏହି ବିଭାଗରେ ବ୍ୟବହାର କରାଯାଇଥିବା {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'distanttemplatesused'             => 'ଏହି ପୃଷ୍ଠାରେ ବ୍ୟବହାର କରାଯାଇଥିବା ଦୂରର {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'distanttemplatesusedpreview'      => 'ଏହି ଦେଖଣାରେ ବ୍ୟବହାର କରାଯାଇଥିବା ଦୂରର {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'distanttemplatesusedsection'      => 'ଏହି ବ୍ବିଭାଗରେ ବ୍ୟବହାର କରାଯାଇଥିବା ଦୂରର {{PLURAL:$1|ଛାଞ୍ଚ|ଛାଞ୍ଚ ମାନ}}:',
'template-protected'               => '(କିଳାଯାଇଥିବା)',
'template-semiprotected'           => '(ଅଧା କିଳାଯାଇଥିବା)',
'hiddencategories'                 => 'ଏହି ପୃଷ୍ଠାଟି {{PLURAL:$1|ଲୁଚାଯାଇଥିବା ଶ୍ରେଣୀ|$1ଟି ଲୁଚାଯାଇଥିବା ଶ୍ରେଣୀସମୂହ}} ଭିତରୁ ଗୋଟିଏ:',
'nocreatetitle'                    => 'ପୃଷ୍ଠା ଗଢ଼ିବାକୁ ସୀମିତ କରାଯାଇଅଛି',
'nocreatetext'                     => '{{SITENAME}} ନୂଆ ପୃଷ୍ଠା ତିଆରି କରିବାକୁ ବାରଣ କରିଅଛନ୍ତି ।
ଆପଣ ପଛକୁ ଫେରି ଆଗରୁ ଥିବା ପୃଷ୍ଠାଟିଏର ସମ୍ପାଦନା କରିପାରିବେ କିମ୍ବା [[Special:UserLogin|ଲଗ ଇନ କରିପାରିବେ ବା ନୂଆ ଖାତାଟିଏ ତିଆରି କରିପାରିବେ]] ।',
'nocreate-loggedin'                => 'ଆପଣଙ୍କୁ ନୂଆ ପୃଷ୍ଠାଟିଏ ତିଆରିବା ନିମନ୍ତେ ଅନୁମତି ମିଳି ନାହିଁ ।',
'sectioneditnotsupported-title'    => 'ବିଭାଗ ସମ୍ପାଦନା କରାଯାଇପାରିବ ନାହିଁ ।',
'sectioneditnotsupported-text'     => 'ଏହି ପୃଷ୍ଠାରେ ବିଭାଗ ସମ୍ପାଦନା କାମ କରିବ ନାହିଁ ।',
'permissionserrors'                => 'ଅନୁମତି ମିଳିବାରେ ଅସୁବିଧା',
'permissionserrorstext'            => 'ତଳଲିଖିତ {{PLURAL:$1|କାରଣ|କାରଣସବୁ}} ପାଇଁ ଆପଣଙ୍କୁ ଏହା କରିବା ନିମନ୍ତେ ଅନୁମତି ନାହିଁ:',
'permissionserrorstext-withaction' => 'ତଳଲିଖିତ {{PLURAL:$1|କାରଣ|କାରଣସବୁ}} ପାଇଁ ଆପଣଙ୍କୁ $2 ଭିତରକୁ ଅନୁମତି ନାହିଁ:',
'recreate-moveddeleted-warn'       => "'''ସୂଚନା: ଆଗରୁ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାଟିଏକୁ ଆପଣ ଆଉଥରେ ତିଆରୁଛନ୍ତି ।'''

ଆପଣ ଏହି ପୃଷ୍ଠାଟିକୁ ସମ୍ପାଦନା କରିବା ଉଚିତ କି ନୁହେଁ ବିଚାର କରିବା ଦରକାର ।
ଏହି ପୃଷ୍ଠାର ଲିଭାଇବା ଓ ଘୁଞ୍ଚାଇବା ଇତିହାସ ଏଠାରେ ସୁବିଧା ନିମନ୍ତେ ଦିଆଗଲା ।:",
'moveddeleted-notice'              => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଲିଭାଇ ଦିଆଯାଇଅଛି ।
ଏହାର ଲିଭାଇବା ଓ ଘୁଞ୍ଚାଇବା ଇତିହାସ ଆଧାର ନିମନ୍ତେ ତଳେ ଦିଆଗଲା ।',
'log-fulllog'                      => 'ପୁରା ଲଗ ଇତିହାସ ଦେଖନ୍ତୁ',
'edit-hook-aborted'                => 'ସମ୍ପାଦନା ଏକ ହୁକ (hook) ଦେଇ ବାରଣ କରାଗଲା ।
ଏହା କିଛି ବି କାରଣ ଦେଇନାହିଁ ।',
'edit-gone-missing'                => 'ଏହି ପୃଷ୍ଠାଟିକୁ ସତେଜ କରାଯାଇପାରିବ ନାହିଁ ।
ଏହାକୁ ଲିଭାଇ ଦିଆଗଲା ପରି ମନେ ହେଉଛି ।',
'edit-conflict'                    => 'ବଦଳାଇବା ଦ୍ଵନ୍ଦ.',
'edit-no-change'                   => 'ଆପଣଙ୍କ ସମ୍ପାଦନାକୁ ଅଣଦେଖା କରାଗଲା, କାରଣ ଲେଖାରେ କିଛି ବି ବଦଳ କରାଯାଇନଥିଲା ।',
'edit-already-exists'              => 'ନୂଆ ପୃଷ୍ଠାଟିଏ ତିଆରି କରିପାରିଲୁଁ ନାହିଁ ।
ଏହା ଅଗରୁ ଅଛି ।',

# Parser/template warnings
'post-expand-template-inclusion-warning'  => "'''ସୂଚନା:''' ଚାଞ୍ଚଟି ଖୁବ ବଡ଼ ଅଟେ ।
କେତେଗୋଟି ଛାଞ୍ଚକୁ ନିଆଯିବ ନାହିଁ ।",
'post-expand-template-inclusion-category' => 'ଛାଞ୍ଚର ଭିତର ଆକାର ଅଧିକଥିବା ପୃଷ୍ଠା',
'post-expand-template-argument-warning'   => "'''ସୂଚନା:''' ଏହି ପୃଷ୍ଠାରେ ଅତି କମରେ ଗୋଟିଏ ଯୁକ୍ତି ରହିଅଛି ଯାହାର ଖୋଲା ଆକାର ବହୁତ ବଡ଼ ।
ଏହି ଯୁକ୍ତିସବୁକୁ ନଜର ଆଢୁଆଳ କରାଗଲା ।",
'post-expand-template-argument-category'  => 'ଗଣାଯାଉନଥିବା ଛାଞ୍ଚର ଯୁକ୍ତିସବୁକୁ ଧରିଥିବା ପୃଷ୍ଠାସବୁ',
'parser-template-loop-warning'            => 'ଛାଞ୍ଚ ଲୁପ (Template loop) ଦେଖିବାକୁ ମିଳିଲା: [[$1]]',
'parser-template-recursion-depth-warning' => 'ଛାଞ୍ଚର ବାରମ୍ବାର ପ୍ରତୀତ ହେବା କ୍ଷମତା ପାର ହୋଇଅଛି ($1)',
'language-converter-depth-warning'        => 'ଭାଷା ରୂପାନ୍ତରଣ କ୍ଷମତା ସରିଯାଇଅଛି ($1)',

# "Undo" feature
'undo-failure' => 'ଏହି ସମ୍ପାଦନା ପଛକୁ ଫେରାଯାଇ ପାରିବ ନାହିଁ କାରଣ ମଝିରେ ଘଟିଥିବା ଅନେକ ଛୋଟ ଛୋଟ ବଦଳ ଅସୁବିଧା ତିଆରି କରୁଅଛି ।',
'undo-norev'   => 'ଏହି ସମ୍ପାଦନାଟି ପଛକୁ ଫେରାଯାଇପାରିବ ନାହିଁ କାରଣ ଏହା ଆଉ ନାହିଁ ବା ଲିଭାଇଦିଆଯାଇଅଛି ।',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|ଆଲୋଚନା]]) ଙ୍କ ଦେଇ କରାଯାଇଥିବା $1 ସଙ୍କଳନଟି ପଛକୁ ଫେରାଇନିଅନ୍ତୁ',

# Account creation failure
'cantcreateaccounttitle' => 'ଖାତାଟିଏ ତିଆରି କରାଯାଇପାରିବ ନାହିଁ',
'cantcreateaccount-text' => "[[User:$3|$3]]ଙ୍କ ଦେଇ ('''$1''') IP ଠିକଣାରୁ ଖାତା ଖୋଲିବାକୁ ବାରଣ କରାଯାଇଅଛି ।

$3ଙ୍କ ଦେଇ ଦିଆଯାଇଥିବା କାରଣ ହେଲା ''$2''",

# History pages
'viewpagelogs'           => 'ଏହି ପୃଷ୍ଠା ପାଇଁ ଲଗଗୁଡ଼ିକୁ ଦେଖନ୍ତୁ ।',
'nohistory'              => 'ଏହି ପୃଷ୍ଠା ନିମନ୍ତେ କିଛି ବି ସମ୍ପାଦନା ଇତିହାସ ନାହିଁ ।',
'currentrev'             => 'ନଗଦ ସଙ୍କଳନ',
'currentrev-asof'        => '$1 ହୋଇଥିବା ରିଭିଜନ',
'revisionasof'           => '$1 ଅନୁସାରେ କରାଯାଇଥିବା ବଦଳ',
'revision-info'          => '$2ଙ୍କ ଦେଇ $1 ସୁଦ୍ଧା ହୋଇଥିବା ସଙ୍କଳନ',
'previousrevision'       => 'ପୁରୁଣା ସଙ୍କଳନ',
'nextrevision'           => 'ନୂଆ ସଙ୍କଳନ',
'currentrevisionlink'    => 'ନଗଦ ସଙ୍କଳନ',
'cur'                    => 'ଦାନକର',
'next'                   => 'ପରବର୍ତ୍ତୀ',
'last'                   => 'ଆଗ',
'page_first'             => 'ପ୍ରଥମ',
'page_last'              => 'ଶେଷ',
'histlegend'             => "ଅଲଗା ବଛା:ସଙ୍କଳନ ସବୁର ରେଡ଼ିଓ ବାକ୍ସକୁ ବାଛି ତୁଳନା କରନ୍ତୁ ଓ ଏଣ୍ଟର ଦବାନ୍ତୁ ବା ତଳେ ଥିବା ବଟନ ଦବାନ୍ତୁ ।<br />
ସାରକଥା: '''({{int:cur}})''' = ନଗଦ ସଙ୍କଳନରେ ଥିବା ତଫାତ, '''({{int:last}})''' = ଆଗ ସଙ୍କଳନ ଭିତରେ ତଫାତ, '''{{int:minoreditletter}}''' = ଟିକେ ବଦଳ ।",
'history-fieldset-title' => 'ଇତିହାସ ଖୋଜିବା',
'history-show-deleted'   => 'କେବଳ ଲିଭାଯାଇଥିବା',
'histfirst'              => 'ସବୁଠୁ ପୁରୁଣା',
'histlast'               => 'ନଗଦ',
'historysize'            => '({{PLURAL:$1|1 ବାଇଟ|$1 ବାଇଟ}})',
'historyempty'           => '(ଖାଲି)',

# Revision feed
'history-feed-title'          => 'ସଙ୍କଳନ ଇତିହାସ',
'history-feed-description'    => 'ଉଇକିରେ ଏହି ପୃଷ୍ଠାଟିପାଇଁ ସଙ୍କଳନ ଇତିହାସ',
'history-feed-item-nocomment' => '$2 ଠାରେ $1',
'history-feed-empty'          => 'ଅନୁରୋଧ କରାଯାଇଥିବା ପୃଷ୍ଠାଟି ନାହିଁ ।
ଏହା ଏହି ଉଇକିରୁ ଲିଭାଇ ଦିଆଯାଇଅଛି କିମ୍ବା ନୂଆ ନାମ ଦିଆଯାଇଅଛି ।
ପାଖାପାଖି ଏକା ନାମ ଥିବା ନୂଆ ପୃଷ୍ଠାମାନ [[Special:Search|ଏହି ଉଇକିରେ ଖୋଜନ୍ତୁ]] ।',

# Revision deletion
'rev-deleted-comment'         => '(ସମ୍ପାଦନା ଇତିହାସ ଲିଭାଇଦିଆଗଲା)',
'rev-deleted-user'            => '(ଇଉଜର ନାମ ବାହର କରିଦିଆଗଲା)',
'rev-deleted-event'           => '(ଲଗ କାମ ବାହାର କରିଦିଆଗଲା)',
'rev-deleted-user-contribs'   => '[ଇଉଜର ନାମ ବା IP ଠିକଣା ବାହାର କରିଦିଆଗଲା - ଅବଦାନସମୂହରୁ ଲୁଚାଯାଇଥିବା ସମ୍ପାଦନା]',
'rev-suppressed-no-diff'      => "ଆପଣ ତୂଳନାମାନ ଦେଖିପାରିବେ ନାହିଁ କାରଣ ଏଥି ଭିତରୁ ଗୋଟିଏ ସଙ୍କଳନ '''ଲିଭାଇ ଦିଆଯାଇଅଛି''' ।",
'rev-delundel'                => 'ଦେଖାଇବା/ଲୁଚାଇବା',
'rev-showdeleted'             => 'ଦେଖାଇବେ',
'revisiondelete'              => 'ସଙ୍କଳନମାନ ଲିଭାଇଦିଅନ୍ତୁ/ଲିଭାଯାଇଥିଲେ ପଛକୁ ଫେରାଇ ନିଅନ୍ତୁ',
'revdelete-nooldid-title'     => 'ଲକ୍ଷ କରାଯାଇଥିବା ସଙ୍କଳନଟି ଭୁଲ ଅଟେ',
'revdelete-nologtype-title'   => 'କିଛି ଲଗ ପ୍ରକାର ଦିଆଯାଇ ନାହିଁ',
'revdelete-nologtype-text'    => 'ଆପଣ ଏହି କାମଟି କରିବା ନିମନ୍ତେ ଗୋଟିଏ ନିର୍ଦିଷ୍ଟ ଲଗ ଟାଇପ ବାବଦରେ ଜଣାଇନାହାନ୍ତି ।',
'revdelete-nologid-title'     => 'ଭୁଲ ଲଗଟିଏ ଦିଆହୋଇଅଛି',
'revdelete-no-file'           => 'ଆପଣ ସୂଚିତ କରିଥିବା ଫାଇଲଟି ନାହିଁ ।',
'revdelete-show-file-confirm' => 'ଆପଣ ନିଶ୍ଚିତ ତ ଆପଣ $2ରୁ $3 ବେଳେ "<nowiki>$1</nowiki>" ଫାଇଲର ଏକ ଲିଭାଯାଇଥିବା ସଙ୍କଳନକୁ ଏଖିବାକୁ ଚାହାନ୍ତି ?',
'revdelete-show-file-submit'  => 'ହଁ',
'revdelete-selected'          => "'''[[:$1]]ର {{PLURAL:$2|ବଛା ସଙ୍କଳନ|ବଛା ସଙ୍କଳନ}}:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|ବଛା ଲଗ ଘଟଣା|ବଛା ଲଗ ଘଟଣାବଳୀ}}:'''",
'revdelete-legend'            => 'ଦେଖଣା ବାରଣ ସବୁ ଥୟ କରନ୍ତୁ',
'revdelete-hide-text'         => 'ସଙ୍କଳନ ଲେଖା ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-image'        => 'ଫାଇଲ ଭିତର ପଦାର୍ଥସବୁ ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-name'         => 'କାମ ଓ ଲକ୍ଷ ସବୁ ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-comment'      => 'ବଦଳ ସାରକଥା ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-user'         => 'ସମ୍ପାଦକଙ୍କର ଇଉଜର ନାମ /IP ଲୁଚାଇଦିଅନ୍ତୁ',
'revdelete-hide-restricted'   => 'ପରିଛା ଓ ବାକିମାନଙ୍କ ଠାରୁ ତଥ୍ୟକୁ ଦବାଇଦିଅନ୍ତୁ',
'revdelete-radio-same'        => '(ବଦଳାନ୍ତୁ ନାହିଁ)',
'revdelete-radio-set'         => 'ହଁ',
'revdelete-radio-unset'       => 'ନାହିଁ',
'revdelete-suppress'          => 'ପରିଛା ଓ ବାକିମାନଙ୍କ ଠାରୁ ତଥ୍ୟକୁ ଦବାଇଦିଅନ୍ତୁ',
'revdelete-unsuppress'        => 'ଆଉଥରେ ସ୍ଥାପିତ ସଙ୍କଳନସବୁରେ ଥିବା ବାରଣକୁ ବାହାର କରିଦିଅନ୍ତୁ',
'revdelete-log'               => 'କାରଣ:',
'revdelete-submit'            => 'ବଛା {{PLURAL:$1|ସଙ୍କଳନ|ସଙ୍କଳନସବୁ}} ପାଇଁ ଲାଗୁ କରନ୍ତୁ',
'revdelete-success'           => "'''ସଙ୍କଳନ ଦେଖଣା ଭଲଭାବେ ସତେଜ କରାଗଲା ।'''",
'revdelete-failure'           => "'''ସଙ୍କଳନ ଦେଖଣା ସତେଜ କରାଯାଇପାରିଲା ନାହିଁ:'''
$1",
'logdelete-success'           => "'''ଲଗ ଦେଖଣା ଠିକ ଭାବରେ ଥୟ କରାଗଲା ।'''",
'logdelete-failure'           => "'''ଲଗ ଦେଖଣା ଥୟ କରାଯାଇପାରିଲା ନାହିଁ:'''
$1",
'revdel-restore'              => 'ଦେଖଣାକୁ ବଦଳାଇବେ',
'revdel-restore-deleted'      => 'ଲିଭାଯାଇଥିବା ସଙ୍କଳନସବୁ',
'revdel-restore-visible'      => 'ଦେଖାଯାଉଥିବା ସଙ୍କଳନସବୁ',
'pagehist'                    => 'ପୃଷ୍ଠାର ଇତିହାସ',
'deletedhist'                 => 'ଲିଭାଯାଇଥିବା ଇତିହାସ',
'revdelete-otherreason'       => 'ବାକି/ଅଧିକ କାରଣ:',
'revdelete-reasonotherlist'   => 'ଅଲଗା କାରଣ',
'revdelete-edit-reasonlist'   => 'ଲିଭାଇବା କାରଣମାନ ବଦଳାଇବେ',
'revdelete-offender'          => 'ସଙ୍କଳନ ଲେଖକ:',

# Suppression log
'suppressionlog' => 'ଦବାଇବା ଲଗ',

# History merging
'mergehistory'                     => 'ପୃଷ୍ଠାର ଇତିହାସ ସବୁ ଯୋଡ଼ିଦେବେ',
'mergehistory-box'                 => 'ଦୁଇଟି ପୃଷ୍ଠାର ସଙ୍କଳନ ଯୋଡ଼ିଦେବେ:',
'mergehistory-from'                => 'ଉତ୍ସ ପୃଷ୍ଠା:',
'mergehistory-into'                => 'ଲକ୍ଷ ପୃଷ୍ଠା:',
'mergehistory-list'                => 'ଯୋଡ଼ାଯାଇପାରିବା ଭଳି ସମ୍ପାଦନା ଇତିହାସ',
'mergehistory-go'                  => 'ଯୋଡ଼ାଯାଇପାରିବା ଭଳି ସମ୍ପାଦନା',
'mergehistory-submit'              => 'ସଙ୍କଳନସବୁକୁ ମିଶାଇଦେବେ',
'mergehistory-empty'               => 'କୌଣସିଟି ବି ସଙ୍କଳାନ ମିଶାଯାଇପାରିବ ନାହିଁ ।',
'mergehistory-success'             => '[[:$1]]ର $3 {{PLURAL:$3|ଟି ସଙ୍କଳନ|ଟି ସଙ୍କଳନ}} [[:$2]] ସାଙ୍ଗରେ ଠିକଭାବେ ମିଶାଇ ଦିଆଗଲା ।',
'mergehistory-no-source'           => 'ମୂଳ ପୃଷ୍ଠା $1ଟି ନାହିଁ ।',
'mergehistory-no-destination'      => 'ଅନ୍ତ ପୃଷ୍ଠା $1 ଟି ନାହିଁ ।',
'mergehistory-invalid-source'      => 'ମୂଳ ପୃଷ୍ଠାଟି ଏକ ଠିକ ନାମ ହୋଇଥିବା ଉଚିତ ।',
'mergehistory-invalid-destination' => 'ଅନ୍ତ ପୃଷ୍ଠାର ନାମ ସଠିକ ହୋଇଥିବା ଉଚିତ ।',
'mergehistory-autocomment'         => '[[:$2]] ସହିତ [[:$1]]କୁ ଯୋଡ଼ି ଦିଆଗଲା ।',
'mergehistory-comment'             => '[[:$2]] ଭିତରେ [[:$1]]କୁ ଯୋଡ଼ି ଦିଆଗଲା: $3',
'mergehistory-same-destination'    => 'ମୂଳାଧାର ଓ ଅନ୍ତ ପୃଷ୍ଠା ସମାନ ହୋଇପାରିବ ନାହିଁ',
'mergehistory-reason'              => 'କାରଣ:',

# Merge log
'mergelog'           => 'ମିଶ୍ରଣ ଲଗ୍',
'pagemerge-logentry' => '[[$2]] ସହିତ [[$1]]କୁ ଯୋଡ଼ି ଦିଆଗଲା ($3 ଯାଏଁ ସଙ୍କଳନ)',
'revertmerge'        => 'ମିଶାଇବା ନାହିଁ',
'mergelogpagetext'   => 'ତଳେ ସବୁଠାରୁ ନଗଦ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠାର ଇତିହାସ ଆଉ ଗୋଟିଏ ସହ ଦିଆଯାଇଅଛି ।',

# Diffs
'history-title'            => '"$1" ପାଇଁ ସଙ୍କଳନ ଇତିହାସ',
'difference'               => '(ସଙ୍କଳନ ଭିତରେ ଥିବା ତଫାତ)',
'difference-multipage'     => '(ପୃଷ୍ଠା ଭିତରେ ଥିବା ତଫାତ)‌',
'lineno'                   => '$1 କ ଧାଡ଼ି:',
'compareselectedversions'  => 'ବଛାହୋଇଥିବା ସଙ୍କଳନ ଗୁଡ଼ିକୁ ତଉଲିବେ',
'showhideselectedversions' => 'ବଛା ହୋଇଥିବା ସଙ୍କଳନ ଗୁଡ଼ିକୁ ଦେଖାଇବେ/ଲୁଚାଇବେ',
'editundo'                 => 'ପଛକୁ ଫେରିବା',
'diff-multi'               => '({{PLURAL:$2|ଜଣେ ବ୍ୟବହାରକାରୀ|$2 ଜଣ ବ୍ୟବହାରକାରୀ}}ଙ୍କ ଦେଇ ହୋଇଥିବା {{PLURAL:$1|ଗୋଟିଏ ମଝି ସଙ୍କଳନ|$1ଟି ମଝି ସଙ୍କଳନ}} ଦେଖାଯାଉନାହିଁ)',

# Search results
'searchresults'                    => 'ଖୋଜା ଫଳାଫଳ',
'searchresults-title'              => '"$1" ପାଇଁ ଖୋଜିବାରୁ ମିଳିଲା',
'searchresulttext'                 => '{{SITENAME}} ରେ ଖୋଜିବା ବାବଦରେ ଅଧିକ ଜାଣିବା ପାଇଁ,  [[{{MediaWiki:Helppage}}|{{int:help}}]] ଦେଖନ୍ତୁ',
'searchsubtitle'                   => 'ଆପଣ  \'\'\'[[:$1]]\'\'\' ପାଇଁ ([[Special:Prefixindex/$1|"$1" ନାଆଁରେ ଆରମ୍ଭ ହୋଇଥିବା ସବୁ ପୃଷ୍ଠା]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" କୁ ଯୋଡ଼ାଥିବା ସବୁତକ ପୃଷ୍ଠା]])',
'searchsubtitleinvalid'            => "ଆପଣ '''$1''' ଖୋଜିଥିଲେ",
'toomanymatches'                   => 'ବହୁଗୁଡ଼ିଏ ମେଳ ଲେଉଟିଆସିଛି, ଦୟାକରି ନୂଆ ପ୍ରଶ୍ନଟିଏ ସହିତ ଖୋଜନ୍ତୁ ।',
'titlematches'                     => 'ପୃଷ୍ଠାଟିର ନାମ ମିଶୁଅଛି',
'notitlematches'                   => 'ପୃଷ୍ଠାଟିର ନାମ ମିଶୁନାହିଁ',
'textmatches'                      => 'ପୃଷ୍ଠାଟିର ଲେଖା ମିଶୁଅଛି',
'notextmatches'                    => 'ପୃଷ୍ଠାଟିର ନାମ ମିଶୁନାହିଁ',
'prevn'                            => '{{PLURAL:$1|$1}}ର ଆଗରୁ',
'nextn'                            => '{{PLURAL:$1|$1}} ପର',
'prevn-title'                      => 'ଆଗରୁ ମିଳିଥିବା $1ଟି  {{PLURAL:$1|result|ଫଳ}}',
'nextn-title'                      => 'ଆଗର $1ଟି  {{PLURAL:$1|result|ଫଳସବୁ}}',
'shown-title'                      => '$1 ପ୍ରତି ପୃଷ୍ଠାର {{PLURAL:$1|ଫଳାଫଳ|ଫଳାଫଳ}} ଦେଖାଇବେ ।',
'viewprevnext'                     => '($1 {{int:pipe-separator}} $2) ($3) ଟି ଦେଖିବେ',
'searchmenu-legend'                => 'ଖୋଜିବା ବିକଳ୍ପ',
'searchmenu-exists'                => "'''ଏହି ଉଇକିରେ \"[[:\$1]]\" ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ଅଛି ।'''",
'searchmenu-new'                   => "'''\"[[:\$1]]\"ଟି ଏହି ଉଇକିରେ ତିଆରି କରିବେ!'''",
'searchhelp-url'                   => 'Help:ସୂଚୀପତ୍ର',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|ଏହି ନାମ ଆଗରୁ ଥିବା ପୃଷ୍ଠାସବୁ ଖୋଜିବେ]]',
'searchprofile-articles'           => 'ସୂଚୀ ପୃଷ୍ଠା',
'searchprofile-project'            => 'ସାହାଯ୍ୟ ଓ ପ୍ରକଳ୍ପ ପୃଷ୍ଠା',
'searchprofile-images'             => 'ମଲ୍ଟିମିଡ଼ିଆ',
'searchprofile-everything'         => 'ସବୁକିଛି',
'searchprofile-advanced'           => 'ଉନ୍ନତ',
'searchprofile-articles-tooltip'   => '$1ରେ ଖୋଜିବେ',
'searchprofile-project-tooltip'    => '$1ରେ ଖୋଜିବେ',
'searchprofile-images-tooltip'     => 'ଫାଇଲ ସବୁ ପାଇଁ ଖୋଜିବେ',
'searchprofile-everything-tooltip' => 'ପ୍ରସଙ୍ଗ ସବୁକୁ ଖୋଜିବେ (ଆଲୋଚନା ସହ)',
'searchprofile-advanced-tooltip'   => 'ନିଜେ ତିଆରିକରିହେବା ଭଳି ନେମସ୍ପେସରେ ଖୋଜିବେ',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ଶବ୍ଦ|$2 ଶବ୍ଦ}})',
'search-result-category-size'      => '{{PLURAL:$1|ଜଣେ ସଭ୍ୟ|$1 ଜଣ ସଭ୍ୟ}} ({{PLURAL:$2|ଗୋଟିଏ ଶ୍ରେଣୀy|$2ଟି ଶ୍ରେଣୀ ସମୂହ}}, {{PLURAL:$3|ଗୋଟିଏ ଫାଇଲ|$3ଟି ଫାଇଲ}})',
'search-result-score'              => 'ପ୍ରାସଙ୍ଗିକତା: $1%',
'search-redirect'                  => '($1 କୁ ଆଗକୁ ବଢେଇନିଅ )',
'search-section'                   => '(ଭାଗ $1)',
'search-suggest'                   => 'ଆପଣ $1 ଭାବି ଖୋଜିଥିଲେ କି?',
'search-interwiki-caption'         => 'ସାଙ୍ଗରେ ଚାଲିଥିବା ବାକି ପ୍ରକଳ୍ପସବୁ',
'search-interwiki-default'         => '$1 ଫଳାଫଳ:',
'search-interwiki-more'            => '(ଅଧିକ)',
'search-mwsuggest-enabled'         => 'ମତାମତ ସହ',
'search-mwsuggest-disabled'        => 'ମତାମତ ନାହିଁ',
'search-relatedarticle'            => 'ଯୋଡ଼ା',
'mwsuggest-disable'                => 'AJAX ମତାମତକୁ ଅଚଳ କରାଇବେ',
'searcheverything-enable'          => 'ସବୁଗୁଡ଼ିକ ନେମସ୍ପେସରେ ଖୋଜିବେ',
'searchrelated'                    => 'ଯୋଡ଼ା',
'searchall'                        => 'ସବୁ',
'showingresults'                   => "ତଳେ {{PLURAL:$1|'''ଗୋଟିଏ'''  ଫଳାଫଳ|'''$1'''ଟି ଫଳାଫଳ}} ଦେଖାଉଛୁ ଯାହା #'''$2'''ରେ ଆରମ୍ଭ ହୋଇଅଛି ।",
'showingresultsnum'                => "ତଳେ {{PLURAL:$3|ଗୋଟିଏ ଫଳାଫଳ|'''$3'''ଟି ଫଳାଫଳ}} ଦେଖାଉଛୁ ଯାହା #'''$2'''ରେ ଆରମ୍ଭ ହୋଇଅଛି ।",
'showingresultsheader'             => "'''$4''' ପାଇଁ {{PLURAL:$5|'''$3'''ର '''$1''' ଫଳ |'''$3'''ର '''$1 - $2''' ଫଳ }}",
'nonefound'                        => "'''ଜାଣି ରଖନ୍ତୁ''': ଆପଣ ଖାଲି କିଛି ନେମସ୍ପେସକୁ ଆପେ ଆପେ ଖୋଜିପାରିବେ ।
ସବୁ ପ୍ରକାରର ଲେଖା (ଆଲୋଚନା ପୃଷ୍ଠା, ଛାଞ୍ଚ ଆଦି) ଖୋଜିବା ପାଇଁ ନିଜ ପ୍ରଶ୍ନ ଆଗରେ ''all:'' ଯୋଡ଼ି ଖୋଜନ୍ତୁ, ନହେଲେ ଦରକାରି ନେମସ୍ପେସକୁ ଲେଖାର ନାମ ଆଗରେ ଯୋଡ଼ି ବ୍ୟବହାର କରନ୍ତୁ ।",
'search-nonefound'                 => 'ଆପଣ ଖୋଜିଥିବା ପ୍ରଶ୍ନ ପାଇଁ କିଛି ଫଳ ମିଳିଲା ନାହିଁ ।',
'powersearch'                      => 'ଗହିର ଖୋଜା',
'powersearch-legend'               => 'ଗହିର ଖୋଜା',
'powersearch-ns'                   => 'ନେମସ୍ପେସରେ ଖୋଜିବେ',
'powersearch-redir'                => 'ପୁନପ୍ରେରଣ ପୃଷ୍ଠାସମୂହର ତାଲିକା ତିଆରିବେ',
'powersearch-field'                => 'ଖୋଜିବେ',
'powersearch-togglelabel'          => 'ଯାଞ୍ଚ କରିବା:',
'powersearch-toggleall'            => 'ସବୁ',
'powersearch-togglenone'           => 'କିଛି ନାହିଁ',
'search-external'                  => 'ବାହାରେ ଖୋଜା',

# Quickbar
'qbsettings'                => 'ସହଳ ପଟି (Quickbar)',
'qbsettings-none'           => 'କିଛି ନାହିଁ',
'qbsettings-fixedleft'      => 'ବାମକୁ ଥୟ କରାଗଲା',
'qbsettings-fixedright'     => 'ଡାହାଣକୁ ଥୟ କରାଗଲା',
'qbsettings-floatingleft'   => 'ବାମରେ ଭାସନ୍ତା',
'qbsettings-floatingright'  => 'ଡାହାଣରେ ଭାସନ୍ତା',
'qbsettings-directionality' => 'ଆପଣଙ୍କ ଭାଷାର ବାମ-ଡାହାଣ ଲିଖନ ଶୈଳୀ ଅନୁସାରେ ସଜାଡ଼ି ଦିଆଗଲା',

# Preferences page
'preferences'                   => 'ପସନ୍ଦ',
'mypreferences'                 => 'ମୋ ପସନ୍ଦ',
'prefs-edits'                   => 'ସମ୍ପାଦନା ସଂଖ୍ୟା:',
'prefsnologin'                  => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'prefsnologintext'              => 'ବ୍ୟବହାରକାରୀଙ୍କ ପସନ୍ଦସବୁ ବଦଳାଇବା ପାଇଁ ଆପଣଙ୍କୁ <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ଲଗ ଇନ]</span> କରିବାକୁ ପଡ଼ିବ ।',
'changepassword'                => 'ପାସବାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'prefs-skin'                    => 'ବାହାର ଆବରଣ',
'skin-preview'                  => 'ଦେଖଣା',
'datedefault'                   => 'କୌଣସି ପସନ୍ଦ ନାହିଁ',
'prefs-beta'                    => 'Beta କାମସବ୍ନୁ',
'prefs-datetime'                => 'ତାରିଖ ଓ ସମୟ',
'prefs-labs'                    => 'ପରଖଶାଳା ସୁବିଧାସବୁ',
'prefs-personal'                => 'ସଭ୍ୟ ପ୍ରଫାଇଲ',
'prefs-rc'                      => 'ନଗଦ ବଦଳ',
'prefs-watchlist'               => 'ଦେଖଣା ତାଲିକା',
'prefs-watchlist-days'          => 'ଦେଖଣା ତାଲିକାରେ ଦେଖାଯିବା ଦିନ:',
'prefs-watchlist-days-max'      => 'ସବୁଠାରୁ ଅଧିକ ହେଲେ ୭ ଦିନ',
'prefs-watchlist-edits'         => 'ବଢ଼ନ୍ତା ଦେଖଣା ତାଲିକାରେ ଦେଖିହେବା ଭଳି ସବୁଠାରୁ ଅଧିକ ବଦଲ:',
'prefs-watchlist-edits-max'     => 'ସବୁଠାରୁ ଅଧିକ ସଂଖ୍ୟା: ୧୦୦',
'prefs-watchlist-token'         => 'ଦେଖଣା ତାଲିକା ଟୋକନ:',
'prefs-misc'                    => 'ବିଭିନ୍ନ',
'prefs-resetpass'               => 'ପାସବାର୍ଡ଼ ବଦଳାନ୍ତୁ',
'prefs-changeemail'             => 'ଇ-ମେଲ ପରିର୍ବତ୍ତନ କରନ୍ତୁ',
'prefs-setemail'                => 'ଇ-ମେଲ ଠିକଣାଟିଏ  ଥୟ କରିବେ',
'prefs-email'                   => 'ଇ-ମେଲ ବିକଳ୍ପମାନ',
'prefs-rendering'               => 'ଦେଖଣା',
'saveprefs'                     => 'ସାଇତିବେ',
'resetprefs'                    => 'ସାଇତା ହୋଇନଥିବା ବଦଳ ଲିଭାଇଦେବେ',
'restoreprefs'                  => 'ଆପେଆପେ ଥିବା ମୂଳ ସଜାଣିକୁ ଫେରିଯିବେ',
'prefs-editing'                 => 'ସମ୍ପାଦନା',
'prefs-edit-boxsize'            => 'ସମ୍ପାଦନା ଘରର ଆକାର ।',
'rows'                          => 'ଧାଡ଼ି:',
'columns'                       => 'ସ୍ତମ୍ଭସବୁ:',
'searchresultshead'             => 'ଖୋଜିବା',
'resultsperpage'                => 'ପୃଷ୍ଠା ପ୍ରତି ହିଟ:',
'stub-threshold-disabled'       => 'ଅଚଳ କରିଦିଆଯାଇଛି',
'recentchangesdays'             => 'ନଗଦ ବଦଳରେ ଦେଖାଇବା ପାଇଁ ବାକିଥିବା ଦିନ:',
'recentchangesdays-max'         => 'ସବୁଠାରୁ ଅଧିକ ହେଲେ $1 {{PLURAL:$1|ଦିନ|ଦିନ}}',
'recentchangescount'            => 'ଆପେଆପେ ଦେଖାଯାଉଥିବା ବଦଳର ସଂଖ୍ୟା:',
'prefs-help-recentchangescount' => 'ଏଥିରେ ନଗଦ ବଦଳ, ପୃଷ୍ଠାର ଇତିହାସ ଓ ଲଗ ଇତିହାସ ରହିଅଛି ।',
'savedprefs'                    => 'ଆପଣଙ୍କ ପସନ୍ଦସବୁ ସାଇତାଗଲା ।',
'timezonelegend'                => 'ସମୟ ଜୋନ:',
'localtime'                     => 'ସ୍ଥାନୀୟ ସମୟ:',
'timezoneuseserverdefault'      => 'ଉଇକିର ଆପେଆପେ ଆସୁଥିବା ($1) ବ୍ୟବହାର କରିବେ',
'timezoneuseoffset'             => 'ବାକି (ଅଫସେଟ ସ୍ଥିର କରନ୍ତୁ)',
'timezoneoffset'                => 'ଅଫସେଟ¹:',
'servertime'                    => 'ସର୍ଭର ସମୟ:',
'guesstimezone'                 => 'ବ୍ରାଉଜରରୁ ଭରିଦିଅନ୍ତୁ',
'timezoneregion-africa'         => 'ଆଫ୍ରିକା',
'timezoneregion-america'        => 'ଆମେରିକା',
'timezoneregion-antarctica'     => 'ଆଣ୍ଟାର୍କଟିକା',
'timezoneregion-arctic'         => 'ସୁମେରୁ',
'timezoneregion-asia'           => 'ଏସିଆ',
'timezoneregion-atlantic'       => 'ଆଟଲାଣ୍ଟିକ ମହାସାଗର',
'timezoneregion-australia'      => 'ଅଷ୍ଟ୍ରେଲିଆ',
'timezoneregion-europe'         => 'ଇଉରୋପ',
'timezoneregion-indian'         => 'ଭାରତୀୟ ମହାସାଗର',
'timezoneregion-pacific'        => 'ପ୍ରଶାନ୍ତ ମହାସାଗର',
'allowemail'                    => 'ବାକି ସଭ୍ୟମାନଙ୍କ ଠାରୁ ଆସିଥିବା ଇ-ମେଲ ସଚଳ କରାଇବେ',
'prefs-searchoptions'           => 'ଖୋଜିବା ବିକଳ୍ପ',
'prefs-namespaces'              => 'ନେମସ୍ପେସ',
'defaultns'                     => 'ନଚେତ ଏହି ନେମସ୍ପେସ ଗୁଡ଼ିକରେ ଖୋଜନ୍ତୁ:',
'default'                       => 'ପୂର୍ବ ନିର୍ଦ୍ଧାରିତ',
'prefs-files'                   => 'ଫାଇଲ',
'prefs-custom-css'              => 'ମନମୁତାବକ CSS',
'prefs-custom-js'               => 'ମନମୁତାବକ JavaScript',
'prefs-common-css-js'           => 'ସବୁ ଆବରଣ ପାଇଁ ବଣ୍ଟା ହୋଇଥିବା CSS/JavaScript:',
'prefs-reset-intro'             => 'ଆପଣ ଏହି ପୃଷ୍ଠାଟି ବ୍ୟବହାର କରି ଆପଣା ପସନ୍ଦସବୁକୁ ସାଇଟର ଆରମ୍ଭରେ ଥିବା ସଜାଣିକୁ ଲେଉଟାଇଦେଇପାରିବେ ।
ଏହାକୁ ପଛକୁ ଫେରାଯାଇପାରିବ ନାହିଁ',
'prefs-emailconfirm-label'      => 'ଇ-ମେଲ ସଜାଣି:',
'prefs-textboxsize'             => 'ସମ୍ପାଦନା ଘରର ଆକାର',
'youremail'                     => 'ଇ-ମେଲ:',
'username'                      => 'ବ୍ୟବାହରକାରୀଙ୍କର ନାଆଁ:',
'uid'                           => 'ବ୍ୟବହାରକାରୀ ଆଇଡ଼ି:',
'prefs-memberingroups'          => '{{PLURAL:$1|ଗୋଠ|ଗୋଠ ସମୂହ}}ର ସଭ୍ୟ:',
'prefs-registration'            => 'ନାମଲେଖା ବେଳା:',
'yourrealname'                  => 'ପ୍ରକୃତ ନାମ:',
'yourlanguage'                  => 'ଭାଷା:',
'yournick'                      => 'ନୂଆ ଦସ୍ତଖତ:',
'prefs-help-signature'          => 'ଆଲୋଚନା ପୃଷ୍ଠାରେ ଦିଆଯାଉଥିବା ମତାମତରେ "<nowiki>~~~~</nowiki>" ଦେଇଦେଲେ ତାହା ସେଠାରେ ଆପେ ଆପେ ଆପଣଙ୍କ ନାମ ଓ ସମୟକୁ ବଦଳିଯିବ ।',
'badsig'                        => 'ଅଚଳ ଖରାପ ଦସ୍ତଖତ ।
HTML ଟାଗ ପରଖିନିଅନ୍ତୁ ।',
'badsiglength'                  => 'ଆପଣଙ୍କ ଦସ୍ତଖତ ଖୁବ ଲମ୍ବା ।
ଏହା ବୋଧ ହୁଏ $1 {{PLURAL:$1|ଗୋଟି ଅକ୍ଷର|ଗୋଟି ଅକ୍ଷର}}ରୁ ଅଧିକ ।',
'yourgender'                    => 'ଲିଙ୍ଗ:',
'gender-unknown'                => 'ଲୁଚାଯାଇଥିବା',
'gender-male'                   => 'ପୁରୁଷ',
'gender-female'                 => 'ନାରୀ',
'email'                         => 'ଇ-ମେଲ',
'prefs-help-realname'           => 'ପ୍ରକୃତ ନାମ ଦେବା ଆପଣଙ୍କ ଉପରେ ନିର୍ଭର କରେ ।
ଯଦି ଆପଣ ଏହା ଦିଅନ୍ତି, ତେବେ ଏହା ଆପଣଙ୍କ କାମ ପାଇଁ ଶ୍ରେୟ ଦେବାରେ ବ୍ୟବହାର କରାଯାଇପାରିବ ।',
'prefs-help-email'              => 'ଇ-ମେଲ ଠିକଣାଟି ଇଚ୍ଛାଧୀନ, କିନ୍ତୁ ଆପଣ ପାସବାର୍ଡ଼ଟି ଯଦି ଭୁଲିଗଲେ ତାହା ଆଉଥରେ ତିଆରିବା ପାଇଁ ଏହା କାମରେ ଲାଗିବ ।',
'prefs-help-email-others'       => 'ଆପଣ ନିଜର ଇ-ମେଲଟିଏ ନିଜର ସଭ୍ୟ ବା ଆଲୋଚନା ପୃଷ୍ଠାରେ ଦେଇ ଅନ୍ୟମାନଙ୍କୁ ଇ-ମେଲରେ ଯୋଗଯୋଗ କରିବାର ସୁବିଧା ଦେଇପାରିବେ ।
ଆପଣଙ୍କୁ କେହି ମେଲ କଲେ ଆପଣଙ୍କ ଇ-ମେଲ ତାହାଙ୍କୁ ଦେଖାଯିବ ନାହିଁ ।',
'prefs-help-email-required'     => 'ଇ-ମେଲ ଠିକଣାଟି ଲୋଡ଼ା ।',
'prefs-info'                    => 'ସାଧାରଣ ଜାଣିବା କଥା',
'prefs-i18n'                    => 'ଜଗତୀକରଣ',
'prefs-signature'               => 'ଦସ୍ତଖତ',
'prefs-dateformat'              => 'ତାରିଖ ସଜାଣି',
'prefs-timeoffset'              => 'ସମୟ ଆରମ୍ଭ',
'prefs-advancedediting'         => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedrc'              => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedrendering'       => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedsearchoptions'   => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-advancedwatchlist'       => 'ଉନ୍ନତ ବିକଳ୍ପସମୂହ',
'prefs-displayrc'               => 'ଦେଖଣା ବିକଳ୍ପ',
'prefs-displaysearchoptions'    => 'ଦେଖଣା ବିକଳ୍ପ',
'prefs-displaywatchlist'        => 'ଦେଖଣା ବିକଳ୍ପ',
'prefs-diffs'                   => 'ପ୍ରଭେଦ',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'ଇ-ମେଲ ଠିକଣା ବୈଧ ଭଳି ଲାଗୁଅଛି',
'email-address-validity-invalid' => 'ଏକ ବୈଧ ଇ-ମେଲ ଠିକଣା ଦିଅନ୍ତୁ',

# User rights
'userrights'                   => 'ସଭ୍ୟ ଅଧିକାର ପରିଚାଳନା',
'userrights-lookup-user'       => 'ସଭ୍ୟ ଗୋଠ ପରିଚାଳନା କରିବେ',
'userrights-user-editname'     => 'ଇଉଜର ନାମଟିଏ ଦିଅନ୍ତୁ:',
'editusergroup'                => 'ଇଉଜର ଗୋଠ ସମ୍ପାଦନା କରନ୍ତୁ',
'editinguser'                  => "'''[[User:$1|$1]]''' $2 ଙ୍କର ସଭ୍ୟ ଅଧିକାର ବଦଳାଉଅଛୁଁ",
'userrights-editusergroup'     => 'ଇଉଜର ଗୋଠ ସମ୍ପାଦନା କରନ୍ତୁ',
'saveusergroups'               => 'ଇଉଜର ଗୋଠ ସମ୍ପାଦନା କରନ୍ତୁ',
'userrights-groupsmember'      => 'ସଭ୍ୟ ଗୋଠ:',
'userrights-groupsmember-auto' => 'ଆପେଆପେ ଥିବା ସଭ୍ୟ ଗୋଠ:',
'userrights-reason'            => 'କାରଣ:',
'userrights-no-interwiki'      => 'ଆପଣଙ୍କୁ ବାକି ଉଇକିରେ ସଭ୍ୟ ଅଧିକାର ବଦଳାଇବା ନିମନ୍ତେ ଅନୁମତି ମିଳିନାହିଁ ।',
'userrights-nodatabase'        => '$1 ଡାଟାବେସଟି ନାହିଁ ବା କେବଳ ସ୍ଥାନୀୟ ହୋଇ ରହିଛି ।',
'userrights-nologin'           => 'ଆପଣ ପରିଛା ଖାତାରୁ [[Special:UserLogin|ଲଗ ଇନ]] କରି ସଭ୍ୟ ଅଧିକାରର ସୁବିଧା ନେଇପାରିବେ ।',
'userrights-notallowed'        => 'ଆପଣଙ୍କ ଖାତାରେ ସଭ୍ୟ ଅଧିକାର ଯୋଡ଼ିବା ବା କାଢ଼ିବାର ଅନୁମତି ନାହିଁ ।',
'userrights-changeable-col'    => 'ଆପଣ ବଦଳାଇପାରିବା ଗୋଠସମୂହ',
'userrights-unchangeable-col'  => 'ଯେଉଁ ଗୋଠସବୁ ଆପଣ ବଦଳାଇପାରିବେ ନାହିଁ',

# Groups
'group'               => 'ଗୋଠ:',
'group-user'          => 'ବ୍ୟବହାରକାରି',
'group-autoconfirmed' => 'ଆପେଆପେ ଥୟ କରା ସଭ୍ୟ',
'group-bot'           => 'ସ୍ଵୟଂଚାଳିତ ସଭ୍ୟ',
'group-sysop'         => 'ପରିଛାଗଣ',
'group-bureaucrat'    => 'ପ୍ରଶାସକ',
'group-suppress'      => 'ଅଜାଣତ ଅଣଦେଖା',
'group-all'           => '(ସବୁ)',

'group-user-member'          => 'ବ୍ୟବାହାରକାରୀ',
'group-autoconfirmed-member' => 'ଆପେଆପେ ଥୟ କରା ସଭ୍ୟ',
'group-bot-member'           => 'ସ୍ଵୟଂଚାଳିତ ସଭ୍ୟ',
'group-sysop-member'         => 'ପରିଛାଗଣ',
'group-bureaucrat-member'    => 'ପ୍ରଶାସକ',
'group-suppress-member'      => 'ଅଜାଣତ ଅଣଦେଖା',

'grouppage-user'          => '{{ns:project}}:ବ୍ୟବାହାରକାରୀ',
'grouppage-autoconfirmed' => '{{ns:project}}:ଆପେଆପେ ଥୟ କରା ସଭ୍ୟ',
'grouppage-bot'           => '{{ns:project}}:ସ୍ଵୟଂଚାଳିତ ସଭ୍ୟଗଣ',
'grouppage-sysop'         => '{{ns:project}}:ପରିଛା (ଆଡମିନ)',
'grouppage-bureaucrat'    => '{{ns:project}}:ପ୍ରଶାସକଗଣ',
'grouppage-suppress'      => '{{ns:project}}:ଅଜାଣତ ଅଣଦେଖା',

# Rights
'right-read'                  => 'ପୃଷ୍ଠାସବୁକୁ ପଢ଼ିବେ',
'right-edit'                  => 'ପୃଷ୍ଠାସବୁକୁ ବଦଳାଇବେ',
'right-createpage'            => 'ପୃଷ୍ଠା ଗଢ଼ିବେ (ଯେଉଁଗୁଡ଼ିକ ଆଲୋଚନା ପୃଷ୍ଠା ହୋଇନଥିବ)',
'right-createtalk'            => 'ଆଲୋଚନା ପୃଷ୍ଠା ଗଢ଼ିବେ',
'right-createaccount'         => 'ନୂଆ ସଭ୍ୟ ଖାତାମାନ ଗଢ଼ିବେ',
'right-minoredit'             => 'ବଦଳକୁ ଟିକେ ବୋଲି ଚିହ୍ନିତ କରିବେ',
'right-move'                  => 'ପୃଷ୍ଠାସବୁ ଘୁଞ୍ଚେଇବା',
'right-move-subpages'         => 'ପୃଷ୍ଠା ସହିତ ସେମାନଙ୍କର ସାନପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'right-move-rootuserpages'    => 'ମୂଳ ସଭ୍ୟ ପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'right-movefile'              => 'ଫାଇଲସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'right-suppressredirect'      => 'ପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇବା ବେଳେ ମୂଳ ପୃଷ୍ଠାର ଫେରନ୍ତା ପୃଷ୍ଠା ତିଆରି କରିବେ ନାହିଁ',
'right-upload'                => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'right-reupload'              => 'ଆଗରୁ ଥିବା ଫାଇଲ ଉପରେ ମଡ଼ାଇ ଦେବେ',
'right-reupload-own'          => 'ଜଣକ ଦେଇ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଆଗରୁ ଥିବା ଫାଇଲ ଉପରେ ମଡ଼ାଇ ଦେବେ',
'right-reupload-shared'       => 'ବଣ୍ଟାଯାଇଥିବା ସ୍ଥାନୀୟ ମାଧ୍ୟମ ଭଣ୍ଡାରର ଫାଇଲ ଗୁଡ଼ିକ ଭରିଦେବେ',
'right-upload_by_url'         => 'ଏକ URLରୁ ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'right-purge'                 => 'ଥୟ କରିବା ବିନା ପୃଷ୍ଠାର ସାଇଟ ଅସ୍ଥାୟୀ ସ୍ମୃତିକୁ ସତେଜ କରିବେ',
'right-autoconfirmed'         => 'ଅଧା-କିଳାଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ବଦଳାଇବେ',
'right-bot'                   => 'ଏକ ଆପେଆପେ ହେବା ପ୍ରକ୍ରିୟା ଭାବରେ ଗଣିବେ',
'right-nominornewtalk'        => 'ଆଲୋଚନା ପୃଷ୍ଠାସବୁରେ ଛୋଟ ଛୋଟ ବଦଳ ହେଲେ ତାହା ନୂଆ ଚିଟାଉ ପଠାଇବ',
'right-apihighlimits'         => 'API ଖୋଜାର ସର୍ବାଧିକ ସୀମା ବ୍ୟବହାର କରିବେ',
'right-writeapi'              => 'API ଲେଖାର ବ୍ୟବହାର',
'right-delete'                => 'ପୃଷ୍ଠାଟି ଲିଭାଇଦେବେ',
'right-bigdelete'             => 'ବଡ଼ ଇତିହାସ ନଥିବା ପୃଷ୍ଠାସବୁକୁ ଲିଭାଇଦେବେ',
'right-deleterevision'        => 'ଏକ ପୃଷ୍ଠାର ନିର୍ଦ୍ଦିଷ୍ଟ ସଙ୍କଳନମାନ ଲିଭାଇବେ ଓ ଲିଭାଇବାରୁ ରୋକିବେ',
'right-deletedhistory'        => 'ଯୋଡ଼ାଯାଇଥିବା ଲେଖାକୁ ବାଦ ଦେଇ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାର ଇତିହାସ ଦେଖିବେ',
'right-deletedtext'           => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ଲେଖା ଓ ଲିଭାଇ ଦିଆଯାଇଥିବା ଲେଖା ଭିତରର ସଙ୍କଳନର ବଦଳ ଦେଖିବେ',
'right-browsearchive'         => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବେ',
'right-undelete'              => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ପୃଷ୍ଠାଟିଏକୁ ଫେରାଇ ଆଣିବେ',
'right-suppressrevision'      => 'ପରିଛାମାନଙ୍କଠାରୁ ଲୁଚାଯାଇଥିବା ସଙ୍କଳନ ପରଖିବେ ଓ ଲେଉଟାଇବେ',
'right-suppressionlog'        => 'ବ୍ୟକ୍ତିଗତ ଲଗ ଦେଖାଇବେ',
'right-block'                 => 'ବାକି ସଭ୍ୟମାନଙ୍କୁ ସମ୍ପାଦନାରୁ ବାରଣ କରିବେ',
'right-blockemail'            => 'ଇ-ମେଲ ପଠାଇବାରୁ ଜଣେ ସଭ୍ୟଙ୍କୁ ବାରଣ କରିବେ',
'right-hideuser'              => 'ସାଧାରଣରୁ ଲୁଚାଇ ଏକ ଇଉଜର ନାମକୁ ଅଟକାଇବେ',
'right-ipblock-exempt'        => 'IP ଅଟକ, ଆପେଆପେ-ଅଟକ ଓ ସୀମା ଅଟକସବୁକୁ ଅଲଗା ଦିଗଗାମୀ କରିବେ',
'right-proxyunbannable'       => 'ପ୍ରକ୍ସିର ଆପେଆପେ ହେଉଥିବା ଅଟକସବୁକୁ ଅଲଗା ଦିଗଗାମୀ କରିବେ',
'right-unblockself'           => 'ସେମାନଙ୍କୁ ଅଟକରୁ ବାହାର କରିବେ',
'right-protect'               => 'କିଳିବା ସ୍ତରକୁ ବଦଳାଇବେ ଓ କିଳାଯାଇଥିବା ପୃଷ୍ଠାମାନଙ୍କର ସମ୍ପାଦନା କରିବେ',
'right-editprotected'         => 'କିଳାଯାଇଥିବା ପୃଷ୍ଠାମାନଙ୍କର ସମ୍ପାଦନା କରିବେ (କ୍ୟାସକେଡ଼କରା କିଳଣା ବିନା)',
'right-editinterface'         => 'ସଭ୍ୟଙ୍କ ଇଣ୍ଟରଫେସ ବଦଳାଇବେ',
'right-editusercssjs'         => 'ବାକି ସଭ୍ୟମାନଙ୍କର CSS ଓ ଜାଭାସ୍କ୍ରିପ୍ଟ ଫାଇଲ ସବୁକୁ ବଦଳାଇବେ',
'right-editusercss'           => 'ବାକି ସଭ୍ୟମାନଙ୍କ CSS ଫାଇଲସବୁ ବଦଳାଇବେ',
'right-edituserjs'            => 'ବାକି ସଭ୍ୟମାନଙ୍କର ଜାଭାସ୍କ୍ରିପ୍ଟ ଫାଇଲ ସବୁକୁ ବଦଳାଇବେ',
'right-rollback'              => 'ଏକ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠାକୁ ବଦଳାଇଥିବା ଶେଷ ସଭ୍ୟଙ୍କ ସମ୍ପାଦନାକୁ ସଙ୍ଗେସଙ୍ଗେ ପୁରାପୁରି ପଛକୁ ଫେରାଇଦେବେ',
'right-markbotedits'          => 'ପୁରାପୁରି ପଛକୁ ଫେରାଇବା ବଦଳଗୁଡ଼ିକ ଆପେ ଆପେ କରା ବଦଳ ବୋଲି ଗଣିବେ',
'right-noratelimit'           => 'ବିରଳ ସୀମା ଦେଇ ପ୍ରଭାବିତ ହୋଇ ନଥିବା',
'right-import'                => 'ବାକି ଉଇକିରୁ ପୃଷ୍ଠାମାନ ଆମଦାନୀ କରିବେ',
'right-importupload'          => 'ଏକ ଫାଇଲ ଅପଲୋଡ଼ରୁ ଏହି ପୃଷ୍ଠାସବୁ ଆଣିବେ',
'right-patrol'                => 'ବାକି ମାନଙ୍କ ବଦଳକୁ ଜଗାଯାଇଥିବା ବଦଳ ବୋଲି ଚିହ୍ନିତ କରିବେ',
'right-autopatrol'            => 'ଜଣକର ଆପଣା ସମ୍ପାଦନାସବୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ ହୋଇଯାଉ',
'right-patrolmarks'           => 'ନଗଦ ବଦଳସବୁ ଜଗାଯାଇଅଛି ବୋଲି ଚିହ୍ନିତ କରି ଦେଖାଇବେ',
'right-unwatchedpages'        => 'ଦେଖାଯାଇନଥିବା ପୃଷ୍ଠାର ଏକ ତାଲିକା ଦେଖାଇବେ',
'right-trackback'             => 'ଏକ ଟ୍ରାକବ୍ୟାକ ଜମା କରିବେ',
'right-mergehistory'          => 'ପୃଷ୍ଠାମାନଙ୍କର ଇତିହାସ ମିଶାଇଦେବେ',
'right-userrights'            => 'ଇଉଜର ଅଧିକାରସବୁକୁ ବଦଳାଇବେ',
'right-userrights-interwiki'  => 'ବାକି ଉଇକିର ସଭ୍ୟମାନଙ୍କ ସଭ୍ୟ ଅଧିକାର ବଦଳାଇବେ',
'right-siteadmin'             => 'ଡାଟାବେସକୁ କିଳିବେ ଓ ଖୋଲିବେ',
'right-override-export-depth' => '୫ଟି ଯାଏଁ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା ସହିତ ସବୁ ପୃଷ୍ଠାକୁ ରପ୍ତାନୀ କରିବେ',
'right-sendemail'             => 'ବାକି ସଭ୍ୟ ମାନଙ୍କୁ ଇ-ମେଲ ପଠାଇବେ',

# User rights log
'rightslog'                  => 'ସଭ୍ୟଙ୍କ ଅଧିକାରର ଲଗ',
'rightslogtext'              => 'ସଭ୍ୟଙ୍କ ଅଧିକାରର ବଦଳର ଏହା ଏକ ଇତିହାସ ।',
'rightslogentry'             => '$1 ପାଇଁ ଗୋଠ ସଭ୍ୟପଦର ଅବସ୍ଥା $2 ରୁ $3କୁ ବଦଳାଇଦିଆଗଲା',
'rightslogentry-autopromote' => '$2 ରୁ $3କୁ ଆପେଆପେ ଉନ୍ନୀତ କରାଗଲା',
'rightsnone'                 => '(କିଛି ନାହିଁ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ଏହି ପୃଷ୍ଠାଟି ପଢ଼ିବେ',
'action-edit'                 => 'ଏହି ପୃଷ୍ଠାଟିକୁ ବଦଳାଇବା',
'action-createpage'           => 'ପୃଷ୍ଠାଟିଏ ତିଆରିବା',
'action-createtalk'           => 'ଆଲୋଚନା ପୃଷ୍ଠାସବୁ ଗଢ଼ିବେ',
'action-createaccount'        => 'ଏହି ନୂଆ ସଭ୍ୟ ଖାତାଟିଏ ଗଢ଼ିବେ',
'action-minoredit'            => 'ଏହି ବଦଳଟିକୁ ଟିକେ ବଦଳ ଭାବରେ ଚିହ୍ନିତ କରନ୍ତୁ',
'action-move'                 => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'action-move-subpages'        => 'ଏହି ପୃଷ୍ଠାଟିକୁ ତାହାର ଉପପୃଷ୍ଠା ସହିତ ଘୁଞ୍ଚାଇବେ ।',
'action-move-rootuserpages'   => 'ମୂଳ ସଭ୍ୟ ପୃଷ୍ଠାସବୁକୁ ଘୁଞ୍ଚାଇଦେବେ',
'action-movefile'             => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'action-upload'               => 'ଏହି ଫାଇଲଟି ଅପଲୋଡ଼ କରିବେ',
'action-reupload'             => 'ଆଗରୁ ଥିବା ଫାଇଲ ଉପରେ ମଡ଼ାଇ ଦେବେ',
'action-reupload-shared'      => 'ଏହି ଫାଇଲଟି ଏକ ବଣ୍ଟା ଭଣ୍ଡାରରେ ମଡ଼ାଇ ଦେବେ',
'action-upload_by_url'        => 'ଏକ URLରୁ ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'action-writeapi'             => 'API ଲେଖାର ବ୍ୟବହାର',
'action-delete'               => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'action-deleterevision'       => 'ଏହି ସଙ୍କଳନଟି ଲିଭାଇବେ',
'action-deletedhistory'       => 'ପୃଷ୍ଠାର ଲିଭାଯାଇଥିବା ଇତିହାସ ଦେଖିବେ',
'action-browsearchive'        => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ପୃଷ୍ଠାସବୁକୁ ଖୋଜିବେ',
'action-undelete'             => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ପୃଷ୍ଠାଟିଏକୁ ଫେରାଇ ଆଣିବେ',
'action-suppressrevision'     => 'ଲୁଚାଯାଇଥିବା ସଙ୍କଳନକୁ ପରଖି ଆଉଥରେ ସ୍ଥାପନା କରିବେ',
'action-suppressionlog'       => 'ବ୍ୟକ୍ତିଗତ ଲଗ ଦେଖାଇବେ',
'action-block'                => 'ଏହି ସଭ୍ୟଙ୍କୁ ସମ୍ପାଦନାରୁ ବାରଣ କରିବେ',
'action-protect'              => 'ଏହି ପୃଷ୍ଠାର କିଳିବା ସ୍ତରକୁ ବଦଳାଇବେ',
'action-import'               => 'ଆଉ ଏକ ଉଇକିରୁ ଏହି ପୃଷ୍ଠାଟି ଆଣିବେ',
'action-importupload'         => 'ଏକ ଫାଇଲ ଅପଲୋଡ଼ରୁ ଏହି ପୃଷ୍ଠାଟି ଆଣିବେ',
'action-patrol'               => 'ବାକି ମାନଙ୍କ ବଦଳକୁ ଜଗାଯାଇଥିବା ବଦଳ ବୋଲି ଚିହ୍ନିତ କରିବେ',
'action-autopatrol'           => 'ଆପଣା ସମ୍ପାଦନାସବୁକୁ ଜଗାଯାଇଛି ବୋଲି ଚିହ୍ନିତ କରିବେ',
'action-unwatchedpages'       => 'ଦେଖାଯାଇନଥିବା ପୃଷ୍ଠାର ଏକ ତାଲିକା ଦେଖାଇବେ',
'action-trackback'            => 'ଏକ ଟ୍ରାକବ୍ୟାକ ଜମା କରିବେ',
'action-mergehistory'         => 'ପୃଷ୍ଠାର ଇତିହାସ ମିଶାଇଦେବେ',
'action-userrights'           => 'ଇଉଜର ଅଧିକାରସବୁକୁ ବଦଳାଇବେ',
'action-userrights-interwiki' => 'ବାକି ଉଇକିର ସଭ୍ୟମାନଙ୍କ ସଭ୍ୟ ଅଧିକାର ବଦଳାଇବେ',
'action-siteadmin'            => 'ଡାଟାବେସକୁ କିଳିବେ ଓ ଖୋଲିବେ',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ବଦଳ|ବଦଳସବୁ}}',
'recentchanges'                     => 'ନଗଦ ବଦଳ',
'recentchanges-legend'              => 'ଏବେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'recentchangestext'                 => 'ଏହି ପୃଷ୍ଠାରେ ଏହି ଉଇକିରେ ନଗଦ ବଦଳର ନିଘା ରଖିବେ',
'recentchanges-feed-description'    => 'ଏହି ଉଇକିରେ ଏହି ଫିଡ଼ଟିର ନଗଦ ବଦଳ ଦେଖାଇବେ ।',
'recentchanges-label-newpage'       => 'ଏହି ବଦଳ ନୂଆ ଫରଦଟିଏ ତିଆରିକଲା',
'recentchanges-label-minor'         => 'ଏହା ଗୋଟିଏ ଛୋଟ ବଦଳ',
'recentchanges-label-bot'           => "ଏହି ବଦଳଟି ଜଣେ '''ବଟ'''ଙ୍କ ଦେଇ କରାଯାଇଥିଲା",
'recentchanges-label-unpatrolled'   => 'ଏହି ବଦଳଟିକୁ ଏ ଯାଏଁ ପରଖା ଯାଇନାହିଁ',
'rcnote'                            => "ଗତ $5, $4 ସୁଦ୍ଧା {{PLURAL:$2|ଦିନ|'''$2''' ଦିନ}}ରେ ତଳ {{PLURAL:$1|'''ଗୋଟିଏ''' ବଦଳ|'''$1'''ଟି ଶେଷ ବଦଳ}} ହୋଇଅଛି ।",
'rcnotefrom'                        => "'''$2''' ପରର ବଦଳସବୁ ତଳେ ଦିଆଗଲା ('''$1''' ଯାଏଁ ଦେଖାଯାଇଛି) ।",
'rclistfrom'                        => '$1ରୁ ଆରମ୍ଭ କରି ନୂଆ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'rcshowhideminor'                   => '$1 ଟି ଛୋଟମୋଟ ବଦଳ',
'rcshowhidebots'                    => '$1 ଜଣ ବଟ',
'rcshowhideliu'                     => '$1 ଜଣ ନାଆଁ ଲେଖାଇଥିବା ଇଉଜର',
'rcshowhideanons'                   => '$1 ଜଣ ଅଜଣା ଇଉଜର',
'rcshowhidepatr'                    => '$1ଟି ଜଗାହୋଇଥିବା ବଦଳ',
'rcshowhidemine'                    => '$1 ମୁଁ କରିଥିବା ବଦଳ',
'rclinks'                           => 'ଗଲା $2 ଦିନର $1 ବଦଳଗୁଡ଼ିକୁ ଦେଖାଇବେ<br />$3',
'diff'                              => 'ଅଦଳ ବଦଳ',
'hist'                              => 'ଇତିହାସ',
'hide'                              => 'ଲୁଚାଇବେ',
'show'                              => 'ଦେଖାଇବେ',
'minoreditletter'                   => 'ଟିକେ',
'newpageletter'                     => 'ନୂଆ',
'boteditletter'                     => 'ବଟ',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|ସଭ୍ୟ|ସଭ୍ୟଗଣା}}ଙ୍କୁ ଦେଖୁଅଛି]',
'rc_categories'                     => 'ଶ୍ରେଣୀସମୂହ ପାଇଁ ସୀମା ( "|" ଦେଇ ଅଲଗା କରିବେ)',
'rc_categories_any'                 => 'ଯେ କୌଣସି',
'newsectionsummary'                 => '/* $1 */ ନୂଆ ଭାଗ',
'rc-enhanced-expand'                => 'ପୁରା ଦେଖାଇବେ (ଜାଭାସ୍କ୍ରିପଟ ଦରକାର)',
'rc-enhanced-hide'                  => 'ବେଶି କଥାସବୁ ଲୁଚାଇଦିଅ',

# Recent changes linked
'recentchangeslinked'          => 'ଏଇମାତ୍ର ବଦଳାଯାଇଥିବା ପୃଷ୍ଠାର ଲିଙ୍କ',
'recentchangeslinked-feed'     => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-toolbox'  => 'ଯୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-title'    => '"$1" ସାଁଗରେ ଜୋଡ଼ାଥିବା ବଦଳ',
'recentchangeslinked-noresult' => 'ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା ସବୁରେ ଏଇ ସମୟସୀମା ଭିତରେ କିଛି ବଦଳାଯାଇନାହିଁ ।',
'recentchangeslinked-summary'  => "ଏଇଟି କିଛିସମୟ ଆଗରୁ ନିର୍ଦ୍ଦିଷ୍ଟ ପୃଷ୍ଠାରୁ ଲିଙ୍କ ହୋଇଥିବା ଆଉ ବଦଳାଯାଇଥିବା (ଅବା ଗୋଟିଏ ନିର୍ଦ୍ଦିଷ୍ଟ ଶ୍ରେଣୀର) ପୃଷ୍ଠାସବୁର ତାଲିକା ।  [[Special:Watchlist|ମୋର ଦେଖାତାଲିକା]]ର ପୃଷ୍ଠା ସବୁ '''ବୋଲଡ଼'''।",
'recentchangeslinked-page'     => 'ଫରଦର ନାଆଁ',
'recentchangeslinked-to'       => 'ଦିଆଯାଇଥିବା ଫରଦରେ ଯୋଡ଼ା ବାକି ଫରଦମାନଙ୍କର ବଦଳ ସବୁ ଦେଖାନ୍ତୁ ।',

# Upload
'upload'                    => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'uploadbtn'                 => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'reuploaddesc'              => 'ଅପଲୋଡ଼କୁ ନାକଚ କରିବେ ଓ ଅପଲୋଡ଼ ଫର୍ମକୁ ଫେରିବେ',
'upload-tryagain'           => 'ବଦଳିଥିବ ଫାଇଲ ବଖାଣ ପଠାଇବା',
'uploadnologin'             => 'ଲଗ‌‌ ଇନ କରିନାହାନ୍ତି',
'uploaderror'               => 'ଅପଲୋଡ଼ କରିବାରେ ଅସୁବିଧା',
'upload-recreate-warning'   => "'''ଚେତାବନୀ: ସେହି ନାମରେ ଥିବା ଫାଇଲଟି ଲିଭାଯାଇଅଛି ବା ଘୁଞ୍ଚାଯାଇଅଛି ।'''

ଏହି ପୃଷ୍ଠାର ଲିଭାଇବା ବା ଘୁଞ୍ଚାଇବା ଇତିହାସ ଆପଣଙ୍କର ଅବଗତି ନିମନ୍ତେ ଦିଆଗଲା :",
'upload-permitted'          => 'ଅନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର: $1 ।',
'upload-preferred'          => 'ପସନ୍ଦର ଫାଇଲ ପ୍ରକାର: $1 ।',
'upload-prohibited'         => 'ଅନନୁମୋଦିତ ଫାଇଲ ପ୍ରକାର: $1 ।',
'uploadlog'                 => 'ଅପଲୋଡ଼ ଇତିହାସ',
'uploadlogpage'             => 'ଲଗ ଅପଲୋଡ଼ କରିବେ',
'filename'                  => 'ଫାଇଲ ନାମ',
'filedesc'                  => 'ସାରକଥା',
'fileuploadsummary'         => 'ସାରକଥା:',
'filereuploadsummary'       => 'ଫାଇଲ ବଦଳ:',
'filestatus'                => 'କପିରାଇଟ ସ୍ଥିତି:',
'filesource'                => 'ଉତ୍ସ:',
'uploadedfiles'             => 'ଫାଇଲସବୁ ଅପଲୋଡ଼ କରିବେ',
'ignorewarning'             => 'ଚେତାବନୀକୁ ଅଣଦେଖା କରି ଫାଇଲତିକୁ ସେହିପରି ସାଇତି ରଖନ୍ତୁ',
'ignorewarnings'            => 'ଚେତାବନୀ ସବୁକୁ ଅନାଦେଖା କରନ୍ତୁ',
'minlength1'                => 'ଫାଇଲ ନାମଟି ଅତି କମରେ ଗୋଟିଏ ଅକ୍ଷର ହୋଇଥିବା ଲୋଡ଼ା ।',
'badfilename'               => 'ଫାଇଲ ନାମ "$1"କୁ ବଦଳାଇ ଦିଆଯାଇଛି ।',
'filetype-mime-mismatch'    => 'ଫାଇଲ ଏକ୍ସଟେନସନ ".$1" ଚିହ୍ନଟ ହୋଇଥିବା MIME ଫାଇଲ ପ୍ରକାର ସଙ୍ଗେ ମେଳ ଖାଉନାହିଁ ($2) ।',
'filetype-badmime'          => '"$1" MIME ପ୍ରକାରର ଫାଇଲ ଅପଲୋଡ଼ କରିବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'filetype-bad-ie-mime'      => 'ଏହି ଫାଇଲଟି ଅପଲୋଡ଼ କରାଯାଇପାରିବ ନାହିଁ କାରଣ ଇଣ୍ଟରନେଟ ଏକ୍ସପ୍ଲୋରର ଏହାକୁ "$1" ବୋଲି ଚିହ୍ନଟ କରିବ, ଯାହାକି ଏକ ଅନନୁମୋଦିତ ଓ ସମ୍ଭାବିତ ବିପଦଜନକ ଫାଇଲ ପ୍ରକାର ।',
'filetype-unwanted-type'    => "'''\".\$1\"''' ଏକ ଅଦରକାରୀ ଫାଇଲ ପ୍ରକାର ।
ପସନ୍ଦଯୋଗ୍ୟ {{PLURAL:\$3|ଫାଇଲ ପ୍ରକାର|ଫାଇଲ ପ୍ରକାରସବୁ}} ହେଲା \$2 ।",
'filetype-missing'          => 'ଏହି ଫାଇଲଟିର କିଛି ବି ଏକ୍ସଟେନସନ ନାହିଁ (ଯଥା ".jpg") ।',
'empty-file'                => 'ଆପଣ ପଠାଇଥିବା ଫାଇଲଟି ଖାଲି ଅଟେ ।',
'file-too-large'            => 'ଆପଣ ପଠାଇଥିବା ଫାଇଲଟି ବହୁ ବିରାଟ ଅଟେ ।',
'filename-tooshort'         => 'ଫାଇଲ ନାମଟି ଖୁବ ଛୋଟ',
'filetype-banned'           => 'ଏହି ପ୍ରକାରର ଫାଇଲ ବାରଣ କରାଯାଇଅଛି ।',
'verification-error'        => 'ଏହି ଫାଇଲଟି ଫାଇଲ ପରୀକ୍ଷଣରେ ଅସଫଳ ହେଲା ।',
'hookaborted'               => 'ଏକ ଏକ୍ସଟେନସନ ହୁକ ଦେଇ ଆପଣ କରୁଥିବା ବଦଳଟି ବନ୍ଦ କରିଦିଆଗଲା ।',
'illegal-filename'          => 'ଏହି ଫାଇଲ ନାମଟି ଅନୁମୋଦିତ ନୁହେଁ ।',
'overwrite'                 => 'ଅଗରୁଥିବା ଏକ ଫାଇଲ ଉପରେ ମଡ଼ାଇବା ଅନୁମୋଦିତ ନୁହେଁ ।',
'unknown-error'             => 'ଏକ ଅଜଣା ଅସୁବିଧା ଘଟିଲା ।',
'tmp-create-error'          => 'ଅସ୍ଥାୟୀ ଫାଇଲ ତିଆରି କରିପାରିଲୁଁ ନାହିଁ ।',
'tmp-write-error'           => 'ଅସ୍ଥାୟୀ ଫାଇଲ ଲେଖିବାରେ ଅସୁବିଧା ହେଲା ।',
'large-file'                => 'ଫାଇଲ ସବୁ $1ରୁ ବଡ଼ ନ ହେବା ଅନୁମୋଦିତ;
ଏହି ଫାଇଲଟି $2 ।',
'largefileserver'           => 'ଏହି ସର୍ଭରର ଅନୁମୋଦିତ ସଂରଚନା ଠାରୁ ଏହି ଫାଇଲଟି ବଡ଼ ।',
'emptyfile'                 => 'ଆପଣ ଅପଲୋଡ଼ କରିଥିବା ଫାଇଲଟି ଫାଙ୍କା ବୋଲି ବୋଧ ହୁଏ ।
ଏହା ହୁଏତ ଫାଇଲ ନାମରେ କିଛି ଭୁଲ ଜନିତ ହୋଇଥାଇପାରେ ।
ସତରେ ଆପଣ ଏହି ଫାଇଲଟି ଅପଲୋଡ଼ କରିବାକୁ ଚାହାନ୍ତି କି ନାଁ ଠାରେ ପରଖି ନିଅନ୍ତୁ ।',
'windows-nonascii-filename' => 'ଏହି ଉଇକି ବିଶେଷ ସଂକେତ ଥିବା ଫାଇଲ ନାମକୁ ଅନୁମତି ଦିଏ ନାହିଁ ।',
'fileexists'                => "ଏହି ଏକା ନାଆଁରେ ଆଗରୁ ଫାଇଲଟିଏ ଅଛି , ସତରେ ଆପଣ ଏହାକୁ ଅପଲୋଡ଼ କରିବାକୁ ଚାହାନ୍ତି କି ନାଁ ଦୟାକରି '''<tt>[[:$1]]</tt>''' ପରଖି ନିଅନ୍ତୁ ।
[[$1|thumb]]",
'filepageexists'            => "ଏହି ଫାଇଲର ବିବରଣୀ ପୃଷ୍ଠାଟି '''<tt>[[:$1]]</tt>''' ଠାରେ ତିଆରି କରାଯାଇଅଛି, କିନ୍ତୁ ଏହି ନାମରେ ଗୋଟିଏ ବି ଫାଇଲ ନାହିଁ ।
ବିବରଣୀ ପୃଷ୍ଠାରେ ଆପଣ ଦେଇଥିବା ସାରକଥା ଦେଖାଯିବ ନାହିଁ ।
ଆପଣଙ୍କ ବିବରଣୀ ସେଠାରେ ଦେଖାଇବା ନିମନ୍ତେ ଆପଣଙ୍କୁ ନିଜେ ଏହା ବଦଳାଇବାକୁ ପଡ଼ିବ ।
[[$1|thumb]]",
'fileexists-extension'      => "ଏକାପରି ନାଆଁ ଥିବା ଫାଇଲଟିଏ ଆଗରୁ ଅଛି: [[$2|thumb]]
* ଅପଲୋଡ଼ କରାଯାଉଥିବା ଫାଇଲର ନାମ: '''<tt>[[:$1]]</tt>'''
* ଆଗରୁ ଥିବା ଫାଇଲର ନାମ: '''<tt>[[:$2]]</tt>'''
ଦୟାକରି ଅଲଗା ନାମଟିଏ ବାଛନ୍ତୁ ।",
'fileexists-thumbnail-yes'  => "ଫାଇଲଟି ଏକ ସାନ ଆକାରର ଛବି ବୋଲି ବୋଧ ହୁଏ ''(ନଖଦେଖଣା)''.
[[$1|thumb]]
ଦୟାକରି '''<tt>[[:$1]]</tt>''' ଫାଇଲଟି ପରଖି ନିଅନ୍ତୁ ।
ଯଦି ବଛା ଫାଇଲଟି ମୂଳ ଫାଇଲ ଆକାରର ହୋଇଥାଏ ତେବେ ଆଉ ଗୋଟିଏ ନଖଦେଖଣା ସାନ ଛବି ଅପଲୋଡ଼ କରିବାକୁ ପଡ଼ିବ ।",
'file-exists-duplicate'     => 'ଏହି ଫାଇଲଟି ଏହି {{PLURAL:$1|ଫାଇଲଟି|ଫାଇଲ ମାନଙ୍କ}}ର ଏକ ନକଲ ଅଟେ:',
'uploadwarning'             => 'ଅପଲୋଡ଼ ଚେତାବନୀ',
'uploadwarning-text'        => 'ତଳେ ଥିବା ଫାଇଲର ବିବରଣୀ ବଦଳାଇ ଆଉ ଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'savefile'                  => 'ଫାଇଲ ସଂରକ୍ଷଣ କରିବା',
'uploadedimage'             => '"[[$1]]" ଅପଲୋଡ କରାଗଲା',
'overwroteimage'            => '"[[$1]]"ର ନୂଆ ସଙ୍କଳନଟିଏ ଅପଲୋଡ଼ କରାଗଲା',
'uploaddisabled'            => 'ଅପଲୋଡ଼ କରିବା ଅଚଳ କରାଯାଇଅଛି ।',
'copyuploaddisabled'        => 'URL ଦେଇ ଅପଲୋଡ଼ କରିବାକୁ ଅଚଳ କରାଯାଇଅଛି ।',
'uploadfromurl-queued'      => 'ଆପଣଙ୍କ ଅପଲୋଡ଼ ଧାଡ଼ିରେ ରହିଲା ।',
'uploaddisabledtext'        => 'ଫାଇଲ ଅପଲୋଡ଼  ଅଚଳ କରାଯାଇଅଛି ।',
'php-uploaddisabledtext'    => 'PHPରେ ଫାଇଲ ଅପଲୋଡ଼କୁ ଅଚଳ କରାଯାଇଅଛି ।
ଦୟାକରି ଫାଇଲ_ଅପଲୋଡ଼ ସଜାଣିକୁ ପରଖି ନିଅନ୍ତୁ ।',
'uploadscripted'            => 'ଏହି ଫାଇଲଟିରେ HTML ବା ସ୍କ୍ରିପ୍ଟ କୋଡ଼ ଥିବାରୁ ଏକ ବେବ ବ୍ରାଉଜରରେ ଅଲଗା ରଖିବେ ।',
'uploadvirus'               => 'ଏହି ଫାଇଲଟିରେ ଏକ ଭାଇରସ ରହିଅଛି!
ସବିଶେଷ: $1',
'upload-source'             => 'ଉତ୍ସ ଫାଇଲ',
'sourcefilename'            => 'ମୂଳ ଫାଇଲ ନାମ:',
'sourceurl'                 => 'ଉତ୍ସ ୟୁ.ଆର୍.ଏଲ୍.:',
'destfilename'              => ':ମିକାମ ଫାଇଲ ନାମ:',
'upload-maxfilesize'        => 'ସବୁଠାରୁ ବଡ଼ ଫାଇଲ ଆକାର: $1',
'upload-description'        => 'ଫାଇଲ ବିବରଣୀ',
'upload-options'            => 'ଅପଲୋଡ଼ ବିକଳ୍ପସମୂହ',
'watchthisupload'           => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'filewasdeleted'            => 'ଆଗରୁ ଏହି ନାମରେ ଅପଲୋଡ଼ କରାଯାଇଥିବା ଫାଇଲଟିଏ ଲିଭାଇ ଦିଆଯାଇଅଛି  ।
ଆଉଥରେ ଅପଲୋଡ଼ କରିବା ଆଗରୁ ଆପଣ $1ଟି ଠାରେ ପରଖି ନିଅନ୍ତୁ ।',
'filename-bad-prefix'       => "ଆପଣ ଅପଲୋଡ଼ କରୁଥିବା '''\"\$1\"'' ନାମରେ ଆରମ୍ଭ ହେଉଥିବା ଫାଇଲ, ଯାହାକି ଏକ ବଖଣାଯାଇନଥିବା ନାମରେ ନାମିତ ଓ ଆପେଆପେ ଡିଜିଟାଲ କ୍ୟାମେରାରୁ ଆସିଥାଏ ।
ଦୟାକରି ଏହି ଫାଇଲ ପାଇଁ ଏକ ବୁଝାପଡୁଥିବା ନାମଟିଏ ଦିଅନ୍ତୁ ।",
'upload-success-subj'       => 'ଅପଲୋଡ଼ ସଫଳ',
'upload-success-msg'        => 'ଆପଣଙ୍କ ଅପଲୋଡ଼ ଫର୍ମ [$2] ସଫଳ ହେଲା । ଏହା [[:{{ns:file}}:$1]] ଠାରେ ମିଳୁଅଛି ।',
'upload-failure-subj'       => 'ଅପଲୋଡ଼ରେ ଅସୁବିଧା',
'upload-failure-msg'        => ' [$2]ରୁ ଆପଣଙ୍କ କରିଥିବା ଅପଲୋଡ଼ରେ ଗୋଟିଏ ଅସୁବିଧା ଥିଲା:

$1',
'upload-warning-subj'       => 'ଅପଲୋଡ଼ ଚେତାବନୀ',
'upload-warning-msg'        => '[$2]ରୁ ଆପଣ କରିଥିବା ଅପଲୋଡରେ ଗୋଟିଏ ଅସୁବିଧା ଥିଲା । ଆପଣ [[Special:Upload/stash/$1|ଅପଲୋଡ଼ ଫର୍ମ]]କୁ ଫେରି ଏହି ଅସୁବିଧାଟିକୁ ସୁଧାରି ପାରନ୍ତି ।',

'upload-proto-error'        => 'ଭୁଲ ପ୍ରଟକଲ',
'upload-proto-error-text'   => 'ସୁଦୂରର ଅପଲୋଡ଼ପାଇଁ URL ସବୁ <code>http://</code> କିମ୍ବା <code>ftp://</code> ରେ ଆରମ୍ଭ ହେଉଥିବା ଲୋଡ଼ା ।',
'upload-file-error'         => 'ଭିତରର ଭୁଲ',
'upload-file-error-text'    => 'ସର୍ଭରରେ ଅସ୍ଥାୟୀ ଫାଇଲଟିଏ ତିଆରିବା ବେଳେ ଏକ ଭିତରର ଅସୁବିଧାଟିଏ ଘଟିଲା ।
ଦୟାକରି ଜଣେ [[Special:ListUsers/sysop|ପରିଛା]]ଙ୍କ ସହ ଯୋଗାଯୋଗ କରନ୍ତୁ ।',
'upload-misc-error'         => 'ଅଜଣା ଅପଲୋଡ଼ ଅସୁବିଧା',
'upload-too-many-redirects' => 'ଏହି URL ଟିରେ ଅନେକ ଗୁଡ଼ିଏ ଫେରନ୍ତା ଲିଙ୍କ ଅଛି',
'upload-unknown-size'       => 'ଅଜଣା ଆକାର',
'upload-http-error'         => 'HTTP ଅସୁବିଧାଟିଏ ଘଟିଲା: $1',

# ZipDirectoryReader
'zip-file-open-error' => 'ZIP ପରଖିବା ପାଇଁ ଫାଇଲଟି ଖୋଲିଲା ବେଳେ ଅସୁବିଧାଟିଏ ଘଟିଲା ।',
'zip-wrong-format'    => 'ଦିଆଯାଇଥିବା ଫାଇଲଟି ଏକ ZIP ଫାଇଲ ନୁହେଁ ।',
'zip-bad'             => 'ଏହା ଏକ ବିଗିଡ଼ି ଯାଇଥିବା ଫାଇଲ ବା ପଢ଼ାଯାଇପାରୁନଥିବା ZIP ଫାଇଲ ।
ଏହାକୁ ପ୍ରତିରକ୍ଷା ନିମନ୍ତେ  ଠିକ ଭାବେ ପରଖା ଯାଇପାରିବ ନାହିଁ ।',
'zip-unsupported'     => 'ଏହା ଏକ ZIP ଫାଇଲ ଯାହା ZIP ବିଶେଷତା ସବୁ ବ୍ୟବହାର କରିଥାଏ ଓ ମିଡ଼ିଆଉଇକି ଦେଇ ଅନୁମୋଦିତ ନୁହେଁ ।
ଏହା ଠିକ ଭାବେ ପ୍ରତିରକ୍ଷା ପାଇଁ ପରଖା ଯାଇପାରିବ ନାହିଁ ।',

# Special:UploadStash
'uploadstash'          => 'ଭଣ୍ଡାର ଅପଲୋଡ଼ କରିବେ',
'uploadstash-clear'    => 'ଲୁଚାଯାଇଥିବା ଭଣ୍ଡାର ଫାଇଲ ସବୁକୁ ସଫା କରିଦେବେ',
'uploadstash-nofiles'  => 'ଆପଣଙ୍କ ପାଖରେ ଗୋଟିଏ ବି ଲୁଚାଯାଇଥିବା ଭଣ୍ଡାରର ଫାଇଲ ନାହିଁ ।',
'uploadstash-badtoken' => 'ସେହି କାମଟି କରିବାରେ ଅସଫଳ ହେଲୁ, ବୋଧେ ଆପଣଙ୍କ ଇଉଜର ନାମ ଓ ପାସବାର୍ଡ଼ ଆଦିର ମିଆଦ ପୁରିଗଲାଣି । ଆଉଥରେ ଚେଷ୍ଟା କରନ୍ତୁ ।',
'uploadstash-errclear' => 'ଫାଇଲମାନଙ୍କର ଲିଭାଇବା ଅସଫଳ ରହିଲା ।',
'uploadstash-refresh'  => 'ଫାଇଲମାନଙ୍କର ତାଲିକାକୁ ସତେଜ କରିବେ',

# img_auth script messages
'img-auth-accessdenied'     => 'ଭିତରକୁ ପଶିବାକୁ ବାରଣ କରାଗଲା',
'img-auth-nopathinfo'       => 'PATH_INFO ମିଳୁନାହିଁ ।
ଆପଣଙ୍କ ସର୍ଭରକୁ ଏହି ତଥ୍ୟ ପଠାଇବା ନିମନ୍ତେ ସଜାଯାଇ ନାହିଁ ।
ଏହା CGI-ଭିତ୍ତିକ ହୋଇପାରେ ଓ img_auth କୁ ସହଯୋଗ କରିନପାରେ ।
http://www.mediawiki.org/wiki/Manual:Image_Authorization ଦେଖନ୍ତୁ ।',
'img-auth-notindir'         => 'ଅନୁରୋଧ କରାଯାଇଥିବା ପଥ ସଂରଚିତ ଅପଲୋଡ଼ ତାଲିକା ନୁହେଁ ।',
'img-auth-badtitle'         => '"$1"ରୁ ଏକ ସଠିକ ଶିରୋନାମା ଗଠନ କରିବାରେ ଅସଫଳ ହେଲୁଁ ।',
'img-auth-nologinnWL'       => 'ଆପଣ ଲଗ ଇନ କରି ନାହାନ୍ତି ଓ "$1" ଏକ ଅନୁମୋଦିତ ତାଲିକାରେ ନାହିଁ ।',
'img-auth-nofile'           => '"$1" ଫାଇଲଟି ଏଠାରେ ନାହିଁ ।',
'img-auth-isdir'            => 'ଆପଣ "$1" ସୂଚି ତାଲିକାଟି ଦେଖିବାକୁ ଚେଷ୍ଟା କରୁଛନ୍ତି ।
ଏଠାରେ କେବଳ ଫାଇଲ ଦେଖିବା ଅନୁମୋଦିତ ।',
'img-auth-streaming'        => '"$1" ନିୟମିତ ଲୋଡ଼ ଚାଲିଅଛି ।',
'img-auth-noread'           => '"$1" ପଢ଼ିବା ନିମନ୍ତେ ସଭ୍ୟଙ୍କୁ ଅନୁମତି ମିଳିନାହିଁ ।',
'img-auth-bad-query-string' => 'URL ଟିର ଖୋଜାଯିବା ପ୍ରଶ୍ନଟି ଅଚଳ ଅଟେ ।',

# HTTP errors
'http-invalid-url'      => 'ଅଚଳ URL: $1',
'http-invalid-scheme'   => '"$1" ପ୍ରକାରର URL ମାନ ଏଠାରେ କାମ କରିବ ନାହିଁ ।',
'http-request-error'    => 'ଅଜଣା କାରଣରୁ HTTP ଅନୁରୋଧ ବିଫଳ ହେଲା ।',
'http-read-error'       => 'HTTP ପଢ଼ିବା ଭୁଲ ।',
'http-timed-out'        => 'HTTP ଅନୁରୋଧ ମିଆଦ ପୁରିଗଲା ।',
'http-curl-error'       => '$1 URL କୁ ପାଇବାରେ ବିଫଳ',
'http-host-unreachable' => 'URLଟି ପାଇଲୁ ନାହିଁ ।',
'http-bad-status'       => 'HTTP ଅନୁରୋଧ ବେଳେ କିଛି ଅସୁବିଧା ହେଲା : $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'      => 'URL ଠେଇଁ ପହଞ୍ଚି ପାଇଲୁ ନାହିଁ ।',
'upload-curl-error6-text' => 'ଦିଆଯାଇଥିବା URL ଠେଇଁ ପହଞ୍ଚି ପାରିଲୁଁ ନାହିଁ ।
URLଟି ଠିକ ଅଚିକି କି ନାଁ ଓ ସାଇଟଟି ସଚଳ ଅଚିକି କି ନାଁ ଦୟାକରି ଆଉଥରେ ପରଖି ନିଅନ୍ତୁ ।',
'upload-curl-error28'     => 'ଅପଲୋଡ଼ କରିବା ମିଆଦ ସରିଗଲା',

'license'            => 'ସ୍ଵତ୍ଵ:',
'license-header'     => 'ସ୍ଵତ୍ଵ',
'nolicense'          => 'ଗୋଟିଏ ବି ବଛାଯାଇନାହିଁ',
'license-nopreview'  => '(ସାଇତିବା ଆଗଦେଖଣା ମିଳୁନାହିଁ)',
'upload_source_url'  => '(ଏକ ବୈଧ ସାଧାରଣରେ ଖୋଲାଯାଇପାରୁଥିବା URL)',
'upload_source_file' => '(ଆପଣଙ୍କ କମ୍ପୁଟରରେ ଥିବା ଏକ ଫାଇଲ)‌',

# Special:ListFiles
'listfiles_search_for'  => 'ମାଧ୍ୟମ ନାମଟି ଖୋଜିବେ:',
'imgfile'               => 'ଫାଇଲ',
'listfiles'             => 'ଫାଇଲ ତାଲିକା',
'listfiles_thumb'       => 'ଛୋଟ ଛବି',
'listfiles_date'        => 'ତାରିଖ',
'listfiles_name'        => 'ନାମ',
'listfiles_user'        => 'ବ୍ୟବାହାରକାରୀ',
'listfiles_size'        => 'ଆକାର',
'listfiles_description' => 'ବିବରଣୀ',
'listfiles_count'       => 'ସଂସ୍କରଣ',

# File description page
'file-anchor-link'          => 'ଫାଇଲ',
'filehist'                  => 'ଫାଇଲ ଇତିହାସ',
'filehist-help'             => 'ଏହା ଫାଇଲଟି ସେତେବେଳେ ଯେମିତି ଦିଶୁଥିଲା ତାହା ଦେଖିବା ପାଇଁ ତାରିଖ/ବେଳା ଉପରେ କ୍ଲିକ କରନ୍ତୁ',
'filehist-deleteall'        => 'ସବୁକିଛି ଲିଭାଇ ଦେବେ',
'filehist-deleteone'        => 'ଲିଭେଇବେ',
'filehist-revert'           => 'ପଛକୁ ଫେରାଇବେ',
'filehist-current'          => 'ଏବେକାର',
'filehist-datetime'         => 'ତାରିଖ/ବେଳ',
'filehist-thumb'            => 'ନଖ ଦେଖଣା',
'filehist-thumbtext'        => '$1 ପରିକା ସଙ୍କଳନର ନଖଦେଖଣା',
'filehist-nothumb'          => 'ସାନଦେଖଣା ନାହିଁ',
'filehist-user'             => 'ବ୍ୟବାହାରକାରୀ',
'filehist-dimensions'       => 'ଆକାର',
'filehist-filesize'         => 'ଫାଇଲ ଆକାର',
'filehist-comment'          => 'ମତାମତ',
'filehist-missing'          => 'ଫାଇଲ ମିଳୁନାହିଁ',
'imagelinks'                => 'ଫାଇଲର ଲିଙ୍କସବୁ',
'linkstoimage'              => 'ଏହି ସବୁ{{PLURAL:$1|ପୃଷ୍ଠା|$1 ପୃଷ୍ଠାସବୁ}} ଏହି ଫାଇଲଟିକୁ ଯୋଡ଼ିଥାନ୍ତି:',
'nolinkstoimage'            => 'ଏହି ଫାଇଲ ସହିତ ଯୋଡ଼ା ଗୋଟିଏ ବି ପୃଷ୍ଠା ନାହିଁ ।',
'morelinkstoimage'          => 'ଏହି ଫାଇଲ ସହିତ ଯୋଡ଼ା [[Special:WhatLinksHere/$1|ଆହୁରି ଅଧିକ ଲିଙ୍କ]] ଦେଖନ୍ତୁ ।',
'linkstoimage-redirect'     => '$1 (ଫାଇଲ ଅନୁପ୍ରେରଣ) $2',
'duplicatesoffile'          => 'ତଳଲିଖିତ {{PLURAL:$1|ଫାଇଲଟି ଏହି ଫାଇଲର ଏକ ନକଲ|$1 ଫାଇଲସବୁ ଏହି ଫାଇଲର ନକଲ ଅଟନ୍ତି}} ([[Special:FileDuplicateSearch/$2|ଅଧିକ ସବିଶେଷ]]):',
'sharedupload'              => 'ଏହି ଫାଇଲଟି $1 ରୁ ଆଉ ବାକି ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର କରାଯାଇପାରିବ .',
'sharedupload-desc-there'   => 'ଏହି ଫାଇଲଟି $1 ଠାରୁ ଓ ବାକି ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର ହୋଇପାରେ ।
ଅଧିକ ଜାଣିବା ନିମନ୍ତେ ଦୟାକରି [$2 ଫାଇଲ ବିବରଣୀ ପୃଷ୍ଠା ଦେଖନ୍ତୁ] ।',
'sharedupload-desc-here'    => 'ଏହି ଫାଇଲଟି $1 ଠାରୁ ଓ ଏହା ଅନ୍ୟ ପ୍ରକଳ୍ପରେ ବ୍ୟବହାର କରାଯାଇପାରିବ ।
ଏହାର ବିବରଣୀ [$2 ଫାଇଲ ବିବରଣୀ ପୃଷ୍ଠାରେ] ତଳେ ରହିଅଛି ।',
'filepage-nofile'           => 'ଏହି ନାମରେ କୌଣସିଟି ଫାଇଲ ନାହିଁ ।',
'filepage-nofile-link'      => 'ଏହି ନାମରେ କିଛି ବି ଫାଇଲ ନାହିଁ, କିନ୍ତୁ ଆପଣ ଏହା [$1 ଅପଲୋଡ଼] କରିପାରିବେ ।',
'uploadnewversion-linktext' => 'ଏହି ଫାଇଲର ନୂଆ ସଙ୍କଳନଟିଏ ଅପଲୋଡ଼ କରିବେ',
'shared-repo-from'          => '$1 ଠାରୁ',
'shared-repo'               => 'ଏକ ବଣ୍ଟା ଭଣ୍ଡାର',

# File reversion
'filerevert'                => '$1କୁ ଫେରାଇଦେବା',
'filerevert-legend'         => 'ପଛକୁ ଫେରାଇବା ଫାଇଲ',
'filerevert-intro'          => "ଆପଣ '''[[Media:$1|$1]]''' ଫାଇଲଟିକୁ [$4 ତମ ସଙ୍କଳନକୁ $3, $2 ବେଳେ] ଫେରାଇବାକୁ ଯାଉଛନ୍ତି ।",
'filerevert-comment'        => 'କାରଣ:',
'filerevert-defaultcomment' => '$2, $1 ବେଳେ ସଙ୍କଳନକୁ ଫେରାଇ ଦିଆଗଲା',
'filerevert-submit'         => 'ପଛକୁ ଫେରାଇବେ',
'filerevert-success'        => "'''[[Media:$1|$1]]''' [$3, $2 ବେଳେ $4 ତମ ସଙ୍କଳନକୁ] ଫେରାଇଦିଆଗଲା ।",
'filerevert-badversion'     => 'ଏହି ଫାଇଲର କିଛି ବି ଆଗର ସ୍ଥାନୀୟ ଫାଇଲ ନାହିଁ ଯେଉଁଥିରେ ଏହି ସମୟ ଛିହ୍ନଟି ଦିଆଯାଇଛି ।',

# File deletion
'filedelete'                  => 'ଲିଭାଇବା $1',
'filedelete-legend'           => 'ଫାଇଲଟି ଲିଭାଇବେ',
'filedelete-comment'          => 'କାରଣ:',
'filedelete-submit'           => 'ଲିଭେଇବେ',
'filedelete-reason-otherlist' => 'ଅଲଗା କାରଣ',
'filedelete-edit-reasonlist'  => 'ଲିଭାଇବା କାରଣମାନ ବଦଳାଇବେ',

# MIME search
'mimesearch' => 'MIME ଖୋଜା',
'mimetype'   => 'MIME ପ୍ରକାର:',
'download'   => 'ଡାଉନଲୋଡ଼',

# Unwatched pages
'unwatchedpages' => 'ଦେଖାହୋଇନଥିବା ପୃଷ୍ଠା',

# List redirects
'listredirects' => 'ପୁନପ୍ରେରଣ ପୃଷ୍ଠାସମୂହର ତାଲିକା',

# Unused templates
'unusedtemplates'    => 'ବ୍ୟବହାର ହୋଇନଥିବା ଛାଞ୍ଚ',
'unusedtemplateswlh' => 'ଅନ୍ୟ ସଂଯୋଗ',

# Random page
'randompage'         => 'ଯାହିତାହି ପୃଷ୍ଠା',
'randompage-nopages' => 'ତଳେ ଥିବା {{PLURAL:$2|ନେମସ୍ପେସ|ନେମସ୍ପେସ}}: $1ରେ ଗୋଟିଏ ବି ପୃଷ୍ଠା ନାହିଁ ।',

# Random redirect
'randomredirect'         => 'ଯାହିତାହି ପୁନପ୍ରେରଣ',
'randomredirect-nopages' => '"$1" ନାମରେ ଗୋଟିଏ ବି ପୁନପ୍ରେରଣ ନାହିଁ ।',

# Statistics
'statistics'               => 'ହିସାବ',
'statistics-header-pages'  => 'ପୃଷ୍ଠା ପରିସଙ୍ଖ୍ୟାନ',
'statistics-header-edits'  => 'ପରିସଙ୍ଖ୍ୟାନ ସମ୍ପାଦନା',
'statistics-header-views'  => 'ପରିସଙ୍ଖ୍ୟାନ ଦେଖିବେ',
'statistics-header-users'  => 'ସଭ୍ୟ ପରିସଙ୍ଖ୍ୟାନ',
'statistics-header-hooks'  => 'ବାକି ପରିସଙ୍ଖ୍ୟାନ',
'statistics-articles'      => 'ସୂଚୀ ପୃଷ୍ଠାସମୂହ',
'statistics-pages'         => 'ପୃଷ୍ଠା',
'statistics-pages-desc'    => 'ଆଲୋଚନା ପୃଷ୍ଠା, ପୁନପ୍ରେରଣକୁ ମିଶାଇ ଏହି ଉଇକିର ପୃଷ୍ଠାମାନ ।',
'statistics-files'         => 'ଫାଇଲସବୁ ଅପଲୋଡ଼ କରାଗଲା',
'statistics-edits'         => '{{SITENAME}} ତିଆରିହେବା ବେଳରୁ ପୃଷ୍ଠାର ନାମ',
'statistics-edits-average' => 'ପୃଷ୍ଠାପ୍ରତି ହାରାହାରି ସମ୍ପାଦନା',
'statistics-views-total'   => 'ମୋଟ ଦେଖଣା',
'statistics-views-peredit' => 'ସମ୍ପାଦନା ପ୍ରତି ଦେଖା',
'statistics-users'         => 'ପଞ୍ଜିକରଣ କରିଥିବା [[Special:ListUsers|ସଭ୍ୟଗଣ]]',
'statistics-users-active'  => 'ସଚଳ ସଭ୍ୟ',

'disambiguationspage' => 'Template:ବହୁବିକଳ୍ପ',

'brokenredirects-edit'   => 'ବଦଳାଇବେ',
'brokenredirects-delete' => 'ଲିଭାଇବା',

'withoutinterwiki-legend' => 'ଆଗରେ ଯୋଡ଼ାହେବା ଶବ୍ଦ',
'withoutinterwiki-submit' => 'ଦେଖାଇବେ',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|ବାଇଟ}}',
'ncategories'             => '$1 {{PLURAL:$1|ଶ୍ରେଣୀ|ଶ୍ରେଣୀସମୂହ}}',
'nmembers'                => '$1 {{PLURAL:$1|member|ସଭ୍ୟ}}',
'specialpage-empty'       => 'ଏହି ଅନୁରୋଧ ପାଇଁ କିଛି ଫଳାଫଳ ମିଳିଲା ନାହିଁ ।',
'lonelypages'             => 'ଅନାଥ ପୃଷ୍ଠା ସବୁ',
'uncategorizedpages'      => 'ଶ୍ରେଣୀହିନ ପୃଷ୍ଠାସମୂହ',
'uncategorizedcategories' => 'ଶ୍ରେଣୀହିନ ଶ୍ରେଣୀସମୂହ',
'uncategorizedimages'     => 'ଶ୍ରେଣୀହିନ ଫାଇଲସମୂହ',
'uncategorizedtemplates'  => 'ଶ୍ରେଣୀହିନ ଛାଞ୍ଚସବୁ',
'unusedcategories'        => 'ବ୍ୟବହାର ହେଉନଥିବା ଶ୍ରେଣୀସମୂହ',
'unusedimages'            => 'ବ୍ୟବହାର ହେଉନଥିବା ଫାଇଲସମୂହ',
'popularpages'            => 'ଜଣାଶୁଣା ପୃଷ୍ଠାସମୂହ',
'wantedcategories'        => 'ଦରକାରୀ ଶ୍ରେଣୀସମୂହ',
'wantedpages'             => 'ଦରକାରି ପୃଷ୍ଠା',
'wantedpages-badtitle'    => '$1 ଉତ୍ତରସବୁରେ ଥିବା ଭୁଲ ଟାଇଟଲ',
'wantedfiles'             => 'ଦରକାରି ଫାଇଲ',
'wantedtemplates'         => 'ଦରକାରୀ ଛାଞ୍ଚ',
'mostlinked'              => 'ଅଧିକ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠା',
'mostlinkedcategories'    => 'ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଶ୍ରେଣୀ',
'mostlinkedtemplates'     => 'ବେଶୀ ଯୋଡ଼ାଯାଇଥିବା ଛାଞ୍ଚ',
'mostcategories'          => 'ଅଧିକ ଶ୍ରେଣୀ ଥିବା ପୃଷ୍ଠା',
'mostimages'              => 'ଫାଇଲରେ ବେଶି ଯୋଡ଼ାଯାଇଥିବା ଥିବା',
'mostrevisions'           => 'ସବୁଠାରୁ ଅଧିକ ସଙ୍କଳନ ଥିବା ପୃଷ୍ଠାସମୂହ',
'prefixindex'             => 'ଆଗରୁ କିଛି ଯୋଡ଼ା ସହ ଥିବା ସବୁ ଫରଦସବୁ',
'shortpages'              => 'ଛୋଟ ପୃଷ୍ଠାସମୂହ',
'longpages'               => 'ଲମ୍ବା ପୃଷ୍ଠା',
'deadendpages'            => 'ଆଗକୁ ଯାଇପାରୁନଥିବା ପୃଷ୍ଠା',
'listusers'               => 'ବ୍ୟବହାରକାରୀଙ୍କ ତାଲିକା',
'usercreated'             => '$2 ଠାରେ $1ରେ ତିଆରି କରାଗଲା',
'newpages'                => 'ନୂଆ ପୃଷ୍ଠା',
'newpages-username'       => 'ବ୍ୟବାହରକାରୀଙ୍କର ନାଆଁ:',
'move'                    => 'ଘୁଞ୍ଚାଇବେ',
'movethispage'            => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'pager-newer-n'           => '{{PLURAL:$1|ନୂଆ 1|ନୂଆ $1}}',
'pager-older-n'           => '{{PLURAL:$1|ପୁରୁଣା 1|ପୁରୁଣା $1}}',

# Book sources
'booksources'               => 'ବହିର ମୁଳ ସ୍ରୋତ',
'booksources-search-legend' => 'ବହିର ସ୍ରୋତସବୁକୁ ଖୋଜିବେ',
'booksources-go'            => 'ଯିବା',

# Special:Log
'specialloguserlabel'  => 'ବ୍ୟବାହାରକାରୀ:',
'speciallogtitlelabel' => 'ଶିରୋନାମା:',
'log'                  => 'ଲଗସବୁ',
'all-logs-page'        => 'ସାଧାରଣ ଲଗସବୁ',
'alllogstext'          => ' {{SITENAME}} ସହିତ ଯୋଡ଼ା ମିଳୁଥିବା ଲଗସବୁ ।
ଆପଣ ଲଗର ପ୍ରକାର ଅନୁସାରେ ବି ସେସବୁକୁ ବାଛି ପାରିବେ । ଇଉଜରନାଆଁଟି ଛୋଟ ଓ ବଡ଼ ଅକ୍ଷର ଅନୁସାରେ ଅଲଗା ହୋଇଥାଏ, ପୃଷ୍ଠାର ନାଆଁ ସବୁ ବି ଛୋଟ ଓ ବଡ଼ ଇଂରାଜି ଅକ୍ଷର ଅନୁସାରେ ଅଲଗା ହୋଇଥାଏ ।',
'logempty'             => 'ଲଗରେ ଥିବା ଲେଖା ସହ ମେଳଖାଉ ନାହିଁ ।',

# Special:AllPages
'allpages'       => 'ସବୁ ପୃଷ୍ଠା',
'alphaindexline' => '$1 ରୁ $2',
'prevpage'       => 'ଆଗ ପୃଷ୍ଠା ($1)',
'allpagesfrom'   => 'ଏହି ନାଆଁରେ ଆରମ୍ଭ ହେଉଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'allpagesto'     => 'ଏହି ନାଆଁରେ ଶେଷ ହେଉଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକୁ ଦେଖାଇବେ:',
'allarticles'    => 'ସବୁ ପୃଷ୍ଠା',
'allpagesprev'   => 'ପୂର୍ବବର୍ତ୍ତୀ',
'allpagesnext'   => 'ପରବର୍ତ୍ତୀ',
'allpagessubmit' => 'ଯିବେ',

# Special:Categories
'categories'         => 'ଶ୍ରେଣୀସମୂହ',
'categoriespagetext' => 'ତଳଲିଖିତ {{PLURAL:$1|ଶ୍ରେଣୀ|ଶ୍ରେଣୀସମୂହ}}ରେ ପୃଷ୍ଠା ବା ମେଡ଼ିଆ ରହିଅଛି ।
[[Special:UnusedCategories|ବ୍ୟବହାର ହୋଇନଥିବା ଶ୍ରେଣୀସବୁ]] ଦେଖାଯାଇନାହିଁ ।
[[Special:WantedCategories|ଦରକାରୀ ଶ୍ରେଣୀସମୂହ]] ସବୁ ଦେଖନ୍ତୁ ।',

# Special:DeletedContributions
'deletedcontributions' => 'ଲିଭାଇ ଦିଆଯାଇଥିବା ସଭ୍ୟଙ୍କ ଅବଦାନ',

# Special:LinkSearch
'linksearch'      => 'ବାହାର ଲିଙ୍କ',
'linksearch-ns'   => 'ନେମସ୍ପେସ:',
'linksearch-ok'   => 'ଖୋଜିବା',
'linksearch-line' => '$1 ଟି $2ରୁ ଯୋଡ଼ାଯାଇଅଛି ।',

# Special:ListUsers
'listusers-submit' => 'ଦେଖାଇବେ',

# Special:Log/newusers
'newuserlogpage'          => 'ବ୍ୟବହାରକାରୀ ତିଆରି ଲଗ',
'newuserlog-create-entry' => 'ନୂଆ ବ୍ୟବହାରକାରୀଙ୍କ ଖାତା',

# Special:ListGroupRights
'listgrouprights-group'   => 'ଗୋଠ',
'listgrouprights-members' => '(ସଭ୍ୟମାନଙ୍କର ତାଲିକା)',

# E-mail user
'emailuser'           => 'ଏହି ଉଇଜରଙ୍କୁ ଇମେଲ କରିବେ',
'emailusername'       => 'ବ୍ୟବାହରକାରୀଙ୍କର ନାଆଁ:',
'emailusernamesubmit' => 'ଦାଖଲକରିବା',
'emailsubject'        => 'ବିଷୟ:',
'emailmessage'        => 'ଖବର:',

# Watchlist
'watchlist'         => 'ଦେଖାତାଲିକା',
'mywatchlist'       => 'ମୋର ଦେଖାତାଲିକା',
'watchlistfor2'     => '$1 $2 ପାଇଁ',
'watch'             => 'ଦେଖିବେ',
'watchthispage'     => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଦେଖିବେ',
'unwatch'           => 'ଦେଖନାହିଁ',
'watchlist-details' => 'ଆପଣଙ୍କ ଦେଖଣା ତାଲିକାରେ ଆଲୋଚନା ପୃଷ୍ଠାକୁ ଛାଡ଼ି {{PLURAL:$1|$1 ଟି ପୃଷ୍ଠା|$1 ଟି ପୃଷ୍ଠା}} ଅଛି ।',
'wlshowlast'        => 'ଶେଷ $1 ଘଣ୍ଟା $2 ଦିନ $3 ଦେଖାଇବେ',
'watchlist-options' => 'ଦେଖଣା ବିକଳ୍ପସବୁ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ଦେଖୁଛି...',
'unwatching' => 'ଦେଖୁନାହିଁ...',

'changed'            => 'ବଦଳାଗଲା',
'enotif_anon_editor' => 'ଅଜଣା ବ୍ୟବହାରକାରୀ $1',

# Delete
'deletepage'            => 'ପୃଷ୍ଠାଟି ଲିଭାଇଦେବେ',
'confirm'               => 'ଥୟ କରିବେ',
'excontent'             => 'ଭିତର ଭାଗ ଥିଲା: $1',
'excontentauthor'       => 'ଭିତର ଭାଗରେ ଥିଲା: "$1" (ଆଉ "[[Special:Contributions/$2|$2]]" କେବଳ ଜଣେ ମାତ୍ର ଦାତା ଥିଲେ)',
'exbeforeblank'         => 'ଖାଲି କରିବା ଆଗରୁ ଭିତରେ "$1" ଥିଲା',
'exblank'               => 'ପୃଷ୍ଠାଟି ଖାଲି ଅଛି',
'delete-confirm'        => 'ଲିଭେଇବେ $1',
'delete-legend'         => 'ଲିଭେଇବେ',
'historywarning'        => "'''ଚେତାବନୀ:''' ଆପଣ ଲିଭାଇବାକୁ ଯାଉଥିବା ଏହି ପୃଷ୍ଠାଟିର ପାଖାପାଖି $1 {{PLURAL:$1|ଟି ସଙ୍କଳନ|ଗୋଟି ସଙ୍କଳନ}} ରହିଅଛି:",
'confirmdeletetext'     => 'ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ଇତିହାସ ସହ ତାହାକୁ ଲିଭାଇବାକୁ ଯାଉଛନ୍ତି ।
ଏହା ଥୟ କରନ୍ତୁ ଯେ ଆପଣ ଏହାର ପରିଣତି ଜାଣିଛନ୍ତି ଓ ଏହା [[{{MediaWiki:Policy-url}}|ମିଡ଼ିଆଉଇକିର ନିୟମ]] ଅନୁସାରେ କରୁଛନ୍ତି ।',
'actioncomplete'        => 'କାମଟି ପୁରା ହେଲା',
'actionfailed'          => 'କାମଟି ଅସଫଳ ହୋଇଗଲା',
'deletedtext'           => '"$1"କୁ ଲିଭାଇ ଦିଆଗଲା ।
ନଗଦ ଲିଭାଯାଇଥିବା ଫାଇଲର ଇତିହାସ $2ରେ ଦେଖନ୍ତୁ ।',
'dellogpage'            => 'ଲିଭାଇବା ଇତିହାସ',
'dellogpagetext'        => 'ତଳେ ନଗଦ ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାମାନଙ୍କର ତାଲିକା ରହିଅଛି ।',
'deletionlog'           => 'ଲିଭାଇବା ଇତିହାସ',
'deletecomment'         => 'କାରଣ:',
'deleteotherreason'     => 'ବାକି/ଅଧିକ କାରଣ:',
'deletereasonotherlist' => 'ଅଲଗା କାରଣ',

# Rollback
'rollbacklink' => 'ପୁରାପୁରି ପଛକୁ ଫେରିଯିବେ',

# Protect
'protectlogpage'              => 'କିଳିବା ଲଗ',
'protectedarticle'            => '"[[$1]]"ଟି କିଳାଗଲା',
'protectcomment'              => 'କାରଣ:',
'protectexpiry'               => 'ଅଚଳ ହେବ:',
'protect_expiry_invalid'      => 'ଅଚଳ ହେବାର ବେଳଟି ଭୁଲ.',
'protect_expiry_old'          => 'ଅଚଳ ହେବାର ବେଳ ଅତୀତରେ ଥିଲା.',
'protect-default'             => 'ସବୁ ଇଉଜରଙ୍କୁ ଅନୁମତି ଦିଅନ୍ତୁ',
'protect-fallback'            => '"$1" ବାଲା ଅନୁମତି ଦରକାର',
'protect-level-autoconfirmed' => 'ନୁଆ ଓ ନାଆଁ ଲେଖାଇ ନ ଥିବା ଇଉଜରମାନକୁ ଅଟକାଁତୁ',
'protect-level-sysop'         => 'କେବଳ ପରିଛାମାନଁକ ପାଇଁ',
'protect-summary-cascade'     => 'କାସକେଡ଼ ହୋଇଥିବା',
'protect-expiring'            => '$1 (ଇଉଟିସି)ରେ ଅଚଳ ହୋଇଯିବ',
'protect-expiring-local'      => '$1ରେ ଅଚଳ ହୋଇଯିବ',
'protect-expiry-indefinite'   => 'ଆସିମୀତ',
'protect-cascade'             => 'ଏହି ଫରଦସହ ଜୋଡ଼ା ଫରଦସବୁକୁ କିଳିଦିଅ (କାସକେଡ଼କରା ସୁରକ୍ଷା)',
'protect-cantedit'            => 'ଆପଣ ଏହି ସୁରକ୍ଷା ସ୍ତରକୁ ବଦଳାଇ ପାରିବେ ନାହିଁ, କାହିଁକି ନା ଏହାକୁ ବଦଳାଇବା ପାଇଁ ଆପଣଁକୁ ଅନୁମତି ନାହିଁ .',
'protect-othertime'           => 'ବାକି ସମୟ:',
'protect-othertime-op'        => 'ବାକି ସମୟ',
'protect-existing-expiry'     => 'ମିଆଦ ପୁରିବା କାଳ: $3, $2',
'protect-otherreason'         => 'ବାକି/ଅଧିକ କାରଣ:',
'protect-otherreason-op'      => 'ଅଲଗା କାରଣ',
'protect-edit-reasonlist'     => 'କିଳିବା କାରଣମାନଙ୍କର ସମ୍ପାଦନା କରିବେ',
'protect-expiry-options'      => '୧ ଘଣ୍ଟା:1 hour,ଦିନେ:1 day,ସପ୍ତାହେ:1 week,୨ ସପ୍ତାହ:2 weeks,ମସେ:1 month,୩ ମାସ:3 months,୬ ମାସ:6 months,ବର୍ଷେ:1 year,ଅସିମୀତ କାଳ:infinite',
'restriction-type'            => 'ଅନୁମତି',
'restriction-level'           => 'ପ୍ରତିରକ୍ଷା ସ୍ତର',
'minimum-size'                => 'ସବୁଠୁ ସାନ',
'maximum-size'                => 'ସବୁଠୁ ବଡ଼ ଆକାର:',
'pagesize'                    => '(ବାଇଟ)',

# Restrictions (nouns)
'restriction-edit'   => 'ବଦଳାଇବେ',
'restriction-move'   => 'ଘୁଞ୍ଚାଇବେ',
'restriction-create' => 'ତିଆରି',
'restriction-upload' => 'ଅପଲୋଡ଼ କରନ୍ତୁ',

# Restriction levels
'restriction-level-sysop'         => 'ପୁରାପୁରି କିଳାଯାଇଥିବା',
'restriction-level-autoconfirmed' => 'ଅଧା କିଳାଯାଇଥିବା',
'restriction-level-all'           => 'ଯେକୌଣସି ସ୍ତର',

# Undelete
'undelete'                  => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସମୂହ',
'undeletepage'              => 'ଲିଭାଯାଇଥିବା ପୃଷ୍ଠାସବୁ ଦେଖିବେ ଓ ପୁନସ୍ଥାପନ କରିବେ',
'undeletebtn'               => 'ପୁନଃସ୍ଥାପନ',
'undeletelink'              => 'ଦେଖିବା/ପୁନଃସ୍ଥାପନ',
'undeleteviewlink'          => 'ଦେଖଣା',
'undeletereset'             => 'ପୁନଃ ସ୍ଥାପନ',
'undeletecomment'           => 'କାରଣ:',
'undelete-search-submit'    => 'ଖୋଜିବା',
'undelete-show-file-submit' => 'ହଁ',

# Namespace form on various pages
'namespace'      => 'ନେମସ୍ପେସ',
'invert'         => 'ବଛାଯାଇଥିବା ଲେଖାକୁ ଓଲଟେଇପକାଅ',
'blanknamespace' => '(ମୂଳ)',

# Contributions
'contributions'       => 'ବ୍ୟବହାରକାରିଙ୍କ ଦାନ',
'contributions-title' => '$1 ପାଇଁ ବ୍ୟବହାରକାରୀଙ୍କ ଦାନ',
'mycontris'           => 'ମୋ ଅବଦାନ',
'contribsub2'         => '$1 ($2) ପାଇଁ',
'uctop'               => '(ଉପର)',
'month'               => 'ମାସରୁ (ଓ ତା ଆଗରୁ)',
'year'                => 'ବର୍ଷରୁ (ଆଉ ତା ଆଗରୁ)',

'sp-contributions-newbies'  => 'କେବଳ ନୂଆ ସଭ୍ୟମାନଙ୍କର ଅବଦାନ ଦେଖାଇବେ',
'sp-contributions-blocklog' => 'ଲଗଟିକୁ ଅଟକାଇଦେବେ',
'sp-contributions-uploads'  => 'ଅପଲୋଡ଼ସବୁ',
'sp-contributions-logs'     => 'ଲଗସବୁ',
'sp-contributions-talk'     => 'କଥାଭାଷା',
'sp-contributions-search'   => 'ଅବଦାନ ପାଇଁ ଖୋଜନ୍ତୁ',
'sp-contributions-username' => 'ବ୍ୟବାହରକାରୀଙ୍କର ଆଇ.ପି. ଠିକଣା',
'sp-contributions-toponly'  => 'ନଗଦ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'sp-contributions-submit'   => 'ଖୋଜିବା',

# What links here
'whatlinkshere'            => 'ଏଠି କଣ କଣ ଲିଙ୍କ ଅଛି',
'whatlinkshere-title'      => '"$1" କୁ ପୃଷ୍ଠା ଲିଙ୍କ',
'whatlinkshere-page'       => 'ପୃଷ୍ଠା:',
'linkshere'                => "ଏହି ପୃଷ୍ଠା ସବୁ  '''[[:$1]]''' ସହ ଯୋଡା ଯାଇଅଛି:",
'nolinkshere'              => "'''[[:$1]]''' ସହିତ କୌଣସିଟି ପୃଷ୍ଠା ଯୋଡ଼ାଯାଇନାହିଁ ।",
'nolinkshere-ns'           => "ବଛା ଯାଇଥିବା ନେମସ୍ପେସରେ '''[[:$1]]''' ନାଆଁ ସହ କୌଣସି ବି ପୃଷ୍ଠା ଯୋଡ଼ାଯାଇନାହିଁ ।",
'isredirect'               => 'ଆଉଥରେ ଫେରିବା ପୃଷ୍ଠା',
'istemplate'               => 'ଆଧାର ସହ ଭିତରେ ରଖିବା',
'isimage'                  => 'ଫାଇଲର ଲିଙ୍କ',
'whatlinkshere-prev'       => '{{PLURAL:$1|ଆଗ|ଆଗ $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ପର|ପର $1}}',
'whatlinkshere-links'      => '← ଲିଙ୍କ',
'whatlinkshere-hideredirs' => '$1 କୁ ଲେଉଟାଣି',
'whatlinkshere-hidetrans'  => '$1 ଆଧାର ସହ ଭିତରେ ରଖିବା',
'whatlinkshere-hidelinks'  => '$1 ଟି ଲିଙ୍କ',
'whatlinkshere-hideimages' => '$1 ଛବିର ଲିଙ୍କସବୁ',
'whatlinkshere-filters'    => 'ଛଣା',

# Block/unblock
'blockip'                  => 'ସଭ୍ୟଙ୍କୁ ଅଟକାଇବେ',
'ipbreason'                => 'କାରଣ:',
'ipbother'                 => 'ବାକି ସମୟ:',
'ipboptions'               => '୨ ଘଣ୍ଟା:2 hours,୧ ଦିନ:1 day,୩ ଦିନ:3 days,୧ ସପ୍ତାହ:1 week,୨ ସପ୍ତାହ:2 weeks,୧ ମାସ:1 month,୩ ମାସ:3 months,୬ ମାସ:6 months,୧ ବର୍ଷ:1 year,ଅସିମୀତ କାଳ:infinite',
'ipbotheroption'           => 'ବାକି',
'ipblocklist'              => 'ଅଟକାଯାଇଥିବା ସଭ୍ୟସମୂହ',
'blocklist-target'         => 'ଲକ୍ଷ',
'blocklist-reason'         => 'କାରଣ',
'ipblocklist-submit'       => 'ଖୋଜିବା',
'blocklink'                => 'ଅଟକେଇ ଦେବେ',
'unblocklink'              => 'ଛାଡ଼ିବା',
'change-blocklink'         => 'ଓଗଳାକୁ ବଦଳାଇବେ',
'contribslink'             => 'ଅବଦାନ',
'blocklogpage'             => 'ଲଗଟିକୁ ଅଟକାଇଦେବେ',
'blocklogentry'            => '[[$1]]ଟିକୁ $2 $3 ଯାଏଁ ଅଟକାଗଲା',
'unblocklogentry'          => 'କିଳାଯାଇନଥିବା $1',
'block-log-flags-nocreate' => 'ନୂଆ ଖାତା ଖୋଲିବା ଅଚଳ କରାଯାଇଅଛି',
'proxyblocksuccess'        => 'ଶେଷ ହେଲା ।',

# Move page
'move-page'               => '$1କୁ ଘୁଞ୍ଚାଇବେ',
'move-page-legend'        => 'ପୃଷ୍ଠା ଘୁଞ୍ଚାଇବେ',
'movepagetext'            => "ଏହି ଫର୍ମଟି ବ୍ୟବହାର କରି ଆପଣ ତଳ ପୃଷ୍ଠାଟିକୁ ବଦଳାଇ ପାରିବେ, ଏହାର ସବୁ ଇତିହାସ ଏକ ନୂଆ ନାଆଁକୁ ବଦଳିଯିବ ।
ପୁରୁଣା ନାଆଁଟି ଏକ ପୁରୁଣା ନାଆଁ ଭାବରେ ଏହି ପୃଷ୍ଠା ଭାବରେ ବାଟ କଢ଼ାଇବ ।
ଆପଣ ମୂଳ ଲେଖାକୁ ସେହି ପୁରୁଣା ନାଆଁ ଦେଇ ଆପେଆପେ ପଢ଼ିପାରିବେ ।
ଯଦି ଆପଣ ଏହା ଚାହାନ୍ତି ନାହିଁ ତେବେ [[Special:DoubleRedirects|ଦୁଇଥର ଥିବା ପୃଷ୍ଠା]] ବା [[Special:BrokenRedirects|ଭଙ୍ଗା ଆଗ ପୃଷ୍ଠା]] ଦେଖି ପାରିବେ ।

ଲିଙ୍କସବୁ କେଉଁଠିକୁ ଯାଉଛି ତାହା ପାଇଁ ଆପଣ ଦାୟୀ ନୁହନ୍ତି ।

ମନେ ରଖନ୍ତୁ, ଆଗରୁ ଏହି ଏକା ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ଥିଲେ ଏହି ପୃଷ୍ଠାଟି '''ଘୁଞ୍ଚିବ ନାହିଁ''' ଯେତେ ଯାଏଁ ତାହା ଖାଲି ନାହିଁ ବା ଆଗ ପୃଷ୍ଠାଟିର କୌଣସି ବଦଳ ଇତିହାସ ନାହିଁ ସେତେ ବେଳ ଯାଏଁ ଏହା ଏମିତି ରହିବ । ଏହାର ମାନେ ହେଉଛି, ଆପଣ ଗୋଟିଏ ପୃଷ୍ଠାର ନାଆଁକୁ ତାର ପୁରୁଣା ନାଆଁ ଦେଇପାରିବେ, କିନ୍ତୁ ଆଗରୁ ଥିବା ପୃଷ୍ଠାଟି ଉପରେ ନୂଆ ପୃଷ୍ଠାଟିଏ ଚାପି ଦେଇପାରିବେ ନାହିଁ ।

'''ଜାଣି ରଖନ୍ତୁ!'''
ଏହା ଏକ ଜଣାଶୁଣା ପୃଷ୍ଠାରେ ଆମୂଳଚୂଳ ଓ ଅଜଣା ବଦଳ କରିପାରେ;
ନିଶ୍ଚିତ କରନ୍ତୁ ଆପଣ ଆଗକୁ ବଢ଼ିବା ଆଗରୁ ଏହାର ଫଳ ବାବଦରେ ଜାଣିଛନ୍ତି ।",
'movepagetalktext'        => 'ଯଦି:
*ଗୋଟିଏ ଖାଲି ଆଲୋଚନା ପୃଷ୍ଠା ସେହି ନାଆଁରେ ଥାଏ
*ଆପଣ ତଳ ବାକ୍ସକୁ ନ ବାଛନ୍ତି
ତେବେ ଏହି ପ୍ରୁଷ୍ଠା ସହ ଯୋଡାଯାଇଥିବା ଆଲୋଚନା ପ୍ରୁଷ୍ଠାକୁ ଆପେ ଆପେ ଘୁଞ୍ଚାଇଦିଆଯିବ ।
ସେହି ଯାଗାରେ, ଆପଣଙ୍କୁ ପ୍ରୁଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବାକୁ/ମିଶାଇବାକୁ ପଡ଼ିବ ।',
'movearticle'             => 'ପୃଷ୍ଠା ଘୁଞ୍ଚେଇବା:',
'cant-move-user-page'     => 'ଆପଣଙ୍କୁ ଏହି ସଭ୍ୟ ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବା ଲାଗି ଅନୁମତି ମିଳିନାହିଁ, କିନ୍ତୁ ନିଜର ଉପପୃଷ୍ଠା ସବୁ ଘୁଞ୍ଚାଇ ପାରିବେ ।',
'newtitle'                => 'ନୂଆ ନାଆଁକୁ:',
'move-watch'              => 'ମୂଳ ପୃଷ୍ଠା ଓ ବଦଳାଇବାକୁ ଚାହୁଁଥିବା ପୃଷ୍ଠା ଦେଖାଇବେ',
'movepagebtn'             => 'ପୃଷ୍ଠା ଘୁଞ୍ଚେଇବେ',
'pagemovedsub'            => 'ଘୁଞ୍ଚାଇବା ସଫଳ ହେଲା',
'movepage-moved'          => '\'\'\'"$1"ରୁ "$2"\'\'\'କୁ ଘୁଞ୍ଚାଇ ଦିଆଗଲା ।',
'movepage-moved-redirect' => 'ପୃଷ୍ଠାଟିର ନାଆଁକୁ ଘୁଞ୍ଚାଇଦିଆଗଲା ।',
'move-subpages'           => 'ଉପପୃଷ୍ଠା ଗୁଡ଼ିକୁ ଘୁଞ୍ଚାଇବେ ($1 ଯାଏଁ)',
'movepage-page-moved'     => '$1 ପୃଷ୍ଠାଟିକୁ $2କୁ ଘୁଞ୍ଚାଇ ଦିଆଗଲା ।',
'movelogpage'             => 'ଲଗଟିକୁ ଘୁଞ୍ଚାଇବେ',
'movenosubpage'           => 'ଏହି ପୃଷ୍ଠାର ଉପପୃଷ୍ଠା ନାହିଁ ।',
'movereason'              => 'କାରଣ:',
'revertmove'              => 'ପଛକୁ ଫେରାଇବେ',
'move-leave-redirect'     => 'ପଛକୁ ଫେରିବା ପୃଷ୍ଠାଟିଏ ଛାଡ଼ିଯାନ୍ତୁ',

# Export
'export'            => 'ପୃଷ୍ଠାସବୁ ରପ୍ତାନି କରିବେ',
'export-submit'     => 'ପଠେଇବେ',
'export-addcattext' => 'ଶ୍ରେଣୀରୁ ପୃଷ୍ଠାସବୁକୁ ଯୋଡ଼ନ୍ତୁ:',
'export-addcat'     => 'ଯୋଡ଼ିବେ',
'export-addnstext'  => 'ଏକ ନେମସ୍ପେସରୁ ପୃଷ୍ଠା ଯୋଡ଼ିବେ:',
'export-addns'      => 'ଯୋଡ଼ିବେ',
'export-download'   => 'ଫାଇଲ ଭାବରେ ସାଇତିବା',

# Namespace 8 related
'allmessagesname'           => 'ନାମ',
'allmessagesdefault'        => 'ଆପେଆପେ ଚିଠିରେ ରହିବା କଥା',
'allmessages-filter-legend' => 'ଛାଣିବା',
'allmessages-filter-all'    => 'ସବୁ',
'allmessages-language'      => 'ଭାଷା:',
'allmessages-filter-submit' => 'ଯିବା',

# Thumbnails
'thumbnail-more'  => 'ବଡ଼କର',
'thumbnail_error' => 'ନଖଦେଖଣା ତିଆରିବାରେ ଅସୁବିଧା: $1',

# Special:Import
'import'                  => 'ପୃଷ୍ଠା ଆମଦାନି କରିବେ',
'import-interwiki-submit' => 'ଆମଦାନୀ',
'import-upload-filename'  => 'ଫାଇଲ ନାମ:',
'import-comment'          => 'ମତାମତ:',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'ଆପଣଙ୍କ ବ୍ୟବାହାରକାରୀ ପୃଷ୍ଠା',
'tooltip-pt-mytalk'               => 'ଆପଣଙ୍କ ଆଲୋଚନା ପୃଷ୍ଠା',
'tooltip-pt-preferences'          => 'ମୋ ପସନ୍ଦ',
'tooltip-pt-watchlist'            => 'ବଦଳ ପାଇଁ ଆପଣ ଦେଖାଶୁଣା କରୁଥିବା ପୃଷ୍ଠାଗୁଡ଼ିକର ତାଲିକା',
'tooltip-pt-mycontris'            => 'ଆପଣଙ୍କ ଅବଦାନ',
'tooltip-pt-login'                => 'ଆପଣଙ୍କୁ ଲଗିନ କରିବାକୁ କୁହାଯାଉଅଛି ସିନା, ବାଧ୍ୟ କରାଯାଉନାହିଁ',
'tooltip-pt-logout'               => 'ଲଗଆଉଟ',
'tooltip-ca-talk'                 => 'ଏହି ପୃଷ୍ଠାଟି ଉପରେ ଆଲୋଚନା',
'tooltip-ca-edit'                 => 'ଆପଣ ଏହି ପୃଷ୍ଠାଟିରେ ଅଦଳ ବଦଳ କରିପାରିବେ, ତେବେ ସାଇତିବା ଆଗରୁ ଦେଖଣା ଦେଖନ୍ତୁ ।',
'tooltip-ca-addsection'           => 'ନୂଆ ବିଭାଗଟିଏ ଆରମ୍ଭ କରିବେ',
'tooltip-ca-viewsource'           => 'ଏହି ପୃଷ୍ଠାଟି କିଳାଯାଇଛି ।
ଆପଣ ଏହାର ମୂଳ ଦେଖିପାରିବେ',
'tooltip-ca-history'              => 'ଏହି ପୃଷ୍ଠାର ପୁରୁଣା ସଂସ୍କରଣ',
'tooltip-ca-protect'              => 'ଏହି ପୃଷ୍ଠାଟିକୁ କିଳିବେ',
'tooltip-ca-delete'               => 'ଏହି ପୃଷ୍ଠାଟି ଲିଭାଇବେ',
'tooltip-ca-move'                 => 'ଏହି ପୃଷ୍ଠାଟିକୁ ଘୁଞ୍ଚାଇବେ',
'tooltip-ca-watch'                => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ପୃଷ୍ଠାଟି ମିଶାଇବେ',
'tooltip-ca-unwatch'              => 'ନିଜ ଦେଖଣାତାଲିକାରୁ ଏହି ପୃଷ୍ଠାଟି ବାହାର କରିଦେବେ',
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
'tooltip-t-whatlinkshere'         => 'ଏଠାରେ ଯୋଡ଼ାଯାଇଥିବା ପୃଷ୍ଠାସବୁର ତାଲିକା',
'tooltip-t-recentchangeslinked'   => 'ଏହି ପୃଷ୍ଠା ସାଗେ ଯୋଡ଼ା ଫରଦଗୁଡ଼ିକରେ ଏଇଲାଗେ କରାଯାଇଥିବା ଅଦଳବଦଳ',
'tooltip-feed-rss'                => 'ଏହି ପୃଷ୍ଠାଟି ପାଇଁ RSS ଫିଡ଼',
'tooltip-feed-atom'               => 'ଏହି ପୃଷ୍ଠାଟି ପାଇଁ ଆଟମ ଫିଡ଼',
'tooltip-t-contributions'         => 'ଏହି ଇଉଜରଙ୍କର ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ଦାନ ଦେଖାଇବା',
'tooltip-t-emailuser'             => 'ଏହି ସଭ୍ୟଙ୍କୁ ଇ-ମେଲଟିଏ ପଠାଇବେ',
'tooltip-t-upload'                => 'ଫାଇଲ ଅପଲୋଡ଼ କରିବେ',
'tooltip-t-specialpages'          => 'ନିଆରା ପୃଷ୍ଠା ତାଲିକା',
'tooltip-t-print'                 => 'ଏହି ପୃଷ୍ଠାର ଛପାହୋଇପାରିବା ସଙ୍କଳନ',
'tooltip-t-permalink'             => 'ବଦଳାଯାଇଥିବା ଏହି ଫରଦଟିର ସ୍ଥାୟୀ ଲିଙ୍କ',
'tooltip-ca-nstab-main'           => 'ସୂଚୀ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'tooltip-ca-nstab-user'           => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-special'        => 'ଏଇଟି ଗୋଟିଏ ନିଆରା ପୃଷ୍ଠା, ଆପଣ ଏହାକୁ ବଦଳାଇପାରିବେ ନାହିଁ',
'tooltip-ca-nstab-project'        => 'ପ୍ରକଳ୍ପ ପୃଷ୍ଠାଟି ଦେଖାଇବେ',
'tooltip-ca-nstab-image'          => 'ଫାଇଲ ପୃଷ୍ଠାଗୁଡ଼ିକ ଦେଖନ୍ତୁ',
'tooltip-ca-nstab-template'       => 'ଟେମ୍ପଲେଟଟି ଦେଖିବା',
'tooltip-ca-nstab-category'       => 'ଶ୍ରେଣୀ ପୃଷ୍ଠାଟିକୁ ଦେଖାଇବେ',
'tooltip-minoredit'               => 'ଏହାକୁ ଏକ ଛୋଟ ବଦଳ ଭାବେ ଗଣିବେ',
'tooltip-save'                    => 'ବଦଳଗୁଡ଼ିକ ସାଇତିବେ',
'tooltip-preview'                 => 'ଆପଣନ୍କ ବଦଳ ଦେଖିନିଅନ୍ତୁ, ସାଇତିବା ଆଗରୁ ଏହା ବ୍ୟ୍ଅବହାର କରନ୍ତୁ!',
'tooltip-diff'                    => 'ଏହି ଲେଖାରେ ଆପଣ କରିଥିବା ବଦଳଗୁଡିକୁ ଦେଖନ୍ତୁ ।',
'tooltip-compareselectedversions' => 'ଏହି ଫରଦର ଦୁଇଟି ବଛାଯାଇଥିବା ସଁକଳନକୁ ତଉଲିବା',
'tooltip-watch'                   => 'ଆପଣଙ୍କ ଦେଖାତାଲିକାରେ ଏଇ ପୃଷ୍ଠାଟି ମିଶାଇବେ',
'tooltip-rollback'                => '"ଫେରିବା" ଏହି ଫରଦରେ ଶେଷ ଦାତାଙ୍କ ଦେଇ କରାଯାଇଥିବା ସବୁଯାକ ବଦଳକୁ  ଏକାଥରକରେ ପଛକୁ ଫେରାଇଦେବ',
'tooltip-undo'                    => '"କରନାହିଁ" ଆଗରୁ କରାଯାଇଥିବା ବଦଳଟିକୁ ପଛକୁ ଲେଉଟାଇଦିଏ ଆଉ ବଦଳ ଫରମଟିକୁ ଦେଖଣା ଭାବରେ ଖୋଲେ । ଏହା ଆପଣଙ୍କୁ ସାରକଥାରେ ଗୋଟିଏ କାରଣ ଲେଖିବାକୁ ଅନୁମତି ଦିଏ ।',
'tooltip-summary'                 => 'ଛୋଟ ସାରକଥାଟିଏ ଦିଅନ୍ତୁ',

# Attribution
'others' => 'ବାକିସବୁ',

# Info page
'pageinfo-header-edits' => 'ବଦଳସବୁ',
'pageinfo-header-views' => 'ଦେଖଣା',
'pageinfo-subjectpage'  => 'ପୃଷ୍ଠା',
'pageinfo-talkpage'     => 'ଆଲୋଚନା ପୃଷ୍ଠା',

# Patrol log
'patrol-log-line' => '$2 ଦେଇ ଜଗାହୋଇଥିବା $3ର $1କୁ ସୂଚିତ କରାଗଲା',
'patrol-log-auto' => '(ଆପେ ଆପେ)',
'patrol-log-diff' => '$1 ସଙ୍କଳନ',

# Browsing diffs
'previousdiff' => '← ପୁରୁଣା ବଦଳ',
'nextdiff'     => 'ନୂଆ ବଦଳ →',

# Media information
'file-info-size'       => '$1 × $2 ପିକସେଲ, ଫାଇଲ ଆକାର: $3, ଏମ.ଆଇ.ଏମ.ଇର ପ୍ରକାର: $4',
'file-nohires'         => 'ବଡ଼ ରେଜୋଲୁସନ ନାହିଁ ।',
'svg-long-desc'        => 'SVG ଫାଇଲ, ସାଧାରଣ ମାପ $1 × $2 ପିକ୍ସେଲ, ଫାଇଲ ଆକାର: $3',
'show-big-image'       => 'ପୁରା ବଡ଼ ଆକାରରେ',
'show-big-image-size'  => '$1 × $2 ପିକ୍ସେଲ',
'file-info-gif-looped' => 'ଲୁପ',
'file-info-png-looped' => 'ଲୁପ ଥିବା',

# Special:NewFiles
'newimages-legend' => 'ଛାଣିବା',
'ilsubmit'         => 'ଖୋଜିବା',

# Bad image list
'bad_image_list' => 'ଗଢ଼ଣଟି ଏମିତି ହେବ:

କେବଳ (ଯେଉଁ ଧାଡ଼ିଗୁଡ଼ିକ * ରୁ ଆରମ୍ଭ ହୋଇଥାଏ) ସେହି ସବୁକୁ ହିସାବକୁ ନିଆଯିବ ।
ଗୋଟିଏ ଧାଡ଼ିର ପ୍ରଥମ ଲିଙ୍କଟି ଗୋଟିଏ ଖରାପ ଫାଇଲର ଲିଙ୍କ ହୋଇଥିବା ଦରକାର ।
ପ୍ରଥମ ଲିଙ୍କ ପରର ସବୁ ଲିଙ୍କକୁ ନିଆରା ବୋଲି ଧରାଯିବ । ମାନେ, ସେଇସବୁ ପୃଷ୍ଠାଦରେ ଯେଉଁଠି ଫାଇଲଟି ଧାଡ଼ି ଭିତରେ ରହିଥିବ ।',

# Metadata
'metadata'          => 'ମେଟାଡାଟା',
'metadata-help'     => 'ଏହି ଫରଦଟିରେ ଗୁଡ଼ାଏ ଅଧିକ କଥା ଅଛି, ବୋଧହୁଏ ଡିଜିଟାଲ କାମେରା କିମ୍ବା ସ୍କାନରରେ ନିଆଯାଇଛି । ଯଦି ଫାଇଲଟି ତାର ମୂଳ ଭାଗଠୁ ବଦଳାଜାଇଥାଏ ତେବେ କିଛି ଅଁଶ ଠିକ ଭାବେ ଦେଖାଯାଇ ନପାରେ ।',
'metadata-expand'   => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',
'metadata-collapse' => 'ଆହୁରି ଖୋଲିକରି ଦେଖାଇବେ',
'metadata-fields'   => 'ମେଟାଡାଟା ସାରଣୀଟି ବନ୍ଦ ହୋଇରହିଥିଲେ ଏହି ମେସେଜରେ ଥିବା ଛବି ମେଟାଡାଟାସବୁ ଛବିର ପୃଷ୍ଠାରେ ରହିଥିବ ।
ବାକିସବୁ ଆପେଆପେ ଲୁଚି ରହିଥିବ ।
* ତିଆରି
* ମଡେଲ
* ଫଟୋ_ଉଠା_ତାରିଖ
* ସଟର‌_ଖୋଲାଥିବା‌_କାଳ
* fnumber
* isospeedratings
* focallength
* ଶିଳ୍ପୀ
* ସ୍ଵତ୍ଵ
* ଛବି‌_ବିବରଣୀ
* ଜି.ପି.ଏସ._ଅଖ୍ୟାଂଶ
* ଜି.ପି.ଏସ.‌_ଦ୍ରାଘିମା
* ଜି.ପି.ଏସ.‌_ଉଚ୍ଚତା',

# EXIF tags
'exif-imagewidth'           => 'ଓସାର',
'exif-imagelength'          => 'ଉଚ୍ଚତା',
'exif-orientation'          => 'ଅନୁସ୍ଥାପନ (Orientation)',
'exif-model'                => 'କ୍ୟାମେରା ମଡ଼େଲ',
'exif-software'             => 'ବ୍ୟବହାର କରାଯାଇଥିବା ସଫ୍ଟବେର',
'exif-artist'               => 'ଲେଖକ',
'exif-datetimeoriginal'     => 'ତଥ୍ୟ ତିଆରିହେବାର ତାରିଖ ଓ ସମୟ',
'exif-exposuretime'         => 'ଖୋଲାରହିବା କାଳ',
'exif-lightsource'          => 'ଆଲୁଅର ମୂଳ',
'exif-flash'                => 'ଫ୍ଲାସ',
'exif-saturation'           => 'ପରିପୃକ୍ତ',
'exif-subjectdistancerange' => 'ବସ୍ତୁର ଦୂରତା ସୀମା',
'exif-keywords'             => 'ସୂଚକ ଶବ୍ଦ',
'exif-source'               => 'ଉତ୍ସ',
'exif-writer'               => 'ଲେଖକ',
'exif-languagecode'         => 'ଭାଷା',
'exif-iimcategory'          => 'ଶ୍ରେଣୀ',
'exif-identifier'           => 'ସୂଚକ',
'exif-label'                => 'ଛାପ',

'exif-orientation-1' => 'ସାଧାରଣ',

'exif-exposureprogram-1' => 'ସହାୟକ ବହି',
'exif-exposureprogram-2' => 'ସାଧାରଣ ପ୍ରୋଗ୍ରାମ',

'exif-subjectdistance-value' => '$1 ମିଟର',

'exif-meteringmode-0'   => 'ଅଜଣା',
'exif-meteringmode-1'   => 'ହାରାହାରି',
'exif-meteringmode-5'   => 'ନମୁନା',
'exif-meteringmode-6'   => 'କିଛି',
'exif-meteringmode-255' => 'ବାକି',

'exif-lightsource-0' => 'ଅଜଣା',
'exif-lightsource-4' => 'ଫ୍ଲାସ',

'exif-scenecapturetype-0' => 'ମାନକ',
'exif-scenecapturetype-2' => 'ସିଧା',

'exif-gaincontrol-0' => 'କିଛି ନାହିଁ',

'exif-contrast-0' => 'ସାଧାରଣ',

'exif-saturation-0' => 'ସାଧାରଣ',

'exif-sharpness-0' => 'ସାଧାରଣ',

'exif-subjectdistancerange-0' => 'ଅଜଣା',
'exif-subjectdistancerange-3' => 'ଦୂରର ଦେଖଣା',

'exif-dc-rights' => 'ଡାହାଣ',

'exif-urgency-normal' => 'ସାଧାରଣ ($1)',

# External editor support
'edit-externally'      => 'ଏକ ବାହାର ଆପ୍ଲିକେସନ ବ୍ୟବହାର କରି ଏହି ଫାଇଲଟିକୁ ବଦଳାଇବା',
'edit-externally-help' => '(ଆହୁରି ବି [http://www.mediawiki.org/wiki/Manual:External_editors ସଜାଡିବା ନିର୍ଦେଶ] ଦେଖନ୍ତୁ)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'ସବୁ',
'namespacesall' => 'ସବୁ',
'monthsall'     => 'ସବୁ',
'limitall'      => 'ସବୁ',

# E-mail address confirmation
'confirmemail_needlogin' => 'ଆପଣଙ୍କୁ ନିଜ ଇମେଲଟିକୁ ଥୟ କରିବା ପାଇଁ $1 କରିବାକୁ ପଡ଼ିବ ।',
'confirmemail_body'      => 'କେହିଜଣେ, ବୋଧହୁଏ ଆପଣ ହିଁ $1 ଆଇ.ପି. ଠିକଣାରୁ,
ଏହି ଇ-ମେଲ ଆଇ.ଡି.ରେ "$2" ନାଆଁରେ {{SITENAME}} ଠାରେ ଖାତାଟିଏ ଖୋଲିଛନ୍ତି ।

ଏହି ଖାତାଟି ସତରେ ଆପଣଙ୍କର ବୋଲି ଥୟ କରିବା ପାଇଁ ଓ {{SITENAME}}ରେ ଇ-ମେଲ ସୁବିଧାସବୁ ସଚଳ କରିବାପାଇଁ, ଏହି ଲିଙ୍କ୍ଟିକୁ ଆପଣଙ୍କ ବ୍ରାଉଜରରେ ଖୋଲନ୍ତୁ:

$3

ଯଦି ଆପଣ ଖାତାଟିଏ ଆଗରୁ ଖୋଲି *ନାହାନ୍ତି* ତେବେ ଏହି ଲିଙ୍କକୁ ଯାଇ ଇ-ମେଲ ଆ.ଡି. ଥୟ କରିବାକୁ ନାକଚ କରିଦିଅନ୍ତୁ:

$5

ଏହି କନଫର୍ମେସନ କୋଡ଼ $4 ବେଳେ ଅଚଳ ହୋଇଯିବ ।',

# Trackbacks
'trackbackremove' => '([$1 ଲିଭାଇବା])',

# action=purge
'confirm_purge_button' => 'ଠିକ ଅଛି',
'confirm-purge-top'    => 'ଏହି ପୃଷ୍ଠାଟିର ନଗଦ ସଙ୍କଳନଟିକୁ ଦେଖାଇବେ?',
'confirm-purge-bottom' => 'ପୁରୁଣା ସ୍ମୃତିସବୁକୁ ସଫା କରିଦେଲେ ତାହା ପୃଷ୍ଠାଟିର ନଗଦ ସଙ୍କଳନଟି ଦେଖାଇଥାଏ ।',

# action=watch/unwatch
'confirm-watch-button'   => 'ଠିକ ଅଛି',
'confirm-unwatch-button' => 'ଠିକ ଅଛି',

# Multipage image navigation
'imgmultipageprev' => 'ଆଗ ପୃଷ୍ଠା',
'imgmultipagenext' => 'ପର ପୃଷ୍ଠା →',
'imgmultigo'       => 'ଯିବା!',

# Table pager
'table_pager_next'         => 'ନୂଆ ପୃଷ୍ଠା',
'table_pager_prev'         => 'ଆଗ ପୃଷ୍ଠା',
'table_pager_limit_submit' => 'ଯିବା',

# Auto-summaries
'autoredircomment' => '[[$1]]କୁ ପୃଷ୍ଠାଟି ଘୁଞ୍ଚାଇଦିଆଗଲା',
'autosumm-new'     => '"$1" ନାଆଁରେ ପୃଷ୍ଠାଟିଏ ତିଆରିକଲେ',

# Live preview
'livepreview-loading' => 'ଖୋଲୁଅଛି...',
'livepreview-ready'   => 'ଖୋଲୁଅଛି...ଏବେ ସଜିଲ!',

# Watchlist editor
'watchlistedit-raw-titles' => 'ଶିରୋନାମା:',

# Watchlist editing tools
'watchlisttools-view' => 'ଦରକାରୀ ବଦଳଗୁଡ଼ିକ ଦେଖାଇବେ',
'watchlisttools-edit' => 'ଦେଖିବା ତାଲିକାଟିକୁ ଦେଖିବେ ଓ ବଦଳାଇବେ',
'watchlisttools-raw'  => 'ଫାଙ୍କା ଦେଖା ତାଲିକାଟିକୁ ଦେଖିବେ ଓ ବଦଳାଇବେ',

# Core parser functions
'duplicate-defaultsort' => '\'\'\'ସୂଚନା:\'\'\' ଆପେଆପେ କାମକରୁଥିବା "$2" ଆଗରୁ ଆପେ ଆପେ ସଜାଡୁଥିବା "$1"କୁ ବନ୍ଦ କରିଦେଇଛି ।',

# Special:Version
'version'                  => 'ସଂସ୍କରଣ',
'version-variables'        => 'ଚଳ',
'version-other'            => 'ବାକି',
'version-version'          => '(ଭାଗ $1)',
'version-license'          => 'ଲାଇସେନ୍ସ',
'version-poweredby-others' => 'ବାକିସବୁ',
'version-software-product' => 'ଉତ୍ପାଦ',
'version-software-version' => 'ସଂସ୍କରଣ',

# Special:FilePath
'filepath-page'   => 'ଫାଇଲ:',
'filepath-submit' => 'ଯିବା',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'ଫାଇଲ ନାମ:',
'fileduplicatesearch-submit'   => 'ଖୋଜିବା',

# Special:SpecialPages
'specialpages'             => 'ନିଆରା ପୃଷ୍ଠା',
'specialpages-group-login' => 'ଲଗିନ / ଖାତା ଖୋଲି',

# External image whitelist
'external_image_whitelist' => ' #ଏହି ଧାଡ଼ିଟି ଯେମିତି ଅଛି ସେମିତି ରଖିଦିଅନ୍ତୁ<pre>
#ସାଧାରଣ ଖଣ୍ଡିତ ଲେଖାସମୂହ (କେବଳ // ଧାଡ଼ି ତଳେ ଥିବା ଭାଗ) ତଳେ ରଖିବେ
#ଏହା ବାହାରେ ଥିବା ଛବି (hotlinked) ସହ ଏହାକୁ ମେଳାଯିବ
#ଯେଉଁସବୁ ମେଳଖାଇବ ତାହା ଛବି ଭାବରେ ଦେଖାଯିବ, ନହେଲେ ଛବିପାଇଁ କେବଳ ଲିଙ୍କଟିଏ ଦେଖାଯିବ
#ଯେଉଁ ଧାଡ଼ିର ଆଗରେ  # ଚିହ୍ନ ଥିବ ତାହାକୁ ମତାମତ ବୋଲି ଗଣାଯିବ
#ଏସବୁ ଇଂରାଜୀ ବଡ଼ ଓ ସାନ ଅକ୍ଷର ପାଇଁ ଅଲଗା

#ସବୁଯାକ ସାଧାରଣ ବିବରଣୀ ଖଣ୍ଡ (regex fragments)ଏହି ରେଖା ଉପରେ ରଖିବେ । ସେସବୁ ଯେମିତି ଅଛି ସେମିତି ହିଁ ରଖିବେ</pre>',

# Special:Tags
'tag-filter'        => '[[Special:Tags|ଟାଗ]] ଛଣା:',
'tag-filter-submit' => 'ଛାଣିବା',
'tags-title'        => 'ସୂଚକ',
'tags-edit'         => 'ବଦଳାଇବେ',

# Special:ComparePages
'compare-page1'  => 'ପୃଷ୍ଠା ୧',
'compare-page2'  => 'ପୃଷ୍ଠା ୨',
'compare-submit' => 'ତୁଳନା',

# Special:GlobalFileUsage
'globalfileusage-ok' => 'ଖୋଜିବା',

# Special:GlobalTemplateUsage
'globaltemplateusage-ok' => 'ଖୋଜିବା',

# HTML forms
'htmlform-submit'              => 'ଦାଖଲକରିବା',
'htmlform-selectorother-other' => 'ବାକି',

# New logging system
'revdelete-restricted'   => 'ପରିଛାମାନଙ୍କ ନିମନ୍ତେ ଥିବା ବାରଣ',
'revdelete-unrestricted' => 'ପରିଛାମାନଙ୍କ ନିମନ୍ତେ ଥିବା ବାରଣ ବାହାର କରିଦିଆଗଲା',

);
