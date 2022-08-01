<div class="page-header page-header-light">
    <div class="page-header-content header-elements-md-inline">
        <div class="page-title d-flex">
            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>

    </div>

    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        
        <div class="d-flex">
            <div class="breadcrumb">
            <?php foreach ($this->uri->segments as $segment):?>
            <?php
                $url = substr($this->uri->uri_string, 0, strpos($this->uri->uri_string, $segment)). $segment;
                $is_active = $url == $this->uri->uri_string;
            ?>
                <li class="breadcrumb-item <?php echo $is_active ? 'active': '' ?>">
                    <?php if($is_active): ?>
                        <?php echo ucfirst($segment) ?>
                    <?php else: ?>
                        <a href="<?php echo base_url($url) ?>"><?php echo ucfirst($segment) ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach;?>
            </div>

            <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        
    </div>
</div>