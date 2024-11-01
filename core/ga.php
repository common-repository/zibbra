<?php

class Zibbra_Plugin_Ga {
	
	private $trackingid;
	private $commands = array();
	private $rendered = false;
	
	const GLOBAL_OBJECT = "zga";
	const DIMENSION1 = "CustomerID";
	
	public function __construct($trackingid) {
		
		$this->trackingid = $trackingid;
		
		$this->registerCommand(new Zibbra_Plugin_Ga_Command_Create($this->trackingid));
		$this->registerCommand(new Zibbra_Plugin_Ga_Command_Set("appName", "Zibbra"));
		$this->registerCommand(new Zibbra_Plugin_Ga_Command_Require("displayfeatures"));		
		$this->registerCommand(new Zibbra_Plugin_Ga_Command_Send("pageview"));
		
	} // end function
	
	public function enableEcommerce() {
				
		$this->registerCommand(new Zibbra_Plugin_Ga_Command_Require("ec"));
		
	} // end function
	
	public function registerCommand(Zibbra_Plugin_Ga_Command $oCommand) {
		
		if($this->rendered) {
			
			throw new Exception("The GA code is already rendered [Zibbra_Plugin_Ga::registerCommand]");
			
		} // end if
		
		if(!isset($this->commands[$oCommand::ORDER])) {
			
			$this->commands[$oCommand::ORDER] = array();
			
		} // end if
		
		$this->commands[$oCommand::ORDER][] = $oCommand;
		
	} // end function
	
	public function replaceCommand(Zibbra_Plugin_Ga_Command $oCommand) {
		
		if($this->rendered) {
			
			throw new Exception("The GA code is already rendered [Zibbra_Plugin_Ga::replaceCommand]");
			
		} // end if
		
		$arrNewCommands = array();
		
		foreach($this->commands as $order=>$arrCommands) {			
		
			foreach($arrCommands as $oRegisteredCommand) {
				
				if(!isset($arrNewCommands[$order])) {
					
					$arrNewCommands[$order] = array();
					
				} // end if
				
				if(get_class($oRegisteredCommand)==get_class($oCommand)) {
					
					$arrNewCommands[$order][] = $oCommand;
					
				}else{
					
					$arrNewCommands[$order][] = $oRegisteredCommand;
					
				} // end if
				
			} // end foreach
			
		} // end foreach
		
		$this->commands = $arrNewCommands;
		
	} // end function
	
	public function registerData(Zibbra_Plugin_Ga_Data $oData) {
		
		if($this->rendered) {
			
			throw new Exception("The GA code is already rendered [Zibbra_Plugin_Ga::registerData]");
			
		} // end if
		
		$oData->setGa($this);
		$oData->registerCommands();
		
	} // end function
	
	public function __toString() {
		
		// General HTML for the GA tracking code
		
		$html = "<!-- Google Analytics -->\n";
		$html .= "<script>\n";
		$html .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){\n";
		$html .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\n";
		$html .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\n";
		$html .= "})(window,document,'script','//www.google-analytics.com/analytics.js','".Zibbra_Plugin_Ga::GLOBAL_OBJECT."');\n";
		$html .= "%s\n";
		$html .= "</script>\n";
		$html .= "<!-- End Google Analytics -->\n";
		
		// Try to identify the customer and user
		
		if(is_customer_logged_in()) {
		
			$adapter = Zibbra_Plugin_Controller::getInstance()->getLibrary()->getAdapter();			
			$oCustomerUser = unserialize($adapter->getSessionValue("user"));
			$customeruserid = $oCustomerUser->getCustomeruserid();
			$customerid = $oCustomerUser->getCustomerid();
			
			$this->registerCommand(new Zibbra_Plugin_Ga_Command_Set("&uid", $customeruserid));			
			$this->registerCommand(new Zibbra_Plugin_Ga_Command_Set("dimension1", $customerid, self::DIMENSION1));
			
		} // end if
		
		// Generate final HTML code
		
		$html_commands = "";
		
		ksort($this->commands);
		
		foreach($this->commands as $commands) {
		
			$html_commands .= implode("\n", $commands)."\n";
			
		} // end foreach
		
		$html = sprintf($html, $html_commands);
		
		// Mark as rendered
		
		$this->rendered = true;
		
		// Return html code for insertion into the page
		
		return $html;
		
	} // end function
	
} // end class

abstract class Zibbra_Plugin_Ga_Command {
	
	protected function generate() {
		
		$args = func_get_args();
		
		foreach($args as &$arg) {
			
			if(is_string($arg) || is_numeric($arg)) {
				
				$arg = "'".$arg."'";
				
			}else{
				
				$arg = json_encode($arg);
				
			} // end if
			
		} // end foreach
		
		$html = Zibbra_Plugin_Ga::GLOBAL_OBJECT."(".implode(",",$args).");";
		
		return $html;
		
	} // end function
	
