<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) : the_post();
    $partner_data = get_partner_data(get_the_ID());
    ?>
    <div class="partner-modern">
        <div class="partner-card-header">
            <?php if ($partner_data['logo_url']) : ?>
                <div class="partner-logo">
                    <img src="<?php echo esc_url($partner_data['logo_url']); ?>" alt="<?php echo esc_attr(get_the_title()); ?> ლოგო">
                </div>
            <?php endif; ?>
            
            <div class="partner-header-info">
                <h1 class="partner-title"><?php the_title(); ?></h1>
                
                <?php if ($partner_data['founding_year'] || $partner_data['employees']) : ?>
                    <div class="partner-meta">
                        <?php if ($partner_data['founding_year']) : ?>
                            <span class="partner-founding-year">
                                <i class="fas fa-calendar-alt"></i>
                                დაარსდა <?php echo esc_html($partner_data['founding_year']); ?> წელს
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($partner_data['employees']) : ?>
                            <span class="partner-employees">
                                <i class="fas fa-users"></i>
                                <?php echo esc_html($partner_data['employees']); ?> თანამშრომელი
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="partner-social-links">
                    <?php if ($partner_data['social_facebook']) : ?>
                        <a href="<?php echo esc_url($partner_data['social_facebook']); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook-square"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($partner_data['social_linkedin']) : ?>
                        <a href="<?php echo esc_url($partner_data['social_linkedin']); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($partner_data['social_twitter']) : ?>
                        <a href="<?php echo esc_url($partner_data['social_twitter']); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter-square"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($partner_data['social_instagram']) : ?>
                        <a href="<?php echo esc_url($partner_data['social_instagram']); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram-square"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($partner_data['cover_url']) : ?>
            <div class="partner-cover">
                <img src="<?php echo esc_url($partner_data['cover_url']); ?>" alt="<?php echo esc_attr(get_the_title()); ?> ქავერი">
            </div>
        <?php endif; ?>
        
        <div class="partner-content-wrapper">
            <div class="partner-column partner-main-content">
                <?php if (get_the_content()) : ?>
                    <div class="partner-section">
                        <h2 class="section-title">შესახებ</h2>
                        <div class="partner-description">
                            <?php the_content(); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php
                // დაკავშირებული სიახლეები
                $related_posts = get_posts(array(
                    'post_type' => 'post',
                    'meta_key' => '_connected_partner',
                    'meta_value' => get_the_ID(),
                    'posts_per_page' => 3
                ));
                
                if ($related_posts) : ?>
                    <div class="partner-section">
                        <h2 class="section-title">
                            პარტნიორის სიახლეები
                            <span class="news-count"><?php echo count($related_posts); ?> სიახლე</span>
                        </h2>
                        <div class="partner-news-grid">
                            <?php foreach ($related_posts as $related_post) : 
                                $thumbnail = get_the_post_thumbnail_url($related_post->ID, 'medium');
                                $post_date = get_the_date('d M, Y', $related_post->ID);
                            ?>
                                <div class="news-card">
                                    <?php if ($thumbnail) : ?>
                                        <div class="news-image">
                                            <a href="<?php echo get_permalink($related_post->ID); ?>">
                                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($related_post->post_title); ?>">
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="news-content">
                                        <h3 class="news-title">
                                            <a href="<?php echo get_permalink($related_post->ID); ?>">
                                                <?php echo esc_html($related_post->post_title); ?>
                                            </a>
                                        </h3>
                                        <div class="news-excerpt">
                                            <?php 
                                            if (has_excerpt($related_post->ID)) {
                                                echo wp_kses_post(get_the_excerpt($related_post));
                                            } else {
                                                echo wp_kses_post(wp_trim_words($related_post->post_content, 20));
                                            }
                                            ?>
                                        </div>
                                        <div class="news-date">
                                            <i class="far fa-calendar-alt"></i>
                                            <?php echo esc_html($post_date); ?>
                                        </div>
                                        <a href="<?php echo get_permalink($related_post->ID); ?>" class="read-more">
                                            სრულად ნახვა <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="partner-column partner-sidebar">
                <?php if ($partner_data['website'] || $partner_data['phone'] || $partner_data['email'] || $partner_data['address']) : ?>
                    <div class="partner-section">
                        <h3 class="section-title">კონტაქტი</h3>
                        <ul class="contact-list">
                            <?php if ($partner_data['website']) : ?>
                                <li class="contact-item">
                                    <div class="contact-icon"><i class="fas fa-globe"></i></div>
                                    <div class="contact-details">
                                        <span class="contact-label">ვებსაიტი</span>
                                        <a href="<?php echo esc_url($partner_data['website']); ?>" class="contact-value" target="_blank">
                                            <?php echo esc_html(preg_replace('#^https?://#', '', $partner_data['website'])); ?>
                                        </a>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ($partner_data['phone']) : ?>
                                <li class="contact-item">
                                    <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                                    <div class="contact-details">
                                        <span class="contact-label">ტელეფონი</span>
                                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $partner_data['phone'])); ?>" class="contact-value">
                                            <?php echo esc_html($partner_data['phone']); ?>
                                        </a>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ($partner_data['email']) : ?>
                                <li class="contact-item">
                                    <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                                    <div class="contact-details">
                                        <span class="contact-label">ელ-ფოსტა</span>
                                        <a href="mailto:<?php echo esc_attr($partner_data['email']); ?>" class="contact-value">
                                            <?php echo esc_html($partner_data['email']); ?>
                                        </a>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <?php if ($partner_data['address']) : ?>
                                <li class="contact-item">
                                    <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="contact-details">
                                        <span class="contact-label">მისამართი</span>
                                        <span class="contact-value"><?php echo esc_html($partner_data['address']); ?></span>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="partner-section">
                    <h3 class="section-title">სამუშაო საათები</h3>
                    <table class="hours-table">
                        <tbody>
                            <?php 
                            $days_ka = array(
                                'monday' => 'ორშაბათი',
                                'tuesday' => 'სამშაბათი',
                                'wednesday' => 'ოთხშაბათი',
                                'thursday' => 'ხუთშაბათი',
                                'friday' => 'პარასკევი',
                                'saturday' => 'შაბათი',
                                'sunday' => 'კვირა'
                            );
                            
                            foreach ($days_ka as $day_en => $day_ka) :
                                $is_open = $partner_data['hours'][$day_en]['is_open'];
                                $opening = $partner_data['hours'][$day_en]['opening'];
                                $closing = $partner_data['hours'][$day_en]['closing'];
                            ?>
                                <tr class="<?php echo $is_open == '0' ? 'day-closed' : ''; ?>">
                                    <td><strong><?php echo esc_html($day_ka); ?></strong></td>
                                    <td>
                                        <?php if ($is_open == '1' && $opening && $closing) : ?>
                                            <?php echo esc_html($opening); ?> - <?php echo esc_html($closing); ?>
                                        <?php else : ?>
                                            <span class="closed-text">დაკეტილია</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endwhile;

get_footer();