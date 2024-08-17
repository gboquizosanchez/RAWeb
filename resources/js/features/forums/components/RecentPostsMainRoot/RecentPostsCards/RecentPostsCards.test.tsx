import { createPaginatedData } from '@/common/models';
import { createRecentActiveForumTopic } from '@/features/forums/models';
import { render, screen } from '@/test';

import { RecentPostsCards } from './RecentPostsCards';

describe('Component: RecentPostsCards', () => {
  it('renders without crashing', () => {
    // ARRANGE
    const { container } = render(<RecentPostsCards />, {
      pageProps: {
        paginatedTopics: createPaginatedData([]),
      },
    });

    // ASSERT
    expect(container).toBeTruthy();
  });

  it('renders a card for every given recent forum post', () => {
    // ARRANGE
    render(<RecentPostsCards />, {
      pageProps: {
        paginatedTopics: createPaginatedData([
          createRecentActiveForumTopic(),
          createRecentActiveForumTopic(),
        ]),
      },
    });

    // ASSERT
    expect(screen.getAllByRole('img').length).toEqual(2); // test the presence of user avatars
  });

  it('displays the topic title and the short message', () => {
    // ARRANGE
    const recentActiveForumTopic = createRecentActiveForumTopic();

    render(<RecentPostsCards />, {
      pageProps: {
        paginatedTopics: createPaginatedData([recentActiveForumTopic]),
      },
    });

    // ASSERT
    expect(screen.getByText(recentActiveForumTopic.title)).toBeVisible();
    expect(screen.getByText(recentActiveForumTopic.latestComment.body)).toBeVisible();
  });
});