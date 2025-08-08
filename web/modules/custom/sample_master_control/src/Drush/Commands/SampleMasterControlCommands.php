<?php

namespace Drupal\sample_master_control\Drush\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Utility\Token;
use Drush\Attributes as CLI;
use Drush\Commands\AutowireTrait;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 */
final class SampleMasterControlCommands extends DrushCommands {

  use AutowireTrait;

  /**
   * Constructs a SampleMasterControlCommands object.
   */
  public function __construct(
    private readonly Token $token,
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly EmailValidatorInterface $emailValidator,
    private readonly MailManagerInterface $mailManager,
  ) {
    parent::__construct();
  }

  /**
   * Command description here.
   */
  #[CLI\Command(name: 'sample_master_control:send-email', aliases: ['se'])]
  #[CLI\Argument(name: 'email', description: 'Email address of the user.')]
  #[CLI\Argument(name: 'subject', description: 'The email subject.')]
  #[CLI\Usage(name: 'sample_master_control:send-email admin@example.com', description: 'Send email to admin@example.co')]
  public function sendEmail($email, $subject, $options = ['option-name' => 'default']): void {
    if (!$this->emailValidator->isValid($email)) {
      $this->logger()->error('Invalid email address: @email', ['@email' => $email]);
      return;
    }

    $users = $this->entityTypeManager->getStorage('user')->loadByProperties(['mail' => $email]);

    if (empty($users)) {
      $this->logger()->error('No user found with email address: @email', ['@email' => $email]);
      return;
    }

    $user = reset($users);

    $params = [
      'subject' => $subject,
      'body' => 'Hello ' . $user->getDisplayName() . ', this is a test email from the Sample Master Control module.',
    ];

    $result = $this->mailManager->mail('sample_master_control', 'sample_email', $email, 'en', $params);

    if ($result['result']) {
      $this->logger()->success('Email sent successfully to @email', ['@email' => $email]);
    } else {
      $this->logger()->error('Failed to send email to @email', ['@email' => $email]);
    }
  }

}
