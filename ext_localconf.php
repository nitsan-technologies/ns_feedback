<?php
use NITSAN\NsFeedback\Controller\FeedbackController;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

ExtensionUtility::configurePlugin(
    'NsFeedback',
    'Feedback',
    [
        FeedbackController::class => 'new, quickFeedback',
    ],
    // non-cacheable actions
    [
        FeedbackController::class => 'new, quickFeedback',
    ]
);

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

$iconRegistry->registerIcon(
    'ns_feedback-plugin-feedback',
    SvgIconProvider::class,
    ['source' => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['Feedback'] = [
    'NITSAN\NsFeedback\ViewHelpers',
];

ExtensionManagementUtility::addPageTSConfig(
    '@import "EXT:ns_feedback/Configuration/TSconfig/ContentElementWizard.tsconfig"'
);
