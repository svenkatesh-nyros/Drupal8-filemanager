<?php

namespace Drupal\file_manager\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\smtp\Plugin\Mail\SMTPMailSystem;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Utility\LinkGenerator;
use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
/**
 * Implements the SMTP admin settings form.
 */
class ShareFile extends ConfigFormBase {
  /**
   * {@inheritdoc}.
   */
  protected $myurl;
  public function getFormID() {
      return 'smtp_admin_settings';
  }
  /**
   * {@inheritdoc}.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
        
          //$current_url= \Drupal::request()->getRequestUri();
          $filename= \Drupal::request()->get('id');
          $config = $this->configFactory->get('smtp.settings');
          $form['#attached']['library'][]= 'file_manager/file_manager_css';
          $form['smtp_test_address'] = array(
              '#type' => 'textfield',
              '#title' => t('To:'),
              '#default_value' => '',
              '#attributes' => array(
                'placeholder' => array('Email'),
               ),
               '#suffix' => '<span class="sharemsg"></span><span class="sharemsg1"></span>',
               );
          $form['test'] = array(
              '#type' => 'textfield',
              '#value' => $filename,
              '#type' => 'hidden',
              );
            
            $form['submit'] = array(
              '#type' => 'submit',
              '#value' => 'Send',
              '#submit' => array('mysubfunction'),
              '#ajax' => array(
                'callback' => '::submitfunction'),
              );
         return parent::buildForm($form, $form_state);
  }



  public function submitfunction(array &$form, FormStateInterface $form_state) {
         
        $values=$form_state->getValues();
        
        if(empty($values['smtp_test_address'])){
          $response= new AjaxResponse();
          $response->addCommand(new HtmlCommand('.sharemsg' , 'Email field is empty'));
          return $response;
        }
        if(!valid_email_address($values['smtp_test_address'])){
          $response= new AjaxResponse();
          $response->addCommand(new HtmlCommand('.sharemsg','Invalid email address'));
          return $response;
        }

         if ($test_address = $values['smtp_test_address']) {
              $params['subject'] = t('Shared a file with you.');
              $params['body'] = array('This is the following url of your shared File '.'
http://10.90.90.53/drupal/sites/default/files/'. $form_state->getValue('test').'

Regards,
Dropbox Team');
              $account = \Drupal::currentUser();
            }
       \Drupal::service('plugin.manager.mail')->mail('smtp', 'smtp-test', $test_address, $account, $params);
          // $command = new CloseModalDialogCommand();
            $response = new AjaxResponse();
            $response->addCommand(new HtmlCommand('.sharemsg1','Email has been sent'));
            $response->addCommand(new CloseModalDialogCommand());
          return $response;

        }
 
  public function mysubfunction(array &$form, FormStateInterface $form_state) {
  }
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }
  public function getEditableConfigNames() {}

}

