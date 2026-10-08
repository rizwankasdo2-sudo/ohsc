<?php

if (!function_exists('getBlogCategoryArticlesWhereSql')) {
    function getBlogCategoryArticlesWhereSql($categoryId)
    {
        $categoryId = (int) $categoryId;

        return getBlogLandingPublishedStatusCondition('b') . '
            AND b.blog_category = ' . $categoryId . '
            AND (b.popular = 0 OR b.popular IS NULL)';
    }
}

if (!function_exists('getBlogCategoryArticlesOrderSql')) {
    function getBlogCategoryArticlesOrderSql($sort)
    {
        if ($sort === 'oldest') {
            return 'b.last_updated ASC, b.id ASC';
        }

        return 'b.last_updated DESC, b.id DESC';
    }
}

if (!function_exists('getBlogLandingImage240_181Url')) {
    function getBlogLandingImage240_181Url($post)
    {
        global $base_url, $pdots;
        if (!empty($base_url)) {
            $imageBase = rtrim($base_url, '/') . '/images/blog/';
        } else {
            $imageBase = $pdots . 'images/blog/';
        }

        $filename = isset($post['image_240_181']) ? trim((string) $post['image_240_181']) : '';
        if ($filename === '') {
            return $imageBase;
        }

        return $imageBase . $filename;
    }
}

if (!function_exists('formatBlogCategoryLastUpdatedDate')) {
    function formatBlogCategoryLastUpdatedDate($lastUpdated)
    {
        if ($lastUpdated === null || $lastUpdated === '') {
            return '';
        }

        $lastUpdated = trim((string) $lastUpdated);
        if ($lastUpdated === '' || strpos($lastUpdated, '0000-00-00') === 0) {
            return '';
        }

        $timestamp = strtotime($lastUpdated);
        if (!$timestamp) {
            return '';
        }

        return date('M j, Y', $timestamp);
    }
}

if (!function_exists('countBlogCategoryArticles')) {
    function countBlogCategoryArticles($db, $categoryId)
    {
        $whereSql = getBlogCategoryArticlesWhereSql($categoryId);
        $result = $db->query('SELECT COUNT(*) AS total FROM blog b WHERE ' . $whereSql);
        $row = $result ? $result->fetch_array() : null;

        return $row ? (int) $row['total'] : 0;
    }
}

