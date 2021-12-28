<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Repositories\TodoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Flash;
use Response;

class TodoController extends AppBaseController
{
    /** @var  TodoRepository */
    private $todoRepository;

    public function __construct(TodoRepository $todoRepo)
    {
        $this->todoRepository = $todoRepo;
    }

    /**
     * Display a listing of the Todo.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $queryText = $request->input('queryText');
        $status = $request->input('status');
        $sort = $request->input('sort');
        $todos = $this->todoRepository->search($queryText, $status, $sort);


        return view('todos.index', compact('queryText','status','sort', 'todos'));
    }

    /**
     * Show the form for creating a new Todo.
     *
     * @return Response
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created Todo in storage.
     *
     * @param CreateTodoRequest $request
     *
     * @return Response
     */
    public function store(CreateTodoRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();

        $todo = $this->todoRepository->create($input);

        Flash::success('Todo saved successfully.');

        return redirect(route('todos.index'));
    }

    /**
     * Display the specified Todo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            Flash::error('Todo not found');

            return redirect(route('todos.index'));
        }

        return view('todos.show')->with('todo', $todo);
    }

    /**
     * Show the form for editing the specified Todo.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            Flash::error('Todo not found');

            return redirect(route('todos.index'));
        }

        return view('todos.edit')->with('todo', $todo);
    }

    /**
     * Update the specified Todo in storage.
     *
     * @param int $id
     * @param UpdateTodoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTodoRequest $request)
    {
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            Flash::error('Todo not found');

            return redirect(route('todos.index'));
        }

        $todo = $this->todoRepository->update($request->all(), $id);

        Flash::success('Todo updated successfully.');

        return redirect(route('todos.index'));
    }

    /**
     * Remove the specified Todo from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $todo = $this->todoRepository->find($id);

        if (empty($todo)) {
            Flash::error('Todo not found');

            return redirect(route('todos.index'));
        }

        $this->todoRepository->delete($id);

        Flash::success('Todo deleted successfully.');

        return redirect(route('todos.index'));
    }
}
