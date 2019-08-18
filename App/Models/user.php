<?php

class User 
{
    protected $userId, $title, $forename, $middleName1, $middleName2, $lastName, $username, $passHash, $age, $gender, $dob, $role;
    
    // Constructor
    function __construct() {}
    
    // Populate user from database, if username or user ID known and user record present in db
    function populateFromDb()
    {
        if (isset($this->username))
        {
            // Connect to db and populate fields
            require '../app/config.php';

            if ($query = $conn->prepare("SELECT UserId, Title, Forename, MiddleName1, MiddleName2, LastName, Age, Gender, DOB, Role FROM User WHERE Username = ?"))
            {
                $query->bind_param("s", $this->username);
                $query->execute();
                $result = $query->get_result();
                
                while ($row = $result->fetch_row())
                {
                    $this->userId = $row['0'];
                    $this->title = $row['1'];
                    $this->forename = $row['2'];
                    $this->middleName1 = $row['3'];
                    $this->middleName2 = $row['4'];
                    $this->lastName = $row['5'];
                    $this->age = $row['6'];
                    $this->gender = $row['7'];
                    $this->dob = $row['8'];
                    $this->role = $row['9'];
                }

            } else
            {
                die('Error: populateFromDb : Could not prepare MySQLi statement');
            }

        } else if (isset($this->userId))
        {
            require '../app/config.php';

            if ($query = $conn->prepare("SELECT UserId, Title, Forename, MiddleName1, MiddleName2, LastName, Age, Gender, DOB, Role FROM User WHERE UserId = ?"))
            {
                $query->bind_param("s", $this->userId);
                $query->execute();
                $result = $query->get_result();

                while ($row = $result->fetch_row())
                {
                    $this->userId = $row['0'];
                    $this->title = $row['1'];
                    $this->forename = $row['2'];
                    $this->middleName1 = $row['3'];
                    $this->middleName2 = $row['4'];
                    $this->lastName = $row['5'];
                    $this->age = $row['6'];
                    $this->gender = $row['7'];
                    $this->dob = $row['8'];
                    $this->role = $row['9'];
                }
            } 
            else
            {
                die('Error: Could not prepare MySQLi statement');
            }
        }
    }

    // ------ THE GETTERS ------
    // Get name
    public function getName()
    {
        return $this->title .' '. $this->forename .' '. $this->middleName1 .' '. $this->middleName2 .' '. $this->lastName;
    }
    // Get username
    public function getUserName()
    {
        return $this->username;
    }
    // Get Id
    public function getId()
    {
        return $this->userId;
    }
    //Shouldn't need this
    public function getPassHash()
    {
        return $this->passHash;
    }
    // Get title
    public function getTitle()
    {
        return $this->title;
    }
    // Get forename
    public function getForeName()
    {
        return $this->forename;
    }
    // Get first middle name
    public function getMiddleName1()
    {
        return $this->middleName1;
    }
    // Get second middle name 
    public function getMiddleName2()
    {
        return $this->middleName2;
    }
    // Get last name
    public function getLastName()
    {
        return $this->lastName;
    }
    // Get age
    public function getAge()
    {
        return $this->age;
    }
    // Get gender
    public function getGender()
    {
        return $this->gender;
    }
    // Get date of birth
    public function getDob()
    {
        return $this->dob;
    }
    // Get role
    public function getRole()
    {
        return $this->role;
    }
    
    // ------  THE SETTERS ------
    // Set UserId
    public function setId($userId)
    {
        $this->userId = $userId;
    }
    // Set username
    public function setUsername($username)
    {
        $this->username = $username;
    }
    // Set Passhash
    public function setPassHash($passHash)
    {
        $this->passHash = $passHash;
    }
    // Set role
    public function setRole($role)
    {
        $this->role = $role;
    }
    // Set title
    public function setTitle($title)
    {
        $this->title = $title;
    }
    // Calculate title (for students, where there can only be two titles) - gender must be set
    public function calcTitle()
    {
        switch ($this->gender)
        {
            case 'M':
                $this->setTitle("MR");
                break;
            case 'F':
                $this->setTitle("MS");
                break;
        }
    }
    // Set forename
    public function setForeName($forename)
    {
        $this->forename = $forename;
    }
    // Set first middle name
    public function setMiddleName1($middleName1)
    {
        $this->middleName1 = $middleName1;
    }
    // Set second middle name 
    public function setMiddleName2($middleName2)
    {
        $this->middleName2 = $middleName2;
    }
    // Set last name
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    // Set age
    public function setAge($age)
    {
        $this->age = $age;
    }
    // Set gender
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
    // Set date of birth
    public function setDob($dob)
    {
        $this->dob = $dob;
    }
    // Generate username by concatenating first name and surname
    public function genUsername()
    {
        $this->username = $this->getForeName() . '.' . $this->getLastName();
    }
    // Calculate age from dob
    public function calcAge()
    {
        $now = new DateTime();
        $dob = new DateTime($this->dob);
        $this->age = $now->diff($dob)->y;
    }

