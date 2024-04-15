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
    public function getCrdate(): ?\DateTime
    {
        return $this->crdate;
    }

    /**
     * comment
     *
     * @var string
     */
    protected string $comment = '';

    /**
     * userIp
     *
     * @var string
     */
    protected string $userIp = '';


    /**
     * quickfeedbacktype
     *
     * @var string
     */
    protected string $quickfeedbacktype = '';

    /**
     * cid
     *
     * @var string
     */
    protected string $cid = '';

    /**
     * Returns the comment
     *
     * @return string $comment
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * Sets the comment
     *
     * @param string $comment
     * @return void
     */
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Returns the userIp
     *
     * @return string $userIp
     */
    public function getUserIp(): string
    {
        return $this->userIp;
    }

    /**
     * Sets the userIp
     *
     * @param string $userIp
     * @return void
     */
    public function setUserIp(string $userIp)
    {
        $this->userIp = $userIp;
    }

    /**
     * Returns the quickfeedbacktype
     *
     * @return string $quickfeedbacktype
     */
    public function getQuickfeedbacktype(): string
    {
        return $this->quickfeedbacktype;
    }

    /**
     * Sets the quickfeedbacktype
     *
     * @param string $quickfeedbacktype
     * @return void
     */
    public function setQuickfeedbacktype(string $quickfeedbacktype)
    {
        $this->quickfeedbacktype = $quickfeedbacktype;
    }

    /**
     * Returns the cid
     *
     * @return string $cid
     */
    public function getCid(): string
    {
        return $this->cid;
    }

    /**
     * Sets the cid
     *
     * @param string $cid
     * @return void
     */
    public function setCid(string $cid)
    {
        $this->cid = $cid;
    }
    /**
     * sysLangId
     *
     * @var int
     */
    protected int $sysLangId = 0;

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
    public function setSysLangId(int $sysLangId)
    {
        $this->_languageUid = $sysLangId;
    }

}
