<?php
/** Korean (한국어)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Albamhandae
 * @author Chanhee
 * @author Chulki Lee
 * @author Cwt96
 * @author Devunt
 * @author Ficell
 * @author Freebiekr
 * @author Gapo
 * @author Gjue
 * @author Ha98574
 * @author IRTC1015
 * @author ITurtle
 * @author Idh0854
 * @author Jmkim dot com
 * @author Kaganer
 * @author Klutzy
 * @author Kwj2772
 * @author Mintz0223
 * @author Pi.C.Noizecehx
 * @author PuzzletChung
 * @author TheAlpha for knowledge
 * @author ToePeu
 * @author Yknok29
 * @author לערי ריינהארט
 * @author 관인생략
 * @author 아라
 */

$namespaceNames = array(
	NS_MEDIA            => '미디어',
	NS_SPECIAL          => '특수기능',
	NS_TALK             => '토론',
	NS_USER             => '사용자',
	NS_USER_TALK        => '사용자토론',
	NS_PROJECT_TALK     => '$1토론',
	NS_FILE             => '파일',
	NS_FILE_TALK        => '파일토론',
	NS_MEDIAWIKI        => '미디어위키',
	NS_MEDIAWIKI_TALK   => '미디어위키토론',
	NS_TEMPLATE         => '틀',
	NS_TEMPLATE_TALK    => '틀토론',
	NS_HELP             => '도움말',
	NS_HELP_TALK        => '도움말토론',
	NS_CATEGORY         => '분류',
	NS_CATEGORY_TALK    => '분류토론',
);

$namespaceAliases = array(
	'특'  => NS_SPECIAL,
	'MediaWiki토론' => NS_MEDIAWIKI_TALK,
	'그림' => NS_FILE,
	'파일토론' => NS_FILE_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( '활동적인사용자' ),
	'Allmessages'               => array( '모든메시지' ),
	'Allpages'                  => array( '모든문서' ),
	'Ancientpages'              => array( '오래된문서' ),
	'Blankpage'                 => array( '빈문서' ),
	'Block'                     => array( '차단' ),
	'Blockme'                   => array( '자가차단' ),
	'Booksources'               => array( '책찾기' ),
	'BrokenRedirects'           => array( '끊긴넘겨주기' ),
	'Categories'                => array( '분류' ),
	'ChangePassword'            => array( '비밀번호변경', '비밀번호바꾸기' ),
	'ComparePages'              => array( '문서비교' ),
	'Confirmemail'              => array( '이메일인증' ),
	'Contributions'             => array( '기여', '기여목록', '사용자기여' ),
	'CreateAccount'             => array( '계정만들기', '가입' ),
	'Deadendpages'              => array( '막다른문서' ),
	'DeletedContributions'      => array( '삭제된기여' ),
	'Disambiguations'           => array( '동음이의', '동음이의문서' ),
	'DoubleRedirects'           => array( '이중넘겨주기' ),
	'Emailuser'                 => array( '이메일보내기' ),
	'Export'                    => array( '내보내기' ),
	'Fewestrevisions'           => array( '역사짧은문서' ),
	'FileDuplicateSearch'       => array( '중복파일찾기' ),
	'Filepath'                  => array( '파일경로', '그림경로' ),
	'Import'                    => array( '가져오기' ),
	'Invalidateemail'           => array( '이메일인증취소', '이메일인증해제' ),
	'BlockList'                 => array( '차단된사용자', '차단목록' ),
	'LinkSearch'                => array( '외부링크찾기', '외부링크검색' ),
	'Listadmins'                => array( '관리자', '관리자목록' ),
	'Listbots'                  => array( '봇', '봇목록' ),
	'Listfiles'                 => array( '파일', '그림', '파일목록', '그림목록' ),
	'Listgrouprights'           => array( '사용자권한', '권한목록' ),
	'Listredirects'             => array( '넘겨주기', '넘겨주기목록' ),
	'Listusers'                 => array( '사용자', '사용자목록' ),
	'Lockdb'                    => array( 'DB잠금', 'DB잠그기' ),
	'Log'                       => array( '기록', '로그' ),
	'Lonelypages'               => array( '외톨이문서' ),
	'Longpages'                 => array( '긴문서' ),
	'MergeHistory'              => array( '역사합치기' ),
	'MIMEsearch'                => array( 'MIME찾기', 'MIME검색' ),
	'Mostcategories'            => array( '많이분류된문서' ),
	'Mostimages'                => array( '많이쓰는파일', '많이쓰는그림' ),
	'Mostlinked'                => array( '많이링크된문서' ),
	'Mostlinkedcategories'      => array( '많이쓰는분류' ),
	'Mostlinkedtemplates'       => array( '많이쓰는틀' ),
	'Mostrevisions'             => array( '역사긴문서' ),
	'Movepage'                  => array( '이동', '문서이동' ),
	'Mycontributions'           => array( '내기여', '내기여목록' ),
	'Mypage'                    => array( '내사용자문서' ),
	'Mytalk'                    => array( '내사용자토론' ),
	'Myuploads'                 => array( '내가올린파일' ),
	'Newimages'                 => array( '새파일', '새그림' ),
	'Newpages'                  => array( '새문서' ),
	'PasswordReset'             => array( '암호변경' ),
	'Popularpages'              => array( '인기있는문서' ),
	'Preferences'               => array( '환경설정' ),
	'Prefixindex'               => array( '접두어찾기' ),
	'Protectedpages'            => array( '보호된문서' ),
	'Protectedtitles'           => array( '생성보호된문서' ),
	'Randompage'                => array( '임의문서' ),
	'Randomredirect'            => array( '임의넘겨주기' ),
	'Recentchanges'             => array( '최근바뀜' ),
	'Recentchangeslinked'       => array( '링크최근바뀜' ),
	'Revisiondelete'            => array( '특정판삭제' ),
	'Search'                    => array( '찾기', '검색' ),
	'Shortpages'                => array( '짧은문서' ),
	'Specialpages'              => array( '특수문서', '특수기능' ),
	'Statistics'                => array( '통계' ),
	'Tags'                      => array( '태그' ),
	'Unblock'                   => array( '차단해제' ),
	'Uncategorizedcategories'   => array( '분류안된분류' ),
	'Uncategorizedimages'       => array( '분류안된파일', '분류안된그림' ),
	'Uncategorizedpages'        => array( '분류안된문서' ),
	'Uncategorizedtemplates'    => array( '분류안된틀' ),
	'Undelete'                  => array( '삭제취소', '삭제된문서' ),
	'Unlockdb'                  => array( 'DB잠금취소', 'DB잠금해제' ),
	'Unusedcategories'          => array( '안쓰는분류' ),
	'Unusedimages'              => array( '안쓰는파일', '안쓰는그림' ),
	'Unusedtemplates'           => array( '안쓰는틀' ),
	'Unwatchedpages'            => array( '주시안되는문서' ),
	'Upload'                    => array( '파일올리기', '그림올리기' ),
	'Userlogin'                 => array( '로그인' ),
	'Userlogout'                => array( '로그아웃' ),
	'Userrights'                => array( '권한조정' ),
	'Version'                   => array( '버전' ),
	'Wantedcategories'          => array( '필요한분류' ),
	'Wantedfiles'               => array( '필요한파일', '필요한그림' ),
	'Wantedpages'               => array( '필요한문서' ),
	'Wantedtemplates'           => array( '필요한틀' ),
	'Watchlist'                 => array( '주시문서목록', '주시목록' ),
	'Whatlinkshere'             => array( '가리키는문서', '링크하는문서' ),
	'Withoutinterwiki'          => array( '인터위키없는문서' ),
);

