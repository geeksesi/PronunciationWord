<?php

$config = 
[
	"directory" 	=> "/home/javadkhof/.translate/",
	"db_name"		=> "translate.db",
	"from_lang" 	=>	"en",
	"to_lang" 		=>	"fa",
	"table_name"	=> 	"words",
	"yandex_key"	=>	"trnsl.1.1.20171216T094142Z.fba71c4aff74bfce.b7e9e96058572f41d56be9a6631c003be7126d01"
];
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


function database($_text, $_type)
{
    global $config;
	if (!isset($_text)) 
	{
		return shell_exec("notify-send -t 5000 'script is very bad '");
	}
	if ( ! file_exists($config["directory"].$config["db_name"]) ) 
	{
		$dir = "sqlite:".$config["directory"].$config["db_name"];
		$db = new PDO($dir) or die(shell_exec("notify-send -t 5000 'can not open databases'"));
		$create_table = "CREATE TABLE ".$config["table_name"]."(".
		"id INTEGER PRIMARY KEY AUTOINCREMENT,
		 {$config['from_lang']} text null,
		 {$config['to_lang']} text null".
		 ");";
		 if ($db->exec($create_table) === false) 
		 {
		 	$err = $db->errorInfo();
		 	return shell_exec("notify-send -t 5000 'sheet database ...!...'");
		 }
	}
	else
	{
		$dir = "sqlite:".$config["directory"].$config["db_name"];
		$db = new PDO($dir) or die(shell_exec("notify-send -t 5000 'can not open databases'"));
	}
	if ($_type == "set")
	{
		if (!isset($text[0]) || !isset($text[1])) 
		{
			return shell_exec("notify-send -t 5000 'please check the text array ..!..'");
		}
		$query_set = "INSERT INTO ".$config["db_name"]."(".$config['from_lang'].",".$config['to_lang'].") VALUES (".'{$text[0]}'.",".'{$text[1]}'.")";
		$query_exec = $db->exec($query_set);
		if ($query_exec === false) 
		{
			return shell_exec("notify-send -t 5000 'query set is false ..!..'");
		}
		return true;
	}
	elseif ($_type == "get") 
	{	
		$query_get = "SELECT * FROM {$config['table_name']} WHERE {$config['from_lang']} = '{$_text}'";
		$result = $db->query($query_get);
		if ($result->fetchColumn() >0) 
		{
			foreach ($result as $key => $value) 
			{
				$answer = $value[$config['to_lang']];
			}
			return $answer;
		}
		return null;
	}
	else
	{
		return shell_exec("notify-send -t 5000 'please check my type'");
	}
	return shell_exec("notify-send -t 5000 'not good db'");
	return null;

}
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
		// $answer = database($text,"get");
		// shell_exec("echo \"{$answer}\" | xclip -selection clipboard");
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
		// if ($answer !== null) 
		// {
		// 	database([$text,$answer],"set");
		// }
		// shell_exec("echo '{$answer}' | xclip -selection clipboard");
		shell_exec("notify-send -t 5000 '{$text}' '{$answer}'");
		return true;
	}
	
}
get_voice();

