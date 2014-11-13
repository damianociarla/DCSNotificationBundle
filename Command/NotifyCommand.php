<?php

namespace DCS\NotificationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('dcs:notification:notify')
            ->setDescription('Send notifications which have the status of "to_send"')
            ->addOption('limit', 20, InputOption::VALUE_OPTIONAL, 'Maximum limit of notifications for each call')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getOption('limit');

        $notificationsToSend = $this->getContainer()->get('dcs_notification.manager.notification')->findAllNotificationToSend($limit);
        $notifierService = $this->getContainer()->get('dcs_notification.service.notifier');

        foreach ($notificationsToSend as $notification) {
            $notifierService->process($notification);
            $output->writeln('Process notification: #'.$notification->getId());
        }

        $output->writeln(sprintf('Sent %d notifications', count($notificationsToSend)));
    }
} 