$magicWords = array(
	'redirect'                => array( '0', '#넘겨주기', '#REDIRECT' ),
	'notoc'                   => array( '0', '__목차숨김__', '__NOTOC__' ),
	'nogallery'               => array( '0', '__화랑숨김__', '__갤러리숨김__', '__NOGALLERY__' ),
	'forcetoc'                => array( '0', '__목차보임__', '__FORCETOC__' ),
	'toc'                     => array( '0', '__목차__', '__TOC__' ),
	'noeditsection'           => array( '0', '__단락편집숨김__', '__NOEDITSECTION__' ),
	'noheader'                => array( '0', '__헤더숨김__', '__NOHEADER__' ),
	'currentmonth'            => array( '1', '현재월', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'           => array( '1', '현재월1', 'CURRENTMONTH1' ),
	'currentmonthname'        => array( '1', '현재월이름', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'     => array( '1', '현재월이름소유격', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'      => array( '1', '현재월이름약자', 'CURRENTMONTHABBREV' ),
	'currentday'              => array( '1', '현재일', 'CURRENTDAY' ),
	'currentday2'             => array( '1', '현재일2', 'CURRENTDAY2' ),
	'currentdayname'          => array( '1', '현재요일', 'CURRENTDAYNAME' ),
	'currentyear'             => array( '1', '현재년', 'CURRENTYEAR' ),
	'currenttime'             => array( '1', '현재시분', '현재시각', 'CURRENTTIME' ),
	'currenthour'             => array( '1', '현재시', 'CURRENTHOUR' ),
	'localmonth'              => array( '1', '지역월', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'             => array( '1', '지역월1', 'LOCALMONTH1' ),
	'localmonthname'          => array( '1', '지역월이름', 'LOCALMONTHNAME' ),
	'localmonthnamegen'       => array( '1', '지역월이름소유격', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'        => array( '1', '지역월이름약자', 'LOCALMONTHABBREV' ),
	'localday'                => array( '1', '지역일', 'LOCALDAY' ),
	'localday2'               => array( '1', '지역일2', 'LOCALDAY2' ),
	'localdayname'            => array( '1', '지역요일', 'LOCALDAYNAME' ),
	'localyear'               => array( '1', '지역년', 'LOCALYEAR' ),
	'localtime'               => array( '1', '지역시분', '지역시각', 'LOCALTIME' ),
	'localhour'               => array( '1', '지역시', 'LOCALHOUR' ),
	'numberofpages'           => array( '1', '모든문서수', 'NUMBEROFPAGES' ),
	'numberofarticles'        => array( '1', '문서수', 'NUMBEROFARTICLES' ),
	'numberoffiles'           => array( '1', '파일수', '그림수', 'NUMBEROFFILES' ),
	'numberofusers'           => array( '1', '사용자수', '계정수', 'NUMBEROFUSERS' ),
	'numberofactiveusers'     => array( '1', '활동중인사용자수', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'           => array( '1', '편집수', 'NUMBEROFEDITS' ),
	'numberofviews'           => array( '1', '조회수', 'NUMBEROFVIEWS' ),
	'pagename'                => array( '1', '문서이름', 'PAGENAME' ),
	'pagenamee'               => array( '1', '문서이름E', 'PAGENAMEE' ),
	'namespace'               => array( '1', '이름공간', 'NAMESPACE' ),
	'namespacee'              => array( '1', '이름공간E', 'NAMESPACEE' ),
	'talkspace'               => array( '1', '토론이름공간', 'TALKSPACE' ),
	'talkspacee'              => array( '1', '토론이름공간E', 'TALKSPACEE' ),
	'subjectspace'            => array( '1', '본문서이름공간', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'           => array( '1', '본문서이름공간E', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'            => array( '1', '전체문서이름', 'FULLPAGENAME' ),
	'fullpagenamee'           => array( '1', '전체문서이름E', 'FULLPAGENAMEE' ),
	'subpagename'             => array( '1', '하위문서이름', 'SUBPAGENAME' ),
	'subpagenamee'            => array( '1', '하위문서이름E', 'SUBPAGENAMEE' ),
	'basepagename'            => array( '1', '상위문서이름', 'BASEPAGENAME' ),
	'basepagenamee'           => array( '1', '상위문서이름E', 'BASEPAGENAMEE' ),
	'talkpagename'            => array( '1', '토론문서이름', 'TALKPAGENAME' ),
	'talkpagenamee'           => array( '1', '토론문서이름E', 'TALKPAGENAMEE' ),
	'subjectpagename'         => array( '1', '본문서이름', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'        => array( '1', '본문서이름E', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                     => array( '0', '메시지:', 'MSG:' ),
	'subst'                   => array( '0', '풀기:', 'SUBST:' ),
	'safesubst'               => array( '0', '안전풀기:', 'SAFESUBST:' ),
	'img_thumbnail'           => array( '1', '섬네일', '썸네일', 'thumbnail', 'thumb' ),
	'img_manualthumb'         => array( '1', '섬네일=$1', '썸네일=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'               => array( '1', '오른쪽', 'right' ),
	'img_left'                => array( '1', '왼쪽', 'left' ),
	'img_none'                => array( '1', '없음', 'none' ),
	'img_width'               => array( '1', '$1픽셀', '$1px' ),
	'img_center'              => array( '1', '가운데', 'center', 'centre' ),
	'img_framed'              => array( '1', '프레임', 'framed', 'enframed', 'frame' ),
	'img_frameless'           => array( '1', '프레임없음', 'frameless' ),
	'img_page'                => array( '1', '문서=$1', 'page=$1', 'page $1' ),
	'img_border'              => array( '1', '테두리', 'border' ),
	'img_baseline'            => array( '1', '밑줄', 'baseline' ),
	'img_sub'                 => array( '1', '아래첨자', 'sub' ),
	'img_super'               => array( '1', '위첨자', 'super', 'sup' ),
	'img_top'                 => array( '1', '위', 'top' ),
	'img_middle'              => array( '1', '중간', 'middle' ),
	'img_bottom'              => array( '1', '아래', 'bottom' ),
	'img_link'                => array( '1', '링크=$1', 'link=$1' ),
	'sitename'                => array( '1', '사이트이름', 'SITENAME' ),
	'ns'                      => array( '0', '이름:', '이름공간:', 'NS:' ),
	'nse'                     => array( '0', '이름E:', '이름공간E:', 'NSE:' ),
	'localurl'                => array( '0', '지역주소:', 'LOCALURL:' ),
	'localurle'               => array( '0', '지역주소E:', 'LOCALURLE:' ),
	'server'                  => array( '0', '서버', 'SERVER' ),
	'servername'              => array( '0', '서버이름', 'SERVERNAME' ),
	'scriptpath'              => array( '0', '스크립트경로', 'SCRIPTPATH' ),
	'stylepath'               => array( '0', '스타일경로', 'STYLEPATH' ),
	'grammar'                 => array( '0', '문법:', 'GRAMMAR:' ),
	'gender'                  => array( '0', '성별:', 'GENDER:' ),
	'currentweek'             => array( '1', '현재주', 'CURRENTWEEK' ),
	'currentdow'              => array( '1', '현재요일숫자', 'CURRENTDOW' ),
	'localweek'               => array( '1', '지역주', 'LOCALWEEK' ),
	'localdow'                => array( '1', '지역요일숫자', 'LOCALDOW' ),
	'revisionid'              => array( '1', '판번호', 'REVISIONID' ),
	'revisionday'             => array( '1', '판일', 'REVISIONDAY' ),
	'revisionday2'            => array( '1', '판일2', 'REVISIONDAY2' ),
	'revisionmonth'           => array( '1', '판월', 'REVISIONMONTH' ),
	'revisionmonth1'          => array( '1', '판월1', 'REVISIONMONTH1' ),
	'revisionyear'            => array( '1', '판년', 'REVISIONYEAR' ),
	'revisiontimestamp'       => array( '1', '판타임스탬프', 'REVISIONTIMESTAMP' ),
	'revisionuser'            => array( '1', '판사용자', 'REVISIONUSER' ),
	'plural'                  => array( '0', '복수:', '복수형:', 'PLURAL:' ),
	'fullurl'                 => array( '0', '전체주소:', 'FULLURL:' ),
	'fullurle'                => array( '0', '전체주소E:', 'FULLURLE:' ),
	'lcfirst'                 => array( '0', '첫소문자:', 'LCFIRST:' ),
	'ucfirst'                 => array( '0', '첫대문자:', 'UCFIRST:' ),
	'lc'                      => array( '0', '소문자:', 'LC:' ),
	'uc'                      => array( '0', '대문자:', 'UC:' ),
	'displaytitle'            => array( '1', '제목표시', 'DISPLAYTITLE' ),
	'newsectionlink'          => array( '1', '__새글쓰기__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'        => array( '1', '__새글쓰기숨기기__', '__NONEWSECTIONLINK__' ),
	'currentversion'          => array( '1', '현재버전', 'CURRENTVERSION' ),
	'urlencode'               => array( '0', '주소인코딩:', 'URLENCODE:' ),
	'anchorencode'            => array( '0', '책갈피인코딩', 'ANCHORENCODE' ),
	'currenttimestamp'        => array( '1', '현재타임스탬프', 'CURRENTTIMESTAMP' ),
	'localtimestamp'          => array( '1', '지역타임스탬프', 'LOCALTIMESTAMP' ),
	'language'                => array( '0', '#언어:', '#LANGUAGE:' ),
	'contentlanguage'         => array( '1', '기본언어', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'        => array( '1', '이름공간문서수', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'          => array( '1', '관리자수', 'NUMBEROFADMINS' ),
	'special'                 => array( '0', '특수기능', 'special' ),
	'defaultsort'             => array( '1', '기본정렬:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                => array( '0', '파일경로:', '그림경로:', 'FILEPATH:' ),
	'tag'                     => array( '0', '태그', 'tag' ),
	'hiddencat'               => array( '1', '__숨은분류__', '__HIDDENCAT__' ),
	'pagesincategory'         => array( '1', '분류문서수', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                => array( '1', '문서크기', 'PAGESIZE' ),
	'index'                   => array( '1', '__색인__', '__INDEX__' ),
	'noindex'                 => array( '1', '__색인거부__', '__NOINDEX__' ),
	'numberingroup'           => array( '1', '권한별사용자수', '그룹별사용자수', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'          => array( '1', '__넘겨주기고정__', '__STATICREDIRECT__' ),
	'protectionlevel'         => array( '1', '보호수준', 'PROTECTIONLEVEL' ),
	'formatdate'              => array( '0', '날짜형식', 'formatdate', 'dateformat' ),
);

$bookstoreList = array(
	'Aladdin.co.kr' => 'http://www.aladdin.co.kr/catalog/book.asp?ISBN=$1',
	'inherit' => true,
);

$datePreferences = array(
	'default',
	'ISO 8601',
);
$defaultDateFormat = 'ko';
$dateFormats = array(
	'ko time'            => 'H:i',
	'ko date'            => 'Y년 M월 j일 (D)',
	'ko both'            => 'Y년 M월 j일 (D) H:i',
);

$messages = array(
# User preference toggles
'tog-underline'               => '링크에 밑줄 표시하기:',
'tog-highlightbroken'         => '없는 문서로 연결된 링크를 <a href="" class="new">이렇게</a> 보이기 (선택하지 않으면 이렇게<a href="" class="internal">?</a> 보임)',
'tog-justify'                 => '문단 정렬하기',
'tog-hideminor'               => '최근 바뀜에서 사소한 편집을 숨기기',
'tog-hidepatrolled'           => '최근 바뀜에서 검토한 편집을 숨기기',
'tog-newpageshidepatrolled'   => '새 문서 목록에서 검토한 문서를 숨기기',
'tog-extendwatchlist'         => '주시문서 목록에서 가장 최근의 편집만이 아닌 모든 편집을 보기',
'tog-usenewrc'                => '최근 바뀜 및 주시 문서 목록에서 문서별 그룹 바뀜 (자바스크립트 필요)',
'tog-numberheadings'          => '머릿글 번호 매기기',
'tog-showtoolbar'             => '편집창에 툴바 보이기 (자바스크립트 필요)',
'tog-editondblclick'          => '더블 클릭으로 문서 편집하기 (자바스크립트 필요)',
'tog-editsection'             => '[편집] 링크로 부분 편집하기',
'tog-editsectiononrightclick' => '제목을 오른쪽 클릭해서 부분 편집하기 (자바스크립트 필요)',
'tog-showtoc'                 => '문서의 차례 보여주기 (머릿글이 4개 이상인 경우)',
'tog-rememberpassword'        => '이 브라우저에서 로그인 상태를 저장하기 (최대 $1일)',
'tog-watchcreations'          => '내가 만드는 문서와 내가 올린 파일을 주시문서 목록에 추가',
'tog-watchdefault'            => '내가 편집하는 문서와 파일을 주시문서 목록에 추가',
'tog-watchmoves'              => '내가 이동하는 문서와 파일을 주시문서 목록에 추가',
'tog-watchdeletion'           => '내가 삭제하는 문서와 파일을 주시문서 목록에 추가',
'tog-minordefault'            => '사소한 편집을 기본적으로 선택하기',
'tog-previewontop'            => '편집 상자 앞에 미리 보기 보기',
'tog-previewonfirst'          => '처음 편집할 때 미리 보기 보기',
'tog-nocache'                 => '브라우저의 문서 캐시 끄기',
'tog-enotifwatchlistpages'    => '주시문서 목록에 속한 문서나 파일이 바뀌면 이메일로 알림',
'tog-enotifusertalkpages'     => '내 토론 문서가 바뀌면 이메일로 알림',
'tog-enotifminoredits'        => '문서나 파일의 사소한 편집도 이메일로 알림',
'tog-enotifrevealaddr'        => '알림 메일에 내 이메일 주소를 밝히기',
'tog-shownumberswatching'     => '주시 사용자 수 보기',
'tog-oldsig'                  => '현재 서명:',
'tog-fancysig'                => '서명을 위키텍스트로 취급 (자동으로 링크를 걸지 않음)',
'tog-externaleditor'          => '바깥 편집기를 기본 편집기로 사용 (숙련자용. 컴퓨터에 특별한 설정이 필요. [//www.mediawiki.org/wiki/Manual:External_editors 자세한 정보 보기])',
'tog-externaldiff'            => '바깥 비교 도구를 기본 도구로 사용 (숙련자용. 컴퓨터에 특별한 설정이 필요. [//www.mediawiki.org/wiki/Manual:External_editors 자세한 설명 보기])',
'tog-showjumplinks'           => '접근성을 위한 "이동" 링크 쓰기 (일부 스킨에서만 작동)',
'tog-uselivepreview'          => '실시간 미리 보기 사용하기 (자바스크립트 필요) (시험 기능)',
'tog-forceeditsummary'        => '편집 요약을 쓰지 않았을 때 알려주기',
'tog-watchlisthideown'        => '주시문서 목록에서 내 편집을 숨기기',
'tog-watchlisthidebots'       => '주시문서 목록에서 봇 편집을 숨기기',
'tog-watchlisthideminor'      => '주시문서 목록에서 사소한 편집을 숨기기',
'tog-watchlisthideliu'        => '주시문서 목록에서 로그인한 사용자의 편집을 숨기기',
'tog-watchlisthideanons'      => '주시문서 목록에서 익명 사용자의 편집을 숨기기',
'tog-watchlisthidepatrolled'  => '주시문서 목록에서 검토한 편집을 숨기기',
'tog-nolangconversion'        => '변형 변환을 비활성화',
'tog-ccmeonemails'            => '이메일을 보낼 때 내 이메일로 복사본을 보내기',
'tog-diffonly'                => '편집 차이를 비교할 때 문서 내용을 보지 않기',
'tog-showhiddencats'          => '숨은 분류 보기',
'tog-noconvertlink'           => '링크 제목 변환을 비활성화',
'tog-norollbackdiff'          => '되돌리기 후 차이를 보이지 않기',

'underline-always'  => '항상',
'underline-never'   => '치지 않음',
'underline-default' => '스킨 또는 브라우저 설정을 따르기',

# Font style option in Special:Preferences
'editfont-style'     => '편집창의 글꼴:',
'editfont-default'   => '브라우저 설정을 따르기',
'editfont-monospace' => '고정폭 글꼴',
'editfont-sansserif' => '산세리프체',
'editfont-serif'     => '세리프체',

# Dates
'sunday'        => '일요일',
'monday'        => '월요일',
'tuesday'       => '화요일',
'wednesday'     => '수요일',
'thursday'      => '목요일',
'friday'        => '금요일',
'saturday'      => '토요일',
'sun'           => '일',
'mon'           => '월',
'tue'           => '화',
'wed'           => '수',
'thu'           => '목',
'fri'           => '금',
'sat'           => '토',
'january'       => '1월',
'february'      => '2월',
'march'         => '3월',
'april'         => '4월',
'may_long'      => '5월',
'june'          => '6월',
'july'          => '7월',
'august'        => '8월',
'september'     => '9월',
'october'       => '10월',
'november'      => '11월',
'december'      => '12월',
'january-gen'   => '1월',
'february-gen'  => '2월',
'march-gen'     => '3월',
'april-gen'     => '4월',
'may-gen'       => '5월',
'june-gen'      => '6월',
'july-gen'      => '7월',
'august-gen'    => '8월',
'september-gen' => '9월',
'october-gen'   => '10월',
'november-gen'  => '11월',
'december-gen'  => '12월',
'jan'           => '1',
'feb'           => '2',
'mar'           => '3',
'apr'           => '4',
'may'           => '5',
'jun'           => '6',
'jul'           => '7',
'aug'           => '8',
'sep'           => '9',
'oct'           => '10',
'nov'           => '11',
'dec'           => '12',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|분류}}',
'category_header'                => '"$1" 분류에 속하는 문서',
'subcategories'                  => '하위 분류',
'category-media-header'          => '"$1" 분류에 속하는 자료',
'category-empty'                 => '이 분류에 속하는 문서나 자료가 없습니다.',
'hidden-categories'              => '{{PLURAL:$1|숨은 분류}}',
'hidden-category-category'       => '숨은 분류',
'category-subcat-count'          => '{{PLURAL:$2|이 분류에는 하위 분류 1개만이 속해 있습니다.|다음은 이 분류에 속하는 하위 분류 $2개 가운데 $1개입니다.}}',
'category-subcat-count-limited'  => '이 분류에 하위 분류 $1개가 있습니다.',
'category-article-count'         => '{{PLURAL:$2|이 분류에는 문서 1개만이 속해 있습니다.|다음은 이 분류에 속하는 문서 $2개 가운데 $1개입니다.}}',
'category-article-count-limited' => '이 분류에 문서 $1개가 있습니다.',
'category-file-count'            => '{{PLURAL:$2|이 분류에는 파일 1개만이 속해 있습니다.|다음은 이 분류에 속하는 파일 $2개 가운데 $1개입니다.}}',
'category-file-count-limited'    => '이 분류에 파일 $1개가 있습니다.',
'listingcontinuesabbrev'         => '(계속)',
'index-category'                 => '색인된 문서',
'noindex-category'               => '색인에서 제외되는 문서',
'broken-file-category'           => '잘못된 파일 링크가 포함된 문서',

'about'         => '소개',
'article'       => '문서 내용',
'newwindow'     => '(새 창으로 열림)',
'cancel'        => '취소',
'moredotdotdot' => '더 보기...',
'mypage'        => '문서',
'mytalk'        => '토론',
'anontalk'      => '익명 사용자 토론',
'navigation'    => '둘러보기',
'and'           => ',',

# Cologne Blue skin
'qbfind'         => '찾기',
'qbbrowse'       => '탐색',
'qbedit'         => '편집',
'qbpageoptions'  => '문서 기능',
'qbpageinfo'     => '문서 정보',
'qbmyoptions'    => '내 사용자 문서',
'qbspecialpages' => '특수 문서',
'faq'            => '자주 묻는 질문',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => '새 주제',
'vector-action-delete'           => '삭제',
'vector-action-move'             => '이동',
'vector-action-protect'          => '보호',
'vector-action-undelete'         => '되살리기',
'vector-action-unprotect'        => '보호 설정 바꾸기',
'vector-simplesearch-preference' => '단순한 찾기 막대 사용하기 (벡터 스킨 전용)',
'vector-view-create'             => '만들기',
'vector-view-edit'               => '편집',
'vector-view-history'            => '역사',
'vector-view-view'               => '읽기',
'vector-view-viewsource'         => '내용 보기',
'actions'                        => '행위',
'namespaces'                     => '이름공간',
'variants'                       => '변수',

'errorpagetitle'    => '오류',
'returnto'          => '$1(으)로 돌아갑니다.',
'tagline'           => '{{SITENAME}}',
'help'              => '도움말',
'search'            => '찾기',
'searchbutton'      => '찾기',
'go'                => '가기',
'searcharticle'     => '가기',
'history'           => '문서 역사',
'history_short'     => '역사',
'updatedmarker'     => '마지막으로 방문한 뒤 바뀜',
'printableversion'  => '인쇄용 문서',
'permalink'         => '고유링크',
'print'             => '인쇄',
'view'              => '보기',
'edit'              => '편집',
'create'            => '만들기',
'editthispage'      => '이 문서 편집하기',
'create-this-page'  => '이 문서 만들기',
'delete'            => '삭제',
'deletethispage'    => '이 문서 삭제하기',
'undelete_short'    => '편집 $1개 되살리기',
'viewdeleted_short' => '삭제된 편집 $1개 보기',
'protect'           => '보호',
'protect_change'    => '보호 수준 바꾸기',
'protectthispage'   => '이 문서 보호하기',
'unprotect'         => '보호 설정 바꾸기',
'unprotectthispage' => '이 문서의 보호 설정을 바꾸기',
'newpage'           => '새 문서',
'talkpage'          => '토론 문서',
'talkpagelinktext'  => '토론',
'specialpage'       => '특수 문서',
'personaltools'     => '개인 도구',
'postcomment'       => '새 주제',
'articlepage'       => '문서 보기',
'talk'              => '토론',
'views'             => '보기',
'toolbox'           => '도구모음',
'userpage'          => '사용자 문서 보기',
'projectpage'       => '프로젝트 문서 보기',
'imagepage'         => '파일 문서 보기',
'mediawikipage'     => '메시지 문서 보기',
'templatepage'      => '틀 문서 보기',
'viewhelppage'      => '도움말 문서 보기',
'categorypage'      => '분류 문서 보기',
'viewtalkpage'      => '토론 보기',
'otherlanguages'    => '다른 언어',
'redirectedfrom'    => '($1에서 넘어옴)',
'redirectpagesub'   => '넘겨주기 문서',
'lastmodifiedat'    => '이 문서는 $1 $2에 마지막으로 바뀌었습니다.',
'viewcount'         => '이 문서는 $1번 읽혔습니다.',
'protectedpage'     => '보호된 문서',
'jumpto'            => '이동:',
'jumptonavigation'  => '둘러보기',
'jumptosearch'      => '찾기',
'view-pool-error'   => '서버가 과부하에 걸렸습니다.
너무 많은 사용자가 이 문서를 보려고 하고 있습니다.
이 문서를 다시 열기 전에 잠시만 기다려주세요.

$1',
'pool-timeout'      => '잠금 대기 중 타임아웃',
'pool-queuefull'    => '풀 큐가 가득 찼습니다.',
'pool-errorunknown' => '알 수 없는 오류',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} 소개',
'aboutpage'            => 'Project:소개',
'copyright'            => '모든 문서는 $1 라이선스를 따릅니다.',
'copyrightpage'        => '{{ns:project}}:저작권',
'currentevents'        => '요즘 화제',
'currentevents-url'    => 'Project:요즘 화제',
'disclaimers'          => '면책 조항',
'disclaimerpage'       => 'Project:면책 조항',
'edithelp'             => '편집 도움말',
'edithelppage'         => 'Help:편집하기',
'helppage'             => 'Help:목차',
'mainpage'             => '대문',
'mainpage-description' => '대문',
'policy-url'           => 'Project:정책',
'portal'               => '사용자 모임',
'portal-url'           => 'Project:사용자 모임',
'privacy'              => '개인정보 정책',
'privacypage'          => 'Project:개인정보 정책',

'badaccess'        => '권한 오류',
'badaccess-group0' => '요청한 동작을 실행할 권한이 없습니다.',
'badaccess-groups' => '요청한 동작은 {{PLURAL:$2|다음|다음 중 하나의}} 권한을 가진 사용자에게만 가능합니다: $1.',

'versionrequired'     => '미디어위키 $1 버전 필요',
'versionrequiredtext' => '이 문서를 사용하려면 $1 버전 미디어위키가 필요합니다.
[[Special:Version|설치된 미디어위키 버전]]을 참고하세요.',

'ok'                      => '확인',
'retrievedfrom'           => '원본 주소 "$1"',
'youhavenewmessages'      => '다른 사용자가 $1란에 글을 남겼습니다. ($2)',
'newmessageslink'         => '사용자 토론',
'newmessagesdifflink'     => '마지막 바뀐 내용',
'youhavenewmessagesmulti' => '다른 사용자가 $1란에 글을 남겼습니다.',
'editsection'             => '편집',
'editold'                 => '편집',
'viewsourceold'           => '내용 보기',
'editlink'                => '편집',
'viewsourcelink'          => '내용 보기',
'editsectionhint'         => '부분 편집: $1',
'toc'                     => '목차',
'showtoc'                 => '보이기',
'hidetoc'                 => '숨기기',
'collapsible-collapse'    => '숨기기',
'collapsible-expand'      => '보이기',
'thisisdeleted'           => '$1을 보거나 되살리겠습니까?',
'viewdeleted'             => '$1을 보겠습니까?',
'restorelink'             => '삭제된 편집 $1개',
'feedlinks'               => '피드:',
'feed-invalid'            => '잘못된 구독 피드 방식입니다.',
'feed-unavailable'        => '피드 서비스는 제공하지 않습니다',
'site-rss-feed'           => '$1 RSS 피드',
'site-atom-feed'          => '$1 Atom 피드',
'page-rss-feed'           => '"$1" RSS 피드',
'page-atom-feed'          => '"$1" Atom 피드',
'red-link-title'          => '$1 (없는 문서)',
'sort-descending'         => '내림차순 정렬',
'sort-ascending'          => '오름차순 정렬',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => '문서',
'nstab-user'      => '사용자 문서',
'nstab-media'     => '자료',
'nstab-special'   => '특수 문서',
'nstab-project'   => '프로젝트 문서',
'nstab-image'     => '파일',
'nstab-mediawiki' => '메시지',
'nstab-template'  => '틀',
'nstab-help'      => '도움말',
'nstab-category'  => '분류',

# Main script and global functions
'nosuchaction'      => '해당하는 동작이 없습니다.',
'nosuchactiontext'  => 'URL로 요청한 동작이 잘못되었습니다.
URL을 잘못 입력하였거나, 잘못된 링크를 따라갔을 수 있습니다.
{{SITENAME}}의 버그일 수도 있습니다.',
'nosuchspecialpage' => '해당하는 특수 문서가 없습니다.',
'nospecialpagetext' => '<strong>요청한 특수 문서가 존재하지 않습니다.</strong>

특수 문서의 목록은 [[Special:SpecialPages|여기]]에서 볼 수 있습니다.',

# General errors
'error'                => '오류',
'databaseerror'        => '데이터베이스 오류',
'dberrortext'          => '데이터베이스 쿼리 구문 오류가 발생했습니다.
소프트웨어의 버그가 있을 수 있습니다.
마지막으로 요청한 데이터베이스 쿼리는 "<code>$2</code>" 함수에서 쓰인
<blockquote><code>$1</code></blockquote>
입니다.
데이터베이스는 "<samp>$3: $4</samp>" 오류를 일으켰습니다.',
'dberrortextcl'        => '데이터베이스 쿼리 구문 오류가 발생했습니다.
마지막으로 요청한 데이터베이스 쿼리는 "$2" 함수에서 쓰인
"$1"
입니다.
데이터베이스는 "$3: $4" 오류를 일으켰습니다.',
'laggedslavemode'      => "'''경고:''' 문서가 최근에 바뀐 내용이 아닐 수도 있습니다.",
'readonly'             => '데이터베이스 잠김',
'enterlockreason'      => '데이터베이스를 잠그는 이유와 예상되는 기간을 적어 주세요.',
'readonlytext'         => '데이터베이스가 잠겨 있어서 문서를 편집할 수 없습니다. 데이터베이스 관리가 끝난 후에는 정상으로 돌아올 것입니다.

관리자가 데이터베이스를 잠글 때 남긴 메시지는 다음과 같습니다: $1',
'missing-article'      => '데이터베이스에서 "$1" 문서의 $2 텍스트를 찾지 못했습니다.

삭제된 문서의 역사/비교 문서를 보려고 시도할 때 이러한 문제가 발생할 수 있습니다.

또는, 프로그램 버그가 발생했을 수도 있습니다. [[Special:ListUsers/sysop|관리자]]에게 오류가 나는 URL을 알려주세요.',
'missingarticle-rev'   => '(판번호: $1)',
'missingarticle-diff'  => '(차이: $1, $2)',
'readonly_lag'         => '슬레이브 데이터베이스가 마스터 서버의 자료를 새로 고치는 중입니다. 데이터베이스가 자동으로 잠겨 있습니다.',
'internalerror'        => '내부 오류',
'internalerror_info'   => '내부 오류: $1',
'fileappenderrorread'  => '내용을 덧붙이다가 "$1" 파일을 읽을 수 없습니다.',
'fileappenderror'      => '"$1" 파일을 "$2"에 덧붙일 수 없습니다.',
'filecopyerror'        => '"$1" 파일을 "$2"로 복사할 수 없습니다.',
'filerenameerror'      => '"$1" 파일을 "$2"로 옮길 수 없습니다.',
'filedeleteerror'      => '"$1" 파일을 삭제할 수 없습니다.',
'directorycreateerror' => '"$1" 디렉토리를 만들 수 없습니다.',
'filenotfound'         => '"$1" 파일을 찾을 수 없습니다.',
'fileexistserror'      => '"$1" 파일이 이미 있어 여기에 쓸 수 없습니다.',
'unexpected'           => '예상되지 않은 값: "$1"="$2"',
'formerror'            => '오류: 양식을 제출할 수 없습니다.',
'badarticleerror'      => '지금의 명령은 이 문서에서는 실행할 수 없습니다.',
'cannotdelete'         => '"$1" 문서나 파일을 삭제할 수 없습니다.
이미 삭제되었을 수도 있습니다.',
'cannotdelete-title'   => '"$1" 문서를 삭제할 수 없습니다.',
'badtitle'             => '잘못된 제목',
'badtitletext'         => '문서 제목이 잘못되었거나 비어있습니다. 또는 잘못된 인터위키 제목으로 링크했습니다.
문서 제목에 사용할 수 없는 문자를 사용했을 수 있습니다.',
'perfcached'           => '다음 자료는 캐시된 것이므로 현재 상황을 반영하지 않을 수 있습니다. 캐시에 최대 {{PLURAL:$1|결과 $1개}}가 있습니다.',
'perfcachedts'         => '다음 자료는 캐시된 것으로, $1에 마지막으로 새로 고쳐졌습니다.  캐시에 최대 {{PLURAL:$4|결과 $4개}}가 있습니다.',
'querypage-no-updates' => '이 문서의 새로 고침이 현재 비활성화되어 있습니다.
자료가 잠시 새로 고치지 않을 것입니다.',
'wrong_wfQuery_params' => 'wfQuery()에서 잘못된 매개변수 발생<br />
함수: $1<br />
쿼리: $2',
'viewsource'           => '내용 보기',
'viewsource-title'     => '$1 문서 내용 보기',
'actionthrottled'      => '동작 중지',
'actionthrottledtext'  => '스팸을 막기 위해 짧은 시간 안에 이 작업을 너무 많이 하는 것을 막고 있습니다.
제한을 넘었으니 몇 분 뒤에 새로 시도하세요.',
'protectedpagetext'    => '이 문서는 편집할 수 없도록 보호되어 있습니다.',
'viewsourcetext'       => '문서의 원본을 보거나 복사할 수 있습니다:',
'viewyourtext'         => "이 문서에 남긴 '''내 편집''' 내용을 보거나 복사할 수 있습니다:",
'protectedinterface'   => '이 문서는 이 위키의 소프트웨어 인터페이스에 쓰이는 문서로, 부정 행위를 막기 위해 보호되어 있습니다.
모든 위키에 대한 번역을 추가하거나 바꾸려면 미디어위키 지역화 프로젝트인 [//translatewiki.net/wiki/Main_Page?setlang=ko translatewiki.net]에 참여하시기 바랍니다.',
'editinginterface'     => "'''경고''': 소프트웨어 인터페이스에 쓰이는 문서를 고치고 있습니다.
이 문서에 있는 내용을 바꾸면 이 위키에 있는 모든 사용자에게 영향을 끼칩니다.
모든 위키에 대한 번역을 추가하거나 바꾸려면 미디어위키 지역화 프로젝트인 [//translatewiki.net/wiki/Main_Page?setlang=ko translatewiki.net]에 참여하시기 바랍니다.",
'sqlhidden'            => '(SQL 쿼리 숨겨짐)',
'cascadeprotected'     => '이 문서는 다음 "연쇄적" 보호가 걸린 {{PLURAL:$1|문서}}에 포함되어 있어 함께 보호됩니다:
$2',
'namespaceprotected'   => "'''$1''' 이름공간을 편집할 수 있는 권한이 없습니다.",
'customcssprotected'   => '여기에는 다른 사용자의 개인 설정이 포함되어 있기 때문에 이 CSS 문서를 편집할 수 없습니다.',
'customjsprotected'    => '여기에는 다른 사용자의 개인 설정이 포함되어 있기 때문에 이 자바스크립트 문서를 편집할 수 없습니다.',
'ns-specialprotected'  => '특수 문서는 편집할 수 없습니다.',
'titleprotected'       => '[[User:$1|$1]] 사용자가 문서 만들기를 금지했습니다.
이유는 다음과 같습니다. "$2"',

# Virus scanner
'virus-badscanner'     => "잘못된 설정: 알 수 없는 바이러스 검사기: '''$1'''",
'virus-scanfailed'     => '검사 실패 (코드 $1)',
'virus-unknownscanner' => '알려지지 않은 백신:',

# Login and logout pages
'logouttext'                 => "'''{{SITENAME}}에서 로그아웃했습니다.'''

이대로 이름 없이 {{SITENAME}}을(를) 이용하거나, 방금 사용했던 계정이나 다른 계정으로 다시 [[Special:UserLogin|로그인]]해서 이용할 수 있습니다.
웹 브라우저의 캐시를 지우지 않으면 몇몇 문서에서 로그인이 되어 있는 것처럼 보일 수 있다는 점을 유의해 주세요.",
'welcomecreation'            => '== $1, 환영합니다! ==
계정이 만들어졌습니다.
[[Special:Preferences|{{SITENAME}} 사용자 환경 설정]]을 바꿀 수 있습니다.',
'yourname'                   => '사용자 이름:',
'yourpassword'               => '비밀번호:',
'yourpasswordagain'          => '비밀번호 다시 입력:',
'remembermypassword'         => '이 컴퓨터에서 로그인 상태를 저장하기 (최대 $1일)',
'securelogin-stick-https'    => '로그인 후에도 HTTPS 연결 상태를 유지합니다',
'yourdomainname'             => '도메인 이름:',
'externaldberror'            => '바깥 인증 데이터베이스에 오류가 있거나 바깥 계정을 새로 고칠 권한이 없습니다.',
'login'                      => '로그인',
'nav-login-createaccount'    => '로그인 / 계정 만들기',
'loginprompt'                => '{{SITENAME}}에 로그인하려면 쿠키를 사용할 수 있어야 합니다.',
'userlogin'                  => '로그인 / 계정 만들기',
'userloginnocreate'          => '로그인',
'logout'                     => '로그아웃',
'userlogout'                 => '로그아웃',
'notloggedin'                => '로그인하지 않음',
'nologin'                    => '계정이 없나요? $1.',
'nologinlink'                => '계정을 만들 수 있습니다',
'createaccount'              => '계정 만들기',
'gotaccount'                 => "계정이 이미 있다면, '''$1'''.",
'gotaccountlink'             => '로그인하세요',
'userlogin-resetlink'        => '사용자 이름이나 비밀번호를 잊으셨나요?',
'createaccountmail'          => '이메일로 보내기',
'createaccountreason'        => '이유:',
'badretype'                  => '입력한 비밀번호가 서로 다릅니다.',
'userexists'                 => '입력하신 사용자 이름이 이미 등록되어 있습니다.
다른 이름을 선택하세요.',
'loginerror'                 => '로그인 오류',
'createaccounterror'         => '계정을 만들지 못했습니다: $1',
'nocookiesnew'               => '사용자 계정을 만들었지만, 아직 로그인하지 않았습니다.
{{SITENAME}}에서는 로그인 정보를 저장하기 위해 쿠키를 사용합니다.
지금 사용하는 웹 브라우저는 쿠키를 사용하지 않도록 설정되어 있습니다.
로그인하기 전에 웹 브라우저에서 쿠키를 사용하도록 설정해주세요.',
'nocookieslogin'             => '{{SITENAME}}에서는 로그인을 위해 쿠키를 사용합니다.
쿠키가 비활성되어 있습니다.
쿠키 사용을 활성화한 다음 다시 시도하세요.',
'nocookiesfornew'            => '요청의 출처를 확인할 수 없기 때문에 사용자 계정이 만들어지지 않았습니다.
쿠키를 허용한 것을 확인한 후에 이 문서를 새로 고치고 나서 다시 시도하세요.',
'noname'                     => '사용자 이름이 올바르지 않습니다.',
'loginsuccesstitle'          => '로그인 성공',
'loginsuccess'               => "'''{{SITENAME}}에 \"\$1\" 계정으로 로그인했습니다.'''",
'nosuchuser'                 => '"$1" 사용자가 존재하지 않습니다.
사용자 이름은 대소문자를 구별합니다. 철자가 맞는지 확인해주세요.
[[Special:UserLogin/signup|새 계정을 만들 수도 있습니다]].',
'nosuchusershort'            => '이름이 "$1"인 사용자는 없습니다.
철자가 맞는지 확인하세요.',
'nouserspecified'            => '사용자 이름을 입력하지 않았습니다.',
'login-userblocked'          => '이 사용자는 차단되었습니다. 로그인할 수 없습니다.',
'wrongpassword'              => '입력한 비밀번호가 다릅니다.
다시 시도해 주세요.',
'wrongpasswordempty'         => '비밀번호를 입력하지 않았습니다.
다시 시도해 주세요.',
'passwordtooshort'           => '비밀번호는 $1 문자 이상이어야 합니다.',
'password-name-match'        => '비밀번호는 사용자 이름과 반드시 달라야 합니다.',
'password-login-forbidden'   => '이 사용자 이름과 비밀번호는 사용할 수 없습니다.',
'mailmypassword'             => '새 비밀번호를 이메일로 보내기',
'passwordremindertitle'      => '{{SITENAME}}의 새 임시 비밀번호',
'passwordremindertext'       => '$1 IP 주소에서 누군가가 아마 자신이 {{SITENAME}} ($4)의 새 비밀번호를 요청했습니다.
"$2" 사용자의 임시 비밀번호는 "$3"로 설정되었습니다. 이것이 자신이 의도한 바라면
지금 로그인하여 새로운 비밀번호를 만드세요.
임시 비밀번호는 $5일 후에 만료됩니다.

이 요청을 다른 사람이 했거나 이전 비밀번호를 기억해 내서 바꿀 필요가 없으면
이 메시지를 무시하고 이전 비밀번호를 계속 사용할 수 있습니다.',
'noemail'                    => '"$1" 사용자는 이메일 주소를 등록하지 않았습니다.',
'noemailcreate'              => '바른 이메일 주소를 제공해야 합니다.',
'passwordsent'               => '"$1" 계정의 새로운 비밀번호를 이메일로 보냈습니다.
비밀번호를 받고 다시 로그인해 주세요.',
'blocked-mailpassword'       => '당신의 IP 주소는 편집을 할 수 없게 차단되어 있어서 악용하지 못하도록 비밀번호 되살리기 기능 사용이 금지됩니다.',
'eauthentsent'               => '입력한 이메일로 확인 이메일을 보냈습니다.
게정에서 다른 이메일로 보내기 전에 이메일 내용의 지시대로 계정 확인 절차를 실행해 주십시오.',
'throttled-mailpassword'     => '비밀번호 확인 이메일을 이미 최근 $1시간 안에 보냈습니다.
악용을 방지하기 위해 비밀번호 확인 메일은 $1시간마다 오직 하나씩만 보낼 수 있습니다.',
'mailerror'                  => '메일 보내기 오류: $1',
'acct_creation_throttle_hit' => '당신의 IP 주소를 이용한 방문자가 이전에 이미 계정을 $1개 만들어, 계정 만들기 한도를 초과하였습니다.
따라서 지금은 이 IP 주소로는 더 이상 계정을 만들 수 없습니다.',
'emailauthenticated'         => '이메일 주소는 $2 $3에 인증되었습니다.',
'emailnotauthenticated'      => '이메일 주소를 인증하지 않았습니다.
이메일 확인 절차를 거치지 않으면 다음 이메일 기능을 사용할 수 없습니다.',
'noemailprefs'               => '이 기능을 사용하기 위해서는 사용자 환경 설정에서 이메일 주소를 설정해야 합니다.',
'emailconfirmlink'           => '이메일 주소 확인',
'invalidemailaddress'        => '이메일 주소의 형식이 잘못되어 인식할 수 없습니다.
정상적인 형식의 이메일을 입력하거나 칸을 비워 주세요.',
'cannotchangeemail'          => '이 위키에서는 계정의 이메일 주소를 바꿀 수 없습니다.',
'accountcreated'             => '계정 만들어짐',
'accountcreatedtext'         => '"$1" 사용자 계정이 만들어졌습니다.',
'createaccount-title'        => '{{SITENAME}} 계정 만들기',
'createaccount-text'         => '누군가가 {{SITENAME}} ($4)에서 사용자 이름 "$2", 비밀번호 "$3"로 당신의 이메일 주소가 등록된 계정을 만들었습니다. 
지금 로그인하여 비밀번호를 바꾸십시오.

실수로 계정을 잘못 만들었다면 이 메시지는 무시해도 됩니다.',
'usernamehasherror'          => '사용자 이름에는 해시 문자가 들어갈 수 없습니다.',
'login-throttled'            => '로그인에 연속으로 실패하였습니다.
잠시 후에 다시 시도해주세요.',
'login-abort-generic'        => '로그인에 실패했습니다 - 중지됨',
'loginlanguagelabel'         => '언어: $1',
'suspicious-userlogout'      => '브라우저에 이상이 있거나 캐싱 프록시에서 로그아웃을 요청했기 때문에 로그아웃이 거부되었습니다.',

# E-mail sending
'php-mail-error-unknown' => 'PHP의 mail() 함수에서 알 수 없는 오류가 발생했습니다.',
'user-mail-no-addy'      => '받는이의 이메일 주소가 없으면 이메일을 보낼 수 없습니다.',

# Change password dialog
'resetpass'                 => '비밀번호 바꾸기',
'resetpass_announce'        => '이메일로 받은 임시 비밀번호로 로그인했습니다.
로그인을 마치려면 새 비밀번호를 여기에서 설정해야 합니다:',
'resetpass_text'            => '<!-- 여기에 텍스트를 추가하세요 -->',
'resetpass_header'          => '비밀번호 바꾸기',
'oldpassword'               => '이전 비밀번호:',
'newpassword'               => '새 비밀번호:',
'retypenew'                 => '새 비밀번호 재입력:',
'resetpass_submit'          => '비밀번호를 설정하고 로그인하기',
'resetpass_success'         => '비밀번호를 성공적으로 바꿨습니다!
이제 로그인을 합니다...',
'resetpass_forbidden'       => '비밀번호를 바꿀 수 없음',
'resetpass-no-info'         => '이 특수 문서에 직접 접근하려면 반드시 로그인해야 합니다.',
'resetpass-submit-loggedin' => '비밀번호 바꾸기',
'resetpass-submit-cancel'   => '취소',
'resetpass-wrong-oldpass'   => '비밀번호가 잘못되었거나 현재의 비밀번호와 같습니다.
이미 비밀번호를 성공적으로 바꾸었거나 새 임시 비밀번호를 요청했을 수 있습니다.',
'resetpass-temp-password'   => '임시 비밀번호:',

# Special:PasswordReset
'passwordreset'                    => '비밀번호 재설정',
'passwordreset-text'               => '이메일을 통해 계정 정보를 받을 수 있습니다. 아래의 칸을 채워주세요.',
'passwordreset-legend'             => '비밀번호 재설정',
'passwordreset-disabled'           => '이 위키에서는 비밀번호를 재설정할 수 없습니다.',
'passwordreset-pretext'            => '{{PLURAL:$1||아래에 한 가지 정보를 입력하세요}}',
'passwordreset-username'           => '사용자 이름:',
'passwordreset-domain'             => '도메인:',
'passwordreset-capture'            => '발송 결과 이메일을 보시겠습니까?',
'passwordreset-capture-help'       => '이 상자에 체크하면 이메일이 발송된 즉시 임시 비밀번호가 담긴 이메일을 볼 수 있습니다.',
'passwordreset-email'              => '이메일 주소:',
'passwordreset-emailtitle'         => '{{SITENAME}} 계정 자세한 정보',
'passwordreset-emailtext-ip'       => 'IP 주소 $1을 사용하는 누군가(아마도 당신이), {{SITENAME}} ($4)의 비밀번호 찾기를 요청하였습니다.
이 이메일 주소와 연관된 계정의 목록입니다:

$2

이 {{PLURAL:$3|임시 비밀번호}}는 $5일 후에 만료됩니다.
이 비밀번호로 로그인한 후 비밀번호를 바꾸십시오. 만약 당신이 아닌 다른 사람이 요청하였거나,
원래의 비밀번호를 기억해냈다면, 이 메시지를 무시하고 이전의 비밀번호를 계속 사용할 수 있습니다.',
'passwordreset-emailtext-user'     => '{{SITENAME}} ($4)의 사용자 $1이 비밀번호 찾기를 요청하였습니다.
이 이메일 주소와 연관된 계정의 목록입니다:

$2

이 {{PLURAL:$3|임시 비밀번호}}는 $5일 후에 만료됩니다.
이 비밀번호로 로그인한 후 비밀번호를 바꾸십시오. 만약 당신이 아닌 다른 사람이 요청하였거나,
원래의 비밀번호를 기억해냈다면, 이 메시지를 무시하고 이전의 비밀번호를 계속 사용할 수 있습니다.',
'passwordreset-emailelement'       => '사용자 이름: $1
임시 비밀번호: $2',
'passwordreset-emailsent'          => '비밀번호 찾기 이메일을 보냈습니다.',
'passwordreset-emailsent-capture'  => '비밀번호 찾기 이메일이 발송되었으며, 아래에 나타나 있습니다.',
'passwordreset-emailerror-capture' => '비밀번호 찾기 이메일이 만들어져 아래에 나타났지만 발송하는 데에는 실패했습니다: $1',

# Special:ChangeEmail
'changeemail'          => '이메일 주소 바꾸기',
'changeemail-header'   => '계정 메일 주소 바꾸기',
'changeemail-text'     => '이메일 주소를 바꾸려면 이 양식을 채우세요. 바뀜 내용을 확인하기 위해 비밀번호를 입력해야 합니다.',
'changeemail-no-info'  => '이 특수 문서에 직접 접근하려면 반드시 로그인해야 합니다.',
'changeemail-oldemail' => '현재 이메일 주소 :',
'changeemail-newemail' => '새 이메일 주소:',
'changeemail-none'     => '(없음)',
'changeemail-submit'   => '이메일 주소 바꾸기',
'changeemail-cancel'   => '취소',

# Edit page toolbar
'bold_sample'     => '굵은 글씨',
'bold_tip'        => '굵은 글씨',
'italic_sample'   => '기울인 글씨',
'italic_tip'      => '기울인 글씨',
'link_sample'     => '링크 제목',
'link_tip'        => '안쪽 링크',
'extlink_sample'  => 'http://www.example.com 사이트 이름',
'extlink_tip'     => '바깥 링크 (주소 앞에 http://가 있어야 합니다.)',
'headline_sample' => '제목',
'headline_tip'    => '2단계 문단 제목',
'nowiki_sample'   => '여기에 위키 문법을 사용하지 않을 글을 적어 주세요',
'nowiki_tip'      => '위키 문법 사용하지 않기',
'image_tip'       => '파일 넣기',
'media_tip'       => '파일 링크하기',
'sig_tip'         => '내 서명과 현재 시각',
'hr_tip'          => '가로 줄 (되도록 사용하지 말아 주세요)',

# Edit pages
'summary'                          => '요약:',
'subject'                          => '주제/제목:',
'minoredit'                        => '사소한 편집',
'watchthis'                        => '이 문서 주시하기',
'savearticle'                      => '저장',
'preview'                          => '미리 보기',
'showpreview'                      => '미리 보기',
'showlivepreview'                  => '실시간 미리 보기',
'showdiff'                         => '차이 보기',
'anoneditwarning'                  => "'''경고''': 로그인하고 있지 않습니다.
당신의 IP 주소가 문서 역사에 남게 됩니다.",
'anonpreviewwarning'               => "'''로그인하고 있지 않습니다. 문서를 저장하면 당신의 IP 주소가 문서 역사에 남게 됩니다.'''",
'missingsummary'                   => "'''알림:''' 편집 요약을 적지 않았습니다.
이대로 \"{{int:savearticle}}\"을 클릭하면 편집 요약 없이 저장됩니다.",
'missingcommenttext'               => '아래에 내용을 채워 넣어 주세요.',
'missingcommentheader'             => "'''알림:''' 글의 제목을 입력하지 않았습니다.
다시 \"{{int:savearticle}}\" 버튼을 클릭하면 글이 제목 없이 저장됩니다.",
'summary-preview'                  => '요약 미리 보기:',
'subject-preview'                  => '주제/제목 미리 보기:',
'blockedtitle'                     => '차단됨',
'blockedtext'                      => "'''사용자 계정 또는 IP 주소가 차단되었습니다.'''

차단한 사람은 $1입니다.
차단한 이유는 다음과 같습니다: $2

* 차단이 시작된 시간: $8
* 차단이 끝나는 시간: $6
* 차단된 사용자: $7

$1 또는 [[{{MediaWiki:Grouppage-sysop}}|다른 관리자]]에게 차단에 대해 문의할 수 있습니다.
[[Special:Preferences|계정 환경 설정]]에 올바른 이메일 주소가 있어야만 '이메일 보내기' 기능을 사용할 수 있습니다. 또 이메일 보내기 기능이 차단되어 있으면 이메일을 보낼 수 없습니다.
지금 당신의 IP 주소는 $3이고, 차단 ID는 #$5입니다.
문의할 때에 이 정보를 같이 알려주세요.",
'autoblockedtext'                  => "당신의 IP 주소는 $1이 차단한 사용자가 사용했던 IP이기 때문에 자동으로 차단되었습니다. 차단된 이유는 다음과 같습니다:

:$2

* 차단이 시작된 시간: $8
* 차단이 끝나는 시간: $6
* 차단된 사용자: $7

$1 또는 [[{{MediaWiki:Grouppage-sysop}}|다른 관리자]]에게 차단에 대해 문의할 수 있습니다.

[[Special:Preferences|계정 환경 설정]]에 올바른 이메일 주소가 있어야만 '이메일 보내기' 기능을 사용할 수 있습니다. 또한 이메일 보내기 기능이 차단되어 있으면 이메일을 보낼 수 없습니다.

당신의 현재 IP 주소는 $3이고, 차단 ID는 #$5입니다.
문의할 때에 이 정보를 같이 알려주세요.",
'blockednoreason'                  => '이유를 입력하지 않음',
'whitelistedittext'                => '문서를 편집하려면 $1해야 합니다.',
'confirmedittext'                  => '문서를 고치려면 이메일 인증 절차가 필요합니다.
[[Special:Preferences|사용자 환경 설정]]에서 이메일 주소를 입력하고 이메일 주소 인증을 해주시기 바랍니다.',
'nosuchsectiontitle'               => '문단을 찾을 수 없음',
'nosuchsectiontext'                => '존재하지 않는 문단을 편집하려 했습니다.
이 문서를 보는 동안 문단이 이동되었거나 삭제되었을 수 있습니다.',
'loginreqtitle'                    => '로그인 필요',
'loginreqlink'                     => '로그인',
'loginreqpagetext'                 => '다른 문서를 보기 위해서는 $1해야 합니다.',
'accmailtitle'                     => '비밀번호를 보냈습니다.',
'accmailtext'                      => '[[User talk:$1|$1]] 사용자의 비밀번호가 임의로 만들어져 $2로 전송되었습니다.

새 비밀번호는 로그인한 후 [[Special:ChangePassword|비밀번호를 바꿀]] 수 있습니다.',
'newarticle'                       => '(새 문서)',
'newarticletext'                   => "이 문서는 아직 만들어지지 않았습니다.
새 문서를 만들려면 아래의 상자에 문서 내용을 입력하면 됩니다(자세한 내용은 [[{{MediaWiki:Helppage}}|도움말]]을 읽어 주세요).
만약 잘못 찾아온 문서라면 웹 브라우저의 '''뒤로''' 버튼을 눌러 주세요.",
'anontalkpagetext'                 => '----
여기는 계정을 만들지 않았거나 사용하고 있지 않은 익명 사용자를 위한 토론 문서입니다.
익명 사용자를 구별하기 위해서는 숫자로 된 IP 주소를 사용해야만 합니다.
IP 주소는 여러 사용자가 공유할 수 있습니다.
자신과 관계없는 의견이 자신에게 남겨져 있어 불쾌하다고 생각하는 익명 사용자는 [[Special:UserLogin/signup|계정을 만들고]] [[Special:UserLogin|로그인 하여]] 나중에 다른 익명 사용자에게 줄 혼란을 줄일 수 있습니다.',
'noarticletext'                    => '이 문서가 현재 존재하지 않습니다.
이 문서와 제목이 비슷한 문서가 있는지 [[Special:Search/{{PAGENAME}}|찾거나]],
이 문서에 관련된 <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 기록]을 확인하거나,
문서를 직접 [{{fullurl:{{FULLPAGENAME}}|action=edit}} 편집]</span>할 수 있습니다.',
'noarticletext-nopermission'       => '이 문서가 존재하지 않습니다.
이 문서와 제목이 비슷한 문서가 있는지 [[Special:Search/{{PAGENAME}}|찾거나]],
이 문서에 관련된 <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 기록]을 확인할 수 있습니다.</span>',
'userpage-userdoesnotexist'        => '"$1" 계정은 등록되어 있지 않습니다.
이 문서를 만들거나 편집하려면 계정이 존재 하는지 확인해주세요.',
'userpage-userdoesnotexist-view'   => '"$1" 사용자 계정은 등록되지 않았습니다.',
'blocked-notice-logextract'        => '이 사용자는 현재 차단되어 있습니다.
해당 사용자의 최근 차단 기록을 참고하십시오:',
'clearyourcache'                   => "'''참고:''' 설정을 저장한 후에 바뀐 점을 확인하기 위해서는 브라우저의 캐시를 새로 고쳐야 합니다.
* '''파이어폭스 / 사파리''': ''Shift'' 키를 누르면서 새로 고침을 클릭하거나, ''Ctrl-F5'' 또는 ''Ctrl-R'' 을 입력 (Mac에서는 ''⌘-R'')
* '''구글 크롬''': ''Ctrl-Shift-R''키를 입력 (Mac에서는 ''⌘-Shift-R'')
* '''인터넷 익스플로러''': ''Ctrl'' 키를 누르면서 새로 고침을 클릭하거나, ''Ctrl-F5''를 입력.
* '''오페라''': ''도구→설정''에서 캐시를 비움",
'usercssyoucanpreview'             => "'''안내''': CSS 문서를 저장하기 전에 \"{{int:showpreview}}\" 기능을 통해 작동을 확인해주세요.",
'userjsyoucanpreview'              => "'''안내''': 자바스크립트 문서를 저장하기 전에 \"{{int:showpreview}}\" 기능을 통해 작동을 확인해주세요.",
'usercsspreview'                   => "'''사용자 CSS의 미리 보기입니다.'''
'''아직 저장하지 않았습니다!'''",
'userjspreview'                    => "'''사용자 자바스크립트 미리 보기입니다.'''
'''아직 저장하지 않았습니다!'''",
'sitecsspreview'                   => "'''이 CSS의 미리 보기일 뿐입니다.'''
'''아직 저장하지 않았습니다!'''",
'sitejspreview'                    => "'''이 자바스크립트 코드의 미리 보기일 뿐입니다.'''
'''아직 저장하지 않았습니다!'''",
'userinvalidcssjstitle'            => "'''경고''': \"\$1\" 스킨은 없습니다.
.css와 .js 문서의 제목은 {{ns:user}}:Foo/vector.css 처럼 소문자로 써야 합니다. {{ns:user}}:Foo/Vector.css 와 같이 대문자로 쓸 경우 작동하지 않습니다.",
'updated'                          => '(바뀜)',
'note'                             => "'''참고:'''",
'previewnote'                      => "'''이 화면은 미리 보기입니다.'''
편집한 내용은 아직 저장하지 않았습니다!",
'previewconflict'                  => '이 미리 보기는 저장할 때의 모습으로 위쪽 편집창의 문서를 반영합니다.',
'session_fail_preview'             => "'''세션 데이터가 없어져 편집을 저장하지 못했습니다.'''
다시 시도하세요.
다시 시도해도 되지 않으면 [[Special:UserLogout|로그아웃]]한 다음 다시 로그인하세요.",
'session_fail_preview_html'        => "'''세션 데이터가 없어져 편집을 저장하지 못했습니다.'''

{{SITENAME}}에서 HTML 입력을 허용하기 때문에, 자바스크립트 공격을 막기 위해 미리 보기는 숨겨져 있습니다.

'''적합하게 편집을 시도했다면 다시 시도하세요'''
다시 시도해도 되지 않으면 [[Special:UserLogout|로그아웃]]한 다음 다시 로그인하세요.",
'token_suffix_mismatch'            => "'''저장하려는 내용의 문장 부호가 망가져 있습니다.'''
문서 보호를 위해 해당 내용을 저장하지 않습니다.
버그가 있는 익명 프록시 서비스 등을 사용할 때 이런 문제가 발생할 수 있습니다.",
'edit_form_incomplete'             => "'''편집의 일부 내용이 서버에 전달되지 않았습니다. 편집이 손상되지 않았는지 확인하고 다시 시도해 주십시오.'''",
'editing'                          => '$1 편집하기',
'editingsection'                   => '$1 편집하기 (부분)',
'editingcomment'                   => '$1 편집하기 (덧붙이기)',
'editconflict'                     => '편집 충돌: $1',
'explainconflict'                  => "문서를 편집하는 도중에 누군가 이 문서를 고쳤습니다.
위쪽의 문서가 지금 바뀐 문서이고, 아래쪽의 문서가 당신이 편집한 문서입니다.
아래쪽의 내용을 위쪽에 적절히 합쳐 주시기 바랍니다.
\"{{int:savearticle}}\"을 누르면 '''위쪽의 편집 내역만''' 저장됩니다.",
'yourtext'                         => '당신의 편집',
'storedversion'                    => '현재 문서',
'nonunicodebrowser'                => "'''경고: 웹 브라우저가 유니코드를 완벽하게 지원하지 않습니다.'''
아스키가 아닌 문자가 16진수 코드로 나타날 수 있습니다.",
'editingold'                       => "'''경고: 지금 이전 버전의 문서를 고치고 있습니다.'''
이것을 저장하면 최근에 편집된 부분이 사라질 수 있습니다.",
'yourdiff'                         => '차이',
'copyrightwarning'                 => "{{SITENAME}}에서의 모든 기여는 $2 라이선스로 배포된다는 점을 유의해 주세요 (자세한 내용에 대해서는 $1 문서를 읽어주세요).
만약 여기에 동의하지 않는다면 문서를 저장하지 말아 주세요.<br />
또한, 직접 작성했거나 퍼블릭 도메인과 같은 자유 문서에서 가져왔다는 것을 보증해야 합니다.
'''저작권이 있는 내용을 허가 없이 저장하지 마세요!'''",
'copyrightwarning2'                => "{{SITENAME}}에서의 모든 기여는 다른 사용자가 편집, 수정, 삭제할 수 있다는 점을 유의해 주세요.
만약 여기에 동의하지 않는다면, 문서를 저장하지 말아 주세요.<br />
또한, 직접 작성했거나 퍼블릭 도메인과 같은 자유 문서에서 가져왔다는 것을 보증해야 합니다 (자세한 내용에 대해서는 $1 문서를 읽어 주세요).
'''저작권이 있는 내용을 허가 없이 저장하지 마세요!'''",
'longpageerror'                    => "'''오류: 문서의 크기가 {{PLURAL:$1|$1킬로바이트}}로 최대 크기인 {{PLURAL:$2|$2킬로바이트}}보다 큽니다.'''
저장할 수 없습니다.",
'readonlywarning'                  => "'''경고: 데이터베이스가 관리를 위해 잠겨 있습니다. 따라서 문서를 편집한 내용을 지금 저장할 수 없습니다.'''
편집 내용을 복사 붙여넣기 등을 사용하여 일단 다른 곳에 저장한 후, 나중에 다시 시도해 주세요.

잠근 관리자가 남긴 설명은 다음과 같습니다: $1",
'protectedpagewarning'             => "'''경고: 이 문서는 관리자만 편집할 수 있도록 보호되어 있습니다.'''
이 문서의 최근 기록을 참고하십시오:",
'semiprotectedpagewarning'         => "'''참고:''' 이 문서는 계정을 등록한 사용자만이 편집할 수 있도록 잠겨 있습니다.
이 문서의 최근 기록을 참고하십시오:",
'cascadeprotectedwarning'          => "'''경고''': 이 문서는 잠겨 있어 관리자만 편집할 수 있습니다. 연쇄적 보호가 걸린 다음 {{PLURAL:$1|문서}}에서 이 문서를 사용하고 있습니다:",
'titleprotectedwarning'            => "'''경고: 이 문서는 잠겨 있어, 문서를 만드려면 [[Special:ListGroupRights|특정 권한]]이 필요합니다.'''
아래 문서의 최근 기록을 참고하십시오:",
'templatesused'                    => '이 문서에서 사용한 {{PLURAL:$1|틀}}:',
'templatesusedpreview'             => '이 미리 보기에서 사용하고 있는 {{PLURAL:$1|틀}}:',
'templatesusedsection'             => '이 문단에서 사용하고 있는 {{PLURAL:$1|틀}}:',
'template-protected'               => '(보호됨)',
'template-semiprotected'           => '(준보호됨)',
'hiddencategories'                 => '이 문서는 다음 숨은 분류 $1개에 속해 있습니다:',
'edittools'                        => '<!-- 이 문서는 편집 창과 파일 올리기 창에 출력됩니다. -->',
'nocreatetitle'                    => '문서 만들기 제한',
'nocreatetext'                     => '{{SITENAME}}에서 새로운 문서를 만드는 것은 제한되어 있습니다.
이미 존재하는 다른 문서를 편집하거나, [[Special:UserLogin|로그인하거나 계정을 만들 수 있습니다]].',
'nocreate-loggedin'                => '새 문서를 만들 권한이 없습니다.',
'sectioneditnotsupported-title'    => '부분 편집 지원 안됨',
'sectioneditnotsupported-text'     => '이 문서에서는 문단 편집을 지원하지 않습니다.',
'permissionserrors'                => '권한 오류',
'permissionserrorstext'            => '해당 명령을 수행할 권한이 없습니다. 다음 {{PLURAL:$1|이유}}를 확인해보세요:',
'permissionserrorstext-withaction' => '$2 권한이 없습니다. 다음 {{PLURAL:$1|이유}}를 확인해주세요:',
'recreate-moveddeleted-warn'       => "'''경고: 삭제된 적이 있는 문서를 다시 만들고 있습니다.'''

이 문서를 계속 편집하는 것이 적합한 것인지 확인해주세요.
편의를 위해 삭제와 이동 기록을 다음과 같이 제공합니다:",
'moveddeleted-notice'              => '이 문서는 삭제되었습니다.
이 문서의 삭제 및 이동 기록은 다음과 같습니다.',
'log-fulllog'                      => '전체 기록 보기',
'edit-hook-aborted'                => '훅에 의해 편집이 중단되었습니다.
아무런 설명도 주어지지 않았습니다.',
'edit-gone-missing'                => '문서를 저장하지 못했습니다.
문서가 삭제된 것 같습니다.',
'edit-conflict'                    => '편집 충돌.',
'edit-no-change'                   => '문서에 아무런 변화가 없기 때문에 당신의 편집은 무시되었습니다.',
'edit-already-exists'              => '새 문서를 만들 수 없습니다.
그 문서는 이미 존재합니다.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''경고:''' 이 문서는 너무 많은 파서 함수를 포함하고 있습니다.

$2개 보다 적게 써야 하지만 지금은 $1개를 쓰고 있습니다.",
'expensive-parserfunction-category'       => '느린 파서 함수 호출을 너무 많이 하는 문서',
'post-expand-template-inclusion-warning'  => "'''경고:''' 틀 포함 크기가 너무 큽니다.
일부 틀은 포함되지 않을 수 있습니다.",
'post-expand-template-inclusion-category' => '사용한 틀의 크기가 지나치게 큰 문서의 목록',
'post-expand-template-argument-warning'   => "'''경고:''' 이 문서는 전개 후 크기가 너무 큰 틀 변수가 하나 이상 포함되어 있습니다.
이 변수는 생략했습니다.",
'post-expand-template-argument-category'  => '생략된 틀 변수를 포함한 문서',
'parser-template-loop-warning'            => '재귀적인 틀이 발견되었습니다: [[$1]]',
'parser-template-recursion-depth-warning' => '틀 반복 횟수 제한을 초과함($1)',
'language-converter-depth-warning'        => '언어 변환기 실행 제한 초과($1)',

# "Undo" feature
'undo-success' => '편집을 되돌릴 수 있습니다.
편집 되돌리기를 완료하려면 이 편집을 되돌리려면 아래의 바뀜 사항을 확인한 후 저장해주세요.',
'undo-failure' => '중간의 다른 편집과 충돌하여 이 편집을 되돌릴 수 없습니다.',
'undo-norev'   => '문서가 없거나 삭제되었기 때문에 편집을 되돌릴 수 없습니다.',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|토론]]) 의 $1판 편집을 되돌림',

# Account creation failure
'cantcreateaccounttitle' => '계정을 만들 수 없음',
'cantcreateaccount-text' => "현재 아이피 주소('''$1''')는 [[User:$3|$3]] 사용자에 의해 계정 만들기가 차단된 상태입니다.

차단 이유는 다음과 같습니다: $2",

# History pages
'viewpagelogs'           => '이 문서의 기록 보기',
'nohistory'              => '이 문서는 편집 역사가 없습니다.',
'currentrev'             => '최신판',
'currentrev-asof'        => '$1 기준 최신판',
'revisionasof'           => '$1 판',
'revision-info'          => '$2 사용자의 $1 판',
'previousrevision'       => '← 이전 판',
'nextrevision'           => '다음 판 →',
'currentrevisionlink'    => '최신판',
'cur'                    => '최신',
'next'                   => '다음',
'last'                   => '이전',
'page_first'             => '처음',
'page_last'              => '마지막',
'histlegend'             => "비교하려는 판을 선택한 다음 엔터나 아래의 버튼을 누르세요.<br />
설명: '''({{int:cur}})''' = 최신 판과 비교, '''({{int:last}})''' = 이전 판과 비교, '''{{int:minoreditletter}}'''= 사소한 편집",
'history-fieldset-title' => '문서의 바뀜 내역 찾기',
'history-show-deleted'   => '삭제된 것만',
'histfirst'              => '처음',
'histlast'               => '마지막',
'historysize'            => '($1 바이트)',
'historyempty'           => '(비었음)',

# Revision feed
'history-feed-title'          => '편집 역사',
'history-feed-description'    => '이 문서의 편집 역사',
'history-feed-item-nocomment' => '$2에 대한 $1의 편집',
'history-feed-empty'          => '요청한 문서가 존재하지 않습니다.
해당 문서가 삭제되었거나, 문서 이름이 바뀌었을 수 있습니다.
[[Special:Search|찾기]]를 사용해 관련 문서를 찾아보세요.',

# Revision deletion
'rev-deleted-comment'         => '(편집 요약 삭제됨)',
'rev-deleted-user'            => '(사용자 이름 삭제됨)',
'rev-deleted-event'           => '(기록 동작 삭제됨)',
'rev-deleted-user-contribs'   => '[사용자 이름 또는 IP 주소 삭제됨 -  기여 목록에서 편집이 숨겨짐]',
'rev-deleted-text-permission' => "해당 편집이 문서 역사에서 '''삭제'''되었습니다.
자세한 사항은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 볼 수 있습니다.",
'rev-deleted-text-unhide'     => "해당 편집이 문서 역사에서 '''삭제'''되었습니다.
자세한 사항은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 볼 수 있습니다.
이 편집을 보기를 원하신다면 [$1 해당 편집]을 볼 수 있습니다.",
'rev-suppressed-text-unhide'  => "해당 편집이 문서 역사에서 '''숨겨져''' 있습니다.
자세한 사항은 [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 숨김 기록]에서 볼 수 있습니다.
이 편집을 보기를 원하신다면 [$1 해당 편집]을 볼 수 있습니다.",
'rev-deleted-text-view'       => "이 문서의 편집은 역사에서 '''삭제'''되었습니다.
삭제된 편집을 볼 수 있으며 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 자세한 내용을 볼 수 있습니다.",
'rev-suppressed-text-view'    => "이 문서의 편집은 역사에서 '''숨겨져''' 있습니다.
숨겨진 편집을 볼 수 있으며 [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 숨김 기록]에서 자세한 내용을 볼 수 있습니다.",
'rev-deleted-no-diff'         => "특정 판이 문서 역사에서 '''삭제'''되었기 때문에 비교할 수 없습니다.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 자세한 내용을 볼 수 있습니다.",
'rev-suppressed-no-diff'      => "두 판 중 일부가 '''삭제'''되었기 때문에 문서 편집 내용을 비교할 수 없습니다.",
'rev-deleted-unhide-diff'     => "이 비교에 사용된 판 가운데 하나가 '''삭제'''되었습니다.
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에 자세한 내용을 찾아볼 수 있습니다.
계속 작업하고 싶다면 여전히 [$1 비교 보기]를 계속할 수 있습니다.",
'rev-suppressed-unhide-diff'  => "이 비교에 사용된 판 가운데 하나가 '''숨겨져''' 있습니다.
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 숨김 기록]에 자세한 내용이 있을 수 있습니다.
계속 작업하고 싶다면 [$1 해당 편집]을 볼 수도 있습니다.",
'rev-deleted-diff-view'       => "비교 대상 중 어느 한 판이 '''삭제'''되었습니다.
삭제된 판과 다른 판의 비교를 할 수 있습니다. 자세한 내용은 [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 삭제 기록]에서 찾아볼 수 있습니다.",
'rev-suppressed-diff-view'    => "비교하려는 판 중 일부가 '''숨겨져''' 있습니다.
숨겨진 판과 이 판의 편집 비교를 할 수 있습니다. 자세한 내용은 [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 해당 숨김 기록]에서 찾아볼 수 있습니다.",
'rev-delundel'                => '보이기/숨기기',
'rev-showdeleted'             => '보이기',
'revisiondelete'              => '판 삭제/복구',
'revdelete-nooldid-title'     => '대상 판이 잘못되었습니다.',
'revdelete-nooldid-text'      => '이 기능을 수행할 특정 판을 제시하지 않았거나 해당 판이 없습니다. 또는 현재 판을 숨기려 하고 있을 수도 있습니다.',
'revdelete-nologtype-title'   => '기록의 종류가 제시되지 않았습니다.',
'revdelete-nologtype-text'    => '이 명령을 수행할 기록의 종류를 제시하지 않았습니다.',
'revdelete-nologid-title'     => '잘못된 기록',
'revdelete-nologid-text'      => '이 기능을 수행할 특정 기록을 제시하지 않았거나 제시한 기록이 존재하지 않습니다.',
'revdelete-no-file'           => '해당 파일이 존재하지 않습니다.',
'revdelete-show-file-confirm' => '정말 "<nowiki>$1</nowiki>" 파일의 삭제된 $2 $3 버전을 보시겠습니까?',
'revdelete-show-file-submit'  => '예',
'revdelete-selected'          => "'''[[:$1]]의 {{PLURAL:$2|선택한 판}}:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|선택한 기록}}:'''",
'revdelete-text'              => "'''삭제된 판과 기록은 문서 역사와 기록에 계속 나타나지만, 내용은 공개되지 않을 것입니다.'''
{{SITENAME}}의 다른 관리자는 다른 제한이 설정되어 있지 않는 한, 숨겨진 내용을 볼 수 있고, 같은 도구를 이용해 복구할 수 있습니다.",
'revdelete-confirm'           => '이 작업을 수행하는 것의 결과를 알고 있으며, [[{{MediaWiki:Policy-url}}|정책]]에 맞는 행동인지 확인해주세요.',
'revdelete-suppress-text'     => "숨기기는 '''다음 경우에만''' 사용되어야 합니다:
* 잠재적인 비방 정보
* 부적절한 개인 정보
*: 집 주소, 전화번호, 주민등록번호 등",
'revdelete-legend'            => '보이기 제한을 설정',
'revdelete-hide-text'         => '판의 내용을 숨기기',
'revdelete-hide-image'        => '파일을 숨기기',
'revdelete-hide-name'         => '기록 내용과 대상을 숨기기',
'revdelete-hide-comment'      => '편집 요약을 숨기기',
'revdelete-hide-user'         => '편집자의 사용자 이름/IP를 숨기기',
'revdelete-hide-restricted'   => '관리자도 보지 못하게 숨기기',
'revdelete-radio-same'        => '(바꾸지 않음)',
'revdelete-radio-set'         => '예',
'revdelete-radio-unset'       => '아니오',
'revdelete-suppress'          => '문서 내용을 관리자에게도 보이지 않게 숨기기',
'revdelete-unsuppress'        => '복구된 판에 대한 제한을 해제',
'revdelete-log'               => '이유:',
'revdelete-submit'            => '선택한 {{PLURAL:$1|판}}에 적용',
'revdelete-success'           => "'''판의 보이기 설정을 성공적으로 바꾸었습니다.'''",
'revdelete-failure'           => "'''특정 판 보기 설정을 바꾸지 못했습니다:'''
$1",
'logdelete-success'           => "'''기록 보이기 설정을 성공적으로 바꾸었습니다.'''",
'logdelete-failure'           => "'''기록 보이기 설정을 바꾸지 못했습니다:'''
$1",
'revdel-restore'              => '보이기 설정 바꾸기',
'revdel-restore-deleted'      => '삭제된 판',
'revdel-restore-visible'      => '공개된 판',
'pagehist'                    => '문서 역사',
'deletedhist'                 => '삭제된 역사',
'revdelete-hide-current'      => '$1 $2 판을 숨기는 도중 오류 발생: 이 판은 현재 판입니다.
현재 판은 숨길 수 없습니다.',
'revdelete-show-no-access'    => '$1 $2 판을 보이는 데 오류 발생: 이 판은 "제한"으로 표시되어 있습니다.
여기에 접근할 수 없습니다.',
'revdelete-modify-no-access'  => '$1 $2 판을 고치는 데 오류 발생: 이 판은 "제한"으로 표시되어 있습니다.
여기에 접근할 수 없습니다.',
'revdelete-modify-missing'    => '판 ID $1을 수정하는 중 오류 발생: 데이터베이스에 존재하지 않습니다!',
'revdelete-no-change'         => "'''경고:''' $1 $2에 해당하는 항목은 이미 같은 보이기 설정이 설정되어 있습니다.",
'revdelete-concurrent-change' => '$1 $2에 수정된 항목을 새로 고치면서 오류 발생: 이런 현상은 당신이 문서를 편집하고 있을 때 다른 사람이 문서를 편집했기 때문에 발생합니다.
관련 기록을 확인해 보세요.',
'revdelete-only-restricted'   => '$1 $2 버전 숨기기 오류: 다른 숨기기 설정을 같이 설정하지 않고 관리자가 보지 못하도록 특정 판을 숨길 수 없습니다.',
'revdelete-reason-dropdown'   => '*일반적인 삭제 이유
** 저작권 침해
** 부적절한 의견과 개인 정보
** 부적절한 이름
** 잠재적인 비방 정보',
'revdelete-otherreason'       => '다른 이유/부가적인 이유',
'revdelete-reasonotherlist'   => '다른 이유',
'revdelete-edit-reasonlist'   => '삭제 이유 편집',
'revdelete-offender'          => '판 작성자:',

# Suppression log
'suppressionlog'     => '숨기기 기록',
'suppressionlogtext' => '다음은 관리자로부터 숨겨진 내용에 관한 삭제와 차단 기록입니다.
현재 차단된 사용자 목록을 보려면 [[Special:BlockList|차단된 사용자 목록]]을 참고하세요.',

# History merging
'mergehistory'                     => '문서 역사 합치기',
'mergehistory-header'              => '이 문서는 한 문서에서 다른 문서로 문서 역사를 합치게 할 것입니다.
이전 문서를 역사적 기록으로 계속 남겨둘 것인지 확인해주세요.',
'mergehistory-box'                 => '두 문서의 역사 합치기:',
'mergehistory-from'                => '원본 문서 이름:',
'mergehistory-into'                => '새 문서 이름:',
'mergehistory-list'                => '병합 가능한 문서 역사',
'mergehistory-merge'               => '[[:$1]] 문서의 다음 판이 [[:$2]] 문서로 병합될 수 있습니다.
병합하려는 판과 그 이전의 판을 선택하시려면 라디오 버튼을 이용해주세요.
둘러보기 링크를 이용하는 것은 이 문서를 초기화시킬 것입니다.',
'mergehistory-go'                  => '합칠 수 있는 편집 보기',
'mergehistory-submit'              => '문서 역사 합치기',
'mergehistory-empty'               => '합칠 수 있는 판이 없습니다.',
'mergehistory-success'             => '[[:$1]] 문서의 판 $3개가 [[:$2]]에 성공적으로 합쳐졌습니다.',
'mergehistory-fail'                => '문서 역사 합치기 명령을 수행할 수 없습니다. 문서와 시간 변수를 다시 확인하십시오.',
'mergehistory-no-source'           => '원본인 $1 문서가 존재하지 않습니다.',
'mergehistory-no-destination'      => '대상인 $1 문서가 존재하지 않습니다.',
'mergehistory-invalid-source'      => '원본 문서 이름에는 반드시 유효한 제목을 입력해야 합니다.',
'mergehistory-invalid-destination' => '대상 문서 이름에는 반드시 유효한 제목을 입력해야 합니다.',
'mergehistory-autocomment'         => '[[:$1]] 문서를 [[:$2]] 문서로 합침',
'mergehistory-comment'             => '[[:$1]] 문서를 [[:$2]] 문서로 합침: $3',
'mergehistory-same-destination'    => '원본 문서 이름과 새 문서 이름은 달라야 합니다',
'mergehistory-reason'              => '이유:',

# Merge log
'mergelog'           => '병합 기록',
'pagemerge-logentry' => '사용자가 [[$1]]을 [[$2]]에 병합 ($3판이 위로 옮겨짐)',
'revertmerge'        => '병합 해제',
'mergelogpagetext'   => '다음은 한 문서의 역사를 다른 문서의 역사와 합친 최근 기록입니다.',

# Diffs
'history-title'            => '"$1" 문서의 바뀜 내역',
'difference'               => '(판 사이의 차이)',
'difference-multipage'     => '(문서 사이의 차이)',
'lineno'                   => '$1번째 줄:',
'compareselectedversions'  => '선택한 판을 비교하기',
'showhideselectedversions' => '선택한 판을 보이기/숨기기',
'editundo'                 => '편집 취소',
'diff-multi'               => '({{PLURAL:$2|한 사용자의|사용자 $2명의}} 중간의 편집 $1개 숨겨짐)',
'diff-multi-manyusers'     => '({{PLURAL:$2|한 사용자의|사용자 $2명 이상의}} 중간의 편집 $1개 숨겨짐)',

# Search results
'searchresults'                    => '찾기 결과',
'searchresults-title'              => '"$1"에 대한 찾기 결과',
'searchresulttext'                 => '{{SITENAME}}의 찾기 기능에 대한 자세한 정보는 [[{{MediaWiki:Helppage}}|{{int:help}}]] 문서를 참고해주세요.',
'searchsubtitle'                   => '\'\'\'[[:$1]]\'\'\' 문서를 찾고 있습니다. ([[Special:Prefixindex/$1|이름이 "$1" 접두어로 시작하는 문서 목록]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" 문서를 가리키는 문서 목록]])',
'searchsubtitleinvalid'            => "찾은 단어 '''$1'''",
'toomanymatches'                   => '일치하는 결과가 너무 많습니다. 다른 검색어를 입력해주세요.',
'titlematches'                     => '문서 제목 일치',
'notitlematches'                   => '해당하는 제목 없음',
'textmatches'                      => '문서 내용 일치',
'notextmatches'                    => '해당하는 문서 없음',
'prevn'                            => '이전 $1개',
'nextn'                            => '다음 $1개',
'prevn-title'                      => '이전 결과 $1개',
'nextn-title'                      => '다음 결과 $1개',
'shown-title'                      => '쪽마다 결과 $1개씩 보이기',
'viewprevnext'                     => '보기: ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => '찾기 설정',
'searchmenu-exists'                => "'''이 위키에 \"[[:\$1]]\"의 이름을 가진 문서가 있습니다.'''",
'searchmenu-new'                   => "'''이 위키에 \"[[:\$1]]\" 문서를 만드세요!'''",
'searchhelp-url'                   => 'Help:목차',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|이 접두어로 시작하는 문서 찾기]]',
'searchprofile-articles'           => '일반 문서',
'searchprofile-project'            => '도움말 및 프로젝트 문서',
'searchprofile-images'             => '멀티미디어',
'searchprofile-everything'         => '모든 문서 찾기',
'searchprofile-advanced'           => '고급 찾기',
'searchprofile-articles-tooltip'   => '$1에서 찾기',
'searchprofile-project-tooltip'    => '$1에서 찾기',
'searchprofile-images-tooltip'     => '파일 찾기',
'searchprofile-everything-tooltip' => '토론 문서를 포함한 모든 문서 찾기',
'searchprofile-advanced-tooltip'   => '다음 설정한 이름공간에서 찾기',
'search-result-size'               => '$1 ($2 단어)',
'search-result-category-size'      => '문서 {{PLURAL:$1|1|$1}}개, 하위 분류 {{PLURAL:$2|1|$2}}개, 파일 {{PLURAL:$3|1|$3}}',
'search-result-score'              => '유사도: $1%',
'search-redirect'                  => '($1에서 넘어옴)',
'search-section'                   => '($1 문단)',
'search-suggest'                   => '$1 문서를 찾고 있으신가요?',
'search-interwiki-caption'         => '자매 프로젝트',
'search-interwiki-default'         => '$1 결과:',
'search-interwiki-more'            => '(더 보기)',
'search-mwsuggest-enabled'         => '검색어 제안 있음',
'search-mwsuggest-disabled'        => '검색어 제안 없음',
'search-relatedarticle'            => '관련',
'mwsuggest-disable'                => 'AJAX 검색어 제안 끄기',
'searcheverything-enable'          => '모든 이름공간에서 찾기',
'searchrelated'                    => '관련',
'searchall'                        => '모두',
'showingresults'                   => '<strong>$2</strong>번 부터의 <strong>결과 $1개</strong>입니다.',
'showingresultsnum'                => "'''$2'''번 부터의 '''결과 $3개''' 입니다.",
'showingresultsheader'             => "'''$4''' 검색어에 대하여 결과 '''$3'''개 중 {{PLURAL:$5|'''$1'''개|'''$1 - $2'''번째}}를 보여 주고 있습니다.",
'nonefound'                        => "'''참고''': 몇개의 이름공간만 기본 찾을 범위입니다. 토론이나 틀 등의 모든 자료를 찾하기 위해서는 접두어로 '''all:''' 어떤 이름공간을 위해서는 접두어로 그 이름공간을 쓸 수 있습니다.",
'search-nonefound'                 => '찾기 결과가 없습니다.',
'powersearch'                      => '고급 찾기',
'powersearch-legend'               => '고급 찾기',
'powersearch-ns'                   => '다음 이름공간에서 찾기:',
'powersearch-redir'                => '넘겨주기 목록',
'powersearch-field'                => '찾기',
'powersearch-togglelabel'          => '확인:',
'powersearch-toggleall'            => '모두 선택',
'powersearch-togglenone'           => '모두 선택하지 않음',
'search-external'                  => '바깥 찾기',
'searchdisabled'                   => '{{SITENAME}} 찾기 기능이 비활성화되어 있습니다.
기능이 작동하지 않는 동안에는 구글(Google)을 이용해 찾을 수 있습니다.
검색 엔진의 내용은 최신이 아닐 수 있다는 점을 주의해주세요.',

# Quickbar
'qbsettings'                => '빨리가기 맞춤',
'qbsettings-none'           => '없음',
'qbsettings-fixedleft'      => '왼쪽 고정',
'qbsettings-fixedright'     => '오른쪽 고정',
'qbsettings-floatingleft'   => '왼쪽 유동',
'qbsettings-floatingright'  => '오른쪽 유동',
'qbsettings-directionality' => '사용자 언어의 문자 입력 방향에 맞추어 고정',

# Preferences page
'preferences'                   => '사용자 환경 설정',
'mypreferences'                 => '환경 설정',
'prefs-edits'                   => '편집 횟수:',
'prefsnologin'                  => '로그인하지 않음',
'prefsnologintext'              => '사용자 환경 설정을 바꾸려면 먼저 <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} 로그인]</span>해야 합니다.',
'changepassword'                => '비밀번호 바꾸기',
'prefs-skin'                    => '스킨',
'skin-preview'                  => '미리 보기',
'datedefault'                   => '기본값',
'prefs-beta'                    => '베타 기능',
'prefs-datetime'                => '날짜와 시각',
'prefs-labs'                    => '실험 중인 기능',
'prefs-personal'                => '사용자 정보',
'prefs-rc'                      => '최근 바뀜',
'prefs-watchlist'               => '주시문서 목록',
'prefs-watchlist-days'          => '주시문서 목록에서 볼 날짜 수:',
'prefs-watchlist-days-max'      => '최대 $1{{PLURAL:$1|일}}',
'prefs-watchlist-edits'         => '주시문서 목록에서 볼 편집 수:',
'prefs-watchlist-edits-max'     => '최대 개수: 1000',
'prefs-watchlist-token'         => '주시문서 목록 토큰:',
'prefs-misc'                    => '기타',
'prefs-resetpass'               => '비밀번호 바꾸기',
'prefs-changeemail'             => '이메일 주소 바꾸기',
'prefs-setemail'                => '이메일 주소 설정하기',
'prefs-email'                   => '이메일 설정',
'prefs-rendering'               => '문서 보이기 설정',
'saveprefs'                     => '저장',
'resetprefs'                    => '저장하지 않은 설정 되돌리기',
'restoreprefs'                  => '모두 기본 설정으로 되돌리기',
'prefs-editing'                 => '편집 상자',
'prefs-edit-boxsize'            => '편집 창의 크기',
'rows'                          => '줄 수:',
'columns'                       => '열 수:',
'searchresultshead'             => '찾기',
'resultsperpage'                => '쪽마다 보이는 결과 수:',
'stub-threshold'                => '링크를 <a href="#" class="stub">토막글</a> 형식으로 보여줄 문서 크기 (바이트 수):',
'stub-threshold-disabled'       => '비활성화됨',
'recentchangesdays'             => '최근 바뀜에 보여줄 날짜 수:',
'recentchangesdays-max'         => '최대 $1일',
'recentchangescount'            => '기본으로 보여줄 편집 수:',
'prefs-help-recentchangescount' => '이 설정은 최근 바뀜, 문서 역사와 기록에 적용됩니다.',
'prefs-help-watchlist-token'    => '아래에 비밀 값을 넣으면 주시문서 목록에 대한 RSS 피드가 만들어집니다.
비밀 값을 알고 있는 사람이라면 누구나 피드를 읽을 수 있으므로 안전한 값을 입력해주세요.
임의로 만들어진 다음 값을 사용할 수도 있습니다: $1',
'savedprefs'                    => '설정을 저장했습니다.',
'timezonelegend'                => '시간대:',
'localtime'                     => '현지 시각:',
'timezoneuseserverdefault'      => '위키 기본값($1)을 사용',
'timezoneuseoffset'             => '기타 (시차를 입력해주세요)',
'timezoneoffset'                => '시차¹:',
'servertime'                    => '서버 시각:',
'guesstimezone'                 => '웹 브라우저 설정에서 가져오기',
'timezoneregion-africa'         => '아프리카',
'timezoneregion-america'        => '아메리카',
'timezoneregion-antarctica'     => '남극',
'timezoneregion-arctic'         => '북극',
'timezoneregion-asia'           => '아시아',
'timezoneregion-atlantic'       => '대서양',
'timezoneregion-australia'      => '오스트레일리아',
'timezoneregion-europe'         => '유럽',
'timezoneregion-indian'         => '인도양',
'timezoneregion-pacific'        => '태평양',
'allowemail'                    => '다른 사용자가 보낸 이메일을 받음',
'prefs-searchoptions'           => '찾기',
'prefs-namespaces'              => '이름공간',
'defaultns'                     => '다음 이름공간에서 찾기:',
'default'                       => '기본값',
'prefs-files'                   => '파일',
'prefs-custom-css'              => '사용자 CSS',
'prefs-custom-js'               => '사용자 자바스크립트',
'prefs-common-css-js'           => '모든 스킨에 대한 공통 CSS/자바스크립트:',
'prefs-reset-intro'             => '이 사이트의 기본값으로 환경 설정을 되돌릴 수 있습니다.
복구할 수 없습니다.',
'prefs-emailconfirm-label'      => '이메일 인증:',
'prefs-textboxsize'             => '편집창의 크기',
'youremail'                     => '이메일:',
'username'                      => '사용자 이름:',
'uid'                           => '사용자 ID:',
'prefs-memberingroups'          => '소속 {{PLURAL:$1|그룹}}:',
'prefs-registration'            => '등록 일시:',
'yourrealname'                  => '실명:',
'yourlanguage'                  => '언어:',
'yourvariant'                   => '언어 변종:',
'prefs-help-variant'            => '위키 내용을 볼 때 사용할 언어 변종이나 철자 체계를 선택해주세요.',
'yournick'                      => '새 서명:',
'prefs-help-signature'          => '토론 문서에 글을 쓴 후에는 마지막에 서명을 해야 합니다.  “<nowiki>~~~~</nowiki>” 기호를 추가하면 서명과 글 작성 시각이 자동으로 입력됩니다.',
'badsig'                        => '서명이 잘못되었습니다.
HTML 태그를 확인하세요.',
'badsiglength'                  => '서명이 너무 깁니다.
서명은 $1자보다 짧아야 합니다.',
'yourgender'                    => '성별:',
'gender-unknown'                => '무응답',
'gender-male'                   => '남성',
'gender-female'                 => '여성',
'prefs-help-gender'             => '선택 사항: 소프트웨어에서 성별에 따른 언어 문제를 해결하기 위해 사용됩니다.
이 정보는 공개됩니다.',
'email'                         => '이메일',
'prefs-help-realname'           => '실명 기입은 자유입니다.
실명을 입력할 경우 문서 기여에 자신의 이름이 들어가게 됩니다.',
'prefs-help-email'              => '이메일 주소 입력은 선택 사항입니다. 다만 비밀번호를 잊었을 때 비밀번호 바꾸기를 위해 필요합니다.',
'prefs-help-email-others'       => '자신의 문서나 토론 문서에 있는 이메일 보내기 링크로 다른 사용자가 연락할 수 있게 할 수도 있습니다.
이 경우에도 당신의 이메일 주소는 다른 사용자가 연락할 때 공개되지 않습니다.',
'prefs-help-email-required'     => '이메일 주소가 필요합니다.',
'prefs-info'                    => '기본 정보',
'prefs-i18n'                    => '언어 설정',
'prefs-signature'               => '서명',
'prefs-dateformat'              => '날짜 형식',
'prefs-timeoffset'              => '시차 설정',
'prefs-advancedediting'         => '고급 설정',
'prefs-advancedrc'              => '고급 설정',
'prefs-advancedrendering'       => '고급 설정',
'prefs-advancedsearchoptions'   => '고급 설정',
'prefs-advancedwatchlist'       => '고급 설정',
'prefs-displayrc'               => '보이기 설정',
'prefs-displaysearchoptions'    => '보이기 설정',
'prefs-displaywatchlist'        => '보이기 설정',
'prefs-diffs'                   => '차이',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => '이메일 주소가 유효한 것으로 보입니다.',
'email-address-validity-invalid' => '유효한 이메일 주소를 입력해주세요.',

# User rights
'userrights'                   => '사용자 권한 관리',
'userrights-lookup-user'       => '사용자 권한 관리',
'userrights-user-editname'     => '사용자 이름 입력:',
'editusergroup'                => '사용자 그룹 편집',
'editinguser'                  => "'''[[User:$1|$1]]''' $2 사용자의 권한 바꾸기",
'userrights-editusergroup'     => '사용자 그룹 편집',
'saveusergroups'               => '사용자 권한 저장',
'userrights-groupsmember'      => '현재 권한:',
'userrights-groupsmember-auto' => '자동으로 부여된 권한:',
'userrights-groups-help'       => '이 사용자의 권한을 바꿀 수 있습니다.
* 사용자는 체크 표시가 있는 권한을 갖습니다.
* 사용자는 체크 표시가 없는 권한을 갖지 않습니다.
* <nowiki>*</nowiki>표시는 권한을 주거나 거두는 것 중 하나만 할 수 있다는 뜻입니다.',
'userrights-reason'            => '이유:',
'userrights-no-interwiki'      => '다른 위키의 사용자 권한을 바꿀 권한이 없습니다.',
'userrights-nodatabase'        => '데이터베이스 $1이 존재하지 않거나 로컬에 있지 않습니다.',
'userrights-nologin'           => '사용자의 권한을 바꾸기 위해서는 반드시 관리자 계정으로 [[Special:UserLogin|로그인]]해야 합니다.',
'userrights-notallowed'        => '다른 사용자의 권한을 조정할 권한이 없습니다.',
'userrights-changeable-col'    => '바꿀 수 있는 권한',
'userrights-unchangeable-col'  => '바꿀 수 없는 권한',

# Groups
'group'               => '그룹:',
'group-user'          => '사용자',
'group-autoconfirmed' => '자동 인증된 사용자',
'group-bot'           => '봇',
'group-sysop'         => '관리자',
'group-bureaucrat'    => '사무관',
'group-suppress'      => '오버사이트',
'group-all'           => '(모두)',

'group-user-member'          => '{{GENDER:$1|사용자}}',
'group-autoconfirmed-member' => '{{GENDER:$1|자동 인증된 사용자}}',
'group-bot-member'           => '{{GENDER:$1|봇}}',
'group-sysop-member'         => '{{GENDER:$1|관리자}}',
'group-bureaucrat-member'    => '{{GENDER:$1|사무관}}',
'group-suppress-member'      => '{{GENDER:$1|오버사이트}}',

'grouppage-user'          => '{{ns:project}}:일반 사용자',
'grouppage-autoconfirmed' => '{{ns:project}}:자동 인증된 사용자',
'grouppage-bot'           => '{{ns:project}}:봇',
'grouppage-sysop'         => '{{ns:project}}:관리자',
'grouppage-bureaucrat'    => '{{ns:project}}:사무관',
'grouppage-suppress'      => '{{ns:project}}:오버사이트',

# Rights
'right-read'                  => '문서 읽기',
'right-edit'                  => '문서 편집',
'right-createpage'            => '문서 만들기 (토론 문서 제외)',
'right-createtalk'            => '토론 문서 만들기',
'right-createaccount'         => '새 계정 만들기',
'right-minoredit'             => '사소한 편집 사용 가능',
'right-move'                  => '문서 이동',
'right-move-subpages'         => '문서와 하위 문서 이동하기',
'right-move-rootuserpages'    => '최상위 사용자 문서 이동',
'right-movefile'              => '파일 옮기기',
'right-suppressredirect'      => '문서 이동할 때 이전 이름으로 된 넘겨주기를 남기지 않기',
'right-upload'                => '파일 올리기',
'right-reupload'              => '이미 존재하는 파일을 다시 올리기',
'right-reupload-own'          => '자신이 이미 올린 파일 덮어쓰기',
'right-reupload-shared'       => '공용의 파일을 무시하고 로컬에서 파일 올리기',
'right-upload_by_url'         => 'URL 주소에서 파일 올리기',
'right-purge'                 => '확인 없이 문서의 캐시를 새로 고침',
'right-autoconfirmed'         => '준보호된 문서 편집',
'right-bot'                   => '봇의 편집으로 취급',
'right-nominornewtalk'        => '토론 문서를 새로 만들때 사소한 편집 사용 불가능',
'right-apihighlimits'         => 'API 상한 상승',
'right-writeapi'              => 'API 작성',
'right-delete'                => '문서 삭제',
'right-bigdelete'             => '문서 역사가 긴 문서를 삭제',
'right-deleterevision'        => '문서의 특정 판을 삭제 및 복구',
'right-deletedhistory'        => '삭제된 문서의 내용을 제외한 역사를 보기',
'right-deletedtext'           => '삭제된 문서의 내용과 편집상의 차이를 보기',
'right-browsearchive'         => '삭제된 문서 찾기',
'right-undelete'              => '삭제된 문서 복구',
'right-suppressrevision'      => '관리자도 보지 못하도록 숨겨진 판의 확인 및 복구',
'right-suppressionlog'        => '숨겨진 기록을 보기',
'right-block'                 => '다른 사용자를 편집을 못하도록 차단',
'right-blockemail'            => '다른 사용자가 이메일을 보내지 못하도록 차단',
'right-hideuser'              => '사용자 이름을 차단하고 숨김',
'right-ipblock-exempt'        => 'IP 차단, 자동 차단, 광역 차단을 무시',
'right-proxyunbannable'       => '프록시 자동 차단을 적용하지 않음',
'right-unblockself'           => '자기 자신을 차단 해제하기',
'right-protect'               => '보호 수준 바꾸기 및 보호된 문서 편집',
'right-editprotected'         => '보호된 문서 편집 (연쇄적 보호 제외)',
'right-editinterface'         => '사용자 인터페이스를 편집',
'right-editusercssjs'         => '다른 사용자의 CSS와 자바스크립트 문서를 편집',
'right-editusercss'           => '다른 사용자의 CSS 문서를 편집',
'right-edituserjs'            => '다른 사용자의 자바스크립트 문서를 편집',
'right-rollback'              => '특정 문서를 편집한 마지막 사용자의 편집을 신속하게 되돌리기',
'right-markbotedits'          => '되돌리기를 봇의 편집으로 취급 가능',
'right-noratelimit'           => '편집이나 다른 행동 속도의 제한을 받지 않음',
'right-import'                => '다른 위키에서 문서 가져오기',
'right-importupload'          => '파일 올리기를 통해 문서 가져오기',
'right-patrol'                => '다른 사용자의 편집을 검토',
'right-autopatrol'            => '자신의 편집을 자동으로 검토',
'right-patrolmarks'           => '최근 바뀜에서 검토 표시를 보기',
'right-unwatchedpages'        => '주시되지 않은 문서 목록 보기',
'right-mergehistory'          => '문서의 역사를 합침',
'right-userrights'            => '모든 사용자의 권한 조정',
'right-userrights-interwiki'  => '다른 위키의 사용자 권한을 조정',
'right-siteadmin'             => '데이터베이스를 잠그거나 잠금 해제',
'right-override-export-depth' => '5단계로 링크된 문서를 포함하여 문서를 내보내기',
'right-sendemail'             => '다른 사용자에게 이메일 보내기',
'right-passwordreset'         => '비밀번호 재설정 이메일을 보기',

# User rights log
'rightslog'                  => '사용자 권한 기록',
'rightslogtext'              => '사용자 권한 조정 기록입니다.',
'rightslogentry'             => '사용자가 $1의 권한을 $2에서 $3으로 바꾸었습니다',
'rightslogentry-autopromote' => '사용자의 권한이 자동적으로 $2에서 $3으로 바뀌었습니다.',
'rightsnone'                 => '(없음)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => '이 문서를 읽기',
'action-edit'                 => '문서 편집',
'action-createpage'           => '문서 만들기',
'action-createtalk'           => '토론 문서 만들기',
'action-createaccount'        => '새 계정 만들기',
'action-minoredit'            => '이 편집을 사소한 편집으로 표시하기',
'action-move'                 => '이 문서 옮기기',
'action-move-subpages'        => '하위 문서를 함께 옮길',
'action-move-rootuserpages'   => '최상위 사용자 문서를 이동할',
'action-movefile'             => '이 파일을 옮기기',
'action-upload'               => '이 파일을 올리기',
'action-reupload'             => '이미 존재하는 파일 덮어쓰기',
'action-reupload-shared'      => '공용 저장소의 파일을 무시하고 저장할',
'action-upload_by_url'        => 'URL 주소를 통해 이 파일을 올리기',
'action-writeapi'             => 'API를 작성할',
'action-delete'               => '이 문서 삭제하기',
'action-deleterevision'       => '이 판을 삭제',
'action-deletedhistory'       => '이 문서의 삭제된 기여의 역사 보기',
'action-browsearchive'        => '삭제된 문서 찾기',
'action-undelete'             => '이 문서를 복구하기',
'action-suppressrevision'     => '이 숨겨진 판을 검토하고 복구할',
'action-suppressionlog'       => '비공개 기록 보기',
'action-block'                => '이 사용자를 편집하지 못하도록 차단',
'action-protect'              => '이 문서의 보호 설정을 바꾸기',
'action-rollback'             => '특정 문서를 마지막으로 편집한 사용자의 모든 편집을 간편하게 되돌리기',
'action-import'               => '다른 위키에서 이 문서를 가져오기',
'action-importupload'         => '파일 올리기를 통해 문서를 가져올',
'action-patrol'               => '다른 사용자의 편집을 검토된 것으로 표시하기',
'action-autopatrol'           => '자신의 편집을 검토된 것으로 표시할',
'action-unwatchedpages'       => '주시되지 않은 문서 목록 보기',
'action-mergehistory'         => '이 문서의 역사 합치기',
'action-userrights'           => '모든 사용자의 권한을 조정',
'action-userrights-interwiki' => '다른 위키의 사용자 권한을 조정',
'action-siteadmin'            => '데이터베이스를 잠그거나 잠금 해제하기',
'action-sendemail'            => '이메일 보내기',

# Recent changes
'nchanges'                          => '$1개 바뀜',
'recentchanges'                     => '최근 바뀜',
'recentchanges-legend'              => '최근 바뀜 설정',
'recentchangestext'                 => '위키의 최근 바뀜 내역이 나와 있습니다.',
'recentchanges-feed-description'    => '위키의 최근 바뀜',
'recentchanges-label-newpage'       => '새로운 문서',
'recentchanges-label-minor'         => '사소한 편집',
'recentchanges-label-bot'           => '봇의 편집',
'recentchanges-label-unpatrolled'   => '아직 검토하지 않은 편집',
'rcnote'                            => "다음은 $4 $5 까지의 '''$2'''일동안 바뀐 문서 '''$1'''개입니다.",
'rcnotefrom'                        => "다음은 '''$2'''에서부터 바뀐 문서 '''$1'''개입니다.",
'rclistfrom'                        => '$1 이래로 바뀐 문서',
'rcshowhideminor'                   => '사소한 편집을 $1',
'rcshowhidebots'                    => '봇을 $1',
'rcshowhideliu'                     => '등록 사용자를 $1',
'rcshowhideanons'                   => '익명 사용자를 $1',
'rcshowhidepatr'                    => '검토된 편집을 $1',
'rcshowhidemine'                    => '내 편집을 $1',
'rclinks'                           => '최근 $2일간의 $1개 바뀜 기록 보기<br />$3',
'diff'                              => '비교',
'hist'                              => '역사',
'hide'                              => '숨기기',
'show'                              => '보이기',
'minoreditletter'                   => '잔글',
'newpageletter'                     => '새글',
'boteditletter'                     => '봇',
'number_of_watching_users_pageview' => '[$1명이 주시하고 있음]',
'rc_categories'                     => '다음 분류로 제한 ("|"로 구분)',
'rc_categories_any'                 => '모두',
'rc-change-size-new'                => '바꾼 후 $1 {{PLURAL:$1|바이트}}',
'newsectionsummary'                 => '새 주제: /* $1 */',
'rc-enhanced-expand'                => '자세한 기록 보기 (자바스크립트 필요)',
'rc-enhanced-hide'                  => '자세한 기록 숨기기',
'rc-old-title'                      => '처음에  "$1"라는 제목으로 만들어짐',

# Recent changes linked
'recentchangeslinked'          => '가리키는 글의 바뀜',
'recentchangeslinked-feed'     => '가리키는 글의 바뀜',
'recentchangeslinked-toolbox'  => '가리키는 글의 바뀜',
'recentchangeslinked-title'    => '"$1" 문서에 관련된 문서 바뀜',
'recentchangeslinked-noresult' => '이 문서에서 링크하는 문서 중, 해당 기간에 바뀐 문서가 없습니다.',
'recentchangeslinked-summary'  => "여기를 가리키는 문서(분류일 경우 이 분류에 포함된 문서)에 대한 최근 바뀜이 나와 있습니다.
[[Special:Watchlist|주시하는 문서]]는 '''굵은''' 글씨로 나타납니다.",
'recentchangeslinked-page'     => '문서 이름:',
'recentchangeslinked-to'       => '여기를 가리키는 문서의 최근 바뀜',

# Upload
'upload'                      => '파일 올리기',
'uploadbtn'                   => '파일 올리기',
'reuploaddesc'                => '올리기를 취소하고 올리기 양식으로 돌아가기',
'upload-tryagain'             => '수정된 파일 설명을 저장',
'uploadnologin'               => '로그인하지 않음',
'uploadnologintext'           => '파일을 올리려면 [[Special:UserLogin|로그인]]해야 합니다.',
'upload_directory_missing'    => '파일 올리기용 디렉터리($1)가 없고 웹 서버가 만들지 못했습니다.',
'upload_directory_read_only'  => '파일 저장 디렉터리($1)에 쓰기 권한이 없습니다.',
'uploaderror'                 => '올리기 오류',
'upload-recreate-warning'     => "'''경고: 이 파일로 된 이름이 삭제되었거나 옮겨졌습니다.'''

이 문서의 최근 삭제 기록과 이동 기록을 참고하십시오:",
'uploadtext'                  => "파일을 올리기 위해서는 아래의 양식을 채워주세요.
[[Special:FileList|파일 목록]]에서 이전에 올라온 파일을 찾을 수 있습니다. [[Special:Log/upload|올리기 기록]]에는 파일이 올라온 기록이 남습니다. 삭제 기록은 [[Special:Log/delete|삭제 기록]]에서 볼 수 있습니다.

문서에 파일을 넣으려면 아래 방법 중 하나를 사용하세요.
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></code>''' 파일의 온전한 모양을 사용하고자 할 때.
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200픽셀|섬네일|왼쪽|설명]]</nowiki></code>''' 파일의 넓이를 200픽셀로 하고 왼쪽 정렬하며 '설명' 이라는 주석을 파일 밑에 달 때.
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></code>''' 파일을 직접 보여주지 않고 파일로 바로 링크할때.",
'upload-permitted'            => '허용하는 파일 확장자: $1',
'upload-preferred'            => '권장하는 파일 확장자: $1',
'upload-prohibited'           => '금지하는 파일 확장자: $1',
'uploadlog'                   => '올리기 기록',
'uploadlogpage'               => '올리기 기록',
'uploadlogpagetext'           => '최근 올라온 파일 목록입니다.
갤러리 형식으로 확인하고 싶으시다면 [[Special:NewFiles|새 파일 목록]]을 보세요.',
'filename'                    => '파일 이름',
'filedesc'                    => '파일의 설명',
'fileuploadsummary'           => '요약:',
'filereuploadsummary'         => '파일 바뀜에 대한 요약:',
'filestatus'                  => '저작권 상태:',
'filesource'                  => '출처:',
'uploadedfiles'               => '파일 올리기',
'ignorewarning'               => '경고를 무시하고 파일 저장',
'ignorewarnings'              => '모든 경고 무시하기',
'minlength1'                  => '파일 이름은 적어도 1글자 이상이어야 합니다.',
'illegalfilename'             => '파일 이름 "$1"에는 문서 제목으로 허용되지 않는 글자가 포함되어 있습니다.
이름을 바꾸어 다시 시도해 주세요.',
'filename-toolong'            => '파일 이름은 240바이트를 넘을 수 없습니다.',
'badfilename'                 => '파일 이름이 "$1"로 바뀌었습니다.',
'filetype-mime-mismatch'      => '파일 확장자 ".$1"와 이 파일의 MIME($2)가 일치하지 않습니다.',
'filetype-badmime'            => '"$1" MIME을 가진 파일은 올릴 수 없습니다.',
'filetype-bad-ie-mime'        => '인터넷 익스플로러가 잠재적으로 위험한 파일 형식으로 판단되어 사용이 금지된 "$1"로 인식할 수 있기 때문에 이 파일을 올릴 수 없습니다.',
'filetype-unwanted-type'      => "'''\".\$1\"''' 확장자는 추천하지 않습니다.
추천하는 {{PLURAL:\$3|파일 확장자}}는 \$2입니다.",
'filetype-banned-type'        => '{{PLURAL:$3$4}}\'\'\'".$1"\'\'\' 형식의 파일은 올릴 수 없습니다.
$2 형식만 사용할 수 있습니다.',
'filetype-missing'            => '파일에 확장자(".jpg" 등)가 없습니다.',
'empty-file'                  => '올린 파일이 비어 있습니다.',
'file-too-large'              => '올리려는 파일이 너무 큽니다.',
'filename-tooshort'           => '파일 이름이 너무 짧습니다.',
'filetype-banned'             => '이러한 종류의 파일은 금지되어 있습니다.',
'verification-error'          => '이 파일은 파일 확인 절차를 통과하지 않았습니다.',
'hookaborted'                 => '수정하려고 한 것이 확장 기능 훅에 의해 중지되었습니다.',
'illegal-filename'            => '이 파일 이름은 허용되지 않습니다.',
'overwrite'                   => '기존 파일을 덮어쓰는 것은 허용되지 않습니다.',
'unknown-error'               => '알 수 없는 오류가 발생했습니다.',
'tmp-create-error'            => '임시 파일을 만들 수 없습니다.',
'tmp-write-error'             => '임시 파일을 작성하는 데 오류가 발생했습니다.',
'large-file'                  => '파일 크기는 $1을 넘지 않는 것을 추천합니다.
이 파일의 크기는 $2입니다.',
'largefileserver'             => '이 파일의 크기가 서버에서 허용된 설정보다 큽니다.',
'emptyfile'                   => '올리려는 파일이 빈 파일입니다.
파일 이름을 잘못 입력했을 수도 있습니다.
올리려는 파일을 다시 한 번 확인해 주시기 바랍니다.',
'windows-nonascii-filename'   => '이 위키에서는 특수 문자가 포함된 파일 이름을 지원하지 않습니다.',
'fileexists'                  => '같은 이름의 파일이 이미 있습니다. 파일을 바꾸고 싶지 않다면 <strong>[[:$1]]</strong> 파일을 확인해 주세요.
[[$1|thumb]]',
'filepageexists'              => '이 파일의 설명 문서가 <strong>[[:$1]]</strong>에 존재하지만, 이 이름을 가진 파일이 존재하지 않습니다.
입력한 설명은 설명 문서에 반영되지 않을 것입니다.
설명을 반영시키려면, 직접 편집하셔야 합니다.
[[$1|thumb]]',
'fileexists-extension'        => '비슷한 이름의 파일이 존재합니다: [[$2|thumb]]
* 올리려는 파일 이름: <strong>[[:$1]]</strong>
* 존재하는 파일 이름: <strong>[[:$2]]</strong>
다른 이름으로 시도해 주세요.',
'fileexists-thumbnail-yes'    => '이 파일은 원본 그림이 아닌, 다른 그림의 크기를 줄인 섬네일 파일인 것 같습니다.
[[$1|thumb]]
<strong>[[:$1]]</strong> 파일을 확인해주세요.
해당 파일이 현재 올리려는 파일과 같다면, 더 작은 크기의 그림을 올릴 필요는 없습니다.',
'file-thumbnail-no'           => '파일 이름이 <strong>$1</strong>으로 시작합니다.
이 파일은 원본 그림이 아닌, 다른 그림의 크기를 줄인 섬네일 파일인 것 같습니다.
더 해상도가 좋은 파일이 있다면 그 파일을 올려주세요. 아니면 올리려는 파일 이름을 바꾸어 주세요.',
'fileexists-forbidden'        => '같은 이름의 파일이 이미 있고, 덮어쓸 수 없습니다.
그래도 파일을 올리시려면, 뒤로 돌아가서 다른 이름으로 시도해 주시기 바랍니다.
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => '같은 이름의 파일이 이미 위키미디어 공용에 있습니다.
파일을 업로드하길 원하신다면 뒤로 돌아가서 다른 이름으로 시도해 주시기 바랍니다.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => '현재 올리고 있는 {{PLURAL:$1|파일}}이 아래 파일과 중복됩니다:',
'file-deleted-duplicate'      => '이 파일과 같은 파일 ([[:$1]])이 이전에 삭제된 적이 있습니다. 파일을 다시 올리기 전에 문서의 삭제 기록을 확인해 주시기 바랍니다.',
'uploadwarning'               => '올리기 경고',
'uploadwarning-text'          => '아래의 파일 설명을 수정하고 다시 시도해 주세요.',
'savefile'                    => '파일 저장',
'uploadedimage'               => '사용자가 "[[$1]]" 파일을 올렸습니다.',
'overwroteimage'              => '사용자가 "[[$1]]" 파일의 새 판을 올렸습니다.',
'uploaddisabled'              => '올리기 비활성화됨',
'copyuploaddisabled'          => 'URL로 파일 올리기가 비활성화되어 있습니다.',
'uploadfromurl-queued'        => '올리기 명령이 기록되었습니다.',
'uploaddisabledtext'          => '파일 올리기 기능이 비활성화되어 있습니다.',
'php-uploaddisabledtext'      => 'PHP 파일 올리기가 비활성화되었습니다. 파일 올리기 설정을 확인하십시오.',
'uploadscripted'              => '이 파일에는 HTML이나 다른 스크립트 코드가 포함되어 있어, 웹 브라우저에서 오류를 일으킬 수 있습니다.',
'uploadvirus'                 => '파일이 바이러스를 포함하고 있습니다!
자세한 설명: $1',
'uploadjava'                  => '이 ZIP 파일은 자바의 .class 파일을 포함하고 있습니다.
보안을 위한 제한을 우회할 수 있기 때문에 자바 파일을 올리는 것이 허용되지 않습니다.',
'upload-source'               => '원본 파일',
'sourcefilename'              => '원본 파일 이름:',
'sourceurl'                   => '출처 URL:',
'destfilename'                => '파일의 새 이름:',
'upload-maxfilesize'          => '파일의 최대 크기: $1',
'upload-description'          => '파일의 설명',
'upload-options'              => '올리기 설정',
'watchthisupload'             => '이 파일 주시하기',
'filewasdeleted'              => '같은 이름을 가진 파일이 올라온 적이 있었고 그 후에 삭제되었습니다.
올리기 전에 $1을 확인해 주시기 바랍니다.',
'filename-bad-prefix'         => '올리려고 하는 파일 이름이 \'\'\'"$1"\'\'\'(으)로 시작합니다. "$1"은(는) 디지털 사진기가 자동으로 붙이는 의미없는 이름입니다.
파일에 대해 알기 쉬운 이름을 골라주세요.',
'filename-prefix-blacklist'   => ' #<!-- 이 줄은 그대로 두십시오 --> <pre>
# 문법은 다음과 같습니다:
#   * "#"에서 그 줄의 끝까지는 코멘트입니다.
#   * 비어 있지 않은 줄은 디지털 카메라에서 자동적으로 부여하는 파일 접두어입니다.
CIMG # 카시오
DSC_ # 니콘
DSCF # 후지
DSCN # 니콘
DUW # 일부 휴대폰
IMG # 일반
JD # 제놉틱
MGP # 펜탁스
PICT # 기타
 #</pre> <!-- 이 줄은 그대로 두십시오 -->',
'upload-success-subj'         => '올리기 성공',
'upload-success-msg'          => '파일을 [$2]에서 성공적으로 올렸습니다. 올린 파일은 여기 있습니다: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => '올리기 실패',
'upload-failure-msg'          => '[$2]에서 파일을 올리는 중 문제가 발생했습니다:

$1',
'upload-warning-subj'         => '파일 올리기 경고',
'upload-warning-msg'          => '[$2]에서 파일을 올리는 데 문제가 있습니다. 이 문제를 해결하려면 [[Special:Upload/stash/$1|올리기 양식]]으로 되돌아가십시오.',

'upload-proto-error'        => '잘못된 프로토콜',
'upload-proto-error-text'   => '파일을 URL로 올리려면 <code>http://</code>이나 <code>ftp://</code>로 시작해야 합니다.',
'upload-file-error'         => '내부 오류',
'upload-file-error-text'    => '서버에 임시 파일을 만드는 과정에서 내부 오류가 발생했습니다.
[[Special:ListUsers/sysop|관리자]]에게 연락해주세요.',
'upload-misc-error'         => '알 수 없는 파일 올리기 오류',
'upload-misc-error-text'    => '파일을 올리는 중 알 수 없는 오류가 발생했습니다.
URL이 올바르고 접근 가능한지를 확인하고 다시 시도해주세요.
문제가 계속되면 [[Special:ListUsers/sysop|관리자]]에게 연락해주세요.',
'upload-too-many-redirects' => 'URL이 너무 많은 넘겨주기에 연결되어 있습니다.',
'upload-unknown-size'       => '크기를 알 수 없음',
'upload-http-error'         => 'HTTP 오류 발생: $1',

# File backend
'backend-fail-stream'        => '$1 파일을 스트리밍할 수 없습니다.',
'backend-fail-backup'        => '$1 파일을 백업할 수 없습니다.',
'backend-fail-notexists'     => '$1 파일이 존재하지 않습니다.',
'backend-fail-hashes'        => '비교 해시값을 얻지 못했습니다.',
'backend-fail-notsame'       => '$1 파일과 같은 이름을 가진 다른 파일이 존재합니다.',
'backend-fail-invalidpath'   => '$1 경로가 유효하지 않습니다.',
'backend-fail-delete'        => '$1 파일을 삭제할 수 없습니다.',
'backend-fail-alreadyexists' => '$1 파일이 이미 존재합니다.',
'backend-fail-store'         => '$1 파일을 $2 경로에 저장하지 못했습니다.',
'backend-fail-copy'          => '$1 파일을 $2 경로에 복사하지 못했습니다.',
'backend-fail-move'          => '$1 파일을 $2 경로로 이동하지 못했습니다.',
'backend-fail-opentemp'      => '임시 파일을 열 수 없습니다.',
'backend-fail-writetemp'     => '임시 파일을 쓸 수 없습니다.',
'backend-fail-closetemp'     => '임시 파일을 닫을 수 없습니다.',
'backend-fail-read'          => '$1 파일을 읽을 수 없습니다.',
'backend-fail-create'        => '$1 파일을 저장하지 못했습니다.',
'backend-fail-readonly'      => '"$1" 저장 백엔드가 읽기 전용입니다. 자세한 이유는 다음과 같습니다: "$2"',
'backend-fail-synced'        => '파일 "$1"은 내부 저장 백엔드에 불안정한 상태로 있습니다.',
'backend-fail-connect'       => '"$1" 저장 백엔드에 접속하지 못했습니다.',
'backend-fail-internal'      => '"$1" 저장 백엔드에 알 수 없는 오류가 발생했습니다.',
'backend-fail-contenttype'   => '"$1"에 저장하기 위한 파일의 내용 유형을 판별하지 못했습니다.',
'backend-fail-batchsize'     => '저장 백엔드에서 파일 {{PLURAL:$1|작업}} $1개가 쌓여 있습니다. 한계는 $2개입니다.',

# Lock manager
'lockmanager-notlocked'        => '"$1" 경로의 잠금을 풀 수 없습니다. 해당 경로는 잠겨 있지 않습니다.',
'lockmanager-fail-closelock'   => '"$1"에 대한 잠금 파일을 닫지 못했습니다.',
'lockmanager-fail-deletelock'  => '"$1"에 대한 잠금 파일을 삭제하지 못했습니다.',
'lockmanager-fail-acquirelock' => '"$1"에 대한 잠금이 실패했습니다.',
'lockmanager-fail-openlock'    => '"$1"에 대한 잠금 파일을 열지 못했습니다.',
'lockmanager-fail-releaselock' => '"$1"에 대한 잠금을 해제하지 못했습니다.',
'lockmanager-fail-db-bucket'   => '데이터베이스의 버킷 $1의 잠금을 풀지 못했습니다.',
'lockmanager-fail-db-release'  => '데이터베이스 $1의 잠금을 풀지 못했습니다.',
'lockmanager-fail-svr-release' => '서버 $1의 잠금을 풀지 못했습니다.',

# ZipDirectoryReader
'zip-file-open-error' => 'ZIP 파일 검사를 위해 파일을 여는 도중 오류가 발생했습니다.',
'zip-wrong-format'    => '지정한 파일이 ZIP 파일이 아닙니다.',
'zip-bad'             => '이 ZIP 파일이 잘못되었거나 읽을 수 없습니다.
보안을 위한 검사를 정상적으로 수행할 수 없습니다.',
'zip-unsupported'     => '이 ZIP 파일은 미디어위키에서 지원하지 않는 기능을 사용하고 있습니다.
보안 검사를 정상적으로 수행할 수 없습니다.',

# Special:UploadStash
'uploadstash'          => '파일 올리기 임시 저장',
'uploadstash-summary'  => '이 문서는 위키에 등록되지는 않았지만 올리는 과정 중에 있는 파일을 접근할 수 있습니다. 이 파일은 올린이 외에는 볼 수 없습니다.',
'uploadstash-clear'    => '임시 저장한 파일 제거하기',
'uploadstash-nofiles'  => '임시 저장한 파일이 없습니다.',
'uploadstash-badtoken' => '이 동작을 수행하는 데 실패했습니다. 편집 토큰이 만료되었을 가능성이 있습니다. 다시 시도하세요.',
'uploadstash-errclear' => '파일을 제거하는 데 실패했습니다.',
'uploadstash-refresh'  => '파일 목록을 새로 고침',
'invalid-chunk-offset' => '청크 오프셋이 잘못되었습니다.',

# img_auth script messages
'img-auth-accessdenied'     => '접근 거부됨',
'img-auth-nopathinfo'       => 'PATH_INFO를 잃었습니다.
서버가 이 정보를 받을 수 있도록 설정되어 있지 않습니다.
이러한 경우는 서버가 CGI 기반이고 img_auth를 지원하지 않을 때 나타날 수 있습니다.
https://www.mediawiki.org/wiki/Manual:Image_Authorization 을 참고하십시오.',
'img-auth-notindir'         => '요청한 경로가 설정한 올리기 디렉토리에 없습니다.',
'img-auth-badtitle'         => '"$1"에서 올바른 제목을 만들 수 없습니다.',
'img-auth-nologinnWL'       => '로그인하지 않았으며 "$1" 파일은 화이트리스트에 존재하지 않습니다.',
'img-auth-nofile'           => '"$1" 파일이 없습니다.',
'img-auth-isdir'            => '"$1" 디렉토리에 접근을 시도했습니다.
파일에만 접근할 수 있습니다.',
'img-auth-streaming'        => '"$1" 파일을 전송하는 중입니다.',
'img-auth-public'           => 'img_auth.php는 개인 위키 파일을 바깥 사이트로 전송하는 기능입니다.
이 기능은 기본적으로 공개적인 위키에서 사용하도록 설계되어 있습니다.
보안적인 문제로 기본적으로 img_auth.php 기능은 비활성화되어 있습니다.',
'img-auth-noread'           => '"$1" 파일을 볼 권한이 없습니다.',
'img-auth-bad-query-string' => 'URL에 잘못된 쿼리 문자열이 있습니다.',

# HTTP errors
'http-invalid-url'      => '잘못된 URL: $1',
'http-invalid-scheme'   => '"$1"로 시작하는 URL은 지원되지 않습니다.',
'http-request-error'    => '알 수 없는 오류로 HTTP 요청에 실패했습니다.',
'http-read-error'       => 'HTTP 읽기 오류.',
'http-timed-out'        => 'HTTP 요청 시간 초과.',
'http-curl-error'       => 'URL 열기 오류: $1',
'http-host-unreachable' => 'URL에 접근하지 못했습니다.',
'http-bad-status'       => 'HTTP 요청 중 오류 발생: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL 접근 불가',
'upload-curl-error6-text'  => 'URL에 접근할 수 없습니다.
URL이 맞고 해당 웹사이트가 작동하는지 확인해주세요.',
'upload-curl-error28'      => '업로드 시간 초과',
'upload-curl-error28-text' => '사이트에서 응답하는 시간이 너무 깁니다.
사이트 접속이 가능한지 확인한 다음 다시 시도해주세요.
해당 사이트에 접속이 많을 경우 접속이 원활한 시간대에 시도해주세요.',

'license'            => '라이선스:',
'license-header'     => '라이선스',
'nolicense'          => '선택하지 않음',
'license-nopreview'  => '(미리 보기 불가능)',
'upload_source_url'  => ' (유효하고, 모든 사람이 접근 가능한 URL)',
'upload_source_file' => ' (당신의 컴퓨터에 있는 파일)',

# Special:ListFiles
'listfiles-summary'     => '이 위키에 올라와 있는 모든 파일이 나열되어 있습니다.
사용자별로 필터링했을 경우에는 사용자가 올린 가장 최신 버전만이 보여집니다.',
'listfiles_search_for'  => '다음 이름을 가진 미디어 찾기:',
'imgfile'               => '파일',
'listfiles'             => '파일 목록',
'listfiles_thumb'       => '섬네일',
'listfiles_date'        => '날짜',
'listfiles_name'        => '이름',
'listfiles_user'        => '사용자',
'listfiles_size'        => '크기',
'listfiles_description' => '설명',
'listfiles_count'       => '버전',

# File description page
'file-anchor-link'                  => '파일',
'filehist'                          => '파일 역사',
'filehist-help'                     => '날짜/시간 링크를 클릭하면 해당 시간의 파일을 볼 수 있습니다.',
'filehist-deleteall'                => '모두 삭제',
'filehist-deleteone'                => '삭제',
'filehist-revert'                   => '되돌리기',
'filehist-current'                  => '현재',
'filehist-datetime'                 => '날짜/시간',
'filehist-thumb'                    => '섬네일',
'filehist-thumbtext'                => '$1 판의 파일',
'filehist-nothumb'                  => '섬네일 없음',
'filehist-user'                     => '사용자',
'filehist-dimensions'               => '크기',
'filehist-filesize'                 => '파일 크기',
'filehist-comment'                  => '내용',
'filehist-missing'                  => '파일을 찾을 수 없음',
'imagelinks'                        => '이 파일을 사용하는 문서',
'linkstoimage'                      => '다음 문서 $1개가 이 파일을 사용하고 있습니다:',
'linkstoimage-more'                 => '$1개 이상의 문서가 이 파일을 가리키고 있습니다.
다음 목록은 이 파일을 가리키는 처음 $1개 문서만 보여주고 있습니다.
이 파일을 가리키는 모든 문서를 보려면 [[Special:WhatLinksHere/$2|여기]]를 참고해 주십시오.',
'nolinkstoimage'                    => '이 파일을 사용하는 문서가 없습니다.',
'morelinkstoimage'                  => '이 파일이 쓰이고 있는 문서 목록 [[Special:WhatLinksHere/$1|더 보기]].',
'linkstoimage-redirect'             => '$1 (파일 넘겨주기) $2',
'duplicatesoffile'                  => '다음 파일 $1개가 이 파일과 중복됩니다 ([[Special:FileDuplicateSearch/$2|자세한 정보]]):',
'sharedupload'                      => '이 파일은 $1으로부터 왔고, 다른 프로젝트에서 사용하고 있을 가능성이 있습니다.',
'sharedupload-desc-there'           => '이 파일은 $1에 있으며, 다른 프로젝트에서 사용하고 있을 가능성이 있습니다.
[$2 해당 파일]에 대한 자세한 정보를 확인해주세요.',
'sharedupload-desc-here'            => '이 파일은 $1에 있으며, 다른 프로젝트에서 사용하고 있을 가능성이 있습니다.
[$2 해당 파일]에 대한 설명이 아래에 나와 있습니다.',
'filepage-nofile'                   => '해당 이름으로 된 파일이 없습니다.',
'filepage-nofile-link'              => '해당 이름으로 된 파일이 없습니다. [$1 파일을 올릴 수] 있습니다.',
'uploadnewversion-linktext'         => '이 파일의 새로운 버전을 올리기',
'shared-repo-from'                  => '($1)',
'shared-repo'                       => '공용 저장소',
'shared-repo-name-wikimediacommons' => '위키미디어 공용',
'filepage.css'                      => '/* 이 CSS 설정은 파일 설명 문서에 포함되며, 또한 해외 클라이언트 위키에 포함됩니다 */',

# File reversion
'filerevert'                => '$1 되돌리기',
'filerevert-legend'         => '파일 되돌리기',
'filerevert-intro'          => "'''[[Media:$1|$1]]''' 파일을 [$4 $2 $3 버전]으로 되돌립니다.",
'filerevert-comment'        => '이유:',
'filerevert-defaultcomment' => '$1 $2 버전으로 되돌림',
'filerevert-submit'         => '되돌리기',
'filerevert-success'        => "'''[[Media:$1|$1]]''' 파일을 [$4 $2 $3 버전]으로 되돌렸습니다.",
'filerevert-badversion'     => '주어진 타임스탬프를 가진 파일의 로컬 버전이 없습니다.',

# File deletion
'filedelete'                   => '$1 삭제하기',
'filedelete-legend'            => '파일 삭제하기',
'filedelete-intro'             => "'''[[Media:$1|$1]]''' 파일과 모든 역사를 삭제합니다.",
'filedelete-intro-old'         => "'''[[Media:$1|$1]]''' 파일의 [$4 $2 $3] 버전을 삭제합니다.",
'filedelete-comment'           => '이유:',
'filedelete-submit'            => '삭제',
'filedelete-success'           => "'''$1''' 파일을 삭제했습니다.",
'filedelete-success-old'       => "'''[[Media:$1|$1]]''' 파일의 $2 $3 버전을 삭제했습니다.",
'filedelete-nofile'            => "'''$1''' 파일이 존재하지 않습니다.",
'filedelete-nofile-old'        => "해당 조건에 맞는 과거 '''$1''' 파일이 존재하지 않습니다.",
'filedelete-otherreason'       => '다른 이유/추가적인 이유:',
'filedelete-reason-otherlist'  => '다른 이유',
'filedelete-reason-dropdown'   => '*일반적인 삭제 이유
** 저작권 침해
** 중복된 파일',
'filedelete-edit-reasonlist'   => '삭제 이유 편집',
'filedelete-maintenance'       => '점검 중에는 임시적으로 삭제와 복구를 할 수 없습니다.',
'filedelete-maintenance-title' => '파일을 삭제할 수 없습니다',

# MIME search
'mimesearch'         => 'MIME 찾기',
'mimesearch-summary' => 'MIME 타입에 해당하는 파일을 찾습니다.
다음 형태로 입력해주세요: 내용종류/하위종류, 예를 들어 <code>image/jpeg</code>',
'mimetype'           => 'MIME 종류:',
'download'           => '다운로드',

# Unwatched pages
'unwatchedpages' => '주시되지 않는 문서 목록',

# List redirects
'listredirects' => '넘겨주기 문서 목록',

# Unused templates
'unusedtemplates'     => '사용하지 않는 틀 목록',
'unusedtemplatestext' => '다른 문서에서 사용하지 않는 {{ns:template}} 이름공간 문서의 목록입니다.
삭제하기 전에 사용 여부를 다시 확인해 주세요.',
'unusedtemplateswlh'  => '다른 링크',

# Random page
'randompage'         => '임의 문서로',
'randompage-nopages' => '{{PLURAL:$2|다음}} 이름공간에는 문서가 없습니다: $1',

# Random redirect
'randomredirect'         => '임의 넘겨주기 문서로',
'randomredirect-nopages' => '"$1" 이름공간에서 해당하는 넘겨주기 문서가 없습니다.',

# Statistics
'statistics'                   => '통계',
'statistics-header-pages'      => '문서 통계',
'statistics-header-edits'      => '편집 통계',
'statistics-header-views'      => '방문 통계',
'statistics-header-users'      => '사용자 통계',
'statistics-header-hooks'      => '기타 통계',
'statistics-articles'          => '일반 문서',
'statistics-pages'             => '전체 문서',
'statistics-pages-desc'        => '토론 문서, 넘겨주기 문서 등을 포함하는 모든 문서.',
'statistics-files'             => '올려져 있는 파일',
'statistics-edits'             => '{{SITENAME}} 설치 후 문서의 전체 편집 횟수',
'statistics-edits-average'     => '문서당 평균 편집 횟수',
'statistics-views-total'       => '총 방문 수',
'statistics-views-total-desc'  => '존재하지 않는 문서나 특수 문서에 대한 방문수는 집계하지 않았습니다.',
'statistics-views-peredit'     => '편집당 방문 횟수',
'statistics-users'             => '등록된 [[Special:ListUsers|사용자]]',
'statistics-users-active'      => '활동적인 사용자',
'statistics-users-active-desc' => '최근 $1일 동안 활동한 사용자',
'statistics-mostpopular'       => '가장 많이 읽힌 문서',

'disambiguations'      => '동음이의 문서를 가리키는 문서 목록',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "다음 문서는 적어도 하나 이상 '''동음이의 문서'''를 가리키고 있습니다.
그 링크는 다른 적절한 문서로 연결할 필요가 있습니다.<br />
[[MediaWiki:Disambiguationspage]]에서 링크된 틀을 사용하는 문서를 동음이의 문서로 간주합니다.",

'doubleredirects'                   => '이중 넘겨주기 목록',
'doubleredirectstext'               => '이 문서는 다른 넘겨주기 문서로 넘겨주고 있는 문서의 목록입니다.
매 줄에는 첫 번째 문서와 두 번째 문서의 링크가 있습니다. 그리고 보통 첫 번째 문서가 넘겨주어야 할 "실제" 문서인 두 번째 넘겨주기의 대상이 있습니다.
<del>취소선이 그어진</del> 부분은 이미 해결되었습니다.',
'double-redirect-fixed-move'        => '[[$1]] 문서를 옮겼습니다.
이 문서는 이제 [[$2]] 문서로 넘겨줍니다.',
'double-redirect-fixed-maintenance' => '[[$1]]에서 [[$2]]로 이중 넘겨주기를 고치는 중',
'double-redirect-fixer'             => '넘겨주기 수리꾼',

'brokenredirects'        => '끊긴 넘겨주기 목록',
'brokenredirectstext'    => '존재하지 않는 문서로 넘겨주기가 되어 있는 문서의 목록입니다:',
'brokenredirects-edit'   => '편집',
'brokenredirects-delete' => '삭제',

'withoutinterwiki'         => '언어 인터위키 링크가 없는 문서 목록',
'withoutinterwiki-summary' => '다른 언어로의 연결이 없는 문서의 목록입니다:',
'withoutinterwiki-legend'  => '접두어',
'withoutinterwiki-submit'  => '보기',

'fewestrevisions' => '편집 역사가 짧은 문서 목록',

# Miscellaneous special pages
'nbytes'                  => '$1 바이트',
'ncategories'             => '분류 $1개',
'nlinks'                  => '링크 $1개',
'nmembers'                => '문서 $1개',
'nrevisions'              => '편집 $1개',
'nviews'                  => '$1회 읽음',
'nimagelinks'             => '문서 $1개에서 사용 중',
'ntransclusions'          => '문서 $1개에서 사용 중',
'specialpage-empty'       => '명령에 대한 결과가 없습니다.',
'lonelypages'             => '외톨이 문서 목록',
'lonelypagestext'         => '{{SITENAME}}에서 다른 모든 문서에서 링크되거나 틀로 포함되지 않은 문서의 목록입니다.',
'uncategorizedpages'      => '분류되지 않은 문서 목록',
'uncategorizedcategories' => '분류되지 않은 분류 목록',
'uncategorizedimages'     => '분류되지 않은 파일 목록',
'uncategorizedtemplates'  => '분류되지 않은 틀 목록',
'unusedcategories'        => '사용하지 않는 분류 목록',
'unusedimages'            => '사용하지 않는 파일 목록',
'popularpages'            => '인기있는 문서 목록',
'wantedcategories'        => '필요한 분류 목록',
'wantedpages'             => '필요한 문서 목록',
'wantedpages-badtitle'    => '문서 제목이 잘못되었습니다: $1',
'wantedfiles'             => '필요한 파일 목록',
'wantedfiletext-cat'      => '다음 파일은 쓰이고는 있지만 없는 파일입니다. 바깥 저장소에 있는 파일은 실제로는 있지만 여기 올라 있을 수 있습니다. 그런 오류는 <del>삭제선</del>이 그어질 것입니다. 또한 없는 파일을 포함하고 있는 문서는 [[:$1]]에 올라 있습니다.',
'wantedfiletext-nocat'    => '다음 파일은 쓰이고는 있지만 없는 파일입니다. 바깥 저장소에 있는 파일은 실제로는 있지만 여기 올라 있을 수 있습니다. 그런 오류는 <del>삭제선</del>이 그어질 것입니다.',
'wantedtemplates'         => '필요한 틀 목록',
'mostlinked'              => '가장 많이 연결된 문서 목록',
'mostlinkedcategories'    => '가장 많이 연결된 분류 목록',
'mostlinkedtemplates'     => '가장 많이 사용된 틀 목록',
'mostcategories'          => '가장 많이 분류된 문서 목록',
'mostimages'              => '가장 많이 사용된 파일 목록',
'mostrevisions'           => '가장 많이 편집된 문서 목록',
'prefixindex'             => '접두어에 따른 문서 목록',
'prefixindex-namespace'   => '접두어가 있는 모든 문서 ($1 이름공간)',
'shortpages'              => '짧은 문서 목록',
'longpages'               => '긴 문서 목록',
'deadendpages'            => '막다른 문서 목록',
'deadendpagestext'        => '{{SITENAME}} 내의 다른 문서로 나가는 링크가 없는 문서의 목록입니다.',
'protectedpages'          => '보호된 문서 목록',
'protectedpages-indef'    => '오른쪽 조건에 맞는 보호만 보기',
'protectedpages-cascade'  => '연쇄적 보호만 보기',
'protectedpagestext'      => '다음 문서는 이동이나 편집이 불가능하도록 보호되어 있습니다.',
'protectedpagesempty'     => '보호되어 있는 문서가 없습니다.',
'protectedtitles'         => '만들기 보호된 표제어 목록',
'protectedtitlestext'     => '다음 표제어는 만들기가 금지되어 있습니다.',
'protectedtitlesempty'    => '해당 조건에 맞는 만들기 금지 표제어가 없습니다.',
'listusers'               => '사용자 목록',
'listusers-editsonly'     => '기여가 있는 사용자만 보기',
'listusers-creationsort'  => '계정 등록일 순으로 정렬',
'usereditcount'           => '편집 $1회',
'usercreated'             => '$1 $2에 계정 {{GENDER:$3|만들어짐}}',
'newpages'                => '새 문서 목록',
'newpages-username'       => '사용자 이름:',
'ancientpages'            => '오래된 문서 목록',
'move'                    => '이동',
'movethispage'            => '문서 이동하기',
'unusedimagestext'        => '다음은 어떤 문서도 사용하지 않는 파일의 목록입니다.
다른 사이트에서 URL 접근을 통해 파일을 사용할 수 있기 때문에, 아래 목록에 있는 파일도 실제로 사용 중일 가능성이 있다는 점을 주의해주세요.',
'unusedcategoriestext'    => '사용하지 않는 분류 문서의 목록입니다.',
'notargettitle'           => '해당하는 문서 없음',
'notargettext'            => '기능을 수행할 대상 문서나 사용자를 지정하지 않았습니다.',
'nopagetitle'             => '해당 문서 없음',
'nopagetext'              => '찾는 문서가 존재하지 않습니다.',
'pager-newer-n'           => '이전 $1개',
'pager-older-n'           => '다음 $1개',
'suppress'                => '오버사이트',
'querypage-disabled'      => '이 특수 문서는 성능상의 이유로 비활성화되었습니다.',

# Book sources
'booksources'               => '책 찾기',
'booksources-search-legend' => '책 찾기',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => '찾기',
'booksources-text'          => '아래의 목록은 새 책이나 중고 책을 판매하는 바깥 사이트로, 원하는 책의 정보를 얻을 수 있습니다:',
'booksources-invalid-isbn'  => '입력한 ISBN이 잘못된 것으로 보입니다. 원본과 대조해 보세요.',

# Special:Log
'specialloguserlabel'  => '작업 수행자:',
'speciallogtitlelabel' => '대상 (제목 또는 사용자):',
'log'                  => '기록 목록',
'all-logs-page'        => '모든 공개 기록',
'alllogstext'          => '{{SITENAME}}에서의 기록이 모두 나와 있습니다.
기록 종류, 사용자 이름, 문서 이름을 선택해서 볼 수 있습니다. (대소문자를 구별합니다.)',
'logempty'             => '일치하는 항목이 없습니다.',
'log-title-wildcard'   => '다음 글로 시작하는 제목 찾기',

# Special:AllPages
'allpages'          => '모든 문서 목록',
'alphaindexline'    => '$1부터 $2까지',
'nextpage'          => '다음 문서 ($1)',
'prevpage'          => '이전 문서 ($1)',
'allpagesfrom'      => '다음으로 시작하는 문서 보기:',
'allpagesto'        => '다음으로 끝나는 문서 보기:',
'allarticles'       => '모든 문서',
'allinnamespace'    => '$1 이름공간의 모든 문서',
'allnotinnamespace' => '$1 이름공간을 제외한 모든 문서',
'allpagesprev'      => '이전',
'allpagesnext'      => '다음',
'allpagessubmit'    => '보기',
'allpagesprefix'    => '다음으로 시작하는 문서 보기:',
'allpagesbadtitle'  => '문서 제목이 잘못되었거나 다른 사이트로 연결되는 인터위키를 가지고 있습니다.
문서 제목에 사용할 수 없는 문자를 사용했을 수 있습니다.',
'allpages-bad-ns'   => '{{SITENAME}}에서는 "$1" 이름공간을 사용하지 않습니다.',

# Special:Categories
'categories'                    => '분류',
'categoriespagetext'            => '{{PLURAL:$1}}문서나 자료를 담고 있는 분류 목록입니다.
[[Special:UnusedCategories|사용되지 않는 분류]]는 여기에 보이지 않습니다.
[[Special:WantedCategories|필요한 분류]]도 참고하세요.',
'categoriesfrom'                => '다음으로 시작하는 분류를 보여주기:',
'special-categories-sort-count' => '항목 갯수 순으로 정렬',
'special-categories-sort-abc'   => '알파벳순으로 정렬',

# Special:DeletedContributions
'deletedcontributions'             => '삭제된 기여 목록',
'deletedcontributions-title'       => '삭제된 기여 목록',
'sp-deletedcontributions-contribs' => '기여',

# Special:LinkSearch
'linksearch'       => '바깥 링크 찾기',
'linksearch-pat'   => '찾기 패턴:',
'linksearch-ns'    => '이름공간:',
'linksearch-ok'    => '찾기',
'linksearch-text'  => '"*.wikipedia.org"와 같이 와일드 카드를 사용할 수 있습니다.
적어도 "*.org"와 같이 최상위 도메인을 입력해야 합니다.<br />
지원하는 프로토콜: <code>$1</code> (프로토콜을 지정하지 않을 때 기본값은 http://)',
'linksearch-line'  => '$2에서 $1 을 링크하고 있습니다.',
'linksearch-error' => '와일드카드는 주소의 처음 부분에만 사용될 수 있습니다.',

# Special:ListUsers
'listusersfrom'      => '다음으로 시작하는 사용자 보기:',
'listusers-submit'   => '보기',
'listusers-noresult' => '해당 사용자가 없습니다.',
'listusers-blocked'  => '(차단됨)',

# Special:ActiveUsers
'activeusers'            => '활동적인 사용자 목록',
'activeusers-intro'      => '다음은 최근 $1일 동안 활동한 사용자의 목록입니다.',
'activeusers-count'      => '최근 $3일 사이의 편집 $1개',
'activeusers-from'       => '다음으로 시작하는 사용자를 보기:',
'activeusers-hidebots'   => '봇을 숨기기',
'activeusers-hidesysops' => '관리자를 숨기기',
'activeusers-noresult'   => '사용자가 없습니다.',

# Special:Log/newusers
'newuserlogpage'     => '사용자 등록 기록',
'newuserlogpagetext' => '사용자 등록 기록입니다.',

# Special:ListGroupRights
'listgrouprights'                      => '사용자 권한 목록',
'listgrouprights-summary'              => '다음은 이 위키에서 설정된 사용자 권한 그룹의 목록입니다.
각각의 권한에 대해서는 [[{{MediaWiki:Listgrouprights-helppage}}|이곳]]을 참고하세요.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">부여된 권한</span>
* <span class="listgrouprights-revoked">해제된 권한</span>',
'listgrouprights-group'                => '그룹',
'listgrouprights-rights'               => '권한',
'listgrouprights-helppage'             => 'Help:사용자 권한 그룹',
'listgrouprights-members'              => '(사용자 목록)',
'listgrouprights-addgroup'             => '{{PLURAL:$2|권한}} 부여: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|권한}} 회수: $1',
'listgrouprights-addgroup-all'         => '모든 권한을 부여',
'listgrouprights-removegroup-all'      => '모든 권한을 회수',
'listgrouprights-addgroup-self'        => '자신에게 다음 {{PLURAL:$2|권한}}을 부여: $1',
'listgrouprights-removegroup-self'     => '자신에게서 다음 {{PLURAL:$2|권한}}을 해제: $1',
'listgrouprights-addgroup-self-all'    => '자신에게 모든 권한을 부여',
'listgrouprights-removegroup-self-all' => '자신의 계정에서 모든 권한을 해제',

# E-mail user
'mailnologin'          => '보낼 이메일 주소가 없음',
'mailnologintext'      => '다른 사용자에게 이메일을 보내려면 [[Special:UserLogin|로그인]]한 다음 [[Special:Preferences|사용자 환경 설정]]에서 자신의 이메일 주소를 저장해야 합니다.',
'emailuser'            => '이메일 보내기',
'emailpage'            => '사용자에게 이메일 보내기',
'emailpagetext'        => '이 {{GENDER:$1|사용자}}가 환경 설정에 올바른 이메일 주소를 적었다면, 아래 양식을 통해 이메일을 보낼 수 있습니다.
이메일을 받은 사용자가 바로 답장할 수 있도록 하기 위해 [[Special:Preferences|사용자 환경 설정]]에 적은 이메일 주소가 "발신자" 정보에 들어갑니다. 따라서 수신자가 당신에게 직접 답장을 보낼 수 있습니다.',
'usermailererror'      => '메일 개체에서 오류 발생:',
'defemailsubject'      => '"$1" 사용자가 보낸 {{SITENAME}} 이메일',
'usermaildisabled'     => '사용자 이메일 비활성화됨',
'usermaildisabledtext' => '이 위키에서 다른 사용자에게 메일을 보낼 수 없습니다',
'noemailtitle'         => '이메일 주소 없음',
'noemailtext'          => '이 사용자는 올바른 이메일 주소를 입력하지 않았습니다.',
'nowikiemailtitle'     => '이메일이 허용되지 않음',
'nowikiemailtext'      => '이 사용자는 다른 사용자로부터의 이메일을 받지 않도록 설정하였습니다.',
'emailnotarget'        => '받는이로 없는 사용자를 지정하였거나 사용자 이름이 잘못되었습니다.',
'emailtarget'          => '수신자 사용자 이름 입력',
'emailusername'        => '사용자 이름:',
'emailusernamesubmit'  => '확인',
'email-legend'         => '{{SITENAME}}의 다른 사용자에게 이메일을 보내기',
'emailfrom'            => '이메일 발신자:',
'emailto'              => '수신자:',
'emailsubject'         => '제목:',
'emailmessage'         => '내용:',
'emailsend'            => '보내기',
'emailccme'            => '사본을 내 이메일로도 보내기',
'emailccsubject'       => '$1에게 보낸 메일 사본: $2',
'emailsent'            => '이메일 보냄',
'emailsenttext'        => '이메일을 보냈습니다.',
'emailuserfooter'      => '이 이메일은 {{SITENAME}}의 $1 사용자가 $2 사용자에게 "이메일 보내기" 기능을 통해 보냈습니다.',

# User Messenger
'usermessage-summary'  => '시스템 메시지 남기기',
'usermessage-editor'   => '시스템 메신저',
'usermessage-template' => 'MediaWiki:UserMessage',

# Watchlist
'watchlist'            => '주시문서 목록',
'mywatchlist'          => '주시문서 목록',
'watchlistfor2'        => '사용자:$1 $2',
'nowatchlist'          => '주시하는 문서가 아직 없습니다.',
'watchlistanontext'    => '주시문서 목록을 보거나 고치려면 $1 하세요.',
'watchnologin'         => '로그인하지 않음',
'watchnologintext'     => '주시문서 목록을 고치려면 [[Special:UserLogin|로그인]]해야 합니다.',
'addwatch'             => '주시문서 목록에 추가',
'addedwatchtext'       => "\"[[:\$1]]\" 문서를 [[Special:Watchlist|주시문서 목록]]에 추가했습니다.
앞으로 이 문서나 토론 문서가 바뀌면 [[Special:RecentChanges|최근 바뀜]]에서 알아보기 쉽게 '''굵은 글씨'''로 보일 것입니다.",
'removewatch'          => '주시문서 목록에서 제거',
'removedwatchtext'     => '"[[:$1]]" 문서를 [[Special:Watchlist|주시문서 목록]]에서 뺐습니다.',
'watch'                => '주시',
'watchthispage'        => '주시하기',
'unwatch'              => '주시 해제',
'unwatchthispage'      => '주시 해제하기',
'notanarticle'         => '문서가 아님',
'notvisiblerev'        => '이 판은 삭제되었습니다.',
'watchnochange'        => '주어진 기간 중에 바뀐 주시문서가 없습니다.',
'watchlist-details'    => '토론을 제외하고 문서 $1개를 주시하고 있습니다.',
'wlheader-enotif'      => '* 이메일 알림 기능이 활성화되었습니다.',
'wlheader-showupdated' => "* 마지막으로 방문한 이후에 바뀐 문서는 '''굵은 글씨'''로 보여집니다.",
'watchmethod-recent'   => '주시된 문서를 확인하고자 최근 편집을 확인',
'watchmethod-list'     => '최근 편집을 확인하고자 주시된 문서 확인',
'watchlistcontains'    => '문서 $1개를 주시하고 있습니다.',
'iteminvalidname'      => "'$1' 항목에 문제가 발생했습니다. 이름이 잘못되었습니다...",
'wlnote'               => "다음은 최근 '''$2'''시간 동안 바뀐 문서 '''$1'''개 입니다. ($3 $4 기준)",
'wlshowlast'           => '최근 $1시간 $2일 또는 $3 동안에 바뀐 문서',
'watchlist-options'    => '주시문서 목록 설정',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => '주시 추가 중…',
'unwatching'     => '주시 해제 중…',
'watcherrortext' => '"$1" 문서에 대한 주시 여부를 바꾸는 중 오류가 발생했습니다.',

'enotif_mailer'                => '{{SITENAME}} 자동 알림 메일',
'enotif_reset'                 => '모든 문서를 방문한 것으로 표시하기',
'enotif_newpagetext'           => '이 문서는 새 문서입니다.',
'enotif_impersonal_salutation' => '{{SITENAME}} 사용자',
'changed'                      => '바꾸었',
'created'                      => '만들었',
'enotif_subject'               => '{{SITENAME}}에서 $PAGEEDITOR 사용자가 $PAGETITLE 문서를 $CHANGEDORCREATED습니다.',
'enotif_lastvisited'           => '마지막으로 방문한 뒤 생긴 모든 바뀜 사항을 보려면 $1 을 보세요.',
'enotif_lastdiff'              => '이 바뀐 내용을 보려면 $1 을 보세요.',
'enotif_anon_editor'           => '익명 사용자 $1',
'enotif_body'                  => '$WATCHINGUSERNAME님,

{{SITENAME}}의 $PAGETITLE 문서를 $PAGEEDITDATE에 $PAGEEDITOR님이 $CHANGEDORCREATED었습니다. 현재의 문서는 $PAGETITLE_URL 에서 볼 수 있습니다.

$NEWPAGE

편집 요약: $PAGESUMMARY $PAGEMINOREDIT

다음을 통해 편집자와 대화를 할 수 있습니다:
이메일: $PAGEEDITOR_EMAIL
위키: $PAGEEDITOR_WIKI

이 문서를 열기 전에는 다른 알림 이메일을 더 이상 보내지 않습니다.
모든 주시 문서의 알림 딱지를 초기화할 수도 있습니다.

{{SITENAME}} 알림 시스템

--
이메일 알림 설정을 바꾸시려면 이곳을 방문해주세요:
{{canonicalurl:{{#special:Preferences}}}}

주시문서 설정을 바꾸려면 다음을 사용하세요:
{{canonicalurl:{{#special:EditWatchlist}}}}

주시문서에서 이 문서를 지우려면 이곳을 방문해주세요:
$UNWATCHURL

도움을 얻거나 피드백 하기:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => '문서 삭제하기',
'confirm'                => '확인',
'excontent'              => '내용: "$1"',
'excontentauthor'        => '내용: "$1" (유일한 편집자는 "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => '비우기 전의 내용: "$1"',
'exblank'                => '빈 문서',
'delete-confirm'         => '"$1" 삭제',
'delete-legend'          => '삭제',
'historywarning'         => "'''경고:''' 삭제하려는 문서에 과거 편집 내역 약 $1개가 있습니다:",
'confirmdeletetext'      => '문서와 문서 역사를 삭제하려고 합니다.
삭제하려는 문서가 맞는지, 이 문서를 삭제하는 것이 [[{{MediaWiki:Policy-url}}|정책]]에 맞는 행동인지를 확인해 주세요.',
'actioncomplete'         => '명령 완료',
'actionfailed'           => '명령 실패',
'deletedtext'            => '"$1" 문서를 삭제했습니다.
최근 삭제 기록은 $2에 있습니다.',
'dellogpage'             => '삭제 기록',
'dellogpagetext'         => '아래의 목록은 최근에 삭제된 문서입니다.',
'deletionlog'            => '삭제 기록',
'reverted'               => '이전 버전으로 되돌렸습니다.',
'deletecomment'          => '이유:',
'deleteotherreason'      => '다른 이유/추가적인 이유:',
'deletereasonotherlist'  => '다른 이유',
'deletereason-dropdown'  => '*일반적인 삭제 이유
** 작성자의 요청
** 저작권 침해
** 훼손 행위',
'delete-edit-reasonlist' => '삭제 이유 편집',
'delete-toobig'          => '이 문서에는 편집 역사가 $1개 있습니다.
편집 역사가 긴 문서를 삭제하면 {{SITENAME}}에 큰 혼란을 줄 수 있기 때문에 삭제할 수 없습니다.',
'delete-warning-toobig'  => '이 문서에는 편집 역사가 $1개 있습니다.
편집 역사가 긴 문서를 삭제하면 {{SITENAME}} 데이터베이스 동작에 큰 영향을 줄 수 있습니다.
주의해 주세요.',

# Rollback
'rollback'          => '편집 되돌리기',
'rollback_short'    => '되돌리기',
'rollbacklink'      => '되돌리기',
'rollbackfailed'    => '되돌리기 실패',
'cantrollback'      => '편집을 되돌릴 수 없습니다.
문서를 편집한 사용자가 한명뿐입니다.',
'alreadyrolled'     => '[[:$1]]에서 [[User:$2|$2]] ([[User talk:$2|토론]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]])의 편집을 되돌릴 수 없습니다.
누군가가 이미 문서를 고치거나 되돌렸습니다.

마지막으로 이 문서를 편집한 사용자는 [[User:$3|$3]] ([[User talk:$3|토론]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]])입니다.',
'editcomment'       => '편집 요약: "$1"',
'revertpage'        => '[[Special:Contributions/$2|$2]]([[User talk:$2|토론]])의 편집을 [[User:$1|$1]]의 마지막 버전으로 되돌림',
'revertpage-nouser' => '(사용자 이름 삭제됨)의 편집을 [[User:$1|$1]]의 마지막 편집으로 되돌림',
'rollback-success'  => '$1의 편집을 $2의 마지막 버전으로 되돌렸습니다.',

# Edit tokens
'sessionfailure-title' => '세션 손실',
'sessionfailure'       => '로그인 세션에 문제가 발생한 것 같습니다.
세션 하이재킹을 막기 위해 동작이 취소되었습니다.
브라우저의 뒤로 버튼을 누르고 문서를 새로 고침한 후에 다시 시도해 주세요.',

# Protect
'protectlogpage'              => '문서 보호 기록',
'protectlogtext'              => '아래의 목록은 문서 보호에 관한 바뀜 사항에 대한 기록입니다.
현재 보호된 문서의 목록에 대해서는 [[Special:ProtectedPages|보호된 문서 목록]]을 참고하세요.',
'protectedarticle'            => '사용자가 "[[$1]]" 문서를 보호함',
'modifiedarticleprotection'   => '사용자가 "[[$1]]" 문서의 보호 설정을 바꿈',
'unprotectedarticle'          => '사용자가 "[[$1]]" 문서를 보호 해제함',
'movedarticleprotection'      => '사용자가 문서의 보호 설정을 "[[$2]]"에서 "[[$1]]"으로 옮김',
'protect-title'               => '"$1" 보호하기',
'protect-title-notallowed'    => '"$1" 문서의 보호 수준 보기',
'prot_1movedto2'              => '[[$1]] 문서를 [[$2]] 문서로 이동함',
'protect-badnamespace-title'  => '보호할 수 없는 이름공간',
'protect-badnamespace-text'   => '이 이름공간에 있는 문서는 보호할 수 없습니다.',
'protect-legend'              => '보호 확인',
'protectcomment'              => '이유:',
'protectexpiry'               => '보호 기간:',
'protect_expiry_invalid'      => '보호 기간이 잘못되었습니다.',
'protect_expiry_old'          => '기한을 과거로 입력했습니다.',
'protect-unchain-permissions' => '다른 보호 설정을 수동으로 설정하기',
'protect-text'                => "'''$1''' 문서의 보호 수준을 보거나 바꿀 수 있습니다.",
'protect-locked-blocked'      => "차단된 동안에는 보호 설정을 바꿀 수 없습니다.
'''$1''' 문서의 보호 설정은 다음과 같습니다:",
'protect-locked-dblock'       => "데이터베이스가 잠겨 문서 보호 설정을 바꿀 수 없습니다.
'''$1''' 문서의 현재 설정은 다음과 같습니다:",
'protect-locked-access'       => "문서 보호 수준을 바꿀 권한이 없습니다.
'''$1''' 문서의 권한은 다음과 같습니다.",
'protect-cascadeon'           => '다음 {{PLURAL:$1|문서}}에 연쇄적 보호가 작동하고 있어 그 문서에 속한 이 문서도 현재 보호됩니다.
사용자는 이 문서의 보호 설정을 바꾸실 수 있지만 연쇄적 보호에는 영향을 주지 않습니다.',
'protect-default'             => '모든 사용자에게 허용',
'protect-fallback'            => '"$1" 권한 필요',
'protect-level-autoconfirmed' => '등록된 사용자만 가능',
'protect-level-sysop'         => '관리자만 가능',
'protect-summary-cascade'     => '연쇄적',
'protect-expiring'            => '$1 (UTC)에 만료',
'protect-expiring-local'      => '$1에 해제',
'protect-expiry-indefinite'   => '무기한',
'protect-cascade'             => '연쇄적 보호 - 이 문서에서 사용되는 다른 문서를 함께 보호합니다.',
'protect-cantedit'            => '이 문서의 보호 설정을 바꿀 권한이 없습니다.',
'protect-othertime'           => '다른 기간:',
'protect-othertime-op'        => '다른 기간',
'protect-existing-expiry'     => '현재 만료 기간: $2 $3',
'protect-otherreason'         => '다른 이유/추가적인 이유:',
'protect-otherreason-op'      => '다른 이유',
'protect-dropdown'            => '*일반적인 보호 이유
** 빈번한 훼손 행위
** 빈번한 광고 행위
** 비생산적인 편집 분쟁
** 방문이 많은 문서',
'protect-edit-reasonlist'     => '보호 이유 편집하기',
'protect-expiry-options'      => '1시간:1 hour,1일:1 day,1주일:1 week,2주일:2 weeks,1개월:1 month,3개월:3 months,6개월:6 months,1년:1 year,무기한:infinite',
'restriction-type'            => '권한:',
'restriction-level'           => '보호 수준:',
'minimum-size'                => '최소 크기',
'maximum-size'                => '최대 크기:',
'pagesize'                    => '(바이트)',

# Restrictions (nouns)
'restriction-edit'   => '편집',
'restriction-move'   => '이동',
'restriction-create' => '만들기',
'restriction-upload' => '올리기',

# Restriction levels
'restriction-level-sysop'         => '보호됨',
'restriction-level-autoconfirmed' => '준보호됨',
'restriction-level-all'           => '모두',

# Undelete
'undelete'                     => '삭제된 문서 보기',
'undeletepage'                 => '삭제된 문서를 보거나 되살리기',
'undeletepagetitle'            => "'''아래는 [[:$1|$1]]의 삭제된 판입니다.'''.",
'viewdeletedpage'              => '삭제된 문서 보기',
'undeletepagetext'             => '다음 {{PLURAL:$1|문서는|문서 $1개는}} 삭제되었지만 아직 보관되어 있고 되살릴 수 있습니다.
보관된 문서는 주기적으로 삭제될 것입니다.',
'undelete-fieldset-title'      => '문서 복구',
'undeleteextrahelp'            => "문서 역사 전체를 복구하려면 모든 체크박스의 선택을 해제하고 '''{{int:undeletebtn}}'''를 누르세요.
특정한 버전만 복구하려면 복구하려는 버전을 선택하고 '''{{int:undeletebtn}}'''를 누르세요.",
'undeleterevisions'            => '판 $1개 보관중',
'undeletehistory'              => '문서를 되살리면 모든 역사가 같이 복구됩니다.
문서가 삭제된 뒤 같은 이름의 문서가 만들어졌다면, 복구되는 역사는 지금 역사의 과거 부분에 나타날 것입니다.',
'undeleterevdel'               => '복구하려는 문서의 최신판이 삭제되어 있는 경우 문서를 복구시킬 수 없습니다.
이러한 경우, 삭제된 최신판 문서의 체크박스를 선택 해제하거나 숨김을 해제해야 합니다.',
'undeletehistorynoadmin'       => '이 문서는 삭제되었습니다.
삭제된 이유와 삭제되기 전에 이 문서를 편집한 사용자가 아래에 나와 있습니다.
삭제된 문서의 내용을 보려면 관리자 권한이 필요합니다.',
'undelete-revision'            => '삭제된 $1 문서의 $4 $5 버전 (기여자 $3):',
'undeleterevision-missing'     => '해당 판이 잘못되었거나 존재하지 않습니다.
잘못된 링크를 따라왔거나, 특정 판이 이미 복구되거나 데이터베이스에서 제거되었을 수도 있습니다.',
'undelete-nodiff'              => '이전의 판이 없습니다.',
'undeletebtn'                  => '복구',
'undeletelink'                 => '보기/되살리기',
'undeleteviewlink'             => '보기',
'undeletereset'                => '초기화',
'undeleteinvert'               => '선택 반전',
'undeletecomment'              => '이유:',
'undeletedrevisions'           => '판 $1개를 복구했습니다',
'undeletedrevisions-files'     => '판 $1개와 파일 $2개를 복구했습니다.',
'undeletedfiles'               => '파일 $1개를 복구했습니다',
'cannotundelete'               => '복구에 실패했습니다.
다른 사용자가 이미 복구했을 수도 있습니다.',
'undeletedpage'                => "'''$1 문서를 복구했습니다.'''

[[Special:Log/delete|삭제 기록]]에서 최근의 삭제와 복구 기록을 볼 수 있습니다.",
'undelete-header'              => '최근에 삭제한 문서에 대한 기록은 [[Special:Log/delete|여기]]에서 볼 수 있습니다.',
'undelete-search-title'        => '삭제된 문서 찾기',
'undelete-search-box'          => '삭제된 문서 찾기',
'undelete-search-prefix'       => '다음으로 시작하는 문서 보기:',
'undelete-search-submit'       => '찾기',
'undelete-no-results'          => '삭제된 문서 보존 자료에서 입력한 값에 맞는 문서가 없습니다.',
'undelete-filename-mismatch'   => '타임스탬프가 $1인 파일의 버전을 복구할 수 없습니다: 파일 이름이 일치하지 않습니다.',
'undelete-bad-store-key'       => '타임스탬프가 $1인 파일의 버전을 복구할 수 없습니다: 파일이 삭제되기 전에 사라졌습니다.',
'undelete-cleanup-error'       => '사용되지 않는 보존된 파일 "$1"을 삭제하는 데 오류가 발생했습니다.',
'undelete-missing-filearchive' => '데이터베이스에 존재하지 않기 때문에 파일 보존 ID가 $1인 파일을 복구할 수 없습니다.
이미 복구되었을 수 있습니다.',
'undelete-error'               => '문서 복구 중 오류',
'undelete-error-short'         => '파일 복구 오류: $1',
'undelete-error-long'          => '파일을 복구하는 동안 오류가 발생했습니다:

$1',
'undelete-show-file-confirm'   => '정말 "<nowiki>$1</nowiki>" 파일의 삭제된 $2 $3 버전을 보시겠습니까?',
'undelete-show-file-submit'    => '예',

# Namespace form on various pages
'namespace'                     => '이름공간:',
'invert'                        => '선택 반전',
'tooltip-invert'                => '선택한 이름공간에 있는 문서의 바뀜을 숨기려면 이 상자에 체크해주세요.',
'namespace_association'         => '관련된 이름공간',
'tooltip-namespace_association' => '선택한 이름공간과 관련된 토론이나 본문 이름공간을 같이 선택합니다.',
'blanknamespace'                => '(일반)',

# Contributions
'contributions'       => '사용자 기여',
'contributions-title' => '$1 사용자의 기여 목록',
'mycontris'           => '기여 목록',
'contribsub2'         => '$1($2)의 기여',
'nocontribs'          => '이 사용자는 아무 것도 기여하지 않았습니다.',
'uctop'               => '(최신)',
'month'               => '월:',
'year'                => '연도:',

'sp-contributions-newbies'             => '새 사용자의 기여만 보기',
'sp-contributions-newbies-sub'         => '새 사용자의 기여',
'sp-contributions-newbies-title'       => '새 사용자의 기여',
'sp-contributions-blocklog'            => '차단 기록',
'sp-contributions-deleted'             => '삭제된 기여 목록',
'sp-contributions-uploads'             => '파일 올리기',
'sp-contributions-logs'                => '기록',
'sp-contributions-talk'                => '토론',
'sp-contributions-userrights'          => '사용자 권한 관리',
'sp-contributions-blocked-notice'      => '이 사용자는 현재 차단되어 있습니다.
해당 사용자의 차단 기록은 다음과 같습니다:',
'sp-contributions-blocked-notice-anon' => '이 IP 주소는 현재 차단되어 있습니다.
차단 기록은 다음과 같습니다:',
'sp-contributions-search'              => '기여 찾기',
'sp-contributions-username'            => 'IP 주소 또는 사용자 이름:',
'sp-contributions-toponly'             => '최신판만 보기',
'sp-contributions-submit'              => '찾기',
'sp-contributions-explain'             => '',

# What links here
'whatlinkshere'            => '여기를 가리키는 문서',
'whatlinkshere-title'      => '"$1" 문서를 가리키는 문서 목록',
'whatlinkshere-page'       => '문서:',
'linkshere'                => "다음 문서가 '''[[:$1]]''' 문서를 가리키고 있습니다:",
'nolinkshere'              => "'''[[:$1]]''' 문서를 가리키는 문서가 없습니다.",
'nolinkshere-ns'           => "선택한 이름공간에는 '''[[:$1]]''' 문서를 가리키는 문서가 없습니다.",
'isredirect'               => '넘겨주기 문서',
'istemplate'               => '포함',
'isimage'                  => '파일 사용 중',
'whatlinkshere-prev'       => '{{PLURAL:$1|이전|이전 $1개}}',
'whatlinkshere-next'       => '{{PLURAL:$1|다음|다음 $1개}}',
'whatlinkshere-links'      => '← 가리키는 문서 목록',
'whatlinkshere-hideredirs' => '넘겨주기를 $1',
'whatlinkshere-hidetrans'  => '틀을 $1',
'whatlinkshere-hidelinks'  => '링크를 $1',
'whatlinkshere-hideimages' => '파일 링크를 $1',
'whatlinkshere-filters'    => '필터',

# Block/unblock
'autoblockid'                     => '자동 차단 #$1',
'block'                           => '사용자 차단',
'unblock'                         => '사용자 차단 해제',
'blockip'                         => '사용자 차단',
'blockip-title'                   => '특정 사용자를 차단하기',
'blockip-legend'                  => '사용자 차단',
'blockiptext'                     => '차단할 IP 주소나 사용자 이름을 아래에 적어 주세요.
차단은 문서 훼손을 막기 위해, [[{{MediaWiki:Policy-url}}|정책]]에 의해서만 이루어져야 합니다.
차단 이유를 같이 적어주세요(예: 특정 문서 훼손).',
'ipadressorusername'              => 'IP 주소 또는 사용자 이름:',
'ipbexpiry'                       => '기한:',
'ipbreason'                       => '이유:',
'ipbreasonotherlist'              => '다른 이유',
'ipbreason-dropdown'              => '*일반적인 차단 이유
** 거짓 정보 추가
** 문서 내용을 지움
** 문서에 광고성 링크를 만듦
** 장난 편집
** 협박성 행동
** 다중 계정 악용
** 부적절한 사용자 이름',
'ipb-hardblock'                   => '이 IP를 이용하는 로그인한 사용자가 편집하는 것을 막기',
'ipbcreateaccount'                => '계정 만들기를 막기',
'ipbemailban'                     => '이메일을 보내지 못하도록 막기',
'ipbenableautoblock'              => '이 사용자가 최근에 사용했거나 앞으로 사용하는 IP를 자동으로 막기',
'ipbsubmit'                       => '사용자 차단',
'ipbother'                        => '다른 기간:',
'ipboptions'                      => '2시간:2 hours,1일:1 day,3일:3 days,1주일:1 week,2주일:2 weeks,1개월:1 month,3개월:3 months,6개월:6 months,1년:1 year,무기한:infinite',
'ipbotheroption'                  => '수동으로 지정',
'ipbotherreason'                  => '다른 이유/추가적인 이유:',
'ipbhidename'                     => '사용자 이름을 편집 역사에서 숨기기',
'ipbwatchuser'                    => '이 사용자 문서와 사용자 토론 문서를 주시하기',
'ipb-disableusertalk'             => '차단된 동안 자신의 사용자 토론 문서를 편집하지 못하도록 막기',
'ipb-change-block'                => '이 설정으로 이 사용자를 다시 차단합니다',
'ipb-confirm'                     => '차단 확인',
'badipaddress'                    => '잘못된 IP 주소',
'blockipsuccesssub'               => '차단 완료',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] 사용자가 차단되었습니다.<br />
차단된 사용자 목록은 [[Special:BlockList|여기]]에서 볼 수 있습니다.',
'ipb-blockingself'                => '자기 자신을 차단하려고 합니다! 정말로 실행할까요?',
'ipb-confirmhideuser'             => '사용자를 차단하면서 "사용자 숨기기" 설정을 선택했습니다. 모든 기록에서 이 사용자의 사용자 이름을 숨기게 됩니다. 정말로 계정을 숨기시겠습니까?',
'ipb-edit-dropdown'               => '차단 이유 목록 편집하기',
'ipb-unblock-addr'                => '$1 차단 해제하기',
'ipb-unblock'                     => '사용자 또는 IP 주소 차단 해제하기',
'ipb-blocklist'                   => '현재 차단 기록 보기',
'ipb-blocklist-contribs'          => '$1의 기여',
'unblockip'                       => '사용자 차단 해제',
'unblockiptext'                   => '아래의 양식에 차단 해제하려는 IP 주소나 사용자 이름을 입력하세요.',
'ipusubmit'                       => '차단 해제',
'unblocked'                       => '[[User:$1|$1]] 사용자 차단 해제됨',
'unblocked-range'                 => '$1 대역이 차단 해제되었습니다.',
'unblocked-id'                    => '차단 $1 해제됨',
'blocklist'                       => '차단된 사용자 목록',
'ipblocklist'                     => '차단된 사용자',
'ipblocklist-legend'              => '차단 중인 사용자 찾기',
'blocklist-userblocks'            => '계정에 대한 차단 숨기기',
'blocklist-tempblocks'            => '기한이 정해진 차단을 숨기기',
'blocklist-addressblocks'         => '단일 IP 차단을 숨기기',
'blocklist-rangeblocks'           => '광역 차단을 숨기기',
'blocklist-timestamp'             => '시간 기록',
'blocklist-target'                => '차단 대상',
'blocklist-expiry'                => '차단 기한',
'blocklist-by'                    => '차단한 관리자',
'blocklist-params'                => '차단 설정',
'blocklist-reason'                => '이유',
'ipblocklist-submit'              => '찾기',
'ipblocklist-localblock'          => '로컬 차단',
'ipblocklist-otherblocks'         => '다른 {{PLURAL:$1|차단}} 기록',
'infiniteblock'                   => '무기한',
'expiringblock'                   => '$1 $2에 해제',
'anononlyblock'                   => '익명 사용자만',
'noautoblockblock'                => '자동 차단 비활성화됨',
'createaccountblock'              => '계정 만들기 금지됨',
'emailblock'                      => '이메일 차단됨',
'blocklist-nousertalk'            => '자신의 토론 문서 편집 불가',
'ipblocklist-empty'               => '차단된 사용자가 없습니다.',
'ipblocklist-no-results'          => '요청한 IP 주소나 사용자는 차단되지 않았습니다.',
'blocklink'                       => '차단',
'unblocklink'                     => '차단 해제',
'change-blocklink'                => '차단 설정 바꾸기',
'contribslink'                    => '기여',
'emaillink'                       => '이메일 보내기',
'autoblocker'                     => '당신의 IP 주소는 최근에 "[[User:$1|$1]]" 사용자가 사용하였기 때문에 자동으로 차단되었습니다.
$1 사용자가 차단된 이유는 다음과 같습니다: "$2"',
'blocklogpage'                    => '차단 기록',
'blocklog-showlog'                => '이 사용자는 과거에 차단된 기록이 있습니다.
해당 사용자의 차단 기록은 다음과 같습니다:',
'blocklog-showsuppresslog'        => '이 사용자는 과거에 차단된 적이 있으며, 그 기록이 숨겨져 있습니다.
해당 사용자의 차단 기록은 다음과 같습니다:',
'blocklogentry'                   => '사용자가 [[$1]] 사용자를 $2 차단함 $3',
'reblock-logentry'                => '사용자가 [[$1]] 사용자의 차단 기간을 $2(으)로 바꿈 $3',
'blocklogtext'                    => '이 목록은 사용자 차단/차단 해제 기록입니다.
자동으로 차단된 IP 주소는 여기에 나오지 않습니다.
[[Special:BlockList|여기]]에서 현재 차단된 사용자 목록을 볼 수 있습니다.',
'unblocklogentry'                 => '사용자가 $1 사용자를 차단 해제했습니다.',
'block-log-flags-anononly'        => 'IP만 막음',
'block-log-flags-nocreate'        => '계정 만들기 금지됨',
'block-log-flags-noautoblock'     => '자동 차단 비활성화됨',
'block-log-flags-noemail'         => '이메일 막음',
'block-log-flags-nousertalk'      => '자신의 토론 문서 편집 불가',
'block-log-flags-angry-autoblock' => '향상된 자동 차단 활성화됨',
'block-log-flags-hiddenname'      => '사용자 이름 숨겨짐',
'range_block_disabled'            => 'IP 범위 차단 기능이 비활성화되어 있습니다.',
'ipb_expiry_invalid'              => '차단 기간이 잘못되었습니다.',
'ipb_expiry_temp'                 => '사용자 이름을 숨기는 차단은 반드시 무기한이어야 합니다.',
'ipb_hide_invalid'                => '해당 계정은 막을 수 없습니다. 기여량이 너무 많습니다.',
'ipb_already_blocked'             => '"$1" 사용자는 이미 차단됨',
'ipb-needreblock'                 => '$1 사용자는 이미 차단되었습니다. 차단 설정을 바꾸시겠습니까?',
'ipb-otherblocks-header'          => '다른 {{PLURAL:$1|차단}} 기록',
'unblock-hideuser'                => '이 사용자 이름이 숨겨져 있기 때문에 이 사용자를 차단 해제할 수 없습니다.',
'ipb_cant_unblock'                => '오류: 차단 ID $1이(가) 존재하지 않습니다. 이미 차단 해제되었을 수 있습니다.',
'ipb_blocked_as_range'            => '오류: IP 주소 $1은 직접 차단되지 않았기 때문에 차단 해제할 수 없습니다.
하지만 $2로 광역 차단되었기 때문에, 광역 차단 해제로 차단을 해제할 수 있습니다.',
'ip_range_invalid'                => 'IP 범위가 잘못되었습니다.',
'ip_range_toolarge'               => '/$1보다 넓은 범위의 광역 차단을 할 수 없습니다.',
'blockme'                         => '자가 차단',
'proxyblocker'                    => '프록시 차단',
'proxyblocker-disabled'           => '이 기능은 비활성되어 있습니다.',
'proxyblockreason'                => '당신의 IP 주소는 공개 프록시로 밝혀져 자동으로 차단됩니다.
만약 인터넷 사용에 문제가 있다면 인터넷 서비스 공급자나 기술 지원팀에게 문의해주세요.',
'proxyblocksuccess'               => '완료.',
'sorbsreason'                     => '당신의 IP 주소는 {{SITENAME}}에서 사용하는 DNSBL 공개 프록시 목록에 들어 있습니다.',
'sorbs_create_account_reason'     => '당신의 IP 주소는 {{SITENAME}}에서 사용하는 DNSBL 공개 프록시 목록에 들어 있습니다.
계정을 만들 수 없습니다.',
'cant-block-while-blocked'        => '자신이 차단되어 있는 동안에는 다른 사용자를 차단할 수 없습니다.',
'cant-see-hidden-user'            => '차단하려 하는 사용자는 이미 차단되었고 숨김 처리되었습니다.
사용자 숨기기 권한을 갖고 있지 않기 때문에, 이 사용자의 차단 기록을 보거나 차단 설정을 바꿀 수 없습니다.',
'ipbblocked'                      => '자신이 차단되어 있기 때문에 다른 사용자를 차단하거나 차단을 해제할 수 없습니다.',
'ipbnounblockself'                => '자기 스스로를 차단 해제할 수 없습니다.',

# Developer tools
'lockdb'              => '데이터베이스 잠그기',
'unlockdb'            => '데이터베이스 잠금 해제',
'lockdbtext'          => '데이터베이스를 잠그면 모든 사용자의 편집, 환경 설정 바꾸기, 주시문서 편집 등 데이터베이스를 요구하는 모든 기능이 정지됩니다.
정말로 잠가야 하는지를 다시 한번 확인해주세요. 관리 작업이 끝난 뒤에는 데이터베이스 잠금을 풀어야 합니다.',
'unlockdbtext'        => '데이터베이스를 잠금 해제하면 모든 사용자의 편집, 환경 설정 바꾸기, 주시문서 편집 등 데이터베이스를 요구하는 모든 기능이 복구됩니다.
정말로 잠금을 해제하려는지를 다시 한번 확인해주세요.',
'lockconfirm'         => '네, 데이터베이스를 잠급니다.',
'unlockconfirm'       => '네, 데이터베이스를 잠금 해제합니다.',
'lockbtn'             => '데이터베이스 잠그기',
'unlockbtn'           => '데이터베이스 잠금 해제',
'locknoconfirm'       => '확인 상자를 선택하지 않았습니다.',
'lockdbsuccesssub'    => '데이터베이스 잠김',
'unlockdbsuccesssub'  => '데이터베이스 잠금 해제됨',
'lockdbsuccesstext'   => '데이터베이스가 잠겼습니다.<br />
관리가 끝나면 잊지 말고 [[Special:UnlockDB|잠금을 풀어]] 주세요.',
'unlockdbsuccesstext' => '데이터베이스 잠금 상태가 해제되었습니다.',
'lockfilenotwritable' => '데이터베이스 잠금 파일에 쓰기 권한이 없습니다.
데이터베이스를 잠그거나 잠금 해제하려면, 웹 서버에서 이 파일의 쓰기 권한을 설정해야 합니다.',
'databasenotlocked'   => '데이터베이스가 잠겨 있지 않습니다.',
'lockedbyandtime'     => '($1이 $2 $3에 잠금)',

# Move page
'move-page'                    => '$1 이동',
'move-page-legend'             => '문서 이동하기',
'movepagetext'                 => "아래의 양식을 사용해 문서의 이름을 바꾸고 문서의 모든 역사를 새 이름으로 옮길 수 있습니다.
이전의 제목은 새 제목으로 넘겨줄 것입니다.
원래 이름을 가리키는 넘겨주기를 자동으로 새로 고칠 수 있습니다.
만약 이 설정을 선택하지 않았다면 [[Special:DoubleRedirects|이중 넘겨주기]]와 [[Special:BrokenRedirects|끊긴 넘겨주기]]가 있는지 확인해주세요.
넘겨주기 링크가 제대로 향하고 있는지 확인하여야 합니다.

참고로 새 제목으로 된 문서가 이미 있을 때, 비어 있거나 넘겨주기 문서이고 문서 역사가 없을 때에만 이동하며 그렇지 않을 경우에는 이동하지 '''않습니다'''.
실수로 문서를 옮겼을 때 되돌릴 수는 있지만 이미 있는 문서를 덮어쓸 수 없음을 의미합니다.

'''경고!'''
인기 있는 문서일 경우 심각하고 예상하지 못한 문제를 초래할 수 있습니다.
문서를 이동하기 전에 이러한 행동이 초래할 수 있는 결과에 대해 숙지하시기 바랍니다.",
'movepagetext-noredirectfixer' => "아래의 양식을 사용해 문서의 이름을 바꾸고 문서의 모든 역사를 새 이름으로 옮길 수 있습니다.
이전의 제목은 새 제목으로 넘겨줄 것입니다.
[[Special:DoubleRedirects|이중 넘겨주기]]나 [[Special:BrokenRedirects|끊긴 넘겨주기]]가 있는지 확인해주세요.
넘겨주기 링크가 제대로 향하고 있는지 확인하여야 합니다.

참고로 새 제목으로 된 문서가 이미 있을 때, 비어 있거나 넘겨주기 문서이고 문서 역사가 없을 때에만 이동하며 그렇지 않을 경우에는 이동하지 '''않습니다'''.
실수로 문서를 옮겼을 때 되돌릴 수는 있지만 이미 있는 문서를 덮어쓸 수 없음을 의미합니다.

'''경고!'''
인기 있는 문서일 경우 심각하고 예상하지 못한 문제를 초래할 수 있습니다.
문서를 이동하기 전에 이러한 행동이 초래할 수 있는 결과에 대해 숙지하시기 바랍니다.",
'movepagetalktext'             => "딸린 토론 문서도 자동으로 이동합니다. 하지만 다음의 경우는 '''이동하지 않습니다''':
* 이동할 이름으로 된 문서가 이미 있는 경우
* 아래의 선택을 해제하는 경우

이 경우에는 문서를 직접 이동하거나 두 문서를 합쳐야 합니다.",
'movearticle'                  => '문서 이동하기',
'moveuserpage-warning'         => "'''경고:''' 사용자 문서를 옮기려 하고 있습니다. 사용자 문서만 이동되며 사용자 이름이 바뀌지 '''않는다'''는 점을 참고해주시기 바랍니다.",
'movenologin'                  => '로그인하지 않음',
'movenologintext'              => '문서를 이동하려면 [[Special:UserLogin|로그인]]해야 합니다.',
'movenotallowed'               => '문서를 이동할 권한이 없습니다.',
'movenotallowedfile'           => '파일을 이동할 권한이 없습니다.',
'cant-move-user-page'          => '사용자 문서를 이동할 권한이 없습니다(하위 문서는 예외).',
'cant-move-to-user-page'       => '문서를 사용자 문서로 이동할 권한이 없습니다(하위 문서는 예외).',
'newtitle'                     => '새 문서 이름',
'move-watch'                   => '문서 주시하기',
'movepagebtn'                  => '이동',
'pagemovedsub'                 => '문서 이동함',
'movepage-moved'               => '\'\'\'"$1" 문서를 "$2" 문서로 이동했습니다.\'\'\'',
'movepage-moved-redirect'      => '넘겨주기 문서를 만들었습니다.',
'movepage-moved-noredirect'    => '넘겨주기 문서를 남기지 않았습니다.',
'articleexists'                => '문서가 이미 존재하거나 이름이 올바르지 않습니다.
다른 제목으로 시도해주세요.',
'cantmove-titleprotected'      => '새로운 제목으로 문서를 만드는 것이 금지되어 있어 문서를 이동할 수 없습니다.',
'talkexists'                   => "'''문서는 이동되었습니다. 하지만 딸린 토론 문서의 새 이름으로 된 문서가 이미 있기 때문에 토론 문서는 옮기지 못했습니다. 직접 문서를 합쳐 주세요.'''",
'movedto'                      => '새 이름',
'movetalk'                     => '딸린 토론도 함께 이동합니다.',
'move-subpages'                => '하위 문서도 함께 ($1개 이하) 이동합니다.',
'move-talk-subpages'           => '토론 문서의 하위 문서도 ($1개까지) 함께 이동합니다.',
'movepage-page-exists'         => '이동할 수 없습니다. "$1" 문서가 이미 존재합니다.',
'movepage-page-moved'          => '"$1" 문서를 "$2" 문서로 이동했습니다.',
'movepage-page-unmoved'        => '"$1" 문서를 "$2" 문서로 이동할 수 없습니다.',
'movepage-max-pages'           => '문서를 최대 $1개 이동했습니다. 나머지 문서는 자동 이동하지 않습니다.',
'movelogpage'                  => '이동 기록',
'movelogpagetext'              => '아래는 이동한 문서의 목록입니다.',
'movesubpage'                  => '{{PLURAL:$1|하위 문서}}',
'movesubpagetext'              => '이 문서에는 다음 하위 문서 $1개가 있습니다.',
'movenosubpage'                => '이 문서에는 하위 문서가 존재하지 않습니다.',
'movereason'                   => '이유:',
'revertmove'                   => '되돌리기',
'delete_and_move'              => '삭제하고 이동',
'delete_and_move_text'         => '== 삭제 필요 ==
이동하려는 제목으로 된 "[[:$1]]" 문서가 이미 존재합니다.
삭제하고 이동할까요?',
'delete_and_move_confirm'      => '네. 문서를 삭제합니다',
'delete_and_move_reason'       => '"[[$1]]"에서 문서를 이동하기 위해 삭제함',
'selfmove'                     => '이동하려는 제목이 원래 제목과 같습니다.
이동할 수 없습니다.',
'immobile-source-namespace'    => '"$1" 이름공간에 속한 문서는 이동시킬 수 없습니다.',
'immobile-target-namespace'    => '"$1" 이름공간에 속한 문서는 이동시킬 수 없습니다.',
'immobile-target-namespace-iw' => '인터위키 링크를 넘어 문서를 이동할 수 없습니다.',
'immobile-source-page'         => '이 문서는 이동할 수 없습니다.',
'immobile-target-page'         => '새 이름으로 옮길 수 없습니다.',
'imagenocrossnamespace'        => '파일을 파일이 아닌 이름공간으로 옮길 수 없습니다.',
'nonfile-cannot-move-to-file'  => '파일이 아닌 문서를 파일 이름공간으로 옮길 수 없습니다.',
'imagetypemismatch'            => '새 파일의 확장자가 원래의 확장자와 일치하지 않습니다.',
'imageinvalidfilename'         => '새 파일 이름이 잘못되었습니다.',
'fix-double-redirects'         => '기존 이름을 가리키는 넘겨주기를 새로 고침',
'move-leave-redirect'          => '이동 후 넘겨주기를 남기기',
'protectedpagemovewarning'     => "'''경고:''' 이 문서는 관리자만이 이동할 수 있도록 잠겨 있습니다.
최근의 기록을 참고용으로 제공합니다:",
'semiprotectedpagemovewarning' => "'''참고:''' 이 문서는 등록된 사용자만이 이동할 수 있도록 잠겨 있습니다.
최근 기록 내용을 참고용로 제공합니다:",
'move-over-sharedrepo'         => '== 파일이 존재함 ==
[[:$1]] 파일이 공용 저장소에 있습니다. 이 이름으로 파일을 옮기면 공용의 파일을 덮어쓰게 될 것입니다.',
'file-exists-sharedrepo'       => '선택한 파일 이름은 공용 저장소에서 사용 중입니다.
다른 이름을 선택해주세요.',

# Export
'export'            => '문서 내보내기',
'exporttext'        => '특정 문서와 그 문서의 편집 역사를 XML 파일로 만들 수 있습니다. 이렇게 만들어진 파일은 다른 미디어위키에서 [[Special:Import|문서 가져오기]] 기능을 통해 가져갈 수 있습니다.

문서를 내보내려면, 내보내려는 문서 제목을 한 줄에 하나씩 입력해주세요. 그리고 문서의 전체 역사가 필요한지, 혹은 현재 버전만이 필요한지를 선택해 주세요.

특정 문서를 내보내려면, 예를 들어 "[[{{MediaWiki:Mainpage}}]]" 문서를 내보내려면 [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] 링크를 사용할 수도 있습니다.',
'exportall'         => '모든 문서를 내보내기',
'exportcuronly'     => '현재 버전만 포함하고, 전체 역사는 포함하지 않음',
'exportnohistory'   => "----
'''참고:''' 전체 문서 역사를 내보내는 기능은 성능 문제로 인해 비활성되어 있습니다.",
'exportlistauthors' => '각각 문서마다 모든 기여자의 목록을 포함',
'export-submit'     => '내보내기',
'export-addcattext' => '분류에 있는 문서 추가:',
'export-addcat'     => '추가',
'export-addnstext'  => '다음 이름공간을 가진 문서를 추가:',
'export-addns'      => '추가',
'export-download'   => '파일로 저장',
'export-templates'  => '틀 포함하기',
'export-pagelinks'  => '다음 단계로 링크된 문서를 포함:',

# Namespace 8 related
'allmessages'                   => '시스템 메시지 목록',
'allmessagesname'               => '이름',
'allmessagesdefault'            => '기본 내용',
'allmessagescurrent'            => '현재 문자열',
'allmessagestext'               => '미디어위키 이름공간에 있는 모든 시스템 메시지의 목록입니다.
미디어위키의 번역 작업에 관심이 있으면 [//www.mediawiki.org/wiki/Localisation 미디어위키 지역화]나 [//translatewiki.net translatewiki.net]에 참가해주세요.',
'allmessagesnotsupportedDB'     => "'''\$wgUseDatabaseMessages'''가 비활성화되어 있어서 이 문서를 사용할 수 없습니다.",
'allmessages-filter-legend'     => '필터',
'allmessages-filter'            => '수정 상태로 거르기:',
'allmessages-filter-unmodified' => '수정되지 않음',
'allmessages-filter-all'        => '모두',
'allmessages-filter-modified'   => '수정됨',
'allmessages-prefix'            => '접두어로 거르기:',
'allmessages-language'          => '언어:',
'allmessages-filter-submit'     => '실행',

# Thumbnails
'thumbnail-more'           => '실제 크기로',
'filemissing'              => '파일 사라짐',
'thumbnail_error'          => '섬네일을 만드는 중 오류 발생: $1',
'djvu_page_error'          => 'DjVu 페이지 범위 벗어남',
'djvu_no_xml'              => 'DjVu 파일의 XML 정보를 읽을 수 없음',
'thumbnail-temp-create'    => '임시 섬네일 파일을 만들 수 없습니다.',
'thumbnail-dest-create'    => '대상 경로에 섬네일을 저장할 수 없습니다.',
'thumbnail_invalid_params' => '섬네일 매개변수가 잘못되었습니다.',
'thumbnail_dest_directory' => '새 목적 디렉토리를 만들 수 없습니다.',
'thumbnail_image-type'     => '해당 파일 형식은 지원하지 않습니다',
'thumbnail_gd-library'     => 'GD 라이브러리 설정이 잘못되었습니다: $1 함수를 찾을 수 없습니다.',
'thumbnail_image-missing'  => '파일을 찾을 수 없습니다: $1',

# Special:Import
'import'                     => '문서 가져오기',
'importinterwiki'            => '다른 위키에서 문서 가져오기',
'import-interwiki-text'      => '문서를 가져올 위키를 선택하고 문서 제목을 입력해주세요.
편집 날짜와 편집자의 이름이 보존될 것입니다.
모든 가져오기는 [[Special:Log/import|가져오기 기록]]에 기록될 것입니다.',
'import-interwiki-source'    => '원본 위키/문서:',
'import-interwiki-history'   => '이 문서의 모든 역사를 가져오기',
'import-interwiki-templates' => '모든 틀을 포함하기',
'import-interwiki-submit'    => '가져오기',
'import-interwiki-namespace' => '새 이름공간:',
'import-upload-filename'     => '파일 이름:',
'import-comment'             => '이유:',
'importtext'                 => '원본 위키에서 [[Special:Export|내보내기]] 기능을 사용해 파일을 내려받으세요.
그리고 당신의 컴퓨터에 저장해 둔 후 여기에 올려주세요.',
'importstart'                => '문서를 가져오는 중...',
'import-revision-count'      => '판 $1개',
'importnopages'              => '가져올 문서가 없습니다.',
'imported-log-entries'       => '기록 항목 $1개를 가져왔습니다.',
'importfailed'               => '가져오기 실패: <nowiki>$1</nowiki>',
'importunknownsource'        => '알 수 없는 가져오기 자료 유형',
'importcantopen'             => '파일을 열 수 없습니다.',
'importbadinterwiki'         => '인터위키 링크가 잘못되었습니다.',
'importnotext'               => '내용이 없습니다.',
'importsuccess'              => '가져오기 완료!',
'importhistoryconflict'      => '문서 역사가 충돌하는 판이 있습니다. (이전에 이 문서를 가져온 적이 있을 수도 있습니다)',
'importnosources'            => '문서를 가져올 출처가 정의되지 않았고 문서 역사 올리기가 비활성화되었습니다.',
'importnofile'               => '가져오기용 파일이 올려지지 않았습니다.',
'importuploaderrorsize'      => '파일 올리기를 통한 가져오기에 실패했습니다.
파일이 허용된 크기 제한보다 큽니다.',
'importuploaderrorpartial'   => '가져오기 파일을 올리는 데 실패하였습니다.
파일이 부분적으로만 올려졌습니다.',
'importuploaderrortemp'      => '가져오기 파일을 올리는 데 실패했습니다.
임시 폴더가 존재하지 않습니다.',
'import-parse-failure'       => 'XML 문서 분석 실패',
'import-noarticle'           => '가져올 문서가 없습니다!',
'import-nonewrevisions'      => '이전에 이미 모든 판을 가져왔습니다.',
'xml-error-string'           => '$3단 $2줄 (바이트 $4)에서 $1: $5',
'import-upload'              => 'XML 데이터 올리기',
'import-token-mismatch'      => '세션 데이터가 손실되었습니다.
다시 시도하세요.',
'import-invalid-interwiki'   => '해당 위키에서 문서를 가져올 수 없습니다.',
'import-error-edit'          => '현재 문서를 편집할 권한이 없기 때문에 "$1" 문서를 불러올 수 없습니다.',
'import-error-create'        => '현재 문서를 만들 권한이 없기 때문에 "$1" 문서를 불러올 수 없습니다.',
'import-error-interwiki'     => '"$1" 문서는 제목이 바깥 링크(인터위키)용으로 할당되어 있기 때문에 가져오지 않습니다.',
'import-error-special'       => '"$1" 문서는 특수 문서에 속해 있기 때문에 가져오지 않습니다.',
'import-error-invalid'       => '"$1" 문서는 제목이 잘못되었기 때문에 가져오지 않습니다.',

# Import log
'importlogpage'                    => '가져오기 기록',
'importlogpagetext'                => '다른 위키에서 가져온 문서 기록입니다.',
'import-logentry-upload'           => '사용자가 파일 올리기를 통해 [[$1]] 문서를 가져왔습니다.',
'import-logentry-upload-detail'    => '판 $1개',
'import-logentry-interwiki'        => '$1 문서를 다른 위키에서 가져왔습니다.',
'import-logentry-interwiki-detail' => '$2에서 판 $1개를 가져옴',

# JavaScriptTest
'javascripttest'                           => '자바스크립트 테스트',
'javascripttest-disabled'                  => '이 기능은 비활성되어 있습니다.',
'javascripttest-title'                     => '$1 테스트 실행',
'javascripttest-pagetext-noframework'      => '이 페이지는 자바스크립트 테스트를 실행하기 위한 용도로 할당되어 있습니다.',
'javascripttest-pagetext-unknownframework' => '실험용 프레임워크 "$1"를 알 수 없습니다.',
'javascripttest-pagetext-frameworks'       => '다음 실험용 프레임워크 중 하나를 선택하세요: $1',
'javascripttest-pagetext-skins'            => '실험할 스킨을 선택하세요:',
'javascripttest-qunit-intro'               => 'mediawiki.org의 [$1 테스트 설명서]를 참고하세요.',
'javascripttest-qunit-heading'             => '미디어위키 자바스크립트 QUnit 실험군',

# Tooltip help for the actions
'tooltip-pt-userpage'                 => '내 사용자 문서',
'tooltip-pt-anonuserpage'             => '현재 사용하는 IP의 사용자 문서',
'tooltip-pt-mytalk'                   => '내 토론 문서',
'tooltip-pt-anontalk'                 => '현재 사용하는 IP를 위한 사용자 토론 문서',
'tooltip-pt-preferences'              => '사용자 환경 설정',
'tooltip-pt-watchlist'                => '주시문서에 대한 바뀜 목록',
'tooltip-pt-mycontris'                => '내가 편집한 글',
'tooltip-pt-login'                    => '꼭 로그인해야 하는 것은 아니지만, 로그인을 권장합니다.',
'tooltip-pt-anonlogin'                => '꼭 필요한 것은 아니지만, 로그인을 하면 편리한 점이 많습니다.',
'tooltip-pt-logout'                   => '로그아웃',
'tooltip-ca-talk'                     => '문서의 내용에 대한 토론 문서',
'tooltip-ca-edit'                     => '문서를 편집할 수 있습니다. 저장하기 전에 미리 보기를 해 주세요.',
'tooltip-ca-addsection'               => '문단 추가하기',
'tooltip-ca-viewsource'               => '문서가 잠겨 있습니다.
문서의 내용만 볼 수 있습니다.',
'tooltip-ca-history'                  => '문서의 과거 판',
'tooltip-ca-protect'                  => '문서 보호하기',
'tooltip-ca-unprotect'                => '이 문서의 보호 설정을 바꾸기',
'tooltip-ca-delete'                   => '문서 삭제하기',
'tooltip-ca-undelete'                 => '삭제되기 전에 이 문서의 완료한 편집 복구하기',
'tooltip-ca-move'                     => '문서 이동하기',
'tooltip-ca-watch'                    => '이 문서를 주시문서 목록에 추가합니다.',
'tooltip-ca-unwatch'                  => '이 문서를 주시문서 목록에서 제거합니다.',
'tooltip-search'                      => '{{SITENAME}} 찾기',
'tooltip-search-go'                   => '이 이름의 문서가 존재하면 그 문서로 바로 가기',
'tooltip-search-fulltext'             => '이 문자열이 포함된 문서 찾기',
'tooltip-p-logo'                      => '대문 방문하기',
'tooltip-n-mainpage'                  => '대문으로',
'tooltip-n-mainpage-description'      => '대문으로',
'tooltip-n-portal'                    => '프로젝트 소개, 여러분이 할 수 있는 것, 무언가를 찾는 곳',
'tooltip-n-currentevents'             => '최근의 소식을 봅니다',
'tooltip-n-recentchanges'             => '이 위키에서 최근 바뀐 내용의 목록',
'tooltip-n-randompage'                => '임의 문서로 갑니다',
'tooltip-n-help'                      => '도움말',
'tooltip-t-whatlinkshere'             => '여기로 연결된 모든 문서의 목록',
'tooltip-t-recentchangeslinked'       => '여기로 연결된 모든 문서의 바뀜 내역',
'tooltip-feed-rss'                    => '이 문서의 RSS 피드입니다.',
'tooltip-feed-atom'                   => '이 문서의 Atom 피드입니다.',
'tooltip-t-contributions'             => '이 사용자의 기여 목록을 봅니다.',
'tooltip-t-emailuser'                 => '이 사용자에게 이메일을 보냅니다.',
'tooltip-t-upload'                    => '파일을 올립니다.',
'tooltip-t-specialpages'              => '모든 특수 문서의 목록',
'tooltip-t-print'                     => '이 문서의 인쇄용 버전',
'tooltip-t-permalink'                 => '이 판에 대한 고유링크',
'tooltip-ca-nstab-main'               => '문서 내용을 봅니다.',
'tooltip-ca-nstab-user'               => '사용자 문서 내용을 봅니다.',
'tooltip-ca-nstab-media'              => '미디어 문서 내용을 봅니다.',
'tooltip-ca-nstab-special'            => '이 문서는 특수 문서로, 편집할 수 없습니다.',
'tooltip-ca-nstab-project'            => '프로젝트 문서 내용을 봅니다.',
'tooltip-ca-nstab-image'              => '파일 문서 내용을 봅니다.',
'tooltip-ca-nstab-mediawiki'          => '시스템 메시지 내용을 봅니다.',
'tooltip-ca-nstab-template'           => '틀 문서 내용을 봅니다.',
'tooltip-ca-nstab-help'               => '도움말 문서 내용을 봅니다.',
'tooltip-ca-nstab-category'           => '분류 문서 내용을 봅니다.',
'tooltip-minoredit'                   => '사소한 편집으로 표시하기',
'tooltip-save'                        => '편집 내용을 저장하기',
'tooltip-preview'                     => '편집 미리 보기. 저장하기 전에 꼭 미리 보기를 해 주세요!',
'tooltip-diff'                        => '자신이 바꾼 것 보기',
'tooltip-compareselectedversions'     => '이 문서에서 선택한 두 판간의 차이를 비교',
'tooltip-watch'                       => '이 문서를 주시문서 목록에 추가',
'tooltip-watchlistedit-normal-submit' => '항목 제거하기',
'tooltip-watchlistedit-raw-submit'    => '주시문서 목록 새로 고침',
'tooltip-recreate'                    => '문서를 편집하는 중 삭제되어도 새로 만들기',
'tooltip-upload'                      => '파일 올리기 시작',
'tooltip-rollback'                    => '"되돌리기" 기능을 사용하면 이 문서에 대한 마지막 기여자의 편집을 모두 되돌릴 수 있습니다.',
'tooltip-undo'                        => '"편집 취소" 기능을 사용하면 이 편집이 되돌려지고 차이 보기 기능이 미리 보기 형식으로 나타납니다. 편집 요약에 이 편집을 왜 되돌리는지에 대한 이유를 쓸 수 있습니다.',
'tooltip-preferences-save'            => '환경 설정 저장하기',
'tooltip-summary'                     => '짧은 편집 요약을 적어주세요',

# Stylesheets
'common.css'              => '/* 이 CSS 설정은 모든 스킨에 동일하게 적용됩니다 */',
'standard.css'            => '/* 이 CSS 설정은 모든 스탠다드 스킨에 적용됩니다 */',
'nostalgia.css'           => '/* 이 CSS 설정은 모든 노스텔지아 스킨에 적용됩니다 */',
'cologneblue.css'         => '/* 이 CSS 설정은 모든 쾰른 블루 스킨에 적용됩니다 */',
'monobook.css'            => '/* 이 CSS 설정은 모든 모노북 스킨에 적용됩니다 */',
'myskin.css'              => '/* 이 CSS 설정은 모든 마이스킨 스킨에 적용됩니다 */',
'chick.css'               => '/* 이 CSS 설정은 모든 치크 스킨에 적용됩니다 */',
'simple.css'              => '/* 이 CSS 설정은 모든 심플 스킨에 적용됩니다 */',
'modern.css'              => '/* 이 CSS 설정은 모든 모던 스킨에 적용됩니다 */',
'vector.css'              => '/* 이 CSS 설정은 모든 벡터 스킨에 적용됩니다 */',
'print.css'               => '/* 이 CSS 설정은 인쇄 출력 화면에 적용됩니다 */',
'handheld.css'            => '/* 이 CSS 설정은 $wgHandheldStyle에 설정한 스킨을 기반으로 한 휴대 기기에 적용됩니다 */',
'noscript.css'            => '/* 이 CSS 설정은 자바스크립트를 비활성화한 사용자에 적용됩니다 */',
'group-autoconfirmed.css' => '/* 이 CSS 설정은 자동 인증된 사용자에만 적용됩니다 */',
'group-bot.css'           => '/* 이 CSS 설정은 봇에만 적용됩니다 */',
'group-sysop.css'         => '/* 이 CSS 설정은 관리자에만 적용됩니다 */',
'group-bureaucrat.css'    => '/* 이 CSS 설정은 사무관에만 적용됩니다 */',

# Scripts
'common.js'              => '/* 이 자바스크립트 설정은 모든 문서, 모든 사용자에게 적용됩니다. */',
'standard.js'            => '/* 이 자바스크립트 설정은 스탠다드 스킨을 사용하는 사용자에게 적용됩니다 */',
'nostalgia.js'           => '/* 이 자바스크립트 설정은 노스텔지아 스킨을 사용하는 사용자에게 적용됩니다 */',
'cologneblue.js'         => '/* 이 자바스크립트 설정은 쾰른 블루 스킨을 사용하는 사용자에게 적용됩니다 */',
'monobook.js'            => '/* 이 자바스크립트 설정은 모노북 스킨을 사용하는 사용자에게 적용됩니다 */',
'myskin.js'              => '/* 이 자바스크립트 설정은 마이스킨 스킨을 사용하는 사용자에게 적용됩니다 */',
'chick.js'               => '/* 이 자바스크립트 설정은 치크 스킨을 사용하는 사용자에게 적용됩니다 */',
'simple.js'              => '/* 이 자바스크립트 설정은 심플 스킨을 사용하는 사용자에게 적용됩니다 */',
'modern.js'              => '/* 이 자바스크립트 설정은 모던 스킨을 사용하는 사용자에게 적용됩니다 */',
'vector.js'              => '/* 이 자바스크립트 설정은 벡터 스킨을 사용하는 사용자에게 적용됩니다 */',
'group-autoconfirmed.js' => '/* 이 자바스크립트 설정은 자동 인증된 사용자에만 적용됩니다 */',
'group-bot.js'           => '/* 이 자바스크립트 설정은 봇에만 적용됩니다 */',
'group-sysop.js'         => '/* 이 자바스크립트 설정은 관리자에만 적용됩니다 */',
'group-bureaucrat.js'    => '/* 이 자바스크립트 설정은 사무관에만 적용됩니다 */',

# Metadata
'notacceptable' => '클라이언트에서 인식 가능한 출력 포맷이 없습니다.',

# Attribution
'anonymous'        => '{{SITENAME}} 익명 {{PLURAL:$1|사용자}}',
'siteuser'         => '{{SITENAME}} 사용자 $1',
'anonuser'         => '{{SITENAME}} 익명 사용자 $1',
'lastmodifiedatby' => '이 문서는 $3 사용자가 $1 $2에 마지막으로 바꾸었습니다.',
'othercontribs'    => '$1의 작업을 바탕으로 합니다.',
'others'           => '기타',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|사용자}} $1',
'anonusers'        => '{{SITENAME}} 익명 {{PLURAL:$2|사용자}} $1',
'creditspage'      => '문서 기여자',
'nocredits'        => '이 문서에서는 기여자 정보가 없습니다.',

# Spam protection
'spamprotectiontitle' => '스팸 방지 필터',
'spamprotectiontext'  => '스팸 필터가 문서 저장을 막았습니다.
바깥 사이트로 연결하는 링크 중에 블랙리스트에 포함된 사이트가 있을 것입니다.',
'spamprotectionmatch' => '문제가 되는 부분은 다음과 같습니다: $1',
'spambot_username'    => 'MediaWiki 스팸 제거',
'spam_reverting'      => '$1을 포함하지 않는 최신 버전으로 되돌림',
'spam_blanking'       => '모든 버전에 $1 링크를 포함하고 있어 차단함',

# Info page
'pageinfo-title'            => '"$1" 문서에 대한 정보',
'pageinfo-header-edits'     => '편집 역사',
'pageinfo-header-watchlist' => '주시 현황',
'pageinfo-header-views'     => '보기 현황',
'pageinfo-subjectpage'      => '문서',
'pageinfo-talkpage'         => '토론 문서',
'pageinfo-watchers'         => '문서를 주시하는 사용자 수',
'pageinfo-edits'            => '편집 수',
'pageinfo-authors'          => '총 서로 다른 편집자 수',
'pageinfo-views'            => '읽힌 횟수',
'pageinfo-viewsperedit'     => '읽힌 횟수/편집 수',

# Skin names
'skinname-standard'    => '클래식',
'skinname-nostalgia'   => '노스탤지아',
'skinname-cologneblue' => '쾰른 블루',
'skinname-monobook'    => '모노북',
'skinname-myskin'      => '마이스킨',
'skinname-chick'       => '치크',
'skinname-simple'      => '심플',
'skinname-modern'      => '모던',
'skinname-vector'      => '벡터',

# Patrolling
'markaspatrolleddiff'                 => '검토한 문서로 표시',
'markaspatrolledtext'                 => '이 문서를 검토한 것으로 표시',
'markedaspatrolled'                   => '검토한 문서로 표시',
'markedaspatrolledtext'               => '[[:$1]] 문서의 선택한 판을 검토한 것으로 표시하였습니다.',
'rcpatroldisabled'                    => '최근 바뀜 검토 기능 비활성화됨',
'rcpatroldisabledtext'                => '최근 바뀜 검토 기능은 현재 비활성화되어 있습니다.',
'markedaspatrollederror'              => '검토한 것으로 표시할 수 없습니다.',
'markedaspatrollederrortext'          => '검토한 것으로 표시할 판을 지정해야 합니다.',
'markedaspatrollederror-noautopatrol' => '자신의 편집은 스스로 검토할 수 없습니다.',

# Patrol log
'patrol-log-page'      => '검토 기록',
'patrol-log-header'    => '문서 검토에 대한 기록입니다.',
'log-show-hide-patrol' => '검토 기록을 $1',

# Image deletion
'deletedrevision'                 => '예전 $1 판이 삭제되었습니다.',
'filedeleteerror-short'           => '파일 삭제 오류: $1',
'filedeleteerror-long'            => '파일을 삭제하는 도중 오류가 발생했습니다:

$1',
'filedelete-missing'              => '"$1" 파일을 삭제할 수 없습니다. 파일이 존재하지 않습니다.',
'filedelete-old-unregistered'     => '입력한 파일의 "$1" 판이 데이터베이스에 존재하지 않습니다.',
'filedelete-current-unregistered' => '"$1" 이라는 이름을 가진 파일이 데이터베이스에 존재하지 않습니다.',
'filedelete-archive-read-only'    => '웹 서버의 "$1" 파일 저장 위치에 쓰기 권한이 없습니다.',

# Browsing diffs
'previousdiff' => '← 이전 편집',
'nextdiff'     => '다음 편집 →',

# Media information
'mediawarning'           => "'''경고''': 이 파일에 악성 코드가 포함되어 있을 수 있습니다.
파일을 실행하면 컴퓨터에 문제가 생길 가능성이 있습니다.",
'imagemaxsize'           => '그림 최대 크기:<br />(파일 문서에 적용되는 기능)',
'thumbsize'              => '섬네일 크기:',
'widthheightpage'        => '$1 × $2, $3페이지',
'file-info'              => '파일 크기: $1, MIME 종류: $2',
'file-info-size'         => '$1 × $2 픽셀, 파일 크기: $3, MIME 종류: $4',
'file-info-size-pages'   => '$1 × $2 픽셀, 파일 크기: $3, MIME 형식: $4, $5{{PLURAL:$5|쪽}}',
'file-nohires'           => '최대 해상도입니다.',
'svg-long-desc'          => 'SVG 파일, 실제 크기 $1 × $2 픽셀, 파일 크기: $3',
'show-big-image'         => '최대 해상도',
'show-big-image-preview' => '미리 보기 크기: $1.',
'show-big-image-other'   => '다른 {{PLURAL:$2|해상도}}: $1.',
'show-big-image-size'    => '$1 × $2 픽셀',
'file-info-gif-looped'   => '반복됨',
'file-info-gif-frames'   => '$1 프레임',
'file-info-png-looped'   => '반복됨',
'file-info-png-repeat'   => '$1번 재생됨',
'file-info-png-frames'   => '$1 프레임',

# Special:NewFiles
'newimages'             => '새 파일 목록',
'imagelisttext'         => "파일 '''$1'''개를 $2 순으로 정렬한 목록입니다.",
'newimages-summary'     => '이 특수 문서는 최근에 올라온 파일을 나열하고 있습니다.',
'newimages-legend'      => '필터',
'newimages-label'       => '파일 이름 (또는 그 일부분):',
'showhidebots'          => '(봇을 $1)',
'noimages'              => '그림이 없습니다.',
'ilsubmit'              => '찾기',
'bydate'                => '날짜',
'sp-newimages-showfrom' => '$1 $2부터 올라온 파일 목록 보기',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '$1초',
'minutes' => '$1분',
'hours'   => '$1시간',
'days'    => '$1일',
'ago'     => '$1 전',

# Bad image list
'bad_image_list' => '형식은 아래와 같습니다.

"*"로 시작하는 목록의 내용만 적용됩니다.
매 줄의 첫번째 링크는 부적절한 파일을 가리켜야 합니다.
같은 줄에 따라오는 모든 링크는 예외로 봅니다. (예: 파일이 사용되어야 하는 문서)',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '간체',
'variantname-zh-hant' => '번체',

# Metadata
'metadata'          => '메타데이터',
'metadata-help'     => '이 파일은 카메라나 스캐너에서 기록한 부가 정보를 가지고 있습니다.
프로그램에서 파일을 편집할 경우, 새로 저장한 그림 파일에 일부 부가 정보가 빠질 수 있습니다.',
'metadata-expand'   => '자세한 정보 보이기',
'metadata-collapse' => '자세한 정보 숨기기',
'metadata-fields'   => '파일 메타데이터 표가 접혀 있을 때, 이 메시지에 올라와 있는 다음 속성값만이 기본적으로 보이게 됩니다.
나머지 값은 자동적으로 숨겨집니다.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => '너비',
'exif-imagelength'                 => '높이',
'exif-bitspersample'               => '픽셀당 비트 수',
'exif-compression'                 => '압축 방식',
'exif-photometricinterpretation'   => '픽셀 배열',
'exif-orientation'                 => '방향',
'exif-samplesperpixel'             => '화소 수',
'exif-planarconfiguration'         => '데이터 정렬 방식',
'exif-ycbcrsubsampling'            => 'Y와 C의 축소 비율',
'exif-ycbcrpositioning'            => 'Y와 C 위치',
'exif-xresolution'                 => '수평 해상도',
'exif-yresolution'                 => '수직 해상도',
'exif-stripoffsets'                => '그림 데이터 위치',
'exif-rowsperstrip'                => '스트립당 줄의 수',
'exif-stripbytecounts'             => '압축된 스트립당 바이트 수',
'exif-jpeginterchangeformat'       => 'JPEG SOI와의 차이',
'exif-jpeginterchangeformatlength' => 'JPEG 데이터 바이트 수',
'exif-whitepoint'                  => '화이트 포인트 색도',
'exif-primarychromaticities'       => '색도의 우선 색',
'exif-ycbcrcoefficients'           => '색 공간 변환 표 계수',
'exif-referenceblackwhite'         => '흑백 값에 대한 정보',
'exif-datetime'                    => '파일이 바뀐 날짜와 시간',
'exif-imagedescription'            => '그림 제목',
'exif-make'                        => '카메라 제조사',
'exif-model'                       => '카메라 모델',
'exif-software'                    => '사용한 소프트웨어',
'exif-artist'                      => '저작자',
'exif-copyright'                   => '저작권자',
'exif-exifversion'                 => 'Exif 버전',
'exif-flashpixversion'             => '지원하는 플래시픽스 버전',
'exif-colorspace'                  => '색 공간',
'exif-componentsconfiguration'     => '각 구성 요소의 의미',
'exif-compressedbitsperpixel'      => '그림 압축 방식',
'exif-pixelydimension'             => '그림 너비',
'exif-pixelxdimension'             => '그림 높이',
'exif-usercomment'                 => '사용자 주',
'exif-relatedsoundfile'            => '관련된 오디오 파일',
'exif-datetimeoriginal'            => '날짜와 시간',
'exif-datetimedigitized'           => '날짜와 시간(디지털 데이터)',
'exif-subsectime'                  => '파일을 수정한 날짜와 시간 (초단위 미만)',
'exif-subsectimeoriginal'          => '파일을 만든 날짜와 시간 (초단위 미만)',
'exif-subsectimedigitized'         => '디지털화된 날짜와 시간 (초단위 미만)',
'exif-exposuretime'                => '노출 시간',
'exif-exposuretime-format'         => '$1초 ($2)',
'exif-fnumber'                     => 'F 번호',
'exif-exposureprogram'             => '노출 프로그램',
'exif-spectralsensitivity'         => '스펙트럼 감도',
'exif-isospeedratings'             => 'ISO 속도',
'exif-shutterspeedvalue'           => 'APEX 셔터 속도',
'exif-aperturevalue'               => 'APEX 조리개',
'exif-brightnessvalue'             => 'APEX 밝기',
'exif-exposurebiasvalue'           => '노출 보정값',
'exif-maxaperturevalue'            => '최대 조리개 값 (최소 F 값)',
'exif-subjectdistance'             => '대상과의 거리',
'exif-meteringmode'                => '측광 방식',
'exif-lightsource'                 => '광원',
'exif-flash'                       => '플래시',
'exif-focallength'                 => '렌즈 초점 거리',
'exif-subjectarea'                 => '대상 위치',
'exif-flashenergy'                 => '플래시 광량',
'exif-focalplanexresolution'       => '초점면 X방향 해상도',
'exif-focalplaneyresolution'       => '초점면 Y방향 해상도',
'exif-focalplaneresolutionunit'    => '초점면 해상도 단위',
'exif-subjectlocation'             => '대상 위치',
'exif-exposureindex'               => '노출 값',
'exif-sensingmethod'               => '감지 방식',
'exif-filesource'                  => '파일 출처',
'exif-scenetype'                   => '촬영 모드',
'exif-customrendered'              => '그림 처리 방식',
'exif-exposuremode'                => '노출 방식',
'exif-whitebalance'                => '화이트 밸런스',
'exif-digitalzoomratio'            => '디지털 줌 비율',
'exif-focallengthin35mmfilm'       => '35 mm 필름에서의 초점 거리',
'exif-scenecapturetype'            => '장면 포착 방식',
'exif-gaincontrol'                 => '장면 제어',
'exif-contrast'                    => '대비',
'exif-saturation'                  => '채도',
'exif-sharpness'                   => '선명도',
'exif-devicesettingdescription'    => '장치 설정에 대한 설명',
'exif-subjectdistancerange'        => '대상과의 거리 범위',
'exif-imageuniqueid'               => '그림 고유 ID',
'exif-gpsversionid'                => 'GPS 태그 버전',
'exif-gpslatituderef'              => '북위 또는 남위',
'exif-gpslatitude'                 => '위도',
'exif-gpslongituderef'             => '동경 또는 서경',
'exif-gpslongitude'                => '경도',
'exif-gpsaltituderef'              => '고도 정보',
'exif-gpsaltitude'                 => '고도',
'exif-gpstimestamp'                => 'GPS 시간 (원자 시계)',
'exif-gpssatellites'               => '측정에 사용된 위성',
'exif-gpsstatus'                   => '수신기 상태',
'exif-gpsmeasuremode'              => '측정 방식',
'exif-gpsdop'                      => '측정 정확도',
'exif-gpsspeedref'                 => '속도 단위',
'exif-gpsspeed'                    => 'GPS 수신기 속도',
'exif-gpstrackref'                 => '이동 방향에 대한 정보',
'exif-gpstrack'                    => '이동 방향',
'exif-gpsimgdirectionref'          => '그림 방향에 대한 정보',
'exif-gpsimgdirection'             => '그림 방향',
'exif-gpsmapdatum'                 => '측지 조사 데이처 사용',
'exif-gpsdestlatituderef'          => '목적지의 위도 정보',
'exif-gpsdestlatitude'             => '목적지의 위도',
'exif-gpsdestlongituderef'         => '목적지의 경도 정보',
'exif-gpsdestlongitude'            => '목적지의 경도',
'exif-gpsdestbearingref'           => '목적지의 방향에 대한 정보',
'exif-gpsdestbearing'              => '목적지의 방향',
'exif-gpsdestdistanceref'          => '목적지까지의 거리 정보',
'exif-gpsdestdistance'             => '목적지와의 거리',
'exif-gpsprocessingmethod'         => 'GPS 처리 방식의 이름',
'exif-gpsareainformation'          => 'GPS 구역 이름',
'exif-gpsdatestamp'                => 'GPS 날짜',
'exif-gpsdifferential'             => 'GPS 차이 보정',
'exif-jpegfilecomment'             => 'JPEG 파일의 주석',
'exif-keywords'                    => '핵심 단어',
'exif-worldregioncreated'          => '사진을 촬영한 곳의 대륙/지역',
'exif-countrycreated'              => '사진을 촬영한 곳의 국가',
'exif-countrycodecreated'          => '사진을 촬영한 곳의 국가 ISO 코드',
'exif-provinceorstatecreated'      => '사진을 촬영한 지역(주/도 단위)',
'exif-citycreated'                 => '사진을 촬영한 지역(시/군 단위)',
'exif-sublocationcreated'          => '사진을 촬영한 곳의 세부 지역',
'exif-worldregiondest'             => '사진 속 대륙/지역',
'exif-countrydest'                 => '사진 속 국가',
'exif-countrycodedest'             => '사진 속 국가의 ISO 코드',
'exif-provinceorstatedest'         => '사진 속 지역(주/도 단위)',
'exif-citydest'                    => '사진 속 지역(시/군 단위)',
'exif-sublocationdest'             => '사진 속 세부 지역',
'exif-objectname'                  => '짧은 제목',
'exif-specialinstructions'         => '사진 이용에 대한 특이 사항',
'exif-headline'                    => '표제어',
'exif-credit'                      => '기여자/제공자',
'exif-source'                      => '출처',
'exif-editstatus'                  => '그림의 편집/구성',
'exif-urgency'                     => '긴급',
'exif-fixtureidentifier'           => '고정 이름',
'exif-locationdest'                => '장소',
'exif-locationdestcode'            => '장소의 위치 코드(ISO, XSP 등)',
'exif-objectcycle'                 => '미디어 파일이 의도하는 시간대',
'exif-contact'                     => '연락처 정보',
'exif-writer'                      => '작성자',
'exif-languagecode'                => '언어',
'exif-iimversion'                  => 'IIM 버전',
'exif-iimcategory'                 => '분류',
'exif-iimsupplementalcategory'     => '보조 분류',
'exif-datetimeexpires'             => '사용 기한',
'exif-datetimereleased'            => '발표된 날짜',
'exif-originaltransmissionref'     => '원본 전송 위치 코드',
'exif-identifier'                  => '식별자',
'exif-lens'                        => '사용된 렌즈',
'exif-serialnumber'                => '카메라 일련 번호',
'exif-cameraownername'             => '카메라 소유자',
'exif-label'                       => '라벨',
'exif-datetimemetadata'            => '메타데이터 최종 수정일',
'exif-nickname'                    => '그림의 비공식적 이름',
'exif-rating'                      => '평가 (5점 만점)',
'exif-rightscertificate'           => '권리 관리 인증서',
'exif-copyrighted'                 => '저작권 정보',
'exif-copyrightowner'              => '저작권자',
'exif-usageterms'                  => '이용 조건',
'exif-webstatement'                => '온라인 저작권 선언',
'exif-originaldocumentid'          => '원본 문서의 고유 ID',
'exif-licenseurl'                  => '저작권 라이선스의 URL',
'exif-morepermissionsurl'          => '다른 라이선스 정보',
'exif-attributionurl'              => '이 저작물을 이용할 때 링크할 주소',
'exif-preferredattributionname'    => '이 저작물을 이용할 때 보일 저작자 이름',
'exif-pngfilecomment'              => 'PNG 파일 주석',
'exif-disclaimer'                  => '면책 조항',
'exif-contentwarning'              => '콘텐츠 경고',
'exif-giffilecomment'              => 'GIF 파일 주석',
'exif-intellectualgenre'           => '항목 종류',
'exif-subjectnewscode'             => '주제 코드',
'exif-scenecode'                   => 'IPTC 장면 코드',
'exif-event'                       => '묘사된 사건',
'exif-organisationinimage'         => '묘사된 기관',
'exif-personinimage'               => '묘사된 사람',
'exif-originalimageheight'         => '자르기 전 그림의 세로 길이',
'exif-originalimagewidth'          => '자르기 전 그림의 가로 길이',

# EXIF attributes
'exif-compression-1'     => '압축되지 않음',
'exif-compression-2'     => 'CCITT 그룹-3 1차원 수정 허프먼 반복 길이 부호화',
'exif-compression-3'     => 'CCITT 그룹-3 팩스 인코딩',
'exif-compression-4'     => 'CCITT 그룹-4 팩스 인코딩',
'exif-compression-6'     => 'JPEG (오래됨)',
'exif-compression-8'     => '수축 (Adobe)',
'exif-compression-32773' => 'PackBits (매킨토시 RLE)',
'exif-compression-32946' => '수축 (PKZIP)',

'exif-copyrighted-true'  => '저작권의 보호를 받음',
'exif-copyrighted-false' => '퍼블릭 도메인',

'exif-unknowndate' => '날짜를 알 수 없음',

'exif-orientation-1' => '일반',
'exif-orientation-2' => '수평으로 뒤집음',
'exif-orientation-3' => '180° 회전됨',
'exif-orientation-4' => '수직으로 뒤집음',
'exif-orientation-5' => '시계 반대 방향으로 90° 회전하고 수직으로 뒤집음',
'exif-orientation-6' => '반시계 방향으로 90° 회전함',
'exif-orientation-7' => '시계 방향으로 90° 회전하고 수직으로 뒤집음',
'exif-orientation-8' => '시계 방향으로 90° 회전됨',

'exif-planarconfiguration-1' => '덩어리 형식',
'exif-planarconfiguration-2' => '평면형',

'exif-colorspace-65535' => '조정되지 않음',

'exif-componentsconfiguration-0' => '존재하지 않음',

'exif-exposureprogram-0' => '정의되지 않음',
'exif-exposureprogram-1' => '수동',
'exif-exposureprogram-2' => '일반 프로그램',
'exif-exposureprogram-3' => '조리개 우선',
'exif-exposureprogram-4' => '셔터 우선',
'exif-exposureprogram-5' => '크리에이티브 프로그램 (피사계 심도 우선)',
'exif-exposureprogram-6' => '액션 프로그램 (빠른 셔터 속도에 치중)',
'exif-exposureprogram-7' => '인물 사진 모드 (배경을 초점 밖으로 하여 대상을 강조)',
'exif-exposureprogram-8' => '풍경 모드 (초점이 배경인 풍경 사진용)',

'exif-subjectdistance-value' => '$1 미터',

'exif-meteringmode-0'   => '알 수 없음',
'exif-meteringmode-1'   => '평균 측광',
'exif-meteringmode-2'   => '중앙 중점 평균 측광',
'exif-meteringmode-3'   => '스팟 측광',
'exif-meteringmode-4'   => '멀티스팟 측광',
'exif-meteringmode-5'   => '평가 측광',
'exif-meteringmode-6'   => '부분',
'exif-meteringmode-255' => '기타',

'exif-lightsource-0'   => '알 수 없음',
'exif-lightsource-1'   => '태양광',
'exif-lightsource-2'   => '형광등',
'exif-lightsource-3'   => '텅스텐 (백열광)',
'exif-lightsource-4'   => '플래시',
'exif-lightsource-9'   => '맑은 날씨',
'exif-lightsource-10'  => '흐린 날씨',
'exif-lightsource-11'  => '그늘',
'exif-lightsource-12'  => '주광색 형광등 (D 5700 – 7100K)',
'exif-lightsource-13'  => '주백색 형광등 (N 4600 – 5400K)',
'exif-lightsource-14'  => '냉백색 형광등 (W 3900 – 4500K)',
'exif-lightsource-15'  => '백색 형광등 (WW 3200 – 3700K)',
'exif-lightsource-17'  => '표준 A',
'exif-lightsource-18'  => '표준 B',
'exif-lightsource-19'  => '표준 C',
'exif-lightsource-24'  => 'ISO 스튜디오 백열광',
'exif-lightsource-255' => '다른 광원',

# Flash modes
'exif-flash-fired-0'    => '플래시가 터지지 않음',
'exif-flash-fired-1'    => '플래시 터짐',
'exif-flash-return-0'   => '플래시 반사광 감지 기능을 사용하지 않음',
'exif-flash-return-2'   => '플래시 반사광이 감지되지 않음',
'exif-flash-return-3'   => '플래시 반사광이 감지됨',
'exif-flash-mode-1'     => '플래시 강제',
'exif-flash-mode-2'     => '플래시 억제',
'exif-flash-mode-3'     => '자동 모드',
'exif-flash-function-1' => '플래시 기능 없음',
'exif-flash-redeye-1'   => '적목 방지 모드',

'exif-focalplaneresolutionunit-2' => '인치',

'exif-sensingmethod-1' => '정의되지 않음',
'exif-sensingmethod-2' => '1칩 색 공간 센서',
'exif-sensingmethod-3' => '2칩 색 공간 센서',
'exif-sensingmethod-4' => '3칩 색 공간 센서',
'exif-sensingmethod-5' => '순차적 색 공간 센서',
'exif-sensingmethod-7' => '3선 센서',
'exif-sensingmethod-8' => '순차적 색 공간 선형 센서',

'exif-filesource-3' => '디지털 정지 카메라',

'exif-scenetype-1' => '직접 촬영한 그림',

'exif-customrendered-0' => '일반',
'exif-customrendered-1' => '사용자 정의',

'exif-exposuremode-0' => '자동 노출',
'exif-exposuremode-1' => '수동 노출',
'exif-exposuremode-2' => '자동 노출 브래킷',

'exif-whitebalance-0' => '자동 화이트 밸런스',
'exif-whitebalance-1' => '수동 화이트 밸런스',

'exif-scenecapturetype-0' => '표준',
'exif-scenecapturetype-1' => '풍경',
'exif-scenecapturetype-2' => '인물 사진',
'exif-scenecapturetype-3' => '야경 사진',

'exif-gaincontrol-0' => '없음',
'exif-gaincontrol-1' => '약하게 증가',
'exif-gaincontrol-2' => '강하게 증가',
'exif-gaincontrol-3' => '약하게 감소',
'exif-gaincontrol-4' => '강하게 감소',

'exif-contrast-0' => '보통',
'exif-contrast-1' => '부드러움',
'exif-contrast-2' => '강함',

'exif-saturation-0' => '보통',
'exif-saturation-1' => '저채도',
'exif-saturation-2' => '고채도',

'exif-sharpness-0' => '보통',
'exif-sharpness-1' => '부드러움',
'exif-sharpness-2' => '강함',

'exif-subjectdistancerange-0' => '알 수 없음',
'exif-subjectdistancerange-1' => '접사',
'exif-subjectdistancerange-2' => '근거리',
'exif-subjectdistancerange-3' => '원거리',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '북위',
'exif-gpslatitude-s' => '남위',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '동경',
'exif-gpslongitude-w' => '서경',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '해발 $1미터',
'exif-gpsaltitude-below-sealevel' => '해저 $1미터',

'exif-gpsstatus-a' => '측정 중',
'exif-gpsstatus-v' => '인터랙티브 측정',

'exif-gpsmeasuremode-2' => '2차원 측정',
'exif-gpsmeasuremode-3' => '3차원 측정',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => '킬로미터 매 시간',
'exif-gpsspeed-m' => '마일 매 시간',
'exif-gpsspeed-n' => '노트',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => '킬로미터',
'exif-gpsdestdistance-m' => '마일',
'exif-gpsdestdistance-n' => '해리',

'exif-gpsdop-excellent' => '우수 ($1)',
'exif-gpsdop-good'      => '좋음 ($1)',
'exif-gpsdop-moderate'  => '보통 ($1)',
'exif-gpsdop-fair'      => '적당 ($1)',
'exif-gpsdop-poor'      => '나쁨 ($1)',

'exif-objectcycle-a' => '오전 중에만',
'exif-objectcycle-p' => '오후 중에만',
'exif-objectcycle-b' => '오전, 오후 모두',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '실제 방위',
'exif-gpsdirection-m' => '자기 방위',

'exif-ycbcrpositioning-1' => '중앙',
'exif-ycbcrpositioning-2' => '코사이티드',

'exif-dc-contributor' => '조력자',
'exif-dc-coverage'    => '미디어의 시공간적 범위',
'exif-dc-date'        => '날짜',
'exif-dc-publisher'   => '출판사',
'exif-dc-relation'    => '관련된 미디어',
'exif-dc-rights'      => '권리',
'exif-dc-source'      => '원본 출처 미디어',
'exif-dc-type'        => '미디어 종류',

'exif-rating-rejected' => '거부',

'exif-isospeedratings-overflow' => '65535 이상',

'exif-iimcategory-ace' => '예술, 문화, 엔터테인먼트',
'exif-iimcategory-clj' => '범죄와 법률',
'exif-iimcategory-dis' => '재난 및 사고',
'exif-iimcategory-fin' => '경제 및 비즈니스',
'exif-iimcategory-edu' => '교육',
'exif-iimcategory-evn' => '환경',
'exif-iimcategory-hth' => '건강',
'exif-iimcategory-hum' => '인간의 흥미',
'exif-iimcategory-lab' => '노동',
'exif-iimcategory-lif' => '생활 방식과 레저',
'exif-iimcategory-pol' => '정치',
'exif-iimcategory-rel' => '종교 및 신념',
'exif-iimcategory-sci' => '과학 기술',
'exif-iimcategory-soi' => '사회적 문제',
'exif-iimcategory-spo' => '스포츠',
'exif-iimcategory-war' => '전쟁, 분쟁과 사회 불안',
'exif-iimcategory-wea' => '날씨',

'exif-urgency-normal' => '보통 ($1)',
'exif-urgency-low'    => '낮음 ($1)',
'exif-urgency-high'   => '높음 ($1)',
'exif-urgency-other'  => '사용자 정의 ($1)',

# External editor support
'edit-externally'      => '이 파일을 바깥 프로그램을 사용해서 편집하기',
'edit-externally-help' => '(자세한 정보는 [//www.mediawiki.org/wiki/Manual:External_editors 설치 방법]을 참고하세요)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => '모든 기간',
'namespacesall' => '모두',
'monthsall'     => '모든 달',
'limitall'      => '모두',

# E-mail address confirmation
'confirmemail'              => '이메일 주소 확인',
'confirmemail_noemail'      => '[[Special:Preferences|환경 설정]]에 이메일을 설정하지 않았습니다.',
'confirmemail_text'         => '{{SITENAME}}에서는 이메일 기능을 사용하기 전에 이메일 인증을 받아야 합니다.
아래의 버튼을 누르면 인증 메일을 보냅니다.
메일에는 인증 코드가 들어있는 링크가 있습니다.
그 링크를 웹 브라우저로 열면 인증이 완료됩니다.',
'confirmemail_pending'      => '이미 확인 이메일을 보냈습니다.
계정을 최근에 만들었다면 이메일을 보내는 데에 몇 분이 걸릴 수 있으므로 잠시 후에 다시 확인해 주세요.',
'confirmemail_send'         => '인증 코드를 메일로 보내기',
'confirmemail_sent'         => '인증 이메일을 보냈습니다.',
'confirmemail_oncreate'     => '확인 이메일을 보냈습니다.
이 확인 과정은 로그인하는 데에 필요하지는 않지만, 위키 프로그램에서 제공하는 이메일 기능을 사용하기 위해서 필요합니다.',
'confirmemail_sendfailed'   => '{{SITENAME}}에서 인증 이메일을 보낼 수 없습니다.
이메일 주소를 잘못 입력했는지 확인해주세요.

메일 서버로부터의 응답: $1',
'confirmemail_invalid'      => '인증 코드가 올바르지 않습니다.
인증 코드가 만료되었을 수도 있습니다.',
'confirmemail_needlogin'    => '이메일 주소를 인증하려면 $1이 필요합니다.',
'confirmemail_success'      => '이메일 주소가 인증되었습니다.
이제 [[Special:UserLogin|로그인]]해서 위키를 사용하세요.',
'confirmemail_loggedin'     => '이메일 주소가 인증되었습니다.',
'confirmemail_error'        => '당신의 인증을 저장하는 도중 오류가 발생했습니다.',
'confirmemail_subject'      => '{{SITENAME}} 이메일 주소 인증',
'confirmemail_body'         => '$1 IP 주소를 사용하는 사용자가
{{SITENAME}}의 "$2" 계정에 이메일 인증 신청을 했습니다.

이 계정이 당신의 계정이고 {{SITENAME}}에서 이메일 기능을 활성화하려면
아래 주소를 열어서 이메일 인증을 해 주세요:

$3

당신의 계정이 아니라면,
이메일 인증 신청을 취소하기 위해 아래의 주소를 열어주세요:

$5

인증 코드는 $4에 만료됩니다.',
'confirmemail_body_changed' => '$1 IP 주소를 사용하는 사용자가
{{SITENAME}}의 "$2" 계정의 이메일 주소를 바꾸었습니다.

이 계정이 당신의 계정이고 {{SITENAME}}에서 이메일 기능을 활성화하려면
아래 주소를 열어서 이메일 인증을 해 주세요:

$3

당신의 계정이 아니라면,
이메일 인증 신청을 취소하기 위해 아래의 주소를 열어주세요:

$5

인증 코드는 $4에 만료됩니다.',
'confirmemail_body_set'     => 'IP 주소 $1을 사용하는 사용자가
{{SITENAME}}의 "$2" 계정의 이메일 주소를 지정하였습니다.

이 계정이 당신의 계정이고 {{SITENAME}}에서 이메일 기능을
다시 활성화하려면 아래 주소를 열어서 이메일 인증을 해 주세요:

$3

당신의 계정이 아니라면,
이메일 인증 신청을 취소하기 위해 아래의 주소를 열어주세요:

$5

인증 코드는 $4에 만료됩니다.',
'confirmemail_invalidated'  => '이메일 확인이 취소됨',
'invalidateemail'           => '이메일 확인 취소',

# Scary transclusion
'scarytranscludedisabled' => '[인터위키가 비활성되어 있습니다]',
'scarytranscludefailed'   => '[$1 틀을 불러오는 데에 실패했습니다]',
'scarytranscludetoolong'  => '[URL이 너무 깁니다]',

# Delete conflict
'deletedwhileediting'      => "'''경고''': 이 문서를 편집하던 중에 이 문서가 삭제되었습니다!",
'confirmrecreate'          => '[[User:$1|$1]] 사용자([[User talk:$1|토론]])가 당신이 편집하는 도중에 문서를 삭제했습니다. 삭제 이유는 다음과 같습니다:
: $2
문서를 다시 만들어야 하는지 확인해주세요.',
'confirmrecreate-noreason' => '[[User:$1|$1]] 사용자([[User talk:$1|토론]])가 당신이 편집하는 도중에 문서를 삭제했습니다. 문서를 다시 만들어야 하는지 확인해주세요.',
'recreate'                 => '새로 만들기',

# action=purge
'confirm_purge_button' => '확인',
'confirm-purge-top'    => '문서의 캐시를 지울까요?',
'confirm-purge-bottom' => '문서를 새로 고침하는 것은 캐시를 새로 고치고 가장 최근의 판이 나타나게 할 것입니다.',

# action=watch/unwatch
'confirm-watch-button'   => '확인',
'confirm-watch-top'      => '이 문서를 주시문서 목록에 추가할까요?',
'confirm-unwatch-button' => '확인',
'confirm-unwatch-top'    => '이 문서를 주시문서 목록에서 뺄까요?',

# Multipage image navigation
'imgmultipageprev' => '← 이전 페이지',
'imgmultipagenext' => '다음 페이지 →',
'imgmultigo'       => '이동!',
'imgmultigoto'     => '$1 페이지로 가기',

# Table pager
'ascending_abbrev'         => '오름차순',
'descending_abbrev'        => '내림차순',
'table_pager_next'         => '다음 문서',
'table_pager_prev'         => '이전 문서',
'table_pager_first'        => '처음 문서',
'table_pager_last'         => '마지막 문서',
'table_pager_limit'        => '문서당 $1개 항목 보이기',
'table_pager_limit_label'  => '문서당 항목 수:',
'table_pager_limit_submit' => '확인',
'table_pager_empty'        => '결과 없음',

# Auto-summaries
'autosumm-blank'   => '문서를 비움',
'autosumm-replace' => '문서 내용을 "$1"으로 바꿈',
'autoredircomment' => '[[$1]] 문서로 넘겨주기',
'autosumm-new'     => '새 문서: $1',

# Live preview
'livepreview-loading' => '불러오는 중...',
'livepreview-ready'   => '불러 오는 중... 준비!',
'livepreview-failed'  => '실시간 미리 보기 실패!
일반 미리 보기를 이용하세요.',
'livepreview-error'   => '연결에 실패하였습니다: $1 "$2"
일반 미리 보기를 이용하세요.',

# Friendlier slave lag warnings
'lag-warn-normal' => '최근 $1초 안에 바뀐 문서는 이 목록에서 빠졌을 수 있습니다.',
'lag-warn-high'   => '데이터베이스 서버의 과도한 부하 때문에 최근 $1초 안에 바뀐 문서 목록은 보여지지 않을 수 있습니다.',

# Watchlist editor
'watchlistedit-numitems'       => '토론 문서를 제외하고 문서 $1개를 주시하고 있습니다.',
'watchlistedit-noitems'        => '주시문서 목록이 비어 있습니다.',
'watchlistedit-normal-title'   => '주시문서 목록 편집하기',
'watchlistedit-normal-legend'  => '주시문서 목록에서 문서 제거하기',
'watchlistedit-normal-explain' => '주시문서 목록에 있는 문서의 제목이 아래에 나열되어 있습니다.
주시문서 목록에서 제거하려는 문서가 있으면, 각 항목의 체크박스를 선택한 다음 "{{int:Watchlistedit-normal-submit}}"를 클릭해주세요.
또는 [[Special:EditWatchlist/raw|목록을 직접 편집]]할 수도 있습니다.',
'watchlistedit-normal-submit'  => '항목 삭제',
'watchlistedit-normal-done'    => '주시문서 목록에서 다음 {{PLURAL:$1|항목}}을 주시하지 않습니다:',
'watchlistedit-raw-title'      => '주시문서 목록 직접 편집하기',
'watchlistedit-raw-legend'     => '주시문서 목록 직접 편집하기',
'watchlistedit-raw-explain'    => '주시문서 목록의 각 항목이 나와 있습니다. 필요한 항목을 직접 추가하거나 제거할 수 있습니다.
각 줄마다 하나의 제목을 입력하세요.
수정을 마쳤다면 "{{int:Watchlistedit-raw-submit}}"을 누르면 됩니다.
또는 [[Special:EditWatchlist|일반적인 편집기]]를 쓸 수도 있습니다.',
'watchlistedit-raw-titles'     => '목록:',
'watchlistedit-raw-submit'     => '주시문서 목록 새로 고침',
'watchlistedit-raw-done'       => '주시문서 목록을 새로 고쳤습니다.',
'watchlistedit-raw-added'      => '문서 $1개를 추가했습니다:',
'watchlistedit-raw-removed'    => '문서 $1개를 제거했습니다:',

# Watchlist editing tools
'watchlisttools-view' => '주시문서 최근 바뀜',
'watchlisttools-edit' => '주시문서 목록 보기/편집하기',
'watchlisttools-raw'  => '주시문서 목록 직접 편집하기',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|토론]])',

# Core parser functions
'unknown_extension_tag' => '알 수 없는 확장 기능 태그 "$1"',
'duplicate-defaultsort' => '\'\'\'경고:\'\'\' 기본 정렬 키 "$2"가 이전의 기본 정렬 키 "$1"를 덮어쓰고 있습니다.',

# Special:Version
'version'                       => '버전',
'version-extensions'            => '설치된 확장 기능',
'version-specialpages'          => '특수 문서',
'version-parserhooks'           => '파서 훅',
'version-variables'             => '변수',
'version-antispam'              => '스팸 방지',
'version-skins'                 => '스킨',
'version-other'                 => '기타',
'version-mediahandlers'         => '미디어 핸들러',
'version-hooks'                 => '훅',
'version-extension-functions'   => '확장 함수',
'version-parser-extensiontags'  => '파서 확장 태그',
'version-parser-function-hooks' => '파서 기능 훅',
'version-hook-name'             => '훅 이름',
'version-hook-subscribedby'     => '훅이 사용된 위치',
'version-version'               => '(버전 $1)',
'version-license'               => '라이선스',
'version-poweredby-credits'     => "이 위키는 '''[//www.mediawiki.org/ MediaWiki]'''를 기반으로 작동합니다. Copyright © 2001-$1 $2.",
'version-poweredby-others'      => '[{{SERVER}}{{SCRIPTPATH}}/CREDITS 그 외 다른 개발자]',
'version-license-info'          => "미디어위키는 자유 소프트웨어입니다. 당신은 자유 소프트웨어 재단이 발표한 GNU 일반 공중 사용 허가서 버전 2나 그 이후 버전에 따라 이 파일을 재배포하거나 수정할 수 있습니다.

미디어위키가 유용하게 사용될 수 있기를 바라지만 '''상용으로 사용'''되거나 '''특정 목적에 맞을 것'''이라는 것을 '''보증하지 않습니다'''. 자세한 내용은 GNU 일반 공중 사용 허가서 전문을 참고하십시오.

당신은 이 프로그램을 통해 [{{SERVER}}{{SCRIPTPATH}}/COPYING GNU 일반 공중 사용 허가서 전문]을 받았습니다. 그렇지 않다면, Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA로 편지를 보내주시거나 [//www.gnu.org/licenses/old-licenses/gpl-2.0.html 온라인으로 읽어보시기] 바랍니다.",
'version-software'              => '설치된 프로그램',
'version-software-product'      => '제품',
'version-software-version'      => '버전',

# Special:FilePath
'filepath'         => '파일 경로',
'filepath-page'    => '파일:',
'filepath-submit'  => '가기',
'filepath-summary' => '파일의 실제 URL 주소를 엽니다.
그림 파일일 경우 원본 해상도의 파일이 열립니다. 다른 종류의 파일일 경우 그 파일의 종류에 맞는 프로그램이 실행됩니다.',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => '중복된 파일 찾기',
'fileduplicatesearch-summary'   => '파일 해시값을 이용해 중복 파일을 찾습니다.',
'fileduplicatesearch-legend'    => '중복 찾기',
'fileduplicatesearch-filename'  => '파일 이름:',
'fileduplicatesearch-submit'    => '찾기',
'fileduplicatesearch-info'      => '$1 × $2 픽셀<br />파일 크기: $3<br />MIME 유형: $4',
'fileduplicatesearch-result-1'  => '"$1" 파일과 중복된 파일이 없습니다.',
'fileduplicatesearch-result-n'  => '"$1" 파일은 중복 파일이 $2개 있습니다.',
'fileduplicatesearch-noresults' => '"$1"이라는 이름을 가진 파일이 없습니다.',

# Special:SpecialPages
'specialpages'                   => '특수 문서 목록',
'specialpages-note'              => '----
* 일반 특수 문서.
* <span class="mw-specialpagerestricted">제한된 특수 문서.</span>',
'specialpages-group-maintenance' => '관리용 목록',
'specialpages-group-other'       => '다른 특수 문서',
'specialpages-group-login'       => '로그인 / 계정 만들기',
'specialpages-group-changes'     => '최근 바뀜과 기록',
'specialpages-group-media'       => '파일 관리',
'specialpages-group-users'       => '사용자와 권한',
'specialpages-group-highuse'     => '많이 쓰이는 문서 목록',
'specialpages-group-pages'       => '문서 목록',
'specialpages-group-pagetools'   => '문서 도구',
'specialpages-group-wiki'        => '위키 정보와 도구',
'specialpages-group-redirects'   => '넘겨주기 특수 문서',
'specialpages-group-spam'        => '스팸 처리 도구',

# Special:BlankPage
'blankpage'              => '빈 문서',
'intentionallyblankpage' => '일부러 비워 둔 문서입니다.',

# External image whitelist
'external_image_whitelist' => ' #이 줄은 그대로 두십시오<pre>
#정규 표현식(// 사이에 있는 부분)을 아래에 입력하세요.
#이 목록은 바깥 그림의 URL과 대조할 것입니다.
#이 목록과 일치하는 것은 그림이 직접 보여지지만, 그렇지 않은 경우 그림을 가리키는 링크만 보이게 될 것입니다.
# "#"으로 시작하는 줄은 주석으로 간주됩니다.
#이 목록은 대소문자를 구별하지 않습니다.

#모든 정규 표현식은 이 줄 위에 넣어 주십시오. 그리고 이 줄은 그대로 두십시오.</pre>',

# Special:Tags
'tags'                    => '유효한 편집에 대한 태그',
'tag-filter'              => '[[Special:Tags|태그]] 필터:',
'tag-filter-submit'       => '필터',
'tags-title'              => '태그',
'tags-intro'              => '이 문서는 소프트웨어에서 편집에 대해 표시하는 태그와 그 의미를 설명하는 목록입니다.',
'tags-tag'                => '태그 이름',
'tags-display-header'     => '바뀜 목록의 모양',
'tags-description-header' => '태그에 대한 설명',
'tags-hitcount-header'    => '태그된 바뀜',
'tags-edit'               => '편집',
'tags-hitcount'           => '$1개 바뀜',

# Special:ComparePages
'comparepages'                => '문서 비교',
'compare-selector'            => '문서의 특정판 비교',
'compare-page1'               => '첫 번째 문서',
'compare-page2'               => '두 번째 문서',
'compare-rev1'                => '첫 번째 판',
'compare-rev2'                => '두 번째 판',
'compare-submit'              => '비교하기',
'compare-invalid-title'       => '입력한 제목이 잘못되었습니다.',
'compare-title-not-exists'    => '입력한 문서가 존재하지 않습니다.',
'compare-revision-not-exists' => '지정한 판이 없습니다.',

# Database error messages
'dberr-header'      => '이 위키에 문제가 있습니다.',
'dberr-problems'    => '죄송합니다!
이 사이트는 기술적인 문제가 있습니다.',
'dberr-again'       => '잠시 후에 다시 시도해주세요.',
'dberr-info'        => '(데이터베이스에 접속할 수 없습니다: $1)',
'dberr-usegoogle'   => '그 동안 구글을 통해 검색할 수도 있습니다.',
'dberr-outofdate'   => '참고로 구글의 내용 개요는 오래된 것일 수도 있습니다.',
'dberr-cachederror' => '다음은 요청한 문서의 캐시된 복사본이며, 최신이 아닐 수도 있습니다.',

# HTML forms
'htmlform-invalid-input'       => '입력한 값에 문제가 있습니다.',
'htmlform-select-badoption'    => '지정한 값은 올바른 설정이 아닙니다.',
'htmlform-int-invalid'         => '지정한 값은 정수가 아닙니다.',
'htmlform-float-invalid'       => '지정한 값은 수가 아닙니다.',
'htmlform-int-toolow'          => '지정한 값은 최소값 $1 미만입니다.',
'htmlform-int-toohigh'         => '지정한 값은 최대값 $1 이상입니다.',
'htmlform-required'            => '이 값은 필수 항목입니다',
'htmlform-submit'              => '저장',
'htmlform-reset'               => '바꾼 것을 되돌리기',
'htmlform-selectorother-other' => '기타',

# SQLite database support
'sqlite-has-fts' => '$1 (본문 전체 찾기)',
'sqlite-no-fts'  => '$1 (본문은 찾기에서 제외)',

# New logging system
'logentry-delete-delete'              => '$1 사용자가 $3 문서를 삭제하였습니다.',
'logentry-delete-restore'             => '$1 사용자가 $3 문서를 복구하였습니다.',
'logentry-delete-event'               => '$1 사용자가 $3의 기록 $5개에 대해 보이기 설정을 바꾸었습니다: $4',
'logentry-delete-revision'            => '$1 사용자가 $3 문서의 {{PLURAL:$5|$5개 편집}}의 설정을 바꾸었습니다: $4',
'logentry-delete-event-legacy'        => '$1 사용자가 $3 문서 기록의 보이기 설정을 바꾸었습니다.',
'logentry-delete-revision-legacy'     => '$1 사용자가 $3 문서 편집의 보이기 설정을 바꾸었습니다.',
'logentry-suppress-delete'            => '$1 사용자가 $3 문서를 숨겼습니다.',
'logentry-suppress-event'             => '$1 사용자가 비공개적으로 $3의 {{PLURAL:$5|기록 $5개}}에 대해 보이기 설정을 바꾸었습니다: $4',
'logentry-suppress-revision'          => '$1 사용자가 비공개적으로 $3 문서의 {{PLURAL:$5|판 $5개}}에 대해 보이기 설정을 바꾸었습니다: $4',
'logentry-suppress-event-legacy'      => '$1 사용자가 비공개적으로 $3의 항목에 대한 보이기 설정을 바꾸었습니다.',
'logentry-suppress-revision-legacy'   => '$1 사용자가 비공개적으로 $3 문서의 특정 판에 대한 보이기 설정을 바꾸었습니다.',
'revdelete-content-hid'               => '내용 숨겨짐',
'revdelete-summary-hid'               => '편집 요약 숨겨짐',
'revdelete-uname-hid'                 => '사용자 이름 숨겨짐',
'revdelete-content-unhid'             => '내용 숨김 해제됨',
'revdelete-summary-unhid'             => '편집 요약 숨김 해제됨',
'revdelete-uname-unhid'               => '사용자 이름 숨김 해제됨',
'revdelete-restricted'                => '관리자에게 제한을 적용함',
'revdelete-unrestricted'              => '관리자에 대한 제한을 해제함',
'logentry-move-move'                  => '$1 사용자가 $3 문서를 $4 문서로 옮겼습니다.',
'logentry-move-move-noredirect'       => '$1 사용자가 $3 문서를 넘겨주기를 만들지 않고 $4 문서로 옮겼습니다.',
'logentry-move-move_redir'            => '$1 사용자가 $3 문서를 $4 문서로 옮기면서 넘겨주기를 덮어썼습니다.',
'logentry-move-move_redir-noredirect' => '$1 사용자가 $3 문서를 $4 문서로 넘겨주기를 남기지 않으면서 옮기면서 옮길 대상에 있던 넘겨주기를 덮어썼습니다.',
'logentry-patrol-patrol'              => '$1 사용자가 $3 문서의 $4판을 검토한 것으로 표시했습니다.',
'logentry-patrol-patrol-auto'         => '$1 사용자가 자동적으로 $3 문서의 $4판을 검토한 것으로 표시했습니다.',
'logentry-newusers-newusers'          => '$1 사용자 계정을 만들었습니다.',
'logentry-newusers-create'            => '$1 사용자 계정을 만들었습니다.',
'logentry-newusers-create2'           => '$1 사용자가 $3 사용자 계정을 만들었습니다.',
'logentry-newusers-autocreate'        => '$1 사용자 계정을 자동적으로 만들었습니다.',
'newuserlog-byemail'                  => '이메일로 보낸 비밀번호',

# Feedback
'feedback-bugornote' => '기술적 문제를 구체적으로 설명할 준비가 되었다면 [$1 버그를 신고]해 주세요.
아니면 아래에 쉬운 양식을 쓸 수 있습니다. 당신의 의견은 사용자 이름과 사용 중인 브라우저 정보와 함께 "[$3 $2]"에 남겨질 것입니다.',
'feedback-subject'   => '제목:',
'feedback-message'   => '내용:',
'feedback-cancel'    => '취소',
'feedback-submit'    => '피드백 제출',
'feedback-adding'    => '문서에 피드백을 올리는 중...',
'feedback-error1'    => '오류: API 실행 결과를 인식할 수 없음',
'feedback-error2'    => '오류: 편집 실패',
'feedback-error3'    => '오류: API가 응답하지 않음',
'feedback-thanks'    => '감사합니다! "[$2 $1]" 문서에 당신의 의견을 남겼습니다.',
'feedback-close'     => '완료',
'feedback-bugcheck'  => '감사합니다! 혹시 해당 사항이 [$1 기존의 버그 보고서]에 올라와 있는지 확인해주세요.',
'feedback-bugnew'    => '확인했습니다. 새로운 버그 보고서를 작성합니다.',

# API errors
'api-error-badaccess-groups'              => '이 위키에 파일을 올릴 권한이 없습니다.',
'api-error-badtoken'                      => '내부 오류: 토큰이 잘못되었습니다.',
'api-error-copyuploaddisabled'            => '이 서버에서 URL을 통해 파일 올리기가 비활성화되어 있습니다.',
'api-error-duplicate'                     => '이 위키에 내용이 똑같은 {{PLURAL:$1|[$2 다른 파일]}}이 있습니다.',
'api-error-duplicate-archive'             => '같은 내용을 담고 있던 {{PLURAL:$1|[$2 다른 파일]}}이 있었지만 이 {{PLURAL:$1|파일}}은 삭제되었습니다.',
'api-error-duplicate-archive-popup-title' => '중복된 {{PLURAL:$1|파일}}이 이미 삭제되었습니다.',
'api-error-duplicate-popup-title'         => '중복된 {{PLURAL:$1|파일}}입니다.',
'api-error-empty-file'                    => '올리려는 파일이 비어 있습니다.',
'api-error-emptypage'                     => '새 문서로 빈 문서를 만들 수 없습니다.',
'api-error-fetchfileerror'                => '내부 오류: 파일을 불러오는 중 문제가 발생했습니다.',
'api-error-file-too-large'                => '올리려는 파일이 너무 큽니다.',
'api-error-filename-tooshort'             => '파일 이름이 너무 짧습니다.',
'api-error-filetype-banned'               => '이런 파일 형식은 올릴 수 없습니다.',
'api-error-filetype-missing'              => '파일 이름에 확장자가 없습니다.',
'api-error-hookaborted'                   => '수정하려고 한 것이 확장 기능에 의해 중지되었습니다.',
'api-error-http'                          => '내부 오류: 서버에 연결할 수 없습니다.',
'api-error-illegal-filename'              => '이 파일 이름을 사용할 수 없습니다.',
'api-error-internal-error'                => '내부 오류: 올린 파일을 위키에서 처리하는 중 어떤 문제가 발생했습니다.',
'api-error-invalid-file-key'              => '내부 오류: 임시 저장소에서 파일을 찾지 못했습니다.',
'api-error-missingparam'                  => '내부 오류: 요청 중 매개변수가 누락되었습니다.',
'api-error-missingresult'                 => '내부 오류: 파일의 복제가 성공했는지 판단할 수 없습니다.',
'api-error-mustbeloggedin'                => '파일을 올리기 위해서는 로그인해야 합니다.',
'api-error-mustbeposted'                  => '내부 오류: HTTP POST에 요청이 필요합니다.',
'api-error-noimageinfo'                   => '파일 올리기는 성공했지만 서버가 파일에 대해 어떠한 정보도 주지 않았습니다.',
'api-error-nomodule'                      => '내부 오류: 올리기 모듈이 설정되지 않았습니다.',
'api-error-ok-but-empty'                  => '내부 오류: 서버에서 응답이 없습니다.',
'api-error-overwrite'                     => '이미 있는 파일을 덮어쓸 수 없습니다.',
'api-error-stashfailed'                   => '내부 오류: 서버가 임시 파일을 저장하지 못했습니다.',
'api-error-timeout'                       => '서버가 제 시간 내에 응답하지 않았습니다.',
'api-error-unclassified'                  => '알 수 없는 오류가 발생했습니다.',
'api-error-unknown-code'                  => '알 수 없는 오류: "$1".',
'api-error-unknown-error'                 => '내부 오류: 파일을 올리려 하는 도중에 무엇인가가 잘못되었습니다.',
'api-error-unknown-warning'               => '알 수 없는 경고: "$1".',
'api-error-unknownerror'                  => '알 수 없는 오류: "$1".',
'api-error-uploaddisabled'                => '이 위키에서 파일 올리기가 비활성화되어 있습니다.',
'api-error-verification-error'            => '파일이 손상되었거나 잘못된 확장자를 사용하고 있습니다.',

);
