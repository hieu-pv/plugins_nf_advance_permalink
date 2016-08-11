<?php

namespace NFWP\Models;

require __DIR__ . '/../../../../../../../../wp-includes/pluggable.php';

use WP_User;

class NFUser extends WP_User
{

    /**
     * aggregate of where constrain
     *
     * @var Array
     */
    public $where = [];

    /**
     * aggregate of user data key
     *
     * @var Array
     */
    public $user_data_key = ['ID', 'user_pass', 'user_login', 'user_nicename', 'user_url', 'user_email', 'display_name', 'nickname', 'first_name', 'last_name', 'description', 'rich_editing', 'user_registered', 'role', 'jabber', 'aim', 'yim', 'show_admin_bar_front'];

    /**
     * aggreate of all operators which can be accepted
     *
     * @var Array
     */
    public $operators = ['='];

    /**
     * Retrieve user info by ID
     *
     * @param init $id
     * @return self
     */
    public static function find($id)
    {
        $user = self::get_data_by('ID', $id);
        return new self($user);
    }

    /**
     * Retrieve current user info
     *
     * @param init $id
     * @return self
     */
    public static function current()
    {
        $user = wp_get_current_user();
        return new self($user);
    }

    /**
     * find user by id and throw error if no user is found
     *
     * @param int $id
     * @return WP_User object or throw error if no user is found
     */
    public static function findOrFail($id)
    {
        $user = self::get_data_by('ID', $id);
        if ($user == false) {
            throw new Exception('can\'t find user', 0);
        }
        return new self($user);
    }

    /**
     * set data to Where constrain
     *
     * @param string id | ID | slug | email | login
     * @return self
     */
    public static function where($first, $second, $third = null)
    {
        $where     = self::__callStatic('getWhere');
        $operators = self::__callStatic('getOperators');
        $constrain = [
            'field' => $first,
        ];

        if (in_array($second, $operators)) {
            $constrain['operator'] = $second;
            $constrain['value']    = $third;
        } else {
            $constrain['operator'] = '=';
            $constrain['value']    = $second;
        }
        $where[] = $constrain;
        return self::__callStatic('setWhere', [$where]);
    }

    /**
     * Retrieve user info
     *
     * @return Array
     */
    public function fetch()
    {
        $users = [];
        foreach ($this->where as $constrain) {
            $user = self::get_data_by($constrain['field'], $constrain['value']);
            if ($user) {
                $user = new self($user);
                if (empty($users)) {
                    array_push($users, $user);
                } else {
                    if ($user != $users[0]) {
                        return [];
                    }
                }
            }
        }
        return $users;
    }

    /**
     * Get user meta data
     *
     * @var String $key, Boolean $single
     */
    public function getMetaData($key = '', $single = false)
    {
        $data = get_user_meta($this->ID, $key, $single);
        return $data;
    }

    /**
     * Add user meta data
     *
     * @var $key, $value
     */
    public function setMetaData($key, $value)
    {
        return add_user_meta($this->ID, $key, $value);
    }

    /**
     * Update user meta data
     *
     * @var $key, $value
     */
    public function updateMetaData($key, $value)
    {
        return update_user_meta($this->ID, $key, $value);
    }

    /**
     * Delete user meta data
     *
     * @var $key
     */
    public function deleteMetaData($key)
    {
        return delete_user_meta($this->ID, $key);
    }

    /**
     * update user meta data
     *
     * @var $key, $value
     * @return mixed
     */
    public function save()
    {
        wp_update_user($this);
    }

    public function first()
    {
        $users = $this->fetch();
        if (empty($users)) {
            return false;
        } else {
            return $users[0];
        }
    }

    public static function __callStatic($method, $parameters = [])
    {
        $instance = new static;
        return call_user_func_array([$instance, $method], $parameters);
    }

    /**
     * Gets the aggregate of where constrain.
     *
     * @return Array
     */
    public function getWhere()
    {
        return $this->where;
    }

    /**
     * Sets the aggregate of where constrain.
     *
     * @param Array $where the where
     *
     * @return self
     */
    public function setWhere(array $where)
    {
        $this->where = $where;

        return $this;
    }

    /**
     * Gets the aggreate of all operators which can be accepted.
     *
     * @return Array
     */
    public function getOperators()
    {
        return $this->operators;
    }

    /**
     * Sets the aggreate of all operators which can be accepted.
     *
     * @param Array $operators the operators
     *
     * @return self
     */
    public function setOperators(array $operators)
    {
        $this->operators = $operators;

        return $this;
    }
}
