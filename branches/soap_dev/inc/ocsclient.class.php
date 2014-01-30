<?php

/**
 * Use an abstract class because GLPI is unable to autoload interfaces
 */
abstract class PluginOcsinventoryngOcsClient {
	const CHECKSUM_NONE                 = 0x00000;
	const CHECKSUM_HARDWARE             = 0x00001;
	const CHECKSUM_BIOS                 = 0x00002;
	const CHECKSUM_MEMORY_SLOTS         = 0x00004;
	const CHECKSUM_SYSTEM_SLOTS         = 0x00008;
	const CHECKSUM_REGISTRY             = 0x00010;
	const CHECKSUM_SYSTEM_CONTROLLERS   = 0x00020;
	const CHECKSUM_MONITORS             = 0x00040;
	const CHECKSUM_SYSTEM_PORTS         = 0x00080;
	const CHECKSUM_STORAGE_PERIPHERALS  = 0x00100;
	const CHECKSUM_LOGICAL_DRIVES       = 0x00200;
	const CHECKSUM_INPUT_DEVICES        = 0x00400;
	const CHECKSUM_MODEMS               = 0x00800;
	const CHECKSUM_NETWORK_ADAPTERS     = 0x01000;
	const CHECKSUM_PRINTERS             = 0x02000;
	const CHECKSUM_SOUND_ADAPTERS       = 0x04000;
	const CHECKSUM_VIDEO_ADAPTERS       = 0x08000;
	const CHECKSUM_SOFTWARE             = 0x10000;
	const CHECKSUM_ALL                  = 0x1FFFF;
	
	const WANTED_NONE           = 0x00000;
	const WANTED_ACCOUNTINFO    = 0x00001;
	const WANTED_DICO_SOFT      = 0x00002;
	const WANTED_ALL            = 0x00003;
	
	private $id;
	
	public function __construct($id) {
		$this->id = $id;
	}

	/**********************/
	/* ABSTRACT FUNCTIONS */
	/**********************/

	/**
	 * Return true if connection was successful, false otherwise
	 *
	 * @return boolean
	 */
	abstract public function checkConnection();

	/**
	 * Returns a list of computers for a given filter
	 *
	 * @param string $field The field to filter computers
	 * @param mixed $value The value to filter computers
	 * @return array List of computers :
	 * 		array (
	 * 			array (
	 * 				'ID' => ...
	 * 				'CHECKSUM' => ...
	 * 				'DEVICEID' => ...
	 * 				'LASTCOME' => ...
	 * 				'LASTDATE' => ...
	 * 				'NAME' => ...
	 * 				'TAG' => ...
	 * 			),
	 * 			...
	 * 		)
	 */
	abstract public function searchComputers($field, $value);

	/**
	 * Returns a list of computers
	 *
	 * @param array $options Possible options :
	 * 		array(
	 * 			'OFFSET' => int,
	 * 			'MAX_RECORDS' => int,
	 * 			'FILTER' => array(						// filter the computers to return
	 * 				'IDS' => array(int),				// list of computer ids to select
	 * 				'EXCLUDE_IDS' => array(int),		// list of computer ids to exclude
	 * 				'TAGS' => array(string),			// list of computer tags to select
	 * 				'EXCLUDE_TAGS' => array(string),	// list of computer tags to exclude
	 * 				'CHECKSUM' => int					// filter which sections have been modified (see CHECKSUM_* constants)
	 * 			),
	 * 			'DISPLAY' => array(		// select which sections of the computers to return
	 * 				'CHECKSUM' => int,	// inventory sections to return (see CHECKSUM_* constants)
	 * 				'WANTED' => int		// special sections to return (see WANTED_* constants)
	 * 			)
	 * 		)
	 * 
	 * @return array List of computers :
	 * 		array (
	 * 			array (
	 * 				'META' => array(
	 * 					'ID' => ...
	 * 					'CHECKSUM' => ...
	 * 					'DEVICEID' => ...
	 * 					'LASTCOME' => ...
	 * 					'LASTDATE' => ...
	 * 					'NAME' => ...
	 * 					'TAG' => ...
	 * 				),
	 * 				'SECTION1' => array(
	 * 					array(...),   // Section element 1
	 * 					array(...),   // Section element 2
	 * 					...
	 * 				),
	 * 				'SECTION2' => array(...),
	 * 				...
	 * 			),
	 * 			...
	 * 		)
	 */
	abstract public function getComputers($options);

	/**
	 * Return the account infos for the given id
	 * 
	 * @param int $id The id 
	 * @return array Account Infos :
	 *		array(
	 *			HARDWARE_ID' => ...
 	 *		   'TAG' => ...
  	 *		 	... => ... 
 	 *	 	)
	 *	
	 */
	abstract public function getAccountInfo($id);

	/**
	 * Returns the config for the given key
	 *
	 * @param string $key The name of the config item to return
	 * @return mixed The config value :
	 * 		array (
	 * 			'IVALUE' => integer value,
	 * 			'TVALUE' => text value
	 * 		)
	 */
	abstract public function getConfig($key);

	/**
	 * Sets the config for the given key
	 *
	 * @param string $key The name of the config item to change
	 * @param int $ivalue The integer value of the config
	 * @param string $tvalue The text value of the config
	 * @return void
	 */
	abstract public function setConfig($key, $ivalue, $tvalue);

	abstract public function getCategorie($table, $condition = 1, $sort);

	abstract public function getUnique($columns, $table, $conditions, $sort);

	/**
	 * Sets the checksum for the given computer
	 * 
	 * @param int $checksum The checksum value 
	 * @param int $id The computer id
	 * @return void
	 */
	abstract public function setChecksum($checksum, $id);
	
	/**
	 * Gets the checksum for the given computer
	 * 
	 * @param int $id The computer id
	 * @return int The checksum
	 */
	abstract public function getChecksum($id);
	
	/**
	 * Get the computer that were deleted (or merged) in ocsinventory
	 * 
	 * @return array The list of deleted computers : (DELETED contains the id or deviceid of the computer and equivalent and EQUIV contains the new id if the computer was marged)
	 * 		array (
	 * 			'DELETED' => 'EQUIV'
	 * 		)
	 */
	abstract public function getDeletedComputers();

	abstract public function removeDeletedComputers($deleted, $equivclean = null);
	
	/**
	 * Get the account infox columns
	 * 
	 * @return array 
	 * 		array (
	 * 			'0' => 'HARDWARE_ID',
	 *			'1' => 'TAG',	
	 *			'2' => ...
	 * 		)
	 */
	abstract public function getAccountInfoColumns();

	/*************************/
	/* IMPLEMENTED FUNCTIONS */
	/*************************/
	
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns the integer config for the given key
	 *
	 * @see PluginOcsinventoryngOcsClient::getConfig()
	 * @param string $key The name of the config item to return
	 * @return integer
	 */
	public function getIntConfig($key) {
		$config = $this->getConfig($key);
		return $config['IVALUE'];
	}
	
	/**
	 * Returns the text config for the given key
	 *
	 * @see PluginOcsinventoryngOcsClient::getConfig()
	 * @param string $key The name of the config item to return
	 * @return string
	 */
	public function getTextConfig($key) {
		$config = $this->getConfig($key);
		return $config['TVALUE'];
	}
}

?>

