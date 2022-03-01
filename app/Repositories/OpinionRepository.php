<?php

namespace App\Repositories;

use App\Models\Opinion;
use App\Repositories\BaseRepository;

/**
 * Class OpinionRepository
 * @package App\Repositories
 * @version January 22, 2022, 9:43 pm UTC
*/

class OpinionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nip',
        'doc_delivery',
        'payment',
        'cooperation',
        'comment',
        'user_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Opinion::class;
    }
}
