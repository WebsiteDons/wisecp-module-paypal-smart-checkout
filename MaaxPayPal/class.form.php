<?php defined('CORE_FOLDER') || exit('WebsiteDons.com');

class wcpForm
{
	function __construct() {
		
	}
	
	public static function getXml($filename) {
		if( !file_exists($filename.'.xml') )
			return;
		
		return simplexml_load_file($filename.'.xml');
	}
	
	public static function getXmlAttrib($xml) 
	{
		if( is_string($xml) )
			return;
		$val = makeObj(current($xml));
		
		return $val;
	}
	
	public static function fields($val) 
	{
		$v = makeobj($val);
		$formfile = $v->xmlform;
		$form = self::getXml($formfile);
		
		$formhtml='';
		foreach($form->fieldset as $fieldset) 
		{
			if( isset($fieldset->field) ) {
				foreach($fieldset->field as $formfield) 
				{
					$att = self::getXmlAttrib($formfield);
					$formhtml .= self::fieldHtml($formfield,$v);
					
					if( $att->type == 'group' ) {
						foreach($formfield->gfield as $gfield) {
							$f_group[] = self::fieldHtml($gfield,$v);
						}
						$gclass = (isset($att->class) ? ' '.$att->class:'');
						$title = (isset($att->gtitle) ? '<h4>'.$att->gtitle.'</h4>':'');
						$formhtml .= '<div class="fgroup'.$gclass.'">'.$title.'<div class="gfields">'.implode('',$f_group).'</div></div>';
					}
				}
			}
			
			if( isset($fieldset->group) ) {
				foreach($fieldset->group as $fgroup) {
					foreach($fgroup->field as $gformfield) {
						$gformhtml[] = self::fieldHtml($gformfield,$v);
					}
					$formhtml .= '<div class="fgroup">'.implode('',$gformhtml).'</div>';
				}
			}
		}
		
		$fields = $formhtml;
		
		return $fields;
	}
	
	public static function fieldHtml($formfield,$v)
	{
		$field	= self::getXmlAttrib($formfield);
		
		$type	= getvar($field->type);
		$name	= getvar($field->name);
		$note	= (!empty($field->note) ? '<small>'.$field->note.'</small>':'');
		$lbl	= (!empty($field->label) ? '<div class="label"><label>'.$field->label.'</label>'.$note.'</div>':'');
		$fnote	= (!empty($field->fnote) ? '<small>'.wcpForm::txt($field->fnote).'</small>':'');
		$def = getvar($field->default);
		
		// replacements in notes text
		$fnote_sc = ['[MYIP]'];
		$fnote_rpl = [vip()];
		$fnote = str_replace($fnote_sc,$fnote_rpl,$fnote);
		
		$hint	= (!empty($field->hint) ? ' placeholder="'.$field->hint.'"':'');
		$min	= (isset($field->min) ? ' min="'.$field->min.'"':'');
		$max	= (isset($field->max) ? ' max="'.$field->max.'"':'');
		$req	= (isset($field->required) ? ' required="required"':'');
		$fval	= getVar($v->value->$name,$def);
		
		$open	= '<div class="control-group">'.$lbl.'<div class="control">';
		$close	= $fnote.'</div></div>';
		
		// field HTML
		$fld='';
		switch($type)
		{
			case 'text': $fld = '<input type="text" name="'.$name.'" value="'.$fval.'" id="'.$name.'"'.$hint.$req.' />'; break;
			case 'switch': 
			$fld = self::radioSwitch(['fatt'=>$field,'val'=>$fval]);
			break;
			case 'list':
			$fld = self::select(['fatt'=>$field,'val'=>$fval]);
			break;
			case 'number':
			$fld = '<input type="number" name="'.$name.'" value="'.$fval.'"'.$min.$max.' />';
			break;
		}
		
		return $open.$fld.$close;
	}
	
	public static function fnames($formfile) 
	{
		$form = self::getXml($formfile);
		
		$fnames=[];$fg=[];
		foreach($form->fieldset as $fieldset) 
		{
			foreach($fieldset->field as $formfield) 
			{
				$field = self::getXmlAttrib($formfield);
				$fnames[] = getvar($field->name);
				if( $field->type == 'group' ) {
					foreach($formfield->gfield as $gfield) {
						$gf = self::getXmlAttrib($gfield);
						$fg[] = $gf->name;
					}
				}
			}
		}
		
		$all = array_merge(array_filter($fnames),$fg);
		
		return (object)$all;
	}
	
	public static function select($val)
	{
		$val = makeobj($val);
		$v = $val->fatt;
		$opts=[];
		if( isset($v->options) ) 
		{
			if( strstr($v->options, '{') ) {
				$opts = self::stringToArray($v->options);
			}
			if( strstr($v->options, 'currency') ) {
				if( !class_exists('Money') ) 
					include_once CORE_DIR.'helpers/money.php';
				foreach(Money::getCurrencies($v->name) as $curr) {
					$opts[$curr['id']] = $curr['name'].' ('.$curr['code'].')';
				}
			}
		}
		
		$multiple= $s2= $s2js= $mval=''; $name = $v->name; $sval = $val->val;
		if( isset($v->multiple) ) {
			$multiple = ' multiple="multiple"';
			$s2 = ' class="s2-multiple"';
			$name = $v->name.'[]';
			$mval = (array)$val->val;
		}
		$field = '<select name="'.$name.'"'.$multiple.$s2.'>';
		
		foreach($opts as $optval => $optname) {
			if( !empty($mval) && is_array($mval) ) {
				$selected = (in_array($optval, $mval) ? ' selected':'');
			}else{
				$selected = ($optval == $sval ? ' selected':'');
			}
			$field .= '<option value="'.$optval.'"'.$selected.'>'.$optname.'</option>';
		}
		$field .= '</select>';
		
		return $field;
	}
	
	public static function radioSwitch($val)
	{
		$val = makeobj($val);
		$v = $val->fatt;
		$checked = (1 == $val->val ? ' checked="checked"':'');
		$field = '
			<div class="flex switch">
			<label class="yes"><span>Yes</span><span><input type="radio" name="'.$v->name.'" value="1"'.(1 == $val->val ? ' checked="checked"':'').' /></span></label>
			<label class="no"><span>No</span><span><input type="radio" name="'.$v->name.'" value="0"'.(empty($val->val) ? ' checked="checked"':'').' /></span></label>
			</div>
		'; 
		
		return $field;
	}
	
	public static function stringToArray($val,$numkeys=false) 
	{
		if( $numkeys ) {
			$arr = '['.str_replace('\'','"',$val).']';
		}else{
			$arr = str_replace('\'','"',$val);
		}
		$value = json_decode($arr,true);
		
		return $value;
	}
	
	// get language
	public static function txt($txt)
	{
		$val=[];
		if( isset($_GET['module']) )
			$val = include MODULE_DIR.'Payment/'.$_GET['module'].'/lang/'.Config::get('general/local').'.php';
		
		return (isset($val[$txt]) ? $val[$txt] : $txt);
	}
}

