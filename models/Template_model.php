<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template_model extends CI_Model {

  private $_id;
  private $_name;
  private $_is_deleted;
  private $_lup;

  private $_data;
  private $_limit_min;
  private $_error;
  private $_total_data;
  
  /**
   * Contructor
   *
   * @access	public
   * @return	boolean
   */
  public function __construct() {
    $this->load->database();
    $this->_table_name = "`profile`";
    $this->_error = '';
    $this->_limit_min = 0;

    $sql = "
      CREATE TABLE IF NOT EXISTS `profile` (
          `id` INT(12) UNSIGNED NOT NULL auto_increment,
          `date` DATE NOT NULL,
          `name` VARCHAR(100) NOT NULL,
          `is_deleted` ENUM('0','1') NOT NULL DEFAULT '0',
          `lup` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
      ) ENGINE=InnoDB;
    ";
    $this->db->query($sql);
  }

  // --------------------------------------------------------------------

  public function set_id($v) { 
  	$this->_id = $v; 
  	$this->_data['id'] = $this->_id;
  }
  public function set_name($v) { 
  	$this->_name = $v; 
  	$this->_data['name'] = $this->_name;
  }
  public function set_is_deleted($v) { 
  	$this->_is_deleted = $v; 
  	$this->_data['is_deleted'] = $this->_is_deleted;
  }
  public function set_lup($v) { 
  	$this->_lup = $v; 
  	$this->_data['lup'] = $this->_lup;
  }
  public function set_data($v) { $this->_data = $v; }
  public function set_error($v) { $this->_error = $v; }
  public function set_limit_min($v) { $this->_limit_min = $v; }
  public function set_total_data($v) { $this->_total_data = $v; }
  
  public function get_id() { return $this->_id; }
  public function get_name() { return $this->_name; }
  public function get_is_deleted() { return $this->_is_deleted; }
  public function get_lup() { return $this->_lup; }
  public function get_data() { return $this->_data; }
  public function get_error() { return $this->_error; }

  // ---------------------------------------------------------------------

  /**
  * insert
  */
  public function insert() {
 
    $insert = $this->db->insert($this->_table_name, $this->_data);
    $this->_id = $this->db->insert_id();
    return $insert;

  }

  // ---------------------------------------------------------------------
  
  /**
  * insert
  */
  public function remove() {

    $this->db->where('id', $this->_id);
    return $this->db->update($this->_table_name, array('is_deleted' => '1'));

  }

  // ---------------------------------------------------------------------

  /**
  * update
  */
  public function update() {
  	$this->db->where('id', $this->_id);
    return $this->db->update($this->_table_name, $this->_data);

  }

  // ---------------------------------------------------------------------

  /**
  * detail
  */
  public function detail() {
  	$this->db->select('*');
  	$this->db->from($this->_table_name);
    $this->db->where('id',$this->_id);
    $this->db->where('is_deleted !=','1');
    return $this->db->get()->result();

  }

  // ---------------------------------------------------------------------

  /**
  * get_list
  */
  public function get_list() {
  	$this->db->select('*');
  	$this->db->from($this->_table_name);
    $this->db->where('is_deleted !=','1');
    $this->db->order_by("lup", "desc");
    $this->db->limit($this->_total_data,$this->_limit_min);
    return $this->db->get()->result();

  }

  // ---------------------------------------------------------------------


}