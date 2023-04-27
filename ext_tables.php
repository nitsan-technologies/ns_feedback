<?php
defined('TYPO3') || die('Access denied.');


    $GLOBALS['TCA']['tx_nsfeedback_domain_model_feedbacks']['ctrl']['hideTable'] = 1;
        $GLOBALS['TCA']['tx_nsfeedback_domain_model_report']['ctrl']['hideTable'] = 1;

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_feedback/Configuration/TSconfig/ContentElementWizard.tsconfig">'
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nsfeedback_domain_model_report', 'EXT:ns_feedback/Resources/Private/Language/locallang_csh_tx_nsfeedback_domain_model_report.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfeedback_domain_model_report');

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nsfeedback_domain_model_feedbacks', 'EXT:ns_feedback/Resources/Private/Language/locallang_csh_tx_nsfeedback_domain_model_feedbacks.xlf');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfeedback_domain_model_feedbacks');
