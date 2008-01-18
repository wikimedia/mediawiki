<?php
/** Vietnamese (Tiếng Việt)
 *
 * @addtogroup Language
 *
 * @author Trần Thế Trung
 * @author Nguyễn Thanh Quang
 * @author SPQRobin
 * @author Mxn
 * @author Apple
 * @author Arisa
 * @author DHN
 * @author Neoneurone
 * @author Thaisk
 * @author Tmct
 * @author Tttrung
 * @author Vietbio
 * @author Vinhtantran
 * @author Vương Ngân Hà
 * @author לערי ריינהארט
 * @author Nike
 * @author Siebrand
 */

$namespaceNames = array(
	NS_MEDIA			=> 'Phương_tiện',
	NS_SPECIAL			=> 'Đặc_biệt',
	NS_MAIN				=> '',
	NS_TALK				=> 'Thảo_luận',
	NS_USER				=> 'Thành_viên',
	NS_USER_TALK		=> 'Thảo_luận_Thành_viên',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK		=> 'Thảo_luận_$1',
	NS_IMAGE			=> 'Hình',
	NS_IMAGE_TALK		=> 'Thảo_luận_Hình',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Thảo_luận_MediaWiki',
	NS_TEMPLATE			=> 'Tiêu_bản',
	NS_TEMPLATE_TALK	=> 'Thảo_luận_Tiêu_bản',
	NS_HELP				=> 'Trợ_giúp',
	NS_HELP_TALK		=> 'Thảo_luận_Trợ_giúp',
	NS_CATEGORY			=> 'Thể_loại',
	NS_CATEGORY_TALK	=> 'Thảo_luận_Thể_loại'
);

$skinNames = array(
	'standard'		=> 'Cổ điển',
	'nostalgia'		=> 'Vọng cổ',
	'myskin'		=> 'Cá nhân'
);

