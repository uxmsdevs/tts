<?php namespace Uxms\Tts;

use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Uxms\Tts\Models\Configs;


class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'uxms.tts::lang.app.name',
            'description' => 'uxms.tts::lang.app.desc',
            'author'      => 'uXMs Devs',
            'icon'        => 'icon-headphones',
            'homepage'    => 'https://uxms.net/'
        ];
    }

    public function registerPermissions()
    {
        return [
            'uxms.tts.settings' => ['label' => 'Access Settings', 'tab' => 'Uxms TTS']
        ];
    }

    public function registerComponents()
    {
        return [
            'Uxms\Tts\Components\UxmsTTS' => 'TTS'
        ];
    }

    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'uxms.tts::lang.app.name',
                'description' => 'uxms.tts::lang.app.setting_desc',
                'icon'        => 'icon-headphones',
                'class'       => 'Uxms\Tts\Models\Configs',
                'category'    => SettingsManager::CATEGORY_CMS,
                'order'       => 998
            ]
        ];
    }

    /**
     * The boot() method is called right before a request is routed
     */
    public function boot()
    {
        if (Configs::get('use_saved_files') == null)
            Configs::set('use_saved_files', '1');

        if (Configs::get('purge_temp') == null)
            Configs::set('purge_temp', '1');

        if (Configs::get('return_element_or_uri') == null)
            Configs::set('return_element_or_uri', '1');

        if (Configs::get('translate_base_url') == null)
            Configs::set('translate_base_url', 'http://translate.google.com/translate_tts');

        if (Configs::get('ip_for_header') == null)
            Configs::set('ip_for_header', '74.125.239.35');

        if (Configs::get('temp_name') == null)
            Configs::set('temp_name', 'uxms_tts');

        if (Configs::get('final_audio_folder_name') == null)
            Configs::set('final_audio_folder_name', 'tts');

    }

}