if (!function_exists('loadBlogCategoryArticles')) {
    function loadBlogCategoryArticles($db, $categoryId, $page = 1, $perPage = 5, $sort = 'latest')
    {
        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $offset = ($page - 1) * $perPage;
        $sort = ($sort === 'oldest') ? 'oldest' : 'latest';
        $whereSql = getBlogCategoryArticlesWhereSql($categoryId);
        $orderSql = getBlogCategoryArticlesOrderSql($sort);

        $sql = "SELECT b.id, b.title, b.slug, b.last_updated, b.minutes_read, b.short_content, b.alt, b.image_240_181, b.blog_category, bc.slug AS category_slug
            FROM blog b
            LEFT JOIN blog_categories bc ON bc.id = b.blog_category
            WHERE $whereSql
            ORDER BY $orderSql
            LIMIT $offset, $perPage";

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

if (!function_exists('getBlogCategoryArticlesSummaryText')) {
    function getBlogCategoryArticlesSummaryText($page, $perPage, $total)
    {
        $total = (int) $total;
        if ($total <= 0) {
            return 'Showing 0 articles';
        }

        $page = max(1, (int) $page);
        $perPage = max(1, (int) $perPage);
        $start = (($page - 1) * $perPage) + 1;
        $end = min($page * $perPage, $total);

        return 'Showing ' . $start . '&ndash;' . $end . ' of ' . $total . ' articles';
    }
}

if (!function_exists('renderBlogCategoryArticlesHtml')) {
    function renderBlogCategoryArticlesHtml($posts, $categoryName)
    {
        if (empty($posts)) {
            return '<p class="bcat-articles-empty">No articles to show in this category yet.</p>';
        }

        ob_start();
        foreach ($posts as $post) {
            $post_url = getBlogPostLandingUrl($post);
            $post_image = getBlogLandingImage240_181Url($post);
            $post_alt = trim((string) $post['alt']);
            if ($post_alt === '') {
                $post_alt = $post['title'];
            }
            $post_date = formatBlogCategoryLastUpdatedDate($post['last_updated']);
            $post_read = getBlogLandingMinutesRead($post);
            $post_short_content = trim((string) $post['short_content']);
            ?>
            <article class="bcat-article">
                <a href="<?php echo htmlspecialchars($post_url); ?>" class="bcat-article__image">
                    <img src="<?php echo htmlspecialchars($post_image); ?>" alt="<?php echo htmlspecialchars($post_alt); ?>" width="240" height="181">
                </a>
                <div class="bcat-article__body">
                    <span class="bcat-article__label"><?php echo htmlspecialchars($categoryName); ?></span>
                    <h3 class="bcat-article__title"><a href="<?php echo htmlspecialchars($post_url); ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                    <div class="bcat-article__meta">
                        <?php if ($post_date !== '') { ?><span><i class="fa-regular fa-calendar"></i><?php echo htmlspecialchars($post_date); ?></span><?php } ?>
                        <?php if ($post_read !== '') { ?><span><i class="fa-regular fa-clock"></i><?php echo htmlspecialchars($post_read); ?></span><?php } ?>
                    </div>
                    <?php if ($post_short_content !== '' && trim(strip_tags($post_short_content)) !== '') { ?>
                    <div class="bcat-article__excerpt"><?php echo $post_short_content; ?></div>
                    <?php } ?>
                    <a href="<?php echo htmlspecialchars($post_url); ?>" class="bcat-article__more">Read More <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </article>
            <?php
        }

        return ob_get_clean();
    }
}

if (!function_exists('renderBlogCategoryPaginationHtml')) {
    function renderBlogCategoryPaginationHtml($page, $perPage, $total)
    {
        $total = (int) $total;
        $perPage = max(1, (int) $perPage);
        $totalPages = (int) ceil($total / $perPage);

        if ($totalPages <= 1) {
            return '';
        }

        $page = max(1, min((int) $page, $totalPages));

        ob_start();
        ?>
        <nav class="bcat-pagination" aria-label="Pagination">
            <?php if ($page > 1) { ?>
            <a href="#" class="bcat-pagination__link bcat-pagination__prev" data-page="<?php echo $page - 1; ?>"><i class="fa-solid fa-arrow-left"></i> Prev</a>
            <?php }

            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);

            if ($startPage > 1) {
                ?>
            <a href="#" class="bcat-pagination__num" data-page="1">1</a>
                <?php
                if ($startPage > 2) {
                    echo '<span class="bcat-pagination__ellipsis">&hellip;</span>';
                }
            }

            for ($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++) {
                $isActive = ($pageNumber === $page);
                ?>
            <a href="#" class="bcat-pagination__num<?php echo $isActive ? ' is-active' : ''; ?>" data-page="<?php echo $pageNumber; ?>"<?php echo $isActive ? ' aria-current="page"' : ''; ?>><?php echo $pageNumber; ?></a>
                <?php
            }

            if ($endPage < $totalPages) {
                if ($endPage < $totalPages - 1) {
                    echo '<span class="bcat-pagination__ellipsis">&hellip;</span>';
                }
                ?>
            <a href="#" class="bcat-pagination__num" data-page="<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a>
                <?php
            }

            if ($page < $totalPages) { ?>
            <a href="#" class="bcat-pagination__link bcat-pagination__next" data-page="<?php echo $page + 1; ?>">Next <i class="fa-solid fa-arrow-right"></i></a>
            <?php } ?>
        </nav>
        <?php

        return ob_get_clean();
    }
}
