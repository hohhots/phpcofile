<?php

                /***************************************************************************
		                                html.**
		                             -------------------
		    begin                : 2005 June 26 Saturday
		    copyright            : (C) 2005 The nm114.net web
		    email                : brgd@nm114.net
		
		    $Id: html.php,v 1.1.1.1 2006/02/05 13:47:01 brgd Exp $
		
		 ***************************************************************************/	 
//require_once("PEAR.php");
//***********************************************
class IT_Error extends PEAR_Error {


  var $error_message_prefix = "IntegratedTemplate Error: ";
  
  function IT_Error($msg, $file = __FILE__, $line = __LINE__) {
    
    $this->PEAR_Error(sprintf("%s [%s on line %d].", $msg, $file, $line));
    
  } // end func IT_Error
  
} // end class IT_Error

define("IT_OK",                         1);
define("IT_ERROR",                     -1);
define("IT_TPL_NOT_FOUND",             -2);
define("IT_BLOCK_NOT_FOUND",           -3);
define("IT_BLOCK_DUPLICATE",           -4);
define('IT_UNKNOWN_OPTION',            -6);
 //***********************************************
abstract class HTML_Template_IT {

    var $err = array();

    var $clearCache = false;

    var $openingDelimiter = "{";

    var $closingDelimiter     = "}";

    var $blocknameRegExp    = "[0-9A-Za-z_-]+";

    var $variablenameRegExp    = "[0-9A-Za-z_-]+";

    var $variablesRegExp = "";

    var $removeVariablesRegExp = "";

    var $removeUnknownVariables = true;

    var $removeEmptyBlocks = true;

    var $blockRegExp = "";

    var $currentBlock = "__global__";

    var $template = "";

    var $blocklist = array();

    var $blockdata = array();

    var $blockvariables = array();

    var $blockinner         = array();

    var $touchedBlocks = array();

    var $_hiddenBlocks = array();

    var $variableCache = array();

    var $clearCacheOnParse = false;

    var $fileRoot = "";

    var $flagBlocktrouble = false;

    var $flagGlobalParsed = false;

    var $flagCacheTemplatefile = true;

    var $lastTemplatefile = "";

    var $_options = array(
        'preserve_data' => false,
        'use_preg'      => true
    );

    function HTML_Template_IT($root = "", $options=null) {
        if(!is_null($options)){
            $this->setOptions($options);
        }
        $this->variablesRegExp = "@" . $this->openingDelimiter .
                                 "(" . $this->variablenameRegExp . ")" .
                                 $this->closingDelimiter . "@sm";
        $this->removeVariablesRegExp = "@" . $this->openingDelimiter .
                                       "\s*(" . $this->variablenameRegExp .
                                       ")\s*" . $this->closingDelimiter ."@sm";

        $this->blockRegExp = '@<!--\s+BEGIN\s+(' . $this->blocknameRegExp .
                             ')\s+-->(.*)<!--\s+END\s+\1\s+-->@sm';

        $this->setRoot($root);
    } 
    
    function setOption($option, $value)
    {
        if (isset($this->_options[$option])) {
            $this->_options[$option] = $value;
            return IT_OK;
        }
        return PEAR::raiseError(
                $this->errorMessage(IT_UNKNOWN_OPTION) . ": '{$option}'",
                IT_UNKNOWN_OPTION
            );
    }

    function setOptions($options)
    {
        foreach($options as $option=>$value){
            if( PEAR::isError($error=$this->setOption($option, $value)) ){
                return $error;
            }
        }
    }

    function show($block = "__global__") {
        print $this->get($block);
    }

