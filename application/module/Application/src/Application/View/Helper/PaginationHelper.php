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

    private  $queryString = '';

    const PER_PAGE = 10;

    const DEFAULT_NUM_PAGE = 1;

    /**
     * @param int $countResults
     * @param int $pageNumber
     * @param string $baseUrl
     * @param $query
     * @param int $resultsPerPage
     * @return string
     */
    public function __invoke($countResults, $pageNumber, $baseUrl, $query, $resultsPerPage = self::PER_PAGE)
    {
        $this->page = (int)$pageNumber;

        $this->queryString = $query;

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

        if (self::DEFAULT_NUM_PAGE !== $this->page) {
            $numPage = $this->getNumPageWithQuery();
            $result .= sprintf('<a href="%spage/%s"><</a>', $baseUrl, $numPage);
        }

        $pageCount = 1;

        while ($pageCount <= $pages) {
            $selectedPage = '';
            if ($pageCount == $this->page) {
                $selectedPage = 'class="selected-page"';
            }

            $query = $this->getNumPageWithQuery($pageCount);

            $result .= sprintf('<a href="%spage/%d" %s>%d</a>', $baseUrl, $query, $selectedPage, $pageCount);
            $pageCount++;
        }

        if ($this->page != $pages) {
            $query = $this->getNumPageWithQuery($pages);
            $result .= sprintf('<a href="%spage/%s">></a>', $baseUrl, $query);
        }

        return $result;
    }

    /**
     * @param int $page
     * @return string
     */
    private function getNumPageWithQuery($page = self::DEFAULT_NUM_PAGE)
    {
        if ($this->queryString) {
            $page = $page . '?' . $this->queryString;
        }

        return $page;
    }
}

