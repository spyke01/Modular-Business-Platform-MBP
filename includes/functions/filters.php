<?php
/************************************************************************
 *                               filters.php
 *                            -------------------
 *   begin                : Saturday, Sept 24, 2005
 *   copyright            : (C) 2005 Paden Clayton
 *   email                : me@padenclayton.com
 *
 *
 ***************************************************************************/


//=========================================================
// Mimics WordPress's functions
//=========================================================
// Initialize the filter globals.
global $mbp_filter, $mbp_actions, $merged_filters, $mbp_current_filter;

if ( ! isset( $mbp_filter ) ) {
	$mbp_filter = [];
}

if ( ! isset( $mbp_actions ) ) {
	$mbp_actions = [];
}

if ( ! isset( $merged_filters ) ) {
	$merged_filters = [];
}

if ( ! isset( $mbp_current_filter ) ) {
	$mbp_current_filter = [];
}

/**
 * Hook a function or method to a specific filter action.
 *
 * We offers filter hooks to allow plugins to modify
 * various types of internal data at runtime.
 *
 * A plugin can modify data by binding a callback to a filter hook. When the filter
 * is later applied, each bound callback is run in order of priority, and given
 * the opportunity to modify a value by returning a new value.
 *
 * The following example shows how a callback function is bound to a filter hook.
 * Note that $example is passed to the callback, (maybe) modified, then returned:
 *
 * <code>
 * function example_callback( $example ) {
 *    // Maybe modify $example in some way
 *    return $example;
 * }
 * add_filter( 'example_filter', 'example_callback' );
 * </code>
 *
 * Bound callbacks can take as many arguments as are
 * passed as parameters in the corresponding apply_filters() call. The $accepted_args
 * parameter allows for calling functions only when the number of args match.
 *
 * <strong>Note:</strong> the function will return true whether or not the callback
 * is valid. It is up to you to take care. This is done for optimization purposes,
 * so everything is as quick as possible.
 *
 * @param string   $tag             The name of the filter to hook the $function_to_add callback to.
 * @param callback $function_to_add The callback to be run when the filter is applied.
 * @param int      $priority        Optional. Used to specify the order in which the functions
 *                                  associated with a particular action are executed. Default 10.
 *                                  Lower numbers correspond with earlier execution,
 *                                  and functions with the same priority are executed
 *                                  in the order in which they were added to the action.
 * @param int      $accepted_args   Optional. The number of arguments the function accepts. Default 1.
 *
 * @return boolean true
 * @global array   $merged_filters  Tracks the tags that need to be merged for later. If the hook is added,
 *                                  it doesn't need to run through that process.
 *
 * @since 4.13.08.08
 *
 * @global array   $mbp_filter      A multidimensional array of all hooks and the callbacks hooked to them.
 */
function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	global $mbp_filter, $merged_filters;

	$idx                                     = _mbp_filter_build_unique_id( $tag, $function_to_add, $priority );
	$mbp_filter[ $tag ][ $priority ][ $idx ] = array( 'function' => $function_to_add, 'accepted_args' => $accepted_args );
	unset( $merged_filters[ $tag ] );

	return true;
}

/**
 * Check if any filter has been registered for a hook.
 *
 * @param string        $tag               The name of the filter hook.
 * @param callback|bool $function_to_check Optional. The callback to check for. Default false.
 *
 * @return bool|int If $function_to_check is omitted, returns boolean for whether the hook has
 *                  anything registered. When checking a specific function, the priority of that
 *                  hook is returned, or false if the function is not attached. When using the
 *                  $function_to_check argument, this function may return a non-boolean value
 *                  that evaluates to false (e.g.) 0, so use the === operator for testing the
 *                  return value.
 * @global array        $mbp_filter        Stores all of the filters.
 *
 * @since 4.13.08.08
 *
 */
function has_filter( $tag, $function_to_check = false ) {
	global $mbp_filter;

	$has = ! empty( $mbp_filter[ $tag ] );
	if ( false === $function_to_check || false == $has ) {
		return $has;
	}

	if ( ! $idx = _mbp_filter_build_unique_id( $tag, $function_to_check, false ) ) {
		return false;
	}

	foreach ( (array) array_keys( $mbp_filter[ $tag ] ) as $priority ) {
		if ( isset( $mbp_filter[ $tag ][ $priority ][ $idx ] ) ) {
			return $priority;
		}
	}

	return false;
}

