<?php

namespace NITSAN\NsFeedback\Domain\Model;

use DateTime;
use NITSAN\NsFeedback\Domain\Model\Feedbacks;
use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

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
 * Report
 */
class Report extends AbstractEntity
{
    /**
     * @var DateTime|null
     */
    protected ?DateTime $crdate = null;

    /**
     * Returns the creation date
     *
     * @return DateTime|null $crdate
     */
    public function getCrdate(): ?DateTime
    {
        return $this->crdate;
    }

    /**
     * pageId
     *
     * @var int
     */
    protected int $pageId = 0;

    /**
     * recordId
     *
     * @var int
     */
    protected int $recordId = 0;

    /**
     * pageType
     *
     * @var int
     */
    protected int $pageType = 0;

    /**
     * pageTitle
     *
     * @var string
     */
    protected string $pageTitle = '';

    /**
     * feedbackYesCount
     *
     * @var int
     */
    protected int $feedbackYesCount = 0;

    /**
     * feedbackNoCount
     *
     * @var int
     */
    protected int $feedbackNoCount = 0;

    /**
     * feedbackYesButCount
     *
     * @var int
     */
    protected int $feedbackYesButCount = 0;

    /**
     * feedbackNoButCount
     *
     * @var int
     */
    protected int $feedbackNoButCount = 0;

    /**
     * sysLangId
     *
     * @var int
     */
    protected int $sysLangId = 0;

    /**
     * feedbacks
     *
     * @var ObjectStorage<Feedbacks>|null
     * @Cascade("remove")
     */
    protected ?ObjectStorage $feedbacks = null;

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
    protected function initStorageObjects(): void
    {
        $this->feedbacks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the pageId
     *
     * @return int $pageId
     */
    public function getPageId(): int
    {
        return $this->pageId;
    }

    /**
     * Sets the pageId
     *
     * @param int $pageId
     * @return void
     */
    public function setPageId(int $pageId): void
    {
        $this->pageId = $pageId;
    }

    /**
     * Returns the recordId
     *
     * @return int $recordId
     */
    public function getRecordId(): int
    {
        return $this->recordId;
    }

    /**
     * Sets the recordId
     *
     * @param int $recordId
     * @return void
     */
    public function setRecordId(int $recordId): void
    {
        $this->recordId = $recordId;
    }

    /**
     * Returns the pageType
     *
     * @return int $pageType
     */
    public function getPageType(): int
    {
        return $this->pageType;
    }

    /**
     * Sets the pageType
     *
     * @param int $pageType
     * @return void
     */
    public function setPageType(int $pageType): void
    {
        $this->pageType = $pageType;
    }

    /**
     * Returns the pageTitle
     *
     * @return string $pageTitle
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    /**
     * Sets the pageTitle
     *
     * @param string $pageTitle
     * @return void
     */
    public function setPageTitle(string $pageTitle): void
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * Returns the feedbackYesCount
     *
     * @return int $feedbackYesCount
     */
    public function getFeedbackYesCount(): int
    {
        return $this->feedbackYesCount;
    }

    /**
     * Sets the feedbackYesCount
     *
     * @param int $feedbackYesCount
     * @return void
     */
    public function setFeedbackYesCount(int $feedbackYesCount): void
    {
        $this->feedbackYesCount = $feedbackYesCount;
    }

    /**
     * Returns the feedbackNoCount
     *
     * @return int $feedbackNoCount
     */
    public function getFeedbackNoCount(): int
    {
        return $this->feedbackNoCount;
    }

    /**
     * Sets the feedbackNoCount
     *
     * @param int $feedbackNoCount
     * @return void
     */
    public function setFeedbackNoCount(int $feedbackNoCount): void
    {
        $this->feedbackNoCount = $feedbackNoCount;
    }

    /**
     * Returns the feedbackYesButCount
     *
     * @return int $feedbackYesButCount
     */
    public function getFeedbackYesButCount(): int
    {
        return $this->feedbackYesButCount;
    }

    /**
     * Sets the feedbackYesButCount
     *
     * @param int $feedbackYesButCount
     * @return void
     */
    public function setFeedbackYesButCount(int $feedbackYesButCount): void
    {
        $this->feedbackYesButCount = $feedbackYesButCount;
    }

    /**
     * Returns the feedbackNoButCount
     *
     * @return int $feedbackNoButCount
     */
    public function getFeedbackNoButCount(): int
    {
        return $this->feedbackNoButCount;
    }

    /**
     * Sets the feedbackNoButCount
     *
     * @param int $feedbackNoButCount
     * @return void
     */
    public function setFeedbackNoButCount(int $feedbackNoButCount): void
    {
        $this->feedbackNoButCount = $feedbackNoButCount;
    }

    /**
     * Returns the sysLangId
     *
     * @return int $sysLangId
     */
    public function getSysLangId(): int
    {
        return $this->_languageUid;
    }

    /**
     * Sets the sysLangId
     *
     * @param int $sysLangId
     * @return void
     */
    public function setSysLangId($sysLangId): void
    {
        $this->_languageUid = $sysLangId;
    }

    /**
     * Adds a Feedbacks
     *
     * @param Feedbacks $feedback
     * @return void
     */
    public function addFeedback(Feedbacks $feedback): void
    {
        $this->feedbacks->attach($feedback);
    }

    /**
     * Removes a Feedbacks
     *
     * @param Feedbacks $feedbackToRemove The Feedbacks to be removed
     * @return void
     */
    public function removeFeedback(Feedbacks $feedbackToRemove): void
    {
        $this->feedbacks->detach($feedbackToRemove);
    }

    /**
     * Returns the feedbacks
     *
     * @return ObjectStorage<Feedbacks> $feedbacks
     */
    public function getFeedbacks(): ObjectStorage
    {
        return $this->feedbacks;
    }

    /**
     * Sets the feedbacks
     *
     * @param ObjectStorage<Feedbacks> $feedbacks
     * @return void
     */
    public function setFeedbacks(ObjectStorage $feedbacks): void
    {
        $this->feedbacks = $feedbacks;
    }
}
