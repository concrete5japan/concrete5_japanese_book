<?php
defined('C5_EXECUTE') or die("Access Denied.");
class Concrete5_Helper_Xml { 

	public function createCDataNode(SimpleXMLElement $x, $nodeName, $content) {
		$node = $x->addChild($nodeName);
		$node = dom_import_simplexml($node);
		$no = $node->ownerDocument;
		$node->appendChild($no->createCDataSection($content));
		return $node;
	}
	
	
}