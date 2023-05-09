<?php

class Compressor
{	
	const VERSION = "2.215";
	const VERSION_COMPATIBLE = "2.213"; // version of min compatible Pivot Component

	/**
	 * Compress MySql result
	 * @param input - MySql result
	 * @return boolean
	 */
	public static function compressMySql($input){
		$reader = new MySqlReader();
		return $reader->process($input);
	}

	/**
	 * Compress MS SQL Server result
	 * @param input - MS SQL Server result
	 * @return boolean
	 */
	public static function compressSQLSRV($input){
		$reader = new SQLSRVReader();
		return $reader->process($input);
	}

	/**
	 * Compress PostgreSQL result
	 * @param input - PostgreSQL result
	 * @return boolean
	 */
	public static function compressPostgreSQL($input){
		$reader = new PostgreSQLReader();
		return $reader->process($input);
	}
		
	/**
	 * Compress Oracle OCI result
	 * @param input - Oracle OCI result
	 * @return boolean
	 */
	public static function compressOCI($input){
		$reader = new OCI8Reader();
		return $reader->process($input);
	}

	/**
	 * Compress PDO result
	 * @param input - PDOStatement object
	 * @return boolean
	 */
	public static function compressPDO($input){
		$reader = new PDOReader();
		return $reader->process($input);
	}

	/**
	 * Compress array of data
	 * @param input - array of data
	 * @return boolean
	 */
	public static function compressArray($input) {
		$reader = new ArrayReader();
		return $reader->process($input);
	}

	/**
	 * Compress CSV file with specified path
	 * @param filePath - path to file in CSV format
	 * @param delimiter - character used to separate the values. By default is comma or colon
	 * @return boolean
	 */
	public static function compressFile($filePath, $delimiter = "") {
		$reader = new FileReader();
		$reader->delimiter = $delimiter;
		return $reader->process($filePath);
	}

	/**
	 * Compress string data in CSV format
	 * @param input - string data in CSV format
	 * @param delimiter - character used to separate the values. By default is comma or colon
	 * @param recordsetDelimiter - character used to separate the rows. By default is caret return
	 * @return boolean
	 */
	public static function compressString($input, $delimiter = "", $recordsetDelimiter = "\n") {
		$input = explode($recordsetDelimiter, $input);
		return Compressor::compressArray($input, $delimiter);
	}
}

/* Backward compatibility */
class CSVCompressor {
	public function compressFile($filePath, $delimiter = "") {
		return Compressor::compressFile($filePath, $delimiter);
	}
	public function compressDataString($input, $delimiter = "") {
		return Compressor::compressString($input, $delimiter);
	}
	public function compressDataMySql($input) {
		return Compressor::compressMySql($input);
	}
	// deprecated
	public function setMonthLabels($month) { }
	public function setQuarterLabels($quarter) { }
	public function compressDataStringArray($input, $delimiter = "") { }
}

abstract class BaseReader {
	protected $FIELD_DELIMITER = ",";
	protected $RECORDSET_DELIMITER = "\n";
	protected $header;
	private $_headerLength;

	protected function addColumn($caption, $type) {
		$this->header[] = array(
			"caption" => $caption,
			"type" => $type,
			"members" => array()
		);
		$this->_headerLength = count($this->header);
	}

	protected function composeHeader() {
		$columns = array();
		for ($i = 0; $i < count($this->header); $i++){
			$column = $this->header[$i];
			$columns[$i] = $this->getColumnPrefix($column["type"]).$this->encodeChars($column["caption"]);
		}
		echo "___ocsv2___".Compressor::VERSION."/".Compressor::VERSION_COMPATIBLE."\n"; // version
		echo join(",", $columns)."\n";
	}

	protected function composeDataRow($values) {
		for ($colIdx = 0; $colIdx < $this->_headerLength; $colIdx++) {
			$value = isset($values[$colIdx]) ? $values[$colIdx] : "";
			$values[$colIdx] = $this->addMember($value, $colIdx);
		}
		echo join(",", $values)."\n";
	}

	protected function addMember($value, $colIdx) {
		$type = $this->header[$colIdx]["type"];
		if ($type == ColumnType::FACT) {
			return $value;
		} elseif ($type == ColumnType::STRING
				|| $type == ColumnType::WEEKDAY
				|| $type == ColumnType::MONTH) {
			if (isset($this->header[$colIdx]["members"][strtolower($value)])) {
				return $this->header[$colIdx]["members"][strtolower($value)];
			} else {
				$this->header[$colIdx]["members"][strtolower($value)] = "^".count($this->header[$colIdx]["members"]);
				return $this->encodeChars($value);
			}
		} elseif ( $type == ColumnType::DATE
				|| $type == ColumnType::DATE_STRING 
				|| $type == ColumnType::DATE_WITH_MONTHS
				|| $type == ColumnType::DATE_WITH_QUARTERS) {
			if ($value == "") return $value;
			$date = ($value instanceof DateTime) ? $value : new DateTime($value, new DateTimeZone('UTC'));
			return $date->getTimestamp();

		}
		return $value;
	}

