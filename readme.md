# Pronunciation Word
a simple php-cli script to play Pronunciation from translate.google.com and play it...

get word translate from "yandex" and show it in notification service (just tested in Ubuntu)  

# installation 
just clone it and install some package and change some text in code
## packages
- xsel
- mpv
- php-cli & php-cli
- wget 

## something should be change
- "directory" in $config array :: it's a directory to save Pronunciation file when downloaded form google
- "from_lang" and "to_lang" in $config array :: two variable to translate your text from a language to different language (default is : 'en' to 'fa'!   
- "yandex_key" in $config array :: it's the yandex translate api key... if you want to use your private api key can change it.

# Use
just select a text and call the index.php file like : `php index.php` or use `alias` or make `keyboard short key` 

# Errors
every text/errors show in notification 
- if selected text is empty "empty(shell_exec('xsel -o'))" 
- if "directory" in $config is not set ! (if you set it wron i can't help you ! so please careful)

# Tip 
- if script don't work on your gnu/linux system please tell me 
- i think this script can use in OSX if you have that please test it and edite this readme
- i have problem in "Gnome" when i want to make keyboard short key to use this script enything don't work ! if you know how fix it please help me :)  


# more
can find me in : 
- telegram : [telegram](https://t.me/geeksesi_xyz)
- twitter : [twitter](https://twitter.com/geeksesi)
- email : `geeksesi[at]gmail.com`

