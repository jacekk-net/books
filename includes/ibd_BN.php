<?php
class ibd_BN implements ibd_module {
	public $url = 'http://data.bn.org.pl/api/bibs.json';
	public $limit = 20;
	
	function szukaj_info($tytul=NULL, $autor=NULL, $wydawnictwo=NULL) {
		$params = [];
		if(!empty($tytul)) {
			$params['title'] = $tytul;
		}
		if(!empty($autor)) {
			$params['author'] = $author;
		}
		if(!empty($wydawnictwo)) {
			$params['publisher'] = $wydawnictwo;
		}
		return $this->query($params);
	}
	
	function szukaj_ISBN($ISBN) {
		$result = $this->query(array(
			'isbnIssn' => $ISBN,
		));
		if(substr($ISBN, 0, 3) == '978') {
			$result = array_merge(
				$result,
				$this->query(array(
					'isbnIssn' => convert::ISBN13_to_ISBN10( $ISBN ),
				))
			);
		}
		return $result;
	}
	
	function szukaj_ISSN($ISSN) {
		$result = $this->query(array(
			'isbnIssn' => $ISSN,
		));
		if(substr($ISSN, 0, 3) == '977') {
			$result = array_merge(
				$result,
				$this->query(array(
					'isbnIssn' => convert::ISSN13_to_ISSN8( $ISSN )
				))
			);
		}
		return $result;
	}
	
	protected function query($params) {
		$params['limit'] = $limit;
		$result = file_get_contents($this->url . '?' . http_build_query($params));
		$result = json_decode($result, TRUE);
		return $this->extractArrays($result);
		
	}
	
	protected function convertSubfields($values) {
		$result = array();
		if(!isset($values['subfields'])) {
			return array();
		}
		if(isset($values['ind1'])) {
			$result['f0'] = $values['ind1'];
		}
		if(isset($values['ind2'])) {
			$result['f1'] = $values['ind2'];
		}
		foreach($values['subfields'] as $subfield) {
			foreach($subfield as $name => $value) {
				$result[$name] = $value;
			}
		}
		return $result;
	}
	
	protected function convert($entry) {
		$marc = array();
		if(!isset($entry['marc'])) return NULL;
		if(!isset($entry['marc']['fields'])) return NULL;
		foreach($entry['marc']['fields'] as $fields) {
			foreach($fields as $field => $values) {
				if(!isset($marc[$field])) {
					$marc[$field] = array();
				}
				$marc[$field][] = $this->convertSubfields($values);
			}
		}
		return $marc;
	}
	
	protected function extractArrays($result) {
		if(!$result) return array();
		if(!$result['bibs']) return array();
		
		$return = array();
		foreach($result['bibs'] as $bib) {
			$marc = $this->convert($bib);
			$return[] = MARC21::to_array($marc);
		}
		return $return;
	}
}
