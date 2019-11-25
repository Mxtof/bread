<?php
declare(strict_types = 1);

namespace com\mxtof\sql;

require_once 'Database.class.php';
require_once 'FieldFormatException.class.php';
require_once 'FieldSizeException.class.php';
require_once 'QueryException.class.php';

/**
 * Represents an user entry in the underlying database.
 *
 * @author mxtof <michel.xtof@mail.fr>
 * @package com\mxtof\sql
 * @filesource User.class.php
 */
class User {

    /**
     * Specifies the user table name.
     */
    public const TABLE_NAME         = "users";
    /**
     * Specifies the minimum size for name inputs.
     */
    public const NAME_MIN_SIZE      = 2;
    /**
     * Specifies the maximum size for name inputs.
     */
    public const NAME_MAX_SIZE      = 64;
    /**
     * Specifies the minimum size for email inputs.
     */
    public const EMAIL_MIN_SIZE     = 8;
    /**
     * Specifies the maximum size for email inputs.
     */
    public const EMAIL_MAX_SIZE     = 64;
    /**
     * Specifies the minimum size for password inputs.
     */
    public const PASSWORD_MIN_SIZE  = 4;
    /**
     * Specifies the maximum size for email inputs.
     */
    public const PASSWORD_MAX_SIZE  = 64;
    /**
     * Specifies the admin group.
     */
    public const GROUP_ADMIN        = "admin";
    /**
     * Specifies the user group.
     */
    public const GROUP_USER         = "user";
    /**
     * Specifies the pending user status.
     */
    public const STATUS_PENDING     = "pending";
    /**
     * Specifies the enabled user status.
     */
    public const STATUS_ENABLED     = "enabled";
    /**
     * Specifies the disabled user status.
     */
    public const STATUS_DISABLED    = "disabled";

    /**
     * Specifies the format for name inputs.
     */
    private const NAME_WHITELIST     = "/^[a-zA-Z0-9_\-\.]*$/"; //  "/^[a-zA-Z][a-zA-Z0-9-_\.]$/"
    /**
     * Specifies the forbidden characters for password inputs.
     */
    private const PASSWORD_BLACKLIST = "/[\<\>\[\]\{\}\:\#]/";
    /**
     * Specifies the algorithm for password hashing.
     */
    private const HASH_ALGORITHM     = \PASSWORD_DEFAULT;
    /**
     * Specifies the options for password hashing.
     */
    private const HASH_OPTIONS       = array("cost"=>11);

    /**
     * Creates a default user.
     *
     * The initial state is:
     * * group is set to `GROUP_USER`;
     * * status is set to `STATUS_PENDING`;
     * * created and modified are both set to now.
     *
     * All other attributes are unset.
     *
     * @return User
     */
    public static function create() : User {
        $user = new User();
        $now  = date_create()->format("Y-m-d H:i:s");

        $user->created = $user->modified = $now;
        $user->group   = self::GROUP_USER;
        $user->status  = self::STATUS_PENDING;
        return $user;
    }

    /**
     * Checks the size of an input against a given range (class utility).
     *
     * @param string $input     The input string to be checked.
     * @param string $name      The attribute name.
     * @param integer $min      The lower bound of the range.
     * @param integer $max      The upper bound of the range.
     * @return string
     * The input itself, minus potential leading/trailing whitespaces.
     * @throws FieldSizeException
     * if the size of such input is out of range.
     */
    private static function checkSize(
        string $name,
        string $input,
        int $min,
        int $max
    ): string
    {
        $value = \trim($input);
        $size = \mb_strlen($value);

        if (($size < $min) || ($size > $max)) {
            throw new FieldSizeException($name, $size, $min, $max);
        }
        return $value;
    }


