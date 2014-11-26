<?php

namespace Application\Model;

class UserTable extends AppTable {

    public $id;
    public $level;
    public $name = 'Guest';
    public $active;
    public $email;
    public $login;
    public $country;
    public $code;
    public $newpass;
    public $password;
    public $phone;
    protected $goodFields = array(
        'id',
        'name',
        'email',
        'level',
        'active',
        'password',
        'newpass',
        'code',
        'phone',
    );

    public function __construct($userId = null) {
        parent::__construct('user', $userId);
    }

    /**
     * sets data for current user; do not update password if don't need
     *
     * @param array $data
     */
    public function set($data) {
        if (isset($data['pass'])) {
            if (!empty($data['pass']))
                $data['password'] = $this->saltPass($data['pass']);
        }
        parent::set($data);
    }

    /**
     * Inserts a record; set password value as hash
     *
     * @param array $set
     * @return int last insert Id
     */
    public function insert($set) {
        if (isset($set['password'])) {
            $set['password'] = $this->saltPass($set['password']);
        }
        $id = parent::insert($set);
        return $id;
    }

    /**
     * returns random user ids
     *
     * @param int $limit
     * @param array $excludeUsers
     * @return array
     */
    public function findRandomUsers($limit = 10, $excludeUsers = array()) {
        if (!empty($excludeUsers)) {
            $exclude = ' where id not in (' . implode(',', $excludeUsers) . ')';
        }
        $result = $this->query('select id from user' . $exclude . ' order by rand() limit ' . $limit);
        $users = array();
        foreach ($result as $user) {
            $users [] = $user->id;
        }

        return $users;
    }

    /**
     * deletes user
     *
     * @param int $id
     * @return bool: true on OK, false on user not found
     */
    public function delete($id) {
        return (bool) parent::delete(array('id' => $id));
    }

    /**
     * returns name or login
     * 
     * @param mixed $id
     * @param default return name
     */
    public function getUserName($id, $login = false) {
        $row = $this->select(array('id' => $id))->current();
        if ($login)
            return $row->login;
        else
            return $row->name;
    }

    /**
     * check login data for user
     * return user object if exists or false
     * 
     * @param string $email
     * @param string $pass
     * @return \ArrayObject|bool
     */
    public function checkLogin($email, $pass) {
        if (!$email || !$pass)
            return false;

        $row = $this->find(array(
            array('email', '=', $email),
            array('password', '=', $this->saltPass($pass)),
            array('active', '=', '1'))
        );
        if ($row) {
            return array_pop($row);
        } else {
            return false;
        }
    }

    public function createUser($login, $email, $password) {
        return $this->insert(array(
                    'login' => $login,
                    'email' => $email,
                    'password' => $this->saltPass($password),
                    'code' => \Application\Lib\Utils::generatePassword(32),
                    'created' => TIME,
        ));
    }

    /**
     * create hash code for password
     * 
     * @param string $pass
     */
    public function saltPass($pass) {
        return hash('sha256', DATABASE_SALT . $pass);
    }

    /**
     * send request to change password;
     * user password will be changed only after confirmation by link
     * 
     * @param mixed $email
     * @param mixed $newpass
     */
    public function forgotPass($email, $newpass) {
        $UserRow = $this->select(array('email' => $email))->current();
        if (!$UserRow) {
            throw new \Exception(_('This E-mail is not found in our records'));
        }

        $this->setId($UserRow->id);
        $this->code = \Application\Lib\Utils::generatePassword(32);
        $this->newpass = $this->saltPass($newpass);

        $this->set(array(
            'newpass' => $this->newpass,
            'code' => $this->code,
        ));
    }

    /**
     * save new password as user password for authentication
     * after confirmation code check
     * 
     * @param int $id
     * @param string $code
     */
    public function activeForgotPass($id, $code) {
        //get user
        $userRow = $this->select(array('id' => $id, 'code' => $code))->current();
        if (!$userRow) {
            throw new \Exception(_('Bad confirmation code'));
        }

        $this->setId($userRow->id);

        //set new pass
        $this->set(array(
            'password' => $userRow->newpass,
            'code' => '',
        ));
    }

    /**
     * activates user.
     * 
     * @param string $code
     * @throws Exception on code not found
     */
    public function activate($code) {
        $user = $this->find(array(array('code', '=', $code)))->current();

        if (!$user) {
            throw new \Exception(_('Wrong activation code or account already activated'));
        }

        $this->setId($user->id);
        $this->set(array(
            'active' => 1,
            'code' => '',
        ));
    }

    public function getShoppingCartProductListFromUser($user)
    {
        $result = $this->select(function ($select) use ($user) {
            $select->join('shopping_cart', $this->getTable() . '.id = shopping_cart.id_user', array('count'));
            $select->join('product', 'shopping_cart.id_product = product.id', array('*'));
            $select->join('product_type', 'product.type_id = product_type.id', array('type' => 'title'));
            $select->join('product_attachment', 'product.id = product_attachment.product_id', array(), $select::JOIN_LEFT);
            $select->join('attachment', 'product_attachment.attachment_id = attachment.id', array('resource'), $select::JOIN_LEFT);
            $select->where($this->getTable() . '.id=' . $user->getUser()->getId());
            $select->group('product.id');
            $select->columns(array());
        });
        return $result;
    }
}
