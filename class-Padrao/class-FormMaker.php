<?php
Class FormMaker{
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

    public function ConfigForm($idform=null,$nameform=null,$actionform=null,$method=null,$titleform=null){
        $this->idform = (!empty($idform)) ? $idform : 'form';
        $this->nameform = (!empty($nameform)) ? $nameform : 'form';
        $this->actionform = (!empty($actionform)) ? $actionform : null;
        $this->method = (!empty($method)) ? $method : 'post';
        $this->titleform = (!empty($titleform)) ? $titleform : 'Formulario';
    }

    public function FormBasic(){
        if(empty($this->method)) return "O form necessita de um m?todo de envio.";

        $this->form= "<form method='{$this->method}' action='{$this->actionform}' name='{$this->nameform}' id='{$this->idform}' enctype='multipart/form-data'>";

        if(!empty($this->elements)){
            foreach ($this->elements as $field) {
                foreach ($field as $key => $value) {
                    if($key == 'title'){
                        $this->form.= "<div class='form-group'>{$value}";
                    }elseif($key == 'element'){
                        $this->form.= "{$value}</div>";
                    }elseif($key == 'single'){
                        $this->form.= $value;
                    }
                }
            }
        }else{
            $this->form.= "N?o h? ?tens neste form!";
        }


        if(!empty($this->buttons)){
            foreach ($this->buttons as $key => $value) {
                $this->form.= $value;
            }
        }

        $this->form.= "</form>";
        return $this->form;
    }

    public function additem($title = null, $element = null){
        if(empty($element)){
            $this->elements[] = array('single' => $title);
        }else{
            $title = "<label for='{$title}'>{$title}</label>";
            $this->elements[] = array('title' => $title, 'element' => $element);
        }
    }

    public function addbutton($button=null){
        $this->buttons[] = $button." ";
    }
	
	public static function addFormGroupSingle($class, $titulo, $input, $sepRight='') {

		//echo print_r(explode(' ', $class));
		//exit;
		//col-md-4 col-sm-12 col-xs-12
		
		$sep = (empty($sepRight)) ? "" : "style='margin-right: {$sepRight}px;'";
		
		$html = "";
		$html .= "<div class='{$class}' {$sep}>";
			$html .= "<div class='form-group'>";
				$html .= "<label for='disabledTextInput'>{$titulo}</label>";
				$html .= $input;
			$html .= "</div>";
		$html .= "</div>";
		
		return $html;

	}

    ###############################################################################################################

	public static function button($id, $title, $icon='', $atributos=array(), $js=''){
		$atributos['type'] = 'button';
		return FormMaker::_button($id, $title, $icon, $atributos, $js);
	}
	
	public static function buttonModal($id, $title, $icon='', $atributos=array(), $js=''){
		//$atributos['type'] = 'button';
		$atributos['id'] = 'buttonModal1';
		$atributos['data-toggle'] = 'modal';
		$atributos['data-target'] = $id;
		$id = !empty($id) ? '#'.$id : '#myModal';
		
		return FormMaker::button($id, $title, $icon, $atributos, $js);
	}
	
	public static function submitButton($id, $title, $icon='', $atributos=array()){
		$atributos['type'] = 'submit';
		return FormMaker::_button($id, $title, $icon, '', $atributos);
	}
	
	public static function _button ($id, $title, $icon, $atributos, $js) {
		
		$atributos['class'] = (empty($atributos['class'])) ? "btn btn-default" : $atributos['class'];
		$icon = (!empty($icon)) ? "<span class='glyphicon glyphicon-{$icon}' aria-hidden='true'></span>" : '';
		
		$button = "<button id='{$id}'";
		foreach ($atributos as $attr => $value) {
			if (empty($value)) continue;
			$button .= " {$attr} = '{$value}'";
		}
		$button .= "{$js}>{$icon} {$title}</button>";
        return $button;
	}
	
	public static function linkButton($id, $title,  $url='#', $icon='', $atributos=array(), $js=''){
		$atributos['class'] = (empty($atributos['class'])) ? "btn btn-default" : $atributos['class'];
		$icon = (!empty($icon)) ? "<span class='glyphicon glyphicon-{$icon}' aria-hidden='true'></span>" : '';
		
		$button = "<a href='{$url}'";
		foreach ($atributos as $attr => $value) {
			if (empty($value)) continue;
			$button .= " {$attr} = '{$value}'";
		}
		$button .= "{$js}>{$icon} {$title}</button>";
		
        return $button;
	}

    public static function searchBar($action=null, $extra=null){
        if(empty($action)) $action = "index.php";
        $container = "
			<form method='get' action='{$action}' {$extra}>
				<div class='row'>
				  <div class='col-lg-12'>
					<div class='input-group'>
					  <input type='text' class='form-control' name='busca' placeholder='Busca!'>
					  <span class='input-group-btn'>
						<button class='btn btn-default' type='submit'>
							<span class='glyphicon glyphicon-search' aria-hidden='true'></span>
						</button>
					  </span>
					</div><!-- /input-group -->
				  </div><!-- /.col-lg-12 -->
				</div><!-- /.row -->
			</form>";
        return $container;
    }

    public static function panel($title=null, $style=null, $container=null){
        $title = (empty($title)) ? null : "<div class='panel-heading'><h3>{$title}</h3></div>";
        $style = (empty($style)) ? 'default' : $style;
        $container = (empty($container)) ? 'Painel sem conte?do' : $container;

        $panel= "<div class='panel panel-{$style}'>{$title}";
        $panel.= "<div class='panel-body'>{$container}</div></div>";
        return $panel;
    }

    public static function title($title=null, $size=null, $align=null, $subtitle=null){
        $size = empty($size) ? '1' : $size;
        $align = empty($align) ? "class='text-left'" : "class='text-{$align}'";
        $subtitle = empty($subtitle) ? "" : " <small>{$subtitle}</small>";

        $title = "<h{$size} {$align}>{$title}{$subtitle}</h{$size}>";
        return $title;
    }

    public static function modal($id=null, $title=null, $container=null, $btnfunction=null){
        $id = !empty($id) ? $id : 'myModel';
        $title = !empty($title) ? $title : 'Painel';
        $container = !empty($container) ? $container : null;
        $button = '';

        if (!empty($btnfunction)) {
            if (is_array($btnfunction)) {
                for ($i=0; $i < count($btnfunction); $i++) {
                    $button.= $btnfunction[$i];
                }
            } elseif(is_string($btnfunction)) {
                $button.= $btnfunction;
            }
        }

        $modal = "
        <div class='modal fade' id='{$id}' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
          <div class='modal-dialog' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                <h4 class='modal-title' id='myModalLabel'>{$title}</h4>
              </div>
              <div class='modal-body'>
                {$container}
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-default' data-dismiss='modal'>Cancelar</button>
                {$button}
              </div>
            </div>
          </div>
        </div>
        ";

        return $modal;
    }

}