    function get($block = "__global__") {

        if ("__global__" == $block && !$this->flagGlobalParsed)
            $this->parse("__global__");

        if (!isset($this->blocklist[$block])) {
            $this->err[] = PEAR::raiseError(
                            $this->errorMessage( IT_BLOCK_NOT_FOUND ) .
                            '"' . $block . "'",
                            IT_BLOCK_NOT_FOUND
                        );
            return "";
        }

        if (!isset($this->blockdata[$block])) {
            return '';

        } else {
            $ret = $this->blockdata[$block];
            if ($this->clearCache) {
                unset($this->blockdata[$block]);
            }
            if ($this->_options['preserve_data']) {
                $ret = str_replace(
                        $this->openingDelimiter .
                        '%preserved%' . $this->closingDelimiter,
                        $this->openingDelimiter,
                        $ret
                    );
            }
            return $ret;
        }
    } // end func get()

    /**
     * Parses the given block.
     *
     * @param    string    name of the block to be parsed
     * @access   public
     * @see      parseCurrentBlock()
     * @throws   PEAR_Error
     */
    function parse($block = "__global__", $flag_recursion = false)
    {
        static $regs, $values;

        if (!isset($this->blocklist[$block])) {
            return PEAR::raiseError(
                $this->errorMessage( IT_BLOCK_NOT_FOUND ) . '"' . $block . "'",
                        IT_BLOCK_NOT_FOUND
                );
        }

        if ("__global__" == $block) {
            $this->flagGlobalParsed = true;
        }

        if (!$flag_recursion) {
            $regs   = array();
            $values = array();
        }
        $outer = $this->blocklist[$block];
        $empty = true;

        if ($this->clearCacheOnParse) {

            foreach ($this->variableCache as $name => $value) {
                $regs[] = $this->openingDelimiter .
                          $name . $this->closingDelimiter;
                $values[] = $value;
                $empty = false;
            }
            $this->variableCache = array();

        } else {

            foreach ($this->blockvariables[$block] as $allowedvar => $v) {

                if (isset($this->variableCache[$allowedvar])) {
                   $regs[]   = $this->openingDelimiter .
                               $allowedvar . $this->closingDelimiter;
                   $values[] = $this->variableCache[$allowedvar];
                   unset($this->variableCache[$allowedvar]);
                   $empty = false;
                }

            }

        }

        if (isset($this->blockinner[$block])) {

            foreach ($this->blockinner[$block] as $k => $innerblock) {

                $this->parse($innerblock, true);
                if ("" != $this->blockdata[$innerblock]) {
                    $empty = false;
                }

                $placeholder = $this->openingDelimiter . "__" .
                                $innerblock . "__" . $this->closingDelimiter;
                $outer = str_replace(
                                    $placeholder,
                                    $this->blockdata[$innerblock], $outer
                        );
                $this->blockdata[$innerblock] = "";
            }

        }

        if (!$flag_recursion && 0 != count($values)) {
            if ($this->_options['use_preg']) {
                $regs        = array_map(array(
                                    &$this, '_addPregDelimiters'),
                                    $regs
                                );
                $funcReplace = 'preg_replace';
            } else {
                $funcReplace = 'str_replace';
            }
            if ($this->_options['preserve_data']) {
                $values = array_map(
                            array(&$this, '_preserveOpeningDelimiter'), $values
                        );
            }

            $outer = $funcReplace($regs, $values, $outer);

            if ($this->removeUnknownVariables) {
                $outer = preg_replace($this->removeVariablesRegExp, "", $outer);
            }
        }
        if ($empty) {

            if (!$this->removeEmptyBlocks) {

                $this->blockdata[$block ].= $outer;

            } else {

                if (isset($this->touchedBlocks[$block])) {
                    $this->blockdata[$block] .= $outer;
                    unset($this->touchedBlocks[$block]);
                }

            }

        } else {

            $this->blockdata[$block] .= $outer;

        }

        return $empty;
    }
    
    function parseCurrentBlock() {
        return $this->parse($this->currentBlock);
    }
    function setVariable($variable, $value = "") {

        if (is_array($variable)) {

            $this->variableCache = array_merge(
                                            $this->variableCache, $variable
                                    );

        } else {

            $this->variableCache[$variable] = $value;

        }

    }
    
