<?php


namespace Drupal\insign_early_access\Form;


use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityRepositoryInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\user\Entity\User;
use Drupal\user\RegisterForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a user register form.
 */
class UserRegisterForm extends RegisterForm {

  const EARLY_ACCESS_CODE = '42BDF75C3561INSDDIGN896FF8E85032';

  /**
   * UserRegisterForm constructor.
   *
   * @param EntityRepositoryInterface $entity_manager
   * @param LanguageManagerInterface $language_manager
   * @param ModuleHandlerInterface $moduleHandler
   * @param EntityTypeBundleInfoInterface|null $entity_type_bundle_info
   * @param TimeInterface|null $time
   */
  public function __construct(
    EntityRepositoryInterface $entity_manager,
    LanguageManagerInterface $language_manager,
    ModuleHandlerInterface $moduleHandler,
    EntityTypeBundleInfoInterface $entity_type_bundle_info = NULL,
    TimeInterface $time = NULL
  ) {
    $this->setEntity(new User([], 'user'));
    $this->setModuleHandler($moduleHandler);
    parent::__construct($entity_manager, $language_manager, $entity_type_bundle_info, $time);
  }
  /**
   * @inheritdoc
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('language_manager'),
      $container->get('module_handler'),
      $container->get('entity_type.bundle.info'),
      $container->get('datetime.time')
    );
  }

  /**
   * @param array $form
   * @param FormStateInterface $form_state
   *
   * @return array
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['common_code'] = [
      '#type' => 'textfield',
      '#title' => 'Code',
      '#placeholder' => "Enter le code d'accès",
      '#maxlength' => '255',
    ];

    $form['single_usage_code'] = [
      '#type' => 'textfield',
      '#title' => 'Code à usage unique',
      '#placeholder' => "Enter un code d'accès à usage unique",
      '#maxlength' => '255',
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $codeValue            = $form_state->getValue('common_code');
    $singleUsageCodeValue = $form_state->getValue('single_usage_code');

    if (empty($codeValue) && empty($singleUsageCodeValue)) {
      $form_state->setErrorByName('common_code', "Veuillez entrer un code d'accès.");
    }

    // Validate early access code
    if (!empty($codeValue) && ($codeValue !== self::EARLY_ACCESS_CODE)) {
      $form_state->setErrorByName('common_code', "Le code fourni n'est pas valide.");
    }

    // Validate single usage code
    if (!empty($singleUsageCodeValue)) {
      $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties([
            'vid' =>  'code_a_usage_unique',
            'name' => $singleUsageCodeValue,
            'field_status' => 0
          ]
        );

      if (!reset($terms)) {
        $form_state->setErrorByName('single_usage_code', "Le code à usage unique fourni n'est pas valide.");
      }
    }


    parent::validateForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Set code as used and add it user mail
    $singleUsageCodeValue = $form_state->getValue('single_usage_code');
    if (!empty($singleUsageCodeValue)) {
      $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadByProperties([
            'vid' => 'code_a_usage_unique',
            'name' => $singleUsageCodeValue,
            'field_status' => 0
          ]
        );
      $term = reset($terms);
      $term->field_status->setValue(TRUE);
      $term->field_user_email->setValue($form_state->getValue('mail'));
      $term->Save();
    }
    parent::submitForm($form, $form_state);
  }
}
