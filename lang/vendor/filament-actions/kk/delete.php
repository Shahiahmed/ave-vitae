<?php

return [

    'single' => [

        'label' => 'Жою',

        'modal' => [

            'heading' => ':label жою',

            'actions' => [

                'delete' => [
                    'label' => 'Жою',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Жойылды',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Таңдалғанды жою',

        'modal' => [

            'heading' => 'Таңдалған :label жою',

            'actions' => [

                'delete' => [
                    'label' => 'Таңдалғанды жою',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Жойылды',
            ],

            'deleted_partial' => [
                'title' => ':total ішінен :count жойылды',
                'missing_authorization_failure_message' => ':count жоюға құқығыңыз жоқ.',
                'missing_processing_failure_message' => ':count жою мүмкін емес.',
            ],

            'deleted_none' => [
                'title' => 'Жою мүмкін болмады',
                'missing_authorization_failure_message' => ':count жоюға құқығыңыз жоқ.',
                'missing_processing_failure_message' => ':count жою мүмкін емес.',
            ],

        ],

    ],

];