    function setCurrentBlock($block = "__global__") {

        if (!isset($this->blocklist[$block])) {
            return PEAR::raiseError(
                $this->errorMessage( IT_BLOCK_NOT_FOUND ) .
                '"' . $block . "'", IT_BLOCK_NOT_FOUND
            );
        }

        $this->currentBlock = $block;

        return true;
    } 
    
    function touchBlock($block) {

        if (!isset($this->blocklist[$block])) {
            return PEAR::raiseError(
                $this->errorMessage( IT_BLOCK_NOT_FOUND ) .
                '"' . $block . "'", IT_BLOCK_NOT_FOUND  );
        }

        $this->touchedBlocks[$block] = true;

        return true;
    } 
    
    function init() {

        $this->free();
        $this->findBlocks($this->template);
        // we don't need it any more
        $this->template = '';
        $this->buildBlockvariablelist();

    } 
    
    function free() {

        $this->err = array();

        $this->currentBlock = "__global__";

        $this->variableCache    = array();
        $this->blocklookup      = array();
        $this->touchedBlocks    = array();

        $this->flagBlocktrouble = false;
        $this->flagGlobalParsed = false;

    } 
    
    function setTemplate( $template, $removeUnknownVariables = true,
                          $removeEmptyBlocks = true
    ) {

        $this->removeUnknownVariables = $removeUnknownVariables;
        $this->removeEmptyBlocks = $removeEmptyBlocks;

        if ("" == $template && $this->flagCacheTemplatefile) {

            $this->variableCache = array();
            $this->blockdata = array();
            $this->touchedBlocks = array();
            $this->currentBlock = "__global__";

        } else {

            $this->template = '<!-- BEGIN __global__ -->' . $template .
                              '<!-- END __global__ -->';
            $this->init();

        }

        if ($this->flagBlocktrouble)
            return false;

        return true;
    }
    
    function loadTemplatefile( $filename,
                               $removeUnknownVariables = true,
                               $removeEmptyBlocks = true ) {

        $template = "";
        if (!$this->flagCacheTemplatefile ||
            $this->lastTemplatefile != $filename
        ){
            $template = $this->getfile($filename);
        }
        $this->lastTemplatefile = $filename;

        return $template!=""?
                $this->setTemplate(
                        $template,$removeUnknownVariables, $removeEmptyBlocks
                    ):false;
    } 
    
    function setRoot($root) {

        if ("" != $root && "/" != substr($root, -1))
            $root .= "/";

        $this->fileRoot = $root;

    } 
    
    function buildBlockvariablelist() {

        foreach ($this->blocklist as $name => $content) {
            preg_match_all( $this->variablesRegExp, $content, $regs );

            if (0 != count($regs[1])) {

                foreach ($regs[1] as $k => $var)
                    $this->blockvariables[$name][$var] = true;

            } else {

                $this->blockvariables[$name] = array();

            }

        }

    } 
    
    function getGlobalvariables() {

        $regs   = array();
        $values = array();

        foreach ($this->blockvariables["__global__"] as $allowedvar => $v) {

            if (isset($this->variableCache[$allowedvar])) {
                $regs[]   = "@" . $this->openingDelimiter .
                            $allowedvar . $this->closingDelimiter."@";
                $values[] = $this->variableCache[$allowedvar];
                unset($this->variableCache[$allowedvar]);
            }

        }

        return array($regs, $values);
    } 
    
