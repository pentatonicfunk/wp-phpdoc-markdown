<?php

namespace WP_Phpdoc_Markdown;

use Webmozart\PathUtil\Path;

class Command extends \WP_Parser\Command {

	/**
	 * Generate a JSON file containing the PHPDoc markup, and create markdown docs of hooks on <output_dir>.
	 *
	 * ## OPTIONS
	 *
	 * <src_dir>
	 * : Source code directory
	 *
	 * <output_dir>
	 * : Desired Output directory of markdown documents
	 *
	 * [--json_doc=<json_doc>]
	 * : Where temporary generated json phpdoc will be created, default is /tmp/phpdoc.json
	 *
	 * ## EXAMPLES
	 *  wp wparser mdhooks <src_dir> <output_dir> [--json_doc=<json_doc>]
	 *  wp wparser mdhooks ../forminator/ ./../forminator/docs --json_doc=/tmp/phpdoc.json
	 *
	 * @param array $args
	 *
	 * @param       $assoc_args
	 *
	 * @throws \WP_CLI\ExitException
	 */
	public function mdhooks( $args, $assoc_args ) {
		try {
			$tmp_json = trailingslashit( sys_get_temp_dir() ) . 'phpdoc.json';
			if ( isset( $assoc_args['json_doc'] ) ) {
				$tmp_json = $assoc_args['json_doc'];
			}
			$export_args = array(
				$args[0],
				$tmp_json,
			);

			$output_dir = $args[1];

			$dir = Path::makeAbsolute( $output_dir, getcwd() );
			if ( is_dir( $dir ) ) {
				\WP_CLI::confirm( $dir . ' is exist, are you sure want to remove it\'s contents ?' );
			}

			// extended from WP-Parser
			$this->export( $export_args );

			$output_file = $tmp_json;


			$phpdoc = json_decode( file_get_contents( $output_file ), true );

			$hooks = array();
			foreach ( $phpdoc as $file ) {
				wp_phpdoc_get_hooks( $file['path'], $file, $hooks );
			}

			if ( empty( $hooks ) ) {
				\WP_CLI::error( 'Found No Hooks' );
			}

			\WP_CLI::log( 'Found ' . count( $hooks ) . ' Hooks' );

			$found_types              = array();
			$hook_sorted_source_paths = array();
			foreach ( $hooks as $key => $hook ) {
				if ( ! in_array( $hook['type'], array_keys( $found_types ), true ) ) {
					$found_types[ $hook['type'] ] = 1;
				} else {
					$found_types[ $hook['type'] ] = $found_types[ $hook['type'] ] + 1;
				}
				$hook_sorted_source_paths[ $key ] = $hook['source_path'] . ':' . str_pad( $hook['line'], 4, 0, STR_PAD_LEFT );
			}

			array_multisort( $hook_sorted_source_paths, SORT_ASC, $hooks );

			foreach ( $found_types as $type => $num ) {
				\WP_CLI::log( 'Found ' . $num . ' ' . $type );
				//pluralize
			}

			$home_tocs = array();

			$files_generated = array();

			//clear output dir
			//TODO: use temp dir to save last
			$output_dir = trailingslashit( $output_dir );
			$output_dir = Path::makeAbsolute( $output_dir, getcwd() );
			wp_phpdoc_markdown_rmrf( $output_dir );
			mkdir( $output_dir, 0777, true );

			//write markdown
			foreach ( $hooks as $hook ) {

				$dir = trailingslashit( $output_dir ) . $hook['type'] . 's';
				$dir = Path::makeAbsolute( $dir, getcwd() );

				// file
				$file_php     = basename( $hook['source_path'] );
				$file_md      = str_replace( '.php', '.md', $file_php );
				$path_file_md = str_replace( $file_php, $file_md, $hook['source_path'] );
				$file         = trailingslashit( $dir ) . $path_file_md;


				if ( ! in_array( $file, $files_generated, true ) ) {
					$md_dir = Path::getDirectory( $file );
					if ( ! is_dir( $md_dir ) ) {
						mkdir( $md_dir, 0777, true );
					}
					file_put_contents( $file, '[TOC]' . PHP_EOL, FILE_APPEND );
					$files_generated[] = $file;
				}


				if ( ! isset( $home_tocs[ $hook['type'] ] ) ) {
					$home_tocs[ $hook['type'] ] = array();
				}

				$home_tocs[ $hook['type'] ][] = array(
					'name'      => sanitize_title( $hook['name'] ),
					'orig_name' => $hook['name'],
					'file'      => $file,
				);
				wp_phpdoc_markdown_append_hook_doc( $hook, $file );
			}

			$toc_template = wp_phpdoc_markdown_get_template( 'toc' );
			$toc_template = file_get_contents( $toc_template );
			$tocs_list    = array();
			foreach ( $home_tocs as $type => $tocs ) {
				\WP_CLI::log( 'Generating TOC for ' . $type );
				$tocs_list[] = '- ' . strtoupper( $type );
				foreach ( $tocs as $toc ) {
					$rel         = Path::makeRelative( $toc['file'], $output_dir );
					$link        = $rel . '#markdown-header-' . $toc['name'];
					$tocs_list[] = "\t- " . '[' . $toc['orig_name'] . '](' . $link . ')';
				}
			}
			$tocs_list    = implode( PHP_EOL, $tocs_list );
			$toc_template = str_replace( '{{TOC}}', $tocs_list, $toc_template );
			file_put_contents( trailingslashit( $output_dir ) . 'README.md', $toc_template );
			\WP_CLI::success( 'TOC Generated in ' . trailingslashit( $output_dir ) . 'README.md' );


			\WP_CLI::success( 'Markdown Generated in ' . $output_dir );

		} catch ( \Exception $e ) {
			\WP_CLI::error( $e->getMessage() );
		}

	}

}
