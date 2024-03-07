<?php

declare(strict_types=1);

namespace App\Platform\Controllers;

use App\Http\Controller;
use App\Models\Achievement;
use App\Models\System;
use App\Models\User;
use App\Platform\Requests\AchievementRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    protected function resourceName(): string
    {
        return 'achievement';
    }

    public function index(): View
    {
        $this->authorize('viewAny', $this->resourceClass());

        return view('resource.index')
            ->with('resource', $this->resourceName());
    }

    public function show(Achievement $achievement, ?string $slug = null): View|RedirectResponse
    {
        $this->authorize('view', $achievement);

        if (!$this->resolvesToSlug($achievement->slug, $slug)) {
            return redirect($achievement->canonicalUrl);
        }

        $achievement->loadMissing([
            'game',
            'user',
        ]);

        return view($this->resourceName() . '.show')->with('achievement', $achievement);
    }

    public function edit(Achievement $achievement): View
    {
        $this->authorize('update', $achievement);

        $achievement->load([
            'game' => function ($query) {
                // $query->with('memoryNotes');
            },
            'user',
        ]);

        return view($this->resourceName() . '.edit')->with('achievement', $achievement);
    }

    public function update(AchievementRequest $request, Achievement $achievement): RedirectResponse
    {
        $this->authorize('update', $achievement);

        $achievement->fill($request->validated())->save();

        return back()->with('success', $this->resourceActionSuccessMessage('achievement', 'update'));
    }

    public function achievements(Request $request): View
    {
        $consoleList = System::pluck('Name', 'ID');
        $consoleIDInput = (int) $request->input('z', 0);
        $mobileBrowser = IsMobileBrowser();

        authenticateFromCookie($user, $permissions, $userDetails);

        $userModel = $user ? User::firstWhere('User', $user) : null;

        $maxCount = 25;

        $count = (int) $request->input('c', $maxCount);
        $offset = (int) $request->input('o', 0);
        $params = (int) $request->input('p', 3);
        $dev = requestInputSanitized('d');

        if ($user == null) {
            $params = 3;
        }
        $flags = match ($params) {
            5 => 5,
            default => 3,
        };

        $dev_param = null;
        if ($dev != null) {
            $dev_param .= "&d=$dev";
        }

        $sortBy = (int) $request->input('s', 17);
        $achData = getAchievementsList($userModel, $sortBy, $params, $count, $offset, $flags, $dev);

        // Is the user looking at their own achievements list?
        $isOwnEarnedAchievementsList = $user !== null && $params === 1;

        $requestedConsole = '';
        if ($consoleIDInput !== 0) {
            $requestedConsole = ' ' . $consoleList[$consoleIDInput];
        }

        return view('pages.achievement.list', [
            'requestedConsole' => $requestedConsole,
            'dev' => $dev,
            'params' => $params,
            'user' => $userModel,
            'sortBy' => $sortBy,
            'dev_param' => $dev_param,
            'mobileBrowser' => $mobileBrowser,
            'isOwnEarnedAchievementsList' => $isOwnEarnedAchievementsList,
            'achData' => $achData,
            'offset' => $offset,
            'maxCount' => $maxCount,
        ]);
    }
}
