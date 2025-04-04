<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="partners-archive">
    <div class="partners-intro">
        <h1>ჩვენი პარტნიორები</h1>
        <p>გაეცანით ჩვენს პარტნიორებს, რომლებთანაც ვთანამშრომლობთ და ვქმნით ერთად წარმატებულ პროექტებს.</p>
    </div>

    <?php if (have_posts()) : ?>
        <div class="partners-grid">
            <?php while (have_posts()) : the_post(); 
                $partner_data = get_partner_data(get_the_ID());
            ?>
                <div class="partner-card-archive">
                    <?php if ($partner_data['logo_url']) : ?>
                        <div class="partner-image-container">
                            <img src="<?php echo esc_url($partner_data['logo_url']); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="partner-logo-img">
                        </div>
                    <?php endif; ?>
                    
                    <div class="partner-info">
                        <h2 class="partner-title"><?php the_title(); ?></h2>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="partner-excerpt">
                                <?php echo wp_kses_post(get_the_excerpt()); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($partner_data['founding_year']) : ?>
                            <div class="partner-meta">
                                დაარსდა <?php echo esc_html($partner_data['founding_year']); ?> წელს
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <a href="<?php the_permalink(); ?>" class="partner-link" aria-label="<?php echo esc_attr(get_the_title()); ?>"></a>
                </div>
            <?php endwhile; ?>
        </div>

        <?php
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => '&larr; წინა',
            'next_text' => 'შემდეგი &rarr;'
        ));
        ?>

    <?php else : ?>
        <div class="no-partners">
            <p>პარტნიორები ვერ მოიძებნა.</p>
        </div>
    <?php endif; ?>
</div>

<?php
get_footer();