    function findBlocks($string) {

        $blocklist = array();

        if (
            preg_match_all($this->blockRegExp, $string, $regs, PREG_SET_ORDER)
        ) {

            foreach ($regs as $k => $match) {

                $blockname         = $match[1];
                $blockcontent = $match[2];

                if (isset($this->blocklist[$blockname])) {
                    $this->err[] = PEAR::raiseError(
                                            $this->errorMessage(
                                            IT_BLOCK_DUPLICATE ) . '"' .
                                            $blockname . "'",
                                            IT_BLOCK_DUPLICATE
                                    );
                    $this->flagBlocktrouble = true;
                }

                $this->blocklist[$blockname] = $blockcontent;
                $this->blockdata[$blockname] = "";

                $blocklist[] = $blockname;

                $inner = $this->findBlocks($blockcontent);
                foreach ($inner as $k => $name) {

                    $pattern = sprintf(
                        '@<!--\s+BEGIN\s+%s\s+-->(.*)<!--\s+END\s+%s\s+-->@sm',
                        $name,
                        $name
                    );

                    $this->blocklist[$blockname] = preg_replace(
                                        $pattern,
                                        $this->openingDelimiter .
                                        "__" . $name . "__" .
                                        $this->closingDelimiter,
                                        $this->blocklist[$blockname]
                               );
                    $this->blockinner[$blockname][] = $name;
                    $this->blockparents[$name] = $blockname;

                }

            }

        }

        return $blocklist;
    } 
    
    function getFile($filename) {

        if ("/" == $filename{0} && "/" == substr($this->fileRoot, -1))
            $filename = substr($filename, 1);

        $filename = $this->fileRoot . $filename;

        if (!($fh = @fopen($filename, "r"))) {
            $this->err[] = PEAR::raiseError(
                        $this->errorMessage(IT_TPL_NOT_FOUND) .
                        ': "' .$filename .'"',
                        IT_TPL_NOT_FOUND
                    );
            return "";
        }

        $content = fread($fh, filesize($filename));
        fclose($fh);

        return preg_replace(
            "#<!-- INCLUDE (.*) -->#ime", "\$this->getFile('\\1')", $content
        );
    }
    
    function _addPregDelimiters($str)
    {
        return '@' . $str . '@';
    }
    
    function _preserveOpeningDelimiter($str)
    {
        return (false === strpos($str, $this->openingDelimiter))?
                $str:
                str_replace(
                    $this->openingDelimiter,
                    $this->openingDelimiter .
                    '%preserved%' . $this->closingDelimiter,
                    $str
                );
    }


    function errorMessage($value)
    {
        static $errorMessages;
        if (!isset($errorMessages)) {
            $errorMessages = array(
                IT_OK                       => '',
                IT_ERROR                    => 'unknown error',
                IT_TPL_NOT_FOUND            => 'Cannot read the template file',
                IT_BLOCK_NOT_FOUND          => 'Cannot find this block',
                IT_BLOCK_DUPLICATE          => 'The name of a block must be'.
                                               ' uniquewithin a template.'.
                                               ' Found "$blockname" twice.'.
                                               'Unpredictable results '.
                                               'may appear.',
                IT_UNKNOWN_OPTION           => 'Unknown option'
            );
        }

        if (PEAR::isError($value)) {
            $value = $value->getCode();
        }

        return isset($errorMessages[$value]) ?
                $errorMessages[$value] : $errorMessages[IT_ERROR];
    }
} // end class IntegratedTemplate
//***********************************************
class HTML_Template_ITX extends HTML_Template_IT {
    
    var $warn = array();

    var $printWarning = false;

    var $haltOnWarning = false;

    var $checkblocknameRegExp = '';

    var $functionPrefix = 'func_';

    var $functionnameRegExp = '[_a-zA-Z]+[A-Za-z_0-9]*';

    var $functionRegExp = '';

    var $functions         = array();

    var $callback         = array();

	private static $num = 0;
	
	public static function makeObj($root){
		if(HTML_Template_ITX::$num == 0){
			HTML_Template_ITX::$num++;
			return new  HTML_Template_ITX($root);
		}else{
			exit('Just can make only one HTML_Template_ITX object!');
		}
	}

