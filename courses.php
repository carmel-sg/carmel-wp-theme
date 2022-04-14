<?php
/*
Template Name: Courses
*/
add_action("genesis_loop", "custom_loop");

function custom_loop()
{
    global $post;

    $args = [
        "post_type" => "courses",
        "posts_per_page" => 9,
        "paged" => get_query_var("paged"),
    ];
    ?>
  <div class="course-main">
            <div class="row">
                <?php
                global $wp_query;
                $wp_query = new WP_Query($args);

                if (have_posts()):
                    while (have_posts()):
                        the_post(); ?>
             
           
            <div class="col">  
            <div class="courses-box">  
               <a href="<?php echo get_post_permalink($post->ID); ?>">
                <?php the_post_thumbnail(); ?>
                <div class="courses-box-content"> 
                <h2><?php
                get_permalink();
                the_title();
                ?></h2>
                <?php the_excerpt(); ?>
            </a>
            </div>
            </div>
             </div>
 
        <?php
                    endwhile;
                    do_action("genesis_after_endwhile");
                endif;
                ?>
    </div>
    </div>
 
   <?php wp_reset_query();
}

genesis();
