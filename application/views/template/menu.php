<?php
//$usermenu = unserialize(file_get_contents("assets/temp/usermenu_" . $userid . ".tmp"));
$usermenu = "";
$segment = $this->uri->segment(1); 
$segment2 = $this->uri->segment(2);
?>
<aside id="sarah-left-panel" sarah-position-type="absolute">
<div class="profile-box">
    <div class="media">                          
        <div class="media-body">
            <h4 class="media-heading">Welcome <?php echo $emp_name; ?></h4>
            <small><?php echo $designation; ?></small>
        </div>
    </div>
</div>
<ul class="nav panel-list">
    <li class="nav-level">Navigation</li>
    <li <?php if($segment == ''){ ?>class="active" <?php } ?>>
        <a href="<?php echo base_url(); ?>">
            <i class="fa fa-tachometer"></i>
            <span class="menu-text">Dashboard</span>
            <span class="selected"></span>
        </a>
    </li>  
    

    <?php 
    //var_dump($usermenu);
    function filtersubmenu($val){
        global $menuname;
        if($val['MenuName'] == $menuname){
            return true;
        }else{
            return false;
        }
    }
    global $menuname;    
    if(!empty($usermenu)){
        foreach($usermenu as $row){ 
            if($menuname != $row['MenuName']){     
                $menuname = $row['MenuName'];
                $submenu = array_filter($usermenu,"filtersubmenu")
            ?>
            <li class="sarah-has-menu <?php if($segment == $row['MenuActiveLink']){ ?> active <?php } ?>">
                <a href="javascript:void(0)">
                    <i class="fa fa-pencil-square-o"></i>
                    <span class="menu-text"><?php echo $row['MenuName']; ?></span>
                    <span class="selected"></span>
                </a>
                <ul class="sarah-sub-menu"> 
                    <?php 
                    if(!empty($submenu)){ 
                        foreach($submenu as $row1){
                            ?>
                            <li <?php if($segment == $row['Link']){ ?>class="active" <?php } ?>>
                                <a href="<?php echo base_url().$row1['Link']; ?>">
                                    <span class="menu-text"><?php echo $row1['SubMenuName']; ?></span>
                                    <span class="selected"></span>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                    
                </ul>
            </li>
            <?php                
            }
        }
    }
    ?>
    
    <li class="sarah-has-menu <?php if($segment == 'report'){ ?> active <?php } ?>">
        <a href="javascript:void(0)">
            <i class="fa fa-user-md"></i>
            <span class="menu-text">Report</span>
            <span class="selected"></span>
        </a>
        <ul class="sarah-sub-menu">
            <li <?php if($segment == 'profitlossstatement'){ ?>class="active" <?php } ?>>
                <a href="<?php echo base_url(); ?>report/profitlossstatement">
                    <span class="menu-text">Profit & Loss Statement</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li <?php if($segment == 'businessperformancereport'){ ?>class="active" <?php } ?>>
                <a href="<?php echo base_url(); ?>report/businessperformancereport">
                    <span class="menu-text">Business Performance Report</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li <?php if($segment == 'businesstrendperformancere'){ ?>class="active" <?php } ?>>
                <a href="<?php echo base_url(); ?>report/businesstrendperformancere">
                    <span class="menu-text">Business Trend Performance</span>
                    <span class="selected"></span>
                </a>
            </li>
        </ul>
    </li>

    <li class="sarah-has-menu <?php if($segment == 'setup'){ ?> active <?php } ?>">
        <a href="javascript:void(0)">
            <i class="fa fa-user-md"></i>
            <span class="menu-text">Setup</span>
            <span class="selected"></span>
        </a>
        <ul class="sarah-sub-menu">
            <li <?php if($segment == 'setupstatement'){ ?>class="active" <?php } ?>>
                <a href="<?php echo base_url(); ?>setup/setupstatement">
                    <span class="menu-text">Data generate and Import</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li <?php if($segment == 'setupreport'){ ?>class="active" <?php } ?>>
                <a href="<?php echo base_url(); ?>setup/setupreport">
                    <span class="menu-text">Setup Report</span>
                    <span class="selected"></span>
                </a>
            </li>
        </ul>
    </li>

    <li>
        <a href="<?php echo base_url(); ?>authenticate/logout">
            <i class="fa fa-sitemap"></i>
            <span class="menu-text">Log Out</span>
            <span class="selected"></span>
        </a>
    </li>
    
</ul>
</aside>