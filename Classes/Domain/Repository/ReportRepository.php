<?php

namespace NITSAN\NsFeedback\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * The repository for Reports
 */
class ReportRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @var array<non-empty-string, 'ASC'|'DESC'>
     */
    protected $defaultOrderings = [
        'uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
    ];

    /**
     * getFromAll function
     *
     * @return void
     */
    public function getFromAll()
    {
        $querySettings = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * checkExistRecord function
     *
     * @param array $filterData
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function checkExistRecord($filterData = null)
    {
        $query = $this->createQuery();

        $filterData['newsId'] = isset($filterData['newsId']) ? $filterData['newsId'] : '';
        $filterData['pId'] = isset($filterData['pId']) ? $filterData['pId'] : '';
        $filterData['cid'] = isset($filterData['cid']) ? $filterData['cid'] : '';
        $filterData['userIp'] = isset($filterData['userIp']) ? $filterData['userIp'] : '';
        $filterData['feedbackType'] = isset($filterData['feedbackType']) ? $filterData['feedbackType'] : '';

        if ($filterData['newsId']) {
            $query->matching($query->logicalAnd(
                $query->equals('record_id', $filterData['newsId'])
            ));
        }


        $query->matching($query->logicalAnd(
            $query->equals('feedbacks.pid', $filterData['pId'])
        ));
        if ($filterData['cid']) {
            $query->matching($query->logicalAnd(
                $query->equals('feedbacks.cid', (int)$filterData['cid'])
            ));
        }
        if ($filterData['userIp']) {
            $query->matching($query->logicalAnd(
                $query->equals('feedbacks.user_ip', (string)$filterData['userIp'])
            ));
        }
        $query->matching($query->logicalAnd(
            $query->equals('feedbacks.feedback_type', (int)$filterData['feedbackType'])
        ));
        $query->setOrderings(
            [
                'feedbacks.uid' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
            ]
        );
        return $query->execute();
    }
}
