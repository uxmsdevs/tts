<?php namespace Uxms\Tts\Components;

use File;
use Cms\Classes\ComponentBase;
use Uxms\Tts\Models\Configs;
use Uxms\Tts\Classes\Tts;


class UxmsTTS extends ComponentBase
{
    /* Holds TTS Class instance */
    public $tts;

    public $lang;
    public $sentence;

    public function componentDetails()
    {
        return [
            'name'          => 'uxms.tts::lang.app.name',
            'description'   => 'uxms.tts::lang.app.desc'
        ];
    }

    public function onRun()
    {
        $this->tts = new Tts();
    }

    public function onRender()
    {
        $this->lang = $this->property('lang');
        $this->sentence = $this->property('sentence');

        return $this->speak($this->lang, $this->sentence);
    }

    public function speak($lang = 'en', $text = 'text not provided')
    {
        // Create temp path
        $uxmsTtsTempPath = temp_path(Configs::get('temp_name'));

        if (!File::isDirectory($uxmsTtsTempPath)) {
            if (!File::makeDirectory($uxmsTtsTempPath))
                throw new ApplicationException('Unable to create temp directory: '.$uxmsTtsTempPath);
        }

        // Create storage path
        $uxmsTtsStoragePath = storage_path().'/app/'.Configs::get('final_audio_folder_name');

        if (!File::isDirectory($uxmsTtsStoragePath)) {
            if (!File::makeDirectory($uxmsTtsStoragePath))
                throw new ApplicationException('Unable to create storage directory: '.$uxmsTtsStoragePath);
        }

        // Check if saved audios will be using and if it exists in stor dir
        if (Configs::get('use_saved_files')) {
            if (File::exists($uxmsTtsStoragePath.'/'.md5($text).'.mp3')) {
                return $this->tts->getAudioElementOrUri(md5($text));
            }

        }

        // Lets create the speech
        $this->tts
            ->text($lang, $text)
            ->saveFile(false);

        // Do not forget to activate puging from settings..
        if (Configs::get('purge_temp'))
            $this->tts->clearTemp();

        return $this->tts->getAudioElementOrUri();
    }

}
