<?php

namespace Modules\Exam\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Exam\Entities\Tag;
use Modules\Exam\Http\Requests\Backstage\TagRequest;

/**
 * Class TagController
 *
 * @package Modules\Exam\Http\Controllers\Backstage
 */
class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $tags = Tag::query()
            ->when($request->name, function ($query) use ($request) {
                return $query->where('name', 'like', "%{$request->name}%");
            })
            ->paginate(config('modules.paginator.per_page'));

        return view('exam::tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        return view('exam::tags.create_and_edit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TagRequest $request
     *
     * @return mixed
     */
    public function store(TagRequest $request)
    {
        Tag::query()->create(get_request_params($request));

        return $this->redirectRouteWithSuccess('新增标签成功', 'backstage.tags.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Tag $tag
     *
     * @return mixed
     */
    public function edit(Tag $tag)
    {
        return view('exam::tags.create_and_edit', ['item' => $tag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Tag        $tag
     * @param TagRequest $request
     *
     * @return mixed
     */
    public function update(Tag $tag, TagRequest $request)
    {
        $tag->update(get_request_params($request));

        return $this->redirectRouteWithSuccess('编辑成功', 'backstage.tags.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $tag
     *
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return $this->redirectBackWithSuccess('删除标签成功');
    }
}
