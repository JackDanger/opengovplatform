<?php
    global $base_url;
	if($base_url == "http://demodatacms.nic.in" || $base_url == "https://demodatacms.nic.in") { drupal_goto("user"); }
	global $theme_key;
    $themes = list_themes();
    $theme_object = $themes[$theme_key];
    $site_var = variable_get('site_country','');
    $site_node = node_load($site_var);
	$theme_name=$theme_object->name;
    $flag_img=substr($site_node->field_website_header_image[0]['filepath'],strpos($site_node->field_website_header_image[0]['filepath'],"files/"));
 
    if(variable_get('file_downloads','') == 2) {
		$site_img = $base_url."/system/".$flag_img;
	} else {
		$site_img = $base_url.'/'.$site_node->field_website_header_image[0]['filepath'];
	}
	$query=db_query('select * from node,content_type_theme_file_uploader where type = \'theme_file_uploader\' and node.nid=content_type_theme_file_uploader.nid and field_theme_list_select_value = \''.$theme_name.'\'');
    $portal_url = $site_node->field_country_portal_url[0]['url'];
    $gov_name = $site_node->field_union_govt_name[0]['value'];
	$color=array();
    $contrast=array();
    $css_contrast= array();
	$css_color=array();
	$theme_file_path=file_directory_path().'/themes/';
	while($result=db_fetch_object($query))
    {
	if($result->field_theme_type_value=='color')
	{     
		$color[]=$result->title;
		$image_result=db_query("select filename from content_type_theme_file_uploader T LEFT JOIN files F on T.field_theme_logo_image_fid=F.fid where T.nid=$result->nid");
		$image=db_fetch_object($image_result);
		$image_ext=explode('.',$image->filename);
        $image_ext=$image_ext[count($image_ext)-1];
        if(variable_get('file_downloads','') == 2) {	
			$image_color[$result->title]=$base_url.'/system/files/themes/'.$theme_name.'/css/color/'.$result->title.'/'.$result->title.'.'.$image_ext;
			$css_color[$result->title]=$base_url.'/system/files/themes/'.$theme_name.'/css/color/'.$result->title.'/'.$result->title.'.css';
		} else {
			$image_color[$result->title]=$base_url.'/'.$theme_file_path.$theme_name.'/css/color/'.$result->title.'/'.$result->title.'.'.$image_ext;
			$css_color[$result->title]=$base_url.'/'.$theme_file_path.$theme_name.'/css/color/'.$result->title.'/'.$result->title.'.css';
		}
	}
	else
	{
		$contrast[]=$result->title;
		$image_result=db_query("select filename from content_type_theme_file_uploader T LEFT JOIN files F on T.field_theme_logo_image_fid=F.fid where T.nid=$result->nid");
		$image=db_fetch_object($image_result);
        $image_ext=explode('.',$image->filename);
        $image_ext=$image_ext[count($image_ext)-1];
       if(variable_get('file_downloads','') == 2) {	
			$image_contrast[$result->title]=$base_url.'/system/files/themes/'.$theme_name.'/css/contrast/'.$result->title.'/'.$result->title.'.'.$image_ext;
			$css_contrast[$result->title]=$base_url.'/system/files/themes/'.$theme_name.'/css/contrast/'.$result->title.'/'.$result->title.'.css';
		} else {
			$image_contrast[$result->title]=$base_url.'/'.$theme_file_path.$theme_name.'/css/contrast/'.$result->title.'/'.$result->title.'.'.$image_ext;
			$css_contrast[$result->title]=$base_url.'/'.$theme_file_path.$theme_name.'/css/contrast/'.$result->title.'/'.$result->title.'.css';
		}
	}
    }
	# Array of the color scheme css file
	$cssArr = array('blue'=>$base_url."/".path_to_theme()."/".'blue.css',
					'pink'=>$base_url."/".path_to_theme()."/".'pink.css',
					'green'=>$base_url."/".path_to_theme()."/".'green.css',
					'change'=>$base_url."/".path_to_theme()."/".'high.css');
	$cssArr=array_merge($cssArr,$css_color,$css_contrast);
	if(!empty($_GET['switch']) || !empty($_GET['contrast'])){ 
		if(!empty($_GET['switch'])){ #Cookies set via URL when javascript is disabled.
			setCookie("_SERVER_SIDE_THEME_COLOR",$_GET['switch']);
			setCookie("_SERVER_SIDE_CONTRAST",'false');
		}
		if(!empty($_GET['contrast'])){ # If the contrast mode is enabled.
			setCookie("_SERVER_SIDE_CONTRAST",$_GET['contrast']);
		}
		if(!empty($_GET['refURL'])){
			Header("Location:".$_GET['refURL']);
			exit;
		}	
	}	
	$refURL = urlencode($_SERVER['REQUEST_URI']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php print $language->language; ?>" xml:lang="<?php print $language->language; ?>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<meta http-equiv="content-language" content="<?php echo $language->language;?>" />
<title>Home | Welcome to ODP</title>
<?php print $head ?>
<?php print $styles ?>
<?php

	foreach($cssArr as $key=> $values){
		echo "<link href=\"".$values."\" rel=\"alternate stylesheet\" type=\"text/css\" media=\"screen\" title=\"".$key."\" />\n";
	}
	
	if(isset($_COOKIE['_SERVER_SIDE_THEME_COLOR']) && (!isset($_COOKIE['_SERVER_SIDE_CONTRAST']) || $_COOKIE['_SERVER_SIDE_CONTRAST']=='false')){
		echo "<noscript>\n";
		echo "<link href=\"".$cssArr[$_COOKIE['_SERVER_SIDE_THEME_COLOR']]."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"  />";
		echo "</noscript>\n";
	}elseif(isset($_COOKIE['_SERVER_SIDE_CONTRAST'])){
		echo "<noscript>\n";
		echo "<link href=\"".$cssArr[$_COOKIE['_SERVER_SIDE_CONTRAST']]."\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\"  />";
		echo "</noscript>\n";
	}
?>
<?php print $scripts; ?>
<!--[if gte IE 9]>
  <style type="text/css">
    .gradient {
       filter: none;
    }
  </style>
<![endif]-->
</head>

<body role="application">
	<span>
		<a class="skipnav" accesskey="1" href="#mainNav">Skip to navigation</a>
		<a class="skipnav" accesskey="2" href="#mainContent">Skip to main content</a>
	</span>
<div id="outerContainer">	
	<div id="mainContainer">
    	<!--top panel start here -->
        <div id="topPanel">
            <!--goi -->
                <div style="float: left; width: auto;"><div class="goi"><div class="gov"><a target="_blank" class="country-flag" title="<?php echo $gov_name; ?>" href="<?php echo $portal_url; ?>"><img style="float: left; width: auto; padding-right:7px;" src="<?php echo $site_img; ?>" alt="Country Flag" width="auto" height="20" /><?php echo $gov_name; ?></a></div><span class="ext"></span>&nbsp;</div>
</div>
            <!--goi -->
            
            <!--accessibility panel start here -->
            <div class="accessPan">
            	<ul>
                	<!--color options -->
                	<!--<li><a href="#content" title="Skip to Main Content">Skip to Main Content</a></li> -->
                    <li class="colorOptions">
						<a href="<?php echo $base_url."?switch=style&amp;refURL=".$refURL;?>"   onclick="chooseStyle('grey', 60,'standard');" title="Default Theme"><img src="<?php echo $base_url."/".path_to_theme();?>/images/grey.jpg" alt="Grey"/></a>
					<!--	<a href="<?php echo $base_url."?switch=blue&amp;refURL=".$refURL;?>"  onclick="chooseStyle('blue', 60,'standard');" title="Blue Theme"><img src="<?php echo $base_url."/".path_to_theme();?>/images/blue.jpg" alt="Blue"/></a>
						<a href="<?php echo $base_url."?switch=pink&amp;refURL=".$refURL;?>"   onclick="chooseStyle('pink', 60,'standard');" title="Pink Theme"><img src="<?php echo $base_url."/".path_to_theme();?>/images/pink.jpg" alt="Pink"/></a>
						<a href="<?php echo $base_url."?switch=green&amp;refURL=".$refURL;?>" onclick="chooseStyle('green', 60,'standard');" title="Green Theme"><img src="<?php echo $base_url."/".path_to_theme();?>/images/green.jpg" alt="Green"/></a>   -->          
						<?php foreach($color as $color_title) echo '<a title="'.$color_title.'" href="'.$base_url."?switch=".$color_title."&amp;refURL=".$refURL.'" onclick="chooseStyle(\''.$color_title.'\', 60,\'standard\')"><img  alt="'.$color_title.'" width="14" height="12" src="'.$image_color[$color_title].'"/>  </a>';    ?>
			</li>
					<!--color options -->
                    <li class="resize">
						<?php print $text_resize; ?>
					</li>
                                        
                    <!--color contrast options -->
                    <li class="contrast">
                    <!--	<a href="<?php echo $base_url."?contrast=change&amp;refURL=".$refURL;?>" class="dark" onclick="chooseStyle('change', 60,'contrast');" title="High Contrast"><img src="<?php echo $base_url."/".path_to_theme();?>/images/texthighContrast.gif" alt="High Contrast View"/></a> -->
                        <a href="<?php echo $base_url."?contrast=none&amp;refURL=".$refURL;?>" class="light" onclick="chooseStyle('none', 60,'contrast');" title="Standard Contrast"><img src="<?php echo $base_url."/".path_to_theme();?>/images/textNormal.gif" alt="Standard Contrast View"/></a>
						<?php foreach($contrast as $contrast_title) echo '<a title="'.$contrast_title.'" href="'.$base_url."?contrast=".$contrast_title."&amp;refURL=".$refURL.'" onclick="chooseStyle(\''.$contrast_title.'\', 60,\'contrast\')"><img alt="'.$color_title.'" width="21" height="18" src="'.$image_contrast[$contrast_title].'"/>  </a>';    ?>
			</li>
                    <!--color contrast options -->
                    
                	<li class="feedback"><a href="<?php echo $base_url;?>/feedback" title="Feedback">Feedback</a></li>
					
                	<li class="rssfeeds">
					  <a href="<?php echo $base_url;?>/rssfeeds" title="RSS Feeds" class="rss">
						<img src="<?php echo $base_url."/".path_to_theme();?>/images/icon-rss.png" alt="RSS Feed" title="RSS Feed" />
					  </a>
					</li>
                	
                    <li class="share">
                    <!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style ">
                            <a class="addthis_button_compact add-this-link" href="http://www.addthis.com/bookmark.php" title="Bookmark and Share">Share+</a>
                        </div>
                    <!-- AddThis Button END -->
                    <?php //print $header_options; ?>
					</li>
                    <!--flags options -->
                    <li class="flags">
						<?php print $header_flags; ?>
                    </li>
                    <!--flags options -->
					<?php 
					global $user;
					if ($user->uid) {
					echo "<li><a href='logout'>Log out</a></li>";
					}
					?>
                </ul>
            </div>
            <!--accessibility panel end here -->
        </div>
    	<!--top panel end here -->
        <script type="text/javascript">
			$(".colorOptions a, .contrast a").attr('href','#switch');
		</script>
        <!--logo panel start here -->
        <div id="logoPanel">
			<!--logo start here -->
			<div class="logo">
		    <?php
	        // Prepare header
	        $site_fields = array();
	        if ($site_name) {
	        	$site_fields[] = check_plain($site_name);
	        }
	        if ($site_slogan) {
	        	$site_fields[] = check_plain($site_slogan);
	        }
	        $site_title = implode(' ', $site_fields);
	        if ($site_fields) {
	        	$site_fields[0] = '<span>'. $site_fields[0] .'</span>';
	        }
	        $site_html = implode(' ', $site_fields);
	        
	        if ($logo || $site_title) {
	        	print '<a href="'. check_url($front_page) .'" title="'. $site_title .'">';
	        if ($logo) {
	        	print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo-image" />';
	        }
	        print '</a>';
	        }
	        ?>
			</div>
			<!--logo end here -->

            <!--search panel start here -->
            <div class="searchPan" role="search">
            	<?php print $search_box; ?>
            </div>
            <!--search panel end here -->
        </div>
        <!--logo panel end here -->
        
        <!--header panel start here -->
        <div id="headerPanel">
        	<!--header start here -->
            <div id="header">
                <?php if($banner) { ?>
                <div class="rotating-banner"><?php print $banner;?></div>
                <?php } ?>
                <div class="rotating-banner banner-left"><?php print $banner_left;?></div>
                <?php if($banner_right) { ?>
                <div class="banner-right"><?php print $banner_right;?></div>
                <?php } ?>
			</div>
        	<!--header end here -->
            
            <!--navigation start here -->
            <div id="mainNav" role="navigation">
				<?php print $banner_links;?>
            </div>
            <!--navigation end here -->
        </div>
        <!--header panel end here -->
        
        <!--blocks panel start here -->
        <div id="mainContent" role="main">
			<?php print $home_content;?>
        </div>
        <!--blocks panel end here -->
        
        <!--footer link start here -->
        <div class="footerLinks" role="contentinfo">
			<?php print $footer;?>
        </div>
        <!--footer link end here -->
    </div>
        
        <!--footer container start here-->
        <div class="footerSeparator">&nbsp;</div>
        <div class="footerContainer">
        	<!--footer links -->
        	<div class="blocksPanel">
				<?php //print $footer_sitemap;?>		
				<?php echo theme('site_map'); ?>
            </div>
        	<!--footer links -->            
            <!--footer sub links -->
            <div class="footerSubLinks">
				<?php print $footer_links;?>
            </div>
            <!--footer sub links -->
            
			<div class="footerContent">
                <div class="left"><?php print $site_hosting_details;?></div>
                <div class="right"><?php print $site_ownership_details;?></div>
            </div>
        </div>
        <!--footer container end here-->
</div>
</body>
<?php
print $closure;
?>
</html>
