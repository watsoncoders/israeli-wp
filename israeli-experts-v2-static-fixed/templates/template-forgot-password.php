<?php
/**
 * Template Name: Pablo Forgot Password
 * author: pablo rotem
 */

if (!defined('ABSPATH')) {
	exit;
}

get_header();
?>

<div class="pablo-form-wrap">
	<h1><?php the_title(); ?></h1>

	<?php if (isset($_GET['pablo_reset']) && $_GET['pablo_reset'] === 'success') : ?>
		<div class="pablo-notice pablo-notice-success">נשלח אליך מייל לאיפוס הסיסמה.</div>
	<?php elseif (isset($_GET['pablo_reset']) && $_GET['pablo_reset'] === 'error') : ?>
		<div class="pablo-notice pablo-notice-error">לא הצלחנו לשלוח מייל איפוס. בדוק את שם המשתמש או האימייל.</div>
	<?php endif; ?>

	<form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
		<input type="hidden" name="action" value="pablo_forgot_password">
		<?php wp_nonce_field('pablo_forgot_password', 'pablo_forgot_nonce'); ?>

		<p>
			<label for="user_login">שם משתמש או אימייל</label><br>
			<input type="text" name="user_login" id="user_login" required>
		</p>

		<p>
			<button type="submit">שלח קישור איפוס</button>
		</p>
	</form>
</div>

<?php get_footer(); ?>