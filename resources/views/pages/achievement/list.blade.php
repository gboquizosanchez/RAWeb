@php
    use App\Platform\Enums\AchievementFlag;
@endphp

<x-app-layout pageTitle="Achievement List{{ $requestedConsole }}">
    <div class="navpath">
        @if ($requestedConsole == "")
            <b>Achievement List</b>
        @endif
    </div>
    <div class="detaillist">
        <h3>
            @if ($dev !== null)
                <a href={{ "/user/$dev" }}>{{ $dev }}</a>
            @endif
            Achievement List
        </h3>

        <div class="flex flex-wrap justify-between">
            <div>
                @if ($params !== AchievementFlag::OfficialCore)
                    {{-- @todo Route needed here --}}
                    <a href='/achievements?s={{ $sortBy }}&p={{ AchievementFlag::OfficialCore }}{{ $dev_param }}'>
                        Achievements in Core Sets
                    </a>
                @else
                    <b>Achievements in Core Sets</b>
                @endif
                <br>
                @if ($user !== null)
                    @if ($params !== AchievementFlag::Unofficial)
                        <a href='/achievements?s={{ $sortBy }}&p={{ AchievementFlag::Unofficial }}{{ $dev_param }}'>
                            Achievements in Unofficial Sets
                        </a>
                    @else
                        <b>Achievements in Unofficial Sets</b>
                    @endif
                    <br>
                    @if ($params !== 1)
                        <a href='/achievements?s={{ $sortBy }}&p=1{{ $dev_param }}'>
                            My Unlocked Achievements
                        </a>
                    @else
                        <b>My Unlocked Achievements</b>
                    @endif
                    <br>
                    {{-- @if ($params !== 2) --}}
                    {{--    <a href='/achievements?s={{ $sortBy }}&p=2{{ $dev_param }}'> --}}
                    {{--        Achievements I haven't yet unlocked --}}
                    {{--    </a> --}}
                    {{-- @else --}}
                    {{--    <b>Achievements I haven't yet unlocked</b> --}}
                    {{-- @endif --}}
                    {{-- <br> --}}
                @endif
            </div>
            @if ($user !== null)
                <div>
                    Filter by developer:<br>
                    <form action="/achievements">
                        <input type="hidden" name="s" value="{{ $sortBy }}">
                        <input type="hidden" name="p" value="{{ $params }}">
                        <input size="28" name="d" type='text' value="{{ $dev }}">
                        &nbsp;<button class='btn'>Select</button>
                    </form>
                </div>
            @endif
        </div>
        <div class="float-right">* = ordered by</div>
        <br style="clear:both;">
        <div class="table-wrapper">
            <table class="table-highlight">
                <tbody>
                @php
                    // @todo: Refactor needed
                    $sort1 = ($sortBy == 1) ? 11 : 1;
                    $sort2 = ($sortBy == 2) ? 12 : 2;
                    $sort3 = ($sortBy == 13) ? 3 : 13;
                    $sort4 = ($sortBy == 4) ? 14 : 4;
                    $sort5 = ($sortBy == 5) ? 15 : 5;
                    $sort6 = ($sortBy == 6) ? 16 : 6;
                    $sort7 = ($sortBy == 17) ? 7 : 17;
                    $sort8 = ($sortBy == 18) ? 8 : 18;
                    $sort9 = ($sortBy == 19) ? 9 : 19;

                    $mark1 = ($sortBy % 10 == 1) ? '&nbsp;*' : '';
                    $mark2 = ($sortBy % 10 == 2) ? '&nbsp;*' : '';
                    $mark3 = ($sortBy % 10 == 3) ? '&nbsp;*' : '';
                    $mark4 = ($sortBy % 10 == 4) ? '&nbsp;*' : '';
                    $mark5 = ($sortBy % 10 == 5) ? '&nbsp;*' : '';
                    $mark6 = ($sortBy % 10 == 6) ? '&nbsp;*' : '';
                    $mark7 = ($sortBy % 10 == 7) ? '&nbsp;*' : '';
                    $mark8 = ($sortBy % 10 == 8) ? '&nbsp;*' : '';
                    $mark9 = ($sortBy % 10 == 9) ? '&nbsp;*' : '';
                @endphp
                <tr class="do-not-highlight">
                    <th class="pr-0"></th>
                    <th>
                        <a href="/achievements?s={{ $sort1 }}&p={{ $params }}{{ $dev_param }}">Title</a>{!! $mark1 !!}
                        /
                        <a href="/achievements?s={{ $sort2 }}&p={{ $params }}{{ $dev_param }}">Description</a>{!! $mark2 !!}
                    </th>
                    @if (! $mobileBrowser)
                        <th class='whitespace-nowrap'>
                            <a href="/achievements?s={{ $sort3 }}&p={{ $params }}{{ $dev_param }}">Points</a>{!! $mark3 !!}
                            <br>
                            <span class="TrueRatio">
                                (<a href='/achievements?s={{ $sort4 }}&p={{ $params }}{{ $dev_param }}'>RetroPoints</a>{!! $mark4 !!})
                            </span>
                        </th>
                        <th>
                            <a href="/achievements?s={{ $sort5 }}&p={{ $params }}{{ $dev_param }}">Author</a>{!! $mark5 !!}
                        </th>
                    @endif
                    <th>
                        <a href="/achievements?s={{ $sort6 }}&p={{ $params }}{{ $dev_param }}">Game</a>{!! $mark6 !!}
                    </th>
                    @if (! $isOwnEarnedAchievementsList)
                        <th>
                            <a href="/achievements?s={{ $sort7 }}&p={{ $params }}{{ $dev_param }}">Added</a>{!! $mark7 !!}
                        </th>
                        @if (! $mobileBrowser)
                            <th>
                                <a href="/achievements?s={{ $sort8 }}&p={{ $params }}{{ $dev_param }}">Modified</a>{!! $mark8 !!}
                            </th>
                        @else
                            <th>
                                <a href="/achievements?s={{ $sort9 }}&p={{ $params }}{{ $dev_param }}">Awarded</a>{!! $mark9 !!}
                            </th>
                        @endif
                    @endif
                </tr>
                @foreach ($achData as $achEntry)
                    @php
                        $achTitle = $achEntry['AchievementTitle'];
                        $achDesc = $achEntry['Description'];
                        $achPoints = $achEntry['Points'];
                        $achTruePoints = $achEntry['TrueRatio'];
                        $achAuthor = $achEntry['Author'];
                        $achDateCreated = $achEntry['DateCreated'];
                        $achDateModified = $achEntry['DateModified'];
                        $gameTitle = $achEntry['GameTitle'];
                        $consoleName = $achEntry['ConsoleName'];
                        $achAwardedDate = $achEntry['AwardedDate'] ?? "";

                        sanitize_outputs(
                            $achTitle,
                            $achDesc,
                            $achAuthor,
                            $gameTitle,
                            $consoleName
                        );
                    @endphp
                    <tr>
                        <td class="pr-0">
                            {!! achievementAvatar($achEntry, label: false) !!}
                        </td>
                        <td class="w-full xl:w-[50%]">
                            {!! achievementAvatar($achEntry, icon: false) !!}
                            <br>{{ $achDesc }}
                        </td>
                        @if (! $mobileBrowser)
                            <td>
                                {{ $achPoints }}
                                <x-points-weighted-container>{{ localized_number($achTruePoints) }}</x-points-weighted-container>
                            </td>
                            <td>
                                {!! userAvatar($achAuthor, label: false) !!}
                            </td>
                        @endif
                        <td>
                            {!! gameAvatar($achEntry, label: false) !!}
                        </td>
                        @if (! $isOwnEarnedAchievementsList)
                            <td>
                                <span class="smalldate">{{ getNiceDate(strtotime($achDateCreated)) }}</span>
                            </td>
                            @if (! $mobileBrowser)
                                <td>
                                    <span class="smalldate">{{ getNiceDate(strtotime($achDateModified)) }}</span>
                                </td>
                            @endif
                        @else
                            @php
                                $renderAwardedDate = "Unknown";
                            @endphp
                            @if (strlen($achAwardedDate) > 0)
                                @php
                                    $renderAwardedDate = getNiceDate(strtotime($achAwardedDate))
                                @endphp
                                <td>
                                    <span class="smalldate">{{ $renderAwardedDate }}</span>
                                </td>
                            @endif
                            <td>
                                <span class="smalldate">{{ getNiceDate(strtotime($achAwardedDate)) }}</span>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-right">
            @if ($offset > 0)
                @php
                    $prevOffset = $offset - $maxCount;
                @endphp
                <a href='/achievements?s={{ $sortBy }}&o={{ $prevOffset }}&p={{ $params }}{{ $dev_param }}'>&lt; Previous {{ $maxCount }}</a> -
            @endif
            @if ($achData->count() === $maxCount)
                {{-- Max number fetched, i.e. there are more. Can goto next 25. --}}
                @php
                    $nextOffset = $offset + $maxCount;
                @endphp
                <a href='/achievements?s={{ $sortBy }}&o={{ $nextOffset }}&p={{ $params }}{{ $dev_param }}'>Next {{ $maxCount }} &gt;</a>
            @endif
        </div>
    </div>
</x-app-layout>
