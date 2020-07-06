<?php

/**
 *  Zebra_Mptt is a PHP class that provides an implementation of the modified preorder tree traversal algorithm making
 *  it easy for you to use MPTT in your PHP applications.
 *
 *  It provides methods for adding nodes anywhere in the tree, deleting nodes, moving and copying nodes around the tree
 *  and methods for retrieving various information about the nodes.
 *
 *  Zebra_Mptt uses {@link http://dev.mysql.com/doc/refman/5.0/en/ansi-diff-transactions.html MySQL transactions} making
 *  sure that database integrity is always preserved and that SQL operations execute completely or not at all (in the case
 *  there's a problem with the MySQL server). Also, the library uses a caching mechanism ensuring that the database is
 *  accessed in an optimum way.
 *
 *  The code is heavily commented and generates no warnings/errors/notices when PHP's error reporting level is set to
 *  E_ALL.
 *
 *  Visit {@link http://stefangabos.ro/php-libraries/zebra-mptt/} for more information.
 *
 *  For more resources visit {@link http://stefangabos.ro/}
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @version    2.2 (last revision: January 20, 2012)
 *  @copyright  (c) 2009 - 2012 Stefan Gabos
 *  @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 *  @package    Zebra_Mptt
 */

class Zebra_Mptt
{
    /**
     *  Constructor of the class.
     *
     *  <i>Make sure that before you instantiate the class you import or execute the SQL code found in the in the
     *  "install/mptt.sql" file, using the command line or your preferred MySQL manager.</i>
     *
     *  <code>
     *  // include the php file
     *  require 'path/to/Zebra_Mptt.php';
     *
     *  // instantiate the class
     *  $mptt = new Zebra_Mptt();
     *  </code>
     *
     *  @param  string      $table_name     (Optional) MySQL table name to be used for storing items.
     *
     *                                      Default is <i>mptt</i>
     *
     *  @param  string      $id_column      (Optional) Name of the column that uniquely identifies items in the table
     *
     *                                      Default is <i>id</i>
     *
     *  @param  string      $title_column   (Optional) Name of the column that stores items' names
     *
     *                                      Default is <i>title</i>
     *
     *  @param  string      $left_column    (Optional) Name of the column that stores "left" values
     *
     *                                      Default is <i>lft</i> ("left" is a reserved word in MySQL)
     *
     *  @param  string      $right_column   (Optional) Name of the column that stores "right" values
     *
     *                                      Default is <i>rgt</i> ("right" is a reserved word in MySQL)
     *
     *  @param  string      $parent_column  (Optional) Name of the column that stores the IDs of parent items
     *
     *                                      Default is <i>parent</i>
     *
     *  @return void
     */
    function __construct($table_name = 'categories', $id_column = 'id',$level_column = 'level', $status_column = 'status', $tree_column = 'tree_path', $title_column = 'name', $left_column = 'sx', $right_column = 'dx', $parent_column = 'parent',$meta_description_column = 'meta_description',$meta_keywords_column = 'meta_keywords') {
        global $table_prefix;
        // continue only if there is an active MySQL connection
        if (@mysql_ping())

            // initialize properties
            $this->properties = array(

                'table_name'    =>  $table_prefix.$table_name,
                'id_column'     =>  $id_column,
                'level_column'  =>  $level_column,
                'status_column' =>  $status_column,
                'tree_column'   =>  $tree_column,
                'title_column'  =>  $title_column,
                'left_column'   =>  $left_column,
                'right_column'  =>  $right_column,
                'parent_column' =>  $parent_column,
                'meta_keywords_column' => $meta_keywords_column,
				'meta_description_column' => $meta_description_column
            );

        // if no MySQL connections could be found
        else

            // trigger a fatal error message and stop execution
            trigger_error('<br>No MySQL connection!<br>Error', E_USER_ERROR);

    }

    /**
     *  Adds a new node as the child of a given parent node.
     *
     *  <code>
     *  // add a new topmost node
     *  $node = $mptt->add(0, 'Main');
     *
     *  // add a child node
     *  $mptt->add($node, 'Child 1');
     *
     *  // add another child node
     *  $mptt->add($node, 'Child 2');
     *
     *  // insert a third child node
     *  // notice the "1" as the last argument, instructing the script to insert the child node
     *  // as the second child node, after "Child 1"
     *  // remember that the trees are 0-based, meaning that the first node in a tree has the index 0!
     *  $mptt->add($node, 'Child 3', 1);
     *
     *  // and finally, insert a fourth child node
     *  // notice the "0" as the last argument, instructing the script to insert the child node
     *  // as the very first child node of the parent node
     *  // remember that the trees are 0-based, meaning that the first node in a tree has the index 0!
     *  $mptt->add($node, 'Child 4', 0);
     *  </code>
     *
     *  @param  integer     $parent     The ID of the parent node.
     *
     *                                  Use "0" to add a topmost node.
     *
     *  @param  string      $title      The title of the node.
     *
     *  @param  integer     $position   (Optional) The position the node will have amongst the {@link $parent}'s
     *                                  children nodes.
     *
     *                                  When {@link $parent} is "0", this refers to the position the node will have
     *                                  amongst the topmost nodes.
     *
     *                                  The values are 0-based, meaning that if you want the node to be inserted as
     *                                  the first in the list of {@link $parent}'s children nodes, you have to use "0".<br>
     *                                  If you want it to be second, use "1" and so on.
     *
     *                                  Default is "0" - the node will be inserted as last of the {@link $parent}'s
     *                                  children nodes.
     *
     *  @return mixed                   Returns the ID of the newly inserted node or FALSE upon error.
     */
    function add($parent, $title,$tree_path,$level,$meta_description,$meta_keywords,$status, $position = false) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // make sure parent ID is an integer
        $parent = (int)$parent;

