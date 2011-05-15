<?php
/*
Plugin Name: HTML Helpers
Plugin URI: http://jetdog.biz/projects/wp-html-helpers
Description: Simple HTML rendering API for WordPress
Version: 0.2
Author: Nikolay Karev
Author URI: http://jetdog.biz/
*/

/*
Copyright (C) Nikolay Karev, karev.n@gmail.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/



if (!(function_exists('h'))){

	/* HTML Generator functions */
	function _h($tag, $attributes = array(), $content = null, $close = null){
		if (!is_array($attributes)){
			$close = $content;
			$content = $attributes;
			$attributes = array();
		}
		if (is_bool($content)){
			$close = $content;
			$content = null;
		}
		if ($content === null && $close === null)
			$close = true;
		else if ($close === null)
			$close = $content != null;
		else
			$close = $close === null ? true : $close;
		$parts = explode(' ', $tag, 2);
		$tag = $parts[0];
		$additional_attrs = count($parts) > 1 ? $parts[1] : null;
		return html_tag_start($tag . ($additional_attrs ? " " . $additional_attrs : ''), $attributes) . 
			$content . 
			($close ? html_tag_end($tag) : '');
	}

	function h($tag, $attributes = array(), $content = null, $close = null){
		echo _h($tag, $attributes, $content, $close);
	}

	function html_tag_start($tag, $attributes = array()){
		$attr_string = '';
		foreach( $attributes as $key => $val)
			$attr_string .= " $key=\"" . esc_attr(is_array($val) ? join(' ', $val) : $val) . "\"";
		return "<$tag $attr_string>";
	}

	function html_tag_end($tag){
		return "</{$tag}>";
	}


	function _img($src, $alt = '', $attributes = array()){
		if ($alt) $attributes['alt'] = $alt;
		$attributes['src'] = preg_match('/\//', $src) ? $src : get_bloginfo('template_url') . "/images/$src";
		return _h('img', $attributes);
	}

	function img($src, $alt = '', $attributes = array()){
		echo img($src, $alt, $attributes);
	}

	function _link_to($text, $url = '', $attributes = array()){
		$attributes['href'] = $url;
		return _h('a', $attributes, $text);
	}

	function link_to($text, $url = '', $attributes = array()){
		echo _link_to($text, $url, $attributes);
	}

	function select($name, $values = array(), $selected = null, $attributes = array(),
	$options = array()){
		echo _select($name, $values, $selected, $attributes, $options);
	}

	function _select($name, $values = array(), $selected = null, $attributes = array(), 
	$options = array()){
		$res = '';
		if (isset($options['empty']) && $options['empty']){
			$res .= _h('option', array('value' => null), $options['empty']);
		}
		foreach($values as $arr){
			$text = $arr[0];
			$value = $arr[1];
			$attrs = array();
			if ($value && $value == $selected || $text == $selected) $attrs['selected'] = 'selected';
			if ($value)
				$attrs['value'] = $value;
			$res .= _h("option", $attrs, $text);      
		}
		$attributes['name'] = $name;
		if (empty($attributes['id']))
			$attributes['id'] = sanitize_title_with_dashes($attributes['name']);
		$res = _h('select', $attributes, false) . $res . html_tag_end('select');
		return $res;
	}

	function text_field($name, $value = null, $attributes = array()){
		echo _text_field($name, $value, $attributes);
	}

	function _text_field($name, $value = null, $attributes = array()){
		$attributes['value'] = $value;
		$attributes['name'] = $name;
		$attributes['type'] = 'text';
		if (empty($attributes['id']))
			$attributes['id'] = sanitize_title_with_dashes($attributes['name']);
		return _h('input', $attributes);
	}

	function password_field($name, $value = null, $attributes = array()){
		echo _password_field($name, $value, $attributes);
	}

	function _password_field($name, $value = null, $attributes = array()){
		$attributes['value'] = $value;
		$attributes['name'] = $name;
		$attributes['type'] = 'password';
		if (empty($attributes['id']))
			$attributes['id'] = sanitize_title_with_dashes($attributes['name']);
		return _h('input', $attributes);
	}

	function hidden_field($name, $value = null, $attributes = array()){
		echo _hidden_field($name, $value, $attributes);
	}

	function _hidden_field($name, $value = null, $attributes = array()){
		$attributes['value'] = $value;
		$attributes['name'] = $name;
		$attributes['type'] = 'hidden';
		return _h('input', $attributes);
	}

	function checkbox($name, $value = null, $checked = false, $attributes = array()){
		echo _checkbox($name, $value, $checked, $attributes);
	}

	function _checkbox($name, $value = null, $checked = false, $attributes = array()){
		if ($checked)
			$attributes['checked'] = 'checked';
		$attributes['type'] = 'checkbox';
		$attributes = array_merge($attributes, array('name' => $name, 'value' => $value));
		if (empty($attributes['id']))
			$attributes['id'] = sanitize_title_with_dashes($attributes['name']);
		return _h('input', $attributes);
	}

	function radiobutton($name, $value = null, $checked = false, $attributes = array()){
		echo _radiobutton($name, $value, $checked, $attributes);
	}
	
	function _radiobutton($name, $value = null, $checked = false, $attributes = array()){
		if ($checked)
			$attributes['checked'] = 'checked';
		$attributes['type'] = 'radio';
		$attributes = array_merge($attributes, array('name' => $name, 'value' => $value));
		if (empty($attributes['id']))
			$attributes['id'] = sanitize_title_with_dashes($attributes['name']);
		return _h('input', $attributes);
	}

	function textarea($name, $value = '', $attributes = array(), $escape = true){
		echo _textarea($name, $value, $attributes, $escape);
	}
	
	function _textarea($name, $value, $attributes = array(), $escape = true){
		$attributes['name'] = $name;
		if (empty($attributes['id']))
			$attributes['id'] = sanitize_title_with_dashes($attributes['name']);
		return _h('textarea', $attributes, $escape ? esc_html($value) : $value, true);
	}

	function label($attrs = array(), $content){
		echo _label($attrs, $content);
	}

	function _label($attrs = array(), $content){
		return _h('label', $attrs, $content);
	}

	/* "The" functions */
	function get_the_post_meta($names, $options = array()){
		global $wp_query, $id;
		if (!isset($options['separator']) && is_array($names)) $options['separator'] = ' ';
		if (!is_array($names)) $options['separator'] = '';
		if (is_string($names)) $names = array($names);
		$values = array();
		foreach($names as $name) $values []= get_post_meta($id, $name, true);
		return join($options['separator'], apply_filters('get_the_post_meta_values', $values, $options));
	}

	function the_post_meta($names, $options = array()){
		$meta = get_the_post_meta($names, $options);
		echo $meta;
		if (isset($options['break_after']) && $options['break_after'] && !empty($meta)) echo "<br>";
	}

	function the_post_meta_tag($name, $tag = 'div', $attributes = array(), $options = array()){
		$meta = get_the_post_meta($name, $options);
		if (!empty($meta)){
			echo _h($tag, $attributes, apply_filters('the_content', get_the_post_meta($name, $options)));
		}
	}

	function the_post_meta_content($name, $options = array()){
		echo apply_filters('the_content', get_the_post_meta($name, $options));
	}

	function has_the_post_meta($name){
		$meta = get_the_post_meta($name);
		return !empty($meta);
	}

	function wp_enqueue_conditional_style($id, $path, $condition) {
		global $wp_styles;
		wp_enqueue_style($id, $path);
		$wp_styles->add_data($id, "conditional", $condition);
	}


	function link_or_text($title, $url, $options = array()){
		$options['href'] = $url;
		if (!empty($url))
			tag_around('a', $title, $options);
		else
			echo $title;
	}
	
	/* Helper functions */
	
	function collection2hash($collection, $key_field, $value_field){
		$result = array();
		foreach($collection as $obj){
			$result[$obj->$key_field] = $obj->$value_field;
		}
		return $result;
	}

	function collection2options($collection, $key_field, $value_field){
		$result = array();
		foreach($collection as $obj){
			$result []= array($obj->$value_field, $obj->$key_field);
		}
		return $result;
	}

	function hash2options($hash){
		$options = array();
		foreach($hash as $key => $value){
			$options []= array($value, $key);
		}
		return $options;
	}

	function array2options($array){
		$options = array();
		foreach($array as $item){
			$options []= array($item, null);
		}
		return $options;
	}
}