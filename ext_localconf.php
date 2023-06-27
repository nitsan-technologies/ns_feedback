<?php

defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NsFeedback',
    'Feedback',
    [
        \NITSAN\NsFeedback\Controller\FeedbackController::class => 'new, quickFeedback, default',
    ],
    // non-cacheable actions
    [
        \NITSAN\NsFeedback\Controller\FeedbackController::class => 'new, quickFeedback, default',
    ]
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
    'ns_feedback-plugin-feedback',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['Feedback'] = [
    'NITSAN\NsFeedback\ViewHelpers',
];

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:ns_feedback/Configuration/TSconfig/ContentElementWizard.tsconfig"'
);
