<?php

/*
Plugin Name: Social Media Share Buttons
Plugin URI: https://www.mozedia.com/
Description: #1 Fast Loading Social Media Sharing Buttons, no need JavaScript and jQuery.
Author: Jumedeen khan
Author URI: https://www.mozedia.com/
Version: 2.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: social-media-share-buttons
*/

// * Quit files
defined('ABSPATH') || exit;
define('SMSB_PAGE', 'mozedia-social-sharing');

add_filter('the_content', 'add_mozedia_social_sharing_buttons');

function mozedia_smsb_menu() {
    add_submenu_page('options-general.php', 'Social Sharing', 'Social Sharing', 'manage_options', SMSB_PAGE, 'SMSB_PAGE');
}

add_action('admin_menu', 'mozedia_smsb_menu');
add_action('mozedia_smsb_settings_tab', 'mozedia_smsb_welcome_tab', 1);

function mozedia_smsb_welcome_tab() {
    global $smsb_active_tab; ?>
	<a class="nav-tab <?php echo $smsb_active_tab == 'inline' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=mozedia-social-sharing&tab=inline' ); ?>"><?php _e( 'Inline Sharing', 'mozedia' ); ?> </a>
   <?php
}

add_action('mozedia_settings_content', 'mozedia_smsb_welcome_options_page');

function mozedia_smsb_welcome_options_page() {
    global $smsb_active_tab;
    if ( 'inline' != $smsb_active_tab)
        return;
?>
   <div class="mozedia wrap" style="margin:0;background:#fff;padding:10px 20px;border:1px solid #ccc;margin-bottom:20px;max-width:600px;">
         <form method="post" action="options.php">
                <?php
    settings_fields("mozedia_smsb_config_section");
    do_settings_sections("mozedia-social-sharing");
    submit_button();
?>
       </form>
    </div>
     <div>Need help, check this <a href="https://www.mozedia.com/social-media-share-buttons" target="_blank">Guidelines</a> for more details.</div>
    <?php
}

add_action( 'mozedia_smsb_settings_tab', 'mozedia_cos_tab2' );
function mozedia_cos_tab2(){
	global $smsb_active_tab; ?>
	<a class="nav-tab <?php echo $smsb_active_tab == 'floating' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=mozedia-social-sharing&tab=floating' ); ?>"><?php _e( 'Floating Sharing', 'mozedia' ); ?> </a>
	<?php
}

add_action( 'mozedia_settings_content', 'mozedia_smsb_another_options_page' );

function mozedia_smsb_another_options_page() {
	global $smsb_active_tab;
	if ( 'floating' != $smsb_active_tab )
		return;
	?>
   <div class="mozedia wrap" style="margin:0;background:#fff;padding:10px 20px;border:1px solid #ccc;margin-bottom:20px;max-width:600px;">
         <form method="post" action="options.php">
                <?php
    settings_fields("mozedia_float_config_section");
    do_settings_sections("mozedia-floating-sharing");
    submit_button();
?>
       </form>
    </div>
     <div>Need help, check this <a href="https://www.mozedia.com/social-media-share-buttons" target="_blank">Guidelines</a> for more details.</div>

	<?php
}

function SMSB_PAGE() {
    global $smsb_active_tab;
    $smsb_active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'inline'; ?>
      <h1 style="font-weight: 500;">Social Share Buttons</h1>
		<h2 class="nav-tab-wrapper">
		<?php
			do_action( 'mozedia_smsb_settings_tab' );
		?>
		</h2>

        <?php
    do_action('mozedia_settings_content');
}

