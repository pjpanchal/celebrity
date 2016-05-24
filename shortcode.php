<?php
    add_shortcode( 'celebrity', 'display_celebrity_post_type' );

    function display_celebrity_post_type(){
        $args = array(
            'post_type' => 'celebrity',
            'post_status' => 'publish'
        );

        $query = new WP_Query( $args );
        if( $query->have_posts() ){
           echo  '<div class="gallery-columns-3">';
            while( $query->have_posts() ){
                $query->the_post();
				echo '<div class="gallery-item ">';
                echo '<div class="title">' . get_the_title() . '</div>';
				echo '<div class="thumb">' . the_post_thumbnail(array(150,150)) . '</div>';
				echo '<div class="desc">' . esc_html( get_post_meta( get_the_ID(), 'email', true ) ) . '</div>';
				echo '</div>';
            }
            echo '</div>';
        }
        wp_reset_postdata();
        
    }
?>
