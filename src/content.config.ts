import { defineCollection } from 'astro:content';
import { glob } from 'astro/loaders';
import { z } from 'astro/zod';

const news = defineCollection({
  loader: glob({
    base: './src/content/news',
    pattern: '**/*.md',
    retainBody: true,
    generateId: ({ entry }) => entry.replace(/\.md$/, ''),
  }),
  schema: z.object({
    title: z.string(),
    description: z.string(),
    category: z.enum([
      'Jobs',
      'Education',
      'Government Schemes',
      'Training',
      'Job Fairs',
      'Community',
      'Technology',
      'Career',
    ]),
    publishedAt: z.coerce.date(),
    updatedAt: z.coerce.date().optional(),
    image: z.string(),
    imageAlt: z.string().default(''),
    author: z.string().default('DeafJobsIndia Desk'),
    authorId: z.string().default('desk'),
    status: z.enum(['draft', 'published', 'scheduled']).default('published'),
    tags: z.array(z.string()).default([]),
    featured: z.boolean().default(false),
    trending: z.boolean().default(false),
    breaking: z.boolean().default(false),
    draft: z.boolean().default(false),
    demo: z.boolean().default(false),

    // Publishing / verification
    verificationStatus: z.enum(['pending', 'verified']).default('pending'),
    verifiedAt: z.coerce.date().optional(),
    sourceName: z.string().optional(),
    sourceUrl: z.string().url().optional(),
    sourceType: z.enum([
      'Official',
      'Employer',
      'Education Institution',
      'Community Organization',
      'Press Release',
      'Original Reporting',
      'Other',
    ]).optional(),
    sourceNote: z.string().optional(),

    // Optional useful reader details
    actionLabel: z.string().optional(),
    actionUrl: z.string().url().optional(),
    deadline: z.coerce.date().optional(),
    location: z.string().optional(),
  }),
});

export const collections = { news };
