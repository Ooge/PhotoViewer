<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Page {
    protected $CI;

    private $_stylesheets = array();
    private $_scripts = array();
    private $_title = '';
    private $_content = '';
    private $_container;

    public function __construct($stylsheets, $scripts, $title, $content, $container = true) {
        $this->_stylesheets = $stylsheets;
        $this->_scripts = $scripts;
        $this->_title = $title;
        $this->_content = $content;
        $this->_container = $container;
    }

    public function stylesheets() {
        if (count($this->_stylesheets) == 0) {
            return '';
        }
        $sheets = '';
        foreach ($this->_stylesheets as $sheet) {
            $sheets .= '<link rel="stylesheet" type="text/css" href="' . $sheet . '" />';
        }
        return $sheets;
    }

    public function scripts() {
        if (count($this->_scripts) == 0) {
            return '';
        }
        $scripts = '';
        foreach ($this->_scripts as $script) {
            $scripts .= '<script src="' . $script . '" type="text/javascript"></script>';
        }
        return $scripts;
    }

    public function title() {
        return $this->_title;
    }

    public function content() {
        return $this->_content;
    }

    public function has_container() {
        return $this->_container;
    }
}