<?php
/**
 * Plugin Name: WP phpDoc Markdown
 * Description: Create Markdown form phpDoc
 * Author: Hendrawan Kuncoro and Contributors
 * Author URI: https://premium.wpmudev.org
 * Plugin URI: https://github.com/pentatonicfunk/wp-phpdoc-markdown
 * Version:
 * Text Domain: wp-parser
 */

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

define( 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_TOC', __DIR__ . '/templates/toc.md' );
define( 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK', __DIR__ . '/templates/hook.md' );
define( 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_PARAMETERS', __DIR__ . '/templates/hook.parameters.md' );
define( 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_PARAMETER', __DIR__ . '/templates/hook.parameter.md' );
define( 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_CHANGELOGS', __DIR__ . '/templates/hook.changelogs.md' );
define( 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_CHANGELOG', __DIR__ . '/templates/hook.changelog.md' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 'wparser', 'WP_Phpdoc_Markdown\Command' );
}