/**
 * Removes a function from a specified filter hook.
 *
 * This function removes a function attached to a specified filter hook. This
 * method can be used to remove default functions attached to a specific filter
 * hook and possibly replace them with a substitute.
 *
 * To remove a hook, the $function_to_remove and $priority arguments must match
 * when the hook was added. This goes for both filters and actions. No warning
 * will be given on removal failure.
 *
 * @param string   $tag                The filter hook to which the function to be removed is hooked.
 * @param callback $function_to_remove The name of the function which should be removed.
 * @param int      $priority           Optional. The priority of the function. Default 10.
 *
 * @return boolean Whether the function existed before it was removed.
 * @since 4.13.08.08
 *
 */
function remove_filter( $tag, $function_to_remove, $priority = 10, $accepted_args = 1 ) {
	$function_to_remove = _mbp_filter_build_unique_id( $tag, $function_to_remove, $priority );

	$r = isset( $GLOBALS['mbp_filter'][ $tag ][ $priority ][ $function_to_remove ] );

	if ( true === $r ) {
		unset( $GLOBALS['mbp_filter'][ $tag ][ $priority ][ $function_to_remove ] );
		if ( empty( $GLOBALS['mbp_filter'][ $tag ][ $priority ] ) ) {
			unset( $GLOBALS['mbp_filter'][ $tag ][ $priority ] );
		}
		if ( empty( $GLOBALS['mbp_filter'][ $tag ] ) ) {
			$GLOBALS['mbp_filter'][ $tag ] = [];
		}
		unset( $GLOBALS['merged_filters'][ $tag ] );
	}

	return $r;
}

/**
 * Remove all of the hooks from a filter.
 *
 * @param string   $tag      The filter to remove hooks from.
 * @param int|bool $priority Optional. The priority number to remove. Default false.
 *
 * @return bool True when finished.
 * @since 4.13.08.08
 *
 */
function remove_all_filters( $tag, $priority = false ) {
	global $mbp_filter, $merged_filters;

	if ( isset( $mbp_filter[ $tag ] ) ) {
		if ( false !== $priority && isset( $mbp_filter[ $tag ][ $priority ] ) ) {
			$mbp_filter[ $tag ][ $priority ] = [];
		} else {
			$mbp_filter[ $tag ] = [];
		}
	}

	if ( isset( $merged_filters[ $tag ] ) ) {
		unset( $merged_filters[ $tag ] );
	}

	return true;
}

/**
 * Call the functions added to a filter hook.
 *
 * The callback functions attached to filter hook $tag are invoked by calling
 * this function. This function can be used to create a new filter hook by
 * simply calling this function with the name of the new hook specified using
 * the $tag parameter.
 *
 * The function allows for additional arguments to be added and passed to hooks.
 * <code>
 * // Our filter callback function
 * function example_callback( $string, $arg1, $arg2 ) {
 *    // (maybe) modify $string
 *    return $string;
 * }
 * add_filter( 'example_filter', 'example_callback', 10, 3 );
 *
 * // Apply the filters by calling the 'example_callback' function we
 * // "hooked" to 'example_filter' using the add_filter() function above.
 * // - 'example_filter' is the filter hook $tag
 * // - 'filter me' is the value being filtered
 * // - $arg1 and $arg2 are the additional arguments passed to the callback.
 * $value = apply_filters( 'example_filter', 'filter me', $arg1, $arg2 );
 * </code>
 *
 * @param string $tag                The name of the filter hook.
 * @param mixed  $value              The value on which the filters hooked to <tt>$tag</tt> are applied on.
 * @param mixed  $var                Additional variables passed to the functions hooked to <tt>$tag</tt>.
 *
 * @return mixed The filtered value after all hooked functions are applied to it.
 * @since 4.13.08.08
 *
 * @global array $mbp_filter         Stores all of the filters.
 * @global array $merged_filters     Merges the filter hooks using this function.
 * @global array $mbp_current_filter Stores the list of current filters with the current one last.
 *
 */
