<?php
/***************************************************************************
 *                               forms.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/




/**
 * Returns a form based on the values passed.
 *
 * @access public
 *
 * @param mixed $id
 * @param mixed $action
 * @param mixed $title
 * @param mixed $buttonText
 * @param array $formFields (default: array())
 * @param array $formData (default: array())
 * @param int $isAJAX (default: 0)
 * @param int $showClearButton (default: 0)
 * @param string $enctype (default: '')
 * @param array $extraOptions (default: array())
 *
 * @return string                                        The actual form HTML
 */
function makeForm( $id, $action, $title, $buttonText, $formFields = array(), $formData = array(), $isAJAX = 0, $showClearButton = 0, $enctype = '', $extraOptions = array() ) {
	global $menuvar, $mbp_config;
	$tabLinks = $tabHolders = $extraPrimaryButtonClasses = $extraButtons = '';

	// Handle sections for sub tabs
	$firstKey = key( $formFields );
	//print_r($formFields[$firstKey]);
	if ( isset ( $formFields[ $firstKey ]['tabData'] ) ) {
		// Tabbed form
		$x = 1;

		foreach ( $formFields as $tabID => $tabData ) {
			$tabLinks   .= '
				<li' . ( ( $x == 1 ) ? ' class="active"' : '' ) . '><a href="#' . $tabID . '" data-toggle="tab"><span>' . $tabData['title'] . '</span></a></li>';
			$tabHolders .= '
				<div id="' . $tabID . '" class="tab-pane' . ( ( $x == 1 ) ? ' active' : '' ) . '">
					' . makeFormFieldset( $tabData['title'], $tabData['tabData'], $formData, 0 ) . '
				</div>';
			$x ++;
		}
		$tabLinks = '
			<div class="toolbar">
				<ul class="nav nav-tabs">
					' . $tabLinks . '
				</ul>
			</div>';
	} else {
		// Normal form
		$tabHolders = makeFormFieldset( $title, $formFields, $formData, 0 );
	}

	// Handle extraOptions
	if ( count( $extraOptions ) > 0 ) {
		if ( isset( $extraOptions['extraPrimaryButtonClasses'] ) ) {
			$extraPrimaryButtonClasses = $extraOptions['extraPrimaryButtonClasses'];
		}
		if ( isset( $extraOptions['extraButtons'] ) ) {
			$extraButtons = $extraOptions['extraButtons'];
		}
	}

	$returnVar = apply_filters( 'form_html_' . $id, '
			<div class="box tabbable">
				<div class="box-header">
					<h3>' . $title . '</h3>
					' . $tabLinks . '
				</div>
				<div class="tab-content form-container noPadding">
					<form name="' . $id . 'Form" id="' . $id . 'Form" action="' . $action . '" method="post" class="form-horizontal" role="form"' . ( ( ! empty( $enctype ) ) ? ' enctype="' . $enctype . '"' : '' ) . ( ( $isAJAX ) ? ' onsubmit="return false;"' : '' ) . '>
						' . $tabHolders . '
						<div class="form-actions"><input type="submit" name="submit" class="btn btn-primary' . $extraPrimaryButtonClasses . '" value="' . $buttonText . '" />' . ( ( $showClearButton ) ? ' <input type="button" class="btn btn-default clearFormButton" value="Clear Form" />' : '' ) . $extraButtons . '</div>
					</form>
					<div id="' . $id . 'Response"></div>
				</div>
			</div>' );

	return $returnVar;
}

/**
 * Returns a forms jquery based on the values passed.
 *
 * @access public
 *
 * @param mixed $id
 * @param string $action (default: '')
 * @param string $table (default: '')
 * @param string $type (default: '')
 * @param string $extraValidationStuff (default: '')
 * @param string $customSuccessFunction (default: '')
 * @param string $updateHolderID (default: '')
 * @param int $clearForm (default: 0)
 *
 * @return string                                            The actual form jQuery
 */
function makeFormJQuery( $id, $action = '', $table = '', $type = '', $extraValidationStuff = '', $customSuccessFunction = '', $updateHolderID = '', $clearForm = 0 ) {
	$updateHolderID = ( empty( $updateHolderID ) ) ? $id . 'Response' : $updateHolderID;
	$extraJQuery    = ( empty( $table ) ) ? '
					// Update the proper div with the returned data
					$("#' . $updateHolderID . '").html(data);
					$("#' . $updateHolderID . '").effect("highlight",{},500);'
		: '
					// Clear the default row
					$("#' . $table . 'DefaultRow").remove();
					// Update the table with the new row
					$("#' . $table . ' > tbody:last").append(data);
					$("#' . $table . 'UpdateNotice").html(\'' . tableUpdateNoticeHTML() . '\');
					// Show a success message
					$("#' . $updateHolderID . '").html(returnSuccessMessage("' . $type . '"));';
	if ( ! empty( $customSuccessFunction ) ) {
		$extraJQuery = $customSuccessFunction;
	}
	if ( $clearForm ) {
		$extraJQuery .= '$("#' . $id . 'Form").clearForm();';
	}

	$JQueryReadyScripts = '
		var v = jQuery("#' . $id . 'Form").submit(function() {
			// update underlying textarea before submit validation
			tinyMCE.triggerSave();
		}).validate({
			highlight: function(element) {
				$(element).closest(\'.form-group\').addClass(\'has-error\');
			},
			unhighlight: function(element) {
				$(element).closest(\'.form-group\').removeClass(\'has-error\');
			},
			errorElement: \'span\',
			errorClass: \'help-block\',
			errorPlacement: function(error, element) {
				if(element.parent(\'.input-group\').length) {
					error.insertAfter(element.parent());
				} else {
					error.insertAfter(element);
				}
			},' . trim( $extraValidationStuff ) . '
			' . ( ( ! empty( $action ) ) ? '
			submitHandler: function(form) {	
				$("#' . $updateHolderID . '").html(\'' . progressSpinnerHTML() . '\');		
				jQuery.post("' . $action . '", $("#' . $id . 'Form").serialize(), function(data) {
					' . $extraJQuery . '
				});
			}
			' : '' ) . '
		});';

	return apply_filters( 'form_jquery_' . $id, $JQueryReadyScripts );
}

/**
 * Returns a form fieldset based on the values passed.
 *
 * Separated from the main function since we can then build custom forms more easily
 *
 * @access public
 *
 * @param mixed $title
 * @param array $formFields (default: array())
 * @param array $formData (default: array())
 * @param int $addLegend (default: 1)
 *
 * @return string                                The form fieldset
 */
function makeFormFieldset( $title, $formFields = array(), $formData = array(), $addLegend = 1 ) {
	global $menuvar, $mbp_config;

	// Handle sections for sub tabs

	$returnVar = '
				<fieldset>';
	if ( $addLegend ) {
		$returnVar .= '
					<legend>' . __( $title ) . '</legend>';
	}

	foreach ( $formFields as $name => $settingInfo ) {
		$formItem = $settingInfo;
		if ( ! is_array( $settingInfo ) ) {
			// Get a text field for this value
			$formItem = array(
				'name' => $name,
				'text' => $settingInfo
			);
		}
		if ( ! isset ( $formItem['name'] ) ) {
			$formItem['name'] = $name;
		}
		if ( isset ( $formData[ $formItem['name'] ] ) ) {
			$formItem['currentValue'] = $formData[ $formItem['name'] ];
		}

		$returnVar .= getFormItemFromArray( $formItem );
	}

	$returnVar .= '
				</fieldset>';

	return $returnVar;
}

/**
 * Add prefix to settings
 *
 * @access public
 *
 * @param mixed $prefix
 * @param mixed $formFields
 *
 * @return array                The prefixed form field array
 */
function addPrefixToFormFields( $prefix, $formFields ) {
	$prefixedFormFields = array();
	foreach ( $formFields as $name => $settingInfo ) {
		$prefixedFormFields[ $prefix . $name ] = $settingInfo;
	}

	return $prefixedFormFields;
}

//=========================================================
// Returns a form line based on the values passed
//=========================================================
function getFormItemFromArray( $rowData = array() ) {
	global $mbp_config;

	// Make sure we are actually looking at an array
	if ( is_array( $rowData ) ) {
		$defaults = array(
			'append'                     => '',
			'appendButton'               => '',
			'autocomplete'               => '',
			'autofocus'                  => 'false',
			'cols'                       => '58', // Makes selects match roughly the same width as an input
			'cols_label_class'           => 'col-lg-2',
			'cols_input_container_class' => 'col-lg-10',
			'class'                      => '',
			'currentValue'               => '',
			'data_animated'              => 'true',
			'data_label_icon'            => '',
			'data_on'                    => 'primary',
			'data_on_text'               => 'ON',
			'data_off'                   => 'default',
			'data_off_text'              => 'OFF',
			'data_label_text'            => '',
			'default'                    => '',
			'disabled'                   => 'false',
			'formGroupID'                => '',
			'group'                      => '',
			'help_block'                 => '',
			'inputmode'                  => '',
			'id'                         => '',
			'max'                        => '',
			'maxlength'                  => '',
			'min'                        => '',
			'multiple'                   => 'false',
			'name'                       => '',
			'options'                    => array(),
			'pattern'                    => '',
			'placeholder'                => '',
			'prepend'                    => '',
			'prependButton'              => '',
			'readonly'                   => 'false',
			'required'                   => 'false',
			'rows'                       => '10',
			'showLabel'                  => 1,
			'showRequiredText'           => 1,
			'size'                       => '60',
			'step'                       => '',
			'text'                       => '',
			'type'                       => 'text',
			'value'                      => '',
		);
		extract( $rowData = prepArrayDefaults( $defaults, $rowData ) );

		// Fix any variables
		//$name = $name;
		$id           = ( ! empty( $id ) ) ? $id : $name;
		$currentValue = ( ! empty( $currentValue ) ) ? $currentValue : $default;
		$currentValue = ( $type == 'password' ) ? '' : $currentValue; // Don't show the password values!
		$autocomplete = ( 'true' == $autocomplete ) ? ' autocomplete="' . esc_attr( $autocomplete ) . '"' : '';
		$autofocus    = ( 'true' == $autofocus ) ? ' autofocus' : '';
		$disabled     = ( 'true' == $disabled ) ? ' disabled' : '';
		$formGroupID  = ( ! empty( $formGroupID ) ) ? ' id="' . esc_attr( $formGroupID ) . '"' : '';
		$pattern      = ( ! empty( $pattern ) ) ? ' pattern="' . esc_attr( $pattern ) . '"' : '';
		$inputmode    = ( ! empty( $inputmode ) ) ? ' inputmode="' . esc_attr( $inputmode ) . '"' : '';
		$placeholder  = ( ! empty( $placeholder ) ) ? ' placeholder="' . esc_attr( $placeholder ) . '"' : '';
		$required     = ( 'true' == $required ) ? ' required' : '';
		$readonly     = ( 'true' == $readonly ) ? ' readonly' : '';

		// Fixes for bootstrap switches
		if ( ! empty( $data_off_label ) && empty( $data_off_text ) ) {
			$data_off_text = $data_off_label;
		}
		if ( ! empty( $data_on_label ) && empty( $data_on_text ) ) {
			$data_on_text = $data_on_label;
		}
		if ( ! empty( $data_text_label ) && empty( $data_label_text ) ) {
			$data_label_text = $data_text_label;
		}

		$textInputs = array(
			'file',
			'password',
			'text',
			'search',
			'email',
			'url',
			'tel',
			'number',
			'range',
			'date',
			'month',
			'week',
			'time',
			'datetime',
			'datetime-local',
			'color',
		);

		// Is it a separator?
		if ( $type == 'separator' ) {
			return '<div><h4>' . __( $text ) . '</h4></div>';
		} else {
			// Its a form field so lets figure out what to show
			$inputHTML = '';

			// File / Password / Text input (other options are HTML5 options that show as text fields on browsers that don't support HTML5)
			if ( in_array( $type, $textInputs ) ) {
				$inputHTML = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="form-control ' . esc_attr( $class ) . '" min="' . esc_attr( $min ) . '"  max="' . esc_attr( $max ) . '"  step="' . esc_attr( $step ) . '"  maxlength="' . esc_attr( $maxlength ) . '" 
								 size="' . esc_attr( $size ) . '" value="' . esc_attr( $currentValue ) . '"' . $autocomplete . $autofocus . $pattern . $inputmode . $placeholder . $required . $readonly . $disabled . ' />';
			} // Checkbox
			elseif ( $type == 'checkbox' ) {
				$inputHTML = '<input type="checkbox" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '"' . testChecked( $currentValue, $value ) . $autocomplete . $autofocus . $pattern . $inputmode . $required . $readonly . $disabled . ' />';
			} // Colorpicker
			elseif ( $type == 'colorpicker' ) {
				$inputHTML = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="minicolors ' . esc_attr( $class ) . '" size="' . esc_attr( $size ) . '" value="' . ( ( empty( $currentValue ) ) ? '#FFFFFF' : esc_attr( $currentValue ) ) . '" />';
			}
			// Disabled / Read Only Text input
			if ( $type == 'disabled' || $type == 'readonly' ) {
				$inputHTML = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="form-control ' . esc_attr( $class ) . '"	placeholder="' . esc_attr( $currentValue ) . '" ' . $type . ' />';
			} // Hidden input
			elseif ( $type == 'hidden' ) {
				$inputHTML = '<input type="hidden" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '" />';
			} // HTML
			elseif ( $type == 'html' || $type == 'htmlWithLabel' ) {
				$inputHTML = $value;
			} // Iconpicker
			elseif ( $type == 'iconpicker' ) {
				$inputHTML = '<input type="' . esc_attr( $type ) . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="icon-picker ' . esc_attr( $class ) . '" size="' . esc_attr( $size ) . '" value="' . $currentValue . '"' . $autofocus . $pattern . $inputmode . $placeholder . $required . $readonly . $disabled . ' />';
			} // Plain text with a label
			elseif ( $type == 'plainText' ) {
				$inputHTML = esc_html( $value );
			} // Radio
			elseif ( $type == 'radio' ) {
				if ( ! empty( $group ) ) {
					$name = $group;
				}
				$inputHTML = '<input type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '"' . testChecked( $currentValue, $value ) . $autofocus . $required . $readonly . $disabled . ' />';
			} // Select
			elseif ( $type == 'select' || $type == 'selectWithPreview' ) {
				$inputHTML = '<select name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '"' . ( ( $multiple == 'true' ) ? ' multiple="multiple"' : '' ) . $autofocus . $required . $readonly . $disabled . '>';

				foreach ( $options as $optionValue => $optionName ) {
					if ( is_array( $optionName ) ) {
						// This is actually an optgroup so handle it accordingly
						$inputHTML .= '<optgroup label="' . esc_attr( $optionValue ) . '">';

						foreach ( $optionName as $realValue => $realName ) {
							$inputHTML .= '<option value="' . esc_attr( $realValue ) . '"' . testSelected( $currentValue, $realValue ) . '>' . esc_html( $realName ) . '</option>';
						}

						$inputHTML .= '</optgroup>';

					} else if ( is_array( $currentValue ) ) {
						// Multiple select option
						$inputHTML .= '<option value="' . esc_attr( $optionValue ) . '"' . testSelected( ( in_array( $optionValue, $currentValue ) ? $optionValue : null ), $optionValue ) . '>' . esc_html( $optionName ) . '</option>';

					} else {
						// Normal select option
						$inputHTML .= '<option value="' . esc_attr( $optionValue ) . '"' . testSelected( $currentValue, $optionValue ) . '>' . esc_html( $optionName ) . '</option>';
					}
				}

				$inputHTML .= '</select>';
				if ( $type == 'selectWithPreview' ) {
					$inputHTML .= '<div id="' . $id . 'Preview" class="selectionPreview"><img src="' . SITE_URL . '/themes/' . $mbp_config['ftsmbp_theme'] . '/images/preview.png" alt="Preview" /></div>';
				}
			} // Textarea
			elseif ( $type == 'textarea' ) {
				$inputHTML = '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="form-control ' . esc_attr( $class ) . '" cols="' . esc_attr( $cols ) . '" rows="' . esc_attr( $rows ) . '"' . $autofocus . $placeholder . $required . $readonly . $disabled . '>' . esc_textarea( $currentValue ) . '</textarea>';
			} // Toggle
			elseif ( $type == 'toggle' ) {
				$inputHTML = '<input type="checkbox" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="form-control toggle ' . esc_attr( $class ) . '" value="' . esc_attr( $value ) . '" data-animated="' . esc_attr( $data_animated ) . '" 
								data-label-icon="' . esc_attr( $data_label_icon ) . '" data-on="' . esc_attr( $data_on ) . '" data-on-text="' . esc_attr( $data_on_text ) . '" data-off="' . esc_attr( $data_off ) . '" 
								data-off-text="' . esc_attr( $data_off_text ) . '" data-label-text="' . esc_attr( $data_label_text ) . '"' . testChecked( $currentValue, $value ) . $autofocus . $required . $readonly . $disabled . ' />';
			}

			// Remove empty attributes (other than value)
			// _ are not valid in PHP variable names so we have to account for cases where we used _ instead of -
			$keysToRemove = array_merge( $defaults, array(
				'data-animated'   => 'true',
				'data-label-icon' => '',
				'data-on'         => 'primary',
				'data-on-label'   => 'ON',
				'data-off'        => 'default',
				'data-off-label'  => 'OFF',
				'data-text-label' => '',
			) );
			unset( $keysToRemove['value'] );
			$inputHTML = preg_replace( '/(' . implode( '|', array_keys( $keysToRemove ) ) . ')=""/', '', $inputHTML );

			// Add the required span to the inputs that have the proper class
			if ( $showRequiredText && stristr( $class, 'required' ) !== false ) {
				$text .= ' <sup>*</sup>';
			}

			// Handle prepends and appends
			$addAppend        = ( ! empty( $append ) ) ? 1 : 0;
			$addPrepend       = ( ! empty( $prepend ) ) ? 1 : 0;
			$addAppendButton  = ( ! empty( $appendButton ) ) ? 1 : 0;
			$addPrependButton = ( ! empty( $prependButton ) ) ? 1 : 0;
			if ( $addAppend || $addPrepend || $addAppendButton || $addPrependButton ) {
				if ( $addAppend ) {
					$inputHTML .= '<span class="input-group-addon">' . esc_html( $append ) . '</span>';
				}
				if ( $addPrepend ) {
					$inputHTML = '<span class="input-group-addon">' . esc_html( $prepend ) . '</span>' . $inputHTML;
				}
				if ( $addAppendButton ) {
					$inputHTML .= '<span class="input-group-btn">' . esc_html( $appendButton ) . '</span>';
				}
				if ( $addPrependButton ) {
					$inputHTML = '<span class="input-group-btn">' . esc_html( $prependButton ) . '</span>' . $inputHTML;
				}
				$inputHTML = '<div class="input-group">' . $inputHTML . '</div>';
			}
			if ( ! empty( $help_block ) ) {
				$inputHTML .= '<p class="help-block">' . $help_block . '</p>';
			}

			// Handle the wrappers
			if ( $type != 'html' && $type != 'hidden' && $showLabel ) {
				$inputHTML = '<label for="' . $id . '" class="control-label ' . $cols_label_class . '">' . esc_html( $text ) . ' </label> <div class="' . $cols_input_container_class . '">' . $inputHTML . '</div>';
			}
			if ( $type != 'hidden' ) {
				$inputHTML = '<div class="form-group"' . $formGroupID . '>' . $inputHTML . '</div>';
			}

			// Return our form item
			return "\n" . $inputHTML;
		}
	}

	return '';
}