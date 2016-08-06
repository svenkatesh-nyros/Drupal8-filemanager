<?php

namespace Drupal\file_manager\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file_manager\FileStorage;


class DeleteFile extends ConfirmFormBase {
  protected $fid;

  function getFormId() {
    return 'file_delete_new';
  }

  function getQuestion() {
    return t('Are you sure you want to delete this?');
  }

  function getConfirmText() {
    return t('Delete');
  }

  function getCancelUrl() {
    return new Url('file_manager_list');
  }

  function buildForm(array $form, FormStateInterface $form_state) {
    $this->fid = \Drupal::request()->get('id');
    return parent::buildForm($form, $form_state);
  }

  function submitForm(array &$form, FormStateInterface $form_state) {
    FileStorage::filedelete($this->fid);
    //watchdog('bd_contact', 'Deleted BD Contact Submission with id %id.', array('%id' => $this->id));
       $form_state->setRedirect('file_manager_list');
  }
}

