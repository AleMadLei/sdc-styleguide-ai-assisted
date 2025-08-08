<?php

declare(strict_types=1);

namespace Drupal\sample_master_control\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a Sample Master Control form.
 */
final class ControlForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'sample_master_control_control';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => t('admin@example.com')
    ];

    $form['subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => t('This is the subject')
    ];

    $form['dominio'] = [
      '#type' => 'select',
      '#title' => $this->t('Dominio'),
      '#options' => [
        'sitio1.com' => t('Sitio 1'),
        'sitio2.com' => t('Sitio 2'),
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Send'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $sitio = $form_state->getValue('dominio');
    $email = $form_state->getValue('email');
    $subject = $form_state->getValue('subject');
    $command = "drush sample_master_control:send-email {$email} \"{$subject}\" --uri={$sitio}";
    dd(shell_exec($command));
  }

}
