<?php

define('API_KEY','543444052:AAEKKTq4G4Y7rAS179CajiRkpeLpWcpsads');

function makereq($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = "https://api.telegram.org/bot".API_KEY."/".$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
//=========
$chat_id = $update->message->chat->id;
$boolean = file_get_contents('booleans.txt');
$booleans= explode("\n",$boolean);
$done = file_get_contents('done.txt');
$start = file_get_contents('start.txt');
$message_id = $update->message->message_id;
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$username = $update->message->from->username;
$textmessage = isset($update->message->text)?$update->message->text:'';
$rpto = $update->message->reply_to_message->forward_from->id;
$stickerid = $update->message->reply_to_message->sticker->file_id;
$photo = $update->message->photo;
$video = $update->message->video;
$sticker = $update->message->sticker;
$file = $update->message->document;
$music = $update->message->audio;
$voice = $update->message->voice;
$forward = $update->message->forward_from;
$admin = 364759538;
//-------
function SendMessage($ChatId, $TextMsg)
{
 makereq('sendMessage',[
'chat_id'=>$ChatId,
'text'=>$TextMsg,
'parse_mode'=>"MarkDown"
]);
}
function SendSticker($ChatId, $sticker_ID)
{
 makereq('sendSticker',[
'chat_id'=>$ChatId,
'sticker'=>$sticker_ID
]);
}
function Forward($KojaShe,$AzKoja,$KodomMSG)
{
makereq('ForwardMessage',[
'chat_id'=>$KojaShe,
'from_chat_id'=>$AzKoja,
'message_id'=>$KodomMSG
]);
}
function save($filename,$TXTdata)
	{
	$myfile = fopen($filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}

//------------

if($textmessage == '/start')
 if ($from_id == $admin) {
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"سلام
به ربات خودتان خوش آمدید 🌹

پنل مدیریتی 👇",
        'parse_mode'=>'MarkDown',
        'reply_markup'=>json_encode([
            'keyboard'=>[
              [
                ['text'=>"📊 Members"],['text'=>"🚫 Block List"]
              ],
	      [
                ['text'=>"📨 Send To All"],['text'=>"🗑 Clean Block List"]
              ],
	      [
	        ['text'=>"⚓️ Help"]
	      ]
            ],
            'resize_keyboard'=>true,
        ])
    ]));
 }
 else{
 
var_dump(makereq('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>" $start ",
        'parse_mode'=>'MarkDown',
        'resize_keyboard'=>true,
        'reply_markup'=>json_encode([
            'keyboard'=>[
       [
                ['text'=>"پروفایل👤"]
              ]
            ],
            'resize_keyboard'=>true,
        ])
    ]));
    $txxt = file_get_contents('member.txt');
$pmembersid= explode("\n",$txxt);
	if (!in_array($chat_id,$pmembersid)) {
		$aaddd = file_get_contents('member.txt');
		$aaddd .= $chat_id."
";
    	file_put_contents('member.txt',$aaddd);
}
 }

	elseif(strpos($textmessage , '/setprofile')!== false && $chat_id == $admin)
	{
		$javab = str_replace('/setprofile',"",$textmessage);
		if ($javab != "")
	{
	save("profile.txt","$javab");
	SendMessage($chat_id,"با موفقیت تغییریافت");
	}
	}
