<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_sessions_model extends CI_Model {

  private $_sess_id;
  private $_username;
  private $_expired;
  private $_is_deleted;
  private $_lup;

  /**
	 * Contructor
	 *
	 * @access	public
	 * @return	boolean
	 */
  public function __construct() {
    $this->load->database();
    $this->_table_name = "`user_sessions`";
  }
  
  // --------------------------------------------------------------------

  public function set_sess_id($v) { $this->_sess_id = $v; }
  public function set_username($v) { $this->_username = $v; }
  public function set_expired($v) { $this->_expired = $v; }
  public function set_is_deleted($v) { $this->_is_deleted = $v; }
  public function set_lup($v) { $this->_lup = $v; }

  public function get_sess_id() { return $this->_sess_id; }
  public function get_username() { return $this->_username; }
  public function get_expired() { return $this->_expired; }
  public function get_is_deleted() { return $this->_is_deleted; }
  public function get_lup() { return $this->_lup; }

  // ---------------------------------------------------------------------

  /**
  * insert
  */
  public function insert() {
 
    $data = array(
      'username' => $this->_username,
      'sess_id' => $this->_sess_id,
      'expired' => $this->_expired,
		);

		return $this->db->insert($this->_table_name, $data);

  }

  // ---------------------------------------------------------------------

  /**
  * insert
  */
  public function is_exist() {

    $this->db->select('*');
    $this->db->from($this->_table_name);
    $this->db->like('username',$this->_username);
    $this->db->like('sess_id',$this->_sess_id);
    $this->db->like('expired',$this->_expired);
    $query = $this->db->get();
    return ($query->num_rows()>0)?true:false;

  }

  // ---------------------------------------------------------------------

  /**
  * remove_all_session
  */
  public function remove_all_session() {

    $this->db->set('is_deleted', '1');
    $this->db->where('username', $this->_username);
    return $this->db->update($this->_table_name);

  }

  // ---------------------------------------------------------------------

  /**
  * remove_all_session
  */
  public function remove_session() {

    $this->db->set('is_deleted', '1');
    $this->db->where('sess_id', $this->_sess_id);
    return $this->db->update($this->_table_name);

  }

  // ---------------------------------------------------------------------

  /**
  * insert
  */
  public function is_valid() {

    $this->db->select('*');
    $this->db->from($this->_table_name);
    $this->db->where('sess_id',$this->_sess_id);
    $this->db->where('expired >',$this->_expired);
    $query = $this->db->get();
    $this->_username = $query->row('username');
    return ($query->num_rows()>0)?true:false;

  }

  // ---------------------------------------------------------------------


}