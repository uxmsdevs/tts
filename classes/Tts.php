<?php namespace Uxms\Tts\Classes;

use File;
use Uxms\Tts\Models\Configs;
use Uxms\Tts\Classes\Mp3;

class Tts
{
	/* URL for google translate */
	public $googleTranslateBaseURL = '';

	/* Handles the values of returned MP3 encoded data. */
	public $soundDatas = [];

	/* These IPs are for spoofing the GET header */
	public $ipForHeader = [];

	/* Collection of URLs for TTS Ops */
	public $urlsForTTS = [];

	/* Sentences array which provided by splitting the paragraph. */
	public $sentences = [];

	/* Total count of sentence */
	public $sentenceCount = 0;

	/* Md5 of text for filename */
	public $fileNameAsMd5;

	public function __construct() {
		$this->googleTranslateBaseURL = Configs::get('translate_base_url');
		$this->ipForHeader = Configs::get('ip_for_header');
	}

	/**
	 * Text for converting to voice.
	 *
	 * NOTE: urlencode method must be applied to the $text here
	 *
	 * @param string $lang
	 * @param string $text
	 * @return Tts
	 */
	public function text($lang = 'en', $text = 'text not provided') {

		$this->fileNameAsMd5 = md5($text);

		// Split paragraph into sentences
		$this->splitIntoSentences($text, ".");

		for ($i = 0; $i < $this->sentenceCount; $i++) {
			$buildQuery = [
				'ie'		=> 'utf-8',
				'tl'		=> $lang,
				'q'			=> $this->sentences[$i],
				'total'		=> $this->sentenceCount,
				'idx'		=> $i,
				'textlen'	=> strlen($this->sentences[$i]),
				'tk'		=> 765712,
				'client'	=> 't',
				'ttsspeed'	=> 1
			];

			$this->urlsForTTS[] = $this->googleTranslateBaseURL . '?' . http_build_query($buildQuery);
		}

		return $this;
	}

	/**
	 * Split a paragraph into sentences by punctuation such as !?.;" semantically
	 *
	 * @param string $str 		String to split
	 * @param string $eosChars	Characters which represent the end of the sentence. Should be a string with no spaces (".,!?")
	 * @return Tts
	 */
	private function splitIntoSentences($str, $eosChars) {
		$inside_quotes = false;
		$buffer = "";

		$str = strip_tags($str);

		for ($i = 0; $i < strlen($str); $i++) {
			$buffer .= $str[$i];
			if ($str[$i] === '"')
				$inside_quotes = !$inside_quotes;

			if (!$inside_quotes) {
				if (preg_match("/[$eosChars]/", $str[$i])) {
					// If string length is longer than 130 character
					if (strlen($buffer) > 130) {
						$parseLongSentence = explode("\n", wordwrap($buffer, 130));
						foreach ($parseLongSentence as $value) {
							$this->sentences[] = $value;
						}
					} else {
						$this->sentences[] = $buffer;
						$buffer = "";
					}
				}
			}
		}

		// If there's not eosChars matched, handle full sentence
		if (empty($this->sentences))
			$this->sentences[] = $str;

		// Define how much sentence we have
		$this->sentenceCount = count($this->sentences);

		return $this;
	}