function apply_filters( $tag, $value ) {
	global $mbp_filter, $merged_filters, $mbp_current_filter;

	$args = [];

	// Do 'all' actions first
	if ( isset( $mbp_filter['all'] ) ) {
		$mbp_current_filter[] = $tag;
		$args                 = func_get_args();
		_mbp_call_all_hook( $args );
	}

	if ( ! isset( $mbp_filter[ $tag ] ) ) {
		if ( isset( $mbp_filter['all'] ) ) {
			array_pop( $mbp_current_filter );
		}

		return $value;
	}

	if ( ! isset( $mbp_filter['all'] ) ) {
		$mbp_current_filter[] = $tag;
	}

	// Sort
	if ( ! isset( $merged_filters[ $tag ] ) ) {
		ksort( $mbp_filter[ $tag ] );
		$merged_filters[ $tag ] = true;
	}

	reset( $mbp_filter[ $tag ] );

	if ( empty( $args ) ) {
		$args = func_get_args();
	}

	do {
		foreach ( (array) current( $mbp_filter[ $tag ] ) as $the_ ) {
			if ( ! is_null( $the_['function'] ) ) {
				$args[1] = $value;
				$value   = call_user_func_array( $the_['function'], array_slice( $args, 1, (int) $the_['accepted_args'] ) );
			}
		}

	} while ( next( $mbp_filter[ $tag ] ) !== false );

	array_pop( $mbp_current_filter );

	return $value;
}

/**
 * Hooks a function on to a specific action.
 *
 * Actions are the hooks that the core launches at specific points
 * during execution, or when specific events occur. Plugins can specify that
 * one or more of its PHP functions are executed at these points, using the
 * Action API.
 *
 * @param string   $tag             The name of the action to which the $function_to_add is hooked.
 * @param callback $function_to_add The name of the function you wish to be called.
 * @param int      $priority        Optional. Used to specify the order in which the functions
 *                                  associated with a particular action are executed. Default 10.
 *                                  Lower numbers correspond with earlier execution,
 *                                  and functions with the same priority are executed
 *                                  in the order in which they were added to the action.
 * @param int      $accepted_args   Optional. The number of arguments the function accept. Default 1.
 *
 * @return bool Will always return true.
 * @uses  add_filter() Adds an action. Parameter list and functionality are the same.
 *
 * @since 4.13.08.08
 *
 */
function add_action( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	return add_filter( $tag, $function_to_add, $priority, $accepted_args );
}

/**
 * Execute functions hooked on a specific action hook.
 *
 * This function invokes all functions attached to action hook $tag. It is
 * possible to create new action hooks by simply calling this function,
 * specifying the name of the new hook using the <tt>$tag</tt> parameter.
 *
 * You can pass extra arguments to the hooks, much like you can with
 * apply_filters().
 *
 * @param string $tag         The name of the action to be executed.
 * @param mixed  $arg         Optional. Additional arguments which are passed on to the
 *                            functions hooked to the action. Default empty.
 *
 * @return null Will return null if $tag does not exist in $mbp_filter array.
 * @global array $mbp_actions Increments the amount of times action was triggered.
 *
 * @since 4.13.08.08
 *
 * @see   apply_filters() This function works similar with the exception that nothing
 *                      is returned and only the functions or methods are called.
 * @global array $mbp_filter  Stores all of the filters
 */
function do_action( $tag, $arg = '' ) {
	global $mbp_filter, $mbp_actions, $merged_filters, $mbp_current_filter;

	if ( ! isset( $mbp_actions[ $tag ] ) ) {
		$mbp_actions[ $tag ] = 1;
	} else {
		++ $mbp_actions[ $tag ];
	}

	// Do 'all' actions first
	if ( isset( $mbp_filter['all'] ) ) {
		$mbp_current_filter[] = $tag;
		$all_args             = func_get_args();
		_mbp_call_all_hook( $all_args );
	}

	if ( ! isset( $mbp_filter[ $tag ] ) ) {
		if ( isset( $mbp_filter['all'] ) ) {
			array_pop( $mbp_current_filter );
		}

		return;
	}

	if ( ! isset( $mbp_filter['all'] ) ) {
		$mbp_current_filter[] = $tag;
	}

	$args = [];
	if ( is_array( $arg ) && 1 == count( $arg ) && isset( $arg[0] ) && is_object( $arg[0] ) ) // array(&$this)
	{
		$args[] =& $arg[0];
	} else {
		$args[] = $arg;
	}
	for ( $a = 2; $a < func_num_args(); $a ++ ) {
		$args[] = func_get_arg( $a );
	}

	// Sort
	if ( ! isset( $merged_filters[ $tag ] ) ) {
		ksort( $mbp_filter[ $tag ] );
		$merged_filters[ $tag ] = true;
	}

	reset( $mbp_filter[ $tag ] );

	do {
		foreach ( (array) current( $mbp_filter[ $tag ] ) as $the_ ) {
			if ( ! is_null( $the_['function'] ) ) {
				call_user_func_array( $the_['function'], array_slice( $args, 0, (int) $the_['accepted_args'] ) );
			}
		}

	} while ( next( $mbp_filter[ $tag ] ) !== false );

	array_pop( $mbp_current_filter );
}

