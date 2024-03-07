<?php

defined('TYPO3_MODE') || die('Access denied.');

if (TYPO3_MODE === 'BE') {
    $isVersion9Up = \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;
    if (!array_key_exists('nitsan', $GLOBALS['TBE_MODULES']) || $GLOBALS['TBE_MODULES']['nitsan'] == '') {
        if (version_compare(TYPO3_branch, '8.0', '>=')) {
            if (!isset($GLOBALS['TBE_MODULES']['nitsan'])) {
                $temp_TBE_MODULES = [];
                foreach ($GLOBALS['TBE_MODULES'] as $key => $val) {
                    if ($key == 'web') {
                        $temp_TBE_MODULES[$key] = $val;
                        $temp_TBE_MODULES['nitsan'] = '';
                    } else {
                        $temp_TBE_MODULES[$key] = $val;
                    }
                }
                $GLOBALS['TBE_MODULES'] = $temp_TBE_MODULES;
                $GLOBALS['TBE_MODULES']['_configuration']['nitsan'] = [
                    'iconIdentifier' => 'module-nsfeedback',
                    'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/BackendModule.xlf',
                    'name' => 'nitsan'
                ];
            }
        }
    }
    $reportController = 'Report';
    if (version_compare(TYPO3_branch, '10.0', '>=')) {
        $reportController = \NITSAN\NsFeedback\Controller\ReportController::class;
    }
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'NITSAN.NsFeedback',
        'nitsan', // Make module a submodule of 'nitsan'
        'feedback', // Submodule key
        '', // Position
        [
            $reportController => 'dashboard, saveConstant, list, show, appearanceSettings, commonSettings',
        ],
        [
            'access' => 'user,group',
            'icon'   => 'EXT:ns_feedback/Resources/Public/Icons/plugin-feedback.svg',
            'labels' => 'LLL:EXT:ns_feedback/Resources/Private/Language/locallang_feedback.xlf',
            'navigationComponentId' => ($isVersion9Up ? 'TYPO3/CMS/Backend/PageTree/PageTreeElement' : 'typo3-pagetree'),
            'inheritNavigationComponentFromMainModule' => false
        ]
    );

    $GLOBALS['TCA']['tx_nsfeedback_domain_model_feedbacks']['ctrl']['hideTable'] = 1;
    $GLOBALS['TCA']['tx_nsfeedback_domain_model_report']['ctrl']['hideTable'] = 1;
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_feedback/Configuration/TSconfig/ContentElementWizard.tsconfig">'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nsfeedback_domain_model_report', 'EXT:ns_feedback/Resources/Private/Language/locallang_csh_tx_nsfeedback_domain_model_report.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfeedback_domain_model_report');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nsfeedback_domain_model_feedbacks', 'EXT:ns_feedback/Resources/Private/Language/locallang_csh_tx_nsfeedback_domain_model_feedbacks.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfeedback_domain_model_feedbacks');
