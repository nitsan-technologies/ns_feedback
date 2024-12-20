<?php

namespace NITSAN\NsFeedback\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
class FeedbacksRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function getFromAll()
    {
        $querySettings = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function insertNewData($data)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nsfeedback_domain_model_apidata');
        $row = $queryBuilder
            ->insert('tx_nsfeedback_domain_model_apidata')
            ->values($data);

        $query = $queryBuilder->executeQuery();
        return $query;
    }
    public function curlInitCall($url)
    {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curlSession);
        curl_close($curlSession);

        return $data;
    }
    public function deleteOldApiData()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nsfeedback_domain_model_apidata');
        $queryBuilder
            ->delete('tx_nsfeedback_domain_model_apidata')
            ->where(
                $queryBuilder->expr()->comparison('last_update', '<', 'DATE_SUB(NOW() , INTERVAL 1 DAY)')
            )
            ->executeQuery();
    }

    public function countAllByLanguage()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        return $query->execute()->count();
    }

}