function mozedia_smsb_settings() {
    add_settings_section("mozedia_smsb_config_section", "", null, "mozedia-social-sharing");
    add_settings_section("mozedia_float_config_section", "", null, "mozedia-floating-sharing");
    add_settings_field("mozedia-social-sharing-global", "Activate", "mozedia_cos_sharing_global", "mozedia-social-sharing", "mozedia_smsb_config_section");
    add_settings_field("mozedia-social-sharing-title", "Share Title", "mozedia_cos_sharing_title", "mozedia-social-sharing", "mozedia_smsb_config_section");
    add_settings_field("mozedia-social-sharing-facebook", "Choose Icons", "mozedia_cos_sharing_post_page_options", "mozedia-social-sharing", "mozedia_smsb_config_section");
    add_settings_field("mozedia-social-sharing-twitter", "Twitter Username", "mozedia_cos_sharing_twitter", "mozedia-social-sharing", "mozedia_smsb_config_section");
    add_settings_field("mozedia-social-sharing-nofollow", "Add Nofollow", "mozedia_cos_sharing_nofollow", "mozedia-social-sharing", "mozedia_smsb_config_section");
    add_settings_field("mozedia-social-sharing-floating", "Activate", "mozedia_cos_sharing_float_global", "mozedia-floating-sharing", "mozedia_float_config_section");
    add_settings_field("mozedia-social-sharing-float-facebook", "Choose Icons", "mozedia_cos_sharing_float_options", "mozedia-floating-sharing", "mozedia_float_config_section");
    add_settings_field("mozedia-social-sharing-top-padding", "Top padding", "mozedia_cos_top_padding", "mozedia-floating-sharing", "mozedia_float_config_section");
    add_settings_field("mozedia-social-sharing-mobile-hide", "Hide on Mobile", "mozedia_cos_mobile_hide", "mozedia-floating-sharing", "mozedia_float_config_section");
	
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-facebook");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-twitter");
	register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-twitter-name");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-pinterest");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-linkedin");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-whatsapp");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-rel-nofollow");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-custom-label");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-email");
    register_setting("mozedia_smsb_config_section", "mozedia-social-sharing-post-page-global");
	
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-facebook");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-twitter");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-pinterest");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-linkedin");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-whatsapp");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-email");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-top-padding");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-mobile-hide");
    register_setting("mozedia_float_config_section", "mozedia-social-sharing-float-global");
}

function mozedia_cos_sharing_global() {
?>
      <div class="postbox" style="padding: 15px;">
                <input type="checkbox" name="mozedia-social-sharing-post-page-global" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-post-page-global'), true);
?> /> Enable (display share button on Post/Page)
            <?php
}

function mozedia_cos_sharing_float_global() {
?>
      <div class="postbox" style="padding: 15px;">
                <input type="checkbox" name="mozedia-social-sharing-float-global" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-global'), true);
?> /> Enable (display share button in left side)
            <?php
}

function mozedia_cos_sharing_title() {
?>
      <div class="postbox" style="padding: 15px;">
                <input type="text" name="mozedia-social-sharing-custom-label" value="<?php
    echo esc_attr(get_option('mozedia-social-sharing-custom-label'));
?>" /> (Like: Share, share this)
            </div>
            <?php
}

function mozedia_cos_sharing_post_page_options() {
?>
  <div class="postbox" style="padding: 30px;">
        <input type="checkbox" name="mozedia-social-sharing-facebook" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-facebook'), true);
?> /> Facebook
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-twitter" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-twitter'), true);
?> /> Twitter
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-linkedin" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-linkedin'), true);
?> /> Linkedin
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-email" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-email'), true);
?> /> Email
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-pinterest" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-pinterest'), true);
?> /> Pinterest
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-whatsapp" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-whatsapp'), true);
?> /> WhatsApp

    </div>
   <?php
}

function mozedia_cos_sharing_float_options() {
?>
  <div class="postbox" style="padding: 30px;">
        <input type="checkbox" name="mozedia-social-sharing-float-facebook" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-facebook'), true);
?> /> Facebook
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-float-twitter" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-twitter'), true);
?> /> Twitter
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-float-linkedin" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-linkedin'), true);
?> /> Linkedin
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-float-email" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-email'), true);
?> /> Email
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-float-pinterest" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-pinterest'), true);
?> /> Pinterest
        <br /><br /><input type="checkbox" name="mozedia-social-sharing-float-whatsapp" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-float-whatsapp'), true);
?> /> WhatsApp

    </div>
   <?php
}

function mozedia_cos_sharing_twitter() {
?>
      <div class="postbox" style="padding: 15px;">
                 <input type="text" name="mozedia-social-sharing-twitter-name" value="<?php
    echo esc_attr(get_option('mozedia-social-sharing-twitter-name'));
?>" /> (without @)
            </div>
            <?php
}