	protected $chars = array(",", "\"", "\n");
	protected $chars_encoded = array("%d%", "%q%", "%n%");
	protected function encodeChars($input) {
		return str_replace($this->chars, $this->chars_encoded, $input);
	}

	protected function getColumnType($column, $value = ""){
		if (strpos($column,":")) {
		 	return ColumnType::LEVELS;
		} else if (substr($column, 0, 1) == "+") {
			return ColumnType::STRING;
		} else if (substr($column, 0, 2) == "d+") {
			return ColumnType::DATE;
		} else if (substr($column, 0, 2) == "D+") {
			return ColumnType::DATE_WITH_MONTHS;
		} else if (substr($column, 0, 3) == "D4+") {				
			return ColumnType::DATE_WITH_QUARTERS;
		} else if (substr($column, 0, 3) == "ds+") {
			return ColumnType::DATE_STRING;
		} else if (substr($column, 0, 1) == "-") {
			return ColumnType::FACT;
		} else if (substr($column, 0, 2) == "t+") {
			return ColumnType::TIME;
		} else if (substr($column, 0, 3) == "dt+") {
			return ColumnType::DATETIME;
		} else if (substr($column, 0, 2) == "m+") {
			return ColumnType::MONTH;
		} else if (substr($column, 0, 2) == "w+") {
			return ColumnType::WEEKDAY;
		} else if (substr($column, 0, 2) == "n+") {
			return ColumnType::FACT;
		} else if (CompressorUtils::isNan($value)) {
			$date_val = strtotime($value);
			if (strtotime($value)) {
				return ColumnType::DATE;
			} else {
				return ColumnType::STRING;
			}				
		} else if ($value == "") {
			return ColumnType::STRING;
		}
		return ColumnType::FACT;
	}
	
	protected function getColumnCaption($column) {	
		if (substr($column, 0, 1) == "+") {
			return substr($column, 1 , strlen($column));
		} else if (substr($column, 0, 2) == "d+") {
			return substr($column, 2 , strlen($column));
		} else if (substr($column, 0, 2) == "D+") {
			return substr($column, 2 , strlen($column));
		} else if (substr($column, 0, 3) == "D4+") {				
			return substr($column, 3 , strlen($column));
		} else if (substr($column, 0, 3) == "ds+") {
			return substr($column, 3 , strlen($column));
		} else if (substr($column, 0, 1) == "-") {
			return substr($column, 1 , strlen($column));
		} else if (substr($column, 0, 2) == "t+") {
			return substr($column, 2 , strlen($column));
		} else if (substr($column, 0, 3) == "dt+") {
			return substr($column, 3 , strlen($column));
		} else if (substr($column, 0, 2) == "m+") {
			return substr($column, 2 , strlen($column));
		} else if (substr($column, 0, 2) == "w+") {
			return substr($column, 2 , strlen($column));
		} else if (substr($column, 0, 2) == "n+") {
			return substr($column, 2 , strlen($column));
		}
		return $column;
	}

	protected function getColumnPrefix($type) {
		if ($type == ColumnType::FACT) {
			return "-";
		} elseif ($type == ColumnType::STRING) {
			return "+";
		} elseif ($type == ColumnType::DATE) {
			return "d+";
		} elseif ($type == ColumnType::DATE_WITH_MONTHS) {
			return "D+";
		} elseif ($type == ColumnType::DATE_WITH_QUARTERS) {
			return "D4+";
		} elseif ($type == ColumnType::DATE_STRING) {
			return "ds+";
		} elseif ($type == ColumnType::WEEKDAY) {
			return "w+";
		} elseif ($type == ColumnType::MONTH) {
			return "m+";
		} elseif ($type == ColumnType::TIME) {
			return "t+";
		} elseif ($type == ColumnType::DATETIME) {
			return "dt+";
		} elseif ($type == ColumnType::LEVELS) {
			return "+";
		} 
		return "";
	}
}

abstract class DBReader extends BaseReader {
	public function process($input) {
		try {
			$this->parseHeader($input);
			$this->parseDataRows($input);
		} catch (Exception $e) {
		    return false;
		}
		return true;
	}

	protected function parseHeader($input) {
		$fields = $this->getNumFields($input);
		for ($i = 0; $i < $fields; $i++) {
	      	$name = $this->getFieldName($input, $i);
	    	$type = $this->getDbColumnType($this->getFieldType($input, $i));
	      	$caption = $this->getColumnCaption($name);
			$type = ($caption == $name) ? $type : $this->getColumnType($name);
			$this->addColumn($caption, $type);
	    }
	    $this->composeHeader();
	}

