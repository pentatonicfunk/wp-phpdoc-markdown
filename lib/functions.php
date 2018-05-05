<?php

/**
 * Get All hooks recursively
 *
 * @param $source_path
 * @param $file
 * @param $hooks
 */
function wp_phpdoc_get_hooks( $source_path, $file, &$hooks ) {
	if ( is_array( $file ) ) {
		if ( isset( $file['hooks'] ) ) {
			$new_hooks = $file['hooks'];
			foreach ( $new_hooks as $key => $new_hook ) {
				$new_hooks[ $key ]['source_path'] = $source_path;
			}
			$hooks = array_merge( $hooks, $new_hooks );
		}

		foreach ( $file as $data ) {
			wp_phpdoc_get_hooks( $source_path, $data, $hooks );
		}
	}
}

/**
 * Appending Hook to markdown doc
 *
 * @param $hook
 * @param $file
 * @param $source_dir
 *
 * @throws Exception
 */
function wp_phpdoc_markdown_append_hook_doc( $hook, $file, $source_dir ) {
	$template    = wp_phpdoc_markdown_get_template( 'hook' );
	$template    = file_get_contents( $template );
	$template    = str_replace( '{{TAG}}', $hook['name'], $template );
	$description = wp_phpdoc_markdown_simple_html_to_markdown( $hook['doc']['description'] );
	$long_desc   = wp_phpdoc_markdown_simple_html_to_markdown( $hook['doc']['long_description'] );

	$template = str_replace( '{{SUMMARY}}', $description, $template );
	$template = str_replace( '{{DESCRIPTION}}', $long_desc, $template );
	$template = str_replace( '{{SOURCE_CODE}}', wp_phpdoc_markdown_get_template_build_source_code( $hook ), $template );
	$template = str_replace( '{{EXAMPLE}}', wp_phpdoc_markdown_get_template_build_example( $hook ), $template );

	$out_dir = \Webmozart\PathUtil\Path::getDirectory( $file );
	$out_dir = \Webmozart\PathUtil\Path::makeAbsolute( $out_dir, getcwd() );
	$src_dir = \Webmozart\PathUtil\Path::makeAbsolute( $source_dir, getcwd() );
	$src_dir = \Webmozart\PathUtil\Path::makeAbsolute( $hook['source_path'], $src_dir );

	$rel = \Webmozart\PathUtil\Path::makeRelative( $src_dir, $out_dir );

	$template = str_replace( '{{SOURCE}}', '[' . $hook['source_path'] . '](' . $rel . '#lines-' . $hook['line'] . ')', $template );

	$arguments  = $hook['arguments'];
	$params_doc = array();
	foreach ( $hook['doc']['tags'] as $tag ) {
		if ( 'param' === $tag['name'] ) {
			$params_doc[ $tag['variable'] ] = array(
				'types'       => '(' . implode( '|', $tag['types'] ) . ')',
				'description' => $tag['content'],
			);
		}
	}


	$template_parameters = wp_phpdoc_markdown_get_template( 'hook.parameters' );
	$template_parameters = file_get_contents( $template_parameters );

	$template_parameter = wp_phpdoc_markdown_get_template( 'hook.parameter' );
	$template_parameter = file_get_contents( $template_parameter );

	$all_parameters = '';
	if ( count( $arguments ) > 0 ) {
		foreach ( $arguments as $argument ) {

			$parameters = str_replace( '{{NAME}}', $argument, $template_parameter );
			if ( isset( $params_doc[ $argument ] ) ) {
				$parameters = str_replace( '{{TYPE}}', $params_doc[ $argument ]['types'], $parameters );
				$parameters = str_replace( '{{DESC}}', wp_phpdoc_markdown_simple_html_to_markdown( $params_doc[ $argument ]['description'] ), $parameters );
			} else {
				$parameters = str_replace( '{{TYPE}}', '-', $parameters );
				$parameters = str_replace( '{{DESC}}', '-', $parameters );
			}

			$all_parameters .= $parameters;
		}
		$all_parameters = str_replace( '{{PARAMETERS}}', $all_parameters, $template_parameters );

	}
	$template = str_replace( '{{PARAMETERS}}', $all_parameters, $template );
	$template = str_replace( '{{CHANGELOGS}}', wp_phpdoc_markdown_get_template_build_changelogs( $hook ), $template );
	file_put_contents( $file, $template . PHP_EOL, FILE_APPEND );
}

