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

    public function getMemberById($member_id)
    {
        return $this->convertFormat($this->pokemonModel->find($pokemonId));
    }

    public function getMemberByName($member_name)
    {
        // Search by name
        $pokemon = $this->pokemonModel->where('name', strtolower($pokemonName));

        if ($pokemon)
        {
            // Return first found row
            return $this->convertFormat($pokemon->first());
        }

        return null;
    }
    
    /**
     * Converting the Eloquent object to a standard format
     *
     * @param mixed $pokemon
     * @return stdClass
     */
    protected function convertFormat($pokemon)
    {
        if ($pokemon == null)
        {
            return null;
        }

        $object = new stdClass();
        $object->id = $pokemon->id;
        $object->name = $pokemon->name;

        return $object;
    }
}