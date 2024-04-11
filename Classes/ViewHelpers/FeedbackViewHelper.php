<?php

namespace Nitsan\NsFeedback\ViewHelpers;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FeedbackViewHelper extends AbstractViewHelper
{
    /**
     * @throws Exception
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_nsfeedback_domain_model_report');
        $queryBuilder = $connection->createQueryBuilder();
        $startingPoint = $GLOBALS['TSFE']->page['pid'];
        $pageRecord = BackendUtility::getRecord(
            'pages',
            $startingPoint
        );
        $rows = $queryBuilder
            ->select('*')
            ->from('tx_nsfeedback_domain_model_feedbacks')
            ->where($queryBuilder->expr()->eq('pid', $GLOBALS['TSFE']->page['uid']))
            ->andWhere(
                $queryBuilder->expr()->eq('user_ip', "'" . $_SERVER['REMOTE_ADDR'] . "'")
            )
            ->executeQuery()
            ->fetchAllAssociative();
        if (!$rows && ($GLOBALS['TSFE']->page['tx_nsfeedback_enable'] > 0 || $pageRecord['tx_nsfeedback_enable'] > 0)) {
            // Create repository instance
            $view = GeneralUtility::makeInstance(StandaloneView::class);
            $view->setLayoutRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Layouts/')]);
            $view->setPartialRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Partials/')]);
            $view->setTemplateRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Templates/')]);
            $view->setTemplate('Report/New');
            return $view->render();
        }
        $alreadyfeedback = LocalizationUtility::translate('tx_nsfeedback_domain_model_feedbacks.alreadyfeedback', 'ns_feedback');
        return ' <div class="form-wrapper" id="ns-feedback-form"><div class="container"><div class="send-msg">' . $alreadyfeedback . '</div></div></div>';
    }

}
