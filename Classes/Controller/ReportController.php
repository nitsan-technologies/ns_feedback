<?php

namespace NITSAN\NsFeedback\Controller;

use GeorgRinger\News\Domain\Repository\NewsRepository;
use NITSAN\NsFeedback\Domain\Model\Report;
use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateModuleController;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use NITSAN\NsFeedback\Domain\Repository\ReportRepository;
use NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
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
     * @var ReportRepository
     */
    protected ReportRepository $reportRepository;

    /**
     * feedbacksRepository
     *
     * @var FeedbacksRepository
     */
    protected FeedbacksRepository $feedbacksRepository;


    protected $newsRepository;
    protected $sidebarData;
    protected $dashboardSupportData;
    protected $constants;
    protected $contentObject = null;
    protected $pid = null;

    protected ModuleTemplateFactory $moduleTemplateFactory;

    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        ReportRepository $reportRepository,
        FeedbacksRepository $feedbacksRepository
    ) {
        $this->moduleTemplateFactory = $moduleTemplateFactory;
        $this->reportRepository = $reportRepository;
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
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction(): void
    {
        parent::initializeAction();

        // Request Data
        $getData = $this->request->getQueryParams();
        $postData = $this->request->getParsedBody();
        $requestData = array_merge((array)$getData, (array)$postData);

        //GET and SET pid for the
        $this->pid = $requestData['id'] ?? '0';
        $querySettings = $this->reportRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->reportRepository->setDefaultQuerySettings($querySettings);

    }


    /**
     * action dashboard
     * @return ResponseInterface
     */
    public function dashboardAction(): ResponseInterface
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
        $pagid = $requestData['id'] ?? '0';
        $querySettings->setStoragePageIds([$pagid]);
        $this->feedbacksRepository->setDefaultQuerySettings($querySettings);
        $total = $this->feedbacksRepository->countAllByLanguage();

        $yesCount = $noCount = $yesbutCount = $nobutCount = 0;

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
        $view->assignMultiple($assign);

        return $view->renderResponse();
    }

    /**
     * action show
     *
     * @param Report $report
     * @return ResponseInterface
     */
    public function showAction(Report $report): ResponseInterface
    {
        $view = $this->initializeModuleTemplate($this->request);
        $view->assign('report', $report);

        return $view->renderResponse();
    }

    /**
     * action list
     * @return ResponseInterface
     */
    public function listAction(): ResponseInterface
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
        }
        $totalfeed = $totalfeed ?? '';
        $assign = [
            'totalfeedback' => $totalfeed,
            'reports' => $reports,
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
