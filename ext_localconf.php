<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NITSAN.NsFeedback',
    'Feedback',
    [
        'Feedback' => 'new, quickFeedback, default',
    ],
    // non-cacheable actions
    [
        'Feedback' => 'new, quickFeedback, default',
    ]
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
    'ns_feedback-plugin-feedback',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg']
);

$iconRegistry->registerIcon(
    'module-nsfeedback',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:ns_feedback/Resources/Public/Icons/module-nitsan.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['Feedback'] = [
    'NITSAN\NsFeedback\ViewHelpers',
];
