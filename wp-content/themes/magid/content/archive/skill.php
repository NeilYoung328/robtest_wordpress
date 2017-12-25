<?php
/**
 * @package Magid
 */

use Magid\App\Posts\PostContent;
use Magid\App\Posts\PostAttributes;
use Magid\App\ACF;

$options  = ( new ACF() )->getACFOptions();
$thumb_id = get_post_thumbnail_id( get_the_ID() ) ?: $options['default_image'];

// check for external link settings
$meta             = get_post_meta( $post->ID );
$external         = isset( $meta['external_resource'][0] ) && $meta['external_resource'][0] ? $meta['external_resource'][0] : false;
$external_url     = isset( $meta['external_url'][0] ) && ! empty( $meta['external_url'][0] ) ? $meta['external_url'][0] : false;
$external_excerpt = isset( $meta['external_excerpt'][0] ) && ! empty( $meta['external_excerpt'][0] ) ? $meta['external_excerpt'][0] : false;
$post_url         = $external && $external_url ? $external_url : get_permalink();
$target           = $external && $external_url ? 'target="_blank"' : '';
?>

<?php if ( 'top' === $layout ) : ?>
    <div class="entry__thumb">
        <a rel="bookmark" href="<?php echo esc_url( $post_url ); ?>" <?php echo $target; ?>>
            <?php $thumb = wp_get_attachment_image_src( $thumb_id, 'mention-sm' ); ?>
            <img data-src="<?php echo esc_url( $thumb[0] ); ?>" alt="<?php the_title(); ?>" itemprop="image"
                 height="<?php echo $thumb[2]; ?>" width="<?php echo $thumb[1]; ?>">
        </a>
    </div>
<?php endif; ?>

<?php if ( 'left' === $layout ) : ?>
    <?php $image = wp_get_attachment_image_src( $thumb_id, 'mention-md' ); ?>
    <a rel="bookmark" href="<?php echo esc_url( $post_url ); ?>" class="entry__background"
       data-bkg="<?php echo esc_url( $image[0] ); ?>" <?php echo $target; ?>></a>
<?php endif; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry__post--' . $layout ); ?>>

    <div class="entry__meta">
        <div class="meta__date" style="color:<?php echo $meta_color; ?>;">
            <?php
            printf(
                __( '<span class="meta__author">By %s</span>', 'magid' ),
                esc_html( ( new PostAttributes() )->getPostAuthor( $post->ID, $post->post_author ) )
            );
            echo get_the_date( 'm/d/y' );
            ?>
        </div>
        <?php
        hybrid_post_terms( [
            'taxonomy' => 'category',
        ] );
        ?>
    </div>

    <header class="entry__header">
        <h2 class="hdg hdg--3 hdg--medium" id="more-text">
		    <a rel="bookmark" href="<?php echo esc_url( $post_url ); ?>" <?php echo $target; ?>>
                <?php ( new PostContent() )->getMentionsTitle( $layout );?>
            </a>
        </h2>
    </header><!-- .entry__header -->

    <?php if ( 'none' === $layout ) : ?>
        <div class="entry__excerpt" id="text_limit">
            <?php
            /*if ( $external && $external_excerpt ) {
                echo wpautop($external_excerpt);
            } else {
                ( new PostContent() )->getMentionsExcerpt();
            }*/
			$title_length=get_the_title($post->ID);
            $text_count=get_the_excerpt($post->ID);
			$title_l=strlen($title_length);
			if($title_l>=20 || $title_l<=35){
				$stringCut = substr($text_count, 0, 250);
				echo $stringCut;
			}
			else{
				$stringCut = substr($text_count, 0, 100);
				echo $stringCut;
				}
            ?>
        </div><!-- .entry__content -->
		<?php else : ?>
		
        <div class="entry__excerpt" id="text_limit">
		
            <?php
			$title_length=get_the_title($post->ID);
            $text_count=get_the_excerpt($post->ID);
			$title_l=strlen($title_length);
			//echo $title_l;
			if($title_l<=20 || $title_l<=35){
				$stringCut = substr($text_count, 0, 250);
				echo $stringCut;
			}
			else{
				$stringCut = substr($text_count, 0, 100);
				echo $stringCut;
				}
            ?>
        </div><!-- .entry__content -->
		
    <?php endif; ?>

    <a class="entry_read-more" href="<?php echo esc_url( $post_url ); ?>"
       style="color:<?php echo $meta_color; ?>;" <?php echo $target; ?>>
        <svg class="icon icon-arrow" style="fill: <?php echo $meta_color; ?>;">
            <use xlink:href="#icon-arrow"></use>
        </svg>
        <?php _e( 'Read More', 'magid' ); ?>
    </a>

</article><!-- #post-## -->