        // continue only if
        if (

            // we are adding a topmost node OR
            $parent == 0 ||

            // parent node exists in the lookup array
            isset($this->lookup[$parent])

        ) {

            // get parent's children nodes (no deeper than the first level)
            $children = $this->get_children($parent, true);

            // if node is to be inserted in the default position (as the last of the parent node's children)
            if ($position === false)

                // give a numerical value to the position
                $position = count($children);

            // if a custom position was specified
            else {

                // make sure that position is an integer value
                $position = (int)$position;

                // if position is a bogus number
                if ($position > count($children) || $position < 0)

                    // use the default position (as the last of the parent node's children)
                    $position = count($children);

            }

            // if parent has no children OR the node is to be inserted as the parent node's first child
            if (empty($children) || $position == 0)

                // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
                // the insert, and will need to be updated
                // if parent is not found (meaning that we're inserting a topmost node) set the boundary to 0
                $boundary = isset($this->lookup[$parent]) ? $this->lookup[$parent][$this->properties['left_column']] : 0;

            // if parent node has children nodes and/or the node needs to be inserted at a specific position
            else {

                // find the child node that currently exists at the position where the new node needs to be inserted to
                // since PHP 5.3 this needs to be done in two steps rather than
                // $children = array_shift(array_slice($children, $position - 1, 1));
                // or PHP will trigger a warning "Strict standards: Only variables should be passed by reference"
                $slice = array_slice($children, $position - 1, 1);
                $children = array_shift($slice);

                // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
                // the insert, and will need to be updated
                $boundary = $children[$this->properties['right_column']];

            }

            // iterate through all the records in the lookup array
            foreach ($this->lookup as $id => $properties) {

                // if the node's "left" value is outside the boundary
                if ($properties[$this->properties['left_column']] > $boundary)

                    // increment it with 2
                    $this->lookup[$id][$this->properties['left_column']] += 2;

                // if the node's "right" value is outside the boundary
                if ($properties[$this->properties['right_column']] > $boundary)

                    // increment it with 2
                    $this->lookup[$id][$this->properties['right_column']] += 2;

            }

            // lock table to prevent other sessions from modifying the data and thus preserving data integrity
            mysql_query('LOCK TABLE ' . $this->properties['table_name'] . ' WRITE');

            // update the nodes in the database having their "left"/"right" values outside the boundary
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = ' . $this->properties['left_column'] . ' + 2
                WHERE
                    ' . $this->properties['left_column'] . ' > ' . $boundary . '

            ');

            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['right_column'] . ' = ' . $this->properties['right_column'] . ' + 2
                WHERE
                    ' . $this->properties['right_column'] . ' > ' . $boundary . '

            ');

            // insert the new node into the database
            mysql_query('
                INSERT INTO
                    ' . $this->properties['table_name'] . '
                    (
                        ' . $this->properties['title_column'] . ',
                        ' . $this->properties['tree_column'] . ',
                        ' . $this->properties['status_column'] . ',
                        ' . $this->properties['level_column'] . ',
                        ' . $this->properties['meta_keywords_column'] . ',
                        ' . $this->properties['meta_description_column'] . ',
                        ' . $this->properties['left_column'] . ',
                        ' . $this->properties['right_column'] . ',
                        ' . $this->properties['parent_column'] . '
                    )
                VALUES
                    (
                        "' . mysql_real_escape_string($title) . '",
                        "' . $tree_path . '",
                        ' . $status . ',
                        ' . $level . ',
                        "' . $meta_keywords . '",
                        "' . $meta_description . '",
                        ' . ($boundary + 1) . ',
                        ' . ($boundary + 2) . ',
                        ' . $parent . '
                    )
            ');

            // get the ID of the newly inserted node
            $node_id = mysql_insert_id();

            // release table lock
            mysql_query('UNLOCK TABLES');

            // add the node to the lookup array
            $this->lookup[$node_id] = array(
                $this->properties['id_column']      => $node_id,
                $this->properties['title_column']   => $title,
                $this->properties['tree_column']   => $tree_path,
                $this->properties['level_column']   => $level,
                $this->properties['meta_keywords_column'] => $meta_keywords,
                $this->properties['meta_description_column'] => $meta_description,
                $this->properties['status_column']   => $status,
                $this->properties['left_column']    => $boundary + 1,
                $this->properties['right_column']   => $boundary + 2,
                $this->properties['parent_column']  => $parent,
            );

            // reorder the lookup array
            $this->_reorder_lookup_array();

            // return the ID of the newly inserted node
            return $node_id;

        }

        // if script gets this far, something must've went wrong so we return false
        return false;

    }

