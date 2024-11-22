<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Model\Report;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FeedbackController extends ActionController
{
    /**
     * feedbacksRepository
     *
     * @var FeedbacksRepository
     */
    protected $feedbacksRepository = null;

    /**
     * reportRepository
     *
     * @var ReportRepository
     */
    protected $reportRepository = null;

    /*
     * sys_language_uid
     * @int
     */
    protected $sys_language_uid = null;

    public function __construct(ReportRepository $reportRepository, FeedbacksRepository $feedbacksRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->feedbacksRepository = $feedbacksRepository;
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
     */
    public function newAction()
    {

        // Request Data
        $getData = $this->request->getQueryParams();
        $postData = $this->request->getParsedBody();
        $requestData = array_merge((array)$getData, (array)$postData);

        $this->reportRepository->getFromAll();

        $assign = [];
        $data = $GLOBALS['TSFE']->page;

        //Fetch Content data
        $contentData = $this->request->getAttribute('currentContentObject');
        $cdata = $contentData->data;

        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $_SERVER['REMOTE_ADDR'];
        $filterData['feedbackType'] = 3;

        if(isset($requestData['tx_news_pi1'])) {
            $newsParams = $requestData['tx_news_pi1'];
            $newsParams['news'] = isset($newsParams['news']) ? $newsParams['news'] : '';
            if ($newsParams['news'] > 0) {
                $newsId = $newsParams['news'];
                $filterData['newsId'] = $newsId;
                $assign['newsId'] = $newsId;
            }
        }

        //Array for the buttons for the quick feedback form
        $btns = [];

        if($this->settings['quickbuttonsYes'] == 1) {
            array_push($btns, 1);
        }
        if($this->settings['quickbuttonsNo'] == 1) {
            array_push($btns, 2);
        }
        if($this->settings['quickbuttonsYesBut'] == 1) {
            array_push($btns, 3);
        }
        if($this->settings['quickbuttonsNoBut'] == 1) {
            array_push($btns, 4);
        }
        $assign['quickbuttons'] = $btns;
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
        return $this->htmlResponse();
    }

    /**
    * action new
    *
    */
    public function defaultAction()
    {

        // Request Data
        $getData = $this->request->getQueryParams();
        $postData = $this->request->getParsedBody();
        $requestData = array_merge((array)$getData, (array)$postData);

        $this->reportRepository->getFromAll();

        $assign = [];
        $data = $GLOBALS['TSFE']->page;

        //Fetch Content data
        $contentData = $this->request->getAttribute('currentContentObject');
        $cdata = $contentData->data;

        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $_SERVER['REMOTE_ADDR'];
        $filterData['feedbackType'] = 3;
        $newsParams = $requestData['tx_news_pi1'];

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

        return $this->htmlResponse();
    }

    /**
     * action quickFeedback
     *
     * @param array $result
     */
    public function quickFeedbackAction($result = null)
    {
        $languageid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');

        $this->reportRepository->getFromAll();
        $report = new Report();
        $feedbacks = new \NITSAN\NsFeedback\Domain\Model\Feedbacks();
        $data = $GLOBALS['TSFE']->page;
        if ($result['newsId'] > 0) {
            $checkExistRecord = $this->reportRepository->findBy(['record_id' => $data['uid']]);
            $checkExistRecord = $this->reportRepository->findByRecordId($result['newsId']);
        } else {
            $checkExistRecord = $this->reportRepository->findBy(['pid' => $data['uid']]);
            $checkExistRecord = $this->reportRepository->findByPageId($data['uid']);
        }

        if ($checkExistRecord[0]) {
            $report = $checkExistRecord[0];
            $checkExistFeedbackRecord = $this->feedbacksRepository->findBy(['user_ip' => $_SERVER['REMOTE_ADDR']]);
            $checkExistFeedbackRecord = $this->feedbacksRepository->findByUserIp($_SERVER['REMOTE_ADDR']);

            if (empty($checkExistFeedbackRecord[0])) {
                $feedbacks->setUserIp($_SERVER['REMOTE_ADDR']);
                $feedbacks->setFeedbackType($result['feedbackType']);
                $feedbacks->setQuickfeedbacktype($result['qkbutton']);
                $feedbacks->setSysLangId($languageid);

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
                $report->setSysLangId($languageid);
                $this->reportRepository->update($report);
            }
        } else {
            $feedbacks->setUserIp($_SERVER['REMOTE_ADDR']);
            $feedbacks->setFeedbackType($result['feedbackType']);
            $feedbacks->setQuickfeedbacktype($result['qkbutton']);
            $feedbacks->setSysLangId($languageid);

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
                $report->setPageType($data['doktype']);
            } else {
                $report->setPageType($data['doktype']);
            }
            $report->setPageId($data['uid']);
            $report->setPId($data['uid']);

            $report->setPageTitle($data['title']);
            $report->setSysLangId($languageid);
            $this->reportRepository->add($report);
        }

        return $this->jsonResponse(json_encode(['Status' => 'Success']));
    }
}
