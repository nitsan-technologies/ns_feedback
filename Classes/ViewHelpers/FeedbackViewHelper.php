<?php

namespace NITSAN\NsFeedback\ViewHelpers;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FeedbackViewHelper extends AbstractViewHelper
{
    public function render() {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_nsfeedback_domain_model_report');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder2 = $connection->createQueryBuilder();

        // $startingPoint = $GLOBALS['TSFE']->page['pid'];
        // $currentPoint = $GLOBALS['TSFE']->page['tx_nsfeedback_enable'];
        $startingPoint = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord('pid');
        $currentPoint = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord('tx_nsfeedback_enable');

        $pageRecord = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
            'pages',
            $startingPoint
        );
        $rows = null;
        if ($_GET['tx_news_pi1']['news'] > 0) {
            $newsId = $_GET['tx_news_pi1']['news'];
            $rows2 = $queryBuilder
                ->select('*')
                ->from('tx_nsfeedback_domain_model_report')
                ->where($queryBuilder->expr()->eq('pid', $GLOBALS['TYPO3_REQUEST']
                    ->getAttribute('frontend.page.information')
                    ->getPageRecord('uid')))
                ->andWhere(
                    $queryBuilder->expr()->eq('record_id', $newsId)
                )
                ->execute()
                ->fetchAll();

            if ($rows2) {
                $rows = $queryBuilder2
                    ->select('*')
                    ->from('tx_nsfeedback_domain_model_feedbacks')
                    ->where($queryBuilder->expr()->eq('pid', $GLOBALS['TYPO3_REQUEST']
                        ->getAttribute('frontend.page.information')
                        ->getPageRecord('uid')))
                    ->andWhere(
                        $queryBuilder->expr()->eq('user_ip', "'" . $_SERVER['REMOTE_ADDR'] . "'")
                    )
                    ->execute()
                    ->fetchAll();
            }
        } else {
            $rows = $queryBuilder
                ->select('*')
                ->from('tx_nsfeedback_domain_model_feedbacks')
                ->where($queryBuilder->expr()->eq('pid', $GLOBALS['TYPO3_REQUEST']
                    ->getAttribute('frontend.page.information')
                    ->getPageRecord('uid')))
                ->andWhere(
                    $queryBuilder->expr()->eq('user_ip', "'" . $_SERVER['REMOTE_ADDR'] . "'")
                )
                ->execute()
                ->fetchAll();
        }

        if (!$rows) {
            if ($GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord('tx_nsfeedback_enable') > 0 || $pageRecord['tx_nsfeedback_enable'] > 0) {

                // Create repository instance
                $querySettings = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);

                // $view = GeneralUtility::makeInstance(StandaloneView::class);
                // $view->setLayoutRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Layouts/')]);
                // $view->setPartialRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Partials/')]);
                // $view->setTemplateRootPaths([GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Templates/')]);
                // $view->setTemplate('Report/New');

                $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
                $viewFactoryData = new ViewFactoryData(
                    layoutRootPaths: ['ns_feedback/Resources/Private/Layouts/'],
                    partialRootPaths: ['ns_feedback/Resources/Private/Partials/'],
                    templateRootPaths: ['EXT:ns_feedback/Resources/Private/Templates/'],
                );
                $view = $viewFactory->create($viewFactoryData);

                if ($_GET['tx_news_pi1']['news'] > 0) {
                    $view->assign('newsId', $_GET['tx_news_pi1']['news']);
                }
                // $view->getRequest()->setControllerExtensionName('ns_feedback');
                return $view->render();
            }
        } else {
            $alreadyfeedback = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('tx_nsfeedback_domain_model_feedbacks.alreadyfeedback', 'ns_feedback');
            return ' <div class="form-wrapper" id="ns-feedback-form"><div class="container"><div class="send-msg">' . $alreadyfeedback . '</div></div></div>';
        }
    }
}