    /**
     *  Creates a copy of a node (including its children nodes) as the children node of a given parent node.
     *
     *  <code>
     *  // insert a topmost node
     *  $node = $mptt->add(0, 'Main');
     *
     *  // add a child node
     *  $child1 = $mptt->add($node, 'Child 1');
     *
     *  // add another child node
     *  $child2 = $mptt->add($node, 'Child 2');
     *
     *  // create a copy of "Child 2" node and put it as "Child 1"'s child
     *  $mptt->copy($child2, $child1);
     *  </code>
     *
     *  @param  integer     $source     The ID of the node we want to copy.
     *
     *                                  Remember that the node will be copied with all its children nodes!
     *
     *  @param  integer     $target     The ID of the node which will become the copy's parent node.
     *
     *                                  Use "0" to create a copy as a topmost node.
     *
     *  @param  integer     $position   (Optional) The position the node will have amongst the {@link $target}'s
     *                                  children nodes.
     *
     *                                  When {@link $target} is "0", this refers to the position the node will have
     *                                  amongst the topmost nodes.
     *
     *                                  The values are 0-based, meaning that if you want the node to be inserted as
     *                                  the first in the list of {@link $parent}'s children nodes, you have to use "0".<br>
     *                                  If you want it to be second, use "1" and so on.
     *
     *                                  Default is "0" - the node will be inserted as last of the {@link $parent}'s
     *                                  children nodes.
     *
     *  @return mixed                   Returns the ID of the newly created copy or FALSE upon error.
     */
    function copy($source, $target, $position = false) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // continue only if
        if (

            // source node exists in the lookup array AND
            isset($this->lookup[$source]) &&

            // target node exists in the lookup array OR is 0 (indicating a topmost node)
            (isset($this->lookup[$target]) || $target == 0)

        ) {

            // get the source's children nodes (if any)
            $source_children = $this->get_children($source);

            // this array will hold the items we need to copy
            // by default we add the source item to it
            $sources = array($this->lookup[$source]);

            // the copy's parent will be the target node
            $sources[0][$this->properties['parent_column']] = $target;

            // iterate through source node's children
            foreach ($source_children as $child)

                // save them for later use
                $sources[] = $this->lookup[$child[$this->properties['id_column']]];

            // the value with which items outside the boundary set below, are to be updated with
            $source_rl_difference =

                $this->lookup[$source][$this->properties['right_column']] -

                $this->lookup[$source][$this->properties['left_column']]

                + 1;

            // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
            // the insert, and will need to be updated
            $source_boundary = $this->lookup[$source][$this->properties['left_column']];

            // get target node's children (no deeper than the first level)
            $target_children = $this->get_children($target, true);

            // if copy is to be inserted in the default position (as the last of the target node's children)
            if ($position === false)

                // give a numerical value to the position
                $position = count($target_children);

            // if a custom position was specified
            else {

                // make sure given position is an integer value
                $position = (int)$position;

                // if position is a bogus number
                if ($position > count($target_children) || $position < 0)

                    // use the default position (the last of the target node's children)
                    $position = count($target_children);

            }

            // we are about to do an insert and some nodes need to be updated first

            // if target has no children nodes OR the copy is to be inserted as the target node's first child node
            if (empty($target_children) || $position == 0)

                // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
                // the insert, and will need to be updated
                // if parent is not found (meaning that we're inserting a topmost node) set the boundary to 0
                $target_boundary = isset($this->lookup[$target]) ? $this->lookup[$target][$this->properties['left_column']] : 0;

            // if target has children nodes and/or the copy needs to be inserted at a specific position
            else {

                // find the target's child node that currently exists at the position where the new node needs to be inserted to
                // since PHP 5.3 this needs to be done in two steps rather than
                // $target_children = array_shift(array_slice($target_children, $position - 1, 1));
                // or PHP will trigger a warning "Strict standards: Only variables should be passed by reference"
                $slice = array_slice($target_children, $position - 1, 1);
                $target_children = array_shift($slice);

                // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
                // the insert, and will need to be updated
                $target_boundary = $target_children[$this->properties['right_column']];

            }

            // iterate through the nodes in the lookup array
            foreach ($this->lookup as $id => $properties) {

                // if the "left" value of node is outside the boundary
                if ($properties[$this->properties['left_column']] > $target_boundary)

                    // increment it
                    $this->lookup[$id][$this->properties['left_column']] += $source_rl_difference;

                // if the "right" value of node is outside the boundary
                if ($properties[$this->properties['right_column']] > $target_boundary)

                    // increment it
                    $this->lookup[$id][$this->properties['right_column']] += $source_rl_difference;

            }

            // lock table to prevent other sessions from modifying the data and thus preserving data integrity
            mysql_query('LOCK TABLE ' . $this->properties['table_name'] . ' WRITE');

            // update the nodes in the database having their "left"/"right" values outside the boundary
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = ' . $this->properties['left_column'] . ' + ' . $source_rl_difference . '
                WHERE
                    ' . $this->properties['left_column'] . ' > ' . $target_boundary . '

            ');

            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['right_column'] . ' = ' . $this->properties['right_column'] . ' + ' . $source_rl_difference . '
                WHERE
                    ' . $this->properties['right_column'] . ' > ' . $target_boundary . '

            ');

            // finally, the nodes that are to be inserted need to have their "left" and "right" values updated
            $shift = $target_boundary - $source_boundary + 1;

            // iterate through the nodes that are to be inserted
            foreach ($sources as $id => $properties) {

                // update "left" value
                $properties[$this->properties['left_column']] += $shift;

                // update "right" value
                $properties[$this->properties['right_column']] += $shift;

                // insert into the database
                mysql_query('
                    INSERT INTO
                        ' . $this->properties['table_name'] . '
                        (
                            ' . $this->properties['title_column'] . ',
                            ' . $this->properties['left_column'] . ',
                            ' . $this->properties['right_column'] . ',
                            ' . $this->properties['parent_column'] . '
                        )
                    VALUES
                        (
                            "' . mysql_real_escape_string($properties[$this->properties['title_column']]) . '",
                            ' . $properties[$this->properties['left_column']] . ',
                            ' . $properties[$this->properties['right_column']] . ',
                            ' . $properties[$this->properties['parent_column']] . '
                        )
                ');

                // get the ID of the newly inserted node
                $node_id = mysql_insert_id();

                // update the node's properties with the ID
                $properties[$this->properties['id_column']] = $node_id;

                // update the array of inserted items
                $sources[$id] = $properties;

            }

            // release table lock
            mysql_query('UNLOCK TABLES');

            // at this point, we have the nodes in the database but we need to also update the lookup array

            $parents = array();

            // iterate through the inserted nodes
            foreach ($sources as $id => $properties) {

                // if the node has any parents
                if (count($parents) > 0)

                    // iterate through the array of parent nodes
                    while ($parents[count($parents) - 1]['right'] < $properties[$this->properties['right_column']])

                        // and remove those which are not parents of the current node
                        array_pop($parents);

                // if there are any parents left
                if (count($parents) > 0)

                    // the last node in the $parents array is the current node's parent
                    $properties[$this->properties['parent_column']] = $parents[count($parents) - 1]['id'];

                // update the lookup array
                $this->lookup[$properties[$this->properties['id_column']]] = $properties;

                // add current node to the stack
                $parents[] = array(

                    'id'    =>  $properties[$this->properties['id_column']],
                    'right' =>  $properties[$this->properties['right_column']]

                );

            }

            // reorder the lookup array
            $this->_reorder_lookup_array();

            // return the ID of the copy
            return $sources[0][$this->properties['id_column']];

        }

        // if scripts gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Deletes a node, including the node's children nodes.
     *
     *  <code>
     *  // add a topmost node
     *  $node = $mptt->add(0, 'Main');
     *
     *  // add child node
     *  $child1 = $mptt->add($node, 'Child 1');
     *
     *  // add another child node
     *  $child2 = $mptt->add($node, 'Child 2');
     *
     *  // delete the "Child 2" node
     *  $mptt->delete($child2);
     *  </code>
     *
     *  @param  integer     $node       The ID of the node to be deleted.
     *
     *  @return boolean                 TRUE on success or FALSE upon error.
     */
    function delete($node) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // continue only if target node exists in the lookup array
        if (isset($this->lookup[$node])) {

            // get target node's children nodes (if any)
            $children = $this->get_children($node);

            // iterate through target node's children nodes
            foreach ($children as $child)

                // remove node from the lookup array
                unset($this->lookup[$child[$this->properties['id_column']]]);

            // lock table to prevent other sessions from modifying the data and thus preserving data integrity
            mysql_query('LOCK TABLE ' . $this->properties['table_name'] . ' WRITE');

            // also remove nodes from the database
            mysql_query('

                DELETE
                FROM
                    ' . $this->properties['table_name'] . '
                WHERE
                    ' . $this->properties['left_column'] . ' >= ' . $this->lookup[$node][$this->properties['left_column']] . ' AND
                    ' . $this->properties['right_column'] . ' <= ' . $this->lookup[$node][$this->properties['right_column']] . '

            ');

            // the value with which items outside the boundary set below, are to be updated with
            $target_rl_difference =

                $this->lookup[$node][$this->properties['right_column']] -

                $this->lookup[$node][$this->properties['left_column']]

                + 1;

            // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
            // the insert, and will need to be updated
            $boundary = $this->lookup[$node][$this->properties['left_column']];

            // remove the target node from the lookup array
            unset($this->lookup[$node]);

            // iterate through nodes in the lookup array
            foreach ($this->lookup as $id => $properties) {

                // if the "left" value of node is outside the boundary
                if ($this->lookup[$id][$this->properties['left_column']] > $boundary)

                    // decrement it
                    $this->lookup[$id][$this->properties['left_column']] -= $target_rl_difference;

                // if the "right" value of node is outside the boundary
                if ($this->lookup[$id][$this->properties['right_column']] > $boundary)

                    // decrement it
                    $this->lookup[$id][$this->properties['right_column']] -= $target_rl_difference;

            }

            // update the nodes in the database having their "left"/"right" values outside the boundary
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = ' . $this->properties['left_column'] . ' - ' . $target_rl_difference . '
                WHERE
                    ' . $this->properties['left_column'] . ' > ' . $boundary . '

            ');

            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['right_column'] . ' = ' . $this->properties['right_column'] . ' - ' . $target_rl_difference . '
                WHERE
                    ' . $this->properties['right_column'] . ' > ' . $boundary . '

            ');

            // release table lock
            mysql_query('UNLOCK TABLES');

            // return true as everything went well
            return true;

        }

        // if script gets this far, something must've went wrong so we return false
        return false;

    }

    /**
     *  Returns an unidimensional (flat) array with the children nodes of a given parent node.
     *
     *  <i>For a multi-dimensional array use the {@link get_tree()} method.</i>
     *
     *  @param  integer     $parent             (Optional) The ID of the node for which to return children nodes.
     *
     *                                          Default is "0" - return all the nodes.
     *
     *  @param  boolean     $children_only      (Optional) Set this to TRUE to return only the node's direct children nodes
     *                                          (and no children nodes of children nodes of children nodes...)
     *
     *                                          Default is FALSE
     *
     *  @return array                           Returns an unidimensional array with the children nodes of the given
     *                                          parent node.
     */
    function get_children($parent = 0, $children_only = false) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // if parent node exists in the lookup array OR we're looking for the topmost nodes
        if (isset($this->lookup[$parent]) || $parent === 0) {

            $children = array();

            // get the keys in the lookup array
            $keys = array_keys($this->lookup);

            // iterate through the available keys
            foreach ($keys as $item)

                // if
                if (

                    // node's "left" is higher than parent node's "left" (or, if parent is 0, if it is higher than 0)
                    $this->lookup[$item][$this->properties['left_column']] > ($parent !== 0 ? $this->lookup[$parent][$this->properties['left_column']] : 0) &&

                    // node's "left" is smaller than parent node's "right" (or, if parent is 0, if it is smaller than PHP's maximum integer value)
                    $this->lookup[$item][$this->properties['left_column']] < ($parent !== 0 ? $this->lookup[$parent][$this->properties['right_column']] : PHP_INT_MAX) &&

                    // if we only need the first level children, check if children node's parent node is the parent given as argument
                    (!$children_only || ($children_only && $this->lookup[$item][$this->properties['parent_column']] == $parent))

                )

                    // save to array
                    $children[$this->lookup[$item][$this->properties['id_column']]] = $this->lookup[$item];

            // return children nodes
            return $children;

        }

        // if script gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Returns the number of direct children nodes that a given node has (excluding children nodes of children nodes of
     *  children nodes and so on)
     *
     *  @param  integer     $node               The ID of the node for which to return the number of direct children nodes.
     *
     *  @return integer                         Returns the number of direct children nodes that a given node has, or
     *                                          FALSE on error.
     *
     *                                          <i>Since this method may return both "0" and FALSE, make sure you use ===
     *                                          to verify the returned result!</i>
     */
    function get_children_count($node) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // if node exists in the lookup array
        if (isset($this->lookup[$node])) {

            $result = 0;

            // iterate through all the records in the lookup array
            foreach ($this->lookup as $id => $properties)

                // if node is a direct children of the parent node
                if ($this->lookup[$id][$this->properties['parent_column']] == $node)

                    // increment the number of direct children
                    $result++;

            // return the number of direct children nodes
            return $result;

        }

