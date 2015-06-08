<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// Our Page class which loads each individual page
class Page {
    // CI variable
    protected $CI;

    // Private variables storing data such as stylesheets, scripts, titles, content and a container on each page
    private $_stylesheets = array();
    private $_scripts = array();
    private $_title = '';
    private $_content = '';
    private $_container;

    // Constructor which assigns the above variables.
    public function __construct($stylsheets, $scripts, $title, $content, $container = true) {
        $this->_stylesheets = $stylsheets;
        $this->_scripts = $scripts;
        $this->_title = $title;
        $this->_content = $content;
        $this->_container = $container;
    }

    // Return the stylesheets for this page
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
    // Return the scripts for this page
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
    // Return the title for this page
    public function title() {
        return $this->_title;
    }
    // Return the content of this page.
    public function content() {
        return $this->_content;
    }
    // Do we want to load the default container, or use our own container.
    public function has_container() {
        return $this->_container;
    }
}
