<?php

namespace NITSAN\NsFeedback\Domain\Model;

use DateTime;
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
 * Feedbacks
 */
class Feedbacks extends AbstractEntity
{
    /**
     * @var DateTime
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
     * sysLangId
     *
     * @var int
     */
    protected int $sysLangId = 0;

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
    public function setComment(string $comment): void
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
    public function setUserIp(string $userIp): void
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
    public function setQuickfeedbacktype(string $quickfeedbacktype): void
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
    public function setCid(string $cid): void
    {
        $this->cid = $cid;
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
    public function setSysLangId(int $sysLangId): void
    {
        $this->_languageUid = $sysLangId;
    }
}
