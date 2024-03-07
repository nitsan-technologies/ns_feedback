<?php

namespace NITSAN\NsFeedback\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

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
class ReportRepository extends Repository
{
    /**
     * @var array
     */
    protected $defaultOrderings = [
        'uid' => QueryInterface::ORDER_DESCENDING
    ];

    public function getFromAll()
    {
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function checkExistRecord($filterData = null)
    {
        $query = $this->createQuery();
        $main = [];

        $filterData['newsId'] = isset($filterData['newsId']) ? $filterData['newsId'] : '';
        $filterData['pId'] = isset($filterData['pId']) ? $filterData['pId'] : '';
        $filterData['cid'] = isset($filterData['cid']) ? $filterData['cid'] : '';
        $filterData['userIp'] = isset($filterData['userIp']) ? $filterData['userIp'] : '';
        $filterData['feedbackType'] = isset($filterData['feedbackType']) ? $filterData['feedbackType'] : '';

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
            $query->logicalAnd($main)
        );
        $query->setOrderings(
            [
                'feedbacks.uid' => QueryInterface::ORDER_DESCENDING
            ]
        );
        return $query->execute();

    }
    public function findAllByLanguage()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        return $query->execute();
    }

}
