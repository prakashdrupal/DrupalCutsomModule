<?php

namespace Drupal\recall_campaign;

class RecallCampaignStorage {

  static function getAllRecallCampaign($page ='', $domain='') {
    if(!empty($page)){
      $pagefind = $page;
    } else{
      $pagefind = 'home';
    }
    $result = \Drupal::database()->select('recall_campaign','s')
            ->fields('s')
            ->condition('page', $pagefind, '=')->isNull('domain')->orderBy('position', 'ASC')->execute()->fetchAllAssoc('id');

    if (empty($result)) {
      $result = \Drupal::database()->select('recall_campaign','s')
            ->fields('s')
            ->condition('page', $pagefind, '=')->condition('domain', '', '=')->orderBy('position', 'ASC')->execute()->fetchAllAssoc('id');
    }

    if (empty($result)) {
      $result = \Drupal::database()->select('recall_campaign','s')
            ->fields('s')
            ->condition('page', $pagefind, '=')->condition('domain', $domain, '=')->orderBy('position', 'ASC')->execute()->fetchAllAssoc('id');
    }

    return $result;
  }

  static function getAll() {
    $query = \Drupal::database()->select('recall_campaign','s')->fields('s')->orderBy('id', 'DESC');
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10);
    $results = $pager->execute()->fetchAll();
   
    return $results;
  }

  /**
   * To check if an slider is valid.
   *
   * @param int $id
   *   The slider ID.
   */
  public static function exists($id) {
    $result = \Drupal::database()->select('recall_campaign', 's')
      ->fields('s', ['id'])
      ->condition('id', $id, '=')
      ->execute()
      ->fetchField();
    return (bool) $result;
  }

   static function get($id) {
    $result = db_query('SELECT * FROM {recall_campaign} WHERE id = :id', array(':id' => $id))->fetchAllAssoc('id');
    if ($result) {
      return $result[$id];
    }
    else {
      return FALSE;
    }
  }

  /**
   * To insert a new slider record.
   *
   * @param array $fields
   *   An array conating the slider data in key value pair.
   */
  public static function add(array $fields) {
    return \Drupal::database()->insert('recall_campaign')->fields($fields)->execute();
  }

   /**
   * To update an existing slider record.
   *
   * @param int $id
   *   The slider ID.
   * @param array $fields
   *   An array conating the slider data in key value pair.
   */
  public static function edit($id, array $fields) {
    return \Drupal::database()->update('recall_campaign')->fields($fields)
      ->condition('id', $id)
      ->execute();
  }

  /**
   * To load an slider record.
   *
   * @param int $id
   *   The slider ID.
   */
  public static function load($id) {
    $result = \Drupal::database()->select('recall_campaign', 's')
      ->fields('s')
      ->condition('id', $id, '=')
      ->execute()
      ->fetchObject();
    return $result;
  }

   /**
   * To delete a specific slider record.
   *
   * @param int $id
   *   The slider ID.
   */
  public static function delete($id) {
    $record = self::load($id);
    if ($record->image) {
      file_delete($record->image);
    }
    return \Drupal::database()->delete('recall_campaign')->condition('id', $id)->execute();
  }
  
}
