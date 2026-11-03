# Career resources

Public hub: `/career-expert/resources`. The former `/tools/resources/` URL redirects. The hierarchy contains exactly 30 pages: the hub, seven categories, and the 22 listed subcategories. Download examples remain items, not separate pages. Admin: `/admin/career-resources` → Add resource.

Add title, description, category, career goal, order and optional thumbnail. Upload one or more files and select Publish. A published resource must have at least one file. Existing files can be removed and replacements added when editing. Hide removes both the public listing and download access.

Allowed downloads: PDF, DOCX, XLSX, PPTX, TXT and ZIP; 10 MB per file, up to five files per save, subject to PHP server limits. Thumbnail: WebP, exactly 640 × 400, maximum 5 MB. Office containers and ZIP contents are validated; macro payloads, scripts, traversal paths and oversized archives are rejected.

Deployment needs PHP fileinfo and zip extensions, writable `tools/resource-files/` and `tools/uploads/`, and the committed Apache rules. Original download files must remain inaccessible directly; only `tools/resources/download.php?id=...` serves published files. The root rewrite denial and resource-files/.htaccess enforce this on Apache. Database tables are created additively on first use. No sample downloads are automatically published.

The Resources menu entry is registered in Tools Manager. Existing visibility/title/order changes are preserved. Tools Manager → Edit Resources also manages the shared hero illustration. Resource-specific thumbnails/files live in the separate Career resources admin section.

Run `php tools/tests/resources.test.php` for validation and database tests. Set `OHSC_RESOURCE_TEST_AUTH` to local staging Basic credentials to include localhost HTTP download checks; credentials are not stored in the test. Temporary rows/files are cleaned up.

## Resource pages and SEO

`/admin/resource-pages` manages every hub/category/subcategory page: custom slug, heading/introduction, meta title, description, keywords and robots. Parent slug changes update descendant URLs; old URLs redirect. The staging site's master noindex and password lock override indexable robots selections until the staging policy is deliberately changed. Resource SEO settings remain stored.

Assign download items to a category and optional subcategory in the resource editor. FAQs need an answer instead of an attachment. Only the requested 30 page types are seeded; the page manager has no Add page action. Default seed values do not overwrite admin edits.

Run `php tools/tests/resource-pages.test.php` for hierarchy/SEO/alias/FAQ tests; the same optional local test-auth environment variable enables 30-page HTTP checks.
