<div class="pagination">
<?php
$leftArrow = <<<SVG
        <svg fill="currentColor" width="16" height="16">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
    SVG;

$rightArrow = <<<SVG
        <svg fill="currentColor" width="16" height="16">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
    SVG;

$currentPage = $this->paginator->getCurrentPage();
$perPage = $this->paginator->getPerPage();
$pagesNumber = $this->paginator->getPagesNumber();
        
if ($pagesNumber > 0) {
    echo 1 === $currentPage ? $this->span($leftArrow) : $this->link($currentPage - 1, $perPage, $fields, $leftArrow);
    
    for($i = 1; $i <= $pagesNumber; $i++) {
        if (
                $i <= 4 ||
                ($currentPage - 3 <= $i && $currentPage + 3 >= $i) ||
                $i >= $currentPage - 3
        ):
            echo $i === $currentPage ? $this->span($i) : $this->link($i, $perPage, $fields);
        elseif ($currentPage - 4 === $i): 
            echo '<span class="hellip">&hellip;</span>';
        elseif ($currentPage + 4 === $i): 
            echo '<span class="hellip">&hellip;</span>';
        endif;
    }
        
    echo $pagesNumber === $currentPage ? $this->span($rightArrow) : $this->link($currentPage + 1, $perPage, $fields, $rightArrow);
} else {
    echo <<<HTML
            <div>Ничего не найдено.<div>
        HTML;
}
?>
</div>
