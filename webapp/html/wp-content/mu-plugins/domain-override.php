<?php
/**
 * Plugin Name: Domain Override
 * Description: Override WordPress URLs for public domain access
 * Version: 1.0
 * Author: System
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Override WordPress URLs for public domain access
add_filter('option_home', function($url) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        return 'https://amer-alsabbagh.de';
    }
    return $url;
});

add_filter('option_siteurl', function($url) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        return 'https://amer-alsabbagh.de';
    }
    return $url;
});

// Prevent WordPress from redirecting when domain doesn't match
add_filter('redirect_canonical', function($redirect_url, $requested_url) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        return false;
    }
    return $redirect_url;
}, 10, 2);

// Comprehensive URL replacement function
function replace_domain_urls($content) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        $content = str_replace('http://wp.internal.lan', 'https://amer-alsabbagh.de', $content);
        $content = str_replace('https://wp.internal.lan', 'https://amer-alsabbagh.de', $content);
    }
    return $content;
}

// Fix all URLs in content to use the correct domain
add_filter('the_content', 'replace_domain_urls');
add_filter('widget_text', 'replace_domain_urls');
add_filter('wp_nav_menu_items', 'replace_domain_urls');
add_filter('the_content_feed', 'replace_domain_urls');
add_filter('comment_text', 'replace_domain_urls');

// Fix image srcset attributes
add_filter('wp_calculate_image_srcset', function($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        foreach ($sources as &$source) {
            if (isset($source['url']) && strpos($source['url'], 'wp.internal.lan') !== false) {
                $source['url'] = str_replace('http://wp.internal.lan', 'https://amer-alsabbagh.de', $source['url']);
                $source['url'] = str_replace('https://wp.internal.lan', 'https://amer-alsabbagh.de', $source['url']);
            }
        }
    }
    return $sources;
}, 10, 5);

// Fix URLs in all output - comprehensive fix
add_filter('wp_get_attachment_url', 'replace_domain_urls');
add_filter('style_loader_src', 'replace_domain_urls');
add_filter('script_loader_src', 'replace_domain_urls');

// Fix URLs in post meta and custom fields
add_filter('get_post_metadata', function($value, $object_id, $meta_key, $single) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        if (is_string($value) && strpos($value, 'wp.internal.lan') !== false) {
            $value = str_replace('http://wp.internal.lan', 'https://amer-alsabbagh.de', $value);
            $value = str_replace('https://wp.internal.lan', 'https://amer-alsabbagh.de', $value);
        }
    }
    return $value;
}, 10, 4);

// Fix URLs in all output buffers - final comprehensive fix
add_filter('wp_footer', function() {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'amer-alsabbagh.de') {
        ob_start(function($buffer) {
            return str_replace(['http://wp.internal.lan', 'https://wp.internal.lan'], 'https://amer-alsabbagh.de', $buffer);
        });
    }
}, 1);
