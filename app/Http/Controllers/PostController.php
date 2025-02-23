<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    protected $postRepository;

    public function __construct(PostRepository $postRepository) {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = $this->postRepository->getAll();
        return response()->success($posts, __('Posts retrieved successfully'));
    }

    /**
     * Store a newly created post in database.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'text' => 'string'
            ]);
            $data['user_id'] = auth()->id();

            $post = $this->postRepository->create($data);
            return response()->success($post, __('Post created successfully'), 201);
        }
        catch (ValidationException $e) {
            return response()->error($e->getMessage(), 400, $e->errors());
        } catch (\Exception $e) {
            return response()->error(__('Something went wrong'), 500);
        }
    }

    /**
     * Display the specified post.
     */
    public function show(int $id)
    {
        $post = $this->postRepository->findById($id);
        if (!$post) {
            return response()->error(__('Post not found'), 404);
        }

        return response()->success($post, __('Post retrieved successfully'));
    }

    /**
     * Update the specified post in database.
     */
    public function update(Request $request, int $id)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'text' => 'string'
            ]);

            $post = $this->postRepository->findById($id);
            if (!$post) {
                return response()->error(__('Post not found'), 404);
            }

            $post = $this->postRepository->update($id, $data);
            return response()->success($post, __('Post updated successfully'));
        }
        catch (ValidationException $e) {
            return response()->error($e->getMessage(), 400, $e->errors());
        } catch (\Exception $e) {
            return response()->error(__('Something went wrong'), 500);
        }
    }

    /**
     * Remove the specified post from database.
     */
    public function destroy(int $id)
    {
        $post = $this->postRepository->findById($id);
        if (!$post) {
            return response()->error(__('Post not found'), 404);
        }

        $this->postRepository->delete($id);
        return response()->success(null, __('Post deleted successfully'));
    }
}
