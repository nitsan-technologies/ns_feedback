<?php

namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\Domain\Model\Report;
use NITSAN\NsFeedback\NsTemplate\ExtendedTemplateService;
use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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
     * @var \NITSAN\NsFeedback\Domain\Repository\ReportRepository
     */
    protected \NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository;

    /**
     * FeedbacksRepository
     *
     * @var \NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository
     */
    protected \NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository $feedbacksRepository;

    protected $templateService;
    protected $constantObj;
    protected $sidebarData;
    protected $dashboardSupportData;
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
    public function injectReportRepository(\NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository): void
    {
        $this->reportRepository = $reportRepository;
    }

    /*
    * Inject feedbacksRepository
    *
    * @param \NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository $feedbacksRepository
    * @return void
    */
    public function injectFeedbacksRepository(\NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository $feedbacksRepository): void
    {
        $this->feedbacksRepository = $feedbacksRepository;
    }

    /**
     * Initializes this object
     *
     * @return void
     */
    public function initializeObject(): void
    {
        $this->contentObject = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $this->templateService = GeneralUtility::makeInstance(ExtendedTemplateService::class);
        $this->constantObj = GeneralUtility::makeInstance(TypoScriptTemplateConstantEditorModuleFunctionController::class);
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction(): void
    {
        parent::initializeAction();
        //GET and SET pid for the
        $this->pid = (GeneralUtility::_GP('id') ? GeneralUtility::_GP('id') : '0');
        $querySettings = $this->reportRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->reportRepository->setDefaultQuerySettings($querySettings);
        //GET CONSTANTs
        $this->constantObj->init($this->pObj);
        //@extensionScannerIgnoreLine
        $this->constants = $this->constantObj->main();
    }

    /**
     * action appearanceSettings
     *
     * @return void
     */
    public function appearanceSettingsAction(): void
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
    public function commonSettingsAction(): void
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
    public function dashboardAction(): void
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
            'totalrating' => '',
            'report' => ''
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action show
     *
     * @param Report $report
     * @return void
     */
    public function showAction(Report $report): void
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
        }
        $totalfeed = $totalfeed ?? '';
        $reports = $reports ?? '';
        $assign = [
            'totalfeedback' => $totalfeed,
            'reports' => $reports,
            'action' => 'list',
        ];
        $this->view->assignMultiple($assign);
    }


    /**
     * action saveConstant
     */
    public function saveConstantAction(): bool
    {
        //@extensionScannerIgnoreLine
        $this->constantObj->main();
        $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action'] = isset($_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action']) ? $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action'] : '';
        return false;
    }
}
