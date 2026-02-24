<?php
header("Content-Type: application/xml; charset=utf-8");

// Timezone & base URL
date_default_timezone_set('Asia/Kolkata');
$baseUrl = "https://PavanCashLoot.xyz/Loots/";

// Auto-scan PHP files from current directory (excluding hidden and admin files)
$allFiles = scandir(__DIR__);
$pages = [];
foreach ($allFiles as $file) {
    if (
        pathinfo($file, PATHINFO_EXTENSION) === 'php' &&
        !str_starts_with($file, '.') &&
        !str_starts_with($file, 'admin') &&
        $file !== 'sitemap.php'
    ) {
        $pages[] = $file;
    }
}

// Add any manual or external links
$manualPages = [
    // "external-page.php",
];

// Merge all pages
$pages = array_unique(array_merge($pages, $manualPages));

// Start XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<!-- Sitemap generated on <?= date("Y-m-d H:i:s A") ?> IST -->
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($pages as $page): ?>
  <?php
    $loc = $baseUrl . $page;
    $lastmod = date('Y-m-d', filemtime(__DIR__ . '/' . $page));
    $priority = ($page === 'index.php') ? "1.0" : "0.80";
  ?>
  <url>
    <loc><?= htmlspecialchars($loc) ?></loc>
    <lastmod><?= $lastmod ?></lastmod>
    <changefreq>daily</changefreq>
    <priority><?= $priority ?></priority>
  </url>
<?php endforeach; ?>
</urlset>