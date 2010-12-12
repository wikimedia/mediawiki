<?php
/** Vietnamese (Tiếng Việt)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Apple
 * @author Arisa
 * @author DHN
 * @author Minh Nguyen
 * @author Mxn
 * @author Neoneurone
 * @author Nguyễn Thanh Quang
 * @author Thaisk
 * @author Tmct
 * @author Trần Nguyễn Minh Huy
 * @author Trần Thế Trung
 * @author Tttrung
 * @author Vietbio
 * @author Vinhtantran
 * @author Vương Ngân Hà
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Phương_tiện',
	NS_SPECIAL          => 'Đặc_biệt',
	NS_TALK             => 'Thảo_luận',
	NS_USER             => 'Thành_viên',
	NS_USER_TALK        => 'Thảo_luận_Thành_viên',
	NS_PROJECT_TALK     => 'Thảo_luận_$1',
	NS_FILE             => 'Tập_tin',
	NS_FILE_TALK        => 'Thảo_luận_Tập_tin',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Thảo_luận_MediaWiki',
	NS_TEMPLATE         => 'Bản_mẫu',
	NS_TEMPLATE_TALK    => 'Thảo_luận_Bản_mẫu',
	NS_HELP             => 'Trợ_giúp',
	NS_HELP_TALK        => 'Thảo_luận_Trợ_giúp',
	NS_CATEGORY         => 'Thể_loại',
	NS_CATEGORY_TALK    => 'Thảo_luận_Thể_loại',
);

$namespaceAliases = array(
	'Hình' => NS_FILE,
	'Thảo_luận_Hình' => NS_FILE_TALK,
	'Tiêu_bản' => NS_TEMPLATE,
	'Thảo_luận_Tiêu_bản' => NS_TEMPLATE_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Đổi_hướng_kép' ),
	'BrokenRedirects'           => array( 'Đổi_hướng_sai' ),
	'Disambiguations'           => array( 'Trang_định_hướng' ),
	'Userlogin'                 => array( 'Đăng_nhập' ),
	'Userlogout'                => array( 'Đăng_xuất' ),
	'CreateAccount'             => array( 'Đăng_ký' ),
	'Preferences'               => array( 'Tùy_chọn' ),
	'Watchlist'                 => array( 'Danh_sách_theo_dõi' ),
	'Recentchanges'             => array( 'Thay_đổi_gần_đây' ),
	'Upload'                    => array( 'Tải_lên' ),
	'Listfiles'                 => array( 'Danh_sách_hình', 'Danh_sách_tập_tin' ),
	'Newimages'                 => array( 'Tập_tin_mới', 'Hình_mới' ),
	'Listusers'                 => array( 'Danh_sách_thành_viên' ),
	'Listgrouprights'           => array( 'Quyền_nhóm_người_dùng' ),
	'Statistics'                => array( 'Thống_kê' ),
	'Randompage'                => array( 'Ngẫu_nhiên' ),
	'Lonelypages'               => array( 'Trang_mồ_côi' ),
	'Uncategorizedpages'        => array( 'Trang_chưa_phân_loại' ),
	'Uncategorizedcategories'   => array( 'Thể_loại_chưa_phân_loại' ),
	'Uncategorizedimages'       => array( 'Hình_chưa_phân_loại' ),
	'Uncategorizedtemplates'    => array( 'Bản_mẫu_chưa_phân_loại', 'Tiêu_bản_chưa_phân_loại' ),
	'Unusedcategories'          => array( 'Thể_loại_chưa_dùng' ),
	'Unusedimages'              => array( 'Hình_chưa_dùng' ),
	'Wantedpages'               => array( 'Trang_cần_thiết' ),
	'Wantedcategories'          => array( 'Thể_loại_cần_thiết' ),
	'Wantedfiles'               => array( 'Tập_tin_cần_thiết' ),
	'Wantedtemplates'           => array( 'Bản_mẫu_cần_thiết', 'Tiêu_bản_cần_thiết' ),
	'Mostlinked'                => array( 'Liên_kết_nhiều_nhất' ),
	'Mostlinkedcategories'      => array( 'Thể_loại_liên_kết_nhiều_nhất' ),
	'Mostlinkedtemplates'       => array( 'Bản_mẫu_liên_kết_nhiều_nhất', 'Tiêu_bản_liên_kết_nhiều_nhất' ),
	'Mostimages'                => array( 'Tập_tin_liên_kết_nhiều_nhất' ),
	'Mostcategories'            => array( 'Thể_loại_lớn_nhất' ),
	'Mostrevisions'             => array( 'Nhiều_phiên_bản_nhất' ),
	'Fewestrevisions'           => array( 'Ít_phiên_bản_nhất' ),
	'Shortpages'                => array( 'Trang_ngắn' ),
	'Longpages'                 => array( 'Trang_dài' ),
	'Newpages'                  => array( 'Trang_mới' ),
	'Ancientpages'              => array( 'Trang_cũ' ),
	'Deadendpages'              => array( 'Trang_đường_cùng' ),
	'Protectedpages'            => array( 'Trang_khóa' ),
	'Protectedtitles'           => array( 'Tựa_đề_bị_khóa' ),
	'Allpages'                  => array( 'Mọi_bài' ),
	'Prefixindex'               => array( 'Tiền_tố' ),
	'Ipblocklist'               => array( 'Danh_sách_cấm' ),
	'Specialpages'              => array( 'Trang_đặc_biệt' ),
	'Contributions'             => array( 'Đóng_góp' ),
	'Emailuser'                 => array( 'Gửi_thư' ),
	'Confirmemail'              => array( 'Xác_nhận_thư' ),
	'Whatlinkshere'             => array( 'Liên_kết_đến_đây' ),
	'Recentchangeslinked'       => array( 'Thay_đổi_liên_quan' ),
	'Movepage'                  => array( 'Di_chuyển' ),
	'Blockme'                   => array( 'Khóa_tôi' ),
	'Booksources'               => array( 'Nguồn_sách' ),
	'Categories'                => array( 'Thể_loại' ),
	'Export'                    => array( 'Xuất' ),
	'Version'                   => array( 'Phiên_bản' ),
	'Allmessages'               => array( 'Mọi_thông_báo' ),
	'Log'                       => array( 'Nhật_trình' ),
	'Blockip'                   => array( 'Cấm_IP' ),
	'Undelete'                  => array( 'Phục_hồi' ),
	'Import'                    => array( 'Nhập' ),
	'Lockdb'                    => array( 'Khóa_CSDL' ),
	'Unlockdb'                  => array( 'Mở_khóa_CSDL' ),
	'Userrights'                => array( 'Quyền_thành_viên' ),
	'MIMEsearch'                => array( 'Tìm_MIME' ),
	'FileDuplicateSearch'       => array( 'Tìm_tập_tin_trùng' ),
	'Unwatchedpages'            => array( 'Trang_chưa_theo_dõi' ),
	'Listredirects'             => array( 'Trang_đổi_hướng' ),
	'Revisiondelete'            => array( 'Xóa_phiên_bản' ),
	'Unusedtemplates'           => array( 'Tiêu_bản_chưa_dùng', 'Bản_mẫu_chưa_dùng' ),
	'Randomredirect'            => array( 'Đổi_hướng_ngẫu_nhiên' ),
	'Mypage'                    => array( 'Trang_tôi', 'Trang_cá_nhân' ),
	'Mytalk'                    => array( 'Thảo_luận_tôi', 'Trang_thảo_luận_của_tôi' ),
	'Mycontributions'           => array( 'Đóng_góp_của_tôi', 'Tôi_đóng_góp' ),
	'Listadmins'                => array( 'Danh_sách_admin' ),
	'Listbots'                  => array( 'Danh_sách_bot' ),
	'Popularpages'              => array( 'Trang_phổ_biến' ),
	'Search'                    => array( 'Tìm_kiếm' ),
	'Resetpass'                 => array( 'Đổi_mật_khẩu' ),
	'Withoutinterwiki'          => array( 'Không_interwiki' ),
	'MergeHistory'              => array( 'Trộn_lịch_sử' ),
	'Filepath'                  => array( 'Đường_dẫn_file' ),
	'Invalidateemail'           => array( 'Tắt_thư' ),
	'Blankpage'                 => array( 'Trang_trắng' ),
	'LinkSearch'                => array( 'Tìm_liên_kết' ),
	'DeletedContributions'      => array( 'Đóng_góp_bị_xóa' ),
	'Tags'                      => array( 'Thẻ' ),
	'Activeusers'               => array( 'Người_dùng_tích_cực' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#đổi', '#REDIRECT' ),
	'notoc'                 => array( '0', '__KHÔNGMỤCMỤC__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__KHÔNGALBUM__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__LUÔNMỤCLỤC__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__MỤCLỤC__', '__TOC__' ),
	'noeditsection'         => array( '0', '__KHÔNGSỬAMỤC__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'THÁNGNÀY', 'THÁNGNÀY2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'THÁNGNÀY1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'TÊNTHÁNGNÀY', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'TÊNDÀITHÁNGNÀY', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'TÊNNGẮNTHÁNGNÀY', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'NGÀYNÀY', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'NGÀYNÀY2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'TÊNNGÀYNÀY', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'NĂMNÀY', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'GIỜNÀY', 'CURRENTTIME' ),
	'localmonth'            => array( '1', 'THÁNGĐỊAPHƯƠNG', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'THÁNGĐỊAPHƯƠNG1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'TÊNTHÁNGĐỊAPHƯƠNG', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'THÁNGĐỊAPHƯƠNGTẮT', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'NGÀYĐỊAPHƯƠNG', 'LOCALDAY' ),
	'localday2'             => array( '1', 'NGÀYĐỊAPHƯƠNG2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'TÊNNGÀYĐỊAPHƯƠNG', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'NĂMĐỊAPHƯƠNG', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'GIỜĐỊAPHƯƠNG', 'LOCALTIME' ),
	'numberofpages'         => array( '1', 'SỐTRANG', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'SỐBÀI', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'SỐTẬPTIN', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'SỐTHÀNHVIÊN', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'SỐTHÀNHVIÊNTÍCHCỰC', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'SỐSỬAĐỔI', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'SỐLẦNXEM', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'TÊNTRANG', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'TÊNTRANG2', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'KHÔNGGIANTÊN', 'NAMESPACE' ),
	'talkspace'             => array( '1', 'KGTTHẢOLUẬN', 'TALKSPACE' ),
	'subjectspace'          => array( '1', 'KGTNỘIDUNG', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'fullpagename'          => array( '1', 'TÊNTRANGĐỦ', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'TÊNTRANGPHỤ', 'SUBPAGENAME' ),
	'basepagename'          => array( '1', 'TÊNTRANGGỐC', 'BASEPAGENAME' ),
	'talkpagename'          => array( '1', 'TÊNTRANGTHẢOLUẬN', 'TALKPAGENAME' ),
	'subjectpagename'       => array( '1', 'TÊNTRANGNỘIDUNG', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'msg'                   => array( '0', 'NHẮN:', 'MSG:' ),
	'subst'                 => array( '0', 'THẾ:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'NHẮNMỚI:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'nhỏ', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'nhỏ=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'phải', 'right' ),
	'img_left'              => array( '1', 'trái', 'left' ),
	'img_none'              => array( '1', 'không', 'none' ),
	'img_center'            => array( '1', 'giữa', 'center', 'centre' ),
	'img_framed'            => array( '1', 'khung', 'framed', 'enframed', 'frame' ),
	'img_page'              => array( '1', 'trang=$1', 'trang $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'đứng', 'đứng=$1', 'đứng $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_link'              => array( '1', 'liên_kết=$1', 'link=$1' ),
	'int'                   => array( '0', 'NỘI:', 'INT:' ),
	'sitename'              => array( '1', 'TÊNMẠNG', 'SITENAME' ),
	'ns'                    => array( '0', 'KGT:', 'NS:' ),
	'localurl'              => array( '0', 'URLĐỊAPHƯƠNG:', 'LOCALURL:' ),
	'articlepath'           => array( '0', 'LỐIBÀI', 'ARTICLEPATH' ),
	'server'                => array( '0', 'MÁYCHỦ', 'SERVER' ),
	'servername'            => array( '0', 'TÊNMÁYCHỦ', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'ĐƯỜNGDẪNSCRIPT', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'NGỮPHÁP:', 'GRAMMAR:' ),
	'gender'                => array( '0', 'GIỐNG:', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__KHÔNGCHUYỂNTÊN__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__KHÔNGCHUYỂNNỘIDUNG__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'TUẦNNÀY', 'CURRENTWEEK' ),
	'localweek'             => array( '1', 'TUẦNĐỊAPHƯƠNG', 'LOCALWEEK' ),
	'revisionid'            => array( '1', 'SỐBẢN', 'REVISIONID' ),
	'revisionday'           => array( '1', 'NGÀYBẢN', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'NGÀYBẢN2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'THÁNGBẢN', 'REVISIONMONTH' ),
	'revisionmonth1'        => array( '1', 'THÁNGBẢN1', 'REVISIONMONTH1' ),
	'revisionyear'          => array( '1', 'NĂMBẢN', 'REVISIONYEAR' ),
	'plural'                => array( '0', 'SỐNHIỀU:', 'PLURAL:' ),
	'fullurl'               => array( '0', 'URLĐỦ:', 'FULLURL:' ),
	'newsectionlink'        => array( '1', '__LIÊNKẾTMỤCMỚI__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__KHÔNGLIÊNKẾTMỤCMỚI__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'BẢNNÀY', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'MÃHÓAURL:', 'URLENCODE:' ),
	'language'              => array( '0', '#NGÔNNGỮ:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'NGÔNNGỮNỘIDUNG', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'CỠKHÔNGGIANTÊN:', 'CỠKGT:', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'SỐQUẢNLÝ', 'NUMBEROFADMINS' ),
	'formatnum'             => array( '0', 'PHÂNCHIASỐ', 'FORMATNUM' ),
	'defaultsort'           => array( '1', 'XẾPMẶCĐỊNH:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'ĐƯỜNGDẪNTẬPTIN', 'FILEPATH:' ),
	'tag'                   => array( '0', 'thẻ', 'tag' ),
	'hiddencat'             => array( '1', '__THỂLOẠIẨN__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'CỠTHỂLOẠI', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'CỠTRANG', 'PAGESIZE' ),
	'numberingroup'         => array( '1', 'CỠNHÓM', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__ĐỔIHƯỚNGNHẤTĐỊNH__', '__STATICREDIRECT__' ),
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
$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Gạch chân liên kết:',
'tog-highlightbroken'         => 'Liên kết đến trang chưa được viết sẽ <a href="" class="new">như thế này</a> (nếu không chọn: như thế này<a href="" class="internal">?</a>)',
'tog-justify'                 => 'Căn đều hai bên đoạn văn',
'tog-hideminor'               => 'Ẩn sửa đổi nhỏ trong thay đổi gần đây',
'tog-hidepatrolled'           => 'Ẩn sửa đổi đã tuần tra trong trang thay đổi gần đây',
'tog-newpageshidepatrolled'   => 'Ẩn trang đã tuần tra trong danh sách các trang mới',
'tog-extendwatchlist'         => 'Mở rộng danh sách theo dõi để hiển thị tất cả các thay đổi, chứ không chỉ các thay đổi gần đây',
'tog-usenewrc'                => 'Sử dụng Thay đổi gần đây nâng cao (cần JavaScript)',
'tog-numberheadings'          => 'Tự động đánh số các đề mục',
'tog-showtoolbar'             => 'Hiển thị thanh định dạng (JavaScript)',
'tog-editondblclick'          => 'Nhấn đúp để sửa đổi trang (JavaScript)',
'tog-editsection'             => 'Cho phép sửa đổi đề mục qua liên kết [sửa]',
'tog-editsectiononrightclick' => 'Cho phép sửa đổi đề mục bằng cách bấm chuột phải trên tên đề mục (JavaScript)',
'tog-showtoc'                 => 'Hiển thị mục lục (cho trang có trên 3 đề mục)',
'tog-rememberpassword'        => 'Nhớ thông tin đăng nhập của tôi trong trình duyệt này (cho đến $1 ngày)',
'tog-watchcreations'          => 'Tự động theo dõi trang tôi viết mới',
'tog-watchdefault'            => 'Tự động theo dõi trang tôi sửa',
'tog-watchmoves'              => 'Tự động theo dõi trang tôi di chuyển',
'tog-watchdeletion'           => 'Tự động theo dõi trang tôi xóa',
'tog-minordefault'            => 'Mặc định đánh dấu tất cả sửa đổi của tôi là sửa đổi nhỏ',
'tog-previewontop'            => 'Hiển thị phần xem thử nằm trên hộp sửa đổi',
'tog-previewonfirst'          => 'Hiện xem thử tại lần sửa đầu tiên',
'tog-nocache'                 => 'Không lưu trang trong bộ nhớ đệm trình duyệt',
'tog-enotifwatchlistpages'    => 'Gửi thư cho tôi khi có thay đổi tại trang tôi theo dõi',
'tog-enotifusertalkpages'     => 'Gửi thư cho tôi khi có thay đổi tại trang thảo luận của tôi',
'tog-enotifminoredits'        => 'Gửi thư cho tôi cả những thay đổi nhỏ trong trang',
'tog-enotifrevealaddr'        => 'Hiện địa chỉ thư điện tử của tôi trong thư thông báo',
'tog-shownumberswatching'     => 'Hiển thị số người đang xem',
'tog-oldsig'                  => 'Chữ ký hiện tại:',
'tog-fancysig'                => 'Xem chữ ký là mã wiki (không có liên kết tự động)',
'tog-externaleditor'          => 'Mặc định dùng trình soạn thảo bên ngoài (chỉ dành cho người thành thạo, cần thiết lập đặc biệt trên máy tính của bạn)',
'tog-externaldiff'            => 'Mặc định dùng trình so sánh bên ngoài (chỉ dành cho người thành thạo, cần thiết lập đặc biệt trên máy tính của bạn)',
'tog-showjumplinks'           => 'Bật liên kết “bước tới” trên đầu trang cho bộ trình duyệt thuần văn bản hay âm thanh',
'tog-uselivepreview'          => 'Xem thử trực tiếp (JavaScript; chưa ổn định)',
'tog-forceeditsummary'        => 'Nhắc tôi khi tôi quên tóm lược sửa đổi',
'tog-watchlisthideown'        => 'Ẩn các sửa đổi của tôi khỏi danh sách theo dõi',
'tog-watchlisthidebots'       => 'Ẩn các sửa đổi của robot khỏi danh sách theo dõi',
'tog-watchlisthideminor'      => 'Ẩn các sửa đổi nhỏ khỏi danh sách theo dõi',
'tog-watchlisthideliu'        => 'Ẩn sửa đổi của thành viên đã đăng nhập khỏi danh sách theo dõi',
'tog-watchlisthideanons'      => 'Ẩn sửa đổi của thành viên vô danh khỏi danh sách theo dõi',
'tog-watchlisthidepatrolled'  => 'Ẩn sửa đổi đã tuần tra trong danh sách theo dõi',
'tog-nolangconversion'        => 'Tắt chuyển đổi biến thể',
'tog-ccmeonemails'            => 'Gửi bản sao cho tôi khi gửi thư điện tử cho người khác',
'tog-diffonly'                => 'Không hiển thị nội dung trang dưới phần so sánh phiên bản',
'tog-showhiddencats'          => 'Hiển thị thể loại ẩn',
'tog-noconvertlink'           => 'Tắt liên kết chuyển đổi tựa đề',
'tog-norollbackdiff'          => 'Không so sánh sau khi lùi sửa',

'underline-always'  => 'Luôn luôn',
'underline-never'   => 'Không bao giờ',
'underline-default' => 'Mặc định của trình duyệt',

# Font style option in Special:Preferences
'editfont-style'     => 'Kiểu phông chữ trong khung sửa đổi:',
'editfont-default'   => 'Mặc định của trình duyệt',
'editfont-monospace' => 'Phông đẳng cách',
'editfont-sansserif' => 'Phông không chân',
'editfont-serif'     => 'Phông có chân',

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

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Thể loại|Thể loại}}',
'category_header'                => 'Các trang trong thể loại “$1”',
'subcategories'                  => 'Tiểu thể loại',
'category-media-header'          => 'Các tập tin trong thể loại “$1”',
'category-empty'                 => "''Thể loại này hiện không có trang hay tập tin nào.''",
'hidden-categories'              => '{{PLURAL:$1|Thể loại ẩn|Thể loại ẩn}}',
'hidden-category-category'       => 'Thể loại ẩn',
'category-subcat-count'          => 'Thể loại này có {{PLURAL:$2|tiểu thể loại sau|{{PLURAL:$1||$1}} tiểu thể loại sau, trên tổng số $2 tiểu thể loại}}.',
'category-subcat-count-limited'  => 'Thể loại này có {{PLURAL:$1||$1}} tiểu thể loại sau.',
'category-article-count'         => '{{PLURAL:$2|Thể loại này gồm trang sau.|{{PLURAL:$1|Trang|$1 trang}} sau nằm trong thể loại này, trên tổng số $2 trang.}}',
'category-article-count-limited' => '{{PLURAL:$1|Trang|$1 trang}} sau nằm trong thể loại hiện hành.',
'category-file-count'            => '{{PLURAL:$2|Thể loại này có tập tin sau.|{{PLURAL:$1|Tập tin|$1 tập tin}} sau nằm trong thể loại này, trong tổng số $2 tập tin.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Tập tin|$1 tập tin}} sau nằm trong thể loại hiện hành.',
'listingcontinuesabbrev'         => 'tiếp',
'index-category'                 => 'Trang được ghi chỉ mục',
'noindex-category'               => 'Trang không ghi chỉ mục',

'mainpagetext'      => "'''MediaWiki đã được cài đặt thành công.'''",
'mainpagedocfooter' => 'Xin đọc [http://meta.wikimedia.org/wiki/Help:Contents Hướng dẫn sử dụng] để biết thêm thông tin về cách sử dụng phần mềm wiki.

== Để bắt đầu ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Danh sách các thiết lập cấu hình]
* [http://www.mediawiki.org/wiki/Manual:FAQ Các câu hỏi thường gặp MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Danh sách gửi thư về việc phát hành MediaWiki]',

'about'         => 'Giới thiệu',
'article'       => 'Trang nội dung',
'newwindow'     => '(mở cửa sổ mới)',
'cancel'        => 'Hủy bỏ',
'moredotdotdot' => 'Thêm nữa…',
'mypage'        => 'Trang của tôi',
'mytalk'        => 'Thảo luận với tôi',
'anontalk'      => 'Thảo luận với IP này',
'navigation'    => 'Xem nhanh',
'and'           => '&#32;và',

# Cologne Blue skin
'qbfind'         => 'Tìm kiếm',
'qbbrowse'       => 'Xem qua',
'qbedit'         => 'Sửa đổi',
'qbpageoptions'  => 'Trang này',
'qbpageinfo'     => 'Ngữ cảnh',
'qbmyoptions'    => 'Trang cá nhân',
'qbspecialpages' => 'Trang đặc biệt',
'faq'            => 'Câu hỏi thường gặp',
'faqpage'        => 'Project:Các câu hỏi thường gặp',

# Vector skin
'vector-action-addsection'       => 'Thêm chủ đề',
'vector-action-delete'           => 'Xóa',
'vector-action-move'             => 'Di chuyển',
'vector-action-protect'          => 'Khóa',
'vector-action-undelete'         => 'Phục hồi',
'vector-action-unprotect'        => 'Mở khóa',
'vector-simplesearch-preference' => 'Gợi ý tìm kiếm nâng cao (cần bề ngoài Vectơ)',
'vector-view-create'             => 'Tạo',
'vector-view-edit'               => 'Sửa',
'vector-view-history'            => 'Xem lịch sử',
'vector-view-view'               => 'Xem',
'vector-view-viewsource'         => 'Xem mã nguồn',
'actions'                        => 'Tác vụ',
'namespaces'                     => 'Không gian tên',
'variants'                       => 'Biến thể',

'errorpagetitle'    => 'Lỗi',
'returnto'          => 'Quay lại $1.',
'tagline'           => 'Từ {{SITENAME}}',
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
'create'            => 'Tạo',
'editthispage'      => 'Sửa trang này',
'create-this-page'  => 'Tạo trang này',
'delete'            => 'Xóa',
'deletethispage'    => 'Xóa trang này',
'undelete_short'    => 'Phục hồi {{PLURAL:$1|một sửa đổi|$1 sửa đổi}}',
'protect'           => 'Khóa',
'protect_change'    => 'thay đổi',
'protectthispage'   => 'Khóa trang này',
'unprotect'         => 'Mở khóa',
'unprotectthispage' => 'Mở khóa trang này',
'newpage'           => 'Trang mới',
'talkpage'          => 'Thảo luận về trang này',
'talkpagelinktext'  => 'Thảo luận',
'specialpage'       => 'Trang đặc biệt',
'personaltools'     => 'Công cụ cá nhân',
'postcomment'       => 'Đề mục mới',
'articlepage'       => 'Xem trang nội dung',
'talk'              => 'Thảo luận',
'views'             => 'Xem',
'toolbox'           => 'Công cụ',
'userpage'          => 'Xem trang thành viên',
'projectpage'       => 'Xem trang dự án',
'imagepage'         => 'Xem trang tập tin',
'mediawikipage'     => 'Thông báo giao diện',
'templatepage'      => 'Trang bản mẫu',
'viewhelppage'      => 'Trang trợ giúp',
'categorypage'      => 'Trang thể loại',
'viewtalkpage'      => 'Trang thảo luận',
'otherlanguages'    => 'Ngôn ngữ khác',
'redirectedfrom'    => '(đổi hướng từ $1)',
'redirectpagesub'   => 'Trang đổi hướng',
'lastmodifiedat'    => 'Lần sửa cuối : $2, $1.',
'viewcount'         => 'Trang này đã được đọc {{PLURAL:$1|một|$1}} lần.',
'protectedpage'     => 'Trang bị khóa',
'jumpto'            => 'Bước tới:',
'jumptonavigation'  => 'chuyển hướng',
'jumptosearch'      => 'tìm kiếm',
'view-pool-error'   => 'Xin lỗi, máy chủ hiện đang bị quá tải.
Có quá nhiều thành viên đang cố gắng xem trang này.
Xin hãy đợi một lát rồi thử truy cập lại vào trang.

$1',
'pool-timeout'      => 'Hết thời gian chờ đợi khóa',
'pool-queuefull'    => 'Đầy hàng đợi khối ứng dụng (pool queue)',
'pool-errorunknown' => 'Lỗi lạ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Giới thiệu {{SITENAME}}',
'aboutpage'            => 'Project:Giới thiệu',
'copyright'            => 'Bản quyền $1.',
'copyrightpage'        => '{{ns:project}}:Bản quyền',
'currentevents'        => 'Thời sự',
'currentevents-url'    => 'Project:Thời sự',
'disclaimers'          => 'Phủ nhận',
'disclaimerpage'       => 'Project:Phủ nhận chung',
'edithelp'             => 'Trợ giúp sửa đổi',
'edithelppage'         => 'Help:Sửa đổi',
'helppage'             => 'Help:Nội dung',
'mainpage'             => 'Trang Chính',
'mainpage-description' => 'Trang Chính',
'policy-url'           => 'Project:Quy định và hướng dẫn',
'portal'               => 'Cộng đồng',
'portal-url'           => 'Project:Cộng đồng',
'privacy'              => 'Chính sách về sự riêng tư',
'privacypage'          => 'Project:Chính sách về sự riêng tư',

'badaccess'        => 'Lỗi về quyền truy cập',
'badaccess-group0' => 'Bạn không được phép thực hiện thao tác này.',
'badaccess-groups' => 'Chỉ những thành viên trong {{PLURAL:$2|nhóm|các nhóm}} $1 mới được thực hiện thao tác này.',

'versionrequired'     => 'Cần phiên bản $1 của MediaWiki',
'versionrequiredtext' => 'Cần phiên bản $1 của MediaWiki để sử dụng trang này. Xem [[Special:Version|trang phiên bản]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 – {{SITENAME}}',
'retrievedfrom'           => 'Lấy từ “$1”',
'youhavenewmessages'      => 'Bạn có $1 ($2).',
'newmessageslink'         => 'tin nhắn mới',
'newmessagesdifflink'     => 'thay đổi gần nhất',
'youhavenewmessagesmulti' => 'Bạn có tin nhắn mới ở $1',
'editsection'             => 'sửa',
'editold'                 => 'sửa',
'viewsourceold'           => 'xem mã nguồn',
'editlink'                => 'sửa đổi',
'viewsourcelink'          => 'xem mã nguồn',
'editsectionhint'         => 'Sửa đổi đề mục: $1',
'toc'                     => 'Mục lục',
'showtoc'                 => 'hiện',
'hidetoc'                 => 'ẩn',
'thisisdeleted'           => 'Xem hay phục hồi $1 ?',
'viewdeleted'             => 'Xem $1?',
'restorelink'             => '{{PLURAL:$1|một|$1}} sửa đổi đã xóa',
'feedlinks'               => 'Nạp:',
'feed-invalid'            => 'Định dạng feed không hợp lệ.',
'feed-unavailable'        => 'Website không cung cấp bản tin',
'site-rss-feed'           => '$1 mục RSS',
'site-atom-feed'          => '$1 mục Atom',
'page-rss-feed'           => 'Mục RSS của “$1”',
'page-atom-feed'          => 'Mục Atom của “$1”',
'red-link-title'          => '$1 (trang chưa được viết)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Nội dung',
'nstab-user'      => 'Thành viên',
'nstab-media'     => 'Phương tiện',
'nstab-special'   => 'Trang đặc biệt',
'nstab-project'   => 'Dự án',
'nstab-image'     => 'Tập tin',
'nstab-mediawiki' => 'Thông báo',
'nstab-template'  => 'Bản mẫu',
'nstab-help'      => 'Trợ giúp',
'nstab-category'  => 'Thể loại',

# Main script and global functions
'nosuchaction'      => 'Không có tác vụ này',
'nosuchactiontext'  => 'Wiki không hiểu được tác vụ được yêu cầu trong địa chỉ URL.
Có thể bạn đã gõ nhầm địa chỉ URL, hoặc nhấn vào một liên kết sai.
Nó cũng có thể là dấu hiệu của một lỗi trong phần mềm mà {{SITENAME}} sử dụng.',
'nosuchspecialpage' => 'Không có trang đặc biệt nào có tên này',
'nospecialpagetext' => '<strong>Bạn đã yêu cầu một trang đặc biệt không tồn tại.</strong>

Có danh sách trang đặc biệt tại [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Lỗi',
'databaseerror'        => 'Lỗi cơ sở dữ liệu',
'dberrortext'          => 'Đã xảy ra lỗi cú pháp trong truy vấn cơ sở dữ liệu.
Có vẻ như nguyên nhân của vấn đề này xuất phát từ một lỗi trong phần mềm.
Truy vấn vừa rồi là:
<blockquote><tt>$1</tt></blockquote>
từ hàm “<tt>$2</tt>”.
Cơ sở dữ liệu  báo lỗi “<tt>$3: $4</tt>”.',
'dberrortextcl'        => 'Đã xảy ra lỗi cú pháp trong truy vấn cơ sở dữ liệu.
Truy vấn vừa rồi là:
“$1”
từ hàm “$2”.
Cơ sở dữ liệu báo lỗi “$3: $4”',
'laggedslavemode'      => 'Cảnh báo: Trang có thể chưa được cập nhật.',
'readonly'             => 'Cơ sở dữ liệu bị khóa',
'enterlockreason'      => 'Nêu lý do khóa, cùng với thời hạn khóa',
'readonlytext'         => 'Cơ sở dữ liệu hiện đã bị khóa không nhận trang mới và các điều chỉnh khác, có lẽ để bảo trì cơ sở dữ liệu định kỳ, một thời gian ngắn nữa nó sẽ trở lại bình thường.

Người quản lý khóa nó đã đưa ra lời giải thích sau: $1',
'missing-article'      => 'Cơ sở dữ liệu không tìm thấy văn bản của trang lẽ ra phải có, trang      Normal   0               false   false   false      EN-US   X-NONE   X-NONE                                                     MicrosoftInternetExplorer4                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     “$1” $2.

Điều này thường xảy ra do nhấn vào liên kết khác biệt phiên bản đã quá lâu hoặc liên kết lịch sử của một trang đã bị xóa.

Nếu không phải lý do trên, có thể bạn đã gặp phải một lỗi của phần mềm.
Xin hãy báo nó cho một [[Special:ListUsers/sysop|bảo quản viên]], trong đó ghi lại địa chỉ URL.',
'missingarticle-rev'   => '(số phiên bản: $1)',
'missingarticle-diff'  => '(Khác: $1, $2)',
'readonly_lag'         => 'Cơ sở dữ liệu bị khóa tự động trong khi các máy chủ cập nhật thông tin của nhau.',
'internalerror'        => 'Lỗi nội bộ',
'internalerror_info'   => 'Lỗi nội bộ: $1',
'fileappenderrorread'  => 'Không đọc được “$1” trong việc bổ sung.',
'fileappenderror'      => 'Không thể nối “$1” vào “$2”.',
'filecopyerror'        => 'Không thể chép tập tin “$1” đến “$2”.',
'filerenameerror'      => 'Không thể đổi tên tập tin “$1” thành “$2”.',
'filedeleteerror'      => 'Không thể xóa tập tin “$1”.',
'directorycreateerror' => 'Không thể tạo được danh mục “$1”.',
'filenotfound'         => 'Không tìm thấy tập tin “$1”.',
'fileexistserror'      => 'Không thể ghi ra tập tin “$1”: tập tin đã tồn tại',
'unexpected'           => 'Không hiểu giá trị: “$1”=“$2”.',
'formerror'            => 'Lỗi: không gửi mẫu đi được.',
'badarticleerror'      => 'Không thể thực hiện được tác vụ như thế tại trang này.',
'cannotdelete'         => 'Không thể xóa trang hay tập tin “$1”. Có thể nó đã bị ai đó xóa rồi.',
'badtitle'             => 'Tựa trang sai',
'badtitletext'         => 'Tựa trang yêu cầu không đúng, rỗng, hoặc là một liên kết ngôn ngữ hoặc liên kết wiki sai. Nó có thể chứa một hoặc nhiều ký tự mà tựa trang không thể sử dụng.',
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
'editinginterface'     => "'''Lưu ý:''' Bạn đang sửa chữa một trang dùng để cung cấp thông báo giao diện cho phần mềm. Những thay đổi tại trang này sẽ ảnh hưởng đến giao diện của rất nhiều người dùng website này. Để dịch thuật, hãy xem xét sử dụng [http://translatewiki.net/wiki/Main_Page?setlang=vi translatewiki.net], dự án bản địa hóa của MediaWiki.",
'sqlhidden'            => '(đã giấu truy vấn SQL)',
'cascadeprotected'     => 'Trang này đã bị khóa không cho sửa đổi, vì nó được nhúng vào {{PLURAL:$1|trang|những trang}} đã bị khóa với tùy chọn “khóa theo tầng” được kích hoạt:
$2',
'namespaceprotected'   => "Bạn không có quyền sửa các trang trong không gian tên '''$1'''.",
'customcssjsprotected' => 'Bạn không có quyền sửa đổi trang này vì nó chứa các tùy chọn cá nhân của một thành viên khác.',
'ns-specialprotected'  => 'Không thể sửa chữa các trang trong không gian tên {{ns:special}}.',
'titleprotected'       => "Tựa đề này đã bị [[User:$1|$1]] khóa không cho tạo ra.
Lý do được cung cấp là ''$2''.",

# Virus scanner
'virus-badscanner'     => "Cấu hình sau: không nhận ra bộ quét virus: ''$1''",
'virus-scanfailed'     => 'quét thất bại (mã $1)',
'virus-unknownscanner' => 'không nhận ra phần mềm diệt virus:',

# Login and logout pages
'logouttext'                 => "'''Bạn đã đăng xuất.'''

Bạn có thể tiếp tục dùng {{SITENAME}} một cách vô danh, hoặc bạn có thể [[Special:UserLogin|đăng nhập lại]] dưới cùng tên người dùng này hoặc một tên người dùng khác. Xin lưu ý rằng một vài trang có thể vẫn hiển thị như khi bạn còn đăng nhập, cho đến khi bạn xóa vùng nhớ đệm (''cache'') của trình duyệt.",
'welcomecreation'            => '== Chào mừng, $1! ==
Tài khoản của bạn đã mở.
Đừng quên thay đổi [[Special:Preferences|tùy chọn cá nhân của bạn tại {{SITENAME}}]].',
'yourname'                   => 'Tên người dùng:',
'yourpassword'               => 'Mật khẩu:',
'yourpasswordagain'          => 'Gõ lại mật khẩu',
'remembermypassword'         => 'Nhớ thông tin đăng nhập của tôi trên máy tính này (cho đến $1 ngày)',
'securelogin-stick-https'    => 'Giữ kết nối với HTTPS sau khi đăng nhập',
'yourdomainname'             => 'Tên miền của bạn:',
'externaldberror'            => 'Có lỗi khi xác nhận cơ sở dữ liệu bên ngoài hoặc bạn không được phép cập nhật tài khoản bên ngoài.',
'login'                      => 'Đăng nhập',
'nav-login-createaccount'    => 'Đăng nhập / Mở tài khoản',
'loginprompt'                => 'Bạn cần bật cookie để đăng nhập vào {{SITENAME}}.',
'userlogin'                  => 'Đăng nhập / Mở tài khoản',
'userloginnocreate'          => 'Đăng nhập',
'logout'                     => 'Đăng xuất',
'userlogout'                 => 'Đăng xuất',
'notloggedin'                => 'Chưa đăng nhập',
'nologin'                    => "Bạn chưa có tài khoản ở đây? '''$1'''.",
'nologinlink'                => 'Mở một tài khoản',
'createaccount'              => 'Mở tài khoản',
'gotaccount'                 => "Đã mở tài khoản rồi? '''$1'''.",
'gotaccountlink'             => 'Đăng nhập',
'createaccountmail'          => 'qua thư điện tử',
'createaccountreason'        => 'Lý do:',
'badretype'                  => 'Hai mật khẩu không khớp.',
'userexists'                 => 'Tên người dùng này đã có người lấy.
Hãy chọn một tên khác.',
'loginerror'                 => 'Lỗi đăng nhập',
'createaccounterror'         => 'Không thể mở tài khoản: $1',
'nocookiesnew'               => 'Tài khoản đã mở, nhưng bạn chưa đăng nhập. {{SITENAME}} sử dụng cookie để đăng nhập vào tài khoản. Bạn đã tắt cookie. Xin hãy kích hoạt nó, rồi đăng nhập lại với tên người dùng và mật khẩu mới.',
'nocookieslogin'             => '{{SITENAME}} sử dụng cookie để đăng nhập thành viên. Bạn đã tắt cookie. Xin hãy kích hoạt rồi thử lại.',
'noname'                     => 'Chưa nhập tên.',
'loginsuccesstitle'          => 'Đăng nhập thành công',
'loginsuccess'               => "'''Bạn đã đăng nhập vào {{SITENAME}} với tên “$1”.'''",
'nosuchuser'                 => 'Không có thành viên nào có tên “$1”.
Tên người dùng có phân biệt chữ hoa chữ thường.
Hãy kiểm tra lại chính tả, hoặc [[Special:UserLogin/signup|mở tài khoản mới]].',
'nosuchusershort'            => 'Không có thành viên nào có tên “<nowiki>$1</nowiki>”. Xin hãy kiểm tra lại chính tả.',
'nouserspecified'            => 'Bạn phải đưa ra tên đăng ký.',
'login-userblocked'          => 'Thành viên này đã bị cấm. Không cho phép đăng nhập.',
'wrongpassword'              => 'Mật khẩu sai. Xin vui lòng nhập lại.',
'wrongpasswordempty'         => 'Bạn chưa gõ vào mật khẩu. Xin thử lần nữa.',
'passwordtooshort'           => 'Mật khẩu phải có ít nhất {{PLURAL:$1|1 ký tự|$1 ký tự}}.',
'password-name-match'        => 'Mật khẩu của bạn phải khác với tên người dùng của bạn.',
'password-too-weak'          => 'Không thể sử dụng mật khẩu được cung cấp vì nó quá yếu.',
'mailmypassword'             => 'Gửi mật khẩu mới qua thư điện tử',
'passwordremindertitle'      => 'Mật khẩu tạm thời cho {{SITENAME}}',
'passwordremindertext'       => 'Người nào đó (có thể là bạn, có địa chỉ IP $1) đã yêu cầu chúng tôi gửi cho bạn mật khẩu mới của {{SITENAME}} ($4). Mật khẩu tạm cho thành viên “$2” đã được khởi tạo là “$3”. Nếu bạn chính là người đã yêu cầu mật khẩu, bạn sẽ cần phải đăng nhập và thay đổi mật khẩu ngay bây giờ. Mật khẩu tạm sẽ hết hạn trong vòng {{PLURAL:$5|một ngày|$5 ngày}}.

Nếu bạn không phải là người yêu cầu gửi mật khẩu, hoặc nếu bạn đã nhớ ra mật khẩu gốc của mình và không còn muốn đổi nó nữa, bạn có thể bỏ qua bức thư này và tiếp tục sử dụng mật khẩu cũ của bạn.',
'noemail'                    => 'Thành viên “$1” không đăng ký thư điện tử.',
'noemailcreate'              => 'Bạn cần cung cấp một địa chỉ thư điện tử hợp lệ',
'passwordsent'               => 'Mật khẩu mới đã được gửi tới thư điện tử của thành viên “$1”. Xin đăng nhập lại sau khi nhận thư.',
'blocked-mailpassword'       => 'Địa chỉ IP của bạn bị cấm không được sửa đổi, do đó cũng không được phép dùng chức năng phục hồi mật khẩu để tránh lạm dụng.',
'eauthentsent'               => 'Thư xác nhận đã được gửi. Trước khi dùng chức năng nhận thư, bạn cần thực hiện hướng dẫn trong thư xác nhận, để đảm bảo tài khoản thuộc về bạn.',
'throttled-mailpassword'     => 'Mật khẩu đã được gửi đến cho bạn trong vòng {{PLURAL:$1|$1 giờ|$1 giờ}} đồng hồ trở lại. Để tránh lạm dụng, chỉ có thể gửi mật khẩu $1 giờ đồng hồ một lần.',
'mailerror'                  => 'Lỗi gửi thư : $1',
'acct_creation_throttle_hit' => 'Những người sử dụng địa chỉ IP này đã mở {{PLURAL:$1|1 tài khoản|$1 tài khoản}} trong vòng một ngày, và đó là số lượng tài khoản tối đa có thể mở trong ngày.
Vì vậy, người khác sử dụng địa chỉ IP này hiện không thể mở thêm tài khoản được nữa.',
'emailauthenticated'         => 'Địa chỉ thư điện tử của bạn được xác nhận vào lúc $3 $2.',
'emailnotauthenticated'      => 'Địa chỉ thư điện tử của bạn chưa được xác nhận. Chức năng thư điện tử chưa bật.',
'noemailprefs'               => 'Hãy ghi một địa chỉ thư điện tử trong tùy chọn cá nhân để có thể sử dụng tính năng này.',
'emailconfirmlink'           => 'Xác nhận địa chỉ thư điện tử',
'invalidemailaddress'        => 'Địa chỉ thư điện tử không được chấp nhận vì định dạng thư có vẻ sai.
Hãy nhập một địa chỉ có định dạng đúng hoặc bỏ trống ô đó.',
'accountcreated'             => 'Mở tài khoản thành công',
'accountcreatedtext'         => 'Tài khoản thành viên cho $1 đã được mở.',
'createaccount-title'        => 'Tài khoản mới tại {{SITENAME}}',
'createaccount-text'         => 'Ai đó đã tạo một tài khoản với tên $2 tại {{SITENAME}} ($4). Mật khẩu của "$2" là "$3". Bạn nên đăng nhập và đổi mật khẩu ngay bây giờ.

Xin hãy bỏ qua thông báo này nếu tài khoản này không phải do bạn tạo ra.',
'usernamehasherror'          => 'Tên người dùng không thể chứa dấu rào',
'login-throttled'            => 'Bạn đã thử quá nhiều mật khẩu của tài khoản này
Xin hãy đợi chốc lát rồi thử lại.',
'loginlanguagelabel'         => 'Ngôn ngữ: $1',
'suspicious-userlogout'      => 'Đã bỏ qua yêu cầu đăng xuất bạn, hình như được gửi từ trình duyệt hoặc máy proxy nhớ đệm hư.',

# E-mail sending
'php-mail-error-unknown' => 'Lỗi không rõ trong hàm PHP mail()',

# JavaScript password checks
'password-strength'            => 'Độ mạnh ước lượng của mật khẩu: $1',
'password-strength-bad'        => 'DỞ',
'password-strength-mediocre'   => 'xoàng',
'password-strength-acceptable' => 'được',
'password-strength-good'       => 'tốt',
'password-retype'              => 'Gõ lại mật khẩu tại đây',
'password-retype-mismatch'     => 'Mật khẩu không khớp',

# Password reset dialog
'resetpass'                 => 'Đổi mật khẩu',
'resetpass_announce'        => 'Bạn đã đăng nhập bằng mật khẩu tạm gởi qua e-mail. Để hoàn tất việc đăng nhập, bạn phải tạo lại mật khẩu mới tại đây:',
'resetpass_text'            => '<!-- Nhập văn bản vào đây -->',
'resetpass_header'          => 'Đổi mật khẩu cho tài khoản',
'oldpassword'               => 'Mật khẩu cũ:',
'newpassword'               => 'Mật khẩu mới:',
'retypenew'                 => 'Gõ lại:',
'resetpass_submit'          => 'Chọn mật khẩu và đăng nhập',
'resetpass_success'         => 'Đã đổi mật khẩu thành công! Đang đăng nhập…',
'resetpass_forbidden'       => 'Không được đổi mật khẩu',
'resetpass-no-info'         => 'Bạn phải đăng nhập mới có thể truy cập trực tiếp trang này.',
'resetpass-submit-loggedin' => 'Thay đổi mật khẩu',
'resetpass-submit-cancel'   => 'Hủy bỏ',
'resetpass-wrong-oldpass'   => 'Mật khẩu tạm hoặc mật khẩu hiện thời không hợp lệ.
Có thể bạn đã thay đổi thành công mật khẩu của mình hoặc đã yêu cầu cung cấp một mật khẩu tạm mới.',
'resetpass-temp-password'   => 'Mật khẩu tạm:',

# Edit page toolbar
'bold_sample'     => 'Chữ đậm',
'bold_tip'        => 'Chữ đậm',
'italic_sample'   => 'Chữ xiên',
'italic_tip'      => 'Chữ xiên',
'link_sample'     => 'Liên kết',
'link_tip'        => 'Liên kết',
'extlink_sample'  => 'http://www.example.com liên kết ngoài',
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
'summary'                          => 'Tóm lược:',
'subject'                          => 'Đề mục:',
'minoredit'                        => 'Sửa đổi nhỏ',
'watchthis'                        => 'Theo dõi trang này',
'savearticle'                      => 'Lưu trang',
'preview'                          => 'Xem thử',
'showpreview'                      => 'Xem thử',
'showlivepreview'                  => 'Xem thử nhanh',
'showdiff'                         => 'Xem thay đổi',
'anoneditwarning'                  => "'''Cảnh báo:''' Bạn chưa đăng nhập. Địa chỉ IP của bạn sẽ được ghi lại trong lịch sử sửa đổi của trang.",
'anonpreviewwarning'               => "''Bạn chưa đăng nhập. Lúc khi lưu trang này, địa chỉ IP của bạn sẽ được ghi vào lịch sử trang.''",
'missingsummary'                   => "'''Nhắc nhở:''' Bạn đã không ghi lại tóm lược sửa đổi. Nếu bạn nhấn Lưu trang một lần nữa, sửa đổi của bạn sẽ được lưu mà không có tóm lược.",
'missingcommenttext'               => 'Xin hãy gõ vào lời bàn luận ở dưới.',
'missingcommentheader'             => "'''Nhắc nhở:''' Bạn chưa ghi chủ đề/tiêu đề cho bàn luận này.
Nếu bạn nhấn nút \"{{int:savearticle}}\" lần nữa, sửa đổi của bạn sẽ được lưu mà không có đề mục.",
'summary-preview'                  => 'Xem trước dòng tóm lược:',
'subject-preview'                  => 'Xem trước đề mục:',
'blockedtitle'                     => 'Thành viên bị cấm',
'blockedtext'                      => "'''Tên người dùng hoặc địa chỉ IP của bạn đã bị cấm.'''

Người thực hiện cấm là $1.
Lý do được cung cấp là ''$2''.

* Bắt đầu cấm: $8
* Kết thúc cấm: $6
* Mục tiêu cấm: $7

Bạn có thể liên hệ với $1 hoặc một [[{{MediaWiki:Grouppage-sysop}}|bảo quản viên]] khác để thảo luận về việc cấm.
Bạn không thể sử dụng tính năng “gửi thư cho người này” trừ khi bạn đã đăng ký một địa chỉ thư điện tử hợp lệ trong [[Special:Preferences|tùy chọn tài khoản]] và bạn không bị khóa chức năng đó.
Địa chỉ IP hiện tại của bạn là $3, và mã số cấm là #$5.
Xin hãy ghi kèm tất cả các thông tin trên vào thư yêu cầu của bạn.",
'autoblockedtext'                  => "Địa chỉ IP của bạn đã bị tự động cấm vì một người nào đó đã sử dụng nó, $1 là thành viên đã thực hiện cấm.
Lý do được cung cấp là:

:''$2''

* Bắt đầu cấm: $8
* Kết thúc cấm: $6
* Mục tiêu cấm: $7

Bạn có thể liên hệ với $1 hoặc một trong số các
[[{{MediaWiki:Grouppage-sysop}}|bảo quản viên]] khác để thảo luận về việc cấm.

Chú ý rằng bạn sẽ không dùng được chức năng “gửi thư cho người này” trừ khi bạn đã đăng ký một địa chỉ thư điện tử hợp lệ trong [[Special:Preferences|tùy chọn]] và bạn không bị cấm dùng chức năng đó.

Địa chỉ IP hiện tại của bạn là $3, mã số cấm là $5.
Xin hãy ghi kèm tất cả các chi tiết trên vào thư yêu cầu của bạn.",
'blockednoreason'                  => 'không đưa ra lý do',
'blockedoriginalsource'            => "Mã nguồn của '''$1''':",
'blockededitsource'                => "Các '''sửa đổi của bạn''' ở '''$1''':",
'whitelistedittitle'               => 'Cần đăng nhập để sửa trang',
'whitelistedittext'                => 'Bạn phải $1 để sửa trang.',
'confirmedittext'                  => 'Bạn cần phải xác nhận địa chỉ thư điện tử trước khi được sửa đổi trang. Xin hãy đặt và xác nhận địa chỉ thư điện tử của bạn dùng trang [[Special:Preferences|tùy chọn]].',
'nosuchsectiontitle'               => 'Không tìm thấy đề mục',
'nosuchsectiontext'                => 'Bạn vừa sửa đổi một mục chưa tồn tại.
Có thể nó đã bị di chuyển hoặc xóa đi trong khi bạn đang xem trang.',
'loginreqtitle'                    => 'Cần đăng nhập',
'loginreqlink'                     => 'đăng nhập',
'loginreqpagetext'                 => 'Bạn phải $1 mới có quyền xem các trang khác.',
'accmailtitle'                     => 'Đã gửi mật khẩu.',
'accmailtext'                      => "Một mật khẩu được tạo ngẫu nhiên cho [[User talk:$1|$1]] đã được gửi đến $2.

Có thể đổi mật khẩu cho tài khoản mới này tại trang ''[[Special:ChangePassword|đổi mật khẩu]]'' sau khi đã đăng nhập.",
'newarticle'                       => '(Mới)',
'newarticletext'                   => "Bạn đi đến đây từ một liên kết đến một trang chưa tồn tại. Để tạo trang, hãy bắt đầu gõ vào ô bên dưới (xem [[{{MediaWiki:Helppage}}|trang trợ giúp]] để có thêm thông tin). Nếu bạn đến đây do nhầm lẫn, chỉ cần nhấn vào nút '''Back''' trên trình duyệt của bạn.",
'anontalkpagetext'                 => "----''Đây là trang thảo luận của một thành viên vô danh chưa tạo tài khoản hoặc có tài khoản nhưng không đăng nhập.
Do đó chúng ta phải dùng một dãy số gọi là địa chỉ IP để xác định anh/chị ta.
Một địa chỉ IP như vậy có thể có nhiều người cùng dùng chung.
Nếu bạn là một thành viên vô danh và cảm thấy rằng có những lời bàn luận không thích hợp đang nhắm vào bạn, xin hãy [[Special:UserLogin/signup|tạo tài khoản]] hoặc [[Special:UserLogin|đăng nhập]] để tránh sự nhầm lẫn về sau với những thành viên vô danh khác.''",
'noarticletext'                    => 'Trang này hiện chưa có nội dung.
Bạn có thể [[Special:Search/{{PAGENAME}}|tìm kiếm tựa trang này]] trong các trang khác, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tìm trong các nhật trình liên quan],
hoặc [{{fullurl:{{FULLPAGENAME}}|action=edit}} sửa đổi trang này]</span>.',
'noarticletext-nopermission'       => 'Trang này hiện đang trống.
Bạn có thể [[Special:Search/{{PAGENAME}}|tìm kiếm tựa trang này]] tại các trang khác,
hoặc <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tìm kiếm các nhật trình liên quan]</span>.',
'userpage-userdoesnotexist'        => 'Tài khoản mang tên “$1” chưa được đăng ký. Xin hãy kiểm tra lại nếu bạn muốn tạo/sửa trang này.',
'userpage-userdoesnotexist-view'   => 'Tài khoản “$1” chưa được đăng ký.',
'blocked-notice-logextract'        => 'Người dùng này hiện đang bị cấm sửa đổi. Nhật trình cấm gần nhất được ghi ở dưới để tiện theo dõi:',
'clearyourcache'                   => "'''Ghi chú&nbsp;– Sau khi lưu trang, có thể bạn sẽ phải xóa bộ nhớ đệm của trình duyệt để xem các thay đổi.''' '''Mozilla / Firefox / Safari:''' giữ phím ''Shift'' trong khi nhấn ''Reload'' (''Tải lại''), hoặc nhấn tổ hợp ''Ctrl-F5'' hay ''Ctrl-R'' (<span title=\"Command\">⌘</span>''R'' trên Macintosh); '''Konqueror:''' nhấn nút ''Reload'' hoặc nhấn ''F5''; '''Opera:''' xóa bộ nhớ đệm trong ''Tools → Preferences''; '''Internet Explorer:''' giữ phím ''Ctrl'' trong khi nhấn ''Refresh'', hoặc nhấn tổ hợp ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Mẹo:''' Sử dụng nút “{{int:showpreview}}” để kiểm thử bản CSS của bạn trước khi lưu trang.",
'userjsyoucanpreview'              => "'''Mẹo:''' Sử dụng nút “{{int:showpreview}}” để kiểm thử bản JS của bạn trước khi lưu trang.",
'usercsspreview'                   => "'''Hãy nhớ rằng bạn chỉ đang xem thử trang CSS cá nhân của bạn.
Nó chưa được lưu!'''",
'userjspreview'                    => "'''Nhớ rằng bạn chỉ đang kiểm thử/xem thử trang JavaScript, nó chưa được lưu!'''",
'userinvalidcssjstitle'            => "'''Cảnh báo:''' Không có skin “$1”. Hãy nhớ rằng các trang .css và .js tùy chỉnh sử dụng tiêu đề chữ thường, như {{ns:user}}:Ví&nbsp;dụ/vector.css chứ không phải {{ns:user}}:Ví&nbsp;dụ/Vector.css.",
'updated'                          => '(Cập nhật)',
'note'                             => "'''Ghi chú:'''",
'previewnote'                      => "'''Đây chỉ mới là xem thử; các thay đổi vẫn chưa được lưu!'''",
'previewconflict'                  => 'Phần xem thử này là kết quả của văn bản trong vùng soạn thảo phía trên và nó sẽ xuất hiện như vậy nếu bạn chọn lưu trang.',
'session_fail_preview'             => "'''Những sửa đổi của bạn chưa được lưu giữ do mất dữ liệu về phiên làm việc.
Xin hãy thử lần nữa.
Nếu vẫn không thành công, hãy thử [[Special:UserLogout|đăng xuất]] rồi đăng nhập lại.'''",
'session_fail_preview_html'        => "'''Những sửa đổi của bạn chưa được lưu giữ do mất dữ liệu về phiên làm việc.'''

''Do {{SITENAME}} cho phép dùng mã HTML, trang xem thử được ẩn đi để đề phòng bị tấn công bằng JavaScript.''

'''Nếu sửa đổi này là đúng đắn, xin hãy thử lần nữa.
Nếu vẫn không thành công, bạn hãy thử [[Special:UserLogout|đăng xuất]] rồi đăng nhập lại.'''",
'token_suffix_mismatch'            => "'''Sửa đổi của bạn bị hủy bỏ vì trình duyệt của bạn lẫn lộn các ký tự dấu trong số hiệu
sửa đổi. Việc hủy bỏ này nhằm tránh nội dung trang bị hỏng.
Điều này thường xảy ra khi bạn sử dụng một dịch vụ proxy vô danh trên web có vấn đề.'''",
'editing'                          => 'Sửa đổi $1',
'editingsection'                   => 'Sửa đổi $1 (đề mục)',
'editingcomment'                   => 'Sửa đổi $1 (đề mục mới)',
'editconflict'                     => 'Sửa đổi mâu thuẫn: $1',
'explainconflict'                  => "Trang này có đã được lưu bởi người khác sau khi bạn bắt đầu sửa.
Phía trên là bản hiện tại.
Phía dưới là sửa đổi của bạn.
Bạn sẽ phải trộn thay đổi của bạn với bản hiện tại.
'''Chỉ có''' phần văn bản ở phía trên là sẽ được lưu khi bạn nhất nút “{{int:savearticle}}”.",
'yourtext'                         => 'Nội dung bạn nhập',
'storedversion'                    => 'Phiên bản lưu',
'nonunicodebrowser'                => "'''CHU' Y': Tri`nh duye^.t cu?a ba.n kho^ng ho^~ tro+. unicode. Mo^.t ca'ch dde^? ba.n co' the^? su+?a ddo^?i an toa`n trang na`y: ca'c ky' tu+. kho^ng pha?i ASCII se~ xua^'t hie^.n trong ho^.p soa.n tha?o du+o+'i da.ng ma~ tha^.p lu.c pha^n.'''",
'editingold'                       => "'''Chú ý: bạn đang sửa một phiên bản cũ. Nếu bạn lưu, các sửa đổi trên các phiên bản mới hơn sẽ bị mất.'''",
'yourdiff'                         => 'Khác',
'copyrightwarning'                 => "Xin chú ý rằng tất cả các đóng góp của bạn tại {{SITENAME}} được xem là sẽ phát hành theo giấy phép $2 (xem $1 để biết thêm chi tiết). Nếu bạn không muốn trang của bạn bị sửa đổi không thương tiếc và không sẵn lòng cho phép phát hành lại, đừng đăng trang ở đây.<br />
Bạn phải đảm bảo với chúng tôi rằng chính bạn là người viết nên, hoặc chép nó từ một nguồn thuộc phạm vi công cộng hoặc tự do tương đương.
'''ĐỪNG ĐĂNG TÁC PHẨM CÓ BẢN QUYỀN MÀ CHƯA XIN PHÉP!'''",
'copyrightwarning2'                => "Xin chú ý rằng tất cả các đóng góp của bạn tại {{SITENAME}} có thể được sửa đổi, thay thế, hoặc xóa bỏ bởi các thành viên khác. Nếu bạn không muốn trang của bạn bị sửa đổi không thương tiếc, đừng đăng trang ở đây.<br />
Bạn phải đảm bảo với chúng tôi rằng chính bạn là người viết nên, hoặc chép nó từ một nguồn thuộc phạm vi công cộng hoặc tự do tương đương (xem $1 để biết thêm chi tiết).
'''ĐỪNG ĐĂNG TÁC PHẨM CÓ BẢN QUYỀN MÀ CHƯA XIN PHÉP!'''",
'longpageerror'                    => "'''LỖI: Văn bạn mà bạn muốn lưu dài $1 kilobyte, dài hơn độ dài tối đa cho phép $2 kilobyte. Không thể lưu trang.'''",
'readonlywarning'                  => "'''CẢNH BÁO: Cơ sở dữ liệu đã bị khóa để bảo dưỡng, do đó bạn không thể lưu các sửa đổi của mình. Bạn nên cắt-dán đoạn bạn vừa sửa vào một tập tin và lưu nó lại để sửa đổi sau này.'''

Người quản lý khi khóa dữ liệu đã đưa ra lý do: $1",
'protectedpagewarning'             => "'''Cảnh báo: Trang này đã bị khóa và chỉ có các thành viên có quyền quản lý mới có thể sửa được.'''
Thông tin mới nhất trong nhật trình được ghi dưới đây để tiện theo dõi:",
'semiprotectedpagewarning'         => "'''Lưu ý:''' Trang này đã bị khóa và chỉ có các thành viên đã đăng ký mới có thể sửa đổi được.
Thông tin mới nhất trong nhật trình được ghi dưới đây để tiện theo dõi:",
'cascadeprotectedwarning'          => "'''Cảnh báo:''' Trang này đã bị khóa, chỉ có thành viên có quyền quản lý mới có thể sửa đổi được, vì nó được nhúng vào {{PLURAL:$1|trang|những trang}} bị khóa theo tầng sau:",
'titleprotectedwarning'            => "'''Cảnh báo:  Trang này đã bị khóa và bạn phải có một số [[Special:ListGroupRights|quyền nhất định]] mới có thể tạo trang.'''
Thông tin mới nhất trong nhật trình được ghi dưới đây để tiện theo dõi:",
'templatesused'                    => '{{PLURAL:$1|Bản mẫu|Các bản mẫu}} dùng trong trang này:',
'templatesusedpreview'             => '{{PLURAL:$1|Bản mẫu|Các bản mẫu}} sẽ được dùng trong trang này:',
'templatesusedsection'             => '{{PLURAL:$1|Bản mẫu|Các bản mẫu}} dùng trong phần này:',
'template-protected'               => '(khóa hoàn toàn)',
'template-semiprotected'           => '(bị hạn chế sửa đổi)',
'hiddencategories'                 => 'Trang này thuộc về {{PLURAL:$1|1 thể loại ẩn|$1 thể loại ẩn}}:',
'edittools'                        => '<!-- Văn bản dưới đây sẽ xuất hiện phía dưới mẫu sửa đổi và tải lên. -->',
'nocreatetitle'                    => 'Khả năng tạo trang bị hạn chế',
'nocreatetext'                     => '{{SITENAME}} đã hạn chế khả năng tạo trang mới.
Bạn có thể quay trở lại và sửa đổi các trang đã có, hoặc [[Special:UserLogin|đăng nhập hoặc tạo tài khoản]].',
'nocreate-loggedin'                => 'Bạn không có quyền tạo trang mới.',
'sectioneditnotsupported-title'    => 'Không hỗ trợ sửa đổi đề mục',
'sectioneditnotsupported-text'     => 'Trang sửa đổi này không hỗ trợ sửa đổi đề mục.',
'permissionserrors'                => 'Không có quyền thực hiện',
'permissionserrorstext'            => 'Bạn không có quyền thực hiện thao tác đó, vì {{PLURAL:$1|lý do|lý do}}:',
'permissionserrorstext-withaction' => 'Bạn không quyền $2, với {{PLURAL:$1|lý do|lý do}} sau:',
'recreate-moveddeleted-warn'       => "'''Cảnh báo: Bạn sắp tạo lại một trang từng bị xóa trước đây.'''

Bạn nên cân nhắc trong việc tiếp tục soạn thảo trang này.
Các nhật trình xóa và di chuyển của trang được đưa ra dưới đây để tiện theo dõi:",
'moveddeleted-notice'              => 'Trang này đã bị xóa.
Các nhật trình xóa và di chuyển của trang được đưa ra dưới đây để tiện theo dõi.',
'log-fulllog'                      => 'Xem nhật trình đầy đủ',
'edit-hook-aborted'                => 'Một phần bổ trợ phần mềm đã bỏ qua sửa đổi này.
Không có lý do nào được đưa ra.',
'edit-gone-missing'                => 'Không thể cập nhật trang.
Dường như trang này đã bị xóa.',
'edit-conflict'                    => 'Sửa đổi mâu thuẫn.',
'edit-no-change'                   => 'Sửa đổi của bạn không được tính đến, vì nó không làm thay đổi nội dung.',
'edit-already-exists'              => 'Không thể tạo trang mới.
Nó đã tồn tại.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Cảnh báo: Trang này có quá nhiều lần gọi hàm cú pháp cần mức độ xử lý cao.

Nó nên ít hơn $2 {{PLURAL:$2|lần gọi|lần gọi}}, hiện giờ đang là {{PLURAL:$1|$1 lần gọi|$1 lần gọi}}.',
'expensive-parserfunction-category'       => 'Trang có quá nhiều lời gọi hàm cú pháp cần mức độ xử lý cao',
'post-expand-template-inclusion-warning'  => 'Cảnh báo: Kích thước bản mẫu nhúng vào quá lớn.
Một số bản mẫu sẽ không được đưa vào.',
'post-expand-template-inclusion-category' => 'Những trang có kích thước bản mẫu nhúng vào vượt quá giới hạn cho phép',
'post-expand-template-argument-warning'   => 'Cảnh báo: Trang này có chứa ít nhất một giá trị bản mẫu có kích thước bung ra quá lớn.
Những giá trị này sẽ bị bỏ đi.',
'post-expand-template-argument-category'  => 'Những trang có chứa những giá trị bản mẫu bị loại bỏ',
'parser-template-loop-warning'            => 'Phát hiện bản mẫu lặp vòng: [[$1]]',
'parser-template-recursion-depth-warning' => 'Bản mẫu đã vượt quá giới hạn về độ sâu đệ quy ($1)',
'language-converter-depth-warning'        => 'Đã vượt quá giới hạn độ sâu của bộ chuyển đổi ngôn ngữ ($1)',

# "Undo" feature
'undo-success' => 'Các sửa đổi có thể được lùi lại. Xin hãy kiểm tra phần so sánh bên dưới để xác nhận lại những gì bạn muốn làm, sau đó lưu thay đổi ở dưới để hoàn tất việc lùi lại sửa đổi.',
'undo-failure' => 'Sửa đổi không thể phục hồi vì đã có những sửa đổi mới ở sau.',
'undo-norev'   => 'Sửa đổi không thể hồi phục vì nó không tồn tại hoặc đã bị xóa.',
'undo-summary' => 'Đã lùi lại sửa đổi $1 của [[Special:Contributions/$2|$2]] ([[User talk:$2|Thảo luận]])',

# Account creation failure
'cantcreateaccounttitle' => 'Không thể mở tài khoản',
'cantcreateaccount-text' => "Chức năng tài tạo khoản từ địa chỉ IP này ('''$1''') đã bị [[User:$3|$3]] cấm.

Lý do được $3 đưa ra là ''$2''",

# History pages
'viewpagelogs'           => 'Xem nhật trình của trang này',
'nohistory'              => 'Trang này chưa có lịch sử.',
'currentrev'             => 'Bản hiện tại',
'currentrev-asof'        => 'Bản hiện tại lúc $1',
'revisionasof'           => 'Phiên bản lúc $1',
'revision-info'          => 'Phiên bản vào lúc $1 do $2 sửa đổi',
'previousrevision'       => '← Phiên bản cũ',
'nextrevision'           => 'Phiên bản mới →',
'currentrevisionlink'    => 'xem phiên bản hiện hành',
'cur'                    => 'hiện',
'next'                   => 'tiếp',
'last'                   => 'trước',
'page_first'             => 'đầu',
'page_last'              => 'cuối',
'histlegend'             => 'Chọn so sánh: đánh dấu để chọn các phiên bản để so sánh rồi nhấn enter hoặc nút ở dưới.<br />
Chú giải: (hiện) = khác với phiên bản hiện hành,
(trước) = khác với phiên bản trước, n = sửa đổi nhỏ.',
'history-fieldset-title' => 'Tìm trong lịch sử',
'history-show-deleted'   => 'Chỉ bị xóa',
'histfirst'              => 'Cũ nhất',
'histlast'               => 'Mới nhất',
'historysize'            => '({{PLURAL:$1|1 byte|$1 byte}})',
'historyempty'           => '(trống)',

# Revision feed
'history-feed-title'          => 'Lịch sử thay đổi',
'history-feed-description'    => 'Lịch sử thay đổi của trang này ở wiki',
'history-feed-item-nocomment' => '$1 vào lúc $2',
'history-feed-empty'          => 'Trang bạn yêu cầu không tồn tại. Có thể là nó đã bị xóa khỏi wiki hay được đổi tên. Hãy [[Special:Search|tìm kiếm trong wiki]] về các trang mới có liên quan.',

# Revision deletion
'rev-deleted-comment'         => '(bàn luận đã bị xóa)',
'rev-deleted-user'            => '(tên người dùng đã bị xóa)',
'rev-deleted-event'           => '(tác vụ nhật trình đã bị xóa)',
'rev-deleted-user-contribs'   => '[tên người dùng hay địa chỉ IP bị ẩn – sửa đổi được ẩn khỏi danh sách đóng góp]',
'rev-deleted-text-permission' => "Phiên bản này đã bị '''xóa'''.
Có thể có thêm chi tiết tại [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} nhật trình xóa].",
'rev-deleted-text-unhide'     => "Phiên bản này đã bị '''xóa'''.
Có thể có thêm chi tiết tại [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} nhật trình xóa].
Vì là người quản lý, bạn vẫn có thể [$1 xem phiên bản này] nếu muốn.",
'rev-suppressed-text-unhide'  => "Phiên bản này đã bị '''giấu'''.
Có thể có thêm chi tiết tại [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} nhật trình giấu].
Vì là người quản lý, bạn vẫn có thể [$1 xem phiên bản này] nếu muốn.",
'rev-deleted-text-view'       => "Phiên bản này đã bị '''xóa'''.
Vì là người quản lý bạn vẫn có thể xem nó; có thể có thêm chi tiết tại [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} nhật trình xóa].",
'rev-suppressed-text-view'    => "Phiên bản này đã bị '''giấu'''.
Vì là người quản lý bạn vẫn có thể xem nó; có thể có thêm chi tiết tại [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} nhật trình giấu].",
'rev-deleted-no-diff'         => "Bạn không thể xem khác biệt giữa các phiên bản vì một phiên bản đã bị '''xóa'''.
Bạn có thể xem thêm chi tiết tại [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} nhật trình xóa].",
'rev-suppressed-no-diff'      => "Bạn không thể xem khác biệt vì một trong hai phiên bản đã bị '''xóa'''.",
'rev-deleted-unhide-diff'     => "Một trong những phiên bản của khác biệt này đã bị '''xóa'''.
Bạn có thể xem thêm chi tiết trong [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} nhật trình xóa].
Vì là người quản lý, bạn vẫn có thể [$1 xem khác biệt này] nếu muốn.",
'rev-suppressed-unhide-diff'  => "Một trong các phiên bản trong lần so sánh này đã được '''ẩn giấu'''.
Bạn có thể xem chi tiết trong [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} nhật trình ẩn giấu].
Là người quản lý bạn vẫn có thể [$1 so sánh khác biệt] nếu bạn muốn.",
'rev-deleted-diff-view'       => "Một trong những phiên bản trong khác biệt này đã bị '''xóa'''.
Là người quản lý bạn vẫn có thể xem khác biệt này; có thể xem chi tiết trong [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} nhật trình xóa].",
'rev-suppressed-diff-view'    => "Trong trong những phiên bản trong khác biệt này đã bị '''ẩn giấu'''.
Là người quản lý bạn vẫn có thể xem khác biệt này; có thể xem chi tiết trong [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} nhật trình ẩn giấu].",
'rev-delundel'                => 'hiện/ẩn',
'rev-showdeleted'             => 'hiện',
'revisiondelete'              => 'Xóa hay phục hồi phiên bản',
'revdelete-nooldid-title'     => 'Chưa chọn phiên bản',
'revdelete-nooldid-text'      => 'Có thể bạn chưa xác định (các) phiên bản đích để thực hiện tác vụ,
hoặc phiên bản đích không tồn tại,
hoặc bạn đang tìm cách ẩn phiên bản hiện tại.',
'revdelete-nologtype-title'   => 'Chưa cung cấp kiểu nhật trình',
'revdelete-nologtype-text'    => 'Bạn chưa chỉ định một kiểu nhật trình mà tác vụ này sẽ ghi vào.',
'revdelete-nologid-title'     => 'Mục nhật trình không hợp lệ',
'revdelete-nologid-text'      => 'Bạn chưa chỉ định sự kiện nhật trình mục tiêu mà chức năng này ghi vào hoặc mục nhật trình chỉ định không tồn tại.',
'revdelete-no-file'           => 'Tập tin chỉ định không tồn tại.',
'revdelete-show-file-confirm' => 'Bạn có chắc muốn xem phiên bản đã bị xóa của tập tin “<nowiki>$1</nowiki>” từ ngày $2 vào lúc $3?',
'revdelete-show-file-submit'  => 'Có',
'revdelete-selected'          => "'''{{PLURAL:$2|Phiên bản|Các phiên bản}} được chọn của [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Nhật trình đã chọn|Các nhật trình đã chọn}}:'''",
'revdelete-text'              => "'''Các phiên bản và sự kiện bị xóa sẽ vẫn xuất hiện trong lịch sử trang và nhật trình, nhưng mọi người sẽ không xem được một số phần của các nội dung đó.'''
Các quản lý khác ở {{SITENAME}} vẫn có thể truy nhập vào nội dung ẩn và phục hồi lại bằng cách dùng giao diện này, trừ trường hợp thiết lập thêm một số hạn chế.",
'revdelete-confirm'           => 'Xin hãy xác nhận rằng bạn có ý định xóa, nhận biết tầm quan trọng của việc này, và việc xóa tuân theo [[{{MediaWiki:Policy-url}}|quy định]].',
'revdelete-suppress-text'     => "Việc ẩn giấu '''chỉ''' nên dùng trong các trường hợp sau:
* Thông tin cá nhân không thích hợp
*: ''địa chỉ nhà và số điện thoại, số an sinh xã hội, v.v.''",
'revdelete-legend'            => 'Thiết lập hạn chế khả kiến',
'revdelete-hide-text'         => 'Ẩn nội dung phiên bản',
'revdelete-hide-image'        => 'Ẩn nội dung tập tin',
'revdelete-hide-name'         => 'Ẩn tác vụ và đích của tác vụ',
'revdelete-hide-comment'      => 'Ẩn tóm lược sửa đổi',
'revdelete-hide-user'         => 'Ẩn tên người dùng hay địa chỉ IP của người viết trang',
'revdelete-hide-restricted'   => 'Ẩn giấu thông tin khỏi các Quản lý lẫn thành viên khác',
'revdelete-radio-same'        => '(không đổi)',
'revdelete-radio-set'         => 'Có',
'revdelete-radio-unset'       => 'Không',
'revdelete-suppress'          => 'Che dữ liệu đối với người quản lý cũng như các thành viên khác',
'revdelete-unsuppress'        => 'Bỏ các hạn chế trên các phiên bản được phục hồi',
'revdelete-log'               => 'Lý do:',
'revdelete-submit'            => 'Áp dụng vào {{PLURAL:$1|phiên bản|các phiên bản}} được chọn',
'revdelete-logentry'          => 'đã thay đổi khả năng nhìn thấy phiên bản của [[$1]]',
'logdelete-logentry'          => 'đã thay đổi khả năng nhìn thấy sự kiện của [[$1]]',
'revdelete-success'           => "'''Đã cập nhật thành công độ khả kiến của phiên bản.'''",
'revdelete-failure'           => "'''Không thể cập nhật khả năng hiển thị của phiên bản:'''
$1",
'logdelete-success'           => "'''Khả năng nhìn thấy của sự kiện đã được thiết lập thành công.'''",
'logdelete-failure'           => "'''Không thể thiết lập khả năng hiện thị của nhật trình:'''
$1",
'revdel-restore'              => 'Thay đổi mức khả kiến',
'revdel-restore-deleted'      => 'các phiên bản xóa',
'revdel-restore-visible'      => 'các phiên bản được hiện',
'pagehist'                    => 'Lịch sử trang',
'deletedhist'                 => 'Lịch sử đã xóa',
'revdelete-content'           => 'nội dung',
'revdelete-summary'           => 'tóm lược sửa đổi',
'revdelete-uname'             => 'tên người dùng',
'revdelete-restricted'        => 'áp dụng hạn chế này cho sysop',
'revdelete-unrestricted'      => 'gỡ bỏ hạn chế này cho sysop',
'revdelete-hid'               => 'đã ẩn $1',
'revdelete-unhid'             => 'đã hiện $1',
'revdelete-log-message'       => '$2 {{PLURAL:$2|phiên bản|phiên bản}} được $1',
'logdelete-log-message'       => '$1 của $2 {{PLURAL:$2|sự kiện|sự kiện}}',
'revdelete-hide-current'      => 'Xảy ra lỗi khi ẩn mục ghi lúc $2, $1: đây là phiên bản hiện tại.
Nó không ẩn đi được.',
'revdelete-show-no-access'    => 'Có lỗi khi hiện mục ghi lúc $2, $1: mục này đã được đánh dấu “hạn chế”.
Bạn không có đủ quyền truy cập nó.',
'revdelete-modify-no-access'  => 'Có lỗi khi sửa mục vào lúc $2, $1: mục này đã được đánh dấu “hạn chế”.
Bạn không có đủ quyền để truy cập nó.',
'revdelete-modify-missing'    => 'Có lỗi khi sửa mục có mã số $1: không tìm thấy trong cơ sở dữ liệu!',
'revdelete-no-change'         => "'''Cảnh báo:''' mục được ghi vào lúc $2, $1 đã được yêu cầu thiết lập hiển thị.",
'revdelete-concurrent-change' => 'Có lỗi khi sửa mục ghi vào lúc $2, $1: trạng thái của nó dường như đã được ai khác sửa đổi trong khi bạn đang sửa.
Xin hãy kiểm tra nhật trình.',
'revdelete-only-restricted'   => 'Có lỗi khi ẩn mục vào $2, $1: nếu ẩn mục để cho bảo quản viên khỏi nhìn thấy được thì cũng cần chọn một trong những tùy chọn ẩn khác.',
'revdelete-reason-dropdown'   => '*Các lý do thường gặp khi xóa
** Vi phạm bản quyền
** Thông tin cá nhân không thích hợp',
'revdelete-otherreason'       => 'Lý do khác/bổ sung:',
'revdelete-reasonotherlist'   => 'Lý do khác',
'revdelete-edit-reasonlist'   => 'Sửa lý do xóa',
'revdelete-offender'          => 'Tác giả phiên bản:',

# Suppression log
'suppressionlog'     => 'Nhật trình ẩn giấu',
'suppressionlogtext' => 'Dưới đây là danh sách các tác vụ xóa và cấm liên quan đến nội dung mà các quản lý không nhìn thấy.
Xem [[Special:IPBlockList|danh sách các IP bị cấm]] để xem danh sách các tác vụ cấm chỉ và cấm thông thường hiện nay.',

# Revision move
'moverevlogentry'              => 'di chuyển {{PLURAL:$3|phiên bản|$3 phiên bản}} từ $1 đến $2',
'revisionmove'                 => 'Di chuyển phiên bản từ “$1”',
'revmove-explain'              => 'Các phiên bản sau sẽ được di chuyển từ $1 đến trang mục tiêu được chọn. Nếu trang mục tiêu tồn tại, các phiên bản này sẽ được hợp nhất vào lịch sử của trang mục từ; không thì trang mục tiêu sẽ được tạo ra.',
'revmove-legend'               => 'Đặt trang mục tiêu và tóm lược',
'revmove-submit'               => 'Di chuyển các phiên bản đến trang lựa chọn',
'revisionmoveselectedversions' => 'Di chuyển các phiên bản được chọn',
'revmove-reasonfield'          => 'Lý do:',
'revmove-titlefield'           => 'Trang mục tiêu:',
'revmove-badparam-title'       => 'Tham số hỏng',
'revmove-badparam'             => 'Yêu cầu của bạn không đủ tham số hoặc có tham số không hợp lệ. Xin hãy bấm “Lùi” và thử lại.',
'revmove-norevisions-title'    => 'Phiên bản mục tiêu không hợp lệ',
'revmove-norevisions'          => 'Chưa chọn ít nhất một phiên bản mục tiêu để thực hiện tác vụ này, hoặc phiên bản được chọn không tồn tại.',
'revmove-nullmove-title'       => 'Tựa trang sai',
'revmove-nullmove'             => 'Không thể di chuyển phiên bản từ một trang đến cùng trang. Xin hãy bấm “Lùi” và đổi “[[$1]]” thành tên trang khác.',
'revmove-success-existing'     => '{{PLURAL:$1|Một phiên bản|$1 phiên bản}} [[$2]] đã được di chuyển đến trang tồn tại [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Một phiên bản|$1 phiên bản}} [[$2]] đã được di chuyển đên trang mới [[$3]].',

# History merging
'mergehistory'                     => 'Trộn lịch sử trang',
'mergehistory-header'              => 'Trang này cho phép trộn các sửa đổi trong lịch sử của một trang nguồn vào một trang mới hơn.
Xin hãy bảo đảm giữ vững tính liên tục của lịch sử trang.',
'mergehistory-box'                 => 'Trộn các sửa đổi của hai trang:',
'mergehistory-from'                => 'Trang nguồn:',
'mergehistory-into'                => 'Trang đích:',
'mergehistory-list'                => 'Lịch sử sửa đổi có thể trộn được',
'mergehistory-merge'               => 'Các sửa đổi sau của [[:$1]] có thể trộn được với [[:$2]]. Dùng một nút chọn trong cột để trộn các sửa đổi từ đầu cho đến thời điểm đã chọn. Lưu ý là việc dùng các liên kết chuyển hướng sẽ khởi tạo lại cột này.',
'mergehistory-go'                  => 'Hiển thị các sửa đổi có thể trộn được',
'mergehistory-submit'              => 'Trộn các sửa đổi',
'mergehistory-empty'               => 'Không thể trộn được sửa đổi nào.',
'mergehistory-success'             => '$3 {{PLURAL:$3|sửa đổi|sửa đổi}} của [[:$1]] đã được trộn vào [[:$2]].',
'mergehistory-fail'                => 'Không thể thực hiện được việc trộn lịch sử sửa đổi, vui lòng chọn lại trang cũng như thông số ngày giờ.',
'mergehistory-no-source'           => 'Trang nguồn $1 không tồn tại.',
'mergehistory-no-destination'      => 'Trang đích $1 không tồn tại.',
'mergehistory-invalid-source'      => 'Trang nguồn phải có tiêu đề hợp lệ.',
'mergehistory-invalid-destination' => 'Trang đích phải có tiêu đề hợp lệ.',
'mergehistory-autocomment'         => 'Đã trộn [[:$1]] vào [[:$2]]',
'mergehistory-comment'             => 'Đã trộn [[:$1]] vào [[:$2]]: $3',
'mergehistory-same-destination'    => 'Trang nguồn và trang đích không được trùng tên',
'mergehistory-reason'              => 'Lý do:',

# Merge log
'mergelog'           => 'Nhật trình trộn',
'pagemerge-logentry' => 'đã trộn [[$1]] vào [[$2]] (sửa đổi cho đến $3)',
'revertmerge'        => 'Bỏ trộn',
'mergelogpagetext'   => 'Dưới đây là danh sách các thao tác trộn mới nhất của lịch sử một trang vào trang khác.',

# Diffs
'history-title'            => 'Lịch sử sửa đổi của “$1”',
'difference'               => '(Khác biệt giữa các bản)',
'difference-multipage'     => '(Khác biệt giữa các trang)',
'lineno'                   => 'Dòng $1:',
'compareselectedversions'  => 'So sánh các bản đã chọn',
'showhideselectedversions' => 'Hiện/ẩn các phiên bản được chọn',
'editundo'                 => 'lùi sửa',
'diff-multi'               => '(Không hiển thị {{PLURAL:$1||$1}} phiên bản {{PLURAL:$2||của $2 người dùng}} ở giữa)',
'diff-multi-manyusers'     => '(Không hiển thị {{PLURAL:$1||$1}} phiên bản của hơn $2 người dùng ở giữa)',

# Search results
'searchresults'                    => 'Kết quả tìm kiếm',
'searchresults-title'              => 'Kết quả tìm kiếm “$1”',
'searchresulttext'                 => 'Để biết thêm chi tiết về tìm kiếm tại {{SITENAME}}, xem [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => "Bạn đã tìm '''[[:$1]]''' ([[Special:Prefixindex/$1|tất cả các trang bắt đầu bằng “$1”]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tất cả các trang liên kết đến “$1”]])",
'searchsubtitleinvalid'            => "Tìm '''$1'''",
'toomanymatches'                   => 'Có quá nhiều kết quả được trả về, xin hãy thử câu tìm kiếm khác',
'titlematches'                     => 'Đề mục tương tự',
'notitlematches'                   => 'Không có tên trang nào có nội dung tương tự',
'textmatches'                      => 'Câu chữ tương tự',
'notextmatches'                    => 'Không tìm thấy nội dung trang',
'prevn'                            => '{{PLURAL:$1|$1}} mục trước',
'nextn'                            => '{{PLURAL:$1|$1}} mục sau',
'prevn-title'                      => '$1 {{PLURAL:$1|kết quả|kết quả}} trước',
'nextn-title'                      => '$1 {{PLURAL:$1|kết quả|kết quả}} sau',
'shown-title'                      => 'Hiển thị $1 {{PLURAL:$1|kết quả|kết quả}} mỗi trang',
'viewprevnext'                     => 'Xem ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Tùy chọn tìm kiếm',
'searchmenu-exists'                => "* Trang '''[[$1]]'''",
'searchmenu-new'                   => "'''Tạo trang “[[:$1]]” trên wiki này!'''",
'searchhelp-url'                   => 'Help:Nội dung',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Duyệt các trang với tiền tố này]]',
'searchprofile-articles'           => 'Trang nội dung',
'searchprofile-project'            => 'Trang trợ giúp và trang dự án',
'searchprofile-images'             => 'Đa phương tiện',
'searchprofile-everything'         => 'Tất cả',
'searchprofile-advanced'           => 'Nâng cao',
'searchprofile-articles-tooltip'   => 'Tìm trong $1',
'searchprofile-project-tooltip'    => 'Tìm trong $1',
'searchprofile-images-tooltip'     => 'Tìm tập tin',
'searchprofile-everything-tooltip' => 'Tìm tất cả nội dung (gồm cả các trang thảo luận)',
'searchprofile-advanced-tooltip'   => 'Tìm trong không gian tên tùy chọn',
'search-result-size'               => '$1 ({{PLURAL:$2|1 từ|$2 từ}})',
'search-result-category-size'      => '{{PLURAL:$1|1 trang thành viên|$1 trang thành viên}} ({{PLURAL:$2|1 tiểu thể loại|$2 tiểu thể loại}}, {{PLURAL:$3|1 tập tin|$3 tập tin}})',
'search-result-score'              => 'Độ phù hợp: $1%',
'search-redirect'                  => '(đổi hướng $1)',
'search-section'                   => '(đề mục $1)',
'search-suggest'                   => 'Có phải bạn muốn tìm: $1',
'search-interwiki-caption'         => 'Các dự án liên quan',
'search-interwiki-default'         => '$1 kết quả:',
'search-interwiki-more'            => '(thêm)',
'search-mwsuggest-enabled'         => 'có gợi ý',
'search-mwsuggest-disabled'        => 'không có gợi ý',
'search-relatedarticle'            => 'Liên quan',
'mwsuggest-disable'                => 'Tắt gợi ý bằng AJAX',
'searcheverything-enable'          => 'Tìm trong tất cả không gian tên',
'searchrelated'                    => 'có liên quan',
'searchall'                        => 'tất cả',
'showingresults'                   => "Dưới đây là {{PLURAL:$1|'''1'''|'''$1'''}} kết quả bắt đầu từ #'''$2'''.",
'showingresultsnum'                => "Dưới đây là {{PLURAL:$3|'''1'''|'''$3'''}} kết quả bắt đầu từ #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Kết quả thứ '''$1''' trong tổng số '''$3''' kết quả|Kết quả từ '''$1 - $2''' trong tổng số '''$3''' kết quả}} cho '''$4'''",
'nonefound'                        => "'''Chú ý''': Theo mặc định chỉ tìm kiếm một số không gian tên. Hãy thử bắt đầu từ khóa bằng ''all:'' để tìm mọi nội dung (kể cả trang thảo luận, bản mẫu, v.v.), hoặc bắt đầu bằng không gian tên mong muốn (ví dụ ''Thảo luận:'', ''Bản mẫu:'', ''Thể loại:''…).",
'search-nonefound'                 => 'Không có kết quả nào khớp với câu truy vấn.',
'powersearch'                      => 'Tìm kiếm nâng cao',
'powersearch-legend'               => 'Tìm kiếm nâng cao',
'powersearch-ns'                   => 'Tìm trong không gian tên:',
'powersearch-redir'                => 'Liệt kê cả trang đổi hướng',
'powersearch-field'                => 'Tìm',
'powersearch-togglelabel'          => 'Chọn:',
'powersearch-toggleall'            => 'Tất cả',
'powersearch-togglenone'           => 'Không',
'search-external'                  => 'Tìm kiếm từ bên ngoài',
'searchdisabled'                   => 'Chức năng tìm kiếm tại {{SITENAME}} đã bị tắt. Bạn có tìm kiếm bằng Google trong thời gian này. Chú ý rằng các chỉ mục từ {{SITENAME}} của chúng có thể đã lỗi thời.',

# Quickbar
'qbsettings'               => 'Thanh công cụ',
'qbsettings-none'          => 'Không có',
'qbsettings-fixedleft'     => 'Cố định trái',
'qbsettings-fixedright'    => 'Cố định phải',
'qbsettings-floatingleft'  => 'Nổi bên trái',
'qbsettings-floatingright' => 'Nổi bên phải',

# Preferences page
'preferences'                   => 'Tùy chọn',
'mypreferences'                 => 'Tùy chọn',
'prefs-edits'                   => 'Số lần sửa đổi:',
'prefsnologin'                  => 'Chưa đăng nhập',
'prefsnologintext'              => 'Bạn phải <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} đăng nhập]</span> để thiết lập tùy chọn cá nhân.',
'changepassword'                => 'Đổi mật khẩu',
'prefs-skin'                    => 'Hình dạng',
'skin-preview'                  => 'Xem thử',
'prefs-math'                    => 'Công thức toán',
'datedefault'                   => 'Không quan tâm',
'prefs-datetime'                => 'Ngày tháng',
'prefs-personal'                => 'Thông tin cá nhân',
'prefs-rc'                      => 'Thay đổi gần đây',
'prefs-watchlist'               => 'Theo dõi',
'prefs-watchlist-days'          => 'Số ngày hiển thị trong danh sách theo dõi:',
'prefs-watchlist-days-max'      => '(tối đa 7 ngày)',
'prefs-watchlist-edits'         => 'Số lần sửa đổi tối đa trong danh sách theo dõi mở rộng:',
'prefs-watchlist-edits-max'     => '(con số tối đa: 1000)',
'prefs-watchlist-token'         => 'Số thẻ Danh sách theo dõi:',
'prefs-misc'                    => 'Linh tinh',
'prefs-resetpass'               => 'Thay đổi mật khẩu',
'prefs-email'                   => 'Tùy chọn thư điện tử',
'prefs-rendering'               => 'Bề ngoài',
'saveprefs'                     => 'Lưu tùy chọn',
'resetprefs'                    => 'Mặc định lại lựa chọn',
'restoreprefs'                  => 'Mặc định lại toàn bộ tùy chọn',
'prefs-editing'                 => 'Sửa đổi',
'prefs-edit-boxsize'            => 'Kích thước cửa sổ soạn thảo.',
'rows'                          => 'Số hàng:',
'columns'                       => 'Số cột:',
'searchresultshead'             => 'Tìm kiếm',
'resultsperpage'                => 'Số kết quả mỗi trang:',
'contextlines'                  => 'Số hàng trong trang dùng để tìm ra kết quả:',
'contextchars'                  => 'Số chữ trong một hàng kết quả:',
'stub-threshold'                => 'Định dạng <a href="#" class="stub">liên kết đến sơ khai</a> cho các trang ngắn hơn (byte):',
'stub-threshold-disabled'       => 'Tắt',
'recentchangesdays'             => 'Số ngày hiển thị trong thay đổi gần đây:',
'recentchangesdays-max'         => '(tối đa $1 {{PLURAL:$1|ngày|ngày}})',
'recentchangescount'            => 'Số sửa đổi hiển thị mặc định:',
'prefs-help-recentchangescount' => 'Số này bao gồm các thay đổi gần đây, lịch sử trang, và nhật trình.',
'prefs-help-watchlist-token'    => 'Điền vào ô này một khóa bí mật để tạo ra bản tin RSS cho danh sách theo dõi của bạn.
Bất cứ ai biết được khóa trong ô này cũng có thể đọc được danh sách theo dõi của bạn, vì vậy hãy chọn một giá trị an toàn.
Đây là giá trị được tạo ngẫu nhiên mà bạn có thể sử dụng: $1',
'savedprefs'                    => 'Đã lưu các tùy chọn cá nhân.',
'timezonelegend'                => 'Múi giờ:',
'localtime'                     => 'Giờ hiện tại:',
'timezoneuseserverdefault'      => 'Sử dụng giờ mặc định của máy chủ',
'timezoneuseoffset'             => 'Khác (cần ghi số giờ chênh lệch)',
'timezoneoffset'                => 'Chênh giờ¹:',
'servertime'                    => 'Giờ máy chủ:',
'guesstimezone'                 => 'Dùng giờ của trình duyệt',
'timezoneregion-africa'         => 'Châu Phi',
'timezoneregion-america'        => 'Châu Mỹ',
'timezoneregion-antarctica'     => 'Châu Nam cực',
'timezoneregion-arctic'         => 'Bắc cực',
'timezoneregion-asia'           => 'Châu Á',
'timezoneregion-atlantic'       => 'Đại Tây Dương',
'timezoneregion-australia'      => 'Châu Úc',
'timezoneregion-europe'         => 'Châu Âu',
'timezoneregion-indian'         => 'Ấn Độ Dương',
'timezoneregion-pacific'        => 'Thái Bình Dương',
'allowemail'                    => 'Nhận thư điện tử từ các thành viên khác',
'prefs-searchoptions'           => 'Tìm kiếm',
'prefs-namespaces'              => 'Không gian tên',
'defaultns'                     => 'Nếu không thì tìm trong không gian sau:',
'default'                       => 'mặc định',
'prefs-files'                   => 'Tập tin',
'prefs-custom-css'              => 'sửa CSS',
'prefs-custom-js'               => 'sửa JS',
'prefs-common-css-js'           => 'CSS/JS chung cho mọi hình dạng:',
'prefs-reset-intro'             => 'Có thể mặc định lại toàn bộ tùy chọn dùng trang này.
Không có thể lùi lại tác động này.',
'prefs-emailconfirm-label'      => 'Xác nhận thư điện tử:',
'prefs-textboxsize'             => 'Kích cỡ hộp sửa đổi',
'youremail'                     => 'Thư điện tử:',
'username'                      => 'Tên người dùng:',
'uid'                           => 'Số thứ tự thành viên:',
'prefs-memberingroups'          => 'Thành viên của {{PLURAL:$1|nhóm|nhóm}}:',
'prefs-registration'            => 'Thời điểm đăng ký:',
'yourrealname'                  => 'Tên thật:',
'yourlanguage'                  => 'Ngôn ngữ:',
'yourvariant'                   => 'Ngôn ngữ địa phương:',
'yournick'                      => 'Chữ ký:',
'prefs-help-signature'          => 'Các ý kiến tại trang thảo luận nên được ký tên bằng cách gõ "<nowiki>~~~~</nowiki>", nó sẽ được đổi thành chữ ký của bạn cùng với thời điểm thảo luận.',
'badsig'                        => 'Chữ ký không hợp lệ; hãy kiểm tra thẻ HTML.',
'badsiglength'                  => 'Chữ ký của bạn quá dài.
Nó không được dài quá $1 ký tự.',
'yourgender'                    => 'Giới tính:',
'gender-unknown'                => 'Không chỉ rõ',
'gender-male'                   => 'Nam',
'gender-female'                 => 'Nữ',
'prefs-help-gender'             => 'Tùy chọn: được phần mềm sử dụng để xác định đúng giới tính.
Thông tin này là công khai.',
'email'                         => 'Thư điện tử',
'prefs-help-realname'           => 'Tên thật là không bắt buộc.
Nếu bạn đồng ý cung cấp, nó sẽ dùng để ghi nhận công lao của bạn.',
'prefs-help-email'              => 'Địa chỉ thư điện tử là tùy chọn, nhưng nó giúp chúng tôi gửi cho bạn mật khẩu mới qua thư điện tử nếu bạn quên mật khẩu của mình.
Bạn cũng có thể lựa chọn cho phép người khác liên lạc với bạn thông qua trang thành_viên hoặc thảo_luận_thành_viên mà không cần để lộ danh tính.',
'prefs-help-email-required'     => 'Bắt buộc phải có địa chỉ e-mail.',
'prefs-info'                    => 'Thông tin cơ bản',
'prefs-i18n'                    => 'Quốc tế hóa',
'prefs-signature'               => 'Chữ ký',
'prefs-dateformat'              => 'Kiểu ngày tháng',
'prefs-timeoffset'              => 'Chênh giờ',
'prefs-advancedediting'         => 'Tùy chọn nâng cao',
'prefs-advancedrc'              => 'Tùy chọn nâng cao',
'prefs-advancedrendering'       => 'Tùy chọn nâng cao',
'prefs-advancedsearchoptions'   => 'Tùy chọn nâng cao',
'prefs-advancedwatchlist'       => 'Tùy chọn nâng cao',
'prefs-displayrc'               => 'Tùy chọn hiển thị',
'prefs-displaysearchoptions'    => 'Tùy chọn hiển thị',
'prefs-displaywatchlist'        => 'Tùy chọn hiển thị',
'prefs-diffs'                   => 'Khác biệt',

# User rights
'userrights'                   => 'Quản lý quyền thành viên',
'userrights-lookup-user'       => 'Quản lý nhóm thành viên',
'userrights-user-editname'     => 'Nhập tên thành viên:',
'editusergroup'                => 'Sửa nhóm thành viên',
'editinguser'                  => "Thay đổi quyền hạn của thành viên '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Sửa nhóm thành viên',
'saveusergroups'               => 'Lưu nhóm thành viên',
'userrights-groupsmember'      => 'Thuộc nhóm:',
'userrights-groupsmember-auto' => 'Ngầm thuộc nhóm:',
'userrights-groups-help'       => 'Bạn có thể xếp thành viên này vào nhóm khác:
* Hộp kiểm được đánh dấu có nghĩa rằng thành viên thuộc về nhóm đó.
* Hộp không được đánh dấu có nghĩa rằng thành viên không thuộc về nhóm đó.
* Dấu * có nghĩa là bạn sẽ không thể loại thành viên ra khỏi nhóm một khi bạn đã đưa thành viên vào, hoặc ngược lại.',
'userrights-reason'            => 'Lý do:',
'userrights-no-interwiki'      => 'Bạn không có quyền thay đổi quyền hạn của thành viên tại các wiki khác.',
'userrights-nodatabase'        => 'Cơ sở dữ liệu $1 không tồn tại hoặc nằm ở bên ngoài.',
'userrights-nologin'           => 'Bạn phải [[Special:UserLogin|đăng nhập]] vào một tài khoản có quyền quản lý để gán quyền cho thành viên.',
'userrights-notallowed'        => 'Tài khoản của bạn không có quyền gán quyền cho thành viên.',
'userrights-changeable-col'    => 'Những nhóm bạn có thể thay đổi',
'userrights-unchangeable-col'  => 'Những nhóm bạn không thể thay đổi',

# Groups
'group'               => 'Nhóm:',
'group-user'          => 'Thành viên thông thường',
'group-autoconfirmed' => 'Thành viên tự xác nhận',
'group-bot'           => 'Robot',
'group-sysop'         => 'Bảo quản viên',
'group-bureaucrat'    => 'Hành chính viên',
'group-suppress'      => 'Giám sát viên',
'group-all'           => '(tất cả)',

'group-user-member'          => 'Thành viên',
'group-autoconfirmed-member' => 'Thành viên tự động xác nhận',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'bảo quản viên',
'group-bureaucrat-member'    => 'Hành chính viên',
'group-suppress-member'      => 'Giám sát viên',

'grouppage-user'          => '{{ns:project}}:Thành viên',
'grouppage-autoconfirmed' => '{{ns:project}}:Thành viên tự xác nhận',
'grouppage-bot'           => '{{ns:project}}:Robot',
'grouppage-sysop'         => '{{ns:project}}:Người quản lý',
'grouppage-bureaucrat'    => '{{ns:project}}:Hành chính viên',
'grouppage-suppress'      => '{{ns:project}}:Giám sát viên',

# Rights
'right-read'                  => 'Đọc trang',
'right-edit'                  => 'Sửa trang',
'right-createpage'            => 'Tạo trang (không phải trang thảo luận)',
'right-createtalk'            => 'Tạo trang thảo luận',
'right-createaccount'         => 'Mở tài khoản mới',
'right-minoredit'             => 'Đánh dấu sửa đổi nhỏ',
'right-move'                  => 'Di chuyển trang',
'right-move-subpages'         => 'Di chuyển trang cùng với các trang con của nó',
'right-move-rootuserpages'    => 'Di chuyển các trang cá nhân chính',
'right-movefile'              => 'Di chuyển tập tin',
'right-suppressredirect'      => 'Không tạo đổi hướng từ tên cũ khi di chuyển trang',
'right-upload'                => 'Tải tập tin lên',
'right-reupload'              => 'Tải đè tập tin cũ',
'right-reupload-own'          => 'Tải đè tập tin cũ do chính mình tải lên',
'right-reupload-shared'       => 'Ghi đè lên kho hình ảnh dùng chung',
'right-upload_by_url'         => 'Tải tập tin từ địa chỉ URL',
'right-purge'                 => 'Tẩy bộ đệm của trang mà không có trang xác nhận',
'right-autoconfirmed'         => 'Sửa trang bị nửa khóa',
'right-bot'                   => 'Được đối xử như tác vụ tự động',
'right-nominornewtalk'        => 'Không báo về tin nhắn mới khi trang thảo luận chỉ được sửa đổi nhỏ',
'right-apihighlimits'         => 'Được dùng giới hạn cao hơn khi truy vấn API',
'right-writeapi'              => 'Sử dụng API để viết',
'right-delete'                => 'Xóa trang',
'right-bigdelete'             => 'Xóa trang có lịch sử lớn',
'right-deleterevision'        => 'Xóa và phục hồi phiên bản nào đó của trang',
'right-deletedhistory'        => 'Xem phần lịch sử đã xóa, mà không xem nội dung đi kèm',
'right-deletedtext'           => 'Xem văn bản đã xóa và các thay đổi giữa phiên bản đã xóa',
'right-browsearchive'         => 'Tìm những trang đã xóa',
'right-undelete'              => 'Phục hồi trang',
'right-suppressrevision'      => 'Xem lại và phục hồi phiên bản mà Sysop không thấy',
'right-suppressionlog'        => 'Xem nhật trình riêng tư',
'right-block'                 => 'Cấm thành viên khác sửa đổi',
'right-blockemail'            => 'Cấm thành viên gửi thư',
'right-hideuser'              => 'Cấm thành viên, rồi ẩn nó đi',
'right-ipblock-exempt'        => 'Bỏ qua cấm IP, tự động cấm và cấm dải IP',
'right-proxyunbannable'       => 'Bỏ qua cấm proxy tự động',
'right-unblockself'           => 'Tự bỏ cấm',
'right-protect'               => 'Thay đổi mức khóa và sửa trang khóa',
'right-editprotected'         => 'Sửa trang khóa (không bị khóa theo tầng)',
'right-editinterface'         => 'Sửa giao diện người dùng',
'right-editusercssjs'         => 'Sửa tập tin CSS và JS của người dùng khác',
'right-editusercss'           => 'Sửa tập tin CSS của người dùng khác',
'right-edituserjs'            => 'Sửa tập tin JS của người dùng khác',
'right-rollback'              => 'Nhanh chóng lùi tất cả sửa đổi của thành viên cuối cùng sửa đổi tại trang nào đó',
'right-markbotedits'          => 'Đánh dấu sửa đổi phục hồi là sửa đổi bot',
'right-noratelimit'           => 'Không bị ảnh hưởng bởi mức giới hạn tần suất sử dụng',
'right-import'                => 'Nhập trang từ wiki khác',
'right-importupload'          => 'Nhập trang bằng tải tập tin',
'right-patrol'                => 'Đánh dấu tuần tra sửa đổi',
'right-autopatrol'            => 'Tự động đánh dấu tuần tra khi sửa đổi',
'right-patrolmarks'           => 'Dùng tính năng tuần tra thay đổi gần đây',
'right-unwatchedpages'        => 'Xem danh sách các trang chưa theo dõi',
'right-trackback'             => 'Đăng trackback',
'right-mergehistory'          => 'Trộn lịch sử trang',
'right-userrights'            => 'Sửa tất cả quyền thành viên',
'right-userrights-interwiki'  => 'Sửa quyền thành viên của các thành viên ở các wiki khác',
'right-siteadmin'             => 'Khóa và mở khóa cơ sở dữ liệu',
'right-reset-passwords'       => 'Tái tạo mật khẩu của thành viên khác',
'right-override-export-depth' => 'Xuất trang kèm theo các trang được liên kết đến với độ sâu tối đa là 5',
'right-sendemail'             => 'Gửi thư điện tử cho thành viên khác',
'right-revisionmove'          => 'Di chuyển phiên bản',
'right-disableaccount'        => 'Vô hiệu hóa tài khoản',

# User rights log
'rightslog'      => 'Nhật trình cấp quyền thành viên',
'rightslogtext'  => 'Đây là nhật trình lưu những thay đổi đối với các quyền hạn thành viên.',
'rightslogentry' => 'đã đổi cấp của thành viên $1 từ $2 thành $3',
'rightsnone'     => '(không có)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'đọc trang này',
'action-edit'                 => 'sửa đổi trang này',
'action-createpage'           => 'tạo trang',
'action-createtalk'           => 'tạo trang thảo luận',
'action-createaccount'        => 'mở tài khoản này',
'action-minoredit'            => 'đánh dấu đây là sửa đổi nhỏ',
'action-move'                 => 'di chuyển trang này',
'action-move-subpages'        => 'di chuyển trang này và các trang con',
'action-move-rootuserpages'   => 'di chuyển trang cá nhân chính',
'action-movefile'             => 'di chuyển tập tin này',
'action-upload'               => 'tải tập tin này lên',
'action-reupload'             => 'ghi đè lên tập tin có sẵn này',
'action-reupload-shared'      => 'ghi đè lên tập tin đang thuộc kho tập tin chung này',
'action-upload_by_url'        => 'tải lên tập tin này từ địa chỉ URL',
'action-writeapi'             => 'dùng API để sửa đổi',
'action-delete'               => 'xóa trang này',
'action-deleterevision'       => 'xóa phiên bản này',
'action-deletedhistory'       => 'xem các phiên bản đã bị xóa của trang này',
'action-browsearchive'        => 'tìm trong các trang đã bị xóa',
'action-undelete'             => 'phục hồi trang này',
'action-suppressrevision'     => 'duyệt và phục hồi phiên bản bị giấu này',
'action-suppressionlog'       => 'xem nhật trình ẩn giấu này',
'action-block'                => 'cấm không cho người dùng này sửa đổi',
'action-protect'              => 'thay đổi mức khóa của trang này',
'action-import'               => 'nhập trang này từ wiki khác',
'action-importupload'         => 'nhập trang này bằng cách tải lên tập tin',
'action-patrol'               => 'đánh dấu đã tuần tra vào sửa đổi của người khác',
'action-autopatrol'           => 'tự động đánh dấu đã tuần tra vào sửa đổi của bạn',
'action-unwatchedpages'       => 'xem danh sách các trang chưa được theo dõi',
'action-trackback'            => 'gửi TrackBack',
'action-mergehistory'         => 'hợp nhất lịch sử của trang này',
'action-userrights'           => 'sửa đổi mọi quyền người dùng',
'action-userrights-interwiki' => 'sửa đổi quyền của người dùng tại wiki khác',
'action-siteadmin'            => 'khóa hoặc mở khóa cơ sở dữ liệu',
'action-revisionmove'         => 'di chuyển phiên bản',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|thay đổi|thay đổi}}',
'recentchanges'                     => 'Thay đổi gần đây',
'recentchanges-legend'              => 'Tùy chọn thay đổi gần đây',
'recentchangestext'                 => 'Xem các thay đổi gần đây nhất tại wiki trên trang này.',
'recentchanges-feed-description'    => 'Theo dõi các thay đổi gần đây nhất của wiki dùng feed này.',
'recentchanges-label-newpage'       => 'Bản sửa này tạo ra trang mới',
'recentchanges-label-minor'         => 'Đây là một sửa đổi nhỏ',
'recentchanges-label-bot'           => 'Sửa đổi này do bot thực hiện',
'recentchanges-label-unpatrolled'   => 'Sửa đổi này chưa được tuần tra',
'rcnote'                            => "Dưới đây là {{PLURAL:$1|'''1''' thay đổi|'''$1''' thay đổi gần nhất}} trong {{PLURAL:$2|ngày qua|'''$2''' ngày qua}}, tính tới $5, $4.",
'rcnotefrom'                        => "Thay đổi từ '''$2''' (hiển thị tối đa '''$1''' thay đổi).",
'rclistfrom'                        => 'Hiển thị các thay đổi từ $1.',
'rcshowhideminor'                   => '$1 sửa đổi nhỏ',
'rcshowhidebots'                    => '$1 sửa đổi bot',
'rcshowhideliu'                     => '$1 sửa đổi thành viên',
'rcshowhideanons'                   => '$1 sửa đổi vô danh',
'rcshowhidepatr'                    => '$1 sửa đổi đã tuần tra',
'rcshowhidemine'                    => '$1 sửa đổi của tôi',
'rclinks'                           => 'Xem $1 sửa đổi gần đây nhất trong $2 ngày qua; $3.',
'diff'                              => 'khác',
'hist'                              => 'sử',
'hide'                              => 'ẩn',
'show'                              => 'hiện',
'minoreditletter'                   => 'n',
'newpageletter'                     => 'M',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|người|người}} đang xem]',
'rc_categories'                     => 'Hạn chế theo thể loại (phân cách bằng “|”)',
'rc_categories_any'                 => 'Bất kỳ',
'newsectionsummary'                 => 'Đề mục mới: /* $1 */',
'rc-enhanced-expand'                => 'Xem chi tiết (cần JavaScript)',
'rc-enhanced-hide'                  => 'Giấu chi tiết',