/**
 * Retrieve the number of times an action is fired.
 *
 * @param string $tag         The name of the action hook.
 *
 * @return int The number of times action hook $tag is fired.
 * @since 4.13.08.08
 *
 * @global array $mbp_actions Increments the amount of times action was triggered.
 *
 */
function did_action( $tag ) {
	global $mbp_actions;

	if ( ! isset( $mbp_actions ) || ! isset( $mbp_actions[ $tag ] ) ) {
		return 0;
	}

	return $mbp_actions[ $tag ];
}

/**
 * Execute functions hooked on a specific action hook, specifying arguments in an array.
 *
 * @param string $tag         The name of the action to be executed.
 * @param array  $args        The arguments supplied to the functions hooked to <tt>$tag</tt>
 *
 * @return null Will return null if $tag does not exist in $mbp_filter array
 * @global array $mbp_actions Increments the amount of times action was triggered.
 *
 * @since 4.13.08.08
 *
 * @see   do_action() This function is identical, but the arguments passed to the
 *                  functions hooked to $tag< are supplied using an array.
 * @global array $mbp_filter  Stores all of the filters
 */
function do_action_ref_array( $tag, $args ) {
	global $mbp_filter, $mbp_actions, $merged_filters, $mbp_current_filter;

	if ( ! isset( $mbp_actions[ $tag ] ) ) {
		$mbp_actions[ $tag ] = 1;
	} else {
		++ $mbp_actions[ $tag ];
	}

	// Do 'all' actions first
	if ( isset( $mbp_filter['all'] ) ) {
		$mbp_current_filter[] = $tag;
		$all_args             = func_get_args();
		_mbp_call_all_hook( $all_args );
	}

	if ( ! isset( $mbp_filter[ $tag ] ) ) {
		if ( isset( $mbp_filter['all'] ) ) {
			array_pop( $mbp_current_filter );
		}

		return;
	}

	if ( ! isset( $mbp_filter['all'] ) ) {
		$mbp_current_filter[] = $tag;
	}

	// Sort
	if ( ! isset( $merged_filters[ $tag ] ) ) {
		ksort( $mbp_filter[ $tag ] );
		$merged_filters[ $tag ] = true;
	}

	reset( $mbp_filter[ $tag ] );

	do {
		foreach ( (array) current( $mbp_filter[ $tag ] ) as $the_ ) {
			if ( ! is_null( $the_['function'] ) ) {
				call_user_func_array( $the_['function'], array_slice( $args, 0, (int) $the_['accepted_args'] ) );
			}
		}

	} while ( next( $mbp_filter[ $tag ] ) !== false );

	array_pop( $mbp_current_filter );
}

/**
 * Check if any action has been registered for a hook.
 *
 * @param string        $tag               The name of the action hook.
 * @param callback|bool $function_to_check Optional. The callback to check for. Default false.
 *
 * @return bool|int If $function_to_check is omitted, returns boolean for whether the hook has
 *                  anything registered. When checking a specific function, the priority of that
 *                  hook is returned, or false if the function is not attached. When using the
 *                  $function_to_check argument, this function may return a non-boolean value
 *                  that evaluates to false (e.g.) 0, so use the === operator for testing the
 *                  return value.
 * @see   has_filter() has_action() is an alias of has_filter().
 *
 * @since 4.13.08.08
 *
 */
function has_action( $tag, $function_to_check = false ) {
	return has_filter( $tag, $function_to_check );
}

/**
 * Removes a function from a specified action hook.
 *
 * This function removes a function attached to a specified action hook. This
 * method can be used to remove default functions attached to a specific filter
 * hook and possibly replace them with a substitute.
 *
 * @param string   $tag                The action hook to which the function to be removed is hooked.
 * @param callback $function_to_remove The name of the function which should be removed.
 * @param int      $priority           Optional. The priority of the function. Default 10.
 *
 * @return boolean Whether the function is removed.
 * @since 4.13.08.08
 *
 */
