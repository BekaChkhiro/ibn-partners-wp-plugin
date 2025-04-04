<?php
/**
 * Meta Boxes for Partner Post Type
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// 2. პარტნიორების მეტა ველების დამატება
function add_partner_meta_boxes() {
    add_meta_box(
        'partner_details',
        'პარტნიორის დეტალები',
        'partner_details_callback',
        'partner',
        'normal',
        'high'
    );
    
    // დამატებითი სურათების მეტა ბოქსი ლოგოსა და ქავერისთვის
    add_meta_box(
        'partner_images',
        'პარტნიორის სურათები',
        'partner_images_callback',
        'partner',
        'normal',
        'high'
    );
    
    // სამუშაო საათების მეტა ბოქსი
    add_meta_box(
        'partner_hours',
        'სამუშაო საათები',
        'partner_hours_callback',
        'partner',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_partner_meta_boxes');

// 3. მეტა ველების შინაარსის გამოტანა
function partner_details_callback($post) {
    wp_nonce_field(basename(__FILE__), 'partner_nonce');
    
    $website = get_post_meta($post->ID, '_partner_website', true);
    $phone = get_post_meta($post->ID, '_partner_phone', true);
    $email = get_post_meta($post->ID, '_partner_email', true);
    $address = get_post_meta($post->ID, '_partner_address', true);
    
    // დამატებითი მეტა ველები
    $founding_year = get_post_meta($post->ID, '_partner_founding_year', true);
    $employees = get_post_meta($post->ID, '_partner_employees', true);
    $social_facebook = get_post_meta($post->ID, '_partner_social_facebook', true);
    $social_linkedin = get_post_meta($post->ID, '_partner_social_linkedin', true);
    $social_twitter = get_post_meta($post->ID, '_partner_social_twitter', true);
    $social_instagram = get_post_meta($post->ID, '_partner_social_instagram', true);
    
    ?>
    <style>
        .partner-meta-group {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #e5e5e5;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .partner-meta-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .partner-meta-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
            color: #2c3e50;
        }
        .partner-meta-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .partner-meta-col {
            flex: 1;
            min-width: 200px;
            padding: 0 10px;
            margin-bottom: 15px;
        }
        .partner-meta-col label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #444;
        }
        .partner-meta-col input,
        .partner-meta-col textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border 0.3s ease;
        }
        .partner-meta-col input:focus,
        .partner-meta-col textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 1px #3498db;
            outline: none;
        }
    </style>
    
    <div class="partner-meta-group">
        <h4 class="partner-meta-title">ძირითადი საკონტაქტო ინფორმაცია</h4>
        <div class="partner-meta-row">
            <div class="partner-meta-col">
                <label for="partner_website">ვებსაიტი:</label>
                <input type="url" name="partner_website" id="partner_website" value="<?php echo esc_url($website); ?>" class="widefat" placeholder="https://example.com">
            </div>
            <div class="partner-meta-col">
                <label for="partner_phone">ტელეფონი:</label>
                <input type="text" name="partner_phone" id="partner_phone" value="<?php echo esc_attr($phone); ?>" class="widefat" placeholder="+995 XXX XXX XXX">
            </div>
        </div>
        <div class="partner-meta-row">
            <div class="partner-meta-col">
                <label for="partner_email">ელ-ფოსტა:</label>
                <input type="email" name="partner_email" id="partner_email" value="<?php echo esc_attr($email); ?>" class="widefat" placeholder="contact@example.com">
            </div>
            <div class="partner-meta-col">
                <label for="partner_address">მისამართი:</label>
                <input type="text" name="partner_address" id="partner_address" value="<?php echo esc_attr($address); ?>" class="widefat" placeholder="მისამართი...">
            </div>
        </div>
    </div>
    
    <div class="partner-meta-group">
        <h4 class="partner-meta-title">დამატებითი ინფორმაცია</h4>
        <div class="partner-meta-row">
            <div class="partner-meta-col">
                <label for="partner_founding_year">დაარსების წელი:</label>
                <input type="number" name="partner_founding_year" id="partner_founding_year" value="<?php echo esc_attr($founding_year); ?>" class="widefat" min="1900" max="<?php echo date('Y'); ?>">
            </div>
            <div class="partner-meta-col">
                <label for="partner_employees">თანამშრომლების რაოდენობა:</label>
                <input type="number" name="partner_employees" id="partner_employees" value="<?php echo esc_attr($employees); ?>" class="widefat" min="1">
            </div>
        </div>
    </div>
    
    <div class="partner-meta-group">
        <h4 class="partner-meta-title">სოციალური ქსელები</h4>
        <div class="partner-meta-row">
            <div class="partner-meta-col">
                <label for="partner_social_facebook">Facebook:</label>
                <input type="url" name="partner_social_facebook" id="partner_social_facebook" value="<?php echo esc_url($social_facebook); ?>" class="widefat" placeholder="https://facebook.com/...">
            </div>
            <div class="partner-meta-col">
                <label for="partner_social_linkedin">LinkedIn:</label>
                <input type="url" name="partner_social_linkedin" id="partner_social_linkedin" value="<?php echo esc_url($social_linkedin); ?>" class="widefat" placeholder="https://linkedin.com/company/...">
            </div>
        </div>
        <div class="partner-meta-row">
            <div class="partner-meta-col">
                <label for="partner_social_twitter">Twitter/X:</label>
                <input type="url" name="partner_social_twitter" id="partner_social_twitter" value="<?php echo esc_url($social_twitter); ?>" class="widefat" placeholder="https://twitter.com/...">
            </div>
            <div class="partner-meta-col">
                <label for="partner_social_instagram">Instagram:</label>
                <input type="url" name="partner_social_instagram" id="partner_social_instagram" value="<?php echo esc_url($social_instagram); ?>" class="widefat" placeholder="https://instagram.com/...">
            </div>
        </div>
    </div>
    <?php
}

// 4. ლოგოსა და ქავერის მეტა ბოქსის შინაარსი
function partner_images_callback($post) {
    wp_nonce_field(basename(__FILE__), 'partner_images_nonce');
    
    $logo_id = get_post_meta($post->ID, '_partner_logo_id', true);
    $cover_id = get_post_meta($post->ID, '_partner_cover_id', true);
    
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : '';
    $cover_url = $cover_id ? wp_get_attachment_image_url($cover_id, 'large') : '';
    
    ?>
    <style>
        .partner-image-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .partner-image-col {
            flex: 1;
            min-width: 300px;
            padding: 0 10px;
            margin-bottom: 20px;
        }
        .partner-image-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #2c3e50;
        }
        .partner-image-preview {
            width: 100%;
            height: 200px;
            background-color: #f5f5f5;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .partner-image-preview:hover {
            border-color: #3498db;
        }
        .partner-image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .partner-cover-preview {
            height: 150px;
        }
        .partner-cover-preview img {
            object-fit: cover;
            width: 100%;
            height: 100%;
        }
        .partner-image-placeholder {
            color: #aaa;
            text-align: center;
        }
        .partner-image-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255,255,255,0.9);
            color: #e74c3c;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: none;
            font-size: 18px;
            line-height: 30px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .partner-image-preview:hover .partner-image-remove {
            display: block;
        }
        .upload-btn-wrapper {
            display: flex;
            gap: 10px;
        }
        .upload-btn-wrapper button {
            padding: 8px 15px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .upload-btn-wrapper button:hover {
            background: #2980b9;
        }
        .upload-description {
            margin-top: 10px;
            font-size: 13px;
            color: #666;
        }
    </style>
    
    <div class="partner-meta-group">
        <div class="partner-image-row">
            <div class="partner-image-col">
                <div class="partner-image-title">პარტნიორის ლოგო</div>
                <div class="partner-image-preview" id="partner-logo-preview">
                    <?php if ($logo_url) : ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="ლოგო">
                        <button type="button" class="partner-image-remove" id="remove-partner-logo">×</button>
                    <?php else : ?>
                        <div class="partner-image-placeholder">
                            <span class="dashicons dashicons-format-image" style="font-size: 30px; width: 30px; height: 30px;"></span>
                            <p>ლოგო არ არის არჩეული</p>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="partner_logo_id" id="partner_logo_id" value="<?php echo esc_attr($logo_id); ?>">
                <div class="upload-btn-wrapper">
                    <button type="button" class="button" id="upload-partner-logo">
                        <?php echo $logo_id ? 'შეცვლა' : 'ლოგოს ატვირთვა'; ?>
                    </button>
                </div>
                <p class="upload-description">რეკომენდებულია კვადრატული ზომა, მინიმუმ 400x400px</p>
            </div>
            
            <div class="partner-image-col">
                <div class="partner-image-title">ქავერ სურათი</div>
                <div class="partner-image-preview partner-cover-preview" id="partner-cover-preview">
                    <?php if ($cover_url) : ?>
                        <img src="<?php echo esc_url($cover_url); ?>" alt="ქავერი">
                        <button type="button" class="partner-image-remove" id="remove-partner-cover">×</button>
                    <?php else : ?>
                        <div class="partner-image-placeholder">
                            <span class="dashicons dashicons-format-image" style="font-size: 30px; width: 30px; height: 30px;"></span>
                            <p>ქავერი არ არის არჩეული</p>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="partner_cover_id" id="partner_cover_id" value="<?php echo esc_attr($cover_id); ?>">
                <div class="upload-btn-wrapper">
                    <button type="button" class="button" id="upload-partner-cover">
                        <?php echo $cover_id ? 'შეცვლა' : 'ქავერის ატვირთვა'; ?>
                    </button>
                </div>
                <p class="upload-description">რეკომენდებულია ფართო სურათი, მინიმუმ 1200x400px</p>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // ლოგოს ატვირთვის ფუნქციონალი
        $('#upload-partner-logo').click(function(e) {
            e.preventDefault();
            
            var logoFrame = wp.media({
                title: 'აირჩიეთ პარტნიორის ლოგო',
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: 'გამოყენება'
                }
            });
            
            logoFrame.on('select', function() {
                var attachment = logoFrame.state().get('selection').first().toJSON();
                $('#partner_logo_id').val(attachment.id);
                
                var logoPreview = '<img src="' + attachment.url + '" alt="ლოგო">';
                logoPreview += '<button type="button" class="partner-image-remove" id="remove-partner-logo">×</button>';
                
                $('#partner-logo-preview').html(logoPreview);
                $('#upload-partner-logo').text('შეცვლა');
            });
            
            logoFrame.open();
        });
        
        // ქავერის ატვირთვის ფუნქციონალი
        $('#upload-partner-cover').click(function(e) {
            e.preventDefault();
            
            var coverFrame = wp.media({
                title: 'აირჩიეთ პარტნიორის ქავერი',
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: 'გამოყენება'
                }
            });
            
            coverFrame.on('select', function() {
                var attachment = coverFrame.state().get('selection').first().toJSON();
                $('#partner_cover_id').val(attachment.id);
                
                var coverPreview = '<img src="' + attachment.url + '" alt="ქავერი">';
                coverPreview += '<button type="button" class="partner-image-remove" id="remove-partner-cover">×</button>';
                
                $('#partner-cover-preview').html(coverPreview);
                $('#upload-partner-cover').text('შეცვლა');
            });
            
            coverFrame.open();
        });
        
        // ლოგოს წაშლა
        $(document).on('click', '#remove-partner-logo', function(e) {
            e.preventDefault();
            
            $('#partner_logo_id').val('');
            
            var emptyPreview = '<div class="partner-image-placeholder">';
            emptyPreview += '<span class="dashicons dashicons-format-image" style="font-size: 30px; width: 30px; height: 30px;"></span>';
            emptyPreview += '<p>ლოგო არ არის არჩეული</p>';
            emptyPreview += '</div>';
            
            $('#partner-logo-preview').html(emptyPreview);
            $('#upload-partner-logo').text('ლოგოს ატვირთვა');
        });
        
        // ქავერის წაშლა
        $(document).on('click', '#remove-partner-cover', function(e) {
            e.preventDefault();
            
            $('#partner_cover_id').val('');
            
            var emptyPreview = '<div class="partner-image-placeholder">';
            emptyPreview += '<span class="dashicons dashicons-format-image" style="font-size: 30px; width: 30px; height: 30px;"></span>';
            emptyPreview += '<p>ქავერი არ არის არჩეული</p>';
            emptyPreview += '</div>';
            
            $('#partner-cover-preview').html(emptyPreview);
            $('#upload-partner-cover').text('ქავერის ატვირთვა');
        });
    });
    </script>
    <?php
}

// 5. სამუშაო საათების მეტა ბოქსი
function partner_hours_callback($post) {
    wp_nonce_field(basename(__FILE__), 'partner_hours_nonce');
    
    $days = array(
        'monday' => 'ორშაბათი',
        'tuesday' => 'სამშაბათი',
        'wednesday' => 'ოთხშაბათი',
        'thursday' => 'ხუთშაბათი',
        'friday' => 'პარასკევი',
        'saturday' => 'შაბათი',
        'sunday' => 'კვირა'
    );
    
    $hours = array();
    foreach ($days as $day_key => $day_name) {
        $is_open = get_post_meta($post->ID, '_partner_' . $day_key . '_open', true);
        $opening_time = get_post_meta($post->ID, '_partner_' . $day_key . '_opening', true);
        $closing_time = get_post_meta($post->ID, '_partner_' . $day_key . '_closing', true);
        
        if ($opening_time == '' && $day_key == 'monday') $opening_time = '10:00';
        if ($closing_time == '' && $day_key == 'monday') $closing_time = '18:00';
        
        $hours[$day_key] = array(
            'is_open' => $is_open !== '' ? $is_open : '1', // Default is open
            'opening' => $opening_time,
            'closing' => $closing_time
        );
    }
    
    // Default: Sunday closed
    if ($hours['sunday']['is_open'] === '1' && $hours['sunday']['opening'] == '') {
        $hours['sunday']['is_open'] = '0';
    }
    
    ?>
    <style>
        .partner-hours-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .partner-hours-table th,
        .partner-hours-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .partner-hours-table th {
            background-color: #f5f5f5;
            font-weight: 600;
            color: #444;
        }
        .partner-hours-table tr:last-child td {
            border-bottom: none;
        }
        .partner-hours-status {
            display: flex;
            align-items: center;
        }
        .partner-hours-time {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .partner-hours-time input[type="time"] {
            padding: 6px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 120px;
        }
        .partner-hours-closed-msg {
            margin-left: 10px;
            color: #e74c3c;
            font-weight: 500;
            display: none;
        }
        .partner-hours-separator {
            color: #888;
        }
        .closed-row .partner-hours-time {
            opacity: 0.5;
            pointer-events: none;
        }
        .closed-row .partner-hours-closed-msg {
            display: inline-block;
        }
    </style>
    
    <div class="partner-meta-group">
        <h4 class="partner-meta-title">სამუშაო საათები</h4>
        
        <table class="partner-hours-table">
            <thead>
                <tr>
                    <th width="25%">დღე</th>
                    <th width="20%">სტატუსი</th>
                    <th width="55%">სამუშაო საათები</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($days as $day_key => $day_name): ?>
                <tr id="hours-row-<?php echo $day_key; ?>" class="<?php echo $hours[$day_key]['is_open'] == '0' ? 'closed-row' : ''; ?>">
                    <td><?php echo $day_name; ?></td>
                    <td>
                        <div class="partner-hours-status">
                            <label>
                                <input type="radio" name="partner_<?php echo $day_key; ?>_open" value="1" <?php checked($hours[$day_key]['is_open'], '1'); ?> class="status-open">
                                ღია
                            </label>
                            &nbsp;&nbsp;
                            <label>
                                <input type="radio" name="partner_<?php echo $day_key; ?>_open" value="0" <?php checked($hours[$day_key]['is_open'], '0'); ?> class="status-closed">
                                დაკეტილი
                            </label>
                        </div>
                    </td>
                    <td>
                        <div class="partner-hours-time">
                            <input type="time" name="partner_<?php echo $day_key; ?>_opening" value="<?php echo esc_attr($hours[$day_key]['opening']); ?>">
                            <span class="partner-hours-separator">-</span>
                            <input type="time" name="partner_<?php echo $day_key; ?>_closing" value="<?php echo esc_attr($hours[$day_key]['closing']); ?>">
                            <span class="partner-hours-closed-msg">დაკეტილია</span>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <button type="button" class="button" id="copy-to-all-days">გადაკოპირება ყველა დღეზე</button>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // სამუშაო საათების სტატუსის შეცვლის დროს
        $('.status-open, .status-closed').on('change', function() {
            var row = $(this).closest('tr');
            if ($(this).val() === '0') { // დაკეტილი
                row.addClass('closed-row');
            } else { // ღია
                row.removeClass('closed-row');
            }
        });
        
        // ორშაბათის სამუშაო საათების კოპირება ყველა დღეზე
        $('#copy-to-all-days').on('click', function(e) {
            e.preventDefault();
            
            var mondayOpen = $('input[name="partner_monday_open"]:checked').val();
            var mondayOpening = $('input[name="partner_monday_opening"]').val();
            var mondayClosing = $('input[name="partner_monday_closing"]').val();
            
            var days = ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            
            days.forEach(function(day) {
                $('input[name="partner_' + day + '_open"][value="' + mondayOpen + '"]').prop('checked', true).trigger('change');
                $('input[name="partner_' + day + '_opening"]').val(mondayOpening);
                $('input[name="partner_' + day + '_closing"]').val(mondayClosing);
            });
            
            alert('სამუშაო საათები გადაკოპირდა ყველა დღეზე!');
        });
    });
    </script>
    <?php
}