function mozedia_cos_sharing_nofollow() {
?>
      <div class="postbox" style="padding: 15px;">
                 <input type="checkbox" name="mozedia-social-sharing-rel-nofollow" value="1" <?php
    checked(1, get_option('mozedia-social-sharing-rel-nofollow'), true);
?> /> add rel="nofollow" to all links
            </div>
            <?php
}

function mozedia_cos_top_padding() {
?>
      <div class="postbox" style="padding: 15px;">
                <input type="text" style="width:50px;" name="mozedia-social-sharing-top-padding" value="<?php
    echo esc_attr(get_option('mozedia-social-sharing-top-padding'));
?>" /> Set padding from top (Eg: 150-200)
            </div>
            <?php
}

function mozedia_cos_mobile_hide() {
?>
      <div class="postbox" style="padding: 15px;">
                <input type="text" style="width:50px;" name="mozedia-social-sharing-mobile-hide" value="<?php
    echo esc_attr(get_option('mozedia-social-sharing-mobile-hide'));
?>" /> (Recommended: 1200.)
            </div>
            <?php
}

add_action("admin_init", "mozedia_smsb_settings");

function add_mozedia_social_sharing_buttons($content) {
	
	global $post;
    
    // Get current page URL
    
    $mozediaURL = get_permalink();
    
    // Get current page title
    
	$mozediaTitle = str_replace( '%', '%25', get_the_title());
	$socialTitle = urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));
    
    // Get Post Thumbnail for pinterest
    
    $mozediaThumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		
	// Open link in Popup
	$openpopup ='window.open(this.href,\'\', \'left=20,top=20,width=550,height=320\');return false;';
    
    // Twitter URL with Username
    $twitterURL      = 'https://twitter.com/intent/tweet?text=' . $socialTitle . '&amp;url=' . $mozediaURL;
    $twitterUserName = get_option("mozedia-social-sharing-twitter-name");
    if (!empty($twitterUserName)) {
        $twitterURL .= '&amp;via=' . $twitterUserName;
    }
	
    // Social share button URLs
    $facebookURL = 'https://www.facebook.com/sharer/sharer.php?u=' . $mozediaURL;
    $linkedInURL = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $mozediaURL . '&amp;title=' . $mozediaTitle;
    $pinterestURL = 'https://pinterest.com/pin/create/button/?url=' . $mozediaURL . '&amp;media=' . $mozediaThumbnail[0] . '&amp;description=' . $mozediaTitle;
    $emailURL     = 'mailto:?subject=' . $mozediaTitle . '&amp;body= ' . $mozediaURL . '" title="Share by Email';
    $whatsappURL = 'https://api.whatsapp.com/send?text=' . $mozediaTitle . ' ' . $mozediaURL;
    
    // Nofollow tags
    if (get_option("mozedia-social-sharing-rel-nofollow") == 1) {
        $rel_nofollow = 'rel="nofollow"';
    } else {
        $rel_nofollow = '';
    }
    if (!is_single()) {
		
		return $content;
    }
    
    if (get_option("mozedia-social-sharing-post-page-global") == 1) {
        
        // Add share button at the end of page/page content
        
        $content .= '<div class="social-share inline">';
        $content .= '<span class="sharetitle">' . get_option("mozedia-social-sharing-custom-label") . '</span><ul class="share-list">';
        if (get_option("mozedia-social-sharing-facebook") == 1) {
            $content .= '<li class="icon facebook"><a ' . $rel_nofollow . ' href="' . $facebookURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-facebook"></i><span>Share on Facebook</span></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-twitter") == 1) {
            $content .= '<li class="icon twitter"><a ' . $rel_nofollow . ' href="' . $twitterURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-twitter"></i><span>Tweet on Twitter</span></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-linkedin") == 1) {
            $content .= '<li class="icon linkedin"><a ' . $rel_nofollow . ' href="' . $linkedInURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-email") == 1) {
            $content .= '<li class="icon email"><a ' . $rel_nofollow . ' href="' . $emailURL . '"><i class="fa fa-mail"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-pinterest") == 1) {
            $content .= '<li class="icon pinterest"><a ' . $rel_nofollow . ' href="' . $pinterestURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-pinterest"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-whatsapp") == 1) {
            $content .= '<li class="icon whatsapp"><a ' . $rel_nofollow . ' href="' . $whatsappURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-whatsapp"></i></a></li>';
        }
        
        $content .= '</ul></div>';
    }
    
    if (get_option("mozedia-social-sharing-float-global") == 1) {
        
        // Add share buttons in left side of content
        
        $content .= '<div class="social-share flt"><div class="flt-bar"><input type="checkbox" id="share-toggle"><div class="flt-bar-button"><ul class="float-list">';
        if (get_option("mozedia-social-sharing-float-facebook") == 1) {
            $content .= '<li class="icon facebook"><a ' . $rel_nofollow . ' href="' . $facebookURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-facebook"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-float-twitter") == 1) {
            $content .= '<li class="icon twitter"><a ' . $rel_nofollow . ' href="' . $twitterURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-twitter"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-float-email") == 1) {
            $content .= '<li class="icon email"><a ' . $rel_nofollow . ' href="' . $emailURL . '"><i class="fa fa-mail"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-float-linkedin") == 1) {
            $content .= '<li class="icon linkedin"><a ' . $rel_nofollow . ' href="' . $linkedInURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-float-pinterest") == 1) {
            $content .= '<li class="icon pinterest"><a ' . $rel_nofollow . ' href="' . $pinterestURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-pinterest"></i></a></li>';
        }
        
        if (get_option("mozedia-social-sharing-float-whatsapp") == 1) {
            $content .= '<li class="icon whatsapp"><a ' . $rel_nofollow . ' href="' . $whatsappURL . '" onclick="'.$openpopup.'" target="_blank"><i class="fa fa-whatsapp"></i></a></li>';
        }
        
        $content .= '</ul><label for="share-toggle" class="share-toggle"></label></div></div></div>';
    }
    
    return $content;
};

