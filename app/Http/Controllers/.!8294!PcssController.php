<?php

/**
 * PCSS Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    PCSS
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\ThemeSettings;

class PcssController extends Controller
{
    private $BODYBGCOLOR   = "";
    private $BODYFONTCOLOR = "";
    private $BODYFONTSIZE  = "";
    private $HEADERCOLOR   = "";
    private $FOOTERCOLOR   = "";
    private $BUTTONPRIMARYCOLOR   = "";
    private $BUTTONPRIMARYBORDERCOLOR   = "";

    public static $defined = array();
	
	private $css;
    private $iniciado = false;

    /**
     * Load Given CSS File
     *
     * @return load CSS File
     */
    public function index()
    {
        if (isset($_GET["css"]))
        {
            $css = strip_tags($_GET["css"]);
            $this->init($css);
            return response($this->pcss)->header('Content-Type', 'text/css');
        }
    }

    /**
     * Set Dynamic CSS Styles
     *
     * @param string $arg CSS file name
     * @return string CSS styles
     */
    public function init($arq)
    {
        $result = ThemeSettings::get();

        $this->BODYBGCOLOR        = "background-color: ".$result[0]->value;
        $this->BODYFONTCOLOR      = "color: ".$result[1]->value;
        $this->BODYFONTSIZE       = "font-size: ".$result[2]->value;
        $this->HEADERBGCOLOR      = "background-color: ".$result[3]->value;
        $this->FOOTERBGCOLOR      = "background-color: ".$result[4]->value;
        $this->HREFCOLOR          = "color: ".$result[5]->value;
        $this->BUTTONPRIMARYCOLOR = "background-color: ".$result[6]->value;
        $this->BUTTONPRIMARYBORDERCOLOR = "border-color: ".$result[6]->value;

        if (!is_file($arq))
        {
            die("CSS File not found");
        } 
        else 
        {                        
            $f = file($arq,FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
            foreach ($f as &$linha)
            {
                $linha = trim($linha);
                $defines = array();
                if (strtolower(substr(trim($linha),0,4))=="unit")
                {
                    $this->UNIT = trim(substr(trim($linha),5));
                    $linha = "";
                }
                if (strtolower(substr(trim($linha),0,6))=="imgdir")
                {
                    $this->IMGDIR = trim(substr(trim($linha),7));
                    if (substr($this->IMGDIR,-1,1)!="/") $this->IMGDIR .= "/";
                    $linha = "";
                }
                if (strtolower(substr(trim($linha),0,8))=="fontface")
                {
                    $tempval = trim(substr(trim($linha),8));
                    list($name,$file) = explode(" ",$tempval);
                    if ($this->IsBrowser("MSIE 6")||$this->IsBrowser("MSIE 7")||$this->IsBrowser("MSIE 8"))
                    {
                        $eot = "url('$file.eot#') format('eot'),";
                    } else 
                    {
                        $eot = false;
                    }
					$linha = "@font-face{font-family:'$name';font-style:normal;font-weight:normal;src:".$eot."url('$file.woff') format('woff'),url('$file.ttf') format('truetype');}";
                }
                if (strtolower(substr(trim($linha),0,1))=="&")
                {
                    $linhai = explode("=",substr(trim($linha),1),2);
                    eval ('$this->defined[\''.trim($linhai[0])."']="."'".addslashes(trim($linhai[1]))."';");
                    $linha = "";
                }
                if (strtolower(substr(trim($linha),0,2))=="//")
                {
                    $linha = "";
                }
            }
            $this->css = join("",$f);
            
            $pcss = $this->CSSDecode();
            $ret = $this->ParsePCSSArray($pcss);
            $this->pcss = $ret;
        }
    }

    private function CheckSyntax($code)
    {
        return @eval('return true;' . $code);
    }

	private function CSSDecode()
    {
		$css = $this->css;
		$comment = false;
		$out = '$css=array("';
	 
		for ($i=0; $i<strlen($css); $i++)
        {
			if (!$comment)
            {
