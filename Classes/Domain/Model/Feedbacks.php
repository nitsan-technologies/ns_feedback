<?php

namespace NITSAN\NsFeedback\Domain\Model;

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
 * Feedbacks
 */
class Feedbacks extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
     * comment
     *
     * @var string
     */
    protected $comment = '';

    /**
     * userIp
     *
     * @var string
     */
    protected $userIp = '';

    /**
     * feedbackType
     *
     * @var string
     */
    protected $feedbackType = '';

    /**
     * quickfeedbacktype
     *
     * @var string
     */
    protected $quickfeedbacktype = '';

    /**
     * cid
     *
     * @var string
     */
    protected $cid = '';

    /**
     * Returns the comment
     *
     * @return string $comment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the comment
     *
     * @param string $comment
     * @return void
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Returns the userIp
     *
     * @return string $userIp
     */
    public function getUserIp()
    {
        return $this->userIp;
    }

    /**
     * Sets the userIp
     *
     * @param string $userIp
     * @return void
     */
    public function setUserIp($userIp)
    {
        $this->userIp = $userIp;
    }

    /**
     * Returns the feedbackType
     *
     * @return string $feedbackType
     */
    public function getFeedbackType()
    {
        return $this->feedbackType;
    }

    /**
     * Sets the feedbackType
     *
     * @param string $feedbackType
     * @return void
     */
    public function setFeedbackType($feedbackType)
    {
        $this->feedbackType = $feedbackType;
    }

    /**
     * Returns the quickfeedbacktype
     *
     * @return string $quickfeedbacktype
     */
    public function getQuickfeedbacktype()
    {
        return $this->quickfeedbacktype;
    }

    /**
     * Sets the quickfeedbacktype
     *
     * @param string $quickfeedbacktype
     * @return void
     */
    public function setQuickfeedbacktype($quickfeedbacktype)
    {
        $this->quickfeedbacktype = $quickfeedbacktype;
    }

    /**
     * Returns the cid
     *
     * @return string $cid
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Sets the cid
     *
     * @param string $cid
     * @return void
     */
    public function setCid($cid)
    {
        $this->cid = $cid;
    }
}
