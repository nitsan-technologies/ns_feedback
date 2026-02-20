<?php

use NITSAN\NsFeedback\Controller\ReportController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Information\Typo3Version;

if ((GeneralUtility::makeInstance(Typo3Version::class))->getMajorVersion() <= 13) {
    $navigationComponent = '@typo3/backend/page-tree/page-tree-element';
} else {
    $navigationComponent = '@typo3/backend/tree/page-tree-element';
}

return [
    'nitsan_module' => [
        'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/BackendModule.xlf',
        'icon' => 'EXT:ns_feedback/Resources/Public/Icons/module-nsfeedback.svg',
        'iconIdentifier' => 'module-nsfeedback',
        'navigationComponent' => $navigationComponent,
        'position' => ['after' => 'web'],
    ],
    'nitsan_nsfeedbackmodule_report' => [
        'parent' => 'nitsan_module',
        'position' => ['before' => 'top'],
        'access' => 'user',
        'path' => '/module/nitsan/NsFeedbackReport',
        'icon'   => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg',
        'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_feedback_report.xlf',
        'navigationComponent' => $navigationComponent,
        'extensionName' => 'NsFeedback',
        'controllerActions' => [
            ReportController::class => [
                'dashboard',
                'list',
                'show',
                'premiumExtension',
            ],
        ],
    ],

];
