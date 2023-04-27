<?php


return [
    'nitsan_module' => [
        'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/BackendModule.xlf',
        'icon' => 'EXT:ns_feedback/Resources/Public/Icons/module-nsfeedback.svg',
        'iconIdentifier' => 'module-nsfeedback',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'position' => ['after' => 'web'],
    ],
    'nitsan_nsfeedbackmodule_configuration' => [
        'parent' => 'nitsan_module',
        'position' => ['before' => 'top'],
        'access' => 'admin,user,group',
        'path' => '/module/nitsan/NsFeedbackConfiguration',
        'icon'   => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg',
        'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_feedback_configuration.xlf',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'NsFeedback',
        'routes' => [
            '_default' => [
                'target' => \NITSAN\NsFeedback\Controller\NsConstantEditorController::class . '::handleRequest',
            ],
        ],
        'moduleData' => [
            'selectedTemplatePerPage' => [],
            'selectedCategory' => '',
        ],
    ],
    'nitsan_nsfeedbackmodule_report' => [
        'parent' => 'nitsan_module',
        'position' => ['before' => 'top'],
        'access' => 'admin,user,group',
        'path' => '/module/nitsan/NsFeedbackReport',
        'icon'   => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg',
        'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_feedback_report.xlf',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'NsFeedback',
        'controllerActions' => [
            \NITSAN\NsFeedback\Controller\ReportController::class => [
                'dashboard',
                'list', 
                'show', 
                'premiumExtension',
            ],
        ],
    ],
    
];

?>