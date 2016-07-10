<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 10.07.16
 * Time: 17:04
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PaginationHelper extends AbstractHelper
{
    /**
     * Current page number
     * @var int
     */
    private $page;

    const PER_PAGE = 10;

    /**
     * @param int $countResults
     * @param int $pageNumber
     * @param string $baseUrl
     * @param int $resultsPerPage
     * @return string
     */
    public function __invoke($countResults, $pageNumber, $baseUrl, $resultsPerPage = self::PER_PAGE)
    {
        $this->page = $pageNumber;

        $pages = ceil($countResults / $resultsPerPage);

        return $this->createPager($pages, $baseUrl);
    }

    /**
     * @param int $pages
     * @param string $baseUrl
     * @return string
     */
    private function createPager($pages, $baseUrl)
    {
        $result = '';

        if (1 === $pages) {
            return $result;
        }

        if ($this->page != 1) {
            $result .= sprintf('<a href="%spage/1"><</a>', $baseUrl);
        }

        $pageCount = 1;

        while ($pageCount <= $pages) {
            $selectedPage = '';
            if ($pageCount == $this->page) {
                $selectedPage = 'class="selected-page"';
            }
            $result .= sprintf('<a href="%spage/%d" %s>%d</a>', $baseUrl, $pageCount, $selectedPage, $pageCount);
            $pageCount++;
        }

        if ($this->page != $pages) {
            $result .= sprintf('<a href="%spage/%d">></a>', $baseUrl, $pages);
        }

        return $result;
    }
}

