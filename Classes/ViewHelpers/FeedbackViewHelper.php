<?php

namespace Nitsan\NsFeedback\ViewHelpers;

use Doctrine\DBAL\Exception;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\InvalidExtensionNameException;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FeedbackViewHelper extends AbstractViewHelper
{
    /**
     * @throws InvalidExtensionNameException
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $reportRepository = GeneralUtility::makeInstance(ReportRepository::class);
        $rows = $reportRepository->getFeedbacksReport();
        $pageRecord = BackendUtility::getRecord(
            'pages',
            $GLOBALS['TSFE']->page['pid']
        );
        if (!$rows) {
            if ($GLOBALS['TSFE']->page['tx_nsfeedback_enable'] > 0 || $pageRecord['tx_nsfeedback_enable'] > 0) {
                // Create repository instance
                $view = GeneralUtility::makeInstance(StandaloneView::class);
                $view->setLayoutRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Layouts/')]);
                $view->setPartialRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Partials/')]);
                $view->setTemplateRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Templates/')]);
                $view->setTemplate('Report/New');
                $view->getRequest()->setControllerExtensionName('ns_feedback');
                return $view->render();
            }
        } else {
            $alreadyfeedback = LocalizationUtility::translate('tx_nsfeedback_domain_model_feedbacks.alreadyfeedback', 'ns_feedback');
            return ' <div class="form-wrapper" id="ns-feedback-form"><div class="container"><div class="send-msg">' . $alreadyfeedback . '</div></div></div>';
        }
        return '';
    }
}
