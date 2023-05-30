<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DBLib {
	var $CI;		
	
	/**
	 * Column Field Variable
	 *
	 * @var Array
	 */
	public $fields = array(); 
	/**
	 * Primary Key of Table
	 *
	 * @var unknown_type
	 */
	public $keyfield ="id";

	public $columns = array(); 
	/**
	 * Main Table
	 *
	 * @var unknown_type
	 */
	public $master_table =""; 
	/**
	 * Set Join To Relationship Table
	 *
	 * @var String
	 */
	public $join ="";
	/**
	 * Set Where Condition
	 *
	 * @var string
	 */
	public $where="0=0"; 	
	public $start=0;	
	public $count=0; 	
	public $total =0;	
	public $sort=""; 	
	public $dir="";	
	public $errMsg=""; 	
	public $json =""; 	
	public $manualFilter ="";	
	public $groupBy ="";	
	public $dataInput; 	
	public $dataOutput;	
	public $loadSingle =false;
    public $manualOrder ="";
    
	/**
	 * Array Input For REST Operation
	 *
	 * @var boolean
	 */
	private $isArrayInput = true; 	
	private $sqlNolimit =0; 
	private $sql_query,
            $sql_array;
	/**
	 * Constructor to Connection Database
	 *
	 * @param boolean $connection
	 */
	function __construct() {												
		$this->CI =& get_instance();
		$this->CI->load->database();
	}
	
	/**
	 * Destructor Class
	 *
	 */
	function __destruct() {		
	}

	/**
   * Execute String Sql 
   * Use executeSQL to return resutl
   * @param string $the_sql
   */
	public function setSQL($the_sql)
	{ 
		$this->sql_query = $the_sql;
	}
	public function setArray($the_array)
	{
		$this->sql_array = $the_array;
	}
	/**
	 * Set Main Table
	 *
	 * @param string $table
	 */	 
	function setTable($table) {
		$this->master_table = $table;                
	}
	
	/**
	 * Set Primary Key on Table
	 *
	 */
	function setKeyfield(){
		$list = $this->fields; 
		foreach ($list as $col){
			if (array_key_exists("primary",$col))
				$this->keyfield = $col['field']; 
		}
	}
	
	/**
	 * Add Relationship Table
	 *
	 * @param string $join
	 */
	function setJoin($join) {
		$this->join = $join; 
	}

  	function setOrderBy($order){
    	$this->manualOrder = $order; 
  	}

	/**
	 * Set Group By field Table
	 *
	 * @param string $groupBy
	 */
	function setGroupBy($groupBy) {
		$this->groupBy = $groupBy;
	}
	
	/**
	 * Adding Fieldlist Table
	 *
	 * @param array $field
	 */
	function addField($field) {
		$this->fields[] = $field; 
	}
	
	function addColumn($column){
		$this->columns[] = $column; 
	}
	
	function formatColumns(){
		return json_encode($this->columns); 
	}
	
	function getColumns() {
		foreach ($this->fields as $field){
			if ($field['cm'])
				$this->addColumn($field['cm']);
		}
		return $this->formatColumns(); 
	}
	/**
	 * Add Manual Filter in clause Where
	 *
	 * @param string $manualFilter
	 */
	function setManualFilter($manualFilter) {
		$this->manualFilter = $manualFilter;
	}

	/**
	 * Find Key Field on Column Name
	 *
	 * @param string $field
	 * @return string
	 */
	function getKeyField($field) {
		$field_name = '';
		$tmp = $this->fields; 
		foreach($tmp as $data)
			if ($data['name'] ==$field)
				$field_name = $data['field'];
		return $field_name;
	}

	function getExtraFunction($field){		
	}
	/**
	 * Send Parameter to filter base on Extjs Grid
	 *
	 * @param unknown_type $params
	 */
	function sendParam($params) {
		$this->start = (!isset($params["start"]))? 0 : $params["start"];
		$this->count = (!isset($params["limit"] ))? 1 : $params["limit"];
		$this->sort = (!isset($params["sort"]))? "" : $params["sort"];
		if (isset($params["dir"]))
			$this->dir = ($params["dir"] == "DESC")? "DESC" : "";
		$the_filter = (isset($params["filter"])?$params["filter"]:""); 
		$this->buildFilter($the_filter);
	}

	function buildFilter($filter) {
		$this->where = " 0 = 0 ";		
		$qs = ""; 		
		if (is_array($filter)) {
			for ($i=0;$i<count($filter);$i++){
				$filter[$i]['field'] = $this->getKeyField($filter[$i]['field']); 
				switch($filter[$i]['data']['type']){
					case 'string' : $qs .= " OR ".$filter[$i]['field']." LIKE '%".$filter[$i]['data']['value']."%'"; break;
					case 'list' : 
						if (strstr($filter[$i]['data']['value'],',')){
							$fi = explode(',',$filter[$i]['data']['value']);
							for ($q=0;$q<count($fi);$q++){
								$fi[$q] = "'".$fi[$q]."'";
							}
							$filter[$i]['data']['value'] = implode(',',$fi);
							$qs .= " OR ".$filter[$i]['field']." IN (".$filter[$i]['data']['value'].")"; 
						}else{
							$qs .= " OR ".$filter[$i]['field']." = '".$filter[$i]['data']['value']."'"; 
						}
					    break;
					case 'boolean' : $qs .= " OR ".$filter[$i]['field']." = ".($filter[$i]['data']['value']); break;
					case 'numeric' : 
						switch ($filter[$i]['data']['comparison']) {
							case 'eq' : $qs .= " OR ".$filter[$i]['field']." = ".$filter[$i]['data']['value']; break;
							case 'lt' : $qs .= " OR ".$filter[$i]['field']." < ".$filter[$i]['data']['value']; break;
							case 'gt' : $qs .= " OR ".$filter[$i]['field']." > ".$filter[$i]['data']['value']; break;
						}
					break;
					case 'date' : 
						switch ($filter[$i]['data']['comparison']) {
							case 'eq' : $qs .= " AND ".$filter[$i]['field']." = '".
								date('Y-m-d',strtotime($filter[$i]['data']['value']))."'"; break;
							case 'lt' : $qs .= " AND ".$filter[$i]['field']." < '".
								date('Y-m-d',strtotime($filter[$i]['data']['value']))."'"; break;
							case 'gt' : $qs .= " AND ".$filter[$i]['field']." > '".
								date('Y-m-d',strtotime($filter[$i]['data']['value']))."'"; break;
						}
					break;
				}
			}	
			$this->where .= "AND (".substr($qs, 3).")";
		}
	}

	function buildList($list){
		$tmp = array(); 
		foreach($list as $arr) {
			$tmp[] = $arr['field'] . " AS ". $arr['name']; 
		}
		return $tmp; 
	}
	
	function isMasterField($field) {
		$result = true;
		$tmp = $this->fields;
		foreach($tmp as $data){                        
			if ($data['name'] ==$field) {
				if(isset($data['join_table'])) {
                    $result =false;
                }
            }
        }
		return $result; 
	}
  function add_always_column_avaible($column){
    $field =Array();
    foreach($this->fields as $col) {
      if (isset($col['always_include']))
        $field[] = $col['name'];
    }
    if ($field){
      $str_col = implode(" ", $column);
      foreach($field as $col_field)
      if (!preg_match("/\b$col_field\b/i", $str_col))
        $column[] = $this->getKeyField($col_field) . " AS " . $col_field;
    }
    return $column;
  }
	
	function buildField($fields){
		$col_field = array();
		$col_value = array(); 
		$keyvalue =  array(); 
		$col_select = array(); 
		foreach($fields as $arr=>$value) {
			if ($this->getKeyField($arr) != $this->keyfield){
				if ($this->isMasterField($arr)){
                    switch ($arr){
                        case 'uid': case 'udt': case 'lastuid': case 'lastudt':
                            $col_field[] = $arr; 
                            $col_value[] = $value;
                            break;
                        default:
					        $col_field[] = $this->getKeyField($arr); 
					        $col_value[] = $value;
                            break;
                    }
				}
			} else { 
				$keyvalue[] = $value; 
			}
            switch ($arr){
                case 'uid': case 'udt': case 'lastuid': case 'lastudt':
                    $col_select[] = $arr . " AS " . $arr;
                    break;
                default:
                    $col_select[] = $this->getKeyField($arr) . " AS " . $arr;
                    break;
            }        
		}
    $col_select = $this->add_always_column_avaible($col_select);
		$tmp = Array(
					"fields" =>$col_field, 
					"value" =>$col_value,
					"select" =>$col_select,
					"id"=>$keyvalue
					); 
		return $tmp; 
	}
	
	function buildSql($params, $is_proc = false, $proc = '') {
		$list_field="";
        
        $fields = isset($params['fields'])?$params['fields']:'';
        if ($fields) {
            $list_field = implode(",",$this->buildList($fields));
        } else {
		    $list_field = implode(",",$this->buildList($this->fields));
        }
        if ($this->CI->db->dbdriver == "odbc"){
        	$top = 'TOP '.(intval($this->start)+intval($this->count));  
		} else {
			$top = '';
		}
        if ($is_proc === false) {
			$query = "SELECT $top $list_field from ". $this->master_table ." " . $this->join; 
			$query .= " WHERE ". $this->where ." ".$this->manualFilter;
			if ($this->groupBy !="")
				$query .= " GROUP BY ".$this->groupBy;    
	        if ($this->manualOrder != ""){
	          $query .= " ORDER BY ". $this->manualOrder;      
	        }else{
	          if ($this->sort !="")
				    $query .= " ORDER BY ".$this->getKeyField($this->sort)." ".$this->dir;
	        }
	        if ($this->CI->db->dbdriver != "odbc"){
				if (!$this->sqlNolimit) {
		            if ($this->count > 0){
					    $query .= " LIMIT ".$this->start.",".$this->count;
		            }
		        }
			}
		} else {
			$query = $proc;
		}
        		
		$rs = $this->CI->db->query($query);
		if ($rs === false)
			$this->errMsg = "Error On Execute query : $query \n Error: ".$this->CI->db->_error_message(); 
			
		$rows = $rs->result();
        $rowcount = count($rows);
        if ($rows !== false && count($rows) > 0) {                                
            if ($this->CI->db->dbdriver == 'odbc') {
                $rs = array();                                      
                if ((intval($this->start)+intval($this->count)) > count($rows)) {
                	if (((intval($this->start)+intval($this->count)) - count($rows)) < count($rows)) {
                		$this->count = count($rows);
                	} else {
                    	$this->count = (intval($this->start)+intval($this->count)) - count($rows);    
					}
                }
                
                $rs = array_slice($rows, $this->start, $this->count);                    
                $rows = $rs;                                        
            }                
        }
            
		if (!$this->sqlNolimit)	
			$this->buildJson($rows, $is_proc, $rowcount); 
		else 
			return $rows;						 
	}

	function buildJson($rs, $is_proc = false, $dcount=0) {
	    $tmp = array(); 
		if ($this->errMsg ==""){
			if ($is_proc === false) {
				$this->getTotal(); 
			} else {
				$this->total = $dcount;
			}
			$arr = array(); 
			foreach ($rs as $row) {
				$the_col = array();
				foreach($row as $key=>$x){
					$key = strtolower($key);
					$the_col[$key] = $x;
				}	
				$arr[] = $the_col; 
			}
			$tmp['success'] = true; 
			$tmp['total'] = $this->total; 
			$tmp['data'] = $arr; 
			if ($this->loadSingle){
			  if ($arr)
			    $tmp['data'] = $arr[0];
			}
			$tmp['metaData']= $this->buildMetaData(); 
		} else {
			$tmp['success']= false; 
			$tmp['total'] = 0; 
			$tmp['data']= Array(); 
			$tmp['message'] = $this->errMsg;
		}
		
		$this->json = json_encode($tmp); 	
	}

	function buildSingleJson($rs) {
		$the_col = array(); 
		foreach ($rs as $row) {
			foreach($row as $key=>$x){
				$key = strtolower($key);
				$the_col[$key] = $x;
			}	
		}
		return $the_col;
	}
	
	/**
	 * View Data on Json Format
	 *
	 * @param unknown_type $params
	 * @return json string
	 */
	function doRead($params, $is_proc = false, $proc = '') {
		$this->sendParam($params); 
		$this->buildSql($params, $is_proc, $proc);
		return $this->json; 
	}

	function doSql($params) {
		$this->sqlNolimit =1; 
		$this->sendParam($params); 
		return $this->buildSql();
	}
	
	function getTotal() {
		$sql ="SELECT COUNT(1) as total FROM ". $this->master_table . " ".$this->join; 
		$sql.= " WHERE ". $this->where." ".$this->manualFilter; 
		if ($this->groupBy !="")
			$sql .= " GROUP BY ".$this->groupBy;
		$this->setSQL($sql);
		$query = $this->CI->db->query($sql); 
		$rs = $query->result();
		if ($this->groupBy =="")
			$this->total = $rs[0]->total; 
		else 
			$this->total = $this->CI->db->num_rows(); 
	}
	
	function setIsArrayInput(){
		$tagArray = substr($this->dataInput,0,1); 
		$this->isArrayInput =($tagArray == "[")?true:false; 
	}
	
	/**
	 * Insert into Table Master using field list
	 *
	 * @param unknown_type $dataInput
	 * @return json string
	 */
	function doCreate($dataInput) {
		$this->dataInput = $dataInput; 
		$this->setIsArrayInput(); 
		$this->setKeyfield(); 
		$this->executeInsert(); 
		return $this->dataOutput; 
	}

	/**
	 * Update Table Master using CRUD
	 *
	 * @param unknown_type $dataInput
	 * @return json string
	 */
	function doUpdate($dataInput) {
		$this->dataInput = $dataInput; 
		$this->setIsArrayInput(); 
		$this->setKeyfield(); 
		$this->executeUpdate(); 
		return $this->dataOutput; 
	}

	function executeUpdate(){		
		$query = $this->buildFieldUpdate(); 
		$msgArray = Array(); 
		$json = Array(); 
		$total = 0; 
		foreach ($query as $sql) {
			$sqlUpdate = $sql['sqlUpdate']; 
			$error = $this->CI->db->query($sqlUpdate['sql'],$sqlUpdate['args']); 			
			if ($error === false){
				$msgArray[] = $this->CI->db->_error_message(); 
			} else {
				$total++; 
				$sqlSelect = $sql['sqlSelect']; 
				$rs = $this->CI->db->query($sqlSelect['sql'],$sqlSelect['args']); 
				$json[] = $this->buildSingleJson($rs->result_array()); 
			}
		}
		$result = array(); 
		if ($total) {
			if ($this->isArrayInput)
				$result['data'] = $json; 
			else 
				$result['data'] = $json[0];
				 
			$result['total'] = $total; 
			$result['success'] = true; 
			$result['message']['note'] = "Data has been updated Succesfully!"; 	
		} else{
			$result['success'] =false; 
			$result['data'] = $json; 
			$result['message']['note'] = "Failed to update data!"; 
		}
			$result['message']['error'] = $msgArray; 	
		
		$this->dataOutput = json_encode($result); 
	}

	function executeInsert(){
		$query = $this->buildFieldInsert(); 
		$msgArray = Array(); 
		$json = Array(); 
		$total = 0; 
		foreach ($query as $sql) {
			$sqlInsert = $sql["sqlInsert"];
			$this->CI->db->query($sqlInsert['sql'],$sqlInsert['args']); 
			if ($this->CI->db->_error_message() !=""){
				$msgArray[] = $this->CI->db->_error_message();  
			} else {
				$total++; 
				$sqlSelect = $sql["sqlSelect"]; 
				$this->setSQL($sqlSelect);				
				$this->setArray(Array($this->getLastID()));								
				$rs = $this->CI->db->query($sqlSelect, $this->sql_array);
				$json[] = $this->buildSingleJson($rs->result_array()); 
			}
		}
		$result = array(); 
		if ($total) {
			if ($this->isArrayInput)
				$result['data'] = $json; 
			else 
				$result['data'] = $json[0]; 
			$result['total'] = $total; 
			$result['success'] = true; 
			$result['message']['note'] = "New Record has been saved succesfully!"; 	
		} else{
			$result['success'] =false; 
			$result['data'] = $json; 
			$result['message']['note'] = "Failed to insert new record!"; 
		}
			$result['message']['error'] = $msgArray; 	
		
		$this->dataOutput = json_encode($result); 
	}
	
	function buildUpdateQuery($data) {
		$row = $this->buildField($data);  
		$fields = $row['fields']; 
		$tmp = Array(); 
		foreach ($fields as $ff){
			if ($ff)
				$tmp[] =$ff; 
		}
		$fields = $tmp;        
		$fieldSql = array(); 
		foreach($fields as $field) {
			$fieldSql[] = $field ."=?";  
		}
		$strSql = "UPDATE " . $this->master_table . " set " . implode(",",$fieldSql); 
		$strSql .= " WHERE ". $this->keyfield ."=?"; 
		$args = array_merge($row['value'],$row['id']);  
		$result = array(
			"sql"=>$strSql,
			"args"=>$args
		); 
		return $result; 
	}
    
	function buildInsertQuery($data) {
		$row = $this->buildField($data);  
		$fields = $row['fields']; 
		$tmp = Array(); 
		foreach ($fields as $ff){
			if ($ff)
				$tmp[] =$ff; 
		}
                
		$fields = $tmp;
		$fieldSql = array(); 
		foreach($fields as $field) {
			$fieldSql[] = "?";  
		}
		$strSql = "INSERT INTO ".$this->master_table ."(". implode(",",$fields) .")"; 
		$strSql .= " VALUES(". implode(",",$fieldSql) .")"; 
		$args = $row['value'];   
		$result = array(
			"sql"=>$strSql,
			"args"=>$args
		); 
		return $result; 
	}
	
	function buildUpdateQuerySelect($data) {
		$row = $this->buildField($data);  
		$fields = $row['select']; 
		$tmp = Array(); 
		foreach ($fields as $ff){
			if ($ff)
				$tmp[] =$ff; 
		}
		$fields = $tmp;
		$selectlist = implode(",",$fields); 
		$strSql ="SELECT ". $selectlist ." FROM ". $this->master_table ." " . $this->join ." WHERE ". $this->keyfield ."=?"; 
		$args = $row['id'];  
		$result = array(
			"sql"=>$strSql,
			"args"=>$args
		);
		return $result; 
	}

	function getLastRecordSql($data) {
		$row = $this->buildList($this->fields);
		$selectlist = implode(",",$row); 	
		$strSql = "select ".$selectlist." from ".$this->master_table." ".$this->join." where ".$this->keyfield." =?";
		return $strSql; 
	}
	
	function buildFieldUpdate() {
		$result = array(); 
		$jsonInput = json_decode(stripslashes($this->dataInput)); 
		if ($this->isArrayInput) {
			foreach ($jsonInput as $row) 
				$result[] = Array(
					"sqlUpdate"=>$this->buildUpdateQuery($row),
					"sqlSelect"=>$this->buildUpdateQuerySelect($row)
				); 
		} else {
			$result[] = Array(
				"sqlUpdate"=>$this->buildUpdateQuery($jsonInput),
				"sqlSelect"=>$this->buildUpdateQuerySelect($jsonInput)
			); 
		}
		return $result; 
	}
	
	function buildFieldInsert() {
		$result = array(); 
		$jsonInput = json_decode(stripslashes($this->dataInput)); 
		if ($this->isArrayInput) {
			foreach ($jsonInput as $row) 
				$result[] =	Array(
					"sqlInsert"=>$this->buildInsertQuery($row),
					"sqlSelect"=>$this->getLastRecordSql($row)
				);
		} else {
			$result[] =	Array(
				"sqlInsert"=>$this->buildInsertQuery($jsonInput),
				"sqlSelect"=>$this->getLastRecordSql($jsonInput)
			);
		}
		return $result; 
	}
	
	/**
	 * Delete Operation on Table Master
	 *
	 * @param json string $dataInput
	 * @return json string
	 */
	function doDestroy($dataInput) {
		$this->dataInput = $dataInput; 
		$this->setIsArrayInput(); 
		$this->setKeyfield(); 
		$result = $this->executeDestroy();
		return $result; 
	}
	function executeDestroy() {
		if ($this->isArrayInput){
			$tmp = json_decode(stripslashes($this->dataInput)); 
			$id_delete = implode(",",$tmp); 
		} else {
			$id_delete = stripslashes($this->dataInput);
			if ($this->CI->db->dbdriver == 'odbc') {
				$id_delete = str_replace('"','\'', $id_delete);			
			}
		}
		$sqlStr = 'delete from '.$this->master_table .' where '.$this->keyfield .' in('.$id_delete.')';
		$this->setSQL($sqlStr); 
		$this->CI->db->query($sqlStr);
		
		$result = array(); 
		if ($this->CI->db->_error_message() ==""){
			$result['success'] = true; 
			$result['data'] = array(); 
			$result['total'] =0; 
			$result['message']['note'] = "Data has been delete"; 
		} else {
			$result['success']=false; 
			$result['data'] = array(); 
			$result['total'] = 0; 
			$result['message']['note'] = $this->CI->db->_error_message(); 
		}
		
		return json_encode($result); 
		
	}
	
	function buildMetaData(){
		$meta_data = new stdClass(); 
		$meta_data->successProperty = "success"; 
		$meta_data->totalProperty = "total"; 
		$meta_data->root = "data"; 
		$meta_data->fields = array(); 
		$meta_data->colModel= array();
		$meta_data->filterList=array();  
		  
		foreach($this->fields as $field){
			if (isset($field['meta'])){	    
				if (isset($field['primary']))
				$meta_data->idProperty = $field['name'];           	    
						  
				$meta = $field['meta']; 
				/**pengaturan store **/
				$meta['st']['name'] = $field['name']; 
				$meta_data->fields[] = $meta['st']; 

				/**pengaturan colummodel **/
				$meta['cm']['dataIndex'] = $field['name']; 

				/**pengaturan editor **/
				if (isset($meta['editor']))
				$meta['cm']['editor'] = $meta['editor'];	      
				$meta_data->colModel[] = $meta['cm']; 
					  
				/**pengaturan filter **/ 
				if (isset($meta['filter'])){
					$meta['filter']['dataIndex'] = $field['name']; 
					$meta_data->filterList[]= $meta['filter']; 
				}	      	          
			}	    
		}	  
    
		if (!$meta_data->fields){
	   		return '';  
	 	} else
	 		return $meta_data; 
	}
    
	
	function formatDate($date){
	  if ($date){
    	  $arr = explode('/',$date); 
    	  $arr = array_reverse($arr,1);
    	  return implode('-',$arr);
	  }else{
	     return ""; 
	  }
	}
	
	public function getLastID(){
		if ($this->CI->db->dbdriver == 'odbc') {
			$sql = "SELECT MAX(".$this->keyfield.") AS LastID FROM ".$this->master_table;
			$query = $this->CI->db->query($sql);
			$row = $query->result();
			if ($row !== false && count($row) > 0) {
				return $row[0]->LastID;
			}
		} else {
	    	return $this->CI->db->Insert_ID();
		}
	}
}
