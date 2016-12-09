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
define('ROLE_NAME_SPECIAL_INVITE', '特邀列席');

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
define('ROLE_ID_SPECIAL_INVITE', 11);

// Stage ID and Name
define('STAGE_ID_INITIATE', 1);
define('STAGE_ID_INVITE_DEPT', 2);
define('STAGE_ID_ASSIGN_MAKER', 3);
define('STAGE_ID_SELECT_MODE', 4);
define('STAGE_ID_PRETRIAL', 5);
define('STAGE_ID_PASS_SIGN', 6);
define('STAGE_ID_RECORD', 7);
define('STAGE_ID_SUMMARIZE', 8);
define('STAGE_ID_MANAGER_APPROVE', 9);
define('STAGE_ID_VP_APPROVE', 10);
define('STAGE_ID_AUDIT', 11);
define('STAGE_ID_DUE_DILIGENCE', 12);
define('STAGE_ID_REVIEW', 13);
define('STAGE_ID_FILE_CONTRACT', 14);
define('STAGE_ID_COMPLETE', 15);

define('STAGE_ID_REVIEW_MEETING_INITIATE', 101);
define('STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM', 102);
define('STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES', 103);
define('STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS', 104);
define('STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE', 105);
define('STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE', 106);
define('STAGE_ID_REVIEW_MEETING_DECIDE_PROCUREMENT_MODE', 107);
define('STAGE_ID_REVIEW_MEETING_COMPLETE', 108);

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
define('DOC_TYPE_PUT_RECORDS', 16);

// Error Messages
define('ERROR_MESSAGE_NOT_AUTHORIZED', 'You are not authorized to view the page.');
define('ERROR_MESSAGE_ALREADY_COMMENTED', '不能重复填写意见');

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
define('ROUTE_NAME_STAGE_SELECT_MODE', 'StageSelectMode');
define('ROUTE_NAME_STAGE_FINISH_RECORD', 'StageFinishRecord');
define('ROUTE_NAME_STAGE_SUMMARIZE', 'StageSummarize');
define('ROUTE_NAME_STAGE_APPROVE', 'StageApprove');
define('ROUTE_NAME_STAGE_COMPLETE', 'StageComplete');

define('ROUTE_NAME_ASSIGN_MAKER_ADD', 'AssignMakerAdd');
define('ROUTE_NAME_ASSIGN_MAKER_REMOVE', 'AssignMakerRemove');

define('ROUTE_NAME_SCORE_EDIT_TEMPLATE', 'ScoreEditTemplate');
define('ROUTE_NAME_SCORE_SELECT_TEMPLATE', 'ScoreSelectTemplate');
define('ROUTE_NAME_SCORE_COMMIT_ITEMS', 'ScoreCommitItems');
define('ROUTE_NAME_SCORE_PAGE', 'ScorePage');
define('ROUTE_NAME_SCORE_SUBMIT_SCORE', 'ScoreSubmitScore');
define('ROUTE_NAME_SCORE_OVERVIEW', 'ScoreOverview');

define('ROUTE_NAME_VENDOR_ADD', 'VendorAdd');
define('ROUTE_NAME_VENDOR_REMOVE', 'VendorRemove');

define('ROUTE_NAME_NEGOTIATION_ADD', 'NegotiationAdd');

define('ROUTE_NAME_DUE_DILIGENCE_ADD_REQUEST', 'DueDiligenceAddRequest');
define('ROUTE_NAME_DUE_DILIGENCE_REMOVE_REQUEST', 'DueDiligenceRemoveRequest');
define('ROUTE_NAME_DUE_DILIGENCE_ANSWER', 'DueDiligenceAnswer');

define('ROUTE_NAME_DOCUMENT_DISPLAY', 'DocumentDisplay');
define('ROUTE_NAME_DOCUMENT_UPLOAD', 'DocumentUpload');

define('ROUTE_NAME_CONVERSATION_ADD', 'ConversationAdd');

define('ROUTE_NAME_REVIEW_APPLY', 'ReviewApply');
define('ROUTE_NAME_REVIEW_EDIT', 'ReviewEdit');
define('ROUTE_NAME_REVIEW_DISPLAY', 'ReviewDisplay');

define('ROUTE_NAME_REVIEW_STAGE_COMPLETE', 'ReviewStageComplete');
define('ROUTE_NAME_REVIEW_STAGE_APPROVE', 'ReviewStageApprove');

define('ROUTE_NAME_TOPIC_ADD_PROJECT', 'TopicAddProject');
define('ROUTE_NAME_TOPIC_ADD_PUT_RECORD', 'TopicAddPutRecord');
define('ROUTE_NAME_TOPIC_REMOVE', 'TopicRemove');

define('ROUTE_NAME_REVIEW_PARTICIPANT_EDIT', 'ReviewParticipantEdit');

// Page Names
define('PAGE_NAME_ROLE_LIST', '查看角色');
define('PAGE_NAME_PROJECT_APPLY', '发起采购');
define('PAGE_NAME_PROJECT_LIST', '项目列表');

define('PAGE_NAME_PROJECT_DISPLAY', '项目浏览');
define('PAGE_NAME_SCORE_EDIT_TEMPLATE', '编辑评分模板');
define('PAGE_NAME_SCORE_PAGE', '供应商打分');
define('PAGE_NAME_SCORE_OVERVIEW', '供应商打分汇总');

define('PAGE_NAME_REVIEW_APPLY', '发起采购评审');

// Others
define('PROCUREMENT_METHOD_NOT_SELECTED', '尚未选择');
define('PRINT_PAGE', true);
define('YEAR', '年');
define('MONTH', '月');
define('DAY', '日');

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
        DOC_TYPE_PUT_RECORDS => '报备文档',
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
        STAGE_ID_SUMMARIZE => '采购结果总结',
        STAGE_ID_MANAGER_APPROVE => '部门总经理审批',
        STAGE_ID_VP_APPROVE => '分管行领导审批',
        STAGE_ID_AUDIT => '审核项目资料',
        STAGE_ID_DUE_DILIGENCE => '尽职调查',
        STAGE_ID_REVIEW => '采购评审',
        STAGE_ID_FILE_CONTRACT => '采购合同归档',
        STAGE_ID_COMPLETE => '完成',

        STAGE_ID_REVIEW_MEETING_INITIATE => '发起采购评审',
        STAGE_ID_REVIEW_MEETING_MEMBER_CONFIRM => '委员确认',
        STAGE_ID_REVIEW_MEETING_GENERATE_MINUTES => '生成会议纪要',
        STAGE_ID_REVIEW_MEETING_MEMBER_COMMENTS => '委员填写意见',
        STAGE_ID_REVIEW_MEETING_SECRETARIAT_LEADER_APPROVE => '审核会议纪要',
        STAGE_ID_REVIEW_MEETING_DIRECTOR_APPROVE => '签发会议纪要',
        STAGE_ID_REVIEW_MEETING_DECIDE_PROCUREMENT_MODE => '采购方式讨论结果',
        STAGE_ID_REVIEW_MEETING_COMPLETE => '完成',
    ],

    // Pass Sign Values
    'passSignValues' => [
        'approve' => '同意',
        'reject' => '不同意',
    ],

    'TopicTypeNames' => [
        'review' => '采购项目评审',
        'discussion' => '采购方式预审',
        'putRecord' => '报备',
    ],
];

