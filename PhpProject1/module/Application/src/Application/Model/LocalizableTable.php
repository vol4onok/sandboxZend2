<?php
namespace Application\Model;

abstract class LocalizableTable extends AppTable {  
	/**
	 * returns row from db with specified slug
	 *
	 * @param string $name
	 * @param int $lang
	 * @return \ArrayObject
	 */
	public function getByNameWithLang($name) {
		$key = base64_encode($name).'.'.$this->lang;
		$item = $this->cacheGet($key);
		if(!$item) {
			$rows = $this->find(array(
				array('name', '=', $name),
				), 1, 0);
			$item = array_pop($rows);
			if($item)
				$this->cacheSet($key, $item);
		}

		return $item;
	}

	/**
	 * returns row from db with specified id
	 *
	 * @param int $id
	 * @return \ArrayObject
	 */
	public function getUncached($id) {
		$item = parent::getUncached($id);
		$item->localData = $this->getLocalData(['id' => $id]);
		return $item;
	}

	public function get($id) {
		$item = $this->cacheGet($id.'.'.$this->lang);
		if(!$item) {
			$item = parent::get($id);
			if(isset($item->localData) && isset($item->localData[$this->lang])) {
				$item2 = array_merge((array)$item, (array)$item->localData[$this->lang]);
				unset($item2['localData']);
				$item = new \ArrayObject($item2, \ArrayObject::ARRAY_AS_PROPS);
			}
			$this->cacheSet($id.'.'.$this->lang, $item);
		}

		return $item;
	}

	/**
	 * returns rows from db with specified id
	 *
	 * @param array $ids
	 * @return \ArrayObject
	 */
	public function mget($ids) {
		$keys = [];
		foreach($ids as $id) {
			$keys[]='table.'.$this->table.'.'.$id.'.'.$this->lang;
		}
		$values = self::$redis->mget($keys);
		$result = [];
		foreach($ids as $num => $id) {
			if(isset($values[$num]) && !empty($values[$num])) {
				$result[$id] = $values[$num];
			}
			else {
				$result[$id] = $this->get($id);
			}
		}

		return $result;
	}

	/**
	 * deletes cached value and all its local values
	 *
	 * @param string $key
	 */
	public function cacheDelete($key) {
		return parent::cacheDeleteByMask($key);
	}

	/**
	 * get local data for localized items
	 * 
	 * @param array $params
	 * @param int limit
	 * @param int $offset
	 */
	public function getLocalData($params=[], $limit = null, $offset = 0) {
		$select = new \Zend\Db\Sql\Select;
		$select->columns(array('*'));
		$select->from(array('cl'=>$this->locTable));
		if($this->id) {
			$select->where(['id' => $this->id]);
		}

		//include all where settings
		if (isset($params) && is_array($params)){
			$select->where($params);
		}
		//set user's limit if it's nessesary
		if (isset($limit)){
			$select->limit($limit);

			//set user's offset if it's nessesary
			if (isset($offset)){
				$select->offset($offset);
			}
		}

		$results = $this->execute($select);
		$res = [];
		foreach($results as $result) {
			$res[$result->lang] = $result;
		}
		return $res;
	}

	/**
	 * returns row from db with specified id and localData
	 * in apropriate structure to fill form
	 *
	 * @param int $id
	 * @return \ArrayObject
	 */
	public function getFullLocalData($id) {
		$row = $this->getUncached($id);
		//get translations
		foreach($row->localData as $locItem){
			foreach ( $this->localFields as $field){
				if (!isset($row->{$field}) || !is_array($row->{$field})){
					//echo 'array';
					$row->{$field} = array();
				}
				//var_dump($row->{$field});
				$row->{$field}[$locItem->lang] = $locItem->$field;
			}
		}
		return $row;
	}

	/**
	 * sets data for current id
	 *
	 * @param array $data
	 */
	public function set($data) {
		$this->updateLocData($data);
		return parent::set($data);
	}

	/**
	 * update or insert local data for localized items
	 * 
	 * @param mixed $data
	 */
	public function updateLocData($data) {
		$updateData = [];
		foreach ($data as $field => $values) {
			if(in_array($field, $this->localFields) && is_array($values)) {
				foreach($values as $lang => $value) {
					$updateData[$lang][$field] = $value;
				}
			}
		}

		foreach($updateData as $lang => $data) {
			$tValues = []; $fields = [];
			$data['id'] = $this->id;
			$data['lang'] = $lang;
			foreach($data as $key => $value) {
				$tValues []= ':'.$key;
				$fields []= $key;
			}
			$this->query('replace into `'.$this->locTable.'` ('.implode(', ', $fields).') values ('.implode(', ', $tValues).')', $data);
		}

		$this->cacheDelete($this->id);
	}


	/**
	 * Inserts a record
	 *
	 * @param array $set
	 * @return int last insert Id
	 */
	public function insert($set) {
		$id = parent::insert($set);
		$this->setId($id);
		$this->updateLocData($set);

		return $id;
	}

}
