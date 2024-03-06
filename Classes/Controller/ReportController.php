<?php

namespace NITSAN\NsFeedback\Controller;

use GeorgRinger\News\Domain\Repository\NewsRepository;
use NITSAN\NsFeedback\Domain\Model\Report;
use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

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
     */
    protected $reportRepository = null;

    /**
     * feedbacksRepository
     *
     * @var FeedbacksRepository
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
    protected $premiumExtensionData;
    protected $constants;
    protected $contentObject = null;
    protected $pid = null;

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        ReportRepository $reportRepository,
        FeedbacksRepository $feedbacksRepository
    ) {
        $this->reportRepository = $reportRepository;
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
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();

        // Request Data
        $getData = $this->request->getQueryParams();
        $postData = $this->request->getParsedBody();
        $requestData = array_merge((array)$getData, (array)$postData);

        //GET and SET pid for the
        $this->pid = isset($requestData['id']) ? $requestData['id'] : '0' ;
        $querySettings = $this->reportRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->reportRepository->setDefaultQuerySettings($querySettings);

    }


    /**
     * action dashboard
     *
     */
    public function dashboardAction()
    {
        $view = $this->initializeModuleTemplate($this->request);
        $this->reportRepository->setDefaultOrderings(['feedbacks.uid' => QueryInterface::ORDER_DESCENDING]);
        $reports = $this->reportRepository->findAllByLanguage();

        // Request Data
        $getData = $this->request->getQueryParams();
        $postData = $this->request->getParsedBody();
        $requestData = array_merge((array)$getData, (array)$postData);

        //set default query builder for mm table
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(true);
        $pagid = isset($requestData['id']) ? $requestData['id'] : '0' ;
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
        $view->assignMultiple($assign);

        return $view->renderResponse();
    }

    /**
     * action show
     *
     * @param Report $report
     */
    public function showAction(Report $report)
    {
        $view = $this->initializeModuleTemplate($this->request);
        $view->assign('report', $report);

        return $view->renderResponse();
    }

    /**
     * action list
     *
     */
    public function listAction()
    {
        $view = $this->initializeModuleTemplate($this->request);
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
                $this->newsRepository = GeneralUtility::makeInstance(NewsRepository::class);
                $newsData[$report->getUid()] = $this->newsRepository->findByUid($report->getRecordId());
            }
        }
        $totalfeed = isset($totalfeed) ? $totalfeed : '';
        $newsData = isset($newsData) ? $newsData : '';

        $assign = [
            'totalfeedback' => $totalfeed,
            'reports' => $reports,
            'newsitems' => $newsData,
            'action' => 'list',
        ];
        $view->assignMultiple($assign);
        return $view->renderResponse();
    }

    /**
     * Generates the action menu
     */
    protected function initializeModuleTemplate(
        ServerRequestInterface $request
    ): ModuleTemplate {
        return $this->moduleTemplateFactory->create($request);
    }
}