        // if script gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Returns the number of total children nodes that a given node has, including children nodes of children nodes of
     *  children nodes and so on.
     *
     *  @param  integer     $node               The ID of the node for which to return the total number of descendant nodes.
     *
     *  @return integer                         Returns the number of total children nodes that a given node has, or
     *                                          FALSE on error.
     *
     *                                          <i>Since this method may return both "0" and FALSE, make sure you use ===
     *                                          to verify the returned result!</i>
     */
    function get_descendants_count($node) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // if parent node exists in the lookup array
        if (isset($this->lookup[$node]))

            // return the total number of descendant nodes
            return ($this->lookup[$node][$this->properties['right_column']] - $this->lookup[$node][$this->properties['left_column']] - 1) / 2;

        // if script gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Returns information about the node's direct parent node.
     *
     *  If node given as argument has a direct parent node, return an array containing the parent node's properties. If
     *  node given as argument is a topmost node, return 0.
     *
     *  @param  integer     $node               The ID of a node for which to return the direct parent node's properties.
     *
     *  @return mixed                           If node given as argument has a direct parent node, returns an array
     *                                          containing the parent node's properties. If node given as argument is a
     *                                          topmost node, returns 0.
     *
     *                                          <i>Since this method may return both "0" and FALSE, make sure you use ===
     *                                          to verify the returned result!</>
     */
    function get_parent($node) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // if node exists in the lookup array
        if (isset($this->lookup[$node]))

