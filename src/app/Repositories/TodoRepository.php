<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;


/**
 * Class TodoRepository
 * @package App\Repositories
 * @version December 28, 2021, 3:04 pm JST
*/

class TodoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'title',
        'status'
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
        return Todo::class;
    }
    public function search($sort)
    {
        $query = Todo::where('user_id', Auth::id());
        if ($sort === 'titleAsc') $query->orderBy('title', 'asc');
        if ($sort === 'titleDesc') $query->orderBy('title', 'desc');
        if ($sort === 'statusAsc') $query->orderBy('status', 'asc');
        if ($sort === 'statusDesc') $query->orderBy('status', 'desc');
        $todos = $query->get();
        return $todos;
    }
}
