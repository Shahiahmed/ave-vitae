<?php

return [
    'nav' => [
        'management' => 'Управление',
        'registry' => 'Реестр',
    ],

    'department' => [
        'label' => 'Отделение',
        'plural' => 'Отделения',
        'name_ru' => 'Название (рус.)',
        'name_kk' => 'Название (каз.)',
        'name_zh' => 'Название (кит.)',
        'is_active' => 'Активно',
    ],

    'user' => [
        'label' => 'Пользователь',
        'plural' => 'Пользователи',
        'name' => 'Имя',
        'name_kk' => 'Имя (каз.)',
        'name_zh' => 'Имя (кит.)',
        'email' => 'Email',
        'password' => 'Пароль',
        'roles' => 'Роли',
        'department' => 'Отделение',
    ],

    'patient' => [
        'label' => 'Пациент',
        'plural' => 'Пациенты',
        'name_kk' => 'ФИО (каз. написание)',
        'name_zh' => 'ФИО (кит. написание)',
        'iin' => 'ИИН',
        'phone' => 'Телефон',
        'birth_date' => 'Дата рождения',
        'city' => 'Город',
        'category' => 'Категория',
        'notes' => 'Дополнительная информация',
        'registered_at' => 'Дата регистрации',
    ],

    'appointment' => [
        'label' => 'Запись',
        'plural' => 'Записи',
        'patient' => 'Пациент',
        'department' => 'Отделение',
        'doctor' => 'Врач',
        'doctor_any' => 'Без врача (на отделение)',
        'scheduled_at' => 'Дата и время приёма',
        'visit_status' => 'Статус визита',
        'treatment_status' => 'Результат лечения',
        'treatment_amount' => 'Сумма лечения (₸)',
        'notes_kk' => 'Заметки (каз.)',
        'notes_zh' => 'Заметки (кит.)',
        'created_by' => 'Создал',
        'new_patient' => 'Новый пациент',
        'section_main' => 'Приём',
        'section_notes' => 'Заметки к приёму',
        'filter_upcoming' => 'Предстоящие',
        'filter_from' => 'С даты',
        'filter_to' => 'По дату',
    ],

    'today' => [
        'title' => 'Сегодня',
        'date' => 'Дата',
        'time' => 'Время',
        'total' => 'Всего',
        'arrived' => 'Пришло',
        'waiting' => 'Ожидается',
        'no_show' => 'Не пришло',
        'export' => 'Экспорт в Excel',
    ],

    'my' => [
        'title' => 'Мои приёмы',
        'complete' => 'Завершить приём',
        'completed_notice' => 'Приём завершён',
        'filter_upcoming' => 'Сегодня и предстоящие',
    ],

    'dashboard' => [
        'title' => 'Дашборд',
        'today' => 'Записей на сегодня',
        'today_desc' => 'Приёмы на текущую дату',
        'arrived_month' => 'Пришли в этом месяце',
        'treated_month' => 'Прошли лечение в этом месяце',
        'new_patients_month' => 'Новые пациенты за месяц',
    ],

    'empty' => [
        'today' => 'На эту дату записей нет',
        'my' => 'У вас пока нет приёмов',
        'appointments' => 'Записей нет',
        'patients' => 'Пациентов пока нет',
    ],

    'certificate' => [
        'action' => 'Мед. справка (PDF)',
        'heading' => 'Медицинская справка',
        'submit' => 'Сохранить и скачать PDF',
        'status' => 'Справка',
        'issued' => 'Выдана',
        'not_issued' => 'Не выдана',
        'period_from' => 'Дата осмотра — с',
        'period_to' => 'по',
        'complaints' => 'Жалобы',
        'examination' => 'Результаты осмотра',
        'diagnosis' => 'Диагноз',
        'treatment' => 'Лечение и рекомендации',
    ],
];
