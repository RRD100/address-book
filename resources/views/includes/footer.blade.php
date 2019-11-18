<?php
    $current_year = date('Y');
    $years = ( $current_year > 2019 ) ? '2019 - ' . $current_year : '2019';
?>
<div id="copyright" class="flex-center"><div class="content">© Copyright {{ $years }} Realm Digital</div></div>