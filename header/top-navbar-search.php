<?php
global $CDWFunc;
?>
<form id="navbar-search" method="GET" action="<?php echo $CDWFunc->getUrl('index', 'search'); ?>" class="navbar-form search-form">
    <input name="search" value="" class="form-control" placeholder="Tìm kiếm..." type="text">
    <button type="submit" class="btn btn-default"><i class="icon-magnifier"></i></button>
</form>