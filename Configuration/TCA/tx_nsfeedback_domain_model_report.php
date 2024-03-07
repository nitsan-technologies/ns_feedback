<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report',
        'label' => 'page_title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'page_title',
        'iconfile' => 'EXT:ns_feedback/Resources/Public/Icons/tx_nsfeedback_domain_model_report.gif',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_diffsource, hidden, page_id, record_id, page_type, page_title, feedbacks',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_diffsource, hidden, page_id, record_id, page_type, page_title, feedbacks, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple',
                    ],
                ],
                'default' => 0,
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
                        0 => '',
                        1 => '',
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
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
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
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],

        'page_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.page_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'record_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.record_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'page_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.page_type',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'page_title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.page_title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'feedback_yes_count' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.feedback_yes_count',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'feedback_no_count' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.feedback_no_count',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'feedback_yes_but_count' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.feedback_yes_but_count',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'feedback_no_but_count' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.feedback_no_but_count',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'sys_lang_id' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.sys_lang_id',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
            ],
        ],
        'feedbacks' => [
            'exclude' => true,
            'label' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_db.xlf:tx_nsfeedback_domain_model_report.feedbacks',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_nsfeedback_domain_model_feedbacks',
                'foreign_field' => 'report',
                'maxitems' => 9999,
                'appearance' => [
                    'collapseAll' => 0,
                    'levelLinksPosition' => 'top',
                    'showSynchronizationLink' => 1,
                    'showPossibleLocalizationRecords' => 1,
                    'showAllLocalizationLink' => 1,
                ],
            ],
        ],
    ],
];