    private function HTML_Template_ITX($root = '')
    {

        $this->checkblocknameRegExp = '@' . $this->blocknameRegExp . '@';
        $this->functionRegExp = '@' . $this->functionPrefix . '(' .
                                $this->functionnameRegExp . ')\s*\(@sm';

        $this->HTML_Template_IT($root);
    } // end func constructor

    function init()
    {
        $this->free();
        $this->buildFunctionlist();
        $this->findBlocks($this->template);
        // we don't need it any more
        $this->template = '';
        $this->buildBlockvariablelist();

    }
    
    function replaceBlock($block, $template, $keep_content = false)
    {
        if (!isset($this->blocklist[$block])) {
            return new IT_Error(
            "The block "."'$block'".
            " does not exist in the template and thus it can't be replaced.",
            __FILE__, __LINE__
            );
        }
        if ('' == $template) {
            return new IT_Error('No block content given.', __FILE__, __LINE__);
        }
        if ($keep_content) {
            $blockdata = $this->blockdata[$block];
        }

        // remove all kinds of links to the block / data of the block
        $this->removeBlockData($block);

        $template = "<!-- BEGIN $block -->" . $template . "<!-- END $block -->";
        $parents = $this->blockparents[$block];
        $this->findBlocks($template);
        $this->blockparents[$block] = $parents;

        // KLUDGE: rebuild the list for all block - could be done faster
        $this->buildBlockvariablelist();

        if ($keep_content) {
            $this->blockdata[$block] = $blockdata;
        }

        // old TODO - I'm not sure if we need this
        // update caches

        return true;
    } 
    
    function replaceBlockfile($block, $filename, $keep_content = false)
    {
        return $this->replaceBlock($block, $this->getFile($filename), $keep_content);
    } 
    
    function addBlock($placeholder, $blockname, $template)
    {

        // Don't trust any user even if it's a programmer or yourself...
        if ('' == $placeholder) {

            return new IT_Error('No variable placeholder given.',
                                __FILE__, __LINE__
                                );

        } else if ( '' == $blockname ||
                    !preg_match($this->checkblocknameRegExp, $blockname)
        ) {

            return new IT_Error("No or invalid blockname '$blockname' given.",
                    __FILE__, __LINE__
                    );

        } else if ('' == $template) {

            return new IT_Error('No block content given.', __FILE__, __LINE__);

        } else if (isset($this->blocklist[$blockname])) {

            return new IT_Error('The block already exists.',
                                __FILE__, __LINE__
                            );

        }

        // find out where to insert the new block
        $parents = $this->findPlaceholderBlocks($placeholder);
        if (0 == count($parents)) {

            return new IT_Error(
                "The variable placeholder".
                " '$placeholder' was not found in the template.",
                __FILE__, __LINE__
            );

        } else if ( count($parents) > 1 ) {

            reset($parents);
            while (list($k, $parent) = each($parents)) {
                $msg .= "$parent, ";
            }
            $msg = substr($parent, -2);

            return new IT_Error("The variable placeholder "."'$placeholder'".
                                " must be unique, found in multiple blocks '$msg'.",
                                __FILE__, __LINE__
                                );
        }

        $template = "<!-- BEGIN $blockname -->" . $template . "<!-- END $blockname -->";
        $this->findBlocks($template);
        if ($this->flagBlocktrouble) {
            return false;    // findBlocks() already throws an exception
        }
        $this->blockinner[$parents[0]][] = $blockname;
        $this->blocklist[$parents[0]] = preg_replace(
                    '@' . $this->openingDelimiter . $placeholder .
                    $this->closingDelimiter . '@',

                    $this->openingDelimiter . '__' . $blockname . '__' .
                    $this->closingDelimiter,

                    $this->blocklist[$parents[0]]
                );

        $this->deleteFromBlockvariablelist($parents[0], $placeholder);
        $this->updateBlockvariablelist($blockname);
    
        return true;
    } 
    
