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
		$lbl	= (!empty($field->label) ? '<div class="label"><label class="form-label">'.$field->label.'</label>'.$note.'</div>':'');
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
		$tarows = (isset($field->rows) ? $field->rows : 2);
		
		$open	= '<div class="control-group">'.$lbl.'<div class="control">';
		$close	= $fnote.'</div></div>';
		
		// field HTML
		$fld='';
		switch($type)
		{
			case 'text': $fld = '<input type="text" name="'.$name.'" value="'.$fval.'" id="'.$name.'" class="form-control"'.$hint.$req.' />'; break;
			case 'switch': 
			$fld = self::radioSwitch(['fatt'=>$field,'val'=>$fval]);
			break;
			case 'list':
			$fld = self::select(['fatt'=>$field,'val'=>$fval]);
			break;
			case 'number':
			$fld = '<input type="number" name="'.$name.'" value="'.$fval.'" class="form-control"'.$min.$max.' />';
			break;
			case 'textarea':
			$fld = '<textarea name="'.$name.'" class="form-control" rows="'.$tarows.'" id="'.$name.'" '.$hint.'>'.$fval.'</textarea>';
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
			$s2 = ' s2-multiple';
			$name = $v->name.'[]';
			$mval = (array)$val->val;
		}
		$field = '<select name="'.$name.'" class="form-select'.$s2.'"'.$multiple.'>';
		
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
		<div class="form-check form-switch">
		<input name="'.$v->name.'" value="1" class="form-check-input" type="checkbox" role="switch" id="'.$v->name.'"'.(1 == $val->val ? ' checked="checked"':'').'>
		<label class="form-check-label" for="'.$v->name.'"></label>
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

