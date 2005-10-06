<?php
/** Korean (한국어)
  *
  * @package MediaWiki
  * @subpackage Language
  */
require_once('LanguageUtf8.php');

/* private */ $wgNamespaceNamesKo = array(
	NS_MEDIA	    => 'Media',
	NS_SPECIAL	  => '특수기능',
	NS_MAIN	     => '',
	NS_TALK	     => '토론',
	NS_USER	     => '사용자',
	NS_USER_TALK	=> '사용자토론',
	NS_PROJECT	  => $wgMetaNamespace,
	NS_PROJECT_TALK     => "${wgMetaNamespace}토론",
	NS_IMAGE	    => '그림',
	NS_IMAGE_TALK       => '그림토론',
	NS_CATEGORY	 => '분류',
	NS_CATEGORY_TALK    => '분류토론',
) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsKo = array(
	'없음', '왼쪽 붙박이', '오른쪽 붙박이', '왼쪽 떠다님'

);

/* private */ $wgSkinNamesKo = array(
	'standard' => '보통',
	'nostalgia' => '그리움',
	'cologneblue' => '쾰른 파랑',
) + $wgSkinNamesEn;


/* private */ $wgBookstoreListKo = array(
	'Aladdin.co.kr' => 'http://www.aladdin.co.kr/catalog/book.asp?ISBN=$1'
) + $wgBookstoreListEn;



# (Okay, I think I got it right now. This can be adjusted
#  in the 'date' function down at the bottom. --Brion)
#
# Thanks. And it's usual that the time comes after dates.
# So I've change the timeanddate function, just exchanged $time and $date
# But you should check before you install it, 'cause I'm quite stupid about
# the programming.
#

/* private */ $wgWeekdayAbbreviationsKo = array(
	'日', '月', '火', '水', '木',
	'金', '土'
);

# I'll leave this part in english, for it's more likely that
# there will probably no Korean for the maintaining this site
# in near future, and if any shows up, he would possibly speak
# both Korean and English. And he can later koreanize this part.

