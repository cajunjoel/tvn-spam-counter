<?php 
namespace TarValonNet;

use Exception;
use PDO;

class DBHandler {

  private $config = null;
  private $rules = [];
  private $event_name = null;
  private $db = null;
  private $season_id = null;
  private $event_id = null;

  public function __construct($config, $rules, $event_name) {
    $this->config = $config;
    $this->rules = $rules;
    $this->event_name = $event_name;

    if (!isset($this->rules['english_rules'])) {
      $this->rules['english_rules'] = ["(Rule Descriptions Not Provided)"];
    }
    // ----------------------
    // Connect to the database
    // ----------------------
    try {
      $this->db = new \PDO(
    		"mysql:host=".$this->config['database']['host'].
    		";port=".$this->config['database']['port'].
    		";dbname=".$this->config['database']['database'], 
    		$this->config['database']['user'], 
    		$this->config['database']['password']
    	);
      // set the PDO error mode to exception
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(\PDOException $e) {
      throw new Exception("Database connection failed: " . $e->getMessage());
    }
  }

  public function prepare_database($season) {
    // Make sure the season exists
    $stmt = $this->db->prepare('SELECT * FROM season WHERE`name` = ?');
    $result = $stmt->execute(array($season));
    $row = $stmt->fetch();
    if ($row) {
      $this->season_id = $row['id'];
    } else {
      // Add the season
      $stmt = $this->db->prepare("INSERT INTO season (name) VALUES (?);");  
      $result = $stmt->execute(array($season));
      // Get the ID of what we just added
      $this->season_id = $this->db->lastInsertId();
    }


    // Make sure the event exists
    $stmt = $this->db->prepare('SELECT * FROM `event` WHERE `name` = ?');
    $result = $stmt->execute(array($this->event_name));
    $row = $stmt->fetch();
    if ($row) {
      $this->event_id = $row['id'];
    } else {
      // Add the event
      $stmt = $this->db->prepare("INSERT INTO `event` (`name`, rules, season_id) VALUES (?, ?, ?);");  
      $result = $stmt->execute(array(
        $this->event_name, 
        implode('<br>', $this->rules['english_rules']), 
        $this->season_id
      ));
      // Get the ID of what we just added
      $this->event_id = $this->db->lastInsertId();
    }
  }

  public function log_post($post) {

    $stmt = $this->db->prepare('SELECT * FROM post WHERE post_url = ?');
    $result = $stmt->execute(array($post['url']));
    $row = $stmt->fetch();
    if (!$row) {

      $stmt = $this->db->prepare(
        "INSERT INTO `post` (season_id, event_id, author, group_name, post_url, page_url, post, valid, invalid_reason, extra_data) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"
      );
      $extra = [];
      foreach (array_keys($post) as $k) {
        if ($k != 'author' && $k != 'group' && $k != 'url' && $k != 'page_url' && 
            $k != 'post' && $k != 'valid' && $k != 'invalid_reason') {
          $extra[] = $post[$k];
        }
      }
      $params = array(
        $this->season_id,
        $this->event_id,
        $post['author'],
        $post['group'],
        $post['url'],
        $post['page_url'],
        $post['post'],
        ($post['valid'] ? 1 : 0),
        $post['invalid_reason'],
        serialize($extra),
      );
      $result = $stmt->execute($params);
    }
  }

}