	/**
	 * Saves the sound data as file
	 *
	 * First: Saves partial audios to temp dir
	 * Second: Saves combined .mp3 file to audios dir
	 *
	 * @param bool $clearTempFiles
	 * @return Tts
	 */
	public function saveFile($clearTempFiles = true) {
		$this->curlRequest();

		// Set temp & storage paths
		$uxmsTtsTempPath = temp_path(Configs::get('temp_name'));
		$uxmsTtsStoragePath = storage_path().'/app/'.Configs::get('final_audio_folder_name');

		try {
			// Save partial audios to temp dir
			for ($i = 0; $i < $this->sentenceCount; $i++) {
				File::put($uxmsTtsTempPath.'/'.$this->fileNameAsMd5.'_'.$i.'.mp3', $this->soundDatas[$i]);
			}

			// Prepare combined audio
			$lastFileOfStack = $this->sentenceCount - 1;
			if (!File::get($uxmsTtsTempPath.'/'.$this->fileNameAsMd5.'_'.$lastFileOfStack.'.mp3'))
				sleep(5);

			// Set up the first file
			$mp3 = new Mp3($uxmsTtsTempPath.'/'.$this->fileNameAsMd5.'_0.mp3');
			$mp3->removeId3Tags();

			// Generate the new mp3 file by combining each seperate file
			for ($i = 1; $i < $this->sentenceCount; ++$i) {
				$mp3AddtionalPath = $uxmsTtsTempPath.'/'.$this->fileNameAsMd5.'_'.$i.'.mp3';
				$mp3Additional = new Mp3($mp3AddtionalPath);

				$mp3->addFileToStack($mp3Additional);
				$mp3->removeId3Tags();
			}

			// Spit out the audio file!
			$mp3->saveFile($uxmsTtsStoragePath.'/'.$this->fileNameAsMd5.'.mp3');

			// If need to purge temp files
			if ($clearTempFiles)
				$this->clearTemp($this->fileNameAsMd5);
		} catch (\Exception $e) {
			print_r($e->getMessage());
		}

		return $this;
	}

	/**
	 * Handles the cURL Execution
	 *
	 * @param bool $keepCookieJar
	 * @return Tts
	 */
	private function curlRequest($keepCookieJar = false) {
		for ($i = 0; $i < $this->sentenceCount; $i++) {
			$curl = curl_init();

			$options = [
				CURLOPT_URL				=> $this->urlsForTTS[$i],
				CURLOPT_ENCODING		=> '',
				CURLOPT_USERAGENT		=> 'Googlebot/2.1 (+http://www.google.com/bot.html)',
				CURLOPT_REFERER 		=> "",
				// CURLOPT_AUTOREFERER		=> 0,
				CURLOPT_FORBID_REUSE	=> 1,
				CURLOPT_FRESH_CONNECT	=> 1,
				CURLOPT_RETURNTRANSFER	=> 1,
				CURLOPT_TIMEOUT 		=> 60,
				CURLOPT_CONNECTTIMEOUT	=> 60,
				// CURLOPT_SSL_VERIFYHOST	=> 0,
				// CURLOPT_SSL_VERIFYPEER	=> 0
			];

			// Create And Save Cookies
			if ($keepCookieJar) {
				$jarName = dirname(__FILE__).'/cookie.jar';

				$options = [
					CURLOPT_COOKIE 		=> 'test=uxms',
					CURLOPT_COOKIEJAR	=> $jarName,
					CURLOPT_COOKIEFILE	=> $jarName
				];
			}

			curl_setopt_array($curl, $options);
			$this->soundDatas[$i] = curl_exec($curl);
			curl_close($curl);
		}

		return $this;
	}

	/**
	 * Outputs the sound data
	 *
	 * @return Tts
	 */
	public function finalSpeak() {
		$this->curlRequest();

		header("Content-Type: audio/mpeg");
		echo $this->soundDatas[0];

		return $this;
	}

	/**
	 * Returns HTML5 Audio Element
	 *
	 * @param string $fileName
	 * @return Tts
	 */
	public function getAudioElementOrUri($fileName = '') {
		$fileName = ($fileName ? $fileName : $this->fileNameAsMd5);

		$uxmsTtsStoragePath = 'storage/app/'.Configs::get('final_audio_folder_name');
		$storageFileURI = url( $uxmsTtsStoragePath.'/'.$fileName.'.mp3' );

		$audioElement = '
			<audio controls>
				<source src="'.$storageFileURI.'" type="audio/mpeg">
				Your browser does not support the audio element.
			</audio>
		';

		return (Configs::get('return_element_or_uri') ? $audioElement : $storageFileURI);
	}

	/**
	 * Clears the temp dir
	 *
	 * @param string $which
	 * @return Tts
	 */
	public function clearTemp($which = '*') {
		$uxmsTtsTempPath = temp_path(Configs::get('temp_name'));

		try {
			if ($which == '*') {
				array_map('unlink', glob( $uxmsTtsTempPath."/{$which}") );
			} else {
				array_map('unlink', glob( $uxmsTtsTempPath."/{$which}_*") );
			}
		} catch (\Exception $e) {

		}

		return $this;
	}

}
