<?php

namespace NITSAN\NsFeedback\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
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
class FeedbacksRepository extends Repository
{
    public function getFromAll()
    {
        $querySettings = GeneralUtility::makeInstance(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    public function countAllByLanguage()
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectSysLanguage(false);
        return $query->execute()->count();
    }
}
