<?php


class DocShowApi{
	private $groups;
	private $collection;
	function __construct(DocParserStorage $storage){
		$this->groups = $storage->getPer();
		$this->collection = $storage->getCollection();

	}
	function content(){
		$output = '';
		foreach ($this->collection as $key => $value) {
			$output .= '<div class="apiitem" id="item'.$value['_id'].'" style="display:none">';
			$output .= '<h1>';
			$output .= $this->method_label(ex_el($value['http_method'],'???'))." ";
			$output .= $this->shortUrl(empty($value['url']) ? "NO URL FOUND": $value['url']);
			$output .= '</h1>';
			if(isset($value['description'])){
				$output .= '<blockquote>'.$value['description'].'</blockquote>';				
			}
			$output .= '<dl class="dl-horizontal">';
			if(isset($value['succes'])){
				$output .= '<dt>Success Result</dt>';
				$output .= '<dd>'.ex_el($value['succes'],'Not Set').'</dd>';				
			}
			if(isset($value['fail'])){
				$output .= '<dt>Fail Result</dt>';
				$output .= '<dd>'.ex_el($value['fail'],'Not Set').'</dd>';				
			}

			if(isset($value['auth'])){
				$output .= '<dt>Needs Authentication</dt>';
				$output .= '<dd>'.ex_el($value['auth'],'Not Set').'</dd>';				
			}
			$output .= '</dl>';
			if(isset($value['input_var'])){
				$output .= '<h2>URL input variables</h2>';
				$output .= '<dl class="dl-horizontal">';
				foreach ($value['input_var'] as $name => $rules) {
					$output .= '<dt>'.$name.'</dt>';
					$output .= '<dd>'.$this->format_rule($rules).'</dd>';	
				}
				$output .= '</dl>';
			}
			if(isset($value['get_params'])){
				$output .= '<h2>Get Parameters</h2>';
				$output .= '<dl class="dl-horizontal">';
				foreach ($value['get_params'] as $name => $rules) {
					$output .= '<dt>'.$name.'</dt>';
					$output .= '<dd>'.$this->format_rule($rules).'</dd>';	
				}
				$output .= '</dl>';
			}



			$output .= '</div>';
		}
		return $output;
	}	

	function format_rule($rule){
		$output = '';
		if(preg_match("/\[(.*?)\]/", $rule,$regx)){
			$output .= '<i>'.$regx[1]."</i>";
			$rule = str_replace($regx[0], '', $rule);
		}
		$parts = preg_split("/\s+/",$rule);
		foreach ($parts as $key => $value) {
			$output .= "<span class='label label-info'>".$value."</span>";
		}
		return $output;


	}

	function navigation(){
		$output = '<div>';
		foreach ($this->groups as $groupname => $groupitems) {
			$output .= '<h2>'.$groupname.'</h2>';
			$output .= '<ul  class="nav nav-pills nav-stacked" >';
			foreach ($groupitems as $key => $value) {
				if(isset($value['url'])){
					$output .= '<li  class="apinav" id="item'.$value['_id'].'_btn"><a href="#" onclick="showItem(\'#item'.$value['_id'].'\')">';
					
					$output .= $this->method_label(ex_el($value['http_method'],'???'));
					$output .= ' ';
					$output .= $this->shortUrl($value['url']);
					$output .= '</a></li>';
				}
			}
			$output .= '</ul>';
		}
		$output .= '</div>';
		return $output;
	}

	function render(){
		$output = '';
		$output .= '<html><head>';
		$output .= '<title>API Documentation</title>';
		$output .= '<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">';
		$output .= '<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>';
		$output .= '<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>';
		$output .= '</head>';

		$output .= '<div class="row">';
		$output .= '<div class="col-md-4">'.$this->navigation().'</div>';
		$output .= '<div class="col-md-8">'.$this->content().'</div>';
		$output .= '';

		$output .= '</div>';
		$output .= '<script>';
		$output .= 'function showItem(name){
			console.log(name)
			$(".apinav").removeClass("active");
			$(name+"_btn").addClass("active");
			$(".apiitem").hide();
			$(name).show()
		}';

		$output .= '$(function (){
			var n = $(".apinav:first").attr("id");
			showItem("#"+n.substring(0,n.length-4));

		})';
		$output .= '</script>';
		return $output;
	}

	// utilities
	function shortUrl($var){
		preg_match("/^https?:\\/\\/.*?(\\/.*)$/", $var,$reg);
		return empty($reg[1]) ? $var : $reg[1] ;
	}
	function method_label($method){
		$output = '';
		if($method == 'POST')
		$output .= '<span class="label label-warning">';
		elseif($method == 'PUT')
		$output .= '<span class="label label-info">';
		elseif($method == 'DELETE')
		$output .= '<span class="label label-danger">';
		else
		$output .= '<span class="label label-success">';

		$output .= $method;
		$output .= '</span>';
		return $output;
	}
}

// exists or else
function ex_el(&$var,$else=''){
	if(isset($var)){
		return $var;
	}else{
		return $else;
	}
}