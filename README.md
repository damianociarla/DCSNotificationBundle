DCSNotificationBundle
=====================

DCSNotificationBundle is a Symfony2 bundle that allows you to send notifications.

## Usage example

To illustrate how the bundle works, we will go through an example. We have a Blog application which permits logged users to add comments to published posts.

When a reader leaves a message under one of the posts, the author of the post and all the previous commenters receive an email about the new comment. Since we are responsible developers, we give to all commenters the option to disable the notification.

## The flow

The DCSNotificationBundle exposes tree important services:

- `dcs_notification.service.notification` which, through the `notify()` method, takes care of handling the notification
- `dcs_notification.manager.component` responsible of creating the `subject` of the notification 
- `dcs_notification.service.notifier` which, through the `process()` method processes the notification

For each type of notification we are responsible for creating a __notifier__ and a __transporter__ that know how to manage the notification.

### Configuration

To use the DSCNotificationBunde we need to configure the bundle and our notifiers.

An example configuration of the bundle could be the following:

```
dcs_notification:
    db_driver: orm
    class:
        model:
            component: Blog\NotificationBundle\Entity\Component
            component_setting: Blog\NotificationBundle\Entity\ComponentSetting
            notification: Blog\NotificationBundle\Entity\Notification
            notification_component: Blog\NotificationBundle\Entity\NotificationComponent
            recipient: Blog\NotificationBundle\Entity\Recipient
    transporters:
        mail:
            id: blog_notification.transporter.mail
    actions:
        new_comment:
            transporters:
                mail:
                    id: mail
                    config:
                        template: BlogNotificationBundle:Email:new_comment.html.twig
```

`dcs_notification` is the key for the DCSNotificationBundle configuration. To use the bundle  John first configured the mandatory keys:

- `db_driver` set to `orm` will tell Symfony 2 to use Doctrine ORM
- `class` contains the list of model classes. If we don't need nothing fancy, we can just extend the base classes provided by the bundle:
  - `Blog\NotificationBundle\Entity\Component extends DCS\NotificationBundle\Entity\Component`
  - `Blog\NotificationBundle\Entity\ComponentSetting extends DCS\NotificationBundle\Entity\ComponentSetting`
  - `Blog\NotificationBundle\Entity\Notification extends DCS\NotificationBundle\Entity\Notification `
  - `Blog\NotificationBundle\Entity\NotificationComponent extends DCS\NotificationBundle\Entity\NotificationComponent `
  - `Blog\NotificationBundle\Entity\Recipient extends DCS\NotificationBundle\Entity\Recipient`
- `transporters` defines a list of transporters associated with valid services
- `actions` are the list of accessible actions, configured with the transporters associated

To be able to use the bundle we need to configure the notifiers. This is an example configuration:

```
services:
    blog.notification_bundle.notifier.new_comment:
        class: Blog\NotificationBundle\Notifier\NewCommentNotifier
        tags:
            - { name: dcs_notification.notifier }
```

In this way we added the `blog.notification_bundle.notifier.new_comment` notifier.

### Notifying the notification

The `dcs_notification.service.notification` service represents the entry point of the notification flow. The service is used through the `notify()` method as follows: 

```php
$commentComponent = $this->componentManager->findOrCreateComponent($comment, $comment->getId());
$this->notificationService->notify($commentComponent, 'new_comment', array('url' => $currentUrl));
```

The first argument of the `notify()` method is called `subject`. This could be anything, as long as it's wrapped in a `DCS\NotificationBundle\Model\ComponentManagerInterface` type object. In the example we decided to pass the comment to the `notify()` method because the comment has a lot of information on what's happening: the comment itself, the author of the comment, the post to which the comment was made to, and, from the post, the author of the post, the other comments left on the posts and, finally, all the authors of the other comments. The second argument is `new_comment` and is called the `action` of the notice. We will get back on the `action` later. The third argument is not mandatory and contains additional data to which we want to be able to access later.

The `notify()` method doesn't actually process the notification but saves it as "pending".

#### A different way to pass data the `notify()` method:

We could have decided to implement the notification in a different way, passing the comment in the third argument and being more generic with the subject:

```php
$commentComponent = $this->componentManager->findOrCreateComponent('the_comment_subject', $comment->getId());
$this->notificationService->notify(
    $commentComponent,
    'new_comment',
    array(
        'url' => $currentUrl,
        'id' => $comment->getId()
    )
);
```

This change would affect how we process the notification.

### Processing pending notifications

The `dcs_notification.manager.notification` service exposes the `findAllNotificationToSend()` method, which returns al the pending notifications.

Having the list of pending notification, these can be processed using the `dcs_notification.service.notifier` service as follows:

```php
$notificationsToSend = $this->getContainer()->get('dcs_notification.manager.notification')->findAllNotificationToSend($limit);
$notifierService = $this->getContainer()->get('dcs_notification.service.notifier');
foreach ($notificationsToSend as $notification) {
    $notifierService->process($notification);
}
```

