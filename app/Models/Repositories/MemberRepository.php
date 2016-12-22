<?php
namespace Repository;

use Illuminate\Database\Eloquent\Model;
use \stdClass;

/**
 * Member repository, containing commonly used queries
 */
class MemberRepository implements MemberInterface
{
    // Our Eloquent member model
    protected $memberModel;

    /**
     * Setting our class $memberModel to the injected model
     *
     * @param Model $member
     * @return MemberRepository
     */
    public function __construct(Model $member)
    {
        $this->memberModel = $member;
    }

    /**
     * Returns the member object associated with the passed id
     *
     * @param mixed $memberId
     * @return Model
     */
    public function getMemberById($member_id)
    {
        return $this->convertFormat($this->memberModel->find($member_id));
    }

    /**
     * Returns the member object associated with the memberName
     *
     * @param string $memberName
     */
    public function getMemberByName($member_name)
    {
        // Search by name
        $member = $this->memberModel->where('name', strtolower($member_name));

        if ($member)
        {
            // Return first found row
            return $this->convertFormat($member->first());
        }

        return null;
    }

    /**
     * Converting the Eloquent object to a standard format
     *
     * @param mixed $member
     * @return stdClass
     */
    protected function convertFormat($member)
    {
        if ($member == null)
        {
            return null;
        }

        $object = new stdClass();
        $object->id = $member->id;
        $object->name = $member->name;

        return $object;
    }
}