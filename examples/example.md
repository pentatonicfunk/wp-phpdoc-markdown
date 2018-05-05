[TOC]
## forminator_custom_form_before_handle_submit
### Code
```php
<?php
do_action( "forminator_custom_form_before_handle_submit", $form_id );
```

Action called before full form submit
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$form_id**  | (int)   | - the form id   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-89)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_before_handle_submit( $form_id ){
	// do some action.

}
add_action( "forminator_custom_form_before_handle_submit", "add_action_forminator_custom_form_before_handle_submit" );
```


---

## forminator_custom_form_after_handle_submit
### Code
```php
<?php
do_action( "forminator_custom_form_after_handle_submit", $form_id, $response );
```

Action called after full form submit
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$form_id**  | (int)   | - the form id   |
| **$response**  | (array)   | - the post response   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-113)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_after_handle_submit( $form_id, $response ){
	// do some action.

}
add_action( "forminator_custom_form_after_handle_submit", "add_action_forminator_custom_form_after_handle_submit" );
```


---

## forminator_custom_form_before_save_entry
### Code
```php
<?php
do_action( "forminator_custom_form_before_save_entry", $form_id, 'submit' );
```

Action called before form ajax
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$form_id**  | (int)   | - the form id   |
| **'submit'**  | -   | -   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-155)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_before_save_entry( $form_id, $submit ){
	// do some action.

}
add_action( "forminator_custom_form_before_save_entry", "add_action_forminator_custom_form_before_save_entry" );
```


---

## forminator_custom_form_after_save_entry
### Code
```php
<?php
do_action( "forminator_custom_form_after_save_entry", $form_id, $response, 'submit' );
```

Action called after form ajax
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$form_id**  | (int)   | - the form id   |
| **$response**  | (array)   | - the post response   |
| **'submit'**  | -   | -   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-181)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_after_save_entry( $form_id, $response, $submit ){
	// do some action.

}
add_action( "forminator_custom_form_after_save_entry", "add_action_forminator_custom_form_after_save_entry" );
```


---

## forminator_custom_form_before_save_entry
### Code
```php
<?php
do_action( "forminator_custom_form_before_save_entry", $payment_id, 'payment' );
```

Action called before form payment
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$payment_id**  | (int)   | - the payment id   |
| **'payment'**  | -   | -   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-198)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_before_save_entry( $payment_id, $payment ){
	// do some action.

}
add_action( "forminator_custom_form_before_save_entry", "add_action_forminator_custom_form_before_save_entry" );
```


---

## forminator_custom_form_after_save_entry
### Code
```php
<?php
do_action( "forminator_custom_form_after_save_entry", $payment_id, $response, 'payment' );
```

Action called after form payment
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$payment_id**  | (int)   | - the payment id   |
| **$response**  | (array)   | - the post response   |
| **'payment'**  | -   | -   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-224)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_after_save_entry( $payment_id, $response, $payment ){
	// do some action.

}
add_action( "forminator_custom_form_after_save_entry", "add_action_forminator_custom_form_after_save_entry" );
```


---

## forminator_custom_form_submit_before_set_fields
### Code
```php
<?php
do_action( "forminator_custom_form_submit_before_set_fields", $entry, $form_id, $field_data_array );
```

Action called before setting fields to database
### Description

### Parameters
| Name     | Type    | Description |
| ---------|---------|-------|
| **$entry**  | (\Forminator_Form_Entry_Model)   | - the entry model   |
| **$form_id**  | (int)   | - the form id   |
| **$field_data_array**  | (array)   | - the entry data   |


### Source
[library/modules/custom-forms/front/front-action.php](../../../../../../library/modules/custom-forms/front/front-action.php#lines-551)
### Changelog
| Version | Description |
| ---------|---------|
| **1.0.2**  | Added   |


### Example Usage
```php
<?php
function add_action_forminator_custom_form_submit_before_set_fields( $entry, $form_id, $field_data_array ){
	// do some action.

}
add_action( "forminator_custom_form_submit_before_set_fields", "add_action_forminator_custom_form_submit_before_set_fields" );
```


---
