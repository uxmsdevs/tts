<?php namespace Uxms\Tts\Models;

use October\Rain\Database\Model;

/**
 * Uxms Google TTS Settings Model
 *
 * @package uxms\tts
 * @author Uxms Devs
 */
class Configs extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $rules = [];
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'uxms_tts_configs';
    public $settingsFields = 'fields.yaml';
}