The code above could be used in a command-line command set to be run periodically using using cron.

### Notifiers

At this point we have a list of pending notifications being processed one at a time. When the `process()` method is called, two important things happen:

- each notifier is being asked if knows how to manage the current notification and, if the result is positive, the the notification is passed to it
- the transporter is called

A notifier is a class implementing the `DCS\NotificationBundle\Notifier\NotifierInterface` interface.

In our example, we have a new notification with the `new_comment` action and a valid notifier could be used to manage the recipients of the notification:

```php
<?php
namespace Blog\NotificationBundle\Notifier;

use DCS\NotificationBundle\Model\NotificationComponentInterface;
use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Model\NotificationManagerInterface;
use DCS\NotificationBundle\Notifier\Collection\RecipientCollectionInterface;
use DCS\NotificationBundle\Notifier\NotifierInterface;

use Blog\PostBundle\Entity\Post;
use Blog\PostBundle\Entity\Comment;
use Blog\PostBundle\Repository\CommentRepository;
use Blog\UserBundle\Entity\User;

class NewCommentNotifier implements NotifierInterface
{
    protected $notificationManager;
    protected $projectRepository;
    
    function __construct(NotificationManagerInterface $notificationManager, CommentRepository $commentRepository)
    {
        $this->notificationManager = $notificationManager;
        $this->commentRepository = $commentRepository;
    }
    
    public function supports(NotificationInterface $notification)
    {
        return $notification->getAction() == 'new_comment';
    }
    
    public function notify(NotificationInterface $notification, RecipientCollectionInterface $recipients)
    {
        $notificationComponents = $this->notificationManager->findAllNotificationComponent($notification);
        
        /** @var NotificationComponentInterface $notificationComponent */
        foreach ($notificationComponents as $notificationComponent) {
            if ($notificationComponent->getType() == 'comment') {
                /** @var Comment $comment */
                $comment = $notificationComponent->getComponent()->getData();
                if ($comment instanceof Comment) {
                    $commentAuthor = $comment->getAuthor();
                    $post = $comment->getPost();
                    $postAuthor = $post->getAuthor();
                    
                    if ($comment->getAuthor()->getId() != $postAuthor->getId()) {
                        $recipients->add('Blog\UserBundle\Entity\User', $postAuthor->getId());
                    }
                    
                    $commenters = $this->commentRepository->findAllCommentersToPost($post);
                    /** @var User $member */
                    foreach ($commenters as $commenter) {
                        if ($commenter->getId() != $postAuthor->getId() && $commenter->getId() != $postAuthor->getId()) {
                            $recipients->add('Blog\UserBundle\Entity\User', $commenter->getId());
                        }
                    }
                }
            }
        }
    }
} 
```

Whit the `Blog\NotificationBundle\Notifier\CommentNotifier` class we are now able to add the correct recipients to the notification: the author of the post and all the commenters. The notification is ready to be sent to the transporter.
 
### Transporters

A transporter is a class implementing the `DCS\NotificationBundle\Transporter\TransporterInterface` interface. The interface asks the developer to implement two methods: `setConfiguration()` and `send()`:

```php
<?php
namespace DCS\NotificationBundle\Transporter;

use DCS\NotificationBundle\Model\NotificationInterface;
use DCS\NotificationBundle\Model\RecipientInterface;

interface TransporterInterface
{
    public function setConfiguration($config);
    public function send(NotificationInterface $notification, array $additionalData, RecipientInterface $recipient);
} 
```

If we look back at the `actions.new_comment.transporters.mail.config` configuration, we have defined an option which specifies the template associated with the transporter. The options are injected in the `blog_notification.transporter.mail` service through the `setConfiguration()` method.

The `send()` method is responsible of doing the actual work of sending the notification. Will accept a `$notification` object, a `$components` array and a `$recipient` object.

- `$notification` object contains the notification subject that needs to be sent
- `$additionalData` contains the data passed to the third parameter of the `$notificationService->notify()` method
- `$recipient` is the recipient

An example implementation of the `send()` method could be the following:

```php
public function send(NotificationInterface $notification, array $additionalData, RecipientInterface $recipient)
{
   if (!isset($this->config['template'])) {
       throw new \Exception('Template must be set in this transporter');
   }
   
   $recipient = $recipient->getComponent()->getData();
   $templateName = $this->config['template'];

   $attach = array();
   $context = array(
       'siteUrlHttp' => $this->siteUrlHttp,
       'subject' => $notification->getSubject()->getData(),
       'notification' => $notification,
       'recipient' => $recipient,
   );

   $email = null;
   if ($recipient instanceof User) {
       $email = $recipient->getEmailCanonical();
   } elseif (is_array($recipient) && isset($recipient['email']) && is_scalar($recipient['email'])) {
       $email = $recipient['email'];
   }

   if (!empty($email)) {
       $this->sendMessage($templateName, $context, $email, $attach);
   }
}

private function sendMessage($templateName, $context, $toEmail, array $attach = null)
{
    // send the email
}
```