    function addBlockfile($placeholder, $blockname, $filename)
    {
        return $this->addBlock($placeholder, $blockname, $this->getFile($filename));
    } 
    
    function placeholderExists($placeholder, $block = '')
    {
        if ('' == $placeholder) {
            new IT_Error('No placeholder name given.', __FILE__, __LINE__);
            return '';
        }

        if ('' != $block && !isset($this->blocklist[$block])) {
            new IT_Error("Unknown block '$block'.", __FILE__, __LINE__);
            return '';
        }

        // name of the block where the given placeholder was found
        $found = '';

        if ('' != $block) {

            if (is_array($variables = $this->blockvariables[$block])) {

                // search the value in the list of blockvariables
                reset($variables);
                while (list($k, $variable) = each($variables)) {
                    if ($k == $placeholder) {
                        $found = $block;
                        break;
                    }
                }
            }

        } else {

            // search all blocks and return the name of the first block that
            // contains the placeholder
            reset($this->blockvariables);
            while (list($blockname, $variables) = each($this->blockvariables)){

                if (is_array($variables) && isset($variables[$placeholder])) {
                    $found = $blockname;
                    break;
                }
            }

        }

        return $found;
    }
    
    function performCallback()
    {

        reset($this->functions);
        while (list($func_id, $function) = each($this->functions)) {

            if (isset($this->callback[$function['name']])) {

                if ('' != $this->callback[$function['name']]['object']) {
                    $this->variableCache['__function' . $func_id . '__'] =
                        call_user_func(
                        array(
                        &$GLOBALS[$this->callback[$function['name']]['object']],
                        $this->callback[$function['name']]['function']),
                        $function['args']
                       );
                } else {
                    $this->variableCache['__function' . $func_id . '__'] =
                            call_user_func(
                            $this->callback[$function['name']]['function'],
                            $function['args']
                        );
                }

            }

        }

    } 
    
    function getFunctioncalls()
    {
        return $this->functions;
    } 
    
    function setFunctioncontent($functionID, $replacement)
    {
        $this->variableCache['__function' . $functionID . '__'] = $replacement;
    } 
    
    function
    setCallbackFunction($tplfunction, $callbackfunction, $callbackobject = '')
    {

        if ('' == $tplfunction || '' == $callbackfunction) {
            return new IT_Error(
                "No template function "."('$tplfunction')".
                " and/or no callback function ('$callback') given.",
                    __FILE__, __LINE__
                );
        }
        $this->callback[$tplfunction] = array(
                                          "function"    => $callbackfunction,
                                          "object"        => $callbackobject
                                        );

        return true;
    }
    
    function setCallbackFuntiontable($functions)
    {
        $this->callback = $functions;
    } 
    
    function removeBlockData($block)
    {
        if (isset($this->blockinner[$block])) {
            foreach ($this->blockinner[$block] as $k => $inner) {
                $this->removeBlockData($inner);
            }

            unset($this->blockinner[$block]);
        }

        unset($this->blocklist[$block]);
        unset($this->blockdata[$block]);
        unset($this->blockvariables[$block]);
        unset($this->touchedBlocks[$block]);

    } 
    
    function getBlocklist()
    {
        $blocklist = array();
        foreach ($this->blocklist as $block => $content) {
            $blocklist[$block] = $block;
        }

        return $blocklist;
    } 
    
    function blockExists($blockname)
    {
        return isset($this->blocklist[$blockname]);
    }
    
    function getBlockvariables($block)
    {
        if (!isset($this->blockvariables[$block])) {
            return array();
        }

        $variables = array();
        foreach ($this->blockvariables[$block] as $variable => $v) {
            $variables[$variable] = $variable;
        }

        return $variables;
    } // end func getBlockvariables

    function BlockvariableExists($block, $variable)
    {
        return isset($this->blockvariables[$block][$variable]);
    }

