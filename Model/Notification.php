<?php

namespace DCS\NotificationBundle\Model;

abstract class Notification implements NotificationInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ComponentInterface
     */
    protected $subject;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $sentAt;

    function __construct()
    {
        $this->status = self::STATUS_TO_SEND;
        $this->createdAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * {@inheritdoc}
     */
    public function setAction($action)
    {
        $this->action = $action;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus($status)
    {
        if (!in_array($status, self::getStatusValues())) {
            throw new \Exception(sprintf('Status %s is not valid', $status));
        }

        $this->status = $status;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusValues()
    {
        return array(
            self::STATUS_SENT,
            self::STATUS_TO_SEND,
            self::STATUS_TO_RESEND,
        );
    }
}