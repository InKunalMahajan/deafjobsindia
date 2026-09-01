import authorData from './authors.json';

export interface AuthorProfile {
  id: string;
  name: string;
  role: string;
  bio: string;
  avatar: string;
}

export const authors = authorData as Record<string, AuthorProfile>;

export function getAuthorProfile(id?: string, fallbackName = 'DeafJobsIndia Desk'): AuthorProfile {
  if (id && authors[id]) return authors[id];

  return {
    id: id || 'guest',
    name: fallbackName,
    role: 'Contributor',
    bio: `Articles and updates published for DeafJobsIndia by ${fallbackName}.`,
    avatar: '/images/author-desk.svg',
  };
}