elseif($textmessage == 'پروفایل👤')
	{
	$profile = file_get_contents("profile.txt");
	Sendmessage($chat_id," $profile ");
	}
         elseif(strpos($textmessage , '/setdone')!== false && $chat_id == $admin)
	{
		$javab = str_replace('/setdone',"",$textmessage);
		if ($javab != "")
	{
	save("done.txt","$javab");
	SendMessage($chat_id,"پیام پیشفرض ربات به

$javab

تغییر یافت ✅");
	}
        }
  elseif(strpos($textmessage , '/setstart')!== false && $chat_id == $admin)
  {
    $javab = str_replace('/setstart',"",$textmessage);
    if ($javab != "")
  {
  save("start.txt","$javab");
  SendMessage($chat_id,"پیام شروع (استارت) ربات به

$javab

تغییر یافت ✅");
  }
  }

elseif($textmessage == '⚓️ Help')
if($chat_id == $admin){
	{
		Sendmessage($chat_id," 🔸مسدود کردن فرد
`/ban` (Reply)

🔸آزاد کردن فرد
`/unban` (Reply)

🔸تنظیم متن دکمه پروفایل
`/setprofile` (Text)

🔸تنظیم متن پیشفرض (Done)
`/setdone` (Text)

🔸تنظیم متن شروع
`/setstart` (Text)

✍🏻 Source By #DeViL
");
	}
}
else
	{
		Sendmessage($chat_id,"🔶راهنما ربات:
➖➖➖➖➖➖➖
Source By 》 @Me_DeViL
");
	}


elseif ($chat_id != $admin) {


    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);
$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id," $done ");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

    }
    }
      elseif (isset($message['contact'])) {

      if ( $chat_id != $admin) {

    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"شماره با موفقیت ارسال شد");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

}
    }
      }

	   elseif (isset($message['sticker'])) {

      if ( $chat_id != $admin) {

    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"استیکر با موفقیت ارسال شد");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

}
    }
      }


   elseif (isset($message['photo'])) {

      if ( $chat_id != $admin) {

    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"تصویر باموفقیت ارسال شد");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

}
    }
      }

         elseif (isset($message['voice'])) {

      if ( $chat_id != $admin) {

    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"ویس شما باموفقیت ارسال شد");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

}
    }
      }
               elseif (isset($message['video'])) {

      if ( $chat_id != $admin) {

    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"فیلم شما ارسال شد");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

}
    }
      }



	elseif($textmessage == '📊 Members' && $chat_id == $admin)
	{
		$txtt = file_get_contents('member.txt');
		$membersidd= explode("\n",$txtt);
		$mmemcount = count($membersidd) -1;
{
sendmessage($chat_id,"👥لیست اعضای ربات: \n\n🔸 $mmemcount عضو");
}
}

	elseif($textmessage == '🚫 Block List' && $chat_id == $admin){
		$txtt = file_get_contents('banlist.txt');
		$membersidd= explode("\n",$txtt);
		$mmemcount = count($membersidd) -1;
{
sendmessage($chat_id,"🔰لیست بلاک شده ها:\n\n🔹$mmemcount عضو بلاک شده اند");
}
}




                  elseif (isset($message['location'])) {

      if ( $chat_id != $admin) {

    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
Forward($admin,$chat_id,$message_id);
Sendmessage($chat_id,"مکان موردنظر باموفقیت ارسال شد");
}else{

Sendmessage($chat_id,"You Blocked !🚫");

}
    }
      }
            elseif($rpto != "" && $chat_id == $admin){
    	if($textmessage != "/ban" && $textmessage != "/unban")
    	{
sendmessage($rpto,"$textmessage");
sendmessage($chat_id,"🗣پیام شما با موفقیت به کاربر ارسال شد." );
    	}
    	else
    	{
    		if($textmessage == "/ban"){
    	$txtt = file_get_contents('banlist.txt');
		$banid= explode("\n",$txtt);
	if (!in_array($rpto,$banid)) {
		$addd = file_get_contents('banlist.txt');
		$addd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $addd);
		$addd .= $rpto."
";

    	file_put_contents('banlist.txt',$addd);
    	{
sendmessage($rpto,"Your Are Banned ! ⛔️");
sendmessage($chat_id,"User Banned ! 🚫");
        }
    		}
}
    	if($textmessage == "/unban"){
    	$txttt = file_get_contents('banlist.txt');
		$banidd= explode("\n",$txttt);
	if (in_array($rpto,$banidd)) {
		$adddd = file_get_contents('banlist.txt');
		$adddd = str_replace($rpto,"",$adddd);
		$adddd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $adddd);
    $adddd .="
";


		$banid= explode("\n",$adddd);
    if($banid[1]=="")
      $adddd = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $adddd);

    	file_put_contents('banlist.txt',$adddd);
}
sendmessage($rpto,"Your Are UnBanned ✅");
sendmessage($chat_id,"User UnBanned ✅");
    		}
    	}
	}


        elseif ($textmessage =="📨 Send To All"  && $chat_id == $admin | $booleans[0]=="false") {
	{
          sendmessage($chat_id,"لطفا پیام خود را ارسال کنید");
	}
      $boolean = file_get_contents('booleans.txt');
		  $booleans= explode("\n",$boolean);
	  	$addd = file_get_contents('banlist.txt');
	  	$addd = "true";
    	file_put_contents('booleans.txt',$addd);

    }
      elseif($chat_id == $admin && $booleans[0] == "true") {
    $texttoall = $textmessage;
		$ttxtt = file_get_contents('member.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			sendmessage($membersidd[$y],"💬: $texttoall \n\n  📨 همگانی");

		}
		$memcout = count($membersidd)-1;
	 	{
	 	Sendmessage($chat_id,"📬پیام شما به $memcout عضو ارسال شد.");
	 	}
         $addd = "false";
    	file_put_contents('booleans.txt',$addd);
    	}
 elseif($textmessage == '🗑 Clean Block List')
 if($chat_id == $admin){
 {
 file_put_contents('banlist.txt',$chat_id);
 Sendmessage($chat_id,"❌ Black List Cleaned!");
 }
}
?>