/**
 * Same as `rm -rf <dir>`
 *
 * @param $dir
 */
function wp_phpdoc_markdown_rmrf( $dir ) {
	foreach ( glob( $dir ) as $file ) {
		if ( is_dir( $file ) ) {
			wp_phpdoc_markdown_rmrf( "$file/*" );
			rmdir( $file );
		} else {
			unlink( $file );
		}
	}
}

/**
 * Retrieving template file
 *
 * @param $name
 *
 * @return string
 * @throws Exception
 */
function wp_phpdoc_markdown_get_template( $name ) {
	$defined_templates = array(
		'toc'             => array(
			'plugin'    => 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_TOC',
			'wp_config' => 'WP_PHPDOC_MARKDOWN_TEMPLATE_TOC',
		),
		'hook'            => array(
			'plugin'    => 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK',
			'wp_config' => 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK',
		),
		'hook.parameters' => array(
			'plugin'    => 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_PARAMETERS',
			'wp_config' => 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_PARAMETERS',
		),
		'hook.parameter'  => array(
			'plugin'    => 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_PARAMETER',
			'wp_config' => 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_PARAMETER',
		),
		'hook.changelogs' => array(
			'plugin'    => 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_CHANGELOGS',
			'wp_config' => 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_CHANGELOGS',
		),
		'hook.changelog'  => array(
			'plugin'    => 'WP_PHPDOC_MARKDOWN_DEFAULTS_TEMPLATE_HOOK_CHANGELOG',
			'wp_config' => 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_CHANGELOG',
		),
	);

	if ( ! in_array( $name, array_keys( $defined_templates ), true ) ) {
		throw new Exception( 'template not defined' );
	}

	$defined_const = get_defined_constants();
	// wp-config
	if ( in_array( $defined_templates[ $name ]['wp_config'], array_keys( $defined_const ), true ) ) {
		$defined_templates = $defined_templates[ $name ];

		return $defined_const[ $defined_templates['wp_config'] ];
	}

	if ( in_array( $defined_templates[ $name ]['plugin'], array_keys( $defined_const ), true ) ) {
		$defined_templates = $defined_templates[ $name ];

		return $defined_const[ $defined_templates['plugin'] ];
	}

	throw new Exception( 'Template Not Defined ' . wp_json_encode( array( $name, $defined_templates ) ) );
}

/**
 * Build Source Code of hook
 *
 * @param $hook
 *
 * @return string
 */
function wp_phpdoc_markdown_get_template_build_source_code( $hook ) {

	$source_code = '```php' . PHP_EOL . '<?php' . PHP_EOL;
	$func_call   = '';
	switch ( $hook['type'] ) {
		case 'action':
			$func_call = 'do_action';
			break;
		case 'filter':
			$func_call = 'apply_filters';
			break;
		case 'action_reference':
			$func_call = 'do_action_ref_array';
			break;

		case 'filter_reference':
			$func_call = 'apply_filters_ref_array';
			break;
	}

	$params      = array_merge( array(
		                            '"' . $hook['name'] . '"',
	                            ),
	                            $hook['arguments'] );
	$source_code .= $func_call . '( ' . implode( ', ', $params ) . ' );' . PHP_EOL;
	$source_code .= '```' . PHP_EOL;

	return $source_code;
}

/**
 * Build example usage
 *
 * @param $hook
 *
 * @return string
 */