	abstract public function __toString();
	
} // end class

class Zibbra_Plugin_Ga_Command_Create extends Zibbra_Plugin_Ga_Command {
	
	const ORDER = 1;
	
	private $trackingid;
	
	public function __construct($trackingid) {
		
		$this->trackingid = $trackingid;
		
	} // end function
	
	public function __toString() {
		
		return $this->generate("create", $this->trackingid, "auto");
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Command_Require extends Zibbra_Plugin_Ga_Command {
	
	const ORDER = 2;
	
	private $libraryName;
	
	public function __construct($libraryName) {
		
		$this->libraryName = $libraryName;
		
	} // end function
	
	public function __toString() {
		
		return $this->generate("require", $this->libraryName);
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Command_Set extends Zibbra_Plugin_Ga_Command {
	
	const ORDER = 3;
	
	private $fieldName;
	private $value;
	private $comment;
	
	public function __construct($fieldName, $value, $comment=null) {
		
		$this->fieldName = $fieldName;
		$this->value = $value;
		$this->comment = $comment;
		
	} // end function
	
	public function __toString() {
		
		return $this->generate("set", $this->fieldName, $this->value).(!empty($this->comment) ? " // ".$this->comment : "");
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Command_Ec extends Zibbra_Plugin_Ga_Command {
	
	const ORDER = 4;
	
	private $functionName;
	private $data;
	
	public function __construct($functionName, $data=null) {
		
		$this->functionName = $functionName;
		$this->data = $data;
		
	} // end function
	
	public function __toString() {
		
		return $this->generate("ec:".$this->functionName, $this->data);
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Command_Send extends Zibbra_Plugin_Ga_Command {
	
	const ORDER = 5;
	
	private $hitType;
	
	public function __construct($hitType) {
		
		$this->hitType = $hitType;
		
	} // end function
	
	public function __toString() {
		
		return $this->generate("send", $this->hitType);
		
	} // end function
	
} // end class

abstract class Zibbra_Plugin_Ga_Data {
	
	protected $ga;
	
	public function setGa(Zibbra_Plugin_Ga $ga) {
		
		$this->ga = $ga;
		
	} // end function

	abstract public function registerCommands();
	
} // end class

class Zibbra_Plugin_Ga_Data_Product extends Zibbra_Plugin_Ga_Data {
	
	private $id;
	private $name;
	private $action;
	private $category;
	private $brand;
	private $price;
	
	public function __construct($id, $name, $action="detail") {
		
		$this->id = $id;
		$this->name = $name;
		$this->action = $action;
		
	} // end function
	
	public function setCategory($category) {
		
		$this->category = $category;
		
	} // end function
	
	public function setBrand($brand) {
		
		$this->brand = $brand;
		
	} // end function
	
	public function setPrice($price) {
		
		$this->price = $price;
		
	} // end function
	
	public function registerCommands() {
		
		$o = new StdClass();
		
		foreach(get_object_vars($this) as $key=>$value) {
			
			if($key!=="ga" && $key!=="action" && !empty($value)) {
				
				$o->$key = $value;
			
			} // end if
			
		} // end if
		
		$this->ga->registerCommand(new Zibbra_Plugin_Ga_Command_Ec("addProduct", $o));
		$this->ga->registerCommand(new Zibbra_Plugin_Ga_Command_Ec("setAction", $this->action));
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Data_Impression extends Zibbra_Plugin_Ga_Data {
	
	private $id;
	private $name;
	private $category;
	private $brand;
	private $position;
	private $list;
	
	public function __construct($id, $name) {
		
		$this->id = $id;
		$this->name = $name;
		
	} // end function
	
	public function setCategory($category) {
		
		$this->category = $category;
		
	} // end function
	
	public function setBrand($brand) {
		
		$this->brand = $brand;
		
	} // end function
	
	public function setPosition($position) {
		
		$this->position = $position;
		
	} // end function
	
	public function setList($list) {
		
		$this->list = $list;
		
	} // end function
	
	public function registerCommands() {
		
		$o = new StdClass();
		
		foreach(get_object_vars($this) as $key=>$value) {
			
			if($key!=="ga" && !empty($value)) {
				
				$o->$key = $value;
			
			} // end if
			
		} // end if
		
		$this->ga->registerCommand(new Zibbra_Plugin_Ga_Command_Ec("addImpression", $o));
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Data_Promotion extends Zibbra_Plugin_Ga_Data {
	
	private $id;
	private $name;
	
	public function __construct($id, $name) {
		
		$this->id = $id;
		$this->name = $name;
		
	} // end function
	
	public function registerCommands() {
		
	} // end function
	
} // end class

class Zibbra_Plugin_Ga_Data_Action extends Zibbra_Plugin_Ga_Data {
	
	private $id;
	
	public function __construct($id) {
		
		$this->id = $id;
		
	} // end function
	
	public function registerCommands() {
		
	} // end function
	
} // end class

?>