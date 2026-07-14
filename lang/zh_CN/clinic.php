<?php

return [
    'nav' => [
        'management' => '管理',
        'registry' => '登记',
    ],

    'department' => [
        'label' => '科室',
        'plural' => '科室',
        'name_ru' => '名称（俄文）',
        'name_kk' => '名称（哈萨克文）',
        'name_zh' => '名称（中文）',
        'is_active' => '启用',
    ],

    'user' => [
        'label' => '用户',
        'plural' => '用户',
        'name' => '姓名',
        'name_kk' => '姓名（哈萨克文）',
        'name_zh' => '姓名（中文）',
        'email' => '邮箱',
        'password' => '密码',
        'roles' => '角色',
        'department' => '科室',
    ],

    'patient' => [
        'label' => '患者',
        'plural' => '患者',
        'name_kk' => '姓名（哈萨克文）',
        'name_zh' => '姓名（中文）',
        'iin' => '身份证号',
        'phone' => '电话',
        'birth_date' => '出生日期',
        'city' => '城市',
        'category' => '类别',
        'notes' => '补充信息',
        'registered_at' => '登记日期',
    ],

    'appointment' => [
        'label' => '预约',
        'plural' => '预约',
        'patient' => '患者',
        'department' => '科室',
        'doctor' => '医生',
        'doctor_any' => '不指定医生（按科室）',
        'scheduled_at' => '就诊日期和时间',
        'visit_status' => '就诊状态',
        'treatment_status' => '治疗结果',
        'treatment_amount' => '治疗金额 (₸)',
        'notes_kk' => '备注（哈萨克文）',
        'notes_zh' => '备注（中文）',
        'created_by' => '创建人',
        'new_patient' => '新患者',
        'section_main' => '就诊',
        'section_notes' => '就诊备注',
        'filter_upcoming' => '即将到来',
        'filter_from' => '起始日期',
        'filter_to' => '结束日期',
    ],

    'today' => [
        'title' => '今天',
        'date' => '日期',
        'time' => '时间',
        'total' => '总数',
        'arrived' => '已到达',
        'waiting' => '等待中',
        'no_show' => '未到诊',
        'export' => '导出到 Excel',
    ],

    'my' => [
        'title' => '我的就诊',
        'complete' => '完成就诊',
        'completed_notice' => '就诊已完成',
        'filter_upcoming' => '今天及以后',
    ],

    'dashboard' => [
        'title' => '仪表板',
        'today' => '今日预约',
        'today_desc' => '当天的就诊',
        'arrived_month' => '本月到诊',
        'treated_month' => '本月已治疗',
        'new_patients_month' => '本月新患者',
    ],

    'empty' => [
        'today' => '该日期没有预约',
        'my' => '您目前没有就诊',
        'appointments' => '没有预约',
        'patients' => '暂无患者',
    ],

    'certificate' => [
        'action' => '医疗证明 (PDF)',
        'heading' => '医疗证明',
        'submit' => '保存并下载 PDF',
        'period_from' => '就诊开始',
        'period_to' => '就诊结束',
        'complaints' => '主诉',
        'examination' => '检查结果',
        'diagnosis' => '诊断',
        'treatment' => '治疗及建议',
    ],
];
