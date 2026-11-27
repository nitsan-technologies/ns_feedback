<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Model\Feedbacks;
use NITSAN\NsFeedback\Domain\Model\Report;
use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Crypto\HashService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class FeedbackController extends ActionController
{
    private const FORM_TOKEN_SECRET = 'nsFeedbackQuickFeedback';

    private const ALLOWED_BUTTONS = ['1', '2', '3', '4'];

    private const ALLOWED_QUICK_TYPES = ['yes', 'no', 'yesbut', 'nobut'];

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
        $versionNumber = VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());
        if ($versionNumber['version_main'] <= '12') {
            $data = $GLOBALS['TSFE']->page;
        } else {
            $data = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord();
        }

        //Fetch Content data
        $contentData = $this->request->getAttribute('currentContentObject');
        $cdata = $contentData->data;

        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $this->getRemoteAddress();
        $filterData['feedbackType'] = 3;

        if (isset($requestData['tx_news_pi1'])) {
            $newsParams = $requestData['tx_news_pi1'];
            $newsParams['news'] = isset($newsParams['news']) ? $newsParams['news'] : '';
            if ((int)$newsParams['news'] > 0) {
                $newsId = (int)$newsParams['news'];
                $filterData['newsId'] = $newsId;
                $assign['newsId'] = $newsId;
            }
        }

        //Array for the buttons for the quick feedback form
        $btns = [];

        if (isset($this->settings['quickbuttonsYes']) && $this->settings['quickbuttonsYes'] == 1) {
            array_push($btns, 1);
        }
        if (isset($this->settings['quickbuttonsNo']) && $this->settings['quickbuttonsNo'] == 1) {
            array_push($btns, 2);
        }
        if (isset($this->settings['quickbuttonsYesBut']) && $this->settings['quickbuttonsYesBut'] == 1) {
            array_push($btns, 3);
        }
        if (isset($this->settings['quickbuttonsNoBut']) && $this->settings['quickbuttonsNoBut'] == 1) {
            array_push($btns, 4);
        }
        $assign['quickbuttons'] = $btns;
        /*check records exist or not*/
        $Existrecord = $this->reportRepository->checkExistRecord($filterData);

        if (isset($this->settings['quickenable']) && $this->settings['quickenable']) {
            unset($filterData['userIp']);
            unset($filterData['cid']);
            $getFeedbacks = $this->reportRepository->checkExistRecord($filterData);
            $assign['feedbacks'] = $getFeedbacks;
        }
        if ($Existrecord) {
            $assign['exist'] = $Existrecord;
        }
        $assign['cData'] = $cdata;
        $assign['layout'] = (isset($cdata['list_type']) && $cdata['list_type'] == 'nsfeedback_feedback') ? 'Default' : 'Global';
        $assign['formToken'] = $this->createQuickFeedbackToken((int)$data['uid'], (int)$cdata['uid']);
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
        $versionNumber = VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());
        if ($versionNumber['version_main'] <= '12') {
            $data = $GLOBALS['TSFE']->page;
        } else {
            $data = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord();
        }

        //Fetch Content data
        $contentData = $this->request->getAttribute('currentContentObject');
        $cdata = $contentData->data;

        $filterData['pId'] = $data['uid'];
        $filterData['cid'] = $cdata['uid'];
        $filterData['userIp'] = $this->getRemoteAddress();
        $filterData['feedbackType'] = 3;
        $newsParams = $requestData['tx_news_pi1'] ?? [];

        $newsParams['news'] = isset($newsParams['news']) ? $newsParams['news'] : '';
        if ((int)$newsParams['news'] > 0) {
            $newsId = (int)$newsParams['news'];
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
        $assign['formToken'] = $this->createQuickFeedbackToken((int)$data['uid'], (int)$cdata['uid']);
        $this->view->assignMultiple($assign);

        return $this->htmlResponse();
    }

    /**
     * action quickFeedback
     *
     * @param array $result
     */
    public function quickFeedbackAction(array $result = [])
    {
        $languageid = GeneralUtility::makeInstance(Context::class)->getPropertyFromAspect('language', 'id');
        $this->reportRepository->getFromAll();
        $report = new Report();
        $feedbacks = new Feedbacks();
        $versionNumber = VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());
        if ($versionNumber['version_main'] <= '12') {
            $data = $GLOBALS['TSFE']->page;
        } else {
            $data = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.page.information')->getPageRecord();
        }

        $pageId = (int)($data['uid'] ?? 0);
        $cid = (int)($result['cid'] ?? 0);
        $buttonfor = (string)($result['buttonfor'] ?? '');
        $qkbutton = (string)($result['qkbutton'] ?? '');
        $formToken = (string)($result['formToken'] ?? '');
        $feedbackType = (string)((int)($result['feedbackType'] ?? 3));
        $commentText = mb_substr((string)($result['commentText'] ?? ''), 0, 5000);
        $userIp = $this->getRemoteAddress();

        if (!$this->isValidQuickFeedbackRequest($pageId, $cid, $buttonfor, $qkbutton, $formToken)) {
            return $this->jsonResponse(json_encode(['Status' => 'Error']));
        }

        // Keep previous behaviour: newsId from the request was never applied.
        $newsId = 0;
        $checkExistRecord = $this->reportRepository->findByProperties($pageId, 'PageId');

        if ($checkExistRecord[0]) {
            $report = $checkExistRecord[0];
            $checkExistFeedbackRecord = $this->feedbacksRepository->findBy(['user_ip' => $userIp]);

            if (empty($checkExistFeedbackRecord[0])) {
                $feedbacks->setUserIp($userIp);
                $feedbacks->setFeedbackType($feedbackType);
                $feedbacks->setQuickfeedbacktype($qkbutton);
                $feedbacks->setSysLangId($languageid);

                if ($buttonfor === '3' || $buttonfor === '4') {
                    $feedbacks->setComment($commentText);
                }

                $feedbacks->setPId($pageId);
                $feedbacks->setCId($cid);
                $report->addFeedback($feedbacks);
                if ($newsId > 0) {
                    $report->setRecordId($newsId);
                }

                switch ($buttonfor) {
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

                $report->setPageId($pageId);
                $report->setPId($pageId);
                $report->setPageType($data['doktype']);
                $report->setPageTitle($data['title']);
                $report->setSysLangId($languageid);
                $this->reportRepository->update($report);
            }
        } else {
            $feedbacks->setUserIp($userIp);
            $feedbacks->setFeedbackType($feedbackType);
            $feedbacks->setQuickfeedbacktype($qkbutton);
            $feedbacks->setSysLangId($languageid);

            if ($buttonfor === '3' || $buttonfor === '4') {
                $feedbacks->setComment($commentText);
            }
            $feedbacks->setPId($pageId);
            $feedbacks->setCId($cid);
            $report->addFeedback($feedbacks);

            switch ($buttonfor) {
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

            if ($newsId > 0) {
                $report->setRecordId($newsId);
                $report->setPageType($data['doktype']);
            } else {
                $report->setPageType($data['doktype']);
            }
            $report->setPageId($pageId);
            $report->setPId($pageId);

            $report->setPageTitle($data['title']);
            $report->setSysLangId($languageid);
            $this->reportRepository->add($report);
        }

        return $this->jsonResponse(json_encode(['Status' => 'Success']));
    }

    protected function createQuickFeedbackToken(int $pageId, int $contentId): string
    {
        return $this->createHmac($pageId . '|' . $contentId);
    }

    protected function isValidQuickFeedbackRequest(
        int $pageId,
        int $contentId,
        string $buttonfor,
        string $qkbutton,
        string $formToken
    ): bool {
        // contentId may be 0 for page-level (allPage) embedding without a tt_content record
        if ($pageId <= 0 || $contentId < 0 || $formToken === '') {
            return false;
        }
        if (!in_array($buttonfor, self::ALLOWED_BUTTONS, true)) {
            return false;
        }
        if (!in_array($qkbutton, self::ALLOWED_QUICK_TYPES, true)) {
            return false;
        }

        return $this->validateHmac($pageId . '|' . $contentId, $formToken);
    }

    /**
     * Client IP from PSR-7 NormalizedParams (TYPO3 v14.3+ replacement for getIndpEnv).
     */
    protected function getRemoteAddress(): string
    {
        $normalizedParams = $this->request->getAttribute('normalizedParams');
        if ($normalizedParams !== null) {
            return (string)$normalizedParams->getRemoteAddress();
        }

        return (string)($_SERVER['REMOTE_ADDR'] ?? '');
    }

    /**
     * Compatible with TYPO3 v12 (GeneralUtility::hmac) and v13+ (HashService).
     */
    protected function createHmac(string $payload): string
    {
        if (class_exists(HashService::class)) {
            return GeneralUtility::makeInstance(HashService::class)
                ->hmac($payload, self::FORM_TOKEN_SECRET);
        }

        return GeneralUtility::hmac($payload, self::FORM_TOKEN_SECRET);
    }

    protected function validateHmac(string $payload, string $hmac): bool
    {
        if (class_exists(HashService::class)) {
            return GeneralUtility::makeInstance(HashService::class)
                ->validateHmac($payload, self::FORM_TOKEN_SECRET, $hmac);
        }

        return hash_equals(GeneralUtility::hmac($payload, self::FORM_TOKEN_SECRET), $hmac);
    }
}
