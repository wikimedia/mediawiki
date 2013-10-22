<?php
/** Japanese (日本語)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author 2nd-player
 * @author Akaniji
 * @author Alexsh
 * @author Ant176
 * @author Aotake
 * @author Aphaia
 * @author Broad-Sky
 * @author Chatama
 * @author Chinneeb
 * @author Emk
 * @author Fievarsty
 * @author Fitoschido
 * @author Fryed-peach
 * @author Hatukanezumi
 * @author Hijiri
 * @author Hisagi
 * @author Hosiryuhosi
 * @author Iwai.masaharu
 * @author Joe Elkins
 * @author JtFuruhata
 * @author Kahusi
 * @author Kanon und wikipedia
 * @author Kkkdc
 * @author Klutzy
 * @author Koba-chan
 * @author Krinkle
 * @author Liangent
 * @author Likibp
 * @author Lovekhmer
 * @author Marine-Blue
 * @author MarkAHershberger
 * @author Miya
 * @author Mizusumashi
 * @author Muttley
 * @author Mzm5zbC3
 * @author Ohgi
 * @author Ort43v
 * @author Penn Station
 * @author Reedy
 * @author Schu
 * @author Shirayuki
 * @author Suisui
 * @author VZP10224
 * @author Vigorous action
 * @author W.CC
 * @author Web comic
 * @author Whym
 * @author Yanajin66
 * @author לערי ריינהארט
 * @author 欅
 * @author 蝋燭α
 * @author 青子守歌
 * @author 아라
 */

$datePreferences = array(
	'default',
	'nengo',
	'ISO 8601',
);

$defaultDateFormat = 'ja';

$dateFormats = array(
	'ja time'    => 'H:i',
	'ja date'    => 'Y年n月j日 (D)',
	'ja both'    => 'Y年n月j日 (D) H:i',

	'nengo time' => 'H:i',
	'nengo date' => 'xtY年n月j日 (D)',
	'nengo both' => 'xtY年n月j日 (D) H:i',
);

$namespaceNames = array(
	NS_MEDIA            => 'メディア',
	NS_SPECIAL          => '特別',
	NS_TALK             => 'トーク',
	NS_USER             => '利用者',
	NS_USER_TALK        => '利用者・トーク',
	NS_PROJECT_TALK     => '$1・トーク',
	NS_FILE             => 'ファイル',
	NS_FILE_TALK        => 'ファイル・トーク',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki・トーク',
	NS_TEMPLATE         => 'テンプレート',
	NS_TEMPLATE_TALK    => 'テンプレート・トーク',
	NS_HELP             => 'ヘルプ',
	NS_HELP_TALK        => 'ヘルプ・トーク',
	NS_CATEGORY         => 'カテゴリ',
	NS_CATEGORY_TALK    => 'カテゴリ・トーク',
);

$namespaceAliases = array(
	'ノート'           => NS_TALK,
	'利用者‐会話'        => NS_USER_TALK,
	'$1‐ノート'        => NS_PROJECT_TALK,
	'画像'            => NS_FILE,
	'画像‐ノート'        => NS_FILE_TALK,
	'ファイル‐ノート'      => NS_FILE_TALK,
	'MediaWiki‐ノート' => NS_MEDIAWIKI_TALK,
	'Template‐ノート'  => NS_TEMPLATE_TALK,
	'Help‐ノート'      => NS_HELP_TALK,
	'Category‐ノート'  => NS_CATEGORY_TALK
);