    // Add User record to DB - oinly works once all the mandatory params have been populated
    function addUserToDB()
    {
        // Adds record to 'User' table in db
        require_once '../app/config.php';

            if ($insQuery = $conn->prepare("INSERT INTO User (Title, Forename, MiddleName1, MiddleName2, LastName, Username, PassHash, Age, Gender, DOB, Role) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"))
            {   
                // Check to see if record already exists
                $queryId = $conn->prepare("SELECT * from User WHERE Username = ? AND DOB = ?");
                $queryId->bind_param("ss", $this->username, $this->dob);
                $queryId->execute();
                $queryId->store_result();
                if ($queryId->num_rows === 0)
                {   
                    // No duplicate found, so bind the properties to the query and fire away
                    $insQuery->bind_param("sssssssisss", $this->title, $this->forename, $this->middleName1, $this->middleName2, $this->lastName, $this->username, $this->passHash, $this->age, $this->gender, $this->dob, $this->role);
                    $insQuery->execute();

                } 
                else
                {
                    die('Error: addToDB : Duplicate entry. Record already exists.');
                }
            }
            else
            {
                die('Error: addToDB : Could not prepare MySQLi statement');
            }

    }
    // Function to add the user id and adress id in the UserAddress bridging tabe
    function addUserAddressToDB($addressId)
    {
        require '../app/config.php';

        $userId = $this->getId();

        if ($insQuery = $conn->prepare("INSERT INTO UserAddress (UserId, AddressId) VALUES (?, ?)"))
        {   
            // Check to see if record already exists
            $queryId = $conn->prepare("SELECT * from UserAddress WHERE UserId = ? AND AddressId = ?");
            $queryId->bind_param("is", $userId, $addressId);
            $queryId->execute();
            $queryId->store_result();
            if ($queryId->num_rows === 0)
            {   
                // No duplicate found, so bind the properties to the query and fire away
                $insQuery->bind_param("is", $userId, $addressId);
                $insQuery->execute();
            } 
            else
            {
                die('Error: addToDB : Duplicate entry. Record already exists.');
            }
        }
        else
        {
            die('Error: addToDB : Could not prepare MySQLi statement');
        }
    }

    function addUserContToDB($emailId, $hPhoneId, $mPhoneId)
    {
        require '../app/config.php';

        $userId = $this->getId();
        // Email
        if ($userEmailQuery = $conn->prepare("INSERT INTO UserEmails (UserId, MailId) VALUES (?, ?)"))
        {   
            // Check to see if record already exists
            $queryId = $conn->prepare("SELECT * from UserEmails WHERE UserId = ? AND MailId = ?");
            $queryId->bind_param("ii", $userId, $emailId);
            $queryId->execute();
            $queryId->store_result();
            if ($queryId->num_rows === 0)
            {   
                // No duplicate found, so bind the properties to the query and fire away
                $userEmailQuery->bind_param("ii", $userId, $emailId);
                $userEmailQuery->execute();
            } 
            else
            {
                die('Error: addUserContToDB : Email : Duplicate entry. Record already exists.');
            }
        }
        else
        {
            die('Error: addUserContToDB : Email : Could not prepare MySQLi statement');
        }
        // Home Telephone
        if ($userHPhoneQuery = $conn->prepare("INSERT INTO UserPhones (UserId, PhoneId) VALUES (?, ?)"))
        {   
            // Check to see if record already exists
            $queryId = $conn->prepare("SELECT * from UserPhones WHERE UserId = ? AND PhoneId = ?");
            $queryId->bind_param("ii", $userId, $hPhoneId);
            $queryId->execute();
            $queryId->store_result();
            if ($queryId->num_rows === 0)
            {   
                // No duplicate found, so bind the properties to the query and fire away
                $userHPhoneQuery->bind_param("ii", $userId, $hPhoneId);
                $userHPhoneQuery->execute();
            } 
            else
            {
                die('Error: addUserContToDB : Home Telephone : Duplicate entry. Record already exists.');
            }
        }
        else
        {
            die('Error: addUserContToDB : Home Telephone : Could not prepare MySQLi statement');
        }
        // Mobile Phone
        if ($userMPhoneQuery = $conn->prepare("INSERT INTO UserPhones (UserId, PhoneId) VALUES (?, ?)"))
        {   
            // Check to see if record already exists
            $queryId = $conn->prepare("SELECT * from UserPhones WHERE UserId = ? AND PhoneId = ?");
            $queryId->bind_param("ii", $userId, $mPhoneId);
            $queryId->execute();
            $queryId->store_result();
            if ($queryId->num_rows === 0)
            {   
                // No duplicate found, so bind the properties to the query and fire away
                $userMPhoneQuery->bind_param("ii", $userId, $mPhoneId);
                $userMPhoneQuery->execute();
            } 
            else
            {
                die('Error: addUserContToDB : Home Telephone : Duplicate entry. Record already exists.');
            }
        }
        else
        {
            die('Error: addUserContToDB : Home Telephone : Could not prepare MySQLi statement');
        }
    }

    protected function authenticate()
    {
        // Check if username & pass hash are valid - record exists in the db & values passed are correct
        require_once '../app/config.php';
        // Create Query
        if ($query = $conn->prepare("SELECT * from User WHERE Username = ? AND PassHash = ?"))
        {
            $query->bind_param("ss", $this->username, $this->passHash);
            $query->execute();
            $query->store_result();

            // Count rows returned
            if($query->num_rows > 0)
            {
                // Nicely done, record found
                //var_dump($query);
                $query->close();
                return true;
            }
            else
            {
                // No records found
                $query->close();
                return false;
            }
        }
        else
        {
            die('Error: authenticate : Could not prepare MySQLi statement');
        }
    }

    public function logIn()
    {
        $auth = false;
        if ($this->authenticate())
        {
            $auth = true;
            // Sanitize the $_SESSION array if just logged in before adding new values
            $_SESSION = array();
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['isAuth'] = true;
            $_SESSION['user'] = $this->username;
            $_SESSION['passHash'] = $this->passHash;
        }
        return $auth;
    }
}