function admin_register_head() {
    $siteurl = get_option('siteurl');
    echo "<style>.nav-tab-active,.nav-tab-active:focus,.nav-tab-active:focus:active,.nav-tab-active:hover{background:#fff;border-bottom:1px solid #fff}.form-table th{width:120px;padding:10px}.mozedia .form-table td{padding:0}.nav-tab-wrapper,.postbox{border:0;box-shadow:none}</style>";
}

add_action('admin_head', 'admin_register_head');

function mozedia_smsb_header() {
	$top_padding = get_option("mozedia-social-sharing-top-padding");
	$mobile_hide = get_option("mozedia-social-sharing-mobile-hide");
    if ( $top_padding == "") { $top_padding = '150';}
    if ( $mobile_hide == "") { $mobile_hide = '1200';}
    echo '<style>@font-face{font-family:icons;src:url(/wp-content/plugins/social-media-share-buttons/font/icons.eot);src:url(/wp-content/plugins/social-media-share-buttons/font/icons.eot#iefix) format("embedded-opentype"),url(/wp-content/plugins/social-media-share-buttons/font/icons.woff2) format("woff2"),url(/wp-content/plugins/social-media-share-buttons/font/icons.woff) format("woff"),url(/wp-content/plugins/social-media-share-buttons/font/icons.ttf) format("truetype"),url(/wp-content/plugins/social-media-share-buttons/font/icons.svg) format("svg");font-weight:400;font-style:normal}.social-share .icon span,.social-share .icon,.social-share .icon a,.social-share .icon a:hover,.social-share a{text-decoration:none}.social-share .fa{font-family:icons;font-style:normal}.social-share .fa-facebook:before{content:"\f09a";}.social-share .fa-twitter:before{content:"\f099";}.social-share .fa-linkedin:before{content:"\f0e1";}.social-share .fa-pinterest:before{content:"\f231";}.social-share .fa-whatsapp:before{content:"\f232";}.social-share .fa-mail:before{content:"\e800";}.social-share .sharetitle{display:inline-block;float:left;font-size:18px;font-weight:600;border-right:1px solid #dfdfdf;padding:0 20px 0 5px;margin:5px 20px 0 0;color:#555;vertical-align:middle;text-align:center}.social-share.inline{display:block;margin:10px 0}.social-share .icon,.social-share .icon a{color:#fff;width:auto;height:auto;text-align:center;vertical-align:middle;overflow:hidden;white-space:nowrap;transition:.3s}.social-share .icon{display:inline-block;padding:0;min-width:auto;margin:2px;cursor:pointer}.social-share .icon a{display:block;font-size:15px;padding:7px 15px;border-radius:3px;line-height:1.5;}.social-share p:empty,.social-share ul>li:before{display:none}.social-share ul{margin:0}.social-share .share-list{display:flex;width:100%;max-width:80%;padding:0;border-spacing:5px 0}.social-share .icon a:hover{transition:.3s}.social-share.flt{float:left;position:fixed;top:' . $top_padding . 'px;left:5px}.social-share.hide{left:-999px;transition:.5s;opacity:0}.fixed .social-share.hide{left:5px;transition:.5s;opacity:1}.social-share.flt .icon{display:block;width:60px}.social-share.flt .icon a,.social-share.flt .icon a:hover{line-height:1.7;padding:10px;text-align:center}.social-share.flt .icon:hover{width:auto;transition:.3s}.social-share .icon svg{width:14px;height:20px}.social-share .icon.flt-bar{padding:0 10px}.social-share .icon.flt-bar:hover{padding:0 20px}.social-share .flt-bar-items{display:none}.social-share .flt-bar,.social-share .flt-bar .flt-bar-button{display:inline}.social-share .flt-bar ul{margin:0;padding:0}.social-share .flt-bar #share-toggle,.social-share .flt-bar #share-toggle:checked+div .float-list,.social-share .flt-bar .float-list{opacity:0;transition:.3s}.social-share .flt-bar #share-toggle:checked+div .float-list{transform:translateX(-150%);-webkit-transform:translateX(-150%);-ms-transform:translateX(-150%)}.social-share .flt-bar .share-toggle:after{display:inline;content:"\f104"}.social-share .flt-bar #share-toggle:checked+div .share-toggle:after{content:"\f105"}.share-toggle,.social-share .flt-bar .float-list{opacity:1;transition:.3s}.social-share .flt-bar .share-toggle{color:#333;display:inline;line-height:1;margin:5px 13px;left:0;padding:3px 15px;cursor:pointer;font-family:icons;user-select:none;opacity:0;position:absolute;border-radius:100px}.social-share .flt-bar #share-toggle:checked+div .share-toggle,.social-share .flt-bar .share-toggle:hover{background:#777;color:#fff;opacity:1}.social-share:hover .flt-bar .share-toggle{opacity:1}.social-share .flt-bar #share-toggle:checked+div .share-toggle{left:0;margin:0}.social-share.flt .icon span{display:inline-block;width:0;height:0;overflow:hidden;transition:.3s;line-height:.8;position:absolute}.social-share .icon:hover span,.social-share.flt .icon:hover span{width:42px;height:auto;transition:.3s;position:relative}.social-share .icon span{font-size:12px;padding-left:5px;vertical-align:text-bottom;border-left:1px solid rgba(255,255,255,.1)}.social-share .icon.small{max-width:44px}.icon span{margin-left:5px;font-size:14px;text-decoration:none}.icon.facebook a{background:#4267b2}.icon.twitter a{background:#1da1f2}.icon.linkedin a{background:#0073b5}.icon.email a{background:#7d7d7d}.icon.pinterest a{background:#e60023}.icon.whatsapp a{background:#25d366}.socialicons{text-align:center}.icon.facebook a:hover{background:#365086}.icon.twitter a:hover{background:#1272ad}.icon.linkedin a:hover{background:#005983}.icon.email a:hover{background:#5d5d5d}.icon.pinterest a:hover{background:#a90007}.icon.whatsapp a:hover{background:#15b350}.socialicons .icon{display:inline-block;margin:5px;color:#fff;text-align:center}@media only screen and (max-width:' . $mobile_hide . 'px){.social-share.flt{display:none}}@media only screen and (max-width:768px){.social-share .share-list{max-width:100%}.social-share .sharetitle{display:none}}@media only screen and (max-width:500px){.social-share .icon span{display:none}}</style>';
}

add_action('wp_head', 'mozedia_smsb_header', 100);