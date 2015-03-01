DCSNotificationBundle
=====================

DCSNotificationBundle is a Symfony2 bundle that allows you to send notifications.

## Example and terms

This example will describe how the bundle works introducing the terms used in the code.

### Background

John has written a blog application using the Symfony 2 framework allowing registered readers to leave comments. When a reader leaves a message under one of his posts, John and all the previous commenters receives an email informing them of a new comment. Since John is a responsible developer, he has given to all registered readers the option to disable the notification.

John is using DCSNotificationBundle.

### The flow

Now lets try to understand what happens when a registered reader leaves a comment.

In the code we have the following entities:

- `PostEntity` is the post, with title, content and author user
- `CommentEntity` is the comment, with content and author user
- `UserEntity` is a user

When a registered user leaves a comment a `new_comment` action happens. The action is responsible of two things:

- saving the new comment to the database
- sending notifications about the new comment

We will focus on the latter part.

The DCSNotificationBundle exposes tree important services:

- `dcs_notification.service.notification` which, through the `notify()` method, takes care of handling the notification
- `dcs_notification.manager.component` responsible of creating the `subject` of the notification 
- `dcs_notification.service.notifier` which, through the `process()` processes the notification

In our example, John could create a new notification in this way:

```php
$commentComponent = $this->componentManager->findOrCreateComponent($comment, $comment->getId());
$this->notificationService->notify($commentComponent, 'new_comment');
```

The first argument of the `notify()` method is called `subject`. This could be anything, as long as it's wrapped in a `DCS\NotificationBundle\Model\ComponentManagerInterface` type object. John decided to pass the comment to the `notify()` method because the comment has a lot of information on what's happening: the comment itself, the author of the comment, the post to which the comment was made to, and, from the post, the author of the post, the other comments left on the posts and, finally, all the authors of the other comments. The second argument is `new_comment` and is called the `action` of the notice. We will get back on the `action` later.

After the execution of the two lines above, the notification has been saved on the database but no email has been actually sent.

To send the notifications John has created a command-line command which he runs periodically using cron. The command asks the `dcs_notification.manager.notification` service for all the pending notifications and, one at a time, uses the `dcs_notification.service.notifier ` service to process it:

```php
$notificationsToSend = $this->getContainer()->get('dcs_notification.manager.notification')->findAllNotificationToSend($limit);
$notifierService = $this->getContainer()->get('dcs_notification.service.notifier');
foreach ($notificationsToSend as $notification) {
    $notifierService->process($notification);
}
```

### Configuration

Let's take a look at the configuration to see what's happening when calling the `notify()` and `process()` methods:

```
dcs_notification:
    db_driver: orm
    class:
        model:
            component: BlogBundle\NotificationBundle\Entity\Component
            component_setting: BlogBundle\NotificationBundle\Entity\ComponentSetting
            notification: BlogBundle\NotificationBundle\Entity\Notification
            notification_component: BlogBundle\NotificationBundle\Entity\NotificationComponent
            recipient: BlogBundle\NotificationBundle\Entity\Recipient
    transporters:
        mail:
            id: blog_notification.transporter.mail
    actions:
        new_comment:
            transporters:
                mail:
                    id: mail
                    config:
                        template: BlogBundleNotificationBundle:Email:new_comment.html.twig
```

`dcs_notification` is the key for the DCSNotificationBundle configuration. To use the bundle  John first configured the mandatory keys:

- `db_driver` set to `orm` will tell Symfony 2 to use Doctrine ORM
- `class` contains the list of model classes
- `transporters` defines a list of transporters associated with a valid service id
- `actions` are the list of accessible actions, configured with the transporters associated

From the configuration we see that the bundle actually uses plain simple services to do the actual work of sending the email. In this case John is using the `blogbundle_notification.transporter.mail` service.

### Transporters

But how what does the transporter actually do? The transporter is a class implementing the `DCS\NotificationBundle\Transporter\TransporterInterface` interface. The interface asks the developer to implement two methods: `setConfiguration()` and `send()`:

```php
<?php
namespace DCS\NotificationBundle\Transporter;

use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Model\RecipientInterface;

interface TransporterInterface
{
    public function setConfiguration($config);
    public function send(NotificationInterface $notification, array $components, RecipientInterface $recipient);
} 
```

John, in the `actions.new_comment.transporters.mail.config` configuration, has defined a template option which is injected in the `blog_notification.transporter.mail` service through the `setConfiguration()` method.

The `send()` method is responsible of doing the actual work of sending the notification. Will accept a `$notification` object, a `$components` array and a `$recipient` object.

The `$notification` object contains the notification that needs to be sent. 
