<?php
class sql {
	static $db;
	static $queries = 0;
	
	static $PDO;
	
	static function connect() {
	}
	
	static function query($q) {
		if(!self::$PDO) {
			self::$PDO = PDOO::Singleton();
		}
		
		self::$queries++;
		
		return self::$PDO->query($q);
	}
	
	static function fetchonea($q) {
		return $q->fetch(PDO::FETCH_ASSOC);
	}
	
	static function fetchone($q) {
		return $q->fetch(PDO::FETCH_ASSOC);
	}
	
	static function fetch($q) {
		return $q->fetchAll();
	}
	
	static function increment_id() {
		if(!self::$PDO) {
			self::$PDO = PDOO::Singleton();
		}
		
		return self::$PDO->lastInsertId();
	}
	
	static function affected($q = NULL) {
		if($q === NULL) {
			return 'nieznana';
		}
		
		return $q->rowCount();
	}
	
	static function escape($q) {
		if(!self::$PDO) {
			self::$PDO = PDOO::Singleton();
		}
		
		return self::$PDO->quote($q);
	}
	
	static function close() {
		mysql_close(self::$db);
	}
}

class db2 {
	// FALSE - Allows WHERE queries other than "is equal"
	//         LIKE, MATCH ... AGAINST, !=, >, <, IS NULL ...
	static $SAFE_MODE_WHERE = FALSE;
	
	// FALSE - Allows SELECT *
	static $SAFE_MODE_SELECT = FALSE;
	
	// FALSE - Skipping empty fields in INSERTs
	static $SAFE_MODE_INSERT = FALSE;
	
	// FALSE - Empty field is stated as NULL
	static $SAFE_MODE_UPDATE = FALSE;
	
	// FALSE - Allows JOINs
	static $SAFE_MODE_TABLE = FALSE;
	
	// FALSE - Allow `table`.`key` using table.key
	static $SAFE_MODE_KEY = FALSE;
	
	// Do htmlspecialchars() on db2::get results?
	static $ESCAPE_DATA = TRUE;
	
	static $revelance = FALSE;
	
	static function revelance() {
		return (bool)self::$revelance;
	}
	
	static function __combine_insert($keys) {
		foreach($keys as $key => $value) {
			if(empty($key)) {
				continue;
			}
			
			if(!self::$SAFE_MODE_INSERT AND $value==='') {
				continue;
			}
			
			$a[] = '`'.$key.'`';
			$b[] = sql::escape($value);
		}
		
		return '('.implode(', ', $a).') VALUES ('.implode(', ', $b).')';
	}
	
	static function __combine_update($keys) {
		foreach($keys as $key => $value) {
			if(empty($key)) {
				continue;
			}
			
			if(!self::$SAFE_MODE_UPDATE AND $value==='') {
				$value = NULL;
			}
			if(is_null($value)) {
				$a[] = '`'.$key.'`=NULL';
			}
			else
			{
				$a[] = '`'.$key.'`='.sql::escape($value);
			}
		}
		
		return implode(', ', $a);
	}
	
	static function __combine_select($keys, $revelance=FALSE) {
		if(!self::$SAFE_MODE_SELECT && $keys=='*') {
			$a[] =  '*';
		}
		else
		{
			if(!is_array($keys)) {
				$keys = array($keys);
			}
			
			foreach($keys as $v) {
				$a[] = self::__combine_keyn($v);
				if($one) break;
			}
			
		}
		
		if($revelance && self::$revelance) {
			$a[] = self::$revelance;
		}
		
		return implode(', ', $a);
	}
	
