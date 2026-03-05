<?php

defined('TYPO3') || die('Access denied.');

use NITSAN\NsFeedback\Controller\FeedbackController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'NsFeedback',
    'Feedback',
    [
        FeedbackController::class => 'new, quickFeedback, default',
    ],
    // non-cacheable actions
    [
        FeedbackController::class => 'new, quickFeedback, default',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['Feedback'] = [
    'NITSAN\NsFeedback\ViewHelpers',
];