$specialPageAliases = array(
	'Activeusers'               => array( '活動中の利用者', '活動中の利用者一覧' ),
	'Allmessages'               => array( 'メッセージ一覧', 'システムメッセージの一覧', '表示メッセージの一覧' ),
	'Allpages'                  => array( 'ページ一覧', '全ページ' ),
	'Ancientpages'              => array( '更新されていないページ' ),
	'Badtitle'                  => array( '不正なページ名' ),
	'Blankpage'                 => array( '白紙ページ' ),
	'Block'                     => array( '投稿ブロック', 'ブロック' ),
	'Blockme'                   => array( '自己ブロック' ),
	'Booksources'               => array( '文献資料', '書籍情報源' ),
	'BrokenRedirects'           => array( '迷子のリダイレクト', '壊れたリダイレクト' ),
	'Categories'                => array( 'カテゴリ', 'カテゴリ一覧' ),
	'ChangeEmail'               => array( 'メールアドレスの変更' ),
	'ChangePassword'            => array( 'パスワードの変更', 'パスワード変更', 'パスワード再発行', 'パスワードの再発行' ),
	'ComparePages'              => array( 'ページの比較' ),
	'Confirmemail'              => array( 'メールアドレスの確認' ),
	'Contributions'             => array( '投稿記録' ),
	'CreateAccount'             => array( 'アカウント作成', 'アカウントの作成' ),
	'Deadendpages'              => array( '有効なページへのリンクがないページ', '行き止まりページ' ),
	'DeletedContributions'      => array( '削除された投稿記録', '削除された投稿履歴', '削除歴' ),
	'Disambiguations'           => array( '曖昧さ回避のページ', '曖昧さ回避' ),
	'DoubleRedirects'           => array( '二重リダイレクト', '二重転送' ),
	'EditWatchlist'             => array( 'ウォッチリストの編集', 'ウォッチリスト編集' ),
	'Emailuser'                 => array( 'メール送信', 'ウィキメール' ),
	'Export'                    => array( 'データ書き出し', 'データー書き出し', 'エクスポート' ),
	'Fewestrevisions'           => array( '編集履歴の少ないページ', '版の少ない項目', '版の少ないページ' ),
	'FileDuplicateSearch'       => array( '重複ファイル検索' ),
	'Filepath'                  => array( 'パスの取得' ),
	'Import'                    => array( 'データ取り込み', 'データー取り込み', 'インポート' ),
	'Invalidateemail'           => array( 'メール無効化', 'メール無効' ),
	'JavaScriptTest'            => array( 'JavaScriptテスト', 'JavaScript試験' ),
	'BlockList'                 => array( 'ブロック一覧', 'ブロックの一覧' ),
	'LinkSearch'                => array( '外部リンク検索' ),
	'Listadmins'                => array( '管理者一覧' ),
	'Listbots'                  => array( 'ボット一覧', 'Bot一覧' ),
	'Listfiles'                 => array( 'ファイル一覧', 'ファイルリスト' ),
	'Listgrouprights'           => array( '利用者グループ権限', '利用者グループの権限一覧', '利用者権限一覧' ),
	'Listredirects'             => array( 'リダイレクト一覧', 'リダイレクトの一覧', 'リダイレクトリスト' ),
	'Listusers'                 => array( '登録利用者一覧', '登録利用者の一覧' ),
	'Lockdb'                    => array( 'データベースロック' ),
	'Log'                       => array( 'ログ', '記録' ),
	'Lonelypages'               => array( '孤立しているページ' ),
	'Longpages'                 => array( '長いページ' ),
	'MergeHistory'              => array( '履歴統合' ),
	'MIMEsearch'                => array( 'MIME検索', 'MIMEタイプ検索' ),
	'Mostcategories'            => array( 'カテゴリの多いページ', 'カテゴリの多い項目' ),
	'Mostimages'                => array( '被リンクの多いファイル', '使用箇所の多いファイル' ),
	'Mostinterwikis'            => array( 'ウィキ間リンクの多いページ' ),
	'Mostlinked'                => array( '被リンクの多いページ' ),
	'Mostlinkedcategories'      => array( '被リンクの多いカテゴリ' ),
	'Mostlinkedtemplates'       => array( '使用箇所の多いテンプレート', '被リンクの多いテンプレート' ),
	'Mostrevisions'             => array( '編集履歴の多いページ', '版の多い項目', '版の多いページ' ),
	'Movepage'                  => array( '移動', 'ページの移動' ),
	'Mycontributions'           => array( '自分の投稿記録' ),
	'Mypage'                    => array( '利用者ページ', 'マイページ', 'マイ・ページ' ),
	'Mytalk'                    => array( 'トークページ', '会話ページ', 'マイトーク', 'マイ・トーク' ),
	'Myuploads'                 => array( '自分のアップロード記録' ),
	'Newimages'                 => array( '新着ファイル', '新しいファイルの一覧', '新着画像展示室' ),
	'Newpages'                  => array( '新しいページ' ),
	'PagesWithProp'             => array( 'プロパティがあるページ' ),
	'PasswordReset'             => array( 'パスワード再設定', 'パスワードの再設定', 'パスワードのリセット', 'パスワードリセット' ),
	'PermanentLink'             => array( '固定リンク', 'パーマリンク' ),
	'Popularpages'              => array( '人気ページ' ),
	'Preferences'               => array( '個人設定', 'オプション' ),
	'Prefixindex'               => array( '前方一致ページ一覧', '始点指定ページ一覧' ),
	'Protectedpages'            => array( '保護されているページ' ),
	'Protectedtitles'           => array( '作成保護されているページ名' ),
	'Randompage'                => array( 'おまかせ表示' ),
	'Randomredirect'            => array( 'おまかせリダイレクト', 'おまかせ転送' ),
	'Recentchanges'             => array( '最近の更新', '最近更新したページ' ),
	'Recentchangeslinked'       => array( '関連ページの更新状況', 'リンク先の更新状況' ),
	'Redirect'                  => array( '転送', 'リダイレクト' ),
	'Revisiondelete'            => array( '版指定削除', '特定版削除' ),
	'Search'                    => array( '検索' ),
	'Shortpages'                => array( '短いページ' ),
	'Specialpages'              => array( '特別ページ一覧' ),
	'Statistics'                => array( '統計' ),
	'Tags'                      => array( 'タグ一覧' ),
	'Unblock'                   => array( 'ブロック解除' ),
	'Uncategorizedcategories'   => array( 'カテゴリ未導入のカテゴリ' ),
	'Uncategorizedimages'       => array( 'カテゴリ未導入のファイル' ),
	'Uncategorizedpages'        => array( 'カテゴリ未導入のページ' ),
	'Uncategorizedtemplates'    => array( 'カテゴリ未導入のテンプレート' ),
	'Undelete'                  => array( '復元', '復帰' ),
	'Unlockdb'                  => array( 'データベースロック解除', 'データベース解除' ),
	'Unusedcategories'          => array( '使われていないカテゴリ', '未使用カテゴリ' ),
	'Unusedimages'              => array( '使われていないファイル', '未使用ファイル', '未使用画像' ),
	'Unusedtemplates'           => array( '使われていないテンプレート', '未使用テンプレート' ),
	'Unwatchedpages'            => array( 'ウォッチされていないページ' ),
	'Upload'                    => array( 'アップロード' ),
	'UploadStash'               => array( '未公開アップロード' ),
	'Userlogin'                 => array( 'ログイン' ),
	'Userlogout'                => array( 'ログアウト' ),
	'Userrights'                => array( '利用者権限', '利用者権限の変更' ),
	'Version'                   => array( 'バージョン情報', 'バージョン' ),
	'Wantedcategories'          => array( '存在しないカテゴリへのリンク', '赤リンクカテゴリ' ),
	'Wantedfiles'               => array( 'ファイルページが存在しないファイル', '赤リンクファイル' ),
	'Wantedpages'               => array( '存在しないページへのリンク', '赤リンク' ),
	'Wantedtemplates'           => array( '存在しないテンプレートへのリンク', '赤リンクテンプレート' ),
	'Watchlist'                 => array( 'ウォッチリスト' ),
	'Whatlinkshere'             => array( 'リンク元' ),
	'Withoutinterwiki'          => array( '言語間リンクを持たないページ', '言語間リンクのないページ' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#転送', '#リダイレクト', '＃転送', '＃リダイレクト', '#REDIRECT' ),
	'notoc'                     => array( '0', '__目次非表示__', '＿＿目次非表示＿＿', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__ギャラリー非表示__', '＿＿ギャラリー非表示＿＿', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__目次強制__', '＿＿目次強制＿＿', '__FORCETOC__' ),
	'toc'                       => array( '0', '__目次__', '＿＿目次＿＿', '__TOC__' ),
	'noeditsection'             => array( '0', '__節編集非表示__', '__セクション編集非表示__', '＿＿セクション編集非表示＿＿', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', '現在の月', '協定月', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', '現在の月1', '協定月1', '協定月１', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', '現在の月名', '協定月名', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', '現在の月属格', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', '現在の月省略形', '省略協定月', '協定月省略', '協定月省略形', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', '現在の日', '協定日', 'CURRENTDAY' ),
	'currentday2'               => array( '1', '現在の日2', '協定日2', '協定日２', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', '現在の曜日名', '協定曜日', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', '現在の年', '協定年', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', '現在の時刻', '協定時間', '協定時刻', 'CURRENTTIME' ),
	'currenthour'               => array( '1', '現在の時', '協定時', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', '地方時の月', '現地月', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', '地方時の月1', '現地月1', '現地月１', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', '地方時の月名1', '現地月名', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', '地方時の月属格', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', '地方時の月省略形', '省略現地月', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', '地方時の日', '現地日', 'ローカルデイ', 'LOCALDAY' ),
	'localday2'                 => array( '1', '地方時の日2', '現地日2', '現地日２', 'LOCALDAY2' ),
	'localdayname'              => array( '1', '地方時の曜日名', '現地曜日', 'ローカルデイネーム', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', '地方時の年', '現地年', 'ローカルイヤー', 'LOCALYEAR' ),
	'localtime'                 => array( '1', '地方時の時刻', '現地時間', 'ローカルタイム', 'LOCALTIME' ),
	'localhour'                 => array( '1', '地方時の時', '現地時', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'ページ数', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', '記事数', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'ファイル数', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', '利用者数', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', '活動利用者数', '有効な利用者数', '有効利用者数', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', '編集回数', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', '閲覧回数', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'ページ名', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'ページ名E', 'ページ名Ｅ', 'PAGENAMEE' ),
	'namespace'                 => array( '1', '名前空間', 'NAMESPACE' ),
	'namespacee'                => array( '1', '名前空間E', '名前空間Ｅ', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'トーク空間', 'ノート空間', '会話空間', 'トークスペース', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'トーク空間E', 'トーク空間Ｅ', 'ノート空間E', '会話空間E', 'ノート空間Ｅ', '会話空間Ｅ', 'トークスペースE', 'トークスペースＥ', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', '主空間', '標準空間', '記事空間', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', '主空間E', '標準空間E', '標準空間Ｅ', '記事空間E', '記事空間Ｅ', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', '完全なページ名', 'フルページ名', '完全な記事名', '完全記事名', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', '完全なページ名E', 'フルページ名E', 'フルページ名Ｅ', '完全なページ名Ｅ', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'サブページ名', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'サブページ名E', 'サブページ名Ｅ', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', '親ページ名', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', '親ページ名E', '親ページ名Ｅ', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'トークページ名', '会話ページ名', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'トークページ名E', '会話ページ名E', '会話ページ名Ｅ', 'トークページ名Ｅ', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', '主ページ名', '記事ページ名', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', '主ページ名E', '記事ページ名E', '主ページ名Ｅ', '記事ページ名Ｅ', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'メッセージ:', 'MSG:' ),
	'subst'                     => array( '0', '展開:', '展開：', 'SUBST:' ),
	'safesubst'                 => array( '0', '安全展開:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'ウィキ無効メッセージ:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'サムネイル', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', '代替画像=$1', 'サムネイル=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', '右', 'right' ),
	'img_left'                  => array( '1', '左', 'left' ),
	'img_none'                  => array( '1', 'なし', '無し', 'none' ),
	'img_width'                 => array( '1', '$1ピクセル', '$1px' ),
	'img_center'                => array( '1', '中央', 'center', 'centre' ),
	'img_framed'                => array( '1', 'フレーム', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'フレームなし', 'frameless' ),
	'img_page'                  => array( '1', 'ページ=$1', 'ページ $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', '右上', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', '境界', 'ボーダー', 'border' ),
	'img_baseline'              => array( '1', '下線', 'ベースライン', 'baseline' ),
	'img_sub'                   => array( '1', '下付き', 'sub' ),
	'img_super'                 => array( '1', '上付き', 'super', 'sup' ),
	'img_top'                   => array( '1', '上端', 'top' ),
	'img_text_top'              => array( '1', '文上端', 'text-top' ),
	'img_middle'                => array( '1', '中心', 'middle' ),
	'img_bottom'                => array( '1', '下端', 'bottom' ),
	'img_text_bottom'           => array( '1', '文下端', 'text-bottom' ),
	'img_link'                  => array( '1', 'リンク=$1', 'link=$1' ),
	'img_alt'                   => array( '1', '代替文=$1', 'alt=$1' ),
	'int'                       => array( '0', 'インターフェース:', 'インタ:', 'インターフェース：', 'インタ：', 'インターフェイス:', 'インターフェイス：', 'INT:' ),
	'sitename'                  => array( '1', 'サイト名', 'サイトネーム', 'SITENAME' ),
	'ns'                        => array( '0', '名前空間:', '名前空間：', '名空:', '名空：', 'NS:' ),
	'nse'                       => array( '0', '名前空間E:', 'NSE:' ),
	'localurl'                  => array( '0', 'ローカルURL:', 'ローカルＵＲＬ：', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'ローカルURLE:', 'ローカルＵＲＬＥ：', 'LOCALURLE:' ),
	'articlepath'               => array( '0', '記事パス', 'ARTICLEPATH' ),
	'server'                    => array( '0', 'サーバー', 'サーバ', 'SERVER' ),
	'servername'                => array( '0', 'サーバー名', 'サーバーネーム', 'サーバ名', 'サーバネーム', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'スクリプトパス', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'スタイルパス', 'STYLEPATH' ),
	'grammar'                   => array( '0', '文法:', 'GRAMMAR:' ),
	'gender'                    => array( '0', '性別:', '性別：', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__タイトル変換無効__', '__タイトルコンバート拒否__', '＿＿タイトルコンバート拒否＿＿', '__タイトル非表示__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__内容変換無効__', '__内容変換抑制__', '＿＿内容変換抑制＿＿', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', '現在の週', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', '現在の曜日番号', 'CURRENTDOW' ),
	'localweek'                 => array( '1', '地方時の週', '現地週', 'ローカルウィーク', 'LOCALWEEK' ),
	'localdow'                  => array( '1', '地方時の曜日番号', 'LOCALDOW' ),
	'revisionid'                => array( '1', '版のID', 'リビジョンID', '差分ID', 'リビジョンＩＤ', '差分ＩＤ', 'REVISIONID' ),
	'revisionday'               => array( '1', '版の日', 'リビジョン日', '差分日', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', '版の日2', 'リビジョン日2', '差分日2', 'リビジョン日２', '差分日２', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', '版の月', 'リビジョン月', '差分月', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', '版の月1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', '版の年', 'リビジョン年', '差分年', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', '版のタイムスタンプ', 'リビジョンタイムスタンプ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', '版の利用者', 'リビジョンユーザー', 'リビジョンユーザ', 'リビジョン利用者', '差分利用者', 'REVISIONUSER' ),
	'plural'                    => array( '0', '複数:', '複数：', 'PLURAL:' ),
	'fullurl'                   => array( '0', '完全なURL:', 'フルURL:', '完全なＵＲＬ：', 'フルＵＲＬ：', 'FULLURL:' ),
	'fullurle'                  => array( '0', '完全なURLE:', 'フルURLE:', '完全なＵＲＬＥ：', 'フルＵＲＬＥ：', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', '先頭小文字:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', '先頭大文字:', 'UCFIRST:' ),
	'lc'                        => array( '0', '小文字:', 'LC:' ),
	'uc'                        => array( '0', '大文字:', 'UC:' ),
	'raw'                       => array( '0', '生:', 'RAW:' ),
	'displaytitle'              => array( '1', '表示タイトル:', 'DISPLAYTITLE' ),
	'rawsuffix'                 => array( '1', '生', 'R' ),
	'newsectionlink'            => array( '1', '__新しい節リンク__', '__新しいセクションリンク__', '__新セクションリンク__', '＿＿新しいセクションリンク＿＿', '＿＿新セクションリンク＿＿', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__新しい節リンク非表示__', '__新しいセクションリンク非表示__', '＿＿新しいセクションリンク非表示＿＿', '__新セクションリンク非表示__', '＿＿新セクションリンク非表示＿＿', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', '現在のバージョン', 'ウィキバージョン', 'MediaWikiバージョン', 'メディアウィキバージョン', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URLエンコード:', 'ＵＲＬエンコード：', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'アンカー用エンコード', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', '現在のタイムスタンプ', '協定タイムスタンプ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', '地方時のタイムスタンプ', '現地タイムスタンプ', 'ローカルタイムスタンプ', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', '方向印', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#言語:', '＃言語：', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', '内容言語', '記事言語', 'プロジェクト言語', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', '名前空間内ページ数', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', '管理者数', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', '数整形', 'FORMATNUM' ),
	'padleft'                   => array( '0', '補充左', 'PADLEFT' ),
	'padright'                  => array( '0', '補充右', 'PADRIGHT' ),
	'special'                   => array( '0', '特別', 'special' ),
	'defaultsort'               => array( '1', 'デフォルトソート:', 'デフォルトソート：', 'デフォルトソートキー:', 'デフォルトカテゴリソート:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ファイルパス:', 'ファイルパス：', 'FILEPATH:' ),
	'tag'                       => array( '0', 'タグ', 'tag' ),
	'hiddencat'                 => array( '1', '__カテゴリ非表示__', '__カテ非表示__', '__非表示カテ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'カテゴリ内ページ数', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'ページサイズ', 'PAGESIZE' ),
	'index'                     => array( '1', '__インデックス__', '＿＿インデックス＿＿', '__INDEX__' ),
	'noindex'                   => array( '1', '__インデックス拒否__', '＿＿インデックス拒否＿＿', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'グループ人数', 'グループ所属人数', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__静的転送__', '__二重転送解消無効__', '＿＿二重転送解消無効＿＿', '__二重転送修正無効__', '＿＿二重転送修正無効＿＿', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', '保護レベル', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', '日付整形', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'パス', 'PATH' ),
	'url_wiki'                  => array( '0', 'ウィキ', 'WIKI' ),
	'url_query'                 => array( '0', 'クエリ', 'クエリー', 'QUERY' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'リンクの下線:',
'tog-justify' => '段落に均等割り付けを設定',
'tog-hideminor' => '最近の更新に細部の編集を表示しない',
'tog-hidepatrolled' => '最近の更新に巡回済みの編集を表示しない',
'tog-newpageshidepatrolled' => '新しいページの一覧に巡回済みのページを表示しない',
'tog-extendwatchlist' => 'ウォッチリストを拡張し、最新のものだけではなくすべての変更を表示',
'tog-usenewrc' => '最近の更新とウォッチリストで複数の変更をページごとにまとめる',
'tog-numberheadings' => '見出しに番号を自動的に振る',
'tog-showtoolbar' => '編集用のツールバーを表示',
'tog-editondblclick' => 'ダブルクリックでページを編集',
'tog-editsection' => '[編集]リンクから節を編集できるようにする',
'tog-editsectiononrightclick' => '節見出しの右クリックで節を編集できるようにする',
'tog-showtoc' => '目次を表示 (ページに見出しが4つ以上ある場合)',
'tog-rememberpassword' => 'このブラウザーにログイン情報を保存 (最長 $1 {{PLURAL:$1|日|日間}})',
'tog-watchcreations' => '自分が作成したページやアップロードしたファイルをウォッチリストに追加',
'tog-watchdefault' => '自分が編集したページやファイルをウォッチリストに追加',
'tog-watchmoves' => '自分が移動したページやファイルをウォッチリストに追加',
'tog-watchdeletion' => '自分が削除したページやファイルをウォッチリストに追加',
'tog-minordefault' => '細部の編集に既定でチェックを入れる',
'tog-previewontop' => 'プレビューを編集ボックスの前に配置',
'tog-previewonfirst' => '編集開始時にもプレビューを表示',
'tog-nocache' => 'ブラウザーによるページのキャッシュを無効にする',
'tog-enotifwatchlistpages' => 'ウォッチリストにあるページやファイルが更新されたらメールを受け取る',
'tog-enotifusertalkpages' => '自分のトークページが更新されたらメールを受け取る',
'tog-enotifminoredits' => 'ページやファイルへの細部の編集でもメールを受け取る',
'tog-enotifrevealaddr' => '通知メールで自分のメールアドレスを明示',
'tog-shownumberswatching' => 'ページをウォッチしている利用者数を表示',
'tog-oldsig' => '既存の署名:',
'tog-fancysig' => '署名をウィキ文として扱う (自動リンクなし)',
'tog-uselivepreview' => 'ライブプレビューを使用 (開発中)',
'tog-forceeditsummary' => '要約欄が空欄の場合に確認を促す',
'tog-watchlisthideown' => 'ウォッチリストに自分の編集を表示しない',
'tog-watchlisthidebots' => 'ウォッチリストにボットによる編集を表示しない',
'tog-watchlisthideminor' => 'ウォッチリストに細部の編集を表示しない',
'tog-watchlisthideliu' => 'ウォッチリストにログイン利用者による編集を表示しない',
'tog-watchlisthideanons' => 'ウォッチリストに匿名利用者による編集を表示しない',
'tog-watchlisthidepatrolled' => 'ウォッチリストに巡回済みの編集を表示しない',
'tog-ccmeonemails' => '他の利用者に送信したメールの控えを自分にも送信',
'tog-diffonly' => '差分の下にページ内容を表示しない',
'tog-showhiddencats' => '隠しカテゴリを表示',
'tog-noconvertlink' => 'リンクタイトル変換を無効にする',
'tog-norollbackdiff' => '巻き戻し後の差分を表示しない',
'tog-useeditwarning' => '変更を保存せずに編集画面から離れようとしたら警告',
'tog-prefershttps' => 'ログインする際、常に SSL (https) 接続を使用する',

'underline-always' => '常に付ける',
'underline-never' => '常に付けない',
'underline-default' => '外装またはブラウザーの既定値を使用',

# Font style option in Special:Preferences
'editfont-style' => '編集エリアのフォント:',
'editfont-default' => 'ブラウザーの設定を使用',
'editfont-monospace' => '等幅フォント',
'editfont-sansserif' => 'サンセリフ体のフォント',
'editfont-serif' => 'セリフ体のフォント',

# Dates
'sunday' => '日曜日',
'monday' => '月曜日',
'tuesday' => '火曜日',
'wednesday' => '水曜日',
'thursday' => '木曜日',
'friday' => '金曜日',
'saturday' => '土曜日',
'sun' => '日',
'mon' => '月',
'tue' => '火',
'wed' => '水',
'thu' => '木',
'fri' => '金',
'sat' => '土',
'january' => '1月',
'february' => '2月',
'march' => '3月',
'april' => '4月',
'may_long' => '5月',
'june' => '6月',
'july' => '7月',
'august' => '8月',
'september' => '9月',
'october' => '10月',
'november' => '11月',
'december' => '12月',
'january-gen' => '1月',
'february-gen' => '2月',
'march-gen' => '3月',
'april-gen' => '4月',
'may-gen' => '5月',
'june-gen' => '6月',
'july-gen' => '7月',
'august-gen' => '8月',
'september-gen' => '9月',
'october-gen' => '10月',
'november-gen' => '11月',
'december-gen' => '12月',
'jan' => '1月',
'feb' => '2月',
'mar' => '3月',
'apr' => '4月',
'may' => '5月',
'jun' => '6月',
'jul' => '7月',
'aug' => '8月',
'sep' => '9月',
'oct' => '10月',
'nov' => '11月',
'dec' => '12月',
'january-date' => '1月$1日',
'february-date' => '2月$1日',
'march-date' => '3月$1日',
'april-date' => '4月$1日',
'may-date' => '5月$1日',
'june-date' => '6月$1日',
'july-date' => '7月$1日',
'august-date' => '8月$1日',
'september-date' => '9月$1日',
'october-date' => '10月$1日',
'november-date' => '11月$1日',
'december-date' => '12月$1日',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|カテゴリ}}',
'category_header' => 'カテゴリ「$1」にあるページ',
'subcategories' => '下位カテゴリ',
'category-media-header' => 'カテゴリ「$1」にあるメディア',
'category-empty' => "''このカテゴリには現在、ページやメディアが何もありません。''",
'hidden-categories' => '{{PLURAL:$1|隠しカテゴリ}}',
'hidden-category-category' => '隠しカテゴリ',
'category-subcat-count' => '{{PLURAL:$2|このカテゴリには以下の下位カテゴリのみが含まれています。|このカテゴリには $2 下位カテゴリが含まれており、そのうち以下の {{PLURAL:$1|$1 下位カテゴリ}}を表示しています。}}',
'category-subcat-count-limited' => 'このカテゴリには以下の{{PLURAL:$1|下位カテゴリ|​&#32;$1 下位カテゴリ}}が含まれています。',
'category-article-count' => '{{PLURAL:$2|このカテゴリには以下のページのみが含まれています。|このカテゴリには $2 ページが含まれており、そのうち以下の $1 ページを表示しています。}}',
'category-article-count-limited' => '現在のカテゴリには以下の{{PLURAL:$1|ページ|​&#32;$1 ページ}}が含まれています。',
'category-file-count' => '{{PLURAL:$2|このカテゴリには以下のファイルのみが含まれています。|このカテゴリには $2 ファイルが含まれており、そのうち以下の {{PLURAL:$1|$1 ファイル}}を表示しています。}}',
'category-file-count-limited' => '現在のカテゴリには以下の{{PLURAL:$1|ファイル|​&#32;$1 ファイル}}が含まれています。',
'listingcontinuesabbrev' => 'の続き',
'index-category' => '検索エンジンに収集されるページ',
'noindex-category' => '検索エンジンに収集されないページ',
'broken-file-category' => '壊れたファイルへのリンクがあるページ',

'about' => '解説',
'article' => '本文',
'newwindow' => '(新しいウィンドウで開きます)',
'cancel' => '中止',
'moredotdotdot' => '続き...',
'morenotlisted' => 'この一覧の続き',
'mypage' => 'ページ',
'mytalk' => 'トーク',
'anontalk' => 'このIPアドレスのトーク',
'navigation' => '案内',
'and' => '&#32;および&#32;',

# Cologne Blue skin
'qbfind' => '検索',
'qbbrowse' => '閲覧',
'qbedit' => '編集',
'qbpageoptions' => 'このページについて',
'qbmyoptions' => '自分のページ',
'qbspecialpages' => '特別ページ',
'faq' => 'よくある質問と回答',
'faqpage' => 'Project:よくある質問と回答',

# Vector skin
'vector-action-addsection' => '話題追加',
'vector-action-delete' => '削除',
'vector-action-move' => '移動',
'vector-action-protect' => '保護',
'vector-action-undelete' => '復元',
'vector-action-unprotect' => '保護再設定',
'vector-simplesearch-preference' => '簡素化した検索バーを有効にする (ベクター外装のみ)',
'vector-view-create' => '作成',
'vector-view-edit' => '編集',
'vector-view-history' => '履歴表示',
'vector-view-view' => '閲覧',
'vector-view-viewsource' => 'ソースを閲覧',
'actions' => '操作',
'namespaces' => '名前空間',
'variants' => '変種',

'navigation-heading' => '案内メニュー',
'errorpagetitle' => 'エラー',
'returnto' => '$1 に戻る。',
'tagline' => '提供: {{SITENAME}}',
'help' => 'ヘルプ',
'search' => '検索',
'searchbutton' => '検索',
'go' => '表示',
'searcharticle' => '表示',
'history' => 'ページの履歴',
'history_short' => '履歴',
'updatedmarker' => '最終閲覧以降に変更されました',
'printableversion' => '印刷用バージョン',
'permalink' => 'この版への固定リンク',
'print' => '印刷',
'view' => '閲覧',
'edit' => '編集',
'create' => '作成',
'editthispage' => 'このページを編集',
'create-this-page' => 'このページを作成',
'delete' => '削除',
'deletethispage' => 'このページを削除',
'undeletethispage' => 'このページを復元',
'undelete_short' => '{{PLURAL:$1|$1 編集}}を復元',
'viewdeleted_short' => '{{PLURAL:$1|削除された $1 編集}}を閲覧',
'protect' => '保護',
'protect_change' => '設定変更',
'protectthispage' => 'このページを保護',
'unprotect' => '保護の設定変更',
'unprotectthispage' => 'このページの保護を変更',
'newpage' => '新規ページ',
'talkpage' => 'このページについて話し合う',
'talkpagelinktext' => 'トーク',
'specialpage' => '特別ページ',
'personaltools' => '個人用ツール',
'postcomment' => '新しい節',
'articlepage' => '本文を表示',
'talk' => '議論',
'views' => '表示',
'toolbox' => 'ツール',
'userpage' => '利用者ページを表示',
'projectpage' => 'プロジェクトのページを表示',
'imagepage' => 'ファイルのページを表示',
'mediawikipage' => 'メッセージのページを表示',
'templatepage' => 'テンプレートのページを表示',
'viewhelppage' => 'ヘルプのページを表示',
'categorypage' => 'カテゴリのページを表示',
'viewtalkpage' => '議論を表示',
'otherlanguages' => '他言語版',
'redirectedfrom' => '（$1から転送）',
'redirectpagesub' => '転送ページ',
'lastmodifiedat' => 'このページの最終更新日時は $1 $2 です。',
'viewcount' => 'このページは {{PLURAL:$1|$1 回}}アクセスされました。',
'protectedpage' => '保護されたページ',
'jumpto' => '移動:',
'jumptonavigation' => '案内',
'jumptosearch' => '検索',
'view-pool-error' => '申し訳ありませんが、現在サーバーに過大な負荷がかかっています。
このページを閲覧しようとする利用者が多すぎます。
しばらく時間を置いてから、もう一度このページにアクセスしてみてください。

$1',
'pool-timeout' => 'ロック待ちタイムアウト',
'pool-queuefull' => 'プールキューがいっぱいです',
'pool-errorunknown' => '不明なエラー',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => '{{SITENAME}}について',
'aboutpage' => 'Project:{{SITENAME}}について',
'copyright' => '特に記載がない限り、内容は$1のライセンスで利用できます。',
'copyrightpage' => '{{ns:project}}:著作権',
'currentevents' => '最近の出来事',
'currentevents-url' => 'Project:最近の出来事',
'disclaimers' => '免責事項',
'disclaimerpage' => 'Project:免責事項',
'edithelp' => '編集の仕方',
'helppage' => 'Help:目次',
'mainpage' => 'メインページ',
'mainpage-description' => 'メインページ',
'policy-url' => 'Project:方針',
'portal' => 'コミュニティ・ポータル',
'portal-url' => 'Project:コミュニティ・ポータル',
'privacy' => 'プライバシー・ポリシー',
'privacypage' => 'Project:プライバシー・ポリシー',

'badaccess' => '権限がありません',
'badaccess-group0' => '要求した操作を行うことは許可されていません。',
'badaccess-groups' => 'この操作は、以下の{{PLURAL:$2|グループ|グループのいずれか}}に属する利用者のみが実行できます: $1。',

'versionrequired' => 'MediaWiki のバージョン $1 が必要',
'versionrequiredtext' => 'このページの使用にはMediaWiki バージョン $1 が必要です。
[[Special:Version|バージョン情報]]をご覧ください。',

'ok' => 'OK',
'retrievedfrom' => '「$1」から取得',
'youhavenewmessages' => '$1があります ($2)。',
'newmessageslink' => '新着メッセージ',
'newmessagesdifflink' => '最新の差分',
'youhavenewmessagesfromusers' => '{{PLURAL:$3|他の利用者|$3 人の利用者}}からの$1があります ($2)。',
'youhavenewmessagesmanyusers' => '多数の利用者からの$1があります ($2)。',
'newmessageslinkplural' => '{{PLURAL:$1|新着メッセージ}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|最新の差分|最新版までの差分}}',
'youhavenewmessagesmulti' => '$1に新着メッセージがあります',
'editsection' => '編集',
'editold' => '編集',
'viewsourceold' => 'ソースを閲覧',
'editlink' => '編集',
'viewsourcelink' => 'ソースを閲覧',
'editsectionhint' => '節を編集: $1',
'toc' => '目次',
'showtoc' => '表示',
'hidetoc' => '非表示',
'collapsible-collapse' => '折り畳む',
'collapsible-expand' => '展開する',
'thisisdeleted' => '$1を閲覧または復元しますか?',
'viewdeleted' => '$1を閲覧しますか?',
'restorelink' => '{{PLURAL:$1|削除された$1編集}}',
'feedlinks' => 'フィード:',
'feed-invalid' => 'フィード形式の指定が正しくありません。',
'feed-unavailable' => 'フィードの配信は利用できません',
'site-rss-feed' => '$1のRSSフィード',
'site-atom-feed' => '$1のAtomフィード',
'page-rss-feed' => '「$1」のRSSフィード',
'page-atom-feed' => '「$1」のAtomフィード',
'feed-atom' => 'Atom',
'feed-rss' => 'RSS',
'red-link-title' => '$1 (存在しないページ)',
'sort-descending' => '降順に並べ替え',
'sort-ascending' => '昇順に並べ替え',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'ページ',
'nstab-user' => '利用者ページ',
'nstab-media' => 'メディアページ',
'nstab-special' => '特別ページ',
'nstab-project' => 'プロジェクトページ',
'nstab-image' => 'ファイル',
'nstab-mediawiki' => 'メッセージ',
'nstab-template' => 'テンプレート',
'nstab-help' => 'ヘルプ',
'nstab-category' => 'カテゴリ',

# Main script and global functions
'nosuchaction' => 'そのような操作はありません',
'nosuchactiontext' => 'この URL で指定された操作は正しくありません。
URL を間違って入力したか、正しくないリンクをたどった可能性があります。
{{SITENAME}}が利用するソフトウェアのバグの可能性もあります。',
'nosuchspecialpage' => 'そのような特別ページはありません',
'nospecialpagetext' => '<strong>要求された特別ページは存在しません。</strong>

有効な特別ページの一覧は[[Special:SpecialPages|{{int:specialpages}}]]にあります。',

# General errors
'error' => 'エラー',
'databaseerror' => 'データベース エラー',
'databaseerror-text' => 'データベース クエリのエラーが発生しました。
これはソフトウェアのバグである可能性があります。',
'databaseerror-textcl' => 'データベース クエリのエラーが発生しました。',
'databaseerror-query' => 'クエリ: $1',
'databaseerror-function' => '関数: $1',
'databaseerror-error' => 'エラー: $1',
'laggedslavemode' => "'''警告:''' ページに最新の編集が反映されていない可能性があります。",
'readonly' => 'データベースがロックされています',
'enterlockreason' => 'ロックの理由とロック解除の予定を入力してください',
'readonlytext' => 'データベースは現在、新しいページの追加や編集を受け付けない「ロック状態」になっています。これはおそらくデータベースの定期メンテナンスのためで、メンテナンス終了後は正常な状態に復帰します。

データベースをロックした管理者による説明は以下の通りです: $1',
'missing-article' => '指定されたページ「$1」$2 の本文がデータベース内で見つかりませんでした。

通常、削除されたページの版への古い差分表示や固定リンクをたどった際に、このようなことが起きます。

それ以外の操作でこのメッセージが表示された場合、ソフトウェアのバグである可能性があります。
[[Special:ListUsers/sysop|管理者]]までその URL を添えてお知らせください。',
'missingarticle-rev' => '(版番号: $1)',
'missingarticle-diff' => '(差分: $1, $2)',
'readonly_lag' => 'データベースはスレーブのデータベースサーバーがマスターに同期するまで自動的にロックされています',
'internalerror' => '内部エラー',
'internalerror_info' => '内部エラー: $1',
'fileappenderrorread' => '追加中に、「$1」を読み取れませんでした。',
'fileappenderror' => '「$1」を「$2」に追加できませんでした。',
'filecopyerror' => 'ファイル「$1」を「$2」に複製できませんでした。',
'filerenameerror' => 'ファイル名を「$1」から「$2」へ変更できませんでした。',
'filedeleteerror' => 'ファイル「$1」を削除できませんでした。',
'directorycreateerror' => 'ディレクトリ「$1」を作成できませんでした。',
'filenotfound' => 'ファイル「$1」が見つかりませんでした。',
'fileexistserror' => 'ファイル「$1」に書き込めませんでした: ファイルが存在します。',
'unexpected' => '予期しない値「$1」=「$2」です。',
'formerror' => 'エラー: フォームを送信できませんでした。',
'badarticleerror' => 'このページでは要求された操作を行えません。',
'cannotdelete' => 'ページまたはファイル「$1」を削除できませんでした。
他の人が既に削除した可能性があります。',
'cannotdelete-title' => '「$1」というページを削除できません',
'delete-hook-aborted' => 'フックによって削除が中止されました。
理由は不明です。',
'no-null-revision' => 'ページ「$1」に新しい空編集の版を作成できませんでした。',
'badtitle' => '正しくないページ名',
'badtitletext' => '無効または空のページ名が指定されたか、言語間/ウィキ間リンクの方法に誤りがあります。
ページ名に使用できない文字が含まれている可能性があります。',
'perfcached' => '以下のデータはキャッシュされており、最新ではない可能性があります。最大 $1 {{PLURAL:$1|件の結果}}がキャッシュされます。',
'perfcachedts' => '以下のデータはキャッシュされており、最終更新日時は $1 です。最大 $4 {{PLURAL:$4|件の結果}}がキャッシュされます。',
'querypage-no-updates' => 'ページの更新は無効になっています。
以下のデータの更新は現在行われていません。',
'wrong_wfQuery_params' => 'wfQuery() のパラメーターが無効です<br />
関数: $1<br />
クエリ: $2',
'viewsource' => 'ソースを表示',
'viewsource-title' => '$1のソースを表示',
'actionthrottled' => '操作が速度規制されました',
'actionthrottledtext' => '短時間にこの操作を大量に行ったため、スパム対策として設定されている制限を超えました。
少し時間をおいてからもう一度操作してください。',
'protectedpagetext' => 'このページは編集や他の操作ができないように保護されています。',
'viewsourcetext' => 'このページのソースの閲覧やコピーができます:',
'viewyourtext' => "このページへの'''あなたの編集'''のソースの閲覧やコピーができます:",
'protectedinterface' => 'このページにはこのウィキのソフトウェアのインターフェイスに使用されるテキストが保存されており、いたずらなどの防止のために保護されています。
すべてのウィキに対して翻訳を追加/変更する場合は、MediaWiki の地域化プロジェクト [//translatewiki.net/ translatewiki.net] を使用してください。',
'editinginterface' => "'''警告:''' ソフトウェアのインターフェイスに使用されるテキストのページを編集しています。
このページを変更すると、このウィキの他の利用者のユーザーインターフェイスの外観に影響します。
すべてのウィキに対して翻訳を追加/変更する場合は、MediaWiki の地域化プロジェクト [//translatewiki.net/wiki/Main_Page?setlang=ja translatewiki.net] を使用してください。",
'cascadeprotected' => 'このページは、「カスケード保護」が指定された状態で保護されている以下の{{PLURAL:$1|ページ|ページ群}}で読み込まれているため、編集できないように保護されています:
$2',
'namespaceprotected' => "'''$1'''名前空間にあるページを編集する権限がありません。",
'customcssprotected' => 'この CSS ページは他の利用者の個人設定を含んでいるため、あなたには編集する権限がありません。',
'customjsprotected' => 'この JavaScript ページは他の利用者の個人設定を含んでいるため、あなたには編集する権限がありません。',
'mycustomcssprotected' => 'あなたには CSS ページを編集する権限がありません。',
'mycustomjsprotected' => 'あなたには JavaScript ページを編集する権限がありません。',
'myprivateinfoprotected' => 'あなたには自身の非公開情報を編集する権限がありません。',
'mypreferencesprotected' => 'あなたには自身の個人設定を編集する権限がありません。',
'ns-specialprotected' => '特別ページは編集できません。',
'titleprotected' => "[[User:$1|$1]]によりこのページ名を持つページの作成は保護されています。
理由は「''$2''」です。",
'filereadonlyerror' => 'ファイルリポジトリ「$2」が読み取り専用の状態にあるため、ファイル「$1」を変更できません。

読み取り専用に設定した管理者からの説明:「$3」',
'invalidtitle-knownnamespace' => '名前空間名「$2」と名前「$3」の組み合わせはページ名として無効です',
'invalidtitle-unknownnamespace' => '不明な名前空間番号 $1 と名前「$2」の組み合わせはページ名として無効です',
'exception-nologin' => 'ログインしていません',
'exception-nologin-text' => 'このページまたは操作には、このウィキへのログインが必要です。',

# Virus scanner
'virus-badscanner' => "環境設定が不適合です: 不明なウイルス対策ソフトウェア: ''$1''",
'virus-scanfailed' => 'スキャンに失敗しました (コード $1)',
'virus-unknownscanner' => '不明なウイルス対策ソフトウェア:',

# Login and logout pages
'logouttext' => "'''ログアウトしました。'''

ページによっては、ブラウザーのキャッシュをクリアするまで、ログインしているかのように表示され続ける場合があるためご注意ください。",
'welcomeuser' => 'ようこそ、$1さん!',
'welcomecreation-msg' => 'アカウントが作成されました。
[[Special:Preferences|{{SITENAME}}の個人設定]]の変更も忘れないようにしてください。',
'yourname' => '利用者名:',
'userlogin-yourname' => '利用者名',
'userlogin-yourname-ph' => '利用者名を入力',
'createacct-another-username-ph' => '利用者名を入力',
'yourpassword' => 'パスワード:',
'userlogin-yourpassword' => 'パスワード',
'userlogin-yourpassword-ph' => 'パスワードを入力',
'createacct-yourpassword-ph' => 'パスワードを入力',
'yourpasswordagain' => 'パスワード再入力:',
'createacct-yourpasswordagain' => 'パスワード再入力',
'createacct-yourpasswordagain-ph' => 'パスワードを再入力',
'remembermypassword' => 'このブラウザーにログイン情報を保存 (最長 $1 {{PLURAL:$1|日|日間}})',
'userlogin-remembermypassword' => 'ログイン状態を保持',
'userlogin-signwithsecure' => 'SSL (https) 接続を使用',
'yourdomainname' => 'ドメイン:',
'password-change-forbidden' => 'このウィキではパスワードを変更できません。',
'externaldberror' => '認証データベースでエラーが発生した、または外部アカウントの更新が許可されていません。',
'login' => 'ログイン',
'nav-login-createaccount' => 'ログインまたはアカウント作成',
'loginprompt' => '{{SITENAME}}にログインするにはCookieを有効にする必要があります。',
'userlogin' => 'ログインまたはアカウント作成',
'userloginnocreate' => 'ログイン',
'logout' => 'ログアウト',
'userlogout' => 'ログアウト',
'notloggedin' => 'ログインしていません',
'userlogin-noaccount' => '登録がまだの場合',
'userlogin-joinproject' => '{{SITENAME}}のアカウントを作成',
'nologin' => '登録がまだの場合、$1。',
'nologinlink' => 'アカウントを作成してください',
'createaccount' => 'アカウント作成',
'gotaccount' => 'アカウントを既に持っている場合、$1。',
'gotaccountlink' => 'ログインしてください',
'userlogin-resetlink' => 'ログイン情報をお忘れですか?',
'userlogin-resetpassword-link' => 'パスワードを再設定',
'helplogin-url' => 'Help:ログイン',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|ログインのヘルプ]]',
'userlogin-loggedin' => '{{GENDER:$1|$1}} として既にログインしています。
別の利用者としてログインするには下のフォームを使用してください。',
'userlogin-createanother' => '別アカウントを作成',
'createacct-join' => '以下の情報を入力してください。',
'createacct-another-join' => '新しいアカウントの情報を以下に記入してください。',
'createacct-emailrequired' => 'メールアドレス',
'createacct-emailoptional' => 'メールアドレス (省略可能)',
'createacct-email-ph' => 'メールアドレスを入力',
'createacct-another-email-ph' => 'メールアドレスを入力',
'createaccountmail' => '一時的な無作為のパスワードを生成して、指定したメールアドレスに送信',
'createacct-realname' => '本名 (省略可能)',
'createaccountreason' => '理由:',
'createacct-reason' => '理由',
'createacct-reason-ph' => 'アカウントを作成する理由',
'createacct-captcha' => '自動作成防止チェック',
'createacct-imgcaptcha-ph' => '上に表示されている文字列を入力',
'createacct-submit' => 'アカウントを作成',
'createacct-another-submit' => '別アカウントを作成',
'createacct-benefit-heading' => '{{SITENAME}}は、あなたのような人々が創っています。',
'createacct-benefit-body1' => '{{PLURAL:$1|編集}}',
'createacct-benefit-body2' => '{{PLURAL:$1|ページ}}',
'createacct-benefit-body3' => '最近の{{PLURAL:$1|貢献者}}',
'badretype' => '入力したパスワードが一致しません。',
'userexists' => '入力された利用者名は既に使用されています。
他の名前を選んでください。',
'loginerror' => 'ログインのエラー',
'createacct-error' => 'アカウント作成エラー',
'createaccounterror' => 'アカウントを作成できませんでした: $1',
'nocookiesnew' => '利用者アカウントは作成されましたが、ログインしていません。
{{SITENAME}}では利用者のログインに Cookie を使用します。
ご使用のブラウザーでは Cookie が無効になっています。
Cookie を有効にしてから、新しい利用者名とパスワードでログインしてください。',
'nocookieslogin' => '{{SITENAME}}ではログインにCookieを使用します。
Cookieを無効にしているようです。
Cookieを有効にしてから、もう一度試してください。',
'nocookiesfornew' => '発信元を確認できなかったため、アカウントは作成されませんでした。
Cookieを有効にしていることを確認して、このページを再読込してもう一度試してください。',
'nocookiesforlogin' => '{{int:nocookieslogin}}',
'noname' => '利用者名を正しく指定していません。',
'loginsuccesstitle' => 'ログイン成功',
'loginsuccess' => "'''{{SITENAME}}に「$1」としてログインしました。'''",
'nosuchuser' => '「$1」という名前の利用者は見当たりません。
利用者名では大文字と小文字を区別します。
綴りが正しいことを確認するか、[[Special:UserLogin/signup|新たにアカウントを作成]]してください。',
'nosuchusershort' => '「$1」という名前の利用者は存在しません。
綴りを確認してください。',
'nouserspecified' => '利用者名を指定してください。',
'login-userblocked' => 'この利用者はブロックされています。ログインは拒否されます。',
'wrongpassword' => 'パスワードが間違っています。 
もう一度やり直してください。',
'wrongpasswordempty' => 'パスワードを空欄にはできません。
もう一度やり直してください。',
'passwordtooshort' => 'パスワードは {{PLURAL:$1|$1 文字}}以上にしてください。',
'password-name-match' => 'パスワードは利用者名とは異なる必要があります。',
'password-login-forbidden' => 'この利用者名とパスワードの使用は禁止されています。',
'mailmypassword' => '新しいパスワードをメールで送信',
'passwordremindertitle' => '{{SITENAME}}の仮パスワード通知',
'passwordremindertext' => '誰か (おそらくあなた) が IP アドレス $1 から{{SITENAME}} ($4) のログイン用パスワードの再発行を申請しました。
利用者「$2」の仮パスワードが作成され「$3」に設定されました。
もしあなたがこの申請をしたのであれば、ログインして新しいパスワードを決めてください。
この仮パスワードは {{PLURAL:$5|$5 日|$5 日間}}で有効期限が切れます。

この申請をしたのが他人の場合、あるいはパスワードを思い出してパスワード変更が不要になった場合は、
このメッセージを無視して、引き続き以前のパスワードを使用し続けることができます。',
'noemail' => '利用者「$1」のメールアドレスは登録されていません。',
'noemailcreate' => '有効なメールアドレスを入力する必要があります。',
'passwordsent' => '新しいパスワードを「$1」に登録されたメールアドレスにお送りしました。
メールが届いたら、再度ログインしてください。',
'blocked-mailpassword' => 'ご使用中のIPアドレスからの編集はブロックされており、不正利用防止のため、パスワードの再発行機能は使用できません。',
'eauthentsent' => '指定したメールアドレスに、アドレス確認のためのメールをお送りしました。
メールに記載された手順に従って、このアカウントの所有者であることの確認が取れると、このアカウント宛のメールを受け取れるようになります。',
'throttled-mailpassword' => 'パスワード再設定メールを過去 {{PLURAL:$1|$1 時間}}に送信済みです。
悪用防止のため、パスワードの再設定は {{PLURAL:$1|$1 時間}}に 1 回のみです。',
'mailerror' => 'メールを送信する際にエラーが発生しました: $1',
'acct_creation_throttle_hit' => 'あなたと同じ IP アドレスでこのウィキに訪れた人が、最近 24 時間で {{PLURAL:$1|$1 アカウント}}を作成しており、これはこの期間で作成が許可されている最大数です。
そのため、現在この IP アドレスではアカウントをこれ以上作成できません。',
'emailauthenticated' => 'メールアドレスは$2 $3に認証済みです。',
'emailnotauthenticated' => 'メールアドレスが認証されていません。
認証されるまで、以下のいかなる機能でもメールは送信されません。',
'noemailprefs' => 'これらの機能を有効にするには、個人設定でメールアドレスを登録してください。',
'emailconfirmlink' => 'あなたのメールアドレスを確認',
'invalidemailaddress' => '入力されたメールアドレスが正しい形式に従っていないため、受け付けられません。
正しい形式で入力し直すか、メールアドレス欄を空にしておいてください。',
'cannotchangeemail' => 'このウィキではアカウントのメールアドレスを変更できません。',
'emaildisabled' => 'このサイトではメールを送信できません。',
'accountcreated' => 'アカウントを作成しました',
'accountcreatedtext' => '利用者アカウント [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|トーク]]) を作成しました。',
'createaccount-title' => '{{SITENAME}}のアカウント作成',
'createaccount-text' => '誰か (おそらくあなた) が、{{SITENAME}} ($4) にあなたのメールアドレスのアカウントを作成しました。
アカウント名「$2」、パスワード「$3」です。
今すぐログインしてパスワードを変更してください。

このアカウントが何かの手違いで作成された場合は、このメッセージを無視してください。',
'usernamehasherror' => '利用者名には番号記号を含めることができません',
'login-throttled' => 'ログインの失敗が制限回数を超えました。
$1待ってから再度試してください。',
'login-abort-generic' => 'ログインに失敗しました - 中止',
'loginlanguagelabel' => '言語: $1',
'suspicious-userlogout' => '壊れたブラウザーまたはキャッシュプロキシによって送信された可能性があるため、ログアウト要求は拒否されました。',
'createacct-another-realname-tip' => '本名は省略できます。
入力すると、その利用者の著作物の帰属表示に使われます。',

# Email sending
'php-mail-error-unknown' => 'PHPのmail()関数での不明なエラーです。',
'user-mail-no-addy' => 'メールアドレスなしでメールを送信しようとしました。',
'user-mail-no-body' => '本文が空、またはあまりにも短いメールは送信できません。',

# Change password dialog
'resetpass' => 'パスワードの変更',
'resetpass_announce' => 'メールでお送りした仮パスワードでログインしました。
ログインを完了するには、ここで新しいパスワードを設定する必要があります:',
'resetpass_text' => '<!-- ここに文を挿入 -->',
'resetpass_header' => 'アカウントのパスワードの変更',
'oldpassword' => '古いパスワード:',
'newpassword' => '新しいパスワード:',
'retypenew' => '新しいパスワードを再入力:',
'resetpass_submit' => '再設定してログイン',
'changepassword-success' => 'パスワードを変更しました!',
'resetpass_forbidden' => 'パスワードは変更できません',
'resetpass-no-info' => 'このページに直接アクセスするためにはログインしている必要があります。',
'resetpass-submit-loggedin' => 'パスワードを変更',
'resetpass-submit-cancel' => '中止',
'resetpass-wrong-oldpass' => '仮パスワードまたは現在のパスワードが正しくありません。
パスワードを既に変更した、または新しい仮パスワードを依頼した可能性があります。',
'resetpass-temp-password' => '仮パスワード:',
'resetpass-abort-generic' => '拡張機能により、パスワードの変更は取り消されました。',

# Special:PasswordReset
'passwordreset' => 'パスワードの再設定',
'passwordreset-text-one' => 'パスワードを再設定するにはこのフォームに記入してください。',
'passwordreset-text-many' => '{{PLURAL:$1|パスワードを再設定するにはいずれかの欄に記入してください。}}',
'passwordreset-legend' => 'パスワードの再設定',
'passwordreset-disabled' => 'パスワードの再設定は、このウィキでは無効になっています。',
'passwordreset-emaildisabled' => 'メール機能は、このウィキでは無効化されています。',
'passwordreset-username' => '利用者名:',
'passwordreset-domain' => 'ドメイン:',
'passwordreset-capture' => 'お送りするメールの内容を表示しますか?',
'passwordreset-capture-help' => 'このボックスにチェックを入れると、利用者に送信されるメールの内容 (仮パスワードを含む) をあなたも閲覧できます。',
'passwordreset-email' => 'メールアドレス:',
'passwordreset-emailtitle' => '{{SITENAME}}上のアカウントの詳細',
'passwordreset-emailtext-ip' => '誰か (おそらくあなた、IP アドレス $1) が {{SITENAME}} ($4)
でのパスワードを再設定するよう申請しました。
以下の利用者{{PLURAL:$3|アカウント|アカウント群}}がこのメールアドレスと紐付けられています。

$2

{{PLURAL:$3|この仮パスワード|これらの仮パスワード}}は {{PLURAL:$5|$5 日|$5 日間}}で有効期限が切れます。
あなたはすぐにログインして新しいパスワードを設定する必要があります。
これが他の誰かによる申請である場合、あるいはあなたが自分の元のパスワードを
覚えていてそれを変更したくない場合には、このメッセージを無視して以前のパスワードを
使用し続けることができます。',
'passwordreset-emailtext-user' => '{{SITENAME}} の利用者 $1 があなたの {{SITENAME}} ($4)
でのパスワードを再設定するよう申請しました。
以下の利用者{{PLURAL:$3|アカウント|アカウント群}}がこのメールアドレスと紐付けられています。

$2

{{PLURAL:$3|この仮パスワード|これらの仮パスワード}}は{{PLURAL:$5|$5日}}で有効期限が切れます。
あなたは、すぐにログインして新しいパスワードを設定する必要があります。
この申請が他の誰かによるものの場合、あるいはあなたが自分の元のパスワードを
覚えていて、変更したくない場合は、このメッセージを無視して
以前のパスワードを使い続けることができます。',
'passwordreset-emailelement' => '利用者名: $1
仮パスワード: $2',
'passwordreset-emailsent' => 'パスワード再設定メールをお送りしました。',
'passwordreset-emailsent-capture' => '下記の内容の、パスワード再設定メールをお送りしました。',
'passwordreset-emailerror-capture' => '以下の内容のパスワード再設定メールを生成しましたが、{{GENDER:$2|利用者}}への送信に失敗しました: $1',

# Special:ChangeEmail
'changeemail' => 'メールアドレスの変更',
'changeemail-header' => 'アカウントのメールアドレスの変更',
'changeemail-text' => 'このフォームではメールアドレスを変更できます。この変更を確認するためにパスワードを入力する必要があります。',
'changeemail-no-info' => 'このページに直接アクセスするためにはログインしている必要があります。',
'changeemail-oldemail' => '現在のメールアドレス:',
'changeemail-newemail' => '新しいメールアドレス:',
'changeemail-none' => '(なし)',
'changeemail-password' => '{{SITENAME}}のパスワード:',
'changeemail-submit' => 'メールアドレスを変更',
'changeemail-cancel' => '中止',

# Special:ResetTokens
'resettokens' => 'トークンの再設定',
'resettokens-text' => 'ここでは、アカウントに関連付けられた特定の非公開データにアクセスするためのトークンを再設定できます。

トークンを誤って他人に教えてしまった場合やあなたのアカウントが侵害された場合は、必ず再設定してください。',
'resettokens-no-tokens' => '再設定できるトークンはありません。',
'resettokens-legend' => 'トークンの再設定',
'resettokens-tokens' => 'トークン:',
'resettokens-token-label' => '$1 (現在の値: $2)',
'resettokens-watchlist-token' => '[[Special:Watchlist|あなたのウォッチリストに登録されているページの変更]]を列挙するフィード (Atom/RSS) のトークン',
'resettokens-done' => 'トークンを再設定しました。',
'resettokens-resetbutton' => '選択したトークンを再設定',

# Edit page toolbar
'bold_sample' => '太字',
'bold_tip' => '太字',
'italic_sample' => '斜体',
'italic_tip' => '斜体',
'link_sample' => 'リンクの名前',
'link_tip' => '内部リンク',
'extlink_sample' => 'http://www.example.com リンクの名前',
'extlink_tip' => '外部リンク (http:// を忘れずに付けてください)',
'headline_sample' => '見出し文',
'headline_tip' => '2段目の見出し',
'nowiki_sample' => 'ここにマークアップを無効にするテキストを入力します',
'nowiki_tip' => 'ウィキ書式を無視',
'image_sample' => 'サンプル.jpg',
'image_tip' => 'ファイルの埋め込み',
'media_sample' => 'サンプル.ogg',
'media_tip' => 'ファイルへのリンク',
'sig_tip' => '時刻印付きの署名',
'hr_tip' => '水平線を挿入 (利用は控えめに)',

# Edit pages
'summary' => '編集内容の要約:',
'subject' => '題名/見出し:',
'minoredit' => 'これは細部の編集です',
'watchthis' => 'このページをウォッチ',
'savearticle' => 'ページを保存',
'preview' => 'プレビュー',
'showpreview' => 'プレビューを表示',
'showlivepreview' => 'ライブプレビュー',
'showdiff' => '差分を表示',
'anoneditwarning' => "'''警告:''' ログインしていません。
編集すると、IPアドレスがこのページの編集履歴に記録されます。",
'anonpreviewwarning' => "''ログインしていません。投稿を保存すると、ご使用中のIPアドレスがこのページの履歴に記録されます。''",
'missingsummary' => "'''注意:''' 編集内容の要約が空欄です。
「{{int:savearticle}}」をもう一度クリックすると、編集内容は要約なしで保存されます。",
'missingcommenttext' => '以下にコメントを入力してください。',
'missingcommentheader' => "'''注意:''' このコメントに対する題名/見出しが空欄です。
「{{int:savearticle}}」ボタンをもう一度押すと、空のまま編集が保存されます。",
'summary-preview' => '要約のプレビュー:',
'subject-preview' => '題名/見出しのプレビュー:',
'blockedtitle' => '利用者はブロックされています',
'blockedtext' => "'''この利用者名またはIPアドレスはブロックされています。'''

ブロックは$1によって実施されました。
ブロックの理由は ''$2'' です。

* ブロック開始日時: $8
* ブロック解除予定: $6
* ブロック対象: $7

このブロックについて、$1もしくは他の[[{{MediaWiki:Grouppage-sysop}}|管理者]]に問い合わせることができます。
ただし、[[Special:Preferences|個人設定]]で有効なメールアドレスが登録されていない場合、またはメール送信機能の使用がブロックされている場合、「この利用者にメールを送信」の機能は使えません。
現在ご使用中のIPアドレスは$3、このブロックIDは#$5です。
お問い合わせの際には、上記の情報を必ず書いてください。",
'autoblockedtext' => "このIPアドレスは、$1によりブロックされた利用者によって使用されたため、自動的にブロックされています。
理由は次の通りです。

:''$2''

* ブロック開始日時: $8
* ブロック解除予定: $6
* ブロック対象: $7

$1または他の[[{{MediaWiki:Grouppage-sysop}}|管理者]]にこのブロックについて問い合わせることができます。

ただし、[[Special:Preferences|個人設定]]に正しいメールアドレスが登録されていない場合、またはメール送信がブロックされている場合、メール送信機能が使えないことに注意してください。

現在ご使用中のIPアドレスは$3 、このブロックIDは#$5です。
お問い合わせの際は、上記の情報を必ず書いてください。",
'blockednoreason' => '理由が設定されていません',
'whitelistedittext' => 'このページを編集するには$1する必要があります。',
'confirmedittext' => 'ページの編集を始める前にメールアドレスの確認をする必要があります。
[[Special:Preferences|個人設定]]でメールアドレスを設定し、確認を行ってください。',
'nosuchsectiontitle' => '節が見つかりません',
'nosuchsectiontext' => '存在しない節を編集しようとしました。
ページを閲覧している間に移動あるいは削除された可能性があります。',
'loginreqtitle' => 'ログインが必要',
'loginreqlink' => 'ログイン',
'loginreqpagetext' => '他のページを閲覧するには$1する必要があります。',
'accmailtitle' => 'パスワードをお送りしました',
'accmailtext' => "[[User talk:$1|$1]]のために無作為に生成したパスワードを、$2に送信しました。パスワードは、ログインした際に''[[Special:ChangePassword|パスワード変更]]''ページで変更できます。",
'newarticle' => '(新)',
'newarticletext' => "まだ存在しないページへのリンクをたどりました。
このページを新規作成するには、ページの内容を以下のボックスに記入してください (詳しくは[[{{MediaWiki:Helppage}}|ヘルプページ]]を参照してください)。
誤ってこのページにたどり着いた場合には、ブラウザーの'''戻る'''ボタンで前のページに戻ってください。",
'anontalkpagetext' => "----
''このページはアカウントをまだ作成していないか使用していない匿名利用者のための議論ページです。''

匿名利用者を識別するために、利用者名の代わりにIPアドレスが使用されています。IP アドレスは複数の利用者で共有されている場合があります。もし、あなたが匿名利用者であり、自分に関係のないコメントが寄せられていると考えられる場合は、[[Special:UserLogin/signup|アカウントを作成する]]か[[Special:UserLogin|ログインして]]他の匿名利用者と間違えられないようにしてください。",
'noarticletext' => '現在このページには内容がありません。
他のページ内で[[Special:Search/{{PAGENAME}}|このページ名を検索]]、
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 関連する記録を検索]、
または[{{fullurl:{{FULLPAGENAME}}|action=edit}} このページを編集]</span>できます。',
'noarticletext-nopermission' => '現在このページには内容がありません。
他のページ内で[[Special:Search/{{PAGENAME}}|このページ名を検索]]、または<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} 関連する記録を検索]</span>できますが、あなたにはこのページを作成する権限はありません。',
'missing-revision' => '「{{PAGENAME}}」というページの版番号 $1 の版は存在しません。

通常、削除されたページの版への古い差分表示や固定リンクをたどった際に、このようなことが起きます。 
詳細は[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]を参照してください。',
'userpage-userdoesnotexist' => '「$1」という利用者アカウントは登録されていません。
このページの作成/編集が適切かご確認ください。',
'userpage-userdoesnotexist-view' => '利用者アカウント「$1」は登録されていません。',
'blocked-notice-logextract' => 'この利用者は現在ブロックされています。
参考のために最新のブロック記録項目を以下に表示します:',
'clearyourcache' => "'''注意:''' 保存後、変更を確認するにはブラウザーのキャッシュを消去する必要がある場合があります。
* '''Firefox / Safari:''' ''Shift'' を押しながら ''再読み込み'' をクリックするか、''Ctrl-F5'' または ''Ctrl-R'' を押してください (Mac では ''&#x2318;-R'')
* '''Google Chrome:''' ''Ctrl-Shift-R'' を押してください (Mac では ''&#x2318;-Shift-R'')
* '''Internet Explorer:''' ''Ctrl'' を押しながら ''最新の情報に更新'' をクリックするか、''Ctrl-F5'' を押してください
* '''Opera:''' ''ツール → 設定'' からキャッシュをクリアしてください。",
'usercssyoucanpreview' => "'''ヒント:'''「{{int:showpreview}}」ボタンを使うと、保存前に新しいCSSを試験できます。",
'userjsyoucanpreview' => "'''ヒント:'''「{{int:showpreview}}」ボタンを使うと、保存前に新しいJavaScriptを試験できます。",
'usercsspreview' => "'''利用者CSSをプレビューしているだけに過ぎません。'''
'''まだ保存されていません!'''",
'userjspreview' => "'''利用者JavaScriptを試験/プレビューしているだけに過ぎません。'''
'''まだ保存されていません!'''",
'sitecsspreview' => "'''ここでは、CSSをプレビューしているだけに過ぎません。'''
'''まだ保存されていません!'''",
'sitejspreview' => "'''ここでは、JavaScriptをプレビューしているだけに過ぎません。'''
'''まだ保存されていません!'''",
'userinvalidcssjstitle' => "'''警告:'''「$1」という外装はありません。
カスタム .css/.js ページではページ名を小文字にしてください。例: {{ns:user}}:Hoge/Vector.css ではなく {{ns:user}}:Hoge/vector.css",
'updated' => '(更新)',
'note' => "'''お知らせ:'''",
'previewnote' => "'''これはプレビューです。'''
変更内容はまだ保存されていません!",
'continue-editing' => '編集を続行',
'previewconflict' => 'これは、上の編集エリアの文章を保存した場合にどう表示されるかを示すプレビューです。',
'session_fail_preview' => "'''申し訳ありません! セッションデータが消失したため編集を処理できませんでした。'''
もう一度やり直してください。
それでも失敗する場合、[[Special:UserLogout|ログアウト]]してからログインし直してください。",
'session_fail_preview_html' => "'''申し訳ありません! セッション データが消失したため編集を処理できませんでした。'''

''{{SITENAME}}では生のHTMLが有効であり、JavaScriptでの攻撃を予防するためにプレビューを表示していません。''

'''この編集が問題ない場合はもう一度保存してください。'''
それでもうまくいかない場合は一度[[Special:UserLogout|ログアウト]]して、ログインし直してみてください。",
'token_suffix_mismatch' => "'''ご使用中のクライアントが編集トークン内の句読点を正しく処理していないため、編集を受け付けられません。'''
ページ本文の破損を防ぐため、編集は反映されません。
問題のある匿名プロキシ サービスを使用していると、これが発生する場合があります。",
'edit_form_incomplete' => "'''編集フォームの一部がサーバーに届きませんでした。ご確認の上、そのまま再度投稿してください。'''",
'editing' => '「$1」を編集中',
'creating' => '「$1」を作成中',
'editingsection' => '「$1」を編集中 (節単位)',
'editingcomment' => '「$1」を編集中 (新しい節)',
'editconflict' => '編集競合: $1',
'explainconflict' => "このページを編集し始めた後に、他の誰かがこのページを変更しました。
上側のテキスト領域は現在の最新の状態です。
編集していた文章は下側のテキスト領域に示されています。
編集していた文章を、上側のテキスト領域の、既存の文章に組み込んでください。
上側のテキスト領域の内容'''だけ'''が、「{{int:savearticle}}」をクリックした時に実際に保存されます。",
'yourtext' => '編集中の文章',
'storedversion' => '保存された版',
'nonunicodebrowser' => "'''警告: ご使用中のブラウザーは Unicode に未対応です。'''
安全にページを編集する回避策を表示しています: 編集ボックス内の非 ASCII 文字を 16 進数コードで表現しています。",
'editingold' => "'''警告: このページの古い版を編集しています。'''
保存すると、この版以降になされた変更がすべて失われます。",
'yourdiff' => '差分',
'copyrightwarning' => "{{SITENAME}}への投稿は、すべて$2 (詳細は$1を参照) のもとで公開したと見なされることにご注意ください。
あなたが投稿したものを、他人がよって遠慮なく編集し、それを自由に配布するのを望まない場合は、ここには投稿しないでください。<br />
また、投稿するのは、あなたが書いたものか、パブリック ドメインまたはそれに類するフリーな資料からの複製であることを約束してください。
'''著作権保護されている作品を、許諾なしに投稿しないでください!'''",
'copyrightwarning2' => "{{SITENAME}}へのすべての投稿は、他の利用者によって編集、変更、除去される場合があります。
あなたの投稿を、他人が遠慮なく編集するのを望まない場合は、ここには投稿しないでください。<br />
また、投稿するのは、あなたが書いたものか、パブリック ドメインまたはそれに類するフリーな資料からの複製であることを約束してください (詳細は$1を参照)。
'''著作権保護されている作品を、許諾なしに投稿してはいけません!'''",
'longpageerror' => "'''エラー: 投稿された文章は {{PLURAL:$1|$1 KB}} の長さがあります。これは投稿できる最大の長さ {{PLURAL:$2|$2 KB}} を超えています。'''
この編集内容は保存できません。",
'readonlywarning' => "'''警告: データベースがメンテナンスのためロックされており、現在は編集内容を保存できません。'''
必要であれば文章をコピー&amp;ペーストしてテキストファイルとして保存し、後ほど保存をやり直してください。

データベースをロックした管理者による説明は以下の通りです: $1",
'protectedpagewarning' => "'''警告: このページは保護されているため、管理者権限を持つ利用者のみが編集できます。'''
参考として以下に最後の記録を表示します:",
'semiprotectedpagewarning' => "'''注意:''' このページは保護されているため、登録利用者のみが編集できます。
参考として以下に最後の記録を表示します:",
'cascadeprotectedwarning' => "'''警告:''' このページはカスケード保護されている以下の{{PLURAL:$1|ページ|ページ群}}から読み込まれているため、管理者権限を持つ利用者のみが編集できるように保護されています:",
'titleprotectedwarning' => "'''警告: このページは保護されているため、作成には[[Special:ListGroupRights|特定の権限]]が必要です。'''
参考として以下に最後の記録を表示します:",
'templatesused' => 'このページで使用されている{{PLURAL:$1|テンプレート}}:',
'templatesusedpreview' => 'このプレビューで使用されている{{PLURAL:$1|テンプレート}}:',
'templatesusedsection' => 'この節で使用されている{{PLURAL:$1|テンプレート}}:',
'template-protected' => '（保護）',
'template-semiprotected' => '(半保護)',
'hiddencategories' => 'このページは {{PLURAL:$1|$1 個の隠しカテゴリ}}に属しています:',
'edittools' => '<!-- ここに書いたテキストは編集およびアップロードのフォームの下に表示されます。 -->',
'nocreatetext' => '{{SITENAME}}ではページの新規作成を制限しています。
元のページに戻って既存のページを編集するか、[[Special:UserLogin|ログインまたはアカウント作成]]をしてください。',
'nocreate-loggedin' => '新しいページを作成する権限がありません。',
'sectioneditnotsupported-title' => '節単位編集はサポートされていません',
'sectioneditnotsupported-text' => 'このページでは節単位編集はサポートされません。',
'permissionserrors' => '権限エラー',
'permissionserrorstext' => 'あなたにはこの操作を行う権限はありません。{{PLURAL:$1|理由}}は以下の通りです:',
'permissionserrorstext-withaction' => 'あなたには「$2」を行う権限はありません。{{PLURAL:$1|理由}}は以下の通りです:',
'recreate-moveddeleted-warn' => "'''警告: 以前削除されたページを再作成しようとしています。'''

このページの編集を続行するのが適切かどうかご確認ください。
参考までに、このページの削除と移動の記録を以下に示します:",
'moveddeleted-notice' => 'このページは削除されています。
参考のため、このページの削除と移動の記録を以下に表示します。',
'log-fulllog' => '完全な記録を閲覧',
'edit-hook-aborted' => 'フックによって編集が破棄されました。
理由は不明です。',
'edit-gone-missing' => 'ページを更新できませんでした。
既に削除されているようです。',
'edit-conflict' => '編集が競合。',
'edit-no-change' => '文章が変更されていないため、編集は無視されました。',
'postedit-confirmation' => '編集を保存しました。',
'edit-already-exists' => '新しいページを作成できませんでした。
そのページは既に存在します。',
'defaultmessagetext' => '既定のメッセージ文',
'content-failed-to-parse' => '$2のコンテンツを$1モデルとして構文解析できませんでした: $3',
'invalid-content-data' => '本文データが無効です',
'content-not-allowed-here' => 'ページ [[$2]] では、「$1」コンテンツは許可されていません',
'editwarning-warning' => 'このページを離れると、あなたが行なった変更がすべて失われてしまうかもしれません。
ログインしている場合、個人設定の「編集」タブでこの警告を表示しないようにすることができます。',

# Content models
'content-model-wikitext' => 'ウィキテキスト',
'content-model-text' => 'プレーンテキスト',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''警告:''' このページでは、高負荷なパーサー関数の呼び出し回数が多過ぎます。

{{PLURAL:$2|呼び出しを $2 回}}未満にしてください ({{PLURAL:$1|現在は $1 回}})。",
'expensive-parserfunction-category' => '高負荷な構文解析関数の呼び出しが多過ぎるページ',
'post-expand-template-inclusion-warning' => "'''警告:''' テンプレートの読み込みサイズが大き過ぎます。
いくつかのテンプレートは読み込まれません。",
'post-expand-template-inclusion-category' => 'テンプレート読み込みサイズが制限値を越えているページ',
'post-expand-template-argument-warning' => "'''警告:''' このページは、展開後のサイズが大きすぎるテンプレート引数を少なくとも 1 つ含んでいます。
これらの引数を省略しました。",
'post-expand-template-argument-category' => '省略されたテンプレート引数を含むページ',
'parser-template-loop-warning' => 'テンプレートのループを検出しました: [[$1]]',
'parser-template-recursion-depth-warning' => 'テンプレートの再帰の深さ ($1) が上限を超えました',
'language-converter-depth-warning' => '言語変換機能の深さ ($1) が制限を超えました',
'node-count-exceeded-category' => 'ノード数が制限を超えたページ',
'node-count-exceeded-warning' => 'ページがノード数の制限を超えました',
'expansion-depth-exceeded-category' => '展開の深さ制限を超えたページ',
'expansion-depth-exceeded-warning' => 'ページが展開の深さ制限を超えました',
'parser-unstrip-loop-warning' => 'unstrip のループを検出しました',
'parser-unstrip-recursion-limit' => 'unstrip の再帰 ($1) が上限を超えました',
'converter-manual-rule-error' => '手動の言語変換規則でエラーを検出しました。',

# "Undo" feature
'undo-success' => 'この編集を取り消せます。
下記の差分を確認して、本当に取り消していいか検証してください。よろしければ変更を保存して取り消しを完了してください。',
'undo-failure' => '中間の版での編集と競合したため、取り消せませんでした。',
'undo-norev' => '取り消そうとした編集が存在しないか削除済みのため取り消せませんでした。',
'undo-summary' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|トーク]]) による版 $1 を取り消し',
'undo-summary-username-hidden' => '秘匿された利用者による版 $1 を取り消し',

# Account creation failure
'cantcreateaccounttitle' => 'アカウントを作成できません',
'cantcreateaccount-text' => "このIPアドレス('''$1''')からのアカウント作成は[[User:$3|$3]]によってブロックされています。

$3が示した理由: ''$2''",

# History pages
'viewpagelogs' => 'このページの記録を閲覧',
'nohistory' => 'このページには編集履歴がありません。',
'currentrev' => '最新版',
'currentrev-asof' => '$1時点における最新版',
'revisionasof' => '$1時点における版',
'revision-info' => '$1時点における$2による版',
'previousrevision' => '←前の版',
'nextrevision' => '次の版→',
'currentrevisionlink' => '最新版',
'cur' => '最新',
'next' => '次',
'last' => '前',
'page_first' => '先頭',
'page_last' => '末尾',
'histlegend' => "差分の選択: 比較したい版のラジオボタンを選択し、Enterキーを押すか、下部のボタンを押します。<br />
凡例: '''({{int:cur}})'''＝最新版との比較、'''({{int:last}})'''＝直前の版との比較、'''{{int:minoreditletter}}'''＝細部の編集",
'history-fieldset-title' => '履歴の閲覧',
'history-show-deleted' => '削除済みのみ',
'histfirst' => '最古',
'histlast' => '最新',
'historysize' => '({{PLURAL:$1|$1バイト}})',
'historyempty' => '(空)',

# Revision feed
'history-feed-title' => '変更履歴',
'history-feed-description' => 'このウィキのこのページに関する変更履歴',
'history-feed-item-nocomment' => '$2に$1による',
'history-feed-empty' => '要求されたページは存在しません。
このウィキから既に削除されたか、名前が変更された可能性があります。
[[Special:Search|このウィキの検索]]で関連する新しいページを探してみてください。',

# Revision deletion
'rev-deleted-comment' => '(要約は除去されています)',
'rev-deleted-user' => '(利用者名は除去されています)',
'rev-deleted-event' => '(記録は除去されています)',
'rev-deleted-user-contribs' => '[利用者名またはIPアドレスは除去されました - その編集は投稿記録で非表示にされています]',
'rev-deleted-text-permission' => "この版は'''削除されています'''。
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]に詳細情報があるかもしれません。",
'rev-deleted-text-unhide' => "この版は'''削除されています'''。
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]に詳細情報があるかもしれません。
このまま[$1 この版を閲覧]できます。",
'rev-suppressed-text-unhide' => "この版は'''秘匿されています'''。
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 秘匿記録]に詳細情報があるかもしれません。
このまま[$1 この版を閲覧]できます。",
'rev-deleted-text-view' => "この版は'''削除されています'''。
内容を閲覧できます。[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]に詳細情報があるかもしれません。",
'rev-suppressed-text-view' => "この版は'''秘匿されています'''。
内容を閲覧できます。[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 秘匿記録]に詳細情報があるかもしれません。",
'rev-deleted-no-diff' => "どちらかの版が'''削除されているため'''、差分表示できません。
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]に詳細情報があるかもしれません。",
'rev-suppressed-no-diff' => "どちらかの版が'''削除されているため'''、差分表示できません。",
'rev-deleted-unhide-diff' => "この差分の一方の版は'''削除されています'''。
[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]に詳細情報があるかもしれません。
このまま[$1 この差分を閲覧]できます。",
'rev-suppressed-unhide-diff' => "この差分の一方の版は'''秘匿されています'''。
[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 秘匿記録]に詳細情報があるかもしれません。
このまま[$1 この差分を閲覧]できます。",
'rev-deleted-diff-view' => "この差分の一方の版は'''削除されています'''。
この差分を閲覧できます。[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]に詳細情報があるかもしれません。",
'rev-suppressed-diff-view' => "この差分の一方の版は'''秘匿されています'''。
この差分を閲覧できます。[{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} 秘匿記録]に詳細情報があるかもしれません。",
'rev-delundel' => '表示/非表示',
'rev-showdeleted' => '表示',
'revisiondelete' => '版の削除と復元',
'revdelete-nooldid-title' => '無効な対象版',
'revdelete-nooldid-text' => 'この操作の対象となる版を指定していないか、指定した版が存在しないか、または最新版を非表示にしようとしています。',
'revdelete-nologtype-title' => '記録の種類を指定していません',
'revdelete-nologtype-text' => 'この操作を実行する記録の種類を指定していません。',
'revdelete-nologid-title' => '無効な記録項目',
'revdelete-nologid-text' => 'この操作の対象となる記録項目を指定していないか、または指定した項目が存在しません。',
'revdelete-no-file' => '指定されたファイルは存在しません。',
'revdelete-show-file-confirm' => 'ファイル「<nowiki>$1</nowiki>」の削除された$2$3の版を本当に閲覧しますか?',
'revdelete-show-file-submit' => 'はい',
'revdelete-selected' => "'''[[:$1]] の{{PLURAL:$2|選択された版}}:'''",
'logdelete-selected' => "'''{{PLURAL:$1|選択された記録項目}}:'''",
'revdelete-text' => "'''削除された版や記録項目は引き続きページの履歴や記録に表示されますが、一般利用者はその内容の一部を取得できなくなります。'''
追加の制限がかけられない限り、{{SITENAME}}の他の管理者は同じインターフェイスを使って非表示の内容の取得や復元ができます。",
'revdelete-confirm' => 'この操作を行おうとしていること、その結果を理解していること、[[{{MediaWiki:Policy-url}}|方針]]に従っていること、を確認してください。',
'revdelete-suppress-text' => "秘匿は、'''以下の場合に限って'''使用すべきです:
* 名誉毀損のおそれがある記述
* 非公開個人情報
*: ''自宅の住所、電話番号、社会保障番号など''",
'revdelete-legend' => '閲覧レベル制限を設定',
'revdelete-hide-text' => '版の本文を隠す',
'revdelete-hide-image' => 'ファイル内容を隠す',
'revdelete-hide-name' => '操作および対象を隠す',
'revdelete-hide-comment' => '編集の要約を隠す',
'revdelete-hide-user' => '投稿者の利用者名またはIPを隠す',
'revdelete-hide-restricted' => '他の利用者と同様に管理者からもデータを隠す',
'revdelete-radio-same' => '(変更しない)',
'revdelete-radio-set' => 'はい',
'revdelete-radio-unset' => 'いいえ',
'revdelete-suppress' => '他の利用者と同様に管理者からもデータを隠す',
'revdelete-unsuppress' => '復元版に対する制限を除去',
'revdelete-log' => '理由:',
'revdelete-submit' => '選択された{{PLURAL:$1|版}}に適用',
'revdelete-success' => "'''版の閲覧レベルを更新しました。'''",
'revdelete-failure' => "'''版の閲覧レベルを更新できませんでした:'''
$1",
'logdelete-success' => "'''記録の閲覧レベルを変更しました。'''",
'logdelete-failure' => "'''記録の閲覧レベルを設定できませんでした。'''
$1",
'revdel-restore' => '閲覧レベルを変更',
'revdel-restore-deleted' => '削除された版',
'revdel-restore-visible' => '閲覧できる版',
'pagehist' => 'ページの履歴',
'deletedhist' => '削除された履歴',
'revdelete-hide-current' => '$1$2の項目の非表示に失敗しました: これは最新版であるため。
非表示にはできません。',
'revdelete-show-no-access' => '$1$2の項目の表示に失敗しました: この項目には「制限付き」の印が付いています。
アクセス権限がありません。',
'revdelete-modify-no-access' => '$1$2の項目の修正に失敗しました: この項目には「制限付き」の印が付いています。
アクセス権限がありません。',
'revdelete-modify-missing' => '版 ID $1 の項目の変更に失敗しました: データベース内にありません!',
'revdelete-no-change' => "'''警告:''' $1$2の項目には要求された閲覧レベルが既に設定されています。",
'revdelete-concurrent-change' => '$1$2の項目の変更に失敗しました: あなたが変更しようとしている間に、他の誰かが変更したようです。
記録を確認してください。',
'revdelete-only-restricted' => '$1$2の項目の版指定削除に失敗しました: 他の閲覧レベルの選択肢のうちどれかをさらに選択しなければ、管理者から項目を秘匿できません。',
'revdelete-reason-dropdown' => '*よくある削除理由
** 著作権侵害
** 不適切なコメントや個人情報の開示
** 不適切な利用者名
** 名誉毀損のおそれ',
'revdelete-otherreason' => '他の、または追加の理由:',
'revdelete-reasonotherlist' => 'その他の理由',
'revdelete-edit-reasonlist' => '削除理由を編集',
'revdelete-offender' => '指定版の投稿者:',

# Suppression log
'suppressionlog' => '秘匿記録',
'suppressionlogtext' => '以下は管理者から秘匿された内容を含む削除およびブロックの一覧です。
現在操作できる追放とブロックの一覧については[[Special:BlockList|ブロックの一覧]]を参照してください。',

# History merging
'mergehistory' => 'ページの履歴の統合',
'mergehistory-header' => 'このページでは、ある元ページの履歴を新しいページに統合できます。
この変更を行ってもページの履歴の連続性が確実に保たれるようにしてください。',
'mergehistory-box' => '2ページの過去の版を統合する:',
'mergehistory-from' => '統合元となるページ:',
'mergehistory-into' => '統合先のページ:',
'mergehistory-list' => '統合できる編集履歴',
'mergehistory-merge' => '以下の [[:$1]] の履歴を [[:$2]] に統合できます。
特定の日時以前に作成された版のみを統合するには、ラジオボタンで版を選択してください。
案内リンクを使用すると選択が初期化されるためご注意ください。',
'mergehistory-go' => '統合できる版を表示',
'mergehistory-submit' => '版を統合',
'mergehistory-empty' => '統合できる版がありません。',
'mergehistory-success' => '[[:$1]]の $3 {{PLURAL:$3|版}}を[[:$2]]に統合しました。',
'mergehistory-fail' => '履歴の統合を実行できません。ページと時刻の引数を再確認してください。',
'mergehistory-no-source' => '統合元ページ $1 が存在しません。',
'mergehistory-no-destination' => '統合先ページ $1 が存在しません。',
'mergehistory-invalid-source' => '統合元のページは有効な名前でなければなりません。',
'mergehistory-invalid-destination' => '統合先のページは有効な名前でなければなりません。',
'mergehistory-autocomment' => '[[:$1]]を[[:$2]]に統合',
'mergehistory-comment' => '[[:$1]]を[[:$2]]に統合: $3',
'mergehistory-same-destination' => '統合元と統合先のページを同じにはできません',
'mergehistory-reason' => '理由:',

# Merge log
'mergelog' => '統合記録',
'pagemerge-logentry' => '[[$1]]を[[$2]]に統合 ($3 版まで)',
'revertmerge' => '統合解除',
'mergelogpagetext' => '以下は、最近行われたあるページから別のページへの統合の一覧です。',

# Diffs
'history-title' => '「$1」の変更履歴',
'difference-title' => '「$1」の版間の差分',
'difference-title-multipage' => 'ページ「$1」と「$2」の間の差分',
'difference-multipage' => '(ページ間の差分)',
'lineno' => '$1行:',
'compareselectedversions' => '選択した版同士を比較',
'showhideselectedversions' => '選択した版を表示/非表示',
'editundo' => '取り消し',
'diff-empty' => '(相違点なし)',
'diff-multi' => '({{PLURAL:$2|$2人の利用者}}による、{{PLURAL:$1|間の$1版}}が非表示)',
'diff-multi-manyusers' => '({{PLURAL:$2|$2人を超える利用者}}による、{{PLURAL:$1|間の$1版}}が非表示)',
'difference-missing-revision' => '指定された{{PLURAL:$2|$2版}}の差分 ($1) が見つかりませんでした。

通常、削除されたページの版への古い差分表示や固定リンクをたどった際に、このようなことが起きます。 
詳細は[{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} 削除記録]を参照してください。',

# Search results
'searchresults' => '検索結果',
'searchresults-title' => '「$1」の検索結果',
'searchresulttext' => '{{SITENAME}}の検索に関する詳しい情報は、[[{{MediaWiki:Helppage}}|{{int:help}}]]をご覧ください。',
'searchsubtitle' => "'''[[:$1]]'''の検索 ([[Special:Prefixindex/$1|「$1」から始まるページ]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|「$1」へリンクしている全ページ]])",
'searchsubtitleinvalid' => "'''$1'''を検索しました",
'toomanymatches' => '一致したページが多すぎます。他の検索語を指定してください。',
'titlematches' => 'ページ名と一致',
'notitlematches' => 'ページ名とは一致しませんでした',
'textmatches' => 'ページ本文と一致',
'notextmatches' => 'どのページ本文とも一致しませんでした',
'prevn' => '前の$1件',
'nextn' => '次の$1件',
'prevn-title' => '前の{{PLURAL:$1|$1件}}',
'nextn-title' => '次の{{PLURAL:$1|$1件}}',
'shown-title' => 'ページあたり{{PLURAL:$1|$1件の結果}}を表示',
'viewprevnext' => '($1{{int:pipe-separator}}$2) ($3 件) を表示',
'searchmenu-legend' => '検索オプション',
'searchmenu-exists' => "'''このウィキには「[[:$1]]」という名前のページがあります'''",
'searchmenu-new' => "'''このウィキでページ「[[:$1]]」を新規作成する'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|この文字列から始まる名前のページを見る]]',
'searchprofile-articles' => '本文ページ',
'searchprofile-project' => 'ヘルプとプロジェクトページ',
'searchprofile-images' => 'マルチメディア',
'searchprofile-everything' => 'すべて',
'searchprofile-advanced' => '詳細',
'searchprofile-articles-tooltip' => '$1内を検索',
'searchprofile-project-tooltip' => '$1内を検索',
'searchprofile-images-tooltip' => 'ファイルを検索',
'searchprofile-everything-tooltip' => '全本文ページ (トークページを含む) 内を検索',
'searchprofile-advanced-tooltip' => '特定の名前空間内を検索',
'search-result-size' => '$1 ({{PLURAL:$2|$2 単語}})',
'search-result-category-size' => '{{PLURAL:$1|$1 件}} ({{PLURAL:$2|$2 下位カテゴリ}}、{{PLURAL:$3|$3 ファイル}})',
'search-result-score' => '関連度: $1%',
'search-redirect' => '($1からのリダイレクト)',
'search-section' => '($1の節)',
'search-suggest' => 'もしかして: $1',
'search-interwiki-caption' => '姉妹プロジェクト',
'search-interwiki-default' => '$1の結果:',
'search-interwiki-more' => '(続き)',
'search-relatedarticle' => '関連',
'mwsuggest-disable' => '検索候補の提示を無効にする',
'searcheverything-enable' => 'すべての名前空間を検索',
'searchrelated' => '関連',
'searchall' => 'すべて',
'showingresults' => "'''$2''' 件目以降の最大 {{PLURAL:$1|'''$1''' 件の結果}}を表示しています。",
'showingresultsnum' => "'''$2''' 件目以降の {{PLURAL:$3|'''$3''' 件の結果}}を表示しています。",
'showingresultsheader' => "「'''$4'''」の検索結果 {{PLURAL:$5|'''$3''' 件中の '''$1''' 件目|'''$3''' 件中の '''$1''' 件目から '''$2''' 件目}}",
'nonefound' => "'''注意:''' 既定では一部の名前空間のみを検索します。
''all:''を前に付けると、すべて (トークページやテンプレートなどを含む) を対象にできます。検索する名前空間を前に付けることもできます。",
'search-nonefound' => '問い合わせに合致する検索結果はありませんでした。',
'powersearch' => '高度な検索',
'powersearch-legend' => '高度な検索',
'powersearch-ns' => '名前空間を指定して検索:',
'powersearch-redir' => '転送ページを含める',
'powersearch-field' => '検索対象',
'powersearch-togglelabel' => 'チェックを入れる:',
'powersearch-toggleall' => 'すべて',
'powersearch-togglenone' => 'すべて外す',
'search-external' => '外部検索',
'searchdisabled' => '{{SITENAME}}の検索機能は無効化されています。
さしあたってはGoogleなどで検索できます。
ただし外部の検索エンジンの索引にある{{SITENAME}}のコンテンツは古い場合があります。',
'search-error' => '検索する際にエラーが発生しました: $1',

# Preferences page
'preferences' => '個人設定',
'mypreferences' => '個人設定',
'prefs-edits' => '編集回数:',
'prefsnologin' => 'ログインしていません',
'prefsnologintext' => '個人設定を変更するためには<span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} ログイン]</span>する必要があります。',
'changepassword' => 'パスワードの変更',
'prefs-skin' => '外装',
'skin-preview' => 'プレビュー',
'datedefault' => '選択なし',
'prefs-beta' => 'ベータ版機能',
'prefs-datetime' => '日付と時刻',
'prefs-labs' => 'ラボの機能',
'prefs-user-pages' => '利用者ページ',
'prefs-personal' => '利用者情報',
'prefs-rc' => '最近の更新',
'prefs-watchlist' => 'ウォッチリスト',
'prefs-watchlist-days' => 'ウォッチリストの表示日数:',
'prefs-watchlist-days-max' => '最大 $1 {{PLURAL:$1|日間}}',
'prefs-watchlist-edits' => '拡張ウォッチリストの表示件数:',
'prefs-watchlist-edits-max' => '最大数: 1000',
'prefs-watchlist-token' => 'ウォッチリストのトークン:',
'prefs-misc' => 'その他',
'prefs-resetpass' => 'パスワードを変更',
'prefs-changeemail' => 'メールアドレスを変更',
'prefs-setemail' => 'メールアドレスを設定',
'prefs-email' => 'メールの設定',
'prefs-rendering' => '表示',
'saveprefs' => '保存',
'resetprefs' => '保存していない変更を破棄',
'restoreprefs' => 'すべて初期設定に戻す (すべての節について)',
'prefs-editing' => '編集',
'rows' => '行数:',
'columns' => '列数:',
'searchresultshead' => '検索',
'resultsperpage' => '1 ページあたりの表示件数:',
'stub-threshold' => '<a href="#" class="stub">スタブリンク</a>として表示する閾値 (バイト):',
'stub-threshold-disabled' => '無効',
'recentchangesdays' => '最近の更新に表示する日数:',
'recentchangesdays-max' => '(最大 $1 {{PLURAL:$1|日|日間}})',
'recentchangescount' => '既定で表示する件数:',
'prefs-help-recentchangescount' => 'この設定は最近の更新、ページの履歴、および記録に適用されます。',
'prefs-help-watchlist-token2' => 'これはあなたのウォッチリスト フィードの秘密のコードです。
このトークンを知っている人は誰でもあなたのウォッチリストを読めてしまうため、他の人に教えないでください。
[[Special:ResetTokens|トークンを再設定する必要がある場合はここをクリックしてください]]。',
'savedprefs' => '個人設定を保存しました。',
'timezonelegend' => 'タイムゾーン:',
'localtime' => 'ローカルの時刻:',
'timezoneuseserverdefault' => 'ウィキの既定を使用 ($1)',
'timezoneuseoffset' => 'その他 (時差を指定)',
'timezoneoffset' => '時差¹:',
'servertime' => 'サーバーの時刻:',
'guesstimezone' => 'ブラウザーの設定から入力',
'timezoneregion-africa' => 'アフリカ',
'timezoneregion-america' => 'アメリカ',
'timezoneregion-antarctica' => '南極',
'timezoneregion-arctic' => '北極',
'timezoneregion-asia' => 'アジア',
'timezoneregion-atlantic' => '大西洋',
'timezoneregion-australia' => 'オーストラリア',
'timezoneregion-europe' => 'ヨーロッパ',
'timezoneregion-indian' => 'インド洋',
'timezoneregion-pacific' => '太平洋',
'allowemail' => '他の利用者からのメールを受け取る',
'prefs-searchoptions' => '検索',
'prefs-namespaces' => '名前空間',
'defaultns' => '指定した名前空間のみを検索:',
'default' => '既定',
'prefs-files' => 'ファイル',
'prefs-custom-css' => 'カスタムCSS',
'prefs-custom-js' => 'カスタムJS',
'prefs-common-css-js' => 'すべての外装に共通のCSSとJavaScript:',
'prefs-reset-intro' => 'このページを使用すると、自分の個人設定をこのサイトの初期設定に戻せます。
この操作は取り消せません。',
'prefs-emailconfirm-label' => 'メールアドレスの確認:',
'youremail' => 'メールアドレス:',
'username' => '{{GENDER:$1|利用者名}}:',
'uid' => '{{GENDER:$1|利用者}} ID:',
'prefs-memberingroups' => '{{GENDER:$2|所属}}{{PLURAL:$1|グループ}}:',
'prefs-memberingroups-type' => '$1',
'prefs-registration' => '登録日時:',
'prefs-registration-date-time' => '$1',
'yourrealname' => '本名:',
'yourlanguage' => '使用言語:',
'yourvariant' => 'コンテンツ言語変種:',
'prefs-help-variant' => 'このウィキのコンテンツに表示に使用したい言語変種または正書法。',
'yournick' => '新しい署名:',
'prefs-help-signature' => 'トークページ上での発言には「<nowiki>~~~~</nowiki>」と付けて署名すべきです。これは自分の署名に時刻印を付けたものに変換されます。',
'badsig' => '署名用のソースが正しくありません。
HTMLタグを見直してください。',
'badsiglength' => '署名が長すぎます。
$1 {{PLURAL:$1|文字}}以下である必要があります。',
'yourgender' => '表示に使用する性別',
'gender-unknown' => '未指定',
'gender-male' => '男',
'gender-female' => '女',
'prefs-help-gender' => 'この項目の設定は省略できます。
ソフトウェアが利用者向けの画面表示であなたに言及する際に、適切な文法的性を選択するために使用されます。
この情報は公開されます。',
'email' => 'メール',
'prefs-help-realname' => '本名は省略できます。
入力すると、あなたの著作物の帰属表示に使われます。',
'prefs-help-email' => 'メールアドレスは省略できますが、パスワードを忘れた際にパスワードをリセットするのに必要です。',
'prefs-help-email-others' => '利用者ページやトークページ上のリンクを通じて、他の利用者があなたにメールで連絡を取れるようにすることもできます。
他の利用者が連絡を取る際にあなたのメールアドレスが開示されることはありません。',
'prefs-help-email-required' => 'メールアドレスが必要です。',
'prefs-info' => '基本情報',
'prefs-i18n' => '国際化',
'prefs-signature' => '署名',
'prefs-dateformat' => '日付と時刻の形式',
'prefs-timeoffset' => '時差',
'prefs-advancedediting' => '全般オプション',
'prefs-editor' => 'エディター',
'prefs-preview' => 'プレビュー',
'prefs-advancedrc' => '詳細設定',
'prefs-advancedrendering' => '詳細設定',
'prefs-advancedsearchoptions' => '詳細設定',
'prefs-advancedwatchlist' => '詳細設定',
'prefs-displayrc' => '表示の設定',
'prefs-displaysearchoptions' => '表示の設定',
'prefs-displaywatchlist' => '表示の設定',
'prefs-tokenwatchlist' => 'トークン',
'prefs-diffs' => '差分',
'prefs-help-prefershttps' => 'この設定は、次回ログインの際に反映されます。',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'メールアドレスは有効のようです',
'email-address-validity-invalid' => '有効なメールアドレスを入力してください',

# User rights
'userrights' => '利用者権限を管理',
'userrights-lookup-user' => '利用者グループを管理',
'userrights-user-editname' => '利用者名を入力:',
'editusergroup' => '利用者グループを編集',
'editinguser' => "利用者''' [[User:$1|$1]]''' $2 の権限を変更",
'userrights-editusergroup' => '利用者グループを編集',
'saveusergroups' => '利用者グループを保存',
'userrights-groupsmember' => '所属グループ:',
'userrights-groupsmember-auto' => '自動的に付与される権限:',
'userrights-groupsmember-type' => '$1',
'userrights-groups-help' => 'この利用者が属するグループを変更できます。
* チェックが入っているボックスは、この利用者がそのグループに属していることを意味します。
* チェックが入っていないボックスは、この利用者がそのグループに属していないことを意味します。
* 「*」はグループに一旦追加した場合に除去 (あるいはその逆) ができないことを示しています。',
'userrights-reason' => '理由:',
'userrights-no-interwiki' => '他ウィキ上における利用者権限の編集権限はありません。',
'userrights-nodatabase' => 'データベース$1は存在しないか、ローカル上にありません。',
'userrights-nologin' => '利用者権限を付与するには、管理者アカウントで[[Special:UserLogin|ログイン]]する必要があります。',
'userrights-notallowed' => 'あなたには利用者権限を追加/除去する権限がありません。',
'userrights-changeable-col' => '変更できるグループ',
'userrights-unchangeable-col' => '変更できないグループ',
'userrights-irreversible-marker' => '$1*',
'userrights-conflict' => '利用者権限の変更が競合しています! 変更内容を確認してください。',
'userrights-removed-self' => 'あなた自身の権限を除去しました。そのため、このページにはもうアクセスできません。',

# Groups
'group' => 'グループ:',
'group-user' => '登録利用者',
'group-autoconfirmed' => '自動承認された利用者',
'group-bot' => 'ボット',
'group-sysop' => '管理者',
'group-bureaucrat' => 'ビューロクラット',
'group-suppress' => '秘匿者',
'group-all' => '(全員)',

'group-user-member' => '{{GENDER:$1|登録利用者}}',
'group-autoconfirmed-member' => '{{GENDER:$1|自動承認された利用者}}',
'group-bot-member' => '{{GENDER:$1|ボット}}',
'group-sysop-member' => '{{GENDER:$1|管理者}}',
'group-bureaucrat-member' => '{{GENDER:$1|ビューロクラット}}',
'group-suppress-member' => '{{GENDER:$1|秘匿者}}',

'grouppage-user' => '{{ns:project}}:登録利用者',
'grouppage-autoconfirmed' => '{{ns:project}}:自動承認された利用者',
'grouppage-bot' => '{{ns:project}}:ボット',
'grouppage-sysop' => '{{ns:project}}:管理者',
'grouppage-bureaucrat' => '{{ns:project}}:ビューロクラット',
'grouppage-suppress' => '{{ns:project}}:秘匿者',

# Rights
'right-read' => 'ページを閲覧',
'right-edit' => 'ページを編集',
'right-createpage' => 'ページ (議論ページ以外) を作成',
'right-createtalk' => '議論ページを作成',
'right-createaccount' => '新しい利用者アカウントを作成',
'right-minoredit' => '細部の編集の印を付ける',
'right-move' => 'ページを移動',
'right-move-subpages' => '下位ページを含めてページを移動',
'right-move-rootuserpages' => '利用者ページ本体を移動',
'right-movefile' => 'ファイルを移動',
'right-suppressredirect' => '転送ページを作成せずにページを移動',
'right-upload' => 'ファイルをアップロード',
'right-reupload' => '既存のファイルに上書き',
'right-reupload-own' => '自身がアップロードした既存のファイルに上書き',
'right-reupload-shared' => '共有メディアリポジトリ上のファイルにローカルで上書き',
'right-upload_by_url' => 'URL からファイルをアップロード',
'right-purge' => '確認なしでサイトのキャッシュを破棄',
'right-autoconfirmed' => 'IPベースの速度制限を受けない',
'right-bot' => '自動処理と認識させる',
'right-nominornewtalk' => '議論ページの細部の編集をした際に、新着メッセージとして通知しない',
'right-apihighlimits' => 'API要求でより高い制限値を使用',
'right-writeapi' => '書き込みAPIを使用',
'right-delete' => 'ページを削除',
'right-bigdelete' => '大きな履歴があるページを削除',
'right-deletelogentry' => '特定の記録項目を削除/復元',
'right-deleterevision' => 'ページの特定の版を削除/復元',
'right-deletedhistory' => '削除された履歴項目 (関連する本文を除く) を閲覧',
'right-deletedtext' => '削除された本文と削除された版間の差分を閲覧',
'right-browsearchive' => '削除されたページを検索',
'right-undelete' => 'ページを復元',
'right-suppressrevision' => '管理者から隠された版を確認/復元',
'right-suppressionlog' => '非公開記録を閲覧',
'right-block' => '他の利用者の編集をブロック',
'right-blockemail' => '利用者のメール送信をブロック',
'right-hideuser' => '利用者名をブロックして公開記録から隠す',
'right-ipblock-exempt' => 'IPブロック、自動ブロック、広域ブロックを回避',
'right-proxyunbannable' => 'プロキシの自動ブロックを回避',
'right-unblockself' => '自身に対するブロックを解除',
'right-protect' => '保護レベルを変更し、カスケード保護されたページを編集',
'right-editprotected' => '「{{int:protect-level-sysop}}」の保護を設定されたページを編集',
'right-editsemiprotected' => '「{{int:protect-level-autoconfirmed}}」の保護を設定されたページを編集',
'right-editinterface' => 'ユーザーインターフェースを編集',
'right-editusercssjs' => '他の利用者のCSSファイル/JavaScriptファイルを編集',
'right-editusercss' => '他の利用者のCSSファイルを編集',
'right-edituserjs' => '他の利用者のJavaScriptファイルを編集',
'right-editmyusercss' => '自身のCSSファイルを編集',
'right-editmyuserjs' => '自身のJavaScriptファイルを編集',
'right-viewmywatchlist' => 'ウォッチリストを閲覧',
'right-editmywatchlist' => '自身のウォッチリストを編集 (注: この権限がなくてもページを追加できる権限が他にもあります)',
'right-viewmyprivateinfo' => '自身の非公開データ (例: メールアドレス、本名) を閲覧',
'right-editmyprivateinfo' => '自身の非公開データ (例: メールアドレス、本名) を編集',
'right-editmyoptions' => '自身の個人設定を編集',
'right-rollback' => '特定ページを最後に編集した利用者の編集を即時巻き戻し',
'right-markbotedits' => '巻き戻しをボットの編集として扱う',
'right-noratelimit' => '速度制限を受けない',
'right-import' => '他のウィキからページを取り込み',
'right-importupload' => 'ファイルアップロードでページを取り込み',
'right-patrol' => '他の利用者の編集を巡回済みにする',
'right-autopatrol' => '自身の編集を自動で巡回済みにする',
'right-patrolmarks' => '最近の更新で巡回済み印を閲覧',
'right-unwatchedpages' => 'ウォッチされていないページ一覧を閲覧',
'right-mergehistory' => 'ページの履歴を統合',
'right-userrights' => '全利用者権限を編集',
'right-userrights-interwiki' => '他のウィキの利用者の利用者権限を編集',
'right-siteadmin' => 'データベースをロックおよびロック解除',
'right-override-export-depth' => 'リンク先ページを5階層まで含めて書き出す',
'right-sendemail' => '他の利用者にメールを送信',
'right-passwordreset' => 'パスワード再設定メールを閲覧',

# Special:Log/newusers
'newuserlogpage' => 'アカウント作成記録',
'newuserlogpagetext' => '以下はアカウント作成の記録です。',

# User rights log
'rightslog' => '利用者権限変更記録',
'rightslogtext' => '以下は利用者権限の変更記録です。',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'このページの閲覧',
'action-edit' => 'このページの編集',
'action-createpage' => 'ページの作成',
'action-createtalk' => '議論ページの作成',
'action-createaccount' => 'この利用者アカウントの作成',
'action-minoredit' => '細部の編集の印を付ける',
'action-move' => 'このページの移動',
'action-move-subpages' => 'このページとその下位ページの移動',
'action-move-rootuserpages' => '利用者ページ本体の移動',
'action-movefile' => 'このファイルの移動',
'action-upload' => 'このファイルのアップロード',
'action-reupload' => 'この既存のファイルへの上書き',
'action-reupload-shared' => '共有リポジトリにあるこのファイルへの上書き',
'action-upload_by_url' => 'URL からのこのファイルのアップロード',
'action-writeapi' => '書き込みAPIの使用',
'action-delete' => 'このページの削除',
'action-deleterevision' => 'この版の削除',
'action-deletedhistory' => 'このページの削除履歴の閲覧',
'action-browsearchive' => '削除されたページの検索',
'action-undelete' => 'このページの復元',
'action-suppressrevision' => '隠された版の確認と復元',
'action-suppressionlog' => 'この非公開記録の閲覧',
'action-block' => 'この利用者の編集ブロック',
'action-protect' => 'このページの保護レベルの変更',
'action-rollback' => '特定ページを最後に編集した利用者の編集の即時巻き戻し',
'action-import' => '他のウィキからのページの取り込み',
'action-importupload' => 'ファイルアップロードでのページへの取り込み',
'action-patrol' => '他の利用者の編集を巡回済みにする',
'action-autopatrol' => '自分の編集を巡回済みにする',
'action-unwatchedpages' => 'ウォッチされていないページ一覧の閲覧',
'action-mergehistory' => 'このページの履歴の統合',
'action-userrights' => '全利用者権限の編集',
'action-userrights-interwiki' => '他のウィキの利用者の利用者権限変更',
'action-siteadmin' => 'データベースのロックまたはロック解除',
'action-sendemail' => 'メールの送信',
'action-editmywatchlist' => '自身のウォッチリストの編集',
'action-viewmywatchlist' => '自身のウォッチリストの閲覧',
'action-viewmyprivateinfo' => '自分の非公開情報の閲覧',
'action-editmyprivateinfo' => '自分の非公開情報の編集',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|回の変更}}',
'enhancedrc-since-last-visit' => '最終閲覧以降 $1 {{PLURAL:$1|件}}',
'enhancedrc-history' => '履歴',
'recentchanges' => '最近の更新',
'recentchanges-legend' => '最近の更新のオプション',
'recentchanges-summary' => 'このページでは、このウィキでの最近の更新を確認できます。',
'recentchanges-noresult' => '指定した条件に該当する期間の変更はありません。',
'recentchanges-feed-description' => 'このフィードでこのウィキの最近の更新を追跡できます。',
'recentchanges-label-newpage' => 'この編集で新しいページが作成されました',
'recentchanges-label-minor' => 'これは細部の編集です',
'recentchanges-label-bot' => 'この編集はボットによって行われました',
'recentchanges-label-unpatrolled' => 'この編集はまだ巡回されていません',
'rcnote' => "$4 $5 までの{{PLURAL:$2|'''$2'''日間}}になされた{{PLURAL:$1|'''$1'''件の変更}}は以下の通りです。",
'rcnotefrom' => "以下は'''$2'''以降の更新です (最大 '''$1''' 件)。",
'rclistfrom' => '$1以降の更新を表示する',
'rcshowhideminor' => '細部の編集を$1',
'rcshowhidebots' => 'ボットを$1',
'rcshowhideliu' => 'ログイン利用者を$1',
'rcshowhideanons' => '匿名利用者を$1',
'rcshowhidepatr' => '巡回された編集を$1',
'rcshowhidemine' => '自分の編集を$1',
'rclinks' => '最近 $2 日間の更新を最大 $1 件表示<br />$3',
'diff' => '差分',
'hist' => '履歴',
'hide' => '非表示',
'show' => '表示',
'minoreditletter' => '細',
'newpageletter' => '新',
'boteditletter' => 'ボ',
'unpatrolledletter' => '!',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|$1 人の利用者}}がウォッチしています]',
'rc_categories' => 'カテゴリを限定 (「|」で区切る)',
'rc_categories_any' => 'すべて',
'rc-change-size' => '$1',
'rc-change-size-new' => '変更後は $1 {{PLURAL:$1|バイト}}',
'newsectionsummary' => '/* $1 */ 新しい節',
'rc-enhanced-expand' => '詳細を表示',
'rc-enhanced-hide' => '詳細を非表示',
'rc-old-title' => '作成時のページ名は「$1」',

# Recent changes linked
'recentchangeslinked' => '関連ページの更新状況',
'recentchangeslinked-feed' => '関連ページの更新状況',
'recentchangeslinked-toolbox' => '関連ページの更新状況',
'recentchangeslinked-title' => '「$1」と関連する変更',
'recentchangeslinked-summary' => "これは指定したページからリンクされている (または指定したカテゴリに含まれている) ページの最近の変更の一覧です。
[[Special:Watchlist|自分のウォッチリスト]]にあるページは'''太字'''で表示されます。",
'recentchangeslinked-page' => 'ページ名:',
'recentchangeslinked-to' => 'このページへのリンク元での変更の表示に切り替え',

# Upload
'upload' => 'ファイルをアップロード',
'uploadbtn' => 'ファイルをアップロード',
'reuploaddesc' => 'アップロードを中止してアップロードフォームへ戻る',
'upload-tryagain' => '修正したファイル解説を投稿',
'uploadnologin' => 'ログインしていません',
'uploadnologintext' => 'ファイルをアップロードするには$1する必要があります。',
'upload_directory_missing' => 'アップロード先ディレクトリ ($1) が見つかりませんでした。ウェブ サーバーによる作成もできませんでした。',
'upload_directory_read_only' => 'アップロード先ディレクトリ ($1) には、ウェブサーバーが書き込めません。',
'uploaderror' => 'アップロードのエラー',
'upload-recreate-warning' => "'''警告: その名前のファイルは、以前に削除または移動されています。'''

参考のため、このページの削除と移動の記録を以下に示します:",
'uploadtext' => "ファイルをアップロードするには、以下のフォームを使用してください。

以前にアップロードされたファイルの表示と検索には[[Special:FileList|{{int:listfiles}}]]を使用してください。(再) アップロードは[[Special:Log/upload|アップロード記録]]に、削除は[[Special:Log/delete|削除記録]]にも記録されます。

ページにファイルを入れるには、以下の書式のリンクを使用してください:
* '''<code><nowiki>[[</nowiki>{{ns:file}}:<nowiki>File.jpg]]</nowiki></code>''' とすると、ファイルが完全なままで使用されます。
* '''<code><nowiki>[[</nowiki>{{ns:file}}:<nowiki>File.png|200px|thumb|left|代替文]]</nowiki></code>''' とすると、200ピクセルの幅に修正された状態で、左寄せの枠内に、「代替文」が説明として使用されます。
* '''<code><nowiki>[[</nowiki>{{ns:media}}:<nowiki>File.ogg]]</nowiki></code>''' とすると、ファイルを表示せずにそのファイルに直接リンクします。",
'upload-permitted' => '許可されているファイル形式: $1。',
'upload-preferred' => '推奨されているファイル形式: $1。',
'upload-prohibited' => '禁止されているファイル形式: $1。',
'uploadlog' => 'アップロード記録',
'uploadlogpage' => 'アップロード記録',
'uploadlogpagetext' => '以下はファイルアップロードの最近の記録です。
画像付きで見るには[[Special:NewFiles|新規ファイルの一覧]]をご覧ください。',
'filename' => 'ファイル名',
'filedesc' => '概要',
'fileuploadsummary' => '概要:',
'filereuploadsummary' => 'ファイルの変更:',
'filestatus' => '著作権情報:',
'filesource' => '出典:',
'uploadedfiles' => 'アップロードされたファイル',
'ignorewarning' => '警告を無視してファイルを保存',
'ignorewarnings' => '警告を無視',
'minlength1' => 'ファイル名には少なくとも1文字必要です。',
'illegalfilename' => 'ファイル名「$1」にページ名として許可されていない文字が含まれています。
ファイル名を変更してからもう一度アップロードしてください。',
'filename-toolong' => '240バイトを超えるファイル名は禁止されています。',
'badfilename' => 'ファイル名は「$1」へ変更されました。',
'filetype-mime-mismatch' => 'ファイルの拡張子「$1」がMIMEタイプ「$2」と一致しません。',
'filetype-badmime' => 'MIMEタイプ「$1」のファイルのアップロードは許可されていません。',
'filetype-bad-ie-mime' => '許可されていない潜在的危険性のあるファイル形式「$1」としてInternet Explorerに認識されてしまうため、このファイルをアップロードできません。',
'filetype-unwanted-type' => "'''「.$1」'''は好ましくないファイル形式です。
推奨される{{PLURAL:$3|ファイル形式}}は $2 です。",
'filetype-banned-type' => "'''「.$1」''' は許可されていないファイル形式です{{PLURAL:$4|}}。
許可されているファイル形式{{PLURAL:$3|}}は$2です。",
'filetype-missing' => 'ファイル名に「.jpg」のような拡張子がありません。',
'empty-file' => '送信されたファイルは空でした。',
'file-too-large' => '送信されたファイルは大きすぎます。',
'filename-tooshort' => 'ファイル名が短すぎます。',
'filetype-banned' => 'この形式のファイルは禁止されています。',
'verification-error' => 'このファイルは、ファイルの検証システムに合格しませんでした。',
'hookaborted' => '拡張機能のフックによって、修正が中断されました。',
'illegal-filename' => 'そのファイル名は許可されていません。',
'overwrite' => '既存のファイルへの上書きは許可されていません。',
'unknown-error' => '不明なエラーが発生しました。',
'tmp-create-error' => '一時ファイルを作成できませんでした。',
'tmp-write-error' => '一時ファイルへの書き込みエラーです。',
'large-file' => 'ファイルサイズを $1 バイト以下にすることを推奨します。
このファイルは $2 バイトです。',
'largefileserver' => 'このファイルは、サーバー設定で許されている最大サイズより大きいです。',
'emptyfile' => 'アップロードしたファイルは内容が空のようです。
ファイル名の指定が間違っている可能性があります。
本当にこのファイルをアップロードしたいのか、確認してください。',
'windows-nonascii-filename' => 'このウィキではファイル名に特殊文字を使用できません。',
'fileexists' => 'この名前のファイルは既に存在します。置き換えていいかどうか確信が持てない場合は、<strong>[[:$1]]</strong>を確認してください。
[[$1|thumb]]',
'filepageexists' => 'このファイルのための説明ページは既に<strong>[[:$1]]</strong>に作成されていますが、現在、ファイルが存在しません。
入力した概要は説明ページに反映されません。
新しい概要を表示させるには、説明ページを手動で編集する必要があります。
[[$1|thumb]]',
'fileexists-extension' => '類似した名前のファイルが既に存在します: [[$2|thumb]]
* アップロード中のファイルの名前: <strong>[[:$1]]</strong>
* 既存ファイルの名前: <strong>[[:$2]]</strong>
違う名前を選択してください。',
'fileexists-thumbnail-yes' => "このファイルは元の画像から縮小されたもの ''(サムネイル)'' のようです。
[[$1|thumb]]
ファイル <strong>[[:$1]]</strong> を確認してください。
確認したファイルが同じ画像の元のサイズの版の場合は、サムネイルを別途アップロードする必要はありません。",
'file-thumbnail-no' => "ファイル名が <strong>$1</strong> で始まっています。
他の画像から縮小されたもの ''(サムネイル)'' のようです。
より高精細な画像をお持ちの場合はそれをアップロードしてください。お持ちではない場合はファイル名を変更してください。",
'fileexists-forbidden' => 'この名前のファイルは既に存在しており、上書きできません。
アップロードを継続したい場合は、前のページに戻り、別のファイル名を使用してください。
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'この名前のファイルは共有ファイルリポジトリに既に存在しています。
アップロードを継続したい場合は、前のページに戻り、別のファイル名を使用してください。
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'このファイルは以下の{{PLURAL:$1|ファイル|ファイル群}}と重複しています:',
'file-deleted-duplicate' => 'このファイル ([[:$1]]) と同一のファイルが以前に削除されています。
再度アップロードをする前に、以前削除されたファイルの削除記録を確認してください。',
'uploadwarning' => 'アップロード警告',
'uploadwarning-text' => '下記のファイル解説を修正して再試行してください。',
'savefile' => 'ファイルを保存',
'uploadedimage' => '「[[$1]]」をアップロードしました',
'overwroteimage' => '「[[$1]]」の新しい版をアップロードしました',
'uploaddisabled' => 'アップロード機能は無効になっています。',
'copyuploaddisabled' => 'URL からのアップロードは無効になっています。',
'uploadfromurl-queued' => 'アップロードがキューに追加されました。',
'uploaddisabledtext' => 'ファイルのアップロードは、無効になっています。',
'php-uploaddisabledtext' => 'ファイルのアップロードがPHPで無効化されています。
file_uploadsの設定を確認してください。',
'uploadscripted' => 'このファイルは、ウェブブラウザーが誤って解釈してしまうおそれがあるHTMLまたはスクリプトコードを含んでいます。',
'uploadvirus' => 'このファイルはウイルスを含んでいます!
詳細: $1',
'uploadjava' => 'このファイルは、Javaの.classファイルを含むZIPファイルです。
セキュリティ上の制限を回避されるおそれがあるため、Javaファイルのアップロードは許可されていません。',
'upload-source' => 'アップロード元ファイル',
'sourcefilename' => 'アップロード元のファイル名:',
'sourceurl' => 'アップロード元の URL:',
'destfilename' => '登録するファイル名:',
'upload-maxfilesize' => 'ファイルの最大サイズ: $1',
'upload-description' => 'ファイルの解説',
'upload-options' => 'アップロードのオプション',
'watchthisupload' => 'このファイルをウォッチ',
'filewasdeleted' => 'この名前のファイルは一度アップロードされ、その後削除されています。
再度アップロードする前に$1を確認してください。',
'filename-bad-prefix' => "アップロードしようとしているファイルの名前が'''「$1」'''から始まっていますが、これはデジタルカメラによって自動的に付与されるような具体性を欠いた名前です。
ファイルの内容をより具体的に説明する名前を使用してください。",
'filename-prefix-blacklist' => ' #<!-- この行はそのままにしておいてください --> <pre>
# 構文は以下の通り:
#   * "#" 記号から行末まではすべてがコメント
#   * 空ではない行はすべてデジタルカメラによって自動的に付けられる典型的なファイル名の接頭辞
CIMG # カシオ
DSC_ # ニコン
DSCF # 富士フイルム
DSCN # ニコン
DUW # 一部の携帯電話
IMG # 一般
JD # Jenoptik
MGP # ペンタックス
PICT # その他
 #</pre> <!-- この行はそのままにしておいてください -->',
'upload-success-subj' => 'アップロード成功',
'upload-success-msg' => '[$2] からアップロードしました。こちらで利用できます: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'アップロード失敗',
'upload-failure-msg' => '[$2] からアップロードする際に問題が発生しました:

$1',
'upload-warning-subj' => 'アップロードの警告',
'upload-warning-msg' => '[$2] からアップロードしようとしたデータに問題があります。 [[Special:Upload/stash/$1|アップロードのフォーム]]に戻って問題を修正してください。',

'upload-proto-error' => '無効なプロトコル',
'upload-proto-error-text' => '遠隔アップロード機能では、URLが<code>http://</code>または<code>ftp://</code>で始まっている必要があります。',
'upload-file-error' => '内部エラー',
'upload-file-error-text' => '内部エラーのため、サーバー上の一時ファイル作成に失敗しました。
[[Special:ListUsers/sysop|管理者]]に連絡してください。',
'upload-misc-error' => '不明なアップロードエラー',
'upload-misc-error-text' => 'アップロード中に不明なエラーが発生しました。
指定した URL が正しいこととアクセスできることを確認して、もう一度試してください。
それでもこのエラーが発生する場合は、[[Special:ListUsers/sysop|管理者]]に連絡してください。',
'upload-too-many-redirects' => 'そのURLに含まれるリダイレクトが多すぎます',
'upload-unknown-size' => 'サイズ不明',
'upload-http-error' => 'HTTP エラー発生: $1',
'upload-copy-upload-invalid-domain' => 'このドメインからのアップロードは許可されていません。',

# File backend
'backend-fail-stream' => 'ファイル $1 をストリームできませんでした。',
'backend-fail-backup' => 'ファイル $1 をバックアップできませんでした。',
'backend-fail-notexists' => 'ファイル $1 は存在しません。',
'backend-fail-hashes' => 'ファイルの比較用のハッシュを取得できませんでした。',
'backend-fail-notsame' => '異なる内容のファイル「$1」が既に存在します。',
'backend-fail-invalidpath' => '「$1」は有効なストレージパスではありません。',
'backend-fail-delete' => 'ファイル「$1」を削除できませんでした。',
'backend-fail-describe' => 'ファイル「$1」のメタデータを変更できませんでした。',
'backend-fail-alreadyexists' => 'ファイル「$1」は既に存在します。',
'backend-fail-store' => 'ファイル「$1」を「$2」に格納できませんでした。',
'backend-fail-copy' => 'ファイル「$1」を「$2」に複製できませんでした。',
'backend-fail-move' => 'ファイル「$1」を「$2」に移動できませんでした。',
'backend-fail-opentemp' => '一時ファイルを開けませんでした。',
'backend-fail-writetemp' => '一時ファイルに書き込めませんでした。',
'backend-fail-closetemp' => '一時ファイルを閉じることができませんでした。',
'backend-fail-read' => 'ファイル「$1」から読み取れませんでした。',
'backend-fail-create' => 'ファイル「$1」に書き込めませんでした。',
'backend-fail-maxsize' => 'サイズが {{PLURAL:$2|$2 バイト}}を超えているため、ファイル「$1」に書き込めませんでした。',
'backend-fail-readonly' => "ストレージバックエンド「$1」は現在読み取り専用です。理由:「''$2''」",
'backend-fail-synced' => 'ファイル「$1」は、ストレージバックエンド内部で不一致の状態にあります',
'backend-fail-connect' => 'ストレージバックエンド「$1」に接続できませんでした。',
'backend-fail-internal' => 'ストレージバックエンド「$1」内で不明なエラーが発生しました。',
'backend-fail-contenttype' => '「$1」に保存するコンテンツの種類が判断できませんでした。',
'backend-fail-batchsize' => 'ストレージバックエンドは $1 件のファイル{{PLURAL:$1|操作}}のバッチを与えられました; 上限は $2 件の{{PLURAL:$2|操作}}です。',
'backend-fail-usable' => '権限が不足している、またはディレクトリ/コンテナーがないため、ファイル「$1」の読み取り/書き込みができません。',

# File journal errors
'filejournal-fail-dbconnect' => 'ストレージバックエンド「$1」のジャーナルデータベースに接続できません。',
'filejournal-fail-dbquery' => 'ストレージバックエンド「$1」のジャーナルデータベースを更新できません。',

# Lock manager
'lockmanager-notlocked' => '「$1」をロック解除できませんでした。ロックされていません。',
'lockmanager-fail-closelock' => '「$1」用のロックファイルを閉じることができませんでした。',
'lockmanager-fail-deletelock' => '「$1」用のロックファイルを削除できませんでした。',
'lockmanager-fail-acquirelock' => '「$1」用のロックを取得できませんでした。',
'lockmanager-fail-openlock' => '「$1」用のロックファイルを開くことができませんでした。',
'lockmanager-fail-releaselock' => '「$1」用のロックを解放できませんでした。',
'lockmanager-fail-db-bucket' => 'バケツ $1 で十分な数のロックデータベースに接触できませんでした。',
'lockmanager-fail-db-release' => 'データベース $1 上のロックを解放できませんでした。',
'lockmanager-fail-svr-acquire' => 'サーバー $1 上でロックを取得できませんでした。',
'lockmanager-fail-svr-release' => 'サーバー $1 上のロックを解放できませんでした。',

# ZipDirectoryReader
'zip-file-open-error' => 'ZIPのチェックを行った際にエラーが検出されました。',
'zip-wrong-format' => '指定されたファイルはZIPファイルではありませんでした。',
'zip-bad' => 'このファイルは破損しているか解読不能のZIPファイルです。
セキュリティについて適切な検査ができません。',
'zip-unsupported' => 'このファイルはMediaWikiが未対応のZIP機能を使用したZIPファイルです。
セキュリティについて適切な検査ができません。',

# Special:UploadStash
'uploadstash' => '未公開アップロード',
'uploadstash-summary' => 'このページでは、アップロード済みまたはアップロード中の、ウィキ上でまだ公開されていないファイルを表示します。これらのファイルは、アップロードした利用者以外閲覧できません。',
'uploadstash-clear' => '未公開ファイルを消去',
'uploadstash-nofiles' => '未公開ファイルはありません。',
'uploadstash-badtoken' => '操作を実行できませんでした。編集するための認証の期限切れが原因である可能性があります。再度試してください。',
'uploadstash-errclear' => 'ファイルの消去に失敗しました。',
'uploadstash-refresh' => 'ファイルの一覧を更新',
'invalid-chunk-offset' => '無効なチャンクオフセット',

# img_auth script messages
'img-auth-accessdenied' => 'アクセスが拒否されました',
'img-auth-nopathinfo' => 'PATH_INFO が見つかりません。
サーバーが、この情報を渡すように構成されていません。
CGI ベースであるため、img_auth に対応できない可能性もあります。
https://www.mediawiki.org/wiki/Manual:Image_Authorization をご覧ください。',
'img-auth-notindir' => '要求されたパスは、設定済みのアップロード先ディレクトリ内にありません。',
'img-auth-badtitle' => '「$1」からは有効なページ名を構築できません。',
'img-auth-nologinnWL' => 'ログインしておらず、さらに「$1」はホワイトリストに入っていません。',
'img-auth-nofile' => 'ファイル「$1」は存在しません。',
'img-auth-isdir' => 'ディレクトリ「$1」にアクセスしようとしています。
ファイルへのアクセスのみが許可されています。',
'img-auth-streaming' => '「$1」を転送中。',
'img-auth-public' => 'img_auth.phpの機能は、非公開ウィキからのファイルの出力です。
このウィキは公開ウィキとして構成されています。
最適なセキュリティのため、img_auth.phpを無効にしています。',
'img-auth-noread' => '利用者は「$1」の読み取り権限を持っていません。',
'img-auth-bad-query-string' => 'URLの中に無効なクエリ文字列があります。',

# HTTP errors
'http-invalid-url' => '無効なURL: $1',
'http-invalid-scheme' => 'スキーム「$1」の URL には未対応です。',
'http-request-error' => '不明なエラーによりHTTPリクエストに失敗しました。',
'http-read-error' => 'HTTP読み込みエラーです。',
'http-timed-out' => 'HTTP要求がタイムアウトしました。',
'http-curl-error' => 'URLからの取得に失敗しました: $1',
'http-bad-status' => 'HTTP リクエストで問題が発生しました: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'URLに到達できませんでした',
'upload-curl-error6-text' => '指定したURLに到達できませんでした。
URLが正しいものであり、ウェブサイトが稼働していることを再度確認してください。',
'upload-curl-error28' => 'アップロードのタイムアウト',
'upload-curl-error28-text' => 'ウェブサイトからの応答に時間がかかりすぎています。
ウェブサイトが現在稼働していることを確認し、しばらく待ってからもう一度お試しください。
混雑していない時間帯に試すことをおすすめします。',

'license' => 'ライセンス:',
'license-header' => 'ライセンス',
'nolicense' => '選択なし',
'license-nopreview' => '(プレビューはありません)',
'upload_source_url' => '(有効かつ一般に公開されている URL)',
'upload_source_file' => '(あなたのコンピューター上のファイル)',

# Special:ListFiles
'listfiles-summary' => 'この特別ページでは、アップロードされたファイルをすべて表示します。',
'listfiles_search_for' => '検索するメディア名:',
'imgfile' => 'ファイル',
'listfiles' => 'ファイル一覧',
'listfiles_thumb' => 'サムネイル',
'listfiles_date' => '日時',
'listfiles_name' => '名前',
'listfiles_user' => '利用者',
'listfiles_size' => 'サイズ',
'listfiles_description' => '概要',
'listfiles_count' => '版数',
'listfiles-show-all' => '画像の古い版を含める',
'listfiles-latestversion' => '現在の版',
'listfiles-latestversion-yes' => 'はい',
'listfiles-latestversion-no' => 'いいえ',

# File description page
'file-anchor-link' => 'ファイル',
'filehist' => 'ファイルの履歴',
'filehist-help' => '過去の版のファイルを表示するには、その版の日時をクリックしてください。',
'filehist-deleteall' => 'すべて削除',
'filehist-deleteone' => '削除',
'filehist-revert' => '差し戻す',
'filehist-current' => '現在の版',
'filehist-datetime' => '日付と時刻',
'filehist-thumb' => 'サムネイル',
'filehist-thumbtext' => '$1時点における版のサムネイル',
'filehist-nothumb' => 'サムネイルなし',
'filehist-user' => '利用者',
'filehist-dimensions' => '寸法',
'filehist-filesize' => 'ファイルサイズ',
'filehist-comment' => 'コメント',
'filehist-missing' => 'ファイルがありません',
'imagelinks' => 'ファイルの使用状況',
'linkstoimage' => '以下の{{PLURAL:$1|ページ|​&#32;$1 ページ}}がこのファイルにリンクしています:',
'linkstoimage-more' => 'このファイルへは $1 を超える数のページからリンクがあります。
以下の一覧ではこのファイルにリンクしている最初の $1 ページのみを表示しています。
[[Special:WhatLinksHere/$2|完全な一覧]]も参照してください。',
'nolinkstoimage' => 'このファイルへリンクしているページはありません。',
'morelinkstoimage' => 'このファイルへの[[Special:WhatLinksHere/$1|リンク元を更に]]を表示する。',
'linkstoimage-redirect' => '$1 (リダイレクト) $2',
'duplicatesoffile' => '以下の $1 {{PLURAL:$1|ファイル}}が、このファイルと重複しています ([[Special:FileDuplicateSearch/$2|詳細]]):',
'sharedupload' => 'このファイルは$1のものであり、他のプロジェクトで使用されている可能性があります。',
'sharedupload-desc-there' => 'このファイルは$1のものであり、他のプロジェクトで使用されている可能性があります。
詳細は[$2 ファイル解説ページ]を参照してください。',
'sharedupload-desc-here' => 'このファイルは$1から来ており、他のプロジェクトで使用されている可能性があります。
$1での[$2 ファイル解説ページ]にある説明を以下に示します。',
'sharedupload-desc-edit' => 'このファイルは$1から来ており、他のプロジェクトで使用されている可能性があります。
$1での[$2 ファイル解説ページ]にある説明を編集した方がいいかもしれません。',
'sharedupload-desc-create' => 'このファイルは$1から来ており、他のプロジェクトで使用されている可能性があります。
$1での[$2 ファイル解説ページ]にある説明を編集した方がいいかもしれません。',
'filepage-nofile' => 'この名前のファイルは存在しません。',
'filepage-nofile-link' => 'この名前のファイルは存在しませんが、[$1 アップロード]できます。',
'uploadnewversion-linktext' => 'このファイルの新しい版をアップロードする',
'shared-repo-from' => '$1 より',
'shared-repo' => '共有リポジトリ',
'shared-repo-name-wikimediacommons' => 'ウィキメディア・コモンズ',
'filepage.css' => '/* ここに記述したCSSはファイル解説ページにて読み込まれます。また外部のクライアントウィキにも影響します */',
'upload-disallowed-here' => 'あなたはこのファイルを上書きできません。',

# File reversion
'filerevert' => '$1を差し戻す',
'filerevert-legend' => 'ファイルを差し戻す',
'filerevert-intro' => "ファイル'''[[Media:$1|$1]]'''を[$4 $2$3版]に差し戻そうとしています。",
'filerevert-comment' => '理由:',
'filerevert-defaultcomment' => '$1$2の版へ差し戻し',
'filerevert-submit' => '差し戻す',
'filerevert-success' => "'''[[Media:$1|$1]]'''は[$4 $2$3の版]に差し戻されました。",
'filerevert-badversion' => 'このファイルに指定された時刻印を持つ過去の版はありません。',

# File deletion
'filedelete' => '$1の削除',
'filedelete-legend' => 'ファイルの削除',
'filedelete-intro' => "'''[[Media:$1|$1]]'''をすべての履歴とともに削除しようとしています。",
'filedelete-intro-old' => "'''[[Media:$1|$1]]'''の[$4 $2$3の版]を削除しようとしています。",
'filedelete-comment' => '理由:',
'filedelete-submit' => '削除',
'filedelete-success' => "'''$1''' を削除しました。",
'filedelete-success-old' => "'''[[Media:$1|$1]]'''の$2$3の版を削除しました。",
'filedelete-nofile' => "'''$1'''は存在しません。",
'filedelete-nofile-old' => "指定された属性を持つ'''$1'''の古い版は存在しません。",
'filedelete-otherreason' => '他の、または追加の理由:',
'filedelete-reason-otherlist' => 'その他の理由',
'filedelete-reason-dropdown' => '*よくある削除理由
** 著作権侵害
** 重複ファイル',
'filedelete-edit-reasonlist' => '削除理由を編集',
'filedelete-maintenance' => 'メンテナンス中のため、ファイルの削除と復元は一時的に無効化されています。',
'filedelete-maintenance-title' => 'ファイルを削除できません',

# MIME search
'mimesearch' => 'MIMEタイプ検索',
'mimesearch-summary' => 'このページでは、ファイルをMIMEタイプで絞り込みます。
contenttype/subtypeの形式で入力してください (例: <code>image/jpeg</code>)。',
'mimetype' => 'MIMEタイプ:',
'download' => 'ダウンロード',

# Unwatched pages
'unwatchedpages' => 'ウォッチされていないページ',

# List redirects
'listredirects' => '転送ページの一覧',

# Unused templates
'unusedtemplates' => '使われていないテンプレート',
'unusedtemplatestext' => 'このページでは{{ns:template}}名前空間にあって他のページに読み込まれていないページを一覧にしています。
削除する前にリンク元で他のリンクがないか確認してください。',
'unusedtemplateswlh' => 'リンク元',

# Random page
'randompage' => 'おまかせ表示',
'randompage-nopages' => '以下の{{PLURAL:$2|名前空間}}にはページがありません: $1',

# Random page in category
'randomincategory' => 'カテゴリ内でおまかせ表示',
'randomincategory-invalidcategory' => '「$1」は有効なカテゴリ名ではありません。',
'randomincategory-nopages' => 'カテゴリ [[:Category:$1|$1]] にはページがありません。',
'randomincategory-selectcategory' => '以下のカテゴリでおまかせ表示: $1 $2',
'randomincategory-selectcategory-submit' => '表示',

# Random redirect
'randomredirect' => 'おまかせリダイレクト',
'randomredirect-nopages' => '「$1」名前空間に転送ページはありません。',

# Statistics
'statistics' => '統計',
'statistics-header-pages' => 'ページに関する統計',
'statistics-header-edits' => '編集に関する統計',
'statistics-header-views' => '閲覧に関する統計',
'statistics-header-users' => '利用者に関する統計',
'statistics-header-hooks' => 'その他の統計',
'statistics-articles' => '記事数',
'statistics-pages' => '総ページ数',
'statistics-pages-desc' => 'トークページ、転送ページなどを含む、ウィキ内のすべてのページです。',
'statistics-files' => 'アップロードされたファイル数',
'statistics-edits' => '{{SITENAME}}の開設以降の編集回数の総計',
'statistics-edits-average' => '1ページあたりの編集回数',
'statistics-views-total' => '総閲覧回数',
'statistics-views-total-desc' => '存在しないページと特別ページに対する閲覧は含まれていません',
'statistics-views-peredit' => '1編集あたりの閲覧回数',
'statistics-users' => '[[Special:ListUsers|利用者]]',
'statistics-users-active' => '活動中の利用者',
'statistics-users-active-desc' => '過去 {{PLURAL:$1|$1 日間}}に何らかの操作をした利用者',
'statistics-mostpopular' => '最も閲覧されているページ',

'pageswithprop' => 'ページプロパティがあるページ',
'pageswithprop-legend' => 'ページプロパティがあるページ',
'pageswithprop-text' => 'このページでは、特定のページプロパティを持つページを列挙します。',
'pageswithprop-prop' => 'プロパティ名:',
'pageswithprop-submit' => '実行',
'pageswithprop-prophidden-long' => 'プロパティ値のテキストが長いため非表示 ($1)',
'pageswithprop-prophidden-binary' => 'プロパティ値のバイナリが長いため非表示 ($1)',

'doubleredirects' => '二重転送',
'doubleredirectstext' => 'このページでは、転送ページへの転送ページを列挙します。
最初の転送ページ、その転送先にある転送ページ、さらにその転送先にあるページ、それぞれへのリンクを各行に表示しています。多くの場合は最終的な転送先が「正しい」転送先であり、最初の転送ページの転送先は最終的な転送先に直接向けるべきです。
<del>取り消し線</del>が入った項目は解決済みです。',
'double-redirect-fixed-move' => '[[$1]]を移動しました。
今後は[[$2]]に転送されます。',
'double-redirect-fixed-maintenance' => '[[$1]]から[[$2]]への二重転送を修正します。',
'double-redirect-fixer' => '転送修正係',

'brokenredirects' => '迷子のリダイレクト',
'brokenredirectstext' => '以下は、存在しないページへのリダイレクトの一覧です:',
'brokenredirects-edit' => '編集',
'brokenredirects-delete' => '削除',

'withoutinterwiki' => '言語間リンクを持たないページ',
'withoutinterwiki-summary' => '以下のページには他の言語版へのリンクがありません。',
'withoutinterwiki-legend' => '先頭文字列',
'withoutinterwiki-submit' => '表示',

'fewestrevisions' => '編集履歴の少ないページ',

# Miscellaneous special pages
'nbytes' => '$1バイト',
'ncategories' => '$1カテゴリ',
'ninterwikis' => '$1 {{PLURAL:$1|個のウィキ間リンク}}',
'nlinks' => '$1 {{PLURAL:$1|個のリンク}}',
'nmembers' => '$1項目',
'nrevisions' => '$1版',
'nviews' => '$1回の閲覧',
'nimagelinks' => '$1 {{PLURAL:$1|ページ}}で使用',
'ntransclusions' => '$1 {{PLURAL:$1|ページ}}で使用',
'specialpage-empty' => '該当するものはありません。',
'lonelypages' => '孤立しているページ',
'lonelypagestext' => '以下のページは、{{SITENAME}}の他のページからリンクも参照読み込みもされていません。',
'uncategorizedpages' => 'カテゴリ分類されていないページ',
'uncategorizedcategories' => 'カテゴリ分類されていないカテゴリ',
'uncategorizedimages' => 'カテゴリ分類されていないファイル',
'uncategorizedtemplates' => 'カテゴリ分類されていないテンプレート',
'unusedcategories' => '使われていないカテゴリ',
'unusedimages' => '使われていないファイル',
'popularpages' => '人気のページ',
'wantedcategories' => 'カテゴリページが存在しないカテゴリ',
'wantedpages' => 'ページが存在しないリンク',
'wantedpages-badtitle' => '結果が、無効なページ名を含んでいます: $1',
'wantedfiles' => 'ファイル情報ページが存在しないファイル',
'wantedfiletext-cat' => '以下のファイルは使用されていますが存在しません。外部リポジトリ由来のファイルは、存在していてもここに列挙される場合があります。その場合は<del>取り消し線</del>が付きます。さらに、存在しないファイルを埋め込んでいるページは[[:$1]]に列挙されます。',
'wantedfiletext-nocat' => '以下のファイルは使用されていますが存在しません。外部リポジトリ由来のファイルは、存在していてもここに列挙される場合があります。その場合は<del>取り消し線</del>が付きます。',
'wantedtemplates' => '呼び出し先が存在しないテンプレート呼び出し',
'mostlinked' => '被リンク数の多いページ',
'mostlinkedcategories' => '被リンク数の多いカテゴリ',
'mostlinkedtemplates' => '使用箇所の多いテンプレート',
'mostcategories' => 'カテゴリの多いページ',
'mostimages' => '被リンク数の多いファイル',
'mostinterwikis' => 'ウィキ間リンクの多いページ',
'mostrevisions' => '版の多いページ',
'prefixindex' => '先頭が同じ全ページ',
'prefixindex-namespace' => '先頭が同じ全ページ ($1名前空間)',
'prefixindex-strip' => '一覧で接頭辞を省略',
'shortpages' => '短いページ',
'longpages' => '長いページ',
'deadendpages' => '行き止まりページ',
'deadendpagestext' => '以下のページは、{{SITENAME}}の他のページにリンクしていません。',
'protectedpages' => '保護されているページ',
'protectedpages-indef' => '無期限保護のみ',
'protectedpages-cascade' => 'カスケード保護のみ',
'protectedpagestext' => '以下のページは移動や編集が禁止されています',
'protectedpagesempty' => '指定した条件で保護中のページは現在ありません。',
'protectedtitles' => '作成保護されているページ名',
'protectedtitlestext' => '以下のページは新規作成が禁止されています',
'protectedtitlesempty' => 'これらの引数で現在保護されているページはありません。',
'listusers' => '利用者一覧',
'listusers-editsonly' => '投稿記録のある利用者のみを表示',
'listusers-creationsort' => '作成日順に並べ替え',
'listusers-desc' => '降順に並べ替える',
'usereditcount' => '$1 {{PLURAL:$1|回編集}}',
'usercreated' => '$1 $2 に{{GENDER:$3|作成}}',
'newpages' => '新しいページ',
'newpages-username' => '利用者名:',
'ancientpages' => '最古のページ',
'move' => '移動',
'movethispage' => 'このページを移動',
'unusedimagestext' => '以下のファイルは、存在しますがどのページにも埋め込まれていません。
ただし、他のウェブサイトがURLでファイルに直接リンクする場合があることに注意してください。以下のファイル一覧には、そのような形で利用中のファイルが含まれている場合があります。',
'unusedcategoriestext' => '以下のカテゴリはページが存在しますが、他のどのページおよびカテゴリでも使用されていません。',
'notargettitle' => '対象が存在しません',
'notargettext' => 'この機能の実行対象となるページまたは利用者が指定されていません。',
'nopagetitle' => 'そのようなページはありません',
'nopagetext' => '指定したページは存在しません。',
'pager-newer-n' => '{{PLURAL:$1|以後の$1件}}',
'pager-older-n' => '{{PLURAL:$1|以前の$1件}}',
'suppress' => '秘匿する',
'querypage-disabled' => 'パフォーマンスに悪影響を与えるおそれがあるため、この特別ページは無効になっています。',

# Book sources
'booksources' => '書籍情報源',
'booksources-search-legend' => '書籍情報源を検索',
'booksources-isbn' => 'ISBN:',
'booksources-go' => '検索',
'booksources-text' => 'お探しの書籍の新品/中古品を販売している外部サイトへのリンクを以下に列挙します。この書籍についてさらに詳しい情報があるかもしれません:',
'booksources-invalid-isbn' => '指定した ISBN は有効ではないようです。情報源から写し間違えていないか確認してください。',

# Special:Log
'specialloguserlabel' => '実行者:',
'speciallogtitlelabel' => '対象 (ページまたは利用者):',
'log' => '記録',
'all-logs-page' => 'すべての公開記録',
'alllogstext' => '{{SITENAME}}の取得できる記録をまとめて表示しています。
記録の種類、実行した利用者 (大文字小文字は区別)、影響を受けたページ (大文字小文字は区別) による絞り込みができます。',
'logempty' => '該当する記録はありません。',
'log-title-wildcard' => 'この文字列で始まるページ名を検索',
'showhideselectedlogentries' => '選択した記録項目を表示/非表示',

# Special:AllPages
'allpages' => '全ページ',
'alphaindexline' => '$1から$2まで',
'nextpage' => '次のページ ($1)',
'prevpage' => '前のページ ($1)',
'allpagesfrom' => '最初に表示するページ:',
'allpagesto' => '最後に表示するページ:',
'allarticles' => '全ページ',
'allinnamespace' => '全ページ ($1名前空間)',
'allnotinnamespace' => '全ページ ($1名前空間以外)',
'allpagesprev' => '前へ',
'allpagesnext' => '次へ',
'allpagessubmit' => '表示',
'allpagesprefix' => '次の文字列から始まるページを表示:',
'allpagesbadtitle' => '指定したページ名は無効か、言語間またはインターウィキ接頭辞を含んでいます。
ページ名に使用できない文字が1つ以上含まれている可能性があります。',
'allpages-bad-ns' => '{{SITENAME}}に「$1」という名前空間はありません。',
'allpages-hide-redirects' => 'リダイレクトを隠す',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'このページのキャッシュされた版を表示しています。最大 $1経過しています。',
'cachedspecial-viewing-cached-ts' => 'このページのキャッシュされた版を表示しています。現在の実際の版と異なる場合があります。',
'cachedspecial-refresh-now' => '最新版を表示します。',

# Special:Categories
'categories' => 'カテゴリ',
'categoriespagetext' => '以下の{{PLURAL:$1|カテゴリ}}にはページまたはメディアがあります。
[[Special:UnusedCategories|未使用のカテゴリ]]はここには表示していません。
[[Special:WantedCategories|望まれるカテゴリ]]も参照してください。',
'categoriesfrom' => '最初に表示するカテゴリ:',
'special-categories-sort-count' => '項目数順に並べ替え',
'special-categories-sort-abc' => '辞書順に並べ替え',

# Special:DeletedContributions
'deletedcontributions' => '利用者の削除された投稿',
'deletedcontributions-title' => '利用者の削除された投稿',
'sp-deletedcontributions-contribs' => '投稿記録',

# Special:LinkSearch
'linksearch' => '外部リンクの検索',
'linksearch-pat' => '検索パターン:',
'linksearch-ns' => '名前空間:',
'linksearch-ok' => '検索',
'linksearch-text' => '「*.wikipedia.org」のようにワイルドカードを使用できます。
少なくとも「*.org」のようなトップレベルドメインが必要です。<br />
対応{{PLURAL:$2|プロトコル}}: <code>$1</code> (プロトコルを省略した場合の既定値は http:// )。',
'linksearch-line' => '$1 が $2 からリンクされています',
'linksearch-error' => 'ワイルドカードはホスト名の先頭でのみ使用できます。',

# Special:ListUsers
'listusersfrom' => '最初に表示する利用者:',
'listusers-submit' => '表示',
'listusers-noresult' => '利用者が見つかりませんでした。',
'listusers-blocked' => '(ブロック中)',

# Special:ActiveUsers
'activeusers' => '活動中の利用者一覧',
'activeusers-intro' => 'これは過去 $1 {{PLURAL:$1|日|日間}}に何らかの活動をした利用者の一覧です。',
'activeusers-count' => '過去 {{PLURAL:$3|1 日|$3 日間}}に $1 {{PLURAL:$1|回の操作}}',
'activeusers-from' => '最初に表示する利用者:',
'activeusers-hidebots' => 'ボットを隠す',
'activeusers-hidesysops' => '管理者を隠す',
'activeusers-noresult' => '利用者が見つかりませんでした。',

# Special:ListGroupRights
'listgrouprights' => '利用者グループの権限',
'listgrouprights-summary' => '以下は、このウィキに登録されている利用者グループと、それぞれに割り当てられている権限の一覧です。
個々の権限に関する更なる情報は[[{{MediaWiki:Listgrouprights-helppage}}|追加情報]]を見てください。',
'listgrouprights-key' => '凡例:
* <span class="listgrouprights-granted">与えられた権限</span>
* <span class="listgrouprights-revoked">取り消された権限</span>',
'listgrouprights-group' => 'グループ',
'listgrouprights-rights' => '権限',
'listgrouprights-helppage' => 'Help:グループ権限',
'listgrouprights-members' => '(該当者一覧)',
'listgrouprights-right-display' => '<span class="listgrouprights-granted">$1 (<code>$2</code>)</span>',
'listgrouprights-right-revoked' => '<span class="listgrouprights-revoked">$1 (<code>$2</code>)</span>',
'listgrouprights-addgroup' => '{{PLURAL:$2|グループ}}を追加: $1',
'listgrouprights-removegroup' => '{{PLURAL:$2|グループ}}を除去: $1',
'listgrouprights-addgroup-all' => '全グループを追加可能',
'listgrouprights-removegroup-all' => '全グループを除去可能',
'listgrouprights-addgroup-self' => '自身のアカウントに{{PLURAL:$2|グループ}}を追加: $1',
'listgrouprights-removegroup-self' => '自身のアカウントから{{PLURAL:$2|グループ}}を除去: $1',
'listgrouprights-addgroup-self-all' => '自身のアカウントに全グループを追加可能',
'listgrouprights-removegroup-self-all' => '自身のアカウントから全グループを除去可能',

# Email user
'mailnologin' => '送信アドレスがありません',
'mailnologintext' => '他の利用者宛にメールを送信するためには、[[Special:UserLogin|ログイン]]し、[[Special:Preferences|個人設定]]で有効なメールアドレスを設定する必要があります。',
'emailuser' => 'この利用者にメールを送信',
'emailuser-title-target' => 'この{{GENDER:$1|利用者}}にメールを送信',
'emailuser-title-notarget' => '利用者にメールを送信',
'emailpage' => '利用者にメールを送信',
'emailpagetext' => '以下のフォームを使用してこの{{GENDER:$1|利用者}}にメールを送信できます。
「差出人」として、[[Special:Preferences|利用者の個人設定]]で入力したメールアドレスが設定されます。これにより、受信者があなたに直接返信できるようになります。',
'usermailererror' => 'メールが以下のエラーを返しました:',
'defemailsubject' => '{{SITENAME}} 利用者「$1」からのメール',
'usermaildisabled' => '利用者メール機能は無効です',
'usermaildisabledtext' => 'このウィキでは他の利用者にメールを送信できません',
'noemailtitle' => 'メールアドレスがありません',
'noemailtext' => 'この利用者は有効なメールアドレスを登録していません。',
'nowikiemailtitle' => 'メールは許可されていません',
'nowikiemailtext' => 'この利用者は他の利用者からメールを受け取らない設定にしています。',
'emailnotarget' => '受信者の利用者名が存在しない、あるいは無効です。',
'emailtarget' => '受信者の利用者名を入力してください',
'emailusername' => '利用者名:',
'emailusernamesubmit' => '送信',
'email-legend' => '{{SITENAME}} の他の利用者にメールを送信',
'emailfrom' => '差出人:',
'emailto' => '宛先:',
'emailsubject' => '件名:',
'emailmessage' => '本文:',
'emailsend' => '送信',
'emailccme' => '自分宛に控えを送信する。',
'emailccsubject' => '$1 に送信したメールの控え: $2',
'emailsent' => 'メールを送信しました',
'emailsenttext' => 'メールを送信しました。',
'emailuserfooter' => 'このメールは$1から$2へ、{{SITENAME}}の「利用者にメールを送信」機能で送信されました。',

# User Messenger
'usermessage-summary' => 'システムメッセージを残す。',
'usermessage-editor' => 'システムメッセンジャー',

# Watchlist
'watchlist' => 'ウォッチリスト',
'mywatchlist' => 'ウォッチリスト',
'watchlistfor2' => '利用者: $1 $2',
'nowatchlist' => 'ウォッチリストには何も項目がありません。',
'watchlistanontext' => 'ウォッチリストにある項目を閲覧または編集するには、$1してください。',
'watchnologin' => 'ログインしていません',
'watchnologintext' => 'ウォッチリストを変更するためには、[[Special:UserLogin|ログイン]]している必要があります。',
'addwatch' => 'ウォッチリストに追加',
'addedwatchtext' => 'ページ「[[:$1]]」を[[Special:Watchlist|ウォッチリスト]]に追加しました。
このページまたはそのトークページが変更されると、ウォッチリストに表示されます。',
'removewatch' => 'ウォッチリストから除去',
'removedwatchtext' => 'ページ「[[:$1]]」を[[Special:Watchlist|ウォッチリスト]]から除去しました。',
'watch' => 'ウォッチ',
'watchthispage' => 'このページをウォッチする',
'unwatch' => 'ウォッチ解除',
'unwatchthispage' => 'ウォッチをやめる',
'notanarticle' => '記事ではありません',
'notvisiblerev' => '別の利用者による最終版は削除されました',
'watchlist-details' => 'ウォッチリストには {{PLURAL:$1|$1 ページ}}が登録されています (トークページを除く)。',
'wlheader-enotif' => 'メール通知が有効になっています。',
'wlheader-showupdated' => "最終訪問以降に変更されたページは、'''太字'''で表示されます。",
'watchmethod-recent' => '最近の更新内のウォッチされているページを確認中',
'watchmethod-list' => 'ウォッチされているページ内の最近の更新を確認中',
'watchlistcontains' => 'ウォッチリストには {{PLURAL:$1|$1 ページ}}が登録されています。',
'iteminvalidname' => '項目「$1」には問題があります。名前が無効です...',
'wlnote' => "$3 $4 までの{{PLURAL:$2|'''$2'''時間}}になされた{{PLURAL:$1|'''$1'''件の変更}}は以下の通りです。",
'wlshowlast' => '次の期間で表示: $1時間、$2日間、$3',
'watchlist-options' => 'ウォッチリストのオプション',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'ウォッチリストに追加中...',
'unwatching' => 'ウォッチリストから除去中...',
'watcherrortext' => 'ウォッチリストの「$1」の設定を変更中にエラーが発生しました。',

'enotif_mailer' => '{{SITENAME}} 通知メール',
'enotif_reset' => 'すべてのページを訪問済みにする',
'enotif_impersonal_salutation' => '{{SITENAME}}の利用者',
'enotif_subject_deleted' => '{{SITENAME}} ページ $1 を $2 が{{GENDER:$2|削除しました}}',
'enotif_subject_created' => '{{SITENAME}} ページ $1 を $2 が{{GENDER:$2|作成しました}}',
'enotif_subject_moved' => '{{SITENAME}} ページ $1 を $2 が{{GENDER:$2|移動しました}}',
'enotif_subject_restored' => '{{SITENAME}} ページ $1 を $2 が{{GENDER:$2|復元しました}}',
'enotif_subject_changed' => '{{SITENAME}} ページ $1 を $2 が{{GENDER:$2|変更しました}}',
'enotif_body_intro_deleted' => '{{SITENAME}}のページ「$1」が$PAGEEDITDATEに、$2 によって{{GENDER:$2|削除}}されました。$3 をご覧ください。',
'enotif_body_intro_created' => '{{SITENAME}}のページ「$1」が$PAGEEDITDATEに、$2 によって{{GENDER:$2|作成}}されました。現在の版は $3 で閲覧できます。',
'enotif_body_intro_moved' => '{{SITENAME}}のページ「$1」が$PAGEEDITDATEに、$2 によって{{GENDER:$2|移動}}されました。現在の版は $3 で閲覧できます。',
'enotif_body_intro_restored' => '{{SITENAME}}のページ「$1」が$PAGEEDITDATEに、$2 によって{{GENDER:$2|復元}}されました。現在の版は $3 で閲覧できます。',
'enotif_body_intro_changed' => '{{SITENAME}}のページ「$1」が$PAGEEDITDATEに、$2 によって{{GENDER:$2|変更}}されました。現在の版は $3 で閲覧できます。',
'enotif_lastvisited' => '最終訪問以降のすべての変更は $1 をご覧ください。',
'enotif_lastdiff' => 'この変更内容を表示するには $1 をご覧ください。',
'enotif_anon_editor' => '匿名利用者 $1',
'enotif_body' => '$WATCHINGUSERNAMEさん

$PAGEINTRO $NEWPAGE

編集内容の要約: $PAGESUMMARY ($PAGEMINOREDIT)

投稿者の連絡先:
メール: $PAGEEDITOR_EMAIL
ウィキ: $PAGEEDITOR_WIKI

このページを訪れない限り、これ以上の活動に対する通知は送信されません。ウォッチリスト内のすべてのページについて、通知を再設定することもできます。

                         {{SITENAME}}通知システム

--
メール通知の設定は、以下のページで変更してください:
{{canonicalurl:{{#special:Preferences}}}}

ウォッチリストの設定は、以下のページで変更してください:
{{canonicalurl:{{#special:EditWatchlist}}}}

このページは、以下のページでウォッチリストから削除できます:
$UNWATCHURL

ご意見、お問い合わせ:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => '作成',
'changed' => '変更',

# Delete
'deletepage' => 'ページを削除',
'confirm' => '確認',
'excontent' => '内容:「$1」',
'excontentauthor' => '内容:「$1」(投稿者は「[[Special:Contributions/$2|$2]]」のみ)',
'exbeforeblank' => '白紙化前の内容:「$1」',
'exblank' => '白紙ページ',
'delete-confirm' => '「$1」の削除',
'delete-legend' => '削除',
'historywarning' => "'''警告:''' 削除しようとしているページには、約$1版の履歴があります:",
'confirmdeletetext' => 'ページをすべての履歴とともに削除しようとしています。
本当にこの操作を行いたいか、操作の結果を理解しているか、およびこの操作が[[{{MediaWiki:Policy-url}}|方針]]に従っているかどうか、確認してください。',
'actioncomplete' => '操作を完了しました',
'actionfailed' => '操作に失敗しました',
'deletedtext' => '「$1」は削除されました。
最近の削除については、$2を参照してください。',
'dellogpage' => '削除記録',
'dellogpagetext' => '以下は最近の削除と復元の一覧です。',
'deletionlog' => '削除記録',
'reverted' => '以前の版への差し戻し',
'deletecomment' => '理由:',
'deleteotherreason' => '他の、または追加の理由:',
'deletereasonotherlist' => 'その他の理由',
'deletereason-dropdown' => '*よくある削除理由
** スパム
** 荒らし
** 著作権侵害
** 投稿者依頼
** 破損リダイレクト',
'delete-edit-reasonlist' => '削除理由を編集',
'delete-toobig' => 'このページには、$1版を超える編集履歴があります。
このようなページの削除は、{{SITENAME}}の偶発的な問題を避けるため、制限されています。',
'delete-warning-toobig' => 'このページには、 $1版を超える編集履歴があります。
削除すると、{{SITENAME}}のデータベース処理に大きな負荷がかかります。
十分に注意してください。',

# Rollback
'rollback' => '編集を巻き戻し',
'rollback_short' => '巻き戻し',
'rollbacklink' => '巻き戻し',
'rollbacklinkcount' => '$1{{PLURAL:$1|編集}}を巻き戻し',
'rollbacklinkcount-morethan' => '$1{{PLURAL:$1|編集}}以上を巻き戻し',
'rollbackfailed' => '巻き戻しに失敗しました',
'cantrollback' => '編集を差し戻せません。
最後の投稿者が、このページの唯一の作者です。',
'alreadyrolled' => 'ページ[[:$1]]の[[User:$2|$2]] ([[User talk:$2|トーク]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) による編集を巻き戻せません。
他の利用者が既に編集または巻き戻しを行ったためです。

このページの最後の編集は[[User:$3|$3]] ([[User talk:$3|トーク]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) によるものです。',
'editcomment' => "編集内容の要約:「''$1''」",
'revertpage' => '[[Special:Contributions/$2|$2]] ([[User talk:$2|トーク]]) による編集を [[User:$1|$1]] による直前の版へ差し戻しました',
'revertpage-nouser' => '非表示の利用者による編集を {{GENDER:$1|[[User:$1|$1]]}} による直前の版へ差し戻しました',
'rollback-success' => '$1による編集を差し戻しました。
$2による直前の版へ変更されました。',

# Edit tokens
'sessionfailure-title' => 'セッションの失敗',
'sessionfailure' => 'ログインのセッションに問題が発生しました。
セッション乗っ取りを防ぐため、操作を取り消しました。
前のページへ戻って再度読み込んだ後に、もう一度試してください。',

# Protect
'protectlogpage' => '保護記録',
'protectlogtext' => '以下はページ保護に対する変更の記録です。
現在、保護レベルを変更できるページについては[[Special:ProtectedPages|保護ページ一覧]]を参照してください。',
'protectedarticle' => '「[[$1]]」を保護しました',
'modifiedarticleprotection' => '「[[$1]]」の保護レベルを変更しました',
'unprotectedarticle' => '「[[$1]]」の保護を解除しました',
'movedarticleprotection' => 'が保護の設定を「[[$2]]」から「[[$1]]」へ移動しました',
'protect-title' => '「$1」の保護レベルを変更',
'protect-title-notallowed' => '「$1」の保護レベルを表示',
'prot_1movedto2' => '[[$1]] を [[$2]] へ移動',
'protect-badnamespace-title' => '保護不可能な名前空間',
'protect-badnamespace-text' => 'この名前空間のページは保護できません。',
'protect-norestrictiontypes-text' => '利用できる制限の種類がないため、このページは保護できません。',
'protect-norestrictiontypes-title' => '保護できないページ',
'protect-legend' => '保護の確認',
'protectcomment' => '理由:',
'protectexpiry' => '有効期限:',
'protect_expiry_invalid' => '有効期間が正しくありません。',
'protect_expiry_old' => '有効期限が過去の時刻です。',
'protect-unchain-permissions' => '追加保護オプションをロック解除',
'protect-text' => "ここでは、ページ '''$1''' に対する保護レベルの表示と操作ができます。",
'protect-locked-blocked' => "ブロックされている間は、保護レベルを変更できません。
ページ '''$1''' の現在の状態は以下の通りです:",
'protect-locked-dblock' => "データベースのロックが有効なため、保護レベルを変更できません。
ページ '''$1''' の現在の状態は以下の通りです:",
'protect-locked-access' => "アカウントに、ページの保護レベルを変更する権限がありません。
ページ '''$1''' の現在の状態は以下の通りです:",
'protect-cascadeon' => 'このページは現在、カスケード保護が有効になっている以下の{{PLURAL:$1|ページ|ページ群}}から読み込まれているため、保護されています。
このページの保護レベルを変更できますが、カスケード保護には影響しません。',
'protect-default' => 'すべての利用者に許可',
'protect-fallback' => '「$1」権限を持つ利用者のみに許可',
'protect-level-autoconfirmed' => '自動承認された利用者のみに許可',
'protect-level-sysop' => '管理者のみに許可',
'protect-summary-desc' => '[$1=$2] ($3)',
'protect-summary-cascade' => 'カスケード',
'protect-expiring' => '$1(UTC)で自動的に解除',
'protect-expiring-local' => '期限 $1',
'protect-expiry-indefinite' => '無期限',
'protect-cascade' => 'このページに読み込まれているページを保護する (カスケード保護)',
'protect-cantedit' => 'このページの編集権限がないため、保護レベルを変更できません。',
'protect-othertime' => 'その他の期間:',
'protect-othertime-op' => 'その他の期間',
'protect-existing-expiry' => '現在の保護期限: $2 $3',
'protect-otherreason' => '他の、または追加の理由:',
'protect-otherreason-op' => 'その他の理由',
'protect-dropdown' => '*よくある保護理由
** 度重なる荒らし
** 度重なるスパム投稿
** 非生産的な編集合戦
** 高負荷ページ',
'protect-edit-reasonlist' => '保護理由を編集',
'protect-expiry-options' => '1時間:1 hour,1日:1 day,1週間:1 week,2週間:2 weeks,1か月:1 month,3か月:3 months,6か月:6 months,1年:1 year,無期限:infinite',
'restriction-type' => '許可:',
'restriction-level' => '制限レベル:',
'minimum-size' => '最小サイズ',
'maximum-size' => '最大サイズ:',
'pagesize' => '(バイト)',

# Restrictions (nouns)
'restriction-edit' => '編集',
'restriction-move' => '移動',
'restriction-create' => '作成',
'restriction-upload' => 'アップロード',

# Restriction levels
'restriction-level-sysop' => '全保護',
'restriction-level-autoconfirmed' => '半保護',
'restriction-level-all' => '任意のレベル',

# Undelete
'undelete' => '削除されたページを表示',
'undeletepage' => '削除されたページの表示と復元',
'undeletepagetitle' => "'''以下は[[:$1|$1]]の削除された版です'''。",
'viewdeletedpage' => '削除されたページを表示',
'undeletepagetext' => '以下の{{PLURAL:$1|削除されたページ|​&#32;$1 件の削除されたページ}}は、保存版に残っているため復元できます。
保存版は定期的に消去される可能性があります。',
'undelete-fieldset-title' => '削除された版の復元',
'undeleteextrahelp' => "すべての版を復元する場合は、どのボックスにもチェックを入れていない状態で'''''{{int:undeletebtn}}'''''をクリックしてください。
特定の版を復元する場合は、復元する版のボックスにチェックを入れた状態で'''''{{int:undeletebtn}}'''''をクリックしてください。",
'undeleterevisions' => '保存版に$1{{PLURAL:$1|版}}あります',
'undeletehistory' => 'ページを復元すると、すべての版が履歴に復元されます。
削除後に同じ名前で新しいページが作成されている場合、復元された版はそれに先行する履歴として表示されます。',
'undeleterevdel' => '版指定削除されている版がページまたはファイルの最新版となるような復元はできません。
この場合、版指定削除されている最新版のチェックを外すか、その版指定削除を解除する必要があります。',
'undeletehistorynoadmin' => 'このページは削除されています。
削除の理由は、削除前にこのページを編集していた利用者の詳細情報と共に、以下に表示されています。
管理者以外の利用者には、削除された各版の本文への制限がかけられています。',
'undelete-revision' => '削除されたページ $1 の $4 $5 時点での $3 による版:',
'undeleterevision-missing' => '無効または存在しない版です。
間違ったリンクをたどったか、この版は既に復元されたか、もしくは保存版から除去された可能性があります。',
'undelete-nodiff' => 'これより前の版はありません。',
'undeletebtn' => '復元',
'undeletelink' => '閲覧/復元',
'undeleteviewlink' => '閲覧',
'undeletereset' => 'リセット',
'undeleteinvert' => '選択を反転',
'undeletecomment' => '理由:',
'undeletedrevisions' => '{{PLURAL:$1|$1版}}を復元しました',
'undeletedrevisions-files' => '{{PLURAL:$1|$1版}}と{{PLURAL:$2|$2ファイル}}を復元しました',
'undeletedfiles' => '{{PLURAL:$1|$1ファイル}}を復元しました',
'cannotundelete' => '復元に失敗しました:
$1',
'undeletedpage' => "'''$1を復元しました。'''

最近の削除と復元の記録については[[Special:Log/delete|削除記録]]を参照してください。",
'undelete-header' => '最近削除されたページは[[Special:Log/delete|削除記録]]で確認できます。',
'undelete-search-title' => '削除されたページの検索',
'undelete-search-box' => '削除されたページの検索',
'undelete-search-prefix' => '表示を開始するページ名:',
'undelete-search-submit' => '検索',
'undelete-no-results' => '削除の保存版に、一致するページが見つかりませんでした。',
'undelete-filename-mismatch' => 'ファイルの $1 時点の版を復元できません: ファイル名が一致しません。',
'undelete-bad-store-key' => 'ファイルの $1 時点の版を復元できません: 削除前にファイルが失われています。',
'undelete-cleanup-error' => '未使用の保存版のファイル「$1」の削除中にエラーが発生しました。',
'undelete-missing-filearchive' => 'データベースに存在しないため、ファイルの保存版 ID $1 を復元できません。
既に復元されている可能性があります。',
'undelete-error' => 'ページの復元エラー',
'undelete-error-short' => 'ファイルの復元エラー: $1',
'undelete-error-long' => 'ファイルの復元中にエラーが発生しました:

$1',
'undelete-show-file-confirm' => 'ファイル「<nowiki>$1</nowiki>」の削除された$2$3の版を本当に閲覧しますか?',
'undelete-show-file-submit' => 'はい',

# Namespace form on various pages
'namespace' => '名前空間:',
'invert' => '選択したものを除く',
'tooltip-invert' => '選択した名前空間 (チェックを入れている場合は、関連付けられた名前空間も含む) のページの変更を非表示にするには、このボックスにチェックを入れる',
'namespace_association' => '関連付けられた名前空間も含める',
'tooltip-namespace_association' => '選択した名前空間に関連付けられたトークページ (逆にトークページの名前空間を選択した場合も同様) の名前空間も含めるには、このボックスにチェックを入れる',
'blanknamespace' => '（標準）',

# Contributions
'contributions' => '{{GENDER:$1|利用者}}の投稿記録',
'contributions-title' => '$1の投稿記録',
'mycontris' => '投稿記録',
'contribsub2' => '利用者: {{GENDER:$3|$1}} ($2)',
'nocontribs' => 'これらの条件に一致する変更は見つかりませんでした。',
'uctop' => '(最新)',
'month' => 'この月以前:',
'year' => 'この年以前:',

'sp-contributions-newbies' => '新規利用者の投稿のみ表示',
'sp-contributions-newbies-sub' => '新規利用者のみ',
'sp-contributions-newbies-title' => '新規利用者の投稿記録',
'sp-contributions-blocklog' => 'ブロック記録',
'sp-contributions-deleted' => '削除された投稿の一覧',
'sp-contributions-uploads' => 'アップロード',
'sp-contributions-logs' => '記録',
'sp-contributions-talk' => 'トーク',
'sp-contributions-userrights' => '利用者権限の管理',
'sp-contributions-blocked-notice' => 'この利用者は現在ブロックされています。
参考のために最新のブロック記録項目を以下に表示します:',
'sp-contributions-blocked-notice-anon' => 'このIPアドレスは現在ブロックされています。
参考のために最近のブロック記録項目を以下に表示します:',
'sp-contributions-search' => '投稿の検索',
'sp-contributions-username' => 'IPアドレスまたは利用者名:',
'sp-contributions-toponly' => '最新版の編集のみを表示',
'sp-contributions-submit' => '検索',

# What links here
'whatlinkshere' => 'リンク元',
'whatlinkshere-title' => '「$1」へリンクしているページ',
'whatlinkshere-page' => 'ページ:',
'linkshere' => "以下のページが、'''[[:$1]]''' にリンクしています:",
'nolinkshere' => "'''[[:$1]]''' にリンクしているページはありません。",
'nolinkshere-ns' => "指定した名前空間内に、'''[[:$1]]''' にリンクしているページはありません。",
'isredirect' => '転送ページ',
'istemplate' => '参照読み込み',
'isimage' => 'ファイルへのリンク',
'whatlinkshere-prev' => '{{PLURAL:$1|前|前の$1件}}',
'whatlinkshere-next' => '{{PLURAL:$1|次|次の$1件}}',
'whatlinkshere-links' => '← リンク',
'whatlinkshere-hideredirs' => '転送ページを$1',
'whatlinkshere-hidetrans' => '参照読み込みを$1',
'whatlinkshere-hidelinks' => 'リンクを$1',
'whatlinkshere-hideimages' => 'ファイルへのリンクを$1',
'whatlinkshere-filters' => '絞り込み',

# Block/unblock
'autoblockid' => '自動ブロック #$1',
'block' => '利用者をブロック',
'unblock' => '利用者のブロックを解除',
'blockip' => '利用者をブロック',
'blockip-title' => '利用者のブロック',
'blockip-legend' => '利用者をブロック',
'blockiptext' => '以下のフォームを使用して、指定したIPアドレスまたは利用者からの書き込みアクセスをブロックできます。
このような措置は、荒らしからの防御の目的のみに行われるべきで、また[[{{MediaWiki:Policy-url}}|方針]]に沿ったものであるべきです。
以下にブロックの理由を具体的に書いてください (例えば、荒らされたページへの言及など)。',
'ipadressorusername' => 'IPアドレスまたは利用者名:',
'ipbexpiry' => '有効期限:',
'ipbreason' => '理由:',
'ipbreasonotherlist' => 'その他の理由',
'ipbreason-dropdown' => '*よくあるブロック理由
** 虚偽情報の挿入
** ページから内容の除去
** 外部サイトへのスパムリンク追加
** ページへ無意味な/意味不明な内容の挿入
** 威圧的な態度/嫌がらせ
** 複数アカウントの不正利用
** 不適切な利用者名',
'ipb-hardblock' => 'このIPアドレスからのログイン利用者の編集を禁止',
'ipbcreateaccount' => 'アカウント作成を禁止',
'ipbemailban' => 'メール送信を禁止',
'ipbenableautoblock' => 'この利用者が最後に使用したIPアドレスと、ブロック後に編集を試みた際のIPアドレスを自動的にブロック',
'ipbsubmit' => 'この利用者をブロック',
'ipbother' => 'その他の期間:',
'ipboptions' => '2時間:2 hours,1日:1 day,3日:3 days,1週間:1 week,2週間:2 weeks,1か月:1 month,3か月:3 months,6か月:6 months,1年:1 year,無期限:infinite',
'ipbotheroption' => 'その他',
'ipbotherreason' => '他の、または追加の理由:',
'ipbhidename' => '利用者名を編集履歴や各種一覧から秘匿する',
'ipbwatchuser' => 'この利用者の利用者ページとトークページをウォッチ',
'ipb-disableusertalk' => 'この利用者がブロック中に自身のトークページを編集することを禁止',
'ipb-change-block' => 'これらの設定で、利用者を再びブロック',
'ipb-confirm' => 'ブロックの確認',
'badipaddress' => '無効なIPアドレス',
'blockipsuccesssub' => 'ブロックしました',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]]をブロックしました。<br />
[[Special:BlockList|ブロックの一覧]]を参照してください。',
'ipb-blockingself' => '自分自身をブロックしようとしています! 本当に実行しますか?',
'ipb-confirmhideuser' => '利用者名の秘匿を有効にしてブロックしようとしています。実行すると、すべての一覧や記録項目で利用者名が表示されません。本当に実行しますか?',
'ipb-edit-dropdown' => 'ブロック理由を編集',
'ipb-unblock-addr' => '$1のブロックを解除',
'ipb-unblock' => '利用者またはIPアドレスのブロックを解除',
'ipb-blocklist' => '現在有効なブロックを表示',
'ipb-blocklist-contribs' => '$1の投稿の一覧',
'unblockip' => 'ブロックを解除',
'unblockiptext' => '以下のフォームで利用者またはIPアドレスのブロックを解除できます。',
'ipusubmit' => 'このブロックを解除',
'unblocked' => '[[User:$1|$1]]のブロックを解除しました。',
'unblocked-range' => '$1のブロックを解除しました。',
'unblocked-id' => 'ブロック$1を除去しました。',
'blocklist' => 'ブロックされている利用者',
'ipblocklist' => 'ブロックされている利用者',
'ipblocklist-legend' => 'ブロックされている利用者の検索',
'blocklist-userblocks' => 'アカウントのブロックを非表示',
'blocklist-tempblocks' => '期限付きブロックを非表示',
'blocklist-addressblocks' => '単一 IP のブロックを非表示',
'blocklist-rangeblocks' => '範囲ブロックを非表示',
'blocklist-timestamp' => '日時',
'blocklist-target' => '対象',
'blocklist-expiry' => '有効期限',
'blocklist-by' => 'ブロックした管理者',
'blocklist-params' => 'ブロックのパラメーター',
'blocklist-reason' => '理由',
'ipblocklist-submit' => '検索',
'ipblocklist-localblock' => 'ローカルでのブロック',
'ipblocklist-otherblocks' => 'その他の{{PLURAL:$1|ブロック}}',
'infiniteblock' => '無期限',
'expiringblock' => '$1$2に解除',
'anononlyblock' => '匿名利用者のみ',
'noautoblockblock' => '自動ブロック無効',
'createaccountblock' => 'アカウント作成の禁止',
'emailblock' => 'メール送信の禁止',
'blocklist-nousertalk' => '自分のトークページの編集禁止',
'ipblocklist-empty' => 'ブロック一覧は空です。',
'ipblocklist-no-results' => '指定されたIPアドレスまたは利用者名はブロックされていません。',
'blocklink' => 'ブロック',
'unblocklink' => 'ブロック解除',
'change-blocklink' => '設定を変更',
'contribslink' => '投稿記録',
'emaillink' => 'メールを送信',
'autoblocker' => "この IP アドレスを「[[User:$1|$1]]」が最近使用したため、自動ブロックされています。
$1 のブロックの理由は「''$2''」です。",
'blocklogpage' => 'ブロック記録',
'blocklog-showlog' => 'この利用者は以前にブロックされたことがあります。
参考のため、ブロック記録を以下に示します:',
'blocklog-showsuppresslog' => 'この利用者は以前にブロックされ、隠されたことがあります。
参考のため、秘匿記録を以下に示します:',
'blocklogentry' => 'が [[$1]] を$2ブロックしました。ブロックの詳細: $3',
'reblock-logentry' => 'が [[$1]] のブロック設定を$2に変更しました。ブロックの詳細: $3',
'blocklogtext' => 'このページは利用者のブロックと解除の記録です。
自動的にブロックされたIPアドレスは表示されていません。
現時点で有効なブロックは[[Special:BlockList|ブロックの一覧]]をご覧ください。',
'unblocklogentry' => '$1のブロックを解除しました',
'block-log-flags-anononly' => '匿名利用者のみ',
'block-log-flags-nocreate' => 'アカウント作成のブロック',
'block-log-flags-noautoblock' => '自動ブロック無効',
'block-log-flags-noemail' => 'メール送信のブロック',
'block-log-flags-nousertalk' => '自分のトークページの編集禁止',
'block-log-flags-angry-autoblock' => '拡張自動ブロック有効',
'block-log-flags-hiddenname' => '利用者名の秘匿',
'range_block_disabled' => '範囲ブロックを作成する管理者機能は無効化されています。',
'ipb_expiry_invalid' => '有効期限が無効です。',
'ipb_expiry_temp' => '利用者名秘匿のブロックは、無期限ブロックになります。',
'ipb_hide_invalid' => 'このアカウントを秘匿できません。編集回数が非常に多いためだと思われます。',
'ipb_already_blocked' => '「$1」は既にブロックされています',
'ipb-needreblock' => '$1 は既にブロックされています。設定を変更しますか?',
'ipb-otherblocks-header' => 'その他の{{PLURAL:$1|ブロック}}',
'unblock-hideuser' => '利用者名が隠されているため、この利用者のブロックを解除できません。',
'ipb_cant_unblock' => 'エラー: ブロック ID $1 が見つかりません。ブロックが既に解除されている可能性があります。',
'ipb_blocked_as_range' => 'エラー: IPアドレス$1は直接ブロックされておらず、ブロック解除できませんでした。
ただし、$2の範囲でブロックされており、こちらのブロックは別途解除できます。',
'ip_range_invalid' => 'IP範囲が無効です。',
'ip_range_toolarge' => '/$1より広範囲の範囲ブロックは許可されていません。',
'proxyblocker' => 'プロキシブロック係',
'proxyblockreason' => 'このIPアドレスは公開プロキシであるためブロックされています。
ご使用中のインターネットサービスプロバイダーまたは所属組織の技術担当者に連絡して、これが深刻なセキュリティ問題であることを伝えてください。',
'sorbs' => 'DNSBL',
'sorbsreason' => 'ご使用中のIPアドレスが、{{SITENAME}}の使用しているDNSBLに公開プロキシとして記載されています。',
'sorbs_create_account_reason' => 'ご使用中のIPアドレスが、{{SITENAME}}の使用しているDNSBLに公開プロキシとして記載されています。
アカウント作成はできません',
'xffblockreason' => 'X-Forwarded-For ヘッダーに含まれている IP アドレスがブロックされています。これはあなたのものか、あなたが利用しているプロキシサーバーのものです。元のブロックの理由は: $1',
'cant-block-while-blocked' => 'ブロックされている間は、他の利用者をブロックできません。',
'cant-see-hidden-user' => 'ブロックしようとしている利用者は、既にブロックされ隠されています。
あなたには hideuser 権限がないため、この利用者のブロックの閲覧/編集はできません。',
'ipbblocked' => '自分自身をブロックしているため、他の利用者のブロックやブロック解除はできません',
'ipbnounblockself' => '自分自身のブロックは解除できません',

# Developer tools
'lockdb' => 'データベースのロック',
'unlockdb' => 'データベースのロック解除',
'lockdbtext' => 'データベースをロックするとすべての利用者はページの編集や、個人設定の変更、ウォッチリストの編集、その他データベースでの変更を要求する作業ができなくなります。
本当にデータベースをロックしていいかどうか確認し、メンテナンスが終了したらロックを解除してください。',
'unlockdbtext' => 'データベースのロックを解除すると、すべての利用者がページの編集や、個人設定の変更、ウォッチリストの編集、その他データベースでの変更を要求する作業ができるようになります。
本当にデータベースのロックを解除していいかどうか確認してください。',
'lockconfirm' => '本当にデータベースをロックします。',
'unlockconfirm' => '本当にデータベースのロックを解除します。',
'lockbtn' => 'データベースをロック',
'unlockbtn' => 'データベースのロックを解除',
'locknoconfirm' => '確認ボックスにチェックが入っていません。',
'lockdbsuccesssub' => 'データベースのロック',
'unlockdbsuccesssub' => 'データベースのロック除去',
'lockdbsuccesstext' => 'データベースをロックしました。<br />
メンテナンスが完了したら、忘れずに[[Special:UnlockDB|ロックを除去]]してください。',
'unlockdbsuccesstext' => 'データベースのロックを解除しました。',
'lockfilenotwritable' => 'データベースのロック ファイルが書き込み禁止です。
データベースをロックまたはロック解除するには、ウェブ サーバーがこれに書き込める必要があります。',
'databasenotlocked' => 'データベースはロックされていません。',
'lockedbyandtime' => '($1 が $2 $3 から)',

# Move page
'move-page' => '「$1」の移動',
'move-page-legend' => 'ページの移動',
'movepagetext' => "下のフォームを使用すると、ページ名を変更でき、そのページの履歴も変更先に移動できます。
移動元のページは移動先への転送ページになります。
移動元のページへの転送ページを自動的に修正できます。
[[Special:DoubleRedirects|二重転送]]や[[Special:BrokenRedirects|迷子のリダイレクト]]を確認する必要があります。
リンクを正しく維持するのは移動した人の責任です。

移動先のページが既に存在する場合は、その移動先が転送ページであり、かつ過去の版を持たない場合以外は移動'''できません'''。
つまり、間違えてページ名を変更した場合には元に戻せます。また移動によって既存のページを上書きしてしまうことはありません。

'''注意!'''
よく閲覧されるページや、他の多くのページからリンクされているページを移動すると予期しない結果が起こるかもしれません。
ページの移動に伴う影響をよく考えてから踏み切るようにしてください。",
'movepagetext-noredirectfixer' => "下のフォームを使用すると、ページ名を変更でき、そのページの履歴も変更先に移動できます。
移動元のページは移動先への転送ページになります。
自動的な修正を選択しない場合は、[[Special:DoubleRedirects|二重転送]]や[[Special:BrokenRedirects|迷子のリダイレクト]]を確認する必要があります。
つながるべき場所にリンクがつながるよう維持するのは移動した人の責任です。

移動先が既に存在する場合は、そのページが転送ページであり、かつ過去の版を持たない場合を除いて移動'''できません'''。
つまり、間違えてページ名を変更した場合には元に戻せます。また移動によって既存のページを上書きしてしまうことはありません。

'''警告!'''
多く閲覧されるページや多くリンクされているページを移動すると、予期しない大きな変化が起こるかもしれないことにご注意ください。
ページの移動に伴う影響をよく考えてから移動してください。",
'movepagetalktext' => "関連付けられたトークページも一緒に、自動的に移動されます。ただし、'''以下の場合を除きます:'''
* 移動先に、空ではないトークページが既に存在する場合
* 下のボックスのチェックを消した場合

これらの場合、必要に応じて、トークページを移動または統合する必要があります。",
'movearticle' => '移動するページ:',
'moveuserpage-warning' => "'''警告:''' 利用者ページを移動しようとしています。この操作ではページのみが移動され、利用者名は''変更されない''点に注意してください。",
'movenologin' => 'ログインしていません',
'movenologintext' => 'ページを移動するためには、登録利用者でありかつ、[[Special:UserLogin|ログイン]]している必要があります。',
'movenotallowed' => 'ページを移動する権限がありません。',
'movenotallowedfile' => 'ファイルを移動する権限がありません。',
'cant-move-user-page' => '利用者ページを移動させる権限がありません (下位ページ内を除く)。',
'cant-move-to-user-page' => '利用者下位ページ以外の利用者ページに、ページを移動させる権限がありません。',
'newtitle' => '新しいページ名:',
'move-watch' => '移動元と移動先ページをウォッチ',
'movepagebtn' => 'ページを移動',
'pagemovedsub' => '移動に成功しました',
'movepage-moved' => "'''「$1」は「$2」へ移動されました'''",
'movepage-moved-redirect' => '転送ページを作成しました。',
'movepage-moved-noredirect' => '転送ページは作成されませんでした。',
'articleexists' => '指定された移動先には既にページが存在するか、名前が不適切です。
別の名前を選択してください。',
'cantmove-titleprotected' => '新しいページ名が作成保護されているため、この場所にページを移動できません',
'talkexists' => "'''ページ自身は移動できましたが、トークページは移動先のページが存在したため移動できませんでした。
手動で統合してください。'''",
'movedto' => '移動先:',
'movetalk' => '付随するトークページも移動',
'move-subpages' => '下位ページも移動 ($1 件まで)',
'move-talk-subpages' => 'トークページの下位ページも移動 ($1 件まで)',
'movepage-page-exists' => 'ページ $1 は既に存在するため、自動的な上書きはできません。',
'movepage-page-moved' => 'ページ $1 は $2 に移動しました。',
'movepage-page-unmoved' => 'ページ $1 は $2 に移動できませんでした。',
'movepage-max-pages' => '自動的に移動できるのは $1 {{PLURAL:$1|ページ}}までで、それ以上は移動されません。',
'movelogpage' => '移動記録',
'movelogpagetext' => '以下はすべてのページ移動の一覧です。',
'movesubpage' => '{{PLURAL:$1|下位ページ}}',
'movesubpagetext' => 'このページには、以下の $1 {{PLURAL:$1|下位ページ}}があります。',
'movenosubpage' => 'このページに下位ページはありません。',
'movereason' => '理由:',
'revertmove' => '差し戻し',
'delete_and_move' => '削除して移動',
'delete_and_move_text' => '== 削除が必要です ==
移動先「[[:$1]]」は既に存在します。
移動のためにこのページを削除しますか?',
'delete_and_move_confirm' => 'はい、ページを削除します',
'delete_and_move_reason' => '「[[$1]]」からの移動のために削除',
'selfmove' => '移動元と移動先のページ名が同じです。
自分自身には移動できません。',
'immobile-source-namespace' => '「$1」名前空間のページは移動できません',
'immobile-target-namespace' => '「$1」名前空間にはページを移動できません',
'immobile-target-namespace-iw' => 'ウィキ間リンクは、ページの移動先には指定できません。',
'immobile-source-page' => 'このページは移動できません。',
'immobile-target-page' => '指定したページ名には移動できません。',
'bad-target-model' => '指定した移動先では、異なるコンテンツ モデルを使用しています。$1から$2には変換できません。',
'imagenocrossnamespace' => 'ファイルを、ファイル名前空間以外に移動させることはできません',
'nonfile-cannot-move-to-file' => 'ファイル以外のものを、ファイル名前空間に移動させることはできません',
'imagetypemismatch' => '新しいファイルの拡張子がファイルのタイプと一致していません',
'imageinvalidfilename' => '対象ファイル名が無効です',
'fix-double-redirects' => 'このページへのリダイレクトがあればそのリダイレクトを修正',
'move-leave-redirect' => '移動元に転送ページを作成する',
'protectedpagemovewarning' => "'''警告:''' このページは保護されているため、管理者権限を持つ利用者のみが移動できます。
参考として以下に最後の記録を表示します:",
'semiprotectedpagemovewarning' => "'''注意:''' このページは保護されているため、登録利用者のみが移動できます。
参考として以下に最後の記録を表示します:",
'move-over-sharedrepo' => '== ファイルが存在します ==
[[:$1]]は共有リポジトリ上に存在します。ファイルをこの名前に移動すると共有ファイルを上書きします。',
'file-exists-sharedrepo' => '選ばれたファイル名は既に共有リポジトリ上で使用されています。
別の名前を選んでください。',

# Export
'export' => 'ページの書き出し',
'exporttext' => 'ここでは単独あるいは複数のページの本文と編集履歴を、XMLの形で書き出しができます。
このXMLは、他のMediaWikiを使用しているウィキで[[Special:Import|取り込みページ]]を使用して取り込みができます。

ページを書き出すには、下の入力ボックスに一行に一つずつ書き出したいページの名前を記入してください。また、編集履歴とともにすべての過去版を含めて書き出すのか、最新版のみを書き出すのか選択してください。

後者の場合ではリンクの形で使うこともできます。例えば、[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]はページ「[[{{MediaWiki:Mainpage}}]]」が対象になります。',
'exportall' => 'すべてのページを書き出し',
'exportcuronly' => '完全な履歴は含めず、最新版のみを含める',
'exportnohistory' => "----
'''注意:''' 処理能力上の理由により、このフォームによるページの完全な履歴の書き出しは無効化されています。",
'exportlistauthors' => '各ページの投稿者の完全な一覧を含める',
'export-submit' => '書き出し',
'export-addcattext' => '指定したカテゴリ内のページを追加:',
'export-addcat' => '追加',
'export-addnstext' => '指定した名前空間内のページを追加:',
'export-addns' => '追加',
'export-download' => 'ファイルとして保存',
'export-templates' => 'テンプレートを含める',
'export-pagelinks' => '以下の階層までのリンク先ページを含める:',

# Namespace 8 related
'allmessages' => 'システムメッセージの一覧',
'allmessagesname' => '名前',
'allmessagesdefault' => '既定のメッセージ文',
'allmessagescurrent' => '現在のメッセージ文',
'allmessagestext' => 'これは MediaWiki 名前空間で利用できるシステム メッセージの一覧です。
MediaWiki 全般のローカライズ (地域化) に貢献したい場合は、[//www.mediawiki.org/wiki/Localisation/ja MediaWiki のローカライズ]や [//translatewiki.net?setlang=ja translatewiki.net] をご覧ください。',
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages'''が無効のため、このページを使用できません。",
'allmessages-filter-legend' => '絞り込み',
'allmessages-filter' => '変更状態により絞り込む:',
'allmessages-filter-unmodified' => '変更なし',
'allmessages-filter-all' => 'すべて',
'allmessages-filter-modified' => '変更あり',
'allmessages-prefix' => '名前の先頭部分で絞り込む:',
'allmessages-language' => '言語:',
'allmessages-filter-submit' => '表示',

# Thumbnails
'thumbnail-more' => '拡大',
'filemissing' => 'ファイルがありません',
'thumbnail_error' => 'サムネイルの作成エラー: $1',
'thumbnail_error_remote' => '$1が返したエラーメッセージ:
$2',
'djvu_page_error' => 'DjVuページが範囲外です',
'djvu_no_xml' => 'DjVuファイルのXMLデータを取得できません',
'thumbnail-temp-create' => '一時的なサムネイルファイルを作成できません',
'thumbnail-dest-create' => 'サムネイルを保存先に保存できません',
'thumbnail_invalid_params' => 'サムネイル引数が無効です',
'thumbnail_dest_directory' => '出力ディレクトリを作成できません',
'thumbnail_image-type' => '対応していない画像形式です',
'thumbnail_gd-library' => 'GDライブラリの構成が不完全です: 関数$1が不足',
'thumbnail_image-missing' => 'ファイルが見つかりません: $1',

# Special:Import
'import' => 'ページデータの取り込み',
'importinterwiki' => 'ウィキ間移動の取り込み',
'import-interwiki-text' => '取り込むウィキとページ名を選択してください。
版の日付と編集者名は保持されます。
ウィキ間移動のすべての取り込み操作は[[Special:Log/import|取り込み記録]]に記録されます。',
'import-interwiki-source' => '取り込み元のウィキ/ページ:',
'import-interwiki-history' => 'このページのすべての版を複製する',
'import-interwiki-templates' => 'すべてのテンプレートを含める',
'import-interwiki-submit' => '取り込み',
'import-interwiki-namespace' => '取り込み先の名前空間:',
'import-interwiki-rootpage' => '取り込み先のルートページ (省略可能):',
'import-upload-filename' => 'ファイル名:',
'import-comment' => 'コメント:',
'importtext' => '元のウィキで[[Special:Export|書き出し機能]]を使用してファイルに書き出してください。
それをコンピューターに保存した後、こちらへアップロードしてください。',
'importstart' => 'ページを取り込み中...',
'import-revision-count' => '$1{{PLURAL:$1|版}}',
'importnopages' => '取り込むページがありません。',
'imported-log-entries' => '$1 件の{{PLURAL:$1|記録項目}}を取り込みました。',
'importfailed' => '取り込みに失敗しました: <nowiki>$1</nowiki>',
'importunknownsource' => '取り込み元のタイプが不明です',
'importcantopen' => '取り込みファイルが開けませんでした',
'importbadinterwiki' => 'ウィキ間リンクが正しくありません',
'importnotext' => '内容が空、または本文がありません',
'importsuccess' => '取り込みが完了しました!',
'importhistoryconflict' => '取り込み時にいくつかの版が競合しました (以前に同じページが取り込まれているかもしれません)',
'importnosources' => 'ウィキ間移動の取り込み元が定義されていないため、履歴の直接アップロードは無効になっています。',
'importnofile' => '取り込みファイルはアップロードされませんでした。',
'importuploaderrorsize' => '取り込みファイルのアップロードに失敗しました。
ファイルが、アップロードできるサイズを超えています。',
'importuploaderrorpartial' => '取り込みファイルのアップロードに失敗しました。
ファイルの一部のみアップロードされました。',
'importuploaderrortemp' => '取り込みファイルのアップロードに失敗しました。
一時フォルダーがありません。',
'import-parse-failure' => 'XML取り込みの構文解析に失敗しました',
'import-noarticle' => '取り込むページがありません!',
'import-nonewrevisions' => 'すべての版は以前に取り込み済みです。',
'xml-error-string' => '$1、$2 行の $3 文字目 ($4バイト目): $5',
'import-upload' => 'XMLデータをアップロード',
'import-token-mismatch' => 'セッションデータを損失しました。
もう一度試してください。',
'import-invalid-interwiki' => '指定されたウィキから取り込めませんでした。',
'import-error-edit' => 'あなたにそのページを編集する許可がないため、ページ「$1」は取り込まれませんでした。',
'import-error-create' => 'あなたにそのページを作成する許可がないため、ページ「$1」は取り込まれませんでした。',
'import-error-interwiki' => 'ページ名が外部リンク (ウィキ間リンク) に予約されているため、ページ「$1」を取り込みませんでした。',
'import-error-special' => 'ページ「$1」は、ページが許可されない特別名前空間に属しているため取り込みません。',
'import-error-invalid' => '名前が正しくないため、ページ「$1」を取り込みませんでした。',
'import-error-unserialize' => 'ページ「$1」の版 $2 はシリアライズ解除できませんでした。この版は $4 としてシリアライズされたコンテンツモデル $3 を使用していると報告されています。',
'import-options-wrong' => '間違った{{PLURAL:$2|オプション}}です: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => '入力したルートページの名前は無効です。',
'import-rootpage-nosubpage' => 'ルートページの名前空間「$1」では、下位ページが許可されていません。',

# Import log
'importlogpage' => '取り込み記録',
'importlogpagetext' => '管理された他のウィキから編集履歴を伴ったページ取り込みです。',
'import-logentry-upload' => 'ファイルのアップロードにより[[$1]]を取り込みました',
'import-logentry-upload-detail' => '$1{{PLURAL:$1|版}}',
'import-logentry-interwiki' => '$1をウィキ間移動しました',
'import-logentry-interwiki-detail' => '$2の$1{{PLURAL:$1|版}}',

# JavaScriptTest
'javascripttest' => 'JavaScript をテスト中',
'javascripttest-title' => '$1 のテストの実行',
'javascripttest-pagetext-noframework' => 'このページは JavaScript のテストを実行するために予約されています。',
'javascripttest-pagetext-unknownframework' => 'テストフレームワーク「$1」は不明です。',
'javascripttest-pagetext-frameworks' => '以下のテストフレームワークから1つ選択してください: $1',
'javascripttest-pagetext-skins' => 'テストを実行する外装を選択してください:',
'javascripttest-qunit-intro' => 'mediawiki.org上の[$1 テストのドキュメント]を参照してください。',
'javascripttest-qunit-heading' => 'MediaWiki JavaScript QUnit テストスイート',

# Tooltip help for the actions
'tooltip-pt-userpage' => '自分の利用者ページ',
'tooltip-pt-anonuserpage' => '自分が編集しているIPアドレスの利用者ページ',
'tooltip-pt-mytalk' => '自分のトークページ',
'tooltip-pt-anontalk' => 'このIPアドレスからなされた編集についての議論',
'tooltip-pt-preferences' => '個人設定',
'tooltip-pt-watchlist' => '変更をウォッチしているページの一覧',
'tooltip-pt-mycontris' => '自分の投稿の一覧',
'tooltip-pt-login' => 'ログインすることを推奨します。ただし、必須ではありません。',
'tooltip-pt-anonlogin' => 'ログインすることを推奨します。ただし、必須ではありません。',
'tooltip-pt-logout' => 'ログアウト',
'tooltip-ca-talk' => '本文ページについての議論',
'tooltip-ca-edit' => 'このページを編集できます。保存前にプレビューボタンを使用してください。',
'tooltip-ca-addsection' => '新しい節を開始する',
'tooltip-ca-viewsource' => 'このページは保護されています。
ページのソースを閲覧できます。',
'tooltip-ca-history' => 'このページの過去の版',
'tooltip-ca-protect' => 'このページを保護する',
'tooltip-ca-unprotect' => 'このページの保護設定を変更する',
'tooltip-ca-delete' => 'このページを削除する',
'tooltip-ca-undelete' => '削除される前になされた編集を復元する',
'tooltip-ca-move' => 'このページを移動する',
'tooltip-ca-watch' => 'このページをウォッチリストに追加する',
'tooltip-ca-unwatch' => 'このページをウォッチリストから除去する',
'tooltip-search' => '{{SITENAME}}内を検索する',
'tooltip-search-go' => '厳密に一致する名前のページが存在すれば、そのページへ移動する',
'tooltip-search-fulltext' => 'この文字列が含まれるページを探す',
'tooltip-p-logo' => 'メインページに移動する',
'tooltip-n-mainpage' => 'メインページに移動する',
'tooltip-n-mainpage-description' => 'メインページに移動する',
'tooltip-n-portal' => 'このプロジェクトについて、できること、情報を入手する場所',
'tooltip-n-currentevents' => '最近の出来事の背景を知る',
'tooltip-n-recentchanges' => 'このウィキにおける最近の更新の一覧',
'tooltip-n-randompage' => '無作為に選択されたページを読み込む',
'tooltip-n-help' => '情報を得る場所',
'tooltip-t-whatlinkshere' => 'ここにリンクしている全ウィキページの一覧',
'tooltip-t-recentchangeslinked' => 'このページからリンクしているページの最近の更新',
'tooltip-feed-rss' => 'このページのRSSフィード',
'tooltip-feed-atom' => 'このページのAtomフィード',
'tooltip-t-contributions' => 'この利用者の投稿の一覧',
'tooltip-t-emailuser' => 'この利用者にメールを送信する',
'tooltip-t-upload' => 'ファイルをアップロードする',
'tooltip-t-specialpages' => '特別ページの一覧',
'tooltip-t-print' => 'このページの印刷用ページ',
'tooltip-t-permalink' => 'このページのこの版への固定リンク',
'tooltip-ca-nstab-main' => '本文を閲覧',
'tooltip-ca-nstab-user' => '利用者ページを表示',
'tooltip-ca-nstab-media' => 'メディアページを表示',
'tooltip-ca-nstab-special' => 'これは特別ページです。編集はできません。',
'tooltip-ca-nstab-project' => 'プロジェクトページを表示',
'tooltip-ca-nstab-image' => 'ファイルページを表示',
'tooltip-ca-nstab-mediawiki' => 'システムメッセージを表示',
'tooltip-ca-nstab-template' => 'テンプレートを表示',
'tooltip-ca-nstab-help' => 'ヘルプページを表示',
'tooltip-ca-nstab-category' => 'カテゴリページを閲覧',
'tooltip-minoredit' => 'この編集に細部の変更の印を付ける',
'tooltip-save' => '変更を保存する',
'tooltip-preview' => '変更内容をプレビューで確認できます。保存前に使用してください!',
'tooltip-diff' => '文章への変更箇所を表示する',
'tooltip-compareselectedversions' => '選択した2つの版の差分を表示する',
'tooltip-watch' => 'このページをウォッチリストに追加する',
'tooltip-watchlistedit-normal-submit' => 'ページを除去する',
'tooltip-watchlistedit-raw-submit' => 'ウォッチリストを更新する',
'tooltip-recreate' => '削除されていても、ページを再作成する',
'tooltip-upload' => 'アップロードを開始する',
'tooltip-rollback' => '「巻き戻し」は最後の編集者によるこのページの複数の編集を1クリックで差し戻します',
'tooltip-undo' => '「取り消し」はこの編集を差し戻し、編集画面をプレビュー付きで開きます。要約欄に理由を追加できます。',
'tooltip-preferences-save' => '設定を保存する',
'tooltip-summary' => '短い要約を入力してください',

# Stylesheets
'common.css' => '/* ここに記述したCSSはすべての外装に反映されます */',
'cologneblue.css' => '/* ここに記述したCSSはケルンブルー外装の利用者に影響します */',
'monobook.css' => '/* ここに記述したCSSはモノブック外装の利用者に影響します */',
'modern.css' => '/* ここに記述したCSSはモダン外装の利用者に影響します */',
'vector.css' => '/* ここに記述したCSSはベクター外装の利用者に影響します */',
'print.css' => '/* ここに記述したCSSは印刷出力に影響します */',
'noscript.css' => '/* ここに記述したCSSはJavaScriptを無効にしている利用者に影響します */',
'group-autoconfirmed.css' => '/* ここに記述したCSSは自動承認された利用者のみに影響します */',
'group-bot.css' => '/* ここに記述したCSSはボットのみに影響します */',
'group-sysop.css' => '/* ここに記述したCSSは管理者のみに影響します */',
'group-bureaucrat.css' => '/* ここに記述したCSSはビューロクラットのみに影響します */',

# Scripts
'common.js' => '/* ここにあるすべてのJavaScriptは、すべてのページ読み込みですべての利用者に対して読み込まれます */',
'cologneblue.js' => '/* ここにあるすべてのJavaScriptは、ケルンブルー外装を使用している利用者に対して読み込まれます */',
'monobook.js' => '/* ここにあるすべてのJavaScriptは、モノブック外装を使用している利用者に対して読み込まれます */',
'modern.js' => '/* ここにあるすべてのJavaScriptは、モダン外装を使用している利用者に対して読み込まれます */',
'vector.js' => '/* ここにあるすべてのJavaScriptは、ベクター外装を使用している利用者に対して読み込まれます */',
'group-autoconfirmed.js' => '/* ここにあるすべてのJavaScriptは、自動承認された利用者に対して読み込まれます */',
'group-bot.js' => '/* ここにあるすべてのJavaScriptは、ボットのみに読み込まれます */',
'group-sysop.js' => '/* ここにあるすべてのJavaScriptは、管理者のみに読み込まれます */',
'group-bureaucrat.js' => '/* ここにあるすべてのJavaScriptは、ビューロクラットのみに読み込まれます */',

# Metadata
'notacceptable' => 'ウィキサーバーは、ご使用中のクライアントが読める形式では情報を提供できません。',

# Attribution
'anonymous' => '{{SITENAME}}の匿名{{PLURAL:$1|利用者}}',
'siteuser' => '{{SITENAME}}の利用者 $1',
'anonuser' => '{{SITENAME}}の匿名利用者 $1',
'lastmodifiedatby' => 'このページの最終更新は $1 $2 に $3 によって行われました。',
'othercontribs' => 'また、最終更新より前に $1 が編集しました。',
'others' => 'その他',
'siteusers' => '{{SITENAME}}の{{PLURAL:$2|利用者}} $1',
'anonusers' => '{{SITENAME}}の匿名{{PLURAL:$2|利用者}} $1',
'creditspage' => 'ページの帰属表示',
'nocredits' => 'このページに対する帰属情報がありません。',

# Spam protection
'spamprotectiontitle' => 'スパム防御フィルター',
'spamprotectiontext' => '保存しようとした文章はスパムフィルターによってブロックされました。
これはおそらく、ブラックリストにある外部サイトへのリンクが原因で発生します。',
'spamprotectionmatch' => '以下の文章はスパムフィルターが発動したものです: $1',
'spambot_username' => 'MediaWikiスパム除去',
'spam_reverting' => '$1へのリンクを含まない最新の版に差し戻し',
'spam_blanking' => 'すべての版が$1へのリンクを含んでいます。白紙化します。',
'spam_deleting' => 'すべての版が$1へのリンクを含んでいます。削除します。',

# Info page
'pageinfo-title' => '「$1」の情報',
'pageinfo-not-current' => '申し訳ありませんが、過去の版の情報は表示できません。',
'pageinfo-header-basic' => '基本情報',
'pageinfo-header-edits' => '編集履歴',
'pageinfo-header-restrictions' => 'ページの保護',
'pageinfo-header-properties' => 'ページのプロパティ',
'pageinfo-display-title' => '表示されるページ名',
'pageinfo-default-sort' => '既定のソートキー',
'pageinfo-length' => 'ページの長さ (バイト単位)',
'pageinfo-article-id' => 'ページ ID',
'pageinfo-language' => 'ページ本文の言語',
'pageinfo-robot-policy' => 'ロボットによるインデックス作成',
'pageinfo-robot-index' => '許可',
'pageinfo-robot-noindex' => '不許可',
'pageinfo-views' => '閲覧回数',
'pageinfo-watchers' => 'ページをウォッチリストに入れている人数',
'pageinfo-few-watchers' => 'ウォッチしている利用者 $1 {{PLURAL:$1|人未満}}',
'pageinfo-redirects-name' => 'このページへのリダイレクトの数',
'pageinfo-redirects-value' => '$1',
'pageinfo-subpages-name' => 'このページの下位ページの数',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|件のリダイレクト}}、$3 {{PLURAL:$3|件の非リダイレクト}})',
'pageinfo-firstuser' => 'ページの作成者',
'pageinfo-firsttime' => 'ページの作成日時',
'pageinfo-lastuser' => '最終編集者',
'pageinfo-lasttime' => '最終編集日時',
'pageinfo-edits' => '総編集回数',
'pageinfo-authors' => '総投稿者数',
'pageinfo-recent-edits' => '最近の編集回数 (過去 $1)',
'pageinfo-recent-authors' => '最近の投稿者数',
'pageinfo-magic-words' => 'マジック {{PLURAL:$1|ワード}} ($1)',
'pageinfo-hidden-categories' => '隠し{{PLURAL:$1|カテゴリ}} ($1)',
'pageinfo-templates' => 'このページが参照読み込みしている{{PLURAL:$1|テンプレート}} ($1)',
'pageinfo-transclusions' => 'このページを参照読み込みしている{{PLURAL:$1|ページ}} ($1)',
'pageinfo-toolboxlink' => 'ページ情報',
'pageinfo-redirectsto' => '転送先',
'pageinfo-redirectsto-info' => '情報',
'pageinfo-contentpage' => '本文ページとして数える',
'pageinfo-contentpage-yes' => 'はい',
'pageinfo-protect-cascading' => 'カスケード保護されている',
'pageinfo-protect-cascading-yes' => 'はい',
'pageinfo-protect-cascading-from' => 'カスケード保護の起点',
'pageinfo-category-info' => 'カテゴリ情報',
'pageinfo-category-pages' => 'ページ数',
'pageinfo-category-subcats' => '下位カテゴリ数',
'pageinfo-category-files' => 'ファイル数',

# Skin names
'skinname-cologneblue' => 'ケルンブルー',
'skinname-monobook' => 'モノブック',
'skinname-modern' => 'モダン',
'skinname-vector' => 'ベクター',

# Patrolling
'markaspatrolleddiff' => '巡回済みにする',
'markaspatrolledtext' => 'このページを巡回済みにする',
'markedaspatrolled' => '巡回済みにしました',
'markedaspatrolledtext' => '[[:$1]]の、選択した版を巡回済みにしました。',
'rcpatroldisabled' => '最近の更新の巡回は無効です',
'rcpatroldisabledtext' => '最近の更新の巡回機能は現在無効になっています。',
'markedaspatrollederror' => '巡回済みにできません',
'markedaspatrollederrortext' => '巡回済みにするには、版を指定する必要があります。',
'markedaspatrollederror-noautopatrol' => 'あなたには自分の編集を巡回済みにする権限がありません。',
'markedaspatrollednotify' => '$1 へのこの変更を巡回済みにしました。',
'markedaspatrollederrornotify' => '巡回済みにするのに失敗しました。',

# Patrol log
'patrol-log-page' => '巡回記録',
'patrol-log-header' => '以下は巡回された版の記録です。',
'log-show-hide-patrol' => '巡回記録を$1',

# Image deletion
'deletedrevision' => '古い版 $1 を削除しました',
'filedeleteerror-short' => 'ファイルの削除エラー: $1',
'filedeleteerror-long' => 'ファイルの削除中にエラーが発生しました:

$1',
'filedelete-missing' => 'ファイル「$1」は存在しないため、削除できません。',
'filedelete-old-unregistered' => '指定されたファイルの版「$1」はデータベース内にありません。',
'filedelete-current-unregistered' => '指定されたファイル「$1」はデータベース内にありません。',
'filedelete-archive-read-only' => '保存版ディレクトリ「$1」は、ウェブサーバーから書き込み不可になっています。',

# Browsing diffs
'previousdiff' => '←古い編集',
'nextdiff' => '新しい編集→',

# Media information
'mediawarning' => "'''警告:''' この種類のファイルは、悪意があるコードを含んでいる可能性があります。
実行するとシステムが危険にさらされるおそれがあります。",
'imagemaxsize' => "画像のサイズ制限: <br />''(ファイルページに対する)''",
'thumbsize' => 'サムネイルの大きさ:',
'widthheight' => '$1 × $2',
'widthheightpage' => '$1 × $2、$3 {{PLURAL:$3|ページ}}',
'file-info' => 'ファイルサイズ: $1、MIMEタイプ: $2',
'file-info-size' => '$1 × $2 ピクセル、ファイルサイズ: $3、MIME タイプ: $4',
'file-info-size-pages' => '$1 × $2 ピクセル、ファイルサイズ: $3、MIMEタイプ: $4、$5 {{PLURAL:$5|ページ}}',
'file-nohires' => '高解像度版はありません。',
'svg-long-desc' => 'SVG ファイル、$1 × $2 ピクセル、ファイルサイズ: $3',
'svg-long-desc-animated' => 'アニメーション SVG ファイル、$1 × $2 ピクセル、ファイルサイズ: $3',
'svg-long-error' => '無効な SVG ファイル: $1',
'show-big-image' => '高解像度での画像',
'show-big-image-preview' => 'このプレビューのサイズ: $1。',
'show-big-image-other' => 'その他の{{PLURAL:$2|解像度}}: $1。',
'show-big-image-size' => '$1 × $2 ピクセル',
'file-info-gif-looped' => 'ループします',
'file-info-gif-frames' => '$1 {{PLURAL:$1|フレーム}}',
'file-info-png-looped' => '繰り返し',
'file-info-png-repeat' => '$1 {{PLURAL:$1|回再生しました}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|フレーム}}',
'file-no-thumb-animation' => "'''注意: 技術的な制限により、このファイルのサムネイルはアニメーションされません。'''",
'file-no-thumb-animation-gif' => "'''注意: 技術的な制限により、この画像のような高解像度の GIF 画像の、サムネイルはアニメーションされません。'''",

# Special:NewFiles
'newimages' => '新しいファイルのギャラリー',
'imagelisttext' => "以下は、'''$1'''{{PLURAL:$1|ファイル}}の$2で並べ替えた一覧です。",
'newimages-summary' => 'この特別ページでは、最近アップロードされたファイルを表示します。',
'newimages-legend' => '絞り込み',
'newimages-label' => 'ファイル名 (またはその一部):',
'showhidebots' => '(ボットを$1)',
'noimages' => '表示できるものがありません。',
'ilsubmit' => '検索',
'bydate' => '日付順',
'sp-newimages-showfrom' => '$1の$2以降の新しいファイルを表示',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims' => '$1、 $2 × $3',
'seconds-abbrev' => '$1 s',
'minutes-abbrev' => '$1 m',
'hours-abbrev' => '$1 h',
'days-abbrev' => '$1 d',
'seconds' => '{{PLURAL:$1|$1 秒}}',
'minutes' => '{{PLURAL:$1|$1 分}}',
'hours' => '{{PLURAL:$1|$1 時間}}',
'days' => '{{PLURAL:$1|$1 日}}',
'weeks' => '{{PLURAL:$1|$1 週間}}',
'months' => '{{PLURAL:$1|$1 か月}}',
'years' => '{{PLURAL:$1|$1 年}}',
'ago' => '$1前',
'just-now' => 'ちょうど今',

# Human-readable timestamps
'hours-ago' => '$1 {{PLURAL:$1|時間}}前',
'minutes-ago' => '$1 {{PLURAL:$1|分}}前',
'seconds-ago' => '$1 {{PLURAL:$1|秒}}前',
'monday-at' => '月曜日 $1',
'tuesday-at' => '火曜日 $1',
'wednesday-at' => '水曜日 $1',
'thursday-at' => '木曜日 $1',
'friday-at' => '金曜日 $1',
'saturday-at' => '土曜日 $1',
'sunday-at' => '日曜日 $1',
'yesterday-at' => '昨日 $1',

# Bad image list
'bad_image_list' => '書式は以下の通りです:

箇条書き項目 (*で始まる行) のみが考慮されます。
各行の最初のリンクは、好ましくないファイルへのリンクにしてください。
同じ行のそれ以降にあるリンクは例外、つまりインライン挿入されてもいいページと見なされます。',

/*
Short names for language variants used for language conversion links.
Variants for Chinese language
*/
'variantname-zh-hans' => '中国語 (簡体)',
'variantname-zh-hant' => '中国語 (繁体)',
'variantname-zh-cn' => '中国簡体',
'variantname-zh-tw' => '台湾正体',
'variantname-zh-hk' => '香港正体',
'variantname-zh-mo' => 'マカオ繁体',
'variantname-zh-sg' => 'シンガポール簡体',
'variantname-zh-my' => 'マレーシア簡体',
'variantname-zh' => '中国語',

# Variants for Gan language
'variantname-gan-hans' => 'カン語 (簡体)',
'variantname-gan-hant' => 'カン語 (繁体)',
'variantname-gan' => 'カン語',

# Variants for Serbian language
'variantname-sr-ec' => 'セルビア語 (キリル文字)',
'variantname-sr-el' => 'セルビア語 (ラテン文字)',
'variantname-sr' => 'セルビア語',

# Variants for Kazakh language
'variantname-kk-kz' => 'カザフ語 (カザフスタン)',
'variantname-kk-tr' => 'カザフ語 (トルコ)',
'variantname-kk-cn' => 'カザフ語 (中国)',
'variantname-kk-cyrl' => 'カザフ語 (キリル文字)',
'variantname-kk-latn' => 'カザフ語 (ラテン文字)',
'variantname-kk-arab' => 'カザフ語 (アラビア文字)',
'variantname-kk' => 'カザフ語',

# Variants for Kurdish language
'variantname-ku-arab' => 'クルド語 (アラビア文字)',
'variantname-ku-latn' => 'クルド語 (ラテン文字)',
'variantname-ku' => 'クルド語',

# Variants for Tajiki language
'variantname-tg-cyrl' => 'タジク語 (キリル文字)',
'variantname-tg-latn' => 'タジク語 (ラテン文字)',
'variantname-tg' => 'タジク語',

# Variants for Inuktitut language
'variantname-ike-cans' => 'イヌクティトゥット語 (カナダ先住民文字)',
'variantname-ike-latn' => 'イヌクティトゥット語 (ラテン文字)',
'variantname-iu' => 'イヌクティトゥット語',

# Variants for Tachelhit language
'variantname-shi-tfng' => 'シルハ語 (ティフィナグ文字)',
'variantname-shi-latn' => 'シルハ語 (ラテン文字)',
'variantname-shi' => 'シルハ語',

# Metadata
'metadata' => 'メタデータ',
'metadata-help' => 'このファイルには、追加情報があります (おそらく、作成やデジタル化する際に使用したデジタルカメラやスキャナーが追加したものです)。
このファイルが元の状態から変更されている場合、修正されたファイルを完全に反映していない項目がある場合があります。',
'metadata-expand' => '拡張項目を表示',
'metadata-collapse' => '拡張項目を非表示',
'metadata-fields' => 'このメッセージで列挙している画像メタデータフィールドは、メタデータ表を折り畳んだ状態のときに画像ページに読み込まれます。
他のものは既定では非表示です。
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

# Exif tags
'exif-imagewidth' => '画像の幅',
'exif-imagelength' => '画像の高さ',
'exif-bitspersample' => '画像のビットの深さ',
'exif-compression' => '圧縮の種類',
'exif-photometricinterpretation' => '画素構成',
'exif-orientation' => '画像方向',
'exif-samplesperpixel' => 'コンポーネント数',
'exif-planarconfiguration' => '画像データの並び',
'exif-ycbcrsubsampling' => 'YCCの画素構成 (Cの間引き率)',
'exif-ycbcrpositioning' => 'YCCの画素構成 (YとCの位置)',
'exif-xresolution' => '水平方向の解像度',
'exif-yresolution' => '垂直方向の解像度',
'exif-stripoffsets' => '画像データのロケーション',
'exif-rowsperstrip' => '1ストリップごとの行数',
'exif-stripbytecounts' => 'ストリップの総バイト数',
'exif-jpeginterchangeformat' => 'JPEGのSOIへのオフセット',
'exif-jpeginterchangeformatlength' => 'JPEGデータのバイト数',
'exif-whitepoint' => '参照白色点の色度座標値',
'exif-primarychromaticities' => '原色の色度座標値',
'exif-ycbcrcoefficients' => '色変換マトリックス係数',
'exif-referenceblackwhite' => '参照黒色点値と参照白色点値',
'exif-datetime' => 'ファイル変更日時',
'exif-imagedescription' => '画像の説明',
'exif-make' => '撮影機器のメーカー名',
'exif-model' => '撮影機器のモデル名',
'exif-software' => '使用ソフトウェア名',
'exif-artist' => '作者',
'exif-copyright' => '撮影著作権者/編集著作権者',
'exif-exifversion' => 'Exifバージョン',
'exif-flashpixversion' => '対応フラッシュピックスバージョン',
'exif-colorspace' => '色空間情報',
'exif-componentsconfiguration' => '各コンポーネントの意味',
'exif-compressedbitsperpixel' => '画像圧縮モード',
'exif-pixelydimension' => '実効画像の幅',
'exif-pixelxdimension' => '実効画像の高さ',
'exif-usercomment' => 'ユーザー コメント',
'exif-relatedsoundfile' => '関連音声ファイル',
'exif-datetimeoriginal' => '原画像データの生成日時',
'exif-datetimedigitized' => 'デジタルデータの作成日時',
'exif-subsectime' => 'ファイル変更日時(1秒未満)',
'exif-subsectimeoriginal' => '原画像データの生成日時(1秒未満)',
'exif-subsectimedigitized' => 'デジタルデータの作成日時(1秒未満)',
'exif-exposuretime' => '露出時間',
'exif-exposuretime-format' => '$1秒 ($2)',
'exif-fnumber' => 'F値',
'exif-exposureprogram' => '露出プログラム',
'exif-spectralsensitivity' => 'スペクトル感度',
'exif-isospeedratings' => 'ISO 感度',
'exif-shutterspeedvalue' => 'シャッタースピード',
'exif-aperturevalue' => '絞り値',
'exif-brightnessvalue' => '輝度値',
'exif-exposurebiasvalue' => '露出補正値',
'exif-maxaperturevalue' => 'レンズ最小F値',
'exif-subjectdistance' => '被写体距離',
'exif-meteringmode' => '測光モード',
'exif-lightsource' => '光源',
'exif-flash' => 'フラッシュ',
'exif-focallength' => 'レンズ焦点距離',
'exif-subjectarea' => '被写体領域',
'exif-flashenergy' => 'フラッシュ強度',
'exif-focalplanexresolution' => '焦点面の幅の解像度',
'exif-focalplaneyresolution' => '焦点面の高さの解像度',
'exif-focalplaneresolutionunit' => '焦点面解像度単位',
'exif-subjectlocation' => '被写体位置',
'exif-exposureindex' => '露出インデックス',
'exif-sensingmethod' => 'センサー方式',
'exif-filesource' => 'ファイルソース',
'exif-scenetype' => 'シーンタイプ',
'exif-customrendered' => '個別画像処理',
'exif-exposuremode' => '露出モード',
'exif-whitebalance' => 'ホワイトバランス',
'exif-digitalzoomratio' => 'デジタルズーム倍率',
'exif-focallengthin35mmfilm' => '35mmフィルム換算焦点距離',
'exif-scenecapturetype' => '撮影シーンタイプ',
'exif-gaincontrol' => 'ゲイン制御',
'exif-contrast' => '撮影コントラスト',
'exif-saturation' => '撮影彩度',
'exif-sharpness' => '撮影シャープネス',
'exif-devicesettingdescription' => '撮影条件記述情報',
'exif-subjectdistancerange' => '被写体距離レンジ',
'exif-imageuniqueid' => '画像ユニークID',
'exif-gpsversionid' => 'GPSタグのバージョン',
'exif-gpslatituderef' => '北緯/南緯',
'exif-gpslatitude' => '緯度',
'exif-gpslongituderef' => '東経/西経',
'exif-gpslongitude' => '経度',
'exif-gpsaltituderef' => '高度の基準',
'exif-gpsaltitude' => '高度',
'exif-gpstimestamp' => 'GPS日時 (原子時計)',
'exif-gpssatellites' => '測位に用いた衛星信号',
'exif-gpsstatus' => 'GPS受信機の状態',
'exif-gpsmeasuremode' => 'GPSの測位方法',
'exif-gpsdop' => '測位の信頼性',
'exif-gpsspeedref' => '速度の単位',
'exif-gpsspeed' => 'GPS 受信機の速度',
'exif-gpstrackref' => '進行方向の単位',
'exif-gpstrack' => '進行方向',
'exif-gpsimgdirectionref' => '撮影方向の基準',
'exif-gpsimgdirection' => '画像の方向',
'exif-gpsmapdatum' => '測位に用いた地図データ',
'exif-gpsdestlatituderef' => '目的地の北緯/南緯',
'exif-gpsdestlatitude' => '目的地の緯度',
'exif-gpsdestlongituderef' => '目的地の東経/西経',
'exif-gpsdestlongitude' => '目的地の経度',
'exif-gpsdestbearingref' => '目的地の方角の単位',
'exif-gpsdestbearing' => '目的地の方角',
'exif-gpsdestdistanceref' => '目的地までの距離の単位',
'exif-gpsdestdistance' => '目的地までの距離',
'exif-gpsprocessingmethod' => '測位方式の名称',
'exif-gpsareainformation' => '測位地点の名称',
'exif-gpsdatestamp' => 'GPS日付',
'exif-gpsdifferential' => 'GPS補正測位',
'exif-jpegfilecomment' => 'JPEGファイルのコメント',
'exif-keywords' => 'キーワード',
'exif-worldregioncreated' => '写真が撮影された大陸/地域',
'exif-countrycreated' => '写真が撮影された国',
'exif-countrycodecreated' => '写真が撮影された国のコード',
'exif-provinceorstatecreated' => '写真が撮影された州/県',
'exif-citycreated' => '写真が撮影された都市',
'exif-sublocationcreated' => '写真が撮影された町や通りの名前',
'exif-worldregiondest' => '写っている大陸/地域',
'exif-countrydest' => '写っている国',
'exif-countrycodedest' => '写っている国のコード',
'exif-provinceorstatedest' => '写っている州/県',
'exif-citydest' => '写っている都市',
'exif-sublocationdest' => '写っている町や通りの名前',
'exif-objectname' => '短いタイトル',
'exif-specialinstructions' => '取扱いに関する特記事項',
'exif-headline' => '見出し',
'exif-credit' => '帰属/提供者',
'exif-source' => 'ソース',
'exif-editstatus' => '画像の編集上の状態',
'exif-urgency' => '緊急度',
'exif-fixtureidentifier' => 'フィクスチャ名',
'exif-locationdest' => '映っている場所',
'exif-locationdestcode' => '映っている場所のコード',
'exif-objectcycle' => 'このメディアファイルが意図されている時間帯',
'exif-contact' => '連絡先情報',
'exif-writer' => '記入者',
'exif-languagecode' => '言語',
'exif-iimversion' => 'IIMバージョン',
'exif-iimcategory' => 'カテゴリ',
'exif-iimsupplementalcategory' => '補足カテゴリ',
'exif-datetimeexpires' => '使用期限',
'exif-datetimereleased' => '初公開日',
'exif-originaltransmissionref' => '原転送位置コード',
'exif-identifier' => '識別子',
'exif-lens' => '使用レンズ',
'exif-serialnumber' => 'カメラのシリアル番号',
'exif-cameraownername' => 'カメラの所有者',
'exif-label' => 'ラベル',
'exif-datetimemetadata' => 'メタデータの最終更新日',
'exif-nickname' => '画像の非公式名',
'exif-rating' => '評価 (5点満点)',
'exif-rightscertificate' => '権利管理証明書',
'exif-copyrighted' => '著作権情報',
'exif-copyrightowner' => '著作権者',
'exif-usageterms' => '使用条件',
'exif-webstatement' => 'オンライン上の著作権文',
'exif-originaldocumentid' => '元文書の一意なID',
'exif-licenseurl' => '著作権ライセンスのURL',
'exif-morepermissionsurl' => '代替ライセンス情報',
'exif-attributionurl' => 'この作品を再利用する際に、次のURLにリンクしてください',
'exif-preferredattributionname' => 'この作品を再利用する際に、次の帰属表示を使用してください',
'exif-pngfilecomment' => 'PNGファイルのコメント',
'exif-disclaimer' => '免責事項',
'exif-contentwarning' => 'コンテンツに関する警告',
'exif-giffilecomment' => 'GIFファイルのコメント',
'exif-intellectualgenre' => '項目の種類',
'exif-subjectnewscode' => '主題コード',
'exif-scenecode' => 'IPTCシーンコード',
'exif-event' => '映っている事象',
'exif-organisationinimage' => '映っている組織',
'exif-personinimage' => '映っている人物',
'exif-originalimageheight' => 'トリミングされる前の画像の高さ',
'exif-originalimagewidth' => 'トリミングされる前の画像の幅',

# Exif attributes
'exif-compression-1' => '無圧縮',
'exif-compression-2' => 'CCITT Group 3 1次元修正ハフマン連長符号化',
'exif-compression-3' => 'CCITT Group 3 ファックス符号化',
'exif-compression-4' => 'CCITT Group 4 ファックス符号化',
'exif-compression-6' => 'JPEG (旧式)',

'exif-copyrighted-true' => '著作権あり',
'exif-copyrighted-false' => '著作権情報未設定',

'exif-unknowndate' => '不明な日付',

'exif-orientation-1' => '通常',
'exif-orientation-2' => '左右反転',
'exif-orientation-3' => '180°回転',
'exif-orientation-4' => '上下反転',
'exif-orientation-5' => '反時計回りに90°回転、上下反転',
'exif-orientation-6' => '反時計回りに90°回転',
'exif-orientation-7' => '時計回りに90°回転、上下反転',
'exif-orientation-8' => '時計回りに90°回転',

'exif-planarconfiguration-1' => '点順次フォーマット',
'exif-planarconfiguration-2' => '面順次フォーマット',

'exif-colorspace-65535' => 'その他',

'exif-componentsconfiguration-0' => '存在しない',

'exif-exposureprogram-0' => '未定義',
'exif-exposureprogram-1' => 'マニュアル',
'exif-exposureprogram-2' => 'ノーマルプログラム',
'exif-exposureprogram-3' => '絞り優先',
'exif-exposureprogram-4' => 'シャッター優先',
'exif-exposureprogram-5' => 'クリエイティブプログラム(被写界を深度方向に偏らせる)',
'exif-exposureprogram-6' => 'アクションプログラム(シャッタースピードを高速側に偏らせる)',
'exif-exposureprogram-7' => 'ポートレイトモード(近接撮影、フォーカスを背景から外す)',
'exif-exposureprogram-8' => 'ランドスケープモード(風景撮影、フォーカスを背景に合わせる)',

'exif-subjectdistance-value' => '$1メートル',

'exif-meteringmode-0' => '不明',
'exif-meteringmode-1' => '平均',
'exif-meteringmode-2' => '中央重点',
'exif-meteringmode-3' => 'スポット',
'exif-meteringmode-4' => 'マルチスポット',
'exif-meteringmode-5' => '分割測光',
'exif-meteringmode-6' => '部分測光',
'exif-meteringmode-255' => 'その他の測光形式',

'exif-lightsource-0' => '不明',
'exif-lightsource-1' => '昼光',
'exif-lightsource-2' => '蛍光灯',
'exif-lightsource-3' => 'タングステン (白熱灯)',
'exif-lightsource-4' => 'フラッシュ',
'exif-lightsource-9' => '晴天',
'exif-lightsource-10' => '曇天',
'exif-lightsource-11' => '日陰',
'exif-lightsource-12' => '昼光色蛍光灯 (D:5700 - 7100K)',
'exif-lightsource-13' => '昼白色蛍光灯 (N:4600 - 5400K)',
'exif-lightsource-14' => '白色蛍光灯 (W:3900 - 4500K)',
'exif-lightsource-15' => '温白色蛍光灯 (WW:3200 - 3700K)',
'exif-lightsource-17' => '標準光A',
'exif-lightsource-18' => '標準光B',
'exif-lightsource-19' => '標準光C',
'exif-lightsource-24' => 'ISOスタジオタングステン',
'exif-lightsource-255' => 'その他の光源',

# Flash modes
'exif-flash-fired-0' => 'フラッシュ発光せず',
'exif-flash-fired-1' => 'フラッシュ発光',
'exif-flash-return-0' => 'ストロボのリターン検出機能なし',
'exif-flash-return-2' => 'ストロボのリターン検出されず',
'exif-flash-return-3' => 'ストロボのリターン検出',
'exif-flash-mode-1' => '強制発光モード',
'exif-flash-mode-2' => '強制非発光モード',
'exif-flash-mode-3' => '自動発光モード',
'exif-flash-function-1' => 'ストロボ機能なし',
'exif-flash-redeye-1' => '赤目軽減有り',

'exif-focalplaneresolutionunit-2' => 'インチ',

'exif-sensingmethod-1' => '未定義',
'exif-sensingmethod-2' => '単板カラーセンサー',
'exif-sensingmethod-3' => '2板カラーセンサー',
'exif-sensingmethod-4' => '3板カラーセンサー',
'exif-sensingmethod-5' => '色順次カラーセンサー',
'exif-sensingmethod-7' => '3線リニアセンサー',
'exif-sensingmethod-8' => '色順次リニアセンサー',

'exif-filesource-3' => 'デジタルスチルカメラ',

'exif-scenetype-1' => '直接撮影された画像',

'exif-customrendered-0' => '通常処理',
'exif-customrendered-1' => '特殊処理',

'exif-exposuremode-0' => '露出自動',
'exif-exposuremode-1' => '露出マニュアル',
'exif-exposuremode-2' => 'オートブラケット',

'exif-whitebalance-0' => 'ホワイトバランス自動',
'exif-whitebalance-1' => 'ホワイトバランスマニュアル',

'exif-scenecapturetype-0' => '標準',
'exif-scenecapturetype-1' => '風景',
'exif-scenecapturetype-2' => '人物',
'exif-scenecapturetype-3' => '夜景',

'exif-gaincontrol-0' => 'なし',
'exif-gaincontrol-1' => '弱い増感',
'exif-gaincontrol-2' => '強い増感',
'exif-gaincontrol-3' => '弱い減感',
'exif-gaincontrol-4' => '強い減感',

'exif-contrast-0' => '標準',
'exif-contrast-1' => '軟調',
'exif-contrast-2' => '硬調',

'exif-saturation-0' => '標準',
'exif-saturation-1' => '低彩度',
'exif-saturation-2' => '高彩度',

'exif-sharpness-0' => '標準',
'exif-sharpness-1' => '弱い',
'exif-sharpness-2' => '強い',

'exif-subjectdistancerange-0' => '不明',
'exif-subjectdistancerange-1' => 'マクロ',
'exif-subjectdistancerange-2' => '近景',
'exif-subjectdistancerange-3' => '遠景',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => '北緯',
'exif-gpslatitude-s' => '南緯',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => '東経',
'exif-gpslongitude-w' => '西経',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '海抜 $1 {{PLURAL:$1|メートル}}',
'exif-gpsaltitude-below-sealevel' => '水面下 $1 {{PLURAL:$1|メートル}}',

'exif-gpsstatus-a' => '測位中',
'exif-gpsstatus-v' => '未測位 (中断中)',

'exif-gpsmeasuremode-2' => '2 次元測位',
'exif-gpsmeasuremode-3' => '3 次元測位',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'キロメートル/時',
'exif-gpsspeed-m' => 'マイル/時',
'exif-gpsspeed-n' => 'ノット',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'キロメートル',
'exif-gpsdestdistance-m' => 'マイル',
'exif-gpsdestdistance-n' => '海里',

'exif-gpsdop-excellent' => '優秀 ($1)',
'exif-gpsdop-good' => '良好 ($1)',
'exif-gpsdop-moderate' => '適度 ($1)',
'exif-gpsdop-fair' => '中程度 ($1)',
'exif-gpsdop-poor' => '劣悪 ($1)',

'exif-objectcycle-a' => '午前のみ',
'exif-objectcycle-p' => '午後のみ',
'exif-objectcycle-b' => '午後と午前の両方',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => '真方位',
'exif-gpsdirection-m' => '磁気方位',

'exif-ycbcrpositioning-1' => '中心',
'exif-ycbcrpositioning-2' => '一致',

'exif-dc-contributor' => '貢献者',
'exif-dc-coverage' => 'メディアの空間的または時間的範囲',
'exif-dc-date' => '日付',
'exif-dc-publisher' => '公開者',
'exif-dc-relation' => '関連メディア',
'exif-dc-rights' => '権利',
'exif-dc-source' => 'ソースメディア',
'exif-dc-type' => 'メディアの種類',

'exif-rating-rejected' => '却下',

'exif-isospeedratings-overflow' => '65535より大きい',

'exif-iimcategory-ace' => '芸術、文化、娯楽',
'exif-iimcategory-clj' => '犯罪と法律',
'exif-iimcategory-dis' => '災害、事故',
'exif-iimcategory-fin' => '経済とビジネス',
'exif-iimcategory-edu' => '教育',
'exif-iimcategory-evn' => '環境',
'exif-iimcategory-hth' => '健康',
'exif-iimcategory-hum' => '人々の興味',
'exif-iimcategory-lab' => '労働',
'exif-iimcategory-lif' => 'ライフスタイルとレジャー',
'exif-iimcategory-pol' => '政治',
'exif-iimcategory-rel' => '宗教と信仰',
'exif-iimcategory-sci' => '科学と技術',
'exif-iimcategory-soi' => '社会問題',
'exif-iimcategory-spo' => 'スポーツ',
'exif-iimcategory-war' => '戦争、紛争、動乱',
'exif-iimcategory-wea' => '天気',

'exif-urgency-normal' => '通常 ($1)',
'exif-urgency-low' => '低 ($1)',
'exif-urgency-high' => '高 ($1)',
'exif-urgency-other' => '利用者定義の優先度 ($1)',

# External editor support
'edit-externally' => '外部アプリケーションを使用してこのファイルを編集',
'edit-externally-help' => '(詳しくは[//www.mediawiki.org/wiki/Manual:External_editors 設定手順]をご覧ください)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'すべて',
'namespacesall' => 'すべて',
'monthsall' => 'すべて',
'limitall' => 'すべて',

# Email address confirmation
'confirmemail' => 'メールアドレスの確認',
'confirmemail_noemail' => '[[Special:Preferences|個人設定]]で有効なメールアドレスが指定されていません。',
'confirmemail_text' => '{{SITENAME}}では、メール機能を使用する前にメールアドレスの検証が必要です。
以下のボタンを押すと、あなたのメールアドレスに確認用メールをお送りします。
そのメール内に、確認用コードを含むリンクがあります。
メールアドレスが有効であることを確認するために、そのリンクをブラウザーで開いてください。',
'confirmemail_pending' => '確認用コードをメールで既にお送りしました。
アカウントを作成したばかりの場合は、メールが届くまでしばらくお待ちください。届かない場合は新しいコードを再度申請してください。',
'confirmemail_send' => '確認用コードをメールで送信',
'confirmemail_sent' => '確認メールを送信しました。',
'confirmemail_oncreate' => 'あなたのメールアドレスに確認用コードをメールでお送りしました。
この確認をしなくてもログインはできますが、確認するまでウィキ内のメール関連の機能は無効化されます。',
'confirmemail_sendfailed' => '{{SITENAME}}は確認メールを送信できませんでした。
メールアドレスが無効な文字を含んでいないかご確認ください。

メールサーバーからの返答: $1',
'confirmemail_invalid' => '確認用コードが正しくありません。
このコードの有効期限が切れている可能性があります。',
'confirmemail_needlogin' => 'メールアドレスを確認するには$1する必要があります。',
'confirmemail_success' => 'メールアドレスは確認されました。
[[Special:UserLogin|ログイン]]してウィキを使用できます。',
'confirmemail_loggedin' => 'メールアドレスは確認されました。',
'confirmemail_error' => '確認情報を保存する際にエラーが発生しました。',
'confirmemail_subject' => '{{SITENAME}} メールアドレスの確認',
'confirmemail_body' => '誰か (おそらくあなた) が、IP アドレス$1から、
このメールアドレスで {{SITENAME}} のアカウント「$2」を登録しました。

このアカウントが本当に自分のものか確認して、
{{SITENAME}} のメール機能を有効にするには、以下の URL をブラウザーで開いてください:

$3

アカウント登録をした覚えがない場合は、
以下の URL をブラウザーで開いて、メールアドレスの確認をキャンセルしてください:

$5

この確認用コードは、$4に期限切れになります。',
'confirmemail_body_changed' => '誰か (おそらくあなた) が IP アドレス $1 から、
{{SITENAME}} のアカウント「$2」のメールアドレスをこのアドレスに変更しました。

このアカウントが本当にあなたのものであれば、以下のリンクをブラウザーで開いて、
{{SITENAME}} のメール機能を再び有効にしてください:

$3

もしあなたのアカウント *ではない* 場合は、
ブラウザーで以下のリンクを開いて、メールアドレスの確認をキャンセルしてください:

$5

この確認コードは $4 に期限切れになります。',
'confirmemail_body_set' => '誰か (おそらくあなた) が IP アドレス $1 から
{{SITENAME}} のアカウント「$2」のメールアドレスをこのアドレスに設定しました。

このアカウントが本当にあなたのものであれば、以下のリンクをブラウザーで開いて、
{{SITENAME}} のメール機能を有効にしてください。

$3

あなたのアカウントではない場合は、
以下のリンクをブラウザーで開いて、メールアドレスの確認をキャンセルしてください:

$5

この確認コードは $4 に期限切れになります。',
'confirmemail_invalidated' => 'メールアドレスの確認が中止されました',
'invalidateemail' => 'メールアドレスの確認中止',

# Scary transclusion
'scarytranscludedisabled' => '[ウィキ間の参照読み込みは無効になっています]',
'scarytranscludefailed' => '[$1に対してテンプレートの取得に失敗しました]',
'scarytranscludefailed-httpstatus' => '[$1に対してテンプレートの取得に失敗しました: HTTP $2]',
'scarytranscludetoolong' => '[URLが長すぎます]',

# Delete conflict
'deletedwhileediting' => "'''警告:''' このページが、編集開始後に削除されました!",
'confirmrecreate' => "あなたが編集を開始した後、[[User:$1|$1]] ([[User talk:$1|トーク]]) がこのページを以下の理由で削除しました:
: ''$2''
このままこのページを本当に再作成していいか確認してください。",
'confirmrecreate-noreason' => 'あなたが編集を開始した後、[[User:$1|$1]] ([[User talk:$1|トーク]]) がこのページを削除しました。このページを本当に再作成していいかご確認ください。',
'recreate' => '再作成する',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top' => 'このページのキャッシュを破棄しますか?',
'confirm-purge-bottom' => 'ページをパージすると、キャッシュが破棄され、強制的に最新版が表示されます。',

# action=watch/unwatch
'confirm-watch-button' => 'OK',
'confirm-watch-top' => 'このページをウォッチリストに追加しますか?',
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top' => 'このページをウォッチリストから除去しますか?',

# Separators for various lists, etc.
'semicolon-separator' => ';&#32;',
'comma-separator' => '、',
'colon-separator' => ':&#32;',
'pipe-separator' => '&#32;|&#32;',
'word-separator' => '&#32;',
'parentheses' => '($1)',

# Multipage image navigation
'imgmultipageprev' => '&larr;前ページ',
'imgmultipagenext' => '次ページ&rarr;',
'imgmultigo' => '表示',
'imgmultigoto' => 'ページ$1に移動',

# Table pager
'ascending_abbrev' => '昇順',
'descending_abbrev' => '降順',
'table_pager_next' => '次のページ',
'table_pager_prev' => '前のページ',
'table_pager_first' => '最初のページ',
'table_pager_last' => '最後のページ',
'table_pager_limit' => '1ページに$1項目を表示',
'table_pager_limit_label' => 'ページあたりの項目数:',
'table_pager_limit_submit' => '実行',
'table_pager_empty' => '結果なし',

# Auto-summaries
'autosumm-blank' => 'ページの白紙化',
'autosumm-replace' => '内容を「$1」で置換',
'autoredircomment' => '[[$1]]への転送ページ',
'autosumm-new' => 'ページの作成:「$1」',

# Size units
'size-bytes' => '$1バイト',
'size-kilobytes' => '$1キロバイト',
'size-megabytes' => '$1メガバイト',
'size-gigabytes' => '$1ギガバイト',
'size-terabytes' => '$1 TB',
'size-petabytes' => '$1 PB',
'size-exabytes' => '$1 EB',
'size-zetabytes' => '$1 ZB',
'size-yottabytes' => '$1 YB',

# Live preview
'livepreview-loading' => '読み込み中...',
'livepreview-ready' => '読み込み中...完了!',
'livepreview-failed' => 'ライブプレビューが失敗しました!
通常のプレビューを試してください。',
'livepreview-error' => '接続に失敗しました: $1「$2」。
通常のプレビューを試してください。',

# Friendlier slave lag warnings
'lag-warn-normal' => 'この一覧には、$1 {{PLURAL:$1|秒}}より前の変更が表示されていない可能性があります。',
'lag-warn-high' => 'データベースサーバー遅延のため、この一覧には、$1 {{PLURAL:$1|秒}}より前の変更が表示されていない可能性があります。',

# Watchlist editor
'watchlistedit-numitems' => 'ウォッチリストには {{PLURAL:$1|$1 件のページ}}が登録されています (トークページを除く)。',
'watchlistedit-noitems' => 'ウォッチリストにはどのページも登録されていません。',
'watchlistedit-normal-title' => 'ウォッチリストの編集',
'watchlistedit-normal-legend' => 'ウォッチリストからページを除去',
'watchlistedit-normal-explain' => 'ウォッチリストに入っているページ名を以下に表示しています。
ページを除去するには、隣のボックスにチェックを入れて「{{int:watchlistedit-normal-submit}}」をクリックしてください。
また、[[Special:EditWatchlist/raw|ウォッチリストをテキストで編集]]も使用できます。',
'watchlistedit-normal-submit' => 'ページを除去',
'watchlistedit-normal-done' => 'ウォッチリストから {{PLURAL:$1|$1 件のページ}}を除去しました:',
'watchlistedit-raw-title' => 'ウォッチリストをテキストで編集',
'watchlistedit-raw-legend' => 'ウォッチリストをテキストで編集',
'watchlistedit-raw-explain' => '以下に、ウォッチリストに含まれるページ名を列挙しています。この一覧で追加や除去ができます。
1行に1ページ名です。
完了したら、「{{int:Watchlistedit-raw-submit}}」をクリックしてください。
[[Special:EditWatchlist|標準の編集ページ]]も使用できます。',
'watchlistedit-raw-titles' => 'ページ名:',
'watchlistedit-raw-submit' => 'ウォッチリストを更新',
'watchlistedit-raw-done' => 'ウォッチリストを更新しました。',
'watchlistedit-raw-added' => '{{PLURAL:$1|$1 ページ}}を追加しました:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|$1 ページ}}を除去しました:',

# Watchlist editing tools
'watchlisttools-view' => '関連する変更を閲覧',
'watchlisttools-edit' => 'ウォッチリストの閲覧と編集',
'watchlisttools-raw' => 'ウォッチリストをテキストで編集',

# Iranian month names
'iranian-calendar-m1' => 'ファルヴァルディーン',
'iranian-calendar-m2' => 'オルディーベヘシュト',
'iranian-calendar-m3' => 'ホルダード',
'iranian-calendar-m4' => 'ティール',
'iranian-calendar-m5' => 'モルダード',
'iranian-calendar-m6' => 'シャハリーヴァル',
'iranian-calendar-m7' => 'メフル',
'iranian-calendar-m8' => 'アーバーン',
'iranian-calendar-m9' => 'アーザル',
'iranian-calendar-m10' => 'デイ',
'iranian-calendar-m11' => 'バフマン',
'iranian-calendar-m12' => 'エスファンド',

# Hijri month names
'hijri-calendar-m1' => 'ムハッラム',
'hijri-calendar-m2' => 'サファル',
'hijri-calendar-m3' => 'ラビーウ＝ル＝アウワル',
'hijri-calendar-m4' => 'ラビーウッ＝サーニー',
'hijri-calendar-m5' => 'ジュマーダー＝ル＝ウーラー',
'hijri-calendar-m6' => 'ジュマーダーッ＝サーニー',
'hijri-calendar-m7' => 'ラジャブ',
'hijri-calendar-m8' => 'シャアバーン',
'hijri-calendar-m9' => 'ラマダーン',
'hijri-calendar-m10' => 'シャウワール',
'hijri-calendar-m11' => 'ズー＝ル＝カアダ',
'hijri-calendar-m12' => 'ズー＝ル＝ヒッジャ',

# Hebrew month names
'hebrew-calendar-m1' => 'ティシュリー',
'hebrew-calendar-m2' => 'ヘシュヴァン',
'hebrew-calendar-m3' => 'キスレーヴ',
'hebrew-calendar-m4' => 'テベット',
'hebrew-calendar-m5' => 'シュバット',
'hebrew-calendar-m6' => 'アダル',
'hebrew-calendar-m6a' => 'アダル・アレフ',
'hebrew-calendar-m6b' => 'アダル・ベート',
'hebrew-calendar-m7' => 'ニサン',
'hebrew-calendar-m8' => 'イヤール',
'hebrew-calendar-m9' => 'シバン',
'hebrew-calendar-m10' => 'タムーズ',
'hebrew-calendar-m11' => 'アブ',
'hebrew-calendar-m12' => 'エルール',
'hebrew-calendar-m1-gen' => 'ティシュリー',
'hebrew-calendar-m2-gen' => 'ヘシュヴァン',
'hebrew-calendar-m3-gen' => 'キスレーヴ',
'hebrew-calendar-m4-gen' => 'テベット',
'hebrew-calendar-m5-gen' => 'シュバット',
'hebrew-calendar-m6-gen' => 'アダル',
'hebrew-calendar-m6a-gen' => 'アダル・アレフ',
'hebrew-calendar-m6b-gen' => 'アダル・ベート',
'hebrew-calendar-m7-gen' => 'ニサン',
'hebrew-calendar-m8-gen' => 'イヤール',
'hebrew-calendar-m9-gen' => 'シバン',
'hebrew-calendar-m10-gen' => 'タムーズ',
'hebrew-calendar-m11-gen' => 'アブ',
'hebrew-calendar-m12-gen' => 'エルール',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|トーク]])',

# Core parser functions
'unknown_extension_tag' => '不明な拡張機能タグ「$1」です',
'duplicate-defaultsort' => "'''警告:''' 既定のソートキー「$2」が、その前に書かれている既定のソートキー「$1」を上書きしています。",

# Special:Version
'version' => 'バージョン情報',
'version-extensions' => 'インストール済み拡張機能',
'version-specialpages' => '特別ページ',
'version-parserhooks' => '構文解析フック',
'version-variables' => '変数',
'version-antispam' => 'スパム対策',
'version-skins' => '外装',
'version-other' => 'その他',
'version-mediahandlers' => 'メディアハンドラー',
'version-hooks' => 'フック',
'version-parser-extensiontags' => '構文解析拡張機能タグ',
'version-parser-function-hooks' => 'パーサー関数フック',
'version-hook-name' => 'フック名',
'version-hook-subscribedby' => '使用個所',
'version-version' => '(バージョン $1)',
'version-license' => 'ライセンス',
'version-poweredby-credits' => "このウィキは、'''[//www.mediawiki.org/ MediaWiki]''' (copyright © 2001-$1 $2) で動作しています。",
'version-poweredby-others' => 'その他',
'version-poweredby-translators' => 'translatewiki.net の翻訳者たち',
'version-credits-summary' => '[[Special:Version|MediaWiki]] に貢献した以下の人たちに感謝します。',
'version-license-info' => 'MediaWikiはフリーソフトウェアです。あなたは、フリーソフトウェア財団の発行するGNU一般公衆利用許諾書 (GNU General Public License) (バージョン2、またはそれ以降のライセンス) の規約に基づき、このライブラリを再配布および改変できます。

MediaWikiは、有用であることを期待して配布されていますが、商用あるいは特定の目的に適するかどうかも含めて、暗黙的にも、一切保証されません。詳しくは、GNU一般公衆利用許諾書をご覧ください。

あなたはこのプログラムと共に、[{{SERVER}}{{SCRIPTPATH}}/COPYING GNU一般公衆利用許諾契約書の複製]を受け取ったはずです。受け取っていない場合は、フリーソフトウェア財団 (the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA) まで請求するか、[//www.gnu.org/licenses/old-licenses/gpl-2.0.html オンラインで閲覧]してください。',
'version-software' => 'インストール済みソフトウェア',
'version-software-product' => '製品',
'version-software-version' => 'バージョン',
'version-entrypoints' => 'エントリーポイントの URL',
'version-entrypoints-header-entrypoint' => 'エントリーポイント',
'version-entrypoints-header-url' => 'URL',
'version-entrypoints-articlepath' => '[https://www.mediawiki.org/wiki/Manual:$wgArticlePath/ja 記事のパス]',
'version-entrypoints-scriptpath' => '[https://www.mediawiki.org/wiki/Manual:$wgScriptPath/ja スクリプトパス]',

# Special:Redirect
'redirect' => 'ファイル名、利用者ID、版IDでの転送',
'redirect-legend' => 'ファイルまたはページヘの転送',
'redirect-summary' => 'この特別ページは、ファイル (ファイル名を指定)、ページ (版 ID を指定)、利用者ページ (利用者 ID を整数で指定) に転送されます。',
'redirect-submit' => '実行',
'redirect-lookup' => '検索の種類:',
'redirect-value' => '値:',
'redirect-user' => '利用者 ID',
'redirect-revision' => 'ページの版 ID',
'redirect-file' => 'ファイル名',
'redirect-not-exists' => '値が見つかりません',

# Special:FileDuplicateSearch
'fileduplicatesearch' => '重複ファイルの検索',
'fileduplicatesearch-summary' => '重複ファイルをハッシュ値に基づいて検索します。',
'fileduplicatesearch-legend' => '重複の検索',
'fileduplicatesearch-filename' => 'ファイル名:',
'fileduplicatesearch-submit' => '検索',
'fileduplicatesearch-info' => '$1 × $2 ピクセル<br />ファイルサイズ: $3<br />MIME タイプ: $4',
'fileduplicatesearch-result-1' => 'ファイル「$1」と重複するファイルはありません。',
'fileduplicatesearch-result-n' => 'ファイル「$1」には {{PLURAL:$2|$2 件の重複ファイル}}があります。',
'fileduplicatesearch-noresults' => '「$1」という名前のファイルはありません。',

# Special:SpecialPages
'specialpages' => '特別ページ',
'specialpages-note' => '----
* 通常の特別ページ
* <span class="mw-specialpagerestricted">制限されている特別ページ</span>',
'specialpages-group-maintenance' => 'メンテナンス報告',
'specialpages-group-other' => 'その他の特別ページ',
'specialpages-group-login' => 'ログインまたはアカウント作成',
'specialpages-group-changes' => '最近の更新と記録',
'specialpages-group-media' => 'メディア情報とアップロード',
'specialpages-group-users' => '利用者と権限',
'specialpages-group-highuse' => 'よく利用されているページ',
'specialpages-group-pages' => 'ページの一覧',
'specialpages-group-pagetools' => 'ページツール',
'specialpages-group-wiki' => 'データとツール',
'specialpages-group-redirects' => '転送される特別ページ',
'specialpages-group-spam' => 'スパム対策ツール',

# Special:BlankPage
'blankpage' => '白紙ページ',
'intentionallyblankpage' => 'このページは意図的に白紙にされています。',

# External image whitelist
'external_image_whitelist' => '  #この行はこのままにしておいてください<pre>
#この下に正規表現 (//の間に入る記述) を置いてください
#外部の (ホットリンクされている) 画像の URL と一致するか検査されます
#一致する場合は画像として、一致しない場合は画像へのリンクとして表示されます
#行の頭に # を付けるとコメントとして扱われます
#大文字と小文字は区別されません

#正規表現はすべてこの行の上に置いてください。この行はこのままにしておいてください</pre>',

# Special:Tags
'tags' => '有効な変更タグ',
'tag-filter' => '[[Special:Tags|タグ]]絞り込み:',
'tag-filter-submit' => '絞り込み',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|タグ}}]]: $2)',
'tags-title' => 'タグ',
'tags-intro' => 'このページは、ソフトウェアが編集に対して付けるタグとその意味の一覧です。',
'tags-tag' => 'タグ名',
'tags-display-header' => '変更一覧に表示されるもの',
'tags-description-header' => '詳細な意味の説明',
'tags-active-header' => 'アクティブ?',
'tags-hitcount-header' => 'タグが付与された変更',
'tags-active-yes' => 'はい',
'tags-active-no' => 'いいえ',
'tags-edit' => '編集',
'tags-hitcount' => '$1 {{PLURAL:$1|回の変更}}',

# Special:ComparePages
'comparepages' => 'ページの比較',
'compare-selector' => 'ページの版を比較',
'compare-page1' => 'ページ 1',
'compare-page2' => 'ページ 2',
'compare-rev1' => '版 1',
'compare-rev2' => '版 2',
'compare-submit' => '比較',
'compare-invalid-title' => '指定したページ名は無効です。',
'compare-title-not-exists' => '指定したページは存在しません。',
'compare-revision-not-exists' => '指定した版は存在しません。',

# Database error messages
'dberr-header' => 'このウィキには問題があります',
'dberr-problems' => '申し訳ありません! このウェブサイトに技術的な障害が発生しています。',
'dberr-again' => '数分間待った後、もう一度読み込んでください。',
'dberr-info' => '(データベースサーバー $1 に接続できませんでした)',
'dberr-info-hidden' => '(データベース サーバーと通信できません)',
'dberr-usegoogle' => '元に戻るまで、Googleを利用して検索できます。',
'dberr-outofdate' => '収集された内容は古い可能性があることに注意してください。',
'dberr-cachederror' => 'これは要求されたページをキャッシュした複製であり、古くなっている可能性があります。',

# HTML forms
'htmlform-invalid-input' => '入力に何らかの問題があります',
'htmlform-select-badoption' => '指定した値は有効な選択肢ではありません。',
'htmlform-int-invalid' => '指定した値は整数ではありません。',
'htmlform-float-invalid' => '指定した値は数値ではありません。',
'htmlform-int-toolow' => '指定した値は、最小値 $1 より小さい値です',
'htmlform-int-toohigh' => '指定した値は、最大値 $1 を超えています',
'htmlform-required' => 'この値は必須です',
'htmlform-submit' => '送信',
'htmlform-reset' => '変更を取り消す',
'htmlform-selectorother-other' => 'その他',
'htmlform-no' => 'いいえ',
'htmlform-yes' => 'はい',
'htmlform-chosen-placeholder' => '選択してください',

# SQLite database support
'sqlite-has-fts' => '$1 (全文検索あり)',
'sqlite-no-fts' => '$1 (全文検索なし)',

# New logging system
'logentry-delete-delete' => '$1 がページ「$3」を{{GENDER:$2|削除しました}}',
'logentry-delete-restore' => '$1 がページ「$3」を{{GENDER:$2|復元しました}}',
'logentry-delete-event' => '$1 が$3の{{PLURAL:$5|記録項目$5件}}の閲覧レベルを{{GENDER:$2|変更しました}}: $4',
'logentry-delete-revision' => '$1 がページ「$3」の{{PLURAL:$5|$5版}}の閲覧レベルを{{GENDER:$2|変更しました}}: $4',
'logentry-delete-event-legacy' => '$1 が「$3」の記録項目の閲覧レベルを{{GENDER:$2|変更しました}}',
'logentry-delete-revision-legacy' => '$1 がページ「$3」の版の閲覧レベルを{{GENDER:$2|変更しました}}',
'logentry-suppress-delete' => '$1 がページ「$3」を{{GENDER:$2|隠蔽しました}}',
'logentry-suppress-event' => '$1 が$3の{{PLURAL:$5|記録項目$5件}}の閲覧レベルを見えない形で{{GENDER:$2|変更しました}}: $4',
'logentry-suppress-revision' => '$1 がページ「$3」の{{PLURAL:$5|$5版}}の閲覧レベルを見えない形で{{GENDER:$2|変更しました}}: $4',
'logentry-suppress-event-legacy' => '$1 が$3で記録項目の閲覧レベルを見えない形で{{GENDER:$2|変更しました}}',
'logentry-suppress-revision-legacy' => '$1 がページ「$3」の版の閲覧レベルを見えない形で{{GENDER:$2|変更しました}}',
'revdelete-content-hid' => '本文の不可視化',
'revdelete-summary-hid' => '編集要約の不可視化',
'revdelete-uname-hid' => '利用者名の不可視化',
'revdelete-content-unhid' => '本文の可視化',
'revdelete-summary-unhid' => '編集要約の可視化',
'revdelete-uname-unhid' => '利用者名の可視化',
'revdelete-restricted' => '管理者に対する制限の適用',
'revdelete-unrestricted' => '管理者に対する制限の除去',
'logentry-move-move' => '$1 がページ「$3」を「$4」に{{GENDER:$2|移動しました}}',
'logentry-move-move-noredirect' => '$1 がページ「$3」を「$4」に、リダイレクトを残さずに{{GENDER:$2|移動しました}}',
'logentry-move-move_redir' => '$1 がページ「$3」をリダイレクトの「$4」に{{GENDER:$2|移動しました}}',
'logentry-move-move_redir-noredirect' => '$1 がページ「$3」をリダイレクトの「$4」に、リダイレクトを残さずに{{GENDER:$2|移動しました}}',
'logentry-patrol-patrol' => '$1 がページ「$3」の版 $4 を巡回済みと{{GENDER:$2|しました}}',
'logentry-patrol-patrol-auto' => '$1 が自動的にページ「$3」の版 $4 を巡回済みと{{GENDER:$2|しました}}',
'logentry-newusers-newusers' => '利用者アカウント $1 が{{GENDER:$2|作成されました}}',
'logentry-newusers-create' => '利用者アカウント $1 が{{GENDER:$2|作成されました}}',
'logentry-newusers-create2' => '利用者アカウント $3 が $1 により{{GENDER:$2|作成されました}}',
'logentry-newusers-byemail' => '利用者アカウント $3 が $1 によって{{GENDER:$2|作成され}}、そのパスワードがメールで送信されました',
'logentry-newusers-autocreate' => '利用者アカウント $1 が自動的に{{GENDER:$2|作成されました}}',
'logentry-rights-rights' => '$1 が $3 の所属グループを $4 から $5 に{{GENDER:$2|変更しました}}',
'logentry-rights-rights-legacy' => '$1 が $3 の所属グループを{{GENDER:$2|変更しました}}',
'logentry-rights-autopromote' => '$1 が $4 から $5 に自動的に{{GENDER:$2|昇格しました}}',
'rightsnone' => '(なし)',

# Feedback
'feedback-bugornote' => '技術的な問題の詳細を説明する準備ができている場合は、[$1 バグ報告]をお願いします。
準備ができていない場合は、下の簡易フォームを使用してください。あなたのコメントと利用者名が、ページ「[$3 $2]」に追加されます。',
'feedback-subject' => '件名:',
'feedback-message' => 'メッセージ:',
'feedback-cancel' => 'キャンセル',
'feedback-submit' => 'フィードバックを送信',
'feedback-adding' => 'ページへのフィードバックの追加...',
'feedback-error1' => 'エラー: 認識できない結果を API が返しました',
'feedback-error2' => 'エラー: 編集に失敗しました',
'feedback-error3' => 'エラー: API からの応答がありません',
'feedback-thanks' => 'ありがとうございます。フィードバックを「[$2 $1]」のページに投稿しました。',
'feedback-close' => '完了',
'feedback-bugcheck' => 'Great! [$1 既出のバグ]に既に含まれていないかご確認ください。',
'feedback-bugnew' => 'チェックしました。バグを報告します。',

# Search suggestions
'searchsuggest-search' => '検索',
'searchsuggest-containing' => 'この語句を全文検索',

# API errors
'api-error-badaccess-groups' => 'このウィキへのファイルのアップロードが許可されていません。',
'api-error-badtoken' => '内部エラー: トークンが正しくありません。',
'api-error-copyuploaddisabled' => 'URLによるアップロードはこのサーバーでは無効になっています。',
'api-error-duplicate' => '当ウェブサイト上には、既に同じ内容の{{PLURAL:$1|[$2 他のファイル]が|[$2 他のファイルがいくつか]}}存在しています。',
'api-error-duplicate-archive' => 'サイト上に同じ内容の{{PLURAL:$1|[$2 別のファイル]が|[$2 他のファイルがいくつか]}}既にありましたが、{{PLURAL:$1|それは|それらは}}削除されました。',
'api-error-duplicate-archive-popup-title' => '重複した{{PLURAL:$1|ファイル|ファイル群}}は削除済みです。',
'api-error-duplicate-popup-title' => '重複した{{PLURAL:$1|ファイル|ファイル群}}です。',
'api-error-empty-file' => '送信されたファイルは空でした。',
'api-error-emptypage' => '内容がないページの新規作成は許可されていません。',
'api-error-fetchfileerror' => '内部エラー: ファイルを取得する際に問題が発生しました。',
'api-error-fileexists-forbidden' => '「$1」という名前のファイルは存在しており、上書きはできません。',
'api-error-fileexists-shared-forbidden' => '「$1」という名前のファイルは共有ファイルリポジトリに存在しており、上書きはできません。',
'api-error-file-too-large' => '送信されたファイルは大きすぎます。',
'api-error-filename-tooshort' => 'ファイル名が短すぎます。',
'api-error-filetype-banned' => 'この形式のファイルは禁止されています。',
'api-error-filetype-banned-type' => '$1{{PLURAL:$4|は許可されていないファイル形式です}}。許可されている{{PLURAL:$3|ファイル形式}}は$2です。',
'api-error-filetype-missing' => 'ファイルに拡張子がありません。',
'api-error-hookaborted' => '拡張機能のフックによって、修正が中断されました。',
'api-error-http' => '内部エラー: サーバーに接続できませんでした。',
'api-error-illegal-filename' => 'ファイル名が許可されていません。',
'api-error-internal-error' => '内部エラー: ウィキ上でアップロードを処理する際に問題が発生しました。',
'api-error-invalid-file-key' => '内部エラー: 一時格納場所にファイルが見つかりませんでした。',
'api-error-missingparam' => '内部エラー: リクエストのパラメーターが足りません。',
'api-error-missingresult' => '内部エラー: 複製に成功したかどうか判断できませんでした。',
'api-error-mustbeloggedin' => 'ファイルをアップロードするにはログインする必要があります。',
'api-error-mustbeposted' => '内部エラー: リクエストは HTTP の POST メソッドである必要があります。',
'api-error-noimageinfo' => 'アップロードには成功しましたが、サーバーはファイルに関する情報を返しませんでした。',
'api-error-nomodule' => '内部エラー: アップロードを処理するモジュールが設定されていません。',
'api-error-ok-but-empty' => '内部エラー: サーバーからの応答がありません。',
'api-error-overwrite' => '既存のファイルへの上書きは許可されていません。',
'api-error-stashfailed' => '内部エラー: サーバーは一時ファイルを格納できませんでした。',
'api-error-publishfailed' => '内部エラー: サーバーは一時ファイルを発行できませんでした。',
'api-error-timeout' => 'サーバーが決められた時間内に応答しませんでした。',
'api-error-unclassified' => '不明なエラーが発生しました。',
'api-error-unknown-code' => '不明なエラー:「$1」',
'api-error-unknown-error' => '内部エラー: ファイルをアップロードする際に問題が発生しました。',
'api-error-unknown-warning' => '不明な警告:「$1」',
'api-error-unknownerror' => '不明なエラー:「$1」',
'api-error-uploaddisabled' => 'このウィキではアップロードは無効になっています。',
'api-error-verification-error' => 'このファイルは壊れているか、間違った拡張子になっています。',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|秒}}',
'duration-minutes' => '$1 {{PLURAL:$1|分}}',
'duration-hours' => '$1 {{PLURAL:$1|時間}}',
'duration-days' => '$1 {{PLURAL:$1|日}}',
'duration-weeks' => '$1 {{PLURAL:$1|週間}}',
'duration-years' => '$1 {{PLURAL:$1|年}}',
'duration-decades' => '$1{{PLURAL:$1|0 年}}',
'duration-centuries' => '$1 {{PLURAL:$1|世紀}}',
'duration-millennia' => '$1{{PLURAL:$1|,000 年}}',

# Image rotation
'rotate-comment' => '画像を時計回りに $1 {{PLURAL:$1|度}}回転',

# Limit report
'limitreport-title' => 'パーサーのプロファイリング データ:',
'limitreport-cputime' => 'CPU 時間',
'limitreport-cputime-value' => '$1 {{PLURAL:$1|秒}}',
'limitreport-walltime' => '実時間',
'limitreport-walltime-value' => '$1 {{PLURAL:$1|秒}}',
'limitreport-ppvisitednodes' => 'プリプロセッサが訪問したノード数',
'limitreport-ppgeneratednodes' => 'プリプロセッサが生成したノード数',
'limitreport-postexpandincludesize' => '参照読み込みの展開後のサイズ',
'limitreport-postexpandincludesize-value' => '$1/$2 {{PLURAL:$2|バイト}}',
'limitreport-templateargumentsize' => 'テンプレート引数のサイズ',
'limitreport-templateargumentsize-value' => '$1/$2 {{PLURAL:$2|バイト}}',
'limitreport-expansiondepth' => '展開の最大深さ',
'limitreport-expensivefunctioncount' => '高負荷パーサー関数の数',

);
