<?php

return [

    'column_manager' => [

        'heading' => 'Бағандар',

        'actions' => [

            'apply' => [
                'label' => 'Бағандарды қолдану',
            ],

            'reset' => [
                'label' => 'Ысыру',
            ],

        ],

    ],

    'columns' => [

        'actions' => [
            'label' => 'Әрекет|Әрекеттер',
        ],

        'select' => [

            'loading_message' => 'Жүктелуде...',

            'no_options_message' => 'Қолжетімді нұсқалар жоқ.',

            'no_search_results_message' => 'Сұранысыңызға сәйкес нұсқа табылмады.',

            'placeholder' => 'Мән таңдаңыз',

            'searching_message' => 'Ізделуде...',

            'search_prompt' => 'Іздеу үшін мәтін теріңіз...',

        ],

        'text' => [

            'actions' => [
                'collapse_list' => ':count жасыру',
                'expand_list' => 'Тағы :count көрсету',
            ],

            'more_list_items' => 'және тағы :count',

        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'Топтық әрекеттер үшін барлық элементтерді таңдау/алып тастау.',
        ],

        'bulk_select_record' => [
            'label' => 'Топтық әрекеттер үшін :key таңдау/алып тастау.',
        ],

        'bulk_select_group' => [
            'label' => 'Топтық әрекеттер үшін :title қорытындысын таңдау/алып тастау.',
        ],

        'search' => [
            'label' => 'Іздеу',
            'placeholder' => 'Іздеу',
            'indicator' => 'Іздеу',
        ],

    ],

    'summary' => [

        'heading' => 'Қорытынды',

        'subheadings' => [
            'all' => 'Барлық :label',
            'group' => ':group қорытындысы',
            'page' => 'Осы бет',
        ],

        'summarizers' => [

            'average' => [
                'label' => 'Орташа',
            ],

            'count' => [
                'label' => 'Саны',
            ],

            'sum' => [
                'label' => 'Қосынды',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => 'Ретті сақтау',
        ],

        'enable_reordering' => [
            'label' => 'Ретін өзгерту',
        ],

        'filter' => [
            'label' => 'Сүзгі',
        ],

        'group' => [
            'label' => 'Топтау',
        ],

        'open_bulk_actions' => [
            'label' => 'Әрекеттерді ашу',
        ],

        'column_manager' => [
            'label' => 'Бағандарды ауыстыру',
        ],

    ],

    'empty' => [

        'heading' => ':model табылмады',

        'description' => 'Бастау үшін :model қосыңыз.',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => 'Сүзгілерді қолдану',
            ],

            'remove' => [
                'label' => 'Сүзгіні алып тастау',
            ],

            'remove_all' => [
                'label' => 'Сүзгілерді тазарту',
                'tooltip' => 'Сүзгілерді тазарту',
            ],

            'reset' => [
                'label' => 'Ысыру',
            ],

        ],

        'heading' => 'Сүзгілер',

        'indicator' => 'Белсенді сүзгілер',

        'multi_select' => [
            'placeholder' => 'Барлығы',
        ],

        'select' => [

            'placeholder' => 'Барлығы',

            'relationship' => [
                'empty_option_label' => 'Жоқ',
            ],

        ],

        'trashed' => [

            'label' => 'Жойылған жазбалар',

            'only_trashed' => 'Тек жойылған жазбалар',

            'with_trashed' => 'Жойылған жазбалармен',

            'without_trashed' => 'Жойылған жазбаларсыз',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => 'Бойынша топтау',
                'placeholder' => 'Бойынша топтау',
            ],

            'direction' => [

                'label' => 'Бағыты',

                'options' => [
                    'asc' => 'Өсу бойынша',
                    'desc' => 'Кему бойынша',
                ],

            ],

        ],

    ],

    'reorder_indicator' => 'Ретін өзгерту үшін жазбаларды сүйреңіз.',

    'selection_indicator' => [

        'selected_count' => ':count жазба таңдалды',

        'actions' => [

            'select_all' => [
                'label' => 'Барлық :count таңдау',
            ],

            'deselect_all' => [
                'label' => 'Барлық таңдауды алып тастау',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Сұрыптау',
            ],

            'direction' => [

                'label' => 'Бағыты',

                'options' => [
                    'asc' => 'Өсу бойынша',
                    'desc' => 'Кему бойынша',
                ],

            ],

        ],

    ],

    'default_model_label' => 'жазба',

];
