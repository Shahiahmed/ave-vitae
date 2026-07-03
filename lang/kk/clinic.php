<?php

return [
    'nav' => [
        'management' => 'Басқару',
        'registry' => 'Тізілім',
    ],

    'department' => [
        'label' => 'Бөлім',
        'plural' => 'Бөлімдер',
        'name_ru' => 'Атауы (орыс.)',
        'name_kk' => 'Атауы (қаз.)',
        'is_active' => 'Белсенді',
    ],

    'user' => [
        'label' => 'Пайдаланушы',
        'plural' => 'Пайдаланушылар',
        'name' => 'Аты',
        'name_kk' => 'Аты (қаз.)',
        'name_zh' => 'Аты (қыт.)',
        'email' => 'Email',
        'password' => 'Құпиясөз',
        'roles' => 'Рөлдер',
        'department' => 'Бөлім',
    ],

    'patient' => [
        'label' => 'Пациент',
        'plural' => 'Пациенттер',
        'name_kk' => 'Аты-жөні (қаз. жазылуы)',
        'name_zh' => 'Аты-жөні (қыт. жазылуы)',
        'iin' => 'ЖСН',
        'phone' => 'Телефон',
        'birth_date' => 'Туған күні',
        'city' => 'Қала',
        'category' => 'Санаты',
        'notes' => 'Қосымша ақпарат',
        'registered_at' => 'Тіркелген күні',
    ],

    'appointment' => [
        'label' => 'Жазылу',
        'plural' => 'Жазылулар',
        'patient' => 'Пациент',
        'department' => 'Бөлім',
        'doctor' => 'Дәрігер',
        'doctor_any' => 'Дәрігерсіз (бөлімге)',
        'scheduled_at' => 'Қабылдау күні мен уақыты',
        'visit_status' => 'Визит күйі',
        'treatment_status' => 'Емдеу нәтижесі',
        'notes_kk' => 'Ескертпелер (қаз.)',
        'notes_zh' => 'Ескертпелер (қыт.)',
        'created_by' => 'Жасаған',
        'new_patient' => 'Жаңа пациент',
        'section_main' => 'Қабылдау',
        'section_notes' => 'Қабылдау ескертпелері',
        'filter_upcoming' => 'Алдағы',
        'filter_from' => 'Күнінен',
        'filter_to' => 'Күніне дейін',
    ],

    'today' => [
        'title' => 'Бүгін',
        'date' => 'Күні',
        'time' => 'Уақыты',
        'total' => 'Барлығы',
        'arrived' => 'Келді',
        'waiting' => 'Күтілуде',
        'no_show' => 'Келмеді',
    ],

    'my' => [
        'title' => 'Менің қабылдауларым',
        'complete' => 'Қабылдауды аяқтау',
        'completed_notice' => 'Қабылдау аяқталды',
        'filter_upcoming' => 'Бүгін және алдағы',
    ],

    'dashboard' => [
        'title' => 'Бақылау тақтасы',
        'today' => 'Бүгінгі жазылулар',
        'today_desc' => 'Ағымдағы күнге қабылдаулар',
        'arrived_week' => 'Апта ішінде келді',
        'no_show_week' => 'Апта ішінде келмеді',
        'new_patients_month' => 'Ай ішіндегі жаңа пациенттер',
    ],

    'empty' => [
        'today' => 'Бұл күнге жазылулар жоқ',
        'my' => 'Сізде әзірге қабылдаулар жоқ',
        'appointments' => 'Жазылулар жоқ',
        'patients' => 'Пациенттер әзірге жоқ',
    ],
];
