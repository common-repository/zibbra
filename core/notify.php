<?php

class Zibbra_Plugin_Notify {
	
	const STATUS_OK = "ok";
	const STATUS_WARNING = "warning";
	const STATUS_ERROR = "error";
	const STATUS_INFO = "info";
	
	private $status;
	private $message;
	
	private function __construct($status, $message) {
		
		$this->status = $status;
		$this->message = $message;
		
	} // end function
	
	private function getHash() {
		
		return md5($this->status."|".$this->message);
		
	} // end function
	
	public function getStatus() {
		
		return $this->status;
		
	} // end function
	
	public function getMessage() {
		
		return $this->message;
		
	} // end function
	
	public function confirm() {

		$adapter = ZLibrary::getInstance()->getAdapter();
		$notifications = $adapter->getSessionValue("notifications",array());		
		unset($notifications[$this->getHash()]);
		$adapter->setSessionValue("notifications",$notifications);
		
	} // end function

	private function toJSON() {

		$json = new stdClass();
		$json->status = $this->status;
		$json->message = $this->message;

		return json_encode($json);

	} // end function

	private static function fromJSON($json) {

		$json = json_decode($json);

		$notification = new Zibbra_Plugin_Notify($json->status, $json->message);

		return $notification;

	} // end function

	/**
	 * @param $status
	 * @param $message
	 * @return boolean
	 */
	public static function register($status, $message) {
		
		if(!in_array($status,array(self::STATUS_OK, self::STATUS_WARNING, self::STATUS_ERROR, self::STATUS_INFO))) {
			
			return false;
			
		} // end if

		$adapter = ZLibrary::getInstance()->getAdapter();
		$notifications = $adapter->getSessionValue("notifications",array());
		$notification = new Zibbra_Plugin_Notify($status, $message);
		$notifications[$notification->getHash()] = $notification->toJSON();
		$adapter->setSessionValue("notifications", $notifications);

		return true;
		
	} // end function
	
	public static function getNotifications() {

		$adapter = ZLibrary::getInstance()->getAdapter();
		$notifications = array();

		foreach($adapter->getSessionValue("notifications", array()) as $hash=>$json) {

			$notifications[$hash] = Zibbra_Plugin_Notify::fromJSON($json);

		} // end foreach
		
		return $notifications;
		
	} // end function
	
} // end class

?>