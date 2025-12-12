<?php
/**
* Template Name: Custom RSS Template - Harrigrams
*/
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'),true);

$pageQuery = new WP_Query(
  array(
    'post_type' => 'page',
    'pagename' => 'harrigrams'
  )
);
$page = $pageQuery->post;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
?>
<rss version="2.0"
  xmlns:content="http://purl.org/rss/1.0/modules/content/"
  xmlns:wfw="http://wellformedweb.org/CommentAPI/"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:atom="http://www.w3.org/2005/Atom"
  xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
  xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
  <?php do_action('rss2_ns');?>>
<channel>
  <atom:link href="<?php bloginfo_rss('url') ?>/harrigrams/feed" rel="self" type="application/rss+xml" />
  <title><?php echo $page->post_title ?> - HarrisonFM in Photos</title>
  <link><?php bloginfo_rss('url') ?>/harrigrams</link>
  <description><![CDATA[<?php echo $page->post_excerpt ?>]]></description>
  <lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000',get_lastpostmodified('GMT'),false); ?></lastBuildDate>
  <language>en-us</language>
  <sy:updatePeriod><?php echo apply_filters('rss_update_period','hourly'); ?></sy:updatePeriod>
  <sy:updateFrequency><?php echo apply_filters('rss_update_frequency','1'); ?></sy:updateFrequency>
  <?php do_action('rss2_head');?>
  <?php while(have_posts()):the_post(); global $post; ?>
    <item>
      <title><?php the_title_rss(); ?></title>
      <link><?php bloginfo_rss('url') ?>/harrigrams/<?php echo get_the_ID().'-'.strtolower(str_replace(' ', '-', $post->post_title)) ?></link>
      <guid><?php bloginfo_rss('url') ?>/harrigrams/<?php echo get_the_ID().'-'.strtolower(str_replace(' ', '-', $post->post_title)) ?></guid>
      <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000',get_post_time('Y-m-d H:i:s',true),false); ?></pubDate>
      <dc:creator><?php the_author(); ?></dc:creator>
      <description><![CDATA[<?php echo wp_get_attachment_image($post->ID, 'large', false)."\r\n"; the_excerpt_rss() ?>]]></description>
      <content:encoded><![CDATA[<?php echo wp_get_attachment_image($post->ID, 'large', false)."\r\n"; the_excerpt_rss() ?>]]></content:encoded>
      <?php rss_enclosure(); ?>
      <?php do_action('rss2_item');?>
    </item>
  <?php endwhile;?>
</channel>
</rss>