# Recent changes linked
'recentchangeslinked'          => 'Thay đổi liên quan',
'recentchangeslinked-feed'     => 'Thay đổi liên quan',
'recentchangeslinked-toolbox'  => 'Thay đổi liên quan',
'recentchangeslinked-title'    => 'Thay đổi liên quan tới “$1”',
'recentchangeslinked-noresult' => 'Không có thay đổi nào trên trang được liên kết đến trong khoảng thời gian đã chọn.',
'recentchangeslinked-summary'  => "Đây là danh sách các thay đổi được thực hiện gần đây tại những trang được liên kết đến từ một trang nào đó (hoặc tại các trang thuộc một thể loại nào đó).
Các trang trong [[Special:Watchlist|danh sách bạn theo dõi]] được '''tô đậm'''.",
'recentchangeslinked-page'     => 'Tên trang:',
'recentchangeslinked-to'       => 'Hiện thay đổi tại những trang có liên kết đến trang này thay thế',

# Upload
'upload'                      => 'Tải tập tin lên',
'uploadbtn'                   => 'Tải tập tin lên',
'reuploaddesc'                => 'Hủy tác vụ tải và quay lại mẫu tải tập tin lên',
'upload-tryagain'             => 'Lưu miêu tả tập tin được sửa đổi',
'uploadnologin'               => 'Chưa đăng nhập',
'uploadnologintext'           => 'Bạn phải [[Special:UserLogin|đăng nhập]] để tải tập tin lên.',
'upload_directory_missing'    => 'Thư mục tải lên ($1) không có hoặc máy chủ web không thể tạo được.',
'upload_directory_read_only'  => 'Máy chủ không thể sửa đổi thư mục tải lên ($1) được.',
'uploaderror'                 => 'Lỗi khi tải lên',
'upload-recreate-warning'     => "'''Cảnh báo: Một tập tin với tên này đã từng bị xóa hoặc di chuyển.'''

