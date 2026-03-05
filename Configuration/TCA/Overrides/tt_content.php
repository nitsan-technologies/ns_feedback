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
    'ns_feedback-plugin-feedback',
    'plugins'
);