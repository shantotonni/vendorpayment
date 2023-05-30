<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo $head; ?>
</head>

<body sarah-navigation-type="vertical" sarah-nav-placement="left" theme-layout="wide-layout" theme-bg="bg1" >
    <div id="sarahapp-wrapper" class="sarah-hide-lpanel" sarah-device-type="desktop">
        <?php echo $header; ?>
        <div id="sarahapp-container" sarah-color-type="lpanel-bg2" sarah-lpanel-effect="shrink">
            <?php echo $menu; ?>   			
			<!-- Section  -->
            <?php echo $content; ?>			
        </div>
    </div>
    <?php echo $footer; ?>	
</body>
</html>