    /**
     * The user identifier.
     *
     * This is a read-only attribute.
     *
     * @var int
     */
    private $id;
    /**
     * The user name.
     *
     * First character *must* be a letter;
     * Following characters must be one of:
     * * Letters (`[a-zA-Z]`);
     * * Digits (`[0-9]`);;
     * * Hyphen (`'-'`);
     * * Dot (`'.'`);
     * * Underscore (`'_'`).
     *
     * @var string
     */
    private $name;
    /**
     * The user email.
     *
     * @var string
     */
    private $email;
    /**
     * The user password.
     *
     * Forbidden characters being:
     * * Any bracket; whether angle (`< >`), curly (`{ }`),
     *   or square (`[ ]`);
     * * Colon (`:`);
     * * Hash character (`#`).
     *
     * @var string
     */
    private $password;
    /**
     * The user group.
     *
     * @var string
     */
    private $group;
    /**
     * The user status.
     *
     * @var string
     */
    private $status;
    /**
     * The creation date.
     *
     * This is a read-only attribute.
     *
     * @var string
     */
    private $created;
    /**
     * The last modification date.
     *
     * This is a read-only attribute.
     *
     * @var string
     */
    private $modified;
    /**
     * Whether this user has been logged in.
     *
     * This is a read-only attribute.
     *
     * @var boolean
     */
    private $authenticated = false;


    /**
     * Default constructor, made private to enforce the use of factory methods.
     *
     * @return void
     */
    private function __construct() {}

    /**
     * Creates a new user entry in the database.
     *
     * @return void
     *
     * @throws QueryException
     * If the search raised an issue.
     * @throws LogicException
     * If the database hasn't been started;
     * or if either this name or password or email is not set.
     */
    public function register() {
        if (! $this->isRegistrable()) {
            throw new \LogicException("All name, password and email should be set.");
        }

        $sql =
<<<STATEMENT
            INSERT INTO `users`
                (`name`, `email`, `password`, `group`, `status`)
            VALUES
                (:name, :email, :password, :group, :status);
STATEMENT;

        $hash = \password_hash(
            $this->password,
            self::HASH_ALGORITHM,
            self::HASH_OPTIONS
        );
        $this->password = $hash;

        $handle    = Database::getInstance()->getHandle();
        $statement = $handle->prepare($sql);
        $type      = \PDO::PARAM_STR;

        $statement->bindValue(":name",     $this->name,     $type);
        $statement->bindValue(":email",    $this->email,    $type);
        $statement->bindValue(":password", $this->password, $type);
        $statement->bindValue(":group",    $this->group,    $type);
        $statement->bindValue(":status",   $this->status,   $type);

        try {
            $statement->execute();

        } catch (\PDOException $cause) {
            throw new QueryException("registration failure", 0, $cause);
        }

        $this->id = intval($handle->lastInsertId());
        $this->authenticated = true;
    }

    /**
     * Searches this user's name in the database, and checks this
     * password against the stored one.
     *
     * If the login is successful, the whole state of this object will
     * be updated to reflect the user entry.
     *
     * @param boolean $doSaveSession
     * `true`, if the current session id is to be saved in the sessions table;
     * `false` otherwise.
     *
     * @return boolean
     * `true` if the login is successful;
     * `false` if no such name was found, or if passwords did not match.
     *
     * @throws QueryException
     * If the search raised an issue.
     * @throws LogicException
     * If the database hasn't been started;
     * or if either this name or password is not set.
     */
    public function login(bool $doSaveSession = false) : bool {
        if (! $this->isAuthenticable()) {
            throw new \LogicException("Both name and password should be set.");
        }

        $sql =
<<<STATEMENT
            SELECT * FROM `users`
            WHERE (`name`   = ?)
            AND   (`status` = 'enabled')
            LIMIT 1
STATEMENT;

        $statement = $this->prepareStatement($sql);
        try {
            $statement->execute(array($this->name));

        } catch (\PDOException $cause) {
            throw new QueryException("login failure", 0, $cause);
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! is_array($row)) {
            return false;
        }

        if (! \password_verify($this->password, $row["password"])) {
            return false;
        }

        $this->setAttributes($row);
        if ($doSaveSession) {
            $this->saveSession();
        }

        return ($this->authenticated = true);
    }

