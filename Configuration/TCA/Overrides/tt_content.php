<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') or die();

$_EXTKEY = 'ns_feedback';

/***************
 * Plugin
 */
ExtensionUtility::registerPlugin(
    'NsFeedback',
    'Feedback',
    'feedback',
    '',
    'plugins'
);
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['nsfeedback_feedback'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tx_nsfeedback_domain_model_feedbacks']['ctrl']['hideTable'] = 1;
$GLOBALS['TCA']['tx_nsfeedback_domain_model_report']['ctrl']['hideTable'] = 1;
