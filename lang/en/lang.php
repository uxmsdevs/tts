<?php

return [
    'app' => [
        'name'          => 'Google TTS',
        'desc'          => 'Convert any string (or article) into speech without length limitation',
        'setting_desc'  => 'Configure Settings for Google TTS',
        'menu_label'    => 'Google TTS'
    ],

    'generic' => [
        'return_relations' => 'Return to the list'
    ],

    'settings' => [
        'use_saved_files' => [
            'title' => 'Use Local Copy of Previously Saved Sentences',
            'desc'  => 'If you want to play audios from saved files, activate this option (Activating this option is recommended)'
        ],
        'purge_temp' => [
            'title' => 'Purge -Plugin Specific- Temp Directory',
            'desc'  => 'You should purge temp directory. Plugin uses temp dir for long length sentences\' partial audios and they won\'t be need after successful operation'
        ],
        'return_element_or_uri' => [
            'title' => 'Return Audio Element / Audio URI',
            'desc'  => 'Activate this if you want to get Audio element, or deactivate for getting URI of saved audio only'
        ],
        'translate_base_url' => [
            'title' => 'Google Translate Base URL',
            'desc'  => 'Probably this will be same all the time..'
        ],
        'ip_for_header' => [
            'title' => 'IP for Spoofing the Header (Experimental)',
            'desc'  => 'You can look at plugin documentation for detailed instructions'
        ],
        'temp_name' => [
            'title' => 'Temporary Folder for Google Translate',
            'desc'  => 'This folder will be used when combining too long sentences. Do not use slashes (/)'
        ],
        'final_audio_folder_name' => [
            'title' => 'Folder Name where Audio Files Saved',
            'desc'  => 'This folder will be used for storing the final (combined) audios'
        ]
    ]

];
