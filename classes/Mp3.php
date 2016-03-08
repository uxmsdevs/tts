<?php namespace Uxms\Tts\Classes;

use File;


class Mp3
{
    public $fileAsStack;

    // Create a new mp3
    public function __construct($path)
    {
        $this->fileAsStack = File::get($path);
    }

    // Put an mp3 behind the stack
    public function addFileToStack($mp3)
    {
        $this->fileAsStack .= $mp3->fileAsStack;
    }

    // Remove the ID3 tags
    public function removeId3Tags()
    {
        // Remove start stuff...
        $s = $start = $this->getStartPointOfFile();

        if ($s === false) {
            return false;
        } else {
            $this->fileAsStack = substr($this->fileAsStack, $start);
        }

        // Remove end tag stuff
        $end = $this->getIdvEnd();
        if ($end !== false)
            $this->fileAsStack = substr($this->fileAsStack, 0, (strlen($this->fileAsStack) - 129));

        return $this;
    }

    // Calculate where's the beginning of the sound file
    protected function getStartPointOfFile()
    {
        $strlen = strlen($this->fileAsStack);

        for ($i = 0; $i < $strlen; $i++) {
            $v = substr($this->fileAsStack, $i, 1);
            $value = ord($v);
            if ($value == 255)
                return $i;
        }
        return true;
    }

    // Calculate where's the end of the sound file
    protected function getIdvEnd()
    {
        $strlen = strlen($this->fileAsStack);
        $str = substr($this->fileAsStack, ($strlen - 128));
        $str1 = substr($str, 0, 3);

        if (strtolower($str1) == strtolower('TAG')) {
            return $str;
        } else {
            return false;
        }
    }

    // Display an error
    protected function showError($msg)
    {
        throw new ApplicationException('audio file error: '.$msg);
    }


    // Save the new mp3
    public function saveFile($fileName = '')
    {
        try {
            if ($this->fileAsStack)
                File::put($fileName, $this->fileAsStack);

        } catch (\Exception $e) {
            print_r($e->getMessage());
        }

        return $this;
    }
}
