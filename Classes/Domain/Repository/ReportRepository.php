<?php

namespace NITSAN\NsFeedback\Domain\Repository;

use Doctrine\DBAL\Exception;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
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

        $filterData['pId'] = $filterData['pId'] ?? '';
        $filterData['cid'] = $filterData['cid'] ?? '';
        $filterData['userIp'] = $filterData['userIp'] ?? '';

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
        $query->setOrderings(
            [
                'feedbacks.uid' => QueryInterface::ORDER_DESCENDING
            ]
        );
        return $query->execute();
    }

    /**
     * @return array|QueryResultInterface
     */
    public function findAllByLanguage(): array|QueryResultInterface
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        return $query->execute();
    }

    /**
     * @throws Exception
     */
    public function getFeedbacksReport() : mixed
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getConnectionForTable('tx_nsfeedback_domain_model_report');
        $queryBuilder = $connection->createQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from('tx_nsfeedback_domain_model_feedbacks')
            ->where($queryBuilder->expr()->eq('pid', $GLOBALS['TSFE']->page['uid']))
            ->andWhere(
                $queryBuilder->expr()->eq('user_ip', "'" . $_SERVER['REMOTE_ADDR'] . "'")
            )
            ->executeQuery()
            ->fetchAllAssociative();
    }
}
