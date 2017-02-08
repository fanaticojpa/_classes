<?php
Class tag{
    public $idform;
    public $nameform;
    public $actionform;
    public $method;
    public $titleform;
    public $form;

    /* Elements for Forms */
    private $elements = array();
    private $buttons = array();

    public function __construct($idform=null,$nameform=null,$actionform=null,$method=null,$titleform=null){
        self::ConfigForm($idform,$nameform,$actionform,$method, $titleform);
    }


    ###############################################################################################################

	public static function hidden($nome=null, $id=null, $value=null){
        $hidden = "<input type='hidden' name='{$nome}' id='{$id}' value='{$value}'>";
		return $hidden;
    }

	 public static function inputText($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'text';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function inputCpf($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'text';
		 $atributos['onFocus'] = "mascara(this, \"999.999.999-99\")";
		 //$atributos['onBlur'] = 'Função para validar valor';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function inputTelefone($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'text';
		 $atributos['onFocus'] = "mascara(this, \"(99) 99999-9999\")";
		 //$atributos['onBlur'] = 'Função para validar valor';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function inputNumber($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'number';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputPassword($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'password';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputEmail($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'email';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputDate($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'date';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputMes($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'month';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputSemana($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'week';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputTime($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'time';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputColor($id, $name, $js='', $atributos=array()){
		 $atributos['type'] = 'color';
		 return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputcheckBox($id, $name, $js='', $atributos=array()){
		$atributos['type'] = 'checkbox';
		$atributos['class'] = 'form-check-input';
		//$atributos['checked'] = 'checked';
		return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputRadio($id, $name, $js='', $atributos=array()){
		$atributos['type'] = 'radio';
		$atributos['class'] = 'form-check-input';
		//$atributos['checked'] = 'checked';
		return tag::_input($id, $name, $js, $atributos);
	 }

	 public static function InputformCheck($id, $name, $label, $js=''){

		$html = '';
		$html .= "<div class='form-check'>";
		  $html .= "<label class='form-check-label'>";
			 $html .= tag::InputcheckBox($id, $name, $js).$label;
		  $html .= "</label>";
		$html .= "</div>";

		return $html;
	 }

	 public static function InputFile($id, $name, $js, $atributos=array()){
		 $atributos['type'] = 'file';
		 return tag::_input($id, $name, $js, $atributos);
	 }

    public static function _input($id, $name, $js, $atributos){

        $input = "<input id={$id} name={$name}";

        if (is_array($atributos)) {
            $atributos['class'] = (empty($atributos['class'])) ? "form-control" : $atributos['class'];

            foreach ($atributos as $name => $value) {
                if (empty($value)) continue;
                $input .= " {$name} = '{$value}'";
            }
            $input .= " {$js}> ";
        } else {
            $input .= $atributos;
        }

		return $input;

	/*
	$atributos['nome']
	$atributos['id']
	$atributos['size']
	$atributos['maxlength']
	$atributos['disabled']
	$atributos['value']
	$atributos['placeholder']
	$atributos['extra']
	*/
    }

	//--------------------------------------------------------------------------
	public static function InputTextAreaEditor($id, $name, $valCampo='', $atributos=array()){
        $textareaEditor = "<script src=\"//cdn.tinymce.com/4/tinymce.min.js\"></script>";
        $textareaEditor.= "<script>tinymce.init({ selector:'textarea#".$id."'});</script>";
        $textareaEditor.= tag::InputTextArea($id, $name, $valCampo='', $atributos);

        return $textareaEditor;
    }

	public static function InputTextArea($id, $name, $valCampo='', $atributos=array()) {

		$attr = "<textarea id={$id} name={$name}";
		foreach ($atributos as $atr => $value) {
			if (empty($value)) continue;
			$attr .= " {$atr} = '{$value}'";
		}
		$attr .= ">{$valCampo}</textarea>";
		return $attr;
	}


	//--------------------------------------------------------------------------

	public static function selectPickFile($id, $name, $checked, $pickVar, $pickFile, $atributos=array(), $js='') {
		$pickList = text::_getpickarray($pickVar, $pickFile);
		return tag::_select($id, $name, $checked, $pickList, $atributos, $js);
	}

	public static function selectPickVar($id, $name, $checked, $pickVar, $atributos=array(), $js='') {
		return tag::_select($id, $name, $checked, $pickVar, $atributos, $js);
	}

	public static function _select($id, $name, $checked, $pickList, $atributos, $js) {

		$atributos['class'] = (empty($atributos['class'])) ? 'form-control' : $atributos['class'];

		$select = "<select id={$id} name={$name} {$js}";
		foreach ($atributos as $atr => $value) {
			if (empty($value)) continue;
			$select .= " {$atr} = '{$value}'";
		}
		$select .= "> ";

        if (!empty($pickList)){
            $select.=  "<option value='0'>Selecione</option>";
			foreach ($pickList as $id => $value) {

				if ($id == $checked) {
					$select.=  "<option value='{$id}' selected>{$value}</option>";
				} else {
					$select.=  "<option value='{$id}'>{$value}</option>";
				}
			}
		} else {
			$select.=  "<option value='0'>Sem opções</option>";
		}
        $select.= "</select>";

        return $select;
    }

	public static function addButton($id, $name, $atributos=array(), $js='') {
		$btn = '';
		$btn .= '<button ';
		foreach ($atributos as $atr => $value) {
			if (empty($value)) continue;
			$btn .= " {$atr} = '{$value}'";
		}
		$btn .= "{$js} </button>";
	}

	//--------------------------------------------------------------------------
	//Fazer ainda
    public static function comboRadio($nome=null, $id=null, $array=null, $inline=null, $active=null){
        $nome = (empty($nome)) ? null : $nome;
        $id = (empty($id)) ? null : $id;
        $inline = (empty($inline)) ? null : '-inline';
        $active = (empty($active)) ? null : 'disabled';

        $radio = "<div class='radio {$active}' name='{$nome}' id='{$id}' >";
        if(!empty($array)){
            if(is_array($array)){
                foreach ($array as $key => $value) {
                    $radio.=  "<label><input type='radio{$inline}' value='{$key}' {$active}> {$value}</label>";
                }
            }elseif (is_string($array)) {
                $radio.=  "<label><input type='radio{$inline}' value='{$array}' {$active}> {$array}</label>";
            }
        }else{
            $radio.=  "<label><input type='radio{$inline}' disabled> Sem op??es</label>";
        }
        $radio.= "</div>";
        return $radio;
    }

    public static function comboCheck($nome=null, $id=null, $array=null, $checked=null, $inline=null, $active=null){
        $nome = (empty($nome)) ? null : $nome;
        $id = (empty($id)) ? null : $id;
        $inline = (empty($inline)) ? null : '-inline';
        $active = (empty($active)) ? null : 'disabled';
        $checked = (empty($checked)) ? null : 'checked';

        $checkbox = "<div class='checkbox {$active}' name='{$nome}check' id='{$id}check' >";
        if(!empty($array)){
            if(is_array($array)){
                foreach ($array as $key => $value) {
                    $checkbox.=  "<label><input type='checkbox{$inline}' name='{$nome}' id='{$id}' value='{$key}' {$active}{$checked}> {$value}</label>";
                }
            }elseif (is_string($array)) {
                $checkbox.=  "<label><input type='checkbox{$inline}' name='{$nome}' id='{$id}' value='{$array}' {$active}{$checked}> {$array}</label>";
            }
        }else{
            $checkbox.=  "<label><input type='checkbox{$inline}' disabled> Sem opções</label>";
        }
        $checkbox.= "</div>";
        return $checkbox;
    }

}
