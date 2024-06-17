<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Model\Feedbacks;
use NITSAN\NsFeedback\Domain\Model\Report;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

class FeedbackController extends ActionController
{

    /**
     * @var $feedbacksRepository
     */
    protected $feedbacksRepository;
    /**
     * @var $reportRepository
     */
    protected $reportRepository;
    /*
     * sys_language_uid
     * @int
     * @extensionScannerIgnoreLine
     */
    protected $sys_language_uid = null;

    /**
     * Inject reportRepository
     *
     * @param \NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository
     * @return void
     */
    public function injectReportRepository(\NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /**
    * Inject feedbacksRepository
    *
    * @param \NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository $feedbacksRepository
    * @return void
    */
    public function injectFeedbacksRepository(\NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository $feedbacksRepository)
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
    public function newAction(): void
    {
        $assign = $this->getFeedbackPageData();
        $this->view->assignMultiple($assign);
    }

    /**
     * action new
     *
     * @return void
     */
    public function defaultAction(): void
    {
        $assign = $this->getFeedbackPageData();
        $this->view->assignMultiple($assign);
    }

    /**
     * @return array
     */
    public function getFeedbackPageData(): array
    {
        $this->reportRepository->getFromAll();
        $assign = [];
        $data = $GLOBALS['TSFE']->page;
        //Fetch Content data
        // @extensionScannerIgnoreLine
        $contentData = $this->configurationManager->getContentObject();
        $cdata = $contentData->data;
        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $_SERVER['REMOTE_ADDR'];
        //Array for the buttons for the quick feedback form
        if ($this->settings['quickbuttons']) {
            $btns = explode(',', $this->settings['quickbuttons']);
            $assign['quickbuttons'] = $btns;
            if(in_array(1, $btns)) {
                $assign['quickbuttonsYes'] = 1;
            }
            if(in_array(2, $btns)) {
                $assign['quickbuttonsNo'] = 1;
            }
            if(in_array(3, $btns)) {
                $assign['quickbuttonsYesBut'] = 1;
            }
            if(in_array(4, $btns)) {
                $assign['quickbuttonsNoBut'] = 1;
            }
        }
        /*check records exist or not*/
        $existrecord = $this->reportRepository->checkExistRecord($filterData);
        if ($this->settings['quickenable']) {
            unset($filterData['userIp']);
            unset($filterData['cid']);
            $getFeedbacks = $this->reportRepository->checkExistRecord($filterData);
            $assign['feedbacks'] = $getFeedbacks;
        }
        if ($existrecord) {
            $assign['exist'] = $existrecord;
        }
        $assign['cData'] = $cdata;
        return $assign;
    }

    /**
     * action quickFeedback
     *
     * @param array $result
     * @return string
     */
    public function quickFeedbackAction(array $result = []): string
    {
        $this->reportRepository->getFromAll();
        $report = new Report();
        $data = $GLOBALS['TSFE']->page;
        $feedbacks = new Feedbacks();
        $feedbacks->setUserIp($_SERVER['REMOTE_ADDR']);
        $feedbacks->setQuickfeedbacktype($result['qkbutton']);
        $feedbacks->setSysLangId($this->sys_language_uid);
        if ($result['buttonfor'] == 3 || $result['buttonfor'] == 4) {
            $feedbacks->setComment($result['commentText']);
        }
        $feedbacks->setPId($data['uid']);
        $feedbacks->setCId($result['cid']);
        $checkExistRecord = $this->reportRepository->findByPageId($data['uid']);

        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->feedbacksRepository->setDefaultQuerySettings($querySettings);

        $checkExistFeedbackRecord = $this->feedbacksRepository->findByUserIp($_SERVER['REMOTE_ADDR']);

        if ($checkExistRecord[0]) {
            $report = $checkExistRecord[0];
        }

        $report->setFeedbackYesCount(0);
        $report->setFeedbackNoCount(0);
        $report->setFeedbackYesButCount(0);
        $report->setFeedbackNoButCount(0);

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
            default:
                break;
        }

        $report->setPageType($data['doktype']);
        $report->setPageId($data['uid']);
        $report->setPId($data['uid']);
        $report->setPageTitle($data['title']);
        $report->setSysLangId($this->sys_language_uid);

        if($checkExistFeedbackRecord[0]){
            $checkExistFeedbackRecord[0]->setQuickfeedbacktype($result['qkbutton']);
            $checkExistFeedbackRecord[0]->setComment("");
            if ($result['buttonfor'] == 3 || $result['buttonfor'] == 4) {
                $checkExistFeedbackRecord[0]->setComment($result['commentText']);
            }
            $this->feedbacksRepository->update($checkExistFeedbackRecord[0]);
        }

        if ($checkExistRecord[0]) {
            $this->reportRepository->update($report);

        } else {
            $report->addFeedback($feedbacks);
            $this->reportRepository->add($report);
        }
        return 'OK';
    }
}
