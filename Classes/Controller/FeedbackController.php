<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Model\Feedbacks;
use NITSAN\NsFeedback\Domain\Model\Report;
use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FeedbackController extends ActionController
{
    /**
     * feedbacksRepository
     *
     * @var FeedbacksRepository
     * @inject
     */
    protected $feedbacksRepository = null;

    /**
     * reportRepository
     *
     * @var ReportRepository
     * @inject
     */
    protected $reportRepository = null;

    /*
     * sys_language_uid
     * @int
     */
    protected $sys_language_uid = null;

    /**
     * Inject reportRepository
     *
     * @param ReportRepository $reportRepository
     * @return void
     */
    public function injectReportRepository(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
    * Inject feedbacksRepository
    *
    * @param FeedbacksRepository $feedbacksRepository
    * @return void
    */
    public function injectFeedbacksRepository(FeedbacksRepository $feedbacksRepository)
    {
        $this->feedbacksRepository = $feedbacksRepository;
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();
        if (version_compare(TYPO3_branch, '9.0', '>')) {
            $languageid = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Context\Context::class)->getAspect('language');
            $this->sys_language_uid = $languageid->getId();
        } else {
            $this->sys_language_uid = $GLOBALS['TSFE']->sys_language_uid;
        }
    }

    /**
     * action show
     *
     * @param Report $report
     * @return void
     */
    public function showAction(Report $report)
    {
        $this->view->assign('report', $report);
    }

    /**
     * action new
     *
     * @return void
     */
    public function newAction()
    {
        $this->reportRepository->getFromAll();

        $assign = [];
        $data = $GLOBALS['TSFE']->page;

        //Fetch Content data
        $contentData = $this->configurationManager->getContentObject();
        $cdata = $contentData->data;

        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $_SERVER['REMOTE_ADDR'];
        $filterData['feedbackType'] = 3;
        $newsParams = GeneralUtility::_GP('tx_news_pi1');

        $newsParams['news'] = isset($newsParams['news']) ? $newsParams['news'] : '';
        if ($newsParams['news'] > 0) {
            $newsId = $newsParams['news'];
            $filterData['newsId'] = $newsId;
            $assign['newsId'] = $newsId;
        }

        //Array for the buttons for the quick feedback form
        if ($this->settings['quickbuttons']) {
            $btns = explode(',', $this->settings['quickbuttons']);
            $assign['quickbuttons'] = $btns;
        }
        /*check records exist or not*/
        $Existrecord = $this->reportRepository->checkExistRecord($filterData);
        $pageRender = GeneralUtility::makeInstance(PageRenderer::class);
        $js = '';

        if ($this->settings['quickenable']) {
            unset($filterData['userIp']);
            unset($filterData['cid']);
            $getFeedbacks = $this->reportRepository->checkExistRecord($filterData);
            $assign['feedbacks'] = $getFeedbacks;
        }
        if ($Existrecord) {
            $assign['exist'] = $Existrecord;
        }
        $assign['cData'] = $cdata;
        $this->view->assignMultiple($assign);
    }

    /**
    * action new
    *
    * @return void
    */
    public function defaultAction()
    {
        $this->reportRepository->getFromAll();

        $assign = [];
        $data = $GLOBALS['TSFE']->page;

        //Fetch Content data
        $contentData = $this->configurationManager->getContentObject();
        $cdata = $contentData->data;

        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $_SERVER['REMOTE_ADDR'];
        $filterData['feedbackType'] = 3;
        $newsParams = GeneralUtility::_GP('tx_news_pi1');

        $newsParams['news'] = isset($newsParams['news']) ? $newsParams['news'] : '';
        if ($newsParams['news'] > 0) {
            $newsId = $newsParams['news'];
            $filterData['newsId'] = $newsId;
            $assign['newsId'] = $newsId;
        }

        //Array for the buttons for the quick feedback form
        if ($this->settings['quickbuttons']) {
            $btns = explode(',', $this->settings['quickbuttons']);
            $assign['quickbuttons'] = $btns;
        }
        /*check records exist or not*/
        $Existrecord = $this->reportRepository->checkExistRecord($filterData);
        $pageRender = GeneralUtility::makeInstance(PageRenderer::class);
        $js = '';

        if ($this->settings['quickenable']) {
            unset($filterData['userIp']);
            unset($filterData['cid']);
            $getFeedbacks = $this->reportRepository->checkExistRecord($filterData);
            $assign['feedbacks'] = $getFeedbacks;
        }
        if ($Existrecord) {
            $assign['exist'] = $Existrecord;
        }
        $assign['cData'] = $cdata;
        $this->view->assignMultiple($assign);
    }

    /**
     * action quickFeedback
     *
     * @param array $result
     * @return string
     */
    public function quickFeedbackAction($result = null)
    {
        $this->reportRepository->getFromAll();
        $report = new Report();
        $feedbacks = new Feedbacks();
        $data = $GLOBALS['TSFE']->page;
        if ($result['newsId'] > 0) {
            $checkExistRecord = $this->reportRepository->findByRecordId($result['newsId']);
        } else {
            $checkExistRecord = $this->reportRepository->findByPageId($data['uid']);
        }

        if ($checkExistRecord[0]) {
            $report = $checkExistRecord[0];
            $checkExistFeedbackRecord = $this->feedbacksRepository->findByUserIp($_SERVER['REMOTE_ADDR']);

            if (empty($checkExistFeedbackRecord[0])) {
                $feedbacks->setUserIp($_SERVER['REMOTE_ADDR']);
                $feedbacks->setFeedbackType($result['feedbackType']);
                $feedbacks->setQuickfeedbacktype($result['qkbutton']);
                $feedbacks->setSysLangId($this->sys_language_uid);

                if ($result['buttonfor'] == 3 || $result['buttonfor'] == 4) {
                    $feedbacks->setComment($result['commentText']);
                }

                $feedbacks->setPId($data['uid']);
                $feedbacks->setCId($result['cid']);
                $report->addFeedback($feedbacks);
                if ($result['newsId'] > 0) {
                    $report->setRecordId($result['newsId']);
                }

                switch ($result['buttonfor']) {
                    case '1':
                        $getexistCount = $checkExistRecord[0]->getFeedbackYesCount();
                        $getexistCount = $getexistCount + 1;
                        $report->setFeedbackYesCount($getexistCount);
                        break;

                    case '2':
                        $getexistCount = $checkExistRecord[0]->getFeedbackNoCount();
                        $getexistCount = $getexistCount + 1;
                        $report->setFeedbackNoCount($getexistCount);
                        break;

                    case '3':
                        $getexistCount = $checkExistRecord[0]->getFeedbackYesButCount();
                        $getexistCount = $getexistCount + 1;
                        $report->setFeedbackYesButCount($getexistCount);
                        break;

                    case '4':
                        $getexistCount = $checkExistRecord[0]->getFeedbackNoButCount();
                        $getexistCount = $getexistCount + 1;
                        $report->setFeedbackNoButCount($getexistCount);
                        break;
                }

                $report->setPageId($data['uid']);
                $report->setPId($data['uid']);
                $report->setPageType($data['doktype']);
                $report->setPageTitle($data['title']);
                $report->setSysLangId($this->sys_language_uid);
                $this->reportRepository->update($report);
            }
        } else {
            $feedbacks->setUserIp($_SERVER['REMOTE_ADDR']);
            $feedbacks->setFeedbackType($result['feedbackType']);
            $feedbacks->setQuickfeedbacktype($result['qkbutton']);
            $feedbacks->setSysLangId($this->sys_language_uid);

            if ($result['buttonfor'] == 3 || $result['buttonfor'] == 4) {
                $feedbacks->setComment($result['commentText']);
            }
            $feedbacks->setPId($data['uid']);
            $feedbacks->setCId($result['cid']);
            $report->addFeedback($feedbacks);

            switch ($result['buttonfor']) {
                case '1':
                    $report->setFeedbackYesCount(1);
                    break;

                case '2':
                    $report->setFeedbackNoCount(1);
                    break;

                case '3':
                    $report->setFeedbackYesButCount(1);
                    break;

                case '4':
                    $report->setFeedbackNoButCount(1);
                    break;
            }

            if ($result['newsId'] > 0) {
                $report->setRecordId($result['newsId']);
            }
            $report->setPageType($data['doktype']);
            $report->setPageId($data['uid']);
            $report->setPId($data['uid']);

            $report->setPageTitle($data['title']);
            $report->setSysLangId($this->sys_language_uid);
            $this->reportRepository->add($report);
        }
        return 'OK';
    }
}
