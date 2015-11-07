# Google TTS (Text-to-Speech)
This plugin can provide a text's speech using Google Translate


## Performance
> **NOTE:** Fetching and combining 2.000 characters long sentence tooks ~8 sec. It depends on Google's response time mostly


## Working logic
Bear in mind that as of this is Google's paid service as API actually. You are limited to ~200 characters of the text in SAME request hence using GET method

So, this script uses multiple requests for providing long length paragraphs

> **NOTE:** TTS limitation of character count is not relevant to our plugin, stems from Google. We do it like Google's way and now you can get TTS as the Google did actually on Translate


## Storage Folder Structure
* For temporary saving long audios partials, `temp_path() / YOUR_FOLDER_NAME` will be used.
* For saving final audio for public, `storage_path() / app / YOUR_FOLDER_NAME` will be used.

> **NOTE:** Temp folder should be purge. You can activate purge by Google TTS Settings


## Usage
- Go to Settings page, find `Google TTS` in CMS section. Edit configs if you need

- Second, you need to add `Google TTS` Component to a page which you need to show audio element or URI of the saved audio. General logic is:

	{% component 'TTS' lang='LANG_CODE' sentence='SENTENCE' %}

If you preferred using as audio element in settings, For example:

	{% component 'TTS' lang='en' sentence='One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. "What has happened!?" he asked himself. "I... don\'t know." said Samsa, "Maybe this is a bad dream." He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections.' %}

this returns something like:

	<audio controls="">
		<source 
			src="http://127.0.0.1/octo_exps/storage/app/tts/1e035ad8b2de1be69696950953b28c66.mp3" 
			type="audio/mpeg">
		Your browser does not support the audio element.
	</audio>

If you preferred using for URI only, For example: 

	<a href="{% componeny 'TTS' lang='en' sentence='One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin. "What has happened!?" he asked himself. "I... don\'t know." said Samsa, "Maybe this is a bad dream." He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff sections.' %}">Go to Speech</a>

this returns something like:

	<a href="http://127.0.0.1/octo_exps/storage/app/tts/1e035ad8b2de1be69696950953b28c66.mp3">Go to Speech</a>


## Which languages I can use?
You can use following languages for "lang" parameter:

	'ar'	=> 'arabic',
	'bg'	=> 'bulgarian',
	'ca'	=> 'catalan',
	'zh'	=> 'chinese',
	'zh-CN'	=> 'chinese_simplified',
	'zh-TW'	=> 'chinese_traditional',
	'hr'	=> 'croatian',
	'cs'	=> 'czech',
	'da'	=> 'danish',
	'nl'	=> 'dutch',
	'en'	=> 'english',
	'fi'	=> 'finnish',
	'fr'	=> 'french',
	'de'	=> 'german',
	'el'	=> 'greek',
	'iw'	=> 'hebrew',
	'hi'	=> 'hindi',
	'id'	=> 'indonesian',
	'it'	=> 'italian',
	'ja'	=> 'japanese',
	'ko'	=> 'korean',
	'lv'	=> 'latvian',
	'lt'	=> 'lithuanian',
	'no'	=> 'norwegian',
	'pl'	=> 'polish',
	'pt-PT'	=> 'portuguese',
	'ro'	=> 'romanian',
	'ru'	=> 'russian',
	'sr'	=> 'serbian',
	'sk'	=> 'slovak',
	'sl'	=> 'slovenian',
	'es'	=> 'spanish',
	'sv'	=> 'swedish',
	'tr'	=> 'turkish',
	'uk'	=> 'ukrainian',
	'vi'	=> 'vietnamese'


## About Spoofing the Header:
It's experimental for this version of plugin. Will be updated in next versions.


## Some of the millions of Google's IP Ranges for Headers:
	64.233.160.0    - 64.233.191.255
	66.102.0.0      - 66.102.15.255
	66.249.64.0		- 66.249.95.255
	72.14.192.0		- 72.14.255.255
	74.125.0.0      - 74.125.255.255
	209.85.128.0	- 209.85.255.255
	216.239.32.0	- 216.239.63.255


## Thanks for icon:
speech by Deivid Sáenz from the Noun Project

