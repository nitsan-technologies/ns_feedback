<?php

namespace NITSAN\NsFeedback\Domain\Model;

use NITSAN\NsFeedback\Domain\Model\Feedbacks;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
 * Report
 */
class Report extends AbstractEntity
{
    /**
     * @var \DateTime
     */
    protected $crdate = null;

    /**
     * Returns the creation date
     *
     * @return \DateTime $crdate
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * pageId
     *
     * @var int
     */
    protected $pageId = 0;

    /**
     * recordId
     *
     * @var int
     */
    protected $recordId = 0;

    /**
     * pageType
     *
     * @var int
     */
    protected $pageType = 0;

    /**
     * pageTitle
     *
     * @var string
     */
    protected $pageTitle = '';

    /**
     * feedbackYesCount
     *
     * @var int
     */
    protected $feedbackYesCount = 0;

    /**
     * feedbackNoCount
     *
     * @var int
     */
    protected $feedbackNoCount = 0;

    /**
     * feedbackYesButCount
     *
     * @var int
     */
    protected $feedbackYesButCount = 0;

    /**
     * feedbackNoButCount
     *
     * @var int
     */
    protected $feedbackNoButCount = 0;

    /**
     * sysLangId
     *
     * @var int
     */
    protected $sysLangId = 0;

    /**
     * feedbacks
     *
     * @var ObjectStorage<Feedbacks>
     * @Cascade("remove")
     */
    protected $feedbacks = null;

    /**
     * __construct
     */
    public function __construct()
    {

        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->feedbacks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the pageId
     *
     * @return int $pageId
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Sets the pageId
     *
     * @param int $pageId
     * @return void
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * Returns the recordId
     *
     * @return int $recordId
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * Sets the recordId
     *
     * @param int $recordId
     * @return void
     */
    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * Returns the pageType
     *
     * @return int $pageType
     */
    public function getPageType()
    {
        return $this->pageType;
    }

    /**
     * Sets the pageType
     *
     * @param int $pageType
     * @return void
     */
    public function setPageType($pageType)
    {
        $this->pageType = $pageType;
    }

    /**
     * Returns the pageTitle
     *
     * @return string $pageTitle
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    /**
     * Sets the pageTitle
     *
     * @param string $pageTitle
     * @return void
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * Returns the feedbackYesCount
     *
     * @return int $feedbackYesCount
     */
    public function getFeedbackYesCount()
    {
        return $this->feedbackYesCount;
    }

    /**
     * Sets the feedbackYesCount
     *
     * @param int $feedbackYesCount
     * @return void
     */
    public function setFeedbackYesCount($feedbackYesCount)
    {
        $this->feedbackYesCount = $feedbackYesCount;
    }

    /**
     * Returns the feedbackNoCount
     *
     * @return int $feedbackNoCount
     */
    public function getFeedbackNoCount()
    {
        return $this->feedbackNoCount;
    }

    /**
     * Sets the feedbackNoCount
     *
     * @param int $feedbackNoCount
     * @return void
     */
    public function setFeedbackNoCount($feedbackNoCount)
    {
        $this->feedbackNoCount = $feedbackNoCount;
    }

    /**
     * Returns the feedbackYesButCount
     *
     * @return int $feedbackYesButCount
     */
    public function getFeedbackYesButCount()
    {
        return $this->feedbackYesButCount;
    }

    /**
     * Sets the feedbackYesButCount
     *
     * @param int $feedbackYesButCount
     * @return void
     */
    public function setFeedbackYesButCount($feedbackYesButCount)
    {
        $this->feedbackYesButCount = $feedbackYesButCount;
    }

    /**
     * Returns the feedbackNoButCount
     *
     * @return int $feedbackNoButCount
     */
    public function getFeedbackNoButCount()
    {
        return $this->feedbackNoButCount;
    }

    /**
     * Sets the feedbackNoButCount
     *
     * @param int $feedbackNoButCount
     * @return void
     */
    public function setFeedbackNoButCount($feedbackNoButCount)
    {
        $this->feedbackNoButCount = $feedbackNoButCount;
    }

    /**
     * Returns the sysLangId
     *
     * @return int $sysLangId
     */
    public function getSysLangId()
    {
        return $this->_languageUid;
    }

    /**
     * Sets the sysLangId
     *
     * @param int $sysLangId
     * @return void
     */
    public function setSysLangId($sysLangId)
    {
        $this->_languageUid = $sysLangId;
    }

    /**
     * Adds a Feedbacks
     *
     * @param \NITSAN\NsFeedback\Domain\Model\Feedbacks $feedback
     * @return void
     */
    public function addFeedback(\NITSAN\NsFeedback\Domain\Model\Feedbacks $feedback)
    {
        $this->feedbacks->attach($feedback);
    }

    /**
     * Removes a Feedbacks
     *
     * @param \NITSAN\NsFeedback\Domain\Model\Feedbacks $feedbackToRemove The Feedbacks to be removed
     * @return void
     */
    public function removeFeedback(\NITSAN\NsFeedback\Domain\Model\Feedbacks $feedbackToRemove)
    {
        $this->feedbacks->detach($feedbackToRemove);
    }

    /**
     * Returns the feedbacks
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NITSAN\NsFeedback\Domain\Model\Feedbacks> $feedbacks
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * Sets the feedbacks
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\NITSAN\NsFeedback\Domain\Model\Feedbacks> $feedbacks
     * @return void
     */
    public function setFeedbacks(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $feedbacks)
    {
        $this->feedbacks = $feedbacks;
    }
}
