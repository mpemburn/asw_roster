<?php
namespace Repository;

/**
 * A simple interface to set the methods in our Member repository
 */
interface MemberInterface {
    public function getMemberById($member_id);

    public function getMemberByName($member_name);

}