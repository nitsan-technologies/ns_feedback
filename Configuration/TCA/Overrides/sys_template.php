<?php
defined('TYPO3_MODE') || die('Access denied.');

$extKey = 'ns_feedback';

// Adding fields to the tt_content table definition in TCA
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', '[NITSAN] Feedback');