/* private */ $wgAllMessagesKo = array(

# User Toggles

'tog-underline' => '고리에 밑줄치기',
'tog-highlightbroken' => '없는 문서로의 고리 돋보이기',
'tog-justify' => '문단 정렬',
'tog-hideminor' => '사소한 편집 최근 바뀜에서 숨기기',
'tog-numberheadings' => '머릿글 번호 매기기',
'tog-showtoolbar' => 'Show edit toolbar',
'tog-rememberpassword' => '세션동안 암호 기억',
'tog-editwidth' => '편집상자 너비 최대',
# Dates

'sunday' => '일요일',
'monday' => '월요일',
'tuesday' => '화요일',
'wednesday' => '수요일',
'thursday' => '목요일',
'friday' => '금요일',
'saturday' => '토요일',
'january' => '1월',
'february' => '2월',
'march' => '3월',
'april' => '4월',
'may_long' => '5월',
'june' => '6월',
'july' => '7월',
'august' => '8월',
'september' => '9월',
'october' => '10월',
'november' => '11월',
'december' => '12월',
'jan' => '1',
'feb' => '2',
'mar' => '3',
'apr' => '4',
'may' => '5',
'jun' => '6',
'jul' => '7',
'aug' => '8',
'sep' => '9',
'oct' => '10',
'nov' => '11',
'dec' => '12',
# Bits of text used by many pages:
#
'subcategories' => '하위 분류',


'mainpage'	    => '대문',
'about'		       => '소개',
'aboutsite'      => '{{SITENAME}}란',
'aboutpage'	   => '{{ns:4}}:소개',
'help'			=> '도움말',
'helppage'	    => '{{ns:4}}:도움말',
'bugreports'  => '벌레 발견',
'bugreportspage' => '{{ns:4}}:벌레_발견',
'faq'		 => '잦은질문(FAQ)',
'faqpage'	     => '{{ns:4}}:잦은질문(FAQ)',
'edithelp'	    => '편집 도움말',
'edithelppage'	=> '{{ns:4}}:문서_편집_방법',
'cancel'	      => '취소',
'qbfind'	      => '찾기',

# where does this 'browse' appear? I haven't seen it.
# and therefore no idea what its function is.

# (Select the Cologne Blue skin; it's a section header
#  in the sidebar over 'main page', 'recent changes', etc.)

'qbbrowse'	    => '항해판',
'qbedit'	      => '고치기',
'qbpageoptions' => '문서 기능',
'qbpageinfo'  => '문서 정보',
'qbmyoptions' => '자기 기능',
'mypage'	      => '자기 문서',
'mytalk'	      => '자기 토론',
'currentevents' => '요즘의 화제',
'errorpagetitle' => '오류',
'returnto'	    => '$1(으)로 돌아가기.',
'tagline'	     => '{{SITENAME}}, 우리 모두의 백과사전.',
'whatlinkshere'       => '여길 가리키는 문서',
'help'			=> '도움말',
'search'	      => '찾기',
'history'	     => '문서역사',
'printableversion' => '인쇄용',
'editthispage'	=> '문서 고치기',
'deletethispage' => '문서 지우기',
'protectthispage' => '문서 보호',
'unprotectthispage' => '문서 보호 해제',
'talkpage'	    => '토론',
'subjectpage' => '본 문서',
'otherlanguages' => '다른 언어',
'redirectedfrom' => '($1에서 넘어옴.)',
'lastmodified'	=> '이 문서는 최근 $1에 바뀌었습니다.',

'viewcount'	   => '이 문서는 총 $1번 읽혔습니다.',
'printsubtitle' => '({{SERVER}}에서)',
'protectedpage' => '보호되는 문서',
'administrators' => '{{ns:4}}:관리자',
'sysoptitle'  => 'Sysop 권한 필요',
'sysoptext'	   => '해당 action은 \'Sysop\'만 실행할 수 있습니다.
참조 $1.',
'developertitle' => 'Developer 권한 필요',
'developertext'       => '해당 action은 \'developer\'만 실행할 수 있습니다.
참조 $1.',
'nbytes'	      => '$1 바이트',
'go'		  => '가기',
'ok'		  => '확인',
'sitetitle'	   => '{{SITENAME}}',
'sitesubtitle'	=> '우리 모두의 백과사전',
'retrievedfrom' => '\'$1\'에서',

# Main script and global functions
#
'nosuchaction'	=> '그런 action은 없습니다.',
'nosuchactiontext' => '{{SITENAME}} 무른모는 URL로 주어진 action을
모릅니다.',
'nosuchspecialpage' => '틀린 특수기능',
'nospecialpagetext' => '{{SITENAME}}는 요청한 특수기능을
모릅니다.',

# Login and logout pages
#
'logouttitle' => '나옴',
'logouttext'  => '{{SITENAME}}에서 나왔습니다.
이대로 이름없이 {{SITENAME}}를 이용하거나, 방금 사용했던 또이름, 혹은 다른 또이름으로 들어가서 이용하세요.\n',

'welcomecreation' => '<h2>$1 님, 환영합니다!</h2><p>또이름이 만들어 졌습니다.
개인 맞춤에서 자잘한 환경들을 바꾸어 보세요.',

'loginpagetitle' => '들어가기',
'yourname'	    => '또이름은',
'yourpassword'	=> '암호는',
'yourpasswordagain' => '암호 또 한번',
'newusersonly'	=> ' (새내기 사용자)',
'remembermypassword' => '세쎤동안 암호를 기억합니다.',
'loginproblem'	=> '<b>들어가는 데 문제가 있습니다.</b><br />다시 해 보세요!',
'alreadyloggedin' => '<strong>$1 님, 벌써 들어와 있습니다!</strong><br />\n',

'login'		       => '들어가기',
'userlogin'	   => '들어가기',
'logout'	      => '나가기',
'userlogout'  => '나오기',
'createaccount'       => '또이름 새로 만들기',
'badretype'	   => '암호가 서로 다릅니다.',
'userexists'  => '또이름이 벌써 사용중입니다. 다른 또이름을 지으세요.',
'youremail'	   => '당신의 누리편지',
'yournick'	    => '당신의 별명 (서명용)',
'emailforlost'	=> '암호를 잊었을 때, 새 암호를 누리편지로 받을 수 있습니다.',
'loginerror'  => '들어가기 오류',
'noname'	      => '또이름이 틀립니다.',
'loginsuccesstitle' => '들어가기 성공',
'loginsuccess'	=> '\'$1\' {{SITENAME}}에 들어왔습니다.',
'nosuchuser'  => '\'$1\'란 또이름은 없습니다.'.
'철자가 맞는지 확인하고, 아직 또이름이 없다면, 아래를 채워 또이름을 새로이 만드세요.',
'wrongpassword'       => '암호가 틀립니다. 다시 시도하세요.',
'mailmypassword' => '새 암호를 누리편지로 보냅니다.',
'passwordremindertitle' => '{{SITENAME}}에서 보내는 새 암호',
'passwordremindertext' => '누군가가 (IP $1 을 사용했던, 아마도 당신이)
새 {{SITENAME}} 암호를 보내달라고 부탁했습니다.
또이름 \'$2\'의 암호는 이제 \'$3\'입니다.
새 암호로 {{SITENAME}}에 들어와서, 암호를 바꾸세요.',
'noemail'	     => '또이름 \'$1\'에 딸린 누리편지주소정보가 없습니다.',
'passwordsent'	=> '\'$1\'의 새 암호를 누리편지로 보냈습니다.
암호를 받고 다시 들어오세요.',

# Edit pages
#
'summary'	     => '바꾼내용 간추리기',
'minoredit'	   => '사소한 편집',
'savearticle' => '저장',
'preview'	     => '미리보기',
'showpreview' => '미리보기',
'blockedtitle'	=> '사용자 접근금지',
'blockedtext' => '$1 가 당신의 또이름이나 IP를 막았습니다.
이유는 다음과 같습니다:<br />$2<p> 접근금지에 대해선 관리자와 상의하십시오.',
'newarticle'  => '(새문서)',
'newarticletext' => '새문서에 내용을 써 넣으세요.',
'noarticletext' => '(현재 문서는 비어 있습니다.)',
'updated'	     => '(바뀜)',
'note'			=> '<strong>주의:</strong> ',
'previewnote' => '지금 미리보기로 보고 있는 내용은 아직 저장되지 않았습니다!',

# when does this message show up? I have encountered it yet, I guess.
# And what does it have to do with conflict? The message sound quite normal.
# (I don't think it _can_ show up. It's only used if you preview and get
# an edit conflict, but edit conflict is tripped only if you're saving.)
'previewconflict' => 'This preview reflects the text in the upper
text editing area as it will appear if you choose to save.',

'editing'	     => '$1 고쳐쓰기',
'editconflict'	=> '고치기 충돌: $1',
'explainconflict' => '문서를 고쳐쓰는 동안, 문서가 바뀌었습니다.
위쪽이 바뀐 현재 문서이고, 아래쪽이 당신이 고쳐쓴 내용입니다.
당신이 고쳐쓴 내용을 현재 문서에 다시 집어 넣어야 할 것입니다.
지금 \'저장\'을 눌러도,
<strong>오직</strong> 위쪽에 있는 내용만 저장됩니다.<br />',
'yourtext'	    => '당신이 고쳐쓴 것',
'storedversion' => '바뀐 현재 문서',
'editingold'  => '<strong>경고: 지금 옛날 버젼의 문서를 고치고 있습니다.
만약, 지금 여기서 저장을 하면, 그 때 이후의 모든 버젼을 잃게 됩니다.</strong>',
'yourdiff'	    => '차이',
'copyrightwarning' => '{{SITENAME}}에 당신이 기여한 것은 모두 GNU 자유 문서 사용허가서(GFDL)
($1참조)에 따라 배포됩니다.
당신이 써 넣은 내용이 제한없이 고쳐지고, 재배포되는 것이 싫다면, 저장하지 마십시오.에 반대할 때에는, 여기에 쓰지 마시길 바랍니다.<br />
또한, 여기 써 넣은 내용을 스스로 썼음을, 혹은 모두에게 공개된 자료에서 빌어왔음을
같이 약속해야 합니다.
<strong>저작권의 보호를 받는 내용을 저작권자의 허가없이 보내지 마십시오!</strong>',


# History pages
#
'revhistory'  => '바뀐역사',
'nohistory'	   => '이 문서는 역사가 없습니다.',
'revnotfound' => '버젼 찾지 못함',
'revnotfoundtext' => '요청한 이 문서의 옛 버젼을 찾지 못했습니다.
이 문서에 접근할 때의 URL을 확인해 주십시오.\n',
'loadhist'	    => '문서역사를 받고 있습니다.',
'currentrev'  => '현재 버젼',
'revisionasof'	=> '$1 버젼',
'cur'		 => '현재',
'next'			=> '다음',
'last'			=> '이전',
'orig'			=> '처음',
'histlegend'  => '상세설명: (현재) = 현재 버젼과의 차이,
(이전) = 바로 이전 버전과의 차이, 少 = 사소한 편집',

# Diffs
#
'difference'  => '(버젼사이 차이)',
'loadingrev'  => '버젼 차이를 받고 있습니다.',
'lineno'	      => '$1째 줄:',
'editcurrent' => '현재 버전의 문서를 고칩니다.',

# Search results
#
'searchresults' => '찾아본 결과',
'searchresulttext' => '{{SITENAME}} 찾기에 대해 자세한 정보는 [[Project:찾기|{{SITENAME}} 찾기]] 를 보세요.',
'searchquery' => '열쇠말 \'$1\'',
'badquery'	    => '좋지 않은 열쇠말',

# I think we should enable the less-than-3-letter query in Korean.
# One korean letter corresponds to one syllable. And when I do a search
# in google or yahoo. The query I type in is mostly 3, 4 of letters
# and it works. I'll leave this part in English. I'll wait for the
# software to be developed to be compitable three letter search.

# It's really 3 bytes, not 3 letters. Any Korean letter in UTF-8 encoding
# is 3 bytes, so in theory it should work. However, searches for Korean
# text don't work anyway. I'll have to bang it into shape... --Brion

# Then, one and a half, am I right?

'matchtotals' => '열쇠말 \'$1\'이 제목에 들어있는 문서는 $2개 이고,
내용에 담고있는 문서는 $3개 입니다.',
'titlematches'	=> '문서 제목 일치',
'notitlematches' => '제목과 맞는 문서가 없습니다.',
'textmatches' => '문서 내용 일치',
'notextmatches'       => '내용에 열쇠말을 담고 있은 문서가 없습니다.',
'prevn'		       => '이전 $1',
'nextn'		       => '다음 $1',
'viewprevnext'	=> '($1) ($2) 보기 ($3).',
'showingresults' => '<b>$2</b>번 부터 <b>$1</b>개의 결과 입니다.',
'powersearch' => '찾기',

# Preferences page
#
'preferences' => '개인 맞춤',
'prefsnologin' => '나와 있습니다.',
'prefsnologintext'    => '[[Special:Userlogin|들어와]] 있을 때에만,
# Special:Userlogin => 특수기능:들어가기 개인 환경을 맞출 수 있습니다.',
'prefslogintext' => '당신은 \'$1\', 맞죠?
당신의 내부 ID 번호는 $2입니다.',
'prefsreset'  => '개인 맞춤을 보통으로 되돌렸습니다.',
'qbsettings'  => '빨리가기 맞춤',
'changepassword' => '암호 바꾸기',
'skin'			=> '옷',
'saveprefs'	   => '맞춤 저장',
'resetprefs'  => '맞춤 보통으로',
'oldpassword' => '현재 암호',
'newpassword' => '새 암호',
'retypenew'	   => '새 암호 또 한번',
'textboxsize' => '문서상자 크기',
'rows'			=> '줄수',
'columns'	     => '너비',
'searchresultshead' => '찾기 결과 맞춤',
'resultsperpage' => '쪽마다 보이는 결과',
'contextlines'	=> '결과마다 보이는 줄수',
'contextchars'	=> '줄수마다 보이는 글잣수',
'stubthreshold' => '씨앗보기 문턱값',
'recentchangescount' => '최근 바뀜에 보이는 항목 수',
'savedprefs'  => '새 설정을 저장했습니다.',
'timezonetext'	=> '현지 시간과 서버 시간(UTC)과 차이를 써 넣으세요.',
'localtime'   => '현지 시각',
'timezoneoffset' => '시간차',
'emailflag'	   => '다른 사용자에게서 누리편지 안 받음',

# Recent changes
#
'recentchanges' => '최근 바뀜',
'recentchangestext' => '아래 나열된 문서들이 최근에 바뀌었습니다.

[[{{ns:4}}:새내기_환영|새내기, 환영합니다]]!
새내기들은 다음 문서를 읽어 보세요.: [[{{ns:4}}:잦은질문(FAQ)|{{SITENAME}} 잦은질문(FAQ)]],
[[{{ns:4}}:정책과 지침|{{SITENAME}} 정책]]
(특별히 [[{{ns:4}}:제목달기 규칙|제목달기 규칙]],
[[{{ns:4}}:중립적인 시각|중립적인 시각]]),
그리고 [[{{ns:4}}:범하기_쉬운_실수|범하기 쉬운 실수]].

{{SITENAME}}가 성공하려면, 여러분이 저작권에 저촉되는 내용을 이곳에 써 넣지 않는 것이
매우 중요합니다.\' [[{{ns:4}}:저작권|저작권]].
법적 문제가 프로젝트를 망칠 수 있습니다. 저작권에 유의해 주세요.
또, [http://meta.wikipedia.org/wiki/Special:Recentchanges 최근 메타 토론]도
읽어 보세요.',
'rcloaderr'	   => '최근 바뀜을 받고 있습니다.',
'rcnote'	      => '다음이 최근 <strong>$2</strong>일간 바뀐  <strong>$1</strong>개의 문서입니다.',
'rclinks'	     => '최근 $2일 동안에 바뀐 $1개의 문서를 봅니다.',

'diff'			=> '차이',
'hist'			=> '역사',
'hide'			=> '숨김',
'show'			=> '보임',
'tableform'	   => '표로',
'listform'	    => '목록으로',
'nchanges'	    => '$1개 바뀜',
'minoreditletter' => '少',
'newpageletter' => '新',

# Upload
#
'upload'	      => '올리기',
'uploadbtn'	   => '파일 올리기',
'uploadlink'  => '그림 올리기',
'reupload'	    => '다시 올리기',
'reuploaddesc'	=> '올리기 틀로 돌아감',
'uploadnologin' => '나와있습니다.',
'uploadnologintext'   => '{{SITENAME}}에 [[Special:Userlogin|들어와]] 있을 때에만
# special:userlogin 특수기능:들어가기
파일을 올릴 수 있습니다.',
'uploaderror' => '올리기 오류',
'uploadtext'  => "'''잠깐!''' 여기 그림을 올리기 전에,
{{SITENAME}}의 [[Project:Image_use_policy|그림 사용 정책]]읽고 따라 주세요.

이미 올라온 그림을 찾아 보려면, [[Special:Imagelist|올라온 그림 목록]]으로 가세요.
# Special:Image list  특수기능:그림_목록
그림을 올리거나 지우면 [[Project:올리기_기록|올리기 기록]]에 그 사실이 남습니다.
# {{ns:4}}:올리기_기록 {{ns:4}}:올리기_기록

밑에 있는 틀을 이용해서 문서에 담을 파일을 올리세요.
대부분의 누리그물 훑개(browser)는, 아래 \'찾아보기...\' (영문 \'Browse...\') 단추로
 자기 컴퓨터의 파일을 찾게 해 줍니다.
원하는 파일을 고르면, 단추 옆의 공간에 파일이름이 채워질 것입니다.
또한, 저작권에 저촉되지 않는 파일을 올린다는 사실도
확인해야 합니다.
마지막으로, \'올리기\' 단추를 누르면 올라갑니다. 누리그물 연결이 느리면,
시간이 걸릴 수 있습니다.

{{SITENAME}}는 사진은 JPEG형식을, 보통 그림, 아이콘은 PNG형식을, 소리는
OGG형식을 더 좋아합니다.
이름은 햇갈리지 않고, 내용을 잘 나타내는 것으로 지어주세요. 그림을 문서에
담을 때에는 '''[[image:file.jpg]]''' 또는 '''[[image:file.png|alt text]]'''
처럼, 소리를 담을 때에는'''[[media:file.ogg]]'''처럼 써서 고리를 걸어주면
됩니다.

다른 사람들이 올린 파일을 고치거나 지울 수 있다는 것을 알아두십시오.
또한, 시스템을 남용할 때에는, 파일 올리기가 금지될 수도 있습니다.
",
'uploadlog'	   => '올리기 기록',
'uploadlogpage' => '올리기_기록',
'uploadlogpagetext' => '최근 올라온 그림 목록입니다.
모든 시간은 서버 기준(UTC)입니다.
<ul>
</ul>
',
'filename'	    => '파일이름',
'filedesc'	    => '짧은설명',
'copyrightpage' => '{{ns:4}}:저작권',
'copyrightpagename' => '{{SITENAME}} 저작권',
'uploadedfiles'       => '파일 올리기',
'ignorewarning'       => '경고 무시하고, 파일 저장',

# three alphabets and how many for Korean character?
'minlength'	   => '그림이름은 두글자가 넘어야 합니다.',

'badfilename' => '그림이름이 \'$1\'로 바뀌었습니다.',
'badfiletype' => '\'.$1\' 형식은 권장하지 않습니다.',
'largefile'	   => '그림크기는 100k이하를 권장합니다.',
'successfulupload' => '올리기 성공',
'fileuploaded'	=> '\'$1\'가 올라갔습니다.
다음 고리($2)를 따라 가서, 설명문서에 파일에 대한 정보를(어디서 구했는지,
누가 언제 만들었는지, 또 그 이외 필요한 사항들을) 채우세요.',
'uploadwarning' => '올리기 경고',
'savefile'	    => '파일 저장',
'uploadedimage' => '\'[[$1]]\'를 올렸습니다.',

# Image list
#
'imagelist'	   => '그림 목록',
'imagelisttext'       => '$2순으로 정리된 $1개의 그림입니다.',
'getimagelist'	=> '그림 목록 가져오기',
'ilsubmit'	    => '찾기',
'showlast'	    => '$2순으로 이전 $1개의 그림 보이기',
'byname'	      => '이름',
'bydate'	      => '날짜',
'bysize'	      => '크기',
'imgdelete'	   => '지움',

'imgdesc'	     => '설명',
'imglegend'	   => '상세설명: (설명) = 그림 설명을 보입니다/고칩니다.',
'imghistory'  => '그림역사',
'revertimg'	   => '돌림',
'deleteimg'	   => '지우기',
'deleteimgcompletely'	 => '지우기',
'imghistlegend' => '상세설명: (현재) = 현재의 그림입니다, (지움) = 옛 버젼을 지웁니다, (돌림) = 옛 버젼으로 되돌려 놓습니다.
<br /><i>특정 날짜에 올라온 그림을 보려면, 날짜를 찍어 주세요</i>.',
'imagelinks'  => '그림고리',
'linkstoimage'	=> '다음 문서들이 이 그림을 담고 있습니다:',
'nolinkstoimage' => '이 그림을 담고 있는 문서는 없습니다.',

# Statistics
#
'statistics'  => '통계',
'sitestats'	   => '누리터 통계',
'userstats'	   => '사용자 통계',
'sitestatstext' => '이곳 정보창고(DB)에는 총 <b>$1</b>개의 문서가 있습니다.
이 숫자는 \'토론\' 문서, {{SITENAME}} 자체에 관한 문서, 자라기를 기다리는 \'씨앗\' 문서,
넘겨주기 문서, 그리고 아직 사전항목으로 부족한 문서들을 모두 포함한 것입니다.
이들을 제외하면, <b>$2</b>개의 문서가 있습니다.<p>
또, 무른모 업그레이드가 있었던 2002년 7월 20일 이래, 여러분은 총 <b>$3</b>번 문서를
읽었고, <b>$4</b>번 고쳤습니다.
따라서, 평균적으로 문서 하나가 <b>$5</b>번 바뀌었고, 한번 바뀔 때마다 <b>$6</b>번 읽힌
셈이 됩니다.',
'userstatstext' => '<b>$1</b>명의 사용자가 등록되어 있습니다.
이 중 관리자는 <b>$2</b>명입니다.($3 참조)',


# Miscellaneous special pages
#
'orphans'	     => '외톨이 문서',
'lonelypages' => '외톨이 문서',
'unusedimages'	=> '안 쓰는 그림',
'popularpages'	=> '인기있는 문서',
'nviews'	      => '$1 번 읽음',
'wantedpages' => '필요한 문서',
'nlinks'	      => '$1개의 고리',
'allpages'	    => '모든 문서',
'randompage'  => '아무거나',
'shortpages'  => '짧은 문서',
'longpages'	   => '긴 문서',
'listusers'	   => '사용자들',
'specialpages'	=> '특수기능문서',
'spheading'	   => '특수기능문서',
'protectpage' => '보호된 문서',
'recentchangeslinked' => '여기서 가리키는 문서',
'rclsub'	      => '(\'$1\'의 고리들이 가리키는)',
'newpages'	    => '새 문서',
'movethispage'	=> '문서 옮기기',
'unusedimagestext' => '<p>다음 그림중 어떤 것은, 다른 언어의 {{SITENAME}}등 다른
누리터에서 URL바로걸기로 사용하고 있을 지도 모릅니다.',
'booksources' => '외부 책방',
'booksourcetext' => '새책이나 헌책을 파는 몇몇 누리터입니다. 찾고 있는 책의
정보를 담고 있을 수 있습니다.
{{SITENAME}}는 다음 중 어떤 기업과도 관련이 없으며,
아래 목록이 상업적 광고로 오해되지 않기를 바랍니다.',

# Email this user
#
'mailnologin' => '누리편지주소 없음',
'mailnologintext' => '{{SITENAME}}에 [[Special:Userlogin|들어와]] 있을 때, 또,
[[Special:Preferences|개인 맞춤]]에
자기의 누리편지주소를 기억시켰을 때에만,
다른 사용자에게 편지를 보낼 수 있습니다.',
'emailuser'	   => '사용자에게 편지쓰기',
'emailpage'	   => '누리편지 쓰기',
'emailpagetext'       => '이 사용자가 개인맞춤에 옳바른 주소를 써 넣었다면,
아래 틀을 이용하여 편지를 보낼 수 있습니다.
이 사용자가 바로 답장할 수 있도록, 당신의 개인맞춤에 넣었던 주소가,
\'보낸이\' 주소에 들어갑니다.',
'noemailtitle'	=> '누리편지 없음',
'noemailtext' => '이 사용자는 누리편지를 받지않음에 맞추어 놓았거나,
옳바른 주소를 써 넣지 않았습니다.',
'emailfrom'	   => '보낸이',
'emailto'	     => '받는이',
'emailsubject'	=> '제목',
'emailmessage'	=> '편지내용',
'emailsend'	   => '보내기',
'emailsent'	   => '편지 보냄',
'emailsenttext' => '누리편지를 보냈습니다.',

# Watchlist
#
'watchlist'	   => '눈여겨보기 목록',
'watchlistsub'	=> '(\'$1\'의)',
'nowatchlist' => '눈여겨보는 문서가 아직 없습니다.',
'watchnologin'	=> '나와있습니다.',
'watchnologintext'    => '[[Special:Userlogin|들어와]]
있을 때에만 눈여겨보기 목록을 볼 수 있습니다.',
'addedwatch'  => '눈여겨 봅니다.',
'addedwatchtext' => '앞으로 \'$1\'문서와 딸린 토론를
<a href=\'{{localurle:Special:Watchlist}}\'>눈여겨보기 목록</a>에서
관찰할 수 있으며,<a href=\'{{localurle:Special:Recentchanges}}\'>최근 바뀜</a>에는 금방 눈에
띄도록 <b>두터운 글씨체</b>로 나타납니다.</p>

<p>더 이상 눈여겨 보지 않아도 될 때에는, 옆의 \'눈여겨 보지 않음\'을 누르면 됩니다.',
'removedwatch'	=> '눈여겨 보지 않음',
'removedwatchtext' => '\'$1\'를 더 이상 눈여겨 보지 않습니다.',
'watchthispage'       => '눈여겨보기',
'unwatchthispage' => '눈여겨 보지 않음',
'notanarticle'	=> '문서가 아님',

# Contributions
#
'contributions'       => '사용자 기여',
'contribsub'  => '$1의',
'nocontribs'  => '이 사용는 어디에도 기여하지 않았습니다.',
'ucnote'	      => '<b>$2</b>일 동안 이 사용자가 바꾼 <b>$1</b>개의 문서입니다.',
'uclinks'	     => '최근 $1개 보기; 최근 $2일 보기',

# What links here
#
'whatlinkshere'       => '여길 가리키는 문서',
'notargettitle' => '문서 없음',
'notargettext'	=> '기능을 수행할 목표 문서나 목표 사용자를
지정하지 않았습니다.',
'linklistsub' => '(고리 목록)',
'linkshere'	   => '다음 문서들이 여기를 가리키고 있습니다.',
'nolinkshere' => '어떤 문서도 이곳을 가리키지 않습니다.',
'isredirect'  => '넘겨주기 문서',

# Move page
#
'movepage'	    => '문서 옮기기',
'movepagetext'	=> '아래 틀을 채워 문서이름을 바꾸세요.
문서역사도 함께 새문서로 갑니다.
기존의 문서는 새이름의 문서로 넘겨주는 역할만 하는 넘겨주기 문서가 됩니다.
기존 문서로의 고리도 바뀌지 않습니다. 딸린토론이 있어도, 옮기지
않습니다.
<b>경고!</b>
인기있는 문서를 옮기면 예상치 못한 엄청난 결과를 가져올 수 있습니다.
옮기는 것이 옳다는 확신이 들 때에만 진행하세요.',
'movearticle' => '문서 옮기기',
'movenologin' => '나와 있습니다.',
'movenologintext' => '{{SITENAME}}에 [[Special:Userlogin|들어와]] 있을 때에만
문서를 옮길 수 있습니다.',
'newtitle'	    => '새 이름',
'movepagebtn' => '옮기기',
'pagemovedsub'	=> '문서 옮김',
'pagemovedtext' => '\'[[$1]]\'를 \'[[$2]]\'로 옮겼습니다.',
'articleexists' => '그 이름의 문서가 이미 존재하거나, 새 이름이 옳바르지
않습니다. 이름을 다시 지으세요.',
'movedto'	     => '새 이름',
'movetalk'	    => '딸린 \'토론\'도 함께 옮깁니다.',
'talkpagemoved' => '딸린토론도 옮겼습니다.',
'talkpagenotmoved' => '딸린토론은 옮기지 <strong>않았습니다</strong>.',

# Spam protection

'subcategorycount' => '이 분류에 $1 개의 하위분류가 있습니다.', #'There are $1 subcategories to this category.',
'subcategorycount1' => '이 분류에 $1 개의 하위분류가 있습니다.', #'There is $1 subcategory to this category.',
'categoryarticlecount' => '이 분류에 $1 개의 문서가 있습니다.', #'There are $1 articles in this category.',
'categoryarticlecount1' => '이 분류에 $1 개의 문서가 있습니다.', #'There is $1 article in this category.',
'listingcontinuesabbrev' => ' (계속)', #' cont.',

);

class LanguageKo extends LanguageUtf8 {

	function getBookstoreList() {
		global $wgBookstoreListKo;
		return $wgBookstoreListKo;
	}

	function getNamespaces() {
		global $wgNamespaceNamesKo;
		return $wgNamespaceNamesKo;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsKo;
		return $wgQuickbarSettingsKo;
	}

	function getSkinNames() {
		global $wgSkinNamesKo;
		return $wgSkinNamesKo;
	}

	function date( $ts, $adj = false ) {
		global $wgWeekdayAbbreviationsKo;
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		# This is horribly inefficient; I need to rework this
		$x = getdate(mktime(( (int)substr( $ts, 8, 2) ),
			(int)substr( $ts, 10, 2 ), (int)substr( $ts, 12, 2 ),
			(int)substr( $ts, 4, 2 ), (int)substr( $ts, 6, 2 ),
			(int)substr( $ts, 0, 4 )));

		$d = substr( $ts, 0, 4 ) . "년 " .
			$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) . "월 " .
			(0 + substr( $ts, 6, 2 )) . "일 " .
			"(" . $wgWeekdayAbbreviationsKo[$x["wday"]] . ")";
		return $d;
	}

	function timeanddate( $ts, $adj = false ) {
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getMessage( $key ) {
		global $wgAllMessagesKo;
		return isset($wgAllMessagesKo[$key]) ? $wgAllMessagesKo[$key] : parent::getMessage($key);
	}

	function firstChar( $s ) {
		preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})/', $s, $matches);

		if ( isset( $matches[1] ) ) {
			if ( strlen( $matches[1] ) != 3 ) {
				return $matches[1];
			}
			$code = (ord($matches[1]{0}) & 0x0f) << 12;
			$code |= (ord($matches[1]{1}) & 0x3f) << 6;
			$code |= (ord($matches[1]{2}) & 0x3f);
			if ( $code < 0xac00 || 0xd7a4 <= $code) {
				return $matches[1];
			} elseif ( $code < 0xb098 ) {
				return "\xe3\x84\xb1";
			} elseif ( $code < 0xb2e4 ) {
				return "\xe3\x84\xb4";
			} elseif ( $code < 0xb77c ) {
				return "\xe3\x84\xb7";
			} elseif ( $code < 0xb9c8 ) {
				return "\xe3\x84\xb9";
			} elseif ( $code < 0xbc14 ) {
				return "\xe3\x85\x81";
			} elseif ( $code < 0xc0ac ) {
				return "\xe3\x85\x82";
			} elseif ( $code < 0xc544 ) {
				return "\xe3\x85\x85";
			} elseif ( $code < 0xc790 ) {
				return "\xe3\x85\x87";
			} elseif ( $code < 0xcc28 ) {
				return "\xe3\x85\x88";
			} elseif ( $code < 0xce74 ) {
				return "\xe3\x85\x8a";
			} elseif ( $code < 0xd0c0 ) {
				return "\xe3\x85\x8b";
			} elseif ( $code < 0xd30c ) {
				return "\xe3\x85\x8c";
			} elseif ( $code < 0xd558 ) {
				return "\xe3\x85\x8d";
			} else {
				return "\xe3\x85\x8e";
			}
		} else {
			return "";
		}
	}
}

?>
