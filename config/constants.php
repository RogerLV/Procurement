<?php

define('APP_NAME_PROCUREMENT_SYSTEM', '采购系统');

// System Role Names and Role ID
define('ROLE_NAME_DEPT_MAKER', '部门经办');
define('ROLE_NAME_DEPT_MANAGER', '部门经理');
define('ROLE_NAME_DUE_DILIGENCE_MEMBER', '尽职调查小组成员');
define('ROLE_NAME_SECRETARIAT', '委员会秘书组');
define('ROLE_NAME_DEPUTY_COUNTRY_HEAD', '分管行领导');
define('ROLE_NAME_REVIEW_COMMITTEE_MEMBER', '采购评审委员');
define('ROLE_NAME_SECRETARIAT_LEADER', '采购评审秘书组组长');
define('ROLE_NAME_REVIEW_DIRECTOR', '采购评审主任');
define('ROLE_NAME_APP_ADMIN', '应用管理员');
define('ROLE_NAME_SYSTEM_ADMIN', '系统管理员');

define('ROLE_ID_DEPT_MAKER', 1);
define('ROLE_ID_DEPT_MANAGER', 2);
define('ROLE_ID_DUE_DILIGENCE_MEMBER', 3);
define('ROLE_ID_SECRETARIAT', 4);
define('ROLE_ID_DEPUTY_COUNTRY_HEAD', 5);
define('ROLE_ID_REVIEW_COMMITTEE_MEMBER', 6);
define('ROLE_ID_SECRETARIAT_LEADER', 7);
define('ROLE_ID_REVIEW_DIRECTOR', 8);
define('ROLE_ID_APP_ADMIN', 9);
define('ROLE_ID_SYSTEM_ADMIN', 10);

// Stage ID and Name
define('STAGE_ID_INITIATE', 1);
define('STAGE_ID_INVITE_DEPT', 2);
define('STAGE_ID_ASSIGN_MAKER', 3);
define('STAGE_ID_SELECT_MODE', 4);
define('STAGE_ID_PRETRIAL', 5);
define('STAGE_ID_PASS_SIGN', 6);
define('STAGE_ID_RECORD', 7);

// File Type and Name
define('DOC_TYPE_SIGNED_REPORT', 1);
define('DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION', 2);
define('DOC_TYPE_CALL_FOR_BIDS', 3);
define('DOC_TYPE_TENDER_INVITATION', 4);
define('DOC_TYPE_SIGNED_TENDER_FORM', 5);
define('DOC_TYPE_VENDOR_CLARIFICATION', 6);
define('DOC_TYPE_EVALUATION_REPORT', 7);
define('DOC_TYPE_VENDOR_INVITATION', 8);
define('DOC_TYPE_REVIEW_REPORT', 9);
define('DOC_TYPE_PROJECT_INQUIRY', 10);
define('DOC_TYPE_PROCUREMENT_SELECTION_SCHEMA', 11);
define('DOC_TYPE_DUE_DILIGENCE_REPORT', 12);
define('DOC_TYPE_PROCUREMENT_CONTRACT', 13);
define('DOC_TYPE_MEETING_MINUTES', 14);
define('DOC_TYPE_OTHER_DOCS', 15);

// Error Messages
define('ERROR_MESSAGE_NOT_AUTHORIZED', 'You are not authorized to view the page.');

// Route Names
define('ROUTE_NAME_WELCOME', 'Welcome');
define('ROUTE_NAME_ROLE_LIST', 'RoleList');
define('ROUTE_NAME_ROLE_REMOVE', 'RoleRemove');
define('ROUTE_NAME_ROLE_ADD', 'RoleAdd');
define('ROUTE_NAME_ROLE_SELECT', 'RoleSelect');

define('ROUTE_NAME_PROJECT_APPLY', 'ProjectApply');
define('ROUTE_NAME_PROJECT_DISPLAY', 'ProjectDisplay');
define('ROUTE_NAME_PROJECT_LIST', 'ProjectList');
define('ROUTE_NAME_PROJECT_CREATE', 'ProjectCreate');

