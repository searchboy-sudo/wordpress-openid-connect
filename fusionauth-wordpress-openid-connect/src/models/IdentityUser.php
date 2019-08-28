<?php


namespace fusionauth\openidconnect\src\models;


class IdentityUser
{

    private $sub;
    private $email;
    private $family_name;
    private $given_name;
    private $roles = array();

    public function __construct(){

    }

    public function populateFields(Array $properties){
        foreach($properties as $key => $value){
            $this->{$key} = $value;
        }
    }

    function setId($id){
        $this->sub = $id;
    }

    function getId(){
        return $this->sub;
    }

    function setEmail($email){
        $this->email = $email;
    }

    function getEmail(){
        return $this->email;
    }

    function setLastName($lastName){
        $this->family_name = $lastName;
    }

    function getLastName(){
        return $this->family_name;
    }

    function setFirstName($firstName){
        $this->given_name = $firstName;
    }

    function getFirstName(){
        return $this->given_name;
    }

    function setRoles($roles){
        $this->roles = $roles;
    }

    function getRoles(){
        return $this->roles;
    }
}