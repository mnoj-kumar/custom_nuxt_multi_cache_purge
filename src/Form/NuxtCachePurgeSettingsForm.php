<?php

namespace Drupal\custom_nuxt_multi_cache_purge\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class NuxtCachePurgeSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['custom_nuxt_multi_cache_purge.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_nuxt_multi_cache_purge_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_nuxt_multi_cache_purge.settings');

    $form['frontend_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Frontend URL'),
      '#default_value' => $config->get('frontend_url'),
      '#required' => TRUE,
      '#description' => $this->t('Enter the frontend domain url for purging cache.'),
    ];

    $form['purge_endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Purge Endpoint URL'),
      '#default_value' => $config->get('purge_endpoint'),
      '#required' => TRUE,
      '#description' => $this->t('Enter the API endpoint for purging cache.'),
    ];

    $form['auth_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Authentication Token'),
      '#default_value' => $config->get('auth_token'),
      '#required' => TRUE,
      '#description' => $this->t('Enter the authentication token required for API requests.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('custom_nuxt_multi_cache_purge.settings')
      ->set('frontend_url', $form_state->getValue('frontend_url'))
      ->set('purge_endpoint', $form_state->getValue('purge_endpoint'))
      ->set('auth_token', $form_state->getValue('auth_token'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
