<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageBlock;
use App\Services\LandingPageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockApiController extends Controller
{
    use AuthorizesRequests;
    public function tree(LandingPage $landingPage): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $blocks = $landingPage->getBlocksTree();

        return response()->json(['blocks' => $blocks]);
    }

    public function show(LandingPage $landingPage, int $block): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $blockModel = LandingPageBlock::where('id', $block)
            ->where('landing_page_id', $landingPage->id)
            ->first();

        if (!$blockModel) {
            return response()->json(['error' => 'Block not found'], 404);
        }

        return response()->json(['block' => $blockModel]);
    }

    public function store(Request $request, LandingPage $landingPage, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $validated = $request->validate([
            'type' => 'required|string|in:section,column,widget',
            'component' => 'required|string',
            'parent_id' => 'nullable|exists:landing_page_blocks,id',
            'content' => 'nullable|array',
            'styles' => 'nullable|array',
        ]);

        $block = $service->addBlock($landingPage, $validated, $validated['parent_id'] ?? null);

        return response()->json(['success' => true, 'block' => $block]);
    }

    public function update(Request $request, LandingPage $landingPage, int $block, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $blockModel = LandingPageBlock::where('id', $block)
            ->where('landing_page_id', $landingPage->id)
            ->first();

        if (!$blockModel) {
            return response()->json(['error' => 'Block not found'], 404);
        }

        $validated = $request->validate([
            'content' => 'nullable|array',
            'styles' => 'nullable|array',
            'is_visible' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $blockModel = $service->updateBlock($blockModel, $validated);

        return response()->json(['success' => true, 'block' => $blockModel]);
    }

    public function destroy(LandingPage $landingPage, int $block, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $blockModel = LandingPageBlock::where('id', $block)
            ->where('landing_page_id', $landingPage->id)
            ->first();

        if (!$blockModel) {
            return response()->json(['error' => 'Block not found'], 404);
        }

        $service->deleteBlock($blockModel);

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request, LandingPage $landingPage, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:landing_page_blocks,id',
            'parent_id' => 'nullable|exists:landing_page_blocks,id',
        ]);

        $service->reorderBlocks($landingPage, $validated['order'], $validated['parent_id'] ?? null);

        return response()->json(['success' => true]);
    }

    public function duplicate(LandingPage $landingPage, int $block, LandingPageService $service): JsonResponse
    {
        $this->authorize('update', $landingPage);

        $blockModel = LandingPageBlock::where('id', $block)
            ->where('landing_page_id', $landingPage->id)
            ->first();

        if (!$blockModel) {
            return response()->json(['error' => 'Block not found'], 404);
        }

        $newBlock = $service->duplicateBlockById($blockModel);

        return response()->json(['success' => true, 'block' => $newBlock]);
    }
}