	protected function parseDataRows($input) {
		while ($values = $this->fetchRow($input)) {
			$this->composeDataRow($values);
		}
	}

	protected function getDbColumnType($value) {
		$value = strtolower($value);
		if ($value == "tinyint" ||
			$value == "smallint" ||
			$value == "mediumint" ||
			$value == "int" ||
			$value == "bigint" ||
			$value == "float" ||
			$value == "double" ||
			$value == "decimal" || 
			$value == "real" ||
			$value == "short" ||
			$value == "long" || 
			$value == "longlong") {
			return ColumnType::FACT;
		} else if ($value == "string") {
			return ColumnType::STRING;
		} else if ($value == "date" || 
			$value == "datetime" ||
			$value == "timestamp" ) {
			return ColumnType::DATE;
		} else if ($value == "time") {
			return ColumnType::TIME;
		} 
		return ColumnType::STRING;
	}

	abstract protected function getNumFields($input);
	abstract protected function getFieldName($input, $colIdx);
	abstract protected function getFieldType($input, $colIdx);
	abstract protected function fetchRow($input);
}

abstract class CSVReader extends BaseReader {
	private $FIELD_ENCLOSURE_TOKEN = "\"";
	private $headerRow;

	public $delimiter;

    public function processRow($row) {
    	if (!isset($this->headerRow)) {
    		$this->headerRow = $row;
    	} elseif (isset($this->headerRow) && !isset($this->header)) {
    		$this->processHeaderRow($this->headerRow, $row);
    		$this->processDataRow($row);
    	} else {
    		$this->processDataRow($row);
    	}
    }

	private function processHeaderRow($headerStr, $rowStr) {
		$this->header = array();
		$this->delimiter = $this->chooseSeparator($headerStr);
		$pattern = "/^\s+|\s+$/";
		$headerItems = $this->splitRow($headerStr);
		$rowItems = $this->splitRow($rowStr);
		for ($i = 0; $i < count($headerItems); $i++){
			$column = preg_replace($pattern, "", $headerItems[$i]);
			$type = $this->getColumnType($column, $rowItems[$i]);
			$caption = $this->getColumnCaption($column);
			$this->addColumn($caption, $type);	
		}
		$this->composeHeader();
	}

	private function processDataRow($rowStr) {
		$rowStr = trim($rowStr);
		if (strlen($rowStr) <= 0){
			return;	
		}
		$values = $this->splitRow($rowStr);
		$this->composeDataRow($values);
	}

	private function chooseSeparator($row){
		if ($this->delimiter && strlen($this->delimiter) > 0) {
			return $this->delimiter;
		}
		$commas = count(explode(",", $row));
		$semicoloms = count(explode(";", $row));
		return ($commas > $semicoloms) ? "," : ";";
	}

	private function splitRow($rowStr){
		if (!isset($rowStr)) return array();
		$pattern = "/^\s+|\s+$/";
		$value = "";
		$quoted = false;
		$prevQuote = false;
		$parsed = array();
		$rowStrlength = strlen($rowStr);
		for ($i = 0; $i < $rowStrlength; $i++){
			$char = $rowStr[$i];
			if (($i == $rowStrlength - 1) || ($char == $this->delimiter && !$quoted)){
				if ($i == $rowStrlength - 1) $value = $value.$char;
				$value = preg_replace($pattern, "", $value);
				if (strlen($value) > 0 && $value[0] == $this->FIELD_ENCLOSURE_TOKEN && $value[strlen($value) - 1] == $this->FIELD_ENCLOSURE_TOKEN){
					$value = substr($value, 1, strlen($value) - 2);
				}
				$prevQuote = false;
				$quoted = false;
				$parsed[count($parsed)] = $value;
				$value = "";
			} else {
				if ($char == $this->FIELD_ENCLOSURE_TOKEN){
					if (!$prevQuote) $value = $value.$char;
					$quoted = !$quoted;
					$prevQuote = true;
				} else {
					$value = $value.$char;
					$prevQuote = false;
				}
			}
		}
		return $parsed;
	}
}

class FileReader extends CSVReader {
	public function process($filePath, $delimiter = "") {
		try {
		    $file = fopen($filePath, "r");
			while (!feof($file)) {
				$this->processRow(fgets($file));
			}	
			fclose($file);
		} catch (Exception $e) {
		    return false;
		}
		return true;
	}
}

class MySqlReader extends DBReader {
	protected function getNumFields($input) {
		return mysql_num_fields($input);
	}
	protected function getFieldName($input, $colIdx) {
		return mysql_field_name($input, $colIdx);
	}
	protected function getFieldType($input, $colIdx) {
		return mysql_field_type($input, $colIdx);
	}
	protected function fetchRow($input) {
		return mysql_fetch_row($input);
	}
}