    /**
     * Attempts to restore a previously saved session, if any.
     *
     * @return boolean
     * `true`, if such session is successfully restored;
     * `false` otherwise.
     * @throws QueryException
     * If the search raised an issue.
     * @throws LogicException
     * If the database hasn't been started.
     */
    public function sessionLogin() : bool {
        if (! $this->sessionExists()) {
            return false;
        }

        $sql =
<<<STATEMENT
            SELECT * FROM `sessions`, `users`
            WHERE (`sessions`.`id` = ?)
            AND (`sessions`.`login_time` >= (NOW() - INTERVAL 30 DAY))
            AND (`sessions`.`user_id` = `users`.`id`)
            AND (`users`.`status` = 'enabled')
            LIMIT 1;
STATEMENT;

        $statement = $this->prepareStatement($sql);
        try {
            $statement->execute(array(\session_id()));

        } catch (\PDOException $cause) {
            throw new QueryException("session login failure", 0, $cause);
        }

        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! is_array($row)) {
            return false;
        }

        $this->setAttributes($row);
        return ($this->authenticated = true);
    }

    /**
     * Terminates this user's current session.
     *
     * Previously saved session, if any, is removed from
     * the database.
     *
     * @return void
     * @throws QueryException
     * If the session table could not be updated.
     * @throws LogicException
     * If the database hasn't been started.
     */
    public function logout() {
        if (! $this->sessionExists()) {
            return;
        }

        $sql = "DELETE FROM `sessions` WHERE (`id` = ?);";
        $statement = $this->prepareStatement($sql);

        try {
            $statement->execute(array(\session_id()));

        } catch (\PDOException $cause) {
            throw new QueryException("logout failure", 0, $cause);
        }
    }

    /**
     * Retrieves the user identifier.
     *
     * @return integer|null
     */
    public function getId() : ?int {
        return $this->id;
    }

    /**
     * Retrieves the user name.
     *
     * @return string|null
     */
    public function getName() : ?string {
        return $this->name;
    }

    /**
     * Specifies the user name.
     *
     * @param  string  $name  The user name.
     *
     * @return self
     *
     * @throws FieldSizeException
     * If the name size is out of range.
     *
     * @throws FieldFormatException
     * If such value does not comply with the format of this attribute.
     */
    public function setName(string $name) : User {
        $value = self::checkSize(
            "name",
            $name,
            self::NAME_MIN_SIZE,
            self::NAME_MAX_SIZE
        );

        if (! \preg_match(self::NAME_WHITELIST, $value)) {
            throw new FieldFormatException("name", $value);
        }

        $this->name = $value;
        return $this;
    }

    /**
     * Checks whether this name exists in the database.
     *
     * @return boolean
     * `true` if this name exists; `false` otherwise.
     * @throws QueryException
     * If the search raised an issue.
     * @throws LogicException
     * If the database hasn't been started;
     */
    public function nameExists() : bool {
        return isset($this->name) &&
                Database::getInstance()->contains("name", $this->name);
    }

    /**
     * Retrieves the user email.
     *
     * @return  string|null
     */
    public function getEmail() : ?string {
        return $this->email;
    }

    /**
     * Specifies the user email.
     *
     * @param  string  $email  The user email.
     *
     * @return  self
     *
     * @throws FieldSizeException
     * If the email size is out of range.
     *
     * @throws FieldFormatException
     * If such value does not comply with the format of this attribute.
     */
    public function setEmail(string $email) : User {
        $value = self::checkSize(
            "email",
            $email,
            self::EMAIL_MIN_SIZE,
            self::EMAIL_MAX_SIZE
        );

        if (! \filter_var($value, \FILTER_VALIDATE_EMAIL)) {
            throw new FieldFormatException("email", $value);
        }

        $this->email = $value;
        return $this;
    }

    /**
     * Checks whether this email exists in the database.
     *
     * @return boolean
     * `true` if this email exists; `false` otherwise.
     * @throws QueryException
     * If the search raised an issue.
     * @throws LogicException
     * If the database hasn't been started;
     */
    public function emailExists() : bool {
        return isset($this->email) &&
                Database::getInstance()->contains("email", $this->email);
    }

    /**
     * Retrieves the user password.
     *
     * @return  string|null
     */
    public function getPassword() : ?string {
        return $this->password;
    }

    /**
     * Specifies the user password.
     *
     * @param  string  $password  The user password.
     *
     * @return  self
     *
     * @throws FieldSizeException
     * If the password size is out of range.
     *
     * @throws FieldFormatException
     * If such value does not comply with the format of this attribute.
     */
    public function setPassword(string $password) : User {
        $value = self::checkSize(
            "password",
            $password,
            self::PASSWORD_MIN_SIZE,
            self::PASSWORD_MAX_SIZE
        );

        if (preg_match(self::PASSWORD_BLACKLIST, $value)) {
            throw new FieldFormatException("password", $value);
        }

        $this->password = $value;
        return $this;
    }

    /**
     * Retrieves the user group.
     *
     * @return string   One of the `GROUP_*` constants.
     */
    public function getGroup() : string {
        return $this->group;
    }

    /**
     * Specifies the user group.
     *
     * @param  string  $group  One of the `GROUP_*` constants.
     *
     * @return  self
     *
     * @throws FieldFormatException
     * If such value does not comply with the format of this attribute.
     */
    public function setGroup(string $group) : User {
        switch ($group) {
            case self::GROUP_USER:
            case self::GROUP_ADMIN: {
                $this->group = $group;
                return $this;
            }
            default: throw new FieldFormatException("group", $group);
        }
    }

    /**
     * Retrieves the user status.
     *
     * @return string   One of the `STATUS_*` constants.
     */
    public function getStatus() : string {
        return $this->status;
    }

    /**
     * Specifies the user status.
     *
     * @param  string  $status  One of the `STATUS_*` constants.
     *
     * @return  self
     *
     * @throws FieldFormatException
     * If such value does not comply with the format of this attribute.
     */
    public function setStatus(string $status) : User {
        switch ($status) {
            case self::STATUS_DISABLED:
            case self::STATUS_ENABLED:
            case self::STATUS_PENDING: {
                $this->status = $status;
                return $this;
            }
            default: throw new FieldFormatException("status", $status);
        }
    }

    /**
     * Retrieves the creation date of user's entry.
     *
     * @return  string
     */
    public function getCreated() : string {
        return $this->created;
    }

    /**
     * Retrieves the last modification date of the user's entry.
     *
     * @return  string
     */
    public function getModified() : string {
        return $this->modified;
    }

    /**
     * Checks whether this user has been logged in.
     *
     * @return  boolean
     * `true`, if authenticated.
     */
    public function isAuthenticated() : bool {
        return $this->authenticated;
    }

    /**
     * Saves the current state of this user into the `$_SESSION` container.
     *
     * Note: The password field is *not* included in the dump.
     *
     * @return void
     */
    public function dumpToSession() {
        $_SESSION["id"           ] = $this->id;
        $_SESSION["name"         ] = $this->name;
        $_SESSION["email"        ] = $this->email;
        $_SESSION["group"        ] = $this->group;
        $_SESSION["status"       ] = $this->status;
        $_SESSION["created"      ] = $this->created;
        $_SESSION["modified"     ] = $this->modified;
        $_SESSION["authenticated"] = $this->authenticated;
    }

    private function saveSession() {
        if (! $this->sessionExists()) {
            return;
        }

        $sql = "REPLACE INTO `sessions` (`id`, `user_id`) VALUES (:sid, :uid)";
        $statement = $this->prepareStatement($sql);

        try {
            $statement->execute(array(":sid" => \session_id(), ":uid" => $this->id));

        } catch (\PDOException $cause) {
            throw new QueryException("session save failure", 0, $cause);
        }
    }

    private function setAttributes(array $row) {
        $this->id       = intval($row["id"], 10);
        $this->name     = $row["name"    ];
        $this->email    = $row["email"   ];
        $this->password = $row["password"];
        $this->group    = $row["group"   ];
        $this->status   = $row["status"  ];
        $this->created  = $row["created" ];
        $this->modified = $row["modified"];
    }

    private function isAuthenticable() : bool {
        return (isset($this->name) && isset($this->password));
    }

    private function isRegistrable() : bool {
        return (isset($this->email) && $this->isAuthenticable());
    }

    private function sessionExists() : bool {
        return (\session_status() === \PHP_SESSION_ACTIVE);
    }

    private function prepareStatement(string $sql) : \PDOStatement {
        return Database::getInstance()->getHandle()->prepare($sql);
    }
}
?>