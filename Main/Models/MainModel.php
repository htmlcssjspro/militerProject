<?php

namespace Main\Models;

use Militer\mvcCore\Model\aPageModel;

class MainModel extends aPageModel
{
    public $headerNav;

    public $guest = false;
    public $user = false;


    public function __construct()
    {
        parent::__construct();

        $this->getHeaderData();
        $this->getFooterData();
        $this->getAsideData();
    }


    public function getHeaderData()
    {
        $sql = "SELECT `label`, `page_url` FROM {$this->sitemapTable} WHERE `header_nav`=1";
        $headerNav = $this->PDO->query($sql)->fetchAll();
        $this->headerNav = $this->escapeOutput($headerNav);
    }

    public function getFooterData()
    {
    }

    public function getAsideData()
    {
    }





    private function escapeOutput(array &$data)
    {
        \array_walk_recursive($data, function (&$item) {
            $item = \htmlspecialchars($item, \ENT_QUOTES | \ENT_HTML5 | \ENT_SUBSTITUTE, 'UTF-8');
        });
        return $data;
    }
}
