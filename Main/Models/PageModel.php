<?php

namespace Main\Models;

use Militer\mvcCore\Model\aPageModel;

class PageModel extends aPageModel
{


    public function __construct()
    {
        parent::__construct();
        $this->sitemapTable  = self::MAIN_SITEMAP_TABLE;
        $this->layoutsTable  = self::MAIN_LAYOUTS_TABLE;
        $this->sectionsTable = self::MAIN_SECTIONS_TABLE;
        $this->views = \MAIN_VIEWS;
    }


}
