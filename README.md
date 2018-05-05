# WP phpDoc Markdown

Create Markdown documentations for WordPress Code. Currently its only creating docs for `hooks`. `Functions` and `Classes` not included yet.

## History and Credits
 This project is based on [WordPress/phpdoc-parser](https://github.com/WordPress/phpdoc-parser).
 
 Inline documentation should follow [WordPress Best Practices](https://make.wordpress.org/core/handbook/best-practices/inline-documentation-standards/php/)

## Requirements
* PHP 5.4+
* [Composer](https://getcomposer.org/)
* [WP CLI](http://wp-cli.org/)

Clone the repository into your WordPress plugins directory:

```bash
git clone git@github.com:pentatonicfunk/wp-phpdoc-markdown.git 
```

After that install the dependencies using composer in the parser directory:

```bash
composer install
```

## Running
Activate the plugin first:

    wp plugin activate wp-phpdoc-markdown

In your site's directory / `wp-phpdoc-markdown` directory:

    wp wparser mdhooks <src_dir> <output_dir> [--json_doc=<json_doc>]
    wp wparser mdhooks ../forminator/ ./../forminator/docs
    
Sample Output : [example.md](examples/example.md)

## Customization
All default used templates are placed in [templates](templates)

To Customize it define this constant in your `wp-config.php`
```php
define( 'WP_PHPDOC_MARKDOWN_TEMPLATE_TOC', '/path/to/toc.md' );
define( 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK', __DIR__ . '/path/to/hook.md' );
define( 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_PARAMETERS', '/path/to/hook.parameters.md' );
define( 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_PARAMETER', '/path/to/hook.parameter.md' );
define( 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_CHANGELOGS', '/path/to/hook.changelogs.md' );
define( 'WP_PHPDOC_MARKDOWN_TEMPLATE_HOOK_CHANGELOG', '/path/to/hook.changelog.md' );
```

## Usage Help
### NAME

    wp wparser mdhooks

### DESCRIPTION

    Generate a JSON file containing the PHPDoc markup, and create markdown docs of hooks on <output_dir>.

### SYNOPSIS

    wp wparser mdhooks <src_dir> <output_dir> [--json_doc=<json_doc>]

### OPTIONS

    <src_dir>
        Source code directory

    <output_dir>
        Desired Output directory of markdown documents

    [--json_doc=<json_doc>]
        Where temporary generated json phpdoc will be created, default is /tmp/phpdoc.json

### EXAMPLES

    wp wparser mdhooks <src_dir> <output_dir> [--json_doc=<json_doc>]
    wp wparser mdhooks ../forminator/ ./../forminator/docs --json_doc=/tmp/phpdoc.json
    