function remove_action( $tag, $function_to_remove, $priority = 10, $accepted_args = 1 ) {
	return remove_filter( $tag, $function_to_remove, $priority, $accepted_args );
}

/**
 * Remove all of the hooks from an action.
 *
 * @param string   $tag      The action to remove hooks from.
 * @param int|bool $priority The priority number to remove them from. Default false.
 *
 * @return bool True when finished.
 * @since 4.13.08.08
 *
 */
function remove_all_actions( $tag, $priority = false ) {
	return remove_all_filters( $tag, $priority );
}

/**
 * Call the 'all' hook, which will process the functions hooked into it.
 *
 * The 'all' hook passes all of the arguments or parameters that were used for
 * the hook, which this function was called for.
 *
 * This function is used internally for apply_filters(), do_action(), and
 * do_action_ref_[] and is not meant to be used from outside those
 * functions. This function does not check for the existence of the all hook, so
 * it will fail unless the all hook exists prior to this function call.
 *
 * @param array $args The collected parameters from the hook that was called.
 *
 * @uses   $mbp_filter Used to process all of the functions in the 'all' hook.
 *
 * @since  4.13.08.08
 * @access private
 *
 */
function _mbp_call_all_hook( $args ) {
	global $mbp_filter;

	reset( $mbp_filter['all'] );
	do {
		foreach ( (array) current( $mbp_filter['all'] ) as $the_ ) {
			if ( ! is_null( $the_['function'] ) ) {
				call_user_func_array( $the_['function'], $args );
			}
		}

	} while ( next( $mbp_filter['all'] ) !== false );
}

/**
 * Build Unique ID for storage and retrieval.
 *
 * The old way to serialize the callback caused issues and this function is the
 * solution. It works by checking for objects and creating an a new property in
 * the class to keep track of the object and new objects of the same class that
 * need to be added.
 *
 * It also allows for the removal of actions and filters for objects after they
 * change class properties. It is possible to include the property $mbp_filter_id
 * in your class and set it to "null" or a number to bypass the workaround.
 * However this will prevent you from adding new classes and any new classes
 * will overwrite the previous hook by the same class.
 *
 * Functions and static method callbacks are just returned as strings and
 * shouldn't have any speed penalty.
 *
 * @param string   $tag        Used in counting how many hooks were applied
 * @param callback $function   Used for creating unique id
 * @param int|bool $priority   Used in counting how many hooks were applied. If === false
 *                             and $function is an object reference, we return the unique
 *                             id only if it already has one, false otherwise.
 *
 * @return string|bool Unique ID for usage as array key or false if $priority === false
 *                     and $function is an object reference, and it does not already have
 *                     a unique id.
 * @since  4.13.08.08
 * @access private
 *
 * @global array   $mbp_filter Storage for all of the filters and actions.
 *
 */
function _mbp_filter_build_unique_id( $tag, $function, $priority ) {
	global $mbp_filter;
	static $filter_id_count = 0;

	if ( is_string( $function ) ) {
		return $function;
	}

	if ( is_object( $function ) ) {
		// Closures are currently implemented as objects
		$function = array( $function, '' );
	} else {
		$function = (array) $function;
	}

	if ( is_object( $function[0] ) ) {
		// Object Class Calling
		if ( function_exists( 'spl_object_hash' ) ) {
			return spl_object_hash( $function[0] ) . $function[1];
		} else {
			$obj_idx = get_class( $function[0] ) . $function[1];
			if ( ! isset( $function[0]->mbp_filter_id ) ) {
				if ( false === $priority ) {
					return false;
				}
				$obj_idx                    .= isset( $mbp_filter[ $tag ][ $priority ] ) ? count( (array) $mbp_filter[ $tag ][ $priority ] ) : $filter_id_count;
				$function[0]->mbp_filter_id = $filter_id_count;
				++ $filter_id_count;
			} else {
				$obj_idx .= $function[0]->mbp_filter_id;
			}

			return $obj_idx;
		}
	} elseif ( is_string( $function[0] ) ) {
		// Static Calling
		return $function[0] . '::' . $function[1];
	}
}