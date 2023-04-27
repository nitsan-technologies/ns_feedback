<?php
namespace NITSAN\NsFeedback\Controller;

use NITSAN\NsFeedback\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;


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
class ReportController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory
    ) {
    }

    /**
     * reportRepository
     *
     * @var \NITSAN\NsFeedback\Domain\Repository\ReportRepository
     */
    protected $reportRepository = null;

    /**
     * feedbacksRepository
     *
     * @var \NITSAN\NsFeedback\Domain\Repository\FeedbacksRepository
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

    /**
     * @var TypoScriptTemplateModuleController
     */
    protected $pObj;

    /*
     * Inject reportRepository
     *
     * @param \NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository
     * @return void
     */
    public function injectReportRepository(\NITSAN\NsFeedback\Domain\Repository\ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    /*
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
        $requestData = array_merge((array)$getData,(array)$postData);

        //GET and SET pid for the
        $this->pid = (isset($requestData['id']) ? $requestData['id'] : '0');
        $querySettings = $this->reportRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->reportRepository->setDefaultQuerySettings($querySettings);

    }


    /**
     * action dashboard
     *
     * @return void
     */
    public function dashboardAction()
    {
        $view = $this->initializeModuleTemplate($this->request);
        $this->reportRepository->setDefaultOrderings(['feedbacks.uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING]);
        $reports = $this->reportRepository->findAll();

        // Request Data
        $getData = $this->request->getQueryParams();
        $postData = $this->request->getParsedBody();
        $requestData = array_merge((array)$getData,(array)$postData);

        //set default query builder for mm table
        $querySettings = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(true);
        $pagid = $requestData['id'];
        $querySettings->setStoragePageIds([$pagid]);
        $this->feedbacksRepository->setDefaultQuerySettings($querySettings);
        $total = $this->feedbacksRepository->countAll();

        $yesCount = 0;
        $noCount = 0;
        $yesbutCount = isset($yesbutCount) ? $yesbutCount : 0;
        $nobutCount = isset($nobutCount) ? $nobutCount : 0;

        foreach ($reports as  $report) {
            $yesCount += $report->getFeedbackYesCount();
            $noCount += $report->getFeedbackNoCount();
            $yesbutCount += $report->getFeedbackYesButCount();
            $nobutCount += $report->getFeedbackNoButCount();
        }

        $totalratings = isset($totalratings) ? $totalratings : '';
        $report = isset($report) ? $report : '';

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
     * @param \NITSAN\NsFeedback\Domain\Model\Report $report
     * @return void
     */
    public function showAction(\NITSAN\NsFeedback\Domain\Model\Report $report)
    {
        $view = $this->initializeModuleTemplate($this->request);
        $view->assign('report', $report);

        return $view->renderResponse();
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $view = $this->initializeModuleTemplate($this->request);
        $reports = $this->reportRepository->findAll();

        foreach ($reports as  $report) {
            //quick feedback count
            $yesCount = $report->getFeedbackYesCount();
            $noCount = $report->getFeedbackNoCount();
            $yesButCount = $report->getFeedbackYesButCount();
            $noButCount = $report->getFeedbackNoButCount();
            $total = $yesCount+$noCount+$yesButCount+$noButCount;

            $totalfeed[$report->getUid()]['quicktotal'] = $total;

            //Fetching the news record if available
            if ($report->getRecordId()) {
                $this->newsRepository = GeneralUtility::makeInstance(\GeorgRinger\News\Domain\Repository\NewsRepository::class);
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
