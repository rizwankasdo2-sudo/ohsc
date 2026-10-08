<?php
if (!function_exists('isBlogLandingTestMode')) {
    function isBlogLandingTestMode()
    {
        global $blog_landing_test_mode;
        if (isset($blog_landing_test_mode)) {
            return (bool) $blog_landing_test_mode;
        }

        $uri = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH);
        $uri = is_string($uri) ? $uri : '';

        return $uri === '/OHSC-Blog-Test' || strpos($uri, '/OHSC-Blog-Test/') === 0;
    }
}

if (!function_exists('getBlogLandingBasePath')) {
    function getBlogLandingBasePath()
    {
        return isBlogLandingTestMode() ? '/OHSC-Blog-Test' : '/OHSC-Blog';
    }
}

if (!function_exists('getBlogListingProductionUrl')) {
    function getBlogListingProductionUrl()
    {
        global $base_url;
        $path = '/OHSC-Blog';
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('getBlogCategoryProductionUrl')) {
    function getBlogCategoryProductionUrl($slug)
    {
        $slug = trim((string) $slug);
        if ($slug === '') {
            return '#';
        }

        $slug = preg_replace('/[^a-z0-9\-]/i', '', $slug);
        if ($slug === '') {
            return '#';
        }

        global $base_url;
        $path = '/OHSC-Blog/' . $slug;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('getBlogPostProductionUrl')) {
    function getBlogPostProductionUrl($post, $db = null)
    {
        if ($db === null) {
            global $db;
        }

        $slug = '';
        if (is_array($post)) {
            $slug = trim((string) (isset($post['slug']) ? $post['slug'] : ''));
        } else {
            $slug = trim((string) $post);
        }

        $slug = preg_replace('/[^a-z0-9\-]/i', '', $slug);
        if ($slug === '') {
            return '#';
        }

        global $base_url;
        $path = '/OHSC-Blog/' . $slug;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('getBlogLandingCategoryMeta')) {
    function getBlogLandingCategoryMeta($index = 0)
    {
        $meta = [
            ['icon' => 'fa-briefcase', 'class' => 'career', 'desc' => 'Expert guidance to help you plan, grow and advance your career.'],
            ['icon' => 'fa-graduation-cap', 'class' => 'study', 'desc' => 'Tips and strategies for effective online learning and study success.'],
            ['icon' => 'fa-award', 'class' => 'professional', 'desc' => 'Build skills, earn certifications and strengthen your professional profile.'],
            ['icon' => 'fa-chart-column', 'class' => 'industry', 'desc' => 'Stay informed with trends, analysis and insights across key industries.'],
            ['icon' => 'fa-certificate', 'class' => 'qualifications', 'desc' => 'Guides on qualifications, certificates and accredited learning pathways.'],
            ['icon' => 'fa-gift', 'class' => 'free-courses', 'desc' => 'Free courses, enrolment tips and certificates from OHSC.'],
        ];

        return $meta[$index % count($meta)];
    }
}

if (!function_exists('getBlogLandingPublishedStatusCondition')) {
    function getBlogLandingPublishedStatusCondition($tableAlias = '')
    {
        $prefix = $tableAlias !== '' ? $tableAlias . '.' : '';

        return $prefix . 'status = 1';
    }
}

if (!function_exists('loadPublishedBlogPostBySlug')) {
    function loadPublishedBlogPostBySlug($db, $slug, $categorySlug = '')
    {
        $slug = preg_replace('/[^a-z0-9\-]/i', '', (string) $slug);
        if ($slug === '') {
            return null;
        }

        $slugSql = mysqli_real_escape_string($db, $slug);
        $statusSql = getBlogLandingPublishedStatusCondition('b');
        $categorySlug = preg_replace('/[^a-z0-9\-]/i', '', (string) $categorySlug);
        $categorySql = '';

        if ($categorySlug !== '') {
            $categorySlugSql = mysqli_real_escape_string($db, $categorySlug);
            $categorySql = " AND bc.slug = '$categorySlugSql'";
        }

        $result = $db->query("SELECT b.*, bc.name AS category_name, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE b.slug = '$slugSql'
            AND $statusSql
            $categorySql
            LIMIT 1");

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        return $result->fetch_array();
    }
}

if (!function_exists('getBlogListingLandingUrl')) {
    function getBlogListingLandingUrl()
    {
        global $base_url;
        $path = getBlogLandingBasePath();
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('getBlogCategoryLandingUrl')) {
    function getBlogCategoryLandingUrl($slug)
    {
        $slug = trim((string) $slug);
        if ($slug === '') {
            return '#';
        }

        $slug = preg_replace('/[^a-z0-9\-]/i', '', $slug);
        if ($slug === '') {
            return '#';
        }

        $path = getBlogLandingBasePath() . '/' . $slug;
        global $base_url;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('getBlogCategorySlugById')) {
    function getBlogCategorySlugById($db, $categoryId)
    {
        $categoryId = (int) $categoryId;
        if ($categoryId <= 0) {
            return '';
        }

        $row = $db->query('SELECT slug FROM blog_categories WHERE id = ' . $categoryId . ' LIMIT 1')->fetch_array();

        return $row ? trim((string) $row['slug']) : '';
    }
}

if (!function_exists('getBlogPostLandingUrl')) {
    function getBlogPostLandingUrl($post, $db = null)
    {
        if ($db === null) {
            global $db;
        }

        $slug = '';
        $categorySlug = '';

        if (is_array($post)) {
            $slug = trim((string) (isset($post['slug']) ? $post['slug'] : ''));
            if (!empty($post['category_slug'])) {
                $categorySlug = trim((string) $post['category_slug']);
            } elseif (!empty($post['blog_category'])) {
                $categorySlug = getBlogCategorySlugById($db, $post['blog_category']);
            }
        } else {
            $slug = trim((string) $post);
            if ($slug !== '' && $db) {
                $slugSql = mysqli_real_escape_string($db, preg_replace('/[^a-z0-9\-]/i', '', $slug));
                $row = $db->query("SELECT b.slug, bc.slug AS category_slug
                    FROM blog b
                    LEFT JOIN blog_categories bc ON bc.id = b.blog_category
                    WHERE b.slug = '$slugSql'
                    AND " . getBlogLandingPublishedStatusCondition('b') . "
                    LIMIT 1")->fetch_array();
                if ($row) {
                    $slug = trim((string) $row['slug']);
                    $categorySlug = trim((string) $row['category_slug']);
                }
            }
        }

        $slug = preg_replace('/[^a-z0-9\-]/i', '', $slug);

        if ($slug === '') {
            return '#';
        }

        $path = getBlogLandingBasePath() . '/' . $slug;
        global $base_url;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . $path;
        }

        return $path;
    }
}

if (!function_exists('getBlogLandingBlogImagePublicUrl')) {
    function getBlogLandingBlogImagePublicUrl($filename)
    {
        global $base_url, $pdots;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . '/images/blog/' . $filename;
        }

        return $pdots . 'images/blog/' . $filename;
    }
}

if (!function_exists('renderBlogStoredHtml')) {
    function renderBlogStoredHtml($html)
    {
        $html = (string) $html;
        if ($html === '') {
            return '';
        }

        $html = stripslashes($html);

        if (strpos($html, '&lt;') !== false || strpos($html, '&gt;') !== false) {
            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $html;
    }
}

if (!function_exists('slugifyBlogArticleHeading')) {
    function slugifyBlogArticleHeading($text)
    {
        $text = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim($text, '-') !== '' ? trim($text, '-') : 'section';
    }
}

if (!function_exists('prepareBlogArticleContent')) {
    function prepareBlogArticleContent($html)
    {
        $html = renderBlogStoredHtml($html);
        if ($html === '') {
            return [
                'content' => '',
                'headings' => [],
            ];
        }

        $headings = [];
        $usedIds = [];

        $content = preg_replace_callback(
            '/<h2(\s[^>]*)?>(.*?)<\/h2>/is',
            function ($matches) use (&$headings, &$usedIds) {
                $attrs = isset($matches[1]) ? $matches[1] : '';
                $inner = $matches[2];
                $text = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                if ($text === '') {
                    return $matches[0];
                }

                $baseId = slugifyBlogArticleHeading($text);
                $id = $baseId;
                $counter = 2;
                while (in_array($id, $usedIds, true)) {
                    $id = $baseId . '-' . $counter;
                    $counter++;
                }
                $usedIds[] = $id;
                $headings[] = [
                    'id' => $id,
                    'text' => $text,
                ];

                $attrs = preg_replace('/\s*id=(["\']).*?\1/i', '', $attrs);

                return '<h2 id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"' . $attrs . '>' . $inner . '</h2>';
            },
            $html
        );

        return [
            'content' => $content,
            'headings' => $headings,
        ];
    }
}

if (!function_exists('getBlogLandingMainImageUrl')) {
    function getBlogLandingMainImageUrl($post)
    {
        global $base_url, $pdots;
        if (!empty($base_url)) {
            $imageBase = rtrim($base_url, '/') . '/images/blog/';
        } else {
            $imageBase = $pdots . 'images/blog/';
        }

        $filename = isset($post['image']) ? trim((string) $post['image']) : '';
        if ($filename === '') {
            return $imageBase;
        }

        return $imageBase . $filename;
    }
}

if (!function_exists('getBlogLandingImage272_170Url')) {
    function getBlogLandingImage272_170Url($post)
    {
        global $base_url, $pdots;
        if (!empty($base_url)) {
            $imageBase = rtrim($base_url, '/') . '/images/blog/';
        } else {
            $imageBase = $pdots . 'images/blog/';
        }

        $filename = isset($post['image_272_170']) ? trim((string) $post['image_272_170']) : '';
        if ($filename === '') {
            return $imageBase;
        }

        return $imageBase . $filename;
    }
}

if (!function_exists('getBlogLandingImage80_64Url')) {
    function getBlogLandingImage80_64Url($post)
    {
        global $base_url, $pdots;
        if (!empty($base_url)) {
            $imageBase = rtrim($base_url, '/') . '/images/blog/';
        } else {
            $imageBase = $pdots . 'images/blog/';
        }

        $filename = isset($post['image_80_64']) ? trim((string) $post['image_80_64']) : '';
        if ($filename === '') {
            return $imageBase;
        }

        return $imageBase . $filename;
    }
}

if (!function_exists('getBlogLandingPopularThumbUrl')) {
    function getBlogLandingPopularThumbUrl($post)
    {
        return getBlogLandingImage80_64Url($post);
    }
}

if (!function_exists('getBlogLandingPostThumbUrl')) {
    function getBlogLandingPostThumbUrl($post)
    {
        $filename = resolveBlogLandingPostImageFilename($post, ['image_80_64', 'image_240_181', 'image']);
        if ($filename === '') {
            return '';
        }

        global $base_url, $pdots;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . '/images/blog/' . $filename;
        }

        return $pdots . 'images/blog/' . $filename;
    }
}

if (!function_exists('formatBlogLandingPublishDate')) {
    function formatBlogLandingPublishDate($publishDate)
    {
        if (empty($publishDate)) {
            return '';
        }

        $timestamp = strtotime($publishDate);
        if ($timestamp === false) {
            return '';
        }

        return date('M j, Y', $timestamp);
    }
}

if (!function_exists('loadBlogLandingPopularArticles')) {
    function loadBlogLandingPopularArticles($db, $limit = 0, $blogCategoryId = 0)
    {
        $articles = [];
        $limitSql = '';
        $limit = (int) $limit;
        $blogCategoryId = (int) $blogCategoryId;
        $categorySql = '';

        if ($limit > 0) {
            $limitSql = ' LIMIT ' . $limit;
        }

        if ($blogCategoryId > 0) {
            $categorySql = ' AND blog_category = ' . $blogCategoryId;
        }

        $popularSql = "SELECT b.id, b.title, b.slug, b.publish_date, b.alt, b.image_80_64, b.blog_category, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE " . getBlogLandingPublishedStatusCondition('b') . " AND b.popular = 1$categorySql
            ORDER BY b.publish_date DESC
            $limitSql";
        $popularResult = $db->query($popularSql);
        if ($popularResult) {
            while ($row = $popularResult->fetch_array()) {
                $articles[] = $row;
            }
        }

        return $articles;
    }
}

if (!function_exists('getBlogCategoryLabel')) {
    function getBlogCategoryLabel($db, $blog_category_id)
    {
        if (empty($blog_category_id)) {
            return 'BLOG';
        }

        $row = $db->query('SELECT name FROM blog_categories WHERE id = ' . (int) $blog_category_id . ' LIMIT 1')->fetch_array();

        return $row ? strtoupper($row['name']) : 'BLOG';
    }
}

if (!function_exists('getBlogLandingCategoryMetaForCategoryId')) {
    function getBlogLandingCategoryMetaForCategoryId($db, $categoryId)
    {
        static $categoryMetaMap = null;

        if ($categoryMetaMap === null) {
            $categoryMetaMap = [];
            $result = $db->query('SELECT id FROM blog_categories ORDER BY `order` ASC, name ASC LIMIT 6');
            if ($result) {
                $index = 0;
                while ($row = $result->fetch_array()) {
                    $categoryMetaMap[(int) $row['id']] = getBlogLandingCategoryMeta($index);
                    $index++;
                }
            }
        }

        $categoryId = (int) $categoryId;
        if (isset($categoryMetaMap[$categoryId])) {
            return $categoryMetaMap[$categoryId];
        }

        return getBlogLandingCategoryMeta(0);
    }
}

if (!function_exists('normalizeBlogLandingImageFilename')) {
    function normalizeBlogLandingImageFilename($filename)
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return '';
        }

        return preg_replace('/(\.webp)+$/i', '.webp', $filename);
    }
}

if (!function_exists('getBlogLandingImageFilenameCandidates')) {
    function getBlogLandingImageFilenameCandidates($filename)
    {
        $filename = trim((string) $filename);
        if ($filename === '') {
            return [];
        }

        $candidates = [$filename];
        $normalized = normalizeBlogLandingImageFilename($filename);
        if ($normalized !== '' && $normalized !== $filename) {
            $candidates[] = $normalized;
        }

        return array_values(array_unique($candidates));
    }
}

if (!function_exists('pickBlogLandingImageFilename')) {
    function pickBlogLandingImageFilename($rawFilename)
    {
        $uploadDir = dirname(__DIR__) . '/images/blog/';

        foreach (getBlogLandingImageFilenameCandidates($rawFilename) as $candidate) {
            if ($candidate !== '' && is_file($uploadDir . $candidate)) {
                return $candidate;
            }
        }

        $normalized = normalizeBlogLandingImageFilename($rawFilename);
        if ($normalized !== '') {
            return $normalized;
        }

        return trim((string) $rawFilename);
    }
}

if (!function_exists('getBlogLandingPostImageDiskPath')) {
    function getBlogLandingPostImageDiskPath($filename)
    {
        $filename = pickBlogLandingImageFilename($filename);
        if ($filename === '') {
            return '';
        }

        return dirname(__DIR__) . '/images/blog/' . $filename;
    }
}

if (!function_exists('resolveBlogLandingPostImageFilename')) {
    function resolveBlogLandingPostImageFilename($post, $fieldCandidates)
    {
        foreach ((array) $fieldCandidates as $field) {
            if (empty($post[$field])) {
                continue;
            }

            $filename = pickBlogLandingImageFilename($post[$field]);
            if ($filename !== '') {
                return $filename;
            }
        }

        return '';
    }
}

if (!function_exists('getBlogLandingPostImageUrl')) {
    function getBlogLandingPostImageUrl($post, $preferredField, $fallbackField = 'image')
    {
        $fields = [$preferredField];
        if ($fallbackField !== $preferredField) {
            $fields[] = $fallbackField;
        }
        if (!in_array('image', $fields, true)) {
            $fields[] = 'image';
        }

        $filename = resolveBlogLandingPostImageFilename($post, $fields);

        if ($filename === '') {
            return '';
        }

        global $base_url, $pdots;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . '/images/blog/' . $filename;
        }

        return $pdots . 'images/blog/' . $filename;
    }
}

if (!function_exists('getBlogLandingPostImageUrlFromFields')) {
    function getBlogLandingPostImageUrlFromFields($post, array $fieldCandidates)
    {
        $filename = resolveBlogLandingPostImageFilename($post, $fieldCandidates);
        if ($filename === '') {
            return '';
        }

        global $base_url, $pdots;
        if (!empty($base_url)) {
            return rtrim($base_url, '/') . '/images/blog/' . $filename;
        }

        return $pdots . 'images/blog/' . $filename;
    }
}

if (!function_exists('getBlogLandingPostExcerpt')) {
    function getBlogLandingPostExcerpt($post, $length = 140)
    {
        $text = '';
        if (!empty($post['short_content'])) {
            $text = $post['short_content'];
        } elseif (!empty($post['excerpt'])) {
            $text = $post['excerpt'];
        }

        $text = trim(strip_tags($text));
        if ($text === '') {
            return '';
        }

        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }
}

if (!function_exists('getBlogLandingMinutesRead')) {
    function getBlogLandingMinutesRead($post)
    {
        if (!empty($post['minutes_read'])) {
            $minutes = (int) $post['minutes_read'];

            return max(1, $minutes) . ' min read';
        }

        return '';
    }
}

if (!function_exists('loadBlogLandingLatestPostIds')) {
    function loadBlogLandingLatestPostIds($db, $limit = 4)
    {
        $limit = max(1, (int) $limit);
        $ids = [];
        $result = $db->query('SELECT id FROM blog WHERE ' . getBlogLandingPublishedStatusCondition() . ' ORDER BY publish_date DESC LIMIT ' . $limit);
        if ($result) {
            while ($row = $result->fetch_array()) {
                $ids[] = (int) $row['id'];
            }
        }

        return $ids;
    }
}

if (!function_exists('loadBlogLandingFeaturedCenterPosts')) {
    function loadBlogLandingFeaturedCenterPosts($db, $limit = 4, $excludeIds = [])
    {
        $limit = max(1, (int) $limit);

        $sql = "SELECT b.id, b.title, b.slug, b.publish_date, b.alt, b.image, b.image_272_170, b.image_240_181,
                b.image_80_64, b.short_content, b.excerpt, b.minutes_read, b.blog_category, b.featured, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE " . getBlogLandingPublishedStatusCondition('b') . "
            AND b.featured = 1
            ORDER BY b.publish_date DESC
            LIMIT $limit";

        $posts = [];
        $result = $db->query($sql);
        if ($result) {
            while ($row = $result->fetch_array()) {
                $posts[] = $row;
            }
        }

        return $posts;
    }
}

if (!function_exists('loadBlogLandingLatestArticles')) {
    function loadBlogLandingLatestArticles($db, $limit = 50, $blogCategoryId = 0, $excludeId = 0)
    {
        $limit = max(1, (int) $limit);
        $blogCategoryId = (int) $blogCategoryId;
        $excludeId = (int) $excludeId;
        $categorySql = '';
        $excludeSql = '';

        if ($blogCategoryId > 0) {
            $categorySql = ' AND b.blog_category = ' . $blogCategoryId;
        }

        if ($excludeId > 0) {
            $excludeSql = ' AND b.id != ' . $excludeId;
        }

        $sql = "SELECT b.id, b.title, b.slug, b.publish_date, b.alt, b.image_272_170, b.minutes_read, b.blog_category, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE " . getBlogLandingPublishedStatusCondition('b') . "
            AND (b.popular = 0 OR b.popular IS NULL)
            AND (b.featured = 0 OR b.featured IS NULL)
            $categorySql
            $excludeSql
            ORDER BY b.id DESC
            LIMIT $limit";

        $posts = [];
        $result = $db->query($sql);
        if ($result) {
            while ($row = $result->fetch_array()) {
                $posts[] = $row;
            }
        }

        return $posts;
    }
}

if (!function_exists('loadBlogAllCoursesArticles')) {
    function loadBlogAllCoursesArticles($db, $limit = 50)
    {
        $limit = max(1, (int) $limit);

        $sql = "SELECT b.id, b.title, b.slug, b.publish_date, b.alt, b.image_272_170, b.minutes_read, b.blog_category, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE " . getBlogLandingPublishedStatusCondition('b') . "
            AND b.show_in_all_courses = 1
            ORDER BY b.publish_date DESC
            LIMIT $limit";

        $posts = [];
        $result = $db->query($sql);
        if ($result) {
            while ($row = $result->fetch_array()) {
                $posts[] = $row;
            }
        }

        return $posts;
    }
}

if (!function_exists('loadBlogArticlesByCourseCategoryId')) {
    function loadBlogArticlesByCourseCategoryId($db, $courseCategoryId, $limit = 50)
    {
        $courseCategoryId = (int) $courseCategoryId;
        $limit = max(1, (int) $limit);

        if ($courseCategoryId <= 0) {
            return [];
        }

        $sql = "SELECT b.id, b.title, b.slug, b.publish_date, b.alt, b.image_272_170, b.minutes_read, b.blog_category, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE " . getBlogLandingPublishedStatusCondition('b') . "
            AND (
                b.category = '$courseCategoryId'
                OR FIND_IN_SET('$courseCategoryId', REPLACE(b.category, ' ', ''))
            )
            ORDER BY b.publish_date DESC
            LIMIT $limit";

        $posts = [];
        $result = $db->query($sql);
        if ($result) {
            while ($row = $result->fetch_array()) {
                $posts[] = $row;
            }
        }

        return $posts;
    }
}
