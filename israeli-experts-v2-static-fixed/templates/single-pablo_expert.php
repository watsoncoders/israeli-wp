<?php
/**
 * תבנית יחיד למומחה
 * Author: pablo rotem
 */

defined('ABSPATH') || exit;

get_header();

$plugin = class_exists('Pablo_Experts_CPT_Migrator') ? Pablo_Experts_CPT_Migrator::instance() : null;
$post_id = get_the_ID();
$specialization = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_specialization', '_legacy_israeli_experts_fldSpecialization', '_legacy_israeli_experts_specialization']) : '';
$profession = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_profession', '_legacy_israeli_experts_fldProfession', '_legacy_israeli_experts_profession']) : '';
$seniority = $plugin ? $plugin->get_single_field_value($post_id, ['_legacy_israeli_experts_fldSeniority', '_legacy_israeli_experts_seniority', '_legacy_israeli_experts_fldVetek']) : '';
$education = $plugin ? $plugin->get_single_field_value($post_id, ['_legacy_israeli_experts_fldEducation', '_legacy_israeli_experts_education']) : '';
$organizations = $plugin ? $plugin->get_single_field_value($post_id, ['_legacy_israeli_experts_fldOrganizations', '_legacy_israeli_experts_organization']) : '';
$more_details = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_more_details', '_legacy_israeli_experts_moreDetails']) : '';
$cv = $plugin ? $plugin->get_single_field_value($post_id, ['_legacy_israeli_experts_fldCv', '_legacy_israeli_experts_cv', '_legacy_israeli_experts_resume']) : '';
$email = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_email', '_legacy_clubMembers_email']) : '';
$phone = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_phone', '_legacy_clubMembers_phone']) : '';
$cellphone = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_cellphone', '_legacy_clubMembers_cellphone']) : '';
$address = $plugin ? $plugin->get_single_field_value($post_id, ['_legacy_israeli_experts_fldAddress', '_legacy_israeli_experts_address', '_legacy_clubMembers_address']) : '';
$legacy_url = $plugin ? $plugin->get_single_field_value($post_id, ['_pablo_legacy_profile_url']) : '';
$remote_image = $plugin ? $plugin->get_remote_image_value($post_id) : '';
$public_meta = $plugin ? $plugin->get_safe_public_meta($post_id) : [];
$terms = get_the_terms($post_id, $plugin ? $plugin->get_taxonomy_slug() : 'pablo_expert_category') ?: [];
?>
<style>
.pablo-expert-single{direction:rtl;text-align:right;max-width:1180px;margin:0 auto;padding:30px 16px;font-family:Arial,sans-serif}
.pablo-expert-grid{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(300px,.8fr);gap:24px}
.pablo-card{background:#fff;border:1px solid #ececec;border-radius:18px;padding:22px;box-shadow:0 8px 30px rgba(0,0,0,.05)}
.pablo-title{font-size:36px;line-height:1.2;margin:0 0 14px;color:#0a1b42}
.pablo-subgrid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:18px 0}
.pablo-stat{background:#f7f9fc;border:1px solid #edf1f7;border-radius:14px;padding:14px}
.pablo-stat-label{font-size:13px;color:#6d7788;margin-bottom:6px}.pablo-stat-value{font-size:20px;font-weight:700;color:#0a1b42}
.pablo-section-title{font-size:24px;margin:0 0 14px;color:#0a1b42}.pablo-copy{font-size:17px;line-height:1.8;color:#222}.pablo-tags{display:flex;gap:8px;flex-wrap:wrap;margin-top:12px}.pablo-tag{display:inline-flex;padding:8px 12px;border-radius:999px;background:#fff7ea;color:#8a5500;border:1px solid #ffd48c;font-size:14px;text-decoration:none}.pablo-media{display:flex;justify-content:center;align-items:flex-start}.pablo-media img{width:100%;max-width:280px;border-radius:18px;display:block}.pablo-contact-list{list-style:none;margin:0;padding:0;display:grid;gap:12px}.pablo-contact-item{padding:12px 14px;background:#f7f9fc;border-radius:14px;border:1px solid #edf1f7}.pablo-contact-label{font-size:13px;color:#6d7788;margin-bottom:4px}.pablo-contact-value{font-size:16px;color:#0a1b42;word-break:break-word}.pablo-table{width:100%;border-collapse:collapse}.pablo-table th,.pablo-table td{padding:10px;border-bottom:1px solid #eee;vertical-align:top}.pablo-table th{width:34%;color:#0a1b42;text-align:right}.pablo-breadcrumbs{margin-bottom:18px;font-size:14px;color:#6d7788}.pablo-breadcrumbs a{text-decoration:none}.pablo-legacy-note{margin-top:12px;font-size:14px;color:#6d7788}.pablo-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}.pablo-btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 16px;border-radius:12px;background:#0a1b42;color:#fff;text-decoration:none;border:1px solid #0a1b42}.pablo-btn--alt{background:#fff;color:#0a1b42}.pablo-empty{color:#6d7788}@media (max-width:900px){.pablo-expert-grid{grid-template-columns:1fr}.pablo-subgrid{grid-template-columns:1fr}}
</style>

<div class="pablo-expert-single">
    <div class="pablo-breadcrumbs"><a href="<?php echo esc_url(home_url('/')); ?>">דף הבית</a> / <a href="<?php echo esc_url(get_post_type_archive_link($plugin ? $plugin->get_post_type_slug() : 'pablo_expert')); ?>">מומחים</a> / <?php the_title(); ?></div>

    <div class="pablo-expert-grid">
        <main class="pablo-card">
            <h1 class="pablo-title"><?php the_title(); ?></h1>

            <div class="pablo-subgrid">
                <div class="pablo-stat">
                    <div class="pablo-stat-label">התמחות ראשית</div>
                    <div class="pablo-stat-value"><?php echo esc_html($specialization !== '' ? $specialization : '—'); ?></div>
                </div>
                <div class="pablo-stat">
                    <div class="pablo-stat-label">מקצוע</div>
                    <div class="pablo-stat-value"><?php echo esc_html($profession !== '' ? $profession : '—'); ?></div>
                </div>
                <div class="pablo-stat">
                    <div class="pablo-stat-label">ותק</div>
                    <div class="pablo-stat-value"><?php echo esc_html($seniority !== '' ? $seniority : '—'); ?></div>
                </div>
            </div>

            <?php if (!empty($terms)): ?>
                <div class="pablo-tags">
                    <?php foreach ($terms as $term): ?>
                        <?php if ($term instanceof WP_Term): ?>
                            <a class="pablo-tag" href="<?php echo esc_url(get_term_link($term)); ?>"><?php echo esc_html($term->name); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($education !== ''): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">פירוט תארים והשכלה</h2>
                    <div class="pablo-copy"><?php echo nl2br(esc_html($education)); ?></div>
                </section>
            <?php endif; ?>

            <?php if ($organizations !== ''): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">חבר בארגון/ים</h2>
                    <div class="pablo-copy"><?php echo nl2br(esc_html($organizations)); ?></div>
                </section>
            <?php endif; ?>

            <?php if ($more_details !== ''): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">התמחויות ומידע מקצועי משלים</h2>
                    <div class="pablo-copy"><?php echo nl2br(esc_html($more_details)); ?></div>
                </section>
            <?php elseif (get_the_content() !== ''): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">מידע מקצועי</h2>
                    <div class="pablo-copy"><?php the_content(); ?></div>
                </section>
            <?php endif; ?>

            <?php if (!empty($terms)): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">תחומי עיסוק</h2>
                    <div class="pablo-tags">
                        <?php foreach ($terms as $term): ?>
                            <?php if ($term instanceof WP_Term): ?>
                                <span class="pablo-tag"><?php echo esc_html($term->name); ?></span>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($cv !== ''): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">קורות חיים מקצועיים</h2>
                    <div class="pablo-copy"><?php echo nl2br(esc_html($cv)); ?></div>
                </section>
            <?php endif; ?>

            <?php if ($public_meta): ?>
                <section class="pablo-card" style="margin-top:20px">
                    <h2 class="pablo-section-title">פרטים נוספים מהמאגר הישן</h2>
                    <table class="pablo-table">
                        <tbody>
                        <?php foreach ($public_meta as $key => $value): ?>
                            <tr>
                                <th><?php echo esc_html($key); ?></th>
                                <td><?php echo nl2br(esc_html($value)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pablo-legacy-note">כל הערכים נשמרו כ־meta כדי שלא יאבד מידע בזמן המעבר.</div>
                </section>
            <?php endif; ?>
        </main>

        <aside class="pablo-card">
            <?php if (has_post_thumbnail()): ?>
                <div class="pablo-media"><?php the_post_thumbnail('large'); ?></div>
            <?php elseif ($remote_image !== '' && filter_var($remote_image, FILTER_VALIDATE_URL)): ?>
                <div class="pablo-media"><img src="<?php echo esc_url($remote_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></div>
            <?php else: ?>
                <div class="pablo-empty">אין תמונה כרגע.</div>
            <?php endif; ?>

            <section class="pablo-card" style="margin-top:20px">
                <h2 class="pablo-section-title">פרטי התקשרות</h2>
                <ul class="pablo-contact-list">
                    <?php if ($email !== ''): ?>
                        <li class="pablo-contact-item">
                            <div class="pablo-contact-label">דוא"ל</div>
                            <div class="pablo-contact-value"><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></div>
                        </li>
                    <?php endif; ?>
                    <?php if ($phone !== ''): ?>
                        <li class="pablo-contact-item">
                            <div class="pablo-contact-label">טלפון</div>
                            <div class="pablo-contact-value"><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></div>
                        </li>
                    <?php endif; ?>
                    <?php if ($cellphone !== ''): ?>
                        <li class="pablo-contact-item">
                            <div class="pablo-contact-label">נייד</div>
                            <div class="pablo-contact-value"><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $cellphone)); ?>"><?php echo esc_html($cellphone); ?></a></div>
                        </li>
                    <?php endif; ?>
                    <?php if ($address !== ''): ?>
                        <li class="pablo-contact-item">
                            <div class="pablo-contact-label">כתובת</div>
                            <div class="pablo-contact-value"><?php echo nl2br(esc_html($address)); ?></div>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="pablo-actions">
                    <a class="pablo-btn" href="<?php echo esc_url(get_post_type_archive_link($plugin ? $plugin->get_post_type_slug() : 'pablo_expert')); ?>">לכל המומחים</a>
                    <?php if ($legacy_url !== ''): ?>
                        <a class="pablo-btn pablo-btn--alt" href="<?php echo esc_url($legacy_url); ?>">לעמוד הישן</a>
                    <?php endif; ?>
                </div>
            </section>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
