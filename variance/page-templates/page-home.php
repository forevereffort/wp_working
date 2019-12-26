<?php
/**
 * Template Name: Home Page
 */

get_header(); ?>

<?php
	if( have_posts() ){
		while( have_posts() ){
			the_post();
?>
<div class="home-subscribe-section">
    <div class="home-subscribe-container">
        <h2><?php the_field('subscribe_title'); ?></h2>
        <p><?php the_field('subscribe_content'); ?></p>
        <div class="home-subscribe-form">
            <form action="" method="POST">
                <div class="form-row">
                    <div class="form-email">
                        <input type="email" placeholder="Your email" />
                    </div>
                    <div class="form-button">
                        <input type="submit" value="Submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="home-jobs-section">
    <div class="home-jobs-container">
        <h2><?php the_field('jobs_title'); ?></h2>
        <p><?php the_field('jobs_content'); ?></p>
        <div class="job-list">
            <div class="job-item">
                <div class="job-info">
                    <div class="company">
                        <h3><?php the_field('company_name'); ?></h3>
                    </div>
                    <div class="location">
                        <p><?php the_field('company_location'); ?></p>
                    </div>
                </div>
                <div class="job-apply">
                    <a href="<?php the_field('company_url'); ?>">LEARN MORE</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="home-featured-post-section">
    <div class="home-featured-post-container">
        <h2><?php the_field('featured_blog_section_title'); ?></h2>
        <?php
            $featured_post = get_field('featured_blog');
            $featured_image_url = get_the_post_thumbnail_url($featured_post->ID, 'full');
            $categories = get_the_category($featured_post->ID);
        ?>
        <div class="post-row">
            <div class="post-image">
                <a href="<?php echo get_permalink($featured_post->ID); ?>"><img src="<?php echo $featured_image_url; ?>" alt="logo" /></a>
            </div>
            <div class="post-content">
                <div class="category">
                    <?php
                        foreach($categories as $cat){
                    ?>
                        <a href="<?php echo get_category_link($cat->term_id); ?>"><?php echo $cat->name; ?></a>
                    <?php
                        }
                    ?>
                </div>
                <div class="title">
                    <h3><a href="<?php echo get_permalink($featured_post->ID); ?>"><?php echo $featured_post->post_title; ?></a></h3>
                </div>
                <div class="extract">
                    <p><?php echo $featured_post->post_excerpt; ?></p>
                </div>
            </div>
        </div>
        <div class="post-link">
            <a href="<?php echo get_permalink($featured_post->ID); ?>">SEE BLOG</a>
        </div>
    </div>
</div>
<?php
		}
	}
?>

<?php get_footer(); ?>