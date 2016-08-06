<?php

namespace Drupal\file_manager\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Url;
use Drupal\Core\Ajax\AjaxResponse;

  class UploadFile extends FormBase {
      /**
       * {@inheritdoc}
       */
      public function getFormId() {
        return 'UploadFileForm';
      }
      public function buildForm(array $form, FormStateInterface $form_state)
       {
          $form['file_upload'] = array(
            '#type' => 'managed_file',
            '#name' => 'file_upload',
            '#title' => $this->t('Selcet your file'),
            '#size' => 40,
            '#upload_location' => 'public://'
          ); 
          $form['actions']['#type'] = 'actions';
          $form['actions']['submit']= array(
             '#type' =>'submit',
             '#value' => $this->t('Save'),
             '#button_type' => 'primary',
          );
            return $form; 
       }
       
       public function validateForm(array &$form , FormStateInterface $form_state){

       }

       public function submitForm(array &$form, FormStateInterface $form_state)
        {
              if(empty($form_state->getValue('file_upload'))){
                  drupal_set_message("Plesase select your file.");
              }
              else{
                drupal_set_message('Your file uploaded successfully');
               }
        }
  }

  
