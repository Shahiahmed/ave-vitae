<?php

return [

    'select' => [

        'actions' => [

            'create_option' => [

                'label' => 'Қосу',

                'modal' => [

                    'heading' => 'Қосу',

                    'actions' => [

                        'create' => [
                            'label' => 'Қосу',
                        ],

                        'create_another' => [
                            'label' => 'Қосу және тағы бірін қосу',
                        ],

                    ],

                ],

            ],

            'edit_option' => [

                'label' => 'Өңдеу',

                'modal' => [

                    'heading' => 'Өңдеу',

                    'actions' => [

                        'save' => [
                            'label' => 'Сақтау',
                        ],

                    ],

                ],

            ],

        ],

        'boolean' => [
            'true' => 'Иә',
            'false' => 'Жоқ',
        ],

        'loading_message' => 'Жүктелуде...',

        'max_items_message' => 'Тек :count таңдауға болады.',

        'no_options_message' => 'Қолжетімді нұсқалар жоқ.',

        'no_search_results_message' => 'Сұранысыңызға сәйкес нұсқа табылмады.',

        'placeholder' => 'Нұсқаны таңдаңыз',

        'searching_message' => 'Ізделуде...',

        'search_prompt' => 'Іздеу үшін мәтін теріңіз...',

    ],

    'radio' => [

        'boolean' => [
            'true' => 'Иә',
            'false' => 'Жоқ',
        ],

    ],

];
