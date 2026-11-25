<?php

namespace NITSAN\NsFeedback\ViewHelpers;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\View\ViewFactoryData;
use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class FeedbackViewHelper extends AbstractViewHelper
{
    public function render()
    {
        $request = $GLOBALS['TYPO3_REQUEST'];
        $pageInformation = $request->getAttribute('frontend.page.information');
        $pageRecord = $pageInformation->getPageRecord();
        $pageUid = (int)($pageRecord['uid'] ?? 0);
        $pagePid = (int)($pageRecord['pid'] ?? 0);
        $userIp = (string)GeneralUtility::getIndpEnv('REMOTE_ADDR');
        $newsParams = $request->getQueryParams()['tx_news_pi1'] ?? [];
        $newsId = (int)($newsParams['news'] ?? 0);

        $parentPageRecord = BackendUtility::getRecord('pages', $pagePid) ?? [];
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_nsfeedback_domain_model_feedbacks');

        $rows = null;
        if ($newsId > 0) {
            $reportQueryBuilder = $connection->createQueryBuilder();
            $reportRows = $reportQueryBuilder
                ->select('uid')
                ->from('tx_nsfeedback_domain_model_report')
                ->where(
                    $reportQueryBuilder->expr()->eq(
                        'pid',
                        $reportQueryBuilder->createNamedParameter($pageUid, Connection::PARAM_INT)
                    )
                )
                ->andWhere(
                    $reportQueryBuilder->expr()->eq(
                        'record_id',
                        $reportQueryBuilder->createNamedParameter($newsId, Connection::PARAM_INT)
                    )
                )
                ->executeQuery()
                ->fetchAllAssociative();

            if ($reportRows) {
                $feedbackQueryBuilder = $connection->createQueryBuilder();
                $rows = $feedbackQueryBuilder
                    ->select('*')
                    ->from('tx_nsfeedback_domain_model_feedbacks')
                    ->where(
                        $feedbackQueryBuilder->expr()->eq(
                            'pid',
                            $feedbackQueryBuilder->createNamedParameter($pageUid, Connection::PARAM_INT)
                        )
                    )
                    ->andWhere(
                        $feedbackQueryBuilder->expr()->eq(
                            'user_ip',
                            $feedbackQueryBuilder->createNamedParameter($userIp, Connection::PARAM_STR)
                        )
                    )
                    ->executeQuery()
                    ->fetchAllAssociative();
            }
        } else {
            $feedbackQueryBuilder = $connection->createQueryBuilder();
            $rows = $feedbackQueryBuilder
                ->select('*')
                ->from('tx_nsfeedback_domain_model_feedbacks')
                ->where(
                    $feedbackQueryBuilder->expr()->eq(
                        'pid',
                        $feedbackQueryBuilder->createNamedParameter($pageUid, Connection::PARAM_INT)
                    )
                )
                ->andWhere(
                    $feedbackQueryBuilder->expr()->eq(
                        'user_ip',
                        $feedbackQueryBuilder->createNamedParameter($userIp, Connection::PARAM_STR)
                    )
                )
                ->executeQuery()
                ->fetchAllAssociative();
        }

        if (!$rows) {
            $enabledOnPage = (int)($pageRecord['tx_nsfeedback_enable'] ?? 0) > 0
                || (int)($parentPageRecord['tx_nsfeedback_enable'] ?? 0) > 0;
            if ($enabledOnPage) {
                $viewFactory = GeneralUtility::makeInstance(ViewFactoryInterface::class);
                $viewFactoryData = new ViewFactoryData(
                    layoutRootPaths: [GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Layouts/')],
                    partialRootPaths: [GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Partials/')],
                    templateRootPaths: [GeneralUtility::getFileAbsFileName('EXT:ns_feedback/Resources/Private/Templates/')],
                );
                $view = $viewFactory->create($viewFactoryData);

                if ($newsId > 0) {
                    $view->assign('newsId', $newsId);
                }
                return $view->render();
            }
        } else {
            $alreadyfeedback = LocalizationUtility::translate(
                'tx_nsfeedback_domain_model_feedbacks.alreadyfeedback',
                'ns_feedback'
            );
            return ' <div class="form-wrapper" id="ns-feedback-form"><div class="container"><div class="send-msg">'
                . htmlspecialchars((string)$alreadyfeedback, ENT_QUOTES | ENT_HTML5)
                . '</div></div></div>';
        }

        return '';
    }
}
