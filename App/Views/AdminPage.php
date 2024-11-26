<div class="wrap">
    <h1><?php esc_html_e('Loyalty Users', 'wp-loyalty'); ?></h1>
    <table class="widefat fixed striped">
        <thead>
        <tr>
            <th><?php esc_html_e('User ID', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Email', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Points', 'wp-loyalty'); ?></th>
            <th><?php esc_html_e('Actions', 'wp-loyalty'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo esc_html($user->id); ?></td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><?php echo esc_html($user->points); ?></td>
                    <td>
                        <a href="<?php echo esc_url(admin_url('admin-post.php?action=send_email_to_user&id=' . $user->id)); ?>" class="button">
                            <?php esc_html_e('Send Email', 'wp-loyalty'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><?php esc_html_e('No users found.', 'wp-loyalty'); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
