<?php

class CRM_Tokens_Broodfonds {

  protected static $singelton;

  public static function tokens(&$tokens) {
    $tokens['broodfonds']['broodfonds.geinteresseerden'] = 'Aantal geinteresseerden';
  }

  public function tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
    if (!empty($tokens['broodfonds'])) {
      if (in_array('geinteresseerden', $tokens['broodfonds']) || array_key_exists('geinteresseerden', $tokens['broodfonds'])) {
        $this->geinteresserden($values, $cids, $job, $tokens, $context);
      }
    }
  }

  protected function geinteresseerden(&$values, $cids, $job = null, $tokens = array(), $context = null) {
    $cg = civicrm_api3('CustomGroup', 'getsingle', array('name' => 'Broodfonds'));
    $cf = civicrm_api3('CustomField', 'getsingle', array('custom_group_id' => $cg['id'], 'name' => 'Interresse'));
    $values = array(
      1 => array('Ik ga mee doen met het broodfonds', 'String'),
      2 => array('Ik heb interesse om mee te doen en houd mij op de hoogte', 'String'),
    );

    $sql = "SELECT COUNT(*) FROM `".$cg['table_name']."` WHERE `".$cf['column_name']."` = %1 OR `".$cf['column_name']."` = %2";
    $geinteresserden = CRM_Core_DAO::singleValueQuery($sql, $values);

    foreach($cids as $cid) {
      $values[$cid]['broodfonds.geinteresseerden'] = $geinteresserden;
    }
  }

  /**
   *
   * @return CRM_Tokens_Broodfonds
   */
  public static function singleton() {
    if (!self::$singelton) {
      self::$singelton = new CRM_Tokens_Broodfonds();
    }
    return self::$singelton;
  }

}