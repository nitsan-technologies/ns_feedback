<?php
defined('TYPO3_MODE') or die();

$_EXTKEY = 'ns_feedback';

/***************
 * Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Nitsan.NsFeedback',
    'Feedback',
    'feedback'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['nsfeedback_feedback'] = 'recursive,select_key,pages';
