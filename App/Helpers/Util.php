<?php

namespace  wlper\App\Helper;

defined('ABSPATH') or exit;

class Util {
    public static function isMethodExists( $object_or_class, $method ) {
        return ( is_object( $object_or_class ) || is_string( $object_or_class ) ) && method_exists( $object_or_class, $method );
    }

    /**
     * Check is HPOS enabled.
     *
     * @return bool
     */
    public static function isHPOSEnabled() {
        if ( ! class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) ) {
            return false;
        }
        if ( \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
            return true;
        }

        return false;
    }

    /**
     * render template.
     *
     * @param string $file File path.
     * @param array $data Template data.
     * @param bool $display Display or not.
     *
     * @return string|void
     */
    public static function renderTemplate( string $file, array $data = [], bool $display = true ) {
        $content = '';
        if ( file_exists( $file ) ) {
            ob_start();
            extract( $data );
            include $file;
            $content = ob_get_clean();
        }
        if ( $display ) {
            echo $content;
        } else {
            return $content;
        }
    }

    /**
     * Add admin notice.
     *
     * @param string $message Message.
     * @param string $status Status.
     *
     * @return void
     */
    public static function adminNotice( string $message, string $status = "success" ) {

        add_action( 'admin_notices', function () use ( $message, $status ) {
            ?>
            <div class="notice notice-<?php echo esc_attr( $status ); ?>">
                <p><?php echo wp_kses_post( $message ); ?></p>
            </div>
            <?php
        }, 1 );
    }


    /**
     * Create nonce for woocommerce.
     *
     * @param string $action
     *
     * @return false|string
     */
    public static function createNonce( string $action = '' ) {
        if ( empty( $action ) ) {
            return false;
        }

        return wp_create_nonce( $action );
    }

    /**
     * Check the validity of a security nonce and the admin privilege.
     *
     * @param string $nonce_name The name of the nonce.
     *
     * @return bool
     */
    public static function isSecurityValid( string $nonce_name = '' ) {

        $wlper_nonce = empty( $_POST['wlper_nonce'] ) ? '' : $_POST['wlper_nonce'];
        if ( ! self::hasAdminPrivilege() || ! self::verifyNonce( $wlper_nonce, $nonce_name ) ) {
            return false;
        }

        return true;
    }

    /**
     * Has admin privilege.
     *
     * @return bool
     */
    public static function hasAdminPrivilege(): bool {
        return current_user_can( 'manage_woocommerce' );
    }

    /**
     * Verify nonce.
     *
     * @param string $nonce Nonce.
     * @param string $action Action.
     *
     * @return bool
     */
    public static function verifyNonce( string $nonce, string $action = '' ): bool {
        if ( empty( $nonce ) || empty( $action ) ) {
            return false;
        }

        return wp_verify_nonce( $nonce, $action );
    }

}