	static function __combine_where($keys, $revelance=FALSE) {
		self::$revelance = FALSE;
		
		$implode = ' AND ';
		
		if(!is_array($keys) OR empty($keys)) {
			return '';
		}
		
		if(self::$SAFE_MODE_WHERE) {
			foreach($keys as $key => $value) {
				if(is_null($value)) {
					$a[] = self::__combine_keyn($key).' IS NULL';
				}
				else
				{
					$a[] = self::__combine_keyn($key).'='.sql::escape($value);
				}
			}
		}
		else
		{
			$a = array();
			foreach($keys as $key => $v) {
				if(!is_array($v)) {
					$v = array($v);
				}
				
				foreach($v as $value) {
					if($key === 'OR') {
						$implode = ' OR ';
					}
					elseif(substr($key, -1)=='!' AND is_null($value) OR $value==='') {
						$a[] = self::__combine_keyn(substr($key, 0, -1)).' IS NOT NULL';
					}
					elseif(is_null($value) OR $value==='') {
						$a[] = self::__combine_keyn($key).' IS NULL';
					}
					elseif(substr($key, -1)=='!') {
						$a[] = self::__combine_keyn(substr($key, 0, -1)).'!='.sql::escape($value);
					}
					elseif($key=='^') {
						$a[] = 'MAX('.self::__combine_keyn($value).')';
					}
					elseif(substr($key, -2)=='~~') {
						$temp = 'MATCH ('.self::__combine_keyn(substr($key, 0, -2)).') AGAINST ('.sql::escape($value).')';
						if($revelance) {
							self::$revelance = $temp.' AS `revelance`';
						}
						
						$a[] = $temp;
					}
					elseif(substr($key, -1)=='~') {
						$a[] = self::__combine_keyn(substr($key, 0, -1)).' LIKE '.sql::escape($value);
					}
					elseif(substr($key, -2)=='>=') {
						$a[] = self::__combine_keyn(substr($key, 0, -2)).'>='.sql::escape($value);
					}
					elseif(substr($key, -2)=='<=') {
						$a[] = self::__combine_keyn(substr($key, 0, -2)).'<='.sql::escape($value);
					}
					elseif(substr($key, -1)=='>') {
						$a[] = self::__combine_keyn(substr($key, 0, -1)).'>'.sql::escape($value);
					}
					elseif(substr($key, -1)=='<') {
						$a[] = self::__combine_keyn(substr($key, 0, -1)).'<'.sql::escape($value);
					}
					else
					{
						$a[] = self::__combine_keyn($key).'='.sql::escape($value);
					}
				}
			}
		}
		
		return ' WHERE '.implode($implode, $a).$addon;
	}
	
	static function __combine_limit($limit, $stop) {
		if(!ctype_digit($limit) AND !is_int($limit)) {
			return '';
		}
		elseif(!ctype_digit($stop) AND !is_int($stop)) {
			return ' LIMIT '.$limit;
		}
		else
		{
			return ' LIMIT '.$limit.', '.$stop;
		}
	}
	
	static function __combine_order($keys, $revelance=FALSE) {
		if(empty($keys)) {
			return '';
		}
		
		if(!is_array($keys)) {
			$keys = array($keys => 'ASC');
		}
		
		foreach($keys as $key => $value) {
			$bvalue = strtoupper($value);
			if($bvalue != 'ASC' AND $bvalue != 'DESC') {
				$key = $value;
				$value = 'ASC';
			}
			else
			{
				$value = $bvalue;
			}
			
			if((!$revelance OR !self::$revelance) AND $key=='revelance') {
				continue;
			}
			
			$a[] = self::__combine_keyn($key).' '.$value;
		}
		
		return ' ORDER BY '.implode(', ', $a);
	}
	
	private static function __combine_tablelist($key, $value) {
		$as = FALSE;
		if(!is_int($key)) {
			$as = $value;
			$value = $key;
		}
		return '`'.$value.'`'.($as ? ' AS `'.$as.'`' : '');
	}
	
	static function __combine_keyn($key) {
		if(!self::$SAFE_MODE_KEY AND strpos($key, '.')!==FALSE) {
			$key = explode('.', $key, 2);
			return '`'.$key[0].'`.`'.$key[1].'`';
		}
		
		return '`'.$key.'`';
	}
	
