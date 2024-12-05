<?php

namespace NITSAN\NsFeedback\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/***
 *
 * This file is part of the "feedback" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Sanjay Chauhan <sanjay@nitsan.in>, NITSAN Technologies Pvt Ltd
 *
 ***/
/**
 * The repository for Reports
 */
class ReportRepository extends Repository
{
    /**
     * @var array<non-empty-string, 'ASC'|'DESC'>
     */
    protected $defaultOrderings = [
        'uid' => QueryInterface::ORDER_DESCENDING
    ];

    /**
     * getFromAll function
     *
     * @return void
     */
    public function getFromAll(): void
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * checkExistRecord function
     *
     * @param array|null $filterData
     * @return QueryResultInterface
     */
    public function checkExistRecord(array $filterData = null): QueryResultInterface
    {
        $query = $this->createQuery();

        $filterData['newsId'] = $filterData['newsId'] ?? '';
        $filterData['pId'] = $filterData['pId'] ?? '';
        $filterData['cid'] = $filterData['cid'] ?? '';
        $filterData['userIp'] = $filterData['userIp'] ?? '';
        $filterData['feedbackType'] = $filterData['feedbackType'] ?? '';
       

        if ($filterData['newsId']) {
            $main[] =  $query->equals('record_id', $filterData['newsId']);
        }
        $main[] =  $query->equals('feedbacks.pid', $filterData['pId']);
        if ($filterData['cid']) {
            $main[] = $query->equals('feedbacks.cid', (int)$filterData['cid']);
        }
        if ($filterData['userIp']) {
            $main[] =  $query->equals('feedbacks.user_ip', (string)$filterData['userIp']);
        }
        $main[] =  $query->equals('feedbacks.feedback_type', (int)$filterData['feedbackType']);
        $query->matching(
            $query->logicalAnd(...$main)
        );
        return $query->execute();
    }
    public function findAllByLanguage(): array|QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        return $query->execute();
    }

}
