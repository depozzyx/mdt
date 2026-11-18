# Hidden content

Content the client asked to take off the site for now. `content-pages.php` only reads
`tools/content/*.json`, so nothing in this folder is applied. The matching posts are drafts in the
local DB (restore by publishing them in wp-admin); at a fresh-dump cutover they simply aren't created.

- **Götessons** (2026-11-18, no longer working with them): supplier page + Götessons Sandy sofa.
  `gotessons.json` holds the cut posts/media and how to put them back.
- **Acoustic booths of MDD, ISKU, Bralco, Götessons** (2026-11-12): MDD Hako, ISKU Vetrospace S,
  Bralco B-mybox, Götessons ScreenIT A30 Booth. Only their photos are kept here (`media/p-*-hako-*`,
  `p-isku-vetrospace-s-1`, `p-bralco-b-mybox-*`, `p-gotessons-screenit-a30-booth-*`); the product
  text is in the drafted posts.
