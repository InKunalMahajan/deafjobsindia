# Advertising-ready layout

Step 8 includes reusable ad positions but does not install or activate an advertising network.

Configuration:

`src/data/site.ts`

```ts
export const adConfig = {
  enabled: false,
  showPlaceholders: true,
  publisherId: '',
};
```

## Current placements

### Header
`header-leaderboard`

Desktop placement beside the DeafJobsIndia brand.

### Homepage
`homepage-infeed`

Between the Latest News area and category newsroom sections.

### Sidebar
`sidebar-rectangle`

Below Trending.

### Article
`article-top`

Between the article hero image and sharing/body area.

## Production recommendations

Before enabling advertisements:
1. obtain approval from the chosen ad provider;
2. update Content Security Policy only for required provider domains;
3. add the provider script once in the base layout;
4. replace the pending markup inside `AdSlot.astro`;
5. set `adConfig.enabled = true`;
6. set `showPlaceholders = false`;
7. test Core Web Vitals and mobile layout shift.

The theme deliberately does not ship with an AdSense publisher ID or third-party advertising script.