Nhật trình xóa và di chuyển của trang này được ghi ở dưới để bạn tiện theo dõi:",
'uploadtext'                  => "Hãy sử dụng mẫu sau để tải tập tin lên.
Để xem hoặc tìm kiếm những hình ảnh đã được tải lên trước đây, xin mời xem [[Special:FileList|danh sách các tập tin đã tải lên]].
việc tải lên và tải lên lại được ghi lại trong [[Special:Log/upload|nhật trình tải lên]],  việc xóa đi được ghi trong [[Special:Log/delete|nhật trình xóa]].

Để đưa tập tin vào trang, hãy dùng liên kết có một trong các dạng sau:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Tập tin.jpg]]</nowiki></tt>''' để phiên bản đầy đủ của tập tin
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Tập tin.png|200px|nhỏ|trái|văn bản thay thế]]</nowiki></tt>''' để dùng hình đã được co lại còn 200 pixel chiều rộng đặt trong một hộp ở lề bên trái với 'văn bản thay thế' dùng để mô tả
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Tập tin.ogg]]</nowiki></tt>''' để liên kết trực tiếp đến tập tin mà không hiển thị nó",
'upload-permitted'            => 'Các định dạng tập tin được phép tải lên: $1.',
'upload-preferred'            => 'Các định dạng tập tin nên dùng: $1.',
'upload-prohibited'           => 'Các định dạng tập tin bị cấm: $1.',
'uploadlog'                   => 'nhật trình tải lên',
'uploadlogpage'               => 'Nhật trình tải lên',
'uploadlogpagetext'           => 'Dưới đây là danh sách các tập tin đã tải lên gần nhất.
Xem [[Special:NewFiles|trang trưng bày các tập tin mới]] để xem trực quan hơn.',
'filename'                    => 'Tên tập tin',
'filedesc'                    => 'Miêu tả',
'fileuploadsummary'           => 'Tóm lược:',
'filereuploadsummary'         => 'Các thay đổi của tập tin:',
'filestatus'                  => 'Tình trạng bản quyền:',
'filesource'                  => 'Nguồn:',
'uploadedfiles'               => 'Tập tin đã tải',
'ignorewarning'               => 'Bỏ qua cảnh báo và lưu tập tin',
'ignorewarnings'              => 'Bỏ qua cảnh báo',
'minlength1'                  => 'Tên tập tin phải có ít nhất một ký tự.',
'illegalfilename'             => 'Tên tập tin “$1” có chứa ký tự không được phép dùng cho tựa trang. Xin hãy đổi tên và tải lên lại.',
'badfilename'                 => 'Tên tập tin đã được đổi thành “$1”.',
'filetype-mime-mismatch'      => 'Phần mở rộng của tập tin không phù hợp kiểu MIME.',
'filetype-badmime'            => 'Không thể tải lên các tập tin có định dạng MIME “$1”.',
'filetype-bad-ie-mime'        => 'Không thể tải tập tin này lên vì Internet Explorer sẽ nhận diện tập tin này là “$1”, một định dạng tập tin tiềm ẩn nguy hiểm và không được cho phép.',
'filetype-unwanted-type'      => "'''“.$1”''' là định dạng tập tin không được trông đợi.
{{PLURAL:$3|Loại|Những loại}} tập tin thích hợp hơn là $2.",
'filetype-banned-type'        => "'''“.$1”''' là định dạng tập tin không được chấp nhận.
{{PLURAL:$3|Loại tập tin|Những loại tập tin}} được chấp nhận là $2.",
'filetype-missing'            => 'Tập tin không có phần mở rộng (ví dụ “.jpg”).',
'empty-file'                  => 'Bạn đã gửi tập tin rỗng.',
'file-too-large'              => 'Bạn đã gửi tập tin lớn quá hạn.',
'filename-tooshort'           => 'Tên tập tin ngắn quá.',
'filetype-banned'             => 'Kiểu tập tin này đã bị cấm.',
'verification-error'          => 'Tập tin này không qua được bước thẩm tra.',
'hookaborted'                 => 'Sửa đổi của bạn bị hook phần mở rộng hủy bỏ.',
'illegal-filename'            => 'Không được đặt tên tập tin này.',
'overwrite'                   => 'Không được ghi đè một tập tin đã tồn tại.',
'unknown-error'               => 'Gặp lỗi không ngờ.',
'tmp-create-error'            => 'Không thể tạo tập tin tạm thời.',
'tmp-write-error'             => 'Gặp lỗi khi ghi vào tập tin tạm thời.',
'large-file'                  => 'Các tập tin được khuyến cáo không được lớn hơn $1; tập tin này lớn đến $2.',
'largefileserver'             => 'Tập tin này quá lớn so với khả năng phục vụ của máy chủ.',
'emptyfile'                   => 'Tập tin bạn vừa mới tải lên có vẻ trống không. Điều này có thể xảy ra khi bạn đánh sai tên tập tin. Xin hãy chắc chắn rằng bạn thật sự muốn tải lên tập tin này.',
'fileexists'                  => "Một tập tin với tên này đã tồn tại, xin hãy kiểm tra lại '''<tt>[[:$1]]</tt>''' nếu bạn không chắc bạn có muốn thay đổi nó hay không.
[[$1|thumb]]",
'filepageexists'              => "Trang miêu tả tập tin này đã tồn tại ở '''<tt>[[:\$1]]</tt>''', nhưng chưa có tập tin với tên này.
Những gì bạn ghi trong ô \"Tóm tắt tập tin\" sẽ không hiện ra ở trang miêu tả.
Để làm nó hiển thị, bạn sẽ cần phải sửa đổi trang đó bằng tay.
[[\$1|thumb]]",
'fileexists-extension'        => "Hiện có một tập tin trùng tên: [[$2|thumb]]
* Tên tập tin đang tải lên: '''<tt>[[:$1]]</tt>'''
* Tên tập tin có từ trước: '''<tt>[[:$2]]</tt>'''
Xin hãy chọn một tên tập tin khác.",
'fileexists-thumbnail-yes'    => "Tập tin này có vẻ là hình có kích thước thu gọn ''(hình thu nhỏ)''. [[$1|thumb]]
Xin kiểm tra lại tập tin '''<tt>[[:$1]]</tt>'''.
Nếu tập tin được kiểm tra trùng với hình có kích cỡ gốc thì không cần thiết tải lên một hình thu nhỏ khác.",
'file-thumbnail-no'           => "Tên tập tin bắt đầu bằng '''<tt>$1</tt>'''.
Có vẻ đây là bản thu nhỏ của hình gốc ''(thumbnail)''.
Nếu bạn có hình ở độ phân giải tối đa, xin hãy tải bản đó lên, nếu không xin hãy đổi lại tên tập tin.",
'fileexists-forbidden'        => 'Đã có tập tin với tên gọi này, và nó không thể bị ghi đè.
Nếu bạn vẫn muốn tải tập tin của bạn lên, xin hãy quay lại và sử dụng một tên khác. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Một tập tin với tên này đã tồn tại ở kho tập tin dùng chung.
Nếu bạn vẫn muốn tải tập tin của bạn lên, xin hãy quay lại và dùng một tên khác. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Tập tin này có vẻ là bản sao của {{PLURAL:$1|tập tin|các  tập tin}} sau:',
'file-deleted-duplicate'      => 'Một tập tin giống hệt như tập tin này ([[$1]]) đã từng bị xóa trước đây. Bạn nên xem lại lịch sử xóa tập tin trước khi tiếp tục tải nó lên lại.',
'uploadwarning'               => 'Cảnh báo!',
'uploadwarning-text'          => 'Xin hãy chỉnh sửa miêu tả tập tin ở dưới và thử lại.',
'savefile'                    => 'Lưu tập tin',
'uploadedimage'               => 'đã tải “[[$1]]” lên',
'overwroteimage'              => 'đã tải lên một phiên bản mới của “[[$1]]”',
'uploaddisabled'              => 'Chức năng tải lên đã bị khóa.',
'copyuploaddisabled'          => 'Chức năng tải lên từ địa chỉ URL đã bị tắt.',
'uploadfromurl-queued'        => 'Tập tin của bạn đã được xếp vào hàng đợi tải lên.',
'uploaddisabledtext'          => 'Chức năng tải tập tin đã bị tắt.',
'php-uploaddisabledtext'      => 'Việc tải tập tin trong PHP đã bị tắt. Xin hãy kiểm tra lại thiết lập file_uploads.',
'uploadscripted'              => 'Tập tin này có chứa mã HTML hoặc script có thể khiến trình duyệt web thông dịch sai.',
'uploadvirus'                 => 'Tập tin có virút! Chi tiết: $1',
'upload-source'               => 'Tập tin gốc',
'sourcefilename'              => 'Tên tập tin nguồn:',
'sourceurl'                   => 'URL gốc:',
'destfilename'                => 'Tên tập tin mới:',
'upload-maxfilesize'          => 'Kích thước tập tin tối đa: $1',
'upload-description'          => 'Miêu tả tập tin',
'upload-options'              => 'Tùy chọn tải lên',
'watchthisupload'             => 'Theo dõi tập tin này',
'filewasdeleted'              => 'Một tên với tên này đã được tải lên trước đã rồi sau đó bị xóa. Bạn nên kiểm tra lại $1 trước khi tải nó lên lại lần nữa.',
'upload-wasdeleted'           => "'''Cảnh báo: Bạn đang tải lên một tập tin từng bị xóa trước đây.'''

Bạn nên cân nhắc trong việc tiếp tục tải lên tập tin này. Nhật trình xóa của tập tin được đưa ra dưới đây để tiện theo dõi:",
'filename-bad-prefix'         => "Tên cho tập tin mà bạn đang tải lên bắt đầu bằng '''“$1”''', đây không phải là dạng tên tiêu biểu có tính chất miêu tả do các máy chụp ảnh số tự động đặt. Xin hãy chọn một tên có tính chất miêu tả và gợi nhớ hơn cho tập tin của bạn.",
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
'upload-success-subj'         => 'Đã tải xong',
'upload-success-msg'          => 'Bạn đã tải lên tập tin thành công từ [$2]. Nó có sẵn tại [[:{{ns:file}}:$1]].',
'upload-failure-subj'         => 'Vấn đề tải lên',
'upload-failure-msg'          => 'Tập tin bạn tải lên từ [$2] có một vấn đề:

$1',
'upload-warning-subj'         => 'Cảnh báo tải lên',
'upload-warning-msg'          => 'Tập tin tải lên từ [$2] đã gặp vấn đề. Xin hãy trở về [[Special:Upload/stash/$1|biểu mẫu tải lên]] để giải quyết vấn đề này.',

'upload-proto-error'        => 'Giao thức sai',
'upload-proto-error-text'   => 'Phải đưa vào URL bắt đầu với <code>http://</code> hay <code>ftp://</code> để tải lên tập tin từ trang web khác.',
'upload-file-error'         => 'Lỗi nội bộ',
'upload-file-error-text'    => 'Có lỗi nội bộ khi tạo tập tin tạm trên máy chủ.
Xin hãy liên hệ với một [[Special:ListUsers/sysop|bảo quản viên]].',
'upload-misc-error'         => 'Có lỗi lạ khi tải lên',
'upload-misc-error-text'    => 'Có lỗi lạ khi tải lên.
Xin hãy xác nhận lại địa chỉ URL là hợp lệ và có thể truy cập được không rồi thử lại lần nữa.
Nếu vẫn còn bị lỗi, xin hãy liên hệ với một [[Special:ListUsers/sysop|bảo quản viên]].',
'upload-too-many-redirects' => 'URL có quá nhiều chuyển hướng',
'upload-unknown-size'       => 'Không rõ kích thước',
'upload-http-error'         => 'Xảy ra lỗi HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Không cho phép truy cập',
'img-auth-nopathinfo'   => 'Thiếu PATH_INFO.
Máy chủ của bạn không được thiết lập để truyền thông tin này.
Có thể do nó dựa trên CGI và không hỗ trợ img_auth.
Xem http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Đường dẫn yêu cầu không nằm trong thư mục cấu hình tải lên.',
'img-auth-badtitle'     => 'Không thể tạo tựa đề hợp lệ từ “$1”.',
'img-auth-nologinnWL'   => 'Bạn chưa đăng nhập và “$1” không nằm trong danh sách trắng.',
'img-auth-nofile'       => 'Không tồn tại tập tin “$1”.',
'img-auth-isdir'        => 'Bạn đang cố truy cập vào thư mục “$1”.
Chỉ cho phép truy cập tập tin mà thôi.',
'img-auth-streaming'    => 'Đang truyền “$1”.',
'img-auth-public'       => 'Chức năng của img_auth.php là xuất tập tin từ wiki cá nhân.
Wiki này được cấu hình là wiki công cộng.
Vì lý do bảo mật, img_auth.php đã bị tắt.',
'img-auth-noread'       => 'Người dùng không đủ quyền truy cập để đọc “$1”.',

# HTTP errors
'http-invalid-url'      => 'URL không hợp lệ: $1',
'http-invalid-scheme'   => 'Không hỗ trợ các URL có mô hình “$1”',
'http-request-error'    => 'Có lỗi lạ khi gửi yêu cầu HTTP.',
'http-read-error'       => 'Lỗi đọc HTTP.',
'http-timed-out'        => 'Hết thời gian yêu cầu HTTP.',
'http-curl-error'       => 'Có lỗi khi truy xuất URL: $1',
'http-host-unreachable' => 'Không thể truy cập URL',
'http-bad-status'       => 'Có vấn đề khi yêu cầu HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Không thể truy cập URL',
'upload-curl-error6-text'  => 'Không thể truy cập URL mà bạn đưa vào. Xin hãy kiểm tra xem URL có đúng không và website vẫn còn hoạt động.',
'upload-curl-error28'      => 'Quá thời gian tải lên cho phép',
'upload-curl-error28-text' => 'Trang web phản hồi quá chậm. Xin hãy kiểm tra lại xem trang web còn hoạt động hay không, đợi một thời gian ngắn rồi thử lại. Bạn nên thử lại vào lúc trang rảnh rỗi hơn.',

'license'            => 'Giấy phép:',
'license-header'     => 'Giấy phép',
'nolicense'          => 'chưa chọn',
'license-nopreview'  => '(Không xem trước được)',
'upload_source_url'  => ' (địa chỉ URL đúng, có thể truy cập)',
'upload_source_file' => ' (tập tin trên máy của bạn)',

# Special:ListFiles
'listfiles-summary'     => 'Trang đặc biệt này liệt kê các tập tin được tải lên.
Theo mặc định, các tập tin mới nhất được xếp vào đầu danh sách.
Hãy nhấn chuột vào tiêu đề cột để thay đổi thứ tự sắp xếp.',
'listfiles_search_for'  => 'Tìm kiếm theo tên tập tin:',
'imgfile'               => 'tập tin',
'listfiles'             => 'Danh sách tập tin',
'listfiles_thumb'       => 'Hình nhỏ',
'listfiles_date'        => 'Ngày tải',
'listfiles_name'        => 'Tên',
'listfiles_user'        => 'Thành viên tải',
'listfiles_size'        => 'Kích cỡ',
'listfiles_description' => 'Miêu tả',
'listfiles_count'       => 'Số phiên bản',

# File description page
'file-anchor-link'          => 'Tập tin',
'filehist'                  => 'Lịch sử tập tin',
'filehist-help'             => 'Nhấn vào một ngày/giờ để xem nội dung tập tin tại thời điểm đó.',
'filehist-deleteall'        => 'xóa toàn bộ',
'filehist-deleteone'        => 'xóa bản này',
'filehist-revert'           => 'lùi lại',
'filehist-current'          => 'hiện',
'filehist-datetime'         => 'Ngày/Giờ',
'filehist-thumb'            => 'Hình nhỏ',
'filehist-thumbtext'        => 'Hình thu nhỏ của phiên bản vào lúc $1',
'filehist-nothumb'          => 'Không có hình thu nhỏ',
'filehist-user'             => 'Thành viên',
'filehist-dimensions'       => 'Kích cỡ',
'filehist-filesize'         => 'Kích thước tập tin',
'filehist-comment'          => 'Miêu tả',
'filehist-missing'          => 'Không thấy tập tin',
'imagelinks'                => 'Liên kết đến tập tin',
'linkstoimage'              => '{{PLURAL:$1|Trang|$1 trang}} sau có liên kết đến tập tin này:',
'linkstoimage-more'         => 'Có hơn $1 {{PLURAL:$1|trang|trang}} liên kết đến tập tin này.
Danh sách dưới đây chỉ hiển thị {{PLURAL:$1|liên kết đầu tiên|$1 liên kết đầu tiên}} đến tập tin này.
Có [[Special:WhatLinksHere/$2|danh sách đầy đủ ở đây]].',
'nolinkstoimage'            => 'Không có trang nào chứa liên kết đến hình.',
'morelinkstoimage'          => 'Xem [[Special:WhatLinksHere/$1|thêm liên kết]] đến tập tin này.',
'redirectstofile'           => '{{PLURAL:$1|Tập tin|$1 tập tin}} sau chuyển hướng đến tập tin này:',
'duplicatesoffile'          => '{{PLURAL:$1|Tập tin sau|$1 tập tin sau}} là bản sao của tập tin này ([[Special:FileDuplicateSearch/$2|chi tiết]]):',
'sharedupload'              => 'Tập tin này đặt tại $1 và các dự án khác có thể dùng chúng.',
'sharedupload-desc-there'   => 'Tập tin này đặt tại $1 và các dự án khác có thể sử dụng chúng.
Mời xem [$2 trang mô tả tập tin] để có thêm thông tin.',
'sharedupload-desc-here'    => 'Tập tin này đặt tại $1 và các dự án khác có thể sử dụng chúng.
Lời miêu tả tại [$2 trang mô tả tập tin] tại đấy được hiển thị dưới đây.',
'filepage-nofile'           => 'Không có tập tin nào có tên này.',
'filepage-nofile-link'      => 'Không có tập tin nào có tên này, nhưng bạn có thể [$1 tải nó lên].',
'uploadnewversion-linktext' => 'Tải lên phiên bản mới',
'shared-repo-from'          => 'tại $1',
'shared-repo'               => 'kho lưu trữ dùng chung',

# File reversion
'filerevert'                => 'Lùi lại phiên bản của $1',
'filerevert-legend'         => 'Lùi lại tập tin',
'filerevert-intro'          => "Bạn đang lùi '''[[Media:$1|$1]]''' về [$4 phiên bản lúc $3, $2].",
'filerevert-comment'        => 'Lý do:',
'filerevert-defaultcomment' => 'Đã lùi về phiên bản lúc $2, $1',
'filerevert-submit'         => 'Lùi lại',
'filerevert-success'        => "'''[[Media:$1|$1]]''' đã được lùi về [$4 phiên bản lúc $3, $2].",
'filerevert-badversion'     => 'Không tồn tại phiên bản trước đó của tập tin tại thời điểm trên.',

# File deletion
'filedelete'                  => 'Xóa $1',
'filedelete-legend'           => 'Xóa tập tin',
'filedelete-intro'            => "Bạn sắp xóa tập tin '''[[Media:$1|$1]]''' cùng với tất cả lịch sử của nó.",
'filedelete-intro-old'        => "Bạn đang xóa phiên bản của '''[[Media:$1|$1]]''' vào lúc [$4 $3, $2].",
'filedelete-comment'          => 'Lý do:',
'filedelete-submit'           => 'Xóa',
'filedelete-success'          => "'''$1''' đã bị xóa.",
'filedelete-success-old'      => "Phiên bản của '''[[Media:$1|$1]]''' vào lúc $3, $2 đã bị xóa.",
'filedelete-nofile'           => "'''$1''' không tồn tại.",
'filedelete-nofile-old'       => "Không có phiên bản lưu trữ của '''$1''' với các thuộc tính này.",
'filedelete-otherreason'      => 'Lý do bổ sung:',
'filedelete-reason-otherlist' => 'Lý do khác',
'filedelete-reason-dropdown'  => '*Những lý do xóa thường gặp
** Vi phạm bản quyền
** Tập tin trùng lắp',
'filedelete-edit-reasonlist'  => 'Sửa lý do xóa',
'filedelete-maintenance'      => 'Tác vụ xóa và phục hồi tập tin đã bị tắt tạm thời trong khi bảo trì.',

# MIME search
'mimesearch'         => 'Tìm kiếm theo định dạng',
'mimesearch-summary' => 'Trang này có khả năng lọc tập tin theo định dạng MIME. Đầu vào: contenttype/subtype, v.d. <tt>image/jpeg</tt>.',
'mimetype'           => 'Định dạng MIME:',
'download'           => 'tải về',

# Unwatched pages
'unwatchedpages' => 'Trang chưa được theo dõi',

# List redirects
'listredirects' => 'Danh sách trang đổi hướng',

# Unused templates
'unusedtemplates'     => 'Bản mẫu chưa dùng',
'unusedtemplatestext' => 'Trang này liệt kê tất cả các trang trong không gian tên {{ns:template}} mà chưa được dùng trong trang nào khác.

Hãy nhớ kiểm tra các liên kết khác đến bản mẫu trước khi xóa chúng.',
'unusedtemplateswlh'  => 'liên kết khác',

# Random page
'randompage'         => 'Trang ngẫu nhiên',
'randompage-nopages' => 'Hiện chưa có trang nào trong {{PLURAL:$2||các}} không gian tên: $1.',

# Random redirect
'randomredirect'         => 'Trang đổi hướng ngẫu nhiên',
'randomredirect-nopages' => 'Không có trang đổi hướng nào trong không gian tên “$1”.',

# Statistics
'statistics'                   => 'Thống kê',
'statistics-header-pages'      => 'Thống kê trang',
'statistics-header-edits'      => 'Thống kê sửa đổi',
'statistics-header-views'      => 'Thống kê truy cập',
'statistics-header-users'      => 'Thống kê thành viên',
'statistics-header-hooks'      => 'Thống kê khác',
'statistics-articles'          => 'Số trang nội dung',
'statistics-pages'             => 'Số trang',
'statistics-pages-desc'        => 'Tất cả các trang tại wiki, bao gồm trang thảo luận, trang đổi hướng, v.v.',
'statistics-files'             => 'Số tập tin đã tải lên',
'statistics-edits'             => 'Số sửa đổi trang từ khi {{SITENAME}} được thành lập',
'statistics-edits-average'     => 'Số sửa đổi trung bình trên một trang',
'statistics-views-total'       => 'Số lần xem tổng cộng',
'statistics-views-total-desc'  => 'Không bao gồm số lần xem các trang không tồn tại và các trang đặc biệt',
'statistics-views-peredit'     => 'Số lần xem trên một sửa đổi',
'statistics-users'             => 'Số [[Special:ListUsers|thành viên]] đã đăng ký',
'statistics-users-active'      => 'Số thành viên tích cực',
'statistics-users-active-desc' => 'Những thành viên đã hoạt động trong {{PLURAL:$1|ngày|$1 ngày}} qua',
'statistics-mostpopular'       => 'Các trang được xem nhiều nhất',

'disambiguations'      => 'Trang định hướng',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "Các trang này có liên kết đến một '''trang định hướng'''. Nên sửa các liên kết này để chỉ đến một trang đúng nghĩa hơn.<br />Các trang định hướng là trang sử dụng những bản mẫu được liệt kê ở [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Đổi hướng kép',
'doubleredirectstext'        => 'Trang này liệt kê các trang đổi hướng đến một trang đổi hướng khác.
Mỗi hàng có chứa các liên kết đến trang đổi hướng thứ nhất và thứ hai, cũng như mục tiêu của trang đổi hướng thứ hai, thường là trang đích “thực sự”, là nơi mà trang đổi hướng đầu tiên nên trỏ đến.
Các mục <del>bị gạch bỏ</del> là các trang đã được sửa.',
'double-redirect-fixed-move' => '[[$1]] đã được đổi tên, giờ nó là trang đổi hướng đến [[$2]]',
'double-redirect-fixer'      => 'Người sửa trang đổi hướng',

'brokenredirects'        => 'Đổi hướng sai',
'brokenredirectstext'    => 'Các trang đổi hướng sau đây liên kết đến trang không tồn tại:',
'brokenredirects-edit'   => 'sửa',
'brokenredirects-delete' => 'xóa',

'withoutinterwiki'         => 'Trang chưa có liên kết ngoại ngữ',
'withoutinterwiki-summary' => 'Các trang sau đây không có liên kết đến các phiên bản ngoại ngữ khác:',
'withoutinterwiki-legend'  => 'Tiền tố',
'withoutinterwiki-submit'  => 'Xem',

'fewestrevisions' => 'Trang có ít sửa đổi nhất',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|thể loại|thể loại}}',
'nlinks'                  => '$1 {{PLURAL:$1|liên kết|liên kết}}',
'nmembers'                => '$1 {{PLURAL:$1|trang|trang}}',
'nrevisions'              => '$1 {{PLURAL:$1|phiên bản|phiên bản}}',
'nviews'                  => '$1 {{PLURAL:$1|lượt truy cập|lượt truy cập}}',
'nimagelinks'             => 'Được sử dụng trong $1 trang',
'ntransclusions'          => 'được sử dụng trong $1 trang',
'specialpage-empty'       => 'Trang này đang trống.',
'lonelypages'             => 'Trang mồ côi',
'lonelypagestext'         => 'Chưa có trang nào liên kết đến hoặc nhúng vào các trang này tại {{SITENAME}}.',
'uncategorizedpages'      => 'Trang chưa xếp thể loại',
'uncategorizedcategories' => 'Thể loại chưa phân loại',
'uncategorizedimages'     => 'Tập tin chưa được phân loại',
'uncategorizedtemplates'  => 'Bản mẫu chưa được phân loại',
'unusedcategories'        => 'Thể loại trống',
'unusedimages'            => 'Tập tin chưa dùng',
'popularpages'            => 'Trang nhiều người đọc',
'wantedcategories'        => 'Thể loại cần thiết',
'wantedpages'             => 'Trang cần viết',
'wantedpages-badtitle'    => 'Tiêu đề không hợp lệ trong tập kết quả: $1',
'wantedfiles'             => 'Tập tin cần thiết',
'wantedtemplates'         => 'Bản mẫu cần viết nhất',
'mostlinked'              => 'Trang được liên kết đến nhiều nhất',
'mostlinkedcategories'    => 'Thể loại có nhiều trang nhất',
'mostlinkedtemplates'     => 'Bản mẫu được liên kết đến nhiều nhất',
'mostcategories'          => 'Các trang có nhiều thể loại nhất',
'mostimages'              => 'Tập tin được liên kết đến nhiều nhất',
'mostrevisions'           => 'Các trang được sửa đổi nhiều lần nhất',
'prefixindex'             => 'Tất cả các trang trùng với tiền tố',
'shortpages'              => 'Trang ngắn nhất',
'longpages'               => 'Trang dài nhất',
'deadendpages'            => 'Trang đường cùng',
'deadendpagestext'        => 'Các trang này không có liên kết đến trang khác trong {{SITENAME}}.',
'protectedpages'          => 'Trang bị khóa',
'protectedpages-indef'    => 'Chỉ hiển thị khóa vô hạn',
'protectedpages-cascade'  => 'Chỉ hiển thị khóa theo tầng',
'protectedpagestext'      => 'Các trang này bị khóa không cho sửa đổi hay di chuyển',
'protectedpagesempty'     => 'Hiện không có trang nào bị khóa với các thông số này.',
'protectedtitles'         => 'Các tựa trang được bảo vệ',
'protectedtitlestext'     => 'Các tựa trang sau đây đã bị khóa không cho tạo mới',
'protectedtitlesempty'    => 'Không có tựa trang nào bị khóa với các thông số như vậy.',
'listusers'               => 'Danh sách thành viên',
'listusers-editsonly'     => 'Chỉ hiện thành viên có tham gia sửa đổi',
'listusers-creationsort'  => 'Xếp theo ngày khởi tạo',
'usereditcount'           => '$1 {{PLURAL:$1|sửa đổi|sửa đổi}}',
'usercreated'             => 'Tạo tài khoản $1 lúc $2',
'newpages'                => 'Các trang mới nhất',
'newpages-username'       => 'Tên người dùng:',
'ancientpages'            => 'Các trang cũ nhất',
'move'                    => 'Di chuyển',
'movethispage'            => 'Di chuyển trang này',
'unusedimagestext'        => 'Các tập tin sau tồn tại nhưng chưa được nhúng vào trang nào.
Xin lưu ý là các trang Web bên ngoài có thể liên kết đến một tập tin ở đây qua một địa chỉ URL trực tiếp, do đó nhiều tập tin vẫn được liệt kê ở đây dù có thể nó đang được sử dụng.',
'unusedcategoriestext'    => 'Các trang thể loại này tồn tại mặc dù không có trang hay tiểu thể loại nào thuộc về nó.',
'notargettitle'           => 'Chưa có mục tiêu',
'notargettext'            => 'Xin chỉ rõ trang hoặc thành viên cần thực hiện tác vụ.',
'nopagetitle'             => 'Không có trang đích nào như vậy',
'nopagetext'              => 'Trang đích bạn chỉ định không tồn tại.',
'pager-newer-n'           => '{{PLURAL:$1|1|$1}} mới hơn',
'pager-older-n'           => '{{PLURAL:$1|1|$1}} cũ hơn',
'suppress'                => 'Giám sát viên',

# Book sources
'booksources'               => 'Nguồn sách',
'booksources-search-legend' => 'Tìm kiếm nguồn sách',
'booksources-go'            => 'Tìm kiếm',
'booksources-text'          => 'Dưới đây là danh sách những trang bán sách mới và cũ, đồng thời có thể có thêm thông tin về những cuốn sách bạn đang tìm:',
'booksources-invalid-isbn'  => 'ISBN mà bạn cung cấp dường như không đúng; xin hãy kiểm tra lại xem có lỗi gì khi sao chép từ nội dung gốc hay không.',

# Special:Log
'specialloguserlabel'  => 'Thành viên:',
'speciallogtitlelabel' => 'Tên trang:',
'log'                  => 'Nhật trình',
'all-logs-page'        => 'Tất cả các nhật trình công khai',
'alllogstext'          => 'Hiển thị tất cả các nhật trình đang có của {{SITENAME}} chung với nhau.
Bạn có thể thu hẹp kết quả bằng cách chọn loại nhật trình, tên thành viên (phân biệt chữ hoa-chữ thường), hoặc các trang bị ảnh hưởng (cũng phân biệt chữ hoa-chữ thường).',
'logempty'             => 'Không có mục nào khớp với từ khóa.',
'log-title-wildcard'   => 'Tìm các tựa trang bắt đầu bằng các chữ này',

# Special:AllPages
'allpages'          => 'Tất cả các trang',
'alphaindexline'    => '$1 đến $2',
'nextpage'          => 'Trang sau ($1)',
'prevpage'          => 'Trang trước ($1)',
'allpagesfrom'      => 'Xem trang từ:',
'allpagesto'        => 'Xem đến trang:',
'allarticles'       => 'Mọi trang',
'allinnamespace'    => 'Mọi trang (không gian $1)',
'allnotinnamespace' => 'Mọi trang (không trong không gian $1)',
'allpagesprev'      => 'Trước',
'allpagesnext'      => 'Sau',
'allpagessubmit'    => 'Hiển thị',
'allpagesprefix'    => 'Hiển thị trang có tiền tố:',
'allpagesbadtitle'  => 'Tựa trang không hợp lệ hay chứa tiền tố liên kết ngôn ngữ hoặc liên kết wiki. Nó có thể chứa một hoặc nhiều ký tự không dùng được ở tựa trang.',
'allpages-bad-ns'   => '{{SITENAME}} không có không gian tên “$1”',

# Special:Categories
'categories'                    => 'Thể loại',
'categoriespagetext'            => '{{PLURAL:$1|Thể loại|Các thể loại}} dưới đây có trang hoặc tập tin phương tiện.
Những [[Special:UnusedCategories|thể loại trống]] không được hiển thị tại đây.
Xem thêm [[Special:WantedCategories|thể loại cần thiết]].',
'categoriesfrom'                => 'Hiển thị thể loại bằng đầu từ:',
'special-categories-sort-count' => 'xếp theo số trang',
'special-categories-sort-abc'   => 'xếp theo vần',

# Special:DeletedContributions
'deletedcontributions'             => 'Đóng góp đã bị xóa của thành viên',
'deletedcontributions-title'       => 'Đóng góp đã bị xóa của thành viên',
'sp-deletedcontributions-contribs' => 'đóng góp',

# Special:LinkSearch
'linksearch'       => 'Liên kết ngoài',
'linksearch-pat'   => 'Mẫu liên kết:',
'linksearch-ns'    => 'Không gian tên:',
'linksearch-ok'    => 'Tìm kiếm',
'linksearch-text'  => "Có thể sử dụng ký tự đại diện (''wildcard'') ở đầu tiên, ví dụ “*.wikipedia.org”.<br />Hiện hỗ trợ các giao thức: <tt>$1</tt>",
'linksearch-line'  => '$1 được liên kết từ $2',
'linksearch-error' => "Chỉ được sử dụng ký tự đại diện (''wildcard'') vào đầu tên miền (''hostname'').",

# Special:ListUsers
'listusersfrom'      => 'Hiển thị thành viên bắt đầu từ:',
'listusers-submit'   => 'Liệt kê',
'listusers-noresult' => 'Không thấy thành viên.',
'listusers-blocked'  => '(bị cấm)',

# Special:ActiveUsers
'activeusers'            => 'Danh sách thành viên tích cực',
'activeusers-intro'      => 'Dánh sách này liệt kê các thành viên đã hoạt động cách nào đó trong $1 ngày qua.',
'activeusers-count'      => '$1 {{PLURAL:$1|sửa đổi|sửa đổi}} trong {{PLURAL:$3|ngày|$3 ngày}} gần đây',
'activeusers-from'       => 'Hiển thị thành viên bắt đầu từ:',
'activeusers-hidebots'   => 'Ẩn robot',
'activeusers-hidesysops' => 'Ẩn quản lý viên',
'activeusers-noresult'   => 'Không thấy thành viên.',

# Special:Log/newusers
'newuserlogpage'              => 'Nhật trình mở tài khoản',
'newuserlogpagetext'          => 'Đây là danh sách những tài khoản thành viên mở lên gần đây.',
'newuserlog-byemail'          => 'gửi mật khẩu qua thư điện tử',
'newuserlog-create-entry'     => 'đã mở tài khoản mới',
'newuserlog-create2-entry'    => 'đã tạo tài khoản mới với tên $1',
'newuserlog-autocreate-entry' => 'Tài khoản được tạo tự động',

# Special:ListGroupRights
'listgrouprights'                      => 'Nhóm thành viên',
'listgrouprights-summary'              => 'Dưới đây là danh sách nhóm thành viên được định nghĩa tại wiki này, với mức độ truy cập của từng nhóm.
Có [[{{MediaWiki:Listgrouprights-helppage}}|thông tin thêm]] về từng nhóm riêng biệt.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Quyền được trao</span>
* <span class="listgrouprights-revoked">Quyền bị tước</span>',
'listgrouprights-group'                => 'Nhóm',
'listgrouprights-rights'               => 'Khả năng',
'listgrouprights-helppage'             => 'Help:Khả năng của nhóm thành viên',
'listgrouprights-members'              => '(danh sách thành viên)',
'listgrouprights-addgroup'             => 'Có thể thêm {{PLURAL:$2|nhóm|các nhóm}}: $1',
'listgrouprights-removegroup'          => 'Có thể bỏ {{PLURAL:$2|nhóm|các nhóm}}: $1',
'listgrouprights-addgroup-all'         => 'Có thể thêm tất cả các nhóm',
'listgrouprights-removegroup-all'      => 'Có thể bỏ tất cả các nhóm',
'listgrouprights-addgroup-self'        => 'Có thể đưa tài khoản của chính mình vào {{PLURAL:$2|nhóm|các nhóm}}: $1',
'listgrouprights-removegroup-self'     => 'Có thể loại tài khoản của chính mình ra khỏi {{PLURAL:$2|nhóm|các nhóm}}: $1',
'listgrouprights-addgroup-self-all'    => 'Có thể đưa tài khoản của chính mình vào tất cả các nhóm',
'listgrouprights-removegroup-self-all' => 'Có thể loại tài khoản của chính mình ra khỏi tất cả các nhóm',

# E-mail user
'mailnologin'          => 'Không có địa chỉ gửi thư',
'mailnologintext'      => 'Bạn phải [[Special:UserLogin|đăng nhập]] và khai báo một địa chỉ thư điện tử hợp lệ trong phần [[Special:Preferences|tùy chọn cá nhân]] thì mới gửi được thư cho người khác.',
'emailuser'            => 'Gửi thư cho người này',
'emailpage'            => 'Gửi thư',
'emailpagetext'        => 'Mẫu dưới đây sẽ gửi một bức thư điện tử tới người dùng này.
Địa chỉ thư điện tử mà bạn đã cung cấp trong [[Special:Preferences|tùy chọn cá nhân của mình]] sẽ xuất hiện trong phần địa chỉ “Người gửi” của bức thư, do đó người nhận sẽ có thể trả lời trực tiếp cho bạn.',
'usermailererror'      => 'Lỗi gửi thư:',
'defemailsubject'      => 'thư gửi từ {{SITENAME}}',
'usermaildisabled'     => 'Chức năng gửi thư cho người dùng đã bị tắt.',
'usermaildisabledtext' => 'Bạn không thể gửi thư điện tử cho những người dùng khác trên wiki này.',
'noemailtitle'         => 'Không có địa chỉ nhận thư',
'noemailtext'          => 'Người này không cung cấp một địa chỉ thư hợp lệ.',
'nowikiemailtitle'     => 'Không cho phép thư điện tử',
'nowikiemailtext'      => 'Thành viên này quyết định không nhận thư từ các thành viên khác.',
'email-legend'         => 'Gửi thư điện tử đến thành viên {{SITENAME}} khác',
'emailfrom'            => 'Người gửi:',
'emailto'              => 'Người nhận:',
'emailsubject'         => 'Chủ đề:',
'emailmessage'         => 'Nội dung:',
'emailsend'            => 'Gửi',
'emailccme'            => 'Gửi cho tôi bản sao của thư này.',
'emailccsubject'       => 'Bản sao của thư gửi cho $1: $2',
'emailsent'            => 'Đã gửi',
'emailsenttext'        => 'Thư của bạn đã được gửi.',
'emailuserfooter'      => 'Thư điện tử này được $1 gửi đến $2 thông qua chức năng “Gửi thư cho người này” của {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Đang để lại thông báo hệ thống.',
'usermessage-editor'  => 'Trình thông báo hệ thống',

# Watchlist
'watchlist'            => 'Trang tôi theo dõi',
'mywatchlist'          => 'Trang tôi theo dõi',
'watchlistfor2'        => 'Của $1 $2',
'nowatchlist'          => 'Danh sách theo dõi của bạn không có gì.',
'watchlistanontext'    => 'Xin hãy $1 để xem hay sửa đổi các trang được theo dõi.',
'watchnologin'         => 'Chưa đăng nhập',
'watchnologintext'     => 'Bạn phải [[Special:UserLogin|đăng nhập]] mới sửa đổi được danh sách theo dõi.',
'addedwatch'           => 'Đã thêm vào danh sách theo dõi',
'addedwatchtext'       => 'Trang “<nowiki>$1</nowiki>” đã được cho vào [[Special:Watchlist|danh sách theo dõi]]. Những sửa đổi đối với trang này và trang thảo luận của nó sẽ được liệt kê, và được <b>tô đậm</b> trong [[Special:RecentChanges|danh sách các thay đổi mới]].

Nếu bạn muốn cho trang này ra khỏi danh sách theo dõi, nhấn vào "Ngừng theo dõi" ở trên.',
'removedwatch'         => 'Đã ra khỏi danh sách theo dõi',
'removedwatchtext'     => 'Trang “[[:$1]]” đã được đưa ra khỏi [[Special:Watchlist|danh sách theo dõi]] của bạn.',
'watch'                => 'Theo dõi',
'watchthispage'        => 'Theo dõi trang này',
'unwatch'              => 'Ngừng theo dõi',
'unwatchthispage'      => 'Ngừng theo dõi',
'notanarticle'         => 'Không phải trang có nội dung',
'notvisiblerev'        => 'Phiên bản bị xóa',
'watchnochange'        => 'Không có trang nào bạn theo dõi được sửa đổi.',
'watchlist-details'    => 'Bạn đang theo dõi {{PLURAL:$1|$1 trang|$1 trang}}, không kể các trang thảo luận.',
'wlheader-enotif'      => '* Đã bật thông báo qua thư điện tử.',
'wlheader-showupdated' => "* Các trang đã thay đổi từ lần cuối bạn xem chúng được in '''đậm'''",
'watchmethod-recent'   => 'Dưới đây hiện thay đổi mới với các trang theo dõi.',
'watchmethod-list'     => 'Dưới đây hiện danh sách các trang theo dõi.',
'watchlistcontains'    => 'Danh sách theo dõi của bạn có $1 {{PLURAL:$1|trang|trang}}.',
'iteminvalidname'      => 'Tên trang “$1” không hợp lệ…',
'wlnote'               => "Dưới đây là {{PLURAL:$1|sửa đổi cuối cùng|'''$1''' sửa đổi mới nhất}} trong '''$2''' giờ qua.",
'wlshowlast'           => 'Hiển thị $1 giờ $2 ngày gần đây $3',
'watchlist-options'    => 'Tùy chọn về danh sách theo dõi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Đang theo dõi…',
'unwatching' => 'Đang ngừng theo dõi…',

'enotif_mailer'                => 'Thông báo của {{SITENAME}}',
'enotif_reset'                 => 'Đánh dấu đã xem mọi trang',
'enotif_newpagetext'           => 'Trang này mới',
'enotif_impersonal_salutation' => 'thành viên {{SITENAME}}',
'changed'                      => 'thay đổi',
'created'                      => 'viết mới',
'enotif_subject'               => '$PAGETITLE tại {{SITENAME}} đã được $CHANGEDORCREATED bởi $PAGEEDITOR',
'enotif_lastvisited'           => 'Xem $1 để biết các thay đổi diễn ra từ lần xem cuối cùng của bạn.',
'enotif_lastdiff'              => 'Vào $1 để xem sự thay đổi này.',
'enotif_anon_editor'           => 'thành viên vô danh $1',
'enotif_body'                  => 'Xin chào $WATCHINGUSERNAME,


Trang $PAGETITLE tại {{SITENAME}} đã được $PAGEEDITOR $CHANGEDORCREATED vào $PAGEEDITDATE, xem phiên bản hiện hành tại $PAGETITLE_URL.

$NEWPAGE

Tóm lược sửa đổi: $PAGESUMMARY $PAGEMINOREDIT

Liên lạc với người viết trang qua:
thư: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Sẽ không có thông báo nào khác nếu có sự thay đổi tiếp theo trừ khi bạn xem trang đó.
Bạn cũng có thể thiết lập lại việc nhắc nhở cho tất cả các trang nằm trong danh sách theo dõi của bạn.

              Hệ thống báo tin {{SITENAME}} thân thiện của bạn

--
Để thay đổi các thiết lập danh sách theo dõi, mời xem
{{fullurl:{{#special:Watchlist}}/edit}}

Để xóa trang ra khỏi danh sách theo dõi của bạn, mời xem
$UNWATCHURL

Phản hồi và cần sự hỗ trợ:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Xóa trang',
'confirm'                => 'Xác nhận',
'excontent'              => 'nội dung cũ: “$1”',
'excontentauthor'        => 'nội dung cũ: “$1” (người viết duy nhất “[[Special:Contributions/$2|$2]]”)',
'exbeforeblank'          => 'nội dung trước khi tẩy trống: “$1”',
'exblank'                => 'trang trắng',
'delete-confirm'         => 'Xóa “$1”',
'delete-legend'          => 'Xóa',
'historywarning'         => "'''Cảnh báo:''' Trang bạn sắp xóa đã có lịch sử khoảng $1 {{PLURAL:$1|phiên bản|phiên bản}}:",
'confirmdeletetext'      => 'Bạn sắp xóa hẳn một trang cùng với tất cả lịch sử của nó.
Xin xác nhận việc bạn định làm, và hiểu rõ những hệ lụy của nó, và bạn thực hiện nó theo đúng đúng [[{{MediaWiki:Policy-url}}|quy định]].',
'actioncomplete'         => 'Đã thực hiện xong',
'actionfailed'           => 'Tác động bị thất bại',
'deletedtext'            => 'Đã xóa “<nowiki>$1</nowiki>”. Xem danh sách các xóa bỏ gần nhất tại $2.',
'deletedarticle'         => 'đã xóa “[[$1]]”',
'suppressedarticle'      => 'đã giấu "[[$1]]"',
'dellogpage'             => 'Nhật trình xóa',
'dellogpagetext'         => 'Dưới đây là danh sách các trang bị xóa gần đây nhất.',
'deletionlog'            => 'nhật trình xóa',
'reverted'               => 'Đã hồi phục một phiên bản cũ',
'deletecomment'          => 'Lý do:',
'deleteotherreason'      => 'Lý do khác/bổ sung:',
'deletereasonotherlist'  => 'Lý do khác',
'deletereason-dropdown'  => '*Các lý do xóa phổ biến
** Tác giả yêu cầu
** Vi phạm bản quyền
** Phá hoại',
'delete-edit-reasonlist' => 'Sửa lý do xóa',
'delete-toobig'          => 'Trang này có lịch sử sửa đổi lớn, đến hơn {{PLURAL:$1|lần|lần}} sửa đổi.
Việc xóa các trang như vậy bị hạn chế để ngăn ngừa phá hoại do vô ý cho {{SITENAME}}.',
'delete-warning-toobig'  => 'Trang này có lịch sử sửa đổi lớn, đến hơn {{PLURAL:$1|lần|lần}} sửa đổi.
Việc xóa các trang có thể làm tổn hại đến hoạt động của cơ sở dữ liệu {{SITENAME}};
hãy cẩn trọng khi thực hiện.',

# Rollback
'rollback'          => 'Lùi tất cả sửa đổi',
'rollback_short'    => 'Lùi tất cả',
'rollbacklink'      => 'lùi tất cả',
'rollbackfailed'    => 'Lùi sửa đổi không thành công',
'cantrollback'      => 'Không lùi sửa đổi được;
người viết trang cuối cùng cũng là tác giả duy nhất của trang này.',
'alreadyrolled'     => 'Không thể lùi tất cả sửa đổi cuối của [[User:$2|$2]] ([[User talk:$2|thảo luận]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) tại [[:$1]]; ai đó đã thực hiện sửa đổi hoặc thực hiện lùi tất cả rồi.

Sửa đổi cuối cùng tại trang do [[User:$3|$3]] ([[User talk:$3|thảo luận]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) thực hiện.',
'editcomment'       => "Tóm lược sửa đổi: “''$1''”.",
'revertpage'        => 'Đã hủy sửa đổi của [[Special:Contributions/$2|$2]] ([[User talk:$2|Thảo luận]]) quay về phiên bản của [[User:$1|$1]]',
'revertpage-nouser' => 'Lùi sửa đổi của (tên người dùng đã xóa) quay lại phiên bản cuối của [[User:$1|$1]]',
'rollback-success'  => 'Đã hủy sửa đổi của $1;
quay về phiên bản cuối của $2.',

# Edit tokens
'sessionfailure-title' => 'Phiên thất bại',
'sessionfailure'       => 'Dường như có trục trặc với phiên đăng nhập của bạn; thao tác này đã bị hủy để tránh việc cướp quyền đăng nhập. Xin hãy nhấn nút “Back”, tải lại trang đó, rồi thử lại.',

# Protect
'protectlogpage'              => 'Nhật trình khóa',
'protectlogtext'              => 'Dưới đây là danh sách các thao tác khóa và mở khóa trang. Xem [[Special:ProtectedPages|danh sách các trang bị khóa]] để xem danh sách các trang hiện thời đang bị khóa.',
'protectedarticle'            => 'đã khóa “[[$1]]”',
'modifiedarticleprotection'   => 'đã đổi mức khóa cho “[[$1]]”',
'unprotectedarticle'          => 'đã mở khóa cho “[[$1]]”',
'movedarticleprotection'      => 'đã di chuyển thiết lập khóa trang từ “[[$2]]” đến “[[$1]]”',
'protect-title'               => 'Thiết lập mức khóa cho “$1”',
'prot_1movedto2'              => '[[$1]] đổi thành [[$2]]',
'protect-legend'              => 'Xác nhận khóa',
'protectcomment'              => 'Lý do:',
'protectexpiry'               => 'Thời hạn:',
'protect_expiry_invalid'      => 'Thời hạn không hợp lệ.',
'protect_expiry_old'          => 'Thời hạn đã qua.',
'protect-unchain-permissions' => 'Thay đổi các tùy chọn khóa trang khác',
'protect-text'                => "Bạn có thể xem và đổi kiểu khóa trang '''<nowiki>$1</nowiki>''' ở đây.",
'protect-locked-blocked'      => "Bạn không thể đổi mức khóa khi bị cấm. Đây là trạng thái
hiện tại của trang '''$1''':",
'protect-locked-dblock'       => "Hiện không thể đổi mức khóa do cơ sở dữ liệu bị khóa.
Đây là trạng thái hiện tại của trang '''$1''':",
'protect-locked-access'       => "Tài khoản của bạn không được cấp quyền đổi mức khóa của trang.
Đây là trạng thái hiện tại của trang '''$1''':",
'protect-cascadeon'           => 'Trang này hiện bị khóa vì nó được nhúng vào {{PLURAL:$1|những trang|trang}} dưới đây bị khóa với tùy chọn “khóa theo tầng” được kích hoạt. Bạn có thể đổi mức độ khóa của trang này, nhưng nó sẽ không ảnh hưởng đến việc khóa theo tầng.',
'protect-default'             => 'Cho phép mọi thành viên',
'protect-fallback'            => 'Cần quyền “$1”',
'protect-level-autoconfirmed' => 'Cấm thành viên mới và thành viên chưa đăng ký',
'protect-level-sysop'         => 'Cấm mọi thành viên (trừ quản lý)',
'protect-summary-cascade'     => 'khóa theo tầng',
'protect-expiring'            => 'hết hạn $1 (UTC)',
'protect-expiry-indefinite'   => 'vô thời hạn',
'protect-cascade'             => 'Tự động khóa các trang được nhúng vào trang này (khóa theo tầng)',
'protect-cantedit'            => 'Bạn không thể thay đổi mức khóa cho trang này do không có đủ quyền hạn.',
'protect-othertime'           => 'Thời hạn khác:',
'protect-othertime-op'        => 'thời hạn khác',
'protect-existing-expiry'     => 'Thời hạn hiện thời: $3, $2',
'protect-otherreason'         => 'Lý do khác/bổ sung:',
'protect-otherreason-op'      => 'Lý do khác',
'protect-dropdown'            => '*Các lý do thường dùng khi khóa
** Bị phá hoại quá mức
** Bị spam quá mức
** Bút chiến thiếu tính xây dựng
** Trang nhiều người xem',
'protect-edit-reasonlist'     => 'Sửa lý do khóa trang',
'protect-expiry-options'      => '1 giờ:1 hour,1 ngày:1 day,1 tuần:1 week,2 tuần:2 weeks,1 tháng:1 month,3 tháng:3 months,6 tháng:6 months,1 năm:1 year,vô hạn:infinite',
'restriction-type'            => 'Quyền:',
'restriction-level'           => 'Mức độ hạn chế:',
'minimum-size'                => 'Kích thước tối thiểu',
'maximum-size'                => 'Kích thước tối đa:',
'pagesize'                    => '(byte)',

# Restrictions (nouns)
'restriction-edit'   => 'Sửa đổi',
'restriction-move'   => 'Di chuyển',
'restriction-create' => 'Tạo mới',
'restriction-upload' => 'Tải lên',

# Restriction levels
'restriction-level-sysop'         => 'khóa hẳn',
'restriction-level-autoconfirmed' => 'hạn chế sửa đổi',
'restriction-level-all'           => 'mọi mức độ',

# Undelete
'undelete'                     => 'Xem các trang bị xóa',
'undeletepage'                 => 'Xem và phục hồi trang bị xóa',
'undeletepagetitle'            => "'''Sau đây là những phiên bản đã bị xóa của [[:$1]].'''",
'viewdeletedpage'              => 'Xem các trang bị xóa',
'undeletepagetext'             => '{{PLURAL:$1|Trang sau|$1 trang sau}} đã bị xóa nhưng vẫn nằm trong kho lưu trữ và có thể phục hồi được. Kho lưu trữ sẽ được xóa định kỳ.',
'undelete-fieldset-title'      => 'Phục hồi phiên bản',
'undeleteextrahelp'            => "Để phục hồi toàn bộ lịch sử trang, hãy để trống các hộp kiểm và bấm nút '''''Phục hồi'''''.
Để thực hiện phục hồi có chọn lọc, hãy đánh dấu vào hộp kiểm của các phiên bản muốn phục hồi, rồi bấm nút '''''Phục hồi'''''.
Bấm nút '''''Tẩy trống''''' sẽ tẩy trống ô lý do và tất cả các hộp kiểm.",
'undeleterevisions'            => '$1 {{PLURAL:$1|bản|bản}} đã được lưu',
'undeletehistory'              => 'Nếu bạn phục hồi trang này, tất cả các phiên bản của nó cũng sẽ được phục hồi vào lịch sử của trang.
Nếu một trang mới có cùng tên đã được tạo ra kể từ khi xóa trang này, các phiên bản được khôi phục sẽ xuất hiện trong lịch sử trước.',
'undeleterevdel'               => 'Việc phục hồi sẽ không được thực hiện nếu nó dẫn đến việc phiên bản trang hoặc tập tin trên cùng bị xóa mất một phần.
Trong trường hợp đó, bạn phải bỏ đánh dấu hộp kiểm hoặc bỏ ẩn những phiên bản bị xóa mới nhất.',
'undeletehistorynoadmin'       => 'Trang này đã bị xóa.
Lý do xóa trang được hiển thị dưới đây, cùng với thông tin về những người đã sửa đổi trang này trước khi bị xóa.
Chỉ có người quản lý mới xem được văn bản đầy đủ của những phiên bản trang bị xóa.',
'undelete-revision'            => 'Phiên bản đã xóa của $1 (vào lúc $4 tại $5) do $3 sửa đổi:',
'undeleterevision-missing'     => 'Phiên bản này không hợp lệ hay không tồn tại. Đây có thể là một địa chỉ sai, hoặc là phiên bản đã được phục hồi hoặc đã xóa khỏi kho lưu trữ.',
'undelete-nodiff'              => 'Không tìm thấy phiên bản cũ hơn.',
'undeletebtn'                  => 'Phục hồi',
'undeletelink'                 => 'xem lại/phục hồi',
'undeleteviewlink'             => 'xem',
'undeletereset'                => 'Tẩy trống',
'undeleteinvert'               => 'Đảo sự lựa chọn',
'undeletecomment'              => 'Lý do:',
'undeletedarticle'             => 'đã phục hồi “$1”',
'undeletedrevisions'           => '$1 {{PLURAL:$1|bản|bản}} được phục hồi',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|bản|bản}} và $2 {{PLURAL:$2|tập tin|tập tin}} đã được phục hồi',
'undeletedfiles'               => '$1 {{PLURAL:$1|tập tin|tập tin}} đã được phục hồi',
'cannotundelete'               => 'Phục hồi thất bại;
một người nào khác đã phục hồi trang này rồi.',
'undeletedpage'                => "'''$1 đã được khôi phục'''

Xem nhật trình xóa và phục hồi các trang gần đây tại [[Special:Log/delete|nhật trình xóa]].",
'undelete-header'              => 'Xem các trang bị xóa gần đây tại [[Special:Log/delete|nhật trình xóa]].',
'undelete-search-box'          => 'Tìm kiếm trang đã bị xóa',
'undelete-search-prefix'       => 'Hiển thị trang có tiền tố:',
'undelete-search-submit'       => 'Tìm kiếm',
'undelete-no-results'          => 'Không tìm thấy trang đã bị xóa nào khớp với từ khóa.',
'undelete-filename-mismatch'   => 'Không thể phục hồi phiên bản tập tin vào thời điểm $1: không có tập tin trùng tên',
'undelete-bad-store-key'       => 'Không thể phục hồi phiên bản tập tin tại thời điểm $1: tập tin không tồn tại trước khi xóa.',
'undelete-cleanup-error'       => 'Có lỗi khi xóa các tập tin lưu trữ “$1” không được sử dụng.',
'undelete-missing-filearchive' => 'Không thể phục hồi bộ tập tin có định danh $1 vì nó không nằm ở cơ sở dữ liệu. Có thể nó được phục hồi rồi.',
'undelete-error-short'         => 'Có lỗi khi phục hồi tập tin: $1',
'undelete-error-long'          => 'Xuất hiện lỗi khi phục hồi tập tin:

$1',
'undelete-show-file-confirm'   => 'Bạn có chắc mình muốn xem một phiên bản đã xóa của tập tin “<nowiki>$1</nowiki>” vào ngày $2 lúc $3?',
'undelete-show-file-submit'    => 'Có',

# Namespace form on various pages
'namespace'      => 'Không gian tên:',
'invert'         => 'Đảo ngược lựa chọn',
'blanknamespace' => '(Chính)',

# Contributions
'contributions'       => 'Đóng góp của thành viên',
'contributions-title' => 'Đóng góp của thành viên $1',
'mycontris'           => 'Đóng góp của tôi',
'contribsub2'         => 'Của $1 ($2)',
'nocontribs'          => 'Không tìm thấy thay đổi nào khớp với yêu cầu.',
'uctop'               => '(mới nhất)',
'month'               => 'Từ tháng (trở về trước):',
'year'                => 'Từ năm (trở về trước):',

'sp-contributions-newbies'             => 'Chỉ hiển thị đóng góp của tài khoản mới',
'sp-contributions-newbies-sub'         => 'Các thành viên mới',
'sp-contributions-newbies-title'       => 'Đóng góp của các thành viên mới',
'sp-contributions-blocklog'            => 'Nhật trình cấm',
'sp-contributions-deleted'             => 'đóng góp đã bị xóa của thành viên',
'sp-contributions-uploads'             => 'tập tin tải lên',
'sp-contributions-logs'                => 'nhật trình',
'sp-contributions-talk'                => 'thảo luận',
'sp-contributions-userrights'          => 'quản lý quyền thành viên',
'sp-contributions-blocked-notice'      => 'Thành viên này hiện đang bị cấm sửa đổi. Nhật trình cấm gần nhất được ghi ở dưới để tiện theo dõi:',
'sp-contributions-blocked-notice-anon' => 'Địa chỉ IP này đang bị cấm. Hãy tham khảo mục mới nhất trong nhật trình cấm IP này:',
'sp-contributions-search'              => 'Tìm kiếm đóng góp',
'sp-contributions-username'            => 'Địa chỉ IP hay tên thành viên:',
'sp-contributions-toponly'             => 'Chỉ hiện các phiên bản gần đây',
'sp-contributions-submit'              => 'Tìm kiếm',

# What links here
'whatlinkshere'            => 'Các liên kết đến đây',
'whatlinkshere-title'      => 'Các trang liên kết đến “$1”',
'whatlinkshere-page'       => 'Trang:',
'linkshere'                => "Các trang sau liên kết đến '''[[:$1]]''':",
'nolinkshere'              => "Không có trang nào liên kết đến '''[[:$1]]'''.",
'nolinkshere-ns'           => "Không có trang nào liên kết đến '''[[:$1]]''' trong không gian tên đã chọn.",
'isredirect'               => 'trang đổi hướng',
'istemplate'               => 'được nhúng vào',
'isimage'                  => 'liên kết hình',
'whatlinkshere-prev'       => '{{PLURAL:$1|kết quả trước|$1 kết quả trước}}',
'whatlinkshere-next'       => '{{PLURAL:$1|kết quả sau|$1 kết quả sau}}',
'whatlinkshere-links'      => '← liên kết',
'whatlinkshere-hideredirs' => '$1 trang đổi hướng',
'whatlinkshere-hidetrans'  => '$1 trang nhúng',
'whatlinkshere-hidelinks'  => '$1 liên kết',
'whatlinkshere-hideimages' => '$1 liên kết hình',
'whatlinkshere-filters'    => 'Bộ lọc',

# Block/unblock
'blockip'                         => 'Cấm thành viên',
'blockip-title'                   => 'Cấm thành viên',
'blockip-legend'                  => 'Cấm thành viên',
'blockiptext'                     => 'Dùng mẫu dưới để cấm một địa chỉ IP hoặc thành viên không được viết trang.
Điều này chỉ nên làm để tránh phá hoại, và phải theo [[{{MediaWiki:Policy-url}}|quy định]].
Điền vào lý do cụ thể ở dưới (ví dụ, chỉ ra trang nào bị phá hoại).',
'ipaddress'                       => 'Địa chỉ IP:',
'ipadressorusername'              => 'Địa chỉ IP hay tên thành viên:',
'ipbexpiry'                       => 'Thời hạn:',
'ipbreason'                       => 'Lý do:',
'ipbreasonotherlist'              => 'Lý do khác',
'ipbreason-dropdown'              => '*Một số lý do cấm thường gặp
** Phá hoại
** Thêm thông tin sai lệch
** Xóa nội dung trang
** Gửi liên kết spam đến trang web bên ngoài
** Cho thông tin rác vào trang
** Có thái độ dọa dẫm/quấy rối
** Dùng nhiều tài khoản
** Tên thành viên không được chấp nhận
** Tạo nhiều trang mới vi phạm bản quyền, bỏ qua thảo luận và cảnh báo
** Truyền nhiều hình ảnh thiếu nguồn gốc hoặc bản quyền
** Con rối của thành viên bị cấm',
'ipbanononly'                     => 'Chỉ cấm thành viên vô danh',
'ipbcreateaccount'                => 'Cấm mở tài khoản',
'ipbemailban'                     => 'Không cho gửi email',
'ipbenableautoblock'              => 'Tự động cấm các địa chỉ IP mà thành viên này sử dụng',
'ipbsubmit'                       => 'Cấm',
'ipbother'                        => 'Thời hạn khác:',
'ipboptions'                      => '2 giờ:2 hours,1 ngày:1 day,3 ngày:3 days,1 tuần:1 week,2 tuần:2 weeks,1 tháng:1 month,3 tháng:3 months,6 tháng:6 months,1 năm:1 year,vô hạn:infinite',
'ipbotheroption'                  => 'khác',
'ipbotherreason'                  => 'Lý do khác',
'ipbhidename'                     => 'Ẩn tên người dùng ra khỏi các sửa đổi và danh sách',
'ipbwatchuser'                    => 'Theo dõi trang thành viên và thảo luận thành viên của thành viên này',
'ipballowusertalk'                => 'Cho phép người dùng sửa trang thảo luận của chính họ trong khi bị khóa',
'ipb-change-block'                => 'Cấm người dùng này lại theo các thiết lập này',
'badipaddress'                    => 'Địa chỉ IP không hợp lệ',
'blockipsuccesssub'               => 'Cấm thành công',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] đã bị cấm.
<br />Xem lại những lần cấm tại [[Special:IPBlockList|danh sách cấm]].',
'ipb-edit-dropdown'               => 'Sửa đổi lý do cấm',
'ipb-unblock-addr'                => 'Bỏ cấm $1',
'ipb-unblock'                     => 'Bỏ cấm thành viên hay địa chỉ IP',
'ipb-blocklist'                   => 'Xem danh sách đang bị cấm',
'ipb-blocklist-contribs'          => 'Đóng góp của $1',
'unblockip'                       => 'Bỏ cấm thành viên',
'unblockiptext'                   => 'Sử dụng mẫu sau để phục hồi lại quyền sửa đổi đối với một địa chỉ IP hoặc tên thành viên đã bị cấm trước đó.',
'ipusubmit'                       => 'Bỏ cấm',
'unblocked'                       => '[[User:$1|$1]] đã hết bị cấm',
'unblocked-id'                    => '$1 đã hết bị cấm',
'ipblocklist'                     => 'Địa chỉ IP và tên người dùng bị cấm',
'ipblocklist-legend'              => 'Tìm một thành viên bị cấm',
'ipblocklist-username'            => 'Tên thành viên hoặc địa chỉ IP:',
'ipblocklist-sh-userblocks'       => '$1 tác vụ cấm vĩnh viễn',
'ipblocklist-sh-tempblocks'       => '$1 tác vụ cấm có thời hạn',
'ipblocklist-sh-addressblocks'    => '$1 tác vụ cấm IP',
'ipblocklist-submit'              => 'Tìm kiếm',
'ipblocklist-localblock'          => 'Cấm tại wiki này',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Lần|Các lần}} cấm khác',
'blocklistline'                   => '$1, $2 đã cấm $3 (hết hạn $4)',
'infiniteblock'                   => 'vô hạn',
'expiringblock'                   => 'hết hạn vào ngày $1 lúc $2',
'anononlyblock'                   => 'chỉ cấm vô danh',
'noautoblockblock'                => 'đã tắt chức năng tự động cấm',
'createaccountblock'              => 'không được mở tài khoản',
'emailblock'                      => 'đã cấm thư điện tử',
'blocklist-nousertalk'            => 'không được sửa đổi trang thảo luận cá nhân',
'ipblocklist-empty'               => 'Danh sách cấm hiện đang trống.',
'ipblocklist-no-results'          => 'Địa chỉ IP hoặc tên thành viên này hiện không bị cấm.',
'blocklink'                       => 'cấm',
'unblocklink'                     => 'bỏ cấm',
'change-blocklink'                => 'đổi mức cấm',
'contribslink'                    => 'đóng góp',
'autoblocker'                     => 'Bạn bị tự động cấm vì địa chỉ IP của bạn vừa rồi đã được “[[User:$1|$1]]” sử dụng. Lý do đưa ra cho việc cấm $1 là: ”$2”',
'blocklogpage'                    => 'Nhật trình cấm',
'blocklog-showlog'                => 'Thành viên này trước đây đã bị cấm. Nhật trình cấm được ghi ra ở đây để tiện theo dõi:',
'blocklog-showsuppresslog'        => 'Thành viên trước đây đã từng bị cấm và ẩn đi. Nhật trình ẩn được ghi dưới đây để tiện theo dõi:',
'blocklogentry'                   => 'đã cấm [[$1]] với thời hạn là $2 $3',
'reblock-logentry'                => 'thay đổi thiết lập cấm [[$1]] thành thời hạn $2 $3',
'blocklogtext'                    => 'Đây là nhật trình ghi lại những lần cấm và bỏ cấm. Các địa chỉ IP bị cấm tự động không được liệt kê ở đây. Xem thêm [[Special:IPBlockList|danh sách cấm]] để có danh sách cấm và cấm hẳn hiện tại.',
'unblocklogentry'                 => 'đã bỏ cấm “$1”',
'block-log-flags-anononly'        => 'chỉ cấm thành viên vô danh',
'block-log-flags-nocreate'        => 'cấm mở tài khoản',
'block-log-flags-noautoblock'     => 'tắt tự động cấm',
'block-log-flags-noemail'         => 'cấm thư điện tử',
'block-log-flags-nousertalk'      => 'không được sửa trang thảo luận của mình',
'block-log-flags-angry-autoblock' => 'bật tự động cấm nâng cao',
'block-log-flags-hiddenname'      => 'đã ẩn tên người dùng',
'range_block_disabled'            => 'Đã tắt khả năng cấm hàng loạt của quản lý.',
'ipb_expiry_invalid'              => 'Thời điểm hết hạn không hợp lệ.',
'ipb_expiry_temp'                 => 'Cấm tên người dùng ẩn nên là cấm vô hạn.',
'ipb_hide_invalid'                => 'Không thể ẩn tài khoản này; có thể do nó có quá nhiều sửa đổi.',
'ipb_already_blocked'             => '“$1” đã bị cấm rồi',
'ipb-needreblock'                 => '== Đã bị cấm ==
$1 đã bị cấm. Bạn có muốn thay đổi các thiết lập?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Tác vụ cấm|Các tác vụ cấm}} khác',
'ipb_cant_unblock'                => 'Lỗi: Không tìm được ID cấm $1. Địa chỉ IP này có thể đã được bỏ cấm.',
'ipb_blocked_as_range'            => 'Lỗi: Địa chỉ IP $1 không bị cấm trực tiếp và do đó không thể bỏ cấm. Tuy nhiên, nó bị cấm do là một bộ phận của dải IP $2, bạn có thể bỏ cấm dải này.',
'ip_range_invalid'                => 'Dải IP không hợp lệ.',
'ip_range_toolarge'               => 'Không được phép cấm dải IP lớn hơn /$1.',
'blockme'                         => 'Cấm tôi',
'proxyblocker'                    => 'Cấm proxy',
'proxyblocker-disabled'           => 'Chức năng này đã bị tắt.',
'proxyblockreason'                => 'Địa chỉ IP của bạn đã bị cấm vì là proxy mở. Xin hãy liên hệ nhà cung cấp dịch vụ Internet hoặc bộ phận hỗ trợ kỹ thuật của bạn và thông báo với họ về vấn đề an ninh nghiêm trọng này.',
'proxyblocksuccess'               => 'Xong.',
'sorbsreason'                     => 'Địa chỉ IP của bạn bị liệt kê là một proxy mở trong DNSBL mà {{SITENAME}} đang sử dụng.',
'sorbs_create_account_reason'     => 'Địa chỉ chỉ IP của bạn bị liệt kê là một proxy mở trong DNSBL mà {{SITENAME}} đang sử dụng. Bạn không thể mở tài khoản.',
'cant-block-while-blocked'        => 'Bạn không thể cấm thành viên khác trong khi bạn đang bị cấm.',
'cant-see-hidden-user'            => 'Thành viên bạn muốn cấm đã bị cấm trước đây hoặc đã bị ẩn đi. Vì bạn không có quyền hideuser, bạn không thể xem hoặc thay đổi mức cấm của thành viên.',
'ipbblocked'                      => 'Bạn không thể cấm hay bỏ cấm người dùng khác vì chính bạn đang bị cấm.',
'ipbnounblockself'                => 'Bạn không được bỏ cấm chính mình.',

# Developer tools
'lockdb'              => 'Khóa cơ sở dữ liệu',
'unlockdb'            => 'Mở khóa cơ sở dữ liệu',
'lockdbtext'          => 'Khóa cơ sở dữ liệu sẽ ngưng tất cả khả năngsửa đổi các trang, thay đổi tùy chọn cá nhân, sửa danh sách theo dõi, và những thao tác khác của thành viên đòi hỏi phải thay đổi trong cơ sở dữ liệu.
Xin hãy xác nhận những việc bạn định làm, và rằng bạn sẽ mở khóa cơ sở dữ liệu khi xong công việc bảo trì của bạn.',
'unlockdbtext'        => 'Mở khóa cơ sở dữ liệu sẽ khôi phục lại tất cả khả năng sửa đổi trang, thay đổi tùy chọn cá nhân, sửa đổi danh sách theo dõi,
và nhiều thao tác khác của thành viên đòi hỏi phải có thay đổi trong cơ sở dữ liệu.
Xin hãy xác nhận đây là điều bạn định làm.',
'lockconfirm'         => 'Vâng, tôi thực sự muốn khóa cơ sở dữ liệu.',
'unlockconfirm'       => 'Vâng, tôi thực sự muốn mở khóa cơ sở dữ liệu.',
'lockbtn'             => 'Khóa cơ sở dữ liệu',
'unlockbtn'           => 'Mở khóa cơ sở dữ liệu',
'locknoconfirm'       => 'Bạn đã không đánh vào ô xác nhận.',
'lockdbsuccesssub'    => 'Đã khóa cơ sở dữ liệu thành công.',
'unlockdbsuccesssub'  => 'Đã mở khóa cơ sở dữ liệu thành công',
'lockdbsuccesstext'   => 'Cơ sở dữ liệu đã bị khóa.
<br />Nhớ [[Special:UnlockDB|mở khóa]] sau khi bảo trì xong.',
'unlockdbsuccesstext' => 'Cơ sở dữ liệu đã được mở khóa.',
'lockfilenotwritable' => 'Tập tin khóa của cơ sở dữ liệu không cho phép ghi. Để khóa hay mở khóa cơ sở dữ liệu, máy chủ web phải có khả năng ghi tập tin.',
'databasenotlocked'   => 'Cơ sở dữ liệu không bị khóa.',

# Move page
'move-page'                    => 'Di chuyển $1',
'move-page-legend'             => 'Di chuyển trang',
'movepagetext'                 => "Dùng mẫu dưới đây để đổi tên một trang, di chuyển tất cả lịch sử của nó sang tên mới.
Tên cũ sẽ trở thành trang đổi hướng sang tên mới.
Bạn có thể cập nhật tự động các trang đổi hướng đến tên cũ.
Nếu bạn chọn không cập nhật, hãy nhớ kiểm tra [[Special:DoubleRedirects|đổi hướng kép]] hoặc [[Special:BrokenRedirects|đổi hướng đến trang không tồn tại]].
Bạn phải chịu trách nhiệm đảm bảo các liên kết đó tiếp tục trỏ đến nơi chúng cần đến.

Chú ý rằng trang sẽ '''không''' bị di chuyển nếu đã có một trang tại tên mới, trừ khi nó rỗng hoặc là trang đổi hướng và không có lịch sử sửa đổi trước đây.
Điều này có nghĩa là bạn có thể đổi tên trang lại như cũ nếu bạn có nhầm lẫn, và bạn không thể ghi đè lên một trang đã có sẵn.

'''CẢNH BÁO!'''
Việc làm này có thể dẫn đến sự thay đổi mạnh mẽ và không lường trước đối với các trang dễ nhìn thấy;
xin hãy chắc chắn rằng bạn đã nhận thức được những hệ lụy của nó trước khi thực hiện.",
'movepagetext-noredirectfixer' => "Dùng mẫu dưới đây để đổi tên một trang, di chuyển tất cả lịch sử của nó sang tên mới.
Tên cũ sẽ trở thành trang đổi hướng sang tên mới.
Hãy nhớ kiểm tra [[Special:DoubleRedirects|đổi hướng kép]] hoặc [[Special:BrokenRedirects|đổi hướng đến trang không tồn tại]].
Bạn phải chịu trách nhiệm đảm bảo các liên kết đó tiếp tục trỏ đến nơi chúng cần đến.

Chú ý rằng trang sẽ '''không''' bị di chuyển nếu đã có một trang tại tên mới, trừ khi nó rỗng hoặc là trang đổi hướng và không có lịch sử sửa đổi trước đây.
Điều này có nghĩa là bạn có thể đổi tên trang lại như cũ nếu bạn có nhầm lẫn, và bạn không thể ghi đè lên một trang đã có sẵn.

'''CẢNH BÁO!'''
Việc làm này có thể dẫn đến sự thay đổi mạnh mẽ và không lường trước đối với các trang dễ nhìn thấy;
xin hãy chắc chắn rằng bạn đã nhận thức được những hệ lụy của nó trước khi thực hiện.",
'movepagetalktext'             => "Trang thảo luận đi kèm sẽ được tự động di chuyển theo '''trừ khi''':
*Đã tồn tại một trang thảo luận không trống tại tên mới, hoặc
*Bạn không đánh vào ô bên dưới.

Trong những trường hợp đó, bạn phải di chuyển hoặc hợp nhất trang theo kiểu thủ công nếu muốn.",
'movearticle'                  => 'Di chuyển trang:',
'moveuserpage-warning'         => "'''Cảnh báo:''' Bạn sắp di chuyển trang cá nhân của người dùng. Xin lưu ý rằng chỉ có trang này sẽ được di chuyển, còn người dùng sẽ ''không'' đổi tên.",
'movenologin'                  => 'Chưa đăng nhập',
'movenologintext'              => 'Bạn phải là thành viên đã đăng ký và [[Special:UserLogin|đăng nhập]] mới di chuyển trang được.',
'movenotallowed'               => 'Bạn không có quyền di chuyển trang.',
'movenotallowedfile'           => 'Bạn không có đủ quyền để di chuyển tập tin.',
'cant-move-user-page'          => 'Bạn không có quyền di chuyển trang cá nhân (ngoại trừ trang con).',
'cant-move-to-user-page'       => 'Bạn không có quyền di chuyển một trang đến trang cá nhân (ngoại trừ đến trang con của trang cá nhân).',
'newtitle'                     => 'Tên mới',
'move-watch'                   => 'Theo dõi trang này',
'movepagebtn'                  => 'Di chuyển trang',
'pagemovedsub'                 => 'Di chuyển thành công',
'movepage-moved'               => "'''“$1” đã được di chuyển đến “$2”'''",
'movepage-moved-redirect'      => 'Đã tạo trang chuyển hướng.',
'movepage-moved-noredirect'    => 'Chức năng tạo trang chuyển hướng đã bị tắt.',
'articleexists'                => 'Đã có một trang với tên đó, hoặc tên bạn chọn không hợp lệ.
Xin hãy chọn tên khác.',
'cantmove-titleprotected'      => 'Bạn không thể đổi tên trang, vì tên trang mới đã bị khóa không cho tạo mới',
'talkexists'                   => "'''Trang được di chuyển thành công, nhưng trang thảo luận không thể di chuyển được vì đã tồn tại một trang thảo luận ở tên mới. Xin hãy hợp nhất chúng lại một cách thủ công.'''",
'movedto'                      => 'đổi thành',
'movetalk'                     => 'Di chuyển trang thảo luận đi kèm',
'move-subpages'                => 'Di chuyển các trang con (tối đa là $1 trang)',
'move-talk-subpages'           => 'Di chuyển các trang con của trang thảo luận (tối đa $1 trang)',
'movepage-page-exists'         => 'Trang $1 đã tồn tại và không thể bị tự động ghi đè.',
'movepage-page-moved'          => 'Trang $1 đã được di chuyển đến $2.',
'movepage-page-unmoved'        => 'Trang $1 không thể di chuyển đến $2.',
'movepage-max-pages'           => 'Đã có tối đa $1 {{PLURAL:$1|trang|trang}} đã di chuyển và không tự động di chuyển thêm được nữa.',
'1movedto2'                    => '[[$1]] đổi thành [[$2]]',
'1movedto2_redir'              => '[[$1]] đổi thành [[$2]] qua đổi hướng',
'move-redirect-suppressed'     => 'đã tắt chuyển hướng',
'movelogpage'                  => 'Nhật trình di chuyển',
'movelogpagetext'              => 'Dưới đây là danh sách các trang đã được di chuyển.',
'movesubpage'                  => '{{PLURAL:$1|Trang con|Các trang con}}',
'movesubpagetext'              => 'Trang này có $1 {{PLURAL:$1|trang con|trang con}} như hiển thị dưới đây.',
'movenosubpage'                => 'Trang này không có trang con.',
'movereason'                   => 'Lý do:',
'revertmove'                   => 'lùi lại',
'delete_and_move'              => 'Xóa và đổi tên',
'delete_and_move_text'         => '==Cần xóa==

Trang với tên “[[:$1]]” đã tồn tại. Bạn có muốn xóa nó để dọn chỗ di chuyển tới tên này không?',
'delete_and_move_confirm'      => 'Xóa trang để đổi tên',
'delete_and_move_reason'       => 'Xóa để có chỗ đổi tên',
'selfmove'                     => 'Tên mới giống tên cũ; không đổi tên một trang thành chính nó.',
'immobile-source-namespace'    => 'Không thể di chuyển các trang trong không gian tên    	 	 	 	“$1”',
'immobile-target-namespace'    => 'Không thể di chuyển trang vào không gian tên    	 	 	 	“$1”',
'immobile-target-namespace-iw' => 'Không cho phép di chuyển trang đến một liên kết liên wiki.',
'immobile-source-page'         => 'Bạn không thể di chuyển trang này.',
'immobile-target-page'         => 'Không thể di chuyển đến tựa đề đích.',
'imagenocrossnamespace'        => 'Không thể di chuyển tập tin ra khỏi không gian tên Tập tin',
'nonfile-cannot-move-to-file'  => 'Không thể di chuyển những gì không phải là tập tin vào không gian tên Tập tin',
'imagetypemismatch'            => 'Phần mở rộng trong tên tập tin mới không hợp dạng của tập tin',
'imageinvalidfilename'         => 'Tên tập tin đích không hợp lệ',
'fix-double-redirects'         => 'Cập nhật tất cả các trang đổi hướng chỉ đến tựa đề cũ',
'move-leave-redirect'          => 'Để lại trang đổi hướng',
'protectedpagemovewarning'     => "'''Cảnh báo:''' Trang này đã bị khóa và chỉ có các thành viên có quyền quản lý mới có thể di chuyển được.
Thông tin mới nhất trong nhật trình được ghi dưới đây để tiện theo dõi:",
'semiprotectedpagemovewarning' => "'''Lưu ý:''' Trang này đã bị khóa và chỉ có các thành viên đã đăng ký mới có thể di chuyển được.
Thông tin mới nhất trong nhật trình được ghi dưới đây để tiện theo dõi:",
'move-over-sharedrepo'         => '== Tập tin đã tồn tại ==
[[:$1]] đã tồn tại trong kho dùng chung. Nếu đổi tên tập tin thành tên này thì sẽ ghi đè lên tập tin dùng chung.',
'file-exists-sharedrepo'       => 'Bạn đã chọn tên tập tin trùng với tập tin nằm trong kho dùng chung.
Xin hãy chọn tên khác.',

# Export
'export'            => 'Xuất các trang',
'exporttext'        => 'Bạn có thể xuất nội dung và lịch sử sửa đổi của một trang hoặc tập hợp trang vào tập tin XML.
Những tập tin này cũng có thể được nhập vào wiki khác có sử dụng MediaWiki thông qua [[Special:Import|nhập trang]].

Để xuất các trang, nhập vào tên trang trong hộp soạn thảo ở dưới, mỗi dòng một tên, và lựa chọn bạn muốn phiên bản hiện tại cũng như tất cả phiên bản cũ, với các dòng lịch sử trang, hay chỉ là phiên bản hiện tại với thông tin về lần sửa đổi cuối.

Trong trường hợp sau bạn cũng có thể dùng một liên kết, ví dụ [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] để biểu thị trang “[[{{MediaWiki:Mainpage}}]]”.',
'exportcuronly'     => 'Chỉ xuất phiên bản hiện hành, không xuất tất cả lịch sử trang',
'exportnohistory'   => "----
'''Chú ý:''' Chức năng xuất lịch sử trang đầy đủ bằng mẫu này bị tắt do vấn đề hiệu suất.",
'export-submit'     => 'Xuất',
'export-addcattext' => 'Thêm trang từ thể loại:',
'export-addcat'     => 'Thêm',
'export-addnstext'  => 'Thêm trang từ không gian tên:',
'export-addns'      => 'Thêm vào',
'export-download'   => 'Lưu xuống tập tin',
'export-templates'  => 'Gồm cả bản mẫu',
'export-pagelinks'  => 'Gồm cả các trang liên kết sâu đến:',

# Namespace 8 related
'allmessages'                   => 'Thông báo hệ thống',
'allmessagesname'               => 'Tên thông báo',
'allmessagesdefault'            => 'Nội dung mặc định',
'allmessagescurrent'            => 'Nội dung hiện thời',
'allmessagestext'               => 'Đây là toàn bộ thông báo hệ thống có trong không gian tên MediaWiki.
Mời vào [http://www.mediawiki.org/wiki/Localisation Địa phương hóa MediaWiki]  và [http://translatewiki.net translatewiki.net] nếu bạn muốn đóng góp dịch chung cả MediaWiki.',
'allmessagesnotsupportedDB'     => "Trang này không dùng được vì biến '''\$wgUseDatabaseMessages''' đã bị tắt.",
'allmessages-filter-legend'     => 'Bộ lọc',
'allmessages-filter'            => 'Lọc theo tình trạng sửa đổi:',
'allmessages-filter-unmodified' => 'Chưa sửa',
'allmessages-filter-all'        => 'Tất cả',
'allmessages-filter-modified'   => 'Đã sửa',
'allmessages-prefix'            => 'Lọc theo tiền tố:',
'allmessages-language'          => 'Ngôn ngữ:',
'allmessages-filter-submit'     => 'Xem',

# Thumbnails
'thumbnail-more'           => 'Phóng lớn',
'filemissing'              => 'Không có tập tin',
'thumbnail_error'          => 'Hình thu nhỏ có lỗi: $1',
'djvu_page_error'          => 'Trang DjVu quá xa',
'djvu_no_xml'              => 'Không thể truy xuất XML cho tập tin DjVu',
'thumbnail_invalid_params' => 'Tham số hình thu nhỏ không hợp lệ',
'thumbnail_dest_directory' => 'Không thể tạo thư mục đích',
'thumbnail_image-type'     => 'Không hỗ trợ kiểu hình này',
'thumbnail_gd-library'     => 'Cấu hình thư viện GD chưa hoàn thành: thiếu hàm $1',
'thumbnail_image-missing'  => 'Hình như tập tin mất tích: $1',

# Special:Import
'import'                     => 'Nhập các trang',
'importinterwiki'            => 'Nhập giữa các wiki',
'import-interwiki-text'      => 'Chọn tên trang và wiki để nhập trang vào.
Ngày của phiên bản và tên người viết trang sẽ được giữ nguyên.
Tất cả những lần nhập trang từ wiki khác được ghi lại ở [[Special:Log/import|nhật trình nhập trang]].',
'import-interwiki-source'    => 'Wiki/trang mã nguồn:',
'import-interwiki-history'   => 'Sao chép tất cả các phiên bản cũ của trang này',
'import-interwiki-templates' => 'Gồm tất cả các bản mẫu',
'import-interwiki-submit'    => 'Nhập trang',
'import-interwiki-namespace' => 'Không gian tên đích:',
'import-upload-filename'     => 'Tên tập tin:',
'import-comment'             => 'Lý do:',
'importtext'                 => 'Xin hãy xuất tập tin từ wiki nguồn sử dụng [[Special:Export|tính năng xuất]].
Lưu nó vào máy tính của bạn rồi tải nó lên đây.',
'importstart'                => 'Đang nhập các trang…',
'import-revision-count'      => '$1 {{PLURAL:$1|phiên bản|phiên bản}}',
'importnopages'              => 'Không có trang để nhập vào.',
'imported-log-entries'       => 'Đã nhập {{PLURAL:$1|mục nhật trình|$1 mục nhật trình}}.',
'importfailed'               => 'Không nhập được: $1',
'importunknownsource'        => 'Không hiểu nguồn trang để nhập vào',
'importcantopen'             => 'Không có thể mở tập tin để nhập vào',
'importbadinterwiki'         => 'Liên kết liên wiki sai',
'importnotext'               => 'Trang trống hoặc không có nội dung',
'importsuccess'              => 'Nhập thành công!',
'importhistoryconflict'      => 'Có mâu thuẫn trong lịch sử của các phiên bản (trang này có thể đã được nhập vào trước đó)',
'importnosources'            => 'Không có nguồn nhập giữa wiki và việc nhập lịch sử bị tắt.',
'importnofile'               => 'Không tải được tập tin nào lên.',
'importuploaderrorsize'      => 'Không thể tải tập tin nhập trang. Tập tin lớn hơn kích thước cho phép tải lên.',
'importuploaderrorpartial'   => 'Không thể tải tập tin nhập trang. Tập tin mới chỉ tải lên được một phần.',
'importuploaderrortemp'      => 'Không thể tải tập tin nhập trang. Thiếu thư mục tạm.',
'import-parse-failure'       => 'Không thể phân tích tập tin nhập XML',
'import-noarticle'           => 'Không có trang nào để nhập cả!',
'import-nonewrevisions'      => 'Tất cả các phiên bản đều đã được nhập trước đây.',
'xml-error-string'           => '$1 tại dòng $2, cột $3 (byte $4): $5',
'import-upload'              => 'Tải lên dữ liệu XML',
'import-token-mismatch'      => 'Mất dữ liệu phiên làm việc. Xin hãy thử lại lần nữa.',
'import-invalid-interwiki'   => 'Không thể nhập trang từ wiki được chỉ định.',

# Import log
'importlogpage'                    => 'Nhật trình nhập trang',
'importlogpagetext'                => 'Đây là danh sách các trang được quản lý nhập vào đây. Các trang này có lịch sử sửa đổi từ hồi ở wiki khác.',
'import-logentry-upload'           => 'nhập vào [[$1]] bằng cách tải tập tin',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|phiên bản|phiên bản}}',
'import-logentry-interwiki'        => 'đã nhập vào $1 từ wiki khác',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|phiên bản|phiên bản}} từ $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Trang thành viên của tôi',
'tooltip-pt-anonuserpage'         => 'Trang của IP bạn đang dùng',
'tooltip-pt-mytalk'               => 'Thảo luận với tôi',
'tooltip-pt-anontalk'             => 'Thảo luận với địa chỉ IP này',
'tooltip-pt-preferences'          => 'Tùy chọn cá nhân của tôi',
'tooltip-pt-watchlist'            => 'Thay đổi của các trang tôi theo dõi',
'tooltip-pt-mycontris'            => 'Danh sách các đóng góp của tôi',
'tooltip-pt-login'                => 'Đăng nhập sẽ có lợi hơn, tuy nhiên không bắt buộc.',
'tooltip-pt-anonlogin'            => 'Không đăng nhập vẫn tham gia được, tuy nhiên đăng nhập sẽ lợi hơn.',
'tooltip-pt-logout'               => 'Đăng xuất',
'tooltip-ca-talk'                 => 'Thảo luận về trang này',
'tooltip-ca-edit'                 => 'Bạn có thể sửa được trang này. Xin xem thử trước khi lưu.',
'tooltip-ca-addsection'           => 'Bắt đầu một đề mục mới',
'tooltip-ca-viewsource'           => 'Trang này được khóa. Bạn có thể xem mã nguồn.',
'tooltip-ca-history'              => 'Những phiên bản cũ của trang này.',
'tooltip-ca-protect'              => 'Khóa trang này lại',
'tooltip-ca-unprotect'            => 'Mở khóa trang này',
'tooltip-ca-delete'               => 'Xóa trang này',
'tooltip-ca-undelete'             => 'Phục hồi những sửa đổi trên trang này như trước khi nó bị xóa',
'tooltip-ca-move'                 => 'Di chuyển trang này',
'tooltip-ca-watch'                => 'Thêm trang này vào danh sách theo dõi',
'tooltip-ca-unwatch'              => 'Bỏ trang này khỏi danh sách theo dõi',
'tooltip-search'                  => 'Tìm kiếm {{SITENAME}}',
'tooltip-search-go'               => 'Xem trang khớp với tên này nếu có',
'tooltip-search-fulltext'         => 'Tìm trang có nội dung này',
'tooltip-p-logo'                  => 'Trang Chính',
'tooltip-n-mainpage'              => 'Đi đến Trang Chính',
'tooltip-n-mainpage-description'  => 'Xem trang chính',
'tooltip-n-portal'                => 'Giới thiệu dự án, cách sử dụng và tìm kiếm thông tin ở đây',
'tooltip-n-currentevents'         => 'Các trang có liên quan đến thời sự',
'tooltip-n-recentchanges'         => 'Danh sách các thay đổi gần đây',
'tooltip-n-randompage'            => 'Xem trang ngẫu nhiên',
'tooltip-n-help'                  => 'Nơi tìm hiểu thêm cách dùng.',
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
'tooltip-ca-nstab-main'           => 'Xem trang nội dung này',
'tooltip-ca-nstab-user'           => 'Xem trang về người này',
'tooltip-ca-nstab-media'          => 'Xem trang phương tiện',
'tooltip-ca-nstab-special'        => 'Đây là một trang đặc biệt, bạn không thể sửa đổi nó.',
'tooltip-ca-nstab-project'        => 'Xem trang dự án',
'tooltip-ca-nstab-image'          => 'Xem trang hình',
'tooltip-ca-nstab-mediawiki'      => 'Xem thông báo hệ thống',
'tooltip-ca-nstab-template'       => 'Xem bản mẫu',
'tooltip-ca-nstab-help'           => 'Xem trang trợ giúp',
'tooltip-ca-nstab-category'       => 'Xem trang thể loại',
'tooltip-minoredit'               => 'Đánh dấu đây là sửa đổi nhỏ',
'tooltip-save'                    => 'Lưu lại những thay đổi của bạn',
'tooltip-preview'                 => 'Xem thử những thay đổi, hãy dùng nó trước khi lưu!',
'tooltip-diff'                    => 'Xem thay đổi bạn đã thực hiện.',
'tooltip-compareselectedversions' => 'Xem khác biệt giữa hai phiên bản đã chọn của trang này.',
'tooltip-watch'                   => 'Thêm trang này vào danh sách theo dõi',
'tooltip-recreate'                => 'Tạo lại trang dù cho nó vừa bị xóa',
'tooltip-upload'                  => 'Bắt đầu tải lên',
'tooltip-rollback'                => '"Lùi tất cả" sẽ lùi mọi sửa đổi của người sửa đổi cuối cùng chỉ bằng một cú nhấp chuột.',
'tooltip-undo'                    => '"Lùi lại" sẽ lùi sửa đổi này và mở trang sửa đổi ở chế độ xem thử. Cho phép thêm lý do vào tóm lược.',
'tooltip-preferences-save'        => 'Lưu tùy chọn',
'tooltip-summary'                 => 'Hãy nhập câu tóm lược',

# Stylesheets
'common.css'      => '/* Mã CSS đặt ở đây sẽ áp dụng cho mọi hình dạng */',
'standard.css'    => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Cổ điển */',
'nostalgia.css'   => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Vọng cổ */',
'cologneblue.css' => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Xanh Cologne */',
'monobook.css'    => '/* Mã CSS đặt ở đây sẽ ảnh hưởng đến thành viên sử dụng hình dạng MonoBook */',
'myskin.css'      => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Cá nhân */',
'chick.css'       => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Chick */',
'simple.css'      => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Đơn giản */',
'modern.css'      => '/* Mã CSS tại đây sẽ ảnh hưởng đến những người dùng sử dụng hình dạng Hiện đại */',
'vector.css'      => '/* Mã CSS đặt ở đây sẽ ảnh hưởng đến thành viên sử dụng hình dạng Vectơ */',
'print.css'       => '/* Mã CSS tại đây sẽ ảnh hưởng đến bản để in */',
'handheld.css'    => '/* Mã CSS tại đây sẽ ảnh hưởng đến các thiết bị cầm tay dựa trên hình dạng cấu hình trong $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Bất kỳ mã JavaScript ở đây sẽ được tải cho tất cả các thành viên khi tải một trang nào đó lên. */',
'standard.js'    => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng hình dạng Cổ điển */',
'nostalgia.js'   => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng hình dạng Vọng cổ */',
'cologneblue.js' => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng hình dạng Xanh Cologne */',
'monobook.js'    => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng bề ngoài MonoBook */',
'myskin.js'      => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng bề ngoài Cá nhân */',
'chick.js'       => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng bề ngoài Chick */',
'simple.js'      => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng bề ngoài Đơn giản */',
'modern.js'      => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng bề ngoài Hiện đại */',
'vector.js'      => '/* Mã JavaScript tại đây sẽ được tải khi người dùng sử dụng bề ngoài Vectơ */',

# Metadata
'nodublincore'      => 'Máy chủ không hỗ trợ siêu dữ liệu Dublin Core RDF.',
'nocreativecommons' => 'Máy chủ không hỗ trợ siêu dữ liệu Creative Commons RDF.',
'notacceptable'     => 'Máy chủ không thể cho ra định dạng dữ liệu tương thích với phần mềm của bạn.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Thành viên|Thành viên}} vô danh của {{SITENAME}}',
'siteuser'         => 'thành viên $1 của {{SITENAME}}',
'anonuser'         => 'người vô danh $1 tại {{SITENAME}}',
'lastmodifiedatby' => 'Trang này được $3 cập nhật lần cuối lúc $2, $1.',
'othercontribs'    => 'Dựa trên công trình của $1.',
'others'           => 'những người khác',
'siteusers'        => '{{PLURAL:$2|Thành viên|Các thành viên}} $1 của {{SITENAME}}',
'anonusers'        => '{{plural:$2|người|những người}} vô danh $1 tại {{SITENAME}}',
'creditspage'      => 'Trang ghi nhận đóng góp',
'nocredits'        => 'Không có thông tin ghi nhận đóng góp cho trang này.',

# Spam protection
'spamprotectiontitle' => 'Bộ lọc chống thư rác',
'spamprotectiontext'  => 'Trang bạn muốn lưu bị bộ lọc thư rác chặn lại.
Đây có thể do một liên kết dẫn tới một địa chỉ bên ngoài đã bị ghi vào danh sách đen.',
'spamprotectionmatch' => 'Nội dung sau đây đã kích hoạt bộ lọc thư rác: $1',
'spambot_username'    => 'Bộ dọn dẹp thư rác MediaWiki',
'spam_reverting'      => 'Lùi lại đến phiên bản cuối không chứa liên kết đến $1',
'spam_blanking'       => 'Tất cả các phiên bản có liên kết đến $1, đang tẩy trống',

# Info page
'infosubtitle'   => 'Thông tin về trang',
'numedits'       => 'Số lần sửa đổi (trang nội dung): $1',
'numtalkedits'   => 'Số lần sửa đổi (trang thảo luận): $1',
'numwatchers'    => 'Số người theo dõi: $1',
'numauthors'     => 'Số người sửa đổi khác nhau (trang nội dung): $1',
'numtalkauthors' => 'Số người sửa đổi khác nhau (trang thảo luận): $1',

# Skin names
'skinname-standard'    => 'Cổ điển',
'skinname-nostalgia'   => 'Vọng cổ',
'skinname-cologneblue' => 'Xanh Cologne',
'skinname-myskin'      => 'Cá nhân',
'skinname-simple'      => 'Đơn giản',
'skinname-modern'      => 'Hiện đại',
'skinname-vector'      => 'Vectơ',

# Math options
'mw_math_png'    => 'Luôn cho ra dạng hình PNG',
'mw_math_simple' => 'HTML nếu rất đơn giản, nếu không thì PNG',
'mw_math_html'   => 'HTML nếu có thể, nếu không thì PNG',
'mw_math_source' => 'Để nguyên mã TeX (dành cho trình duyệt văn bản)',
'mw_math_modern' => 'Khuyên dùng với các trình duyệt hiện đại',
'mw_math_mathml' => 'MathML nếu có thể (thử nghiệm)',

# Math errors
'math_failure'          => 'Không thể phân tích cú pháp',
'math_unknown_error'    => 'lỗi lạ',
'math_unknown_function' => 'hàm lạ',
'math_lexing_error'     => 'lỗi chính tả',
'math_syntax_error'     => 'lỗi cú pháp',
'math_image_error'      => 'Không chuyển sang định dạng PNG được; xin kiểm tra lại cài đặt latex, dvips, gs và convert',
'math_bad_tmpdir'       => 'Không tạo mới hay viết vào thư mục toán tạm thời được',
'math_bad_output'       => 'Không tạo mới hay viết vào thư mục kết quả được',
'math_notexvc'          => 'Không thấy hàm thực thi texvc; xin xem math/README để biết cách cấu hình.',

# Patrolling
'markaspatrolleddiff'                 => 'Đánh dấu tuần tra',
'markaspatrolledtext'                 => 'Đánh dấu tuần tra trang này',
'markedaspatrolled'                   => 'Đã đánh dấu tuần tra',
'markedaspatrolledtext'               => 'Phiên bản được chọn của [[:$1]] đã được đánh dấu tuần tra.',
'rcpatroldisabled'                    => '“Thay đổi gần đây” của các trang tuần tra không bật',
'rcpatroldisabledtext'                => 'Chức năng “thay đổi gần đây” của các trang tuần tra hiện không được bật.',
'markedaspatrollederror'              => 'Không thể đánh dấu tuần tra',
'markedaspatrollederrortext'          => 'Bạn phải chọn phiên bản để đánh dấu tuần tra.',
'markedaspatrollederror-noautopatrol' => 'Bạn không được đánh dấu tuần tra vào sửa đổi của bạn.',

# Patrol log
'patrol-log-page'      => 'Nhật ký tuần tra',
'patrol-log-header'    => 'Đây là nhật trình tuần tra phiên bản.',
'patrol-log-line'      => 'đánh dấu tuần tra vào $1 của $2 $3',
'patrol-log-auto'      => '(tự động)',
'patrol-log-diff'      => 'bản $1',
'log-show-hide-patrol' => '$1 nhật trình tuần tra',

# Image deletion
'deletedrevision'                 => 'Đã xóa phiên bản cũ $1',
'filedeleteerror-short'           => 'Lỗi xóa tập tin: $1',
'filedeleteerror-long'            => 'Có lỗi khi xóa tập tin:

$1',
'filedelete-missing'              => 'Không thể xóa tập tin “$1” vì không tồn tại.',
'filedelete-old-unregistered'     => 'Phiên bản chỉ định “$1” không có trong cơ sở dữ liệu.',
'filedelete-current-unregistered' => 'Tập tin “$1” không thấy trong cơ sở dữ liệu.',
'filedelete-archive-read-only'    => 'Máy chủ web không ghi được vào thư mục lưu trữ “$1”.',

# Browsing diffs
'previousdiff' => '← Sửa đổi cũ',
'nextdiff'     => 'Sửa đổi sau →',

# Media information
'mediawarning'         => "'''Cảnh báo''': Kiểu tập tin này có thể chứa mã hiểm độc.
Nếu thực thi nó máy tính của bạn có thể bị tiếm quyền.",
'imagemaxsize'         => "Giới hạn cỡ hình:<br />''(trên trang miêu tả tập tin)''",
'thumbsize'            => 'Cỡ hình thu nhỏ:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|trang|trang}}',
'file-info'            => '(kích thước tập tin: $1, định dạng MIME: $2)',
'file-info-size'       => '($1×$2 điểm ảnh, kích thước: $3, định dạng MIME: $4)',
'file-nohires'         => '<small>Không có độ phân giải cao hơn.</small>',
'svg-long-desc'        => '(tập tin SVG, $1 × $2 điểm ảnh trên danh nghĩa, kích thước: $3)',
'show-big-image'       => 'Độ phân giải tối đa',
'show-big-image-thumb' => '<small>Kích thước xem thử: $1 × $2 điểm ảnh</small>',
'file-info-gif-looped' => 'có lặp',
'file-info-gif-frames' => '$1 {{PLURAL:$1|khung ảnh|khung ảnh}}',
'file-info-png-looped' => 'có lặp',
'file-info-png-repeat' => 'chơi $1 lần',
'file-info-png-frames' => '$1 khung ảnh',

# Special:NewFiles
'newimages'             => 'Trang trưng bày hình ảnh mới',
'imagelisttext'         => "Dưới đây là danh sách '''$1''' {{PLURAL:$1|tập tin|tập tin}} xếp theo $2.",
'newimages-summary'     => 'Trang đặc biệt này hiển thị các tập tin được tải lên gần đây nhất.',
'newimages-legend'      => 'Bộ lọc',
'newimages-label'       => 'Tên tập tin (hoặc một phần tên):',
'showhidebots'          => '($1 robot)',
'noimages'              => 'Chưa có hình.',
'ilsubmit'              => 'Tìm kiếm',
'bydate'                => 'theo ngày',
'sp-newimages-showfrom' => 'Trưng bày những tập tin mới, bắt đầu từ lúc $2, ngày $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds-abbrev' => 's',
'minutes-abbrev' => 'm',
'hours-abbrev'   => 'h',

# Bad image list
'bad_image_list' => 'Định dạng như sau:

Chỉ có những mục được liệt kê (những dòng bắt đầu bằng *) mới được tính tới. Liên kết đầu tiên tại một dòng phải là liên kết đến tập tin phản cảm.
Các liên kết sau đó trên cùng một dòng được xem là các ngoại lệ, có nghĩa là các trang mà tại đó có thể dùng được tập tin.',

# Metadata
'metadata'          => 'Đặc tính hình',
'metadata-help'     => 'Tập tin này có chứa thông tin về nó, do máy ảnh hay máy quét thêm vào. Nếu tập tin bị sửa đổi sau khi được tạo ra lần đầu, có thể thông tin này không được cập nhật.',
'metadata-expand'   => 'Hiện chi tiết cấp cao',
'metadata-collapse' => 'Ẩn chi tiết cấp cao',
'metadata-fields'   => 'Những thông tin đặc tính EXIF được danh sách dưới đây sẽ được đưa vào vào trang miêu tả hình khi bảng đặc tính được thu nhỏ.
Những thông tin khác mặc định sẽ được ẩn đi.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Chiều ngang',
'exif-imagelength'                 => 'Chiều cao',
'exif-bitspersample'               => 'Bit trên mẫu',
'exif-compression'                 => 'Kiểu nén',
'exif-photometricinterpretation'   => 'Thành phần điểm ảnh',
'exif-orientation'                 => 'Hướng',
'exif-samplesperpixel'             => 'Số mẫu trên điểm ảnh',
'exif-planarconfiguration'         => 'Cách xếp dữ liệu',
'exif-ycbcrsubsampling'            => 'Tỉ lệ lấy mẫu con của Y so với C',
'exif-ycbcrpositioning'            => 'Định vị Y và C',
'exif-xresolution'                 => 'Phân giải theo chiều ngang',
'exif-yresolution'                 => 'Phân giải theo chiều cao',
'exif-resolutionunit'              => 'Đơn vị phân giải X và Y',
'exif-stripoffsets'                => 'Vị trí dữ liệu hình',
'exif-rowsperstrip'                => 'Số hàng trên mỗi mảnh',
'exif-stripbytecounts'             => 'Số byte trên mỗi mảnh nén',
'exif-jpeginterchangeformat'       => 'Vị trí SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Kích cỡ (byte) của JPEG',
'exif-transferfunction'            => 'Hàm chuyển đổi',
'exif-whitepoint'                  => 'Sắc độ điểm trắng',
'exif-primarychromaticities'       => 'Sắc độ của màu cơ bản',
'exif-ycbcrcoefficients'           => 'Hệ số ma trận biến đổi không gian màu',
'exif-referenceblackwhite'         => 'Giá trị tham chiếu cặp trắng đen',
'exif-datetime'                    => 'Ngày giờ sửa tập tin',
'exif-imagedescription'            => 'Tiêu đề của hình',
'exif-make'                        => 'Hãng máy ảnh',
'exif-model'                       => 'Kiểu máy ảnh',
'exif-software'                    => 'Phần mềm đã dùng',
'exif-artist'                      => 'Tác giả',
'exif-copyright'                   => 'Bản quyền',
'exif-exifversion'                 => 'Phiên bản Exif',
'exif-flashpixversion'             => 'Phiên bản Flashpix được hỗ trợ',
'exif-colorspace'                  => 'Không gian màu',
'exif-componentsconfiguration'     => 'Ý nghĩa thành phần',
'exif-compressedbitsperpixel'      => 'Độ nén (bit/điểm)',
'exif-pixelydimension'             => 'Chiều ngang hợp lệ',
'exif-pixelxdimension'             => 'Chiều cao hợp lệ',
'exif-makernote'                   => 'Ghi chú của nhà sản xuất',
'exif-usercomment'                 => 'Lời bình của tác giả',
'exif-relatedsoundfile'            => 'Tập tin âm thanh liên quan',
'exif-datetimeoriginal'            => 'Ngày giờ sinh dữ liệu',
'exif-datetimedigitized'           => 'Ngày giờ số hóa',
'exif-subsectime'                  => 'Ngày giờ nhỏ hơn giây',
'exif-subsectimeoriginal'          => 'Ngày giờ gốc nhỏ hơn giây',
'exif-subsectimedigitized'         => 'Ngày giờ số hóa nhỏ hơn giây',
'exif-exposuretime'                => 'Thời gian mở ống kính',
'exif-exposuretime-format'         => '$1 giây ($2)',
'exif-fnumber'                     => 'Số F',
'exif-fnumber-format'              => 'f/$1',
'exif-exposureprogram'             => 'Chương trình phơi sáng',
'exif-spectralsensitivity'         => 'Độ nhạy quang phổ',
'exif-isospeedratings'             => 'Điểm tốc độ ISO',
'exif-oecf'                        => 'Yếu tố chuyển đổi quang điện',
'exif-shutterspeedvalue'           => 'Tốc độ cửa chớp',
'exif-aperturevalue'               => 'Độ mở ống kính',
'exif-brightnessvalue'             => 'Độ sáng',
'exif-exposurebiasvalue'           => 'Độ lệch phơi sáng',
'exif-maxaperturevalue'            => 'Khẩu độ cực đại qua đất',
'exif-subjectdistance'             => 'Khoảng cách vật thể',
'exif-meteringmode'                => 'Chế độ đo',
'exif-lightsource'                 => 'Nguồn sáng',
'exif-flash'                       => 'Đèn chớp',
'exif-focallength'                 => 'Độ dài tiêu cự thấu kính',
'exif-focallength-format'          => '$1 mm',
'exif-subjectarea'                 => 'Diện tích vật thể',
'exif-flashenergy'                 => 'Nguồn đèn chớp',
'exif-spatialfrequencyresponse'    => 'Phản ứng tần số không gian',
'exif-focalplanexresolution'       => 'Phân giải X trên mặt phẳng tiêu',
'exif-focalplaneyresolution'       => 'Phân giải Y trên mặt phẳng tiêu',
'exif-focalplaneresolutionunit'    => 'Đơn vị phân giải trên mặt phẳng tiêu',
'exif-subjectlocation'             => 'Vị trí vật thể',
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
'exif-gpsstatus'                   => 'Tình trạng đầu thu',
'exif-gpsmeasuremode'              => 'Chế độ đo',
'exif-gpsdop'                      => 'Độ chính xác máy đo',
'exif-gpsspeedref'                 => 'Đơn vị tốc độ',
'exif-gpsspeed'                    => 'Tốc độ đầu thu GPS',
'exif-gpstrackref'                 => 'Tham chiếu cho hướng chuyển động',
'exif-gpstrack'                    => 'Hướng chuyển động',
'exif-gpsimgdirectionref'          => 'Tham chiếu cho hướng của ảnh',
'exif-gpsimgdirection'             => 'Hướng của hình',
'exif-gpsmapdatum'                 => 'Dữ liệu trắc địa đã dùng',
'exif-gpsdestlatituderef'          => 'Tham chiếu cho vĩ độ đích',
'exif-gpsdestlatitude'             => 'Vĩ độ đích',
'exif-gpsdestlongituderef'         => 'Tham chiếu cho kinh độ đích',
'exif-gpsdestlongitude'            => 'Kinh độ đích',
'exif-gpsdestbearingref'           => 'Tham chiếu cho phương hướng đích',
'exif-gpsdestbearing'              => 'Phương hướng đích',
'exif-gpsdestdistanceref'          => 'Tham chiếu cho khoảng cách đến đích',
'exif-gpsdestdistance'             => 'Khoảng cách đến đích',
'exif-gpsprocessingmethod'         => 'Tên phương pháp xử lý GPS',
'exif-gpsareainformation'          => 'Tên khu vực theo GPS',
'exif-gpsdatestamp'                => 'Ngày theo GPS',
'exif-gpsdifferential'             => 'Sửa vi sai GPS',

# EXIF attributes
'exif-compression-1' => 'Không nén',
'exif-compression-6' => 'JPEG',

'exif-photometricinterpretation-2' => 'RGB',

'exif-unknowndate' => 'Không biết ngày',

'exif-orientation-1' => 'Thường',
'exif-orientation-2' => 'Lộn ngược theo phương ngang',
'exif-orientation-3' => 'Quay 180°',
'exif-orientation-4' => 'Lộn ngược theo phương dọc',
'exif-orientation-5' => 'Quay 90° bên trái và lộn thẳng đứng',
'exif-orientation-6' => 'Quay 90° bên phải',
'exif-orientation-7' => 'Quay 90° bên phải và lộn thẳng đứng',
'exif-orientation-8' => 'Quay 90° bên trái',

'exif-planarconfiguration-1' => 'định dạng thấp',
'exif-planarconfiguration-2' => 'định dạng phẳng',

'exif-componentsconfiguration-0' => 'không tồn tại',

'exif-exposureprogram-0' => 'Không chỉ định',
'exif-exposureprogram-1' => 'Thủ công',
'exif-exposureprogram-2' => 'Chương trình chuẩn',
'exif-exposureprogram-3' => 'Ưu tiên độ mở ống kính',
'exif-exposureprogram-4' => 'Ưu tiên tốc độ sập',
'exif-exposureprogram-5' => 'Chương trình sáng tạo (thiên về chiều sâu)',
'exif-exposureprogram-6' => 'Chương trình chụp (thien về tốc độ sập nhanh)',
'exif-exposureprogram-7' => 'Chế độ chân dung (đối với ảnh chụp gần với phông nền ở ngoài tầm tiêu cự)',
'exif-exposureprogram-8' => 'Chế độ phong cảnh (đối với ảnh phong cảnh với phông ở trong tiêu cự)',

'exif-subjectdistance-value' => '$1 mét',

'exif-meteringmode-0'   => 'Không biết',
'exif-meteringmode-1'   => 'Trung bình',
'exif-meteringmode-2'   => 'Trung bình trọng lượng ở giữa',
'exif-meteringmode-3'   => 'Vết',
'exif-meteringmode-4'   => 'Đa vết',
'exif-meteringmode-5'   => 'Lấy mẫu',
'exif-meteringmode-6'   => 'Cục bộ',
'exif-meteringmode-255' => 'Khác',

'exif-lightsource-0'   => 'Không biết',
'exif-lightsource-1'   => 'Trời nắng',
'exif-lightsource-2'   => 'Huỳnh quang',
'exif-lightsource-3'   => 'Vonfram (ánh nóng sáng)',
'exif-lightsource-4'   => 'Đèn chớp',
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

# Flash modes
'exif-flash-fired-0'    => 'Đèn flash không chớp',
'exif-flash-fired-1'    => 'Có chớp đèn flash',
'exif-flash-return-0'   => 'không có chức năng kiểm tra tín hiệu trả về nhấp nháy',
'exif-flash-return-2'   => 'không phát hiện ra ánh sáng trả về nhấp nháy',
'exif-flash-return-3'   => 'phát hiện ra ánh sáng trả về nhấp nháy',
'exif-flash-mode-1'     => 'chớp flash cưỡng ép',
'exif-flash-mode-2'     => 'tắt flash cưỡng ép',
'exif-flash-mode-3'     => 'chế độ tự động',
'exif-flash-function-1' => 'Không có chức năng flash',
'exif-flash-redeye-1'   => 'chế độ giảm mắt đỏ',

'exif-focalplaneresolutionunit-2' => 'inch',

'exif-sensingmethod-1' => 'Không định rõ',
'exif-sensingmethod-2' => 'Cảm biến vùng màu một mảnh',
'exif-sensingmethod-3' => 'Cảm biến vùng màu hai mảnh',
'exif-sensingmethod-4' => 'Cảm biến vùng màu ba mảnh',
'exif-sensingmethod-5' => 'Cảm biến vùng màu liên tục',
'exif-sensingmethod-7' => 'Cảm biến ba đường',
'exif-sensingmethod-8' => 'Cảm biến đường màu liên tục',

'exif-scenetype-1' => 'Hình chụp thẳng',

'exif-customrendered-0' => 'Thường',
'exif-customrendered-1' => 'Thủ công',

'exif-exposuremode-0' => 'Phơi sáng tự động',
'exif-exposuremode-1' => 'Phơi sáng thủ công',
'exif-exposuremode-2' => 'Tự động chụp nhiều hình',

'exif-whitebalance-0' => 'Cân bằng trắng tự động',
'exif-whitebalance-1' => 'Cân bằng trắng thủ công',

'exif-scenecapturetype-0' => 'Chuẩn',
'exif-scenecapturetype-1' => 'Nằm',
'exif-scenecapturetype-2' => 'Đứng',
'exif-scenecapturetype-3' => 'Cảnh ban đêm',

'exif-gaincontrol-0' => 'Không có',
'exif-gaincontrol-1' => 'Độ rọi thấp',
'exif-gaincontrol-2' => 'Độ rọi cao',
'exif-gaincontrol-3' => 'Độ rọi dưới thấp',
'exif-gaincontrol-4' => 'Độ rọi dưới cao',

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
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Nhìn gần',
'exif-subjectdistancerange-3' => 'Nhìn xa',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Vĩ độ bắc',
'exif-gpslatitude-s' => 'Vĩ độ nam',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Kinh độ đông',
'exif-gpslongitude-w' => 'Kinh độ tây',

'exif-gpsstatus-a' => 'Đang đo',
'exif-gpsstatus-v' => 'Mức độ khả năng liên điều hành',

'exif-gpsmeasuremode-2' => 'Đo 2 chiều',
'exif-gpsmeasuremode-3' => 'Đo 3 chiều',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilômét một giờ',
'exif-gpsspeed-m' => 'Dặm một giờ',
'exif-gpsspeed-n' => 'Hải lý một giờ',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Hướng thật',
'exif-gpsdirection-m' => 'Hướng từ trường',

# External editor support
'edit-externally'      => 'Sửa bằng phần mềm bên ngoài',
'edit-externally-help' => '(Xem [http://www.mediawiki.org/wiki/Manual:External_editors hướng dẫn cài đặt bằng tiếng Anh] để biết thêm thông tin)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'tất cả',
'imagelistall'     => 'tất cả',
'watchlistall2'    => 'tất cả',
'namespacesall'    => 'tất cả',
'monthsall'        => 'tất cả',
'limitall'         => 'tất cả',

# E-mail address confirmation
'confirmemail'              => 'Xác nhận thư điện tử',
'confirmemail_noemail'      => 'Bạn chưa đưa vào địa chỉ thư điện tử hợp lệ ở [[Special:Preferences|tùy chọn cá nhân]].',
'confirmemail_text'         => '{{SITENAME}} đòi hỏi bạn xác minh thư điện tử của mình
trước khi sử dụng tính năng thư điện tử. Nhấn vào nút bên dưới để gửi thư
xác nhận đến địa chỉ của bạn. Thư xác nhận sẽ có kèm một liên kết có chứa một mã số;
tải liên kết đó trong trình duyệt để xác nhận địa chỉ thư điện tử của bạn là đúng.',
'confirmemail_pending'      => 'Mã xác đã được gửi đến địa chỉ thư điện tử của bạn; nếu bạn
mới vừa tạo tài khoản, xin chờ vài phút để thư tới nơi rồi
hãy cố gắng yêu cầu mã mới.',
'confirmemail_send'         => 'Gửi thư xác nhận',
'confirmemail_sent'         => 'Thư xác nhận đã được gửi',
'confirmemail_oncreate'     => 'Đã gửi mã xác nhận đến địa chỉ thư điện tử của bạn.
Bạn không cần mã này để đăng nhập, nhưng sẽ cần sử dụng nó để bật các tính năng có dùng thư điện tử của wiki.',
'confirmemail_sendfailed'   => '{{SITENAME}} không thể gửi thư xác nhận.
Xin kiểm tra lại địa chỉ thư xem có bị nhầm ký tự nào không.

Chương trình thư báo rằng: $1',
'confirmemail_invalid'      => 'Mã xác nhận sai. Mã này có thể đã hết hạn',
'confirmemail_needlogin'    => 'Bạn cần phải $1 để xác nhận địa chỉ thư điện tử.',
'confirmemail_success'      => 'Thư điện tử của bạn đã được xác nhận. Bạn đã có thể đăng nhập và bắt đầu sử dụng wiki.',
'confirmemail_loggedin'     => 'Địa chỉ thư điện tử của bạn đã được xác nhận',
'confirmemail_error'        => 'Có trục trặc khi lưu xác nhận của bạn.',
'confirmemail_subject'      => 'Xác nhận thư điện tử tại {{SITENAME}}',
'confirmemail_body'         => 'Ai đó, có thể là bạn, từ địa chỉ IP $1,
đã đăng ký tài khoản có tên "$2" với địa chỉ thư điện tử này tại {{SITENAME}}.

Để xác nhận rằng tài khoản này thực sự là của bạn và để kích hoạt tính năng thư điện tử tại {{SITENAME}}, xin mở liên kết này trong trình duyệt:

$3

Nếu bạn *không* đăng ký tài khoản, hãy nhấn vào liên kết này
để hủy thủ tục xác nhận địa chỉ thư điện tử:

$5

Mã xác nhận này sẽ hết hạn vào $4.',
'confirmemail_body_changed' => 'Ai đó, có thể là bạn, từ địa chỉ IP $1, đã đăng ký tài khoản có
tên "$2" với địa chỉ thư điện tử này tại {{SITENAME}}.

Để xác nhận rằng tài khoản này thực sự là của bạn và để kích hoạt tính năng
thư điện tử tại {{SITENAME}}, xin mở liên kết này trong trình duyệt:

$3

Nếu tài khoản *không* phải là của bạn, hãy nhấn vào liên kết này để hủy thủ
tục xác nhận địa chỉ thư điện tử:

$5

Mã xác nhận này sẽ hết hạn vào $4.',
'confirmemail_invalidated'  => 'Đã hủy xác nhận địa chỉ thư điện tử',
'invalidateemail'           => 'Hủy xác nhận thư điện tử',

# Scary transclusion
'scarytranscludedisabled' => '[Nhúng giữa các wiki bị tắt]',
'scarytranscludefailed'   => '[Truy xuất bản mẫu cho $1 thất bại]',
'scarytranscludetoolong'  => '[Địa chỉ URL quá dài]',

# Trackbacks
'trackbackbox'      => 'Các TrackBack về trang này:<br />
$1',
'trackbackremove'   => '([$1 Xóa])',
'trackbacklink'     => 'TrackBack',
'trackbackdeleteok' => 'Đã xóa trackback.',

# Delete conflict
'deletedwhileediting' => "'''Cảnh báo''': Trang này đã bị xóa sau khi bắt đầu sửa đổi!",
'confirmrecreate'     => "Thành viên [[User:$1|$1]] ([[User talk:$1|thảo luận]]) đã xóa trang này sau khi bạn bắt đầu sửa đổi trang với lý do:
: ''$2''
Xin hãy xác nhận bạn thực sự muốn tạo lại trang này.",
'recreate'            => 'Tạo ra lại',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Làm sạch vùng nhớ đệm của trang này?',
'confirm-purge-bottom' => 'Làm mới một trang sẽ giúp xóa bộ đệm và buộc hiển thị phiên bản gần nhất.',

# Separators for various lists, etc.
'ellipsis' => '…',

# Multipage image navigation
'imgmultipageprev' => '← trang trước',
'imgmultipagenext' => 'trang sau →',
'imgmultigo'       => 'Xem',
'imgmultigoto'     => 'Đi đến trang $1',

# Table pager
'ascending_abbrev'         => 'tăng',
'descending_abbrev'        => 'giảm',
'table_pager_next'         => 'Trang sau',
'table_pager_prev'         => 'Trang trước',
'table_pager_first'        => 'Trang đầu',
'table_pager_last'         => 'Trang cuối',
'table_pager_limit'        => 'Xem $1 kết quả mỗi trang',
'table_pager_limit_label'  => 'Số khoản mỗi trang:',
'table_pager_limit_submit' => 'Xem',
'table_pager_empty'        => 'Không có kết quả nào.',

# Auto-summaries
'autosumm-blank'   => 'Tẩy trống trang',
'autosumm-replace' => 'Thay cả nội dung bằng “$1”',
'autoredircomment' => 'Đổi hướng đến [[$1]]',
'autosumm-new'     => 'Tạo trang mới với nội dung ‘$1’',

# Size units
'size-kilobytes' => '$1 kB',

# Live preview
'livepreview-loading' => 'Đang tải…',
'livepreview-ready'   => 'Đang tải… Xong!',
'livepreview-failed'  => 'Không thể xem thử trực tiếp! Hãy dùng thử chế độ xem thử thông thường.',
'livepreview-error'   => 'Không thể kết nối: $1 “$2”. Hãy dùng thử chế độ xem thử thông thường.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Những thay đổi trong vòng $1 {{PLURAL:||}}giây trở lại đây có thể chưa xuất hiện trong danh sách.',
'lag-warn-high'   => 'Do độ trễ của máy chủ cơ sở dữ liệu, những thay đổi trong vòng $1 {{PLURAL:$1||}}giây trở lại đây có thể chưa xuất hiện trong danh sách.',

# Watchlist editor
'watchlistedit-numitems'       => 'Danh sách theo dõi của bạn có $1 {{PLURAL:$1|tựa đề|tựa đề}}, không tính các trang thảo luận.',
'watchlistedit-noitems'        => 'Danh sách các trang bạn theo dõi hiện không có gì.',
'watchlistedit-normal-title'   => 'Sửa các trang tôi theo dõi',
'watchlistedit-normal-legend'  => 'Bỏ các trang đang theo dõi ra khỏi danh sách',
'watchlistedit-normal-explain' => 'Tên các trang bạn theo dõi được hiển thị dưới đây. Để xóa một tên trang, chọn vào hộp kiểm bên cạnh nó, rồi nhấn “{{int:Watchlistedit-normal-submit}}”. Bạn cũng có thể [[Special:Watchlist/raw|sửa danh sách theo dạng thô]].',
'watchlistedit-normal-submit'  => 'Bỏ trang đã chọn',
'watchlistedit-normal-done'    => '$1 {{PLURAL:$1|tựa đề|tựa đề}} đã được xóa khỏi danh sách các trang theo dõi:',
'watchlistedit-raw-title'      => 'Sửa danh sách theo dõi dạng thô',
'watchlistedit-raw-legend'     => 'Sửa danh sách theo dõi dạng thô',
'watchlistedit-raw-explain'    => 'Danh sách này có tên các trang bạn theo dõi để bạn sửa chữa bằng cách thêm vào hoặc bỏ ra khỏi danh sách; mỗi trang một hàng.
Khi xong, nhấn nút ”{{int:Watchlistedit-raw-submit}}”.
Bạn cũng có thể [[Special:Watchlist/edit|dùng trang sửa đổi bình thường]] để sửa danh sách này.',
'watchlistedit-raw-titles'     => 'Tên các trang:',
'watchlistedit-raw-submit'     => 'Cập nhật Trang tôi theo dõi',
'watchlistedit-raw-done'       => 'Danh sách các trang bạn theo dõi đã được cập nhật.',
'watchlistedit-raw-added'      => '$1 {{PLURAL:$1|tựa đề|tựa đề}} đã được thêm vào:',
'watchlistedit-raw-removed'    => '$1 {{PLURAL:$1|tựa đề|tựa đề}} đã được xóa khỏi danh sách:',

# Watchlist editing tools
'watchlisttools-view' => 'Xem thay đổi trên các trang theo dõi',
'watchlisttools-edit' => 'Xem và sửa danh sách theo dõi',
'watchlisttools-raw'  => 'Sửa danh sách theo dõi dạng thô',

# Iranian month names
'iranian-calendar-m1'  => 'Farvardin',
'iranian-calendar-m2'  => 'Ordibehesht',
'iranian-calendar-m3'  => 'Khordad',
'iranian-calendar-m4'  => 'Tir',
'iranian-calendar-m5'  => 'Mordad',
'iranian-calendar-m6'  => 'Shahrivar',
'iranian-calendar-m7'  => 'Mehr',
'iranian-calendar-m8'  => 'Aban',
'iranian-calendar-m9'  => 'Azar',
'iranian-calendar-m10' => 'Dey',
'iranian-calendar-m11' => 'Bahman',
'iranian-calendar-m12' => 'Esfand',

# Hijri month names
'hijri-calendar-m1'  => 'Muharram',
'hijri-calendar-m2'  => 'Safar',
'hijri-calendar-m3'  => 'Rabi’ al-awwal',
'hijri-calendar-m4'  => 'Rabi’ al-thani',
'hijri-calendar-m5'  => 'Jumada al-awwal',
'hijri-calendar-m6'  => 'Jumada al-thani',
'hijri-calendar-m7'  => 'Rajab',
'hijri-calendar-m8'  => 'Sha’aban',
'hijri-calendar-m9'  => 'Ramadan',
'hijri-calendar-m10' => 'Shawwal',
'hijri-calendar-m11' => 'Dhu al-Qi’dah',
'hijri-calendar-m12' => 'Dhu al-Hijjah',

# Hebrew month names
'hebrew-calendar-m1'  => 'Tishrei',
'hebrew-calendar-m2'  => 'Cheshvan',
'hebrew-calendar-m3'  => 'Kislev',
'hebrew-calendar-m4'  => 'Tevet',
'hebrew-calendar-m5'  => 'Shevat',
'hebrew-calendar-m6'  => 'Adar',
'hebrew-calendar-m6a' => 'Adar 1',
'hebrew-calendar-m6b' => 'Adar 2',
'hebrew-calendar-m7'  => 'Nisan',
'hebrew-calendar-m8'  => 'Iyar',
'hebrew-calendar-m9'  => 'Sivan',
'hebrew-calendar-m10' => 'Tamuz',
'hebrew-calendar-m11' => 'Av',
'hebrew-calendar-m12' => 'Elul',

# Core parser functions
'unknown_extension_tag' => 'Không hiểu thẻ mở rộng “$1”',
'duplicate-defaultsort' => 'Cảnh báo: Từ khóa xếp mặc định “$2” ghi đè từ khóa trước, “$1”.',

# Special:Version
'version'                          => 'Phiên bản',
'version-extensions'               => 'Các phần mở rộng được cài đặt',
'version-specialpages'             => 'Trang đặc biệt',
'version-parserhooks'              => 'Hook trong bộ xử lý',
'version-variables'                => 'Biến',
'version-skins'                    => 'Hình dạng',
'version-other'                    => 'Phần mở rộng khác',
'version-mediahandlers'            => 'Bộ xử lý phương tiện',
'version-hooks'                    => 'Các hook',
'version-extension-functions'      => 'Hàm mở rộng',
'version-parser-extensiontags'     => 'Thẻ mở rộng trong bộ xử lý',
'version-parser-function-hooks'    => 'Hook cho hàm cú pháp trong bộ xử lý',
'version-skin-extension-functions' => 'Hàm mở rộng skin',
'version-hook-name'                => 'Tên hook',
'version-hook-subscribedby'        => 'Được theo dõi bởi',
'version-version'                  => '(Phiên bản $1)',
'version-license'                  => 'Giấy phép bản quyền',
'version-poweredby-credits'        => "Wiki này chạy trên '''[http://www.mediawiki.org/ MediaWiki]''', bản quyền © 2001–$1 $2.",
'version-poweredby-others'         => 'những người khác',
'version-license-info'             => "MediaWiki là phần mềm tự do; bạn được phép tái phân phối và/hoặc sửa đổi nó theo những điều khoản của Giấy phép Công cộng GNU do Quỹ Phần mềm Tự do xuất bản; phiên bản 2 hay bất kỳ phiên bản nào mới hơn nào của Giấy phép.

MediaWiki được phân phối với hy vọng rằng nó sẽ hữu ích, nhưng '''không có bất kỳ một bảo đảm nào cả''', ngay cả những bảo đảm ngụ ý cho '''các mục đích thương mại''' hoặc cho '''một mục đích đặc biệt nào đó'''. Xem Giấy phép Công cộng GNU để biết thêm chi tiết.

Có lẽ bạn đã nhận [{{SERVER}}{{SCRIPTPATH}}/COPYING bản sao Giấy phép Công cộng GNU] đi kèm với tác phẩm này; nếu không, hãy viết thư đến:
 Free Software Foundation, Inc.
 51 Franklin St., Fifth Floor
 Boston, MA 02110-1301
 USA
hoặc [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html đọc nó trực tuyến].",
'version-software'                 => 'Phần mềm được cài đặt',
'version-software-product'         => 'Phần mềm',
'version-software-version'         => 'Phiên bản',

# Special:FilePath
'filepath'         => 'Đường dẫn tập tin',
'filepath-page'    => 'Tập tin:',
'filepath-submit'  => 'Hiển thị tập tin',
'filepath-summary' => 'Trang này chuyển bạn thẳng đến địa chỉ của một tập tin. Nếu là hình, địa chỉ là của hình kích thước tối đa; các loại tập tin khác sẽ được mở lên ngay trong chương trình đúng.

Hãy ghi vào tên tập tin, không bao gồm tiền tố “{{ns:file}}:”.',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Tìm kiếm các tập tin trùng lắp',
'fileduplicatesearch-summary'  => 'Tìm kiếm các bản sao y hệt với tập tin khác, theo giá trị băm của nó.

Hãy cho vào tên của tập tin, trừ tiền tố “{{ns:file}}:”.',
'fileduplicatesearch-legend'   => 'Tìm kiếm tập tin trùng lắp',
'fileduplicatesearch-filename' => 'Tên tập tin:',
'fileduplicatesearch-submit'   => 'Tìm kiếm',
'fileduplicatesearch-info'     => '$1×$2 điểm ảnh<br />Kích thước tập tin: $3<br />Định dạng MIME: $4',
'fileduplicatesearch-result-1' => 'Không có bản sao y hệt với tập tin “$1”.',
'fileduplicatesearch-result-n' => 'Có {{PLURAL:$2|1 bản sao|$2 bản sao}} y hệt với tập tin “$1”.',

# Special:SpecialPages
'specialpages'                   => 'Các trang đặc biệt',
'specialpages-note'              => '----
* Trang đặc biệt thông thường.
* <strong class="mw-specialpagerestricted">Trang đặc biệt có hạn chế.</strong>',
'specialpages-group-maintenance' => 'Báo cáo bảo quản',
'specialpages-group-other'       => 'Những trang đặc biệt khác',
'specialpages-group-login'       => 'Đăng nhập / Mở tài khoản',
'specialpages-group-changes'     => 'Thay đổi gần đây và nhật trình',
'specialpages-group-media'       => 'Báo cáo và tải lên phương tiện',
'specialpages-group-users'       => 'Thành viên và chức năng',
'specialpages-group-highuse'     => 'Trang được dùng nhiều',
'specialpages-group-pages'       => 'Danh sách các trang',
'specialpages-group-pagetools'   => 'Công cụ cho trang',
'specialpages-group-wiki'        => 'Dữ liệu và công cụ cho wiki',
'specialpages-group-redirects'   => 'Đang đổi hướng trang đặc biệt',
'specialpages-group-spam'        => 'Công cụ chống spam',

# Special:BlankPage
'blankpage'              => 'Trang trắng',
'intentionallyblankpage' => 'Trang này được chủ định để trắng',

# External image whitelist
'external_image_whitelist' => ' #Hãy để yên dòng này<pre>
#Hãy đặt các mẩu biểu thức chính quy (chỉ gồm phần ở giữa //) vào phía dưới
#Những mẩu này sẽ được so trùng với địa chỉ URL của hình ảnh được nhúng trực tiếp từ bên ngoài
#Những địa chỉ nào trùng sẽ hiển thị thành hình ảnh, nếu không thì chỉ hiển thị liên kết đến hình
#Những dòng bắt đầu bằng # được xem là chú thích
#Không phân biệt chữ hoa chữ thường

#Hãy đặt các mẩu biểu thức chính quy ở phía trên dòng này. Hãy để yên dòng này</pre>',

# Special:Tags
'tags'                    => 'Các thẻ đánh dấu thay đổi hợp lệ',
'tag-filter'              => 'Bộ lọc [[Special:Tags|thẻ]]:',
'tag-filter-submit'       => 'Bộ lọc',
'tags-title'              => 'Thẻ đánh dấu',
'tags-intro'              => 'Trang này liệt kê các thẻ đánh dấu mà phần mềm dùng nó để đánh dấu một sửa đổi, và ý nghĩa của nó.',
'tags-tag'                => 'Tên thẻ',
'tags-display-header'     => 'Hiển thị trên danh sách thay đổi',
'tags-description-header' => 'Mô tả ý nghĩa đầy đủ',
'tags-hitcount-header'    => 'Các thay đổi được ghi thẻ',
'tags-edit'               => 'sửa',
'tags-hitcount'           => '$1 {{PLURAL:$1|thay đổi|thay đổi}}',

# Special:ComparePages
'comparepages'     => 'So sánh trang',
'compare-selector' => 'So sánh phiên bản trang',
'compare-page1'    => 'Trang 1',
'compare-page2'    => 'Trang 2',
'compare-rev1'     => 'Phiên bản 1',
'compare-rev2'     => 'Phiên bản 2',
'compare-submit'   => 'So sánh',

# Database error messages
'dberr-header'      => 'Wiki này đang gặp trục trặc',
'dberr-problems'    => 'Xin lỗi! Trang này đang gặp phải những khó khăn về kỹ thuật.',
'dberr-again'       => 'Xin thử đợi vài phút rồi tải lại trang.',
'dberr-info'        => '(Không thể liên lạc với máy chủ cơ sở dữ liệu: $1)',
'dberr-usegoogle'   => 'Bạn có thể thử tìm trên Google trong khi chờ đợi.',
'dberr-outofdate'   => 'Chú ý rằng các chỉ mục của Google có thể đã lỗi thời.',
'dberr-cachederror' => 'Sau đây là bản sao được lưu bộ đệm của trang bạn muốn xem, và có thể đã lỗi thời.',

# HTML forms
'htmlform-invalid-input'       => 'Có vấn đề trong dữ liệu bạn vừa đưa vào',
'htmlform-select-badoption'    => 'Giá trị đưa vào không hợp lệ.',
'htmlform-int-invalid'         => 'Giá trị đưa vào không phải số nguyên.',
'htmlform-float-invalid'       => 'Giá trị chỉ định không phải là con số.',
'htmlform-int-toolow'          => 'Giá trị đưa vào phải ít nhất $1',
'htmlform-int-toohigh'         => 'Giá trị không được vượt quá $1',
'htmlform-required'            => 'Phần này đòi giá trị',
'htmlform-submit'              => 'Đăng',
'htmlform-reset'               => 'Hủy các thay đổi',
'htmlform-selectorother-other' => 'Khác',

# SQLite database support
'sqlite-has-fts' => '$1 với sự hỗ trợ tìm kiếm toàn văn',
'sqlite-no-fts'  => '$1 không có hỗ trợ tìm kiếm toàn văn',

# Special:DisableAccount
'disableaccount'             => 'Vô hiệu hóa tài khoản người dùng',
'disableaccount-user'        => 'Tên người dùng:',
'disableaccount-reason'      => 'Lý do:',
'disableaccount-confirm'     => "Vô hiệu hóa tài khoản của người dùng này.
Người dùng sẽ không thể đăng nhập, mặc định lại mật khẩu, hoặc nhận thông báo qua thư điện tử.
Nếu người dùng đã dăng nhập vào bất cứ wiki nào trong hệ thống, nó sẽ bị đăng xuất ngay.
''Lưu ý rằng chỉ có người quản trị hệ thống can thiệp có thể lùi lại việc vô hiệu hóa tài khoản.''",
'disableaccount-mustconfirm' => 'Bạn phải xác nhận rằng bạn muốn vô hiệu hóa tài khoản này.',
'disableaccount-nosuchuser'  => 'Tài khoản người dùng “$1” không tồn tại.',
'disableaccount-success'     => 'Tài khoản người dùng “$1” đã bị vô hiệu hóa vĩnh viễn.',
'disableaccount-logentry'    => 'đã vô hiệu hóa vĩnh viễn tài khoản của người dùng [[$1]]',

);
