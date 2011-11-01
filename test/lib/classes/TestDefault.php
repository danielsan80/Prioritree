<?php
class TestDefault extends PHPUnit_Framework_TestCase {
	
	protected function assertDomEquals($html1, $html2) {
		$replacements =  array("\t" =>'', "\r\n" => "\n");
		$html1 = strtr($html1, $replacements);
		$html2 = strtr($html2, $replacements);
		try {
			$dom1 = new DOMDocument();
			$dom1->loadHTML(trim($html1));
		} catch (Exception $e) {
			$this->fail('Not valid DOM document');
		}
		try {
			$dom2 = new DOMDocument();
			$dom2->loadHTML(trim($html2));
		} catch (Exception $e) {
			$this->fail('Not valid DOM document');
		}
		
		$dom1->normalize();
		$this->clearDom($dom1);
				
		$dom2->normalize();
		$this->clearDom($dom2);
		
		//file_put_contents('dom1.txt',$dom1->saveHTML());
		//file_put_contents('dom2.txt',$dom2->saveHTML());
		
		$this->assertEquals(trim($dom1->saveHTML()), trim($dom2->saveHTML()));
	}
	
	private function clearDom(&$dom){
   	 $stack = array($dom);
		while($stack) {
			$el = array_shift($stack);					
			if ($el->hasChildNodes()){
				for ($i=0; $i<$el->childNodes->length; $i++) {
					$child = $el->childNodes->item($i);
					if (get_class($child)=='DOMText' && $child->isWhitespaceInElementContent())
						$el->removeChild($child);
					else $stack[] = $child;
				}					
			}
		}
	}
}