define('ROUTE_NAME_STAGE_INVITE_DEPT', 'StageInviteDept');
define('ROUTE_NAME_STAGE_ASSIGN_MAKER', 'StageAssignMaker');
define('ROUTE_NAME_STAGE_SELECT_MODE', 'StageSelectMode');
define('ROUTE_NAME_STAGE_PRETRIAL', 'StagePretrial');
define('ROUTE_NAME_STAGE_PASS_SIGN', 'StagePassSign');

define('ROUTE_NAME_ASSIGN_MAKER_ADD', 'AssignMakerAdd');
define('ROUTE_NAME_ASSIGN_MAKER_REMOVE', 'AssignMakerRemove');

define('ROUTE_NAME_DOCUMENT_DISPLAY', 'DocumentDisplay');
define('ROUTE_NAME_DOCUMENT_UPLOAD', 'DocumentUpload');

define('ROUTE_NAME_CONVERSATION_ADD', 'ConversationAdd');

// Page Names
define('PAGE_NAME_ROLE_LIST', '查看角色');
define('PAGE_NAME_PROJECT_APPLY', '发起采购');
define('PAGE_NAME_PROJECT_LIST', '项目列表');

define('PAGE_NAME_PROJECT_DISPLAY', '项目浏览');

// Others
define('PROCUREMENT_METHOD_NOT_SELECTED', '尚未选择');

return [
    // Procurement Scope
    'procurementScopeNames' => [
        'goods' => '货物类',
        'engineering' => '工程类',
        'services' => '服务类',
    ],

    // Procurement Method
    'procurementMethods' => [
        'OpenTender' => '公开招标',
        'InviteTender' => '邀请招标',
        'CompetitiveNegotiation' => '竞争性谈判',
        'PriceEnquiry' => '询价',
        'SingleSourcing' => '单一来源',
    ],

    // Document Type Names
    'documentTypeNames' => [
        DOC_TYPE_SIGNED_REPORT => '审批通过立项签报',
        DOC_TYPE_PROCUREMENT_APPROACH_APPLICATION =>'采购方式申请报告',
        DOC_TYPE_CALL_FOR_BIDS => '公开招标招标公示',
        DOC_TYPE_TENDER_INVITATION => '邀请招标招标邀请书',
        DOC_TYPE_SIGNED_TENDER_FORM => '开标日投标书签字',
        DOC_TYPE_VENDOR_CLARIFICATION => '供应商澄清说明',
        DOC_TYPE_EVALUATION_REPORT => '项目评估报告',
        DOC_TYPE_VENDOR_INVITATION => '供应商邀请函',
        DOC_TYPE_REVIEW_REPORT => '上会报告',
        DOC_TYPE_PROJECT_INQUIRY => '项目询价函',
        DOC_TYPE_PROCUREMENT_SELECTION_SCHEMA => '采购小组选型方案',
        DOC_TYPE_DUE_DILIGENCE_REPORT => '尽职调查报告',
        DOC_TYPE_PROCUREMENT_CONTRACT => '采购合同',
        DOC_TYPE_MEETING_MINUTES => '会议纪要',
        DOC_TYPE_OTHER_DOCS => '其他文档',
    ],

    // Stage Names
    'stageNames' => [
        STAGE_ID_INITIATE => '发起采购',
        STAGE_ID_INVITE_DEPT => '邀请部门',
        STAGE_ID_ASSIGN_MAKER => '指派采购小组成员',
        STAGE_ID_SELECT_MODE => '选择采购方式',
        STAGE_ID_PRETRIAL => '秘书组预审',
        STAGE_ID_PASS_SIGN => '委员会委员传签',
        STAGE_ID_RECORD => '记录采购过程',
    ],

    // Pass Sign Values
    'passSignValues' => [
        'approve' => '同意',
        'reject' => '不同意',
    ],
];