            // if node has a parent node, return the parent node's properties
            // also, return 0 if the node is a topmost node
            return isset($this->lookup[$node][$this->properties['parent_column']]) ? $this->lookup[$node][$this->properties['parent_column']] : 0;

        // if script gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Returns information about the node's data.
     *
     *
     *  @param  integer     $node               The ID of a node for which to return the direct parent node's properties.
	 *
     *  @param  string      $data               Column name.
     *
     *  @return mixed                           return specific data
     *
     *                                          <i>Since this method may return both "0" and FALSE, make sure you use ===
     *                                          to verify the returned result!</>
     */
    function get_specific_data($node,$data) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // if node exists in the lookup array
        if (isset($this->lookup[$node]))

            // if node has a parent node, return the parent node's properties
            // also, return 0 if the node is a topmost node
            return isset($this->lookup[$node][$this->properties[$data.'_column']]) ? $this->lookup[$node][$this->properties[$data.'_column']] : 0;

        // if script gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Returns an unidimensional (flat) array with the path to the given node (including the node itself).
     *
     *  @param  integer     $node               The ID of a node for which to return the path.
     *
     *  @return array                           Returns an unidimensional array with the path to the given node.
     */
    function get_path($node) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        $parents = array();

        // if node exists in the lookup array
        if (isset($this->lookup[$node])) {

            // iterate through all the nodes in the lookup array
            foreach ($this->lookup as $id => $properties)

                // if
                if (

                    // node is a parent node
                    $properties[$this->properties['left_column']] < $this->lookup[$node][$this->properties['left_column']] &&

                    $properties[$this->properties['right_column']] > $this->lookup[$node][$this->properties['right_column']]

                )

                    // save the parent node's information
                    $parents[$properties[$this->properties['id_column']]] = $properties;

        }
      if (isset($this->lookup[$node])) {
        // add also the node given as argument
        $parents[] = $this->lookup[$node];

        // return the path to the node
        return $parents;
      }
    }

    /**
     *  Returns tree of node with a link, it's very good for a "where you are" menu
     *
     *  @param  integer     $node       the id of category
     *
     *
     *  @param  string      $separator  A string to indent the nodes by.
     *
     *                                  Default is " - "
     *
     *
     *
     *  @param  string      $link       (OPTIONAL) link of node (it's put the id as querystring at the end)
     *
     *
     */
     function get_orizzontal($node = 0,$separator = '-',$link = null) {
       if(isset($link)){
         if(mb_substr($link,mb_strlen($link)-1,1) == '/')
          $link = mb_substr($link,0,-1);
       }
       $children = $this->get_path($node);
       $return_var = '';
       if (isset($this->lookup[$node])) {
        foreach ($children as $id => $properties) {
         $return_var .= (isset($link) && $properties[$this->properties['id_column']] != $node ? '<a href="'.$link.'?id='.$properties[$this->properties['id_column']].'">' : '').$properties[$this->properties['title_column']].(isset($link) && $properties[$this->properties['id_column']] != $node ? '</a>' : '').$separator;
        }
         return mb_substr($return_var,0,-mb_strlen($separator));
       }
     }
/************************************/
function filesystem($str, $options = array()){
	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	$defaults = array(
		'delimiter' => '-',
		'limit' => null,
		'lowercase' => false,
		'replacements' => array(),
		'transliterate' => true
	);
	// Merge options
	$options = array_merge($defaults, $options);
	$char_map = array(
		// Latin
		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
		'ß' => 'ss',
		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
		'ÿ' => 'y',
		// Latin symbols
		'©' => '(c)',
		// Greek
		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
		'Ϋ' => 'Y',
		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
		// Turkish
		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
		// Russian
		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
		'Я' => 'Ya',
		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
		'я' => 'ya',
		// Ukrainian
		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
		// Czech
		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
		'Ž' => 'Z',
		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
		'ž' => 'z',
		// Polish
		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
		'Ż' => 'Z',
		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
		'ż' => 'z',
		// Latvian
		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
		'š' => 's', 'ū' => 'u', 'ž' => 'z'
	);
	// Make custom replacements
	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	// Transliterate characters to ASCII
	if ($options['transliterate']) {
		$str = str_replace(array_keys($char_map), $char_map, $str);
	}
	// Replace non-alphanumeric characters with our delimiter
	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	// Remove duplicate delimiters
	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	// Truncate slug to max. characters
	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	// Remove delimiter from ends
	$str = trim($str, $options['delimiter']);
	return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}
     function get_orizzontal_path($node = 0,$separator = '/') {
       $children = $this->get_path($node);
       $return_var = '';
       if (isset($this->lookup[$node])) {
        foreach ($children as $id => $properties) {
         $return_var .= filesystem(html_entity_decode($properties[$this->properties['title_column']])).$separator;
        }
         return mb_substr($return_var,0,-mb_strlen($separator));
       }
     }
