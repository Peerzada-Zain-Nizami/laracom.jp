<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Reference;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    function highlight($text, $query)
    {
        return preg_replace("/($query)/i", '<b>$1</b>', $text);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if (!$query) {
            return response()->json(['message' => 'Query is required'], 400);
        }

        // Search and highlight in Tutorials
        $tutorials = Tutorial::where('title', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->get()
            ->map(function ($tutorial) use ($query) {
                $tutorial->title = $this->highlight($tutorial->title, $query);
                $tutorial->content = $this->highlight($tutorial->content, $query);
                return $tutorial;
            });

        // Search and highlight in References
        $references = Reference::where('title', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->get()
            ->map(function ($reference) use ($query) {
                $reference->title = $this->highlight($reference->title, $query);
                $reference->content = $this->highlight($reference->content, $query);
                return $reference;
            });

        // Search and highlight in Blog Posts
        $blogPosts = BlogPost::where('title', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->get()
            ->map(function ($blogPost) use ($query) {
                $blogPost->title = $this->highlight($blogPost->title, $query);
                $blogPost->content = $this->highlight($blogPost->content, $query);
                return $blogPost;
            });

        return response()->json([
            'tutorials' => $tutorials,
            'references' => $references,
            'blog_posts' => $blogPosts,
        ]);
    }

}
