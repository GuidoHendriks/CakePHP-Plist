<?php
App::uses('View', 'View');
App::import('Vendor', 'Plist.CFPropertyList', array('file' => 'CFPropertyList/classes/CFPropertyList/CFPropertyList.php'));

class PlistView extends View {
	public $subDir = 'plist';
	
	private function isBinary() {
		if (isset($this->viewVars['_binaryPlist'])) {
			return $this->viewVars['_binaryPlist'] == true;
		}
		return (bool)Configure::read('binaryPlist');
	}
	public function __construct(Controller $controller = null) {
		parent::__construct($controller);
		if (isset($controller->response) && $controller->response instanceof CakeResponse) {
			$responseType = 'plist';
			if ($this->isBinary()) {
				$responseType .= '-binary';
			}
			$controller->response->type($responseType);
		}
	}
	
    public function render($view = null, $layout = null) {
        if (isset($this->viewVars['_serialize'])) {
			return $this->_serialize($this->viewVars['_serialize']);
		}
		if ($view !== false && $this->_getViewFileName($view)) {
			return parent::render($view, false);
		}
    }
    public function _serialize($serialize) {
		if (is_array($serialize)) {
			$data = array();
			foreach ($serialize as $key) {
				$data[$key] = $this->viewVars[$key];
			}
		} else {
			$data = isset($this->viewVars[$serialize]) ? $this->viewVars[$serialize] : null;
		}
    
	    $plist = new CFPropertyList\CFPropertyList();
	    
		$typeDetector = new CFPropertyList\CFTypeDetector();
		$guessedStructure = $typeDetector->toCFType($data);
		$plist->add($guessedStructure);
		
		return $this->isBinary() ? $plist->toBinary() : $plist->toXML(false);
    }
}