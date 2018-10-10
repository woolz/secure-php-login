<?php
namespace SecurePHPLogin;

class pagination extends sys_info {


    private $pages;
    private $current_page;
    private $next_page;
    private $previous_page;
    public $html_pagination;

    public function __construct(int $current_page, int $pages) {

        $this->pages = $pages;
        $this->current_page = $current_page;
        $this->next_page = $this->current_page + 1;
        $this->previous_page = $this->current_page - 1;
        

        $html = "<ul class=\"pagination\">";
        if ($this->current_page != 1) {
           $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$this->previous_page}/\">&laquo; Previous</a></li>";
        }

         if ($this->current_page-2 == 0 ) {
             $one = $this->current_page - 1;
             $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$one}/\">{$one}</a></li>";

        } else if ($this->current_page-2 >= 1 ) {
             $one = $this->current_page - 1;
             $two = $this->current_page - 2;
             $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$two}/\">{$two}</a></li>";
             $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$one}/\">{$one}</a></li>";

        }

         $html .= "<li class=\"page-item active\"><a class=\"page-link\" href=\"{$this->url}/page/{$this->current_page}/\">{$this->current_page}</a></li>";



        if ($this->current_page+2 < $this->pages) {
             $one = $this->current_page + 1;
             $two = $this->current_page + 2;
             $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$two}/\">{$one}</a></li>";
             $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$two}/\">{$two}</a></li>";

        } else if ($this->current_page+1 < $this->pages) {
             $one = $this->current_page - 1;
             $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$one}/\">{$one}</a></li>";

        }


        
        
        if ($this->current_page < $this->pages) {
            $html .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->url}/page/{$this->next_page}/\">Next &raquo;</a></li>";
        }

         $html .= "</ul>";

        $this->html_pagination = $html;

    }



    public function __destruct() {
        $this->html_pagination = null;
        $this->pages = null;
        $this->current_page = null;
        $this->next_page = null;
        $this->previous_page = null;
    }
}