$magicWords = array(
	'redirect'               => array( 0,    '#redirect' , '#đổi'             ),
	'notoc'                  => array( 0,    '__NOTOC__' , '__KHÔNGMỤCMỤC__'             ),
	'forcetoc'               => array( 0,    '__FORCETOC__', '__LUÔNMỤCLỤC__'        ),
	'toc'                    => array( 0,    '__TOC__' , '__MỤCLỤC__'               ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__', '__KHÔNGSỬAMỤC__'      ),
	'currentmonth'           => array( 1,    'CURRENTMONTH' , 'THÁNGNÀY'          ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME'  , 'TÊNTHÁNGNÀY'     ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN' , 'TÊNDÀITHÁNGNÀY'   ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV'  , 'TÊNNGẮNTHÁNGNÀY'  ),
	'currentday'             => array( 1,    'CURRENTDAY'       , 'NGÀYNÀY'     ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME'   , 'TÊNNGÀYNÀY'      ),
	'currentyear'            => array( 1,    'CURRENTYEAR'    , 'NĂMNÀY'        ),
	'currenttime'            => array( 1,    'CURRENTTIME'     , 'GIỜNÀY'       ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES'  , 'SỐBÀI'     ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES'   , 'SỐTẬPTIN'       ),
	'pagename'               => array( 1,    'PAGENAME'      , 'TÊNTRANG'        ),
	'pagenamee'              => array( 1,    'PAGENAMEE'   , 'TÊNTRANG2'           ),
	'namespace'              => array( 1,    'NAMESPACE'   , 'KHÔNGGIANTÊN'           ),
	'msg'                    => array( 0,    'MSG:'     , 'NHẮN:'              ),
	'subst'                  => array( 0,    'SUBST:'   ,  'THẾ:'            ),
	'msgnw'                  => array( 0,    'MSGNW:'    ,  'NHẮNMỚI:'             ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb' , 'nhỏ'    ),
	'img_right'              => array( 1,    'right' , 'phải'                 ),
	'img_left'               => array( 1,    'left'  , 'trái'                ),
	'img_none'               => array( 1,    'none'  , 'không'                 ),
	'img_center'             => array( 1,    'center', 'centre' , 'giữa'      ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame' , 'khung'),
	'sitename'               => array( 1,    'SITENAME'  , 'TÊNMẠNG'             ),
	'server'                 => array( 0,    'SERVER'    , 'MÁYCHỦ'             ),
	'servername'             => array( 0,    'SERVERNAME' , 'TÊNMÁYCHỦ'            ),
	'grammar'                => array( 0,    'GRAMMAR:'   , 'NGỮPHÁP'            ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__KHÔNGCHUYỂNTÊN__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__KHÔNGCHUYỂNNỘIDUNG__'),
	'currentweek'            => array( 1,    'CURRENTWEEK' , 'TUẦNNÀY'           ),
	'revisionid'             => array( 1,    'REVISIONID'  , 'SỐBẢN'           ),
 );

$datePreferences = array(
	'default',
	'vi normal',
	'vi spelloutmonth',
	'vi shortcolon',
	'vi shorth',
	'ISO 8601',
);

$defaultDateFormat = 'vi normal';

$dateFormats = array(
	'vi normal time' => 'H:i',
	'vi normal date' => '"ngày" j "tháng" n "năm" Y',
	'vi normal both' => 'H:i, "ngày" j "tháng" n "năm" Y',

	'vi spelloutmonth time' => 'H:i',
	'vi spelloutmonth date' => '"ngày" j xg "năm" Y',
	'vi spelloutmonth both' => 'H:i, "ngày" j xg "năm" Y',

	'vi shortcolon time' => 'H:i',
	'vi shortcolon date' => 'j/n/Y',
	'vi shortcolon both' => 'H:i, j/n/Y',

	'vi shorth time' => 'H"h"i',
	'vi shorth date' => 'j/n/Y',
	'vi shorth both' => 'H"h"i, j/n/Y',
);

$datePreferenceMigrationMap = array(
	'default',
	'vi normal',
	'vi normal',
	'vi normal',
);


$linkTrail = "/^([a-zàâçéèêîôûäëïöüùÇÉÂÊÎÔÛÄËÏÖÜÀÈÙ]+)(.*)$/sDu";
$separatorTransformTable = array(',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Gạch chân liên kết:',
'tog-highlightbroken'         => 'Liên kết đến trang chưa có sẽ <a href="" class="new">giống thế này</a> (nếu không chọn: giống thế này<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Căn đều hai bên đoạn văn',
'tog-hideminor'               => 'Giấu sửa đổi nhỏ trong thay đổi gần đây',
'tog-extendwatchlist'         => 'Danh sách theo dõi nhiều chức năng (JavaScript)',
'tog-usenewrc'                => 'Thay đổi gần đây nhiều chức năng (JavaScript)',
'tog-numberheadings'          => 'Tự động đánh số các đề mục',
'tog-showtoolbar'             => 'Hiển thị thanh định dạng (JavaScript)',
'tog-editondblclick'          => 'Nhấn đúp để sửa đổi trang (JavaScript)',
'tog-editsection'             => 'Cho phép sửa đổi đề mục qua liên kết [sửa]',
'tog-editsectiononrightclick' => 'Cho phép sửa đổi mục bằng cách bấm chuột phải trên đề mục (JavaScript)',
'tog-showtoc'                 => 'Hiển thị mục lục (cho bài có trên 3 đề mục)',
'tog-rememberpassword'        => 'Nhớ thông tin đăng nhập của tôi trên máy tính này',
'tog-editwidth'               => 'Ô sửa đổi có bề rộng tối đa',
'tog-watchcreations'          => 'Tự động theo dõi bài tôi viết mới',
'tog-watchdefault'            => 'Tự động theo dõi bài tôi sửa',
'tog-watchmoves'              => 'Tự động theo dõi bài tôi di chuyển',
'tog-watchdeletion'           => 'Tự động theo dõi bài tôi xóa',
'tog-minordefault'            => 'Đánh dấu mặc định sửa đổi của tôi là thay đổi nhỏ',
'tog-previewontop'            => 'Hiển thị phần xem thử nằm trên hộp sửa đổi',
'tog-previewonfirst'          => 'Hiện xem thử tại lần sửa đầu tiên',
'tog-nocache'                 => 'Không lưu trang trong bộ nhớ đệm',
'tog-enotifwatchlistpages'    => 'Gửi thư cho tôi khi có thay đổi tại trang tôi theo dõi',
'tog-enotifusertalkpages'     => 'Gửi thư cho tôi khi có thay đổi tại trang thảo luận của tôi',
'tog-enotifminoredits'        => 'Gửi thư cho tôi cả những thay đổi nhỏ trong trang',
'tog-enotifrevealaddr'        => 'Hiện địa chỉ thư điện tử của tôi trong thư thông báo',
'tog-shownumberswatching'     => 'Hiển thị số người đang xem',
'tog-fancysig'                => 'Chữ ký không dùng liên kết tự động',
'tog-externaleditor'          => 'Mặc định dùng trình soạn thảo bên ngoài',
'tog-externaldiff'            => 'Mặc định dùng trình so sánh bên ngoài',
'tog-showjumplinks'           => 'Bật liên kết "bước tới" trên đầu trang cho bộ trình duyệt thuần văn bản hay âm thanh',
'tog-uselivepreview'          => 'Sử dụng xem thử trực tiếp (JavaScript) (Thử nghiệm)',
'tog-forceeditsummary'        => 'Nhắc tôi khi tôi quên tóm lược sửa đổi',
'tog-watchlisthideown'        => 'Giấu các sửa đổi của tôi khỏi danh sách theo dõi',
'tog-watchlisthidebots'       => 'Giấu các sửa đổi của robot khỏi danh sách theo dõi',
'tog-watchlisthideminor'      => 'Giấu các sửa đổi nhỏ khỏi danh sách theo dõi',
'tog-ccmeonemails'            => 'Gửi bản sao cho tôi khi gửi thư điện tử cho người khác',
'tog-diffonly'                => 'Không hiển thị nội dung trang dưới phần so sánh phiên bản',

'underline-always'  => 'Luôn luôn',
'underline-never'   => 'Không bao giờ',
'underline-default' => 'Mặc định của trình duyệt',

'skinpreview' => '(Xem thử)',

# Dates
'sunday'        => 'Chủ nhật',
'monday'        => 'thứ Hai',
'tuesday'       => 'thứ Ba',
'wednesday'     => 'thứ Tư',
'thursday'      => 'thứ Năm',
'friday'        => 'thứ Sáu',
'saturday'      => 'thứ Bảy',
'sun'           => 'Chủ nhật',
'mon'           => 'thứ 2',
'tue'           => 'thứ 3',
'wed'           => 'thứ 4',
'thu'           => 'thứ 5',
'fri'           => 'thứ 6',
'sat'           => 'thứ 7',
'january'       => 'tháng 1',
'february'      => 'tháng 2',
'march'         => 'tháng 3',
'april'         => 'tháng 4',
'may_long'      => 'tháng 5',
'june'          => 'tháng 6',
'july'          => 'tháng 7',
'august'        => 'tháng 8',
'september'     => 'tháng 9',
'october'       => 'tháng 10',
'november'      => 'tháng 11',
'december'      => 'tháng 12',
'january-gen'   => 'tháng Một',
'february-gen'  => 'tháng Hai',
'march-gen'     => 'tháng Ba',
'april-gen'     => 'tháng Tư',
'may-gen'       => 'tháng Năm',
'june-gen'      => 'tháng Sáu',
'july-gen'      => 'tháng Bảy',
'august-gen'    => 'tháng Tám',
'september-gen' => 'tháng Chín',
'october-gen'   => 'tháng Mười',
'november-gen'  => 'tháng Mười một',
'december-gen'  => 'tháng Mười hai',
'jan'           => 'tháng 1',
'feb'           => 'tháng 2',
'mar'           => 'tháng 3',
'apr'           => 'tháng 4',
'may'           => 'tháng 5',
'jun'           => 'tháng 6',
'jul'           => 'tháng 7',
'aug'           => 'tháng 8',
'sep'           => 'tháng 9',
'oct'           => 'tháng 10',
'nov'           => 'tháng 11',
'dec'           => 'tháng 12',

# Bits of text used by many pages
'categories'            => 'Thể loại',
'pagecategories'        => 'Thể loại',
'category_header'       => 'Các bài trong Thể loại "$1"',
'subcategories'         => 'Tiểu thể loại',
'category-media-header' => 'Các tập tin trong thể loại “$1”',
'category-empty'        => "''Thể loại này hiện không có bài viết hay tập tin.''",

'mainpagetext'      => "<big>'''MediaWiki đã được cài đặt thành công.'''</big>",
'mainpagedocfooter' => 'Xin đọc [http://meta.wikimedia.org/wiki/Help:Contents Hướng dẫn sử dụng] để biết thêm thông tin về cách sử dụng phần mềm wiki.

== Để bắt đầu ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Danh sách các thiết lập cấu hình]
* [http://www.mediawiki.org/wiki/Manual:FAQ Các câu hỏi thường gặp MediaWiki]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Danh sách gửi thư về việc phát hành MediaWiki]',

'about'          => 'Giới thiệu',
'article'        => 'Bài',
'newwindow'      => '(mở cửa sổ mới)',
'cancel'         => 'Hủy',
'qbfind'         => 'Tìm kiếm',
'qbbrowse'       => 'Xem qua',
'qbedit'         => 'Sửa',
'qbpageoptions'  => 'Trang này',
'qbpageinfo'     => 'Ngữ cảnh',
'qbmyoptions'    => 'Trang của tôi',
'qbspecialpages' => 'Trang đặc biệt',
'moredotdotdot'  => 'Thêm nữa...',
'mypage'         => 'Trang của tôi',
'mytalk'         => 'Thảo luận với tôi',
'anontalk'       => 'Thảo luận với IP này',
'navigation'     => 'Chuyển hướng',

# Metadata in edit box
'metadata_help' => 'Đặc tính hình:',

'errorpagetitle'    => 'Lỗi',
'returnto'          => 'Quay lại $1.',
'tagline'           => 'Bài từ {{SITENAME}}.',
'help'              => 'Trợ giúp',
'search'            => 'Tìm kiếm',
'searchbutton'      => 'Tìm kiếm',
'go'                => 'Xem',
'searcharticle'     => 'Xem',
'history'           => 'Lịch sử trang',
'history_short'     => 'Lịch sử',
'updatedmarker'     => 'được cập nhật kể từ lần xem cuối',
'info_short'        => 'Thông tin',
'printableversion'  => 'Bản để in',
'permalink'         => 'Liên kết thường trực',
'print'             => 'In',
'edit'              => 'Sửa đổi',
'editthispage'      => 'Sửa trang này',
'delete'            => 'Xóa',
'deletethispage'    => 'Xóa trang này',
'undelete_short'    => 'Phục hồi $1 sửa đổi',
'protect'           => 'Khóa',
'protect_change'    => 'đổi mức khóa',
'protectthispage'   => 'Khóa trang này',
'unprotect'         => 'Mở khóa',
'unprotectthispage' => 'Mở khóa trang này',
'newpage'           => 'Trang mới',
'talkpage'          => 'Thảo luận về trang này',
'talkpagelinktext'  => 'Thảo luận',
'specialpage'       => 'Trang đặc biệt',
'personaltools'     => 'Công cụ cá nhân',
'postcomment'       => 'Thêm bàn luận',
'articlepage'       => 'Xem bài',
'talk'              => 'Thảo luận',
'views'             => 'Xem',
'toolbox'           => 'Thanh công cụ',
'userpage'          => 'Trang thành viên',
'projectpage'       => 'Trang Wikipedia',
'imagepage'         => 'Trang hình',
'mediawikipage'     => 'Thông báo giao diện',
'templatepage'      => 'Trang tiêu bản',
'viewhelppage'      => 'Trang trợ giúp',
'categorypage'      => 'Trang thể loại',
'viewtalkpage'      => 'Trang thảo luận',
'otherlanguages'    => 'Ngôn ngữ khác',
'redirectedfrom'    => '(đổi hướng từ $1)',
'redirectpagesub'   => 'Trang đổi hướng',
'lastmodifiedat'    => 'Lần sửa cuối : $2, $1.', # $1 date, $2 time
'viewcount'         => 'Trang này đã được đọc $1 lần.',
'protectedpage'     => 'Trang bị khóa',
'jumpto'            => 'Bước tới:',
'jumptonavigation'  => 'chuyển hướng',
'jumptosearch'      => 'tìm kiếm',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Giới thiệu {{SITENAME}}',
'aboutpage'         => 'Project:Giới thiệu',
'bugreports'        => 'Báo lỗi',
'bugreportspage'    => 'Project:Báo lỗi',
'copyright'         => 'Bản quyền $1.',
'copyrightpagename' => 'giấy phép {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Bản quyền',
'currentevents'     => 'Thời sự',
'currentevents-url' => 'Project:Thời sự',
'disclaimers'       => 'Phủ nhận',
'disclaimerpage'    => 'Project:Phủ nhận chung',
'edithelp'          => 'Trợ giúp sửa đổi',
'edithelppage'      => 'Help:Sửa đổi',
'faq'               => 'Câu hỏi thường gặp',
'helppage'          => 'Help:Nội dung',
'mainpage'          => 'Trang Chính',
'policy-url'        => 'Project:Quy định và hướng dẫn',
'portal'            => 'Cộng đồng',
'portal-url'        => 'Project:Cộng đồng',
'privacy'           => 'Chính sách về sự riêng tư',
'privacypage'       => 'Project:Chính sách về sự riêng tư',
'sitesupport'       => 'Quyên góp',
'sitesupport-url'   => 'Project:Quyên góp',

'badaccess'        => 'Lỗi về quyền truy cập',
'badaccess-group0' => 'Bạn không được phép thực hiện thao tác này.',
'badaccess-group1' => 'Chỉ những thành viên trong nhóm $1 mới được làm thao tác này.',
'badaccess-group2' => 'Chỉ những thành viên trong các nhóm $1 mới được làm thao tác này.',
'badaccess-groups' => 'Chỉ những thành viên trong các nhóm $1 mới được làm thao tác này.',

'versionrequired'     => 'Cần phiên bản $1 của MediaWiki',
'versionrequiredtext' => 'Cần phiên bản $1 của MediaWiki để sử dụng trang này. Xem [[Special:Version|phiên bản trang]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Lấy từ "$1"',
'youhavenewmessages'      => 'Bạn có $1 ($2).',
'newmessageslink'         => 'tin nhắn mới',
'newmessagesdifflink'     => 'thay đổi gần nhất',
'youhavenewmessagesmulti' => 'Bạn có tin nhắn mới ở $1',
'editsection'             => 'sửa',
'editold'                 => 'sửa',
'editsectionhint'         => 'Sửa đổi đề mục: $1',
'toc'                     => 'Mục lục',
'showtoc'                 => 'xem',
'hidetoc'                 => 'giấu',
'thisisdeleted'           => 'Xem hay phục hồi $1 ?',
'viewdeleted'             => 'Xem $1?',
'restorelink'             => '$1 sửa đổi đã xóa',
'feedlinks'               => 'Nạp:',
'feed-invalid'            => 'Định dạng feed không hợp lệ.',
'site-rss-feed'           => '$1 mục RSS',
'site-atom-feed'          => '$1 mục Atom',
'page-rss-feed'           => 'Mục RSS của "$1"',
'page-atom-feed'          => 'Mục Atom của "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Bài viết',
'nstab-user'      => 'Trang thành viên',
'nstab-media'     => 'Phương tiện',
'nstab-special'   => 'Đặc biệt',
'nstab-project'   => 'Dự án',
'nstab-image'     => 'Hình',
'nstab-mediawiki' => 'Thông báo',
'nstab-template'  => 'Tiêu bản',
'nstab-help'      => 'Trợ giúp',
'nstab-category'  => 'Thể loại',

# Main script and global functions
'nosuchaction'      => 'Không có tác vụ này',
'nosuchactiontext'  => 'Wiki không hiểu được tác vụ được yêu cầu trong địa chỉ URL',
'nosuchspecialpage' => 'Không có trang đặc biệt nào như vậy',
'nospecialpagetext' => 'Không có trang đặc biệt này.',

# General errors
'error'                => 'Lỗi',
'databaseerror'        => 'Lỗi cơ sở dữ liệu',
'dberrortext'          => 'Lỗi cú pháp trong cơ sở dữ liệu.
Cái này có thể mà một lỗi của phần mềm.
Truy vấn vừa rồi là:
<blockquote><tt>$1</tt></blockquote>
từ hàm "<tt>$2</tt>".
MySQL báo lỗi "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Lỗi cú pháp trong cơ sở dữ liệu.
Truy vấn vừa rồi là:
"$1"
từ hàm "$2".
MySQL báo lỗi "$3: $4".',
'noconnect'            => 'Xin lỗi! Hiện nay wiki đang gặp một số trục trặc kỹ thuật, và không thể kết nối với cơ sở dữ liệu. <br />
$1',
'nodb'                 => 'Không thấy cơ sở dữ liệu $1',
'cachederror'          => 'Đây là bản sao trong bộ nhớ đệm của trang bạn yêu cầu, nó có thể đã lỗi thời.',
'laggedslavemode'      => 'Cảnh báo: Trang có thể chưa được cập nhật.',
'readonly'             => 'Cơ sở dữ liệu bị khóa',
'enterlockreason'      => 'Nêu lý do khóa, cùng với thời hạn khóa',
'readonlytext'         => 'Cơ sở dữ liệu hiện đã bị khóa không nhận bài mới và các điều chỉnh khác, có lẽ để bảo trì cơ sở dữ liệu định kỳ, một thời gian ngắn nữa nó sẽ trở lại bình thường.

Người quản lý khóa nó đã đưa ra lời giải thích sau: $1',
'missingarticle'       => 'Cơ sở dữ liệu không tìm thấy đoạn văn bản trong trang mà lẽ ra nó phải tìm thấy, có tên "$1".

Điều này thường xảy ra do liên kết so sánh hoặc lịch sử bị lỗi thời đối với trang đã bị xóa.

Nếu trường hợp này không phải như vậy, bạn có thể đã tìm thấy một lỗi của phần mềm.
Xin hãy báo việc này lên người quản lý, ghi chú lại địa chỉ URL.',
'readonly_lag'         => 'Cơ sở dữ liệu bị khóa tự động trong khi các máy chủ cập nhật thông tin của nhau.',
'internalerror'        => 'Lỗi nội bộ',
'internalerror_info'   => 'Lỗi nội bộ: $1',
'filecopyerror'        => 'Không thể chép tập tin "$1" đến "$2".',
'filerenameerror'      => 'Không thể đổi tên tập tin "$1" thành "$2".',
'filedeleteerror'      => 'Không thể xóa tập tin "$1".',
'directorycreateerror' => 'Không thể tạo được danh mục "$1".',
'filenotfound'         => 'Không tìm thấy tập tin "$1".',
'fileexistserror'      => 'Không thể ghi ra tập tin "$1": tập tin đã tồn tại',
'unexpected'           => 'Không hiểu giá trị: "$1"="$2".',
'formerror'            => 'Lỗi: không gửi mẫu đi được.',
'badarticleerror'      => 'Không thể thực hiện được tác vụ như thế tại trang này.',
'cannotdelete'         => 'Không thể xóa trang hay tập tin được chỉ định. (Có thể nó đã bị ai đó xóa rồi).',
'badtitle'             => 'Tựa bài sai',
'badtitletext'         => 'Tựa bài yêu cầu không đúng, rỗng, hoặc là một liên kết ngôn ngữ hoặc liên kết wiki sai. Nó có thể chứa một hoặc nhiều ký tự mà tựa bài không thể sử dụng.',
'perfdisabled'         => 'Xin lỗi! Tính năng này đã bị tắt tạm thời do nó làm chậm cơ sở dữ liệu đến mức không ai có thể dùng được wiki.',
'perfcached'           => 'Dữ liệu sau được lấy từ bộ nhớ đệm và có thể đã lỗi thời.',
'perfcachedts'         => 'Dữ liệu dưới đây được đưa vào vùng nhớ đệm và được cập nhật lần cuối lúc $1.',
'querypage-no-updates' => 'Việc cập nhật trang này hiện đã bị tắt. Dữ liệu ở đây có thể bị lỗi thời.',
'wrong_wfQuery_params' => 'Tham số sai trong wfQuery()<br />
Hàm: $1<br />
Truy vấn: $2',
'viewsource'           => 'Xem mã nguồn',
'viewsourcefor'        => 'đối với $1',
'actionthrottled'      => 'Thao tác bị giới hạn',
'actionthrottledtext'  => 'Để nhằm tránh spam, bạn không thể thực hiện thao tác này quá nhiều lần trong một thời gian ngắn.  Xin hãy chờ vài phút trước khi thực hiện lại.',
'protectedpagetext'    => 'Trang này đã bị khóa không cho sửa đổi.',
'viewsourcetext'       => 'Bạn vẫn có thể xem và chép xuống mã nguồn của trang này:',
'protectedinterface'   => 'Trang này cung cấp một thông báo trong giao diện phần mềm, và bị khóa để tránh phá hoại.',
'editinginterface'     => "'''Lưu ý:''' Bạn đang sửa chữa một trang dùng để cung cấp thông báo giao diện cho phần mềm. Những thay đổi tại trang này sẽ ảnh hưởng đến giao diện của rất nhiều người dùng website này. Để dịch luật, hãy xem xét việc sử dụng [http://translatewiki.net/wiki/Translating:Intro Betawiki], dự án địa phương hóa của MediaWiki.",
'sqlhidden'            => '(giấu truy vấn SQL)',
'cascadeprotected'     => 'Trang này đã bị khóa không cho sửa đổi, vì nó được nhúng vào {{PLURAL:$1|trang|những trang}} đã bị khóa với tùy chọn "khóa theo tầng" được kích hoạt:
$2',
'namespaceprotected'   => "Bạn không được cấp quyền sửa các trang trong không gian '''$1'''.",
'customcssjsprotected' => 'Bạn không có quyền sửa đổi trang này vì nó chứa các tùy chọn cá nhân của một thành viên khác.',
'ns-specialprotected'  => 'Không thể sửa chữa các trang trong không gian tên {{ns:special}}.',
'titleprotected'       => 'Tựa đề này đã bị [[User:$1|$1]] khóa không cho tạo ra. Lý do được cung cấp là <i>$2</i>.',

# Login and logout pages
'logouttitle'                => 'Đăng xuất',
'logouttext'                 => "<strong>Bạn đã đăng xuất.</strong><br />
Bạn có thể tiếp tục dùng {{SITENAME}} một cách vô danh, hoặc bạn có thể đăng nhập lại dưới tên người dùng này hoặc tên người dùng khác. Xin lưu ý rằng một vài trang có thể vẫn hiển thị như bạn còn đăng nhập cho đến khi bạn xóa vùng nhớ đệm (''cache'') của trình duyệt.",
'welcomecreation'            => '== Chào mừng, $1! ==

Tài khoản của bạn đã mở. Đừng quên thay đổi tùy chọn cá nhân của bạn tại {{SITENAME}}.',
'loginpagetitle'             => 'Đăng nhập',
'yourname'                   => 'Tên người dùng',
'yourpassword'               => 'Mật khẩu',
'yourpasswordagain'          => 'Gõ lại mật khẩu',
'remembermypassword'         => 'Nhớ thông tin đăng nhập của tôi trên máy tính này',
'yourdomainname'             => 'Tên miền của bạn:',
'externaldberror'            => 'Có lỗi khi xác nhận cơ sở dữ liệu bên ngoài hoặc bạn không được phép cập nhật tài khoản bên ngoài.',
'loginproblem'               => '<b>Trục trặc đăng nhập.</b><br />Mời thử lại!',
'login'                      => 'Đăng nhập',
'loginprompt'                => 'Bạn cần bật cookie để đăng nhập vào {{SITENAME}}.',
'userlogin'                  => 'Đăng nhập / Mở tài khoản',
'logout'                     => 'Đăng xuất',
'userlogout'                 => 'Đăng xuất',
'notloggedin'                => 'Chưa đăng nhập',
'nologin'                    => 'Bạn chưa có tài khoản ở đây? $1.',
'nologinlink'                => 'Mở một tài khoản',
'createaccount'              => 'Mở tài khoản',
'gotaccount'                 => 'Đã mở tài khoản rồi? $1.',
'gotaccountlink'             => 'Đăng nhập',
'createaccountmail'          => 'qua thư điện tử',
'badretype'                  => 'Hai mật khẩu không khớp.',
'userexists'                 => 'Tên người dùng này đã có người lấy. Xin vui lòng chọn một tên khác.',
'youremail'                  => 'Thư điện tử:',
'username'                   => 'Tên người dùng:',
'uid'                        => 'Số thứ tự thành viên:',
'yourrealname'               => 'Tên thật:',
'yourlanguage'               => 'Ngôn ngữ:',
'yourvariant'                => 'Ngôn ngữ địa phương:',
'yournick'                   => 'Chữ ký trong thảo luận:',
'badsig'                     => 'Chữ ký không hợp lệ; hãy kiểm tra thẻ HTML.',
'badsiglength'               => 'Chữ ký quá dài; chỉ được phép không quá $1 ký tự.',
'email'                      => 'Thư điện tử',
'prefs-help-realname'        => 'Tên thật là tùy chọn và nếu bạn cung cấp, tên này sẽ dùng để ghi công cho bạn.',
'loginerror'                 => 'Lỗi đăng nhập',
'prefs-help-email'           => 'Địa chỉ thư điện tử là tùy chọn, nhưng nó cho phép những người khác liên lạc với bạn thông qua trang thành viên hoặc thảo luận thành viên mà không cần để lộ danh tánh.',
'prefs-help-email-required'  => 'Bắt buộc phải có địa chỉ e-mail.',
'nocookiesnew'               => 'Tài khoản đã mở, nhưng bạn chưa đăng nhập. {{SITENAME}} sử dụng cookie để đăng nhập vào tài khoản. Bạn đã tắt cookie. Xin hãy kích hoạt nó, rồi đăng nhập lại với tên người dùng và mật khẩu mới.',
'nocookieslogin'             => '{{SITENAME}} sử dụng cookie để đăng nhập thành viên. Bạn đã tắt cookie. Xin hãy kích hoạt rồi thử lại.',
'noname'                     => 'Chưa nhập tên.',
'loginsuccesstitle'          => 'Đăng nhập thành công',
'loginsuccess'               => "'''Bạn đã đăng nhập vào {{SITENAME}} với tên \"\$1\".'''",
'nosuchuser'                 => 'Thành viên "$1" không tồn tại. Xin kiểm tra lại tên, hoặc mở tài khoản mới.',
'nosuchusershort'            => 'Không có thành viên nào có tên "$1". Xin hãy kiểm tra lại chính tả.',
'nouserspecified'            => 'Bạn phải đưa ra tên đăng ký.',
'wrongpassword'              => 'Mật khẩu sai. Xin vui lòng nhập lại.',
'wrongpasswordempty'         => 'Bạn chưa gõ vào mật khẩu. Xin thử lần nữa.',
'passwordtooshort'           => 'Mật khẩu của bạn sai hoặc quá ngắn. Nó phải có ít nhất $1 ký tự và phải khác với tên người dùng của bạn.',
'mailmypassword'             => 'Gửi mật khẩu mới bằng thư điện tử',
'passwordremindertitle'      => 'Mật khẩu tạm thời cho {{SITENAME}}',
'passwordremindertext'       => 'Ai đó (có thể là bạn, có địa chỉ IP $1)
đã yêu cầu chúng tôi gửi một mật khẩu mới của {{SITENAME}} ($4).
Mật khẩu mới của "$2" giờ là "$3".
Bạn nên đăng nhập và thay đổi mật khẩu ngay bây giờ.

Nếu một người nào khác yêu cầu điều này hoặc 
nếu bạn đã nhớ ra mật khẩu và không còn muốn đổi nó nữa, 
bạn có thể bỏ qua tin nhắn này và tiếp tục sử dụng
mật khẩu cũ của bạn.',
'noemail'                    => 'Thành viên "$1" không đăng ký thư điện tử.',
'passwordsent'               => 'Mật khẩu mới đã được gửi tới thư điện tử của thành viên "$1". Xin đăng nhập lại sau khi nhận thư.',
'blocked-mailpassword'       => 'Địa chỉ IP của bạn bị cấm không được sửa đổi, do đó cũng không được phép dùng chức năng phục hồi mật khẩu để tránh lạm dụng.',
'eauthentsent'               => 'Thư xác nhận đã được gửi. Trước khi dùng chức năng nhận thư, bạn cần thực hiện hướng dẫn trong thư xác nhận, để đảm bảo tài khoản thuộc về bạn.',
'throttled-mailpassword'     => 'Mật khẩu đã được gửi đến cho bạn trong vòng $1 giờ đồng hồ trở lại. Để tránh lạm dụng, chỉ có thể gửi mật khẩu $1 giờ đồng hồ một lần.',
'mailerror'                  => 'Lỗi gửi thư : $1',
'acct_creation_throttle_hit' => 'Bạn đã mở $1 tài khoản. Không thể mở thêm được nữa.',
'emailauthenticated'         => 'Địa chỉ thư điện tử của bạn được xác nhận tại $1.',
'emailnotauthenticated'      => 'Địa chỉ thư điện tử của bạn chưa được xác nhận. Chức năng thư điện tử chưa bật.',
'noemailprefs'               => 'Không có địa chỉ thư điện tử, chức năng sau có thể không hoạt động.',
'emailconfirmlink'           => 'Xác nhận địa chỉ thư điện tử',
'invalidemailaddress'        => 'Địa chỉ thư điện tử không được chấp nhận định dạng có vẻ sai. Xin hãy nhập lại một địa chỉ có định dạng đúng hoặc bỏ trống ô đó.',
'accountcreated'             => 'Mở tài khoản thành công',
'accountcreatedtext'         => 'Tài khoản thành viên cho $1 đã được mở.',
'createaccount-title'        => 'Tài khoản mới tại {{SITENAME}}',
'createaccount-text'         => 'Ai đó (ở địa chỉ $1) đã tạo một tài khoản với tên $2 tại {{SITENAME}}
($4). Mật khẩu của "$2" là "$3". Bạn nên đăng nhập và đổi mật khẩu ngay bây giờ.

Xin hãy bỏ qua thông báo này nếu tài khoản này không phải do bạn tạo ra.',
'loginlanguagelabel'         => 'Ngôn ngữ: $1',

# Password reset dialog
'resetpass'               => 'Đặt lại mật khẩu',
'resetpass_announce'      => 'Bạn đã đăng nhập bằng mật khẩu tạm gởi qua e-mail. Để hoàn tất việc đăng nhập, bạn phải tạo lại mật khẩu mới tại đây:',
'resetpass_text'          => '<!-- Gõ chữ vào đây -->',
'resetpass_header'        => 'Đặt lại mật khẩu',
'resetpass_submit'        => 'Chọn mật khẩu và đăng nhập',
'resetpass_success'       => 'Đã đổi mật khẩu thành công! Đang đăng nhập…',
'resetpass_bad_temporary' => 'Mật khẩu tạm sai. Có thể là bạn đã đổi mật khẩu thành công hay đã xin mật khẩu tạm mới.',
'resetpass_forbidden'     => 'Không được đổi mật khẩu ở {{SITENAME}}',
'resetpass_missing'       => 'Biểu mẫu đang trống.',

# Edit page toolbar
'bold_sample'     => 'Chữ đậm',
'bold_tip'        => 'Chữ đậm',
'italic_sample'   => 'Chữ xiên',
'italic_tip'      => 'Chữ xiên',
'link_sample'     => 'Liên kết',
'link_tip'        => 'Liên kết',
'extlink_sample'  => 'http://www.vidu.com liên kết ngoài',
'extlink_tip'     => 'Liên kết ngoài (nhớ ghi http://)',
'headline_sample' => 'Đề mục',
'headline_tip'    => 'Đề mục cấp 2',
'math_sample'     => 'Nhập công thức toán vào đây',
'math_tip'        => 'Công thức toán (LaTeX)',
'nowiki_sample'   => 'Nhập dòng chữ không theo định dạng wiki vào đây',
'nowiki_tip'      => 'Không theo định dạng wiki',
'image_sample'    => 'Ví dụ.jpg',
'image_tip'       => 'Chèn hình',
'media_sample'    => 'Ví dụ.ogg',
'media_tip'       => 'Liên kết phương tiện',
'sig_tip'         => 'Chữ ký có ngày',
'hr_tip'          => 'Dòng kẻ ngang (không nên lạm dụng)',

# Edit pages
'summary'                   => 'Tóm tắt',
'subject'                   => 'Đề mục',
'minoredit'                 => 'Sửa đổi nhỏ',
'watchthis'                 => 'Theo dõi bài này',
'savearticle'               => 'Lưu trang',
'preview'                   => 'Xem thử',
'showpreview'               => 'Xem thử',
'showlivepreview'           => 'Xem thử nhanh',
'showdiff'                  => 'Xem thay đổi',
'anoneditwarning'           => "'''Cảnh báo:''' Bạn chưa đăng nhập. Địa chỉ IP của bạn sẽ được ghi lại trong lịch sử sửa đổi của trang.",
'missingsummary'            => "'''Nhắc nhở:''' Bạn đã không ghi lại tóm lược sửa đổi. Nếu bạn nhấn Lưu trang một lần nữa, sửa đổi của bạn sẽ được lưu mà không có tóm lược.",
'missingcommenttext'        => 'Xin hãy gõ vào lời bàn luận ở dưới.',
'missingcommentheader'      => "'''Nhắc nhở:''' Bạn chưa cung cấp đề mục cho bàn luận này. Nếu bạn nhấn nút Lưu trang lần nữa, sửa đổi của bạn sẽ được lưu mà không có đề mục.",
'summary-preview'           => 'Xem trước dòng tóm lược',
'subject-preview'           => 'Xem trước đề mục',
'blockedtitle'              => 'Thành viên bị cấm',
'blockedtext'               => "<big>'''Tên người dùng hoặc địa chỉ IP của bạn đã bị cấm.'''</big>

Người thực hiện cấm là $1. Lý do được cung cấp là ''$2''.

* Bắt đầu cấm: $8
* Kết thúc cấm: $6
* Người bị cấm: $7

Bạn có thể liên lạc với $1 hoặc một [[{{MediaWiki:Grouppage-sysop}}|người quản lý]] khác để thảo luận về việc cấm.
Bạn không thể sử dụng tính năng 'gửi thư cho người này' trừ khi bạn đã đăng ký một địa chỉ thư điện tử hợp lý trong 
[[Special:Preferences|tùy chọn tài khoản]] và bạn không bị khóa chức năng đó
Địa chỉ IP hiện tại của bạn là $3, và mã số cấm là #$5. Xin hãy ghi kèm theo một trong hai hoặc cả hai vào các yêu cầu của bạn.",
'autoblockedtext'           => 'Địa chỉ IP của bạn đã bị tự động cấm vì một người nào đó đã sử dụng nó, và người đó đã bị $1 cấm.
Lý do được cung cấp là:

:\'\'$2\'\'

* Thời điểm bắt đầu cấm: $8
* Thời điểm kết thúc cấm: $6

Bạn có thể liên lạc với $1 hoặc một trong số các
[[{{MediaWiki:Grouppage-sysop}}|quản lý]] khác để thảo luận về việc cấm.

Chú ý rằng bạn sẽ không dùng được chức năng "gửi thư cho người này" trừ khi bạn đã đăng ký một địa chỉ thư điện tử đúng trong [[Special:Preferences|tùy chọn]] và chức năng đó không bị cấm.

Mã số cấm của bạn là $5. Xin hãy ghi kèm mã số này trong những yêu cầu của bạn.',
'blockednoreason'           => 'không đưa ra lý do',
'blockedoriginalsource'     => "Mã nguồn của '''$1''':",
'blockededitsource'         => "Các '''sửa đổi của bạn''' ở '''$1''':",
'whitelistedittitle'        => 'Cần đăng nhập để sửa bài',
'whitelistedittext'         => 'Bạn phải $1 để sửa bài.',
'whitelistreadtitle'        => 'Cần đăng nhập để đọc bài',
'whitelistreadtext'         => 'Bạn cần [[Special:Userlogin|đăng nhập]] để đọc bài.',
'whitelistacctitle'         => 'Bạn không được phép mở tài khoản.',
'whitelistacctext'          => 'Bạn cần [[Special:Userlogin|đăng nhập]] để mở tài khoản và phải có quyền tương ứng tại {{SITENAME}}.',
'confirmedittitle'          => 'Cần xác nhận địa chỉ thư điện tử trước khi sửa đổi',
'confirmedittext'           => 'Bạn cần phải xác nhận địa chỉ thư điện tử trước khi được sửa đổi trang. Xin hãy đặt và xác nhận địa chỉ thư điện tử của bạn dùng trang [[Special:Preferences|tùy chọn]].',
'nosuchsectiontitle'        => 'Không có mục nào như vậy',
'nosuchsectiontext'         => 'Bạn vừa sửa đổi một mục chưa tồn tại.  Do đó sửa đổi của bạn không được lưu.',
'loginreqtitle'             => 'Cần đăng nhập',
'loginreqlink'              => 'đăng nhập',
'loginreqpagetext'          => 'Bạn phải $1 mới có quyền xem các trang khác.',
'accmailtitle'              => 'Đã gửi mật khẩu.',
'accmailtext'               => 'Mật khẩu của "$1" đã được gửi đến $2.',
'newarticle'                => '(Mới)',
'newarticletext'            => "Bạn đi đến đây từ một liên kết đến một trang chưa tồn tại.
Để tạo trang, hãy bắt đầu gõ vào ô bên dưới
(xem [[{{MediaWiki:Helppage}}|trang trợ giúp]] để có thêm thông tin).
Nếu bạn đến đây do nhầm lẫn, chỉ cần nhấn vào nút '''back''' trên trình duyệt của bạn.",
'anontalkpagetext'          => "----''Đây là trang thảo luận của một thành viên vô danh chưa tạo tài khoản hoặc có nhưng không đăng nhập. Do đó chúng ta phải dùng một dãy số gọi là địa chỉ IP để xác định anh/chị ta. Một địa chỉ IP như vậy có thể có nhiều người cùng dùng chung. Nếu bạn là một thành viên vô danh và cảm thấy rằng có những lời bàn luận không thích hợp đang nhắm vào bạn, xin hãy [[Special:Userlogin|tạo tài khoản hoặc đăng nhập]] để tránh sự nhầm lẫn về sau với những thành viên vô danh khác.''",
'noarticletext'             => 'Trang này hiện chưa có gì, bạn có thể [[Special:Search/{{PAGENAME}}|tìm kiếm tựa bài]] tại các trang khác hoặc [{{fullurl:{{FULLPAGENAME}}|action=edit}} sửa đổi trang này].',
'userpage-userdoesnotexist' => 'Tài khoản mang tên "$1" chưa được đăng ký. Xin hãy kiểm tra lại nếu bạn muốn tạo/sửa trang này.',
'clearyourcache'            => "'''Ghi chú:''' Sau khi lưu trang, có thể bạn sẽ phải xóa bộ nhớ đệm của trình duyệt để xem các thay đổi. '''Mozilla / Firefox / Safari:''' giữ phím ''Shift'' trong khi nhấn ''Reload'', hoặc nhấn tổ hợp ''Ctrl-Shift-R'' (''Cmd-Shift-R'' trên Apple Mac); '''IE:''' giữ phím ''Ctrl'' trong khi nhấn ''Refresh'', hoặc nhấn tổ hợp ''Ctrl-F5''; '''Konqueror:''': chỉ cần nhấn nút ''Reload'', hoặc nhấn ''F5''; người dùng '''Opera''' có thể cần phải xóa hoàn toàn bộ nhớ đệm trong ''Tools→Preferences''.",
'usercssjsyoucanpreview'    => "<strong>Mẹo:</strong> Sử dụng nút 'Xem thử' để kiểm thử trang CSS/JS của bạn trước khi lưu trang.",
'usercsspreview'            => "'''Nhớ rằng bạn chỉ đang xem thử trang CSS, nó chưa được lưu!'''",
'userjspreview'             => "'''Nhớ rằng bạn chỉ đang kiểm thử/xem thử trang JavaScript, nó chưa được lưu!'''",
'userinvalidcssjstitle'     => "'''Cảnh báo:''' Không có skin \"\$1\". Hãy nhớ rằng các trang .css và .js tùy chỉnh sử dụng tiêu đề chữ thường, như {{ns:user}}:Ví&nbsp;dụ/monobook.css chứ không phải {{ns:user}}:Ví&nbsp;dụ/Monobook.css.",
'updated'                   => '(Cập nhật)',
'note'                      => '<strong>Ghi chú:</strong>',
'previewnote'               => '<strong>Đây chỉ mới là xem thử; các thay đổi vẫn chưa được lưu!</strong>',
'previewconflict'           => 'Phần xem thử này là kết quả của văn bản trong vùng soạn thảo phía trên và nó sẽ xuất hiện như vậy nếu bạn chọn lưu trang.',
'session_fail_preview'      => '<strong>Xin lỗi! Những sửa đổi của bạn chưa được lưu giữ do mất dữ liệu về phiên làm việc. 
Xin hãy thử lần nữa. Nếu vẫn không thành công, bạn hãy thử đăng xuất và đăng nhập lại.</strong>',
'session_fail_preview_html' => "<strong>Xin lỗi! Những sửa đổi của bạn chưa được lưu giữ do mất dữ liệu về phiên làm việc.</strong>

''Do {{SITENAME}} cho phép dùng mã HTML, trang xem thử được giấu đi để đề phòng bị tấn công bằng JavaScript.''

<strong>Nếu sửa đổi này là đúng đắn, xin hãy thử lần nữa. Nếu vẫn không thành công, bạn hãy thử đăng xuất và đăng nhập lại.</strong>",
'token_suffix_mismatch'     => '<strong>Sửa đổi của bạn bị hủy bỏ vì trình duyệt của bạn lẫn lộn các ký tự dấu trong số hiệu 
sửa đổi. Việc hủy bỏ này nhằm tránh nội dung trang bị hỏng.
Điều này thường xảy ra khi bạn sử dụng một dịch vụ proxy vô danh trên web có vấn đề.</strong>',
'editing'                   => 'Sửa đổi $1',
'editinguser'               => 'Sửa đổi $1',
'editingsection'            => 'Sửa đổi $1',
'editingcomment'            => 'Sửa đổi $1',
'editconflict'              => 'Sửa đổi mâu thuẫn : $1',
'explainconflict'           => 'Trang này có đã được lưu bởi người khác sau khi bạn bắt đầu sửa. Phía trên là bản hiện tại. Phía dưới là sửa đổi của bạn. Bạn sẽ phải trộn thay đổi của bạn với bản hiện tại. <b>Chỉ có</b> phần văn bản ở phía trên là sẽ được lưu khi bạn nhất nút "Lưu trang".<br />',
'yourtext'                  => 'Nội dung bạn nhập',
'storedversion'             => 'Phiên bản lưu',
'nonunicodebrowser'         => "<strong>CHU' Y': Tri`nh duye^.t cu?a ba.n kho^ng ho^~ tro+. unicode. Mo^.t ca'ch dde^? ba.n co' the^? su+?a ddo^?i an toa`n trang na`y: ca'c ky' tu+. kho^ng pha?i ASCII se~ xua^'t hie^.n trong ho^.p soa.n tha?o du+o+'i da.ng ma~ tha^.p lu.c pha^n.</strong>",
'editingold'                => '<strong>Chú ý: bạn đang sửa một phiên bản cũ. Nếu bạn lưu, các sửa đổi trên các phiên bản mới hơn sẽ bị mất.</strong>',
'yourdiff'                  => 'Khác',
'copyrightwarning'          => 'Xin chú ý rằng tất cả các đóng góp của bạn tại {{SITENAME}} được xem là sẽ phát hành theo giấy phép $2 (xem $1 để biết thêm chi tiết). Nếu bạn không muốn bài viết của bạn bị sửa đổi không thương tiếc và không sẵn lòng cho phép phát hành lại, đừng đăng bài ở đây.<br />
Bạn phải đảm bảo với chúng tôi rằng chính bạn là người viết nên, hoặc chép nó từ một nguồn thuộc phạm vi công cộng hoặc tự do tương đương.
<strong>ĐỪNG ĐĂNG TÁC PHẨM CÓ BẢN QUYỀN MÀ CHƯA XIN PHÉP!</strong>',
'copyrightwarning2'         => 'Xin chú ý rằng tất cả các đóng góp của bạn tại {{SITENAME}} có thể được sửa đổi, thay thế, hoặc xóa bỏ bởi các thành viên khác. Nếu bạn không muốn bài viết của bạn bị sửa đổi không thương tiếc, đừng đăng bài ở đây.<br />
Bạn phải đảm bảo với chúng tôi rằng chính bạn là người viết nên, hoặc chép nó từ một nguồn thuộc phạm vi công cộng hoặc tự do tương đương (xem $1 để biết thêm chi tiết).
<strong>ĐỪNG ĐĂNG TÁC PHẨM CÓ BẢN QUYỀN MÀ CHƯA XIN PHÉP!</strong>',
'longpagewarning'           => '<strong>CẢNH BÁO: Trang này dài $1 kilobyte; một số trình duyệt không tải được trang dài hơn 32 kb. Bạn nên chia nhỏ trang này thành nhiều trang.</strong>',
'longpageerror'             => '<strong>LỖI: Văn bạn mà bạn muốn lưu dài $1 kilobyte, dài hơn độ dài tối đa cho phép $2 kilobyte. Không thể lưu trang.</strong>',
'readonlywarning'           => '<strong>CẢNH BÁO: Cơ sở dữ liệu đã bị khóa để bảo dưỡng, do đó bạn không thể lưu các sửa đổi của mình. Bạn nên cắt-dán đoạn bạn vừa sửa vào một tập tin và lưu nó lại để sửa đổi sau này.</strong>',
'protectedpagewarning'      => '<strong>CẢNH BÁO:  Trang này đã bị khoá, chỉ có các thành viên có quyền quản lý mới sửa được.</strong>',
'semiprotectedpagewarning'  => "'''Ghi chú:''' Trang này đã bị khóa, chỉ cho phép các thành viên đã đăng ký sửa đổi.",
'cascadeprotectedwarning'   => "'''Cảnh báo:''' Trang này đã bị khóa, chỉ có thành viên có quyền quản lý mới có thể sửa đổi được, vì nó được nhúng vào {{PLURAL:$1|trang|những trang}} bị khóa theo tầng sau:",
'titleprotectedwarning'     => '<strong>CẢNH BÁO:  Trang này đã bị khóa, chỉ có một số thành viên mới có thể tạo ra.</strong>',
'templatesused'             => 'Các tiêu bản dùng trong trang này',
'templatesusedpreview'      => 'Các tiêu bản sẽ được dùng trong trang này:',
'templatesusedsection'      => 'Các tiêu bản sẽ được dùng trong phần này:',
'template-protected'        => '(khóa hoàn toàn)',
'template-semiprotected'    => '(bị hạn chế sửa đổi)',
'edittools'                 => '<!-- Văn bản dưới đây sẽ xuất hiện phía dưới mẫu sửa đổi và tải lên. -->',
'nocreatetitle'             => 'Khả năng tạo trang bị hạn chế',
'nocreatetext'              => '{{SITENAME}} đã hạn chế khả năng tạo trang mới.
Bạn có thể quay trở lại và sửa đổi các trang đã có, hoặc [[Special:Userlogin|đăng nhập hoặc tạo tài khoản]].',
'nocreate-loggedin'         => 'Bạn không có quyền tạo trang mới trên {{SITENAME}}.',
'permissionserrors'         => 'Không có quyền thực hiện',
'permissionserrorstext'     => 'Bạn không có quyền thực hiện thao tác đó, vì {{PLURAL:$1|lý do|lý do}}:',
'recreate-deleted-warn'     => "'''Cảnh báo: Bạn vừa tạo lại một trang từng bị xóa trước đây.'''

Bạn nên cân nhắc trong việc tiếp tục soạn thảo trang này.
Nhật trình xóa của trang được đưa ra dưới đây để tiện theo dõi:",

# "Undo" feature
'undo-success' => 'Các sửa đổi có thể được lùi lại. Xin hãy kiểm tra phần so sánh bên dưới để xác nhận lại những gì bạn muốn làm, sau đó lưu thay đổi ở dưới để hoàn tất việc lùi lại sửa đổi.',
'undo-failure' => 'Không có thể lùi lại sửa đổi vì những sửa đổi sau mâu thuẫn.',
'undo-summary' => 'Đã lùi lại sửa đổi $1 của [[Special:Contributions/$2]] ([[User talk:$2]])',

# Account creation failure
'cantcreateaccounttitle' => 'Không có thể mở tài khoản',
'cantcreateaccount-text' => "Chức năng tài tạo khoản từ địa chỉ IP này (<b>$1</b>) đã bị [[User:$3|$3]] cấm.

Lý do được $3 đưa ra là ''$2''",

# History pages
'viewpagelogs'        => 'Xem nhật trình của trang này',
'nohistory'           => 'Trang này chưa có lịch sử.',
'revnotfound'         => 'Không thấy',
'revnotfoundtext'     => 'Không thấy phiên bản trước của trang này. Xin kiểm tra lại.',
'loadhist'            => 'Đang mở lịch sử...',
'currentrev'          => 'Hiện nay',
'revisionasof'        => '$1',
'revision-info'       => 'Phiên bản của $1 vào lúc $2',
'previousrevision'    => '&larr; Bản trước',
'nextrevision'        => 'Bản sau &rarr;',
'currentrevisionlink' => 'xem bản hiện nay',
'cur'                 => 'nay',
'next'                => 'sau',
'last'                => 'cũ',
'orig'                => 'gốc',
'page_first'          => 'đầu',
'page_last'           => 'cuối',
'histlegend'          => 'Chú thích : (nay) = so sánh với bản hiện nay,
(cũ) = so sánh với bản trước, n = sửa nhỏ',
'deletedrev'          => '[đã xóa]',
'histfirst'           => 'cũ nhất',
'histlast'            => 'mới nhất',
'historysize'         => '($1 byte)',
'historyempty'        => '(trống)',

# Revision feed
'history-feed-title'          => 'Lịch sử thay đổi',
'history-feed-description'    => 'Lịch sử thay đổi của trang này ở wiki',
'history-feed-item-nocomment' => '$1 vào lúc $2', # user at time
'history-feed-empty'          => 'Trang bạn yêu cầu không tồn tại. Có thể là nó đã bị xóa khỏi wiki hay được đổi tên. Hãy [[Special:Search|tìm kiếm trong wiki]] về các trang liên quan mới.',

# Revision deletion
'rev-deleted-comment'         => '(đang giấu tóm lược)',
'rev-deleted-user'            => '(đang giấu tên hiệu)',
'rev-deleted-event'           => '(mục đã xóa)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Công chúng không được xem phiên bản này. [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Nhật trình xóa] có thể có thêm chi tiết.
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">
Công chúng không được xem phiên bản này, nhưng vì bạn có quyền quản lý, bạn có thể xem nó. [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} Nhật trình xóa] có thể có thêm chi tiết.
</div>',
'rev-delundel'                => 'xem/giấu',
'revisiondelete'              => 'Xóa hay phục hồi phiên bản',
'revdelete-nooldid-title'     => 'Chưa chọn phiên bản',
'revdelete-nooldid-text'      => 'Chưa chọn phiên bản để điều khiển.',
'revdelete-selected'          => "{{PLURAL:$2|Phiên bản|Các phiên bản}} được chọn của '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|Những nhật trình đã chọn|Nhật trình đã chọn}} của '''$1:'''",
'revdelete-text'              => 'Các phiên bản bị xóa vẫn còn trong lịch sử trang, nhưng công chúng không được xem nội dung của nó.

Các quản lý ở dự án này có thể truy nhập vào nội dung và phục hồi dùng giao diện này, trừ trường hợp nhóm bảo quản website đặt thêm hạn chế vào trang này.',
'revdelete-legend'            => 'Đặt hạn chế cho phiên bản:',
'revdelete-hide-text'         => 'Giấu văn bản của phiên bản',
'revdelete-hide-name'         => 'Giấu tác vụ và mục tiêu',
'revdelete-hide-comment'      => 'Giấu tóm lược sửa đổi',
'revdelete-hide-user'         => 'Giấu tên hiệu hay địa chỉ IP của người sửa đổi',
'revdelete-hide-restricted'   => 'Áp dụng những hạn chế này vào nhóm quản lý thêm vào công chúng',
'revdelete-suppress'          => 'Che dữ liệu đối với người quản lý cũng như các thành viên khác',
'revdelete-hide-image'        => 'Giấu nội dung tập tin',
'revdelete-unsuppress'        => 'Bỏ các hạn chế trên các phiên bản được phục hồi',
'revdelete-log'               => 'Tóm lược trong nhật trình:',
'revdelete-submit'            => 'Áp dụng vào phiên bản được chọn',
'revdelete-logentry'          => 'đã đổi hạn chế xem phiên bản của [[$1]]',
'logdelete-logentry'          => 'đã thay đổi khả năng nhìn thấy sự kiện của [[$1]]',
'revdelete-logaction'         => '$1 phiên bản được thiết lập chế độ $2',
'logdelete-logaction'         => '$1 sự kiện đối với [[$3]] được thiết lập chế độ $2',
'revdelete-success'           => 'Khả năng nhìn thấy của phiên bản đã được thiết lập thành công.',
'logdelete-success'           => 'Khả năng nhìn thấy của sự kiện đã được thiết lập thành công.',

# Oversight log
'oversightlog'    => 'Nhật trình giám thị',
'overlogpagetext' => 'Dưới đây là danh sách các tác vụ xóa và cấm gần nhất có liên quan đến nội dung đã được che đi đối với Người quản lý. Xem [[Special:Ipblocklist|danh sách các địa chỉ bị cấm]] để có danh sách các lần cấm hẳn và cấm thành viên hiện tại.',

# History merging
'mergehistory'                     => 'Trộn lịch sử trang',
'mergehistory-header'              => "Trang này cho phép trộn các sửa đổi của lịch sử một trang nguồn vào một trang mới.
Xin hãy bảo đảm tính liên tục của lịch sử trang.

'''Ít nhất phiên bản hiện tại của trang nguồn phải được duy trì.'''",
'mergehistory-box'                 => 'Trộn các sửa đổi của hai trang:',
'mergehistory-from'                => 'Trang nguồn:',
'mergehistory-into'                => 'Trang đích:',
'mergehistory-list'                => 'Lịch sử sửa đổi có thể trộn được',
'mergehistory-merge'               => 'Các sửa đổi sau của [[:$1]] có thể trộn được với [[:$2]]. Dùng một nút chọn trong cột để trộn các sửa đổi từ đầu cho đến thời điểm đã chọn. Lưu ý là việc dùng các liên kết chuyển hướng sẽ khởi tạo lại cột này.',
'mergehistory-go'                  => 'Hiển thị các sửa đổi có thể trộn được',
'mergehistory-submit'              => 'Trộn các sửa đổi',
'mergehistory-empty'               => 'Không có sửa đổi nào được trộn',
'mergehistory-success'             => '$3 sửa đổi của [[:$1]] đã được trộn vào [[:$2]].',
'mergehistory-fail'                => 'Không thể thực hiện được việc trộn lịch sử sửa đổi, vui lòng chọn lại trang cũng như thông số ngày giờ.',
'mergehistory-no-source'           => 'Trang nguồn $1 không tồn tại.',
'mergehistory-no-destination'      => 'Trang đích $1 không tồn tại.',
'mergehistory-invalid-source'      => 'Trang nguồn phải có tiêu đề hợp lệ.',
'mergehistory-invalid-destination' => 'Trang đích phải có tiêu đề hợp lệ.',

# Merge log
'mergelog'           => 'Nhật trình trộn',
'pagemerge-logentry' => 'đã trộn [[$1]] vào [[$2]] (sửa đổi cho đến $3)',
'revertmerge'        => 'Bỏ trộn',
'mergelogpagetext'   => 'Dưới đây là danh sách các thao tác trộn mới nhất của lịch sử một trang vào trang khác.',

# Diffs
'history-title'           => 'Lịch sử sửa đổi của “$1”',
'difference'              => '(Khác biệt giữa các bản)',
'lineno'                  => 'Dòng $1:',
'compareselectedversions' => 'So sánh các bản đã chọn',
'editundo'                => 'hồi sửa',
'diff-multi'              => '(Không hiển thị $1 phiên bản ở giữa)',

# Search results
'searchresults'         => 'Kết quả tìm',
'searchresulttext'      => 'Xem thêm [[{{ns:project}}:Tìm_kiếm|hướng dẫn tìm kiếm {{SITENAME}}]].',
'searchsubtitle'        => 'Cho truy vấn "[[:$1]]"',
'searchsubtitleinvalid' => 'Cho truy vấn "$1"',
'noexactmatch'          => "'''Trang \"\$1\" không tồn tại.''' Bạn có thể [[:\$1|tạo trang này]].",
'noexactmatch-nocreate' => "'''Không có trang nào có tên \"\$1\".'''",
'titlematches'          => 'Đề mục tương tự',
'notitlematches'        => 'Không có tên bài nào có nội dung tương tự',
'textmatches'           => 'Câu chữ tương tự',
'notextmatches'         => 'Không có câu chữ nào trong các bài có nội dung tương tự',
'prevn'                 => '$1 trước',
'nextn'                 => '$1 sau',
'viewprevnext'          => 'Xem ($1) ($2) ($3).',
'showingresults'        => "Xem '''$1''' kết quả bắt đầu từ #'''$2'''.",
'showingresultsnum'     => "Xem '''$3''' kết quả bắt đầu từ #'''$2'''.",
'nonefound'             => '<strong>Chú ý</strong>: viết truy vấn tìm kiếm dài quá có thể gây khó khăn khi tìm.',
'powersearch'           => 'Tìm kiếm',
'powersearchtext'       => '
Tìm trong :<br />
$1<br />
$2 gồm cả trang đổi hướng &nbsp; Tìm $3 $9',
'searchdisabled'        => 'Chức năng tìm kiếm tại {{SITENAME}} đã bị tắt. Bạn có tìm kiếm bằng Google trong thời gian này. Chú ý rằng các chỉ mục từ {{SITENAME}} của chúng có thể đã lỗi thời.',

# Preferences page
'preferences'              => 'Tùy chọn',
'mypreferences'            => 'Tùy chọn',
'prefs-edits'              => 'Số lần sửa đổi:',
'prefsnologin'             => 'Chưa đăng nhập',
'prefsnologintext'         => 'Bạn phải [[Đặc_biệt:Userlogin|đăng nhập]] để sửa các Lựa chọn cá nhân của bạn.',
'prefsreset'               => 'Các Lựa chọn cá nhân đã được mặc định lại.',
'qbsettings'               => 'Các lựa chọn cho thanh công cụ',
'qbsettings-none'          => 'Không',
'qbsettings-fixedleft'     => 'Trái',
'qbsettings-fixedright'    => 'Phải',
'qbsettings-floatingleft'  => 'Nổi bên trái',
'qbsettings-floatingright' => 'Nổi bên phải',
'changepassword'           => 'Đổi mật khẩu',
'skin'                     => 'Ngoại hình',
'math'                     => 'Công thức toán',
'dateformat'               => 'Ngày tháng',
'datedefault'              => 'Không lựa chọn',
'datetime'                 => 'Ngày tháng',
'math_failure'             => 'Lỗi toán',
'math_unknown_error'       => 'lỗi chưa rõ',
'math_unknown_function'    => 'hàm chưa rõ',
'math_lexing_error'        => 'lỗi chính tả',
'math_syntax_error'        => 'lỗi ngữ pháp',
'math_image_error'         => 'Không chuyển sang định dạng PNG được, xin kiểm tra lại cài đặt Latex, dvips, gs và convert',
'math_bad_tmpdir'          => 'Không tạo mới hay viết vào thư mục tạm thời được',
'math_bad_output'          => 'Không tạo mới hay viết vào thư mục kết quả được',
'math_notexvc'             => "Không thấy 'texvc'. Xem math/README để cài đặt lại.",
'prefs-personal'           => 'Thông tin cá nhân',
'prefs-rc'                 => 'Thay đổi gần đây',
'prefs-watchlist'          => 'Theo dõi',
'prefs-watchlist-days'     => 'Số ngày trong danh sách theo dõi:',
'prefs-watchlist-edits'    => 'Số lần sửa đổi trong danh sách theo dõi nhiều chức năng:',
'prefs-misc'               => 'Lựa chọn khác',
'saveprefs'                => 'Lưu lựa chọn',
'resetprefs'               => 'Mặc định lại lựa chọn',
'oldpassword'              => 'Mật khẩu cũ',
'newpassword'              => 'Mật khẩu mới&nbsp;',
'retypenew'                => 'Gõ lại',
'textboxsize'              => 'Kích thước cửa sổ soạn thảo',
'rows'                     => 'Hàng&nbsp;',
'columns'                  => 'Cột',
'searchresultshead'        => 'Xem kết quả tìm kiếm',
'resultsperpage'           => 'Số kết quả trong một trang&nbsp;',
'contextlines'             => 'Số hàng trong một kết quả',
'contextchars'             => 'Số chữ trong một hàng',
'stub-threshold'           => '<a href="#" class="stub">Liên kết nâu</a> cho các bài ngắn hơn (byte):',
'recentchangesdays'        => 'Số ngày hiển thị trong thay đổi gần đây:',
'recentchangescount'       => 'Số đề mục trong Thay đổi gần đây',
'savedprefs'               => 'Đã lưu các lựa chọn cá nhân.',
'timezonelegend'           => 'Múi giờ',
'timezonetext'             => 'Nếu không chọn, giờ mặc định UTC sẽ được dùng.',
'localtime'                => 'Giờ địa phương',
'timezoneoffset'           => 'Chênh giờ¹',
'servertime'               => 'Giờ máy chủ',
'guesstimezone'            => 'Dùng giờ của trình duyệt',
'allowemail'               => 'Nhận thư điện tử từ các thành viên khác',
'defaultns'                => 'Mặc định tìm kiếm trong không gian tên :',
'default'                  => 'mặc định',
'files'                    => 'Tệp tin',

# User rights
'userrights-lookup-user'           => 'Quản lý nhóm thành viên',
'userrights-user-editname'         => 'Nhập tên thành viên:',
'editusergroup'                    => 'Sửa các nhóm thành viên',
'userrights-editusergroup'         => 'Sửa nhóm thành viên',
'saveusergroups'                   => 'Lưu nhóm thành viên',
'userrights-groupsmember'          => 'Thành viên của:',
'userrights-groupsremovable'       => 'Các nhóm có thể xóa được:',
'userrights-groupsavailable'       => 'Các nhóm hiện nay:',
'userrights-groupshelp'            => 'Chọn nhóm mà bạn muốn thêm hay bớt thành viên. Các nhóm không được chọn sẽ không thay đổi. Có thể chọn nhóm bằng CTRL + Chuột trái',
'userrights-reason'                => 'Lý do thay đổi:',
'userrights-available-none'        => 'Có thể bạn không đổi được kiểu nhóm thành viên.',
'userrights-available-add'         => 'Bạn có thể thêm thành viên vào {{PLURAL:$2|nhóm|các nhóm}}: $1.',
'userrights-available-remove'      => 'Bạn có thể xóa thành viên khỏi {{PLURAL:$2|nhóm|các nhóm}}: $1.',
'userrights-available-add-self'    => 'Bạn có thể tự thêm mình vào {{PLURAL:$2|nhóm này|các nhóm này}}: $1.',
'userrights-available-remove-self' => 'Bạn có thể tự xóa mình ra khỏi {{PLURAL:$2|nhóm này|các nhóm này}}: $1.',
'userrights-no-interwiki'          => 'Bạn không có quyền thay đổi quyền hạn của thành viên tại các wiki khác.',
'userrights-nodatabase'            => 'Cơ sở dữ liệu $1 không tồn tại hoặc nằm ở bên ngoài.',
'userrights-nologin'               => 'Bạn phải [[Special:Userlogin|đăng nhập]] vào một tài khoản có quyền quản lý để gán quyền cho thành viên.',
'userrights-notallowed'            => 'Tài khoản của bạn không có quyền gán quyền cho thành viên.',

# Groups
'group'               => 'Nhóm:',
'group-autoconfirmed' => 'Các thành viên tự động xác nhận.',
'group-bot'           => 'Các robot',
'group-sysop'         => 'Các quản lý',
'group-bureaucrat'    => 'Các hành chính viên',
'group-all'           => '(tất cả)',

'group-autoconfirmed-member' => 'Thành viên tự động xác nhận',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Quản lý',
'group-bureaucrat-member'    => 'Hành chính viên',

'grouppage-autoconfirmed' => '{{ns:project}}:Thành viên tự động xác nhận',
'grouppage-bot'           => '{{ns:project}}:Robot',
'grouppage-sysop'         => '{{ns:project}}:Người quản lý',
'grouppage-bureaucrat'    => '{{ns:project}}:Hành chính viên',

# User rights log
'rightslog'      => 'Nhật trình cấp thành viên',
'rightslogtext'  => 'Đây là nhật trình lưu những thay đổi đối với các quyền hạn thành viên.',
'rightslogentry' => 'đã đổi cấp của thành viên $1 từ $2 thành $3',
'rightsnone'     => '(không có)',

# Recent changes
'nchanges'                          => '$1 thay đổi',
'recentchanges'                     => 'Thay đổi gần đây',
'recentchangestext'                 => '[[{{ns:project}}:Chào mừng người mới đến|Chào mừng]] bạn! Trang này dùng để theo dõi các thay đổi gần đây trên {{SITENAME}}.',
'recentchanges-feed-description'    => 'Theo dõi các thay đổi gần đây nhất của wiki dùng feed này.',
'rcnote'                            => "Dưới đây là '''$1''' thay đổi gần nhất trong '''$2''' ngày qua, tính tới $3.",
'rcnotefrom'                        => 'Thay đổi từ <strong>$2</strong> (<b>$1</b> tối đa).',
'rclistfrom'                        => 'Xem thay đổi từ $1.',
'rcshowhideminor'                   => '$1 sửa đổi nhỏ',
'rcshowhidebots'                    => '$1 sửa đổi bot',
'rcshowhideliu'                     => '$1 sửa đổi thành viên',
'rcshowhideanons'                   => '$1 sửa đổi vô danh',
'rcshowhidepatr'                    => '$1 sửa đổi đã tuần tra',
'rcshowhidemine'                    => '$1 sửa đổi của tôi',
'rclinks'                           => 'Xem $1 thay đổi của $2 ngày qua; $3.',
'diff'                              => 'khác',
'hist'                              => 'sử',
'hide'                              => 'giấu',
'show'                              => 'xem',
'minoreditletter'                   => 'n',
'newpageletter'                     => 'M',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 người đang xem]',
'rc_categories'                     => 'Hạn chế theo thể loại (phân tách dùng “|”)',
'rc_categories_any'                 => 'Cái nào cũng được',
'newsectionsummary'                 => 'Đề mục mới: /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Thay đổi liên quan',
'recentchangeslinked-title'    => 'Những thay đổi liên quan tới $1',
'recentchangeslinked-noresult' => 'Không có thay đổi nào trên trang được liên kết trong khoảng thời gian đó.',
'recentchangeslinked-summary'  => "Trang đặc biệt này liệt kê các thay đổi gần đây nhất trên các trang được liên kết. Các trang trong danh sách bạn theo dõi được '''in đậm'''.",

# Upload
'upload'                      => 'Tải tập tin lên',
'uploadbtn'                   => 'Tải lên',
'reupload'                    => 'Tải lại',
'reuploaddesc'                => 'Quay lại.',
'uploadnologin'               => 'Chưa đăng nhập',
'uploadnologintext'           => 'Bạn phải [[Đặc_biệt:Userlogin|đăng nhập]] để tải lên tệp tin.',
'upload_directory_read_only'  => 'Máy chủ không thể sửa đổi thư mục tải lên ($1) được.',
'uploaderror'                 => 'Lỗi',
'uploadtext'                  => "Hãy sử dụng mẫu sau để tải tập tin, để xem hoặc tìm kiếm những hình ảnh đã được tải lên trước đây xin mời xem [[Special:Imagelist|danh sách các tập tin đã tải lên]], việc tải lên và xóa đi cũng được ghi lại trong [[Special:Log/upload|nhật trình tải lên]].

Để đưa hình vào bài viết, hãy dùng một liên kết theo dạng
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Tập_tin.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Tập_tin.png|văn bản thay thế]]</nowiki>''' hoặc
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Tập_tin.ogg]]</nowiki>''' để trực tiếp liên kết đến tập tin.",
'upload-permitted'            => 'Các định dạng tập tin được phép tải lên: $1.',
'upload-preferred'            => 'Các định dạng tập tin nên dùng: $1.',
'upload-prohibited'           => 'Các định dạng tập tin bị cấm: $1.',
'uploadlog'                   => 'Nhật trình tải lên',
'uploadlogpage'               => 'Nhật_trình_tải_lên',
'uploadlogpagetext'           => 'Danh sách các tệp tin đã tải lên, theo giờ máy chủ (UTC).',
'filename'                    => 'Tên&nbsp;',
'filedesc'                    => 'Mô tả&nbsp;',
'fileuploadsummary'           => 'tóm tắt',
'filestatus'                  => 'Bản quyền',
'filesource'                  => 'Nguồn',
'uploadedfiles'               => 'Đã tải xong',
'ignorewarning'               => 'Bỏ qua cảnh báo và lưu tập tin.',
'ignorewarnings'              => 'Bỏ qua cảnh báo',
'minlength1'                  => 'Tên tập tin phải có ít nhất một ký tự.',
'illegalfilename'             => 'Tên « $1 » có chứa ký tự không dùng được cho tên trang. Xin hãy đổi tên và tải lại.',
'badfilename'                 => 'Đổi thành tên « $1 ».',
'filetype-badmime'            => 'Không thể truyền lên các tập tin có định dạng MIME "$1".',
'filetype-unwanted-type'      => "'''\".\$1\"''' là định dạng tập tin không mong muốn.  Những loại tập tin thích hợp hơn là \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' là định dạng tập tin không được chấp nhận.  Những loại tập tin được chấp nhận là \$2.",
'filetype-missing'            => 'Tập tin không có phần mở rộng (ví dụ ".jpg").',
'large-file'                  => "'''Lớn quá:''' Tập tin này lớn đến $2. Nếu có thể, xin nén lại tập tin này hay sử dụng một định dạng khác để tập tin khỏi bị lớn hơn $1.",
'largefileserver'             => 'Tập tin này quá lớn so với khả năng phục vụ của máy chủ.',
'emptyfile'                   => 'Tệp tin tải lên là rỗng. Xin kiểm tra lại tên tệp tin.',
'fileexists'                  => "'Một tệp tin với tên này đã tồn tại, xin hãy kiểm tra $1 nếu bạn không muốn thay đổi nó.",
'fileexists-extension'        => 'Hiện có một tập tin trùng tên:<br />
Tên tập tin đang tải lên: <strong><tt>$1</tt></strong><br />
Tên tập tin có từ trước: <strong><tt>$2</tt></strong><br />
Khác biệt chỉ là chữ hoa/thường trong phần tên mở rộng. Xin hãy kiểm tra lại các tập tin này xem có thực sự giống nhau không.',
'fileexists-thumb'            => "'''<center>Hình đã có sẵn</center>'''",
'fileexists-thumbnail-yes'    => 'Tập tin này có vẻ là hình có kích thước thu gọn <i>(hình thu nhỏ)</i>. Xin kiểm tra lại tập tin <strong><tt>$1</tt></strong>.<br />
Nếu tập tin được kiểm tra trùng với hình có kích cỡ gốc thì không cần thiết tải lên một hình thu nhỏ khác.',
'file-thumbnail-no'           => 'Tên tập tin bắt đầu bằng <strong><tt>$1</tt></strong>. Có vẻ đây là bản có kích thước thu nhỏ của hình <i>(thumbnail)</i>.
Nếu bạn có bản ở độ phân giải tối đa, mời bạn tải bản này lên, nếu không hãy đổi lại tên tập tin.',
'fileexists-forbidden'        => 'Đã có tập tin với tên gọi này, và không thể lưu đè lên nó. Xin quay lại để truyền lên tập tin này dưới tên khác.

[[Hình:$1|nhỏ|giữa|$1]]',
'fileexists-shared-forbidden' => '<div style="float: left">[[Hình:Commons-logo.svg|không|20px]]</div>
<p class="plainlinks" style="margin-left: 24px">\'\'\'[{{fullurle:Commons:Trang Chính|uselang=vi}} Wikimedia Commons]\'\'\' đã có [{{fullurle:Commons:$1|uselang=vi}} tập tin cùng tên]; hãy quay lại và truyền lên tập tin này dưới tên khác.</p>

[[Hình:$1|nhỏ|giữa|$1]]',
'successfulupload'            => 'Đã tải xong',
'uploadwarning'               => 'Chú ý!',
'savefile'                    => 'Lưu tệp tin',
'uploadedimage'               => 'đã tải lên « [[$1]] »',
'overwroteimage'              => 'đã tải lên một phiên bản mới của "[[$1]]"',
'uploaddisabled'              => 'Xin lỗi, chức năng tải lên bị khóa.',
'uploaddisabledtext'          => 'Chức năng truyền lên tập tin bị tắt trên wiki này.',
'uploadscripted'              => 'Tập tin này có chứa mã HTML hoặc script có thể khiến trình duyệt web thông dịch sai.',
'uploadcorrupt'               => 'Tập tin bị hỏng hoặc có đuôi không chuẩn. Xin kiểm tra và tải lại.',
'uploadvirus'                 => 'Tệp tin có virút: $1',
'sourcefilename'              => 'Tên tệp tin nguồn',
'destfilename'                => 'Tên mới',
'watchthisupload'             => 'Theo dõi tập tin này',
'filewasdeleted'              => '<div style="float: left">[[Hình:Nuvola filesystems trashcan full.png|không|24px]]</div>
<p style="margin-left: 28px"><strong style="color: red; background-color: white">Đã bị xóa:</strong> Một tập tin dưới tên này đã được truyền lên trước đây nhưng bị xóa. Xin hãy kiểm tra $1 để biết lý do trước khi tiếp tục truyền nó lên đây.</p>',
'upload-wasdeleted'           => '<div style="float: left">[[Hình:Nuvola filesystems trashcan full.png|không|32px]]</div>
<p style="margin-left: 36px;"><strong style="color: red; background-color: white">Đã bị xóa:</strong> Bạn sẽ tải lên lại một tập tin \'\'\'từng bị xóa\'\'\' trước đây. Bạn nên cân nhắc trong việc tiếp tục tải lên tập tin này. Nhật trình xóa của tập tin được đưa ra dưới đây để tiện theo dõi:</p>',
'filename-bad-prefix'         => 'Tên cho tập tin mà bạn đang tải lên bắt đầu bằng <strong>"$1"</strong>, đây không phải là dạng tên tiêu biểu có tính chất mô tả do các máy chụp ảnh số tự động đặt. Xin hãy chọn một tên có tính chất mô tả và gợi nhớ hơn cho tập tin của bạn.',
'filename-prefix-blacklist'   => ' #<!-- xin để nguyên hàng này --> <pre>
# Cú pháp như sau:
#   * Các ký tự từ dấu "#" trở đến cuối hàng là chú thích
#   * Các dòng sau là các tiền tố do các máy ảnh số gán tự động cho tên tập tin 
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # một số điện thoại di động
IMG # tổng quát
JD # Jenoptik
MGP # Pentax
PICT # khác
 #</pre> <!-- xin để nguyên hàng này -->',

'upload-proto-error'      => 'Giao thức sai',
'upload-proto-error-text' => 'Phải đưa vào URL bắt đầu với <code>http://</code> hay <code>ftp://</code> để truyền lên tập tin từ website khác.',
'upload-file-error'       => 'Lỗi nội bộ',
'upload-file-error-text'  => 'Có lỗi nội bộ khi tạo ra tập tin tạm trên máy phục vụ. Xin hãy liên lạc với người quản lý hệ thống.',
'upload-misc-error'       => 'Lỗi lạ khi truyền lên',
'upload-misc-error-text'  => 'Có lỗi lạ khi truyền lên. Xin hãy kiểm tra là có URL đúng và có thể truy cập tập tin này, và thử lần nữa. Nếu vẫn bị lỗi, hãy liên lạc với người quản lý hệ thống.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Không thể truy cập URL',
'upload-curl-error6-text'  => 'Không có thể truy cập URL mà bạn đưa vào. Xin hãy kiểm tra là có URL đúng và website đang hoạt động.',
'upload-curl-error28'      => 'Hết thời lượng khi truyền lên',
'upload-curl-error28-text' => 'Website trả lời chậm quá. Xin hãy kiểm tra là website đang hoạt động không, chờ một tí, và thử lần nữa. Bạn có thể thử truyền lên vào lúc nó không bận.',

'license'            => 'Giấy phép',
'nolicense'          => 'chưa chọn',
'license-nopreview'  => '(Không xem trước được)',
'upload_source_url'  => ' (địa chỉ chuẩn dẫn đến nguồn công cộng)',
'upload_source_file' => ' (tập tin trên máy của bạn)',

# Image list
'imagelist'                 => 'Danh sách hình',
'imagelisttext'             => "Dưới đây là danh sách '''$1''' tập tin xếp theo $2.",
'getimagelist'              => 'Đang lấy danh sách hình',
'ilsubmit'                  => 'Tìm',
'showlast'                  => 'Xem $1 hình mới nhất xếp theo $2.',
'byname'                    => 'tên',
'bydate'                    => 'ngày',
'bysize'                    => 'kích cỡ',
'imgdelete'                 => 'xóa',
'imgdesc'                   => 'tả',
'imgfile'                   => 'tập tin',
'filehist'                  => 'Lịch sử tập tin',
'filehist-help'             => 'Nhấn vào một ngày/giờ để xem nội dung tập tin tại thời điểm đó.',
'filehist-deleteall'        => 'xóa toàn bộ',
'filehist-deleteone'        => 'xóa bản này',
'filehist-revert'           => 'lùi lại',
'filehist-current'          => 'hiện',
'filehist-datetime'         => 'Ngày/Giờ',
'filehist-user'             => 'Thành viên',
'filehist-dimensions'       => 'Kích cỡ',
'filehist-filesize'         => 'Kích thước tập tin',
'filehist-comment'          => 'Miêu tả',
'imagelinks'                => 'Liên kết đến hình',
'linkstoimage'              => 'Các trang sau có liên kết đến hình:',
'nolinkstoimage'            => 'Không có trang nào chứa liên kết đến hình.',
'sharedupload'              => 'Tập tin này được tải lên để dùng chung và có thể dùng ở các dự án khác.',
'shareduploadwiki'          => 'Xin xem thêm [$1 mô tả tệp tin]',
'shareduploadwiki-linktext' => 'trang miêu tả tập tin',
'noimage'                   => 'Không có hình này, bạn có thể [$1 tải nó lên]',
'noimage-linktext'          => 'tải lên',
'uploadnewversion-linktext' => 'Tải lên phiên bản mới',
'imagelist_date'            => 'Lúc truyền lên',
'imagelist_name'            => 'Tên',
'imagelist_user'            => 'Thành viên truyền lên',
'imagelist_size'            => 'Cỡ (byte)',
'imagelist_description'     => 'Miêu tả',
'imagelist_search_for'      => 'Tìm kiếm theo tên tập tin:',

# File reversion
'filerevert'                => 'Quay lui phiên bản của $1',
'filerevert-legend'         => 'Quay lui tập tin',
'filerevert-intro'          => '<span class="plainlinks">Bạn đang lùi \'\'\'[[Media:$1|$1]]\'\'\' về [phiên bản $4 lúc $3, $2].</span>',
'filerevert-comment'        => 'Lý do:',
'filerevert-defaultcomment' => 'Đã lui về phiên bản lúc $2, $1',
'filerevert-submit'         => 'Quay lui',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' đã được lui về [phiên bản $4 lúc $3, $2].</span>',
'filerevert-badversion'     => 'Không tồn tại phiên bản trước đó của tập tin tại thời điểm trên.',

# File deletion
'filedelete'             => 'Xóa $1',
'filedelete-legend'      => 'Xóa tập tin',
'filedelete-intro'       => "Bạn chuẩn bị xóa tập tin '''[[Media:\$1|\$1]]''' cùng với tất cả <span class=plainlinks>[{{fullurl:{{FULLPAGENAME}}|action=history}} lịch sử]</span> của nó. Xin hãy:
* '''Xác nhận''' rằng bạn thật sự định làm điều này và bạn đang thực hiện nó dựa theo [[{{ns:Project}}:Xóa trang#Hình ảnh|quy định xóa]];
* Kiểm tra lại <span class=\"plainlinks\">nhật trình xóa ở dưới</span>;
* Ghi lại lý do xóa trong đó giải thích rõ ràng tại sao bạn lại xóa tập tin, và '''''không ghi nội dung không thích hợp''''', vì các mục nhật trình sẽ dễ nhìn thấy hơn trước đây (điều này có nghĩa là bạn nên thay đổi lý do tự động tạo ra);
* Kiểm tra \"[[Đặc biệt:Whatlinkshere/{{FULLPAGENAMEE}}|Các liên kết đến đây]]\" trước khi xóa (liên kết đến trang này sẽ không bị đổi).  '''Cũng cần kiểm tra các liên kết đến tập tin hình ảnh và xóa bỏ các liên kết khỏi mọi bài viết.''' Trách nhiệm của bạn là phải [[Wikipedia:Hướng dẫn dành cho quản lý#Xóa một hình|xóa bỏ một cách hoàn toàn]] và không sót lại liên kết sai và các hộp có liên kết đỏ trong bài viết. <span id=\"Deletereason\"></span>",
'filedelete-intro-old'   => '<span class="plainlinks">Bạn đang xóa phiên bản của \'\'\'[[Media:$1|$1]]\'\'\' vào lúc [$4 $3, $2].</span>',
'filedelete-comment'     => 'Tóm tắt:',
'filedelete-submit'      => 'Xóa',
'filedelete-success'     => "'''$1''' đã bị xóa.",
'filedelete-success-old' => '<span class="plainlinks">Phiên bản của \'\'\'[[Media:$1|$1]]\'\'\' vào lúc $3, $2 đã bị xóa.</span>',
'filedelete-nofile'      => "'''$1''' không tồn tại trên site này.",
'filedelete-nofile-old'  => "Không có phiên bản lưu trữ của '''$1''' với các thuộc tính này.",
'filedelete-iscurrent'   => 'Bạn đang cố xóa phiên bản mới nhất của tập tin này. Xin hãy lui tập tin về một phiên bản cũ hơn đã.',

# MIME search
'mimesearch'         => 'Tìm kiếm theo định dạng',
'mimesearch-summary' => 'Trang này có khả năng lọc tập tin theo định dạng MIME. Đầu vào: contenttype/subtype, v.d. <tt>image/jpeg</tt>.',
'mimetype'           => 'Định dạng MIME:',
'download'           => 'tải xuống',

# Unwatched pages
'unwatchedpages' => 'Trang chưa được theo dõi',

# List redirects
'listredirects' => 'Danh sách trang đổi hướng',

# Unused templates
'unusedtemplates'     => 'Tiêu bản chưa dùng',
'unusedtemplatestext' => 'Đây là danh sách các trang thuộc tên miền không gian Tiêu bản mà chưa được nhúng vào trang khác. Trước khi xóa tiêu bản, hãy nhớ kiểm tra nó được liên kết từ trang khác hay không.',
'unusedtemplateswlh'  => 'liên kết khác',

# Random page
'randompage'         => 'Bài viết ngẫu nhiên',
'randompage-nopages' => 'Hiện chưa có trang nào trong không gian tên này.',

# Random redirect
'randomredirect'         => 'Trang đổi hướng ngẫu nhiên',
'randomredirect-nopages' => 'Không có trang đổi hướng nào trong không gian này.',

# Statistics
'statistics'             => 'Thống kê',
'sitestats'              => 'Thống kê',
'userstats'              => 'Thống kê thành viên',
'sitestatstext'          => "Hiện có \$1 bài trong cơ sở dữ liệu.
Trong số đó có các trang \"thảo luận\", trang liên quan đến {{SITENAME}}, các bài viết \"sơ khai\" ngắn, và những trang khác không tính là trang có nội dung.
Nếu không tính đến các trang đó, có \$2 trang là những trang có nội dung tốt.

Có '''\$8''' tập tin đã được tải lên.

Đã có tổng cộng '''\$3''' lần truy cập, và '''\$4''' sửa đổi từ khi {{SITENAME}} được khởi tạo.
Như vậy trung bình có '''\$5''' sửa đổi tại mỗi trang, và '''\$6''' lần truy cập trên mỗi sửa đổi.

Độ dài của [http://meta.wikimedia.org/wiki/Help:Job_queue hàng đợi việc] là '''\$7'''.",
'userstatstext'          => "Có '''$1''' [[Special:Listusers|thành viên]] đã đăng ký tài khoản, trong số đó có '''$2''' thành viên (chiếm '''$4%''' trên tổng số) là $5.",
'statistics-mostpopular' => 'Các trang được xem nhiều nhất',

'disambiguations'      => 'Trang định hướng',
'disambiguationspage'  => '{{ns:project}}:Trang_định_hướng',
'disambiguations-text' => "Các trang này có liên kết đến một '''[[Wikipedia:Định hướng|trang định hướng]]'''. Nên sửa các liên kết này để chỉ đến một bài đúng nghĩa hơn. Các trang định hướng là trang sử dụng những tiêu bản được liệt kê ở [[MediaWiki:Disambiguationspage]].",

'doubleredirects'     => 'Đổi hướng kép',
'doubleredirectstext' => 'Mỗi hàng có chứa các liên kết đến trang chuyển hướng thứ nhất và thứ hai, cũng như dòng đầu tiên của nội dung trang chuyển hướng thứ hai, thường chỉ tới trang đích "thực sự", là nơi mà trang chuyển hướng đầu tiên phải trỏ đến.',

'brokenredirects'        => 'Đổi hướng sai',
'brokenredirectstext'    => 'Các trang đổi hướng sau đây liên kết đến một trang không tồn tại.',
'brokenredirects-edit'   => '(sửa)',
'brokenredirects-delete' => '(xóa)',

'withoutinterwiki'        => 'Trang chưa có liên kết ngoại ngữ',
'withoutinterwiki-header' => 'Các trang sau đây không có liên kết đến các phiên bản ngoại ngữ khác:',

'fewestrevisions' => 'Bài có ít sửa đổi nhất',

# Miscellaneous special pages
'nbytes'                  => '$1 byte',
'ncategories'             => '$1 thể loại',
'nlinks'                  => '$1 liên kết',
'nmembers'                => '$1 thành viên',
'nrevisions'              => '$1 phiên bản',
'nviews'                  => '$1 lượt truy cập',
'specialpage-empty'       => 'Trang này đang trống.',
'lonelypages'             => 'Trang mồ côi',
'lonelypagestext'         => 'Wiki này chưa có trang nào liên kết đến các trang này.',
'uncategorizedpages'      => 'Trang chưa xếp thể loại',
'uncategorizedcategories' => 'Thể loại chưa phân loại',
'uncategorizedimages'     => 'Tập tin chưa được phân loại',
'uncategorizedtemplates'  => 'Tiêu bản chưa được phân loại',
'unusedcategories'        => 'Thể loại chưa dùng',
'unusedimages'            => 'Hình chưa dùng',
'popularpages'            => 'Trang nhiều người đọc',
'wantedcategories'        => 'Thể loại cần thiết',
'wantedpages'             => 'Trang cần viết',
'mostlinked'              => 'Trang được liên kết đến nhiều nhất',
'mostlinkedcategories'    => 'Thể loại có nhiều trang nhất',
'mostlinkedtemplates'     => 'Tiêu bản được liên kết đến nhiều nhất',
'mostcategories'          => 'Các bài có nhiều thể loại nhất',
'mostimages'              => 'Tập tin được liên kết đến nhiều nhất',
'mostrevisions'           => 'Các bài được sửa đổi nhiều lần nhất',
'allpages'                => 'Tất cả các trang',
'prefixindex'             => 'Mục lục theo không gian tên',
'shortpages'              => 'Bài ngắn',
'longpages'               => 'Bài dài',
'deadendpages'            => 'Trang đường cùng',
'deadendpagestext'        => 'Các trang này không có liên kết đến trang khác trong wiki này.',
'protectedpages'          => 'Trang bị khóa',
'protectedpagestext'      => 'Các trang này bị khóa không được sửa đổi hay di chuyển:',
'protectedpagesempty'     => 'Hiện không có trang nào bị khóa với các thông số này.',
'protectedtitles'         => 'Các tựa bài được bảo vệ',
'protectedtitlestext'     => 'Các tựa bài sau đây đã bị khóa không cho tạo mới',
'protectedtitlesempty'    => 'Không có tựa bài nào bị khóa với các tham số như vậy.',
'listusers'               => 'Danh sách thành viên',
'specialpages'            => 'Các trang đặc biệt',
'spheading'               => 'Các trang đặc biệt',
'restrictedpheading'      => 'Trang đặc biệt hạn chế',
'newpages'                => 'Các bài mới nhất',
'newpages-username'       => 'Tên thành viên:',
'ancientpages'            => 'Các bài cũ nhất',
'intl'                    => 'Liên kết liên ngôn ngữ',
'move'                    => 'Di chuyển',
'movethispage'            => 'Đổi tên trang này',
'unusedimagestext'        => '<p>Xin lưu ý là các địa chỉ mạng bên ngoài có thể liên kết đến một hình ở đây qua một địa chỉ trực tiếp, dù hình này được liệt kê là chưa dùng.</p>',
'unusedcategoriestext'    => 'Các trang thể loại này tồn tại, nhưng không có trang hay tiểu thể loại nào nằm dưới nó.',
'notargettitle'           => 'Không hiểu',
'notargettext'            => 'Xin chỉ rõ trang mục tiêu.',
'pager-newer-n'           => '$1 mới hơn',
'pager-older-n'           => '$1 cũ hơn',

# Book sources
'booksources'               => 'Nguồn tham khảo',
'booksources-search-legend' => 'Tìm kiếm nguồn sách',
'booksources-go'            => 'Tìm kiếm',
'booksources-text'          => 'Đây có liên kết đến nhiều tiệm trực tuyến bán sách mới và cũ, cũng như thông tin về sách này:',

'categoriespagetext' => 'Các thể loại :',
'data'               => 'dữ liệu',
'userrights'         => 'Quản lý quyền thành viên',
'groups'             => 'Các nhóm',
'alphaindexline'     => '$1 đến $2',
'version'            => 'Phiên bản',

# Special:Log
'specialloguserlabel'  => 'Thành viên:',
'speciallogtitlelabel' => 'Tên bài:',
'log'                  => 'Nhật trình',
'all-logs-page'        => 'Tất cả các nhật trình',
'log-search-legend'    => 'Tìm kiếm nhật trình',
'log-search-submit'    => 'Tìm kiếm',
'alllogstext'          => 'Xem nhật trình tải lên, xóa, khóa, cấm, quản lý. Có thể xem theo từng loại, theo tên thành viên, hoặc tên trang.',
'logempty'             => 'Nhật trình không có mục nào khớp với từ khóa.',
'log-title-wildcard'   => 'Tìm các tựa bài bắt đầu bằng các chữ này',

# Special:Allpages
'nextpage'          => 'Bài sau ($1)',
'prevpage'          => 'Trang trước ($1)',
'allpagesfrom'      => 'Xem trang từ:',
'allarticles'       => 'Mọi bài',
'allinnamespace'    => 'Mọi trang (không gian $1)',
'allnotinnamespace' => 'Mọi trang (không trong không gian $1)',
'allpagesprev'      => 'Trước',
'allpagesnext'      => 'Sau',
'allpagessubmit'    => 'Hiển thị',
'allpagesprefix'    => 'Hiển thị trang có tiền tố:',
'allpagesbadtitle'  => 'Tựa trang không hợp lệ hay có tiền tố để [[Wikipedia:Liên kết liên wiki|nối đến wiki khác]], hoặc nó có một [[Wikipedia:Tên bài#Hạn chế kĩ thuật|ký tự không hợp lệ]] trong tựa trang.',
'allpages-bad-ns'   => '{{SITENAME}} không có không gian "$1"',

# Special:Listusers
'listusersfrom'      => 'Xem thành viên bắt đầu từ:',
'listusers-submit'   => 'Liệt kê',
'listusers-noresult' => 'Không thấy thành viên.',

# E-mail user
'mailnologin'     => 'Không có địa chỉ gửi thư',
'mailnologintext' => 'Bạn phải [[Đặc_biệt:Userlogin|đăng nhập]] và có khai báo một địa chỉ thư điện tử hợp lệ trong phần [[Đặc_biệt:Preferences|lựa chọn cá nhân]] thì mới gửi được thư cho người khác.',
'emailuser'       => 'Gửi thư cho người này',
'emailpage'       => 'Gửi thư',
'emailpagetext'   => 'Nếu người này đã cung cấp địa chỉ thư điện tử, biểu mẫu dưới đây sẽ cho bạn gửi thư. Địa chỉ thư điện tử của bạn sẽ xuất hiện trong phần địa chỉ người gửi của bức thư, nên người nhận có thể trả lời lại bạn.',
'usermailererror' => 'Lỗi gửi thư:',
'defemailsubject' => 'thư gửi từ {{SITENAME}}',
'noemailtitle'    => 'Không có địa chỉ nhận thư',
'noemailtext'     => 'Người này không cung cấp một địa chỉ thư hợp lệ, hoặc đã chọn không nhận thư từ người khác.',
'emailfrom'       => 'Từ',
'emailto'         => 'Đến',
'emailsubject'    => 'Chủ đề',
'emailmessage'    => 'Nội dung',
'emailsend'       => 'Gửi',
'emailccme'       => 'Gửi cho tôi bản sao của thư này.',
'emailccsubject'  => 'Bản sao của thư gửi cho $1: $2',
'emailsent'       => 'Đã gửi',
'emailsenttext'   => 'Thư của bạn đã được gửi.',

# Watchlist
'watchlist'            => 'Trang tôi theo dõi',
'mywatchlist'          => 'Trang tôi theo dõi',
'watchlistfor'         => "(của '''$1''')",
'nowatchlist'          => 'Chưa có gì.',
'watchlistanontext'    => 'Xin hãy $1 để xem hay sửa đổi các trang được theo dõi.',
'watchnologin'         => 'Chưa đăng nhập',
'watchnologintext'     => 'Bạn phải [[Đặc_biệt:Userlogin|đăng nhập]] mới sửa đổi được danh sách theo dõi.',
'addedwatch'           => 'Đã vào danh sách theo dõi',
'addedwatchtext'       => 'Trang "$1" đã được cho vào [[Đặc_biệt:Watchlist|danh sách theo dõi]].
Những sửa đổi đối với trang này và trang thảo luận của nó sẽ được liệt kê, và được <b>in đậm</b> trong [[Đặc_biệt:Recentchanges|danh sách các thay đổi mới]].
<p>Nếu bạn muốn cho trang này ra khỏi danh sách theo dõi, nhấn vào "Ngừng theo dõi" ở trên.',
'removedwatch'         => 'Đã ra khỏi danh sách theo dõi',
'removedwatchtext'     => 'Trang « $1 » đã ra khỏi danh sách theo dõi.',
'watch'                => 'Theo dõi',
'watchthispage'        => 'Theo dõi trang này',
'unwatch'              => 'Ngừng theo dõi',
'unwatchthispage'      => 'Ngừng theo dõi',
'notanarticle'         => 'Không phải bài viết',
'watchnochange'        => 'Không có trang nào bạn theo dõi được sửa đổi.',
'watchlist-details'    => 'Bạn đang theo dõi $1 trang không kể trang thảo luận.',
'wlheader-enotif'      => '* Đã bật thông báo qua thư điện tử.',
'wlheader-showupdated' => "* Các trang đã thay đổi từ lần cuối bạn xem chúng được in '''đậm'''",
'watchmethod-recent'   => 'Dưới đây hiện thay đổi mới với các trang theo dõi.',
'watchmethod-list'     => 'Dưới đây hiện danh sách các trang theo dõi.',
'watchlistcontains'    => 'Danh sách theo dõi của bạn có $1 trang.',
'iteminvalidname'      => "Tên trang '$1' không hợp lệ...",
'wlnote'               => "Dưới đây là {{PLURAL:$1|sửa đổi cuối cùng|'''$1''' sửa đổi mới nhất}} trong '''$2''' giờ qua.",
'wlshowlast'           => 'Xem $1 giờ $2 ngày qua, hoặc $3',
'watchlist-show-bots'  => 'Xem sửa đổi bot',
'watchlist-hide-bots'  => 'Giấu sửa đổi bot',
'watchlist-show-own'   => 'Xem sửa đổi của tôi',
'watchlist-hide-own'   => 'Giấu sửa đổi của tôi',
'watchlist-show-minor' => 'Xem sửa đổi nhỏ',
'watchlist-hide-minor' => 'Giấu sửa đổi nhỏ',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Đang theo dõi...',
'unwatching' => 'Đang ngừng theo dõi...',

'enotif_mailer'                => 'Thông báo của {{SITENAME}}',
'enotif_reset'                 => 'Đánh dấu đã xem mọi trang',
'enotif_newpagetext'           => 'Trang này mới',
'enotif_impersonal_salutation' => 'thành viên {{SITENAME}}',
'changed'                      => 'Đã sửa',
'created'                      => 'đã viết mới',
'enotif_subject'               => '$PAGETITLE tại {{SITENAME}} đã thay đổi $CHANGEDORCREATED bởi $PAGEEDITOR',
'enotif_lastvisited'           => 'Xem $1 để biết các thay đổi diễn ra từ lần xem cuối cùng của bạn.',
'enotif_lastdiff'              => 'Vào $1 để xem sự thay đổi này.',
'enotif_anon_editor'           => 'Người sửa đổi vô danh $1',
'enotif_body'                  => 'Gửi $WATCHINGUSERNAME,  trang $PAGETITLE tại {{SITENAME}} đã được $CHANGEDORCREATED vào $PAGEEDITDATE bởi $PAGEEDITOR, xem {{fullurl:$PAGETITLE_RAWURL}} để biết phiên bản hiện nay. Tóm tắt của $NEWPAGE: $PAGESUMMARY $PAGEMINOREDIT Liên hệ người sửa: thư {{fullurl:Special:Emailuser|target=$PAGEEDITOR_RAWURL}}  {{fullurl:User:$PAGEEDITOR_RAWURL}} Sẽ không có thông báo mới nếu bạn không xem trang này. Bạn có thể thay đổi các cài đặt về các trang theo dõi. Hệ thống thông báo {{SITENAME}} -- Để thay đổi cài đặt, mời vào {{fullurl:Special:Watchlist|edit=yes}} Góp ý của bạn: {{fullurl:Help:Contents}}',

# Delete/protect/revert
'deletepage'                  => 'Xóa trang',
'confirm'                     => 'Khẳng định',
'excontent'                   => "nội dung cũ là: '$1'",
'excontentauthor'             => 'nội dung cũ: "$1" (người viết duy nhất "$2")',
'exbeforeblank'               => "nội dung trước khi tẩy trống là: '$1'",
'exblank'                     => 'trang rỗng',
'confirmdelete'               => 'Khẳng định xóa',
'deletesub'                   => '(Xóa  "$1")',
'historywarning'              => '<b>Chú ý</b>: trang bạn sắp xóa đã có lịch sử:',
'confirmdeletetext'           => 'Bạn sắp xóa hẳn một trang hoặc hình cùng với tất cả lịch sử của nó khỏi cơ sở dữ liệu. Xin khẳng định bạn hiểu rõ hậu quả có thể xảy ra, và bạn thực hiện đúng [[{{MediaWiki:Policy-url}}|quy định]].',
'actioncomplete'              => 'Xong',
'deletedtext'                 => '"$1" đã được xóa. Xem danh sách các xóa bỏ gần nhất tại $2.',
'deletedarticle'              => 'đã xóa "$1"',
'dellogpage'                  => 'Nhật trình xóa',
'dellogpagetext'              => 'Danh sách xóa mới, theo giờ máy chủ (UTC).',
'deletionlog'                 => 'nhật trình xóa',
'reverted'                    => 'Đã quay lại phiên bản cũ',
'deletecomment'               => 'Lý do',
'deleteotherreason'           => 'Lý do khác/bổ sung:',
'deletereasonotherlist'       => 'Lý do khác',
'deletereason-dropdown'       => '*Xóa nhanh
** [[WP:XT#XN11|XT XN1.1]]: Viết linh tinh, phá hoại
** [[WP:XT#XN12|XT XN1.2]]: Tác giả duy nhất yêu cầu xóa hay tẩy trống
** [[WP:XT#XN13|XT XN1.3]]: Nội dung tục tĩu
** [[WP:XT#XN14|XT XN1.4]]: Quảng cáo ngắn
** [[WP:XT#XN15|XT XN1.5]]: Nội dung quá ngắn (ít hơn 10 chữ)
** [[WP:XT#XN2|XT XN2]]: Nội dung tiếng Việt ít hơn 10 chữ
** [[WP:XT#X3|XT X3]]: Tên bài viết sai chính tả, đã di chuyển
*Hết hạn tiêu bản
** [[WP:XT#TB1|XT TB1]]: Chất lượng kém quá 7 ngày
** [[WP:XT#TB2|XT TB2]]: Vi phạm bản quyền quá 7 ngày
** [[WP:XT#TB3|XT TB3]]: Đơn thuần là quảng cáo quá 1 ngày
** [[WP:XT#TB4|XT TB4]]: Văn phong không thích hợp quá 7 ngày
** [[WP:XT#TB5|XT TB5]]: Bài chưa được Unicode hóa quá 7 ngày
*Tiêu chuẩn đưa vào
** [[WP:XT#X2|XT X2]]: Về cá nhân nhưng không nêu rõ điểm nổi bật
** [[WP:XT#X2|XT X2]]: Về tổ chức hay công ty nhưng không nêu rõ điểm nổi bật
** [[WP:XT#X2|XT X2]]: Địa danh Việt Nam dưới cấp xã nhưng không nêu rõ điểm nổi bật
*Theo biểu quyết
** [[WP:XT#X6|XT X6]]: Xóa theo biểu quyết: không bách khoa
** [[WP:XT#X6|XT X6]]: Xóa theo biểu quyết: không đủ tiêu chuẩn
*Thường dùng cho trang thảo luận
** Là trang thảo luận của bài viết không tồn tại
*Khác
** Thể loại trống
** Trang thành viên không tồn tại
** Chuyển hướng đến trang không tồn tại',
'delete-toobig'               => 'Trang này có lịch sử sửa đổi lớn, đến hơn $1 lần sửa đổi. Việc xóa các trang như vậy bị hạn chế để ngăn ngừa sự phá hoại vô ý cho {{SITENAME}}.',
'delete-warning-toobig'       => 'Trang này có lịch sử sửa đổi lớn, đến hơn $1 lần sửa đổi. Việc xóa các trang có thể làm tổn hại đến hoạt động của cơ sở dữ liệu của {{SITENAME}}; hãy cẩn trọng khi thực hiện.',
'rollback'                    => 'Quay lại sửa đổi cũ',
'rollback_short'              => 'Quay lại',
'rollbacklink'                => 'quay lại',
'rollbackfailed'              => 'Không quay lại được',
'cantrollback'                => 'Không quay lại được; trang này có 1 tác giả.',
'alreadyrolled'               => 'Không thể quay lại phiên bản của [[:$1]] bởi [[Thành_viên:$2|$2]] ([[Thảo_luận_thành_viên:$2|Thảo luận]]). Đã có sửa đổi lần cuối bởi [[Thành_viên:$3|$3]] ([[Thảo_luận_thành_viên:$3|Thảo luận]]).',
'editcomment'                 => 'Tóm lược sửa đổi: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'đã hủy sửa đổi của $2, quay về phiên bản của $1',
'rollback-success'            => 'Lùi các sửa đổi của $1; quay về phiên bản trước của $2.',
'sessionfailure'              => 'Có thể có trục trặc với phiên đăng nhập của bạn; thao tác này đã bị hủy để tránh việc cướp quyền đăng nhập. Xin hãy tải lại trang và thử lại.',
'protectlogpage'              => 'Nhật trình khóa',
'protectlogtext'              => 'Danh sách khóa/mở (xem [[{{ns:project}}:Các trang bị khóa|các trang bị khóa]]).',
'protectedarticle'            => 'đã khóa $1',
'modifiedarticleprotection'   => 'đã đổi mức khóa cho "[[$1]]"',
'unprotectedarticle'          => 'đã mở $1',
'protectsub'                  => '(Khóa "$1")',
'confirmprotect'              => 'Khẳng định khóa',
'protectcomment'              => 'Lý do',
'protectexpiry'               => 'Thời hạn',
'protect_expiry_invalid'      => 'Thời hạn không hợp lệ.',
'protect_expiry_old'          => 'Thời hạn đã qua.',
'unprotectsub'                => '(Mở "$1")',
'protect-unchain'             => 'Vẫn cho đổi tên bài',
'protect-text'                => "<div name=\"protect_text\" id=\"protect_text\">Bạn có thể xem và đổi kiểu khóa trang '''<nowiki>\$1</nowiki>''' ở đây. Xin hãy tuân theo [[Wikipedia:Quy định khóa trang|quy định khóa trang]].

Mức độ khóa mặc định là khóa vô thời hạn. Để xác định ngày hết hạn, nhập vào khoảng thời gian cấm (theo [http://www.gnu.org/software/tar/manual/html_node/Date-input-formats.html định dạng chuẩn GNU]) hoặc ngày hết hạn cấm.
*Tránh các giá trị không phải số nguyên, như  \"2.37 weeks\" hay \"1.84 days\"; phần mềm MediaWiki có thể mắc lỗi khi định dạng các số này.
*''Toàn bộ'' khóa sẽ mở khi hết hạn, bất kể mức độ khóa là gì.
**Hãy cẩn trọng với ''trang cấm di chuyển vĩnh viễn'', như ''Tin nhắn cho quản lý''.
</div>",
'protect-locked-blocked'      => 'Bạn không thể đổi mức khóa khi bị cấm. Đây là trạng thái
hiện tại của trang <strong>$1</strong>:',
'protect-locked-dblock'       => 'Hiện không thể đổi mức khóa do cơ sở dữ liệu bị khóa.
Đây là trạng thái hiện tại của trang <strong>$1</strong>:',
'protect-locked-access'       => 'Tài khoản của bạn không được cấp quyền đổi mức khóa của trang.
Đây là trạng thái hiện tại của trang <strong>$1</strong>:',
'protect-cascadeon'           => '[[Hình:Padlock.svg|phải|60px]]
<span style="color: red; background-color: transparent;" id="protectedpagewarning"><strong>Trang này hiện bị khóa vì nó được nhúng vào {{PLURAL:$1|những|}} trang dưới đây bị khóa với tùy chọn "khóa theo tầng" được kích hoạt.</strong></span> Bạn có thể đổi mức độ khóa của trang này, nhưng nó sẽ không ảnh hưởng đến việc khóa theo tầng.',
'protect-default'             => 'Để tất cả mọi người sửa đổi',
'protect-fallback'            => 'Cần quyền “$1”',
'protect-level-autoconfirmed' => 'Cấm IP và thành viên mới',
'protect-level-sysop'         => 'Cấm mọi thành viên (trừ quản lý)',
'protect-summary-cascade'     => 'khóa theo tầng',
'protect-expiring'            => 'hết hạn $1',
'protect-cascade'             => 'Khóa theo tầng: tự động khóa các trang được nhúng vào trang ngày',
'protect-cantedit'            => 'Bạn không thể thay đổi mức khóa cho trang này do thiếu quyền.',
'restriction-type'            => 'Quyền:',
'restriction-level'           => 'Mức độ hạn chế',
'minimum-size'                => 'Kích thước tối thiểu (byte)',
'maximum-size'                => 'Kích thước lớn nhất',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Sửa đổi',
'restriction-move'   => 'Di chuyển',
'restriction-create' => 'Tạo mới',

# Restriction levels
'restriction-level-sysop'         => 'khóa hẳn',
'restriction-level-autoconfirmed' => 'hạn chế sửa đổi',
'restriction-level-all'           => 'mọi mức độ',

# Undelete
'undelete'                     => 'Phục hồi lại trang đã bị xóa',
'undeletepage'                 => 'Xem và khôi phục trang bị xóa',
'viewdeletedpage'              => 'Xem các trang bị xóa',
'undeletepagetext'             => 'Các trang sau có thể khôi phục được từ thùng rác. Thùng rác được xóa định kỳ.',
'undeleteextrahelp'            => "Để phục hồi toàn bộ trang và lịch sử của nó, hãy để trống các hộp kiểm và bấm nút '''''Phục hồi'''''. Để thực hiện phục hồi có chọn lọc, hãy đánh dấu vào hộp kiểm của các phiên bản muốn phục hồi và bấm nút '''''Phục hồi'''''. Đánh dấu vào một hộp kiểm, sau đó vừa bấm phím Shift vừa chọn một hộp kiểm khác sẽ đánh dấu tất cả các hộp ở giữa, điều này hiện thực được ở nhiều trình duyệt. Bấm nút '''''Tẩy trống''''' sẽ tẩy trống ô lý do và tất cả các hộp kiểm. Hãy chắc chắn rằng bạn đang thực hiện theo đúng [[Wikipedia:Quy định phục hồi|quy định phục hồi]] và đã để lại một câu tóm tắt trong ô lý do.",
'undeleterevisions'            => '$1 bản đã được lưu',
'undeletehistory'              => 'Nếu bạn khôi phục trang này, tất cả các phiên bản của nó sẽ được phục hồi vào lịch sử của trang. Nếu một trang mới có cùng tên đã được tạo ra kể từ khi xóa trang này, các phiên bản được khôi phục sẽ xuất hiện trong lịch sử trước, và phiên bản hiện hành của trang mới sẽ không bị thay thế.',
'undeleterevdel'               => 'Việc phục hồi sẽ không được thực hiện nếu nó dẫn đến việc phiên bản trên cùng bị xóa mất một phần. Trong những trường hợp như vậy, bạn phải bỏ đánh dấu hộp kiểm hoặc bỏ ẩn những phiên bản bị xóa mới nhất. Các phiên bản của tập tin mà bạn không có quyền xem sẽ không được phục hồi.',
'undeletehistorynoadmin'       => 'Trang này đã bị [[Wikipedia:Xóa|xóa]]. Lý do xóa trang được hiển thị dưới đây, cùng với thông tin về các người đã sửa đổi trang này trước khi bị xóa. Chỉ có [[Wikipedia:Người quản lý|người quản lý]] không bị cấm tài khoản mới xem được văn bản đầy đủ của những phiên bản trang bị xóa.',
'undelete-revision'            => 'Phiên bản của $1 đã được $3 xóa (vào lúc $2):',
'undeleterevision-missing'     => 'Phiên bản này không hợp lệ hay không tồn tại. Đây có thể là một địa chỉ sai, hoặc là phiên bản có thể được phục hồi và dời khỏi lưu trữ.',
'undelete-nodiff'              => 'Không tìm thấy phiên bản cũ hơn.',
'undeletebtn'                  => 'Khôi phục',
'undeletereset'                => 'Tẩy trống',
'undeletecomment'              => 'Lý do:',
'undeletedarticle'             => 'đã khôi phục "$1"',
'undeletedrevisions'           => '$1 bản được phục hồi',
'undeletedrevisions-files'     => '$1 bản và $2 tập tin đã được phục hồi',
'undeletedfiles'               => '$1 tập tin đã được phục hồi',
'cannotundelete'               => '<div class="plainlinks">
<p>Không thể khôi phục trang hoặc tập tin chỉ định. (Có thể là nó đã <a href="{{fullurle:Đặc biệt:Log/delete}}" title="Đặc biệt:Log/delete">bị một người khác khôi phục</a>.)</p>
<p>Trở lại về:</p>
<ul>
<li><a href="{{fullurle:Wikipedia:Biểu quyết phục hồi bài}}" title="Wikipedia:Biểu quyết phục hồi bài">Biểu quyết phục hồi bài</a></li>
<li><a href="{{fullurle:Đặc biệt:Recentchanges}}" title="Đặc biệt:Recentchanges">Thay đổi gần đây</a> (<a href="{{fullurl:Đặc biệt:Recentchanges|hideliu=1&hideminor=1}}" title="Đặc biệt:Recentchanges">các sửa đổi vô danh</a>)</li>
</ul>
</div>',
'undeletedpage'                => '__NOEDITSECTION__
===$1 đã được khôi phục.===

Xem danh sách các xóa bỏ và khôi phục tại [[Đặc biệt:Log/delete|nhật trình xóa]].',
'undelete-header'              => 'Xem các trang bị xóa gần đây tại [[Đặc biệt:Log/delete|nhật trình xóa]].',
'undelete-search-box'          => 'Tìm kiếm về trang bị xóa',
'undelete-search-prefix'       => 'Hiển thị trang có tiền tố:',
'undelete-search-submit'       => 'Tìm kiếm',
'undelete-no-results'          => 'Không tìm thấy trang đã bị xóa nào khớp với từ khóa.',
'undelete-filename-mismatch'   => 'Không thể phục hồi phiên bản tập tin vào thời điểm $1: không có tập tin trùng tên',
'undelete-bad-store-key'       => 'Không thể phục hồi phiên bản tập tin tại thời điểm $1: tập tin không tồn tại trước khi xóa.',
'undelete-cleanup-error'       => 'Có lỗi khi xóa các tập tin lưu trữ "$1" không được sử dụng.',
'undelete-missing-filearchive' => 'Không thể phục hồi bộ tập tin có định danh $1 vì nó không nằm ở cơ sở dữ liệu. Có thể nó được phục hồi rồi.',
'undelete-error-short'         => 'Có lỗi khi phục hồi tập tin: $1',
'undelete-error-long'          => 'Xuất hiện lỗi khi phục hồi tập tin:

$1',

# Namespace form on various pages
'namespace'      => 'Không gian:',
'invert'         => 'Đảo ngược lựa chọn',
'blanknamespace' => '(Chính)',

# Contributions
'contributions' => 'Đóng góp',
'mycontris'     => 'Đóng góp của tôi',
'contribsub2'   => 'Của $1 ($2)',
'nocontribs'    => 'Không tìm thấy.',
'ucnote'        => '<b>$1</b> thay đổi mới của người này trong <b>$2</b> ngày qua.',
'uclinks'       => 'Xem $1 thay đổi mới; xem $2 ngày qua.',
'uctop'         => '(mới nhất)',
'month'         => 'Từ tháng (trở về trước):',
'year'          => 'Từ năm (trở về trước):',

'sp-contributions-newbies'     => 'Chỉ hiển thị đóng góp của tài khoản mới',
'sp-contributions-newbies-sub' => 'Các người mới đến',
'sp-contributions-blocklog'    => 'Nhật trình cấm',
'sp-contributions-search'      => 'Tìm kiếm đóng góp',
'sp-contributions-username'    => 'Địa chỉ IP hay tên thành viên:',
'sp-contributions-submit'      => 'Tìm',

'sp-newimages-showfrom' => 'Trưng bày các tập tin mới, bắt đầu từ $1',

# What links here
'whatlinkshere'        => 'Các liên kết đến đây',
'whatlinkshere-title'  => 'Các trang liên kết tới $1',
'whatlinkshere-page'   => 'Trang:',
'whatlinkshere-barrow' => '←',
'linklistsub'          => '(Các liên kết)',
'linkshere'            => "Các trang sau liên kết đến '''[[:$1]]''':",
'nolinkshere'          => "Không có liên kết đến '''[[:$1]]'''.",
'nolinkshere-ns'       => "Chưa có trang nào liên kết tới '''[[:$1]]''' trong không gian tên đã chọn.",
'isredirect'           => 'trang đổi hướng',
'istemplate'           => 'được nhúng vào',
'whatlinkshere-prev'   => '{{PLURAL:$1|kết quả trước|$1 kết quả trước}}',
'whatlinkshere-next'   => '{{PLURAL:$1|kết quả sau|$1 kết quả sau}}',
'whatlinkshere-links'  => '← liên kết',

# Block/unblock
'blockip'                     => 'Cấm thành viên',
'blockiptext'                 => 'Mẫu dưới để cấm một địa chỉ IP hoặc một tài khoản.
Chức năng này chỉ nên dùng để ngăn những hành vi phá hoại, và phải tuân theo [[{{ns:project}}:Quy_định|quy_định]]. Xin cho biết lý do cấm.',
'ipaddress'                   => 'Địa chỉ IP/tên tài khoản',
'ipadressorusername'          => 'Địa chỉ IP hay tên thành viên',
'ipbexpiry'                   => 'Thời hạn',
'ipbreason'                   => 'Lý do',
'ipbreasonotherlist'          => 'Lý do khác',
'ipbreason-dropdown'          => '*Một số lý do cấm thường gặp
** Phá hoại
** Thêm thông tin sai lệch
** Xóa nội dung trang
** Gửi liên kết spam đến trang web bên ngoài
** Cho thông tin rác vào trang
** Có thái độ dọa dẫm/quấy rối
** Dùng nhiều tài khoản
** Tên thành viên không được chấp nhận
** Tạo nhiều bài mới vi phạm bản quyền, bỏ qua thảo luận và cảnh báo
** Truyền nhiều hình ảnh thiếu nguồn gốc hoặc bản quyền
** Con rối của thành viên bị cấm',
'ipbanononly'                 => 'Chỉ cấm thành viên vô danh',
'ipbcreateaccount'            => 'Cấm mở tài khoản',
'ipbemailban'                 => 'Không cho gửi email',
'ipbenableautoblock'          => 'Tự động cấm các địa chỉ IP mà thành viên này sử dụng',
'ipbsubmit'                   => 'Cấm',
'ipbother'                    => 'Thời hạn khác',
'ipboptions'                  => '2 giờ:2 hours,1 ngày:1 day,3 ngày:3 days,1 tuần:1 week,2 tuần:2 weeks,1 tháng:1 month,3 tháng:3 months,6 tháng:6 months,1 năm:1 year,vô hạn:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'khác',
'ipbotherreason'              => 'Lý do khác',
'badipaddress'                => 'Địa chỉ IP không hợp lệ',
'blockipsuccesssub'           => 'Đã cấm',
'blockipsuccesstext'          => '"$1" đã bị cấm.
<br />Xem lại những lần cấm tại [[Đặc_biệt:Ipblocklist|danh sách cấm]].',
'ipb-edit-dropdown'           => 'Sửa đổi lý do cấm',
'ipb-unblock-addr'            => 'Bỏ cấm $1',
'ipb-unblock'                 => 'Bỏ cấm thành viên hay địa chỉ IP',
'ipb-blocklist-addr'          => 'Xem $1 đang bị cấm hay không',
'ipb-blocklist'               => 'Xem các người đang bị cấm',
'unblockip'                   => 'Bỏ cấm',
'unblockiptext'               => 'Mẫu sau để khôi phục lại quyền sửa bài đối với một địa chỉ IP hoặc tài khoản đã bị cấm trước đó.',
'ipusubmit'                   => 'Bỏ cấm',
'unblocked'                   => '[[Thành viên:$1|$1]] đã hết bị cấm',
'unblocked-id'                => '$1 đã hết bị cấm',
'ipblocklist'                 => 'Danh sách cấm',
'ipblocklist-legend'          => 'Tra một thành viên bị cấm',
'ipblocklist-username'        => 'Tên thành viên hoặc địa chỉ IP:',
'ipblocklist-summary'         => 'Xem [http://tools.wikimedia.de/~krimpet/rbhelper.php?db=viwiki_p công cụ này] để tìm tất cả các nhật trình khóa dải IP đối với một IP cụ thể nào đó.',
'ipblocklist-submit'          => 'Tìm',
'blocklistline'               => '$1, $2 đã cấm $3 (thời hạn $4)',
'infiniteblock'               => 'vô tận',
'expiringblock'               => 'lúc $1',
'anononlyblock'               => 'chỉ cấm vô danh',
'noautoblockblock'            => 'chức năng tự động cấm bị tắt',
'createaccountblock'          => 'không được mở tài khoản',
'emailblock'                  => 'thư điện tử đã bị cấm',
'ipblocklist-empty'           => 'Danh sách cấm hiện đang trống.',
'ipblocklist-no-results'      => 'Địa chỉ IP hoặc tên thành viên này hiện không bị cấm.',
'blocklink'                   => 'cấm',
'unblocklink'                 => 'bỏ cấm',
'contribslink'                => 'đóng góp',
'autoblocker'                 => 'Bị tự động cấm vì dùng chung địa chỉ IP với "$1". Lý do "$2".',
'blocklogpage'                => 'Nhật trình cấm',
'blocklogentry'               => 'đã cấm [[$1]] với thời hạn của $2 $3',
'blocklogtext'                => 'Nhật trình lưu những lần cấm và bỏ cấm. Các địa chỉ IP bị cấm tự động không được liệt kê. Xem thêm
[[Đặc_biệt:Ipblocklist|danh sách cấm]].',
'unblocklogentry'             => 'đã hết cấm "$1"',
'block-log-flags-anononly'    => 'chỉ cấm những người chưa đăng nhập',
'block-log-flags-nocreate'    => 'cấm mở tài khoản',
'block-log-flags-noautoblock' => 'tự động cấm không kích hoạt',
'block-log-flags-noemail'     => 'thư điện tử bị khóa',
'range_block_disabled'        => 'Không được cấm hàng loạt.',
'ipb_expiry_invalid'          => 'Thời điểm hết hạn không hợp lệ.',
'ipb_already_blocked'         => '<div class="alreadyblocked" id="alreadyblocked" style="color:red; font-weight:bold; font-size:96.75%;"><a href="http://vi.wikipedia.org/wiki/Th%C3%A0nh_vi%C3%AAn:$1">$1</a> (<a href="http://vi.wikipedia.org/wiki/Th%E1%BA%A3o_lu%E1%BA%ADn_Th%C3%A0nh_vi%C3%AAn:$1">thảo luận</a> • <a href="http://vi.wikipedia.org/wiki/%C4%90%E1%BA%B7c_bi%E1%BB%87t:Contributions/$1">đóng góp</a>) hiện đang bị cấm (<a href="http://vi.wikipedia.org/w/index.php?title=%C4%90%E1%BA%B7c_bi%E1%BB%87t:Log/block&page=Th%C3%A0nh+vi%C3%AAn:$1">nhật trình cấm</a> • <a href="http://vi.wikipedia.org/w/index.php?title=%C4%90%E1%BA%B7c_bi%E1%BB%87t:Ipblocklist&action=unblock&ip=$1&wpUnblockReason=m%E1%BB%9F+kh%C3%B3a+%C4%91%E1%BB%83+%C4%91%E1%BB%95i+th%E1%BB%9Di+h%E1%BA%A1n">mở khóa</a>) . Để thay đổi thời hạn cấm và/hoặc lý do cấm, trước hết bạn phải bỏ cấm cho thành viên.</div>',
'ipb_cant_unblock'            => 'Lỗi: Không tìm được ID cấm $1. Địa chỉ IP này có thể bị cấm rồi.',
'ip_range_invalid'            => 'Dải IP không hợp lệ.',
'blockme'                     => 'Cấm tôi',
'proxyblocker'                => 'Chặn proxy',
'proxyblocker-disabled'       => 'Chức năng này hiện không bật.',
'proxyblockreason'            => 'Địa chỉ IP của bạn đã bị cấm vì là proxy mở. Xin hãy liên hệ nhà cung cấp dịch vụ Internet hoặc bộ phận hỗ trợ kỹ thuật của bạn và thông báo với họ về vấn đề an ninh nghiêm trọng này.',
'proxyblocksuccess'           => 'Xong.',
'sorbsreason'                 => 'Địa chỉ IP của bạn bị liệt kê là một proxy mở theo DNSBL.',
'sorbs_create_account_reason' => 'Địa chỉ IP của bạn bị liệt kê là một proxy mở theo DNSBL. Bạn không thể mở được tài khoản.',

# Developer tools
'lockdb'              => 'Khóa cơ sở dữ liệu',
'unlockdb'            => 'Mở cơ sở dữ liệu',
'lockdbtext'          => 'Khóa cơ sở dữ liệu sẽ không cho phép người dùng sửa đổi các trang, thay đổi thông số cá nhân của họ, sửa danh sách theo dõi, và những thao tác khác đòi hỏi phải thay đổi trong cơ sở dữ liệu.
Xin hãy khẳng định là bạn có ý định thực hiện điều này, và bạn sẽ mở khóa cơ sở dữ liệu khi xong công việc bảo trì của bạn.',
'unlockdbtext'        => 'Mở khóa cơ sở dữ liệu sẽ lại cho phép các người dùng có thể sửa đổi trang, thay đổi thông số cá nhân của họ, sửa đổi danh sách theo dõi của họ, và nhiều thao tác khác đòi hỏi phải có thay đổi trong cơ sở dữ liệu.
Xin hãy khẳng định đây là điều bạn định làm.',
'lockconfirm'         => 'Vâng, tôi thực sự muốn khóa cơ sở dữ liệu.',
'unlockconfirm'       => 'Vâng, tôi thực sự muốn mở cơ sở dữ liệu.',
'lockbtn'             => 'Khóa cơ sở dữ liệu',
'unlockbtn'           => 'Mở cơ sở dữ liệu',
'locknoconfirm'       => 'Bạn đã không chọn vào ô khẳng định.',
'lockdbsuccesssub'    => 'Khóa thành công cơ sở dữ liệu',
'unlockdbsuccesssub'  => 'Mở thành công cơ sở dữ liệu',
'lockdbsuccesstext'   => 'Cơ sở dữ liệu đã bị khóa.
<br />Nhớ bỏ khóa sau khi bảo trì xong.',
'unlockdbsuccesstext' => 'Cơ sở dữ liệu đã được mở khóa.',
'lockfilenotwritable' => 'Không có thể ghi vào tập tin khóa cơ sở dữ liệu. Để khóa hay mở khóa cơ sở dữ liệu, cần để máy phục vụ ghi vào tập tin này.',
'databasenotlocked'   => 'Cơ sở dữ liệu không bị khóa.',

# Move page
'movepage'                => 'Di chuyển',
'movepagetext'            => 'Dùng mẫu dưới đây sẽ đổi tên một trang, đồng thời chuyển tất cả lịch sử của nó sang tên mới.
*Tên cũ sẽ tự động đổi hướng sang tên mới.
*Trang sẽ <b>không</b> bị chuyển nếu đã có một trang tại tên mới, trừ khi nó rỗng hoặc là trang đổi hướng và không có lịch sử sửa đổi. Điều này có nghĩa là bạn có thể đổi tên một trang lại như trước lúc nó được đổi tên nếu bạn nhầm, và bạn không thể ghi đè một trang đã có sẵn.
*Những liên kết đến tên trang cũ sẽ không thay đổi; cần [[Đặc_biệt:DoubleRedirects|kiểm tra]] những trang chuyển hướng kép và sai.<br />
<b>Bạn phải đảm bảo những liên kết tiếp tục trỏ đến đúng trang cần thiết.</b>',
'movepagetalktext'        => "Trang thảo luận đi kèm nếu có, sẽ được tự động chuyển theo '''trừ khi:'''
*Bạn đang chuyển xuyên qua không gian tên,
*Một trang thảo luận đã tồn tại dưới tên bạn chọn, hoặc
*Bạn không chọn vào ô bên dưới.

Trong những trường hợp này, bạn phải di chuyển hoặc hợp nhất trang theo kiểu thủ công nếu muốn.",
'movearticle'             => 'Di chuyển',
'movenologin'             => 'Chưa đăng nhập',
'movenologintext'         => 'Bạn phải [[Đặc_biệt:Userlogin|đăng nhập]] mới di chuyển trang được.',
'movenotallowed'          => 'Bạn không có quyền di chuyển trang trong wiki này.',
'newtitle'                => 'Tên mới',
'move-watch'              => 'Theo dõi trang này',
'movepagebtn'             => 'Di chuyển',
'pagemovedsub'            => 'Di chuyển thành công',
'movepage-moved'          => '<span class="plainlinks">Trang "$1" {{MediaWiki liên kết di chuyển nguồn}} đã được di chuyển đến "$2" {{MediaWiki liên kết di chuyển đích}}.</span>

\'\'\'Xin hãy {{MediaWiki kiểm tra di chuyển}}\'\'\' xem việc di chuyển này có tạo ra [[Wikipedia:Đổi hướng kép|đổi hướng kép]] hay không, và sửa lại chúng. Bạn có thể sửa bằng đoạn ký tự:\'\'\'

<center><span style="font-family:monospace"><nowiki>#REDIRECT [[$4]]</nowiki></span></center>

Nếu trang này có [[Wikipedia:Nội dung không tự do|hình không tự do]], đừng quên cập nhật [[Wikipedia:Hướng dẫn mô tả sử dụng hợp lý|mô tả sử dụng hợp lý]] để nó liên kết đến tựa bài mới.
<span id="specialDeleteTarget" style="display:none;"><nowiki>$3</nowiki></span>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Đã có một trang với tên đó, hoặc tên bạn chọn không hợp lệ.
Xin hãy chọn tên khác.',
'talkexists'              => 'Trang được di chuyển thành công, nhưng trang thảo luận tương ứng không thể chuyển được vì đã có một trang thảo luận ở tên mới.
Xin hãy hợp nhất chúng lại.',
'movedto'                 => 'đổi thành',
'movetalk'                => 'Di chuyển trang "thảo luận" nếu có.',
'talkpagemoved'           => 'Trang thảo luận tương ứng đã chuyển.',
'talkpagenotmoved'        => 'Trang thảo luận tương ứng <strong>không</strong> chuyển.',
'1movedto2'               => '[[$1]] đổi thành [[$2]]',
'1movedto2_redir'         => '$1 đổi thành $2 qua đổi hướng',
'movelogpage'             => 'Nhật trình đổi tên',
'movelogpagetext'         => 'Các trang bị đổi tên.',
'movereason'              => 'Lý do',
'revertmove'              => 'lùi lại',
'delete_and_move'         => 'Xóa và đổi tên',
'delete_and_move_text'    => ' ==Cần xóa==
Bài với tên "[[$1]]" đã tồn tại. Bạn có muốn xóa nó để di chuyển tới tên này không?',
'delete_and_move_confirm' => 'Xóa trang để đổi tên',
'delete_and_move_reason'  => 'Xóa để có chỗ đổi tên',
'selfmove'                => 'Tên mới giống tên cũ; không đổi tên được.',
'immobile_namespace'      => 'Tên mới đặc biệt; không đổi sang tên đó được.',

# Export
'export'            => 'Xuất các trang',
'exporttext'        => 'Bạn có thể xuất nội dung và lịch sử sửa đổi của một trang hay tập hợp trang nào đó vào trong các XML. Trong tương lai, cũng có thể nhập vào một mạng khác chạy phần mềm MediaWiki.

Để xuất nội dung các bài, gõ vào tên bài trong cửa sổ dưới đây, mỗi tên một hàng, và cho biết là bạn muốn chọn phiên bản hiện tại cùng với các phiên bản cũ của nó, với các dòng về lịch sử trang, hay chỉ phiên bản hiện hành với thông tin về lần sửa đổi cuối cùng.',
'exportcuronly'     => 'Chỉ xuất phiên bản hiện hành, không xuất tất cả lịch sử trang',
'exportnohistory'   => "----
'''Tắt:''' Chức năng xuất lịch sử trang đầy đủ bị tắt tạm thời, do vấn đề hiệu suất. Trong lúc ấy, bạn vẫn có thể lấy lịch sử trang đầy đủ từ những [http://download.wikimedia.org/ dữ liệu nguyên] của nhóm quản lý máy móc Wikimedia.",
'export-submit'     => 'Xuất',
'export-addcattext' => 'Thêm bài từ thể loại:',
'export-addcat'     => 'Thêm',

# Namespace 8 related
'allmessages'               => 'Thông báo hệ thống',
'allmessagesname'           => 'Tên thông báo',
'allmessagesdefault'        => 'Nội dung mặc định',
'allmessagescurrent'        => 'Nội dung hiện thời',
'allmessagestext'           => 'Đây là toàn bộ thông báo hệ thống có trong không gian tên MediaWiki: .',
'allmessagesnotsupportedDB' => '{{ns:special}}:AllMessages không được hỗ trợ vì wgUseDatabaseMessages bị tắt.',
'allmessagesfilter'         => 'Bộ lọc dùng biểu thức chính quy (regular expression):',
'allmessagesmodified'       => 'Chỉ hiển thị các thông báo đã được sửa đổi.',

# Thumbnails
'thumbnail-more'           => 'Phóng lớn',
'missingimage'             => '<b>Không có hình</b><br /><i>$1</i>',
'filemissing'              => 'Không có tệp tin',
'thumbnail_error'          => 'Hình thu nhỏ có lỗi: $1',
'thumbnail_invalid_params' => 'Tham số hình thu nhỏ không hợp lệ',

# Special:Import
'import'                     => 'Nhập các trang',
'importinterwiki'            => 'Nhập giữa các wiki',
'import-interwiki-text'      => 'Chọn tên trang và wiki để nhập trang này vào. Các lúc giờ sửa đổi và tên của người sửa đổi sẽ còn nguyên. Các trang được nhập vào đây từ wiki khác sẽ được liệt kê ở [[Đăc biệt:Log/import|nhật trình nhập trang]].',
'import-interwiki-history'   => 'Sao chép các phiên bản cũ của trang này',
'import-interwiki-submit'    => 'Nhập các trang',
'import-interwiki-namespace' => 'Di chuyển các trang vào không gian tên:',
'importtext'                 => 'Xin hãy xuất tập tin từ wiki nguồn sử dụng công cụ Đặc_biệt:Export, lưu nó vào đĩa và tải lên ở đây.',
'importstart'                => 'Đang nhập các trang…',
'import-revision-count'      => '$1 phiên bản',
'importnopages'              => 'Không có trang để nhập vào.',
'importfailed'               => 'Không nhập được: $1',
'importunknownsource'        => 'Không hiểu nguồn trang để nhập vào',
'importcantopen'             => 'Không có thể mở tập tin để nhập vào',
'importbadinterwiki'         => 'Liên kết đến wiki sai',
'importnotext'               => 'Trang trống không có nội dung',
'importsuccess'              => 'Nhập thành công!',
'importhistoryconflict'      => 'Có mâu thuẫn trong lịch sử của các phiên bản (trang này có thể đã được nhập vào trước đó)',
'importnosources'            => 'Không có nguồn nhập giữa wiki và việc nhập lịch sử bị tắt.',
'importnofile'               => 'Không tải được tập tin nào lên.',

# Import log
'importlogpage'                    => 'Nhật trình nhập trang',
'importlogpagetext'                => 'Đây là danh sách các trang được quản lý nhập vào đây. Các trang này có lịch sử sửa đổi từ hồi ở wiki khác.',
'import-logentry-upload'           => 'nhập vào [[$1]] khi truyền lên tập tin',
'import-logentry-upload-detail'    => '$1 phiên bản',
'import-logentry-interwiki'        => 'nhập vào $1 từ wiki khác',
'import-logentry-interwiki-detail' => '$1 phiên bản từ $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Trang của tôi',
'tooltip-pt-anonuserpage'         => 'Trang của IP bạn đang dùng',
'tooltip-pt-mytalk'               => 'Thảo luận với tôi',
'tooltip-pt-anontalk'             => 'Thảo luận với địa chỉ IP này',
'tooltip-pt-preferences'          => 'Lựa chọn cá nhân của tôi',
'tooltip-pt-watchlist'            => 'Thay đổi của các trang tôi theo dõi.',
'tooltip-pt-mycontris'            => 'Đóng góp của tôi',
'tooltip-pt-login'                => 'Đăng nhập sẽ có lợi hơn, tuy nhiên không bắt buộc.',
'tooltip-pt-anonlogin'            => 'Không đăng nhập vẫn tham gia được, tuy nhiên đăng nhập sẽ lợi hơn.',
'tooltip-pt-logout'               => 'Đăng xuất',
'tooltip-ca-talk'                 => 'Thảo luận về trang này',
'tooltip-ca-edit'                 => 'Bạn có thể sửa được trang này. Xin xem thử trước khi lưu.',
'tooltip-ca-addsection'           => 'Thêm bình luận vào đây.',
'tooltip-ca-viewsource'           => 'Trang này được khóa. Bạn có thể xem mã nguồn.',
'tooltip-ca-history'              => 'Những phiên bản cũ của trang này.',
'tooltip-ca-protect'              => 'Khóa trang này lại',
'tooltip-ca-delete'               => 'Xóa trang này',
'tooltip-ca-undelete'             => 'Khôi phục lại những sửa đổi trên trang này trước khi nó bị xóa',
'tooltip-ca-move'                 => 'Di chuyển trang này',
'tooltip-ca-watch'                => 'Thêm trang này vào danh sách theo dõi',
'tooltip-ca-unwatch'              => 'Bỏ trang này khỏi danh sách theo dõi',
'tooltip-search'                  => 'Tìm kiếm',
'tooltip-p-logo'                  => 'Trang đầu',
'tooltip-n-mainpage'              => 'Đi đến Trang Chính',
'tooltip-n-portal'                => 'Giới thiệu dự án, cách sử dụng, tìm kiếm thông tin ở đây',
'tooltip-n-currentevents'         => 'Xem thời sự',
'tooltip-n-recentchanges'         => 'Danh sách các thay đổi gần đây',
'tooltip-n-randompage'            => 'Xem trang ngẫu nhiên',
'tooltip-n-help'                  => 'Nơi tìm hiểu thêm cách dùng.',
'tooltip-n-sitesupport'           => 'Quyên góp xây dựng dự án mở',
'tooltip-t-whatlinkshere'         => 'Các trang liên kết đến đây',
'tooltip-t-recentchangeslinked'   => 'Thay đổi gần đây của các trang liên kết đến đây',
'tooltip-feed-rss'                => 'Nạp RSS cho trang này',
'tooltip-feed-atom'               => 'Nạp Atom cho trang này',
'tooltip-t-contributions'         => 'Xem đóng góp của người này',
'tooltip-t-emailuser'             => 'Gửi thư cho người này',
'tooltip-t-upload'                => 'Tải hình ảnh hoặc tập tin lên',
'tooltip-t-specialpages'          => 'Danh sách các trang đặc biệt',
'tooltip-t-print'                 => 'Bản để in ra của trang',
'tooltip-t-permalink'             => 'Liên kết thường trực đến phiên bản này của trang',
'tooltip-ca-nstab-main'           => 'Xem trang này',
'tooltip-ca-nstab-user'           => 'Xem trang về người này',
'tooltip-ca-nstab-media'          => 'Xem trang phương tiện',
'tooltip-ca-nstab-special'        => 'Đây là một trang dặc biệt, bạn không thể sửa đổi được nó.',
'tooltip-ca-nstab-project'        => 'Xem trang dự án',
'tooltip-ca-nstab-image'          => 'Xem trang hình',
'tooltip-ca-nstab-mediawiki'      => 'Xem thông báo hệ thống',
'tooltip-ca-nstab-template'       => 'Xem tiêu bản',
'tooltip-ca-nstab-help'           => 'Xem trang trợ giúp',
'tooltip-ca-nstab-category'       => 'Xem trang thể loại',
'tooltip-minoredit'               => 'Đánh dấu đây là sửa đổi nhỏ',
'tooltip-save'                    => 'Lưu lại những thay đổi của bạn',
'tooltip-preview'                 => 'Xem thử những thay đổi trước khi lưu!',
'tooltip-diff'                    => 'Xem thay đổi bạn đã thực hiện',
'tooltip-compareselectedversions' => 'Xem khác biệt giữa hai phiên bản của trang này.',
'tooltip-watch'                   => 'Cho trang này vào danh sách theo dõi',
'tooltip-recreate'                => 'Tạo lại trang dù cho nó vừa bị xóa',

# Metadata
'nodublincore'      => 'Máy chủ không hỗ trợ siêu dữ liệu Dublin Core RDF.',
'nocreativecommons' => 'Máy chủ không hỗ trợ siêu dữ liệu Creative Commons RDF.',
'notacceptable'     => 'Máy chủ không thể cho ra định dạng dữ liệu tương thích với phần mềm của bạn.',

# Attribution
'anonymous'        => 'Thành viên vô danh của {{SITENAME}}',
'siteuser'         => 'Thành viên $1 của {{SITENAME}}',
'lastmodifiedatby' => 'Trang này được $3 cập nhật lần cuối lúc $2, $1.', # $1 date, $2 time, $3 user
'and'              => 'và',
'othercontribs'    => 'dựa trên công trình của $1.',
'others'           => 'những người khác',
'siteusers'        => 'Thành viên $1 của {{SITENAME}}',
'creditspage'      => 'Trang ghi nhận đóng góp',
'nocredits'        => 'Không có thông tin ghi nhận đóng góp cho trang này.',

# Spam protection
'spamprotectiontitle'    => 'Bộ lọc chống thư rác',
'spamprotectiontext'     => 'Trang bạn muốn lưu bị bộ lọc thư rác chặn lại. Đây có thể do một liên kết dẫn tới một địa chỉ bên ngoài.',
'spamprotectionmatch'    => 'Nội dung sau đây đã kích hoạt bộ lọc thư rác: $1',
'subcategorycount'       => 'Có $1 tiểu thể loại trong thể loại này.',
'categoryarticlecount'   => 'Có $1 bài trong thể loại này.',
'category-media-count'   => '{{#ifeq: $1 | 0 |Không có tập tin nào|Có ít nhất $1 tập tin}} trong thể loại này.',
'listingcontinuesabbrev' => 'tiếp',
'spambot_username'       => 'Robot MediaWiki dọn dẹp liên kết nhũng lạm',
'spam_reverting'         => 'Đổi lại thành phiên bản cuối không liên kết đến $1',
'spam_blanking'          => 'Các phiên bản liên kết đến $1; tẩy trống',

# Info page
'infosubtitle'   => 'Thông tin về trang',
'numedits'       => 'Số lần sửa đổi (bài chính): $1',
'numtalkedits'   => 'Số lần sửa đổi  (trang thảo luận): $1',
'numwatchers'    => 'Số người theo dõi: $1',
'numauthors'     => 'Số người sửa đổi khác nhau (bài chính): $1',
'numtalkauthors' => 'Số người sửa đổi khác nhau (trang thảo luận): $1',

# Math options
'mw_math_png'    => 'Luôn cho ra dạng hình PNG',
'mw_math_simple' => 'HTML nếu rất đơn giản, nếu không PNG',
'mw_math_html'   => 'HTML nếu có thể, nếu không PNG',
'mw_math_source' => 'Để là TeX (cho trình duyệt văn bản)',
'mw_math_modern' => 'Dành cho trình duyệt hiện đại',
'mw_math_mathml' => 'MathML nếu có thể',

# Patrolling
'markaspatrolleddiff'                 => 'Đánh dấu tuần tra',
'markaspatrolledtext'                 => 'Đánh dấu tuần tra',
'markedaspatrolled'                   => 'Đã đánh dấu tuần tra',
'markedaspatrolledtext'               => 'Bản được đánh dấu đã tuần tra.',
'rcpatroldisabled'                    => '"Thay đổi gần đây" của các trang tuần tra không bật',
'rcpatroldisabledtext'                => 'Chức năng "thay đổi gần đây"  của các trang tuần tra hiện không được bật.',
'markedaspatrollederror'              => 'Không thể đánh dấu tuần tra',
'markedaspatrollederrortext'          => 'Bạn phải chọn phiên bản để đánh dấu tuần tra.',
'markedaspatrollederror-noautopatrol' => 'Bạn không được đánh dấu tuần tra vào sửa đổi của bạn.',

# Patrol log
'patrol-log-page' => 'Nhật ký tuần tra',
'patrol-log-line' => 'đánh dấu tuần tra vào phiên bản $1 của $2 $3',
'patrol-log-auto' => '(tự động)',

# Image deletion
'deletedrevision'                 => 'Đã xóa phiên bản cũ $1',
'filedeleteerror-short'           => 'Lỗi xóa tập tin: $1',
'filedeleteerror-long'            => 'Có lỗi khi xóa tập tin:

$1',
'filedelete-missing'              => 'Không thể xóa tập tin "$1" vì không tồn tại.',
'filedelete-old-unregistered'     => 'Phiên bản chỉ định "$1" không có trong cơ sở dữ liệu.',
'filedelete-current-unregistered' => 'Tập tin "$1" không thấy trong cơ sở dữ liệu.',
'filedelete-archive-read-only'    => 'Máy chủ web không ghi được vào thư mục lưu trữ "$1".',

# Browsing diffs
'previousdiff' => '&larr; So với trước',
'nextdiff'     => 'So với sau &rarr;',

# Media information
'mediawarning'         => " '''Cảnh báo''': Tệp tin này có thể làm hại máy tính của bạn. <hr />",
'imagemaxsize'         => 'Giới hạn độ phân giải ảnh là:&nbsp;',
'thumbsize'            => 'Kích thước thu nhỏ:&nbsp;',
'widthheightpage'      => '$1×$2, $3 trang',
'file-info'            => '(kích thước tập tin: $1, định dạng MIME: $2)',
'file-info-size'       => '($1 × $2 điểm ảnh, kích thước: $3, kiểu MIME: $4)',
'file-nohires'         => '<small>Không có độ phân giải cao hơn.</small>',
'show-big-image'       => 'Độ phân giải tối đa',
'show-big-image-thumb' => '<small>Kích thước xem thử: $1 × $2 điểm ảnh</small>',

# Special:Newimages
'newimages'    => 'Trang trưng bày hình ảnh mới',
'showhidebots' => '($1 robot)',
'noimages'     => 'Chưa có hình',

# Bad image list
'bad_image_list' => 'Định dạng như sau:

Chỉ có những mục được liệt kê (những dòng bắt đầu bằng *) mới được tính tới. Liên kết đầu tiên tại một dòng phải là liên kết đến hình ảnh xấu.
Các liên kết sau đó trên cùng một dòng được xem là các ngoại lệ, có nghĩa là các trang mà tại đó có thể dùng được hình.',

# Metadata
'metadata'          => 'Đặc tính hình',
'metadata-help'     => 'Tập tin này có chứa thông tin về nó, do máy ảnh hay máy quét thêm vào. Nếu tập tin bị sửa đổi sau khi được tạo ra lần đầu, có thể thông tin này không được cập nhật.',
'metadata-expand'   => 'Xem chi tiết cấp cao',
'metadata-collapse' => 'Giấu chi tiết cấp cao',
'metadata-fields'   => 'Những thông tin EXIF được danh sách dưới đây sẽ được bao gồm vào trang miêu tả hình khi bảng EXIF bị dẹp xuống. Những thông tin khác sẽ bị giấu mặc định.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Bề ngang',
'exif-imagelength'                 => 'Chiều cao',
'exif-bitspersample'               => 'Bit trên mẫu',
'exif-compression'                 => 'Kiểu nén',
'exif-orientation'                 => 'Hướng',
'exif-samplesperpixel'             => 'Số mẫu trên điểm ảnh',
'exif-planarconfiguration'         => 'Cách xếp dữ liệu',
'exif-ycbcrsubsampling'            => 'Tỉ lệ lấy mẫu con của Y so với C',
'exif-ycbcrpositioning'            => 'Định vị Y và C',
'exif-xresolution'                 => 'Phân giải trên bề ngang',
'exif-yresolution'                 => 'Phân giải theo chiều cao',
'exif-resolutionunit'              => 'Đơn vị phân giải X và Y',
'exif-stripoffsets'                => 'Vị trí dữ liệu hình',
'exif-rowsperstrip'                => 'Số hàng trên mỗi mảnh',
'exif-jpeginterchangeformat'       => 'Vị trí SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Kích cỡ (byte) của JPEG',
'exif-transferfunction'            => 'Hàm chuyển đổi',
'exif-ycbcrcoefficients'           => 'Hệ số ma trận biến đổi không gian màu',
'exif-datetime'                    => 'Ngày tháng sửa',
'exif-imagedescription'            => 'Tiêu đề của hình',
'exif-make'                        => 'Hãng máy ảnh',
'exif-model'                       => 'Kiểu máy ảnh',
'exif-software'                    => 'Phần mềm đã dùng',
'exif-artist'                      => 'Tác giả',
'exif-copyright'                   => 'Bản quyền',
'exif-exifversion'                 => 'Phiên bản exif',
'exif-flashpixversion'             => 'Phiên bản Flashpix được hỗ trợ',
'exif-colorspace'                  => 'Không gian màu',
'exif-componentsconfiguration'     => 'Ý nghĩa thành phần',
'exif-compressedbitsperpixel'      => 'Độ nén (bit/điểm)',
'exif-pixelydimension'             => 'Bề ngang hợp lệ',
'exif-pixelxdimension'             => 'Bề cao hình hợp lệ',
'exif-makernote'                   => 'Lưu ý của nhà sản xuất',
'exif-usercomment'                 => 'Ghi chú của tác giả',
'exif-relatedsoundfile'            => 'Tệp âm thanh liên quan',
'exif-datetimeoriginal'            => 'Ngày giờ sinh dữ liệu',
'exif-datetimedigitized'           => 'Ngày giờ số hóa',
'exif-exposuretime'                => 'Thời gian mở ống kính',
'exif-exposuretime-format'         => '$1 giây ($2)',
'exif-fnumber'                     => 'Số F',
'exif-exposureprogram'             => 'Chương trình phơi sáng',
'exif-isospeedratings'             => 'Điểm tốc độ ISO',
'exif-shutterspeedvalue'           => 'Tốc độ cửa chớp',
'exif-aperturevalue'               => 'Độ mở ống kính',
'exif-brightnessvalue'             => 'Độ sáng',
'exif-exposurebiasvalue'           => 'Độ lệch phơi sáng',
'exif-maxaperturevalue'            => 'Khẩu độ cực đại qua đất',
'exif-meteringmode'                => 'Chế độ đo',
'exif-lightsource'                 => 'Nguồn sáng',
'exif-flash'                       => 'Đèn chớp',
'exif-focallength'                 => 'Độ dài tiêu cự thấu kính',
'exif-flashenergy'                 => 'Nguồn đèn chớp',
'exif-focalplanexresolution'       => 'Phân giải X trên mặt phẳng tiêu',
'exif-focalplaneyresolution'       => 'Phân giải Y trên mặt phẳng tiêu',
'exif-focalplaneresolutionunit'    => 'Đơn vị phân giải trên mặt phẳng tiêu',
'exif-exposureindex'               => 'Chỉ số phơi sáng',
'exif-sensingmethod'               => 'Phương pháp đo',
'exif-filesource'                  => 'Nguồn tập tin',
'exif-scenetype'                   => 'Loại cảnh',
'exif-cfapattern'                  => 'Mẫu CFA',
'exif-customrendered'              => 'Sửa hình thủ công',
'exif-exposuremode'                => 'Chế độ phơi sáng',
'exif-whitebalance'                => 'Độ sáng trắng',
'exif-digitalzoomratio'            => 'Tỉ lệ phóng lớn kỹ thuật số',
'exif-focallengthin35mmfilm'       => 'Tiêu cự trong phim 35 mm',
'exif-scenecapturetype'            => 'Kiểu chụp cảnh',
'exif-gaincontrol'                 => 'Điều khiển cảnh',
'exif-contrast'                    => 'Độ tương phản',
'exif-saturation'                  => 'Độ bão hòa',
'exif-sharpness'                   => 'Độ sắc nét',
'exif-devicesettingdescription'    => 'Miêu tả cài đặt thiết bị',
'exif-subjectdistancerange'        => 'Khoảng cách tới vật',
'exif-imageuniqueid'               => 'ID hình duy nhất',
'exif-gpsversionid'                => 'Phiên bản thẻ GPS',
'exif-gpslatituderef'              => 'Vĩ độ bắc hay nam',
'exif-gpslatitude'                 => 'Vĩ độ',
'exif-gpslongituderef'             => 'Kinh độ đông hay tây',
'exif-gpslongitude'                => 'Kinh độ',
'exif-gpsaltituderef'              => 'Tham chiếu cao độ',
'exif-gpsaltitude'                 => 'Độ cao',
'exif-gpstimestamp'                => 'Giờ GPS (đồng hồ nguyên tử)',
'exif-gpssatellites'               => 'Vệ tinh nhân tạo dùng để đo',
'exif-gpsmeasuremode'              => 'Chế độ đo',
'exif-gpsspeedref'                 => 'Đơn vị tốc độ',
'exif-gpstrack'                    => 'Hướng chuyển động',
'exif-gpsimgdirection'             => 'Hướng của hình',
'exif-gpsprocessingmethod'         => 'Tên phương pháp xử lý GPS',
'exif-gpsareainformation'          => 'Tên khu vực theo GPS',
'exif-gpsdatestamp'                => 'Ngày theo GPS',

# EXIF attributes
'exif-compression-1' => 'Không nén',

'exif-unknowndate' => 'Không biết ngày',

'exif-orientation-1' => 'Thường', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Lộn ngược theo phương ngang', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Quay 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Lộn ngược theo phương dọc', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Quay 90° bên trái và lộn thẳng đứng', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Quay 90° bên phải', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Quay 90° bên phải và lộn thẳng đứng', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Quay 90° bên trái', # 0th row: left; 0th column: bottom

'exif-componentsconfiguration-0' => 'không có',

'exif-exposureprogram-0' => 'Không định',
'exif-exposureprogram-1' => 'Thủ công',
'exif-exposureprogram-2' => 'Chương trình chuẩn',

'exif-subjectdistance-value' => '$1 mét',

'exif-meteringmode-0'   => 'Không biết',
'exif-meteringmode-1'   => 'Trung bình',
'exif-meteringmode-2'   => 'Trung bình trọng lượng ở giữa',
'exif-meteringmode-5'   => 'Lấy mẫu',
'exif-meteringmode-255' => 'Khác',

'exif-lightsource-0'   => 'Không biết',
'exif-lightsource-1'   => 'Trời nắng',
'exif-lightsource-2'   => 'Huỳnh quang',
'exif-lightsource-3'   => 'Vonfram (ánh nóng sáng)',
'exif-lightsource-9'   => 'Trời đẹp',
'exif-lightsource-10'  => 'Trời mây',
'exif-lightsource-11'  => 'Che nắng',
'exif-lightsource-12'  => 'Nắng huỳnh quang (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Màu trắng huỳnh quang ban ngày (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Màu trắng mát huỳnh quang (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Màu trắng huỳnh quang (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Ánh chuẩn A',
'exif-lightsource-18'  => 'Ánh chuẩn B',
'exif-lightsource-19'  => 'Ánh chuẩn C',
'exif-lightsource-24'  => 'Vonfram xưởng ISO',
'exif-lightsource-255' => 'Nguồn ánh sáng khác',

'exif-focalplaneresolutionunit-2' => 'inch',

'exif-sensingmethod-1' => 'Không định rõ',
'exif-sensingmethod-2' => 'Đầu đo vùng màu một mảnh',
'exif-sensingmethod-3' => 'Đầu đo vùng màu hai mảnh',
'exif-sensingmethod-4' => 'Đầu đo vùng màu ba mảnh',

'exif-scenetype-1' => 'Hình chụp thẳng',

'exif-customrendered-0' => 'Thường',
'exif-customrendered-1' => 'Thủ công',

'exif-exposuremode-0' => 'Phơi sáng tự động',
'exif-exposuremode-1' => 'Phơi sáng thủ công',
'exif-exposuremode-2' => 'Tự động đặt trong dấu ngoặc đơn',

'exif-whitebalance-0' => 'Cân bằng trắng tự động',
'exif-whitebalance-1' => 'Cân bằng trắng thủ công',

'exif-scenecapturetype-0' => 'Chuẩn',
'exif-scenecapturetype-1' => 'Nằm',
'exif-scenecapturetype-2' => 'Đứng',
'exif-scenecapturetype-3' => 'Cảnh ban đêm',

'exif-gaincontrol-0' => 'Không có',

'exif-contrast-0' => 'Thường',
'exif-contrast-1' => 'Nhẹ',
'exif-contrast-2' => 'Mạnh',

'exif-saturation-0' => 'Thường',
'exif-saturation-1' => 'Độ bão hòa thấp',
'exif-saturation-2' => 'Độ bão hòa cao',

'exif-sharpness-0' => 'Thường',
'exif-sharpness-1' => 'Dẻo',
'exif-sharpness-2' => 'Cứng',

'exif-subjectdistancerange-0' => 'Không biết',
'exif-subjectdistancerange-2' => 'Nhìn gần',
'exif-subjectdistancerange-3' => 'Nhìn xa',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Vĩ độ bắc',
'exif-gpslatitude-s' => 'Vĩ độ nam',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Kinh độ đông',
'exif-gpslongitude-w' => 'Kinh độ tây',

'exif-gpsstatus-a' => 'Đang đo',

'exif-gpsmeasuremode-2' => 'Đo 2 chiều',
'exif-gpsmeasuremode-3' => 'Đo 3 chiều',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilômét một giờ',
'exif-gpsspeed-m' => 'Dặm một giờ',
'exif-gpsspeed-n' => 'Hải lý một giờ',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Hướng thật',

# External editor support
'edit-externally'      => 'Sửa bằng phần mềm bên ngoài',
'edit-externally-help' => '* Xem thêm [http://meta.wikimedia.org/wiki/Help:External_editors hướng dẫn bằng tiếng Anh]',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tất cả',
'imagelistall'     => 'tất cả',
'watchlistall2'    => 'tất cả',
'namespacesall'    => 'tất cả',
'monthsall'        => 'tất cả',

# E-mail address confirmation
'confirmemail'            => 'Xác nhận thư điện tử',
'confirmemail_noemail'    => 'Bạn chưa đưa vào địa chỉ thư điện tử hợp lệ ở [[Đặc biệt:Preferences|tùy chọn cá nhân]].',
'confirmemail_text'       => 'Cần kiểm tra địa chỉ thư điện tử trước khi lưu. Ấn nút bên dưới để gửi thư xác nhận đến địa chỉ. Thư xác nhận có một mã xác nhận; khi bạn nhập mã xác nhận vào đây, địa chỉ thư điện tử của bạn sẽ được xác nhận.',
'confirmemail_pending'    => '<div class="error">
Đã gửi mã xác nhận đến địa chỉ thư điện tử của bạn. Nếu bạn mới mở tài khoản này, xin chờ vài phút cho mã trước khi yêu cầu mã mới.
</div>',
'confirmemail_send'       => 'Gửi thư xác nhận',
'confirmemail_sent'       => 'Thư xác nhận đã được gửi',
'confirmemail_oncreate'   => 'Đã gửi mã xác nhận đến địa chỉ thư điện tử của bạn. Bạn không cần mã này để đăng nhập, nhưng cần sử dụng nó để bật lên các chức năng thư điện tử của wiki này.',
'confirmemail_sendfailed' => 'Không thể gửi thư xác nhận. Xin kiểm tra lại địa chỉ thư.

Chương trình gửi trả về: $1',
'confirmemail_invalid'    => 'Mã xác nhận sai. Mã này có thể đã hết hạn',
'confirmemail_needlogin'  => 'Bạn cần phải $1 để xác nhận địa chỉ thư điện tử.',
'confirmemail_success'    => 'Thư điện tử của bạn đã được xác nhận. Bạn có thể đăng nhập được.',
'confirmemail_loggedin'   => 'Địa chỉ thư điện tử của bạn đã được xác nhận',
'confirmemail_error'      => 'Có trục trặc',
'confirmemail_subject'    => 'Xác nhận thư điện tử tại {{SITENAME}}',
'confirmemail_body'       => 'Ai đó, có thể là bạn, với địa chỉ thư điện tử $1, đã mở tài khoản "$2" dùng địa chỉ này ở {{SITENAME}}.

Để xác nhận rằng tài khoản này của bạn và dùng chức năng thư điện tử ở {{SITENAME}}, xin mở địa chỉ mạng sau :

$3

Nếu không phải bạn, đừng mở địa chỉ này. Mã xác nhận này sẽ hết hạn lúc $4.',

# Scary transclusion
'scarytranscludedisabled' => 'Liên wiki bị tắt',
'scarytranscludefailed'   => 'Tiêu bản cho $1 bị tắt',
'scarytranscludetoolong'  => 'Địa chỉ mạng dài quá',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Các TrackBack về bài này:<br />
$1
</div>',
'trackbackremove'   => ' ([$1 Xóa])',
'trackbacklink'     => 'TrackBack',
'trackbackdeleteok' => 'Đã xóa trackback.',

# Delete conflict
'deletedwhileediting' => '<div style="float: left">[[Hình:Nuvola filesystems trashcan full.png|không|24px]]</div>
<p style="margin-left: 28px"><strong style="color: red; background-color: white">Đã bị xóa:</strong> Trong lúc bạn đang sửa đổi trang này, một người quản lý đã xóa nó đi. Xin hãy tham khảo [[{{ns:Special}}:Log/delete|nhật trình xóa]] để xem tại sao.</p>',
'confirmrecreate'     => '<div style="float: left">[[Hình:Nuvola filesystems trashcan full.png|không|24px]]</div>
<div style="margin-left: 28px">

<strong style="color: red; background-color: white">Đã bị xóa:</strong> Thành viên [[Thành viên:$1|$1]] ([[Thảo luận Thành viên:$1|thảo luận]]) đã xóa trang này trong lúc bạn đang sửa đổi nó, họ cho lý do:

:\'\'$2\'\'

Xin hãy xác nhận là bạn vẫn muốn tạo ra trang này lại hay không.

</div>',
'recreate'            => 'Tạo ra lại',

# HTML dump
'redirectingto' => "Đổi hướng đến '''[[$1]]'''…",

# action=purge
'confirm_purge'        => "[[Hình:Nuvola apps kcmmemory.png|phải|100px|]]

Bạn sắp '''làm sạch vùng nhớ đệm''' của trang này.

''Clear the cache of this page?''

$1

----
Những tập tin [[Bộ nhớ đệm|nhớ đệm]] là bản sao tạm thời của một trang.

Khi bạn truy nhập vào một trang ở đây, phần mềm dùng mã nguồn để tạo ra phiên bản mới và gửi nó tới trình duyệt của bạn. Do hạn chế công nghệ, các trang được vào bộ nhớ đệm&nbsp;– nó được lưu tạm thời. Vì vậy, hễ khi người dùng thăm một trang đã được thăm trước, bản sao trong vùng nhớ đệm sẽ hiển thị thay vì trang cập nhật.

Sau khi làm sạch vùng nhớ đệm, phiên bản hiện hành sẽ hiển thị.",
'confirm_purge_button' => 'Được',

# AJAX search
'searchcontaining' => 'Tìm những bài có “$1”.',
'searchnamed'      => "Tìm kiếm bài có tên ''$1''.",
'articletitles'    => "Các bài bắt đầu với ''$1''",
'hideresults'      => 'Giấu kết quả',

# Multipage image navigation
'imgmultipageprev'   => '← trang trước',
'imgmultipagenext'   => 'trang sau →',
'imgmultigo'         => 'Hiển thị',
'imgmultigotopre'    => 'Xem trang',
'imgmultiparseerror' => 'Tập tin hình có vẻ bị hỏng nên {{SITENAME}} không thể lấy được danh sách các trang.',

# Table pager
'ascending_abbrev'         => 'tăng',
'descending_abbrev'        => 'giảm',
'table_pager_next'         => 'Trang sau',
'table_pager_prev'         => 'Trang trước',
'table_pager_first'        => 'Trang đầu',
'table_pager_last'         => 'Trang cuối',
'table_pager_limit'        => 'Xem $1 kết quả mỗi trang',
'table_pager_limit_submit' => 'Hiển thị',
'table_pager_empty'        => 'Không có kết quả nào.',

# Auto-summaries
'autosumm-blank'   => 'Tẩy trống',
'autosumm-replace' => 'Thay cả nội dung bằng “$1”',
'autoredircomment' => 'Đổi hướng đến [[$1]]',
'autosumm-new'     => 'Trang mới: $1',

# Size units
'size-kilobytes' => '$1 kB',

# Live preview
'livepreview-loading' => 'Đang tải…',
'livepreview-ready'   => 'Đang tải… Xong!',
'livepreview-failed'  => 'Không thể xem thử trực tiếp!
Dùng thử chế độ xem thử thông thường.',
'livepreview-error'   => 'Không thể kết nối: $1 "$2"
Dùng thử chế độ xem thử thông thường.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Những thay đổi trong vòng $1 giây trở lại đây có thể chưa xuất hiện trong danh sách.',
'lag-warn-high'   => 'Do độ trễ của máy chủ cơ sở dữ liệu, những thay đổi trong vòng $1 giây trở lại đây có thể chưa xuất hiện trong danh sách.',

# Watchlist editor
'watchlistedit-numitems'       => 'Danh sách theo dõi của bạn có $1 trang, không tính các trang thảo luận.',
'watchlistedit-noitems'        => 'Danh sách các trang bạn theo dõi hiện không có gì.',
'watchlistedit-normal-title'   => 'Sửa các trang tôi theo dõi',
'watchlistedit-normal-legend'  => 'Bỏ các trang đang theo dõi ra khỏi danh sách',
'watchlistedit-normal-explain' => 'Tên các trang bạn theo dõi được hiển thị dưới đây. Để xóa một tên trang, nhấp chuột
	vào ô cạnh nó, rồi nhấn nút "Bỏ trang đã chọn". Bạn cũng có thể [[Special:Watchlist/raw|sửa danh sách theo dạng thô]],
	hoặc [[Special:Watchlist/clear|xóa toàn bộ các trang theo dõi]].',
'watchlistedit-normal-submit'  => 'Bỏ trang đã chọn',
'watchlistedit-normal-done'    => '$1 trang đã được xóa khỏi danh sách các trang theo dõi:',
'watchlistedit-raw-title'      => 'Sửa danh sách theo dõi dạng thô',
'watchlistedit-raw-legend'     => 'Sửa danh sách theo dõi dạng thô',
'watchlistedit-raw-explain'    => 'Tên các trang bạn theo dõi đuọc hiển thị dưới đây, và có thể được sửa chữa bằng cách
	thêm vào hoặc bỏ ra khỏi danh sách; mỗi trang một hàng. Khi xong, nhấn nút "Cập nhật Trang tôi theo dõi".
	Bạn cũng có thể [[Special:Watchlist/edit|dùng trình soạn thảo chuẩn]] để sửa danh sách này.',
'watchlistedit-raw-titles'     => 'Tên các trang:',
'watchlistedit-raw-submit'     => 'Cập nhật Trang tôi theo dõi',
'watchlistedit-raw-done'       => 'Danh sách các trang bạn theo dõi đã được cập nhật.',
'watchlistedit-raw-added'      => '$1 trang đã được thêm vào:',
'watchlistedit-raw-removed'    => '$1 trang đã được xóa khỏi danh sách:',

# Watchlist editing tools
'watchlisttools-view' => 'Xem thay đổi trên các trang theo dõi',
'watchlisttools-edit' => 'Xem và sửa danh sách theo dõi',
'watchlisttools-raw'  => 'Sửa danh sách theo dõi dạng thô',

);
