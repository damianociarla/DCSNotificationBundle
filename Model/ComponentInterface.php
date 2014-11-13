<?php

namespace DCS\NotificationBundle\Model;

interface ComponentInterface
{
    /**
     * Get id
     *
     * @return int
     */
    public function getId();

    /**
     * Get model
     *
     * @return string
     */
    public function getModel();

    /**
     * Set model
     *
     * @param string $model
     * @return ComponentInterface
     */
    public function setModel($model);

    /**
     * Get identifier
     *
     * @return array
     */
    public function getIdentifier();

    /**
     * Set identifier
     *
     * @param array $identifier
     * @return ComponentInterface
     */
    public function setIdentifier($identifier);

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash();

    /**
     * Set hash
     *
     * @param string $hash
     * @return ComponentInterface
     */
    public function setHash($hash);

    /**
     * Get data
     *
     * @return mixed
     */
    public function getData();

    /**
     * Set data
     *
     * @param mixed $data
     * @return ComponentInterface
     */
    public function setData($data);
}