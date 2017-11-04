<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//include Rest Controller library
require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

    public function __construct() { 
        parent::__construct();
		// Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	public function index_get() {
		$uri = $this->uri->segment_array();
		$start = $this->input->get('start');
		$limit = $this->input->get('limit');
		$table = isset($uri[2]) ? $uri[2] : 0;
		$id = isset($uri[3]) ? $uri[3] : 0;
		$prefix = 'ref';
		if($table == 'pegawai' || $table == 'jakhir' || $table == 'tkerja' || $table == 'identpeg'){
			$prefix = 'peg';
		}
		if($table == 'pegawai'){
			$table = 'identpeg';
		}
		if ($this->db->table_exists($prefix.'_'.$table)){
			$fields = $this->db->field_data($prefix.'_'.$table);
			foreach($fields as $field){
				if($field->primary_key){
					$set_key = $field->name;
				}
			}
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'Table not found.'
			], REST_Controller::HTTP_NOT_FOUND);
			return false;
		}
		$this->load->model($table.'_model', $table);
		//$query = $this->guru->with('pembelajaran')->find_all("sekolah_id = $loggeduser->sekolah_id AND (nama LIKE '%$search%' OR nuptk LIKE '%$search%')", '*','nama ASC', $start, $rows);
		$start = ($start) ? $start : 0;
		$rows = ($limit) ? $limit : 25;
		if($id && $id != '%7Bid%7D'){
			$query = $this->{$table}->get($id);
		} else {
			$count = $this->{$table}->count_all();
			$query = $this->{$table}->find_all("$set_key IS NOT NULL", "*", "$set_key ASC", $start, $rows);
		}
		//check if the user data exists
		if(!empty($query)){
			if(is_array($query)){
				foreach($query as $q){
					$key = $q->{$set_key};
					$set_query[$key] = $q;
				}
				unset($query);
				$query['count'] = $count;
				$query['start'] = $start;
				$query['limit'] = $rows;
				$query['data'] = $set_query;
			}
			//test($query);
			//set the response and exit
			//OK (200) being the HTTP response code
			$this->response($query, REST_Controller::HTTP_OK);
		}else{
			//set the response and exit
			//NOT_FOUND (404) being the HTTP response code
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function jumlah_pegawai_get() {
		$a = $this->input->get_request_header('X-API-KEY', TRUE);
		$b = $this->input->get_request_header('CLIENT', TRUE);
		$query = $this->db->query("SELECT COUNT(peg_identpeg.nip) AS jumlah FROM peg_identpeg 
		WHERE peg_identpeg.aktif = 1");
		$result = $query->row();
		if(!empty($result)){
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function jumlah_pegawai_eselon_get() {
		$a = $this->input->get_request_header('X-API-KEY', TRUE);
		$b = $this->input->get_request_header('CLIENT', TRUE);
		$query = $this->db->query("SELECT ref_eselon.neselon, COUNT(*) as jumlah FROM peg_identpeg LEFT JOIN peg_jakhir ON peg_identpeg.nip = peg_jakhir.nip LEFT JOIN ref_eselon ON peg_jakhir.keselon = ref_eselon.keselon LEFT JOIN ref_statpeg ON peg_identpeg.kstatus = ref_statpeg.kstatus WHERE peg_identpeg.KEADAAN != 2  AND peg_identpeg.aktif = 1 GROUP BY ref_eselon.neselon");
		$result = $query->result();
		if(!empty($result)){
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function jumlah_golongan_get() {
		$a = $this->input->get_request_header('X-API-KEY', TRUE);
		$b = $this->input->get_request_header('CLIENT', TRUE);
		$query = $this->db->query("SELECT MID(b.KGOLRU,2,1) AS Golongan,COUNT(*) as jumlah  
FROM peg_pakhir a 
LEFT JOIN ref_golruang b ON (a.KGOLRU = b.KGOLRU) 
LEFT JOIN peg_jakhir c ON a.NIP = c.NIP 
 GROUP BY  MID(b.KGOLRU,2,1)");
		$result = $query->result();
		if(!empty($result)){
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function pensiun_person_get() {
		$a = $this->input->get_request_header('X-API-KEY', TRUE);
		$b = $this->input->get_request_header('CLIENT', TRUE);
		$query = $this->db->query("SELECT COUNT(NIP) as jumlah,
  CASE
  WHEN DATEDIFF(NOW(), tlahir) / 365.25 < 20 THEN '20 Kebawah'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 20 AND 29 THEN '20 - 29'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 30 AND 39 THEN '30 - 39'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 40 AND 49 THEN '40 - 49'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 50 AND 59 THEN '50 - 59'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 60 AND 69 THEN '60 - 69'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 BETWEEN 70 AND 79 THEN '70 - 79'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 >= 80 THEN '80 Ke Atas'
        WHEN DATEDIFF(NOW(), tlahir) / 365.25 IS NULL THEN 'Data Kosong' 
  END AS batasan_umur
FROM peg_identpeg
GROUP BY batasan_umur");
		$result = $query->result();
		if(!empty($result)){
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function pensiun_get() {
		$a = $this->input->get_request_header('X-API-KEY', TRUE);
		$b = $this->input->get_request_header('CLIENT', TRUE);
		$query = $this->db->query("SELECT DISTINCT peg_identpeg.nip, 
peg_identpeg.nama, 
peg_identpeg.gldepan, 
peg_identpeg.glblk , 
ref_statpeg.nstatus,  
ref_eselon.neselon, 
IF (peg_identpeg.kstatus < 3,'0','1') AS kstat 
FROM peg_identpeg
 LEFT JOIN peg_jakhir ON peg_identpeg.nip = peg_jakhir.nip 
	LEFT JOIN ref_eselon ON peg_jakhir.keselon = ref_eselon.keselon 
	LEFT JOIN ref_statpeg ON peg_identpeg.kstatus = ref_statpeg.kstatus 
WHERE peg_identpeg.KEADAAN = 2  AND peg_identpeg.aktif = 1");
		$result = $query->result();
		if(!empty($result)){
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function kenaikan_pangkat_get() {
		$a = $this->input->get_request_header('X-API-KEY', TRUE);
		$b = $this->input->get_request_header('CLIENT', TRUE);
		$query = $this->db->query("SELECT peg_identpeg.nip,peg_identpeg.NAMA FROM peg_identpeg LEFT JOIN peg_tkerja 
  ON (peg_identpeg.nip = peg_tkerja.nip)
  LEFT JOIN ref_unkerja 
  ON (peg_tkerja.kunker= ref_unkerja.kunker)
  LEFT JOIN ref_eselon 
        ON (ref_unkerja.keselon = ref_eselon.keselon)
  LEFT OUTER JOIN peg_jakhir
        ON (peg_jakhir.nip = peg_identpeg.nip)
WHERE    (1=1)
AND (TIMESTAMPDIFF( YEAR, tmtjab, NOW())) = 2
AND (TIMESTAMPDIFF( MONTH, tmtjab, NOW() ) % 12) =0");
		$result = $query->result();
		if(!empty($result)){
			$this->response($result, REST_Controller::HTTP_OK);
		} else {
			$this->response([
				'status' => FALSE,
				'message' => 'No data were found.'
			], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	/*public function dikfung_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function dikstr_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function diktek_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function dukpej_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function dukpns_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function eselon_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function gapok_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function geselon_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function ggolruang_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function golruang_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function hukumandis_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function insinduk_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jabfung_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jabneg_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jabnstr_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jdiktek_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jenis_cuti_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jenisjab_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jenispenghargaan_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jenistugas_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jenpeg_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jfu_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jpensiun_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jurpend_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function kbayar_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function keljfu_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function klmpjab_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function klmpusia_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function kpe_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function ktua_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function kursus_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function lembagapend_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function loknegara_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function marga_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function matpel_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function naikpang_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function pejabat_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function pkerjaan_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function seminar_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function sgoldar_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function tatar_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function tingpend_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function tpu_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function uniturusan_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function unkerja_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function wilayah_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function pegawai_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function tkerja_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	public function jakhir_get($id = '') {
		$api['key'] = $this->input->get_request_header('X-API-KEY', TRUE);
		$api['key'] = $this->input->get_request_header('CLIENT', TRUE);
	}
	*/
}

?>