class SQLSRVReader extends DBReader {
	protected function getNumFields($input) {
		return sqlsrv_num_fields($input);
	}
	protected function getFieldName($input, $colIdx) {
		$meta = sqlsrv_field_metadata($input);
		return $meta[$colIdx]["Name"];
	}
	protected function getFieldType($input, $colIdx) {
		$meta = sqlsrv_field_metadata($input);
		$type = $meta[$colIdx]["Type"];
		if ($type == -5) return "bigint";
		if ($type == -2) return "binary";
		if ($type == -7) return "bit";
		if ($type == 1) return "char";
		if ($type == 91) return "date";
		if ($type == 93) return "datetime";
		if ($type == -155) return "datetimeoffset";
		if ($type == 3) return "decimal";
		if ($type == 6) return "float";
		if ($type == 4) return "int";
		if ($type == -8) return "nchar";
		if ($type == -10) return "ntext";
		if ($type == 2) return "numeric";
		if ($type == -9) return "nvarchar";
		if ($type == 7) return "real";
		if ($type == 5) return "smallint";
		if ($type == -1) return "text";
		if ($type == -154) return "time";
		if ($type == -6) return "tinyint";
		return "varchar";
	}
	protected function fetchRow($input) {
		return sqlsrv_fetch_array($input, SQLSRV_FETCH_NUMERIC);
	}
}

class PostgreSQLReader extends DBReader {
	protected function getNumFields($input) {
		return pg_num_fields($input);
	}
	protected function getFieldName($input, $colIdx) {
		return pg_field_name($input, $colIdx);
	}
	protected function getFieldType($input, $colIdx) {
		return pg_field_type($input, $colIdx);
	}
	private $rowIdx = 0;
	private $numRows = 0;
	protected function fetchRow($input) {
		if ($this->rowIdx == 0) {
			$this->numRows = pg_num_rows($input); 
		}
		return $this->rowIdx < $this->numRows ? pg_fetch_row($input, $this->rowIdx++) : null;
	}
}

class OCI8Reader extends DBReader {
	protected function getNumFields($input) {
		return oci_num_fields($input);
	}
	protected function getFieldName($input, $colIdx) {
		return oci_field_name($input, $colIdx);
	}
	protected function getFieldType($input, $colIdx) {
		return oci_field_type($input, $colIdx);
	}
	protected function fetchRow($input) {
		return oci_fetch_row($input);
	}
}

class PDOReader extends DBReader {
	protected function getNumFields($input) {
		return $input->columnCount();
	}
	protected function getFieldName($input, $colIdx) {
		$meta = $input->getColumnMeta($colIdx);
		return $meta["name"];
	}
	protected function getFieldType($input, $colIdx) {
		$meta = $input->getColumnMeta($colIdx);
		return $meta["native_type"];
	}
	protected function fetchRow($input) {
		return $input->fetch(PDO::FETCH_NUM);
	}
}

class ArrayReader extends DBReader {
	public function process($input) {
		try {
			$length = count($input);
			$this->parseHeader($input[0]);
			for ($i=1; $i < $length; $i++) { 
				$this->composeDataRow($input[$i]);
			}
		} catch (Exception $e) {
		    return false;
		}
		return true;
	}

	protected function parseHeader($input) {
		foreach ($input as $field) {
			$this->addColumn($field["name"], $this->getDbColumnType($field["type"]));
	    }
	    $this->composeHeader();
	}

	protected function getNumFields($input) { }
	protected function getFieldName($input, $colIdx) { }
	protected function getFieldType($input, $colIdx) { }
	protected function fetchRow($input) { }
}

abstract class ColumnType
{
	const FACT = 0;					// fact
	const STRING = 1;				// string
	const DATE = 2;					// imple date of 3 (Year|Month|Day)
	const LEVELS = 4;				// levels
	const DATE_WITH_MONTHS = 6;		// hierarchical date of 3 (Year|Month|Day)
	const MONTH = 10;				// month
	const DATE_WITH_QUARTERS = 11;	// hierarchical date of 4 (Year|Quarter|Month|Day)
	const TIME = 13;				// time measure (hh:mm:ss)
	const WEEKDAY = 14;				// weekday
	const DATETIME = 15;			// date/time measure (mm/dd/yyyy hh:mm:ss) 
	const DATE_STRING = 16;			// date string (mm/dd/yyyy)
}

class CompressorUtils
{
	public static function isNaN($value) {
		//Don't forget to mention we do not support all the types. Check, what to we need to support.
		$f_val = filter_var($value, FILTER_VALIDATE_FLOAT/*, array(FILTER_FLAG_ALLOW_THOUSAND, )*/);
		if ($f_val === FALSE) {
			return true;
		} else {
			return false;
		}
	}
}

?>
