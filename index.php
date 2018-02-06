<?php


/**
 * it's included a basic data !
 * #Tip 
 * * please change  "/home/javadkhof/.translate/" it's a local folder to save Pronunciation Word downloaded from translate.google.com
 * * yandex_key is yandex translate api key . you can use your private key or use my key ! 
 * 
 * @var array
 */
$config = 
[
	"directory" 	=> "/home/javadkhof/.translate/",
	"from_lang" 	=>	"en",
	"to_lang" 		=>	"fa",
	"yandex_key"	=>	"trnsl.1.1.20171216T094142Z.fba71c4aff74bfce.b7e9e96058572f41d56be9a6631c003be7126d01"
];


/**
 * it's a function just for use yandex translate api....; just get a text and return a text if yandex response is OK (200) else get null. 
 * @param  [type] $_text  
 * @return [type]        
 */
function yandex($_text)
{
	global $config;
	$text = trim($_text);
	$url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key={$config['yandex_key']}&text=$text&lang={$config['from_lang']}-{$config['to_lang']}";
	$json = file_get_contents($url);
	$decode = json_decode($json);
	if ($decode->code == 200) 
	{
		return $decode->text["0"];
	}
	return null;
}

/**
 * "xsel" to get selected text
 * "notify-send" to show error or translated text in notification service (just tested in ubuntu);
 * "wget" to download Pronunciation from google
 * "mpv" to play word's Pronunciation
 * @return boolean 
 */
function get_voice()
{
	global $config;
	if ( empty(shell_exec('xsel -o')) ) 
	{
		return shell_exec("notify-send -t 5000 'please select text'");	
	}
	if ( empty($config["directory"]) ) 
	{
		return shell_exec("notify-send -t 5000 'please check configure var'");	
	}

	$text = shell_exec('xsel -o');
	if (file_exists($config['directory'].$text.'.mp3') === true) 
	{
		$answer = yandex($text);
		shell_exec("notify-send -t 5000 '{$text}' '{$answer}'");
		shell_exec("mpv ".$config['directory'].$text.'.mp3');
		return true;
	}
	else
	{
		$shell = "wget -q -U Mozilla \"https://translate.google.com/translate_tts?ie=UTF-8&total=1&idx=0&client=tw-ob&tl={$config['from_lang']}&q={$text}&textlen=4\" -O {$config["directory"]}{$text}.mp3";
		shell_exec($shell);
		shell_exec("mpv ".$config['directory'].$text.'.mp3');
		$answer = yandex($text);
		shell_exec("notify-send -t 5000 '{$text}' '{$answer}'");
		return true;
	}
	
}


get_voice();

