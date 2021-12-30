<?php
defined('TYPO3_MODE') || die('Access denied.');
$feedbackController = 'Feedback';
if (version_compare(TYPO3_branch, '10.0', '>=')) {
    $feedbackController = \NITSAN\NsFeedback\Controller\FeedbackController::class;
}
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NITSAN.NsFeedback',
    'Feedback',
    [
        $feedbackController => 'new, quickFeedback, default',
    ],
    // non-cacheable actions
    [
        $feedbackController => 'new, quickFeedback, default',
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
