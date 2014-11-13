<?php

namespace DCS\NotificationBundle\Transporter;

class TransporterChain
{
    private $transporters;

    public function __construct()
    {
        $this->transporters = array();
    }

    public function addTransporter(TransporterInterface $transporter, $name)
    {
        $this->transporters[$name] = $transporter;
    }

    /**
     * Get transporters
     *
     * @param string $name
     * @return TransporterInterface|null
     */
    public function getTransporter($name)
    {
        if (isset($this->transporters[$name])) {
            return $this->transporters[$name];
        }

        return null;
    }

    /**
     * Get transporters
     *
     * @return array
     */
    public function getTransporters()
    {
        return $this->transporters;
    }
} 