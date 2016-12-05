<?php
//上記の（※4）でダウンロードしたファイルから src/Google/autoload.php を読み込み
require_once realpath(dirname(__FILE__).'/〜/src/Google/autoload.php');　

$calendarId='unagi.tabetai.unachan@gmail.com';　//上記の（※3）でメモしたカレンダーID

$client_email = 'fm-shift@crack-flight-151311.iam.gserviceaccount.com'; //上記の（※2）でメモしたサービスアカウントID（長いけどそのまま全部）
$private_key = file_get_contents('/path/My Project-42f2980c0869.p12'); //上記の（※1）でダウンロードした秘密鍵ファイルを指定します
$scopes = array('https://www.googleapis.com/auth/calendar');//カレンダー書き込み時に必要なscope（下記で説明します）
$credentials = new Google_Auth_AssertionCredentials(
    $client_email,
    $scopes,
    $private_key
);

$client = new Google_Client();
$client->setAssertionCredentials($credentials);
if ($client->getAuth()->isAccessTokenExpired()) {
  $client->getAuth()->refreshTokenWithAssertion();
}

$service = new Google_Service_Calendar($client);

//以下がカレンダーに登録する内容です。（下記で説明します）
$event = new Google_Service_Calendar_Event(array(
  'summary' => 'テストの予定を登録するよ',　//予定のタイトル
  'start' => array(
    'dateTime' => '2016-05-28T09:00:00+09:00',// 開始日時
    'timeZone' => 'Asia/Tokyo',
  ),
  'end' => array(
    'dateTime' => '2016-05-28T18:00:00+09:00', // 終了日時
    'timeZone' => 'Asia/Tokyo',
  ),
));

$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);