/************************************/
    /**
     *  Returns tree of node with a link, it's very good for a "vertical" menu
     *
     *  @param  integer     $node       the id of category
     *
     *
     *  @param  string      $separator  A string to indent the nodes by.
     *
     *                                  Default is "&#8286;.."
     *
     *
     *
     *  @param  string      $link       (OPTIONAL) link of node (it's put the id as querystring at the end)
     *
     *
     */
    function get_vertical($node = 0, $separator = '&#8286;..',$link = null) {
       if(isset($link)){
         if(mb_substr($link,mb_strlen($link)-1,1) == '/')
          $link = mb_substr($link,0,-1);
       }
        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // continue only if
        if (

            // parent node exists in the lookup array OR is 0 (indicating topmost node)
            isset($this->lookup[$node]) || $node == 0

        ) {

            // the resulting array and a temporary array
            $result = $parents = array();

            // get node's children nodes
            $children = $this->get_children($node);

            // if node is not 0
            if ($node != 0)

                // prepend the item itself to the list
                array_unshift($children, $this->lookup[$node]);

            // iterate through the nodes
            foreach ($children as $id => $properties) {

                // if we find a topmost node
                if ($properties[$this->properties['parent_column']] == 0) {

                    // if the $categories variable is set, save the categories we have so far
                    if (isset($nodes)) $result += $nodes;

                    // reset the categories and parents arrays
                    $nodes = $parents = array();

                }

                // if the node has any parents
                if (count($parents) > 0)

                    // iterate through the array of parent nodes
                    while (@array_pop(array_keys($parents)) < $properties[$this->properties['right_column']])

                        // and remove parents that are not parents of current node
                        array_pop($parents);

                // add node to the stack of nodes
                  $link_a = (isset($link) && $properties[$this->properties['id_column']] != $node ? '<a name_order="'.(empty($parents) ? $properties[$this->properties['title_column']] : '').'" href="'.$link.'?id='.$properties[$this->properties['id_column']].'">' : '');
                  $link_b = (isset($link) && $properties[$this->properties['id_column']] != $node ? '</a>' : '');
                  $nodes[$properties[$this->properties['id_column']]] = (!empty($parents) ? '-' : '').(!empty($parents) ? count($parents) != 1 ? str_repeat('&nbsp;&nbsp;', count($parents)).$separator : $separator : '') . $link_a.$properties[$this->properties['title_column']].$link_b.'<br/>';
                // add node to the stack of parents
                $parents[$properties[$this->properties['right_column']]] = $properties[$this->properties['title_column']];
            }

            // may not be set when there are no nodes at all
            if (isset($nodes))

                // finalize the result
                $result += $nodes;

            // ordering array
             $arr_order = array();
             $order_var = '';
               foreach($result as $key => $val){
                  if(mb_substr($val,0,1) != '-'){
                    $order_var .= '@'.$val;
                  }else{
                    $order_var .= mb_substr($val,1,mb_strlen($val));
                  }
               }
             $arr_order = explode('@',$order_var);
             asort($arr_order);
             // return the resulting
             $return_var = '';
             foreach($arr_order as $key => $val){
              $return_var .= $val;
             }
            return $return_var;
        }

        // if the script gets this far, return false as something must've went wrong
        return false;

    }


    /**
     *  Returns an array of children nodes of a node given as argument, indented and ready to be used in a <select>
     *  control.
     *
     *  @param  integer             $node       (Optional) The ID of a node for which to fetch its children nodes and return
     *                                          the node and its children as an array, indented and ready to be used in a <select>
     *                                          control.
     *
     *                                  Default is "0" - the generated array contains *all* the available nodes.
     *
     *  @param  string              $separator  A string to indent the nodes by.
     *
     *                                          Default is "&#8286;.."
     *
     *  @param  Boolean             $value_tree   if false give as value the id of category into value of option.
	 *                                            if true give as value the tree of category into value of option
     *
	 *  @param Boolean/integer      $editable_id   its value must be the id of category to edit.
	 *
	 *  @param  Boolean/integer     $selected   give a selected category (its value must be the id of selected category) (this option is false with $editable_id specified).
     *
     *
     *  @return array                   Returns options structure indented and ready to be used in a <select> control.
     */
    private function values_structure($value_tree,$tree,$id){
	 return ($value_tree ? ($tree != '' ? $tree.'|'.$id : $id) : $id);
	}
    function get_selectables($node = 0,$value_tree = true,$editable_id = false,$selected = false,$separator = '&#8286;..') {
		$result = $this->get_children($node, true);
		$result = array_msort($result, array('name'=>SORT_ASC));
		$options = '';
        foreach ($result as $id => $properties){
		  if($editable_id){
			 $selected_option = $result[$id]['id'] == $this->get_parent($editable_id) ? 'selected' : '';
			 if ($this->get_parent($result[$id]['id']) == $editable_id || $result[$id]['id'] == $editable_id)
			  continue;
		  }else{
		     $selected_option = $selected ? ($result[$id]['id'] == $selected ? 'selected' : '') : '';
		  }
           $options .= '<option value="'.$this->values_structure($value_tree,$result[$id]['tree_path'],$result[$id]['id']).'" '.$selected_option.'>';
		   $options .= str_repeat('&nbsp;&nbsp;',count($this->get_path($result[$id]['id']))-1);
		   $options .= count($this->get_path($result[$id]['id'])) > 1 ? $separator : '';
		   $options .= $result[$id]['name'];
            $result[$id]['children'] = $this->get_tree($id);
			$result[$id]['children'] = array_msort($result[$id]['children'], array('name'=>SORT_ASC));
			foreach($result[$id]['children'] as $key => $val){
			  if($editable_id){
				 $selected_option = $val['id'] == $this->get_parent($editable_id) ? 'selected' : '';
				 if ($this->get_parent($val['id']) == $editable_id || $val['id'] == $editable_id )
				  continue;
			  }else{
			     $selected_option = $selected ? ($val['id'] == $selected ? 'selected' : '') : '';
			  }
			  $options .= '<option value="'.$this->values_structure($value_tree,$val['tree_path'],$val['id']).'" '.$selected_option.'>';
			  $options .= str_repeat('&nbsp;&nbsp;',count($this->get_path($val['id']))-1);
			  $options .= count($this->get_path($val['id'])) > 1 ? $separator : '';
			  $options .= $val['name'];
			  $options .= $this->get_selectables($key,$value_tree,$editable_id,$selected).'</option>';
			}
			$options .= '</option>';
	     }
        return $options;
    }
    /**
     *  Returns a multi dimensional array with all the descendant nodes (including children nodes of children nodes of
     *  children nodes and so on) of a given node.
     *
     *  @param  integer     $node               (Optional) The ID of the node for which to return all descendant nodes
     *                                          as a multi-dimensional array.
     *
     *                                          Default is "0" - return all the nodes.
     *
     *  @return array                           Returns a multi dimensional array with all the descendant nodes (including
     *                                          children nodes of children nodes of children nodes and so on) of a given
     *                                          node.
     */
    function get_tree($node = 0) {

        // get direct children nodes
        $result = $this->get_children($node, true);

        // iterate through the direct children nodes
        foreach ($result as $id => $properties)

            // for each child node create a "children" property
            // and get the node's children nodes, recursively
            $result[$id]['children'] = $this->get_tree($id);

        // return the array
        return $result;

    }


    /**
     *  Moves a node, including node's children nodes, as the children of a target node.
     *
     *  <code>
     *  // insert a topmost node
     *  $node = $mptt->add(0, 'Main');
     *
     *  // add a child node
     *  $child1 = $mptt->add($node, 'Child 1');
     *
     *  // add another child node
     *  $child2 = $mptt->add($node, 'Child 2');
     *
     *  // move "Child 2" node to be the first of "Main"'s children nodes
     *  $mptt->move($child2, $node, 0);
     *  </code>
     *
     *  @param  integer     $source     The ID of the node that needs to be moved.
     *
     *  @param  integer     $target     The ID of the node where {@link $source} node needs to be moved to. Use "0" if
     *                                  the node does not need a parent node (making it a topmost node).
     *
     *  @param  integer     $position   (Optional) The position the node will have amongst the {@link $parent}'s
     *                                  children nodes.
     *
     *                                  When {@link $parent} is "0", this refers to the position the node will have
     *                                  amongst the topmost nodes.
     *
     *                                  The values are 0-based, meaning that if you want the node to be inserted as
     *                                  the first in the list of {@link $parent}'s children nodes, you have to use "0".<br>
     *                                  If you want it to be second, use "1" and so on.
     *
     *                                  Default is "0" - the node will be inserted as last of the {@link $parent}'s
     *                                  children nodes.
     *
     *  @return boolean                 TRUE on success or FALSE upon error
     */
    function move($source, $target, $position = false) {

        // lazy connection: touch the database only when the data is required for the first time and not at object instantiation
        $this->_init();

        // continue only if
        if (

            // source node exists in the lookup array AND
            isset($this->lookup[$source]) &&

            // target node exists in the lookup array OR is 0 (indicating a topmost node)
            (isset($this->lookup[$target]) || $target == 0) &&

            // target node is not a child node of the source node (that would cause infinite loop)
            !in_array($target, array_keys($this->get_children($source)))

        ) {

            // the source's parent node's ID becomes the target node's ID
            $this->lookup[$source][$this->properties['parent_column']] = $target;

            // get source node's children nodes (if any)
            $source_children = $this->get_children($source);

            // this array will hold the nodes we need to move
            // by default we add the source node to it
            $sources = array($this->lookup[$source]);

            // iterate through source node's children
            foreach ($source_children as $child) {

                // save them for later use
                $sources[] = $this->lookup[$child[$this->properties['id_column']]];

                // for now, remove them from the lookup array
                unset($this->lookup[$child[$this->properties['id_column']]]);

            }

            // the value with which nodes outside the boundary set below, are to be updated with
            $source_rl_difference =

                $this->lookup[$source][$this->properties['right_column']] -

                $this->lookup[$source][$this->properties['left_column']]

                + 1;

            // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
            // the insert, and will need to be updated
            $source_boundary = $this->lookup[$source][$this->properties['left_column']];

            // lock table to prevent other sessions from modifying the data and thus preserving data integrity
            mysql_query('LOCK TABLE ' . $this->properties['table_name'] . ' WRITE');

            // we'll multiply the "left" and "right" values of the nodes we're about to move with "-1", in order to
            // prevent the values being changed further in the script
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = ' . $this->properties['left_column'] . ' * -1,
                    ' . $this->properties['right_column'] . ' = ' . $this->properties['right_column'] . ' * -1
                WHERE
                    ' . $this->properties['left_column'] . ' >= ' . $this->lookup[$source][$this->properties['left_column']] . ' AND
                    ' . $this->properties['right_column'] . ' <= ' . $this->lookup[$source][$this->properties['right_column']] . '

            ');

            // remove the source node from the list
            unset($this->lookup[$source]);

            // iterate through the remaining nodes in the lookup array
            foreach ($this->lookup as $id=>$properties) {

                // if the "left" value of node is outside the boundary
                if ($this->lookup[$id][$this->properties['left_column']] > $source_boundary)

                    // decrement it
                    $this->lookup[$id][$this->properties['left_column']] -= $source_rl_difference;

                // if the "right" value of item is outside the boundary
                if ($this->lookup[$id][$this->properties['right_column']] > $source_boundary)

                    // decrement it
                    $this->lookup[$id][$this->properties['right_column']] -= $source_rl_difference;

            }

            // update the nodes in the database having their "left"/"right" values outside the boundary
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = ' . $this->properties['left_column'] . ' - ' . $source_rl_difference . '
                WHERE
                    ' . $this->properties['left_column'] . ' > ' . $source_boundary . '

            ');

            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['right_column'] . ' = ' . $this->properties['right_column'] . ' - ' . $source_rl_difference . '
                WHERE
                    ' . $this->properties['right_column'] . ' > ' . $source_boundary . '

            ');

            // get children nodes of target node (first level only)
            $target_children = $this->get_children((int)$target, true);

            // if node is to be inserted in the default position (as the last of target node's children nodes)
            if ($position === false)

                // give a numerical value to the position
                $position = count($target_children);

            // if a custom position was specified
            else {

                // make sure given position is an integer value
                $position = (int)$position;

                // if position is a bogus number
                if ($position > count($target_children) || $position < 0)

                    // use the default position (as the last of the target node's children)
                    $position = count($target_children);

            }

            // because of the insert, some nodes need to have their "left" and/or "right" values adjusted

            // if target node has no children nodes OR the node is to be inserted as the target node's first child node
            if (empty($target_children) || $position == 0)

                // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
                // the insert, and will need to be updated
                // if parent is not found (meaning that we're inserting a topmost node) set the boundary to 0
                $target_boundary = isset($this->lookup[$target]) ? $this->lookup[$target][$this->properties['left_column']] : 0;

            // if target has any children nodes and/or the node needs to be inserted at a specific position
            else {

                // find the target's child node that currently exists at the position where the new node needs to be inserted to
                // since PHP 5.3 this needs to be done in two steps rather than
                // $target_children = array_shift(array_slice($target_children, $position - 1, 1));
                // or PHP will trigger a warning "Strict standards: Only variables should be passed by reference"
                $slice = array_slice($target_children, $position - 1, 1);
                $target_children = array_shift($slice);

                // set the boundary - nodes having their "left"/"right" values outside this boundary will be affected by
                // the insert, and will need to be updated
                $target_boundary = $target_children[$this->properties['right_column']];

            }

            // iterate through the records in the lookup array
            foreach ($this->lookup as $id => $properties) {

                // if the "left" value of node is outside the boundary
                if ($properties[$this->properties['left_column']] > $target_boundary)

                    // increment it
                    $this->lookup[$id][$this->properties['left_column']] += $source_rl_difference;

                // if the "left" value of node is outside the boundary
                if ($properties[$this->properties['right_column']] > $target_boundary)

                    // increment it
                    $this->lookup[$id][$this->properties['right_column']] += $source_rl_difference;

            }

            // update the nodes in the database having their "left"/"right" values outside the boundary
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = ' . $this->properties['left_column'] . ' + ' . $source_rl_difference . '
                WHERE
                    ' . $this->properties['left_column'] . ' > ' . $target_boundary . '

            ');

            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['right_column'] . ' = ' . $this->properties['right_column'] . ' + ' . $source_rl_difference . '
                WHERE
                    ' . $this->properties['right_column'] . ' > ' . $target_boundary . '

            ');

            // finally, the nodes that are to be inserted need to have their "left" and "right" values updated
            $shift = $target_boundary - $source_boundary + 1;

            // iterate through the nodes to be inserted
            foreach ($sources as $properties) {

                // update "left" value
                $properties[$this->properties['left_column']] += $shift;

                // update "right" value
                $properties[$this->properties['right_column']] += $shift;

                // add the item to our lookup array
                $this->lookup[$properties[$this->properties['id_column']]] = $properties;

            }

            // also update the entries in the database
            // (notice that we're subtracting rather than adding and that finally we multiply by -1 so that the values
            // turn positive again)
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['left_column'] . ' = (' . $this->properties['left_column'] . ' - ' . $shift . ') * -1,
                    ' . $this->properties['right_column'] . ' = (' . $this->properties['right_column'] . ' - ' . $shift . ') * -1
                WHERE
                    ' . $this->properties['left_column'] . ' < 0

            ');

            // finally, update the parent of the source node
            mysql_query('

                UPDATE
                    ' . $this->properties['table_name'] . '
                SET
                    ' . $this->properties['parent_column'] . ' = ' . $target . '
                WHERE
                    ' . $this->properties['id_column'] . ' = ' . $source . '

            ');

            // release table lock
            mysql_query('UNLOCK TABLES');

            // reorder the lookup array
            $this->_reorder_lookup_array();

            // return true as everything went well
            return true;

        }

        // if scripts gets this far, return false as something must've went wrong
        return false;

    }

    /**
     *  Reads the data from the MySQL table and creates a lookup array. Searches will be done in the lookup array
     *  rather than always querying the database.
     *
     *  @return void
     *
     *  @access private
     */
    function _init() {

        // if the results are not already cached
        if (!isset($this->lookup)) {
            // fetch data from the database
            $result = mysql_query('

                SELECT
                    *
                FROM
                    ' . $this->properties['table_name'] . '
                ORDER BY
                    ' . $this->properties['left_column'] . '

            ');

            $this->lookup = array();

            // iterate through the found records
            while ($row = mysql_fetch_assoc($result)) {

                // put all records in an array; use the ID column as index
                $this->lookup[$row[$this->properties['id_column']]] = $row;

            }

        }

    }

    /**
     *  Updates the lookup array after inserts and deletes.
     *
     *  @return void
     *
     *  @access private
     */
    function _reorder_lookup_array() {

        // re-order the lookup array

        // iterate through the nodes in the lookup array
        foreach ($this->lookup as $properties)

            // create a new array with the name of "left" column, having the values from the "left" column
            ${$this->properties['left_column']}[] = $properties[$this->properties['left_column']];

        // order the array by the left column
        // in the ordering process, the keys are lost
        array_multisort(${$this->properties['left_column']}, SORT_ASC, $this->lookup);

        $tmp = array();

        // iterate through the existing nodes
        foreach ($this->lookup as $properties)

            // and save them to a different array, this time with the correct ID
            $tmp[$properties[$this->properties['id_column']]] = $properties;

        // the updated lookup array
        $this->lookup = $tmp;

    }
}

?>