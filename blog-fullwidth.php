<?php 
/**
* Template Name: Blog Fullwidth 
*/
get_header();?>

<section id="main">

    <?php get_template_part('lib/sub-header'); ?>

    <div class="container">
        <div class="row">

            <div id="content" class="site-content col-md-12" role="main">
                <?php

                # Query for FontPage and default template. 
                if (is_front_page()) {
                    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                }else{
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                }
                
                $args = array('post_type' => 'post','paged' => $paged);
                query_posts($args); 

                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        get_template_part( 'post-format/content', get_post_format() );
                    endwhile;
                else:
                    get_template_part( 'post-format/content', 'none' );
                endif;

                ?>
                <?php themeum_pagination(); ?>
            </div>

        </div> <!-- .row -->
    </div><!-- .container -->
</section> 

<?php get_footer();