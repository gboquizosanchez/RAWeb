<?php

declare(strict_types=1);

namespace App\Community;

use App\Community\Commands\SyncComments;
use App\Community\Commands\SyncForumCategories;
use App\Community\Commands\SyncForums;
use App\Community\Commands\SyncForumTopics;
use App\Community\Commands\SyncMessages;
use App\Community\Commands\SyncNews;
use App\Community\Commands\SyncRatings;
use App\Community\Commands\SyncTickets;
use App\Community\Commands\SyncUserRelations;
use App\Community\Commands\SyncVotes;
use App\Community\Components\AchievementComments;
use App\Community\Components\ForumTopicComments;
use App\Community\Components\ForumTopics;
use App\Community\Components\GameComments;
use App\Community\Components\MessageIcon;
use App\Community\Components\NewsCarousel;
use App\Community\Components\NewsComments;
use App\Community\Components\NewsGrid;
use App\Community\Components\NewsTeaser;
use App\Community\Components\UserActivityFeed;
use App\Community\Components\UserComments;
use App\Community\Models\AchievementComment;
use App\Community\Models\Comment;
use App\Community\Models\Forum;
use App\Community\Models\ForumCategory;
use App\Community\Models\ForumTopic;
use App\Community\Models\ForumTopicComment;
use App\Community\Models\GameComment;
use App\Community\Models\Message;
use App\Community\Models\News;
use App\Community\Models\NewsComment;
use App\Community\Models\Rating;
use App\Community\Models\Ticket;
use App\Community\Models\TriggerTicket;
use App\Community\Models\UserActivity;
use App\Community\Models\UserComment;
use App\Community\Models\UserRelation;
use App\Community\Models\Vote;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SyncComments::class,
                SyncForumCategories::class,
                SyncForums::class,
                SyncForumTopics::class,
                SyncMessages::class,
                SyncNews::class,
                SyncRatings::class,
                SyncTickets::class,
                SyncUserRelations::class,
                SyncVotes::class,
            ]);
        }

        $this->loadMigrationsFrom([database_path('migrations/community')]);

        Relation::morphMap([
            'forum' => Forum::class,
            'forum-category' => ForumCategory::class,
            'forum-topic' => ForumTopic::class,

            'comment' => Comment::class,
            'achievement.comment' => AchievementComment::class,
            'forum-topic.comment' => ForumTopicComment::class,
            'game.comment' => GameComment::class,
            'news.comment' => NewsComment::class,
            'user.comment' => UserComment::class,

            'ticket' => Ticket::class,
            'trigger.ticket' => TriggerTicket::class,

            'user-activity' => UserActivity::class,
            'user-relation' => UserRelation::class,

            'message' => Message::class,
            'news' => News::class,
            'rating' => Rating::class,
            'vote' => Vote::class,
        ]);

        // Livewire::component('forum-topics', ForumTopics::class);
        //
        // Livewire::component('achievement.comments', AchievementComments::class);
        // Livewire::component('forum-topic.comments', ForumTopicComments::class);
        // Livewire::component('game.comments', GameComments::class);
        // Livewire::component('news.comments', NewsComments::class);
        // Livewire::component('user.comments', UserComments::class);
        //
        // Livewire::component('message-icon', MessageIcon::class);
        // Blade::component('news-carousel', NewsCarousel::class);
        // Livewire::component('news-grid', NewsGrid::class);
        // Livewire::component('news-teaser', NewsTeaser::class);
        // Livewire::component('user-activity-feed', UserActivityFeed::class);
    }
}