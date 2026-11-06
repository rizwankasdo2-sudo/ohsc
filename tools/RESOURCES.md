# Career Expert resources and tools

## Routes and scope

The canonical tools hub is `/career-expert`. The navbar has a single Tools link.
The working tools use `/career-expert/cv-builder`, `/career-expert/career-quiz`,
`/career-expert/typing-speed-test`, `/career-expert/emotional-intelligence-test`
and `/career-expert/interview-questions`. Existing interview industry collections use
the workbook's `/interview-questions/industries/...` routes. Customer Service uses
`/interview-questions/customer-service-interview-questions`.

The resource hub is `/career-expert/resources`. Previously agreed category and
subcategory browsing remains available. Nine real starter resources also have the
preferred individual URLs and initial keyword sets listed in
`includes/preferred-resource-pages.json`, using stable workbook IDs.
CV and cover-letter template/example collections use their preferred
`/career-expert/cv-and-cover-letters/...` owners. Earlier resource URLs redirect
to these same records. Existing `/tools/` and `/resources/` addresses redirect. Admin slug edits retain
aliases. Tool and resource URL collisions are rejected.

The workbook's remaining planned topics are not automatically published as empty
pages. Career Guides, Career Advice and CV/cover-letter directories link to real,
already-published articles at their original owners.

## Admin

Website Management has one Tools Manager menu entry. Its internal management
links provide access to tools, metadata, question banks, professions and resources.

`/admin/tool-pages` edits all tools and directory SEO. The hub's edit screen also
contains direct image upload controls. Individual Tool Edit screens retain their
inline SEO fields and image uploads.

`/admin/resource-pages` edits headings, introductions, custom slugs, meta title,
description, keywords and robots, plus independent page/featured images. Create
resource page associates a new page with an existing downloadable resource;
duplicate content owners, invalid slugs and URL collisions are rejected.

`/admin/career-resources` manages resource content/files, publish/hide, soft delete
and restore. Category and subcategory page editors include their resource CRUD
controls. Deleted/hidden resources cannot be downloaded. Files remain stored for
recovery. Custom detail pages show a preview, real format buttons and use instructions.

Profession images are uploaded directly in `/admin/interview-professions`:
genuine WebP only, exactly 560 x 400 px, maximum 5 MB. Names are compared case
insensitively across professions; stored filenames are random. The form preserves
existing images when no replacement is selected.

Other tool/page image controls show each slot's exact dimensions, preview and 5 MB
limit. Resources hero/toolkit images are 640 x 440; resource and featured cards are
640 x 400. Featured slots are independent. Delete/hide and restore-default actions
remain available for resource images. Course and article imagery also follows its
existing CMS record; the tools hub supports WebP overrides for displayed images.

## Files and deployment

Allowed new downloads: PDF, DOCX, XLSX, PPTX and ZIP; 10 MB per file, maximum five
per save. Genuine formats, archive paths, expanded sizes and executable/macro
payloads are validated. TXT uploads are no longer offered.

Twenty-four starter resources include printable PDF and editable Word documents.
The DOCX files are bundled, so published downloads do not require server-side Word
generation. Only untouched seed-owned TXT database records migrate to their DOCX
companions; admin-created content, edits, hiding and deletion are preserved.
The builder in `scripts/build-resource-starters.py` maintains the seed sources.

Use PHP fileinfo and ZIP extensions for validating Office/ZIP uploads, and writable
`tools/resource-files/` and `tools/uploads/` directories. Deploy committed Apache
rules. Direct access to original files, seeds and scripts is denied; the public
publication-aware download endpoint remains `/tools/resources/download.php?id=...`.
Database changes are additive and run on first use. No credentials are shipped by
these updates. Preserve the environment's site lock and password configuration.

Meta tags reflect saved admin values. Staging continues to send a site-wide
X-Robots-Tag noindex header and require its existing login, independently of page
metadata. Changing metadata alone does not make staging indexable.

## Verification

Run the PHP tests under `tests/`: resources, resource-pages, tool-pages,
resource-images (GD), update-review, admin-update-render (hub/resource/profession)
and admin-inline. HTTP tests accept `OHSC_RESOURCE_TEST_AUTH` from the environment;
credentials are never stored in test source. Fixtures clean up after themselves.

The November update was checked across 60 local routes, legacy redirects, real
DOCX packages, admin creation/metadata/image controls and 360/390/768/1440 CSS-pixel
layouts. Normal vertical page scrolling remains; page-wide horizontal overflow
and nested carousel vertical scrollbars are prevented.