    function buildFunctionlist()
    {
        $this->functions = array();

        $template = $this->template;
        $num = 0;

        while (preg_match($this->functionRegExp, $template, $regs)) {

            $pos = strpos($template, $regs[0]);
            $template = substr($template, $pos + strlen($regs[0]));

            $head = $this->getValue($template, ')');
            $args = array();

            $this->template = str_replace($regs[0] . $head . ')',
                                '{__function' . $num . '__}', $this->template
                            );
            $template = str_replace($regs[0] . $head . ')',
                        '{__function' . $num . '__}', $template
                        );

            while ('' != $head && $args2 = $this->getValue($head, ',')) {
                $arg2 = trim($args2);
                $args[] = ('"' == $arg2{0} || "'" == $arg2{0}) ?
                                    substr($arg2, 1, -1) : $arg2;
                if ($arg2 == $head) {
                    break;
                }
                $head = substr($head, strlen($arg2) + 1);
            }

            $this->functions[$num++] = array(
                                                'name'    => $regs[1],
                                                'args'    => $args
                                            );
        }

    }


    function getValue($code, $delimiter) {
        if ('' == $code) {
            return '';
        }

        if (!is_array($delimiter)) {
            $delimiter = array( $delimiter => true );
        }

        $len         = strlen($code);
        $enclosed    = false;
        $enclosed_by = '';

        if (isset($delimiter[$code[0]])) {

            $i = 1;

        } else {

            for ($i = 0; $i < $len; ++$i) {

                $char = $code[$i];

                if (
                        ('"' == $char || "'" == $char) &&
                        ($char == $enclosed_by || '' == $enclosed_by) &&
                        (0 == $i || ($i > 0 && '\\' != $code[$i - 1]))
                    ){

                    if (!$enclosed) {
                        $enclosed_by = $char;
                    } else {
                        $enclosed_by = "";
                    }
                    $enclosed = !$enclosed;

                }
                if (!$enclosed && isset($delimiter[$char])) {
                    break;
                }
            }

        }

        return substr($code, 0, $i);
    } 

    function deleteFromBlockvariablelist($block, $variables)
    {
        if (!is_array($variables)) {
            $variables = array($variables => true);
        }

        reset($this->blockvariables[$block]);
        while (list($varname, $val) = each($this->blockvariables[$block])) {
            if (isset($variables[$varname])) {
                unset($this->blockvariables[$block][$varname]);
            }
        }
    } 

    function updateBlockvariablelist($block)
    {
        preg_match_all( $this->variablesRegExp,
                        $this->blocklist[$block], $regs
                    );

        if (0 != count($regs[1])) {
            foreach ($regs[1] as $k => $var) {
                $this->blockvariables[$block][$var] = true;
            }
        } else {
            $this->blockvariables[$block] = array();
        }

        if (isset($this->blockinner[$block]) &&
            is_array($this->blockinner[$block]) &&
            count($this->blockinner[$block]) > 0
        ) {
            foreach($this->blockinner[$block] as $childBlock) {
                $this->updateBlockvariablelist($childBlock);
            }
        }

    }
    
    function findPlaceholderBlocks($variable)
    {
        $parents = array();
        reset($this->blocklist);
        while (list($blockname, $content) = each($this->blocklist)) {
            reset($this->blockvariables[$blockname]);
            while (
                list($varname, $val) = each($this->blockvariables[$blockname]))
            {
                if ($variable == $varname) {
                    $parents[] = $blockname;
                }
            }
        }

        return $parents;
    } 
    
    function warning($message, $file = '', $line = 0)
    {
        $message = sprintf(
                    'HTML_Template_ITX Warning: %s [File: %s, Line: %d]',
                    $message,
                    $file,
                    $line
                );

        $this->warn[] = $message;

        if ($this->printWarning) {
            print $message;
        }

        if ($this->haltOnError) {
            die($message);
        }
    } 

}
?>