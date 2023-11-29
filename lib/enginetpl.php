<?php 
class EngineTpl{
	public $url_tpl;
	public $tpl;
	function __construct($url_tpl){
		$this->url_tpl = $url_tpl;
		$this->tpl = file_get_contents($url_tpl);
		if($this->testVar("CACHE"))
			if(CACHE==MODO_DEBUG)
				$this->assignVar("CACHE", "?rand=".date("YmdHis"));
			else
				$this->assignVar("CACHE", "");
	}
	public function replaceURL(){
		if($this->testVar("{{URL_APP_LINKS}}")){
			$this->assignVar("{{URL_APP_LINKS}}", URL_APP_LINKS);
		}
		if($this->testVar("{{URL_APP}}")){
			$this->assignVar("{{URL_APP}}", URL_APP);
		}
	}
	public function replaceFooter(){
		if($this->testVar("{{FOOTER}}")){
			$this->assignVar("{{FOOTER}}", file_get_contents("resources/static/tpl/Footer.html"));
		}
	}
	private function testVar($var_tpl){
		return strpos($this->tpl, $var_tpl);		
	}
	public function assignVar($var_tpl, $value){
		if(!$this->testVar($var_tpl)){
			return "<b>error tpl:</b> No se encontro la variable <u>$var_tpl</u>";
		}
		$this->tpl=str_replace($var_tpl, $value, $this->tpl);
	}
	public function printToScreen(){
		echo $this->tpl;
	}
	public function makePrivatePage(){
		$this->assignVar("<<header>>", file_get_contents("resources/static/tpl/header.html"));
		$this->assignVar("<<templates>>", file_get_contents("resources/static/tpl/templates.html"));
		$this->assignVar("<<modals>>", file_get_contents("resources/static/tpl/modals.html"));
		$this->assignVar("<<menu>>", file_get_contents("resources/static/tpl/menu.html"));
	}
	public function makePublicPage(){
		$this->assignVar("<<resources>>", file_get_contents("resources/static/tpl/resources.html"));
		$this->assignVar("<<error>>", file_get_contents("resources/static/tpl/error.html"));
		$this->assignVar("<<footer>>", file_get_contents("resources/static/tpl/footer.html"));
		if ($this->testVar("{{date('Y-m-d')}}")) {
			$this->assignVar("{{date('Y-m-d')}}", date("Y-m-d"));
		}
	}
}
?>