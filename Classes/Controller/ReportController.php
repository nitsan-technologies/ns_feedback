<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use NITSAN\NsFeedback\NsTemplate\ExtendedTemplateService;
use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/***
 *
 * This file is part of the "[NITSAN] feedback" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Sanjay Chauhan <sanjay@nitsan.in>, NITSAN Technologies Pvt Ltd
 *
 ***/
/**
 * ReportController
 */
class ReportController extends ActionController
{
    /**
     * reportRepository
     *
     * @var ReportRepository
     * @inject
     */
    protected $reportRepository = null;

    /**
     * feedbacksRepository
     *
     * @var FeedbacksRepository
     * @inject
     */
    protected $feedbacksRepository = null;

    /**
     *
     */
    protected $newsRepository;

    protected $templateService;
    protected $constantObj;
    protected $sidebarData;
    protected $dashboardSupportData;
    protected $generalFooterData;
    protected $constants;
    protected $contentObject = null;
    protected $pid = null;

    protected $pObj;
    /*
     * Inject reportRepository
     *
     * @param \NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository
     * @return void
     */
    public function injectReportRepository(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /*
    * Inject feedbacksRepository
    *
    * @param \NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository $feedbacksRepository
    * @return void
    */
    public function injectFeedbacksRepository(FeedbacksRepository $feedbacksRepository)
    {
        $this->feedbacksRepository = $feedbacksRepository;
    }

    /**
     * Initializes this object
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->contentObject = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
        $this->templateService = GeneralUtility::makeInstance(ExtendedTemplateService::class);
        $this->constantObj = GeneralUtility::makeInstance(TypoScriptTemplateConstantEditorModuleFunctionController::class);
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();

        //GET and SET pid for the
        $this->pid = (GeneralUtility::_GP('id') ? GeneralUtility::_GP('id') : '0');
        $querySettings = $this->reportRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->reportRepository->setDefaultQuerySettings($querySettings);

        //GET CONSTANTs
        $this->constantObj->init($this->pObj);
        $this->constants = $this->constantObj->main();
    }

    /**
     * action appearanceSettings
     *
     * @return void
     */
    public function appearanceSettingsAction()
    {
        $assign = [
            'action' => 'appearanceSettings',
            'constant' => $this->constants
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action commonSettings
     *
     * @return void
     */
    public function commonSettingsAction()
    {
        $assign = [
            'action' => 'commonSettings',
            'constant' => $this->constants
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action dashboard
     *
     * @return void
     */
    public function dashboardAction()
    {
        $this->reportRepository->setDefaultOrderings(['feedbacks.uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING]);
        $reports = $this->reportRepository->findAllByLanguage();

        //set default query builder for mm table
        $querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
        $querySettings->setRespectStoragePage(true);
        $pagid = GeneralUtility::_GP('id');
        $querySettings->setStoragePageIds([$pagid]);
        $this->feedbacksRepository->setDefaultQuerySettings($querySettings);
        $total = $this->feedbacksRepository->countAllByLanguage();

        $yesCount = 0;
        $noCount = 0;
        $yesbutCount = 0;
        $nobutCount = 0;

        foreach ($reports as  $report) {
            $yesCount += $report->getFeedbackYesCount();
            $noCount += $report->getFeedbackNoCount();
            $yesbutCount += $report->getFeedbackYesButCount();
            $nobutCount += $report->getFeedbackNoButCount();
        }

        $totalratings = '';
        $report = '';

        $assign = [
            'action' => 'dashboard',
            'pid' => $this->pid,
            'rightSide' => $this->sidebarData,
            'dashboardSupport' => $this->dashboardSupportData,
            'totalyescount' => $yesCount,
            'totalnocount' => $noCount,
            'totalyesbutcount' => $yesbutCount,
            'totalnobutcount' => $nobutCount,
            'total' => $total,
            'totalrating' => $totalratings,
            'report' => $report
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action show
     *
     * @param \NITSAN\NsFeedback\Domain\Model\Report $report
     * @return void
     */
    public function showAction(\NITSAN\NsFeedback\Domain\Model\Report $report)
    {
        $this->view->assign('report', $report);
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $reports = $this->reportRepository->findAllByLanguage();

        foreach ($reports as  $report) {
            //quick feedback count
            $yesCount = $report->getFeedbackYesCount();
            $noCount = $report->getFeedbackNoCount();
            $yesButCount = $report->getFeedbackYesButCount();
            $noButCount = $report->getFeedbackNoButCount();
            $total = $yesCount + $noCount + $yesButCount + $noButCount;

            $totalfeed[$report->getUid()]['quicktotal'] = $total;

            //Fetching the news record if available
            if ($report->getRecordId()) {
                $this->newsRepository = $this->objectManager->get('GeorgRinger\News\Domain\Repository\NewsRepository');
                $newsData[$report->getUid()] = $this->newsRepository->findByUid($report->getRecordId());
            }
        }
        $totalfeed = isset($totalfeed) ? $totalfeed : '';
        $reports = isset($reports) ? $reports : '';
        $newsData = isset($newsData) ? $newsData : '';

        $assign = [
            'totalfeedback' => $totalfeed,
            'reports' => $reports,
            'newsitems' => $newsData,
            'action' => 'list',
        ];
        $this->view->assignMultiple($assign);
    }


    /**
     * action saveConstant
     */
    public function saveConstantAction()
    {
        $this->constantObj->main();
        $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action'] = isset($_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action']) ? $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action'] : '';
        $returnAction = $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action']; //get action name
        return false;
    }
}
