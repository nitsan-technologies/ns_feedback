<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_feedbacks',
        'label' => 'comment',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'created_by' => [
            'exclude' => true,
            'label' => 'Created By',
            'config' => [
                'type' => 'createdBy',
                'renderType' => 'selectSingle',
            ],
        ],
        'sorting' => 'uid',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'security' => [
            'ignorePageTypeRestriction' => true
        ],
        'searchFields' => 'comment,user_ip,feedback_type',
        'iconfile' => 'EXT:ns_feedback/Resources/Public/Icons/tx_nsfeedback_domain_model_feedbacks.gif',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, comment, user_ip, feedback_type, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    [
                        'label' => '',
                        'value' => 0
                    ],
                ],
                'foreign_table' => 'tx_nsfeedback_domain_model_feedbacks',
                'foreign_table_where' => 'AND {#tx_nsfeedback_domain_model_feedbacks}.{#pid}=###CURRENT_PID### AND {#tx_nsfeedback_domain_model_feedbacks}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'crdate' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        'label' => '',
                        'value' => 0,
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'datetime',
                'eval' => 'datetime',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'datetime',
                'eval' => 'datetime',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'comment' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_feedbacks.comment',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 8,
                'eval' => 'trim',
            ],
        ],
        'user_ip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_feedbacks.user_ip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'readOnly' =>1,
                'eval' => 'trim',
            ],
        ],
        'feedback_type' => [
            'exclude' => true,
            'label' => 'Feedback Variation',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        'label' => 'Full feedback',
                        'value' => 1
                    ],
                    [
                        'label' => 'Ratings',
                        'value' => 2
                    ],
                    [
                        'label' => 'Quick feedback',
                        'value' => 3
                    ],
                    [
                        'label' => 'Popup',
                        'value' => 4
                    ],
                ],
                'readOnly' => 1,
            ],
        ],
        'quickfeedbacktype' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_feedbacks.quickfeedbacktype',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'readOnly' =>1,
                'eval' => 'trim',
            ],
        ],
        'cid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_feedbacks.cid',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'readOnly' =>0,
                'eval' => 'trim,number',
            ],
        ],
        'report' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
    ],
];