function wp_phpdoc_markdown_get_template_build_example( $hook ) {
	$sample_code = '```php' . PHP_EOL . '<?php' . PHP_EOL;
	$func_call   = '';
	$params      = array();
	switch ( $hook['type'] ) {
		case 'action':
			$func_call = 'add_action';
			$params    = $hook['arguments'];
			break;
		case 'filter':
			$func_call = 'add_filter';
			$params    = $hook['arguments'];
			break;
		case 'action_reference':
			$func_call = 'add_action';
			$params    = array();
			if ( isset( $hook['arguments'][0] ) ) {
				// manually parsing to avoid `eval`
				// `++` is only delimiter
				$params = '++' . $hook['arguments'][0] . '++';
				$params = str_replace( '++array(', '', $params );
				$params = str_replace( ')++', '', $params );
				$params = str_replace( '$this', '$class_object', $params );
				$params = explode( ',', $params );
			}
			break;
		case 'filter_reference':
			$func_call = 'add_filter';
			$params    = $hook['arguments'][0];
			break;
	}

	$func_name_to_hook = $func_call . '_' . $hook['name'];
	$func_name_to_hook = sanitize_title( $func_name_to_hook );
	$func_name_to_hook = str_replace( '-', '_', $func_name_to_hook );

	// normalize param
	foreach ( $params as $key => $param ) {
		$param          = str_replace( '$this', '$class_object', $param );
		$param          = str_replace( '$', '', $param );
		$param          = sanitize_title( $param );
		$param          = str_replace( '-', '_', $param );
		$params[ $key ] = '$' . $param;
	}
	$sample_code .= 'function ' . $func_name_to_hook . '( ' . implode( ', ', $params ) . ' ){' . PHP_EOL;
	if ( 'add_filter' === $func_call ) {
		$sample_code .= "\t// do some filters." . PHP_EOL . PHP_EOL;
		$sample_code .= "\treturn " . $params[0] . ';' . PHP_EOL;
	} else {
		$sample_code .= "\t// do some action." . PHP_EOL . PHP_EOL;
	}
	$sample_code .= '}' . PHP_EOL;

	$params      = array(
		'"' . $hook['name'] . '"',
		'"' . $func_name_to_hook . '"',
	);
	$sample_code .= $func_call . '( ' . implode( ', ', $params ) . ' );' . PHP_EOL;
	$sample_code .= '```' . PHP_EOL;

	return $sample_code;
}

/**
 * Build Changelogs
 *
 * @param $hook
 *
 * @return mixed|string
 * @throws Exception
 */
function wp_phpdoc_markdown_get_template_build_changelogs( $hook ) {
	$template_changelogs = wp_phpdoc_markdown_get_template( 'hook.changelogs' );
	$template_changelogs = file_get_contents( $template_changelogs );

	$template_changelog = wp_phpdoc_markdown_get_template( 'hook.changelog' );
	$template_changelog = file_get_contents( $template_changelog );

	$sinces = array();
	foreach ( $hook['doc']['tags'] as $key => $tag ) {
		if ( 'since' === $tag['name'] ) {
			$description = '';
			if ( 0 === $key && ! isset( $tag['description'] ) ) {
				$description = 'Added';
			}
			$sinces[] = array(
				'version'     => $tag['content'],
				'description' => isset( $tag['description'] ) ? $tag['description'] : $description,
			);
		}
	}

	if ( empty( $sinces ) ) {
		return '';
	}

	$all_versions = '';
	foreach ( $sinces as $since ) {
		$version      = str_replace( '{{VERSION}}', $since['version'], $template_changelog );
		$version      = str_replace( '{{DESC}}', wp_phpdoc_markdown_simple_html_to_markdown( $since['description'] ), $version );
		$all_versions .= $version;
	}

	$changelogs = str_replace( '{{CHANGELOGS}}', $all_versions, $template_changelogs );

	return $changelogs;
}

/**
 * Simple converter from html to MarkDown
 */
function wp_phpdoc_markdown_simple_html_to_markdown( $html ) {
	$markdown = $html;

	//@ tag on bitbucket will mentioning user
	$markdown = str_replace( '@', '`@`', $markdown );

	//<p> generated by wp-phpdoc-parser
	$markdown = str_replace( '<p>', '', $markdown );
	$markdown = str_replace( '</p>', '', $markdown );

	//<strong> generated by wp-phpdoc-parser
	$markdown = str_replace( '<strong>', '**', $markdown );
	$markdown = str_replace( '</strong>', '**', $markdown );

	return $markdown;
}
