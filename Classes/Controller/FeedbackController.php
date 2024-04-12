<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Model\Feedbacks;
use NITSAN\NsFeedback\Domain\Model\Report;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Frontend\ContentObject\AbstractContentObject;

class FeedbackController extends ActionController
{
    /**
     * feedbacksRepository
     *
     * @var FeedbacksRepository
     */
    protected FeedbacksRepository $feedbacksRepository;

    /**
     * reportRepository
     *
     * @var ReportRepository
     */
    protected ReportRepository $reportRepository;

    public function __construct(
        ReportRepository $reportRepository,
        FeedbacksRepository $feedbacksRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->feedbacksRepository = $feedbacksRepository;
    }


    /**
     * action new
     *
     */
    public function newAction(): ResponseInterface
    {
        // Request Data
        $this->reportRepository->getFromAll();
        $assign = [];

        //Fetch Content data
        // @extensionScannerIgnoreLine
        $contentData = $this->configurationManager->getContentObject();
        $cdata = $contentData->data;
        $filterData = [
            'pId' => $GLOBALS['TSFE']->page['uid'],
            'cid' => $cdata['uid'],
            'userIp' => $_SERVER['REMOTE_ADDR'],
        ];

        //Array for the buttons for the quick feedback form
        $btns = [];
        if($this->settings['quickbuttonsYes'] == 1) {
            $btns[] = 1;
        }
        if($this->settings['quickbuttonsNo'] == 1) {
            $btns[] = 2;
        }
        if($this->settings['quickbuttonsYesBut'] == 1) {
            $btns[] = 3;
        }
        if($this->settings['quickbuttonsNoBut'] == 1) {
            $btns[] = 4;
        }
        $assign['quickbuttons'] = $btns;
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
        $this->view->assignMultiple($assign);
        return $this->htmlResponse();
    }

    /**
     * action quickFeedback
     *
     * @param array|null $result
     * @return ResponseInterface
     * @throws AspectNotFoundException
     * @throws IllegalObjectTypeException
     * @throws UnknownObjectException
     */
    public function quickFeedbackAction(array $result = null): ResponseInterface
    {
        $languageid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
        $this->reportRepository->getFromAll();
        $report = new Report();
        $feedbacks = new Feedbacks();
        $data = $GLOBALS['TSFE']->page;
        $feedbacks->setUserIp($_SERVER['REMOTE_ADDR']);
        $feedbacks->setQuickfeedbacktype($result['qkbutton']);
        $feedbacks->setSysLangId($languageid);
        if ($result['buttonfor'] == 3 || $result['buttonfor'] == 4) {
            $feedbacks->setComment($result['commentText']);
        }
        $feedbacks->setPId($data['uid']);
        $feedbacks->setCId($result['cid']);
        $checkExistRecord = $this->reportRepository->findBy(['pid' => $data['uid']]);
        $checkExistFeedbackRecord = $this->feedbacksRepository->findBy(['user_ip' => $_SERVER['REMOTE_ADDR']]);
        if ($checkExistRecord[0] && empty($checkExistFeedbackRecord[0])) {
            $report = $checkExistRecord[0];
            switch ($result['buttonfor']) {
                case '1':
                    $getexistCount = ($checkExistRecord[0]->getFeedbackYesCount()) + 1;
                    $report->setFeedbackYesCount($getexistCount);
                    break;
                case '2':
                    $getexistCount = ($checkExistRecord[0]->getFeedbackNoCount()) + 1;
                    $report->setFeedbackNoCount($getexistCount);
                    break;
                case '3':
                    $getexistCount = ($checkExistRecord[0]->getFeedbackYesButCount()) + 1;
                    $report->setFeedbackYesButCount($getexistCount);
                    break;
                case '4':
                    $getexistCount = ($checkExistRecord[0]->getFeedbackNoButCount()) + 1;
                    $report->setFeedbackNoButCount($getexistCount);
                    break;
                default:
                    break;
            }

        } else {
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
        }
        $report->addFeedback($feedbacks);
        $report->setPageId($data['uid']);
        $report->setPId($data['uid']);
        $report->setPageType($data['doktype']);
        $report->setPageTitle($data['title']);
        $report->setSysLangId($languageid);
        if ($checkExistRecord[0] && empty($checkExistFeedbackRecord[0])) {
            $this->reportRepository->update($report);
        } else {
            $this->reportRepository->add($report);
        }
        return $this->jsonResponse(json_encode(['Status' => 'Success']));
    }
}
