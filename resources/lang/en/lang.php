<?php

return [
    'actions' => [
        'label' => 'Locale'
    ],
    'locales' => 'Locales',
    'name' => [
        'label' => 'Name',
        'helper' => 'Use to display the helper text below the entry',
    ],
    'description' => [
        'label' => 'Description',
        'helper' => 'Use to display the helper text below the entry',
    ],
    'searchable' => [
        'label' => 'Searchable',
    ],
    'filterable' => [
        'label' => 'Filterable',
    ],
    'required' => [
        'label' => 'Required',
    ],
    'translation' => [
        'title' => 'Auto translation',
        'source' => 'Source',
        'source_text' => 'Text to Translate',
        'target' => 'Target',
        'translated_text' => 'Translated Text',
        'submit' => 'Apply',
        'success' => [
            'title' => 'Successfully translated from :source to :target',
            'content' => 'Translated text: ":text"',
        ],
        'error' => [
            'title' => 'Error translated from :source to :target',
            'content' => 'Exception: :exception',
        ],
    ]
];