	static function __combine_table($table) {
		if(!is_array($table) OR self::$SAFE_MODE_TABLE) {
			return '`'.$table.'` ';
		}
		else
		{
			$ret = array();
			foreach($table as $key => $value) {
				if(empty($value)) {
					continue;
				}
				
				if(!is_array($value)) {
					$ret[] = self::__combine_tablelist($key, $value);
				}
				else
				{
					switch(strtoupper($value[0])) {
						case 'L':
							$sub = 'LEFT JOIN ';
						break;
						case 'R':
							$sub = 'RIGHT JOIN ';
						break;
						case 'J':
							$sub = 'JOIN ';
						break;
						case 'S':
							$sub = 'STRAIGHT_JOIN ';
						break;
						default:
							continue 2;
						break;
					}
					
					if(!is_array($value[1])) {
						$value[1] = array($value[1]);
					}
					foreach($value[1] as $k => $v) {
						$sub1[] = self::__combine_tablelist($k, $v);
					}
					$sub .= implode(', ', $sub1).' ';
					
					if(strtoupper($value[2])=='USING') {
						$sub .= 'USING ('.self::__combine_select($value[3]).')';
					}
					elseif(strtoupper($value[2])=='ON') {
						$sub .= 'ON '.substr(self::__combine_where($value[3]), 7);
					}
					
					if($value[4]) {
						$sub .= ' HAVING '.self::__combine_where($value[4]);
						
					}
					
					$ret[] = $sub;
				}
			}
			
			return implode(' ', $ret).' ';
		}
	}
	
	static function escape_data($data) {
		if(self::$ESCAPE_DATA) {
			if(is_array($data)) {
				foreach($data as &$value) {
					$value = self::escape_data($value);
				}
			}
			elseif(is_string($data)) {
				$data = htmlspecialchars($data);
			}
		}
		
		return $data;
	}
	
	static function get($table, $keys, $where=NULL, $order=NULL, $limit=NULL, $stop=NULL, $revelance=FALSE) {
		$where = self::__combine_where($where, $revelance);
		$keys = self::__combine_select($keys, $revelance);
		
		$q = sql::query('SELECT '.$keys.' FROM '.self::__combine_table($table).$where.self::__combine_order($order, $revelance).self::__combine_limit($limit, $stop));
		return self::escape_data(sql::fetch($q));
	}
	
	static function getone($table, $keys, $where=NULL, $order=NULL, $limit=NULL, $stop=NULL, $revelance=FALSE) {
		$where = self::__combine_where($where, $revelance);
		$keys = self::__combine_select($keys, $revelance);
		
		$q = sql::query('SELECT '.$keys.' FROM '.self::__combine_table($table).$where.self::__combine_order($order, $revelance).self::__combine_limit($limit, $stop));
		return self::escape_data(sql::fetchone($q));
	}
	
	static function num($table, $key, $where=NULL) {
		$q = sql::query('SELECT COUNT('.self::__combine_select($key, $one).') AS `num` FROM '.self::__combine_table($table).self::__combine_where($where));
		$r = sql::fetchone($q);
		return $r['num'];
	}
	
	static function add($table, $keys) {
		sql::query('INSERT INTO `'.$table.'` '.self::__combine_insert($keys));
		return sql::affected();
	}
	
	static function edit($table, $keys, $where=NULL, $order=NULL, $limit=NULL, $stop=NULL) {
		sql::query('UPDATE `'.$table.'` SET '.self::__combine_update($keys).self::__combine_where($where).self::__combine_order($order).self::__combine_limit($limit, $stop));
		return sql::affected();
	}
	
	static function del($table, $where=NULL, $order=NULL, $limit=NULL, $stop=NULL) {
		sql::query('DELETE FROM `'.$table.'`'.self::__combine_where($where).self::__combine_order($order).self::__combine_limit($limit, $stop));
		return sql::affected();
	}
	
	static function last_id() {
		return sql::increment_id();
	}
}
?>